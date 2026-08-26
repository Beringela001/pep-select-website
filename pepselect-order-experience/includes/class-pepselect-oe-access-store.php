<?php

defined( 'ABSPATH' ) || exit;

final class PepSelect_OE_Access_Store {
	public const TABLE_VERSION = '1';

	private wpdb $db;
	private string $table;

	public function __construct( wpdb $db ) {
		$this->db    = $db;
		$this->table = $db->prefix . 'pepselect_order_access';
	}

	public static function install(): void {
		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$table   = $wpdb->prefix . 'pepselect_order_access';
		$charset = $wpdb->get_charset_collate();
		$sql     = "CREATE TABLE {$table} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			order_id bigint(20) unsigned NOT NULL,
			token_hash char(64) NOT NULL,
			snapshot_version smallint(5) unsigned NOT NULL DEFAULT 1,
			snapshot_hash char(64) NOT NULL,
			snapshot_json longtext NOT NULL,
			created_at datetime NOT NULL,
			updated_at datetime NOT NULL,
			revoked_at datetime NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY order_id (order_id),
			UNIQUE KEY token_hash (token_hash)
		) {$charset};";
		dbDelta( $sql );
		update_option( 'pepselect_oe_table_version', self::TABLE_VERSION, false );
	}

	public static function generate_token(): string {
		return rtrim( strtr( base64_encode( random_bytes( 32 ) ), '+/', '-_' ), '=' );
	}

	public static function token_hash( string $token ): string {
		return hash( 'sha256', $token );
	}

	public function table_exists(): bool {
		return $this->table === $this->db->get_var( $this->db->prepare( 'SHOW TABLES LIKE %s', $this->db->esc_like( $this->table ) ) );
	}

	public function find_by_order( int $order_id ): ?array {
		$row = $this->db->get_row(
			$this->db->prepare( "SELECT * FROM {$this->table} WHERE order_id = %d LIMIT 1", $order_id ),
			ARRAY_A
		);
		return is_array( $row ) ? $row : null;
	}

	public function find_active_by_token( string $token ): ?array {
		$hash = self::token_hash( $token );
		$row  = $this->db->get_row(
			$this->db->prepare( "SELECT * FROM {$this->table} WHERE token_hash = %s AND revoked_at IS NULL LIMIT 1", $hash ),
			ARRAY_A
		);
		if ( ! is_array( $row ) || ! hash_equals( (string) $row['token_hash'], $hash ) ) {
			return null;
		}
		return $row;
	}

	/**
	 * Store the immutable Ops snapshot. WordPress retains only a SHA-256 token
	 * hash; Ops is the sole keeper of the printable token.
	 */
	public function upsert( int $order_id, array $snapshot, string $snapshot_hash, ?string $access_token, bool $rotate ): array|WP_Error {
		$existing = $this->find_by_order( $order_id );
		$now      = current_time( 'mysql', true );
		$token    = $access_token ? trim( $access_token ) : '';

		if ( $existing && ! $rotate ) {
			if ( '' === $token || ! hash_equals( (string) $existing['token_hash'], self::token_hash( $token ) ) ) {
				return new WP_Error(
					'pepselect_oe_token_required',
					'Ops must resend the access token returned by the first successful write, or explicitly rotate it.',
					array( 'status' => 409 )
				);
			}
		} else {
			$token = self::generate_token();
		}

		$json = wp_json_encode( $snapshot, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
		if ( false === $json ) {
			return new WP_Error( 'pepselect_oe_invalid_snapshot', 'The order snapshot could not be encoded.', array( 'status' => 400 ) );
		}

		$data = array(
			'order_id'         => $order_id,
			'token_hash'       => self::token_hash( $token ),
			'snapshot_version' => 1,
			'snapshot_hash'    => $snapshot_hash,
			'snapshot_json'    => $json,
			'updated_at'       => $now,
			'revoked_at'       => null,
		);

		if ( $existing ) {
			$ok = $this->db->update( $this->table, $data, array( 'id' => (int) $existing['id'] ) );
		} else {
			$data['created_at'] = $now;
			$ok                 = $this->db->insert( $this->table, $data );
		}

		if ( false === $ok ) {
			return new WP_Error( 'pepselect_oe_store_failed', 'The secure order record could not be saved.', array( 'status' => 500 ) );
		}

		return array(
			'access_token' => $token,
			'created'      => ! $existing,
			'rotated'      => (bool) $existing && $rotate,
		);
	}

	public function revoke( int $order_id ): bool {
		return false !== $this->db->update(
			$this->table,
			array( 'revoked_at' => current_time( 'mysql', true ), 'updated_at' => current_time( 'mysql', true ) ),
			array( 'order_id' => $order_id )
		);
	}
}

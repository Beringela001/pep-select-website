<?php

defined( 'ABSPATH' ) || exit;

final class PepSelect_OE_REST_Controller {
	public const NAMESPACE = 'pepselect-order-experience/v1';

	private PepSelect_OE_Access_Store $store;

	public function __construct( PepSelect_OE_Access_Store $store ) {
		$this->store = $store;
	}

	public function register_routes(): void {
		register_rest_route(
			self::NAMESPACE,
			'/orders/(?P<order_id>\d+)/snapshot',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'write_snapshot' ),
				'permission_callback' => array( $this, 'can_manage_orders' ),
			)
		);
		register_rest_route(
			self::NAMESPACE,
			'/orders/(?P<order_id>\d+)/revoke',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'revoke' ),
				'permission_callback' => array( $this, 'can_manage_orders' ),
			)
		);
	}

	public function can_manage_orders(): bool {
		return current_user_can( 'manage_woocommerce' );
	}

	public function write_snapshot( WP_REST_Request $request ): WP_REST_Response|WP_Error {
		if ( ! function_exists( 'wc_get_order' ) ) {
			return new WP_Error( 'pepselect_oe_woocommerce_required', 'WooCommerce is unavailable.', array( 'status' => 503 ) );
		}
		$order_id = absint( $request['order_id'] );
		$order    = wc_get_order( $order_id );
		if ( ! $order ) {
			return new WP_Error( 'pepselect_oe_order_not_found', 'The WooCommerce order was not found.', array( 'status' => 404 ) );
		}

		$body     = $request->get_json_params();
		$snapshot = is_array( $body['snapshot'] ?? null ) ? $body['snapshot'] : null;
		if ( ! $snapshot || 1 !== absint( $snapshot['schema_version'] ?? 0 ) ) {
			return new WP_Error( 'pepselect_oe_schema_version', 'A schema_version 1 snapshot is required.', array( 'status' => 400 ) );
		}
		if ( (string) $order_id !== (string) ( $snapshot['woo_order_id'] ?? '' ) ) {
			return new WP_Error( 'pepselect_oe_order_mismatch', 'The snapshot does not belong to this WooCommerce order.', array( 'status' => 400 ) );
		}
		$allocations = $snapshot['items'] ?? null;
		if ( ! is_array( $allocations ) || array() === $allocations ) {
			return new WP_Error( 'pepselect_oe_allocations_required', 'At least one settled order item is required.', array( 'status' => 400 ) );
		}
		foreach ( $allocations as $item ) {
			if ( ! is_array( $item ) || empty( $item['allocations'] ) || ! is_array( $item['allocations'] ) ) {
				return new WP_Error( 'pepselect_oe_batch_required', 'Every order item needs at least one exact batch allocation.', array( 'status' => 400 ) );
			}
			$line_item_id = absint( $item['woo_line_item_id'] ?? 0 );
			if ( ! $line_item_id || ! $order->get_item( $line_item_id ) ) {
				return new WP_Error( 'pepselect_oe_line_mismatch', 'Every snapshot item must match an item on this WooCommerce order.', array( 'status' => 400 ) );
			}
			foreach ( $item['allocations'] as $allocation ) {
				if ( empty( $allocation['batch_number'] ) || empty( $allocation['quantity'] ) ) {
					return new WP_Error( 'pepselect_oe_batch_invalid', 'Each allocation needs a batch number and quantity.', array( 'status' => 400 ) );
				}
			}
		}

		$json          = wp_json_encode( $snapshot, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
		$snapshot_hash = hash( 'sha256', (string) $json );
		$sent_hash     = sanitize_text_field( (string) ( $body['snapshot_hash'] ?? '' ) );
		if ( ! preg_match( '/^[a-f0-9]{64}$/', $sent_hash ) || ! hash_equals( $snapshot_hash, $sent_hash ) ) {
			return new WP_Error( 'pepselect_oe_snapshot_hash', 'The snapshot hash does not match its contents.', array( 'status' => 400 ) );
		}

		$result = $this->store->upsert(
			$order_id,
			$snapshot,
			$snapshot_hash,
			isset( $body['access_token'] ) ? sanitize_text_field( (string) $body['access_token'] ) : null,
			! empty( $body['rotate_access'] )
		);
		if ( is_wp_error( $result ) ) {
			return $result;
		}

		// Keep WooCommerce canonical while attaching the immutable, versioned
		// fulfillment facts to the order/item records they describe.
		foreach ( $allocations as $item_snapshot ) {
			$order_item = $order->get_item( absint( $item_snapshot['woo_line_item_id'] ) );
			$order_item->update_meta_data(
				'_pepselect_order_allocations_v1',
				wp_json_encode( $item_snapshot['allocations'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
			);
			$order_item->save();
		}
		$order->update_meta_data( '_pepselect_order_experience_snapshot_version', 1 );
		$order->update_meta_data( '_pepselect_order_experience_snapshot_hash', $snapshot_hash );
		$order->update_meta_data( '_pepselect_order_experience_allocation_status', 'settled' );
		$order->save();

		$url = add_query_arg( 'access', $result['access_token'], PepSelect_OE_Plugin::order_page_url() );
		return new WP_REST_Response(
			array(
				'ready'         => true,
				'order_id'      => $order_id,
				'snapshot_hash' => $snapshot_hash,
				'access_token'  => $result['access_token'],
				'access_url'    => $url,
				'created'       => $result['created'],
				'rotated'       => $result['rotated'],
			),
			200
		);
	}

	public function revoke( WP_REST_Request $request ): WP_REST_Response {
		$order_id = absint( $request['order_id'] );
		$this->store->revoke( $order_id );
		return new WP_REST_Response( array( 'revoked' => true, 'order_id' => $order_id ), 200 );
	}
}

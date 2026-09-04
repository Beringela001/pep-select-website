<?php
/**
 * Campaign attribution stored in the existing WooCommerce session and order.
 *
 * @package PepSelectTracking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class PepSelect_Tracking_Attribution {
	const FIRST_KEY = 'pepselect_attribution_first';
	const LAST_KEY  = 'pepselect_attribution_last';

	/** Register hooks. */
	public static function register() {
		add_action( 'wp_loaded', array( __CLASS__, 'capture_request' ), 25 );
		add_action( 'woocommerce_checkout_create_order', array( __CLASS__, 'copy_to_order' ), 20, 2 );
	}

	/**
	 * Capture a bounded first/last touch without storing full URLs or arbitrary query data.
	 *
	 * @return void
	 */
	public static function capture_request() {
		if ( is_admin() || wp_doing_ajax() || wp_doing_cron() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
			return;
		}

		if ( ! function_exists( 'WC' ) || ! WC()->session ) {
			return;
		}

		$touch = self::request_touch();
		if ( empty( $touch ) ) {
			return;
		}

		if ( ! WC()->session->get( self::FIRST_KEY ) ) {
			WC()->session->set( self::FIRST_KEY, $touch );
		}

		WC()->session->set( self::LAST_KEY, $touch );
	}

	/**
	 * Create the current attribution touch.
	 *
	 * @return array<string,string>
	 */
	public static function request_touch() {
		$map = array(
			'utm_source'          => 'source',
			'utm_medium'          => 'medium',
			'utm_campaign'        => 'campaign',
			'utm_id'              => 'campaign_id',
			'utm_term'            => 'term',
			'utm_content'         => 'content',
			'utm_source_platform' => 'source_platform',
			'utm_creative_format' => 'creative_format',
			'utm_marketing_tactic'=> 'marketing_tactic',
		);

		$touch = array();
		foreach ( $map as $query_key => $stored_key ) {
			if ( ! isset( $_GET[ $query_key ] ) || is_array( $_GET[ $query_key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only campaign capture.
				continue;
			}

			$value = sanitize_text_field( wp_unslash( $_GET[ $query_key ] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$value = function_exists( 'mb_substr' ) ? mb_substr( $value, 0, 160 ) : substr( $value, 0, 160 );
			if ( '' !== $value ) {
				$touch[ $stored_key ] = $value;
			}
		}

		if ( self::query_has_value( 'gclid' ) ) {
			$touch['source'] = isset( $touch['source'] ) ? $touch['source'] : 'google';
			$touch['medium'] = isset( $touch['medium'] ) ? $touch['medium'] : 'cpc';
		} elseif ( self::query_has_value( 'fbclid' ) ) {
			$touch['source'] = isset( $touch['source'] ) ? $touch['source'] : 'meta';
			$touch['medium'] = isset( $touch['medium'] ) ? $touch['medium'] : 'paid_social';
		} elseif ( self::query_has_value( 'msclkid' ) ) {
			$touch['source'] = isset( $touch['source'] ) ? $touch['source'] : 'bing';
			$touch['medium'] = isset( $touch['medium'] ) ? $touch['medium'] : 'cpc';
		}

		$referrer_host = self::external_referrer_host();
		if ( '' !== $referrer_host && ! isset( $touch['source'] ) ) {
			$touch['source']        = $referrer_host;
			$touch['medium']        = 'referral';
			$touch['referrer_host'] = $referrer_host;
		}

		if ( empty( $touch ) ) {
			return array();
		}

		$request_path = isset( $_SERVER['REQUEST_URI'] ) ? wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ) : '/';
		$touch['landing_path'] = is_string( $request_path ) && '' !== $request_path ? substr( $request_path, 0, 240 ) : '/';
		$touch['captured_at']  = gmdate( 'c' );

		return $touch;
	}

	/** Detect a known ad click parameter without persisting its identifier. */
	private static function query_has_value( $query_key ) {
		if ( ! isset( $_GET[ $query_key ] ) || is_array( $_GET[ $query_key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only campaign detection.
			return false;
		}

		return '' !== sanitize_text_field( wp_unslash( $_GET[ $query_key ] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

	/** Return an external referring hostname, never a full referrer URL. */
	private static function external_referrer_host() {
		if ( empty( $_SERVER['HTTP_REFERER'] ) ) {
			return '';
		}

		$host      = strtolower( (string) wp_parse_url( wp_unslash( $_SERVER['HTTP_REFERER'] ), PHP_URL_HOST ) );
		$site_host = strtolower( (string) wp_parse_url( home_url( '/' ), PHP_URL_HOST ) );

		if ( '' === $host || $host === $site_host || substr( $host, -strlen( '.' . $site_host ) ) === '.' . $site_host ) {
			return '';
		}

		return sanitize_text_field( substr( $host, 0, 190 ) );
	}

	/**
	 * Copy attribution and consent-scoped anonymous identifiers to the order.
	 *
	 * @param WC_Order $order Order being created.
	 * @param array    $data  Checkout data, unused.
	 */
	public static function copy_to_order( $order, $data ) {
		if ( ! is_a( $order, 'WC_Order' ) || ! function_exists( 'WC' ) || ! WC()->session ) {
			return;
		}

		self::copy_scoped_identifier( $order, 'pepselect_analytics_consent', '_pepselect_analytics_consent', array( 'granted', 'denied' ) );
		self::copy_scoped_identifier( $order, 'pepselect_marketing_consent', '_pepselect_marketing_consent', array( 'granted', 'denied' ) );

		if ( 'granted' === $order->get_meta( '_pepselect_analytics_consent', true ) || 'granted' === $order->get_meta( '_pepselect_marketing_consent', true ) ) {
			$first = WC()->session->get( self::FIRST_KEY );
			$last  = WC()->session->get( self::LAST_KEY );

			if ( is_array( $first ) ) {
				$order->update_meta_data( '_pepselect_attribution_first', $first );
			}
			if ( is_array( $last ) ) {
				$order->update_meta_data( '_pepselect_attribution_last', $last );
			}
		}

		if ( 'granted' === $order->get_meta( '_pepselect_analytics_consent', true ) ) {
			self::copy_scoped_identifier( $order, 'pepselect_ga_client_id', '_pepselect_ga_client_id', array(), 80 );
			self::copy_scoped_identifier( $order, 'pepselect_ga_session_id', '_pepselect_ga_session_id', array(), 40 );
		}

		if ( 'granted' === $order->get_meta( '_pepselect_marketing_consent', true ) ) {
			self::copy_scoped_identifier( $order, 'pepselect_fbp', '_pepselect_fbp', array(), 100 );
			self::copy_scoped_identifier( $order, 'pepselect_fbc', '_pepselect_fbc', array(), 140 );
		}
	}

	/** Copy one hidden checkout value after strict character and length checks. */
	private static function copy_scoped_identifier( $order, $post_key, $meta_key, $allowed = array(), $max_length = 16 ) {
		if ( ! isset( $_POST[ $post_key ] ) || is_array( $_POST[ $post_key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- WooCommerce validates the checkout request.
			return;
		}

		$value = sanitize_text_field( wp_unslash( $_POST[ $post_key ] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$value = preg_replace( '/[^A-Za-z0-9._-]/', '', $value );
		$value = substr( (string) $value, 0, $max_length );

		if ( '' === $value || ( ! empty( $allowed ) && ! in_array( $value, $allowed, true ) ) ) {
			return;
		}

		$order->update_meta_data( $meta_key, $value );
	}

	/** Return the last touch for event payloads. */
	public static function current() {
		if ( ! function_exists( 'WC' ) || ! WC()->session ) {
			return array();
		}

		$value = WC()->session->get( self::LAST_KEY );
		return is_array( $value ) ? $value : array();
	}
}

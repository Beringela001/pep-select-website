<?php
/**
 * Paid-order delivery to GA4 Measurement Protocol and Meta CAPI.
 *
 * @package PepSelectTracking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class PepSelect_Tracking_Delivery {
	const RETRY_HOOK = 'pepselect_tracking_deliver_paid_order';

	/** Register paid-order and retry hooks. */
	public static function register() {
		add_action( 'woocommerce_payment_complete', array( __CLASS__, 'queue' ), 20 );
		add_action( 'woocommerce_order_status_changed', array( __CLASS__, 'status_changed' ), 20, 4 );
		add_action( self::RETRY_HOOK, array( __CLASS__, 'deliver' ), 10, 1 );
	}

	/** Queue when a manual/external payment changes to a paid status. */
	public static function status_changed( $order_id, $from, $to, $order ) {
		if ( is_a( $order, 'WC_Order' ) && $order->is_paid() ) {
			self::queue( $order_id );
		}
	}

	/** Queue a unique async delivery. */
	public static function queue( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order || ! $order->is_paid() || 'bacs' !== $order->get_payment_method() ) {
			return;
		}

		if ( function_exists( 'as_enqueue_async_action' ) ) {
			as_enqueue_async_action( self::RETRY_HOOK, array( (int) $order_id ), 'pepselect-tracking', true );
			return;
		}

		if ( ! wp_next_scheduled( self::RETRY_HOOK, array( (int) $order_id ) ) ) {
			wp_schedule_single_event( time() + 5, self::RETRY_HOOK, array( (int) $order_id ) );
		}
	}

	/** Deliver configured providers, each with independent idempotency and retry state. */
	public static function deliver( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order || ! $order->is_paid() || 'bacs' !== $order->get_payment_method() ) {
			return;
		}

		$retry = false;
		if ( self::ga4_configured() && 'granted' === $order->get_meta( '_pepselect_analytics_consent', true ) && ! $order->get_meta( '_pepselect_ga4_purchase_sent', true ) ) {
			$retry = ! self::send_ga4( $order ) || $retry;
		}

		if ( self::meta_configured() && 'granted' === $order->get_meta( '_pepselect_marketing_consent', true ) && ! $order->get_meta( '_pepselect_meta_purchase_sent', true ) ) {
			$retry = ! self::send_meta( $order ) || $retry;
		}

		if ( $retry ) {
			self::schedule_retry( $order );
		}
	}

	/** Send an anonymous paid purchase to GA4. */
	private static function send_ga4( $order ) {
		$client_id = (string) $order->get_meta( '_pepselect_ga_client_id', true );
		if ( '' === $client_id ) {
			return true;
		}

		$measurement_id = rawurlencode( (string) constant( 'PEPSELECT_GA4_MEASUREMENT_ID' ) );
		$api_secret     = rawurlencode( (string) constant( 'PEPSELECT_GA4_API_SECRET' ) );
		$url            = 'https://www.google-analytics.com/mp/collect?measurement_id=' . $measurement_id . '&api_secret=' . $api_secret;
		$params         = self::purchase_params( $order );
		$params['engagement_time_msec'] = 1;

		$session_id = (string) $order->get_meta( '_pepselect_ga_session_id', true );
		if ( '' !== $session_id ) {
			$params['session_id'] = $session_id;
		}

		$payload = array(
			'client_id' => $client_id,
			'events'    => array( array( 'name' => 'purchase', 'params' => $params ) ),
		);

		$response = wp_safe_remote_post(
			$url,
			array(
				'timeout'     => 4,
				'redirection' => 0,
				'headers'     => array( 'Content-Type' => 'application/json' ),
				'body'        => wp_json_encode( $payload ),
			)
		);

		if ( ! is_wp_error( $response ) && in_array( wp_remote_retrieve_response_code( $response ), array( 200, 204 ), true ) ) {
			$order->update_meta_data( '_pepselect_ga4_purchase_sent', gmdate( 'c' ) );
			$order->save_meta_data();
			return true;
		}

		return false;
	}

	/** Send Meta CAPI with consented Meta click/browser identifiers only; never PII or IP. */
	private static function send_meta( $order ) {
		$fbp = (string) $order->get_meta( '_pepselect_fbp', true );
		$fbc = (string) $order->get_meta( '_pepselect_fbc', true );
		if ( '' === $fbp && '' === $fbc ) {
			return true;
		}

		$user_data = array();
		if ( '' !== $fbp ) {
			$user_data['fbp'] = $fbp;
		}
		if ( '' !== $fbc ) {
			$user_data['fbc'] = $fbc;
		}

		$pixel_id = rawurlencode( (string) constant( 'PEPSELECT_META_PIXEL_ID' ) );
		$version  = preg_replace( '/[^v0-9.]/', '', (string) constant( 'PEPSELECT_META_API_VERSION' ) );
		$url      = 'https://graph.facebook.com/' . $version . '/' . $pixel_id . '/events';
		$data     = array(
			'event_name'       => 'Purchase',
			'event_time'       => time(),
			'event_id'         => 'pep_order_' . $order->get_id() . '_paid',
			'action_source'    => 'website',
			'event_source_url' => home_url( '/checkout/order-received/' ),
			'user_data'        => $user_data,
			'custom_data'      => self::meta_purchase_data( $order ),
		);

		$response = wp_safe_remote_post(
			$url,
			array(
				'timeout'     => 4,
				'redirection' => 0,
				'body'        => array(
					'access_token' => (string) constant( 'PEPSELECT_META_ACCESS_TOKEN' ),
					'data'         => wp_json_encode( array( $data ) ),
				),
			)
		);

		if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
			$order->update_meta_data( '_pepselect_meta_purchase_sent', gmdate( 'c' ) );
			$order->save_meta_data();
			return true;
		}

		return false;
	}

	/** GA4 purchase payload from authoritative order values. */
	private static function purchase_params( $order ) {
		$items = array();
		foreach ( $order->get_items() as $line ) {
			$product = $line->get_product();
			if ( $product ) {
				$items[] = PepSelect_Tracking_Events::item_payload( $product, $line->get_quantity(), $line->get_total() );
			}
		}

		$params = array(
			'transaction_id' => (string) $order->get_order_number(),
			'affiliation'    => 'Pep Select',
			'value'          => (float) $order->get_total(),
			'currency'       => $order->get_currency(),
			'tax'            => (float) $order->get_total_tax(),
			'shipping'       => (float) $order->get_shipping_total(),
			'items'          => $items,
		);

		$coupons = $order->get_coupon_codes();
		if ( ! empty( $coupons ) ) {
			$params['coupon'] = implode( ',', $coupons );
		}

		$touch = $order->get_meta( '_pepselect_attribution_last', true );
		if ( is_array( $touch ) ) {
			foreach ( array( 'source', 'medium', 'campaign', 'campaign_id', 'term', 'content' ) as $key ) {
				if ( isset( $touch[ $key ] ) ) {
					$params[ $key ] = $touch[ $key ];
				}
			}
		}

		return $params;
	}

	/** Meta custom data, excluding customer identity. */
	private static function meta_purchase_data( $order ) {
		$contents = array();
		foreach ( $order->get_items() as $line ) {
			$product = $line->get_product();
			if ( $product ) {
				$contents[] = array(
					'id'       => $product->get_sku() ? (string) $product->get_sku() : (string) $product->get_id(),
					'quantity' => (int) $line->get_quantity(),
					'item_price'=> round( (float) $line->get_total() / max( 1, (int) $line->get_quantity() ), wc_get_price_decimals() ),
				);
			}
		}

		return array(
			'currency'     => $order->get_currency(),
			'value'        => (float) $order->get_total(),
			'content_type' => 'product',
			'contents'     => $contents,
			'order_id'     => (string) $order->get_order_number(),
		);
	}

	/** Retry failures with bounded exponential delay. */
	private static function schedule_retry( $order ) {
		$attempts = (int) $order->get_meta( '_pepselect_tracking_attempts', true ) + 1;
		$order->update_meta_data( '_pepselect_tracking_attempts', $attempts );
		$order->save_meta_data();
		if ( $attempts >= 5 ) {
			return;
		}

		$delay = min( HOUR_IN_SECONDS, 60 * ( 2 ** $attempts ) );
		wp_schedule_single_event( time() + $delay, self::RETRY_HOOK, array( (int) $order->get_id() ) );
	}

	/** Configuration guards. */
	public static function ga4_configured() {
		return defined( 'PEPSELECT_GA4_MEASUREMENT_ID' ) && defined( 'PEPSELECT_GA4_API_SECRET' ) && '' !== (string) PEPSELECT_GA4_MEASUREMENT_ID && '' !== (string) PEPSELECT_GA4_API_SECRET;
	}

	public static function meta_configured() {
		return defined( 'PEPSELECT_META_PIXEL_ID' ) && defined( 'PEPSELECT_META_ACCESS_TOKEN' ) && defined( 'PEPSELECT_META_API_VERSION' ) && '' !== (string) PEPSELECT_META_PIXEL_ID && '' !== (string) PEPSELECT_META_ACCESS_TOKEN && '' !== (string) PEPSELECT_META_API_VERSION;
	}
}


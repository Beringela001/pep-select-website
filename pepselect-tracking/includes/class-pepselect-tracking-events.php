<?php
/**
 * Browser funnel events and Site Kit interop.
 *
 * @package PepSelectTracking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class PepSelect_Tracking_Events {
	/** Register hooks. */
	public static function register() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ), 90 );
		add_filter( 'script_loader_tag', array( __CLASS__, 'defer_script' ), 20, 3 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'suppress_premature_sitekit_purchase' ), PHP_INT_MAX );
	}

	/** Enqueue the small dependency-free event bridge. */
	public static function enqueue() {
		if ( is_admin() || ! self::is_measurement_surface() ) {
			return;
		}

		wp_enqueue_script(
			'pepselect-conversion-tracking',
			PEPSELECT_TRACKING_URL . 'assets/js/tracking.js',
			array(),
			PEPSELECT_TRACKING_VERSION,
			true
		);

		wp_localize_script(
			'pepselect-conversion-tracking',
			'pepselectTrackingConfig',
			array(
				'context'          => self::context(),
				'currency'         => get_woocommerce_currency(),
				'product'          => self::product_payload(),
				'cart'             => self::cart_payload(),
				'attribution'      => PepSelect_Tracking_Attribution::current(),
				'metaPixelId' => self::constant_string( 'PEPSELECT_META_PIXEL_ID' ),
				'debug'       => defined( 'PEPSELECT_TRACKING_DEBUG' ) && PEPSELECT_TRACKING_DEBUG,
			)
		);
	}

	/** Add defer without affecting dependencies or third-party scripts. */
	public static function defer_script( $tag, $handle, $src ) {
		if ( 'pepselect-conversion-tracking' !== $handle || false !== strpos( $tag, ' defer' ) ) {
			return $tag;
		}

		return str_replace( ' src=', ' defer src=', $tag );
	}

	/**
	 * Site Kit treats the thank-you page as a purchase. For the emailed BACS/Square
	 * flow, remove only that event before its provider script executes. The order
	 * remains an order_submitted event until WooCommerce marks it paid.
	 */
	public static function suppress_premature_sitekit_purchase() {
		if ( ! function_exists( 'is_wc_endpoint_url' ) || ! is_wc_endpoint_url( 'order-received' ) ) {
			return;
		}

		$order_id = absint( get_query_var( 'order-received' ) );
		$order    = $order_id ? wc_get_order( $order_id ) : false;
		if ( ! $order || 'bacs' !== $order->get_payment_method() || $order->is_paid() ) {
			return;
		}

		$handle = 'googlesitekit-events-provider-woocommerce';
		if ( ! wp_script_is( $handle, 'enqueued' ) ) {
			return;
		}

		$script = <<<'JS'
window._googlesitekit=window._googlesitekit||{};
window._googlesitekit.wcdata=window._googlesitekit.wcdata||{};
if(Array.isArray(window._googlesitekit.wcdata.eventsToTrack)){
window._googlesitekit.wcdata.eventsToTrack=window._googlesitekit.wcdata.eventsToTrack.filter(function(eventName){return eventName!=="purchase";});
}
JS;
		wp_add_inline_script( $handle, $script, 'before' );
	}

	/** Measurement surfaces only; no site-wide script tax on editorial/legal pages. */
	private static function is_measurement_surface() {
		return is_front_page() || is_shop() || is_product() || is_product_category() || is_cart() || is_checkout();
	}

	/** Return a stable page context. */
	private static function context() {
		if ( is_product() ) {
			return 'product';
		}
		if ( is_cart() ) {
			return 'cart';
		}
		if ( is_checkout() && ! is_wc_endpoint_url( 'order-received' ) ) {
			return 'checkout';
		}
		if ( is_wc_endpoint_url( 'order-received' ) ) {
			return 'order_received';
		}
		return 'catalog';
	}

	/** Build a GA4/Meta-safe product object with no customer fields. */
	public static function item_payload( $product, $quantity = 1, $line_total = null ) {
		if ( ! is_a( $product, 'WC_Product' ) ) {
			return array();
		}

		$price = null === $line_total ? (float) wc_get_price_to_display( $product ) : (float) $line_total / max( 1, (int) $quantity );
		$item  = array(
			'item_id'   => $product->get_sku() ? (string) $product->get_sku() : (string) $product->get_id(),
			'item_name' => wp_strip_all_tags( $product->get_name() ),
			'price'     => round( $price, wc_get_price_decimals() ),
			'quantity'  => max( 1, (int) $quantity ),
		);

		$categories = wc_get_product_category_list( $product->get_id(), ', ' );
		if ( $categories ) {
			$item['item_category'] = wp_strip_all_tags( explode( ',', $categories )[0] );
		}

		if ( $product->is_type( 'variation' ) ) {
			$item['item_variant'] = wp_strip_all_tags( wc_get_formatted_variation( $product, true, false, true ) );
		}

		return $item;
	}

	/** Current product payload. */
	private static function product_payload() {
		global $product;
		return self::item_payload( $product );
	}

	/** Current cart payload. */
	private static function cart_payload() {
		if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
			return array();
		}

		$items = array();
		foreach ( WC()->cart->get_cart() as $line ) {
			if ( isset( $line['data'] ) ) {
				$items[] = self::item_payload( $line['data'], isset( $line['quantity'] ) ? $line['quantity'] : 1, isset( $line['line_total'] ) ? $line['line_total'] : null );
			}
		}

		return array(
			'items'    => $items,
			'value'    => round( (float) WC()->cart->get_total( 'edit' ), wc_get_price_decimals() ),
			'cartHash' => WC()->cart->get_cart_hash(),
		);
	}

	/** Read a non-secret configuration constant. */
	private static function constant_string( $name ) {
		return defined( $name ) ? sanitize_text_field( (string) constant( $name ) ) : '';
	}
}

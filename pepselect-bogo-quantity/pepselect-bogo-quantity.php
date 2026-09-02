<?php
/**
 * Plugin Name: Pep Select Cart Discounts
 * Description: Manages BOGO, compound, and sitewide automatic discounts from one Ops-ready admin area.
 * Version:     2.2.0
 * Author:      Pep Select
 * Text Domain: pepselect-bogo-quantity
 */

defined( 'ABSPATH' ) || exit;

define( 'PEPSELECT_BOGO_VERSION', '2.2.0' );
define( 'PEPSELECT_BOGO_FILE', __FILE__ );

require_once plugin_dir_path( __FILE__ ) . 'includes/class-pepselect-compound-discount.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-pepselect-bogo-rule.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-pepselect-sitewide-discount.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-pepselect-discount-admin.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-pepselect-discount-stacking.php';

PepSelect_Discount_Admin::boot();
PepSelect_Compound_Discount::boot();
PepSelect_BOGO_Rule::boot();
PepSelect_Sitewide_Discount::boot();
PepSelect_Discount_Stacking::boot();

/**
 * Compatibility filter for integrations that still consume eligible SKUs.
 *
 * @return string[]
 */
function pepselect_bogo_skus() {
	$skus = array();
	foreach ( PepSelect_BOGO_Rule::get_state()['product_ids'] as $product_id ) {
		$product = function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : null;
		if ( $product && $product->get_sku() ) {
			$skus[] = strtoupper( (string) $product->get_sku() );
		}
	}
	return apply_filters( 'pepselect_bogo_skus', array_values( array_unique( $skus ) ) );
}

/**
 * @param int $product_id WooCommerce product or variation ID.
 * @return bool
 */
function pepselect_bogo_is_eligible( $product_id ) {
	return PepSelect_BOGO_Rule::is_product_eligible( $product_id );
}

/**
 * Load the cart-only promotion-pill styles.
 *
 * The stylesheet hides the marker by default, then reveals it only inside the
 * full Cart page or the Xootix side cart. Checkout and order surfaces retain
 * their existing presentation.
 */
function pepselect_bogo_enqueue_cart_notice_styles() {
	if ( ! PepSelect_BOGO_Rule::is_enabled() || ( is_admin() && ! wp_doing_ajax() ) ) {
		return;
	}

	wp_enqueue_style(
		'pepselect-bogo-cart-notice',
		plugin_dir_url( __FILE__ ) . 'assets/bogo-cart-notice.css',
		array(),
		PEPSELECT_BOGO_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'pepselect_bogo_enqueue_cart_notice_styles' );

/**
 * Return the customer-facing promotion message for a physical cart quantity.
 *
 * @param int $quantity Physical quantity in the cart.
 * @return string
 */
function pepselect_bogo_notice_text( $quantity ) {
	$total = max( 1, (int) $quantity );
	$free  = PepSelect_BOGO_Rule::free_vials( $total );

	if ( $free < 1 ) {
		return __( 'Add 5, one is on us.', 'pepselect-bogo-quantity' );
	}

	return sprintf(
		/* translators: %d: free vial count. */
		_n( '%d free vial added', '%d free vials added', $free, 'pepselect-bogo-quantity' ),
		$free
	);
}

/**
 * Reduce the Xootix footer to the single decision-driving number used by the
 * full Cart: an estimated total. Shipping remains calculated at checkout.
 *
 * @param array $totals Xootix footer totals.
 * @return array
 */
function pepselect_bogo_simplify_side_cart_totals( $totals ) {
	if ( empty( $totals['total'] ) ) {
		return $totals;
	}

	$total          = $totals['total'];
	$total['label'] = __( 'Estimated total', 'pepselect-bogo-quantity' );

	return array( 'total' => $total );
}
add_filter( 'xoo_wsc_cart_totals', 'pepselect_bogo_simplify_side_cart_totals', 999 );

/** Remove Xootix's separate shipping/checkout disclaimer from the footer. */
function pepselect_bogo_simplify_side_cart_footer( $args ) {
	if ( array_key_exists( 'footerTxt', $args ) ) {
		$args['footerTxt']     = '';
		$args['showFooterTxt'] = false;
	}

	return $args;
}
add_filter( 'xoo_wsc_cart_header_args', 'pepselect_bogo_simplify_side_cart_footer', 999 );
add_filter( 'xoo_wsc_cart_footer_args', 'pepselect_bogo_simplify_side_cart_footer', 999 );

/** Add a compact promotion explanation beside eligible cart lines. */
function pepselect_bogo_item_data( $data, $cart_item ) {
	$product_id = ! empty( $cart_item['variation_id'] ) ? $cart_item['variation_id'] : $cart_item['product_id'];
	if ( ! pepselect_bogo_is_eligible( (int) $product_id ) ) {
		return $data;
	}

	$text   = pepselect_bogo_notice_text( $cart_item['quantity'] );
	$notice = '<span class="pepselect-bogo-cart-notice">' . esc_html( $text ) . '</span>';

	$data[] = array(
		'key'     => __( 'Buy 4 get 1 free', 'pepselect-bogo-quantity' ),
		'value'   => $notice,
		'display' => $notice,
	);

	return $data;
}
add_filter( 'woocommerce_get_item_data', 'pepselect_bogo_item_data', 20, 2 );

/**
 * Render directly inside Xootix's product summary. Its product-meta setting is
 * disabled on live, so the standard WooCommerce item-data hook is not printed.
 *
 * @param WC_Product $product       Product displayed by Xootix.
 * @param string     $cart_item_key WooCommerce cart item key.
 */
function pepselect_bogo_side_cart_notice( $product, $cart_item_key ) {
	if ( ! $product instanceof WC_Product || ! function_exists( 'WC' ) || ! WC()->cart ) {
		return;
	}

	$cart_item = WC()->cart->get_cart_item( $cart_item_key );
	$product_id = ! empty( $cart_item['variation_id'] ) ? $cart_item['variation_id'] : ( $cart_item['product_id'] ?? $product->get_id() );
	if ( empty( $cart_item ) || ! pepselect_bogo_is_eligible( (int) $product_id ) ) {
		return;
	}

	printf(
		'<div class="pepselect-bogo-side-cart-notice"><span class="pepselect-bogo-cart-notice">%s</span></div>',
		esc_html( pepselect_bogo_notice_text( $cart_item['quantity'] ?? 1 ) )
	);
}
add_action( 'xoo_wsc_product_summary_col_start', 'pepselect_bogo_side_cart_notice', 10, 2 );

<?php
/**
 * Plugin Name: Pep Select BOGO Cart Experience
 * Description: Keeps Buy 4 get 1 quantities literal and explains earned free vials in Cart and Side Cart.
 * Version:     1.7.0
 * Author:      Pep Select
 * Text Domain: pepselect-bogo-quantity
 */

defined( 'ABSPATH' ) || exit;

define( 'PEPSELECT_BOGO_VERSION', '1.7.0' );
define( 'PEPSELECT_BOGO_FILE', __FILE__ );

require_once plugin_dir_path( __FILE__ ) . 'includes/class-pepselect-compound-discount.php';

PepSelect_Compound_Discount::boot();

/**
 * These are the SKUs in the live YITH "Buy 4 get 1 free" rule (rule 1209).
 * Keep the YITH rule and this list aligned when eligibility changes.
 *
 * @return string[]
 */
function pepselect_bogo_skus() {
	return apply_filters(
		'pepselect_bogo_skus',
		array( 'GLP3R10', 'GLP3R20', 'GLP2T20', 'GLP1S10', 'MOTSC10', 'GHKCU50' )
	);
}

/**
 * @param int $product_id WooCommerce product or variation ID.
 * @return bool
 */
function pepselect_bogo_is_eligible( $product_id ) {
	if ( ! function_exists( 'wc_get_product' ) ) {
		return false;
	}

	$product = wc_get_product( $product_id );
	if ( ! $product ) {
		return false;
	}

	$sku = $product->get_sku();
	if ( '' === $sku && $product->is_type( 'variation' ) ) {
		$parent = wc_get_product( $product->get_parent_id() );
		$sku    = $parent ? $parent->get_sku() : '';
	}

	return in_array( strtoupper( (string) $sku ), pepselect_bogo_skus(), true );
}

/**
 * Load the cart-only promotion-pill styles.
 *
 * The stylesheet hides the marker by default, then reveals it only inside the
 * full Cart page or the Xootix side cart. Checkout and order surfaces retain
 * their existing presentation.
 */
function pepselect_bogo_enqueue_cart_notice_styles() {
	if ( is_admin() && ! wp_doing_ajax() ) {
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
	$free  = intdiv( $total, 5 );

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
 * Keep the listed per-vial price truthful when YITH spreads a free vial's
 * discount across every unit in its formatted cart price.
 *
 * @param string $price         Existing formatted unit price.
 * @param array  $cart_item     WooCommerce cart item.
 * @param string $cart_item_key WooCommerce cart item key.
 * @return string
 */
function pepselect_bogo_regular_unit_price( $price, $cart_item, $cart_item_key = '' ) {
	unset( $cart_item_key );

	$product_id = ! empty( $cart_item['variation_id'] ) ? $cart_item['variation_id'] : ( $cart_item['product_id'] ?? 0 );
	$product    = $cart_item['data'] ?? null;
	if ( ! $product instanceof WC_Product || ! pepselect_bogo_is_eligible( (int) $product_id ) ) {
		return $price;
	}

	$regular_price = (float) $product->get_regular_price();
	if ( $regular_price <= 0 ) {
		return $price;
	}

	return wc_price(
		wc_get_price_to_display(
			$product,
			array( 'price' => $regular_price )
		)
	);
}
add_filter( 'woocommerce_cart_item_price', 'pepselect_bogo_regular_unit_price', 999, 3 );

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

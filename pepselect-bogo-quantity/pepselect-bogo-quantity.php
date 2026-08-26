<?php
/**
 * Plugin Name: Pep Select Automatic Free Vials
 * Description: Makes Buy 4 get 1 free add the earned vial before YITH prices the cart.
 * Version:     1.1.2
 * Author:      Pep Select
 * Text Domain: pepselect-bogo-quantity
 */

defined( 'ABSPATH' ) || exit;

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

/** Mark the eligible single-product form so cart editors cannot retrigger expansion. */
function pepselect_bogo_add_request_marker() {
	global $product;
	if ( $product instanceof WC_Product && pepselect_bogo_is_eligible( $product->get_id() ) ) {
		echo '<input type="hidden" name="pepselect_bogo_product_add" value="1">';
	}
}
add_action( 'woocommerce_before_add_to_cart_button', 'pepselect_bogo_add_request_marker', 5 );

/**
 * Turn the quantity selected on the product page into the physical quantity
 * that belongs in the cart: 4 becomes 5, 8 becomes 10, and so on.
 *
 * @param int $quantity   Requested paid quantity.
 * @param int $product_id   Product ID.
 * @param int $variation_id Variation ID (WooCommerce 11+; zero on older versions).
 * @return int
 */
function pepselect_bogo_expand_add_to_cart_quantity( $quantity, $product_id, $variation_id = 0 ) {
	$quantity = max( 1, (int) $quantity );
	if ( empty( $_REQUEST['pepselect_bogo_product_add'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Request marker only scopes quantity behavior; WooCommerce validates the add request.
		return $quantity;
	}
	$effective_product_id = $variation_id ? $variation_id : $product_id;
	if ( ! pepselect_bogo_is_eligible( $effective_product_id ) ) {
		return $quantity;
	}

	$physical = $quantity + intdiv( $quantity, 4 );
	$GLOBALS['pepselect_bogo_add_targets'][ (int) $product_id ] = $physical;
	$GLOBALS['pepselect_bogo_add_targets'][ (int) $effective_product_id ] = $physical;
	return $physical;
}
add_filter( 'woocommerce_add_to_cart_quantity', 'pepselect_bogo_expand_add_to_cart_quantity', 20, 3 );

/** Apply the same rule when a Cart/Checkout block uses Woo's Store API. */
function pepselect_bogo_store_api_add_to_cart_data( $data ) {
	if ( ! is_array( $data ) || empty( $data['id'] ) || empty( $data['quantity'] ) ) {
		return $data;
	}
	if ( ! pepselect_bogo_is_eligible( (int) $data['id'] ) ) {
		return $data;
	}

	$selected         = max( 1, (int) $data['quantity'] );
	$data['quantity'] = $selected + intdiv( $selected, 4 );
	$GLOBALS['pepselect_bogo_add_targets'][ (int) $data['id'] ] = $data['quantity'];
	return $data;
}
add_filter( 'woocommerce_store_api_add_to_cart_data', 'pepselect_bogo_store_api_add_to_cart_data', 20 );

/** Make repeated Add to Cart selections replace the eligible line, as Orbitrex does. */
function pepselect_bogo_replace_added_line_quantity( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
	unset( $variation, $cart_item_data );
	$effective_product_id = $variation_id ? $variation_id : $product_id;
	$targets              = $GLOBALS['pepselect_bogo_add_targets'] ?? array();
	$target_key           = array_key_exists( (int) $effective_product_id, $targets ) ? (int) $effective_product_id : ( array_key_exists( (int) $product_id, $targets ) ? (int) $product_id : null );
	if ( null === $target_key ) {
		return;
	}

	$target = (int) $targets[ $target_key ];
	unset(
		$GLOBALS['pepselect_bogo_add_targets'][ (int) $product_id ],
		$GLOBALS['pepselect_bogo_add_targets'][ (int) $effective_product_id ]
	);
	if ( function_exists( 'WC' ) && WC()->cart ) {
		$current = WC()->cart->get_cart_item( $cart_item_key );
		if ( $target !== (int) ( $current['quantity'] ?? $quantity ) ) {
			WC()->cart->set_quantity( $cart_item_key, $target, false );
		}
	}
}
add_action( 'woocommerce_add_to_cart', 'pepselect_bogo_replace_added_line_quantity', 5, 6 );

/** Add an explicit explanation beside the cart/order line. */
function pepselect_bogo_item_data( $data, $cart_item ) {
	$product_id = ! empty( $cart_item['variation_id'] ) ? $cart_item['variation_id'] : $cart_item['product_id'];
	if ( ! pepselect_bogo_is_eligible( (int) $product_id ) ) {
		return $data;
	}

	$total = max( 1, (int) $cart_item['quantity'] );
	$free  = intdiv( $total, 5 );
	if ( $free > 0 ) {
		$data[] = array(
			'key'   => __( 'Buy 4 get 1 free', 'pepselect-bogo-quantity' ),
			'value' => sprintf(
				/* translators: %d: free vial count. */
				_n( '%d free vial included', '%d free vials included', $free, 'pepselect-bogo-quantity' ),
				$free
			),
		);
	}

	return $data;
}
add_filter( 'woocommerce_get_item_data', 'pepselect_bogo_item_data', 20, 2 );

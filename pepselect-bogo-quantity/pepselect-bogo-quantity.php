<?php
/**
 * Plugin Name: Pep Select Automatic Free Vials
 * Description: Makes Buy 4 get 1 free add the earned vial before YITH prices the cart.
 * Version:     1.0.0
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

/**
 * The storefront quantity is the number the customer pays for. Woo and YITH
 * need the physical count, so 4 becomes 5, 8 becomes 10, and so on.
 *
 * @param int $quantity   Requested paid quantity.
 * @param int $product_id   Product ID.
 * @param int $variation_id Variation ID (WooCommerce 11+; zero on older versions).
 * @return int
 */
function pepselect_bogo_expand_add_to_cart_quantity( $quantity, $product_id, $variation_id = 0 ) {
	$quantity = max( 1, (int) $quantity );
	if ( ! pepselect_bogo_is_eligible( $variation_id ? $variation_id : $product_id ) ) {
		return $quantity;
	}

	return $quantity + intdiv( $quantity, 4 );
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

	$paid             = max( 1, (int) $data['quantity'] );
	$data['quantity'] = $paid + intdiv( $paid, 4 );
	return $data;
}
add_filter( 'woocommerce_store_api_add_to_cart_data', 'pepselect_bogo_store_api_add_to_cart_data', 20 );

/**
 * If a customer edits an eligible cart line, treat the edited number as paid
 * vials and restore the earned vial(s). The guard prevents set_quantity from
 * recursively re-entering this hook.
 */
function pepselect_bogo_expand_cart_update( $cart_item_key, $new_quantity, $old_quantity, $cart ) {
	static $updating = false;
	if ( $updating || (int) $new_quantity === (int) $old_quantity ) {
		return;
	}

	$item = $cart->get_cart_item( $cart_item_key );
	$product_id = ! empty( $item['variation_id'] ) ? $item['variation_id'] : ( $item['product_id'] ?? 0 );
	if ( ! $product_id || ! pepselect_bogo_is_eligible( (int) $product_id ) ) {
		return;
	}

	$paid     = max( 1, (int) $new_quantity );
	$physical = $paid + intdiv( $paid, 4 );
	if ( $physical === (int) $new_quantity ) {
		return;
	}

	$updating = true;
	$cart->set_quantity( $cart_item_key, $physical, false );
	$updating = false;
}
add_action( 'woocommerce_after_cart_item_quantity_update', 'pepselect_bogo_expand_cart_update', 20, 4 );

/**
 * Show the paid count in classic cart quantity inputs. Woo still stores and
 * orders the full physical count, which is what stock reduction must use.
 */
function pepselect_bogo_cart_quantity_input( $html, $cart_item_key, $cart_item ) {
	$product_id = ! empty( $cart_item['variation_id'] ) ? $cart_item['variation_id'] : $cart_item['product_id'];
	if ( ! pepselect_bogo_is_eligible( (int) $product_id ) ) {
		return $html;
	}

	$total = max( 1, (int) $cart_item['quantity'] );
	$free  = intdiv( $total, 5 );
	$paid  = $total - $free;

	return woocommerce_quantity_input(
		array(
			'input_name'  => "cart[{$cart_item_key}][qty]",
			'input_value' => $paid,
			'min_value'   => 1,
			'max_value'   => $cart_item['data']->get_max_purchase_quantity(),
		),
		$cart_item['data'],
		false
	);
}
add_filter( 'woocommerce_cart_item_quantity', 'pepselect_bogo_cart_quantity_input', 20, 3 );

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
				/* translators: 1: paid vial count, 2: free vial count. */
				__( '%1$d paid + %2$d free (inventory: %3$d)', 'pepselect-bogo-quantity' ),
				$total - $free,
				$free,
				$total
			),
		);
	}

	return $data;
}
add_filter( 'woocommerce_get_item_data', 'pepselect_bogo_item_data', 20, 2 );

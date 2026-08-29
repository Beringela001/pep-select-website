<?php

define( 'ABSPATH', __DIR__ );

function absint( $value ) {
	return abs( (int) $value );
}

function apply_filters( $hook, $value ) {
	return $value;
}

function do_action() {}

function wc_format_decimal( $value ) {
	return number_format( (float) $value, 2, '.', '' );
}

function wc_format_coupon_code( $value ) {
	return strtolower( trim( (string) $value ) );
}

function wp_json_encode( $value ) {
	return json_encode( $value );
}

function is_admin() {
	return false;
}

function wp_doing_ajax() {
	return false;
}

function wc_get_notices() {
	return array();
}

function wc_set_notices() {}

$pepselect_test_sku_ids = array(
	'GLP3R10' => 11,
	'GLP3R20' => 12,
	'GLP2T20' => 13,
	'GLP1S10' => 14,
	'MOTSC10' => 15,
	'GHKCU50' => 16,
);

function wc_get_product_id_by_sku( $sku ) {
	global $pepselect_test_sku_ids;
	return $pepselect_test_sku_ids[ $sku ] ?? 0;
}

class WC_Product {
	private $id;
	private $price;
	private $parent_id;

	public function __construct( $id, $price, $parent_id = 0 ) {
		$this->id        = $id;
		$this->price     = $price;
		$this->parent_id = $parent_id;
	}

	public function get_price() {
		return $this->price;
	}

	public function is_type( $type ) {
		return 'variation' === $type && $this->parent_id > 0;
	}

	public function get_parent_id() {
		return $this->parent_id;
	}
}

$pepselect_test_products = array(
	11 => new WC_Product( 11, 100 ),
	22 => new WC_Product( 22, 50 ),
	33 => new WC_Product( 33, 100, 11 ),
);

function wc_get_product( $id ) {
	global $pepselect_test_products;
	return $pepselect_test_products[ $id ] ?? null;
}

$pepselect_test_options = array();

function get_option( $key, $default = false ) {
	global $pepselect_test_options;
	return array_key_exists( $key, $pepselect_test_options ) ? $pepselect_test_options[ $key ] : $default;
}

function update_option( $key, $value ) {
	global $pepselect_test_options;
	$pepselect_test_options[ $key ] = $value;
}

class WC_Cart {
	private $items;
	public $applied = array();

	public function __construct( $items ) {
		$this->items = $items;
	}

	public function get_cart() {
		return $this->items;
	}

	public function has_discount( $code ) {
		return in_array( strtolower( $code ), $this->applied, true );
	}

	public function apply_coupon( $code ) {
		$this->applied[] = strtolower( $code );
	}

	public function remove_coupon( $code ) {
		$this->applied = array_values( array_diff( $this->applied, array( strtolower( $code ) ) ) );
	}
}

function WC() {
	global $pepselect_test_wc;
	return $pepselect_test_wc;
}

function pepselect_assert( $condition, $message ) {
	if ( ! $condition ) {
		fwrite( STDERR, "FAIL: {$message}\n" );
		exit( 1 );
	}
}

require_once dirname( __DIR__ ) . '/pepselect-bogo-quantity/includes/class-pepselect-bogo-rule.php';

$defaults = PepSelect_BOGO_Rule::get_state();
pepselect_assert( false === $defaults['enabled'], 'new rule defaults off' );
pepselect_assert( array( 11, 12, 13, 14, 15, 16 ) === $defaults['product_ids'], 'legacy SKUs seed the selectable compounds' );
pepselect_assert( 0 === PepSelect_BOGO_Rule::free_vials( 4 ), 'four physical vials earn none' );
pepselect_assert( 1 === PepSelect_BOGO_Rule::free_vials( 5 ), 'five physical vials earn one' );
pepselect_assert( 1 === PepSelect_BOGO_Rule::free_vials( 9 ), 'nine physical vials earn one' );
pepselect_assert( 2 === PepSelect_BOGO_Rule::free_vials( 10 ), 'ten physical vials earn two' );

$pepselect_test_options[ PepSelect_BOGO_Rule::OPTION ] = array( 'enabled' => true, 'product_ids' => array( 11 ) );
$cart = new WC_Cart(
	array(
		array( 'product_id' => 11, 'variation_id' => 0, 'quantity' => 10, 'data' => $pepselect_test_products[11] ),
		array( 'product_id' => 22, 'variation_id' => 0, 'quantity' => 5, 'data' => $pepselect_test_products[22] ),
	)
);
$pepselect_test_wc = (object) array( 'cart' => $cart );

pepselect_assert( 200.0 === PepSelect_BOGO_Rule::cart_discount_amount( $cart ), 'discount prices only earned units on selected products' );
$coupon = PepSelect_BOGO_Rule::provide_virtual_coupon( false, PepSelect_BOGO_Rule::COUPON_CODE );
pepselect_assert( 'fixed_cart' === $coupon['discount_type'], 'managed WooCommerce coupon applies the discount' );
pepselect_assert( '200.00' === $coupon['amount'], 'coupon amount equals the earned vial value' );
pepselect_assert( array( 11 ) === $coupon['product_ids'], 'coupon is restricted to selected compounds' );

PepSelect_BOGO_Rule::sync_automatic_coupon( $cart );
pepselect_assert( $cart->has_discount( PepSelect_BOGO_Rule::COUPON_CODE ), 'enabled qualifying rule adds its coupon' );

$pepselect_test_options[ PepSelect_BOGO_Rule::OPTION ]['enabled'] = false;
$disabled_coupon = PepSelect_BOGO_Rule::provide_virtual_coupon( false, PepSelect_BOGO_Rule::COUPON_CODE );
pepselect_assert( '0.00' === $disabled_coupon['amount'], 'disabled session coupon remains valid at zero until removal' );
PepSelect_BOGO_Rule::sync_automatic_coupon( $cart );
pepselect_assert( ! $cart->has_discount( PepSelect_BOGO_Rule::COUPON_CODE ), 'disabling the rule removes its coupon' );
pepselect_assert( 0.0 === PepSelect_BOGO_Rule::cart_discount_amount( $cart ), 'disabled rule prices nothing' );

$pepselect_test_options[ PepSelect_BOGO_Rule::OPTION ] = array( 'enabled' => true, 'product_ids' => array( 11 ) );
pepselect_assert( PepSelect_BOGO_Rule::is_product_eligible( 33 ), 'selected parent makes its variation eligible' );

echo "Pep Select Buy 4 Get 1 rule checks passed.\n";

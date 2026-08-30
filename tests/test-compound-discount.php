<?php

define( 'ABSPATH', __DIR__ );

function absint( $value ) {
	return abs( (int) $value );
}

function wc_format_decimal( $value ) {
	return number_format( (float) $value, 2, '.', '' );
}

function sanitize_text_field( $value ) {
	return trim( strip_tags( (string) $value ) );
}

function sanitize_key( $value ) {
	return preg_replace( '/[^a-z0-9_\-]/', '', strtolower( (string) $value ) );
}

function wc_format_coupon_code( $value ) {
	return trim( (string) $value );
}

function is_wp_error( $value ) {
	return $value instanceof WP_Error;
}

function __( $value ) {
	return $value;
}

function wp_json_encode( $value ) {
	return json_encode( $value );
}

$pepselect_test_options = array(
	'pepselect_compound_discount_rule_v1' => array(
		'enabled'          => true,
		'product_ids'      => array( 11, 22 ),
		'match_mode'       => 'all',
		'discount_type'    => 'percent',
		'discount_amount'  => '20',
		'threshold_type'   => 'quantity',
		'threshold_amount' => '1',
		'label'            => 'GHK+NAD DUO',
	),
);

function get_option( $key, $default = false ) {
	global $pepselect_test_options;
	return array_key_exists( $key, $pepselect_test_options ) ? $pepselect_test_options[ $key ] : $default;
}

class WP_Error {
	public function __construct( $code, $message ) {
		$this->code    = $code;
		$this->message = $message;
	}
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

function WC() {
	global $pepselect_test_wc;
	return $pepselect_test_wc;
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
		return in_array( $code, $this->applied, true );
	}

	public function apply_coupon( $code ) {
		$this->applied[] = $code;
	}

	public function remove_coupon( $code ) {
		$this->applied = array_values( array_diff( $this->applied, array( $code ) ) );
	}
}

class PepSelect_Test_Cart extends WC_Cart {}

function pepselect_assert( $condition, $message ) {
	if ( ! $condition ) {
		fwrite( STDERR, "FAIL: {$message}\n" );
		exit( 1 );
	}
}

require_once dirname( __DIR__ ) . '/pepselect-bogo-quantity/includes/class-pepselect-compound-discount.php';

$migrated = PepSelect_Compound_Discount::get_state();
pepselect_assert( 3 === $migrated['schema_version'], 'legacy rule migrates to schema version 3' );
pepselect_assert( 1 === count( $migrated['rules'] ), 'legacy rule is preserved as one saved discount' );
pepselect_assert( 'GHK+NAD DUO' === $migrated['rules'][0]['label'], 'legacy customer label is preserved' );
pepselect_assert( in_array( 'pepselect-auto-compound', $migrated['retired_coupon_codes'], true ), 'legacy internal coupon is retired' );
$pepselect_test_wc = (object) array(
	'cart' => new PepSelect_Test_Cart(
		array(
			array( 'product_id' => 11, 'variation_id' => 0, 'quantity' => 1, 'line_subtotal' => 50 ),
			array( 'product_id' => 22, 'variation_id' => 0, 'quantity' => 1, 'line_subtotal' => 50 ),
		)
	),
);
$legacy_coupon = PepSelect_Compound_Discount::provide_virtual_coupon( false, 'pepselect-auto-compound' );
pepselect_assert( 'pepselect-auto-compound' === $legacy_coupon['code'], 'legacy coupon remains valid during silent cart migration' );

$sanitized = PepSelect_Compound_Discount::sanitize_rule(
	array(
		'enabled'          => 1,
		'product_ids'      => array( 11, '11', -22, 0 ),
		'match_mode'       => 'invalid',
		'discount_type'    => 'percent',
		'discount_amount'  => 150,
		'threshold_type'   => 'quantity',
		'threshold_amount' => 2.9,
		'label'            => '<b>Pair offer</b>',
		'id'               => 'Rule One',
	)
);

pepselect_assert( array( 11, 22 ) === $sanitized['product_ids'], 'product IDs are normalized and unique' );
pepselect_assert( 'all' === $sanitized['match_mode'], 'invalid match mode uses all' );
pepselect_assert( 100.0 === (float) $sanitized['discount_amount'], 'percentage is capped at 100' );
pepselect_assert( 2 === (int) $sanitized['threshold_amount'], 'quantity minimum is an integer' );
pepselect_assert( 'Pair offer' === $sanitized['label'], 'label is plain text' );
pepselect_assert( 'ruleone' === $sanitized['id'], 'rule ID is normalized' );
pepselect_assert( true === $sanitized['stackable'], 'legacy and omitted stacking settings default to stackable' );

$exclusive = PepSelect_Compound_Discount::sanitize_rule( array_merge( $sanitized, array( 'stackable' => '0' ) ) );
pepselect_assert( false === $exclusive['stackable'], 'compound rules can be exclusive' );

$long_label = PepSelect_Compound_Discount::sanitize_rule(
	array(
		'product_ids'      => array( 11 ),
		'discount_amount'  => 10,
		'threshold_amount' => 1,
		'label'            => 'Tesamorelin+Ipamorelin+Long',
	)
);
pepselect_assert( 24 === strlen( $long_label['label'] ), 'customer label is capped at 24 characters' );
pepselect_assert( $long_label['label'] === PepSelect_Compound_Discount::coupon_code_for_rule( $long_label ), 'customer label is the visible coupon code' );

$rule = array(
	'id'               => 'pair-offer',
	'enabled'          => true,
	'product_ids'      => array( 11, 22 ),
	'match_mode'       => 'all',
	'discount_type'    => 'percent',
	'discount_amount'  => '10',
	'threshold_type'   => 'quantity',
	'threshold_amount' => '3',
	'label'            => 'Pair offer',
);

$qualifying = new PepSelect_Test_Cart(
	array(
		array( 'product_id' => 11, 'variation_id' => 0, 'quantity' => 2, 'line_subtotal' => 80 ),
		array( 'product_id' => 22, 'variation_id' => 0, 'quantity' => 1, 'line_subtotal' => 70 ),
		array( 'product_id' => 99, 'variation_id' => 0, 'quantity' => 20, 'line_subtotal' => 500 ),
	)
);

pepselect_assert( PepSelect_Compound_Discount::cart_qualifies( $qualifying, $rule ), 'all compounds and eligible quantity qualify' );

$missing_compound = new PepSelect_Test_Cart(
	array( array( 'product_id' => 11, 'variation_id' => 0, 'quantity' => 10, 'line_subtotal' => 500 ) )
);
pepselect_assert( ! PepSelect_Compound_Discount::cart_qualifies( $missing_compound, $rule ), 'all mode requires every selected compound' );

$rule['match_mode'] = 'any';
pepselect_assert( PepSelect_Compound_Discount::cart_qualifies( $missing_compound, $rule ), 'any mode accepts one selected compound' );

$rule['threshold_type']   = 'subtotal';
$rule['threshold_amount'] = '501';
pepselect_assert( ! PepSelect_Compound_Discount::cart_qualifies( $missing_compound, $rule ), 'unselected products do not count toward subtotal minimum' );

$rule['product_ids']      = array( 44 );
$rule['threshold_amount'] = '120';
$variation_cart = new PepSelect_Test_Cart(
	array( array( 'product_id' => 33, 'variation_id' => 44, 'quantity' => 1, 'line_subtotal' => 120 ) )
);
pepselect_assert( PepSelect_Compound_Discount::cart_qualifies( $variation_cart, $rule ), 'selected variations qualify independently' );

$rule['enabled'] = false;
pepselect_assert( ! PepSelect_Compound_Discount::cart_qualifies( $variation_cart, $rule ), 'disabled rule never qualifies' );

$pepselect_test_options['pepselect_compound_discount_rules_v2'] = array(
	'schema_version'       => 2,
	'retired_coupon_codes' => array(),
	'rules'                => array(
		array_merge( $rule, array( 'id' => 'stack-a', 'enabled' => true, 'product_ids' => array( 11 ), 'threshold_type' => 'quantity', 'threshold_amount' => 1, 'label' => 'PAIR A' ) ),
		array_merge( $rule, array( 'id' => 'stack-b', 'enabled' => true, 'product_ids' => array( 11 ), 'threshold_type' => 'quantity', 'threshold_amount' => 1, 'label' => 'PAIR B' ) ),
	),
);
$stacking_cart = new PepSelect_Test_Cart( array( array( 'product_id' => 11, 'variation_id' => 0, 'quantity' => 1, 'line_subtotal' => 50 ) ) );
PepSelect_Compound_Discount::sync_automatic_coupons( $stacking_cart );
pepselect_assert( array( 'PAIR A', 'PAIR B' ) === $stacking_cart->applied, 'multiple qualifying discounts apply together' );

fwrite( STDOUT, "Pep Select compound discount behavior checks passed.\n" );

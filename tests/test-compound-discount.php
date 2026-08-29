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

function is_wp_error( $value ) {
	return $value instanceof WP_Error;
}

function __( $value ) {
	return $value;
}

function wp_json_encode( $value ) {
	return json_encode( $value );
}

class WP_Error {
	public function __construct( $code, $message ) {
		$this->code    = $code;
		$this->message = $message;
	}
}

class PepSelect_Test_Cart {
	private $items;

	public function __construct( $items ) {
		$this->items = $items;
	}

	public function get_cart() {
		return $this->items;
	}
}

function pepselect_assert( $condition, $message ) {
	if ( ! $condition ) {
		fwrite( STDERR, "FAIL: {$message}\n" );
		exit( 1 );
	}
}

require_once dirname( __DIR__ ) . '/pepselect-bogo-quantity/includes/class-pepselect-compound-discount.php';

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
	)
);

pepselect_assert( array( 11, 22 ) === $sanitized['product_ids'], 'product IDs are normalized and unique' );
pepselect_assert( 'all' === $sanitized['match_mode'], 'invalid match mode uses all' );
pepselect_assert( 100.0 === (float) $sanitized['discount_amount'], 'percentage is capped at 100' );
pepselect_assert( 2 === (int) $sanitized['threshold_amount'], 'quantity minimum is an integer' );
pepselect_assert( 'Pair offer' === $sanitized['label'], 'label is plain text' );

$rule = array(
	'schema_version'   => 1,
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

fwrite( STDOUT, "Pep Select compound discount behavior checks passed.\n" );

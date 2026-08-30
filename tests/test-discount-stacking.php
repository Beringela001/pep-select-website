<?php

define( 'ABSPATH', __DIR__ );

function wc_format_coupon_code( $value ) { return strtolower( trim( (string) $value ) ); }
function is_admin() { return false; }
function wp_doing_ajax() { return false; }
function wc_get_notices() { return array(); }
function wc_set_notices() {}

$pepselect_candidates = array( 'bogo' => array(), 'compound' => array(), 'sitewide' => array() );

class PepSelect_BOGO_Rule { public static function discount_candidates() { global $pepselect_candidates; return $pepselect_candidates['bogo']; } }
class PepSelect_Compound_Discount { public static function discount_candidates() { global $pepselect_candidates; return $pepselect_candidates['compound']; } }
class PepSelect_Sitewide_Discount { public static function discount_candidates() { global $pepselect_candidates; return $pepselect_candidates['sitewide']; } }

class WC_Cart {
	public $applied = array();
	public function has_discount( $code ) { return in_array( strtolower( $code ), $this->applied, true ); }
	public function apply_coupon( $code ) { $this->applied[] = strtolower( $code ); }
	public function remove_coupon( $code ) { $this->applied = array_values( array_diff( $this->applied, array( strtolower( $code ) ) ) ); }
}

function pepselect_assert( $condition, $message ) {
	if ( ! $condition ) { fwrite( STDERR, "FAIL: {$message}\n" ); exit( 1 ); }
}

require_once dirname( __DIR__ ) . '/pepselect-bogo-quantity/includes/class-pepselect-discount-stacking.php';

$cart = new WC_Cart();
$pepselect_candidates['bogo'][] = array( 'code' => 'BOGO', 'qualifies' => true, 'stackable' => true, 'estimated_amount' => 25 );
$pepselect_candidates['compound'][] = array( 'code' => 'PAIR', 'qualifies' => true, 'stackable' => true, 'estimated_amount' => 10 );
PepSelect_Discount_Stacking::sync( $cart );
pepselect_assert( array( 'bogo', 'pair' ) === $cart->applied, 'all qualifying stackable discounts apply together' );

$pepselect_candidates['sitewide'] = array(
	array( 'code' => 'VIP20', 'qualifies' => true, 'stackable' => false, 'estimated_amount' => 20 ),
	array( 'code' => 'VIP30', 'qualifies' => true, 'stackable' => false, 'estimated_amount' => 30 ),
);
PepSelect_Discount_Stacking::sync( $cart );
pepselect_assert( array( 'vip30' ) === $cart->applied, 'the best exclusive discount replaces every stackable discount' );

$pepselect_candidates['sitewide'][1]['qualifies'] = false;
PepSelect_Discount_Stacking::sync( $cart );
pepselect_assert( array( 'vip20' ) === $cart->applied, 'exclusive selection updates when qualification changes' );

fwrite( STDOUT, "Pep Select discount stacking checks passed.\n" );

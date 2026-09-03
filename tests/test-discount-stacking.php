<?php

define( 'ABSPATH', __DIR__ );

function wc_format_coupon_code( $value ) { return strtolower( trim( (string) $value ) ); }
function is_admin() { return false; }
function wp_doing_ajax() { return false; }
function wc_get_notices() { return array(); }
function wc_set_notices() {}
function __( $value ) { return $value; }

$pepselect_candidates = array( 'bogo' => array(), 'compound' => array(), 'sitewide' => array() );

class PepSelect_BOGO_Rule { public static function discount_candidates() { global $pepselect_candidates; return $pepselect_candidates['bogo']; } }
class PepSelect_Compound_Discount { public static function discount_candidates() { global $pepselect_candidates; return $pepselect_candidates['compound']; } }
$pepselect_takeover_rule = null;
class PepSelect_Sitewide_Discount {
	public static function discount_candidates() { global $pepselect_candidates; return $pepselect_candidates['sitewide']; }
	public static function active_takeover_rule() { global $pepselect_takeover_rule; return $pepselect_takeover_rule; }
	public static function coupon_code_for_rule( $rule ) { return $rule['code']; }
	public static function cart_qualifies( $cart, $rule ) { return ! empty( $rule['qualifies'] ); }
	public static function coupon_is_allowed( $coupon, $rule ) { return in_array( strtolower( $coupon->get_code() ), $rule['allowed'], true ); }
	public static function coupon_is_override( $coupon, $rule ) { return in_array( strtolower( $coupon->get_code() ), $rule['override'], true ); }
}

class WC_Coupon {
	private $code;
	public function __construct( $code ) { $this->code = strtolower( $code ); }
	public function get_code() { return $this->code; }
}

class WC_Cart {
	public $applied = array();
	public function has_discount( $code ) { return in_array( strtolower( $code ), $this->applied, true ); }
	public function apply_coupon( $code ) { $this->applied[] = strtolower( $code ); }
	public function remove_coupon( $code ) { $this->applied = array_values( array_diff( $this->applied, array( strtolower( $code ) ) ) ); }
	public function get_applied_coupons() { return $this->applied; }
}

$pepselect_test_wc = null;
function WC() { global $pepselect_test_wc; return $pepselect_test_wc; }

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

$pepselect_takeover_rule = array( 'code' => 'SITE30', 'qualifies' => true, 'allowed' => array( 'military10' ), 'override' => array( 'laborday40' ) );
$pepselect_candidates['sitewide'] = array( array( 'code' => 'SITE30', 'qualifies' => true, 'stackable' => false, 'estimated_amount' => 30 ) );
$cart->applied = array( 'bogo', 'pair', 'random10', 'military10' );
$pepselect_test_wc = (object) array( 'cart' => $cart );
PepSelect_Discount_Stacking::sync( $cart );
pepselect_assert( array( 'military10', 'site30' ) === $cart->applied, 'takeover keeps only its sitewide coupon and explicitly allowed Woo coupons' );

$pepselect_takeover_rule['allowed'][] = 'bogo';
PepSelect_Discount_Stacking::sync( $cart );
pepselect_assert( array( 'military10', 'site30', 'bogo' ) === $cart->applied, 'an explicitly allowed automatic BOGO coupon is restored during takeover' );
$pepselect_takeover_rule['allowed'] = array( 'military10' );
PepSelect_Discount_Stacking::sync( $cart );
pepselect_assert( array( 'military10', 'site30' ) === $cart->applied, 'removing the BOGO exception removes its coupon again' );

$cart->applied[] = 'laborday40';
PepSelect_Discount_Stacking::sync( $cart );
pepselect_assert( array( 'laborday40' ) === $cart->applied, 'replacement code removes the sitewide coupon and every other discount' );

$blocked = new WC_Coupon( 'random10' );
$allowed = new WC_Coupon( 'military10' );
$override = new WC_Coupon( 'laborday40' );
pepselect_assert( ! PepSelect_Discount_Stacking::coupon_is_valid( true, $blocked ), 'unlisted Woo coupon is rejected during takeover' );
pepselect_assert( PepSelect_Discount_Stacking::coupon_is_valid( true, $allowed ), 'allowlisted Woo coupon remains valid during takeover' );
pepselect_assert( PepSelect_Discount_Stacking::coupon_is_valid( true, $override ), 'replacement Woo coupon remains valid during takeover' );

fwrite( STDOUT, "Pep Select discount stacking checks passed.\n" );

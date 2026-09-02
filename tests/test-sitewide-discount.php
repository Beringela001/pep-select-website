<?php

define( 'ABSPATH', __DIR__ );

function absint( $value ) { return abs( (int) $value ); }
function wc_format_decimal( $value ) { return rtrim( rtrim( number_format( (float) $value, 2, '.', '' ), '0' ), '.' ); }
function wc_format_coupon_code( $value ) { return trim( (string) $value ); }
function sanitize_text_field( $value ) { return trim( strip_tags( (string) $value ) ); }
function sanitize_key( $value ) { return preg_replace( '/[^a-z0-9_\-]/', '', strtolower( (string) $value ) ); }
function __( $value ) { return $value; }
function wp_json_encode( $value ) { return json_encode( $value ); }
function is_wp_error( $value ) { return $value instanceof WP_Error; }
function get_user_meta() { return false; }
function is_admin() { return false; }
function wc_price( $value ) { return '$' . number_format( (float) $value, 2, '.', '' ); }
function wc_get_price_to_display( $product, $args ) { return (float) $args['price']; }
function wp_strip_all_tags( $value ) { return strip_tags( $value ); }
function esc_attr( $value ) { return htmlspecialchars( (string) $value, ENT_QUOTES ); }
function esc_html( $value ) { return htmlspecialchars( (string) $value, ENT_QUOTES ); }

$pepselect_test_user_id = 0;
$pepselect_test_order_count = 0;
$pepselect_test_options = array();
$pepselect_ineligible_products = array();

function get_current_user_id() { global $pepselect_test_user_id; return $pepselect_test_user_id; }
function wc_get_customer_order_count() { global $pepselect_test_order_count; return $pepselect_test_order_count; }
function get_option( $key, $default = false ) { global $pepselect_test_options; return $pepselect_test_options[ $key ] ?? $default; }
function update_option( $key, $value ) { global $pepselect_test_options; $pepselect_test_options[ $key ] = $value; }
function get_userdata( $user_id ) { return (object) array( 'ID' => $user_id, 'roles' => array( 'subscriber' ), 'display_name' => 'Researcher', 'user_email' => 'researcher@example.com' ); }
function apply_filters( $hook, $value, ...$args ) {
	if ( 'pepselect_sitewide_discount_product_eligible' === $hook ) {
		global $pepselect_ineligible_products;
		return $value && ! in_array( (int) $args[0], $pepselect_ineligible_products, true );
	}
	return $value;
}

class WP_Error {
	public function __construct( $code, $message ) { $this->code = $code; $this->message = $message; }
}

class WC_Product {
	private $id;
	private $price;
	private $parent_id;
	public function __construct( $id = 0, $price = 0, $parent_id = 0 ) { $this->id = $id; $this->price = $price; $this->parent_id = $parent_id; }
	public function is_type() { return false; }
	public function get_id() { return $this->id; }
	public function get_price() { return $this->price; }
	public function get_parent_id() { return $this->parent_id; }
}

function wc_get_product() { return new WC_Product(); }

class WC_Cart {
	private $items;
	public function __construct( $items ) { $this->items = $items; }
	public function get_cart() { return $this->items; }
}

function WC() { global $pepselect_test_wc; return $pepselect_test_wc; }

function pepselect_assert( $condition, $message ) {
	if ( ! $condition ) {
		fwrite( STDERR, "FAIL: {$message}\n" );
		exit( 1 );
	}
}

require_once dirname( __DIR__ ) . '/pepselect-bogo-quantity/includes/class-pepselect-sitewide-discount.php';

$rule = PepSelect_Sitewide_Discount::sanitize_rule(
	array(
		'id' => 'Launch Sale', 'enabled' => true, 'discount_type' => 'percent', 'discount_amount' => 20,
		'threshold_type' => 'none', 'threshold_amount' => 999, 'audience' => 'everyone',
		'excluded_product_ids' => array( '99', '99', '0' ), 'stackable' => '0', 'label' => 'Summer Compound Savings Now',
	)
);
pepselect_assert( 'launchsale' === $rule['id'], 'sitewide rule IDs are normalized' );
pepselect_assert( '0' === $rule['threshold_amount'], 'no-minimum rules ignore a supplied amount' );
pepselect_assert( false === $rule['stackable'], 'sitewide rules can be exclusive' );
pepselect_assert( array( 99 ) === $rule['excluded_product_ids'], 'sitewide exclusions are normalized and deduplicated' );
pepselect_assert( 24 === strlen( $rule['label'] ), 'sitewide labels are capped at 24 characters' );

$cart = new WC_Cart(
	array(
		array( 'product_id' => 11, 'variation_id' => 0, 'quantity' => 2, 'line_subtotal' => 100 ),
		array( 'product_id' => 99, 'variation_id' => 0, 'quantity' => 4, 'line_subtotal' => 200 ),
	)
);
$pepselect_test_wc = (object) array( 'cart' => $cart );
pepselect_assert( PepSelect_Sitewide_Discount::cart_qualifies( $cart, $rule ), 'eligible compounds qualify without a minimum' );
pepselect_assert( 20.0 === PepSelect_Sitewide_Discount::estimated_discount_amount( $cart, $rule ), 'sitewide discount omits excluded catalog items' );

$rule['threshold_type'] = 'subtotal';
$rule['threshold_amount'] = '101';
pepselect_assert( ! PepSelect_Sitewide_Discount::cart_qualifies( $cart, $rule ), 'order subtotal minimum omits excluded catalog items' );

$rule['threshold_type'] = 'none';
$rule['audience'] = 'subscribers';
$pepselect_test_user_id = 7;
pepselect_assert( PepSelect_Sitewide_Discount::cart_qualifies( $cart, $rule ), 'logged-in subscribers qualify dynamically' );

$rule['audience'] = 'specific';
$rule['customer_ids'] = array( 8 );
pepselect_assert( ! PepSelect_Sitewide_Discount::cart_qualifies( $cart, $rule ), 'unlisted customer does not qualify' );
$rule['customer_ids'] = array( 7 );
pepselect_assert( PepSelect_Sitewide_Discount::cart_qualifies( $cart, $rule ), 'specific selected customer qualifies' );

$pepselect_test_options[ PepSelect_Sitewide_Discount::OPTION ] = array( 'rules' => array( $rule ) );
$coupon = PepSelect_Sitewide_Discount::provide_virtual_coupon( false, $rule['label'] );
pepselect_assert( true === $coupon['individual_use'], 'exclusive sitewide coupon blocks stacking' );
pepselect_assert( array( 11 ) === $coupon['product_ids'], 'coupon omits excluded products currently in cart' );

$display_rule = PepSelect_Sitewide_Discount::sanitize_rule(
	array(
		'id' => 'all-items-20', 'enabled' => true, 'discount_type' => 'percent', 'discount_amount' => 20,
		'threshold_type' => 'none', 'audience' => 'everyone', 'stackable' => true, 'label' => '20% off',
	)
);
$pepselect_test_options[ PepSelect_Sitewide_Discount::OPTION ] = array( 'rules' => array( $display_rule ) );
$price_html = PepSelect_Sitewide_Discount::price_html( '$79.99', new WC_Product( 11, 79.99 ) );
pepselect_assert( false !== strpos( $price_html, '<del aria-hidden="true">$79.99</del>' ), 'storefront price crosses out the original price' );
pepselect_assert( false !== strpos( $price_html, '20% off' ), 'storefront price shows the discount percentage' );
pepselect_assert( false !== strpos( $price_html, '<ins>$63.99</ins>' ), 'storefront price shows the discounted price' );

$display_rule['excluded_product_ids'] = array( 11 );
$pepselect_test_options[ PepSelect_Sitewide_Discount::OPTION ] = array( 'rules' => array( $display_rule ) );
pepselect_assert( '$79.99' === PepSelect_Sitewide_Discount::price_html( '$79.99', new WC_Product( 11, 79.99 ) ), 'rule exclusion keeps the regular storefront price' );
$display_rule['excluded_product_ids'] = array( 10 );
$pepselect_test_options[ PepSelect_Sitewide_Discount::OPTION ] = array( 'rules' => array( $display_rule ) );
pepselect_assert( '$79.99' === PepSelect_Sitewide_Discount::price_html( '$79.99', new WC_Product( 11, 79.99, 10 ) ), 'excluding a variable parent excludes its variations' );

$pepselect_ineligible_products[] = 11;
pepselect_assert( '$79.99' === PepSelect_Sitewide_Discount::price_html( '$79.99', new WC_Product( 11, 79.99 ) ), 'Ops eligibility filter can exclude a product' );

fwrite( STDOUT, "Pep Select sitewide discount behavior checks passed.\n" );

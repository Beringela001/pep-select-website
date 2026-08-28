<?php
/**
 * Checkout and shipping-rate restrictions for Pep Select.
 */

defined( 'ABSPATH' ) || exit;

final class PepSelect_Shipping_Restrictions {
	const MESSAGE = 'Pep Select ships only to the contiguous 48 states and Washington, D.C. Enter a shipping address within this area.';

	/** @var PepSelect_Shipping_Restrictions|null */
	private static $instance;

	/**
	 * Contiguous states plus Washington, D.C.
	 *
	 * @var string[]
	 */
	private const ALLOWED_STATES = array(
		'AL', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC', 'FL', 'GA',
		'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD', 'MA',
		'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM',
		'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD',
		'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY',
	);

	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {}

	public function boot(): void {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'woocommerce_after_checkout_validation', array( $this, 'validate_checkout' ), 20, 2 );
		add_filter( 'woocommerce_package_rates', array( $this, 'filter_package_rates' ), 100, 2 );
	}

	public function enqueue_assets(): void {
		if ( ! function_exists( 'is_checkout' ) || ! is_checkout() || ( function_exists( 'is_order_received_page' ) && is_order_received_page() ) ) {
			return;
		}

		wp_enqueue_style(
			'pepselect-shipping-restrictions',
			plugins_url( 'assets/shipping-restrictions.css', PEPSELECT_SHIPPING_RESTRICTIONS_FILE ),
			array(),
			PEPSELECT_SHIPPING_RESTRICTIONS_VERSION
		);

		wp_enqueue_script(
			'pepselect-shipping-restrictions',
			plugins_url( 'assets/shipping-restrictions.js', PEPSELECT_SHIPPING_RESTRICTIONS_FILE ),
			array( 'jquery' ),
			PEPSELECT_SHIPPING_RESTRICTIONS_VERSION,
			true
		);

		wp_localize_script(
			'pepselect-shipping-restrictions',
			'pepSelectShippingRestrictions',
			array(
				'allowedStates' => self::ALLOWED_STATES,
				'message'       => self::MESSAGE,
			)
		);
	}

	/**
	 * Prevent order submission when the shipping destination is excluded.
	 *
	 * @param array    $data   Submitted checkout data.
	 * @param WP_Error $errors Checkout validation errors.
	 */
	public function validate_checkout( array $data, $errors ): void {
		$prefix   = ! empty( $data['ship_to_different_address'] ) ? 'shipping' : 'billing';
		$country  = $this->clean_checkout_value( $data[ $prefix . '_country' ] ?? '' );
		$state    = $this->clean_checkout_value( $data[ $prefix . '_state' ] ?? '' );
		$postcode = $this->clean_checkout_value( $data[ $prefix . '_postcode' ] ?? '' );

		if ( '' === $country || '' === $state || '' === $postcode ) {
			return;
		}

		if ( self::address_is_allowed( $country, $state, $postcode ) ) {
			return;
		}

		$errors->add(
			'pepselect_shipping_area',
			self::MESSAGE,
			array( 'id' => $prefix . '_postcode' )
		);
	}

	/**
	 * Remove every rate for an excluded destination, including cart estimates.
	 *
	 * @param array $rates   Available rates.
	 * @param array $package Shipping package.
	 * @return array
	 */
	public function filter_package_rates( array $rates, array $package ): array {
		$destination = isset( $package['destination'] ) && is_array( $package['destination'] )
			? $package['destination']
			: array();

		$country  = $this->clean_checkout_value( $destination['country'] ?? '' );
		$state    = $this->clean_checkout_value( $destination['state'] ?? '' );
		$postcode = $this->clean_checkout_value( $destination['postcode'] ?? '' );

		if ( '' !== $country && 'US' !== strtoupper( $country ) ) {
			return array();
		}

		if ( '' !== $state && ! self::state_is_allowed( $state ) ) {
			return array();
		}

		if ( '' !== $postcode && self::postcode_is_excluded( $postcode ) ) {
			return array();
		}

		return $rates;
	}

	public static function address_is_allowed( string $country, string $state, string $postcode ): bool {
		return 'US' === strtoupper( trim( $country ) )
			&& self::state_is_allowed( $state )
			&& ! self::postcode_is_excluded( $postcode );
	}

	public static function state_is_allowed( string $state ): bool {
		return in_array( strtoupper( trim( $state ) ), self::ALLOWED_STATES, true );
	}

	public static function postcode_is_excluded( string $postcode ): bool {
		$digits = preg_replace( '/\D+/', '', $postcode );

		if ( strlen( $digits ) < 3 ) {
			return false;
		}

		$prefix = (int) substr( $digits, 0, 3 );

		return ( $prefix >= 6 && $prefix <= 9 )
			|| ( $prefix >= 90 && $prefix <= 98 )
			|| 340 === $prefix
			|| ( $prefix >= 962 && $prefix <= 969 )
			|| ( $prefix >= 995 && $prefix <= 999 );
	}

	private function clean_checkout_value( $value ): string {
		$value = is_scalar( $value ) ? (string) $value : '';

		return function_exists( 'wc_clean' ) ? wc_clean( $value ) : trim( $value );
	}
}

<?php
/**
 * Checkout address integrity and destination-specific shipping rules.
 */

defined( 'ABSPATH' ) || exit;

final class PepSelect_Shipping_Restrictions {
	const UNSUPPORTED_MESSAGE = 'Pep Select does not currently ship to this destination. Enter an address in the 50 U.S. states, Washington, D.C., or Puerto Rico.';

	/** @var PepSelect_Shipping_Restrictions|null */
	private static $instance;

	/** @var string[] */
	private const ALLOWED_STATES = array(
		'AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC', 'FL',
		'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME',
		'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH',
		'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'PR',
		'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV',
		'WI', 'WY',
	);

	/** @var array<string,string> */
	private const REGION_NAMES = array(
		'AK' => 'Alaska',
		'HI' => 'Hawaii',
		'PR' => 'Puerto Rico',
	);

	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {}

	public function boot(): void {
		add_filter( 'woocommerce_states', array( $this, 'add_puerto_rico_state' ), 20 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'woocommerce_checkout_update_order_review', array( $this, 'clear_destination_rate_cache' ), 5 );
		add_action( 'woocommerce_after_checkout_validation', array( $this, 'validate_checkout' ), 20, 2 );
		add_filter( 'woocommerce_package_rates', array( $this, 'filter_package_rates' ), 100, 2 );
		add_filter( 'woocommerce_checkout_fields', array( $this, 'label_state_as_territory' ), 30 );
		add_filter( 'fc_gaa_google_autocomplete_js_settings', array( $this, 'configure_google_address_autocomplete' ), 100 );
	}

	/** @param array<string,array<string,string>> $states WooCommerce states by country. */
	public function add_puerto_rico_state( array $states ): array {
		$states['US']['PR'] = 'Puerto Rico';
		return $states;
	}

	/**
	 * Let Google suggest Puerto Rico while WooCommerce stores it as US:PR.
	 *
	 * @param array $settings Fluid Checkout Google Autocomplete settings.
	 */
	public function configure_google_address_autocomplete( array $settings ): array {
		$input_ids = array(
			'address_1',
			'shipping_address_1',
			'billing_address_1',
			'shipping-address_1',
			'billing-address_1',
			'_shipping_address_1',
			'_billing_address_1',
		);

		if ( ! isset( $settings['allowedCountries'] ) || ! is_array( $settings['allowedCountries'] ) ) {
			$settings['allowedCountries'] = array();
		}

		foreach ( $input_ids as $input_id ) {
			$countries = isset( $settings['allowedCountries'][ $input_id ] ) && is_array( $settings['allowedCountries'][ $input_id ] )
				? $settings['allowedCountries'][ $input_id ]
				: array();

			$settings['allowedCountries'][ $input_id ] = array_values(
				array_unique(
					array_merge( $countries, array( 'US', 'PR' ) )
				)
			);
		}

		return $settings;
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
				'allowedStates'      => self::ALLOWED_STATES,
				'regionNames'        => self::REGION_NAMES,
				'unsupportedMessage' => self::UNSUPPORTED_MESSAGE,
			)
		);
	}

	/** @param array $fields Checkout fields. */
	public function label_state_as_territory( array $fields ): array {
		foreach ( array( 'billing', 'shipping' ) as $section ) {
			$key = $section . '_state';
			if ( isset( $fields[ $section ][ $key ] ) ) {
				$fields[ $section ][ $key ]['label'] = 'State / Territory';
			}
		}

		return $fields;
	}

	/**
	 * @param array    $data   Submitted checkout data.
	 * @param WP_Error $errors Checkout validation errors.
	 */
	public function validate_checkout( array $data, $errors ): void {
		$address = self::checkout_destination( $data );
		if ( ! self::address_is_complete( $address ) ) {
			return;
		}

		$message = self::address_error_message( $address['country'], $address['state'], $address['postcode'] );
		if ( '' !== $message ) {
			$errors->add( 'pepselect_shipping_address', $message, array( 'id' => $address['prefix'] . '_postcode' ) );
		}
	}

	/** @param string $posted_data Serialized checkout fields. */
	public function clear_destination_rate_cache( string $posted_data ): void {
		$data = array();
		wp_parse_str( $posted_data, $data );
		$address = self::checkout_destination( $data );

		if ( ! self::destination_requires_rate_refresh( $address ) || ! function_exists( 'WC' ) || ! WC()->session || ! WC()->cart ) {
			return;
		}

		foreach ( array_keys( WC()->cart->get_shipping_packages() ) as $package_key ) {
			WC()->session->__unset( 'shipping_for_package_' . $package_key );
		}
	}

	/**
	 * @param array $rates   Available rates.
	 * @param array $package Shipping package.
	 * @return array
	 */
	public function filter_package_rates( array $rates, array $package ): array {
		$destination = isset( $package['destination'] ) && is_array( $package['destination'] ) ? $package['destination'] : array();
		$country  = $this->clean_checkout_value( $destination['country'] ?? '' );
		$state    = $this->clean_checkout_value( $destination['state'] ?? '' );
		$postcode = $this->clean_checkout_value( $destination['postcode'] ?? '' );

		if ( '' !== self::address_error_message( $country, $state, $postcode ) ) {
			return array();
		}

		if ( ! self::destination_is_usps_only( $country, $state, $postcode ) ) {
			return $rates;
		}

		return array_filter(
			$rates,
			static function ( $rate ): bool {
				$label = is_object( $rate ) && method_exists( $rate, 'get_label' ) ? (string) $rate->get_label() : '';
				return false !== stripos( $label, 'USPS' );
			}
		);
	}

	public static function address_is_allowed( string $country, string $state, string $postcode ): bool {
		return '' === self::address_error_message( $country, $state, $postcode );
	}

	public static function checkout_data_is_excluded( array $data ): bool {
		$address = self::checkout_destination( $data );
		if ( ! self::address_is_complete( $address ) ) {
			return false;
		}

		return ! self::address_is_allowed( $address['country'], $address['state'], $address['postcode'] );
	}

	public static function state_is_allowed( string $state ): bool {
		return in_array( strtoupper( trim( $state ) ), self::ALLOWED_STATES, true );
	}

	public static function expected_region_for_postcode( string $postcode ): string {
		$digits = preg_replace( '/\D+/', '', $postcode );
		if ( strlen( $digits ) < 5 ) {
			return '';
		}

		$zip = (int) substr( $digits, 0, 5 );
		if ( ( $zip >= 601 && $zip <= 799 ) || ( $zip >= 901 && $zip <= 988 ) ) {
			return 'PR';
		}
		if ( $zip >= 96701 && $zip <= 96898 ) {
			return 'HI';
		}
		if ( $zip >= 99501 && $zip <= 99950 ) {
			return 'AK';
		}

		return '';
	}

	public static function postcode_is_excluded( string $postcode ): bool {
		$digits = preg_replace( '/\D+/', '', $postcode );
		if ( strlen( $digits ) < 5 ) {
			return false;
		}

		$zip    = (int) substr( $digits, 0, 5 );
		$prefix = (int) substr( $digits, 0, 3 );

		return 8 === $prefix
			|| ( $prefix >= 90 && $prefix <= 98 )
			|| 340 === $prefix
			|| ( $prefix >= 962 && $prefix <= 966 )
			|| 969 === $prefix
			|| 96799 === $zip;
	}

	public static function destination_is_usps_only( string $country, string $state, string $postcode ): bool {
		$country  = strtoupper( trim( $country ) );
		$state    = strtoupper( trim( $state ) );
		$expected = self::expected_region_for_postcode( $postcode );

		return 'US' === $country
			&& in_array( $state, array( 'AK', 'PR' ), true )
			&& $state === $expected;
	}

	public static function address_error_message( string $country, string $state, string $postcode ): string {
		$country = strtoupper( trim( $country ) );
		$state   = strtoupper( trim( $state ) );

		if ( self::postcode_is_excluded( $postcode ) ) {
			return self::UNSUPPORTED_MESSAGE;
		}

		$expected = self::expected_region_for_postcode( $postcode );
		if ( 'US' !== $country || ! self::state_is_allowed( $state ) ) {
			return self::UNSUPPORTED_MESSAGE;
		}

		if ( '' !== $expected && $expected !== $state ) {
			return sprintf(
				'This ZIP code belongs to %s. Select %s in the State / Territory field.',
				self::REGION_NAMES[ $expected ],
				self::REGION_NAMES[ $expected ]
			);
		}

		if ( in_array( $state, array( 'AK', 'HI', 'PR' ), true ) && $expected !== $state ) {
			return sprintf(
				'The ZIP code does not match %s. Check the ZIP code or select the correct State / Territory.',
				self::REGION_NAMES[ $state ]
			);
		}

		return '';
	}

	/** @return array{prefix:string,country:string,state:string,postcode:string} */
	private static function checkout_destination( array $data ): array {
		$prefix = ! empty( $data['ship_to_different_address'] ) ? 'shipping' : 'billing';

		return array(
			'prefix'   => $prefix,
			'country'  => is_scalar( $data[ $prefix . '_country' ] ?? null ) ? trim( (string) $data[ $prefix . '_country' ] ) : '',
			'state'    => is_scalar( $data[ $prefix . '_state' ] ?? null ) ? trim( (string) $data[ $prefix . '_state' ] ) : '',
			'postcode' => is_scalar( $data[ $prefix . '_postcode' ] ?? null ) ? trim( (string) $data[ $prefix . '_postcode' ] ) : '',
		);
	}

	/** @param array{country:string,state:string,postcode:string} $address */
	private static function destination_requires_rate_refresh( array $address ): bool {
		return '' !== self::address_error_message( $address['country'], $address['state'], $address['postcode'] )
			|| self::destination_is_usps_only( $address['country'], $address['state'], $address['postcode'] )
			|| in_array( strtoupper( $address['state'] ), array( 'AK', 'HI', 'PR' ), true );
	}

	/** @param array{country:string,state:string,postcode:string} $address */
	private static function address_is_complete( array $address ): bool {
		return '' !== $address['country']
			&& '' !== $address['postcode']
			&& '' !== $address['state'];
	}

	private function clean_checkout_value( $value ): string {
		$value = is_scalar( $value ) ? (string) $value : '';
		return function_exists( 'wc_clean' ) ? wc_clean( $value ) : trim( $value );
	}
}

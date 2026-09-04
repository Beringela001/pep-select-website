<?php
/**
 * Checkout address integrity and destination-specific shipping rules.
 */

defined( 'ABSPATH' ) || exit;

final class PepSelect_Shipping_Restrictions {
	const UNSUPPORTED_MESSAGE = 'Pep Select does not currently ship to this destination. Enter an address in the 50 U.S. states, Washington, D.C., or Puerto Rico.';
	const INCOMPLETE_ADDRESS_MESSAGE = 'Enter a complete street address, including the street number and street name.';
	const UNVERIFIED_ADDRESS_MESSAGE = 'We could not verify this delivery address. Check the street, city, state, and ZIP code, then try again.';
	const ADDRESS_REVIEW_MESSAGE = 'This address could not be confirmed as entered. Choose the suggested address or correct the street, city, state, and ZIP code.';

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
		add_filter( 'woocommerce_checkout_posted_data', array( $this, 'synchronize_billing_to_shipping' ), 100 );
		add_action( 'woocommerce_after_checkout_validation', array( $this, 'validate_checkout' ), 20, 2 );
		add_filter( 'woocommerce_package_rates', array( $this, 'filter_package_rates' ), 100, 2 );
		add_filter( 'woocommerce_checkout_fields', array( $this, 'label_state_as_territory' ), 30 );
		add_filter( 'fc_gaa_google_autocomplete_js_settings', array( $this, 'configure_google_address_autocomplete' ), 100 );
		add_action( 'wp_ajax_pepselect_validate_checkout_address', array( $this, 'ajax_validate_checkout_address' ) );
		add_action( 'wp_ajax_nopriv_pepselect_validate_checkout_address', array( $this, 'ajax_validate_checkout_address' ) );
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
				'ajaxUrl'            => admin_url( 'admin-ajax.php' ),
				'nonce'              => wp_create_nonce( 'pepselect_validate_checkout_address' ),
				'allowedStates'      => self::ALLOWED_STATES,
				'regionNames'        => self::REGION_NAMES,
				'unsupportedMessage' => self::UNSUPPORTED_MESSAGE,
				'incompleteMessage'  => self::INCOMPLETE_ADDRESS_MESSAGE,
				'checkingMessage'    => 'Verifying delivery address…',
				'verifiedMessage'    => 'Delivery address verified.',
			)
		);
	}

	/** Validate a complete checkout address without exposing the Google API key. */
	public function ajax_validate_checkout_address(): void {
		check_ajax_referer( 'pepselect_validate_checkout_address', 'nonce' );

		if ( ! self::ajax_request_is_within_limit() ) {
			wp_send_json_error(
				array( 'message' => 'Address verification is temporarily busy. Wait a moment and try again.' ),
				429
			);
		}

		$value = static function ( string $key ): string {
			$value = isset( $_POST[ $key ] ) && is_scalar( $_POST[ $key ] ) ? wp_unslash( (string) $_POST[ $key ] ) : '';
			return function_exists( 'wc_clean' ) ? wc_clean( $value ) : sanitize_text_field( $value );
		};

		$address = array(
			'address_1' => $value( 'address_1' ),
			'address_2' => $value( 'address_2' ),
			'city'      => $value( 'city' ),
			'country'   => strtoupper( $value( 'country' ) ),
			'state'     => strtoupper( $value( 'state' ) ),
			'postcode'  => $value( 'postcode' ),
		);

		if ( ! self::address_is_complete( $address ) ) {
			wp_send_json_success( array( 'valid' => false, 'message' => 'Complete the street, city, state, and ZIP code to verify this address.' ) );
		}

		$message = self::address_error_message( $address['country'], $address['state'], $address['postcode'] );
		if ( '' === $message && ! self::address_line_is_complete( $address['address_1'] ) ) {
			$message = self::INCOMPLETE_ADDRESS_MESSAGE;
		}

		$result = '' === $message
			? $this->verify_postal_address( $address )
			: array( 'valid' => false, 'message' => $message, 'suggested' => array() );

		wp_send_json_success( $result );
	}

	private static function ajax_request_is_within_limit(): bool {
		$identity = '';
		if ( function_exists( 'WC' ) && WC()->session ) {
			$identity = (string) WC()->session->get_customer_id();
		}
		if ( '' === $identity ) {
			$identity = (string) ( $_SERVER['REMOTE_ADDR'] ?? '' ) . '|' . (string) ( $_SERVER['HTTP_USER_AGENT'] ?? '' );
		}

		$key   = 'pep_av_rate_' . substr( hash( 'sha256', $identity ), 0, 32 );
		$count = (int) get_transient( $key );
		if ( $count >= 30 ) {
			return false;
		}

		set_transient( $key, $count + 1, MINUTE_IN_SECONDS );
		return true;
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
		foreach ( self::checkout_addresses( $data ) as $address ) {
			if ( ! self::address_is_complete( $address ) ) {
				continue;
			}

			$message = self::address_error_message( $address['country'], $address['state'], $address['postcode'] );
			if ( '' === $message && ! self::address_line_is_complete( $address['address_1'] ) ) {
				$message = self::INCOMPLETE_ADDRESS_MESSAGE;
			}

			if ( '' === $message ) {
				$verification = $this->verify_postal_address( $address );
				$message      = $verification['message'];
			}

			if ( '' !== $message ) {
				$errors->add( 'pepselect_' . $address['prefix'] . '_address', $message, array( 'id' => $address['prefix'] . '_address_1' ) );
			}
		}
	}

	/**
	 * Fluid Checkout can update the hidden billing fields one at a time. Make the
	 * posted order data atomic when the customer selected "Same as shipping".
	 */
	public function synchronize_billing_to_shipping( array $data ): array {
		return self::synchronize_same_address_data( $data );
	}

	public static function synchronize_same_address_data( array $data ): array {
		if ( empty( $data['billing_same_as_shipping'] ) ) {
			return $data;
		}

		foreach ( array( 'first_name', 'last_name', 'company', 'address_1', 'address_2', 'city', 'state', 'postcode', 'country' ) as $field ) {
			$shipping_key = 'shipping_' . $field;
			if ( array_key_exists( $shipping_key, $data ) ) {
				$data[ 'billing_' . $field ] = $data[ $shipping_key ];
			}
		}

		if ( array_key_exists( 'shipping_phone', $data ) ) {
			$data['billing_phone'] = $data['shipping_phone'];
		}

		return $data;
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

	public static function address_line_is_complete( string $address_line ): bool {
		$address_line = trim( preg_replace( '/\s+/', ' ', $address_line ) );
		return 1 === preg_match( '/\d/u', $address_line )
			&& 1 === preg_match( '/\p{L}{2,}/u', $address_line )
			&& count( preg_split( '/\s+/', $address_line ) ) >= 2;
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

	/** @return array{prefix:string,address_1:string,address_2:string,city:string,country:string,state:string,postcode:string} */
	private static function checkout_destination( array $data ): array {
		$prefix = ! empty( $data['ship_to_different_address'] ) ? 'shipping' : 'billing';
		return self::address_from_checkout_data( $data, $prefix );
	}

	/** @return array<int,array{prefix:string,address_1:string,address_2:string,city:string,country:string,state:string,postcode:string}> */
	private static function checkout_addresses( array $data ): array {
		$shipping = self::address_from_checkout_data( $data, 'shipping' );
		$billing  = self::address_from_checkout_data( $data, 'billing' );

		if ( '' === $shipping['address_1'] ) {
			return array( $billing );
		}

		if ( ! empty( $data['billing_same_as_shipping'] ) || self::address_fingerprint( $shipping ) === self::address_fingerprint( $billing ) ) {
			return array( $shipping );
		}

		return array( $shipping, $billing );
	}

	/** @return array{prefix:string,address_1:string,address_2:string,city:string,country:string,state:string,postcode:string} */
	private static function address_from_checkout_data( array $data, string $prefix ): array {
		$value = static function ( string $key ) use ( $data ): string {
			return is_scalar( $data[ $key ] ?? null ) ? trim( (string) $data[ $key ] ) : '';
		};

		return array(
			'prefix'   => $prefix,
			'address_1' => $value( $prefix . '_address_1' ),
			'address_2' => $value( $prefix . '_address_2' ),
			'city'      => $value( $prefix . '_city' ),
			'country'   => $value( $prefix . '_country' ),
			'state'     => $value( $prefix . '_state' ),
			'postcode'  => $value( $prefix . '_postcode' ),
		);
	}

	/** @param array{address_1:string,address_2:string,city:string,country:string,state:string,postcode:string} $address */
	private function verify_postal_address( array $address ): array {
		$api_key = defined( 'PEPSELECT_GOOGLE_ADDRESS_VALIDATION_API_KEY' )
			? (string) PEPSELECT_GOOGLE_ADDRESS_VALIDATION_API_KEY
			: '';
		$api_key = (string) apply_filters( 'pepselect_address_validation_api_key', $api_key );

		if ( '' === trim( $api_key ) ) {
			return array( 'valid' => false, 'message' => self::UNVERIFIED_ADDRESS_MESSAGE, 'suggested' => array() );
		}

		$fingerprint = self::address_fingerprint( $address );
		$cache_key   = 'pep_av_v2_' . substr( hash( 'sha256', $fingerprint ), 0, 40 );
		$cached      = get_transient( $cache_key );
		if ( is_array( $cached ) && isset( $cached['valid'], $cached['message'] ) ) {
			return $cached;
		}

		$lines = array_values( array_filter( array( $address['address_1'], $address['address_2'] ) ) );
		$body  = array(
			'address'        => array(
				'regionCode'        => strtoupper( $address['country'] ),
				'administrativeArea' => strtoupper( $address['state'] ),
				'locality'           => $address['city'],
				'postalCode'         => $address['postcode'],
				'addressLines'       => $lines,
			),
			'enableUspsCass' => true,
		);

		$response = wp_safe_remote_post(
			'https://addressvalidation.googleapis.com/v1:validateAddress',
			array(
				'timeout' => 5,
				'headers' => array(
					'Content-Type'   => 'application/json',
					'X-Goog-Api-Key' => $api_key,
				),
				'body'    => wp_json_encode( $body ),
			)
		);

		if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
			return array( 'valid' => false, 'message' => self::UNVERIFIED_ADDRESS_MESSAGE );
		}

		$payload = json_decode( wp_remote_retrieve_body( $response ), true );
		$result  = self::google_validation_result( is_array( $payload ) ? $payload : array(), $address );
		set_transient( $cache_key, $result, DAY_IN_SECONDS );

		return $result;
	}

	/**
	 * Reduce the Google/USPS response to the only decision checkout needs.
	 * USPS DPV=Y confirms the primary and any required secondary information.
	 */
	public static function google_validation_result( array $payload, array $submitted_address ): array {
		$result      = isset( $payload['result'] ) && is_array( $payload['result'] ) ? $payload['result'] : array();
		$verdict     = isset( $result['verdict'] ) && is_array( $result['verdict'] ) ? $result['verdict'] : array();
		$usps        = isset( $result['uspsData'] ) && is_array( $result['uspsData'] ) ? $result['uspsData'] : array();
		$postal      = isset( $result['address']['postalAddress'] ) && is_array( $result['address']['postalAddress'] ) ? $result['address']['postalAddress'] : array();
		$granularity = strtoupper( (string) ( $verdict['validationGranularity'] ?? '' ) );
		$next_action = strtoupper( (string) ( $verdict['possibleNextAction'] ?? '' ) );
		$dpv         = strtoupper( (string) ( $usps['dpvConfirmation'] ?? '' ) );

		$complete = ! empty( $verdict['addressComplete'] );
		$precise  = in_array( $granularity, array( 'PREMISE', 'SUB_PREMISE' ), true );
		$accepted = '' === $next_action || 'ACCEPT' === $next_action;
		$unchanged = empty( $verdict['hasUnconfirmedComponents'] )
			&& empty( $verdict['hasInferredComponents'] )
			&& empty( $verdict['hasReplacedComponents'] )
			&& empty( $verdict['hasSpellCorrectedComponents'] );
		$matches  = self::normalized_component_matches( $postal, $submitted_address );

		if ( $complete && $precise && $accepted && $unchanged && 'Y' === $dpv && $matches ) {
			return array( 'valid' => true, 'message' => '', 'suggested' => array() );
		}

		$suggested = $complete && $precise && 'Y' === $dpv ? self::suggested_address_from_postal( $postal ) : array();
		return array( 'valid' => false, 'message' => self::ADDRESS_REVIEW_MESSAGE, 'suggested' => $suggested );
	}

	/** @return array{address_1:string,address_2:string,city:string,state:string,postcode:string,country:string}|array{} */
	private static function suggested_address_from_postal( array $postal ): array {
		$lines = isset( $postal['addressLines'] ) && is_array( $postal['addressLines'] ) ? array_values( $postal['addressLines'] ) : array();
		$suggestion = array(
			'address_1' => trim( (string) ( $lines[0] ?? '' ) ),
			'address_2' => trim( (string) ( $lines[1] ?? '' ) ),
			'city'      => trim( (string) ( $postal['locality'] ?? '' ) ),
			'state'     => strtoupper( trim( (string) ( $postal['administrativeArea'] ?? '' ) ) ),
			'postcode'  => trim( (string) ( $postal['postalCode'] ?? '' ) ),
			'country'   => strtoupper( trim( (string) ( $postal['regionCode'] ?? '' ) ) ),
		);

		foreach ( array( 'address_1', 'city', 'state', 'postcode', 'country' ) as $required ) {
			if ( '' === $suggestion[ $required ] ) {
				return array();
			}
		}

		return $suggestion;
	}

	private static function normalized_component_matches( array $postal, array $submitted ): bool {
		if ( empty( $postal ) ) {
			return false;
		}

		$submitted_zip = substr( preg_replace( '/\D+/', '', (string) ( $submitted['postcode'] ?? '' ) ), 0, 5 );
		$postal_zip    = substr( preg_replace( '/\D+/', '', (string) ( $postal['postalCode'] ?? '' ) ), 0, 5 );
		$submitted_city = self::normalize_component_text( (string) ( $submitted['city'] ?? '' ) );
		$postal_city    = self::normalize_component_text( (string) ( $postal['locality'] ?? '' ) );

		return strtoupper( trim( (string) ( $postal['regionCode'] ?? '' ) ) ) === strtoupper( trim( (string) ( $submitted['country'] ?? '' ) ) )
			&& strtoupper( trim( (string) ( $postal['administrativeArea'] ?? '' ) ) ) === strtoupper( trim( (string) ( $submitted['state'] ?? '' ) ) )
			&& $postal_zip === $submitted_zip
			&& '' !== $postal_city
			&& $postal_city === $submitted_city;
	}

	private static function normalize_component_text( string $value ): string {
		$value = strtoupper( trim( $value ) );
		return preg_replace( '/[^A-Z0-9]+/', '', $value );
	}

	private static function address_fingerprint( array $address ): string {
		$parts = array( 'address_1', 'address_2', 'city', 'state', 'postcode', 'country' );
		return implode( '|', array_map( static function ( string $key ) use ( $address ): string {
			return strtoupper( trim( preg_replace( '/\s+/', ' ', (string) ( $address[ $key ] ?? '' ) ) ) );
		}, $parts ) );
	}

	/** @param array{country:string,state:string,postcode:string} $address */
	private static function destination_requires_rate_refresh( array $address ): bool {
		return '' !== self::address_error_message( $address['country'], $address['state'], $address['postcode'] )
			|| self::destination_is_usps_only( $address['country'], $address['state'], $address['postcode'] )
			|| in_array( strtoupper( $address['state'] ), array( 'AK', 'HI', 'PR' ), true );
	}

	/** @param array{country:string,state:string,postcode:string} $address */
	private static function address_is_complete( array $address ): bool {
		return '' !== $address['address_1']
			&& '' !== $address['city']
			&& '' !== $address['country']
			&& '' !== $address['postcode']
			&& '' !== $address['state'];
	}

	private function clean_checkout_value( $value ): string {
		$value = is_scalar( $value ) ? (string) $value : '';
		return function_exists( 'wc_clean' ) ? wc_clean( $value ) : trim( $value );
	}
}

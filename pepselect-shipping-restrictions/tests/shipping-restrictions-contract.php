<?php

define( 'ABSPATH', __DIR__ . '/' );

require_once dirname( __DIR__ ) . '/includes/class-pepselect-shipping-restrictions.php';

function apply_filters( $hook, $value ) {
	return $value;
}

function pepselect_shipping_assert( $condition, $message ) {
	if ( ! $condition ) {
		fwrite( STDERR, "FAIL: {$message}\n" );
		exit( 1 );
	}
}

final class PepSelect_Test_Rate {
	private $label;

	public function __construct( $label ) {
		$this->label = $label;
	}

	public function get_label() {
		return $this->label;
	}
}

final class PepSelect_Test_Errors {
	public $errors = array();

	public function add( $code, $message, $data = array() ) {
		$this->errors[ $code ] = array( 'message' => $message, 'data' => $data );
	}
}

pepselect_shipping_assert( PepSelect_Shipping_Restrictions::address_is_allowed( 'US', 'CA', '90210' ), 'California should pass.' );
pepselect_shipping_assert( ! PepSelect_Shipping_Restrictions::address_line_is_complete( '38206' ), 'A house number alone must fail.' );
pepselect_shipping_assert( PepSelect_Shipping_Restrictions::address_line_is_complete( '15655 Airline Hwy' ), 'A complete street line should pass.' );
pepselect_shipping_assert( PepSelect_Shipping_Restrictions::address_is_allowed( 'US', 'AK', '99501' ), 'Alaska should pass.' );
pepselect_shipping_assert( PepSelect_Shipping_Restrictions::address_is_allowed( 'US', 'HI', '96801' ), 'Hawaii should pass.' );
pepselect_shipping_assert( PepSelect_Shipping_Restrictions::address_is_allowed( 'US', 'PR', '00901' ), 'Puerto Rico state and ZIP should pass.' );
pepselect_shipping_assert( ! PepSelect_Shipping_Restrictions::address_is_allowed( 'US', 'NY', '00926' ), 'Puerto Rico ZIP with New York selected should fail.' );
pepselect_shipping_assert( ! PepSelect_Shipping_Restrictions::address_is_allowed( 'PR', '', '00901' ), 'Puerto Rico country code should not bypass the U.S. destination rules.' );
pepselect_shipping_assert( ! PepSelect_Shipping_Restrictions::address_is_allowed( 'US', 'PR', '10001' ), 'New York ZIP with Puerto Rico selected should fail.' );
pepselect_shipping_assert( ! PepSelect_Shipping_Restrictions::address_is_allowed( 'US', 'VI', '00802' ), 'U.S. Virgin Islands should fail.' );
pepselect_shipping_assert( ! PepSelect_Shipping_Restrictions::address_is_allowed( 'US', 'HI', '96799' ), 'American Samoa should fail.' );
pepselect_shipping_assert( ! PepSelect_Shipping_Restrictions::address_is_allowed( 'US', 'CA', '96201' ), 'Overseas military ZIP should fail.' );
pepselect_shipping_assert( ! PepSelect_Shipping_Restrictions::address_is_allowed( 'US', 'CA', '96910' ), 'Pacific territory ZIP should fail.' );
pepselect_shipping_assert( PepSelect_Shipping_Restrictions::destination_is_usps_only( 'US', 'AK', '99501' ), 'Alaska should be USPS-only.' );
pepselect_shipping_assert( PepSelect_Shipping_Restrictions::destination_is_usps_only( 'US', 'PR', '00901' ), 'Puerto Rico should be USPS-only.' );
pepselect_shipping_assert( ! PepSelect_Shipping_Restrictions::destination_is_usps_only( 'US', 'HI', '96801' ), 'Hawaii should use returned live carrier options.' );

$restriction = PepSelect_Shipping_Restrictions::instance();
$autocomplete_settings = $restriction->configure_google_address_autocomplete(
	array(
		'allowedCountries' => array(
			'shipping_address_1' => array( 'US' ),
		),
	)
);
pepselect_shipping_assert(
	array( 'US', 'PR' ) === $autocomplete_settings['allowedCountries']['shipping_address_1'],
	'Shipping autocomplete should include the United States and Puerto Rico.'
);
pepselect_shipping_assert(
	array( 'US', 'PR' ) === $autocomplete_settings['allowedCountries']['billing_address_1'],
	'Billing autocomplete should include the United States and Puerto Rico.'
);

$rates       = array(
	'free_shipping:1' => new PepSelect_Test_Rate( 'Free shipping' ),
	'easyship:usps'   => new PepSelect_Test_Rate( 'USPS - Priority Mail' ),
	'easyship:fedex'  => new PepSelect_Test_Rate( 'FedEx 2Day' ),
);

pepselect_shipping_assert(
	array( 'easyship:usps' ) === array_keys(
		$restriction->filter_package_rates(
			$rates,
			array( 'destination' => array( 'country' => 'US', 'state' => 'PR', 'postcode' => '00901' ) )
		)
	),
	'Puerto Rico should retain only USPS.'
);
pepselect_shipping_assert(
	array_keys( $rates ) === array_keys(
		$restriction->filter_package_rates(
			$rates,
			array( 'destination' => array( 'country' => 'US', 'state' => 'HI', 'postcode' => '96801' ) )
		)
	),
	'Hawaii should retain all live rates returned by the shipping providers.'
);
pepselect_shipping_assert(
	array() === $restriction->filter_package_rates(
		$rates,
		array( 'destination' => array( 'country' => 'US', 'state' => 'NY', 'postcode' => '00926' ) )
	),
	'Mismatched Puerto Rico ZIP and New York state should remove all rates.'
);

$synced = PepSelect_Shipping_Restrictions::synchronize_same_address_data(
	array(
		'billing_same_as_shipping' => '1',
		'shipping_address_1'       => '15655 Airline Hwy',
		'shipping_city'            => 'Prairieville',
		'shipping_state'           => 'LA',
		'shipping_postcode'        => '70769',
		'shipping_country'         => 'US',
		'billing_address_1'        => 'Old value',
		'billing_city'             => 'Oceanside',
		'billing_state'            => 'NY',
		'billing_postcode'         => '11572',
		'billing_country'          => 'US',
	)
);
pepselect_shipping_assert( 'LA' === $synced['billing_state'], 'Same-as-shipping must atomically copy state.' );
pepselect_shipping_assert( '70769' === $synced['billing_postcode'], 'Same-as-shipping must atomically copy ZIP.' );
pepselect_shipping_assert( 'Prairieville' === $synced['billing_city'], 'Same-as-shipping must atomically copy city.' );

$valid_google_response = array(
	'result' => array(
		'verdict' => array(
			'addressComplete'       => true,
			'validationGranularity' => 'PREMISE',
			'possibleNextAction'    => 'ACCEPT',
		),
		'address' => array(
			'postalAddress' => array(
				'regionCode'        => 'US',
				'administrativeArea' => 'LA',
				'locality'           => 'Prairieville',
				'postalCode'         => '70769-9997',
				'addressLines'       => array( '15655 Airline Hwy' ),
			),
		),
		'uspsData' => array( 'dpvConfirmation' => 'Y' ),
	),
);
$prairieville_address = array(
	'address_1' => '15655 Airline Hwy',
	'address_2' => '',
	'city'      => 'Prairieville',
	'state'     => 'LA',
	'postcode'  => '70769',
	'country'   => 'US',
);
$verified = PepSelect_Shipping_Restrictions::google_validation_result( $valid_google_response, $prairieville_address );
pepselect_shipping_assert( true === $verified['valid'], 'Deliverable Prairieville address should pass.' );

$mismatched = $prairieville_address;
$mismatched['state'] = 'NY';
$verified = PepSelect_Shipping_Restrictions::google_validation_result( $valid_google_response, $mismatched );
pepselect_shipping_assert( false === $verified['valid'], 'Louisiana ZIP with New York state must fail.' );

$mismatched_city = $prairieville_address;
$mismatched_city['city'] = 'Oceanside';
$verified = PepSelect_Shipping_Restrictions::google_validation_result( $valid_google_response, $mismatched_city );
pepselect_shipping_assert( false === $verified['valid'], 'City and ZIP combination must match the verified address.' );

$oceanside_google_response = $valid_google_response;
$oceanside_google_response['result']['verdict']['hasReplacedComponents'] = true;
$oceanside_google_response['result']['address']['postalAddress'] = array(
	'regionCode'        => 'US',
	'administrativeArea' => 'NY',
	'locality'           => 'Oceanside',
	'postalCode'         => '11572-1234',
	'addressLines'       => array( '66 Homecrest Ct' ),
);
$oceanside_submitted = array(
	'address_1' => '66 Homecrest Court',
	'address_2' => '',
	'city'      => 'Oceanside',
	'state'     => 'NJ',
	'postcode'  => '11572',
	'country'   => 'US',
);
$verified = PepSelect_Shipping_Restrictions::google_validation_result( $oceanside_google_response, $oceanside_submitted );
pepselect_shipping_assert( false === $verified['valid'], 'Oceanside 11572 with New Jersey selected must fail.' );
pepselect_shipping_assert( 'NY' === $verified['suggested']['state'], 'The verified suggestion must replace New Jersey with New York.' );
pepselect_shipping_assert( '66 Homecrest Ct' === $verified['suggested']['address_1'], 'The verified suggestion must include the confirmed street.' );

$incomplete_response = $valid_google_response;
$incomplete_response['result']['uspsData']['dpvConfirmation'] = 'N';
$verified = PepSelect_Shipping_Restrictions::google_validation_result( $incomplete_response, $prairieville_address );
pepselect_shipping_assert( false === $verified['valid'], 'Non-deliverable street address must fail.' );

$corrected_response = $valid_google_response;
$corrected_response['result']['verdict']['hasReplacedComponents'] = true;
$verified = PepSelect_Shipping_Restrictions::google_validation_result( $corrected_response, $prairieville_address );
pepselect_shipping_assert( false === $verified['valid'], 'An address corrected by the service must be reviewed by the customer.' );

$errors = new PepSelect_Test_Errors();
$restriction->validate_checkout(
	array(
		'billing_same_as_shipping' => '1',
		'shipping_address_1'       => '38206',
		'shipping_city'            => 'Prairieville',
		'shipping_state'           => 'NY',
		'shipping_postcode'        => '70769',
		'shipping_country'         => 'US',
	),
	$errors
);
pepselect_shipping_assert( isset( $errors->errors['pepselect_shipping_address'] ), 'Checkout must block a house-number-only street line.' );

echo "Pep Select shipping rules contract passed.\n";

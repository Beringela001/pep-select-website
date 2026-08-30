<?php

define( 'ABSPATH', __DIR__ . '/' );

require_once dirname( __DIR__ ) . '/includes/class-pepselect-shipping-restrictions.php';

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

pepselect_shipping_assert( PepSelect_Shipping_Restrictions::address_is_allowed( 'US', 'CA', '90210' ), 'California should pass.' );
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

echo "Pep Select shipping rules contract passed.\n";

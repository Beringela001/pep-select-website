<?php

define( 'ABSPATH', __DIR__ . '/' );

require_once dirname( __DIR__ ) . '/includes/class-pepselect-shipping-restrictions.php';

function pepselect_shipping_assert( $condition, $message ) {
	if ( ! $condition ) {
		fwrite( STDERR, "FAIL: {$message}\n" );
		exit( 1 );
	}
}

pepselect_shipping_assert( PepSelect_Shipping_Restrictions::address_is_allowed( 'US', 'CA', '90210' ), 'California address should pass.' );
pepselect_shipping_assert( PepSelect_Shipping_Restrictions::address_is_allowed( 'US', 'DC', '20001' ), 'Washington, D.C. address should pass.' );
pepselect_shipping_assert( ! PepSelect_Shipping_Restrictions::address_is_allowed( 'US', 'AK', '99501' ), 'Alaska should fail.' );
pepselect_shipping_assert( ! PepSelect_Shipping_Restrictions::address_is_allowed( 'US', 'HI', '96801' ), 'Hawaii should fail.' );
pepselect_shipping_assert( ! PepSelect_Shipping_Restrictions::address_is_allowed( 'PR', '', '00901' ), 'Puerto Rico country should fail.' );
pepselect_shipping_assert( ! PepSelect_Shipping_Restrictions::address_is_allowed( 'US', 'FL', '00901' ), 'Puerto Rico ZIP with a false Florida state should fail.' );
pepselect_shipping_assert( ! PepSelect_Shipping_Restrictions::address_is_allowed( 'US', 'FL', '00802-1234' ), 'U.S. Virgin Islands ZIP should fail.' );
pepselect_shipping_assert( ! PepSelect_Shipping_Restrictions::address_is_allowed( 'US', 'CA', '96201' ), 'Overseas military ZIP should fail.' );
pepselect_shipping_assert( ! PepSelect_Shipping_Restrictions::address_is_allowed( 'US', 'CA', '96910' ), 'Pacific territory ZIP should fail.' );

$restriction = PepSelect_Shipping_Restrictions::instance();
$rates       = array( 'free_shipping:1' => 'free', 'easyship:2' => 'easyship' );

pepselect_shipping_assert(
	$rates === $restriction->filter_package_rates(
		$rates,
		array( 'destination' => array( 'country' => 'US', 'state' => 'DC', 'postcode' => '20001' ) )
	),
	'Allowed package rates should remain available.'
);
pepselect_shipping_assert(
	array() === $restriction->filter_package_rates(
		$rates,
		array( 'destination' => array( 'country' => 'US', 'state' => 'FL', 'postcode' => '00901' ) )
	),
	'Puerto Rico ZIP should remove every package rate even with a false state.'
);

echo "Pep Select shipping restrictions contract passed.\n";

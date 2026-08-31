<?php

$root      = dirname( __DIR__ );
$bootstrap = file_get_contents( $root . '/pepselect-order-experience.php' );
$plugin    = file_get_contents( $root . '/includes/class-pepselect-oe-plugin.php' );
$dashboard = file_get_contents( dirname( $root ) . '/pepselect-child/woocommerce/myaccount/dashboard.php' );
$css       = file_get_contents( dirname( $root ) . '/pepselect-child/assets/css/account.css' );

function pepselect_oe_account_expect( bool $condition, string $message ): void {
	if ( ! $condition ) {
		fwrite( STDERR, "FAIL: {$message}\n" );
		exit( 1 );
	}
}

pepselect_oe_account_expect( str_contains( $bootstrap, 'pepselect_oe_account_order_summary' ), 'Theme-facing account helper must exist.' );
pepselect_oe_account_expect( str_contains( $plugin, "'Waiting for payment'" ), 'Pending payment status must use customer language.' );
pepselect_oe_account_expect( str_contains( $plugin, "'Being prepared'" ), 'Processing status must use customer language.' );
pepselect_oe_account_expect( str_contains( $plugin, "'In transit'" ), 'Tracked completed orders must show in transit.' );
pepselect_oe_account_expect( str_contains( $plugin, "find_by_order" ) && str_contains( $plugin, "get_order_key" ), 'Private URLs must require an existing secure record and order key.' );
pepselect_oe_account_expect( str_contains( $dashboard, 'array_slice( $pepselect_orders, 0, 5 )' ), 'Five newest orders must remain visible.' );
pepselect_oe_account_expect( str_contains( $dashboard, '<details class="pepselect-orders-archive"' ), 'Older orders must use native progressive disclosure.' );
pepselect_oe_account_expect( str_contains( $css, '.pepselect-order-row__target' ), 'Each row must provide a full-card link target.' );
pepselect_oe_account_expect( str_contains( $css, '@media (max-width: 640px)' ), 'Dedicated mobile order-row layout must exist.' );

echo "Order account contract passed.\n";

<?php

declare( strict_types = 1 );

function pepselect_oe_m4_expect( bool $condition, string $message ): void {
	if ( ! $condition ) { throw new RuntimeException( $message ); }
}

$root       = dirname( __DIR__ );
$bootstrap  = file_get_contents( $root . '/pepselect-order-experience.php' );
$plugin     = file_get_contents( $root . '/includes/class-pepselect-oe-plugin.php' );
$store      = file_get_contents( $root . '/includes/class-pepselect-oe-access-store.php' );
$controller = file_get_contents( $root . '/includes/class-pepselect-oe-rest-controller.php' );
$renderer   = file_get_contents( $root . '/includes/class-pepselect-oe-renderer.php' );
$overrides  = file_get_contents( $root . '/assets/order-experience-overrides.css' );

pepselect_oe_m4_expect( str_contains( $bootstrap, "Version: 0.4.0" ), 'Order Experience plugin version must be 0.4.0.' );
pepselect_oe_m4_expect( str_contains( $plugin, "add_option( 'pepselect_oe_enabled', '0'" ), 'Customer order pages must remain default-off.' );
pepselect_oe_m4_expect( str_contains( $plugin, 'Referrer-Policy: no-referrer' ), 'Opaque access credentials must not leave through referrer headers.' );
pepselect_oe_m4_expect( str_contains( $plugin, "preg_match( '/^[A-Za-z0-9_-]{43}$/'" ), 'Public access must reject malformed tokens before lookup.' );
pepselect_oe_m4_expect( str_contains( $plugin, 'INVALID_ACCESS_LIMIT' ) && str_contains( $plugin, 'set_transient' ), 'Invalid token attempts must be throttled without storing raw client identifiers.' );
pepselect_oe_m4_expect( ! str_contains( $plugin, 'delete_option( \'pepselect_oe_page_id\'' ), 'Deactivation must retain the permanent QR fallback page.' );
pepselect_oe_m4_expect( str_contains( $store, "hash( 'sha256', \$token )" ), 'WordPress must retain only a token hash.' );
pepselect_oe_m4_expect( str_contains( $controller, "current_user_can( 'manage_woocommerce' )" ), 'Ops snapshot writes must remain authenticated and capability-gated.' );
pepselect_oe_m4_expect( str_contains( $renderer, 'pepselect-oe__ordered-content' ), 'Ordered-card copy and batch details must share the content column.' );
pepselect_oe_m4_expect( str_contains( $overrides, 'transform:scale(1.2)' ), 'Desktop vial photos must fill the specimen rail without hiding their centered labels.' );
pepselect_oe_m4_expect( str_contains( $overrides, 'grid-template-columns:repeat(4,minmax(0,1fr))' ), 'Related compounds must retain the standard four-card desktop grid.' );
pepselect_oe_m4_expect( str_contains( $overrides, 'object-fit:contain' ) && str_contains( $overrides, 'height:210px' ), 'Related-product images must remain complete in a consistent desktop frame.' );

echo "Milestone 4 contract: OK\n";

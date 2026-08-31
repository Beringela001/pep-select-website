<?php

declare( strict_types = 1 );

define( 'ABSPATH', __DIR__ );

function wp_strip_all_tags( string $value ): string { return strip_tags( $value ); }

require_once dirname( __DIR__ ) . '/includes/class-pepselect-oe-content-registry.php';

function pepselect_oe_expect( bool $condition, string $message ): void {
	if ( ! $condition ) { throw new RuntimeException( $message ); }
}

$root     = dirname( __DIR__ );
$plugin   = file_get_contents( $root . '/includes/class-pepselect-oe-plugin.php' );
$renderer = file_get_contents( $root . '/includes/class-pepselect-oe-renderer.php' );
$resolver = file_get_contents( $root . '/includes/class-pepselect-oe-coa-resolver.php' );
$relationships = file_get_contents( $root . '/includes/class-pepselect-oe-relationship-engine.php' );
$css      = file_get_contents( $root . '/assets/order-experience.css' );

pepselect_oe_expect( 14 === count( PepSelect_OE_Content_Registry::compounds() ), 'The controlled registry must cover 14 compounds.' );
pepselect_oe_expect( 'glp-3 r' === PepSelect_OE_Content_Registry::normalize_name( 'GLP-3 R 30mg' ), 'Strength must not change the compound key.' );
pepselect_oe_expect( 'ghk-cu' === PepSelect_OE_Content_Registry::normalize_name( 'GHK CU 50 mg' ), 'GHK-CU aliases must resolve.' );
pepselect_oe_expect( ! str_contains( $plugin, "\$_GET['order']" ), 'Public order access must not accept numeric order IDs.' );
pepselect_oe_expect( str_contains( $plugin, 'wp_verify_nonce' ), 'Reorder must verify a nonce.' );
pepselect_oe_expect( str_contains( $plugin, 'get_qty_refunded_for_item' ), 'Reorder must account for refunded quantities.' );
pepselect_oe_expect( str_contains( $plugin, 'out of stock may fill open positions with a Restocking label' ), 'Administrator guidance must describe the restocking-card behavior accurately.' );
pepselect_oe_expect( str_contains( $plugin, 'noindex, nofollow, noarchive, nosnippet, noimageindex' ), 'Private pages need complete robot exclusion.' );
pepselect_oe_expect( str_contains( $resolver, "'batch_vial_photo'" ), 'The exact COA batch-vial image must be preferred.' );
pepselect_oe_expect( str_contains( $relationships, 'pepselect_oe_blocked_relationships' ), 'Owners need an explicit related-product block control.' );
pepselect_oe_expect( str_contains( $relationships, "'outofstock' === \$product->get_stock_status()" ), 'Relevant published products may fill open recommendation slots while restocking.' );
pepselect_oe_expect( str_contains( $relationships, "wp_get_attachment_image_url( \$product->get_image_id(), 'large' )" ), 'Related cards must use an uncropped product image source.' );
pepselect_oe_expect( str_contains( $renderer, 'Match the vial. Match the batch.' ), 'Approved order-page mission copy is required.' );
pepselect_oe_expect( str_contains( $renderer, '>Restocking</span>' ), 'Unavailable related products need an explicit restocking state.' );
pepselect_oe_expect( str_contains( $renderer, 'Review full report' ), 'Each available batch must link to its report.' );
pepselect_oe_expect( str_contains( $renderer, "! empty( \$item['is_bacteriostatic_water'] )" ), 'Bacteriostatic water must use its dedicated product-details card.' );
pepselect_oe_expect( str_contains( $renderer, '>HOSPIRA</dd>' ) && str_contains( $renderer, '>USP</dd>' ), 'The accessory card must match the Hospira USP vial label.' );
pepselect_oe_expect( str_contains( $renderer, 'View product information' ), 'The accessory card must link to product information instead of a COA.' );
pepselect_oe_expect( str_contains( $css, '@media(max-width:767px)' ) && str_contains( $css, 'repeat(2,minmax(0,1fr))' ), 'Mobile must retain compact two-column grids.' );

echo "Milestone 2 contract: OK\n";

<?php

declare( strict_types = 1 );

define( 'ABSPATH', __DIR__ );

function wp_strip_all_tags( string $value ): string { return strip_tags( $value ); }
function get_option( string $key, string $default = '' ): string { return $default; }
function apply_filters( string $hook, array $value ): array { return $value; }
function wp_get_attachment_image_url( int $id, string $size ): string { return "image-{$id}-{$size}.jpg"; }
function wc_placeholder_img_src( string $size ): string { return "placeholder-{$size}.jpg"; }

final class WC_Product {
	public function __construct(
		private int $id,
		private string $name,
		private string $stock_status,
		private int $menu_order,
		private string $visibility = 'visible'
	) {}
	public function get_id(): int { return $this->id; }
	public function get_name(): string { return $this->name; }
	public function get_stock_status(): string { return $this->stock_status; }
	public function get_menu_order(): int { return $this->menu_order; }
	public function get_catalog_visibility(): string { return $this->visibility; }
	public function is_visible(): bool { return 'visible' === $this->visibility && 'outofstock' !== $this->stock_status; }
	public function is_purchasable(): bool { return 'outofstock' !== $this->stock_status; }
	public function is_in_stock(): bool { return 'outofstock' !== $this->stock_status; }
	public function get_permalink(): string { return '/product/' . strtolower( str_replace( array( ' ', '+' ), array( '-', 'plus' ), $this->name ) ); }
	public function get_image_id(): int { return $this->id; }
}

/** @return WC_Product[] */
function wc_get_products( array $args ): array {
	return array(
		new WC_Product( 1, 'GLP-3 R', 'instock', 1 ),
		new WC_Product( 2, 'NAD+', 'instock', 5 ),
		new WC_Product( 3, 'MOTS-C', 'outofstock', 1 ),
		new WC_Product( 4, 'GLP-2 T', 'outofstock', 2 ),
		new WC_Product( 5, 'GLP-1 S', 'outofstock', 3 ),
		new WC_Product( 6, 'Cagrilintide', 'outofstock', 4 ),
		new WC_Product( 7, 'Tesamorelin', 'outofstock', 0, 'hidden' ),
		new WC_Product( 8, 'PT-141', 'instock', 0 )
	);
}

require_once dirname( __DIR__ ) . '/includes/class-pepselect-oe-content-registry.php';
require_once dirname( __DIR__ ) . '/includes/class-pepselect-oe-relationship-engine.php';

$results = ( new PepSelect_OE_Relationship_Engine() )->recommend( array( array( 'name' => 'GLP-3 R', 'display_name' => 'GLP-3 R' ) ) );

if ( 4 !== count( $results ) ) { throw new RuntimeException( 'Related results must backfill all four slots when relevant restocking compounds exist.' ); }
if ( 'NAD+' !== $results[0]['name'] || $results[0]['restocking'] ) { throw new RuntimeException( 'Available related compounds must remain ahead of restocking compounds.' ); }
if ( ! $results[1]['restocking'] || ! $results[2]['restocking'] || ! $results[3]['restocking'] ) { throw new RuntimeException( 'Open slots must be filled by relevant restocking compounds.' ); }
if ( in_array( 'Tesamorelin', array_column( $results, 'name' ), true ) ) { throw new RuntimeException( 'Hidden catalog products must not appear as restocking recommendations.' ); }
if ( 'image-2-large.jpg' !== $results[0]['image'] ) { throw new RuntimeException( 'Recommendations must request the uncropped large product image.' ); }

echo "Relationship engine contract: OK\n";

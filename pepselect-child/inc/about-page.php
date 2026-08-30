<?php
/**
 * About page wiring and current product-image selection.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Determine whether the current request is the coded About page.
 *
 * @return bool
 */
function pepselect_child_is_about_request() {
	return is_page( 'about-us' );
}

/**
 * Force the coded template ahead of the page's legacy Elementor assignment.
 *
 * The stored Elementor document remains untouched for rollback, but it no
 * longer owns the public About route while the child theme is active.
 *
 * @param string $template Resolved template path.
 * @return string
 */
function pepselect_child_about_template( $template ) {
	if ( ! pepselect_child_is_about_request() ) {
		return $template;
	}

	$about_template = get_stylesheet_directory() . '/page-about-us.php';

	return is_readable( $about_template ) ? $about_template : $template;
}
add_filter( 'template_include', 'pepselect_child_about_template', 99 );

/**
 * Return the current WooCommerce product used by the About page visual.
 *
 * Tesamorelin is selected by slug so the About page uses the same featured
 * image as its product page. If that listing disappears, use the first
 * published catalog product with an image rather than rendering a stale file.
 *
 * @return WC_Product|null
 */
function pepselect_child_get_about_visual_product() {
	if ( ! function_exists( 'wc_get_product' ) ) {
		return null;
	}

	$product_post = get_page_by_path( 'tesa-10', OBJECT, 'product' );

	if ( $product_post instanceof WP_Post && 'publish' === $product_post->post_status ) {
		$product = wc_get_product( $product_post->ID );

		if ( is_a( $product, 'WC_Product' ) && 0 < $product->get_image_id() ) {
			return $product;
		}
	}

	if ( ! function_exists( 'wc_get_products' ) ) {
		return null;
	}

	$products = wc_get_products(
		array(
			'status'  => 'publish',
			'limit'   => 20,
			'orderby' => 'menu_order',
			'order'   => 'ASC',
			'return'  => 'objects',
		)
	);

	foreach ( $products as $product ) {
		if ( is_a( $product, 'WC_Product' ) && $product->is_visible() && 0 < $product->get_image_id() ) {
			return $product;
		}
	}

	return null;
}

/**
 * Enqueue the coded About page stylesheet.
 *
 * @return void
 */
function pepselect_child_enqueue_about_assets() {
	if ( ! pepselect_child_is_about_request() ) {
		return;
	}

	wp_enqueue_style(
		'pepselect-child-about',
		get_stylesheet_directory_uri() . '/assets/css/about.css',
		array( 'pepselect-child-foundations' ),
		pepselect_child_asset_version( 'assets/css/about.css' )
	);
}
add_action( 'wp_enqueue_scripts', 'pepselect_child_enqueue_about_assets', 40 );

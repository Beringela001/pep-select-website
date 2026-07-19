<?php
/**
 * Single compound page presentation (WEB-2E).
 *
 * Replaces the WooCommerce long-description output with the coded compound
 * block while leaving all commerce renderers (add to cart, quantity
 * discounts, points messaging, verification, gallery) in place. The COA
 * history carousel keeps its position: it renders through the plugin's
 * [pepselect_product_coa_carousel] shortcode after the summary, exactly
 * where the legacy template placed it.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue product styles on single product pages.
 *
 * @return void
 */
function pepselect_child_enqueue_product_assets() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}

	if ( function_exists( 'pepselect_child_is_elementor_editor_request' ) && pepselect_child_is_elementor_editor_request() ) {
		return;
	}

	wp_enqueue_style(
		'pepselect-child-cards',
		get_stylesheet_directory_uri() . '/assets/css/cards.css',
		array( 'pepselect-child-foundations' ),
		pepselect_child_asset_version( 'assets/css/cards.css' )
	);

	wp_enqueue_script(
		'pepselect-child-cards-js',
		get_stylesheet_directory_uri() . '/assets/js/cards.js',
		array(),
		pepselect_child_asset_version( 'assets/js/cards.js' ),
		true
	);

	wp_enqueue_style(
		'pepselect-child-product',
		get_stylesheet_directory_uri() . '/assets/css/product.css',
		array( 'pepselect-child-foundations' ),
		pepselect_child_asset_version( 'assets/css/product.css' )
	);

	wp_enqueue_script(
		'pepselect-child-product',
		get_stylesheet_directory_uri() . '/assets/js/product.js',
		array(),
		pepselect_child_asset_version( 'assets/js/product.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'pepselect_child_enqueue_product_assets', 40 );

/**
 * Add a body class for single product styling scope.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function pepselect_child_product_body_class( $classes ) {
	if ( function_exists( 'is_product' ) && is_product() ) {
		$classes[] = 'pepselect-single-compound';
	}

	return $classes;
}
add_filter( 'body_class', 'pepselect_child_product_body_class' );

/**
 * Remove default WooCommerce after-summary output. The coded template
 * renders the description, testing history, and related grid directly, in
 * the COA-consistent card layout.
 *
 * @return void
 */
function pepselect_child_swap_product_description() {
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
}
add_action( 'wp', 'pepselect_child_swap_product_description' );

/**
 * Report whether the current request is a coded single-product view.
 *
 * @return bool
 */
function pepselect_child_is_single_compound_request() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return false;
	}

	if ( function_exists( 'pepselect_child_is_elementor_editor_request' ) && pepselect_child_is_elementor_editor_request() ) {
		return false;
	}

	return true;
}

/**
 * Seize the single-product template so the legacy Elementor Theme Builder
 * single-product template no longer renders these pages. The late priority
 * mirrors the archive and homepage mechanism.
 *
 * @param string $template Resolved template.
 * @return string
 */
function pepselect_child_single_compound_template( $template ) {
	if ( ! pepselect_child_is_single_compound_request() ) {
		return $template;
	}

	$coded = get_stylesheet_directory() . '/templates/single-compound.php';

	return file_exists( $coded ) ? $coded : $template;
}
add_filter( 'template_include', 'pepselect_child_single_compound_template', 99 );

/**
 * Prevent Elementor's Theme Builder from taking over single-product output.
 *
 * @param bool $do_override Whether Elementor should override the location.
 * @param string $location  Theme Builder location.
 * @return bool
 */
function pepselect_child_block_elementor_single_product( $do_override, $location = '' ) {
	if ( 'single' === $location && pepselect_child_is_single_compound_request() ) {
		return false;
	}

	return $do_override;
}
add_filter( 'elementor/theme/need_override_location', 'pepselect_child_block_elementor_single_product', 10, 2 );



/**
 * Trim clutter from the product summary per M4 direction: remove the short
 * description ("High-purity research peptide") above the price and the SKU
 * and category/tag meta below add to cart. Commerce logic is untouched.
 *
 * @return void
 */
function pepselect_child_trim_product_summary() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}

	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
}
add_action( 'wp', 'pepselect_child_trim_product_summary' );

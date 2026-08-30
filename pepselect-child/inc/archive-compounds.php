<?php
/**
 * Coded compounds archive (WEB-2D).
 *
 * Routes the shop page, product taxonomies, and product searches to the
 * coded archive template. The late template_include priority mirrors the
 * homepage mechanism and keeps route ownership explicit.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Report whether the current request is a supported compounds-archive view.
 *
 * @return bool
 */
function pepselect_child_is_compounds_archive_request() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return false;
	}

	$is_product_search = is_search() && 'product' === get_query_var( 'post_type' );

	return is_shop() || is_product_taxonomy() || $is_product_search;
}

/**
 * Select the coded archive template for supported requests.
 *
 * @param string $template Resolved template.
 * @return string
 */
function pepselect_child_compounds_archive_template( $template ) {
	if ( ! pepselect_child_is_compounds_archive_request() ) {
		return $template;
	}

	$coded = get_stylesheet_directory() . '/templates/archive-compounds.php';

	return file_exists( $coded ) ? $coded : $template;
}
add_filter( 'template_include', 'pepselect_child_compounds_archive_template', 99 );

/**
 * Add a body class for archive-scoped styling.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function pepselect_child_compounds_archive_body_class( $classes ) {
	if ( pepselect_child_is_compounds_archive_request() ) {
		$classes[] = 'pepselect-compounds-archive';
	}

	return $classes;
}
add_filter( 'body_class', 'pepselect_child_compounds_archive_body_class' );

/**
 * Enqueue archive styles on supported requests.
 *
 * @return void
 */
function pepselect_child_enqueue_compounds_archive_assets() {
	if ( ! pepselect_child_is_compounds_archive_request() ) {
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
		'pepselect-child-archive',
		get_stylesheet_directory_uri() . '/assets/css/archive.css',
		array( 'pepselect-child-cards' ),
		pepselect_child_asset_version( 'assets/css/archive.css' )
	);
}
add_action( 'wp_enqueue_scripts', 'pepselect_child_enqueue_compounds_archive_assets', 40 );

/**
 * Load the full catalog on one page; the selection is short by design.
 *
 * @param WP_Query $query Main query.
 * @return void
 */
function pepselect_child_compounds_archive_per_page( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_post_type_archive( 'product' ) || $query->is_tax( get_object_taxonomies( 'product' ) ) || ( $query->is_search() && 'product' === $query->get( 'post_type' ) ) ) {
		$query->set( 'posts_per_page', -1 );
		$query->set( 'no_found_rows', true );
	}
}
add_action( 'pre_get_posts', 'pepselect_child_compounds_archive_per_page' );

/**
 * Return a real 404 for impossible Shop pagination.
 *
 * The catalog intentionally renders every visible product on one page, so
 * `/shop/page/2/` and higher are duplicate crawl traps rather than valid
 * archive pages.
 *
 * @return void
 */
function pepselect_child_reject_shop_pagination() {
	if ( is_admin() || ! function_exists( 'is_shop' ) || ! is_shop() || 2 > absint( get_query_var( 'paged' ) ) ) {
		return;
	}

	global $wp_query;
	$wp_query->set_404();
	status_header( 404 );
	nocache_headers();
}
add_action( 'template_redirect', 'pepselect_child_reject_shop_pagination', 2 );

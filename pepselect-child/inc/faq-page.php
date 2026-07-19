<?php
/**
 * FAQ page wiring: content include, conditional asset enqueue, footer link.
 *
 * The template itself (page-faq.php) is selected by WordPress for the page
 * with slug "faq". This module loads the approved content source and enqueues
 * the page's presentation assets only on that page.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_stylesheet_directory() . '/inc/faq-content.php';

/**
 * Determine whether the current request is the coded FAQ page.
 *
 * @return bool
 */
function pepselect_child_is_faq_request() {
	return is_page( 'faq' );
}

/**
 * Enqueue FAQ presentation assets only on the FAQ page.
 *
 * @return void
 */
function pepselect_child_enqueue_faq_assets() {
	if ( ! pepselect_child_is_faq_request() ) {
		return;
	}

	wp_enqueue_style(
		'pepselect-child-faq',
		get_stylesheet_directory_uri() . '/assets/css/faq.css',
		array( 'pepselect-child-foundations' ),
		pepselect_child_asset_version( 'assets/css/faq.css' )
	);

	wp_enqueue_script(
		'pepselect-child-faq',
		get_stylesheet_directory_uri() . '/assets/js/faq.js',
		array(),
		pepselect_child_asset_version( 'assets/js/faq.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'pepselect_child_enqueue_faq_assets', 40 );

/**
 * Return the canonical FAQ page URL, with a path fallback.
 *
 * @return string
 */
function pepselect_child_get_faq_url() {
	return pepselect_child_get_page_url( 'faq' );
}

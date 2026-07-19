<?php
/**
 * Military & First Responder page wiring: conditional asset enqueue.
 *
 * The template (page-military-discount.php) is selected by WordPress for the
 * page with slug "military-discount". The VerifyPass button stays as authored
 * HTML in the page content, so its behavior is never altered by the theme;
 * this template only supplies the surrounding design.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Determine whether the current request is the coded Military discount page.
 *
 * @return bool
 */
function pepselect_child_is_military_request() {
	return is_page( 'military-discount' );
}

/**
 * Enqueue Military page presentation assets only on that page.
 *
 * @return void
 */
function pepselect_child_enqueue_military_assets() {
	if ( ! pepselect_child_is_military_request() ) {
		return;
	}

	wp_enqueue_style(
		'pepselect-child-military',
		get_stylesheet_directory_uri() . '/assets/css/military.css',
		array( 'pepselect-child-foundations' ),
		pepselect_child_asset_version( 'assets/css/military.css' )
	);
}
add_action( 'wp_enqueue_scripts', 'pepselect_child_enqueue_military_assets', 40 );

/**
 * Return the canonical Military discount page URL, with a path fallback.
 *
 * @return string
 */
function pepselect_child_get_military_url() {
	return pepselect_child_get_page_url( 'military-discount' );
}

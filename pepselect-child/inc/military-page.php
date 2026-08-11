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

/**
 * Force the Military & First Responder page to noindex so it stays reachable by
 * its direct URL but never surfaces in search. Applied through whichever robots
 * layer is active: Yoast (array and legacy string filters) when present, and core
 * wp_robots as a fallback when it is not. Every filter is gated to this one page,
 * so no other page is affected.
 *
 * @param array<string,mixed> $robots Robots directives.
 * @return array<string,mixed>
 */
function pepselect_child_military_robots_yoast_array( $robots ) {
	if ( pepselect_child_is_military_request() ) {
		$robots['index']  = 'noindex';
		$robots['follow'] = 'nofollow';
	}

	return $robots;
}
add_filter( 'wpseo_robots_array', 'pepselect_child_military_robots_yoast_array' );

/**
 * Legacy Yoast robots string filter, for older Yoast versions.
 *
 * @param string $robots Robots string.
 * @return string
 */
function pepselect_child_military_robots_yoast_string( $robots ) {
	return pepselect_child_is_military_request() ? 'noindex, nofollow' : $robots;
}
add_filter( 'wpseo_robots', 'pepselect_child_military_robots_yoast_string' );

/**
 * Core wp_robots fallback, used when no SEO plugin outputs the robots meta.
 *
 * @param array<string,bool> $robots Robots directives.
 * @return array<string,bool>
 */
function pepselect_child_military_robots_core( $robots ) {
	if ( pepselect_child_is_military_request() ) {
		unset( $robots['index'], $robots['follow'] );
		$robots['noindex']  = true;
		$robots['nofollow'] = true;
	}

	return $robots;
}
add_filter( 'wp_robots', 'pepselect_child_military_robots_core' );

<?php
/**
 * Canonical Pep Select site icon.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the approved standalone brand mark for every WordPress site-icon size.
 *
 * Keeping the files in the active theme avoids dependence on Media Library
 * thumbnail generation and ensures Google, browsers, feeds, embeds, and the
 * REST API all resolve the same icon family.
 *
 * @param string $url     Existing site icon URL.
 * @param int    $size    Requested square size in pixels.
 * @param int    $blog_id Site ID.
 * @return string
 */
function pepselect_child_site_icon_url( $url, $size, $blog_id ) {
	unset( $url, $blog_id );

	$size = (int) $size;

	if ( $size <= 32 ) {
		$file = 'pep-select-site-icon-32.png';
	} elseif ( $size <= 48 ) {
		$file = 'pep-select-site-icon-48.png';
	} elseif ( $size <= 180 ) {
		$file = 'pep-select-site-icon-180.png';
	} elseif ( $size <= 192 ) {
		$file = 'pep-select-site-icon-192.png';
	} else {
		$file = 'pep-select-site-icon-512.png';
	}

	return get_stylesheet_directory_uri() . '/assets/images/brand/site-icon/' . $file;
}

/**
 * Register the canonical site-icon URL override.
 *
 * @return void
 */
function pepselect_child_register_site_icon() {
	add_filter( 'get_site_icon_url', 'pepselect_child_site_icon_url', 20, 3 );
}

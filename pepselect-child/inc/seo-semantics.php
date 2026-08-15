<?php
/**
 * Small, page-specific semantic corrections.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Determine whether the current request is the About page.
 *
 * @return bool
 */
function pepselect_child_is_about_request() {
	return is_page( 'about-us' );
}

/**
 * Promote the existing About page hero heading to the page's primary heading.
 *
 * Elementor currently stores the visible hero as an H2. This changes only the
 * server-rendered tag; the copy, classes, layout, and all other headings remain
 * untouched.
 *
 * @param string $content Rendered page content.
 * @return string
 */
function pepselect_child_promote_about_heading( $content ) {
	if ( is_admin() || ! pepselect_child_is_about_request() ) {
		return $content;
	}

	$pattern = '#<h2(\s+class="elementor-heading-title elementor-size-default")>(Built For Researchers\s*<br\s*/?>\s*Who Prefer Clarity Over\s*Noise\.)</h2>#i';

	return preg_replace( $pattern, '<h1$1>$2</h1>', $content, 1 );
}
add_filter( 'the_content', 'pepselect_child_promote_about_heading', 20 );

/**
 * Keep the About page accessible by direct URL while excluding it from search.
 *
 * @param array<string,mixed> $robots Yoast robots directives.
 * @return array<string,mixed>
 */
function pepselect_child_about_robots_yoast_array( $robots ) {
	if ( pepselect_child_is_about_request() ) {
		$robots['index'] = 'noindex';
	}

	return $robots;
}
add_filter( 'wpseo_robots_array', 'pepselect_child_about_robots_yoast_array' );

/**
 * Support Yoast versions that expose robots directives as a string.
 *
 * @param string $robots Yoast robots string.
 * @return string
 */
function pepselect_child_about_robots_yoast_string( $robots ) {
	return pepselect_child_is_about_request() ? 'noindex, follow' : $robots;
}
add_filter( 'wpseo_robots', 'pepselect_child_about_robots_yoast_string' );

/**
 * Apply the same noindex directive when WordPress core owns robots output.
 *
 * @param array<string,bool> $robots Core robots directives.
 * @return array<string,bool>
 */
function pepselect_child_about_robots_core( $robots ) {
	if ( pepselect_child_is_about_request() ) {
		unset( $robots['index'] );
		$robots['noindex'] = true;
	}

	return $robots;
}
add_filter( 'wp_robots', 'pepselect_child_about_robots_core' );

/**
 * Remove the About page from Yoast XML sitemaps without deleting the page.
 *
 * @param int[] $excluded_post_ids Existing excluded post IDs.
 * @return int[]
 */
function pepselect_child_exclude_about_from_sitemap( $excluded_post_ids ) {
	$page = get_page_by_path( 'about-us' );

	if ( $page instanceof WP_Post ) {
		$excluded_post_ids[] = (int) $page->ID;
	}

	return array_values( array_unique( array_map( 'absint', $excluded_post_ids ) ) );
}
add_filter( 'wpseo_exclude_from_sitemap_by_post_ids', 'pepselect_child_exclude_about_from_sitemap' );

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
	if ( is_admin() || ! is_page( 'about-us' ) ) {
		return $content;
	}

	$pattern = '#<h2(\s+class="elementor-heading-title elementor-size-default")>(Built For Researchers\s*<br\s*/?>\s*Who Prefer Clarity Over\s*Noise\.)</h2>#i';

	return preg_replace( $pattern, '<h1$1>$2</h1>', $content, 1 );
}
add_filter( 'the_content', 'pepselect_child_promote_about_heading', 20 );

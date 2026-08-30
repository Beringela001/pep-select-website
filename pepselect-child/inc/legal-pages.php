<?php
/**
 * Routing and assets for the four coded legal pages.
 *
 * The approved legal copy lives in the child theme's versioned data file.
 * This seizes template_include at priority 99 for the four known slugs so the
 * coded documents remain the sole public source. Any slug not in the data file
 * falls through to normal WordPress behaviour.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_stylesheet_directory() . '/inc/legal-content.php';

/**
 * Return the current request's legal document, or null when this is not one of
 * our legal pages.
 *
 * @return array<string,mixed>|null
 */
function pepselect_child_current_legal_document() {
	if ( is_admin() || ! is_page() ) {
		return null;
	}

	$post = get_queried_object();

	if ( ! $post instanceof WP_Post ) {
		return null;
	}

	return pepselect_child_get_legal_document( $post->post_name );
}

/**
 * Seize the template for a known legal slug. Unknown slugs fall through so
 * nothing 404s or blanks.
 *
 * @param string $template Resolved template.
 * @return string
 */
function pepselect_child_legal_template( $template ) {
	if ( null === pepselect_child_current_legal_document() ) {
		return $template;
	}

	$coded = get_stylesheet_directory() . '/templates/legal-page.php';

	return file_exists( $coded ) ? $coded : $template;
}
add_filter( 'template_include', 'pepselect_child_legal_template', 99 );

/**
 * Enqueue the legal stylesheet on legal pages only.
 *
 * @return void
 */
function pepselect_child_enqueue_legal_assets() {
	if ( null === pepselect_child_current_legal_document() ) {
		return;
	}

	wp_enqueue_style(
		'pepselect-child-legal',
		get_stylesheet_directory_uri() . '/assets/css/legal.css',
		array( 'pepselect-child-foundations' ),
		pepselect_child_asset_version( 'assets/css/legal.css' )
	);
}
add_action( 'wp_enqueue_scripts', 'pepselect_child_enqueue_legal_assets', 40 );

/**
 * Return the canonical URL for a legal page, with a path fallback when the
 * page has not been located by slug.
 *
 * @param string $slug Legal page slug.
 * @return string
 */
function pepselect_child_get_legal_url( $slug ) {
	$page = get_page_by_path( $slug );

	if ( $page instanceof WP_Post ) {
		return get_permalink( $page );
	}

	return home_url( '/' . $slug . '/' );
}

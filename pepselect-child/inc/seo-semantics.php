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
 * Return the approved About page SEO title.
 *
 * @param string $title Existing title.
 * @return string
 */
function pepselect_child_filter_about_seo_title( $title ) {
	return pepselect_child_is_about_request()
		? __( 'About Pep Select | Research Compounds & Batch COAs', 'pepselect-child' )
		: $title;
}
add_filter( 'pre_get_document_title', 'pepselect_child_filter_about_seo_title', 20 );
add_filter( 'wpseo_title', 'pepselect_child_filter_about_seo_title', 20 );
add_filter( 'wpseo_opengraph_title', 'pepselect_child_filter_about_seo_title', 20 );
add_filter( 'wpseo_twitter_title', 'pepselect_child_filter_about_seo_title', 20 );

/**
 * Return the approved About page search and social description.
 *
 * @param string $description Existing description.
 * @return string
 */
function pepselect_child_filter_about_seo_description( $description ) {
	return pepselect_child_is_about_request()
		? __( 'Learn how Pep Select selects research compounds, requires independent laboratory testing before release, and keeps batch-specific COA records accessible.', 'pepselect-child' )
		: $description;
}
add_filter( 'wpseo_metadesc', 'pepselect_child_filter_about_seo_description', 20 );
add_filter( 'wpseo_opengraph_desc', 'pepselect_child_filter_about_seo_description', 20 );
add_filter( 'wpseo_twitter_description', 'pepselect_child_filter_about_seo_description', 20 );

/**
 * Keep About canonical and social URLs on the public page.
 *
 * @param string $url Existing URL.
 * @return string
 */
function pepselect_child_filter_about_seo_url( $url ) {
	return pepselect_child_is_about_request() ? home_url( '/about-us/' ) : $url;
}
add_filter( 'wpseo_canonical', 'pepselect_child_filter_about_seo_url', 20 );
add_filter( 'wpseo_opengraph_url', 'pepselect_child_filter_about_seo_url', 20 );

/**
 * Use the current About visual product as its social image.
 *
 * @param string $image Existing image URL.
 * @return string
 */
function pepselect_child_filter_about_social_image( $image ) {
	if ( ! pepselect_child_is_about_request() || ! function_exists( 'pepselect_child_get_about_visual_product' ) ) {
		return $image;
	}

	$product = pepselect_child_get_about_visual_product();
	$image_id = is_a( $product, 'WC_Product' ) ? $product->get_image_id() : 0;
	$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'full' ) : '';

	return $image_url ? $image_url : $image;
}
add_filter( 'wpseo_opengraph_image', 'pepselect_child_filter_about_social_image', 20 );
add_filter( 'wpseo_twitter_image', 'pepselect_child_filter_about_social_image', 20 );

/**
 * Identify the coded About page as an AboutPage in Yoast's schema graph.
 *
 * @param array $graph Yoast schema graph.
 * @return array
 */
function pepselect_child_filter_about_schema( $graph ) {
	if ( ! pepselect_child_is_about_request() ) {
		return $graph;
	}

	foreach ( $graph as $index => $piece ) {
		$types = isset( $piece['@type'] ) ? (array) $piece['@type'] : array();

		if ( in_array( 'WebPage', $types, true ) ) {
			$types[] = 'AboutPage';
			$graph[ $index ]['@type'] = array_values( array_unique( $types ) );
			$graph[ $index ]['about'] = array( '@id' => home_url( '/#organization' ) );
		}
	}

	return $graph;
}
add_filter( 'wpseo_schema_graph', 'pepselect_child_filter_about_schema', 30 );

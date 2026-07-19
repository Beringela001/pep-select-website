<?php
/**
 * Live compound search suggestions for the header typeahead.
 *
 * Registers a public, read-only REST endpoint that returns matching
 * published products with their strength tag and stock state. Results come
 * from live WooCommerce data on every request; nothing is hard-coded.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the suggestion route.
 *
 * @return void
 */
function pepselect_child_register_compound_search_route() {
	register_rest_route(
		'pepselect-child/v1',
		'/compound-search',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'pepselect_child_compound_search_callback',
			'permission_callback' => '__return_true',
			'args'                => array(
				'term' => array(
					'type'              => 'string',
					'required'          => true,
					'sanitize_callback' => 'sanitize_text_field',
				),
			),
		)
	);
}
add_action( 'rest_api_init', 'pepselect_child_register_compound_search_route' );

/**
 * Return up to eight published products matching the term.
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response
 */
function pepselect_child_compound_search_callback( $request ) {
	$term = trim( (string) $request->get_param( 'term' ) );

	if ( 2 > mb_strlen( $term ) || ! class_exists( 'WooCommerce' ) ) {
		return rest_ensure_response( array() );
	}

	$query = new WP_Query(
		array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			's'              => $term,
			'posts_per_page' => 8,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'no_found_rows'  => true,
		)
	);

	$items = array();

	foreach ( $query->posts as $post ) {
		$product = wc_get_product( $post );

		if ( ! $product || ! $product->is_visible() ) {
			continue;
		}

		$strength = function_exists( 'pepselect_child_get_product_strength_label' )
			? pepselect_child_get_product_strength_label( $product )
			: '';

		$items[] = array(
			'title'    => $product->get_name(),
			'strength' => $strength,
			'url'      => $product->get_permalink(),
			'in_stock' => $product->is_in_stock(),
		);
	}

	$response = rest_ensure_response( $items );
	$response->header( 'Cache-Control', 'public, max-age=60' );

	return $response;
}

<?php
/**
 * Private coded-homepage preview routing and presentation data.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Determine whether the authorized coded homepage preview owns this request.
 *
 * @return bool
 */
function pepselect_child_is_home_preview_request() {
	return pepselect_child_is_supported_frontend_shell_request()
		&& is_front_page()
		&& pepselect_child_preview_flag_is_set( 'pepselect_home_preview' );
}

/**
 * Register the private homepage preview hooks.
 *
 * @return void
 */
function pepselect_child_register_homepage_preview() {
	add_action( 'template_redirect', 'pepselect_child_home_preview_no_cache', 5 );
	add_filter( 'template_include', 'pepselect_child_home_preview_template', 99 );
	add_filter( 'body_class', 'pepselect_child_home_preview_body_class' );
	add_action( 'wp_enqueue_scripts', 'pepselect_child_enqueue_home_preview_assets', 40 );
}

/**
 * Prevent an authorized private homepage preview from being cached.
 *
 * @return void
 */
function pepselect_child_home_preview_no_cache() {
	if ( pepselect_child_is_home_preview_request() ) {
		nocache_headers();
	}
}

/**
 * Select the coded front-page template only for an authorized preview.
 *
 * @param string $template Resolved WordPress template.
 * @return string
 */
function pepselect_child_home_preview_template( $template ) {
	if ( ! pepselect_child_is_home_preview_request() ) {
		return $template;
	}

	$preview_template = get_stylesheet_directory() . '/templates/front-page-preview.php';

	return is_readable( $preview_template ) ? $preview_template : $template;
}

/**
 * Add the homepage component scope class for an authorized preview.
 *
 * @param string[] $classes Existing body classes.
 * @return string[]
 */
function pepselect_child_home_preview_body_class( $classes ) {
	if ( pepselect_child_is_home_preview_request() ) {
		$classes[] = 'pepselect-home-preview';
	}

	return $classes;
}

/**
 * Enqueue homepage presentation only for the authorized preview.
 *
 * @return void
 */
function pepselect_child_enqueue_home_preview_assets() {
	if ( ! pepselect_child_is_home_preview_request() ) {
		return;
	}

	wp_enqueue_style(
		'pepselect-child-home-preview',
		get_stylesheet_directory_uri() . '/assets/css/homepage.css',
		array( 'pepselect-child-foundations' ),
		pepselect_child_asset_version( 'assets/css/homepage.css' )
	);
}

/**
 * Filter WooCommerce products to the public homepage eligibility boundary.
 *
 * @param array<int,mixed> $products Candidate products.
 * @return array<int,WC_Product>
 */
function pepselect_child_filter_homepage_products( $products ) {
	$eligible = array();

	foreach ( (array) $products as $product ) {
		if ( ! is_a( $product, 'WC_Product' ) ) {
			continue;
		}

		if ( 'publish' !== $product->get_status() || ! $product->is_visible() || ! $product->is_purchasable() || ! $product->is_in_stock() ) {
			continue;
		}

		$eligible[ $product->get_id() ] = $product;
	}

	return array_values( $eligible );
}

/**
 * Return four to six qualifying products using the approved selection rule.
 *
 * Featured products are used first. When fewer than four qualify, the latest
 * other eligible products fill only the positions needed to reach four.
 *
 * @return array{available:bool,products:array<int,WC_Product>}
 */
function pepselect_child_get_homepage_products() {
	if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'wc_get_products' ) ) {
		return array(
			'available' => false,
			'products'  => array(),
		);
	}

	$featured = wc_get_products(
		array(
			'status'       => 'publish',
			'limit'        => 12,
			'featured'     => true,
			'stock_status' => 'instock',
			'orderby'      => 'menu_order',
			'order'        => 'ASC',
			'return'       => 'objects',
		)
	);

	$products = array_slice( pepselect_child_filter_homepage_products( $featured ), 0, 6 );

	if ( count( $products ) < 4 ) {
		$excluded_ids = array_map(
			static function ( $product ) {
				return $product->get_id();
			},
			$products
		);

		$fallback = wc_get_products(
			array(
				'status'       => 'publish',
				'limit'        => 12,
				'stock_status' => 'instock',
				'exclude'      => $excluded_ids,
				'orderby'      => 'date',
				'order'        => 'DESC',
				'return'       => 'objects',
			)
		);

		foreach ( pepselect_child_filter_homepage_products( $fallback ) as $product ) {
			$products[] = $product;

			if ( 4 <= count( $products ) ) {
				break;
			}
		}
	}

	return array(
		'available' => true,
		'products'  => array_slice( array_values( $products ), 0, 6 ),
	);
}

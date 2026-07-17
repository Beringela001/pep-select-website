<?php
/**
 * Public coded-homepage routing and presentation data.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Determine whether the coded homepage owns this request.
 *
 * @return bool
 */
function pepselect_child_is_home_preview_request() {
	return pepselect_child_is_supported_frontend_shell_request()
		&& is_front_page();
}

/**
 * Register the coded homepage hooks.
 *
 * @return void
 */
function pepselect_child_register_homepage_preview() {
	add_filter( 'template_include', 'pepselect_child_home_preview_template', 99 );
	add_filter( 'body_class', 'pepselect_child_home_preview_body_class' );
	add_action( 'wp_enqueue_scripts', 'pepselect_child_enqueue_home_preview_assets', 40 );
}

/**
 * Select the coded front-page template for supported front-page requests.
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
 * Add the homepage component scope class on the coded front page.
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
 * Enqueue homepage presentation only on the coded front page.
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

	wp_enqueue_script(
		'pepselect-child-home-preview',
		get_stylesheet_directory_uri() . '/assets/js/homepage.js',
		array(),
		pepselect_child_asset_version( 'assets/js/homepage.js' ),
		true
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
 * Return the product-first homepage selection through WooCommerce APIs.
 *
 * Featured products lead the candidate pool. Other current products fill any
 * remaining positions. The storefront receives at most four products, while
 * the hero receives at most three candidates with real product images.
 *
 * @return array{available:bool,products:array<int,WC_Product>,hero_products:array<int,WC_Product>}
 */
function pepselect_child_get_homepage_products() {
	if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'wc_get_products' ) ) {
		return array(
			'available'     => false,
			'products'      => array(),
			'hero_products' => array(),
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

	$featured      = pepselect_child_filter_homepage_products( $featured );
	$excluded_ids  = array_map(
		static function ( $product ) {
			return $product->get_id();
		},
		$featured
	);
	$fallback      = wc_get_products(
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
	$candidates    = array_merge( $featured, pepselect_child_filter_homepage_products( $fallback ) );
	$hero_products = array_values(
		array_filter(
			$candidates,
			static function ( $product ) {
				return 0 < $product->get_image_id();
			}
		)
	);

	return array(
		'available'     => true,
		'products'      => array_slice( $candidates, 0, 4 ),
		'hero_products' => array_slice( $hero_products, 0, 3 ),
	);
}

/**
 * Return the supported FAQ subset sourced from Elementor Homepage #571.
 *
 * The obsolete order-link item is deliberately excluded. The batch-search
 * answer is updated only to name the verified canonical archive destination.
 *
 * @return array<int,array{question:string,answer:string}>
 */
function pepselect_child_get_homepage_faqs() {
	return array(
		array(
			'question' => __( 'What are Pep Select compounds intended for?', 'pepselect-child' ),
			'answer'   => __( 'Research use only.', 'pepselect-child' ),
		),
		array(
			'question' => __( 'Do all products include COAs?', 'pepselect-child' ),
			'answer'   => __( 'Where available, documentation is associated with individual batches.', 'pepselect-child' ),
		),
		array(
			'question' => __( 'Can I verify a batch?', 'pepselect-child' ),
			'answer'   => __( 'Yes. Use the Quality Archive to search by compound and open available batch records.', 'pepselect-child' ),
		),
	);
}

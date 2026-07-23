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

	wp_enqueue_script(
		'pepselect-child-cards-js',
		get_stylesheet_directory_uri() . '/assets/js/cards.js',
		array(),
		pepselect_child_asset_version( 'assets/js/cards.js' ),
		true
	);

	wp_enqueue_style(
		'pepselect-child-cards',
		get_stylesheet_directory_uri() . '/assets/css/cards.css',
		array( 'pepselect-child-foundations' ),
		pepselect_child_asset_version( 'assets/css/cards.css' )
	);

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
 * Return the ordered priority compound names for the featured grid.
 *
 * GLP-3 R always leads while in stock. The remaining names fill open slots
 * in this order while in stock. Matching is name-based against current
 * WooCommerce records; no product is invented or hard-coded by ID.
 *
 * @return string[]
 */
function pepselect_child_get_priority_compound_names() {
	return array( 'GLP-3 R', 'GLP-2 T', 'GHK-CU', 'NAD+', 'TB-500', 'BPC-157' );
}

/**
 * Normalize a product name for priority comparison.
 *
 * @param string $name Product name.
 * @return string
 */
function pepselect_child_normalize_compound_name( $name ) {
	return strtoupper( preg_replace( '/\s+/', ' ', trim( (string) $name ) ) );
}

/**
 * Return the product-first homepage selection through WooCommerce APIs.
 *
 * Slots fill from the in-stock priority compounds in order, then from the
 * remaining eligible catalog in a rotation that reshuffles daily. The daily
 * seed keeps the selection stable within a day so page caching stays valid.
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

	$candidates = pepselect_child_filter_homepage_products(
		wc_get_products(
			array(
				'status'       => 'publish',
				'limit'        => 60,
				'stock_status' => 'instock',
				'orderby'      => 'menu_order',
				'order'        => 'ASC',
				'return'       => 'objects',
			)
		)
	);

	$selected       = array();
	$selected_ids   = array();
	$priority_names = array_map( 'pepselect_child_normalize_compound_name', pepselect_child_get_priority_compound_names() );

	foreach ( $priority_names as $priority_name ) {
		if ( 4 <= count( $selected ) ) {
			break;
		}

		foreach ( $candidates as $product ) {
			if ( isset( $selected_ids[ $product->get_id() ] ) ) {
				continue;
			}

			if ( pepselect_child_normalize_compound_name( $product->get_name() ) === $priority_name ) {
				$selected[]                          = $product;
				$selected_ids[ $product->get_id() ] = true;
				break;
			}
		}
	}

	$remaining = array_values(
		array_filter(
			$candidates,
			static function ( $product ) use ( $selected_ids ) {
				return ! isset( $selected_ids[ $product->get_id() ] );
			}
		)
	);

	if ( $remaining && 4 > count( $selected ) ) {
		mt_srand( (int) crc32( wp_date( 'Y-m-d' ) . 'pepselect-home' ) );

		for ( $i = count( $remaining ) - 1; 0 < $i; $i-- ) {
			$j = mt_rand( 0, $i );

			$swap            = $remaining[ $i ];
			$remaining[ $i ] = $remaining[ $j ];
			$remaining[ $j ] = $swap;
		}

		mt_srand();

		foreach ( $remaining as $product ) {
			if ( 4 <= count( $selected ) ) {
				break;
			}

			$selected[] = $product;
		}
	}

	return array(
		'available' => true,
		'products'  => array_slice( $selected, 0, 4 ),
	);
}

/**
 * Return the attachment ID of the approved hero family image.
 *
 * The image is resolved from the Media Library through the current
 * environment's uploads URL so the reference survives domain changes.
 * Candidates are checked in order; a missing attachment produces the
 * branded image-ready state, never a broken layout.
 *
 * @return int
 */
function pepselect_child_get_hero_image_id() {
	$chosen_id = absint( get_theme_mod( 'pepselect_hero_image', 0 ) );

	if ( 0 < $chosen_id && wp_attachment_is_image( $chosen_id ) ) {
		return $chosen_id;
	}

	$uploads_base = trailingslashit( wp_get_upload_dir()['baseurl'] );
	$candidates   = array(
		'2026/07/PS-laying_fam-scaled.png',
		'2026/07/PS-laying_fam.png',
		'2026/07/ChatGPT-Image-Jul-2_-2026_-07_21_25-AM.webp',
	);

	foreach ( $candidates as $relative_path ) {
		$attachment_id = attachment_url_to_postid( $uploads_base . $relative_path );

		if ( 0 < $attachment_id ) {
			return (int) $attachment_id;
		}
	}

	return 0;
}

/**
 * Return the strength label for a product from its WooCommerce product tags.
 *
 * Mirrors the legacy Elementor loop card, which sourced the strength bubble
 * from the product_tag taxonomy. Only tags matching a strength pattern are
 * used; products without a strength tag render no badge.
 *
 * @param WC_Product $product Product record.
 * @return string
 */
function pepselect_child_get_product_strength_label( $product ) {
	if ( ! is_a( $product, 'WC_Product' ) ) {
		return '';
	}

	$terms = get_the_terms( $product->get_id(), 'product_tag' );

	if ( ! is_array( $terms ) ) {
		return '';
	}

	foreach ( $terms as $term ) {
		$name = trim( $term->name );

		if ( preg_match( '/^\d+(?:\.\d+)?\s*(?:mg|mcg|iu|ml|g)$/i', $name ) ) {
			return strtoupper( preg_replace( '/\s+/', '', $name ) );
		}
	}

	return '';
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
			'question' => __( 'What does "for research use only" mean?', 'pepselect-child' ),
			'answer'   => __( 'Every compound in the catalog is supplied for laboratory research only. Nothing we sell is intended for use in humans or animals.', 'pepselect-child' ),
		),
		array(
			'question' => __( 'How do I find documentation for my batch?', 'pepselect-child' ),
			'answer'   => __( 'Open the Quality Archive and search by compound. When a batch record exists, it is filed there and stays online.', 'pepselect-child' ),
		),
		array(
			'question' => __( 'How do orders ship, and how fast?', 'pepselect-child' ),
			'answer'   => __( 'Orders placed before 10:00 AM ET, Monday through Thursday, ship the same day. Orders placed after that cutoff ship the next day, and orders placed Friday through Sunday ship on Monday. Holidays can shift these times when carrier services are closed. Orders with a subtotal of $200 or more ship free with FedEx two-day.', 'pepselect-child' ),
		),
		array(
			'question' => __( 'How does payment work?', 'pepselect-child' ),
			'answer'   => __( 'After you place an order, a secure Square payment link arrives by email. Your order confirms once payment clears, and unpaid orders cancel automatically after 90 minutes.', 'pepselect-child' ),
		),
		array(
			'question' => __( 'What if something is wrong with my order?', 'pepselect-child' ),
			'answer'   => __( 'If an order arrives damaged or incorrect, contact support within 72 hours of delivery with your order number and photos of the product and packaging. Once verified, we resolve it with a replacement, refund, or store credit.', 'pepselect-child' ),
		),
	);
}

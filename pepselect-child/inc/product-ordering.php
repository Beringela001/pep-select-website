<?php
/**
 * Compound archive ordering (M10).
 *
 * Products sort first by live availability: in stock, restocking soon, then
 * out of stock. Within each availability group, compounds follow Pep Select's
 * research-mechanism merchandising sequence: GLPs, healing/repair,
 * metabolism/mitochondrial, growth-hormone axis, then supporting/other.
 * Per-product display order remains the tie-breaker within one compound and for
 * future compounds that have not yet been classified.
 *
 * The sort is applied to the main query on the shop page, every product
 * taxonomy (category and tag) listing, and product search, so it covers all
 * archive surfaces, not just one.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Meta key holding a product's display order. Lower sorts first. Unset products
 * fall back to a high default, so they sit after the seeded ones.
 */
if ( ! defined( 'PEPSELECT_DISPLAY_ORDER_META' ) ) {
	define( 'PEPSELECT_DISPLAY_ORDER_META', '_pepselect_display_order' );
}

/**
 * Default display order for products with no value set.
 */
if ( ! defined( 'PEPSELECT_DISPLAY_ORDER_DEFAULT' ) ) {
	define( 'PEPSELECT_DISPLAY_ORDER_DEFAULT', 9999 );
}

/**
 * Add the "Display order" number field to the product data General panel.
 *
 * @return void
 */
function pepselect_child_display_order_admin_field() {
	woocommerce_wp_text_input(
		array(
			'id'                => PEPSELECT_DISPLAY_ORDER_META,
			'label'             => __( 'Display order (Pep Select)', 'pepselect-child' ),
			'desc_tip'          => true,
			'description'       => __( 'Lower numbers break ties within the same compound and order unclassified products. Live availability and Pep Select compound-family priority always sort first.', 'pepselect-child' ),
			'type'              => 'number',
			'custom_attributes' => array(
				'step' => '1',
				'min'  => '0',
			),
		)
	);
}
add_action( 'woocommerce_product_options_general_product_data', 'pepselect_child_display_order_admin_field' );

/**
 * Save the display-order field. WooCommerce verifies its own product meta nonce
 * before this hook fires.
 *
 * @param int $post_id Product ID.
 * @return void
 */
function pepselect_child_display_order_save( $post_id ) {
	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- WooCommerce verifies the product meta nonce before woocommerce_process_product_meta.
	$raw = isset( $_POST[ PEPSELECT_DISPLAY_ORDER_META ] ) ? sanitize_text_field( wp_unslash( $_POST[ PEPSELECT_DISPLAY_ORDER_META ] ) ) : '';

	if ( '' === $raw ) {
		delete_post_meta( $post_id, PEPSELECT_DISPLAY_ORDER_META );
		return;
	}

	update_post_meta( $post_id, PEPSELECT_DISPLAY_ORDER_META, absint( $raw ) );
}
add_action( 'woocommerce_process_product_meta', 'pepselect_child_display_order_save' );

/**
 * Whether a query is one of the compound-archive surfaces to sort: the shop
 * page, a product taxonomy (category or tag), or a product search.
 *
 * @param WP_Query $query Query being evaluated.
 * @return bool
 */
function pepselect_child_is_sortable_archive_query( $query ) {
	if ( is_admin() || ! $query instanceof WP_Query || ! $query->is_main_query() ) {
		return false;
	}

	if ( $query->is_post_type_archive( 'product' ) ) {
		return true;
	}

	if ( $query->is_tax( get_object_taxonomies( 'product' ) ) ) {
		return true;
	}

	return $query->is_search() && 'product' === $query->get( 'post_type' );
}

/**
 * Read a product's display order, defaulting when unset.
 *
 * @param int $product_id Product ID.
 * @return int
 */
function pepselect_child_get_display_order( $product_id ) {
	$value = get_post_meta( $product_id, PEPSELECT_DISPLAY_ORDER_META, true );

	return '' === $value ? PEPSELECT_DISPLAY_ORDER_DEFAULT : (int) $value;
}

/**
 * Normalize a WooCommerce product name for explicit alias matching.
 *
 * @param string $name Product name.
 * @return string
 */
function pepselect_child_normalize_merchandising_name( $name ) {
	return (string) preg_replace( '/[^a-z0-9]/', '', strtolower( (string) $name ) );
}

/**
 * Whether a normalized product name begins with one of the approved aliases.
 * Prefix matching allows strength suffixes such as "10mg" while avoiding broad
 * substring matches (for example, "nad" must not match "gonadorelin").
 *
 * @param string   $normalized Normalized product name.
 * @param string[] $aliases    Approved normalized aliases.
 * @return bool
 */
function pepselect_child_merchandising_name_matches( $normalized, $aliases ) {
	foreach ( (array) $aliases as $alias ) {
		if ( 0 === strpos( $normalized, $alias ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Return the evidence-based merchandising priority for a compound name.
 *
 * These are internal catalog groups, not a universal scientific taxonomy. The
 * aliases are intentionally explicit: unknown future compounds fall through to
 * the admin display order instead of being guessed from partial name matches.
 * Bacteriostatic water is a supply and is deliberately not classified here.
 *
 * @param string $product_name WooCommerce product name.
 * @return int Lower values sort first.
 */
function pepselect_child_get_compound_priority( $product_name ) {
	$normalized = pepselect_child_normalize_merchandising_name( $product_name );
	$sequence   = array(
		// GLP / incretin receptor compounds: triple, dual, then single agonist.
		100 => array( 'glp3r', 'retatrutide' ),
		110 => array( 'glp2t', 'tirzepatide' ),
		120 => array( 'glp1s', 'semaglutide' ),

		// Healing and repair research.
		200 => array( 'bpc157' ),
		210 => array( 'tb500', 'thymosinbeta4' ),
		220 => array( 'ghkcu' ),

		// Metabolism and mitochondrial research.
		300 => array( 'motsc' ),
		310 => array( 'ss31', 'elamipretide' ),
		320 => array( 'nad' ),

		// Growth-hormone-axis research.
		400 => array( 'tesamorelin', 'tesa' ),
		410 => array( 'sermorelin' ),
		420 => array( 'cjc1295', 'modifiedgrf129' ),
		430 => array( 'ipamorelin' ),

		// Supporting and other research compounds.
		500 => array( 'kpv' ),
		510 => array( 'glutathione' ),
		520 => array( 'pt141', 'bremelanotide' ),
	);

	foreach ( $sequence as $priority => $aliases ) {
		if ( pepselect_child_merchandising_name_matches( $normalized, $aliases ) ) {
			return (int) $priority;
		}
	}

	return 9999;
}

/**
 * Return live availability priority: in stock, restocking soon, out of stock.
 *
 * @param WC_Product|null $product Product object.
 * @return int Lower values sort first.
 */
function pepselect_child_get_availability_priority( $product ) {
	if ( $product && $product->is_in_stock() ) {
		return 0;
	}

	if ( $product && function_exists( 'pepselect_child_get_product_status_band' ) ) {
		$status_band = pepselect_child_get_product_status_band( $product->get_id() );

		if ( null !== $status_band ) {
			return 1;
		}
	}

	return 2;
}

/**
 * Sort archive posts by live availability, compound priority, display order,
 * then original position so the sort is stable. Runs on the main archive query,
 * which the coded archive template loops directly and which loads the full
 * catalog on one page, so re-ordering the full array orders the whole listing.
 *
 * @param WP_Post[] $posts Posts returned for the query.
 * @param WP_Query  $query Query the posts belong to.
 * @return WP_Post[]
 */
function pepselect_child_sort_archive_products( $posts, $query ) {
	if ( empty( $posts ) || ! pepselect_child_is_sortable_archive_query( $query ) ) {
		return $posts;
	}

	if ( ! function_exists( 'wc_get_product' ) ) {
		return $posts;
	}

	$decorated = array();

	foreach ( $posts as $index => $post ) {
		$product = wc_get_product( $post->ID );

		$decorated[] = array(
			'post'         => $post,
			'availability' => pepselect_child_get_availability_priority( $product ),
			'compound'     => $product ? pepselect_child_get_compound_priority( $product->get_name() ) : 9999,
			'order'        => pepselect_child_get_display_order( $post->ID ),
			'index'        => $index,
		);
	}

	usort(
		$decorated,
		static function ( $a, $b ) {
			if ( $a['availability'] !== $b['availability'] ) {
				return $a['availability'] <=> $b['availability'];
			}

			if ( $a['compound'] !== $b['compound'] ) {
				return $a['compound'] <=> $b['compound'];
			}

			if ( $a['order'] !== $b['order'] ) {
				return $a['order'] <=> $b['order'];
			}

			return $a['index'] <=> $b['index'];
		}
	);

	return wp_list_pluck( $decorated, 'post' );
}
add_filter( 'the_posts', 'pepselect_child_sort_archive_products', 10, 2 );

/**
 * One-time legacy seed of the display-order tie-breaker, matched by a normalized
 * product title so the values land on real products. Availability and compound
 * family priority always outrank these stored values. The seed remains for new
 * installations and for stable ordering among multiple strengths.
 *
 * @return void
 */
function pepselect_child_seed_display_order() {
	if ( get_option( 'pepselect_display_order_seed_v1' ) ) {
		return;
	}

	if ( ! function_exists( 'wc_get_products' ) ) {
		return;
	}

	// Normalized title needle => display order. Normalization strips everything
	// but a-z0-9, so "GLP-3 R 5mg" matches "glp3r".
	$sequence = array(
		'glp3r'       => 10,
		'glp2t'       => 20,
		'glp1s'       => 30,
		'bpc157'      => 40,
		'tb500'       => 50,
		'ghkcu'       => 60,
		'motsc'       => 70,
		'ss31'        => 80,
		'nad'         => 90,
		'tesamorelin' => 100,
		'sermorelin'  => 110,
		'cjc1295'     => 120,
		'ipamorelin'  => 130,
		'kpv'         => 140,
		'glutathione' => 150,
		'pt141'       => 160,
	);

	$products = wc_get_products(
		array(
			'status' => 'publish',
			'limit'  => -1,
			'return' => 'objects',
		)
	);

	foreach ( (array) $products as $product ) {
		if ( '' !== get_post_meta( $product->get_id(), PEPSELECT_DISPLAY_ORDER_META, true ) ) {
			continue;
		}

		$normalized = preg_replace( '/[^a-z0-9]/', '', strtolower( (string) $product->get_name() ) );

		foreach ( $sequence as $needle => $order ) {
			if ( 0 === strpos( $normalized, $needle ) ) {
				update_post_meta( $product->get_id(), PEPSELECT_DISPLAY_ORDER_META, $order );
				break;
			}
		}
	}

	update_option( 'pepselect_display_order_seed_v1', 1 );
}
add_action( 'init', 'pepselect_child_seed_display_order', 20 );

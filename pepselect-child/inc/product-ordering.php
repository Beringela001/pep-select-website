<?php
/**
 * Compound archive ordering (M10).
 *
 * In-stock products always sort before out-of-stock ones. Within the in-stock
 * group, products sort by a numeric "display order" stored per product and
 * editable in the product admin, so the sequence can be changed without a code
 * change. Stock status always outranks the display order.
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
			'description'       => __( 'Lower numbers appear first within the in-stock group on the shop, category, and search listings. Out-of-stock products always sink to the bottom regardless of this value.', 'pepselect-child' ),
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
 * Sort archive posts: in-stock first, then by display order, then by original
 * position so the sort is stable. Runs on the main archive query, which the
 * coded archive template loops directly and which loads the full catalog on one
 * page, so re-ordering the full array orders the whole listing.
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
		$product  = wc_get_product( $post->ID );
		$in_stock = $product ? $product->is_in_stock() : false;

		$decorated[] = array(
			'post'     => $post,
			'in_stock' => $in_stock ? 0 : 1,
			'order'    => pepselect_child_get_display_order( $post->ID ),
			'index'    => $index,
		);
	}

	usort(
		$decorated,
		static function ( $a, $b ) {
			if ( $a['in_stock'] !== $b['in_stock'] ) {
				return $a['in_stock'] <=> $b['in_stock'];
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
 * One-time seed of the display-order field to the launch sequence, matched by a
 * normalized product title so the values land on real products. After this runs
 * once (guarded by an option), the admin field is the source of truth and the
 * sequence can be changed there without code. The sort logic itself never reads
 * this list; it only reads the stored meta.
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
		'nad'         => 40,
		'tb500'       => 50,
		'bpc157'      => 60,
		'tesamorelin' => 70,
		'glutathione' => 80,
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
			if ( false !== strpos( $normalized, $needle ) ) {
				update_post_meta( $product->get_id(), PEPSELECT_DISPLAY_ORDER_META, $order );
				break;
			}
		}
	}

	update_option( 'pepselect_display_order_seed_v1', 1 );
}
add_action( 'init', 'pepselect_child_seed_display_order', 20 );

<?php
/**
 * Read-only bridge to the Pep Select COA Archive plugin.
 *
 * Maps WooCommerce products to their plugin compound and surfaces the state
 * of pending batches for storefront status bands. Nothing here writes to
 * plugin data; the plugin's ownership of records is untouched.
 *
 * Mapping agreed 2026-07-18 (simplified same day): every pending batch,
 * whether at the vendor or at the laboratory, surfaces as "Restocking
 * Soon". The distinction lives on the product page. Expected COA dates
 * are deliberately not shown.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the product-to-status-band map, built once per request.
 *
 * @return array<int,array{label:string,tone:string}>
 */
function pepselect_child_get_compound_status_map() {
	static $map = null;

	if ( null !== $map ) {
		return $map;
	}

	$map = array();

	if ( ! post_type_exists( 'ps_compound' ) || ! post_type_exists( 'ps_coa_test' ) ) {
		return $map;
	}

	$cache_key = 'pepselect_child_compound_status_map_' . md5( (string) wp_get_theme()->get( 'Version' ) );
	$cached    = get_transient( $cache_key );

	if ( is_array( $cached ) ) {
		$map = $cached;
		return $map;
	}

	// Compound -> product mapping.
	$compound_products = array();
	$compounds         = get_posts(
		array(
			'post_type'      => 'ps_compound',
			'post_status'    => 'publish',
			'posts_per_page' => 200,
			'fields'         => 'ids',
			'no_found_rows'  => true,
		)
	);

	foreach ( $compounds as $compound_id ) {
		$product_id = absint( get_post_meta( $compound_id, 'woocommerce_product_id', true ) );

		if ( 0 < $product_id ) {
			$compound_products[ $compound_id ] = $product_id;
		}
	}

	if ( $compound_products ) {
		$pending_tests = get_posts(
			array(
				'post_type'      => 'ps_coa_test',
				'post_status'    => 'publish',
				'posts_per_page' => 200,
				'fields'         => 'ids',
				'no_found_rows'  => true,
				'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- Bounded, transient-cached read.
					array(
						'key'   => 'coa_status',
						'value' => 'pending',
					),
				),
			)
		);

		foreach ( $pending_tests as $test_id ) {
			$compound_id = absint( get_post_meta( $test_id, 'compound_id', true ) );

			if ( ! isset( $compound_products[ $compound_id ] ) ) {
				continue;
			}

			$product_id = $compound_products[ $compound_id ];
			$stage = (string) get_post_meta( $test_id, 'workflow_stage', true );

			if ( ! in_array( $stage, array( 'vendor-vetting', 'waiting-on-vendor', 'submitted-to-lab', 'in-testing' ), true ) ) {
				continue;
			}

			$map[ $product_id ] = array(
				'label' => __( 'Restocking Soon', 'pepselect-child' ),
				'tone'  => 'incoming',
			);
		}
	}

	set_transient( $cache_key, $map, 5 * MINUTE_IN_SECONDS );

	return $map;
}

/**
 * Return the status band for one product, or null.
 *
 * @param int $product_id Product ID.
 * @return array{label:string,tone:string}|null
 */
function pepselect_child_get_product_status_band( $product_id ) {
	$map = pepselect_child_get_compound_status_map();

	return isset( $map[ $product_id ] ) ? $map[ $product_id ] : null;
}

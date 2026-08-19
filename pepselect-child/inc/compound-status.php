<?php
/**
 * Read-only bridge to the Pep Select COA Archive plugin.
 *
 * Maps WooCommerce products to their plugin compound and surfaces whether a
 * batch is in the vetting pipeline, for the storefront "Restocking soon" chip.
 * Nothing here writes to plugin data; the plugin's ownership of records is
 * untouched, and every read is guarded so a missing plugin degrades to "Out of
 * stock" rather than an error or a blank.
 *
 * A product is flagged restocking when it has at least one batch whose
 * workflow_stage is not terminal (see PEPSELECT_COA_TERMINAL_STAGES). Compounds
 * are stored per strength -- "Retatrutide 10mg", "20mg", and "30mg" are separate
 * ps_compound records -- so the compound -> product link is already 1:1 and no
 * SKU keying is needed. That link lives on the ps_compound record as
 * pepselect_coa_product_id, with woocommerce_product_id read only as a fallback
 * for any older record that carries just that.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Terminal COA workflow stages. A batch whose workflow_stage matches one of
 * these has left the pipeline -- documented and released, or failed out -- and
 * does not signal restocking. Every other stage (vendor vetting, waiting on
 * vendor, submitted to laboratory, verification in progress, or any pre-approval
 * stage renamed or added upstream later) counts as in-pipeline.
 *
 * Matching on "not terminal" rather than an allowlist of pipeline stages is
 * deliberate: an allowlist silently drops a product back to "Out of stock" the
 * moment an upstream stage is renamed, which is the failure this replaces. The
 * trade-off is the opposite, louder failure -- if the terminal value itself is
 * renamed, completed batches read "Restocking soon" until this list is updated,
 * which is visible and easily caught. Keep this list in sync with the plugin's
 * terminal stage values.
 */
if ( ! defined( 'PEPSELECT_COA_TERMINAL_STAGES' ) ) {
	define( 'PEPSELECT_COA_TERMINAL_STAGES', array( 'complete' ) );
}

/**
 * Normalize a workflow-stage value for tolerant comparison: lowercased, with
 * every run of non-alphanumeric characters collapsed to a single hyphen and the
 * ends trimmed. So "vendor-vetting", "vendor_vetting", and "Vendor Vetting" all
 * resolve to "vendor-vetting", and separator or case drift cannot break a match.
 *
 * @param string $stage Raw stage value.
 * @return string
 */
function pepselect_child_coa_normalize_stage( $stage ) {
	$stage = strtolower( trim( (string) $stage ) );
	$stage = preg_replace( '/[^a-z0-9]+/', '-', $stage );

	return trim( $stage, '-' );
}

/**
 * Whether a workflow stage is terminal. An empty or unrecognized stage is not
 * terminal, so it counts as in-pipeline -- the safe direction for this chip.
 *
 * @param string $stage Raw workflow_stage value.
 * @return bool
 */
function pepselect_child_coa_stage_is_terminal( $stage ) {
	$normalized = pepselect_child_coa_normalize_stage( $stage );

	if ( '' === $normalized ) {
		return false;
	}

	foreach ( (array) PEPSELECT_COA_TERMINAL_STAGES as $terminal ) {
		if ( $normalized === pepselect_child_coa_normalize_stage( $terminal ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Build the product -> status-band map from a resolved compound->product map and
 * a list of batch records. Pure (no database access) so the resolver can be
 * exercised against synthetic records. A product is flagged when it has at least
 * one non-terminal batch.
 *
 * @param array<int,int>                                          $compound_products compound_id => product_id.
 * @param array<int,array{compound_id:int,workflow_stage:string}> $batches           Batch records.
 * @return array<int,array{label:string,tone:string}>
 */
function pepselect_child_coa_map_from( $compound_products, $batches ) {
	$map = array();

	foreach ( (array) $batches as $batch ) {
		$compound_id = isset( $batch['compound_id'] ) ? absint( $batch['compound_id'] ) : 0;

		if ( ! isset( $compound_products[ $compound_id ] ) ) {
			continue;
		}

		$stage = isset( $batch['workflow_stage'] ) ? $batch['workflow_stage'] : '';

		if ( pepselect_child_coa_stage_is_terminal( $stage ) ) {
			continue;
		}

		$product_id = (int) $compound_products[ $compound_id ];

		$map[ $product_id ] = array(
			'label' => __( 'Restocking Soon', 'pepselect-child' ),
			'tone'  => 'incoming',
		);
	}

	return $map;
}

/**
 * Return the product-to-status-band map, built once per request and cached.
 *
 * @return array<int,array{label:string,tone:string}>
 */
function pepselect_child_get_compound_status_map() {
	static $map = null;

	if ( null !== $map ) {
		return $map;
	}

	$map = array();

	$cache_key = 'pepselect_child_compound_status_map_' . md5( (string) wp_get_theme()->get( 'Version' ) );
	$cached    = get_transient( $cache_key );

	if ( is_array( $cached ) ) {
		$map = $cached;
		return $map;
	}

	// Ops may publish intent before a physical batch exists. This private Woo
	// product meta is display-only: it never changes stock, backorders, or COA.
	$manual_products = get_posts(
		array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 200,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'meta_key'       => '_pepselect_restocking_soon',
			'meta_value'     => 'yes',
		)
	);
	foreach ( $manual_products as $product_id ) {
		$map[ (int) $product_id ] = array(
			'label' => __( 'Restocking Soon', 'pepselect-child' ),
			'tone'  => 'incoming',
		);
	}

	if ( ! post_type_exists( 'ps_compound' ) || ! post_type_exists( 'ps_coa_test' ) ) {
		set_transient( $cache_key, $map, 5 * MINUTE_IN_SECONDS );
		return $map;
	}

	// Compound -> product. The link is stored as pepselect_coa_product_id on the
	// ps_compound record (set through the plugin's WooCommerce Product Matching
	// panel); woocommerce_product_id is read only as a fallback for older records.
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
		$product_id = absint( get_post_meta( $compound_id, 'pepselect_coa_product_id', true ) );

		if ( 0 === $product_id ) {
			$product_id = absint( get_post_meta( $compound_id, 'woocommerce_product_id', true ) );
		}

		if ( 0 < $product_id ) {
			$compound_products[ $compound_id ] = $product_id;
		}
	}

	if ( $compound_products ) {
		// Every published batch record. The in-pipeline test is workflow_stage,
		// not coa_status: a batch signals restocking while it moves through the
		// vetting pipeline, whatever its eventual pass/fail coa_status, which is
		// only meaningful once the batch is complete.
		$batch_ids = get_posts(
			array(
				'post_type'      => 'ps_coa_test',
				'post_status'    => 'publish',
				'posts_per_page' => 200,
				'fields'         => 'ids',
				'no_found_rows'  => true,
			)
		);

		$batches = array();

		foreach ( $batch_ids as $test_id ) {
			$batches[] = array(
				'compound_id'    => absint( get_post_meta( $test_id, 'compound_id', true ) ),
				'workflow_stage' => (string) get_post_meta( $test_id, 'workflow_stage', true ),
			);
		}

		$map = array_replace( $map, pepselect_child_coa_map_from( $compound_products, $batches ) );
	}

	set_transient( $cache_key, $map, 5 * MINUTE_IN_SECONDS );

	return $map;
}

/** Invalidate the combined status map immediately when Ops changes its meta. */
function pepselect_child_clear_restocking_status_cache( $meta_id, $object_id, $meta_key ) {
	if ( '_pepselect_restocking_soon' !== $meta_key ) {
		return;
	}
	delete_transient( 'pepselect_child_compound_status_map_' . md5( (string) wp_get_theme()->get( 'Version' ) ) );
}
add_action( 'added_post_meta', 'pepselect_child_clear_restocking_status_cache', 10, 3 );
add_action( 'updated_post_meta', 'pepselect_child_clear_restocking_status_cache', 10, 3 );
add_action( 'deleted_post_meta', 'pepselect_child_clear_restocking_status_cache', 10, 3 );

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

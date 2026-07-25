<?php
/**
 * Legacy shortcodes migrated from the parent theme (M10 migration).
 *
 * These three shortcodes lived in the parent theme's functions.php and would be
 * lost on a Hello Elementor update, leaving any page that still contains them
 * rendering raw shortcode text. They are migrated here with the
 * pepselect_child_ prefix so they never collide with the parent's originals.
 *
 * [product_stock_status] is migrated as-is (it uses the global $product and has
 * no Advanced Custom Fields dependency). [recent_batches] and [coa_table]
 * depended on ACF, which has been removed from the site, and are superseded by
 * the COA Archive plugin; they are kept only as empty stubs so legacy pages
 * render nothing rather than the raw shortcode, and can be removed once no
 * published content references them.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * [product_stock_status] — output the current product's stock status. Migrated
 * verbatim from the parent theme, renamed. Relies on the global $product.
 *
 * @return string
 */
function pepselect_child_product_stock_status_shortcode() {
	global $product;

	if ( ! $product ) {
		return '';
	}

	if ( $product->is_in_stock() ) {
		return '<span class="stock-status in-stock">In Stock</span>';
	} else {
		return '<span class="stock-status out-of-stock">Out of Stock</span>';
	}
}
add_shortcode( 'product_stock_status', 'pepselect_child_product_stock_status_shortcode' );

/**
 * [recent_batches] — deprecated. Previously rendered recent batch records from
 * Advanced Custom Fields. ACF has been removed and this is superseded by the
 * COA Archive plugin. Retained only so a legacy page still containing the
 * shortcode renders nothing instead of raw "[recent_batches]" text. Safe to
 * remove once no published content references it.
 *
 * @return string Always an empty string.
 */
function pepselect_child_recent_batches_shortcode() {
	return '';
}
add_shortcode( 'recent_batches', 'pepselect_child_recent_batches_shortcode' );

/**
 * [coa_table] — deprecated. Previously rendered a certificate-of-analysis table
 * from Advanced Custom Fields. ACF has been removed and this is superseded by
 * the COA Archive plugin. Retained only so a legacy page still containing the
 * shortcode renders nothing instead of raw "[coa_table]" text. Safe to remove
 * once no published content references it.
 *
 * @return string Always an empty string.
 */
function pepselect_child_coa_table_shortcode() {
	return '';
}
add_shortcode( 'coa_table', 'pepselect_child_coa_table_shortcode' );

<?php
/**
 * Front-end performance safeguards for the audited SEO templates.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return whether the current request is one of the four PageSpeed templates.
 *
 * @return bool
 */
function pepselect_child_is_seo_performance_template() {
	return is_front_page()
		|| is_home()
		|| ( function_exists( 'is_shop' ) && is_shop() )
		|| ( function_exists( 'is_product' ) && is_product() )
		|| is_page( 'testing' );
}

/**
 * Remove files that have no matching component on the audited templates.
 *
 * This runs after plugin and Elementor enqueue callbacks. WooCommerce product,
 * cart, checkout, rewards, pricing, side-cart, and back-in-stock assets remain
 * untouched.
 *
 * @return void
 */
function pepselect_child_optimize_seo_template_assets() {
	if ( is_admin() || ! pepselect_child_is_seo_performance_template() ) {
		return;
	}

	$unused_styles = array(
		'deensimc-marquee-common-styles',
		'deensimc-text-marquee-style',
		'jetpack-forms-layout',
		'mediaelement',
		'wp-mediaelement',
		'wp-block-library',
		'wc-blocks-style',
	);

	foreach ( $unused_styles as $style_handle ) {
		wp_dequeue_style( $style_handle );
	}

	$unused_scripts = array(
		'deensimc-marquee-track-fill',
		'deensimc-handle-animation-duration',
		'deensimc-init-text-length-toggle',
		'deensimc-text-marquee-script',
	);

	foreach ( $unused_scripts as $script_handle ) {
		wp_dequeue_script( $script_handle );
	}
}
add_action( 'wp_enqueue_scripts', 'pepselect_child_optimize_seo_template_assets', 999 );

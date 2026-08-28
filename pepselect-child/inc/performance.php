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
 * Return whether the current request is the Quality Archive route.
 *
 * WordPress can expose the assigned posts page through is_home() instead of
 * is_page(), so also check the queried page slug.
 *
 * @return bool
 */
function pepselect_child_is_testing_template() {
	$queried_id = get_queried_object_id();
	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$request_path = wp_parse_url( $request_uri, PHP_URL_PATH );

	return is_page( 'testing' )
		|| ( $queried_id && 'testing' === get_post_field( 'post_name', $queried_id ) )
		|| '/testing' === untrailingslashit( (string) $request_path );
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
		|| pepselect_child_is_testing_template();
}

/**
 * Return whether performance cleanup may replace Elementor front-end assets.
 *
 * The audited Home, Shop, product, and Quality Archive templates are rendered
 * by the child theme or the COA plugin. Elementor remains available in its
 * editor and preview requests, where its runtime assets are required.
 *
 * @return bool
 */
function pepselect_child_can_remove_elementor_assets() {
	if ( ! pepselect_child_is_seo_performance_template() ) {
		return false;
	}

	return ! function_exists( 'pepselect_child_is_elementor_editor_request' )
		|| ! pepselect_child_is_elementor_editor_request();
}

/**
 * Return whether a registered asset belongs to Elementor's front-end runtime.
 *
 * @param string $handle Registered WordPress asset handle.
 * @param string $src    Registered asset source URL or path.
 * @return bool
 */
function pepselect_child_is_elementor_frontend_asset( $handle, $src ) {
	$src = (string) $src;

	return false !== strpos( $src, '/plugins/elementor/' )
		|| false !== strpos( $src, '/plugins/elementor-pro/' )
		|| ( 0 === strpos( (string) $handle, 'elementor-post-' ) && false !== strpos( $src, '/uploads/elementor/css/' ) );
}

/**
 * Remove unused Elementor styles from fully coded audited templates.
 *
 * @return void
 */
function pepselect_child_remove_elementor_styles() {
	global $wp_styles;

	if ( is_admin() || ! pepselect_child_can_remove_elementor_assets() || ! $wp_styles instanceof WP_Styles ) {
		return;
	}

	foreach ( $wp_styles->registered as $handle => $asset ) {
		if ( ! pepselect_child_is_elementor_frontend_asset( $handle, $asset->src ) ) {
			continue;
		}

		wp_dequeue_style( $handle );
		wp_deregister_style( $handle );
	}
}
add_action( 'wp_print_styles', 'pepselect_child_remove_elementor_styles', 996 );

/**
 * Remove unused Elementor scripts from fully coded audited templates.
 *
 * This also removes the orphaned frontend bundle currently logging
 * `elementorFrontendConfig is not defined` in Lighthouse.
 *
 * @return void
 */
function pepselect_child_remove_elementor_scripts() {
	global $wp_scripts;

	if ( is_admin() || ! pepselect_child_can_remove_elementor_assets() || ! $wp_scripts instanceof WP_Scripts ) {
		return;
	}

	foreach ( $wp_scripts->registered as $handle => $asset ) {
		if ( ! pepselect_child_is_elementor_frontend_asset( $handle, $asset->src ) ) {
			continue;
		}

		wp_dequeue_script( $handle );
		wp_deregister_script( $handle );
	}
}
add_action( 'wp_print_scripts', 'pepselect_child_remove_elementor_scripts', 996 );
add_action( 'wp_print_footer_scripts', 'pepselect_child_remove_elementor_scripts', 1 );

/**
 * Defer non-critical helpers while preserving their dependency order.
 *
 * Checkout and account requests are outside this template boundary. These
 * handles remain loaded on catalog templates for commerce behavior, but no
 * longer block their first paint.
 *
 * @return void
 */
function pepselect_child_defer_seo_template_scripts() {
	if ( is_admin() || ! pepselect_child_is_seo_performance_template() ) {
		return;
	}

	$deferred_handles = array(
		'jquery-blockui',
		'js-cookie',
		'underscore',
		'wp-util',
		'woocommerce',
		'hello-theme-frontend',
		'sourcebuster-js',
		'wc-order-attribution',
		'pepselect-cart-recovery',
	);

	foreach ( $deferred_handles as $script_handle ) {
		if ( wp_script_is( $script_handle, 'registered' ) ) {
			wp_script_add_data( $script_handle, 'strategy', 'defer' );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'pepselect_child_defer_seo_template_scripts', 999 );
add_action( 'wp_print_scripts', 'pepselect_child_defer_seo_template_scripts', 995 );

/**
 * Return stylesheet handles verified unused on the Quality Archive.
 *
 * @return string[]
 */
function pepselect_child_testing_unused_style_handles() {
	return array(
		'ywpar-blocks-style',
		'ywpar-date-picker-style',
		'yith-plugin-fw-icon-font',
		'ywpar_frontend',
		'ywdpd_owl',
		'yith_ywdpd_frontend',
		'cwginstock_frontend_css',
		'cwginstock_bootstrap',
		'select2',
		'woocommerce-general',
		'woocommerce-layout',
		'woocommerce-smallscreen',
		'pepselect-child-bisn-form',
	);
}

/**
 * Open the Google Fonts connections before the combined stylesheet is needed.
 *
 * @param array  $urls          Existing resource hints.
 * @param string $relation_type Hint relation type.
 * @return array
 */
function pepselect_child_font_resource_hints( $urls, $relation_type ) {
	if ( is_admin() || ! pepselect_child_is_seo_performance_template() || 'preconnect' !== $relation_type ) {
		return $urls;
	}

	$urls[] = 'https://fonts.googleapis.com';
	$urls[] = array(
		'href'        => 'https://fonts.gstatic.com',
		'crossorigin' => 'anonymous',
	);

	return $urls;
}
add_filter( 'wp_resource_hints', 'pepselect_child_font_resource_hints', 10, 2 );

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
	$deregister_styles = array();

	// The Quality Archive has no pricing, rewards, stock-notification, or
	// Select2 interface. Keep those assets on commerce templates, where their
	// components and behavior remain available.
	if ( pepselect_child_is_testing_template() ) {
		$deregister_styles = pepselect_child_testing_unused_style_handles();
		$unused_styles = array_merge( $unused_styles, $deregister_styles );
	}

	foreach ( $unused_styles as $style_handle ) {
		wp_dequeue_style( $style_handle );
	}

	// Some commerce styles are dependencies of plugin bundles and WordPress can
	// add them back while resolving that dependency tree. Deregister only the
	// handles verified unused on the non-commerce Quality Archive template.
	foreach ( $deregister_styles as $style_handle ) {
		wp_deregister_style( $style_handle );
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
// Elementor and several commerce plugins enqueue styles after
// wp_enqueue_scripts. Run immediately before WordPress prints the stylesheet
// queue so those late registrations are visible and can be handled reliably.
add_action( 'wp_print_styles', 'pepselect_child_optimize_seo_template_assets', 997 );

/**
 * Suppress late-printed plugin styles on the Quality Archive.
 *
 * Several commerce plugins enqueue these handles after the main stylesheet
 * queue has already been processed. Filtering the final tag keeps the removal
 * narrowly scoped to the verified handles and route.
 *
 * @param string $html   Stylesheet tag.
 * @param string $handle Registered style handle.
 * @return string
 */
function pepselect_child_filter_testing_unused_style_tags( $html, $handle ) {
	if ( is_admin() || ! pepselect_child_is_testing_template() ) {
		return $html;
	}

	if ( in_array( $handle, pepselect_child_testing_unused_style_handles(), true ) ) {
		return '';
	}

	return $html;
}
add_filter( 'style_loader_tag', 'pepselect_child_filter_testing_unused_style_tags', 20, 2 );

/**
 * Replace Elementor's four separate Google Fonts stylesheets with one request.
 *
 * The combined URL preserves every family, weight, and italic variant already
 * requested by Elementor. This is request consolidation, not a typography
 * change.
 *
 * @return void
 */
function pepselect_child_consolidate_google_fonts() {
	if ( is_admin() || ! pepselect_child_is_seo_performance_template() ) {
		return;
	}

	$elementor_font_handles = array(
		'elementor-gf-roboto',
		'elementor-gf-robotoslab',
		'elementor-gf-plusjakartasans',
		'elementor-gf-ibmplexmono',
	);

	$has_elementor_fonts = false;

	foreach ( $elementor_font_handles as $style_handle ) {
		if ( wp_style_is( $style_handle, 'enqueued' ) ) {
			$has_elementor_fonts = true;
		}

		wp_dequeue_style( $style_handle );
		wp_deregister_style( $style_handle );
	}

	if ( ! $has_elementor_fonts ) {
		return;
	}

	$variants = '100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic';
	$families = array(
		'Roboto:' . $variants,
		'Roboto Slab:' . $variants,
		'Plus Jakarta Sans:' . $variants,
		'IBM Plex Mono:' . $variants,
	);
	$font_url = add_query_arg(
		array(
			'family'  => implode( '|', $families ),
			'display' => 'swap',
		),
		'https://fonts.googleapis.com/css'
	);

	wp_enqueue_style( 'pepselect-google-fonts', $font_url, array(), null );
}
add_action( 'wp_print_styles', 'pepselect_child_consolidate_google_fonts', 998 );

/**
 * Inline the small Pep Select shell styles on the four audited templates.
 *
 * The same source files and cascade order are preserved. Larger route-specific
 * styles remain cacheable files, while six tiny request round trips are removed
 * from the critical render path.
 *
 * @return void
 */
function pepselect_child_inline_shell_styles() {
	if ( is_admin() || ! pepselect_child_is_seo_performance_template() ) {
		return;
	}

	$style_sources = array(
		'pepselect-child-foundations'      => 'assets/css/foundations.css',
		'pepselect-child-bisn-form'        => 'assets/css/bisn-form.css',
		'pepselect-child-header-preview'   => 'assets/css/header.css',
		'pepselect-child-footer-preview'   => 'assets/css/footer.css',
		'pepselect-child-side-cart-upsell' => 'assets/css/side-cart-upsell.css',
	);
	$combined_css  = '';

	foreach ( $style_sources as $style_handle => $relative_path ) {
		if ( ! wp_style_is( $style_handle, 'enqueued' ) ) {
			continue;
		}

		$absolute_path = get_stylesheet_directory() . '/' . $relative_path;

		if ( ! is_readable( $absolute_path ) ) {
			// Keep the external file when its source cannot be read safely.
			continue;
		}

		$stylesheet = file_get_contents( $absolute_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

		if ( false === $stylesheet || '' === trim( $stylesheet ) ) {
			continue;
		}

		$combined_css .= "\n/* " . $style_handle . " */\n" . $stylesheet;
		wp_dequeue_style( $style_handle );
		wp_deregister_style( $style_handle );
	}

	if ( '' === $combined_css ) {
		return;
	}

	// style.css contains theme metadata only, so its request can become the
	// dependency anchor for the inlined foundations and shell rules.
	wp_dequeue_style( 'pepselect-child' );
	wp_deregister_style( 'pepselect-child' );
	wp_register_style( 'pepselect-child', false, array( 'hello-elementor' ), null );
	wp_enqueue_style( 'pepselect-child' );

	wp_register_style( 'pepselect-child-foundations', false, array( 'pepselect-child' ), null );
	wp_enqueue_style( 'pepselect-child-foundations' );
	wp_add_inline_style( 'pepselect-child-foundations', $combined_css );
}
add_action( 'wp_print_styles', 'pepselect_child_inline_shell_styles', 999 );

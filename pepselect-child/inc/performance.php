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
 * Prevent Site Kit from rendering the unused AdSense advertising tag.
 *
 * Pep Select does not run publisher advertising. Keep Site Kit Analytics and
 * Search Console connected; these filters target only the AdSense module.
 *
 * @return bool
 */
function pepselect_child_block_unused_adsense_tag() {
	return true;
}
add_filter( 'googlesitekit_adsense_tag_blocked', 'pepselect_child_block_unused_adsense_tag' );
add_filter( 'googlesitekit_adsense_tag_amp_blocked', 'pepselect_child_block_unused_adsense_tag' );

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
 * Return whether the current request is a coded PageSpeed template.
 *
 * @return bool
 */
function pepselect_child_is_seo_performance_template() {
	return is_front_page()
		|| is_home()
		|| ( function_exists( 'is_shop' ) && is_shop() )
		|| ( function_exists( 'is_product' ) && is_product() )
		|| pepselect_child_is_testing_template()
		|| is_page( 'about-us' );
}

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
 * Replace the side-cart confetti bundle with a tiny on-demand loader.
 *
 * The premium side cart declares its 127 KiB celebration library as a hard
 * dependency even though it is only used after a progress-bar checkpoint is
 * reached. Keep the registered handle and dependency order intact, but defer
 * downloading and parsing the vendor bundle until its async create() method is
 * actually called.
 *
 * @return void
 */
function pepselect_child_lazy_load_side_cart_confetti() {
	static $configured = false;

	if ( $configured || is_admin() || ! pepselect_child_is_seo_performance_template() ) {
		return;
	}

	global $wp_scripts;

	if ( ! $wp_scripts instanceof WP_Scripts || ! isset( $wp_scripts->registered['xoo-confetti'] ) ) {
		return;
	}

	$script          = $wp_scripts->registered['xoo-confetti'];
	$original_source = (string) $script->src;

	// Only replace the verified Side Cart WooCommerce dependency. If the plugin
	// changes ownership or registration, retain its original loading behavior.
	if ( false === strpos( $original_source, '/woocommerce-side-cart-premium/assets/library/confetti/' ) ) {
		return;
	}

	if ( 0 === strpos( $original_source, '//' ) ) {
		$original_source = ( is_ssl() ? 'https:' : 'http:' ) . $original_source;
	} elseif ( 0 === strpos( $original_source, '/' ) ) {
		$original_source = home_url( $original_source );
	}

	if ( ! wp_http_validate_url( $original_source ) ) {
		return;
	}

	$script->src = get_stylesheet_directory_uri() . '/assets/js/confetti-loader.js';
	$script->ver = pepselect_child_asset_version( 'assets/js/confetti-loader.js' );

	wp_add_inline_script(
		'xoo-confetti',
		'window.pepselectConfettiSource = ' . wp_json_encode( $original_source ) . ';',
		'before'
	);
	wp_script_add_data( 'xoo-confetti', 'strategy', 'defer' );

	$configured = true;
}
add_action( 'wp_enqueue_scripts', 'pepselect_child_lazy_load_side_cart_confetti', 1000 );
add_action( 'wp_print_footer_scripts', 'pepselect_child_lazy_load_side_cart_confetti', 0 );

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
 * This runs after plugin enqueue callbacks. WooCommerce product, cart,
 * checkout, rewards, pricing, side-cart, and back-in-stock assets remain
 * untouched.
 *
 * @return void
 */
function pepselect_child_optimize_seo_template_assets() {
	if ( is_admin() || ! pepselect_child_is_seo_performance_template() ) {
		return;
	}

	$unused_styles = array(
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

}
// Several commerce plugins enqueue styles after wp_enqueue_scripts. Run
// immediately before WordPress prints the stylesheet queue so those late
// registrations are visible and can be handled reliably.
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

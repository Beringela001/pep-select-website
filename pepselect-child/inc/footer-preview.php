<?php
/**
 * Administrator-only coded footer preview.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Determine whether the current front-end request may show the coded footer.
 *
 * @return bool
 */
function pepselect_child_is_footer_preview_request() {
	return pepselect_child_preview_flag_is_set( 'pepselect_footer_preview' ) || pepselect_child_preview_flag_is_set( 'pepselect_shell_preview' );
}

/**
 * Register footer-preview hooks without changing ordinary front-end requests.
 *
 * @return void
 */
function pepselect_child_register_footer_preview() {
	add_action( 'template_redirect', 'pepselect_child_footer_preview_no_cache' );
	add_filter( 'body_class', 'pepselect_child_footer_preview_body_class' );
	add_filter( 'elementor/theme/get_location_templates/template_id', 'pepselect_child_suppress_elementor_footer_preview', 10, 2 );
	add_action( 'wp_enqueue_scripts', 'pepselect_child_enqueue_footer_preview_assets', 30 );
	add_action( 'wp_footer', 'pepselect_child_render_footer_preview', 5 );
}

/**
 * Suppress Footer #391 through Elementor's documented location filter.
 *
 * The Hello Elementor fallback footer is hidden by preview-only CSS. Header
 * and page-content locations remain untouched unless shell preview is active.
 *
 * @param int    $template_id Elementor Theme Builder template ID.
 * @param string $location    Elementor Theme Builder location when available.
 * @return int
 */
function pepselect_child_suppress_elementor_footer_preview( $template_id, $location = '' ) {
	if ( pepselect_child_is_footer_preview_request() && ( 'footer' === $location || 391 === absint( $template_id ) ) ) {
		return 0;
	}

	return $template_id;
}

/**
 * Prevent an administrator preview response from being stored in page cache.
 *
 * @return void
 */
function pepselect_child_footer_preview_no_cache() {
	if ( pepselect_child_is_footer_preview_request() ) {
		nocache_headers();
	}
}

/**
 * Add the body class that scopes footer-preview presentation.
 *
 * @param string[] $classes Existing body classes.
 * @return string[]
 */
function pepselect_child_footer_preview_body_class( $classes ) {
	if ( pepselect_child_is_footer_preview_request() ) {
		$classes[] = 'pepselect-footer-preview';
	}

	return $classes;
}

/**
 * Load coded-footer styles only for an authorized preview request.
 *
 * @return void
 */
function pepselect_child_enqueue_footer_preview_assets() {
	if ( ! pepselect_child_is_footer_preview_request() ) {
		return;
	}

	wp_enqueue_style(
		'pepselect-child-footer-preview',
		get_stylesheet_directory_uri() . '/assets/css/footer.css',
		array( 'pepselect-child-foundations' ),
		pepselect_child_asset_version( 'assets/css/footer.css' )
	);
}

/**
 * Render the coded footer before WordPress prints normal footer scripts.
 *
 * @return void
 */
function pepselect_child_render_footer_preview() {
	if ( pepselect_child_is_footer_preview_request() ) {
		get_template_part( 'template-parts/footer/site-footer' );
	}
}

/**
 * Return the configured footer logo as WordPress attachment markup.
 *
 * The Custom Logo setting takes precedence. Footer #391's confirmed Media
 * Library attachment remains the environment-neutral fallback.
 *
 * @return string
 */
function pepselect_child_get_footer_logo_html() {
	$custom_logo_id           = absint( get_theme_mod( 'custom_logo' ) );
	$elementor_footer_logo_id = 687;
	$logo_id                  = wp_attachment_is_image( $custom_logo_id ) ? $custom_logo_id : $elementor_footer_logo_id;

	if ( ! wp_attachment_is_image( $logo_id ) ) {
		return '';
	}

	return (string) wp_get_attachment_image(
		$logo_id,
		'full',
		false,
		array(
			'alt'      => get_bloginfo( 'name' ),
			'class'    => 'pepselect-footer__logo-image',
			'decoding' => 'async',
			'loading'  => 'lazy',
		)
	);
}

/**
 * Return the supported WooCommerce orders destination when available.
 *
 * @return string
 */
function pepselect_child_get_orders_url() {
	if ( function_exists( 'wc_get_account_endpoint_url' ) ) {
		return (string) wc_get_account_endpoint_url( 'orders' );
	}

	return home_url( '/my-account/orders/' );
}

/**
 * Return the three footer navigation groups from current approved routes.
 *
 * @return array<string,array<int,array<string,string>>>
 */
function pepselect_child_get_footer_link_groups() {
	return array(
		__( 'Explore', 'pepselect-child' ) => array(
			array( 'label' => __( 'All products', 'pepselect-child' ), 'url' => pepselect_child_get_shop_url() ),
			array( 'label' => __( 'About us', 'pepselect-child' ), 'url' => pepselect_child_get_page_url( 'about-us' ) ),
			array( 'label' => __( 'FAQ', 'pepselect-child' ), 'url' => pepselect_child_get_page_url( 'faq' ) ),
		),
		__( 'Support', 'pepselect-child' ) => array(
			array( 'label' => __( 'Contact us', 'pepselect-child' ), 'url' => pepselect_child_get_page_url( 'contact' ) ),
			array( 'label' => __( 'Certificate of Analysis', 'pepselect-child' ), 'url' => home_url( '/testing/' ) ),
			array( 'label' => __( 'Track your order', 'pepselect-child' ), 'url' => pepselect_child_get_orders_url() ),
			array( 'label' => __( 'Military & First responder discount', 'pepselect-child' ), 'url' => pepselect_child_get_page_url( 'military-discount' ) ),
		),
		__( 'Legal', 'pepselect-child' ) => array(
			array( 'label' => __( 'Privacy Policy', 'pepselect-child' ), 'url' => pepselect_child_get_page_url( 'privacy-policy' ) ),
			array( 'label' => __( 'Terms and conditions', 'pepselect-child' ), 'url' => pepselect_child_get_page_url( 'terms-conditions' ) ),
			array( 'label' => __( 'RUO Disclaimer', 'pepselect-child' ), 'url' => pepselect_child_get_page_url( 'ruo-disclaimer' ) ),
			array( 'label' => __( 'Refund & Shipping Policy', 'pepselect-child' ), 'url' => pepselect_child_get_page_url( 'refund-shipping-policy' ) ),
		),
	);
}

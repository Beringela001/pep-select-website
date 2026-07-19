<?php
/**
 * Coded site-shell footer presentation.
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
	return pepselect_child_should_render_coded_shell();
}

/**
 * Register coded-footer hooks.
 *
 * @return void
 */
function pepselect_child_register_footer_preview() {
	add_filter( 'body_class', 'pepselect_child_footer_preview_body_class' );
	add_filter( 'elementor/theme/get_location_templates/template_id', 'pepselect_child_suppress_elementor_footer_preview', 10, 2 );
	add_action( 'wp_enqueue_scripts', 'pepselect_child_enqueue_footer_preview_assets', 30 );
	add_action( 'wp_footer', 'pepselect_child_render_footer_preview', 5 );
}

/**
 * Suppress Footer #391 through Elementor's documented location filter.
 *
 * The Hello Elementor fallback footer is hidden by scoped CSS. Elementor page
 * content remains untouched, and legacy/editor requests bypass suppression.
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
 * Add the body class that scopes coded-footer presentation.
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
 * Load coded-footer styles only when the coded shell owns the request.
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
 * Return the guest order-tracking URL.
 *
 * Prefers the published /track-your-order/ page carrying the WooCommerce
 * order-tracking shortcode, so guests can check an order with only an order
 * number and billing email. Falls back to the account orders endpoint until
 * that page exists, so the footer never links to a 404.
 *
 * @return string
 */
function pepselect_child_get_track_order_url() {
	$page = get_page_by_path( 'track-your-order' );

	if ( $page instanceof WP_Post && 'publish' === $page->post_status ) {
		return (string) get_permalink( $page );
	}

	return pepselect_child_get_orders_url();
}

/**
 * Return the three footer navigation groups from current approved routes.
 *
 * @return array<string,array<int,array<string,string>>>
 */
function pepselect_child_get_footer_link_groups() {
	return array(
		__( 'Support', 'pepselect-child' ) => array(
			array( 'label' => __( 'Contact us', 'pepselect-child' ), 'url' => pepselect_child_get_page_url( 'contact' ) ),
			array( 'label' => __( 'FAQ', 'pepselect-child' ), 'url' => pepselect_child_get_faq_url() ),
			array( 'label' => __( 'Certificate of Analysis', 'pepselect-child' ), 'url' => home_url( '/testing/' ) ),
			array( 'label' => __( 'Track your order', 'pepselect-child' ), 'url' => pepselect_child_get_track_order_url() ),
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

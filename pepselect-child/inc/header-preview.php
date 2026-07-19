<?php
/**
 * Coded site-shell request routing and header presentation.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Determine whether the current user may access a coded shell preview.
 *
 * @return bool
 */
function pepselect_child_user_can_preview_shell() {
	if ( is_admin() || ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
		return false;
	}

	return true;
}

/**
 * Determine whether an exact read-only preview flag is present.
 *
 * @param string $flag Query-string key.
 * @return bool
 */
function pepselect_child_preview_flag_is_set( $flag ) {
	if ( ! pepselect_child_user_can_preview_shell() ) {
		return false;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only preview flag; access is capability restricted.
	$preview_value = isset( $_GET[ $flag ] ) ? sanitize_text_field( wp_unslash( $_GET[ $flag ] ) ) : '';

	return '1' === $preview_value;
}

/**
 * Determine whether the request is an authenticated Elementor editor preview.
 *
 * @return bool
 */
function pepselect_child_is_elementor_editor_request() {
	if ( ! is_user_logged_in() || ! current_user_can( 'edit_posts' ) ) {
		return false;
	}

	if ( did_action( 'elementor/loaded' ) && class_exists( '\\Elementor\\Plugin' ) ) {
		$elementor = \Elementor\Plugin::$instance;

		if ( isset( $elementor->editor ) && is_object( $elementor->editor ) && method_exists( $elementor->editor, 'is_edit_mode' ) && $elementor->editor->is_edit_mode() ) {
			return true;
		}
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only editor routing check; capability is required above.
	$preview_id = isset( $_GET['elementor-preview'] ) ? absint( wp_unslash( $_GET['elementor-preview'] ) ) : 0;

	return 0 < $preview_id;
}

/**
 * Determine whether the current request supports the coded front-end shell.
 *
 * @return bool
 */
function pepselect_child_is_supported_frontend_shell_request() {
	if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
		return false;
	}

	if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
		return false;
	}

	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		return false;
	}

	if ( ( isset( $GLOBALS['pagenow'] ) && 'wp-login.php' === $GLOBALS['pagenow'] ) || is_feed() ) {
		return false;
	}

	if ( pepselect_child_is_elementor_editor_request() ) {
		return false;
	}

	return true;
}

/**
 * Determine whether an administrator requested the preserved Elementor shell.
 *
 * @return bool
 */
function pepselect_child_is_legacy_shell_request() {
	return pepselect_child_is_supported_frontend_shell_request() && pepselect_child_preview_flag_is_set( 'pepselect_legacy_shell' );
}

/**
 * Determine whether the coded header and footer should own this request.
 *
 * @return bool
 */
function pepselect_child_should_render_coded_shell() {
	return pepselect_child_is_supported_frontend_shell_request() && ! pepselect_child_is_legacy_shell_request();
}

/**
 * Determine whether an authorized explicit shell-control flag is present.
 *
 * @return bool
 */
function pepselect_child_is_explicit_shell_control_request() {
	return pepselect_child_preview_flag_is_set( 'pepselect_header_preview' )
		|| pepselect_child_preview_flag_is_set( 'pepselect_footer_preview' )
		|| pepselect_child_preview_flag_is_set( 'pepselect_shell_preview' )
		|| pepselect_child_is_legacy_shell_request();
}

/**
 * Determine whether the current front-end request may show the coded header.
 *
 * @return bool
 */
function pepselect_child_is_header_preview_request() {
	return pepselect_child_should_render_coded_shell();
}

/**
 * Register coded-header hooks.
 *
 * @return void
 */
function pepselect_child_register_header_preview() {
	add_action( 'template_redirect', 'pepselect_child_shell_control_no_cache' );
	add_filter( 'body_class', 'pepselect_child_header_preview_body_class' );
	add_filter( 'elementor/theme/get_location_templates/template_id', 'pepselect_child_suppress_elementor_header_preview', 10, 2 );
	add_action( 'wp_enqueue_scripts', 'pepselect_child_enqueue_header_preview_assets', 30 );
	add_action( 'wp_body_open', 'pepselect_child_render_header_preview', 5 );
}

/**
 * Suppress Header #1323 through Elementor's documented location filter.
 *
 * The Hello Elementor fallback header is hidden by scoped CSS. Elementor page
 * content remains untouched, and legacy/editor requests bypass suppression.
 *
 * @param int    $template_id Elementor Theme Builder template ID.
 * @param string $location    Elementor Theme Builder location when available.
 * @return int
 */
function pepselect_child_suppress_elementor_header_preview( $template_id, $location = '' ) {
	if ( pepselect_child_is_header_preview_request() && ( 'header' === $location || 1323 === absint( $template_id ) ) ) {
		return 0;
	}

	return $template_id;
}

/**
 * Prevent explicit administrator shell-control responses from being cached.
 *
 * @return void
 */
function pepselect_child_shell_control_no_cache() {
	if ( pepselect_child_is_explicit_shell_control_request() ) {
		nocache_headers();
	}
}

/**
 * Add the body class used to hide the Elementor or parent-theme header.
 *
 * @param string[] $classes Existing body classes.
 * @return string[]
 */
function pepselect_child_header_preview_body_class( $classes ) {
	if ( pepselect_child_is_header_preview_request() ) {
		$classes[] = 'pepselect-header-preview';
	}

	return $classes;
}

/**
 * Load coded-header assets only when the coded shell owns the request.
 *
 * @return void
 */
function pepselect_child_enqueue_header_preview_assets() {
	if ( ! pepselect_child_is_header_preview_request() ) {
		return;
	}

	wp_enqueue_style(
		'pepselect-child-bisn-form',
		get_stylesheet_directory_uri() . '/assets/css/bisn-form.css',
		array( 'pepselect-child-foundations' ),
		pepselect_child_asset_version( 'assets/css/bisn-form.css' )
	);

	wp_enqueue_style(
		'pepselect-child-header-preview',
		get_stylesheet_directory_uri() . '/assets/css/header.css',
		array( 'pepselect-child-foundations' ),
		pepselect_child_asset_version( 'assets/css/header.css' )
	);

	wp_enqueue_script(
		'pepselect-child-header-search',
		get_stylesheet_directory_uri() . '/assets/js/header-search.js',
		array(),
		pepselect_child_asset_version( 'assets/js/header-search.js' ),
		true
	);

	wp_enqueue_script(
		'pepselect-child-header-preview',
		get_stylesheet_directory_uri() . '/assets/js/header.js',
		array(),
		pepselect_child_asset_version( 'assets/js/header.js' ),
		true
	);

	wp_script_add_data( 'pepselect-child-header-preview', 'strategy', 'defer' );
}

/**
 * Render the coded header before the normal page content.
 *
 * @return void
 */
function pepselect_child_render_header_preview() {
	if ( pepselect_child_is_header_preview_request() ) {
		get_template_part( 'template-parts/header/site-header' );
	}
}

/**
 * Return the configured Pep Select logo as WordPress attachment markup.
 *
 * The Custom Logo setting takes precedence. Header #1323's confirmed Media
 * Library attachment remains the environment-neutral fallback while the
 * Custom Logo setting is empty.
 *
 * @return string
 */
function pepselect_child_get_header_logo_html() {
	$custom_logo_id           = absint( get_theme_mod( 'custom_logo' ) );
	$elementor_header_logo_id = 595;
	$logo_id                  = wp_attachment_is_image( $custom_logo_id ) ? $custom_logo_id : $elementor_header_logo_id;

	if ( ! wp_attachment_is_image( $logo_id ) ) {
		return '';
	}

	return (string) wp_get_attachment_image(
		$logo_id,
		'full',
		false,
		array(
			'alt'           => get_bloginfo( 'name' ),
			'class'         => 'pepselect-header__logo-image',
			'decoding'      => 'async',
			'fetchpriority' => 'high',
			'loading'       => 'eager',
		)
	);
}

/**
 * Return a published page URL, with an environment-neutral path fallback.
 *
 * @param string $slug Page slug.
 * @return string
 */
function pepselect_child_get_page_url( $slug ) {
	$page = get_page_by_path( $slug, OBJECT, 'page' );

	if ( $page instanceof WP_Post && 'publish' === $page->post_status ) {
		return (string) get_permalink( $page );
	}

	return home_url( '/' . trim( $slug, '/' ) . '/' );
}

/**
 * Return the canonical WooCommerce Shop URL when available.
 *
 * @return string
 */
function pepselect_child_get_shop_url() {
	if ( function_exists( 'wc_get_page_permalink' ) ) {
		$shop_url = wc_get_page_permalink( 'shop' );

		if ( $shop_url ) {
			return (string) $shop_url;
		}
	}

	$archive_url = get_post_type_archive_link( 'product' );

	return $archive_url ? (string) $archive_url : pepselect_child_get_page_url( 'shop' );
}

/**
 * Return the canonical WooCommerce My Account URL when available.
 *
 * @return string
 */
function pepselect_child_get_account_url() {
	if ( function_exists( 'wc_get_page_permalink' ) ) {
		$account_url = wc_get_page_permalink( 'myaccount' );

		if ( $account_url ) {
			return (string) $account_url;
		}
	}

	return wp_login_url();
}

/**
 * Return the existing rewards account destination without a fixed hostname.
 *
 * @return string
 */
function pepselect_child_get_rewards_url() {
	if ( function_exists( 'wc_get_account_endpoint_url' ) ) {
		return (string) wc_get_account_endpoint_url( 'cash-back' );
	}

	return pepselect_child_get_account_url();
}

/**
 * Return the canonical WooCommerce Cart URL when available.
 *
 * @return string
 */
function pepselect_child_get_cart_url() {
	if ( function_exists( 'wc_get_cart_url' ) ) {
		return (string) wc_get_cart_url();
	}

	return pepselect_child_get_page_url( 'cart' );
}

/**
 * Return the current WooCommerce cart item count without recalculating it.
 *
 * @return int
 */
function pepselect_child_get_cart_count() {
	if ( function_exists( 'WC' ) && WC()->cart ) {
		return absint( WC()->cart->get_cart_contents_count() );
	}

	return 0;
}

/**
 * Return the established YITH rewards output when its shortcode is registered.
 *
 * @return string
 */
function pepselect_child_get_rewards_output() {
	if ( ! is_user_logged_in() ) {
		return '';
	}

	// Prefer the dollar cash-back display (YITH points x $0.01). This shows the
	// amount redeemable, e.g. "$4.88", instead of an abstract points count.
	if ( function_exists( 'pepselect_child_get_cashback_display' ) ) {
		$cashback = pepselect_child_get_cashback_display();

		return '<span class="pepselect-header__rewards-amount">' . esc_html( $cashback['balance_formatted'] ) . '</span>';
	}

	// Fallback to the YITH shortcode only if the helper is unavailable.
	if ( shortcode_exists( 'yith_ywpar_points' ) ) {
		$rewards_output = trim( do_shortcode( '[yith_ywpar_points label="" show_worth="no"]' ) );

		return '' === wp_strip_all_tags( $rewards_output ) ? '' : $rewards_output;
	}

	return '';
}

/**
 * Normalize a WordPress menu URL only when it belongs to this site.
 *
 * @param string $url Candidate URL.
 * @return string
 */
function pepselect_child_normalize_internal_menu_url( $url ) {
	$url = trim( $url );

	if ( '' === $url ) {
		return '';
	}

	$url_host  = wp_parse_url( $url, PHP_URL_HOST );
	$home_host = wp_parse_url( home_url( '/' ), PHP_URL_HOST );

	if ( empty( $url_host ) && 0 === strpos( $url, '/' ) ) {
		return home_url( $url );
	}

	if ( $url_host && $home_host && 0 === strcasecmp( $url_host, $home_host ) ) {
		return $url;
	}

	return '';
}

/**
 * Return the five approved navigation definitions and controlled fallbacks.
 *
 * @return array<string,array<string,mixed>>
 */
function pepselect_child_get_navigation_definitions() {
	return array(
		'home'      => array(
			'label'    => __( 'Home', 'pepselect-child' ),
			'aliases'  => array( 'home' ),
			'fallback' => home_url( '/' ),
		),
		'compounds' => array(
			'label'    => __( 'Compounds', 'pepselect-child' ),
			'aliases'  => array( 'compounds', 'shop' ),
			'fallback' => pepselect_child_get_shop_url(),
		),
		'coas'      => array(
			'label'    => __( 'COAs', 'pepselect-child' ),
			'aliases'  => array( 'coas', 'coa-archive', 'testing' ),
			'fallback' => home_url( '/testing/' ),
		),
		'faq'       => array(
			'label'    => __( 'FAQ', 'pepselect-child' ),
			'aliases'  => array( 'faq', 'frequently-asked-questions' ),
			'fallback' => pepselect_child_get_page_url( 'faq' ),
		),
		'contact'   => array(
			'label'    => __( 'Contact', 'pepselect-child' ),
			'aliases'  => array( 'contact', 'contact-us' ),
			'fallback' => pepselect_child_get_page_url( 'contact' ),
		),
	);
}

/**
 * Return candidate menu IDs, prioritizing the menu identifier in Header #1323.
 *
 * @return int[]
 */
function pepselect_child_get_navigation_menu_candidates() {
	$menu_ids       = array();
	$confirmed_menu = wp_get_nav_menu_object( 'new' );

	if ( $confirmed_menu instanceof WP_Term ) {
		$menu_ids[] = (int) $confirmed_menu->term_id;
	}

	foreach ( get_nav_menu_locations() as $menu_id ) {
		$menu_ids[] = (int) $menu_id;
	}

	foreach ( wp_get_nav_menus() as $menu ) {
		$menu_ids[] = (int) $menu->term_id;
	}

	return array_values( array_unique( array_filter( $menu_ids ) ) );
}

/**
 * Find the existing WordPress menu that resolves the most approved links.
 *
 * @param array<string,array<string,mixed>> $definitions Navigation definitions.
 * @return array<string,WP_Post>
 */
function pepselect_child_find_navigation_menu_matches( $definitions ) {
	$best_matches = array();

	foreach ( pepselect_child_get_navigation_menu_candidates() as $menu_id ) {
		$matches    = array();
		$menu_items = wp_get_nav_menu_items( $menu_id );

		if ( ! is_array( $menu_items ) ) {
			continue;
		}

		foreach ( $menu_items as $menu_item ) {
			if ( ! empty( $menu_item->menu_item_parent ) ) {
				continue;
			}

			$item_key = sanitize_title( wp_strip_all_tags( $menu_item->title ) );

			foreach ( $definitions as $definition_key => $definition ) {
				if ( ! isset( $matches[ $definition_key ] ) && in_array( $item_key, $definition['aliases'], true ) ) {
					$matches[ $definition_key ] = $menu_item;
				}
			}
		}

		if ( count( $matches ) > count( $best_matches ) ) {
			$best_matches = $matches;
		}
	}

	return $best_matches;
}

/**
 * Determine whether a navigation URL represents the current front-end route.
 *
 * @param string  $url          Navigation URL.
 * @param WP_Post $matched_item Optional matched WordPress menu item.
 * @return bool
 */
function pepselect_child_navigation_item_is_current( $url, $matched_item = null ) {
	if ( $matched_item instanceof WP_Post && ! empty( $matched_item->classes ) ) {
		$current_classes = array( 'current-menu-item', 'current_page_item', 'current-menu-ancestor' );

		if ( array_intersect( $current_classes, (array) $matched_item->classes ) ) {
			return true;
		}
	}

	$target_path  = untrailingslashit( (string) wp_parse_url( $url, PHP_URL_PATH ) );
	$request_path = isset( $GLOBALS['wp']->request ) ? '/' . trim( $GLOBALS['wp']->request, '/' ) : '';
	$request_path = untrailingslashit( $request_path );

	if ( '' === $target_path ) {
		return is_front_page();
	}

	return $target_path === $request_path;
}

/**
 * Return exactly five navigation items, using safe menu URLs where available.
 *
 * @return array<int,array<string,mixed>>
 */
function pepselect_child_get_header_navigation_items() {
	$definitions = pepselect_child_get_navigation_definitions();
	$matches     = pepselect_child_find_navigation_menu_matches( $definitions );
	$items       = array();

	foreach ( $definitions as $key => $definition ) {
		$matched_item = isset( $matches[ $key ] ) ? $matches[ $key ] : null;
		$menu_url     = $matched_item instanceof WP_Post ? pepselect_child_normalize_internal_menu_url( $matched_item->url ) : '';

		// The canonical COA Archive route always overrides obsolete menu destinations.
		$url = 'coas' === $key || '' === $menu_url ? $definition['fallback'] : $menu_url;

		$current_source = 'coas' === $key ? null : $matched_item;

		$items[] = array(
			'label'   => $definition['label'],
			'url'     => $url,
			'current' => pepselect_child_navigation_item_is_current( $url, $current_source ),
		);
	}

	return $items;
}

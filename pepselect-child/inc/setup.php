<?php
/**
 * Theme setup and front-end asset loading.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_stylesheet_directory() . '/inc/header-preview.php';
require_once get_stylesheet_directory() . '/inc/footer-preview.php';

/**
 * Determine whether the required Hello Elementor parent theme is available.
 *
 * WordPress normally prevents activation of a child theme without its parent.
 * This additional guard avoids loading child assets if the parent disappears
 * after activation.
 *
 * @return bool
 */
function pepselect_child_parent_is_available() {
	$parent_theme = wp_get_theme( 'hello-elementor' );

	return $parent_theme->exists() && ! $parent_theme->errors();
}

/**
 * Return a cache-busting version for a local theme asset.
 *
 * The theme version is used only if the file timestamp cannot be read.
 *
 * @param string $relative_path File path relative to the child-theme root.
 * @return string
 */
function pepselect_child_asset_version( $relative_path ) {
	$asset_path    = get_stylesheet_directory() . '/' . ltrim( $relative_path, '/\\' );
	$file_modified = is_readable( $asset_path ) ? filemtime( $asset_path ) : false;

	if ( false !== $file_modified ) {
		return (string) $file_modified;
	}

	return (string) wp_get_theme()->get( 'Version' );
}

/**
 * Display a safe administrative notice if the required parent is unavailable.
 *
 * No administrative assets are loaded by the child theme.
 *
 * @return void
 */
function pepselect_child_missing_parent_notice() {
	?>
	<div class="notice notice-error">
		<p>
			<?php esc_html_e( 'Pep Select requires the Hello Elementor parent theme. Install Hello Elementor or switch to another available theme.', 'pepselect-child' ); ?>
		</p>
	</div>
	<?php
}

/**
 * Enqueue the parent theme and Pep Select foundation styles on the front end.
 *
 * @return void
 */
function pepselect_child_enqueue_styles() {
	$parent_theme = wp_get_theme( 'hello-elementor' );

	wp_enqueue_style(
		'hello-elementor',
		get_template_directory_uri() . '/style.css',
		array(),
		(string) $parent_theme->get( 'Version' )
	);

	wp_enqueue_style(
		'pepselect-child',
		get_stylesheet_uri(),
		array( 'hello-elementor' ),
		pepselect_child_asset_version( 'style.css' )
	);

	wp_enqueue_style(
		'pepselect-child-foundations',
		get_stylesheet_directory_uri() . '/assets/css/foundations.css',
		array( 'pepselect-child' ),
		pepselect_child_asset_version( 'assets/css/foundations.css' )
	);
}

/**
 * Register the child theme's foundation hooks.
 *
 * @return void
 */
function pepselect_child_bootstrap() {
	if ( ! pepselect_child_parent_is_available() ) {
		add_action( 'admin_notices', 'pepselect_child_missing_parent_notice' );
		return;
	}

	add_action( 'wp_enqueue_scripts', 'pepselect_child_enqueue_styles', 20 );
	pepselect_child_register_header_preview();
	pepselect_child_register_footer_preview();
}

<?php
/**
 * Homepage hero framing controls for the WordPress Customizer.
 *
 * Provides an image picker and live-preview sliders for fit, focal point,
 * and zoom. Values are stored as theme mods and rendered as
 * CSS custom properties, so templates stay untouched and the parent-theme
 * rollback remains clean.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the hero framing section, settings, and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 * @return void
 */
function pepselect_child_register_hero_customizer( $wp_customize ) {
	$wp_customize->add_section(
		'pepselect_hero',
		array(
			'title'       => __( 'Homepage Hero', 'pepselect-child' ),
			'description' => __( 'Choose the hero image and adjust how it sits inside its rounded frame. Changes preview live.', 'pepselect-child' ),
			'priority'    => 30,
		)
	);

	$wp_customize->add_setting(
		'pepselect_hero_image',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'pepselect_hero_image',
			array(
				'section'     => 'pepselect_hero',
				'label'       => __( 'Hero image', 'pepselect-child' ),
				'description' => __( 'Leave empty to use the approved default family image.', 'pepselect-child' ),
				'mime_type'   => 'image',
			)
		)
	);

	$wp_customize->add_setting(
		'pepselect_hero_fit',
		array(
			'default'           => 'cover',
			'sanitize_callback' => 'pepselect_child_sanitize_hero_fit',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'pepselect_hero_fit',
		array(
			'section' => 'pepselect_hero',
			'label'   => __( 'Image fit', 'pepselect-child' ),
			'type'    => 'select',
			'choices' => array(
				'cover'   => __( 'Fill the frame (may crop edges)', 'pepselect-child' ),
				'contain' => __( 'Show the whole image', 'pepselect-child' ),
			),
		)
	);

	$sliders = array(
		'pepselect_hero_pos_x'  => array(
			'label'       => __( 'Horizontal position', 'pepselect-child' ),
			'description' => __( '0 = left edge, 50 = centered, 100 = right edge.', 'pepselect-child' ),
			'default'     => 50,
			'min'         => 0,
			'max'         => 100,
		),
		'pepselect_hero_pos_y'  => array(
			'label'       => __( 'Vertical position', 'pepselect-child' ),
			'description' => __( '0 = top edge, 50 = centered, 100 = bottom edge.', 'pepselect-child' ),
			'default'     => 50,
			'min'         => 0,
			'max'         => 100,
		),
		'pepselect_hero_zoom'   => array(
			'label'       => __( 'Zoom', 'pepselect-child' ),
			'description' => __( '100 = normal size, 150 = zoomed in 50%.', 'pepselect-child' ),
			'default'     => 100,
			'min'         => 100,
			'max'         => 160,
		),
	);

	foreach ( $sliders as $setting_id => $slider ) {
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => $slider['default'],
				'sanitize_callback' => 'absint',
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			$setting_id,
			array(
				'section'     => 'pepselect_hero',
				'label'       => $slider['label'],
				'description' => $slider['description'],
				'type'        => 'range',
				'input_attrs' => array(
					'min'  => $slider['min'],
					'max'  => $slider['max'],
					'step' => 1,
				),
			)
		);
	}
}
add_action( 'customize_register', 'pepselect_child_register_hero_customizer' );

/**
 * Allow only the supported object-fit keywords.
 *
 * @param string $value Raw value.
 * @return string
 */
function pepselect_child_sanitize_hero_fit( $value ) {
	return in_array( $value, array( 'cover', 'contain' ), true ) ? $value : 'cover';
}

/**
 * Return the saved hero framing values within safe bounds.
 *
 * @return array{fit:string,pos_x:int,pos_y:int,zoom:int}
 */
function pepselect_child_get_hero_framing() {
	return array(
		'fit'    => pepselect_child_sanitize_hero_fit( get_theme_mod( 'pepselect_hero_fit', 'cover' ) ),
		'pos_x'  => max( 0, min( 100, absint( get_theme_mod( 'pepselect_hero_pos_x', 50 ) ) ) ),
		'pos_y'  => max( 0, min( 100, absint( get_theme_mod( 'pepselect_hero_pos_y', 50 ) ) ) ),
		'zoom'   => max( 100, min( 160, absint( get_theme_mod( 'pepselect_hero_zoom', 100 ) ) ) ),
	);
}

/**
 * Print the saved framing values as CSS custom properties on the front page.
 *
 * Attached to the homepage stylesheet so the variables load with it.
 *
 * @return void
 */
function pepselect_child_output_hero_framing_css() {
	if ( ! function_exists( 'pepselect_child_is_home_preview_request' ) || ! pepselect_child_is_home_preview_request() ) {
		return;
	}

	$framing = pepselect_child_get_hero_framing();
	$css     = sprintf(
		':root{--pep-hero-image-fit:%1$s;--pep-hero-image-position:%2$d%% %3$d%%;--pep-hero-image-zoom:%4$s;}',
		$framing['fit'],
		$framing['pos_x'],
		$framing['pos_y'],
		rtrim( rtrim( number_format( $framing['zoom'] / 100, 2, '.', '' ), '0' ), '.' )
	);

	wp_add_inline_style( 'pepselect-child-home-preview', $css );
}
add_action( 'wp_enqueue_scripts', 'pepselect_child_output_hero_framing_css', 50 );

/**
 * Enqueue the live-preview bridge inside the Customizer preview frame.
 *
 * @return void
 */
function pepselect_child_enqueue_hero_customizer_preview() {
	wp_enqueue_script(
		'pepselect-child-hero-customize-preview',
		get_stylesheet_directory_uri() . '/assets/js/customizer-hero-preview.js',
		array( 'customize-preview' ),
		pepselect_child_asset_version( 'assets/js/customizer-hero-preview.js' ),
		true
	);
}
add_action( 'customize_preview_init', 'pepselect_child_enqueue_hero_customizer_preview' );

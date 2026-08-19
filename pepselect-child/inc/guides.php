<?php
/**
 * Guide routing, assets, and schema ownership.
 *
 * Guide copy remains in WordPress post content. The child theme owns only the
 * shared presentation and the approved Organization author reference.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Determine whether the request is a published post in the Guides category.
 *
 * @return bool
 */
function pepselect_child_is_guide_request() {
	return is_singular( 'post' ) && has_category( 'guides' );
}

/**
 * Use the coded editorial template for guide posts only.
 *
 * @param string $template Resolved template path.
 * @return string
 */
function pepselect_child_guide_template( $template ) {
	if ( ! pepselect_child_is_guide_request() ) {
		return $template;
	}

	$guide_template = get_stylesheet_directory() . '/templates/single-guide.php';

	return file_exists( $guide_template ) ? $guide_template : $template;
}
add_filter( 'template_include', 'pepselect_child_guide_template', 99 );

/**
 * Load the guide stylesheet only where the guide template renders.
 *
 * @return void
 */
function pepselect_child_enqueue_guide_assets() {
	if ( ! pepselect_child_is_guide_request() ) {
		return;
	}

	wp_enqueue_style(
		'pepselect-child-guide',
		get_stylesheet_directory_uri() . '/assets/css/guide.css',
		array( 'pepselect-child-foundations' ),
		pepselect_child_asset_version( 'assets/css/guide.css' )
	);
}
add_action( 'wp_enqueue_scripts', 'pepselect_child_enqueue_guide_assets', 40 );

/**
 * Keep approved guide Articles connected to the existing Pep Select entity.
 *
 * No person, credential, or reviewer is invented. Yoast remains the graph
 * owner; this filter aligns only the Article author and publisher references.
 *
 * @param array $graph Yoast schema graph.
 * @return array
 */
function pepselect_child_filter_guide_schema( $graph ) {
	if ( ! pepselect_child_is_guide_request() ) {
		return $graph;
	}

	$organization_id = home_url( '/#organization' );

	foreach ( $graph as $index => $piece ) {
		$types = isset( $piece['@type'] ) ? (array) $piece['@type'] : array();

		if ( array_intersect( array( 'Article', 'BlogPosting' ), $types ) ) {
			$graph[ $index ]['author']    = array( '@id' => $organization_id );
			$graph[ $index ]['publisher'] = array( '@id' => $organization_id );
		}
	}

	return $graph;
}
add_filter( 'wpseo_schema_graph', 'pepselect_child_filter_guide_schema', 30 );

/**
 * Remove duplicate non-Yoast description tags from final guide HTML.
 *
 * The existing plugin stack emits the stored post excerpt as a second meta
 * description after Yoast has already printed the approved description. This
 * response-only cleanup keeps the visible guide excerpt intact and leaves
 * Yoast as the single metadata owner for guide posts.
 *
 * @param string $html Final response HTML.
 * @return string
 */
function pepselect_child_filter_guide_response_metadata( $html ) {
	$meta_pattern = '~<meta\b[^>]*>~i';
	$name_pattern = '~\bname\s*=\s*(["\'])description\1~i';
	$has_yoast    = false !== strpos( $html, 'This site is optimized with the Yoast SEO plugin' );

	if ( ! $has_yoast ) {
		return $html;
	}

	$description_count = 0;
	$filtered = preg_replace_callback(
		$meta_pattern,
		static function ( $matches ) use ( $name_pattern, &$description_count ) {
			$tag = $matches[0];

			if ( ! preg_match( $name_pattern, $tag ) ) {
				return $tag;
			}

			++$description_count;

			return 1 === $description_count ? $tag : '';
		},
		$html
	);

	return is_string( $filtered ) ? $filtered : $html;
}

/**
 * Start the narrowly scoped guide response metadata cleanup.
 *
 * @return void
 */
function pepselect_child_start_guide_response_metadata_cleanup() {
	if ( ! is_admin() && pepselect_child_is_guide_request() ) {
		ob_start( 'pepselect_child_filter_guide_response_metadata' );
	}
}
add_action( 'template_redirect', 'pepselect_child_start_guide_response_metadata_cleanup', 99 );

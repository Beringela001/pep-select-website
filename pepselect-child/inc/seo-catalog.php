<?php
/**
 * Catalog consolidation and structured-data corrections (SEO Milestone 3).
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Redirect the legacy Terms URL used by the research gate.
 *
 * The published legal page uses /terms-conditions/. Keeping this narrow
 * fallback prevents old gate markup, bookmarks, and external links from
 * sending visitors to a 404 while the gate's separate source is corrected.
 *
 * @return void
 */
function pepselect_child_redirect_legacy_terms_url() {
	if ( is_admin() || ! is_404() || empty( $_SERVER['REQUEST_URI'] ) ) {
		return;
	}

	$request_path = wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH );

	if ( '/terms-of-service' !== untrailingslashit( (string) $request_path ) ) {
		return;
	}

	wp_safe_redirect( home_url( '/terms-conditions/' ), 301, 'Pep Select' );
	exit;
}
add_action( 'template_redirect', 'pepselect_child_redirect_legacy_terms_url', 0 );

/**
 * Redirect the duplicate Research Compounds archive to the primary Shop URL.
 *
 * Query parameters are retained so campaign attribution, sorting, filters,
 * and supported direct add-to-cart links survive the consolidation.
 *
 * @return void
 */
function pepselect_child_redirect_research_compounds_archive() {
	if ( is_admin() || ! function_exists( 'is_product_category' ) || ! is_product_category( 'research-compounds' ) ) {
		return;
	}

	$target = function_exists( 'pepselect_child_get_shop_url' ) ? pepselect_child_get_shop_url() : home_url( '/shop/' );
	$args   = array();

	foreach ( wp_unslash( $_GET ) as $raw_key => $raw_value ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only redirect parameters.
		$key = sanitize_key( $raw_key );

		if ( '' === $key || in_array( $key, array( 'product_cat', 'paged', 'page' ), true ) ) {
			continue;
		}

		$args[ $key ] = map_deep( $raw_value, 'sanitize_text_field' );
	}

	if ( $args ) {
		$target = add_query_arg( $args, $target );
	}

	wp_safe_redirect( $target, 301, 'Pep Select' );
	exit;
}
add_action( 'template_redirect', 'pepselect_child_redirect_research_compounds_archive', 1 );

/**
 * Remove the redirected category term from Yoast XML sitemaps.
 *
 * @param int[] $excluded_term_ids Existing excluded term IDs.
 * @return int[]
 */
function pepselect_child_exclude_research_compounds_from_sitemap( $excluded_term_ids ) {
	$term = get_term_by( 'slug', 'research-compounds', 'product_cat' );

	if ( $term instanceof WP_Term ) {
		$excluded_term_ids[] = (int) $term->term_id;
	}

	return array_values( array_unique( array_map( 'absint', $excluded_term_ids ) ) );
}
add_filter( 'wpseo_exclude_from_sitemap_by_term_ids', 'pepselect_child_exclude_research_compounds_from_sitemap' );

/**
 * Remove the now-empty product-category sitemap from Yoast's sitemap index.
 *
 * The only public product category redirects to Shop, so publishing an empty
 * taxonomy sitemap adds no discovery value.
 *
 * @param bool   $excluded Whether the taxonomy is excluded.
 * @param string $taxonomy Taxonomy name.
 * @return bool
 */
function pepselect_child_exclude_product_category_sitemap( $excluded, $taxonomy ) {
	if ( 'product_cat' === $taxonomy ) {
		return true;
	}

	return $excluded;
}
add_filter( 'wpseo_sitemap_exclude_taxonomy', 'pepselect_child_exclude_product_category_sitemap', 20, 2 );

/**
 * Remove the standard post sitemap while it has no indexable published posts.
 *
 * The exclusion is deliberately data-aware: publishing an indexable post makes
 * the sitemap available again without requiring a theme release. A published
 * placeholder that Yoast explicitly marks noindex does not create an empty
 * sitemap entry.
 *
 * @param bool   $excluded Whether the post type is excluded.
 * @param string $post_type Post type name.
 * @return bool
 */
function pepselect_child_exclude_empty_post_sitemap( $excluded, $post_type ) {
	if ( 'post' !== $post_type ) {
		return $excluded;
	}

	$indexable_post_ids = get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- One-row sitemap-provider check.
				'relation' => 'OR',
				array(
					'key'     => '_yoast_wpseo_meta-robots-noindex',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => '_yoast_wpseo_meta-robots-noindex',
					'value'   => '1',
					'compare' => '!=',
				),
			),
		)
	);

	return empty( $indexable_post_ids );
}
add_filter( 'wpseo_sitemap_exclude_post_type', 'pepselect_child_exclude_empty_post_sitemap', 20, 2 );

/**
 * Keep the WooCommerce Shop archive in one sitemap only.
 *
 * Yoast includes the Shop archive in the product sitemap. Excluding the same
 * WordPress page from the page sitemap removes a duplicate discovery signal
 * without changing the Shop URL, template, canonical, or commerce behavior.
 *
 * @param int[] $excluded_post_ids Existing excluded post IDs.
 * @return int[]
 */
function pepselect_child_exclude_shop_page_from_page_sitemap( $excluded_post_ids ) {
	$shop_page_id = absint( get_option( 'woocommerce_shop_page_id' ) );

	if ( $shop_page_id ) {
		$excluded_post_ids[] = $shop_page_id;
	}

	return array_values( array_unique( array_map( 'absint', $excluded_post_ids ) ) );
}
add_filter( 'wpseo_exclude_from_sitemap_by_post_ids', 'pepselect_child_exclude_shop_page_from_page_sitemap', 20 );

/**
 * Give the homepage and Shop page clear, page-specific search titles.
 *
 * These filters change document and social metadata only. They do not alter
 * visible headings or landing-page copy.
 *
 * @param string $title Existing document or social title.
 * @return string
 */
function pepselect_child_filter_catalog_page_seo_title( $title ) {
	$site = get_bloginfo( 'name' ) ?: 'Pep Select';

	if ( is_front_page() ) {
		return sprintf( __( 'Research Peptides with Batch-Matched Lab Reports | %s', 'pepselect-child' ), $site );
	}

	if ( function_exists( 'is_shop' ) && is_shop() ) {
		return sprintf( __( 'Shop Research Peptides & Compounds | %s', 'pepselect-child' ), $site );
	}

	return $title;
}
add_filter( 'pre_get_document_title', 'pepselect_child_filter_catalog_page_seo_title', 20 );
add_filter( 'wpseo_title', 'pepselect_child_filter_catalog_page_seo_title', 20 );
add_filter( 'wpseo_opengraph_title', 'pepselect_child_filter_catalog_page_seo_title', 20 );
add_filter( 'wpseo_twitter_title', 'pepselect_child_filter_catalog_page_seo_title', 20 );

/**
 * Return the product identity customers can see: name plus strength when set.
 *
 * @param WC_Product $product Product instance.
 * @return string
 */
function pepselect_child_get_product_search_identity( $product ) {
	if ( ! is_a( $product, 'WC_Product' ) ) {
		return '';
	}

	$name     = trim( $product->get_name() );
	$strength = function_exists( 'pepselect_child_get_product_strength_label' ) ? pepselect_child_get_product_strength_label( $product ) : '';

	if ( '' !== $strength && false === stripos( preg_replace( '/\s+/', '', $name ), preg_replace( '/\s+/', '', $strength ) ) ) {
		$name .= ' ' . $strength;
	}

	return trim( $name );
}

/**
 * Return the product description that is actually visible in the coded page.
 *
 * @param WC_Product $product Product instance.
 * @return string
 */
function pepselect_child_get_visible_product_schema_description( $product ) {
	if ( ! is_a( $product, 'WC_Product' ) ) {
		return '';
	}

	$content     = function_exists( 'pepselect_child_get_compound_content' ) ? pepselect_child_get_compound_content( $product ) : null;
	$description = is_array( $content ) && ! empty( $content['description'] ) ? $content['description'] : $product->get_description();

	if ( '' === trim( (string) $description ) ) {
		return '';
	}

	$description = do_shortcode( (string) $description );
	$description = html_entity_decode( $description, ENT_QUOTES | ENT_HTML5, get_bloginfo( 'charset' ) );
	$description = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( $description ) ) );

	if ( '' === $description ) {
		return '';
	}

	return pepselect_child_get_product_search_identity( $product ) . '. ' . $description;
}

/**
 * Give same-compound strengths distinct, visible-fact search titles.
 *
 * @param string $title Yoast title.
 * @return string
 */
function pepselect_child_filter_product_seo_title( $title ) {
	if ( ! function_exists( 'is_product' ) || ! is_product() || ! function_exists( 'wc_get_product' ) ) {
		return $title;
	}

	$product  = wc_get_product( get_queried_object_id() );
	$identity = pepselect_child_get_product_search_identity( $product );

	$site = get_bloginfo( 'name' ) ?: 'Pep Select';

	return '' !== $identity ? sprintf( __( '%1$s for Research | %2$s', 'pepselect-child' ), $identity, $site ) : $title;
}
add_filter( 'pre_get_document_title', 'pepselect_child_filter_product_seo_title', 20 );
add_filter( 'wpseo_title', 'pepselect_child_filter_product_seo_title', 20 );
add_filter( 'wpseo_opengraph_title', 'pepselect_child_filter_product_seo_title', 20 );
add_filter( 'wpseo_twitter_title', 'pepselect_child_filter_product_seo_title', 20 );

/**
 * Replace generic product snippets with concise page-grounded descriptions.
 *
 * @param string $description Yoast meta description.
 * @return string
 */
function pepselect_child_filter_product_seo_description( $description ) {
	if ( ! function_exists( 'is_product' ) || ! is_product() || ! function_exists( 'wc_get_product' ) ) {
		return $description;
	}

	$product  = wc_get_product( get_queried_object_id() );
	$identity = pepselect_child_get_product_search_identity( $product );

	if ( '' === $identity ) {
		return $description;
	}

	return sprintf(
		/* translators: %s: visible product name and strength. */
		__( 'Review %s from Pep Select, including current price, availability, product details, and research-use information.', 'pepselect-child' ),
		$identity
	);
}
add_filter( 'wpseo_metadesc', 'pepselect_child_filter_product_seo_description', 20 );
add_filter( 'wpseo_opengraph_desc', 'pepselect_child_filter_product_seo_description', 20 );
add_filter( 'wpseo_twitter_description', 'pepselect_child_filter_product_seo_description', 20 );

/**
 * Suppress the obsolete generic product excerpt on single-product requests.
 *
 * The excerpt is already absent from the visible product summary, but a legacy
 * metadata callback can otherwise emit it as a second meta description.
 *
 * @param string       $excerpt Product excerpt.
 * @param WP_Post|null $post    Post object when provided by WordPress.
 * @return string
 */
function pepselect_child_suppress_generic_product_excerpt( $excerpt, $post = null ) {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return $excerpt;
	}

	if ( $post instanceof WP_Post && (int) $post->ID !== (int) get_queried_object_id() ) {
		return $excerpt;
	}

	$normalized = strtolower( trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( (string) $excerpt ) ) ) );

	return 'high-purity research peptide' === $normalized ? '' : $excerpt;
}
add_filter( 'get_the_excerpt', 'pepselect_child_suppress_generic_product_excerpt', 20, 2 );
add_filter( 'woocommerce_short_description', 'pepselect_child_suppress_generic_product_excerpt', 20, 2 );

/**
 * Remove the exact legacy duplicate description from final product HTML.
 *
 * A legacy callback reads the raw post excerpt directly and does not apply
 * WordPress or WooCommerce excerpt filters. Buffering only product
 * responses lets us remove that one obsolete tag without changing the stored
 * product excerpt or any visible product copy.
 *
 * @param string $html Final response HTML.
 * @return string
 */
function pepselect_child_filter_product_response_metadata( $html ) {
	$filtered = preg_replace(
		'~<meta\s+name=["\']description["\']\s+content=["\']High-purity research peptide["\']\s*/?>\s*~i',
		'',
		$html
	);

	return is_string( $filtered ) ? $filtered : $html;
}

/**
 * Start the narrowly scoped product response metadata cleanup.
 *
 * @return void
 */
function pepselect_child_start_product_response_metadata_cleanup() {
	if ( ! is_admin() && function_exists( 'is_product' ) && is_product() ) {
		ob_start( 'pepselect_child_filter_product_response_metadata' );
	}
}
add_action( 'template_redirect', 'pepselect_child_start_product_response_metadata_cleanup', 99 );

/**
 * Keep product canonical and social URLs on the current WooCommerce permalink.
 *
 * @param string $url Yoast URL value.
 * @return string
 */
function pepselect_child_filter_product_seo_url( $url ) {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return $url;
	}

	$product_url = get_permalink( get_queried_object_id() );

	return $product_url ? $product_url : $url;
}
add_filter( 'wpseo_canonical', 'pepselect_child_filter_product_seo_url', 20 );
add_filter( 'wpseo_opengraph_url', 'pepselect_child_filter_product_seo_url', 20 );

/**
 * Use the current WooCommerce product image for social previews.
 *
 * @param string $image_url Yoast image URL.
 * @return string
 */
function pepselect_child_filter_product_social_image( $image_url ) {
	if ( ! function_exists( 'is_product' ) || ! is_product() || ! function_exists( 'wc_get_product' ) ) {
		return $image_url;
	}

	$product = wc_get_product( get_queried_object_id() );
	$image   = is_a( $product, 'WC_Product' ) ? wp_get_attachment_image_url( $product->get_image_id(), 'full' ) : '';

	return $image ? set_url_scheme( $image, 'https' ) : $image_url;
}
add_filter( 'wpseo_opengraph_image', 'pepselect_child_filter_product_social_image', 20 );
add_filter( 'wpseo_twitter_image', 'pepselect_child_filter_product_social_image', 20 );

/**
 * Make WooCommerce Product markup mirror visible, approved product content.
 *
 * @param array      $markup  Product structured data.
 * @param WC_Product $product Product instance.
 * @return array
 */
function pepselect_child_filter_product_structured_data( $markup, $product ) {
	$description = pepselect_child_get_visible_product_schema_description( $product );

	if ( '' !== $description ) {
		$markup['description'] = $description;
	} else {
		unset( $markup['description'] );
	}

	$markup['brand'] = array(
		'@type' => 'Brand',
		'name'  => get_bloginfo( 'name' ),
	);

	return $markup;
}
add_filter( 'woocommerce_structured_data_product', 'pepselect_child_filter_product_structured_data', 20, 2 );

/**
 * Normalize WooCommerce's root JSON-LD context to Yoast's value.
 *
 * WooCommerce wraps its generated graph in this context after the individual
 * Product filter runs, so the root-context filter is the correct owner hook.
 *
 * @param array $context Structured-data context wrapper.
 * @return array
 */
function pepselect_child_filter_woocommerce_schema_context( $context ) {
	if ( is_array( $context ) ) {
		$context['@context'] = 'https://schema.org';
	}

	return $context;
}
add_filter( 'woocommerce_structured_data_context', 'pepselect_child_filter_woocommerce_schema_context', 20 );

/**
 * Return the stable identifier for Pep Select's published return policy.
 *
 * @return string
 */
function pepselect_child_get_merchant_return_policy_id() {
	return home_url( '/#merchant-return-policy' );
}

/**
 * Return the store-level policy backed by the published policy page.
 *
 * Pep Select does not accept returns after shipment. Pre-shipment
 * cancellations and remedies for damaged or incorrect orders remain governed
 * by the linked policy page and are not represented as merchandise returns.
 *
 * @return array
 */
function pepselect_child_get_merchant_return_policy_schema() {
	return array(
		'@type'                => 'MerchantReturnPolicy',
		'@id'                  => pepselect_child_get_merchant_return_policy_id(),
		'applicableCountry'    => 'US',
		'returnPolicyCategory' => 'https://schema.org/MerchantReturnNotPermitted',
		'merchantReturnLink'   => pepselect_child_get_page_url( 'refund-shipping-policy' ),
	);
}

/**
 * Remove WooCommerce's assumed year-end price-validity date.
 *
 * A real scheduled-sale end date remains intact. Ordinary catalog prices have
 * no promised expiry date in Pep Select's source data, so the generated
 * year-end date and matching UnitPriceSpecification value are omitted.
 *
 * @param array      $offer   Offer structured data.
 * @param WC_Product $product Product instance.
 * @return array
 */
function pepselect_child_filter_product_offer_structured_data( $offer, $product ) {
	if ( ! is_a( $product, 'WC_Product' ) ) {
		return $offer;
	}

	$offer['seller'] = array(
		'@type' => 'OnlineStore',
		'@id'   => home_url( '/#organization' ),
		'name'  => get_bloginfo( 'name' ),
		'url'   => home_url( '/' ),
	);

	$offer['hasMerchantReturnPolicy'] = array(
		'@id' => pepselect_child_get_merchant_return_policy_id(),
	);

	if ( $product->get_date_on_sale_to() ) {
		return $offer;
	}

	unset( $offer['priceValidUntil'] );
	if ( ! empty( $offer['priceSpecification'] ) && is_array( $offer['priceSpecification'] ) ) {
		foreach ( $offer['priceSpecification'] as $index => $specification ) {
			if ( is_array( $specification ) ) {
				unset( $offer['priceSpecification'][ $index ]['validThrough'] );
			}
		}
	}

	return $offer;
}
add_filter( 'woocommerce_structured_data_product_offer', 'pepselect_child_filter_product_offer_structured_data', 20, 2 );

/**
 * Rewrite stale Yoast product URLs to the current WooCommerce permalink.
 *
 * @param mixed  $value       Schema value.
 * @param string $product_url Current product permalink.
 * @return mixed
 */
function pepselect_child_rewrite_product_schema_urls( $value, $product_url ) {
	if ( is_array( $value ) ) {
		foreach ( $value as $key => $child ) {
			$value[ $key ] = pepselect_child_rewrite_product_schema_urls( $child, $product_url );
		}

		return $value;
	}

	if ( ! is_string( $value ) ) {
		return $value;
	}

	$product_base = trailingslashit( home_url( '/product/' ) );
	$pattern      = '~^' . preg_quote( $product_base, '~' ) . '[^/?#]+/?(?=$|#)~';
	$rewritten    = preg_replace( $pattern, trailingslashit( $product_url ), $value );

	return is_string( $rewritten ) ? $rewritten : $value;
}

/**
 * Add one truthful OnlineStore entity and repair current-product graph URLs.
 *
 * @param array $graph Yoast schema graph.
 * @return array
 */
function pepselect_child_filter_yoast_schema_graph( $graph ) {
	if ( function_exists( 'is_product' ) && is_product() ) {
		$product_url = get_permalink( get_queried_object_id() );

		if ( $product_url ) {
			$graph = pepselect_child_rewrite_product_schema_urls( $graph, $product_url );
		}
	}

	$organization_id = home_url( '/#organization' );
	$has_store       = false;

	foreach ( $graph as $index => $piece ) {
		$types = isset( $piece['@type'] ) ? (array) $piece['@type'] : array();

		if ( array_intersect( array( 'Organization', 'OnlineStore' ), $types ) ) {
			$has_store       = true;
			$organization_id = isset( $piece['@id'] ) ? $piece['@id'] : $organization_id;
			$graph[ $index ]['@type']                    = 'OnlineStore';
			$graph[ $index ]['hasMerchantReturnPolicy'] = pepselect_child_get_merchant_return_policy_schema();
			break;
		}
	}

	if ( ! $has_store ) {
		$custom_logo_id = absint( get_theme_mod( 'custom_logo' ) );
		$logo_id        = wp_attachment_is_image( $custom_logo_id ) ? $custom_logo_id : 595;
		$logo_url       = wp_get_attachment_image_url( $logo_id, 'full' );

		if ( $logo_url ) {
			$graph[] = array(
				'@type' => 'OnlineStore',
				'@id'   => $organization_id,
				'name'  => get_bloginfo( 'name' ),
				'url'   => home_url( '/' ),
				'logo'  => array(
					'@type' => 'ImageObject',
					'@id'   => $organization_id . '#logo',
					'url'   => $logo_url,
				),
				'image' => array( '@id' => $organization_id . '#logo' ),
				'hasMerchantReturnPolicy' => pepselect_child_get_merchant_return_policy_schema(),
			);
			$has_store = true;
		}
	}

	if ( $has_store ) {
		foreach ( $graph as $index => $piece ) {
			$types = isset( $piece['@type'] ) ? (array) $piece['@type'] : array();

			if ( in_array( 'WebSite', $types, true ) ) {
				$graph[ $index ]['publisher'] = array( '@id' => $organization_id );
			}
		}
	}

	return $graph;
}
add_filter( 'wpseo_schema_graph', 'pepselect_child_filter_yoast_schema_graph', 20 );

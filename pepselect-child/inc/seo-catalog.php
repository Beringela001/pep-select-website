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

	return '' !== $identity ? $identity . ' - ' . get_bloginfo( 'name' ) : $title;
}
add_filter( 'wpseo_title', 'pepselect_child_filter_product_seo_title', 20 );

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

	if ( isset( $offer['seller'] ) && is_array( $offer['seller'] ) ) {
		$offer['seller']['@type'] = 'OnlineStore';
	}

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
			$graph[ $index ]['hasMerchantReturnPolicy'] = array(
				'@type'              => 'MerchantReturnPolicy',
				'merchantReturnLink' => pepselect_child_get_page_url( 'refund-shipping-policy' ),
			);
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
				'hasMerchantReturnPolicy' => array(
					'@type'              => 'MerchantReturnPolicy',
					'merchantReturnLink' => pepselect_child_get_page_url( 'refund-shipping-policy' ),
				),
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

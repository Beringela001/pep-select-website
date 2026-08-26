<?php

defined( 'ABSPATH' ) || exit;

/** Scores related products only from approved, explainable research-area overlap. */
final class PepSelect_OE_Relationship_Engine {
	/** @param array<int,array<string,mixed>> $ordered @return array<int,array<string,mixed>> */
	public function recommend( array $ordered, int $limit = 4 ): array {
		if ( ! function_exists( 'wc_get_products' ) ) {
			return array();
		}
		$ordered_keys = array();
		$ordered_map  = array();
		foreach ( $ordered as $item ) {
			$key = PepSelect_OE_Content_Registry::normalize_name( (string) ( $item['name'] ?? '' ) );
			$entry = PepSelect_OE_Content_Registry::for_name( $key );
			if ( $entry ) {
				$ordered_keys[ $key ] = true;
				$ordered_map[ $key ]  = array( 'name' => (string) ( $item['display_name'] ?? $item['name'] ?? $key ), 'areas' => $entry['areas'] );
			}
		}
		if ( ! $ordered_map ) {
			return array();
		}

		$labels = PepSelect_OE_Content_Registry::area_labels();
		$found  = array();
		$blocked = array_filter( array_map( static fn( string $name ): string => PepSelect_OE_Content_Registry::normalize_name( $name ), explode( ',', (string) get_option( 'pepselect_oe_blocked_compounds', '' ) ) ) );
		$blocked = (array) apply_filters( 'pepselect_oe_blocked_relationships', $blocked, array_keys( $ordered_map ) );
		$products = wc_get_products( array( 'status' => 'publish', 'limit' => 100, 'orderby' => 'menu_order', 'order' => 'ASC', 'return' => 'objects' ) );
		foreach ( $products as $product ) {
			if ( ! $product instanceof WC_Product || ! $product->is_visible() || ! $product->is_purchasable() || ! $product->is_in_stock() ) {
				continue;
			}
			$key   = PepSelect_OE_Content_Registry::normalize_name( $product->get_name() );
			$entry = PepSelect_OE_Content_Registry::for_name( $key );
			if ( ! $entry || isset( $ordered_keys[ $key ] ) || isset( $found[ $key ] ) || in_array( $key, $blocked, true ) ) {
				continue;
			}
			$best = null;
			foreach ( $ordered_map as $source_key => $source ) {
				$overlap = array_values( array_intersect( $entry['areas'], $source['areas'] ) );
				if ( ! $overlap ) {
					continue;
				}
				$candidate = array( 'score' => count( $overlap ), 'source_key' => $source_key, 'source_name' => $source['name'], 'overlap' => $overlap );
				if ( null === $best || $candidate['score'] > $best['score'] ) {
					$best = $candidate;
				}
			}
			if ( null === $best ) {
				continue;
			}
			$area = $best['overlap'][0];
			$found[ $key ] = array(
				'key'         => $key,
				'product_id'  => $product->get_id(),
				'name'        => $product->get_name(),
				'url'         => $product->get_permalink(),
				'image'       => wp_get_attachment_image_url( $product->get_image_id(), 'woocommerce_thumbnail' ) ?: wc_placeholder_img_src( 'woocommerce_thumbnail' ),
				'related_to'  => $best['source_name'],
				'reason'      => sprintf( 'Also studied in %s.', $labels[ $area ] ?? str_replace( '-', ' ', $area ) ),
				'score'       => $best['score'],
				'menu_order'  => (int) $product->get_menu_order(),
			);
		}

		usort( $found, static function ( array $left, array $right ): int {
			return array( -$left['score'], $left['menu_order'], $left['name'] ) <=> array( -$right['score'], $right['menu_order'], $right['name'] );
		} );
		return array_slice( array_values( $found ), 0, max( 0, min( 4, $limit ) ) );
	}
}

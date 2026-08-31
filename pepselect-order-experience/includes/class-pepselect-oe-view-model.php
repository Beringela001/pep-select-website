<?php

defined( 'ABSPATH' ) || exit;

/** Builds one presentation-safe model from canonical Woo data plus the immutable Ops snapshot. */
final class PepSelect_OE_View_Model {
	private PepSelect_OE_COA_Resolver $coa;
	private PepSelect_OE_Relationship_Engine $relationships;

	public function __construct() {
		$this->coa           = new PepSelect_OE_COA_Resolver();
		$this->relationships = new PepSelect_OE_Relationship_Engine();
	}

	/** @param WC_Order $order @param array<string,mixed> $snapshot @return array<string,mixed> */
	public function build( $order, array $snapshot, bool $preview = false ): array {
		$items = array();
		foreach ( (array) ( $snapshot['items'] ?? array() ) as $snapshot_item ) {
			if ( ! is_array( $snapshot_item ) ) {
				continue;
			}
			$line_id   = absint( $snapshot_item['woo_line_item_id'] ?? 0 );
			$order_item = $line_id ? $order->get_item( $line_id ) : null;
			$product    = $order_item && method_exists( $order_item, 'get_product' ) ? $order_item->get_product() : null;
			$refunded   = $order_item ? absint( $order->get_qty_refunded_for_item( $line_id ) ) : 0;
			$ordered_quantity = max( 1, absint( $snapshot_item['quantity'] ?? ( $order_item ? $order_item->get_quantity() : 1 ) ) );
			$name       = (string) ( $snapshot_item['product_name'] ?? ( $order_item ? $order_item->get_name() : 'Compound' ) );
			$registry   = PepSelect_OE_Content_Registry::for_name( $name );
			$is_bacteriostatic_water = str_contains( PepSelect_OE_Content_Registry::normalize_name( $name ), 'bacteriostatic water' );
			$product_image = $product instanceof WC_Product ? ( wp_get_attachment_image_url( $product->get_image_id(), 'large' ) ?: wc_placeholder_img_src( 'woocommerce_thumbnail' ) ) : '';
			$allocations = array();
			foreach ( (array) ( $snapshot_item['allocations'] ?? array() ) as $allocation ) {
				if ( is_array( $allocation ) ) {
					$allocations[] = $this->allocation( $allocation, $product );
				}
			}
			$items[] = array(
				'name'         => $name,
				'display_name' => $is_bacteriostatic_water ? 'Bacteriostatic Water' : $name,
				'strength'     => $is_bacteriostatic_water ? '30 mL' : trim( (string) ( $snapshot_item['strength'] ?? '' ) ),
				'quantity'     => $ordered_quantity,
				'refunded'     => $refunded,
				'bullets'      => $is_bacteriostatic_water ? array( 'Hospira Bacteriostatic Water, USP', '30 mL multiple-dose vial' ) : ( $registry['bullets'] ?? array( 'Review the product page for its documented research context.' ) ),
				'allocations'  => $allocations,
				'image'        => esc_url_raw( $product_image ),
				'product_url'  => $product instanceof WC_Product ? esc_url_raw( get_permalink( $product->get_id() ) ) : '',
				'is_bacteriostatic_water' => $is_bacteriostatic_water,
				'line_total'   => $order_item ? $order->get_formatted_line_subtotal( $order_item ) : '',
				'product_id'   => $product ? $product->get_id() : 0,
				'available'    => $product instanceof WC_Product && $product->is_purchasable() && $product->is_in_stock() && $refunded < $ordered_quantity,
			);
		}

		$number = (string) $order->get_order_number();
		$date   = $order->get_date_created();
		$blocked_status = $order->has_status( array( 'cancelled', 'refunded', 'failed' ) );
		$coupon = $this->coupon( $preview );
		$related = ( $preview || '1' === get_option( 'pepselect_oe_relationships_approved', '0' ) ) ? $this->relationships->recommend( $items ) : array();

		return array(
			'preview'          => $preview,
			'order_number'     => $number,
			'first_name'       => trim( (string) $order->get_billing_first_name() ) ?: 'there',
			'date'             => $date ? wc_format_datetime( $date, 'M j, Y' ) : '',
			'status'           => wc_get_order_status_name( $order->get_status() ),
			'items'            => $items,
			'related'          => $related,
			'coupon'           => $blocked_status ? '' : $coupon,
			'can_reorder'      => ! $blocked_status && (bool) array_filter( $items, static fn( array $item ): bool => ! empty( $item['available'] ) ),
			'order_total'      => $order->get_formatted_order_total(),
			'subtotal'         => wc_price( (float) $order->get_subtotal(), array( 'currency' => $order->get_currency() ) ),
			'shipping'         => wc_price( (float) $order->get_shipping_total(), array( 'currency' => $order->get_currency() ) ),
			'discount'         => (float) $order->get_discount_total() > 0 ? wc_price( (float) $order->get_discount_total(), array( 'currency' => $order->get_currency() ) ) : '',
			'tax'              => (float) $order->get_total_tax() > 0 ? wc_price( (float) $order->get_total_tax(), array( 'currency' => $order->get_currency() ) ) : '',
			'contact_url'      => home_url( '/contact-us/' ),
			'faq_url'          => home_url( '/faq/' ),
			'account_url'      => function_exists( 'wc_get_account_endpoint_url' ) ? wc_get_account_endpoint_url( 'orders' ) : home_url( '/my-account/orders/' ),
			'account_order_url'=> is_user_logged_in() && (int) $order->get_customer_id() === get_current_user_id() ? $order->get_view_order_url() : ( function_exists( 'wc_get_account_endpoint_url' ) ? wc_get_account_endpoint_url( 'orders' ) : home_url( '/my-account/orders/' ) ),
		);
	}

	/** @param array<string,mixed> $allocation @param WC_Product|null $product @return array<string,mixed> */
	private function allocation( array $allocation, $product ): array {
		$batch = trim( (string) ( $allocation['batch_number'] ?? '' ) );
		$live  = $this->coa->resolve( $batch );
		$image = (string) ( $live['image'] ?? '' );
		$exact = '' !== $image;
		if ( ! $exact && $product instanceof WC_Product ) {
			$image = wp_get_attachment_image_url( $product->get_image_id(), 'large' ) ?: wc_placeholder_img_src( 'woocommerce_thumbnail' );
		}
		$outcome = (string) ( $live['outcome'] ?? '' );
		$stage   = (string) ( $live['stage'] ?? '' );
		if ( 'failed' === $outcome ) {
			$status = array( 'label' => 'Did not pass release review', 'tone' => 'failed' );
		} elseif ( 'in-testing' === $stage || 'pending' === $outcome ) {
			$status = array( 'label' => 'Testing underway', 'tone' => 'pending' );
		} elseif ( 'approved' === $outcome || ! empty( $allocation['third_party_tested'] ) ) {
			$status = array( 'label' => 'Third Party Tested - Passed', 'tone' => 'passed' );
		} else {
			$status = array( 'label' => 'Report available', 'tone' => 'neutral' );
		}
		$purity = rtrim( trim( (string) ( $live['purity'] ?? $allocation['purity_percent'] ?? '' ) ), "% \t\n\r\0\x0B" );
		return array(
			'batch'        => $batch,
			'quantity'     => max( 1, absint( $allocation['quantity'] ?? 1 ) ),
			'coa_url'      => esc_url_raw( (string) ( $allocation['coa_permalink'] ?? $live['url'] ?? '' ) ),
			'image'        => esc_url_raw( $image ),
			'image_srcset' => esc_attr( (string) ( $live['image_srcset'] ?? '' ) ),
			'image_exact'  => $exact,
			'purity'       => '' !== $purity ? rtrim( rtrim( $purity, '0' ), '.' ) . '%' : 'Not reported',
			'test_date'    => $this->date( (string) ( $live['test_date'] ?? $allocation['tested_date'] ?? '' ) ),
			'lab'          => trim( (string) ( $live['lab'] ?? $allocation['lab'] ?? '' ) ) ?: 'Not reported',
			'status'       => $status,
		);
	}

	private function coupon( bool $preview ): string {
		$code = trim( (string) get_option( 'pepselect_oe_coupon_code', '' ) );
		if ( $preview && '' === $code ) {
			return 'THANKYOU15';
		}
		if ( '' === $code || ! class_exists( 'WC_Coupon' ) ) {
			return '';
		}
		$coupon = new WC_Coupon( $code );
		return $coupon->get_id() && 'percent' === $coupon->get_discount_type() && 15.0 === (float) $coupon->get_amount() ? $coupon->get_code() : '';
	}

	private function date( string $value ): string {
		if ( '' === trim( $value ) ) {
			return 'Not reported';
		}
		$timestamp = strtotime( $value );
		return $timestamp ? wp_date( 'M j, Y', $timestamp ) : $value;
	}
}

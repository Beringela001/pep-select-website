<?php
/**
 * Email presentation helpers (M7).
 *
 * Shared email styling tokens and shipment-tracking resolution for the
 * customer-facing order emails.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Email-safe brand tokens, mirroring foundations.css values. Email clients
 * cannot read CSS custom properties, so the values are resolved here once and
 * inlined by the templates.
 *
 * @return array<string,string>
 */
function pepselect_child_email_tokens() {
	return array(
		'navy'        => '#002A53',
		'ink'         => '#001D3A',
		'slate'       => '#5E6F80',
		'neutral'     => '#7A8793',
		'cyan'        => '#17A1CF',
		'cyan_soft'   => '#E8F6FB',
		'amber'       => '#B46A00',
		'amber_soft'  => '#FDF6EA',
		'amber_ink'   => '#5C3A00',
		'border'      => '#D7E1E9',
		'white'       => '#FFFFFF',
		'font'        => "'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif",
		'font_mono'   => "'IBM Plex Mono', 'SFMono-Regular', Consolas, 'Liberation Mono', 'Courier New', Courier, monospace",
		'radius_pill' => '999px',
		'radius'      => '8px',
	);
}

/**
 * Resolve shipment tracking for an order.
 *
 * Easyship's own documentation states that tracking is written to the order
 * when the shipment is fulfilled, but does not publish a meta key, and the key
 * differs between the Easyship integration and generic shipment-tracking
 * plugins. Rather than assume one, this checks known keys first, then falls
 * back to scanning the order's meta for any tracking-shaped key, then to the
 * order notes Easyship writes. The key actually used is returned as 'source' so
 * it can be confirmed on a real shipped order.
 *
 * @param WC_Order $order Order object.
 * @return array{number:string,carrier:string,url:string,source:string}
 */
function pepselect_child_get_order_tracking( $order ) {
	$found = array(
		'number'  => '',
		'carrier' => '',
		'url'     => '',
		'source'  => '',
	);

	if ( ! is_a( $order, 'WC_Order' ) ) {
		return $found;
	}

	// 1. WooCommerce Shipment Tracking / AST structure, which several
	// integrations (including Easyship setups) write into.
	$items = $order->get_meta( '_wc_shipment_tracking_items' );

	if ( is_array( $items ) && ! empty( $items ) ) {
		$item = reset( $items );

		if ( is_array( $item ) ) {
			$found['number']  = isset( $item['tracking_number'] ) ? (string) $item['tracking_number'] : '';
			$found['carrier'] = isset( $item['tracking_provider'] ) && $item['tracking_provider']
				? (string) $item['tracking_provider']
				: ( isset( $item['custom_tracking_provider'] ) ? (string) $item['custom_tracking_provider'] : '' );
			$found['url']     = isset( $item['custom_tracking_link'] ) ? (string) $item['custom_tracking_link'] : '';
			$found['source']  = '_wc_shipment_tracking_items';

			if ( '' !== $found['number'] ) {
				return pepselect_child_finalize_tracking( $found );
			}
		}
	}

	// 2. Known Easyship and common tracking meta keys, most specific first.
	$number_keys = array(
		'_easyship_tracking_number',
		'easyship_tracking_number',
		'_easyship_last_mile_tracking_number',
		'_easyship_tracking',
		'_tracking_number',
		'tracking_number',
		'_shipment_tracking_number',
	);

	foreach ( $number_keys as $key ) {
		$value = $order->get_meta( $key );

		if ( is_string( $value ) && '' !== trim( $value ) ) {
			$found['number'] = trim( $value );
			$found['source'] = $key;
			break;
		}
	}

	$carrier_keys = array( '_easyship_courier', 'easyship_courier', '_easyship_carrier', '_tracking_provider', 'tracking_provider', '_shipment_carrier' );

	foreach ( $carrier_keys as $key ) {
		$value = $order->get_meta( $key );

		if ( is_string( $value ) && '' !== trim( $value ) ) {
			$found['carrier'] = trim( $value );
			break;
		}
	}

	$url_keys = array( '_easyship_tracking_url', 'easyship_tracking_url', '_easyship_tracking_page_url', '_tracking_url', 'tracking_url', '_custom_tracking_link' );

	foreach ( $url_keys as $key ) {
		$value = $order->get_meta( $key );

		if ( is_string( $value ) && '' !== trim( $value ) ) {
			$found['url'] = trim( $value );
			break;
		}
	}

	if ( '' !== $found['number'] ) {
		return pepselect_child_finalize_tracking( $found );
	}

	// 3. Unknown key: scan the order's own meta for a tracking-shaped entry.
	if ( method_exists( $order, 'get_meta_data' ) ) {
		foreach ( $order->get_meta_data() as $meta ) {
			$data = $meta->get_data();
			$key  = isset( $data['key'] ) ? (string) $data['key'] : '';
			$val  = isset( $data['value'] ) ? $data['value'] : '';

			if ( ! is_string( $val ) || '' === trim( $val ) ) {
				continue;
			}

			if ( preg_match( '/track/i', $key ) && ! preg_match( '/url|link|provider|carrier|courier|status/i', $key ) ) {
				$found['number'] = trim( $val );
				$found['source'] = $key . ' (discovered)';
			} elseif ( preg_match( '/track/i', $key ) && preg_match( '/url|link/i', $key ) ) {
				$found['url'] = trim( $val );
			} elseif ( preg_match( '/carrier|courier|provider/i', $key ) ) {
				$found['carrier'] = trim( $val );
			}
		}
	}

	if ( '' !== $found['number'] ) {
		return pepselect_child_finalize_tracking( $found );
	}

	// 4. Easyship writes tracking into an order note on fulfilment. Read the
	// most recent note that names a tracking number.
	$notes = function_exists( 'wc_get_order_notes' )
		? wc_get_order_notes(
			array(
				'order_id' => $order->get_id(),
				'limit'    => 20,
			)
		)
		: array();

	foreach ( (array) $notes as $note ) {
		$content = isset( $note->content ) ? (string) $note->content : '';

		if ( '' === $content || ! preg_match( '/track/i', $content ) ) {
			continue;
		}

		if ( preg_match( '~https?://\S+~i', $content, $url_match ) ) {
			$found['url'] = rtrim( $url_match[0], '.,)' );
		}

		if ( preg_match( '/([A-Z0-9][A-Z0-9\-]{7,34})/', strtoupper( $content ), $num_match ) ) {
			$found['number'] = $num_match[1];
			$found['source'] = 'order note';
			break;
		}
	}

	return pepselect_child_finalize_tracking( $found );
}

/**
 * Normalise a resolved tracking set and allow overriding it.
 *
 * @param array $found Resolved tracking data.
 * @return array{number:string,carrier:string,url:string,source:string}
 */
function pepselect_child_finalize_tracking( $found ) {
	$found['number']  = isset( $found['number'] ) ? trim( (string) $found['number'] ) : '';
	$found['carrier'] = isset( $found['carrier'] ) ? trim( (string) $found['carrier'] ) : '';
	$found['url']     = isset( $found['url'] ) ? esc_url_raw( trim( (string) $found['url'] ) ) : '';
	$found['source']  = isset( $found['source'] ) ? (string) $found['source'] : '';

	return (array) apply_filters( 'pepselect_child_order_tracking', $found );
}

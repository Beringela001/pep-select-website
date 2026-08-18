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
 * Customer-support mailbox used by transactional email links.
 *
 * Delivery authentication and routing remain owned by WP Mail SMTP. Keeping
 * the public support address in one helper prevents the customer templates
 * from drifting apart as the email system is completed one message at a time.
 *
 * @return string
 */
function pepselect_child_email_support_address() {
	return 'support@pepselect.com';
}

/**
 * Put the order's creation date and number at the front of the admin alert.
 *
 * The order timestamp is formatted in the store timezone by WooCommerce, so
 * the inbox date matches the date shown inside the order record.
 *
 * @param string   $subject Existing email subject.
 * @param WC_Order $order   Order being emailed.
 * @param WC_Email $email   WooCommerce email object.
 * @return string
 */
function pepselect_child_new_order_email_subject( $subject, $order, $email ) {
	unset( $email );

	if ( ! is_a( $order, 'WC_Order' ) ) {
		return $subject;
	}

	$created    = $order->get_date_created();
	$order_date = $created ? wc_format_datetime( $created, 'M j, Y' ) : wp_date( 'M j, Y' );

	return sprintf(
		/* translators: 1: order creation date, 2: order number. */
		__( '[%1$s] New order #%2$s', 'pepselect-child' ),
		$order_date,
		$order->get_order_number()
	);
}
add_filter( 'woocommerce_email_subject_new_order', 'pepselect_child_new_order_email_subject', 10, 3 );

/**
 * Keep the customer on-hold subject aligned with the approved payment email.
 *
 * The full HTML template owns its visible title, so the inbox subject is
 * intentionally managed here instead of relying on a database-only setting.
 *
 * @param string   $subject Existing email subject.
 * @param WC_Order $order   Order being emailed.
 * @param WC_Email $email   WooCommerce email object.
 * @return string
 */
function pepselect_child_on_hold_email_subject( $subject, $order, $email ) {
	unset( $subject, $order, $email );

	return __( 'Thank you for your order. Payment is the next step', 'pepselect-child' );
}
add_filter( 'woocommerce_email_subject_customer_on_hold_order', 'pepselect_child_on_hold_email_subject', 10, 3 );

/**
 * Confirm payment clearly and appreciatively in the processing-email subject.
 *
 * @param string   $subject Existing email subject.
 * @param WC_Order $order   Order being emailed.
 * @param WC_Email $email   WooCommerce email object.
 * @return string
 */
function pepselect_child_processing_order_email_subject( $subject, $order, $email ) {
	unset( $subject, $order, $email );

	return __( 'Thank you for your order. Payment confirmed', 'pepselect-child' );
}
add_filter( 'woocommerce_email_subject_customer_processing_order', 'pepselect_child_processing_order_email_subject', 10, 3 );

/**
 * Keep the completed-order subject warm, clear, and identifiable in the inbox.
 *
 * @param string   $subject Existing email subject.
 * @param WC_Order $order   Order being emailed.
 * @param WC_Email $email   WooCommerce email object.
 * @return string
 */
function pepselect_child_completed_order_email_subject( $subject, $order, $email ) {
	unset( $subject, $order, $email );

	return __( 'Thank you for choosing Pep Select. Your order is on the way', 'pepselect-child' );
}
add_filter( 'woocommerce_email_subject_customer_completed_order', 'pepselect_child_completed_order_email_subject', 10, 3 );

/**
 * Route replies from every WooCommerce customer email to customer support.
 *
 * The authenticated From address remains owned by WP Mail SMTP so this does
 * not weaken Gmail authentication or change delivery behavior.
 *
 * @param string   $headers  Existing email headers.
 * @param string   $email_id WooCommerce email identifier.
 * @param mixed    $object   Object associated with the email.
 * @param WC_Email $email    WooCommerce email object.
 * @return string
 */
function pepselect_child_customer_email_reply_to( $headers, $email_id, $object, $email ) {
	unset( $email_id, $object );

	if ( ! is_object( $email ) || ! method_exists( $email, 'is_customer_email' ) || ! $email->is_customer_email() ) {
		return $headers;
	}

	if ( false !== stripos( $headers, 'Reply-To:' ) ) {
		return $headers;
	}

	$name = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );

	return $headers . sprintf( "Reply-To: %s <%s>\r\n", $name, pepselect_child_email_support_address() );
}
add_filter( 'woocommerce_email_headers', 'pepselect_child_customer_email_reply_to', 20, 4 );

/**
 * Report whether a value actually looks like a shipment tracking number.
 *
 * Some integrations store flags under tracking-shaped meta keys, so a key name
 * alone is not evidence. A completed-order email rendered a tracking number of
 * "1" from such a flag; every resolution path now runs its candidate through
 * this check first, and a candidate that fails is skipped so resolution
 * continues to the next source.
 *
 * @param mixed $value Candidate value.
 * @return bool
 */
function pepselect_child_is_plausible_tracking_number( $value ) {
	// Arrays, objects, and other non-scalars are never tracking numbers.
	if ( ! is_scalar( $value ) ) {
		return false;
	}

	$value = trim( (string) $value );

	if ( '' === $value ) {
		return false;
	}

	// Serialized payloads are structured data, not a tracking number.
	if ( function_exists( 'is_serialized' ) && is_serialized( $value ) ) {
		return false;
	}

	// Explicit boolean-ish flags, whatever key they were stored under.
	$flags = array( '0', '1', 'yes', 'no', 'true', 'false' );

	if ( in_array( strtolower( $value ), $flags, true ) ) {
		return false;
	}

	$length = strlen( $value );

	if ( $length < 6 || $length > 40 ) {
		return false;
	}

	// Carriers use letters, digits, spaces, and hyphens only.
	if ( ! preg_match( '/^[A-Za-z0-9 \-]+$/', $value ) ) {
		return false;
	}

	// A real tracking number carries digits; four is a conservative floor.
	if ( preg_match_all( '/\d/', $value ) < 4 ) {
		return false;
	}

	return true;
}

/**
 * List the tracking-related meta keys present on an order, keys only.
 *
 * Easyship does not publish a meta key, so this is the diagnostic that lets the
 * real one be identified from a single shipped order. Values are deliberately
 * never included.
 *
 * @param WC_Order $order Order object.
 * @return string Comma-separated key list, capped at 15 entries.
 */
function pepselect_child_tracking_meta_key_list( $order ) {
	if ( ! is_a( $order, 'WC_Order' ) || ! method_exists( $order, 'get_meta_data' ) ) {
		return '';
	}

	$keys = array();

	foreach ( $order->get_meta_data() as $meta ) {
		$data = $meta->get_data();
		$key  = isset( $data['key'] ) ? (string) $data['key'] : '';

		if ( '' === $key || ! preg_match( '/track|tracking|shipment|easyship/i', $key ) ) {
			continue;
		}

		if ( ! in_array( $key, $keys, true ) ) {
			$keys[] = $key;
		}

		if ( count( $keys ) >= 15 ) {
			break;
		}
	}

	return implode( ', ', $keys );
}

/**
 * Resolve shipment tracking for an order.
 *
 * Easyship's own documentation states that tracking is written to the order
 * when the shipment is fulfilled, but does not publish a meta key, and the key
 * differs between the Easyship integration and generic shipment-tracking
 * plugins. Rather than assume one, this checks known keys first, then falls
 * back to scanning the order's meta for any tracking-shaped key, then to the
 * order notes Easyship writes. Every candidate must pass
 * pepselect_child_is_plausible_tracking_number() before it is accepted, so a
 * flag stored under a tracking-shaped key cannot populate the email. The key
 * actually used is returned as 'source', and 'candidates' lists the
 * tracking-related keys found on the order.
 *
 * @param WC_Order $order Order object.
 * @return array{number:string,carrier:string,url:string,source:string,candidates:string}
 */
function pepselect_child_get_order_tracking( $order ) {
	$found = array(
		'number'     => '',
		'carrier'    => '',
		'url'        => '',
		'source'     => '',
		'candidates' => '',
	);

	if ( ! is_a( $order, 'WC_Order' ) ) {
		return pepselect_child_finalize_tracking( $found );
	}

	$found['candidates'] = pepselect_child_tracking_meta_key_list( $order );

	// 1. WooCommerce Shipment Tracking / AST structure, which several
	// integrations (including Easyship setups) write into.
	$items = $order->get_meta( '_wc_shipment_tracking_items' );

	if ( is_array( $items ) && ! empty( $items ) ) {
		$item = reset( $items );

		if ( is_array( $item ) ) {
			$candidate = isset( $item['tracking_number'] ) ? $item['tracking_number'] : '';

			if ( '' === $found['carrier'] ) {
				$found['carrier'] = isset( $item['tracking_provider'] ) && $item['tracking_provider']
					? (string) $item['tracking_provider']
					: ( isset( $item['custom_tracking_provider'] ) ? (string) $item['custom_tracking_provider'] : '' );
			}

			if ( '' === $found['url'] && ! empty( $item['custom_tracking_link'] ) ) {
				$found['url'] = (string) $item['custom_tracking_link'];
			}

			if ( pepselect_child_is_plausible_tracking_number( $candidate ) ) {
				$found['number'] = trim( (string) $candidate );
				$found['source'] = '_wc_shipment_tracking_items';

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

		if ( pepselect_child_is_plausible_tracking_number( $value ) ) {
			$found['number'] = trim( (string) $value );
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
	// The key only nominates a candidate; the value still has to look like a
	// tracking number.
	if ( method_exists( $order, 'get_meta_data' ) ) {
		foreach ( $order->get_meta_data() as $meta ) {
			$data = $meta->get_data();
			$key  = isset( $data['key'] ) ? (string) $data['key'] : '';
			$val  = isset( $data['value'] ) ? $data['value'] : '';

			if ( '' === $key ) {
				continue;
			}

			if ( preg_match( '/track/i', $key ) && preg_match( '/url|link/i', $key ) ) {
				if ( is_string( $val ) && '' !== trim( $val ) && '' === $found['url'] ) {
					$found['url'] = trim( $val );
				}

				continue;
			}

			if ( preg_match( '/carrier|courier|provider/i', $key ) ) {
				if ( is_string( $val ) && '' !== trim( $val ) && '' === $found['carrier'] ) {
					$found['carrier'] = trim( $val );
				}

				continue;
			}

			if ( '' === $found['number'] && preg_match( '/track/i', $key ) && pepselect_child_is_plausible_tracking_number( $val ) ) {
				$found['number'] = trim( (string) $val );
				$found['source'] = $key . ' (discovered)';
			}
		}
	}

	if ( '' !== $found['number'] ) {
		return pepselect_child_finalize_tracking( $found );
	}

	// 4. Easyship writes tracking into an order note on fulfilment. Read the
	// notes that mention tracking or shipping and take the first token that
	// passes the plausibility rules, plus any URL in the same note.
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

		if ( '' === $content || ! preg_match( '/track|tracking|shipped/i', $content ) ) {
			continue;
		}

		$tokens = array();
		preg_match_all( '/[A-Za-z0-9\-]{6,40}/', $content, $tokens );

		$number = '';

		foreach ( (array) $tokens[0] as $token ) {
			if ( pepselect_child_is_plausible_tracking_number( $token ) ) {
				$number = $token;
				break;
			}
		}

		if ( '' === $number ) {
			continue;
		}

		$found['number'] = $number;
		$found['source'] = 'order note';

		if ( preg_match( '~https?://\S+~i', $content, $url_match ) ) {
			$found['url'] = rtrim( $url_match[0], '.,)' );
		}

		break;
	}

	return pepselect_child_finalize_tracking( $found );
}

/**
 * Normalise a resolved tracking set and allow overriding it.
 *
 * The number is re-validated here so a filtered or partially built result can
 * never reintroduce an implausible value.
 *
 * @param array $found Resolved tracking data.
 * @return array{number:string,carrier:string,url:string,source:string,candidates:string}
 */
function pepselect_child_finalize_tracking( $found ) {
	$number = isset( $found['number'] ) ? $found['number'] : '';

	$found['number']     = pepselect_child_is_plausible_tracking_number( $number ) ? trim( (string) $number ) : '';
	$found['carrier']    = isset( $found['carrier'] ) ? trim( (string) $found['carrier'] ) : '';
	$found['url']        = isset( $found['url'] ) ? esc_url_raw( trim( (string) $found['url'] ) ) : '';
	$found['source']     = isset( $found['source'] ) ? (string) $found['source'] : '';
	$found['candidates'] = isset( $found['candidates'] ) ? (string) $found['candidates'] : '';

	if ( '' === $found['number'] ) {
		$found['source'] = '';
	}

	return (array) apply_filters( 'pepselect_child_order_tracking', $found );
}

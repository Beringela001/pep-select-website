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
 * Public company details used in every customer-facing email footer.
 *
 * @return array<string,string>
 */
function pepselect_child_email_company_details() {
	return array(
		'name'        => 'Pep Select',
		'website'     => 'pepselect.com',
		'website_url' => home_url( '/' ),
		'address_1'   => '2090 Baker Rd, Ste 304 #A85',
		'address_2'   => 'Kennesaw, GA 30144',
		'email'       => pepselect_child_email_support_address(),
		'phone'       => '1 (833) 737-7528',
		'phone_url'   => 'tel:+18337377528',
	);
}

/**
 * Company-level ownership line used across customer-facing email footers.
 *
 * This wording describes Pep Select, not the origin of any product.
 *
 * @return string
 */
function pepselect_child_company_ownership_line() {
	return __( '🇺🇸 American-owned and operated.', 'pepselect-child' );
}

/**
 * Prominent, email-client-safe ownership treatment for WooCommerce footers.
 *
 * @return string
 */
function pepselect_child_company_ownership_email_html() {
	return sprintf(
		'<strong style="color:#FFFFFF;font-size:16px;font-weight:800;line-height:1.45;">%s</strong>',
		esc_html( pepselect_child_company_ownership_line() )
	);
}

/**
 * Shared, email-client-safe company footer.
 *
 * The navy field follows Pep Select marketing emails while the generous
 * spacing and muted secondary type keep it from overpowering the message.
 * Optional context stays below the company details for unsubscribe or
 * transactional disclosures owned by the sending system.
 *
 * @param string $context_html Escaped or trusted internal HTML appended below the company details.
 * @return string
 */
function pepselect_child_email_company_footer_html( $context_html = '' ) {
	$company = pepselect_child_email_company_details();
	$year    = wp_date( 'Y' );

	ob_start();
	?>
	<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="background-color:#002A53;border-collapse:separate;border-radius:0 0 16px 16px;width:100%;">
		<tr>
			<td style="padding:30px 44px 28px;">
				<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
					<tr>
						<td align="center" style="padding:0 0 22px;">
							<p style="font-family:'Plus Jakarta Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;line-height:1.45;margin:0;"><?php echo wp_kses_post( pepselect_child_company_ownership_email_html() ); ?></p>
						</td>
					</tr>
					<tr>
						<td align="center" style="border-top:1px solid #315775;color:#D8E6F2;font-family:'Plus Jakarta Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;font-size:12px;line-height:1.7;padding:22px 0 0;">
							<strong style="color:#FFFFFF;font-size:14px;"><?php echo esc_html( $company['name'] ); ?></strong><br>
							<a href="<?php echo esc_url( $company['website_url'] ); ?>" style="color:#7DD6F2;text-decoration:none;" target="_blank"><?php echo esc_html( $company['website'] ); ?></a><br>
							<?php echo esc_html( $company['address_1'] ); ?><br>
							<?php echo esc_html( $company['address_2'] ); ?><br>
							<a href="mailto:<?php echo esc_attr( $company['email'] ); ?>" style="color:#7DD6F2;text-decoration:none;"><?php echo esc_html( $company['email'] ); ?></a><br>
							<a href="<?php echo esc_url( $company['phone_url'] ); ?>" style="color:#7DD6F2;text-decoration:none;"><?php echo esc_html( $company['phone'] ); ?></a>
						</td>
					</tr>
					<?php if ( '' !== trim( $context_html ) ) : ?>
					<tr><td align="center" style="color:#AFC2D3;font-family:'Plus Jakarta Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;font-size:11px;line-height:1.6;padding:22px 0 0;"><?php echo wp_kses_post( $context_html ); ?></td></tr>
					<?php endif; ?>
					<tr><td align="center" style="color:#8FA8BA;font-family:'Plus Jakarta Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;font-size:10px;line-height:1.5;padding:18px 0 0;">&copy; <?php echo esc_html( $year ); ?> Pep Select. <?php esc_html_e( 'All rights reserved.', 'pepselect-child' ); ?></td></tr>
				</table>
			</td>
		</tr>
	</table>
	<?php
	return trim( (string) ob_get_clean() );
}

/**
 * Footer row for Pep Select standalone HTML email templates.
 *
 * @param string $context_html Optional disclosure or unsubscribe HTML.
 * @return string
 */
function pepselect_child_email_company_footer_row_html( $context_html = '' ) {
	return '<tr><td class="pep-email-footer" style="padding:0;">' . pepselect_child_email_company_footer_html( $context_html ) . '</td></tr>';
}

/**
 * Provide a complete fallback when a WooCommerce renderer uses only the
 * footer-text filter instead of the theme's email-footer template override.
 *
 * @param string $text Existing WooCommerce footer text.
 * @return string
 */
function pepselect_child_email_footer_text( $text ) {
	$company = pepselect_child_email_company_details();
	$details = sprintf(
		'%1$s<br>%2$s<br>%3$s<br><a href="mailto:%4$s">%4$s</a><br><a href="%5$s">%6$s</a>',
		esc_html( $company['website'] ),
		esc_html( $company['address_1'] ),
		esc_html( $company['address_2'] ),
		esc_attr( $company['email'] ),
		esc_url( $company['phone_url'] ),
		esc_html( $company['phone'] )
	);

	return pepselect_child_company_ownership_email_html() . '<br>' . $details . '<br><br>' . wp_kses_post( (string) $text );
}
add_filter( 'woocommerce_email_footer_text', 'pepselect_child_email_footer_text', 20 );

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
 * Read a resolved Back In Stock Notifier placeholder from its email object.
 *
 * The notifier populates placeholders before WooCommerce filters the subject
 * and heading. Reading that prepared value keeps the child theme out of the
 * plugin's subscriber and inventory workflow.
 *
 * @param mixed  $email    WooCommerce email object.
 * @param string $key      Placeholder name, including braces.
 * @param string $fallback Value used when the plugin has no live subscriber.
 * @return string
 */
function pepselect_child_bis_email_placeholder( $email, $key, $fallback = '' ) {
	if ( ! is_object( $email ) || ! isset( $email->placeholders ) || ! is_array( $email->placeholders ) ) {
		return $fallback;
	}

	$value = isset( $email->placeholders[ $key ] ) ? $email->placeholders[ $key ] : '';
	$value = trim( wp_strip_all_tags( (string) $value ) );

	return '' !== $value ? $value : $fallback;
}

/**
 * Keep the stock-subscription subject aligned with the approved email.
 *
 * @param string $subject Existing subject.
 * @param mixed  $object  Associated email object data.
 * @param mixed  $email   WooCommerce email object.
 * @return string
 */
function pepselect_child_bis_subscription_subject( $subject, $object, $email ) {
	unset( $object );

	$product_name = pepselect_child_bis_email_placeholder( $email, '{only_product_name}' );

	if ( '' === $product_name ) {
		return $subject;
	}

	return sprintf(
		/* translators: %s: product name. */
		__( 'You\'re on the list for %s', 'pepselect-child' ),
		$product_name
	);
}
add_filter( 'woocommerce_email_subject_cwg_bis_subscription', 'pepselect_child_bis_subscription_subject', 20, 3 );

/**
 * Keep the availability subject clear and free of artificial urgency.
 *
 * @param string $subject Existing subject.
 * @param mixed  $object  Associated email object data.
 * @param mixed  $email   WooCommerce email object.
 * @return string
 */
function pepselect_child_bis_instock_subject( $subject, $object, $email ) {
	unset( $object );

	$product_name = pepselect_child_bis_email_placeholder( $email, '{only_product_name}' );

	if ( '' === $product_name ) {
		return $subject;
	}

	return sprintf(
		/* translators: %s: product name. */
		__( '%s is available again at Pep Select', 'pepselect-child' ),
		$product_name
	);
}
add_filter( 'woocommerce_email_subject_cwg_bis_instock', 'pepselect_child_bis_instock_subject', 20, 3 );

/**
 * Replace the plugin heading with the approved stock-watch confirmation.
 *
 * @param string $heading Existing heading.
 * @param mixed  $object  Associated email object data.
 * @param mixed  $email   WooCommerce email object.
 * @return string
 */
function pepselect_child_bis_subscription_heading( $heading, $object, $email ) {
	unset( $heading, $object, $email );

	return __( 'We\'ll keep an eye on it', 'pepselect-child' );
}
add_filter( 'woocommerce_email_heading_cwg_bis_subscription', 'pepselect_child_bis_subscription_heading', 20, 3 );

/**
 * Replace the plugin heading with the approved availability message.
 *
 * @param string $heading Existing heading.
 * @param mixed  $object  Associated email object data.
 * @param mixed  $email   WooCommerce email object.
 * @return string
 */
function pepselect_child_bis_instock_heading( $heading, $object, $email ) {
	unset( $heading, $object, $email );

	return __( 'Good news. It\'s back.', 'pepselect-child' );
}
add_filter( 'woocommerce_email_heading_cwg_bis_instock', 'pepselect_child_bis_instock_heading', 20, 3 );

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

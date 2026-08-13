<?php
/**
 * Checkout Research Purpose field and compliance Acknowledgments (M12-1).
 *
 * The Research Purpose dropdown is unchanged from the M10 migration. The two
 * legacy consent checkboxes (privacy_agreement, terms_agreement_custom) are
 * replaced by two required Acknowledgments modelled for payment-processor
 * underwriting: a compliance statement, and a combined policy agreement that
 * folds the privacy consent into the Terms, Privacy, and Return & Refund links.
 *
 * All three controls render on woocommerce_review_order_before_submit (Research
 * Purpose at priority 10, the Acknowledgments at priority 20), the same proven
 * hook the migrated controls used, because Fluid Checkout silently drops markup
 * on classic checkout hooks it does not fire.
 *
 * Acceptance is stored on the order as evidence, not a flag: each checkbox as
 * Yes/No, an acceptance timestamp, and a wording-version hash so a later wording
 * change cannot make old orders unprovable. Legacy orders keep their original
 * meta and the admin block reads whichever set is present.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Remove the parent theme's original checkout-field hooks so the migrated
 * versions below are the only ones that run. Each remove is a harmless no-op if
 * the parent code has already been overwritten by an update.
 *
 * @return void
 */
function pepselect_child_remove_parent_checkout_hooks() {
	remove_action( 'woocommerce_review_order_before_submit', 'custom_checkout_required_checkboxes', 20 );
	remove_action( 'woocommerce_review_order_before_submit', 'pepselect_research_purpose_field', 10 );
	remove_action( 'woocommerce_checkout_process', 'custom_validate_required_checkboxes', 10 );
	remove_action( 'woocommerce_checkout_process', 'pepselect_validate_research_purpose', 10 );
	remove_action( 'woocommerce_checkout_create_order', 'custom_save_checkout_checkboxes', 10 );
	remove_action( 'woocommerce_checkout_create_order', 'pepselect_save_research_purpose', 20 );
	remove_action( 'woocommerce_admin_order_data_after_billing_address', 'display_checkout_agreements_admin', 10 );
	remove_action( 'woocommerce_admin_order_data_after_billing_address', 'pepselect_admin_research_purpose', 10 );
	remove_filter( 'woocommerce_email_order_meta_fields', 'pepselect_email_research_purpose', 10 );
}
add_action( 'init', 'pepselect_child_remove_parent_checkout_hooks', 5 );

/**
 * Remove YITH's optional birthday field from checkout.
 *
 * The rewards plugin adds this field to WooCommerce's checkout field array.
 * Removing it here prevents Fluid Checkout from building an empty expandable
 * section around it, while leaving every rewards and redemption feature intact.
 *
 * @param array<string,array<string,array<string,mixed>>> $fields Checkout fields.
 * @return array<string,array<string,array<string,mixed>>>
 */
function pepselect_child_remove_checkout_birthday_field( $fields ) {
	foreach ( $fields as $group => $group_fields ) {
		if ( is_array( $group_fields ) && isset( $fields[ $group ]['yith_birthday'] ) ) {
			unset( $fields[ $group ]['yith_birthday'] );
		}
	}

	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'pepselect_child_remove_checkout_birthday_field', 999 );

/**
 * The Research Purpose options, keyed by stored value. Keys equal labels, and
 * the stored value is the raw label, so this is also the allow-list used to
 * validate a submitted value before it is saved. The empty first entry is the
 * placeholder. Do not change the labels: they are stored on existing orders.
 *
 * @return array<string,string>
 */
function pepselect_child_research_purpose_options() {
	return array(
		''                                     => 'Select research purpose...',
		'Academic Research'                    => 'Academic Research',
		'Pharmaceutical Research'              => 'Pharmaceutical Research',
		'Biotech R&D'                          => 'Biotech R&D',
		'Cellular / Molecular Biology'         => 'Cellular / Molecular Biology',
		'Peptide Characterization'             => 'Peptide Characterization',
		'Quality Control / Analytical Testing' => 'Quality Control / Analytical Testing',
		'Other Research Purpose'               => 'Other Research Purpose',
	);
}

/**
 * Resolve a legal page URL through the theme helper, with a path fallback so the
 * label still links correctly if the helper is ever unavailable.
 *
 * @param string $slug Legal page slug.
 * @return string
 */
function pepselect_child_legal_link_url( $slug ) {
	if ( function_exists( 'pepselect_child_get_legal_url' ) ) {
		return pepselect_child_get_legal_url( $slug );
	}

	return home_url( '/' . $slug . '/' );
}

/**
 * The canonical Acknowledgment wording. One source string per checkbox is reused
 * for the rendered label and for the version hash, so the two can never drift.
 * Checkbox 2's three named policies are linked at render time from this text.
 *
 * @return array<string,string>
 */
function pepselect_child_ack_definitions() {
	return array(
		'compliance' => 'Research-only use restriction; prohibition on human or animal consumption; acknowledgment that products are not for diagnosis/treatment/prevention of any disease; indemnification of the seller; acknowledgment that the buyer is a qualified professional.',
		'policy'     => 'I have read and agree to the Terms & Conditions, Privacy Policy, and Return & Refund Policy.',
	);
}

/**
 * A short, stable identifier for the exact Acknowledgment wording an order
 * accepted. A hash of the two canonical strings is used rather than a manual
 * version number: it changes automatically the instant the wording changes and
 * cannot drift out of sync with the text, and the current text and its hash are
 * recorded in HANDOFF-processor-compliance-wording.md for lookup.
 *
 * @return string
 */
function pepselect_child_ack_version() {
	$defs = pepselect_child_ack_definitions();

	return substr( sha1( $defs['compliance'] . '|' . $defs['policy'] ), 0, 12 );
}

/**
 * Render the Research Purpose select at priority 10, above the Acknowledgments.
 *
 * @return void
 */
function pepselect_child_research_purpose_field() {
	woocommerce_form_field(
		'research_purpose',
		array(
			'type'              => 'select',
			'class'             => array( 'form-row', 'research-purpose-field' ),
			'label'             => __( 'Research Purpose', 'pepselect-child' ),
			'required'          => true,
			'options'           => pepselect_child_research_purpose_options(),
			'custom_attributes' => array(
				'aria-required' => 'true',
			),
		),
		''
	);

	echo '<span class="pepselect-ack__error pepselect-ack__error--field" id="research_purpose_error" role="alert" hidden></span>';
}
// M12-9: the consent block moved to the left column, into the slot the payment
// section vacated (fc_checkout_payment). Only the hook target changes here - the
// field markup, labels, validation and order meta are untouched.
add_action( 'fc_checkout_payment', 'pepselect_child_research_purpose_field', 10 );

/**
 * Render the two required Acknowledgment checkboxes at priority 20, so they
 * follow the Research Purpose field. Each carries an empty, hidden inline error
 * node that the client-side script fills; the input's aria-describedby points at
 * it so a screen reader announces the error when shown.
 *
 * @return void
 */
function pepselect_child_required_checkboxes() {
	$defs        = pepselect_child_ack_definitions();
	$terms_url   = pepselect_child_legal_link_url( 'terms-conditions' );
	$privacy_url = pepselect_child_legal_link_url( 'privacy-policy' );
	$refund_url  = pepselect_child_legal_link_url( 'refund-shipping-policy' );

	// Link the three named policies inside the canonical wording, so the rendered
	// label stays derived from the same string the version hash is built from.
	$policy_label = str_replace(
		array( 'Terms & Conditions', 'Privacy Policy', 'Return & Refund Policy' ),
		array(
			'<a href="' . esc_url( $terms_url ) . '" target="_blank" rel="noopener">Terms & Conditions</a>',
			'<a href="' . esc_url( $privacy_url ) . '" target="_blank" rel="noopener">Privacy Policy</a>',
			'<a href="' . esc_url( $refund_url ) . '" target="_blank" rel="noopener">Return &amp; Refund Policy</a>',
		),
		$defs['policy']
	);

	echo '<div class="pepselect-acknowledgments" role="group" aria-labelledby="pepselect-acknowledgments-heading">';
	echo '<p class="pepselect-acknowledgments__heading" id="pepselect-acknowledgments-heading">' . esc_html__( 'Acknowledgments', 'pepselect-child' ) . '</p>';

	woocommerce_form_field(
		'compliance_acknowledgment',
		array(
			'type'              => 'checkbox',
			'class'             => array( 'form-row', 'pepselect-ack', 'pepselect-ack--compliance' ),
			'label'             => esc_html( $defs['compliance'] ),
			'required'          => true,
			'custom_attributes' => array(
				'aria-required' => 'true',
			),
		),
		''
	);
	echo '<span class="pepselect-ack__error" id="compliance_acknowledgment_error" role="alert" hidden></span>';

	woocommerce_form_field(
		'policy_agreement',
		array(
			'type'              => 'checkbox',
			'class'             => array( 'form-row', 'pepselect-ack', 'pepselect-ack--policy' ),
			'label'             => $policy_label,
			'required'          => true,
			'custom_attributes' => array(
				'aria-required' => 'true',
			),
		),
		''
	);
	echo '<span class="pepselect-ack__error" id="policy_agreement_error" role="alert" hidden></span>';

	echo '</div>';
}
add_action( 'fc_checkout_payment', 'pepselect_child_required_checkboxes', 20 );

/**
 * Enqueue the client-side Acknowledgments validation on the checkout only. This
 * is UX (inline errors, focus); the server-side validation below is the
 * authoritative, non-bypassable gate.
 *
 * @return void
 */
function pepselect_child_ack_assets() {
	if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
		return;
	}

	wp_enqueue_script(
		'pepselect-child-checkout-acknowledgments',
		get_stylesheet_directory_uri() . '/assets/js/checkout-acknowledgments.js',
		array( 'jquery' ),
		pepselect_child_asset_version( 'assets/js/checkout-acknowledgments.js' ),
		true
	);

	wp_localize_script(
		'pepselect-child-checkout-acknowledgments',
		'pepselectAck',
		array(
			'fields' => array(
				array(
					'input'   => 'research_purpose',
					'error'   => 'research_purpose_error',
					'message' => __( 'Please select a research purpose to continue.', 'pepselect-child' ),
				),
				array(
					'input'   => 'compliance_acknowledgment',
					'error'   => 'compliance_acknowledgment_error',
					'message' => __( 'You must acknowledge the compliance statement to place your order.', 'pepselect-child' ),
				),
				array(
					'input'   => 'policy_agreement',
					'error'   => 'policy_agreement_error',
					'message' => __( 'You must agree to the Terms & Conditions, Privacy Policy, and Return & Refund Policy to place your order.', 'pepselect-child' ),
				),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'pepselect_child_ack_assets', 45 );

/**
 * Server-side validation of the two Acknowledgments. WooCommerce core validates
 * the checkout nonce before woocommerce_checkout_process fires, so the posted
 * fields can be trusted here. This runs regardless of the client-side script and
 * is what actually blocks placement, so acceptance is real evidence.
 *
 * @return void
 */
function pepselect_child_validate_required_checkboxes() {
	// phpcs:disable WordPress.Security.NonceVerification.Missing -- WooCommerce core validates the checkout nonce before this hook.
	if ( empty( $_POST['compliance_acknowledgment'] ) ) {
		wc_add_notice( __( 'You must acknowledge the compliance statement to place your order.', 'pepselect-child' ), 'error' );
	}

	if ( empty( $_POST['policy_agreement'] ) ) {
		wc_add_notice( __( 'You must agree to the Terms & Conditions, Privacy Policy, and Return & Refund Policy to place your order.', 'pepselect-child' ), 'error' );
	}
	// phpcs:enable WordPress.Security.NonceVerification.Missing
}
add_action( 'woocommerce_checkout_process', 'pepselect_child_validate_required_checkboxes', 10 );

/**
 * Validate Research Purpose: required, sanitized, and restricted to the known
 * options so an arbitrary value cannot be submitted.
 *
 * @return void
 */
function pepselect_child_validate_research_purpose() {
	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- WooCommerce core validates the checkout nonce before this hook.
	$value   = isset( $_POST['research_purpose'] ) ? sanitize_text_field( wp_unslash( $_POST['research_purpose'] ) ) : '';
	$options = pepselect_child_research_purpose_options();

	if ( '' === $value || ! array_key_exists( $value, $options ) ) {
		wc_add_notice( __( 'Please select a research purpose to continue.', 'pepselect-child' ), 'error' );
	}
}
add_action( 'woocommerce_checkout_process', 'pepselect_child_validate_research_purpose', 10 );

/**
 * Store Acknowledgment acceptance on the order as evidence: each checkbox as
 * Yes/No, an acceptance timestamp, and the wording-version hash. The order is
 * blocked unless both are ticked, so a stored "No" would only ever appear if the
 * gate were bypassed; it is recorded honestly either way.
 *
 * @param WC_Order $order Order being created.
 * @return void
 */
function pepselect_child_save_checkout_checkboxes( $order ) {
	// phpcs:disable WordPress.Security.NonceVerification.Missing -- WooCommerce core validates the checkout nonce before order creation.
	$order->update_meta_data( '_pepselect_ack_compliance', empty( $_POST['compliance_acknowledgment'] ) ? 'No' : 'Yes' );
	$order->update_meta_data( '_pepselect_ack_policy', empty( $_POST['policy_agreement'] ) ? 'No' : 'Yes' );
	// phpcs:enable WordPress.Security.NonceVerification.Missing
	$order->update_meta_data( '_pepselect_ack_timestamp', current_time( 'c' ) );
	$order->update_meta_data( '_pepselect_ack_version', pepselect_child_ack_version() );
}
add_action( 'woocommerce_checkout_create_order', 'pepselect_child_save_checkout_checkboxes', 10, 1 );

/**
 * Save the Research Purpose to order meta as the raw selected label. The value
 * is re-checked against the allow list so only a known label is stored.
 *
 * @param WC_Order $order Order being created.
 * @return void
 */
function pepselect_child_save_research_purpose( $order ) {
	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- WooCommerce core validates the checkout nonce before order creation.
	$value   = isset( $_POST['research_purpose'] ) ? sanitize_text_field( wp_unslash( $_POST['research_purpose'] ) ) : '';
	$options = pepselect_child_research_purpose_options();

	if ( '' !== $value && array_key_exists( $value, $options ) ) {
		$order->update_meta_data( '_research_purpose', $value );
	}
}
add_action( 'woocommerce_checkout_create_order', 'pepselect_child_save_research_purpose', 20, 1 );

/**
 * Show the stored acceptance in the admin order screen, after the billing
 * address. New orders (M12-1 onward) show the Acknowledgment block with its
 * timestamp and wording version; orders placed before this change fall back to
 * the legacy consent meta so historical orders still display correctly.
 *
 * @param WC_Order $order Order being displayed.
 * @return void
 */
function pepselect_child_admin_display_agreements( $order ) {
	$ack_compliance = (string) $order->get_meta( '_pepselect_ack_compliance' );
	$ack_policy     = (string) $order->get_meta( '_pepselect_ack_policy' );

	if ( '' !== $ack_compliance || '' !== $ack_policy ) {
		$timestamp = (string) $order->get_meta( '_pepselect_ack_timestamp' );
		$version   = (string) $order->get_meta( '_pepselect_ack_version' );

		echo '<div class="pepselect-admin-agreements">';
		echo '<p><strong>' . esc_html__( 'Compliance acknowledgments', 'pepselect-child' ) . '</strong></p>';
		echo '<p>' . esc_html__( 'Compliance statement accepted', 'pepselect-child' ) . ': ' . esc_html( '' !== $ack_compliance ? $ack_compliance : 'No' ) . '</p>';
		echo '<p>' . esc_html__( 'Terms, Privacy &amp; Refund agreement accepted', 'pepselect-child' ) . ': ' . esc_html( '' !== $ack_policy ? $ack_policy : 'No' ) . '</p>';

		if ( '' !== $timestamp ) {
			echo '<p>' . esc_html__( 'Accepted at', 'pepselect-child' ) . ': ' . esc_html( $timestamp ) . '</p>';
		}

		if ( '' !== $version ) {
			echo '<p>' . esc_html__( 'Wording version', 'pepselect-child' ) . ': ' . esc_html( $version ) . '</p>';
		}

		echo '</div>';
		return;
	}

	// Legacy orders placed before M12-1: fall back to the original consent meta.
	// The migrated code stored these under the leading-underscore keys; the
	// no-underscore forms are read too as a safety net, so a key-shape mismatch
	// cannot hide a real acceptance on an older order.
	$privacy = (string) $order->get_meta( '_privacy_agreement' );

	if ( '' === $privacy ) {
		$privacy = (string) $order->get_meta( 'privacy_agreement' );
	}

	$terms = (string) $order->get_meta( '_terms_agreement_custom' );

	if ( '' === $terms ) {
		$terms = (string) $order->get_meta( 'terms_agreement_custom' );
	}

	// Neither shape present: render nothing rather than an empty block.
	if ( '' === $privacy && '' === $terms ) {
		return;
	}

	// These pre-change orders carry no acceptance timestamp or wording version;
	// show them as not recorded rather than fabricating a value.
	echo '<div class="pepselect-admin-agreements">';
	echo '<p><strong>' . esc_html__( 'Legacy consent (pre-2026-08-11 wording)', 'pepselect-child' ) . '</strong></p>';
	echo '<p>' . esc_html__( 'Privacy Policy', 'pepselect-child' ) . ': ' . esc_html( '' !== $privacy ? $privacy : 'No' ) . '</p>';
	echo '<p>' . esc_html__( 'Terms & Conditions', 'pepselect-child' ) . ': ' . esc_html( '' !== $terms ? $terms : 'No' ) . '</p>';
	echo '<p>' . esc_html__( 'Accepted at', 'pepselect-child' ) . ': ' . esc_html__( 'not recorded', 'pepselect-child' ) . '</p>';
	echo '<p>' . esc_html__( 'Wording version', 'pepselect-child' ) . ': ' . esc_html__( 'not recorded', 'pepselect-child' ) . '</p>';
	echo '</div>';
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'pepselect_child_admin_display_agreements', 10, 1 );

/**
 * Show the stored Research Purpose in the admin order screen, after the billing
 * address.
 *
 * @param WC_Order $order Order being displayed.
 * @return void
 */
function pepselect_child_admin_display_research_purpose( $order ) {
	$value = (string) $order->get_meta( '_research_purpose' );

	if ( '' === $value ) {
		return;
	}

	echo '<div class="pepselect-admin-research-purpose">';
	echo '<p><strong>' . esc_html__( 'Research Purpose', 'pepselect-child' ) . ':</strong> ' . esc_html( $value ) . '</p>';
	echo '</div>';
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'pepselect_child_admin_display_research_purpose', 10, 1 );

/**
 * Add the Research Purpose to the order emails, under the "Research Purpose"
 * label. The label is a data contract and is left unchanged.
 *
 * @param array<string,array<string,string>> $fields        Existing email meta fields.
 * @param bool                                $sent_to_admin Whether the email targets the admin.
 * @param WC_Order                            $order         Order the email is for.
 * @return array<string,array<string,string>>
 */
function pepselect_child_email_research_purpose( $fields, $sent_to_admin, $order ) {
	if ( ! is_a( $order, 'WC_Order' ) ) {
		return $fields;
	}

	$value = (string) $order->get_meta( '_research_purpose' );

	if ( '' !== $value ) {
		$fields['research_purpose'] = array(
			'label' => __( 'Research Purpose', 'pepselect-child' ),
			'value' => $value,
		);
	}

	return $fields;
}
add_filter( 'woocommerce_email_order_meta_fields', 'pepselect_child_email_research_purpose', 10, 3 );

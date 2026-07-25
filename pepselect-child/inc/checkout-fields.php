<?php
/**
 * Checkout consent checkboxes and Research Purpose field (M10 migration).
 *
 * These fields were added directly to the parent theme's functions.php, where a
 * Hello Elementor update would silently overwrite them and take the checkout
 * consent gate and the Research Purpose field with it. They are migrated here,
 * renamed with the pepselect_child_ prefix so they never collide with the
 * parent's still-present originals, which are unhooked at runtime on init. The
 * data contracts are preserved exactly: field names (privacy_agreement,
 * terms_agreement_custom, research_purpose), meta keys (_privacy_agreement,
 * _terms_agreement_custom, _research_purpose), the stored Yes/No and raw-label
 * values, field order (Research Purpose at 10, consents at 20), and the
 * "Research Purpose" email label. The only change is the fix to the Terms link,
 * which pointed at the non-existent /terms-and-conditions/ and now resolves
 * through the legal URL helper.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Remove the parent theme's original checkout-field hooks so the migrated
 * versions below are the only ones that run. Each remove_action/remove_filter
 * is a harmless no-op if the parent code has already been overwritten by an
 * update. Runs early on init, before the checkout renders.
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
 * Resolve a legal page URL through the theme helper, with a path fallback so
 * the label still links correctly if the helper is ever unavailable. This is
 * the Terms-link fix: the slug is the real one, never /terms-and-conditions/.
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
 * Render the Research Purpose select. Registers at the default priority 10 on
 * woocommerce_review_order_before_submit, so it appears above the consents.
 *
 * @return void
 */
function pepselect_child_research_purpose_field() {
	woocommerce_form_field(
		'research_purpose',
		array(
			'type'     => 'select',
			'class'    => array( 'form-row', 'research-purpose-field' ),
			'label'    => __( 'Research Purpose', 'pepselect-child' ),
			'required' => true,
			'options'  => pepselect_child_research_purpose_options(),
		),
		''
	);
}
add_action( 'woocommerce_review_order_before_submit', 'pepselect_child_research_purpose_field', 10 );

/**
 * Render the two required consent checkboxes at priority 20, so they follow the
 * Research Purpose field. The label URLs are built from the legal helper; the
 * href is escaped and the sentence wording is otherwise verbatim.
 *
 * @return void
 */
function pepselect_child_required_checkboxes() {
	$privacy_url = pepselect_child_legal_link_url( 'privacy-policy' );
	$terms_url   = pepselect_child_legal_link_url( 'terms-conditions' );

	woocommerce_form_field(
		'privacy_agreement',
		array(
			'type'     => 'checkbox',
			'class'    => array( 'form-row', 'privacy-checkbox' ),
			'label'    => 'I have read and agree to the <a href="' . esc_url( $privacy_url ) . '" target="_blank">Privacy Policy</a>.',
			'required' => true,
		),
		''
	);

	woocommerce_form_field(
		'terms_agreement_custom',
		array(
			'type'     => 'checkbox',
			'class'    => array( 'form-row', 'terms-checkbox' ),
			'label'    => 'I have read and agree to the <a href="' . esc_url( $terms_url ) . '" target="_blank">Terms & Conditions</a>.',
			'required' => true,
		),
		''
	);
}
add_action( 'woocommerce_review_order_before_submit', 'pepselect_child_required_checkboxes', 20 );

/**
 * Validate the required consent checkboxes. WooCommerce core validates the
 * checkout nonce before woocommerce_checkout_process fires, so the posted
 * fields can be trusted here.
 *
 * @return void
 */
function pepselect_child_validate_required_checkboxes() {
	// phpcs:disable WordPress.Security.NonceVerification.Missing -- WooCommerce core validates the checkout nonce before this hook.
	if ( empty( $_POST['privacy_agreement'] ) ) {
		wc_add_notice( __( 'Please agree to the Privacy Policy to continue.', 'pepselect-child' ), 'error' );
	}

	if ( empty( $_POST['terms_agreement_custom'] ) ) {
		wc_add_notice( __( 'Please agree to the Terms & Conditions to continue.', 'pepselect-child' ), 'error' );
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
 * Save the consent answers to order meta as Yes/No. Priority 10 mirrors the
 * parent hook order.
 *
 * @param WC_Order $order Order being created.
 * @return void
 */
function pepselect_child_save_checkout_checkboxes( $order ) {
	// phpcs:disable WordPress.Security.NonceVerification.Missing -- WooCommerce core validates the checkout nonce before order creation.
	$order->update_meta_data( '_privacy_agreement', empty( $_POST['privacy_agreement'] ) ? 'No' : 'Yes' );
	$order->update_meta_data( '_terms_agreement_custom', empty( $_POST['terms_agreement_custom'] ) ? 'No' : 'Yes' );
	// phpcs:enable WordPress.Security.NonceVerification.Missing
}
add_action( 'woocommerce_checkout_create_order', 'pepselect_child_save_checkout_checkboxes', 10, 1 );

/**
 * Save the Research Purpose to order meta as the raw selected label. Priority
 * 20 mirrors the parent hook order. The value is re-checked against the allow
 * list so only a known label is stored.
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
 * Show the stored consent answers in the admin order screen, after the billing
 * address. Priority 10 mirrors the parent hook.
 *
 * @param WC_Order $order Order being displayed.
 * @return void
 */
function pepselect_child_admin_display_agreements( $order ) {
	$privacy = (string) $order->get_meta( '_privacy_agreement' );
	$terms   = (string) $order->get_meta( '_terms_agreement_custom' );

	if ( '' === $privacy && '' === $terms ) {
		return;
	}

	echo '<div class="pepselect-admin-agreements">';
	echo '<p><strong>' . esc_html__( 'Consent', 'pepselect-child' ) . '</strong></p>';
	echo '<p>' . esc_html__( 'Privacy Policy', 'pepselect-child' ) . ': ' . esc_html( '' !== $privacy ? $privacy : 'No' ) . '</p>';
	echo '<p>' . esc_html__( 'Terms & Conditions', 'pepselect-child' ) . ': ' . esc_html( '' !== $terms ? $terms : 'No' ) . '</p>';
	echo '</div>';
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'pepselect_child_admin_display_agreements', 10, 1 );

/**
 * Show the stored Research Purpose in the admin order screen, after the billing
 * address. Priority 10 mirrors the parent hook.
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

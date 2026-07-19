<?php
/**
 * Contact page wiring: conditional asset enqueue and a coded contact form
 * handler that sends to the support inbox via wp_mail().
 *
 * The template (page-contact.php) is selected by WordPress for the page with
 * slug "contact". The form posts back to the same page; this module validates
 * the submission, sends the email, and exposes the result to the template.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Support inbox for contact submissions.
 */
if ( ! defined( 'PEPSELECT_CONTACT_INBOX' ) ) {
	define( 'PEPSELECT_CONTACT_INBOX', 'support@pepselect.com' );
}

/**
 * Determine whether the current request is the coded Contact page.
 *
 * @return bool
 */
function pepselect_child_is_contact_request() {
	return is_page( 'contact' );
}

/**
 * Enqueue Contact presentation assets only on the Contact page.
 *
 * @return void
 */
function pepselect_child_enqueue_contact_assets() {
	if ( ! pepselect_child_is_contact_request() ) {
		return;
	}

	wp_enqueue_style(
		'pepselect-child-contact',
		get_stylesheet_directory_uri() . '/assets/css/contact.css',
		array( 'pepselect-child-foundations' ),
		pepselect_child_asset_version( 'assets/css/contact.css' )
	);
}
add_action( 'wp_enqueue_scripts', 'pepselect_child_enqueue_contact_assets', 40 );

/**
 * Return the canonical Contact page URL, with a path fallback.
 *
 * @return string
 */
function pepselect_child_get_contact_url() {
	return pepselect_child_get_page_url( 'contact' );
}

/**
 * Process a contact form submission on the Contact page.
 *
 * Runs on template_redirect so it fires before output. Stores the result in a
 * module-level static that the template reads. Returns early for non-Contact
 * requests and non-POST requests, so it is inert everywhere else.
 *
 * @return void
 */
function pepselect_child_handle_contact_submission() {
	if ( is_admin() || 'POST' !== ( isset( $_SERVER['REQUEST_METHOD'] ) ? $_SERVER['REQUEST_METHOD'] : '' ) ) {
		return;
	}

	if ( ! isset( $_POST['pepselect_contact_submit'] ) || ! pepselect_child_is_contact_request() ) {
		return;
	}

	$state = array(
		'status'  => 'error',
		'message' => __( 'Something went wrong. Please email us directly at support@pepselect.com.', 'pepselect-child' ),
		'values'  => array(
			'name'    => '',
			'email'   => '',
			'subject' => '',
			'body'    => '',
		),
	);

	// Nonce check.
	$nonce = isset( $_POST['pepselect_contact_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['pepselect_contact_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'pepselect_contact' ) ) {
		pepselect_child_set_contact_state( $state );
		return;
	}

	// Honeypot: a real user leaves this empty. Bots fill it.
	$honeypot = isset( $_POST['pepselect_contact_website'] ) ? trim( (string) wp_unslash( $_POST['pepselect_contact_website'] ) ) : '';
	if ( '' !== $honeypot ) {
		// Silently treat as success so bots get no signal, but send nothing.
		$state['status']  = 'success';
		$state['message'] = __( 'Thanks. Your message is on its way, and we\'ll reply within one business day.', 'pepselect-child' );
		pepselect_child_set_contact_state( $state );
		return;
	}

	$name    = isset( $_POST['pepselect_contact_name'] ) ? sanitize_text_field( wp_unslash( $_POST['pepselect_contact_name'] ) ) : '';
	$email   = isset( $_POST['pepselect_contact_email'] ) ? sanitize_email( wp_unslash( $_POST['pepselect_contact_email'] ) ) : '';
	$subject = isset( $_POST['pepselect_contact_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['pepselect_contact_subject'] ) ) : '';
	$body    = isset( $_POST['pepselect_contact_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['pepselect_contact_message'] ) ) : '';

	$state['values'] = array(
		'name'    => $name,
		'email'   => $email,
		'subject' => $subject,
		'body'    => $body,
	);

	// Validation: email and message are required.
	if ( '' === $email || ! is_email( $email ) ) {
		$state['message'] = __( 'Please enter a valid email address so we can reply.', 'pepselect-child' );
		pepselect_child_set_contact_state( $state );
		return;
	}

	if ( '' === $body ) {
		$state['message'] = __( 'Please add a message before sending.', 'pepselect-child' );
		pepselect_child_set_contact_state( $state );
		return;
	}

	// Compose and send.
	$site_name    = wp_specialchars_decode( (string) get_bloginfo( 'name' ), ENT_QUOTES );
	$mail_subject = sprintf(
		/* translators: 1: site name, 2: submitted subject or default. */
		__( '[%1$s Contact] %2$s', 'pepselect-child' ),
		$site_name,
		'' !== $subject ? $subject : __( 'New message', 'pepselect-child' )
	);

	$lines = array(
		'' !== $name ? sprintf( __( 'Name: %s', 'pepselect-child' ), $name ) : __( 'Name: (not provided)', 'pepselect-child' ),
		sprintf( __( 'Email: %s', 'pepselect-child' ), $email ),
		'' !== $subject ? sprintf( __( 'Subject: %s', 'pepselect-child' ), $subject ) : __( 'Subject: (not provided)', 'pepselect-child' ),
		'',
		$body,
	);
	$mail_body = implode( "\n", $lines );

	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		sprintf( 'Reply-To: %s', $email ),
	);

	$sent = wp_mail( PEPSELECT_CONTACT_INBOX, $mail_subject, $mail_body, $headers );

	if ( $sent ) {
		$state['status']  = 'success';
		$state['message'] = __( 'Thanks. Your message is on its way, and we\'ll reply within one business day.', 'pepselect-child' );
		// Clear values so the form resets on success.
		$state['values']  = array( 'name' => '', 'email' => '', 'subject' => '', 'body' => '' );
	} else {
		$state['message'] = __( 'We could not send your message just now. Please email us directly at support@pepselect.com.', 'pepselect-child' );
	}

	pepselect_child_set_contact_state( $state );
}
add_action( 'template_redirect', 'pepselect_child_handle_contact_submission' );

/**
 * Store or retrieve the contact submission result for the current request.
 *
 * @param array<string,mixed>|null $state Optional state to store.
 * @return array<string,mixed>|null
 */
function pepselect_child_set_contact_state( $state = null ) {
	static $stored = null;

	if ( null !== $state ) {
		$stored = $state;
	}

	return $stored;
}

/**
 * Convenience accessor for the template.
 *
 * @return array<string,mixed>|null
 */
function pepselect_child_get_contact_state() {
	return pepselect_child_set_contact_state();
}

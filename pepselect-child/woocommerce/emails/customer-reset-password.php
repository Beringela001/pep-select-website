<?php
/**
 * Customer reset password email — Pep Select child theme override.
 *
 * @package WooCommerce\Templates\Emails
 * @version 10.9.0
 */

defined( 'ABSPATH' ) || exit;

$pep_display_name = isset( $user_display_name ) ? trim( (string) $user_display_name ) : '';
$pep_display_name = '' !== $pep_display_name ? $pep_display_name : ( isset( $user_login ) ? (string) $user_login : __( 'there', 'pepselect-child' ) );
$pep_reset_url    = add_query_arg(
	array(
		'key'   => $reset_key,
		'id'    => $user_id,
		'login' => rawurlencode( $user_login ),
	),
	wc_get_endpoint_url( 'lost-password', '', wc_get_page_permalink( 'myaccount' ) )
);

$pep_email_title        = __( 'Reset your password', 'pepselect-child' );
$pep_preheader          = __( 'Use the secure link in this email to choose a new password.', 'pepselect-child' );
$pep_header_meta        = __( 'Account security', 'pepselect-child' );
$pep_label              = __( 'Account security', 'pepselect-child' );
$pep_heading            = __( 'Reset your password.', 'pepselect-child' );
$pep_greeting           = sprintf( __( 'Hi %s,', 'pepselect-child' ), $pep_display_name );
$pep_intro_html         = '<p style="margin:0;">' . esc_html__( 'We received a request to reset the password for your Pep Select account.', 'pepselect-child' ) . '</p>';
$pep_panel_html         = '<p style="color:#0D708E;font-family:monospace;font-size:10px;font-weight:700;letter-spacing:1.3px;line-height:1.5;margin:0 0 5px;text-transform:uppercase;">' . esc_html__( 'Account', 'pepselect-child' ) . '</p><p style="color:#002A53;font-family:monospace;font-size:14px;font-weight:700;line-height:1.5;margin:0;overflow-wrap:anywhere;">' . esc_html( $user_login ) . '</p>';
$pep_order_content_html = '';
$pep_button_url         = $pep_reset_url;
$pep_button_label       = __( 'Choose a new password', 'pepselect-child' );
$pep_note_html          = '<p style="margin:0;">' . esc_html__( 'If you did not request this, you can ignore this email. Your password will not change unless the button above is used.', 'pepselect-child' ) . '</p>';
$pep_footer_context     = esc_html__( 'This secure password link is intended only for the account shown above.', 'pepselect-child' );

if ( ! empty( $additional_content ) ) {
	$pep_note_html .= wpautop( wp_kses_post( wptexturize( $additional_content ) ) );
}

require __DIR__ . '/pepselect-simple-message.php';

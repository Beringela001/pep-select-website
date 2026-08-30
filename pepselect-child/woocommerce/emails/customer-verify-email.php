<?php
/**
 * Customer email verification — Pep Select child theme override.
 *
 * @package WooCommerce\Templates\Emails
 * @version 11.0.0
 */

defined( 'ABSPATH' ) || exit;

$pep_display_name = isset( $user_display_name ) ? trim( (string) $user_display_name ) : '';
$pep_display_name = '' !== $pep_display_name ? $pep_display_name : __( 'there', 'pepselect-child' );

$pep_email_title        = __( 'Confirm your email address', 'pepselect-child' );
$pep_preheader          = __( 'Confirm your email address to finish setting up your Pep Select account.', 'pepselect-child' );
$pep_header_meta        = __( 'Account verification', 'pepselect-child' );
$pep_label              = __( 'Account verification', 'pepselect-child' );
$pep_heading            = __( 'Confirm your email address.', 'pepselect-child' );
$pep_greeting           = sprintf( __( 'Hi %s,', 'pepselect-child' ), $pep_display_name );
$pep_intro_html         = '<p style="margin:0;">' . esc_html__( 'Confirm this email address to finish setting up your Pep Select account.', 'pepselect-child' ) . '</p>';
$pep_panel_html         = '<p style="color:#0D708E;font-family:monospace;font-size:10px;font-weight:700;letter-spacing:1.3px;line-height:1.5;margin:0 0 5px;text-transform:uppercase;">' . esc_html__( 'Email address', 'pepselect-child' ) . '</p><p style="color:#002A53;font-family:monospace;font-size:14px;font-weight:700;line-height:1.5;margin:0;overflow-wrap:anywhere;">' . esc_html( $user_email ) . '</p>';
$pep_order_content_html = '';
$pep_button_url         = $verify_url;
$pep_button_label       = __( 'Confirm email address', 'pepselect-child' );
$pep_note_html          = '<p style="margin:0;">' . esc_html__( 'If you did not create this account, you can ignore this email.', 'pepselect-child' ) . '</p>';
$pep_footer_context     = esc_html__( 'This confirmation link is intended only for the email address shown above.', 'pepselect-child' );

if ( ! empty( $additional_content ) ) {
	$pep_note_html .= wpautop( wp_kses_post( wptexturize( $additional_content ) ) );
}

require __DIR__ . '/pepselect-simple-message.php';

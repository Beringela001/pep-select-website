<?php
/**
 * Customer refunded order email — Pep Select child theme override.
 *
 * @package WooCommerce\Templates\Emails
 * @version 10.4.0
 */

defined( 'ABSPATH' ) || exit;

$pep_order_number = $order->get_order_number();
$pep_first_name   = trim( (string) $order->get_billing_first_name() );
$pep_first_name   = '' !== $pep_first_name ? $pep_first_name : __( 'there', 'pepselect-child' );
$pep_is_partial   = ! empty( $partial_refund );

ob_start();
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );
$pep_order_content_html = (string) ob_get_clean();

$pep_email_title = $pep_is_partial ? __( 'Part of your order has been refunded', 'pepselect-child' ) : __( 'Your order has been refunded', 'pepselect-child' );
$pep_preheader   = $pep_is_partial
	? sprintf( __( 'A partial refund was processed for order #%s.', 'pepselect-child' ), $pep_order_number )
	: sprintf( __( 'A refund was processed for order #%s.', 'pepselect-child' ), $pep_order_number );
$pep_header_meta = sprintf( __( 'Order #%s', 'pepselect-child' ), $pep_order_number );
$pep_label       = $pep_is_partial ? sprintf( __( 'Order #%s · Partial refund', 'pepselect-child' ), $pep_order_number ) : sprintf( __( 'Order #%s · Refund complete', 'pepselect-child' ), $pep_order_number );
$pep_heading     = $pep_is_partial ? __( 'Part of your order has been refunded.', 'pepselect-child' ) : __( 'Your order has been refunded.', 'pepselect-child' );
$pep_greeting    = sprintf( __( 'Hi %s,', 'pepselect-child' ), $pep_first_name );
$pep_intro_html  = $pep_is_partial
	? '<p style="margin:0;">' . esc_html__( 'We processed a partial refund for your order. The updated order summary below shows the refunded amount and remaining total.', 'pepselect-child' ) . '</p>'
	: '<p style="margin:0;">' . esc_html__( 'We processed the refund for your order. The order summary below shows the refunded amount.', 'pepselect-child' ) . '</p>';
$pep_panel_html  = '<p style="color:#002A53;font-family:inherit;font-size:15px;font-weight:700;line-height:1.45;margin:0 0 4px;">' . esc_html__( 'What happens next', 'pepselect-child' ) . '</p><p style="color:#5E6F80;font-family:inherit;font-size:14px;line-height:1.55;margin:0;">' . esc_html__( 'The time it takes for the credit to appear depends on your payment provider.', 'pepselect-child' ) . '</p>';
$pep_button_url   = '';
$pep_button_label = '';
$pep_note_html    = '<p style="margin:0;">' . esc_html__( 'Have a question? Reply to this email, and one of our team members will be in touch shortly.', 'pepselect-child' ) . '</p>';
$pep_footer_context = esc_html__( 'All products are intended for laboratory study and identification purposes only. Not intended for human or animal use.', 'pepselect-child' );

require __DIR__ . '/pepselect-simple-message.php';

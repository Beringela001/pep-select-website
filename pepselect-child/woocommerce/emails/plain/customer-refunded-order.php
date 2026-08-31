<?php
/** Customer refunded order email (plain text). @version 10.4.0 */
defined( 'ABSPATH' ) || exit;

$pep_is_partial = ! empty( $partial_refund );
$pep_first_name = trim( (string) $order->get_billing_first_name() );
$pep_first_name = '' !== $pep_first_name ? $pep_first_name : __( 'there', 'pepselect-child' );

echo "==========\n";
echo esc_html( $pep_is_partial ? __( 'PARTIAL REFUND', 'pepselect-child' ) : __( 'REFUND COMPLETE', 'pepselect-child' ) ) . "\n";
echo "==========\n\n";
printf( esc_html__( 'Hi %s,', 'pepselect-child' ), esc_html( $pep_first_name ) );
echo "\n\n";
echo esc_html( $pep_is_partial
	? __( 'We processed a partial refund for your order. The updated order summary below shows the refunded amount and remaining total.', 'pepselect-child' )
	: __( 'We processed the refund for your order. The order summary below shows the refunded amount.', 'pepselect-child' ) );
echo "\n\n";
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, true, $email );
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, true, $email );
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, true, $email );
echo "\n" . esc_html__( 'The time it takes for the credit to appear depends on your payment provider.', 'pepselect-child' ) . "\n";
echo "\n" . esc_html__( 'Have a question? Reply to this email, and one of our team members will be in touch shortly.', 'pepselect-child' ) . "\n";

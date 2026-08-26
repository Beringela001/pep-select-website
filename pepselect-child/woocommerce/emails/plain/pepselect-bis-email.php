<?php
/**
 * Shared plain-text Back In Stock Notifier email.
 *
 * @package PepSelectChild
 */

defined( 'ABSPATH' ) || exit;

$pep_bis_is_available = isset( $pep_bis_email_type ) && 'instock' === $pep_bis_email_type;
$pep_product_label    = trim( wp_strip_all_tags( isset( $product_name ) ? (string) $product_name : '' ) );
$pep_product_label    = '' !== $pep_product_label ? $pep_product_label : __( 'Your selected product', 'pepselect-child' );
$pep_product_url      = ! empty( $product_link ) ? esc_url_raw( $product_link ) : home_url( '/shop/' );
$pep_recipient        = isset( $email ) && is_object( $email ) && method_exists( $email, 'get_recipient' ) ? sanitize_email( $email->get_recipient() ) : '';
$pep_support_email    = function_exists( 'pepselect_child_email_support_address' ) ? pepselect_child_email_support_address() : 'support@pepselect.com';

echo "PEP SELECT\n\n";
echo $pep_bis_is_available ? "YOUR STOCK NOTIFICATION\n" : "STOCK NOTIFICATION CONFIRMED\n";
echo wp_strip_all_tags( $pep_bis_is_available ? __( 'Good news. It\'s back.', 'pepselect-child' ) : __( 'We\'ll keep an eye on it', 'pepselect-child' ) ) . "\n\n";
echo wp_strip_all_tags( __( 'Hi there,', 'pepselect-child' ) ) . "\n\n";

if ( $pep_bis_is_available ) {
	printf( wp_strip_all_tags( __( '%s is available again. You asked us to let you know, so this is the note.', 'pepselect-child' ) ), $pep_product_label );
	echo "\n\n" . wp_strip_all_tags( __( 'Review the product information and available batch documentation before ordering.', 'pepselect-child' ) );
	echo "\n\n" . wp_strip_all_tags( __( 'Thanks for asking us to keep an eye on it.', 'pepselect-child' ) );
} else {
	printf(
		wp_strip_all_tags( __( 'We saved your request for %1$s. When it returns to stock, we\'ll send one email to %2$s.', 'pepselect-child' ) ),
		$pep_product_label,
		$pep_recipient
	);
	echo "\n\n" . wp_strip_all_tags( __( 'Stock watch is on. You can give the refresh button a rest.', 'pepselect-child' ) );
	echo "\n\n" . wp_strip_all_tags( __( 'No action is needed to keep your notification active.', 'pepselect-child' ) );
}

echo "\n\n" . wp_strip_all_tags( __( 'Product:', 'pepselect-child' ) ) . ' ' . $pep_product_label;
echo "\n" . wp_strip_all_tags( $pep_bis_is_available ? __( 'Available now', 'pepselect-child' ) : __( 'Waiting for restock', 'pepselect-child' ) );
echo "\n" . wp_strip_all_tags( $pep_bis_is_available ? __( 'View product:', 'pepselect-child' ) : __( 'Review product details:', 'pepselect-child' ) ) . ' ' . $pep_product_url;
echo "\n\n" . wp_strip_all_tags( __( 'Questions?', 'pepselect-child' ) ) . ' ' . $pep_support_email;
echo "\n\n---\nPep Select\npepselect.com\n" . wp_strip_all_tags( __( 'For laboratory research use only.', 'pepselect-child' ) ) . "\n";

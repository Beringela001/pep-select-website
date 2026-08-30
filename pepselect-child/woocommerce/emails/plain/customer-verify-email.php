<?php
/** Customer email verification (plain text). @version 11.0.0 */
defined( 'ABSPATH' ) || exit;

echo "==========\n";
echo esc_html__( 'CONFIRM YOUR EMAIL ADDRESS', 'pepselect-child' ) . "\n";
echo "==========\n\n";
printf( esc_html__( 'Hi %s,', 'pepselect-child' ), esc_html( $user_display_name ? $user_display_name : __( 'there', 'pepselect-child' ) ) );
echo "\n\n" . esc_html__( 'Confirm this email address to finish setting up your Pep Select account.', 'pepselect-child' ) . "\n\n";
printf( esc_html__( 'Email address: %s', 'pepselect-child' ), esc_html( $user_email ) );
echo "\n\n" . esc_html__( 'Confirm email address:', 'pepselect-child' ) . "\n" . esc_url( $verify_url ) . "\n\n";
echo esc_html__( 'If you did not create this account, you can ignore this email.', 'pepselect-child' ) . "\n";
if ( ! empty( $additional_content ) ) { echo "\n" . wp_strip_all_tags( wptexturize( $additional_content ) ) . "\n"; }

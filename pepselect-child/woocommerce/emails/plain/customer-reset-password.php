<?php
/** Customer reset password email (plain text). @version 10.9.0 */
defined( 'ABSPATH' ) || exit;

$pep_reset_url = add_query_arg(
	array( 'key' => $reset_key, 'id' => $user_id, 'login' => rawurlencode( $user_login ) ),
	wc_get_endpoint_url( 'lost-password', '', wc_get_page_permalink( 'myaccount' ) )
);

echo "==========\n";
echo esc_html__( 'RESET YOUR PASSWORD', 'pepselect-child' ) . "\n";
echo "==========\n\n";
printf( esc_html__( 'Hi %s,', 'pepselect-child' ), esc_html( $user_display_name ? $user_display_name : $user_login ) );
echo "\n\n" . esc_html__( 'We received a request to reset the password for your Pep Select account.', 'pepselect-child' ) . "\n\n";
printf( esc_html__( 'Account: %s', 'pepselect-child' ), esc_html( $user_login ) );
echo "\n\n" . esc_html__( 'Choose a new password:', 'pepselect-child' ) . "\n" . esc_url( $pep_reset_url ) . "\n\n";
echo esc_html__( 'If you did not request this, you can ignore this email. Your password will not change unless the link above is used.', 'pepselect-child' ) . "\n";
if ( ! empty( $additional_content ) ) { echo "\n" . wp_strip_all_tags( wptexturize( $additional_content ) ) . "\n"; }

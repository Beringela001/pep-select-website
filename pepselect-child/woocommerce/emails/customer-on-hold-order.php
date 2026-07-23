<?php
/**
 * Customer on-hold order email — Pep Select child theme override.
 *
 * Based on the WooCommerce 10.4.0 template. Moved here from
 * hello-elementor/woocommerce/emails/ so parent-theme updates cannot
 * remove the Square payment block. Styled to the storefront: navy headings,
 * cyan Square button, amber exact-amount block.
 *
 * Email-client safe: table layout, inline styles, no flexbox or grid.
 *
 * @package PepSelect_Child\WooCommerce\Templates\Emails
 * @version 10.4.0
 */

use Automattic\WooCommerce\Utilities\FeaturesUtil;

defined( 'ABSPATH' ) || exit;

$email_improvements_enabled = FeaturesUtil::feature_is_enabled( 'email_improvements' );

$pep = function_exists( 'pepselect_child_email_tokens' ) ? pepselect_child_email_tokens() : array(
	'navy'        => '#002A53',
	'ink'         => '#001D3A',
	'slate'       => '#5E6F80',
	'cyan'        => '#17A1CF',
	'amber'       => '#B46A00',
	'amber_soft'  => '#FDF6EA',
	'amber_ink'   => '#5C3A00',
	'border'      => '#D7E1E9',
	'white'       => '#FFFFFF',
	'font'        => "'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif",
	'font_mono'   => "'IBM Plex Mono', 'SFMono-Regular', Consolas, 'Liberation Mono', 'Courier New', Courier, monospace",
	'radius_pill' => '999px',
	'radius'      => '8px',
);

$pep_square_link = 'https://square.link/u/QUyZwLLC';
$pep_body        = 'font-family:' . $pep['font'] . ';font-size:15px;line-height:1.7;color:' . $pep['ink'] . ';';

/*
 * @hooked WC_Emails::email_header()
 */
do_action( 'woocommerce_email_header', $email_heading, $email );
?>

<?php echo $email_improvements_enabled ? '<div class="email-introduction">' : ''; ?>

<p style="<?php echo esc_attr( $pep_body ); ?>">
<?php
if ( ! empty( $order->get_billing_first_name() ) ) {
	printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $order->get_billing_first_name() ) );
} else {
	esc_html_e( 'Hi,', 'woocommerce' );
}
?>
</p>

<p style="<?php echo esc_attr( $pep_body ); ?>">
	Thank you for your order. It has been received and is <strong>on hold</strong> until your payment clears.
</p>

<!-- PAYMENT BOX -->
<table cellpadding="0" cellspacing="0" border="0" width="100%" role="presentation" style="margin:30px 0;border:1px solid <?php echo esc_attr( $pep['border'] ); ?>;border-radius:12px;background:<?php echo esc_attr( $pep['white'] ); ?>;">
	<tr>
		<td style="padding:30px;font-family:<?php echo esc_attr( $pep['font'] ); ?>;color:<?php echo esc_attr( $pep['ink'] ); ?>;">

			<h2 style="margin:0 0 12px;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:22px;font-weight:600;line-height:1.3;color:<?php echo esc_attr( $pep['navy'] ); ?>;">
				Complete your payment
			</h2>

			<p style="margin:0 0 20px;<?php echo esc_attr( $pep_body ); ?>">
				To complete your purchase, submit your payment through our secure Square payment link.
			</p>

			<!-- EXACT AMOUNT REMINDER -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%" role="presentation" style="margin:0 0 5px;border:1px solid <?php echo esc_attr( $pep['amber'] ); ?>;border-left:4px solid <?php echo esc_attr( $pep['amber'] ); ?>;border-radius:<?php echo esc_attr( $pep['radius'] ); ?>;background:<?php echo esc_attr( $pep['amber_soft'] ); ?>;">
				<tr>
					<td style="padding:18px 22px;font-family:<?php echo esc_attr( $pep['font'] ); ?>;color:<?php echo esc_attr( $pep['amber_ink'] ); ?>;">
						<p style="margin:0 0 8px;font-size:15px;line-height:1.6;">
							When you open the payment link, enter your order total exactly:
						</p>
						<p style="margin:0 0 8px;font-size:24px;font-weight:600;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;color:<?php echo esc_attr( $pep['amber'] ); ?>;">
							<?php echo wp_kses_post( $order->get_formatted_order_total() ); ?>
						</p>
						<p style="margin:0;font-size:13px;line-height:1.6;">
							Payments that do not match your order total cannot be processed and will delay your order.
						</p>
					</td>
				</tr>
			</table>

			<table align="center" cellpadding="0" cellspacing="0" border="0" role="presentation" style="margin:28px auto;">
				<tr>
					<td bgcolor="<?php echo esc_attr( $pep['cyan'] ); ?>" style="border-radius:<?php echo esc_attr( $pep['radius_pill'] ); ?>;">
						<a href="<?php echo esc_url( $pep_square_link ); ?>"
						   target="_blank"
						   style="display:inline-block;padding:14px 34px;font-family:<?php echo esc_attr( $pep['font'] ); ?>;color:<?php echo esc_attr( $pep['white'] ); ?>;font-size:16px;font-weight:600;text-decoration:none;border-radius:<?php echo esc_attr( $pep['radius_pill'] ); ?>;">
							Complete payment
						</a>
					</td>
				</tr>
			</table>

			<p style="margin:20px 0 10px;<?php echo esc_attr( $pep_body ); ?>">
				If the button above does not work, use this link:
			</p>

			<p style="margin:0;word-break:break-all;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:13px;">
				<a href="<?php echo esc_url( $pep_square_link ); ?>" target="_blank" style="color:<?php echo esc_attr( $pep['cyan'] ); ?>;">
					<?php echo esc_html( $pep_square_link ); ?>
				</a>
			</p>

			<hr style="border:none;border-top:1px solid <?php echo esc_attr( $pep['border'] ); ?>;margin:25px 0;">

			<p style="margin:0 0 15px;<?php echo esc_attr( $pep_body ); ?>">
				Once your payment is submitted, our team reviews and verifies it. After confirmation, your order is processed and you receive another email with your updated order status.
			</p>

			<p style="margin:0 0 15px;<?php echo esc_attr( $pep_body ); ?>">
				<strong>Important:</strong> Your payment will appear as
				<strong>&ldquo;3BS Holdings LLC&rdquo;</strong>, our verified Square business account.
			</p>

			<p style="margin:0;<?php echo esc_attr( $pep_body ); ?>">
				If you have any questions, contact us at
				<a href="mailto:support@pepselect.com" style="color:<?php echo esc_attr( $pep['cyan'] ); ?>;">support@pepselect.com</a>.
			</p>

		</td>
	</tr>
</table>

<?php echo $email_improvements_enabled ? '</div>' : ''; ?>

<?php
/*
 * Order Details
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/*
 * Order Meta
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/*
 * Customer Details
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/*
 * Additional Content
 */
if ( $additional_content ) {
	echo $email_improvements_enabled
		? '<table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation"><tr><td class="email-additional-content">'
		: '';

	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );

	echo $email_improvements_enabled
		? '</td></tr></table>'
		: '';
}

/*
 * Footer
 */
do_action( 'woocommerce_email_footer', $email );

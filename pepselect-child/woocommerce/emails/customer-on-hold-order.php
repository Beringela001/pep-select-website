<?php
/**
 * Customer on-hold order email — Pep Select child theme override.
 *
 * Based on the WooCommerce 10.4.0 template. Moved here from
 * hello-elementor/woocommerce/emails/ so parent-theme updates cannot
 * remove the Square payment block. Adds a live exact-amount reminder
 * matching the checkout and order-received amber treatment.
 *
 * @package PepSelect_Child\WooCommerce\Templates\Emails
 * @version 10.4.0
 */

use Automattic\WooCommerce\Utilities\FeaturesUtil;

defined( 'ABSPATH' ) || exit;

$email_improvements_enabled = FeaturesUtil::feature_is_enabled( 'email_improvements' );

/*
 * @hooked WC_Emails::email_header()
 */
do_action( 'woocommerce_email_header', $email_heading, $email );
?>

<?php echo $email_improvements_enabled ? '<div class="email-introduction">' : ''; ?>

<p>
<?php
if ( ! empty( $order->get_billing_first_name() ) ) {
	printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $order->get_billing_first_name() ) );
} else {
	esc_html_e( 'Hi,', 'woocommerce' );
}
?>
</p>

<p>
	Thank you for your order. It has been received and is <strong>on hold</strong> until your payment clears.
</p>

<!-- PAYMENT BOX -->
<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:30px 0;border:1px solid #e5e5e5;border-radius:8px;background:#fafafa;">
	<tr>
		<td style="padding:30px;font-family:Arial,Helvetica,sans-serif;color:#333333;">

			<h2 style="margin:0 0 15px;font-size:24px;color:#222222;">
				Complete Your Payment
			</h2>

			<p style="margin:0 0 20px;font-size:16px;line-height:1.7;">
				To complete your purchase, submit your payment through our secure Square payment link.
			</p>

			<!-- EXACT AMOUNT REMINDER -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:0 0 5px;border:1px solid #B46A00;border-left:4px solid #B46A00;border-radius:6px;background:#FDF6EA;">
				<tr>
					<td style="padding:16px 20px;font-family:Arial,Helvetica,sans-serif;color:#5C3A00;">
						<p style="margin:0 0 8px;font-size:15px;line-height:1.6;">
							When you open the payment link, enter your order total exactly:
						</p>
						<p style="margin:0 0 8px;font-size:22px;font-weight:bold;font-family:'Courier New',Courier,monospace;color:#B46A00;">
							<?php echo wp_kses_post( $order->get_formatted_order_total() ); ?>
						</p>
						<p style="margin:0;font-size:13px;line-height:1.6;">
							Payments that do not match your order total cannot be processed and will delay your order.
						</p>
					</td>
				</tr>
			</table>

			<table align="center" cellpadding="0" cellspacing="0" border="0" style="margin:25px auto;">
				<tr>
					<td bgcolor="#17A1CF" style="border-radius:999px;">
						<a href="https://square.link/u/QUyZwLLC"
						   target="_blank"
						   style="display:inline-block;padding:14px 34px;color:#ffffff;font-size:16px;font-weight:bold;text-decoration:none;">
							Complete Payment
						</a>
					</td>
				</tr>
			</table>

			<p style="margin:20px 0 10px;font-size:15px;">
				If the button above does not work, use this link:
			</p>

			<p style="word-break:break-all;">
				<a href="https://square.link/u/QUyZwLLC" target="_blank">
					https://square.link/u/QUyZwLLC
				</a>
			</p>

			<hr style="border:none;border-top:1px solid #e5e5e5;margin:25px 0;">

			<p style="margin:0 0 15px;font-size:15px;line-height:1.7;">
				Once your payment is submitted, our team reviews and verifies it. After confirmation, your order is processed and you receive another email with your updated order status.
			</p>

			<p style="margin:0 0 15px;font-size:15px;line-height:1.7;">
				<strong>Important:</strong> Your payment will appear as
				<strong>&ldquo;3BS Holdings LLC&rdquo;</strong>, our verified Square business account.
			</p>

			<p style="margin:0;font-size:15px;line-height:1.7;">
				If you have any questions, contact us at
				<a href="mailto:support@pepselect.com">support@pepselect.com</a>.
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

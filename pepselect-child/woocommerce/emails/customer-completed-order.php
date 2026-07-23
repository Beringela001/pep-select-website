<?php
/**
 * Customer completed order email — Pep Select child theme override.
 *
 * Based on the WooCommerce 10.4.0 template. Adds a shipment tracking block
 * matching the on-hold email's styling. Tracking is resolved from the order by
 * pepselect_child_get_order_tracking(); when no tracking is present the block
 * is omitted entirely and the email reads as the standard completed notice.
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
	'cyan_soft'   => '#E8F6FB',
	'border'      => '#D7E1E9',
	'white'       => '#FFFFFF',
	'font'        => "'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif",
	'font_mono'   => "'IBM Plex Mono', 'SFMono-Regular', Consolas, 'Liberation Mono', 'Courier New', Courier, monospace",
	'radius_pill' => '999px',
	'radius'      => '8px',
);

$pep_body     = 'font-family:' . $pep['font'] . ';font-size:15px;line-height:1.7;color:' . $pep['ink'] . ';';
$pep_tracking = function_exists( 'pepselect_child_get_order_tracking' )
	? pepselect_child_get_order_tracking( $order )
	: array(
		'number'  => '',
		'carrier' => '',
		'url'     => '',
		'source'  => '',
	);

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
	<?php if ( '' !== $pep_tracking['number'] ) : ?>
		Your order is complete and on its way. Tracking details are below.
	<?php else : ?>
		Your order is complete. Thank you for your business.
	<?php endif; ?>
</p>

<?php if ( '' !== $pep_tracking['number'] ) : ?>
	<!-- SHIPMENT TRACKING -->
	<table cellpadding="0" cellspacing="0" border="0" width="100%" role="presentation" style="margin:30px 0;border:1px solid <?php echo esc_attr( $pep['cyan'] ); ?>;border-radius:12px;background:<?php echo esc_attr( $pep['cyan_soft'] ); ?>;">
		<tr>
			<td style="padding:26px 30px;font-family:<?php echo esc_attr( $pep['font'] ); ?>;color:<?php echo esc_attr( $pep['ink'] ); ?>;">

				<h2 style="margin:0 0 14px;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:20px;font-weight:600;line-height:1.3;color:<?php echo esc_attr( $pep['navy'] ); ?>;">
					Track your shipment
				</h2>

				<?php if ( '' !== $pep_tracking['carrier'] ) : ?>
					<p style="margin:0 0 6px;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:12px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;color:<?php echo esc_attr( $pep['slate'] ); ?>;">
						Carrier
					</p>
					<p style="margin:0 0 16px;<?php echo esc_attr( $pep_body ); ?>">
						<?php echo esc_html( $pep_tracking['carrier'] ); ?>
					</p>
				<?php endif; ?>

				<p style="margin:0 0 6px;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:12px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;color:<?php echo esc_attr( $pep['slate'] ); ?>;">
					Tracking number
				</p>
				<p style="margin:0 0 4px;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:20px;font-weight:600;color:<?php echo esc_attr( $pep['navy'] ); ?>;word-break:break-all;">
					<?php echo esc_html( $pep_tracking['number'] ); ?>
				</p>

				<?php if ( '' !== $pep_tracking['url'] ) : ?>
					<table align="left" cellpadding="0" cellspacing="0" border="0" role="presentation" style="margin:22px 0 6px;">
						<tr>
							<td bgcolor="<?php echo esc_attr( $pep['cyan'] ); ?>" style="border-radius:<?php echo esc_attr( $pep['radius_pill'] ); ?>;">
								<a href="<?php echo esc_url( $pep_tracking['url'] ); ?>"
								   target="_blank"
								   style="display:inline-block;padding:13px 30px;font-family:<?php echo esc_attr( $pep['font'] ); ?>;color:<?php echo esc_attr( $pep['white'] ); ?>;font-size:15px;font-weight:600;text-decoration:none;border-radius:<?php echo esc_attr( $pep['radius_pill'] ); ?>;">
									Track your order
								</a>
							</td>
						</tr>
					</table>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation"><tr><td style="height:70px;line-height:70px;font-size:0;">&nbsp;</td></tr></table>
					<p style="margin:0;word-break:break-all;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:13px;">
						<a href="<?php echo esc_url( $pep_tracking['url'] ); ?>" target="_blank" style="color:<?php echo esc_attr( $pep['cyan'] ); ?>;">
							<?php echo esc_html( $pep_tracking['url'] ); ?>
						</a>
					</p>
				<?php else : ?>
					<p style="margin:12px 0 0;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:14px;line-height:1.6;color:<?php echo esc_attr( $pep['slate'] ); ?>;">
						Enter this number on the carrier's website to follow your shipment.
					</p>
				<?php endif; ?>

			</td>
		</tr>
	</table>
	<?php
	// Names the meta key the tracking was read from. Visible only in the email
	// source, so the resolved key can be confirmed on a real shipped order.
	echo "\n<!-- pepselect tracking source: " . esc_html( $pep_tracking['source'] ) . " -->\n";
	?>
<?php endif; ?>

<p style="<?php echo esc_attr( $pep_body ); ?>">
	If you have any questions, contact us at
	<a href="mailto:support@pepselect.com" style="color:<?php echo esc_attr( $pep['cyan'] ); ?>;">support@pepselect.com</a>.
</p>

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

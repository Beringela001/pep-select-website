<?php
/**
 * Admin new-order email — Pep Select operational canvas.
 *
 * WooCommerce remains authoritative for the order date, products, images,
 * quantities, totals, customer details, payment, shipping, and order status.
 *
 * @package WooCommerce\Templates\Emails
 * @version 10.9.0
 */

defined( 'ABSPATH' ) || exit;

$pep = function_exists( 'pepselect_child_email_tokens' ) ? pepselect_child_email_tokens() : array(
	'navy' => '#002A53', 'ink' => '#001D3A', 'slate' => '#5E6F80', 'neutral' => '#7A8793',
	'cyan' => '#17A1CF', 'cyan_soft' => '#E8F6FB', 'border' => '#D7E1E9', 'white' => '#FFFFFF',
	'font' => "'Plus Jakarta Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif",
	'font_mono' => "'IBM Plex Mono','SFMono-Regular',Consolas,'Liberation Mono','Courier New',Courier,monospace",
);

$pep_logo_url       = 'https://pepselect.com/wp-content/uploads/2026/06/Logo_Pepselect_Whitebackground-1.png';
$pep_order_number   = $order->get_order_number();
$pep_created        = $order->get_date_created();
$pep_order_date     = $pep_created ? wc_format_datetime( $pep_created, 'M j, Y' ) : '';
$pep_order_time     = $pep_created ? wc_format_datetime( $pep_created, 'g:i A' ) : '';
$pep_order_items    = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$pep_order_totals   = $order->get_order_item_totals();
$pep_customer_name  = trim( (string) $order->get_formatted_billing_full_name() );
$pep_customer_name  = '' !== $pep_customer_name ? $pep_customer_name : __( 'Customer', 'pepselect-child' );
$pep_payment_method = trim( (string) $order->get_payment_method_title() );
$pep_shipping       = trim( (string) $order->get_shipping_method() );
$pep_status         = wc_get_order_status_name( $order->get_status() );
$pep_edit_url       = method_exists( $order, 'get_edit_order_url' ) ? $order->get_edit_order_url() : admin_url( 'post.php?post=' . $order->get_id() . '&action=edit' );
$pep_billing        = $order->get_formatted_billing_address();
$pep_shipping_addr  = $order->get_formatted_shipping_address();
$pep_shipping_addr  = '' !== $pep_shipping_addr ? $pep_shipping_addr : $pep_billing;
$pep_customer_note  = trim( (string) $order->get_customer_note() );
$pep_coupon_codes   = method_exists( $order, 'get_coupon_codes' ) ? $order->get_coupon_codes() : array();

/* Read compatible order metadata without inheriting third-party email markup. */
$pep_email_meta     = apply_filters( 'woocommerce_email_order_meta_fields', array(), true, $order );
$pep_research_value = trim( (string) $order->get_meta( '_research_purpose' ) );
$pep_points_value   = '';

foreach ( (array) $pep_email_meta as $pep_meta_field ) {
	$pep_meta_label = isset( $pep_meta_field['label'] ) ? wp_strip_all_tags( (string) $pep_meta_field['label'] ) : '';
	$pep_meta_value = isset( $pep_meta_field['value'] ) ? wp_strip_all_tags( (string) $pep_meta_field['value'] ) : '';

	if ( '' === $pep_research_value && preg_match( '/research purpose/i', $pep_meta_label ) ) {
		$pep_research_value = trim( $pep_meta_value );
	}

	if ( '' === $pep_points_value && preg_match( '/point/i', $pep_meta_label ) && preg_match( '/[0-9]+/', $pep_meta_value, $pep_point_match ) ) {
		$pep_points_value = $pep_point_match[0];
	}
}

if ( '' === $pep_points_value ) {
	foreach ( array( '_ywpar_points_earned', 'ywpar_points_earned', '_ywpar_points_from_cart', 'ywpar_points_from_cart' ) as $pep_points_key ) {
		$pep_points_candidate = $order->get_meta( $pep_points_key );
		if ( is_numeric( $pep_points_candidate ) && (float) $pep_points_candidate > 0 ) {
			$pep_points_value = (string) absint( $pep_points_candidate );
			break;
		}
	}
}
?>
<!doctype html>
<html lang="<?php echo esc_attr( get_bloginfo( 'language' ) ); ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo esc_attr( get_bloginfo( 'charset' ) ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="color-scheme" content="light">
	<meta name="supported-color-schemes" content="light">
	<title><?php printf( esc_html__( 'New order #%s', 'pepselect-child' ), esc_html( $pep_order_number ) ); ?></title>
	<style type="text/css">
		body,table,td,a,p,h1,h2,h3{-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}
		table,td{mso-table-lspace:0;mso-table-rspace:0}table{border-collapse:separate;border-spacing:0}
		img{-ms-interpolation-mode:bicubic;border:0;display:block;height:auto;line-height:100%;outline:none;text-decoration:none}
		@media only screen and (max-width:520px){
			.pep-admin-outer-pad{padding:8px!important}.pep-admin-card{border-radius:18px!important}
			.pep-admin-header{padding:28px 22px 22px!important}.pep-admin-main{padding:0 22px 28px!important}.pep-admin-footer{padding:0 22px 28px!important}
			.pep-admin-heading{font-size:27px!important;letter-spacing:-.6px!important}.pep-admin-desktop-only{display:none!important;font-size:0!important;line-height:0!important;max-height:0!important;mso-hide:all!important;overflow:hidden!important}
			.pep-admin-stat,.pep-admin-address{box-sizing:border-box!important;display:block!important;padding-left:0!important;padding-right:0!important;width:100%!important}
			.pep-admin-stat+.pep-admin-stat,.pep-admin-address+.pep-admin-address{padding-top:8px!important}
			.pep-admin-product-image{width:48px!important}.pep-admin-product-main{padding-left:10px!important}.pep-admin-product-price{width:76px!important}
			.pep-admin-button{display:block!important;width:auto!important}.pep-admin-button-table{width:100%!important}
		}
	</style>
</head>
<body style="background-color:#E9EEF4;margin:0;min-width:100%;padding:0;width:100%;">
	<div aria-hidden="true" style="font-size:1px;line-height:1px;max-height:1px;opacity:0;overflow:hidden;mso-hide:all;">
		<?php printf( esc_html__( 'New order #%1$s from %2$s. Total: %3$s.', 'pepselect-child' ), esc_html( $pep_order_number ), esc_html( $pep_customer_name ), wp_kses_post( $order->get_formatted_order_total() ) ); ?>
	</div>
	<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="background-color:#E9EEF4;width:100%;">
		<tr><td align="center" class="pep-admin-outer-pad" style="padding:32px 16px;">
			<!--[if mso]><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="680"><tr><td><![endif]-->
			<table border="0" cellpadding="0" cellspacing="0" class="pep-admin-card" role="presentation" width="100%" style="background-color:<?php echo esc_attr( $pep['white'] ); ?>;border-radius:18px;box-shadow:0 18px 46px rgba(0,42,83,.14);max-width:680px;overflow:hidden;width:100%;">
				<tr><td style="font-size:0;line-height:0;padding:0;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%"><tr><td bgcolor="<?php echo esc_attr( $pep['navy'] ); ?>" height="6" style="background-color:<?php echo esc_attr( $pep['navy'] ); ?>;border-radius:18px 0 0 0;font-size:0;height:6px;line-height:6px;width:75%;">&nbsp;</td><td bgcolor="<?php echo esc_attr( $pep['cyan'] ); ?>" height="6" style="background-color:<?php echo esc_attr( $pep['cyan'] ); ?>;border-radius:0 18px 0 0;font-size:0;height:6px;line-height:6px;width:25%;">&nbsp;</td></tr></table></td></tr>
				<tr><td class="pep-admin-header" style="padding:38px 44px 28px;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%"><tr><td align="left" valign="middle"><img alt="Pep Select" src="<?php echo esc_url( $pep_logo_url ); ?>" width="132" style="height:auto;max-width:132px;width:132px;"></td><td align="right" class="pep-admin-desktop-only" style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:10px;font-weight:600;letter-spacing:1.4px;line-height:1.4;text-transform:uppercase;" valign="middle"><?php esc_html_e( 'Admin order alert', 'pepselect-child' ); ?></td></tr><tr><td colspan="2" style="border-bottom:1px solid <?php echo esc_attr( $pep['border'] ); ?>;font-size:0;line-height:0;padding-top:28px;">&nbsp;</td></tr></table></td></tr>
				<tr><td class="pep-admin-main" style="padding:0 44px 34px;">
					<p style="color:#0D708E;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:10px;font-weight:700;letter-spacing:1.5px;line-height:1.5;margin:0 0 12px;text-transform:uppercase;"><?php printf( esc_html__( 'New order · #%s', 'pepselect-child' ), esc_html( $pep_order_number ) ); ?></p>
					<h1 class="pep-admin-heading" style="color:<?php echo esc_attr( $pep['ink'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:32px;font-weight:750;letter-spacing:-.9px;line-height:1.18;margin:0 0 10px;"><?php esc_html_e( 'New order received', 'pepselect-child' ); ?></h1>
					<p style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:15px;line-height:1.55;margin:0 0 22px;"><?php printf( esc_html__( 'Placed %1$s at %2$s by %3$s.', 'pepselect-child' ), esc_html( $pep_order_date ), esc_html( $pep_order_time ), esc_html( $pep_customer_name ) ); ?></p>

					<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="margin:0 0 14px;"><tr>
						<td class="pep-admin-stat" style="padding-right:5px;width:33.333%;" valign="top" width="33.333%"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="background-color:#F8FAFC;border:1px solid <?php echo esc_attr( $pep['border'] ); ?>;border-radius:8px;"><tr><td style="padding:14px 15px;"><p style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:9px;letter-spacing:1.2px;line-height:1.4;margin:0 0 5px;text-transform:uppercase;"><?php esc_html_e( 'Order total', 'pepselect-child' ); ?></p><p style="color:<?php echo esc_attr( $pep['navy'] ); ?>;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:18px;font-weight:700;line-height:1.35;margin:0;"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></p></td></tr></table></td>
						<td class="pep-admin-stat" style="padding-left:5px;padding-right:5px;width:33.333%;" valign="top" width="33.333%"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="background-color:#F8FAFC;border:1px solid <?php echo esc_attr( $pep['border'] ); ?>;border-radius:8px;"><tr><td style="padding:14px 15px;"><p style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:9px;letter-spacing:1.2px;line-height:1.4;margin:0 0 5px;text-transform:uppercase;"><?php esc_html_e( 'Status', 'pepselect-child' ); ?></p><p style="color:<?php echo esc_attr( $pep['navy'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:14px;font-weight:700;line-height:1.35;margin:0;"><?php echo esc_html( $pep_status ); ?></p></td></tr></table></td>
						<td class="pep-admin-stat" style="padding-left:5px;width:33.333%;" valign="top" width="33.333%"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="background-color:#F8FAFC;border:1px solid <?php echo esc_attr( $pep['border'] ); ?>;border-radius:8px;"><tr><td style="padding:14px 15px;"><p style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:9px;letter-spacing:1.2px;line-height:1.4;margin:0 0 5px;text-transform:uppercase;"><?php esc_html_e( 'Payment', 'pepselect-child' ); ?></p><p style="color:<?php echo esc_attr( $pep['navy'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:14px;font-weight:700;line-height:1.35;margin:0;"><?php echo esc_html( '' !== $pep_payment_method ? $pep_payment_method : __( 'Not specified', 'pepselect-child' ) ); ?></p></td></tr></table></td>
					</tr></table>

					<table border="0" cellpadding="0" cellspacing="0" class="pep-admin-button-table" role="presentation" style="margin:0 0 24px;"><tr><td align="center" bgcolor="<?php echo esc_attr( $pep['cyan'] ); ?>" style="background-color:<?php echo esc_attr( $pep['cyan'] ); ?>;border-radius:999px;"><a class="pep-admin-button" href="<?php echo esc_url( $pep_edit_url ); ?>" style="border:1px solid <?php echo esc_attr( $pep['cyan'] ); ?>;border-radius:999px;color:#FFFFFF;display:block;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:14px;font-weight:700;line-height:20px;padding:13px 24px;text-align:center;text-decoration:none;" target="_blank"><?php esc_html_e( 'Review order in WooCommerce', 'pepselect-child' ); ?></a></td></tr></table>

					<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="background-color:#F8FAFC;border:1px solid <?php echo esc_attr( $pep['border'] ); ?>;border-radius:8px;margin:0 0 16px;overflow:hidden;"><tr><td style="border-bottom:1px solid <?php echo esc_attr( $pep['border'] ); ?>;padding:18px 20px;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%"><tr><td><h2 style="color:<?php echo esc_attr( $pep['navy'] ); ?>;font-family:Georgia,'Times New Roman',serif;font-size:21px;font-weight:700;line-height:1.3;margin:0;"><?php esc_html_e( 'Order summary', 'pepselect-child' ); ?></h2></td><td align="right" style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:10px;line-height:1.5;"><?php printf( esc_html__( '#%1$s · %2$s', 'pepselect-child' ), esc_html( $pep_order_number ), esc_html( $pep_order_date ) ); ?></td></tr></table></td></tr>
						<?php foreach ( $pep_order_items as $pep_item_id => $pep_item ) : ?>
							<?php
							$pep_product     = $pep_item->get_product();
							$pep_image_id    = $pep_product ? $pep_product->get_image_id() : 0;
							$pep_image_url   = $pep_image_id ? wp_get_attachment_image_url( $pep_image_id, 'woocommerce_thumbnail' ) : wc_placeholder_img_src( 'woocommerce_thumbnail' );
							$pep_sku         = $pep_product ? $pep_product->get_sku() : '';
							$pep_item_meta   = wc_display_item_meta( $pep_item, array( 'echo' => false, 'label_before' => '', 'label_after' => ': ', 'separator' => '<br>' ) );
							$pep_line_total  = $order->get_formatted_line_subtotal( $pep_item );
							?>
							<tr><td style="border-bottom:1px solid <?php echo esc_attr( $pep['border'] ); ?>;padding:14px 20px;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%"><tr>
								<td class="pep-admin-product-image" style="width:54px;" valign="top" width="54"><img alt="" src="<?php echo esc_url( $pep_image_url ); ?>" width="46" style="background-color:#EEF3F6;border-radius:6px;height:46px;object-fit:contain;width:46px;"></td>
								<td class="pep-admin-product-main" style="padding-left:12px;" valign="middle"><p style="color:<?php echo esc_attr( $pep['navy'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:14px;font-weight:700;line-height:1.4;margin:0 0 3px;"><?php echo esc_html( $pep_item->get_name() ); ?></p><?php if ( '' !== $pep_sku ) : ?><p style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:9px;line-height:1.4;margin:0 0 2px;"><?php printf( esc_html__( 'SKU %s', 'pepselect-child' ), esc_html( $pep_sku ) ); ?></p><?php endif; ?><?php if ( '' !== trim( wp_strip_all_tags( $pep_item_meta ) ) ) : ?><div style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:11px;line-height:1.45;"><?php echo wp_kses_post( $pep_item_meta ); ?></div><?php endif; ?></td>
								<td align="right" class="pep-admin-product-price" style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:11px;line-height:1.45;width:92px;" valign="middle" width="92"><?php printf( esc_html__( '×%s', 'pepselect-child' ), esc_html( $pep_item->get_quantity() ) ); ?><br><strong style="color:<?php echo esc_attr( $pep['navy'] ); ?>;font-size:12px;"><?php echo wp_kses_post( $pep_line_total ); ?></strong></td>
							</tr></table></td></tr>
						<?php endforeach; ?>
						<tr><td style="padding:15px 20px 17px;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
							<?php foreach ( $pep_order_totals as $pep_total_key => $pep_total ) : ?>
								<?php $pep_is_total = 'order_total' === $pep_total_key; ?>
								<tr><td style="<?php echo $pep_is_total ? 'border-top:1px solid ' . esc_attr( $pep['border'] ) . ';padding-top:11px;' : ''; ?>color:<?php echo esc_attr( $pep_is_total ? $pep['navy'] : $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:<?php echo $pep_is_total ? '14' : '12'; ?>px;font-weight:<?php echo $pep_is_total ? '700' : '400'; ?>;line-height:1.5;padding-bottom:5px;"><?php echo wp_kses_post( $pep_total['label'] ); ?></td><td align="right" style="<?php echo $pep_is_total ? 'border-top:1px solid ' . esc_attr( $pep['border'] ) . ';padding-top:11px;' : ''; ?>color:<?php echo esc_attr( $pep['navy'] ); ?>;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:<?php echo $pep_is_total ? '15' : '12'; ?>px;font-weight:<?php echo $pep_is_total ? '700' : '500'; ?>;line-height:1.5;padding-bottom:5px;"><?php echo wp_kses_post( $pep_total['value'] ); ?></td></tr>
							<?php endforeach; ?>
						</table></td></tr>
						<?php if ( '' !== $pep_research_value || '' !== $pep_points_value || ! empty( $pep_coupon_codes ) ) : ?>
							<tr><td style="border-top:1px solid <?php echo esc_attr( $pep['border'] ); ?>;padding:12px 20px;"><p style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:11px;line-height:1.55;margin:0;"><?php if ( '' !== $pep_research_value ) : ?><strong style="color:<?php echo esc_attr( $pep['navy'] ); ?>;"><?php esc_html_e( 'Research purpose:', 'pepselect-child' ); ?></strong> <?php echo esc_html( $pep_research_value ); ?><?php endif; ?><?php if ( '' !== $pep_points_value ) : ?><?php echo '' !== $pep_research_value ? ' · ' : ''; ?><strong style="color:<?php echo esc_attr( $pep['navy'] ); ?>;"><?php esc_html_e( 'Points:', 'pepselect-child' ); ?></strong> <?php echo esc_html( $pep_points_value ); ?><?php endif; ?><?php if ( ! empty( $pep_coupon_codes ) ) : ?><?php echo ( '' !== $pep_research_value || '' !== $pep_points_value ) ? ' · ' : ''; ?><strong style="color:<?php echo esc_attr( $pep['navy'] ); ?>;"><?php esc_html_e( 'Coupons:', 'pepselect-child' ); ?></strong> <?php echo esc_html( implode( ', ', $pep_coupon_codes ) ); ?><?php endif; ?></p></td></tr>
						<?php endif; ?>
					</table>

					<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="margin:0 0 16px;"><tr>
						<td class="pep-admin-address" style="padding-right:6px;width:50%;" valign="top" width="50%"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="border:1px solid <?php echo esc_attr( $pep['border'] ); ?>;border-radius:8px;"><tr><td style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:13px;line-height:1.45;padding:17px;"><h3 style="color:<?php echo esc_attr( $pep['navy'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:15px;line-height:1.4;margin:0 0 7px;"><?php esc_html_e( 'Billing and contact', 'pepselect-child' ); ?></h3><?php echo wp_kses_post( $pep_billing ); ?><?php if ( $order->get_billing_email() ) : ?><br><a href="mailto:<?php echo esc_attr( $order->get_billing_email() ); ?>" style="color:#0D708E;text-decoration:none;"><?php echo esc_html( $order->get_billing_email() ); ?></a><?php endif; ?><?php if ( $order->get_billing_phone() ) : ?><br><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $order->get_billing_phone() ) ); ?>" style="color:#0D708E;text-decoration:none;"><?php echo esc_html( $order->get_billing_phone() ); ?></a><?php endif; ?></td></tr></table></td>
						<td class="pep-admin-address" style="padding-left:6px;width:50%;" valign="top" width="50%"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="border:1px solid <?php echo esc_attr( $pep['border'] ); ?>;border-radius:8px;"><tr><td style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:13px;line-height:1.45;padding:17px;"><h3 style="color:<?php echo esc_attr( $pep['navy'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:15px;line-height:1.4;margin:0 0 7px;"><?php esc_html_e( 'Shipping and fulfillment', 'pepselect-child' ); ?></h3><?php echo wp_kses_post( $pep_shipping_addr ); ?><?php if ( '' !== $pep_shipping ) : ?><br><br><strong style="color:<?php echo esc_attr( $pep['navy'] ); ?>;"><?php esc_html_e( 'Method:', 'pepselect-child' ); ?></strong> <?php echo esc_html( $pep_shipping ); ?><?php endif; ?></td></tr></table></td>
					</tr></table>

					<?php if ( '' !== $pep_customer_note ) : ?>
						<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="background-color:<?php echo esc_attr( $pep['cyan_soft'] ); ?>;border:1px solid <?php echo esc_attr( $pep['cyan'] ); ?>;border-radius:8px;margin:0 0 16px;"><tr><td style="padding:15px 17px;"><p style="color:#0D708E;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:9px;font-weight:700;letter-spacing:1.2px;line-height:1.4;margin:0 0 5px;text-transform:uppercase;"><?php esc_html_e( 'Customer note', 'pepselect-child' ); ?></p><p style="color:<?php echo esc_attr( $pep['ink'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:13px;line-height:1.55;margin:0;"><?php echo nl2br( esc_html( $pep_customer_note ) ); ?></p></td></tr></table>
					<?php endif; ?>

				</td></tr>
				<tr><td class="pep-admin-footer" style="padding:0 44px 34px;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%"><tr><td align="center" style="border-top:1px solid <?php echo esc_attr( $pep['border'] ); ?>;padding-top:22px;"><p style="color:<?php echo esc_attr( $pep['navy'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:12px;font-weight:700;line-height:1.45;margin:0;"><?php printf( esc_html__( 'Pep Select · Order #%s', 'pepselect-child' ), esc_html( $pep_order_number ) ); ?></p></td></tr></table></td></tr>
			</table>
			<!--[if mso]></td></tr></table><![endif]-->
		</td></tr>
	</table>
</body>
</html>

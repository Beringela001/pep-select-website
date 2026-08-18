<?php
/**
 * Customer completed order email — Pep Select child theme override.
 *
 * Owns the responsive shipped-order canvas. WooCommerce remains authoritative
 * for order items, product images, totals, addresses, shipping, and customer
 * data. Tracking still resolves through pepselect_child_get_order_tracking().
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

$pep_support_email = function_exists( 'pepselect_child_email_support_address' ) ? pepselect_child_email_support_address() : 'support@pepselect.com';
$pep_logo_url      = 'https://pepselect.com/wp-content/uploads/2026/06/Logo_Pepselect_Whitebackground-1.png';
$pep_order_number  = $order->get_order_number();
$pep_order_date    = $order->get_date_created() ? wc_format_datetime( $order->get_date_created(), 'M j, Y' ) : '';
$pep_first_name    = trim( (string) $order->get_billing_first_name() );
$pep_first_name    = '' !== $pep_first_name ? $pep_first_name : __( 'there', 'pepselect-child' );
$pep_order_items   = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$pep_order_totals  = $order->get_order_item_totals();
$pep_tracking      = function_exists( 'pepselect_child_get_order_tracking' )
	? pepselect_child_get_order_tracking( $order )
	: array( 'number' => '', 'carrier' => '', 'url' => '', 'source' => '', 'candidates' => '' );

/* Read compatible email metadata without inheriting third-party markup. */
$pep_email_meta     = apply_filters( 'woocommerce_email_order_meta_fields', array(), false, $order );
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

$pep_shipping_address = $order->get_formatted_shipping_address();
if ( '' === $pep_shipping_address ) {
	$pep_shipping_address = $order->get_formatted_billing_address();
}

$pep_shipping_phone = $order->get_shipping_phone() ? $order->get_shipping_phone() : $order->get_billing_phone();
$pep_carrier        = isset( $pep_tracking['carrier'] ) ? trim( (string) $pep_tracking['carrier'] ) : '';
$pep_service        = trim( (string) $order->get_shipping_method() );

/* Split combined labels without replacing the actual carrier or service. */
if ( '' !== $pep_carrier && preg_match( '/^(.+?)\s*(?:-|–|—|·|\|)\s*(.+)$/u', $pep_carrier, $pep_carrier_parts ) ) {
	$pep_carrier = trim( $pep_carrier_parts[1] );
	if ( '' === $pep_service ) {
		$pep_service = trim( $pep_carrier_parts[2] );
	}
}
if ( '' !== $pep_carrier && '' !== $pep_service && 0 === stripos( $pep_service, $pep_carrier ) ) {
	$pep_service = trim( preg_replace( '/^' . preg_quote( $pep_carrier, '/' ) . '\s*(?:-|–|—|·|\|)?\s*/iu', '', $pep_service ) );
}
if ( '' === $pep_carrier && '' !== $pep_service ) {
	if ( preg_match( '/^(.+?)\s*(?:-|–|—|·|\|)\s*(.+)$/u', $pep_service, $pep_service_parts ) ) {
		$pep_carrier = trim( $pep_service_parts[1] );
		$pep_service = trim( $pep_service_parts[2] );
	} else {
		$pep_carrier = $pep_service;
		$pep_service = '';
	}
}

$pep_carrier_combined = implode( ' · ', array_filter( array( $pep_carrier, $pep_service ) ) );
$pep_tracking_number  = isset( $pep_tracking['number'] ) ? trim( (string) $pep_tracking['number'] ) : '';
$pep_tracking_url     = isset( $pep_tracking['url'] ) ? trim( (string) $pep_tracking['url'] ) : '';
?>
<!doctype html>
<html lang="<?php echo esc_attr( get_bloginfo( 'language' ) ); ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo esc_attr( get_bloginfo( 'charset' ) ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="color-scheme" content="light">
	<meta name="supported-color-schemes" content="light">
	<title><?php esc_html_e( 'Thank you. Your order is on the way', 'pepselect-child' ); ?></title>
	<style type="text/css">
		body,table,td,a,p,h1,h2,h3{-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}
		table,td{mso-table-lspace:0;mso-table-rspace:0}table{border-collapse:separate;border-spacing:0}
		img{-ms-interpolation-mode:bicubic;border:0;display:block;height:auto;line-height:100%;outline:none;text-decoration:none}
		.pep-email-mobile-only{display:none;font-size:0;line-height:0;max-height:0;mso-hide:all;overflow:hidden}
		@media only screen and (max-width:520px){
			.pep-email-outer-pad{padding:8px!important}.pep-email-card{border-radius:18px!important}
			.pep-email-header{padding:28px 22px 22px!important}.pep-email-main{padding:0 22px 26px!important}.pep-email-footer{padding:0 22px 28px!important}
			.pep-email-heading{font-size:27px!important;letter-spacing:-.6px!important;line-height:1.18!important}
			.pep-email-tracking-card,.pep-email-address-card{padding-left:18px!important;padding-right:18px!important}
			.pep-email-desktop-only,.pep-email-desktop-addresses{display:none!important;font-size:0!important;line-height:0!important;max-height:0!important;mso-hide:all!important;overflow:hidden!important}
			.pep-email-mobile-only,.pep-email-mobile-addresses{display:block!important;font-size:inherit!important;line-height:inherit!important;max-height:none!important;overflow:visible!important}
			.pep-email-summary-column{box-sizing:border-box!important;display:block!important;width:100%!important}.pep-email-total-column{border-left:0!important;border-top:1px solid <?php echo esc_attr( $pep['border'] ); ?>!important}
			.pep-email-button-table{width:100%!important}.pep-email-button{display:block!important;width:auto!important}
		}
	</style>
</head>
<body style="background-color:#E9EEF4;margin:0;min-width:100%;padding:0;width:100%;">
	<div aria-hidden="true" style="font-size:1px;line-height:1px;max-height:1px;opacity:0;overflow:hidden;mso-hide:all;">
		<?php printf( esc_html__( 'Thank you for choosing Pep Select. Order #%s is on the way.', 'pepselect-child' ), esc_html( $pep_order_number ) ); ?>
	</div>
	<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="background-color:#E9EEF4;width:100%;">
		<tr><td align="center" class="pep-email-outer-pad" style="padding:32px 16px;">
			<!--[if mso]><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="680"><tr><td><![endif]-->
			<table border="0" cellpadding="0" cellspacing="0" class="pep-email-card" role="presentation" width="100%" style="background-color:<?php echo esc_attr( $pep['white'] ); ?>;border-radius:18px;box-shadow:0 18px 46px rgba(0,42,83,.14);max-width:680px;overflow:hidden;width:100%;">
				<tr><td style="font-size:0;line-height:0;padding:0;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%"><tr><td bgcolor="<?php echo esc_attr( $pep['navy'] ); ?>" height="6" style="background-color:<?php echo esc_attr( $pep['navy'] ); ?>;border-radius:18px 0 0 0;font-size:0;height:6px;line-height:6px;width:75%;">&nbsp;</td><td bgcolor="<?php echo esc_attr( $pep['cyan'] ); ?>" height="6" style="background-color:<?php echo esc_attr( $pep['cyan'] ); ?>;border-radius:0 18px 0 0;font-size:0;height:6px;line-height:6px;width:25%;">&nbsp;</td></tr></table></td></tr>
				<tr><td class="pep-email-header" style="padding:38px 44px 28px;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%"><tr><td align="left" valign="middle"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="display:inline-block;text-decoration:none;" target="_blank"><img alt="Pep Select" src="<?php echo esc_url( $pep_logo_url ); ?>" width="132" style="height:auto;max-width:132px;width:132px;"></a></td><td align="right" class="pep-email-desktop-only" style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:10px;font-weight:600;letter-spacing:1.4px;line-height:1.4;text-transform:uppercase;" valign="middle"><?php printf( esc_html__( 'Order #%s', 'pepselect-child' ), esc_html( $pep_order_number ) ); ?></td></tr><tr><td colspan="2" style="border-bottom:1px solid <?php echo esc_attr( $pep['border'] ); ?>;font-size:0;line-height:0;padding-top:28px;">&nbsp;</td></tr></table></td></tr>
				<tr><td class="pep-email-main" style="padding:0 44px 34px;">
					<p style="color:#0D708E;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:10px;font-weight:700;letter-spacing:1.5px;line-height:1.5;margin:0 0 12px;text-transform:uppercase;"><?php printf( esc_html__( 'Order #%s · Shipped', 'pepselect-child' ), esc_html( $pep_order_number ) ); ?></p>
					<h1 class="pep-email-heading" style="color:<?php echo esc_attr( $pep['ink'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:32px;font-weight:750;letter-spacing:-.9px;line-height:1.18;margin:0 0 18px;"><?php esc_html_e( 'Thank you. Your order is on the way', 'pepselect-child' ); ?></h1>
					<p style="color:<?php echo esc_attr( $pep['ink'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:16px;font-weight:600;line-height:1.55;margin:0 0 4px;"><?php printf( esc_html__( 'Hi %s,', 'pepselect-child' ), esc_html( $pep_first_name ) ); ?></p>
					<p style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:16px;line-height:1.55;margin:0 0 26px;"><?php esc_html_e( 'Your shipment has left our facility. Use the tracking details below for updates. We look forward to supporting your next research project.', 'pepselect-child' ); ?></p>

					<table border="0" cellpadding="0" cellspacing="0" class="pep-email-desktop-only" role="presentation" width="100%" style="background-color:#F8FAFC;border:1px solid <?php echo esc_attr( $pep['border'] ); ?>;border-radius:8px;margin:0 0 22px;overflow:hidden;"><tr><td style="padding:18px 18px 16px;">
						<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%"><tr>
							<td style="color:#1E9467;font-family:Arial,Helvetica,sans-serif;font-size:17px;font-weight:700;line-height:17px;width:20px;" width="20">&#10003;</td><td style="border-top:2px solid #9FD5C2;font-size:0;line-height:0;">&nbsp;</td>
							<td style="color:#1E9467;font-family:Arial,Helvetica,sans-serif;font-size:17px;font-weight:700;line-height:17px;width:20px;" width="20">&#10003;</td><td style="border-top:2px solid #9FD5C2;font-size:0;line-height:0;">&nbsp;</td>
							<td style="color:#1E9467;font-family:Arial,Helvetica,sans-serif;font-size:17px;font-weight:700;line-height:17px;width:20px;" width="20">&#10003;</td><td style="border-top:2px solid #9FD5C2;font-size:0;line-height:0;">&nbsp;</td>
							<td style="color:<?php echo esc_attr( $pep['cyan'] ); ?>;font-family:Arial,Helvetica,sans-serif;font-size:19px;line-height:17px;width:20px;" width="20">&#9675;</td>
						</tr></table>
						<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="margin-top:7px;"><tr>
							<td style="color:<?php echo esc_attr( $pep['ink'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:11px;font-weight:700;line-height:1.35;width:25%;"><?php esc_html_e( 'Order received', 'pepselect-child' ); ?></td>
							<td style="color:<?php echo esc_attr( $pep['ink'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:11px;font-weight:700;line-height:1.35;width:25%;"><?php esc_html_e( 'Payment confirmed', 'pepselect-child' ); ?></td>
							<td style="color:<?php echo esc_attr( $pep['ink'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:11px;font-weight:700;line-height:1.35;width:25%;"><?php esc_html_e( 'Processed', 'pepselect-child' ); ?></td>
							<td style="color:<?php echo esc_attr( $pep['ink'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:11px;font-weight:700;line-height:1.35;width:25%;"><?php esc_html_e( 'Shipped', 'pepselect-child' ); ?></td>
						</tr></table>
					</td></tr></table>

					<div class="pep-email-mobile-only" style="display:none;max-height:0;mso-hide:all;overflow:hidden;">
						<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="background-color:#F8FAFC;border:1px solid <?php echo esc_attr( $pep['border'] ); ?>;border-radius:8px;margin:0 0 22px;"><tr><td style="padding:15px 16px;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
							<tr><td style="color:#1E9467;font-family:Arial,Helvetica,sans-serif;font-size:17px;font-weight:700;line-height:18px;width:24px;" width="24">&#10003;</td><td style="color:<?php echo esc_attr( $pep['ink'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:12px;font-weight:700;line-height:1.4;padding:3px 0;"><?php esc_html_e( 'Order received', 'pepselect-child' ); ?></td></tr>
							<tr><td style="border-left:2px solid #9FD5C2;font-size:0;height:7px;line-height:7px;">&nbsp;</td><td style="font-size:0;height:7px;line-height:7px;">&nbsp;</td></tr>
							<tr><td style="color:#1E9467;font-family:Arial,Helvetica,sans-serif;font-size:17px;font-weight:700;line-height:18px;width:24px;" width="24">&#10003;</td><td style="color:<?php echo esc_attr( $pep['ink'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:12px;font-weight:700;line-height:1.4;padding:3px 0;"><?php esc_html_e( 'Payment confirmed', 'pepselect-child' ); ?></td></tr>
							<tr><td style="border-left:2px solid #9FD5C2;font-size:0;height:7px;line-height:7px;">&nbsp;</td><td style="font-size:0;height:7px;line-height:7px;">&nbsp;</td></tr>
							<tr><td style="color:#1E9467;font-family:Arial,Helvetica,sans-serif;font-size:17px;font-weight:700;line-height:18px;width:24px;" width="24">&#10003;</td><td style="color:<?php echo esc_attr( $pep['ink'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:12px;font-weight:700;line-height:1.4;padding:3px 0;"><?php esc_html_e( 'Processed', 'pepselect-child' ); ?></td></tr>
							<tr><td style="border-left:2px solid #9FD5C2;font-size:0;height:7px;line-height:7px;">&nbsp;</td><td style="font-size:0;height:7px;line-height:7px;">&nbsp;</td></tr>
							<tr><td style="color:<?php echo esc_attr( $pep['cyan'] ); ?>;font-family:Arial,Helvetica,sans-serif;font-size:19px;line-height:18px;width:24px;" width="24">&#9675;</td><td style="color:<?php echo esc_attr( $pep['ink'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:12px;font-weight:700;line-height:1.4;padding:3px 0;"><?php esc_html_e( 'Shipped', 'pepselect-child' ); ?></td></tr>
						</table></td></tr></table>
					</div>

					<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="background-color:<?php echo esc_attr( $pep['cyan_soft'] ); ?>;border:1px solid <?php echo esc_attr( $pep['cyan'] ); ?>;border-radius:8px;margin:0 0 24px;overflow:hidden;"><tr><td class="pep-email-tracking-card" style="padding:24px;">
						<p style="color:#0D708E;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:10px;font-weight:700;letter-spacing:1.5px;line-height:1.5;margin:0 0 8px;text-transform:uppercase;"><?php esc_html_e( 'Shipment tracking', 'pepselect-child' ); ?></p>
						<h2 style="color:<?php echo esc_attr( $pep['navy'] ); ?>;font-family:Georgia,'Times New Roman',serif;font-size:21px;font-weight:700;line-height:1.3;margin:0 0 14px;"><?php esc_html_e( 'Track your shipment', 'pepselect-child' ); ?></h2>
						<?php if ( '' !== $pep_carrier_combined ) : ?>
							<table border="0" cellpadding="0" cellspacing="0" class="pep-email-desktop-only" role="presentation" width="100%" style="margin:0 0 15px;"><tr>
								<td style="padding-right:6px;width:50%;" width="50%"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="background-color:#F6FBFD;border-radius:6px;"><tr><td style="padding:12px 14px;"><p style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:9px;letter-spacing:1.2px;line-height:1.4;margin:0 0 4px;text-transform:uppercase;"><?php esc_html_e( 'Carrier', 'pepselect-child' ); ?></p><p style="color:<?php echo esc_attr( $pep['navy'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:14px;font-weight:700;line-height:1.4;margin:0;"><?php echo esc_html( $pep_carrier ); ?></p></td></tr></table></td>
								<td style="padding-left:6px;width:50%;" width="50%"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="background-color:#F6FBFD;border-radius:6px;"><tr><td style="padding:12px 14px;"><p style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:9px;letter-spacing:1.2px;line-height:1.4;margin:0 0 4px;text-transform:uppercase;"><?php esc_html_e( 'Service', 'pepselect-child' ); ?></p><p style="color:<?php echo esc_attr( $pep['navy'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:14px;font-weight:700;line-height:1.4;margin:0;"><?php echo esc_html( '' !== $pep_service ? $pep_service : $pep_carrier ); ?></p></td></tr></table></td>
							</tr></table>
							<div class="pep-email-mobile-only" style="display:none;max-height:0;mso-hide:all;overflow:hidden;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="background-color:#F6FBFD;border-radius:6px;margin:0 0 15px;"><tr><td style="padding:12px 14px;"><p style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:10px;letter-spacing:1.2px;line-height:1.4;margin:0 0 4px;text-transform:uppercase;"><?php esc_html_e( 'Carrier', 'pepselect-child' ); ?></p><p style="color:<?php echo esc_attr( $pep['navy'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:14px;font-weight:700;line-height:1.4;margin:0;"><?php echo esc_html( $pep_carrier_combined ); ?></p></td></tr></table></div>
						<?php endif; ?>
						<?php if ( '' !== $pep_tracking_number ) : ?>
							<p style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:10px;letter-spacing:1.2px;line-height:1.4;margin:0 0 5px;text-transform:uppercase;"><?php esc_html_e( 'Tracking number', 'pepselect-child' ); ?></p>
							<p style="color:<?php echo esc_attr( $pep['navy'] ); ?>;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:16px;font-weight:700;line-height:1.45;margin:0 0 16px;word-break:break-all;"><?php echo esc_html( $pep_tracking_number ); ?></p>
							<?php if ( '' !== $pep_tracking_url ) : ?>
								<table border="0" cellpadding="0" cellspacing="0" class="pep-email-button-table" role="presentation" style="margin:0 0 15px;"><tr><td align="center" bgcolor="<?php echo esc_attr( $pep['cyan'] ); ?>" style="background-color:<?php echo esc_attr( $pep['cyan'] ); ?>;border-radius:999px;"><a class="pep-email-button" href="<?php echo esc_url( $pep_tracking_url ); ?>" style="border:1px solid <?php echo esc_attr( $pep['cyan'] ); ?>;border-radius:999px;color:#FFF;display:inline-block;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:14px;font-weight:700;line-height:20px;padding:13px 40px;text-align:center;text-decoration:none;" target="_blank"><?php esc_html_e( 'Track shipment', 'pepselect-child' ); ?></a></td></tr></table>
								<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="border-top:1px solid <?php echo esc_attr( $pep['border'] ); ?>;"><tr><td style="padding-top:13px;"><p style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:12px;line-height:1.5;margin:0 0 4px;"><?php esc_html_e( 'Button not working? Copy the tracking link:', 'pepselect-child' ); ?></p><p style="font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:11px;line-height:1.5;margin:0;word-break:break-all;"><a href="<?php echo esc_url( $pep_tracking_url ); ?>" style="color:#0D708E;text-decoration:none;" target="_blank"><?php echo esc_html( $pep_tracking_url ); ?></a></p></td></tr></table>
							<?php else : ?>
								<p style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:13px;line-height:1.55;margin:0;"><?php esc_html_e( 'Use this number on the carrier’s tracking page to follow your shipment.', 'pepselect-child' ); ?></p>
							<?php endif; ?>
						<?php else : ?>
							<p style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:14px;line-height:1.55;margin:0;"><?php esc_html_e( 'Tracking details are not available yet. Contact support if you need help with this shipment.', 'pepselect-child' ); ?></p>
						<?php endif; ?>
					</td></tr></table>

					<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="background-color:#F8FAFC;border:1px solid <?php echo esc_attr( $pep['border'] ); ?>;border-radius:8px;margin:0 0 16px;overflow:hidden;">
						<tr><td colspan="2" style="border-bottom:1px solid <?php echo esc_attr( $pep['border'] ); ?>;padding:18px 20px;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%"><tr><td><h2 style="color:<?php echo esc_attr( $pep['navy'] ); ?>;font-family:Georgia,'Times New Roman',serif;font-size:21px;line-height:1.3;margin:0;"><?php esc_html_e( 'Order summary', 'pepselect-child' ); ?></h2></td><td align="right" style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:10px;line-height:1.5;"><?php echo esc_html( '#' . $pep_order_number ); ?><br><?php echo esc_html( $pep_order_date ); ?></td></tr></table></td></tr>
						<tr><td class="pep-email-summary-column" style="padding:0 20px;width:62%;" valign="top" width="62%">
							<?php foreach ( $pep_order_items as $pep_item_id => $pep_item ) : ?>
								<?php
								$pep_product  = $pep_item->get_product();
								$pep_image_id = $pep_product ? $pep_product->get_image_id() : 0;
								if ( ! $pep_image_id && $pep_product && $pep_product->is_type( 'variation' ) ) {
									$pep_parent_product = wc_get_product( $pep_product->get_parent_id() );
									$pep_image_id       = $pep_parent_product ? $pep_parent_product->get_image_id() : 0;
								}
								$pep_image_url = $pep_image_id ? wp_get_attachment_image_url( $pep_image_id, 'woocommerce_thumbnail' ) : wc_placeholder_img_src( 'woocommerce_thumbnail' );
								$pep_item_note = '';
								foreach ( (array) $pep_item->get_formatted_meta_data( '' ) as $pep_item_meta_value ) {
									$pep_item_meta_key = isset( $pep_item_meta_value->display_key ) ? wp_strip_all_tags( (string) $pep_item_meta_value->display_key ) : '';
									if ( preg_match( '/batch|lot/i', $pep_item_meta_key ) ) {
										$pep_item_note = isset( $pep_item_meta_value->display_value ) ? wp_strip_all_tags( (string) $pep_item_meta_value->display_value ) : '';
										break;
									}
								}
								if ( '' === $pep_item_note && $pep_product && $pep_product->get_sku() ) {
									$pep_item_note = $pep_product->get_sku();
								}
								?>
								<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="border-bottom:1px solid <?php echo esc_attr( $pep['border'] ); ?>;"><tr><td style="padding:14px 0;width:58px;" valign="middle" width="58"><img alt="<?php echo esc_attr( $pep_item->get_name() ); ?>" src="<?php echo esc_url( $pep_image_url ); ?>" width="46" style="background-color:#EEF3F6;border-radius:6px;height:46px;object-fit:contain;width:46px;"></td><td style="padding:14px 8px 14px 0;" valign="middle"><p style="color:<?php echo esc_attr( $pep['navy'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:15px;font-weight:700;line-height:1.4;margin:0 0 2px;"><?php echo esc_html( $pep_item->get_name() ); ?></p><?php if ( '' !== $pep_item_note ) : ?><p style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:10px;line-height:1.45;margin:0;"><?php echo esc_html( $pep_item_note ); ?></p><?php endif; ?></td><td align="right" style="color:<?php echo esc_attr( $pep['ink'] ); ?>;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:12px;font-weight:700;line-height:1.45;padding:14px 0;white-space:nowrap;" valign="middle">&times;<?php echo esc_html( $pep_item->get_quantity() ); ?><br><?php echo wp_kses_post( $order->get_formatted_line_subtotal( $pep_item ) ); ?></td></tr></table>
							<?php endforeach; ?>
						</td><td class="pep-email-summary-column pep-email-total-column" style="border-left:1px solid <?php echo esc_attr( $pep['border'] ); ?>;padding:12px 20px;width:38%;" valign="top" width="38%"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
							<?php foreach ( $pep_order_totals as $pep_total_key => $pep_total_data ) : ?>
								<?php if ( 'payment_method' === $pep_total_key ) { continue; } ?>
								<tr><td style="<?php echo 'order_total' === $pep_total_key ? 'border-top:1px solid ' . esc_attr( $pep['border'] ) . ';color:' . esc_attr( $pep['navy'] ) . ';font-weight:700;padding-top:12px;' : 'color:' . esc_attr( $pep['slate'] ) . ';'; ?>font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:12px;line-height:1.45;padding-bottom:8px;"><?php echo 'order_total' === $pep_total_key ? esc_html__( 'Paid total', 'pepselect-child' ) : wp_kses_post( $pep_total_data['label'] ); ?></td><td align="right" style="font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:12px;line-height:1.45;padding-bottom:8px;<?php echo 'order_total' === $pep_total_key ? 'border-top:1px solid ' . esc_attr( $pep['border'] ) . ';color:' . esc_attr( $pep['navy'] ) . ';font-size:16px;font-weight:700;padding-top:12px;' : 'color:' . esc_attr( $pep['ink'] ) . ';'; ?>white-space:nowrap;"><?php echo wp_kses_post( $pep_total_data['value'] ); ?></td></tr>
							<?php endforeach; ?>
						</table></td></tr>
						<?php if ( '' !== $pep_research_value || '' !== $pep_points_value ) : ?><tr><td colspan="2" style="border-top:1px solid <?php echo esc_attr( $pep['border'] ); ?>;padding:12px 20px;"><?php if ( '' !== $pep_points_value ) : ?><span style="border:1px solid <?php echo esc_attr( $pep['border'] ); ?>;border-radius:999px;color:<?php echo esc_attr( $pep['slate'] ); ?>;display:inline-block;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:11px;line-height:1.4;margin:2px 6px 2px 0;padding:5px 10px;"><?php printf( esc_html__( '%s points earned', 'pepselect-child' ), esc_html( $pep_points_value ) ); ?></span><?php endif; ?><?php if ( '' !== $pep_research_value ) : ?><span style="border:1px solid <?php echo esc_attr( $pep['border'] ); ?>;border-radius:999px;color:<?php echo esc_attr( $pep['slate'] ); ?>;display:inline-block;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:11px;line-height:1.4;margin:2px 6px 2px 0;padding:5px 10px;"><?php printf( esc_html__( 'Research purpose · %s', 'pepselect-child' ), esc_html( $pep_research_value ) ); ?></span><?php endif; ?></td></tr><?php endif; ?>
					</table>

					<table border="0" cellpadding="0" cellspacing="0" class="pep-email-desktop-addresses" role="presentation" width="100%" style="margin:0 0 16px;"><tr><td style="padding-right:6px;width:50%;" valign="top" width="50%"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="border:1px solid <?php echo esc_attr( $pep['border'] ); ?>;border-radius:8px;"><tr><td class="pep-email-address-card" style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:13px;line-height:1.45;padding:17px;"><h3 style="color:<?php echo esc_attr( $pep['navy'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:15px;line-height:1.4;margin:0 0 7px;"><?php esc_html_e( 'Shipping to', 'pepselect-child' ); ?></h3><?php echo wp_kses_post( $pep_shipping_address ); ?><?php if ( $pep_shipping_phone ) : ?><br><?php echo esc_html( $pep_shipping_phone ); ?><?php endif; ?></td></tr></table></td><td style="padding-left:6px;width:50%;" valign="top" width="50%"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="border:1px solid <?php echo esc_attr( $pep['border'] ); ?>;border-radius:8px;"><tr><td class="pep-email-address-card" style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:13px;line-height:1.45;padding:17px;"><h3 style="color:<?php echo esc_attr( $pep['navy'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:15px;line-height:1.4;margin:0 0 7px;"><?php esc_html_e( 'Delivery details', 'pepselect-child' ); ?></h3><?php echo esc_html( '' !== $pep_carrier_combined ? $pep_carrier_combined : $order->get_shipping_method() ); ?><br><?php esc_html_e( 'Tracking updates are available from the button above.', 'pepselect-child' ); ?></td></tr></table></td></tr></table>
					<div class="pep-email-mobile-addresses pep-email-mobile-only" style="display:none;max-height:0;mso-hide:all;overflow:hidden;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="border:1px solid <?php echo esc_attr( $pep['border'] ); ?>;border-radius:8px;margin:0 0 16px;"><tr><td class="pep-email-address-card" style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:13px;line-height:1.45;padding:17px;"><h3 style="color:<?php echo esc_attr( $pep['navy'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:15px;line-height:1.4;margin:0 0 7px;"><?php esc_html_e( 'Shipping to', 'pepselect-child' ); ?></h3><?php echo wp_kses_post( $pep_shipping_address ); ?><?php if ( $pep_shipping_phone ) : ?><br><?php echo esc_html( $pep_shipping_phone ); ?><?php endif; ?></td></tr></table></div>
					<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="background-color:#F3F6F8;border-radius:6px;margin:0;"><tr><td style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:13px;line-height:1.55;padding:13px 15px;">Questions about this shipment? Contact <a href="mailto:<?php echo esc_attr( $pep_support_email ); ?>" style="color:#0D708E;font-weight:700;text-decoration:underline;"><?php echo esc_html( $pep_support_email ); ?></a>.</td></tr></table>

					<?php
					if ( isset( $pep_tracking['source'] ) && '' !== $pep_tracking['source'] ) {
						echo "\n<!-- pepselect tracking source: " . esc_html( $pep_tracking['source'] ) . " -->\n";
					}
					if ( isset( $pep_tracking['candidates'] ) && '' !== $pep_tracking['candidates'] ) {
						echo "<!-- pepselect tracking candidates: " . esc_html( $pep_tracking['candidates'] ) . " -->\n";
					}
					?>
				</td></tr>
				<tr><td class="pep-email-footer" style="padding:0 44px 34px;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%"><tr><td align="center" style="border-top:1px solid <?php echo esc_attr( $pep['border'] ); ?>;padding-top:24px;"><p style="color:<?php echo esc_attr( $pep['navy'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:13px;font-weight:700;line-height:1.45;margin:0 0 2px;">Pep Select</p><p style="color:#0D708E;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:12px;line-height:1.45;margin:0 0 8px;"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color:#0D708E;text-decoration:none;">pepselect.com</a> · <a href="mailto:<?php echo esc_attr( $pep_support_email ); ?>" style="color:#0D708E;text-decoration:none;">Support</a></p><p class="pep-email-desktop-only" style="color:<?php echo esc_attr( $pep['slate'] ); ?>;display:block;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:11px;line-height:1.45;margin:0;"><?php esc_html_e( 'For laboratory research use only.', 'pepselect-child' ); ?></p></td></tr></table></td></tr>
			</table>
			<!--[if mso]></td></tr></table><![endif]-->
		</td></tr>
	</table>
</body>
</html>

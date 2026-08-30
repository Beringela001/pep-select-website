<?php
/**
 * Shared Pep Select canvas for Back In Stock Notifier customer emails.
 *
 * The plugin remains authoritative for subscriptions, stock transitions,
 * recipients, and delivery. This partial owns presentation only.
 *
 * @package PepSelectChild
 */

defined( 'ABSPATH' ) || exit;

$pep_bis_is_available = isset( $pep_bis_email_type ) && 'instock' === $pep_bis_email_type;
$pep                   = function_exists( 'pepselect_child_email_tokens' ) ? pepselect_child_email_tokens() : array(
	'navy'      => '#002A53',
	'ink'       => '#001D3A',
	'slate'     => '#5E6F80',
	'cyan'      => '#17A1CF',
	'cyan_soft' => '#E8F6FB',
	'border'    => '#D7E1E9',
	'white'     => '#FFFFFF',
	'font'      => "'Plus Jakarta Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif",
	'font_mono' => "'IBM Plex Mono','SFMono-Regular',Consolas,'Liberation Mono','Courier New',Courier,monospace",
);

$pep_support_email = function_exists( 'pepselect_child_email_support_address' ) ? pepselect_child_email_support_address() : 'support@pepselect.com';
$pep_logo_url      = 'https://pepselect.com/wp-content/uploads/2026/06/Logo_Pepselect_Whitebackground-1.png';
$pep_subscriber_id = isset( $subscriber_id ) ? absint( $subscriber_id ) : 0;
$pep_product_id    = 0;

if ( $pep_subscriber_id ) {
	$pep_product_id = absint( get_post_meta( $pep_subscriber_id, 'cwginstock_bypass_pid', true ) );
	if ( ! $pep_product_id ) {
		$pep_product_id = absint( get_post_meta( $pep_subscriber_id, 'cwginstock_pid', true ) );
	}
}

$pep_product        = $pep_product_id && function_exists( 'wc_get_product' ) ? wc_get_product( $pep_product_id ) : false;
$pep_product_label  = $pep_product ? trim( wp_strip_all_tags( $pep_product->get_name() ) ) : trim( wp_strip_all_tags( isset( $product_name ) ? (string) $product_name : '' ) );
$pep_product_label  = '' !== $pep_product_label ? $pep_product_label : __( 'Your selected product', 'pepselect-child' );
$pep_product_sku    = $pep_product ? trim( (string) $pep_product->get_sku() ) : '';
$pep_product_url    = ! empty( $product_link ) ? esc_url_raw( $product_link ) : ( $pep_product ? $pep_product->get_permalink() : home_url( '/shop/' ) );
$pep_product_image  = '';
$pep_product_image_id = $pep_product ? absint( $pep_product->get_image_id() ) : 0;

if ( ! $pep_product_image_id && $pep_product && $pep_product->is_type( 'variation' ) ) {
	$pep_parent_product  = wc_get_product( $pep_product->get_parent_id() );
	$pep_product_image_id = $pep_parent_product ? absint( $pep_parent_product->get_image_id() ) : 0;
}

if ( $pep_product_image_id ) {
	$pep_product_image = wp_get_attachment_image_url( $pep_product_image_id, 'woocommerce_thumbnail' );
}

$pep_recipient = isset( $email ) && is_object( $email ) && method_exists( $email, 'get_recipient' ) ? sanitize_email( $email->get_recipient() ) : '';
$pep_preheader = $pep_bis_is_available
	? sprintf(
		/* translators: %s: product name. */
		__( 'You asked us to let you know when %s returned to stock.', 'pepselect-child' ),
		$pep_product_label
	)
	: sprintf(
		/* translators: %s: product name. */
		__( 'We\'ll email you when %s is available again.', 'pepselect-child' ),
		$pep_product_label
	);
?>
<!doctype html>
<html lang="<?php echo esc_attr( get_bloginfo( 'language' ) ); ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo esc_attr( get_bloginfo( 'charset' ) ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="color-scheme" content="light">
	<meta name="supported-color-schemes" content="light">
	<title><?php echo esc_html( $pep_bis_is_available ? __( 'Good news. It\'s back.', 'pepselect-child' ) : __( 'We\'ll keep an eye on it', 'pepselect-child' ) ); ?></title>
	<style type="text/css">
		body,table,td,a,p,h1,h2{-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}
		table,td{mso-table-lspace:0;mso-table-rspace:0}table{border-collapse:separate;border-spacing:0}
		img{-ms-interpolation-mode:bicubic;border:0;display:block;height:auto;line-height:100%;outline:none;text-decoration:none}
		@media only screen and (max-width:520px){
			.pep-email-outer-pad{padding:8px!important}.pep-email-card{border-radius:18px!important}
			.pep-email-header{padding:28px 22px 22px!important}.pep-email-main{padding:0 22px 26px!important}.pep-email-footer{padding:0 22px 28px!important}
			.pep-email-heading{font-size:27px!important;letter-spacing:-.6px!important;line-height:1.18!important}
			.pep-email-desktop-only{display:none!important;font-size:0!important;line-height:0!important;max-height:0!important;mso-hide:all!important;overflow:hidden!important}
			.pep-bis-watch{padding:16px!important}.pep-bis-product-cell{box-sizing:border-box!important;display:block!important;padding-left:16px!important;padding-right:16px!important;width:100%!important}
			.pep-bis-image-cell{padding-bottom:0!important}.pep-bis-product-image{height:82px!important;width:82px!important}.pep-email-button-table{width:100%!important}.pep-email-button{display:block!important;width:auto!important}
		}
	</style>
</head>
<body style="background-color:#E9EEF4;margin:0;min-width:100%;padding:0;width:100%;">
	<div aria-hidden="true" style="font-size:1px;line-height:1px;max-height:1px;opacity:0;overflow:hidden;mso-hide:all;"><?php echo esc_html( $pep_preheader ); ?></div>
	<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="background-color:#E9EEF4;width:100%;">
		<tr><td align="center" class="pep-email-outer-pad" style="padding:32px 16px;">
			<!--[if mso]><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="680"><tr><td><![endif]-->
			<table border="0" cellpadding="0" cellspacing="0" class="pep-email-card" role="presentation" width="100%" style="background-color:<?php echo esc_attr( $pep['white'] ); ?>;border-radius:18px;box-shadow:0 18px 46px rgba(0,42,83,.14);max-width:680px;overflow:hidden;width:100%;">
				<tr><td style="font-size:0;line-height:0;padding:0;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%"><tr><td bgcolor="<?php echo esc_attr( $pep['navy'] ); ?>" height="6" style="background-color:<?php echo esc_attr( $pep['navy'] ); ?>;border-radius:18px 0 0 0;font-size:0;height:6px;line-height:6px;width:75%;">&nbsp;</td><td bgcolor="<?php echo esc_attr( $pep['cyan'] ); ?>" height="6" style="background-color:<?php echo esc_attr( $pep['cyan'] ); ?>;border-radius:0 18px 0 0;font-size:0;height:6px;line-height:6px;width:25%;">&nbsp;</td></tr></table></td></tr>
				<tr><td class="pep-email-header" style="padding:38px 44px 28px;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%"><tr><td align="left" valign="middle"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="display:inline-block;text-decoration:none;" target="_blank"><img alt="Pep Select" src="<?php echo esc_url( $pep_logo_url ); ?>" width="132" style="height:auto;max-width:132px;width:132px;"></a></td><td align="right" class="pep-email-desktop-only" style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:10px;font-weight:600;letter-spacing:1.4px;line-height:1.4;text-transform:uppercase;" valign="middle"><?php echo esc_html( $pep_bis_is_available ? __( 'Available again', 'pepselect-child' ) : __( 'Stock notification', 'pepselect-child' ) ); ?></td></tr><tr><td colspan="2" style="border-bottom:1px solid <?php echo esc_attr( $pep['border'] ); ?>;font-size:0;line-height:0;padding-top:28px;">&nbsp;</td></tr></table></td></tr>
				<tr><td class="pep-email-main" style="padding:0 44px 34px;">
					<p style="color:#0D708E;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:10px;font-weight:700;letter-spacing:1.5px;line-height:1.5;margin:0 0 12px;text-transform:uppercase;"><?php echo esc_html( $pep_bis_is_available ? __( 'Your stock notification', 'pepselect-child' ) : __( 'Stock notification confirmed', 'pepselect-child' ) ); ?></p>
					<h1 class="pep-email-heading" style="color:<?php echo esc_attr( $pep['ink'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:32px;font-weight:750;letter-spacing:-.9px;line-height:1.18;margin:0 0 18px;"><?php echo esc_html( $pep_bis_is_available ? __( 'Good news. It\'s back.', 'pepselect-child' ) : __( 'We\'ll keep an eye on it', 'pepselect-child' ) ); ?></h1>
					<p style="color:<?php echo esc_attr( $pep['ink'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:16px;font-weight:600;line-height:1.55;margin:0 0 4px;"><?php esc_html_e( 'Hi there,', 'pepselect-child' ); ?></p>
					<p style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:16px;line-height:1.6;margin:0 0 24px;">
						<?php if ( $pep_bis_is_available ) : ?>
							<?php printf( esc_html__( '%s is available again. You asked us to let you know, so this is the note.', 'pepselect-child' ), esc_html( $pep_product_label ) ); ?>
						<?php else : ?>
							<?php
							printf(
								/* translators: 1: product name, 2: subscriber email address. */
								esc_html__( 'We saved your request for %1$s. When it returns to stock, we\'ll send one email to %2$s.', 'pepselect-child' ),
								esc_html( $pep_product_label ),
								esc_html( $pep_recipient )
							);
							?>
						<?php endif; ?>
					</p>

					<?php if ( ! $pep_bis_is_available ) : ?>
					<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="background-color:<?php echo esc_attr( $pep['cyan_soft'] ); ?>;border:1px solid #BBDDE8;border-radius:8px;margin:0 0 22px;"><tr><td class="pep-bis-watch" style="padding:17px 18px;"><table border="0" cellpadding="0" cellspacing="0" role="presentation"><tr><td valign="middle" width="42"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="38"><tr><td align="center" bgcolor="#FFFFFF" height="38" style="background-color:#FFFFFF;border:1px solid #B9DBE7;border-radius:999px;color:#0D708E;font-family:Arial,Helvetica,sans-serif;font-size:18px;font-weight:700;height:38px;line-height:38px;text-align:center;width:38px;" width="38">&#10003;</td></tr></table></td><td style="padding-left:13px;" valign="middle"><h2 style="color:<?php echo esc_attr( $pep['navy'] ); ?>;font-family:Georgia,'Times New Roman',serif;font-size:18px;line-height:1.35;margin:0 0 3px;"><?php esc_html_e( 'Stock watch is on', 'pepselect-child' ); ?></h2><p style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:13px;line-height:1.5;margin:0;"><?php esc_html_e( 'You can give the refresh button a rest.', 'pepselect-child' ); ?></p></td></tr></table></td></tr></table>
					<?php endif; ?>

					<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="border:1px solid <?php echo esc_attr( $pep['border'] ); ?>;border-radius:8px;margin:0 0 22px;overflow:hidden;">
						<tr><td colspan="2" style="background-color:#F8FAFC;border-bottom:1px solid <?php echo esc_attr( $pep['border'] ); ?>;padding:14px 18px;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%"><tr><td style="color:#0D708E;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:9px;font-weight:700;letter-spacing:1.2px;line-height:1.4;text-transform:uppercase;"><?php echo esc_html( $pep_bis_is_available ? __( 'Available at Pep Select', 'pepselect-child' ) : __( 'Product requested', 'pepselect-child' ) ); ?></td><td align="right" style="color:<?php echo esc_attr( $pep_bis_is_available ? '#1E9467' : $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:9px;font-weight:700;line-height:1.4;text-transform:uppercase;"><?php echo $pep_bis_is_available ? '<span style="color:#1E9467;">&#9679;</span> ' . esc_html__( 'Available now', 'pepselect-child' ) : '<span style="color:' . esc_attr( $pep['slate'] ) . ';">&#9679;</span> ' . esc_html__( 'Waiting for restock', 'pepselect-child' ); ?></td></tr></table></td></tr>
						<tr>
							<td class="pep-bis-product-cell pep-bis-image-cell" style="padding:20px 0 20px 20px;width:116px;" valign="middle" width="116">
								<?php if ( $pep_product_image ) : ?>
									<img alt="<?php echo esc_attr( $pep_product_label ); ?>" class="pep-bis-product-image" src="<?php echo esc_url( $pep_product_image ); ?>" width="96" style="background-color:#EEF3F6;border-radius:6px;height:96px;object-fit:contain;width:96px;">
								<?php else : ?>
									<table border="0" cellpadding="0" cellspacing="0" class="pep-bis-product-image" role="presentation" width="96"><tr><td align="center" bgcolor="#EEF3F6" height="96" style="background-color:#EEF3F6;border-radius:6px;color:<?php echo esc_attr( $pep['cyan'] ); ?>;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:18px;font-weight:700;height:96px;text-align:center;width:96px;" width="96">PS</td></tr></table>
								<?php endif; ?>
							</td>
							<td class="pep-bis-product-cell" style="padding:20px;" valign="middle"><h2 style="color:<?php echo esc_attr( $pep['navy'] ); ?>;font-family:Georgia,'Times New Roman',serif;font-size:23px;line-height:1.25;margin:0 0 5px;word-break:break-word;"><?php echo esc_html( $pep_product_label ); ?></h2><?php if ( '' !== $pep_product_sku ) : ?><p style="color:#0D708E;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:10px;font-weight:700;letter-spacing:1.1px;line-height:1.4;margin:0 0 11px;text-transform:uppercase;"><?php printf( esc_html__( 'SKU %s', 'pepselect-child' ), esc_html( $pep_product_sku ) ); ?></p><?php endif; ?><p style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:13px;line-height:1.55;margin:0;"><?php echo esc_html( $pep_bis_is_available ? __( 'Review the product information and available batch documentation before ordering.', 'pepselect-child' ) : __( 'Review the product information while you wait.', 'pepselect-child' ) ); ?></p></td>
						</tr>
					</table>

					<table border="0" cellpadding="0" cellspacing="0" class="pep-email-button-table" role="presentation" style="margin:0 0 14px;"><tr><td align="center" bgcolor="<?php echo esc_attr( $pep['cyan'] ); ?>" style="background-color:<?php echo esc_attr( $pep['cyan'] ); ?>;border-radius:999px;"><a class="pep-email-button" href="<?php echo esc_url( $pep_product_url ); ?>" style="border:1px solid <?php echo esc_attr( $pep['cyan'] ); ?>;border-radius:999px;color:#FFFFFF;display:inline-block;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:14px;font-weight:700;line-height:20px;padding:13px 28px;text-align:center;text-decoration:none;" target="_blank"><?php echo esc_html( $pep_bis_is_available ? sprintf( __( 'View %s', 'pepselect-child' ), $pep_product_label ) : __( 'Review product details', 'pepselect-child' ) ); ?></a></td></tr></table>
					<p style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:12px;line-height:1.5;margin:0 0 22px;"><?php echo esc_html( $pep_bis_is_available ? __( 'This opens the product page with current details and availability.', 'pepselect-child' ) : __( 'No action is needed to keep your notification active.', 'pepselect-child' ) ); ?></p>
					<?php if ( $pep_bis_is_available ) : ?><p style="color:<?php echo esc_attr( $pep['navy'] ); ?>;font-family:Georgia,'Times New Roman',serif;font-size:17px;line-height:1.5;margin:0 0 22px;"><?php esc_html_e( 'Thanks for asking us to keep an eye on it.', 'pepselect-child' ); ?></p><?php endif; ?>
					<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="background-color:#F3F6F8;border-radius:6px;"><tr><td style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:13px;line-height:1.55;padding:13px 15px;"><?php echo esc_html( $pep_bis_is_available ? __( 'Have a question before ordering?', 'pepselect-child' ) : __( 'Subscribed by mistake or have a question?', 'pepselect-child' ) ); ?> Reply to this email or contact <a href="mailto:<?php echo esc_attr( $pep_support_email ); ?>" style="color:#0D708E;font-weight:700;text-decoration:underline;"><?php echo esc_html( $pep_support_email ); ?></a>.</td></tr></table>
				</td></tr>
				<tr><td class="pep-email-footer" style="padding:0 44px 34px;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%"><tr><td align="center" style="border-top:1px solid <?php echo esc_attr( $pep['border'] ); ?>;padding-top:24px;"><p style="color:<?php echo esc_attr( $pep['navy'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:13px;font-weight:700;line-height:1.45;margin:0 0 2px;">Pep Select</p><p style="color:#0D708E;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:12px;line-height:1.45;margin:0 0 8px;"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color:#0D708E;text-decoration:none;">pepselect.com</a> · <a href="mailto:<?php echo esc_attr( $pep_support_email ); ?>" style="color:#0D708E;text-decoration:none;">Support</a></p><p style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:11px;line-height:1.45;margin:0 0 6px;"><?php echo esc_html( function_exists( 'pepselect_child_company_ownership_line' ) ? pepselect_child_company_ownership_line() : __( '🇺🇸 American-owned and operated.', 'pepselect-child' ) ); ?></p><p class="pep-email-desktop-only" style="color:<?php echo esc_attr( $pep['slate'] ); ?>;display:block;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:11px;line-height:1.45;margin:0;"><?php esc_html_e( 'For laboratory research use only.', 'pepselect-child' ); ?></p></td></tr></table></td></tr>
			</table>
			<!--[if mso]></td></tr></table><![endif]-->
		</td></tr>
	</table>
</body>
</html>

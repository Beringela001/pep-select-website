<?php
/**
 * Shared Pep Select canvas for concise customer account and refund emails.
 *
 * The including WooCommerce template supplies escaped or trusted internal
 * message fragments. This file owns presentation only; WooCommerce continues
 * to own recipients, signed URLs, order records, and refund calculations.
 *
 * @package PepSelectChild
 */

defined( 'ABSPATH' ) || exit;

$pep = function_exists( 'pepselect_child_email_tokens' ) ? pepselect_child_email_tokens() : array(
	'navy' => '#002A53', 'ink' => '#001D3A', 'slate' => '#5E6F80', 'cyan' => '#17A1CF',
	'cyan_soft' => '#E8F6FB', 'border' => '#D7E1E9', 'white' => '#FFFFFF',
	'font' => "'Plus Jakarta Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif",
	'font_mono' => "'IBM Plex Mono','SFMono-Regular',Consolas,'Liberation Mono','Courier New',Courier,monospace",
);
$pep_logo_url = 'https://pepselect.com/wp-content/uploads/2026/06/Logo_Pepselect_Whitebackground-1.png';
?>
<!doctype html>
<html lang="<?php echo esc_attr( get_bloginfo( 'language' ) ); ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo esc_attr( get_bloginfo( 'charset' ) ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="color-scheme" content="light">
	<meta name="supported-color-schemes" content="light">
	<title><?php echo esc_html( $pep_email_title ); ?></title>
	<style type="text/css">
		body,table,td,a,p,h1,h2,h3{-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}
		table,td{mso-table-lspace:0;mso-table-rspace:0}table{border-collapse:separate;border-spacing:0}
		img{-ms-interpolation-mode:bicubic;border:0;display:block;height:auto;line-height:100%;outline:none;text-decoration:none}
		.pep-email-order-content table{max-width:100%;width:100%}.pep-email-order-content th,.pep-email-order-content td{box-sizing:border-box;overflow-wrap:anywhere;word-break:break-word}
		.pep-email-order-content h2{color:<?php echo esc_attr( $pep['navy'] ); ?>!important;font-family:<?php echo esc_attr( $pep['font'] ); ?>!important;font-size:20px!important;line-height:1.35!important;margin:24px 0 12px!important}
		.pep-email-order-content p,.pep-email-order-content address{color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:14px;line-height:1.55}
		@media only screen and (max-width:520px){
			.pep-email-outer-pad{padding:8px!important}.pep-email-card{border-radius:18px!important}
			.pep-email-header{padding:28px 22px 22px!important}.pep-email-main{padding:0 22px 28px!important}.pep-email-footer{padding:0!important}
			.pep-email-heading{font-size:27px!important;letter-spacing:-.6px!important;line-height:1.18!important}
			.pep-email-panel{padding:18px!important}.pep-email-button-table{width:100%!important}.pep-email-button{box-sizing:border-box!important;display:block!important;width:100%!important}
			.pep-email-desktop-only{display:none!important;font-size:0!important;line-height:0!important;max-height:0!important;mso-hide:all!important;overflow:hidden!important}
			.pep-email-order-content table,.pep-email-order-content tbody,.pep-email-order-content tr{max-width:100%!important;width:100%!important}
			.pep-email-order-content th,.pep-email-order-content td{font-size:12px!important;min-width:0!important;padding-left:5px!important;padding-right:5px!important}
			.pep-email-order-content th:first-child,.pep-email-order-content td:first-child{overflow-wrap:anywhere!important;width:60%!important;word-break:normal!important}
			.pep-email-order-content th:nth-child(2),.pep-email-order-content td:nth-child(2){overflow-wrap:normal!important;text-align:center!important;white-space:nowrap!important;width:14%!important;word-break:normal!important}
			.pep-email-order-content th:nth-child(3),.pep-email-order-content td:nth-child(3){overflow-wrap:normal!important;text-align:right!important;white-space:nowrap!important;width:26%!important;word-break:normal!important}
			.pep-email-order-content td:first-child img{display:none!important;height:0!important;width:0!important}
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
				<tr><td class="pep-email-header" style="padding:38px 44px 28px;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%"><tr><td align="left" valign="middle"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="display:inline-block;text-decoration:none;" target="_blank"><img alt="Pep Select" src="<?php echo esc_url( $pep_logo_url ); ?>" width="132" style="height:auto;max-width:132px;width:132px;"></a></td><td align="right" class="pep-email-desktop-only" style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:10px;font-weight:600;letter-spacing:1.4px;line-height:1.4;text-transform:uppercase;" valign="middle"><?php echo esc_html( $pep_header_meta ); ?></td></tr><tr><td colspan="2" style="border-bottom:1px solid <?php echo esc_attr( $pep['border'] ); ?>;font-size:0;line-height:0;padding-top:28px;">&nbsp;</td></tr></table></td></tr>
				<tr><td class="pep-email-main" style="padding:0 44px 34px;">
					<p style="color:#0D708E;font-family:<?php echo esc_attr( $pep['font_mono'] ); ?>;font-size:10px;font-weight:700;letter-spacing:1.5px;line-height:1.5;margin:0 0 12px;text-transform:uppercase;"><?php echo esc_html( $pep_label ); ?></p>
					<h1 class="pep-email-heading" style="color:<?php echo esc_attr( $pep['ink'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:32px;font-weight:750;letter-spacing:-.9px;line-height:1.18;margin:0 0 18px;"><?php echo esc_html( $pep_heading ); ?></h1>
					<p style="color:<?php echo esc_attr( $pep['ink'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:16px;font-weight:600;line-height:1.55;margin:0 0 4px;"><?php echo esc_html( $pep_greeting ); ?></p>
					<div style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:16px;line-height:1.55;margin:0 0 24px;"><?php echo wp_kses_post( $pep_intro_html ); ?></div>

					<?php if ( '' !== trim( $pep_panel_html ) ) : ?>
					<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="margin:0 0 24px;"><tr><td bgcolor="<?php echo esc_attr( $pep['cyan_soft'] ); ?>" class="pep-email-panel" style="background-color:<?php echo esc_attr( $pep['cyan_soft'] ); ?>;border:1px solid #BBDDE8;border-radius:8px;padding:20px 22px;"><?php echo wp_kses_post( $pep_panel_html ); ?></td></tr></table>
					<?php endif; ?>

					<?php if ( '' !== trim( $pep_order_content_html ) ) : ?><div class="pep-email-order-content" style="margin:0 0 24px;max-width:100%;overflow:hidden;"><?php echo wp_kses_post( $pep_order_content_html ); ?></div><?php endif; ?>

					<?php if ( '' !== trim( $pep_button_url ) && '' !== trim( $pep_button_label ) ) : ?>
					<table border="0" cellpadding="0" cellspacing="0" class="pep-email-button-table" role="presentation" style="margin:0 0 20px;"><tr><td align="center" bgcolor="<?php echo esc_attr( $pep['cyan'] ); ?>" style="background-color:<?php echo esc_attr( $pep['cyan'] ); ?>;border-radius:999px;"><a class="pep-email-button" href="<?php echo esc_url( $pep_button_url ); ?>" style="border:1px solid <?php echo esc_attr( $pep['cyan'] ); ?>;border-radius:999px;color:#FFFFFF;display:inline-block;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:14px;font-weight:700;line-height:20px;padding:13px 28px;text-align:center;text-decoration:none;" target="_blank"><?php echo esc_html( $pep_button_label ); ?></a></td></tr></table>
					<?php endif; ?>

					<?php if ( '' !== trim( $pep_note_html ) ) : ?><div style="color:<?php echo esc_attr( $pep['slate'] ); ?>;font-family:<?php echo esc_attr( $pep['font'] ); ?>;font-size:13px;line-height:1.6;margin:0;"><?php echo wp_kses_post( $pep_note_html ); ?></div><?php endif; ?>
				</td></tr>
				<?php if ( function_exists( 'pepselect_child_email_company_footer_row_html' ) ) { echo pepselect_child_email_company_footer_row_html( $pep_footer_context ); } // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</table>
			<!--[if mso]></td></tr></table><![endif]-->
		</td></tr>
	</table>
</body>
</html>

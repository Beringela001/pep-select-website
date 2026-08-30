<?php
/**
 * Pep Select email-signup coupon delivery.
 *
 * Business logic and recipient validation remain in the plugin class. This
 * template owns only the customer-facing email presentation.
 *
 * @package PepSelectCartRecovery
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html lang="<?php echo esc_attr( get_bloginfo( 'language' ) ); ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo esc_attr( get_bloginfo( 'charset' ) ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="color-scheme" content="light">
	<meta name="supported-color-schemes" content="light">
	<title><?php echo esc_html( $pep_email_copy['heading'] ); ?></title>
	<style type="text/css">
		body,table,td,a,p,h1,h2{-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}
		table,td{mso-table-lspace:0;mso-table-rspace:0}table{border-collapse:separate;border-spacing:0}
		img{-ms-interpolation-mode:bicubic;border:0;display:block;height:auto;line-height:100%;outline:none;text-decoration:none}
		@media only screen and (max-width:520px){
			.pep-email-outer-pad{padding:8px!important}.pep-email-card{border-radius:18px!important}
			.pep-email-header{padding:28px 22px 22px!important}.pep-email-main{padding:0 22px 28px!important}.pep-email-footer{padding:0 22px 28px!important}
			.pep-email-heading{font-size:27px!important;letter-spacing:-.6px!important;line-height:1.18!important}
			.pep-email-code{font-size:22px!important;letter-spacing:1.2px!important;word-break:break-all!important}
			.pep-email-button-table{width:100%!important}.pep-email-button{display:block!important;width:auto!important}
			.pep-email-desktop-only{display:none!important;font-size:0!important;line-height:0!important;max-height:0!important;mso-hide:all!important;overflow:hidden!important}
		}
	</style>
</head>
<body style="background-color:#E9EEF4;margin:0;min-width:100%;padding:0;width:100%;">
	<div aria-hidden="true" style="font-size:1px;line-height:1px;max-height:1px;opacity:0;overflow:hidden;mso-hide:all;"><?php echo esc_html( $pep_email_copy['preheader'] ); ?></div>
	<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="background-color:#E9EEF4;width:100%;">
		<tr><td align="center" class="pep-email-outer-pad" style="padding:32px 16px;">
			<!--[if mso]><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="680"><tr><td><![endif]-->
			<table border="0" cellpadding="0" cellspacing="0" class="pep-email-card" role="presentation" width="100%" style="background-color:#FFFFFF;border-radius:18px;box-shadow:0 18px 46px rgba(0,42,83,.14);max-width:680px;overflow:hidden;width:100%;">
				<tr><td style="font-size:0;line-height:0;padding:0;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%"><tr><td bgcolor="#002A53" height="6" style="background-color:#002A53;border-radius:18px 0 0 0;font-size:0;height:6px;line-height:6px;width:75%;">&nbsp;</td><td bgcolor="#17A1CF" height="6" style="background-color:#17A1CF;border-radius:0 18px 0 0;font-size:0;height:6px;line-height:6px;width:25%;">&nbsp;</td></tr></table></td></tr>
				<tr><td class="pep-email-header" style="padding:38px 44px 28px;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%"><tr><td align="left" valign="middle"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="display:inline-block;text-decoration:none;" target="_blank"><img alt="Pep Select" src="<?php echo esc_url( $pep_logo_url ); ?>" width="132" style="height:auto;max-width:132px;width:132px;"></a></td><td align="right" class="pep-email-desktop-only" style="color:#5E6F80;font-family:'IBM Plex Mono','SFMono-Regular',Consolas,'Liberation Mono','Courier New',Courier,monospace;font-size:10px;font-weight:600;letter-spacing:1.4px;line-height:1.4;text-transform:uppercase;" valign="middle"><?php echo esc_html( $pep_email_copy['label'] ); ?></td></tr><tr><td colspan="2" style="border-bottom:1px solid #D7E1E9;font-size:0;line-height:0;padding-top:28px;">&nbsp;</td></tr></table></td></tr>
				<tr><td class="pep-email-main" style="padding:0 44px 34px;">
					<p style="color:#0D708E;font-family:'IBM Plex Mono','SFMono-Regular',Consolas,'Liberation Mono','Courier New',Courier,monospace;font-size:10px;font-weight:700;letter-spacing:1.5px;line-height:1.5;margin:0 0 12px;text-transform:uppercase;"><?php echo esc_html( $pep_email_copy['eyebrow'] ); ?></p>
					<h1 class="pep-email-heading" style="color:#001D3A;font-family:'Plus Jakarta Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;font-size:32px;font-weight:750;letter-spacing:-.9px;line-height:1.18;margin:0 0 18px;"><?php echo esc_html( $pep_email_copy['heading'] ); ?></h1>
					<p style="color:#001D3A;font-family:'Plus Jakarta Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;font-size:16px;font-weight:600;line-height:1.55;margin:0 0 4px;"><?php echo esc_html( $pep_email_copy['greeting'] ); ?></p>
					<p style="color:#5E6F80;font-family:'Plus Jakarta Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;font-size:16px;line-height:1.6;margin:0 0 24px;"><?php echo esc_html( $pep_email_copy['intro'] ); ?></p>
					<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="background-color:#E8F6FB;border:1px solid #BBDDE8;border-radius:8px;margin:0 0 22px;"><tr><td style="padding:22px;">
						<p style="color:#0D708E;font-family:'IBM Plex Mono','SFMono-Regular',Consolas,'Liberation Mono','Courier New',Courier,monospace;font-size:9px;font-weight:700;letter-spacing:1.3px;line-height:1.4;margin:0 0 9px;text-transform:uppercase;"><?php echo esc_html( $pep_email_copy['code_label'] ); ?></p>
						<p class="pep-email-code" style="color:#002A53;font-family:'IBM Plex Mono','SFMono-Regular',Consolas,'Liberation Mono','Courier New',Courier,monospace;font-size:26px;font-weight:700;letter-spacing:1.8px;line-height:1.35;margin:0 0 10px;"><?php echo esc_html( $pep_coupon_code ); ?></p>
						<p style="color:#5E6F80;font-family:'Plus Jakarta Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;font-size:13px;line-height:1.55;margin:0;"><?php echo esc_html( $pep_email_copy['code_note'] ); ?></p>
					</td></tr></table>
					<p style="color:#5E6F80;font-family:'Plus Jakarta Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;font-size:14px;line-height:1.6;margin:0 0 22px;"><?php echo esc_html( $pep_email_copy['extra'] ); ?></p>
					<table border="0" cellpadding="0" cellspacing="0" class="pep-email-button-table" role="presentation" style="margin:0 0 16px;"><tr><td align="center" bgcolor="#17A1CF" style="background-color:#17A1CF;border-radius:999px;"><a class="pep-email-button" href="<?php echo esc_url( $pep_shop_url ); ?>" style="border:1px solid #17A1CF;border-radius:999px;color:#FFFFFF;display:inline-block;font-family:'Plus Jakarta Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;font-size:14px;font-weight:700;line-height:20px;padding:13px 28px;text-align:center;text-decoration:none;" target="_blank"><?php echo esc_html( $pep_email_copy['button'] ); ?></a></td></tr></table>
					<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="background-color:#F3F6F8;border-radius:6px;"><tr><td style="color:#5E6F80;font-family:'Plus Jakarta Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;font-size:13px;line-height:1.55;padding:13px 15px;"><?php echo wp_kses_post( make_clickable( esc_html( $pep_email_copy['support'] ) ) ); ?></td></tr></table>
				</td></tr>
				<tr><td class="pep-email-footer" style="padding:0;"><?php echo wp_kses_post( $pep_company_footer_html ); ?></td></tr>
			</table>
			<!--[if mso]></td></tr></table><![endif]-->
		</td></tr>
	</table>
</body>
</html>

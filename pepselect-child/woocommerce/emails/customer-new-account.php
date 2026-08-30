<?php
/**
 * Customer new account email — Pep Select child theme override.
 *
 * This template intentionally owns the complete email canvas so the account
 * notification renders consistently without changing the shared WooCommerce
 * order-email templates. Account URLs and customer data remain WooCommerce's
 * source of truth.
 *
 * @package WooCommerce\Templates\Emails
 * @version 10.9.0
 */

defined( 'ABSPATH' ) || exit;

$pep = function_exists( 'pepselect_child_email_tokens' ) ? pepselect_child_email_tokens() : array(
	'navy'      => '#002A53',
	'ink'       => '#001D3A',
	'slate'     => '#5E6F80',
	'neutral'   => '#7A8793',
	'cyan'      => '#17A1CF',
	'cyan_soft' => '#E8F6FB',
	'border'    => '#D7E1E9',
	'white'     => '#FFFFFF',
	'font'      => "'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif",
);

$pep_support_email = function_exists( 'pepselect_child_email_support_address' )
	? pepselect_child_email_support_address()
	: 'support@pepselect.com';
$pep_account_url   = wc_get_page_permalink( 'myaccount' );
$pep_display_name  = isset( $user_display_name ) ? trim( (string) $user_display_name ) : '';
$pep_login         = isset( $user_login ) ? trim( (string) $user_login ) : '';
$pep_account_email = '';

if ( isset( $email ) && is_object( $email ) ) {
	if ( isset( $email->user_email ) ) {
		$pep_account_email = sanitize_email( (string) $email->user_email );
	}

	if ( '' === $pep_account_email && is_callable( array( $email, 'get_recipient' ) ) ) {
		$pep_account_email = sanitize_email( (string) $email->get_recipient() );
	}
}

if ( '' === $pep_display_name ) {
	$pep_display_name = '' !== $pep_login ? $pep_login : __( 'there', 'pepselect-child' );
}

if ( ! empty( $password_generated ) && ! empty( $set_password_url ) ) {
	$pep_account_url = $set_password_url;
}

$pep_logo_url = 'https://pepselect.com/wp-content/uploads/2026/06/Logo_Pepselect_Whitebackground-1.png';
?>
<!doctype html>
<html lang="<?php echo esc_attr( get_bloginfo( 'language' ) ); ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo esc_attr( get_bloginfo( 'charset' ) ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="color-scheme" content="light">
	<meta name="supported-color-schemes" content="light">
	<title><?php esc_html_e( 'Your account is ready', 'pepselect-child' ); ?></title>
	<style type="text/css">
		body,
		table,
		td,
		a,
		p,
		h1,
		h2 {
			-webkit-text-size-adjust: 100%;
			-ms-text-size-adjust: 100%;
		}

		table,
		td {
			mso-table-lspace: 0pt;
			mso-table-rspace: 0pt;
		}

		table {
			border-collapse: separate;
			border-spacing: 0;
		}

		img {
			-ms-interpolation-mode: bicubic;
			border: 0;
			display: block;
			height: auto;
			line-height: 100%;
			outline: none;
			text-decoration: none;
		}

		.pep-email-mobile-only {
			font-size: 0;
			line-height: 0;
			max-height: 0;
			mso-hide: all;
			overflow: hidden;
		}

		@media only screen and (max-width: 520px) {
			.pep-email-outer-pad {
				padding: 8px !important;
			}

			.pep-email-card {
				border-radius: 18px !important;
			}

			.pep-email-header {
				padding: 28px 22px 22px !important;
			}

			.pep-email-main {
				padding: 24px 22px 28px !important;
			}

			.pep-email-footer {
				padding: 0 22px 28px !important;
			}

			.pep-email-heading {
				font-size: 27px !important;
				line-height: 1.18 !important;
				letter-spacing: -0.6px !important;
			}

			.pep-email-status-card {
				padding: 18px !important;
			}

			.pep-email-credential-label,
			.pep-email-credential-value {
				font-size: 13px !important;
			}

			.pep-email-credential-value {
				max-width: 205px !important;
				word-break: break-word !important;
			}

			.pep-email-button-table {
				width: 100% !important;
			}

			.pep-email-button {
				display: block !important;
				width: auto !important;
			}

			.pep-email-desktop-only,
			.pep-email-desktop-label,
			.pep-email-desktop-copy {
				display: none !important;
				font-size: 0 !important;
				line-height: 0 !important;
				max-height: 0 !important;
				mso-hide: all !important;
				overflow: hidden !important;
			}

			.pep-email-mobile-only,
			.pep-email-mobile-label,
			.pep-email-mobile-copy {
				display: inline !important;
				font-size: inherit !important;
				line-height: inherit !important;
				max-height: none !important;
				overflow: visible !important;
			}

			.pep-email-mobile-copy {
				display: block !important;
			}
		}
	</style>
</head>
<body style="background-color:#E9EEF4; margin:0; min-width:100%; padding:0; width:100%;">
	<div aria-hidden="true" style="font-size:1px; line-height:1px; max-height:1px; opacity:0; overflow:hidden; mso-hide:all;">
		<?php esc_html_e( 'Review your account details and open your Pep Select account.', 'pepselect-child' ); ?>
	</div>
	<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="background-color:#E9EEF4; width:100%;">
		<tr>
			<td align="center" class="pep-email-outer-pad" style="padding:32px 16px;">
				<!--[if mso]>
				<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="680"><tr><td>
				<![endif]-->
				<table border="0" cellpadding="0" cellspacing="0" class="pep-email-card" role="presentation" width="100%" style="background-color:<?php echo esc_attr( $pep['white'] ); ?>; border-radius:18px; box-shadow:0 18px 46px rgba(0,42,83,0.14); max-width:680px; overflow:hidden; width:100%;">
					<tr>
						<td style="font-size:0; line-height:0; padding:0;">
							<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
								<tr>
									<td bgcolor="<?php echo esc_attr( $pep['navy'] ); ?>" height="6" style="background-color:<?php echo esc_attr( $pep['navy'] ); ?>; border-radius:18px 0 0 0; font-size:0; height:6px; line-height:6px; width:75%;">&nbsp;</td>
									<td bgcolor="<?php echo esc_attr( $pep['cyan'] ); ?>" height="6" style="background-color:<?php echo esc_attr( $pep['cyan'] ); ?>; border-radius:0 18px 0 0; font-size:0; height:6px; line-height:6px; width:25%;">&nbsp;</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td class="pep-email-header" style="padding:38px 44px 28px;">
							<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
								<tr>
									<td align="left" valign="middle">
										<a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="display:inline-block; text-decoration:none;" target="_blank">
											<img alt="Pep Select" src="<?php echo esc_url( $pep_logo_url ); ?>" width="132" style="height:auto; max-width:132px; width:132px;">
										</a>
									</td>
									<td align="right" class="pep-email-desktop-only" style="color:<?php echo esc_attr( $pep['slate'] ); ?>; font-family:<?php echo esc_attr( $pep['font_mono'] ?? 'monospace' ); ?>; font-size:10px; font-weight:600; letter-spacing:1.4px; line-height:1.4; text-transform:uppercase;" valign="middle">
										<?php esc_html_e( 'Account notification', 'pepselect-child' ); ?>
									</td>
								</tr>
								<tr>
									<td colspan="2" style="border-bottom:1px solid <?php echo esc_attr( $pep['border'] ); ?>; font-size:0; line-height:0; padding-top:28px;">&nbsp;</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td class="pep-email-main" style="padding:0 44px 34px;">
							<p style="color:#0D708E; font-family:<?php echo esc_attr( $pep['font_mono'] ?? 'monospace' ); ?>; font-size:10px; font-weight:700; letter-spacing:1.5px; line-height:1.5; margin:0 0 12px; text-transform:uppercase;">
								<?php esc_html_e( 'Account created', 'pepselect-child' ); ?>
							</p>
							<h1 class="pep-email-heading" style="color:<?php echo esc_attr( $pep['ink'] ); ?>; font-family:<?php echo esc_attr( $pep['font'] ); ?>; font-size:32px; font-weight:750; letter-spacing:-0.9px; line-height:1.18; margin:0 0 18px;">
								<?php esc_html_e( 'Your account is ready', 'pepselect-child' ); ?>
							</h1>
							<p style="color:<?php echo esc_attr( $pep['ink'] ); ?>; font-family:<?php echo esc_attr( $pep['font'] ); ?>; font-size:16px; font-weight:600; line-height:1.55; margin:0 0 4px;">
								<?php
								printf(
									/* translators: %s: Customer first name or username. */
									esc_html__( 'Hi %s,', 'pepselect-child' ),
									esc_html( $pep_display_name )
								);
								?>
							</p>
							<p class="pep-email-desktop-copy" style="color:<?php echo esc_attr( $pep['slate'] ); ?>; display:block; font-family:<?php echo esc_attr( $pep['font'] ); ?>; font-size:16px; line-height:1.55; margin:0 0 26px;">
								<?php esc_html_e( 'Your account is set up. Use it to review orders, update your details, and check shipment information.', 'pepselect-child' ); ?>
							</p>
							<p class="pep-email-mobile-copy pep-email-mobile-only" style="color:<?php echo esc_attr( $pep['slate'] ); ?>; font-family:<?php echo esc_attr( $pep['font'] ); ?>; font-size:16px; line-height:1.55; margin:0 0 22px; max-height:0; overflow:hidden; mso-hide:all;">
								<?php esc_html_e( 'Review orders, update your details, and check shipment information from your account.', 'pepselect-child' ); ?>
							</p>

							<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="margin:0 0 22px;">
								<tr>
									<td bgcolor="<?php echo esc_attr( $pep['cyan_soft'] ); ?>" class="pep-email-status-card" style="background-color:<?php echo esc_attr( $pep['cyan_soft'] ); ?>; border:1px solid #BBDDE8; border-radius:8px; padding:20px;">
										<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
											<tr>
												<td valign="top" width="48" style="padding-right:14px; width:48px;">
													<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="34">
														<tr>
															<td align="center" bgcolor="<?php echo esc_attr( $pep['cyan'] ); ?>" height="34" style="background-color:<?php echo esc_attr( $pep['cyan'] ); ?>; border-radius:50%; color:#FFFFFF; font-family:Arial,Helvetica,sans-serif; font-size:22px; font-weight:700; height:34px; line-height:34px; text-align:center; width:34px;" valign="middle" width="34">&#10003;</td>
														</tr>
													</table>
												</td>
												<td valign="middle">
													<p style="color:<?php echo esc_attr( $pep['navy'] ); ?>; font-family:<?php echo esc_attr( $pep['font'] ); ?>; font-size:18px; font-weight:700; line-height:1.35; margin:0 0 3px;">
														<?php esc_html_e( 'Welcome to Pep Select', 'pepselect-child' ); ?>
													</p>
													<p class="pep-email-desktop-copy" style="color:<?php echo esc_attr( $pep['slate'] ); ?>; display:block; font-family:<?php echo esc_attr( $pep['font'] ); ?>; font-size:14px; line-height:1.5; margin:0;">
														<?php esc_html_e( 'Your account details are ready below.', 'pepselect-child' ); ?>
													</p>
													<p class="pep-email-mobile-copy pep-email-mobile-only" style="color:<?php echo esc_attr( $pep['slate'] ); ?>; font-family:<?php echo esc_attr( $pep['font'] ); ?>; font-size:14px; line-height:1.5; margin:0; max-height:0; overflow:hidden; mso-hide:all;">
														<?php esc_html_e( 'Your account details are ready.', 'pepselect-child' ); ?>
													</p>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>

							<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="border:1px solid <?php echo esc_attr( $pep['border'] ); ?>; border-radius:8px; margin:0 0 24px; overflow:hidden;">
								<tr>
									<td class="pep-email-credential-label" style="color:<?php echo esc_attr( $pep['slate'] ); ?>; font-family:<?php echo esc_attr( $pep['font'] ); ?>; font-size:13px; line-height:1.4; padding:16px 18px;">
										<?php esc_html_e( 'Username', 'pepselect-child' ); ?>
									</td>
									<td align="right" class="pep-email-credential-value" style="color:<?php echo esc_attr( $pep['navy'] ); ?>; font-family:<?php echo esc_attr( $pep['font'] ); ?>; font-size:13px; font-weight:700; line-height:1.4; padding:16px 18px; word-break:break-word;">
										<?php echo esc_html( $pep_login ); ?>
									</td>
								</tr>
								<tr>
									<td class="pep-email-credential-label" style="border-top:1px solid <?php echo esc_attr( $pep['border'] ); ?>; color:<?php echo esc_attr( $pep['slate'] ); ?>; font-family:<?php echo esc_attr( $pep['font'] ); ?>; font-size:13px; line-height:1.4; padding:16px 18px;">
										<span class="pep-email-desktop-label"><?php esc_html_e( 'Account email', 'pepselect-child' ); ?></span><span class="pep-email-mobile-label pep-email-mobile-only" style="max-height:0; mso-hide:all; overflow:hidden;"><?php esc_html_e( 'Email', 'pepselect-child' ); ?></span>
									</td>
									<td align="right" class="pep-email-credential-value" style="border-top:1px solid <?php echo esc_attr( $pep['border'] ); ?>; color:<?php echo esc_attr( $pep['navy'] ); ?>; font-family:<?php echo esc_attr( $pep['font'] ); ?>; font-size:13px; font-weight:700; line-height:1.4; padding:16px 18px; word-break:break-word;">
										<?php echo esc_html( $pep_account_email ); ?>
									</td>
								</tr>
							</table>

							<table border="0" cellpadding="0" cellspacing="0" class="pep-email-button-table" role="presentation" width="220" style="margin:0 0 18px; width:220px;">
								<tr>
									<td align="center" bgcolor="<?php echo esc_attr( $pep['cyan'] ); ?>" style="background-color:<?php echo esc_attr( $pep['cyan'] ); ?>; border-radius:999px;">
										<a class="pep-email-button" href="<?php echo esc_url( $pep_account_url ); ?>" style="border:1px solid <?php echo esc_attr( $pep['cyan'] ); ?>; border-radius:999px; color:#FFFFFF; display:inline-block; font-family:<?php echo esc_attr( $pep['font'] ); ?>; font-size:14px; font-weight:700; line-height:20px; padding:13px 24px; text-align:center; text-decoration:none; width:170px;" target="_blank">
											<?php esc_html_e( 'Open my account', 'pepselect-child' ); ?>
										</a>
									</td>
								</tr>
							</table>

							<p class="pep-email-desktop-copy" style="color:<?php echo esc_attr( $pep['slate'] ); ?>; display:block; font-family:<?php echo esc_attr( $pep['font'] ); ?>; font-size:13px; line-height:1.55; margin:0;">
								<?php esc_html_e( 'If you did not create this account, contact ', 'pepselect-child' ); ?><a href="mailto:<?php echo esc_attr( $pep_support_email ); ?>" style="color:#0D708E; font-weight:700; text-decoration:underline;"><?php echo esc_html( $pep_support_email ); ?></a>.
							</p>
							<p class="pep-email-mobile-copy pep-email-mobile-only" style="color:<?php echo esc_attr( $pep['slate'] ); ?>; font-family:<?php echo esc_attr( $pep['font'] ); ?>; font-size:13px; line-height:1.55; margin:0; max-height:0; overflow:hidden; mso-hide:all;">
								<?php esc_html_e( 'Didn’t create this account? Contact ', 'pepselect-child' ); ?><a href="mailto:<?php echo esc_attr( $pep_support_email ); ?>" style="color:#0D708E; font-weight:700; text-decoration:underline;"><?php echo esc_html( $pep_support_email ); ?></a>.
							</p>
						</td>
					</tr>
					<tr>
						<td class="pep-email-footer" style="padding:0 44px 34px;">
							<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
								<tr>
									<td align="center" style="border-top:1px solid <?php echo esc_attr( $pep['border'] ); ?>; padding-top:24px;">
										<p style="color:<?php echo esc_attr( $pep['navy'] ); ?>; font-family:<?php echo esc_attr( $pep['font'] ); ?>; font-size:12px; font-weight:700; line-height:1.45; margin:0 0 2px;">Pep Select</p>
										<p style="color:#0D708E; font-family:<?php echo esc_attr( $pep['font'] ); ?>; font-size:11px; line-height:1.45; margin:0 0 8px;"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color:#0D708E; text-decoration:none;">pepselect.com</a> · <a href="mailto:<?php echo esc_attr( $pep_support_email ); ?>" style="color:#0D708E; text-decoration:none;"><span class="pep-email-desktop-label"><?php esc_html_e( 'Contact support', 'pepselect-child' ); ?></span><span class="pep-email-mobile-label pep-email-mobile-only" style="max-height:0; mso-hide:all; overflow:hidden;"><?php esc_html_e( 'Support', 'pepselect-child' ); ?></span></a></p>
										<p style="color:<?php echo esc_attr( $pep['slate'] ); ?>; font-family:<?php echo esc_attr( $pep['font'] ); ?>; font-size:10px; line-height:1.45; margin:0 0 6px;"><?php echo esc_html( function_exists( 'pepselect_child_company_ownership_line' ) ? pepselect_child_company_ownership_line() : __( '🇺🇸 American-owned and operated.', 'pepselect-child' ) ); ?></p>
										<p class="pep-email-desktop-only" style="color:<?php echo esc_attr( $pep['slate'] ); ?>; display:block; font-family:<?php echo esc_attr( $pep['font'] ); ?>; font-size:10px; line-height:1.45; margin:0;">
											<?php esc_html_e( 'For laboratory research use only.', 'pepselect-child' ); ?>
										</p>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<!--[if mso]>
				</td></tr></table>
				<![endif]-->
			</td>
		</tr>
	</table>
</body>
</html>

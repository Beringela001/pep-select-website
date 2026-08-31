<?php
/**
 * Plugin Name: Pep Select Cart Recovery
 * Description: Lightweight exit offer, unique coupons, and Cart Abandonment Recovery integration for Pep Select.
 * Version: 0.4.12
 * Author: Pep Select
 * Text Domain: pepselect-cart-recovery
 */

defined( 'ABSPATH' ) || exit;

final class PepSelect_Cart_Recovery {
	const VERSION                     = '0.4.12';
	const OPTION                      = 'pepselect_cart_recovery_settings';
	const VERSION_OPTION              = 'pepselect_cart_recovery_version';
	const RECOVERY_COPY_OPTION        = 'pepselect_cart_recovery_copy_version';
	const RECOVERY_COPY_VERSION       = '3';
	const NONCE                       = 'pepselect_exit_offer_capture';
	const MARKETING_EMAILS_PER_SECOND = 1;

	private static $instance;

	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'init', array( $this, 'maybe_upgrade_settings' ), 5 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'wp_footer', array( $this, 'render_dialog' ), 40 );
		add_action( 'wp_ajax_pepselect_capture_exit_offer', array( $this, 'capture_offer' ) );
		add_action( 'wp_ajax_nopriv_pepselect_capture_exit_offer', array( $this, 'capture_offer' ) );
		add_filter( 'woo_ca_recovery_email_data', array( $this, 'attach_coupon_to_recovery_email' ), 20, 2 );
		add_filter( 'wcar_add_token_data', array( $this, 'attach_coupon_to_recovery_link' ), 20, 2 );
		add_filter( 'wcf_ca_should_send_email', array( $this, 'require_recovery_code_for_final_email' ), 20, 2 );
		add_filter( 'cartflows_ca_email_headers', array( $this, 'recovery_headers' ), 20 );
		add_filter( 'fluent_crm/global_email_limit_per_second', array( $this, 'limit_marketing_delivery_rate' ), 100, 2 );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'update_option_' . self::OPTION, array( $this, 'sync_coupon_stackability_on_settings_update' ), 10, 3 );
		add_action( 'admin_menu', array( $this, 'register_settings_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
	}

	/**
	 * Keep FluentCRM campaign bursts below recipient-provider throttle thresholds.
	 *
	 * WooCommerce transactional mail uses a separate WP Mail SMTP route and does
	 * not pass through FluentCRM, so it remains unaffected by this limit.
	 *
	 * @param int   $limit          FluentCRM's resolved delivery limit.
	 * @param array $email_settings FluentCRM global email settings.
	 * @return int
	 */
	public function limit_marketing_delivery_rate( $limit, $email_settings ) {
		unset( $limit, $email_settings );

		return self::MARKETING_EMAILS_PER_SECOND;
	}

	public static function activate() {
		if ( false === get_option( self::OPTION, false ) ) {
			add_option( self::OPTION, self::defaults() );
		}
	}

	private static function defaults() {
		return array(
			'enabled'                   => 0,
			'discount_type'             => 'percent',
			'discount_amount'           => '20',
			'allow_coupon_stacking'     => 0,
			'coupon_prefix'             => 'PEP',
			'coupon_expiry_days'        => 7,
			'usage_limit'               => 1,
			'usage_limit_per_user'      => 1,
			'limit_usage_to_x_items'    => 0,
			'minimum_amount'            => '',
			'maximum_amount'            => '',
			'free_shipping'             => 0,
			'exclude_sale_items'        => 0,
			'product_ids'               => array(),
			'excluded_product_ids'      => array(),
			'product_category_ids'      => array(),
			'excluded_product_category_ids' => array(),
			'product_brand_ids'         => array(),
			'excluded_product_brand_ids' => array(),
			'dismiss_days'              => 30,
			'fluentcrm_list_id'         => 0,
			'final_template_id'         => 0,
			'exit_eyebrow'              => 'Before you go',
			'exit_heading'              => 'Your email just found {discount} off.',
			'exit_body'                 => 'Drop it below and we will send a private discount code straight to your inbox.',
			'exit_placeholder'          => 'Email address',
			'exit_button'               => 'Send my {discount} code',
			'exit_loading'              => 'Sending…',
			'exit_fineprint'            => 'Your code comes with occasional product and restock emails. Unsubscribe anytime.',
			'exit_success'              => 'Sent. Your {discount} code is headed to your inbox.',
			'exit_success_note'         => 'Use it at checkout with the same email address.',
			'exit_overlay_color'        => '#001D3A',
			'exit_overlay_opacity'      => '0.50',
			'exit_card_color'           => '#FFFFFF',
			'exit_card_image'           => '',
			'exit_card_tint_color'      => '#FFFFFF',
			'exit_card_tint_opacity'    => '0.92',
			'exit_text_color'           => '#001D3A',
			'exit_muted_color'          => '#5E6F80',
			'exit_accent_color'         => '#0D708E',
			'exit_button_color'         => '#17A1CF',
			'exit_button_text_color'    => '#FFFFFF',
			'email_subject'             => 'Your private {discount} code from Pep Select',
			'email_preheader'           => 'Your private Pep Select code is inside and tied to your email address.',
			'email_label'               => 'Private discount code',
			'email_eyebrow'             => 'Fair trade',
			'email_heading'             => 'Your {discount} code has landed.',
			'email_greeting'            => 'Hi there,',
			'email_intro'               => 'You gave us your email. We promised {discount} off. Fair trade.',
			'email_code_label'          => 'Your private {discount} code',
			'email_code_note'           => 'Use it at checkout with the same email address. The code expires in {days} days.',
			'email_extra'               => 'The code is tied to your email and can be applied to eligible products at checkout. Product details and available batch documentation are ready whenever you want to take another look.',
			'email_button'              => 'Explore compounds',
			'email_support'             => 'Have a question? Reply to this email, and one of our team members will be in touch shortly.',
			'promo_enabled'             => 0,
			'promo_start'               => '',
			'promo_end'                 => '',
			'promo_delay_seconds'       => 8,
			'promo_dismiss_days'        => 7,
			'promo_suppress_exit'       => 1,
			'promo_eyebrow'             => 'Limited-time promotion',
			'promo_heading'             => 'Something special is happening.',
			'promo_body'                => 'Take a look at the current Pep Select promotion while it is available.',
			'promo_code_label'          => 'Promotion code',
			'promo_code'                => '',
			'promo_button'              => 'View the promotion',
			'promo_url'                 => '/shop/',
			'promo_fineprint'           => '',
			'promo_overlay_color'       => '#001D3A',
			'promo_overlay_opacity'     => '0.50',
			'promo_card_color'          => '#FFFFFF',
			'promo_card_image'          => '',
			'promo_card_tint_color'     => '#FFFFFF',
			'promo_card_tint_opacity'   => '0.92',
			'promo_text_color'          => '#001D3A',
			'promo_muted_color'         => '#5E6F80',
			'promo_accent_color'        => '#0D708E',
			'promo_button_color'        => '#17A1CF',
			'promo_button_text_color'   => '#FFFFFF',
		);
	}

	/**
	 * Apply release-level setting changes without disturbing customized popup copy.
	 */
	public function maybe_upgrade_settings() {
		$this->maybe_migrate_recovery_templates();

		if ( self::VERSION === get_option( self::VERSION_OPTION ) ) {
			return;
		}

		if ( ! $this->sync_generated_coupon_stackability( false ) ) {
			return;
		}

		$settings                  = (array) get_option( self::OPTION, array() );
		$legacy_email_extra        = 'The code can combine with eligible offers. Product details and available batch documentation are ready whenever you want to take another look.';
		$settings['allow_coupon_stacking'] = 0;
		$settings['email_support'] = self::defaults()['email_support'];
		if ( $legacy_email_extra === (string) ( $settings['email_extra'] ?? '' ) ) {
			$settings['email_extra'] = self::defaults()['email_extra'];
		}
		update_option( self::OPTION, $settings );
		update_option( self::VERSION_OPTION, self::VERSION );
	}

	/** Keep previously generated recovery coupons aligned with the stacking control. */
	public function sync_coupon_stackability_on_settings_update( $old_value, $value, $option ) {
		unset( $option );
		if ( empty( $old_value['allow_coupon_stacking'] ) === empty( $value['allow_coupon_stacking'] ) ) {
			return;
		}
		$this->sync_generated_coupon_stackability( ! empty( $value['allow_coupon_stacking'] ) );
	}

	/** Update only coupons created by this plugin. */
	private function sync_generated_coupon_stackability( $allow_stacking ) {
		if ( ! class_exists( 'WC_Coupon' ) ) {
			return false;
		}
		$ids = get_posts(
			array(
				'post_type'      => 'shop_coupon',
				'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'meta_query'     => array(
					'relation' => 'OR',
					array( 'key' => '_pepselect_exit_offer', 'compare' => 'EXISTS' ),
					array( 'key' => '_pepselect_exit_bonus_offer', 'compare' => 'EXISTS' ),
				),
			)
		);
		foreach ( $ids as $id ) {
			$coupon = new WC_Coupon( $id );
			$coupon->set_individual_use( ! $allow_stacking );
			$coupon->save();
		}
		return true;
	}

	/**
	 * Replace the saved-cart database templates with the approved copy.
	 *
	 * CartFlows stores these messages outside the plugin, so changing the send-time
	 * wrapper alone cannot correct an older subject or body already in the database.
	 */
	private function maybe_migrate_recovery_templates() {
		if ( self::RECOVERY_COPY_VERSION === get_option( self::RECOVERY_COPY_OPTION ) || ! defined( 'CARTFLOWS_CA_EMAIL_TEMPLATE_TABLE' ) ) {
			return;
		}

		global $wpdb;
		$table = $wpdb->prefix . CARTFLOWS_CA_EMAIL_TEMPLATE_TABLE;
		if ( $table !== $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) ) ) {
			return;
		}

		$templates = $wpdb->get_results( "SELECT id, template_name, frequency, frequency_unit FROM {$table} ORDER BY id ASC" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$updated   = array();

		foreach ( (array) $templates as $template ) {
			$minutes       = $this->recovery_delay_minutes( $template->frequency ?? 0, $template->frequency_unit ?? '' );
			$template_name = strtolower( trim( (string) ( $template->template_name ?? '' ) ) );
			$is_90_minute  = 'saved cart | 90 minutes' === $template_name || 90 === $minutes;
			$is_24_hour    = 'saved cart | 24 hours' === $template_name || 1440 === $minutes;
			$is_48_hour    = 'saved cart | 48 hours' === $template_name || in_array( $minutes, array( 2820, 2880 ), true );
			if ( $is_90_minute && empty( $updated['90'] ) ) {
				$wpdb->update(
					$table,
					array(
						'email_subject' => 'Want another look?',
						'email_body'    => $this->recovery_template_body( '90' ),
					),
					array( 'id' => absint( $template->id ) ),
					array( '%s', '%s' ),
					array( '%d' )
				);
				$updated['90'] = true;
			}

			if ( $is_24_hour && empty( $updated['24'] ) ) {
				$wpdb->update(
					$table,
					array(
						'email_subject' => 'Need a hand?',
						'email_body'    => $this->recovery_template_body( '24' ),
					),
					array( 'id' => absint( $template->id ) ),
					array( '%s', '%s' ),
					array( '%d' )
				);
				$updated['24'] = true;
			}

			if ( $is_48_hour && empty( $updated['48'] ) ) {
				$wpdb->update(
					$table,
					array(
						'email_subject' => "Don't forget your code",
						'email_body'    => $this->recovery_template_body( '48' ),
					),
					array( 'id' => absint( $template->id ) ),
					array( '%s', '%s' ),
					array( '%d' )
				);
				$updated['48'] = true;
			}
		}

		if ( ! empty( $updated['90'] ) && ! empty( $updated['24'] ) && ! empty( $updated['48'] ) ) {
			update_option( self::RECOVERY_COPY_OPTION, self::RECOVERY_COPY_VERSION );
		}
	}

	private function recovery_delay_minutes( $frequency, $unit ) {
		$frequency = absint( $frequency );
		switch ( strtoupper( (string) $unit ) ) {
			case 'DAY':
				return $frequency * 1440;
			case 'HOUR':
				return $frequency * 60;
			default:
				return $frequency;
		}
	}

	private function recovery_template_body( $template ) {
		$is_first  = '90' === $template;
		$is_final  = '48' === $template;
		$preheader = $is_final ? 'Your private Pep Select code is inside.' : ( $is_first ? 'Your Pep Select cart is ready when you are.' : 'Questions before ordering? Just reply to this email.' );
		$eyebrow   = $is_final ? 'YOUR PRIVATE CODE' : ( $is_first ? 'YOUR SAVED CART' : 'A QUICK NOTE' );
		$heading   = $is_final ? "Don't forget your code." : ( $is_first ? 'Pick up where you left off.' : 'Any questions?' );
		$reference = $is_final ? 'SAVED CART' : ( $is_first ? 'SAVED CART' : 'A NOTE FROM SUPPORT' );
		$message   = $is_final
			? 'Your cart is still available. Use the private code below if you decide to complete your order.'
			: ( $is_first
			? 'The compounds you selected are still in your cart if you would like to take another look.'
			: 'Just a quick note to let you know your cart is still available if you would like another look.' );
		$support   = 'Have a question? Reply to this email, and one of our team members will be in touch shortly.';
		$logo_url  = plugin_dir_url( __FILE__ ) . 'assets/pep-select-logo-header.png';
		$footer    = $this->company_footer_html( 'For laboratory research use only. &middot; {{cart.unsubscribe}}' );
		$support_before = ( $is_first || $is_final ) ? '' : '<p class="pep-email-support-copy" style="color:#5e6f80;font-family:Arial,sans-serif;font-size:15px;line-height:1.62;margin:0 0 22px;">' . esc_html( $support ) . '</p>';
		$support_after  = ( $is_first || $is_final ) ? '<div class="pep-email-support-card" style="background:#f3f6f8;border-radius:6px;color:#425b70;font-family:Arial,sans-serif;font-size:12px;line-height:1.6;margin-top:22px;padding:12px 14px;">' . esc_html( $support ) . '</div>' : '';
		$code_block     = $is_final ? '<div class="pep-email-code-card" style="background:#f3f6f8;border:1px solid #d7e1e9;border-radius:6px;margin:0 0 22px;padding:17px 18px;text-align:center;"><div style="color:#0d708e;font-family:Consolas,\'Courier New\',monospace;font-size:9px;font-weight:700;letter-spacing:1.2px;margin-bottom:7px;text-transform:uppercase;">Your private code</div><strong style="color:#002a53;display:block;font-family:Consolas,\'Courier New\',monospace;font-size:22px;letter-spacing:1px;overflow-wrap:anywhere;">{{pepselect.recovery_coupon_code}}</strong><div style="color:#5e6f80;font-family:Arial,sans-serif;font-size:12px;line-height:1.5;margin-top:7px;">Use it at checkout with the same email address.</div></div>' : '';

		$template_html = <<<'HTML'
<span style="display:none!important;font-size:1px;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden">{PREHEADER}</span>
<table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background:#e9eef4;border-collapse:collapse;margin:0;padding:0;width:100%;">
<tr><td align="center" style="padding:34px 12px;">
<table class="pep-email-shell" role="presentation" cellpadding="0" cellspacing="0" width="680" style="background:#ffffff;border-collapse:separate;border-radius:18px;box-shadow:0 18px 46px rgba(0,42,83,.14);max-width:680px;overflow:hidden;width:100%;">
<tr><td style="padding:0;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;width:100%;"><tr><td width="75%" style="background:#002a53;font-size:0;height:6px;line-height:6px;">&nbsp;</td><td width="25%" style="background:#17a1cf;font-size:0;height:6px;line-height:6px;">&nbsp;</td></tr></table></td></tr>
<tr><td class="pep-email-header" style="padding:36px 44px 27px;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;width:100%;"><tr><td align="left"><img src="{LOGO_URL}" alt="Pep Select" width="132" style="border:0;display:block;height:auto;max-width:132px;width:132px;"></td><td class="pep-email-reference" align="right" style="color:#5e6f80;font-family:Consolas,'Courier New',monospace;font-size:10px;font-weight:600;letter-spacing:1.3px;text-transform:uppercase;">{REFERENCE}</td></tr><tr><td colspan="2" style="border-bottom:1px solid #d7e1e9;font-size:0;height:27px;line-height:27px;">&nbsp;</td></tr></table></td></tr>
<tr><td class="pep-email-main" style="padding:0 44px 34px;"><div style="color:#0d708e;font-family:Consolas,'Courier New',monospace;font-size:10px;font-weight:700;letter-spacing:1.35px;margin:0 0 12px;text-transform:uppercase;">{EYEBROW}</div><h1 style="color:#001d3a;font-family:Arial,sans-serif;font-size:32px;letter-spacing:-.8px;line-height:1.18;margin:0 0 18px;">{HEADING}</h1><p style="color:#001d3a;font-family:Arial,sans-serif;font-size:15px;font-weight:700;line-height:1.62;margin:0 0 4px;">Hi {{customer.firstname}},</p><p style="color:#5e6f80;font-family:Arial,sans-serif;font-size:15px;line-height:1.62;margin:0 0 22px;">{MESSAGE}</p>{SUPPORT_BEFORE}{CODE_BLOCK}{{pepselect.cart.card}}<a class="pep-email-button" href="{{cart.checkout_url}}" style="background:#17a1cf;border:1px solid #17a1cf;border-radius:999px;color:#ffffff;display:block;font-family:Arial,sans-serif;font-size:14px;font-weight:700;padding:13px 22px;text-align:center;text-decoration:none;">View my cart</a>{SUPPORT_AFTER}</td></tr>
<tr><td style="padding:0;">{FOOTER}</td></tr>
</table>
</td></tr>
</table>
HTML;

		return strtr(
			$template_html,
			array(
				'{PREHEADER}'      => esc_html( $preheader ),
				'{LOGO_URL}'       => esc_url( $logo_url ),
				'{REFERENCE}'      => esc_html( $reference ),
				'{EYEBROW}'        => esc_html( $eyebrow ),
				'{HEADING}'        => esc_html( $heading ),
				'{MESSAGE}'        => esc_html( $message ),
				'{SUPPORT_BEFORE}' => $support_before,
				'{CODE_BLOCK}'     => $code_block,
				'{SUPPORT_AFTER}'  => $support_after,
				'{FOOTER}'         => $footer,
			)
		);
	}

	private function settings() {
		$settings = wp_parse_args( (array) get_option( self::OPTION, array() ), self::defaults() );
		$settings = apply_filters( 'pepselect_popup_settings', $settings );
		$legacy_support = array(
			'',
			'Have a question before ordering? Reply to this email or contact {support_email}.',
			'Questions before you order? Reply to this email and one of our team members will be happy to help.',
		);
		if ( in_array( trim( (string) $settings['email_support'] ), $legacy_support, true ) ) {
			$settings['email_support'] = self::defaults()['email_support'];
		}
		return $settings;
	}

	private function is_eligible_page() {
		if ( is_admin() || wp_doing_ajax() || ! class_exists( 'WooCommerce' ) || ! function_exists( 'WC' ) ) {
			return false;
		}

		if ( is_user_logged_in() && current_user_can( 'manage_woocommerce' ) ) {
			return false;
		}

		if ( function_exists( 'is_checkout' ) && is_checkout() ) {
			return false;
		}

		if ( function_exists( 'is_account_page' ) && is_account_page() ) {
			return false;
		}

		return ! is_robots() && ! is_feed();
	}

	private function replace_tokens( $value, $settings = null ) {
		$settings = is_array( $settings ) ? $settings : $this->settings();
		return strtr(
			(string) $value,
			array(
				'{discount}'      => $this->discount_label( $settings ),
				'{days}'          => (string) max( 1, absint( $settings['coupon_expiry_days'] ) ),
				'{support_email}' => 'support@pepselect.com',
			)
		);
	}

	private function discount_label( $settings = null ) {
		$settings = is_array( $settings ) ? $settings : $this->settings();
		$amount   = (float) $settings['discount_amount'];
		$number   = rtrim( rtrim( number_format( $amount, 2, '.', '' ), '0' ), '.' );
		if ( in_array( $settings['discount_type'], array( 'fixed_cart', 'fixed_product' ), true ) ) {
			return html_entity_decode( get_woocommerce_currency_symbol() . $number, ENT_QUOTES, get_bloginfo( 'charset' ) );
		}
		return $number . '%';
	}

	private function setting_timestamp( $value ) {
		$value = trim( (string) $value );
		if ( '' === $value ) {
			return 0;
		}
		try {
			$date = new DateTimeImmutable( $value, wp_timezone() );
			return $date->getTimestamp();
		} catch ( Exception $exception ) {
			return 0;
		}
	}

	private function popup_style( $prefix, $settings ) {
		$image = esc_url_raw( $settings[ $prefix . '_card_image' ] );
		return implode(
			';',
			array(
				'--pep-offer-overlay:' . $this->rgba( $settings[ $prefix . '_overlay_color' ], $settings[ $prefix . '_overlay_opacity' ] ),
				'--pep-offer-card-color:' . $settings[ $prefix . '_card_color' ],
				'--pep-offer-card-image:' . ( $image ? 'url("' . $image . '")' : 'none' ),
				'--pep-offer-card-tint:' . ( $image ? $this->rgba( $settings[ $prefix . '_card_tint_color' ], $settings[ $prefix . '_card_tint_opacity' ] ) : 'rgba(0,0,0,0)' ),
				'--pep-offer-text:' . $settings[ $prefix . '_text_color' ],
				'--pep-offer-muted:' . $settings[ $prefix . '_muted_color' ],
				'--pep-offer-accent:' . $settings[ $prefix . '_accent_color' ],
				'--pep-offer-button:' . $settings[ $prefix . '_button_color' ],
				'--pep-offer-button-text:' . $settings[ $prefix . '_button_text_color' ],
			)
		);
	}

	private function rgba( $color, $opacity ) {
		$color = sanitize_hex_color( $color );
		$color = $color ? ltrim( $color, '#' ) : '001D3A';
		if ( 3 === strlen( $color ) ) {
			$color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
		}
		return sprintf(
			'rgba(%d,%d,%d,%.2F)',
			hexdec( substr( $color, 0, 2 ) ),
			hexdec( substr( $color, 2, 2 ) ),
			hexdec( substr( $color, 4, 2 ) ),
			min( 1, max( 0, (float) $opacity ) )
		);
	}

	public function enqueue_assets() {
		$settings = $this->settings();
		if ( ( empty( $settings['enabled'] ) && empty( $settings['promo_enabled'] ) ) || ! $this->is_eligible_page() ) {
			return;
		}

		$base_url = plugin_dir_url( __FILE__ );
		wp_enqueue_style( 'pepselect-cart-recovery', $base_url . 'assets/cart-recovery.css', array(), self::VERSION );
		wp_enqueue_script( 'pepselect-cart-recovery', $base_url . 'assets/cart-recovery.js', array(), self::VERSION, true );

		wp_localize_script(
			'pepselect-cart-recovery',
			'pepSelectRecovery',
			array(
				'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
				'nonce'         => wp_create_nonce( self::NONCE ),
				'recoveryNonce' => wp_create_nonce( 'cartflows_save_cart_abandonment_data' ),
				'hasCart'       => WC()->cart && ! WC()->cart->is_empty(),
				'pageId'        => get_queried_object_id(),
				'exit'          => array(
					'enabled'     => ! empty( $settings['enabled'] ),
					'dismissDays' => max( 1, absint( $settings['dismiss_days'] ) ),
					'loadingText' => $this->replace_tokens( $settings['exit_loading'], $settings ),
				),
				'promo'         => array(
					'enabled'      => ! empty( $settings['promo_enabled'] ),
					'start'        => $this->setting_timestamp( $settings['promo_start'] ),
					'end'          => $this->setting_timestamp( $settings['promo_end'] ),
					'delaySeconds' => min( 86400, max( 0, absint( $settings['promo_delay_seconds'] ) ) ),
					'dismissDays'  => min( 365, max( 1, absint( $settings['promo_dismiss_days'] ) ) ),
					'suppressExit' => ! empty( $settings['promo_suppress_exit'] ),
					'campaignId'   => substr( hash( 'sha256', $settings['promo_start'] . '|' . $settings['promo_end'] . '|' . $settings['promo_heading'] ), 0, 16 ),
				),
			)
		);
	}

	public function render_dialog() {
		$settings = $this->settings();
		if ( ( empty( $settings['enabled'] ) && empty( $settings['promo_enabled'] ) ) || ! $this->is_eligible_page() ) {
			return;
		}
		if ( ! empty( $settings['enabled'] ) ) :
		?>
		<div class="pep-exit-offer" data-pep-popup="exit" data-pep-exit-offer style="<?php echo esc_attr( $this->popup_style( 'exit', $settings ) ); ?>" hidden>
			<button class="pep-exit-offer__veil" type="button" data-pep-exit-close aria-label="<?php esc_attr_e( 'Close offer', 'pepselect-cart-recovery' ); ?>"></button>
			<section class="pep-exit-offer__dialog" role="dialog" aria-modal="true" aria-labelledby="pep-exit-title" aria-describedby="pep-exit-copy">
				<button class="pep-exit-offer__close" type="button" data-pep-exit-close aria-label="<?php esc_attr_e( 'Close offer', 'pepselect-cart-recovery' ); ?>">&times;</button>
				<p class="pep-exit-offer__eyebrow"><?php echo esc_html( $this->replace_tokens( $settings['exit_eyebrow'], $settings ) ); ?></p>
				<h2 id="pep-exit-title"><?php echo esc_html( $this->replace_tokens( $settings['exit_heading'], $settings ) ); ?></h2>
				<div class="pep-exit-offer__copy" id="pep-exit-copy"><?php echo wp_kses_post( wpautop( $this->replace_tokens( $settings['exit_body'], $settings ) ) ); ?></div>
				<form data-pep-exit-form novalidate>
					<label class="screen-reader-text" for="pep-exit-email"><?php esc_html_e( 'Email address', 'pepselect-cart-recovery' ); ?></label>
					<div class="pep-exit-offer__fields">
						<input id="pep-exit-email" name="email" type="email" autocomplete="email" placeholder="<?php echo esc_attr( $this->replace_tokens( $settings['exit_placeholder'], $settings ) ); ?>" required>
						<button type="submit"><?php echo esc_html( $this->replace_tokens( $settings['exit_button'], $settings ) ); ?></button>
					</div>
					<input class="pep-exit-offer__trap" name="company" type="text" tabindex="-1" autocomplete="off" aria-hidden="true">
					<p class="pep-exit-offer__fineprint"><?php echo esc_html( $this->replace_tokens( $settings['exit_fineprint'], $settings ) ); ?></p>
					<p class="pep-exit-offer__message" data-pep-exit-message role="status" aria-live="polite"></p>
				</form>
				<div class="pep-exit-offer__success" data-pep-exit-success hidden>
					<p><?php echo esc_html( $this->replace_tokens( $settings['exit_success'], $settings ) ); ?></p>
					<p class="pep-exit-offer__fineprint"><?php echo esc_html( $this->replace_tokens( $settings['exit_success_note'], $settings ) ); ?></p>
				</div>
			</section>
		</div>
		<?php
		endif;

		if ( ! empty( $settings['promo_enabled'] ) ) :
			$promo_url = $settings['promo_url'] ? $settings['promo_url'] : '/shop/';
			?>
			<div class="pep-exit-offer" data-pep-popup="promo" style="<?php echo esc_attr( $this->popup_style( 'promo', $settings ) ); ?>" hidden>
				<button class="pep-exit-offer__veil" type="button" data-pep-popup-close aria-label="<?php esc_attr_e( 'Close promotion', 'pepselect-cart-recovery' ); ?>"></button>
				<section class="pep-exit-offer__dialog" role="dialog" aria-modal="true" aria-labelledby="pep-promo-title" aria-describedby="pep-promo-copy">
					<button class="pep-exit-offer__close" type="button" data-pep-popup-close aria-label="<?php esc_attr_e( 'Close promotion', 'pepselect-cart-recovery' ); ?>">&times;</button>
					<p class="pep-exit-offer__eyebrow"><?php echo esc_html( $settings['promo_eyebrow'] ); ?></p>
					<h2 id="pep-promo-title"><?php echo esc_html( $settings['promo_heading'] ); ?></h2>
					<div class="pep-exit-offer__copy" id="pep-promo-copy"><?php echo wp_kses_post( wpautop( $settings['promo_body'] ) ); ?></div>
					<?php if ( $settings['promo_code'] ) : ?>
						<div class="pep-exit-offer__promo-code"><span><?php echo esc_html( $settings['promo_code_label'] ); ?></span><strong><?php echo esc_html( $settings['promo_code'] ); ?></strong></div>
					<?php endif; ?>
					<?php if ( $settings['promo_button'] ) : ?><a class="pep-exit-offer__cta" href="<?php echo esc_url( $promo_url ); ?>"><?php echo esc_html( $settings['promo_button'] ); ?></a><?php endif; ?>
					<?php if ( $settings['promo_fineprint'] ) : ?><p class="pep-exit-offer__fineprint"><?php echo esc_html( $settings['promo_fineprint'] ); ?></p><?php endif; ?>
				</section>
			</div>
			<?php
		endif;
	}

	public function capture_offer() {
		check_ajax_referer( self::NONCE, 'security' );

		if ( ! empty( $_POST['company'] ) ) {
			wp_send_json_error( array( 'message' => __( 'We could not save that request.', 'pepselect-cart-recovery' ) ), 400 );
		}

		$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		if ( ! is_email( $email ) ) {
			wp_send_json_error( array( 'message' => __( 'That email needs a quick second look.', 'pepselect-cart-recovery' ) ), 422 );
		}

		if ( ! $this->within_rate_limit() ) {
			wp_send_json_error( array( 'message' => __( 'Too many tries for one browser. Give it a few minutes.', 'pepselect-cart-recovery' ) ), 429 );
		}

		if ( ! class_exists( 'WC_Coupon' ) || ! function_exists( 'WC' ) ) {
			wp_send_json_error( array( 'message' => __( 'The code maker took an unscheduled break. Please try again.', 'pepselect-cart-recovery' ) ), 503 );
		}

		$coupon_code = $this->coupon_for_email( $email );
		if ( ! $coupon_code ) {
			$coupon_code = $this->create_coupon( $email );
		}

		if ( ! $coupon_code ) {
			wp_send_json_error( array( 'message' => __( 'We could not create the code. Please try again.', 'pepselect-cart-recovery' ) ), 500 );
		}

		if ( WC()->session ) {
			WC()->session->set( 'pepselect_exit_email', $email );
			WC()->session->set( 'pepselect_exit_coupon', $coupon_code );
		}

		if ( WC()->customer && ! WC()->customer->get_billing_email() ) {
			WC()->customer->set_billing_email( $email );
			WC()->customer->save();
		}

		$has_cart = WC()->cart && ! WC()->cart->is_empty();
		if ( $has_cart && ! WC()->cart->has_discount( $coupon_code ) ) {
			WC()->cart->apply_coupon( $coupon_code );
		}

		if ( ! $this->send_coupon_email( $email, $coupon_code ) ) {
			wp_send_json_error( array( 'message' => __( 'Your code is ready, but the email missed its flight. Please try again.', 'pepselect-cart-recovery' ) ), 503 );
		}

		$this->add_to_fluentcrm( $email );

		wp_send_json_success(
			array( 'hasCart' => $has_cart )
		);
	}

	private function within_rate_limit() {
		$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
		$key = 'pep_exit_rate_' . hash_hmac( 'sha256', $ip, wp_salt( 'nonce' ) );
		$hit = absint( get_transient( $key ) );
		if ( $hit >= 5 ) {
			return false;
		}
		set_transient( $key, $hit + 1, 10 * MINUTE_IN_SECONDS );
		return true;
	}

	private function create_coupon( $email ) {
		$settings = $this->settings();
		$code     = '';
		for ( $attempt = 0; $attempt < 4; $attempt++ ) {
			$candidate = $settings['coupon_prefix'] . '-' . strtoupper( wp_generate_password( 8, false, false ) );
			if ( ! wc_get_coupon_id_by_code( $candidate ) ) {
				$code = $candidate;
				break;
			}
		}
		if ( ! $code ) {
			return '';
		}

		try {
			$coupon = new WC_Coupon();
			$coupon->set_code( $code );
			$coupon->set_description( sprintf( 'Pep Select %s private recovery offer. Generated automatically.', $this->discount_label( $settings ) ) );
			$coupon->set_discount_type( $settings['discount_type'] );
			$coupon->set_amount( (float) $settings['discount_amount'] );
			$coupon->set_individual_use( empty( $settings['allow_coupon_stacking'] ) );
			$coupon->set_usage_limit( absint( $settings['usage_limit'] ) );
			$coupon->set_usage_limit_per_user( absint( $settings['usage_limit_per_user'] ) );
			$coupon->set_limit_usage_to_x_items( absint( $settings['limit_usage_to_x_items'] ) );
			$coupon->set_email_restrictions( array( $email ) );
			$coupon->set_minimum_amount( (string) $settings['minimum_amount'] );
			$coupon->set_maximum_amount( (string) $settings['maximum_amount'] );
			$coupon->set_free_shipping( ! empty( $settings['free_shipping'] ) );
			$coupon->set_exclude_sale_items( ! empty( $settings['exclude_sale_items'] ) );
			$coupon->set_product_ids( array_map( 'absint', (array) $settings['product_ids'] ) );
			$coupon->set_excluded_product_ids( array_map( 'absint', (array) $settings['excluded_product_ids'] ) );
			$coupon->set_product_categories( array_map( 'absint', (array) $settings['product_category_ids'] ) );
			$coupon->set_excluded_product_categories( array_map( 'absint', (array) $settings['excluded_product_category_ids'] ) );
			$coupon->add_meta_data( 'product_brands', array_map( 'absint', (array) $settings['product_brand_ids'] ), true );
			$coupon->add_meta_data( 'exclude_product_brands', array_map( 'absint', (array) $settings['excluded_product_brand_ids'] ), true );
			$coupon->set_date_expires( time() + max( 1, absint( $settings['coupon_expiry_days'] ) ) * DAY_IN_SECONDS );
			$coupon->add_meta_data( '_pepselect_exit_offer', 1, true );
			$coupon->add_meta_data( '_pepselect_exit_email_hash', hash_hmac( 'sha256', strtolower( $email ), wp_salt( 'auth' ) ), true );
			$coupon->add_meta_data( '_pepselect_exit_offer_signature', $this->coupon_signature( $settings ), true );
			$coupon->save();
			set_transient( $this->email_coupon_key( $email ), $code, max( 1, absint( $settings['coupon_expiry_days'] ) ) * DAY_IN_SECONDS );
			return $code;
		} catch ( Exception $exception ) {
			return '';
		}
	}

	private function email_coupon_key( $email ) {
		return 'pep_exit_coupon_' . hash_hmac( 'sha256', strtolower( $email ) . '|' . $this->coupon_signature(), wp_salt( 'auth' ) );
	}

	private function coupon_signature( $settings = null ) {
		$settings = is_array( $settings ) ? $settings : $this->settings();
		$keys = array(
			'discount_type', 'discount_amount', 'coupon_prefix', 'coupon_expiry_days', 'allow_coupon_stacking',
			'usage_limit', 'usage_limit_per_user', 'limit_usage_to_x_items', 'minimum_amount', 'maximum_amount',
			'free_shipping', 'exclude_sale_items', 'product_ids', 'excluded_product_ids', 'product_category_ids', 'excluded_product_category_ids',
			'product_brand_ids', 'excluded_product_brand_ids',
		);
		$signature = array();
		foreach ( $keys as $key ) {
			$value = $settings[ $key ] ?? '';
			if ( is_array( $value ) ) {
				sort( $value );
			}
			$signature[ $key ] = $value;
		}
		return substr( hash( 'sha256', wp_json_encode( $signature ) ), 0, 20 );
	}

	private function coupon_for_email( $email, $require_current_signature = true ) {
		$signature = $this->coupon_signature();
		$code = get_transient( $this->email_coupon_key( $email ) );
		if ( ! $code ) {
			$coupon_ids = get_posts(
				array(
					'post_type'      => 'shop_coupon',
					'post_status'    => 'publish',
					'posts_per_page' => 1,
					'fields'         => 'ids',
					'orderby'        => 'date',
					'order'          => 'DESC',
					'meta_key'       => '_pepselect_exit_email_hash',
					'meta_value'     => hash_hmac( 'sha256', strtolower( $email ), wp_salt( 'auth' ) ),
				)
			);
			if ( $coupon_ids ) {
				$stored_coupon = new WC_Coupon( $coupon_ids[0] );
				$code          = $stored_coupon->get_code();
			}
		}
		if ( ! $code || ! wc_get_coupon_id_by_code( $code ) ) {
			return '';
		}
		$coupon = new WC_Coupon( $code );
		if ( $require_current_signature && $signature !== (string) $coupon->get_meta( '_pepselect_exit_offer_signature', true ) ) {
			return '';
		}
		return $coupon->get_date_expires() && $coupon->get_date_expires()->getTimestamp() < time() ? '' : $code;
	}

	private function is_final_template( $email_data ) {
		$settings = $this->settings();
		return ! empty( $settings['final_template_id'] )
			&& is_object( $email_data )
			&& absint( $email_data->email_template_id ?? 0 ) === absint( $settings['final_template_id'] );
	}

	private function recovery_cart_card_html( $email_data ) {
		$cart_items = maybe_unserialize( $email_data->cart_contents ?? array() );
		if ( ! is_array( $cart_items ) || ! $cart_items ) {
			return '{{cart.product.table}}';
		}

		$item_count = 0;
		$rows       = '';
		foreach ( $cart_items as $cart_item ) {
			$quantity = max( 1, absint( $cart_item['quantity'] ?? 1 ) );
			$item_count += $quantity;
			$product_id = ! empty( $cart_item['variation_id'] ) ? absint( $cart_item['variation_id'] ) : absint( $cart_item['product_id'] ?? 0 );
			$product    = $product_id ? wc_get_product( $product_id ) : false;
			$name       = $product ? $product->get_name() : __( 'Saved item', 'pepselect-cart-recovery' );
			$line_total = isset( $cart_item['line_total'] ) ? (float) $cart_item['line_total'] : 0;
			$quantity_label = $quantity > 1 ? ' &times; ' . $quantity : '';

			$rows .= '<tr><td class="pep-email-cart-thumb-cell" width="66" style="padding:14px 0 14px 18px;vertical-align:middle;"><span style="background:#eef3f6;border-radius:6px;color:#0d708e;display:inline-block;font-family:Consolas,\'Courier New\',monospace;font-size:12px;font-weight:700;line-height:44px;text-align:center;width:44px;">PS</span></td><td class="pep-email-cart-name" style="color:#002a53;font-family:Arial,sans-serif;font-size:13px;font-weight:700;line-height:1.45;padding:14px 10px;vertical-align:middle;">' . esc_html( $name ) . '<span style="color:#748596;font-family:Consolas,\'Courier New\',monospace;font-size:10px;font-weight:400;">' . wp_kses_post( $quantity_label ) . '</span></td><td class="pep-email-cart-price" align="right" style="color:#001d3a;font-family:Consolas,\'Courier New\',monospace;font-size:11px;padding:14px 18px 14px 8px;vertical-align:middle;white-space:nowrap;">' . wp_kses_post( wc_price( $line_total ) ) . '</td></tr>';
		}

		$count_label = sprintf(
			/* translators: %d: number of items in the saved cart. */
			_n( '%d item', '%d items', $item_count, 'pepselect-cart-recovery' ),
			$item_count
		);

		return '<table class="pep-email-cart" role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border:1px solid #d7e1e9;border-collapse:separate;border-radius:8px;margin:0 0 22px;overflow:hidden;width:100%;"><tr><td colspan="3" style="background:#f8fafc;border-bottom:1px solid #d7e1e9;padding:14px 18px;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;width:100%;"><tr><td style="color:#002a53;font-family:Georgia,\'Times New Roman\',serif;font-size:18px;font-weight:700;">Your saved items</td><td align="right" style="color:#5e6f80;font-family:Consolas,\'Courier New\',monospace;font-size:9px;text-transform:uppercase;">' . esc_html( $count_label ) . '</td></tr></table></td></tr>' . $rows . '</table>';
	}

	public function attach_coupon_to_recovery_email( $email_data, $preview_email ) {
		if ( $preview_email && $this->is_final_template( $email_data ) && ! empty( $email_data->email_body ) ) {
			$email_data->email_body = str_replace( array( '{{pepselect.bonus_coupon_code}}', '{{pepselect.recovery_coupon_code}}' ), 'PEP-PREVIEW', $email_data->email_body );
		}

		if ( ! $preview_email && is_object( $email_data ) && ! empty( $email_data->email ) ) {
			$email    = sanitize_email( $email_data->email );
			$is_final = $this->is_final_template( $email_data );
			$code     = $this->coupon_for_email( $email, ! $is_final );
			if ( ! $code && $is_final ) {
				$code = $this->create_coupon( $email );
			}
			if ( $code ) {
				$email_data->coupon_code = $code;
				if ( $is_final && ! empty( $email_data->email_body ) ) {
					$email_data->email_body = str_replace( array( '{{pepselect.bonus_coupon_code}}', '{{pepselect.recovery_coupon_code}}' ), esc_html( $code ), $email_data->email_body );
				}
			}
		}

		if ( is_object( $email_data ) && ! empty( $email_data->email_body ) ) {
			if ( false !== strpos( $email_data->email_body, '{{pepselect.cart.card}}' ) ) {
				$email_data->email_body = str_replace( '{{pepselect.cart.card}}', $this->recovery_cart_card_html( $email_data ), $email_data->email_body );
			}
			$email_data->email_body = $this->normalize_recovery_email_body( $email_data->email_body );
			if ( ! $this->has_company_footer( $email_data->email_body ) ) {
				$email_data->email_body .= $this->company_footer_html();
			}
		}

		return $email_data;
	}

	/**
	 * Keep database-authored recovery emails aligned with the shared footer and
	 * protect the injected cart-product table from clipping on phones.
	 *
	 * @param string $body Recovery template HTML before WCAR expands tokens.
	 * @return string
	 */
	private function normalize_recovery_email_body( $body ) {
		$body = (string) $body;
		$body = str_replace(
			array(
				'Questions before you order? Reply to this email and one of our team members will be happy to help.',
				'Need an answer first? Reply to this email and one of our team members will be happy to help.',
				'If something is not clear, reply to this email and one of our team members will be happy to help.',
				'Have a question first? Reply here or write to support@pepselect.com. Ordering is optional. Our humans will answer your questions.',
				'Need an answer first? Reply to this email. One of our team members will help.',
				'Have a question first? Reply here or write to support@pepselect.com. Ordering is optional. One of our team members will help.',
			),
			'Have a question? Reply to this email, and one of our team members will be in touch shortly.',
			$body
		);

		$mobile_css = <<<'HTML'
<style type="text/css" data-pepselect-mobile-email="1">
@media only screen and (max-width:520px){
.pep-recovery-email{box-sizing:border-box!important;max-width:100%!important;width:100%!important}
.pep-recovery-email>table{box-sizing:border-box!important;margin:0!important;max-width:100%!important;padding:8px!important;width:100%!important}
.pep-email-shell{border-radius:14px!important;box-sizing:border-box!important;max-width:100%!important;width:100%!important}
.pep-email-header{padding:28px 22px 22px!important}
.pep-email-reference{display:none!important}
.pep-email-main{padding:0 22px 28px!important}
.pep-email-main h1{font-size:27px!important;letter-spacing:-.5px!important}
.pep-email-button{box-sizing:border-box!important;width:100%!important}
.pep-email-cart{box-sizing:border-box!important;max-width:100%!important;table-layout:fixed!important;width:100%!important}
.pep-email-cart-thumb-cell{width:62px!important}
.pep-email-cart-name{overflow-wrap:anywhere!important;padding-left:4px!important;padding-right:4px!important;word-break:break-word!important}
.pep-email-cart-price{padding-left:4px!important;padding-right:14px!important;width:72px!important}
.pep-recovery-email td[style*="padding: 30px 36px 22px"]{padding:26px 18px 20px!important}
.pep-recovery-email td[style*="padding: 0 36px 32px"]{padding:0 18px 26px!important}
.pep-recovery-email table.shop_table,.pep-recovery-email table.woocommerce-table,.pep-recovery-email table.wcf-ca-cart-products,.pep-recovery-email table.cartflows-ca-cart-products{box-sizing:border-box!important;max-width:100%!important;table-layout:fixed!important;width:100%!important}
.pep-recovery-email table.shop_table th,.pep-recovery-email table.shop_table td,.pep-recovery-email table.woocommerce-table th,.pep-recovery-email table.woocommerce-table td,.pep-recovery-email table.wcf-ca-cart-products th,.pep-recovery-email table.wcf-ca-cart-products td,.pep-recovery-email table.cartflows-ca-cart-products th,.pep-recovery-email table.cartflows-ca-cart-products td{box-sizing:border-box!important;min-width:0!important;overflow-wrap:anywhere!important;padding-left:7px!important;padding-right:7px!important;word-break:break-word!important}
.pep-recovery-email table.shop_table img,.pep-recovery-email table.woocommerce-table img,.pep-recovery-email table.wcf-ca-cart-products img,.pep-recovery-email table.cartflows-ca-cart-products img{height:auto!important;max-width:52px!important;width:52px!important}
.pep-recovery-email table.shop_table th:nth-last-child(-n+2),.pep-recovery-email table.shop_table td:nth-last-child(-n+2),.pep-recovery-email table.woocommerce-table th:nth-last-child(-n+2),.pep-recovery-email table.woocommerce-table td:nth-last-child(-n+2){width:64px!important}
.pep-recovery-email a{overflow-wrap:anywhere!important;word-break:break-word!important}
}
</style>
HTML;

		if ( false !== strpos( $body, 'data-pepselect-mobile-email="1"' ) ) {
			return $body;
		}

		return $mobile_css . '<div class="pep-recovery-email">' . $body . '</div>';
	}

	/**
	 * Detect either the current footer or the complete legacy company footer.
	 *
	 * Saved CartFlows templates may already contain the company block. Detecting
	 * it is safer than deleting a broad table row from database-authored HTML.
	 *
	 * @param string $body Recovery email HTML.
	 * @return bool
	 */
	private function has_company_footer( $body ) {
		$body = (string) $body;
		if ( false !== strpos( $body, 'data-pepselect-company-footer="1"' ) ) {
			return true;
		}

		return false !== stripos( $body, 'Pep Select is an American-owned and operated company.' )
			&& false !== stripos( $body, '2090 Baker Rd, Ste 304 #A85' )
			&& false !== stripos( $body, 'support@pepselect.com' )
			&& false !== stripos( $body, '1 (833) 737-7528' );
	}

	public function require_recovery_code_for_final_email( $should_send, $email_data ) {
		if ( ! $should_send ) {
			return false;
		}

		if ( ! is_object( $email_data ) || empty( trim( wp_strip_all_tags( (string) ( $email_data->email_body ?? '' ) ) ) ) ) {
			return false;
		}

		if ( ! $this->is_final_template( $email_data ) ) {
			return $should_send;
		}

		if ( empty( $email_data->email ) ) {
			return false;
		}

		$email = sanitize_email( $email_data->email );
		$code  = $this->coupon_for_email( $email, false );
		return (bool) ( $code ? $code : $this->create_coupon( $email ) );
	}

	public function attach_coupon_to_recovery_link( $token_data, $email_data ) {
		if ( is_object( $email_data ) && ! empty( $email_data->email ) ) {
			$email    = sanitize_email( $email_data->email );
			$is_final = $this->is_final_template( $email_data );
			$code     = $this->coupon_for_email( $email, ! $is_final );
			if ( ! $code && $is_final ) {
				$code = $this->create_coupon( $email );
			}
			if ( $code ) {
				$token_data['wcf_coupon_code'] = $code;
			}
		}
		return $token_data;
	}

	public function recovery_headers( $headers ) {
		if ( is_array( $headers ) ) {
			$headers[] = 'Reply-To: Pep Select <support@pepselect.com>';
			return array_values( array_unique( array_filter( $headers ) ) );
		}

		$headers = preg_replace( '/^Reply-To:.*(?:\r\n|\n|\r)/mi', '', (string) $headers );
		return rtrim( $headers ) . "\r\nReply-To: Pep Select <support@pepselect.com>\r\n";
	}

	/**
	 * Shared company footer for cart recovery and signup-code messages.
	 *
	 * This plugin is intentionally self-contained so its outgoing email still
	 * has the complete footer if the storefront theme is temporarily switched.
	 *
	 * @param string $context_html Optional delivery or unsubscribe context.
	 * @return string
	 */
	private function company_footer_html( $context_html = '' ) {
		$flag_url = plugin_dir_url( __FILE__ ) . 'assets/us-flag-email.png';
		ob_start();
		?>
		<table border="0" cellpadding="0" cellspacing="0" data-pepselect-company-footer="1" role="presentation" width="100%" style="background-color:#002A53;border-collapse:separate;width:100%;">
			<tr><td align="center" style="padding:30px 32px 28px;">
				<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="margin:0 auto 20px;"><tr><td style="padding:0 8px 0 0;vertical-align:middle;"><img alt="United States flag" height="13" src="<?php echo esc_url( $flag_url ); ?>" style="border:0;border-radius:2px;display:block;height:13px;width:24px;" width="24"></td><td style="color:#D8E6F2;font-family:'Plus Jakarta Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;font-size:13px;font-weight:400;line-height:1.45;vertical-align:middle;">Pep Select is an American-owned and operated company.</td></tr></table>
				<p style="border-top:1px solid #315775;color:#D8E6F2;font-family:'Plus Jakarta Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;font-size:12px;line-height:1.7;margin:0;padding:20px 0 0;"><strong style="color:#FFFFFF;font-size:14px;">Pep Select</strong><br><a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color:#7DD6F2;text-decoration:none;">pepselect.com</a><br>2090 Baker Rd, Ste 304 #A85<br>Kennesaw, GA 30144<br><a href="mailto:support@pepselect.com" style="color:#7DD6F2;text-decoration:none;">support@pepselect.com</a><br><a href="tel:+18337377528" style="color:#7DD6F2;text-decoration:none;">1 (833) 737-7528</a></p>
				<?php if ( '' !== trim( $context_html ) ) : ?>
					<p style="color:#AFC2D3;font-family:'Plus Jakarta Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;font-size:11px;line-height:1.6;margin:20px 0 0;"><?php echo wp_kses_post( $context_html ); ?></p>
				<?php endif; ?>
				<p style="color:#8FA8BA;font-family:'Plus Jakarta Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;font-size:10px;line-height:1.5;margin:16px 0 0;">&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> Pep Select. <?php esc_html_e( 'All rights reserved.', 'pepselect-cart-recovery' ); ?></p>
			</td></tr>
		</table>
		<?php
		return trim( (string) ob_get_clean() );
	}

	private function add_to_fluentcrm( $email ) {
		$settings = $this->settings();
		$list_id  = absint( $settings['fluentcrm_list_id'] );
		if ( ! $list_id || ! function_exists( 'FluentCrmApi' ) ) {
			return;
		}

		try {
			$contact = FluentCrmApi( 'contacts' )->createOrUpdate(
				array(
					'email'  => $email,
					'status' => 'subscribed',
					'source' => 'Pep Select email offer',
				)
			);
			if ( $contact && method_exists( $contact, 'attachLists' ) ) {
				$contact->attachLists( array( $list_id ) );
			}
		} catch ( Throwable $throwable ) {
			// Coupon delivery must not fail when the optional CRM integration is unavailable.
		}
	}

	private function send_coupon_email( $email, $coupon_code ) {
		$settings = $this->settings();
		$subject  = $this->replace_tokens( $settings['email_subject'], $settings );
		$body    = $this->coupon_email_body( $coupon_code );
		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
			'From: Pep Select <support@pepselect.com>',
			'Reply-To: Pep Select <support@pepselect.com>',
		);
		return (bool) wp_mail( $email, $subject, $body, $headers );
	}

	private function coupon_email_body( $coupon_code ) {
		$settings            = $this->settings();
		$pep_coupon_code     = $coupon_code;
		$pep_shop_url        = home_url( '/shop/' );
		$pep_support_email   = 'support@pepselect.com';
		$pep_unsubscribe_url = 'mailto:support@pepselect.com?subject=' . rawurlencode( 'Unsubscribe me from Pep Select emails' );
		$pep_company_footer_html = $this->company_footer_html(
			sprintf(
				'%1$s<br><a href="%2$s" style="color:#7DD6F2;text-decoration:underline;">%3$s</a> &middot; %4$s',
				esc_html__( 'You received this email because you requested a Pep Select discount code. Your signup also includes occasional product and restock emails.', 'pepselect-cart-recovery' ),
				esc_url( $pep_unsubscribe_url ),
				esc_html__( 'Unsubscribe anytime', 'pepselect-cart-recovery' ),
				esc_html__( 'For laboratory research use only.', 'pepselect-cart-recovery' )
			)
		);
		$pep_logo_url        = 'https://pepselect.com/wp-content/uploads/2026/06/Logo_Pepselect_Whitebackground-1.png';
		$pep_email_copy      = array(
			'preheader'  => $this->replace_tokens( $settings['email_preheader'], $settings ),
			'label'      => $this->replace_tokens( $settings['email_label'], $settings ),
			'eyebrow'    => $this->replace_tokens( $settings['email_eyebrow'], $settings ),
			'heading'    => $this->replace_tokens( $settings['email_heading'], $settings ),
			'greeting'   => $this->replace_tokens( $settings['email_greeting'], $settings ),
			'intro'      => $this->replace_tokens( $settings['email_intro'], $settings ),
			'code_label' => $this->replace_tokens( $settings['email_code_label'], $settings ),
			'code_note'  => $this->replace_tokens( $settings['email_code_note'], $settings ),
			'extra'      => $this->replace_tokens( $settings['email_extra'], $settings ),
			'button'     => $this->replace_tokens( $settings['email_button'], $settings ),
			'support'    => self::defaults()['email_support'],
		);

		ob_start();
		include __DIR__ . '/templates/coupon-email.php';
		return (string) ob_get_clean();
	}

	public function register_settings() {
		register_setting( 'pepselect_cart_recovery', self::OPTION, array( $this, 'sanitize_settings' ) );
	}

	/**
	 * Provide one stable, authenticated settings surface for the future Ops UI.
	 * WordPress Application Passwords can authenticate the external client without
	 * adding a second settings store or exposing popup controls publicly.
	 */
	public function register_rest_routes() {
		register_rest_route(
			'pepselect/v1',
			'/popup-settings',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'rest_get_settings' ),
					'permission_callback' => array( $this, 'rest_settings_permission' ),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'rest_update_settings' ),
					'permission_callback' => array( $this, 'rest_settings_permission' ),
				),
			)
		);
	}

	public function rest_settings_permission() {
		if ( current_user_can( 'manage_woocommerce' ) ) {
			return true;
		}
		return new WP_Error( 'pepselect_popup_forbidden', __( 'You do not have permission to manage popup settings.', 'pepselect-cart-recovery' ), array( 'status' => 403 ) );
	}

	public function rest_get_settings() {
		return rest_ensure_response(
			array(
				'version'  => self::VERSION,
				'settings' => $this->settings(),
			)
		);
	}

	public function rest_update_settings( WP_REST_Request $request ) {
		$payload = $request->get_json_params();
		$payload = is_array( $payload ) && isset( $payload['settings'] ) && is_array( $payload['settings'] ) ? $payload['settings'] : $payload;
		if ( ! is_array( $payload ) ) {
			return new WP_Error( 'pepselect_popup_invalid_payload', __( 'Send popup settings as a JSON object.', 'pepselect-cart-recovery' ), array( 'status' => 400 ) );
		}

		$merged    = array_merge( $this->settings(), $payload );
		$sanitized = $this->sanitize_settings( $merged );
		if ( ! empty( $payload['promo_enabled'] ) && empty( $sanitized['promo_enabled'] ) ) {
			return new WP_Error( 'pepselect_popup_invalid_schedule', __( 'The campaign popup needs a valid start and end time before it can be enabled.', 'pepselect-cart-recovery' ), array( 'status' => 400 ) );
		}

		update_option( self::OPTION, $sanitized );
		do_action( 'pepselect_popup_settings_updated', $sanitized, 'rest', get_current_user_id() );

		return rest_ensure_response(
			array(
				'version'  => self::VERSION,
				'settings' => $sanitized,
			)
		);
	}

	public function sanitize_settings( $input ) {
		$defaults = self::defaults();
		$input    = is_array( $input ) ? $input : array();
		$output   = $defaults;

		foreach ( array( 'enabled', 'promo_enabled', 'promo_suppress_exit', 'allow_coupon_stacking', 'free_shipping', 'exclude_sale_items' ) as $key ) {
			$output[ $key ] = empty( $input[ $key ] ) ? 0 : 1;
		}

		$output['discount_type'] = in_array( $input['discount_type'] ?? '', array( 'percent', 'fixed_cart', 'fixed_product' ), true ) ? $input['discount_type'] : 'percent';
		$raw_amount              = $input['discount_amount'] ?? $defaults['discount_amount'];
		$amount                  = (float) ( function_exists( 'wc_format_decimal' ) ? wc_format_decimal( $raw_amount ) : $raw_amount );
		$output['discount_amount'] = (string) min( 'percent' === $output['discount_type'] ? 100 : 10000, max( 0.01, $amount ) );

		$prefix = strtoupper( preg_replace( '/[^A-Z0-9-]/', '', (string) ( $input['coupon_prefix'] ?? 'PEP' ) ) );
		$output['coupon_prefix']      = substr( $prefix ? $prefix : 'PEP', 0, 16 );
		$output['coupon_expiry_days'] = min( 365, max( 1, absint( $input['coupon_expiry_days'] ?? 7 ) ) );
		$output['dismiss_days']       = min( 365, max( 1, absint( $input['dismiss_days'] ?? 30 ) ) );
		$output['fluentcrm_list_id']  = absint( $input['fluentcrm_list_id'] ?? 0 );
		$output['final_template_id']  = absint( $input['final_template_id'] ?? 0 );
		$output['usage_limit']        = max( 0, absint( $input['usage_limit'] ?? $defaults['usage_limit'] ) );
		$output['usage_limit_per_user'] = max( 0, absint( $input['usage_limit_per_user'] ?? $defaults['usage_limit_per_user'] ) );
		$output['limit_usage_to_x_items'] = max( 0, absint( $input['limit_usage_to_x_items'] ?? $defaults['limit_usage_to_x_items'] ) );
		foreach ( array( 'minimum_amount', 'maximum_amount' ) as $key ) {
			$value          = $input[ $key ] ?? '';
			$output[ $key ] = '' === trim( (string) $value ) ? '' : (string) max( 0, (float) ( function_exists( 'wc_format_decimal' ) ? wc_format_decimal( $value ) : $value ) );
		}
		foreach ( array( 'product_ids', 'excluded_product_ids', 'product_category_ids', 'excluded_product_category_ids', 'product_brand_ids', 'excluded_product_brand_ids' ) as $key ) {
			$output[ $key ] = array_values( array_unique( array_filter( array_map( 'absint', (array) ( $input[ $key ] ?? array() ) ) ) ) );
		}
		$output['promo_delay_seconds'] = min( 86400, max( 0, absint( $input['promo_delay_seconds'] ?? 8 ) ) );
		$output['promo_dismiss_days']  = min( 365, max( 1, absint( $input['promo_dismiss_days'] ?? 7 ) ) );

		$text_fields = array(
			'exit_eyebrow', 'exit_heading', 'exit_placeholder', 'exit_button', 'exit_loading', 'exit_fineprint', 'exit_success', 'exit_success_note',
			'email_subject', 'email_preheader', 'email_label', 'email_eyebrow', 'email_heading', 'email_greeting', 'email_intro',
			'email_code_label', 'email_code_note', 'email_extra', 'email_button', 'email_support',
			'promo_eyebrow', 'promo_heading', 'promo_code_label', 'promo_code', 'promo_button', 'promo_fineprint',
		);
		foreach ( $text_fields as $key ) {
			$output[ $key ] = sanitize_text_field( $input[ $key ] ?? $defaults[ $key ] );
		}

		foreach ( array( 'exit_body', 'promo_body' ) as $key ) {
			$output[ $key ] = sanitize_textarea_field( $input[ $key ] ?? $defaults[ $key ] );
		}

		foreach ( array( 'promo_start', 'promo_end' ) as $key ) {
			$value          = sanitize_text_field( $input[ $key ] ?? '' );
			$output[ $key ] = preg_match( '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $value ) ? $value : '';
		}

		$output['promo_url'] = esc_url_raw( $input['promo_url'] ?? $defaults['promo_url'] );
		foreach ( array( 'exit_card_image', 'promo_card_image' ) as $key ) {
			$output[ $key ] = esc_url_raw( $input[ $key ] ?? '' );
		}

		$color_fields = array(
			'exit_overlay_color', 'exit_card_color', 'exit_card_tint_color', 'exit_text_color', 'exit_muted_color', 'exit_accent_color', 'exit_button_color', 'exit_button_text_color',
			'promo_overlay_color', 'promo_card_color', 'promo_card_tint_color', 'promo_text_color', 'promo_muted_color', 'promo_accent_color', 'promo_button_color', 'promo_button_text_color',
		);
		foreach ( $color_fields as $key ) {
			$output[ $key ] = sanitize_hex_color( $input[ $key ] ?? '' ) ?: $defaults[ $key ];
		}

		foreach ( array( 'exit_overlay_opacity', 'exit_card_tint_opacity', 'promo_overlay_opacity', 'promo_card_tint_opacity' ) as $key ) {
			$output[ $key ] = (string) min( 1, max( 0, (float) ( $input[ $key ] ?? $defaults[ $key ] ) ) );
		}

		if ( $output['promo_enabled'] && ( ! $output['promo_start'] || ! $output['promo_end'] ) ) {
			add_settings_error( self::OPTION, 'promo_dates_required', __( 'Add both a start and end time before enabling the scheduled promotion.', 'pepselect-cart-recovery' ) );
			$output['promo_enabled'] = 0;
		} elseif ( $output['promo_start'] && $output['promo_end'] && $this->setting_timestamp( $output['promo_end'] ) <= $this->setting_timestamp( $output['promo_start'] ) ) {
			add_settings_error( self::OPTION, 'promo_dates', __( 'The promotion end must be later than its start.', 'pepselect-cart-recovery' ) );
			$output['promo_enabled'] = 0;
		}

		return $output;
	}

	public function register_settings_page() {
		add_submenu_page(
			'woocommerce',
			__( 'Pep Select Popups', 'pepselect-cart-recovery' ),
			__( 'Popups', 'pepselect-cart-recovery' ),
			'manage_woocommerce',
			'pepselect-cart-recovery',
			array( $this, 'settings_page' )
		);
	}

	public function enqueue_admin_assets( $hook_suffix ) {
		if ( 'woocommerce_page_pepselect-cart-recovery' !== $hook_suffix ) {
			return;
		}
		wp_enqueue_media();
		wp_enqueue_style( 'woocommerce_admin_styles' );
		wp_enqueue_script( 'wc-enhanced-select' );
		wp_enqueue_style( 'pepselect-cart-recovery-admin', plugin_dir_url( __FILE__ ) . 'assets/admin.css', array(), self::VERSION );
		wp_enqueue_script( 'pepselect-cart-recovery-admin', plugin_dir_url( __FILE__ ) . 'assets/admin.js', array(), self::VERSION, true );
		wp_localize_script(
			'pepselect-cart-recovery-admin',
			'pepSelectPopupAdmin',
			array(
				'discount' => $this->discount_label(),
				'days'     => (string) max( 1, absint( $this->settings()['coupon_expiry_days'] ) ),
				'support'  => 'support@pepselect.com',
			)
		);
	}

	private function admin_field( $settings, $key, $label, $description = '', $type = 'text', $attributes = array() ) {
		$name  = self::OPTION . '[' . $key . ']';
		$value = $settings[ $key ] ?? '';
		if ( 'email_support' === $key ) {
			$value = self::defaults()['email_support'];
		}
		?>
		<div class="pep-recovery-field">
			<label for="pep-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label>
			<?php if ( 'textarea' === $type ) : ?>
				<textarea id="pep-<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $name ); ?>" data-pep-setting="<?php echo esc_attr( $key ); ?>" rows="3"><?php echo esc_textarea( $value ); ?></textarea>
			<?php elseif ( 'image' === $type ) : ?>
				<div class="pep-recovery-image-field"><input id="pep-<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $name ); ?>" data-pep-setting="<?php echo esc_attr( $key ); ?>" type="url" value="<?php echo esc_attr( $value ); ?>"><button class="button" type="button" data-pep-media-target="pep-<?php echo esc_attr( $key ); ?>"><?php esc_html_e( 'Choose image', 'pepselect-cart-recovery' ); ?></button></div>
			<?php else : ?>
				<input id="pep-<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $name ); ?>" data-pep-setting="<?php echo esc_attr( $key ); ?>" type="<?php echo esc_attr( $type ); ?>" value="<?php echo esc_attr( $value ); ?>" <?php foreach ( $attributes as $attribute => $attribute_value ) { echo esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '" '; } ?>>
			<?php endif; ?>
			<?php if ( $description ) : ?><p class="description"><?php echo wp_kses_post( $description ); ?></p><?php endif; ?>
		</div>
		<?php
	}

	private function admin_colors( $settings, $prefix ) {
		$labels = array(
			'overlay_color'     => array( __( 'Page overlay color', 'pepselect-cart-recovery' ), __( 'The color covering the website behind the popup.', 'pepselect-cart-recovery' ) ),
			'overlay_opacity'   => array( __( 'Page overlay strength', 'pepselect-cart-recovery' ), __( '0 is invisible. 1 completely hides the page. 0.5 is the current balanced setting.', 'pepselect-cart-recovery' ) ),
			'card_color'        => array( __( 'Popup background color', 'pepselect-cart-recovery' ), __( 'The solid color inside the popup.', 'pepselect-cart-recovery' ) ),
			'card_image'        => array( __( 'Popup background image', 'pepselect-cart-recovery' ), __( 'Optional image behind the popup copy. Leave blank for a solid background.', 'pepselect-cart-recovery' ) ),
			'card_tint_color'   => array( __( 'Image tint color', 'pepselect-cart-recovery' ), __( 'A color layer placed over the background image so the words remain readable.', 'pepselect-cart-recovery' ) ),
			'card_tint_opacity' => array( __( 'Image tint strength', 'pepselect-cart-recovery' ), __( 'Raise this when the background image makes the words hard to read.', 'pepselect-cart-recovery' ) ),
			'text_color'        => array( __( 'Heading color', 'pepselect-cart-recovery' ), __( 'Changes the large headline.', 'pepselect-cart-recovery' ) ),
			'muted_color'       => array( __( 'Body text color', 'pepselect-cart-recovery' ), __( 'Changes the paragraph and small supporting text.', 'pepselect-cart-recovery' ) ),
			'accent_color'      => array( __( 'Small label color', 'pepselect-cart-recovery' ), __( 'Changes the short all-caps line above the headline.', 'pepselect-cart-recovery' ) ),
			'button_color'      => array( __( 'Button color', 'pepselect-cart-recovery' ), __( 'Changes the main action button.', 'pepselect-cart-recovery' ) ),
			'button_text_color' => array( __( 'Button text color', 'pepselect-cart-recovery' ), __( 'Changes the words inside the action button.', 'pepselect-cart-recovery' ) ),
		);
		foreach ( $labels as $suffix => $field ) {
			$key = $prefix . '_' . $suffix;
			if ( 'card_image' === $suffix ) {
				$this->admin_field( $settings, $key, $field[0], $field[1], 'image' );
			} elseif ( false !== strpos( $suffix, 'opacity' ) ) {
				$this->admin_field( $settings, $key, $field[0], $field[1], 'number', array( 'min' => '0', 'max' => '1', 'step' => '0.01' ) );
			} else {
				$this->admin_field( $settings, $key, $field[0], $field[1], 'color' );
			}
		}
	}

	private function admin_checkbox( $settings, $key, $label, $description ) {
		?>
		<label class="pep-recovery-check"><input name="<?php echo esc_attr( self::OPTION ); ?>[<?php echo esc_attr( $key ); ?>]" data-pep-setting="<?php echo esc_attr( $key ); ?>" type="checkbox" value="1" <?php checked( ! empty( $settings[ $key ] ) ); ?>><span><b><?php echo esc_html( $label ); ?></b><small><?php echo esc_html( $description ); ?></small></span></label>
		<?php
	}

	private function admin_product_selector( $settings, $key, $label, $description ) {
		$ids = array_map( 'absint', (array) ( $settings[ $key ] ?? array() ) );
		?>
		<div class="pep-recovery-field pep-recovery-field--wide">
			<label for="pep-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label>
			<select id="pep-<?php echo esc_attr( $key ); ?>" class="wc-product-search" multiple="multiple" style="width:100%" name="<?php echo esc_attr( self::OPTION ); ?>[<?php echo esc_attr( $key ); ?>][]" data-placeholder="<?php esc_attr_e( 'Search for a product…', 'pepselect-cart-recovery' ); ?>" data-action="woocommerce_json_search_products_and_variations">
				<?php foreach ( $ids as $product_id ) : $product = wc_get_product( $product_id ); if ( $product ) : ?>
					<option value="<?php echo esc_attr( $product_id ); ?>" selected><?php echo esc_html( wp_strip_all_tags( $product->get_formatted_name() ) ); ?></option>
				<?php endif; endforeach; ?>
			</select>
			<p class="description"><?php echo esc_html( $description ); ?></p>
		</div>
		<?php
	}

	private function admin_term_selector( $settings, $key, $taxonomy, $label, $description ) {
		if ( ! taxonomy_exists( $taxonomy ) ) {
			return;
		}
		$selected = array_map( 'absint', (array) ( $settings[ $key ] ?? array() ) );
		$terms    = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => false ) );
		$terms    = is_wp_error( $terms ) ? array() : $terms;
		?>
		<div class="pep-recovery-field pep-recovery-field--wide">
			<label for="pep-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label>
			<select id="pep-<?php echo esc_attr( $key ); ?>" class="wc-enhanced-select" multiple="multiple" style="width:100%" name="<?php echo esc_attr( self::OPTION ); ?>[<?php echo esc_attr( $key ); ?>][]" data-placeholder="<?php esc_attr_e( 'Any', 'pepselect-cart-recovery' ); ?>">
				<?php foreach ( $terms as $term ) : ?>
					<option value="<?php echo esc_attr( $term->term_id ); ?>" <?php selected( in_array( (int) $term->term_id, $selected, true ) ); ?>><?php echo esc_html( $term->name ); ?></option>
				<?php endforeach; ?>
			</select>
			<p class="description"><?php echo esc_html( $description ); ?></p>
		</div>
		<?php
	}

	private function admin_preview( $settings, $prefix ) {
		$is_exit = 'exit' === $prefix;
		?>
		<aside class="pep-recovery-preview" data-pep-preview="<?php echo esc_attr( $prefix ); ?>" style="<?php echo esc_attr( $this->popup_style( $prefix, $settings ) ); ?>">
			<div class="pep-recovery-preview__toolbar">
				<div><strong><?php esc_html_e( 'Live preview', 'pepselect-cart-recovery' ); ?></strong><span><?php esc_html_e( 'Updates while you type. Save to publish.', 'pepselect-cart-recovery' ); ?></span></div>
				<div class="pep-recovery-device" role="group" aria-label="<?php esc_attr_e( 'Preview size', 'pepselect-cart-recovery' ); ?>">
					<button type="button" class="is-active" data-pep-device="desktop" aria-pressed="true"><?php esc_html_e( 'Desktop', 'pepselect-cart-recovery' ); ?></button>
					<button type="button" data-pep-device="mobile" aria-pressed="false"><?php esc_html_e( 'Mobile', 'pepselect-cart-recovery' ); ?></button>
				</div>
			</div>
			<div class="pep-recovery-preview__stage">
				<div class="pep-recovery-preview__site"><span></span><span></span><span></span></div>
				<div class="pep-recovery-preview__overlay"></div>
				<div class="pep-recovery-preview__popup">
					<div class="pep-recovery-preview__tint"></div>
					<button type="button" class="pep-recovery-preview__close" aria-label="<?php esc_attr_e( 'Preview close button', 'pepselect-cart-recovery' ); ?>">&times;</button>
					<div class="pep-recovery-preview__content">
						<p class="pep-recovery-preview__eyebrow" data-pep-preview-bind="<?php echo esc_attr( $prefix ); ?>_eyebrow"><?php echo esc_html( $this->replace_tokens( $settings[ $prefix . '_eyebrow' ], $settings ) ); ?></p>
						<h3 data-pep-preview-bind="<?php echo esc_attr( $prefix ); ?>_heading"><?php echo esc_html( $this->replace_tokens( $settings[ $prefix . '_heading' ], $settings ) ); ?></h3>
						<p class="pep-recovery-preview__body" data-pep-preview-bind="<?php echo esc_attr( $prefix ); ?>_body"><?php echo esc_html( $this->replace_tokens( $settings[ $prefix . '_body' ], $settings ) ); ?></p>
						<?php if ( $is_exit ) : ?>
							<input type="text" disabled data-pep-preview-placeholder="exit_placeholder" placeholder="<?php echo esc_attr( $settings['exit_placeholder'] ); ?>">
							<button type="button" class="pep-recovery-preview__button" data-pep-preview-bind="exit_button"><?php echo esc_html( $this->replace_tokens( $settings['exit_button'], $settings ) ); ?></button>
						<?php else : ?>
							<div class="pep-recovery-preview__code" data-pep-preview-code <?php if ( ! $settings['promo_code'] ) : ?>hidden<?php endif; ?>><span data-pep-preview-bind="promo_code_label"><?php echo esc_html( $settings['promo_code_label'] ); ?></span><strong data-pep-preview-bind="promo_code"><?php echo esc_html( $settings['promo_code'] ); ?></strong></div>
							<button type="button" class="pep-recovery-preview__button" data-pep-preview-bind="promo_button" <?php if ( ! $settings['promo_button'] ) : ?>hidden style="display:none"<?php endif; ?>><?php echo esc_html( $settings['promo_button'] ); ?></button>
						<?php endif; ?>
						<p class="pep-recovery-preview__fineprint" data-pep-preview-bind="<?php echo esc_attr( $prefix ); ?>_fineprint"><?php echo esc_html( $this->replace_tokens( $settings[ $prefix . '_fineprint' ], $settings ) ); ?></p>
					</div>
				</div>
			</div>
		</aside>
		<?php
	}

	public function settings_page() {
		$settings = $this->settings();
		?>
		<div class="wrap pep-recovery-admin">
			<div class="pep-recovery-heading"><div><h1><?php esc_html_e( 'Pep Select Popups', 'pepselect-cart-recovery' ); ?></h1><p><?php esc_html_e( 'Choose a popup below. The preview shows exactly which words, colors, image, and button each setting controls.', 'pepselect-cart-recovery' ); ?></p></div><span class="pep-recovery-version"><?php echo esc_html( 'v' . self::VERSION ); ?></span></div>
			<?php settings_errors( self::OPTION ); ?>
			<nav class="pep-recovery-tabs" role="tablist" aria-label="<?php esc_attr_e( 'Popup type', 'pepselect-cart-recovery' ); ?>">
				<button type="button" role="tab" id="pep-tab-exit" aria-controls="pep-panel-exit" aria-selected="true" data-pep-tab="exit"><span><?php esc_html_e( 'Exit Popup', 'pepselect-cart-recovery' ); ?></span><small><?php esc_html_e( 'Email signup + automatic discount', 'pepselect-cart-recovery' ); ?></small><strong data-pep-status="enabled"><?php echo $settings['enabled'] ? esc_html__( 'On', 'pepselect-cart-recovery' ) : esc_html__( 'Off', 'pepselect-cart-recovery' ); ?></strong></button>
				<button type="button" role="tab" id="pep-tab-promo" aria-controls="pep-panel-promo" aria-selected="false" data-pep-tab="promo"><span><?php esc_html_e( 'Campaign Popup', 'pepselect-cart-recovery' ); ?></span><small><?php esc_html_e( 'Scheduled sale or announcement', 'pepselect-cart-recovery' ); ?></small><strong data-pep-status="promo_enabled"><?php echo $settings['promo_enabled'] ? esc_html__( 'On', 'pepselect-cart-recovery' ) : esc_html__( 'Off', 'pepselect-cart-recovery' ); ?></strong></button>
			</nav>
			<form method="post" action="options.php"><?php settings_fields( 'pepselect_cart_recovery' ); ?>
				<div class="pep-recovery-panel is-active" id="pep-panel-exit" role="tabpanel" aria-labelledby="pep-tab-exit" data-pep-panel="exit">
					<div class="pep-recovery-layout"><div class="pep-recovery-editor">
						<section class="pep-recovery-card pep-recovery-intro"><div><span class="pep-recovery-kicker"><?php esc_html_e( 'Exit Popup', 'pepselect-cart-recovery' ); ?></span><h2><?php esc_html_e( 'Turn a visitor who is leaving into an email subscriber.', 'pepselect-cart-recovery' ); ?></h2><p><?php esc_html_e( 'The visitor enters only an email address. WooCommerce creates a private discount code, emails it, and restricts it to that address. No account is created.', 'pepselect-cart-recovery' ); ?></p></div><label class="pep-recovery-switch"><input name="<?php echo esc_attr( self::OPTION ); ?>[enabled]" data-pep-setting="enabled" type="checkbox" value="1" <?php checked( $settings['enabled'], 1 ); ?>><span></span><b><?php esc_html_e( 'Exit popup enabled', 'pepselect-cart-recovery' ); ?></b></label></section>
						<section class="pep-recovery-card"><h2><?php esc_html_e( 'When does this appear?', 'pepselect-cart-recovery' ); ?></h2><div class="pep-recovery-explainer"><div><b><?php esc_html_e( 'Desktop', 'pepselect-cart-recovery' ); ?></b><p><?php esc_html_e( 'After 15 seconds, when the visitor moves toward the top of the browser to leave.', 'pepselect-cart-recovery' ); ?></p></div><div><b><?php esc_html_e( 'Mobile', 'pepselect-cart-recovery' ); ?></b><p><?php esc_html_e( 'After 45 seconds and after the visitor has scrolled beyond 55% of the page.', 'pepselect-cart-recovery' ); ?></p></div><div><b><?php esc_html_e( 'It stays out of the way', 'pepselect-cart-recovery' ); ?></b><p><?php esc_html_e( 'It never appears at checkout or inside My Account, and waits after a visitor dismisses it.', 'pepselect-cart-recovery' ); ?></p></div></div></section>
						<section class="pep-recovery-card"><h2><?php esc_html_e( 'Exit popup timing', 'pepselect-cart-recovery' ); ?></h2><div class="pep-recovery-grid">
							<?php $this->admin_field( $settings, 'dismiss_days', __( 'Wait after dismissal', 'pepselect-cart-recovery' ), __( 'Number of days before a visitor who closed the popup can see it again.', 'pepselect-cart-recovery' ), 'number', array( 'min' => '1', 'max' => '365' ) ); ?>
						</div></section>
						<details class="pep-recovery-card" open><summary><?php esc_html_e( 'Generated coupon options', 'pepselect-cart-recovery' ); ?></summary><p class="description"><?php esc_html_e( 'These rules apply to every private coupon generated by the Exit Popup and the final saved-cart email.', 'pepselect-cart-recovery' ); ?></p>
							<div class="pep-recovery-grid">
								<div class="pep-recovery-field"><label for="pep-discount-type"><?php esc_html_e( 'Discount type', 'pepselect-cart-recovery' ); ?></label><select id="pep-discount-type" name="<?php echo esc_attr( self::OPTION ); ?>[discount_type]" data-pep-setting="discount_type"><option value="percent" <?php selected( $settings['discount_type'], 'percent' ); ?>><?php esc_html_e( 'Percentage discount', 'pepselect-cart-recovery' ); ?></option><option value="fixed_cart" <?php selected( $settings['discount_type'], 'fixed_cart' ); ?>><?php esc_html_e( 'Fixed cart discount', 'pepselect-cart-recovery' ); ?></option><option value="fixed_product" <?php selected( $settings['discount_type'], 'fixed_product' ); ?>><?php esc_html_e( 'Fixed product discount', 'pepselect-cart-recovery' ); ?></option></select><p class="description"><?php esc_html_e( 'Matches WooCommerce coupon discount types.', 'pepselect-cart-recovery' ); ?></p></div>
								<?php $this->admin_field( $settings, 'discount_amount', __( 'Coupon amount', 'pepselect-cart-recovery' ), __( 'Percentage or currency amount used for each new code.', 'pepselect-cart-recovery' ), 'number', array( 'min' => '0.01', 'max' => '10000', 'step' => '0.01' ) ); ?>
								<?php $this->admin_field( $settings, 'coupon_prefix', __( 'Code prefix', 'pepselect-cart-recovery' ), __( 'A private random ending is added automatically.', 'pepselect-cart-recovery' ), 'text', array( 'maxlength' => '16' ) ); ?>
								<?php $this->admin_field( $settings, 'coupon_expiry_days', __( 'Expires after', 'pepselect-cart-recovery' ), __( 'Days after each code is generated.', 'pepselect-cart-recovery' ), 'number', array( 'min' => '1', 'max' => '365' ) ); ?>
								<?php $this->admin_field( $settings, 'usage_limit', __( 'Usage limit per coupon', 'pepselect-cart-recovery' ), __( 'Total redemptions allowed for each generated code. Use 0 for unlimited.', 'pepselect-cart-recovery' ), 'number', array( 'min' => '0' ) ); ?>
								<?php $this->admin_field( $settings, 'usage_limit_per_user', __( 'Uses per email', 'pepselect-cart-recovery' ), __( 'Redemptions allowed for the email tied to the code. Use 0 for unlimited.', 'pepselect-cart-recovery' ), 'number', array( 'min' => '0' ) ); ?>
								<?php $this->admin_field( $settings, 'limit_usage_to_x_items', __( 'Limit usage to X items', 'pepselect-cart-recovery' ), __( 'For product discounts, limit how many qualifying items receive the discount. Use 0 for all qualifying items.', 'pepselect-cart-recovery' ), 'number', array( 'min' => '0' ) ); ?>
								<?php $this->admin_field( $settings, 'minimum_amount', __( 'Minimum spend', 'pepselect-cart-recovery' ), __( 'Cart subtotal required before the coupon can apply. Leave blank for none.', 'pepselect-cart-recovery' ), 'number', array( 'min' => '0', 'step' => '0.01' ) ); ?>
								<?php $this->admin_field( $settings, 'maximum_amount', __( 'Maximum spend', 'pepselect-cart-recovery' ), __( 'Largest cart subtotal allowed for the coupon. Leave blank for none.', 'pepselect-cart-recovery' ), 'number', array( 'min' => '0', 'step' => '0.01' ) ); ?>
							</div>
							<div class="pep-recovery-coupon-toggles">
								<?php $this->admin_checkbox( $settings, 'allow_coupon_stacking', __( 'Allow combining with other coupons', 'pepselect-cart-recovery' ), __( 'When off, generated codes are individual-use coupons. This setting also aligns existing Pep Select recovery codes.', 'pepselect-cart-recovery' ) ); ?>
								<?php $this->admin_checkbox( $settings, 'free_shipping', __( 'Allow free shipping', 'pepselect-cart-recovery' ), __( 'The store must have a free-shipping method configured to require a valid coupon.', 'pepselect-cart-recovery' ) ); ?>
								<?php $this->admin_checkbox( $settings, 'exclude_sale_items', __( 'Exclude sale items', 'pepselect-cart-recovery' ), __( 'Sale-priced products will not receive the generated coupon discount.', 'pepselect-cart-recovery' ) ); ?>
							</div>
							<h3><?php esc_html_e( 'Product restrictions', 'pepselect-cart-recovery' ); ?></h3><p class="description"><?php esc_html_e( 'Leave these selectors empty to allow every product. Included and excluded restrictions match the fields on a standard WooCommerce coupon.', 'pepselect-cart-recovery' ); ?></p><div class="pep-recovery-grid">
								<?php $this->admin_product_selector( $settings, 'product_ids', __( 'Products', 'pepselect-cart-recovery' ), __( 'Products the coupon applies to or that must be in the cart for a fixed cart discount.', 'pepselect-cart-recovery' ) ); ?>
								<?php $this->admin_product_selector( $settings, 'excluded_product_ids', __( 'Exclude products', 'pepselect-cart-recovery' ), __( 'Products that cannot receive or qualify for this coupon.', 'pepselect-cart-recovery' ) ); ?>
								<?php $this->admin_term_selector( $settings, 'product_category_ids', 'product_cat', __( 'Product categories', 'pepselect-cart-recovery' ), __( 'Only products in these categories qualify.', 'pepselect-cart-recovery' ) ); ?>
								<?php $this->admin_term_selector( $settings, 'excluded_product_category_ids', 'product_cat', __( 'Exclude categories', 'pepselect-cart-recovery' ), __( 'Products in these categories do not qualify.', 'pepselect-cart-recovery' ) ); ?>
								<?php $this->admin_term_selector( $settings, 'product_brand_ids', 'product_brand', __( 'Product brands', 'pepselect-cart-recovery' ), __( 'Only products assigned to these WooCommerce brands qualify.', 'pepselect-cart-recovery' ) ); ?>
								<?php $this->admin_term_selector( $settings, 'excluded_product_brand_ids', 'product_brand', __( 'Exclude brands', 'pepselect-cart-recovery' ), __( 'Products assigned to these brands do not qualify.', 'pepselect-cart-recovery' ) ); ?>
							</div>
						</details>
						<section class="pep-recovery-card"><h2><?php esc_html_e( 'Words inside the popup', 'pepselect-cart-recovery' ); ?></h2><p class="pep-recovery-token-note"><strong><?php esc_html_e( 'Automatic words:', 'pepselect-cart-recovery' ); ?></strong> <code>{discount}</code> <?php esc_html_e( 'shows the current offer amount.', 'pepselect-cart-recovery' ); ?></p><div class="pep-recovery-grid">
							<?php $this->admin_field( $settings, 'exit_eyebrow', __( 'Small label', 'pepselect-cart-recovery' ), __( 'The short all-caps line above the headline.', 'pepselect-cart-recovery' ) ); ?>
							<?php $this->admin_field( $settings, 'exit_heading', __( 'Main headline', 'pepselect-cart-recovery' ), __( 'The largest text in the popup.', 'pepselect-cart-recovery' ) ); ?>
							<?php $this->admin_field( $settings, 'exit_body', __( 'Supporting paragraph', 'pepselect-cart-recovery' ), __( 'Explains what the visitor receives for entering an email.', 'pepselect-cart-recovery' ), 'textarea' ); ?>
							<?php $this->admin_field( $settings, 'exit_placeholder', __( 'Email box placeholder', 'pepselect-cart-recovery' ), __( 'The light text shown inside the empty email field.', 'pepselect-cart-recovery' ) ); ?>
							<?php $this->admin_field( $settings, 'exit_button', __( 'Button text', 'pepselect-cart-recovery' ), __( 'The action visitors press to request the code.', 'pepselect-cart-recovery' ) ); ?>
							<?php $this->admin_field( $settings, 'exit_fineprint', __( 'Small print below the button', 'pepselect-cart-recovery' ), __( 'Use this for the email and unsubscribe notice.', 'pepselect-cart-recovery' ) ); ?>
							<?php $this->admin_field( $settings, 'exit_loading', __( 'Button text while sending', 'pepselect-cart-recovery' ), __( 'Briefly replaces the button text after it is pressed.', 'pepselect-cart-recovery' ) ); ?>
							<?php $this->admin_field( $settings, 'exit_success', __( 'Success headline', 'pepselect-cart-recovery' ), __( 'Appears after the email has been accepted.', 'pepselect-cart-recovery' ) ); ?>
							<?php $this->admin_field( $settings, 'exit_success_note', __( 'Success supporting text', 'pepselect-cart-recovery' ), __( 'Tells the visitor what to do with the code.', 'pepselect-cart-recovery' ) ); ?>
						</div></section>
						<details class="pep-recovery-card" open><summary><?php esc_html_e( 'Popup colors and background', 'pepselect-cart-recovery' ); ?></summary><p class="description"><?php esc_html_e( 'Every change below is visible in the preview before you save it.', 'pepselect-cart-recovery' ); ?></p><div class="pep-recovery-grid"><?php $this->admin_colors( $settings, 'exit' ); ?></div></details>
						<details class="pep-recovery-card"><summary><?php esc_html_e( 'Discount email wording', 'pepselect-cart-recovery' ); ?></summary><p class="description"><?php esc_html_e( 'This is the email sent immediately with the private coupon. The established Pep Select email layout does not change.', 'pepselect-cart-recovery' ); ?></p><p class="pep-recovery-token-note"><code>{discount}</code> <code>{days}</code> <code>{support_email}</code> <?php esc_html_e( 'are filled automatically.', 'pepselect-cart-recovery' ); ?></p><div class="pep-recovery-grid"><?php foreach ( array( 'email_subject' => 'Email subject', 'email_preheader' => 'Inbox preview text', 'email_label' => 'Header label', 'email_eyebrow' => 'Small label', 'email_heading' => 'Email headline', 'email_greeting' => 'Greeting', 'email_intro' => 'Opening paragraph', 'email_code_label' => 'Code label', 'email_code_note' => 'Instructions below code', 'email_extra' => 'Additional paragraph', 'email_button' => 'Email button text', 'email_support' => 'Support line' ) as $key => $label ) { $this->admin_field( $settings, $key, __( $label, 'pepselect-cart-recovery' ) ); } ?></div></details>
						<details class="pep-recovery-card"><summary><?php esc_html_e( 'Connections for email and cart recovery', 'pepselect-cart-recovery' ); ?></summary><p class="description"><?php esc_html_e( 'These IDs connect the popup to existing Pep Select systems. Leave them alone unless the matching list or email template changes.', 'pepselect-cart-recovery' ); ?></p><div class="pep-recovery-grid"><?php $this->admin_field( $settings, 'fluentcrm_list_id', __( 'FluentCRM list ID', 'pepselect-cart-recovery' ), __( 'Adds submitted emails to this list. Use 0 to skip list syncing.', 'pepselect-cart-recovery' ), 'number', array( 'min' => '0' ) ); ?><?php $this->admin_field( $settings, 'final_template_id', __( '48-hour recovery email ID', 'pepselect-cart-recovery' ), __( 'Reminds the customer to use their existing private code or creates one when the tracked cart has no code.', 'pepselect-cart-recovery' ), 'number', array( 'min' => '0' ) ); ?></div></details>
					</div><?php $this->admin_preview( $settings, 'exit' ); ?></div>
				</div>

				<div class="pep-recovery-panel" id="pep-panel-promo" role="tabpanel" aria-labelledby="pep-tab-promo" data-pep-panel="promo" hidden>
					<div class="pep-recovery-layout"><div class="pep-recovery-editor">
						<section class="pep-recovery-card pep-recovery-intro"><div><span class="pep-recovery-kicker"><?php esc_html_e( 'Campaign Popup', 'pepselect-cart-recovery' ); ?></span><h2><?php esc_html_e( 'Run a scheduled sale or announcement.', 'pepselect-cart-recovery' ); ?></h2><p><?php esc_html_e( 'This popup appears after a visitor arrives, only between the start and end times you choose. It stops automatically when the campaign ends.', 'pepselect-cart-recovery' ); ?></p></div><label class="pep-recovery-switch"><input name="<?php echo esc_attr( self::OPTION ); ?>[promo_enabled]" data-pep-setting="promo_enabled" type="checkbox" value="1" <?php checked( $settings['promo_enabled'], 1 ); ?>><span></span><b><?php esc_html_e( 'Campaign popup enabled', 'pepselect-cart-recovery' ); ?></b></label></section>
						<section class="pep-recovery-card"><h2><?php esc_html_e( 'Schedule and frequency', 'pepselect-cart-recovery' ); ?></h2><p class="description"><?php printf( esc_html__( 'Times use the website timezone: %s.', 'pepselect-cart-recovery' ), esc_html( wp_timezone_string() ) ); ?></p><div class="pep-recovery-grid">
							<?php $this->admin_field( $settings, 'promo_start', __( 'Start showing', 'pepselect-cart-recovery' ), __( 'The first date and time visitors can see this campaign.', 'pepselect-cart-recovery' ), 'datetime-local' ); ?>
							<?php $this->admin_field( $settings, 'promo_end', __( 'Stop showing', 'pepselect-cart-recovery' ), __( 'The popup turns itself off at this date and time.', 'pepselect-cart-recovery' ), 'datetime-local' ); ?>
							<?php $this->admin_field( $settings, 'promo_delay_seconds', __( 'Wait after page opens', 'pepselect-cart-recovery' ), __( 'Seconds between page arrival and the popup opening. Use 0 for immediate.', 'pepselect-cart-recovery' ), 'number', array( 'min' => '0', 'max' => '86400' ) ); ?>
							<?php $this->admin_field( $settings, 'promo_dismiss_days', __( 'Wait after dismissal', 'pepselect-cart-recovery' ), __( 'Days before someone who closed this campaign can see it again.', 'pepselect-cart-recovery' ), 'number', array( 'min' => '1', 'max' => '365' ) ); ?>
						</div><label class="pep-recovery-check"><input name="<?php echo esc_attr( self::OPTION ); ?>[promo_suppress_exit]" data-pep-setting="promo_suppress_exit" type="checkbox" value="1" <?php checked( $settings['promo_suppress_exit'], 1 ); ?>><span><b><?php esc_html_e( 'Pause the Exit Popup while this campaign is active', 'pepselect-cart-recovery' ); ?></b><small><?php esc_html_e( 'Recommended. Visitors see one clear message instead of two competing popups.', 'pepselect-cart-recovery' ); ?></small></span></label></section>
						<section class="pep-recovery-card"><h2><?php esc_html_e( 'Words and action', 'pepselect-cart-recovery' ); ?></h2><div class="pep-recovery-grid">
							<?php $this->admin_field( $settings, 'promo_eyebrow', __( 'Small label', 'pepselect-cart-recovery' ), __( 'The short all-caps line above the headline.', 'pepselect-cart-recovery' ) ); ?>
							<?php $this->admin_field( $settings, 'promo_heading', __( 'Main headline', 'pepselect-cart-recovery' ), __( 'The largest text in the popup.', 'pepselect-cart-recovery' ) ); ?>
							<?php $this->admin_field( $settings, 'promo_body', __( 'Supporting paragraph', 'pepselect-cart-recovery' ), __( 'Explains the sale, announcement, or reason to click.', 'pepselect-cart-recovery' ), 'textarea' ); ?>
							<?php $this->admin_field( $settings, 'promo_code_label', __( 'Code label', 'pepselect-cart-recovery' ), __( 'The small words above the optional promotion code.', 'pepselect-cart-recovery' ) ); ?>
							<?php $this->admin_field( $settings, 'promo_code', __( 'Promotion code to display', 'pepselect-cart-recovery' ), __( 'Optional. This displays a code but does not create it. Create the coupon in WooCommerce first.', 'pepselect-cart-recovery' ) ); ?>
							<?php $this->admin_field( $settings, 'promo_button', __( 'Button text (optional)', 'pepselect-cart-recovery' ), __( 'Leave this blank to remove the button. Visitors can close the popup and continue browsing the website.', 'pepselect-cart-recovery' ) ); ?>
							<?php $this->admin_field( $settings, 'promo_url', __( 'Button destination', 'pepselect-cart-recovery' ), __( 'Use a website path such as /shop/ or a full URL.', 'pepselect-cart-recovery' ), 'text' ); ?>
							<?php $this->admin_field( $settings, 'promo_fineprint', __( 'Small print below the button', 'pepselect-cart-recovery' ), __( 'Optional short terms or timing note.', 'pepselect-cart-recovery' ) ); ?>
						</div></section>
						<details class="pep-recovery-card" open><summary><?php esc_html_e( 'Popup colors and background', 'pepselect-cart-recovery' ); ?></summary><p class="description"><?php esc_html_e( 'Every change below is visible in the preview before you save it.', 'pepselect-cart-recovery' ); ?></p><div class="pep-recovery-grid"><?php $this->admin_colors( $settings, 'promo' ); ?></div></details>
					</div><?php $this->admin_preview( $settings, 'promo' ); ?></div>
				</div>
				<div class="pep-recovery-save"><div><strong><?php esc_html_e( 'Preview first. Save when it looks right.', 'pepselect-cart-recovery' ); ?></strong><span><?php esc_html_e( 'Nothing changes on the website until you save.', 'pepselect-cart-recovery' ); ?></span></div><?php submit_button( __( 'Save popup settings', 'pepselect-cart-recovery' ), 'primary', 'submit', false ); ?></div>
			</form>
			<div class="pep-recovery-ops-note"><span aria-hidden="true">↔</span><div><strong><?php esc_html_e( 'Ready for Ops control', 'pepselect-cart-recovery' ); ?></strong><p><?php esc_html_e( 'Both popup tabs use one protected WordPress settings API. Control Ops can read and update these same options later without creating a second popup system.', 'pepselect-cart-recovery' ); ?></p></div></div>
		</div>
		<?php
	}
}

register_activation_hook( __FILE__, array( 'PepSelect_Cart_Recovery', 'activate' ) );
PepSelect_Cart_Recovery::instance();

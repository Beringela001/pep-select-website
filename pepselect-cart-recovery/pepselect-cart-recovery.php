<?php
/**
 * Plugin Name: Pep Select Cart Recovery
 * Description: Lightweight exit offer, unique coupons, and Cart Abandonment Recovery integration for Pep Select.
 * Version: 0.3.0
 * Author: Pep Select
 * Text Domain: pepselect-cart-recovery
 */

defined( 'ABSPATH' ) || exit;

final class PepSelect_Cart_Recovery {
	const VERSION                     = '0.3.0';
	const OPTION                      = 'pepselect_cart_recovery_settings';
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
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'wp_footer', array( $this, 'render_dialog' ), 40 );
		add_action( 'wp_ajax_pepselect_capture_exit_offer', array( $this, 'capture_offer' ) );
		add_action( 'wp_ajax_nopriv_pepselect_capture_exit_offer', array( $this, 'capture_offer' ) );
		add_filter( 'woo_ca_recovery_email_data', array( $this, 'attach_coupon_to_recovery_email' ), 20, 2 );
		add_filter( 'wcar_add_token_data', array( $this, 'attach_coupon_to_recovery_link' ), 20, 2 );
		add_filter( 'wcf_ca_should_send_email', array( $this, 'require_signup_code_for_final_email' ), 20, 2 );
		add_filter( 'cartflows_ca_email_headers', array( $this, 'recovery_headers' ), 20 );
		add_filter( 'fluent_crm/global_email_limit_per_second', array( $this, 'limit_marketing_delivery_rate' ), 100, 2 );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'register_settings_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
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
			'coupon_prefix'             => 'PEP',
			'coupon_expiry_days'        => 7,
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
			'email_extra'               => 'The code can combine with eligible offers. Product details and available batch documentation are ready whenever you want to take another look.',
			'email_button'              => 'Explore compounds',
			'email_support'             => 'Have a question before ordering? Reply to this email or contact {support_email}.',
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

	private function settings() {
		return wp_parse_args( (array) get_option( self::OPTION, array() ), self::defaults() );
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
		if ( 'fixed_cart' === $settings['discount_type'] ) {
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
			$coupon->set_description( sprintf( 'Pep Select %s email signup offer. Generated automatically.', $this->discount_label( $settings ) ) );
			$coupon->set_discount_type( $settings['discount_type'] );
			$coupon->set_amount( (float) $settings['discount_amount'] );
			$coupon->set_individual_use( false );
			$coupon->set_usage_limit( 1 );
			$coupon->set_usage_limit_per_user( 1 );
			$coupon->set_email_restrictions( array( $email ) );
			$coupon->set_free_shipping( false );
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
		return substr( hash( 'sha256', $settings['discount_type'] . '|' . $settings['discount_amount'] . '|' . $settings['coupon_prefix'] ), 0, 20 );
	}

	private function bonus_coupon_key( $email ) {
		return 'pep_exit_bonus_coupon_' . hash_hmac( 'sha256', strtolower( $email ), wp_salt( 'auth' ) );
	}

	private function coupon_for_email( $email ) {
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
		if ( $signature !== (string) $coupon->get_meta( '_pepselect_exit_offer_signature', true ) ) {
			return '';
		}
		return $coupon->get_date_expires() && $coupon->get_date_expires()->getTimestamp() < time() ? '' : $code;
	}

	private function bonus_coupon_for_email( $email ) {
		$code = get_transient( $this->bonus_coupon_key( $email ) );
		if ( ! $code ) {
			$coupon_ids = get_posts(
				array(
					'post_type'      => 'shop_coupon',
					'post_status'    => 'publish',
					'posts_per_page' => 1,
					'fields'         => 'ids',
					'orderby'        => 'date',
					'order'          => 'DESC',
					'meta_key'       => '_pepselect_exit_bonus_email_hash',
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
		return $coupon->get_date_expires() && $coupon->get_date_expires()->getTimestamp() < time() ? '' : $code;
	}

	private function create_bonus_coupon( $email, $parent_code ) {
		$settings = $this->settings();
		$code     = '';
		for ( $attempt = 0; $attempt < 4; $attempt++ ) {
			$candidate = 'PEP5-' . strtoupper( wp_generate_password( 8, false, false ) );
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
			$coupon->set_description( 'Pep Select 5% 48-hour cart recovery offer. Generated automatically.' );
			$coupon->set_discount_type( 'percent' );
			$coupon->set_amount( 5 );
			$coupon->set_individual_use( false );
			$coupon->set_usage_limit( 1 );
			$coupon->set_usage_limit_per_user( 1 );
			$coupon->set_email_restrictions( array( $email ) );
			$coupon->set_free_shipping( false );
			$coupon->set_date_expires( time() + max( 1, absint( $settings['coupon_expiry_days'] ) ) * DAY_IN_SECONDS );
			$coupon->add_meta_data( '_pepselect_exit_bonus_offer', 1, true );
			$coupon->add_meta_data( '_pepselect_exit_bonus_email_hash', hash_hmac( 'sha256', strtolower( $email ), wp_salt( 'auth' ) ), true );
			$coupon->add_meta_data( '_pepselect_exit_parent_code', sanitize_text_field( $parent_code ), true );
			$coupon->save();
			set_transient( $this->bonus_coupon_key( $email ), $code, max( 1, absint( $settings['coupon_expiry_days'] ) ) * DAY_IN_SECONDS );
			return $code;
		} catch ( Exception $exception ) {
			return '';
		}
	}

	private function ensure_bonus_coupon( $email, $parent_code ) {
		$code = $this->bonus_coupon_for_email( $email );
		return $code ? $code : $this->create_bonus_coupon( $email, $parent_code );
	}

	private function is_final_template( $email_data ) {
		$settings = $this->settings();
		return ! empty( $settings['final_template_id'] )
			&& is_object( $email_data )
			&& absint( $email_data->email_template_id ?? 0 ) === absint( $settings['final_template_id'] );
	}

	public function attach_coupon_to_recovery_email( $email_data, $preview_email ) {
		if ( $preview_email && $this->is_final_template( $email_data ) && ! empty( $email_data->email_body ) ) {
			$email_data->email_body = str_replace( '{{pepselect.bonus_coupon_code}}', 'PEP5-PREVIEW', $email_data->email_body );
		}

		if ( ! $preview_email && is_object( $email_data ) && ! empty( $email_data->email ) ) {
			$code = $this->coupon_for_email( sanitize_email( $email_data->email ) );
			if ( $code ) {
				$email_data->coupon_code = $code;
				if ( $this->is_final_template( $email_data ) && ! empty( $email_data->email_body ) ) {
					$bonus_code             = $this->ensure_bonus_coupon( sanitize_email( $email_data->email ), $code );
					$email_data->email_body = str_replace( '{{pepselect.bonus_coupon_code}}', $bonus_code, $email_data->email_body );
				}
			}
		}
		return $email_data;
	}

	public function require_signup_code_for_final_email( $should_send, $email_data ) {
		if ( ! $this->is_final_template( $email_data ) ) {
			return $should_send;
		}

		if ( empty( $email_data->email ) ) {
			return false;
		}

		$email = sanitize_email( $email_data->email );
		$code  = $this->coupon_for_email( $email );
		return $code && (bool) $this->ensure_bonus_coupon( $email, $code );
	}

	public function attach_coupon_to_recovery_link( $token_data, $email_data ) {
		if ( is_object( $email_data ) && ! empty( $email_data->email ) ) {
			$code = $this->coupon_for_email( sanitize_email( $email_data->email ) );
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
			'support'    => $this->replace_tokens( $settings['email_support'], $settings ),
		);

		ob_start();
		include __DIR__ . '/templates/coupon-email.php';
		return (string) ob_get_clean();
	}

	public function register_settings() {
		register_setting( 'pepselect_cart_recovery', self::OPTION, array( $this, 'sanitize_settings' ) );
	}

	public function sanitize_settings( $input ) {
		$defaults = self::defaults();
		$input    = is_array( $input ) ? $input : array();
		$output   = $defaults;

		foreach ( array( 'enabled', 'promo_enabled', 'promo_suppress_exit' ) as $key ) {
			$output[ $key ] = empty( $input[ $key ] ) ? 0 : 1;
		}

		$output['discount_type'] = in_array( $input['discount_type'] ?? '', array( 'percent', 'fixed_cart' ), true ) ? $input['discount_type'] : 'percent';
		$raw_amount              = $input['discount_amount'] ?? $defaults['discount_amount'];
		$amount                  = (float) ( function_exists( 'wc_format_decimal' ) ? wc_format_decimal( $raw_amount ) : $raw_amount );
		$output['discount_amount'] = (string) min( 'percent' === $output['discount_type'] ? 100 : 10000, max( 0.01, $amount ) );

		$prefix = strtoupper( preg_replace( '/[^A-Z0-9-]/', '', (string) ( $input['coupon_prefix'] ?? 'PEP' ) ) );
		$output['coupon_prefix']      = substr( $prefix ? $prefix : 'PEP', 0, 16 );
		$output['coupon_expiry_days'] = min( 365, max( 1, absint( $input['coupon_expiry_days'] ?? 7 ) ) );
		$output['dismiss_days']       = min( 365, max( 1, absint( $input['dismiss_days'] ?? 30 ) ) );
		$output['fluentcrm_list_id']  = absint( $input['fluentcrm_list_id'] ?? 0 );
		$output['final_template_id']  = absint( $input['final_template_id'] ?? 0 );
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
			__( 'Pep Select Cart Recovery', 'pepselect-cart-recovery' ),
			__( 'Campaign Popups', 'pepselect-cart-recovery' ),
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
		wp_enqueue_style( 'pepselect-cart-recovery-admin', plugin_dir_url( __FILE__ ) . 'assets/admin.css', array(), self::VERSION );
		wp_enqueue_script( 'pepselect-cart-recovery-admin', plugin_dir_url( __FILE__ ) . 'assets/admin.js', array(), self::VERSION, true );
	}

	private function admin_field( $settings, $key, $label, $description = '', $type = 'text', $attributes = array() ) {
		$name  = self::OPTION . '[' . $key . ']';
		$value = $settings[ $key ] ?? '';
		?>
		<div class="pep-recovery-field">
			<label for="pep-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label>
			<?php if ( 'textarea' === $type ) : ?>
				<textarea id="pep-<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $name ); ?>" rows="3"><?php echo esc_textarea( $value ); ?></textarea>
			<?php elseif ( 'image' === $type ) : ?>
				<div class="pep-recovery-image-field"><input id="pep-<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $name ); ?>" type="url" value="<?php echo esc_attr( $value ); ?>"><button class="button" type="button" data-pep-media-target="pep-<?php echo esc_attr( $key ); ?>"><?php esc_html_e( 'Choose image', 'pepselect-cart-recovery' ); ?></button></div>
			<?php else : ?>
				<input id="pep-<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $name ); ?>" type="<?php echo esc_attr( $type ); ?>" value="<?php echo esc_attr( $value ); ?>" <?php foreach ( $attributes as $attribute => $attribute_value ) { echo esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '" '; } ?>>
			<?php endif; ?>
			<?php if ( $description ) : ?><p class="description"><?php echo wp_kses_post( $description ); ?></p><?php endif; ?>
		</div>
		<?php
	}

	private function admin_colors( $settings, $prefix ) {
		$labels = array(
			'overlay_color'     => __( 'Page overlay color', 'pepselect-cart-recovery' ),
			'overlay_opacity'   => __( 'Page overlay opacity', 'pepselect-cart-recovery' ),
			'card_color'        => __( 'Popup background color', 'pepselect-cart-recovery' ),
			'card_image'        => __( 'Popup background image', 'pepselect-cart-recovery' ),
			'card_tint_color'   => __( 'Image tint color', 'pepselect-cart-recovery' ),
			'card_tint_opacity' => __( 'Image tint opacity', 'pepselect-cart-recovery' ),
			'text_color'        => __( 'Heading color', 'pepselect-cart-recovery' ),
			'muted_color'       => __( 'Body text color', 'pepselect-cart-recovery' ),
			'accent_color'      => __( 'Eyebrow/accent color', 'pepselect-cart-recovery' ),
			'button_color'      => __( 'Button color', 'pepselect-cart-recovery' ),
			'button_text_color' => __( 'Button text color', 'pepselect-cart-recovery' ),
		);
		foreach ( $labels as $suffix => $label ) {
			$key = $prefix . '_' . $suffix;
			if ( 'card_image' === $suffix ) {
				$this->admin_field( $settings, $key, $label, __( 'Optional. The fixed popup frame remains unchanged.', 'pepselect-cart-recovery' ), 'image' );
			} elseif ( false !== strpos( $suffix, 'opacity' ) ) {
				$this->admin_field( $settings, $key, $label, __( 'Use a value from 0 to 1.', 'pepselect-cart-recovery' ), 'number', array( 'min' => '0', 'max' => '1', 'step' => '0.05' ) );
			} else {
				$this->admin_field( $settings, $key, $label, '', 'color' );
			}
		}
	}

	public function settings_page() {
		$settings = $this->settings();
		?>
		<div class="wrap pep-recovery-admin"><h1><?php esc_html_e( 'Pep Select Campaign Popups', 'pepselect-cart-recovery' ); ?></h1>
			<p><?php esc_html_e( 'Manage the evergreen email offer and one date-scheduled promotion. The popup frame and responsive layout remain fixed.', 'pepselect-cart-recovery' ); ?></p>
			<p class="pep-recovery-token-note"><strong><?php esc_html_e( 'Available wording tokens:', 'pepselect-cart-recovery' ); ?></strong> <code>{discount}</code> <code>{days}</code> <code>{support_email}</code></p>
			<?php settings_errors( self::OPTION ); ?>
			<form method="post" action="options.php"><?php settings_fields( 'pepselect_cart_recovery' ); ?>
				<section class="pep-recovery-card"><h2><?php esc_html_e( 'Email capture offer', 'pepselect-cart-recovery' ); ?></h2>
					<label class="pep-recovery-toggle"><input name="<?php echo esc_attr( self::OPTION ); ?>[enabled]" type="checkbox" value="1" <?php checked( $settings['enabled'], 1 ); ?>> <?php esc_html_e( 'Enable the email capture popup', 'pepselect-cart-recovery' ); ?></label>
					<div class="pep-recovery-grid">
						<div class="pep-recovery-field"><label for="pep-discount-type"><?php esc_html_e( 'Discount type', 'pepselect-cart-recovery' ); ?></label><select id="pep-discount-type" name="<?php echo esc_attr( self::OPTION ); ?>[discount_type]"><option value="percent" <?php selected( $settings['discount_type'], 'percent' ); ?>><?php esc_html_e( 'Percentage', 'pepselect-cart-recovery' ); ?></option><option value="fixed_cart" <?php selected( $settings['discount_type'], 'fixed_cart' ); ?>><?php esc_html_e( 'Fixed cart amount', 'pepselect-cart-recovery' ); ?></option></select></div>
						<?php $this->admin_field( $settings, 'discount_amount', __( 'Discount amount', 'pepselect-cart-recovery' ), __( 'Changing this creates new coupons with the new value. Existing issued coupons remain unchanged.', 'pepselect-cart-recovery' ), 'number', array( 'min' => '0.01', 'max' => '10000', 'step' => '0.01' ) ); ?>
						<?php $this->admin_field( $settings, 'coupon_prefix', __( 'Unique coupon prefix', 'pepselect-cart-recovery' ), __( 'The plugin adds a private random suffix for each email address.', 'pepselect-cart-recovery' ) ); ?>
						<?php $this->admin_field( $settings, 'coupon_expiry_days', __( 'Coupon expiry (days)', 'pepselect-cart-recovery' ), '', 'number', array( 'min' => '1', 'max' => '365' ) ); ?>
						<?php $this->admin_field( $settings, 'dismiss_days', __( 'Dismiss cooldown (days)', 'pepselect-cart-recovery' ), '', 'number', array( 'min' => '1', 'max' => '365' ) ); ?>
						<?php $this->admin_field( $settings, 'fluentcrm_list_id', __( 'FluentCRM list ID', 'pepselect-cart-recovery' ), __( 'Use 0 to disable list sync.', 'pepselect-cart-recovery' ), 'number', array( 'min' => '0' ) ); ?>
						<?php $this->admin_field( $settings, 'final_template_id', __( '48-hour recovery template ID', 'pepselect-cart-recovery' ), __( 'The final recovery email remains managed in Cart Abandonment Recovery. If you change the primary discount, update that email wording to match.', 'pepselect-cart-recovery' ), 'number', array( 'min' => '0' ) ); ?>
					</div>
				</section>

				<section class="pep-recovery-card"><h2><?php esc_html_e( 'Email capture popup wording', 'pepselect-cart-recovery' ); ?></h2><div class="pep-recovery-grid">
					<?php $this->admin_field( $settings, 'exit_eyebrow', __( 'Eyebrow', 'pepselect-cart-recovery' ) ); ?>
					<?php $this->admin_field( $settings, 'exit_heading', __( 'Heading', 'pepselect-cart-recovery' ) ); ?>
					<?php $this->admin_field( $settings, 'exit_body', __( 'Body', 'pepselect-cart-recovery' ), '', 'textarea' ); ?>
					<?php $this->admin_field( $settings, 'exit_placeholder', __( 'Email placeholder', 'pepselect-cart-recovery' ) ); ?>
					<?php $this->admin_field( $settings, 'exit_button', __( 'Button label', 'pepselect-cart-recovery' ) ); ?>
					<?php $this->admin_field( $settings, 'exit_loading', __( 'Button loading label', 'pepselect-cart-recovery' ) ); ?>
					<?php $this->admin_field( $settings, 'exit_fineprint', __( 'Fine print', 'pepselect-cart-recovery' ) ); ?>
					<?php $this->admin_field( $settings, 'exit_success', __( 'Success message', 'pepselect-cart-recovery' ) ); ?>
					<?php $this->admin_field( $settings, 'exit_success_note', __( 'Success note', 'pepselect-cart-recovery' ) ); ?>
				</div></section>

				<section class="pep-recovery-card"><h2><?php esc_html_e( 'Email capture popup appearance', 'pepselect-cart-recovery' ); ?></h2><div class="pep-recovery-grid"><?php $this->admin_colors( $settings, 'exit' ); ?></div></section>

				<section class="pep-recovery-card"><h2><?php esc_html_e( 'Coupon email wording', 'pepselect-cart-recovery' ); ?></h2><p><?php esc_html_e( 'The established Pep Select email layout remains unchanged.', 'pepselect-cart-recovery' ); ?></p><div class="pep-recovery-grid">
					<?php foreach ( array( 'email_subject' => 'Subject', 'email_preheader' => 'Preheader', 'email_label' => 'Header label', 'email_eyebrow' => 'Eyebrow', 'email_heading' => 'Heading', 'email_greeting' => 'Greeting', 'email_intro' => 'Opening copy', 'email_code_label' => 'Code label', 'email_code_note' => 'Code note', 'email_extra' => 'Additional copy', 'email_button' => 'Button label', 'email_support' => 'Support copy' ) as $key => $label ) { $this->admin_field( $settings, $key, __( $label, 'pepselect-cart-recovery' ) ); } ?>
				</div></section>

				<section class="pep-recovery-card"><h2><?php esc_html_e( 'Scheduled promotion popup', 'pepselect-cart-recovery' ); ?></h2>
					<label class="pep-recovery-toggle"><input name="<?php echo esc_attr( self::OPTION ); ?>[promo_enabled]" type="checkbox" value="1" <?php checked( $settings['promo_enabled'], 1 ); ?>> <?php esc_html_e( 'Enable the scheduled promotion', 'pepselect-cart-recovery' ); ?></label>
					<label class="pep-recovery-toggle"><input name="<?php echo esc_attr( self::OPTION ); ?>[promo_suppress_exit]" type="checkbox" value="1" <?php checked( $settings['promo_suppress_exit'], 1 ); ?>> <?php esc_html_e( 'Do not show the email capture popup while this promotion is active', 'pepselect-cart-recovery' ); ?></label>
					<p class="description"><?php printf( esc_html__( 'Dates use the website timezone: %s. The popup stops automatically at the end time.', 'pepselect-cart-recovery' ), esc_html( wp_timezone_string() ) ); ?></p>
					<div class="pep-recovery-grid">
						<?php $this->admin_field( $settings, 'promo_start', __( 'Start date and time', 'pepselect-cart-recovery' ), '', 'datetime-local' ); ?>
						<?php $this->admin_field( $settings, 'promo_end', __( 'End date and time', 'pepselect-cart-recovery' ), '', 'datetime-local' ); ?>
						<?php $this->admin_field( $settings, 'promo_delay_seconds', __( 'Show after (seconds)', 'pepselect-cart-recovery' ), '', 'number', array( 'min' => '0', 'max' => '86400' ) ); ?>
						<?php $this->admin_field( $settings, 'promo_dismiss_days', __( 'Dismiss cooldown (days)', 'pepselect-cart-recovery' ), __( 'Changing the campaign dates or heading starts a new visitor campaign.', 'pepselect-cart-recovery' ), 'number', array( 'min' => '1', 'max' => '365' ) ); ?>
						<?php $this->admin_field( $settings, 'promo_eyebrow', __( 'Eyebrow', 'pepselect-cart-recovery' ) ); ?>
						<?php $this->admin_field( $settings, 'promo_heading', __( 'Heading', 'pepselect-cart-recovery' ) ); ?>
						<?php $this->admin_field( $settings, 'promo_body', __( 'Body', 'pepselect-cart-recovery' ), '', 'textarea' ); ?>
						<?php $this->admin_field( $settings, 'promo_code_label', __( 'Promotion code label', 'pepselect-cart-recovery' ) ); ?>
						<?php $this->admin_field( $settings, 'promo_code', __( 'Promotion code', 'pepselect-cart-recovery' ), __( 'Optional display only. Create and configure the coupon in WooCommerce before publishing it here.', 'pepselect-cart-recovery' ) ); ?>
						<?php $this->admin_field( $settings, 'promo_button', __( 'Button label', 'pepselect-cart-recovery' ) ); ?>
						<?php $this->admin_field( $settings, 'promo_url', __( 'Button destination', 'pepselect-cart-recovery' ), '', 'url' ); ?>
						<?php $this->admin_field( $settings, 'promo_fineprint', __( 'Fine print', 'pepselect-cart-recovery' ) ); ?>
					</div>
				</section>

				<section class="pep-recovery-card"><h2><?php esc_html_e( 'Scheduled promotion appearance', 'pepselect-cart-recovery' ); ?></h2><div class="pep-recovery-grid"><?php $this->admin_colors( $settings, 'promo' ); ?></div></section>
				<?php submit_button( __( 'Save campaign settings', 'pepselect-cart-recovery' ) ); ?>
			</form>
		</div>
		<?php
	}
}

register_activation_hook( __FILE__, array( 'PepSelect_Cart_Recovery', 'activate' ) );
PepSelect_Cart_Recovery::instance();

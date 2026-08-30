<?php
/**
 * Plugin Name: Pep Select Cart Recovery
 * Description: Lightweight exit offer, unique coupons, and Cart Abandonment Recovery integration for Pep Select.
 * Version: 0.2.0
 * Author: Pep Select
 * Text Domain: pepselect-cart-recovery
 */

defined( 'ABSPATH' ) || exit;

final class PepSelect_Cart_Recovery {
	const VERSION                     = '0.2.0';
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
			add_option(
				self::OPTION,
				array(
					'enabled'            => 0,
					'coupon_expiry_days' => 7,
					'dismiss_days'       => 30,
					'fluentcrm_list_id'  => 0,
					'final_template_id'  => 0,
				)
			);
		}
	}

	private function settings() {
		return wp_parse_args(
			(array) get_option( self::OPTION, array() ),
			array(
				'enabled'            => 0,
				'coupon_expiry_days' => 7,
				'dismiss_days'       => 30,
				'fluentcrm_list_id'  => 0,
				'final_template_id'  => 0,
			)
		);
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

	public function enqueue_assets() {
		$settings = $this->settings();
		if ( empty( $settings['enabled'] ) || ! $this->is_eligible_page() ) {
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
				'dismissDays'   => max( 1, absint( $settings['dismiss_days'] ) ),
				'hasCart'       => WC()->cart && ! WC()->cart->is_empty(),
				'pageId'        => get_queried_object_id(),
			)
		);
	}

	public function render_dialog() {
		$settings = $this->settings();
		if ( empty( $settings['enabled'] ) || ! $this->is_eligible_page() ) {
			return;
		}
		?>
		<div class="pep-exit-offer" data-pep-exit-offer hidden>
			<button class="pep-exit-offer__veil" type="button" data-pep-exit-close aria-label="<?php esc_attr_e( 'Close offer', 'pepselect-cart-recovery' ); ?>"></button>
			<section class="pep-exit-offer__dialog" role="dialog" aria-modal="true" aria-labelledby="pep-exit-title" aria-describedby="pep-exit-copy">
				<button class="pep-exit-offer__close" type="button" data-pep-exit-close aria-label="<?php esc_attr_e( 'Close offer', 'pepselect-cart-recovery' ); ?>">&times;</button>
				<p class="pep-exit-offer__eyebrow"><?php esc_html_e( 'Before you go', 'pepselect-cart-recovery' ); ?></p>
				<h2 id="pep-exit-title"><?php esc_html_e( 'Your email just found 20% off.', 'pepselect-cart-recovery' ); ?></h2>
				<p id="pep-exit-copy"><?php esc_html_e( 'Drop it below and we will send a private discount code straight to your inbox.', 'pepselect-cart-recovery' ); ?></p>
				<form data-pep-exit-form novalidate>
					<label class="screen-reader-text" for="pep-exit-email"><?php esc_html_e( 'Email address', 'pepselect-cart-recovery' ); ?></label>
					<div class="pep-exit-offer__fields">
						<input id="pep-exit-email" name="email" type="email" autocomplete="email" placeholder="<?php esc_attr_e( 'Email address', 'pepselect-cart-recovery' ); ?>" required>
						<button type="submit"><?php esc_html_e( 'Send my 20% code', 'pepselect-cart-recovery' ); ?></button>
					</div>
					<input class="pep-exit-offer__trap" name="company" type="text" tabindex="-1" autocomplete="off" aria-hidden="true">
					<p class="pep-exit-offer__fineprint"><?php esc_html_e( 'Your code comes with occasional product and restock emails. Unsubscribe anytime.', 'pepselect-cart-recovery' ); ?></p>
					<p class="pep-exit-offer__message" data-pep-exit-message role="status" aria-live="polite"></p>
				</form>
				<div class="pep-exit-offer__success" data-pep-exit-success hidden>
					<p><?php esc_html_e( 'Sent. Your 20% code is headed to your inbox.', 'pepselect-cart-recovery' ); ?></p>
					<p class="pep-exit-offer__fineprint"><?php esc_html_e( 'Use it at checkout with the same email address.', 'pepselect-cart-recovery' ); ?></p>
				</div>
			</section>
		</div>
		<?php
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
			$candidate = 'PEP-' . strtoupper( wp_generate_password( 8, false, false ) );
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
			$coupon->set_description( 'Pep Select 20% email signup offer. Generated automatically.' );
			$coupon->set_discount_type( 'percent' );
			$coupon->set_amount( 20 );
			$coupon->set_individual_use( false );
			$coupon->set_usage_limit( 1 );
			$coupon->set_usage_limit_per_user( 1 );
			$coupon->set_email_restrictions( array( $email ) );
			$coupon->set_free_shipping( false );
			$coupon->set_date_expires( time() + max( 1, absint( $settings['coupon_expiry_days'] ) ) * DAY_IN_SECONDS );
			$coupon->add_meta_data( '_pepselect_exit_offer', 1, true );
			$coupon->add_meta_data( '_pepselect_exit_email_hash', hash_hmac( 'sha256', strtolower( $email ), wp_salt( 'auth' ) ), true );
			$coupon->save();
			set_transient( $this->email_coupon_key( $email ), $code, max( 1, absint( $settings['coupon_expiry_days'] ) ) * DAY_IN_SECONDS );
			return $code;
		} catch ( Exception $exception ) {
			return '';
		}
	}

	private function email_coupon_key( $email ) {
		return 'pep_exit_coupon_' . hash_hmac( 'sha256', strtolower( $email ), wp_salt( 'auth' ) );
	}

	private function bonus_coupon_key( $email ) {
		return 'pep_exit_bonus_coupon_' . hash_hmac( 'sha256', strtolower( $email ), wp_salt( 'auth' ) );
	}

	private function coupon_for_email( $email ) {
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
					'source' => 'Pep Select 20% exit offer',
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
		$subject = __( 'Your private 20% code from Pep Select', 'pepselect-cart-recovery' );
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
		$pep_coupon_days     = max( 1, absint( $settings['coupon_expiry_days'] ) );
		$pep_shop_url        = home_url( '/shop/' );
		$pep_support_email   = 'support@pepselect.com';
		$pep_unsubscribe_url = 'mailto:support@pepselect.com?subject=' . rawurlencode( 'Unsubscribe me from Pep Select emails' );
		$pep_logo_url        = 'https://pepselect.com/wp-content/uploads/2026/06/Logo_Pepselect_Whitebackground-1.png';

		ob_start();
		include __DIR__ . '/templates/coupon-email.php';
		return (string) ob_get_clean();
	}

	public function register_settings() {
		register_setting( 'pepselect_cart_recovery', self::OPTION, array( $this, 'sanitize_settings' ) );
	}

	public function sanitize_settings( $input ) {
		return array(
			'enabled'            => empty( $input['enabled'] ) ? 0 : 1,
			'coupon_expiry_days' => min( 30, max( 1, absint( $input['coupon_expiry_days'] ?? 7 ) ) ),
			'dismiss_days'       => min( 365, max( 1, absint( $input['dismiss_days'] ?? 30 ) ) ),
			'fluentcrm_list_id'  => absint( $input['fluentcrm_list_id'] ?? 0 ),
			'final_template_id'  => absint( $input['final_template_id'] ?? 0 ),
		);
	}

	public function register_settings_page() {
		add_submenu_page(
			'woocommerce',
			__( 'Pep Select Cart Recovery', 'pepselect-cart-recovery' ),
			__( 'Exit Offer', 'pepselect-cart-recovery' ),
			'manage_woocommerce',
			'pepselect-cart-recovery',
			array( $this, 'settings_page' )
		);
	}

	public function settings_page() {
		$settings = $this->settings();
		?>
		<div class="wrap"><h1><?php esc_html_e( 'Pep Select Exit Offer', 'pepselect-cart-recovery' ); ?></h1>
			<p><?php esc_html_e( 'The offer stays off until enabled here. Cart email timing and bodies remain in WooCommerce Cart Abandonment Recovery.', 'pepselect-cart-recovery' ); ?></p>
			<form method="post" action="options.php"><?php settings_fields( 'pepselect_cart_recovery' ); ?>
			<table class="form-table" role="presentation">
				<tr><th scope="row"><?php esc_html_e( 'Exit offer', 'pepselect-cart-recovery' ); ?></th><td><label><input name="<?php echo esc_attr( self::OPTION ); ?>[enabled]" type="checkbox" value="1" <?php checked( $settings['enabled'], 1 ); ?>> <?php esc_html_e( 'Enable the public offer', 'pepselect-cart-recovery' ); ?></label></td></tr>
				<tr><th scope="row"><label for="pep-expiry"><?php esc_html_e( 'Coupon expiry', 'pepselect-cart-recovery' ); ?></label></th><td><input id="pep-expiry" name="<?php echo esc_attr( self::OPTION ); ?>[coupon_expiry_days]" type="number" min="1" max="30" value="<?php echo esc_attr( $settings['coupon_expiry_days'] ); ?>"> <?php esc_html_e( 'days', 'pepselect-cart-recovery' ); ?></td></tr>
				<tr><th scope="row"><label for="pep-dismiss"><?php esc_html_e( 'Dismiss cooldown', 'pepselect-cart-recovery' ); ?></label></th><td><input id="pep-dismiss" name="<?php echo esc_attr( self::OPTION ); ?>[dismiss_days]" type="number" min="1" max="365" value="<?php echo esc_attr( $settings['dismiss_days'] ); ?>"> <?php esc_html_e( 'days', 'pepselect-cart-recovery' ); ?></td></tr>
				<tr><th scope="row"><label for="pep-list"><?php esc_html_e( 'FluentCRM list ID', 'pepselect-cart-recovery' ); ?></label></th><td><input id="pep-list" name="<?php echo esc_attr( self::OPTION ); ?>[fluentcrm_list_id]" type="number" min="0" value="<?php echo esc_attr( $settings['fluentcrm_list_id'] ); ?>"><p class="description"><?php esc_html_e( 'Subscribers join this list after requesting the 20% code. Use 0 to disable list sync.', 'pepselect-cart-recovery' ); ?></p></td></tr>
				<tr><th scope="row"><label for="pep-final-template"><?php esc_html_e( '48-hour email template ID', 'pepselect-cart-recovery' ); ?></label></th><td><input id="pep-final-template" name="<?php echo esc_attr( self::OPTION ); ?>[final_template_id]" type="number" min="0" value="<?php echo esc_attr( $settings['final_template_id'] ); ?>"><p class="description"><?php esc_html_e( 'This final recovery email sends only when the cart has a 20% signup code, then creates its separate stackable 5% code.', 'pepselect-cart-recovery' ); ?></p></td></tr>
			</table><?php submit_button(); ?></form>
		</div>
		<?php
	}
}

register_activation_hook( __FILE__, array( 'PepSelect_Cart_Recovery', 'activate' ) );
PepSelect_Cart_Recovery::instance();

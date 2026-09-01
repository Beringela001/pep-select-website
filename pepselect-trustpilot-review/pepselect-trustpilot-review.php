<?php
/**
 * Plugin Name: Pep Select Trustpilot Review Invitations
 * Description: Sends one neutral, branded Trustpilot review request after an eligible WooCommerce order is completed.
 * Version: 0.1.0
 * Author: Pep Select
 * Requires at least: 6.4
 * Requires PHP: 7.4
 * Text Domain: pepselect-trustpilot-review
 */

defined( 'ABSPATH' ) || exit;

final class PepSelect_Trustpilot_Review_Invitations {
	const VERSION            = '0.1.0';
	const ACTION_HOOK        = 'pepselect_send_trustpilot_review_invitation';
	const ACTION_GROUP       = 'pepselect-trustpilot-review';
	const ORDER_SCHEDULED    = '_pepselect_trustpilot_review_scheduled_at';
	const ORDER_SENT         = '_pepselect_trustpilot_review_sent_at';
	const ORDER_ATTEMPTS     = '_pepselect_trustpilot_review_attempts';
	const ORDER_OPTED_OUT    = '_pepselect_trustpilot_review_opted_out';
	const OPTOUT_OPTION      = 'pepselect_trustpilot_review_optouts';
	const ENABLED_OPTION     = 'pepselect_trustpilot_review_enabled';
	const REVIEW_URL         = 'https://www.trustpilot.com/evaluate/pepselect.com';
	const DEFAULT_DELAY_DAYS = 7;

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register_admin_page' ) );
		add_action( 'admin_post_pepselect_trustpilot_review_settings', array( __CLASS__, 'save_admin_settings' ) );
		add_action( 'admin_post_pepselect_trustpilot_review_preview', array( __CLASS__, 'render_admin_preview' ) );
		add_action( 'woocommerce_order_status_completed', array( __CLASS__, 'schedule_for_order' ), 20, 1 );
		add_action( self::ACTION_HOOK, array( __CLASS__, 'send_for_order' ), 10, 1 );

		foreach ( array( 'cancelled', 'failed', 'refunded' ) as $status ) {
			add_action( 'woocommerce_order_status_' . $status, array( __CLASS__, 'cancel_for_order' ), 20, 1 );
		}

		add_action( 'template_redirect', array( __CLASS__, 'handle_optout' ), 1 );
	}

	/**
	 * Schedule one request after WooCommerce marks an order completed.
	 *
	 * @param int $order_id WooCommerce order ID.
	 */
	public static function schedule_for_order( $order_id ) {
		if ( ! self::is_enabled() ) {
			return;
		}

		$order = wc_get_order( $order_id );

		if ( ! self::is_eligible( $order ) || $order->get_meta( self::ORDER_SENT ) ) {
			return;
		}

		$args = array( absint( $order_id ) );

		if ( self::has_scheduled_action( $args ) ) {
			return;
		}

		$delay = (int) apply_filters(
			'pepselect_trustpilot_review_delay',
			self::DEFAULT_DELAY_DAYS * DAY_IN_SECONDS,
			$order
		);
		$delay = max( HOUR_IN_SECONDS, $delay );
		$when  = time() + $delay;

		self::schedule_action( $when, $args );
		$order->update_meta_data( self::ORDER_SCHEDULED, gmdate( 'c', $when ) );
		$order->save();
	}

	/**
	 * Send the scheduled review request if the order remains eligible.
	 *
	 * @param int $order_id WooCommerce order ID.
	 */
	public static function send_for_order( $order_id ) {
		if ( ! self::is_enabled() ) {
			return;
		}

		$order = wc_get_order( $order_id );

		if ( ! self::is_eligible( $order ) || $order->get_meta( self::ORDER_SENT ) ) {
			return;
		}

		$recipient = sanitize_email( $order->get_billing_email() );
		$subject   = apply_filters(
			'pepselect_trustpilot_review_subject',
			__( 'How was your Pep Select experience?', 'pepselect-trustpilot-review' ),
			$order
		);
		$headers   = array(
			'Content-Type: text/html; charset=UTF-8',
			'From: Pep Select <support@pepselect.com>',
			'Reply-To: Pep Select <support@pepselect.com>',
		);
		$sent      = wp_mail( $recipient, $subject, self::render_email( $order ), $headers );

		$attempts = absint( $order->get_meta( self::ORDER_ATTEMPTS ) ) + 1;
		$order->update_meta_data( self::ORDER_ATTEMPTS, $attempts );

		if ( $sent ) {
			$order->update_meta_data( self::ORDER_SENT, gmdate( 'c' ) );
			$order->delete_meta_data( self::ORDER_SCHEDULED );
			$order->save();
			return;
		}

		$order->save();

		if ( $attempts < 3 ) {
			self::schedule_action( time() + ( 6 * HOUR_IN_SECONDS ), array( absint( $order_id ) ) );
		}
	}

	/**
	 * Cancel a pending invitation when an order moves to an ineligible state.
	 *
	 * @param int $order_id WooCommerce order ID.
	 */
	public static function cancel_for_order( $order_id ) {
		$args = array( absint( $order_id ) );

		if ( function_exists( 'as_unschedule_all_actions' ) ) {
			as_unschedule_all_actions( self::ACTION_HOOK, $args, self::ACTION_GROUP );
		} else {
			wp_clear_scheduled_hook( self::ACTION_HOOK, $args );
		}

		$order = wc_get_order( $order_id );
		if ( $order ) {
			$order->delete_meta_data( self::ORDER_SCHEDULED );
			$order->save();
		}
	}

	/**
	 * Confirm that a customer may receive the invitation.
	 *
	 * @param WC_Order|false $order WooCommerce order.
	 * @return bool
	 */
	private static function is_eligible( $order ) {
		if ( ! $order instanceof WC_Order || ! $order->has_status( 'completed' ) ) {
			return false;
		}

		$email = sanitize_email( $order->get_billing_email() );
		if ( ! is_email( $email ) || $order->get_meta( self::ORDER_OPTED_OUT ) || self::email_is_opted_out( $email ) ) {
			return false;
		}

		return (bool) apply_filters( 'pepselect_trustpilot_review_order_is_eligible', true, $order );
	}

	/**
	 * Build the final customer email.
	 *
	 * @param WC_Order $order WooCommerce order.
	 * @return string
	 */
	private static function render_email( $order ) {
		$first_name = trim( (string) $order->get_billing_first_name() );
		$data       = array(
			'first_name'     => '' !== $first_name ? $first_name : __( 'there', 'pepselect-trustpilot-review' ),
			'order_number'   => $order->get_order_number(),
			'review_url'     => self::REVIEW_URL,
			'optout_url'     => self::optout_url( $order ),
			'logo_url'       => 'https://pepselect.com/wp-content/uploads/2026/06/Logo_Pepselect_Whitebackground-1.png',
			'flag_url'       => plugin_dir_url( __FILE__ ) . 'assets/us-flag-email.png',
			'support_email'  => 'support@pepselect.com',
			'company_phone'  => '1 (833) 737-7528',
			'company_tel'    => '+18337377528',
			'company_address'=> '2090 Baker Rd, Ste 304 #A85, Kennesaw, GA 30144',
		);

		return self::render_email_data( $data );
	}

	/**
	 * Render a prepared set of non-sensitive email values.
	 *
	 * @param array $data Template values.
	 * @return string
	 */
	private static function render_email_data( $data ) {
		ob_start();
		include __DIR__ . '/templates/review-email.php';
		return (string) ob_get_clean();
	}

	/**
	 * Add the operations page beneath WooCommerce.
	 */
	public static function register_admin_page() {
		add_submenu_page(
			'woocommerce',
			__( 'Review Invitations', 'pepselect-trustpilot-review' ),
			__( 'Review Invitations', 'pepselect-trustpilot-review' ),
			'manage_woocommerce',
			'pepselect-trustpilot-review',
			array( __CLASS__, 'render_admin_page' )
		);
	}

	/**
	 * Render the small operational control and private sample preview.
	 */
	public static function render_admin_page() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		$enabled     = self::is_enabled();
		$preview_url = wp_nonce_url(
			admin_url( 'admin-post.php?action=pepselect_trustpilot_review_preview' ),
			'pepselect_trustpilot_review_preview'
		);
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Pep Select Review Invitations', 'pepselect-trustpilot-review' ); ?></h1>
			<p><?php esc_html_e( 'One neutral review request is scheduled seven days after an eligible order reaches Completed status.', 'pepselect-trustpilot-review' ); ?></p>
			<table class="widefat striped" style="max-width:760px;margin:18px 0;"><tbody>
				<tr><th><?php esc_html_e( 'Status', 'pepselect-trustpilot-review' ); ?></th><td><strong><?php echo esc_html( $enabled ? __( 'Enabled', 'pepselect-trustpilot-review' ) : __( 'Paused', 'pepselect-trustpilot-review' ) ); ?></strong></td></tr>
				<tr><th><?php esc_html_e( 'Trigger', 'pepselect-trustpilot-review' ); ?></th><td><?php esc_html_e( 'WooCommerce order completed', 'pepselect-trustpilot-review' ); ?></td></tr>
				<tr><th><?php esc_html_e( 'Delay', 'pepselect-trustpilot-review' ); ?></th><td><?php esc_html_e( '7 days', 'pepselect-trustpilot-review' ); ?></td></tr>
				<tr><th><?php esc_html_e( 'Destination', 'pepselect-trustpilot-review' ); ?></th><td><code><?php echo esc_html( self::REVIEW_URL ); ?></code></td></tr>
			</tbody></table>
			<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
				<input type="hidden" name="action" value="pepselect_trustpilot_review_settings">
				<?php wp_nonce_field( 'pepselect_trustpilot_review_settings' ); ?>
				<input type="hidden" name="enabled" value="<?php echo esc_attr( $enabled ? 'no' : 'yes' ); ?>">
				<?php submit_button( $enabled ? __( 'Pause invitations', 'pepselect-trustpilot-review' ) : __( 'Enable invitations', 'pepselect-trustpilot-review' ), $enabled ? 'secondary' : 'primary', 'submit', false ); ?>
			</form>
			<h2 style="margin-top:30px;"><?php esc_html_e( 'Desktop email preview', 'pepselect-trustpilot-review' ); ?></h2>
			<iframe src="<?php echo esc_url( $preview_url ); ?>" title="<?php esc_attr_e( 'Desktop review invitation email preview', 'pepselect-trustpilot-review' ); ?>" style="background:#E9EEF4;border:1px solid #CCD7DF;height:940px;max-width:760px;width:100%;"></iframe>
			<h2 style="margin-top:30px;"><?php esc_html_e( 'Mobile email preview', 'pepselect-trustpilot-review' ); ?></h2>
			<p><?php esc_html_e( 'A true 360-pixel-wide preview for checking image scaling, wrapping, CTA visibility, and footer alignment.', 'pepselect-trustpilot-review' ); ?></p>
			<iframe src="<?php echo esc_url( $preview_url ); ?>" title="<?php esc_attr_e( 'Mobile review invitation email preview', 'pepselect-trustpilot-review' ); ?>" style="background:#E9EEF4;border:1px solid #CCD7DF;height:1180px;max-width:100%;width:360px;"></iframe>
		</div>
		<?php
	}

	/**
	 * Save the enabled state through an explicit administrator action.
	 */
	public static function save_admin_settings() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( esc_html__( 'You are not allowed to manage review invitations.', 'pepselect-trustpilot-review' ) );
		}

		check_admin_referer( 'pepselect_trustpilot_review_settings' );
		$enabled = isset( $_POST['enabled'] ) && 'yes' === sanitize_text_field( wp_unslash( $_POST['enabled'] ) );
		update_option( self::ENABLED_OPTION, $enabled ? 'yes' : 'no', false );
		wp_safe_redirect( admin_url( 'admin.php?page=pepselect-trustpilot-review&updated=1' ) );
		exit;
	}

	/**
	 * Display a private placeholder preview without reading any customer order.
	 */
	public static function render_admin_preview() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( esc_html__( 'You are not allowed to preview this email.', 'pepselect-trustpilot-review' ) );
		}

		check_admin_referer( 'pepselect_trustpilot_review_preview' );
		nocache_headers();
		header( 'Content-Type: text/html; charset=UTF-8' );
		echo self::render_email_data(
			array(
				'first_name'      => 'Alex',
				'order_number'    => '12345',
				'review_url'      => self::REVIEW_URL,
				'optout_url'      => '#',
				'logo_url'        => 'https://pepselect.com/wp-content/uploads/2026/06/Logo_Pepselect_Whitebackground-1.png',
				'flag_url'        => plugin_dir_url( __FILE__ ) . 'assets/us-flag-email.png',
				'support_email'   => 'support@pepselect.com',
				'company_phone'   => '1 (833) 737-7528',
				'company_tel'     => '+18337377528',
				'company_address' => '2090 Baker Rd, Ste 304 #A85, Kennesaw, GA 30144',
			)
		);
		exit;
	}

	/**
	 * Create a signed, order-scoped opt-out URL without exposing an email address.
	 *
	 * @param WC_Order $order WooCommerce order.
	 * @return string
	 */
	private static function optout_url( $order ) {
		$order_id = (string) $order->get_id();
		$email    = strtolower( sanitize_email( $order->get_billing_email() ) );
		$sig      = hash_hmac( 'sha256', $order_id . '|' . $email, wp_salt( 'auth' ) );

		return add_query_arg(
			array(
				'pep_review_optout' => $order_id,
				'pep_review_sig'    => $sig,
			),
			home_url( '/' )
		);
	}

	/**
	 * Process a signed review-invitation opt-out.
	 */
	public static function handle_optout() {
		if ( empty( $_GET['pep_review_optout'] ) || empty( $_GET['pep_review_sig'] ) ) {
			return;
		}

		$order_id = absint( wp_unslash( $_GET['pep_review_optout'] ) );
		$sig      = sanitize_text_field( wp_unslash( $_GET['pep_review_sig'] ) );
		$order    = wc_get_order( $order_id );

		if ( ! $order ) {
			self::render_optout_result( false );
		}

		$email    = strtolower( sanitize_email( $order->get_billing_email() ) );
		$expected = hash_hmac( 'sha256', (string) $order_id . '|' . $email, wp_salt( 'auth' ) );

		if ( ! is_email( $email ) || ! hash_equals( $expected, $sig ) ) {
			self::render_optout_result( false );
		}

		$optouts = get_option( self::OPTOUT_OPTION, array() );
		$optouts = is_array( $optouts ) ? $optouts : array();
		$optouts[ self::email_hash( $email ) ] = gmdate( 'c' );
		update_option( self::OPTOUT_OPTION, $optouts, false );

		$order->update_meta_data( self::ORDER_OPTED_OUT, gmdate( 'c' ) );
		$order->save();
		self::cancel_for_order( $order_id );
		self::render_optout_result( true );
	}

	/**
	 * Render a small confirmation without exposing customer or order data.
	 *
	 * @param bool $success Whether the signed opt-out succeeded.
	 */
	private static function render_optout_result( $success ) {
		status_header( $success ? 200 : 400 );
		nocache_headers();
		$title   = $success ? __( 'Review invitations stopped', 'pepselect-trustpilot-review' ) : __( 'This link is no longer valid', 'pepselect-trustpilot-review' );
		$message = $success
			? __( 'You will not receive another Pep Select review invitation at this email address.', 'pepselect-trustpilot-review' )
			: __( 'Please contact Pep Select support if you still want to stop review invitations.', 'pepselect-trustpilot-review' );
		?>
		<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?php echo esc_html( $title ); ?></title></head><body style="background:#E9EEF4;color:#001D3A;font-family:Arial,sans-serif;margin:0;padding:40px 16px;"><main style="background:#FFFFFF;border-top:6px solid #17A1CF;border-radius:12px;box-shadow:0 12px 36px rgba(0,42,83,.12);margin:0 auto;max-width:560px;padding:36px;"><h1 style="font-size:28px;margin:0 0 14px;"><?php echo esc_html( $title ); ?></h1><p style="color:#5E6F80;font-size:16px;line-height:1.6;margin:0 0 22px;"><?php echo esc_html( $message ); ?></p><a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color:#006C8F;">Return to Pep Select</a></main></body></html>
		<?php
		exit;
	}

	private static function email_is_opted_out( $email ) {
		$optouts = get_option( self::OPTOUT_OPTION, array() );
		return is_array( $optouts ) && isset( $optouts[ self::email_hash( $email ) ] );
	}

	private static function email_hash( $email ) {
		return hash_hmac( 'sha256', strtolower( sanitize_email( $email ) ), wp_salt( 'auth' ) );
	}

	private static function is_enabled() {
		return 'yes' === get_option( self::ENABLED_OPTION, 'no' );
	}

	private static function has_scheduled_action( $args ) {
		if ( function_exists( 'as_has_scheduled_action' ) ) {
			return (bool) as_has_scheduled_action( self::ACTION_HOOK, $args, self::ACTION_GROUP );
		}
		return (bool) wp_next_scheduled( self::ACTION_HOOK, $args );
	}

	private static function schedule_action( $timestamp, $args ) {
		if ( function_exists( 'as_schedule_single_action' ) ) {
			as_schedule_single_action( $timestamp, self::ACTION_HOOK, $args, self::ACTION_GROUP, true );
			return;
		}
		wp_schedule_single_event( $timestamp, self::ACTION_HOOK, $args, true );
	}
}

add_action( 'plugins_loaded', array( 'PepSelect_Trustpilot_Review_Invitations', 'init' ) );

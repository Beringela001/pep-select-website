<?php
/**
 * Plugin Name: Pep Select Trustpilot Review Invitations
 * Description: Sends one neutral, branded Trustpilot review request after an eligible WooCommerce order is completed.
 * Version: 0.2.0
 * Author: Pep Select
 * Requires at least: 6.4
 * Requires PHP: 7.4
 * Text Domain: pepselect-trustpilot-review
 */

defined( 'ABSPATH' ) || exit;

final class PepSelect_Trustpilot_Review_Invitations {
	const VERSION            = '0.2.0';
	const ACTION_HOOK        = 'pepselect_send_trustpilot_review_invitation';
	const ACTION_GROUP       = 'pepselect-trustpilot-review';
	const ORDER_SCHEDULED    = '_pepselect_trustpilot_review_scheduled_at';
	const ORDER_SENT         = '_pepselect_trustpilot_review_sent_at';
	const ORDER_ATTEMPTS     = '_pepselect_trustpilot_review_attempts';
	const ORDER_OPTED_OUT    = '_pepselect_trustpilot_review_opted_out';
	const OPTOUT_OPTION      = 'pepselect_trustpilot_review_optouts';
	const ENABLED_OPTION     = 'pepselect_trustpilot_review_enabled';
	const CUSTOMER_SENT_OPTION    = 'pepselect_trustpilot_review_customer_sent';
	const CUSTOMER_PENDING_OPTION = 'pepselect_trustpilot_review_customer_pending';
	const CATCHUP_OPTION          = 'pepselect_trustpilot_review_catchup_v2';
	const REVIEW_URL              = 'https://www.trustpilot.com/evaluate/pepselect.com';
	const DEFAULT_DELAY_DAYS      = 7;
	const CUSTOMER_COOLDOWN_DAYS = 180;
	const CATCHUP_SPACING_SECONDS = 20;

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register_admin_page' ) );
		add_action( 'admin_post_pepselect_trustpilot_review_settings', array( __CLASS__, 'save_admin_settings' ) );
		add_action( 'admin_post_pepselect_trustpilot_review_catchup', array( __CLASS__, 'run_admin_catchup' ) );
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
	public static function schedule_for_order( $order_id, $requested_timestamp = 0 ) {
		if ( ! self::is_enabled() ) {
			return false;
		}

		$order = wc_get_order( $order_id );

		if ( ! self::is_eligible( $order ) || $order->get_meta( self::ORDER_SENT ) ) {
			return false;
		}

		$args = array( absint( $order_id ) );
		$email = self::normalize_email( $order->get_billing_email() );

		if ( self::has_scheduled_action( $args ) ) {
			self::record_customer_pending( $email, $order_id, self::order_due_timestamp( $order ) );
			return false;
		}

		$pending = self::customer_pending( $email );
		if ( $pending && absint( $pending['order_id'] ) !== absint( $order_id ) ) {
			$pending_args = array( absint( $pending['order_id'] ) );
			if ( self::has_scheduled_action( $pending_args ) ) {
				return false;
			}
			self::clear_customer_pending( $email, absint( $pending['order_id'] ) );
		}

		$when = $requested_timestamp ? absint( $requested_timestamp ) : self::order_due_timestamp( $order );
		$when = max( time() + MINUTE_IN_SECONDS, $when );

		if ( ! self::schedule_action( $when, $args ) ) {
			return false;
		}
		self::record_customer_pending( $email, $order_id, $when );
		$order->update_meta_data( self::ORDER_SCHEDULED, gmdate( 'c', $when ) );
		$order->save();
		return true;
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
			if ( $order instanceof WC_Order ) {
				self::clear_order_pending_state( $order );
			}
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
			self::record_customer_sent( $recipient, time() );
			self::clear_customer_pending( $recipient, $order_id );
			return;
		}

		$order->save();

		if ( $attempts < 3 ) {
			$retry_at = time() + ( 6 * HOUR_IN_SECONDS );
			if ( self::schedule_action( $retry_at, array( absint( $order_id ) ) ) ) {
				self::record_customer_pending( $recipient, $order_id, $retry_at );
			} else {
				self::clear_order_pending_state( $order );
			}
		} else {
			self::clear_order_pending_state( $order );
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
			self::clear_order_pending_state( $order );
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

		$email = self::normalize_email( $order->get_billing_email() );
		if ( ! is_email( $email ) || $order->get_meta( self::ORDER_OPTED_OUT ) || self::email_is_opted_out( $email ) ) {
			return false;
		}

		$last_sent = self::customer_last_sent_at( $email );
		if ( $last_sent && ( time() - $last_sent ) < ( self::CUSTOMER_COOLDOWN_DAYS * DAY_IN_SECONDS ) ) {
			return false;
		}

		return (bool) apply_filters( 'pepselect_trustpilot_review_order_is_eligible', true, $order );
	}

	/**
	 * Schedule one catch-up invitation per customer from existing completed orders.
	 * The customer's most recent completed order is used as the experience reference.
	 *
	 * @return array Non-sensitive scheduling totals.
	 */
	public static function schedule_historical_orders() {
		$summary = array(
			'customers'      => 0,
			'queued_now'     => 0,
			'scheduled_later' => 0,
			'skipped'        => 0,
			'completed_at'   => '',
		);

		if ( ! self::is_enabled() || ! function_exists( 'wc_get_orders' ) ) {
			return $summary;
		}

		$orders = wc_get_orders(
			array(
				'status'  => array( 'completed' ),
				'limit'   => -1,
				'return'  => 'objects',
				'orderby' => 'date',
				'order'   => 'DESC',
			)
		);
		$orders = is_array( $orders ) ? $orders : array();

		// Import any order-level send records created by version 0.1.0 before applying the cooldown.
		foreach ( $orders as $order ) {
			if ( ! $order instanceof WC_Order ) {
				continue;
			}
			$email   = self::normalize_email( $order->get_billing_email() );
			$sent_at = strtotime( (string) $order->get_meta( self::ORDER_SENT ) );
			if ( is_email( $email ) && $sent_at && $sent_at > self::customer_last_sent_at( $email ) ) {
				self::record_customer_sent( $email, $sent_at );
			}
		}

		$selected = array();
		foreach ( $orders as $order ) {
			if ( ! $order instanceof WC_Order ) {
				continue;
			}
			$email = self::normalize_email( $order->get_billing_email() );
			$key   = is_email( $email ) ? self::email_hash( $email ) : '';
			if ( '' === $key ) {
				continue;
			}
			if ( isset( $selected[ $key ] ) ) {
				if ( self::has_scheduled_action( array( absint( $order->get_id() ) ) ) ) {
					self::cancel_for_order( $order->get_id() );
				}
				continue;
			}
			$selected[ $key ] = $order;
		}

		$summary['customers'] = count( $selected );
		$catchup_index        = 0;
		$now                  = time();

		foreach ( $selected as $order ) {
			if ( ! self::is_eligible( $order ) || $order->get_meta( self::ORDER_SENT ) ) {
				++$summary['skipped'];
				continue;
			}

			$due = self::order_due_timestamp( $order );
			if ( $due <= $now ) {
				$when = $now + MINUTE_IN_SECONDS + ( $catchup_index * self::CATCHUP_SPACING_SECONDS );
				++$catchup_index;
			} else {
				$when = $due;
			}

			if ( self::schedule_for_order( $order->get_id(), $when ) ) {
				if ( $due <= $now ) {
					++$summary['queued_now'];
				} else {
					++$summary['scheduled_later'];
				}
			} else {
				++$summary['skipped'];
			}
		}

		$summary['completed_at'] = gmdate( 'c' );
		update_option( self::CATCHUP_OPTION, $summary, false );
		return $summary;
	}

	/**
	 * Calculate seven days from the actual order completion time.
	 *
	 * @param WC_Order $order WooCommerce order.
	 * @return int Unix timestamp.
	 */
	private static function order_due_timestamp( $order ) {
		$completed = $order->get_date_completed();
		$base      = $completed && is_callable( array( $completed, 'getTimestamp' ) ) ? $completed->getTimestamp() : time();
		$delay     = (int) apply_filters(
			'pepselect_trustpilot_review_delay',
			self::DEFAULT_DELAY_DAYS * DAY_IN_SECONDS,
			$order
		);
		return absint( $base ) + max( HOUR_IN_SECONDS, $delay );
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
		$catchup     = get_option( self::CATCHUP_OPTION, array() );
		$catchup     = is_array( $catchup ) ? $catchup : array();
		$preview_url = wp_nonce_url(
			admin_url( 'admin-post.php?action=pepselect_trustpilot_review_preview' ),
			'pepselect_trustpilot_review_preview'
		);
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Pep Select Review Invitations', 'pepselect-trustpilot-review' ); ?></h1>
			<p><?php esc_html_e( 'One neutral review request is scheduled seven days after an eligible order reaches Completed status, with a 180-day customer cooldown.', 'pepselect-trustpilot-review' ); ?></p>
			<table class="widefat striped" style="max-width:760px;margin:18px 0;"><tbody>
				<tr><th><?php esc_html_e( 'Status', 'pepselect-trustpilot-review' ); ?></th><td><strong><?php echo esc_html( $enabled ? __( 'Enabled', 'pepselect-trustpilot-review' ) : __( 'Paused', 'pepselect-trustpilot-review' ) ); ?></strong></td></tr>
				<tr><th><?php esc_html_e( 'Trigger', 'pepselect-trustpilot-review' ); ?></th><td><?php esc_html_e( 'WooCommerce order completed', 'pepselect-trustpilot-review' ); ?></td></tr>
				<tr><th><?php esc_html_e( 'Delay', 'pepselect-trustpilot-review' ); ?></th><td><?php esc_html_e( '7 days', 'pepselect-trustpilot-review' ); ?></td></tr>
				<tr><th><?php esc_html_e( 'Customer cadence', 'pepselect-trustpilot-review' ); ?></th><td><?php esc_html_e( 'At most once every 180 days per billing email', 'pepselect-trustpilot-review' ); ?></td></tr>
				<tr><th><?php esc_html_e( 'Existing-customer catch-up', 'pepselect-trustpilot-review' ); ?></th><td>
					<?php if ( ! empty( $catchup['completed_at'] ) ) : ?>
						<?php
						echo esc_html(
							sprintf(
								/* translators: 1: queued now count, 2: scheduled later count, 3: skipped count. */
								__( 'Complete — %1$d queued promptly, %2$d scheduled for their seven-day mark, %3$d skipped.', 'pepselect-trustpilot-review' ),
								absint( $catchup['queued_now'] ?? 0 ),
								absint( $catchup['scheduled_later'] ?? 0 ),
								absint( $catchup['skipped'] ?? 0 )
							)
						);
						?>
					<?php else : ?>
						<?php esc_html_e( 'Not started', 'pepselect-trustpilot-review' ); ?>
					<?php endif; ?>
				</td></tr>
				<tr><th><?php esc_html_e( 'Destination', 'pepselect-trustpilot-review' ); ?></th><td><code><?php echo esc_html( self::REVIEW_URL ); ?></code></td></tr>
			</tbody></table>
			<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
				<input type="hidden" name="action" value="pepselect_trustpilot_review_settings">
				<?php wp_nonce_field( 'pepselect_trustpilot_review_settings' ); ?>
				<input type="hidden" name="enabled" value="<?php echo esc_attr( $enabled ? 'no' : 'yes' ); ?>">
				<?php submit_button( $enabled ? __( 'Pause invitations', 'pepselect-trustpilot-review' ) : __( 'Enable invitations', 'pepselect-trustpilot-review' ), $enabled ? 'secondary' : 'primary', 'submit', false ); ?>
			</form>
			<?php if ( $enabled && empty( $catchup['completed_at'] ) ) : ?>
				<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" style="margin-top:12px;">
					<input type="hidden" name="action" value="pepselect_trustpilot_review_catchup">
					<?php wp_nonce_field( 'pepselect_trustpilot_review_catchup' ); ?>
					<?php submit_button( __( 'Schedule existing customers', 'pepselect-trustpilot-review' ), 'primary', 'submit', false ); ?>
				</form>
			<?php endif; ?>
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
	 * Run the one-time catch-up from an explicit administrator action.
	 */
	public static function run_admin_catchup() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( esc_html__( 'You are not allowed to schedule review invitations.', 'pepselect-trustpilot-review' ) );
		}

		check_admin_referer( 'pepselect_trustpilot_review_catchup' );
		if ( ! get_option( self::CATCHUP_OPTION, false ) ) {
			self::schedule_historical_orders();
		}
		wp_safe_redirect( admin_url( 'admin.php?page=pepselect-trustpilot-review&catchup=1' ) );
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
		$pending = self::customer_pending( $email );
		if ( $pending ) {
			self::cancel_for_order( absint( $pending['order_id'] ) );
		}
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

	private static function normalize_email( $email ) {
		return strtolower( sanitize_email( $email ) );
	}

	private static function customer_last_sent_at( $email ) {
		$sent = get_option( self::CUSTOMER_SENT_OPTION, array() );
		$key  = self::email_hash( $email );
		return is_array( $sent ) && isset( $sent[ $key ] ) ? absint( $sent[ $key ] ) : 0;
	}

	private static function record_customer_sent( $email, $timestamp ) {
		$sent   = get_option( self::CUSTOMER_SENT_OPTION, array() );
		$sent   = is_array( $sent ) ? $sent : array();
		$cutoff = time() - ( self::CUSTOMER_COOLDOWN_DAYS * DAY_IN_SECONDS );

		foreach ( $sent as $key => $recorded_at ) {
			if ( absint( $recorded_at ) < $cutoff ) {
				unset( $sent[ $key ] );
			}
		}

		$sent[ self::email_hash( $email ) ] = absint( $timestamp );
		update_option( self::CUSTOMER_SENT_OPTION, $sent, false );
	}

	private static function customer_pending( $email ) {
		$pending = get_option( self::CUSTOMER_PENDING_OPTION, array() );
		$key     = self::email_hash( $email );
		return is_array( $pending ) && isset( $pending[ $key ] ) && is_array( $pending[ $key ] ) ? $pending[ $key ] : array();
	}

	private static function record_customer_pending( $email, $order_id, $timestamp ) {
		$pending = get_option( self::CUSTOMER_PENDING_OPTION, array() );
		$pending = is_array( $pending ) ? $pending : array();
		$pending[ self::email_hash( $email ) ] = array(
			'order_id'  => absint( $order_id ),
			'timestamp' => absint( $timestamp ),
		);
		update_option( self::CUSTOMER_PENDING_OPTION, $pending, false );
	}

	private static function clear_customer_pending( $email, $order_id ) {
		$pending = get_option( self::CUSTOMER_PENDING_OPTION, array() );
		$key     = self::email_hash( $email );
		if ( ! is_array( $pending ) || empty( $pending[ $key ] ) || absint( $pending[ $key ]['order_id'] ?? 0 ) !== absint( $order_id ) ) {
			return;
		}
		unset( $pending[ $key ] );
		update_option( self::CUSTOMER_PENDING_OPTION, $pending, false );
	}

	private static function clear_order_pending_state( $order ) {
		self::clear_customer_pending( $order->get_billing_email(), $order->get_id() );
		$order->delete_meta_data( self::ORDER_SCHEDULED );
		$order->save();
	}

	private static function email_hash( $email ) {
		return hash_hmac( 'sha256', self::normalize_email( $email ), wp_salt( 'auth' ) );
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
			return (bool) as_schedule_single_action( $timestamp, self::ACTION_HOOK, $args, self::ACTION_GROUP, true );
		}
		$result = wp_schedule_single_event( $timestamp, self::ACTION_HOOK, $args, true );
		return ! is_wp_error( $result ) && false !== $result;
	}
}

add_action( 'plugins_loaded', array( 'PepSelect_Trustpilot_Review_Invitations', 'init' ) );

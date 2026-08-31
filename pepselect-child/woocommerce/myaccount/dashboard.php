<?php
/**
 * My Account Dashboard (Pep Select custom) - single scrolling page.
 *
 * Replaces WooCommerce's dashboard intro and the four link rows with one
 * scrolling page of six cards: welcome + sign out, saved information, cash-back
 * summary in dollars, text-message preferences, the referral link, and the
 * customer's compact order history. The newest five orders remain visible;
 * older orders use native progressive disclosure.
 *
 * The left account navigation is hidden by CSS (see account.css); this is a
 * presentation change only. All seven account endpoints still resolve directly
 * and remain reachable from the cards here and from the site header.
 *
 * Data comes from native WooCommerce customer/order APIs and the theme's YITH
 * cash-back helpers (1 point = $0.01). YITH's engine is unchanged; tracking is
 * resolved by the existing pepselect_child_get_order_tracking() helper.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package PepSelectChild
 * @version 10.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pepselect_user = wp_get_current_user();
$pepselect_uid  = get_current_user_id();

$pepselect_first_name = $pepselect_user->first_name ? $pepselect_user->first_name : $pepselect_user->display_name;
$pepselect_full_name  = trim( $pepselect_user->first_name . ' ' . $pepselect_user->last_name );

if ( '' === $pepselect_full_name ) {
	$pepselect_full_name = $pepselect_user->display_name;
}

// Cash back (dollars), via theme helpers over YITH.
$pepselect_cashback = function_exists( 'pepselect_child_get_cashback_display' ) ? pepselect_child_get_cashback_display() : null;
$pepselect_totals   = function_exists( 'pepselect_child_get_cashback_totals' ) ? pepselect_child_get_cashback_totals() : null;
$pepselect_vanity_url = function_exists( 'pepselect_child_referral_vanity_url' )
	? pepselect_child_referral_vanity_url( $pepselect_uid )
	: '';

// Saved information.
$pepselect_customer     = new WC_Customer( $pepselect_uid );
$pepselect_phone        = $pepselect_customer->get_billing_phone();
$pepselect_ship_address = wc_get_account_formatted_address( 'shipping', $pepselect_uid );

// Endpoint URLs (routing stays native).
$pepselect_logout_url    = wc_logout_url();
$pepselect_edit_addr_url = wc_get_account_endpoint_url( 'edit-address' );
$pepselect_edit_acct_url = wc_get_account_endpoint_url( 'edit-account' );
$pepselect_cashback_url  = wc_get_account_endpoint_url( 'cash-back' );
$pepselect_shop_url      = wc_get_page_permalink( 'shop' );

// Orders, newest first, across the statuses a customer can see.
$pepselect_orders = wc_get_orders(
	array(
		'customer_id' => $pepselect_uid,
		'limit'       => 25,
		'orderby'     => 'date',
		'order'       => 'DESC',
		'status'      => array( 'completed', 'processing', 'on-hold', 'pending', 'cancelled', 'refunded', 'failed' ),
	)
);

// One pass over YITH's log: order_id => dollars earned. Empty when the log has
// no order references, in which case the per-order line is simply omitted.
$pepselect_earned_by_order = function_exists( 'pepselect_child_get_cashback_earned_by_order' )
	? pepselect_child_get_cashback_earned_by_order()
	: array();
?>

<div class="pepselect-dash">

	<?php // Card 1: welcome + sign out. ?>
	<section class="pepselect-card pepselect-card--welcome" aria-labelledby="pepselect-dash-welcome">
		<div class="pepselect-card__welcome-text">
			<h1 id="pepselect-dash-welcome" class="pepselect-card__title pepselect-card__title--welcome">
				<?php
				/* translators: %s: customer first name. */
				printf( esc_html__( 'Welcome, %s.', 'pepselect-child' ), esc_html( $pepselect_first_name ) );
				?>
			</h1>
			<?php if ( $pepselect_user->user_email ) : ?>
				<p class="pepselect-card__email"><?php echo esc_html( $pepselect_user->user_email ); ?></p>
			<?php endif; ?>
		</div>
		<a class="pepselect-btn pepselect-btn--ghost" href="<?php echo esc_url( $pepselect_logout_url ); ?>">
			<?php esc_html_e( 'Sign out', 'pepselect-child' ); ?>
		</a>
	</section>

	<div class="pepselect-dash__grid">

		<?php // Card 2: my information. ?>
		<section class="pepselect-card" aria-labelledby="pepselect-dash-info">
			<div class="pepselect-card__head">
				<h2 id="pepselect-dash-info" class="pepselect-card__title"><?php esc_html_e( 'My information', 'pepselect-child' ); ?></h2>
				<a class="pepselect-card__edit" href="<?php echo esc_url( $pepselect_edit_acct_url ); ?>"><?php esc_html_e( 'Edit', 'pepselect-child' ); ?></a>
			</div>

			<dl class="pepselect-info">
				<div class="pepselect-info__row">
					<dt class="pepselect-info__label"><?php esc_html_e( 'Name', 'pepselect-child' ); ?></dt>
					<dd class="pepselect-info__value"><?php echo esc_html( $pepselect_full_name ); ?></dd>
				</div>
				<div class="pepselect-info__row">
					<dt class="pepselect-info__label"><?php esc_html_e( 'Phone', 'pepselect-child' ); ?></dt>
					<dd class="pepselect-info__value">
						<?php echo $pepselect_phone ? esc_html( $pepselect_phone ) : '<span class="pepselect-info__muted">' . esc_html__( 'Not added yet', 'pepselect-child' ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</dd>
				</div>
				<div class="pepselect-info__row pepselect-info__row--address">
					<dt class="pepselect-info__label"><?php esc_html_e( 'Shipping address', 'pepselect-child' ); ?></dt>
					<dd class="pepselect-info__value">
						<?php if ( $pepselect_ship_address ) : ?>
							<address class="pepselect-info__address"><?php echo wp_kses_post( $pepselect_ship_address ); ?></address>
						<?php else : ?>
							<span class="pepselect-info__muted"><?php esc_html_e( 'No shipping address saved yet.', 'pepselect-child' ); ?></span>
							<a class="pepselect-info__add" href="<?php echo esc_url( $pepselect_edit_addr_url ); ?>"><?php esc_html_e( 'Add an address', 'pepselect-child' ); ?></a>
						<?php endif; ?>
					</dd>
				</div>
			</dl>

			<?php if ( $pepselect_ship_address ) : ?>
				<a class="pepselect-card__link" href="<?php echo esc_url( $pepselect_edit_addr_url ); ?>"><?php esc_html_e( 'Edit shipping address', 'pepselect-child' ); ?></a>
			<?php endif; ?>
		</section>

		<?php // Card 3: cash back. ?>
		<section class="pepselect-card pepselect-card--cashback" aria-labelledby="pepselect-dash-cashback">
			<div class="pepselect-card__head">
				<h2 id="pepselect-dash-cashback" class="pepselect-card__title"><?php esc_html_e( 'Cash back', 'pepselect-child' ); ?></h2>
				<a class="pepselect-card__edit" href="<?php echo esc_url( $pepselect_cashback_url ); ?>"><?php esc_html_e( 'Details', 'pepselect-child' ); ?></a>
			</div>

			<p class="pepselect-cashback-figure">
				<span class="pepselect-cashback-figure__value"><?php echo esc_html( null !== $pepselect_cashback ? $pepselect_cashback['balance_formatted'] : '$0.00' ); ?></span>
				<span class="pepselect-cashback-figure__label"><?php esc_html_e( 'Available to apply at checkout', 'pepselect-child' ); ?></span>
			</p>

			<?php if ( null !== $pepselect_totals ) : ?>
				<p class="pepselect-cashback-lifetime">
					<?php
					/* translators: %s: lifetime cash back earned, formatted in dollars. */
					printf( esc_html__( '%s earned in total so far.', 'pepselect-child' ), '<strong>' . esc_html( $pepselect_totals['earned_formatted'] ) . '</strong>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</p>
			<?php endif; ?>

			<p class="pepselect-cashback-explain">
				<?php esc_html_e( 'You earn 3% back on every completed order. Once your balance reaches $5 you can turn it into a code and apply it to any order at checkout.', 'pepselect-child' ); ?>
			</p>
		</section>

	</div>

	<?php // Card 4: text-message preferences. ?>
	<section id="pepselect-sms-preferences" class="pepselect-card pepselect-card--sms" aria-labelledby="pepselect-dash-sms">
		<div class="pepselect-card__head">
			<h2 id="pepselect-dash-sms" class="pepselect-card__title"><?php esc_html_e( 'Text message preferences', 'pepselect-child' ); ?></h2>
		</div>

		<p class="pepselect-sms__lead"><?php esc_html_e( 'Choose which text messages you want to receive.', 'pepselect-child' ); ?></p>

		<form class="pepselect-sms__form" method="post" action="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) . '#pepselect-sms-preferences' ); ?>">
			<div class="pepselect-sms__field">
				<label class="pepselect-sms__label" for="pepselect-sms-mobile"><?php esc_html_e( 'Mobile number', 'pepselect-child' ); ?></label>
				<input
					class="pepselect-sms__input"
					id="pepselect-sms-mobile"
					name="pepselect_sms_mobile"
					type="tel"
					inputmode="tel"
					autocomplete="tel"
					value="<?php echo esc_attr( $pepselect_phone ); ?>"
					aria-describedby="pepselect-sms-disclosure"
				/>
			</div>

			<fieldset class="pepselect-sms__choices" aria-describedby="pepselect-sms-disclosure">
				<legend class="pepselect-sms__legend"><?php esc_html_e( 'Texting consent', 'pepselect-child' ); ?></legend>
				<label class="pepselect-sms__choice">
					<input class="pepselect-sms__checkbox" type="checkbox" name="pepselect_sms_preferences[]" value="customer_care" />
					<span><?php esc_html_e( 'Yes, I consent to receive customer care messages from Pep Select', 'pepselect-child' ); ?></span>
				</label>
				<label class="pepselect-sms__choice">
					<input class="pepselect-sms__checkbox" type="checkbox" name="pepselect_sms_preferences[]" value="marketing" />
					<span><?php esc_html_e( 'Yes, I consent to receive marketing text messages from Pep Select', 'pepselect-child' ); ?></span>
				</label>
				<label class="pepselect-sms__choice">
					<input class="pepselect-sms__checkbox" type="checkbox" name="pepselect_sms_preferences[]" value="none" />
					<span><?php esc_html_e( 'No, I do not want to receive any text messages from Pep Select', 'pepselect-child' ); ?></span>
				</label>
			</fieldset>

			<?php wp_nonce_field( 'pepselect_save_sms_preferences', 'pepselect_sms_preferences_nonce' ); ?>
			<input type="hidden" name="pepselect_sms_preferences_action" value="save" />
			<button class="pepselect-btn pepselect-btn--solid" type="submit"><?php esc_html_e( 'Save text preferences', 'pepselect-child' ); ?></button>
		</form>

		<div id="pepselect-sms-disclosure" class="pepselect-sms__disclosure">
			<p>PS Research Solutions LLC (doing business as Pep Select) would like your consent to send customer care and/or marketing text message communications from (833) 737-7528 to your mobile number listed above. Customer care messages may include responses to messages you send us, as well as information relevant to your relationship with us. Marketing messages may include discount codes, special deals or texts promoting our products/services.</p>
			<p>Consent is not a condition of purchase. Message frequency varies. Message and data rates may apply. Reply 'STOP' to unsubscribe at any time. Reply 'HELP' for assistance or more information.</p>
			<p>We do not share your mobile opt-in information with anyone. Our combined Privacy Policy and Messaging Terms and Conditions are available at <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">https://pepselect.com/privacy-policy/</a>.</p>
		</div>
	</section>

	<?php if ( '' !== $pepselect_vanity_url ) : ?>
		<?php // Card 5: referral link. ?>
		<section class="pepselect-card pepselect-card--referral pepselect-cashback__referral" aria-labelledby="pepselect-dash-referral">
			<h2 id="pepselect-dash-referral" class="pepselect-cashback__section-title"><?php esc_html_e( 'Refer a friend', 'pepselect-child' ); ?></h2>
			<p class="pepselect-cashback__section-lead"><?php esc_html_e( 'Share your link. They save 10% on their first order, and you earn $15 in cash back once it completes.', 'pepselect-child' ); ?></p>

			<ol class="pepselect-cashback__refer-steps">
				<li class="pepselect-refer-step">
					<span class="pepselect-refer-step__num" aria-hidden="true">1</span>
					<p class="pepselect-refer-step__text"><?php esc_html_e( 'Share your link with a friend.', 'pepselect-child' ); ?></p>
				</li>
				<li class="pepselect-refer-step">
					<span class="pepselect-refer-step__num" aria-hidden="true">2</span>
					<p class="pepselect-refer-step__text">
						<?php
						printf(
							/* translators: %s: the welcome coupon code. */
							esc_html__( 'Tell them to use code %s at checkout for 10%% off their first order.', 'pepselect-child' ),
							'<span class="pepselect-refer-step__code">WELCOME10</span>'
						);
						?>
					</p>
				</li>
				<li class="pepselect-refer-step">
					<span class="pepselect-refer-step__num" aria-hidden="true">3</span>
					<p class="pepselect-refer-step__text"><?php esc_html_e( 'When their order completes, you get $15 in cash back.', 'pepselect-child' ); ?></p>
				</li>
			</ol>

			<div class="pepselect-cashback__referral-fields">
				<div class="pepselect-copyfield">
					<span class="pepselect-copyfield__label"><?php esc_html_e( 'Your share link', 'pepselect-child' ); ?></span>
					<input class="pepselect-copyfield__input" id="pepselect-referral-link" type="text" readonly value="<?php echo esc_url( $pepselect_vanity_url ); ?>" aria-label="<?php esc_attr_e( 'Your share link', 'pepselect-child' ); ?>" />
					<button class="pepselect-copyfield__copy" type="button"><?php esc_html_e( 'Copy', 'pepselect-child' ); ?></button>
				</div>
			</div>

			<div class="pepselect-cashback__mini-stats">
				<div class="pepselect-stat pepselect-stat--mini">
					<span class="pepselect-stat__label"><?php esc_html_e( 'Referral bonus', 'pepselect-child' ); ?></span>
					<span class="pepselect-stat__value"><?php echo esc_html( pepselect_child_format_dollars( 15 ) ); ?></span>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php // Card 6: your orders, inline. ?>
	<section class="pepselect-card pepselect-card--orders" aria-labelledby="pepselect-dash-orders">
		<div class="pepselect-card__head">
			<h2 id="pepselect-dash-orders" class="pepselect-card__title"><?php esc_html_e( 'Your orders', 'pepselect-child' ); ?></h2>
		</div>

		<?php if ( empty( $pepselect_orders ) ) : ?>

			<div class="pepselect-orders__empty">
				<p class="pepselect-orders__empty-text"><?php esc_html_e( 'You have not placed an order yet.', 'pepselect-child' ); ?></p>
				<a class="pepselect-btn pepselect-btn--solid" href="<?php echo esc_url( $pepselect_shop_url ); ?>"><?php esc_html_e( 'Browse compounds', 'pepselect-child' ); ?></a>
			</div>

		<?php else : ?>

			<?php
			$pepselect_render_order_row = static function ( $pepselect_order ) {
				if ( ! is_a( $pepselect_order, 'WC_Order' ) ) {
					return;
				}

				$pepselect_tracking = function_exists( 'pepselect_child_get_order_tracking' )
					? pepselect_child_get_order_tracking( $pepselect_order )
					: array( 'number' => '', 'carrier' => '', 'url' => '' );
				$pepselect_summary = function_exists( 'pepselect_oe_account_order_summary' )
					? pepselect_oe_account_order_summary( $pepselect_order, $pepselect_tracking )
					: array(
						'url'          => $pepselect_order->get_view_order_url(),
						'status_key'   => sanitize_html_class( $pepselect_order->get_status() ),
						'status_label' => wc_get_order_status_name( $pepselect_order->get_status() ),
					);
				$pepselect_date      = $pepselect_order->get_date_created();
				$pepselect_track_num = trim( (string) ( $pepselect_tracking['number'] ?? '' ) );
				$pepselect_track_url = function_exists( 'pepselect_child_account_tracking_url' )
					? pepselect_child_account_tracking_url( $pepselect_tracking )
					: (string) ( $pepselect_tracking['url'] ?? '' );
				?>
				<li class="pepselect-order-row">
					<a class="pepselect-order-row__target" href="<?php echo esc_url( $pepselect_summary['url'] ); ?>" aria-label="<?php echo esc_attr( sprintf( 'Review order %s', $pepselect_order->get_order_number() ) ); ?>"></a>
					<div class="pepselect-order-row__field pepselect-order-row__field--order">
						<span class="pepselect-order-row__label"><?php esc_html_e( 'Order', 'pepselect-child' ); ?></span>
						<span class="pepselect-order-row__value">#<?php echo esc_html( $pepselect_order->get_order_number() ); ?></span>
					</div>
					<div class="pepselect-order-row__field pepselect-order-row__field--placed">
						<span class="pepselect-order-row__label"><?php esc_html_e( 'Placed', 'pepselect-child' ); ?></span>
						<?php if ( $pepselect_date ) : ?>
							<time class="pepselect-order-row__value" datetime="<?php echo esc_attr( $pepselect_date->date( 'c' ) ); ?>"><?php echo esc_html( strtoupper( $pepselect_date->date_i18n( 'M j, Y' ) ) ); ?></time>
						<?php endif; ?>
					</div>
					<div class="pepselect-order-row__field pepselect-order-row__field--tracking">
						<span class="pepselect-order-row__label"><?php esc_html_e( 'Tracking', 'pepselect-child' ); ?></span>
						<?php if ( '' !== $pepselect_track_num && '' !== $pepselect_track_url ) : ?>
							<a class="pepselect-order-row__tracking-link" href="<?php echo esc_url( $pepselect_track_url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $pepselect_track_num ); ?><span aria-hidden="true">↗</span><span class="screen-reader-text"><?php esc_html_e( 'Open carrier tracking', 'pepselect-child' ); ?></span></a>
						<?php elseif ( '' !== $pepselect_track_num ) : ?>
							<span class="pepselect-order-row__value"><?php echo esc_html( $pepselect_track_num ); ?></span>
						<?php else : ?>
							<span class="pepselect-order-row__empty">—</span>
						<?php endif; ?>
					</div>
					<div class="pepselect-order-row__field pepselect-order-row__field--status">
						<span class="pepselect-order-row__label"><?php esc_html_e( 'Status', 'pepselect-child' ); ?></span>
						<span class="pepselect-order-row__status pepselect-order-row__status--<?php echo esc_attr( $pepselect_summary['status_key'] ); ?>"><span aria-hidden="true"></span><?php echo esc_html( $pepselect_summary['status_label'] ); ?></span>
					</div>
					<div class="pepselect-order-row__field pepselect-order-row__field--total">
						<span class="pepselect-order-row__label"><?php esc_html_e( 'Total', 'pepselect-child' ); ?></span>
						<span class="pepselect-order-row__value"><?php echo wp_kses_post( $pepselect_order->get_formatted_order_total() ); ?></span>
					</div>
					<span class="pepselect-order-row__arrow" aria-hidden="true">→</span>
				</li>
				<?php
			};
			$pepselect_recent_orders = array_slice( $pepselect_orders, 0, 5 );
			$pepselect_older_orders  = array_slice( $pepselect_orders, 5 );
			?>

			<ul class="pepselect-orders">
				<?php foreach ( $pepselect_recent_orders as $pepselect_order ) { $pepselect_render_order_row( $pepselect_order ); } ?>
			</ul>

			<?php if ( ! empty( $pepselect_older_orders ) ) : ?>
				<details class="pepselect-orders-archive">
					<summary><?php printf( esc_html( _n( 'Show %d older order', 'Show %d older orders', count( $pepselect_older_orders ), 'pepselect-child' ) ), esc_html( count( $pepselect_older_orders ) ) ); ?><span aria-hidden="true">⌄</span></summary>
					<ul class="pepselect-orders pepselect-orders--older">
						<?php foreach ( $pepselect_older_orders as $pepselect_order ) { $pepselect_render_order_row( $pepselect_order ); } ?>
					</ul>
				</details>
			<?php endif; ?>

		<?php endif; ?>
	</section>

</div>

<?php
	/**
	 * Preserve the native hook so plugins that inject dashboard content keep working.
	 */
	do_action( 'woocommerce_account_dashboard' );
?>

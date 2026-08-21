<?php
/**
 * My Account Dashboard (Pep Select custom) - single scrolling page.
 *
 * Replaces WooCommerce's dashboard intro and the four link rows with one
 * scrolling page of five cards: welcome + sign out, saved information, cash-back
 * summary in dollars, the referral link, and the customer's orders inline
 * (status, total, cash back earned, shipment tracking when a shipped order has
 * an Easyship note, and every line item with quantity and price - no click-through
 * needed).
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

	<?php if ( '' !== $pepselect_vanity_url ) : ?>
		<?php // Card 4: referral link. ?>
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

	<?php // Card 5: your orders, inline. ?>
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

			<ul class="pepselect-orders">
				<?php
				foreach ( $pepselect_orders as $pepselect_order ) :
					if ( ! is_a( $pepselect_order, 'WC_Order' ) ) {
						continue;
					}

					$pepselect_status      = $pepselect_order->get_status();
					$pepselect_status_name = wc_get_order_status_name( $pepselect_status );
					$pepselect_date        = $pepselect_order->get_date_created();
					$pepselect_order_id    = $pepselect_order->get_id();

					// Tracking: reuse the existing resolver. Show the number only when
					// one is resolved; render nothing otherwise (absence is correct).
					// The Easyship note's tracking link is a bare domain, so the number
					// is shown as plain text, never linked.
					$pepselect_tracking = function_exists( 'pepselect_child_get_order_tracking' )
						? pepselect_child_get_order_tracking( $pepselect_order )
						: array( 'number' => '', 'carrier' => '' );
					$pepselect_track_num     = isset( $pepselect_tracking['number'] ) ? $pepselect_tracking['number'] : '';
					$pepselect_track_carrier = isset( $pepselect_tracking['carrier'] ) ? $pepselect_tracking['carrier'] : '';

					// Cash back earned on this order, in dollars, from YITH's log.
					$pepselect_earned = isset( $pepselect_earned_by_order[ $pepselect_order_id ] ) ? (float) $pepselect_earned_by_order[ $pepselect_order_id ] : 0.0;
					?>
					<li class="pepselect-order">
						<div class="pepselect-order__head">
							<div class="pepselect-order__id">
								<span class="pepselect-order__number">#<?php echo esc_html( $pepselect_order->get_order_number() ); ?></span>
								<?php if ( $pepselect_date ) : ?>
									<time class="pepselect-order__date" datetime="<?php echo esc_attr( $pepselect_date->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $pepselect_date ) ); ?></time>
								<?php endif; ?>
							</div>
							<span class="pepselect-order__status pepselect-order__status--<?php echo esc_attr( $pepselect_status ); ?>"><?php echo esc_html( $pepselect_status_name ); ?></span>
						</div>

						<div class="pepselect-order__meta">
							<span class="pepselect-order__total"><?php echo wp_kses_post( $pepselect_order->get_formatted_order_total() ); ?></span>
							<?php if ( $pepselect_earned > 0 ) : ?>
								<span class="pepselect-order__earned">
									<?php
									/* translators: %s: cash back earned on this order, in dollars. */
									printf( esc_html__( '%s cash back', 'pepselect-child' ), esc_html( pepselect_child_format_dollars( $pepselect_earned ) ) );
									?>
								</span>
							<?php endif; ?>
						</div>

						<?php if ( '' !== $pepselect_track_num ) : ?>
							<p class="pepselect-order__tracking">
								<span class="pepselect-order__tracking-label">
									<?php
									if ( '' !== $pepselect_track_carrier ) {
										/* translators: %s: shipping carrier name. */
										printf( esc_html__( 'Tracking (%s)', 'pepselect-child' ), esc_html( $pepselect_track_carrier ) );
									} else {
										esc_html_e( 'Tracking', 'pepselect-child' );
									}
									?>
								</span>
								<span class="pepselect-order__tracking-num"><?php echo esc_html( $pepselect_track_num ); ?></span>
							</p>
						<?php endif; ?>

						<?php
						$pepselect_items = $pepselect_order->get_items();

						if ( ! empty( $pepselect_items ) ) :
							?>
							<ul class="pepselect-order__items">
								<?php foreach ( $pepselect_items as $pepselect_item ) : ?>
									<li class="pepselect-order__item">
										<span class="pepselect-order__item-name"><?php echo esc_html( $pepselect_item->get_name() ); ?></span>
										<span class="pepselect-order__item-qty">&times;<?php echo esc_html( $pepselect_item->get_quantity() ); ?></span>
										<span class="pepselect-order__item-price"><?php echo wp_kses_post( $pepselect_order->get_formatted_line_subtotal( $pepselect_item ) ); ?></span>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>

		<?php endif; ?>
	</section>

</div>

<?php
	/**
	 * Preserve the native hook so plugins that inject dashboard content keep working.
	 */
	do_action( 'woocommerce_account_dashboard' );
?>

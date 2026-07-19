<?php
/**
 * Cash back account page (Pep Select custom).
 *
 * Renders the customer's cash-back balance, how it works, and history, all in
 * dollars. Data comes from YITH via theme helpers (points x $0.01); YITH stays
 * the engine and auto-applies balances at checkout, so this page is display
 * only. No gamification (rank, daily-login labels) and no coupon sharing.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pepselect_cashback = function_exists( 'pepselect_child_get_cashback_display' ) ? pepselect_child_get_cashback_display() : array(
	'points'            => 0,
	'dollars'           => 0.0,
	'balance_formatted' => '$0.00',
);

$pepselect_history = function_exists( 'pepselect_child_get_cashback_history' ) ? pepselect_child_get_cashback_history( 20 ) : array();
?>
<div class="pepselect-cashback">
	<header class="pepselect-cashback__head">
		<h1 class="pepselect-account__title"><?php esc_html_e( 'Cash back', 'pepselect-child' ); ?></h1>
		<p class="pepselect-account__lead"><?php esc_html_e( 'Earn 3% back on every order. Your balance applies automatically at checkout.', 'pepselect-child' ); ?></p>
	</header>

	<div class="pepselect-cashback__balance">
		<span class="pepselect-cashback__balance-label"><?php esc_html_e( 'Available balance', 'pepselect-child' ); ?></span>
		<span class="pepselect-cashback__balance-value"><?php echo esc_html( $pepselect_cashback['balance_formatted'] ); ?></span>
		<span class="pepselect-cashback__balance-note"><?php esc_html_e( 'Applied to your order total at checkout.', 'pepselect-child' ); ?></span>
	</div>

	<section class="pepselect-cashback__how" aria-labelledby="pepselect-cashback-how-title">
		<h2 id="pepselect-cashback-how-title" class="pepselect-cashback__how-title"><?php esc_html_e( 'How cash back works', 'pepselect-child' ); ?></h2>
		<ol class="pepselect-cashback__steps">
			<li class="pepselect-cashback__step">
				<span class="pepselect-cashback__step-number" aria-hidden="true">01</span>
				<div>
					<span class="pepselect-cashback__step-title"><?php esc_html_e( 'Earn on every order', 'pepselect-child' ); ?></span>
					<span class="pepselect-cashback__step-note"><?php esc_html_e( 'You earn 3% back on each order, added once the order completes.', 'pepselect-child' ); ?></span>
				</div>
			</li>
			<li class="pepselect-cashback__step">
				<span class="pepselect-cashback__step-number" aria-hidden="true">02</span>
				<div>
					<span class="pepselect-cashback__step-title"><?php esc_html_e( 'It becomes a balance', 'pepselect-child' ); ?></span>
					<span class="pepselect-cashback__step-note"><?php esc_html_e( 'Your cash back builds up as a dollar balance in your account.', 'pepselect-child' ); ?></span>
				</div>
			</li>
			<li class="pepselect-cashback__step">
				<span class="pepselect-cashback__step-number" aria-hidden="true">03</span>
				<div>
					<span class="pepselect-cashback__step-title"><?php esc_html_e( 'It applies at checkout', 'pepselect-child' ); ?></span>
					<span class="pepselect-cashback__step-note"><?php esc_html_e( 'Once your balance reaches $5, it applies to your order total at checkout.', 'pepselect-child' ); ?></span>
				</div>
			</li>
		</ol>
	</section>

	<section class="pepselect-cashback__history" aria-labelledby="pepselect-cashback-history-title">
		<h2 id="pepselect-cashback-history-title" class="pepselect-cashback__history-title"><?php esc_html_e( 'Activity', 'pepselect-child' ); ?></h2>

		<?php if ( ! empty( $pepselect_history ) ) : ?>
			<ul class="pepselect-cashback__rows">
				<?php foreach ( $pepselect_history as $row ) : ?>
					<?php
					$is_credit = $row['points'] >= 0;
					$amount    = ( $is_credit ? '+' : '-' ) . wp_strip_all_tags( wc_price( abs( $row['dollars'] ) ) );
					?>
					<li class="pepselect-cashback__row">
						<span class="pepselect-cashback__row-main">
							<span class="pepselect-cashback__row-desc"><?php echo esc_html( $row['description'] ); ?></span>
							<span class="pepselect-cashback__row-date"><?php echo esc_html( $row['date'] ); ?></span>
						</span>
						<span class="pepselect-cashback__row-amount <?php echo $is_credit ? 'is-credit' : 'is-debit'; ?>"><?php echo esc_html( $amount ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php else : ?>
			<div class="pepselect-cashback__empty">
				<p><?php esc_html_e( 'No cash-back activity yet. Place an order to start earning.', 'pepselect-child' ); ?></p>
			</div>
		<?php endif; ?>
	</section>
</div>

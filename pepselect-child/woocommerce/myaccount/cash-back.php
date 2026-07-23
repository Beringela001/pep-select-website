<?php
/**
 * Cash back account page (Pep Select).
 *
 * Branded layout over YITH's own points engine. YITH's rendered my-points output
 * is captured and split into slots (history, manage/convert), and the referral
 * code/link is rendered from YITH's [ywpar_referral_link] shortcode, which is
 * separate from the my-points endpoint. Each block is placed and restyled here.
 * YITH keeps all logic and security (nonces, conversion, code generation,
 * referral links); nothing is reimplemented, and every value comes from YITH.
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

$pepselect_totals = function_exists( 'pepselect_child_get_cashback_totals' ) ? pepselect_child_get_cashback_totals() : array(
	'earned_formatted'  => '$0.00',
	'applied_formatted' => '$0.00',
);

// Capture YITH's native my-points output (points history and the
// convert-to-coupon form with its coupon table).
$pepselect_yith_output = '';
if ( has_action( 'woocommerce_account_my-points_endpoint' ) ) {
	ob_start();
	do_action( 'woocommerce_account_my-points_endpoint' );
	$pepselect_yith_output = ob_get_clean();
}

$pepselect_slots = function_exists( 'pepselect_child_split_yith_points_output' )
	? pepselect_child_split_yith_points_output( $pepselect_yith_output )
	: array(
		'referral' => '',
		'history'  => '',
		'manage'   => '',
		'rest'     => $pepselect_yith_output,
	);

// Referral code/link comes from YITH's own shortcode, which is not part of the
// my-points endpoint output.
$pepselect_referral_shortcode = shortcode_exists( 'ywpar_referral_link' );
$pepselect_referral_html      = $pepselect_referral_shortcode ? trim( do_shortcode( '[ywpar_referral_link]' ) ) : '';

if ( '' === $pepselect_referral_html && '' !== trim( (string) $pepselect_slots['referral'] ) ) {
	$pepselect_referral_html = $pepselect_slots['referral'];
}

$pepselect_steps = array(
	array(
		'title' => __( 'Earn on every order', 'pepselect-child' ),
		'note'  => __( '3% of every completed order comes back to you as cash back.', 'pepselect-child' ),
	),
	array(
		'title' => __( 'Bring a friend', 'pepselect-child' ),
		'note'  => __( 'Share your code. They save 10% on their first order, you get $15 once it completes.', 'pepselect-child' ),
	),
	array(
		'title' => __( 'Spend it at checkout', 'pepselect-child' ),
		'note'  => __( 'At $5 or more, turn your balance into a code and apply it to any order.', 'pepselect-child' ),
	),
	array(
		'title' => __( 'One balance', 'pepselect-child' ),
		'note'  => __( 'Cash back from your purchases and your referral rewards collect together in a single balance.', 'pepselect-child' ),
	),
);
?>
<div class="pepselect-cashback">
	<header class="pepselect-cashback__head">
		<h1 class="pepselect-account__title"><?php esc_html_e( 'Cash back', 'pepselect-child' ); ?></h1>
		<p class="pepselect-account__lead"><?php esc_html_e( 'Earn 3% back on every order. Turn your balance into a code and apply it at checkout.', 'pepselect-child' ); ?></p>
	</header>

	<div class="pepselect-cashback__stats">
		<div class="pepselect-stat pepselect-stat--primary">
			<span class="pepselect-stat__label"><?php esc_html_e( 'Available balance', 'pepselect-child' ); ?></span>
			<span class="pepselect-stat__value"><?php echo esc_html( $pepselect_cashback['balance_formatted'] ); ?></span>
			<span class="pepselect-stat__note"><?php esc_html_e( 'Redeem it for a code at checkout.', 'pepselect-child' ); ?></span>
		</div>
		<div class="pepselect-stat">
			<span class="pepselect-stat__label"><?php esc_html_e( 'Total earned', 'pepselect-child' ); ?></span>
			<span class="pepselect-stat__value"><?php echo esc_html( $pepselect_totals['earned_formatted'] ); ?></span>
			<span class="pepselect-stat__note"><?php esc_html_e( 'Across all your orders.', 'pepselect-child' ); ?></span>
		</div>
		<div class="pepselect-stat">
			<span class="pepselect-stat__label"><?php esc_html_e( 'Total applied', 'pepselect-child' ); ?></span>
			<span class="pepselect-stat__value"><?php echo esc_html( $pepselect_totals['applied_formatted'] ); ?></span>
			<span class="pepselect-stat__note"><?php esc_html_e( 'Already used at checkout.', 'pepselect-child' ); ?></span>
		</div>
	</div>

	<section class="pepselect-cashback__how" aria-labelledby="pepselect-cashback-how-title">
		<h2 id="pepselect-cashback-how-title" class="pepselect-cashback__how-title"><?php esc_html_e( 'How it works', 'pepselect-child' ); ?></h2>
		<ol class="pepselect-cashback__steps">
			<?php foreach ( $pepselect_steps as $pepselect_index => $pepselect_step ) : ?>
				<li class="pepselect-cashback__step">
					<span class="pepselect-cashback__step-number" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $pepselect_index + 1 ) ); ?></span>
					<div>
						<span class="pepselect-cashback__step-title"><?php echo esc_html( $pepselect_step['title'] ); ?></span>
						<span class="pepselect-cashback__step-note"><?php echo esc_html( $pepselect_step['note'] ); ?></span>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>
	</section>

	<?php if ( $pepselect_referral_shortcode || '' !== $pepselect_referral_html ) : ?>
		<section class="pepselect-cashback__referral pepselect-cashback__engine" aria-labelledby="pepselect-cashback-referral-title">
			<h2 id="pepselect-cashback-referral-title" class="pepselect-cashback__section-title"><?php esc_html_e( 'Refer a friend', 'pepselect-child' ); ?></h2>
			<p class="pepselect-cashback__section-lead"><?php esc_html_e( 'Share your code. They save 10% on their first order, and you earn $15 in cash back once it completes.', 'pepselect-child' ); ?></p>

			<?php if ( '' !== $pepselect_referral_html ) : ?>
				<div class="pepselect-cashback__referral-body" data-pepselect-referral>
					<?php echo $pepselect_referral_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- YITH's own shortcode markup, re-emitted verbatim. ?>
				</div>
			<?php else : ?>
				<?php
				$pepselect_desc  = function_exists( 'pepselect_child_describe_shortcode' ) ? pepselect_child_describe_shortcode( 'ywpar_referral_link' ) : array();
				$pepselect_state = function_exists( 'pepselect_child_referral_state' ) ? pepselect_child_referral_state() : array();
				?>
				<div class="pepselect-cashback__referral-empty">
					<p><?php esc_html_e( 'YITH\'s [ywpar_referral_link] shortcode is registered but returned no output for this account. Its callback is read below, straight from the plugin file on this install, so the exact condition it requires can be identified.', 'pepselect-child' ); ?></p>
					<pre class="pepselect-cashback__referral-dump"><?php
					echo esc_html( 'callback: ' . ( isset( $pepselect_desc['callback'] ) ? $pepselect_desc['callback'] : '?' ) . "\n" );
					echo esc_html( 'file: ' . ( isset( $pepselect_desc['file'] ) ? $pepselect_desc['file'] : '?' ) . "\n" );
					echo esc_html( 'lines: ' . ( isset( $pepselect_desc['lines'] ) ? $pepselect_desc['lines'] : '?' ) . "\n\n" );
					echo esc_html( "--- callback source ---\n" );
					echo esc_html( ! empty( $pepselect_desc['source'] ) ? $pepselect_desc['source'] : '(source unavailable)' );
					echo esc_html( "\n--- referral options / user meta ---\n" );

					if ( $pepselect_state ) {
						foreach ( $pepselect_state as $pepselect_key => $pepselect_value ) {
							echo esc_html( $pepselect_key . ' = ' . $pepselect_value . "\n" );
						}
					} else {
						echo esc_html( '(no referral options or user meta found)' );
					}
					?></pre>
				</div>
			<?php endif; ?>

			<div class="pepselect-cashback__mini-stats">
				<div class="pepselect-stat pepselect-stat--mini">
					<span class="pepselect-stat__label"><?php esc_html_e( 'Referral bonus', 'pepselect-child' ); ?></span>
					<span class="pepselect-stat__value"><?php echo esc_html( pepselect_child_format_dollars( 15 ) ); ?></span>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( '' !== trim( (string) $pepselect_slots['manage'] ) ) : ?>
		<section class="pepselect-cashback__manage pepselect-cashback__engine" aria-labelledby="pepselect-cashback-manage-title">
			<h2 id="pepselect-cashback-manage-title" class="pepselect-cashback__section-title"><?php esc_html_e( 'Turn your balance into a code', 'pepselect-child' ); ?></h2>
			<?php echo $pepselect_slots['manage']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- YITH's own convert form and coupon table. ?>
		</section>
	<?php endif; ?>

	<?php if ( '' !== trim( (string) $pepselect_slots['history'] ) ) : ?>
		<section class="pepselect-cashback__history pepselect-cashback__engine" aria-labelledby="pepselect-cashback-history-title">
			<h2 id="pepselect-cashback-history-title" class="pepselect-cashback__section-title"><?php esc_html_e( 'Cash back history', 'pepselect-child' ); ?></h2>
			<?php echo $pepselect_slots['history']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- YITH's own history table. ?>
		</section>
	<?php endif; ?>

	<?php if ( '' !== trim( (string) $pepselect_slots['rest'] ) ) : ?>
		<section class="pepselect-cashback__engine pepselect-cashback__rest" aria-label="<?php esc_attr_e( 'Points and coupon codes', 'pepselect-child' ); ?>">
			<?php echo $pepselect_slots['rest']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Remaining YITH markup. ?>
		</section>
	<?php endif; ?>
</div>

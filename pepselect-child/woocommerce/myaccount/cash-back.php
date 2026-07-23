<?php
/**
 * Cash back account page (Pep Select).
 *
 * Branded layout over YITH's own points engine. YITH's rendered my-points output
 * is captured and split into slots (referral, history, manage/convert) and each
 * block is placed and restyled here. YITH keeps all logic and security (nonces,
 * conversion, code generation, referral links); nothing is reimplemented, and
 * every value shown comes from YITH.
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

// Capture YITH's native my-points output (balance summary, referral block,
// points history, and the convert-to-coupon form with its coupon table).
$pepselect_yith_output = '';
if ( has_action( 'woocommerce_account_my-points_endpoint' ) ) {
	ob_start();
	do_action( 'woocommerce_account_my-points_endpoint' );
	$pepselect_yith_output = ob_get_clean();
}

/*
 * TEMPORARY DEBUG (0.16.0-beta.13-debug).
 *
 * Renders YITH's unmodified my-points output verbatim, plus its escaped source
 * and a shortcode/endpoint probe, so the real referral markup (if any) can be
 * identified. Delete this block and the constant to remove.
 */
if ( ! defined( 'PEPSELECT_CASHBACK_DEBUG' ) ) {
	define( 'PEPSELECT_CASHBACK_DEBUG', true );
}

if ( PEPSELECT_CASHBACK_DEBUG ) :
	$pepselect_probe_tags = array();

	if ( ! empty( $GLOBALS['shortcode_tags'] ) && is_array( $GLOBALS['shortcode_tags'] ) ) {
		foreach ( array_keys( $GLOBALS['shortcode_tags'] ) as $pepselect_tag ) {
			if ( preg_match( '/ywpar|refer|point|reward|affiliate|invite/i', $pepselect_tag ) ) {
				$pepselect_probe_tags[] = $pepselect_tag;
			}
		}
	}

	$pepselect_endpoints = array();

	if ( function_exists( 'WC' ) && WC()->query && method_exists( WC()->query, 'get_query_vars' ) ) {
		$pepselect_endpoints = array_keys( (array) WC()->query->get_query_vars() );
	}

	$pepselect_referral_actions = array();

	foreach ( array( 'woocommerce_account_my-points_endpoint', 'woocommerce_account_referral_endpoint', 'woocommerce_account_refer-a-friend_endpoint' ) as $pepselect_hook ) {
		$pepselect_referral_actions[ $pepselect_hook ] = has_action( $pepselect_hook ) ? 'registered' : 'not registered';
	}
	?>
	<div class="pepselect-debug" style="margin:0 0 40px;padding:20px;border:2px solid #B46A00;border-radius:12px;background:#FFF4DF;font-family:monospace;font-size:13px;color:#001D3A;">
		<h2 style="margin:0 0 6px;font-family:monospace;font-size:16px;color:#B46A00;">DEBUG — YITH raw output (temporary build)</h2>
		<p style="margin:0 0 18px;">Theme restyle bypassed below. Screenshot everything, including the source box.</p>

		<h3 style="margin:0 0 8px;font-size:14px;">1. Plugin / endpoint probe</h3>
		<pre style="margin:0 0 20px;padding:12px;background:#fff;border:1px solid #D7E1E9;border-radius:8px;white-space:pre-wrap;word-break:break-word;"><?php
		echo esc_html( 'YITH_YWPAR_VERSION: ' . ( defined( 'YITH_YWPAR_VERSION' ) ? YITH_YWPAR_VERSION : 'undefined' ) . "\n" );
		echo esc_html( 'YITH_WC_Points_Rewards(): ' . ( function_exists( 'YITH_WC_Points_Rewards' ) ? 'available' : 'missing' ) . "\n\n" );
		echo esc_html( "Shortcodes matching ywpar|refer|point|reward|affiliate|invite:\n" );
		echo esc_html( $pepselect_probe_tags ? '  ' . implode( "\n  ", $pepselect_probe_tags ) . "\n\n" : "  (none)\n\n" );
		echo esc_html( "My Account endpoints:\n  " . implode( "\n  ", $pepselect_endpoints ) . "\n\n" );
		echo esc_html( "Endpoint hooks:\n" );
		foreach ( $pepselect_referral_actions as $pepselect_hook => $pepselect_state ) {
			echo esc_html( '  ' . $pepselect_hook . ': ' . $pepselect_state . "\n" );
		}
		?></pre>

		<h3 style="margin:0 0 8px;font-size:14px;">2. YITH output rendered verbatim (no theme restyle)</h3>
		<div class="pepselect-debug__render" style="margin:0 0 20px;padding:16px;background:#fff;border:1px solid #D7E1E9;border-radius:8px;">
			<?php echo $pepselect_yith_output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Debug: YITH markup verbatim. ?>
		</div>

		<h3 style="margin:0 0 8px;font-size:14px;">3. YITH output source (<?php echo esc_html( (string) strlen( (string) $pepselect_yith_output ) ); ?> chars)</h3>
		<pre style="margin:0;padding:12px;max-height:520px;overflow:auto;background:#fff;border:1px solid #D7E1E9;border-radius:8px;white-space:pre-wrap;word-break:break-all;"><?php
		echo '' === trim( (string) $pepselect_yith_output )
			? esc_html( '(YITH rendered NOTHING on this endpoint)' )
			: esc_html( (string) $pepselect_yith_output );
		?></pre>
	</div>
	<?php
endif;

$pepselect_slots = function_exists( 'pepselect_child_split_yith_points_output' )
	? pepselect_child_split_yith_points_output( $pepselect_yith_output )
	: array(
		'referral' => '',
		'history'  => '',
		'manage'   => '',
		'rest'     => $pepselect_yith_output,
	);

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

	<?php if ( '' !== trim( (string) $pepselect_slots['referral'] ) ) : ?>
		<section class="pepselect-cashback__referral pepselect-cashback__engine" aria-labelledby="pepselect-cashback-referral-title">
			<h2 id="pepselect-cashback-referral-title" class="pepselect-cashback__section-title"><?php esc_html_e( 'Refer a friend', 'pepselect-child' ); ?></h2>
			<p class="pepselect-cashback__section-lead"><?php esc_html_e( 'Share your code. They get 10% off their first order, and you earn $15 in cash back once it completes.', 'pepselect-child' ); ?></p>

			<div class="pepselect-cashback__referral-body" data-pepselect-referral>
				<?php echo $pepselect_slots['referral']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- YITH's own markup, re-emitted verbatim. ?>
			</div>

			<div class="pepselect-cashback__mini-stats">
				<div class="pepselect-stat pepselect-stat--mini" data-pepselect-referral-count hidden>
					<span class="pepselect-stat__label"><?php esc_html_e( 'Friends referred', 'pepselect-child' ); ?></span>
					<span class="pepselect-stat__value" data-pepselect-value>&mdash;</span>
				</div>
				<div class="pepselect-stat pepselect-stat--mini" data-pepselect-referral-credit hidden>
					<span class="pepselect-stat__label"><?php esc_html_e( 'Credit from referrals', 'pepselect-child' ); ?></span>
					<span class="pepselect-stat__value" data-pepselect-value>&mdash;</span>
				</div>
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

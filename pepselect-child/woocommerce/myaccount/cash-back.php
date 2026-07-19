<?php
/**
 * Cash back account page (Pep Select).
 *
 * Keeps a branded balance card and "how it works" header, then renders YITH's
 * own points content below (points history, the convert-to-coupon form, and the
 * generated-coupon table with used/unused status). YITH owns all the logic and
 * security (nonces, conversion, code generation); this page only reskins it, so
 * the feature set stays whatever YITH provides and nothing is reimplemented.
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

// Capture YITH's native my-points output (balance summary + history tab +
// Manage Points tab with the convert form and coupon-code table).
$pepselect_yith_output = '';
if ( has_action( 'woocommerce_account_my-points_endpoint' ) ) {
	ob_start();
	do_action( 'woocommerce_account_my-points_endpoint' );
	$pepselect_yith_output = ob_get_clean();
}
?>
<div class="pepselect-cashback">
	<header class="pepselect-cashback__head">
		<h1 class="pepselect-account__title"><?php esc_html_e( 'Cash back', 'pepselect-child' ); ?></h1>
		<p class="pepselect-account__lead"><?php esc_html_e( 'Earn 3% back on every order. Turn your balance into a code and apply it at checkout.', 'pepselect-child' ); ?></p>
	</header>

	<div class="pepselect-cashback__balance">
		<span class="pepselect-cashback__balance-label"><?php esc_html_e( 'Available balance', 'pepselect-child' ); ?></span>
		<span class="pepselect-cashback__balance-value"><?php echo esc_html( $pepselect_cashback['balance_formatted'] ); ?></span>
		<span class="pepselect-cashback__balance-note"><?php esc_html_e( 'Redeem it for a code to use at checkout.', 'pepselect-child' ); ?></span>
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
					<span class="pepselect-cashback__step-title"><?php esc_html_e( 'Turn it into a code', 'pepselect-child' ); ?></span>
					<span class="pepselect-cashback__step-note"><?php esc_html_e( 'Once your balance reaches $5, open Manage Points below to create a code, then enter it in the discount field at checkout.', 'pepselect-child' ); ?></span>
				</div>
			</li>
		</ol>
	</section>

	<?php if ( '' !== trim( (string) $pepselect_yith_output ) ) : ?>
		<section class="pepselect-cashback__engine" aria-label="<?php esc_attr_e( 'Points and coupon codes', 'pepselect-child' ); ?>">
			<?php echo $pepselect_yith_output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</section>
	<?php endif; ?>
</div>

<?php
/**
 * Checkout and payment presentation (M7).
 *
 * Rewrites the pay-by-email-link explanation in Pep Select's voice, injects the
 * live order total so customers know the exact amount to enter into the Square
 * payment link, and repeats a short reminder on the order-received page. Also
 * labels the checkout coupon field and enqueues checkout styling.
 *
 * The underlying BACS gateway, its email flow, and Fluid Checkout are untouched;
 * this only changes presentation and copy.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build the reworded pay-by-email-link explanation.
 *
 * @param string $total_html Formatted order total (already escaped/marked-up).
 * @return string Safe HTML.
 */
function pepselect_child_payment_instructions_html( $total_html ) {
	$intro = esc_html__( 'Placing your order does not charge you. We email you a secure Square payment link to complete your purchase.', 'pepselect-child' );

	$exact = sprintf(
		/* translators: %s: formatted order total. */
		esc_html__( 'When you open the link, enter your order total exactly: %s. Payments that do not match your order total cannot be processed and will delay your order.', 'pepselect-child' ),
		'<strong class="pepselect-pay__amount">' . $total_html . '</strong>'
	);

	$email = esc_html__( 'The email arrives within a few minutes. If you do not see it, check your spam folder.', 'pepselect-child' );

	$hold = esc_html__( 'Orders are held for 90 minutes. Unpaid orders are released after that, and stock is not reserved until payment clears.', 'pepselect-child' );

	return '<div class="pepselect-pay">'
		. '<p class="pepselect-pay__intro">' . $intro . '</p>'
		. '<p class="pepselect-pay__exact">' . $exact . '</p>'
		. '<p class="pepselect-pay__line">' . $email . '</p>'
		. '<p class="pepselect-pay__line pepselect-pay__hold">' . $hold . '</p>'
		. '</div>';
}

/**
 * Replace the BACS gateway description on the checkout with our reworded copy,
 * injecting the live cart total.
 *
 * @param string $description Gateway description.
 * @param string $gateway_id  Gateway ID.
 * @return string
 */
function pepselect_child_filter_bacs_description( $description, $gateway_id ) {
	if ( 'bacs' !== $gateway_id ) {
		return $description;
	}

	$total_html = '';
	if ( function_exists( 'WC' ) && WC()->cart ) {
		$total_html = wp_strip_all_tags( wc_price( WC()->cart->get_total( 'edit' ) ) );
	}

	if ( '' === $total_html ) {
		// Fallback wording when the total is not available in this context.
		$total_html = esc_html__( 'the total shown in your order summary', 'pepselect-child' );
	}

	return pepselect_child_payment_instructions_html( $total_html );
}
add_filter( 'woocommerce_gateway_description', 'pepselect_child_filter_bacs_description', 20, 2 );

/**
 * Add a short exact-amount reminder on the order-received (thank-you) page,
 * using the actual order total.
 *
 * @param int $order_id Order ID.
 * @return void
 */
function pepselect_child_order_received_reminder( $order_id ) {
	$order = wc_get_order( $order_id );

	if ( ! $order || 'bacs' !== $order->get_payment_method() ) {
		return;
	}

	$total_html = wp_strip_all_tags( wc_price( $order->get_total() ) );
	?>
	<div class="pepselect-pay-reminder">
		<p>
			<?php
			printf(
				/* translators: %s: formatted order total. */
				esc_html__( 'Next step: open the Square payment link in your email and enter your order total exactly: %s. Your order is confirmed once payment clears.', 'pepselect-child' ),
				'<strong class="pepselect-pay__amount">' . esc_html( $total_html ) . '</strong>'
			);
			?>
		</p>
	</div>
	<?php
}
add_action( 'woocommerce_thankyou', 'pepselect_child_order_received_reminder', 5 );

/**
 * Enqueue checkout and cart presentation styles.
 *
 * @return void
 */
function pepselect_child_enqueue_checkout_assets() {
	if ( ! function_exists( 'is_checkout' ) ) {
		return;
	}

	if ( is_checkout() || is_cart() || ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'order-received' ) ) ) {
		wp_enqueue_style(
			'pepselect-child-checkout',
			get_stylesheet_directory_uri() . '/assets/css/checkout.css',
			array( 'pepselect-child-foundations' ),
			pepselect_child_asset_version( 'assets/css/checkout.css' )
		);

		wp_enqueue_script(
			'pepselect-child-cart-rewards',
			get_stylesheet_directory_uri() . '/assets/js/cart-rewards.js',
			array(),
			pepselect_child_asset_version( 'assets/js/cart-rewards.js' ),
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'pepselect_child_enqueue_checkout_assets', 40 );

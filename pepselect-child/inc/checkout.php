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
 * Strip the parenthetical delivery estimate from shipping-rate labels.
 *
 * The live-rates integration appends the carrier's transit estimate to the
 * method title itself (e.g. "USPS - Priority Mail (1-3 working days)") and does
 * not offer a setting to suppress it. This removes any parenthetical that
 * mentions "day"/"days" from the label wherever WooCommerce renders it, without
 * touching the rate, its cost, or the stored method title. Parentheticals that
 * are not delivery estimates (e.g. "(Signature required)") are left alone.
 *
 * @param string $label Shipping label text or HTML.
 * @return string
 */
function pepselect_child_strip_delivery_estimate( $label ) {
	if ( ! is_string( $label ) || '' === $label ) {
		return $label;
	}

	return preg_replace( '/\s*\((?=[^()]*\bdays?\b)[^()]*\)/i', '', $label );
}

/**
 * Cart/checkout shipping radio labels (also used by Fluid Checkout's chosen-
 * method review rows and the WooCommerce Blocks cart totals).
 *
 * @param string $label Full rate label, may include the price markup.
 * @return string
 */
function pepselect_child_filter_shipping_rate_label( $label ) {
	return pepselect_child_strip_delivery_estimate( $label );
}
add_filter( 'woocommerce_cart_shipping_method_full_label', 'pepselect_child_filter_shipping_rate_label', 9999 );

/**
 * Order-received page, My Account order views, and order emails read the method
 * title stored on the order, which still contains the estimate; strip it there
 * too so the display is consistent end to end.
 *
 * @param string $names Shipping method name(s) for display.
 * @return string
 */
function pepselect_child_filter_order_shipping_display( $names ) {
	return pepselect_child_strip_delivery_estimate( $names );
}
add_filter( 'woocommerce_order_shipping_method', 'pepselect_child_filter_order_shipping_display', 20 );

/**
 * The rate label at its source. WC_Shipping_Rate::get_label() applies this
 * filter, so hooking here covers renderers that build their own option markup
 * from the raw rate label instead of the cart full-label: Fluid Checkout's
 * shipping options and the WooCommerce Blocks cart both read it directly. The
 * late priority runs after any plugin that appends its estimate through the
 * same filter. The two filters above stay as belt-and-suspenders for
 * stored-order display.
 *
 * @param string                $label Rate label.
 * @param WC_Shipping_Rate|null $rate  Rate object, unused.
 * @return string
 */
function pepselect_child_filter_shipping_rate_source_label( $label, $rate = null ) {
	return pepselect_child_strip_delivery_estimate( $label );
}
add_filter( 'woocommerce_shipping_rate_label', 'pepselect_child_filter_shipping_rate_source_label', 9999, 2 );

/**
 * Drop the "Cart" page heading. Hello Elementor gates its page header on this
 * filter, so the markup is never printed; CSS in checkout.css covers any
 * builder-rendered title as a fallback.
 *
 * @param bool $show Whether to render the page title.
 * @return bool
 */
function pepselect_child_hide_cart_page_title( $show ) {
	if ( function_exists( 'is_cart' ) && is_cart() ) {
		return false;
	}

	return $show;
}
add_filter( 'hello_elementor_page_title', 'pepselect_child_hide_cart_page_title' );

/**
 * Append the compound strength as a pill to line-item names in the checkout
 * order summary. Reuses the homepage strength resolver (product_tag based) so
 * the badge matches the archive and homepage cards.
 *
 * Scoped to the live checkout only. The same filter also runs on the cart page
 * and the side-cart, where the pill span would render unstyled; is_checkout()
 * keeps it to the order review, and order-received is excluded (its line items
 * use woocommerce_order_item_name, not this filter, but the guard is explicit).
 * wp_kses_post, applied by the review-order template, preserves the span class.
 *
 * @param string $name          Product name markup.
 * @param array  $cart_item     Cart item.
 * @param string $cart_item_key Cart item key (unused).
 * @return string
 */
function pepselect_child_checkout_item_strength_pill( $name, $cart_item, $cart_item_key = '' ) {
	if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
		return $name;
	}

	if ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'order-received' ) ) {
		return $name;
	}

	if ( ! function_exists( 'pepselect_child_get_product_strength_label' ) ) {
		return $name;
	}

	$product = isset( $cart_item['data'] ) ? $cart_item['data'] : null;

	if ( ! is_a( $product, 'WC_Product' ) ) {
		return $name;
	}

	$strength = pepselect_child_get_product_strength_label( $product );

	if ( '' === $strength ) {
		return $name;
	}

	return $name . ' <span class="pepselect-order-strength">' . esc_html( $strength ) . '</span>';
}
add_filter( 'woocommerce_cart_item_name', 'pepselect_child_checkout_item_strength_pill', 20, 3 );

/**
 * Remove Fluid Checkout's progress bar from the DOM.
 *
 * Fluid renders it with FluidCheckout_Steps::output_checkout_progress_bar() on
 * woocommerce_before_checkout_form at priority 4. Earlier CSS only hid the
 * "STEP n OF 4" label, leaving .fc-progress-bar and its children in the
 * document; unhooking the renderer removes the markup itself. Runs on wp at a
 * priority after Fluid's own late hooks (it registers on wp at 100) and is
 * guarded so a missing or renamed class is a no-op rather than a fatal.
 *
 * @return void
 */
function pepselect_child_remove_fluid_progress_bar() {
	if ( ! class_exists( 'FluidCheckout_Steps' ) || ! method_exists( 'FluidCheckout_Steps', 'instance' ) ) {
		return;
	}

	$steps = FluidCheckout_Steps::instance();

	if ( ! is_object( $steps ) || ! method_exists( $steps, 'output_checkout_progress_bar' ) ) {
		return;
	}

	remove_action( 'woocommerce_before_checkout_form', array( $steps, 'output_checkout_progress_bar' ), 4 );
}
add_action( 'wp', 'pepselect_child_remove_fluid_progress_bar', 200 );

/**
 * Move Fluid's coupon code section into the order summary.
 *
 * Fluid renders coupons as a checkout substep inside the payment step (body
 * class has-fc-coupon-code--substep-before-payment). fc_coupon_code_displayed_as_substep
 * is Fluid's own documented filter for suppressing that substep, so the section
 * is not duplicated, and the same render method is called inside the summary
 * instead. This keeps Fluid's AJAX apply/remove endpoints untouched - the
 * section is field-based, not a nested <form>, so it is safe inside form.checkout.
 *
 * @return void
 */
function pepselect_child_render_coupon_in_summary() {
	if ( ! class_exists( 'FluidCheckout_CouponCodes' ) || ! method_exists( 'FluidCheckout_CouponCodes', 'instance' ) ) {
		return;
	}

	$coupons = FluidCheckout_CouponCodes::instance();

	if ( ! is_object( $coupons ) || ! method_exists( $coupons, 'output_section_coupon_codes_fields' ) ) {
		return;
	}

	if ( method_exists( $coupons, 'is_feature_enabled' ) && ! $coupons->is_feature_enabled() ) {
		return;
	}

	echo '<tr class="pepselect-summary-row pepselect-summary-row--coupon"><td colspan="2">';
	echo '<div class="pepselect-inner-card pepselect-inner-card--coupon">';

	$coupons->output_section_coupon_codes_fields();

	// Fluid normally prints these two through fc_before_substep_coupon_codes,
	// which only fires when its substep renders. Suppressing the substep took
	// them with it, leaving the section without the container Fluid's own script
	// reads: .fc-coupon-code-messages is where apply/remove errors are printed,
	// and .fc-step__substep-text-content--coupon-codes is both the applied-coupon
	// list and the target of Fluid's woocommerce_update_order_review_fragments
	// entry. Without them, removal still reaches the server but there is no
	// loading state, the applied list never refreshes, and any coupon error - such
	// as an individual-use conflict - is discarded silently.
	if ( method_exists( $coupons, 'output_coupon_codes_messages_container' ) ) {
		$coupons->output_coupon_codes_messages_container();
	}

	if ( method_exists( $coupons, 'output_substep_text_coupon_codes' ) ) {
		$coupons->output_substep_text_coupon_codes();
	}

	echo '</div></td></tr>';
}
add_action( 'woocommerce_review_order_after_cart_contents', 'pepselect_child_render_coupon_in_summary', 20 );
add_filter( 'fc_coupon_code_displayed_as_substep', '__return_false' );

/**
 * Render the payment section inside the order summary, directly above Place order.
 *
 * Fluid prints the payment box through fc_checkout_payment (priority 20) inside
 * the left-hand payment step. That action is unhooked below and the same core
 * function is called here instead, on fc_place_order at priority 5 - earlier
 * than Fluid own place-order output at 10 - so the box renders in the same
 * container as the button, immediately above it. The Square instruction panel is
 * the BACS gateway description and travels with the payment box unchanged.
 *
 * fc_place_order can fire for more than one place-order instance (main and
 * sidebar). A run-once guard is used rather than the $is_sidebar argument so the
 * box renders exactly once wherever the button actually is - relying on
 * $is_sidebar would risk rendering no payment box at all if the layout changes.
 *
 * @param string $step_id    Step id, unused.
 * @param bool   $is_sidebar Whether this is the sidebar instance, unused.
 * @return void
 */
function pepselect_child_render_payment_in_summary( $step_id = 'payment', $is_sidebar = false ) {
	static $rendered = false;

	if ( $rendered || ! function_exists( 'woocommerce_checkout_payment' ) ) {
		return;
	}

	$rendered = true;

	woocommerce_checkout_payment();
}
add_action( 'fc_place_order', 'pepselect_child_render_payment_in_summary', 5, 2 );

/**
 * Render the privacy / terms text with the consent block in the left column.
 *
 * Fluid prints checkout/terms.php through fc_checkout_place_order_terms next to
 * the button; that is unhooked below so the paragraph travels with the
 * acknowledgments instead. Priority 5 puts it above the Research Purpose field
 * (10) and the checkboxes (20), preserving the reading order.
 *
 * @return void
 */
function pepselect_child_render_consent_terms() {
	wc_get_template( 'checkout/terms.php' );
}
add_action( 'fc_checkout_payment', 'pepselect_child_render_consent_terms', 5 );

/**
 * Unhook Fluid payment and terms output so the two blocks swap columns.
 *
 * Runs on wp at a priority after Fluid own late hooks, and is guarded so a
 * missing or renamed class leaves the default layout intact rather than fatals.
 *
 * @return void
 */
function pepselect_child_swap_payment_and_consent() {
	remove_action( 'fc_checkout_payment', 'woocommerce_checkout_payment', 20 );

	if ( ! class_exists( 'FluidCheckout_Steps' ) || ! method_exists( 'FluidCheckout_Steps', 'instance' ) ) {
		return;
	}

	$steps = FluidCheckout_Steps::instance();

	if ( is_object( $steps ) && method_exists( $steps, 'output_checkout_place_order_terms' ) ) {
		remove_action( 'fc_checkout_place_order_terms', array( $steps, 'output_checkout_place_order_terms' ), 10 );
	}
}
add_action( 'wp', 'pepselect_child_swap_payment_and_consent', 200 );

/**
 * Enqueue checkout and cart presentation styles.
 *
 * @return void
 */
function pepselect_child_enqueue_checkout_assets() {
	if ( ! function_exists( 'is_checkout' ) ) {
		return;
	}

	// The empty cart renders the shared compound card, whose styles live in
	// cards.css. Without this the cart would be the one surface using that
	// partial with no stylesheet behind it.
	if ( is_cart() ) {
		wp_enqueue_style(
			'pepselect-child-cards',
			get_stylesheet_directory_uri() . '/assets/css/cards.css',
			array( 'pepselect-child-foundations' ),
			pepselect_child_asset_version( 'assets/css/cards.css' )
		);

		// The empty cart's cards use WooCommerce's own add-to-cart button.
		// WooCommerce registers this script but only enqueues it where it
		// expects loop buttons, which does not include the cart.
		wp_enqueue_script( 'wc-add-to-cart' );
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

		// Themed, dollar-framed redemption card in the order summary (M12-6).
		// jQuery is a dependency so it can re-run on updated_checkout.
		wp_enqueue_script(
			'pepselect-child-checkout-redemption',
			get_stylesheet_directory_uri() . '/assets/js/checkout-redemption.js',
			array( 'jquery' ),
			pepselect_child_asset_version( 'assets/js/checkout-redemption.js' ),
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'pepselect_child_enqueue_checkout_assets', 40 );

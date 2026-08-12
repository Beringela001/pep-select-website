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

	// The mockup splits the first sentence into a bold headline and the sentence
	// that follows it, and boxes the exact-amount instruction. The wording is
	// untouched - every one of the four parts is the live string, verbatim.
	$intro_parts = explode( '. ', $intro, 2 );
	$headline = isset( $intro_parts[0] ) ? $intro_parts[0] . '.' : $intro;
	$intro_rest = isset( $intro_parts[1] ) ? $intro_parts[1] : '';

	return '<div class="pepselect-pay">'
		. '<div class="pepselect-pay__hd">' . $headline . '</div>'
		. ( '' !== $intro_rest ? '<p class="pepselect-pay__intro">' . $intro_rest . '</p>' : '' )
		. '<div class="pepselect-pay__exact">' . $exact . '</div>'
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

	echo '<tr class="pepselect-summary-row pepselect-summary-row--coupon"><td colspan="2" class="pepselect-panel-cell">';
	echo '<div class="pepselect-inner-card pepselect-inner-card--coupon">';
	echo '<div class="pepselect-card-label">' . esc_html__( 'DISCOUNT CODE', 'pepselect-child' ) . '</div>';

	// Expanded, with no toggle handle: Fluid's own expansible-section arguments
	// carry the initial state, and passing $output_handle = false suppresses the
	// "Add coupon code" link entirely, so the field and its Apply button render
	// open in the panel.
	add_filter( 'fc_coupon_code_field_placeholder', 'pepselect_child_coupon_placeholder' );
	$coupons->output_section_coupon_codes_fields( array(), array( 'initial_state' => 'expanded' ), false );
	remove_filter( 'fc_coupon_code_field_placeholder', 'pepselect_child_coupon_placeholder' );

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
add_action( 'woocommerce_review_order_after_cart_contents', 'pepselect_child_render_coupon_in_summary', 10 );
add_filter( 'fc_coupon_code_displayed_as_substep', '__return_false' );

// Belt and braces for the expanded state: Fluid reads this filter when building
// the expansible section's initial state.
add_filter( 'fc_coupon_code_field_initially_expanded', '__return_true' );

// Panel heading, per the approved mockup.
add_filter( 'fc_order_review_title', 'pepselect_child_order_review_title' );

/**
 * Panel heading text.
 *
 * @param string $title Default title.
 * @return string
 */
function pepselect_child_order_review_title( $title ) {
	return __( 'Your Order', 'pepselect-child' );
}

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

	// Close the .pay wrapper opened with the payment heading. The place order
	// button is printed by Fluid immediately after this, inside the same
	// container, which is where the mockup has it.
	echo '</div>';
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

	// Notices move into the order summary (see above).
	remove_action( 'woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 10 );

	if ( ! class_exists( 'FluidCheckout_Steps' ) || ! method_exists( 'FluidCheckout_Steps', 'instance' ) ) {
		return;
	}

	$steps = FluidCheckout_Steps::instance();

	if ( is_object( $steps ) && method_exists( $steps, 'output_checkout_place_order_terms' ) ) {
		remove_action( 'fc_checkout_place_order_terms', array( $steps, 'output_checkout_place_order_terms' ), 10 );
	}

	// Fluid's inline quantity stepper in the order summary. Unhooked through its
	// own action rather than hidden, so the control is never rendered at all.
	// Quantities remain editable via Edit cart.
	if ( is_object( $steps ) && method_exists( $steps, 'output_order_summary_cart_item_quantity' ) ) {
		remove_action( 'fc_order_summary_cart_item_details', array( $steps, 'output_order_summary_cart_item_quantity' ), 90 );
	}
}
add_action( 'wp', 'pepselect_child_swap_payment_and_consent', 200 );

/**
 * Give the relocated payment section a heading in the summary panel.
 *
 * The panel's existing convention is a single h3.fc-checkout-order-review-title
 * ("Order summary"), so payment reuses that class and the string Fluid already
 * used for the substep it replaces. Priority 4 puts it above the payment box
 * rendered at 5. Run-once guarded for the same reason as the payment box.
 *
 * @param string $step_id    Step id, unused.
 * @param bool   $is_sidebar Sidebar flag, unused.
 * @return void
 */
function pepselect_child_render_payment_heading( $step_id = 'payment', $is_sidebar = false ) {
	static $rendered = false;

	if ( $rendered ) {
		return;
	}

	$rendered = true;

	// .pay in the mockup carries the divider and the top spacing; .paylab carries
	// only its own type and a 12px bottom margin. Kept as separate elements so
	// each maps 1:1 to one mockup class.
	echo '<div class="pepselect-pay-section">';
	echo '<div class="pepselect-payment-title">' . esc_html__( 'PAYMENT', 'pepselect-child' ) . '</div>';
}
add_action( 'fc_place_order', 'pepselect_child_render_payment_heading', 4, 2 );

/**
 * Drop the now-orphaned "Payment method" substep title in the left column.
 *
 * The payment box moved to the summary panel, so that heading labelled nothing;
 * the block below it carries its own Acknowledgments heading. Fluid supports a
 * null substep title (it uses one itself when the coupon section title is
 * disabled), so the substep still registers and the step structure is intact.
 *
 * @param array $args Substep registration arguments.
 * @return array
 */
function pepselect_child_clear_payment_substep_title( $args ) {
	if ( isset( $args['substep_id'] ) && 'payment' === $args['substep_id'] ) {
		$args['substep_title'] = null;
	}

	return $args;
}
add_filter( 'fc_register_checkout_substep_args', 'pepselect_child_clear_payment_substep_title' );

/**
 * Render checkout notices inside the order summary, above the coupon block.
 *
 * WooCommerce prints them full width at the top of the page through
 * woocommerce_before_checkout_form (priority 10), far from the controls that
 * produce them. That action is unhooked in the swap callback below and the same
 * core function is called here at priority 15 - after the BAC row (10) and
 * before the coupon row (20). Applies to every cart-level checkout notice, so
 * the reward-applied, coupon-applied and coupon-removed messages all behave the
 * same way.
 *
 * @return void
 */
function pepselect_child_render_notices_in_summary() {
	if ( ! function_exists( 'woocommerce_output_all_notices' ) ) {
		return;
	}

	// Render nothing at all when there is no notice. The wrapper WooCommerce
	// prints is not empty even with no messages, so a :empty CSS rule cannot
	// collapse it, and the row was adding 12px between the line items and the
	// discount card.
	if ( ! function_exists( 'wc_notice_count' ) || 1 > wc_notice_count() ) {
		return;
	}

	echo '<tr class="pepselect-summary-row pepselect-summary-row--notices"><td colspan="2">';
	echo '<div class="pepselect-summary-notices">';
	woocommerce_output_all_notices();
	echo '</div></td></tr>';
}
add_action( 'woocommerce_review_order_after_cart_contents', 'pepselect_child_render_notices_in_summary', 5 );

/**
 * Make the applied-coupon Remove control unable to navigate.
 *
 * WooCommerce renders it as a real link to ?remove_coupon=<code>, and Fluid's
 * coupon script is what cancels that default. The script is enqueued in the
 * footer, so a click landing before it binds follows the href instead - the
 * customer leaves checkout with the discount still applied. Rewriting the href
 * to "#" removes the only navigable path; removal itself is unchanged and still
 * runs through Fluid's handler.
 *
 * Trade-off, stated deliberately: this drops WooCommerce's no-JS removal
 * fallback. That fallback is already academic here - Fluid, the acknowledgment
 * validation and the redemption card all require JavaScript - and a click that
 * does nothing is strictly better than one that navigates away with the
 * discount still on the cart.
 *
 * @param string $html Coupon HTML.
 * @return string
 */
function pepselect_child_neutralise_coupon_remove_href( $html ) {
	if ( ! is_string( $html ) || '' === $html ) {
		return $html;
	}

	return preg_replace(
		'/(<a\b[^>]*class="[^"]*\bwoocommerce-remove-coupon\b[^"]*"[^>]*)\shref="[^"]*"/i',
		'$1 href="#"',
		preg_replace(
			'/(<a\b[^>]*)\shref="[^"]*"([^>]*class="[^"]*\bwoocommerce-remove-coupon\b)/i',
			'$1 href="#"$2',
			$html
		)
	);
}
add_filter( 'woocommerce_cart_totals_coupon_html', 'pepselect_child_neutralise_coupon_remove_href', 20 );

/**
 * Remove YITH's redemption form from the cart page only.
 *
 * On /cart/ YITH prints form.ywpar_apply_discounts inside its own block,
 * wp-block-yith-par-message-reward-cart, above the cash-back pill. Verified on
 * production that this block contains only the nonces and that form - not the
 * "you'll earn" message - so dropping it on the cart removes the duplicate
 * redemption UI and leaves the pill untouched. Checkout is unaffected: the
 * filter is gated on is_cart(), so the redemption card there still works.
 *
 * Done through core's render_block filter rather than by editing YITH.
 *
 * @param string $content Rendered block HTML.
 * @param array  $block   Parsed block.
 * @return string
 */
function pepselect_child_hide_yith_reward_block_on_cart( $content, $block ) {
	if ( ! function_exists( 'is_cart' ) || ! is_cart() ) {
		return $content;
	}

	$name = isset( $block['blockName'] ) ? (string) $block['blockName'] : '';

	if ( '' !== $name && false !== strpos( $name, 'yith-par-message-reward-cart' ) ) {
		return '';
	}

	if ( is_string( $content ) && false !== strpos( $content, 'wp-block-yith-par-message-reward-cart' ) ) {
		return '';
	}

	return $content;
}
add_filter( 'render_block', 'pepselect_child_hide_yith_reward_block_on_cart', 20, 2 );

/**
 * Coupon field placeholder, per the approved mockup.
 *
 * @param string $placeholder Default placeholder.
 * @return string
 */
function pepselect_child_coupon_placeholder( $placeholder ) {
	return __( 'ENTER DISCOUNT CODE', 'pepselect-child' );
}

/**
 * Return the applied coupons split into cash back and ordinary discounts.
 *
 * YITH implements a redemption as a coupon, so the two are told apart by code
 * prefix rather than by asking YITH.
 *
 * @return array<string,array<int,WC_Coupon>>
 */
function pepselect_child_get_applied_coupon_groups() {
	$groups = array( 'cashback' => array(), 'discount' => array() );

	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return $groups;
	}

	foreach ( WC()->cart->get_coupons() as $code => $coupon ) {
		$key = ( 0 === strpos( (string) $code, 'ywpar' ) ) ? 'cashback' : 'discount';
		$groups[ $key ][ $code ] = $coupon;
	}

	return $groups;
}

/**
 * Formatted discount amount for one applied coupon.
 *
 * @param string $code Coupon code.
 * @return string
 */
function pepselect_child_coupon_amount_html( $code ) {
	$amount = WC()->cart->get_coupon_discount_amount( $code, WC()->cart->display_cart_ex_tax );

	return wc_price( $amount );
}

/**
 * Render applied coupons and cash back as removable pills.
 *
 * One rule across the panel: anything applied is a pill with an x. The x reuses
 * WooCommerce's own remove control - same class and data-coupon attribute - so
 * the removal path already working on Live handles it, and the href is the "#"
 * this theme already rewrites so a click can never navigate.
 *
 * @return void
 */
function pepselect_child_render_applied_pills() {
	$groups = pepselect_child_get_applied_coupon_groups();

	if ( empty( $groups['discount'] ) && empty( $groups['cashback'] ) ) {
		return;
	}

	echo '<tr class="pepselect-summary-row pepselect-summary-row--pills"><td colspan="2" class="pepselect-panel-cell">';

	foreach ( $groups['discount'] as $code => $coupon ) {
		$detail = '';

		if ( is_a( $coupon, 'WC_Coupon' ) && 'percent' === $coupon->get_discount_type() ) {
			/* translators: %s: coupon percentage. */
			$detail = sprintf( __( '%s%% off', 'pepselect-child' ), wc_format_localized_decimal( $coupon->get_amount() ) ) . ' &minus; ';
		}

		echo '<span class="pepselect-applied">';
		echo '<b>' . esc_html( strtoupper( $code ) ) . '</b>';
		echo '<span class="pepselect-applied__det">' . wp_kses_post( $detail ) . wp_kses_post( pepselect_child_coupon_amount_html( $code ) ) . '</span>';
		echo '<a href="#" class="woocommerce-remove-coupon pepselect-applied__x" data-coupon="' . esc_attr( $code ) . '" aria-label="' . esc_attr( sprintf( __( 'Remove %s', 'pepselect-child' ), $code ) ) . '">&times;</a>';
		echo '</span>';
	}

	foreach ( $groups['cashback'] as $code => $coupon ) {
		echo '<span class="pepselect-applied">';
		echo '<b>' . esc_html__( 'CASH BACK', 'pepselect-child' ) . '</b>';
		echo '<span class="pepselect-applied__det">&minus;' . wp_kses_post( pepselect_child_coupon_amount_html( $code ) ) . ' ' . esc_html__( 'applied', 'pepselect-child' ) . '</span>';
		echo '<a href="#" class="woocommerce-remove-coupon pepselect-applied__x" data-coupon="' . esc_attr( $code ) . '" aria-label="' . esc_attr__( 'Remove cash back', 'pepselect-child' ) . '">&times;</a>';
		echo '</span>';
	}

	echo '</td></tr>';
}
add_action( 'woocommerce_review_order_after_cart_contents', 'pepselect_child_render_applied_pills', 20 );

/**
 * Empty row the redemption card is rendered into.
 *
 * The card is built client-side from YITH's own form, so the slot is placed
 * here in document order - between the applied pills and the BAC card, as the
 * mockup has it - rather than being injected next to the totals.
 *
 * @return void
 */
function pepselect_child_render_redeem_slot() {
	$groups = pepselect_child_get_applied_coupon_groups();

	// While cash back is applied the card is replaced by its pill.
	if ( ! empty( $groups['cashback'] ) ) {
		return;
	}

	echo '<tr class="pepselect-summary-row pep-redeem-slot"><td colspan="2" class="pepselect-panel-cell"></td></tr>';
}
add_action( 'woocommerce_review_order_after_cart_contents', 'pepselect_child_render_redeem_slot', 25 );

/**
 * Render the order totals as a flat list, replacing the review table's tfoot.
 *
 * The tfoot rows are suppressed in CSS in both tables Fluid renders, so exactly
 * one visible set of totals remains and this list owns its own spacing. Because
 * the list is plain divs inside a single cell, Fluid's boxed-template cell
 * padding - which is declared !important behind an id selector - has to be
 * beaten once, on that one cell, and never again inside it.
 *
 * Row order follows WooCommerce's own semantics: Subtotal is the real cart
 * subtotal, with discounts below it. The mockup shows the discount above a
 * reduced subtotal; matching that literally would mean printing a subtotal
 * WooCommerce never calculated, so the values stay authoritative and only the
 * order differs.
 *
 * @return void
 */
function pepselect_child_render_summary_totals() {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return;
	}

	$cart = WC()->cart;
	$groups = pepselect_child_get_applied_coupon_groups();

	echo '<tr class="pepselect-summary-row pepselect-summary-row--totals"><td colspan="2" class="pepselect-panel-cell">';
	echo '<div class="pepselect-totals">';

	// Row order is the mockup's, exactly: discount, subtotal, cash back,
	// shipping, tax, total. The VALUES are live WooCommerce data dropped into
	// those slots - the subtotal remains WooCommerce's own cart subtotal and is
	// not recomputed to match the mockup's illustrative figure.
	foreach ( $groups['discount'] as $code => $coupon ) {
		/* translators: %s: coupon code. */
		$label = sprintf( __( 'Discount (%s)', 'pepselect-child' ), strtoupper( $code ) );
		echo '<div class="pepselect-totals__row pepselect-totals__row--credit"><span>' . esc_html( $label ) . '</span><span>&minus;' . wp_kses_post( pepselect_child_coupon_amount_html( $code ) ) . '</span></div>';
	}

	echo '<div class="pepselect-totals__row"><span>' . esc_html__( 'Subtotal', 'pepselect-child' ) . '</span><span>' . wp_kses_post( $cart->get_cart_subtotal() ) . '</span></div>';

	foreach ( $groups['cashback'] as $code => $coupon ) {
		echo '<div class="pepselect-totals__row pepselect-totals__row--credit"><span>' . esc_html__( 'Cash back', 'pepselect-child' ) . '</span><span>&minus;' . wp_kses_post( pepselect_child_coupon_amount_html( $code ) ) . '</span></div>';
	}

	if ( $cart->needs_shipping() && $cart->show_shipping() ) {
		echo '<div class="pepselect-totals__row"><span>' . esc_html__( 'Shipping', 'pepselect-child' ) . '</span><span>' . wp_kses_post( wc_price( $cart->get_shipping_total() ) ) . '</span></div>';
	}

	if ( wc_tax_enabled() && 0 < count( $cart->get_tax_totals() ) ) {
		foreach ( $cart->get_tax_totals() as $tax ) {
			echo '<div class="pepselect-totals__row"><span>' . esc_html( $tax->label ) . '</span><span>' . wp_kses_post( $tax->formatted_amount ) . '</span></div>';
		}
	}

	echo '<div class="pepselect-totals__row pepselect-totals__row--total"><span>' . esc_html__( 'Total', 'pepselect-child' ) . '</span><span>' . wp_kses_post( $cart->get_total() ) . '</span></div>';

	echo '</div></td></tr>';
}
add_action( 'woocommerce_review_order_after_cart_contents', 'pepselect_child_render_summary_totals', 40 );

/**
 * Render the quantity line as its own element inside the summary line item.
 *
 * Previously this was appended to the product name through
 * woocommerce_cart_item_name, which put it INSIDE .cart-item__name. A span
 * nested in that element had nowhere to wrap to and overflowed its parent,
 * which is what made it sit on top of the discount card. Fluid emits the parts
 * of a summary line item through fc_order_summary_cart_item_details - product
 * name at 10, unit price at 30, meta at 40, its own quantity stepper at 90 -
 * so the line is rendered there instead, at 95, as a sibling of the others. It
 * therefore participates in normal flow and forms its own row.
 *
 * Fluid's stepper at priority 90 is unhooked (see the swap callback below), so
 * this is the only quantity control in the panel; quantities stay editable
 * through Edit cart, which opens the cart page.
 *
 * @param array  $cart_item     Cart item.
 * @param string $cart_item_key Cart item key.
 * @param mixed  $product       Product, unused.
 * @return void
 */
function pepselect_child_render_summary_item_qty( $cart_item = array(), $cart_item_key = '', $product = null ) {
	$quantity = isset( $cart_item['quantity'] ) ? (int) $cart_item['quantity'] : 0;

	if ( 1 > $quantity ) {
		return;
	}

	echo '<div class="cart-item__element pepselect-qty">';
	/* translators: %d: item quantity. */
	echo esc_html( sprintf( __( 'Qty %d', 'pepselect-child' ), $quantity ) );

	if ( '' !== $cart_item_key && function_exists( 'wc_get_cart_remove_url' ) ) {
		echo '<a href="' . esc_url( wc_get_cart_remove_url( $cart_item_key ) ) . '" class="pepselect-qty__remove" aria-label="' . esc_attr__( 'Remove this item', 'pepselect-child' ) . '">' . esc_html__( 'Remove', 'pepselect-child' ) . '</a>';
	}

	echo '</div>';
}
add_action( 'fc_order_summary_cart_item_details', 'pepselect_child_render_summary_item_qty', 95, 3 );

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

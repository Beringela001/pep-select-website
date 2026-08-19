<?php
/**
 * Single compound page presentation (WEB-2E).
 *
 * Replaces the WooCommerce long-description output with the coded compound
 * block while leaving all commerce renderers (add to cart, quantity
 * discounts, points messaging, verification, gallery) in place. The COA
 * history carousel keeps its position: it renders through the plugin's
 * [pepselect_product_coa_carousel] shortcode after the summary, exactly
 * where the legacy template placed it.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue product styles on single product pages.
 *
 * @return void
 */
function pepselect_child_enqueue_product_assets() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}

	if ( function_exists( 'pepselect_child_is_elementor_editor_request' ) && pepselect_child_is_elementor_editor_request() ) {
		return;
	}

	wp_enqueue_style(
		'pepselect-child-cards',
		get_stylesheet_directory_uri() . '/assets/css/cards.css',
		array( 'pepselect-child-foundations' ),
		pepselect_child_asset_version( 'assets/css/cards.css' )
	);

	wp_enqueue_script(
		'pepselect-child-cards-js',
		get_stylesheet_directory_uri() . '/assets/js/cards.js',
		array(),
		pepselect_child_asset_version( 'assets/js/cards.js' ),
		true
	);

	wp_enqueue_style(
		'pepselect-child-product',
		get_stylesheet_directory_uri() . '/assets/css/product.css',
		array( 'pepselect-child-foundations' ),
		pepselect_child_asset_version( 'assets/css/product.css' )
	);

	wp_enqueue_script(
		'pepselect-child-product',
		get_stylesheet_directory_uri() . '/assets/js/product.js',
		array(),
		pepselect_child_asset_version( 'assets/js/product.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'pepselect_child_enqueue_product_assets', 40 );

/**
 * Add a body class for single product styling scope.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function pepselect_child_product_body_class( $classes ) {
	if ( function_exists( 'is_product' ) && is_product() ) {
		$classes[] = 'pepselect-single-compound';
	}

	return $classes;
}
add_filter( 'body_class', 'pepselect_child_product_body_class' );

/**
 * Remove default WooCommerce after-summary output. The coded template
 * renders the description, testing history, and related grid directly, in
 * the COA-consistent card layout.
 *
 * @return void
 */
function pepselect_child_swap_product_description() {
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
}
add_action( 'wp', 'pepselect_child_swap_product_description' );

/**
 * Report whether the current request is a coded single-product view.
 *
 * @return bool
 */
function pepselect_child_is_single_compound_request() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return false;
	}

	if ( function_exists( 'pepselect_child_is_elementor_editor_request' ) && pepselect_child_is_elementor_editor_request() ) {
		return false;
	}

	return true;
}

/**
 * Seize the single-product template so the legacy Elementor Theme Builder
 * single-product template no longer renders these pages. The late priority
 * mirrors the archive and homepage mechanism.
 *
 * @param string $template Resolved template.
 * @return string
 */
function pepselect_child_single_compound_template( $template ) {
	if ( ! pepselect_child_is_single_compound_request() ) {
		return $template;
	}

	$coded = get_stylesheet_directory() . '/templates/single-compound.php';

	return file_exists( $coded ) ? $coded : $template;
}
add_filter( 'template_include', 'pepselect_child_single_compound_template', 99 );

/**
 * Prevent Elementor's Theme Builder from taking over single-product output.
 *
 * @param bool $do_override Whether Elementor should override the location.
 * @param string $location  Theme Builder location.
 * @return bool
 */
function pepselect_child_block_elementor_single_product( $do_override, $location = '' ) {
	if ( 'single' === $location && pepselect_child_is_single_compound_request() ) {
		return false;
	}

	return $do_override;
}
add_filter( 'elementor/theme/need_override_location', 'pepselect_child_block_elementor_single_product', 10, 2 );



/**
 * Trim clutter from the product summary per M4 direction: remove the short
 * description ("High-purity research peptide") above the price and the SKU
 * and category/tag meta below add to cart. Commerce logic is untouched.
 *
 * @return void
 */
function pepselect_child_trim_product_summary() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}

	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
}
add_action( 'wp', 'pepselect_child_trim_product_summary' );

/**
 * Dilution notice (WEB M10).
 *
 * Renders once per compound page, directly below the buy card's action row:
 * after add to cart on a purchasable compound, and after the back-in-stock
 * notify form when the compound is out of stock. The notice carries binding
 * refund-policy language, so it must never be missing from a compound page and
 * never printed twice.
 *
 * @return void
 */
function pepselect_child_render_dilution_notice() {
	static $rendered = false;

	if ( $rendered ) {
		return;
	}

	$rendered = true;
	?>
	<div class="pepselect-dilution-notice">
		<p class="pepselect-dilution-notice__label"><?php esc_html_e( 'Dilution notice', 'pepselect-child' ); ?></p>
		<p class="pepselect-dilution-notice__body"><?php esc_html_e( 'If a compound turns cloudy after it is reconstituted, the cause is almost always the reconstitution solution rather than the compound itself, and non-laboratory-grade solutions are the usual culprit. For this reason, cloudiness cannot be accepted as grounds for a refund unless the reconstitution was done using a laboratory-grade reconstitution solution.', 'pepselect-child' ); ?></p>
	</div>
	<?php
}

/**
 * Resolve the summary priority that puts the notice below the buy card's
 * action row.
 *
 * Add to cart runs at 30, so 35 clears it on an in-stock compound. The
 * back-in-stock notify form that replaces add to cart when a compound is out
 * of stock is printed by the notifier plugin at a priority the theme does not
 * own and the store can change from the plugin's settings, so on an
 * out-of-stock compound the registered callbacks are inspected and the notice
 * is placed after the last notifier callback instead of at a priority merely
 * assumed to be later. The summary buffer at 999 is left as the outer bound.
 *
 * @return int
 */
function pepselect_child_dilution_notice_priority() {
	global $wp_filter;

	$priority = 35;
	$product  = function_exists( 'wc_get_product' ) ? wc_get_product( get_queried_object_id() ) : null;

	if ( ! is_a( $product, 'WC_Product' ) || $product->is_in_stock() ) {
		return $priority;
	}

	if ( ! isset( $wp_filter['woocommerce_single_product_summary']->callbacks ) ) {
		return $priority;
	}

	foreach ( $wp_filter['woocommerce_single_product_summary']->callbacks as $registered => $callbacks ) {
		$registered = (int) $registered;

		if ( $registered <= $priority || $registered >= 999 ) {
			continue;
		}

		foreach ( $callbacks as $callback ) {
			$function = isset( $callback['function'] ) ? $callback['function'] : null;
			$name     = '';

			if ( is_string( $function ) ) {
				$name = $function;
			} elseif ( is_array( $function ) && isset( $function[0], $function[1] ) && is_string( $function[1] ) ) {
				$name = ( is_object( $function[0] ) ? get_class( $function[0] ) : (string) $function[0] ) . '::' . $function[1];
			}

			if ( '' !== $name && preg_match( '/cwg|instock/i', $name ) ) {
				$priority = $registered + 1;
			}
		}
	}

	return $priority;
}

/**
 * Register the dilution notice once the query is resolved, so the priority can
 * be chosen from the current compound's stock state and the callbacks actually
 * registered on the summary.
 *
 * @return void
 */
function pepselect_child_register_dilution_notice() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}

	// The promotion-pill formatter buffers the summary through priority 999.
	// Render these complete components after that buffer closes so neither can
	// be mistaken for plugin promotion copy while retaining their requested
	// order immediately after the purchase/back-in-stock section.
	$after_action_priority = max( 1000, pepselect_child_dilution_notice_priority() );
	add_action( 'woocommerce_single_product_summary', 'pepselect_child_render_product_coa_summary', $after_action_priority );
	add_action( 'woocommerce_single_product_summary', 'pepselect_child_render_dilution_notice', $after_action_priority + 1 );
}
add_action( 'wp', 'pepselect_child_register_dilution_notice' );

/**
 * Render the COA plugin's compact current/incoming summary after the purchase
 * or back-in-stock action and before the dilution notice. The plugin remains
 * the sole owner of record selection, status wording, and report links.
 *
 * @return void
 */
function pepselect_child_render_product_coa_summary() {
	if ( ! shortcode_exists( 'pepselect_product_coa_carousel' ) ) {
		return;
	}

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Trusted plugin shortcode escapes its own complete output.
	echo do_shortcode( '[pepselect_product_coa_carousel variant="compact"]' );
}

/**
 * Product promotion pills (WEB M7).
 *
 * The single-product summary carries two promotional signals, both printed by
 * plugins and never by the theme:
 *
 *   - Cash back: YITH Points & Rewards prints its own earn message on the
 *     product page (rendered as `p.ywpar_earn_points`).
 *   - Buy 4, get 1 free: YITH Dynamic Pricing & Discounts prints its rule note
 *     (`.ywdpd-notices-wrapper` wrapping `.show_note_on_apply_products`).
 *
 * Rather than recompute either value from plugin internals (fragile and
 * version-bound), the theme captures what those plugins actually RENDER, lifts
 * the raw output out of the summary, and re-emits the text inside styled pills
 * in one flex row above the price. This mirrors the account.php approach of
 * reading YITH's own output instead of calling its API, so the plugins stay the
 * single source of truth. The quantity-discount table and the stock line are
 * left untouched.
 */

/**
 * Whether the pills output buffer is currently open. Guards the paired
 * start/end summary hooks so the buffer is only closed when we opened it.
 *
 * @var bool
 */
$GLOBALS['pepselect_pills_buffering'] = false;

/**
 * Decide whether the Buy 4, Get 1 Free promotion applies to a product.
 *
 * Detection is driven by whether YITH Dynamic Pricing actually rendered its
 * rule note for the product (passed in as $detected). The result is filterable
 * through `pepselect_product_has_b4g1`, so the promotion can be forced on or off
 * (globally or per product) without editing the template.
 *
 * @param bool            $detected Whether the plugin rendered a B4G1 note.
 * @param WC_Product|null $product  Product being evaluated.
 * @return bool
 */
function pepselect_product_has_b4g1( $detected = false, $product = null ) {
	return (bool) apply_filters( 'pepselect_product_has_b4g1', $detected, $product );
}

/**
 * Open an output buffer around the product summary so the promotional notes
 * the plugins print can be captured and restyled.
 *
 * @return void
 */
function pepselect_child_pills_buffer_start() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}

	if ( function_exists( 'pepselect_child_is_elementor_editor_request' ) && pepselect_child_is_elementor_editor_request() ) {
		return;
	}

	$GLOBALS['pepselect_pills_buffering'] = true;
	ob_start();
}
add_action( 'woocommerce_single_product_summary', 'pepselect_child_pills_buffer_start', 4 );

/**
 * Close the summary buffer, lift the plugin-rendered promotional notes out of
 * it, and re-emit them as styled pills in one flex row above the price.
 *
 * @return void
 */
function pepselect_child_pills_buffer_end() {
	if ( empty( $GLOBALS['pepselect_pills_buffering'] ) ) {
		return;
	}

	$GLOBALS['pepselect_pills_buffering'] = false;
	$html = ob_get_clean();

	global $product;

	// Capture and remove YITH Points & Rewards' own earn message. We only read
	// the text it renders; the plugin's API is never called.
	$cashback_text = '';
	$html          = pepselect_child_extract_plugin_note(
		$html,
		'#<p\b[^>]*class="[^"]*\bywpar_earn_points\b[^"]*"[^>]*>.*?</p>#is',
		$cashback_text
	);

	// Fallback selectors, in case the earn message is emitted under a sibling
	// class on this YITH version.
	if ( '' === $cashback_text ) {
		$html = pepselect_child_extract_plugin_note(
			$html,
			'#<([a-z0-9]+)\b[^>]*class="[^"]*\b(?:ywpar-message|ywpar_message_cart|yith-par-message)\b[^"]*"[^>]*>.*?</\1>#is',
			$cashback_text
		);
	}

	// Capture and remove YITH Dynamic Pricing's rule note (the gray
	// "Buy 4 get 1 free" text). The whole notices wrapper is removed so no empty
	// plugin markup is left behind. The separate quantity-discount table wrapper
	// (`ywdpd-table-discounts-wrapper`) is intentionally left in place.
	$b4g1_text = '';
	$html      = pepselect_child_extract_plugin_note(
		$html,
		'#<div\b[^>]*class="[^"]*\bywdpd-notices-wrapper\b[^"]*"[^>]*>.*?</div>\s*</div>#is',
		$b4g1_text
	);

	// Fallback to the inner note alone when the wrapper nesting differs.
	if ( '' === $b4g1_text ) {
		$html = pepselect_child_extract_plugin_note(
			$html,
			'#<div\b[^>]*class="[^"]*\bshow_note_on_apply_products\b[^"]*"[^>]*>.*?</div>#is',
			$b4g1_text
		);
	}

	$show_b4g1 = pepselect_product_has_b4g1( '' !== $b4g1_text, $product );

	$pills = '';

	if ( $show_b4g1 ) {
		// Prefer the plugin's own rule text so the pill always matches the live
		// rule; fall back to the designed label when the note was empty.
		$label  = '' !== $b4g1_text ? $b4g1_text : __( 'Buy 4, get the 5th free', 'pepselect-child' );
		$label  = apply_filters( 'pepselect_child_b4g1_pill_label', $label, $product );
		$pills .= pepselect_child_pill_markup( 'b4g1', $label );
	}

	if ( '' !== $cashback_text ) {
		$cashback_text = apply_filters( 'pepselect_child_cashback_pill_text', $cashback_text, $product );
		$pills        .= pepselect_child_pill_markup( 'cashback', $cashback_text );
	}

	if ( '' !== $pills ) {
		$row  = '<div class="pepselect-product-pills">' . $pills . '</div>';
		$html = pepselect_child_inject_before_price( $html, $row );
	}

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Re-emitting captured summary markup verbatim; pill text is escaped in pepselect_child_pill_markup().
	echo $html;
}
add_action( 'woocommerce_single_product_summary', 'pepselect_child_pills_buffer_end', 999 );

/**
 * Extract the first match of $pattern from $html, capture its inner text into
 * $text (stripped to a clean single line), and remove the matched node from the
 * returned markup. Returns $html unchanged when nothing matches.
 *
 * @param string $html    Buffered summary markup.
 * @param string $pattern Regex matching the node to lift out.
 * @param string $text    Receives the captured, stripped text by reference.
 * @return string Markup with the matched node removed.
 */
function pepselect_child_extract_plugin_note( $html, $pattern, &$text ) {
	if ( ! preg_match( $pattern, $html, $m ) ) {
		return $html;
	}

	$captured = wp_strip_all_tags( $m[0] );
	$captured = trim( preg_replace( '/\s+/', ' ', $captured ) );

	if ( '' === $captured ) {
		return $html;
	}

	$text = $captured;

	return preg_replace( $pattern, '', $html, 1 );
}

/**
 * Build a promotion pill. Cash back keeps the theme's cyan treatment; B4G1 uses
 * the amber treatment that matches the on-hold email.
 *
 * @param string $type One of 'cashback' or 'b4g1'.
 * @param string $text Visible pill text.
 * @return string
 */
function pepselect_child_pill_markup( $type, $text ) {
	$icons = array(
		'cashback' => '<svg class="pepselect-pill__icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24" width="16" height="16"><path fill="currentColor" d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8Zm.9-8.7c-1.6-.4-2.1-.7-2.1-1.3s.5-.9 1.3-.9a1.7 1.7 0 0 1 1.6.9l1.5-.9a3.1 3.1 0 0 0-2-1.5V6.5h-1.8v1.3c-1.3.3-2.2 1.2-2.2 2.5 0 1.7 1.4 2.3 2.9 2.7 1.4.3 1.7.7 1.7 1.3s-.6 1-1.4 1a2 2 0 0 1-1.9-1.2l-1.6.9a3.5 3.5 0 0 0 2.3 1.7v1.3h1.8v-1.3c1.4-.3 2.3-1.2 2.3-2.6 0-1.8-1.5-2.4-3.1-2.8Z"></path></svg>',
		'b4g1'     => '<svg class="pepselect-pill__icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24" width="16" height="16"><path fill="currentColor" d="M20 7h-2.2a3 3 0 0 0-.5-3.4 3 3 0 0 0-4.2 0L12 4.2l-1.1-1.1a3 3 0 0 0-4.2 0A3 3 0 0 0 6.2 7H4a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h1v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-7h1a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1Zm-6.5-2.1a1 1 0 1 1 1.4 1.4l-.7.7H13V5.6l.5-.7ZM9.1 4.9a1 1 0 0 1 1.4 0l.5.7V7H9.8l-.7-.7a1 1 0 0 1 0-1.4ZM11 19H7v-7h4v7Zm0-9H5V9h6v1Zm6 9h-4v-7h4v7Zm2-9h-6V9h6v1Z"></path></svg>',
	);

	$icon  = isset( $icons[ $type ] ) ? $icons[ $type ] : '';
	$class = 'b4g1' === $type ? 'pepselect-b4g1-pill' : 'pepselect-cashback-pill';

	return '<span class="pepselect-pill ' . esc_attr( $class ) . '">'
		. $icon
		. '<span class="pepselect-pill__label">' . esc_html( $text ) . '</span>'
		. '</span>';
}

/**
 * Insert $row immediately before the WooCommerce price element so the pills sit
 * above the price, the position the plugin notes held. Falls back to prepending
 * when no price node is found.
 *
 * @param string $html Summary markup.
 * @param string $row  Pills row markup.
 * @return string
 */
function pepselect_child_inject_before_price( $html, $row ) {
	if ( preg_match( '#<p\b[^>]*class="[^"]*\bprice\b[^"]*"#i', $html, $m, PREG_OFFSET_CAPTURE ) ) {
		$pos = (int) $m[0][1];

		return substr( $html, 0, $pos ) . $row . substr( $html, $pos );
	}

	return $row . $html;
}

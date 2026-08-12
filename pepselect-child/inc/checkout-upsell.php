<?php
/**
 * Bacteriostatic Water checkout upsell (M10).
 *
 * Renders a toggle directly above the payment method on checkout, so the order
 * total is final before the Square payment instruction. Turning it on adds one
 * Bacteriostatic Water to the cart and refreshes the totals live;
 * turning it off removes it. The product is resolved by SKU from the constant
 * below, and its price and stock are always read from the live product. If the
 * product is out of stock, missing, or the SKU is unset, the block does not
 * render at all.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * SKU of the Bacteriostatic Water product.
 *
 * PASTE THE SKU HERE once the product exists in WooCommerce. While this is an
 * empty string the upsell block simply does not appear, so shipping it blank is
 * safe. Only this one value needs to change.
 */
if ( ! defined( 'PEPSELECT_BAC_WATER_SKU' ) ) {
	define( 'PEPSELECT_BAC_WATER_SKU', 'BACW30' );
}

/**
 * Resolve the Bacteriostatic Water product, or null when it should not be
 * offered: no SKU set, product not found, not purchasable, or out of stock.
 *
 * @return WC_Product|null
 */
function pepselect_child_get_bacwater_product() {
	$sku = (string) PEPSELECT_BAC_WATER_SKU;

	if ( '' === $sku || ! function_exists( 'wc_get_product_id_by_sku' ) ) {
		return null;
	}

	$product_id = wc_get_product_id_by_sku( $sku );

	if ( ! $product_id ) {
		return null;
	}

	$product = wc_get_product( $product_id );

	if ( ! $product || ! $product->is_purchasable() || ! $product->is_in_stock() ) {
		return null;
	}

	return $product;
}

/**
 * Find the cart item key for the Bacteriostatic Water product, or '' when it is
 * not in the cart.
 *
 * @param int $product_id Product ID.
 * @return string
 */
function pepselect_child_bacwater_cart_key( $product_id ) {
	if ( is_null( WC()->cart ) ) {
		return '';
	}

	foreach ( WC()->cart->get_cart() as $key => $item ) {
		if ( (int) $item['product_id'] === (int) $product_id && empty( $item['variation_id'] ) ) {
			return (string) $key;
		}
	}

	return '';
}

/**
 * Render the upsell block directly above the payment method, through the Fluid
 * Checkout before-payment hook, so the order total is final before the Square
 * payment instruction.
 *
 * @return void
 */
function pepselect_child_render_bacwater_upsell() {
	$product = pepselect_child_get_bacwater_product();

	if ( ! $product ) {
		return;
	}

	$in_cart    = '' !== pepselect_child_bacwater_cart_key( $product->get_id() );
	$price_html = wc_price( wc_get_price_to_display( $product ) );
	$name       = $product->get_name();

	// The product carries no volume attribute, so the volume is parsed from the
	// product title (e.g. "Bacteriostatic Water 30mL" -> "30mL") rather than typed
	// as a literal, and follows the title if it changes. When no volume can be
	// parsed the toggle falls back to the full product name.
	$volume   = preg_match( '/(\d+(?:\.\d+)?\s?m[lL])\b/', $name, $matches ) ? $matches[1] : '';
	$add_lead = '' !== $volume ? $volume : $name;

	/* translators: %s: full product name, e.g. "Bacteriostatic Water 30mL". */
	$aria = sprintf( __( 'Add %s to your order', 'pepselect-child' ), $name );
	// Product image, resolved from the same WC_Product the price and stock come
	// from - no second lookup, no hard-coded attachment id or URL. Rendered only
	// when the product actually has a featured image, so a product without one
	// degrades to the previous text-only block instead of WooCommerce's
	// placeholder box.
	$image_html = 0 < (int) $product->get_image_id()
		? $product->get_image( 'woocommerce_thumbnail', array( 'class' => 'pepselect-bacwater__img' ) )
		: '';
	?>
	<div class="pepselect-bacwater-standalone">
			<div class="pepselect-bacwater<?php echo '' !== $image_html ? ' pepselect-bacwater--has-media' : ''; ?>" data-pepselect-bacwater>
				<?php if ( '' !== $image_html ) : ?>
					<div class="pepselect-bacwater__media" aria-hidden="true"><?php echo wp_kses_post( $image_html ); ?></div>
				<?php endif; ?>
				<div class="pepselect-bacwater__body">
				<p class="pepselect-bacwater__question"><?php esc_html_e( 'Need bacteriostatic water?', 'pepselect-child' ); ?></p>
				<p class="pepselect-bacwater__sub"><?php esc_html_e( 'Compounds ship as lyophilized powder.', 'pepselect-child' ); ?></p>
				<div class="pepselect-bacwater__row">
				<label class="pepselect-bacwater__toggle">
					<input type="checkbox" class="pepselect-bacwater__input" data-pepselect-bacwater-input aria-label="<?php echo esc_attr( $aria ); ?>" <?php checked( $in_cart ); ?> />
					<span class="pepselect-bacwater__switch" aria-hidden="true"></span>
					<span class="pepselect-bacwater__text"><?php esc_html_e( 'Add to cart', 'pepselect-child' ); ?></span>
				</label>
					<span class="pepselect-bacwater__price-line">
						<?php
						/* translators: 1: product volume, e.g. "30mL". 2: formatted price. */
						echo wp_kses_post( sprintf( __( '%1$s &ndash; %2$s', 'pepselect-child' ), esc_html( $add_lead ), $price_html ) );
						?>
					</span>
				</div>
				</div>
			</div>
	</div>
	<?php
}
/**
 * Render the upsell inside the order summary, below the line items.
 *
 * woocommerce_review_order_after_cart_contents fires inside the review table's
 * tbody, after the line items and before the totals in tfoot, which is the
 * required position. Because the insertion point is inside a table, the card is
 * wrapped in a full-width row; the card markup itself is unchanged.
 *
 * @return void
 */
function pepselect_child_render_bacwater_upsell_summary_row() {
	echo '<tr class="pepselect-summary-row pepselect-summary-row--bac"><td colspan="2" class="pepselect-panel-cell">';
	pepselect_child_render_bacwater_upsell();
	echo '</td></tr>';
}
add_action( 'woocommerce_review_order_after_cart_contents', 'pepselect_child_render_bacwater_upsell_summary_row', 10 );

/**
 * Toggle the Bacteriostatic Water line in the cart. Runs through the WooCommerce
 * AJAX endpoint so the cart and session are loaded. Quantity is always one; the
 * cart line is added when absent and removed entirely when present.
 *
 * @return void
 */
function pepselect_child_bacwater_toggle() {
	check_ajax_referer( 'pepselect_bacwater', 'nonce' );

	if ( is_null( WC()->cart ) ) {
		wp_send_json_error();
	}

	$product = pepselect_child_get_bacwater_product();

	if ( ! $product ) {
		wp_send_json_error();
	}

	$product_id = $product->get_id();
	$key        = pepselect_child_bacwater_cart_key( $product_id );
	$add        = isset( $_POST['add'] ) && '1' === sanitize_text_field( wp_unslash( $_POST['add'] ) );

	if ( $add && '' === $key ) {
		WC()->cart->add_to_cart( $product_id, 1 );
	} elseif ( ! $add && '' !== $key ) {
		WC()->cart->remove_cart_item( $key );
	}

	wp_send_json_success( array( 'in_cart' => '' !== pepselect_child_bacwater_cart_key( $product_id ) ) );
}
add_action( 'wc_ajax_pepselect_bacwater_toggle', 'pepselect_child_bacwater_toggle' );

/**
 * Enqueue the upsell script on the checkout page only, and only when the product
 * is actually offered, so nothing loads when the block will not render.
 *
 * @return void
 */
function pepselect_child_bacwater_assets() {
	if ( ! function_exists( 'is_checkout' ) ) {
		return;
	}

	// The side cart can open on any front-end page, so the toggle script has to
	// load beyond the checkout now. Loaded only when the plugin is active and the
	// product is actually offerable, so ordinary pages gain nothing they cannot
	// use. The script is delegated on document, so it binds to the side cart's
	// markup even though that is rendered after load.
	$side_cart_active = class_exists( 'Xoo_Wsc_Core' ) || function_exists( 'xoo_wsc_cart' ) || function_exists( 'xoo_wsc_helper' );

	if ( ! is_checkout() && ! $side_cart_active ) {
		return;
	}

	if ( ! pepselect_child_get_bacwater_product() ) {
		return;
	}

	wp_enqueue_script(
		'pepselect-child-checkout-upsell',
		get_stylesheet_directory_uri() . '/assets/js/checkout-upsell.js',
		array( 'jquery' ),
		pepselect_child_asset_version( 'assets/js/checkout-upsell.js' ),
		true
	);

	wp_localize_script(
		'pepselect-child-checkout-upsell',
		'pepselectBacwater',
		array(
			'endpoint' => class_exists( 'WC_AJAX' ) ? WC_AJAX::get_endpoint( 'pepselect_bacwater_toggle' ) : admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'pepselect_bacwater' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'pepselect_child_bacwater_assets', 45 );

/**
 * Render the upsell in the side cart, between the totals and the buttons.
 *
 * Side Cart WooCommerce fires xoo_wsc_before_footer_btns inside its footer
 * buttons template, which runs after global/footer/totals.php - the slot Paulo
 * asked for. Using the plugin's own hook avoids a template override, so the
 * plugin can update without carrying a stale copy in the theme.
 *
 * The same resolver as the checkout is used, so an out-of-stock, unpurchasable
 * or missing product returns null and nothing renders. When the product is
 * already in the cart the panel is not shown at all - in the side cart the
 * customer can already see the line item, so re-offering it would be noise.
 *
 * @return void
 */
function pepselect_child_render_bacwater_side_cart() {
	$product = pepselect_child_get_bacwater_product();

	if ( ! $product ) {
		return;
	}

	if ( '' !== pepselect_child_bacwater_cart_key( $product->get_id() ) ) {
		return;
	}

	echo '<div class="pepselect-bacwater-sidecart">';
	pepselect_child_render_bacwater_upsell();
	echo '</div>';
}
add_action( 'xoo_wsc_before_footer_btns', 'pepselect_child_render_bacwater_side_cart', 10 );

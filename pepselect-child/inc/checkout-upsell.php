<?php
/**
 * Bacteriostatic Water checkout upsell (M10).
 *
 * Renders a toggle under the order total in the checkout Order Summary. Turning
 * it on adds one Bacteriostatic Water to the cart and refreshes the totals live;
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
 * Render the upsell block under the order total. Output is a totals-table row so
 * it sits directly beneath the order total and above the terms/privacy text.
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
	?>
	<tr class="pepselect-bacwater-row">
		<td colspan="2" class="pepselect-bacwater-cell">
			<div class="pepselect-bacwater" data-pepselect-bacwater>
				<p class="pepselect-bacwater__question"><?php esc_html_e( 'Do you have all the supplies needed for your research?', 'pepselect-child' ); ?></p>
				<label class="pepselect-bacwater__toggle">
					<input type="checkbox" class="pepselect-bacwater__input" data-pepselect-bacwater-input <?php checked( $in_cart ); ?> />
					<span class="pepselect-bacwater__switch" aria-hidden="true"></span>
					<span class="pepselect-bacwater__text">
						<?php esc_html_e( 'Add Bacteriostatic Water', 'pepselect-child' ); ?>
						<span class="pepselect-bacwater__price">&mdash; <?php echo wp_kses_post( $price_html ); ?></span>
					</span>
				</label>
			</div>
		</td>
	</tr>
	<?php
}
add_action( 'woocommerce_review_order_after_order_total', 'pepselect_child_render_bacwater_upsell' );

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
	if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
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

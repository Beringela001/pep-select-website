<?php
/**
 * Unified compound card for the homepage grid and the compounds archive.
 *
 * Typography follows the approved editorial treatment: serif compound
 * title, strength pill from the product_tag taxonomy, "Available" stock
 * language, price, and a Learn more action. Out-of-stock products swap the
 * action for a notify link to the product page, where the back-in-stock
 * form lives. Status bands are read-only states from the COA Archive
 * plugin bridge.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$product = isset( $args['product'] ) ? $args['product'] : null;

if ( ! is_a( $product, 'WC_Product' ) ) {
	return;
}

$product_name   = $product->get_name();
$product_url    = $product->get_permalink();
$image_id       = $product->get_image_id();
$price_html     = $product->get_price_html();
$in_stock       = $product->is_in_stock();
$strength_label = function_exists( 'pepselect_child_get_product_strength_label' ) ? pepselect_child_get_product_strength_label( $product ) : '';
$status_band    = function_exists( 'pepselect_child_get_product_status_band' ) ? pepselect_child_get_product_status_band( $product->get_id() ) : null;

/*
 * Action variant. Defaults to the Learn more link every existing caller
 * already renders, so the homepage grid and the single-compound related
 * carousel are unaffected. Callers opt in to the add-to-cart button by
 * passing 'action' => 'add-to-cart'; a product that cannot be added directly
 * still falls back to the link, so the button is never shipped broken.
 */
$card_action = isset( $args['action'] ) ? (string) $args['action'] : 'link';
$can_add     = $in_stock && $product->is_purchasable() && $product->is_type( 'simple' );

/*
 * Presentation variant. Empty by default, which is the layout the related
 * carousel and the empty-cart carousel already render, so those two surfaces
 * are unaffected by anything below. 'archive' and 'home' opt into the compact
 * layout: the dose pill moves onto the name row, the reserved heights come
 * out, and the whole card becomes a stretched link. 'home' additionally drops
 * the stock line, which is redundant on a grid that only shows in-stock
 * compounds.
 */
$variant     = isset( $args['variant'] ) ? (string) $args['variant'] : '';
$is_compact  = in_array( $variant, array( 'archive', 'home' ), true );
$show_stock  = 'home' !== $variant;
$has_status  = $status_band || $show_stock;

$card_classes = 'pepselect-card';

if ( $is_compact ) {
	$card_classes .= ' pepselect-card--compact pepselect-card--' . $variant;
}
?>
<article class="<?php echo esc_attr( $card_classes ); ?>">
	<a class="pepselect-card__media" href="<?php echo esc_url( $product_url ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'View %s', 'pepselect-child' ), $product_name ) ); ?>" tabindex="-1">
		<?php
		if ( $image_id ) {
			echo wp_get_attachment_image(
				$image_id,
				'woocommerce_thumbnail',
				false,
				array(
					'alt'      => $product_name,
					'loading'  => 'lazy',
					'decoding' => 'async',
				)
			); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Core generates escaped markup.
		}
		?>
	</a>

	<div class="pepselect-card__body">
		<?php if ( $is_compact ) : ?>
			<?php // Name and dose pill share one row: name left, pill right. ?>
			<div class="pepselect-card__headline">
				<h3 class="pepselect-card__title"><a href="<?php echo esc_url( $product_url ); ?>"><?php echo esc_html( $product_name ); ?></a></h3>

				<?php if ( '' !== $strength_label ) : ?>
					<span class="pepselect-card__strength"><?php echo esc_html( $strength_label ); ?></span>
				<?php endif; ?>
			</div>
		<?php else : ?>
			<?php if ( '' !== $strength_label ) : ?>
				<span class="pepselect-card__strength"><?php echo esc_html( $strength_label ); ?></span>
			<?php endif; ?>

			<h3 class="pepselect-card__title"><a href="<?php echo esc_url( $product_url ); ?>"><?php echo esc_html( $product_name ); ?></a></h3>
		<?php endif; ?>

		<?php if ( $has_status ) : ?>
			<div class="pepselect-card__status">
				<?php if ( $status_band ) : ?>
					<p class="pepselect-card__band pepselect-card__band--<?php echo esc_attr( $status_band['tone'] ); ?>"><?php echo esc_html( $status_band['label'] ); ?></p>
				<?php endif; ?>

				<?php if ( $show_stock ) : ?>
					<p class="pepselect-card__stock <?php echo $in_stock ? 'pepselect-card__stock--available' : 'pepselect-card__stock--out'; ?>">
						<?php $in_stock ? esc_html_e( 'Available', 'pepselect-child' ) : esc_html_e( 'Out of Stock', 'pepselect-child' ); ?>
					</p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<p class="pepselect-card__price"><?php echo wp_kses_post( $price_html ); ?></p>

		<?php if ( $in_stock && 'add-to-cart' === $card_action && $can_add ) : ?>
			<?php
			/*
			 * Standard WooCommerce loop add-to-cart markup, so cart state, the
			 * AJAX refresh, and the header count behave exactly as they do on
			 * the shop archive. Only the class list is extended, not replaced.
			 */
			$pep_classes = 'pepselect-card__action pepselect-card__action--cart button product_type_' . $product->get_type() . ' add_to_cart_button';

			if ( $product->supports( 'ajax_add_to_cart' ) ) {
				$pep_classes .= ' ajax_add_to_cart';
			}
			?>
			<a
				class="<?php echo esc_attr( $pep_classes ); ?>"
				href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"
				data-quantity="1"
				data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"
				data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>"
				aria-label="<?php echo esc_attr( sprintf( __( 'Add %s to your cart', 'pepselect-child' ), $product_name ) ); ?>"
				rel="nofollow"
			><?php echo esc_html( $product->add_to_cart_text() ); ?></a>
		<?php elseif ( $in_stock ) : ?>
			<a class="pepselect-card__action" href="<?php echo esc_url( $product_url ); ?>"><?php echo esc_html( 'add-to-cart' === $card_action ? $product->add_to_cart_text() : __( 'Learn more', 'pepselect-child' ) ); ?></a>
		<?php else : ?>
			<?php $inline_form = shortcode_exists( 'cwginstock_subscribe_form' ); ?>
			<a class="pepselect-card__action pepselect-card__action--notify" href="<?php echo esc_url( $product_url ); ?>"<?php if ( $inline_form ) : ?> data-pepselect-notify-toggle aria-expanded="false" aria-controls="pepselect-notify-<?php echo esc_attr( $product->get_id() ); ?>"<?php endif; ?>><?php esc_html_e( 'Notify when available', 'pepselect-child' ); ?></a>
		<?php endif; ?>
	</div>

	<?php if ( ! $in_stock && shortcode_exists( 'cwginstock_subscribe_form' ) ) : ?>
		<dialog id="pepselect-notify-<?php echo esc_attr( $product->get_id() ); ?>" class="pepselect-notify-dialog" aria-label="<?php echo esc_attr( sprintf( __( 'Back in stock notification for %s', 'pepselect-child' ), $product_name ) ); ?>">
			<button type="button" class="pepselect-notify-dialog__close" data-pepselect-notify-close aria-label="<?php esc_attr_e( 'Close', 'pepselect-child' ); ?>">&times;</button>

			<div class="pepselect-notify-dialog__identity">
				<?php
				if ( $image_id ) {
					echo wp_get_attachment_image( $image_id, 'woocommerce_gallery_thumbnail', false, array( 'alt' => $product_name, 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Core generates escaped markup.
				}
				?>
				<div>
					<?php if ( '' !== $strength_label ) : ?>
						<span class="pepselect-card__strength"><?php echo esc_html( $strength_label ); ?></span>
					<?php endif; ?>
					<p class="pepselect-notify-dialog__title"><?php echo esc_html( $product_name ); ?></p>
				</div>
			</div>

			<div class="pepselect-notify-dialog__form-view">
				<p class="pepselect-notify-dialog__lead"><?php echo esc_html( sprintf( __( 'Leave your email and we will let you know the moment %s is available again.', 'pepselect-child' ), $product_name ) ); ?></p>

				<?php echo do_shortcode( '[cwginstock_subscribe_form product_id="' . absint( $product->get_id() ) . '"]' ); ?>
			</div>

			<div class="pepselect-notify-dialog__success" hidden>
				<p class="pepselect-notify-dialog__success-title"><?php esc_html_e( 'You&rsquo;re all set.', 'pepselect-child' ); ?></p>
				<p class="pepselect-notify-dialog__success-copy"><?php echo esc_html( sprintf( __( 'Once %s comes back in stock, we will notify you at', 'pepselect-child' ), $product_name ) ); ?> <strong data-pepselect-subscriber-email></strong></p>
				<a class="pepselect-notify-dialog__continue" href="<?php echo esc_url( $product_url ); ?>"><?php esc_html_e( 'Continue shopping', 'pepselect-child' ); ?></a>
			</div>
		</dialog>
	<?php endif; ?>
</article>

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
?>
<article class="pepselect-card">
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
		<?php if ( '' !== $strength_label ) : ?>
			<span class="pepselect-card__strength"><?php echo esc_html( $strength_label ); ?></span>
		<?php endif; ?>

		<h3 class="pepselect-card__title"><a href="<?php echo esc_url( $product_url ); ?>"><?php echo esc_html( $product_name ); ?></a></h3>

		<div class="pepselect-card__status">
			<?php if ( $status_band ) : ?>
				<p class="pepselect-card__band pepselect-card__band--<?php echo esc_attr( $status_band['tone'] ); ?>"><?php echo esc_html( $status_band['label'] ); ?></p>
			<?php endif; ?>

			<p class="pepselect-card__stock <?php echo $in_stock ? 'pepselect-card__stock--available' : 'pepselect-card__stock--out'; ?>">
				<?php $in_stock ? esc_html_e( 'Available', 'pepselect-child' ) : esc_html_e( 'Out of Stock', 'pepselect-child' ); ?>
			</p>
		</div>

		<p class="pepselect-card__price"><?php echo wp_kses_post( $price_html ); ?></p>

		<?php if ( $in_stock ) : ?>
			<a class="pepselect-card__action" href="<?php echo esc_url( $product_url ); ?>"><?php esc_html_e( 'Learn more', 'pepselect-child' ); ?></a>
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

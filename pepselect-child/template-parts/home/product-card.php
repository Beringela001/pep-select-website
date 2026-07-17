<?php
/**
 * WEB-2C homepage product-card foundation for later WEB-2D refinement.
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

$product_name = $product->get_name();
$product_url  = $product->get_permalink();
$image_id     = $product->get_image_id();
$price_html   = $product->get_price_html();
$stock_label  = $product->is_in_stock()
	? __( 'In stock', 'woocommerce' )
	: __( 'Out of stock', 'woocommerce' );
?>
<article class="pepselect-home__product-card">
	<a class="pepselect-home__product-image-link" href="<?php echo esc_url( $product_url ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'View %s', 'pepselect-child' ), $product_name ) ); ?>">
		<?php if ( $image_id ) : ?>
			<?php
			echo wp_get_attachment_image(
				$image_id,
				'woocommerce_thumbnail',
				false,
				array(
					'alt'      => $product_name,
					'class'    => 'pepselect-home__product-image',
					'decoding' => 'async',
					'loading'  => 'lazy',
				)
			); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WordPress generates escaped image markup.
			?>
		<?php else : ?>
			<span class="pepselect-home__product-image-fallback"><?php esc_html_e( 'Product image unavailable', 'pepselect-child' ); ?></span>
		<?php endif; ?>
	</a>
	<div class="pepselect-home__product-content">
		<p class="pepselect-home__stock-state"><?php echo esc_html( $stock_label ); ?></p>
		<h3><a href="<?php echo esc_url( $product_url ); ?>"><?php echo esc_html( $product_name ); ?></a></h3>
		<?php if ( '' !== trim( wp_strip_all_tags( $price_html ) ) ) : ?>
			<div class="pepselect-home__product-price"><?php echo wp_kses_post( $price_html ); ?></div>
		<?php endif; ?>
		<a class="pepselect-home__product-link" href="<?php echo esc_url( $product_url ); ?>"><?php esc_html_e( 'View Compound Details', 'pepselect-child' ); ?></a>
	</div>
</article>

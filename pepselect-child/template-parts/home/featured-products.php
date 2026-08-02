<?php
/**
 * Product-first featured-compound section.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$shop_url      = isset( $args['shop_url'] ) ? $args['shop_url'] : home_url( '/shop/' );
$product_state = isset( $args['product_state'] ) && is_array( $args['product_state'] ) ? $args['product_state'] : array( 'available' => false, 'products' => array() );
$products      = isset( $product_state['products'] ) ? array_slice( (array) $product_state['products'], 0, 4 ) : array();
?>
<section class="pepselect-home__section pepselect-home__products" aria-labelledby="pepselect-products-title">
	<div class="pepselect-home__inner">
		<div class="pepselect-home__section-heading">
			<div>
				<p class="pepselect-home__eyebrow"><?php esc_html_e( 'The Current Selection', 'pepselect-child' ); ?></p>
				<h2 id="pepselect-products-title"><?php esc_html_e( 'A short list, on purpose.', 'pepselect-child' ); ?></h2>
				<p><?php esc_html_e( 'Every compound here was selected before it was stocked, with strength and available batch records in plain view.', 'pepselect-child' ); ?></p>
			</div>
			<a class="pepselect-home__text-link" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Browse the Full Selection', 'pepselect-child' ); ?></a>
		</div>

		<?php if ( $products ) : ?>
			<div class="pepselect-home__product-grid" aria-label="<?php esc_attr_e( 'Featured compounds', 'pepselect-child' ); ?>">
				<?php foreach ( $products as $product ) : ?>
					<?php get_template_part( 'template-parts/home/product-card', null, array( 'product' => $product, 'variant' => 'home' ) ); ?>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<div class="pepselect-home__empty-state">
				<p>
					<?php
					echo esc_html(
						! empty( $product_state['available'] )
							? __( 'No qualifying products are available.', 'pepselect-child' )
							: __( 'The product catalog is unavailable.', 'pepselect-child' )
					);
					?>
				</p>
				<a class="pepselect-home__button pepselect-home__button--primary" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Explore Compounds', 'pepselect-child' ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</section>

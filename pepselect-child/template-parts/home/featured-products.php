<?php
/**
 * WEB-2C featured-product section.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$shop_url      = isset( $args['shop_url'] ) ? $args['shop_url'] : home_url( '/shop/' );
$product_state = isset( $args['product_state'] ) && is_array( $args['product_state'] ) ? $args['product_state'] : array( 'available' => false, 'products' => array() );
$products      = isset( $product_state['products'] ) ? (array) $product_state['products'] : array();
?>
<section class="pepselect-home__section pepselect-home__products" aria-labelledby="pepselect-products-title">
	<div class="pepselect-home__inner">
		<div class="pepselect-home__section-heading">
			<div>
				<p class="pepselect-home__section-index" aria-hidden="true">04</p>
				<h2 id="pepselect-products-title"><?php esc_html_e( 'Current research compounds', 'pepselect-child' ); ?></h2>
				<p><?php esc_html_e( 'Browse in-stock compounds from the current Pep Select catalog. Product details, availability, and prices come from the product record.', 'pepselect-child' ); ?></p>
			</div>
			<a class="pepselect-home__text-link" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Explore All Compounds', 'pepselect-child' ); ?></a>
		</div>

		<?php if ( $products ) : ?>
			<div class="pepselect-home__product-grid">
				<?php foreach ( $products as $product ) : ?>
					<?php get_template_part( 'template-parts/home/product-card', null, array( 'product' => $product ) ); ?>
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

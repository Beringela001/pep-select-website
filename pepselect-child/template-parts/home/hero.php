<?php
/**
 * Product-first WEB-2C homepage hero.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$shop_url      = isset( $args['shop_url'] ) ? $args['shop_url'] : home_url( '/shop/' );
$testing_url   = isset( $args['testing_url'] ) ? $args['testing_url'] : home_url( '/testing/' );
$hero_products = isset( $args['hero_products'] ) ? array_values( (array) $args['hero_products'] ) : array();
$hero_count    = count( $hero_products );
$lead_product  = isset( $hero_products[0] ) && is_a( $hero_products[0], 'WC_Product' ) ? $hero_products[0] : null;
$signals       = array(
	__( 'Live catalog pricing', 'pepselect-child' ),
	__( 'Current stock visibility', 'pepselect-child' ),
	__( 'Available batch records', 'pepselect-child' ),
	__( 'Direct support', 'pepselect-child' ),
);
?>
<section class="pepselect-home__section pepselect-home__hero" aria-labelledby="pepselect-home-title">
	<div class="pepselect-home__inner pepselect-home__hero-grid">
		<div class="pepselect-home__hero-copy">
			<p class="pepselect-home__eyebrow pepselect-home__eyebrow--dark"><?php esc_html_e( 'Pep Select Research Compounds', 'pepselect-child' ); ?></p>
			<h1 id="pepselect-home-title">
				<span><?php esc_html_e( 'Choose the compound.', 'pepselect-child' ); ?></span>
				<em><?php esc_html_e( 'See the record.', 'pepselect-child' ); ?></em>
			</h1>
			<p class="pepselect-home__lead"><?php esc_html_e( 'A focused catalog of research compounds with clear product details, current pricing, and available batch documentation within reach.', 'pepselect-child' ); ?></p>
			<div class="pepselect-home__actions">
				<a class="pepselect-home__button pepselect-home__button--cyan" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Explore Compounds', 'pepselect-child' ); ?></a>
				<a class="pepselect-home__button pepselect-home__button--outline-light" href="<?php echo esc_url( $testing_url ); ?>"><?php esc_html_e( 'Review COAs', 'pepselect-child' ); ?></a>
			</div>
			<ul class="pepselect-home__hero-signals" aria-label="<?php esc_attr_e( 'Catalog information available on Pep Select', 'pepselect-child' ); ?>">
				<?php foreach ( $signals as $signal ) : ?>
					<li><?php echo esc_html( $signal ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>

		<div class="pepselect-home__hero-stage" data-product-count="<?php echo esc_attr( (string) $hero_count ); ?>">
			<?php if ( $hero_products ) : ?>
				<div class="pepselect-home__hero-products">
					<?php foreach ( $hero_products as $index => $product ) : ?>
						<?php
						if ( ! is_a( $product, 'WC_Product' ) ) {
							continue;
						}

						$image_id = $product->get_image_id();

						if ( ! $image_id ) {
							continue;
						}
						?>
						<a class="pepselect-home__hero-product pepselect-home__hero-product--<?php echo esc_attr( (string) ( $index + 1 ) ); ?>" href="<?php echo esc_url( $product->get_permalink() ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'View %s', 'pepselect-child' ), $product->get_name() ) ); ?>">
							<?php
							echo wp_get_attachment_image(
								$image_id,
								'woocommerce_single',
								false,
								array(
									'alt'           => $product->get_name(),
									'class'         => 'pepselect-home__hero-product-image',
									'decoding'      => 'async',
									'fetchpriority' => 0 === $index ? 'high' : 'auto',
									'loading'       => 0 === $index ? 'eager' : 'lazy',
									'sizes'         => '(max-width: 767px) 70vw, 34vw',
								)
							); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WordPress generates escaped responsive image markup.
							?>
						</a>
					<?php endforeach; ?>
				</div>

				<?php if ( $lead_product ) : ?>
					<div class="pepselect-home__hero-product-card">
						<p class="pepselect-home__stock-state"><?php echo esc_html( $lead_product->is_in_stock() ? __( 'In stock', 'woocommerce' ) : __( 'Out of stock', 'woocommerce' ) ); ?></p>
						<h2><?php echo esc_html( $lead_product->get_name() ); ?></h2>
						<?php if ( '' !== trim( wp_strip_all_tags( $lead_product->get_price_html() ) ) ) : ?>
							<div class="pepselect-home__product-price"><?php echo wp_kses_post( $lead_product->get_price_html() ); ?></div>
						<?php endif; ?>
						<a href="<?php echo esc_url( $lead_product->get_permalink() ); ?>"><?php esc_html_e( 'View Compound', 'pepselect-child' ); ?></a>
					</div>
				<?php endif; ?>
			<?php else : ?>
				<div class="pepselect-home__hero-image-ready">
					<span><?php esc_html_e( 'Pep Select Catalog', 'pepselect-child' ); ?></span>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

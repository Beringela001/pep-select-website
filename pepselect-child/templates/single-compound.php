<?php
/**
 * Coded single compound page (WEB-2E), COA-consistent card layout.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header( 'shop' );

while ( have_posts() ) :
	the_post();
	global $product;
	if ( ! is_a( $product, 'WC_Product' ) ) {
		$product = wc_get_product( get_the_ID() );
	}
	$shop_url = function_exists( 'pepselect_child_get_shop_url' ) ? pepselect_child_get_shop_url() : home_url( '/shop/' );
	?>
	<main id="pepselect-compound-main" class="pepselect-compound-page" tabindex="-1">
		<div class="pepselect-compound-page__inner">

			<nav class="pepselect-compound-page__breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'pepselect-child' ); ?>">
				<a href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Compounds', 'pepselect-child' ); ?></a>
				<span aria-hidden="true">&rsaquo;</span>
				<span><?php echo esc_html( $product->get_name() ); ?></span>
			</nav>

			<div class="pepselect-compound-page__top">
				<figure class="pepselect-compound-page__gallery">
					<?php
					$pep_img_id  = $product->get_image_id();
					$pep_full    = $pep_img_id ? wp_get_attachment_image_url( $pep_img_id, 'full' ) : wc_placeholder_img_src( 'large' );
					$pep_display = $pep_img_id ? wp_get_attachment_image_url( $pep_img_id, 'woocommerce_single' ) : wc_placeholder_img_src( 'large' );
					?>
					<button type="button" class="pepselect-compound-page__zoom" data-pepselect-lightbox-open aria-label="<?php esc_attr_e( 'Expand image', 'pepselect-child' ); ?>"><span aria-hidden="true">&#9906;</span></button>
					<img class="pepselect-compound-page__image" src="<?php echo esc_url( $pep_display ); ?>" data-full="<?php echo esc_url( $pep_full ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" data-pepselect-lightbox-open />
				</figure>
				<div class="pepselect-compound-page__summary">
					<?php
					/**
					 * Native WooCommerce summary: title, price, stock, quantity
					 * discounts, add to cart, points, meta. All commerce logic intact.
					 */
					do_action( 'woocommerce_single_product_summary' );
					?>
				</div>
			</div>

			<div class="pepselect-lightbox" data-pepselect-lightbox hidden>
				<button type="button" class="pepselect-lightbox__close" data-pepselect-lightbox-close aria-label="<?php esc_attr_e( 'Close', 'pepselect-child' ); ?>">&times;</button>
				<img class="pepselect-lightbox__image" src="" alt="<?php echo esc_attr( $product->get_name() ); ?>" />
			</div>

			<?php get_template_part( 'template-parts/product/description', null, array( 'product' => $product ) ); ?>

			<?php if ( shortcode_exists( 'pepselect_product_coa_carousel' ) ) : ?>
				<section class="pepselect-compound-page__testing">
					<?php echo do_shortcode( '[pepselect_product_coa_carousel]' ); ?>
				</section>
			<?php endif; ?>

			<?php
			$related = wc_get_related_products( $product->get_id(), 3 );
			if ( $related ) :
				?>
				<section class="pepselect-compound-page__related" aria-labelledby="pepselect-related-heading">
					<header class="pepselect-compound-page__section-heading pepselect-compound-page__section-heading--split">
						<div>
							<p class="pepselect-compound-page__eyebrow"><?php esc_html_e( 'Keep exploring', 'pepselect-child' ); ?></p>
							<h2 id="pepselect-related-heading"><?php esc_html_e( 'More from the selection', 'pepselect-child' ); ?></h2>
						</div>
						<a class="pepselect-compound-page__view-all" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'View all compounds', 'pepselect-child' ); ?> <span aria-hidden="true">&rarr;</span></a>
					</header>
					<ul class="pepselect-compound-page__related-grid">
						<?php
						foreach ( $related as $related_id ) {
							$related_product = wc_get_product( $related_id );
							if ( $related_product && $related_product->is_visible() ) {
								echo '<li>';
								get_template_part( 'template-parts/home/product-card', null, array( 'product' => $related_product ) );
								echo '</li>';
							}
						}
						?>
					</ul>
				</section>
			<?php endif; ?>

		</div>
	</main>
	<?php
endwhile;

get_footer( 'shop' );

<?php
/**
 * Product-led Pep Select editorial section.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$product     = isset( $args['visual_product'] ) && is_a( $args['visual_product'], 'WC_Product' ) ? $args['visual_product'] : null;
$testing_url = isset( $args['testing_url'] ) ? $args['testing_url'] : home_url( '/testing/' );
$items       = array(
	array(
		'title' => __( 'Selected first', 'pepselect-child' ),
		'copy'  => __( 'A focused catalog that is easier to explore.', 'pepselect-child' ),
	),
	array(
		'title' => __( 'Filed to the batch', 'pepselect-child' ),
		'copy'  => __( 'Current product information stays close to the compound.', 'pepselect-child' ),
	),
	array(
		'title' => __( 'Nothing rushed at you', 'pepselect-child' ),
		'copy'  => __( 'Open the Quality Archive when you want the batch-level detail.', 'pepselect-child' ),
	),
);
?>
<section class="pepselect-home__section pepselect-home__why" aria-labelledby="pepselect-why-title">
	<div class="pepselect-home__inner pepselect-home__editorial-grid">
		<figure class="pepselect-home__editorial-visual">
			<?php if ( $product && $product->get_image_id() ) : ?>
				<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
					<?php
					echo wp_get_attachment_image(
						$product->get_image_id(),
						'woocommerce_single',
						false,
						array(
							'alt'      => $product->get_name(),
							'class'    => 'pepselect-home__editorial-image',
							'decoding' => 'async',
							'loading'  => 'lazy',
							'sizes'    => '(max-width: 767px) 88vw, 48vw',
						)
					); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WordPress generates escaped responsive image markup.
					?>
				</a>
			<?php else : ?>
				<div class="pepselect-home__visual-fallback"><span><?php esc_html_e( 'Pep Select Research Compounds', 'pepselect-child' ); ?></span></div>
			<?php endif; ?>
		</figure>

		<div class="pepselect-home__editorial-copy">
			<p class="pepselect-home__eyebrow"><?php esc_html_e( 'Why Pep Select', 'pepselect-child' ); ?></p>
			<h2 id="pepselect-why-title">
				<span><?php esc_html_e( 'Everyone has a COA.', 'pepselect-child' ); ?></span>
				<em><?php esc_html_e( 'Ours have a permanent address.', 'pepselect-child' ); ?></em>
			</h2>
			<p class="pepselect-home__lead"><?php esc_html_e( 'One document can be shown to anyone. We file records batch by batch, publicly, forever.', 'pepselect-child' ); ?></p>
			<ol class="pepselect-home__editorial-list">
				<?php foreach ( $items as $item ) : ?>
					<li>
						<div>
							<h3><?php echo esc_html( $item['title'] ); ?></h3>
							<p><?php echo esc_html( $item['copy'] ); ?></p>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>
			<a class="pepselect-home__button pepselect-home__button--primary" href="<?php echo esc_url( $testing_url ); ?>" aria-label="<?php esc_attr_e( 'Open the Pep Select Quality Archive and batch documentation', 'pepselect-child' ); ?>"><?php esc_html_e( 'Open the Quality Archive', 'pepselect-child' ); ?></a>
		</div>
	</div>
</section>

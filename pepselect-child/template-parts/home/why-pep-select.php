<?php
/**
 * Product-led Pep Select editorial section.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$product = isset( $args['visual_product'] ) && is_a( $args['visual_product'], 'WC_Product' ) ? $args['visual_product'] : null;
$items   = array(
	array(
		'title' => __( 'Focused catalog', 'pepselect-child' ),
		'copy'  => __( 'A deliberate selection that is easier to review.', 'pepselect-child' ),
	),
	array(
		'title' => __( 'Clear product details', 'pepselect-child' ),
		'copy'  => __( 'Strength, availability, price, and documentation paths in one place.', 'pepselect-child' ),
	),
	array(
		'title' => __( 'Records within reach', 'pepselect-child' ),
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
				<figcaption><?php echo esc_html( sprintf( __( 'Catalog image: %s', 'pepselect-child' ), $product->get_name() ) ); ?></figcaption>
			<?php else : ?>
				<div class="pepselect-home__visual-fallback"><span><?php esc_html_e( 'Pep Select Research Compounds', 'pepselect-child' ); ?></span></div>
			<?php endif; ?>
		</figure>

		<div class="pepselect-home__editorial-copy">
			<p class="pepselect-home__eyebrow"><?php esc_html_e( 'Why Pep Select', 'pepselect-child' ); ?></p>
			<h2 id="pepselect-why-title">
				<span><?php esc_html_e( 'Less guessing.', 'pepselect-child' ); ?></span>
				<em><?php esc_html_e( 'More to go on.', 'pepselect-child' ); ?></em>
			</h2>
			<p class="pepselect-home__lead"><?php esc_html_e( 'We keep product details, batch status, and available documentation close to the compound—not buried behind broad promises.', 'pepselect-child' ); ?></p>
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
		</div>
	</div>
</section>

<?php
/**
 * Product-led batch identity explainer.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$testing_url = isset( $args['testing_url'] ) ? $args['testing_url'] : home_url( '/testing/' );
$product     = isset( $args['identity_product'] ) && is_a( $args['identity_product'], 'WC_Product' ) ? $args['identity_product'] : null;
$identifiers = array(
	__( 'Compound', 'pepselect-child' ),
	__( 'Labeled Strength', 'pepselect-child' ),
	__( 'Batch Number', 'pepselect-child' ),
	__( 'Cap Color', 'pepselect-child' ),
	__( 'Crimp Color', 'pepselect-child' ),
	__( 'Current Status', 'pepselect-child' ),
);
?>
<section class="pepselect-home__section pepselect-home__identity" aria-labelledby="pepselect-identity-title">
	<div class="pepselect-home__inner pepselect-home__identity-grid">
		<div class="pepselect-home__identity-copy">
			<h2 id="pepselect-identity-title"><span><?php esc_html_e( 'Nice label.', 'pepselect-child' ); ?></span><em><?php esc_html_e( 'Now show me the batch.', 'pepselect-child' ); ?></em></h2>
			<p class="pepselect-home__lead"><?php esc_html_e( 'When a record is available, the vial identifiers and batch details should connect without making you play detective.', 'pepselect-child' ); ?></p>
			<a class="pepselect-home__button pepselect-home__button--primary" href="<?php echo esc_url( $testing_url ); ?>"><?php esc_html_e( 'Review Batch Records', 'pepselect-child' ); ?></a>
		</div>

		<div class="pepselect-home__identity-composition">
			<div class="pepselect-home__identity-product">
				<?php if ( $product && $product->get_image_id() ) : ?>
					<?php
					echo wp_get_attachment_image(
						$product->get_image_id(),
						'woocommerce_single',
						false,
						array(
							'alt'      => $product->get_name(),
							'class'    => 'pepselect-home__identity-image',
							'decoding' => 'async',
							'loading'  => 'lazy',
							'sizes'    => '(max-width: 767px) 46vw, 24vw',
						)
					); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WordPress generates escaped responsive image markup.
					?>
				<?php else : ?>
					<span><?php esc_html_e( 'Catalog image unavailable', 'pepselect-child' ); ?></span>
				<?php endif; ?>
			</div>
			<dl class="pepselect-home__identity-panel">
				<?php foreach ( $identifiers as $identifier ) : ?>
					<div>
						<dt><?php echo esc_html( $identifier ); ?></dt>
						<dd><?php esc_html_e( 'Recorded when available', 'pepselect-child' ); ?></dd>
					</div>
				<?php endforeach; ?>
			</dl>
		</div>
	</div>
</section>

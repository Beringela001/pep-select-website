<?php
/**
 * Compact accessible FAQ sourced from established homepage content.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$faq_url = isset( $args['faq_url'] ) ? $args['faq_url'] : home_url( '/faq/' );
$faqs    = isset( $args['faqs'] ) ? array_slice( (array) $args['faqs'], 0, 5 ) : array();
?>
<section class="pepselect-home__section pepselect-home__faq" aria-labelledby="pepselect-faq-title">
	<div class="pepselect-home__inner pepselect-home__faq-grid">
		<div class="pepselect-home__faq-heading">
			<h2 id="pepselect-faq-title"><?php esc_html_e( 'Questions before you order?', 'pepselect-child' ); ?></h2>
			<a class="pepselect-home__text-link" href="<?php echo esc_url( $faq_url ); ?>"><?php esc_html_e( 'Read All FAQs', 'pepselect-child' ); ?></a>
		</div>

		<?php if ( $faqs ) : ?>
			<div class="pepselect-home__accordion" data-pepselect-faq>
				<?php foreach ( $faqs as $index => $faq ) : ?>
					<?php
					$button_id = 'pepselect-faq-button-' . ( $index + 1 );
					$panel_id  = 'pepselect-faq-panel-' . ( $index + 1 );
					$is_open   = 0 === $index;
					?>
					<div class="pepselect-home__accordion-item">
						<h3>
							<button id="<?php echo esc_attr( $button_id ); ?>" type="button" aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $panel_id ); ?>">
								<span><?php echo esc_html( isset( $faq['question'] ) ? $faq['question'] : '' ); ?></span>
								<span class="pepselect-home__accordion-icon" aria-hidden="true"></span>
							</button>
						</h3>
						<div id="<?php echo esc_attr( $panel_id ); ?>" class="pepselect-home__accordion-panel" role="region" aria-labelledby="<?php echo esc_attr( $button_id ); ?>"<?php if ( ! $is_open ) : ?> hidden<?php endif; ?>>
							<p><?php echo esc_html( isset( $faq['answer'] ) ? $faq['answer'] : '' ); ?></p>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>

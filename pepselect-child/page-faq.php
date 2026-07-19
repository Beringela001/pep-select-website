<?php
/**
 * Coded presentation for the FAQ page (/faq/).
 *
 * WordPress selects this template automatically for the page with slug "faq".
 * All question and answer copy is defined in inc/faq-content.php so the
 * homepage FAQ and this page can draw from one approved source.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$faq_sections = function_exists( 'pepselect_child_get_faq_sections' ) ? pepselect_child_get_faq_sections() : array();

get_header();
?>
<main id="pepselect-faq-main" class="pepselect-faq" tabindex="-1">
	<section class="pepselect-faq__section">
		<div class="pepselect-faq__inner pepselect-faq__grid">
			<header class="pepselect-faq__heading">
				<h1 class="pepselect-faq__eyebrow"><?php esc_html_e( 'FAQ', 'pepselect-child' ); ?></h1>
				<p class="pepselect-faq__lead">
					<?php
					printf(
						/* translators: %s: support email address, rendered as a mailto link. */
						esc_html__( 'The questions we get most, answered plainly. For anything not here, email %s.', 'pepselect-child' ),
						'<a class="pepselect-faq__lead-link" href="mailto:support@pepselect.com">support@pepselect.com</a>'
					);
					?>
				</p>
			</header>

			<div class="pepselect-faq__content">
				<?php if ( $faq_sections ) : ?>
					<?php
					$question_number = 0;
					foreach ( $faq_sections as $section_index => $section ) :
						$section_title = isset( $section['title'] ) ? $section['title'] : '';
						$section_items = isset( $section['items'] ) ? (array) $section['items'] : array();

						if ( empty( $section_items ) ) {
							continue;
						}

						$section_slug = sanitize_title( $section_title ? $section_title : 'section-' . ( $section_index + 1 ) );
						?>
						<section class="pepselect-faq__group" aria-labelledby="pepselect-faq-group-<?php echo esc_attr( $section_slug ); ?>">
							<h2 id="pepselect-faq-group-<?php echo esc_attr( $section_slug ); ?>" class="pepselect-faq__group-title"><?php echo esc_html( $section_title ); ?></h2>

							<div class="pepselect-faq__accordion" data-pepselect-faq-page>
								<?php
								foreach ( $section_items as $item ) :
									$question = isset( $item['question'] ) ? $item['question'] : '';
									$answer   = isset( $item['answer'] ) ? $item['answer'] : '';

									if ( '' === $question || '' === $answer ) {
										continue;
									}

									$question_number++;
									$button_id = 'pepselect-faq-button-' . $question_number;
									$panel_id  = 'pepselect-faq-panel-' . $question_number;
									?>
									<div class="pepselect-faq__accordion-item">
										<h3>
											<button id="<?php echo esc_attr( $button_id ); ?>" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr( $panel_id ); ?>">
												<span><?php echo esc_html( $question ); ?></span>
												<span class="pepselect-faq__accordion-icon" aria-hidden="true"></span>
											</button>
										</h3>
										<div id="<?php echo esc_attr( $panel_id ); ?>" class="pepselect-faq__accordion-panel" role="region" aria-labelledby="<?php echo esc_attr( $button_id ); ?>" hidden>
											<?php echo wp_kses_post( wpautop( $answer ) ); ?>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</section>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();

<?php
/**
 * Presentation template for WordPress posts in the Guides category.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$archive_url = home_url( '/testing/' );
	$shop_url    = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
	?>
	<main id="content" class="pepselect-guide" tabindex="-1">
		<article <?php post_class( 'pepselect-guide__article' ); ?>>
			<header class="pepselect-guide__hero">
				<div class="pepselect-guide__hero-inner">
					<div class="pepselect-guide__hero-copy">
						<nav class="pepselect-guide__breadcrumbs" aria-label="Breadcrumb">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'pepselect-child' ); ?></a>
							<span aria-hidden="true">/</span>
							<span><?php esc_html_e( 'Guides', 'pepselect-child' ); ?></span>
						</nav>
						<p class="pepselect-guide__eyebrow"><?php esc_html_e( 'Research Documentation Guide', 'pepselect-child' ); ?></p>
						<h1><?php the_title(); ?></h1>
						<?php if ( has_excerpt() ) : ?>
							<p class="pepselect-guide__dek"><?php echo esc_html( get_the_excerpt() ); ?></p>
						<?php endif; ?>
						<div class="pepselect-guide__hero-actions">
							<a class="pepselect-guide__button pepselect-guide__button--primary" href="<?php echo esc_url( $archive_url ); ?>"><?php esc_html_e( 'Review COAs', 'pepselect-child' ); ?></a>
							<span><?php esc_html_e( 'Published batch records in one archive.', 'pepselect-child' ); ?></span>
						</div>
					</div>

					<div class="pepselect-guide__document" aria-label="Document review checklist">
						<div class="pepselect-guide__document-head">
							<span><?php esc_html_e( 'Document check', 'pepselect-child' ); ?></span>
							<span class="pepselect-guide__document-status"><?php esc_html_e( 'Batch-specific', 'pepselect-child' ); ?></span>
						</div>
						<dl>
							<div><dt><?php esc_html_e( 'Batch code', 'pepselect-child' ); ?></dt><dd><?php esc_html_e( 'Exact match', 'pepselect-child' ); ?></dd></div>
							<div><dt><?php esc_html_e( 'Vial + packaging', 'pepselect-child' ); ?></dt><dd><?php esc_html_e( 'Visible', 'pepselect-child' ); ?></dd></div>
							<div><dt><?php esc_html_e( 'Lab + report', 'pepselect-child' ); ?></dt><dd><?php esc_html_e( 'Cross-check', 'pepselect-child' ); ?></dd></div>
							<div><dt><?php esc_html_e( 'Release status', 'pepselect-child' ); ?></dt><dd><?php esc_html_e( 'Check last', 'pepselect-child' ); ?></dd></div>
						</dl>
						<p><?php esc_html_e( 'A single number never replaces the full record.', 'pepselect-child' ); ?></p>
					</div>
				</div>
			</header>

			<div class="pepselect-guide__body-shell">
				<aside class="pepselect-guide__rail" aria-label="On this page">
					<p><?php esc_html_e( 'On this page', 'pepselect-child' ); ?></p>
					<a href="#batch-match"><?php esc_html_e( 'Match the batch', 'pepselect-child' ); ?></a>
					<a href="#visual-record"><?php esc_html_e( 'See the record', 'pepselect-child' ); ?></a>
					<a href="#lab-source"><?php esc_html_e( 'Verify the source', 'pepselect-child' ); ?></a>
					<a href="#corrected-strength"><?php esc_html_e( 'Corrected strength', 'pepselect-child' ); ?></a>
					<a href="#rejected-batches"><?php esc_html_e( 'Rejected batches', 'pepselect-child' ); ?></a>
					<a href="#vendor-questions"><?php esc_html_e( 'Use the checklist', 'pepselect-child' ); ?></a>
				</aside>

				<div class="pepselect-guide__content">
					<?php the_content(); ?>

					<section class="pepselect-guide__final" aria-labelledby="pepselect-guide-final-title">
						<div>
							<p class="pepselect-guide__final-eyebrow"><?php esc_html_e( 'Your next check', 'pepselect-child' ); ?></p>
							<h2 id="pepselect-guide-final-title"><?php esc_html_e( 'Review the batch record before you choose.', 'pepselect-child' ); ?></h2>
							<p><?php esc_html_e( 'Open the Quality Archive to compare the laboratory, methods, results, and release status shown for each published record.', 'pepselect-child' ); ?></p>
						</div>
						<div class="pepselect-guide__final-actions">
							<a class="pepselect-guide__button pepselect-guide__button--primary" href="<?php echo esc_url( $archive_url ); ?>"><?php esc_html_e( 'Review COAs', 'pepselect-child' ); ?></a>
							<a class="pepselect-guide__button pepselect-guide__button--secondary" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Browse Research Compounds', 'pepselect-child' ); ?></a>
						</div>
					</section>

					<p class="pepselect-guide__notice"><?php esc_html_e( 'Pep Select products are sold for laboratory research use only. They are not for human consumption, medical use, or veterinary use. This guide explains how to review published research documentation and does not provide medical or laboratory-protocol advice.', 'pepselect-child' ); ?></p>
				</div>
			</div>
		</article>
	</main>
	<?php
endwhile;

get_footer();

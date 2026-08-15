<?php
/**
 * Batch-matching trust section.
 *
 * This is a presentation-only example. The Quality Archive remains the
 * source of truth for current records, testing status, and full reports.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$archive_url = home_url( '/testing/' );
$asset_url   = trailingslashit( get_stylesheet_directory_uri() ) . 'assets/images/why-pep-select/';
$matches     = array(
	array(
		'label' => __( 'Compound name', 'pepselect-child' ),
		'value' => __( 'Tesamorelin · 10 mg', 'pepselect-child' ),
	),
	array(
		'label' => __( 'Identifiers', 'pepselect-child' ),
		'value' => __( 'Blue cap · silver crimp', 'pepselect-child' ),
	),
	array(
		'label' => __( 'Which batch', 'pepselect-child' ),
		'value' => __( 'PSTES1071926GX', 'pepselect-child' ),
	),
);
?>
<section class="pepselect-home__section pepselect-home__why" aria-labelledby="pepselect-why-title">
	<div class="pepselect-home__inner pepselect-home__match-grid">
		<div class="pepselect-home__match-copy pepselect-home__match-copy--intro">
			<p class="pepselect-home__eyebrow"><?php esc_html_e( 'Why Pep Select is different', 'pepselect-child' ); ?></p>
			<h2 id="pepselect-why-title">
				<span><?php esc_html_e( 'Match the batch.', 'pepselect-child' ); ?></span>
				<em><?php esc_html_e( 'Match the vial.', 'pepselect-child' ); ?></em>
			</h2>
			<p class="pepselect-home__lead"><?php esc_html_e( 'You should be able to tell whether a COA belongs to the vial in front of you.', 'pepselect-child' ); ?></p>
		</div>

		<div class="pepselect-home__match-visual">
			<figure class="pepselect-home__vial-stage">
				<img
					class="pepselect-home__match-vials"
					src="<?php echo esc_url( $asset_url . 'tesamorelin-10mg-vial-batch.webp' ); ?>"
					width="1448"
					height="1086"
					loading="lazy"
					decoding="async"
					alt="<?php esc_attr_e( 'Front and reverse views of a Pep Select Tesamorelin 10 mg clear vial with blue cap, silver crimp, and batch PSTES1071926GX.', 'pepselect-child' ); ?>"
				>

				<svg class="pepselect-home__match-lines" viewBox="0 0 100 75" preserveAspectRatio="none" aria-hidden="true" focusable="false">
					<path d="M 16 11 L 24 11 L 34 19" />
					<circle cx="34" cy="19" r="0.8" />
					<path d="M 13 64 L 24 64 L 37 55" />
					<circle cx="37" cy="55" r="0.8" />
					<path d="M 88 38 L 80 38 L 64 38" />
					<circle cx="64" cy="38" r="0.8" />
				</svg>

				<span class="pepselect-home__visual-label pepselect-home__visual-label--hardware"><?php esc_html_e( 'Cap + crimp', 'pepselect-child' ); ?></span>
				<span class="pepselect-home__visual-label pepselect-home__visual-label--identity"><?php esc_html_e( 'Compound + strength', 'pepselect-child' ); ?></span>
				<span class="pepselect-home__visual-label pepselect-home__visual-label--batch"><?php esc_html_e( 'Batch', 'pepselect-child' ); ?></span>
			</figure>

			<div class="pepselect-home__coa-proof">
				<span class="pepselect-home__coa-proof-label"><?php esc_html_e( 'Independent laboratory record', 'pepselect-child' ); ?></span>
				<img
					src="<?php echo esc_url( $asset_url . 'tesamorelin-10mg-coa-source.webp' ); ?>"
					width="3400"
					height="4400"
					loading="lazy"
					decoding="async"
					alt="<?php esc_attr_e( 'Freedom Diagnostics certificate for Pep Select Tesamorelin 10 mg, batch PSTES1071926GX.', 'pepselect-child' ); ?>"
				>
			</div>
		</div>

		<div class="pepselect-home__match-copy pepselect-home__match-copy--details">
			<p class="pepselect-home__match-body"><?php esc_html_e( 'Pep Select sends the finished, labeled vial to an independent laboratory. The report identifies the compound and shows the vial and batch number, giving you three points to match with what is on your bench.', 'pepselect-child' ); ?></p>

			<dl class="pepselect-home__match-list">
				<?php foreach ( $matches as $index => $match ) : ?>
					<div>
						<dt><span aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span><?php echo esc_html( $match['label'] ); ?></dt>
						<dd><?php echo esc_html( $match['value'] ); ?></dd>
					</div>
				<?php endforeach; ?>
			</dl>

			<a class="pepselect-home__button pepselect-home__button--primary" href="<?php echo esc_url( $archive_url ); ?>"><?php esc_html_e( 'Open the Quality Archive', 'pepselect-child' ); ?></a>
		</div>
	</div>
</section>

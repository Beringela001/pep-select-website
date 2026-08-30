<?php
/**
 * Coded presentation for the About page (/about-us/).
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$product          = function_exists( 'pepselect_child_get_about_visual_product' ) ? pepselect_child_get_about_visual_product() : null;
$shop_url         = function_exists( 'pepselect_child_get_shop_url' ) ? pepselect_child_get_shop_url() : home_url( '/shop/' );
$testing_url      = home_url( '/testing/' );
$product_url      = is_a( $product, 'WC_Product' ) ? $product->get_permalink() : $shop_url;
$product_name     = is_a( $product, 'WC_Product' ) ? $product->get_name() : __( 'Current Pep Select compound', 'pepselect-child' );
$product_strength = is_a( $product, 'WC_Product' ) && function_exists( 'pepselect_child_get_product_strength_label' ) ? pepselect_child_get_product_strength_label( $product ) : '';
$image_id         = is_a( $product, 'WC_Product' ) ? $product->get_image_id() : 0;
$visual_label     = trim( $product_name . ( $product_strength ? ' ' . $product_strength : '' ) );

get_header();
?>
<main id="pepselect-about-main" class="pepselect-about" tabindex="-1">
	<section class="pepselect-about__hero" aria-labelledby="pepselect-about-title">
		<div class="pepselect-about__inner pepselect-about__hero-grid">
			<div class="pepselect-about__hero-copy">
				<p class="pepselect-about__eyebrow"><?php esc_html_e( 'About Pep Select', 'pepselect-child' ); ?></p>
				<h1 id="pepselect-about-title"><?php esc_html_e( 'Research compounds with records you can review.', 'pepselect-child' ); ?></h1>
				<p class="pepselect-about__lead"><?php esc_html_e( 'Pep Select gives researchers clear product details and batch-specific laboratory records before they order for laboratory or analytical work.', 'pepselect-child' ); ?></p>
				<div class="pepselect-about__actions">
					<a class="pepselect-about__button pepselect-about__button--primary" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Explore Compounds', 'pepselect-child' ); ?></a>
					<a class="pepselect-about__button pepselect-about__button--secondary" href="<?php echo esc_url( $testing_url ); ?>"><?php esc_html_e( 'Review COAs', 'pepselect-child' ); ?></a>
				</div>
			</div>

			<figure class="pepselect-about__product">
				<a class="pepselect-about__product-link" href="<?php echo esc_url( $product_url ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'View %s product details', 'pepselect-child' ), $visual_label ) ); ?>">
					<?php if ( $image_id ) : ?>
						<?php
						echo wp_get_attachment_image(
							$image_id,
							'large',
							false,
							array(
								'class'         => 'pepselect-about__product-image',
								'alt'           => sprintf( __( '%s vial from the current Pep Select product listing.', 'pepselect-child' ), $visual_label ),
								'loading'       => 'eager',
								'decoding'      => 'async',
								'fetchpriority' => 'high',
							)
						); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					<?php else : ?>
						<span class="pepselect-about__product-placeholder" aria-hidden="true"><?php esc_html_e( 'Pep Select', 'pepselect-child' ); ?></span>
					<?php endif; ?>
				</a>
				<figcaption>
					<span><?php esc_html_e( 'Current product listing', 'pepselect-child' ); ?></span>
					<strong><?php echo esc_html( $visual_label ); ?></strong>
				</figcaption>
			</figure>
		</div>
	</section>

	<section class="pepselect-about__section pepselect-about__purpose" aria-labelledby="pepselect-about-purpose-title">
		<div class="pepselect-about__inner pepselect-about__purpose-grid">
			<header>
				<p class="pepselect-about__eyebrow"><?php esc_html_e( 'Why Pep Select exists', 'pepselect-child' ); ?></p>
				<h2 id="pepselect-about-purpose-title"><?php esc_html_e( 'Clear records belong beside the catalog.', 'pepselect-child' ); ?></h2>
			</header>
			<div class="pepselect-about__purpose-copy">
				<p><?php esc_html_e( 'Pep Select exists to make peptide research more transparent and accessible. We keep the catalog focused and explain each product in plain language.', 'pepselect-child' ); ?></p>
				<p><?php esc_html_e( 'Each product page connects researchers with the documentation available for its batch. You can judge a specific record on its own terms before deciding what belongs in your research workflow.', 'pepselect-child' ); ?></p>
			</div>
		</div>
	</section>

	<section class="pepselect-about__section pepselect-about__standards" aria-labelledby="pepselect-about-standards-title">
		<div class="pepselect-about__inner">
			<header class="pepselect-about__section-heading">
				<p class="pepselect-about__eyebrow"><?php esc_html_e( 'Our approach', 'pepselect-child' ); ?></p>
				<h2 id="pepselect-about-standards-title"><?php esc_html_e( 'Documentation starts before release.', 'pepselect-child' ); ?></h2>
			</header>

			<div class="pepselect-about__standards-grid">
				<article class="pepselect-about__standard">
					<span class="pepselect-about__standard-number" aria-hidden="true">01</span>
					<h3><?php esc_html_e( 'Independent testing comes first', 'pepselect-child' ); ?></h3>
					<p><?php esc_html_e( 'Pep Select requires independent laboratory testing before a compound is released for sale. The resulting report belongs to the tested compound and batch.', 'pepselect-child' ); ?></p>
				</article>
				<article class="pepselect-about__standard">
					<span class="pepselect-about__standard-number" aria-hidden="true">02</span>
					<h3><?php esc_html_e( 'Batch records remain available', 'pepselect-child' ); ?></h3>
					<p><?php esc_html_e( 'The Quality Archive keeps documented batch records accessible after a batch sells out. Researchers can search by compound and review the record that matches their vial.', 'pepselect-child' ); ?></p>
				</article>
			</div>

			<div class="pepselect-about__ruo">
				<p class="pepselect-about__ruo-label"><?php esc_html_e( 'Research-use boundary', 'pepselect-child' ); ?></p>
				<p><?php esc_html_e( 'All catalog products are for laboratory research, identification, or analytical work. They are not for human consumption.', 'pepselect-child' ); ?></p>
			</div>
		</div>
	</section>

	<section class="pepselect-about__section pepselect-about__trace" aria-labelledby="pepselect-about-trace-title">
		<div class="pepselect-about__inner pepselect-about__trace-grid">
			<div class="pepselect-about__trace-copy">
				<p class="pepselect-about__eyebrow"><?php esc_html_e( 'Batch traceability', 'pepselect-child' ); ?></p>
				<h2 id="pepselect-about-trace-title"><?php esc_html_e( 'The vial and its record should agree.', 'pepselect-child' ); ?></h2>
				<p><?php esc_html_e( 'Compound name, labeled strength, batch number, and packaging identifiers help connect a physical vial to the correct laboratory record.', 'pepselect-child' ); ?></p>
			</div>
			<div class="pepselect-about__trace-details">
				<dl>
					<div><dt><?php esc_html_e( 'Compound', 'pepselect-child' ); ?></dt><dd><?php esc_html_e( 'Name on vial and report', 'pepselect-child' ); ?></dd></div>
					<div><dt><?php esc_html_e( 'Strength', 'pepselect-child' ); ?></dt><dd><?php esc_html_e( 'Labeled research quantity', 'pepselect-child' ); ?></dd></div>
					<div><dt><?php esc_html_e( 'Batch', 'pepselect-child' ); ?></dt><dd><?php esc_html_e( 'Identifier for the tested release', 'pepselect-child' ); ?></dd></div>
					<div><dt><?php esc_html_e( 'Packaging', 'pepselect-child' ); ?></dt><dd><?php esc_html_e( 'Cap, crimp, and label details', 'pepselect-child' ); ?></dd></div>
				</dl>
				<a class="pepselect-about__text-link" href="<?php echo esc_url( $testing_url ); ?>"><?php esc_html_e( 'Open the Quality Archive', 'pepselect-child' ); ?><span aria-hidden="true">&rarr;</span></a>
			</div>
		</div>
	</section>

	<section class="pepselect-about__section pepselect-about__closing" aria-labelledby="pepselect-about-closing-title">
		<div class="pepselect-about__inner pepselect-about__closing-inner">
			<div>
				<p class="pepselect-about__eyebrow"><?php esc_html_e( 'Start with the record', 'pepselect-child' ); ?></p>
				<h2 id="pepselect-about-closing-title"><?php esc_html_e( 'Review the documentation before you choose a compound.', 'pepselect-child' ); ?></h2>
				<p><?php esc_html_e( 'Browse the catalog for current availability and product details. Open the Quality Archive for batch-specific COAs and testing history.', 'pepselect-child' ); ?></p>
			</div>
			<div class="pepselect-about__actions">
				<a class="pepselect-about__button pepselect-about__button--primary" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Browse Research Compounds', 'pepselect-child' ); ?></a>
				<a class="pepselect-about__button pepselect-about__button--secondary" href="<?php echo esc_url( $testing_url ); ?>"><?php esc_html_e( 'View Testing History', 'pepselect-child' ); ?></a>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();

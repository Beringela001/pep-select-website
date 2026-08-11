<?php
/**
 * Compound description block for single product pages (WEB-2E).
 *
 * Renders the approved hybrid-register content: description with inline
 * superscripts, Research context bullets, a
 * collapsed source list, and the locked Intended Use and FDA blocks. When a
 * product has no library entry, the standard product description shows with
 * the legal blocks still enforced.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$product = isset( $args['product'] ) ? $args['product'] : null;

if ( ! is_a( $product, 'WC_Product' ) ) {
	return;
}

$content   = function_exists( 'pepselect_child_get_compound_content' ) ? pepselect_child_get_compound_content( $product ) : null;
$source_id = 'pepselect-compound-sources-' . $product->get_id();
?>
<section class="pepselect-compound">
	<?php if ( $content ) : ?>
		<p class="pepselect-compound__eyebrow"><?php esc_html_e( 'Description', 'pepselect-child' ); ?></p>
		<p class="pepselect-compound__description"><?php echo wp_kses_post( $content['description'] ); ?></p>

		<?php if ( ! empty( $content['context'] ) ) : ?>
			<div class="pepselect-compound__context">
				<h3><?php esc_html_e( 'Research context', 'pepselect-child' ); ?></h3>
				<ul>
					<?php foreach ( $content['context'] as $item ) : ?>
						<li><?php echo esc_html( $item['text'] ); ?><sup><?php echo (int) $item['ref']; ?></sup></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $content['sources'] ) ) : ?>
			<button type="button" class="pepselect-compound__sources-toggle" data-pepselect-sources-toggle aria-expanded="false" aria-controls="<?php echo esc_attr( $source_id ); ?>">
				<span><?php echo esc_html( sprintf( _n( 'View the %d source', 'View the %d sources', count( $content['sources'] ), 'pepselect-child' ), count( $content['sources'] ) ) ); ?></span>
				<span class="pepselect-compound__chevron" aria-hidden="true">&#9662;</span>
			</button>
			<div id="<?php echo esc_attr( $source_id ); ?>" class="pepselect-compound__sources" hidden>
				<?php if ( ! empty( $content['specs']['CAS'] ) && '[SUPPLY FROM COA]' !== $content['specs']['CAS'] ) : ?>
					<p class="pepselect-compound__cas"><?php esc_html_e( 'CAS number', 'pepselect-child' ); ?> <span><?php echo esc_html( $content['specs']['CAS'] ); ?></span></p>
				<?php endif; ?>
				<ol>
					<?php foreach ( $content['sources'] as $source ) : ?>
						<li><?php echo esc_html( $source ); ?></li>
					<?php endforeach; ?>
				</ol>
			</div>
		<?php endif; ?>
	<?php else : ?>
		<div class="pepselect-compound__description"><?php the_content(); ?></div>
	<?php endif; ?>

	<div class="pepselect-compound__intended">
		<p class="pepselect-compound__intended-label"><?php esc_html_e( 'Intended use', 'pepselect-child' ); ?></p>
		<p><?php esc_html_e( 'Research Use Only. Supplied strictly for laboratory research. Not for use in humans or animals, and not for use in foods, drugs, supplements, or diagnostics.', 'pepselect-child' ); ?></p>
	</div>

	<p class="pepselect-compound__disclaimer"><?php esc_html_e( 'These statements have not been evaluated by the Food and Drug Administration. No claims are made regarding the diagnosis, treatment, cure, or prevention of any disease. Use is restricted to qualified research professionals.', 'pepselect-child' ); ?></p>
</section>

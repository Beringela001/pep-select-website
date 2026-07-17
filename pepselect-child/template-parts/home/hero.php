<?php
/**
 * WEB-2C homepage hero.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$shop_url    = isset( $args['shop_url'] ) ? $args['shop_url'] : home_url( '/shop/' );
$testing_url = isset( $args['testing_url'] ) ? $args['testing_url'] : home_url( '/testing/' );
?>
<section class="pepselect-home__section pepselect-home__hero" aria-labelledby="pepselect-home-title">
	<div class="pepselect-home__inner pepselect-home__hero-grid">
		<div class="pepselect-home__hero-copy">
			<p class="pepselect-home__eyebrow"><?php esc_html_e( 'Research Compounds', 'pepselect-child' ); ?></p>
			<h1 id="pepselect-home-title"><?php esc_html_e( 'Match the vial. Match the batch.', 'pepselect-child' ); ?></h1>
			<p class="pepselect-home__lead"><?php esc_html_e( 'Pep Select is built around clear identifiers, current batch status, and access to available testing history. Explore the catalog, or review the records first.', 'pepselect-child' ); ?></p>
			<div class="pepselect-home__actions">
				<a class="pepselect-home__button pepselect-home__button--primary" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Explore Compounds', 'pepselect-child' ); ?></a>
				<a class="pepselect-home__button pepselect-home__button--secondary" href="<?php echo esc_url( $testing_url ); ?>"><?php esc_html_e( 'Review COAs', 'pepselect-child' ); ?></a>
			</div>
		</div>
		<div class="pepselect-home__hero-visual" aria-hidden="true">
			<div class="pepselect-home__hero-field">
				<span class="pepselect-home__hero-orbit pepselect-home__hero-orbit--one"></span>
				<span class="pepselect-home__hero-orbit pepselect-home__hero-orbit--two"></span>
				<span class="pepselect-home__hero-rule"></span>
			</div>
		</div>
	</div>
</section>

<?php
/**
 * WEB-2C final storefront decision path.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$shop_url    = isset( $args['shop_url'] ) ? $args['shop_url'] : home_url( '/shop/' );
$testing_url = isset( $args['testing_url'] ) ? $args['testing_url'] : home_url( '/testing/' );
?>
<section class="pepselect-home__section pepselect-home__final" aria-labelledby="pepselect-final-title">
	<div class="pepselect-home__inner">
		<div class="pepselect-home__final-panel">
			<div>
				<h2 id="pepselect-final-title">
					<span><?php esc_html_e( 'Find the compound.', 'pepselect-child' ); ?></span>
					<em><?php esc_html_e( 'Keep the record close.', 'pepselect-child' ); ?></em>
				</h2>
				<p><?php esc_html_e( 'Explore the current catalog, or go directly to the Pep Select Quality Archive.', 'pepselect-child' ); ?></p>
			</div>
			<div class="pepselect-home__actions">
				<a class="pepselect-home__button pepselect-home__button--cyan" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Explore Compounds', 'pepselect-child' ); ?></a>
				<a class="pepselect-home__button pepselect-home__button--outline-light" href="<?php echo esc_url( $testing_url ); ?>"><?php esc_html_e( 'Review COAs', 'pepselect-child' ); ?></a>
			</div>
		</div>
	</div>
</section>

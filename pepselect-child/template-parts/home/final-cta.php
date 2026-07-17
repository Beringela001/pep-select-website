<?php
/**
 * WEB-2C final decision path.
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
	<div class="pepselect-home__inner pepselect-home__final-panel">
		<div class="pepselect-home__section-copy">
			<p class="pepselect-home__section-index" aria-hidden="true">08</p>
			<h2 id="pepselect-final-title"><?php esc_html_e( 'Start with the compound or start with the record.', 'pepselect-child' ); ?></h2>
			<p><?php esc_html_e( 'Browse current research compounds, or review available COAs and testing history before you continue.', 'pepselect-child' ); ?></p>
		</div>
		<div class="pepselect-home__actions">
			<a class="pepselect-home__button pepselect-home__button--cyan" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Explore Compounds', 'pepselect-child' ); ?></a>
			<a class="pepselect-home__button pepselect-home__button--outline-light" href="<?php echo esc_url( $testing_url ); ?>"><?php esc_html_e( 'Review COAs', 'pepselect-child' ); ?></a>
		</div>
	</div>
</section>

<?php
/**
 * WEB-2C transparency section.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$testing_url = isset( $args['testing_url'] ) ? $args['testing_url'] : home_url( '/testing/' );
?>
<section class="pepselect-home__section pepselect-home__transparency" aria-labelledby="pepselect-transparency-title">
	<div class="pepselect-home__inner pepselect-home__transparency-panel">
		<p class="pepselect-home__section-index" aria-hidden="true">06</p>
		<div class="pepselect-home__section-copy">
			<h2 id="pepselect-transparency-title"><?php esc_html_e( 'A record should show what happened.', 'pepselect-child' ); ?></h2>
			<p><?php esc_html_e( 'Review the status and available documentation, then keep every conclusion tied to that specific batch.', 'pepselect-child' ); ?></p>
		</div>
		<a class="pepselect-home__button pepselect-home__button--secondary" href="<?php echo esc_url( $testing_url ); ?>"><?php esc_html_e( 'Review COAs', 'pepselect-child' ); ?></a>
	</div>
</section>

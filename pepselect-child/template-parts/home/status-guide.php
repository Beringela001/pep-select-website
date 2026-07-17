<?php
/**
 * WEB-2C status guidance without hard-coded plugin labels.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$testing_url = isset( $args['testing_url'] ) ? $args['testing_url'] : home_url( '/testing/' );
?>
<section class="pepselect-home__section pepselect-home__status" aria-labelledby="pepselect-status-title">
	<div class="pepselect-home__inner pepselect-home__status-layout">
		<p class="pepselect-home__section-index" aria-hidden="true">05</p>
		<div class="pepselect-home__section-copy">
			<h2 id="pepselect-status-title"><?php esc_html_e( 'Status before conclusions.', 'pepselect-child' ); ?></h2>
			<p><?php esc_html_e( 'A status tells you where the record stands. Open the batch page for the details and documentation behind it.', 'pepselect-child' ); ?></p>
		</div>
		<a class="pepselect-home__text-link" href="<?php echo esc_url( $testing_url ); ?>"><?php esc_html_e( 'View Testing History', 'pepselect-child' ); ?></a>
	</div>
</section>

<?php
/**
 * WEB-2C COA Archive handoff.
 *
 * The stable COA Archive does not expose a supported generic homepage record
 * projection, so this template deliberately avoids direct record queries.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$testing_url = isset( $args['testing_url'] ) ? $args['testing_url'] : home_url( '/testing/' );
?>
<section class="pepselect-home__section pepselect-home__testing" aria-labelledby="pepselect-testing-title">
	<div class="pepselect-home__inner pepselect-home__testing-grid">
		<div class="pepselect-home__section-copy">
			<p class="pepselect-home__section-index" aria-hidden="true">03</p>
			<h2 id="pepselect-testing-title"><?php esc_html_e( 'Read the current record. Keep the history in view.', 'pepselect-child' ); ?></h2>
			<p><?php esc_html_e( 'Open a specific batch record to review the status and test details the COA Archive provides. Continue to the archive for available reports and historical detail.', 'pepselect-child' ); ?></p>
		</div>
		<div class="pepselect-home__archive-handoff">
			<span class="pepselect-home__archive-rule" aria-hidden="true"></span>
			<a class="pepselect-home__button pepselect-home__button--light" href="<?php echo esc_url( $testing_url ); ?>"><?php esc_html_e( 'View Testing History', 'pepselect-child' ); ?></a>
		</div>
	</div>
</section>

<?php
/**
 * WEB-2C identifier system.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$testing_url = isset( $args['testing_url'] ) ? $args['testing_url'] : home_url( '/testing/' );
$identifiers = array(
	__( 'Compound', 'pepselect-child' ),
	__( 'Labeled Strength', 'pepselect-child' ),
	__( 'Batch Number', 'pepselect-child' ),
	__( 'Cap Color', 'pepselect-child' ),
	__( 'Crimp Color', 'pepselect-child' ),
	__( 'Packaging Identity', 'pepselect-child' ),
	__( 'Testing Status', 'pepselect-child' ),
	__( 'Laboratory Documentation', 'pepselect-child' ),
);
?>
<section class="pepselect-home__section pepselect-home__identifiers" aria-labelledby="pepselect-identifiers-title">
	<div class="pepselect-home__inner pepselect-home__split">
		<div class="pepselect-home__section-copy">
			<p class="pepselect-home__section-index" aria-hidden="true">02</p>
			<h2 id="pepselect-identifiers-title"><?php esc_html_e( 'Every identifier should point to the same record.', 'pepselect-child' ); ?></h2>
			<p><?php esc_html_e( 'The record behind a vial may include its labeled strength, batch number, cap, crimp, current status, and available documentation. Read the identifiers together, not one in isolation.', 'pepselect-child' ); ?></p>
			<a class="pepselect-home__text-link" href="<?php echo esc_url( $testing_url ); ?>"><?php esc_html_e( 'Review Batch Documentation', 'pepselect-child' ); ?></a>
		</div>
		<dl class="pepselect-home__identifier-ledger">
			<?php foreach ( $identifiers as $index => $identifier ) : ?>
				<div class="pepselect-home__identifier-row" data-identifier-state="optional">
					<span class="pepselect-home__identifier-index" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span>
					<dt><?php echo esc_html( $identifier ); ?></dt>
					<dd><?php esc_html_e( 'Recorded when available', 'pepselect-child' ); ?></dd>
				</div>
			<?php endforeach; ?>
		</dl>
	</div>
</section>

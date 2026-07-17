<?php
/**
 * WEB-2C research-use and support section.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$faq_url     = isset( $args['faq_url'] ) ? $args['faq_url'] : home_url( '/faq/' );
$contact_url = isset( $args['contact_url'] ) ? $args['contact_url'] : home_url( '/contact/' );
?>
<section class="pepselect-home__section pepselect-home__support" aria-labelledby="pepselect-support-title">
	<div class="pepselect-home__inner pepselect-home__split">
		<div class="pepselect-home__section-copy">
			<p class="pepselect-home__section-index" aria-hidden="true">07</p>
			<h2 id="pepselect-support-title"><?php esc_html_e( 'For laboratory research and analytical work.', 'pepselect-child' ); ?></h2>
		</div>
		<div class="pepselect-home__support-copy">
			<p><?php esc_html_e( 'Pep Select compounds are presented for legitimate laboratory research and analytical purposes. Review the FAQ for common ordering and documentation questions, or contact Pep Select support when you need help with a product, order, or record.', 'pepselect-child' ); ?></p>
			<div class="pepselect-home__actions">
				<a class="pepselect-home__text-link" href="<?php echo esc_url( $faq_url ); ?>"><?php esc_html_e( 'Read the FAQ', 'pepselect-child' ); ?></a>
				<a class="pepselect-home__text-link" href="<?php echo esc_url( $contact_url ); ?>"><?php esc_html_e( 'Contact Support', 'pepselect-child' ); ?></a>
			</div>
		</div>
	</div>
</section>

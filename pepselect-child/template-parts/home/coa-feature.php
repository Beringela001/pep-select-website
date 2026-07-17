<?php
/**
 * Concentrated Quality Archive navigation feature.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$testing_url = isset( $args['testing_url'] ) ? $args['testing_url'] : home_url( '/testing/' );
$actions     = array(
	__( 'Search by compound', 'pepselect-child' ),
	__( 'Follow batch history', 'pepselect-child' ),
	__( 'Open the full record', 'pepselect-child' ),
);
?>
<section class="pepselect-home__section pepselect-home__archive" aria-labelledby="pepselect-archive-title">
	<div class="pepselect-home__inner">
		<div class="pepselect-home__archive-panel">
			<div class="pepselect-home__archive-copy">
				<p class="pepselect-home__eyebrow pepselect-home__eyebrow--dark"><?php esc_html_e( 'Pep Select Quality Archive', 'pepselect-child' ); ?></p>
				<h2 id="pepselect-archive-title">
					<span><?php esc_html_e( 'See what the label', 'pepselect-child' ); ?></span>
					<em><?php echo wp_kses_post( __( 'can&rsquo;t tell you.', 'pepselect-child' ) ); ?></em>
				</h2>
				<p><?php esc_html_e( 'Search by compound, follow current and previous records, and open the documentation available for each release.', 'pepselect-child' ); ?></p>
				<p class="pepselect-home__archive-signature"><?php esc_html_e( 'Match the vial. Match the batch.', 'pepselect-child' ); ?></p>
				<a class="pepselect-home__button pepselect-home__button--cyan" href="<?php echo esc_url( $testing_url ); ?>"><?php esc_html_e( 'Open the Quality Archive', 'pepselect-child' ); ?></a>
			</div>
			<ol class="pepselect-home__archive-actions">
				<?php foreach ( $actions as $action ) : ?>
					<li><span aria-hidden="true"></span><?php echo esc_html( $action ); ?></li>
				<?php endforeach; ?>
			</ol>
		</div>
	</div>
</section>

<?php
/**
 * Default coded site footer.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$logo_html    = pepselect_child_get_footer_logo_html();
$support_mail = 'support@pepselect.com';
$testing_url  = home_url( '/testing/' );
?>
<footer id="pepselect-site-footer" class="pepselect-site-footer" data-pepselect-footer-preview>
	<div class="pepselect-footer__inner">
		<div class="pepselect-footer__primary">
			<section class="pepselect-footer__brand" aria-label="<?php echo esc_attr__( 'Pep Select information', 'pepselect-child' ); ?>">
				<?php if ( '' !== $logo_html ) : ?>
					<a class="pepselect-footer__logo-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr__( 'Pep Select home', 'pepselect-child' ); ?>">
						<?php echo $logo_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Core generates escaped attachment markup. ?>
					</a>
				<?php else : ?>
					<a class="pepselect-footer__site-name" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php echo esc_html( get_bloginfo( 'name' ) ); ?>
					</a>
				<?php endif; ?>

				<div class="pepselect-footer__research-copy">
					<p><?php esc_html_e( 'All products sold on this website are intended for research and identification purposes only. These products are not intended for human dosing, injection, or ingestion.', 'pepselect-child' ); ?></p>
					<p>
						<?php esc_html_e( 'Pep Select requires independent laboratory testing before a compound is released for sale.', 'pepselect-child' ); ?>
						<a href="<?php echo esc_url( $testing_url ); ?>"><?php esc_html_e( 'Review batch-specific Certificates of Analysis in the Quality Archive.', 'pepselect-child' ); ?></a>
					</p>
				</div>

				<p class="pepselect-footer__support">
					<?php esc_html_e( 'Support:', 'pepselect-child' ); ?>
					<a href="<?php echo esc_url( 'mailto:' . $support_mail ); ?>"><?php echo esc_html( $support_mail ); ?></a>
				</p>
			</section>

			<?php get_template_part( 'template-parts/footer/link-groups' ); ?>
		</div>

		<?php get_template_part( 'template-parts/footer/disclaimer' ); ?>

		<div class="pepselect-footer__bottom">
			<p>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php esc_html_e( 'Pep Select. All rights reserved.', 'pepselect-child' ); ?></p>
		</div>
	</div>
</footer>

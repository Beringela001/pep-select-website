<?php
/**
 * Coded footer navigation groups.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$link_groups = pepselect_child_get_footer_link_groups();
?>
<nav class="pepselect-footer__links" aria-label="<?php echo esc_attr__( 'Footer', 'pepselect-child' ); ?>">
	<?php foreach ( $link_groups as $group_title => $links ) : ?>
		<section class="pepselect-footer__link-group" aria-labelledby="pepselect-footer-<?php echo esc_attr( sanitize_title( $group_title ) ); ?>">
			<h2 id="pepselect-footer-<?php echo esc_attr( sanitize_title( $group_title ) ); ?>"><?php echo esc_html( $group_title ); ?></h2>
			<ul>
				<?php foreach ( $links as $link ) : ?>
					<li><a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</section>
	<?php endforeach; ?>
</nav>

<?php
/**
 * Primary coded navigation preview.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$navigation_items = pepselect_child_get_header_navigation_items();
?>
<nav id="pepselect-primary-navigation" class="pepselect-header__nav-panel" aria-label="<?php echo esc_attr__( 'Primary navigation', 'pepselect-child' ); ?>" data-pepselect-menu-panel>
	<ul class="pepselect-header__nav-list">
		<?php foreach ( $navigation_items as $navigation_item ) : ?>
			<li class="pepselect-header__nav-item">
				<a
					class="pepselect-header__nav-link<?php echo $navigation_item['current'] ? ' is-current' : ''; ?>"
					href="<?php echo esc_url( $navigation_item['url'] ); ?>"
					<?php if ( $navigation_item['current'] ) : ?>
						aria-current="page"
					<?php endif; ?>
				>
					<?php echo esc_html( $navigation_item['label'] ); ?>
				</a>
			</li>
		<?php endforeach; ?>
		<li class="pepselect-header__nav-item pepselect-header__mobile-rewards">
			<a class="pepselect-header__nav-link" href="<?php echo esc_url( pepselect_child_get_rewards_url() ); ?>">
				<?php esc_html_e( 'Rewards', 'pepselect-child' ); ?>
			</a>
		</li>
	</ul>
</nav>

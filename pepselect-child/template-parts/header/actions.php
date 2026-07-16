<?php
/**
 * Rewards, account, cart, and mobile-menu controls.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$rewards_output = pepselect_child_get_rewards_output();
$cart_count     = pepselect_child_get_cart_count();
?>
<div class="pepselect-header__actions">
	<a class="pepselect-header__action pepselect-header__action--rewards" href="<?php echo esc_url( pepselect_child_get_rewards_url() ); ?>" aria-label="<?php echo esc_attr__( 'Rewards', 'pepselect-child' ); ?>">
		<svg aria-hidden="true" focusable="false" viewBox="0 0 24 24" width="20" height="20">
			<path d="m12 3 2.7 5.5 6.1.9-4.4 4.3 1 6.1-5.4-2.9-5.4 2.9 1-6.1-4.4-4.3 6.1-.9L12 3Z"></path>
		</svg>
		<span class="pepselect-header__action-label"><?php esc_html_e( 'Rewards', 'pepselect-child' ); ?></span>
		<?php if ( '' !== $rewards_output ) : ?>
			<span class="pepselect-header__rewards-value"><?php echo wp_kses_post( $rewards_output ); ?></span>
		<?php endif; ?>
	</a>

	<a class="pepselect-header__action" href="<?php echo esc_url( pepselect_child_get_account_url() ); ?>" aria-label="<?php echo esc_attr__( 'My Account', 'pepselect-child' ); ?>">
		<svg aria-hidden="true" focusable="false" viewBox="0 0 24 24" width="20" height="20">
			<circle cx="12" cy="8" r="4"></circle>
			<path d="M4 21a8 8 0 0 1 16 0"></path>
		</svg>
		<span class="pepselect-header__action-label"><?php esc_html_e( 'My Account', 'pepselect-child' ); ?></span>
	</a>

	<?php if ( shortcode_exists( 'xoo_wsc_cart' ) ) : ?>
		<div class="pepselect-header__side-cart" role="group" aria-label="<?php echo esc_attr__( 'Cart', 'pepselect-child' ); ?>">
			<?php echo do_shortcode( '[xoo_wsc_cart]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Confirmed plugin shortcode owns its escaped output. ?>
		</div>
	<?php else : ?>
		<a class="pepselect-header__action pepselect-header__action--cart" href="<?php echo esc_url( pepselect_child_get_cart_url() ); ?>" aria-label="<?php echo esc_attr( sprintf( _n( 'Cart with %d item', 'Cart with %d items', $cart_count, 'pepselect-child' ), $cart_count ) ); ?>">
			<svg aria-hidden="true" focusable="false" viewBox="0 0 24 24" width="20" height="20">
				<circle cx="9" cy="20" r="1"></circle>
				<circle cx="18" cy="20" r="1"></circle>
				<path d="M3 4h2l2.3 10.2a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 2-1.6L21 7H6"></path>
			</svg>
			<span class="pepselect-header__action-label"><?php esc_html_e( 'Cart', 'pepselect-child' ); ?></span>
			<span class="pepselect-header__cart-count" aria-hidden="true"><?php echo esc_html( number_format_i18n( $cart_count ) ); ?></span>
		</a>
	<?php endif; ?>

	<button class="pepselect-header__menu-toggle" type="button" aria-controls="pepselect-primary-navigation" aria-expanded="false" data-pepselect-menu-toggle>
		<svg aria-hidden="true" focusable="false" viewBox="0 0 24 24" width="24" height="24">
			<path d="M4 7h16M4 12h16M4 17h16"></path>
		</svg>
		<span class="screen-reader-text"><?php esc_html_e( 'Open primary navigation', 'pepselect-child' ); ?></span>
	</button>
</div>

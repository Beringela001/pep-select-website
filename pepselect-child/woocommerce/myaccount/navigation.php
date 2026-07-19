<?php
/**
 * My Account navigation (Pep Select custom).
 *
 * Overrides WooCommerce's default account navigation. Endpoint routing,
 * labels, and current-item logic stay native (wc_get_account_menu_items,
 * wc_get_account_endpoint_url); this template only supplies our markup, icons,
 * and classes. Do not hardcode endpoints, so plugin-added items (YITH cash
 * back) keep appearing automatically.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package PepSelectChild
 * @version 9.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );

/**
 * Map account endpoints to inline SVG icons. Unknown endpoints fall back to a
 * neutral dot, so nothing breaks if a plugin adds a new item.
 */
$pepselect_account_icons = array(
	'dashboard'       => '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="3" width="7" height="7" rx="1.5"></rect><rect x="3" y="14" width="7" height="7" rx="1.5"></rect><rect x="14" y="14" width="7" height="7" rx="1.5"></rect></svg>',
	'orders'          => '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path d="M21 8 12 3 3 8v8l9 5 9-5V8Z"></path><path d="m3 8 9 5 9-5"></path><path d="M12 13v8"></path></svg>',
	'downloads'       => '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path d="M12 3v12"></path><path d="m7 12 5 5 5-5"></path><path d="M5 21h14"></path></svg>',
	'edit-address'    => '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>',
	'edit-account'    => '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg>',
	'cash-back'       => '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v10"></path><path d="M15 9.5c0-1.4-1.3-2.5-3-2.5s-3 1-3 2.3c0 3 6 1.5 6 4.7 0 1.4-1.3 2.5-3 2.5s-3-1.1-3-2.5"></path></svg>',
	'customer-logout' => '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><path d="m16 17 5-5-5-5"></path><path d="M21 12H9"></path></svg>',
);
?>

<nav class="pepselect-account__nav woocommerce-MyAccount-navigation" aria-label="<?php esc_attr_e( 'Account pages', 'pepselect-child' ); ?>">
	<p class="pepselect-account__nav-title"><?php esc_html_e( 'My Account', 'pepselect-child' ); ?></p>
	<ul>
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
			<?php
			$icon    = isset( $pepselect_account_icons[ $endpoint ] ) ? $pepselect_account_icons[ $endpoint ] : '<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><circle cx="12" cy="12" r="3"></circle></svg>';
			$is_here = wc_is_current_account_menu_item( $endpoint );
			?>
			<li class="<?php echo esc_attr( wc_get_account_menu_item_classes( $endpoint ) ); ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" <?php echo $is_here ? 'aria-current="page"' : ''; ?>>
					<span class="pepselect-account__nav-icon"><?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span class="pepselect-account__nav-label"><?php echo esc_html( $label ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>

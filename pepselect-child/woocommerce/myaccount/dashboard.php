<?php
/**
 * My Account Dashboard (Pep Select custom).
 *
 * Replaces WooCommerce's default intro paragraph with a stat-card summary and
 * quick links. Cash-back balance is read from YITH Points via a theme helper
 * that converts points to dollars (1 point = $0.01); orders count is native.
 * All destinations use wc_get_account_endpoint_url so routing stays correct.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package PepSelectChild
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pepselect_first_name = $current_user->first_name ? $current_user->first_name : $current_user->display_name;

// Cash-back balance in dollars (YITH points x $0.01), via theme helper.
$pepselect_cashback = function_exists( 'pepselect_child_get_cashback_display' ) ? pepselect_child_get_cashback_display() : null;

// Total completed/processing orders for this customer.
$pepselect_order_count = 0;
if ( function_exists( 'wc_get_customer_order_count' ) ) {
	$pepselect_order_count = wc_get_customer_order_count( get_current_user_id() );
}

$pepselect_orders_url    = wc_get_account_endpoint_url( 'orders' );
$pepselect_cashback_url  = wc_get_account_endpoint_url( 'cash-back' );
$pepselect_address_url   = wc_get_account_endpoint_url( 'edit-address' );
$pepselect_account_url   = wc_get_account_endpoint_url( 'edit-account' );
$pepselect_downloads_url = wc_get_account_endpoint_url( 'downloads' );
?>

<div class="pepselect-account__dash">
	<header class="pepselect-account__dash-head">
		<h1 class="pepselect-account__title">
			<?php
			/* translators: %s: customer first name. */
			printf( esc_html__( 'Welcome back, %s.', 'pepselect-child' ), esc_html( $pepselect_first_name ) );
			?>
		</h1>
		<p class="pepselect-account__lead"><?php esc_html_e( 'Your orders, cash back, and account details, all in one place.', 'pepselect-child' ); ?></p>
	</header>

	<div class="pepselect-account__stats">
		<a class="pepselect-account__stat pepselect-account__stat--accent" href="<?php echo esc_url( $pepselect_cashback_url ); ?>">
			<span class="pepselect-account__stat-label"><?php esc_html_e( 'Cash back', 'pepselect-child' ); ?></span>
			<span class="pepselect-account__stat-value"><?php echo esc_html( null !== $pepselect_cashback ? $pepselect_cashback['balance_formatted'] : '$0.00' ); ?></span>
			<span class="pepselect-account__stat-note"><?php esc_html_e( 'Ready to apply at checkout', 'pepselect-child' ); ?></span>
		</a>

		<a class="pepselect-account__stat" href="<?php echo esc_url( $pepselect_orders_url ); ?>">
			<span class="pepselect-account__stat-label"><?php esc_html_e( 'Orders', 'pepselect-child' ); ?></span>
			<span class="pepselect-account__stat-value"><?php echo esc_html( number_format_i18n( $pepselect_order_count ) ); ?></span>
			<span class="pepselect-account__stat-note"><?php esc_html_e( 'View your order history', 'pepselect-child' ); ?></span>
		</a>

		<a class="pepselect-account__stat" href="<?php echo esc_url( $pepselect_address_url ); ?>">
			<span class="pepselect-account__stat-label"><?php esc_html_e( 'Addresses', 'pepselect-child' ); ?></span>
			<span class="pepselect-account__stat-value pepselect-account__stat-value--sm"><?php esc_html_e( 'Manage', 'pepselect-child' ); ?></span>
			<span class="pepselect-account__stat-note"><?php esc_html_e( 'Shipping details for checkout', 'pepselect-child' ); ?></span>
		</a>
	</div>

	<ul class="pepselect-account__links">
		<li>
			<a href="<?php echo esc_url( $pepselect_orders_url ); ?>">
				<span class="pepselect-account__link-body">
					<span class="pepselect-account__link-title"><?php esc_html_e( 'Order history', 'pepselect-child' ); ?></span>
					<span class="pepselect-account__link-note"><?php esc_html_e( 'View past orders and track shipments.', 'pepselect-child' ); ?></span>
				</span>
				<span class="pepselect-account__link-arrow" aria-hidden="true">&rsaquo;</span>
			</a>
		</li>
		<li>
			<a href="<?php echo esc_url( $pepselect_cashback_url ); ?>">
				<span class="pepselect-account__link-body">
					<span class="pepselect-account__link-title"><?php esc_html_e( 'Cash back', 'pepselect-child' ); ?></span>
					<span class="pepselect-account__link-note"><?php echo esc_html( null !== $pepselect_cashback ? sprintf( __( '%s available to apply at checkout.', 'pepselect-child' ), $pepselect_cashback['balance_formatted'] ) : __( 'Earn 3% back on every order.', 'pepselect-child' ) ); ?></span>
				</span>
				<span class="pepselect-account__link-arrow" aria-hidden="true">&rsaquo;</span>
			</a>
		</li>
		<li>
			<a href="<?php echo esc_url( $pepselect_address_url ); ?>">
				<span class="pepselect-account__link-body">
					<span class="pepselect-account__link-title"><?php esc_html_e( 'Addresses', 'pepselect-child' ); ?></span>
					<span class="pepselect-account__link-note"><?php esc_html_e( 'Manage your shipping and billing details.', 'pepselect-child' ); ?></span>
				</span>
				<span class="pepselect-account__link-arrow" aria-hidden="true">&rsaquo;</span>
			</a>
		</li>
		<li>
			<a href="<?php echo esc_url( $pepselect_account_url ); ?>">
				<span class="pepselect-account__link-body">
					<span class="pepselect-account__link-title"><?php esc_html_e( 'Account details', 'pepselect-child' ); ?></span>
					<span class="pepselect-account__link-note"><?php esc_html_e( 'Update your name, email, and password.', 'pepselect-child' ); ?></span>
				</span>
				<span class="pepselect-account__link-arrow" aria-hidden="true">&rsaquo;</span>
			</a>
		</li>
	</ul>
</div>

<?php
	/**
	 * Preserve the native hook so plugins that inject dashboard content keep working.
	 */
	do_action( 'woocommerce_account_dashboard' );
?>

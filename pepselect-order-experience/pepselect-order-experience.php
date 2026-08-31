<?php
/**
 * Plugin Name: Pep Select Order Experience
 * Description: Secure, batch-specific order records for Pep Select customers and Ops.
 * Version: 0.4.1
 * Author: Pep Select
 * Text Domain: pepselect-order-experience
 * Requires at least: 6.5
 * Requires PHP: 8.1
 * WC requires at least: 8.0
 */

defined( 'ABSPATH' ) || exit;

define( 'PEPSELECT_OE_VERSION', '0.4.1' );
define( 'PEPSELECT_OE_FILE', __FILE__ );
define( 'PEPSELECT_OE_DIR', plugin_dir_path( __FILE__ ) );

require_once PEPSELECT_OE_DIR . 'includes/class-pepselect-oe-access-store.php';
require_once PEPSELECT_OE_DIR . 'includes/class-pepselect-oe-rest-controller.php';
require_once PEPSELECT_OE_DIR . 'includes/class-pepselect-oe-content-registry.php';
require_once PEPSELECT_OE_DIR . 'includes/class-pepselect-oe-relationship-engine.php';
require_once PEPSELECT_OE_DIR . 'includes/class-pepselect-oe-coa-resolver.php';
require_once PEPSELECT_OE_DIR . 'includes/class-pepselect-oe-view-model.php';
require_once PEPSELECT_OE_DIR . 'includes/class-pepselect-oe-renderer.php';
require_once PEPSELECT_OE_DIR . 'includes/class-pepselect-oe-plugin.php';

register_activation_hook( __FILE__, array( 'PepSelect_OE_Plugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'PepSelect_OE_Plugin', 'deactivate' ) );

add_action(
	'plugins_loaded',
	static function () {
		PepSelect_OE_Plugin::instance()->boot();
	}
);

/**
 * Return customer-safe display data for an order in My Account.
 *
 * The theme owns presentation; this plugin owns the private-page capability and
 * the public status vocabulary. When no secure snapshot exists, the URL remains
 * WooCommerce's native order URL.
 *
 * @param WC_Order $order    Order owned by the logged-in customer.
 * @param array    $tracking Normalized tracking data from the site resolver.
 * @return array{url:string,status_key:string,status_label:string}
 */
function pepselect_oe_account_order_summary( $order, array $tracking = array() ): array {
	return PepSelect_OE_Plugin::instance()->account_order_summary( $order, $tracking );
}

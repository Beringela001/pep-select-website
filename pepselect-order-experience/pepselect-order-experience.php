<?php
/**
 * Plugin Name: Pep Select Order Experience
 * Description: Secure, batch-specific order records for Pep Select customers and Ops.
 * Version: 0.3.2
 * Author: Pep Select
 * Text Domain: pepselect-order-experience
 * Requires at least: 6.5
 * Requires PHP: 8.1
 * WC requires at least: 8.0
 */

defined( 'ABSPATH' ) || exit;

define( 'PEPSELECT_OE_VERSION', '0.3.2' );
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

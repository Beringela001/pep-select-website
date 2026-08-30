<?php
/**
 * Plugin Name: Pep Select Shipping Restrictions
 * Description: Validates destination addresses and keeps Alaska and Puerto Rico shipping USPS-only.
 * Version: 0.2.6
 * Author: Pep Select
 * Text Domain: pepselect-shipping-restrictions
 * Requires at least: 6.5
 * Requires PHP: 8.1
 * WC requires at least: 8.0
 */

defined( 'ABSPATH' ) || exit;

define( 'PEPSELECT_SHIPPING_RESTRICTIONS_VERSION', '0.2.6' );
define( 'PEPSELECT_SHIPPING_RESTRICTIONS_FILE', __FILE__ );
define( 'PEPSELECT_SHIPPING_RESTRICTIONS_DIR', plugin_dir_path( __FILE__ ) );

require_once PEPSELECT_SHIPPING_RESTRICTIONS_DIR . 'includes/class-pepselect-shipping-restrictions.php';

add_action(
	'plugins_loaded',
	static function () {
		PepSelect_Shipping_Restrictions::instance()->boot();
	}
);

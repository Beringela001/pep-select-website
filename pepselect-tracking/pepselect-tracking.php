<?php
/**
 * Plugin Name: Pep Select Conversion Tracking
 * Description: Privacy-gated WooCommerce funnel events, campaign attribution, and paid-order conversion delivery for Pep Select.
 * Version: 0.1.0-beta.1
 * Author: Pep Select
 * Requires at least: 6.4
 * Requires PHP: 7.4
 * WC requires at least: 8.0
 * Text Domain: pepselect-tracking
 *
 * @package PepSelectTracking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PEPSELECT_TRACKING_VERSION', '0.1.0-beta.1' );
define( 'PEPSELECT_TRACKING_FILE', __FILE__ );
define( 'PEPSELECT_TRACKING_DIR', plugin_dir_path( __FILE__ ) );
define( 'PEPSELECT_TRACKING_URL', plugin_dir_url( __FILE__ ) );

require_once PEPSELECT_TRACKING_DIR . 'includes/class-pepselect-tracking-attribution.php';
require_once PEPSELECT_TRACKING_DIR . 'includes/class-pepselect-tracking-events.php';
require_once PEPSELECT_TRACKING_DIR . 'includes/class-pepselect-tracking-delivery.php';
require_once PEPSELECT_TRACKING_DIR . 'includes/class-pepselect-tracking-privacy.php';
require_once PEPSELECT_TRACKING_DIR . 'includes/class-pepselect-tracking-health.php';

/** Declare compatibility with WooCommerce High-Performance Order Storage. */
function pepselect_tracking_declare_hpos_compatibility() {
	if ( class_exists( 'Automattic\\WooCommerce\\Utilities\\FeaturesUtil' ) ) {
		Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
}
add_action( 'before_woocommerce_init', 'pepselect_tracking_declare_hpos_compatibility' );

/**
 * Start the plugin after all integrations have registered.
 *
 * @return void
 */
function pepselect_tracking_bootstrap() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	PepSelect_Tracking_Attribution::register();
	PepSelect_Tracking_Events::register();
	PepSelect_Tracking_Delivery::register();
	PepSelect_Tracking_Privacy::register();
	PepSelect_Tracking_Health::register();
}
add_action( 'plugins_loaded', 'pepselect_tracking_bootstrap', 30 );

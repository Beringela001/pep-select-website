<?php
/**
 * Privacy declarations for conversion tracking and attribution.
 *
 * @package PepSelectTracking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class PepSelect_Tracking_Privacy {
	/** Register privacy declarations. */
	public static function register() {
		add_filter( 'wp_privacy_personal_data_exporters', array( __CLASS__, 'register_exporter' ) );
		add_filter( 'wp_privacy_personal_data_erasers', array( __CLASS__, 'register_eraser' ) );
	}

	/** Attribution contains no direct identifier; expose the declaration in core privacy tooling. */
	public static function register_exporter( $exporters ) {
		$exporters['pepselect-tracking'] = array(
			'exporter_friendly_name' => __( 'Pep Select order attribution', 'pepselect-tracking' ),
			'callback'               => array( __CLASS__, 'exporter' ),
		);
		return $exporters;
	}

	/** No email-indexed data exists outside WooCommerce orders. */
	public static function exporter( $email, $page = 1 ) {
		return array( 'data' => array(), 'done' => true );
	}

	/** Register a no-op eraser for the same reason. */
	public static function register_eraser( $erasers ) {
		$erasers['pepselect-tracking'] = array(
			'eraser_friendly_name' => __( 'Pep Select order attribution', 'pepselect-tracking' ),
			'callback'             => array( __CLASS__, 'eraser' ),
		);
		return $erasers;
	}

	/** Order retention and erasure remain under WooCommerce's native privacy controls. */
	public static function eraser( $email, $page = 1 ) {
		return array(
			'items_removed'  => false,
			'items_retained' => false,
			'messages'       => array(),
			'done'           => true,
		);
	}
}

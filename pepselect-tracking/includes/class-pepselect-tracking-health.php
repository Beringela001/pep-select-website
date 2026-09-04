<?php
/**
 * Configuration visibility through WordPress Site Health.
 *
 * @package PepSelectTracking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class PepSelect_Tracking_Health {
	/** Register hooks. */
	public static function register() {
		add_filter( 'site_status_tests', array( __CLASS__, 'tests' ) );
	}

	/** Add direct tests without exposing secret values. */
	public static function tests( $tests ) {
		$tests['direct']['pepselect_tracking_ga4'] = array(
			'label' => __( 'Pep Select paid-order GA4 delivery', 'pepselect-tracking' ),
			'test'  => array( __CLASS__, 'ga4' ),
		);
		$tests['direct']['pepselect_tracking_privacy'] = array(
			'label' => __( 'Pep Select tracking consent gate', 'pepselect-tracking' ),
			'test'  => array( __CLASS__, 'privacy' ),
		);
		return $tests;
	}

	/** GA4 configuration result. */
	public static function ga4() {
		$ok = PepSelect_Tracking_Delivery::ga4_configured();
		return array(
			'label'       => $ok ? __( 'Paid-order GA4 delivery is configured', 'pepselect-tracking' ) : __( 'Paid-order GA4 delivery is not configured', 'pepselect-tracking' ),
			'status'      => $ok ? 'good' : 'recommended',
			'badge'       => array( 'label' => 'Pep Select Tracking', 'color' => 'blue' ),
			'description' => '<p>' . ( $ok ? esc_html__( 'The Measurement Protocol identifiers are present. Secret values are not displayed.', 'pepselect-tracking' ) : esc_html__( 'Add the GA4 measurement ID and API secret to wp-config.php before staging verification.', 'pepselect-tracking' ) ) . '</p>',
			'test'        => 'pepselect_tracking_ga4',
		);
	}

	/** Consent integration result is fail-closed until a CMP declares itself. */
	public static function privacy() {
		$integrated = (bool) apply_filters( 'pepselect_tracking_cmp_integrated', false );
		return array(
			'label'       => $integrated ? __( 'A consent manager is integrated', 'pepselect-tracking' ) : __( 'Connect a consent manager before enabling marketing tools', 'pepselect-tracking' ),
			'status'      => $integrated ? 'good' : 'critical',
			'badge'       => array( 'label' => 'Pep Select Tracking', 'color' => 'blue' ),
			'description' => '<p>' . ( $integrated ? esc_html__( 'Analytics and marketing events stay gated by the visitor consent state.', 'pepselect-tracking' ) : esc_html__( 'The plugin is intentionally fail-closed: no Meta Pixel or paid-order delivery starts without explicit consent.', 'pepselect-tracking' ) ) . '</p>',
			'test'        => 'pepselect_tracking_privacy',
		);
	}
}

<?php
/**
 * Referral codes for the cash-back page (M10).
 *
 * YITH's referral tracking is keyed to the numeric user ID (?ref=7). Customers
 * share a readable code instead — a fixed "PSRC" prefix plus the user ID
 * (user 7 -> PSRC7). The prefix is identical for everyone; only the trailing
 * number changes. There is no account-data lookup (no name, no email), so it
 * cannot fail on missing data. On a visit to ?ref=PSRC7 the prefix is stripped,
 * the numeric ID is handed to YITH before YITH reads it, and attribution is
 * unchanged.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The fixed referral-code prefix. Identical for every user.
 */
if ( ! defined( 'PEPSELECT_REFERRAL_PREFIX' ) ) {
	define( 'PEPSELECT_REFERRAL_PREFIX', 'PSRC' );
}

/**
 * Build a user's referral code: the fixed prefix plus the user ID (7 -> PSRC7).
 * No account data is read, so this never degrades to a bare ID.
 *
 * @param int $user_id User ID.
 * @return string Referral code, or '' when the user ID is invalid.
 */
function pepselect_child_referral_vanity_code( $user_id ) {
	$user_id = absint( $user_id );

	if ( ! $user_id ) {
		return '';
	}

	return PEPSELECT_REFERRAL_PREFIX . $user_id;
}

/**
 * Build the full share URL for a user (site root + ?ref=CODE), matching the
 * format YITH's own referral link uses.
 *
 * @param int $user_id User ID.
 * @return string Share URL, or '' when unavailable.
 */
function pepselect_child_referral_vanity_url( $user_id ) {
	$code = pepselect_child_referral_vanity_code( $user_id );

	if ( '' === $code ) {
		return '';
	}

	return untrailingslashit( home_url() ) . '?ref=' . rawurlencode( $code );
}

/**
 * Resolve an inbound referral code back to the numeric user ID that YITH
 * expects, before YITH's own init handler reads the parameter. A PSRC-prefixed
 * ref (case-insensitive) has its prefix stripped and the numeric ID handed to
 * YITH; anything else, including a legacy numeric ?ref=7, is left untouched.
 *
 * @return void
 */
function pepselect_child_resolve_referral_vanity() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Public referral tracking parameter from a shared link; no nonce exists by design.
	if ( empty( $_GET['ref'] ) ) {
		return;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- See above.
	$ref = sanitize_text_field( wp_unslash( $_GET['ref'] ) );

	$pattern = '/^' . preg_quote( PEPSELECT_REFERRAL_PREFIX, '/' ) . '([0-9]+)$/i';

	if ( ! preg_match( $pattern, $ref, $matches ) ) {
		return;
	}

	$user_id = absint( $matches[1] );

	if ( ! $user_id ) {
		return;
	}

	// Hand YITH the numeric ID it keys attribution to.
	$_GET['ref']     = (string) $user_id;
	$_REQUEST['ref'] = (string) $user_id;
}
add_action( 'init', 'pepselect_child_resolve_referral_vanity', 0 );
add_action( 'after_setup_theme', 'pepselect_child_resolve_referral_vanity', 0 );

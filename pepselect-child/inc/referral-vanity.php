<?php
/**
 * Vanity referral codes for the cash-back page (M10).
 *
 * YITH's referral tracking is keyed to the numeric user ID (?ref=7). Customers
 * share a readable code instead — the first two letters of the first name, the
 * first two letters of the last name, and the user ID, uppercased (Paulo
 * Basseto, user 7 -> PABA7). The trailing digits are the real user ID, so on a
 * visit to ?ref=PABA7 the code is validated back to user 7 and the numeric ID
 * is handed to YITH before YITH reads it, leaving YITH's attribution untouched.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Resolve a user's first and last name for the vanity code. The WordPress
 * account first_name/last_name meta is frequently empty for WooCommerce
 * customers, who have only a billing name, so this falls back to the billing
 * name and then to the display name before giving up.
 *
 * @param int $user_id User ID.
 * @return array{0:string,1:string} First and last name (either may be empty).
 */
function pepselect_child_referral_name_parts( $user_id ) {
	$user = get_userdata( $user_id );

	if ( ! $user ) {
		return array( '', '' );
	}

	$first = trim( (string) $user->first_name );
	$last  = trim( (string) $user->last_name );

	if ( '' === $first ) {
		$first = trim( (string) get_user_meta( $user_id, 'billing_first_name', true ) );
	}

	if ( '' === $last ) {
		$last = trim( (string) get_user_meta( $user_id, 'billing_last_name', true ) );
	}

	// Last resort: split the display name (e.g. "Paulo Basseto") into two parts.
	if ( '' === $first && '' === $last ) {
		$display = trim( (string) $user->display_name );

		if ( '' !== $display ) {
			$parts = preg_split( '/\s+/', $display );
			$first = isset( $parts[0] ) ? $parts[0] : '';
			$last  = isset( $parts[1] ) ? $parts[1] : '';
		}
	}

	return array( $first, $last );
}

/**
 * Build a user's vanity referral code: two letters of the first name, two of
 * the last, then the user ID, uppercased. Falls back to whatever letters exist,
 * and to the bare ID when there is no usable name (still unique via the ID).
 *
 * @param int $user_id User ID.
 * @return string Vanity code, or '' when the user ID is invalid.
 */
function pepselect_child_referral_vanity_code( $user_id ) {
	$user_id = absint( $user_id );

	if ( ! $user_id ) {
		return '';
	}

	list( $first, $last ) = pepselect_child_referral_name_parts( $user_id );

	$first_letters = strtoupper( substr( preg_replace( '/[^A-Za-z]/', '', $first ), 0, 2 ) );
	$last_letters  = strtoupper( substr( preg_replace( '/[^A-Za-z]/', '', $last ), 0, 2 ) );

	return $first_letters . $last_letters . $user_id;
}

/**
 * Build the full vanity share URL for a user (site root + ?ref=CODE), matching
 * the format YITH's own referral link uses.
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
 * Resolve an inbound vanity referral code back to the numeric user ID that YITH
 * expects, before YITH's own init handler reads the parameter.
 *
 * A numeric ref is left untouched. A vanity ref is only rewritten when its
 * trailing digits point to a real user AND the code regenerated for that user
 * matches the inbound code, so an arbitrary or spoofed code is ignored and
 * cannot misattribute a referral.
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

	// Already the numeric ID YITH expects: nothing to do.
	if ( '' === $ref || ctype_digit( $ref ) ) {
		return;
	}

	// A vanity code is letters followed by the trailing user ID.
	if ( ! preg_match( '/^[A-Za-z]*([0-9]+)$/', $ref, $matches ) ) {
		return;
	}

	$user_id  = absint( $matches[1] );
	$expected = pepselect_child_referral_vanity_code( $user_id );

	if ( '' === $expected || strtoupper( $ref ) !== strtoupper( $expected ) ) {
		return;
	}

	// Hand YITH the numeric ID it keys attribution to.
	$_GET['ref']     = (string) $user_id;
	$_REQUEST['ref'] = (string) $user_id;
}
add_action( 'init', 'pepselect_child_resolve_referral_vanity', 0 );
add_action( 'after_setup_theme', 'pepselect_child_resolve_referral_vanity', 0 );

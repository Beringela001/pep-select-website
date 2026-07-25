<?php
/**
 * Vanity referral codes for the cash-back page (M10).
 *
 * YITH's referral tracking is keyed to the numeric user ID (?ref=7). Customers
 * share a readable code instead — the email local-part plus the user ID
 * (contact@paulobasseto.com, user 7 -> contact7). Email is always populated,
 * including Google/Nextend social logins that leave the name empty, so this
 * avoids the empty-name problem. The trailing digits encode the real user ID,
 * so on a visit to ?ref=contact7 the code is validated back to user 7 and the
 * numeric ID is handed to YITH before YITH reads it, leaving attribution intact.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build a user's vanity referral code from the email local-part plus the user
 * ID (contact@paulobasseto.com, user 7 -> "contact7"). Email is mandatory and
 * always populated, including Google/Nextend social logins that leave the name
 * empty, so this avoids the empty-name problem. The local-part is lowercased,
 * reduced to a-z0-9, and capped; an empty result falls back to the bare ID,
 * which the trailing ID keeps unique regardless.
 *
 * @param int $user_id User ID.
 * @return string Vanity code, or '' when the user ID is invalid.
 */
function pepselect_child_referral_vanity_code( $user_id ) {
	$user_id = absint( $user_id );

	if ( ! $user_id ) {
		return '';
	}

	$user  = get_userdata( $user_id );
	$email = $user ? (string) $user->user_email : '';
	$at    = strpos( $email, '@' );
	$local = false !== $at ? substr( $email, 0, $at ) : '';

	$local = strtolower( $local );
	$local = preg_replace( '/[^a-z0-9]/', '', $local );
	$local = substr( $local, 0, 12 );

	return $local . $user_id;
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
 * The code is an email local-part followed by the user ID, and the local-part
 * itself may contain digits, so the split point is not known in advance. The
 * user ID is recovered by trying each trailing-digit suffix and regenerating
 * the code for that candidate: the ref is rewritten only when the regenerated
 * code matches exactly, so an arbitrary or spoofed code is ignored and cannot
 * misattribute a referral. A plain numeric ref that does not match any vanity
 * code is left untouched, so a legacy ?ref=7 still works.
 *
 * @return void
 */
function pepselect_child_resolve_referral_vanity() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Public referral tracking parameter from a shared link; no nonce exists by design.
	if ( empty( $_GET['ref'] ) ) {
		return;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- See above.
	$ref = strtolower( sanitize_text_field( wp_unslash( $_GET['ref'] ) ) );

	// Must end in digits (the user ID); otherwise there is nothing to resolve.
	if ( '' === $ref || ! preg_match( '/([0-9]+)$/', $ref, $matches ) ) {
		return;
	}

	$digits = $matches[1];
	$length = strlen( $digits );

	// Try each trailing-digit suffix as the candidate user ID, shortest first.
	for ( $take = 1; $take <= $length; $take++ ) {
		$candidate = absint( substr( $digits, $length - $take ) );

		if ( ! $candidate ) {
			continue;
		}

		$expected = pepselect_child_referral_vanity_code( $candidate );

		if ( '' !== $expected && $ref === strtolower( $expected ) ) {
			// Hand YITH the numeric ID it keys attribution to.
			$_GET['ref']     = (string) $candidate;
			$_REQUEST['ref'] = (string) $candidate;
			return;
		}
	}
}
add_action( 'init', 'pepselect_child_resolve_referral_vanity', 0 );
add_action( 'after_setup_theme', 'pepselect_child_resolve_referral_vanity', 0 );

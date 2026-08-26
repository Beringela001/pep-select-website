<?php
/**
 * Lightweight contract assertions for a WordPress test runner. This file does
 * not bootstrap WordPress by itself; it is intentionally safe to include from
 * WP-CLI, PHPUnit, or a staging smoke-test harness.
 */

defined( 'ABSPATH' ) || exit;

assert( 43 === strlen( PepSelect_OE_Access_Store::generate_token() ) );
assert( 64 === strlen( PepSelect_OE_Access_Store::token_hash( 'opaque-token' ) ) );
assert( PepSelect_OE_Access_Store::token_hash( 'opaque-token' ) !== 'opaque-token' );
assert( 'pepselect-order-experience/v1' === PepSelect_OE_REST_Controller::NAMESPACE );

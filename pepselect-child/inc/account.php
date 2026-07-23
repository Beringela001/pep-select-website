<?php
/**
 * Custom account area wiring.
 *
 * Enqueues account presentation on WooCommerce account pages, converts the
 * YITH points balance into a dollar cash-back display (1 point = $0.01), and
 * feeds that dollar figure to the header rewards pill and the coded account
 * templates in woocommerce/myaccount/. YITH remains the rewards engine; this
 * only changes presentation.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ensure WooCommerce template overrides in the child theme's woocommerce/
 * directory are recognized. Hello Elementor declares this, but declaring it
 * here makes the account template overrides robust to parent changes.
 *
 * @return void
 */
function pepselect_child_declare_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'pepselect_child_declare_woocommerce_support', 20 );

/**
 * Read the current user's YITH points history, newest first.
 *
 * YITH stores point movements in a custom log. Method names vary across
 * versions, so this tries the documented accessors and falls back to an empty
 * array (the page then shows an empty-history state) rather than fataling.
 * Each entry is normalized to date, description, points, and a dollar delta.
 *
 * @param int $limit Maximum rows to return.
 * @return array<int,array{date:string,description:string,points:int,dollars:float}>
 */
function pepselect_child_get_cashback_history( $limit = 20 ) {
	if ( ! is_user_logged_in() ) {
		return array();
	}

	$user_id = get_current_user_id();
	$raw     = array();

	if ( function_exists( 'YITH_WC_Points_Rewards' ) ) {
		$instance = YITH_WC_Points_Rewards();

		if ( is_object( $instance ) ) {
			// 10.x: history via the customer object.
			if ( method_exists( $instance, 'get_customer' ) ) {
				$customer = $instance->get_customer( $user_id );

				if ( is_object( $customer ) ) {
					if ( method_exists( $customer, 'get_points_logs' ) ) {
						$raw = $customer->get_points_logs();
					} elseif ( method_exists( $customer, 'get_logs' ) ) {
						$raw = $customer->get_logs();
					} elseif ( method_exists( $customer, 'get_points_log' ) ) {
						$raw = $customer->get_points_log();
					}
				}
			}

			// Older instance-level accessors.
			if ( empty( $raw ) && method_exists( $instance, 'get_user_points_log' ) ) {
				$raw = $instance->get_user_points_log( $user_id );
			} elseif ( empty( $raw ) && method_exists( $instance, 'get_points_log' ) ) {
				$raw = $instance->get_points_log( $user_id );
			}
		}
	}

	// Direct DB fallback: YITH stores logs in a custom table. Read defensively.
	if ( empty( $raw ) ) {
		global $wpdb;
		$table = $wpdb->prefix . 'yith_ywpar_points_log';

		// Confirm the table exists before querying, so nothing errors if absent.
		$exists = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) );

		if ( $exists === $table ) {
			$rows = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM `{$table}` WHERE user_id = %d ORDER BY id DESC LIMIT %d",
					$user_id,
					absint( $limit )
				),
				ARRAY_A
			);

			if ( is_array( $rows ) ) {
				$raw = $rows;
			}
		}
	}

	// Filter allows supplying history from another source if the accessor changes.
	$raw = apply_filters( 'pepselect_child_cashback_history_raw', $raw, $user_id );

	if ( ! is_array( $raw ) || empty( $raw ) ) {
		return array();
	}

	$out = array();

	foreach ( $raw as $entry ) {
		// Normalize whether entries are arrays or objects across versions.
		$e = (array) $entry;

		$date   = '';
		$points = 0;
		$desc   = '';

		// Common field names across YITH versions, checked defensively.
		if ( isset( $e['date_earning'] ) ) {
			$date = $e['date_earning'];
		} elseif ( isset( $e['date'] ) ) {
			$date = $e['date'];
		} elseif ( isset( $e['date_gmt'] ) ) {
			$date = $e['date_gmt'];
		}

		if ( isset( $e['amount'] ) ) {
			$points = (int) $e['amount'];
		} elseif ( isset( $e['points'] ) ) {
			$points = (int) $e['points'];
		}

		if ( isset( $e['description'] ) ) {
			$desc = (string) $e['description'];
		} elseif ( isset( $e['action'] ) ) {
			$desc = (string) $e['action'];
		} elseif ( isset( $e['reason'] ) ) {
			$desc = (string) $e['reason'];
		}

		// Present the raw date as a formatted date when parseable.
		$timestamp = $date ? strtotime( $date ) : false;
		$date_out  = $timestamp ? date_i18n( get_option( 'date_format' ), $timestamp ) : (string) $date;

		$out[] = array(
			'date'        => $date_out,
			'description' => pepselect_child_cashback_reason_label( $desc, $points ),
			'points'      => $points,
			'dollars'     => $points * (float) PEPSELECT_CASHBACK_DOLLARS_PER_POINT,
			'sort'        => $timestamp ? $timestamp : 0,
		);
	}

	// Newest first.
	usort(
		$out,
		static function ( $a, $b ) {
			if ( $a['sort'] === $b['sort'] ) {
				return 0;
			}
			return ( $a['sort'] < $b['sort'] ) ? 1 : -1;
		}
	);

	return array_slice( $out, 0, absint( $limit ) );
}

/**
 * Reframe raw YITH reason strings into calm, customer-facing cash-back labels.
 * Strips gamification wording (daily-login targets) and coupon jargon.
 *
 * @param string $reason Raw reason.
 * @return string
 */
/**
 * Reframe raw YITH reason strings into calm, customer-facing cash-back labels.
 * Strips gamification wording. Matches both the human-readable phrases YITH
 * shows and its internal action codes (underscored). When the reason is
 * unrecognized, the sign of the points amount is used to infer a sensible
 * label so a row is never left as a generic placeholder.
 *
 * @param string $reason Raw reason text or action code.
 * @param int    $points Points amount for this entry (may be negative).
 * @return string
 */
function pepselect_child_cashback_reason_label( $reason, $points = 0 ) {
	$reason = strtolower( trim( wp_strip_all_tags( (string) $reason ) ) );

	// Spends / redemptions (usually negative).
	if (
		false !== strpos( $reason, 'coupon' ) ||
		false !== strpos( $reason, 'redeem' ) ||
		false !== strpos( $reason, 'discount' ) ||
		false !== strpos( $reason, 'used' )
	) {
		return __( 'Applied to an order', 'pepselect-child' );
	}

	// Earned on a purchase.
	if (
		false !== strpos( $reason, 'order' ) ||
		false !== strpos( $reason, 'purchase' ) ||
		false !== strpos( $reason, 'payment' )
	) {
		return __( 'Earned on an order', 'pepselect-child' );
	}

	// Account bonuses (login streaks, registration, reviews, birthday, etc.).
	if (
		false !== strpos( $reason, 'daily' ) ||
		false !== strpos( $reason, 'login' ) ||
		false !== strpos( $reason, 'target' ) ||
		false !== strpos( $reason, 'regist' ) ||
		false !== strpos( $reason, 'sign up' ) ||
		false !== strpos( $reason, 'signup' ) ||
		false !== strpos( $reason, 'review' ) ||
		false !== strpos( $reason, 'birthday' ) ||
		false !== strpos( $reason, 'bonus' )
	) {
		return __( 'Account bonus', 'pepselect-child' );
	}

	// Adjustments.
	if (
		false !== strpos( $reason, 'refund' ) ||
		false !== strpos( $reason, 'cancel' ) ||
		false !== strpos( $reason, 'expire' ) ||
		false !== strpos( $reason, 'admin' ) ||
		false !== strpos( $reason, 'manual' )
	) {
		return __( 'Adjustment', 'pepselect-child' );
	}

	// Unrecognized: infer from the sign so no row shows a bare placeholder.
	if ( (int) $points < 0 ) {
		return __( 'Applied to an order', 'pepselect-child' );
	}

	if ( (int) $points > 0 ) {
		return __( 'Cash back earned', 'pepselect-child' );
	}

	return __( 'Account activity', 'pepselect-child' );
}

/**
 * Replace YITH's cash-back endpoint output with our coded template.
 *
 * YITH hooks its points UI onto the cash-back account endpoint. We remove any
 * such callbacks on that endpoint and render our own dollar-framed page. The
 * data still comes from YITH via the helpers above, so YITH stays the engine.
 *
 * Runs late so YITH has registered its hook first.
 *
 * @return void
 */
function pepselect_child_override_cashback_endpoint() {
	$hook = 'woocommerce_account_cash-back_endpoint';

	// Remove YITH's own render on this endpoint, whatever its callback name.
	global $wp_filter;
	if ( isset( $wp_filter[ $hook ] ) ) {
		foreach ( $wp_filter[ $hook ]->callbacks as $priority => $callbacks ) {
			foreach ( $callbacks as $id => $cb ) {
				// Only strip YITH-owned callbacks; leave anything else intact.
				$owner = '';
				if ( is_array( $cb['function'] ) && is_object( $cb['function'][0] ) ) {
					$owner = get_class( $cb['function'][0] );
				} elseif ( is_string( $cb['function'] ) ) {
					$owner = $cb['function'];
				}

				if ( false !== stripos( $owner, 'ywpar' ) || false !== stripos( $owner, 'points_rewards' ) ) {
					remove_action( $hook, $cb['function'], $priority );
				}
			}
		}
	}

	add_action( $hook, 'pepselect_child_render_cashback_page', 10 );
}
add_action( 'wp', 'pepselect_child_override_cashback_endpoint', 20 );

/**
 * Render the coded cash-back page.
 *
 * @return void
 */
function pepselect_child_render_cashback_page() {
	$template = get_stylesheet_directory() . '/woocommerce/myaccount/cash-back.php';

	if ( is_readable( $template ) ) {
		include $template;
	}
}

/**
 * Dollars per YITH point. 1 point = $0.01, so 100 points = $1.00.
 */
if ( ! defined( 'PEPSELECT_CASHBACK_DOLLARS_PER_POINT' ) ) {
	define( 'PEPSELECT_CASHBACK_DOLLARS_PER_POINT', 0.01 );
}

/**
 * Read the current user's YITH point balance.
 *
 * Tries the documented YITH accessor first, then a filter as a fallback, so a
 * plugin update or absence degrades gracefully to zero rather than fataling.
 *
 * @return int Points balance, or 0 when unavailable.
 */
function pepselect_child_get_points_balance() {
	if ( ! is_user_logged_in() ) {
		return 0;
	}

	$user_id = get_current_user_id();
	$points  = 0;

	// Authoritative source: YITH's own points shortcode renders the exact
	// balance YITH would show. Parse the integer from its output so our number
	// always matches YITH's, regardless of internal method-name changes.
	if ( shortcode_exists( 'yith_ywpar_points' ) ) {
		$rendered = wp_strip_all_tags( do_shortcode( '[yith_ywpar_points label="" show_worth="no"]' ) );

		if ( '' !== trim( $rendered ) && preg_match( '/-?\d[\d,]*/', $rendered, $m ) ) {
			$points = (int) str_replace( ',', '', $m[0] );
		}
	}

	// Method-based paths as a secondary source if the shortcode is unavailable.
	if ( ! $points && function_exists( 'YITH_WC_Points_Rewards' ) ) {
		$instance = YITH_WC_Points_Rewards();

		if ( is_object( $instance ) ) {
			if ( method_exists( $instance, 'get_customer' ) ) {
				$customer = $instance->get_customer( $user_id );

				if ( is_object( $customer ) ) {
					if ( method_exists( $customer, 'get_total_points' ) ) {
						$points = $customer->get_total_points();
					} elseif ( method_exists( $customer, 'get_points' ) ) {
						$points = $customer->get_points();
					}
				}
			}

			if ( ! $points && method_exists( $instance, 'get_points_of_user' ) ) {
				$points = $instance->get_points_of_user( $user_id );
			}
		}
	}

	if ( ! $points && function_exists( 'ywpar_get_points_of_user' ) ) {
		$points = ywpar_get_points_of_user( $user_id );
	}

	// Allow an explicit override/fallback without hardcoding plugin internals.
	$points = apply_filters( 'pepselect_child_points_balance', $points, $user_id );

	return absint( $points );
}

/**
 * Return the cash-back balance as points, dollars, and a formatted string.
 *
 * @return array{points:int,dollars:float,balance_formatted:string}
 */
function pepselect_child_get_cashback_display() {
	$points  = pepselect_child_get_points_balance();
	$dollars = $points * (float) PEPSELECT_CASHBACK_DOLLARS_PER_POINT;

	$formatted = function_exists( 'wc_price' )
		? wp_strip_all_tags( wc_price( $dollars ) )
		: '$' . number_format_i18n( $dollars, 2 );

	return array(
		'points'            => $points,
		'dollars'           => $dollars,
		'balance_formatted' => $formatted,
	);
}

/**
 * Rename the account menu label "Cash back" wherever WooCommerce lists it,
 * keeping the same endpoint slug (cash-back) that YITH registered.
 *
 * @param array<string,string> $items Menu items.
 * @return array<string,string>
 */
function pepselect_child_account_menu_labels( $items ) {
	if ( isset( $items['cash-back'] ) ) {
		$items['cash-back'] = __( 'Cash back', 'pepselect-child' );
	}

	// Hide Downloads from the menu. The endpoint stays registered and
	// functional; it is only removed from the navigation list.
	if ( isset( $items['downloads'] ) ) {
		unset( $items['downloads'] );
	}

	return $items;
}
add_filter( 'woocommerce_account_menu_items', 'pepselect_child_account_menu_labels', 20 );

/**
 * Enqueue account presentation on WooCommerce account pages only.
 *
 * @return void
 */
function pepselect_child_enqueue_account_assets() {
	if ( ! function_exists( 'is_account_page' ) || ! is_account_page() ) {
		return;
	}

	wp_enqueue_style(
		'pepselect-child-account',
		get_stylesheet_directory_uri() . '/assets/css/account.css',
		array( 'pepselect-child-foundations' ),
		pepselect_child_asset_version( 'assets/css/account.css' )
	);

	wp_enqueue_script(
		'pepselect-child-account-cashback',
		get_stylesheet_directory_uri() . '/assets/js/account-cashback.js',
		array(),
		pepselect_child_asset_version( 'assets/js/account-cashback.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'pepselect_child_enqueue_account_assets', 40 );

/**
 * Total cash back earned and applied, derived from YITH's own points log.
 *
 * Nothing is invented here: the log entries are YITH's, and positive entries are
 * summed as earned while negative entries are summed as applied. Values come
 * back as dollars using the same conversion as the balance card.
 *
 * @return array{earned:float,applied:float,earned_formatted:string,applied_formatted:string}
 */
function pepselect_child_get_cashback_totals() {
	$history = function_exists( 'pepselect_child_get_cashback_history' )
		? pepselect_child_get_cashback_history( 500 )
		: array();

	$earned  = 0.0;
	$applied = 0.0;

	foreach ( (array) $history as $entry ) {
		$dollars = isset( $entry['dollars'] ) ? (float) $entry['dollars'] : 0.0;

		if ( $dollars > 0 ) {
			$earned += $dollars;
		} else {
			$applied += abs( $dollars );
		}
	}

	return array(
		'earned'            => $earned,
		'applied'           => $applied,
		'earned_formatted'  => pepselect_child_format_dollars( $earned ),
		'applied_formatted' => pepselect_child_format_dollars( $applied ),
	);
}

/**
 * Format a dollar amount using WooCommerce when available.
 *
 * @param float $amount Amount in dollars.
 * @return string
 */
function pepselect_child_format_dollars( $amount ) {
	if ( function_exists( 'wc_price' ) ) {
		return wp_strip_all_tags( wc_price( (float) $amount ) );
	}

	return '$' . number_format_i18n( (float) $amount, 2 );
}

/**
 * Split YITH's rendered my-points output into the slots the cash back layout
 * needs, so each block can be placed and restyled without reimplementing any of
 * YITH's logic. Everything returned is YITH's own markup.
 *
 * @param string $html Captured YITH output.
 * @return array{referral:string,history:string,manage:string,rest:string}
 */
function pepselect_child_split_yith_points_output( $html ) {
	$slots = array(
		'referral' => '',
		'history'  => '',
		'manage'   => '',
		'rest'     => '',
	);

	$html = (string) $html;

	if ( '' === trim( $html ) || ! class_exists( 'DOMDocument' ) ) {
		$slots['rest'] = $html;
		return $slots;
	}

	$doc = new DOMDocument();
	libxml_use_internal_errors( true );
	$doc->loadHTML( '<?xml encoding="utf-8" ?><div id="pepselect-yith-root">' . $html . '</div>', LIBXML_NOWARNING | LIBXML_NOERROR );
	libxml_clear_errors();

	$xpath = new DOMXPath( $doc );

	// Discard blocks the branded layout replaces: YITH's points/redeem summary
	// (duplicated by the balance cards) and its now-orphaned tab navigation,
	// whose panels are inlined as sections below.
	$drop = $xpath->query(
		"//*[contains(@class,'ywpar_myaccount_entry_info')]"
		. "|//*[contains(@class,'ywpar_tabs_header')]"
		. "|//ul[contains(@class,'ywpar_tabs')]"
		. "|//*[contains(@class,'ywpar_tabs_links')]"
	);

	if ( $drop ) {
		$stale = array();

		foreach ( $drop as $node ) {
			$stale[] = $node;
		}

		foreach ( $stale as $node ) {
			if ( $node->parentNode ) {
				$node->parentNode->removeChild( $node );
			}
		}
	}

	// Selectors for each slot, matched on class/id substrings so the split keeps
	// working across YITH markup revisions. Referral matches only genuinely
	// referral-named markup: YITH Points & Rewards ships no referral feature, so
	// this stays empty unless a referral plugin renders into the endpoint.
	$queries = array(
		'referral' => "//*[contains(@class,'referral') or contains(@id,'referral') or contains(@class,'refer-a-friend') or contains(@id,'refer_a_friend')]",
		'history'  => "//table[contains(@class,'ywpar_points_rewards')]",
		'manage'   => "//*[@id='share_points' or @id='ywpar-share-points' or contains(@class,'your-coupons')]",
	);

	foreach ( $queries as $slot => $query ) {
		$nodes = $xpath->query( $query );

		if ( ! $nodes || ! $nodes->length ) {
			continue;
		}

		$collected = array();

		foreach ( $nodes as $node ) {
			// Skip nodes already inside a node we collected for this slot.
			foreach ( $collected as $done ) {
				if ( $done->contains( $node ) ) {
					continue 2;
				}
			}

			$collected[] = $node;
			$slots[ $slot ] .= $doc->saveHTML( $node );
		}

		// Remove the collected nodes so they are not duplicated in 'rest'.
		foreach ( $collected as $node ) {
			if ( $node->parentNode ) {
				$node->parentNode->removeChild( $node );
			}
		}
	}

	$root = $doc->getElementById( 'pepselect-yith-root' );

	if ( $root ) {
		$remaining = '';

		foreach ( $root->childNodes as $child ) {
			$remaining .= $doc->saveHTML( $child );
		}

		// Drop leftovers that are only whitespace or an empty tab shell.
		if ( '' !== trim( wp_strip_all_tags( $remaining ) ) ) {
			$slots['rest'] = $remaining;
		}
	}

	return $slots;
}

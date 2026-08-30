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

		// The order this movement is tied to, when the log records one. Used to
		// attribute per-order cash back on the account dashboard.
		$order_ref = 0;
		if ( isset( $e['order_id'] ) ) {
			$order_ref = (int) $e['order_id'];
		} elseif ( isset( $e['order'] ) ) {
			$order_ref = (int) $e['order'];
		}

		// Present the raw date as a formatted date when parseable.
		$timestamp = $date ? strtotime( $date ) : false;
		$date_out  = $timestamp ? date_i18n( get_option( 'date_format' ), $timestamp ) : (string) $date;

		$out[] = array(
			'date'        => $date_out,
			'description' => pepselect_child_cashback_reason_label( $desc, $points ),
			'points'      => $points,
			'dollars'     => $points * (float) PEPSELECT_CASHBACK_DOLLARS_PER_POINT,
			'order_id'    => $order_ref,
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
 * Save the signed-in customer's text-message preferences from the account
 * dashboard. Billing phone remains the canonical WooCommerce phone field;
 * consent choices and their update time are stored as customer user meta.
 *
 * @return void
 */
function pepselect_child_handle_sms_preferences() {
	if (
		! is_user_logged_in()
		|| ! function_exists( 'is_account_page' )
		|| ! is_account_page()
		|| empty( $_POST['pepselect_sms_preferences_action'] )
	) {
		return;
	}

	$nonce = isset( $_POST['pepselect_sms_preferences_nonce'] )
		? sanitize_text_field( wp_unslash( $_POST['pepselect_sms_preferences_nonce'] ) )
		: '';

	if ( ! wp_verify_nonce( $nonce, 'pepselect_save_sms_preferences' ) ) {
		wc_add_notice( __( 'We could not verify that request. Please try again.', 'pepselect-child' ), 'error' );
		return;
	}

	$raw_choices = isset( $_POST['pepselect_sms_preferences'] )
		? (array) wp_unslash( $_POST['pepselect_sms_preferences'] )
		: array();
	$choices     = array_map( 'sanitize_key', $raw_choices );
	$choices     = array_values( array_intersect( $choices, array( 'customer_care', 'marketing', 'none' ) ) );
	$care        = in_array( 'customer_care', $choices, true );
	$marketing   = in_array( 'marketing', $choices, true );
	$none        = in_array( 'none', $choices, true );

	if ( empty( $choices ) ) {
		wc_add_notice( __( 'Choose at least one text-message preference.', 'pepselect-child' ), 'error' );
		return;
	}

	if ( $none && ( $care || $marketing ) ) {
		wc_add_notice( __( 'Choose either the messages you want or the option to receive no text messages.', 'pepselect-child' ), 'error' );
		return;
	}

	$phone = isset( $_POST['pepselect_sms_mobile'] )
		? wc_clean( wp_unslash( $_POST['pepselect_sms_mobile'] ) )
		: '';

	if ( ( $care || $marketing ) && '' === $phone ) {
		wc_add_notice( __( 'Enter the mobile number where you want to receive text messages.', 'pepselect-child' ), 'error' );
		return;
	}

	if ( ( $care || $marketing ) && class_exists( 'WC_Validation' ) && ! WC_Validation::is_phone( $phone ) ) {
		wc_add_notice( __( 'Enter a valid mobile number.', 'pepselect-child' ), 'error' );
		return;
	}

	$user_id = get_current_user_id();

	if ( ( $care || $marketing ) && '' !== $phone ) {
		$customer = new WC_Customer( $user_id );
		$customer->set_billing_phone( $phone );
		$customer->save();
	}

	update_user_meta( $user_id, 'pepselect_sms_customer_care_consent', $care ? 'yes' : 'no' );
	update_user_meta( $user_id, 'pepselect_sms_marketing_consent', $marketing ? 'yes' : 'no' );
	update_user_meta( $user_id, 'pepselect_sms_opt_out', $none ? 'yes' : 'no' );
	update_user_meta( $user_id, 'pepselect_sms_consent_updated_gmt', current_time( 'mysql', true ) );
	update_user_meta( $user_id, 'pepselect_sms_consent_source', 'my-account' );
	update_user_meta( $user_id, 'pepselect_sms_disclosure_version', '2026-08-21' );

	wc_add_notice( __( 'Your text-message preferences have been saved.', 'pepselect-child' ), 'success' );
	wp_safe_redirect( wc_get_page_permalink( 'myaccount' ) . '#pepselect-sms-preferences' );
	exit;
}
add_action( 'template_redirect', 'pepselect_child_handle_sms_preferences', 20 );

/**
 * Render third-party registration fields without YITH's optional birthday row.
 *
 * The hook remains intact for WooCommerce privacy copy and other integrations.
 * Existing birthday metadata is not changed.
 *
 * @return void
 */
function pepselect_child_render_registration_fields_without_birthday() {
	ob_start();
	do_action( 'woocommerce_register_form' );
	$fields_markup = (string) ob_get_clean();

	$fields_markup = preg_replace(
		'~<p\b[^>]*>(?:(?!</p>).)*<input\b[^>]*\bname=(["\'])yith_birthday\1[^>]*>(?:(?!</p>).)*</p>~is',
		'',
		$fields_markup,
		1
	);

	echo $fields_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Preserved output from trusted registration hooks.
}

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

	if ( ! is_user_logged_in() ) {
		wp_enqueue_script(
			'pepselect-child-account-login',
			get_stylesheet_directory_uri() . '/assets/js/account-login.js',
			array(),
			pepselect_child_asset_version( 'assets/js/account-login.js' ),
			true
		);
	}
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

/**
 * Describe a shortcode callback by reading it on this install: which function
 * or method handles it, the file and lines it lives at, and its source. Used to
 * determine exactly what YITH's referral shortcode requires before it outputs,
 * without guessing from documentation.
 *
 * @param string $tag Shortcode tag.
 * @return array<string,string>
 */
function pepselect_child_describe_shortcode( $tag ) {
	$info = array(
		'tag'      => $tag,
		'callback' => 'not registered',
		'file'     => '',
		'lines'    => '',
		'source'   => '',
	);

	if ( empty( $GLOBALS['shortcode_tags'][ $tag ] ) ) {
		return $info;
	}

	$callback = $GLOBALS['shortcode_tags'][ $tag ];

	try {
		if ( is_array( $callback ) ) {
			$class            = is_object( $callback[0] ) ? get_class( $callback[0] ) : (string) $callback[0];
			$info['callback'] = $class . '::' . $callback[1];
			$reflection       = new ReflectionMethod( $callback[0], $callback[1] );
		} elseif ( is_string( $callback ) && false !== strpos( $callback, '::' ) ) {
			$info['callback'] = $callback;
			$parts            = explode( '::', $callback );
			$reflection       = new ReflectionMethod( $parts[0], $parts[1] );
		} elseif ( $callback instanceof Closure ) {
			$info['callback'] = 'Closure';
			$reflection       = new ReflectionFunction( $callback );
		} else {
			$info['callback'] = (string) $callback;
			$reflection       = new ReflectionFunction( $callback );
		}

		$file  = $reflection->getFileName();
		$start = $reflection->getStartLine();
		$end   = $reflection->getEndLine();

		$info['file']  = (string) $file;
		$info['lines'] = $start . '-' . $end;

		if ( $file && is_readable( $file ) ) {
			$lines = file( $file );

			if ( is_array( $lines ) ) {
				$info['source'] = implode( '', array_slice( $lines, $start - 1, ( $end - $start ) + 1 ) );
			}
		}
	} catch ( Exception $e ) {
		$info['source'] = 'Reflection failed: ' . $e->getMessage();
	} catch ( Error $e ) {
		$info['source'] = 'Reflection error: ' . $e->getMessage();
	}

	return $info;
}

/**
 * Collect referral-related options and current-user meta, so the condition the
 * referral shortcode checks can be matched against real stored values.
 *
 * @return array<string,string>
 */
function pepselect_child_referral_state() {
	global $wpdb;

	$state = array();

	$options = $wpdb->get_results(
		"SELECT option_name, option_value FROM {$wpdb->options}
		 WHERE option_name LIKE '%referral%' OR option_name LIKE '%ywpar%'
		 ORDER BY option_name LIMIT 60",
		ARRAY_A
	);

	foreach ( (array) $options as $option ) {
		$state[ 'option: ' . $option['option_name'] ] = mb_substr( (string) $option['option_value'], 0, 160 );
	}

	$user_id = get_current_user_id();

	if ( $user_id ) {
		$meta = get_user_meta( $user_id );

		foreach ( (array) $meta as $key => $value ) {
			if ( preg_match( '/referral|ywpar|refer/i', $key ) ) {
				$state[ 'usermeta: ' . $key ] = mb_substr( is_array( $value ) ? wp_json_encode( $value ) : (string) $value, 0, 160 );
			}
		}
	}

	return $state;
}

/**
 * Map of order_id => positive cash-back dollars earned, from YITH's own points
 * log. One pass over the log (the same authoritative source as the balance and
 * totals), so a dashboard listing many orders stays cheap. No per-order plugin
 * meta key is assumed; when the log carries no order references the map is empty
 * and the dashboard simply omits the per-order cash-back line.
 *
 * @return array<int,float>
 */
function pepselect_child_get_cashback_earned_by_order() {
	$history = function_exists( 'pepselect_child_get_cashback_history' )
		? pepselect_child_get_cashback_history( 500 )
		: array();

	$map = array();

	foreach ( (array) $history as $entry ) {
		$eid     = isset( $entry['order_id'] ) ? (int) $entry['order_id'] : 0;
		$dollars = isset( $entry['dollars'] ) ? (float) $entry['dollars'] : 0.0;

		if ( $eid > 0 && $dollars > 0 ) {
			$map[ $eid ] = isset( $map[ $eid ] ) ? $map[ $eid ] + $dollars : $dollars;
		}
	}

	return $map;
}

/**
 * Convert the POINTS column of YITH's account points-history table into dollar
 * cash back, server-side, before render. Operates on the captured YITH HTML
 * string, so YITH's own logic is untouched - presentation only. Scoped strictly
 * to Table 1 (class my_account_orders); the share-coupon table
 * (ywpar_share_points_table) is deliberately left alone, as its VALUE column is
 * already in dollars.
 *
 * Reason cells that expose a raw coupon code ("Created coupon: xxxx-...") are
 * reworded through pepselect_child_cashback_reason_label() so no code is shown;
 * recognised human reasons (Order Completed, Order Cancelled, Target achieved -
 * Daily Login) are left exactly as YITH renders them - the M10 wording fix is
 * not disturbed.
 *
 * Degrades safely: if the table or DOMDocument is unavailable the input HTML is
 * returned unchanged.
 *
 * @param string $html Captured YITH history HTML.
 * @return string
 */
function pepselect_child_transform_points_history_html( $html ) {
	$html = (string) $html;

	if ( '' === trim( $html ) || false === strpos( $html, 'my_account_orders' ) || ! class_exists( 'DOMDocument' ) ) {
		return $html;
	}

	$doc = new DOMDocument();
	libxml_use_internal_errors( true );
	$doc->loadHTML( '<?xml encoding="utf-8" ?><div id="pep-hist-root">' . $html . '</div>', LIBXML_NOWARNING | LIBXML_NOERROR );
	libxml_clear_errors();

	$xpath = new DOMXPath( $doc );

	// Only the account points-history table; never the share-coupon table.
	$tables = $xpath->query( "//table[contains(concat(' ', normalize-space(@class), ' '), ' my_account_orders ')]" );

	if ( ! $tables || ! $tables->length ) {
		return $html;
	}

	foreach ( $tables as $table ) {
		// Header cell: POINTS -> Cash back.
		foreach ( $xpath->query( ".//thead//th", $table ) as $th ) {
			if ( 0 === strcasecmp( trim( $th->textContent ), 'points' ) ) {
				$th->nodeValue = __( 'Cash back', 'pepselect-child' );
			}
		}

		// Points cells: convert every integer to dollars, drop the "Points" word,
		// keep YITH's ywpar_minus / ywpar_plus spans (coloured by existing CSS).
		foreach ( $xpath->query( ".//td[contains(concat(' ', normalize-space(@class), ' '), ' ywpar_points_rewards-points ')]", $table ) as $td ) {
			pepselect_child_points_cell_to_dollars( $td );

			// WooCommerce responsive tables echo the header into a data-title.
			if ( $td->hasAttribute( 'data-title' ) && 0 === strcasecmp( trim( $td->getAttribute( 'data-title' ) ), 'points' ) ) {
				$td->setAttribute( 'data-title', __( 'Cash back', 'pepselect-child' ) );
			}
		}

		// Reason cells that expose a coupon code -> calm label, no raw code.
		foreach ( $xpath->query( ".//td[contains(concat(' ', normalize-space(@class), ' '), ' ywpar_points_rewards-action ')]", $table ) as $td ) {
			$text = trim( $td->textContent );

			if ( '' !== $text && preg_match( '/coupon|[A-Za-z0-9]{4}-[A-Za-z0-9]{4}/', $text ) ) {
				$td->nodeValue = pepselect_child_cashback_reason_label( $text );
			}
		}
	}

	$root = $doc->getElementById( 'pep-hist-root' );

	if ( ! $root ) {
		return $html;
	}

	$out = '';
	foreach ( $root->childNodes as $child ) {
		$out .= $doc->saveHTML( $child );
	}

	return $out;
}

/**
 * Recursively rewrite the integer point figures inside a points cell as dollars,
 * descending into YITH's ywpar_minus / ywpar_plus spans so their styling is kept.
 *
 * @param DOMNode $node Cell or descendant node.
 * @return void
 */
function pepselect_child_points_cell_to_dollars( $node ) {
	if ( ! $node->hasChildNodes() ) {
		return;
	}

	foreach ( iterator_to_array( $node->childNodes ) as $child ) {
		if ( XML_ELEMENT_NODE === $child->nodeType ) {
			pepselect_child_points_cell_to_dollars( $child );
		} elseif ( XML_TEXT_NODE === $child->nodeType ) {
			$child->nodeValue = pepselect_child_points_text_to_dollars( $child->nodeValue );
		}
	}
}

/**
 * Turn a run of points text ("-570 / 0 Points") into dollars ("-$5.70 / $0.00").
 * The trailing Points/Point label is removed; each signed integer is converted
 * at 1 point = $0.01.
 *
 * @param string $text Raw text.
 * @return string
 */
function pepselect_child_points_text_to_dollars( $text ) {
	$text = (string) $text;

	if ( '' === trim( $text ) ) {
		return $text;
	}

	// Remove the "Points"/"Point" word; the column now reads dollars.
	$text = preg_replace( '/\s*\bpoints?\b/i', '', $text );

	// Convert each integer (optional leading sign) to a dollar amount.
	$text = preg_replace_callback(
		'/-?\d[\d,]*/',
		function ( $m ) {
			$points  = (int) str_replace( ',', '', $m[0] );
			$dollars = abs( $points ) * (float) PEPSELECT_CASHBACK_DOLLARS_PER_POINT;
			$sign    = ( $points < 0 ) ? '-' : '';

			return $sign . '$' . number_format( $dollars, 2 );
		},
		$text
	);

	return $text;
}

/**
 * Convert YITH's per-order "Points earned: N" summary on the view-order page into
 * dollar cash back, without editing YITH. YITH prints
 * <p class="ywpar-order-point-summary">...<span>N</span></p> during the
 * view-order render; these two callbacks bracket that render with an output
 * buffer (priority 1 opens, 999 rewrites and flushes) and rewrite only that one
 * paragraph at 1 point = $0.01. Everything else is echoed byte-for-byte.
 *
 * Scoped to the view-order endpoint. The anchor is the class substring
 * "ywpar-order-point-summary" - fragile by nature, so if YITH renames it the
 * summary is left untouched rather than mis-edited.
 *
 * @return void
 */
function pepselect_child_view_order_points_buffer_start() {
	ob_start();
}
add_action( 'woocommerce_account_view-order_endpoint', 'pepselect_child_view_order_points_buffer_start', 1 );

/**
 * Close the view-order output buffer opened above and flush the converted HTML.
 *
 * @return void
 */
function pepselect_child_view_order_points_buffer_end() {
	if ( 0 === ob_get_level() ) {
		return;
	}

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- YITH's own view-order markup; only the points figure is rewritten.
	echo pepselect_child_convert_order_points_html( ob_get_clean() );
}
add_action( 'woocommerce_account_view-order_endpoint', 'pepselect_child_view_order_points_buffer_end', 999 );

/**
 * Rewrite YITH's per-order points summary paragraph as dollars. Returns the input
 * unchanged when the anchor class is absent or no integer is present.
 *
 * @param string $html Buffered view-order HTML.
 * @return string
 */
function pepselect_child_convert_order_points_html( $html ) {
	$html = (string) $html;

	if ( false === strpos( $html, 'ywpar-order-point-summary' ) ) {
		return $html;
	}

	return preg_replace_callback(
		'/(<p\b[^>]*\bywpar-order-point-summary\b[^>]*>)(.*?)(<\/p>)/is',
		function ( $m ) {
			if ( ! preg_match( '/-?\d[\d,]*/', wp_strip_all_tags( $m[2] ), $n ) ) {
				return $m[0];
			}

			$points  = (int) str_replace( ',', '', $n[0] );
			$dollars = function_exists( 'pepselect_child_format_dollars' )
				? pepselect_child_format_dollars( $points * (float) PEPSELECT_CASHBACK_DOLLARS_PER_POINT )
				: '$' . number_format( $points * (float) PEPSELECT_CASHBACK_DOLLARS_PER_POINT, 2 );

			return $m[1] . '<strong>' . esc_html__( 'Cash back earned:', 'pepselect-child' ) . '</strong> <span>' . esc_html( $dollars ) . '</span>' . $m[3];
		},
		$html
	);
}

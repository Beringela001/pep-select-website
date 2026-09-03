<?php

defined( 'ABSPATH' ) || exit;

/** Coordinate automatic coupons so exclusive rules cannot combine accidentally. */
final class PepSelect_Discount_Stacking {
	/** @var bool */
	private static $syncing = false;

	/** Register one synchronization pass after every cart has loaded. */
	public static function boot() {
		add_action( 'woocommerce_before_calculate_totals', array( __CLASS__, 'sync' ), 30 );
		add_filter( 'woocommerce_coupon_is_valid', array( __CLASS__, 'coupon_is_valid' ), 999, 2 );
		add_filter( 'woocommerce_coupon_error', array( __CLASS__, 'coupon_error' ), 999, 3 );
		add_filter( 'woocommerce_apply_individual_use_coupon', array( __CLASS__, 'keep_allowed_coupons' ), 999, 3 );
		add_filter( 'woocommerce_apply_with_individual_use_coupon', array( __CLASS__, 'allow_with_individual_coupon' ), 999, 4 );
	}

	/** Apply every stackable candidate, or only the best exclusive candidate. */
	public static function sync( $cart ) {
		if ( self::$syncing || ! $cart instanceof WC_Cart || ( is_admin() && ! wp_doing_ajax() ) ) {
			return;
		}
		self::$syncing = true;

		try {
			$candidates = array_merge(
				PepSelect_BOGO_Rule::discount_candidates( $cart ),
				PepSelect_Compound_Discount::discount_candidates( $cart ),
				PepSelect_Sitewide_Discount::discount_candidates( $cart )
			);
			$qualified = array_values( array_filter( $candidates, static function ( $candidate ) { return ! empty( $candidate['qualifies'] ); } ) );
			$takeover  = PepSelect_Sitewide_Discount::active_takeover_rule( $cart );
			if ( $takeover ) {
				self::sync_takeover( $cart, $candidates, $takeover );
				return;
			}
			$exclusive = array_values( array_filter( $qualified, static function ( $candidate ) { return empty( $candidate['stackable'] ); } ) );

			if ( $exclusive ) {
				usort( $exclusive, static function ( $left, $right ) {
					$amount = (float) $right['estimated_amount'] <=> (float) $left['estimated_amount'];
					return 0 !== $amount ? $amount : strcmp( (string) $left['code'], (string) $right['code'] );
				} );
				$wanted = array( $exclusive[0]['code'] );
			} else {
				$wanted = array_values( array_unique( array_map( static function ( $candidate ) { return $candidate['code']; }, $qualified ) ) );
			}

			$managed = array_values( array_unique( array_filter( array_map( static function ( $candidate ) { return $candidate['code']; }, $candidates ) ) ) );
			foreach ( $managed as $code ) {
				$should_apply = self::code_in_list( $code, $wanted );
				$applied      = $cart->has_discount( $code );
				if ( $should_apply && ! $applied ) {
					self::apply_silently( $cart, $code );
				} elseif ( ! $should_apply && $applied ) {
					$cart->remove_coupon( $code );
				}
			}
		} finally {
			self::$syncing = false;
		}
	}

	/** Enforce one exclusive sitewide rule against automatic and ordinary coupons. */
	private static function sync_takeover( $cart, $candidates, $rule ) {
		$managed = array_values( array_unique( array_filter( array_map( static function ( $candidate ) { return $candidate['code']; }, $candidates ) ) ) );
		$applied = method_exists( $cart, 'get_applied_coupons' ) ? $cart->get_applied_coupons() : array();
		$override = '';
		foreach ( $applied as $code ) {
			$coupon = self::coupon( $code );
			if ( $coupon && PepSelect_Sitewide_Discount::coupon_is_override( $coupon, $rule ) ) {
				$override = $code;
				break;
			}
		}

		if ( $override ) {
			foreach ( $applied as $code ) {
				if ( 0 !== strcasecmp( wc_format_coupon_code( $code ), wc_format_coupon_code( $override ) ) ) {
					$cart->remove_coupon( $code );
				}
			}
			return;
		}

		$sitewide_code = PepSelect_Sitewide_Discount::coupon_code_for_rule( $rule );
		$sitewide_qualifies = PepSelect_Sitewide_Discount::cart_qualifies( $cart, $rule );
		foreach ( $applied as $code ) {
			if ( self::code_in_list( $code, array( $sitewide_code ) ) && ! $sitewide_qualifies ) {
				$cart->remove_coupon( $code );
				continue;
			}
			if ( self::code_in_list( $code, $managed ) && ! self::code_in_list( $code, array( $sitewide_code ) ) ) {
				$cart->remove_coupon( $code );
				continue;
			}
			if ( ! self::code_in_list( $code, array( $sitewide_code ) ) ) {
				$coupon = self::coupon( $code );
				if ( ! $coupon || ! PepSelect_Sitewide_Discount::coupon_is_allowed( $coupon, $rule ) ) {
					$cart->remove_coupon( $code );
				}
			}
		}
		if ( $sitewide_qualifies && ! $cart->has_discount( $sitewide_code ) ) {
			self::apply_silently( $cart, $sitewide_code );
		}
	}

	/** Reject a new ordinary coupon unless the active takeover explicitly permits it. */
	public static function coupon_is_valid( $valid, $coupon ) {
		if ( ! $valid || ! $coupon instanceof WC_Coupon ) {
			return $valid;
		}
		$rule = self::takeover_rule();
		if ( ! $rule ) {
			return $valid;
		}
		$code = $coupon->get_code();
		if ( self::code_in_list( $code, array( PepSelect_Sitewide_Discount::coupon_code_for_rule( $rule ) ) ) ) {
			return true;
		}
		return PepSelect_Sitewide_Discount::coupon_is_allowed( $coupon, $rule ) || PepSelect_Sitewide_Discount::coupon_is_override( $coupon, $rule );
	}

	/** Give blocked coupons one useful customer-facing reason. */
	public static function coupon_error( $message, $error_code, $coupon ) {
		if ( $coupon instanceof WC_Coupon && self::takeover_rule() && ! self::coupon_is_valid( true, $coupon ) ) {
			return __( 'This code cannot be combined with the current sitewide promotion.', 'pepselect-bogo-quantity' );
		}
		return $message;
	}

	/** Keep the permitted sitewide pair when an allowed coupon is marked individual-use. */
	public static function keep_allowed_coupons( $coupons_to_keep, $coupon, $applied_coupons ) {
		$rule = self::takeover_rule();
		if ( ! $rule || ! $coupon instanceof WC_Coupon || ! PepSelect_Sitewide_Discount::coupon_is_allowed( $coupon, $rule ) ) {
			return $coupons_to_keep;
		}
		$sitewide_code = PepSelect_Sitewide_Discount::coupon_code_for_rule( $rule );
		foreach ( (array) $applied_coupons as $code ) {
			if ( self::code_in_list( $code, array( $sitewide_code ) ) ) {
				$coupons_to_keep[] = $code;
			}
		}
		return array_values( array_unique( $coupons_to_keep ) );
	}

	/** Permit only a configured sitewide/allowed-code pair beside individual-use coupons. */
	public static function allow_with_individual_coupon( $allowed, $coupon, $individual_coupon, $applied_coupons ) {
		$rule = self::takeover_rule();
		if ( ! $rule || ! $coupon instanceof WC_Coupon || ! $individual_coupon instanceof WC_Coupon ) {
			return $allowed;
		}
		$sitewide_code = PepSelect_Sitewide_Discount::coupon_code_for_rule( $rule );
		$new_is_sitewide = self::code_in_list( $coupon->get_code(), array( $sitewide_code ) );
		$old_is_sitewide = self::code_in_list( $individual_coupon->get_code(), array( $sitewide_code ) );
		if ( PepSelect_Sitewide_Discount::coupon_is_override( $coupon, $rule ) ) {
			return true;
		}
		if ( $new_is_sitewide && PepSelect_Sitewide_Discount::coupon_is_allowed( $individual_coupon, $rule ) ) {
			return true;
		}
		if ( $old_is_sitewide && PepSelect_Sitewide_Discount::coupon_is_allowed( $coupon, $rule ) ) {
			return true;
		}
		return $allowed;
	}

	/** @return array<string,mixed>|null */
	private static function takeover_rule() {
		return function_exists( 'WC' ) && WC() && WC()->cart ? PepSelect_Sitewide_Discount::active_takeover_rule( WC()->cart ) : null;
	}

	/** Whether an exclusive sitewide promotion currently owns the cart. */
	public static function sitewide_takeover_active() {
		return (bool) self::takeover_rule();
	}

	/** Resolve a coupon object without turning lookup failures into checkout errors. */
	private static function coupon( $code ) {
		try {
			$coupon = new WC_Coupon( $code );
			return $coupon->get_code() ? $coupon : null;
		} catch ( Throwable $error ) {
			return null;
		}
	}

	/** @param WC_Cart $cart Cart. @param string $code Code. */
	private static function apply_silently( $cart, $code ) {
		$notices = function_exists( 'wc_get_notices' ) ? wc_get_notices() : array();
		$cart->apply_coupon( $code );
		if ( function_exists( 'wc_set_notices' ) ) {
			wc_set_notices( $notices );
		}
	}

	/** @param string $code Code. @param string[] $codes Codes. */
	private static function code_in_list( $code, $codes ) {
		foreach ( $codes as $candidate ) {
			if ( 0 === strcasecmp( wc_format_coupon_code( $code ), wc_format_coupon_code( $candidate ) ) ) {
				return true;
			}
		}
		return false;
	}
}

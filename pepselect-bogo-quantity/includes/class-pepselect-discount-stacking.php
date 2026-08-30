<?php

defined( 'ABSPATH' ) || exit;

/** Coordinate automatic coupons so exclusive rules cannot combine accidentally. */
final class PepSelect_Discount_Stacking {
	/** @var bool */
	private static $syncing = false;

	/** Register one synchronization pass after every cart has loaded. */
	public static function boot() {
		add_action( 'woocommerce_before_calculate_totals', array( __CLASS__, 'sync' ), 30 );
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

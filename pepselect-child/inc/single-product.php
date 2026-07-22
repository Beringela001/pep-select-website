<?php
/**
 * Single compound page presentation (WEB-2E).
 *
 * Replaces the WooCommerce long-description output with the coded compound
 * block while leaving all commerce renderers (add to cart, quantity
 * discounts, points messaging, verification, gallery) in place. The COA
 * history carousel keeps its position: it renders through the plugin's
 * [pepselect_product_coa_carousel] shortcode after the summary, exactly
 * where the legacy template placed it.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue product styles on single product pages.
 *
 * @return void
 */
function pepselect_child_enqueue_product_assets() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}

	if ( function_exists( 'pepselect_child_is_elementor_editor_request' ) && pepselect_child_is_elementor_editor_request() ) {
		return;
	}

	wp_enqueue_style(
		'pepselect-child-cards',
		get_stylesheet_directory_uri() . '/assets/css/cards.css',
		array( 'pepselect-child-foundations' ),
		pepselect_child_asset_version( 'assets/css/cards.css' )
	);

	wp_enqueue_script(
		'pepselect-child-cards-js',
		get_stylesheet_directory_uri() . '/assets/js/cards.js',
		array(),
		pepselect_child_asset_version( 'assets/js/cards.js' ),
		true
	);

	wp_enqueue_style(
		'pepselect-child-product',
		get_stylesheet_directory_uri() . '/assets/css/product.css',
		array( 'pepselect-child-foundations' ),
		pepselect_child_asset_version( 'assets/css/product.css' )
	);

	wp_enqueue_script(
		'pepselect-child-product',
		get_stylesheet_directory_uri() . '/assets/js/product.js',
		array(),
		pepselect_child_asset_version( 'assets/js/product.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'pepselect_child_enqueue_product_assets', 40 );

/**
 * Add a body class for single product styling scope.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function pepselect_child_product_body_class( $classes ) {
	if ( function_exists( 'is_product' ) && is_product() ) {
		$classes[] = 'pepselect-single-compound';
	}

	return $classes;
}
add_filter( 'body_class', 'pepselect_child_product_body_class' );

/**
 * Remove default WooCommerce after-summary output. The coded template
 * renders the description, testing history, and related grid directly, in
 * the COA-consistent card layout.
 *
 * @return void
 */
function pepselect_child_swap_product_description() {
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
}
add_action( 'wp', 'pepselect_child_swap_product_description' );

/**
 * Report whether the current request is a coded single-product view.
 *
 * @return bool
 */
function pepselect_child_is_single_compound_request() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return false;
	}

	if ( function_exists( 'pepselect_child_is_elementor_editor_request' ) && pepselect_child_is_elementor_editor_request() ) {
		return false;
	}

	return true;
}

/**
 * Seize the single-product template so the legacy Elementor Theme Builder
 * single-product template no longer renders these pages. The late priority
 * mirrors the archive and homepage mechanism.
 *
 * @param string $template Resolved template.
 * @return string
 */
function pepselect_child_single_compound_template( $template ) {
	if ( ! pepselect_child_is_single_compound_request() ) {
		return $template;
	}

	$coded = get_stylesheet_directory() . '/templates/single-compound.php';

	return file_exists( $coded ) ? $coded : $template;
}
add_filter( 'template_include', 'pepselect_child_single_compound_template', 99 );

/**
 * Prevent Elementor's Theme Builder from taking over single-product output.
 *
 * @param bool $do_override Whether Elementor should override the location.
 * @param string $location  Theme Builder location.
 * @return bool
 */
function pepselect_child_block_elementor_single_product( $do_override, $location = '' ) {
	if ( 'single' === $location && pepselect_child_is_single_compound_request() ) {
		return false;
	}

	return $do_override;
}
add_filter( 'elementor/theme/need_override_location', 'pepselect_child_block_elementor_single_product', 10, 2 );



/**
 * Trim clutter from the product summary per M4 direction: remove the short
 * description ("High-purity research peptide") above the price and the SKU
 * and category/tag meta below add to cart. Commerce logic is untouched.
 *
 * @return void
 */
function pepselect_child_trim_product_summary() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}

	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
}
add_action( 'wp', 'pepselect_child_trim_product_summary' );

/**
 * Dollars returned per YITH point. Mirrors the rewards account math
 * (1 point = $0.01, so 100 points = $1.00) so the on-product rate and the
 * account balance speak the same conversion.
 */
if ( ! defined( 'PEPSELECT_CASHBACK_DOLLARS_PER_POINT' ) ) {
	define( 'PEPSELECT_CASHBACK_DOLLARS_PER_POINT', 0.01 );
}

/**
 * Report whether a product qualifies for the Buy 4, Get 1 Free promotion.
 *
 * The YITH Dynamic Pricing plugin that drives this promotion is not installed
 * yet, so this returns false and the B4G1 pill renders nowhere. The markup and
 * styling are built and gated here so that enabling the promotion later is a
 * one-line change to this helper (or a `pepselect_product_has_b4g1` filter),
 * with no template work.
 *
 * @param WC_Product|null $product Product being evaluated.
 * @return bool
 */
function pepselect_product_has_b4g1( $product = null ) {
	// Deliberately false until YITH Dynamic Pricing is installed and its rule
	// detection is wired in. Filterable so the promotion can be switched on
	// (globally or per product) without editing the template.
	return (bool) apply_filters( 'pepselect_product_has_b4g1', false, $product );
}

/**
 * Read the number of points a single unit of a product earns, live from
 * YITH Points & Rewards, honoring any per-product or per-category override.
 *
 * Follows the defensive, version-tolerant pattern used elsewhere in the theme
 * for YITH: try YITH's own earning calculator first (so overrides apply exactly
 * as they would at checkout), then the main instance, then derive from the
 * global earn-rate option, and finally expose a filter escape hatch. Returns a
 * float of points; 0 when nothing can be determined.
 *
 * @param WC_Product $product Product to evaluate.
 * @return float Points earned for one unit, or 0.
 */
function pepselect_child_get_product_earned_points( $product ) {
	if ( ! is_a( $product, 'WC_Product' ) ) {
		return 0.0;
	}

	$points = 0.0;
	$price  = (float) $product->get_price();

	// Primary: YITH's own earning calculator honors per-product and
	// per-category overrides identically to how points are awarded at checkout.
	if ( class_exists( 'YITH_WC_Points_Rewards_Earning' ) && method_exists( 'YITH_WC_Points_Rewards_Earning', 'get_instance' ) ) {
		$earning = YITH_WC_Points_Rewards_Earning::get_instance();

		if ( is_object( $earning ) ) {
			foreach ( array( 'get_points_earned_by_product', 'get_earned_points_by_product', 'get_product_points_earned', 'get_points_earned' ) as $method ) {
				if ( method_exists( $earning, $method ) ) {
					$maybe = $earning->$method( $product );

					if ( is_numeric( $maybe ) ) {
						$points = (float) $maybe;
						break;
					}
				}
			}
		}
	}

	// Secondary: the main plugin instance may expose the calculation directly.
	if ( ! $points && function_exists( 'YITH_WC_Points_Rewards' ) ) {
		$instance = YITH_WC_Points_Rewards();

		if ( is_object( $instance ) ) {
			foreach ( array( 'get_points_earned_by_product', 'get_product_points', 'get_earning_points' ) as $method ) {
				if ( method_exists( $instance, $method ) ) {
					$maybe = $instance->$method( $product );

					if ( is_numeric( $maybe ) ) {
						$points = (float) $maybe;
						break;
					}
				}
			}
		}
	}

	// Tertiary: derive from the stored global earn rate applied to the price.
	// YITH stores this as array( 'points' => x, 'money' => y ): earn x points
	// for every y spent. Reading the option keeps the rate live and unhardcoded.
	if ( ! $points && $price > 0 ) {
		$rate = get_option( 'ywpar_earn_points_rate' );

		if ( is_array( $rate ) && ! empty( $rate['points'] ) ) {
			$per_money = isset( $rate['money'] ) ? (float) $rate['money'] : 1.0;

			if ( $per_money > 0 ) {
				$points = floor( $price / $per_money ) * (float) $rate['points'];
			}
		}
	}

	return (float) apply_filters( 'pepselect_child_product_earned_points', $points, $product );
}

/**
 * Compute the effective cash-back rate for a product as a whole-or-decimal
 * percentage of its price, or null when the pill should not render.
 *
 * Returns null when Points & Rewards is inactive, the product has no usable
 * price, or the product earns nothing — so the caller hides the pill entirely.
 *
 * @param WC_Product $product Product to evaluate.
 * @return float|null Percentage (e.g. 3 or 2.5), or null to hide.
 */
function pepselect_child_get_product_cashback_rate( $product ) {
	if ( ! is_a( $product, 'WC_Product' ) ) {
		return null;
	}

	// Hide when Points & Rewards is not active at all.
	$active = function_exists( 'YITH_WC_Points_Rewards' ) || class_exists( 'YITH_WC_Points_Rewards_Earning' );

	if ( ! $active ) {
		return null;
	}

	$price = (float) $product->get_price();

	if ( $price <= 0 ) {
		return null;
	}

	$points  = pepselect_child_get_product_earned_points( $product );
	$dollars = $points * (float) PEPSELECT_CASHBACK_DOLLARS_PER_POINT;
	$rate    = ( $dollars / $price ) * 100;

	// Round to one decimal, then collapse a whole number to an integer so the
	// common case reads "3%" rather than "3.0%".
	$rate = round( $rate, 1 );

	if ( (float) (int) $rate === $rate ) {
		$rate = (int) $rate;
	}

	$rate = apply_filters( 'pepselect_child_cashback_rate', $rate, $product );

	// Hide when the product earns nothing.
	if ( ! $rate || $rate <= 0 ) {
		return null;
	}

	return $rate;
}

/**
 * Render the product promotion pills as a single wrapping flex row inside the
 * WooCommerce summary: the B4G1 pill first when it applies, the cash-back pill
 * second. Nothing is emitted when neither pill qualifies, so a lone cash-back
 * pill leaves no empty gap and a page with no pills adds no wrapper at all.
 *
 * @return void
 */
function pepselect_child_render_product_pills() {
	global $product;

	if ( ! is_a( $product, 'WC_Product' ) ) {
		return;
	}

	$has_b4g1      = pepselect_product_has_b4g1( $product );
	$cashback_rate = pepselect_child_get_product_cashback_rate( $product );

	if ( ! $has_b4g1 && null === $cashback_rate ) {
		return;
	}

	echo '<div class="pepselect-product-pills">';

	if ( $has_b4g1 ) {
		$b4g1_label = apply_filters( 'pepselect_child_b4g1_pill_label', __( 'Buy 4, get the 5th free', 'pepselect-child' ), $product );
		?>
		<span class="pepselect-pill pepselect-b4g1-pill">
			<svg class="pepselect-pill__icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24" width="16" height="16">
				<path fill="currentColor" d="M20 7h-2.2a3 3 0 0 0-.5-3.4 3 3 0 0 0-4.2 0L12 4.2l-1.1-1.1a3 3 0 0 0-4.2 0A3 3 0 0 0 6.2 7H4a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h1v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-7h1a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1Zm-6.5-2.1a1 1 0 1 1 1.4 1.4l-.7.7H13V5.6l.5-.7ZM9.1 4.9a1 1 0 0 1 1.4 0l.5.7V7H9.8l-.7-.7a1 1 0 0 1 0-1.4ZM11 19H7v-7h4v7Zm0-9H5V9h6v1Zm6 9h-4v-7h4v7Zm2-9h-6V9h6v1Z"></path>
			</svg>
			<span class="pepselect-pill__label"><?php echo esc_html( $b4g1_label ); ?></span>
		</span>
		<?php
	}

	if ( null !== $cashback_rate ) {
		$rate_display = (string) $cashback_rate;
		/* translators: %s: cash-back rate as a percentage, e.g. "3". */
		$cashback_label = apply_filters(
			'pepselect_child_cashback_pill_label',
			sprintf( __( 'Earn %s%% back in points', 'pepselect-child' ), $rate_display ),
			$cashback_rate,
			$product
		);
		?>
		<span class="pepselect-pill pepselect-cashback-pill">
			<svg class="pepselect-pill__icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24" width="16" height="16">
				<path fill="currentColor" d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8Zm.9-8.7c-1.6-.4-2.1-.7-2.1-1.3s.5-.9 1.3-.9a1.7 1.7 0 0 1 1.6.9l1.5-.9a3.1 3.1 0 0 0-2-1.5V6.5h-1.8v1.3c-1.3.3-2.2 1.2-2.2 2.5 0 1.7 1.4 2.3 2.9 2.7 1.4.3 1.7.7 1.7 1.3s-.6 1-1.4 1a2 2 0 0 1-1.9-1.2l-1.6.9a3.5 3.5 0 0 0 2.3 1.7v1.3h1.8v-1.3c1.4-.3 2.3-1.2 2.3-2.6 0-1.8-1.5-2.4-3.1-2.8Z"></path>
			</svg>
			<span class="pepselect-pill__label"><?php echo esc_html( $cashback_label ); ?></span>
		</span>
		<?php
	}

	echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'pepselect_child_render_product_pills', 8 );

/**
 * Suppress YITH's native on-product earn message so the coded cash-back pill is
 * the single source of truth for the rate. The theme's own pill sits in the
 * flex row above; leaving YITH's message in place would duplicate it.
 *
 * The native message is also hidden via CSS on the compound summary as a
 * belt-and-braces guard for versions that hook it through paths not covered by
 * this filter.
 *
 * @param bool $show Whether YITH should show the product message.
 * @return bool
 */
function pepselect_child_hide_native_points_message( $show ) {
	if ( function_exists( 'is_product' ) && is_product() ) {
		return false;
	}

	return $show;
}
add_filter( 'ywpar_show_points_message', 'pepselect_child_hide_native_points_message' );
add_filter( 'yith_ywpar_show_product_message', 'pepselect_child_hide_native_points_message' );

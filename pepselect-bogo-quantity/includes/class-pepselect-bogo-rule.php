<?php

defined( 'ABSPATH' ) || exit;

/** Own the Buy 4 Get 1 rule, pricing, admin setting, and Ops contract. */
final class PepSelect_BOGO_Rule {
	const OPTION         = 'pepselect_bogo_rule_v1';
	const PAGE_SLUG      = 'pepselect-buy-four-get-one';
	const REST_NAMESPACE = 'pepselect-bogo/v1';
	const SCHEMA_VERSION = 2;
	const COUPON_CODE    = 'Buy 4 Get 1 Free';
	const BUY_QUANTITY   = 4;
	const FREE_QUANTITY  = 1;

	/** @var bool */
	private static $syncing = false;

	/** Register WordPress, WooCommerce, and REST hooks. */
	public static function boot() {
		add_action( 'admin_menu', array( __CLASS__, 'register_settings_page' ) );
		add_action( 'admin_post_pepselect_save_bogo_rule', array( __CLASS__, 'handle_save' ) );
		add_action( 'rest_api_init', array( __CLASS__, 'register_rest_routes' ) );
		add_filter( 'woocommerce_get_shop_coupon_data', array( __CLASS__, 'provide_virtual_coupon' ), 10, 3 );
		add_filter( 'woocommerce_cart_totals_coupon_label', array( __CLASS__, 'coupon_label' ), 10, 2 );
		add_filter( 'woocommerce_cart_totals_coupon_html', array( __CLASS__, 'coupon_html' ), 30, 2 );
		add_filter( 'pepselect_product_has_b4g1', array( __CLASS__, 'product_page_is_eligible' ), 10, 2 );
		add_filter( 'pepselect_child_b4g1_pill_label', array( __CLASS__, 'product_page_label' ), 10, 2 );
	}

	/** @return string[] */
	public static function legacy_skus() {
		return array( 'GLP3R10', 'GLP3R20', 'GLP2T20', 'GLP1S10', 'MOTSC10', 'GHKCU50' );
	}

	/** @return int[] */
	private static function default_product_ids() {
		if ( ! function_exists( 'wc_get_product_id_by_sku' ) ) {
			return array();
		}
		$ids = array();
		foreach ( self::legacy_skus() as $sku ) {
			$id = absint( wc_get_product_id_by_sku( $sku ) );
			if ( $id ) {
				$ids[] = $id;
			}
		}
		return array_values( array_unique( $ids ) );
	}

	/** @return array<string,mixed> */
	public static function defaults() {
		return array(
			'schema_version' => self::SCHEMA_VERSION,
			'enabled'        => false,
			'product_ids'    => self::default_product_ids(),
			'buy_quantity'   => self::BUY_QUANTITY,
			'free_quantity'  => self::FREE_QUANTITY,
			'stackable'      => true,
			'label'          => self::COUPON_CODE,
		);
	}

	/** @param mixed $input Raw state. @return array<string,mixed> */
	public static function normalize_state( $input ) {
		$input       = is_array( $input ) ? $input : array();
		$defaults    = self::defaults();
		$product_ids = isset( $input['product_ids'] ) && is_array( $input['product_ids'] ) ? $input['product_ids'] : $defaults['product_ids'];
		$product_ids = array_values( array_unique( array_filter( array_map( 'absint', $product_ids ) ) ) );

		return array(
			'schema_version' => self::SCHEMA_VERSION,
			'enabled'        => ! empty( $input['enabled'] ),
			'product_ids'    => $product_ids,
			'buy_quantity'   => self::BUY_QUANTITY,
			'free_quantity'  => self::FREE_QUANTITY,
			'stackable'      => ! isset( $input['stackable'] ) || ! empty( $input['stackable'] ),
			'label'          => self::COUPON_CODE,
		);
	}

	/** @return array<string,mixed> */
	public static function get_state() {
		$stored = get_option( self::OPTION, null );
		$state  = self::normalize_state( is_array( $stored ) ? $stored : self::defaults() );
		return apply_filters( 'pepselect_bogo_rule', $state );
	}

	/** @param array<string,mixed> $state State. @return array<string,mixed> */
	private static function save_state( $state ) {
		$state = self::normalize_state( $state );
		update_option( self::OPTION, $state, false );
		do_action( 'pepselect_bogo_rule_updated', $state );
		return $state;
	}

	/** @param array<string,mixed>|null $state Optional state. @return bool */
	public static function is_enabled( $state = null ) {
		$state   = is_array( $state ) ? $state : self::get_state();
		$enabled = ! empty( $state['enabled'] ) && ! empty( $state['product_ids'] );
		return (bool) apply_filters( 'pepselect_bogo_enabled', $enabled, $state );
	}

	/** @param int $product_id Product or variation ID. @param array<string,mixed>|null $state Optional state. @return bool */
	public static function is_product_eligible( $product_id, $state = null ) {
		$state      = is_array( $state ) ? $state : self::get_state();
		$product_id = absint( $product_id );
		$candidates = array( $product_id );
		if ( function_exists( 'wc_get_product' ) ) {
			$product = wc_get_product( $product_id );
			if ( $product && $product->is_type( 'variation' ) ) {
				$candidates[] = absint( $product->get_parent_id() );
			}
		}
		$eligible = self::is_enabled( $state ) && (bool) array_intersect( array_map( 'absint', $state['product_ids'] ), $candidates );
		return (bool) apply_filters( 'pepselect_bogo_product_is_eligible', $eligible, $product_id, $state );
	}

	/** @param int $quantity Physical quantity. @return int */
	public static function free_vials( $quantity ) {
		$group = self::BUY_QUANTITY + self::FREE_QUANTITY;
		return intdiv( max( 0, absint( $quantity ) ), $group ) * self::FREE_QUANTITY;
	}

	/**
	 * Let the child theme's existing product-page pill use this rule instead of
	 * waiting for a YITH pricing notice that no longer owns the promotion.
	 *
	 * @param bool            $detected Legacy YITH-detected state; intentionally ignored.
	 * @param WC_Product|null $product  Product being rendered.
	 * @return bool
	 */
	public static function product_page_is_eligible( $detected, $product ) {
		unset( $detected );
		return $product instanceof WC_Product && self::is_product_eligible( $product->get_id() );
	}

	/** @param string $label Existing label. @param WC_Product|null $product Product. @return string */
	public static function product_page_label( $label, $product ) {
		return self::product_page_is_eligible( false, $product ) ? __( 'Buy 4 get 1 free', 'pepselect-bogo-quantity' ) : $label;
	}

	/** Add the settings screen below WooCommerce. */
	public static function register_settings_page() {
		add_submenu_page( PepSelect_Discount_Admin::PAGE_SLUG, __( 'Buy 4 Get 1', 'pepselect-bogo-quantity' ), __( 'Buy 4 Get 1', 'pepselect-bogo-quantity' ), 'manage_woocommerce', self::PAGE_SLUG, array( __CLASS__, 'render_settings_page' ) );
	}

	/** @param string $hook_suffix Current admin hook. */
	public static function enqueue_admin_assets( $hook_suffix ) {
		if ( 'woocommerce_page_' . self::PAGE_SLUG !== $hook_suffix ) {
			return;
		}
		wp_enqueue_style( 'woocommerce_admin_styles' );
		wp_enqueue_script( 'wc-enhanced-select' );
		wp_enqueue_style( 'pepselect-bogo-rule-admin', plugin_dir_url( PEPSELECT_BOGO_FILE ) . 'assets/compound-discount-admin.css', array(), PEPSELECT_BOGO_VERSION );
	}

	/** Render the single source-of-truth rule editor. */
	public static function render_settings_page() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}
		$state  = self::get_state();
		$notice = sanitize_key( wp_unslash( $_GET['pepselect_notice'] ?? '' ) );
		?>
		<div class="wrap pepselect-discount-settings">
			<?php PepSelect_Discount_Admin::render_header( self::PAGE_SLUG ); ?>
			<p class="pepselect-discount-intro"><?php esc_html_e( 'This plugin is the promotion authority. Keep the matching YITH rule inactive to prevent duplicate discounts.', 'pepselect-bogo-quantity' ); ?></p>
			<?php if ( 'saved' === $notice ) : ?><div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Buy 4 Get 1 settings saved.', 'pepselect-bogo-quantity' ); ?></p></div><?php endif; ?>
			<section class="pepselect-discount-panel" aria-labelledby="pepselect-bogo-title">
				<h2 id="pepselect-bogo-title"><?php esc_html_e( 'Promotion settings', 'pepselect-bogo-quantity' ); ?></h2>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="pepselect_save_bogo_rule">
					<?php wp_nonce_field( 'pepselect_save_bogo_rule' ); ?>
					<table class="form-table" role="presentation">
						<tr><th scope="row"><?php esc_html_e( 'Promotion status', 'pepselect-bogo-quantity' ); ?></th><td><label><input type="checkbox" name="rule[enabled]" value="1" <?php checked( $state['enabled'] ); ?>> <?php esc_html_e( 'Enabled', 'pepselect-bogo-quantity' ); ?></label><p class="description"><?php esc_html_e( 'Turning this off removes both the automatic discount and its cart message.', 'pepselect-bogo-quantity' ); ?></p></td></tr>
						<tr><th scope="row"><label for="pepselect-bogo-products"><?php esc_html_e( 'Compounds', 'pepselect-bogo-quantity' ); ?></label></th><td><select id="pepselect-bogo-products" class="wc-product-search" multiple="multiple" name="rule[product_ids][]" data-placeholder="<?php esc_attr_e( 'Search for compoundsâ€¦', 'pepselect-bogo-quantity' ); ?>" data-action="woocommerce_json_search_products_and_variations" required><?php foreach ( $state['product_ids'] as $product_id ) : $product = wc_get_product( $product_id ); if ( $product ) : ?><option value="<?php echo esc_attr( $product_id ); ?>" selected><?php echo wp_kses_post( $product->get_formatted_name() ); ?></option><?php endif; endforeach; ?></select><p class="description"><?php esc_html_e( 'Each selected compound earns one free vial for every five physical vials in its cart line.', 'pepselect-bogo-quantity' ); ?></p></td></tr>
						<tr><th scope="row"><?php esc_html_e( 'Stacking', 'pepselect-bogo-quantity' ); ?></th><td><select name="rule[stackable]"><option value="1" <?php selected( $state['stackable'], true ); ?>><?php esc_html_e( 'Stack with other discounts', 'pepselect-bogo-quantity' ); ?></option><option value="0" <?php selected( $state['stackable'], false ); ?>><?php esc_html_e( 'Do not stack', 'pepselect-bogo-quantity' ); ?></option></select><p class="description"><?php esc_html_e( 'When exclusive, Buy 4 Get 1 cannot combine with compound, sitewide, or coupon-code discounts.', 'pepselect-bogo-quantity' ); ?></p></td></tr>
						<tr><th scope="row"><?php esc_html_e( 'Customer label', 'pepselect-bogo-quantity' ); ?></th><td><code><?php echo esc_html( self::COUPON_CODE ); ?></code><p class="description"><?php esc_html_e( 'Shown as the automatic discount in Cart and Checkout.', 'pepselect-bogo-quantity' ); ?></p></td></tr>
					</table>
					<?php submit_button( __( 'Save promotion', 'pepselect-bogo-quantity' ) ); ?>
				</form>
			</section>
			<p class="pepselect-ops-note"><strong><?php esc_html_e( 'Ops-ready:', 'pepselect-bogo-quantity' ); ?></strong> <?php esc_html_e( 'This screen, the pricing engine, and the authenticated Ops endpoint use the same versioned rule.', 'pepselect-bogo-quantity' ); ?></p>
		</div>
		<?php
	}

	/** Save the admin rule. */
	public static function handle_save() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( esc_html__( 'You do not have permission to manage discounts.', 'pepselect-bogo-quantity' ), '', array( 'response' => 403 ) );
		}
		check_admin_referer( 'pepselect_save_bogo_rule' );
		$input = isset( $_POST['rule'] ) && is_array( $_POST['rule'] ) ? wp_unslash( $_POST['rule'] ) : array();
		$state = self::normalize_state( $input );
		if ( empty( $state['product_ids'] ) ) {
			wp_die( esc_html__( 'Select at least one compound.', 'pepselect-bogo-quantity' ), '', array( 'response' => 400 ) );
		}
		self::save_state( $state );
		wp_safe_redirect( add_query_arg( 'pepselect_notice', 'saved', admin_url( 'admin.php?page=' . self::PAGE_SLUG ) ) );
		exit;
	}

	/** Register the authenticated Ops contract. */
	public static function register_rest_routes() {
		register_rest_route( self::REST_NAMESPACE, '/buy-four-get-one', array(
			array( 'methods' => WP_REST_Server::READABLE, 'callback' => array( __CLASS__, 'rest_get' ), 'permission_callback' => array( __CLASS__, 'can_manage' ) ),
			array( 'methods' => WP_REST_Server::EDITABLE, 'callback' => array( __CLASS__, 'rest_update' ), 'permission_callback' => array( __CLASS__, 'can_manage' ) ),
		) );
	}

	/** @return bool */
	public static function can_manage() {
		return current_user_can( 'manage_woocommerce' );
	}

	/** @return WP_REST_Response */
	public static function rest_get() {
		return new WP_REST_Response( self::rest_payload( self::get_state() ), 200 );
	}

	/** @param WP_REST_Request $request Request. @return WP_REST_Response|WP_Error */
	public static function rest_update( $request ) {
		$body = $request->get_json_params();
		if ( ! in_array( absint( $body['schema_version'] ?? 0 ), array( 1, self::SCHEMA_VERSION ), true ) || ! is_array( $body['product_ids'] ?? null ) ) {
			return new WP_Error( 'pepselect_bogo_schema', __( 'schema_version 2 and a product_ids array are required.', 'pepselect-bogo-quantity' ), array( 'status' => 400 ) );
		}
		$current = self::get_state();
		if ( ! empty( $body['if_revision'] ) && ! hash_equals( self::revision( $current ), sanitize_text_field( $body['if_revision'] ) ) ) {
			return new WP_Error( 'pepselect_bogo_conflict', __( 'The Buy 4 Get 1 rule changed after Ops last read it.', 'pepselect-bogo-quantity' ), array( 'status' => 409 ) );
		}
		$state = self::normalize_state( $body );
		if ( empty( $state['product_ids'] ) ) {
			return new WP_Error( 'pepselect_bogo_products_required', __( 'Select at least one compound.', 'pepselect-bogo-quantity' ), array( 'status' => 400 ) );
		}
		$state = self::save_state( $state );
		return new WP_REST_Response( self::rest_payload( $state ), 200 );
	}

	/** Keep the automatic coupon synchronized with the one saved rule. */
	public static function sync_automatic_coupon( $cart ) {
		if ( self::$syncing || ! $cart instanceof WC_Cart || ( is_admin() && ! wp_doing_ajax() ) ) {
			return;
		}
		self::$syncing = true;
		$qualifies     = self::is_enabled() && self::cart_discount_amount( $cart ) > 0;
		$applied       = $cart->has_discount( self::COUPON_CODE );
		if ( $qualifies && ! $applied ) {
			$notices = function_exists( 'wc_get_notices' ) ? wc_get_notices() : array();
			$cart->apply_coupon( self::COUPON_CODE );
			if ( function_exists( 'wc_set_notices' ) ) {
				wc_set_notices( $notices );
			}
		} elseif ( ! $qualifies && $applied ) {
			$cart->remove_coupon( self::COUPON_CODE );
		}
		self::$syncing = false;
	}

	/** Expose this rule to the shared stacking coordinator. */
	public static function discount_candidates( $cart ) {
		$state  = self::get_state();
		$amount = self::cart_discount_amount( $cart );
		return array( array( 'code' => self::COUPON_CODE, 'qualifies' => self::is_enabled( $state ) && $amount > 0, 'stackable' => ! empty( $state['stackable'] ), 'estimated_amount' => $amount ) );
	}

	/** @param WC_Cart $cart Cart. @return float */
	public static function cart_discount_amount( $cart ) {
		$amount = 0.0;
		$state  = self::get_state();
		if ( ! self::is_enabled( $state ) ) {
			return $amount;
		}
		foreach ( $cart->get_cart() as $item ) {
			$product_id = ! empty( $item['variation_id'] ) ? $item['variation_id'] : ( $item['product_id'] ?? 0 );
			$free       = self::free_vials( $item['quantity'] ?? 0 );
			$product    = $item['data'] ?? null;
			if ( ! $free || ! $product instanceof WC_Product || ! self::is_product_eligible( $product_id, $state ) ) {
				continue;
			}
			$amount += max( 0, (float) $product->get_price() ) * $free;
		}
		return $amount;
	}

	/** @param false|array $data Coupon data. @param string $code Coupon code. @return false|array */
	public static function provide_virtual_coupon( $data, $code ) {
		if ( false !== $data && null !== $data || 0 !== strcasecmp( wc_format_coupon_code( $code ), wc_format_coupon_code( self::COUPON_CODE ) ) ) {
			return $data;
		}
		if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
			return $data;
		}
		$state  = self::get_state();
		$amount = self::cart_discount_amount( WC()->cart );
		// Keep an existing session coupon valid long enough for sync to remove it
		// silently after the rule is disabled or the cart stops qualifying.
		return array( 'code' => self::COUPON_CODE, 'description' => self::COUPON_CODE, 'discount_type' => 'fixed_cart', 'amount' => wc_format_decimal( $amount ), 'product_ids' => $state['product_ids'], 'individual_use' => empty( $state['stackable'] ), 'usage_limit' => 0, 'free_shipping' => false );
	}

	/** @param string $label Existing label. @param WC_Coupon $coupon Coupon. @return string */
	public static function coupon_label( $label, $coupon ) {
		return $coupon instanceof WC_Coupon && 0 === strcasecmp( $coupon->get_code(), self::COUPON_CODE ) ? esc_html( self::COUPON_CODE ) : $label;
	}

	/** Remove the classic WooCommerce remove link. */
	public static function coupon_html( $html, $coupon ) {
		if ( $coupon instanceof WC_Coupon && 0 === strcasecmp( $coupon->get_code(), self::COUPON_CODE ) ) {
			return preg_replace( '/\s*<a\b[^>]*woocommerce-remove-coupon[^>]*>.*?<\/a>/is', '', $html );
		}
		return $html;
	}

	/** @param array<string,mixed> $state State. @return array<string,mixed> */
	private static function rest_payload( $state ) {
		$skus = array();
		if ( function_exists( 'wc_get_product' ) ) {
			foreach ( $state['product_ids'] as $product_id ) {
				$product = wc_get_product( $product_id );
				if ( $product && $product->get_sku() ) {
					$skus[] = $product->get_sku();
				}
			}
		}
		return array_merge( $state, array( 'revision' => self::revision( $state ), 'eligible_skus' => array_values( array_unique( $skus ) ), 'contract' => array( 'endpoint' => '/pepselect-bogo/v1/buy-four-get-one', 'authority' => 'plugin', 'yith_rule_must_be_inactive' => true, 'stackable' => true ) ) );
	}

	/** @param array<string,mixed> $state State. @return string */
	private static function revision( $state ) {
		return hash( 'sha256', wp_json_encode( self::normalize_state( $state ) ) );
	}
}

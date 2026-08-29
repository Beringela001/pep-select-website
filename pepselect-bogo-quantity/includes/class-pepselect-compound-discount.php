<?php

defined( 'ABSPATH' ) || exit;

/**
 * Configurable automatic compound promotion.
 *
 * The versioned option and REST contract are deliberately independent from
 * the admin form so Pep Select Ops can manage the same rule in the future.
 */
final class PepSelect_Compound_Discount {
	const OPTION         = 'pepselect_compound_discount_rule_v1';
	const OPTION_GROUP   = 'pepselect_compound_discount';
	const PAGE_SLUG      = 'pepselect-compound-discounts';
	const COUPON_CODE    = 'pepselect-auto-compound';
	const REST_NAMESPACE = 'pepselect-bogo/v1';
	const SCHEMA_VERSION = 1;

	/** @var bool */
	private static $syncing = false;

	/** Register WordPress, WooCommerce, and REST hooks. */
	public static function boot() {
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
		add_action( 'admin_menu', array( __CLASS__, 'register_settings_page' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_assets' ) );
		add_action( 'rest_api_init', array( __CLASS__, 'register_rest_routes' ) );
		add_action( 'woocommerce_before_calculate_totals', array( __CLASS__, 'sync_automatic_coupon' ), 20 );
		add_filter( 'woocommerce_get_shop_coupon_data', array( __CLASS__, 'provide_virtual_coupon' ), 10, 3 );
		add_filter( 'woocommerce_cart_totals_coupon_label', array( __CLASS__, 'coupon_label' ), 10, 2 );
	}

	/** @return array<string,mixed> */
	public static function defaults() {
		return array(
			'schema_version'   => self::SCHEMA_VERSION,
			'enabled'          => false,
			'product_ids'      => array(),
			'match_mode'       => 'all',
			'discount_type'    => 'percent',
			'discount_amount'  => '10',
			'threshold_type'   => 'quantity',
			'threshold_amount' => '2',
			'label'            => 'Compound promotion',
		);
	}

	/** @return array<string,mixed> */
	public static function get_rule() {
		$stored = get_option( self::OPTION, array() );
		return wp_parse_args( is_array( $stored ) ? $stored : array(), self::defaults() );
	}

	/** Register the versioned rule as one atomic option. */
	public static function register_settings() {
		register_setting(
			self::OPTION_GROUP,
			self::OPTION,
			array(
				'type'              => 'array',
				'default'           => self::defaults(),
				'sanitize_callback' => array( __CLASS__, 'sanitize_admin_rule' ),
				'show_in_rest'      => false,
			)
		);
	}

	/** @param mixed $input Raw settings value. @return array<string,mixed> */
	public static function sanitize_admin_rule( $input ) {
		$rule  = self::sanitize_rule( $input );
		$error = self::validate_rule( $rule );
		if ( is_wp_error( $error ) ) {
			$rule['enabled'] = false;
			add_settings_error( self::OPTION, $error->get_error_code(), $error->get_error_message(), 'error' );
		}
		return $rule;
	}

	/** @param mixed $input Raw rule. @return array<string,mixed> */
	public static function sanitize_rule( $input ) {
		$input       = is_array( $input ) ? $input : array();
		$product_ids = isset( $input['product_ids'] ) && is_array( $input['product_ids'] ) ? $input['product_ids'] : array();
		$product_ids = array_values( array_unique( array_filter( array_map( 'absint', $product_ids ) ) ) );
		$match_mode  = in_array( $input['match_mode'] ?? '', array( 'any', 'all' ), true ) ? $input['match_mode'] : 'all';
		$discount    = in_array( $input['discount_type'] ?? '', array( 'percent', 'fixed_cart' ), true ) ? $input['discount_type'] : 'percent';
		$threshold   = in_array( $input['threshold_type'] ?? '', array( 'quantity', 'subtotal' ), true ) ? $input['threshold_type'] : 'quantity';
		$amount      = max( 0, (float) wc_format_decimal( $input['discount_amount'] ?? 0 ) );
		$minimum     = max( 0, (float) wc_format_decimal( $input['threshold_amount'] ?? 0 ) );

		if ( 'percent' === $discount ) {
			$amount = min( 100, $amount );
		}
		if ( 'quantity' === $threshold ) {
			$minimum = max( 1, absint( $minimum ) );
		}

		return array(
			'schema_version'   => self::SCHEMA_VERSION,
			'enabled'          => ! empty( $input['enabled'] ),
			'product_ids'      => $product_ids,
			'match_mode'       => $match_mode,
			'discount_type'    => $discount,
			'discount_amount'  => wc_format_decimal( $amount ),
			'threshold_type'   => $threshold,
			'threshold_amount' => wc_format_decimal( $minimum ),
			'label'            => sanitize_text_field( $input['label'] ?? self::defaults()['label'] ),
		);
	}

	/** @param array<string,mixed> $rule Rule to validate. @return true|WP_Error */
	public static function validate_rule( $rule ) {
		if ( empty( $rule['enabled'] ) ) {
			return true;
		}
		if ( empty( $rule['product_ids'] ) ) {
			return new WP_Error( 'pepselect_discount_products_required', __( 'Select at least one compound before enabling the promotion.', 'pepselect-bogo-quantity' ) );
		}
		if ( (float) $rule['discount_amount'] <= 0 ) {
			return new WP_Error( 'pepselect_discount_amount_required', __( 'Enter a discount greater than zero before enabling the promotion.', 'pepselect-bogo-quantity' ) );
		}
		if ( (float) $rule['threshold_amount'] <= 0 ) {
			return new WP_Error( 'pepselect_discount_threshold_required', __( 'Enter a minimum greater than zero before enabling the promotion.', 'pepselect-bogo-quantity' ) );
		}
		return true;
	}

	/** Add the settings screen below WooCommerce. */
	public static function register_settings_page() {
		add_submenu_page(
			'woocommerce',
			__( 'Compound Discounts', 'pepselect-bogo-quantity' ),
			__( 'Compound Discounts', 'pepselect-bogo-quantity' ),
			'manage_woocommerce',
			self::PAGE_SLUG,
			array( __CLASS__, 'render_settings_page' )
		);
	}

	/** @param string $hook_suffix Current admin hook. */
	public static function enqueue_admin_assets( $hook_suffix ) {
		if ( 'woocommerce_page_' . self::PAGE_SLUG !== $hook_suffix ) {
			return;
		}
		wp_enqueue_style( 'woocommerce_admin_styles' );
		wp_enqueue_script( 'wc-enhanced-select' );
		wp_enqueue_style(
			'pepselect-compound-discount-admin',
			plugin_dir_url( PEPSELECT_BOGO_FILE ) . 'assets/compound-discount-admin.css',
			array(),
			PEPSELECT_BOGO_VERSION
		);
	}

	/** Render the WooCommerce admin settings screen. */
	public static function render_settings_page() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}
		$rule = self::get_rule();
		$name = self::OPTION;
		?>
		<div class="wrap pepselect-discount-settings">
			<h1><?php esc_html_e( 'Compound Discounts', 'pepselect-bogo-quantity' ); ?></h1>
			<p><?php esc_html_e( 'Automatically apply one order discount when the selected compound and minimum conditions are met.', 'pepselect-bogo-quantity' ); ?></p>
			<?php settings_errors( self::OPTION ); ?>
			<form method="post" action="options.php">
				<?php settings_fields( self::OPTION_GROUP ); ?>
				<input type="hidden" name="<?php echo esc_attr( $name ); ?>[schema_version]" value="1">
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><?php esc_html_e( 'Promotion status', 'pepselect-bogo-quantity' ); ?></th>
						<td><label class="pepselect-switch"><input type="checkbox" role="switch" name="<?php echo esc_attr( $name ); ?>[enabled]" value="1" <?php checked( ! empty( $rule['enabled'] ) ); ?>><span aria-hidden="true"></span><strong><?php esc_html_e( 'Enabled', 'pepselect-bogo-quantity' ); ?></strong></label><p class="description"><?php esc_html_e( 'Turn this off to stop new automatic discounts without deleting the rule.', 'pepselect-bogo-quantity' ); ?></p></td>
					</tr>
					<tr>
						<th scope="row"><label for="pepselect-discount-products"><?php esc_html_e( 'Compounds', 'pepselect-bogo-quantity' ); ?></label></th>
						<td><select id="pepselect-discount-products" class="wc-product-search" multiple="multiple" style="width: 50%;" name="<?php echo esc_attr( $name ); ?>[product_ids][]" data-placeholder="<?php esc_attr_e( 'Search for compounds…', 'pepselect-bogo-quantity' ); ?>" data-action="woocommerce_json_search_products_and_variations"><?php foreach ( $rule['product_ids'] as $product_id ) : $product = wc_get_product( $product_id ); if ( $product ) : ?><option value="<?php echo esc_attr( $product_id ); ?>" selected><?php echo wp_kses_post( $product->get_formatted_name() ); ?></option><?php endif; endforeach; ?></select><p class="description"><?php esc_html_e( 'Only these compounds count toward the minimum.', 'pepselect-bogo-quantity' ); ?></p></td>
					</tr>
					<tr>
						<th scope="row"><label for="pepselect-match-mode"><?php esc_html_e( 'Compound requirement', 'pepselect-bogo-quantity' ); ?></label></th>
						<td><select id="pepselect-match-mode" name="<?php echo esc_attr( $name ); ?>[match_mode]"><option value="all" <?php selected( $rule['match_mode'], 'all' ); ?>><?php esc_html_e( 'Require all selected compounds', 'pepselect-bogo-quantity' ); ?></option><option value="any" <?php selected( $rule['match_mode'], 'any' ); ?>><?php esc_html_e( 'Require any selected compound', 'pepselect-bogo-quantity' ); ?></option></select></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Discount', 'pepselect-bogo-quantity' ); ?></th>
						<td><select name="<?php echo esc_attr( $name ); ?>[discount_type]"><option value="percent" <?php selected( $rule['discount_type'], 'percent' ); ?>><?php esc_html_e( 'Percentage', 'pepselect-bogo-quantity' ); ?></option><option value="fixed_cart" <?php selected( $rule['discount_type'], 'fixed_cart' ); ?>><?php esc_html_e( 'Fixed dollar amount', 'pepselect-bogo-quantity' ); ?></option></select> <input type="number" min="0.01" step="0.01" name="<?php echo esc_attr( $name ); ?>[discount_amount]" value="<?php echo esc_attr( $rule['discount_amount'] ); ?>" class="small-text" required></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Minimum', 'pepselect-bogo-quantity' ); ?></th>
						<td><select name="<?php echo esc_attr( $name ); ?>[threshold_type]"><option value="quantity" <?php selected( $rule['threshold_type'], 'quantity' ); ?>><?php esc_html_e( 'Eligible item quantity', 'pepselect-bogo-quantity' ); ?></option><option value="subtotal" <?php selected( $rule['threshold_type'], 'subtotal' ); ?>><?php esc_html_e( 'Eligible item subtotal', 'pepselect-bogo-quantity' ); ?></option></select> <input type="number" min="0.01" step="0.01" name="<?php echo esc_attr( $name ); ?>[threshold_amount]" value="<?php echo esc_attr( $rule['threshold_amount'] ); ?>" class="small-text" required><p class="description"><?php esc_html_e( 'Measured before discounts and only across the selected compounds.', 'pepselect-bogo-quantity' ); ?></p></td>
					</tr>
					<tr>
						<th scope="row"><label for="pepselect-discount-label"><?php esc_html_e( 'Customer label', 'pepselect-bogo-quantity' ); ?></label></th>
						<td><input id="pepselect-discount-label" type="text" class="regular-text" name="<?php echo esc_attr( $name ); ?>[label]" value="<?php echo esc_attr( $rule['label'] ); ?>" maxlength="80"><p class="description"><?php esc_html_e( 'Shown beside the automatic discount in Cart and Checkout.', 'pepselect-bogo-quantity' ); ?></p></td>
					</tr>
				</table>
				<?php submit_button( __( 'Save discount', 'pepselect-bogo-quantity' ) ); ?>
			</form>
			<p class="pepselect-ops-note"><strong><?php esc_html_e( 'Ops-ready:', 'pepselect-bogo-quantity' ); ?></strong> <?php esc_html_e( 'This screen and the authenticated Ops endpoint use the same versioned rule.', 'pepselect-bogo-quantity' ); ?></p>
		</div>
		<?php
	}

	/** Register the future Ops read/write contract. */
	public static function register_rest_routes() {
		register_rest_route(
			self::REST_NAMESPACE,
			'/compound-discount',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( __CLASS__, 'rest_get_rule' ),
					'permission_callback' => array( __CLASS__, 'can_manage_discounts' ),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( __CLASS__, 'rest_update_rule' ),
					'permission_callback' => array( __CLASS__, 'can_manage_discounts' ),
				),
			)
		);
	}

	/** @return bool */
	public static function can_manage_discounts() {
		return current_user_can( 'manage_woocommerce' );
	}

	/** @return WP_REST_Response */
	public static function rest_get_rule() {
		return new WP_REST_Response( self::rest_payload( self::get_rule() ), 200 );
	}

	/** @param WP_REST_Request $request Request. @return WP_REST_Response|WP_Error */
	public static function rest_update_rule( $request ) {
		$body = $request->get_json_params();
		if ( self::SCHEMA_VERSION !== absint( $body['schema_version'] ?? 0 ) || ! is_array( $body['rule'] ?? null ) ) {
			return new WP_Error( 'pepselect_discount_schema', __( 'schema_version 1 and a rule object are required.', 'pepselect-bogo-quantity' ), array( 'status' => 400 ) );
		}

		$current = self::get_rule();
		if ( ! empty( $body['if_revision'] ) && ! hash_equals( self::revision( $current ), sanitize_text_field( $body['if_revision'] ) ) ) {
			return new WP_Error( 'pepselect_discount_conflict', __( 'The discount rule changed after Ops last read it.', 'pepselect-bogo-quantity' ), array( 'status' => 409 ) );
		}

		$rule  = self::sanitize_rule( $body['rule'] );
		$error = self::validate_rule( $rule );
		if ( is_wp_error( $error ) ) {
			$error->add_data( array( 'status' => 400 ) );
			return $error;
		}

		update_option( self::OPTION, $rule, false );
		return new WP_REST_Response( self::rest_payload( $rule ), 200 );
	}

	/** @param array<string,mixed> $rule Rule. @return array<string,mixed> */
	private static function rest_payload( $rule ) {
		return array(
			'schema_version' => self::SCHEMA_VERSION,
			'revision'       => self::revision( $rule ),
			'rule'           => $rule,
			'contract'       => array(
				'match_mode'     => array( 'any', 'all' ),
				'discount_type'  => array( 'percent', 'fixed_cart' ),
				'threshold_type' => array( 'quantity', 'subtotal' ),
				'coupon_code'    => self::COUPON_CODE,
			),
		);
	}

	/** @param array<string,mixed> $rule Rule. @return string */
	private static function revision( $rule ) {
		return hash( 'sha256', wp_json_encode( $rule ) );
	}

	/** @param WC_Cart $cart Cart. */
	public static function sync_automatic_coupon( $cart ) {
		if ( self::$syncing || ! $cart instanceof WC_Cart || ( is_admin() && ! wp_doing_ajax() ) ) {
			return;
		}

		self::$syncing = true;
		$qualifies     = self::cart_qualifies( $cart, self::get_rule() );
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

	/** @param WC_Cart $cart Cart. @param array<string,mixed> $rule Rule. @return bool */
	public static function cart_qualifies( $cart, $rule ) {
		if ( empty( $rule['enabled'] ) || is_wp_error( self::validate_rule( $rule ) ) ) {
			return false;
		}

		$selected = array_map( 'absint', $rule['product_ids'] );
		$present  = array();
		$quantity = 0;
		$subtotal = 0.0;

		foreach ( $cart->get_cart() as $item ) {
			$candidates = array_filter( array( absint( $item['variation_id'] ?? 0 ), absint( $item['product_id'] ?? 0 ) ) );
			$matches    = array_values( array_intersect( $selected, $candidates ) );
			if ( empty( $matches ) ) {
				continue;
			}
			$present  = array_merge( $present, $matches );
			$quantity += max( 0, absint( $item['quantity'] ?? 0 ) );
			$subtotal += max( 0, (float) ( $item['line_subtotal'] ?? 0 ) );
		}

		$present       = array_unique( $present );
		$products_pass = 'all' === $rule['match_mode'] ? empty( array_diff( $selected, $present ) ) : ! empty( $present );
		$minimum_pass  = 'subtotal' === $rule['threshold_type']
			? $subtotal >= (float) $rule['threshold_amount']
			: $quantity >= absint( $rule['threshold_amount'] );

		return $products_pass && $minimum_pass;
	}

	/** @param false|array $data Coupon data. @param string $code Coupon code. @return false|array */
	public static function provide_virtual_coupon( $data, $code ) {
		if ( self::COUPON_CODE !== wc_format_coupon_code( $code ) || ! function_exists( 'WC' ) || ! WC()->cart ) {
			return $data;
		}
		$rule = self::get_rule();
		if ( ! self::cart_qualifies( WC()->cart, $rule ) ) {
			return $data;
		}
		return array(
			'code'           => self::COUPON_CODE,
			'discount_type'  => $rule['discount_type'],
			'amount'         => $rule['discount_amount'],
			'individual_use' => false,
			'usage_limit'    => 0,
			'free_shipping'  => false,
		);
	}

	/** @param string $label Existing label. @param WC_Coupon $coupon Coupon. @return string */
	public static function coupon_label( $label, $coupon ) {
		if ( $coupon instanceof WC_Coupon && self::COUPON_CODE === $coupon->get_code() ) {
			$rule = self::get_rule();
			return esc_html( $rule['label'] ?: self::defaults()['label'] );
		}
		return $label;
	}
}

<?php

defined( 'ABSPATH' ) || exit;

/** Manage independent automatic compound discounts. */
final class PepSelect_Compound_Discount {
	const LEGACY_OPTION  = 'pepselect_compound_discount_rule_v1';
	const OPTION         = 'pepselect_compound_discount_rules_v2';
	const PAGE_SLUG      = 'pepselect-compound-discounts';
	const LEGACY_CODE    = 'pepselect-auto-compound';
	const REST_NAMESPACE = 'pepselect-bogo/v1';
	const SCHEMA_VERSION = 3;
	const LABEL_LIMIT    = 24;
	const MAX_RULES      = 50;

	/** @var bool */
	private static $syncing = false;

	/** Register WordPress, WooCommerce, and REST hooks. */
	public static function boot() {
		add_action( 'admin_menu', array( __CLASS__, 'register_settings_page' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_frontend_assets' ) );
		add_action( 'admin_post_pepselect_save_compound_discount', array( __CLASS__, 'handle_save_rule' ) );
		add_action( 'admin_post_pepselect_toggle_compound_discount', array( __CLASS__, 'handle_toggle_rule' ) );
		add_action( 'admin_post_pepselect_delete_compound_discount', array( __CLASS__, 'handle_delete_rule' ) );
		add_action( 'rest_api_init', array( __CLASS__, 'register_rest_routes' ) );
		add_filter( 'woocommerce_get_shop_coupon_data', array( __CLASS__, 'provide_virtual_coupon' ), 10, 3 );
		add_filter( 'woocommerce_cart_totals_coupon_label', array( __CLASS__, 'coupon_label' ), 10, 2 );
		add_filter( 'woocommerce_cart_totals_coupon_html', array( __CLASS__, 'coupon_html' ), 30, 2 );
	}

	/** @return array<string,mixed> */
	public static function defaults() {
		return array(
			'id'               => '',
			'enabled'          => false,
			'product_ids'      => array(),
			'match_mode'       => 'all',
			'discount_type'    => 'percent',
			'discount_amount'  => '10',
			'threshold_type'   => 'quantity',
			'threshold_amount' => '2',
			'stackable'        => true,
			'label'            => '',
		);
	}

	/** @return array<string,mixed> */
	public static function get_state() {
		$stored = get_option( self::OPTION, null );
		if ( is_array( $stored ) && isset( $stored['rules'] ) ) {
			return self::normalize_state( $stored );
		}
		return self::legacy_state();
	}

	/** Preserve the existing single rule until the first v2 change is saved. */
	private static function legacy_state() {
		$legacy = get_option( self::LEGACY_OPTION, array() );
		$state  = array(
			'schema_version'       => self::SCHEMA_VERSION,
			'rules'                => array(),
			'retired_coupon_codes' => array( self::LEGACY_CODE ),
		);

		if ( ! is_array( $legacy ) || empty( $legacy ) ) {
			return $state;
		}

		$legacy['id'] = 'legacy-' . substr( md5( wp_json_encode( $legacy ) ), 0, 8 );
		$rule         = self::sanitize_rule( $legacy );
		if ( ! is_wp_error( self::validate_rule( $rule ) ) ) {
			$state['rules'][] = $rule;
		}
		return self::normalize_state( $state );
	}

	/** @param mixed $input Raw rule. @return array<string,mixed> */
	public static function sanitize_rule( $input ) {
		$input       = is_array( $input ) ? $input : array();
		$product_ids = isset( $input['product_ids'] ) && is_array( $input['product_ids'] ) ? $input['product_ids'] : array();
		$product_ids = array_values( array_unique( array_filter( array_map( 'absint', $product_ids ) ) ) );
		$discount    = in_array( $input['discount_type'] ?? '', array( 'percent', 'fixed_cart' ), true ) ? $input['discount_type'] : 'percent';
		$threshold   = in_array( $input['threshold_type'] ?? '', array( 'quantity', 'subtotal' ), true ) ? $input['threshold_type'] : 'quantity';
		$amount      = max( 0, (float) wc_format_decimal( $input['discount_amount'] ?? 0 ) );
		$minimum     = max( 0, (float) wc_format_decimal( $input['threshold_amount'] ?? 0 ) );
		$label       = sanitize_text_field( $input['label'] ?? '' );

		if ( function_exists( 'mb_substr' ) ) {
			$label = mb_substr( $label, 0, self::LABEL_LIMIT );
		} else {
			$label = substr( $label, 0, self::LABEL_LIMIT );
		}
		if ( 'percent' === $discount ) {
			$amount = min( 100, $amount );
		}
		if ( 'quantity' === $threshold ) {
			$minimum = max( 1, absint( $minimum ) );
		}

		return array(
			'id'               => sanitize_key( $input['id'] ?? '' ),
			'enabled'          => ! empty( $input['enabled'] ),
			'product_ids'      => $product_ids,
			'match_mode'       => in_array( $input['match_mode'] ?? '', array( 'any', 'all' ), true ) ? $input['match_mode'] : 'all',
			'discount_type'    => $discount,
			'discount_amount'  => wc_format_decimal( $amount ),
			'threshold_type'   => $threshold,
			'threshold_amount' => wc_format_decimal( $minimum ),
			'stackable'        => ! isset( $input['stackable'] ) || ! empty( $input['stackable'] ),
			'label'            => $label,
		);
	}

	/** @param array<string,mixed> $rule Rule. @return true|WP_Error */
	public static function validate_rule( $rule ) {
		if ( '' === trim( $rule['label'] ?? '' ) ) {
			return new WP_Error( 'pepselect_discount_label_required', __( 'Enter a customer label.', 'pepselect-bogo-quantity' ) );
		}
		if ( empty( $rule['product_ids'] ) ) {
			return new WP_Error( 'pepselect_discount_products_required', __( 'Select at least one compound.', 'pepselect-bogo-quantity' ) );
		}
		if ( (float) ( $rule['discount_amount'] ?? 0 ) <= 0 ) {
			return new WP_Error( 'pepselect_discount_amount_required', __( 'Enter a discount greater than zero.', 'pepselect-bogo-quantity' ) );
		}
		if ( (float) ( $rule['threshold_amount'] ?? 0 ) <= 0 ) {
			return new WP_Error( 'pepselect_discount_threshold_required', __( 'Enter a minimum greater than zero.', 'pepselect-bogo-quantity' ) );
		}
		return true;
	}

	/** Add the settings screen below WooCommerce. */
	public static function register_settings_page() {
		add_submenu_page( PepSelect_Discount_Admin::PAGE_SLUG, __( 'Compound Discounts', 'pepselect-bogo-quantity' ), __( 'Compound Discounts', 'pepselect-bogo-quantity' ), 'manage_woocommerce', self::PAGE_SLUG, array( __CLASS__, 'render_settings_page' ) );
	}

	/** @param string $hook_suffix Current admin hook. */
	public static function enqueue_admin_assets( $hook_suffix ) {
		if ( 'woocommerce_page_' . self::PAGE_SLUG !== $hook_suffix ) {
			return;
		}
		wp_enqueue_style( 'woocommerce_admin_styles' );
		wp_enqueue_script( 'wc-enhanced-select' );
		wp_enqueue_style( 'pepselect-compound-discount-admin', plugin_dir_url( PEPSELECT_BOGO_FILE ) . 'assets/compound-discount-admin.css', array(), PEPSELECT_BOGO_VERSION );
	}

	/** Keep automatic coupons non-removable in WooCommerce Blocks. */
	public static function enqueue_frontend_assets() {
		if ( ! function_exists( 'is_cart' ) || ( ! is_cart() && ! is_checkout() ) ) {
			return;
		}
		$codes = array_map( array( __CLASS__, 'coupon_code_for_rule' ), self::get_state()['rules'] );
		if ( class_exists( 'PepSelect_BOGO_Rule' ) ) {
			$codes[] = PepSelect_BOGO_Rule::COUPON_CODE;
		}
		if ( class_exists( 'PepSelect_Sitewide_Discount' ) ) {
			foreach ( PepSelect_Sitewide_Discount::get_state()['rules'] as $rule ) {
				$codes[] = PepSelect_Sitewide_Discount::coupon_code_for_rule( $rule );
			}
		}
		$codes = array_values( array_unique( array_filter( $codes ) ) );
		if ( empty( $codes ) ) {
			return;
		}
		wp_enqueue_script( 'pepselect-compound-discount-frontend', plugin_dir_url( PEPSELECT_BOGO_FILE ) . 'assets/compound-discount-frontend.js', array(), PEPSELECT_BOGO_VERSION, true );
		wp_localize_script( 'pepselect-compound-discount-frontend', 'pepselectAutomaticDiscounts', array( 'codes' => array_values( $codes ) ) );
	}

	/** Render the discount editor and saved rule list. */
	public static function render_settings_page() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}
		$state   = self::get_state();
		$edit_id = sanitize_key( wp_unslash( $_GET['edit'] ?? '' ) );
		$rule    = self::find_rule( $state['rules'], $edit_id );
		$rule    = $rule ? $rule : self::defaults();
		?>
		<div class="wrap pepselect-discount-settings">
			<?php PepSelect_Discount_Admin::render_header( self::PAGE_SLUG ); ?>
			<p class="pepselect-discount-intro"><?php esc_html_e( 'Create automatic discounts that can run independently or at the same time.', 'pepselect-bogo-quantity' ); ?></p>
			<?php self::render_admin_notice(); ?>
			<div class="pepselect-discount-layout">
				<section class="pepselect-discount-panel" aria-labelledby="pepselect-editor-title">
					<h2 id="pepselect-editor-title"><?php echo $edit_id ? esc_html__( 'Edit discount', 'pepselect-bogo-quantity' ) : esc_html__( 'Add discount', 'pepselect-bogo-quantity' ); ?></h2>
					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
						<input type="hidden" name="action" value="pepselect_save_compound_discount">
						<input type="hidden" name="rule[id]" value="<?php echo esc_attr( $rule['id'] ); ?>">
						<?php wp_nonce_field( 'pepselect_save_compound_discount' ); ?>
						<table class="form-table" role="presentation">
							<tr><th scope="row"><label for="pepselect-discount-products"><?php esc_html_e( 'Compounds', 'pepselect-bogo-quantity' ); ?></label></th><td><select id="pepselect-discount-products" class="wc-product-search" multiple="multiple" name="rule[product_ids][]" data-placeholder="<?php esc_attr_e( 'Search for compounds…', 'pepselect-bogo-quantity' ); ?>" data-action="woocommerce_json_search_products_and_variations" required><?php foreach ( $rule['product_ids'] as $product_id ) : $product = wc_get_product( $product_id ); if ( $product ) : ?><option value="<?php echo esc_attr( $product_id ); ?>" selected><?php echo wp_kses_post( $product->get_formatted_name() ); ?></option><?php endif; endforeach; ?></select><p class="description"><?php esc_html_e( 'Only these compounds count toward the minimum.', 'pepselect-bogo-quantity' ); ?></p></td></tr>
							<tr><th scope="row"><label for="pepselect-match-mode"><?php esc_html_e( 'Compound requirement', 'pepselect-bogo-quantity' ); ?></label></th><td><select id="pepselect-match-mode" name="rule[match_mode]"><option value="all" <?php selected( $rule['match_mode'], 'all' ); ?>><?php esc_html_e( 'Require all selected compounds', 'pepselect-bogo-quantity' ); ?></option><option value="any" <?php selected( $rule['match_mode'], 'any' ); ?>><?php esc_html_e( 'Require any selected compound', 'pepselect-bogo-quantity' ); ?></option></select></td></tr>
							<tr><th scope="row"><?php esc_html_e( 'Discount', 'pepselect-bogo-quantity' ); ?></th><td><select name="rule[discount_type]"><option value="percent" <?php selected( $rule['discount_type'], 'percent' ); ?>><?php esc_html_e( 'Percentage', 'pepselect-bogo-quantity' ); ?></option><option value="fixed_cart" <?php selected( $rule['discount_type'], 'fixed_cart' ); ?>><?php esc_html_e( 'Fixed dollar amount', 'pepselect-bogo-quantity' ); ?></option></select> <input type="number" min="0.01" step="0.01" name="rule[discount_amount]" value="<?php echo esc_attr( $rule['discount_amount'] ); ?>" class="small-text" required></td></tr>
							<tr><th scope="row"><?php esc_html_e( 'Minimum', 'pepselect-bogo-quantity' ); ?></th><td><select name="rule[threshold_type]"><option value="quantity" <?php selected( $rule['threshold_type'], 'quantity' ); ?>><?php esc_html_e( 'Eligible item quantity', 'pepselect-bogo-quantity' ); ?></option><option value="subtotal" <?php selected( $rule['threshold_type'], 'subtotal' ); ?>><?php esc_html_e( 'Eligible item subtotal', 'pepselect-bogo-quantity' ); ?></option></select> <input type="number" min="0.01" step="0.01" name="rule[threshold_amount]" value="<?php echo esc_attr( $rule['threshold_amount'] ); ?>" class="small-text" required><p class="description"><?php esc_html_e( 'Measured before discounts and only across the selected compounds.', 'pepselect-bogo-quantity' ); ?></p></td></tr>
							<tr><th scope="row"><?php esc_html_e( 'Stacking', 'pepselect-bogo-quantity' ); ?></th><td><select name="rule[stackable]"><option value="1" <?php selected( $rule['stackable'], true ); ?>><?php esc_html_e( 'Stack with other discounts', 'pepselect-bogo-quantity' ); ?></option><option value="0" <?php selected( $rule['stackable'], false ); ?>><?php esc_html_e( 'Do not stack', 'pepselect-bogo-quantity' ); ?></option></select><p class="description"><?php esc_html_e( 'Exclusive discounts cannot combine with BOGO, compound, sitewide, or coupon-code discounts.', 'pepselect-bogo-quantity' ); ?></p></td></tr>
							<tr><th scope="row"><label for="pepselect-discount-label"><?php esc_html_e( 'Customer label', 'pepselect-bogo-quantity' ); ?></label></th><td><input id="pepselect-discount-label" type="text" class="regular-text" name="rule[label]" value="<?php echo esc_attr( $rule['label'] ); ?>" maxlength="<?php echo esc_attr( self::LABEL_LIMIT ); ?>" required><p class="description"><?php printf( esc_html__( 'Shown in Cart and Checkout. Maximum %d characters.', 'pepselect-bogo-quantity' ), self::LABEL_LIMIT ); ?></p></td></tr>
						</table>
						<?php submit_button( $edit_id ? __( 'Update discount', 'pepselect-bogo-quantity' ) : __( 'Save discount', 'pepselect-bogo-quantity' ) ); ?>
						<?php if ( $edit_id ) : ?><a class="button" href="<?php echo esc_url( self::page_url() ); ?>"><?php esc_html_e( 'Cancel', 'pepselect-bogo-quantity' ); ?></a><?php endif; ?>
					</form>
				</section>
				<section class="pepselect-discount-panel" aria-labelledby="pepselect-rules-title">
					<h2 id="pepselect-rules-title"><?php esc_html_e( 'Saved discounts', 'pepselect-bogo-quantity' ); ?></h2>
					<?php if ( empty( $state['rules'] ) ) : ?><p class="pepselect-empty-state"><?php esc_html_e( 'No discounts yet. Save one, then activate it here.', 'pepselect-bogo-quantity' ); ?></p><?php endif; ?>
					<div class="pepselect-rule-list">
					<?php foreach ( $state['rules'] as $saved_rule ) : ?>
						<article class="pepselect-rule-row">
							<div class="pepselect-rule-copy"><div class="pepselect-rule-heading"><h3><?php echo esc_html( $saved_rule['label'] ); ?></h3><span class="pepselect-status <?php echo $saved_rule['enabled'] ? 'is-active' : ''; ?>"><?php echo $saved_rule['enabled'] ? esc_html__( 'Active', 'pepselect-bogo-quantity' ) : esc_html__( 'Inactive', 'pepselect-bogo-quantity' ); ?></span></div><p><?php echo esc_html( self::rule_summary( $saved_rule ) ); ?></p></div>
							<div class="pepselect-rule-actions">
								<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"><input type="hidden" name="action" value="pepselect_toggle_compound_discount"><input type="hidden" name="rule_id" value="<?php echo esc_attr( $saved_rule['id'] ); ?>"><?php wp_nonce_field( 'pepselect_toggle_compound_discount_' . $saved_rule['id'] ); ?><button class="pepselect-switch-button <?php echo $saved_rule['enabled'] ? 'is-active' : ''; ?>" type="submit" role="switch" aria-checked="<?php echo $saved_rule['enabled'] ? 'true' : 'false'; ?>"><span aria-hidden="true"></span><span><?php echo $saved_rule['enabled'] ? esc_html__( 'Deactivate', 'pepselect-bogo-quantity' ) : esc_html__( 'Activate', 'pepselect-bogo-quantity' ); ?></span></button></form>
								<a class="button" href="<?php echo esc_url( add_query_arg( 'edit', $saved_rule['id'], self::page_url() ) ); ?>"><?php esc_html_e( 'Edit', 'pepselect-bogo-quantity' ); ?></a>
								<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" onsubmit="return confirm('<?php echo esc_js( __( 'Delete this discount?', 'pepselect-bogo-quantity' ) ); ?>');"><input type="hidden" name="action" value="pepselect_delete_compound_discount"><input type="hidden" name="rule_id" value="<?php echo esc_attr( $saved_rule['id'] ); ?>"><?php wp_nonce_field( 'pepselect_delete_compound_discount_' . $saved_rule['id'] ); ?><button class="button-link-delete" type="submit"><?php esc_html_e( 'Delete', 'pepselect-bogo-quantity' ); ?></button></form>
							</div>
						</article>
					<?php endforeach; ?>
					</div>
				</section>
			</div>
			<p class="pepselect-ops-note"><strong><?php esc_html_e( 'Ops-ready:', 'pepselect-bogo-quantity' ); ?></strong> <?php esc_html_e( 'This screen and the authenticated Ops endpoint use the same versioned discount collection.', 'pepselect-bogo-quantity' ); ?></p>
		</div>
		<?php
	}

	/** Save a new or edited rule. */
	public static function handle_save_rule() {
		self::authorize_admin_action( 'pepselect_save_compound_discount' );
		$state = self::get_state();
		$input = isset( $_POST['rule'] ) && is_array( $_POST['rule'] ) ? wp_unslash( $_POST['rule'] ) : array();
		$id    = sanitize_key( $input['id'] ?? '' );
		$old   = self::find_rule( $state['rules'], $id );

		$input['id']      = $old ? $old['id'] : 'rule-' . substr( str_replace( '-', '', wp_generate_uuid4() ), 0, 12 );
		$input['enabled'] = $old ? $old['enabled'] : false;
		$rule             = self::sanitize_rule( $input );
		$error            = self::validate_rule( $rule );
		if ( is_wp_error( $error ) ) {
			self::redirect_with_notice( 'error', $error->get_error_message(), $old ? $old['id'] : '' );
		}
		if ( self::label_is_duplicate( $rule, $state['rules'] ) ) {
			self::redirect_with_notice( 'error', __( 'That customer label is already used by another discount or coupon.', 'pepselect-bogo-quantity' ), $old ? $old['id'] : '' );
		}

		if ( $old && 0 !== strcasecmp( self::coupon_code_for_rule( $old ), self::coupon_code_for_rule( $rule ) ) ) {
			$state['retired_coupon_codes'][] = self::coupon_code_for_rule( $old );
		}
		$replaced = false;
		foreach ( $state['rules'] as $index => $saved_rule ) {
			if ( $saved_rule['id'] === $rule['id'] ) {
				$state['rules'][ $index ] = $rule;
				$replaced                 = true;
				break;
			}
		}
		if ( ! $replaced ) {
			if ( count( $state['rules'] ) >= self::MAX_RULES ) {
				self::redirect_with_notice( 'error', __( 'The maximum number of discounts has been reached.', 'pepselect-bogo-quantity' ) );
			}
			$state['rules'][] = $rule;
		}
		self::save_state( $state );
		self::redirect_with_notice( 'saved' );
	}

	/** Toggle one rule without changing its configuration. */
	public static function handle_toggle_rule() {
		$id = sanitize_key( wp_unslash( $_POST['rule_id'] ?? '' ) );
		self::authorize_admin_action( 'pepselect_toggle_compound_discount_' . $id );
		$state = self::get_state();
		$found = false;
		foreach ( $state['rules'] as $index => $rule ) {
			if ( $rule['id'] === $id ) {
				$state['rules'][ $index ]['enabled'] = ! $rule['enabled'];
				$found                                = true;
				break;
			}
		}
		if ( ! $found ) {
			self::redirect_with_notice( 'not_found' );
		}
		self::save_state( $state );
		self::redirect_with_notice( 'toggled' );
	}

	/** Delete one rule and remove its coupon from any current cart. */
	public static function handle_delete_rule() {
		$id = sanitize_key( wp_unslash( $_POST['rule_id'] ?? '' ) );
		self::authorize_admin_action( 'pepselect_delete_compound_discount_' . $id );
		$state     = self::get_state();
		$remaining = array();
		$found     = false;
		foreach ( $state['rules'] as $rule ) {
			if ( $rule['id'] === $id ) {
				$state['retired_coupon_codes'][] = self::coupon_code_for_rule( $rule );
				$found                           = true;
				continue;
			}
			$remaining[] = $rule;
		}
		if ( ! $found ) {
			self::redirect_with_notice( 'not_found' );
		}
		$state['rules'] = $remaining;
		self::save_state( $state );
		self::redirect_with_notice( 'deleted' );
	}

	/** Register the Ops-compatible collection contract and legacy route alias. */
	public static function register_rest_routes() {
		foreach ( array( '/compound-discounts', '/compound-discount' ) as $route ) {
			register_rest_route( self::REST_NAMESPACE, $route, array( array( 'methods' => WP_REST_Server::READABLE, 'callback' => array( __CLASS__, 'rest_get_rules' ), 'permission_callback' => array( __CLASS__, 'can_manage_discounts' ) ), array( 'methods' => WP_REST_Server::EDITABLE, 'callback' => array( __CLASS__, 'rest_update_rules' ), 'permission_callback' => array( __CLASS__, 'can_manage_discounts' ) ) ) );
		}
	}

	/** @return bool */
	public static function can_manage_discounts() {
		return current_user_can( 'manage_woocommerce' );
	}

	/** @return WP_REST_Response */
	public static function rest_get_rules() {
		return new WP_REST_Response( self::rest_payload( self::get_state() ), 200 );
	}

	/** @param WP_REST_Request $request Request. @return WP_REST_Response|WP_Error */
	public static function rest_update_rules( $request ) {
		$body = $request->get_json_params();
		if ( ! in_array( absint( $body['schema_version'] ?? 0 ), array( 2, self::SCHEMA_VERSION ), true ) || ! is_array( $body['rules'] ?? null ) ) {
			return new WP_Error( 'pepselect_discount_schema', __( 'schema_version 3 and a rules array are required.', 'pepselect-bogo-quantity' ), array( 'status' => 400 ) );
		}
		$current = self::get_state();
		if ( ! empty( $body['if_revision'] ) && ! hash_equals( self::revision( $current ), sanitize_text_field( $body['if_revision'] ) ) ) {
			return new WP_Error( 'pepselect_discount_conflict', __( 'The discounts changed after Ops last read them.', 'pepselect-bogo-quantity' ), array( 'status' => 409 ) );
		}
		if ( count( $body['rules'] ) > self::MAX_RULES ) {
			return new WP_Error( 'pepselect_discount_limit', __( 'Too many discount rules.', 'pepselect-bogo-quantity' ), array( 'status' => 400 ) );
		}

		$state = array( 'schema_version' => self::SCHEMA_VERSION, 'rules' => array(), 'retired_coupon_codes' => $current['retired_coupon_codes'] );
		$ids   = array();
		$codes = array();
		foreach ( $body['rules'] as $input ) {
			$rule = self::sanitize_rule( $input );
			if ( '' === $rule['id'] ) {
				$rule['id'] = 'rule-' . substr( str_replace( '-', '', wp_generate_uuid4() ), 0, 12 );
			}
			$error = self::validate_rule( $rule );
			$code  = self::coupon_code_for_rule( $rule );
			if ( is_wp_error( $error ) ) {
				$error->add_data( array( 'status' => 400 ) );
				return $error;
			}
			if ( isset( $ids[ $rule['id'] ] ) || isset( $codes[ strtolower( $code ) ] ) ) {
				return new WP_Error( 'pepselect_discount_duplicate', __( 'Rule IDs and customer labels must be unique.', 'pepselect-bogo-quantity' ), array( 'status' => 400 ) );
			}
			if ( self::label_is_duplicate( $rule, $state['rules'] ) ) {
				return new WP_Error( 'pepselect_discount_managed_conflict', __( 'That customer label is already used by another managed discount.', 'pepselect-bogo-quantity' ), array( 'status' => 400 ) );
			}
			if ( function_exists( 'wc_get_coupon_id_by_code' ) && wc_get_coupon_id_by_code( $code ) ) {
				return new WP_Error( 'pepselect_discount_coupon_conflict', __( 'A published WooCommerce coupon already uses that customer label.', 'pepselect-bogo-quantity' ), array( 'status' => 400 ) );
			}
			$ids[ $rule['id'] ]             = true;
			$codes[ strtolower( $code ) ]    = true;
			$state['rules'][]                = $rule;
		}
		foreach ( $current['rules'] as $old_rule ) {
			if ( ! isset( $ids[ $old_rule['id'] ] ) || ! isset( $codes[ strtolower( self::coupon_code_for_rule( $old_rule ) ) ] ) ) {
				$state['retired_coupon_codes'][] = self::coupon_code_for_rule( $old_rule );
			}
		}
		$state = self::save_state( $state );
		return new WP_REST_Response( self::rest_payload( $state ), 200 );
	}

	/** Synchronize every configured virtual coupon independently. */
	public static function sync_automatic_coupons( $cart ) {
		if ( self::$syncing || ! $cart instanceof WC_Cart || ( is_admin() && ! wp_doing_ajax() ) ) {
			return;
		}
		self::$syncing = true;
		$state         = self::get_state();
		foreach ( $state['rules'] as $rule ) {
			$code       = self::coupon_code_for_rule( $rule );
			$qualifies = self::cart_qualifies( $cart, $rule );
			$applied    = $cart->has_discount( $code );
			if ( $qualifies && ! $applied ) {
				self::apply_coupon_silently( $cart, $code );
			} elseif ( ! $qualifies && $applied ) {
				$cart->remove_coupon( $code );
			}
		}
		foreach ( $state['retired_coupon_codes'] as $code ) {
			if ( $cart->has_discount( $code ) ) {
				$cart->remove_coupon( $code );
			}
		}
		self::$syncing = false;
	}

	/** Return every current and retired coupon to the shared stacking coordinator. */
	public static function discount_candidates( $cart ) {
		$state      = self::get_state();
		$candidates = array();
		foreach ( $state['rules'] as $rule ) {
			$candidates[] = array( 'code' => self::coupon_code_for_rule( $rule ), 'qualifies' => self::cart_qualifies( $cart, $rule ), 'stackable' => ! empty( $rule['stackable'] ), 'estimated_amount' => self::estimated_discount_amount( $cart, $rule ) );
		}
		foreach ( $state['retired_coupon_codes'] as $code ) {
			$candidates[] = array( 'code' => $code, 'qualifies' => false, 'stackable' => true, 'estimated_amount' => 0 );
		}
		return $candidates;
	}

	/** Estimate an order-level rule's savings for exclusive-rule selection. */
	public static function estimated_discount_amount( $cart, $rule ) {
		if ( ! self::cart_qualifies( $cart, $rule ) ) {
			return 0.0;
		}
		$subtotal = 0.0;
		foreach ( $cart->get_cart() as $item ) {
			$subtotal += max( 0, (float) ( $item['line_subtotal'] ?? 0 ) );
		}
		return 'percent' === $rule['discount_type'] ? $subtotal * (float) $rule['discount_amount'] / 100 : min( $subtotal, (float) $rule['discount_amount'] );
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
		$products_pass = 'all' === $rule['match_mode'] ? empty( array_diff( $selected, array_unique( $present ) ) ) : ! empty( $present );
		$minimum_pass  = 'subtotal' === $rule['threshold_type'] ? $subtotal >= (float) $rule['threshold_amount'] : $quantity >= absint( $rule['threshold_amount'] );
		return $products_pass && $minimum_pass;
	}

	/** @param false|array $data Coupon data. @param string $code Coupon code. @return false|array */
	public static function provide_virtual_coupon( $data, $code ) {
		if ( false !== $data && null !== $data ) {
			return $data;
		}
		$state     = self::get_state();
		$formatted = wc_format_coupon_code( $code );
		$legacy    = 0 === strcasecmp( self::LEGACY_CODE, $formatted );
		$rule      = self::find_rule_by_code( $state['rules'], $formatted );
		if ( ! $rule && $legacy ) {
			foreach ( $state['rules'] as $candidate ) {
				if ( 0 === strpos( $candidate['id'], 'legacy-' ) ) {
					$rule = $candidate;
					break;
				}
			}
		}
		if ( ! $rule || ! function_exists( 'WC' ) || ! WC()->cart || ! self::cart_qualifies( WC()->cart, $rule ) ) {
			return $data;
		}
		return array( 'code' => $legacy ? self::LEGACY_CODE : self::coupon_code_for_rule( $rule ), 'description' => $rule['label'], 'discount_type' => $rule['discount_type'], 'amount' => $rule['discount_amount'], 'individual_use' => empty( $rule['stackable'] ), 'usage_limit' => 0, 'free_shipping' => false );
	}

	/** @param string $label Existing label. @param WC_Coupon $coupon Coupon. @return string */
	public static function coupon_label( $label, $coupon ) {
		if ( ! $coupon instanceof WC_Coupon ) {
			return $label;
		}
		$rule = self::find_rule_by_code( self::get_state()['rules'], $coupon->get_code() );
		return $rule ? esc_html( $rule['label'] ) : $label;
	}

	/** Remove the classic WooCommerce remove link for managed coupons only. */
	public static function coupon_html( $html, $coupon ) {
		if ( $coupon instanceof WC_Coupon && self::is_managed_coupon( $coupon->get_code() ) ) {
			return preg_replace( '/\s*<a\b[^>]*woocommerce-remove-coupon[^>]*>.*?<\/a>/is', '', $html );
		}
		return $html;
	}

	/** @param string $code Coupon code. @return bool */
	public static function is_managed_coupon( $code ) {
		return null !== self::find_rule_by_code( self::get_state()['rules'], $code );
	}

	/** Customer label doubles as the visible virtual coupon code on every Woo surface. */
	public static function coupon_code_for_rule( $rule ) {
		return wc_format_coupon_code( $rule['label'] ?? '' );
	}

	/** @param array<string,mixed> $state State. @return array<string,mixed> */
	private static function normalize_state( $state ) {
		$normalized = array( 'schema_version' => self::SCHEMA_VERSION, 'rules' => array(), 'retired_coupon_codes' => array() );
		$rules      = isset( $state['rules'] ) && is_array( $state['rules'] ) ? array_slice( $state['rules'], 0, self::MAX_RULES ) : array();
		foreach ( $rules as $input ) {
			$rule = self::sanitize_rule( $input );
			if ( '' !== $rule['id'] && ! is_wp_error( self::validate_rule( $rule ) ) ) {
				$normalized['rules'][] = $rule;
			}
		}
		$current_codes = array_map( array( __CLASS__, 'coupon_code_for_rule' ), $normalized['rules'] );
		$retired       = isset( $state['retired_coupon_codes'] ) && is_array( $state['retired_coupon_codes'] ) ? $state['retired_coupon_codes'] : array();
		foreach ( $retired as $code ) {
			$code = wc_format_coupon_code( $code );
			if ( $code && ! self::code_in_list( $code, $current_codes ) ) {
				$normalized['retired_coupon_codes'][] = $code;
			}
		}
		$normalized['retired_coupon_codes'] = array_values( array_unique( $normalized['retired_coupon_codes'] ) );
		return $normalized;
	}

	/** @param array<string,mixed> $state State. @return array<string,mixed> */
	private static function save_state( $state ) {
		$state = self::normalize_state( $state );
		update_option( self::OPTION, $state, false );
		return $state;
	}

	/** @param array<int,array<string,mixed>> $rules Rules. @param string $id Rule ID. @return array<string,mixed>|null */
	private static function find_rule( $rules, $id ) {
		foreach ( $rules as $rule ) {
			if ( $id && $rule['id'] === $id ) {
				return $rule;
			}
		}
		return null;
	}

	/** @param array<int,array<string,mixed>> $rules Rules. @param string $code Coupon code. @return array<string,mixed>|null */
	private static function find_rule_by_code( $rules, $code ) {
		$code = wc_format_coupon_code( $code );
		foreach ( $rules as $rule ) {
			if ( 0 === strcasecmp( self::coupon_code_for_rule( $rule ), $code ) ) {
				return $rule;
			}
		}
		return null;
	}

	/** @param array<string,mixed> $candidate Rule. @param array<int,array<string,mixed>> $rules Rules. @return bool */
	private static function label_is_duplicate( $candidate, $rules ) {
		$code = self::coupon_code_for_rule( $candidate );
		foreach ( $rules as $rule ) {
			if ( $rule['id'] !== $candidate['id'] && 0 === strcasecmp( self::coupon_code_for_rule( $rule ), $code ) ) {
				return true;
			}
		}
		if ( class_exists( 'PepSelect_BOGO_Rule' ) && 0 === strcasecmp( PepSelect_BOGO_Rule::COUPON_CODE, $code ) ) {
			return true;
		}
		if ( class_exists( 'PepSelect_Sitewide_Discount' ) ) {
			foreach ( PepSelect_Sitewide_Discount::get_state()['rules'] as $rule ) {
				if ( 0 === strcasecmp( PepSelect_Sitewide_Discount::coupon_code_for_rule( $rule ), $code ) ) {
					return true;
				}
			}
		}
		return function_exists( 'wc_get_coupon_id_by_code' ) && (bool) wc_get_coupon_id_by_code( $code );
	}

	/** @param WC_Cart $cart Cart. @param string $code Coupon code. */
	private static function apply_coupon_silently( $cart, $code ) {
		$notices = function_exists( 'wc_get_notices' ) ? wc_get_notices() : array();
		$cart->apply_coupon( $code );
		if ( function_exists( 'wc_set_notices' ) ) {
			wc_set_notices( $notices );
		}
	}

	/** @param string $code Code. @param array<int,string> $codes Codes. @return bool */
	private static function code_in_list( $code, $codes ) {
		foreach ( $codes as $candidate ) {
			if ( 0 === strcasecmp( $code, $candidate ) ) {
				return true;
			}
		}
		return false;
	}

	/** @param array<string,mixed> $rule Rule. @return string */
	private static function rule_summary( $rule ) {
		$discount = 'percent' === $rule['discount_type'] ? $rule['discount_amount'] . '%' : '$' . $rule['discount_amount'];
		$minimum  = 'quantity' === $rule['threshold_type'] ? sprintf( _n( '%s eligible item', '%s eligible items', absint( $rule['threshold_amount'] ), 'pepselect-bogo-quantity' ), $rule['threshold_amount'] ) : '$' . $rule['threshold_amount'] . ' eligible subtotal';
		return sprintf( __( '%1$s off · %2$s · %3$d compounds · %4$s', 'pepselect-bogo-quantity' ), $discount, $minimum, count( $rule['product_ids'] ), $rule['stackable'] ? __( 'stackable', 'pepselect-bogo-quantity' ) : __( 'exclusive', 'pepselect-bogo-quantity' ) );
	}

	/** @return string */
	private static function page_url() {
		return admin_url( 'admin.php?page=' . self::PAGE_SLUG );
	}

	/** @param string $nonce_action Nonce action. */
	private static function authorize_admin_action( $nonce_action ) {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( esc_html__( 'You do not have permission to manage discounts.', 'pepselect-bogo-quantity' ), '', array( 'response' => 403 ) );
		}
		check_admin_referer( $nonce_action );
	}

	/** @param string $notice Notice key. @param string $message Optional message. @param string $edit_id Optional edit ID. */
	private static function redirect_with_notice( $notice, $message = '', $edit_id = '' ) {
		$args = array( 'pepselect_notice' => $notice );
		if ( $message ) {
			$args['pepselect_message'] = $message;
		}
		if ( $edit_id ) {
			$args['edit'] = $edit_id;
		}
		wp_safe_redirect( add_query_arg( $args, self::page_url() ) );
		exit;
	}

	/** Render a saved/error notice from the redirect. */
	private static function render_admin_notice() {
		$notice = sanitize_key( wp_unslash( $_GET['pepselect_notice'] ?? '' ) );
		if ( ! $notice ) {
			return;
		}
		$messages = array( 'saved' => __( 'Discount saved. Activate it when you are ready.', 'pepselect-bogo-quantity' ), 'toggled' => __( 'Discount status updated.', 'pepselect-bogo-quantity' ), 'deleted' => __( 'Discount deleted.', 'pepselect-bogo-quantity' ), 'not_found' => __( 'That discount no longer exists.', 'pepselect-bogo-quantity' ) );
		$message  = 'error' === $notice ? sanitize_text_field( wp_unslash( $_GET['pepselect_message'] ?? '' ) ) : ( $messages[ $notice ] ?? '' );
		if ( $message ) {
			printf( '<div class="notice %1$s is-dismissible"><p>%2$s</p></div>', 'error' === $notice || 'not_found' === $notice ? 'notice-error' : 'notice-success', esc_html( $message ) );
		}
	}

	/** @param array<string,mixed> $state State. @return array<string,mixed> */
	private static function rest_payload( $state ) {
		return array( 'schema_version' => self::SCHEMA_VERSION, 'revision' => self::revision( $state ), 'rules' => $state['rules'], 'contract' => array( 'max_rules' => self::MAX_RULES, 'customer_label_max_length' => self::LABEL_LIMIT, 'customer_label_is_coupon_code' => true, 'match_mode' => array( 'any', 'all' ), 'discount_type' => array( 'percent', 'fixed_cart' ), 'threshold_type' => array( 'quantity', 'subtotal' ), 'stackable' => true ) );
	}

	/** @param array<string,mixed> $state State. @return string */
	private static function revision( $state ) {
		return hash( 'sha256', wp_json_encode( self::normalize_state( $state ) ) );
	}
}

<?php

defined( 'ABSPATH' ) || exit;

/** Manage automatic discounts across the catalog with per-rule product exclusions. */
final class PepSelect_Sitewide_Discount {
	const OPTION         = 'pepselect_sitewide_discount_rules_v1';
	const PAGE_SLUG      = 'pepselect-sitewide-discounts';
	const REST_NAMESPACE = 'pepselect-bogo/v1';
	const SCHEMA_VERSION = 1;
	const LABEL_LIMIT    = 24;
	const MAX_RULES      = 50;

	/** Register settings, Ops, coupon, and storefront pricing hooks. */
	public static function boot() {
		add_action( 'admin_menu', array( __CLASS__, 'register_settings_page' ), 10 );
		add_action( 'admin_post_pepselect_save_sitewide_discount', array( __CLASS__, 'handle_save_rule' ) );
		add_action( 'admin_post_pepselect_toggle_sitewide_discount', array( __CLASS__, 'handle_toggle_rule' ) );
		add_action( 'admin_post_pepselect_delete_sitewide_discount', array( __CLASS__, 'handle_delete_rule' ) );
		add_action( 'rest_api_init', array( __CLASS__, 'register_rest_routes' ) );
		add_filter( 'woocommerce_get_shop_coupon_data', array( __CLASS__, 'provide_virtual_coupon' ), 10, 3 );
		add_filter( 'woocommerce_cart_totals_coupon_label', array( __CLASS__, 'coupon_label' ), 10, 2 );
		add_filter( 'woocommerce_cart_totals_coupon_html', array( __CLASS__, 'coupon_html' ), 30, 2 );
		add_filter( 'woocommerce_get_price_html', array( __CLASS__, 'price_html' ), 30, 2 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_frontend_styles' ) );
	}

	/** @return array<string,mixed> */
	public static function defaults() {
		return array(
			'id'               => '',
			'enabled'          => false,
			'discount_type'    => 'percent',
			'discount_amount'  => '20',
			'threshold_type'   => 'none',
			'threshold_amount' => '0',
			'audience'         => 'everyone',
			'customer_ids'     => array(),
			'excluded_product_ids' => array(),
			'stackable'        => true,
			'label'            => '',
		);
	}

	/** @return array<string,mixed> */
	public static function get_state() {
		return self::normalize_state( get_option( self::OPTION, array() ) );
	}

	/** @param mixed $input Raw rule. @return array<string,mixed> */
	public static function sanitize_rule( $input ) {
		$input        = is_array( $input ) ? $input : array();
		$discount     = in_array( $input['discount_type'] ?? '', array( 'percent', 'fixed_cart' ), true ) ? $input['discount_type'] : 'percent';
		$threshold    = in_array( $input['threshold_type'] ?? '', array( 'none', 'quantity', 'subtotal' ), true ) ? $input['threshold_type'] : 'none';
		$audience     = in_array( $input['audience'] ?? '', array( 'everyone', 'logged_in', 'subscribers', 'purchasers', 'vip', 'specific' ), true ) ? $input['audience'] : 'everyone';
		$amount       = max( 0, (float) wc_format_decimal( $input['discount_amount'] ?? 0 ) );
		$minimum      = max( 0, (float) wc_format_decimal( $input['threshold_amount'] ?? 0 ) );
		$customer_ids = isset( $input['customer_ids'] ) && is_array( $input['customer_ids'] ) ? array_values( array_unique( array_filter( array_map( 'absint', $input['customer_ids'] ) ) ) ) : array();
		$excluded_product_ids = isset( $input['excluded_product_ids'] ) && is_array( $input['excluded_product_ids'] ) ? array_values( array_unique( array_filter( array_map( 'absint', $input['excluded_product_ids'] ) ) ) ) : array();
		$label        = sanitize_text_field( $input['label'] ?? '' );
		$label        = function_exists( 'mb_substr' ) ? mb_substr( $label, 0, self::LABEL_LIMIT ) : substr( $label, 0, self::LABEL_LIMIT );

		if ( 'percent' === $discount ) {
			$amount = min( 100, $amount );
		}
		if ( 'quantity' === $threshold ) {
			$minimum = absint( $minimum );
		}
		if ( 'none' === $threshold ) {
			$minimum = 0;
		}

		return array(
			'id'               => sanitize_key( $input['id'] ?? '' ),
			'enabled'          => ! empty( $input['enabled'] ),
			'discount_type'    => $discount,
			'discount_amount'  => wc_format_decimal( $amount ),
			'threshold_type'   => $threshold,
			'threshold_amount' => wc_format_decimal( $minimum ),
			'audience'         => $audience,
			'customer_ids'     => $customer_ids,
			'excluded_product_ids' => $excluded_product_ids,
			'stackable'        => ! isset( $input['stackable'] ) || ! empty( $input['stackable'] ),
			'label'            => $label,
		);
	}

	/** @param array<string,mixed> $rule Rule. @return true|WP_Error */
	public static function validate_rule( $rule ) {
		if ( '' === trim( $rule['label'] ?? '' ) ) {
			return new WP_Error( 'pepselect_sitewide_label_required', __( 'Enter a customer label.', 'pepselect-bogo-quantity' ) );
		}
		if ( (float) ( $rule['discount_amount'] ?? 0 ) <= 0 ) {
			return new WP_Error( 'pepselect_sitewide_amount_required', __( 'Enter a discount greater than zero.', 'pepselect-bogo-quantity' ) );
		}
		if ( 'none' !== ( $rule['threshold_type'] ?? 'none' ) && (float) ( $rule['threshold_amount'] ?? 0 ) <= 0 ) {
			return new WP_Error( 'pepselect_sitewide_threshold_required', __( 'Enter a minimum greater than zero.', 'pepselect-bogo-quantity' ) );
		}
		if ( 'specific' === ( $rule['audience'] ?? '' ) && empty( $rule['customer_ids'] ) ) {
			return new WP_Error( 'pepselect_sitewide_customers_required', __( 'Select at least one customer for this audience.', 'pepselect-bogo-quantity' ) );
		}
		return true;
	}

	/** Register below the shared Cart Discounts parent. */
	public static function register_settings_page() {
		add_submenu_page( PepSelect_Discount_Admin::PAGE_SLUG, __( 'Sitewide Discounts', 'pepselect-bogo-quantity' ), __( 'Sitewide Discounts', 'pepselect-bogo-quantity' ), 'manage_woocommerce', self::PAGE_SLUG, array( __CLASS__, 'render_settings_page' ) );
	}

	/** Render the sitewide editor and saved rules. */
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
			<h1><?php esc_html_e( 'Sitewide Discounts', 'pepselect-bogo-quantity' ); ?></h1>
			<p><?php esc_html_e( 'Create automatic discounts across every catalog item. Shipping and taxes are excluded.', 'pepselect-bogo-quantity' ); ?></p>
			<?php self::render_admin_notice(); ?>
			<div class="pepselect-discount-layout">
				<section class="pepselect-discount-panel" aria-labelledby="pepselect-sitewide-editor-title">
					<h2 id="pepselect-sitewide-editor-title"><?php echo $edit_id ? esc_html__( 'Edit discount', 'pepselect-bogo-quantity' ) : esc_html__( 'Add discount', 'pepselect-bogo-quantity' ); ?></h2>
					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
						<input type="hidden" name="action" value="pepselect_save_sitewide_discount">
						<input type="hidden" name="rule[id]" value="<?php echo esc_attr( $rule['id'] ); ?>">
						<?php wp_nonce_field( 'pepselect_save_sitewide_discount' ); ?>
						<table class="form-table" role="presentation">
							<tr><th scope="row"><?php esc_html_e( 'Discount', 'pepselect-bogo-quantity' ); ?></th><td><select name="rule[discount_type]"><option value="percent" <?php selected( $rule['discount_type'], 'percent' ); ?>><?php esc_html_e( 'Percentage', 'pepselect-bogo-quantity' ); ?></option><option value="fixed_cart" <?php selected( $rule['discount_type'], 'fixed_cart' ); ?>><?php esc_html_e( 'Fixed dollar amount', 'pepselect-bogo-quantity' ); ?></option></select> <input type="number" min="0.01" step="0.01" name="rule[discount_amount]" value="<?php echo esc_attr( $rule['discount_amount'] ); ?>" class="small-text" required></td></tr>
							<tr><th scope="row"><label for="pepselect-sitewide-exclusions"><?php esc_html_e( 'Excluded products', 'pepselect-bogo-quantity' ); ?></label></th><td><select id="pepselect-sitewide-exclusions" class="wc-product-search" multiple="multiple" name="rule[excluded_product_ids][]" data-placeholder="<?php esc_attr_e( 'Search the catalog…', 'pepselect-bogo-quantity' ); ?>" data-action="woocommerce_json_search_products_and_variations"><?php foreach ( $rule['excluded_product_ids'] as $product_id ) : $product = wc_get_product( $product_id ); if ( $product ) : ?><option value="<?php echo esc_attr( $product_id ); ?>" selected><?php echo wp_kses_post( $product->get_formatted_name() ); ?></option><?php endif; endforeach; ?></select><p class="description"><?php esc_html_e( 'These products keep their regular price and do not count toward this discount or its minimum.', 'pepselect-bogo-quantity' ); ?></p></td></tr>
							<tr><th scope="row"><?php esc_html_e( 'Minimum order', 'pepselect-bogo-quantity' ); ?></th><td><select name="rule[threshold_type]"><option value="none" <?php selected( $rule['threshold_type'], 'none' ); ?>><?php esc_html_e( 'No minimum', 'pepselect-bogo-quantity' ); ?></option><option value="subtotal" <?php selected( $rule['threshold_type'], 'subtotal' ); ?>><?php esc_html_e( 'Order item subtotal', 'pepselect-bogo-quantity' ); ?></option><option value="quantity" <?php selected( $rule['threshold_type'], 'quantity' ); ?>><?php esc_html_e( 'Order item quantity', 'pepselect-bogo-quantity' ); ?></option></select> <input type="number" min="0" step="0.01" name="rule[threshold_amount]" value="<?php echo esc_attr( $rule['threshold_amount'] ); ?>" class="small-text"><p class="description"><?php esc_html_e( 'Measured before discounts. Leave at zero when No minimum is selected.', 'pepselect-bogo-quantity' ); ?></p></td></tr>
							<tr><th scope="row"><label for="pepselect-sitewide-audience"><?php esc_html_e( 'Audience', 'pepselect-bogo-quantity' ); ?></label></th><td><select id="pepselect-sitewide-audience" name="rule[audience]"><option value="everyone" <?php selected( $rule['audience'], 'everyone' ); ?>><?php esc_html_e( 'Everyone', 'pepselect-bogo-quantity' ); ?></option><option value="logged_in" <?php selected( $rule['audience'], 'logged_in' ); ?>><?php esc_html_e( 'All logged-in customers', 'pepselect-bogo-quantity' ); ?></option><option value="subscribers" <?php selected( $rule['audience'], 'subscribers' ); ?>><?php esc_html_e( 'Active subscribers', 'pepselect-bogo-quantity' ); ?></option><option value="purchasers" <?php selected( $rule['audience'], 'purchasers' ); ?>><?php esc_html_e( 'Customers who purchased', 'pepselect-bogo-quantity' ); ?></option><option value="vip" <?php selected( $rule['audience'], 'vip' ); ?>><?php esc_html_e( 'VIP customers', 'pepselect-bogo-quantity' ); ?></option><option value="specific" <?php selected( $rule['audience'], 'specific' ); ?>><?php esc_html_e( 'Specific customers', 'pepselect-bogo-quantity' ); ?></option></select><p class="description"><?php esc_html_e( 'Customer-only audiences require the customer to log in.', 'pepselect-bogo-quantity' ); ?></p></td></tr>
							<tr><th scope="row"><label for="pepselect-sitewide-customers"><?php esc_html_e( 'Customer list', 'pepselect-bogo-quantity' ); ?></label></th><td><select id="pepselect-sitewide-customers" class="wc-customer-search" multiple="multiple" name="rule[customer_ids][]" data-placeholder="<?php esc_attr_e( 'Search names or email addresses…', 'pepselect-bogo-quantity' ); ?>" data-action="woocommerce_json_search_customers"><?php foreach ( $rule['customer_ids'] as $customer_id ) : $customer = get_userdata( $customer_id ); if ( $customer ) : ?><option value="<?php echo esc_attr( $customer_id ); ?>" selected><?php echo esc_html( $customer->display_name . ' (' . $customer->user_email . ')' ); ?></option><?php endif; endforeach; ?></select><p class="description"><?php esc_html_e( 'Required for Specific customers. For VIP customers, this list is combined with customers tagged as VIP by Ops.', 'pepselect-bogo-quantity' ); ?></p></td></tr>
							<tr><th scope="row"><?php esc_html_e( 'Stacking', 'pepselect-bogo-quantity' ); ?></th><td><select name="rule[stackable]"><option value="1" <?php selected( $rule['stackable'], true ); ?>><?php esc_html_e( 'Stack with other discounts', 'pepselect-bogo-quantity' ); ?></option><option value="0" <?php selected( $rule['stackable'], false ); ?>><?php esc_html_e( 'Do not stack', 'pepselect-bogo-quantity' ); ?></option></select><p class="description"><?php esc_html_e( 'If multiple exclusive discounts qualify, the customer receives the one with the greatest estimated savings.', 'pepselect-bogo-quantity' ); ?></p></td></tr>
							<tr><th scope="row"><label for="pepselect-sitewide-label"><?php esc_html_e( 'Customer label', 'pepselect-bogo-quantity' ); ?></label></th><td><input id="pepselect-sitewide-label" type="text" class="regular-text" name="rule[label]" value="<?php echo esc_attr( $rule['label'] ); ?>" maxlength="<?php echo esc_attr( self::LABEL_LIMIT ); ?>" required><p class="description"><?php printf( esc_html__( 'Shown in Cart and Checkout. Maximum %d characters.', 'pepselect-bogo-quantity' ), self::LABEL_LIMIT ); ?></p></td></tr>
						</table>
						<?php submit_button( $edit_id ? __( 'Update discount', 'pepselect-bogo-quantity' ) : __( 'Save discount', 'pepselect-bogo-quantity' ) ); ?>
						<?php if ( $edit_id ) : ?><a class="button" href="<?php echo esc_url( self::page_url() ); ?>"><?php esc_html_e( 'Cancel', 'pepselect-bogo-quantity' ); ?></a><?php endif; ?>
					</form>
				</section>
				<section class="pepselect-discount-panel" aria-labelledby="pepselect-sitewide-rules-title">
					<h2 id="pepselect-sitewide-rules-title"><?php esc_html_e( 'Saved discounts', 'pepselect-bogo-quantity' ); ?></h2>
					<?php if ( empty( $state['rules'] ) ) : ?><p class="pepselect-empty-state"><?php esc_html_e( 'No sitewide discounts yet. Save one, then activate it here.', 'pepselect-bogo-quantity' ); ?></p><?php endif; ?>
					<div class="pepselect-rule-list">
					<?php foreach ( $state['rules'] as $saved_rule ) : ?>
						<article class="pepselect-rule-row">
							<div class="pepselect-rule-copy"><div class="pepselect-rule-heading"><h3><?php echo esc_html( $saved_rule['label'] ); ?></h3><span class="pepselect-status <?php echo $saved_rule['enabled'] ? 'is-active' : ''; ?>"><?php echo $saved_rule['enabled'] ? esc_html__( 'Active', 'pepselect-bogo-quantity' ) : esc_html__( 'Inactive', 'pepselect-bogo-quantity' ); ?></span></div><p><?php echo esc_html( self::rule_summary( $saved_rule ) ); ?></p></div>
							<div class="pepselect-rule-actions"><form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"><input type="hidden" name="action" value="pepselect_toggle_sitewide_discount"><input type="hidden" name="rule_id" value="<?php echo esc_attr( $saved_rule['id'] ); ?>"><?php wp_nonce_field( 'pepselect_toggle_sitewide_discount_' . $saved_rule['id'] ); ?><button class="pepselect-switch-button <?php echo $saved_rule['enabled'] ? 'is-active' : ''; ?>" type="submit" role="switch" aria-checked="<?php echo $saved_rule['enabled'] ? 'true' : 'false'; ?>"><span aria-hidden="true"></span><span><?php echo $saved_rule['enabled'] ? esc_html__( 'Deactivate', 'pepselect-bogo-quantity' ) : esc_html__( 'Activate', 'pepselect-bogo-quantity' ); ?></span></button></form><a class="button" href="<?php echo esc_url( add_query_arg( 'edit', $saved_rule['id'], self::page_url() ) ); ?>"><?php esc_html_e( 'Edit', 'pepselect-bogo-quantity' ); ?></a><form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" onsubmit="return confirm('<?php echo esc_js( __( 'Delete this discount?', 'pepselect-bogo-quantity' ) ); ?>');"><input type="hidden" name="action" value="pepselect_delete_sitewide_discount"><input type="hidden" name="rule_id" value="<?php echo esc_attr( $saved_rule['id'] ); ?>"><?php wp_nonce_field( 'pepselect_delete_sitewide_discount_' . $saved_rule['id'] ); ?><button class="button-link-delete" type="submit"><?php esc_html_e( 'Delete', 'pepselect-bogo-quantity' ); ?></button></form></div>
						</article>
					<?php endforeach; ?>
					</div>
				</section>
			</div>
			<p class="pepselect-ops-note"><strong><?php esc_html_e( 'Ops-ready:', 'pepselect-bogo-quantity' ); ?></strong> <?php esc_html_e( 'Ops can manage the same rules, audiences, and stacking controls through the authenticated endpoint.', 'pepselect-bogo-quantity' ); ?></p>
		</div>
		<?php
	}

	/** Save one rule. */
	public static function handle_save_rule() {
		self::authorize_admin_action( 'pepselect_save_sitewide_discount' );
		$state = self::get_state();
		$input = isset( $_POST['rule'] ) && is_array( $_POST['rule'] ) ? wp_unslash( $_POST['rule'] ) : array();
		$id    = sanitize_key( $input['id'] ?? '' );
		$old   = self::find_rule( $state['rules'], $id );
		$input['id']      = $old ? $old['id'] : 'site-' . substr( str_replace( '-', '', wp_generate_uuid4() ), 0, 12 );
		$input['enabled'] = $old ? $old['enabled'] : false;
		$rule             = self::sanitize_rule( $input );
		$error            = self::validate_rule( $rule );
		if ( is_wp_error( $error ) ) {
			self::redirect_with_notice( 'error', $error->get_error_message(), $old ? $old['id'] : '' );
		}
		if ( self::label_is_duplicate( $rule, $state['rules'] ) ) {
			self::redirect_with_notice( 'error', __( 'That customer label is already used by another sitewide discount or coupon.', 'pepselect-bogo-quantity' ), $old ? $old['id'] : '' );
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
				self::redirect_with_notice( 'error', __( 'The maximum number of sitewide discounts has been reached.', 'pepselect-bogo-quantity' ) );
			}
			$state['rules'][] = $rule;
		}
		self::save_state( $state );
		self::redirect_with_notice( 'saved' );
	}

	/** Toggle one rule. */
	public static function handle_toggle_rule() {
		$id = sanitize_key( wp_unslash( $_POST['rule_id'] ?? '' ) );
		self::authorize_admin_action( 'pepselect_toggle_sitewide_discount_' . $id );
		$state = self::get_state();
		foreach ( $state['rules'] as $index => $rule ) {
			if ( $rule['id'] === $id ) {
				$state['rules'][ $index ]['enabled'] = ! $rule['enabled'];
				self::save_state( $state );
				self::redirect_with_notice( 'toggled' );
			}
		}
		self::redirect_with_notice( 'not_found' );
	}

	/** Delete one rule. */
	public static function handle_delete_rule() {
		$id = sanitize_key( wp_unslash( $_POST['rule_id'] ?? '' ) );
		self::authorize_admin_action( 'pepselect_delete_sitewide_discount_' . $id );
		$state = self::get_state();
		$remaining = array();
		$found = false;
		foreach ( $state['rules'] as $rule ) {
			if ( $rule['id'] === $id ) {
				$state['retired_coupon_codes'][] = self::coupon_code_for_rule( $rule );
				$found = true;
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

	/** Register the Ops collection. */
	public static function register_rest_routes() {
		register_rest_route( self::REST_NAMESPACE, '/sitewide-discounts', array( array( 'methods' => WP_REST_Server::READABLE, 'callback' => array( __CLASS__, 'rest_get_rules' ), 'permission_callback' => array( __CLASS__, 'can_manage' ) ), array( 'methods' => WP_REST_Server::EDITABLE, 'callback' => array( __CLASS__, 'rest_update_rules' ), 'permission_callback' => array( __CLASS__, 'can_manage' ) ) ) );
	}

	public static function can_manage() {
		return current_user_can( 'manage_woocommerce' );
	}

	public static function rest_get_rules() {
		return new WP_REST_Response( self::rest_payload( self::get_state() ), 200 );
	}

	/** @param WP_REST_Request $request Request. */
	public static function rest_update_rules( $request ) {
		$body = $request->get_json_params();
		if ( self::SCHEMA_VERSION !== absint( $body['schema_version'] ?? 0 ) || ! is_array( $body['rules'] ?? null ) ) {
			return new WP_Error( 'pepselect_sitewide_schema', __( 'schema_version 1 and a rules array are required.', 'pepselect-bogo-quantity' ), array( 'status' => 400 ) );
		}
		$current = self::get_state();
		if ( ! empty( $body['if_revision'] ) && ! hash_equals( self::revision( $current ), sanitize_text_field( $body['if_revision'] ) ) ) {
			return new WP_Error( 'pepselect_sitewide_conflict', __( 'The sitewide discounts changed after Ops last read them.', 'pepselect-bogo-quantity' ), array( 'status' => 409 ) );
		}
		$state = array( 'schema_version' => self::SCHEMA_VERSION, 'rules' => array(), 'retired_coupon_codes' => $current['retired_coupon_codes'] );
		$codes = array();
		$ids   = array();
		foreach ( array_slice( $body['rules'], 0, self::MAX_RULES ) as $input ) {
			$rule = self::sanitize_rule( $input );
			if ( '' === $rule['id'] ) {
				$rule['id'] = 'site-' . substr( str_replace( '-', '', wp_generate_uuid4() ), 0, 12 );
			}
			$error = self::validate_rule( $rule );
			if ( is_wp_error( $error ) ) {
				$error->add_data( array( 'status' => 400 ) );
				return $error;
			}
			if ( isset( $ids[ $rule['id'] ] ) || self::label_is_duplicate( $rule, $state['rules'] ) ) {
				return new WP_Error( 'pepselect_sitewide_duplicate', __( 'Customer labels must be unique.', 'pepselect-bogo-quantity' ), array( 'status' => 400 ) );
			}
			$ids[ $rule['id'] ] = true;
			$codes[] = self::coupon_code_for_rule( $rule );
			$state['rules'][] = $rule;
		}
		foreach ( $current['rules'] as $old_rule ) {
			if ( ! self::code_in_list( self::coupon_code_for_rule( $old_rule ), $codes ) ) {
				$state['retired_coupon_codes'][] = self::coupon_code_for_rule( $old_rule );
			}
		}
		$state = self::save_state( $state );
		return new WP_REST_Response( self::rest_payload( $state ), 200 );
	}

	/** Return all candidates, including inactive rules so stale coupons are removed. */
	public static function discount_candidates( $cart ) {
		$candidates = array();
		foreach ( self::get_state()['rules'] as $rule ) {
			$candidates[] = array( 'code' => self::coupon_code_for_rule( $rule ), 'qualifies' => self::cart_qualifies( $cart, $rule ), 'stackable' => ! empty( $rule['stackable'] ), 'estimated_amount' => self::estimated_discount_amount( $cart, $rule ) );
		}
		foreach ( self::get_state()['retired_coupon_codes'] as $code ) {
			$candidates[] = array( 'code' => $code, 'qualifies' => false, 'stackable' => true, 'estimated_amount' => 0 );
		}
		return $candidates;
	}

	/** Determine whether cart contents, minimum, and customer segment qualify. */
	public static function cart_qualifies( $cart, $rule ) {
		if ( empty( $rule['enabled'] ) || is_wp_error( self::validate_rule( $rule ) ) || ! self::audience_qualifies( $rule ) ) {
			return false;
		}
		$metrics = self::eligible_metrics( $cart, $rule );
		if ( $metrics['quantity'] < 1 ) {
			return false;
		}
		if ( 'subtotal' === $rule['threshold_type'] ) {
			return $metrics['subtotal'] >= (float) $rule['threshold_amount'];
		}
		if ( 'quantity' === $rule['threshold_type'] ) {
			return $metrics['quantity'] >= absint( $rule['threshold_amount'] );
		}
		return true;
	}

	/** Estimate savings for deterministic exclusive-rule selection. */
	public static function estimated_discount_amount( $cart, $rule ) {
		if ( ! self::cart_qualifies( $cart, $rule ) ) {
			return 0.0;
		}
		$subtotal = self::eligible_metrics( $cart, $rule )['subtotal'];
		return 'percent' === $rule['discount_type'] ? $subtotal * (float) $rule['discount_amount'] / 100 : min( $subtotal, (float) $rule['discount_amount'] );
	}

	/** @param false|array $data Coupon data. */
	public static function provide_virtual_coupon( $data, $code ) {
		if ( false !== $data && null !== $data ) {
			return $data;
		}
		$rule = self::find_rule_by_code( self::get_state()['rules'], $code );
		if ( ! $rule || ! function_exists( 'WC' ) || ! WC()->cart || ! self::cart_qualifies( WC()->cart, $rule ) ) {
			return $data;
		}
		$metrics = self::eligible_metrics( WC()->cart, $rule );
		return array( 'code' => self::coupon_code_for_rule( $rule ), 'description' => $rule['label'], 'discount_type' => $rule['discount_type'], 'amount' => $rule['discount_amount'], 'product_ids' => $metrics['product_ids'], 'individual_use' => empty( $rule['stackable'] ), 'usage_limit' => 0, 'free_shipping' => false );
	}

	public static function coupon_label( $label, $coupon ) {
		if ( ! $coupon instanceof WC_Coupon ) {
			return $label;
		}
		$rule = self::find_rule_by_code( self::get_state()['rules'], $coupon->get_code() );
		return $rule ? esc_html( $rule['label'] ) : $label;
	}

	public static function coupon_html( $html, $coupon ) {
		return $coupon instanceof WC_Coupon && self::find_rule_by_code( self::get_state()['rules'], $coupon->get_code() ) ? preg_replace( '/\s*<a\b[^>]*woocommerce-remove-coupon[^>]*>.*?<\/a>/is', '', $html ) : $html;
	}

	/**
	 * Present a no-minimum percentage promotion as a real sale price anywhere
	 * WooCommerce renders a catalog price. The cart still owns the monetary
	 * discount through the managed coupon, so tax, order, and reporting totals
	 * retain WooCommerce's native accounting path.
	 *
	 * @param string     $html    Existing price HTML.
	 * @param WC_Product $product Product being rendered.
	 * @return string
	 */
	public static function price_html( $html, $product ) {
		if ( ! $product instanceof WC_Product || ( function_exists( 'is_admin' ) && is_admin() && ( ! function_exists( 'wp_doing_ajax' ) || ! wp_doing_ajax() ) ) ) {
			return $html;
		}

		$rule = self::display_rule_for_product( $product );
		$price = (float) $product->get_price();
		if ( ! $rule || $price <= 0 || ! function_exists( 'wc_price' ) ) {
			return $html;
		}

		$discounted = max( 0, $price * ( 1 - (float) $rule['discount_amount'] / 100 ) );
		if ( function_exists( 'wc_get_price_to_display' ) ) {
			$display_price      = wc_get_price_to_display( $product, array( 'price' => $price ) );
			$display_discounted = wc_get_price_to_display( $product, array( 'price' => $discounted ) );
		} else {
			$display_price      = $price;
			$display_discounted = $discounted;
		}

		$percent = rtrim( rtrim( number_format( (float) $rule['discount_amount'], 2, '.', '' ), '0' ), '.' );
		$label   = sprintf( __( '%s%% off', 'pepselect-bogo-quantity' ), $percent );

		return sprintf(
			'<span class="pepselect-sitewide-price" data-pepselect-sitewide-discount="%1$s"><del aria-hidden="true">%2$s</del><span class="pepselect-sitewide-price__label">%3$s</span><ins>%4$s</ins><span class="screen-reader-text">%5$s</span></span>',
			esc_attr( $rule['id'] ),
			wc_price( $display_price ),
			esc_html( $label ),
			wc_price( $display_discounted ),
			esc_html( sprintf( __( 'Regular price %1$s. Discounted price %2$s. %3$s.', 'pepselect-bogo-quantity' ), wp_strip_all_tags( wc_price( $display_price ) ), wp_strip_all_tags( wc_price( $display_discounted ) ), $label ) )
		);
	}

	/** Load the approved sale-price treatment only when a visible rule exists. */
	public static function enqueue_frontend_styles() {
		if ( function_exists( 'is_admin' ) && is_admin() && ( ! function_exists( 'wp_doing_ajax' ) || ! wp_doing_ajax() ) ) {
			return;
		}
		foreach ( self::get_state()['rules'] as $rule ) {
			if ( ! empty( $rule['enabled'] ) && 'percent' === $rule['discount_type'] && 'none' === $rule['threshold_type'] && self::audience_qualifies( $rule ) ) {
				wp_enqueue_style( 'pepselect-sitewide-discount', plugin_dir_url( PEPSELECT_BOGO_FILE ) . 'assets/sitewide-discount-frontend.css', array(), PEPSELECT_BOGO_VERSION );
				return;
			}
		}
	}

	/** @return array<string,mixed>|null */
	private static function display_rule_for_product( $product ) {
		$rules = array_values(
			array_filter(
				self::get_state()['rules'],
				static function ( $rule ) use ( $product ) {
					return ! empty( $rule['enabled'] )
						&& 'percent' === $rule['discount_type']
						&& 'none' === $rule['threshold_type']
						&& self::audience_qualifies( $rule )
						&& self::is_product_eligible( $product->get_id(), $rule, $product->get_parent_id() );
				}
			)
		);
		if ( ! $rules ) {
			return null;
		}
		usort( $rules, static function ( $left, $right ) { return (float) $right['discount_amount'] <=> (float) $left['discount_amount']; } );
		return $rules[0];
	}

	public static function coupon_code_for_rule( $rule ) {
		return wc_format_coupon_code( $rule['label'] ?? '' );
	}

	/** @return array{quantity:int,subtotal:float,product_ids:int[]} */
	private static function eligible_metrics( $cart, $rule ) {
		$quantity = 0;
		$subtotal = 0.0;
		$product_ids = array();
		foreach ( $cart->get_cart() as $item ) {
			$product_id = absint( ! empty( $item['variation_id'] ) ? $item['variation_id'] : ( $item['product_id'] ?? 0 ) );
			$parent_id = absint( $item['product_id'] ?? 0 );
			if ( ! self::is_product_eligible( $product_id, $rule, $parent_id ) ) {
				continue;
			}
			$quantity += max( 0, absint( $item['quantity'] ?? 0 ) );
			$subtotal += max( 0, (float) ( $item['line_subtotal'] ?? 0 ) );
			$product_ids[] = $product_id;
		}
		return array( 'quantity' => $quantity, 'subtotal' => $subtotal, 'product_ids' => array_values( array_unique( $product_ids ) ) );
	}

	/** Every catalog product is eligible unless this rule or an Ops integration excludes it. */
	public static function is_product_eligible( $product_id, $rule = array(), $parent_id = 0 ) {
		$product_id = absint( $product_id );
		$parent_id  = absint( $parent_id );
		$excluded   = array_map( 'absint', $rule['excluded_product_ids'] ?? array() );
		$eligible   = $product_id > 0 && ! in_array( $product_id, $excluded, true ) && ( ! $parent_id || ! in_array( $parent_id, $excluded, true ) );
		return (bool) apply_filters( 'pepselect_sitewide_discount_product_eligible', $eligible, $product_id, $rule, $parent_id );
	}

	/** Resolve dynamic or explicit customer audiences. */
	private static function audience_qualifies( $rule ) {
		if ( 'everyone' === $rule['audience'] ) {
			return true;
		}
		$user_id = function_exists( 'get_current_user_id' ) ? absint( get_current_user_id() ) : 0;
		if ( ! $user_id ) {
			return false;
		}
		if ( 'logged_in' === $rule['audience'] ) {
			return true;
		}
		if ( 'specific' === $rule['audience'] ) {
			return in_array( $user_id, array_map( 'absint', $rule['customer_ids'] ), true );
		}
		if ( 'purchasers' === $rule['audience'] ) {
			return function_exists( 'wc_get_customer_order_count' ) && wc_get_customer_order_count( $user_id ) > 0;
		}
		$user = function_exists( 'get_userdata' ) ? get_userdata( $user_id ) : null;
		if ( 'subscribers' === $rule['audience'] ) {
			$subscribed = $user && in_array( 'subscriber', (array) $user->roles, true );
			if ( $user && function_exists( 'FluentCrmApi' ) ) {
				try {
					$contact_api = FluentCrmApi( 'contacts' );
					$contact     = is_object( $contact_api ) && method_exists( $contact_api, 'getContact' ) ? $contact_api->getContact( $user->user_email ) : null;
					$subscribed  = $subscribed || ( $contact && 'subscribed' === strtolower( (string) ( $contact->status ?? '' ) ) );
				} catch ( Throwable $error ) {
					// Keep cart calculation available if the CRM integration is temporarily unavailable.
				}
			}
			return (bool) apply_filters( 'pepselect_discount_customer_is_subscriber', $subscribed, $user_id, $user );
		}
		$vip = in_array( $user_id, array_map( 'absint', $rule['customer_ids'] ), true ) || ( function_exists( 'get_user_meta' ) && (bool) get_user_meta( $user_id, 'pepselect_discount_vip', true ) );
		return (bool) apply_filters( 'pepselect_discount_customer_is_vip', $vip, $user_id, $user );
	}

	private static function normalize_state( $state ) {
		$normalized = array( 'schema_version' => self::SCHEMA_VERSION, 'rules' => array(), 'retired_coupon_codes' => array() );
		$rules = isset( $state['rules'] ) && is_array( $state['rules'] ) ? array_slice( $state['rules'], 0, self::MAX_RULES ) : array();
		foreach ( $rules as $input ) {
			$rule = self::sanitize_rule( $input );
			if ( '' !== $rule['id'] && ! is_wp_error( self::validate_rule( $rule ) ) ) {
				$normalized['rules'][] = $rule;
			}
		}
		$retired = isset( $state['retired_coupon_codes'] ) && is_array( $state['retired_coupon_codes'] ) ? $state['retired_coupon_codes'] : array();
		$normalized['retired_coupon_codes'] = array_values( array_unique( array_filter( array_map( 'wc_format_coupon_code', $retired ) ) ) );
		return $normalized;
	}

	private static function save_state( $state ) {
		$state = self::normalize_state( $state );
		update_option( self::OPTION, $state, false );
		return $state;
	}

	private static function find_rule( $rules, $id ) {
		foreach ( $rules as $rule ) {
			if ( $id && $rule['id'] === $id ) {
				return $rule;
			}
		}
		return null;
	}

	private static function find_rule_by_code( $rules, $code ) {
		foreach ( $rules as $rule ) {
			if ( 0 === strcasecmp( self::coupon_code_for_rule( $rule ), wc_format_coupon_code( $code ) ) ) {
				return $rule;
			}
		}
		return null;
	}

	private static function label_is_duplicate( $candidate, $rules ) {
		foreach ( $rules as $rule ) {
			if ( $rule['id'] !== $candidate['id'] && 0 === strcasecmp( self::coupon_code_for_rule( $rule ), self::coupon_code_for_rule( $candidate ) ) ) {
				return true;
			}
		}
		$code = self::coupon_code_for_rule( $candidate );
		if ( class_exists( 'PepSelect_BOGO_Rule' ) && 0 === strcasecmp( PepSelect_BOGO_Rule::COUPON_CODE, $code ) ) {
			return true;
		}
		if ( class_exists( 'PepSelect_Compound_Discount' ) ) {
			foreach ( PepSelect_Compound_Discount::get_state()['rules'] as $rule ) {
				if ( 0 === strcasecmp( PepSelect_Compound_Discount::coupon_code_for_rule( $rule ), $code ) ) {
					return true;
				}
			}
		}
		return function_exists( 'wc_get_coupon_id_by_code' ) && (bool) wc_get_coupon_id_by_code( self::coupon_code_for_rule( $candidate ) );
	}

	private static function code_in_list( $code, $codes ) {
		foreach ( $codes as $candidate ) {
			if ( 0 === strcasecmp( wc_format_coupon_code( $code ), wc_format_coupon_code( $candidate ) ) ) {
				return true;
			}
		}
		return false;
	}

	private static function rule_summary( $rule ) {
		$discount = 'percent' === $rule['discount_type'] ? $rule['discount_amount'] . '%' : '$' . $rule['discount_amount'];
		$minimum  = 'none' === $rule['threshold_type'] ? __( 'no minimum', 'pepselect-bogo-quantity' ) : ( 'quantity' === $rule['threshold_type'] ? $rule['threshold_amount'] . ' items' : '$' . $rule['threshold_amount'] . ' subtotal' );
		$stacking = $rule['stackable'] ? __( 'stackable', 'pepselect-bogo-quantity' ) : __( 'exclusive', 'pepselect-bogo-quantity' );
		$excluded = count( $rule['excluded_product_ids'] ?? array() );
		$scope    = $excluded ? sprintf( _n( '%d product excluded', '%d products excluded', $excluded, 'pepselect-bogo-quantity' ), $excluded ) : __( 'no exclusions', 'pepselect-bogo-quantity' );
		return sprintf( __( '%1$s off · %2$s · %3$s · %4$s · %5$s', 'pepselect-bogo-quantity' ), $discount, $minimum, ucfirst( str_replace( '_', ' ', $rule['audience'] ) ), $stacking, $scope );
	}

	private static function page_url() {
		return admin_url( 'admin.php?page=' . self::PAGE_SLUG );
	}

	private static function authorize_admin_action( $nonce_action ) {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( esc_html__( 'You do not have permission to manage discounts.', 'pepselect-bogo-quantity' ), '', array( 'response' => 403 ) );
		}
		check_admin_referer( $nonce_action );
	}

	private static function redirect_with_notice( $notice, $message = '', $edit_id = '' ) {
		$args = array( 'pepselect_notice' => $notice );
		if ( $message ) { $args['pepselect_message'] = $message; }
		if ( $edit_id ) { $args['edit'] = $edit_id; }
		wp_safe_redirect( add_query_arg( $args, self::page_url() ) );
		exit;
	}

	private static function render_admin_notice() {
		$notice = sanitize_key( wp_unslash( $_GET['pepselect_notice'] ?? '' ) );
		$messages = array( 'saved' => __( 'Discount saved. Activate it when you are ready.', 'pepselect-bogo-quantity' ), 'toggled' => __( 'Discount status updated.', 'pepselect-bogo-quantity' ), 'deleted' => __( 'Discount deleted.', 'pepselect-bogo-quantity' ), 'not_found' => __( 'That discount no longer exists.', 'pepselect-bogo-quantity' ) );
		$message = 'error' === $notice ? sanitize_text_field( wp_unslash( $_GET['pepselect_message'] ?? '' ) ) : ( $messages[ $notice ] ?? '' );
		if ( $message ) {
			printf( '<div class="notice %1$s is-dismissible"><p>%2$s</p></div>', 'error' === $notice || 'not_found' === $notice ? 'notice-error' : 'notice-success', esc_html( $message ) );
		}
	}

	private static function rest_payload( $state ) {
		return array( 'schema_version' => self::SCHEMA_VERSION, 'revision' => self::revision( $state ), 'rules' => $state['rules'], 'contract' => array( 'max_rules' => self::MAX_RULES, 'customer_label_max_length' => self::LABEL_LIMIT, 'threshold_type' => array( 'none', 'quantity', 'subtotal' ), 'audience' => array( 'everyone', 'logged_in', 'subscribers', 'purchasers', 'vip', 'specific' ), 'excluded_product_ids' => true, 'stackable' => true ) );
	}

	private static function revision( $state ) {
		return hash( 'sha256', wp_json_encode( self::normalize_state( $state ) ) );
	}
}

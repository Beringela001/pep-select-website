<?php

defined( 'ABSPATH' ) || exit;

final class PepSelect_OE_Plugin {
	private const INVALID_ACCESS_LIMIT  = 20;
	private const INVALID_ACCESS_WINDOW = 300;

	private static ?self $instance = null;
	private PepSelect_OE_Access_Store $store;
	private PepSelect_OE_View_Model $view_model;
	private PepSelect_OE_Renderer $renderer;

	public static function instance(): self {
		if ( null === self::$instance ) { self::$instance = new self(); }
		return self::$instance;
	}

	private function __construct() {
		global $wpdb;
		$this->store      = new PepSelect_OE_Access_Store( $wpdb );
		$this->view_model = new PepSelect_OE_View_Model();
		$this->renderer   = new PepSelect_OE_Renderer();
	}

	public static function activate(): void {
		PepSelect_OE_Access_Store::install();
		add_option( 'pepselect_oe_enabled', '0', '', false );
		add_option( 'pepselect_oe_relationships_approved', '0', '', false );
		add_option( 'pepselect_oe_coupon_code', '', '', false );
		add_option( 'pepselect_oe_blocked_compounds', '', '', false );
		self::ensure_order_page();
	}

	public static function deactivate(): void { flush_rewrite_rules( false ); }

	private static function ensure_order_page(): int {
		$existing = get_page_by_path( 'order', OBJECT, 'page' );
		if ( $existing instanceof WP_Post ) {
			update_option( 'pepselect_oe_page_id', $existing->ID, false );
			return (int) $existing->ID;
		}
		$page_id = wp_insert_post(
			array(
				'post_title' => 'Your Pep Select order', 'post_name' => 'order', 'post_status' => 'publish', 'post_type' => 'page', 'comment_status' => 'closed',
				'post_content' => '<div class="pepselect-order-fallback"><h1>Your Pep Select order</h1><p>This order page is temporarily unavailable. Your order details are still available in My Account, and our team can help with any questions.</p><p><a href="/my-account/orders/">Review your orders</a> <a href="/contact-us/">Contact our team</a></p></div>',
			),
			true
		);
		if ( is_wp_error( $page_id ) ) { return 0; }
		update_option( 'pepselect_oe_page_id', (int) $page_id, false );
		return (int) $page_id;
	}

	public static function order_page_url(): string {
		$page_id = absint( get_option( 'pepselect_oe_page_id', 0 ) );
		$url = $page_id ? get_permalink( $page_id ) : home_url( '/order/' );
		return $url ?: home_url( '/order/' );
	}

	public function boot(): void {
		add_action( 'rest_api_init', array( new PepSelect_OE_REST_Controller( $this->store ), 'register_routes' ) );
		add_action( 'admin_init', array( $this, 'ensure_runtime_page' ), 1 );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'template_redirect', array( $this, 'protect_order_page' ), 0 );
		add_action( 'template_redirect', array( $this, 'handle_reorder' ), 5 );
		add_action( 'wp_head', array( $this, 'robots_meta' ), 1 );
		add_filter( 'wp_robots', array( $this, 'wp_robots' ) );
		add_filter( 'the_content', array( $this, 'render_order_content' ), 20 );
		add_filter( 'body_class', array( $this, 'body_class' ) );
	}

	public function ensure_runtime_page(): void { self::ensure_order_page(); }

	public function register_settings(): void {
		register_setting( 'pepselect_oe', 'pepselect_oe_enabled', array( 'type' => 'string', 'default' => '0', 'sanitize_callback' => static fn( $value ) => '1' === (string) $value ? '1' : '0' ) );
		register_setting( 'pepselect_oe', 'pepselect_oe_relationships_approved', array( 'type' => 'string', 'default' => '0', 'sanitize_callback' => static fn( $value ) => '1' === (string) $value ? '1' : '0' ) );
		register_setting( 'pepselect_oe', 'pepselect_oe_coupon_code', array( 'type' => 'string', 'default' => '', 'sanitize_callback' => static fn( $value ) => function_exists( 'wc_format_coupon_code' ) ? wc_format_coupon_code( (string) $value ) : sanitize_text_field( (string) $value ) ) );
		register_setting( 'pepselect_oe', 'pepselect_oe_blocked_compounds', array( 'type' => 'string', 'default' => '', 'sanitize_callback' => static fn( $value ) => sanitize_text_field( (string) $value ) ) );
	}

	public function admin_menu(): void {
		add_submenu_page( 'woocommerce', 'Order Experience', 'Order Experience', 'manage_woocommerce', 'pepselect-order-experience', array( $this, 'settings_page' ) );
	}

	public function settings_page(): void {
		$page_id = absint( get_option( 'pepselect_oe_page_id', 0 ) );
		$checks = array(
			'Plugin version'       => PEPSELECT_OE_VERSION,
			'Feature switch'       => '1' === get_option( 'pepselect_oe_enabled', '0' ) ? 'Enabled' : 'Disabled (safe default)',
			'WooCommerce'          => function_exists( 'wc_get_order' ) ? 'Available' : 'Unavailable',
			'Permanent order page' => $page_id && 'publish' === get_post_status( $page_id ) ? 'Published' : 'Needs repair',
			'Access-record table'  => $this->store->table_exists() ? 'Ready' : 'Needs repair',
			'Ops REST endpoint'    => rest_url( PepSelect_OE_REST_Controller::NAMESPACE . '/orders/{woo-order-id}/snapshot' ),
			'Content registry'     => count( PepSelect_OE_Content_Registry::compounds() ) . ' compounds',
			'Relationship cards'   => '1' === get_option( 'pepselect_oe_relationships_approved', '0' ) ? 'Owner approved' : 'Awaiting owner approval',
			'Thank-you coupon'     => $this->coupon_diagnostic(),
		);
		?>
		<div class="wrap">
			<h1>Pep Select Order Experience</h1>
			<p>The permanent <code>/order/</code> fallback remains available if this feature or plugin is turned off. Enabling the feature activates secure customer order records; it does not replace WooCommerce order storage.</p>
			<form method="post" action="options.php">
				<?php settings_fields( 'pepselect_oe' ); ?>
				<input type="hidden" name="pepselect_oe_enabled" value="0"><p><label><input type="checkbox" name="pepselect_oe_enabled" value="1" <?php checked( get_option( 'pepselect_oe_enabled', '0' ), '1' ); ?>> Enable secure order pages</label></p>
				<input type="hidden" name="pepselect_oe_relationships_approved" value="0"><p><label><input type="checkbox" name="pepselect_oe_relationships_approved" value="1" <?php checked( get_option( 'pepselect_oe_relationships_approved', '0' ), '1' ); ?>> Approve the current related-compound registry</label></p>
				<p><label for="pepselect-oe-coupon">15% thank-you coupon code</label><br><input id="pepselect-oe-coupon" class="regular-text" type="text" name="pepselect_oe_coupon_code" value="<?php echo esc_attr( get_option( 'pepselect_oe_coupon_code', '' ) ); ?>"></p>
				<p><label for="pepselect-oe-blocked">Compounds excluded from related cards</label><br><input id="pepselect-oe-blocked" class="regular-text" type="text" name="pepselect_oe_blocked_compounds" value="<?php echo esc_attr( get_option( 'pepselect_oe_blocked_compounds', '' ) ); ?>"><br><span class="description">Comma-separated product names, such as PT-141, KPV.</span></p>
				<?php submit_button(); ?>
			</form>
			<p><a class="button button-secondary" href="<?php echo esc_url( add_query_arg( 'pepselect-preview', '1', self::order_page_url() ) ); ?>" target="_blank" rel="noopener">Preview sample order</a></p>
			<h2>Diagnostics</h2><table class="widefat striped" style="max-width:900px"><tbody><?php foreach ( $checks as $label => $value ) : ?><tr><th scope="row"><?php echo esc_html( $label ); ?></th><td><?php echo esc_html( $value ); ?></td></tr><?php endforeach; ?></tbody></table>
			<h2>Relationship registry</h2><p>Cards appear only when an available compound shares one or more approved research areas with the order. Ordered, hidden, unavailable, and duplicate products stay out.</p>
			<table class="widefat striped" style="max-width:900px"><thead><tr><th>Compound</th><th>Connects through</th></tr></thead><tbody><?php $area_labels = PepSelect_OE_Content_Registry::area_labels(); foreach ( PepSelect_OE_Content_Registry::compounds() as $key => $entry ) : ?><tr><th scope="row"><?php echo esc_html( PepSelect_OE_Content_Registry::display_name( $key ) ); ?></th><td><?php echo esc_html( implode( ', ', array_map( static fn( $area ) => $area_labels[ $area ] ?? $area, $entry['areas'] ) ) ); ?></td></tr><?php endforeach; ?></tbody></table>
		</div>
		<?php
	}

	private function is_order_page(): bool {
		$page_id = absint( get_option( 'pepselect_oe_page_id', 0 ) );
		return is_page( $page_id ?: 'order' );
	}

	public function protect_order_page(): void {
		if ( ! $this->is_order_page() ) { return; }
		nocache_headers();
		header( 'X-Robots-Tag: noindex, nofollow, noarchive, nosnippet, noimageindex', true );
		header( 'Referrer-Policy: no-referrer', true );
		header( 'X-Content-Type-Options: nosniff', true );
	}

	public function robots_meta(): void {
		if ( $this->is_order_page() ) { echo "<meta name=\"robots\" content=\"noindex,nofollow,noarchive,nosnippet,noimageindex\">\n"; }
	}

	public function wp_robots( array $robots ): array {
		if ( $this->is_order_page() ) {
			foreach ( array( 'noindex', 'nofollow', 'noarchive', 'nosnippet', 'noimageindex' ) as $directive ) { $robots[ $directive ] = true; }
			unset( $robots['max-image-preview'] );
		}
		return $robots;
	}

	public function body_class( array $classes ): array {
		if ( $this->is_order_page() ) { $classes[] = 'pepselect-order-experience'; }
		return $classes;
	}

	private function authorized_record(): ?array {
		$token = isset( $_GET['access'] ) ? sanitize_text_field( wp_unslash( $_GET['access'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( $token ) {
			if ( $this->invalid_access_is_limited() || 1 !== preg_match( '/^[A-Za-z0-9_-]{43}$/', $token ) ) {
				$this->note_invalid_access();
				return null;
			}
			$record = $this->store->find_active_by_token( $token );
			if ( ! $record ) { $this->note_invalid_access(); }
			return $record;
		}
		$order_key = isset( $_GET['order_key'] ) ? sanitize_text_field( wp_unslash( $_GET['order_key'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$order_id = $order_key && function_exists( 'wc_get_order_id_by_order_key' ) ? wc_get_order_id_by_order_key( $order_key ) : 0;
		if ( ! $order_id || ! is_user_logged_in() || ! function_exists( 'wc_get_order' ) ) { return null; }
		$order = wc_get_order( $order_id );
		if ( ! $order || (int) $order->get_customer_id() !== get_current_user_id() || ! hash_equals( (string) $order->get_order_key(), $order_key ) ) { return null; }
		return $this->store->find_by_order( $order_id );
	}

	private function invalid_access_key(): string {
		$ip = sanitize_text_field( wp_unslash( (string) ( $_SERVER['REMOTE_ADDR'] ?? 'unknown' ) ) );
		$agent = sanitize_text_field( wp_unslash( (string) ( $_SERVER['HTTP_USER_AGENT'] ?? 'unknown' ) ) );
		$fingerprint = hash_hmac( 'sha256', $ip . '|' . substr( $agent, 0, 180 ), wp_salt( 'nonce' ) );
		return 'pepselect_oe_bad_' . substr( $fingerprint, 0, 40 );
	}

	private function invalid_access_is_limited(): bool {
		return (int) get_transient( $this->invalid_access_key() ) >= self::INVALID_ACCESS_LIMIT;
	}

	private function note_invalid_access(): void {
		$key = $this->invalid_access_key();
		$count = min( self::INVALID_ACCESS_LIMIT, (int) get_transient( $key ) + 1 );
		set_transient( $key, $count, self::INVALID_ACCESS_WINDOW );
	}

	private function is_preview(): bool {
		return $this->is_order_page() && isset( $_GET['pepselect-preview'] ) && '1' === sanitize_text_field( wp_unslash( $_GET['pepselect-preview'] ) ) && current_user_can( 'manage_woocommerce' ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

	private function coupon_diagnostic(): string {
		$code = trim( (string) get_option( 'pepselect_oe_coupon_code', '' ) );
		if ( '' === $code || ! class_exists( 'WC_Coupon' ) ) { return 'Not configured'; }
		$coupon = new WC_Coupon( $code );
		if ( ! $coupon->get_id() ) { return 'Code not found'; }
		if ( 'percent' !== $coupon->get_discount_type() || 15.0 !== (float) $coupon->get_amount() ) { return 'Must be a 15% coupon'; }
		return 1 === (int) $coupon->get_usage_limit_per_user() ? 'Ready (one use per customer)' : 'Set usage limit per user to 1';
	}

	public function handle_reorder(): void {
		if ( ! $this->is_order_page() || 'POST' !== strtoupper( (string) ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) || 'reorder' !== (string) ( $_POST['pepselect_oe_action'] ?? '' ) ) { return; }
		if ( ! isset( $_POST['pepselect_oe_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['pepselect_oe_nonce'] ) ), 'pepselect_oe_reorder' ) ) {
			wp_die( esc_html__( 'This reorder request expired. Reopen the order page and try again.', 'pepselect-order-experience' ), '', array( 'response' => 403 ) );
		}
		$record = $this->authorized_record();
		$order  = $record && function_exists( 'wc_get_order' ) ? wc_get_order( (int) $record['order_id'] ) : null;
		if ( ! $order || $order->has_status( array( 'cancelled', 'refunded', 'failed' ) ) || ! function_exists( 'WC' ) || ! WC()->cart ) {
			wc_add_notice( 'These items could not be added from this order.', 'error' );
			wp_safe_redirect( wc_get_cart_url() ); exit;
		}
		$added = 0;
		foreach ( $order->get_items( 'line_item' ) as $item ) {
			$product = $item->get_product();
			$net_quantity = max( 0, (int) $item->get_quantity() + (int) $order->get_qty_refunded_for_item( $item->get_id() ) );
			if ( 0 === $net_quantity ) {
				wc_add_notice( sprintf( '%s was fully refunded and was not added.', $item->get_name() ), 'notice' ); continue;
			}
			if ( ! $product instanceof WC_Product || ! $product->is_purchasable() || ! $product->is_in_stock() ) {
				wc_add_notice( sprintf( '%s is not currently available and was not added.', $item->get_name() ), 'notice' ); continue;
			}
			$quantity = $net_quantity;
			if ( $product->managing_stock() ) { $quantity = min( $quantity, max( 0, (int) $product->get_stock_quantity() ) ); }
			$variation_id = $product->is_type( 'variation' ) ? $product->get_id() : 0;
			$parent_id = $variation_id ? $product->get_parent_id() : $product->get_id();
			if ( $quantity && WC()->cart->add_to_cart( $parent_id, $quantity, $variation_id, $variation_id ? $product->get_variation_attributes() : array() ) ) { $added++; }
		}
		wc_add_notice( $added ? 'Available items from your order were added to the cart. Review current prices and quantities before checkout.' : 'No items from this order are currently available.', $added ? 'success' : 'notice' );
		wp_safe_redirect( wc_get_cart_url() ); exit;
	}

	public function render_order_content( string $content ): string {
		if ( ! in_the_loop() || ! is_main_query() || ! $this->is_order_page() ) { return $content; }
		if ( $this->is_preview() ) {
			$this->enqueue_assets();
			return $this->renderer->render( $this->preview_model() );
		}
		if ( '1' !== get_option( 'pepselect_oe_enabled', '0' ) ) { return $content; }
		$record = $this->authorized_record();
		if ( ! $record || ! function_exists( 'wc_get_order' ) ) { return $content; }
		$order = wc_get_order( (int) $record['order_id'] );
		$snapshot = json_decode( (string) $record['snapshot_json'], true );
		if ( ! $order || ! is_array( $snapshot ) ) { return $content; }
		$model = $this->view_model->build( $order, $snapshot );
		$args = array_filter( array(
			'access' => isset( $_GET['access'] ) ? sanitize_text_field( wp_unslash( $_GET['access'] ) ) : '',
			'order_key' => isset( $_GET['order_key'] ) ? sanitize_text_field( wp_unslash( $_GET['order_key'] ) ) : '',
		) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$model['reorder_action'] = esc_url_raw( add_query_arg( $args, self::order_page_url() ) );
		$this->enqueue_assets();
		return $this->renderer->render( $model );
	}

	private function enqueue_assets(): void {
		wp_enqueue_style( 'pepselect-order-experience', plugins_url( 'assets/order-experience.css', PEPSELECT_OE_FILE ), array(), PEPSELECT_OE_VERSION );
		wp_enqueue_style( 'pepselect-order-experience-overrides', plugins_url( 'assets/order-experience-overrides.css', PEPSELECT_OE_FILE ), array( 'pepselect-order-experience' ), PEPSELECT_OE_VERSION );
		wp_enqueue_script( 'pepselect-order-experience', plugins_url( 'assets/order-experience.js', PEPSELECT_OE_FILE ), array(), PEPSELECT_OE_VERSION, true );
	}

	/** @return array<string,mixed> */
	private function preview_model(): array {
		$order = new WC_Order();
		$order->set_billing_first_name( 'Alex' ); $order->set_status( 'completed' ); $order->set_date_created( '2026-08-24 10:42:00' ); $order->set_currency( 'USD' ); $order->set_total( 266.97 );
		$snapshot = array( 'items' => array(
			array( 'product_name' => 'GLP-3 R', 'strength' => '30 mg', 'quantity' => 1, 'allocations' => array( array( 'batch_number' => 'ND_R30_060326', 'quantity' => 1, 'coa_permalink' => home_url( '/testing/retatrutide-30mg/nd_r30_060326/' ), 'lab' => 'ILS Labs', 'tested_date' => '2026-06-24', 'purity_percent' => '97.62', 'third_party_tested' => true ) ) ),
			array( 'product_name' => 'Tesamorelin', 'strength' => '10 mg', 'quantity' => 1, 'allocations' => array( array( 'batch_number' => 'PSTES1071926GX', 'quantity' => 1, 'coa_permalink' => home_url( '/testing/tesamorelin-10-mg/pstes1071926gx/' ), 'lab' => 'Freedom Diagnostics', 'tested_date' => '2026-07-23', 'purity_percent' => '99.89', 'third_party_tested' => true ) ) ),
			array( 'product_name' => 'GHK-CU', 'strength' => '50 mg', 'quantity' => 1, 'allocations' => array( array( 'batch_number' => 'PSGKCU5071926GX', 'quantity' => 1, 'coa_permalink' => home_url( '/testing/ghk-cu-50-mg/psgkcu5071926gx/' ), 'lab' => 'Freedom Diagnostics', 'tested_date' => '2026-07-23', 'purity_percent' => '99.85', 'third_party_tested' => true ) ) ),
		) );
		$model = $this->view_model->build( $order, $snapshot, true );
		$model['order_number'] = 'PS-DEMO'; $model['order_total'] = wc_price( 266.97 ); $model['subtotal'] = wc_price( 266.97 ); $model['shipping'] = wc_price( 0 );
		$prices = array( 179.99, 52.99, 33.99 );
		foreach ( $model['items'] as $index => &$item ) { $item['line_total'] = wc_price( $prices[ $index ] ); $item['available'] = true; }
		unset( $item );
		$model['can_reorder'] = true; $model['reorder_action'] = '';
		return $model;
	}
}

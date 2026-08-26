<?php

defined( 'ABSPATH' ) || exit;

final class PepSelect_OE_Plugin {
	private static ?self $instance = null;
	private PepSelect_OE_Access_Store $store;

	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		global $wpdb;
		$this->store = new PepSelect_OE_Access_Store( $wpdb );
	}

	public static function activate(): void {
		PepSelect_OE_Access_Store::install();
		add_option( 'pepselect_oe_enabled', '0', '', false );
		self::ensure_order_page();
	}

	public static function deactivate(): void {
		flush_rewrite_rules( false );
	}

	private static function ensure_order_page(): int {
		$existing = get_page_by_path( 'order', OBJECT, 'page' );
		if ( $existing instanceof WP_Post ) {
			update_option( 'pepselect_oe_page_id', $existing->ID, false );
			return (int) $existing->ID;
		}
		$content = '<div class="pepselect-order-fallback"><h1>Your Pep Select order</h1><p>This order page is temporarily unavailable. Your order details are still available in My Account, and our team can help with any questions.</p><p><a href="/my-account/orders/">Review your orders</a> <a href="/contact/">Contact our team</a></p></div>';
		$page_id = wp_insert_post(
			array(
				'post_title'     => 'Your Pep Select order',
				'post_name'      => 'order',
				'post_content'   => $content,
				'post_status'    => 'publish',
				'post_type'      => 'page',
				'comment_status' => 'closed',
			),
			true
		);
		if ( ! is_wp_error( $page_id ) ) {
			update_option( 'pepselect_oe_page_id', (int) $page_id, false );
			return (int) $page_id;
		}
		return 0;
	}

	public static function order_page_url(): string {
		$page_id = absint( get_option( 'pepselect_oe_page_id', 0 ) );
		$url     = $page_id ? get_permalink( $page_id ) : home_url( '/order/' );
		return $url ?: home_url( '/order/' );
	}

	public function boot(): void {
		add_action( 'rest_api_init', array( new PepSelect_OE_REST_Controller( $this->store ), 'register_routes' ) );
		add_action( 'admin_init', array( $this, 'ensure_runtime_page' ), 1 );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'template_redirect', array( $this, 'protect_order_page' ), 0 );
		add_action( 'wp_head', array( $this, 'robots_meta' ), 1 );
		add_filter( 'wp_robots', array( $this, 'wp_robots' ) );
		add_filter( 'the_content', array( $this, 'render_order_content' ), 20 );
		add_filter( 'body_class', array( $this, 'body_class' ) );
	}

	public function ensure_runtime_page(): void {
		self::ensure_order_page();
	}

	public function register_settings(): void {
		register_setting(
			'pepselect_oe',
			'pepselect_oe_enabled',
			array(
				'type'              => 'string',
				'default'           => '0',
				'sanitize_callback' => static fn( $value ) => '1' === (string) $value ? '1' : '0',
			)
		);
	}

	public function admin_menu(): void {
		add_submenu_page(
			'woocommerce',
			'Order Experience',
			'Order Experience',
			'manage_woocommerce',
			'pepselect-order-experience',
			array( $this, 'settings_page' )
		);
	}

	public function settings_page(): void {
		$page_id = absint( get_option( 'pepselect_oe_page_id', 0 ) );
		$checks  = array(
			'Feature switch'       => '1' === get_option( 'pepselect_oe_enabled', '0' ) ? 'Enabled' : 'Disabled (safe default)',
			'WooCommerce'          => function_exists( 'wc_get_order' ) ? 'Available' : 'Unavailable',
			'Permanent order page' => $page_id && 'publish' === get_post_status( $page_id ) ? 'Published' : 'Needs repair',
			'Access-record table'  => $this->store->table_exists() ? 'Ready' : 'Needs repair',
			'Ops REST endpoint'    => rest_url( PepSelect_OE_REST_Controller::NAMESPACE . '/orders/{woo-order-id}/snapshot' ),
		);
		?>
		<div class="wrap">
			<h1>Pep Select Order Experience</h1>
			<p>The permanent <code>/order/</code> fallback remains available if this feature or plugin is turned off. Enabling the feature activates secure customer order records; it does not replace WooCommerce order storage.</p>
			<form method="post" action="options.php">
				<?php settings_fields( 'pepselect_oe' ); ?>
				<input type="hidden" name="pepselect_oe_enabled" value="0">
				<label><input type="checkbox" name="pepselect_oe_enabled" value="1" <?php checked( get_option( 'pepselect_oe_enabled', '0' ), '1' ); ?>> Enable secure order pages</label>
				<?php submit_button(); ?>
			</form>
			<h2>Diagnostics</h2>
			<table class="widefat striped" style="max-width:900px"><tbody>
				<?php foreach ( $checks as $label => $value ) : ?>
					<tr><th scope="row"><?php echo esc_html( $label ); ?></th><td><?php echo esc_html( $value ); ?></td></tr>
				<?php endforeach; ?>
			</tbody></table>
		</div>
		<?php
	}

	private function is_order_page(): bool {
		$page_id = absint( get_option( 'pepselect_oe_page_id', 0 ) );
		return is_page( $page_id ?: 'order' );
	}

	public function protect_order_page(): void {
		if ( ! $this->is_order_page() ) {
			return;
		}
		nocache_headers();
		header( 'X-Robots-Tag: noindex, nofollow, noarchive, nosnippet, noimageindex', true );
		header( 'Referrer-Policy: same-origin', true );
	}

	public function robots_meta(): void {
		if ( $this->is_order_page() ) {
			echo "<meta name=\"robots\" content=\"noindex,nofollow,noarchive,nosnippet,noimageindex\">\n";
		}
	}

	public function wp_robots( array $robots ): array {
		if ( $this->is_order_page() ) {
			$robots['noindex']       = true;
			$robots['nofollow']      = true;
			$robots['noarchive']     = true;
			$robots['nosnippet']     = true;
			$robots['noimageindex']  = true;
			unset( $robots['max-image-preview'] );
		}
		return $robots;
	}

	public function body_class( array $classes ): array {
		if ( $this->is_order_page() ) {
			$classes[] = 'pepselect-order-experience';
		}
		return $classes;
	}

	private function authorized_record(): ?array {
		$token = isset( $_GET['access'] ) ? sanitize_text_field( wp_unslash( $_GET['access'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( $token ) {
			return $this->store->find_active_by_token( $token );
		}
		$order_id = isset( $_GET['order'] ) ? absint( $_GET['order'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! $order_id || ! is_user_logged_in() || ! function_exists( 'wc_get_order' ) ) {
			return null;
		}
		$order = wc_get_order( $order_id );
		if ( ! $order || (int) $order->get_customer_id() !== get_current_user_id() ) {
			return null;
		}
		return $this->store->find_by_order( $order_id );
	}

	public function render_order_content( string $content ): string {
		if ( ! in_the_loop() || ! is_main_query() || ! $this->is_order_page() || '1' !== get_option( 'pepselect_oe_enabled', '0' ) ) {
			return $content;
		}
		$record = $this->authorized_record();
		if ( ! $record || ! function_exists( 'wc_get_order' ) ) {
			return $content;
		}
		$order = wc_get_order( (int) $record['order_id'] );
		if ( ! $order ) {
			return $content;
		}
		$snapshot = json_decode( (string) $record['snapshot_json'], true );
		if ( ! is_array( $snapshot ) ) {
			return $content;
		}
		wp_enqueue_style( 'pepselect-order-experience', plugins_url( 'assets/order-experience.css', PEPSELECT_OE_FILE ), array(), PEPSELECT_OE_VERSION );
		ob_start();
		?>
		<main class="pepselect-oe-shell">
			<p class="pepselect-oe-eyebrow">Order #<?php echo esc_html( $order->get_order_number() ); ?></p>
			<h1>Thank you, <?php echo esc_html( $order->get_billing_first_name() ?: 'for your order' ); ?>.</h1>
			<p>Your secure order record is ready. The full customer experience will be activated only after the next milestone is approved.</p>
			<section class="pepselect-oe-foundation" aria-label="Verified batch allocations">
				<h2>Verified batch allocations</h2>
				<?php foreach ( $snapshot['items'] as $item ) : ?>
					<article>
						<h3><?php echo esc_html( (string) ( $item['product_name'] ?? 'Compound' ) ); ?></h3>
						<?php foreach ( (array) $item['allocations'] as $allocation ) : ?>
							<p>Batch <strong><?php echo esc_html( (string) $allocation['batch_number'] ); ?></strong> · <?php echo esc_html( (string) $allocation['quantity'] ); ?> vial(s)
							<?php if ( ! empty( $allocation['coa_permalink'] ) ) : ?> · <a href="<?php echo esc_url( $allocation['coa_permalink'] ); ?>">Review COA</a><?php endif; ?></p>
						<?php endforeach; ?>
					</article>
				<?php endforeach; ?>
			</section>
		</main>
		<?php
		return (string) ob_get_clean();
	}
}

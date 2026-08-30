<?php

defined( 'ABSPATH' ) || exit;

/** Register one Cart Discounts admin area with a submenu per discount type. */
final class PepSelect_Discount_Admin {
	const PAGE_SLUG = 'pepselect-cart-discounts';

	/** Register the parent menu and shared assets. */
	public static function boot() {
		add_action( 'admin_menu', array( __CLASS__, 'register_menu' ), 8 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
	}

	/** Create the same parent/submenu pattern used by the popup tools. */
	public static function register_menu() {
		add_menu_page(
			__( 'Cart Discounts', 'pepselect-bogo-quantity' ),
			__( 'Cart Discounts', 'pepselect-bogo-quantity' ),
			'manage_woocommerce',
			self::PAGE_SLUG,
			array( __CLASS__, 'render_overview' ),
			'dashicons-tickets-alt',
			56
		);
		add_submenu_page( self::PAGE_SLUG, __( 'Discount Overview', 'pepselect-bogo-quantity' ), __( 'Overview', 'pepselect-bogo-quantity' ), 'manage_woocommerce', self::PAGE_SLUG, array( __CLASS__, 'render_overview' ) );
	}

	/** Load the shared admin treatment on this plugin's screens only. */
	public static function enqueue_assets() {
		$page = sanitize_key( wp_unslash( $_GET['page'] ?? '' ) );
		if ( ! in_array( $page, array( self::PAGE_SLUG, PepSelect_BOGO_Rule::PAGE_SLUG, PepSelect_Compound_Discount::PAGE_SLUG, PepSelect_Sitewide_Discount::PAGE_SLUG ), true ) ) {
			return;
		}
		wp_enqueue_style( 'woocommerce_admin_styles' );
		wp_enqueue_script( 'wc-enhanced-select' );
		wp_enqueue_style( 'pepselect-discount-admin', plugin_dir_url( PEPSELECT_BOGO_FILE ) . 'assets/compound-discount-admin.css', array(), PEPSELECT_BOGO_VERSION );
	}

	/** Render a compact launchpad without duplicating rule editors. */
	public static function render_overview() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}
		$pages = array(
			array( 'title' => __( 'Buy 4 Get 1', 'pepselect-bogo-quantity' ), 'description' => __( 'Choose eligible compounds and control whether the free-vial offer can stack.', 'pepselect-bogo-quantity' ), 'slug' => PepSelect_BOGO_Rule::PAGE_SLUG ),
			array( 'title' => __( 'Compound Discounts', 'pepselect-bogo-quantity' ), 'description' => __( 'Create product-combination rules with independent activation and stacking.', 'pepselect-bogo-quantity' ), 'slug' => PepSelect_Compound_Discount::PAGE_SLUG ),
			array( 'title' => __( 'Sitewide Discounts', 'pepselect-bogo-quantity' ), 'description' => __( 'Discount every compound for everyone or a selected customer audience.', 'pepselect-bogo-quantity' ), 'slug' => PepSelect_Sitewide_Discount::PAGE_SLUG ),
		);
		?>
		<div class="wrap pepselect-discount-settings">
			<h1><?php esc_html_e( 'Cart Discounts', 'pepselect-bogo-quantity' ); ?></h1>
			<p><?php esc_html_e( 'Manage every automatic cart promotion from one place. Each discount can be stackable or exclusive.', 'pepselect-bogo-quantity' ); ?></p>
			<div class="pepselect-discount-layout pepselect-discount-overview">
				<?php foreach ( $pages as $page ) : ?>
					<section class="pepselect-discount-panel">
						<h2><?php echo esc_html( $page['title'] ); ?></h2>
						<p><?php echo esc_html( $page['description'] ); ?></p>
						<a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=' . $page['slug'] ) ); ?>"><?php esc_html_e( 'Manage discounts', 'pepselect-bogo-quantity' ); ?></a>
					</section>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}

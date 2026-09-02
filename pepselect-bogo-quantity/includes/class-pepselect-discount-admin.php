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
		wp_enqueue_script( 'pepselect-discount-admin', plugin_dir_url( PEPSELECT_BOGO_FILE ) . 'assets/discount-admin.js', array(), PEPSELECT_BOGO_VERSION, true );
	}

	/** Render the shared top navigation used by every discount editor. */
	public static function render_header( $active = '' ) {
		$bogo     = PepSelect_BOGO_Rule::get_state();
		$compound = PepSelect_Compound_Discount::get_state();
		$sitewide = PepSelect_Sitewide_Discount::get_state();
		$items    = array(
			PepSelect_BOGO_Rule::PAGE_SLUG => array( 'title' => __( 'Buy 4 Get 1', 'pepselect-bogo-quantity' ), 'description' => __( 'Free-vial promotion', 'pepselect-bogo-quantity' ), 'count' => ! empty( $bogo['enabled'] ) ? 1 : 0 ),
			PepSelect_Compound_Discount::PAGE_SLUG => array( 'title' => __( 'Compound Discounts', 'pepselect-bogo-quantity' ), 'description' => __( 'Product-combination rules', 'pepselect-bogo-quantity' ), 'count' => self::active_count( $compound['rules'] ?? array() ) ),
			PepSelect_Sitewide_Discount::PAGE_SLUG => array( 'title' => __( 'Sitewide Discounts', 'pepselect-bogo-quantity' ), 'description' => __( 'Catalog-wide promotions', 'pepselect-bogo-quantity' ), 'count' => self::active_count( $sitewide['rules'] ?? array() ) ),
		);
		?>
		<header class="pepselect-discount-heading">
			<div><h1><?php esc_html_e( 'Cart Discounts', 'pepselect-bogo-quantity' ); ?></h1><p><?php esc_html_e( 'Manage every automatic cart promotion from one place.', 'pepselect-bogo-quantity' ); ?></p></div>
			<span class="pepselect-version">v<?php echo esc_html( PEPSELECT_BOGO_VERSION ); ?></span>
		</header>
		<nav class="pepselect-discount-tabs" aria-label="<?php esc_attr_e( 'Discount types', 'pepselect-bogo-quantity' ); ?>">
			<?php foreach ( $items as $slug => $item ) : ?>
				<a class="pepselect-discount-tab <?php echo $slug === $active ? 'is-active' : ''; ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=' . $slug ) ); ?>" <?php echo $slug === $active ? 'aria-current="page"' : ''; ?>>
					<strong><?php echo esc_html( $item['title'] ); ?></strong><span><?php echo esc_html( $item['description'] ); ?></span><em class="<?php echo $item['count'] ? 'is-on' : ''; ?>"><?php echo $item['count'] ? esc_html( sprintf( _n( '%d active', '%d active', $item['count'], 'pepselect-bogo-quantity' ), $item['count'] ) ) : esc_html__( 'Off', 'pepselect-bogo-quantity' ); ?></em>
				</a>
			<?php endforeach; ?>
		</nav>
		<?php
	}

	private static function active_count( $rules ) {
		return count( array_filter( (array) $rules, static function ( $rule ) { return ! empty( $rule['enabled'] ); } ) );
	}

	/** Render a compact launchpad without duplicating rule editors. */
	public static function render_overview() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}
		$pages = array(
			array( 'title' => __( 'Buy 4 Get 1', 'pepselect-bogo-quantity' ), 'description' => __( 'Choose eligible compounds and control whether the free-vial offer can stack.', 'pepselect-bogo-quantity' ), 'slug' => PepSelect_BOGO_Rule::PAGE_SLUG ),
			array( 'title' => __( 'Compound Discounts', 'pepselect-bogo-quantity' ), 'description' => __( 'Create product-combination rules with independent activation and stacking.', 'pepselect-bogo-quantity' ), 'slug' => PepSelect_Compound_Discount::PAGE_SLUG ),
			array( 'title' => __( 'Sitewide Discounts', 'pepselect-bogo-quantity' ), 'description' => __( 'Discount the catalog for everyone or a selected audience, with optional product exclusions.', 'pepselect-bogo-quantity' ), 'slug' => PepSelect_Sitewide_Discount::PAGE_SLUG ),
		);
		?>
		<div class="wrap pepselect-discount-settings">
			<?php self::render_header(); ?>
			<p class="pepselect-discount-intro"><?php esc_html_e( 'Choose a discount type above. Every promotion can be independently activated and configured as stackable or exclusive.', 'pepselect-bogo-quantity' ); ?></p>
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

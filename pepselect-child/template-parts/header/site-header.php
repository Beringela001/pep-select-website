<?php
/**
 * Default coded site header.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$logo_html = pepselect_child_get_header_logo_html();
?>
<header id="pepselect-site-header" class="pepselect-site-header" data-pepselect-header-preview>
	<div class="pepselect-header__announcement">
		<div class="pepselect-header__inner">
			<p><?php esc_html_e( 'Free 2-Day Shipping on Cart Subtotals of $200', 'pepselect-child' ); ?></p>
		</div>
	</div>

	<div class="pepselect-header__main">
		<div class="pepselect-header__inner pepselect-header__main-grid">
			<div class="pepselect-header__brand">
				<?php if ( '' !== $logo_html ) : ?>
					<a class="pepselect-header__logo-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr__( 'Pep Select home', 'pepselect-child' ); ?>">
						<?php echo $logo_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Core generates escaped attachment markup. ?>
					</a>
				<?php else : ?>
					<a class="pepselect-header__site-name" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php echo esc_html( get_bloginfo( 'name' ) ); ?>
					</a>
				<?php endif; ?>
			</div>

			<form class="pepselect-header__search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" data-suggest-endpoint="<?php echo esc_url( rest_url( 'pepselect-child/v1/compound-search' ) ); ?>">
				<label class="screen-reader-text" for="pepselect-header-product-search">
					<?php esc_html_e( 'Search products', 'pepselect-child' ); ?>
				</label>
				<input
					id="pepselect-header-product-search"
					class="pepselect-header__search-input"
					type="search"
					name="s"
					value="<?php echo esc_attr( get_search_query() ); ?>"
					placeholder="<?php echo esc_attr__( 'Which compound are you looking for?', 'pepselect-child' ); ?>"
					required
				>
				<input type="hidden" name="post_type" value="product">
				<button class="pepselect-header__search-submit" type="submit" aria-label="<?php echo esc_attr__( 'Search products', 'pepselect-child' ); ?>">
					<svg aria-hidden="true" focusable="false" viewBox="0 0 24 24" width="20" height="20">
						<circle cx="11" cy="11" r="7"></circle>
						<path d="m20 20-4-4"></path>
					</svg>
				</button>
			</form>

			<?php get_template_part( 'template-parts/header/actions' ); ?>
		</div>
	</div>

	<div class="pepselect-header__navigation-row">
		<div class="pepselect-header__inner">
			<?php get_template_part( 'template-parts/header/navigation' ); ?>
		</div>
	</div>
</header>

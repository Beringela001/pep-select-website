<?php
/**
 * Coded compounds archive and product search results (WEB-2D).
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$is_product_search = is_search() && 'product' === get_query_var( 'post_type' );
$search_term       = $is_product_search ? get_search_query() : '';
$found             = (int) $GLOBALS['wp_query']->found_posts;

get_header();
?>
<main id="pepselect-compounds-main" class="pepselect-compounds" tabindex="-1">
	<section class="pepselect-compounds__section">
		<div class="pepselect-compounds__inner">
			<header class="pepselect-compounds__heading">
				<p class="pepselect-compounds__eyebrow"><?php esc_html_e( 'Compounds', 'pepselect-child' ); ?></p>
				<?php if ( $is_product_search ) : ?>
					<h1><?php echo esc_html( sprintf( __( 'Results for "%s"', 'pepselect-child' ), $search_term ) ); ?></h1>
					<p class="pepselect-compounds__lead">
						<?php
						echo esc_html(
							sprintf(
								/* translators: %s: result count. */
								_n( '%s compound matches your search.', '%s compounds match your search.', $found, 'pepselect-child' ),
								number_format_i18n( $found )
							)
						);
						?>
					</p>
				<?php else : ?>
					<h1><?php echo wp_kses( __( 'Selection is <em>the standard.</em>', 'pepselect-child' ), array( 'em' => array() ) ); ?></h1>
					<p class="pepselect-compounds__lead"><?php esc_html_e( 'Pep Select carries what passes our review and nothing else. The details sit on every card.', 'pepselect-child' ); ?></p>
				<?php endif; ?>
			</header>

			<?php if ( have_posts() ) : ?>
				<ul class="pepselect-compounds__grid">
					<?php
					while ( have_posts() ) :
						the_post();
						$product = wc_get_product( get_the_ID() );

						if ( $product && $product->is_visible() ) {
							echo '<li>';
							get_template_part( 'template-parts/home/product-card', null, array( 'product' => $product ) );
							echo '</li>';
						}
					endwhile;
					?>
				</ul>
			<?php else : ?>
				<div class="pepselect-compounds__empty">
					<p><?php esc_html_e( 'No compounds match that search.', 'pepselect-child' ); ?></p>
					<a class="pepselect-compounds__empty-link" href="<?php echo esc_url( pepselect_child_get_shop_url() ); ?>"><?php esc_html_e( 'Browse the full selection', 'pepselect-child' ); ?></a>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php
get_footer();

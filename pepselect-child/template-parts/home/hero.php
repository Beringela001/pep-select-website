<?php
/**
 * WEB-2C homepage hero with a single brand family image.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$shop_url      = isset( $args['shop_url'] ) ? $args['shop_url'] : home_url( '/shop/' );
$testing_url   = isset( $args['testing_url'] ) ? $args['testing_url'] : home_url( '/testing/' );
$hero_image_id = isset( $args['hero_image_id'] ) ? (int) $args['hero_image_id'] : 0;
$hero_source_url = $hero_image_id ? wp_get_attachment_url( $hero_image_id ) : '';
$hero_filename   = $hero_source_url ? wp_basename( (string) wp_parse_url( $hero_source_url, PHP_URL_PATH ) ) : '';
$optimized_hero  = 0 === strpos( strtolower( $hero_filename ), 'ps-laying_fam' );
?>
<section class="pepselect-home__section pepselect-home__hero" aria-labelledby="pepselect-home-title">
	<div class="pepselect-home__inner pepselect-home__hero-grid">
		<div class="pepselect-home__hero-copy">
			<p class="pepselect-home__eyebrow pepselect-home__eyebrow--dark"><?php esc_html_e( 'Research Without the Runaround', 'pepselect-child' ); ?></p>
			<h1 id="pepselect-home-title">
				<span><?php esc_html_e( 'The label is the easy part.', 'pepselect-child' ); ?></span>
				<em><?php echo wp_kses_post( __( 'What&rsquo;s behind it matters.', 'pepselect-child' ) ); ?></em>
			</h1>
			<p class="pepselect-home__lead"><?php echo wp_kses_post( __( 'You shouldn&rsquo;t need five tabs and a leap of faith to look into a research compound. Pep Select keeps current product details and available batch documentation in one place, so the information is there when you need it.', 'pepselect-child' ) ); ?></p>
			<div class="pepselect-home__actions">
				<a class="pepselect-home__button pepselect-home__button--primary" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Explore Our Selection', 'pepselect-child' ); ?></a>
				<a class="pepselect-home__button pepselect-home__button--outline-navy" href="<?php echo esc_url( $testing_url ); ?>" aria-label="<?php esc_attr_e( 'View Pep Select Quality Archive and batch documentation', 'pepselect-child' ); ?>"><?php esc_html_e( 'See the Receipts', 'pepselect-child' ); ?></a>
			</div>
		</div>

	</div>

	<?php if ( $hero_image_id ) : ?>
		<figure class="pepselect-home__hero-figure">
		<?php
			if ( $optimized_hero ) {
				$hero_asset_url = trailingslashit( get_stylesheet_directory_uri() ) . 'assets/images/hero/';
				$hero_srcset    = array();

				foreach ( array( 320, 480, 640, 768, 1024, 1536, 2048 ) as $hero_width ) {
					$hero_srcset[] = esc_url( $hero_asset_url . 'pepselect-home-hero-' . $hero_width . '.webp' ) . ' ' . $hero_width . 'w';
				}
				?>
				<picture>
					<source
						type="image/webp"
						srcset="<?php echo esc_attr( implode( ', ', $hero_srcset ) ); ?>"
						sizes="(max-width: 767px) 92vw, 50vw"
					>
				<?php
			}

			echo wp_get_attachment_image(
				$hero_image_id,
				'medium_large',
				false,
				array(
				'alt'           => __( 'Pep Select research compound lineup', 'pepselect-child' ),
				'decoding'      => 'async',
				'fetchpriority' => 'high',
				'loading'       => 'eager',
				'sizes'         => '(max-width: 767px) 92vw, 50vw',
				)
			); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WordPress generates escaped responsive image markup.

			if ( $optimized_hero ) {
				echo '</picture>';
			}
			?>
		</figure>
	<?php else : ?>
		<div class="pepselect-home__hero-image-ready">
		<span><?php esc_html_e( 'Pep Select Catalog', 'pepselect-child' ); ?></span>
		</div>
	<?php endif; ?>
</section>

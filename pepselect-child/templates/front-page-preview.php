<?php
/**
 * Public coded homepage.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) || ! pepselect_child_is_home_preview_request() ) {
	exit;
}

$product_state = pepselect_child_get_homepage_products();
$products      = isset( $product_state['products'] ) ? (array) $product_state['products'] : array();
$visual_pool   = array_values(
	array_filter(
		$products,
		static function ( $product ) {
			return is_a( $product, 'WC_Product' ) && 0 < $product->get_image_id();
		}
	)
);

$home_context = array(
	'shop_url'       => pepselect_child_get_shop_url(),
	'testing_url'    => home_url( '/testing/' ),
	'faq_url'        => pepselect_child_get_page_url( 'faq' ),
	'product_state'  => $product_state,
	'hero_image_id'  => pepselect_child_get_hero_image_id(),
	'visual_product' => isset( $visual_pool[0] ) ? $visual_pool[0] : null,
	'faqs'           => pepselect_child_get_homepage_faqs(),
);

get_header();
?>
<main id="pepselect-home-main" class="pepselect-home" tabindex="-1">
	<?php get_template_part( 'template-parts/home/hero', null, $home_context ); ?>
	<?php get_template_part( 'template-parts/home/featured-products', null, $home_context ); ?>
	<?php get_template_part( 'template-parts/home/why-pep-select', null, $home_context ); ?>
	<?php get_template_part( 'template-parts/home/faq', null, $home_context ); ?>
</main>
<?php
get_footer();

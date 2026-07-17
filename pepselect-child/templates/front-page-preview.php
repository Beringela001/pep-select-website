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
$hero_products = isset( $product_state['hero_products'] ) ? (array) $product_state['hero_products'] : array();

$home_context = array(
	'shop_url'         => pepselect_child_get_shop_url(),
	'testing_url'      => home_url( '/testing/' ),
	'faq_url'          => pepselect_child_get_page_url( 'faq' ),
	'product_state'    => $product_state,
	'hero_products'    => $hero_products,
	'visual_product'   => isset( $hero_products[0] ) ? $hero_products[0] : null,
	'identity_product' => isset( $hero_products[1] ) ? $hero_products[1] : ( isset( $hero_products[0] ) ? $hero_products[0] : null ),
	'faqs'             => pepselect_child_get_homepage_faqs(),
);

get_header();
?>
<main id="pepselect-home-main" class="pepselect-home" tabindex="-1">
	<?php get_template_part( 'template-parts/home/hero', null, $home_context ); ?>
	<?php get_template_part( 'template-parts/home/confidence-strip', null, $home_context ); ?>
	<?php get_template_part( 'template-parts/home/featured-products', null, $home_context ); ?>
	<?php get_template_part( 'template-parts/home/why-pep-select', null, $home_context ); ?>
	<?php get_template_part( 'template-parts/home/batch-identity', null, $home_context ); ?>
	<?php get_template_part( 'template-parts/home/coa-feature', null, $home_context ); ?>
	<?php get_template_part( 'template-parts/home/faq', null, $home_context ); ?>
	<?php get_template_part( 'template-parts/home/final-cta', null, $home_context ); ?>
</main>
<?php
get_footer();

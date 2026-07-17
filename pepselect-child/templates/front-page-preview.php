<?php
/**
 * Administrator-only coded homepage preview.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) || ! pepselect_child_is_home_preview_request() ) {
	exit;
}

$home_context = array(
	'shop_url'      => pepselect_child_get_shop_url(),
	'testing_url'   => home_url( '/testing/' ),
	'faq_url'       => pepselect_child_get_page_url( 'faq' ),
	'contact_url'   => pepselect_child_get_page_url( 'contact' ),
	'product_state' => pepselect_child_get_homepage_products(),
);

get_header();
?>
<main id="pepselect-home-main" class="pepselect-home" tabindex="-1">
	<?php get_template_part( 'template-parts/home/hero', null, $home_context ); ?>
	<?php get_template_part( 'template-parts/home/identifiers', null, $home_context ); ?>
	<?php get_template_part( 'template-parts/home/testing-preview', null, $home_context ); ?>
	<?php get_template_part( 'template-parts/home/featured-products', null, $home_context ); ?>
	<?php get_template_part( 'template-parts/home/status-guide', null, $home_context ); ?>
	<?php get_template_part( 'template-parts/home/transparency', null, $home_context ); ?>
	<?php get_template_part( 'template-parts/home/research-support', null, $home_context ); ?>
	<?php get_template_part( 'template-parts/home/final-cta', null, $home_context ); ?>
</main>
<?php
get_footer();

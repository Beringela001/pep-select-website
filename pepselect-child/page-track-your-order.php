<?php
/**
 * Coded presentation for the guest order-tracking page (/track-your-order/).
 *
 * WordPress selects this template automatically by slug. The page content
 * (the WooCommerce order-tracking shortcode) stays authoritative; this
 * template supplies the heading composition and layout around it.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="pepselect-track-order-main" class="pepselect-track-order" tabindex="-1">
	<section class="pepselect-track-order__section">
		<div class="pepselect-track-order__inner">
			<header class="pepselect-track-order__heading">
				<h1 class="pepselect-track-order__eyebrow"><?php esc_html_e( 'Order Tracking', 'pepselect-child' ); ?></h1>
				<p class="pepselect-track-order__lead"><?php esc_html_e( 'Enter your order number and the billing email you used at checkout to see where your shipment stands.', 'pepselect-child' ); ?></p>
			</header>

			<div class="pepselect-track-order__content">
				<?php
				while ( have_posts() ) :
					the_post();
					the_content();
				endwhile;
				?>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();

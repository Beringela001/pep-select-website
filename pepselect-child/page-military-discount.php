<?php
/**
 * Coded presentation for the Military & First Responder page
 * (/military-discount/).
 *
 * WordPress selects this template automatically for the page with slug
 * "military-discount". The VerifyPass button lives in the page's WordPress
 * content as authored HTML and is rendered here via the_content(), so its
 * onclick behavior and popup are preserved exactly. This template supplies
 * the surrounding design.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="pepselect-military-main" class="pepselect-military" tabindex="-1">
	<section class="pepselect-military__hero">
		<div class="pepselect-military__inner">
			<p class="pepselect-military__eyebrow"><?php esc_html_e( 'Discount Program', 'pepselect-child' ); ?></p>
			<h1 class="pepselect-military__title">
				<span><?php esc_html_e( 'For those who serve,', 'pepselect-child' ); ?></span>
				<em><?php esc_html_e( '20% off every order.', 'pepselect-child' ); ?></em>
			</h1>
			<p class="pepselect-military__lead"><?php esc_html_e( 'We honor military members, veterans, and first responders. Verify your service once, and your code is yours to use.', 'pepselect-child' ); ?></p>
		</div>
	</section>

	<section class="pepselect-military__body">
		<div class="pepselect-military__inner pepselect-military__card">
			<ol class="pepselect-military__steps">
				<li class="pepselect-military__step">
					<span class="pepselect-military__step-number" aria-hidden="true">01</span>
					<div class="pepselect-military__step-body">
						<h2 class="pepselect-military__step-title"><?php esc_html_e( 'Verify your status', 'pepselect-child' ); ?></h2>
						<p><?php esc_html_e( 'Confirm your eligibility with our partner VerifyPass. It opens in a secure window and takes about a minute.', 'pepselect-child' ); ?></p>
					</div>
				</li>
				<li class="pepselect-military__step">
					<span class="pepselect-military__step-number" aria-hidden="true">02</span>
					<div class="pepselect-military__step-body">
						<h2 class="pepselect-military__step-title"><?php esc_html_e( 'Receive your code', 'pepselect-child' ); ?></h2>
						<p><?php esc_html_e( 'Once verified, a one-time discount code arrives by email.', 'pepselect-child' ); ?></p>
					</div>
				</li>
				<li class="pepselect-military__step">
					<span class="pepselect-military__step-number" aria-hidden="true">03</span>
					<div class="pepselect-military__step-body">
						<h2 class="pepselect-military__step-title"><?php esc_html_e( 'Apply it at checkout', 'pepselect-child' ); ?></h2>
						<p><?php esc_html_e( 'Enter the code at checkout to take 20% off your order.', 'pepselect-child' ); ?></p>
					</div>
				</li>
			</ol>

			<div class="pepselect-military__action">
				<div class="pepselect-military__verify">
					<?php
					// Only the VerifyPass button should live in the page content, so
					// its onclick and popup are preserved exactly. If the content
					// holds more than the button, trim the page content to just it.
					while ( have_posts() ) :
						the_post();
						the_content();
					endwhile;
					?>
				</div>
				<p class="pepselect-military__fineprint"><?php esc_html_e( 'One code per verified person. Cannot be combined with other offers.', 'pepselect-child' ); ?></p>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();

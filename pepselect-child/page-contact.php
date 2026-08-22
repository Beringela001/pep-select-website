<?php
/**
 * Coded presentation for the Contact page (/contact/).
 *
 * WordPress selects this template automatically for the page with slug
 * "contact". The form posts back to this page and is handled in
 * inc/contact-page.php; this template renders the layout and result state.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$contact_state = function_exists( 'pepselect_child_get_contact_state' ) ? pepselect_child_get_contact_state() : null;
$values        = isset( $contact_state['values'] ) ? $contact_state['values'] : array(
	'name'    => '',
	'email'   => '',
	'subject' => '',
	'body'    => '',
);
$testing_url = home_url( '/testing/' );
$track_url   = function_exists( 'pepselect_child_get_track_order_url' ) ? pepselect_child_get_track_order_url() : home_url( '/track-your-order/' );

get_header();
?>
<main id="pepselect-contact-main" class="pepselect-contact" tabindex="-1">
	<section class="pepselect-contact__section">
		<div class="pepselect-contact__inner pepselect-contact__grid">

			<div class="pepselect-contact__intro">
				<header class="pepselect-contact__heading">
					<h1 class="pepselect-contact__eyebrow"><?php esc_html_e( 'Contact', 'pepselect-child' ); ?></h1>
					<p class="pepselect-contact__lead"><?php esc_html_e( 'Questions about an order, a batch, or a compound? Send us a message and we\'ll reply within one business day.', 'pepselect-child' ); ?></p>
				</header>

				<div class="pepselect-contact__hub">
					<h2 class="pepselect-contact__hub-title"><?php esc_html_e( 'Before you write', 'pepselect-child' ); ?></h2>
					<ul class="pepselect-contact__hub-list">
						<li>
							<span class="pepselect-contact__hub-detail">
								<?php
								printf(
									/* translators: %s: link to the Quality Archive. */
									esc_html__( 'Every batch\'s certificate of analysis is filed in the %s, and stays there after a batch sells out.', 'pepselect-child' ),
									'<a href="' . esc_url( $testing_url ) . '">' . esc_html__( 'Quality Archive', 'pepselect-child' ) . '</a>'
								);
								?>
							</span>
						</li>
						<li>
							<span class="pepselect-contact__hub-detail">
								<?php
								printf(
									/* translators: %s: link to the order tracking page. */
									esc_html__( 'Order status and tracking live on the %s.', 'pepselect-child' ),
									'<a href="' . esc_url( $track_url ) . '">' . esc_html__( 'order tracking page', 'pepselect-child' ) . '</a>'
								);
								?>
							</span>
						</li>
						<li>
							<span class="pepselect-contact__hub-detail">
								<?php esc_html_e( 'Lost a cash-back redeem code? Email us and we will help you recover it.', 'pepselect-child' ); ?>
							</span>
						</li>
					</ul>
					<p class="pepselect-contact__hub-direct">
						<?php
						printf(
							/* translators: %s: support email address as a mailto link. */
							esc_html__( 'For anything else, email %s. We reply within one business day.', 'pepselect-child' ),
							'<a href="mailto:support@pepselect.com">support@pepselect.com</a>'
						);
						?>
					</p>
					<p class="pepselect-contact__hub-direct">
						<?php esc_html_e( 'Call us:', 'pepselect-child' ); ?>
						<a href="tel:+18337377528">1 (833) 737-7528 (1-833-PEP-SLCT)</a>
					</p>
				</div>
			</div>

			<div class="pepselect-contact__form-col">
				<?php if ( $contact_state && 'success' === $contact_state['status'] ) : ?>
					<div class="pepselect-contact__notice pepselect-contact__notice--success" role="status">
						<p><?php echo esc_html( $contact_state['message'] ); ?></p>
					</div>
				<?php else : ?>
					<?php if ( $contact_state && 'error' === $contact_state['status'] ) : ?>
						<div class="pepselect-contact__notice pepselect-contact__notice--error" role="alert">
							<p><?php echo esc_html( $contact_state['message'] ); ?></p>
						</div>
					<?php endif; ?>

					<form class="pepselect-contact__form" method="post" action="<?php echo esc_url( pepselect_child_get_contact_url() ); ?>#pepselect-contact-form" id="pepselect-contact-form" novalidate>
						<?php wp_nonce_field( 'pepselect_contact', 'pepselect_contact_nonce' ); ?>

						<div class="pepselect-contact__field-row">
							<div class="pepselect-contact__field">
								<label for="pepselect-contact-name"><?php esc_html_e( 'Name', 'pepselect-child' ); ?></label>
								<input type="text" id="pepselect-contact-name" name="pepselect_contact_name" value="<?php echo esc_attr( $values['name'] ); ?>" autocomplete="name">
							</div>
							<div class="pepselect-contact__field">
								<label for="pepselect-contact-email"><?php esc_html_e( 'Email', 'pepselect-child' ); ?> <span class="pepselect-contact__req" aria-hidden="true">*</span></label>
								<input type="email" id="pepselect-contact-email" name="pepselect_contact_email" value="<?php echo esc_attr( $values['email'] ); ?>" autocomplete="email" required>
							</div>
						</div>

						<div class="pepselect-contact__field">
							<label for="pepselect-contact-subject"><?php esc_html_e( 'Subject', 'pepselect-child' ); ?></label>
							<input type="text" id="pepselect-contact-subject" name="pepselect_contact_subject" value="<?php echo esc_attr( $values['subject'] ); ?>">
						</div>

						<div class="pepselect-contact__field">
							<label for="pepselect-contact-message"><?php esc_html_e( 'Message', 'pepselect-child' ); ?> <span class="pepselect-contact__req" aria-hidden="true">*</span></label>
							<textarea id="pepselect-contact-message" name="pepselect_contact_message" rows="6" required><?php echo esc_textarea( $values['body'] ); ?></textarea>
						</div>

						<?php // Honeypot: visually hidden, off-screen; real users never see or fill it. ?>
						<div class="pepselect-contact__hp" aria-hidden="true">
							<label for="pepselect-contact-website"><?php esc_html_e( 'Website', 'pepselect-child' ); ?></label>
							<input type="text" id="pepselect-contact-website" name="pepselect_contact_website" tabindex="-1" autocomplete="off">
						</div>

						<button type="submit" name="pepselect_contact_submit" value="1" class="pepselect-contact__submit"><?php esc_html_e( 'Send message', 'pepselect-child' ); ?></button>
					</form>
				<?php endif; ?>
			</div>

		</div>
	</section>
</main>
<?php
get_footer();

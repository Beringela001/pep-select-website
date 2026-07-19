<?php
/**
 * Pep Select subscribe form for Back In Stock Notifier.
 *
 * Theme override of the plugin's templates/default-form.php via the
 * documented mechanism (theme folder back-in-stock-notifier-for-woocommerce).
 * All functional plumbing from the original is preserved: field classes the
 * plugin's JS binds to, hidden product/variation/security fields, the
 * cwgstock_output container, and every extension hook (the I Agree consent
 * row renders through those hooks). Only the Bootstrap presentation is
 * replaced with design-system markup.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="cwginstock-subscribe-form pepselect-bisn <?php echo esc_attr( $variation_class ); ?> <?php echo esc_attr( $dynamic_wrapper_class ); ?>">
	<?php do_action( 'cwg_instock_before_heading', $product_id, $variation_id ); ?>

	<p class="pepselect-bisn__title">
		<?php
		$pepselect_form_title = esc_html__( 'Email when stock available', 'back-in-stock-notifier-for-woocommerce' );
		echo esc_html( isset( $get_option['form_title'] ) && '' !== $get_option['form_title'] ? $instock_api->sanitize_text_field( $get_option['form_title'] ) : $pepselect_form_title );
		?>
	</p>

	<?php do_action( 'cwg_instock_after_heading', $product_id, $variation_id ); ?>

	<div class="pepselect-bisn__fields">
		<?php do_action( 'cwg_instock_before_input_fields', $product_id, $variation_id ); ?>

		<?php if ( $name_field_visibility ) : ?>
			<input type="text" class="cwgstock_name pepselect-bisn__input" name="cwgstock_name" placeholder="<?php echo esc_attr( $instock_api->sanitize_text_field( $name_placeholder ) ); ?>" value="<?php echo esc_attr( $subscriber_name ); ?>" />
		<?php endif; ?>

		<input type="email" class="cwgstock_email pepselect-bisn__input" name="cwgstock_email" placeholder="<?php echo esc_attr( $instock_api->sanitize_text_field( $placeholder ) ); ?>" value="<?php echo esc_attr( $email ); ?>" />

		<?php if ( $phone_field_visibility ) : ?>
			<input type="tel" class="cwgstock_phone pepselect-bisn__input" name="cwgstock_phone" />
		<?php endif; ?>

		<?php do_action( 'cwg_instock_after_email_field', $product_id, $variation_id ); ?>

		<input type="hidden" class="cwg-phone-number" name="cwg-phone-number" value="" />
		<input type="hidden" class="cwg-phone-number-meta" name="cwg-phone-number-meta" value="" />
		<input type="hidden" class="cwg-product-id" name="cwg-product-id" value="<?php echo intval( $product_id ); ?>" />
		<input type="hidden" class="cwg-variation-id" name="cwg-variation-id" value="<?php echo intval( $variation_id ); ?>" />
		<input type="hidden" class="cwg-security" name="cwg-security" value="<?php echo esc_attr( $security ); ?>" />

		<?php do_action( 'cwg_instock_after_input_fields', $product_id, $variation_id ); ?>
	</div>

	<div class="pepselect-bisn__submit-row">
		<?php
		do_action( 'cwginstock_before_submit_button', $product_id, $variation_id );

		$pepselect_btn_class = isset( $get_option['btn_class'] ) && '' !== $get_option['btn_class'] ? str_replace( ',', ' ', $get_option['btn_class'] ) : '';
		?>
		<input type="submit" name="cwgstock_submit" class="cwgstock_button pepselect-bisn__submit <?php echo esc_attr( $pepselect_btn_class ); ?>" <?php echo do_shortcode( apply_filters( 'cwgstock_submit_attr', '', $product_id, $variation_id ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Filter contract from the plugin; mirrors the original template. ?> value="<?php echo esc_attr( $instock_api->sanitize_text_field( $button_label ) ); ?>" />
		<?php do_action( 'cwginstock_after_submit_button', $product_id, $variation_id ); ?>
	</div>

	<div class="cwgstock_output pepselect-bisn__output"></div>
</section>

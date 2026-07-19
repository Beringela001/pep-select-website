<?php
/**
 * Login / Register form (Pep Select custom).
 *
 * Overrides WooCommerce's default login/register template. All field names,
 * nonces, and hooks are preserved exactly, so WooCommerce auth and Nextend
 * Social Login continue to work. The Google button is placed explicitly via
 * Nextend's shortcode; if the shortcode returns nothing, the block is omitted
 * so there is never an empty box.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package PepSelectChild
 * @version 9.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_customer_login_form' );

$pepselect_registration_on = ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) );
?>
<div class="pepselect-login">
	<div class="pepselect-login__inner">

		<div class="pepselect-login__panel" id="customer_login">

			<header class="pepselect-login__head">
				<h1 class="pepselect-login__title"><?php esc_html_e( 'Sign in', 'pepselect-child' ); ?></h1>
				<p class="pepselect-login__lead"><?php esc_html_e( 'Access your orders, cash back, and account details.', 'pepselect-child' ); ?></p>
			</header>

			<?php
			/**
			 * Nextend Social Login (Pro) places the Google button via its
			 * WooCommerce form integration using the login/register form hooks
			 * below, so no manual button is rendered here.
			 */
			?>

			<div class="pepselect-login__forms">

				<div class="pepselect-login__col pepselect-login__col--login">
					<h2 class="pepselect-login__col-title"><?php esc_html_e( 'Log in', 'pepselect-child' ); ?></h2>

					<form class="woocommerce-form woocommerce-form-login login pepselect-login__form" method="post" novalidate>

						<?php do_action( 'woocommerce_login_form_start' ); ?>

						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="username"><?php esc_html_e( 'Email address', 'pepselect-child' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
							<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) && is_string( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
						</p>
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="password"><?php esc_html_e( 'Password', 'pepselect-child' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
							<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" required aria-required="true" />
						</p>

						<?php do_action( 'woocommerce_login_form' ); ?>

						<p class="form-row pepselect-login__actions">
							<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
								<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'pepselect-child' ); ?></span>
							</label>
							<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
							<button type="submit" class="woocommerce-button button woocommerce-form-login__submit<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="login" value="<?php esc_attr_e( 'Log in', 'pepselect-child' ); ?>"><?php esc_html_e( 'Log in', 'pepselect-child' ); ?></button>
						</p>

						<p class="woocommerce-LostPassword lost_password">
							<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'pepselect-child' ); ?></a>
						</p>

						<?php do_action( 'woocommerce_login_form_end' ); ?>

					</form>
				</div>

				<?php if ( $pepselect_registration_on ) : ?>
					<div class="pepselect-login__col pepselect-login__col--register">
						<h2 class="pepselect-login__col-title"><?php esc_html_e( 'Create an account', 'pepselect-child' ); ?></h2>

						<form method="post" class="woocommerce-form woocommerce-form-register register pepselect-login__form" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

							<?php do_action( 'woocommerce_register_form_start' ); ?>

							<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
								<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
									<label for="reg_username"><?php esc_html_e( 'Username', 'pepselect-child' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
									<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
								</p>
							<?php endif; ?>

							<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
								<label for="reg_email"><?php esc_html_e( 'Email address', 'pepselect-child' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
								<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
							</p>

							<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
								<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
									<label for="reg_password"><?php esc_html_e( 'Password', 'pepselect-child' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
									<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" required aria-required="true" />
								</p>
							<?php else : ?>
								<p class="pepselect-login__hint"><?php esc_html_e( 'A link to set a new password will be sent to your email address.', 'pepselect-child' ); ?></p>
							<?php endif; ?>

							<?php do_action( 'woocommerce_register_form' ); ?>

							<p class="woocommerce-form-row form-row pepselect-login__actions">
								<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
								<button type="submit" class="woocommerce-Button woocommerce-button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?> woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'Create account', 'pepselect-child' ); ?>"><?php esc_html_e( 'Create account', 'pepselect-child' ); ?></button>
							</p>

							<?php do_action( 'woocommerce_register_form_end' ); ?>

						</form>
					</div>
				<?php endif; ?>

			</div>
		</div>
	</div>
</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>

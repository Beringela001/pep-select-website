const assert = require('assert');
const fs = require('fs');
const path = require('path');

const root = path.resolve(__dirname, '..');
const php = fs.readFileSync(path.join(root, 'pepselect-cart-recovery.php'), 'utf8');
const js = fs.readFileSync(path.join(root, 'assets', 'cart-recovery.js'), 'utf8');
const css = fs.readFileSync(path.join(root, 'assets', 'cart-recovery.css'), 'utf8');
const adminJs = fs.readFileSync(path.join(root, 'assets', 'admin.js'), 'utf8');
const adminCss = fs.readFileSync(path.join(root, 'assets', 'admin.css'), 'utf8');
const couponEmail = fs.readFileSync(path.join(root, 'templates', 'coupon-email.php'), 'utf8');
const mockup = fs.readFileSync(path.join(root, '..', 'mockups', 'cart-recovery', 'index.html'), 'utf8');

[
  "set_discount_type( $settings['discount_type'] )",
  "set_amount( (float) $settings['discount_amount'] )",
  "set_individual_use( empty( $settings['allow_coupon_stacking'] ) )",
  "set_usage_limit( absint( $settings['usage_limit'] ) )",
  "set_usage_limit_per_user( absint( $settings['usage_limit_per_user'] ) )",
  "set_limit_usage_to_x_items( absint( $settings['limit_usage_to_x_items'] ) )",
  "set_minimum_amount( (string) $settings['minimum_amount'] )",
  "set_maximum_amount( (string) $settings['maximum_amount'] )",
  "set_exclude_sale_items( ! empty( $settings['exclude_sale_items'] ) )",
  "set_product_ids( array_map( 'absint', (array) $settings['product_ids'] ) )",
  "set_product_categories( array_map( 'absint', (array) $settings['product_category_ids'] ) )",
  "add_meta_data( 'product_brands', array_map( 'absint', (array) $settings['product_brand_ids'] )",
  'set_email_restrictions',
  "add_filter( 'woo_ca_recovery_email_data'",
  "add_filter( 'wcar_add_token_data'",
  "add_filter( 'wcf_ca_should_send_email'",
  "add_filter( 'fluent_crm/global_email_limit_per_second'",
  'MARKETING_EMAILS_PER_SECOND = 1',
  'return self::MARKETING_EMAILS_PER_SECOND',
  "'discount_amount'           => '20'",
  "'coupon_prefix'             => 'PEP'",
  "'promo_enabled'             => 0",
  "'promo_start'               => ''",
  "'promo_end'                 => ''",
  "'promo_delay_seconds'       => 8",
  'require_recovery_code_for_final_email',
  "'_pepselect_exit_offer_signature'",
  "'{{pepselect.recovery_coupon_code}}'",
  'setting_timestamp',
  'popup_style',
  'sanitize_settings',
  "register_rest_route(",
  "'/popup-settings'",
  "current_user_can( 'manage_woocommerce' )",
  "'pepselect_popup_settings_updated'",
  'support@pepselect.com'
].forEach((needle) => assert(php.includes(needle), `Missing PHP contract: ${needle}`));

[
  'pep_exit_offer_eligible',
  'pep_exit_offer_view',
  'pep_exit_offer_submit',
  'pep_exit_offer_success',
  'pep_promo_view',
  'pep_promo_click',
  'pep_cart_identified',
  "sessionStorage.setItem('pep_exit_offer_email'",
  "document.documentElement.addEventListener('mouseleave', desktopExit)",
  'eventObject.clientY > 40',
  'pendingDesktopExit = true',
  'if (pendingDesktopExit)',
  'promoIsActive()',
  'config.promo.delaySeconds',
  'config.promo.suppressExit',
  "eventObject.key === 'Escape'",
  "eventObject.key !== 'Tab'"
].forEach((needle) => assert(js.includes(needle), `Missing JS contract: ${needle}`));

[
  'display:grid',
  'place-items:center',
  '--pep-offer-overlay',
  '--pep-offer-card-image',
  '--pep-offer-card-tint',
  'position:relative',
  'max-height:calc(100vh - 36px)'
].forEach((needle) => assert(css.includes(needle), `Missing centered modal contract: ${needle}`));

assert(php.includes("const VERSION                     = '0.4.12'"), 'Plugin version must be 0.4.12');
assert(php.includes("const RECOVERY_COPY_VERSION       = '3'"), 'Saved-cart database copy migration must be versioned');
assert(php.includes("'email_subject' => 'Want another look?'"), 'The 90-minute subject must use the approved copy');
assert(php.includes("'email_subject' => 'Need a hand?'"), 'The 24-hour subject must use the approved copy');
assert(php.includes("'saved cart | 90 minutes' === $template_name"), 'The migration must identify the named 90-minute CartFlows template');
assert(php.includes("'saved cart | 24 hours' === $template_name"), 'The migration must identify the named 24-hour CartFlows template');
assert(php.includes("'saved cart | 48 hours' === $template_name"), 'The migration must identify the named 48-hour CartFlows template');
assert(php.includes('Don\'t forget your code.'), 'The 48-hour body must remind the customer about the private code');
assert(php.includes('The compounds you selected are still in your cart if you would like to take another look.'), 'The 90-minute body must use the approved copy');
assert(php.includes('Just a quick note to let you know your cart is still available if you would like another look.'), 'The 24-hour body must use the approved copy');
assert(php.includes('{{cart.product.table}}'), 'Saved-cart templates must include the cart contents token');
assert(php.includes('{{cart.checkout_url}}'), 'Saved-cart templates must include the recovery link token');
assert(php.includes('{{cart.unsubscribe}}'), 'Saved-cart templates must include the unsubscribe token');
assert(php.includes('{{pepselect.cart.card}}'), 'Approved saved-cart templates must use the mobile-safe Pep Select cart card');
assert(php.includes('pep-select-logo-header.png'), 'Approved saved-cart templates must use the real Pep Select logo');
assert(php.includes('recovery_cart_card_html'), 'Saved-cart messages must render the approved cart card at send time');
assert(php.includes('has_company_footer'), 'Recovery emails must detect existing company footers without deleting body markup');
assert(!php.includes('background:\\s*#f6f8fa'), 'Recovery normalization must not strip broad light-background table rows');
assert(php.includes("empty( trim( wp_strip_all_tags( (string) ( $email_data->email_body ?? '' ) ) ) )"), 'Bodyless recovery templates must be blocked before sending');

assert(!/dataLayer\.push\([^)]*email/i.test(js), 'Email must not be pushed to the dataLayer');
assert(!/https?:\/\//.test(js + css), 'Public assets must not call third-party URLs');
assert(!/one[- ]time/i.test(php + mockup), 'Customer copy must not call the code one-time');
assert(!mockup.includes('—'), 'Reviewed customer copy must not contain em dashes');
assert(!php.includes('marketing_consent'), 'Stay in the Loop must not include a second consent checkbox');
assert(!php.includes('wp_create_user'), 'Email signup must not create a WordPress account');
assert(!js.includes('response.data.code'), 'The private coupon must not be returned to the browser');
assert(php.includes("'usage_limit'               => 1"), 'Recovery coupons must default to one total use');
assert(php.includes("'usage_limit_per_user'      => 1"), 'Recovery coupons must default to one use per email');
assert(php.includes("'Generated coupon options'"), 'Exit Popup must expose the generated coupon rules in its editor');
assert(!php.includes("'Open Cart Discounts → Recovery Codes'"), 'Popup coupon rules must not be overridden by a second editor');
assert(php.includes("'step' => '0.01'"), 'Popup opacity fields must accept saved 0.92 values');
assert(php.includes("'promo_url'"), 'Campaign destination must remain configurable');
assert(!php.includes("'promo_url', __( 'Button destination', 'pepselect-cart-recovery' ), __( 'The page opened after the button is pressed, such as a sale or shop page.', 'pepselect-cart-recovery' ), 'url'"), 'Relative campaign paths must not use invalid HTML URL validation');
assert(!php.includes('ensure_bonus_coupon'), 'The final email must not create a separate bonus coupon');
assert(php.includes("$code ? $code : $this->create_coupon( $email )"), 'The final email must reuse an existing code or create one for a tracked cart');
assert(couponEmail.includes("$pep_email_copy['heading']"), 'Immediate offer email heading must be configurable');
assert(couponEmail.includes("$pep_email_copy['code_note']"), 'Immediate offer email code note must be configurable');
assert(php.includes("'email_code_note'"), 'The configurable email must retain an email-restriction default');
assert(adminJs.includes('wp.media'), 'The admin must support selecting a background image');
assert(adminJs.includes("updatePreview('exit')"), 'The admin must live-preview the exit popup');
assert(adminJs.includes("updatePreview('promo')"), 'The admin must live-preview the campaign popup');
assert(adminJs.includes("campaignButton.hidden = !String(value('promo_button')"), 'The campaign preview button must disappear when its label is blank');
assert(adminJs.includes("campaignButton.style.display = campaignButton.hidden ? 'none' : ''"), 'The preview must override WordPress button display styles');
assert(adminCss.includes('.pep-recovery-grid'), 'The admin campaign form must remain responsive');
assert(adminCss.includes('.pep-recovery-preview__stage'), 'The admin must include a visual popup preview');
assert(php.includes("data-pep-tab=\"exit\""), 'The admin must expose a clear Exit Popup tab');
assert(php.includes("data-pep-tab=\"promo\""), 'The admin must expose a clear Campaign Popup tab');
assert(php.includes("if ( $settings['promo_button'] )"), 'The public campaign button must remain optional');
assert(Buffer.byteLength(js) < 12000, 'JavaScript performance budget exceeded');
assert(Buffer.byteLength(css) < 8000, 'CSS performance budget exceeded');

console.log('Pep Select cart recovery contract checks passed.');

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
  'set_amount( 5 )',
  'set_individual_use( false )',
  'set_usage_limit( 1 )',
  'set_usage_limit_per_user( 1 )',
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
  'ensure_bonus_coupon',
  'require_signup_code_for_final_email',
  "'_pepselect_exit_bonus_email_hash'",
  "'_pepselect_exit_parent_code'",
  "'_pepselect_exit_offer_signature'",
  "'{{pepselect.bonus_coupon_code}}'",
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

assert(php.includes("const VERSION                     = '0.4.0'"), 'Plugin version must be 0.4.0');

assert(!/dataLayer\.push\([^)]*email/i.test(js), 'Email must not be pushed to the dataLayer');
assert(!/https?:\/\//.test(js + css), 'Public assets must not call third-party URLs');
assert(!/one[- ]time/i.test(php + mockup), 'Customer copy must not call the code one-time');
assert(!mockup.includes('—'), 'Reviewed customer copy must not contain em dashes');
assert(!php.includes('marketing_consent'), 'Stay in the Loop must not include a second consent checkbox');
assert(!php.includes('wp_create_user'), 'Email signup must not create a WordPress account');
assert(!js.includes('response.data.code'), 'The private coupon must not be returned to the browser');
assert((php.match(/set_individual_use\( false \)/g) || []).length === 2, 'Both coupons must be stackable');
assert((php.match(/set_usage_limit\( 1 \)/g) || []).length === 2, 'Both coupons must be single-use');
assert((php.match(/set_usage_limit_per_user\( 1 \)/g) || []).length === 2, 'Both coupons must be limited per customer');
assert(php.includes("return $code && (bool) $this->ensure_bonus_coupon( $email, $code );"), 'The 48-hour email must require a generated 5% code');
assert(couponEmail.includes("$pep_email_copy['heading']"), 'Immediate offer email heading must be configurable');
assert(couponEmail.includes("$pep_email_copy['code_note']"), 'Immediate offer email code note must be configurable');
assert(php.includes("'email_code_note'"), 'The configurable email must retain an email-restriction default');
assert(adminJs.includes('wp.media'), 'The admin must support selecting a background image');
assert(adminJs.includes("updatePreview('exit')"), 'The admin must live-preview the exit popup');
assert(adminJs.includes("updatePreview('promo')"), 'The admin must live-preview the campaign popup');
assert(adminCss.includes('.pep-recovery-grid'), 'The admin campaign form must remain responsive');
assert(adminCss.includes('.pep-recovery-preview__stage'), 'The admin must include a visual popup preview');
assert(php.includes("data-pep-tab=\"exit\""), 'The admin must expose a clear Exit Popup tab');
assert(php.includes("data-pep-tab=\"promo\""), 'The admin must expose a clear Campaign Popup tab');
assert(Buffer.byteLength(js) < 12000, 'JavaScript performance budget exceeded');
assert(Buffer.byteLength(css) < 8000, 'CSS performance budget exceeded');

console.log('Pep Select cart recovery contract checks passed.');

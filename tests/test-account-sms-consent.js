const assert = require('assert');
const fs = require('fs');
const path = require('path');

const accountPhp = fs.readFileSync(
	path.join(__dirname, '..', 'pepselect-child', 'inc', 'account.php'),
	'utf8'
);
const dashboard = fs.readFileSync(
	path.join(__dirname, '..', 'pepselect-child', 'woocommerce', 'myaccount', 'dashboard.php'),
	'utf8'
);
const accountCss = fs.readFileSync(
	path.join(__dirname, '..', 'pepselect-child', 'assets', 'css', 'account.css'),
	'utf8'
);
const accountJs = fs.readFileSync(
	path.join(__dirname, '..', 'pepselect-child', 'assets', 'js', 'account-cashback.js'),
	'utf8'
);

const smsIndex = dashboard.indexOf('id="pepselect-sms-preferences"');
const referralIndex = dashboard.indexOf('id="pepselect-dash-referral"');

assert.ok(smsIndex > -1, 'Text-message preference card missing');
assert.ok(referralIndex > smsIndex, 'Text-message preference card must appear before referral');
assert.ok(dashboard.includes('type="tel"'), 'Mobile-number input missing');
assert.ok(dashboard.includes('value="customer_care"'), 'Customer-care consent choice missing');
assert.ok(dashboard.includes('value="marketing"'), 'Marketing consent choice missing');
assert.ok(dashboard.includes('value="none"'), 'No-text choice missing');
assert.ok(!/pepselect-sms__checkbox[^>]*\schecked(?:=|\s|>)/.test(dashboard), 'Consent choices must not be pre-checked');
assert.ok(dashboard.includes('Consent is not a condition of purchase.'), 'Required consent disclosure missing');
assert.ok(dashboard.includes("Reply 'STOP' to unsubscribe at any time."), 'STOP instruction missing');
assert.ok(dashboard.includes('https://pepselect.com/privacy-policy/'), 'Privacy and messaging terms link missing');
assert.ok(accountPhp.includes("wp_verify_nonce( $nonce, 'pepselect_save_sms_preferences' )"), 'Consent save must verify a nonce');
assert.ok(accountPhp.includes("'pepselect_sms_customer_care_consent'"), 'Customer-care consent record missing');
assert.ok(accountPhp.includes("'pepselect_sms_marketing_consent'"), 'Marketing consent record missing');
assert.ok(accountPhp.includes("'pepselect_sms_consent_updated_gmt'"), 'Consent timestamp missing');
assert.ok(accountPhp.includes("( $care || $marketing ) && class_exists( 'WC_Validation' ) && ! WC_Validation::is_phone( $phone )"), 'Opt-ins must validate the mobile number without blocking opt-out');
assert.ok(accountJs.includes("'none' === changed.value"), 'No-text choice exclusivity missing');
assert.ok(accountCss.includes('.pepselect-card--sms') || accountCss.includes('.pepselect-sms__form'), 'Consent card styling missing');

console.log('account SMS consent safeguards verified');

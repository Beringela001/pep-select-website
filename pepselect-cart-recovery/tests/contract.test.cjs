const assert = require('assert');
const fs = require('fs');
const path = require('path');

const root = path.resolve(__dirname, '..');
const php = fs.readFileSync(path.join(root, 'pepselect-cart-recovery.php'), 'utf8');
const js = fs.readFileSync(path.join(root, 'assets', 'cart-recovery.js'), 'utf8');
const css = fs.readFileSync(path.join(root, 'assets', 'cart-recovery.css'), 'utf8');

[
  "set_discount_type( 'percent' )",
  'set_amount( 10 )',
  'set_individual_use( false )',
  'set_usage_limit( 1 )',
  'set_usage_limit_per_user( 1 )',
  'set_email_restrictions',
  "add_filter( 'woo_ca_recovery_email_data'",
  "add_filter( 'wcar_add_token_data'",
  "'enabled'            => 0",
  'support@pepselect.com'
].forEach((needle) => assert(php.includes(needle), `Missing PHP contract: ${needle}`));

[
  'pep_exit_offer_eligible',
  'pep_exit_offer_view',
  'pep_exit_offer_submit',
  'pep_exit_offer_success',
  'pep_cart_identified',
  "sessionStorage.setItem('pep_exit_offer_email'"
].forEach((needle) => assert(js.includes(needle), `Missing JS contract: ${needle}`));

assert(!/dataLayer\.push\([^)]*email/i.test(js), 'Email must not be pushed to the dataLayer');
assert(!/https?:\/\//.test(js + css), 'Public assets must not call third-party URLs');
assert(Buffer.byteLength(js) < 12000, 'JavaScript performance budget exceeded');
assert(Buffer.byteLength(css) < 8000, 'CSS performance budget exceeded');

console.log('Pep Select cart recovery contract checks passed.');

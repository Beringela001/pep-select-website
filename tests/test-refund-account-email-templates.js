const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');

const root = path.resolve(__dirname, '..');
const read = (relative) => fs.readFileSync(path.join(root, relative), 'utf8');

const shared = read('pepselect-child/woocommerce/emails/pepselect-simple-message.php');
const refund = read('pepselect-child/woocommerce/emails/customer-refunded-order.php');
const reset = read('pepselect-child/woocommerce/emails/customer-reset-password.php');
const verify = read('pepselect-child/woocommerce/emails/customer-verify-email.php');
const plainRefund = read('pepselect-child/woocommerce/emails/plain/customer-refunded-order.php');
const plainReset = read('pepselect-child/woocommerce/emails/plain/customer-reset-password.php');
const plainVerify = read('pepselect-child/woocommerce/emails/plain/customer-verify-email.php');

assert.match(shared, /max-width:680px/);
assert.match(shared, /@media only screen and \(max-width:520px\)/);
assert.match(shared, /pepselect_child_email_company_footer_row_html/);
assert.match(shared, /pep-email-order-content/);

for (const template of [refund, reset, verify]) {
  assert.match(template, /pepselect-simple-message\.php/);
  assert.match(template, /\$additional_content/);
}

assert.match(refund, /\$partial_refund/);
assert.match(refund, /Part of your order has been refunded/);
assert.match(refund, /Your order has been refunded/);
assert.match(refund, /woocommerce_email_order_details/);
assert.match(refund, /woocommerce_email_order_meta/);
assert.match(refund, /woocommerce_email_customer_details/);
assert.match(refund, /payment provider/);

assert.match(reset, /'key'\s*=>\s*\$reset_key/);
assert.match(reset, /'id'\s*=>\s*\$user_id/);
assert.match(reset, /rawurlencode\( \$user_login \)/);
assert.match(reset, /wc_get_endpoint_url\( 'lost-password'/);
assert.match(reset, /Choose a new password/);

assert.match(verify, /\$verify_url/);
assert.match(verify, /\$user_email/);
assert.match(verify, /Confirm email address/);

assert.match(plainRefund, /\$partial_refund/);
assert.match(plainRefund, /woocommerce_email_order_details/);
assert.match(plainReset, /\$reset_key/);
assert.match(plainReset, /wc_get_endpoint_url\( 'lost-password'/);
assert.match(plainVerify, /\$verify_url/);

console.log('Refund and account email template checks passed.');

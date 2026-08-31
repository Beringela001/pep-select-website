const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');

const root = path.resolve(__dirname, '..');
const read = (relative) => fs.readFileSync(path.join(root, relative), 'utf8');
const approved = /Have a question\? Reply to this email, and one of our team members will be in touch shortly\./;

const treatedTemplates = [
  'pepselect-child/woocommerce/emails/customer-new-account.php',
  'pepselect-child/woocommerce/emails/customer-on-hold-order.php',
  'pepselect-child/woocommerce/emails/customer-completed-order.php',
  'pepselect-child/woocommerce/emails/customer-refunded-order.php',
  'pepselect-child/woocommerce/emails/customer-reset-password.php',
  'pepselect-child/woocommerce/emails/customer-verify-email.php',
  'pepselect-child/woocommerce/emails/pepselect-bis-email.php',
  'pepselect-child/woocommerce/emails/plain/customer-refunded-order.php',
  'pepselect-child/woocommerce/emails/plain/customer-reset-password.php',
  'pepselect-child/woocommerce/emails/plain/customer-verify-email.php',
  'pepselect-child/woocommerce/emails/plain/pepselect-bis-email.php',
];

for (const template of treatedTemplates) {
  assert.match(read(template), approved, `${template} must use the approved reply line`);
}

const processing = read('pepselect-child/woocommerce/emails/customer-processing-order.php');
assert.match(processing, /customer-completed-order\.php/, 'Processing email must use the treated shared order template');

const recovery = read('pepselect-cart-recovery/pepselect-cart-recovery.php');
assert.match(recovery, approved);
assert.match(recovery, /maybe_upgrade_settings/);
assert.match(recovery, /VERSION_OPTION/);
assert.match(recovery, /in_array\( trim\( \(string\) \$settings\['email_support'\] \), \$legacy_support, true \)/);
assert.match(recovery, /'email_support' === \$key && '' === trim/);
console.log('Treated customer email reply-copy checks passed.');

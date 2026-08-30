const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');

const root = path.resolve(__dirname, '..');
const read = (relative) => fs.readFileSync(path.join(root, relative), 'utf8');

const ownershipLine = '🇺🇸 American-owned and operated.';
const shipFromLine = 'Pep Select orders ship from New York or Georgia.';

const siteFooter = read('pepselect-child/template-parts/footer/site-footer.php');
const faq = read('pepselect-child/inc/faq-content.php');
const homepageFaq = read('pepselect-child/inc/homepage-preview.php');
const emailHelpers = read('pepselect-child/inc/emails.php');
const emailTemplates = [
  'pepselect-child/woocommerce/emails/customer-new-account.php',
  'pepselect-child/woocommerce/emails/customer-on-hold-order.php',
  'pepselect-child/woocommerce/emails/customer-completed-order.php',
  'pepselect-child/woocommerce/emails/pepselect-bis-email.php',
  'pepselect-child/woocommerce/emails/plain/pepselect-bis-email.php',
  'pepselect-cart-recovery/templates/coupon-email.php',
];

assert.ok(siteFooter.includes(ownershipLine));
assert.match(faq, /Where do Pep Select orders ship from\?/);
assert.ok(faq.includes(shipFromLine));
assert.ok(homepageFaq.includes(shipFromLine));
assert.match(emailHelpers, /woocommerce_email_footer_text/);
assert.ok(emailHelpers.includes(ownershipLine));

for (const template of emailTemplates) {
  assert.match(read(template), /American-owned and operated/, `${template} is missing the ownership line`);
}

for (const content of [siteFooter, faq, homepageFaq, emailHelpers, ...emailTemplates.map(read)]) {
  assert.doesNotMatch(content, /fulfillment locations/i);
}

console.log('American ownership and ship-from trust-copy checks passed.');

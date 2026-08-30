const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');

const root = path.resolve(__dirname, '..');
const read = (relative) => fs.readFileSync(path.join(root, relative), 'utf8');

const ownershipLine = '🇺🇸 American-owned and operated.';
const shipFromLine = 'Pep Select orders ship from New York or Georgia.';

const siteFooter = read('pepselect-child/template-parts/footer/site-footer.php');
const footerStyles = read('pepselect-child/assets/css/footer.css');
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
assert.match(siteFooter, /class="pepselect-footer__ownership"/);
assert.match(footerStyles, /\.pepselect-footer__research-copy \.pepselect-footer__ownership[\s\S]*font-size:\s*17px;[\s\S]*font-weight:\s*var\(--pep-font-weight-bold\);/);
assert.match(faq, /Where do Pep Select orders ship from\?/);
assert.ok(faq.includes(shipFromLine));
assert.ok(homepageFaq.includes(shipFromLine));
assert.match(emailHelpers, /woocommerce_email_footer_text/);
assert.ok(emailHelpers.includes(ownershipLine));
assert.match(emailHelpers, /font-size:15px;font-weight:800/);

for (const template of emailTemplates) {
	assert.match(read(template), /American-owned and operated/, `${template} is missing the ownership line`);
}

for (const template of emailTemplates.slice( 0, 4 )) {
	assert.match(read(template), /font-size:15px;\s*font-weight:800/, `${template} is missing the prominent ownership treatment`);
}

for (const content of [siteFooter, faq, homepageFaq, emailHelpers, ...emailTemplates.map(read)]) {
  assert.doesNotMatch(content, /fulfillment locations/i);
}

console.log('American ownership and ship-from trust-copy checks passed.');

const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');

const root = path.resolve(__dirname, '..');
const read = (relative) => fs.readFileSync(path.join(root, relative), 'utf8');
const requiredCompanyDetails = [
  'pepselect.com',
  '2090 Baker Rd, Ste 304 #A85',
  'Kennesaw, GA 30144',
  'support@pepselect.com',
  '1 (833) 737-7528',
  'American-owned and operated company',
];

const helper = read('pepselect-child/inc/emails.php');
const wooFooter = read('pepselect-child/woocommerce/emails/email-footer.php');
const recovery = read('pepselect-cart-recovery/pepselect-cart-recovery.php');
const recoveryCoupon = read('pepselect-cart-recovery/templates/coupon-email.php');

for (const detail of requiredCompanyDetails) {
  assert.ok(helper.includes(detail), `WooCommerce footer helper is missing: ${detail}`);
  assert.ok(recovery.includes(detail), `Cart recovery footer is missing: ${detail}`);
}

assert.match(helper, /background-color:#002A53/);
assert.match(helper, /woocommerce_email_styles/);
assert.match(helper, /#body_content table\.shop_table/);
assert.match(helper, /overflow-wrap: anywhere !important/);
assert.match(helper, /fluentcrm_email_body_text/);
assert.match(helper, /pepselect_child_fluentcrm_company_footer/);
assert.match(wooFooter, /pepselect_child_email_company_footer_html/);
assert.match(recovery, /data-pepselect-company-footer/);
assert.match(recovery, /email_body\s*\.=/);
assert.match(recovery, /normalize_recovery_email_body/);
assert.match(recovery, /data-pepselect-mobile-email/);
assert.match(recovery, /table\.shop_table/);
assert.match(recovery, /Have a question\? Reply to this email, and one of our team members will be in touch shortly\./);
assert.match(recoveryCoupon, /pep_company_footer_html/);

for (const template of [
  'pepselect-child/woocommerce/emails/admin-new-order.php',
  'pepselect-child/woocommerce/emails/customer-new-account.php',
  'pepselect-child/woocommerce/emails/customer-on-hold-order.php',
  'pepselect-child/woocommerce/emails/customer-completed-order.php',
  'pepselect-child/woocommerce/emails/pepselect-simple-message.php',
  'pepselect-child/woocommerce/emails/pepselect-bis-email.php',
]) {
  assert.match(read(template), /pepselect_child_email_company_footer_row_html/);
}

assert.match(
  read('pepselect-child/woocommerce/emails/customer-processing-order.php'),
  /customer-completed-order\.php/
);

for (const newsletter of [
  'mockups/newsletters/what-is-a-peptide/what-is-a-peptide-email-preview.html',
  'mockups/newsletters/ghk-cu-nad-duo/ghk-cu-nad-duo-email-preview.html',
  'mockups/newsletters/labor-day-2026/labor-day-vip-30-email-preview.html',
  'mockups/newsletters/labor-day-2026/labor-day-20-email-preview.html',
]) {
  const html = read(newsletter);
  for (const detail of requiredCompanyDetails) {
    assert.ok(html.includes(detail), `${newsletter} is missing: ${detail}`);
  }
}

console.log('Customer email footer checks passed.');

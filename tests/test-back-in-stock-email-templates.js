const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');

const root = path.resolve(__dirname, '..');
const read = (relative) => fs.readFileSync(path.join(root, relative), 'utf8');

const emails = read('pepselect-child/inc/emails.php');
const html = read('pepselect-child/woocommerce/emails/pepselect-bis-email.php');
const subscription = read('pepselect-child/woocommerce/emails/bis-subscription.php');
const instock = read('pepselect-child/woocommerce/emails/bis-instock.php');
const plain = read('pepselect-child/woocommerce/emails/plain/pepselect-bis-email.php');
const style = read('pepselect-child/style.css');

assert.match(style, /Version:\s+0\.25\.0-beta\.\d+/);
assert.match(subscription, /\$pep_bis_email_type\s*=\s*'subscription'/);
assert.match(instock, /\$pep_bis_email_type\s*=\s*'instock'/);
assert.match(subscription, /pepselect-bis-email\.php/);
assert.match(instock, /pepselect-bis-email\.php/);

assert.match(emails, /woocommerce_email_subject_cwg_bis_subscription/);
assert.match(emails, /woocommerce_email_subject_cwg_bis_instock/);
assert.match(emails, /woocommerce_email_heading_cwg_bis_subscription/);
assert.match(emails, /woocommerce_email_heading_cwg_bis_instock/);
assert.match(emails, /You\\'re on the list for %s/);
assert.match(emails, /%s is available again at Pep Select/);

assert.match(html, /Stock watch is on/);
assert.match(html, /You can give the refresh button a rest/);
assert.match(html, /Good news\. It\\'s back\./);
assert.match(html, /available batch documentation before ordering/);
assert.match(html, /pepselect_child_email_company_footer_row_html/);
assert.match(html, /Have a question\? Reply to this email, and one of our team members will be in touch shortly\./);
assert.match(html, /max-width:680px/);
assert.match(html, /@media only screen and \(max-width:520px\)/);
assert.doesNotMatch(html, /limited stock|act quickly/i);

assert.match(plain, /STOCK NOTIFICATION CONFIRMED/);
assert.match(plain, /YOUR STOCK NOTIFICATION/);
assert.match(plain, /Review product details:/);
assert.match(plain, /View product:/);
assert.match(plain, /Have a question\? Reply to this email, and one of our team members will be in touch shortly\./);
assert.match(plain, /American-owned and operated company/);
assert.match(plain, /2090 Baker Rd, Ste 304 #A85/);
assert.match(plain, /1 \(833\) 737-7528/);
assert.doesNotMatch(plain, /limited stock|act quickly/i);

console.log('Back-in-stock email template checks passed.');

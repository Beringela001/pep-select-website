const assert = require('assert');
const fs = require('fs');
const path = require('path');

const dashboard = fs.readFileSync(
	path.join(__dirname, '..', 'pepselect-child', 'woocommerce', 'myaccount', 'dashboard.php'),
	'utf8'
);
const cashback = fs.readFileSync(
	path.join(__dirname, '..', 'pepselect-child', 'woocommerce', 'myaccount', 'cash-back.php'),
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

const infoIndex = dashboard.indexOf('id="pepselect-dash-info"');
const cashbackIndex = dashboard.indexOf('id="pepselect-dash-cashback"');
const referralIndex = dashboard.indexOf('id="pepselect-dash-referral"');
const ordersIndex = dashboard.indexOf('id="pepselect-dash-orders"');

assert.ok(infoIndex > -1, 'My information card missing');
assert.ok(cashbackIndex > infoIndex, 'Cash back card must follow My information');
assert.ok(referralIndex > cashbackIndex, 'Referral card must follow Cash back');
assert.ok(ordersIndex > referralIndex, 'Your orders must follow the referral card');
assert.ok(dashboard.includes('pepselect_child_referral_vanity_url( $pepselect_uid )'), 'Dashboard must use the existing PSRC referral URL helper');
assert.ok(dashboard.includes('id="pepselect-referral-link"'), 'Dashboard referral link field missing');
assert.ok(dashboard.includes('WELCOME10'), 'Dashboard welcome-code instruction missing');
assert.ok(dashboard.includes("pepselect_child_format_dollars( 15 )"), 'Dashboard referral bonus missing');
assert.ok(!cashback.includes('id="pepselect-referral-link"'), 'Referral link must not remain duplicated on the Cash back detail page');
assert.ok(!cashback.includes('pepselect_child_referral_vanity_url'), 'Cash back detail page must not regenerate the moved referral link');
assert.ok(accountCss.includes('.pepselect-card--referral'), 'Dashboard referral card styling missing');
assert.ok(/\.pepselect-dash\s*\{[^}]*gap:\s*20px;/s.test(accountCss), 'Dashboard vertical gap must remain 20px');
assert.ok(/\.pepselect-dash__grid\s*\{[^}]*grid-template-columns:\s*repeat\(2, minmax\(0, 1fr\)\);[^}]*gap:\s*20px;/s.test(accountCss), 'Information-card horizontal gap must remain 20px');
assert.ok(!dashboard.includes('pepselect-card--referral pepselect-cashback__referral pepselect-cashback__engine'), 'Dashboard referral card must not inherit the Cash back detail-page margin');
assert.ok(accountJs.includes('.pepselect-copyfield__copy'), 'Referral copy control wiring missing');

console.log('account referral placement safeguards verified');

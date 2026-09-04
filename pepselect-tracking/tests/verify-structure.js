'use strict';

const fs = require('fs');
const path = require('path');
const assert = require('assert');

const root = path.resolve(__dirname, '..');
const read = (file) => fs.readFileSync(path.join(root, file), 'utf8');
const php = [
	'pepselect-tracking.php',
	'includes/class-pepselect-tracking-attribution.php',
	'includes/class-pepselect-tracking-events.php',
	'includes/class-pepselect-tracking-delivery.php',
	'includes/class-pepselect-tracking-privacy.php',
	'includes/class-pepselect-tracking-health.php'
].map(read).join('\n');
const js = read('assets/js/tracking.js');

assert.match(php, /woocommerce_checkout_create_order/);
assert.match(php, /woocommerce_payment_complete/);
assert.match(php, /woocommerce_order_status_changed/);
assert.match(php, /'bacs' !== \$order->get_payment_method\(\)/);
assert.match(php, /_pepselect_ga4_purchase_sent/);
assert.match(php, /_pepselect_meta_purchase_sent/);
assert.match(php, /eventsToTrack=.*filter/);
assert.match(php, /declare_compatibility\( 'custom_order_tables'/);
assert.match(php, /'_pepselect_analytics_consent'.*\|\|.*'_pepselect_marketing_consent'/);
assert.doesNotMatch(php, /get_billing_(email|first_name|last_name|phone|address)/);
assert.doesNotMatch(php, /REMOTE_ADDR/);
assert.doesNotMatch(php, /'(gclid|fbclid|msclkid)'\s*=>/);
assert.match(js, /navigator\.globalPrivacyControl/);
assert.match(js, /navigator\.doNotTrack/);
assert.match(js, /pepselect:consent/);
assert.match(js, /fbq\('consent', 'revoke'\)/);
assert.doesNotMatch(js, /clarity\.ms|loadClarity|hotjar/i);
assert.doesNotMatch(js, /email|first_name|last_name|phone|address/i);

console.log('Pep Select tracking structural checks passed.');

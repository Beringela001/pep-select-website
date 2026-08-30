const assert = require('assert');
const fs = require('fs');
const path = require('path');

const handler = fs.readFileSync(
	path.join(__dirname, '..', 'pepselect-child', 'inc', 'contact-page.php'),
	'utf8'
);
const template = fs.readFileSync(
	path.join(__dirname, '..', 'pepselect-child', 'page-contact.php'),
	'utf8'
);
const timingScript = fs.readFileSync(
	path.join(__dirname, '..', 'pepselect-child', 'assets', 'js', 'contact.js'),
	'utf8'
);

assert.ok(
	template.includes('name="pepselect_contact_started_at"'),
	'Contact form must include the minimum-fill timestamp'
);
assert.ok(template.includes('name="pepselect_contact_started_at" value="0"'), 'Cached HTML must not contain a stale server timestamp');
assert.ok(timingScript.includes('Date.now()'), 'Browser must set a fresh form timestamp after cached HTML loads');
assert.ok(timingScript.includes('DOMContentLoaded'), 'Timing guard must wait until the cached form exists in the DOM');
assert.ok(timingScript.includes('dataset.pepInitialized'), 'Timing guard must expose a non-sensitive initialization marker');
assert.ok(handler.includes("( time() - $started_at ) < 3"), 'Handler must reject immediate submissions');
assert.ok(handler.includes("$_SERVER['REMOTE_ADDR']"), 'Rate limiting must use the server-observed address');
assert.ok(!handler.includes('HTTP_X_FORWARDED_FOR'), 'Rate limiting must not trust visitor-controlled forwarded headers');
assert.ok(handler.includes("hash_hmac( 'sha256', $remote_address"), 'Raw IP addresses must not be stored');
assert.ok(handler.includes('$attempt_count >= 5'), 'Handler must enforce the hourly submission ceiling');
assert.ok(handler.includes('HOUR_IN_SECONDS'), 'Rate-limit state must expire');
assert.ok(handler.includes('pepselect_contact_duplicate_'), 'Handler must suppress exact message replays');
assert.ok(handler.includes('15 * MINUTE_IN_SECONDS'), 'Duplicate suppression must expire after 15 minutes');
assert.ok(handler.includes('wp_mail( PEPSELECT_CONTACT_INBOX'), 'Accepted messages must still reach support');

console.log('Contact form abuse safeguards verified');

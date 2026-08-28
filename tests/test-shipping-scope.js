const assert = require('assert');
const fs = require('fs');
const path = require('path');

const faqContent = fs.readFileSync(
	path.join(__dirname, '..', 'pepselect-child', 'inc', 'faq-content.php'),
	'utf8'
);
const legalContent = fs.readFileSync(
	path.join(__dirname, '..', 'pepselect-child', 'inc', 'legal-content.php'),
	'utf8'
);

for (const [surface, source] of [['FAQ', faqContent], ['Refund & Shipping Policy', legalContent]]) {
	assert.ok(
		source.includes('all 50 U.S. states, Washington, D.C., and Puerto Rico'),
		`${surface} must state the eligible shipping area`
	);
	assert.ok(
		source.includes('Alaska and Puerto Rico orders ship by USPS only'),
		`${surface} must state the USPS-only destinations`
	);
	for (const excludedDestination of ['U.S. Virgin Islands', 'other U.S. territories', 'overseas military addresses']) {
		assert.ok(
			source.includes(excludedDestination),
			`${surface} must identify ${excludedDestination} as excluded`
		);
	}
}

assert.ok(!faqContent.includes('contiguous United States'), 'FAQ still contains the obsolete contiguous-only restriction');
assert.ok(!legalContent.includes('contiguous United States'), 'Policy still contains the obsolete contiguous-only restriction');
assert.ok(legalContent.includes("'last_updated' => 'August 27, 2026'"), 'Policy last-updated date was not refreshed');

console.log('Expanded U.S. shipping copy safeguards verified');

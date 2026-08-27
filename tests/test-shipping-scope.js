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
		source.includes('contiguous United States (the lower 48 states) and Washington, D.C.'),
		`${surface} must state the eligible shipping area`
	);
	for (const excludedDestination of ['Alaska', 'Hawaii', 'Puerto Rico', 'U.S. Virgin Islands', 'other U.S. territories', 'overseas military addresses']) {
		assert.ok(
			source.includes(excludedDestination),
			`${surface} must identify ${excludedDestination} as excluded`
		);
	}
}

assert.ok(!faqContent.includes('All 50 U.S. states'), 'FAQ still contains the obsolete 50-state promise');
assert.ok(!legalContent.includes('We currently ship within the United States only.'), 'Policy still contains the obsolete broad U.S. promise');
assert.ok(legalContent.includes("'last_updated' => 'August 27, 2026'"), 'Policy last-updated date was not refreshed');

console.log('Contiguous-U.S. shipping copy safeguards verified');

const assert = require('assert');
const fs = require('fs');
const path = require('path');

const legalContent = fs.readFileSync(
	path.join(__dirname, '..', 'pepselect-child', 'inc', 'legal-content.php'),
	'utf8'
);
const contactPage = fs.readFileSync(
	path.join(__dirname, '..', 'pepselect-child', 'page-contact.php'),
	'utf8'
);
const footer = fs.readFileSync(
	path.join(__dirname, '..', 'pepselect-child', 'template-parts', 'footer', 'site-footer.php'),
	'utf8'
);

assert.ok(legalContent.includes("'text'  => 'Data Sharing'"), 'Privacy Policy Data Sharing section missing');
assert.ok(legalContent.includes("'text'  => '12. Messaging Program Terms and Conditions'"), 'Terms & Conditions messaging section missing');
assert.ok(legalContent.includes("'text'  => 'Messaging Program Terms and Conditions'"), 'Privacy Policy messaging section missing');
assert.strictEqual(
	(legalContent.match(/Mobile opt-in and consent are never shared with anyone for any purpose\./g) || []).length,
	1,
	'Mobile opt-in sharing restriction must appear once in the Privacy Policy'
);
assert.strictEqual(
	(legalContent.match(/Messages will be sent from <a href="tel:\+18337377528">\(833\) 737-7528<\/a>\./g) || []).length,
	2,
	'SMS sending number must appear in both messaging-terms sections'
);
assert.strictEqual(
	(legalContent.match(/Carriers are not liable for delayed or undelivered messages\./g) || []).length,
	2,
	'Carrier disclaimer must appear in both messaging-terms sections'
);
assert.ok(
	legalContent.includes('By checking the policy agreement box at checkout, you acknowledge that you have read and agree to these Messaging Program Terms and Conditions.'),
	'Privacy Policy checkout acknowledgment is missing'
);
assert.ok(legalContent.includes("'text'  => '12. Messaging Program Terms and Conditions'"), 'Terms messaging section numbering is incorrect');
assert.ok(legalContent.includes("'text'  => '15. Contact'"), 'Terms sections after messaging clause were not renumbered');

for (const [surface, source] of [['Contact page', contactPage], ['footer', footer]]) {
	assert.ok(source.includes('href="tel:+18337377528"'), `${surface} tap-to-call link missing`);
	assert.ok(source.includes('1 (833) 737-7528'), `${surface} customer-service number missing`);
	assert.ok(!source.includes('1-833-PEP-SLCT'), `${surface} obsolete vanity number remains`);
}

console.log('SMS policy and phone-number safeguards verified');

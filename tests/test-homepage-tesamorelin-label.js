const assert = require('assert');
const crypto = require('crypto');
const fs = require('fs');
const path = require('path');

const root = path.join(__dirname, '..');
const imageDir = path.join(root, 'pepselect-child', 'assets', 'images', 'why-pep-select');
const template = fs.readFileSync(path.join(root, 'pepselect-child', 'template-parts', 'home', 'why-pep-select.php'), 'utf8');
const css = fs.readFileSync(path.join(root, 'pepselect-child', 'assets', 'css', 'homepage.css'), 'utf8');

function sha256(file) {
	return crypto.createHash('sha256').update(fs.readFileSync(file)).digest('hex').toUpperCase();
}

const base = path.join(imageDir, 'tesamorelin-10mg-vial-batch.webp');
const coa = path.join(imageDir, 'tesamorelin-10mg-coa-source.webp');
const overlay = path.join(imageDir, 'tesamorelin-10mg-left-label-new.webp');

assert.strictEqual(sha256(base), '9A21BB574A830F884FAEF1877C59BBF5643370DBF20275767AE100F6CC730903');
assert.strictEqual(sha256(coa), '41C065627EDC1315CB8D243CB26ACA707B9ED0FE67D76341B920502FC6841451');
assert.ok(fs.existsSync(overlay), 'new Tesamorelin label overlay is missing');
assert.ok(fs.statSync(overlay).size < 25 * 1024, 'new label overlay exceeds its 25 KB budget');

assert.ok(template.includes("'tesamorelin-10mg-vial-batch.webp'"));
assert.ok(template.includes("'tesamorelin-10mg-left-label-new.webp'"));
assert.ok(template.includes("'tesamorelin-10mg-coa-source.webp'"));
assert.ok(template.includes('class="pepselect-home__match-left-label"'));
assert.ok(template.includes('alt=""'));
assert.ok(template.includes('aria-hidden="true"'));

assert.ok(css.includes('.pepselect-home__match-left-label'));
assert.ok(css.includes('top: 42.8889%'));
assert.ok(css.includes('left: 21.5%'));
assert.ok(css.includes('width: 23.3333%'));
assert.ok(css.includes('height: 38.1111%'));

console.log('homepage Tesamorelin label overlay safeguards verified');

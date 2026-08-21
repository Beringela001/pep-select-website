const assert = require('assert');
const fs = require('fs');
const path = require('path');

const contentFile = fs.readFileSync(
	path.join(__dirname, '..', 'pepselect-child', 'inc', 'compound-content.php'),
	'utf8'
);

assert.ok(contentFile.includes("'cagrilintide' => array("), 'Cagrilintide library entry missing');
assert.ok(contentFile.includes('long-acting analogue of amylin'), 'Cagrilintide research description missing');
assert.ok(contentFile.includes('Studied for appetite and body-weight regulation through amylin signaling'), 'Cagrilintide amylin context missing');
assert.ok(contentFile.includes('Researched alongside semaglutide in combined appetite-pathway studies'), 'Cagrilintide combination context missing');
assert.ok(contentFile.includes('Investigated for how amylin receptors influence fullness and food-intake signals'), 'Cagrilintide receptor context missing');
assert.ok(contentFile.includes('DOI:10.1016/S0140-6736(21)01751-7'), 'Lau DOI missing');
assert.ok(contentFile.includes('PMID:34798060'), 'Lau PMID missing');
assert.ok(contentFile.includes('DOI:10.1016/S0140-6736(21)00845-X'), 'Enebo DOI missing');
assert.ok(contentFile.includes('PMID:33894838'), 'Enebo PMID missing');
assert.ok(contentFile.includes('DOI:10.1016/j.ebiom.2025.105836'), 'Carvas DOI missing');
assert.ok(contentFile.includes('PMID:40609154'), 'Carvas PMID missing');
assert.ok(contentFile.includes('PMCID:PMC12270663'), 'Carvas PMCID missing');

console.log('Cagrilintide compound content safeguards verified');

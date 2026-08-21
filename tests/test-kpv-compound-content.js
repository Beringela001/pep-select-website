const assert = require('assert');
const fs = require('fs');
const path = require('path');

const contentFile = fs.readFileSync(
	path.join(__dirname, '..', 'pepselect-child', 'inc', 'compound-content.php'),
	'utf8'
);

assert.ok(contentFile.includes("'kpv' => array("), 'KPV library entry missing');
assert.ok(contentFile.includes('preclinical research focused on the intestinal lining, immune cells, and airway tissue'), 'KPV research description missing');
assert.ok(contentFile.includes('Studied for intestinal inflammation and barrier-related research'), 'KPV intestinal research context missing');
assert.ok(contentFile.includes('Researched for immune signaling in the intestinal lining'), 'KPV immune research context missing');
assert.ok(contentFile.includes('Investigated for airway and epithelial inflammation'), 'KPV airway research context missing');
assert.ok(contentFile.includes('DOI:10.1053/j.gastro.2007.10.026'), 'Dalmasso DOI missing');
assert.ok(contentFile.includes('PMID:18061177'), 'Dalmasso PMID missing');
assert.ok(contentFile.includes('PMID:22837805'), 'Land PMID missing');
assert.ok(contentFile.includes('PMCID:PMC3403564'), 'Land PMCID missing');

console.log('KPV compound content safeguards verified');

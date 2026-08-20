const assert = require('assert');
const fs = require('fs');
const path = require('path');

const orderingFile = fs.readFileSync(
	path.join(__dirname, '..', 'pepselect-child', 'inc', 'product-ordering.php'),
	'utf8'
);

function assertInOrder(markers, message) {
	let previous = -1;

	markers.forEach((marker) => {
		const current = orderingFile.indexOf(marker);
		assert.ok(current > previous, `${message}: ${marker}`);
		previous = current;
	});
}

assertInOrder(
	[
		"100 => array( 'glp3r', 'retatrutide' )",
		"110 => array( 'glp2t', 'tirzepatide' )",
		"120 => array( 'glp1s', 'semaglutide' )",
		"200 => array( 'bpc157' )",
		"210 => array( 'tb500', 'thymosinbeta4' )",
		"220 => array( 'ghkcu' )",
		"300 => array( 'motsc' )",
		"310 => array( 'ss31', 'elamipretide' )",
		"320 => array( 'nad' )",
		"400 => array( 'tesamorelin', 'tesa' )",
		"410 => array( 'sermorelin' )",
		"420 => array( 'cjc1295', 'modifiedgrf129' )",
		"430 => array( 'ipamorelin' )",
		"500 => array( 'kpv' )",
		"510 => array( 'glutathione' )",
		"520 => array( 'pt141', 'bremelanotide' )",
	],
	'compound priority changed'
);

assert.ok(orderingFile.includes("if ( $product && $product->is_in_stock() )"));
assert.ok(orderingFile.includes("$status_band = pepselect_child_get_product_status_band( $product->get_id() )"));
assert.ok(orderingFile.includes("if ( null !== $status_band )"));
assertInOrder(
	[
		"if ( $a['availability'] !== $b['availability'] )",
		"if ( $a['compound'] !== $b['compound'] )",
		"if ( $a['order'] !== $b['order'] )",
	],
	'sort precedence changed'
);
assert.ok(orderingFile.includes("return 9999;"), 'unclassified fallback missing');
assert.ok(orderingFile.includes("0 === strpos( $normalized, $alias )"), 'safe prefix alias matching missing');

console.log('product ordering safeguards verified');

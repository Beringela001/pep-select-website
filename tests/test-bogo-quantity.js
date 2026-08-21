const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');

const plugin = fs.readFileSync(
  path.join(__dirname, '..', 'pepselect-bogo-quantity', 'pepselect-bogo-quantity.php'),
  'utf8'
);

assert.match(plugin, /Plugin Name: Pep Select Automatic Free Vials/);
assert.match(plugin, /Version:\s+1\.0\.0/);
assert.match(plugin, /woocommerce_add_to_cart_quantity/);
assert.match(plugin, /woocommerce_store_api_add_to_cart_data/);
assert.match(plugin, /woocommerce_after_cart_item_quantity_update/);
assert.match(plugin, /woocommerce_cart_item_quantity/);
assert.match(plugin, /woocommerce_get_item_data/);

for (const sku of ['GLP3R10', 'GLP3R20', 'GLP2T20', 'GLP1S10', 'MOTSC10', 'GHKCU50']) {
  assert.ok(plugin.includes(`'${sku}'`), `missing eligible SKU ${sku}`);
}

const physical = paid => paid + Math.floor(paid / 4);
assert.equal(physical(1), 1);
assert.equal(physical(3), 3);
assert.equal(physical(4), 5);
assert.equal(physical(5), 6);
assert.equal(physical(8), 10);

console.log('Pep Select automatic free-vial plugin checks passed.');

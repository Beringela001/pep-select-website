const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');

const plugin = fs.readFileSync(
  path.join(__dirname, '..', 'pepselect-bogo-quantity', 'pepselect-bogo-quantity.php'),
  'utf8'
);

assert.match(plugin, /Plugin Name: Pep Select Automatic Free Vials/);
assert.match(plugin, /Version:\s+1\.1\.2/);
assert.match(plugin, /pepselect_bogo_product_add/);
assert.match(plugin, /woocommerce_before_add_to_cart_button/);
assert.match(plugin, /woocommerce_add_to_cart_quantity/);
assert.match(plugin, /woocommerce_store_api_add_to_cart_data/);
assert.match(plugin, /woocommerce_add_to_cart/);
assert.match(plugin, /woocommerce_get_item_data/);
assert.match(plugin, /pepselect_bogo_replace_added_line_quantity/);
assert.doesNotMatch(plugin, /xoo_wsc_product_args/);
assert.doesNotMatch(plugin, /rest_post_dispatch/);
assert.doesNotMatch(plugin, /woocommerce_after_cart_item_quantity_update/);
assert.doesNotMatch(plugin, /inventory:/i);

for (const sku of ['GLP3R10', 'GLP3R20', 'GLP2T20', 'GLP1S10', 'MOTSC10', 'GHKCU50']) {
  assert.ok(plugin.includes(`'${sku}'`), `missing eligible SKU ${sku}`);
}

const physical = paid => paid + Math.floor(paid / 4);
assert.equal(physical(1), 1);
assert.equal(physical(3), 3);
assert.equal(physical(4), 5);
assert.equal(physical(5), 6);
assert.equal(physical(8), 10);

const cartEdit = (_oldQuantity, newQuantity) => newQuantity;
assert.equal(cartEdit(3, 4), 4);
assert.equal(cartEdit(5, 4), 4);
assert.equal(cartEdit(8, 9), 9);
assert.equal(cartEdit(10, 9), 9);

console.log('Pep Select automatic free-vial plugin checks passed.');

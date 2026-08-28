const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');

const plugin = fs.readFileSync(
  path.join(__dirname, '..', 'pepselect-bogo-quantity', 'pepselect-bogo-quantity.php'),
  'utf8'
);
const noticeCss = fs.readFileSync(
  path.join(__dirname, '..', 'pepselect-bogo-quantity', 'assets', 'bogo-cart-notice.css'),
  'utf8'
);

assert.match(plugin, /Plugin Name: Pep Select Automatic Free Vials/);
assert.match(plugin, /Version:\s+1\.3\.0/);
assert.match(plugin, /pepselect_bogo_product_add/);
assert.match(plugin, /woocommerce_before_add_to_cart_button/);
assert.match(plugin, /woocommerce_add_to_cart_quantity/);
assert.match(plugin, /woocommerce_store_api_add_to_cart_data/);
assert.match(plugin, /woocommerce_add_to_cart/);
assert.match(plugin, /woocommerce_get_item_data/);
assert.match(plugin, /pepselect_bogo_replace_added_line_quantity/);
assert.match(plugin, /pepselect_bogo_enqueue_cart_notice_styles/);
assert.match(plugin, /pepselect_bogo_notice_text/);
assert.match(plugin, /pepselect_bogo_regular_unit_price/);
assert.match(plugin, /woocommerce_cart_item_price/);
assert.match(plugin, /xoo_wsc_product_summary_col_start/);
assert.match(plugin, /pepselect_bogo_side_cart_notice/);
assert.match(plugin, /Add 5, one is on us\./);
assert.match(plugin, /%d free vial added/);
assert.match(plugin, /pepselect-bogo-cart-notice/);
assert.match(plugin, /'value'\s*=>\s*\$notice/);
assert.match(plugin, /'display'\s*=>\s*\$notice/);
assert.doesNotMatch(plugin, /xoo_wsc_product_args/);
assert.doesNotMatch(plugin, /rest_post_dispatch/);
assert.doesNotMatch(plugin, /woocommerce_after_cart_item_quantity_update/);
assert.doesNotMatch(plugin, /inventory:/i);

assert.match(noticeCss, /\.pepselect-bogo-cart-notice\s*\{\s*display:\s*none;/s);
assert.match(noticeCss, /body\.woocommerce-cart \.pepselect-bogo-cart-notice/);
assert.match(noticeCss, /wc-block-components-product-details__name/);
assert.match(noticeCss, /__name:has\(\+ \.wc-block-components-product-details__value/);
assert.match(noticeCss, /body\.woocommerce-cart dl\.variation dt:has/);
assert.match(noticeCss, /\.xoo-wsc-modal \.pepselect-bogo-cart-notice/);
assert.match(noticeCss, /\.pepselect-bogo-side-cart-notice/);
assert.match(noticeCss, /body\.woocommerce-checkout #order_review/);
assert.match(noticeCss, /body\.woocommerce-checkout \.wc-block-checkout/);
assert.match(noticeCss, /\.ywdpd-sale-badge/);
assert.match(noticeCss, /\.ywdpd_subtotal_row p/);
assert.match(noticeCss, /grid-template-areas/);
assert.match(noticeCss, /\.xoo-wsc-sm-info,/);

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

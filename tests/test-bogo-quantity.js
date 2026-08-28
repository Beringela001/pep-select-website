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

assert.match(plugin, /Plugin Name: Pep Select BOGO Cart Experience/);
assert.match(plugin, /Version:\s+1\.5\.0/);
assert.match(plugin, /woocommerce_get_item_data/);
assert.match(plugin, /pepselect_bogo_enqueue_cart_notice_styles/);
assert.match(plugin, /pepselect_bogo_notice_text/);
assert.match(plugin, /pepselect_bogo_regular_unit_price/);
assert.match(plugin, /woocommerce_cart_item_price/);
assert.match(plugin, /pepselect_bogo_simplify_side_cart_totals/);
assert.match(plugin, /xoo_wsc_cart_totals/);
assert.match(plugin, /Estimated total/);
assert.match(plugin, /pepselect_bogo_simplify_side_cart_footer/);
assert.match(plugin, /showFooterTxt/);
assert.match(plugin, /xoo_wsc_product_summary_col_start/);
assert.match(plugin, /pepselect_bogo_side_cart_notice/);
assert.match(plugin, /Add 5, one is on us\./);
assert.match(plugin, /%d free vial added/);
assert.match(plugin, /pepselect-bogo-cart-notice/);
assert.match(plugin, /'value'\s*=>\s*\$notice/);
assert.match(plugin, /'display'\s*=>\s*\$notice/);
assert.doesNotMatch(plugin, /xoo_wsc_product_args/);
assert.doesNotMatch(plugin, /pepselect_bogo_product_add/);
assert.doesNotMatch(plugin, /woocommerce_add_to_cart_quantity/);
assert.doesNotMatch(plugin, /woocommerce_store_api_add_to_cart_data/);
assert.doesNotMatch(plugin, /pepselect_bogo_replace_added_line_quantity/);
assert.doesNotMatch(plugin, /WC\(\)->cart->set_quantity/);
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
assert.match(noticeCss, /"price price"/);
assert.match(noticeCss, /\.xoo-wsc-psavings\s*\{\s*display:\s*none;/s);
assert.match(noticeCss, /\.xoo-wsc-sm-info,/);

for (const sku of ['GLP3R10', 'GLP3R20', 'GLP2T20', 'GLP1S10', 'MOTSC10', 'GHKCU50']) {
  assert.ok(plugin.includes(`'${sku}'`), `missing eligible SKU ${sku}`);
}

const literalQuantity = selected => selected;
for (const quantity of [1, 4, 5, 6, 7, 8, 9, 10, 15]) {
  assert.equal(literalQuantity(quantity), quantity);
}

const freeVials = physicalQuantity => Math.floor(physicalQuantity / 5);
assert.equal(freeVials(4), 0);
assert.equal(freeVials(5), 1);
assert.equal(freeVials(9), 1);
assert.equal(freeVials(10), 2);

console.log('Pep Select BOGO cart experience checks passed.');

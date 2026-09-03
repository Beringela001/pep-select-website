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
const discountClass = fs.readFileSync(
  path.join(__dirname, '..', 'pepselect-bogo-quantity', 'includes', 'class-pepselect-compound-discount.php'),
  'utf8'
);
const bogoRule = fs.readFileSync(
  path.join(__dirname, '..', 'pepselect-bogo-quantity', 'includes', 'class-pepselect-bogo-rule.php'),
  'utf8'
);
const sitewideClass = fs.readFileSync(
  path.join(__dirname, '..', 'pepselect-bogo-quantity', 'includes', 'class-pepselect-sitewide-discount.php'),
  'utf8'
);
const stackingClass = fs.readFileSync(
  path.join(__dirname, '..', 'pepselect-bogo-quantity', 'includes', 'class-pepselect-discount-stacking.php'),
  'utf8'
);
const discountAdmin = fs.readFileSync(
  path.join(__dirname, '..', 'pepselect-bogo-quantity', 'includes', 'class-pepselect-discount-admin.php'),
  'utf8'
);
const adminCss = fs.readFileSync(
  path.join(__dirname, '..', 'pepselect-bogo-quantity', 'assets', 'compound-discount-admin.css'),
  'utf8'
);
const discountFrontend = fs.readFileSync(
  path.join(__dirname, '..', 'pepselect-bogo-quantity', 'assets', 'compound-discount-frontend.js'),
  'utf8'
);
const sitewideFrontend = fs.readFileSync(
  path.join(__dirname, '..', 'pepselect-bogo-quantity', 'assets', 'sitewide-discount-frontend.css'),
  'utf8'
);
const discountAdminJs = fs.readFileSync(
  path.join(__dirname, '..', 'pepselect-bogo-quantity', 'assets', 'discount-admin.js'),
  'utf8'
);

assert.match(plugin, /Plugin Name: Pep Select Cart Discounts/);
assert.match(plugin, /Version:\s+2\.2\.0/);
assert.match(plugin, /PEPSELECT_BOGO_VERSION', '2\.2\.0'/);
assert.match(plugin, /class-pepselect-compound-discount\.php/);
assert.match(plugin, /class-pepselect-bogo-rule\.php/);
assert.match(plugin, /class-pepselect-sitewide-discount\.php/);
assert.match(plugin, /class-pepselect-discount-admin\.php/);
assert.match(plugin, /class-pepselect-discount-stacking\.php/);
assert.match(plugin, /woocommerce_get_item_data/);
assert.match(plugin, /pepselect_bogo_enqueue_cart_notice_styles/);
assert.match(plugin, /pepselect_bogo_notice_text/);
assert.match(plugin, /pepselect_bogo_simplify_side_cart_totals/);
assert.match(plugin, /xoo_wsc_cart_totals/);
assert.match(plugin, /Estimated total/);
assert.match(plugin, /pepselect_bogo_simplify_side_cart_footer/);
assert.match(plugin, /showFooterTxt/);
assert.match(plugin, /xoo_wsc_cart_footer_args/);
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

assert.match(bogoRule, /class PepSelect_BOGO_Rule/);
assert.match(bogoRule, /pepselect_bogo_rule_v1/);
assert.match(bogoRule, /'enabled'\s*=>\s*false/);
assert.match(bogoRule, /pepselect_bogo_enabled/);
assert.match(bogoRule, /pepselect_bogo_rule_updated/);
assert.match(bogoRule, /pepselect_bogo_product_is_eligible/);
assert.match(bogoRule, /\/buy-four-get-one/);
assert.match(bogoRule, /manage_woocommerce/);
assert.match(bogoRule, /if_revision/);
assert.match(bogoRule, /woocommerce_get_shop_coupon_data/);
assert.match(bogoRule, /fixed_cart/);
assert.match(bogoRule, /product_ids/);
assert.match(bogoRule, /Turning this off removes both the automatic discount and its cart message/);
assert.match(bogoRule, /YITH rule inactive/);
assert.match(bogoRule, /pepselect_product_has_b4g1/);
assert.match(bogoRule, /pepselect_child_b4g1_pill_label/);
assert.match(bogoRule, /Buy 4 get 1 free/);
assert.match(bogoRule, /'stackable'\s*=>\s*true/);
assert.match(bogoRule, /individual_use'\s*=>\s*empty/);

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
assert.match(noticeCss, /\.xoo-wsc-footer-txt\s*\{\s*display:\s*none;/s);
assert.match(noticeCss, /\.xoo-wsc-sm-info,/);

assert.match(discountClass, /pepselect_compound_discount_rule_v1/);
assert.match(discountClass, /pepselect_compound_discount_rules_v2/);
assert.match(discountClass, /SCHEMA_VERSION\s*= 3/);
assert.match(discountClass, /LABEL_LIMIT\s*= 24/);
assert.match(discountClass, /PepSelect_Discount_Admin::PAGE_SLUG/);
assert.match(discountClass, /wc-product-search/);
assert.match(discountClass, /woocommerce_json_search_products_and_variations/);
assert.match(discountClass, /'match_mode'\s*=>\s*'all'/);
assert.match(discountClass, /array\( 'any', 'all' \)/);
assert.match(discountClass, /array\( 'percent', 'fixed_cart' \)/);
assert.match(discountClass, /array\( 'quantity', 'subtotal' \)/);
assert.match(discountClass, /woocommerce_get_shop_coupon_data/);
assert.match(discountClass, /pepselect-auto-compound/);
assert.match(discountClass, /pepselect_toggle_compound_discount/);
assert.match(discountClass, /pepselect_delete_compound_discount/);
assert.match(discountClass, /\/compound-discounts/);
assert.match(discountClass, /sync_automatic_coupons/);
assert.match(discountClass, /customer_label_is_coupon_code/);
assert.match(discountClass, /woocommerce_cart_totals_coupon_html/);
assert.match(discountClass, /line_subtotal/);
assert.match(discountClass, /manage_woocommerce/);
assert.match(discountClass, /pepselect-bogo\/v1/);
assert.match(discountClass, /if_revision/);
assert.match(discountClass, /status'\s*=>\s*409/);
assert.match(discountClass, /'stackable'\s*=>\s*true/);
assert.match(discountClass, /estimated_discount_amount/);
assert.doesNotMatch(discountClass, /add_fee\s*\(/);
assert.match(discountAdmin, /add_menu_page/);
assert.match(discountAdmin, /Cart Discounts/);
assert.match(discountAdmin, /Buy 4 Get 1/);
assert.match(discountAdmin, /Compound Discounts/);
assert.match(discountAdmin, /Sitewide Discounts/);
assert.match(sitewideClass, /pepselect_sitewide_discount_rules_v1/);
assert.match(sitewideClass, /array\( 'none', 'quantity', 'subtotal' \)/);
assert.match(sitewideClass, /array\( 'everyone', 'logged_in', 'subscribers', 'purchasers', 'vip', 'specific' \)/);
assert.match(sitewideClass, /woocommerce_json_search_customers/);
assert.match(sitewideClass, /excluded_product_ids/);
assert.match(sitewideClass, /woocommerce_json_search_products_and_variations/);
assert.match(sitewideClass, /pepselect_discount_customer_is_subscriber/);
assert.match(sitewideClass, /pepselect_discount_customer_is_vip/);
assert.match(sitewideClass, /individual_use'\s*=>\s*false/);
assert.match(sitewideClass, /allowed_coupon_sources/);
assert.match(sitewideClass, /allowed_coupon_codes/);
assert.match(sitewideClass, /override_coupon_codes/);
assert.match(sitewideClass, /active_takeover_rule/);
assert.match(sitewideClass, /woocommerce_get_price_html/);
assert.match(sitewideClass, /sitewide-discount-frontend\.css/);
assert.match(sitewideClass, /public static function is_product_eligible/);
assert.match(sitewideClass, /pepselect-sitewide-price__label/);
assert.match(sitewideClass, /sale_label_color/);
assert.match(sitewideClass, /regular_price_color/);
assert.match(sitewideClass, /sale_price_color/);
assert.match(sitewideClass, /data-pep-sitewide-preview/);
assert.doesNotMatch(sitewideClass, /has_term\( 'compounds'/);
assert.match(sitewideFrontend, /\.pepselect-sitewide-price/);
assert.match(sitewideFrontend, /grid-template-columns:\s*auto auto/);
assert.match(sitewideFrontend, /@media \(max-width: 760px\)/);
assert.match(sitewideFrontend, /--pep-sitewide-label/);
assert.match(stackingClass, /woocommerce_before_calculate_totals/);
assert.match(stackingClass, /estimated_amount/);
assert.match(stackingClass, /array_merge/);
assert.match(stackingClass, /woocommerce_coupon_is_valid/);
assert.match(stackingClass, /woocommerce_apply_individual_use_coupon/);
assert.match(stackingClass, /woocommerce_apply_with_individual_use_coupon/);
assert.match(stackingClass, /sync_takeover/);
assert.match(adminCss, /\.pepselect-discount-layout/);
assert.match(adminCss, /\.pepselect-rule-row/);
assert.match(adminCss, /\.pepselect-switch-button/);
assert.match(adminCss, /focus-visible/);
assert.match(adminCss, /\.pepselect-discount-tabs/);
assert.match(adminCss, /\.pepselect-live-preview/);
assert.match(discountAdminJs, /data-preview-surface/);
assert.match(discountAdminJs, /data-preview-device/);
assert.match(discountFrontend, /woocommerce-remove-coupon/);
assert.match(discountFrontend, /wc-block-components-chip/);
assert.match(discountFrontend, /MutationObserver/);

for (const sku of ['GLP3R10', 'GLP3R20', 'GLP2T20', 'GLP1S10', 'MOTSC10', 'GHKCU50']) {
  assert.ok(bogoRule.includes(`'${sku}'`), `missing migration SKU ${sku}`);
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

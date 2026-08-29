# WEB-M15 — Configurable compound discounts 1.6.0

## Outcome

Version 1.6.0 adds one manager-controlled automatic compound promotion to the
Pep Select BOGO Cart Experience plugin. It is disabled by default and does not
change YITH rule 1209 or the existing Buy 4 get 1 pricing behavior.

## Admin experience

WooCommerce > Compound Discounts provides:

- an enabled/disabled switch;
- one or more WooCommerce products or variations;
- an `all selected compounds` or `any selected compound` requirement;
- a percentage or fixed-dollar order discount;
- an eligible-item quantity or eligible-item pre-discount subtotal minimum;
- a customer-facing Cart and Checkout label.

Only the selected compounds count toward the minimum. Once qualified, the
promotion is applied to the order through WooCommerce's coupon engine. The
automatic coupon is non-individual-use, so existing WooCommerce coupon
compatibility rules remain authoritative.

## Ops contract

Authenticated WooCommerce managers can read or update the same rule at:

`/wp-json/pepselect-bogo/v1/compound-discount`

The response carries `schema_version: 1`, a SHA-256 revision, the complete rule,
and allowed enum values. Updates accept `if_revision` for conflict protection.
The endpoint requires `manage_woocommerce`; it does not expose promotion data or
write access publicly.

## Safety and acceptance criteria

- The new rule starts disabled after installation.
- Enabling requires at least one compound, a positive discount, and a positive minimum.
- Percentage discounts are capped at 100%.
- Removing an eligible item or falling below the minimum removes the automatic coupon.
- Product quantity and subtotal qualification use WooCommerce cart source data.
- YITH remains the sole authority for Buy 4 get 1 pricing.
- The plugin stores no customer, order, payment, or credential data.

## Rollback

Reinstall `dist/pepselect-bogo-quantity-1.5.1.zip` or restore the pre-deployment
Live backup, then clear Kinsta caches. Disabling the rule is the immediate
no-package rollback for the new promotion behavior.

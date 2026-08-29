# Compound Discounts 1.6.0 — Live Release

Date: 2026-08-28  
Environment: Pep Select Live  
Branch: `codex/seo-m4-content`

## Outcome

- Added WooCommerce > Compound Discounts to the active Pep Select BOGO Cart Experience plugin.
- Added an enabled/disabled switch, multi-compound selector, any/all matching,
  percentage or fixed-dollar discounts, eligible-quantity or eligible-subtotal
  minimums, and a customer-facing discount label.
- Added the authenticated `/wp-json/pepselect-bogo/v1/compound-discount` Ops
  contract with schema versioning, rule revisions, and conflict protection.
- Left the new promotion disabled with no compounds selected.
- Preserved YITH rule 1209 as the pricing authority for Buy 4 get 1.

## Deployment

- Direct Live deployment was explicitly authorized by Paulo.
- Removed only the verified oldest manual backup at the bottom of the full list:
  `Before shipping restrictions 0.1.0 live deployment - 2026-08-27`.
- Rollback backup: `Before compound discounts 1.6.0 live - 2026-08-28`.
- Immediate package rollback: `dist/pepselect-bogo-quantity-1.5.1.zip`.
- Deployed package: `dist/pepselect-bogo-quantity-1.6.0.zip`.
- SHA-256: `39C71E514B1E9E6C2C69FC9C4F8D022CE0DA19075082B39F60D75F554FFF99DE`.
- Source commit: `1f64ed8 Add configurable compound discounts`.
- All Kinsta caches cleared after installation.

## Verification

- Plugin list reports Pep Select BOGO Cart Experience active at version 1.6.0.
- Compound Discounts screen loads for a WooCommerce manager.
- Enabled switch is off; default amount is 10%, default eligible quantity is 2,
  and the default label is `Compound promotion`.
- Public Cart retained the existing `1 free vial added` state and showed no
  `Compound promotion` discount while the rule was disabled.
- Public Cart retained Proceed to Checkout and loaded without browser errors.
- Checkout retained contact, shipping, billing, payment, compliance, discount,
  rewards, product, tax, shipping, and total surfaces without browser errors.
- The unauthenticated Ops endpoint returned HTTP 401 `rest_forbidden`, confirming
  the route is registered and not publicly readable or writable.
- Local JavaScript regression, PHP behavior, PHP syntax, and whitespace checks passed.
- No customer, order, payment, product, stock, shipping, tax, or coupon record was created or changed.

## Rollback

Disable the rule for an immediate behavioral stop. To roll back the plugin,
reinstall `dist/pepselect-bogo-quantity-1.5.1.zip` or restore the named Kinsta
backup, then clear all Kinsta caches.

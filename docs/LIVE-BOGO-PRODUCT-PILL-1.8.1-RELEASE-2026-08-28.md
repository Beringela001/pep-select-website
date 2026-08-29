# Live BOGO Product Pill 1.8.1 Release — 2026-08-28

## Outcome

Pep Select BOGO Cart Experience 1.8.1 is live. Product pages now use the plugin's enabled state and selected-product list to decide whether to show the `Buy 4 get 1 free` pill. YITH Dynamic Pricing remains inactive and no longer controls product-page promotion visibility.

## Root cause

The child theme's existing product-page pill renderer waited for a YITH promotion note before showing the BOGO pill. Once YITH stopped owning the rule, eligible products could still receive the plugin-controlled cart discount without receiving the product-page message.

## Change

- Added a plugin filter that supplies product-page eligibility from the versioned BOGO rule.
- Added a plugin-controlled product-page label: `Buy 4 get 1 free`.
- Preserved the existing cashback pill, product price, add-to-cart control, and current-batch card.
- Kept non-selected products free of BOGO messaging.

## Live verification

- Plugin active at version 1.8.1.
- Rule enabled with product IDs 862, 864, and 865: the GLP-3 R 10 mg, 20 mg, and 30 mg variants.
- Eligible GLP-3 R product page shows both `Buy 4 get 1 free` and the cashback pill on desktop and at a 390 × 844 mobile viewport.
- Non-selected GHK-CU product page does not show the BOGO pill and still shows the cashback pill.
- Adding five GLP-3 R 10 mg vials produced `1 free vial added` and a `-$79.99` automatic discount.
- The automatic discount has no customer removal control.
- YITH rule `Buy 4 get 1 free` remains inactive.

## Validation

- `tests/test-bogo-quantity.js`
- `tests/test-performance-assets.js`
- `tests/test-bogo-rule.php`
- `tests/test-compound-discount.php`
- PHP syntax checks for the plugin entry point and BOGO rule class
- `git diff --check`

## Backup and rollback

- Pre-deployment backup: `Before BOGO product pill 1.8.1 live - 2026-08-29`
- Package: `dist/pepselect-bogo-quantity-1.8.1.zip`
- SHA-256: `6A52279F6D3CBFA2621F5BAB1D128DDC4D84D800A8D3491333177B8F6AF5040A`
- Implementation commit: `957478c`
- Roll back by restoring the pre-deployment backup or reinstalling the 1.8.0 plugin package.

To make room for the required pre-deployment backup, the confirmed oldest manual backup, `Before compound discounts 1.6.0 live - 2026-08-28`, was permanently deleted and cannot be recovered.

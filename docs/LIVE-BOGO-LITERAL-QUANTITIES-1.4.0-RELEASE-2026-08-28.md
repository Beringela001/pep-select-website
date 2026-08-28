# BOGO Literal Quantities 1.4.0 — Live Release

Date: 2026-08-28  
Environment: Pep Select Live  
Branch: `codex/seo-m4-content`

## Root cause

The plugin still treated customer-entered quantities as paid-vial counts. Its product-form and Store API hooks expanded quantities before YITH priced the cart, and a later replacement hook could overwrite the existing line. Values from 5 through 9 could therefore be reinterpreted instead of saved literally.

## Fix

- Removed the product-form quantity marker and expansion filter.
- Removed the Store API quantity expansion filter.
- Removed the cart-line replacement action and every `set_quantity()` call.
- Kept YITH rule 1209 as the sole free-vial pricing authority.
- Kept the cart notice, regular unit price, savings, totals, and Side Cart layout from 1.3.0.

Quantity inputs now represent physical vials on every surface. Five stays five, eight stays eight, nine stays nine, and ten stays ten.

## Deployment

- Rollback backup: `Before BOGO cart notice 1.2.0 live - 2026-08-28`
- Immediate package rollback: `dist/pepselect-bogo-quantity-1.3.0.zip`
- Deployed package: `dist/pepselect-bogo-quantity-1.4.0.zip`
- SHA-256: `217DCDCB42B7DC1669E23C77B8B4E27F425BEC233E0CAE81A6EB79F32CA9ECB1`
- Live plugin status: active, version 1.4.0
- Kinsta caches: all cleared after installation

## Verification

- Automated regression test passed and rejects all removed mutation hooks.
- PHP syntax check passed.
- Live Cart saved quantities 5, 6, 7, 8, and 9 literally.
- Quantity 9 remained 9 after a full reload.
- Quantity 5 showed `1 free vial added`.
- Quantity 10 remained 10 and showed `2 free vials added`.
- Live Side Cart reflected quantity 10, two free vials, regular unit price, savings, and final total.
- The test cart was restored to its starting quantity of 10.

## Rollback

Reinstall `dist/pepselect-bogo-quantity-1.3.0.zip` for an immediate plugin rollback, or restore the named Kinsta backup, then clear all Kinsta caches.

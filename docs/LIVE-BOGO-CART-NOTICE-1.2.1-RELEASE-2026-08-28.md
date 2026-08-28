# BOGO Cart Notice 1.2.1 — Live Release

Date: 2026-08-28  
Environment: Pep Select Live  
Branch: `codex/seo-m4-content`

## Outcome

- Eligible Buy 4 Get 1 lines show `Add 5, one is on us.` in the Xootix Side Cart and WooCommerce Cart.
- Earned quantities continue to use the existing free-vial confirmation copy.
- The checkout order summary remains unchanged; Side Cart retains the notice wherever the drawer is available.
- Version 1.2.1 renders directly through Xootix's product-summary hook because live Side Cart product metadata is disabled.

## Deployment

- Rollback backup: `Before BOGO cart notice 1.2.0 live - 2026-08-28`
- Package: `dist/pepselect-bogo-quantity-1.2.1.zip`
- SHA-256: `EF44E2D9213F2AAF25976232DC80202D0B4627CE81F935ABF98E6D7D1F55740A`
- Live plugin status: active, version 1.2.1
- Kinsta caches: all cleared after installation

## Verification

- Automated behavior test passed.
- PHP syntax check passed.
- Live Side Cart: eligible GLP-3 R line displayed `Add 5, one is on us.`
- Live Cart: eligible GLP-3 R line displayed the notice beneath its product details.
- Live checkout order summary: no promotion notice rendered.

## Rollback

Restore the named Kinsta backup or reinstall `dist/pepselect-bogo-quantity-1.2.0.zip`, then clear all Kinsta caches.

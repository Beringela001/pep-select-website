# BOGO Cart Notice 1.2.3 — Live Patch

Date: 2026-08-28  
Environment: Pep Select Live  
Branch: `codex/seo-m4-content`

## Outcome

- Full Cart now shows only the `Add 5, one is on us.` pill.
- WooCommerce's redundant `Buy 4 get 1 free:` metadata label is visually removed.
- Xootix Side Cart presentation is unchanged.
- Pricing, quantities, eligibility, checkout, and order behavior are unchanged.

Version 1.2.2 was superseded during the same live smoke test after the live Cart block's inline metadata wrapper differed from the common list wrapper. Version 1.2.3 targets the verified live structure.

## Deployment

- Rollback backup: `Before BOGO cart notice 1.2.0 live - 2026-08-28`
- Immediate package rollback: `dist/pepselect-bogo-quantity-1.2.1.zip`
- Deployed package: `dist/pepselect-bogo-quantity-1.2.3.zip`
- SHA-256: `E554759939C8C108FC1A7FC8A7F393BFAA60A2344080B661583395556F06B7C0`
- Live plugin status: active, version 1.2.3
- Kinsta caches: all cleared after installation

## Verification

- Automated behavior test passed.
- PHP syntax check passed.
- Live Cart: generated label computed to `display: none`; pill computed to `inline-flex`.
- Live Cart accessibility snapshot contained the pill without the generated label.
- Live Side Cart retained the same copy, style, and placement.

## Rollback

Reinstall `dist/pepselect-bogo-quantity-1.2.1.zip` for an immediate plugin rollback, or restore the named Kinsta backup, then clear all Kinsta caches.

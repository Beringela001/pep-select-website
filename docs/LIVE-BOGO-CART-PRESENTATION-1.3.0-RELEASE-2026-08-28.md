# BOGO Cart Presentation 1.3.0 — Live Release

Date: 2026-08-28  
Environment: Pep Select Live  
Branch: `codex/seo-m4-content`

## Decision

The free vial remains a line discount rather than a lower price for every vial. Eligible earned-state lines therefore show:

- the regular per-vial price;
- one savings amount and the final line total; and
- one pill: `1 free vial added`, pluralized from the physical cart quantity.

## Outcome

- Removed the duplicate YITH `Buy 4 get 1 free` labels from Cart and Side Cart earned states.
- Replaced `free vial included` with `free vial added`.
- Preserved the regular per-vial price instead of YITH's averaged discounted unit price.
- Preserved the savings amount and original/final line totals.
- Rebuilt eligible Side Cart rows as a stable two-column grid.
- Left pricing rules, eligibility, quantities, checkout, orders, and inventory unchanged.

## Deployment

- Rollback backup: `Before BOGO cart notice 1.2.0 live - 2026-08-28`
- Immediate package rollback: `dist/pepselect-bogo-quantity-1.2.3.zip`
- Deployed package: `dist/pepselect-bogo-quantity-1.3.0.zip`
- SHA-256: `5A5CD1765208671C6870B2E553C565F0B2A51D96BCBCA3BE4BB9624D5C165819`
- Live plugin status: active, version 1.3.0
- Kinsta caches: all cleared after installation

## Verification

- Automated behavior test passed.
- PHP syntax check passed.
- Live Side Cart at quantity 5 showed `$79.99`, `Save $79.99`, original/final line totals, and `1 free vial added`.
- Side Cart title, tools, price, savings, pill, quantity, and total occupied aligned grid rows.
- Live Cart showed the regular unit price, pill, final line total, and savings without the YITH promotion badge.
- Checkout order summary contained no BOGO notice or label.

## Rollback

Reinstall `dist/pepselect-bogo-quantity-1.2.3.zip` for an immediate plugin rollback, or restore the named Kinsta backup, then clear all Kinsta caches.

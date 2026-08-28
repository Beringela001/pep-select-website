# BOGO Side Cart Summary 1.5.1 — Live Release

Date: 2026-08-28  
Environment: Pep Select Live  
Branch: `codex/seo-m4-content`

## Outcome

- Removed the green per-product savings badge from the Side Cart.
- Removed the duplicate green order-savings row.
- Removed Side Cart subtotal, shipping availability/address output, and the shipping/tax disclaimer.
- Replaced the footer summary with one neutral `Estimated total` row.
- Kept the compact dynamic `1 free vial added` / `2 free vials added` pill.
- Kept the regular Cart savings amount and product pricing presentation unchanged.
- Preserved YITH as the sole BOGO pricing authority; this release changes presentation only.

## Deployment

- Rollback backup: `Before BOGO cart notice 1.2.0 live - 2026-08-28`
- Immediate package rollback: `dist/pepselect-bogo-quantity-1.4.0.zip`
- Deployed package: `dist/pepselect-bogo-quantity-1.5.1.zip`
- SHA-256: `55B789CB239BCABDF89F9D258FFB3E9AA77FEE5A4B3F204841203A8D6DE9D772`
- Live plugin status: active, version 1.5.1
- Kinsta caches: all cleared after installation

## Verification

- Automated BOGO regression test passed.
- PHP syntax check passed.
- Live Side Cart shows only `Estimated total $639.92` for the test cart.
- Live Side Cart contains no visible product-savings badge, order-savings row, subtotal, shipping row, or footer disclaimer.
- Quantity 10 remains literal and shows `2 free vials added`.
- Responsive Side Cart checks passed at 320, 360, 375, 390, 430, 480, and 768 px.
- No tested viewport had document, drawer, product, or control overflow; the total, upsell, and footer buttons remained inside the viewport.
- The browser viewport was restored after testing.

## Rollback

Reinstall `dist/pepselect-bogo-quantity-1.4.0.zip` for an immediate plugin rollback, or restore the named Kinsta backup, then clear all Kinsta caches.

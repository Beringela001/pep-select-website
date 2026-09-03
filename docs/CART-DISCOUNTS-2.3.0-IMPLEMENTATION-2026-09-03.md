# Cart Discounts 2.3.0 implementation — 2026-09-03

Pep Select Cart Discounts 2.3.0 adds sitewide coupon priority for the Labor Day promotion and future events.

## Delivered

- Exclusive sitewide takeover blocks BOGO, compound discounts, and unapproved WooCommerce coupons.
- Allowed exceptions may be selected by native source, exact code, or a trailing-star prefix.
- Exact replacement coupons remove the sitewide promotion and every other discount.
- Replacement coupons suppress the obsolete sitewide catalog-price treatment after application.
- Buy 4 Get 1 product/cart messaging is hidden while an exclusive sitewide policy is active.
- The sitewide Ops contract advances to schema version 2.

## Verification

- Focused PHP syntax and behavior suites.
- Static storefront/admin contract suite.
- ZIP integrity and SHA-256.
- Staging deployed and reported plugin version `2.3.0`; WordPress and Kinsta caches were cleared.
- Staging admin showed the new priority, allowed-source, allowed-code, and replacement-code controls. The existing `LABORDAY20` rule remained active at 20%, for everyone, with its prior stackable setting unchanged.
- Staging product and cart smoke tests showed the 20% product-price treatment and a matching `LABORDAY20` cart discount (`$13.60` on a `$67.98` item subtotal).
- Live deployed and reported plugin version `2.3.0`; WordPress and Kinsta caches were cleared.
- Live admin showed the new controls while preserving `LABORDAY20` as inactive, exclusive, and configured with one excluded product. No campaign was activated or edited during deployment.
- Live GHK-CU, cart, and checkout loaded without PHP errors. With the sitewide rule inactive, regular prices and totals remained unchanged.

## Backups and release record

- Staging backup: `Before Cart Discounts 2.3.0 staging - 2026-09-03` (created Sep 3, 2026 at 11:56 AM).
- Staging capacity was full. The verified oldest bottom backup, `Before Cart Discounts 2.0.0 staging - 2026-08-30`, was removed under owner authorization.
- Live backup: `Before Cart Discounts 2.3.0 live - 2026-09-03` (created Sep 3, 2026 at 12:07 PM).
- Live capacity was full. The verified oldest bottom backup, `Before Trustpilot selector block test and timing 0.5.0 - 2026-09-01`, was removed under owner authorization.

## Package

- `dist/pepselect-bogo-quantity-2.3.0.zip` (not committed)
- SHA-256: `050F4F645DB06B3FF3BE1E0FBDCC0897ADC7709FDE4C7587DE7718616CE73C23`

## Rollback

Restore the pre-deployment Kinsta backup or reinstall the tested 2.2.0 package, then clear caches and recheck cart, side cart, checkout, BOGO messaging, sitewide prices, exclusions, and ordinary coupon behavior.

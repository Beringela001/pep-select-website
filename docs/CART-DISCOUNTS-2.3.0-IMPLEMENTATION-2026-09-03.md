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
- Staging and Live smoke evidence will be recorded after deployment.

## Package

- `dist/pepselect-bogo-quantity-2.3.0.zip` (not committed)

## Rollback

Restore the pre-deployment Kinsta backup or reinstall the tested 2.2.0 package, then clear caches and recheck cart, side cart, checkout, BOGO messaging, sitewide prices, exclusions, and ordinary coupon behavior.

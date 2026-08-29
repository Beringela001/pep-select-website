# Multi-Rule Compound Discounts 1.7.1 — Live Release

Date: 2026-08-28  
Environment: Pep Select Live  
Branch: `codex/seo-m4-content`

## Outcome

- Replaced the single compound-discount setting with a saved rule list.
- Each rule can be activated, deactivated, edited, or deleted independently.
- Multiple active qualifying rules can apply to the same cart.
- Customer labels are required, unique, limited to 24 characters, and displayed as the Cart and Checkout discount name.
- Automatic discount pills are non-removable; ordinary coupons and cash back remain removable.
- Existing 1.6.0 rules and carts migrate without exposing the retired internal coupon code or an invalid-coupon notice.
- The authenticated Ops contract now exposes the version 2 rule collection at `/wp-json/pepselect-bogo/v1/compound-discounts`.

## Package

- Artifact: `dist/pepselect-bogo-quantity-1.7.1.zip` (not committed)
- SHA-256: `DFFEA841B9DE122A5B3F5901E54476B31C11769F6D64FD264789BA9CC66258B1`
- Feature commit: `6393fe4 Support multiple compound discounts`
- Migration hotfix commit: `a39c8dd Silence legacy compound coupon migration`

## Backup and deployment

- Verified the full Kinsta Live manual-backup list and its timestamp order.
- Deleted only the oldest entry at the bottom: `Before BOGO cart notice 1.2.0 live - 2026-08-28`.
- Created and confirmed: `Before compound discounts 1.7.0 live - 2026-08-28`.
- Replaced Live plugin 1.6.0 with 1.7.0, then applied the verified 1.7.1 migration hotfix.
- Cleared all Kinsta WordPress caches after the final package installation.

## Live verification

- WordPress reports Pep Select BOGO Cart Experience active at version 1.7.1.
- WooCommerce > Compound Discounts shows separate Add discount and Saved discounts surfaces.
- The migrated `GHK+NAD DUO` rule remains active at 20%, minimum one eligible item, with two selected compounds.
- The editor states the 24-character customer-label limit.
- Cart shows `ghk+nad duo`, the correct `$44.39` discount, and no automatic-coupon remove control.
- Checkout shows `GHK+NAD DUO`, `20% off`, the correct `$44.39` discount, and no remove control.
- The retired `pepselect-auto-compound` code and its one-time invalid-coupon notice are absent after the 1.7.1 migration.
- Checkout products, shipping, taxes, total, payment instructions, acknowledgments, and Place your order remained intact.
- No order or payment was submitted.
- Local JavaScript regression, JavaScript syntax, PHP behavior, PHP syntax, and whitespace checks passed.

## Rollback

Restore the named Kinsta backup above or reinstall `dist/pepselect-bogo-quantity-1.6.0.zip`, then clear all Kinsta caches.

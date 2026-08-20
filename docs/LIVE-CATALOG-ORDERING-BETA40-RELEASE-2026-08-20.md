# Live Catalog Ordering Release — 0.25.0-beta.40

## Authorization

Paulo authorized deployment to the Pep Select Live environment on 2026-08-20.

## Backup

- Kinsta manual-backup capacity was full at 5/5.
- Verified the backup list was newest-to-oldest before removal.
- Deleted only the oldest backup at the bottom: `Before Claude SEO Milestone 4 batch 1 live deployment - 2026-08-19`, created Aug 19, 2026 at 10:50 AM.
- Created and verified the replacement rollback backup: `Before catalog ordering 0.25.0-beta.40 live deployment - 2026-08-20`, created Aug 20, 2026 at 11:52 AM.

## Package and installation

- Package: `dist/pepselect-child-0.25.0-beta.40.zip`
- Size: 2,378,026 bytes
- SHA-256: `8258948213D6BCEAEABFC5ADBC8EC91F29D854BE6E752215B625BBA0BD6BCE21`
- Installed theme changed from `0.25.0-beta.39` to `0.25.0-beta.40`.
- WordPress reported `Theme updated successfully`.
- Appearance > Themes confirmed `Pep Select` remained active at version `0.25.0-beta.40`.
- Kinsta `Clear all caches` completed after installation.

## Live verification

Shop status and classification order observed after deployment:

1. In stock: GLP-3 R 10MG, GLP-3 R 30MG, TB-500, GHK-CU, NAD+, Tesamorelin, PT-141.
2. Restocking soon: BPC-157, MOTS-C, SS-31.
3. Out of stock: GLP-3 R 20MG, GLP-2T, GLP-1 S, Glutathione.

This confirms status takes precedence dynamically, while the classification priority applies within each status group.

Read-only smoke checks passed for:

- Home
- Shop
- GLP-3 R 10MG product page
- Cart
- Checkout
- My Account

No order, payment, product, customer, or cart mutation was performed during deployment verification.

## Rollback

Restore the Kinsta manual backup `Before catalog ordering 0.25.0-beta.40 live deployment - 2026-08-20`, then clear all Kinsta caches and repeat the Shop and commerce smoke checks.


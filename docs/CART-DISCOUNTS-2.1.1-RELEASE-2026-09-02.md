# Cart Discounts 2.1.1 release — 2026-09-02

## Outcome

Pep Select Cart Discounts 2.1.1 is active on staging and live. Each sitewide rule now has a searchable catalog exclusion list. Excluded products keep their regular storefront price, receive no sitewide coupon discount, and do not count toward quantity or subtotal minimums.

The active `LABORDAY20` rule remains a 20% exclusive discount for everyone with no minimum. `Bacteriostatic Water 30mL (BACW30)` is excluded on staging and live.

## Artifact

- Package: `dist/pepselect-bogo-quantity-2.1.1.zip` (not committed)
- SHA-256: `C18FC3330D0A6D36010195987469FBB4D578273E25A1213F2415789999C6ACBC`
- Archive structure: one top-level `pepselect-bogo-quantity/` directory with portable forward-slash paths

## Verification

- Focused JavaScript safeguards passed.
- Focused PHP sitewide-discount behavior checks passed.
- Staging loaded 2.1.1 without a fatal error and exposed the searchable exclusion field.
- Staging Bacteriostatic Water retained its regular `$19.99` product-page price.
- Staging mixed cart: `$313.96` subtotal, `$58.79` LABORDAY20 discount. The result equals 20% of eligible merchandise after excluding the `$19.99` water item.
- Live Bacteriostatic Water retained its regular `$19.99` product-page price with no sitewide price wrapper.
- Live GLP-3 RT retained the approved `$79.99` / `20% off` / `$63.99` presentation.
- Live mixed cart: `$99.98` subtotal, `$16.00` LABORDAY20 discount. Only the `$79.99` GLP product was discounted; the `$19.99` water item was excluded.
- Live mobile check at 390 × 844 confirmed the same prices with no horizontal overflow.
- Staging and live smoke-test carts were cleaned after verification.

## Backup and rollback

Before the 2.1.0/2.1.1 live rollout, the oldest manual backup, `Before Pep Select Trustpilot review email 0.1.0 - 2026-09-01`, was permanently deleted only after its bottom-list position and timestamp were verified and Paulo approved deletion.

Replacement manual backup: `Before Cart Discounts 2.1.0 live - 2026-09-02`, created September 2, 2026 at 10:02 AM. Restore that backup for a full rollback, or reinstall `dist/pepselect-bogo-quantity-2.1.0.zip` for the immediate plugin rollback, then clear all Kinsta caches.

## Ops contract

The authenticated `/wp-json/pepselect-bogo/v1/sitewide-discounts` collection continues to use schema version 1. Every returned rule now includes `excluded_product_ids`; Ops can read, add, replace, or remove those IDs through the existing revision-protected collection update. Existing rules normalize to an empty exclusion list without migration work.

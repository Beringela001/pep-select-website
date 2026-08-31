# Live Hospira water order-card release — 2026-08-31

## Release

- Plugin: Pep Select Order Experience `0.4.1`
- Package: `dist/pepselect-order-experience-0.4.1.zip`
- SHA-256: `EAE92827F6C41C926A2049C036F770DF9464DCA2F86109A9C502E2E2AFDDDE91`

## Behavior

- Bacteriostatic Water 30mL now uses a dedicated product-details treatment inside private customer order pages.
- The card identifies the photographed product as Hospira Bacteriostatic Water, USP and shows its 30 mL volume.
- The accessory card does not render peptide purity, test date, laboratory, test status, missing-report, or unavailable-COA language.
- The card links to the WooCommerce product page instead of a COA report.
- Normal compound batch cards and COA links remain unchanged.

## Verification

- WordPress accepted, installed, and executed the same `0.4.1` package on Staging and Live without a PHP error.
- Staging and Live diagnostics reported plugin `0.4.1`, the feature enabled, WooCommerce available, the permanent order page published, and the access-record table ready.
- The private order-page preview rendered on both environments.
- Desktop and 390 px mobile checks found no horizontal overflow; mobile cards and actions remained visible.
- The approved standalone Hospira card visual was checked at desktop and mobile widths.
- A local PHP CLI was unavailable, so the repository PHP contract scripts could not run locally; successful WordPress loading and diagnostics provided the deployment execution gate.

## Backup and rollback

- Removed only the verified oldest Live manual backup at the bottom of the full list: `Before standardized reply support beta84 and cart recovery 0.4.9 - 2026-08-30`.
- Created and confirmed: `Before Hospira water card Order Experience 0.4.1 live - 2026-08-31`.
- Roll back by restoring that backup or reinstalling `dist/pepselect-order-experience-0.4.0.zip`, then clear all Kinsta caches.


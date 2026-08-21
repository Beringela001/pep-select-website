# Live Cagrilintide Content Release — 0.25.0-beta.44

**Deployed:** 2026-08-21  
**Environment:** Live  
**Product:** Cagrilintide 10 mg (`CAG10`, compound code `CG`, WooCommerce product 1555)  
**Public URL:** https://pepselect.com/product/cag10/

## Delivered

- Added the reviewed plain-English Cagrilintide description and three separately cited research-context bullets.
- Saved the long and short descriptions through PepSelect Business Control and confirmed the published WooCommerce product.
- Preserved the existing price, SKU, stock state, notifier, commerce logic, and research-use blocks.
- Left CAS and formula placeholders for batch-specific COA confirmation.

## Release controls

- Removed only the verified oldest manual backup: `Before Ops restocking notice 0.25.0-beta.38 live deployment - 2026-08-19`.
- Created and confirmed: `Before Cagrilintide product content 0.25.0-beta.44 live deployment - 2026-08-21`.
- Deployed `dist/pepselect-child-0.25.0-beta.44.zip`.
- SHA-256: `D327911DF2A52D4E18D43A500E7484E01BFC5B5DE6D540ED44072F3242A82CBB`.
- Cleared all Kinsta WordPress caches.

## Verification

- All four JavaScript safeguards passed, including the Cagrilintide citation test.
- Active Live child-theme version confirmed as `0.25.0-beta.44`.
- Desktop and 390 px mobile checks confirmed the description, three bullets, source control, and no horizontal overflow.
- All three displayed citations were expanded and verified on Live.
- KPV, Tesamorelin, Shop, Cart, Checkout, and My Account loaded successfully.
- PHP CLI lint was unavailable because PHP is not installed locally; the changed array follows the existing validated content-library structure and rendered successfully on Live.
- The existing Elementor `elementorFrontendConfig is not defined` console error remains present and was not introduced by this release.

## Rollback

Restore the named Kinsta backup above or reinstall the prior `0.25.0-beta.43` child-theme package.

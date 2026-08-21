# Live KPV Product Content Release — 0.25.0-beta.43

## Authorization

Paulo authorized the complete KPV product-page deployment to Live on 2026-08-21.

## Backup

- Live manual-backup capacity was full at 5/5.
- Verified the list was newest-to-oldest and deleted only the oldest backup at the bottom: `Before Claude SEO Milestone 4 combined batches 3-4 live deployment - 2026-08-19`, created Aug 19, 2026 at 2:48 PM.
- Created and verified: `Before KPV product content 0.25.0-beta.41 live deployment - 2026-08-21`, created Aug 21, 2026 at 1:14 AM. This backup is the rollback point to the pre-KPV theme state.

## Final package

- Package: `dist/pepselect-child-0.25.0-beta.43.zip`
- Size: 2,378,780 bytes
- SHA-256: `7FBE3366B621D00B1930EBB2B16DCF35D748CC20E43EB4A219A3E8338E82249A`
- WordPress reported `Theme updated successfully`.
- Appearance > Themes confirmed Pep Select active at version `0.25.0-beta.43`.
- Kinsta confirmed all caches cleared.

The intermediate beta.41 wording was replaced during the same deployment verification cycle. Beta.43 is the final active Live package.

## Live verification

- KPV renders the same structured Description, Research context, numbered source disclosure, Intended use, and related-product layout as the other library-backed products.
- The three plain-English bullets map directly to three displayed preclinical references: Dalmasso et al. (2008), Xiao et al. (2017), and Land (2012).
- KPV remains out of stock and correctly shows the notification form. No COA summary or testing history is manufactured without a released matching batch.
- Tesamorelin retained its current-batch card, three-part product content, sources, and Independent Testing History.
- Home, Shop, Cart, Checkout, and My Account remained reachable during read-only smoke checks.
- Mobile verification at 390 px showed no horizontal overflow and preserved the KPV description, bullets, source control, and intended-use block.

## Known pre-existing item

The existing Elementor console error (`elementorFrontendConfig is not defined`) remains present and was not introduced or changed by this release.

## Rollback

Restore `Before KPV product content 0.25.0-beta.41 live deployment - 2026-08-21`, clear all Kinsta caches, and verify KPV, Tesamorelin, Shop, Cart, and Checkout.

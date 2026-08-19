# Live Release — SEO Milestone 4, Combined Batches 3–4

**Deployment date:** August 19, 2026  
**Environment:** Live — `https://pepselect.com`  
**Outcome:** Deployed and verified

## Restore point

- Deleted only the verified oldest manual Live backup at the bottom of the list: `Before Claude SEO remediation milestones 1-2 live deployment - 2026-08-18` (August 18, 2026, 4:18 PM).
- Created the new pre-deployment Live backup: `Before Claude SEO Milestone 4 combined batches 3-4 live deployment - 2026-08-19` (August 19, 2026, 2:48 PM).

## Packages deployed

| Package | Version | Size | SHA-256 |
|---|---:|---:|---|
| `pepselect-child-0.25.0-beta.36.zip` | `0.25.0-beta.36` | 2,362,123 bytes | `D1FA2ED30D78617863CF391442B5E7C9E3A18CCAE377C8D01D2E455B4F035A9A` |
| `pepselect-coa-archive-0.7.5.zip` | `0.7.5` | 376,419 bytes | `703E82588C8AEFD9C83F1F8FB631FDD695D5A0A81F81C1D743B99C4A093BED4D` |

The child theme was updated from `0.25.0-beta.34` to `0.25.0-beta.36`. The COA plugin was updated from `0.7.3` to `0.7.5`. The updated child theme was activated after installation. All Kinsta caches were cleared successfully.

## Live verification

- **NAD+:** one compact `CURRENT BATCH` card appears directly beneath the purchase controls. It shows 99.87% purity, batch `ND50026205JP`, July 30, 2026, Freedom Diagnostics Testing, and the correct batch-report link. The compact card does not say `QC Passed` or `Fully Vetted`. The full testing-history section remains farther down the page.
- **Retatrutide 10 mg:** one compact current-batch card shows 99.63% purity and batch `RT2026205JP`. Its report link resolves to `/testing/retatrutide-10mg/rt2026205jp/`, whose title and content identify the same product and batch.
- **Retatrutide 20 mg:** no compact card or full product-page testing carousel is shown because the only record is a failed/not-released batch.
- **Mobile:** the NAD+ purchase area and compact batch card were visually verified at 390 × 844 pixels. The card fits the product panel without horizontal clipping.
- **Desktop:** the NAD+ card was visually verified between Add to Cart and the dilution notice.

Visual evidence:

- `dist/seo-m4-batch34-live-nad-compact-desktop.png`
- `dist/seo-m4-batch34-live-nad-compact-mobile.png`

The screenshots are local release evidence and remain excluded from Git with the rest of `dist/`.

## Behavior preserved

- The compact card reads from the COA plugin's current batch data; it is not hard-coded into product descriptions.
- Current, incoming, and previous batch logic remains owned by the COA plugin and the full testing-history component.
- Failed/not-released batches remain excluded from product shopping pages.
- Product descriptions, the documentation guide, FAQs, products, customers, orders, checkout, payments, shipping, rewards, VerifyPass, and stored COA records were not changed by this deployment.

## Known pre-existing item

The previously observed Elementor console warning (`elementorFrontendConfig is not defined`) was not introduced or changed by this release.

## Rollback

Restore the manual Live backup named `Before Claude SEO Milestone 4 combined batches 3-4 live deployment - 2026-08-19` if rollback is required.

# Staging SEO Milestone 4 Combined Batch 3+4 Release — 2026-08-19

## Outcome

The approved combined Batch 3+4 implementation is deployed to Pep Select Staging only. Product descriptions remain simple and unchanged. Eligible product pages now show one compact batch-documentation card directly after the purchase or back-in-stock action and before the existing dilution notice. The card and the full testing-history section read the same COA plugin relationship and public record model, so a newly published current record updates both automatically.

Batch 2's approved guide already covers Batch 3's candidate questions with Pep Select-specific evidence. No duplicate documentation hub, redundant FAQ group, hidden SEO accordion, or guide link on product pages was added.

## Recovery point

- Kinsta Staging backup: `Before Claude SEO Milestone 4 combined batches 3-4 - 2026-08-19`
- Created August 19, 2026 at 2:28 PM America/New_York.
- Manual capacity was full. After verifying the bottom entry and timestamp, the removed oldest Staging backup was `Before Claude SEO remediation Milestone 3 - 2026-08-18`, created August 18, 2026 at 9:56 PM.
- Live was not changed.

## Packages

- Child theme: `dist/pepselect-child-0.25.0-beta.36.zip`
- Theme size: 2,362,123 bytes
- Theme SHA-256: `D1FA2ED30D78617863CF391442B5E7C9E3A18CCAE377C8D01D2E455B4F035A9A`
- COA Archive: `dist/pepselect-coa-archive-0.7.5.zip`
- COA size: 376,419 bytes
- COA SHA-256: `703E82588C8AEFD9C83F1F8FB631FDD695D5A0A81F81C1D743B99C4A093BED4D`
- Both ZIPs contain one portable package root.

## Runtime and visual verification

- NAD+ shows current batch `ND50026205JP`, 99.87% purity, July 30, 2026, and Freedom Diagnostics Testing directly below Add to cart.
- The NAD+ compact card and full history link to the same exact report.
- Retatrutide 10 mg shows batch `RT2026205JP` and links to `/testing/retatrutide-10mg/rt2026205jp/`; the linked report opened with the matching batch.
- Retatrutide 20 mg has only a not-released record and therefore shows neither a compact sale-area card nor an eligible testing-history carousel.
- Desktop and 390-pixel mobile layouts render without horizontal overflow.
- Purchase controls, out-of-stock notification controls, product descriptions, dilution notice, full testing history, prices, products, orders, checkout, payments, shipping, rewards, and COA records remain unchanged.
- Staging caches were cleared after installation.

## Staging correction during verification

Theme beta.35 exposed the new summary inside the existing promotion-note output buffer, which distorted the purchase area. It was rejected during Staging visual verification and never reached Live. Beta.36 moves the complete summary and dilution components after that buffer closes while preserving their intended order. Desktop and mobile screenshots confirm the corrected layout.

COA Archive 0.7.5 incorporates Paulo's visual review: the purchase-area card is shorter and no longer prints `QC Passed` or `Fully Vetted`. It retains `Current Batch`, purity, batch, tested date, laboratory, and the exact report link. Detailed status wording remains available in the complete history section below.

## Evidence

- Desktop screenshot: `dist/seo-m4-batch34-staging-nad-compact-desktop.png`
- Mobile screenshot: `dist/seo-m4-batch34-staging-nad-compact-mobile.png`
- Review URL: `https://stg-pepselect-staging.kinsta.cloud/product/nad/`
- Corrected-strength example: `https://stg-pepselect-staging.kinsta.cloud/product/glp3-r10/`
- Failed-only example: `https://stg-pepselect-staging.kinsta.cloud/product/glp3-r20/`

## Limitations

- PHP CLI is unavailable locally. WordPress accepted and executed both packages, and the affected Staging routes provided the runtime check.
- The pre-existing Elementor console warning `elementorFrontendConfig is not defined` remains and is not part of this batch.
- No ranking, indexing, traffic, or conversion improvement is claimed before post-release measurement.

## Rollback

Restore the named Kinsta Staging backup, clear Kinsta caches, and repeat the product, COA, Shop, Cart, Checkout, and account checks.

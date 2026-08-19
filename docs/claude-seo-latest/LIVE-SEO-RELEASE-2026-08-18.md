# Live Claude SEO Remediation Release — 2026-08-18

## Outcome

Claude SEO remediation Milestone 1 and the implemented portion of Milestone 2 were promoted from staging to Live with Paulo's explicit approval. The deployment preserves the exact email behavior that was active on Live before the release.

## Recovery point

- Kinsta environment: Live
- Manual backup: `Before Claude SEO remediation milestones 1-2 live deployment - 2026-08-18`
- Created: August 18, 2026 at 4:18 PM America/New_York
- To make room, only the verified bottom/oldest manual backup was deleted: `Before account-created email 0.25.0-beta.5 live deployment - 2026-08-18`, created August 18, 2026 at 11:30 AM.

## Live packages

| Package | Live version | SHA-256 | Purpose |
|---|---:|---|---|
| `dist/pepselect-child-0.25.0-beta.21.zip` | `0.25.0-beta.21` | `0A5ABCBDFAAFE82AE13EC1DE5C593649D1A72B3BEA2E1DA9E6AE5E926E53B61E` | Live-safe Milestones 1–2 theme. It preserves the exact Live `0.25.0-beta.16` email files while adding the staging-verified trust, responsive WebP hero, evidence-image, and render-path work from beta.20. |
| `C:/Users/paulo/Documents/Pep Select COA Page/dist/pepselect-coa-archive-0.7.1.zip` | `0.7.1` | `D458B1997D43BC959559EB17F9EBEDED2DB491EC137117CD6A9110F71544BD6F` | Exact published-product links from Testing archive cards and compound-history heroes. |
| `dist/ps-access-gate-2.1.2.zip` | `2.1.2` | `3DEAE92CECD05972EDD551B5790C2EE7C6EE70776051983A51C703047C4E587E` | Already active on Live before this release; retained without reinstalling. |

## Verification

- Active Live versions verified in WordPress: child theme `0.25.0-beta.21`, COA Archive `0.7.1`, and PS Access Gate `2.1.2`.
- All Kinsta caches were cleared; WordPress confirmed that all caches were cleared.
- Home, Shop, BPC-157, Testing, Cart, My Account, the canonical Terms page, a representative compound-history page, and the canonical NAD batch route return `200`.
- `/terms-of-service/` returns one `301` to `/terms-conditions/`; the destination returns `200`.
- The four affected citation pages return `200`, contain the corrected DOI text, and contain no `[VERIFY DOI]` placeholder. `CONT-06` remains open because DOI/PMID strings are still plain text rather than links.
- Shop exposes 14 unique product destinations.
- Testing exposes eight archive-to-product links; the GHK-CU compound-history page exposes its exact product link.
- BPC-157 remains out of stock and its notification form remains present.
- The existing authenticated cart line and checkout order/payment shell remain present. No order or payment action was submitted.
- The printed NAD QR legacy route still returns `301` to `/testing/nad-500-mg/nd50026205jp/`, which returns `200`.
- The homepage emits all seven responsive WebP hero candidates. Every candidate from 320 through 2048 pixels returns `200 image/webp`.
- Confirmed-unused marquee, media, block, and Jetpack form head assets are absent from the four audited Live templates.
- At a 390 × 844 mobile viewport, Home, Shop, Product, Testing, Cart, My Account, and Checkout all remain within the viewport without horizontal overflow.

## Remaining limitations

- Google PageSpeed API quota remained exhausted during the release window. No improved PageSpeed or Core Web Vitals score is claimed. Homepage, Shop, Product, and Testing must be rerun after quota reset.
- CrUX remains unavailable until Google has enough real-user field data.
- A complete human assistive-technology walkthrough of the research gate remains outstanding; automated dialog, focus, isolation, and keyboard checks passed on staging.
- The longstanding Elementor front-end console error `elementorFrontendConfig is not defined` remains observable. Pre-deployment and post-deployment HTML both load Elementor's front-end script without that configuration object, so it is tracked as pre-existing rather than attributed to this release. The tested storefront flows remained functional.
- No Git commit was created. The working theme source contains separate later email development, while this release was deliberately built from the exact Live email baseline to avoid changing email behavior.

## Rollback

1. Restore the named Kinsta Live backup above for full rollback.
2. For package-only rollback, reinstall child theme `0.25.0-beta.16` and COA Archive `0.7.0`.
3. Clear WordPress/Kinsta caches.
4. Repeat the route, Cart, Checkout-entry, account, Testing, compound-history, and NAD QR checks.

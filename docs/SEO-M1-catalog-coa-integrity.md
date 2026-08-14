# SEO Milestone 1 — Catalog, COA, and URL Integrity

Status: staging verified with one asset blocker  
Checkpoint date: 2026-08-14  
Live deployment: not authorized; live unchanged

## Completed on staging

- Created Kinsta backup `Before Milestone 1 catalog COA SEO integrity - 2026-08-14` after deleting only the oldest backup at the bottom of the full list.
- Corrected GLP-2T product tag from 20mg to 30mg. SKU remains `GLP2T30`.
- Corrected Glutathione product tag from 10mg to 600mg. SKU remains `GLUTA600`.
- Verified all 15 WooCommerce products against catalog names, SKUs, tags, images, image alt text, prices/stock source, and public product routes.
- Verified OPS identifies GLP-2T as 30mg and Glutathione as 600mg; neither has a tested lot. No COA record was invented or connected.
- Verified published failed COA batches remain visible in the public archive as Not Released and remain excluded from Current status and product carousels.
- Installed and activated Pep Select COA Archive 0.6.4 on staging.
- Migrated the verified Retatrutide 10mg compound from `/testing/961/` to `/testing/retatrutide-10mg/`.
- Added exact 301 redirects for the old Retatrutide compound and batch URLs. The existing exact NAD500 printed-QR redirect remains intact.
- Cleared all staging caches after deployment.

## Verification

- All 15 product URLs returned 200 with Product and Offer schema, no fatal errors, and no remaining `synthetic` wording.
- GLP-2T now exposes the 30mg tag; Glutathione now exposes the 600mg tag.
- The testing sitemap contains 18 public routes; all returned 200 with factual meta descriptions, WebPage/CollectionPage schema, breadcrumb schema, and no fatal errors.
- The sitemap now lists the descriptive Retatrutide 10mg URLs, not `/testing/961/`.
- `/testing/961/` returns 301 to `/testing/retatrutide-10mg/`.
- `/testing/961/rt2026205jp/` returns 301 to `/testing/retatrutide-10mg/rt2026205jp/`.
- `/testing/nad-500-mg/progress-1269/` still returns 301 to `/testing/nad-500-mg/nd50026205jp/`.
- Staging omits canonicals because the environment is intentionally noindex. The live read-only baseline confirmed canonical product URLs and product meta descriptions are present.
- Targeted COA redirect tests passed. Five unrelated legacy static suites still fail on pre-existing old-version/copy assertions; no new failure was introduced by 0.6.4.

## Release artifact

- Package: `dist/pepselect-coa-archive-0.6.4.zip`
- SHA-256: `4A4F24A834F30010123A7F8B1CD5168C2327FCF389DC1FC0428B7A7F2E5F0D44`
- Archive validation: one top-level `pepselect-coa-archive/` folder, forward-slash paths, and one activation file.
- COA source commit: `e609d9a` on branch `codex/m1-catalog-seo`.

## Remaining blocker

The GLP-2T product record is correctly named/tagged as 30mg, but its current product image visibly says 20mg and the media filename is `TZ20F`. No authentic 30mg source image was found in WordPress Media, the website repository, Documents, or Downloads. Do not relabel or fabricate this packaging image. Replace it only after Paulo provides the correct GLP-2T 30mg product photo.

## Preserved boundaries

- No landing-page copy was changed.
- No WooCommerce product ID, SKU, price, inventory, customer, order, checkout, payment, shipping, rewards, VerifyPass, or OPS business logic was changed.
- No COA batch identity, result, status, or product relationship was changed.
- Live was audited read-only and was not deployed to or modified.

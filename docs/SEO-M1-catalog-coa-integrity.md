# SEO Milestone 1 — Catalog, COA, and URL Integrity

Status: final — verified, documented, and backed up  
Checkpoint date: 2026-08-14  
Live deployment: GLP-2T catalog correction published by Paulo's explicit request; COA plugin not deployed live

## Completed on staging

- Created Kinsta backup `Before Milestone 1 catalog COA SEO integrity - 2026-08-14` after deleting only the oldest backup at the bottom of the full list.
- Created the final live recovery checkpoint `SEO Milestone 1 final - 2026-08-14` after all catalog and metadata corrections. Live manual-backup capacity is now 5/5.
- Paulo confirmed GLP-2T is 20mg. Corrected product ID 543 across OPS, staging, and live to SKU `GLP2T20`, slug `glp2-t20`, and tag `20mg`.
- Corrected Glutathione product tag from 10mg to 600mg. SKU remains `GLUTA600`.
- Verified all 16 WooCommerce product routes against catalog names, SKUs, tags, images, image alt text, prices/stock source, and public product routes.
- Verified OPS identifies GLP-2T as 20mg and Glutathione as 600mg; neither has a tested lot. No COA record was invented or connected.
- Verified published failed COA batches remain visible in the public archive as Not Released and remain excluded from Current status and product carousels.
- Installed and activated Pep Select COA Archive 0.6.4 on staging.
- Migrated the verified Retatrutide 10mg compound from `/testing/961/` to `/testing/retatrutide-10mg/`.
- Added exact 301 redirects for the old Retatrutide compound and batch URLs. The existing exact NAD500 printed-QR redirect remains intact.
- Cleared all staging caches after deployment.

## Verification

- All 16 product URLs returned 200 with Product and Offer schema, no fatal errors, and no remaining `synthetic` wording.
- GLP-2T now exposes the 20mg tag, SKU `GLP2T20`, and existing `TZ20F` image with alt text `Pep Select GLP-2T 20 mg vial`; Glutathione exposes the 600mg tag.
- GLP-2T's Yoast title and meta description now say 20mg on staging and live; the public live response contains no remaining `GLP-2T 30mg` metadata.
- On live and staging, `/product/glp2-t20/` returns 200 and the previous `/product/glp2-t30/` route returns 301 to the corrected URL.
- COA Product Matching shows GLP-2T as `GLP2T20`, product ID 543, 20 mg, and Not Included on live and staging.
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

## GLP-2T correction note

The earlier 30mg instruction was corrected by Paulo: GLP-2T is 20mg. The existing `TZ20F` image already displays the correct 20mg vial and was retained. Product ID 543 remains unchanged so connected WooCommerce, OPS, and COA references keep their stable identity. OPS Save and push targets live WooCommerce; staging therefore received and passed a separate WordPress update.

## Preserved boundaries

- No landing-page copy was changed.
- No WooCommerce product ID, price, inventory, customer, order, checkout, payment, shipping, rewards, VerifyPass, or OPS business logic was changed. Only the incorrect GLP-2T SKU/strength/slug/tag metadata was corrected.
- No COA batch identity, result, status, or product relationship was changed.
- COA Archive 0.6.4 remains staging-only; this correction did not deploy that plugin to live.

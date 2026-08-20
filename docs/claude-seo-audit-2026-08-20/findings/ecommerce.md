# E-commerce SEO Findings — Verification Audit (2026-08-20)

**Scope:** ECOM-01 through ECOM-09, DFS-07. Verification pass against the 2026-08-18 ledger and the 2026-08-18/19 release notes.
**Method:** Direct GET requests (no cart/checkout mutation) against Live `https://pepselect.com`, 20 August 2026. All 15 product URLs + `/shop/` returned HTTP 200. Raw pre-render HTML was inspected for JSON-LD, stock class, COA batch-card markup, and internal links — this is what WooCommerce/the COA plugin server-render before any client-side JS, so it also approximates what Googlebot's initial HTML fetch sees.
**Data source label:** On-page analysis (static, server-rendered HTML). No DataForSEO Merchant API calls were made — none were needed for this scope, so no cost-guardrail check was triggered.

## Per-product table (all 15 catalog product URLs)

| Product (slug) | Stock status | COA archive coverage | Current-batch card on PDP | Review/aggregateRating schema |
|---|---|---|---|---|
| glp1-s10 | Out of Stock | None — no `/testing/<compound>/` page exists | No | Absent (correct) |
| nad | In Stock | `/testing/nad-500-mg/` | Yes — batch `ND50026205JP`, links to `.../nad-500-mg/nd50026205jp/` | Absent (correct) |
| glp3-r20 (Retatrutide 20mg) | Out of Stock | `/testing/retatrutide-20mg/` exists | No — only record is a failed/not-released batch, correctly suppressed | Absent (correct) |
| tesa-10 | In Stock | `/testing/tesamorelin-10-mg/` | Yes — links to `.../tesamorelin-10-mg/pstes1071926gx/` | Absent (correct) |
| pt-141 | In Stock | `/testing/pt-141-10-mg/` | Yes — links to `.../pt-141-10-mg/pspt14162926jp/` | Absent (correct) |
| glp3-r30 (Retatrutide 30mg) | In Stock | `/testing/retatrutide-30mg/` | Yes — links to `.../retatrutide-30mg/nd_r30_060326/` | Absent (correct) |
| glutathione | Out of Stock | None | No | Absent (correct) |
| glp2-t20 (GLP-2T) | Out of Stock | None | No | Absent (correct) |
| bpc157-10 | Out of Stock | None | No | Absent (correct) |
| motsc-10 | Out of Stock | None | No | Absent (correct) |
| tb500-10 | In Stock | `/testing/tb-500-10-mg/` | Yes — links to `.../tb-500-10-mg/tb10-6926/` | Absent (correct) |
| ss-31 | Out of Stock | None | No | Absent (correct) |
| ghk-cu | In Stock | `/testing/ghk-cu-50-mg/` | Yes — links to `.../ghk-cu-50-mg/psgkcu5071926gx/` | Absent (correct) |
| bacteriostatic-water-30ml | In Stock | None (also absent from `/shop/` catalog listing — confirms MAP-01's "upsell-only, not in Shop" note) | No | Absent (correct) |
| glp3-r10 (Retatrutide 10mg) | In Stock | `/testing/retatrutide-10mg/` | Yes — links to `.../retatrutide-10mg/rt2026205jp/` | Absent (correct) |

**Totals:** 8 In Stock / 7 Out of Stock. 8 of 15 products have a matching COA compound-archive page (7 render a live current-batch card; Retatrutide 20mg's archive page exists but correctly shows no card because its only batch failed/was not released). 7 of 15 products have zero COA archive coverage. Zero review/aggregateRating schema anywhere in the catalog (confirmed correct — no fabricated ratings).

**Products with no COA archive coverage at all (no `/testing/<compound>/` page exists):** glp1-s10, glutathione, glp2-t20, bpc157-10, motsc-10, ss-31, bacteriostatic-water-30ml (7 products). Note: the original ECOM-02 finding says "6 of 15"; current evidence measures 7 of 15 with zero coverage. The likely explanation is bacteriostatic water is a diluent/accessory (not a peptide compound and not merchandised in `/shop/`), so the original count may have excluded it from the applicable-for-COA set. The gap itself — including headline compounds BPC-157 and Glutathione — is unchanged.

## Other checks

- **ECOM-03 (GLP-2T):** Product JSON-LD description reads "Tirzepatide is a dual-agonist research peptide designed to engage both GIP and GLP-1 receptors..." — Pep Select-specific, not generic supplier boilerplate. Page carries 3 unique DOI-formatted citations (`10.1016/j.ijbiomac.2025.146141`, `10.1073/pnas.2116506119`, `10.1172/jci.insight.140532`) under a "Research context" heading. Matches the Batch 1 release claim.
- **ECOM-04 (COA back-links):** `/testing/` hub renders 8 product back-links (ghk-cu, glp3-r10, glp3-r20, glp3-r30, nad, pt-141, tb500-10, tesa-10). Sampled compound-history pages (`nad-500-mg`, `retatrutide-10mg`, `ghk-cu-50-mg`) each render exactly one back-link to their correct matching product, and all three resolved 200 (not 404, not mismatched).
- **ECOM-05 (out-of-stock, no substitutes):** Checked all 7 Out-of-Stock product pages (bpc157-10, glp1-s10, glp2-t20, glp3-r20, glutathione, motsc-10, ss-31) for any substitute/related/"similar products" surfacing — none found on any page. Matches prior "Blocked / input needed" state exactly, including the same 7-of-15 count.
- **ECOM-06 (Offer schema completeness):** Sampled Offer JSON-LD across all 15 products. `sku`, `price`, `priceCurrency`, `availability`, `seller` (`@id` → `/#organization`), and `hasMerchantReturnPolicy` are present. GTIN, MPN, `priceValidUntil`, and `OfferShippingDetails` are absent on every product — no invented identifiers found (correct, per no-fabrication policy), gap unchanged.
- **ECOM-08 (`/shop/` category schema):** `/shop/` JSON-LD contains `BreadcrumbList`, `WebPage`, `WebSite`, `SearchAction`, `OnlineStore`, `MerchantReturnPolicy` — no `ItemList` or `OfferCatalog` node. Gap unchanged from the "partially complete" ledger note; no category-schema code change is evidenced this cycle.
- **DFS-07 / ECOM-09 (Merchant Center):** Searched all 16 fetched pages for `google_product_category`, `gtin`, `mpn`, Merchant Center meta tags, or feed references — none found anywhere. No Merchant Center policy action is visible. Both remain informational/blocked pending owner verification of restricted-catalog eligibility.

## Prior Finding Classifications

| ID | Original Priority | Prior State (8/18) | Current Classification | Evidence |
|---|---|---|---|---|
| ECOM-01 | Critical | Blocked / input needed | BLOCKED BY REAL EVIDENCE | Zero `aggregateRating`/`reviewCount` in JSON-LD across all 15 product pages fetched 8/20; correctly not fabricated. |
| ECOM-02 | Critical | Blocked / input needed | BLOCKED BY REAL EVIDENCE | 7 of 15 products (glp1-s10, glutathione, glp2-t20, bpc157-10, motsc-10, ss-31, bacteriostatic-water-30ml) still have no `/testing/<compound>/` page; only 8 compound-archive hubs exist sitewide and no new one was added. |
| ECOM-03 | High | Not started (row) / Live implementation deployed (M4 Batch 1 checkpoint) | VERIFIED FIXED | GLP-2T Product JSON-LD description is unique dual-receptor copy; 3 DOI citations render on-page; no supplier boilerplate phrase found. |
| ECOM-04 | High | Live verified | VERIFIED FIXED | `/testing/` hub renders all 8 expected product back-links; 3 sampled compound-history pages each link to the correct matching product and resolve 200. |
| ECOM-05 | High | Blocked / input needed | BLOCKED BY REAL EVIDENCE | Exactly 7 of 15 products remain Out of Stock; no substitute/related-product surfacing on any of the 7 pages checked. |
| ECOM-06 | Medium | Conflicting / validate | STILL OPEN | Offer schema has `sku`/`price`/`availability`/`seller`/`hasMerchantReturnPolicy` but no GTIN, MPN, `priceValidUntil`, or `OfferShippingDetails` on any of 15 products; no fabricated values present. |
| ECOM-07 | Medium | Not started | STILL OPEN | Product JSON-LD descriptions across sampled pages still follow the same rigid "[NAME] [DOSE]MG. [one templated research sentence]." pattern (e.g., NAD+, GLP-2T). |
| ECOM-08 | Medium | Partially complete | STILL OPEN | `/shop/` JSON-LD has no `ItemList`/`OfferCatalog` node; only Breadcrumb/WebPage/WebSite/SearchAction/OnlineStore/MerchantReturnPolicy present. |
| ECOM-09 | Low | Blocked / input needed | BLOCKED BY REAL EVIDENCE | No visible Merchant Center policy signal or action anywhere on-page; owner eligibility verification still required, informational only. |
| DFS-07 | Medium | Blocked / input needed | BLOCKED BY REAL EVIDENCE | No `google_product_category`, GTIN, MPN, or Merchant Center meta/feed reference found on any of the 16 pages fetched. |

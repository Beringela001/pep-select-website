# E-commerce SEO Re-Verification — pepselect.com
Audit date: 2026-08-23 | Claude SEO plugin v2.2.4 | Read-only, static evidence only
Source: `docs/claude-seo-audit-2026-08-23/raw-crawl/pages/product_*.html` (17 products), `shop.html`, `testing*.html`, `extracted.json` — fetched live 2026-08-23, analyzed from disk. No DataForSEO/marketplace API calls made (out of scope per task). No re-crawl performed.

## 1. Per-ID re-verification

| ID | Priority | Classification | Evidence |
|---|---|---|---|
| ECOM-01 | Critical | BLOCKED BY REAL EVIDENCE (unchanged) | Parsed Product JSON-LD on all 17 product pages: `aggregateRating` and `review` are absent/null on every page. No reviews exist to report — correctly not fabricated. |
| ECOM-02 | Critical | STILL OPEN / BLOCKED BY REAL EVIDENCE (re-mapped, scope grew) | 9 of 17 products have no matching `/testing/<compound>/` COA hub: `bacteriostatic-water-30ml`, `bpc157-10`, `cag10`, `glp1-s10`, `glp2-t20`, `glutathione`, `kpv10`, `motsc-10`, `ss-31`. The 7 pre-existing gaps (all but bacteriostatic-water, which was already open) are unchanged; the 2 new SKUs (Cagrilintide `cag10`, KPV `kpv10`) confirmed to have no hub in `ps_compound-sitemap.xml` (only 8 hubs total) or in-page. See section 3 for full mapping. |
| ECOM-03 | High | VERIFIED FIXED (unchanged) | `product_glp2-t20.html` Product JSON-LD `description`: "GLP-2T 20MG. Tirzepatide is a dual-agonist research peptide designed to engage both GIP and GLP-1 receptors. Research focuses on how one peptide interacts with two receptor systems and how its structure shapes signaling at each receptor." Still unique (not the shared single-target template used elsewhere) and still carries exactly 3 on-page DOIs: `10.1016/j.ijbiomac.2025.146141`, `10.1073/pnas.2116506119`, `10.1172/jci.insight.140532`. |
| ECOM-04 | High | VERIFIED FIXED, with one caveat (see ECOM-10) | All 8 `/testing/<compound>/` hub pages link back to the correct product page with a plain `<a href>` (verified: ghk-cu-50-mg→/product/ghk-cu/, nad-500-mg→/product/nad/, pt-141-10-mg→/product/pt-141/, retatrutide-10mg→/product/glp3-r10/, retatrutide-20mg→/product/glp3-r20/, retatrutide-30mg→/product/glp3-r30/, tb-500-10-mg→/product/tb500-10/, tesamorelin-10-mg→/product/tesa-10/). The hub→product direction remains 100% correct. Forward linking (product→hub) is not 100%; see ECOM-10. |
| ECOM-05 | High | STILL OPEN (re-counted) | 7 of 17 products are out-of-stock (up from 7 of 15; see section 2 — same absolute count, catalog grew by 2 in-stock SKUs). Checked all 7 OOS product pages for a substitute/related-product block: none found. The only cross-sell markup present (`pepselect-bacwater-sidecart` CSS/JS component) is a generic Bacteriostatic Water side-cart promo, not a substitute suggestion for the OOS SKU, and it renders no static `<a href>`/`data-*` link in the raw HTML captured — [VERIFY CLAIM] whether it becomes an actual crawlable link post-JS (would require `render_page.py --mode always`, out of this read-only pass's scope). |
| ECOM-06 | Medium | STILL OPEN (confirmed) | 0 of 17 products have `gtin`/`gtin13`/`mpn` on the Product node, `offers.gtin`/`offers.mpn`, `priceValidUntil`, or `shippingDetails`/`OfferShippingDetails` in JSON-LD. `hasMerchantReturnPolicy` is present on all 17 (unaffected). |
| ECOM-07 | Medium | STILL OPEN, with an aggravating detail | All 17 descriptions follow the identical template: "`[NAME] [DOSE]MG. [Compound] is a [class] studied for its role in [mechanism]. It is researched for [use].`" New in this pass: the three Retatrutide dosage variants (`glp3-r10`, `glp3-r20`, `glp3-r30`) have **word-for-word identical** JSON-LD descriptions apart from the dose number in the first sentence — this is a stronger near-duplicate-content signal than a shared template, since three distinct product URLs carry effectively one description. |
| ECOM-08 | Medium | STILL OPEN (confirmed) | `/shop/` JSON-LD block (1 block) contains only `WebSite`, `WebPage`, `BreadcrumbList`, `OnlineStore`, `MerchantReturnPolicy`, `ImageObject`, `SearchAction`/`ReadAction`/`EntryPoint`, `PropertyValueSpecification`, `ListItem` types — no `ItemList` or `OfferCatalog` node referencing the 16 products shown in the grid. |
| ECOM-09 | Low | BLOCKED BY REAL EVIDENCE (unchanged) | No `google_product_category`, Merchant Center feed reference, or related markup found in sampled product/shop HTML. Cannot fabricate a Merchant Center policy signal that doesn't exist. |
| DFS-07 | Medium | BLOCKED BY REAL EVIDENCE (unchanged) | No DataForSEO Merchant API call made (out of scope — no metered/paid API calls permitted this pass). Static check confirms no `google_product_category`/GTIN/MPN/feed reference on-page either (same evidence as ECOM-09/ECOM-06). |
| MAP-01 | Medium | STILL OPEN (confirmed) | Searched all 43 crawled pages (`grep -l 'bacteriostatic-water-30ml'`) — the string appears only inside `product_bacteriostatic-water-30ml.html` itself. `/shop/` grid links (16 products, listed in section 2 order) do not include it. No other product page links to it. It remains present in `product-sitemap.xml`, so it is indexable but internally orphaned in the static HTML. The side-cart "Bacteriostatic Water" promo (see ECOM-05) references it in CSS/JS scaffolding but has no static `<a href>` in the raw HTML — does not resolve the orphan status as verified here. |

## 2. Stock-state table (17 products, from Offer `availability` in Product JSON-LD)

| Product (slug) | Availability | Price (USD) |
|---|---|---|
| bacteriostatic-water-30ml | In stock | 19.99 |
| bpc157-10 | **Out of stock** | 38.99 |
| cag10 (Cagrilintide) | In stock | 95.99 |
| ghk-cu | In stock | 33.99 |
| glp1-s10 | **Out of stock** | 65.99 |
| glp2-t20 | **Out of stock** | 109.99 |
| glp3-r10 | In stock | 79.99 |
| glp3-r20 | **Out of stock** | 101.99 |
| glp3-r30 | In stock | 179.99 |
| glutathione | **Out of stock** | 34.99 |
| kpv10 (KPV) | In stock | 44.99 |
| motsc-10 | **Out of stock** | 33.99 |
| nad | In stock | 51.99 |
| pt-141 | In stock | 39.99 |
| ss-31 | **Out of stock** | 67.99 |
| tb500-10 | In stock | 49.99 |
| tesa-10 | In stock | 67.99 |

**Split: 10 in stock / 7 out of stock (10/17 = 58.8% in stock).** Prior audit (8/20): 8/15 in stock (~53%), 7/15 OOS. The absolute OOS count (7) is unchanged — both new SKUs (Cagrilintide, KPV) launched in stock — so the in-stock ratio improved only because the denominator grew, not because any previously-OOS product was restocked. [VERIFY CLAIM] — could not confirm whether any specific SKU flipped in-stock↔out-of-stock between the two audit dates without the 8/20 raw HTML for cross-diff; this reflects the 8/23 snapshot only.

Catalog-order note: the `/shop/` grid link order (`bpc157-10, cag10, ghk-cu, glp1-s10, glp2-t20, glp3-r10, glp3-r20, glp3-r30, glutathione, kpv10, motsc-10, nad, pt-141, ss-31, tb500-10, tesa-10`) is alphabetical-by-slug, not stock-status-first — OOS products are interleaved with in-stock ones, not visibly deprioritized in the raw HTML order. [VERIFY CLAIM] on rendered/visual position, since a WooCommerce theme can still apply client-side sort/CSS ordering not reflected in DOM source order; this check is DOM-order only.

## 3. COA-coverage table (product → matching `/testing/<compound>/` hub)

| Product (slug) | Has hub? | Hub slug | Surfaced on product page? |
|---|---|---|---|
| bacteriostatic-water-30ml | No | — | n/a |
| bpc157-10 | No | — | n/a |
| cag10 (Cagrilintide, NEW) | **No** | — | n/a |
| ghk-cu | Yes | ghk-cu-50-mg | Yes (COA widget + link) |
| glp1-s10 | No | — | n/a |
| glp2-t20 | No | — | n/a |
| glp3-r10 | Yes | retatrutide-10mg | Yes (COA widget + link) |
| glp3-r20 | Yes | retatrutide-20mg | **No** — hub exists but not surfaced (see ECOM-10) |
| glp3-r30 | Yes | retatrutide-30mg | Yes (COA widget + link) |
| glutathione | No | — | n/a |
| kpv10 (KPV, NEW) | **No** | — | n/a |
| motsc-10 | No | — | n/a |
| nad | Yes | nad-500-mg | Yes (COA widget + link) |
| pt-141 | Yes | pt-141-10-mg | Yes (COA widget + link) |
| ss-31 | No | — | n/a |
| tb500-10 | Yes | tb-500-10-mg | Yes (COA widget + link) |
| tesa-10 | Yes | tesamorelin-10-mg | Yes (COA widget + link) |

9 of 17 products have no COA hub at all; of the 8 that do, 7 surface it and 1 (glp3-r20) does not. Confirmed both new SKUs (Cagrilintide, KPV) launched without COA coverage, as anticipated.

## 4. New findings

**ECOM-10 (High, NEW) — COA widget/link silently disappears on out-of-stock product despite a live matching hub.** `product_glp3-r20.html` (Retatrutide 20MG, out of stock) contains zero occurrences of the `ps-coa-app` COA-carousel widget markup and zero `/testing/retatrutide-20mg/` links anywhere in the page, even though the hub exists, is in `ps_compound-sitemap.xml`, and correctly links back to `/product/glp3-r20/`. By contrast, the two other Retatrutide dosages (`glp3-r10`, `glp3-r30`, both in stock) render the full widget with 112 `coa-`-prefixed CSS occurrences each. Cross-checked against `add-to-cart` button presence: the COA widget's absence tracks perfectly with the `stock out-of-stock` class across all 7 OOS pages (widget present on 0/7 OOS pages, present on all 5 in-stock pages that have a hub at all). This suggests the theme conditionally suppresses the whole "purchase-adjacent" module block (cart form + COA widget together) when a product goes OOS, rather than gating on stock alone for the cart form — an unintended side effect that removes third-party lab verification exactly when a buyer would want it most (evaluating whether to wait for restock) and breaks the otherwise-complete hub↔product link symmetry (ECOM-04). Recommendation: decouple the COA widget render condition from the add-to-cart form's OOS suppression so COA/testing links persist regardless of stock status. Expected impact: restores 1 broken forward-link, protects trust signal on a $101.99 SKU during stockout.

**ECOM-11 (Low, NEW) — Near-duplicate description content across 3 Retatrutide dosage SKUs.** `glp3-r10`, `glp3-r20`, `glp3-r30` Product JSON-LD `description` fields are word-for-word identical except for the leading dose number ("GLP-3 R 10MG." / "20MG." / "30MG."). This is a stronger duplicate-content risk than the general templating already logged under ECOM-07, since it spans 3 indexable canonical URLs with otherwise-identical body copy. Recommendation: add one differentiating sentence per dosage tier (e.g., typical research titration range, vial concentration context) to reduce duplicate-content risk in rich-result and organic snippet competition between the three URLs.

**ECOM-12 (Info/Low, NEW) — `bacteriostatic-water-30ml` missing `description` in Product JSON-LD.** Unlike all 16 other products (182–298 char descriptions), the Bacteriostatic Water Product node has no `description` field at all (`description_len: 0`). This is a Google-recommended field for Product rich results; its absence is specific to this SKU, compounding its existing orphan-link issue (MAP-01).

## 5. Changes since 2026-08-20

- Catalog grew from 15 to 17 products: **Cagrilintide** (`cag10`) and **KPV** (`kpv10`) added, both launched in stock, both launched with no COA hub — consistent with the pre-audit expectation.
- ECOM-02 scope re-mapped from "7 of 15" to "9 of 17" lacking COA coverage; the increase of 2 is fully attributable to the 2 new no-hub SKUs, not a regression of previously-covered products.
- ECOM-05 (OOS/no-substitute) absolute count unchanged at 7; ratio improved only nominally (7/15→7/17) due to catalog growth, not restocking. [VERIFY CLAIM] on whether any individual SKU's stock state flipped, since no 8/20 raw HTML snapshot was available for a same-SKU diff.
- ECOM-03 and ECOM-04 (hub→product direction) both hold at VERIFIED FIXED, no regression detected.
- ECOM-06, ECOM-07, ECOM-08, ECOM-09, DFS-07, MAP-01 all confirmed STILL OPEN / unchanged, with fresh static evidence gathered this pass.
- New: ECOM-10 (COA widget suppressed on OOS `glp3-r20` despite hub existing — partially undermines ECOM-04's forward-link completeness), ECOM-11 (Retatrutide 3-SKU duplicate descriptions), ECOM-12 (Bacteriostatic Water missing description field).
- No stop condition encountered specific to e-commerce analysis; DataForSEO Merchant API intentionally not called per task scope (measurement-only, no paid/metered APIs).

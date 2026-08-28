# Pep Select — Post-Remediation SEO Audit (Cycle 4)

**Audited:** `https://pepselect.com` (Live)
**Date:** 2026-08-28
**Tooling:** Claude SEO **2.2.4** plugin (`/seo audit` orchestration + 11 parallel topic specialists), PageSpeed Insights v5, Google Search Console API (read-only Search Analytics + URL Inspection), CrUX + CrUX History API, GA4 Data API (configured, access grant still pending), Common Crawl web graph (backlink Tier 0), direct HTTP/JSON-LD inspection, Playwright Chromium. Auth **Tier 2**.
**Type:** New, independent read-only re-verification against the 2026-08-23 ledger (97 baseline findings + 14 added 8/23 = **111**) plus the nine Live releases shipped 2026-08-26→28. This is not a summary of the prior audit.

---

## 1. Methodology and freshness

- **Crawl:** all 5 XML sitemaps fetched live → **62 sitemap URLs** (post 1, page 10, ps_compound 15, ps_coa_test 18, product 18 incl. `/shop/`). All 62 return HTTP 200; one (`/order/`) is `noindex`. Catalog unchanged at **17 products**; COA infrastructure grew from 8 → **15 compound hubs** and 9 → **18 batch reports**.
- **Specialist coverage:** eleven parallel specialists via the plugin's delegation design — technical, content/E-E-A-T, schema, sitemap, performance, visual, GEO, Google field data, backlinks (Tier 0), SXO, e-commerce. Per-domain detail in `findings/*.md`. seo-local, seo-maps, seo-cluster (no content program exists to cluster) and seo-drift (no baseline captured) were not spawned.
- **Performance:** 12 PageSpeed runs (1 desktop + 3 mobile × home, `/shop/`, `/product/ghk-cu/`); one shop mobile run failed at the API. Lighthouse **lab** only — CrUX and CrUX History both return no data (origin ineligible).
- **Field data:** GSC read-only, 28-day window 2026-07-31→08-25. URL Inspection on `/`, `/product/kpv10/`, `/product/glp3-r30/`. **No indexing/recrawl requests submitted.**
- **Constraints honored:** read-only; no mutations to Live/GSC/GA4/DNS; no indexing submissions; **no paid/metered DataForSEO** (backlinks at Tier 0 only); no fabricated reviews, ratings, GTINs, lab facts, or medical claims. Unverifiable items marked `[VERIFY CLAIM]`.

## 2. Executive summary

Since the 8/23 audit, nine Live releases shipped (cart recovery exit-offer, back-in-stock emails, contiguous-US + AK/HI/PR shipping restrictions, five BOGO cart iterations) — none SEO-targeted — yet the COA archive expanded substantially. Re-verification of all 111 prior findings shows **15 verified fixed, 19 partially fixed, 50 still open, 12 blocked on business input, 14 superseded/informational, and 1 lab regression**, plus **8 new findings**. The real movement this cycle:

1. **The COA archive nearly closed its biggest gap (ECOM-02, BLOCKED → PARTIALLY FIXED).** Six new compound hubs and nine new batch reports went live; **14 of 17 products now link to a hub or batch report** (was 8/17). Only GLP-1 S, GLP-2 T and Bacteriostatic Water remain without a COA path. This is the strongest trust asset the site has and it is now mostly wired.
2. **The out-of-stock COA path is restored (ECOM-10, VERIFIED FIXED).** Retatrutide 20 mg is still OOS but now links to `/testing/retatrutide-20mg/`.
3. **Organic exposure keeps rising from a tiny base:** GSC 28-day moved from 2 clicks / 24 impressions / pos 26.2 to **9 clicks / 85 impressions / pos 38.2** (position "worsened" only because impressions widened into long-tail queries at 63–94). Home earns 7 of the 9 clicks at position 8.1; a batch report and a product page recorded their first clicks. All inspected URLs are indexed. A Trustpilot referring URL was detected by URL Inspection.

**What did not move or got worse:** the **security-header stack is absent for a fourth consecutive cycle** (only `X-Content-Type-Options` present on a checkout site). **Mobile LCP is worse on lab:** `/shop/` 9.9–10.0 s (was 5.3–6.0 s), product 5.4–10.9 s, home 4.6–9.8 s; and the **homepage desktop score fell to 59** with 1,770 ms TBT and 7.0 s main-thread work where every other template scores 86–95 — a new, template-specific regression that coincides with the 8/26 cart-recovery exit-offer release `[VERIFY CLAIM]`. **Out-of-stock rose to 10 of 17 products.** The full-viewport research gate still hides 100% of first paint. The `beringela001` username still leaks; phone is still absent from JSON-LD; the Bacteriostatic Water product is still an orphan; none of the seven 8/23 "code-ready quick wins" shipped.

**New this cycle:** `/order/` (a `noindex` WooCommerce page) sits in `page-sitemap.xml`; `/about-us/` is `noindex,follow` on a ~7,000-word page while the content specialist rates a missing indexed About page the top E-E-A-T gap; `og:type="article"` on `/shop/` and all product pages; an AdSense script chain is loaded on the storefront; a PSI colour-contrast failure and console errors; product titles/meta do not front-load the batch-matched-COA differentiator that every competitor's generic "99% / third-party tested" snippet lacks.

**Compliance:** no fabricated ratings/reviews/GTINs anywhere; `MerchantReturnNotPermitted` accurately reflects policy. One correction to a specialist output: the GEO specialist recommended adding `FAQPage` schema — that conflicts with the plugin's quality gate (Google retired FAQ rich results for all sites on 2026-05-07) and with SCHEMA-08; the recommendation is **not** carried into the action plan.

## 3. Score tables

### 3.1 Category scores (this cycle's specialists, 0–100)

| Category | 8/28 | 8/23 | Basis |
|---|---:|---:|---|
| Technical SEO | 74 | 74 | Crawl/canonical/sitemap hygiene strong; security headers absent (4th cycle); no IndexNow; `/order/` in sitemap |
| Schema / Structured Data | 71 | 70 | Product/Offer/OnlineStore/MerchantReturnPolicy valid; GSC URL Inspection now confirms missing `aggregateRating`, `review`, `shippingDetails`, `gtin` |
| Content Quality | 48 | 43 | COA hub coverage improved; still no authors, no indexed About, one guide, templated descriptions |
| On-Page | 60 | — | Titles/meta present sitewide but generic; USP not in snippets (new SXO-10) |
| Performance (CWV, lab) | 55 | 58 | Mobile median 60 (range 57–71); home desktop 59 vs 86–95 elsewhere |
| E-commerce | 58 | 55 | COA wiring 14/17 (+6); OOS 10/17 (−3); variant duplication; no ItemList |
| Above-the-fold / SXO | 40 | 40 | Gate unchanged; comparison-shopper persona 47/100 on PDP |
| GEO / AI Readiness | 62* | 38 | *Rubric difference between cycles — no sameAs/author/off-site change occurred; treat as not comparable |

Plugin-weighted SEO Health Score (Technical 22 / Content 23 / On-Page 20 / Schema 10 / Performance 10 / AI 10 / Images 5): **62 / 100** (8/18 baseline: 57). As in prior cycles, the dominant open items (gate blocking behaviour, authority/off-site presence) are business decisions, not numbers.

### 3.2 PageSpeed (fresh, 2026-08-28)

| URL | Desktop | Mobile (3 runs) | Mobile LCP | Mobile CLS | Desktop TBT |
|---|---:|---|---|---|---|
| `/` | **59** | 58, 71, 71 | 4.6–9.8 s | 0.081–0.084 | **1,770 ms** |
| `/shop/` | 95 | 59, *(failed)*, 60 | 9.9–10.0 s | 0 | 20 ms |
| `/product/ghk-cu/` | 86 | 57, 58, 58 | 5.4–10.9 s | 0 | 260 ms |
| **Aggregate** | 59–95 | **median 60, range 57–71 (n=8)** | 4.6–10.9 s | Good | — |

8/23 for comparison: desktop 96–97, mobile median 71 (59–81), mobile LCP 3.8–9.7 s. CrUX + CrUX History: no data on every call. PERF-12 (home CLS 0.303) did **not** reproduce — three home mobile runs all ≤0.084.

### 3.3 Google Search Console (28-day, 2026-07-31→08-25)

| Metric | 8/20 | 8/23 | **8/28** |
|---|---:|---:|---:|
| Clicks | 0 | 2 | **9** |
| Impressions | 7 | 24 | **85** |
| Avg. position | 45.7 | 26.2 | 38.2 |
| CTR | 0% | 8.3% | 10.6% |

Top pages: `/` 7 clicks (pos 8.1), `/product/glp3-r30/` 1 click (pos 44.6), `/testing/retatrutide-20mg/psrt2062926jp/` 1 click (pos 5.0). Non-brand queries (`buy kpv 10mg`, `retatrutide certificate of analysis`, `glp3 30mg`) all 0 clicks at positions 63–94. Mobile: 6 clicks / 23 impr / pos 2.8; desktop 3 / 61 / pos 52.1. Sitemap: submitted 2026-08-14, 0 errors, 56 web + 24 image URLs. GA4: `Permission denied for property '549907385'` (GOOG-11 unchanged).

## 4. Drift since 2026-08-23 (the nine Live releases)

| Release theme | Verified SEO-relevant state on Live 8/28 |
|---|---|
| **COA archive expansion** (no release note; observed) | 6 new hubs (glutathione, bpc-157, kpv, mots-c, ss-31, cagrilintide) + 9 new batch reports (`progress-16xx`, `rt3026233gx`, `rt2026233gx`, `nd50026205js`, `psgkcu5071926gx`). ECOM-02 14/17 wired. |
| **Cart recovery 0.1.2 exit-offer** (8/26) | Homepage desktop TBT 1,770 ms / main-thread 7.0 s / score 59; other templates 86–95. Correlation only — cause `[VERIFY CLAIM]` (PERF-14). |
| **Shipping restrictions / contiguous US / AK-HI-PR** (8/27) | FAQ + Refund policy updated; no crawl/index impact observed; `MerchantReturnNotPermitted` unchanged. |
| **BOGO cart 1.2.1→1.5.1** (8/28) | Cart/side-cart only; no indexable-surface change. |
| **Back-in-stock emails** (8/26) | Email only. OOS count 7→10/17 (ECOM-05 worsened). |

## 5. Top 5 causes by overall impact

1. **Zero off-site trust footprint on a 3-month-old domain in a YMYL-adjacent niche** (GEO-05, ECOM-01, DFS-02, CONT-08, new CONT-17). SERPs for the target queries are 100% vendor pages — the templates are right; the site is simply absent. Common Crawl has no record of the domain; no reviews, no authors, no sameAs, no indexed About page. Every ranking lever below is gated by this.
2. **The full-viewport research gate blocks 100% of first paint** (VIS-01, SXO-01) — unchanged; a compliance/business decision awaiting Paulo's sign-off.
3. **Security-header stack absent for a fourth cycle** (TECH-01–04, GOOG-08) — edge-level fix, no WordPress code.
4. **Mobile render path** (PERF-01/02/06, TECH-06) plus the **new home-desktop regression** (PERF-14) and an AdSense chain on the storefront (PERF-13).
5. **Catalog content still templated and split across dose variants** (CONT-03/04/15, ECOM-07/11) — three Retatrutide SKUs share description paragraphs; only meta descriptions were differentiated.

## 6. Quick wins (code-ready, low risk, no business input)

1. **HSTS + X-Frame-Options at Cloudflare/Kinsta** (TECH-01/03) — start HSTS without `preload`; CSP in Report-Only first.
2. **Remove `/order/` from `page-sitemap.xml`** (new MAP-04) — Yoast exclude on the WooCommerce endpoint page.
3. **`telephone` + `contactPoint` in Organization JSON-LD** (SCHEMA-11) — number is already on-page.
4. **`Dataset` JSON-LD on the 15 compound hubs** (GEO-07) — 0/3 sampled hubs carry it; batch reports already do.
5. **Crawlable `<a href>` to Bacteriostatic Water** (MAP-01) — still 0 links from `/shop/` and `/faq/`.
6. **Differentiate Retatrutide body copy** (CONT-15/ECOM-11) — meta is done; description paragraphs still identical.
7. **`og:type` → `product` on PDPs / `website` on `/shop/`** (new SCHEMA-13).
8. **Suppress `beringela001`** in `meta[name=author]` (GEO-08 hygiene) and **hyperlink the 3 GLP-2T DOIs** (CONT-06).
9. **Re-measure home desktop 3× and bisect the 8/26 exit-offer script** (PERF-14).

## 7. Risks

- **CSP enforcement** will break GTM/Elementor/YITH/side-cart/exit-offer AJAX if not trialled Report-Only for ≥1 release cycle.
- **Gate redesign** remains a compliance decision — do not alter blocking without sign-off.
- **`/about-us/` noindex** may be intentional (prior cycles logged it as "monitor"); un-noindexing is a NEEDS-APPROVAL item, not a quick win, because the page's claims must pass `.agents/product-marketing.md` before it is exposed.
- **AdSense on the storefront** (PERF-13): verify it is intended — ads on a research-chemical store carry both performance cost and policy exposure `[VERIFY CLAIM]`.
- **Variant consolidation** (glp3-r10/20/30 → one variable product) touches WooCommerce product/stock/COA relationships — business-logic boundary; needs approval and a rollback package.

## 8. Exact verification checks (next operator)

- `curl -sI https://pepselect.com/ | grep -i strict-transport` → empty today.
- `curl -s https://pepselect.com/page-sitemap.xml | grep -c '/order/'` → 1 today; 0 once MAP-04 ships.
- `curl -s https://pepselect.com/contact/ | grep -c '"telephone"'` → 0 today.
- `curl -s https://pepselect.com/testing/nad-500-mg/ | grep -c '"@type":"Dataset"'` → 0 today.
- `curl -s https://pepselect.com/shop/ | grep -c 'bacteriostatic-water'` → 0 today.
- `curl -s https://pepselect.com/product/tb500-10/ | grep -o 'og:type" content="[^"]*'` → `article` today.
- `curl -s https://pepselect.com/guides/how-to-review-research-peptide-documentation/ | grep -c beringela001` → 2 today.
- PSI home desktop 3× — today 59 / TBT 1,770 ms; other templates 86–95.
- GA4: grant Viewer on property 549907385, then `ga4_report.py --property 549907385 --report organic`.

## 9. Prioritized plan

See `ACTION-PLAN.md`. **[CODE-READY]** security headers, MAP-04 sitemap hygiene, SCHEMA-11, GEO-07, MAP-01, CONT-15 body copy, SCHEMA-13 og:type, GEO-08 hygiene, CONT-06 DOI links, PERF-14 bisect. **[NEEDS APPROVAL]** gate blocking behaviour, `/about-us/` index decision, variant consolidation, AdSense intent, thin-content program, snippet rewrite (SXO-10). **[BLOCKED — REAL EVIDENCE NEEDED]** reviews, 3 remaining COA hubs, sameAs/social, named author, address, GA4 grant, Merchant Center eligibility, backlinks/off-site.

## 10. What this audit does not claim

- No ranking, revenue, or conversion improvement is claimed; the GSC 2→9 click move is reported as measured field data, not attributed to any change.
- All performance numbers are Lighthouse **lab**; no CrUX verdict is possible at this traffic level. Run-to-run variance is large (home mobile 58 vs 71 within minutes); the shop/home LCP "regression" is n=2–3 and flagged for confirmation.
- Backlink profile rests on Common Crawl absence only (Tier 0); no Moz/DataForSEO measurement was made.
- GEO score movement (38→62) is a rubric difference between specialists, not a site change.
- `schema_ecommerce_validate.py` crashed on Yoast's list-form `priceSpecification` (plugin bug, not a site defect); schema was reviewed manually.

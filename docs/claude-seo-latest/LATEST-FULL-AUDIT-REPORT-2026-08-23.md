# Pep Select — Post-Remediation SEO Audit (Cycle 3)

**Audited:** `https://pepselect.com` (Live)
**Date:** 2026-08-23
**Tooling:** Claude SEO **2.2.4** plugin (`/seo-audit` methodology + topic skills), PageSpeed Insights v5 (Tier-0 API key), Google Search Console API (read-only Search Analytics + URL Inspection), CrUX/CrUX-History API, GA4 Data API (configured, access-grant pending), direct HTTP/JSON-LD inspection, Playwright Chromium. Auth **Tier 2**.
**Type:** New, independent read-only re-verification against the 2026-08-20 baseline (97 findings) plus the ten Live releases shipped 2026-08-20→22. This is not a summary of the prior audit.

---

## 1. Methodology and freshness

- **Crawl:** all 5 XML sitemaps fetched live → **46 unique indexable URLs** (was 44 on 8/20; +2 products). Full HTML for all 46 saved to `raw-crawl/pages/`. `robots.txt` fetched and respected.
- **Specialist coverage:** six parallel topic specialists (technical, schema, content, ecommerce, geo, visual/SXO) via the plugin's delegation design, each re-verifying its slice of the 97 prior findings against the local corpus with stable IDs; plus orchestrator-run performance (20 PSI runs) and Google-field-data (GSC + CrUX + URL Inspection) passes. Per-domain detail in `findings/*.md`.
- **Performance:** 20 PageSpeed runs (1 desktop + 3 mobile × 5 template URLs). Lighthouse **lab** only — CrUX field data remains unavailable (origin ineligible).
- **Field data:** GSC read-only, 28-day window ending 2026-08-20. **No indexing/recrawl requests submitted.** URL Inspection on 4 key URLs.
- **Constraints honored:** read-only; no mutations to Live/GSC/GA4/DNS; no indexing submissions; **no paid/metered DataForSEO** (a bridge stop-condition); no fabricated reviews, ratings, GTINs, lab facts, or medical claims. Anything unverifiable marked `[VERIFY CLAIM]`.

## 2. Executive summary

Since the 8/20 audit, ten Live releases (through theme `0.25.0-beta.4`) shipped SEO milestones plus a catalog expansion. Re-verification of all 97 prior findings shows **14 verified fixed, 17 partially fixed, 46 still open, 13 blocked on business input, 7 superseded, and zero regressions**, plus **14 new findings**. Net movement toward fixed is modest but real and concentrated in three genuine changes:

1. **The research gate's accessibility remediation is now genuinely in the markup (VIS-02, VERIFIED FIXED)** — reversing the 8/20 finding that it was marked verified but absent. Inerting, focus-trap, `aria-describedby`, and a real exit `href` are all present and working. The gate still blocks 100% of first paint (VIS-01/SXO-01 unchanged), which remains the highest-leverage open item — but the a11y sub-layer is real now.
2. **A real phone number is live** (+1 833 737-7528) on `/contact/`, the homepage header, and the footer — closing the "no contact info" half of CONT-09 and lifting the SCHEMA-05 blocker (the number now merely needs adding to JSON-LD → code-ready SCHEMA-11).
3. **The first-ever recorded organic clicks:** GSC 28-day moved from 0/7/pos 45.7 to **2 clicks / 24 impressions / pos 26.2**. The two new products (KPV, Cagrilintide) shipped fully crawlable and KPV is already indexed.

**What did not move:** the entire baseline **security-header stack** (HSTS/CSP/X-Frame-Options/Referrer-Policy/Permissions-Policy) is still completely absent for a third consecutive cycle — the single highest-leverage code-ready open item, now spanning two additional checkout-adjacent product pages. **Mobile Core Web Vitals** are statistically identical to 8/20 (median 71, range 59–81; desktop 96–97). **Catalog-wide thin/templated content** persists — the ~109-word compliance block is byte-identical on 17/17 products, and both new SKUs launched thin and without COA hubs.

**Compliance:** an all-page scan found **no RUO violation** — every dosing/human-use string sits inside negating compliance boilerplate; no affirmative human-use, dosing, or therapeutic claim exists on any page. No fabricated ratings/reviews/GTINs anywhere. Both properties are worth guarding.

## 3. Score tables

### 3.1 SEO Health Score (this audit's scoring, 0–100)

| Category | 8/23 | 8/20 | Basis |
|---|---:|---:|---|
| Technical SEO | 74 | 74 | Crawl/canonical/sitemap hygiene strong; security-header stack still entirely absent; Bac Water orphan persists |
| Schema / Structured Data | 70 | 68 | Entity-graph holds; phone now on-page but not in schema (SCHEMA-11); two-emitter + no hub Dataset remain |
| Content Quality | 43 | 42 | Guide grew to ~3,250 words; 17/17 identical compliance block; Retatrutide SKUs now byte-identical (CONT-15); Bac Water zero description |
| Performance (CWV, lab) | 58 | 58 | Desktop excellent; mobile Needs-Improvement→Poor, unchanged; one unconfirmed home CLS spike (PERF-12) |
| E-commerce | 55 | 55 | Schema/no-fabrication discipline strong; 9/17 no COA; 7/17 OOS; OOS COA-path breakage (ECOM-10) |
| Above-the-fold / SXO | 40 | 30 | **Gate a11y sub-layer now real (VIS-02)**; gate still blocks 100% of first paint (VIS-01) |
| GEO / AI Readiness | 38 | 38 | Content in raw HTML (crawlable); passages ~1/3 of citation benchmark; no sameAs/off-site signals |

*(No single weighted score — the dominant open item, the full-viewport gate, is a business/compliance decision, not a number.)*

### 3.2 PageSpeed (fresh, 2026-08-23)

| URL | Desktop | Mobile (unique runs) | Mobile LCP |
|---|---:|---|---|
| `/` | *(2 runs failed)* | 64 | 4.4 s |
| `/shop/` | 96 | 74, 71 | 5.3–6.0 s |
| `/product/nad/` | 97 | 63 | 4.5 s |
| `/testing/` | 97 | 81, 60 | 3.8–9.0 s |
| COA report | 97 | 79, 59 | 4.5–9.7 s |
| **Aggregate** | **96–97** | **median 71, range 59–81 (n=8)** | 3.8–9.7 s |

Statistically identical to 8/20 (median 75 / 56–81). CrUX `field_metrics: {}` on every run — origin still ineligible.

## 4. Drift since 2026-08-20 (the ten Live releases)

| Release theme | Verified state on Live 8/23 |
|---|---|
| **KPV + Cagrilintide products** | Both live, fully crawlable/indexable, full Product+Offer+Brand+MerchantReturnPolicy schema, in product-sitemap; `kpv10` already indexed (URL Inspection PASS). Both launched **thin** (CONT-04) and **without COA hubs** (ECOM-02). |
| **Research gate accessibility** | Inerting + focus-trap + `aria-describedby` + real exit `href` now present (VIS-02 VERIFIED FIXED). Only sub-16px font remains. The "v2.1.3" label is not in the DOM (`[VERIFY CLAIM]`); form-version token is `PS-RUO-2026.08`. |
| **Homepage labels / batch-matching** | Section now cites Tesamorelin 10mg batch `PSTES1071926GX` (hardcoded in template). One unconfirmed home-mobile CLS 0.303 (PERF-12) — plausibly related, needs re-measure. |
| **Logo / phone** | Real phone +1 (833) 737-7528 live (CONT-09 partial, SCHEMA-05 unblocked → SCHEMA-11). |
| **Catalog order** | Shop grid reordered (in-stock-first pattern); 16 crawlable product anchors (Bac Water still orphan, MAP-01). |

## 5. Top 5 causes by overall impact

1. **The full-viewport research gate blocks 100% of first paint on every device/page** (VIS-01, SXO-01) — unchanged; still the highest-leverage open item. The a11y sub-layer is now real (VIS-02 fixed), but the blocking behavior is a compliance/business decision requiring Paulo's sign-off.
2. **Complete absence of baseline security headers** (TECH-01–04, GOOG-08) — an edge-level, no-WordPress-code fix, unaddressed for a third cycle, now covering two more product pages.
3. **Catalog-wide thin/templated content** (CONT-03/04/07, ECOM-07) — 17/17 identical compliance block; Retatrutide SKUs byte-identical (CONT-15); Bac Water zero description.
4. **Mobile render-blocking CSS/JS + GTM cost** (PERF-02/06, TECH-06) — still the dominant driver of Poor-range mobile LCP.
5. **Near-zero organic visibility despite improving indexation** (GOOG-03, DFS-01) — 2 clicks / 24 impressions across 90+ days; indexation is a prerequisite, not a guarantee.

## 6. Quick wins (code-ready, low risk, no business input)

1. **Add HSTS + X-Frame-Options at the Cloudflare/Kinsta edge** (TECH-01/03) — no WordPress code; start HSTS without `preload`.
2. **Add `telephone` + `contactPoint` to Organization/ContactPage JSON-LD** (SCHEMA-11) — the real number is already on-page; pure schema wiring.
3. **Extend the proven `Dataset` JSON-LD from batch reports to the 8 compound-hub pages** (GEO-07) — 100% real backing data, pure engineering.
4. **Add a crawlable `<a href>` to `/product/bacteriostatic-water-30ml/`** (MAP-01) — closes the orphan without a merchandising decision.
5. **Restore the COA widget/hub link on out-of-stock Retatrutide 20MG** (ECOM-10) — a real product↔COA path currently breaks when a SKU goes OOS.
6. **De-duplicate the three Retatrutide SKU descriptions** (CONT-15/ECOM-11) — byte-identical copy across indexed product pages is a duplicate-content risk.
7. **Suppress the `beringela001` username leak** from `meta[name=author]`/Twitter-card on the guide (GEO-08 hygiene).

## 7. Risks

- **Security-header rollout:** CSP can break GTM/Elementor/YITH/side-cart AJAX if enforced without a `Content-Security-Policy-Report-Only` trial first (≥1 release cycle).
- **Gate redesign:** the gate's *blocking behavior* is a compliance/business decision — do not alter it without Paulo's sign-off, even though the a11y sub-claims are now satisfied.
- **Content-freshness discipline:** continue honoring "don't manufacture `lastmod`" (MAP-02/CONT-12).
- **Homepage CLS (PERF-12):** re-measure `/` mobile 3× before/after any homepage change; the 0.303 sample is unconfirmed but worth watching.

## 8. Exact verification checks (next operator)

- `curl -sD - https://pepselect.com/ | grep -i strict-transport` → empty today; should return once HSTS ships.
- `curl -s https://pepselect.com/testing/nad-500-mg/ | grep -c '"@type":"Dataset"'` → 0 today (hub); ≥1 once GEO-07 ships.
- `curl -s https://pepselect.com/contact/ | grep -c '"telephone"'` → 0 today; ≥1 once SCHEMA-11 ships.
- `curl -s https://pepselect.com/shop/ | grep -c '/product/bacteriostatic-water-30ml/'` → 0 today; ≥1 once MAP-01 ships.
- Re-run `pagespeed_check.py --strategy mobile` 3× per URL and report the **range**, not one score.
- GA4: grant the authenticated identity Viewer on property 549907385, then `ga4_report.py --property properties/549907385` will read (GOOG-11).

## 9. Prioritized plan

See `ACTION-PLAN.md`. Headline: **[CODE-READY]** security headers (report-only CSP first), SCHEMA-11 phone-to-schema, GEO-07 Dataset extension, MAP-01 orphan link, ECOM-10 OOS COA path, CONT-15 dedupe, GEO-08 username hygiene. **[NEEDS APPROVAL]** the gate blocking-behavior decision (VIS-01/SXO-01), the content program for thin descriptions. **[BLOCKED — REAL EVIDENCE NEEDED]** reviews (ECOM-01), 9 missing COA pages (ECOM-02), sameAs/social (GEO-04/05), named author (CONT-08/GEO-08), GA4 access grant (GOOG-11).

## 10. What this audit does not claim

- No ranking, click, revenue, or conversion *improvement* is claimed. The GSC 0→2 click move is reported as measured field data, not attributed to any specific change.
- Indexation ≠ visibility: KPV indexing and the Shop/Testing PASS verdicts are real technical outcomes, not evidence of search performance (2 clicks / 90 days).
- Every performance number is Lighthouse **lab** data; no CrUX/real-user CWV verdict is possible at this origin's traffic volume.
- No backlink, keyword-difficulty, or AI-Overview re-measurement (DFS-02/04/06) — paid tooling excluded by scope.

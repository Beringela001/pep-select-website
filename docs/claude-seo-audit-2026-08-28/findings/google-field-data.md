# Google Field Data Findings — pepselect.com — 2026-08-28

**Method:** GSC read-only (`gsc_query.py`, `gsc_inspect.py`), PSI v5 (`pagespeed_check.py`, mobile + desktop), CrUX + CrUX History (`pagespeed_check.py --crux-only`, `crux_history.py`), GA4 (`ga4_report.py`). Auth tier 2. Property `sc-domain:pepselect.com` (siteOwner). **No indexing/recrawl requests; no GSC state changed.** 28-day window 2026-07-31→08-25.

## Search performance (real, measured drift — positive)

| Metric | 8/20 | 8/23 | **8/28** |
|---|---:|---:|---:|
| Clicks (28-day) | 0 | 2 | **9** |
| Impressions | 7 | 24 | **85** |
| Avg position | 45.7 | 26.2 | 38.2 |
| CTR | 0% | 8.3% | 10.6% |

90-day totals identical to 28-day → all activity is recent. Position "worsened" because impressions widened into long-tail non-brand queries at 63–94.

**Top queries (13 rows, all 0 clicks):** buy kpv 10mg (8 impr, pos 83.1), retatrutide certificate of analysis (8, 63.2), kpv 10mg (7, 93.7), glp3 30mg (3, 85.0), peptselect (3, 35.0), glp 3r research compound for sale (2, 70.5), glp 3r 10mg, glp-3 r lyophilized powder, glp3 10mg, kpv 10, pep return policy, retatrutide 30 mg, fedex.com/labreturns. Clicks arrive via anonymised rows.

**Top pages:** `/` 7 clicks / 18 impr / pos 8.1 · `/product/glp3-r30/` 1 / 11 / 44.6 · `/testing/retatrutide-20mg/psrt2062926jp/` 1 / 2 / 5.0 · `/privacy-policy/` 0 / 11 / 2.6 · `/faq/` 0 / 9 / 10.8 · `/about-us/` 0 / 4 / 3.2 (still noindex) · guide 0 / 4 / 6.8 · `/contact/` 0 / 3 / 2.0 · `/my-account/cash-back/` 0 / 2 / 2.0 · `/product-tag/20mg/` 0 / 2 / 6.5.

**Device:** mobile 6 clicks / 23 impr / pos 2.8; desktop 3 / 61 / pos 52.1; tablet 0 / 1.

**Sitemap:** `sitemap_index.xml` submitted 2026-08-14, 0 errors, 0 warnings, 56 web + 24 image URLs.

## URL Inspection (read-only)

| URL | Verdict | Notes |
|---|---|---|
| `/` | PASS, Submitted and indexed, canonical match | Rich results PASS (Breadcrumbs); **referring URL: Trustpilot** |
| `/product/kpv10/` | PASS, indexed | Product/Merchant snippets WARNING: missing `aggregateRating`, `review`, `shippingDetails` |
| `/product/glp3-r30/` | PASS, indexed | Missing `review`, `aggregateRating`, global identifier (gtin/brand), `validFrom`, `hasMerchantReturnPolicy`, `shippingDetails` |

All: robots ALLOWED, indexing ALLOWED, fetch SUCCESSFUL, crawled as MOBILE; mobile-usability `VERDICT_UNSPECIFIED`.

## PageSpeed / CrUX
See performance.md. CrUX: `No CrUX data for this origin` on every call; CrUX History: `No CrUX history data for this origin`. GOOG-09 unchanged.

## GA4 (verbatim)
```
Error: Permission denied for property '549907385'. Add the service account email as Viewer in GA4 Admin > Property Access Management.
```
GOOG-11 unchanged — one grant from measurable.

## Per-ID
| ID | 8/23 | 8/28 | Evidence |
|---|---|---|---|
| GOOG-01 | VF | **VF** | `/` + products indexed |
| GOOG-02 | VF | **VF** | batch report clicked at pos 5.0 |
| GOOG-03 | SO | **SO (improved)** | 9/85/38.2 |
| GOOG-09 | BLOCKED | **BLOCKED** | no CrUX |
| GOOG-10 | SO | **SO** | PSI: 4 non-descriptive links |
| GOOG-11 | BLOCKED | **BLOCKED** | permission denied |
| DFS-01 | SO | **SO** | non-brand 0 clicks at 63–94 |
| DFS-07 | BLOCKED | **BLOCKED** | GSC: missing gtin |
| SCHEMA-06 / ECOM-06 | SO | **SO** | GSC warnings enumerate the exact missing fields |

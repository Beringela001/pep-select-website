# Google Field Data Findings — pepselect.com — 2026-08-23

**Method:** Google Search Console (read-only Search Analytics + URL Inspection), CrUX History API, all via the Claude SEO plugin scripts (`gsc_query.py`, `gsc_inspect.py`, `pagespeed_check.py --crux-only`). Auth tier **2** (API key + OAuth + GA4 property id configured). **No indexing/recrawl requests submitted; no Search Console state changed.** GSC property: `sc-domain:pepselect.com` (siteOwner). Search Analytics has its normal 2–3 day lag (28-day window ends 2026-08-20). Raw URL-inspection JSON in `raw-crawl/gsc-url-inspection.json`.

## Search performance — real, verified drift (positive)

| Metric | 8/20 audit | 8/23 audit | Change |
|---|---|---|---|
| Clicks (28-day) | 0 | **2** | +2 (first non-zero clicks) |
| Impressions (28-day) | 7 | **24** | +17 |
| Avg position | 45.7 | **26.2** | improved 19.5 |
| CTR | 0% | 8.33% | — |

Both clicks are to the homepage (position 13.2). Impressions now spread across 15 URLs including Shop-adjacent products (`glp3-r30` 5 impressions @ pos 52.6, `glp3-r10` @ 89), the guide, Testing hub + batch reports, and legal pages. Top queries are all low-volume brand/product-navigational (`glp3 30mg`, `peptselect` [brand misspelling], `retatrutide 30 mg`, `glp 3r 10mg`). **This is real, measured improvement in indexation-driven exposure — but organic visibility remains near-zero in absolute terms.** Note: `/about-us/` still shows 3 impressions at position 2.3 despite being `noindex` — Google has not yet dropped it from all surfaces; not a defect, monitor.

## Indexation — URL Inspection (2026-08-23, read-only)

| URL | Verdict |
|---|---|
| `/shop/` | **PASS** (indexed) |
| `/testing/` | **PASS** (indexed) |
| `/product/kpv10/` (NEW 8/20–22) | **PASS** (indexed) |
| guide | **PASS** (indexed) |

The new KPV product, launched in the 8/20–22 window, is already indexed — fast pickup. Confirms GOOG-01/GOOG-02 remain resolved.

## Per-ID re-verification

| ID | Priority | Prior (8/20) | 8/23 | Evidence |
|---|---|---|---|---|
| GOOG-01 | Critical | VERIFIED FIXED | **VERIFIED FIXED** | `/shop/` URL Inspection verdict PASS 2026-08-23; corroborated by Shop-adjacent impressions in Search Analytics. |
| GOOG-02 | High | VERIFIED FIXED | **VERIFIED FIXED** | `/testing/` verdict PASS; batch-report and hub URLs receiving impressions. |
| GOOG-03 | Critical | STILL OPEN | **STILL OPEN (improved)** | 2 clicks / 24 impressions / pos 26.2 vs 0 / 7 / 45.7 at 8/20. Real directional improvement; absolute organic visibility still near-zero. The finding's core claim (no meaningful organic traffic) still holds. |
| GOOG-09 | Medium | BLOCKED BY REAL EVIDENCE | **BLOCKED BY REAL EVIDENCE** | CrUX origin + page checks 2026-08-23 return "insufficient Chrome traffic for eligibility." Purely a function of traffic volume; no code fix. |
| GOOG-11 | Medium | BLOCKED BY REAL EVIDENCE | **BLOCKED BY REAL EVIDENCE (advanced)** | Progress since 8/20: the GA4 property is now identified (`properties/549907385`) and configured in the plugin (Tier 2). But a live GA4 Data API read returns *"Permission denied for property 549907385 — add the account as Viewer in GA4 Admin → Property Access Management."* The authenticated OAuth identity is not yet granted access, so organic-session data still cannot be pulled. One access grant from measured; no fabrication performed. |
| DFS-01 | Critical | STILL OPEN | **STILL OPEN (literal claim now false)** | The literal "0 organic clicks" is no longer true (2 clicks, 28-day). Competitive organic visibility remains effectively absent — pepselect.com still does not appear for any non-brand commercial query sampled. Core concern unchanged. |

**Changes since 2026-08-20:** GSC clicks 0→2, impressions 7→24, avg position 45.7→26.2 (real, measured). New product `kpv10` already indexed. GA4 property identified and configured but access-grant pending. CrUX still ineligible. No indexing submissions were made this cycle.

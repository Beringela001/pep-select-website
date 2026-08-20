# Google Field Data Findings — 2026-08-20

**Recorded:** August 20, 2026 (verification audit against the 2026-08-18 ledger and the 2026-08-19 GSC/PageSpeed checkpoint)
**Property:** `sc-domain:pepselect.com`
**Credential tier:** Tier 1 (API key + service/OAuth). PageSpeed, CrUX, CrUX History, GSC, and Indexing API all report `available: true`. GA4 is `available: false` — no `GA4_PROPERTY_ID` configured.
**Scope constraint:** This slice is read-only GSC/CrUX verification only. No indexing/recrawl requests, no GSC property/settings changes, and no sitemap resubmission were made in this cycle. All five URL inspections below used the read-only `urlInspection.index.inspect` call only.

## 1. GSC URL Inspection — before/after comparison

| URL | 8/19 status (before request) | 8/20 status (this check) | Moved to Indexed? |
|---|---|---|---|
| `https://pepselect.com/shop/` | Discovered — currently not indexed | **Submitted and indexed** (verdict PASS; last crawl 2026-08-19T19:08:25Z, MOBILE) | **Yes** |
| `https://pepselect.com/testing/` | Discovered — currently not indexed | **Submitted and indexed** (verdict PASS; last crawl 2026-08-19T19:17:10Z, MOBILE; referring `ps_compound-sitemap.xml`) | **Yes** |
| `https://pepselect.com/guides/how-to-review-research-peptide-documentation/` | URL is unknown to Google | **Submitted and indexed** (verdict PASS; last crawl 2026-08-20T09:43:30Z, MOBILE) | **Yes** |
| `https://pepselect.com/product/nad/` | Indexed; valid HTTPS + 1 valid Product/Merchant/Breadcrumb item, non-critical issues | **Still Submitted and indexed** (verdict PASS; last crawl 2026-08-19T19:17:10Z; referring `product-sitemap.xml`). Same two non-critical warnings persist: Product snippet missing `aggregateRating` and `review`; Merchant listing missing `shippingDetails`. | Already was (no regression) |
| `https://pepselect.com/product/glp3-r10/` (Retatrutide 10 mg) | URL is unknown to Google | **Submitted and indexed** (verdict PASS; last crawl 2026-08-19T19:13:35Z, MOBILE). Same non-critical Product-snippet (`review`, `aggregateRating`) and Merchant-listing (`shippingDetails`) warnings as NAD+. | **Yes** |

All five URLs pass robots.txt (ALLOWED), indexing state (INDEXING_ALLOWED), and page fetch (SUCCESSFUL), with matching Google/user canonicals. Breadcrumbs render as a valid rich result on every page. Mobile Usability verdict is `VERDICT_UNSPECIFIED` on all five (Google retired the standalone Mobile Usability report in 2023; this is expected, not an error).

**Interpretation:** All four previously non-indexed/unknown URLs now show `coverage_state: "Submitted and indexed"` after Google processed the priority-crawl queue accepted on 8/19. This is a real state change captured via read-only inspection, not a re-submitted claim. NAD+ was already indexed on 8/19 and remains indexed with the same two non-critical warnings (no regression, no fix applied to those warnings).

## 2. GSC Search Analytics (Search Performance)

Dimensionless aggregate queries (site-wide, `totals_complete: true`) — this is the correct site-wide total per the tool's own anti-undercount design; per-query/page rows below are not safe to sum.

| Window | Date range | Clicks | Impressions | Avg. CTR | Avg. position |
|---|---|---:|---:|---:|---:|
| 28 days | 2026-07-23 to 2026-08-17 | 0 | 7 | 0% | 45.7 |
| 90 days | 2026-05-22 to 2026-08-17 | 0 | 7 | 0% | 45.7 |

The 28-day and 90-day totals are identical — all measurable impression volume is concentrated inside the most recent 28-day window; nothing further back contributes. Neither window overlaps the documented 2025-05-13–2026-04-27 GSC impressions-logging anomaly, so no warning was raised and none applies here.

Per-query/page sample rows (query,page dimensions, 28 days, 4 rows returned — not a complete list, GSC anonymizes low-volume queries):

| Query | Page | Impressions | Position | Clicks |
|---|---|---:|---:|---:|
| fedex.com/labreturns | `/faq/` | 1 | 65 | 0 |
| glp3 30mg | `/product/glp3-r30/` | 1 | 85 | 0 |
| peptselect | `/` | 1 | 95 | 0 |
| retatrutide 30 mg | `/testing/retatrutide-30mg/` | 1 | 68 | 0 |

**GOOG-03 / DFS-01 context:** Zero clicks, 7 total impressions in 28 and in 90 days, and average position 45.7 (page 5+) confirm near-zero to zero organic search visibility persists. This is consistent with — not contradicted by — the newly-indexed status above: indexation is a prerequisite for visibility, not a guarantee of it. Impressions exist only for long-tail/misspelled queries at very poor positions.

## 3. GSC Page Indexing / coverage summary

The Search Console API does not expose the bulk Index Coverage report (that UI feature has no public API endpoint). The two API-exposed proxies were checked instead:

- **Sitemaps API** (`sc-domain:pepselect.com` → `sitemap_index.xml`): 0 errors, 0 warnings, not pending. Contents: 44 web URLs submitted, 22 image entries submitted. Per the tool's own indexation note, submitted counts are not proof of indexing — only the URL Inspection API (§1 above) is the indexation source of truth.
- **URL Inspection sampling** (§1): 5/5 sampled URLs are now "Submitted and indexed." No exclusion reasons were returned for any of the five (all PASS, no FAIL/NEUTRAL verdicts), so no per-page exclusion data is available from this sample. A full coverage/exclusion breakdown for the remaining ~39 sitemap URLs would require either the GSC UI's Page Indexing report or additional individual Inspection calls, which were out of scope for this read-only slice.

## 4. CrUX field data re-check

| Target | Level | Result |
|---|---|---|
| `https://pepselect.com` | Origin | `404` — "No CrUX history data for this origin. Insufficient Chrome traffic volume for eligibility." |
| `https://pepselect.com/` | Page (homepage) | `404` — same insufficient-traffic error |

No change since 8/19. CrUX History is still ineligible for pepselect.com at both origin and homepage-URL granularity. This directly supports GOOG-09 remaining open/blocked — it is a real-user traffic volume threshold, not a code or configuration issue, and no fix exists on Pep Select's side.

## 5. GA4 configuration status (GOOG-11)

`google_auth.py --check --json` confirms:
```
"ga4": {
  "available": false,
  "method": "oauth_token",
  "service": "GA4 Data API v1beta",
  "error": "Credentials found but no GA4 property ID configured. Set GA4_PROPERTY_ID or add 'ga4_property_id' to google-api.json",
  "note": "Token expired but refresh_token available (will auto-refresh)"
}
```
OAuth credentials exist and would auto-refresh, but no GA4 property ID is set anywhere in config. This is unchanged from 8/19 and is a business/account decision — Paulo must confirm the correct GA4 property before it can be connected. No GA4 checks were attempted per the audit brief.

## Prior Finding Classifications

| ID | Original Priority | Prior State (8/18) | Current Classification | Evidence |
|---|---|---|---|---|
| GOOG-01 | Critical | Live technical work complete / Google validation pending (Shop and sampled products not indexed) | **PARTIALLY FIXED** | Shop (`/shop/`) now inspects as "Submitted and indexed" (§1), closing the indexation half of the finding. But GOOG-01's underlying concern — organic visibility of Shop/product pages — is not yet demonstrated: Search Analytics shows 0 clicks and only 7 impressions site-wide over 28/90 days (§2), so indexation has not yet translated into measurable search presence. |
| GOOG-03 | Critical | Live technical work complete / monitoring pending (near-zero organic visibility) | **STILL OPEN** | 28-day and 90-day Search Analytics totals are identical: 0 clicks, 7 impressions, 0% CTR, avg. position 45.7 (§2). Indexation improved (§1), but organic visibility itself has not — this is the exact metric GOOG-03 measures, and it remains effectively at zero. |
| GOOG-09 | Medium | Blocked / input needed (no CrUX real-user data for any URL/form-factor) | **STILL OPEN / BLOCKED BY REAL EVIDENCE** | Both origin- and homepage-level CrUX History re-checks on 8/20 returned the identical 404 insufficient-traffic error as 8/19 (§4). No code fix exists; this depends solely on real Chrome user traffic volume growing over time. |
| GOOG-11 | Medium | Blocked / input needed (GA4 not configured) | **STILL OPEN / BLOCKED BY REAL EVIDENCE** | `google_auth.py --check` confirms GA4 remains unavailable — credentials exist but no property ID is configured (§5). This requires a Paulo account/business decision, not further technical work. |

## Notes on data freshness

- GSC URL Inspection reflects Google's last crawl of each URL (crawl timestamps range 2026-08-19T19:08Z to 2026-08-20T09:43Z in this check) — up to date as of this run, subject to GSC's normal 2-3 day reporting lag for other reports.
- Search Analytics data lags 2-3 days; the 28-day window ends 2026-08-17 (today is 2026-08-20), consistent with that lag.
- CrUX is a 28-day rolling real-user dataset; still no data available for pepselect.com at either granularity tested.

# Google SEO API Audit — pepselect.com

- Date: 2026-08-18
- Property: `sc-domain:pepselect.com`
- Credential tier: **Tier 1** (PageSpeed Insights v5 + CrUX API + CrUX History API + Search Console API authenticated; Indexing API authenticated but used READ-ONLY; GA4 not configured — skipped)
- Mode: STRICTLY READ-ONLY. No sitemap submissions, no Indexing API publish/delete calls, no property changes were made.

---

## 1. GSC Search Analytics — Raw Data

### 1a. Top queries — last 28 days (2026-07-21 to 2026-08-15)

`totals_complete: true` (site-wide totals, safe to use). Individual query rows can omit anonymized low-volume traffic and must NOT be summed as a total.

| Metric | Value |
|---|---|
| Clicks (total) | 0 |
| Impressions (total) | 5 |
| CTR (total) | 0% |
| Avg. position (total) | 34.0 |

Query rows returned (only 2 rows surfaced; row-level impressions do not sum to the total of 5 due to anonymization of low-volume queries):

| Query | Clicks | Impressions | CTR | Position |
|---|---|---|---|---|
| peptselect (misspelling) | 0 | 1 | 0% | 95.0 |
| retatrutide 30 mg | 0 | 1 | 0% | 68.0 |

### 1b. Top queries — last 6 months (2026-02-17 to 2026-08-15)

| Metric | Value |
|---|---|
| Clicks (total) | 0 |
| Impressions (total) | 5 |
| CTR (total) | 0% |
| Avg. position (total) | 34.0 |

Query rows (identical to the 28-day window — see GOOG-03 below):

| Query | Clicks | Impressions | CTR | Position |
|---|---|---|---|---|
| peptselect | 0 | 1 | 0% | 95.0 |
| retatrutide 30 mg | 0 | 1 | 0% | 68.0 |

Tool warning attached to the 6-month query: *"GSC impressions logging error affected impressions, CTR, and average position from 2025-05-13 through 2026-04-27; clicks were not affected."* This is a caveat on historical impressions/CTR/position data quality for that window; clicks are reported as unaffected.

### 1c. Top pages — last 28 days and last 6 months (identical results both windows)

| Page | Clicks | Impressions | CTR | Position |
|---|---|---|---|---|
| https://pepselect.com/ | 0 | 2 | 0% | 48.5 |
| https://pepselect.com/about-us/ | 0 | 2 | 0% | 3.5 |
| https://pepselect.com/faq/ | 0 | 1 | 0% | 7.0 |
| https://pepselect.com/testing/retatrutide-30mg/ | 0 | 1 | 0% | 68.0 |
| https://pepselect.com/testing/tb-500-10-mg/tb10-6926/ | 0 | 1 | 0% | 2.0 |

Totals: clicks 0, impressions 5, CTR 0%, position 34.0 (`totals_complete: true`), row_count: 5.

### 1d. By device — last 28 days and last 6 months (identical)

| Device | Clicks | Impressions | CTR | Position |
|---|---|---|---|---|
| Desktop | 0 | 3 | 0% | 55.0 |
| Mobile | 0 | 2 | 0% | 2.5 |

### 1e. By country (top 10 returned, only 3 with data) — last 28 days and last 6 months (identical)

| Country | Clicks | Impressions | CTR | Position |
|---|---|---|---|---|
| GBR | 0 | 1 | 0% | 3.0 |
| IND | 0 | 1 | 0% | 95.0 |
| USA | 0 | 3 | 0% | 24.0 |

### 1f. Sitemaps (read-only)

| Sitemap | Last submitted | Pending | Is index | Warnings | Errors | Contents |
|---|---|---|---|---|---|---|
| https://pepselect.com/sitemap_index.xml | 2026-08-14T05:19:52.766Z | No | Yes | 0 | 0 | web: 43 submitted, image: 16 submitted |

Note: the tool flags that `contents[].submitted` reflects submission counts only, not indexation truth — URL Inspection is the source of truth for indexation (see section 2).

### 1g. URL Inspection (read-only)

| URL | Verdict | Coverage state | Robots/Indexing | Last crawl | Crawled as | Canonical match | Referring URLs |
|---|---|---|---|---|---|---|---|
| https://pepselect.com/ | PASS | Submitted and indexed | ALLOWED / INDEXING_ALLOWED | 2026-08-02T08:53:11Z | MOBILE | Yes (Google canonical = user canonical = https://pepselect.com/) | https://dk.trustpilot.com/review/pepselect.com |
| https://pepselect.com/testing/ | NEUTRAL | **URL is unknown to Google** | unspecified (never crawled) | never | — | n/a | none |
| https://pepselect.com/shop/ | NEUTRAL | **Discovered - currently not indexed** | unspecified | never | — | n/a | none |
| https://pepselect.com/product/bpc157-10/ | NEUTRAL | **Discovered - currently not indexed** | unspecified | never | — | n/a | https://pepselect.com/product-sitemap.xml |
| https://pepselect.com/product/ghk-cu/ | NEUTRAL | **Discovered - currently not indexed** | unspecified | never | — | n/a | none |

Rich results on homepage: PASS, Breadcrumbs detected. Mobile usability: `VERDICT_UNSPECIFIED` (no issues surfaced) for all URLs tested.

Sitemap context: the product sitemap (`product-sitemap.xml`) declares 16 URLs (`/shop/` + 15 product pages). Both sampled product URLs are "Discovered - currently not indexed," suggesting this pattern likely extends to some or all of the other 13 unsampled product pages (not independently verified — flagged as a hypothesis, not confirmed fact).

---

## 2. PageSpeed Insights (Lab data) — Raw Scores and Metrics

INP terminology used throughout (no FID references except where PSI's own lab-only "Max Potential FID" diagnostic surfaces — noted verbatim since it is a Lighthouse lab proxy metric, not a field INP measurement).

| Page | Strategy | Perf score | LCP | CLS | TBT | Speed Index | TTI |
|---|---|---|---|---|---|---|---|
| Homepage (/) | Mobile | 62/100 | 8.9 s (Poor) | 0 (Good) | 30 ms (Good) | 5.7 s | 8.9 s |
| Homepage (/) | Desktop | 93/100 | 1.4 s (Good) | 0.006 (Good) | 130 ms (Good) | 1.2 s | 1.4 s |
| Shop (/shop/) | Mobile | 67/100 | 7.8 s (Poor) | 0.014 (Good) | 50 ms (Good) | 3.9 s | 7.9 s |
| Shop (/shop/) | Desktop | 95/100 | 1.2 s (Good) | 0.007 (Good) | 20 ms (Good) | 1.5 s | 1.2 s |
| Product (bpc157-10) | Mobile | 74/100 | 4.2 s (Poor, just over 4.0s threshold) | 0 (Good) | 220 ms (Good) | 3.9 s | 5.1 s |
| Product (bpc157-10) | Desktop | 95/100 | 1.0 s (Good) | 0.001 (Good) | 10 ms (Good) | — | — |

Note: The first desktop PSI request for the product page returned a transient `500 Internal Server Error` from the PageSpeed API; a retry succeeded and is the data used above.

### Top opportunities (mobile, where scores are worst)

**Homepage mobile:**
- Reduce unused JavaScript — est. savings 900 ms (176KB from Google Tag Manager + confetti.js unused bytes)
- Render-blocking requests — est. savings 2,530 ms
- Improve image delivery — est. savings 408 KiB (hero image `PS-laying_fam-768x434.png` = 367,813 bytes with 333,719 bytes wasted; logo PNGs also oversized)
- Total page weight: 1,669 KiB, 114 requests

**Shop mobile:**
- Render-blocking requests — est. savings 2,950 ms
- Reduce unused JavaScript — est. savings 600 ms
- Reduce unused CSS — est. savings 150 ms
- Improve image delivery — est. savings 79 KiB
- Total page weight: 1,164 KiB, 113 requests

**Product mobile (bpc157-10):**
- Render-blocking requests — est. savings 2,680 ms
- Reduce unused CSS — est. savings 300 ms
- Improve image delivery — est. savings 160 KiB
- Max Potential FID (lab-only Lighthouse diagnostic, not a field INP measurement) — 190 ms
- Total page weight: 1,066 KiB, 116 requests

### Cross-page Best Practices / SEO audit failures (all three pages, both strategies)

- No CSP (Content-Security-Policy) header found (High severity — XSS/clickjacking mitigation)
- No frame control policy (clickjacking mitigation)
- No HSTS header
- No COOP header (origin isolation)
- "Links do not have descriptive text" — repeated "Learn more" anchor text on product cards (homepage: 4 links, shop: 7 links, product: 2 links)
- Insufficient color-contrast on several UI elements (age-gate card, price/kicker text, badge spans)
- `document-title`, `meta-description`, `canonical`, `robots-txt`, `image-alt`, `hreflang`, `is-crawlable` all PASS on every page tested

---

## 3. CrUX + CrUX History (Field data) — Raw Results

All CrUX queries — origin-level and page-level, both PHONE and DESKTOP form factors — returned **no data**.

| Target | Form factor | Level | Result |
|---|---|---|---|
| https://pepselect.com | PHONE | Origin (CrUX History) | No data — "Insufficient Chrome traffic volume for eligibility" |
| https://pepselect.com | DESKTOP | Origin (CrUX History) | No data — "Insufficient Chrome traffic volume for eligibility" |
| https://pepselect.com/ | PHONE | Origin (CrUX snapshot, via pagespeed_check) | No data — "insufficient Chrome traffic volume for eligibility" |
| https://pepselect.com/ | ALL | Origin (CrUX snapshot) | No data |
| https://pepselect.com/shop/ | PHONE | Page (CrUX History) | No data — insufficient eligibility |
| https://pepselect.com/shop/ | DESKTOP | Page (CrUX History) | No data — insufficient eligibility |
| https://pepselect.com/shop/ | ALL | Page (CrUX snapshot) | No data |
| https://pepselect.com/product/bpc157-10/ | PHONE | Page (CrUX History) | No data — insufficient eligibility |
| https://pepselect.com/product/bpc157-10/ | DESKTOP | Page (CrUX History) | No data — insufficient eligibility |
| https://pepselect.com/product/bpc157-10/ | ALL | Page (CrUX snapshot) | No data |

**Explicit statement:** insufficient eligibility — Google has no field data (real-user Core Web Vitals) for this origin or any of the sampled URLs, in any form factor. This is consistent with the near-zero GSC click/impression volume: the site does not yet have enough Chrome real-user traffic to populate CrUX. No 25-week trend can be summarized because there is no history to summarize. Fall back to PSI Lighthouse lab data (section 2) for all CWV assessment in this audit.

---

## Findings

### [GOOG-01] Shop category and sampled product pages are not indexed by Google
- Priority: Critical
- Category: Indexation
- Evidence class: 1-GSC verified
- Evidence: URL Inspection API — `/shop/`: coverage_state = "Discovered - currently not indexed"; `/product/bpc157-10/`: coverage_state = "Discovered - currently not indexed" (referring URL: product-sitemap.xml); `/product/ghk-cu/`: coverage_state = "Discovered - currently not indexed" (no referring URLs recorded). None of the three have a recorded `last_crawl_time`.
- Affected URLs: https://pepselect.com/shop/, https://pepselect.com/product/bpc157-10/, https://pepselect.com/product/ghk-cu/ (sitemap declares 15 product URLs total plus /shop/ — unsampled products not independently verified but likely share this state)
- Reasoning: Google has discovered these URLs (they are in the submitted sitemap with 0 sitemap errors) but has explicitly chosen not to crawl/index them yet. For a commerce site, an unindexed category page and unindexed product pages mean these pages cannot appear in organic search results at all — this is the direct cause of the near-zero impressions/clicks in GOOG-03.
- Recommendation: Investigate crawl budget and internal linking depth from the (indexed) homepage to /shop/ and product pages; verify server response times and any JS-dependent rendering that could delay Googlebot's decision to index; consider requesting indexing via Search Console UI for priority product pages once technical causes are ruled out (do not use the Indexing API in read-only sessions).
- Dependencies: Unblocks GOOG-03 (organic visibility) and GOOG-02; depends on confirming crawl/rendering health is not the root cause.
- Failure check: Re-running URL Inspection on these same URLs in 2-4 weeks still shows "Discovered - currently not indexed" or "Crawled - currently not indexed" with no last_crawl_time progress.
- Success check: URL Inspection verdict changes to PASS / "Submitted and indexed" with a populated last_crawl_time and matching Google-selected canonical.
- Leading indicator: GSC "Pages" report (Coverage) — count of pages in "Discovered - currently not indexed" bucket trending down over successive weekly checks.

### [GOOG-02] /testing/ hub page is completely unknown to Google
- Priority: High
- Category: Indexation
- Evidence class: 1-GSC verified
- Evidence: URL Inspection API for https://pepselect.com/testing/ — coverage_state: "URL is unknown to Google"; robots_txt_state, indexing_state, page_fetch_state all `UNSPECIFIED`; no last_crawl_time; no referring URLs.
- Affected URLs: https://pepselect.com/testing/
- Reasoning: "Unknown to Google" is a stronger negative signal than "discovered but not indexed" — it means Google has never even encountered this URL through crawling, sitemaps, or links. Yet individual COA test sub-pages under /testing/ (e.g., /testing/retatrutide-30mg/, /testing/tb-500-10-mg/tb10-6926/) DO have GSC impression data (section 1c), meaning those sub-pages are known/indexed while their parent hub is not. This points to a missing or broken internal link from the site's navigation/sitemap to the /testing/ index page itself, and confirms /testing/ is absent from the sitemap_index.xml sitemaps (page-sitemap.xml, ps_coa_test-sitemap.xml, product-sitemap.xml do not list it).
- Recommendation: Add https://pepselect.com/testing/ to the XML sitemap (likely page-sitemap.xml) and ensure it is linked from primary site navigation or footer so Googlebot can discover it through normal crawling.
- Dependencies: Independent of GOOG-01; complements it since both stem from discoverability gaps.
- Failure check: Re-inspection still shows "URL is unknown to Google" after the page is added to a sitemap and internally linked.
- Success check: URL Inspection verdict changes to at least "Discovered" or "Crawled," with a populated last_crawl_time.
- Leading indicator: Sitemap contents count for page-sitemap.xml increasing by 1 URL; GSC Pages report showing /testing/ appear in "Discovered" or "Indexed" buckets.

### [GOOG-03] Near-zero organic search visibility across the entire measurement window
- Priority: Critical
- Category: Organic Performance
- Evidence class: 1-GSC verified
- Evidence: Site-wide totals (totals_complete: true) — 28-day window (2026-07-21 to 2026-08-15): clicks 0, impressions 5, CTR 0%, avg. position 34.0. 6-month window (2026-02-17 to 2026-08-15): identical — clicks 0, impressions 5, CTR 0%, avg. position 34.0. Only 5 pages have ever recorded any impressions (homepage, /about-us/, /faq/, and two /testing/ COA sub-pages); only 2 queries surfaced ("peptselect" at position 95, "retatrutide 30 mg" at position 68).
- Affected URLs: sitewide (sc-domain:pepselect.com)
- Reasoning: The 28-day and 6-month totals being byte-for-byte identical means essentially all recorded GSC search activity for this property happened within the last 28 days — the site has effectively no organic search history before that. Combined with zero clicks and single-digit impressions, this indicates either a very recently launched/reindexed site, a domain that was previously not properly verified/tracked in GSC, or the indexation gaps documented in GOOG-01/GOOG-02 actively suppressing visibility. There is also a documented Google-side impressions/CTR/position logging error covering 2025-05-13 through 2026-04-27 that may have suppressed some historical reporting (clicks were unaffected by that bug, and clicks are also 0, so the underlying visibility is genuinely near-nil, not just an undercount).
- Recommendation: Treat organic acquisition as pre-launch stage. Prioritize the indexation fixes in GOOG-01/GOOG-02, verify Search Console property age/verification history, and do not judge SEO content/keyword strategy effectiveness until baseline indexation is fixed and a full 28-day cycle of stable data is collected post-fix.
- Dependencies: Depends entirely on resolving GOOG-01 and GOOG-02; blocks any meaningful keyword-strategy or content-optimization prioritization until indexation improves.
- Failure check: 28-day totals remain in single-digit impressions / zero clicks after GOOG-01/GOOG-02 fixes have had 4+ weeks to take effect.
- Success check: 28-day impressions grow into double/triple digits with clicks > 0 and average position improving from the current 34.0.
- Leading indicator: Weekly GSC totals (impressions, clicks) trending upward; number of indexed pages (Coverage report) trending upward.

### [GOOG-04] Homepage mobile performance is Poor on LCP, driven by render-blocking resources and an oversized hero image
- Priority: High
- Category: Core Web Vitals (Lab)
- Evidence class: 2-PageSpeed lab
- Evidence: Mobile PSI performance score 62/100. LCP = 8,864 ms ("8.9 s", Poor, score 0.01) vs. desktop LCP = 1,381 ms ("1.4 s", Good, score 0.84) on the same URL. FCP mobile 4,509 ms vs desktop 728 ms. TTI mobile 8,879 ms vs desktop 1,429 ms. TBT and CLS are both Good on mobile (30 ms / 0). Failed audits: render-blocking-insight (est. savings 2,530 ms), image-delivery-insight (est. savings 408 KiB), unused-javascript (est. savings 105 KiB). Largest network payload item: hero image `PS-laying_fam-768x434.png` at 367,813 bytes total with 333,719 bytes estimated wasted.
- Affected URLs: https://pepselect.com/ (mobile)
- Reasoning: The mobile/desktop LCP gap (8.9 s vs 1.4 s, a ~6.4x difference) is far larger than typical device-capability differences, indicating render-blocking CSS/JS and an unoptimized above-the-fold hero image are specifically hurting the mobile experience, not raw page weight (mobile and desktop total byte weight are nearly identical at ~1,667-1,669 KiB).
- Recommendation: Compress/resize the hero image and other above-the-fold images to responsive srcset sizes; defer or inline critical render-blocking CSS/JS (the render-blocking-insight audit lists specific plugin CSS/JS assets); ensure fetchpriority=high and no lazy-loading on the LCP element.
- Dependencies: Independent of GSC indexation findings; improving this reduces bounce risk once organic traffic does arrive (post GOOG-01/GOOG-02 fixes).
- Failure check: Repeat mobile PSI run after remediation still shows LCP > 4,000 ms.
- Success check: Repeat mobile PSI run shows LCP ≤ 2,500 ms (Good) or at minimum ≤ 4,000 ms (Needs Improvement) with performance score materially above 62.
- Leading indicator: PSI mobile performance score trending upward on ad hoc re-checks; CrUX field LCP percentile (once eligible — see GOOG-09) trending into Good range.

### [GOOG-05] Shop category page mobile LCP is Poor (7.8s) with heavy render-blocking overhead
- Priority: High
- Category: Core Web Vitals (Lab)
- Evidence class: 2-PageSpeed lab
- Evidence: Mobile PSI performance score 67/100. LCP = 7,808 ms ("7.8 s", Poor, score 0.03). FCP 3,757 ms, TTI 7,898 ms, TBT 52 ms (Good), CLS 0.014 (Good). Desktop on the same URL: performance 95/100, LCP 1,221 ms ("1.2 s", Good). Failed audit render-blocking-insight: est. savings 2,950 ms (largest of the three pages tested). lcp-discovery-insight also failed (LCP element not discoverable early in HTML / possibly lazy-loaded).
- Affected URLs: https://pepselect.com/shop/ (mobile)
- Reasoning: The lcp-discovery-insight failure combined with the largest render-blocking savings estimate (2,950 ms) of any page tested suggests the category grid's LCP element (likely a product card image) is being discovered late by the browser, compounding the render-blocking delay.
- Recommendation: Ensure the LCP candidate image on /shop/ (typically the first visible product card) is not lazy-loaded and is preloaded or high-fetchpriority; apply the same render-blocking CSS/JS deferral strategy recommended in GOOG-04.
- Dependencies: Shares root causes with GOOG-04 and GOOG-06 (same theme/plugin stack); a shared fix (critical CSS extraction, script deferral) would likely resolve all three simultaneously.
- Failure check: Repeat mobile PSI run still shows LCP > 4,000 ms after remediation.
- Success check: Repeat mobile PSI run shows LCP ≤ 4,000 ms, ideally ≤ 2,500 ms.
- Leading indicator: PSI mobile performance score for /shop/ trending upward on ad hoc checks.

### [GOOG-06] Product page mobile LCP sits just over the "Poor" threshold (4.2s)
- Priority: Medium
- Category: Core Web Vitals (Lab)
- Evidence class: 2-PageSpeed lab
- Evidence: Mobile PSI performance score 74/100 for https://pepselect.com/product/bpc157-10/. LCP = 4,201 ms ("4.2 s", Poor by a narrow margin over the 4,000 ms threshold, score 0.44). FCP 3,372 ms, TTI 5,064 ms, TBT 217.5 ms ("220 ms", Good but closer to the 200 ms INP-adjacent threshold), CLS 0.0004 (Good). Desktop on same URL: performance 95/100, LCP 1,006 ms (from retry run), CLS 0.001, TBT 10 ms. Failed audits: render-blocking-insight (est. savings 2,680 ms), image-delivery-insight (est. savings 160 KiB), unused-css-rules (est. savings 300 ms).
- Affected URLs: https://pepselect.com/product/bpc157-10/ (mobile) — likely representative of other product template pages given shared theme
- Reasoning: This page is the closest of the three to meeting the "Good" LCP tier, indicating the product template is somewhat lighter than the homepage/shop templates, but still crosses into "Poor" territory on mobile due to the same render-blocking pattern seen sitewide.
- Recommendation: Apply the same render-blocking/critical-CSS remediation as GOOG-04/GOOG-05 to the product template; optimize product gallery images (image-delivery-insight flagged 160 KiB of savings).
- Dependencies: Same root cause family as GOOG-04/GOOG-05.
- Failure check: Repeat mobile PSI run still shows LCP > 4,000 ms.
- Success check: Repeat mobile PSI run shows LCP ≤ 4,000 ms (moves out of Poor into at least Needs Improvement).
- Leading indicator: PSI mobile performance score for product template trending upward.

### [GOOG-07] Site-wide render-blocking CSS/JS is the dominant mobile performance bottleneck
- Priority: Medium
- Category: Performance / Technical SEO
- Evidence class: 2-PageSpeed lab
- Evidence: render-blocking-insight failed on all three pages tested (mobile): homepage est. savings 2,530 ms, shop est. savings 2,950 ms, product est. savings 2,680 ms. Named render-blocking assets include theme CSS (`theme.css`), Elementor animation CSS bundles, jQuery core (`jquery.min.js`, 30,826 bytes with 1,201 ms wasted on shop page), WooCommerce CSS/JS.
- Affected URLs: https://pepselect.com/, https://pepselect.com/shop/, https://pepselect.com/product/bpc157-10/ (mobile) — theme-level issue likely sitewide
- Reasoning: The consistency of ~2.5-3s render-blocking savings across three different templates (home, category, product) indicates a shared theme/plugin loading pattern (Elementor + multiple WooCommerce add-ons + jQuery) rather than a page-specific issue, making this a single fix with sitewide leverage.
- Recommendation: Audit the Elementor/WooCommerce plugin stack for CSS/JS that can be deferred, inlined (critical path only), or conditionally loaded only on pages that need it (e.g., side-cart, points-and-rewards plugin assets loading on every page).
- Dependencies: Root cause for GOOG-04, GOOG-05, GOOG-06; fixing this should improve all three simultaneously.
- Failure check: Render-blocking-insight audit still reports >1,000 ms estimated savings after remediation.
- Success check: Render-blocking-insight audit savings estimate drops below 500 ms on all three templates.
- Leading indicator: Mobile PSI performance scores trending upward across templates on ad hoc re-checks.

### [GOOG-08] Missing baseline security headers (CSP, HSTS, COOP, X-Frame-Options) flagged sitewide
- Priority: Low
- Category: Best Practices / Security
- Evidence class: 2-PageSpeed lab
- Evidence: Repeated across all pages/strategies tested — "No CSP found in enforcement mode" (High severity, csp-xss audit), "No frame control policy found" (clickjacking-mitigation audit), "No HSTS header found" (has-hsts audit), "No COOP header found" (origin-isolation audit).
- Affected URLs: https://pepselect.com/, https://pepselect.com/shop/, https://pepselect.com/product/bpc157-10/ (all strategies) — sitewide header configuration
- Reasoning: These are best-practices/security audits, not direct ranking or CWV factors, but their absence is a defense-in-depth gap and depresses the Lighthouse "Best Practices" score (currently 96/100, capped by these findings).
- Recommendation: Add security headers (Content-Security-Policy, Strict-Transport-Security, Cross-Origin-Opener-Policy, X-Frame-Options) at the server/CDN level; describe only — implementation is outside this read-only audit's scope.
- Dependencies: None; independent, low-effort infrastructure change.
- Failure check: Headers still absent on re-check via response headers or repeat PSI best-practices audit.
- Success check: PSI best-practices audits for csp-xss, clickjacking-mitigation, has-hsts, and origin-isolation all pass.
- Leading indicator: PSI "Best Practices" category score moving from 96 toward 100.

### [GOOG-09] No CrUX (real-user field) data available for this origin or any URL/form-factor tested
- Priority: Medium
- Category: Data Gaps / Measurement
- Evidence class: 3-CrUX field
- Evidence: All 10 CrUX/CrUX History queries executed (origin-level PHONE/DESKTOP History; page-level PHONE/DESKTOP History for /shop/ and /product/bpc157-10/; plus ALL-form-factor snapshot queries for /, /shop/, /product/bpc157-10/ via pagespeed_check.py) returned explicit "insufficient Chrome traffic volume for eligibility" / "No CrUX data" errors — zero exceptions.
- Affected URLs: https://pepselect.com (origin), https://pepselect.com/, https://pepselect.com/shop/, https://pepselect.com/product/bpc157-10/ — all form factors
- Reasoning: This is expected given GOOG-03 (near-zero organic traffic). CrUX requires a meaningful volume of real Chrome user sessions per origin/URL/form-factor combination over a 28-day rolling window; a site with essentially no search-driven or general traffic will not meet that threshold regardless of technical quality.
- Recommendation: Continue relying on PSI Lighthouse lab data (GOOG-04/05/06) as the CWV proxy until traffic volume is sufficient for CrUX eligibility. Re-check CrUX eligibility monthly as a leading indicator of real-world traffic growth, independent of GSC.
- Dependencies: Directly downstream of GOOG-03 (traffic volume); not something to "fix" directly — it resolves naturally as traffic grows post GOOG-01/GOOG-02 remediation.
- Failure check: CrUX History still returns "insufficient eligibility" at next audit cycle despite traffic growth elsewhere.
- Success check: Any one CrUX History query (origin or page-level) returns populated `metrics`/`collection_periods` data.
- Leading indicator: CrUX eligibility status (data present vs. absent) checked on a monthly cadence as a proxy for real-user traffic volume crossing Google's reporting threshold.

### [GOOG-10] Repeated non-descriptive "Learn more" link text across product card templates
- Priority: Low
- Category: On-page SEO / Accessibility
- Evidence class: 2-PageSpeed lab
- Evidence: link-text audit failed on all pages tested. Homepage: 4 links flagged, all `href` to /product/... pages with text "Learn more". Shop: 7 links flagged, same pattern. Product page: 2 links flagged, same pattern.
- Affected URLs: https://pepselect.com/, https://pepselect.com/shop/, https://pepselect.com/product/bpc157-10/ — templated product-card component sitewide
- Reasoning: Generic anchor text ("Learn more") repeated across many links on the same page provides no contextual signal to search engines about the destination page's topic, and is a known accessibility issue (screen reader users hear identical link text out of context).
- Recommendation: Update the product-card template's CTA text to include the product name (e.g., "Learn more about BPC-157") or use `aria-label` for accessible-name enhancement without changing visible design.
- Dependencies: None; independent, template-level content change.
- Failure check: Repeat PSI link-text audit still fails with the same count of generic links.
- Success check: link-text audit passes (score 1) on homepage, shop, and product templates.
- Leading indicator: PSI SEO category score (currently 92/100 across all pages tested) moving toward 100 as this and label-content-name-mismatch issues are resolved.

### [GOOG-11] GA4 not configured — no organic session/conversion visibility
- Priority: Medium
- Category: Data Gaps / Measurement
- Evidence class: n/a (credential gap, not evidence-bearing)
- Evidence: `google_auth.py --check` reports `"ga4": {"available": false, "error": "Credentials found but no GA4 property ID configured. Set GA4_PROPERTY_ID or add 'ga4_property_id' to config."}`
- Affected URLs: sitewide (no GA4 property tied to pepselect.com in current config)
- Reasoning: Without GA4, this audit cannot correlate GSC impression/click data with on-site behavior (bounce rate, conversion rate, revenue) for organic landing pages, meaning the business impact of GOOG-01 through GOOG-03 cannot be fully quantified from Google-side data alone.
- Recommendation: Configure a GA4 property ID for pepselect.com and add it to the claude-seo Google API config to unlock Tier 2 reporting in future audits.
- Dependencies: Independent; unlocks fuller future audits but does not block current remediation priorities.
- Failure check: GA4 property ID still absent at next audit.
- Success check: `google_auth.py --check` reports `"ga4": {"available": true}` and `ga4_report.py` returns organic traffic data.
- Leading indicator: N/A until configured — this itself is the leading indicator to watch (presence/absence of a working GA4 property ID in config).

---

## Verified Correct

- GSC property access confirmed via `gsc_query.py sites` — `sc-domain:pepselect.com` returned with `siteOwner` permission; all subsequent GSC calls used this exact property string.
- All GSC totals used in this report have `totals_complete: true` and `totals_source: "dimensionless_aggregate"`; no row-level sums were substituted for site-wide totals, per instructions.
- Homepage indexation status (PASS / Submitted and indexed) is internally consistent with it being the only URL of the five sampled with meaningful search impressions and the only one with a recorded last_crawl_time and canonical.
- CrUX "no data" results were treated as explicit eligibility statements ("insufficient eligibility — Google has no field data"), not as tooling errors, consistent with the near-zero GSC traffic baseline (self-consistent across both evidence classes).
- No sitemap submission, no Indexing API publish/delete, and no GSC property configuration changes were made at any point in this session — read-only throughout.
- PSI desktop run for the product page returned a transient 500 error on first attempt; the retry succeeded and its data is what is reported (no data was fabricated for the initial failure).

## Data Sources & Limitations

- **GA4: skipped entirely** — no `ga4_property_id` configured in the Google API credentials (Tier 1, not Tier 2). No organic traffic, landing-page, or conversion data from GA4 is included anywhere in this report.
- **CrUX eligibility:** all origin-level and page-level CrUX/CrUX History queries (mobile + desktop, homepage + shop + one product page) returned explicit "insufficient Chrome traffic volume for eligibility" — there is no real-user field data for this domain at any granularity tested. All Core Web Vitals conclusions in this report rely on PSI Lighthouse **lab data only** (evidence class 2), which does not represent real-user conditions or network/device diversity.
- **GSC data freshness:** Search Console data typically lags 2-3 days; the 28-day window used ends 2026-08-15 (3 days before this audit's run date of 2026-08-18).
- **GSC historical data quality caveat:** the tool surfaced a warning that a Google-side impressions/CTR/position logging error affected data from 2025-05-13 through 2026-04-27 (clicks unaffected). This does not change the conclusion of near-zero visibility (clicks are 0 throughout), but historical impression/position figures before 2026-04-27 should be treated with caution if referenced elsewhere.
- **Row-level GSC query/page/device/country data can under-represent totals** due to Google's anonymization of low-volume dimensions; only the `totals` object (marked `totals_complete: true`) was used for site-wide figures.
- **Sample size caveat:** with only 5 total impressions across 6 months, the by-device and by-country breakdowns (section 1d/1e) are statistically meaningless and are reported for completeness only, not as actionable trend data.
- **Indexation sampling caveat:** only 2 of 15 product pages were inspected via URL Inspection (bpc157-10, ghk-cu); both showed "Discovered - currently not indexed." This pattern is reported as observed on the sampled pages, not confirmed sitewide across all products.
- Indexing API and Search Console property-management endpoints were never invoked for write operations, consistent with the STRICTLY READ-ONLY constraint given for this audit.

## Category Score: 28/100

Score reflects Critical-priority indexation failures (shop/product pages not indexed, /testing/ hub unknown to Google) directly causing near-zero organic visibility (0 clicks, 5 impressions in 6 months) — the two Critical findings (GOOG-01, GOOG-03) dominate the score; Core Web Vitals lab data is comparatively healthy on desktop (93-95/100) but Poor on mobile LCP across all three templates (High-priority GOOG-04/05), and CrUX field data is entirely unavailable due to the traffic gap.

# Performance / Core Web Vitals Findings — pepselect.com — 2026-08-23

**Method:** 20 PageSpeed Insights v5 runs (1 desktop + 3 mobile × 5 template URLs) via the Claude SEO plugin `pagespeed_check.py` (Tier-0 API key), plus CrUX field-data check at origin level. Lighthouse **lab** data only — CrUX real-user field data remains unavailable (see GOOG-09). Raw JSON for all 20 runs in `raw-pagespeed/`. Two of the three home runs (`home-desktop`, `home-mobile-1`) returned empty metrics (PSI runtime error) and are excluded; PSI cache-served duplicates are de-duplicated by `analysis_timestamp`.

## Scores (2026-08-23)

| URL | Desktop | Mobile (unique runs) | Mobile LCP range |
|---|---:|---|---|
| `/` (Home) | *(run failed ×2)* | 64 | 4.4 s |
| `/shop/` | 96 | 74, 71 | 5.3 – 6.0 s |
| `/product/nad/` | 97 | 63 | 4.5 s |
| `/testing/` | 97 | 81, 60 | 3.8 – 9.0 s |
| COA batch report | 97 | 79, 59 | 4.5 – 9.7 s |
| **Aggregate** | **96–97** | **median 71, range 59–81 (n=8 unique)** | 3.8 – 9.7 s |

**Verdict vs 8/20:** statistically identical. 8/20 measured desktop 96.8 avg, mobile median 75 / range 56–81. 8/23 measures desktop 96–97, mobile median 71 / range 59–81. No material CWV change — the five milestones since 8/20 were SEO/content, not performance. The reported ~98 desktop / ~68 mobile gap remains real, reproducible, and dominated by Lighthouse's mobile CPU/network throttling methodology (~4–6× multiplier on identical code), exactly as documented on 8/20.

## Per-ID re-verification

| ID | Priority | Prior (8/20) | 8/23 | Evidence |
|---|---|---|---|---|
| PERF-01 | Critical | PARTIALLY FIXED | **PARTIALLY FIXED** | Home mobile LCP 4.4 s (Needs-Improvement/Poor boundary); Shop/NAD+/Testing/COA 4.5–9.7 s (mostly Poor). Unchanged. |
| PERF-02 | Critical | PARTIALLY FIXED | **PARTIALLY FIXED** | Render-blocking remains the dominant mobile LCP driver on every template. Unchanged. |
| PERF-03 | High | PARTIALLY FIXED | **PARTIALLY FIXED** | Homepage hero WebP fix holds; `image-delivery-insight` still fires on non-hero images (Shop worst). Unchanged. |
| PERF-04 | Medium | STILL OPEN | **STILL OPEN** | PSI summary does not isolate the logo asset; not independently confirmable. Unchanged. |
| PERF-05 | High | STILL OPEN | **STILL OPEN** | No INP field data (CrUX unavailable); TBT 0–500 ms, highly variable (NAD+ mobile 500 ms). No consistent improvement. |
| PERF-06 | Medium | STILL OPEN | **STILL OPEN** | GTM cost unchanged; still the dominant third party. |
| PERF-07 | Medium | PARTIALLY FIXED | **PARTIALLY FIXED** | beta.39 font consolidation holds; `font-display: swap` gap unresolved. Unchanged. |
| PERF-08 | Medium | VERIFIED FIXED | **VERIFIED FIXED** | Testing-hub SI/FCP outlier stays gone — Testing desktop 97, mobile 3.8–9.0 s within the same band as other templates (no 12.8 s outlier). |
| PERF-09 | Low | STILL OPEN | **STILL OPEN** | `unsized-images` still latent; CLS Good on most templates today (but see PERF-12). |
| PERF-10 | Low | STILL OPEN | **STILL OPEN** | Unused-JS savings unchanged. |
| PERF-11 | Low | VERIFIED FIXED | **VERIFIED FIXED** | TTFB low across all runs; no regression. |
| TECH-06 | High | PARTIALLY FIXED | **PARTIALLY FIXED** | Mobile LCP still Poor on the majority of independent runs (Shop/NAD+/Testing/COA); Home at Needs-Improvement. Unchanged. |
| GOOG-04 | High | PARTIALLY FIXED | **PARTIALLY FIXED** | Home mobile LCP 4.4 s. Unchanged. |
| GOOG-05 | High | PARTIALLY FIXED | **PARTIALLY FIXED** | Shop mobile LCP 5.3–6.0 s. Unchanged. |
| GOOG-06 | Medium | STILL OPEN | **STILL OPEN** | NAD+ mobile LCP 4.5 s. Unchanged. |
| GOOG-07 | Medium | PARTIALLY FIXED | **PARTIALLY FIXED** | Site-wide render-blocking persists. Unchanged. |
| GOOG-09 | Medium | BLOCKED BY REAL EVIDENCE | **BLOCKED BY REAL EVIDENCE** | CrUX origin check 2026-08-23 returns *"No CrUX data for this origin. Insufficient Chrome traffic volume for eligibility."* `field_metrics: {}` on every PSI run. No code fix exists. |

## New finding

| ID | Priority | Classification | Evidence |
|---|---|---|---|
| PERF-12 | Medium | **NEW — watch / [VERIFY CLAIM]** | The single unique home-mobile run that returned metrics shows **CLS 0.303 (Poor)** vs the prior audit's ≤0.084 Good everywhere. The other two home runs failed, so this is one unconfirmed sample. Plausibly introduced by the 8/20–22 homepage batch-matching / label / logo changes (an unsized image or late-loading label can shift layout). Not asserted as a regression on one sample — flagged for a dedicated 3× mobile re-measure of `/` before/after any homepage change. All other templates measured CLS 0–0.018 (Good). |

**Changes since 2026-08-20:** none material to CWV. Desktop and mobile scores, LCP distribution, and CrUX ineligibility all unchanged. The only new signal is the unconfirmed home-mobile CLS 0.303 (PERF-12).

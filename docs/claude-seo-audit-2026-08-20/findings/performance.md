# Performance Findings — pepselect.com — 2026-08-20

**Method:** PageSpeed Insights API (Lighthouse 13.x, `--psi-only`), Tier-1 authenticated key. Desktop = 1 run per URL. Mobile = 3 runs per URL, requested back-to-back. Raw JSON: `docs/claude-seo-audit-2026-08-20/raw-pagespeed/*.json` (20 files). CrUX origin check: `error: "No CrUX data for this origin. The site likely has insufficient Chrome traffic volume for eligibility."`

**Live theme at time of test:** `0.25.0-beta.39` (render-path cleanup: Google Fonts consolidation, inlined shell CSS, route-specific unused-style removal on Quality Archive only — per `STAGING-SEO-PERFORMANCE-BETA39-2026-08-19.md`).

**Headline finding:** Paulo's reported ~98 desktop / ~68 mobile gap is real and reproducible. Fresh data: desktop 96–98 (tight, stable), mobile 56–81 (wide, unstable). The 68 figure sits inside the observed mobile range, not above or below it — it is very likely a single spot-check that landed on one of the lower-variance mobile runs rather than a stale or anomalous number.

## 1. Score table (all 5 URLs, desktop + mobile)

| URL | Strategy | Runs | Performance score(s) | LCP range | Notes |
|---|---|---|---|---|---|
| Home `/` | Desktop | 1 | 97 | 1.0 s | — |
| Home `/` | Mobile | 3 (identical, PSI-cached) | 81 | 3.8 s | All 3 runs share one timestamp — effectively 1 unique data point |
| Shop `/shop/` | Desktop | 1 | 96 | 1.2 s | — |
| Shop `/shop/` | Mobile | 3 (2 unique) | 81, 81, 67 | 4.2 s – 6.8 s | Run 1=2 cached; run 3 independent and materially worse |
| NAD+ `/product/nad/` | Desktop | 1 | 96 | 0.8 s | — |
| NAD+ `/product/nad/` | Mobile | 3 (2 unique) | 75, 63, 75 | 4.2 s – 7.1 s | Run 1=3 cached; run 2 independent and materially worse |
| Testing hub `/testing/` | Desktop | 1 | 97 | 0.8 s | — |
| Testing hub `/testing/` | Mobile | 3 (all unique) | 76, 66, 56 | 4.2 s – 7.5 s | Largest variance of all 5 URLs — 20-point score swing |
| COA batch report | Desktop | 1 | 98 | 0.9 s | — |
| COA batch report | Mobile | 3 (identical, PSI-cached) | 79 | 4.4 s | All 3 runs share one timestamp — effectively 1 unique data point |

**Desktop average: 96.8** (matches Paulo's reported ~98). **Mobile average across 9 unique runs: 71.6, median 75, range 56–81** (brackets Paulo's reported ~68 at the low end).

## 2. Per-metric detail (FCP / LCP / TBT / CLS / SI / TTFB / transfer / requests)

### Desktop (1 run each — no CPU/network throttling beyond Lighthouse's light default)

| URL | Perf | FCP | LCP | TBT | CLS | SI | TTFB | Transfer | Requests |
|---|---:|---:|---:|---:|---:|---:|---:|---:|---:|
| Home | 97 | 0.7 s | 1.0 s | 100 ms | 0.006 | 1.2 s | 4 ms | 987.4 KiB | 98 |
| Shop | 96 | 0.7 s | 1.2 s | 10 ms | 0.007 | 1.3 s | 7 ms | 1,071.3 KiB | 95 |
| NAD+ | 96 | 0.7 s | 0.8 s | 70 ms | 0.002 | 1.8 s | 79 ms | 982.2 KiB | 100 |
| Testing hub | 97 | 0.7 s | 0.8 s | 100 ms | 0.001 | 1.5 s | 7 ms | 1,207.5 KiB | 66 |
| COA report | 98 | 0.8 s | 0.9 s | 10 ms | 0.001 | 1.3 s | 25 ms | 816.4 KiB | 68 |

All desktop LCP is comfortably "Good" (≤2.5 s). All desktop CLS is "Good" (≤0.1). All desktop TBT low.

### Mobile (3 runs each; run-by-run, not averaged)

| URL / run | Perf | FCP | LCP | TBT | CLS | SI | TTI | TTFB | Transfer | Requests |
|---|---:|---:|---:|---:|---:|---:|---:|---:|---:|---:|
| Home run1=2=3 | 81 | 2.9 s | 3.8 s | 70 ms | 0.084 | 3.5 s | 4.8 s | 104 ms | 992.2 KiB | 96 |
| Shop run1=2 | 81 | 2.9 s | 4.2 s | 40 ms | 0.018 | 2.9 s | 4.7 s | 3 ms | 1,120.0 KiB | 96 |
| Shop run3 | 67 | 4.1 s | 6.8 s | 30 ms | 0 | 4.2 s | 6.8 s | 40 ms | 1,118.9 KiB | 96 |
| NAD+ run1=3 | 75 | 2.9 s | 4.2 s | 270 ms | 0 | 3.5 s | 6.1 s | 16 ms | 950.6 KiB | 101 |
| NAD+ run2 | 63 | 4.4 s | 7.1 s | 30 ms | 0 | 5.8 s | 7.1 s | 19 ms | 952.5 KiB | 101 |
| Testing run1 | 76 | 3.8 s | 4.2 s | 10 ms | 0 | 4.3 s | 4.7 s | 43 ms | 1,156.5 KiB | 62 |
| Testing run2 | 66 | 4.1 s | 6.3 s | 40 ms | 0 | 5.2 s | 6.3 s | 9 ms | 1,157.1 KiB | 62 |
| Testing run3 | 56 | 4.3 s | 7.5 s | 350 ms | 0 | 5.0 s | 7.6 s | 56 ms | 1,160.7 KiB | 62 |
| COA report run1=2=3 | 79 | 3.2 s | 4.4 s | 40 ms | 0 | 3.2 s | 4.8 s | 16 ms | 836.6 KiB | 68 |

**LCP element:** the pre-processed PSI summary format used by `pagespeed_check.py --json` does not include the `largest-contentful-paint-element` audit detail (no `audit_details["largest-contentful-paint-element"]` key in any of the 20 files). This is a tooling data gap, not a page finding — the LCP resource/element cannot be attributed from this dataset without a raw-Lighthouse re-run. Given `image-delivery-insight` fires as a failed audit on every URL/strategy with non-trivial savings, the LCP element is almost certainly an image on most templates, but this is inference, not confirmed evidence.

**CLS:** Good (≤0.1) on every single run, desktop and mobile, 0–0.084. Not a current problem.

**TTFB:** 3–104 ms across all runs, desktop and mobile — well under the 200 ms "good" TTFB threshold on every test. No evidence of a server-response regression.

## 3. Top 5 causes of the mobile-vs-desktop gap

1. **Lighthouse's mobile throttling profile itself (methodological, not a resource-weight issue).** Mobile lab runs apply ~4x CPU slowdown plus a simulated slow-4G network profile; desktop applies minimal throttling. The render-blocking estimate for byte-identical resources swings from **370–610 ms on desktop to 1,650–2,830 ms on mobile** — a 4–6x multiplier that tracks Lighthouse's stated mobile throttle ratio almost exactly. This is the largest single explanatory factor for the score gap and is not something further asset optimization alone can close to zero — it explains why an "already-good" desktop score coexists with a "still-poor" mobile score on identical code.

2. **Render-blocking CSS/JS (still substantial, present on every template).** Mobile `render-blocking-insight` savings: Home 2,320 ms; Shop 2,100–2,370 ms; NAD+ 2,100–2,300 ms; Testing hub 1,650–2,830 ms; COA report 2,450 ms (identical across all 3 cached runs). This is directly responsible for LCP landing in the 3.8–7.5 s mobile range. PERF-02/GOOG-07/TECH-06 territory — beta.39's font consolidation and shell-CSS inlining measurably helped (staging showed 3,150→2,050 ms on Quality Archive) but Live's fresh Testing-hub numbers (1,650–2,830 ms) still bracket the pre-fix staging value, so the improvement is not clearly separable from run-to-run noise on Live yet.

3. **Google Tag Manager (dominant third party, cost scales with CPU throttle).** GTM transfers ~182 KiB on every one of the 5 templates on both strategies (byte-identical), but its main-thread execution cost is 95–285 ms on mobile vs 113–214 ms on desktop, with the highest values appearing on the higher-throttle mobile runs (NAD+ 285 ms, Testing 259 ms). PERF-06, not started — this is a directly actionable, unclaimed opportunity.

4. **Unused JavaScript (byte-weight issue, amplified by mobile CPU throttle).** A consistent 105–106 KiB estimated-savings finding on every URL, both strategies — same bytes shipped regardless of device, but parse/exec time for those bytes is inflated by the 4x mobile CPU throttle. PERF-10, not started.

5. **Image delivery (byte-weight issue, amplified by the mobile network-throttle simulation).** Estimated savings range 49–161 KiB and vary by page — worst on Shop (161 KiB / 82 KiB across runs), best on Testing hub (49–82 KiB). Same underlying images on desktop and mobile; the slower simulated mobile connection converts each KiB into more visible time than it does on desktop's fast simulated connection. PERF-03 fixed the homepage hero specifically (verified 93.7–92.5% size reduction) but the `image-delivery-insight` audit still fires on all 5 templates, meaning other images (not the hero) remain unoptimized.

Font loading (font-display 50–190 ms mobile / 60–180 ms desktop, Google Fonts 133.9–175.5 KiB transfer) was evaluated but excluded from the top 5 — post-beta.39, the values are now similar in magnitude on desktop and mobile and no longer show the "testing hub is uniquely bloated" pattern from the original audit, so this is now a secondary, largely-already-addressed contributor rather than a top driver of the gap.

## 4. Lab vs. field data

- **No CrUX field data exists for this origin.** The orchestrator's origin-level check returned `"error": "No CrUX data for this origin. The site likely has insufficient Chrome traffic volume for eligibility."` This is unchanged from the prior checkpoint (2026-08-19) and confirms **GOOG-09 remains open** — there is still no way to validate any of the above lab numbers against real-user 75th-percentile experience, and Google will not use lab data (Lighthouse/PSI) to evaluate real-world Core Web Vitals status in Search — only CrUX field data counts for that.
- All scores, LCP/CLS/INP-proxy numbers in this report are **lab data only**, from a single (desktop) or triple (mobile) synthetic Lighthouse run per URL, not aggregated real-user sessions.
- INP itself cannot be measured by Lighthouse at all (lab tool, no real interaction stream) — Total Blocking Time (TBT) was used as the standard interactivity proxy per Google guidance, and is reported above, but it is explicitly not INP and should not be presented to Google/stakeholders as an INP figure.
- Until CrUX eligibility is reached (28-day rolling window of sufficient Chrome traffic), Search Console's Core Web Vitals report will keep showing "not enough usage data," as it did on 2026-08-17 in the prior checkpoint.

## 5. Mobile run-to-run variance (do not report a single mobile number as stable)

- PSI served **identical cached results** for all 3 requested mobile runs on Home and COA report (same `analysis_timestamp` for all 3) — meaning only 1 independent measurement exists for those two URLs, not 3. Shop and NAD+ each had 2 of 3 runs cached identically, with 1 independent outlier run. Testing hub was the only URL where all 3 runs were genuinely independent.
- Where independent runs exist, variance is large: Shop 81→67 (LCP 4.2 s→6.8 s), NAD+ 75→63 (LCP 4.2 s→7.1 s), Testing hub 76→66→56 (LCP 4.2 s→6.3 s→7.5 s, a 20-point score swing and a 3.3 s LCP swing across three back-to-back API calls on unchanged code).
- TBT does not move monotonically with the score/LCP swings (e.g., NAD+ run2 has the *lowest* TBT of its two runs, 30 ms, but the *worst* LCP, 7.1 s; Testing run3 has the highest TBT, 350 ms, and the worst LCP, 7.5 s) — this indicates the instability is coming from shared lab-infrastructure contention (CPU/network simulation jitter on Google's PSI test runners), not a single deterministic bottleneck. Any one-off mobile PageSpeed check — including Paulo's reported ~68 — should be read as a sample from a 56–81 range, not a fixed score.
- Practical implication: any future before/after comparison must use ≥3 independent (non-cached) mobile runs per URL and report the range, not a single number, or it will misattribute lab-runner noise to code changes.

## 6. Prior Finding Classifications

| ID | Original Priority | Prior State (8/18) | Current Classification | Evidence |
|---|---|---|---|---|
| PERF-01 | Critical | Live partial | PARTIALLY FIXED | Home mobile LCP moved out of Poor into Needs-Improvement (3.8 s); Shop/NAD+/Testing hub/COA report remain mostly in Poor range across independent runs (4.2–7.5 s). Not all four original templates cleared "Poor." |
| PERF-02 | Critical | Live partial | PARTIALLY FIXED | Mobile render-blocking estimate is now 1,650–2,830 ms (was 2.7–3.0 s / 2,700–3,000 ms in the 8/19 checkpoint) — a real reduction, but still the largest single LCP driver on every template. |
| PERF-03 | High | Live implemented / measurement pending | PARTIALLY FIXED | Hero WebP reduction is verified (93.7%/92.5%), but `image-delivery-insight` still fires with 49–161 KiB savings on all 5 templates — non-hero images remain unoptimized. |
| PERF-04 | Medium | Partially complete | STILL OPEN | This PSI summary format does not isolate individual assets (e.g., the logo) from the aggregate `image-delivery-insight`/resource-summary figures, so the prior "partially complete" state cannot be independently confirmed or refuted from this dataset. |
| PERF-05 | High | Not started | STILL OPEN | No INP field data exists (CrUX unavailable) to assess real interaction cost; TBT (10–350 ms mobile, highly variable) is the only available proxy and shows no consistent improvement. Script/jQuery stack unchanged in this evidence set. |
| PERF-06 | Medium | Not started | STILL OPEN | GTM remains ~182 KiB / 95–285 ms main-thread on every template and both strategies — confirmed present, unchanged, still the dominant third party. |
| PERF-07 | Medium | Not started | PARTIALLY FIXED | Beta.39's Google Fonts consolidation (4 requests → 1, site-wide) is reflected in the data: Testing hub's font transfer (133.9 KiB) is now the *lowest* of the 5 templates, no longer the uniquely bloated outlier the original finding described. `font-display-insight` (missing `swap`) still fires site-wide (50–190 ms), so the swap gap is unresolved. |
| PERF-08 | Medium | Not started | VERIFIED FIXED | Original finding was a Testing-hub-specific SI/FCP outlier (12.8 s vs 3.2 s on other templates). Fresh data shows Testing hub SI (4.3–5.2 s) and FCP (3.8–4.3 s) are now within the same band as the other 4 templates (SI 2.9–5.8 s site-wide) — the template-specific anomaly is gone, even though site-wide SI is still elevated (a separately tracked, still-open issue). |
| PERF-09 | Low | Not started | STILL OPEN | `unsized-images` audit still fails (score 0.5) in the diagnostic data; CLS itself remains Good (0–0.084) today, so the latent risk described in the original finding is unchanged. |
| PERF-10 | Low | Not started | STILL OPEN | Unused JavaScript remains a consistent 105–106 KiB estimated-savings finding on every URL and both strategies, unchanged from baseline. |
| PERF-11 | Low | Already complete | VERIFIED FIXED | TTFB is 3–104 ms across every run, desktop and mobile, all well under the 200 ms good threshold. No regression; positive finding preserved. |
| GOOG-04 | High | Live partial | PARTIALLY FIXED | Homepage mobile LCP is 3.8 s (Needs Improvement, not Poor as originally found); render-blocking (2,320 ms) and hero-image causes are reduced but not eliminated. |
| GOOG-05 | High | Live partial | PARTIALLY FIXED | Shop mobile LCP improved from the original 7.8 s to a 4.2–6.8 s range across independent runs — a real, large improvement, but still above the 4.0 s "Poor" line in the worst-observed run and still above "Good" in the best. |
| GOOG-06 | Medium | Not started | STILL OPEN | NAD+ product mobile LCP is 4.2 s (2 runs) to 7.1 s (1 run) — at or worse than the original 4.2 s baseline; no evidence of remediation targeted at this template. |
| GOOG-07 | Medium | Not started | PARTIALLY FIXED | Site-wide render-blocking remains the dominant mobile bottleneck on every template (1,650–2,830 ms), but beta.39's site-wide font consolidation is measurably reflected in the data (see PERF-07); the underlying claim ("dominant bottleneck") is still true, so this is not resolved, only reduced. |
| GOOG-09 | Medium | Blocked / input needed | BLOCKED BY REAL EVIDENCE | Fresh origin-level CrUX check returned the same "insufficient Chrome traffic volume" error as before. No code fix exists; status is purely a function of real-user traffic volume over time. |
| TECH-06 | High | Live partial | PARTIALLY FIXED | Mobile LCP is no longer uniformly "Poor" (Home now Needs-Improvement) but remains Poor in the majority of independent runs on Shop, NAD+, Testing hub, and COA report. |

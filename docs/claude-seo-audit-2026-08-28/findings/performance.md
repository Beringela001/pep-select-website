# Performance / Core Web Vitals Findings — pepselect.com — 2026-08-28

**Specialist:** seo-performance (hit 15-turn limit; orchestrator parsed product/shop PSI JSON and ran two extra mobile passes per template). **Scope:** `/`, `/shop/`, `/product/ghk-cu/`. Lighthouse 13.x via PSI API. **CrUX and CrUX History: no data** (origin ineligible) — lab only. Raw JSON in `../raw-pagespeed/`.

## Performance score: 55 / 100 (8/23: 58)

| URL | Desktop | Mobile runs | Mobile LCP | Mobile FCP | Mobile TBT | Mobile CLS | Desktop TBT |
|---|---:|---|---|---|---|---|---|
| `/` | **59** | 58, 71, 71 | 9.8 / 4.6 / 4.6 s | 6.0 s (run 1) | 30–240 ms | 0.081–0.084 | **1,770 ms** |
| `/shop/` | 95 | 59, fail, 60 | 10.0 / 9.9 s | 5.2 s | 50–100 ms | 0 | 20 ms |
| `/product/ghk-cu/` | 86 | 57, 58, 58 | 5.4 / 10.9 / 10.4 s | 3.0 s | 40–130 ms | 0 | 260 ms |

**Mobile median 60, range 57–71 (n=8).** 8/23: median 71, range 59–81; desktop 96–97.

## Findings
**Critical**
- Mobile LCP Poor on every run (4.6–10.9 s; target ≤2.5 s) — PERF-01, GOOG-04/05/06.
- **PERF-14 (NEW, High):** homepage desktop 59 with TBT 1,770 ms, JS execution 2.9 s, main-thread 7.0 s, while `/shop/` = 95 and PDP = 86 on the same day. Template-specific. Coincides with the 8/26 cart-recovery exit-offer script and the gate's continuous `requestAnimationFrame` bubble layers (VIS-07). Cause `[VERIFY CLAIM]` — re-run 3× and bisect on staging.
- **GOOG-05 (REGRESSED, lab):** shop mobile LCP 9.9–10.0 s vs 5.3–6.0 s on 8/23. n=2; confirm.

**High**
- Render-blocking requests 2,080 ms (home mobile), 390 ms desktop (PERF-02).
- Unused JS 255–257 KiB; JS execution 2.9 s desktop (PERF-10).
- Third parties: GTM plus an **AdSense chain** (`pagead2.googlesyndication.com` dns-prefetch) — **PERF-13 (NEW, Medium)**: verify intent; lazy-load after interaction if kept.
- No field data; instrument `web-vitals.js` → GA4 once GOOG-11 is granted.

**Medium**
- Image delivery savings 202 KiB mobile / 369 KiB desktop (PERF-03).
- Font-display 70–80 ms (PERF-07).
- Forced reflow, network dependency tree flagged.
- PSI a11y: colour-contrast failure (**VIS-08**, Low); best-practices: console errors (**TECH-09**, Low); SEO: 4 non-descriptive links (GOOG-10).

**Positive**
- `preload_check.py` = 100 on all three templates: LCP image has `fetchpriority="high"`, `speculationrules` prefetch present. Preload is not the bottleneck.
- CLS Good everywhere (0–0.084); PERF-12's 0.303 sample did not reproduce → SUPERSEDED.
- Desktop FCP 0.7–0.8 s; TTFB not flagged (PERF-11 holds).

## Top fixes (expected impact order)
1. Eliminate render-blocking CSS/JS (inline critical CSS, defer theme/plugin scripts) — several seconds of mobile LCP/FCP.
2. Bisect PERF-14 on staging (exit-offer script, gate bubbles) — hundreds of ms TBT on desktop home.
3. Load GTM async; remove or lazy-load AdSense (PERF-13).
4. Compress/resize hero + product-grid images (WebP/AVIF at rendered size).
5. Code-split / prune WooCommerce+plugin JS (41 script tags on home).
6. RUM via `web-vitals.js` since CrUX is unavailable.

Scope already exists as `docs/MOBILE-SEO-PERFORMANCE-MILESTONES-2026-08-20.md` M1–M2; not started as of this audit.

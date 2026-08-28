# Claude SEO — Latest Audit Pointer

**Latest audit:** `docs/claude-seo-audit-2026-08-28/` — 2026-08-28, Claude SEO plugin 2.2.4, Live `https://pepselect.com`, read-only.

This supersedes the 2026-08-23 verification as the current state. Prior dated audits (8/18 baseline, 8/20, 8/23) remain read-only history.

## Headline (2026-08-28, 111 prior findings re-verified + 8 new)

| Classification | Count | vs 8/23 |
|---|---:|---:|
| VERIFIED FIXED | 15 | +1 |
| PARTIALLY FIXED | 19 | +2 |
| STILL OPEN | 50 | 0 net |
| BLOCKED BY REAL EVIDENCE | 12 | −1 |
| SUPERSEDED / informational | 14 | 0 |
| REGRESSED (lab, verify) | 1 | +1 |
| New findings | 8 | — |

**Top changes since 8/23:** (1) COA archive expanded — 15 hubs / 18 batch reports, 14/17 products wired (ECOM-02 BLOCKED→PARTIALLY FIXED); (2) OOS COA path restored (ECOM-10 VERIFIED FIXED); (3) GSC 9 clicks / 85 impressions (was 2/24); (4) shop mobile LCP 9.9–10.0 s and homepage desktop 59 / TBT 1,770 ms — lab regressions to confirm (GOOG-05, new PERF-14); (5) OOS 7→10/17; (6) security-header stack absent for a fourth cycle; none of the 8/23 code-ready quick wins shipped.

**New:** `/order/` (noindex) in sitemap (MAP-04); `/about-us/` noindex vs E-E-A-T (CONT-17, needs approval); `og:type=article` on shop/PDPs (SCHEMA-13); AdSense on storefront (PERF-13); contrast + console errors (VIS-08, TECH-09); snippets don't carry the COA differentiator (SXO-10).

**In this pointer directory:**
- `LATEST-FULL-AUDIT-REPORT-2026-08-28.md`
- `LATEST-VERIFICATION-LEDGER-2026-08-28.md`
- `LATEST-ACTION-PLAN-2026-08-28.md`

Full evidence (raw crawl, PageSpeed JSON, screenshots, per-domain findings, audit-data.json) lives in `docs/claude-seo-audit-2026-08-28/`.

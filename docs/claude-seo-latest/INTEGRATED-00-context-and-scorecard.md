# INTEGRATED-00 — Audit Context & Scorecard

> Part of the Pep Select integrated SEO audit — 2026-08-18 — target https://pepselect.com
> Files: INTEGRATED-00 context & scorecard · INTEGRATED-01 critical & high · INTEGRATED-02 medium, low & verified-correct · INTEGRATED-03 strategy & action plan · INTEGRATED-04 evidence & limitations

---

## Audit Metadata

| Field | Value |
|---|---|
| **Audit date** | 2026-08-18 |
| **Tool** | claude-seo 2.2.4, `/seo audit` — integrated evidence run |
| **Target** | `https://pepselect.com` |
| **Orchestration** | 11 specialist subagents run in parallel; full per-agent reports preserved in `_agents/` |
| **Detected business type** | E-commerce — WooCommerce 10.9.4 + Elementor + Yoast SEO, Kinsta behind Cloudflare. Research-compound vertical (RUO), YMYL-adjacent |
| **Connected evidence** | Google Search Console (verified), PageSpeed Insights v5 mobile+desktop (lab), CrUX + CrUX History (field — **ineligible, no data**, see below), DataForSEO (paid, $0.094 this audit), full crawler passes |
| **Read-only guarantee** | No change was made to the website, Search Console, sitemaps, indexing, Merchant Center or any external account. The Indexing API was used for nothing; URL Inspection was read-only. |
| **SEO Health Score** | **57 / 100** |

### Evidence classes used throughout all five files

| Class | Source | Nature |
|---|---|---|
| **[1]** | Google Search Console | Verified first-party data |
| **[2]** | PageSpeed Insights / Lighthouse | Laboratory measurement |
| **[3]** | CrUX / CrUX History | Real-user field data — **no data exists for this origin (insufficient eligibility)** |
| **[4]** | DataForSEO | Third-party estimate |
| **[5]** | Crawler | Direct observation and inference |

---

## Data Availability

- **Google credentials Tier 1** — PageSpeed Insights v5, CrUX API, CrUX History API, Search Console API all authenticated and used. **GA4 not configured** (no property ID) — organic session/conversion data unavailable (see GOOG-11).
- **CrUX / CrUX History: insufficient eligibility.** Every origin-level and page-level query, both form factors, returned no data — Google has no real-user field data for this origin. All Core Web Vitals statements in this audit are therefore **class-2 laboratory** measurements, clearly labeled.
- **DataForSEO**: 6 calls, $0.094 actual (estimated $0.094 before running; under the $0.10 authorization). Budget-excluded endpoints and their impact are listed in INTEGRATED-04.
- **Backlink free APIs Tier 0** — no Moz/Bing keys; backlink evidence comes from DataForSEO `backlinks_summary` (class 4).
- **No drift baseline** exists for this URL — seo-drift skipped. Not a local business — seo-local/seo-maps skipped.

---

## Scorecard

```
Overall SEO Health Score: 57/100

Technical SEO    (22%):  59/100  ██████░░░░
Content Quality  (23%):  47/100  █████░░░░░
On-Page SEO      (20%):  66/100  ███████░░░
Schema           (10%):  78/100  ████████░░
Performance      (10%):  41/100  ████░░░░░░
AI Search / GEO  (10%):  51/100  █████░░░░░
Images            (5%):  55/100  ██████░░░░
```

**Weighted calculation:** (59×0.22) + (47×0.23) + (66×0.20) + (78×0.10) + (41×0.10) + (51×0.10) + (55×0.05) = 56.74 → **57**

**Supplementary (not part of the weighted formula):** Search Visibility & Off-site Authority — **8/100** (DataForSEO agent score: zero ranked keywords, zero SERP presence, 6 backlinks at spam score 67, domain first seen in the link graph 2026-07-03) and GSC reality of **0 clicks / 5 impressions in 28 days** [1].

### Score derivation (transparent mapping from the 11 agent scores)

| Canonical category | Formula | Inputs |
|---|---|---|
| Technical SEO 59 | 0.4×Technical(78) + 0.2×Sitemap(88) + 0.4×Indexation(25) | Indexation subscore 25/100 from URL Inspection [1]: homepage indexed; `/shop/` + both sampled products "Discovered – currently not indexed"; `/testing/` unknown to Google |
| Content Quality 47 | 0.6×Content(41) + 0.4×SXO(55) | |
| On-Page SEO 66 | 0.7×E-commerce(58) + 0.3×on-page hygiene(85) | Hygiene from verified-correct inventory [5]: unique titles/metas, single H1s, clean canonicals sitewide |
| Schema 78 | Schema agent score | |
| Performance 41 | 0.7×Performance(42) + 0.3×Visual(38) | Visual included because the research-gate interstitial is what lab tools measure (VIS-05) |
| AI Search / GEO 51 | GEO agent score | |
| Images 55 | Judgment from PERF-03/PERF-04/PERF-09 | Oversized hero PNG, oversized logo, unsized images; alt-text not separately audited |

### Agent roster and results

| Agent | Score | Critical | High | Medium | Low/Info | Report |
|---|---|---|---|---|---|---|
| Google APIs (GSC/PSI/CrUX) | 28/100 | 2 | 3 | 4 | 2 | `_agents/google.md` |
| DataForSEO | 8/100 | 2 | 4 | 3 | 1 | `_agents/dataforseo.md` |
| Technical SEO | 78/100 | 0 | 2 | 2 | 3 | `_agents/technical.md` |
| Content & E-E-A-T | 41/100 | 2 | 3 | 4 | 5 | `_agents/content.md` |
| Schema | 78/100 | 0 | 1 | 6 | 3 | `_agents/schema.md` |
| Sitemap | 88/100 | 0 | 0 | 2 | 1 | `_agents/sitemap.md` |
| Performance (lab) | 42/100 | 2 | 2 | 4 | 3 | `_agents/performance.md` |
| Visual / Mobile | 38/100 | 1 | 1 | 1 | 2 | `_agents/visual.md` |
| GEO / AI Search | 51/100 | 1 | 2 | 3 | 3 | `_agents/geo.md` |
| SXO | 55/100 | 1 | 3 | 3 | 1 | `_agents/sxo.md` |
| E-commerce | 58/100 | 2 | 3 | 3 | 1 | `_agents/ecommerce.md` |
| **Total findings: 97** | | **13** | **24** | **35** | **25** | |

Note: `_agents/schema.md` is authoritative for SCHEMA-01 = High (the agent's chat summary miscounted "High: 2"; the file contains exactly one High).

---

## Executive Summary

Pep Select is a **pre-visibility site**: the on-site fundamentals are largely sound, but Google has, so far, decided not to index the money pages, and no off-site authority exists to change its mind.

**What the integrated evidence adds beyond the earlier same-day inline audit (scored 66/100 on crawler evidence alone):**

1. **Verified [1]: the site is effectively invisible.** GSC shows 0 clicks and 5 impressions in 28 days at average position 34. Only the homepage is indexed; `/shop/` and sampled product pages sit in "Discovered – currently not indexed"; the `/testing/` COA hub — the site's single biggest differentiator — is **completely unknown to Google** (GOOG-01, GOOG-02, GOOG-03).
2. **Lab [2]: mobile LCP is Poor everywhere.** Homepage 8.9 s, shop 7.8 s, product 4.2 s (mobile PSI 62 vs desktop 93). Render-blocking CSS/JS and an unoptimized hero PNG dominate (GOOG-04–07, PERF-01–05, TECH-06).
3. **Field [3]: no CrUX data exists** — insufficient eligibility across every URL and form factor. Google currently judges this site's CWV by nothing at all; lab data is the only performance signal available to anyone.
4. **Third-party [4]: zero footprint, near-zero authority, low difficulty.** Zero ranked keywords, absent from all three commercial SERPs pulled, 6 backlinks with spam score 67 on a ~6-week-old domain — while three target keywords score difficulty 0 and the vertical's AI Overviews demonstrably cite vendor content, including a vendor that doesn't rank organically (DFS-01–06).
5. **Observed [5]: a full-viewport "Research Gate" interstitial covers 100% of above-the-fold content on every page for every fresh visitor** — it is what PSI, Googlebot's renderer and every first-time user actually sees (VIS-01, SXO-01, VIS-05).

**Genuine assets confirmed:** clean crawlability/indexability plumbing, one-hop redirects, healthy sitemap (88/100), unique titles/metas and single H1s sitewide, deprecated-schema-free structured data with a rare `Dataset`+`DataDownload` COA archive, fully open AI-crawler access, and unusually concrete, filler-free copy.

**The two structural criticals that gate everything else:** the still-404 Terms of Service that every page's compliance gate references (CONT-01), and the research gate's SEO/UX cost (VIS-01/SXO-01). The full causal chain and sequenced plan are in INTEGRATED-03.

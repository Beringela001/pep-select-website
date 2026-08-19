# 00 — Audit Context

Part of the Pep Select full site SEO audit. Index: [`../CLAUDE-SEO-LATEST.md`](../CLAUDE-SEO-LATEST.md)

---

## Audit Metadata

| Field | Value |
|---|---|
| **Audit date** | 2026-08-18 |
| **Tool** | claude-seo (`/seo audit`) |
| **Claude SEO version** | 2.2.4 |
| **Orchestrator skill** | `seo-audit` v2.2.4 |
| **Target** | `https://pepselect.com` |
| **Pages crawled** | 43 of 43 in sitemap index (100% coverage) |
| **Crawl method** | Full sitemap enumeration + per-page HTML parse (`fetch_page.py`, `parse_html.py`, `content_quality.py`, `commoncrawl_graph.py`, `drift_history.py`, `google_auth.py`, `backlinks_auth.py`) |
| **Detected business type** | E-commerce — WooCommerce 10.9.4 + Elementor 4.1.5 + Yoast SEO, hosted Kinsta behind Cloudflare. Research-compound vertical (RUO), high-trust / YMYL-adjacent |
| **SEO Health Score** | **66 / 100** |

---

## Execution Note

This audit was run inline rather than via parallel subagent delegation (the session prohibits spawning subagents unless explicitly requested). All analysis passes defined by the `seo-audit` skill were executed sequentially against the same bundled tooling; coverage is equivalent.

Conditional agents `seo-local` and `seo-maps` were correctly skipped (not a local business). `seo-drift` was skipped — no baseline exists for this URL.

---

## Data Availability Limits

These constrain three findings and are stated wherever they apply:

- **No Google API credentials configured.** `google_auth.py --check` reports PageSpeed Insights, CrUX, CrUX History, Search Console, Indexing API and GA4 all `[MISSING]`. **All Core Web Vitals statements in this report are inferred from HTML structure and measured asset weights — there is no field data and no Lighthouse run.**
- **No Moz / Bing Webmaster credentials.** Backlink analysis is limited to Common Crawl domain-level metrics.
- **No DataForSEO MCP.** No live SERP positions, keyword volumes, or competitor data.
- **Local `curl` build lacks HTTP/2 support**, so negotiated protocol could not be verified. The origin returns `alt-svc: h3=":443"`, indicating HTTP/3 is offered.

---

## Category Scores

```
Overall SEO Health Score: 66/100

Technical SEO    (22%):  76/100  ████████░░
Content Quality  (23%):  58/100  ██████░░░░
On-Page SEO      (20%):  70/100  ███████░░░
Schema           (10%):  72/100  ███████░░░
Performance      (10%):  45/100  █████░░░░░
AI Search / GEO  (10%):  74/100  ███████░░░
Images            (5%):  66/100  ███████░░░
```

**Weighted calculation:**
(76×0.22) + (58×0.23) + (70×0.20) + (72×0.10) + (45×0.10) + (74×0.10) + (66×0.05) = **66.46 → 66**

---

## Executive Summary

Pep Select is a **well-built site with a serious infrastructure problem and an authority problem**, not a site with broken SEO fundamentals. The fundamentals are unusually clean: 43/43 pages return 200, every page is self-canonical and indexable, there are zero duplicate titles, zero duplicate meta descriptions, zero missing meta descriptions, exactly one H1 per page, and valid heading hierarchy throughout. Canonicalization redirects resolve in a single hop. TTFB is 79–115 ms.

Two things separate this site from its category, and both are genuine competitive assets:

1. **The COA Quality Archive** (`/testing/`) publishes batch-level lab data as `Dataset` schema with `variableMeasured`, `measurementTechnique`, `provider`, and a `DataDownload` PDF per batch. This is a level of structured transparency that is rare anywhere and near-absent in this vertical.
2. **The content is unusually concrete.** `content_quality.py` returned filler score **0/100** and AI-pattern score **0/100** on every page tested, with information density 0.93–1.00. Named labs, exact batch identifiers, purity percentages, shipping cutoffs, and payment mechanics are stated plainly.

What is holding the score down:

- A **sitewide broken legal link** — 43 of 43 pages ask users to agree to Terms and Conditions via a link that returns 404.
- **Plugin bloat**: every page loads 31–49 render-blocking stylesheets and 34–48 scripts. Product pages issue ~95 asset requests.
- **Content volume**: 560 words of identical boilerplate on all 43 pages, and every product page falls below the 400-word quality gate on unique content.
- **Zero measurable off-site authority.** The domain does not appear in the Common Crawl Jan–Mar 2026 web graph at all.

---

## Top 5 Critical / High Findings

| # | Finding | Severity | Evidence |
|---|---|---|---|
| T-01 | `/terms-of-service/` returns 404, linked from all 43 pages | **Critical** | Verified 404; `grep -l 'terms-of-service' pages/*.html` = 43 |
| P-01 | 31–49 render-blocking stylesheets + 34–48 scripts per page | **High** | Measured; 416 KB JS + 77 KB CSS on homepage |
| C-01 | 560 words of identical boilerplate on 43/43 pages | **High** | Line-frequency analysis across full crawl |
| C-02 | All 15 product pages below 400-word quality gate | **High** | 196–324 unique words each |
| I-01 | 7 images exceed 200 KB; all are PNGs that should be WebP | **High** | HEAD request measurement, 73 unique images |

---

## Top 5 Quick Wins

| # | Action | Effort | Impact |
|---|---|---|---|
| QW-1 | Repoint the gate's Terms link to `/terms-conditions/` | 1 line | Removes 43 broken links + legal exposure |
| QW-2 | Convert 15 oversized PNGs to WebP | ~30 min | ~1.8 MB saved; direct LCP gain on product pages |
| QW-3 | Link `/product/bacteriostatic-water-30ml/` from `/shop/` | 1 setting | De-orphans a sitemap page |
| QW-4 | Expand 5 titles under 30 chars (FAQ is 16 chars) | ~20 min | Recovers wasted SERP real estate |
| QW-5 | Add `width`/`height` to the product gallery image template | 1 template | Removes CLS on all 15 product pages |

---

## Complete Findings Index

| ID | Finding | Priority | Category | Depends on |
|---|---|---|---|---|
| T-01 | `/terms-of-service/` 404 linked from all 43 pages | 🔴 Critical | Technical | — |
| T-02 | Orphan page `/product/bacteriostatic-water-30ml/` | 🟠 High | Technical | — |
| T-03 | Security headers largely absent | 🟡 Medium | Technical | — |
| T-04 | Full-page cache bypassed on every request | 🟡 Medium | Technical | — |
| T-05 | Duplicate `User-agent: *` groups in robots.txt (non-functional) | 🔵 Low | Technical | — |
| T-06 | No IndexNow key | 🔵 Low | Technical | — |
| C-01 | 560 words identical boilerplate on 43/43 pages; FDA disclaimer duplicated per page | 🟠 High | Content | T-01 |
| C-02 | All 15 product pages below 400-word quality gate (196–324 unique) | 🟠 High | Content | S-04 |
| C-03 | Three pages under 80 unique words | 🟡 Medium | Content | — |
| C-04 | No About page, no author or credential surface | 🟡 Medium | Content | — |
| C-05 | Duplicate H1s across 6 variant pages | 🟡 Medium | Content | — |
| C-06 | No editorial or informational content surface | 🟡 Medium | Content | C-02, C-04 |
| O-01 | Homepage H1 carries no keyword signal | 🟠 High | On-Page | — |
| O-02 | Product and `/testing/` meta descriptions 100% templated | 🟡 Medium | On-Page | C-02 |
| O-03 | Five titles under 30 chars; separator inconsistency | 🟡 Medium | On-Page | — |
| O-04 | "Out of stock" used as anchor text ×14; 47% of catalog unavailable | 🟡 Medium | On-Page | — |
| O-05 | Run-on anchor text on COA cards | 🔵 Low | On-Page | — |
| O-06 | Product H1s omit strength | 🔵 Low | On-Page | C-05 |
| S-01 | Product offers missing `shippingDetails` | 🟡 Medium | Schema | — |
| S-02 | Missing `productID`/`gtin`/`mpn`, `priceValidUntil`, `category` | 🟡 Medium | Schema | — |
| S-03 | Organization entity has no identity graph | 🟡 Medium | Schema | C-04 |
| S-04 | No `ItemList` on homepage, `/shop/`, or COA hubs | 🟡 Medium | Schema | — |
| S-05 | Homepage `WebPage.name` contradicts `<title>` | 🔵 Low | Schema | — |
| S-06 | Return policy schema contradicts documented practice | 🔵 Low | Schema | — |
| P-01 | 31–49 render-blocking stylesheets, 34–48 scripts per page | 🟠 High | Performance | — |
| P-02 | Four Google Fonts stylesheets, all weights, no preconnect | 🟠 High | Performance | P-01 |
| P-03 | Product gallery image missing dimensions/`fetchpriority`/`srcset` | 🟠 High | Performance | — |
| P-04 | Full-page cache bypassed | 🟡 Medium | Performance | = T-04 |
| I-01 | 7 images >200 KB; all oversized files are PNG | 🟠 High | Images | P-01 |
| I-02 | COA source image 3400×4400 with no `srcset` | 🟡 Medium | Images | — |
| I-03 | COA summary images lack `width`/`height` | 🟡 Medium | Images | — |
| I-04 | Product image alt text is bare product name | 🔵 Low | Images | — |
| I-05 | `<img src="">` placeholder is invalid HTML | 🔵 Low | Images | — |
| G-01 | No entity disambiguation signals | 🟡 Medium | GEO | C-04, S-03 |
| G-02 | Full-viewport research gate in initial HTML (not a penalty risk) | 🔵 Low | GEO | T-01 |
| G-03 | No `llms.txt` (ignored by Google; not a ranking factor) | 🔵 Low | GEO | — |
| G-04 | No informational content for upstream AI queries | 🟡 Medium | GEO | = C-06 |
| G-05 | Domain absent from Common Crawl web graph — no measurable authority | 🟠 High | GEO | C-02, C-04, C-06 |

**Totals:** 1 Critical · 8 High · 17 Medium · 12 Low/Info = **38 findings**

---

## Verified Non-Issues

Recorded so future audits do not re-raise them:

| Item | Status | Reason |
|---|---|---|
| Missing alt text on COA batch pages | **Not a defect** | `<img data-ps-coa-image src="" alt="">` is a JS-populated lightbox placeholder. Empty `alt` on empty `src` is correct |
| "Missing spaces" in H1s (`TB-50010 mg`, `NAD+500 mg`) | **Parser artifact** | Raw markup is `<h1>TB-500 <span>10 mg</span></h1>` — correctly spaced. `parse_html.py` concatenates child nodes without separators |
| `robots.txt` empty `Disallow:` overriding earlier rules | **Not a defect** | RFC 9309 merges same-user-agent groups; earlier disallow rules remain in force |
| No `FAQPage` schema despite 5 FAQs on homepage | **Correct** | Google retired FAQ rich results for all sites 2026-05-07. Adding it would provide no SERP benefit |
| No `aggregateRating` on products | **Correct** | No reviews collected. Fabricating rating markup would be a policy violation |
| Research gate as intrusive interstitial | **Exempt** | Google's policy explicitly exempts legally-required age verification. Content remains fully crawlable |
| No hreflang | **Correct** | Single-language site |
| `noindex` on `/cart/`, `/checkout/`, `/my-account/` | **Correct** | Standard WooCommerce best practice, properly applied |

---

## Audit Artifacts

Working data retained at:

```
<scratchpad>/pepselect.com-audit/
├── sitemap-urls.txt        43 URLs from 4 child sitemaps
├── crawl-status.txt        HTTP status + byte size per URL
├── pages/                  43 raw HTML captures
├── parsed/                 43 parsed SEO JSON extracts
├── onpage.json             Structured on-page dataset
├── onpage-table.txt        Comparison table + duplicate analysis
├── allimgs.txt             73 unique image URLs
└── imgsizes.txt            Measured image weights
```

**Reproduce this audit:** `/seo audit https://pepselect.com`

**Recommended next steps for richer data:**

- `/seo setup` then configure `GOOGLE_API_KEY` → unlocks real CrUX/PSI field data, replacing every inferred Core Web Vitals claim
- `claude-seo run drift_baseline.py https://pepselect.com` → enables `/seo drift compare` for deployment regression checks
- Configure Moz or Bing Webmaster credentials → converts G-05 from a single Common Crawl data point into a real backlink baseline
- `/seo google report full` → generates a professional PDF version of this report

---

*Generated by claude-seo v2.2.4 · `/seo audit` · 2026-08-18 · 43/43 pages analyzed · SEO Health Score 66/100*

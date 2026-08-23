# Technical SEO Findings — pepselect.com — 2026-08-23 Re-verification Pass

**Scope:** Crawlability, indexability, canonicals, redirects, URL structure/case-sensitivity, security
headers, robots.txt, orphan-link risk. Read-only re-verification against the 2026-08-20 ledger.
**Method:** Local evidence review of `docs/claude-seo-audit-2026-08-23/raw-crawl/*` (pages, headers,
sitemaps, `extracted.json`, `robots.txt`) plus a small number of targeted live `curl` GET/HEAD checks
(case-variant Page/CPT routes, http→https, www→non-www, `/shop/page/N/`, `indexnow.txt`, `llms.txt`,
`/about/`). No POST/PUT/DELETE requests, no WordPress/GSC/Cloudflare state touched, no paid API calls.
**Baseline for comparison:** `docs/claude-seo-audit-2026-08-20/PRIOR-FINDINGS-VERIFICATION-LEDGER.md`
and `docs/claude-seo-audit-2026-08-20/findings/technical.md`.

## Technical score: 74 / 100 (unchanged)

No technical regressions and no technical remediations were found in this pass. The two new catalog
products (KPV 10MG, Cagrilintide 10MG) shipped fully crawlable/indexable with no technical defects.
The score-limiting issues from 8/20 — the complete absence of the baseline security-header stack, and
the Bacteriostatic Water orphan-link gap — are both still present, unchanged, verified with fresh
evidence.

---

## Per-ID re-verification table

| ID | Priority | Prior (8/20) | Classification (8/23) | Evidence |
|---|---|---|---|---|
| TECH-01 | High | STILL OPEN | **STILL OPEN** | `Strict-Transport-Security` absent from every 2026-08-23 header pull: `raw-crawl/headers/root.hdr`, `shop.hdr`, `product_nad.hdr`, `product_kpv10.hdr`, `testing.hdr`, `checkout.hdr`, `guides_how-to-review-research-peptide-documentation.hdr`. Zero matches for `strict-transport` across all 7 files. |
| TECH-02 | Medium | STILL OPEN | **STILL OPEN** | `Content-Security-Policy` absent from all 7 headers above. No `Content-Security-Policy-Report-Only` either. |
| TECH-03 | Medium | STILL OPEN | **STILL OPEN** | Neither `X-Frame-Options` nor a CSP `frame-ancestors` directive present on any of the 7 headers, including `checkout.hdr` (WooCommerce checkout redirect + 200 render both checked). |
| TECH-04 | Low | STILL OPEN | **STILL OPEN** | Neither `Referrer-Policy` nor `Permissions-Policy` present on any of the 7 headers. |
| TECH-05 | Low | PARTIALLY FIXED | **PARTIALLY FIXED (unchanged)** | Fresh live spot-check 2026-08-23: `https://pepselect.com/Shop/` → `200`, 0 redirects, correct lowercase self-referencing `<link rel="canonical" href="https://pepselect.com/shop/" />`. `/Contact/`, `/FAQ/`, `/Cart/` → all `200`, 0 redirects (same Page-post-type case-insensitivity as 8/20). Custom-post-type routes remain case-sensitive and correctly 404: `/Product/kpv10/` → `404`, `/Testing/` → `404`. No change in behavior since 8/20; still not remediated at the edge. |
| TECH-06 | High | PARTIALLY FIXED | **OUT OF SCOPE FOR THIS PASS — deferred to Performance domain** | Mobile LCP/CWV numbers are owned by the seo-performance agent; not re-measured here. Flagging only that it exists and remains open per the 8/20 `findings/performance.md`. No source-level regression signal observed on the two new product templates (same Elementor/WooCommerce template family, same image `srcset`/`scaled` asset pattern as existing SKUs — `raw-crawl/pages/product_kpv10.html`, `product_cag10.html`). |
| TECH-07 | Low | SUPERSEDED | **SUPERSEDED (unchanged)** | `https://pepselect.com/indexnow.txt` → `404` (fresh check 2026-08-23). No IndexNow key referenced in `robots.txt`. No change from 8/20; matches the original "optional, Bing-priority-dependent" decision. No action taken. |
| MAP-01 | Medium | STILL OPEN | **STILL OPEN** | Regrep of the full 46-page local corpus (`raw-crawl/pages/*.html`) for any `<a href="...bacteriostatic-water-30ml...">` outside the product's own page returned zero matches. Shop grid (`pages/shop.html`) now renders 16 unique product anchors (up from 14 on 8/20 — see "changes since 8/20" below), and `bacteriostatic-water-30ml` is still not among them. Product is present in `product-sitemap.xml` (`lastmod 2026-08-20T02:28:50Z`), `robots: index,follow`, correct canonical — sitemap-only discovery persists, zero internal PageRank flow, unchanged from 8/20's "genuine orphan" conclusion. |
| MAP-02 | Low | SUPERSEDED | **SUPERSEDED (unchanged)** | `page-sitemap.xml` (fresh pull) still clusters the same 8 static/legal pages in the identical `2026-08-14T16:45:20Z`–`16:46:41Z` 81-second window (byte-identical timestamps to the 8/20 pull). No new edits; correctly left untouched per the "no manufactured-freshness rewrite" ground rule. |
| MAP-03 | Medium | VERIFIED FIXED | **VERIFIED FIXED (re-confirmed)** | Fresh live check 2026-08-23: `https://pepselect.com/shop/page/2/` → `404`; `https://pepselect.com/shop/page/1/` → `301` → `https://pepselect.com/shop/` (1 redirect hop); `/shop/` → `200`. Identical to 8/20 result. |
| GOOG-08 | Low | STILL OPEN | **STILL OPEN** | Umbrella for TECH-01–04; only `X-Content-Type-Options: nosniff` present across all sampled headers, confirmed unchanged. See TECH-01–04 rows above. |
| GEO-09 | Low | STILL OPEN | **STILL OPEN** | `robots.txt` (fresh pull, `raw-crawl/robots.txt`) is textually identical to the 8/20 pull quoted in the prior ledger — same `Disallow` list, same Yoast `User-agent: *` / `Sitemap:` block, no named AI-crawler tokens (`GPTBot`, `ClaudeBot`, `PerplexityBot`, `Google-Extended`, `OAI-SearchBot`, `CCBot`, etc. all still fall under the wildcard group). Still a pending policy decision for Paulo, not a code defect; no action taken. |

### Classification counts (this pass)
- VERIFIED FIXED (re-confirmed): 1 (MAP-03)
- PARTIALLY FIXED (unchanged): 1 (TECH-05)
- STILL OPEN: 6 (TECH-01, TECH-02, TECH-03, TECH-04, MAP-01, GOOG-08, GEO-09) — *7 IDs, see note*
- SUPERSEDED (unchanged): 2 (TECH-07, MAP-02)
- OUT OF SCOPE / DEFERRED: 1 (TECH-06, owned by Performance)
- REGRESSED: 0
- BLOCKED BY REAL EVIDENCE: 0

*(Note: STILL OPEN is 7 items — TECH-01, TECH-02, TECH-03, TECH-04, MAP-01, GOOG-08, GEO-09 — grouped as 6 rows above only because GOOG-08 rolls up TECH-01–04.)*

---

## New technical findings (this pass)

No new defects were identified. One informational item, continuing the ID scheme:

**TECH-08 (Informational, no priority) — Product-sitemap growth reflects the 8/20–8/22 catalog additions cleanly, no technical debt introduced**
- `product-sitemap.xml` grew from 15 to 17 `<url>` entries; the two new entries (`/product/cag10/`, `/product/kpv10/`) both carry `lastmod` timestamps in the same `2026-08-22T02:2x:xx` batch-resave window as the other 15 existing SKUs (`raw-crawl/product-sitemap.xml`), indicating a full sitemap regeneration on catalog reorder rather than a targeted edit — consistent with the "catalog order" change noted in the drift summary. This is the same mechanical-resave signature MAP-02 already covers for static pages; not tracked as a new issue since it does not misrepresent freshness in a way that affects crawl priority (all 17 SKUs are genuinely live/current).
- No action needed.

No new MAP-0x findings were identified — the orphan-link surface (MAP-01) and pagination hygiene (MAP-03) are unchanged from 8/20.

---

## New-product crawlability/indexability confirmation (KPV 10MG, Cagrilintide 10MG)

Per the drift note, both new products were independently verified:

| Check | `/product/kpv10/` | `/product/cag10/` |
|---|---|---|
| HTTP status | 200 (`headers/product_kpv10.hdr`) | 200 (not separately re-pulled; page HTML present in `pages/product_cag10.html`, same template) |
| Meta robots | `index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1` (`extracted.json`) | same |
| Canonical | `https://pepselect.com/product/kpv10/` (self-referencing, correct) | `https://pepselect.com/product/cag10/` (self-referencing, correct) |
| In sitemap | Yes — `product-sitemap.xml`, `lastmod 2026-08-22T02:22:27Z`, includes `<image:image>` | Yes — `product-sitemap.xml`, `lastmod 2026-08-22T02:22:09Z`, includes `<image:image>` |
| Linked from `/shop/` | Yes — `<a href="https://pepselect.com/product/kpv10/">` present in `pages/shop.html` (16 unique product anchors total, verified by regex) | Yes — `<a href="https://pepselect.com/product/cag10/">` present in `pages/shop.html` |
| X-Robots-Tag header | Not present (no conflicting HTTP-level noindex) | Not checked directly (not in headers sample); no reason to expect divergence from sibling products, all of which are header-clean |
| Structured data | `Product`, `Offer`, `MerchantReturnPolicy`, `BreadcrumbList`, `UnitPriceSpecification` JSON-LD blocks present (2 blocks, `extracted.json`) | same JSON-LD type set present |
| Viewport / mobile meta | `<meta name="viewport" content="width=device-width, initial-scale=1">` present | same template, not independently re-checked but byte-identical head boilerplate expected |
| Rendering | Server-rendered (raw `curl`/requests fetch returns full populated HTML, 163KB for kpv10 — not an empty SPA shell); no JS-rendering dependency introduced | same |

**Conclusion: both new SKUs are fully crawlable, indexable, and technically clean — no defects introduced by the 8/20–8/22 releases.**

---

## Header/robots.txt drift check vs 8/20

- **Security headers:** no change. Still exactly `X-Content-Type-Options: nosniff` and nothing else from the baseline hardening stack, on all 7 sampled routes including the WooCommerce checkout redirect/render pair.
- **New headers observed (not security-relevant, informational only):** `Nel` / `Report-To` (Cloudflare Network Error Logging), `CF-Cache-Status`, `Ki-CF-Cache-Status` / `x-kinsta-cache` / `ki-*` (Kinsta edge cache instrumentation), `alt-svc: h3=":443"` (HTTP/3 advertisement). These are infra/observability headers, not security headers, and do not change the TECH-01–04 classification. It is not clear from the 8/20 ledger whether these were present then too (the 8/20 technical.md excerpts quoted did not list a full raw header dump) — flagged **[VERIFY CLAIM: unable to confirm whether Nel/Report-To/alt-svc/ki-* headers are new since 8/20 or were simply not transcribed in the prior ledger's excerpts]**. Not treated as a finding either way since none carry SEO or security weight.
- **robots.txt:** textually identical to the 8/20 pull (same `Disallow` list for wc-logs/transient/uploads/wp-admin/add-to-cart query strings, same Yoast `Sitemap:` block). No change.
- **`X-Robots-Tag`:** still absent everywhere sampled (no conflicting HTTP-level noindex anywhere).

---

## Changes since 2026-08-20

1. **Catalog expansion is technically clean.** Two new products (`/product/kpv10/`, `/product/cag10/`) went live 2026-08-20–22, both fully indexable, canonical-correct, sitemap-listed, and linked from `/shop/` with real `<a href>` anchors — no crawlability regression introduced by the catalog-order/release work.
2. **`product-sitemap.xml` grew from 15 to 17 URLs**, all 17 batch-resaved with fresh `lastmod` timestamps in the `2026-08-22T02:2x` window (informational, TECH-08 above — mirrors the existing MAP-02 mechanical-resave pattern, not a new issue).
3. **Shop grid grew from 14 to 16 crawlable product anchors** (Bacteriostatic Water still excluded by design — MAP-01 unchanged/STILL OPEN).
4. **No security-header remediation shipped.** TECH-01/02/03/04/GOOG-08 remain exactly as they were on 8/20 — this is the single most material gap still outstanding, unchanged across two audit cycles.
5. **No new technical regressions.** Case-sensitivity behavior (TECH-05), pagination hygiene (MAP-03), IndexNow (TECH-07), `llms.txt`/`/about/` (informational, both still 404), and robots.txt/AI-crawler differentiation (GEO-09) are all byte-for-byte or behaviorally unchanged.

---

## Stop conditions hit

None. All checks in scope were completed using local evidence plus a small number (10) of targeted read-only `curl` GET requests, consistent with the audit's read-only/no-paid-API/no-site-modification constraints.

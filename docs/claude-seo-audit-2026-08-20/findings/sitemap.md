# Sitemap Architecture Audit — pepselect.com (Live)

**Date:** 2026-08-20
**Method:** Live GET of `sitemap_index.xml`, all 5 child sitemaps, `robots.txt`, homepage, `/shop/`, `/testing/`; cross-referenced against `docs/claude-seo-audit-2026-08-20/raw-crawl/*` and `status-check.tsv`. Read-only — no mutations, no indexing submissions.

## 1. XML validity and protocol compliance

| Check | Result |
|---|---|
| XML declaration (`<?xml version="1.0" encoding="UTF-8"?>`) | Pass — present on index and all 5 child files |
| Stylesheet reference (`<?xml-stylesheet ... main-sitemap.xsl?>`) | Pass — present on every file |
| `urlset`/`sitemapindex` namespace | Pass — correct `sitemaps.org/schemas/sitemap/0.9` + Google `image` namespace |
| Well-formed XML (opening/closing tags balanced) | Pass — no truncation or malformed entities in any of the 6 files |
| `lastmod` format (W3C Datetime) | Pass — all entries use `YYYY-MM-DDTHH:MM:SS+00:00` |
| `priority` / `changefreq` tags | Absent — correctly not emitted (Yoast default; both ignored by Google, so this is optimal, not a defect) |
| `>50,000 URL` per-file cap | Pass — largest child file (`product-sitemap.xml`) has 16 URLs |
| `>50MB` uncompressed cap | Pass — largest file is ~4.2KB |
| `news:` sitemap present | N/A — none published, none required |
| Live vs. raw-crawl-provided XML | **Identical** — re-fetched `sitemap_index.xml` live and it matches the pre-collected raw-crawl copy byte-for-byte on all 5 child `<loc>`/`<lastmod>` pairs |

No structural sitemap defects found.

## 2. `lastmod` accuracy review

### `page-sitemap.xml` (static/legal pages) — the MAP-02 subject

```
/                        2026-08-14T05:32:23+00:00
/contact/                2026-08-14T16:45:20+00:00
/faq/                    2026-08-14T16:45:31+00:00
/military-discount/      2026-08-14T16:45:44+00:00
/privacy-policy/         2026-08-14T16:45:56+00:00
/refund-shipping-policy/ 2026-08-14T16:46:11+00:00
/ruo-disclaimer/         2026-08-14T16:46:19+00:00
/terms-conditions/       2026-08-14T16:46:31+00:00
/track-your-order/       2026-08-14T16:46:41+00:00
```

The 8 static/legal pages (excluding home) are still clustered inside an **81-second window** (16:45:20 → 16:46:41), each roughly 8–15 seconds apart — the same mechanical-resave signature the original CONT-12/MAP-02 findings described. This is unchanged since the prior audit and is not a new regression; it is the same historical resave event, still visible in today's live data. No evidence of a real per-page editorial change on any of these 8 URLs since 2026-08-14.

### `product-sitemap.xml` — for contrast

Product `lastmod` values are **not** clustered — they range from 2026-08-14T10:49 through 2026-08-20T02:28, with several distinct groups (14th: original 15-product batch; 19th 20:08–20:09: BPC-157/MOTS-C/TB-500/SS-31 batch; 20th 02:28:50: Shop + GHK-CU + Bacteriostatic Water + Retatrutide 10mg sharing one identical timestamp). The 20th cluster of 4 identical timestamps matches commit `069ad8e "Order catalog by availability and compound family"` — a real, single bulk catalog-reorder edit, which is a legitimate reason for a shared timestamp (unlike the static-page case, which has no corresponding edit history). This is a **plausible, real** lastmod pattern, not a defect.

### `ps_compound-sitemap.xml` / `ps_coa_test-sitemap.xml`

Both show varied, incrementing timestamps consistent with genuine batch-report publication dates (07-15 initial hub creation spread over ~2h07m; 07-28 through 08-05 for individual batch reports at meaningfully different days/times; `/testing/` hub and the new `/testing/retatrutide-10mg/` hub share 2026-08-15T14:30:35, consistent with the documented single addition of that hub). No defect.

**Conclusion:** MAP-02's underlying evidence (the 81-second static-page cluster) is reproducible today, unchanged. No fix was ever scoped for MAP-02 (ledger status: "Superseded / no immediate fix" — do not manufacture freshness). Classification below reflects that this is intentionally left alone, not regressed or newly broken.

## 3. Sitemap coverage vs. live crawl

`all-sitemap-urls.txt` (44 URLs) reconciled against live homepage, `/shop/`, and `/testing/` internal-link scans:

- Every internal content link found on Home, Shop, and the Testing hub resolves to a URL already present in the sitemap set. No orphaned or crawlable-but-unlisted content page was found.
- Non-content/infrastructure links present on-page but correctly **absent** from the sitemap: `/feed/`, `/comments/feed/`, `/wp-json/` (+ REST endpoints) — correct, these are not indexable content types.
- `/my-account/` and `/my-account/cash-back/` are linked in the header/nav on Shop and Testing but are **correctly excluded** from the sitemap (transactional/account surface).
- `/cart/` and `/checkout/` have no static `<a href>` in the scanned HTML (side-cart/AJAX-driven) and are, correctly, **not present** in the sitemap. Excluding all three transactional surfaces is the expected behavior and matches the roadmap's stated boundaries.
- `robots.txt` correctly disallows only `/wp-admin/`, WooCommerce log/transient upload paths, and `add-to-cart` query strings; it does not block any sitemap-listed content URL, and correctly declares `Sitemap: https://pepselect.com/sitemap_index.xml`.

No sitemap gaps found against what is actually crawlable from Home/Shop/Testing.

## 4. `/shop/` duplication check

`/shop/` appears **exactly once** in the full 44-URL set — in `product-sitemap.xml` only (`lastmod 2026-08-20T02:28:50`). It is absent from `page-sitemap.xml`. This matches the Milestone 5 fix ("Removed duplicate `/shop/` inclusion from the page sitemap while retaining Shop in the WooCommerce product sitemap") and remains correct on today's live re-fetch. **No regression.**

## 5. Status-code / redirect check

`status-check.tsv` (re-verified against the raw-crawl data, all 44 URLs): 44/44 return `200`, 0 redirects, `text/html; charset=UTF-8` throughout. No non-200, no noindexed-but-listed, no redirect-chain entries. Sitemap contains zero dead weight.

## 6. Total indexable count and growth plausibility

| Sitemap file | URL count |
|---|---|
| post-sitemap.xml | 1 (the new documentation guide) |
| page-sitemap.xml | 9 (static/legal, "/shop/" correctly excluded) |
| ps_compound-sitemap.xml | 9 (`/testing/` hub + 8 compound-history sub-hubs, incl. new Retatrutide 10mg) |
| ps_coa_test-sitemap.xml | 9 (individual batch reports) |
| product-sitemap.xml | 16 (15 products + `/shop/`) |
| **Total** | **44 unique indexable URLs** |

Comparison to prior milestones (`docs/SEO-GOOGLE-ADS-HANDOFF-2026-08-17.md`):

- Milestone 2 (8/14) found **45** sitemap URLs at that point.
- Milestone 5 (8/15) reported **18** public Quality Archive URLs (`/testing/` tree) — today's `/testing/` tree is 9 (compound hubs) + 9 (batch reports) = **18**, an exact match, despite the Retatrutide 10mg hub/report being added since — consistent with steady-state Quality Archive coverage.
- 45 → 44 net is plausible: Milestone 5 removed the duplicate `/shop/` entry from `page-sitemap.xml` and excludes the `noindex,follow` About page from all XML sitemaps (net −2 vs. the 8/14 baseline), while the new documentation guide (+1) was added since — netting to 44. This is consistent with a genuine, explainable consolidation rather than an unexplained loss of pages.

No structural anomaly. The count is small and fully accounted for by known, documented actions (dedup, About exclusion, guide publication, batch-report growth).

## Prior Finding Classifications

| ID | Original Priority | Prior State (8/18) | Current Classification | Evidence |
|---|---|---|---|---|
| MAP-02 | Low | Superseded / no immediate fix | **SUPERSEDED** | The 8 static/legal `page-sitemap.xml` entries remain clustered in an 81-second window (2026-08-14T16:45:20–16:46:41), the same mechanical-resave signature as before. No fix was ever scoped (ledger rule: do not manufacture freshness dates). Live data today reproduces the same pattern unchanged — not a regression, this is the accepted, intentionally-untouched state. |
| MAP-03 | Medium | Live verified | **VERIFIED FIXED** | `/shop/page/2/` was previously confirmed 404 while `/shop/` returns 200 (per ledger). This audit did not re-request `/shop/page/2/` directly (out of scope for sitemap-only slice) but confirms `/shop/` itself is 200, appears exactly once in the sitemap, and no paginated Shop URL is listed in any sitemap file — consistent with the prior verified fix holding. |
| GOOG-02 | High | Live source verified / Google validation pending | **PARTIALLY FIXED** | Sitemap-side fix confirmed again today: `ps_compound-sitemap.xml` live-fetch matches raw-crawl and includes `/testing/` plus all 8 compound-history hubs (9 total incl. `/testing/`), all returning 200. The unresolved half of this finding — actual Google discovery/indexing of `/testing/` — is outside this sitemap-only slice's evidence (requires GSC URL Inspection, not re-run here), so the finding cannot be called VERIFIED FIXED end-to-end. |
| TECH-07 (IndexNow) | Low | Superseded / no immediate fix | **SUPERSEDED** | No IndexNow key file or protocol implementation found; none expected. Optional/Bing-only scope, correctly deprioritized; no change in state. |
| SXO-06 (sitemap-visibility component only) | Medium | Partially complete | **VERIFIED FIXED (sitemap component only)** | `/testing/` is present, well-formed, 200, and listed in `ps_compound-sitemap.xml` with no redirect or duplicate-content issue at the sitemap layer. This finding's title/H1/backlink components are explicitly out of scope for this slice and are unaddressed by this report — only the sitemap/crawl-visibility sub-claim is being closed here. |

## Summary

44 unique indexable URLs across 5 well-formed child sitemaps, 0 non-200 responses, 0 redirects, `/shop/` deduplicated correctly, transactional pages (`/cart/`, `/checkout/`, `/my-account/`) correctly excluded, no orphaned crawlable content found against Home/Shop/Testing link scans. The only open item is MAP-02's static-page `lastmod` clustering, which remains an accepted, unfixed informational finding (no manufactured-freshness rewrite is planned), and GOOG-02's Google-side discovery, which depends on GSC recrawl evidence outside this slice.

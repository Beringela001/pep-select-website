# Sitemap Architecture Audit — pepselect.com
Date: 2026-08-18
Scope: Read-only GET analysis of https://pepselect.com/sitemap_index.xml and its 4 child sitemaps. No sitemap generation, no Search Console access.

## Method Summary
- Discovery cross-check: `claude-seo run sitemap_discovery.py https://pepselect.com --json` — confirmed `https://pepselect.com/sitemap_index.xml` declared in robots.txt, reachable (HTTP 200), valid `sitemapindex` kind, from both `robots.txt` and `common_path` sources. No declared-but-unreachable failures.
- Direct GET of `sitemap_index.xml` and all 4 listed children: `page-sitemap.xml`, `ps_compound-sitemap.xml`, `ps_coa_test-sitemap.xml`, `product-sitemap.xml`.
- XML well-formedness validated with `xml.etree.ElementTree` — all 5 files parsed without error.
- Extracted all 43 `<loc>` entries and checked HTTP status for every one (not just a sample); 13 of these were additionally checked for `X-Robots-Tag` header, `<link rel="canonical">`, and `<meta name="robots">`.
- Homepage, `/shop/`, and `/testing/` HTML were fetched and parsed for internal links to determine coverage vs. discoverability in both directions.
- Spot-checked two adjacent-looking duplicate-shaped URLs (`/shop/page/2/` through `/shop/page/5/`) that are outside the sitemap, to test for a crawl trap.

## Inventory
| Sitemap | URLs | lastmod range | Notes |
|---|---|---|---|
| `sitemap_index.xml` | 4 child sitemaps | 2026-08-05 → 2026-08-18 | `X-Robots-Tag: noindex, follow` on the index file itself (expected/correct Yoast behavior) |
| `page-sitemap.xml` | 8 | 2026-08-14 (all) | Home + 7 static/legal pages |
| `ps_compound-sitemap.xml` | 9 | 2026-07-15 → 2026-08-15 | `/testing/` hub + 8 compound testing pages |
| `ps_coa_test-sitemap.xml` | 9 | 2026-07-28 → 2026-08-05 | Nested batch/COA report pages under `/testing/{compound}/{batch}/` |
| `product-sitemap.xml` | 16 | 2026-08-14 → 2026-08-18 | `/shop/` + 15 product pages |
| **Total** | **43** | | Matches audit brief estimate (~43 URLs) |

No `priority` or `changefreq` tags present anywhere — Yoast has already dropped these (Google ignores both); no action needed.

---

### [MAP-01] Live product page has zero internal links (orphan risk)
- Priority: Medium
- Category: Orphan risk / crawlability
- Evidence class: [5-Crawler observation/inference]
- Evidence: `https://pepselect.com/product/bacteriostatic-water-30ml/` is present in `product-sitemap.xml` (`lastmod: 2026-08-18T11:07:40+00:00`), returns HTTP 200, has a self-referential canonical (`<link rel="canonical" href="https://pepselect.com/product/bacteriostatic-water-30ml/" />`), and no `X-Robots-Tag`/meta-robots noindex signal. A full `grep` for `bacteriostatic-water-30ml` against the rendered HTML of both `https://pepselect.com/shop/` (14 product links found, this one absent) and `https://pepselect.com/` (homepage featured-product links) returned 0 matches in both.
- Affected URLs: `https://pepselect.com/product/bacteriostatic-water-30ml/`
- Reasoning: The product-sitemap.xml lists 15 products, but the two most likely internal discovery paths (the `/shop/` archive and the homepage) link only 14 of them. A page that exists only in the sitemap and nowhere in the site's own link graph receives no internal PageRank/anchor-text signal and is harder for Google to judge as important; it also means human visitors browsing the shop cannot find or buy it without a direct URL or search. The `lastmod` timestamp (same day as this audit) suggests it was just published and the shop listing/menu has not yet been updated to include it.
- Recommendation: Add the product to the `/shop/` archive listing (verify WooCommerce product visibility/catalog settings) and, if relevant, to homepage featured-product placements, so the page is reachable by at least one internal link path in addition to the sitemap.
- Dependencies: Depends on confirming WooCommerce catalog visibility setting for this product is not intentionally set to "hidden"; unblocks normal organic discovery and Search Console "Discovered/Crawled" progression for this URL.
- Failure check: Re-crawl `/shop/` and homepage after the fix; if `bacteriostatic-water-30ml` still doesn't appear in either page's internal links, the fix did not take effect.
- Success check: `/shop/` (or a linked sub-page/menu item) contains an `<a href>` to `/product/bacteriostatic-water-30ml/`.
- Leading indicator: Google Search Console → Pages report shows this URL move from "Discovered – currently not indexed" (or absent) to "Indexed", or Coverage/Crawl Stats show it being crawled without needing sitemap-only discovery.

### [MAP-02] Static/legal page lastmod values reflect a bulk resave, not real content edits
- Priority: Low
- Category: Sitemap metadata accuracy
- Evidence class: [5-Crawler observation/inference]
- Evidence: In `page-sitemap.xml`, seven of eight page entries carry `lastmod` timestamps all dated 2026-08-14 within an 81-second window, in strict URL order: `/contact/` 16:45:20, `/faq/` 16:45:31, `/military-discount/` 16:45:44, `/privacy-policy/` 16:45:56, `/refund-shipping-policy/` 16:46:11, `/ruo-disclaimer/` 16:46:19, `/terms-conditions/` 16:46:31, `/track-your-order/` 16:46:41.
- Affected URLs: `/contact/`, `/faq/`, `/military-discount/`, `/privacy-policy/`, `/refund-shipping-policy/`, `/ruo-disclaimer/`, `/terms-conditions/`, `/track-your-order/`
- Reasoning: Eight unrelated pages (a contact form, an FAQ, a legal disclaimer, a shipping policy, etc.) being "updated" 8–15 seconds apart in sequence is the signature of a bulk/scripted resave (e.g., a plugin update, theme change, or bulk quick-edit) rather than genuine content changes made independently on each page. Yoast's sitemap `lastmod` is meant to tell Google "this content materially changed" so it should be recrawled; when it fires on every save regardless of content, Google can start discounting the signal for the whole site, reducing the incentive to recrawl pages that truly did change.
- Recommendation: Confirm whether the underlying page content actually changed on 2026-08-14; if it was a bulk technical resave (e.g., permalink flush, theme/plugin update touching all pages), that is fine to leave in the sitemap, but avoid future bulk saves that touch content unless something was intentionally updated. No sitemap file edit is prescribed here — this is a workflow/process observation, not a code change.
- Dependencies: None blocking; independent of other findings.
- Failure check: Future unrelated bulk actions (e.g., another plugin update) continue to move `lastmod` on all 8 pages simultaneously with no real content diff.
- Success check: Subsequent `lastmod` changes on these pages correspond to an actual visible content edit (checkable via page diff or CMS revision history) rather than clustering in a tight multi-second window across unrelated pages.
- Leading indicator: Google Search Console Crawl Stats / "Last crawled" dates for these URLs stop moving in lockstep after future unrelated site changes.

### [MAP-03] Unbounded `/shop/page/N/` pagination URLs return 200 with duplicate content and self-referencing canonicals, outside sitemap and unblocked
- Priority: Medium
- Category: Duplicate content / crawl efficiency (sitemap-adjacent — coverage vs. discoverable sections)
- Evidence class: [5-Crawler observation/inference]
- Evidence: `https://pepselect.com/shop/page/2/`, `/shop/page/3/`, `/shop/page/4/`, and `/shop/page/5/` all return HTTP 200 and render the same 14 product links as `/shop/` (page 1). `/shop/page/2/`'s own `<link rel="canonical">` points to itself (`https://pepselect.com/shop/page/2/`), not back to `/shop/`, and no `X-Robots-Tag`/meta robots block was present. `/shop/` itself does not render any pagination UI links to `/shop/page/2/` (grep for `page/2` in the rendered `/shop/` HTML returned no href matches) — the site's catalog (14–15 products) doesn't need pagination, so these URLs shouldn't logically exist as separate crawlable pages.
- Affected URLs: `https://pepselect.com/shop/page/2/`, `/shop/page/3/`, `/shop/page/4/`, `/shop/page/5/` (pattern likely continues for any N)
- Reasoning: These are not in any sitemap (correct — they shouldn't be), but they are still publicly fetchable, return 200 instead of 404, are not `noindex`d, and self-canonicalize instead of canonicalizing back to `/shop/`. If a page number ever gets an external link, gets crawled speculatively, or was linked in the past (e.g., when the catalog was larger), Google could index a duplicate archive page competing with `/shop/` for the same product-listing intent — wasted crawl budget and a duplicate-content signal, even though it's not itself a sitemap defect.
- Recommendation: Have WooCommerce/WordPress return a 404 for out-of-range shop pagination requests, or at minimum set the canonical on any `/shop/page/N/` beyond the last real page back to `/shop/`, so the duplicate surface can't be indexed even if discovered outside the sitemap.
- Dependencies: Independent; a theme/WooCommerce pagination template or hosting-level rewrite fix, not a sitemap file change.
- Failure check: `/shop/page/2/` continues to return 200 with a self-referential canonical after the fix is expected to be live.
- Success check: `/shop/page/2/` (and higher) returns 404, or returns 200 but with `<link rel="canonical" href="https://pepselect.com/shop/">`.
- Leading indicator: Google Search Console Coverage/Pages report shows no `/shop/page/N/` URLs appearing under "Indexed" or "Crawled – currently not indexed" over time.

---

## Verified Correct
- `robots.txt` correctly declares `Sitemap: https://pepselect.com/sitemap_index.xml`; no `Disallow` rules conflict with any sitemap-listed path (`/wp-admin/`, `add-to-cart` query strings, and `wc-logs`/`woocommerce_uploads` upload paths are the only disallows, none overlapping sitemap URLs).
- All 5 sitemap XML files (index + 4 children) are well-formed XML per `ElementTree` parsing.
- All 43 `<loc>` URLs across all 4 child sitemaps return HTTP 200 (checked individually, not sampled) with no 3xx/4xx/5xx found.
- 13 spot-checked URLs spanning every sitemap section (homepage, `/shop/`, `/testing/` hub, 2 products, 2 compound pages, 3 nested COA pages, and 3 static pages) all have self-referencing canonicals and carry no `noindex` signal in either the HTTP `X-Robots-Tag` header or an on-page `<meta name="robots">` tag — all are indexable and not competing with a different canonical target.
- File size and URL-count limits: largest child (`product-sitemap.xml`) is ~4.3 KB with 16 URLs; total across all 4 files is 43 URLs — orders of magnitude under the 50,000 URL / 50 MB per-file cap.
- No deprecated `priority`/`changefreq` tags present in any sitemap — already aligned with current Google guidance.
- Image sitemap coverage is handled via inline `<image:image>` extension tags (homepage hero image, all product images) rather than a separate image sitemap file — this is the supported Yoast approach and requires no separate file.
- Sitemap coverage matches actual site architecture: no WooCommerce product-category archives exist (`/product-category/uncategorized/` → 404) and no blog exists (`/blog/` → 404), consistent with there being no `product_cat` or `post` sitemap in the index — this is not a coverage gap, it reflects a genuinely flat, category-less catalog.
- `/testing/` hub page internally links to all 8 compound sub-pages and all 9 nested COA/batch report pages found in `ps_compound-sitemap.xml` and `ps_coa_test-sitemap.xml` — full two-way match between sitemap and internal link graph for this section.
- `/shop/` internally links 14 of the 15 products in `product-sitemap.xml` (see MAP-01 for the one exception) and all 7 static/legal pages in `page-sitemap.xml` are linked from the homepage/footer.
- Transactional/account WooCommerce URLs (`/cart/`, `/my-account/`, `/my-account/cash-back/`) are correctly excluded from the sitemap — expected behavior, not a gap.
- The two `nad-500-mg` COA batch pages (`psnad562926jp`, `nd50026205jp`) have distinct, batch-specific `<title>` tags ("NAD+ Batch ND50026205JP Lab Report" vs. "...PSNAD562926JP...") and comparable-but-non-identical word counts (~1,073 vs. ~974 words) — templated but not duplicate content; this is the "safe at scale" pattern (unique lab-report data per batch), not a doorway-page risk. Location-page-style quality gates (30+/50+ threshold) do not apply here: only 9 COA pages exist today, well under the 30-page warning threshold, though this is worth re-checking if the batch-report count grows substantially.

## Data Sources & Limitations
- All data gathered via direct, unauthenticated GET requests on 2026-08-18; no Google Search Console, server logs, or analytics data were available, so actual Googlebot crawl/index status (as opposed to fetchability) could not be confirmed for any URL.
- Internal-link coverage checks were limited to the homepage, `/shop/`, and `/testing/` hub pages (the most likely discovery surfaces); a full site crawl of every page's outbound links was not performed, so additional orphan or duplicate-content issues may exist beyond what these three pages reveal.
- The rapid sequential status-code check of all 43 URLs initially triggered what appears to be Cloudflare/Kinsta bot-mitigation (curl error code 000 / connection reset) when run as a single tight loop; results were obtained successfully by re-running in small batches with pauses. This is a normal bot-protection behavior on the hosting/CDN stack, not a sitemap defect, but it means any third-party crawler hitting many sitemap URLs in quick succession could face the same throttling.
- `claude-seo run sitemap_discovery.py` was used only for discovery cross-validation (confirming the declared sitemap is reachable and valid); it does not itself validate per-URL status/canonical/robots signals, which were checked manually via direct fetch as described in the Method Summary.
- Pagination crawl-trap testing (`/shop/page/N/`) was limited to N = 2–5; behavior at higher N was not exhaustively tested but the consistent 200/self-canonical pattern across 4 consecutive values indicates it is not bounded by real product count.

## Category Score: 88/100
Justification: Core sitemap mechanics are fully correct (valid XML, correct robots.txt declaration, all 43 URLs return 200, all spot-checked URLs are self-canonical and indexable, well under size limits, no deprecated tags, coverage matches actual site architecture); score is reduced for one orphaned live product page (MAP-01), a duplicate-content/self-canonical gap on unbounded shop pagination outside the sitemap (MAP-03), and a lastmod-accuracy process issue on static pages (MAP-02).

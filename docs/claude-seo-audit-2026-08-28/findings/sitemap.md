# Sitemap Findings — pepselect.com — 2026-08-28

**Specialist:** seo-sitemap. **Method:** `sitemap_discovery.py` + curl of all 5 child sitemaps; every one of the 62 URLs HEAD-checked (not sampled). Copies in `../raw-crawl/*.xml`.

## Score: 88 / 100

## Structure
- `sitemap_index.xml` declared in robots.txt, HTTP 200. Children: `post` (1), `page` (10), `ps_compound` (15), `ps_coa_test` (18), `product` (18 incl. `/shop/`). **62 URLs total.**
- 8/23 baseline: 46 unique indexable URLs, 8 hubs, 9 batch reports → hubs +7 (incl. `/testing/` index), batch reports +9.

## Validation
| Check | Result |
|---|---|
| XML well-formed | Pass (all 5) |
| priority/changefreq | Pass — absent (correct) |
| lastmod | Pass — valid ISO 8601, realistic spread Jul–Aug 2026 |
| HTTP status (62/62) | Pass — 100% 200, 0 redirects, 0 404s |
| Noindexed URL in sitemap | **Fail — 1** |
| Junk sitemaps (tag/category/author/attachment) | Pass — none exist (all 404) |

## Findings
- **MAP-04 (High, NEW):** `https://pepselect.com/order/` in `page-sitemap.xml` (lastmod 2026-08-26) serves `<meta name="robots" content="noindex, nofollow">` — WooCommerce order page published as a public WP Page. Exclude WC endpoint pages from Yoast's page sitemap so future checkout-flow pages don't reappear.
- **Info:** all primary nav destinations present (`/`, `/shop/`, `/testing/`, `/contact/`, `/faq/`, `/military-discount/`, policies, `/track-your-order/`, all products). `/my-account/` correctly excluded. No `/blog/` index; one post only (`/guides/how-to-review-research-peptide-documentation/`).
- **Info:** `/about-us/` absent from sitemap because it is `noindex` (see content.md CONT-17).
- **Watch:** `ps_coa_test-sitemap.xml` doubled to 18 per-batch pages; confirm each stays unique (batch ID, date, results) as it scales.

## Fixes
1. Remove `/order/` (MAP-04).
2. Monitor batch-report uniqueness.
3. No other action; hygiene otherwise clean.

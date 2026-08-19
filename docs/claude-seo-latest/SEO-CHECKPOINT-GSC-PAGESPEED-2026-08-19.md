# SEO Checkpoint — Search Console and PageSpeed

**Recorded:** August 19, 2026, 3:10–3:12 PM EDT  
**Property:** `sc-domain:pepselect.com`  
**Measurement:** Google PageSpeed Insights mobile lab tests, Lighthouse 13.4.1, emulated Moto G Power, Slow 4G  
**Source methodology:** Claude SEO 2.2.4 findings; this document records the post-release evidence without changing the original findings.

## Google Search Console URL Inspection

| Page | Inspection status before request | Discovery evidence | Indexing request |
|---|---|---|---|
| Shop — `https://pepselect.com/shop/` | Discovered — currently not indexed | `sitemap_index.xml`; no referring page detected | Accepted; added to priority crawl queue |
| Quality Archive — `https://pepselect.com/testing/` | Discovered — currently not indexed | `sitemap_index.xml`; referring page `ps_compound-sitemap.xml` | Accepted; added to priority crawl queue |
| Documentation guide — `https://pepselect.com/guides/how-to-review-research-peptide-documentation/` | URL is unknown to Google | No referring sitemap or page detected | Accepted; added to priority crawl queue |
| NAD+ — `https://pepselect.com/product/nad/` | Indexed; URL is on Google | HTTPS valid; one valid Product snippet, Merchant listing, and Breadcrumb item | Recrawl request accepted; added to priority crawl queue |
| Retatrutide 10 mg — `https://pepselect.com/product/glp3-r10/` | URL is unknown to Google | No referring sitemap or page detected | Accepted; added to priority crawl queue |

NAD+ had non-critical Product snippet and Merchant listing issues. Search Console did not classify those as invalid items.

## Mobile PageSpeed baseline

| Page | Performance | Accessibility | Best Practices | SEO | FCP | LCP | TBT | CLS | Speed Index |
|---|---:|---:|---:|---:|---:|---:|---:|---:|---:|
| Homepage | 79 | 97 | 96 | 92 | 3.0 s | 4.1 s | 50 ms | 0.084 | 3.2 s |
| Shop | 79 | 95 | 96 | 92 | 3.0 s | 4.4 s | 80 ms | 0.018 | 3.2 s |
| NAD+ product | 84 | 96 | 96 | 100 | 3.0 s | 3.6 s | 0 ms | 0 | 3.6 s |
| Quality Archive | 80 | 97 | 100 | 100 | 2.9 s | 4.0 s | 120 ms | 0 | 3.9 s |

### Largest measured opportunities

| Page | Render-blocking estimate | Image-delivery estimate | Unused-JavaScript estimate |
|---|---:|---:|---:|
| Homepage | 2,800 ms | 124 KiB | 105 KiB |
| Shop | 2,360 ms | 161 KiB | 106 KiB |
| NAD+ product | 2,750 ms | 79 KiB | 105 KiB |
| Quality Archive | 2,720 ms | 118 KiB | 105 KiB |

## Interpretation

- All four mobile lab scores are now in Lighthouse's 50–89 range rather than the failing 0–49 range reported by the original audit, but this is one post-release run per route and lab results vary. It is evidence of the current measured state, not proof of a durable field improvement.
- LCP remains above the 2.5-second target on every tested route. The next performance work should therefore focus on remaining render-blocking requests and route-specific image delivery.
- TBT and CLS are currently controlled: TBT is 0–120 ms and CLS is 0–0.084 across the four tests.
- PageSpeed and Search Console both report no real-user field/Core Web Vitals data. Search Console's Core Web Vitals screen was last updated August 17, 2026 and says there is not enough 90-day usage data for mobile or desktop.
- Indexing requests do not guarantee indexing, ranking, or a crawl date. Recheck the five URLs after Google has had time to process the priority queue.

## Next checks

1. Reinspect the five URLs in Search Console after recrawl processing.
2. Confirm that the guide and Retatrutide 10 mg appear in a submitted sitemap; their pre-request inspections reported no referring sitemap.
3. Review the non-critical Product and Merchant issues reported for NAD+.
4. Prioritize the remaining shared render-blocking resources, then retest the same four mobile routes with the same methodology.

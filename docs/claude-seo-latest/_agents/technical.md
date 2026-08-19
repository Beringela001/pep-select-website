# Technical SEO Audit — pepselect.com
Date: 2026-08-18
Scope: Crawlability, indexability, security headers, URL structure, mobile, JS rendering, redirects, 404 handling, hreflang, IndexNow. Read-only GET requests only. Structured data (JSON-LD) validation and detailed render-blocking-asset remediation are covered by the schema and performance sub-agents respectively; this report references those findings by dependency rather than duplicating them.

---

### [TECH-01] No HSTS (Strict-Transport-Security) header
- Priority: High
- Category: Security
- Evidence class: 5-Crawler observation/inference
- Evidence: Full response headers captured for `https://pepselect.com/`, `/shop/`, `/product/bpc157-10/`, `/testing/`, `/contact/`, `/privacy-policy/` contain no `Strict-Transport-Security` header. Header set observed: `Date, Content-Type, Transfer-Encoding, Connection, Nel, CF-Ray, CF-Cache-Status, Content-Encoding, Link, Server: cloudflare, Vary, X-Content-Type-Options: nosniff, ki-* (Kinsta), x-kinsta-cache, set-cookie, Report-To, alt-svc`. Corroborated by PageSpeed Insights (Lighthouse `best-practices` audit) on the homepage: `"has-hsts": {"description": "No HSTS header found", "severity": "High"}`.
- Affected URLs: Site-wide (confirmed on homepage, /shop/, /product/bpc157-10/, /testing/, /contact/, /privacy-policy/; same Cloudflare/Kinsta edge stack serves all templates so the gap is structural, not page-specific).
- Reasoning: The site is behind Cloudflare in front of Kinsta and correctly force-redirects HTTP→HTTPS (301) at the edge, but without a `Strict-Transport-Security` response header, browsers that have never visited the site are not instructed to upgrade future requests automatically, leaving a window for SSL-stripping/downgrade attacks on the first request per browser/network, and blocking HSTS-preload-list eligibility.
- Recommendation: Enable HSTS at the Cloudflare edge (Cloudflare dashboard → SSL/TLS → Edge Certificates → "Enable HSTS"), starting with a conservative `max-age` (e.g., 6 months) and `includeSubDomains`, monitoring for issues before raising `max-age` to 1 year and submitting to the HSTS preload list. This is a configuration change in Cloudflare, not application code.
- Dependencies: None blocking; unblocks HSTS-preload submission. Independent of performance/schema agent work.
- Failure check: Re-fetch homepage headers after the change and `Strict-Transport-Security` is still absent, or the directive is present but `max-age=0` (effectively disabling it).
- Success check: `curl -I https://pepselect.com/` returns a `Strict-Transport-Security` header with `max-age` ≥ 15768000 (6 months) on every template type re-tested.
- Leading indicator: Cloudflare SSL/TLS dashboard "HSTS" status toggle (owner can check without re-crawling).

### [TECH-02] No Content-Security-Policy header
- Priority: Medium
- Category: Security
- Evidence class: 5-Crawler observation/inference
- Evidence: No `Content-Security-Policy` header in any response headers checked (see TECH-01 header list). PageSpeed Insights best-practices audit: `"csp-xss": {"description": "No CSP found in enforcement mode", "severity": "High"}` and `"trusted-types-xss": {"description": "No Content-Security-Policy header with Trusted Types directive found", "severity": "High"}`.
- Affected URLs: Site-wide (same evidence basis as TECH-01).
- Reasoning: WooCommerce + Elementor + multiple third-party plugins (YITH suite, Klaviyo, side-cart plugins) load numerous inline and remote scripts; without a CSP, there is no browser-level containment if any of these scripts or an injected script is compromised (XSS blast-radius control), and this is scored as a "High" severity best-practices/security gap by Lighthouse.
- Recommendation: Introduce a CSP in `Content-Security-Policy-Report-Only` mode first (via Cloudflare Transform Rules or a security plugin) scoped to the actual script/style/font/image origins in use (self, Google Tag Manager, Google Fonts, Klaviyo, Elementor/plugin assets), monitor violation reports for a full crawl-and-checkout cycle, then promote to enforcing mode once the allow-list is stable.
- Dependencies: Should be sequenced after cataloguing all third-party script origins (GTM, Klaviyo, Google Fonts, YITH, WooCommerce side-cart) to avoid breaking checkout functionality; coordinate with whoever owns the performance-agent's third-party-script inventory.
- Failure check: CSP is deployed in enforcing mode but breaks checkout/cart AJAX or blocks GTM/Klaviyo, or is deployed with `default-src *` (no real restriction, i.e., cosmetic only).
- Success check: `Content-Security-Policy` header present in enforcing mode; PageSpeed Insights best-practices audits `csp-xss` and `trusted-types-xss` pass; checkout flow and product-page add-to-cart still function.
- Leading indicator: Browser console CSP-violation report volume (via `report-to`/`report-uri`) trending to zero before flipping to enforce mode.

### [TECH-03] No clickjacking protection (X-Frame-Options / frame-ancestors)
- Priority: Medium
- Category: Security
- Evidence class: 5-Crawler observation/inference
- Evidence: No `X-Frame-Options` header in any response tested. PageSpeed Insights: `"clickjacking-mitigation": {"description": "No frame control policy found", "severity": "High"}` and `"origin-isolation": {"description": "No COOP header found", "severity": "High"}`.
- Affected URLs: Site-wide.
- Reasoning: Checkout and account pages (`/cart/`, `/my-account/`, `/checkout/`) handle payment and account data; without `X-Frame-Options` (or a CSP `frame-ancestors` directive) and without `Cross-Origin-Opener-Policy`, the site can be embedded in a malicious iframe for clickjacking/UI-redress attacks.
- Recommendation: Add `X-Frame-Options: SAMEORIGIN` (or `frame-ancestors 'self'` once the CSP from TECH-02 is in place) and `Cross-Origin-Opener-Policy: same-origin` at the Cloudflare edge (Transform Rules / Response Header Modification) or via a security plugin, prioritizing checkout, cart, and account URLs if a phased rollout is preferred.
- Dependencies: Complements TECH-02; can ship independently and earlier since it carries lower risk of breaking functionality than a full CSP.
- Failure check: Header added but with a value like `ALLOWALL` (non-standard, ineffective) or omitted on checkout/account templates specifically.
- Success check: `X-Frame-Options: SAMEORIGIN` (or equivalent `frame-ancestors`) present on homepage, product, cart, checkout, and my-account responses.
- Leading indicator: Security header scan (e.g., securityheaders.com or the same curl check) rerun monthly by the site owner.

### [TECH-04] Missing Referrer-Policy and Permissions-Policy headers
- Priority: Low
- Category: Security
- Evidence class: 5-Crawler observation/inference
- Evidence: Neither `Referrer-Policy` nor `Permissions-Policy` appear in any response header set captured across homepage, shop, product, testing, contact, or privacy-policy pages.
- Affected URLs: Site-wide.
- Reasoning: These are defense-in-depth headers (controlling referrer leakage to third parties and restricting powerful browser APIs like camera/geolocation); their absence is a lower-severity gap than TECH-01/02/03 but is a quick, low-risk win alongside those changes since it uses the same delivery mechanism (Cloudflare edge headers).
- Recommendation: Add `Referrer-Policy: strict-origin-when-cross-origin` and a `Permissions-Policy` that denies unused APIs (e.g., `geolocation=(), camera=(), microphone=()`) at the same edge layer used for TECH-01/03.
- Dependencies: Can be bundled into the same Cloudflare Transform Rule deployment as TECH-03 to avoid multiple release cycles.
- Failure check: Headers added with overly permissive values that grant more than the site actually uses (e.g., `Permissions-Policy: geolocation=*`).
- Success check: Both headers present with restrictive values on a spot-check of homepage and one product page.
- Leading indicator: Same periodic security-header scan as TECH-03.

### [TECH-05] Case-variant URL serves 200 instead of redirecting to canonical
- Priority: Low
- Category: URL Structure / Indexability
- Evidence class: 5-Crawler observation/inference
- Evidence: `curl -I https://pepselect.com/Shop/` → `HTTP/1.1 200 OK` (no `Location` redirect), while `https://pepselect.com/shop/` is the canonical form declared in the sitemap and in the page's own `<link rel="canonical" href="https://pepselect.com/shop/" />` tag (confirmed present in the `/Shop/` response body too — self-referencing to the lowercase canonical). Byte size differed between the two responses (110,454 bytes for `/Shop/` vs. 152,129 bytes for `/shop/`), consistent with one being edge-cache HIT (`ki-cache-type: Edge`, `Age: 11`) and the other DYNAMIC/MISS rather than genuinely different content.
- Affected URLs: `https://pepselect.com/Shop/` (and by inference, any other mixed-case variant of a rewritten permalink, since WordPress's rewrite engine is not case-normalizing here).
- Reasoning: Returning 200 on a non-canonical case variant instead of a 301 to the canonical lowercase URL means Google can crawl and index two distinct URLs for the same resource; the correct `rel=canonical` tag on the variant substantially mitigates ranking dilution, but it does not stop the variant from being crawled, wasting crawl budget and creating an avoidable duplicate URL in server logs/Search Console coverage reports.
- Recommendation: Add a redirect rule (at Cloudflare edge or via WordPress) that 301-redirects any uppercase/mixed-case path segment to its lowercase canonical equivalent, site-wide, rather than relying solely on the canonical tag.
- Dependencies: None; independent, low-risk fix.
- Failure check: Rule redirects some but not all case variants (e.g., only `/Shop/` but not `/Product/BPC157-10/`), or introduces a redirect loop.
- Success check: `curl -I https://pepselect.com/Shop/` and other mixed-case permalink variants return `301` to the exact lowercase canonical URL.
- Leading indicator: Google Search Console Coverage/Pages report showing zero "Duplicate, Google chose different canonical" entries tied to case variants.

### [TECH-06] Mobile Largest Contentful Paint (LCP) in "Poor" range (lab data)
- Priority: High
- Category: Core Web Vitals
- Evidence class: 2-PageSpeed lab
- Evidence: Two independent PageSpeed Insights (Lighthouse, mobile strategy) runs against `https://pepselect.com/` on 2026-08-18: Run A — `"largest-contentful-paint": {"value": 8926.04, "display": "8.9 s", "score": 0.01}`, Performance score 61. Run B — `"largest-contentful-paint": {"value": 5551.06, "display": "5.6 s", "score": 0.18}`, Performance score 69. Both exceed the 4s "Poor" threshold. Supporting diagnostics from Run B: `"render-blocking-insight"` failed audit, `"display": "Est savings of 2,670 ms"`, `total_items: 58` render-blocking requests; `"image-delivery-insight"` shows the homepage hero image `PS-laying_fam-768x434.png` transferring 367,813 bytes with `wastedBytes: 329,794` (i.e., delivered ~10x larger than needed for its rendered size); total page weight 1,710,715 bytes across 114 requests (`resource-summary`, Run B). Manual HTML inspection independently found 47–49 `<link rel="stylesheet">` tags and ~26 synchronous (no `defer`/`async`) `<script src>` tags inside `<head>` on the product template. CLS was Good in both runs (0 and 0.084) and INP-proxy metrics (TBT 61–150ms) were Good.
- Affected URLs: Confirmed on homepage; the same theme (Hello Elementor + Elementor + pepselect-child) and plugin stack (WooCommerce, YITH suite, Klaviyo, side-cart, back-in-stock notifier) load on product, shop, and testing templates, so the render-blocking-resource pattern is very likely site-wide, though only the homepage was lab-tested via PSI in this pass.
- Reasoning: LCP is a ranking and UX signal measured against a strict 2.5s/4s threshold; a stack of near-50 CSS files and dozens of synchronous head scripts, combined with an oversized, non-preloaded hero image, delays the point at which the largest above-the-fold element paints, regardless of how fast the origin server itself responds (`server-response-time` was 5ms — the bottleneck is client-side asset delivery, not the Kinsta origin).
- Recommendation: Description only, no implementation performed — this is a deep performance-remediation topic (critical CSS extraction, deferring non-critical plugin CSS/JS, responsive hero image sizing, preloading the true LCP image) that should be owned by the performance sub-agent's detailed report; this finding flags the CWV status and the source-level cause so the two reports can be cross-referenced.
- Dependencies: Depends on / should be read alongside the performance agent's render-blocking-asset and image-optimization findings for implementation detail; also related to TECH-01–04 in that the same Cloudflare edge layer used for header fixes could carry certain asset-optimization rules (e.g., Cloudflare Polish/Mirage for images) if adopted.
- Failure check: Re-running PageSpeed Insights (mobile) after remediation still shows LCP > 4s, or LCP improves in lab testing but the underlying render-blocking request count does not decrease.
- Success check: PageSpeed Insights mobile LCP consistently ≤ 4s (target ≤ 2.5s) across repeat runs, and once the site accrues enough Chrome-User-Experience-Report traffic, CrUX field LCP also reports "Good."
- Leading indicator: PageSpeed Insights or Lighthouse CI mobile Performance score trending upward release-over-release; site owner can spot-check via the free PageSpeed Insights web tool without any crawler tooling.

### [TECH-07] IndexNow protocol not implemented
- Priority: Low
- Category: IndexNow Protocol
- Evidence class: 5-Crawler observation/inference
- Evidence: `curl -o /dev/null -w "%{http_code}" https://pepselect.com/indexnow.txt` → `404`. No `IndexNow-Key` reference or IndexNow key file found in homepage HTML or headers. Yoast SEO (the installed SEO plugin, v28.1 per the HTML comment `<!-- This site is optimized with the Yoast SEO plugin v28.1 -->`) does not natively push IndexNow submissions in this configuration.
- Affected URLs: Site-wide (protocol-level gap, not a specific URL).
- Reasoning: IndexNow lets a site push near-real-time change notifications to Bing, Yandex, and Naver instead of waiting for their crawlers to revisit the sitemap; with only ~43 URLs and a low-traffic new site (see CrUX limitation below), faster discovery of new/changed product and testing (COA) pages could meaningfully shorten time-to-index on non-Google engines.
- Recommendation: Generate an IndexNow key file, publish it at the site root (or on Bing's key-hosting endpoint), and configure automatic submission on publish/update — either via a lightweight IndexNow WordPress plugin or a Yoast SEO integration/hook that fires on post save for `product`, `ps_compound`, and `page` post types.
- Dependencies: None; independent, additive change. Does not conflict with any other finding.
- Failure check: Key file published but submissions never fire on content updates (e.g., plugin misconfigured), or the key file returns anything other than the raw key string with a `200`.
- Success check: Bing Webmaster Tools "IndexNow" report shows successful submissions after a test product/testing page update; the key file returns `200` with the exact key as plain text.
- Leading indicator: Bing Webmaster Tools IndexNow submission count ticking up after each content publish (owner-checkable without a re-crawl).

---

## Verified Correct

- **Robots.txt and sitemap discovery**: `https://pepselect.com/robots.txt` returns `200` and correctly declares `Sitemap: https://pepselect.com/sitemap_index.xml`; `sitemap_discovery.py` validated the sitemap index as a well-formed `sitemapindex` reachable via both the robots.txt declaration and the common `/sitemap_index.xml` path. `robots.txt` disallows only `/wp-admin/`, WooCommerce log/transient upload paths, and `add-to-cart` query variants, with an explicit `Allow: /wp-admin/admin-ajax.php` — a standard, non-restrictive Yoast-generated ruleset with no accidental blanket `Disallow: /`.
- **Sitemap completeness**: `sitemap_index.xml` lists 4 sub-sitemaps (`page-sitemap.xml`: 9 URLs, `ps_compound-sitemap.xml` /testing/: 9 URLs, `ps_coa_test-sitemap.xml`: 9 URLs, `product-sitemap.xml`: 16 URLs) totaling 43 URLs, matching the audit brief's expected count. All four sub-sitemaps returned `200` and parsed as valid `urlset` XML.
- **Sample-page indexability**: Verified `meta robots` = `index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1` and a correct self-referencing `rel="canonical"` on all of: homepage, `/testing/`, `/shop/`, `/product/bpc157-10/`, `/product/ghk-cu/`, `/contact/`, `/privacy-policy/`, `/faq/`, `/testing/retatrutide-30mg/`. No `X-Robots-Tag` header on any of these content pages.
- **Non-indexable pages correctly excluded**: `/cart/`, `/my-account/`, and internal search (`/?s=test`) all correctly return `meta name='robots' content='noindex, follow'`. `/checkout/` (empty cart) `302`-redirects to `/cart/`, standard WooCommerce behavior, not a crawl trap.
- **HTTPS enforcement and redirect hygiene**: `http://pepselect.com/` → `301` → `https://pepselect.com/`. `https://www.pepselect.com/` → `301` → `https://pepselect.com/` (non-www is canonical host). Non-trailing-slash permalinks (`/product/bpc157-10`, `/testing/retatrutide-30mg` tested as `/testing/retatrutide-30mg` variant) correctly `301`-redirect to the trailing-slash canonical form via `X-Redirect-By: WordPress`. No redirect chains longer than one hop were observed in any test.
- **404 handling**: A nonexistent path returns a genuine `404 Not Found` status (not a soft-404 masked as `200`), with `Cache-Control: no-cache, must-revalidate, max-age=0, no-store, private` (correctly prevents edge caching of the error page) and `noindex, follow` in the response body's meta robots.
- **Mobile viewport configuration**: `<meta name="viewport" content="width=device-width, initial-scale=1">` present and correctly formed on every page checked. PageSpeed Insights `viewport-insight` audit found only the expected single viewport meta node with no conflicting duplicate.
- **JavaScript rendering / crawlability without JS**: `render_page.py --mode auto` classified every page type tested (homepage, product, shop, testing) as `"is_spa": false`, and the `--mode auto` raw fetch (no Playwright needed) produced fully populated `extracted_text` via trafilatura on the first pass, including price, stock status, and product description on the product template — content is server-side rendered by WordPress/WooCommerce/Elementor, not client-side hydrated, so it is fully accessible to crawlers that do not execute JavaScript.
- **Title and meta description quality**: Homepage `<title>` is 61 characters ("Research Peptides with Batch-Matched Lab Reports | Pep Select") and meta description is 135 characters — both within commonly recommended display-length ranges, and neither is a generic/default placeholder.
- **Hreflang**: Correctly absent. This is a single-market, single-language (en-US, `<html lang="en-US">`) site with no regional/language variants, so no hreflang implementation is required; PageSpeed Insights' `hreflang` SEO audit also passed (`"score": 1`) confirming no invalid/conflicting hreflang markup exists to clean up.
- **Basic transport security present**: `X-Content-Type-Options: nosniff` is present on every response checked, and TLS/HTTPS itself (distinct from HSTS, see TECH-01) is correctly terminated and enforced at Cloudflare.
- **Structured data present at a basic level**: Homepage carries a single valid JSON-LD block (`BreadcrumbList`, `WebSite`, `OnlineStore`, `SearchAction`, `MerchantReturnPolicy`, etc., 2,730 bytes, parsed without error) and the sampled product page carries a `Product` JSON-LD block with `@id`, `name`, `url`, and `description`. Deeper Merchant-listing-rule validation of this markup is deferred to the schema sub-agent per the coordinator's scope split.

## Data Sources & Limitations

- All findings are based on direct, read-only GET requests (curl and the bundled `claude-seo` scripts: `sitemap_discovery.py`, `render_page.py`, `pagespeed_check.py`) performed on 2026-08-18. No authenticated Search Console/GSC data was used for this report (evidence class 5 for crawler-based findings).
- TECH-06 draws on live PageSpeed Insights API lab results (evidence class 2), which is real Lighthouse lab data, not a class-5 inference — flagged accordingly rather than mislabeled.
- **No CrUX field data available**: The `pagespeed_check.py` CrUX lookup returned `"error": "No CrUX data for this origin. The site likely has insufficient Chrome traffic volume for eligibility."` This means the "Poor" LCP in TECH-06 is a lab-only signal (single-load, simulated network/CPU throttling); real-world field LCP experienced by actual visitors is currently unmeasurable via CrUX and could differ. This should be revisited once the origin accumulates enough 28-day Chrome traffic for CrUX eligibility.
- PageSpeed Insights desktop strategy failed both times with a transient `PSI API error 500` from Google's API; only mobile lab data was obtained. Desktop CWV status is therefore unverified in this pass.
- Only a sample of pages was manually header/HTML-checked (homepage, /shop/, two product pages, /testing/ and one testing sub-page, /contact/, /privacy-policy/, /faq/, cart/account/search/checkout utility pages, plus one case-variant and one nonexistent URL). Findings described as "site-wide" (TECH-01 through TECH-04) are inferred from the fact that security headers are applied at the shared Cloudflare/Kinsta edge layer common to every template tested, not from an exhaustive crawl of all 43 sitemap URLs.
- Structured-data (JSON-LD) schema accuracy/Merchant-rule validation and detailed render-blocking-asset/JS-bundle remediation are explicitly out of scope for this report per the coordinator's instruction — see the schema and performance sub-agent reports for that depth.
- This is a point-in-time snapshot (2026-08-18); Cloudflare/Kinsta edge configuration, plugin versions, and PageSpeed lab results can change between runs (as shown by the two different PSI runs in TECH-06 producing different LCP values for the same URL).

## Category Score: 78/100

Foundational technical SEO (crawlability, indexability, sitemap accuracy, URL/redirect hygiene, mobile viewport, and JS-independent renderability) is solid with zero critical crawl or index blockers found; the score is held down by a cluster of missing security headers (HSTS, CSP, clickjacking protection — all confirmed absent site-wide) and a lab-confirmed "Poor" mobile LCP, with minor additional deductions for a non-redirecting case-variant URL and no IndexNow implementation.

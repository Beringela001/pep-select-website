# Technical SEO Findings — pepselect.com — 2026-08-20 Verification Audit

**Scope:** Crawlability, indexability, canonicals, redirect chains, URL structure/case-sensitivity, security headers, robots.txt, internal-link orphan risk.
**Method:** Independent `curl -sD -` / `curl -sI` / `curl -s` GET/HEAD checks against Live `https://pepselect.com`, cross-referenced against `docs/claude-seo-audit-2026-08-20/raw-crawl/*` and the release notes in `docs/claude-seo-latest/`. No POST/PUT/DELETE requests were made; no WordPress, DNS, Cloudflare, or GSC state was touched.
**Baseline for comparison:** `docs/claude-seo-latest/CODEX-SEO-FINDINGS-LEDGER-2026-08-18.md` (2026-08-18) plus subsequent M3/M4/beta.39 release notes through Live child theme `0.25.0-beta.39` / COA Archive `0.7.5`.

## Technical score: 74 / 100

Crawl-path, sitemap, canonical, and pagination hygiene are now strong (MAP-03, GOOG-02 source state, canonicalization, redirect chains all verified). The score is held down by two unrelated but sizeable gaps: (1) the entire baseline security-header stack (HSTS, CSP, X-Frame-Options/frame-ancestors, Referrer-Policy, Permissions-Policy, COOP) is still absent on every response sampled, and (2) one catalog product remains genuinely unreachable by crawlable hyperlink from anywhere on the site (sitemap-only discovery).

---

## Findings

### CRITICAL

None found in this scope during this pass.

### HIGH

**H1 — No HSTS (Strict-Transport-Security) header on any response [TECH-01, contributes to GOOG-08]**
- Evidence: `curl -sD - https://pepselect.com/` and `.../shop/` response headers contain no `Strict-Transport-Security` header. Confirmed on homepage, Shop, and via a direct grep for `strict-transport` across headers (zero matches).
- Impact: HTTP→HTTPS redirects work (`http://pepselect.com/` → 301 → `https://pepselect.com/`), but without HSTS, browsers cache no forced-HTTPS policy, leaving a window for downgrade/SSL-stripping attacks on repeat visits and typed-URL navigation. This is a GSC "Best Practices" flag independent of rankings.
- Recommendation: Add `Strict-Transport-Security: max-age=31536000; includeSubDomains; preload` at the edge (Cloudflare Transform Rule or WordPress/Kinsta HTTP header injection). Start without `preload` for one release cycle, verify no subdomain breakage, then add `preload` and submit to hstspreload.org.

### MEDIUM

**M1 — No Content-Security-Policy header [TECH-02, contributes to GOOG-08]**
- Evidence: no `Content-Security-Policy` header on homepage, Shop, or product-page responses (direct grep, zero matches).
- Recommendation: Start with a report-only CSP (`Content-Security-Policy-Report-Only`) covering the actual script/style/img/font origins in use (self, Cloudflare, Google Fonts, Elementor, GTM, YITH/WooCommerce, Jetpack) before enforcing, given the site's large third-party script surface (jQuery, jQuery UI, GTM, rewards, side-cart, checkout-upsell).

**M2 — No X-Frame-Options / frame-ancestors clickjacking protection [TECH-03, contributes to GOOG-08]**
- Evidence: no `X-Frame-Options` header and no `Content-Security-Policy` (which would carry `frame-ancestors`) on any sampled response.
- Impact: the site — including the WooCommerce checkout and the compliance-gated Research Gate flow — can be framed by a third-party page for clickjacking.
- Recommendation: `X-Frame-Options: SAMEORIGIN` (or `frame-ancestors 'self'` once CSP ships) at the edge.

**M3 — Sitemap `lastmod` values on static/legal pages reflect a bulk resave, not real edits [MAP-02]**
- Evidence: `page-sitemap.xml` (raw-crawl copy, 2026-08-20 pull) shows `/contact/`, `/faq/`, `/military-discount/`, `/privacy-policy/`, `/refund-shipping-policy/`, `/ruo-disclaimer/`, `/terms-conditions/`, `/track-your-order/` all stamped `2026-08-14T16:45:20Z` through `2026-08-14T16:46:41Z` — an 81-second window, identical in character to the pattern the 2026-08-18 audit flagged (CONT-12/MAP-02).
- This is the same mechanical-resave signature as before; no new evidence of genuine content edits at those timestamps. Not a regression — it simply was never remediated (original decision was "no immediate fix," since manufacturing freshness signals is explicitly against instructions).
- Recommendation: no action needed unless/until real content edits occur on these pages; do not touch dates solely for SEO effect (per program ground rules).

### LOW

**L1 — Missing Referrer-Policy and Permissions-Policy headers [TECH-04, contributes to GOOG-08]**
- Evidence: neither header present on any sampled response.
- Recommendation: `Referrer-Policy: strict-origin-when-cross-origin` and a conservative `Permissions-Policy` (e.g. disable `geolocation`, `microphone`, `camera`, `payment` unless a WooCommerce payment method needs `payment=(self)`).

**L2 — Case-variant URLs on static Page-type routes serve 200 instead of redirecting [TECH-05]**
- Evidence (fresh spot-check, 2026-08-20):
  | URL | Status | Redirects | Canonical rendered |
  |---|---|---|---|
  | `https://pepselect.com/Shop/` | 200 | 0 | `https://pepselect.com/shop/` (lowercase, correct) |
  | `https://pepselect.com/SHOP/` | 200 | 0 | `https://pepselect.com/shop/` |
  | `https://pepselect.com/Contact/` | 200 | 0 | (not re-checked, same page-type pattern) |
  | `https://pepselect.com/FAQ/` | 200 | 0 | same pattern |
  | `https://pepselect.com/Cart/` | 200 | 0 | same pattern |
  | `https://pepselect.com/Product/bpc157-10/` | **404** | 0 | n/a |
  | `https://pepselect.com/Testing/` | **404** | 0 | n/a |
- New nuance versus 8/18: the issue is narrower than originally scoped. WordPress's core Page post-type rewrite is case-insensitive at the DB/query level (affects `/shop/`, `/contact/`, `/faq/`, `/cart/`, and presumably every static Page), while the custom-post-type rewrites for Product and the COA Testing archive are case-sensitive and correctly 404 on a wrong-case path. So the live risk surface is the WordPress Page templates only, not the whole site.
- Mitigating factor: every page-type URL checked renders a correct, lowercase self-referencing (well, cross-referencing to the canonical case) `<link rel="canonical">`, which should suppress duplicate indexation in practice. The residual risk is crawl-budget dilution and split link-equity signals if any external link/backlink uses a non-canonical case, not indexation of a duplicate.
- Recommendation: still worth a low-effort 301 (case-normalize slug matches to the canonical lowercase permalink) at the WordPress/Kinsta edge-rule level for Page routes, since the canonical tag alone doesn't stop Googlebot from spending crawl budget on every case permutation it discovers via backlinks.

**L3 — AI crawler access is undifferentiated in robots.txt [GEO-09]**
- Evidence, fresh pull 2026-08-20:
  ```
  User-agent: *
  Disallow: /wp-content/uploads/wc-logs/
  Disallow: /wp-content/uploads/woocommerce_transient_files/
  Disallow: /wp-content/uploads/woocommerce_uploads/
  Disallow: /*?add-to-cart=
  Disallow: /*?*add-to-cart=
  Disallow: /wp-admin/
  Allow: /wp-admin/admin-ajax.php

  # START YOAST BLOCK
  User-agent: *
  Disallow:
  Sitemap: https://pepselect.com/sitemap_index.xml
  # END YOAST BLOCK
  ```
- No named tokens for `GPTBot`, `CCBot`, `Google-Extended`, `OAI-SearchBot`, `PerplexityBot`, `ClaudeBot`, etc. All AI crawlers currently fall under the wildcard `*` group, which effectively allows both training-only and search-retrieval crawlers equally.
- Recommendation: this is a policy decision, not a bug — flag for Paulo's explicit choice (e.g., allow retrieval bots that can drive AI-citation traffic — `OAI-SearchBot`, `PerplexityBot`, `Google-Extended` for AI Overviews — while blocking pure-training crawlers like `CCBot`/`GPTBot` if desired). No change made.

### INFORMATIONAL (not separately tracked IDs, flagged per audit instructions)

**I1 — `/about/` now returns a genuine 404 (was previously a noindex,follow page)**
- Evidence: `curl -sD - https://pepselect.com/about/` → `404 Not Found`.
- Not a regression from a tracked finding, but worth a sanity check: if this URL is linked anywhere internally (nav, footer, old backlinks) it should either be restored, 301'd to a relevant destination, or the link source removed. A quick grep of the current homepage/footer HTML did not surface an `/about/` link, so no active internal 404 was found in this pass — but a full sitewide `href="/about/"` sweep was outside this audit's crawl-budget for the session and should be double-checked if this page is expected to exist.

**I2 — `llms.txt` still 404 [GEO-01, informational, tracked as superseded/low-priority]**
- Confirmed still absent. No action recommended at this priority tier per the 8/18 ledger's own classification.

---

## Crawlability / Indexability — verified clean

- **Sitemap topology:** `sitemap_index.xml` lists five child sitemaps (`post-sitemap.xml`, `page-sitemap.xml`, `ps_compound-sitemap.xml`, `ps_coa_test-sitemap.xml`, `product-sitemap.xml`), all `200`, all XML well-formed. `product-sitemap.xml` includes all 15 catalog SKUs including `bacteriostatic-water-30ml`. `ps_compound-sitemap.xml` includes `/testing/` and all 8 compound-history hub pages. All 44 unique sitemap URLs returned `200` with 0 redirects (raw-crawl `status-check.tsv`, independently spot-confirmed).
- **Meta robots / canonicals:** `/shop/`, `/testing/`, `/product/glp3-r10/`, `/product/bacteriostatic-water-30ml/`, and the new guide URL all render `index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1` and a correct self-referencing canonical. No `X-Robots-Tag` header present anywhere sampled (no conflicting HTTP-level noindex).
- **Shop pagination (MAP-03):** `https://pepselect.com/shop/page/2/` → `404` (fresh check). `https://pepselect.com/shop/page/1/` → `301` → `https://pepselect.com/shop/`. `/shop/` itself → `200`. This is exactly the intended behavior — impossible paginated URLs are pruned, the canonical single-page catalog remains reachable.
- **Redirect chains:** all tested redirects are single-hop (0 intermediate hops): `http://` → `https://` (301), `http://www` → `https://` non-www (301), `/terms-of-service/` → `/terms-conditions/` (301, confirmed by orchestrator), `/product-category/research-compounds/` → `/shop/` (301, re-confirmed fresh, `x-redirect-by: Pep Select` custom rule), `/product/glp3-r10` (no trailing slash) → `/product/glp3-r10/` (301, WordPress canonical trailing-slash rule). No chain exceeded one hop in any test performed.
- **robots.txt:** allows all crawl-critical paths (Shop, Product, Testing, Guides); only disallows WooCommerce log/transient upload directories, `add-to-cart` query strings, and `/wp-admin/` (with `admin-ajax.php` explicitly allowed for AJAX-dependent front-end features like the checkout-upsell toggle and side-cart). `Sitemap:` directive present and correct.

## Orphan-page risk — MAP-01: confirmed STILL OPEN, evidence strengthened

The 8/18 ledger's decision text says Bacteriostatic Water "remains intentionally available through the cart upsell rather than the Shop catalog." This audit checked that claim directly and found the actual mechanism is **not a crawlable hyperlink at all**:

- `/shop/` renders exactly 14 `<a href="https://pepselect.com/product/...">` product links (verified by grep); `bacteriostatic-water-30ml` is not among them. This matches the beta.39 release note ("Shop retains fourteen unique product links") — i.e., this is confirmed-intended catalog scope, not a bug, but it does mean Shop provides zero link path to this SKU.
- No product page sampled (`nad`, `glp1-s10`, `glp2-t20`, `glp3-r10`, `bpc157-10`, `tesa-10`, `ss-31`, `tb500-10`) contains an `href` to `bacteriostatic-water-30ml` in cross-sell/related-product blocks.
- The homepage and `/testing/` hub contain no link to it either.
- The Cart page (`/cart/`, empty-cart state) loads `side-cart-upsell.css`/`.js` and `checkout-upsell.js`, and `checkout-upsell.js` does reference "Bacteriostatic Water" — but reading the script's own header comment confirms it is a **toggle switch that fires an AJAX add-to-cart/remove-from-cart request**, not an anchor tag: *"Turning the switch on adds one Bacteriostatic Water to the cart; turning it off removes it."* There is no `<a href="/product/bacteriostatic-water-30ml/">` anywhere in this flow — it's a stateful UI control, gated behind the Checkout page, which itself requires items already in the cart to render (an empty Checkout redirects to Cart per the 8/18 release notes).
- The product page itself remains fully indexable (200, correct canonical, `index,follow` meta robots) and is present in `product-sitemap.xml` with a fresh `lastmod`. So the URL is technically discoverable via the sitemap, but sitemap-only discovery with zero internal PageRank flow is a materially weaker signal than the rest of the catalog, which is both linked from Shop and in the sitemap.
- **Conclusion:** this is a genuine orphan page by the standard SEO definition (no crawlable internal hyperlink path from any indexed page). The 8/18 "cart upsell" framing overstates the mitigation — a JS toggle with no `href` provides zero link equity and is invisible to a crawler that doesn't execute cart-mutation AJAX calls. This remains a merchandising decision (add to Shop grid, or add a genuine `<a href>` reference from a relevant product page's description/FAQ, e.g. "commonly reconstituted with Bacteriostatic Water" linking to the product) rather than a code bug, so no code change was made in this read-only pass.

---

## Prior Finding Classifications

| ID | Original Priority | Prior State (8/18) | Current Classification | Evidence |
|---|---|---|---|---|
| GOOG-01 | Critical | Live technical work complete / Google validation pending | **VERIFIED FIXED** (crawl-path scope only; indexation itself out of scope for this agent) | Shop and sampled products are `200`, `index,follow`, correct self-referencing canonical, present in `product-sitemap.xml`/`sitemap_index.xml`, reachable via `/shop/` and robots.txt allows all crawl-critical paths. MAP-03 pagination trap removed. All prerequisites a crawler needs to index these pages are now in place on Live. Actual Google indexation status is GSC-dependent and covered by the seo-google agent. |
| GOOG-02 | High | Live source verified / Google validation pending | **VERIFIED FIXED** (crawl-path scope only) | `ps_compound-sitemap.xml` (fresh pull, 2026-08-20) includes `/testing/` (lastmod 2026-08-15) and all 8 compound-history hub URLs; `/testing/` itself returns 200 with `index,follow` and a correct canonical. Sitemap is correctly referenced from `sitemap_index.xml`. GSC discovery/indexation is out of scope for this agent. |
| GOOG-08 | Low | Not started | **STILL OPEN** | Fresh header pull on homepage and Shop shows none of `Strict-Transport-Security`, `Content-Security-Policy`, `X-Frame-Options`, `Cross-Origin-Opener-Policy` present. Only `X-Content-Type-Options: nosniff` is present. See H1/M1/M2/L1 above (this ID is the umbrella; TECH-01/02/03/04 are its components). |
| MAP-01 | Medium | Evaluated / merchandising input needed | **STILL OPEN** | Independently re-verified: Shop grid has 14/15 product links (Bacteriostatic Water excluded by design), zero product pages cross-link to it, and the "cart upsell" is confirmed via `checkout-upsell.js` source to be a non-anchor AJAX toggle on the Checkout page, not a crawlable link. Only discovery path is `product-sitemap.xml`. See "Orphan-page risk" section above for full evidence. |
| MAP-02 | Low | Superseded / no immediate fix | **SUPERSEDED** | `page-sitemap.xml` (raw-crawl, 2026-08-20) still shows the same 81-second bulk-resave signature (`2026-08-14T16:45:20Z`–`16:46:41Z`) across all 8 static/legal pages. No new edits, no regression — matches the original "no SEO rewrite solely to manufacture freshness" decision; correctly left untouched. |
| MAP-03 | Medium | Live verified | **VERIFIED FIXED** | Fresh check: `/shop/page/2/` → `404`; `/shop/page/1/` → `301` → `/shop/`; `/shop/` → `200`. Matches Live M3 release note exactly. |
| TECH-01 | High | Not started | **STILL OPEN** | `Strict-Transport-Security` absent from every response header sampled (homepage, Shop). See H1. |
| TECH-02 | Medium | Not started | **STILL OPEN** | `Content-Security-Policy` absent from every response header sampled. See M1. |
| TECH-03 | Medium | Not started | **STILL OPEN** | Neither `X-Frame-Options` nor a CSP `frame-ancestors` directive present. See M2. |
| TECH-04 | Low | Not started | **STILL OPEN** | Neither `Referrer-Policy` nor `Permissions-Policy` present. See L1. |
| TECH-05 | Low | Not started | **PARTIALLY FIXED** | Case-variant URLs still return 200 without redirecting (`/Shop/`, `/SHOP/`, `/Contact/`, `/FAQ/`, `/Cart/` all confirmed 200, 0 redirects) — the core finding is unresolved. However, new evidence shows (a) the exposure is narrower than originally scoped — Product/CPT routes are case-sensitive and correctly 404 (`/Product/bpc157-10/` → 404, `/Testing/` → 404) — and (b) every case-variant Page URL checked renders a correct lowercase self-referencing canonical, which mitigates duplicate-indexation risk even though the URL itself remains live and un-redirected. Rated partial rather than fully open because the practical SEO harm (duplicate indexation) is substantially mitigated by the canonical tag, even though the underlying redirect gap named in the finding still exists. |
| TECH-07 | Low | Superseded / no immediate fix | **SUPERSEDED** | IndexNow still not implemented (`indexnow.txt` key file 404; no key referenced in robots.txt). No change from 8/18; matches the original "optional, reconsider only if Bing becomes a priority" decision. No action taken. |
| GEO-09 | Low | Not started | **STILL OPEN** | robots.txt (fresh pull) still has no differentiated AI-crawler tokens; all AI bots fall under the wildcard `User-agent: *` group. See L3 — this is a pending policy decision for Paulo, not a code defect. |

### Summary of classifications

- VERIFIED FIXED: 3 (GOOG-01, GOOG-02, MAP-03)
- PARTIALLY FIXED: 1 (TECH-05)
- STILL OPEN: 6 (GOOG-08, MAP-01, TECH-01, TECH-02, TECH-03, TECH-04, GEO-09) — *(7, see note)*
- SUPERSEDED: 2 (MAP-02, TECH-07)
- REGRESSED: 0
- BLOCKED BY REAL EVIDENCE: 0

*(Note: STILL OPEN count is 7, not 6 — GOOG-08, MAP-01, TECH-01, TECH-02, TECH-03, TECH-04, GEO-09.)*

**Single most important still-open technical risk:** the complete absence of the baseline security-header stack (HSTS/CSP/X-Frame-Options/Referrer-Policy/Permissions-Policy — TECH-01 through TECH-04, rolled up under GOOG-08) is the highest-leverage open item in this scope. It is a same-origin fix at the Cloudflare/Kinsta edge (no WordPress plugin code required), touches every page on the site including the WooCommerce checkout, and has sat at "Not started" since 8/18 while five other milestones shipped. Recommend prioritizing HSTS and X-Frame-Options first (lowest regression risk, highest security value), then CSP in report-only mode given the site's real third-party script surface (GTM, Google Fonts, Elementor, YITH rewards, side-cart/checkout-upsell AJAX).

# INTEGRATED-02 — Medium & Low Findings, and Verified-Correct Inventory
> Part of the Pep Select integrated SEO audit — 2026-08-18 — target https://pepselect.com
> Files: INTEGRATED-00 context & scorecard · INTEGRATED-01 critical & high · INTEGRATED-02 medium, low & verified-correct · INTEGRATED-03 strategy & action plan · INTEGRATED-04 evidence & limitations
>
> Evidence classes used throughout: **[1]** Google Search Console (verified) · **[2]** PageSpeed Insights (laboratory) · **[3]** CrUX (real-user field) · **[4]** DataForSEO (third-party estimate) · **[5]** Crawler observation / inference.
> All findings below are reproduced **verbatim** from the specialist agent reports in `_agents/`; only the source-agent attribution line above each block was added.

**Contents: 35 Medium, 25 Low/Info findings, plus the verified-correct inventory of all 11 agents.**

## Index — Medium

- [GOOG-06] Product page mobile LCP sits just over the "Poor" threshold (4.2s) — Medium (Google APIs (GSC / PageSpeed / CrUX))
- [GOOG-07] Site-wide render-blocking CSS/JS is the dominant mobile performance bottleneck — Medium (Google APIs (GSC / PageSpeed / CrUX))
- [GOOG-09] No CrUX (real-user field) data available for this origin or any URL/form-factor tested — Medium (Google APIs (GSC / PageSpeed / CrUX))
- [GOOG-11] GA4 not configured — no organic session/conversion visibility — Medium (Google APIs (GSC / PageSpeed / CrUX))
- [DFS-07] Merchant Center feed and price annotations are table stakes in these SERPs — Medium (DataForSEO)
- [DFS-08] PAA questions across the two commercial SERPs form a ready-made trust-content map — Medium (DataForSEO)
- [DFS-09] "bpc-157 for sale" SERP intent is split — research vendors hold only 2 of 9 slots — Medium (DataForSEO)
- [TECH-02] No Content-Security-Policy header — Medium (Technical SEO)
- [TECH-03] No clickjacking protection (X-Frame-Options / frame-ancestors) — Medium (Technical SEO)
- [CONT-06] Scientific citations (DOI/PMID) are unlinked plain text — not machine-verifiable — Medium (Content & E-E-A-T)
- [CONT-07] Two near-duplicate FDA disclaimer paragraphs, worded inconsistently, both appear on every page — Medium (Content & E-E-A-T)
- [CONT-08] No author, reviewer, or credential signal exists anywhere on the site — Medium (Content & E-E-A-T)
- [CONT-09] Business trust signals (phone, physical address, named staff) are absent from the Contact page and footer; the one address that exists is buried in legal body text only — Medium (Content & E-E-A-T)
- [SCHEMA-02] Offer.seller is a disconnected duplicate of the site Organization entity — Medium (Schema / Structured Data)
- [SCHEMA-03] Dataset.creator on COA batch pages is a third disconnected "Pep Select" entity — Medium (Schema / Structured Data)
- [SCHEMA-04] Organization/OnlineStore entity has no `sameAs` anywhere sampled — Medium (Schema / Structured Data)
- [SCHEMA-05] Organization entity and /contact/ page carry no NAP (address/telephone/contactPoint) data — Medium (Schema / Structured Data)
- [SCHEMA-06] Product/Offer missing recommended trust and rich-result properties across all sampled products — Medium (Schema / Structured Data)
- [SCHEMA-09] Dataset schema on COA batch pages missing recommended `license` and `datePublished` — Medium (Schema / Structured Data)
- [MAP-01] Live product page has zero internal links (orphan risk) — Medium (Sitemap)
- [MAP-03] Unbounded `/shop/page/N/` pagination URLs return 200 with duplicate content and self-referencing canonicals, outside sitemap and unblocked — Medium (Sitemap)
- [PERF-04] Site-wide oversized PNG logo repeats the same byte waste on every template — Medium (Performance (lab))
- [PERF-06] Google Tag Manager is the dominant third-party cost on every template — Medium (Performance (lab))
- [PERF-07] `/testing/` loads a bloated 16-weight Google Fonts Roboto request not seen on other templates, plus a site-wide font-display gap — Medium (Performance (lab))
- [PERF-08] `/testing/` shows a large Speed Index/FCP gap (12.8 s vs 3.2 s) not present on the other three templates — Medium (Performance (lab))
- [VIS-05] Synthetic performance/CWV tooling (PageSpeed Insights, Lighthouse, and Googlebot's rendering pass) will measure the gate, not the real page — Medium (Visual / Mobile)
- [GEO-02] No Q&A structured data (`FAQPage`/`QAPage`) despite genuinely extractable Q&A content — Medium (GEO / AI Search)
- [GEO-04] Organization and lab-provider entities carry no `sameAs`/identity-graph links — Medium (GEO / AI Search)
- [GEO-07] COA compound-hub pages show real batch data as plain text but carry no `Dataset` (or any) structured data of their own — Medium (GEO / AI Search)
- [SXO-05] Product-page trust proof is sequenced after compliance and description content, reversing the SERP-winning trust-first pattern — Medium (SXO)
- [SXO-06] `/testing/` is a page type with no SERP peer, but its title/H1 and zero backlink profile don't yet capture that advantage — Medium (SXO)
- [SXO-07] No page type or CTA path exists for the bulk/wholesale buyer persona — Medium (SXO)
- [ECOM-06] Product Offer schema is missing GTIN/MPN, `priceValidUntil`, and `OfferShippingDetails` — Medium (E-commerce)
- [ECOM-07] Product descriptions follow a rigid one-paragraph template that reads as thin at scale — Medium (E-commerce)
- [ECOM-08] `/shop/` category page lacks ItemList/OfferCatalog schema as the catalog scales — Medium (E-commerce)

## Index — Low / Info

- [GOOG-08] Missing baseline security headers (CSP, HSTS, COOP, X-Frame-Options) flagged sitewide — Low (Google APIs (GSC / PageSpeed / CrUX))
- [GOOG-10] Repeated non-descriptive "Learn more" link text across product card templates — Low (Google APIs (GSC / PageSpeed / CrUX))
- [DFS-10] Domain link history begins 2026-07-03 — pepselect.com is ~6 weeks old to the link graph — Low (DataForSEO)
- [TECH-04] Missing Referrer-Policy and Permissions-Policy headers — Low (Technical SEO)
- [TECH-05] Case-variant URL serves 200 instead of redirecting to canonical — Low (Technical SEO)
- [TECH-07] IndexNow protocol not implemented — Low (Technical SEO)
- [CONT-10] "Notify me" out-of-stock modal text is duplicated per cross-sell item, inflating within-page repetition — Low (Content & E-E-A-T)
- [CONT-11] Homepage body copy never uses the word "peptide" despite it being the site's core topical term — Low (Content & E-E-A-T)
- [CONT-12] Freshness timestamps on 8 static/legal pages cluster in an 81-second window — a mechanical resave, not an editorial signal — Low (Content & E-E-A-T)
- [CONT-13] Brand name is inconsistent between marketing copy ("Pep Select") and the legal/mailing entity name ("PepSelect") — Low (Content & E-E-A-T)
- [CONT-14] Readability is stratified between plain-English pages and dense technical pages, with no bridging content — Low (Content & E-E-A-T)
- [SCHEMA-07] Homepage BreadcrumbList has only one ListItem (no real trail) — Low (Schema / Structured Data)
- [SCHEMA-08] No FAQPage schema found anywhere sampled, including /faq/ — Low (Schema / Structured Data)
- [SCHEMA-10] @context inconsistency between Yoast and WooCommerce JSON-LD emitters — Low (Schema / Structured Data)
- [MAP-02] Static/legal page lastmod values reflect a bulk resave, not real content edits — Low (Sitemap)
- [PERF-09] Unsized images create latent CLS risk despite CLS currently passing — Low (Performance (lab))
- [PERF-10] Unused CSS/JS bytes add measurable but secondary savings opportunity — Low (Performance (lab))
- [PERF-11] TTFB, edge caching, and compression are strong and are not contributing to the LCP/render-blocking problems above — Low (Performance (lab))
- [VIS-03] "Not a researcher? Exit" sends visitors off-site to google.com with no informational alternative — Low (Visual / Mobile)
- [VIS-04] Legal/disclaimer copy inside the gate renders below common mobile-readability guidance (~16px) — Low (Visual / Mobile)
- [GEO-01] `llms.txt` is missing (404) — Low (GEO / AI Search)
- [GEO-08] No visible author, reviewer, or "last updated" byline on YMYL-adjacent content — Low (GEO / AI Search)
- [GEO-09] robots.txt makes no distinction between AI search-retrieval crawlers and AI training-only crawlers — Low (GEO / AI Search)
- [SXO-08] The research gate offers only a binary in/out choice, with no low-friction path for an undecided top-of-funnel visitor — Low (SXO)
- [ECOM-09] Restricted-vertical Merchant Center/free-listing eligibility is an open policy question — owner verification required, no action taken — Low (E-commerce)

---

# MEDIUM FINDINGS

---

*Source: Google APIs (GSC / PageSpeed / CrUX) agent (`_agents/google.md`)*

### [GOOG-06] Product page mobile LCP sits just over the "Poor" threshold (4.2s)
- Priority: Medium
- Category: Core Web Vitals (Lab)
- Evidence class: 2-PageSpeed lab
- Evidence: Mobile PSI performance score 74/100 for https://pepselect.com/product/bpc157-10/. LCP = 4,201 ms ("4.2 s", Poor by a narrow margin over the 4,000 ms threshold, score 0.44). FCP 3,372 ms, TTI 5,064 ms, TBT 217.5 ms ("220 ms", Good but closer to the 200 ms INP-adjacent threshold), CLS 0.0004 (Good). Desktop on same URL: performance 95/100, LCP 1,006 ms (from retry run), CLS 0.001, TBT 10 ms. Failed audits: render-blocking-insight (est. savings 2,680 ms), image-delivery-insight (est. savings 160 KiB), unused-css-rules (est. savings 300 ms).
- Affected URLs: https://pepselect.com/product/bpc157-10/ (mobile) — likely representative of other product template pages given shared theme
- Reasoning: This page is the closest of the three to meeting the "Good" LCP tier, indicating the product template is somewhat lighter than the homepage/shop templates, but still crosses into "Poor" territory on mobile due to the same render-blocking pattern seen sitewide.
- Recommendation: Apply the same render-blocking/critical-CSS remediation as GOOG-04/GOOG-05 to the product template; optimize product gallery images (image-delivery-insight flagged 160 KiB of savings).
- Dependencies: Same root cause family as GOOG-04/GOOG-05.
- Failure check: Repeat mobile PSI run still shows LCP > 4,000 ms.
- Success check: Repeat mobile PSI run shows LCP ≤ 4,000 ms (moves out of Poor into at least Needs Improvement).
- Leading indicator: PSI mobile performance score for product template trending upward.

---

*Source: Google APIs (GSC / PageSpeed / CrUX) agent (`_agents/google.md`)*

### [GOOG-07] Site-wide render-blocking CSS/JS is the dominant mobile performance bottleneck
- Priority: Medium
- Category: Performance / Technical SEO
- Evidence class: 2-PageSpeed lab
- Evidence: render-blocking-insight failed on all three pages tested (mobile): homepage est. savings 2,530 ms, shop est. savings 2,950 ms, product est. savings 2,680 ms. Named render-blocking assets include theme CSS (`theme.css`), Elementor animation CSS bundles, jQuery core (`jquery.min.js`, 30,826 bytes with 1,201 ms wasted on shop page), WooCommerce CSS/JS.
- Affected URLs: https://pepselect.com/, https://pepselect.com/shop/, https://pepselect.com/product/bpc157-10/ (mobile) — theme-level issue likely sitewide
- Reasoning: The consistency of ~2.5-3s render-blocking savings across three different templates (home, category, product) indicates a shared theme/plugin loading pattern (Elementor + multiple WooCommerce add-ons + jQuery) rather than a page-specific issue, making this a single fix with sitewide leverage.
- Recommendation: Audit the Elementor/WooCommerce plugin stack for CSS/JS that can be deferred, inlined (critical path only), or conditionally loaded only on pages that need it (e.g., side-cart, points-and-rewards plugin assets loading on every page).
- Dependencies: Root cause for GOOG-04, GOOG-05, GOOG-06; fixing this should improve all three simultaneously.
- Failure check: Render-blocking-insight audit still reports >1,000 ms estimated savings after remediation.
- Success check: Render-blocking-insight audit savings estimate drops below 500 ms on all three templates.
- Leading indicator: Mobile PSI performance scores trending upward across templates on ad hoc re-checks.

---

*Source: Google APIs (GSC / PageSpeed / CrUX) agent (`_agents/google.md`)*

### [GOOG-09] No CrUX (real-user field) data available for this origin or any URL/form-factor tested
- Priority: Medium
- Category: Data Gaps / Measurement
- Evidence class: 3-CrUX field
- Evidence: All 10 CrUX/CrUX History queries executed (origin-level PHONE/DESKTOP History; page-level PHONE/DESKTOP History for /shop/ and /product/bpc157-10/; plus ALL-form-factor snapshot queries for /, /shop/, /product/bpc157-10/ via pagespeed_check.py) returned explicit "insufficient Chrome traffic volume for eligibility" / "No CrUX data" errors — zero exceptions.
- Affected URLs: https://pepselect.com (origin), https://pepselect.com/, https://pepselect.com/shop/, https://pepselect.com/product/bpc157-10/ — all form factors
- Reasoning: This is expected given GOOG-03 (near-zero organic traffic). CrUX requires a meaningful volume of real Chrome user sessions per origin/URL/form-factor combination over a 28-day rolling window; a site with essentially no search-driven or general traffic will not meet that threshold regardless of technical quality.
- Recommendation: Continue relying on PSI Lighthouse lab data (GOOG-04/05/06) as the CWV proxy until traffic volume is sufficient for CrUX eligibility. Re-check CrUX eligibility monthly as a leading indicator of real-world traffic growth, independent of GSC.
- Dependencies: Directly downstream of GOOG-03 (traffic volume); not something to "fix" directly — it resolves naturally as traffic grows post GOOG-01/GOOG-02 remediation.
- Failure check: CrUX History still returns "insufficient eligibility" at next audit cycle despite traffic growth elsewhere.
- Success check: Any one CrUX History query (origin or page-level) returns populated `metrics`/`collection_periods` data.
- Leading indicator: CrUX eligibility status (data present vs. absent) checked on a monthly cadence as a proxy for real-user traffic volume crossing Google's reporting threshold.

---

*Source: Google APIs (GSC / PageSpeed / CrUX) agent (`_agents/google.md`)*

### [GOOG-11] GA4 not configured — no organic session/conversion visibility
- Priority: Medium
- Category: Data Gaps / Measurement
- Evidence class: n/a (credential gap, not evidence-bearing)
- Evidence: `google_auth.py --check` reports `"ga4": {"available": false, "error": "Credentials found but no GA4 property ID configured. Set GA4_PROPERTY_ID or add 'ga4_property_id' to config."}`
- Affected URLs: sitewide (no GA4 property tied to pepselect.com in current config)
- Reasoning: Without GA4, this audit cannot correlate GSC impression/click data with on-site behavior (bounce rate, conversion rate, revenue) for organic landing pages, meaning the business impact of GOOG-01 through GOOG-03 cannot be fully quantified from Google-side data alone.
- Recommendation: Configure a GA4 property ID for pepselect.com and add it to the claude-seo Google API config to unlock Tier 2 reporting in future audits.
- Dependencies: Independent; unlocks fuller future audits but does not block current remediation priorities.
- Failure check: GA4 property ID still absent at next audit.
- Success check: `google_auth.py --check` reports `"ga4": {"available": true}` and `ga4_report.py` returns organic traffic data.
- Leading indicator: N/A until configured — this itself is the leading indicator to watch (presence/absence of a working GA4 property ID in config).

---

---

*Source: DataForSEO agent (`_agents/dataforseo.md`)*

### [DFS-07] Merchant Center feed and price annotations are table stakes in these SERPs
- Priority: Medium
- Category: Structured Data / Feeds
- Evidence class: [4-DataForSEO estimate]
- Evidence:
  - "buy research peptides": price annotations on pspeptides.com ("$39.99 – $139.99", "Free delivery over $150"), redrockpeptides.com ("$59 to $410", srsltid Merchant Center tag), mybiosource.com ("$240 to $995").
  - "bpc-157 for sale": prohealth.com shows rating 4.8 (84 votes) + $119.95; xenopeptides.com $65.00; biolongevitylabs.com $99.97; desertmobilemedical and prohealth URLs carry srsltid tags inside the AIO itself.
  - "research peptides" (pre-paid file): 2 of 9 organic listings arrived via srsltid Merchant Center tagging; 3 showed price annotations.
- Affected URLs: All future pepselect.com product URLs
- Reasoning: Across all three SERPs, roughly a third to half of vendor listings display price/rating enhancements sourced from Product structured data and Google Merchant Center feeds — and srsltid-tagged URLs appear even inside AI Overview citations. Listings without these enhancements compete with visually poorer snippets.
- Recommendation: Implement Product schema (price, availability, aggregateRating where legitimate) on every product page and submit a Merchant Center feed (free listings tier), with RUO-compliant product data.
- Dependencies: Depends on DFS-01; complements DFS-04 compound pages.
- Failure check: Merchant Center disapproves the feed on policy grounds (restricted products) — would require the free-listings/policy review path documented before further投入.
- Success check: Price annotation visible on pepselect.com snippets in a SERP re-check.
- Leading indicator: Merchant Center "approved products" count and GSC "Shopping tab" / product-snippet impressions.

---

---

*Source: DataForSEO agent (`_agents/dataforseo.md`)*

### [DFS-08] PAA questions across the two commercial SERPs form a ready-made trust-content map
- Priority: Medium
- Category: Content Strategy
- Evidence class: [4-DataForSEO estimate]
- Evidence:
  - "buy research peptides" PAA: "Can anyone buy research only peptides?" · "Where can I buy research peptides in the USA?" · "What is the most legit peptide company?" · "How to buy research peptides?" (all served with asynchronous AI Overview expansions).
  - "bpc-157 for sale" PAA: "Are peptides better injected or oral?"
  - "research peptides" PAA (pre-paid file): "What is the most trusted peptide company?" · "Do research peptides actually work?" · "Where can I buy research peptides in the USA?" · "What is the risk of taking peptides?"
  - Related searches, both new SERPs: "Best place to buy research peptides (online)", "Cheapest research peptides", "Best place to buy peptides online in USA", "BPC-157 peptide for sale USA", "Best BPC-157 peptide on the market", "BPC-157 cost per month".
- Affected URLs: Future pepselect.com educational/FAQ URLs
- Reasoning: The PAA set repeats the same four intents across queries: eligibility/legality of purchase, US sourcing, vendor trust, and safety/efficacy. Each maps to one page. PAA expansions are now AI-generated (asynchronous_ai_overview: true), so the same machine-readable, claim-first formatting that wins AIO (DFS-06) wins PAA.
- Recommendation: Create four pages matching the four recurring intents (who-can-buy/legality; where-to-buy-USA; how-we-verify-trust/COA; risks-and-handling), each with question-matching H2s and concise first-paragraph answers.
- Dependencies: Depends on DFS-01; reinforces DFS-06.
- Failure check: Pages indexed 90+ days with zero GSC impressions on question-form queries.
- Success check: GSC impressions on question queries; appearance in a PAA expansion on re-check.
- Leading indicator: GSC query report filtered to "how/where/what/can" peptide queries.

---

---

*Source: DataForSEO agent (`_agents/dataforseo.md`)*

### [DFS-09] "bpc-157 for sale" SERP intent is split — research vendors hold only 2 of 9 slots
- Priority: Medium
- Category: SERP Intent / Page Format
- Evidence class: [4-DataForSEO estimate]
- Evidence: Page-one composition for "bpc-157 for sale": supplements/retail (amazon.com #1, prohealth.com #2), health-content guide (riteaid.com #3), video (tiktok #4, youtube #9), research vendors (xenopeptides.com #5, biolongevitylabs.com #6), clinic/pharmacy (purehydrationspa #7, veniceapothecary #8). Winning research-vendor title formats: "BPC-157 Peptide For Sale | 5mg & 10mg, COA Verified" ($65.00 annotation) and "Buy BPC-157 (10mg) - 99% Purity USA-Made Peptide" ($99.97 annotation).
- Affected URLs: Future https://pepselect.com/ BPC-157 product page
- Reasoning: Despite KD 0, only two research-vendor slots exist on page one; the rest is consumed by other intents Google chose to blend. The realistic target is displacing/joining xenopeptides and biolongevitylabs at positions 5–6, not position 1 (Amazon). Their shared observable formula: compound + dosage options in title, COA/purity claim, single-product URL, price via structured data.
- Recommendation: Build the pepselect BPC-157 page to that exact spec (title = compound + mg options + COA claim; visible tiered pricing; COA download link) and replicate the template for tirzepatide/retatrutide/semaglutide where the same split likely applies.
- Dependencies: Depends on DFS-04 sequencing and DFS-07 structured data.
- Failure check: Page ranks for zero BPC-157-modified queries after 90 days indexed.
- Success check: Entry into top 100 for "bpc 157 for sale" or related-search variants ("BPC-157 peptide for sale USA").
- Leading indicator: GSC impressions for "bpc" query cluster.

---

---

*Source: Technical SEO agent (`_agents/technical.md`)*

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

---

*Source: Technical SEO agent (`_agents/technical.md`)*

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

---

*Source: Content & E-E-A-T agent (`_agents/content.md`)*

### [CONT-06] Scientific citations (DOI/PMID) are unlinked plain text — not machine-verifiable
- Priority: Medium
- Category: AI Citation Readiness
- Evidence class: 5-Crawler observation/inference
- Evidence: On `/product/bpc157-10/`, the "Research context" section renders as plain text: `"Wu W, et al. J Mol Med. 2017. DOI:10.1007/s00109-016-1488-y"` and `"Cell Commun Signal. 2026. DOI:10.1186/s12964-026-02694-6"`. Searching the raw HTML for `<a ... doi ...>` or any `doi.org` href returns zero matches: `grep -o '<a[^>]*doi[^>]*>' product_bpc157.html -i` → no output; `grep -o '.{50}doi.org.{50}' product_bpc157.html -i` → no output. The DOI strings are typed as static text, not hyperlinks.
- Affected URLs: All product pages carrying a "Research context" citation list (confirmed on `bpc157-10`, `tb500-10`, `nad`, `ss-31`, `motsc-10`, `pt-141`, `glutathione`; same template used sitewide).
- Reasoning: The stated goal of surfacing named labs, batch identifiers, and citations (confirmed elsewhere as a genuine differentiator — see Verified Correct) is only half-realized if the citations themselves are not one-click verifiable. For AI-citation readiness specifically, a linked `doi.org/10.xxxx/yyyy` is a stronger, more extractable factual anchor than a plain-text string that an LLM or fact-checker has to reconstruct into a URL itself; it is also what a skeptical human reader would want in a research-compound context to confirm a claim before trusting it.
- Recommendation: Description only — wrap each DOI in an `<a href="https://doi.org/{doi}">` link (and PMID citations in a link to `https://pubmed.ncbi.nlm.nih.gov/{pmid}/`), which is a template-level change to the citation-rendering component, not a per-product content edit.
- Dependencies: Should follow CONT-02 (fix the placeholder `[VERIFY DOI]` entries first, or the new links will 404/misdirect for those SKUs).
- Failure check: Links are added but point to a search page instead of the specific DOI/PMID record, or open in a way that breaks the existing citation-expand UI interaction (`View the N sources` toggle observed in the current markup).
- Success check: Every DOI/PMID string in a product's citation list is wrapped in a working outbound link to the correct external record, verified by spot-checking 3–5 products.
- Leading indicator: None without re-crawl; could be checked by the content owner via a manual click-through on a few product pages after deployment.

---

*Source: Content & E-E-A-T agent (`_agents/content.md`)*

### [CONT-07] Two near-duplicate FDA disclaimer paragraphs, worded inconsistently, both appear on every page
- Priority: Medium
- Category: Trustworthiness / Content Consistency
- Evidence class: 5-Crawler observation/inference
- Evidence: Exact-line-match diffing (CONT-03) surfaced two distinct 96–97-word FDA disclaimer paragraphs that are each identical across all 16 sampled pages but differ from each other: Variant A — `"FDA Disclaimer: The statements made within this website have not been evaluated by the US Food and Drug Administration. ... Pep Select is not a compounding pharmacy or chemical compounding facility as defined under 503A ... Pep Select is not an outsourcing facility as defined under 503B ..."` Variant B — `"FDA Disclaimer: The statements made within this website have not been evaluated by the U.S. Food and Drug Administration. ... This company is not a compounding pharmacy or chemical compounding facility as defined under 503A ..., and is not an outsourcing facility as defined under 503B ..."` The two differ in: "US" vs "U.S." abbreviation punctuation, self-reference ("Pep Select" vs "the impersonal "This company"), and sentence structure (two sentences vs. one compound sentence for the 503A/503B claims). Both appear to be present on every page tested (one in the age/research-gate overlay, the other in the page footer, based on their surrounding context in the stripped text).
- Affected URLs: Site-wide (both paragraphs found on all 16 sampled pages).
- Reasoning: Two independently-worded statements of the same regulatory disclaimer, one referring to the company by name and the other referring to it only as "this company," is the kind of inconsistency that suggests the disclaimer was updated in one location (likely when the gate overlay was added) without updating the other (the footer), or vice versa. For a compliance-critical statement in a YMYL-adjacent vertical, having two non-identical versions live simultaneously is a minor legal-hygiene risk and a clear content-consistency defect, independent of the boilerplate-volume issue in CONT-03.
- Recommendation: Description only — consolidate to a single canonical FDA disclaimer paragraph (legal should confirm which wording is authoritative) and reference/include it from one source in the template rather than maintaining two hand-authored copies in the gate and the footer.
- Dependencies: Overlaps with CONT-03; fixing this also reduces the boilerplate word count.
- Failure check: Consolidation updates the footer but the gate overlay (or vice versa) retains the old wording, so two variants still coexist.
- Success check: A fresh exact-line-match diff across all pages shows exactly one FDA disclaimer paragraph, byte-identical everywhere it appears.
- Leading indicator: None without re-crawl.

---

*Source: Content & E-E-A-T agent (`_agents/content.md`)*

### [CONT-08] No author, reviewer, or credential signal exists anywhere on the site
- Priority: Medium
- Category: Expertise (E-E-A-T)
- Evidence class: 5-Crawler observation/inference
- Evidence: `grep -il "author"` across all 16 captured HTML files matched only `privacy_policy.html`, `ruo_disclaimer.html`, and `terms_conditions.html` — inspection confirms these are incidental matches on the words "authorize"/"authorized" in legal body text, not author bylines. A targeted search for an `"author"` JSON-LD property in `home.html`, `testing_hub.html`, and `coa_reta30_batch.html` returned zero matches. No page in the sample shows a named individual, a "reviewed by," a staff/team page, or any credential (e.g., "PhD," "chemist," "lab director") anywhere in visible copy or structured data. The Contact page (see CONT-09) likewise names no individual.
- Affected URLs: Site-wide; explicitly checked on all 16 sampled pages.
- Reasoning: The Expertise pillar of E-E-A-T is weighted at 25% in this skill's model and, per Google's guidance, is judged partly on whether the content creator's expertise is identifiable. The site substitutes institutional/instrumentation trust signals (named independent testing labs on COA pages, e.g., "Freedom Diagnostics Testing," CAS numbers, DOIs) for personal expertise signals, which is a reasonable partial substitute for a compound catalog, but there is no equivalent for the editorial content itself (product descriptions, research-context summaries) — nothing in the visible page or in schema attributes those summaries to a named or credentialed author, and no "who selects and reviews these compounds" page exists.
- Recommendation: Description only — this is a content-strategy decision (adding an About/team page, or at minimum a "Compound selection & review process" page naming who is responsible for the catalog's scientific accuracy) rather than a technical fix; it should be planned alongside CONT-05 since new informational content and a credentialed voice reinforce each other.
- Dependencies: Related to CONT-05; both stem from the absence of any content beyond transactional/product/legal pages.
- Failure check: An About page is added but contains only marketing copy with no named person or verifiable credential.
- Success check: At least one page names a real individual or role with relevant qualifications and is linked from the site's primary navigation or footer.
- Leading indicator: None without re-crawl.

---

*Source: Content & E-E-A-T agent (`_agents/content.md`)*

### [CONT-09] Business trust signals (phone, physical address, named staff) are absent from the Contact page and footer; the one address that exists is buried in legal body text only
- Priority: Medium
- Category: Trustworthiness (E-E-A-T)
- Evidence class: 5-Crawler observation/inference
- Evidence: Full stripped text of `/contact/`: `"Contact / Questions about an order, a batch, or a compound? Send us a message and we'll reply within one business day. ... For anything else, email support@pepselect.com . We reply within one business day. Name / Email * / Subject / Message * / Website / Send message"` — no phone number, no street address, no named staff member anywhere on the page; it is a contact form plus one support email address. Separately, a real mailing address does exist on the site, but only inside the body text of two legal pages: `privacy_policy.main.txt` — `"...Mailing address: PepSelect, 2090 Baker Rd, Ste 304, Kennesaw, GA 30144."` and the identical sentence in `terms_conditions.main.txt`. This address does not appear in the sitewide footer boilerplate (confirmed absent from the 57-line identical-across-all-pages set in CONT-03) and does not appear on the Contact page itself.
- Affected URLs: `https://pepselect.com/contact/` (missing signals); `https://pepselect.com/privacy-policy/` and `https://pepselect.com/terms-conditions/` (where the address is present but buried).
- Reasoning: Google's guidance on Trust explicitly calls out contact information and business transparency as a signal a rater looks for, particularly on a page whose whole purpose is contact. A visitor (or an AI system trying to establish "is this a real, locatable business") who checks the Contact page specifically, or scans the footer, finds none of that — they would have to already know to open the Privacy Policy and read past its opening data-collection sections to find the one sentence with the mailing address. This is a "signal exists but is not surfaced where it is expected" problem, distinct from a total absence.
- Recommendation: Description only — surface the existing mailing address (and a phone number, if one exists for the business) on the Contact page itself and/or in the sitewide footer, rather than leaving it discoverable only inside legal-policy prose.
- Dependencies: None blocking; independent, low-effort change once the correct current address/phone are confirmed with the business owner.
- Failure check: Address is added to the footer but rendered in a way excluded from the crawlable DOM (e.g., only in an image or JS-injected element not present in raw HTML).
- Success check: A fresh crawl of `/contact/` and the sitewide footer shows the mailing address in the stripped text.
- Leading indicator: None without re-crawl.

---

*Source: Schema / Structured Data agent (`_agents/schema.md`)*

### [SCHEMA-02] Offer.seller is a disconnected duplicate of the site Organization entity
- Priority: Medium
- Category: Duplicate entity / graph integrity
- Evidence class: [5-Crawler observation/inference]
- Evidence: WooCommerce Product block on every sampled product page: `"seller": {"@type": "OnlineStore", "name": "Pep Select", "url": "https://pepselect.com"}` — no `@id`. Meanwhile Yoast's block on the same page defines the canonical entity at `"@id": "https://pepselect.com/#organization"` with full detail (logo, `hasMerchantReturnPolicy`, etc.).
- Affected URLs: `/product/bpc157-10/`, `/product/ghk-cu/`, `/product/ss-31/`
- Reasoning: Schema.org/Google parsers treat objects without `@id` as anonymous blank nodes. Because `Offer.seller` restates `name`/`url` instead of referencing `{"@id": "https://pepselect.com/#organization"}`, the "same real-world seller" is represented as two unconnected nodes on the same page. This dilutes entity consolidation that Google's Knowledge Graph and Merchant Center matching rely on.
- Recommendation: Change `Offer.seller` to reference the canonical organization by `@id` (`{"@id": "https://pepselect.com/#organization"}`) instead of repeating name/url inline. This is a WooCommerce Product-schema template change, described only.
- Dependencies: Depends on SCHEMA-01 (context alignment). Unblocks cleaner sameAs/NAP consolidation (SCHEMA-04/05).
- Failure check: Rich Results Test / manual graph inspection still shows two separate "Pep Select" organization-type nodes on the same page.
- Success check: Only one Organization/OnlineStore node with `@id` `#organization` appears per page, referenced by both Yoast's `publisher` and WooCommerce's `seller`.
- Leading indicator: No new count of "Organization" entities appears in Search Console structured-data reports beyond the expected single site-wide entity.

---

*Source: Schema / Structured Data agent (`_agents/schema.md`)*

### [SCHEMA-03] Dataset.creator on COA batch pages is a third disconnected "Pep Select" entity
- Priority: Medium
- Category: Duplicate entity / graph integrity
- Evidence class: [5-Crawler observation/inference]
- Evidence: On the COA leaf page, the Dataset node contains: `"creator": {"@type": "Organization", "name": "Pep Select", "url": "https://pepselect.com/"}` (no `@id`), while the same page's Yoast block already defines `"@id": "https://pepselect.com/#organization"` typed `OnlineStore`.
- Affected URLs: `/testing/retatrutide-30mg/nd_r30_060326/` (pattern applies to all `ps_coa_test` leaf pages per `ps_coa_test-sitemap.xml`, e.g. `/testing/ghk-cu-50-mg/psgkcu5071926gx/`, `/testing/tb-500-10-mg/tb10-6926/`, etc.)
- Reasoning: Same root cause as SCHEMA-02 — this is now the third distinct, disconnected node describing "Pep Select" found across the sampled pages (Yoast's `#organization` OnlineStore, WooCommerce's anonymous Offer.seller OnlineStore, and now Dataset's anonymous Organization creator). None share an `@id`, so no single entity accumulates all the trust signals (logo, return policy, eventual sameAs) that Google could otherwise consolidate.
- Recommendation: Point `Dataset.creator` at `{"@id": "https://pepselect.com/#organization"}` instead of restating name/url. Description only.
- Dependencies: Same remediation pattern as SCHEMA-02; can be batched together as one graph-consolidation change.
- Failure check: Structured Data Testing shows Dataset.creator as an anonymous node distinct from the site's main Organization.
- Success check: Dataset.creator resolves to the same `@id` used sitewide for Pep Select.
- Leading indicator: None directly observable in GSC; verify via spot-check of Rich Results Test output after the change.

---

*Source: Schema / Structured Data agent (`_agents/schema.md`)*

### [SCHEMA-04] Organization/OnlineStore entity has no `sameAs` anywhere sampled
- Priority: Medium
- Category: Organization / entity building
- Evidence class: [5-Crawler observation/inference]
- Evidence: The `#organization` node (typed `OnlineStore`) on every sampled page contains only `name`, `url`, `logo`/`image`, and `hasMerchantReturnPolicy`. No `sameAs` array is present on homepage, shop, testing index, testing compound page, COA leaf page, product pages, or contact page.
- Affected URLs: site-wide (`https://pepselect.com/#organization`, verified absent on `/`, `/shop/`, `/testing/`, `/testing/retatrutide-30mg/`, `/testing/retatrutide-30mg/nd_r30_060326/`, `/product/bpc157-10/`, `/product/ghk-cu/`, `/product/ss-31/`, `/contact/`)
- Reasoning: `sameAs` linking the site's Organization entity to owned profiles (e.g., verified social/business profiles) is a standard, low-risk way to help Google disambiguate the brand entity for Knowledge Panel and Merchant Center matching. Its complete absence across every sampled page indicates it was never configured in the Yoast Organization settings (or the corresponding fields are empty), not a per-page inconsistency.
- Recommendation: If Pep Select maintains verified official profiles (business social accounts, Wikidata/Crunchbase if applicable, verified marketplace profiles), add them as a `sameAs` array on the `#organization` node via Yoast's Organization settings ("Social profiles" field), each URL absolute. Do not fabricate placeholder profile URLs. Description only.
- Dependencies: None; independent of SCHEMA-01–03.
- Failure check: `sameAs` remains absent after Yoast social-profile fields are checked and populated (indicates a template override elsewhere).
- Success check: `#organization` node includes a `sameAs` array of verified, live, absolute URLs.
- Leading indicator: Google Knowledge Panel (if/when one appears for the brand name) reflects the linked profiles.

---

*Source: Schema / Structured Data agent (`_agents/schema.md`)*

### [SCHEMA-05] Organization entity and /contact/ page carry no NAP (address/telephone/contactPoint) data
- Priority: Medium
- Category: Organization / ContactPage
- Evidence class: [5-Crawler observation/inference]
- Evidence: `/contact/` emits only generic `WebPage` + `BreadcrumbList` + the sitewide `WebSite`/`OnlineStore` blocks (identical structure to every other page — see full JSON-LD excerpt captured for this page, which contains no `ContactPage` type, `address`, `telephone`, `email`, or `ContactPoint` property anywhere). The `#organization` node itself also has no `address`, `telephone`, or `contactPoint` on any sampled page.
- Affected URLs: `https://pepselect.com/contact/` (primary), plus the sitewide `#organization` node
- Reasoning: This is an opportunity gap rather than a spec violation — generic `Organization`/`OnlineStore` schema does not require address/telephone, and an online-only research-compound retailer with no physical storefront may intentionally omit a mailing address for operational/privacy reasons. However, `/contact/` is the one URL in the sample where a dedicated `ContactPage` type and/or `ContactPoint` (e.g., `contactType: "customer service"`, `email`, or a contact form URL) would be directly on-topic and low-risk to add, and its complete absence means the contact page currently carries zero schema value beyond generic WebPage/Breadcrumb.
- Recommendation: Consider changing `/contact/` page schema type from generic `WebPage` to `ContactPage`, and/or adding a `ContactPoint` (with `contactType`, and `email`/`url` to the existing contact form — no telephone/address if Pep Select does not publish one) to the `#organization` node. This is optional and should only be implemented with real, verifiable values — no placeholder data. Description only.
- Dependencies: None.
- Failure check: N/A (opportunity, not a break) — re-check only if Pep Select decides to implement.
- Success check: `/contact/` emits `ContactPage` type and/or a `ContactPoint` with real values validated by Rich Results Test with zero errors.
- Leading indicator: N/A — no rich result exists for ContactPoint alone; this is a trust/entity-clarity improvement, not a SERP feature.

---

*Source: Schema / Structured Data agent (`_agents/schema.md`)*

### [SCHEMA-06] Product/Offer missing recommended trust and rich-result properties across all sampled products
- Priority: Medium
- Category: Product / Offer
- Evidence class: [5-Crawler observation/inference]
- Evidence: All 3 sampled Product blocks contain only `name`, `url`, `description`, `image`, `sku`, `brand.name`, and one `Offer` with `price`/`priceCurrency`/`availability`/`hasMerchantReturnPolicy`. None contain `aggregateRating`, `review`, `gtin`/`gtin8`/`gtin12`/`gtin13`/`gtin14`, `mpn`, `priceValidUntil`, `itemCondition`, or `OfferShippingDetails`. Example (BPC-157): `{"@type":"Product","sku":"BPC157-10","offers":[{"@type":"Offer","price":"38.99","priceCurrency":"USD","availability":"https://schema.org/OutOfStock", ...}],"brand":{"@type":"Brand","name":"Pep Select"}}` — no rating/review block anywhere in the 5 JSON-LD blocks sampled site-wide.
- Affected URLs: `/product/bpc157-10/`, `/product/ghk-cu/`, `/product/ss-31/` (pattern applies to all WooCommerce products)
- Reasoning: Google's Product structured data requires only `name` plus one of `offers`/`review`/`aggregateRating` for basic eligibility, which is met. But `aggregateRating`/`review`, `gtin`/`mpn`, `priceValidUntil`, and `itemCondition` are recommended properties Google documents for full Product rich-result and Merchant Listing eligibility (star ratings, price-drop badges, shopping-tab enrichment). Their complete absence across all 3 sampled products suggests either WooCommerce product reviews are disabled/unpopulated site-wide, or the review data isn't being passed into schema even where reviews exist — this should be verified against whether the Product Reviews feature is enabled in WooCommerce settings.
- Recommendation: (1) Confirm whether WooCommerce product reviews are enabled/collected; if so, verify why `aggregateRating`/`review` are not emitted (may need a WooCommerce Schema/Yoast setting checked). (2) If GTIN/MPN identifiers exist for compounds (unlikely for research peptides without retail barcodes — verify before adding), add them; otherwise this may not apply to the catalog. (3) Consider adding `priceValidUntil` to Offers to support price-related rich results. Description only — no markup deployed.
- Dependencies: Reviews decision is a business/compliance question outside this audit's scope (research-peptide RUO context may intentionally avoid consumer-style star ratings) — flag for Paulo's decision, not to be assumed.
- Failure check: Rich Results Test continues to show Product as valid but with only baseline fields; Search Console "Merchant listings" report (if enrolled) shows missing recommended fields.
- Success check: Where applicable and accurate, `aggregateRating`/`review`/`priceValidUntil` appear and validate with zero errors/warnings in Rich Results Test.
- Leading indicator: Search Console Products report "Items with issues/warnings" count for missing recommended fields.

---

*Source: Schema / Structured Data agent (`_agents/schema.md`)*

### [SCHEMA-09] Dataset schema on COA batch pages missing recommended `license` and `datePublished`
- Priority: Medium
- Category: Dataset / DataDownload
- Evidence class: [5-Crawler observation/inference]
- Evidence: Sampled Dataset node contains `dateCreated: "2026-06-25"` but no `datePublished`, and no `license` property anywhere in the block (full Dataset JSON-LD captured for `/testing/retatrutide-30mg/nd_r30_060326/#dataset`).
- Affected URLs: `/testing/retatrutide-30mg/nd_r30_060326/` (pattern applies to all `ps_coa_test-sitemap.xml` leaf pages)
- Reasoning: Google's Dataset structured data guidelines list `license` and `datePublished` among recommended properties for Dataset/Google Dataset Search eligibility. `license` in particular clarifies usage rights for the published lab-report data (PDF + images), which is useful given this data is explicitly `isAccessibleForFree: true` and distributed as a `DataDownload`.
- Recommendation: Consider adding a `license` URL (pointing to Pep Select's own terms/usage statement for the COA data, not a generic open-data license unless one is actually intended) and a `datePublished` value (the date the batch report page went live, distinct from `dateCreated` which appears to be the lab test date) to each Dataset node. Description only.
- Dependencies: None.
- Failure check: Google Dataset Search / Rich Results Test still lists these as missing recommended fields.
- Success check: Dataset validates with `license` and `datePublished` present and accurate.
- Leading indicator: N/A (Dataset Search indexing is not observable via Search Console for this property directly); spot-check via Rich Results Test after change.

---

*Source: Sitemap agent (`_agents/sitemap.md`)*

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

---

*Source: Sitemap agent (`_agents/sitemap.md`)*

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

---

*Source: Performance (lab) agent (`_agents/performance.md`)*

### [PERF-04] Site-wide oversized PNG logo repeats the same byte waste on every template
- Priority: Medium
- Category: Image optimization
- Evidence class: 2-PageSpeed lab
- Evidence: `Logo_Pepselect_Whitebackground-1.png` appears as a top-5 `image-delivery-insight` offender on all four audited templates with an identical footprint each time: totalBytes 52,773 B, wastedBytes 50,576 B (96% waste). A second, differently-sized instance (`-768x185.png`, 33,183 B / wastedBytes 30,633 B, 92% waste) also recurs on Home and Product/Shop lists.
- Affected URLs: `/`, `/product/bpc157-10/`, `/shop/`, `/testing/` (site-wide — this is almost certainly the header/age-gate logo asset loaded on every page template)
- Reasoning: A 96% waste ratio on a small logo indicates the served file is a raw, uncompressed or oversized PNG rather than an optimized format sized to its actual display box (the item snippet shows it rendering inside the age-gate card and header, both small elements). Because it loads on every page, this is a small-magnitude-per-page but site-wide-frequency finding — fixing it once (one asset) improves every template simultaneously with no per-template work.
- Recommendation: Re-export the logo as a compressed PNG-8/WebP or (given it's a flat-color brand mark) SVG, sized to its actual rendered dimensions, and reuse the single optimized asset across header and age-gate contexts.
- Dependencies: None. Unblocks a small, uniform LCP/byte-weight improvement across all four templates simultaneously (low effort, site-wide payoff — good candidate to sequence early).
- Failure check: Logo file continues to appear as a top-5 `image-delivery-insight` item with >50% wastedBytes after replacement.
- Success check: Logo no longer appears in `image-delivery-insight` top offenders on any template; total transferred bytes for the logo drop by roughly the 50 KB currently wasted, on every page load.
- Leading indicator: `totalBytes` for the logo URL in `image-delivery-insight`, checked on any single template (result generalizes to all four since it's the same asset).

---

*Source: Performance (lab) agent (`_agents/performance.md`)*

### [PERF-06] Google Tag Manager is the dominant third-party cost on every template
- Priority: Medium
- Category: Third-party scripts / INP
- Evidence class: 2-PageSpeed lab
- Evidence: `third-parties-insight` audit, consistent across all four templates: Google Tag Manager — transferSize 186,574 B, mainThreadTime 130–191 ms (Home 147.7 ms, Product 130.1 ms, Shop 109.5 ms, Testing 190.8 ms — the highest of any run). Google Fonts — 140,750–151,684 B transfer, 0 ms main-thread (font bytes, not execution cost). `unused-javascript` audit flags the GTM script itself (`gtag/js?id=GT-NNQ4N6DP`) with wastedBytes 75,516–76,075 B (~41% of its own transferred size) on every template.
- Affected URLs: `/`, `/product/bpc157-10/`, `/shop/`, `/testing/`
- Reasoning: GTM alone accounts for 110–191 ms of main-thread time on every page — a meaningful fraction of the ≤200 ms INP "Good" budget before any first-party script or user interaction is counted, and it ships ~41% unused code on every load. This is a third-party dependency outside direct template control (tag configuration lives in GTM, not the codebase), so it is flagged separately from first-party script bloat (PERF-05).
- Recommendation: Review the GTM container for tags that can be trimmed, consolidated, or loaded conditionally (e.g., only on pages where the corresponding pixel/tag is actually needed), and confirm GTM is loaded with appropriate loading strategy (not blocking initial render) rather than removing GTM itself — this is a configuration/business decision requiring approval, not a code change.
- Dependencies: Requires GTM container access/approval (outside this audit's read-only scope) to action. No dependency on PERF-01–05.
- Failure check: `third-parties-insight` mainThreadTime for GTM remains >100 ms after container review.
- Success check: GTM mainThreadTime drops below ~50 ms and/or `unused-javascript` wastedBytes for the GTM script drops materially.
- Leading indicator: `third-parties-insight` GTM `mainThreadTime` value, checked per template.

---

*Source: Performance (lab) agent (`_agents/performance.md`)*

### [PERF-07] `/testing/` loads a bloated 16-weight Google Fonts Roboto request not seen on other templates, plus a site-wide font-display gap
- Priority: Medium
- Category: Font loading / render-blocking
- Evidence class: 2-PageSpeed lab
- Evidence: `render-blocking-insight` on `/testing/` lists a Google Fonts request for family `Roboto:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&display=swap` with wastedMs 901 — the single largest render-blocking item found on any of the four templates. `font-display-insight` flags `Woo-Side-Cart.ttf` (side-cart plugin icon font) as missing font-display optimization on all four templates: wastedMs 50 (Home), 45 (Product), 110 (Shop), 80 (Testing). Font resource counts: 6–7 requests, 141,733–152,666 B per template.
- Affected URLs: `/testing/` (Roboto weight bloat, page-specific); `/`, `/product/bpc157-10/`, `/shop/`, `/testing/` (font-display gap, site-wide)
- Reasoning: Requesting all 16 Roboto weight/style combinations (regular + italic × 8 weights) when a typical page uses 2–4 weights forces the browser to download and parse a far larger font-face declaration set than needed, and `render-blocking-insight` confirms this specific request is currently the worst single blocker measured across the entire audit (901 ms). The `Woo-Side-Cart.ttf` font-display gap is smaller in magnitude (45–110 ms) but present on every template, indicating the plugin's icon font is not using `font-display: swap` or equivalent, risking brief invisible-text/icon flashes (FOIT).
- Recommendation: On `/testing/`, request only the specific Roboto weights actually used by that page's typography instead of the full 16-variant family string. Site-wide, add or verify `font-display: swap` (or `optional`) on the side-cart plugin's icon font declaration.
- Dependencies: None on other findings. The Roboto fix is isolated to whatever template/page-builder element renders `/testing/`; the font-display fix touches the side-cart plugin's CSS enqueue only, not plugin core files.
- Failure check: `render-blocking-insight` still lists the full 16-weight Roboto request on `/testing/` after remediation, or `font-display-insight` still flags `Woo-Side-Cart.ttf` on any template.
- Success check: Roboto request on `/testing/` is reduced to the actually-used weight subset and its `wastedMs` drops sharply; `Woo-Side-Cart.ttf` no longer appears in `font-display-insight`.
- Leading indicator: `font-display-insight` `total_items` count (currently 1 per template) and the Roboto request's `wastedMs` value on `/testing/`.

---

*Source: Performance (lab) agent (`_agents/performance.md`)*

### [PERF-08] `/testing/` shows a large Speed Index/FCP gap (12.8 s vs 3.2 s) not present on the other three templates
- Priority: Medium
- Category: Rendering / visual progress
- Evidence class: 2-PageSpeed lab
- Evidence: Speed Index — Home 3.4 s, Product 3.3 s, Shop 3.7 s, Testing 12.8 s. FCP is nearly identical across all four templates (3.2–3.3 s), but Testing's Speed Index is 3.5–3.9x higher than the other three despite a similar FCP and a lower total request count (87 vs 113–116 on the others) and lower Total Blocking Time is not the cause (130 ms, the highest of the four but not extreme).
- Affected URLs: `/testing/`
- Reasoning: Speed Index measures how quickly visible content is painted in, not just when the first pixel appears — a large gap between FCP and Speed Index on one template only (while the other three are consistent) indicates something specific to this page's rendering sequence (e.g., large above-the-fold content painting in late, or a heavy layout/reflow step after first paint) rather than a site-wide condition. This is flagged as an anomaly requiring template-specific investigation rather than a diagnosed root cause, since the bundled audits captured here (render-blocking, image-delivery, DOM size) do not by themselves explain a 4x Speed Index multiplier.
- Recommendation: Investigate `/testing/`'s above-the-fold rendering sequence specifically (e.g., via a Lighthouse trace/filmstrip for this URL) to identify what is painting late; do not apply a generic fix without that diagnosis.
- Dependencies: Needs a dedicated trace-level look at `/testing/` (out of scope for this bundled-audit pass) before a specific recommendation can be made.
- Failure check: Speed Index on `/testing/` remains disproportionately high relative to its own FCP after the render-blocking (PERF-02) and font (PERF-07) fixes specific to this page are applied.
- Success check: Speed Index on `/testing/` falls into the same 3–4 s range as the other three templates.
- Leading indicator: `speed-index` `display` value for `/testing/` in a follow-up `pagespeed_check.py` run.

---

*Source: Visual / Mobile agent (`_agents/visual.md`)*

### [VIS-05] Synthetic performance/CWV tooling (PageSpeed Insights, Lighthouse, and Googlebot's rendering pass) will measure the gate, not the real page
- Priority: Medium
- Category: Above-the-fold / Core Web Vitals measurement integrity
- Evidence class: [5-Crawler observation/inference]
- Evidence: The gate is present in the raw server-rendered HTML (not client-injected after load) and is paint-eligible immediately: its logo `<img ... loading="eager">` and the "Research Access Verification" heading are the first large, above-the-fold content painted for any session without the `71326_cookie` cookie. Any Lighthouse/PageSpeed Insights run, and any Googlebot rendering pass, starts cookie-less by default.
- Affected URLs: Sitewide — homepage, product, shop, and `/testing/` all confirmed to serve the same gate on cold requests.
- Reasoning: Largest Contentful Paint, Cumulative Layout Shift, and above-the-fold visual-completeness metrics captured by lab tools (and potentially CrUX field data for a meaningful share of real first-time sessions) will reflect the gate's logo/heading/card, not the actual homepage hero, product hero image, or shop grid. This means prior or future LCP/CLS optimization work on the real templates can be masked or misrepresented by metrics that are actually measuring the gate, and any performance regression in the real templates could go undetected if testing tools are only ever hitting the gated state.
- Recommendation: Describe (not implement) a testing protocol that accounts for this: run performance/CWV audits both against the gated (cookie-less) state and against a state with the `71326_cookie` consent cookie pre-set, so real-template performance is measured separately from gate performance. Document which of the two states any given CWV report reflects.
- Dependencies: Unblocks more accurate future performance audits; depends on whichever tool/harness is used supporting cookie pre-seeding for a "post-gate" test pass.
- Failure check: Future PSI/Lighthouse/CWV reports for this domain do not specify which state (gated vs. post-gate) was measured, or continue to report the gate's own logo/heading as the LCP element without flagging it.
- Success check: Audit reports going forward explicitly separate "first-visit / gated" and "returning-visitor / post-gate" performance results.
- Leading indicator: LCP element identified in PSI/Lighthouse reports for a cookie-less run — currently expected to be the gate logo or heading rather than a homepage/product hero element.

---

*Source: GEO / AI Search agent (`_agents/geo.md`)*

### [GEO-02] No Q&A structured data (`FAQPage`/`QAPage`) despite genuinely extractable Q&A content
- Priority: Medium
- Category: Structural Readability
- Evidence class: 5-Crawler observation
- Evidence: Raw HTML of `/faq/` and the homepage contains real, server-rendered, question-based `<h3>` headings paired with self-contained `<p>` answers inside `<div class="...__accordion-panel" ... hidden>` — e.g. homepage: `<h3>...<span>What does "for research use only" mean?</span></h3>` → `<p>Every compound in the catalog is supplied for laboratory research only. Nothing we sell is intended for use in humans or animals.</p>`. The `hidden` attribute is CSS/JS-only; the text is present in the raw DOM a non-JS crawler receives. The JSON-LD graph on both pages (`structured_data.blocks[0].types`) contains only `BreadcrumbList, EntryPoint, ImageObject, ListItem, MerchantReturnPolicy, OnlineStore, PropertyValueSpecification, ReadAction, SearchAction, WebPage, WebSite` — **no `FAQPage` or `Question`/`Answer` types anywhere**.
- Affected URLs: `https://pepselect.com/faq/` (15 Q&A pairs across 6 groups), `https://pepselect.com/` (5 Q&A pairs in the homepage accordion)
- Reasoning: A prior full-site SEO audit correctly noted that adding `FAQPage` markup would win no rich-result real estate because Google retired FAQ rich results on 2026-05-07 — that is a Google-SERP-feature judgment. GEO is a different objective: LLM ingestion pipelines and AI-answer engines use structured Q&A markup as an explicit, low-ambiguity signal for "this block is a self-contained answer to this exact question," independent of whether it renders a SERP accordion. The content itself already meets the citability bar (direct, self-contained, 20-75 words per answer); it is only unmarked.
- Recommendation: Add `FAQPage` JSON-LD to `/faq/` and the homepage accordion, mapping each existing `<h3>` question / `<p>` answer pair one-to-one into `mainEntity` → `Question` → `acceptedAnswer` → `Answer`. No visible content changes required — this is markup-only.
- Dependencies: None; can ship independently. Pairs naturally with GEO-06 (new informational content should also carry this markup from day one).
- Failure check: Schema validator shows `FAQPage` present but Google Search Console reports it as unused/ignored, and no measurable change in AI-referral traffic over 8–12 weeks — indicates the constraint was citation-worthiness of the content, not its markup.
- Success check: Rich Results Test / schema.org validator confirms valid `FAQPage` graph nodes on both URLs.
- Leading indicator: Referrer traffic from `chatgpt.com`, `perplexity.ai`, `bing.com/chat`, `google.com` (AI Overview click-throughs) in GA4, tracked before/after.

---

---

*Source: GEO / AI Search agent (`_agents/geo.md`)*

### [GEO-04] Organization and lab-provider entities carry no `sameAs`/identity-graph links
- Priority: Medium
- Category: Authority & Brand Signals
- Evidence class: 5-Crawler observation
- Evidence: Full JSON-LD graph pulled from the deepest schema on the site (`/testing/ghk-cu-50-mg/psgkcu5071926gx/`, the COA batch page) shows the site's own entity typed `"@type": "OnlineStore", "@id": "https://pepselect.com/#organization"` with `name`, `url`, `logo` — **no `sameAs` array anywhere**. The lab that performed the testing appears only as `"provider": {"@type": "Organization", "name": "Freedom Diagnostics Testing"}` — no `url`, no `sameAs`, no way for a consumer of this JSON-LD to verify the lab is a real, findable entity. A sitewide grep of the homepage HTML for `youtube|reddit|wikipedia|linkedin|instagram|twitter|facebook|tiktok` in `href` attributes and for a `"sameAs"` schema property returned **zero matches**.
- Affected URLs: Domain-wide (Organization entity referenced from every page's `@graph`); lab-provider entity specifically on all 9 COA batch pages in `ps_coa_test-sitemap.xml`.
- Reasoning: AI engines and knowledge-graph-dependent retrieval (Google AI Overviews, Bing Copilot) resolve entities partly through `sameAs` cross-references to independently verifiable profiles. Zero `sameAs` on the Organization node, combined with the confirmed absence of any social/video/wiki presence (see GEO-05), means there is no machine-readable path for an engine to disambiguate "Pep Select" from an unknown or corroborate its claims externally. The unlinked lab-provider entity compounds this: "Freedom Diagnostics Testing" is asserted but not independently anchored, weakening the verifiability chain that is otherwise this site's strongest asset.
- Recommendation: Add a `sameAs` array to the Organization schema node once real, active social/business profiles exist (do not fabricate placeholder links). If the testing lab has a public website, add its `url` to the `provider` node in the Dataset schema.
- Dependencies: Gated on GEO-05 — there must be real off-site profiles to link before `sameAs` is meaningful; adding `sameAs` to profiles that don't exist or are dormant would be counterproductive.
- Failure check: Adding `sameAs` links to inactive or unrelated profiles with no follow-through would not improve entity resolution and could read as manipulative — verify each linked profile is genuinely active before publishing.
- Success check: Schema validator shows a non-empty `sameAs` array on the Organization node resolving to live, controlled profiles.
- Leading indicator: Google Knowledge Graph / Bing Entity API presence for "Pep Select" (manual spot-check search), checked quarterly.

---

---

*Source: GEO / AI Search agent (`_agents/geo.md`)*

### [GEO-07] COA compound-hub pages show real batch data as plain text but carry no `Dataset` (or any) structured data of their own
- Priority: Medium
- Category: Structural Readability
- Evidence class: 5-Crawler observation
- Evidence: `https://pepselect.com/testing/ghk-cu-50-mg/psgkcu5071926gx/` (a single-batch leaf page, one of only 9 URLs listed in `ps_coa_test-sitemap.xml`) carries a full JSON-LD `Dataset` node: `variableMeasured` (7 `PropertyValue` entries — claimed content, average net content, purity 99.85%, identity, heavy metals, sterility, fentanyl screen), `measurementTechnique: ["LC-MS","HPLC","Immunoassay"]`, `provider.name: "Freedom Diagnostics Testing"`, `dateCreated: "2026-07-24"`, and a `DataDownload` node pointing at a PDF. By contrast, its parent hub page `https://pepselect.com/testing/retatrutide-20mg/` — one level up in the breadcrumb (`Home > Certificate of Analysis Archive > [Compound]`) — renders detailed batch facts as plain HTML text (verified verbatim: *"Batch PSRT2062926JP Tested July 28, 2026 ILS Labs Partial QC Did not pass release review Purity 100% ... Identity LC-MS ... Heavy Metals Arsenic (As) ≤ 1.5 ppm ... Fentanyl Screen Not detected"*) but its JSON-LD graph (`structured_data.blocks[0].types`) contains **only** `BreadcrumbList, EntryPoint, ImageObject, ListItem, MerchantReturnPolicy, OnlineStore, PropertyValueSpecification, SearchAction, WebPage, WebSite` — no `Dataset`, no `Article`, no `ItemList` of the batches shown. This was confirmed under both raw fetch and forced full-JS render (`--mode always`) — the schema gap is real, not a client-rendering artifact.
- Affected URLs: All compound-hub `/testing/{slug}/` pages (the crawl path between the archive index `/testing/` and each batch leaf page); the archive index `/testing/` itself has the same gap (only `CollectionPage`/`OnlineStore`-level schema, no `ItemList` of compounds).
- Reasoning: The hub page is the URL a query like "GHK-CU purity test" or "retatrutide batch results" is most likely to resolve to (it ranks above the batch-specific leaf page in the site's own information architecture and carries the compound-level title). It already contains the same category of extractable facts as the leaf page — in prose/table form — but nothing tells a schema-parsing AI pipeline "this is measured, sourced, dated laboratory data" the way the leaf page's `Dataset` markup does. The most citable structured data on the entire site is one click too deep to be the page most queries would land on.
- Recommendation: Add an `ItemList` (or a `Dataset` summarizing the current/latest batch) to each compound-hub page, referencing the same `variableMeasured` pattern already proven on the leaf pages, plus an `ItemList` on `/testing/` itself listing all compound hub URLs.
- Dependencies: Should be built from the same data source that already generates the leaf-page `Dataset` nodes, to avoid a second content-governance burden. Feeds GEO-03 (product description data reuse).
- Failure check: If hub-level schema is added but leaf-page traffic/citations don't shift and hub-level citations don't appear either, the gap was not schema-depth but off-site authority (GEO-05) — don't over-invest in markup granularity beyond this fix.
- Success check: Schema validator shows `Dataset` or `ItemList` present on a sampled compound-hub page and on `/testing/`.
- Leading indicator: Google Search Console → Enhancements (or equivalent structured-data report) begins listing `Dataset`/`ItemList` items for `/testing/*` beyond the 9 current leaf URLs.

---

---

*Source: SXO agent (`_agents/sxo.md`)*

### [SXO-05] Product-page trust proof is sequenced after compliance and description content, reversing the SERP-winning trust-first pattern
- Priority: Medium
- Category: Search Experience — Content Sequencing
- Evidence class: 5 (Crawler observation/inference)
- Evidence: Text extraction of `pages/product_glp3-r10.html` main content, in rendered order: Add-to-cart block → dilution-notice legal modal trigger → Description (mechanism summary) → "Research context" bullets + citations → Intended Use/RUO/FDA legal paragraph → **Batch Documentation (Purity 99.63%, Batch RT2026205JP, Freedom Diagnostics, tested July 30, 2026)** → cross-sell. The purity/batch proof block — the exact content type every winning SERP title leads with — is the 6th content block on the page, not the 1st or 2nd.
- Affected URLs: All 15 `/product/*` URLs (sampled on `glp3-r10`; template is shared per the main audit's on-page table).
- Reasoning: Every winning competitor title in the SERP puts the trust claim (purity %, "third-party tested," COA) in the very first thing a searcher sees — the title tag itself. On Pep Select's own product page, the equivalent proof point exists and is accurate, but a visitor has to scroll past two legal blocks and a mechanism paragraph to reach it. This directly affects the skeptical-evidence-seeker and first-time-trust-comparer personas (58/100 and 49/100).
- Recommendation: Description only — reposition or duplicate a condensed trust snippet (purity %, lab name, test date, batch ID) directly beneath the price/Add-to-cart block, while leaving the full Batch Documentation section intact further down the page for detail-seekers.
- Dependencies: Independent of other findings; low-effort, template-level change.
- Failure check: If the snippet is moved and add-to-cart rate / time-on-page for first-visit sessions doesn't change, sequencing was not the binding factor — the content itself (or the persona's actual need) should be re-examined.
- Success check: Re-crawl shows the trust snippet appearing above the fold or immediately below the price; on-page scroll-depth-to-purchase-action (if analytics available) shortens.
- Leading indicator: Manual re-check of DOM order on one product page after any template change.

---

*Source: SXO agent (`_agents/sxo.md`)*

### [SXO-06] `/testing/` is a page type with no SERP peer, but its title/H1 and zero backlink profile don't yet capture that advantage
- Priority: Medium
- Category: Page-Type Mismatch — Underleveraged Asset
- Evidence class: 4 (DataForSEO estimate) + 5 (Crawler observation)
- Evidence: 0 of the 9 organic competitors in the SERP pull expose an equivalent public, per-batch COA archive as a discrete indexable hub. On Pep Select's own site, `/testing/` carries the title "Peptide COA Archive: Search by Compound & Batch" (generic, no trust-modifier keyword) and H1 "Every batch has a permanent address." (brand voice, no keyword — matches the main audit's O-01 pattern). Batch-detail pages (e.g., `/testing/ghk-cu-50-mg/psgkcu5071926gx/`) confirmed to carry valid `Dataset` schema (`"@type":"Dataset"` verified in raw HTML). The main audit's G-05 separately confirms the domain has zero measurable Common Crawl authority.
- Affected URLs: `https://pepselect.com/testing/` and all batch sub-pages.
- Reasoning: This is the one page type in the entire site that directly answers Google's own "Purity Standards" Things-to-know card (HPLC>99%, COA, MS) with real, structured, third-party data — and does so in a format no visible competitor offers. That is a genuine content-market-fit advantage, but it is invisible to ranking systems without (a) keyword-bearing title/H1 framing and (b) any external citation, since the domain has zero Common Crawl presence.
- Recommendation: Description only — evaluate a title/H1 pass that keeps the existing brand voice but adds a trust-modifier term (mirroring the O-01 recommendation already logged for the homepage), and treat the archive as the primary link-earning asset referenced in the main audit's G-05 (outreach to research/quality-assurance communities, since the content is genuinely citable and free).
- Dependencies: Shares its keyword-framing fix with O-01 (main audit) and its authority fix with G-05 (main audit) — this finding exists to flag the SXO-specific angle (unique page type, currently underleveraged) rather than duplicate either.
- Failure check: If title/H1 changes ship and impressions for purity/COA/testing-modifier queries don't move over 6–8 weeks, the constraint is confirmed to be authority (G-05), not framing — stop optimizing this page's on-page signals and prioritize off-site link acquisition.
- Success check: GSC impressions appear for "COA," "batch testing," "purity report" style queries; any inbound link or citation to `/testing/*` appears in Search Console → Links.
- Leading indicator: GSC → Links → Top linking pages, checked monthly, filtered for `/testing/` targets.

---

*Source: SXO agent (`_agents/sxo.md`)*

### [SXO-07] No page type or CTA path exists for the bulk/wholesale buyer persona
- Priority: Medium
- Category: Page-Type Mismatch — Persona Coverage Gap
- Evidence class: 4 (DataForSEO estimate) + 5 (Crawler observation)
- Evidence: One of the 9 organic competitors (genscript.com, "Catalog Peptide") is explicitly a B2B/scientific-supplier catalog page type — a distinct page type Google is rewarding in this SERP alongside the consumer-storefront type. Checked against the full 43-URL sitemap crawl (`onpage-table.txt`): no `/wholesale/`, `/bulk/`, `/b2b/`, or equivalent page exists anywhere on pepselect.com; the only volume-related mechanic found sitewide is the consumer-facing "Buy 4 get 1 free" promotion visible on the product page (`glp3-r10` extraction: "Buy 4 get 1 free").
- Affected URLs: Sitewide (absence, not a specific broken URL).
- Reasoning: The bulk/wholesale buyer persona scores lowest of all seven derived personas (25/100) purely because there is nothing on the site addressing their need — no bulk-pricing tier, no B2B contact form, no lab/institutional account path. This is a coverage gap rather than an execution defect on an existing page.
- Recommendation: Description only — if bulk/institutional demand exists, a dedicated page or contact path describing available quantity tiers and lead-time expectations (no pricing claims beyond what the business already offers) would close the type gap; if it does not exist commercially, no action is warranted and this finding should be marked resolved-by-non-applicability.
- Dependencies: Business decision required before implementation (does Pep Select serve institutional/bulk buyers at all) — flagged as needing Paulo's confirmation before any content is drafted.
- Failure check: If a bulk-facing page is built and receives no institutional inquiries within a reasonable window, the demand assumption (not the execution) was wrong — deprioritize rather than iterate on page copy.
- Success check: A bulk/wholesale contact path exists and receives at least one qualifying inquiry attributable to organic search.
- Leading indicator: Contact-form submissions tagged as bulk/institutional, if such tagging exists; GSC impressions for "wholesale peptides"/"bulk research peptides"-style queries.

---

*Source: E-commerce agent (`_agents/ecommerce.md`)*

### [ECOM-06] Product Offer schema is missing GTIN/MPN, `priceValidUntil`, and `OfferShippingDetails`
- Priority: Medium
- Category: Product schema completeness
- Evidence class: 5-Crawler observation
- Evidence: Full Product JSON-LD parsed for all 15 products contains only `name`, `url`, `description` (14/15 — see ECOM-03/07), `image`, `sku`, `brand` (self-brand "Pep Select"), and one `Offer` with `price`, `priceCurrency`, `availability`, `seller`, and `hasMerchantReturnPolicy` (via `@id` reference to the Organization-level policy). No `gtin`/`gtin8/12/13/14`/`mpn`, no `priceValidUntil`, and no `shippingDetails` (`OfferShippingDetails`) node were found on any of the 15 products.
- Affected URLs: All 15 `/product/*/` pages
- Reasoning: GTIN/MPN are commonly not applicable for a private-label/compounded research product with no third-party manufacturer identifier — that gap is likely unavoidable and should be verified with the owner rather than assumed fixable. `priceValidUntil` and `shippingDetails`, however, are both Google-recommended Offer fields with no such constraint, and their absence is a straightforward completeness gap versus Google's Merchant/Product structured-data guidance, independent of whether this vertical is Shopping-ads-restricted (see ECOM-08).
- Recommendation: Describe adding `priceValidUntil` (a rolling date, e.g. reflecting the site's actual price-review cadence) and an `OfferShippingDetails` block (shipping cost, handling time, transit time — using the values already published on `/refund-shipping-policy/`) to the existing Offer node in the Product schema template. Flag GTIN/MPN to the owner as `[VERIFY CLAIM]` — confirm whether any compound has a manufacturer-assigned identifier before adding one.
- Dependencies: Shipping figures should be sourced from `/refund-shipping-policy/` to avoid an inconsistency between the schema and the on-page policy. Independent of ECOM-01/02/04/05.
- Failure check: `priceValidUntil` and `shippingDetails` remain absent from Product JSON-LD on a future crawl.
- Success check: Both fields validate cleanly in a structured-data test against a sampled product URL, with shipping values matching `/refund-shipping-policy/`.
- Leading indicator: Presence of `"shippingDetails"` and `"priceValidUntil"` keys in the Product JSON-LD block on any single product page fetch.

---

*Source: E-commerce agent (`_agents/ecommerce.md`)*

### [ECOM-07] Product descriptions follow a rigid one-paragraph template that reads as thin at scale
- Priority: Medium
- Category: Content quality (product-page description depth)
- Evidence class: 5-Crawler observation
- Evidence: Every checked description (excluding the ECOM-03 outlier) follows the identical structure: "[NAME] [DOSE]MG. [Compound] is a [class] studied for its role in [X]. It is researched for [Y]." Example set: BPC-157 ("...studied for its role in angiogenesis and tissue integrity. It is researched for its activity within growth-factor signaling pathways..."), SS-31 ("...studied for its ability to bind cardiolipin... researched for its role in supporting mitochondrial energy production..."), NAD+ ("...studied for its central role in cellular energy metabolism. It is researched as a substrate in redox reactions..."). Each is roughly 35–45 words / two sentences.
- Affected URLs: All 15 `/product/*/` pages
- Reasoning: This overlaps with, but is distinct from, the prior full-site audit's C-02 finding (all product pages under the 400-word quality gate) — that finding is about total page word count; this one is specifically about the schema/meta `description` and lead-in copy following one rigid sentence template across the whole catalog, which is a pattern a content-quality classifier (or a competitor doing a side-by-side comparison) can detect regardless of how factually distinct the specifics are.
- Recommendation: Describe expanding each product's lead description with compound-specific detail not present elsewhere on the page — e.g. typical reconstitution/storage notes distinct per compound, a one-line summary of what the current COA batch shows (for the 9 compounds where ECOM-02 doesn't apply), or a short "what researchers use this for" context sentence — varying sentence structure across products rather than only substituting nouns into the same two-sentence frame.
- Dependencies: Complements the previously-tracked C-02 finding; content must go through `.agents/product-marketing.md` compliance review before publication (no new medical/performance claims).
- Failure check: All 15 descriptions still match the identical two-sentence template on a future crawl.
- Success check: A sample of product descriptions shows varied sentence structure and compound-specific detail beyond mechanism-of-action boilerplate, while remaining compliant with RUO framing.
- Leading indicator: Word count and template-match rate across a spot-check of 3–4 product descriptions.

---

*Source: E-commerce agent (`_agents/ecommerce.md`)*

### [ECOM-08] `/shop/` category page lacks ItemList/OfferCatalog schema as the catalog scales
- Priority: Medium
- Category: Category/shop architecture
- Evidence class: 5-Crawler observation
- Evidence: `/shop/` JSON-LD `@graph` contains exactly `WebPage`, `ImageObject`, `BreadcrumbList`, `WebSite`, and `OnlineStore` nodes — no `ItemList`, `OfferCatalog`, or per-product summary node. HTML scan of `/shop/` found no `product-category` links (0 matches) and no pagination markers (`nav-links`/`page-numbers` both absent), confirming a single flat archive of all 15 products with no subcategory taxonomy or faceted navigation.
- Affected URLs: `https://pepselect.com/shop/`
- Reasoning: At 15 SKUs a flat, unpaginated archive is reasonable and not itself a defect — faceted navigation would be premature. The schema gap is the more durable issue: an `ItemList`/`OfferCatalog` node on the category page gives search engines and AI answer engines (relevant given the competitor SERP's AI Overview citations) a machine-readable manifest of what the store carries, which the current markup does not provide.
- Recommendation: Describe adding an `ItemList` (or `OfferCatalog`) node to `/shop/`'s existing JSON-LD `@graph`, referencing each in-stock product's `@id`. Revisit subcategory/faceted navigation only if/when SKU count grows meaningfully beyond ~15–20.
- Dependencies: None; independent of other findings.
- Failure check: `/shop/` JSON-LD still contains no `ItemList`/`OfferCatalog` node on a future crawl.
- Success check: `/shop/` JSON-LD includes a valid `ItemList` referencing the current in-stock catalog.
- Leading indicator: `"ItemList"` or `"OfferCatalog"` appearing in the `@graph` types list on a single fetch of `/shop/`.

---

# LOW / INFO FINDINGS

---

*Source: Google APIs (GSC / PageSpeed / CrUX) agent (`_agents/google.md`)*

### [GOOG-08] Missing baseline security headers (CSP, HSTS, COOP, X-Frame-Options) flagged sitewide
- Priority: Low
- Category: Best Practices / Security
- Evidence class: 2-PageSpeed lab
- Evidence: Repeated across all pages/strategies tested — "No CSP found in enforcement mode" (High severity, csp-xss audit), "No frame control policy found" (clickjacking-mitigation audit), "No HSTS header found" (has-hsts audit), "No COOP header found" (origin-isolation audit).
- Affected URLs: https://pepselect.com/, https://pepselect.com/shop/, https://pepselect.com/product/bpc157-10/ (all strategies) — sitewide header configuration
- Reasoning: These are best-practices/security audits, not direct ranking or CWV factors, but their absence is a defense-in-depth gap and depresses the Lighthouse "Best Practices" score (currently 96/100, capped by these findings).
- Recommendation: Add security headers (Content-Security-Policy, Strict-Transport-Security, Cross-Origin-Opener-Policy, X-Frame-Options) at the server/CDN level; describe only — implementation is outside this read-only audit's scope.
- Dependencies: None; independent, low-effort infrastructure change.
- Failure check: Headers still absent on re-check via response headers or repeat PSI best-practices audit.
- Success check: PSI best-practices audits for csp-xss, clickjacking-mitigation, has-hsts, and origin-isolation all pass.
- Leading indicator: PSI "Best Practices" category score moving from 96 toward 100.

---

*Source: Google APIs (GSC / PageSpeed / CrUX) agent (`_agents/google.md`)*

### [GOOG-10] Repeated non-descriptive "Learn more" link text across product card templates
- Priority: Low
- Category: On-page SEO / Accessibility
- Evidence class: 2-PageSpeed lab
- Evidence: link-text audit failed on all pages tested. Homepage: 4 links flagged, all `href` to /product/... pages with text "Learn more". Shop: 7 links flagged, same pattern. Product page: 2 links flagged, same pattern.
- Affected URLs: https://pepselect.com/, https://pepselect.com/shop/, https://pepselect.com/product/bpc157-10/ — templated product-card component sitewide
- Reasoning: Generic anchor text ("Learn more") repeated across many links on the same page provides no contextual signal to search engines about the destination page's topic, and is a known accessibility issue (screen reader users hear identical link text out of context).
- Recommendation: Update the product-card template's CTA text to include the product name (e.g., "Learn more about BPC-157") or use `aria-label` for accessible-name enhancement without changing visible design.
- Dependencies: None; independent, template-level content change.
- Failure check: Repeat PSI link-text audit still fails with the same count of generic links.
- Success check: link-text audit passes (score 1) on homepage, shop, and product templates.
- Leading indicator: PSI SEO category score (currently 92/100 across all pages tested) moving toward 100 as this and label-content-name-mismatch issues are resolved.

---

*Source: DataForSEO agent (`_agents/dataforseo.md`)*

### [DFS-10] Domain link history begins 2026-07-03 — pepselect.com is ~6 weeks old to the link graph
- Priority: Low
- Category: Domain Maturity / Expectations
- Evidence class: [4-DataForSEO estimate]
- Evidence: backlinks_summary `first_seen: 2026-07-03 06:09:42 +00:00`; 6 backlinks total; Labs databases (rank overview, ranked keywords) return empty — consistent with a domain too new to have accrued any dataset presence.
- Affected URLs: https://pepselect.com/
- Reasoning: All zero-visibility findings above must be read against domain age: this is a cold start, not a penalty signal (no evidence of a penalty is available in this data; the 67 spam score in DFS-02 is the only negative marker). Expectation-setting matters: even KD-0 terms typically need weeks-to-months post-indexation for a brand-new domain.
- Recommendation: Set stakeholder expectations to a 3–6 month horizon for first rankings; treat month-over-month referring-domain and indexation growth as the KPIs, not positions, for the first quarter.
- Dependencies: Frames success criteria for DFS-01 through DFS-09.
- Failure check: N/A (contextual finding); invalidated if evidence emerges the domain is older with a prior penalty.
- Success check: First non-zero ranked_keywords result within 2 database refresh cycles after indexation.
- Leading indicator: GSC impressions trend (any non-zero value is progress).

---

---

*Source: Technical SEO agent (`_agents/technical.md`)*

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

---

*Source: Technical SEO agent (`_agents/technical.md`)*

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

---

*Source: Technical SEO agent (`_agents/technical.md`)*

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

---

*Source: Content & E-E-A-T agent (`_agents/content.md`)*

### [CONT-10] "Notify me" out-of-stock modal text is duplicated per cross-sell item, inflating within-page repetition
- Priority: Low
- Category: Content Structure
- Evidence class: 5-Crawler observation/inference
- Evidence: `content_quality.py` repetition_score (bigram repetition, higher = more repetition) on stripped main-content text: `product_bpc157` 26/100, `product_nad` 22/100, `testing_hub` 22/100, `coa_reta30_batch` 21/100, versus `home` 5/100 and `privacy_policy` 11/100. Inspecting `product_nad.main.txt` shows the cause: the "Keep exploring" cross-sell block repeats the full "Notify when available" modal copy — `"Leave your email and we will let you know the moment {X} is available again. Email when stock available I Agree to the terms and privacy policy You're all set. Once {X} comes back in stock, we will notify you at Continue shopping"` — once per out-of-stock cross-sell item shown (3 times on this page, for GLP-1 S, MOTS-C, and BPC-157).
- Affected URLs: All product pages that display out-of-stock items in their "Keep exploring" cross-sell block (observed on `product_bpc157`, `product_nad`; likely most products given 47% of the catalog is reported out of stock per the prior audit's O-04 finding).
- Reasoning: This is a template/UI-repetition effect, not editorial filler — the underlying `filler_score` and `ai_pattern_score` both remain 0 on every page tested, so this is not evidence of low-effort or AI-pattern content. It is flagged as Low priority purely as a content-structure observation: a large fraction of each product page's rendered text is the same 3–4 boilerplate stock-notification blocks repeated with only the product name swapped, which somewhat dilutes the unique-content ratio beyond what CONT-04's word count alone shows.
- Recommendation: Description only — if template refactoring is ever undertaken for other reasons, consider rendering the notify-modal copy once (client-side, per interaction) rather than repeating the full paragraph in the initial DOM for every cross-sell item; not a standalone priority.
- Dependencies: Related to O-04 in the prior technical/on-page audit (high out-of-stock rate driving this pattern).
- Failure check: N/A — informational.
- Success check: N/A — informational; would only matter if catalog in-stock rate improves and this pattern naturally recedes.
- Leading indicator: In-stock percentage of the catalog (currently ~53% per prior audit); as this rises, this repetition pattern shrinks on its own.

---

*Source: Content & E-E-A-T agent (`_agents/content.md`)*

### [CONT-11] Homepage body copy never uses the word "peptide" despite it being the site's core topical term
- Priority: Low
- Category: Keyword Optimization / Topical Relevance
- Evidence class: 5-Crawler observation/inference
- Evidence: Word-frequency count on `home.main.txt` (410 words, main-content only): `"peptide"` — 0 occurrences; `"research peptide"` — 0 occurrences; `"certificate of analysis"` — 0 occurrences; `"research compound"` — 1 occurrence (0.24%); `"compound"` — 7 occurrences (1.71%); `"coa"` — 1 occurrence. The homepage `<title>` tag (captured separately) begins `"Research Peptides with Ba..."` — the head term the title targets does not appear even once in the body copy retrieved for this audit.
- Affected URLs: `https://pepselect.com/`.
- Reasoning: This is not keyword stuffing in the other direction — it is a complete absence of the primary topical term in body copy, which is unusual and worth noting rather than a stuffing risk. It may be a deliberate compliance choice (using "compound" consistently instead of "peptide" to stay strictly within RUO/non-clinical framing), which would be a reasonable rationale, but as measured it means the homepage's crawlable body text does not reinforce its own title's core term at all, which is a weaker topical-relevance signal for both classic on-page SEO and for LLM topical modeling of the page.
- Recommendation: Description only — if the "compound" terminology is a deliberate compliance choice, no change is needed; if not, consider whether 1–2 natural uses of "research peptide"/"peptide" in body copy (matching the title's term) would strengthen topical reinforcement without changing the compliance framing, since "compound" is already used as the umbrella term for products.
- Dependencies: Related to prior audit's O-01 (homepage H1 carries no keyword signal); this finding extends that observation to full body copy, not just the H1.
- Failure check: N/A — this is advisory; there is no "failure" state, only a judgment call for the content owner.
- Success check: If changed, a fresh word-frequency count shows at least one natural (non-stuffed) use of the title's core term in body copy.
- Leading indicator: None without re-crawl.

---

*Source: Content & E-E-A-T agent (`_agents/content.md`)*

### [CONT-12] Freshness timestamps on 8 static/legal pages cluster in an 81-second window — a mechanical resave, not an editorial signal
- Priority: Low
- Category: Content Freshness
- Evidence class: 5-Crawler observation/inference
- Evidence: `page-sitemap.xml` `<lastmod>` values: `/contact/` 2026-08-14T16:45:20Z, `/faq/` 16:45:31Z, `/military-discount/` 16:45:44Z, `/privacy-policy/` 16:45:56Z, `/refund-shipping-policy/` 16:46:11Z, `/ruo-disclaimer/` 16:46:19Z, `/terms-conditions/` 16:46:31Z, `/track-your-order/` 16:46:41Z — 8 distinct pages, all "modified" within an 81-second span. By contrast, the COA batch pages in `ps_coa_test-sitemap.xml` show genuinely distinct, spread-out timestamps tied to real lab-test events: `ghk-cu .../psgkcu5071926gx/` 2026-07-28T20:53:20Z, `retatrutide-30mg/nd_r30_060326/` 2026-07-29T03:27:17Z, `tesamorelin.../pstes1071926gx/` 2026-07-30T17:10:24Z, `nad-500-mg/nd50026205jp/` 2026-08-04T04:22:21Z, `retatrutide-20mg/psrt2062926jp/` 2026-08-05T05:15:56Z — a genuine multi-day cadence.
- Affected URLs: All 8 static/legal pages listed above.
- Reasoning: An 81-second window across 8 unrelated pages (contact form, FAQ, a discount page, and four separate legal documents) is consistent with a bulk template resave, theme update, or migration event touching every static page's `post_modified` field in WordPress, not with 8 independent editorial reviews happening within the same 81 seconds. This does not mean the *content itself* is stale — it means the `lastmod` freshness signal on these specific pages should not be read as "this legal text was reviewed on this date" by anyone relying on the sitemap for that purpose. This is offered as a caution about signal reliability, not a defect requiring a fix.
- Recommendation: Description only — no action required; noted so a future audit (or Google, which also discounts mechanically-bulk-touched lastmod dates) does not mistake this cluster for meaningful content freshness. If the business wants `lastmod` to carry real signal for legal pages, only resave a page's `lastmod` when its substantive text actually changes.
- Dependencies: None.
- Failure check: N/A — informational.
- Success check: N/A — informational.
- Leading indicator: Whether future legal-content edits produce isolated, non-clustered `lastmod` changes (a sign the pattern was a one-off migration, not an ongoing bulk-touch habit).

---

*Source: Content & E-E-A-T agent (`_agents/content.md`)*

### [CONT-13] Brand name is inconsistent between marketing copy ("Pep Select") and the legal/mailing entity name ("PepSelect")
- Priority: Low
- Category: Entity Consistency / GEO
- Evidence class: 5-Crawler observation/inference
- Evidence: The spaced form "Pep Select" is used throughout marketing and footer copy (e.g., `"© 2026 Pep Select. All rights reserved."`, `"Pep Select provides research-grade compounds..."`, `"Pep Select compounds are independently tested..."` — all confirmed in the CONT-03 identical-line set). The unspaced form appears specifically in the two legal-document mailing-address sentences: `"Mailing address: PepSelect, 2090 Baker Rd, Ste 304, Kennesaw, GA 30144."` (in both `privacy-policy` and `terms-conditions`).
- Affected URLs: `https://pepselect.com/privacy-policy/`, `https://pepselect.com/terms-conditions/` (unspaced form); site-wide footer/marketing copy (spaced form).
- Reasoning: This is a minor but real entity-disambiguation inconsistency: search engines and AI systems building an entity graph for the business benefit from a single, consistently-used name string tied to Organization schema, addresses, and legal identity. Two different string forms for what is presumably the same legal entity is a small but avoidable source of ambiguity, compounding the prior audit's S-03 finding (Organization entity has no identity graph).
- Recommendation: Description only — confirm which form ("Pep Select" or "PepSelect") is the actual registered legal entity name and use that exact form consistently in the two legal-document mailing-address sentences and in any `Organization` schema `legalName` field, while the spaced "Pep Select" can remain the marketing/brand `name`.
- Dependencies: Related to prior audit's S-03 (Organization identity graph); should be fixed together.
- Failure check: N/A — informational/low-effort.
- Success check: A fresh check of the two legal-document mailing-address sentences and any schema `legalName`/`name` fields shows a single, deliberate, documented distinction between brand name and legal name (or full consistency, if they are meant to be identical).
- Leading indicator: None without re-crawl.

---

*Source: Content & E-E-A-T agent (`_agents/content.md`)*

### [CONT-14] Readability is stratified between plain-English pages and dense technical pages, with no bridging content
- Priority: Low
- Category: Readability
- Evidence class: 5-Crawler observation/inference
- Evidence: Flesch Reading Ease scores computed on stripped main-content text (standard Flesch formula, syllable-counted heuristically): `home` 66.8 (avg. 12.0 words/sentence) — "Standard/Fairly Easy"; `faq` 69.3 (10.7 words/sentence) — "Fairly Easy"; `product_nad` 50.7 (16.2 words/sentence) — "Fairly Difficult"; `testing_hub` 41.8 (11.4 words/sentence) — "Difficult"; `coa_reta30_batch` 31.1 (12.1 words/sentence) — "Difficult"; `privacy_policy` 35.2 (21.7 words/sentence) — "Difficult" (driven by long legal sentences, expected for the page type).
- Affected URLs: Site-wide pattern; specifically contrasts `home`/`faq` against `product_nad`/`testing_hub`/`coa_reta30_batch`.
- Reasoning: The lower scores on product/testing/COA pages are driven by chemical nomenclature, CAS numbers, and batch codes (e.g., "ND50026205JP"), which is expected and arguably appropriate for the stated audience of "qualified research professionals" rather than a defect to fix by dumbing down the science. It is flagged because there is currently no bridging/explanatory content (a glossary, a "how to read a COA" explainer, a plain-language mechanism summary) connecting the easy-to-read marketing pages to the technically dense product/data pages — a visitor who is not already fluent in the terminology has no on-site on-ramp between the two registers.
- Recommendation: Description only — this pairs naturally with CONT-05 (no informational content); a short glossary or "how to read this COA" explainer page would raise both AI-citation readiness (clear, quotable definitions) and accessibility of the existing technical pages without altering their scientific precision.
- Dependencies: Related to CONT-05.
- Failure check: N/A — informational.
- Success check: N/A — informational; would be superseded if CONT-05 is addressed with content that includes a glossary/explainer component.
- Leading indicator: None without re-crawl.

---

---

*Source: Schema / Structured Data agent (`_agents/schema.md`)*

### [SCHEMA-07] Homepage BreadcrumbList has only one ListItem (no real trail)
- Priority: Low
- Category: BreadcrumbList
- Evidence class: [5-Crawler observation/inference]
- Evidence: Homepage `BreadcrumbList`: `"itemListElement": [{"@type": "ListItem", "position": 1, "name": "Home"}]` — single entry, no `item` URL.
- Affected URLs: `https://pepselect.com/`
- Reasoning: This is standard Yoast behavior for a site's front page (there is nothing to show a trail to) and is not a spec violation, but a single-node BreadcrumbList carries no rich-result value on its own. Informational only.
- Recommendation: No action required. Documented for completeness only.
- Dependencies: None.
- Failure check: N/A.
- Success check: N/A.
- Leading indicator: N/A.

---

*Source: Schema / Structured Data agent (`_agents/schema.md`)*

### [SCHEMA-08] No FAQPage schema found anywhere sampled, including /faq/
- Priority: Low
- Category: FAQPage (per hard rule: Google retired FAQ rich results for all sites May 7 2026)
- Evidence class: [5-Crawler observation/inference]
- Evidence: `https://pepselect.com/faq/` emits only `BreadcrumbList`, `WebPage`, `WebSite`, `OnlineStore` — no `FAQPage`, `Question`, or `Answer` types detected.
- Affected URLs: `https://pepselect.com/faq/`
- Reasoning: Per current hard rules, FAQ rich results no longer exist for any site as of May 7 2026, so the absence of FAQPage here is not a missed SERP opportunity. Per hard rule, this audit does not recommend adding new FAQPage markup for SERP benefit. If Pep Select is separately curious about AI/GEO citation coverage of the FAQ content, that benefit is unconfirmed and would be an optional decision, not an SEO requirement — and since `/faq/` is static, company-authored FAQ content (not genuine visitor-submitted Q&A), `QAPage` would not be an appropriate substitute either.
- Recommendation: No action required under current Google guidance. If Pep Select later wants to explore unconfirmed AI/GEO markup coverage for this content, that should be a separate, explicitly-scoped decision — not implemented as part of this SEO audit.
- Dependencies: None.
- Failure check: N/A.
- Success check: N/A.
- Leading indicator: N/A — no SERP feature exists to monitor for FAQPage currently.

---

*Source: Schema / Structured Data agent (`_agents/schema.md`)*

### [SCHEMA-10] @context inconsistency between Yoast and WooCommerce JSON-LD emitters
- Priority: Low
- Category: Code quality / spec convention
- Evidence class: [5-Crawler observation/inference]
- Evidence: Yoast blocks use `"@context": "https://schema.org"`; WooCommerce Product blocks use `"@context": "https://schema.org/"` (trailing slash) on the same page.
- Affected URLs: `/product/bpc157-10/`, `/product/ghk-cu/`, `/product/ss-31/`
- Reasoning: Both forms resolve identically for parsers, so this causes no functional error, but it is a documented best-practice deviation (this audit's own rule set: "prefer `https://schema.org` as @context, not a trailing-slash or http variant").
- Recommendation: Normalize the WooCommerce block's `@context` to `https://schema.org` for consistency. This may require a WooCommerce version/setting check since it is core plugin output, not child-theme code (do not edit plugin core files directly). Description only.
- Dependencies: Same remediation batch as SCHEMA-01.
- Failure check: Rich Results Test / validator continues to show mismatched `@context` strings across blocks on the same URL.
- Success check: Both blocks on a given page use identical `@context` string.
- Leading indicator: N/A — cosmetic/consistency fix, no measurable SERP signal.

---

---

*Source: Sitemap agent (`_agents/sitemap.md`)*

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

---

*Source: Performance (lab) agent (`_agents/performance.md`)*

### [PERF-09] Unsized images create latent CLS risk despite CLS currently passing
- Priority: Low
- Category: CLS
- Evidence class: 2-PageSpeed lab
- Evidence: `unsized-images` audit (Home) flags two images without explicit `width`/`height`: the header/age-gate logo (`Logo_Pepselect_Whitebackground-1.png`, `loading="eager"`) and the side-cart's empty-cart illustration (`empty-cart.png`, rendered at 200×200 inside `.xoo-wsc-empty-cart`). Actual CLS scores measured this run are already good: Home 0.084, Shop 0.018, Product 0, Testing 0 — all well inside the ≤0.1 "Good" band.
- Affected URLs: `/` (confirmed via audit); logo is shared site-wide per PERF-04 so the same missing-dimensions condition likely applies wherever it renders without explicit attributes, though this was only directly observed on Home in this pass.
- Reasoning: CLS is not currently a measured problem, but `unsized-images` is a leading indicator, not a lagging one — these two images are exceptions to an otherwise-good pattern (`why-pep-select` images are correctly using `width`/`height`, per their audit snippets), and unsized images only become a visible CLS event when timing conditions change (e.g., slower network causing the image to arrive after adjacent text has already laid out, or the side-cart being opened before/after data loads). Flagging now, before it becomes a scored failure, is lower-cost than fixing it after a regression.
- Recommendation: Add explicit `width`/`height` (or `aspect-ratio` CSS) attributes to the logo `<img>` and the side-cart empty-cart illustration.
- Dependencies: None. Low effort, can be bundled with PERF-04's logo re-export.
- Failure check: `unsized-images` audit still flags either image after the fix.
- Success check: `unsized-images` audit returns 0 items on Home; CLS scores remain ≤0.1 across all four templates in subsequent runs.
- Leading indicator: `unsized-images` `total_items` count.

---

*Source: Performance (lab) agent (`_agents/performance.md`)*

### [PERF-10] Unused CSS/JS bytes add measurable but secondary savings opportunity
- Priority: Low
- Category: JS/CSS payload
- Evidence class: 2-PageSpeed lab
- Evidence: `unused-javascript` flags the GTM script (75,516–76,075 B wasted per template, ~41% of its transfer size) and `confetti.js` from the side-cart plugin's confetti-effect library (34,036 B total, 31,819 B / 93% wasted) on all four templates. `opportunities` (type=`opportunity` audits) report `unused-css-rules` savings of 300 ms (Product) and 150 ms (Shop), and `unused-javascript` savings of 450 ms (Shop) and 300 ms (Testing). `unminified-javascript` flags `xoo-wsc-main.js` with 2,311 B of minification savings (Home).
- Affected URLs: `/`, `/product/bpc157-10/`, `/shop/`, `/testing/`
- Reasoning: `confetti.js` at 93% unused bytes strongly suggests the side-cart's celebratory confetti animation library is loaded on every page load regardless of whether the triggering event (e.g., successful add-to-cart) ever fires — a straightforward lazy-load candidate. This is smaller in magnitude than PERF-02/03/05 (hundreds of ms, not seconds) so it is sequenced as a secondary cleanup item.
- Recommendation: Lazy-load `confetti.js` so it only downloads when the add-to-cart success state actually triggers it, and run the standard unused-CSS-rule review (defer non-critical CSS) already recommended for the render-blocking chain (PERF-02) — these are the same underlying resource list.
- Dependencies: Overlaps with PERF-02 and PERF-05 (same plugin script/style list); sequence together.
- Failure check: `confetti.js` still ships with >80% unused bytes on initial page load after the fix.
- Success check: `confetti.js` no longer appears in `unused-javascript` on initial-load audits (i.e., it loads only on trigger).
- Leading indicator: `unused-javascript` `wastedBytes` value for `confetti.js`.

---

*Source: Performance (lab) agent (`_agents/performance.md`)*

### [PERF-11] TTFB, edge caching, and compression are strong and are not contributing to the LCP/render-blocking problems above
- Priority: Low
- Category: Server response / caching / compression (positive finding — maintain, do not regress)
- Evidence class: 2-PageSpeed lab / 5-Crawler observation
- Evidence: Lighthouse `server-response-time` audit (throttled mobile lab): Home 3 ms, Product 2 ms, Shop 5 ms, Testing 4 ms — all far under the 200 ms TTFB "Good" ceiling referenced in the CWV brief. Direct unthrottled `curl` cross-check confirms this independently: Home TTFB 0.054 s / total 0.060 s, Product 0.091 s / 0.098 s, Shop 0.039 s / 0.046 s, Testing 0.051 s / 0.060 s, all HTTP 200. Response headers on all four HTML pages: `CF-Cache-Status: HIT`, `Server: cloudflare`, `Ki-CF-Cache-Status: HIT` (Kinsta edge cache), `Cache-Control: public, max-age=0, s-maxage=86400`, `Content-Encoding: br` (Brotli). Static asset headers (e.g., `woocommerce.css`) show `Cache-Control: max-age=315360000` (10-year cache) with `Content-Encoding: br`; the homepage hero PNG shows `Cache-Control: max-age=315360000`, `Expires: Thu, 31 Dec 2037` (image formats are not additionally compressed, which is expected/correct for already-compressed PNG/WebP).
- Affected URLs: `/`, `/product/bpc157-10/`, `/shop/`, `/testing/`, and static assets generally
- Reasoning: This contradicts a plausible assumption that a WooCommerce/Elementor/Kinsta stack would have slow TTFB — it does not. The Cloudflare + Kinsta two-layer edge cache is HIT on every page tested, HTML is Brotli-compressed, and static assets carry effectively-infinite cache lifetimes. This means none of PERF-01 through PERF-10 are caused by server response time, cache misses, or missing compression — the entire LCP/render-blocking problem is front-end payload and request-chain structure, not infrastructure. This significantly narrows and de-risks the remediation scope.
- Recommendation: No action required. Preserve current Cloudflare/Kinsta cache and Brotli configuration; re-verify headers after any CDN/hosting configuration change.
- Dependencies: None. Documents a baseline other findings depend on (confirms PERF-01's cause is front-end, not server-side).
- Failure check: A future check shows `CF-Cache-Status` or `Ki-CF-Cache-Status` as `MISS`/`BYPASS` on HTML documents, `Content-Encoding` missing on text assets, or TTFB exceeding 200 ms.
- Success check: Headers continue to show `HIT` + `Content-Encoding: br` + long `max-age` on static assets in future spot checks.
- Leading indicator: `Server-response-time` Lighthouse audit value, or a manual `curl -sD - -o /dev/null <url>` header check.

---

---

*Source: Visual / Mobile agent (`_agents/visual.md`)*

### [VIS-03] "Not a researcher? Exit" sends visitors off-site to google.com with no informational alternative
- Priority: Low
- Category: UX / conversion, tied to interstitial
- Evidence class: [5-Crawler observation/inference]
- Evidence: Gate control script defines `var EXIT = "https://google.com";` and an "Exit" link/button is rendered next to the "Not a researcher?" prompt in the gate card (visible in `homepage_laptop.png` and `homepage_mobile.png`).
- Affected URLs: Sitewide (same shared gate component).
- Reasoning: Any visitor who is unsure, under-informed, or simply misclicks the "Exit" affordance is removed from the site entirely with no compliant landing page, FAQ, or explanation of why they were redirected — a hard, unrecoverable exit rather than a soft decline state. This forecloses any chance of that visitor returning to complete the gate later in the same session and removes any opportunity to capture why they declined.
- Recommendation: Describe (not implement) routing "Exit" to an on-site informational/compliance page explaining the research-use-only policy, rather than an off-domain redirect, or at minimum to a neutral non-competitor destination.
- Dependencies: Independent of VIS-01/VIS-02; can be addressed separately.
- Failure check: Clicking "Exit" continues to navigate to `google.com` (or any off-domain destination) with no explanation shown first.
- Success check: "Exit" leads to an on-site page or a confirmation step before leaving.
- Leading indicator: Exit-click volume relative to total gate impressions, tracked in analytics.

---

*Source: Visual / Mobile agent (`_agents/visual.md`)*

### [VIS-04] Legal/disclaimer copy inside the gate renders below common mobile-readability guidance (~16px)
- Priority: Low
- Category: Mobile usability / readability
- Evidence class: [5-Crawler observation/inference]
- Evidence: Gate CSS sets `.psag-legal p{font-size:10.5px;...}`, `.psag-address,.psag-copy{font-size:10.5px;...}`, `.psag-remember{font-size:13px;...}`, `.psag-attest ol li{font-size:13.5px;...}`, and checkbox label text `.psag-check span{font-size:14px;...}`. All are below the ~16px base-font guidance commonly used to avoid mobile pinch-zoom, though this is stylistically typical for fine-print/legal disclaimers.
- Affected URLs: Sitewide (same shared gate component); most visible in `homepage_mobile.png` where the bottom disclaimer paragraph and copyright line are noticeably small relative to the heading/button.
- Reasoning: The disclaimer text carries the actual compliance/legal weight of the gate ("By proceeding, you confirm... not for human consumption, diagnosis, treatment, or prevention of any disease..."), so it is the one section of the modal where under-sized type is most consequential — a visitor could accept the gate's legal terms without comfortably reading them on a phone.
- Recommendation: Describe (not implement) increasing the legal/disclaimer paragraph and copyright line to at least 13–14px on mobile, and the primary checkbox/attestation copy to 16px where layout allows, without changing the overall card proportions.
- Dependencies: Independent, low-effort styling change; can ship alongside VIS-01/VIS-02 or separately.
- Failure check: Disclaimer/copyright text remains at ~10.5px on mobile captures.
- Success check: Updated screenshot shows disclaimer text at a legible size without requiring pinch-zoom.
- Leading indicator: None specific to analytics; verify via re-screenshot only.

---

*Source: GEO / AI Search agent (`_agents/geo.md`)*

### [GEO-01] `llms.txt` is missing (404)
- Priority: Low
- Category: llms.txt / Discovery
- Evidence class: 5-Crawler observation
- Evidence: `GET https://pepselect.com/llms.txt` → **HTTP 404**, served as the site's standard WordPress 404 template (`<title>Page not found - Pep Select</title>`, `x-robots-tag: noindex, follow`), not a custom 404 or a valid llms.txt payload.
- Affected URLs: `https://pepselect.com/llms.txt`
- Reasoning: `llms.txt` is an optional, unstandardized convention with no confirmed adoption by Google (whose AI Overviews are the dominant citation surface for this query set per the DataForSEO evidence) and inconsistent, unconfirmed usage by ChatGPT/Perplexity crawlers. Its absence is not a meaningful ranking or citation constraint on its own, but it is a near-zero-cost artifact that could give a hand-curated summary of the COA archive and product catalog to any crawler or agent that does check for it.
- Recommendation: Publish a plain-text `/llms.txt` describing Pep Select's structure (catalog, COA Quality Archive, FAQ) and linking to the highest-value canonical URLs. Treat as backlog, not urgent.
- Dependencies: None. Independent of every other finding.
- Failure check: `/llms.txt` still returns 404 six months from now with no change in AI-citation behavior — confirms this file was never the constraint.
- Success check: `GET /llms.txt` returns 200 with a well-formed markdown/plain-text summary.
- Leading indicator: Presence/absence of `/llms.txt` in a repeat crawl; no analytics signal exists to watch this in isolation.

---

---

*Source: GEO / AI Search agent (`_agents/geo.md`)*

### [GEO-08] No visible author, reviewer, or "last updated" byline on YMYL-adjacent content
- Priority: Low
- Category: Authority & Brand Signals
- Evidence class: 5-Crawler observation
- Evidence: `publication_date` returned by htmldate-based extraction for the homepage (2026-08-14), `/testing/` (2026-07-31), and `/faq/` (2026-08-14) match `sitemap.xml`/`Last-Modified` metadata, not a visible on-page date. No `<address>`, byline, "Reviewed by," or "Last updated" text was found in the rendered content of the homepage, `/faq/`, `/testing/`, or the product page examined. A prior full-site SEO audit independently confirmed no About page and no author/credential surface anywhere on the site (its C-04 finding).
- Affected URLs: Domain-wide, most relevant on `/faq/`, `/testing/*`, and any future informational content (GEO-06).
- Reasoning: In a research-compound (YMYL-adjacent) category, AI answer engines and Google's own quality systems weight visible evidence of who is asserting a claim and when it was last checked, separate from HTTP-header freshness. The FAQ and COA archive content is already unusually concrete (named labs, batch IDs, exact percentages) — the missing piece is a visible attribution layer that lets a reader or a crawler see who stands behind those numbers and how current the underlying testing methodology guidance is, independent of any single batch's test date.
- Recommendation: Add a visible "Last reviewed [date]" line to `/faq/` and any new informational pages (GEO-06), and consider a minimal "About / Our Testing Standards" page naming the operational team or QA process (without overstating credentials that don't exist).
- Dependencies: Sequenced with GEO-06 — new informational pages should ship with this from day one rather than being retrofitted.
- Failure check: Adding a byline with no real named accountability behind it (e.g., a generic "Pep Select Team" with no further detail) would not move authority signals — the bar is genuine verifiability, not the presence of a name.
- Success check: `/faq/` and new informational pages display a visible, dated attribution line in the rendered HTML.
- Leading indicator: None measurable in isolation; track alongside GEO-05/GEO-06 leading indicators.

---

---

*Source: GEO / AI Search agent (`_agents/geo.md`)*

### [GEO-09] robots.txt makes no distinction between AI search-retrieval crawlers and AI training-only crawlers
- Priority: Low
- Category: AI Crawler Access
- Evidence class: 5-Crawler observation
- Evidence: `GET https://pepselect.com/robots.txt` → HTTP 200, verbatim:
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
  # ---------------------------
  User-agent: *
  Disallow:

  Sitemap: https://pepselect.com/sitemap_index.xml
  # ---------------------------
  # END YOAST BLOCK
  ```
  There is **no user-agent-specific rule for any of the nine crawlers in scope** (GPTBot, OAI-SearchBot, ClaudeBot, Claude-Web, PerplexityBot, Google-Extended, CCBot, Bytespider, Applebot-Extended). Per RFC 9309, same-user-agent groups merge; both `User-agent: *` blocks apply together, so every one of these nine crawlers is currently allowed everywhere except `/wp-admin/`, WooCommerce log/upload paths, and cart-tracking query strings.
- Affected URLs: Domain-wide (robots.txt governs the entire site).
- Reasoning: For the four crawlers this audit was asked to prioritize for AI search visibility (GPTBot, OAI-SearchBot, ClaudeBot, PerplexityBot), this blanket allow is the correct outcome and needs no change — it is recorded here as a positive baseline, not a defect (see Verified Correct). The finding is that the same blanket rule also allows the training-only crawlers the brief lists as optionally blockable (CCBot, anthropic-ai — not currently present as its own user-agent line at all — and, by extension, Bytespider and Google-Extended for training use). This is a business decision, not a technical gap: the current configuration cannot express "allow retrieval for citation, disallow training" because it never differentiates crawlers at all.
- Recommendation: If Pep Select wants to permit AI citation/search visibility while opting out of model-training use of its content, add explicit `User-agent: CCBot` / `User-agent: Google-Extended` / `User-agent: Bytespider` / `User-agent: anthropic-ai` blocks with `Disallow: /`, leaving the wildcard block (which covers GPTBot, OAI-SearchBot, ClaudeBot, PerplexityBot, Applebot-Extended, and Claude-Web) untouched. If no such preference exists, no action is needed — the current configuration is not blocking anything Pep Select is trying to achieve.
- Dependencies: None; purely a policy decision independent of every content/authority finding above.
- Failure check: Adding training-crawler blocks and then observing no change in citation behavior from GPTBot/ClaudeBot/PerplexityBot confirms the wildcard allow for search-retrieval crawlers was correctly unaffected.
- Success check: A repeat `robots.txt` fetch shows explicit disallow rules for the training-only crawlers the business chooses to opt out of, with GPTBot/OAI-SearchBot/ClaudeBot/PerplexityBot/Applebot-Extended still fully allowed.
- Leading indicator: `robots.txt` diff on next crawl; no traffic/citation metric moves on this alone.

---

---

*Source: SXO agent (`_agents/sxo.md`)*

### [SXO-08] The research gate offers only a binary in/out choice, with no low-friction path for an undecided top-of-funnel visitor
- Priority: Low
- Category: Search Experience — Journey Friction
- Evidence class: 5 (Crawler observation/inference)
- Evidence: Gate screenshot (`_agents/screenshots/homepage_laptop.png`) shows exactly two paths: complete the researcher-type dropdown + two checkboxes and "ENTER SITE," or click "Not a researcher? Exit." There is a "Read the researcher attestation" disclosure toggle for detail, but no path for a visitor who is browsing to learn whether they qualify (e.g., a first-time visitor unsure if their affiliation counts as "qualified researcher") short of committing to the attestation itself.
- Affected URLs: Sitewide (shared gate template).
- Reasoning: This is a narrower, lower-severity variant of SXO-01: distinct because it's about the *choice architecture* of the gate (binary commit-or-leave) rather than its content sequencing. A curious, early-awareness-stage searcher (e.g., someone who clicked from the "do research peptides actually work" PAA style query) may not yet know if they qualify and has no way to preview qualifying criteria without engaging the compliance form.
- Recommendation: Description only, and compliance-sensitive: consider whether the existing "Read the researcher attestation" disclosure could be made more discoverable/prominent as a genuine "not sure? read this first" path, without altering the underlying attestation requirement.
- Dependencies: Same compliance-sensitivity caveat as SXO-01; do not implement without explicit approval given the legal nature of the gate.
- Failure check: If the disclosure is made more prominent and exit rate at the gate is unchanged, choice architecture was not the friction point — the qualification bar itself (age/researcher status) is the binding constraint, which is a business decision, not a UX one.
- Success check: Gate completion rate (Enter Site vs. Exit) improves without any change to who is permitted to complete it.
- Leading indicator: If analytics on the gate exist, Enter Site vs. Exit click ratio, tracked before/after.

---

---

*Source: E-commerce agent (`_agents/ecommerce.md`)*

### [ECOM-09] Restricted-vertical Merchant Center/free-listing eligibility is an open policy question — owner verification required, no action taken
- Priority: Low
- Category: Marketplace visibility / policy (informational only)
- Evidence class: 4-DataForSEO estimate (reused, no new call) + 5-Crawler observation
- Evidence: `docs/claude-seo-latest/DATAFORSEO-serp-research-peptides-2026-08-18.md` (existing report, not re-queried): "Two of nine organic listings arrive via Shopping-feed auto-tagging" — `redrockpeptides.com` ("Price '$59–$410'; `srsltid` = Merchant Center feed") and `nationwidepeptides.com` ("`srsltid` Merchant Center tagging") both surface `srsltid` parameters in organic Google results for "research peptides," alongside a third competitor (`rpeptide.com`) showing a price annotation ("$120–$200"). Separately, this crawl confirmed Pep Select's own `Offer.hasMerchantReturnPolicy` references an Organization-level `MerchantReturnPolicy` with `returnPolicyCategory: "https://schema.org/MerchantReturnNotPermitted"` — schema-valid, but a "no returns permitted" policy is a variable Merchant Center weighs in program eligibility.
- Affected URLs: All 15 `/product/*/` pages (schema); competitor evidence is domain-level (`redrockpeptides.com`, `nationwidepeptides.com`, `rpeptide.com`), not a Pep Select URL
- Reasoning: This vertical (research peptides) is understood to be restricted for Google Shopping *ads*, per the task brief — but the observed `srsltid` tags on competitor organic results suggest some peptide-adjacent listings do appear via Merchant Center free-listing surfaces regardless, which raises a genuine, unresolved policy question about whether Pep Select's own compounds would be eligible for a free listing and, if so, whether "no returns permitted" would affect approval. This is a policy determination Google makes per-merchant and per-category, not something inferable from a SERP snapshot of three competitors.
- Recommendation: This finding is descriptive only. It does not recommend touching Merchant Center, submitting a feed, or changing the return policy — the task scope explicitly excludes that. Surface this open question to Paulo/the site owner for manual verification against current Google Merchant Center category policies before any Merchant Center action is considered. If/when that verification happens, note that Product schema completeness (ECOM-06) is a prerequisite regardless of the policy answer, since Merchant Center feeds are commonly cross-checked against on-page structured data.
- Dependencies: Depends entirely on manual policy research by the site owner; blocks nothing else in this report. Loosely supports ECOM-06 (schema completeness is useful either way).
- Failure check: N/A — this is a flagged question, not an implementable fix; "failure" would be acting on it without owner sign-off.
- Success check: Owner confirms, via Google's own Merchant Center policy documentation or direct account-level testing, whether this vertical/category is eligible for free listings.
- Leading indicator: Any future appearance of `srsltid`-tagged Pep Select results in organic Google searches (would indicate a Merchant Center feed is already live) — none observed in the current evidence.

---

---

# VERIFIED CORRECT — WHAT IS ALREADY HEALTHY

---

*Source: Google APIs (GSC / PageSpeed / CrUX) agent (`_agents/google.md`)*

## Verified Correct

- GSC property access confirmed via `gsc_query.py sites` — `sc-domain:pepselect.com` returned with `siteOwner` permission; all subsequent GSC calls used this exact property string.
- All GSC totals used in this report have `totals_complete: true` and `totals_source: "dimensionless_aggregate"`; no row-level sums were substituted for site-wide totals, per instructions.
- Homepage indexation status (PASS / Submitted and indexed) is internally consistent with it being the only URL of the five sampled with meaningful search impressions and the only one with a recorded last_crawl_time and canonical.
- CrUX "no data" results were treated as explicit eligibility statements ("insufficient eligibility — Google has no field data"), not as tooling errors, consistent with the near-zero GSC traffic baseline (self-consistent across both evidence classes).
- No sitemap submission, no Indexing API publish/delete, and no GSC property configuration changes were made at any point in this session — read-only throughout.
- PSI desktop run for the product page returned a transient 500 error on first attempt; the retry succeeded and its data is what is reported (no data was fabricated for the initial failure).

---

*Source: DataForSEO agent (`_agents/dataforseo.md`)*

## Verified Correct
- All six authorized API calls returned status_code 20000 (Ok). No call was repeated; no unauthorized endpoint was used.
- Empty results for domain_rank_overview and ranked_keywords are genuine "no data" responses, not errors.
- SERP data timestamped 2026-08-18 (live). Backlink counts as of the 2026-08-18 request.

---

*Source: Technical SEO agent (`_agents/technical.md`)*

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

---

*Source: Content & E-E-A-T agent (`_agents/content.md`)*

## Verified Correct

Recorded so a future audit does not need to re-verify these from scratch:

- **Filler and AI-pattern scores are 0/100 on every page tested.** `content_quality.py` run against stripped text of 8 representative pages (`home`, `faq`, `product_nad`, `product_bpc157`, `testing_hub`, `coa_reta30_batch`, `privacy_policy`, `terms_conditions`) returned `filler_score: 0` and `ai_pattern_score: 0` on all 8, with `overall_quality` scores of 79–92/100 and information density 0.335–1.0. This independently reproduces the prior audit's claim of a clean, non-templated, non-AI-slop writing style and rules out the QRG §4.6/§4.6.5/§4.6.6 scaled-content-abuse and filler-content triggers as active concerns for this site's prose (the concerns that do exist — CONT-02 through CONT-04 — are about placeholder text, unlinked citations, and thin word counts, not generic/AI-pattern phrasing).
- **COA batch pages carry the most substantive unique content of any page type on the site.** `/testing/retatrutide-30mg/nd_r30_060326/` measured 590 unique words after boilerplate subtraction (highest of any page type sampled, ahead of even the FAQ and legal pages on a per-page basis relative to their purpose), backed by `Dataset` schema with `variableMeasured`, `measurementTechnique`, `provider`, and a downloadable PDF — this is a genuine, verifiable Experience/Authoritativeness asset and should not be diluted when addressing CONT-03/CONT-04.
- **Internal linking within the content sampled is functional and purposeful**, not decorative: product pages link out to an expandable citation list ("View the N sources"), a full batch report PDF ("View full batch report →"), and cross-sell items; the Contact page links directly to the Quality Archive and the order-tracking page; the testing hub links to each per-compound archive page, which links to individual batch pages. No broken internal content links were found in the sampled set (separate from the CONT-01/terms-of-service issue, which is a gate/consent link, not a content link).
- **Sitemap enumeration is complete and matches the prior audit's 43/43 count**, independently re-derived in this pass via `page-sitemap.xml` (9) + `ps_compound-sitemap.xml` (9) + `ps_coa_test-sitemap.xml` (9) + `product-sitemap.xml` (16) = 43.
- **No FAQPage schema, no aggregateRating fabrication, no hreflang** — all previously verified as correct/non-issues by the prior technical audit; nothing found in this pass contradicts those conclusions, and they are not restated as content findings here.

---

*Source: Schema / Structured Data agent (`_agents/schema.md`)*

## Verified Correct

- `@context` value used by the primary (Yoast) graph is `https://schema.org` (no `http://`, no trailing slash) on every page sampled — correct per current best practice.
- All URLs used as `@id`, `url`, `item`, `contentUrl`, and `logo`/`image` values across every sampled block are absolute (`https://pepselect.com/...`) — no relative URLs found.
- All dates observed (`datePublished`, `dateModified`, `dateCreated`, sitemap `lastmod`) are in ISO 8601 format (e.g., `2026-08-14T16:45:20+00:00`, `2026-06-25`).
- No deprecated schema types found anywhere sampled: no `HowTo`, no `SpecialAnnouncement`, no `CourseInfo`/`EstimatedSalary`/`LearningVideo`.
- No `FAQPage` markup exists anywhere sampled (see SCHEMA-08) — nothing to flag as a stale deprecated-adjacent block, and no incorrect new FAQPage was found either.
- No legacy Microdata or RDFa detected: `grep` for `itemscope`, `itemtype=`, and `typeof=` returned zero matches on raw HTML for both the homepage and a product page — JSON-LD is the sole structured-data format in use, per best practice.
- `WebSite` + `SearchAction` (Sitelinks Search Box) is correctly implemented site-wide with a valid `EntryPoint` (`urlTemplate: "https://pepselect.com/?s={search_term_string}"`) and `query-input` `PropertyValueSpecification` — present and well-formed on every sampled page.
- `BreadcrumbList` is present and correctly structured (sequential `position`, `name`, and `item` URL on all but the final/current-page item, which correctly omits `item` per Google's guidance) on all non-homepage pages sampled: product pages, shop, testing index, testing compound page, testing COA leaf page, and contact page.
- `MerchantReturnPolicy` (`applicableCountry: "US"`, `returnPolicyCategory: "https://schema.org/MerchantReturnNotPermitted"`, `merchantReturnLink`) is present and consistently attached to both the Organization node and each product's Offer — a coherent, deliberate "no returns" policy consistent with a research-use-only compound business model.
- Dataset/DataDownload architecture is correctly scoped: the compound-level page (`/testing/retatrutide-30mg/`) correctly does NOT carry Dataset markup (it's a listing/`WebPage`), while the individual batch/COA leaf page correctly carries the `Dataset` + `DataDownload` + `DataCatalog` + `variableMeasured` (`PropertyValue`) structure, with `isAccessibleForFree: true`, a real `provider` (testing lab), and a real `distribution.contentUrl` pointing to an actual hosted PDF.
- Organization entity is typed `OnlineStore` (a valid schema.org subtype of `Organization`) rather than plain `Organization` — this is expected, intentional WooCommerce+Yoast SEO integration behavior (not a bug) and remains valid for Google's Organization/logo guidance, which accepts more specific subtypes.
- Product `Offer.availability` values observed (`https://schema.org/OutOfStock` for BPC-157 and SS-31, `https://schema.org/InStock` for GHK-CU) are valid schema.org Enumeration values and appear to accurately reflect real per-product stock state at crawl time.

---

*Source: Sitemap agent (`_agents/sitemap.md`)*

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

---

*Source: Performance (lab) agent (`_agents/performance.md`)*

## Verified Correct

- All four URLs were confirmed live (HTTP 200) via direct `curl` immediately before and alongside the PSI runs; `/testing/` was independently confirmed reachable even though it is absent from `sitemap_index.xml`/`page-sitemap.xml`/`product-sitemap.xml`.
- PSI lab metrics (LCP/FCP/TBT/CLS/Speed Index, resource-summary counts) were cross-checked internally between the `lab_metrics`, `failed_audits`, and `audit_details` sections of each PSI JSON response for consistency (e.g., `render-blocking-insight` savings-ms values match between the summary and detail views).
- TTFB and caching/compression claims (PERF-11) were independently verified with unthrottled `curl -sD -` header inspection outside of Lighthouse, rather than relying solely on the lab tool's throttled `server-response-time` figure.
- The render-blocking request-count ratios (PERF-02) were computed directly from each page's own `resource-summary` Script+Stylesheet counts against its own `render-blocking-insight` `total_items`, per template, not inferred from a single page.
- Only GET requests were issued throughout (PSI API calls, sitemap XML fetches, HTML/header fetches). No forms were submitted, no state-changing requests were made, and no CrUX/field-data endpoints were queried per the read-only/lab-only scope of this task.

---

*Source: Visual / Mobile agent (`_agents/visual.md`)*

## Verified Correct
- `<meta name="viewport" content="width=device-width, initial-scale=1">` is present and correctly configured on the homepage, enabling proper mobile scaling.
- No horizontal overflow or element clipping was observed in any of the eight captured screenshots (laptop 1366px and mobile 375px) — the gate card itself is fully responsive and centers correctly at both widths.
- No separate/stacked cookie-consent banner (checked for CookieYes, Complianz, CookieLaw, generic GDPR banner markers) was found layered on top of the research gate — visitors face one modal, not two.
- The gate's decorative floating-bubble animation correctly respects `@media (prefers-reduced-motion:reduce)` (animation disabled for users who request reduced motion), and the animation uses `transform`/`opacity` only (no layout-triggering properties), so it should not contribute to Cumulative Layout Shift.
- Real page content (H1, navigation, Elementor sections) is present in the initial server-rendered HTML response behind the gate for all four sampled URLs (confirmed via `GET` fetch), meaning the gate does not appear to strip content from the DOM outright — the primary risk identified here is visual/UX blocking (VIS-01/VIS-05), not content removal.
- Gate touch targets: the "Enter Site" button (`padding:16px 0`, full width) and each checkbox row (`.psag-check`, full-width flex label with `padding:15px 2px`) both compute to well over 48px tap-target height based on CSS inspection, despite the visual checkbox icon itself being small (19×19px) — the clickable label area is what matters and it is generously sized.

---

*Source: GEO / AI Search agent (`_agents/geo.md`)*

## Verified Correct

Recorded so a future GEO audit does not re-raise these:

| Item | Status | Evidence |
|---|---|---|
| GPTBot, OAI-SearchBot, ClaudeBot, PerplexityBot access | **Fully allowed** | No crawler-specific block in `robots.txt`; only `/wp-admin/`, WooCommerce log paths, and `?add-to-cart=` query strings are disallowed sitewide |
| Server-side rendering for AI crawlers | **Correct** | Homepage, `/testing/`, `/faq/`, and the sampled product and COA batch page all returned `mode_used: "raw"` and `is_spa: false` from `render_page.py` — full content is present in the initial HTML response with no JavaScript execution required, confirmed additionally with a forced `--mode always` Playwright render on the COA batch page showing an identical schema graph |
| `meta robots` on indexable pages | **Correct** | `index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1` — explicitly permits unlimited snippet length, which does not constrain passage-length extraction by any AI summarizer that respects it |
| COA batch-page `Dataset`/`DataDownload` schema | **Genuine strength** | Verified live on `/testing/ghk-cu-50-mg/psgkcu5071926gx/`: 7 `variableMeasured` `PropertyValue` entries, named `measurementTechnique`, dated, with a `DataDownload` PDF — this is the kind of structured, source-attributed, self-contained statistic block the citability signals in scope reward, and it is rare in this vertical per the DataForSEO evidence (competitor "Things to know" citations are narrative vendor-blog claims, not raw datasets) |
| Batch-fail transparency | **Genuine strength** | `/testing/retatrutide-20mg/` publicly shows batch PSRT2062926JP marked "Did not pass release review" / "was not released for sale" with full test detail — a verifiable trust signal largely absent from the competitor content the DataForSEO SERP surfaces |
| FAQ/Q&A content quality | **Correct — content, not markup** | Both `/faq/` (15 Q&A pairs) and the homepage (5 Q&A pairs) use question-phrased `<h3>` headings with direct, self-contained `<p>` answers, server-rendered in raw HTML (not JS-gated); only the machine-readable markup layer is missing (GEO-02) |
| No `FAQPage` rich-result markup | **Correct for the SERP-feature use case** | Google retired FAQ rich results for all sites on 2026-05-07; adding `FAQPage` markup purely for that purpose would provide no SERP benefit. GEO-02 recommends the same markup for a different reason (LLM ingestion), which does not contradict this |
| XML sitemaps / crawl discovery | **Correct** | `sitemap_index.xml` live, references `page-sitemap.xml`, `ps_compound-sitemap.xml`, `ps_coa_test-sitemap.xml`, `product-sitemap.xml`; all fetched sitemaps returned 200 with fresh `lastmod` dates (2026-07-28 to 2026-08-18) |
| `noindex` on transactional utility pages | **Correct** | Consistent with standard WooCommerce practice; not a GEO concern |

---

---

*Source: SXO agent (`_agents/sxo.md`)*

## Verified Correct

These require no SXO action — recorded so they are not re-flagged on a future pass:

- **Product-page trust content is accurate and substantive where it exists** — the Batch Documentation block (purity %, batch ID, lab name, test date) on `/product/glp3-r10/` is real, specific, and matches the linked `/testing/` batch record. The gap identified (SXO-05) is sequencing, not accuracy or existence.
- **`Dataset` schema is genuinely present** on COA batch-detail pages (verified directly in raw HTML on `/testing/ghk-cu-50-mg/psgkcu5071926gx/`), corroborating the main audit's claim about this asset.
- **Transparent failed-batch disclosure exists**: `/testing/retatrutide-20mg/` shows a batch (PSRT2062926JP) marked "Did not pass release review... not released for sale" — publishing a failed QC result is a genuine, rare trust signal in this vertical and should not be removed or softened.
- **The research gate's indexability exemption is correctly a non-issue** (per the main audit's G-02) — Googlebot sees the underlying page content regardless of the gate. SXO-01/SXO-08 are about the *human* first-impression experience, not crawlability, and should not be conflated with G-02 when prioritizing work.
- **`/shop/` and product pages share one consistent template** — no divergent or broken variants were found across the 15 product URLs sampled via the on-page table.

---

---

*Source: E-commerce agent (`_agents/ecommerce.md`)*

## Verified Correct

- All 15 products emit valid `Product` JSON-LD with `name`, `sku`, `brand`, and a `price`/`priceCurrency`/`availability` `Offer` — the schema foundation is sound; gaps are additive (ECOM-06), not structural.
- `Offer.hasMerchantReturnPolicy` is present via `@id` reference to a site-wide `MerchantReturnPolicy` node — this satisfies Google's requirement that a return policy be declared at either Offer or Organization level (Organization level, confirmed here, is valid).
- All 6 spot-checked product pages return `<meta name="robots" content="index, follow...">` — Out-of-Stock products are not being accidentally deindexed or noindexed, which is correct practice.
- Every product and the `/shop/` page carries a `BreadcrumbList` schema node and visible on-page breadcrumb markup.
- Every product page includes a "related products" and cross-sell/upsell block (WooCommerce native), providing product↔product internal linking.
- Products with a mapped COA batch (GHK-CU, TB-500, NAD+, and by pattern the other 6 covered compounds) show a "Current Batch" block with an inline purity percentage and a direct link to the specific `/testing/[compound]/[batch]/` page — a genuinely strong, working trust-signal pattern where it exists.
- Yoast-generated titles/meta descriptions are dynamically unique per product (e.g. "BPC-157 10MG for Research | Pep Select" / "Review BPC-157 10MG from Pep Select, including current price, availability, product details...") — no templated duplication was found at the meta-tag level, only at the body-description level (ECOM-07).
- The 7 Out-of-Stock products still surface a "back in stock" email opt-in (`cwginstock` plugin) rather than a dead end.
- `/shop/` correctly lists all 15 products on a single unpaginated archive with no orphaned catalog pages observed in this pass.

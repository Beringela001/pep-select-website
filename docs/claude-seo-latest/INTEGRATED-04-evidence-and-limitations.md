# INTEGRATED-04 — Evidence & Limitations
> Part of the Pep Select integrated SEO audit — 2026-08-18 — target https://pepselect.com
> Files: INTEGRATED-00 context & scorecard · INTEGRATED-01 critical & high · INTEGRATED-02 medium, low & verified-correct · INTEGRATED-03 strategy & action plan · INTEGRATED-04 evidence & limitations
>
> Evidence classes used throughout: **[1]** Google Search Console (verified) · **[2]** PageSpeed Insights (laboratory) · **[3]** CrUX (real-user field) · **[4]** DataForSEO (third-party estimate) · **[5]** Crawler observation / inference.
> All findings below are reproduced **verbatim** from the specialist agent reports in `_agents/`; only the source-agent attribution line above each block was added.

# ORCHESTRATOR EVIDENCE INVENTORY

## Connected sources actually used

| Source | Class | Status | Used for |
|---|---|---|---|
| Google Search Console API | [1] | Authenticated, property verified | Search analytics (28d + 6mo), sitemaps list (read), URL Inspection (read) on 5 URLs |
| PageSpeed Insights v5 | [2] | Authenticated | Mobile + desktop runs on homepage, product, shop, /testing/ |
| CrUX API + CrUX History API | [3] | Authenticated — **returned no data**: insufficient eligibility for every origin/page query, both form factors. This is a definitive "Google has no real-user field data for this origin", not an error. | Eligibility determination only |
| Google Indexing API | — | Authenticated, **deliberately unused** (write-capable; session was read-only) | Nothing |
| GA4 Data API | — | Not configured (no property ID) | Nothing — noted as GOOG-11 |
| DataForSEO MCP | [4] | Live, cost-gated | 6 calls, $0.094 (see below) |
| Bundled crawler tooling + Playwright | [5] | Local | Full-page fetches, HTML parsing, content metrics, screenshots (desktop 1366 / mobile 375) |
| Moz / Bing Webmaster APIs | — | No keys (Tier 0) | Nothing — DataForSEO covered backlinks |

## DataForSEO spend (estimated before execution, logged after)

| Call | Est. | Actual | Result quality |
|---|---|---|---|
| `dataforseo_labs_google_domain_rank_overview` (pepselect.com) | $0.010 | $0.010 | Returned empty — domain has no rank data |
| `dataforseo_labs_google_ranked_keywords` (limit 100) | $0.050 | $0.050 | Returned empty — 0 ranked keywords |
| `backlinks_summary` | $0.020 | $0.020 | Full summary returned |
| `serp_organic_live_advanced` "buy research peptides" (depth 100) | $0.002 | $0.002 | 9 organic slots (feature-dense single page) |
| `serp_organic_live_advanced` "bpc-157 for sale" (depth 100) | $0.002 | $0.002 | 9 organic slots |
| `dataforseo_labs_bulk_keyword_difficulty` (10 keywords) | $0.010 | $0.010 | Full KD set |
| **Audit total** | **$0.094** | **$0.094** | Under the $0.10 authorization; user's ceiling respected without interruption |

Additionally reused (already paid earlier today, before this audit): `serp_organic_live_advanced` "research peptides" ($0.002) — the saved report `DATAFORSEO-serp-research-peptides-2026-08-18.md` supplied the AI Overview citation evidence. Logged day total: $0.096.

**Budget-excluded endpoints and their impact:**
- `ai_opt_llm_ment_top_domains` / LLM-mention suite ($0.05+) — direct multi-LLM mention tracking not run; AI-visibility evidence rests on the AI Overview citations captured in the three SERP pulls. Impact: AI-visibility conclusions (GEO-05, GEO-06, DFS-06) are inferences from Google AIO behavior, not cross-platform measurements.
- `dataforseo_labs_google_competitors_domain` ($0.05) — formal competitor-overlap matrix not run; competitor set derived from recurrence across the three SERPs (DFS-05). Impact: competitor list is SERP-observed, not traffic-modeled.
- `ai_optimization_chat_gpt_scraper` — ChatGPT citation check not run for the same budget reason.

## Read-only guarantee

No modification of any kind was made to the website, WordPress, DNS, Search Console (nothing submitted, nothing deleted), sitemaps, indexing (Indexing API untouched), Merchant Center, or any external account. All site access was GET-only. The visual agent did not bypass or dismiss the research gate (documented in its limitations); raw fetch artifacts live in `tmp/` and the session scratchpad; screenshots in `_agents/screenshots/`.

## Quality-gate compliance

Per claude-seo 2.2.4 gates: no HowTo schema recommended anywhere; FAQPage handled per the May 7 2026 retirement of FAQ rich results (flagged informationally, no removal, no new FAQPage for SERP benefit, QAPage only for genuine Q&A — see GEO-02/SCHEMA-08); all Core Web Vitals references use INP, never FID; no location-page quality gates triggered (not a local business).

## Relationship to the earlier same-day inline audit (66/100)

The earlier inline audit ran on crawler evidence only (its own header records Google APIs, Moz/Bing and DataForSEO all unavailable). This integrated audit re-verified the site fresh and added classes [1]–[4]; the score moved 66 → 57 **because of new evidence, not site regression** — verified non-indexation of money pages [1], measured Poor mobile LCP [2], confirmed CrUX ineligibility [3], and confirmed zero footprint/authority [4]. Two earlier crawler-inferred concerns were materially sharpened rather than contradicted (performance; authority), and one earlier blind spot became this audit's headline visual finding (the research gate as first paint).

## Known residual limitations of this audit

1. CrUX [3] contributes no data by definition (ineligible) — no real-user confirmation of any performance statement is possible until traffic exists.
2. GSC query/page tables are near-empty because impressions are near-zero; trend analysis will only become meaningful once visibility exists.
3. DataForSEO Labs metrics (KD, volumes) are model estimates; SERP pulls are single-point-in-time snapshots (US / en / desktop).
4. The visual pass could not verify post-gate layouts in a fresh session without interacting with the gate (kept read-only); post-gate template screenshots therefore do not exist in this audit.
5. Backlink evidence is single-source (DataForSEO); no Moz DA/Bing cross-check was available.
6. GA4 organic/conversion data absent (no property configured).
7. Playwright/PSI environments measure from a US-region cloud/local context; no geographic performance matrix was run.

<!--END-ORCHESTRATOR-SECTION-->

---

# PER-AGENT DATA SOURCES & LIMITATIONS (verbatim)

---

*Source: Google APIs (GSC / PageSpeed / CrUX) agent (`_agents/google.md`)*

## Data Sources & Limitations

- **GA4: skipped entirely** — no `ga4_property_id` configured in the Google API credentials (Tier 1, not Tier 2). No organic traffic, landing-page, or conversion data from GA4 is included anywhere in this report.
- **CrUX eligibility:** all origin-level and page-level CrUX/CrUX History queries (mobile + desktop, homepage + shop + one product page) returned explicit "insufficient Chrome traffic volume for eligibility" — there is no real-user field data for this domain at any granularity tested. All Core Web Vitals conclusions in this report rely on PSI Lighthouse **lab data only** (evidence class 2), which does not represent real-user conditions or network/device diversity.
- **GSC data freshness:** Search Console data typically lags 2-3 days; the 28-day window used ends 2026-08-15 (3 days before this audit's run date of 2026-08-18).
- **GSC historical data quality caveat:** the tool surfaced a warning that a Google-side impressions/CTR/position logging error affected data from 2025-05-13 through 2026-04-27 (clicks unaffected). This does not change the conclusion of near-zero visibility (clicks are 0 throughout), but historical impression/position figures before 2026-04-27 should be treated with caution if referenced elsewhere.
- **Row-level GSC query/page/device/country data can under-represent totals** due to Google's anonymization of low-volume dimensions; only the `totals` object (marked `totals_complete: true`) was used for site-wide figures.
- **Sample size caveat:** with only 5 total impressions across 6 months, the by-device and by-country breakdowns (section 1d/1e) are statistically meaningless and are reported for completeness only, not as actionable trend data.
- **Indexation sampling caveat:** only 2 of 15 product pages were inspected via URL Inspection (bpc157-10, ghk-cu); both showed "Discovered - currently not indexed." This pattern is reported as observed on the sampled pages, not confirmed sitewide across all products.
- Indexing API and Search Console property-management endpoints were never invoked for write operations, consistent with the STRICTLY READ-ONLY constraint given for this audit.

---

*Source: DataForSEO agent (`_agents/dataforseo.md`)*

## Data Sources & Limitations
- All findings are **evidence class 4 — DataForSEO third-party estimates**; Labs data reflects DataForSEO's US/en database refresh cycle, and live SERPs reflect one crawl (SERP volatility applies).
- Both depth-100 SERP requests returned only 9 organic results each — Google served single feature-dense pages; deeper results were not available in the response, so "absent from top 100" formally means "absent from all results Google returned."
- Keyword difficulty does not model YMYL/compliance filtering, Merchant Center eligibility, or AI Overview dynamics; KD 0 values on newer compounds (retatrutide) may partly reflect sparse data.
- **Budget-excluded endpoints (NOT called):** backlinks_anchors (no anchor-text detail), backlinks_competitors / dataforseo_labs_google_competitors_domain (no shared-keyword competitor metrics), dataforseo_labs_bulk_traffic_estimation (no competitor traffic estimates), AI-mentions/LLM endpoints (no direct LLM-visibility measurement — AI-visibility findings are inferred from AI Overview citations only), search-volume endpoints (no volume figures for the 10 target terms).
- Pre-paid evidence incorporated: docs/claude-seo-latest/DATAFORSEO-serp-research-peptides-2026-08-18.md ("research peptides" SERP, $0.002, already logged).

---

*Source: Technical SEO agent (`_agents/technical.md`)*

## Data Sources & Limitations

- All findings are based on direct, read-only GET requests (curl and the bundled `claude-seo` scripts: `sitemap_discovery.py`, `render_page.py`, `pagespeed_check.py`) performed on 2026-08-18. No authenticated Search Console/GSC data was used for this report (evidence class 5 for crawler-based findings).
- TECH-06 draws on live PageSpeed Insights API lab results (evidence class 2), which is real Lighthouse lab data, not a class-5 inference — flagged accordingly rather than mislabeled.
- **No CrUX field data available**: The `pagespeed_check.py` CrUX lookup returned `"error": "No CrUX data for this origin. The site likely has insufficient Chrome traffic volume for eligibility."` This means the "Poor" LCP in TECH-06 is a lab-only signal (single-load, simulated network/CPU throttling); real-world field LCP experienced by actual visitors is currently unmeasurable via CrUX and could differ. This should be revisited once the origin accumulates enough 28-day Chrome traffic for CrUX eligibility.
- PageSpeed Insights desktop strategy failed both times with a transient `PSI API error 500` from Google's API; only mobile lab data was obtained. Desktop CWV status is therefore unverified in this pass.
- Only a sample of pages was manually header/HTML-checked (homepage, /shop/, two product pages, /testing/ and one testing sub-page, /contact/, /privacy-policy/, /faq/, cart/account/search/checkout utility pages, plus one case-variant and one nonexistent URL). Findings described as "site-wide" (TECH-01 through TECH-04) are inferred from the fact that security headers are applied at the shared Cloudflare/Kinsta edge layer common to every template tested, not from an exhaustive crawl of all 43 sitemap URLs.
- Structured-data (JSON-LD) schema accuracy/Merchant-rule validation and detailed render-blocking-asset/JS-bundle remediation are explicitly out of scope for this report per the coordinator's instruction — see the schema and performance sub-agent reports for that depth.
- This is a point-in-time snapshot (2026-08-18); Cloudflare/Kinsta edge configuration, plugin versions, and PageSpeed lab results can change between runs (as shown by the two different PSI runs in TECH-06 producing different LCP values for the same URL).

---

*Source: Content & E-E-A-T agent (`_agents/content.md`)*

## Data Sources & Limitations

- All page content was fetched live via `claude-seo run render_page.py <url> --mode auto -o <file>` (raw HTTP fetch; no pages in this sample triggered SPA-mode rendering — `is_spa: false` on all fetches).
- `render_page.py --json`'s `extracted_text` field is truncated to ~503 characters in summary mode and could not be used directly; full HTML was captured instead and stripped with a custom regex-based extractor written for this audit, since `bs4` and `trafilatura` are not installed in this Python environment. This extractor is blunter than trafilatura — it does not remove nav/header/footer chrome the way trafilatura's boilerplate model does — so raw "total word count" figures in this report should be read as an upper bound on true main-content length. Boilerplate itself is quantified separately and more reliably via exact-line-match diffing across pages (CONT-03), which does not depend on this limitation.
- `content_quality.py` (bundled QRG scorer) was run on the same stripped text for 8 of the 23 sampled pages; it is advisory and pattern-based, not a certified AI-detector, per its own documentation.
- Readability (Flesch Reading Ease) was computed with a hand-written heuristic syllable counter (vowel-cluster counting), not a validated NLP library — treat the exact scores as indicative, not precise; the relative ordering between plain and technical pages is the reliable part of that finding.
- 10 of 16 product-sitemap URLs were deep-sampled for word count and citation-placeholder checks; 6 (`glp2-t20`, `glp3-r10`, `glp3-r20`, `glp3-r30`, `tesa-10`) were not, and CONT-02/CONT-04 note this explicitly as a coverage gap for a follow-up spot-check.
- No blog/guide content exists to sample against the 1,500-word blog-post floor; that floor is therefore inapplicable to this site as currently structured (see CONT-05).
- This report does not have Search Console, PageSpeed/CrUX, or DataForSEO access (same data-availability limits recorded in the parent audit's `00-audit-context.md`); all findings here are Evidence class 5 (crawler observation/inference).
- CONT-01 duplicates/cross-references a finding already owned by the Technical audit (T-01); it is restated here specifically for its Trust/E-E-A-T implications, not to double-count it as a separate technical defect.

---

*Source: Schema / Structured Data agent (`_agents/schema.md`)*

## Data Sources & Limitations

- All data gathered via read-only GET requests (`claude-seo run render_page.py --mode auto`); no forms submitted, no authenticated/admin views used, no writes made to the live site.
- Structured-data detection used the tool's bounded `--json-ld-output` extraction; page HTML beyond structured data was not fully reviewed except for targeted `grep` checks for microdata/RDFa markers and `<script type="application/ld+json">` counts.
- Sample scope: 9 URLs total (homepage, shop, 3 products, testing index, testing compound page, 1 testing COA leaf page, contact, plus `/faq/` checked only for FAQPage status). The site has at least 9 COA leaf pages (`ps_coa_test-sitemap.xml`) and 15 products (`product-sitemap.xml`); only 1 COA leaf page and 3 products were sampled in full detail. Findings describing "site-wide" or "pattern applies to all" are inferred from template consistency (identical WooCommerce/Yoast plugin output structure across all sampled instances of each page type), not independently verified on every single URL.
- No PageSpeed/CrUX/GSC/DataForSEO data was pulled for this schema-specific audit; all evidence is class 5 (crawler observation/inference). Rich Results Test / Search Console Enhancements reports were not queried live as part of this audit — "failure check"/"success check" items reference how those tools would be used to verify, not confirmed current output from them.
- WooCommerce product review/rating configuration (SCHEMA-06) was inferred from the absence of `aggregateRating`/`review` in JSON-LD only; the WooCommerce admin settings themselves were not inspected (no site access beyond public GET requests).
- Whether Pep Select intentionally omits NAP data (SCHEMA-05) for privacy/compliance reasons in the research-peptide business model was not confirmed; flagged as an opportunity only, not assumed to be an oversight.

---

*Source: Sitemap agent (`_agents/sitemap.md`)*

## Data Sources & Limitations
- All data gathered via direct, unauthenticated GET requests on 2026-08-18; no Google Search Console, server logs, or analytics data were available, so actual Googlebot crawl/index status (as opposed to fetchability) could not be confirmed for any URL.
- Internal-link coverage checks were limited to the homepage, `/shop/`, and `/testing/` hub pages (the most likely discovery surfaces); a full site crawl of every page's outbound links was not performed, so additional orphan or duplicate-content issues may exist beyond what these three pages reveal.
- The rapid sequential status-code check of all 43 URLs initially triggered what appears to be Cloudflare/Kinsta bot-mitigation (curl error code 000 / connection reset) when run as a single tight loop; results were obtained successfully by re-running in small batches with pauses. This is a normal bot-protection behavior on the hosting/CDN stack, not a sitemap defect, but it means any third-party crawler hitting many sitemap URLs in quick succession could face the same throttling.
- `claude-seo run sitemap_discovery.py` was used only for discovery cross-validation (confirming the declared sitemap is reachable and valid); it does not itself validate per-URL status/canonical/robots signals, which were checked manually via direct fetch as described in the Method Summary.
- Pagination crawl-trap testing (`/shop/page/N/`) was limited to N = 2–5; behavior at higher N was not exhaustively tested but the consistent 200/self-canonical pattern across 4 consecutive values indicates it is not bounded by real product count.

---

*Source: Performance (lab) agent (`_agents/performance.md`)*

## Data Sources & Limitations

- All Core Web Vitals figures in this report are **lab data** (Evidence class 2): a single Lighthouse 13.x run per URL via the PSI API, mobile strategy only, `psi-only` (no CrUX). A single run is not the 75th-percentile field measurement Google actually scores — the sibling field-data agent covering CrUX is the authority for pass/fail against real users. Treat every LCP/CLS/TBT number here as directional, not as a confirmed Google verdict.
- Desktop strategy was not run in this pass; all findings and numbers above are mobile-only. If desktop performance materially differs (e.g., due to different image `srcset` breakpoints), that would require a separate desktop PSI pass to confirm.
- INP itself cannot be measured by Lighthouse (a lab tool with no real user input) in a single run; Total Blocking Time is used throughout as the standard lab proxy and is explicitly labeled as such (PERF-05). FID is not referenced anywhere in this report, per current methodology (INP is the sole interactivity metric).
- The bundled `pagespeed_check.py` tool caps each Lighthouse audit's `items` detail list at 5 entries (with a `total_items` count for the full set); consequently `network-requests` (116/113/116/87 total items) could only be inspected for its first 5 raw entries by request order, not resorted by size — the `resource-summary` and named insight audits (`image-delivery-insight`, `render-blocking-insight`, `third-parties-insight`) were used instead as they report accurate aggregate/top-offender data independent of this cap. No definitive "the LCP element is exactly file X" confirmation audit (e.g., a `largest-contentful-paint-element` audit) was available in this Lighthouse 13.x run; PERF-03's LCP-element attribution for the homepage hero image is inferential (based on its `fetchpriority="high"` attribute), not a direct Lighthouse LCP-element audit output.
- Render-page/HTML-source inspection (`render_page.py`) was not additionally run against these four URLs in this pass, since the PSI audit details already surfaced the specific blocking/oversized resources needed for these findings; it remains available for deeper DOM/element-level follow-up if a specific finding needs confirmation beyond what PSI's `audit_details` already provided.
- All figures reflect a single point-in-time snapshot (2026-08-18); WooCommerce/Elementor sites can vary run-to-run due to plugin updates, cache warm/cold state, or A/B content changes.

---

*Source: Visual / Mobile agent (`_agents/visual.md`)*

## Data Sources & Limitations
- Screenshot capture used the bundled `claude-seo run capture_screenshot.py` tool (Playwright + Chromium, confirmed ready via `claude-seo doctor`) at `laptop` (1366×768) and `mobile` (375×812) presets, with fresh (no-cookie) browser contexts per the tool's default behavior — this matches a genuine first-time-visitor state, which is the correct state to audit for above-the-fold/interstitial purposes.
- Because every page tested returned the same full-viewport gate in a cookie-less session, **the true above-the-fold hero layout, real navigation, product imagery/rendering, and any layout shift of the actual homepage/product/shop/testing templates could not be captured or visually verified in this pass.** This is a direct consequence of the site's own gate mechanism, not a tooling failure — the tool correctly captured what a first-time visitor sees.
- This audit is strictly read-only/GET-only per instructions; the gate's "Enter Site" action triggers a client-side cookie write and (per the script's `AJAX`/`RECORD` variables) likely an `admin-ajax.php` POST to log consent, so the gate was intentionally **not** submitted/clicked through, and no cookie was injected to bypass it, to avoid any state-changing request to the live site. Consequently, screenshots of the real templates' above-the-fold content are not available from this pass; a follow-up pass with explicit authorization to pre-seed a client-side cookie (no POST required, since the check is `document.cookie`-based) could capture the real templates without ever submitting the form.
- Underlying-page facts referenced above (H1 text, presence of nav/Elementor markup, absence of a separate cookie banner, viewport meta tag) were confirmed via plain `GET` fetch of raw HTML (`urllib`), not rendered screenshots, and are noted as such.
- Product page sampled: `/product/bpc157-10/`, chosen as a representative product template discovered via the `/shop/` page's product links; other product templates were not individually screenshotted but share the same gate mechanism per the shop/testing/homepage cross-check.
- No JavaScript execution issues, console errors, or network waterfall were inspected (out of scope for this visual pass); only rendered pixel output and raw HTML/CSS were analyzed.

---

*Source: GEO / AI Search agent (`_agents/geo.md`)*

## Data Sources & Limitations

- **Live fetches performed this session (class 5, GET-only):** `robots.txt`, `llms.txt`, `sitemap_index.xml`, `ps_coa_test-sitemap.xml`, homepage (`/`), `/testing/`, `/faq/`, one product page (`/product/glp3-r10/`), one COA batch leaf page (`/testing/ghk-cu-50-mg/psgkcu5071926gx/`, fetched both raw and force-rendered via Playwright), and one COA compound-hub page (`/testing/retatrutide-20mg/`). All via `claude-seo run render_page.py`, which performs SSRF/DNS-rebinding-safe fetches; no direct `requests.get` calls were made.
- **No live User-Agent spoofing was performed.** `render_page.py` does not expose a custom User-Agent option, so this audit could not directly compare server responses to a real `GPTBot`/`ClaudeBot`/`PerplexityBot` User-Agent header against a generic fetch (a cloaking check). The `robots.txt`-based access assessment (GEO-09) and the raw-HTML-completeness observation (Verified Correct) are the best available substitutes and are both evidence class 5, but neither confirms the origin does not serve different content to a declared AI-bot User-Agent.
- **No DataForSEO calls were made by this agent**, per instruction. All market-side evidence (AI Overview citations, PAA queries, organic result set, "Things to know" cards) is drawn entirely from the single supplied artifact `docs/claude-seo-latest/DATAFORSEO-serp-research-peptides-2026-08-18.md` (evidence class 4, one Google US desktop pull, single point in time, single keyword "research peptides" — not validated against ChatGPT search, Perplexity, or Bing Copilot directly, and not re-verified for currency as of 2026-08-18 beyond the file's own stated pull date).
- **Platform-specific readiness (Google AIO, ChatGPT search, Perplexity, Bing Copilot) is assessed qualitatively, not measured per platform.** Technical accessibility (SSR, robots.txt) is uniform across all four. Google AI Overview is the only platform with direct citation evidence in scope (GEO-05, class 4); ChatGPT search, Perplexity, and Bing Copilot readiness is inferred from the same content/authority findings (GEO-03 through GEO-08) without platform-specific verification, since no `ai_optimization_chat_gpt_scraper` or `ai_opt_llm_ment_search` MCP tool call was made or authorized this session.
- **Not independently re-verified in this session (relied on the prior full-site SEO audit dated the same day):** Common Crawl absence (G-05), no About/author page (C-04), 43-page site structure and word counts (C-01/C-02). These are cited as supporting evidence in GEO-05 and GEO-08 rather than re-measured, since the coordinator's own prior artifact already establishes them with fuller methodology than this GEO-scoped pass would add.
- **Brand-mention presence (Wikipedia, Reddit, YouTube, LinkedIn) was assessed only via on-site signals** (absence of any outbound link or `sameAs` reference) and the domain-wide Common Crawl absence already on record. No external search of Wikipedia, Reddit, YouTube, or LinkedIn was performed to confirm zero third-party mentions exist independent of the site's own lack of self-links — this is a reasonable inference given the domain's newness (content dated from 2026-06-24 onward) and total absence from the Common Crawl web graph, but it is an inference, not a direct check of those platforms.

---

---

*Source: SXO agent (`_agents/sxo.md`)*

## Data Sources & Limitations

- **SERP evidence is a single live DataForSEO pull for one head term** ("research peptides," US/desktop) — evidence class 4. It does not cover compound-specific queries (e.g., "buy BPC-157," "GLP-3 R purity"), which is the actual competitive set for the 15 individual product pages. The "ALIGNED" call on the product-page type above is a scoped judgment, not a validated one — a compound-level SERP pull would be needed to confirm it.
- **No new page fetches were performed for this pass** — analysis reused same-day raw HTML captures and rendered screenshots from a sibling audit run (`docs/claude-seo-latest/00-audit-context.md` and related files), plus fresh text/schema extraction against those captures. Per the read-only/GET-only constraint, no live re-fetch of pepselect.com was made to confirm the site is unchanged since that capture.
- **No Google Search Console, GA4, or PageSpeed Insights access** — every "Leading indicator" above is stated as a metric to watch *if and when* such access exists; none of the persona/engagement claims are backed by first-party analytics in this pass.
- **Gate interaction (Enter Site / Exit click behavior) was not measurable** — no analytics on gate completion rate were available; SXO-01 and SXO-08 are reasoned from static screenshots and markup, not observed user behavior.
- **Persona scores are qualitative, analyst-assigned estimates** (0–25 per dimension) based on observed page content and SERP signals, not survey or behavioral data — they are directional, not measured.
- **This report does not re-litigate findings already fully owned by the on-page SEO audit** (O-01 homepage/shop/testing H1 keyword gap, O-04 out-of-stock anchor text, C-02 thin product content, S-04 missing ItemList, G-05 zero backlink authority) except where cited as supporting evidence for a distinct SXO-specific angle.

---

---

*Source: E-commerce agent (`_agents/ecommerce.md`)*

## Data Sources & Limitations

- All page data in this report comes from a live, read-only, GET-only crawl on 2026-08-18 using `render_page.py --mode always` (Playwright-rendered DOM) and its JSON-LD extraction, run against `https://pepselect.com`. No POST/PUT/DELETE requests were made; no cart, checkout, account, or admin surface was touched.
- No new DataForSEO Merchant API or SERP calls were made for this report, per task instruction. The single DataForSEO citation (ECOM-09) reuses the pre-existing `DATAFORSEO-serp-research-peptides-2026-08-18.md` report verbatim and is marked as such.
- Merchant Center itself was not accessed, queried, or modified — ECOM-09 is explicitly a flagged policy question for owner research, not a verified account-level fact.
- Variant/quantity-tier structured data (WooCommerce variable-product `data-product_variations`) could not be confirmed present or absent with certainty — the variation form markup (`variations_form`) is present on all 15 products, but the embedded variations JSON was not found in the static render for the products checked; this may reflect single-variation products, AJAX-loaded variation data, or a markup pattern not captured by this pass. Not reported as a finding due to insufficient evidence; flagged here for a follow-up pass if variant-level schema (multiple `Offer`s per SKU/strength) needs verification.
- Image-level product-gallery optimization (alt text, dimensions, WebP) was not re-audited here in depth beyond what the existing site-wide audit (`00-audit-context.md`, finding I-01) already covers; no new product-specific image finding is included to avoid duplicating unverified claims.
- Cash-back (YITH Points & Rewards) and Buy-4-Get-1 (YITH Dynamic Pricing) promotional pill behavior was spot-checked against project memory (`product-pills.md`) and found consistent with expected logged-out behavior (no cash-back pill; B4G1 pill present only where the plugin's discount note renders), but this is a UI/promotions concern, not an SEO finding, and is noted here only for completeness, not scored.
- This report is scoped to e-commerce/product-page SEO only; site-wide technical, content-volume, and performance findings already tracked in `docs/claude-seo-latest/00-audit-context.md` and `01-critical-and-high-findings.md` are referenced but not re-litigated here.

---

# PER-AGENT CATEGORY SCORE STATEMENTS (verbatim)


*Google APIs (GSC / PageSpeed / CrUX) (`_agents/google.md`)*

## Category Score: 28/100

Score reflects Critical-priority indexation failures (shop/product pages not indexed, /testing/ hub unknown to Google) directly causing near-zero organic visibility (0 clicks, 5 impressions in 6 months) — the two Critical findings (GOOG-01, GOOG-03) dominate the score; Core Web Vitals lab data is comparatively healthy on desktop (93-95/100) but Poor on mobile LCP across all three templates (High-priority GOOG-04/05), and CrUX field data is entirely unavailable due to the traffic gap.


*DataForSEO (`_agents/dataforseo.md`)*

## Category Score: 8/100
Zero ranked keywords, zero SERP presence across three tracked queries, 6 backlinks at spam score 67 on a 6-week-old domain — the only positives are an unusually low keyword-difficulty landscape (three KD-0 targets) and demonstrated vendor access to AI Overview citations.


*Technical SEO (`_agents/technical.md`)*

## Category Score: 78/100

Foundational technical SEO (crawlability, indexability, sitemap accuracy, URL/redirect hygiene, mobile viewport, and JS-independent renderability) is solid with zero critical crawl or index blockers found; the score is held down by a cluster of missing security headers (HSTS, CSP, clickjacking protection — all confirmed absent site-wide) and a lab-confirmed "Poor" mobile LCP, with minor additional deductions for a non-redirecting case-variant URL and no IndexNow implementation.


*Content & E-E-A-T (`_agents/content.md`)*

## Category Score: 41/100

E-E-A-T breakdown (this skill's internal weighting model): Experience 50/100 (weight 20% → 10.0) — genuine batch-level COA data is a real experience/originality signal, but no narrative/first-person or team-process content exists; Expertise 38/100 (weight 25% → 9.5) — real citations and CAS numbers are undercut by live `[VERIFY DOI]` placeholders (CONT-02) and zero author/credential signal (CONT-08); Authoritativeness 35/100 (weight 25% → 8.75) — named independent testing labs are a positive external-validation signal, but the domain shows no measurable off-site authority per the parent technical/GEO audit and no informational content exists to earn citations; Trustworthiness 40/100 (weight 30% → 12.0) — solid legal-page coverage (privacy, terms, refund/shipping, RUO, FDA disclaimers) is undercut by a still-broken consent link (CONT-01), inconsistent disclaimer wording (CONT-07), and contact/business-transparency signals that exist but are not surfaced (CONT-09). Weighted total ≈ 40.25 → rounded to **41/100**. This sits below the parent audit's original 58/100 Content Quality category score because this pass surfaced two Critical-severity defects not previously itemized as content findings (the still-live `[VERIFY DOI]` placeholder text, and the Trust-lens restatement of the still-broken terms link) alongside confirmation that the prose quality itself (filler/AI-pattern-free, high information density) remains genuinely strong — the score reflects a site with clean writing and one real data asset, held down by unresolved editorial defects and a thin, undifferentiated trust/expertise surface rather than by low-quality prose.


*Schema / Structured Data (`_agents/schema.md`)*

## Category Score: 78/100

Core technical schema (WebSite/SearchAction, BreadcrumbList, MerchantReturnPolicy, Dataset/DataDownload architecture) is well-implemented and free of deprecated types, but the score is held back by unmerged/duplicate Organization entities across three separate emitters (SCHEMA-01–03, -10) and missing recommended Product/Dataset trust properties (SCHEMA-04, -06, -09) that reduce rich-result and entity-consolidation eligibility.


*Sitemap (`_agents/sitemap.md`)*

## Category Score: 88/100
Justification: Core sitemap mechanics are fully correct (valid XML, correct robots.txt declaration, all 43 URLs return 200, all spot-checked URLs are self-canonical and indexable, well under size limits, no deprecated tags, coverage matches actual site architecture); score is reduced for one orphaned live product page (MAP-01), a duplicate-content/self-canonical gap on unbounded shop pagination outside the sitemap (MAP-03), and a lastmod-accuracy process issue on static pages (MAP-02).


*Performance (lab) (`_agents/performance.md`)*

## Category Score: 42/100

LCP fails "Poor" (>4.0 s) on all four templates driven by a consistent, whole-site render-blocking chain (~60% of CSS+JS requests) and unoptimized images (up to 90–96% waste on individual assets) — this is the dominant, unresolved condition and caps the score. CLS is currently passing everywhere and TBT/INP-proxy risk is currently in the "Good" lab range, and server infrastructure (TTFB, Cloudflare+Kinsta caching, Brotli) is strong and not a contributing cause — which meaningfully narrows remediation scope but does not offset the LCP failure being universal across every audited template.


*Visual / Mobile (`_agents/visual.md`)*

## Category Score: 38/100
A mandatory, non-dismissible, full-viewport interstitial with no background inerting/focus-trap currently determines 100% of the first-visit above-the-fold experience on every page type (Critical, VIS-01), compounded by an accessibility gap in how it's implemented (VIS-02) and a measurement-integrity risk for all future Core Web Vitals/PageSpeed audits (VIS-05); the two Low-priority items (VIS-03, VIS-04) are minor by comparison. Score reflects that the single most important visual/UX fact for this site — what a first-time visitor actually sees — is currently a compliance gate rather than the product/value proposition, on every template tested.


*GEO / AI Search (`_agents/geo.md`)*

## Category Score: 51/100

Dimension estimates — Citability 58/100, Structural Readability 65/100, Multi-Modal Content 28/100, Authority & Brand Signals 13/100, Technical Accessibility 85/100 — weighted 25/20/15/20/20 → **51/100**. The site is technically wide open to every AI crawler in scope and already produces one genuinely rare, well-structured citable asset (the COA batch `Dataset` schema), but it carries a near-zero off-site authority signal confirmed against a live SERP where two direct competitors are already cited inside Google's AI Overview for the category's head term — that gap, not any on-page defect, is the dominant constraint on the score.


*SXO (`_agents/sxo.md`)*

## Category Score: 55/100

Content substance is genuinely strong (real batch data, transparent failed-QC disclosure, a page type — the COA archive — with zero SERP peer), but the score is held down by a first-impression journey defect that hits every persona identically (SXO-01), a complete absence of the comparison/PAA-targeted content type the SERP is visibly rewarding (SXO-02/03), and one persona (bulk/wholesale) with no coverage at all (SXO-07).


*E-commerce (`_agents/ecommerce.md`)*

## Category Score: 58/100

Solid schema foundation (valid Product/Offer/return-policy markup on 100% of SKUs, correct indexability of OOS pages, working related-products linking) is offset by two Critical gaps directly inside this category's stated priorities — zero review/rating capture anywhere, and a COA trust-signal chain that is broken for 40% of the catalog including two high-recognition compounds — plus a one-directional trust-to-commerce link gap and a real (not templated-in-general, but one specific) content-uniqueness violation.

---

# DATAFORSEO API CALLS MADE (verbatim)

## API Calls Made
- dataforseo_labs_google_domain_rank_overview — pepselect.com, US, en ($0.01)
- dataforseo_labs_google_ranked_keywords — pepselect.com, US, en, limit 100 ($0.05)
- backlinks_summary — pepselect.com ($0.02)
- serp_organic_live_advanced — "buy research peptides", US, en, desktop, depth 100 ($0.002)
- serp_organic_live_advanced — "bpc-157 for sale", US, en, desktop, depth 100 ($0.002)
- dataforseo_labs_bulk_keyword_difficulty — 10 keywords, US, en ($0.01)

Total: $0.094 (as pre-approved).

---

# PER-AGENT REPORT PREAMBLES & SUPPLEMENTARY DATA SECTIONS (verbatim)

---

*Source: Google APIs (GSC / PageSpeed / CrUX) agent (`_agents/google.md`)*

# Google SEO API Audit — pepselect.com

- Date: 2026-08-18
- Property: `sc-domain:pepselect.com`
- Credential tier: **Tier 1** (PageSpeed Insights v5 + CrUX API + CrUX History API + Search Console API authenticated; Indexing API authenticated but used READ-ONLY; GA4 not configured — skipped)
- Mode: STRICTLY READ-ONLY. No sitemap submissions, no Indexing API publish/delete calls, no property changes were made.

---

## 1. GSC Search Analytics — Raw Data

### 1a. Top queries — last 28 days (2026-07-21 to 2026-08-15)

`totals_complete: true` (site-wide totals, safe to use). Individual query rows can omit anonymized low-volume traffic and must NOT be summed as a total.

| Metric | Value |
|---|---|
| Clicks (total) | 0 |
| Impressions (total) | 5 |
| CTR (total) | 0% |
| Avg. position (total) | 34.0 |

Query rows returned (only 2 rows surfaced; row-level impressions do not sum to the total of 5 due to anonymization of low-volume queries):

| Query | Clicks | Impressions | CTR | Position |
|---|---|---|---|---|
| peptselect (misspelling) | 0 | 1 | 0% | 95.0 |
| retatrutide 30 mg | 0 | 1 | 0% | 68.0 |

### 1b. Top queries — last 6 months (2026-02-17 to 2026-08-15)

| Metric | Value |
|---|---|
| Clicks (total) | 0 |
| Impressions (total) | 5 |
| CTR (total) | 0% |
| Avg. position (total) | 34.0 |

Query rows (identical to the 28-day window — see GOOG-03 below):

| Query | Clicks | Impressions | CTR | Position |
|---|---|---|---|---|
| peptselect | 0 | 1 | 0% | 95.0 |
| retatrutide 30 mg | 0 | 1 | 0% | 68.0 |

Tool warning attached to the 6-month query: *"GSC impressions logging error affected impressions, CTR, and average position from 2025-05-13 through 2026-04-27; clicks were not affected."* This is a caveat on historical impressions/CTR/position data quality for that window; clicks are reported as unaffected.

### 1c. Top pages — last 28 days and last 6 months (identical results both windows)

| Page | Clicks | Impressions | CTR | Position |
|---|---|---|---|---|
| https://pepselect.com/ | 0 | 2 | 0% | 48.5 |
| https://pepselect.com/about-us/ | 0 | 2 | 0% | 3.5 |
| https://pepselect.com/faq/ | 0 | 1 | 0% | 7.0 |
| https://pepselect.com/testing/retatrutide-30mg/ | 0 | 1 | 0% | 68.0 |
| https://pepselect.com/testing/tb-500-10-mg/tb10-6926/ | 0 | 1 | 0% | 2.0 |

Totals: clicks 0, impressions 5, CTR 0%, position 34.0 (`totals_complete: true`), row_count: 5.

### 1d. By device — last 28 days and last 6 months (identical)

| Device | Clicks | Impressions | CTR | Position |
|---|---|---|---|---|
| Desktop | 0 | 3 | 0% | 55.0 |
| Mobile | 0 | 2 | 0% | 2.5 |

### 1e. By country (top 10 returned, only 3 with data) — last 28 days and last 6 months (identical)

| Country | Clicks | Impressions | CTR | Position |
|---|---|---|---|---|
| GBR | 0 | 1 | 0% | 3.0 |
| IND | 0 | 1 | 0% | 95.0 |
| USA | 0 | 3 | 0% | 24.0 |

### 1f. Sitemaps (read-only)

| Sitemap | Last submitted | Pending | Is index | Warnings | Errors | Contents |
|---|---|---|---|---|---|---|
| https://pepselect.com/sitemap_index.xml | 2026-08-14T05:19:52.766Z | No | Yes | 0 | 0 | web: 43 submitted, image: 16 submitted |

Note: the tool flags that `contents[].submitted` reflects submission counts only, not indexation truth — URL Inspection is the source of truth for indexation (see section 2).

### 1g. URL Inspection (read-only)

| URL | Verdict | Coverage state | Robots/Indexing | Last crawl | Crawled as | Canonical match | Referring URLs |
|---|---|---|---|---|---|---|---|
| https://pepselect.com/ | PASS | Submitted and indexed | ALLOWED / INDEXING_ALLOWED | 2026-08-02T08:53:11Z | MOBILE | Yes (Google canonical = user canonical = https://pepselect.com/) | https://dk.trustpilot.com/review/pepselect.com |
| https://pepselect.com/testing/ | NEUTRAL | **URL is unknown to Google** | unspecified (never crawled) | never | — | n/a | none |
| https://pepselect.com/shop/ | NEUTRAL | **Discovered - currently not indexed** | unspecified | never | — | n/a | none |
| https://pepselect.com/product/bpc157-10/ | NEUTRAL | **Discovered - currently not indexed** | unspecified | never | — | n/a | https://pepselect.com/product-sitemap.xml |
| https://pepselect.com/product/ghk-cu/ | NEUTRAL | **Discovered - currently not indexed** | unspecified | never | — | n/a | none |

Rich results on homepage: PASS, Breadcrumbs detected. Mobile usability: `VERDICT_UNSPECIFIED` (no issues surfaced) for all URLs tested.

Sitemap context: the product sitemap (`product-sitemap.xml`) declares 16 URLs (`/shop/` + 15 product pages). Both sampled product URLs are "Discovered - currently not indexed," suggesting this pattern likely extends to some or all of the other 13 unsampled product pages (not independently verified — flagged as a hypothesis, not confirmed fact).

---

## 2. PageSpeed Insights (Lab data) — Raw Scores and Metrics

INP terminology used throughout (no FID references except where PSI's own lab-only "Max Potential FID" diagnostic surfaces — noted verbatim since it is a Lighthouse lab proxy metric, not a field INP measurement).

| Page | Strategy | Perf score | LCP | CLS | TBT | Speed Index | TTI |
|---|---|---|---|---|---|---|---|
| Homepage (/) | Mobile | 62/100 | 8.9 s (Poor) | 0 (Good) | 30 ms (Good) | 5.7 s | 8.9 s |
| Homepage (/) | Desktop | 93/100 | 1.4 s (Good) | 0.006 (Good) | 130 ms (Good) | 1.2 s | 1.4 s |
| Shop (/shop/) | Mobile | 67/100 | 7.8 s (Poor) | 0.014 (Good) | 50 ms (Good) | 3.9 s | 7.9 s |
| Shop (/shop/) | Desktop | 95/100 | 1.2 s (Good) | 0.007 (Good) | 20 ms (Good) | 1.5 s | 1.2 s |
| Product (bpc157-10) | Mobile | 74/100 | 4.2 s (Poor, just over 4.0s threshold) | 0 (Good) | 220 ms (Good) | 3.9 s | 5.1 s |
| Product (bpc157-10) | Desktop | 95/100 | 1.0 s (Good) | 0.001 (Good) | 10 ms (Good) | — | — |

Note: The first desktop PSI request for the product page returned a transient `500 Internal Server Error` from the PageSpeed API; a retry succeeded and is the data used above.

### Top opportunities (mobile, where scores are worst)

**Homepage mobile:**
- Reduce unused JavaScript — est. savings 900 ms (176KB from Google Tag Manager + confetti.js unused bytes)
- Render-blocking requests — est. savings 2,530 ms
- Improve image delivery — est. savings 408 KiB (hero image `PS-laying_fam-768x434.png` = 367,813 bytes with 333,719 bytes wasted; logo PNGs also oversized)
- Total page weight: 1,669 KiB, 114 requests

**Shop mobile:**
- Render-blocking requests — est. savings 2,950 ms
- Reduce unused JavaScript — est. savings 600 ms
- Reduce unused CSS — est. savings 150 ms
- Improve image delivery — est. savings 79 KiB
- Total page weight: 1,164 KiB, 113 requests

**Product mobile (bpc157-10):**
- Render-blocking requests — est. savings 2,680 ms
- Reduce unused CSS — est. savings 300 ms
- Improve image delivery — est. savings 160 KiB
- Max Potential FID (lab-only Lighthouse diagnostic, not a field INP measurement) — 190 ms
- Total page weight: 1,066 KiB, 116 requests

### Cross-page Best Practices / SEO audit failures (all three pages, both strategies)

- No CSP (Content-Security-Policy) header found (High severity — XSS/clickjacking mitigation)
- No frame control policy (clickjacking mitigation)
- No HSTS header
- No COOP header (origin isolation)
- "Links do not have descriptive text" — repeated "Learn more" anchor text on product cards (homepage: 4 links, shop: 7 links, product: 2 links)
- Insufficient color-contrast on several UI elements (age-gate card, price/kicker text, badge spans)
- `document-title`, `meta-description`, `canonical`, `robots-txt`, `image-alt`, `hreflang`, `is-crawlable` all PASS on every page tested

---

## 3. CrUX + CrUX History (Field data) — Raw Results

All CrUX queries — origin-level and page-level, both PHONE and DESKTOP form factors — returned **no data**.

| Target | Form factor | Level | Result |
|---|---|---|---|
| https://pepselect.com | PHONE | Origin (CrUX History) | No data — "Insufficient Chrome traffic volume for eligibility" |
| https://pepselect.com | DESKTOP | Origin (CrUX History) | No data — "Insufficient Chrome traffic volume for eligibility" |
| https://pepselect.com/ | PHONE | Origin (CrUX snapshot, via pagespeed_check) | No data — "insufficient Chrome traffic volume for eligibility" |
| https://pepselect.com/ | ALL | Origin (CrUX snapshot) | No data |
| https://pepselect.com/shop/ | PHONE | Page (CrUX History) | No data — insufficient eligibility |
| https://pepselect.com/shop/ | DESKTOP | Page (CrUX History) | No data — insufficient eligibility |
| https://pepselect.com/shop/ | ALL | Page (CrUX snapshot) | No data |
| https://pepselect.com/product/bpc157-10/ | PHONE | Page (CrUX History) | No data — insufficient eligibility |
| https://pepselect.com/product/bpc157-10/ | DESKTOP | Page (CrUX History) | No data — insufficient eligibility |
| https://pepselect.com/product/bpc157-10/ | ALL | Page (CrUX snapshot) | No data |

**Explicit statement:** insufficient eligibility — Google has no field data (real-user Core Web Vitals) for this origin or any of the sampled URLs, in any form factor. This is consistent with the near-zero GSC click/impression volume: the site does not yet have enough Chrome real-user traffic to populate CrUX. No 25-week trend can be summarized because there is no history to summarize. Fall back to PSI Lighthouse lab data (section 2) for all CWV assessment in this audit.

---

## Findings

---

*Source: DataForSEO agent (`_agents/dataforseo.md`)*

# DataForSEO Analysis — pepselect.com

**Date:** 2026-08-18 · **Data source:** DataForSEO (live) · **Evidence class:** 4 (third-party estimates)
**Scope:** Domain rank, ranked keywords, backlinks, 2 live SERPs (+1 pre-paid SERP file), keyword difficulty for 10 target terms.

---

---

*Source: Technical SEO agent (`_agents/technical.md`)*

# Technical SEO Audit — pepselect.com
Date: 2026-08-18
Scope: Crawlability, indexability, security headers, URL structure, mobile, JS rendering, redirects, 404 handling, hreflang, IndexNow. Read-only GET requests only. Structured data (JSON-LD) validation and detailed render-blocking-asset remediation are covered by the schema and performance sub-agents respectively; this report references those findings by dependency rather than duplicating them.

---

---

*Source: Content & E-E-A-T agent (`_agents/content.md`)*

# Content Quality / E-E-A-T Audit — pepselect.com
Date: 2026-08-18
Scope: E-E-A-T signals, word-count-vs-page-type minimums, readability, keyword optimization, AI citation readiness, freshness signals, and QRG Sept-2025 AI-content-quality markers. Read-only GET requests only. Sitemap enumerated in full (43/43 URLs across 4 child sitemaps: `page-sitemap.xml`, `ps_compound-sitemap.xml`, `ps_coa_test-sitemap.xml`, `product-sitemap.xml`). Deep-sampled 23 of 43 URLs: homepage, `/shop/`, `/testing/` hub, 2 compound-archive pages, 1 COA batch page, 10 of 16 product pages, `/contact/`, `/faq/`, `/privacy-policy/`, `/terms-conditions/`, `/ruo-disclaimer/`, `/refund-shipping-policy/`, `/military-discount/`, `/track-your-order/`. No `/blog/` or guide content exists anywhere in the sitemap — confirmed by full enumeration, not assumed.

**Tooling note:** `render_page.py --json` truncates `extracted_text` to ~503 characters per the tool's own summary-mode behavior, so it could not be used directly for word counts. Full HTML was captured instead via `render_page.py -o <file>` (untruncated), and a purpose-built regex-based HTML-to-text stripper (`extract_text.py`, written for this audit — removes `<script>/<style>/<noscript>` blocks and HTML comments, converts block-level tags to line breaks, strips remaining tags, decodes entities) was used for word counts, boilerplate diffing, and readability. `bs4` and `trafilatura` are not installed in this environment's Python, so this is a blunter tool than trafilatura's boilerplate removal — it does not exclude nav/footer chrome the way trafilatura does, so raw "total word" figures below are an upper bound; boilerplate is instead quantified explicitly by exact-line-match diffing across pages (see CONT-03), which is not sensitive to this limitation. `content_quality.py` (bundled QRG scorer) was run against the stripped text for 8 representative pages.

---

---

*Source: Schema / Structured Data agent (`_agents/schema.md`)*

# Schema.org Structured Data Audit — pepselect.com

Date: 2026-08-18
Method: Read-only GET crawl via `claude-seo run render_page.py --mode auto` (raw HTML; no page in this sample triggered SPA rendering — `is_spa: false`, `mode_used: raw` on every URL). JSON-LD extracted with `--json-ld-output` (bounded full blocks); microdata/RDFa checked by grepping raw HTML for `itemscope`, `itemtype=`, `typeof=`.

Sitemap enumeration: `sitemap_index.xml` → `page-sitemap.xml`, `product-sitemap.xml`, `ps_compound-sitemap.xml`, `ps_coa_test-sitemap.xml`.

Pages sampled:
- Homepage: `https://pepselect.com/`
- Shop/category: `https://pepselect.com/shop/`
- Product ×3: `https://pepselect.com/product/bpc157-10/`, `https://pepselect.com/product/ghk-cu/`, `https://pepselect.com/product/ss-31/`
- `/testing/` (compound archive index): `https://pepselect.com/testing/`
- `/testing/` compound-level page: `https://pepselect.com/testing/retatrutide-30mg/`
- `/testing/` batch/COA leaf page (Dataset/DataDownload): `https://pepselect.com/testing/retatrutide-30mg/nd_r30_060326/`
- Contact page (used as about/contact): `https://pepselect.com/contact/`
- `/faq/` (checked only to confirm FAQPage status per hard rules): `https://pepselect.com/faq/`

---

---

*Source: Sitemap agent (`_agents/sitemap.md`)*

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

---

*Source: Performance (lab) agent (`_agents/performance.md`)*

# Performance Audit — pepselect.com (Lab)

Date: 2026-08-18
Method: Google PSI API v5 (Lighthouse 13.x, insight-based audits), `psi-only` mode (no CrUX field data pulled — field data is owned by a separate agent). Direct `curl` observation of TTFB, HTTP response headers (compression, caching, CDN) for cross-validation of Lighthouse's throttled lab numbers. Mobile strategy only (Lighthouse mobile emulation is Google's primary ranking signal surface; desktop was not run for this pass — see Limitations).

URLs audited (from `https://pepselect.com/sitemap_index.xml` plus the requested non-sitemap `/testing/` page):
- Home: `https://pepselect.com/`
- Product: `https://pepselect.com/product/bpc157-10/`
- Shop (WooCommerce archive): `https://pepselect.com/shop/`
- Testing/COA lookup: `https://pepselect.com/testing/` (200 OK, intentionally absent from the Yoast sitemap)

Raw run summary (Lighthouse mobile, single run each):

| URL | Perf score | LCP | FCP | TBT | CLS | Speed Index | Total requests | Total weight |
|---|---|---|---|---|---|---|---|---|
| Home | 69 | 6.0 s | 3.2 s | 75 ms | 0.084 | 3.4 s | 114 | 1,710,045 B (1,670 KiB) |
| Product | 76 | 4.7 s | 3.3 s | 40 ms | 0 | 3.3 s | 116 | 1,089,904 B |
| Shop | 72 | 5.7 s | 3.2 s | 30 ms | 0.018 | 3.7 s | 113 | 1,191,826 B |
| Testing | 65 | 5.0 s | 3.2 s | 130 ms | 0 | 12.8 s | 87 | 1,260,540 B |

Resource-type breakdown (`resource-summary` audit, requestCount / transferSize bytes):

| URL | Script | Stylesheet | Image | Font |
|---|---|---|---|---|
| Home | 44 req / 464,246 B | 49 req / 134,346 B | 10 req / 926,403 B | 7 req / 152,349 B |
| Product | 48 req / 492,314 B | 47 req / 146,198 B | 10 req / 265,027 B | 7 req / 152,666 B |
| Shop | 41 req / 461,565 B | 41 req / 132,216 B | 20 req / 413,391 B | 7 req / 152,346 B |
| Testing | 36 req / 419,870 B | 32 req / 123,762 B | 9 req / 543,250 B | 6 req / 141,733 B |

This confirms (fresh, 2026-08-18) the prior inline-audit observation of 31–49 render-blocking-eligible stylesheets and 34–48 scripts on templated pages — the CSS/JS request counts above land inside that range on all four templates.

---

---

*Source: Visual / Mobile agent (`_agents/visual.md`)*

# Visual / Mobile Rendering Audit — pepselect.com
Date: 2026-08-18
Tooling: claude-seo bundled Playwright scripts (`capture_screenshot.py`, `render_page.py`), plus read-only `GET` fetches of raw HTML for structural verification. Chromium via claude-seo managed runtime confirmed ready (`claude-seo doctor` → Runtime: ready, Chromium: ready).

Viewports tested: Laptop/"Desktop" 1366×768, Mobile 375×812 (device scale factor 2, per tool default).
Pages tested: Homepage (`/`), Product (`/product/bpc157-10/`), Shop/category (`/shop/`), `/testing/`.

All screenshots saved to `C:\Users\paulo\Documents\Pep Select Website\docs\claude-seo-latest\_agents\screenshots\`:
`homepage_laptop.png`, `homepage_mobile.png`, `product_laptop.png`, `product_mobile.png`, `shop_laptop.png`, `shop_mobile.png`, `testing_laptop.png`, `testing_mobile.png`.

## Headline observation
On a fresh browser session (no cookies — the same state as any first-time visitor, and the same state any automated crawler/testing tool would start from), **all four page types render byte-identical screenshots at each viewport**: an "Research Access Verification" gate (`#psag-gate`) that fully occupies the viewport. MD5 checksums confirm `homepage_laptop.png` = `product_laptop.png` = `shop_laptop.png` = `testing_laptop.png` (`965048a2...`), and the same for all four `*_mobile.png` (`715a6e30...`). This is the dominant visual/UX fact for this audit and is documented in detail below.

Raw-HTML inspection (GET only, no form submission) confirms the gate is server-rendered (present in the initial HTML response, not injected only by client JS) on all four URLs, and that real page content (H1s, nav, Elementor markup) exists in the DOM behind it:
- `/` → H1: "The label is the easy part. / What's behind it matters."
- `/product/bpc157-10/` → H1: "BPC-157"
- `/shop/` → H1: "Selection is the standard."
- `/testing/` → H1: "Every batch has a permanent address."

Because the gate blocks all rendered pixels pre-interaction, **true above-the-fold hero/CTA layout, image rendering, and any visually-observable layout shift for the real page templates could not be assessed from screenshots** — see Data Sources & Limitations. Structural facts below are taken from raw HTML where a screenshot could not confirm rendering.

---

---

*Source: GEO / AI Search agent (`_agents/geo.md`)*

# GEO Audit — pepselect.com

Audit date: 2026-08-18 · Scope: AI crawler access, llms.txt, passage-level citability (homepage, `/testing/`, one product page, `/faq/`), Q&A extractability, brand-mention signals, platform-specific readiness (Google AI Overviews, ChatGPT search, Perplexity, Bing Copilot). Method: live read-only GET fetches (`claude-seo run render_page.py`, raw + rendered modes) plus one DataForSEO live-SERP artifact supplied by the coordinator (`DATAFORSEO-serp-research-peptides-2026-08-18.md`, evidence class 4). No DataForSEO calls were made by this agent. Findings ID prefix: `GEO-NN`.

---

---

*Source: SXO agent (`_agents/sxo.md`)*

# Search Experience Optimization (SXO) — pepselect.com

Audit date: 2026-08-18 · Read-only GET analysis · Keyword cluster: "research peptides" (head term) + PAA/related-search cluster

**Pages analyzed:**
- Homepage — `https://pepselect.com/`
- Category/shop page — `https://pepselect.com/shop/`
- Product page (sample) — `https://pepselect.com/product/glp3-r10/`
- COA archive hub — `https://pepselect.com/testing/` (+ sub-pages `/testing/retatrutide-20mg/`, `/testing/ghk-cu-50-mg/psgkcu5071926gx/`)

**Method:** Reused raw HTML captures and rendered screenshots from the same-day full-site crawl (`docs/claude-seo-latest/00-audit-context.md`, `01-critical-and-high-findings.md`, `FRESH-seo-page-homepage-2026-08-18.md`, `_agents/screenshots/*.png`) plus fresh text/schema extraction run against those captures for this pass. SERP evidence is the supplied DataForSEO live pull (`DATAFORSEO-serp-research-peptides-2026-08-18.md`). No new fetches, no DataForSEO calls, GET only.

---

## SERP Snapshot (evidence class 4 — DataForSEO, live pull)

9 organic results for "research peptides": **7 commercial vendor pages / 2 informational (NIH academic review, YouTube/STAT video) → SERP consensus: 78% Commercial Storefront-or-Category type, confidence 78%.** No shopping carousel, no local pack, no Reddit/forum block.

- **Dominant page type:** vendor homepage or category-style "Buy Research Peptides" page, title leading with trust modifiers: "third-party tested," "99%+ purity," "COA," "US made."
- **SERP features:** AI Overview (cites 2 of 9 vendors directly — americanpeptides.us, rpeptide.com), 4 PAA questions, "Things to know" cards on Benefits/Regulations/Purity Standards (citing NIH/Harvard/WebMD + 5 vendor blog posts), a scholarly-articles block, 8 related searches (all commercial: "for sale," "best place to buy," "cheapest").
- **Price-range annotations** appear in 2 of 9 snippets (rpeptide.com $120–$200; redrockpeptides.com $59–$410) and `srsltid` Merchant Center tagging appears on 2 of 9 — both are SERP-visible trust/comparison signals Pep Select does not currently surface.

---

## Page-Type Classification vs. Target Pages

| Target page | Classification | SERP peer type | Mismatch severity |
|---|---|---|---|
| `/` (homepage) | Commercial homepage, trust-forward narrative | Matches dominant type (phoenixpeptide.com, pspeptides.com pattern) | **ALIGNED** (execution gap only: brand-voice H1 carries no keyword signal — already logged as O-01 in the main audit; not re-litigated here) |
| `/shop/` | Commercial category/listing page | Matches dominant type structurally, but SERP winners in this type show price-range breadth and Merchant-feed trust tagging that this page lacks | **HIGH** — right type, under-executed relative to the SERP bar |
| `/product/glp3-r10/` | Commercial product/transactional page | No exact peer in this specific SERP capture — "research peptides" is a category head-term, so none of the 9 organic slots are single-compound product pages | **ALIGNED**, with a stated limitation: this SERP pull cannot validate compound-level ("buy BPC-157," "GLP-3 R purity") query performance — see Limitations |
| `/testing/` (+ batch pages) | Structured lab-data archive / trust resource hub | **0 of 9** organic competitors expose an equivalent public per-batch COA archive as an indexable hub | **ALIGNED-UNIQUE** — a genuine white-space asset, not a mismatch, but its promotion and framing lag its content quality (see SXO-06) |

---

## User Stories (derived from SERP signals, ≥2 journey stages)

| # | Stage | Story | Signal it's derived from |
|---|---|---|---|
| 1 | Awareness | "I just searched 'research peptides' — I want to see in the first screen who is a legitimate, third-party-tested US vendor, so I don't waste time on a shady supplier." | 7 of 9 organic titles lead with "third-party tested / 99%+ purity / COA / US made"; PAA "What is the most trusted peptide company?" |
| 2 | Consideration | "I'm comparing vendors — I want to see price range and stock breadth at a glance before I click into individual products." | rpeptide.com ($120–$200) and redrockpeptides.com ($59–$410) show price-range annotations directly in the SERP snippet; related search "cheapest research peptides" |
| 3 | Consideration/Decision | "I'm skeptical of purity claims in general — I want proof this specific vial was actually tested, not a generic badge." | Google's own "Purity Standards" Things-to-know card (HPLC >99%, COA, MS); PAA "Do research peptides actually work?" |
| 4 | Decision | "I've ordered before — I want to re-find my batch's COA fast, without re-doing a full verification flow." | Implied repeat-purchase workflow behind the "Remember me for 30 days" gate control; matches Pep Select's own FAQ entry "How do I find the documents for my batch?" |
| 5 | Awareness/Safety | "Before I commit to a US purchase, I want a plain, direct answer on legal status and risk." | PAA "Where can I buy research peptides in the USA?" and "What is the risk of taking peptides?"; AI Overview leans heavily on safety/regulatory caveats |

---

## Persona Scoring (Relevance / Clarity / Trust / Action — 25 pts each, 100 total)

Personas derived from the SERP signals above plus one absent from any current page (bulk/B2B).

| Persona | Landing page(s) | Relevance | Clarity | Trust | Action | Total | Weakest lever |
|---|---|---|---|---|---|---|---|
| **Bulk/wholesale buyer** | none — no page exists | 5 | 5 | 10 | 5 | **25/100** | No B2B/bulk page type at all (GenScript occupies this type in-SERP; Pep Select doesn't) |
| **First-time buyer comparing vendors on trust** | `/`, `/shop/` | 15 | 12 | 12 | 10 | **49/100** | Trust proof (purity/lab/batch) sits below the fold and behind the research gate on first paint |
| **Skeptical researcher ("do peptides work")** | `/product/glp3-r10/` | 18 | 15 | 15 | 10 | **58/100** | No standalone evidence/citations hub; per-product citations exist but aren't consolidated or promoted |
| **Price-comparison shopper** | `/shop/` | 15 | 15 | 12 | 15 | **57/100** | No visible price range/breadth signal; 8 of 15 SKUs show "Out of stock" |
| **Risk/safety-conscious researcher** | none targeted directly | 12 | 15 | 15 | 12 | **54/100** | No page maps to the "risk of taking peptides" PAA intent; RUO page is legal-framed, not safety-framed |
| **USA-legality-focused buyer** | `/`, `/ruo-disclaimer/` | 18 | 15 | 18 | 12 | **63/100** | RUO/disclaimer content exists but is not framed to answer "where to buy in the USA" as a discoverable page |
| **Experienced researcher re-ordering** | `/testing/`, `/faq/` | 20 | 18 | 20 | 18 | **76/100** | Strongest-served persona — COA archive + FAQ batch-lookup entry already answer this need |

Recommendations below are sequenced weakest-persona-first: bulk/wholesale buyer → first-time trust comparer → price-comparison shopper → risk/safety-conscious researcher → USA-legality buyer → skeptical evidence-seeker → experienced re-orderer (already well served).

---

## Findings

---

*Source: E-commerce agent (`_agents/ecommerce.md`)*

# E-commerce SEO Findings — pepselect.com

Audit date: 2026-08-18. Scope: product-page optimization, Product schema completeness, category/shop architecture, out-of-stock handling, review capture, COA trust-signal linking, product↔category↔content internal linking, and free-listing/Merchant Center structured-data implications for a Shopping-restricted vertical.

Method: GET-only crawl via `render_page.py --mode always` (Playwright-rendered HTML) + JSON-LD extraction via `--json-ld-output`. Sitemap fully enumerated from `sitemap_index.xml` → 4 sub-sitemaps → 43 total URLs (9 pages, 9 `ps_compound` COA hub pages, 9 `ps_coa_test` batch pages, 16 product-sitemap entries: `/shop/` + 15 products). All 15 real products (`bpc157-10`, `ghk-cu`, `ss-31`, `tb500-10`, `motsc-10`, `glp1-s10`, `nad`, `glp3-r20`, `tesa-10`, `pt-141`, `glp3-r30`, `glutathione`, `glp2-t20`, `glp3-r10`, `bacteriostatic-water-30ml`) plus `/shop/` and `/testing/` were rendered and their Product/WebPage JSON-LD parsed. No DataForSEO calls were made; the one DataForSEO citation below reuses the existing `DATAFORSEO-serp-research-peptides-2026-08-18.md` report per instructions.

---

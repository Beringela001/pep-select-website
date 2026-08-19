# INTEGRATED-01 — Critical & High Findings
> Part of the Pep Select integrated SEO audit — 2026-08-18 — target https://pepselect.com
> Files: INTEGRATED-00 context & scorecard · INTEGRATED-01 critical & high · INTEGRATED-02 medium, low & verified-correct · INTEGRATED-03 strategy & action plan · INTEGRATED-04 evidence & limitations
>
> Evidence classes used throughout: **[1]** Google Search Console (verified) · **[2]** PageSpeed Insights (laboratory) · **[3]** CrUX (real-user field) · **[4]** DataForSEO (third-party estimate) · **[5]** Crawler observation / inference.
> All findings below are reproduced **verbatim** from the specialist agent reports in `_agents/`; only the source-agent attribution line above each block was added.

**Contents: 13 Critical, 24 High findings.**

## Index — Critical

- [GOOG-01] Shop category and sampled product pages are not indexed by Google — Critical (Google APIs (GSC / PageSpeed / CrUX))
- [GOOG-03] Near-zero organic search visibility across the entire measurement window — Critical (Google APIs (GSC / PageSpeed / CrUX))
- [DFS-01] pepselect.com has zero organic search footprint in Google US — Critical (DataForSEO)
- [DFS-02] Backlink profile is near-zero, brand-new, and carries a 67/100 spam score — Critical (DataForSEO)
- [CONT-01] `/terms-of-service/` still returns 404 sitewide; every page's compliance gate makes users agree to it — Critical (Content & E-E-A-T)
- [CONT-02] Literal "[VERIFY DOI]" QA placeholder text is live in production on product citation sections — Critical (Content & E-E-A-T)
- [PERF-01] LCP is "Poor" (>4.0 s) on all four audited templates — Critical (Performance (lab))
- [PERF-02] Render-blocking CSS/JS consumes ~50–62% of all stylesheet+script requests, adding an estimated 2.7–3.0 s to first paint on every template — Critical (Performance (lab))
- [VIS-01] Mandatory full-viewport "Research Gate" blocks 100% of above-the-fold content on every page, on every device, before any interaction — Critical (Visual / Mobile)
- [GEO-05] Zero measurable off-site brand/entity signal, confirmed against a live SERP where two direct competitors are already cited in the AI Overview — Critical (GEO / AI Search)
- [SXO-01] Every organic-entry page renders only the research gate at first paint — trust content is deferred, not delivered — Critical (SXO)
- [ECOM-01] Zero review capture or aggregateRating anywhere in the catalog — Critical (E-commerce)
- [ECOM-02] COA testing pages missing for 6 of 15 catalog products, including two headline compounds — Critical (E-commerce)

## Index — High

- [GOOG-02] /testing/ hub page is completely unknown to Google — High (Google APIs (GSC / PageSpeed / CrUX))
- [GOOG-04] Homepage mobile performance is Poor on LCP, driven by render-blocking resources and an oversized hero image — High (Google APIs (GSC / PageSpeed / CrUX))
- [GOOG-05] Shop category page mobile LCP is Poor (7.8s) with heavy render-blocking overhead — High (Google APIs (GSC / PageSpeed / CrUX))
- [DFS-03] pepselect.com is absent from both target commercial SERPs (100-deep crawls) — High (DataForSEO)
- [DFS-04] Keyword difficulty is unusually low — three target terms score KD 0 — High (DataForSEO)
- [DFS-05] Five vendors recur across the commercial SERPs and define the benchmark — High (DataForSEO)
- [DFS-06] AI Overviews sit at/near position 1 on all three SERPs and cite vendor content directly — High (DataForSEO)
- [TECH-01] No HSTS (Strict-Transport-Security) header — High (Technical SEO)
- [TECH-06] Mobile Largest Contentful Paint (LCP) in "Poor" range (lab data) — High (Technical SEO)
- [CONT-03] Sitewide boilerplate quantified at 619–628 identical words, 38%–90% of on-page text depending on template — High (Content & E-E-A-T)
- [CONT-04] Product-page unique content is thin — 294 to 441 unique words across 10 of 16 SKUs sampled — High (Content & E-E-A-T)
- [CONT-05] Zero blog, guide, or informational content exists anywhere on the site — High (Content & E-E-A-T)
- [SCHEMA-01] Product pages emit two disconnected JSON-LD graphs with inconsistent @context — High (Schema / Structured Data)
- [PERF-03] Hero and content images are the single largest identified byte-waste source, led by an unoptimized homepage hero PNG — High (Performance (lab))
- [PERF-05] High JavaScript request volume (36–48 scripts/template) built on jQuery/jQuery UI/Underscore is the primary INP-risk driver — High (Performance (lab))
- [VIS-02] Interstitial gate has no focus-trap or `inert`/`aria-hidden` on background content — High (Visual / Mobile)
- [GEO-03] Product-page description passages are far shorter than the optimal AI-citation length — High (GEO / AI Search)
- [GEO-06] No informational content mapped to the exact query set already proven to earn AI Overview and "Things to know" citations in this niche — High (GEO / AI Search)
- [SXO-02] No comparison or "why choose us" content type exists for the "best/cheapest/most trusted" query cluster — High (SXO)
- [SXO-03] Zero pages address any of the 4 ready-made PAA slots — High (SXO)
- [SXO-04] `/shop/` category page under-delivers the trust density and stock breadth that SERP category-page winners show — High (SXO)
- [ECOM-03] Manufacturer boilerplate description used verbatim on GLP-2 T20, inconsistent with the site's otherwise unique copy — High (E-commerce)
- [ECOM-04] The COA trust archive (`/testing/`) has zero internal links back to product pages — High (E-commerce)
- [ECOM-05] Nearly half the live catalog (7 of 15 products) is Out of Stock, fully indexed, with no substitute-product surfacing — High (E-commerce)

---

# CRITICAL FINDINGS

---

*Source: Google APIs (GSC / PageSpeed / CrUX) agent (`_agents/google.md`)*

### [GOOG-01] Shop category and sampled product pages are not indexed by Google
- Priority: Critical
- Category: Indexation
- Evidence class: 1-GSC verified
- Evidence: URL Inspection API — `/shop/`: coverage_state = "Discovered - currently not indexed"; `/product/bpc157-10/`: coverage_state = "Discovered - currently not indexed" (referring URL: product-sitemap.xml); `/product/ghk-cu/`: coverage_state = "Discovered - currently not indexed" (no referring URLs recorded). None of the three have a recorded `last_crawl_time`.
- Affected URLs: https://pepselect.com/shop/, https://pepselect.com/product/bpc157-10/, https://pepselect.com/product/ghk-cu/ (sitemap declares 15 product URLs total plus /shop/ — unsampled products not independently verified but likely share this state)
- Reasoning: Google has discovered these URLs (they are in the submitted sitemap with 0 sitemap errors) but has explicitly chosen not to crawl/index them yet. For a commerce site, an unindexed category page and unindexed product pages mean these pages cannot appear in organic search results at all — this is the direct cause of the near-zero impressions/clicks in GOOG-03.
- Recommendation: Investigate crawl budget and internal linking depth from the (indexed) homepage to /shop/ and product pages; verify server response times and any JS-dependent rendering that could delay Googlebot's decision to index; consider requesting indexing via Search Console UI for priority product pages once technical causes are ruled out (do not use the Indexing API in read-only sessions).
- Dependencies: Unblocks GOOG-03 (organic visibility) and GOOG-02; depends on confirming crawl/rendering health is not the root cause.
- Failure check: Re-running URL Inspection on these same URLs in 2-4 weeks still shows "Discovered - currently not indexed" or "Crawled - currently not indexed" with no last_crawl_time progress.
- Success check: URL Inspection verdict changes to PASS / "Submitted and indexed" with a populated last_crawl_time and matching Google-selected canonical.
- Leading indicator: GSC "Pages" report (Coverage) — count of pages in "Discovered - currently not indexed" bucket trending down over successive weekly checks.

---

*Source: Google APIs (GSC / PageSpeed / CrUX) agent (`_agents/google.md`)*

### [GOOG-03] Near-zero organic search visibility across the entire measurement window
- Priority: Critical
- Category: Organic Performance
- Evidence class: 1-GSC verified
- Evidence: Site-wide totals (totals_complete: true) — 28-day window (2026-07-21 to 2026-08-15): clicks 0, impressions 5, CTR 0%, avg. position 34.0. 6-month window (2026-02-17 to 2026-08-15): identical — clicks 0, impressions 5, CTR 0%, avg. position 34.0. Only 5 pages have ever recorded any impressions (homepage, /about-us/, /faq/, and two /testing/ COA sub-pages); only 2 queries surfaced ("peptselect" at position 95, "retatrutide 30 mg" at position 68).
- Affected URLs: sitewide (sc-domain:pepselect.com)
- Reasoning: The 28-day and 6-month totals being byte-for-byte identical means essentially all recorded GSC search activity for this property happened within the last 28 days — the site has effectively no organic search history before that. Combined with zero clicks and single-digit impressions, this indicates either a very recently launched/reindexed site, a domain that was previously not properly verified/tracked in GSC, or the indexation gaps documented in GOOG-01/GOOG-02 actively suppressing visibility. There is also a documented Google-side impressions/CTR/position logging error covering 2025-05-13 through 2026-04-27 that may have suppressed some historical reporting (clicks were unaffected by that bug, and clicks are also 0, so the underlying visibility is genuinely near-nil, not just an undercount).
- Recommendation: Treat organic acquisition as pre-launch stage. Prioritize the indexation fixes in GOOG-01/GOOG-02, verify Search Console property age/verification history, and do not judge SEO content/keyword strategy effectiveness until baseline indexation is fixed and a full 28-day cycle of stable data is collected post-fix.
- Dependencies: Depends entirely on resolving GOOG-01 and GOOG-02; blocks any meaningful keyword-strategy or content-optimization prioritization until indexation improves.
- Failure check: 28-day totals remain in single-digit impressions / zero clicks after GOOG-01/GOOG-02 fixes have had 4+ weeks to take effect.
- Success check: 28-day impressions grow into double/triple digits with clicks > 0 and average position improving from the current 34.0.
- Leading indicator: Weekly GSC totals (impressions, clicks) trending upward; number of indexed pages (Coverage report) trending upward.

---

*Source: DataForSEO agent (`_agents/dataforseo.md`)*

### [DFS-01] pepselect.com has zero organic search footprint in Google US
- Priority: Critical
- Category: Organic Visibility / Rankings
- Evidence class: [4-DataForSEO estimate]
- Evidence:
  - `dataforseo_labs_google_domain_rank_overview` (pepselect.com, US, en): `"items": []`, `total_count: null` — no ranking data exists for the domain.
  - `dataforseo_labs_google_ranked_keywords` (pepselect.com, US, en, limit 100): `"items": []` — **0 ranked keywords**, therefore 0 estimated traffic value (ETV), 0 positions tracked.
- Affected URLs: https://pepselect.com/ (entire domain)
- Reasoning: DataForSEO Labs indexes keywords where a domain appears in the top 100 Google results. An empty result set means pepselect.com does not appear in the top 100 for ANY keyword in the US database. The site is invisible to organic search — either not indexed, brand-new (backlink first_seen 2026-07-03 supports this), or blocked from crawling.
- Recommendation: Verify indexation first (Google Search Console coverage report, robots.txt, noindex tags, sitemap submission). Only after confirming Google can index the site does any keyword/content work matter.
- Dependencies: Blocks all other SEO recommendations (DFS-03 through DFS-09 are moot if the site is not indexed).
- Failure check: 60 days from now, ranked_keywords still returns 0 items and GSC shows 0 indexed pages.
- Success check: GSC shows pages indexed; DataForSEO ranked_keywords returns ≥1 item (even position 90+ counts as entry into the index).
- Leading indicator: GSC "Pages indexed" count and impressions graph (free, daily, no audit re-run needed).

---

---

*Source: DataForSEO agent (`_agents/dataforseo.md`)*

### [DFS-02] Backlink profile is near-zero, brand-new, and carries a 67/100 spam score
- Priority: Critical
- Category: Off-Page / Authority
- Evidence class: [4-DataForSEO estimate]
- Evidence (backlinks_summary, pepselect.com, 2026-08-18):

  | Metric | Value |
  |---|---|
  | Backlinks | 6 |
  | Referring domains | 6 |
  | Referring main domains | 6 |
  | Referring IPs / subnets | 6 / 6 |
  | Referring pages | 6 |
  | **Backlinks spam score** | **67 / 100** |
  | First seen | 2026-07-03 06:09:42 UTC |
  | TLD mix | .com ×5, .us.com ×1 |
  | Link types | anchor ×6 |
  | Link attributes | noopener ×6 |
  | Platform types | unknown ×6 |
  | Semantic locations / countries | all empty/unknown |

  No anchor-text breakdown was included in the summary payload (anchors endpoint was budget-excluded).
- Affected URLs: https://pepselect.com/ (domain-level)
- Reasoning: 6 total backlinks with an aggregate spam score of 67 means the few links that exist are predominantly low-quality (67 is high; clean profiles typically sit under 30). The `.us.com` TLD and "unknown" platform classification are consistent with directory/spam-network placements rather than editorial links. The domain's link history begins 2026-07-03 — roughly 6 weeks old in the link graph. Competitors in these SERPs display trust signals (ISO 9001, "cited 2,662×" NIH pages) that imply materially stronger authority.
- Recommendation: Do not acquire more links from similar sources. Prioritize a small number of legitimate, relevant links: supplier/testing-lab partner pages (e.g., Janoshik-style COA verifiers), niche directories with editorial review, and digital-PR around published COA/third-party-testing data. Audit the existing 6 links and disavow if they are from link networks.
- Dependencies: Depends on DFS-01 (indexation) for links to transfer value; unblocks competitive ranking on the low-KD terms in DFS-04.
- Failure check: Spam score stays ≥60 while referring domains grow (i.e., new links are also spam).
- Success check: Referring domains ≥20 with aggregate spam score <30 within 6 months.
- Leading indicator: `backlinks_bulk_ranks` / referring-domain count monthly (cheap bulk call), or free Ahrefs/GSC "Links" report trend.

---

---

*Source: Content & E-E-A-T agent (`_agents/content.md`)*

### [CONT-01] `/terms-of-service/` still returns 404 sitewide; every page's compliance gate makes users agree to it
- Priority: Critical
- Category: Trustworthiness (E-E-A-T)
- Evidence class: 5-Crawler observation/inference
- Evidence: `curl -I https://pepselect.com/terms-of-service/` → `HTTP 404` (re-verified live on 2026-08-18, same date as this audit). `grep -o 'href="[^"]*terms-of-service[^"]*"'` against the captured homepage and product HTML returns `href="/terms-of-service/"` on both. The working legal page is at a different slug: `https://pepselect.com/terms-conditions/` → `HTTP 200`.
- Affected URLs: Site-wide — every one of the 43 sitemap URLs renders the same age/research-gate and cart consent checkboxes that link to `/terms-of-service/`, confirmed independently on home, product, testing, and policy templates in this pass.
- Reasoning: This finding is jointly owned with the technical audit (cross-referenced there as T-01); it is restated here because a broken link on the exact control that asks a visitor to legally agree to terms is a direct, first-order Trustworthiness defect under E-E-A-T — the site cannot demonstrate transparent, honest dealing if the terms a customer is asked to accept are unreachable. In a research-compound / YMYL-adjacent vertical, this is the single highest-leverage trust defect on the site.
- Recommendation: Description only — repoint every `/terms-of-service/` anchor (gate modal, cart consent checkbox, footer, account creation flow) to the live `/terms-conditions/` slug, or 301-redirect the old slug to the new one so any external links/bookmarks are not broken either.
- Dependencies: Shared with Technical audit T-01; fixing it once (likely a single theme/template string or a redirect rule) resolves both.
- Failure check: Anchor text is updated on some templates (e.g., footer) but the gate modal or cart consent checkbox still points to `/terms-of-service/`.
- Success check: `grep -r "terms-of-service"` across a fresh crawl of all 43 URLs returns zero matches, or the URL 301s to `/terms-conditions/` with a 200 final status.
- Leading indicator: Zero 404s for `/terms-of-service/` in server/CDN access logs going forward.

---

*Source: Content & E-E-A-T agent (`_agents/content.md`)*

### [CONT-02] Literal "[VERIFY DOI]" QA placeholder text is live in production on product citation sections
- Priority: Critical
- Category: Expertise / Trustworthiness (E-E-A-T)
- Evidence class: 5-Crawler observation/inference
- Evidence: `grep -l "VERIFY DOI" html/*.html` matched 4 of 10 sampled product pages. Verbatim extracted text: `product_nad` — `"Canto C, Menzies KJ, Auwerx J. Cell Metab. 2015. [VERIFY DOI]"`; `product_ss31` — `"Wu J, et al. Mol Neurobiol. 2019. [VERIFY DOI]"`; `product_motsc10` — two separate hits: `"Kong BS, et al. Diabetes Metab J. 2023. [VERIFY DOI]"` and `"Zheng Y, et al. Front Endocrinol. 2023. [VERIFY DOI]"`; `product_glutathione` — `"Allen EMG, Mieyal JJ. Antioxidants. 2023. [VERIFY DOI]"`. This is a bracketed internal QA/editorial marker (identical in form to `[VERIFY CLAIM]` used by this very audit's own workflow conventions), not a typo or formatting artifact — it reads as an unresolved to-do left in a live "Research context" citation block that end users see next to a price and an Add to Cart button.
- Affected URLs: Confirmed on `https://pepselect.com/product/nad/`, `https://pepselect.com/product/ss-31/`, `https://pepselect.com/product/motsc-10/`, `https://pepselect.com/product/glutathione/`. Only 10 of 16 product-sitemap URLs were sampled (63%); at a 40% hit rate in the sampled set, the remaining 6 unsampled product pages (`bpc157-10` and `tb500-10` were checked and are clean; `glp1-s10`, `glp2-t20`, `glp3-r10`, `glp3-r20`, `glp3-r30`, `pt-141`, `tesa-10`, `ghk-cu`, `bacteriostatic-water-30ml` were not all checked for this specific string) should be spot-checked for the same string.
- Reasoning: The site's core differentiator (per the prior full-site audit and confirmed independently here — see Verified Correct) is that its scientific and batch claims are unusually concrete and citable. A visible, unresolved editorial placeholder sitting inside that exact citation apparatus directly contradicts the "concrete and verified" positioning: it tells a careful reader (or an AI system extracting "sources") that at least one cited claim per affected product was never actually source-checked before publishing. This is a materially different, more severe defect than generic thin content — it is evidence of unedited/unreviewed content shipping to production, which is one of the QRG's explicit lowest-quality triggers when systemic ("insufficient editing" — §4.6).
- Recommendation: Description only — audit every product's "Research context" citation block for the literal string `[VERIFY DOI]` (and any other bracketed placeholder pattern) before the next deploy touching that template, and either supply the real DOI or remove the unverifiable citation rather than publish it half-finished.
- Dependencies: Independent fix; unblocks CONT-06 (unlinked citations) once verified DOIs exist to link.
- Failure check: A future crawl still returns the string `[VERIFY DOI]` (or `[VERIFY CLAIM]`, `[TODO]`, `[TBD]`, etc.) anywhere in rendered product HTML.
- Success check: Full-site grep for `[VERIFY` and common placeholder brackets across a fresh crawl of all 16 product pages returns zero hits.
- Leading indicator: None available to the owner without a fresh crawl/grep — recommend adding a pre-deploy CI grep step for bracketed placeholder tokens in the product content pipeline as the leading indicator going forward.

---

*Source: Performance (lab) agent (`_agents/performance.md`)*

### [PERF-01] LCP is "Poor" (>4.0 s) on all four audited templates
- Priority: Critical
- Category: LCP
- Evidence class: 2-PageSpeed lab
- Evidence: Lighthouse mobile LCP — Home 6.0 s (score 0.13), Product 4.7 s, Shop 5.7 s, Testing 5.0 s. All four exceed the 4.0 s "Poor" ceiling; none are even in the 2.5–4.0 s "Needs Improvement" band. `interactive` (time-to-interactive) is numerically identical to LCP on Home/Product/Shop (6.0 s / 4.8 s / 5.7 s), indicating LCP is the last major paint event gated by render-blocking resources rather than a late-injected element.
- Affected URLs: `/`, `/product/bpc157-10/`, `/shop/`, `/testing/`
- Reasoning: A single lab run cannot establish the CrUX 75th-percentile pass/fail Google actually scores, but a mobile LCP of 4.7–6.0 s under Lighthouse's standard throttling profile leaves effectively no headroom — real-world visitors on slower connections/devices will see worse. This is a first-order, whole-site condition, not a page-specific defect, so it should be prioritized above individual asset fixes.
- Recommendation: Treat LCP as the single top KPI for this audit cycle. The concrete levers are covered in PERF-02 (render-blocking CSS/JS) and PERF-03 (image weight) below — LCP will not move without addressing both, since the render-blocking chain delays when the LCP element can even begin downloading/painting, and the LCP image itself is heavier than necessary once it starts.
- Dependencies: Blocks/depends on PERF-02, PERF-03. Unblocks any future INP/CLS work being measured meaningfully (currently overshadowed by LCP failure).
- Failure check: A follow-up PSI lab run still shows LCP >4.0 s mobile on any of the four templates after PERF-02/03 are implemented.
- Success check: A follow-up PSI lab run shows LCP ≤2.5 s (ideally) or at minimum inside the 2.5–4.0 s band, on all four templates, with `interactive` no longer pinned to the same value as LCP.
- Leading indicator: Lighthouse "largest-contentful-paint" `score` field (currently 0.13 Home / ~0.2–0.3 elsewhere) trending toward 0.9+; PSI Lighthouse `performance` category score (currently 65–76) trending toward 90+.

---

*Source: Performance (lab) agent (`_agents/performance.md`)*

### [PERF-02] Render-blocking CSS/JS consumes ~50–62% of all stylesheet+script requests, adding an estimated 2.7–3.0 s to first paint on every template
- Priority: Critical
- Category: LCP / render-blocking
- Evidence class: 2-PageSpeed lab
- Evidence: `render-blocking-insight` audit — Home: 58 render-blocking resources of 93 CSS+JS requests (62%), Est. savings 2,940 ms. Product: 56 of 95 (59%), Est. savings 2,970 ms. Shop: 50 of 82 (61%), Est. savings 2,730 ms. Testing: 41 of 68 (60%), Est. savings 2,800 ms. Top individual blockers by `wastedMs`: Home — `woocommerce.css` (629 ms, 11,558 B), `underscore.min.js` (472 ms, 8,443 B), `cards.css` (315 ms, 4,983 B); Product — `jquery.min.js` (1,100 ms), `xoo-wsc-style.css` (472 ms), `jquery.blockUI.min.js` (315 ms); Shop — `style.min.css` [WordPress core] (1,297 ms), `woocommerce.css` (649 ms), `jquery.min.js` (973 ms); Testing — Google Fonts Roboto request (901 ms, see PERF-07), `style.min.css` (1,365 ms), `pepselect-coa-frontend.css` (854 ms), `frontend.min.css` (513 ms).
- Affected URLs: `/`, `/product/bpc157-10/`, `/shop/`, `/testing/`
- Reasoning: Roughly 6 in 10 CSS/JS requests on every template are marked render-blocking by Lighthouse, meaning the browser must download and parse them before it can paint. This is the direct mechanical cause of PERF-01: FCP (3.2–3.3 s, consistent across all four pages) is already late, and LCP trails a further 1.4–2.8 s behind FCP because the LCP element's own resources queue behind this blocking chain. The consistency of the FCP number (3.2–3.3 s) across four structurally different templates strongly suggests a shared render-blocking cause (theme/plugin CSS and jQuery-family scripts loaded head-blocking on every page) rather than page-specific content.
- Recommendation: Reduce the number of render-blocking stylesheets/scripts loaded in `<head>` — defer or async non-critical CSS/JS (jQuery UI, Underscore, WooCommerce/side-cart CSS not needed for above-the-fold content), and inline or preload only the CSS needed for the first viewport. Do not change WooCommerce, Elementor, or plugin core files; scope changes to enqueue/loading strategy only.
- Dependencies: Depends on identifying which of the 41–58 blocking resources are above-the-fold-critical per template (differs by template, so this needs template-by-template triage before implementation). Unblocks PERF-01.
- Failure check: A follow-up `render-blocking-insight` audit still reports >40% of CSS+JS requests as render-blocking, or `Est savings` remains >1,500 ms.
- Success check: `render-blocking-insight` estimated savings drops below ~500 ms and FCP moves below 2.0 s on all four templates.
- Leading indicator: `render-blocking-insight` `Est savings of X ms` value and `total_items` count, re-checked without a full audit re-run via `claude-seo run pagespeed_check.py <url> --psi-only`.

---

*Source: Visual / Mobile agent (`_agents/visual.md`)*

### [VIS-01] Mandatory full-viewport "Research Gate" blocks 100% of above-the-fold content on every page, on every device, before any interaction
- Priority: Critical
- Category: Above-the-fold / Intrusive interstitial
- Evidence class: [5-Crawler observation/inference]
- Evidence: Screenshots `homepage_laptop.png`, `homepage_mobile.png`, `product_laptop.png`, `product_mobile.png`, `shop_laptop.png`, `shop_mobile.png`, `testing_laptop.png`, `testing_mobile.png` are pairwise byte-identical per viewport (MD5 `965048a25ea7bc6b2b2b260cd4ffcd1b` for all four laptop captures, `715a6e30d850489fa5fdffb3edf4cc49` for all four mobile captures) — i.e. the visible viewport is the gate, not the page, on every template tested. Raw HTML confirms `<div id="psag-gate" role="dialog" aria-modal="true">` with CSS `position:fixed;inset:0;z-index:9999999;` and `html.psag-open,body.psag-open{overflow:hidden!important;}`, present in the initial server response on all four URLs. The gate requires: selecting a "Researcher type" from a dropdown, checking two checkboxes ("21 years of age", "qualified researcher... not for human or veterinary use"), then clicking "Enter Site" before any real page content becomes visible or scrollable.
- Affected URLs: `https://pepselect.com/`, `https://pepselect.com/product/bpc157-10/` (representative product template), `https://pepselect.com/shop/`, `https://pepselect.com/testing/` — mechanism is theme/plugin-level (injected via shared footer include), so it is reasonable to infer it applies sitewide to all templates, not just the four sampled.
- Reasoning: No primary H1, value proposition, hero image, navigation, or CTA is visible or reachable without first completing a 4-step form. This is the textbook definition of an intrusive interstitial from a UX standpoint, and it applies uniformly across content, commerce, and compliance pages alike — a first-time visitor arriving via a shop-page or product-page ad/search click sees the same generic gate as someone landing on the homepage, with zero page-specific context to justify continuing.
- Recommendation: Re-scope the gate to the minimum legally-necessary confirmation (e.g., a single age/RUO acknowledgment) and consider a lighter-weight, dismissible banner pattern for the "researcher type" marketing/segmentation question rather than bundling it into a mandatory full-screen wall. If the full gate must remain for compliance reasons, ensure it is visually clear on first paint (which it is) but reconsider requiring a dropdown selection (which is not an age/legal check) as a hard blocker to entry — that field could be optional or deferred to checkout. Time-box a UX review of drop-off rate at this gate specifically (see Leading indicator).
- Dependencies: Depends on legal/compliance sign-off for what the gate is required to collect (age, RUO acknowledgment) versus what is discretionary (researcher type, "remember me" duration). Unblocks: more accurate above-the-fold/CWV/CTA-visibility testing (see VIS-05), and any conversion-rate-optimization work on hero sections, which is currently invisible to first-time visitors.
- Failure check: Screenshots or CrUX/PSI field data captured with a fresh (no-cookie) session continue to show the gate as 100% of the viewport with no real content visible.
- Success check: A fresh, no-cookie screenshot of each template shows real page content (H1, nav, hero, CTA) either fully visible or partially visible behind/instead of a lighter, less blocking confirmation UI; time-to-first-real-content interaction decreases.
- Leading indicator: Gate completion/exit ratio in analytics (e.g., "Enter Site" clicks vs. "Exit" clicks vs. no interaction/bounce) tracked as a funnel step; bounce rate on landing pages compared before/after any gate redesign.

---

*Source: GEO / AI Search agent (`_agents/geo.md`)*

### [GEO-05] Zero measurable off-site brand/entity signal, confirmed against a live SERP where two direct competitors are already cited in the AI Overview
- Priority: Critical
- Category: Authority & Brand Signals
- Evidence class: 5-Crawler observation + 4-DataForSEO estimate
- Evidence:
  - Site-side (class 5): grep of homepage HTML for any social/video/wiki `href` or `sameAs` returned zero matches (see GEO-04). A prior full-site audit found the domain **absent from the Common Crawl Jan–Mar 2026 web graph entirely** (PageRank: None, Harmonic Centrality: None) — consistent with the domain being new (schema `datePublished` values start 2026-06-24) but establishing a true zero baseline for external link/mention signal.
  - Market side (class 4, DataForSEO live SERP, `research-peptides-2026-08-18.md`): a live Google US desktop SERP for "research peptides" returns only 9 organic slots, wrapped in an AI Overview that **cites two named competitor vendors directly — americanpeptides.us and rpeptide.com — alongside Stanford Medicine, WebMD, MIT Tech Review, CBC, 5 YouTube videos, and 3 Instagram posts.** Pep Select does not appear anywhere in the 9 organic results, the AI Overview, the "Things to know" cards (which cite palmettopeptides.com, pspeptides.com, validatedpeptides.com, peptidelaws.com, Holt Law), the scholarly-articles block, or the People Also Ask set.
- Affected URLs: Domain-wide.
- Reasoning: This is the single most important finding in the audit and it reframes every other one. The DataForSEO evidence proves the citation pathway is open in this exact niche — Google is actively citing vendor-authored content inside its AI Overview and consideration cards, not just NIH/Harvard/WebMD — so the ceiling is not category-level exclusion of commercial content. It is that Pep Select currently has no measurable footprint (no backlinks, no YouTube, no Reddit, no Wikipedia, no social) for any ranking or retrieval system to surface it as a candidate, regardless of how well-built its on-page content is. Per the brand-mention correlation data, YouTube presence (~0.737) and Reddit presence correlate most strongly with AI citation likelihood, both of which are entirely absent here, and domain authority (~0.266) — the one signal on-page work indirectly builds — is the weakest of the four correlates. On-page and structural work (GEO-02, GEO-03, GEO-07) can only convert into citations once there is something for an engine to have discovered and trust in the first place.
- Recommendation: Treat off-site presence-building as the primary GEO lever, not a secondary one: a YouTube presence (even unboxing/lab-process video content showing the COA process), a maintained Reddit presence in relevant research-chemical communities, and a Wikipedia-adjacent or industry-directory presence would each move the needle more than further on-page iteration at this stage. The COA Quality Archive itself (batch-level `Dataset` + `DataDownload` schema, genuinely rare in this vertical per the DataForSEO "Things to know" evidence) is the strongest available linkable/citable asset and should anchor outreach and content syndication.
- Dependencies: Should follow content-depth fixes (GEO-03, GEO-06) — thin pages and an anonymous entity convert outreach and citation attempts poorly. Unblocks nothing technical; this is the strategic constraint everything else operates under.
- Failure check: If YouTube/Reddit/off-site presence is built and 3–6 months pass with no change in AI Overview citation or organic visibility for "research peptides"-family queries, re-verify against a fresh DataForSEO SERP pull — the competitive set may have shifted, or the content itself (not just its distribution) may be the remaining gap.
- Success check: Pep Select appears in organic results, AI Overview citations, or "Things to know" cards on a repeat DataForSEO SERP pull for "research peptides" or its related-searches variants (e.g., "best place to buy research peptides online").
- Leading indicator: Domain appears with a non-null PageRank in the next Common Crawl release; Search Console → Links → referring domains rises above zero; any DataForSEO re-pull of this query shows Pep Select in organic, AIO, or PAA-adjacent placements.

---

---

*Source: SXO agent (`_agents/sxo.md`)*

### [SXO-01] Every organic-entry page renders only the research gate at first paint — trust content is deferred, not delivered
- Priority: Critical
- Category: Search Experience — First Impression / Journey Friction
- Evidence class: 5 (Crawler observation/inference — rendered screenshots)
- Evidence: `_agents/screenshots/homepage_laptop.png`, `shop_laptop.png`, `product_laptop.png`, and `testing_laptop.png` are **pixel-identical** — all four show only the "Research Access Verification" modal (researcher-type dropdown, two checkboxes, "ENTER SITE" button, "Not a researcher? Exit"). The gate is exempted from Google's own first paint (crawlable HTML sits underneath, per the sitewide audit's G-02 finding), but it is not exempted from what a human clicking a Google result actually sees.
- Affected URLs: `/`, `/shop/`, `/product/glp3-r10/`, `/testing/` (and, per the shared template, all 43 crawled URLs).
- Reasoning: The SERP consensus (SXO Snapshot above) is that winning titles put trust proof — purity %, COA, US-made — directly in front of the searcher before the click. A visitor who clicked specifically because a title promised "99%+ purity" or "COA" lands on a page that shows none of that; it shows a compliance form instead. That is a real journey discontinuity for personas 1–3 (first-time trust comparer, price-comparison shopper, skeptical evidence-seeker) even though it is not an indexing problem. The gate is legally necessary (age/researcher attestation) — this finding is about its *sequencing and weight* relative to the promise in the SERP snippet, not about its existence.
- Recommendation: Describe only, and treat as compliance-sensitive (do not alter the attestation logic without explicit approval): evaluate whether a lightweight trust strip (e.g., one line — "Third-party tested · Batch-matched COAs" — plus a thumbnail of a real COA) could sit above or beside the gate without weakening the attestation requirement, and whether the "Researcher Type" dropdown could be deferred to checkout rather than gating page view. Any change must preserve the existing legal/compliance intent exactly.
- Dependencies: Distinct from G-02 (which covers indexability and is correctly a non-issue) and T-01 (broken Terms link inside the gate, already Critical in the main audit). Unblocks nothing directly but is the umbrella context for SXO-04 and SXO-05, which are downstream of "what a visitor sees once past the gate."
- Failure check: If gate presentation changes and organic bounce/exit rate is unchanged, first-paint framing was not the binding constraint — the compliance requirement itself may simply cost more friction than any presentation fix can recover, and the finding should be downgraded on re-audit.
- Success check: A repeat measurement (screenshot capture from a fresh, cookie-less session) shows trust signals visible before or alongside the gate rather than fully behind it; "Remember me" opt-in rate rises, indicating lower first-visit friction perception.
- Leading indicator: If GA4/Search Console access is configured, average engagement time and bounce rate for sessions landing on non-homepage organic entries (`/shop/`, `/product/*`, `/testing/*`), tracked before/after any change.

---

*Source: E-commerce agent (`_agents/ecommerce.md`)*

### [ECOM-01] Zero review capture or aggregateRating anywhere in the catalog
- Priority: Critical
- Category: Review capture / trust signals
- Evidence class: 5-Crawler observation
- Evidence: Parsed Product JSON-LD on `bpc157-10`, `ghk-cu`, `ss-31`, `tb500-10`, `nad`, `bacteriostatic-water-30ml`, `motsc-10`, `glp1-s10`, `glutathione`, `glp2-t20`, `glp3-r10/20/30`, `tesa-10`, `pt-141` (all 15 products) — none contain `aggregateRating` or `review`. HTML search on `ghk-cu` for `star-rating`, `woocommerce-Reviews`, `comment-form`, `judge.me`, `yotpo`, `stamped` all returned 0 matches. The only "review" occurrences on-page are Yoast's templated meta description ("Review GHK-CU 50MG from Pep Select...") and a COA-carousel caption ("Review the latest independently tested batch records") — neither is a customer review mechanism.
- Affected URLs: All 15 `/product/*/` pages
- Reasoning: Google's Product rich-result eligibility and the SERP's own trust signals in this vertical (see ECOM-08) reward `aggregateRating`/`review` markup. A store built around lab-verified purity (the COA archive) has an unusually strong case for genuine reviews but currently surfaces none — a visible gap versus the "third-party tested" trust language that wins in this SERP.
- Recommendation: Confirm whether WooCommerce native reviews are disabled in wp-admin → Settings → Products, or a review app was never installed; if reviews exist in the backend but are suppressed from the theme, describe re-enabling display; if none exist, describe standing up a review-collection mechanism (native WooCommerce reviews or a review app) and emitting `aggregateRating`/`review` in the existing Product JSON-LD once real reviews accumulate. Do not fabricate reviews or ratings.
- Dependencies: Depends on business decision to collect reviews (compliance review needed given RUO/YMYL-adjacent framing — avoid review copy that implies human use). Unblocks Product rich-result star ratings.
- Failure check: `aggregateRating`/`review` still absent from Product JSON-LD after a review app or native reviews are enabled; or reviews are collected but never wired into schema.
- Success check: At least one product page emits valid `aggregateRating` with `ratingValue`, `reviewCount`, and it validates against Google's Merchant/Product schema requirements.
- Leading indicator: Presence of a visible review count/star widget on any product page on a future crawl; review count trending up in wp-admin.

---

*Source: E-commerce agent (`_agents/ecommerce.md`)*

### [ECOM-02] COA testing pages missing for 6 of 15 catalog products, including two headline compounds
- Priority: Critical
- Category: Trust signals / COA linkage
- Evidence class: 5-Crawler observation
- Evidence: `ps_compound-sitemap.xml` lists exactly 8 compound hub pages under `/testing/`: `retatrutide-30mg`, `retatrutide-20mg`, `ghk-cu-50-mg`, `tesamorelin-10-mg`, `nad-500-mg`, `pt-141-10-mg`, `tb-500-10-mg`, `retatrutide-10mg`. Cross-referencing against the 15-product catalog, **BPC-157, SS-31, MOTS-C, GLP-1 S10 (semaglutide), Glutathione, and GLP-2 T20** have no corresponding `/testing/` page. Confirmed on-page: `bpc157-10` and `ss-31` product pages contain only generic `<a href="https://pepselect.com/testing/">COAs</a>` / "Certificate of Analysis" links with no compound-specific "Current Batch" deep link and no inline purity percentage, whereas `ghk-cu`, `tb500-10`, and `nad` each show a "Current Batch" block linking directly to their batch-level COA page (e.g. `https://pepselect.com/testing/tb-500-10-mg/tb10-6926/`) with an inline purity value ("99.76%", "99.85%", "99.87%"). The `/testing/` hub itself (rendered, H2/H3 audit) lists only GHK-CU, NAD+, PT-141, Retatrutide (×3), TB-500, and Tesamorelin — confirming the gap sitewide, not just a linking bug on two pages.
- Affected URLs: `https://pepselect.com/product/bpc157-10/`, `https://pepselect.com/product/ss-31/`, `https://pepselect.com/product/motsc-10/`, `https://pepselect.com/product/glp1-s10/`, `https://pepselect.com/product/glutathione/`, `https://pepselect.com/product/glp2-t20/`, `https://pepselect.com/testing/`
- Reasoning: The 2026-08-18 site-wide audit (`00-audit-context.md`) names the COA Quality Archive as the site's rarest competitive asset. That asset only functions as a trust signal on the ~9 products it actually covers. For BPC-157 and SS-31 in particular — both high-recognition, frequently searched compound names in this vertical — a visitor or crawler following the product's own "Certificate of Analysis" link lands on a hub that does not mention that compound at all, which reads as a broken trust promise rather than a working one.
- Recommendation: Describe publishing (or backdating, if lab data already exists internally) `/testing/` batch pages for BPC-157, SS-31, MOTS-C, GLP-1 S10, Glutathione, and GLP-2 T20, mirroring the existing `Dataset`/`DataDownload` schema pattern used for the other 9. For any compound where current-lot COA data does not yet exist, describe either delaying the "Certificate of Analysis" link/claim on that specific product page or clearly labeling it "testing in progress" rather than linking to a hub that omits the compound silently.
- Dependencies: Depends on lab/COA data availability for the missing compounds (owner-side, not a code change). Unblocks ECOM-04's fix (linking `/testing/` back to products) being meaningful for the full catalog.
- Failure check: Product pages for the 6 compounds keep linking only to the generic `/testing/` index with no compound-specific batch data six months from now.
- Success check: Each of the 6 compounds has a `/testing/[slug]/` hub page and at least one `ps_coa_test` batch page, and its product page shows the same "Current Batch" + purity-percentage block already used for GHK-CU/TB-500/NAD+.
- Leading indicator: `ps_compound-sitemap.xml` entry count rising from 8 toward 15 (currently trackable via `sitemap_index.xml` without re-running the audit).

---

# HIGH FINDINGS

---

*Source: Google APIs (GSC / PageSpeed / CrUX) agent (`_agents/google.md`)*

### [GOOG-02] /testing/ hub page is completely unknown to Google
- Priority: High
- Category: Indexation
- Evidence class: 1-GSC verified
- Evidence: URL Inspection API for https://pepselect.com/testing/ — coverage_state: "URL is unknown to Google"; robots_txt_state, indexing_state, page_fetch_state all `UNSPECIFIED`; no last_crawl_time; no referring URLs.
- Affected URLs: https://pepselect.com/testing/
- Reasoning: "Unknown to Google" is a stronger negative signal than "discovered but not indexed" — it means Google has never even encountered this URL through crawling, sitemaps, or links. Yet individual COA test sub-pages under /testing/ (e.g., /testing/retatrutide-30mg/, /testing/tb-500-10-mg/tb10-6926/) DO have GSC impression data (section 1c), meaning those sub-pages are known/indexed while their parent hub is not. This points to a missing or broken internal link from the site's navigation/sitemap to the /testing/ index page itself, and confirms /testing/ is absent from the sitemap_index.xml sitemaps (page-sitemap.xml, ps_coa_test-sitemap.xml, product-sitemap.xml do not list it).
- Recommendation: Add https://pepselect.com/testing/ to the XML sitemap (likely page-sitemap.xml) and ensure it is linked from primary site navigation or footer so Googlebot can discover it through normal crawling.
- Dependencies: Independent of GOOG-01; complements it since both stem from discoverability gaps.
- Failure check: Re-inspection still shows "URL is unknown to Google" after the page is added to a sitemap and internally linked.
- Success check: URL Inspection verdict changes to at least "Discovered" or "Crawled," with a populated last_crawl_time.
- Leading indicator: Sitemap contents count for page-sitemap.xml increasing by 1 URL; GSC Pages report showing /testing/ appear in "Discovered" or "Indexed" buckets.

---

*Source: Google APIs (GSC / PageSpeed / CrUX) agent (`_agents/google.md`)*

### [GOOG-04] Homepage mobile performance is Poor on LCP, driven by render-blocking resources and an oversized hero image
- Priority: High
- Category: Core Web Vitals (Lab)
- Evidence class: 2-PageSpeed lab
- Evidence: Mobile PSI performance score 62/100. LCP = 8,864 ms ("8.9 s", Poor, score 0.01) vs. desktop LCP = 1,381 ms ("1.4 s", Good, score 0.84) on the same URL. FCP mobile 4,509 ms vs desktop 728 ms. TTI mobile 8,879 ms vs desktop 1,429 ms. TBT and CLS are both Good on mobile (30 ms / 0). Failed audits: render-blocking-insight (est. savings 2,530 ms), image-delivery-insight (est. savings 408 KiB), unused-javascript (est. savings 105 KiB). Largest network payload item: hero image `PS-laying_fam-768x434.png` at 367,813 bytes total with 333,719 bytes estimated wasted.
- Affected URLs: https://pepselect.com/ (mobile)
- Reasoning: The mobile/desktop LCP gap (8.9 s vs 1.4 s, a ~6.4x difference) is far larger than typical device-capability differences, indicating render-blocking CSS/JS and an unoptimized above-the-fold hero image are specifically hurting the mobile experience, not raw page weight (mobile and desktop total byte weight are nearly identical at ~1,667-1,669 KiB).
- Recommendation: Compress/resize the hero image and other above-the-fold images to responsive srcset sizes; defer or inline critical render-blocking CSS/JS (the render-blocking-insight audit lists specific plugin CSS/JS assets); ensure fetchpriority=high and no lazy-loading on the LCP element.
- Dependencies: Independent of GSC indexation findings; improving this reduces bounce risk once organic traffic does arrive (post GOOG-01/GOOG-02 fixes).
- Failure check: Repeat mobile PSI run after remediation still shows LCP > 4,000 ms.
- Success check: Repeat mobile PSI run shows LCP ≤ 2,500 ms (Good) or at minimum ≤ 4,000 ms (Needs Improvement) with performance score materially above 62.
- Leading indicator: PSI mobile performance score trending upward on ad hoc re-checks; CrUX field LCP percentile (once eligible — see GOOG-09) trending into Good range.

---

*Source: Google APIs (GSC / PageSpeed / CrUX) agent (`_agents/google.md`)*

### [GOOG-05] Shop category page mobile LCP is Poor (7.8s) with heavy render-blocking overhead
- Priority: High
- Category: Core Web Vitals (Lab)
- Evidence class: 2-PageSpeed lab
- Evidence: Mobile PSI performance score 67/100. LCP = 7,808 ms ("7.8 s", Poor, score 0.03). FCP 3,757 ms, TTI 7,898 ms, TBT 52 ms (Good), CLS 0.014 (Good). Desktop on the same URL: performance 95/100, LCP 1,221 ms ("1.2 s", Good). Failed audit render-blocking-insight: est. savings 2,950 ms (largest of the three pages tested). lcp-discovery-insight also failed (LCP element not discoverable early in HTML / possibly lazy-loaded).
- Affected URLs: https://pepselect.com/shop/ (mobile)
- Reasoning: The lcp-discovery-insight failure combined with the largest render-blocking savings estimate (2,950 ms) of any page tested suggests the category grid's LCP element (likely a product card image) is being discovered late by the browser, compounding the render-blocking delay.
- Recommendation: Ensure the LCP candidate image on /shop/ (typically the first visible product card) is not lazy-loaded and is preloaded or high-fetchpriority; apply the same render-blocking CSS/JS deferral strategy recommended in GOOG-04.
- Dependencies: Shares root causes with GOOG-04 and GOOG-06 (same theme/plugin stack); a shared fix (critical CSS extraction, script deferral) would likely resolve all three simultaneously.
- Failure check: Repeat mobile PSI run still shows LCP > 4,000 ms after remediation.
- Success check: Repeat mobile PSI run shows LCP ≤ 4,000 ms, ideally ≤ 2,500 ms.
- Leading indicator: PSI mobile performance score for /shop/ trending upward on ad hoc checks.

---

*Source: DataForSEO agent (`_agents/dataforseo.md`)*

### [DFS-03] pepselect.com is absent from both target commercial SERPs (100-deep crawls)
- Priority: High
- Category: SERP Competitive Position
- Evidence class: [4-DataForSEO estimate]
- Evidence:
  - **"buy research peptides"** (US, desktop, depth 100, 2026-08-18): Google returned only **9 organic results** on a feature-dense single page. pepselect.com: **absent**. Organic order: 1 phoenixpeptide.com, 2 americanpeptides.us, 3 biosynth.com, 4 genscript.com, 5 pspeptides.com, 6 redrockpeptides.com, 7 shop.bachem.com, 8 mybiosource.com, 9 anaspec.com.
  - **"bpc-157 for sale"** (US, desktop, depth 100, 2026-08-18): **9 organic results**, AI Overview in position 1. pepselect.com: **absent**. Organic order: 1 amazon.com, 2 prohealth.com, 3 riteaid.com, 4 tiktok.com (video), 5 xenopeptides.com, 6 biolongevitylabs.com, 7 purehydrationspa.com, 8 veniceapothecary.net, 9 youtube.com (video).
  - Pre-paid "research peptides" SERP (same date): pepselect.com also absent from all 9 organic slots.
- Affected URLs: https://pepselect.com/ (homepage + category pages that should target these terms)
- Reasoning: Consistent with DFS-01. Notably, Google serves only ~9 organic slots per page-one on these queries even at depth 100 — page one is the whole game; there is no "rank on page 3 and climb" runway visible in the returned data. Every organic slot is contested by a vendor with explicit trust language (COA, third-party tested, 99%+ purity, US-made) or a major retail/medical brand.
- Recommendation: Target one keyword-to-URL map: homepage → "research peptides" trust positioning; a dedicated /bpc-157 product page titled in the proven winning format ("BPC-157 For Sale | 5mg & 10mg, COA Verified" — the exact format xenopeptides ranks #5 with); category page → "buy research peptides". Lead titles with verifiable purity/testing claims.
- Dependencies: Depends on DFS-01 (indexation) and DFS-02 (minimum authority); unblocks DFS-04 keyword targeting.
- Failure check: After 90 days indexed, still absent from top 100 on all three tracked queries.
- Success check: pepselect.com appears anywhere in the returned SERP items for at least one of the three queries.
- Leading indicator: GSC impressions for queries containing "peptide" (watchable weekly, free).

---

---

*Source: DataForSEO agent (`_agents/dataforseo.md`)*

### [DFS-04] Keyword difficulty is unusually low — three target terms score KD 0
- Priority: High
- Category: Keyword Strategy
- Evidence class: [4-DataForSEO estimate]
- Evidence (dataforseo_labs_bulk_keyword_difficulty, US/en, 2026-08-18):

  | Keyword | KD (0–100) |
  |---|---|
  | bpc 157 for sale | **0** |
  | tirzepatide for sale | **0** |
  | retatrutide for sale | **0** |
  | semaglutide for sale | 16 |
  | best place to buy research peptides | 20 |
  | research peptides for sale | 20 |
  | research peptides | 22 |
  | buy research peptides | 23 |
  | peptides for sale | 34 |
  | buy peptides online | 40 |

- Affected URLs: Future/planned category and product URLs (e.g., /bpc-157, /tirzepatide, /retatrutide, /semaglutide, /research-peptides)
- Reasoning: KD 0 on the three compound-specific "for sale" terms means the current top-10 for those queries has very weak aggregate link authority — these are winnable by a new domain with a handful of clean links and a well-structured product page. The generic head terms ("buy peptides online" 40, "peptides for sale" 34) are 2x+ harder. First-principles sequencing: win compound-level pages first, let them accumulate authority, then the head terms become reachable. Caveat: KD 0 may partly reflect thin DataForSEO data on newer compound queries (retatrutide is recent), and KD does not model the compliance/YMYL filtering Google applies to this niche.
- Recommendation: Build compound-level product/landing pages for BPC-157, tirzepatide, retatrutide, and semaglutide FIRST (KD 0–16), each with COA display, price, and RUO framing. Defer head-term chasing ("buy peptides online") until the compound pages rank.
- Dependencies: Depends on DFS-01/DFS-02; unblocks measurable early wins that fund the harder terms.
- Failure check: Compound pages live+indexed 90 days with zero top-100 appearance on their KD-0 target term.
- Success check: Top-20 position on at least one KD ≤16 term within 90 days of page launch.
- Leading indicator: GSC average position for the four compound query clusters.

---

---

*Source: DataForSEO agent (`_agents/dataforseo.md`)*

### [DFS-05] Five vendors recur across the commercial SERPs and define the benchmark
- Priority: High
- Category: Competitive Landscape
- Evidence class: [4-DataForSEO estimate]
- Evidence — domain presence across the three SERPs (RP = "research peptides" [pre-paid file], BRP = "buy research peptides", BPC = "bpc-157 for sale"):

  | Domain | RP | BRP | BPC | Notes |
  |---|---|---|---|---|
  | phoenixpeptide.com | #1 | #1 + AIO cite | — | Sitelinks both SERPs; ISO 9001 messaging |
  | americanpeptides.us | #2 + AIO cite | #2 + AIO cite | — | "Third-party tested", COA per lot |
  | genscript.com | #7 | #4 + AIO cite ×2 | — | B2B/scientific framing |
  | redrockpeptides.com | #5 | #6 + AIO cite | — | Merchant Center srsltid + price range |
  | pspeptides.com | #8 + "Things to know" cite | #5 | — | "US Made, 99%+ Purity" title |
  | rpeptide.com | #4 + AIO cite ×2 | AIO cite only | — | Recombinant/research positioning |
  | youtube.com | #6 (video) | AIO cite ×2 | #9 + AIO cite ×2 | Only domain present in all three SERPs |
  | xenopeptides.com | — | — | #5 + AIO cite | Product page "For Sale \| COA Verified" |
  | biolongevitylabs.com | — | — | #6 | "99% Purity USA-Made" |
  | amazon.com / prohealth.com / riteaid.com | — | — | #1 / #2 / #3 | Supplement/retail tier, not research vendors |

  Recurring research-vendor set (2 of 2 generic commercial SERPs): **phoenixpeptide.com, americanpeptides.us, genscript.com, redrockpeptides.com, pspeptides.com** (plus rpeptide.com counting AIO citations).
- Affected URLs: N/A (competitive intelligence)
- Reasoning: The same five vendors hold organic + AI Overview real estate on both generic commercial queries — Google has a settled "trusted vendor set" for this niche. The compound-level SERP (bpc-157) is a different battlefield: dominated by supplements (Amazon, ProHealth), retail health content (Rite Aid), and clinics — only two pure research vendors (xenopeptides, biolongevitylabs) made page one, which explains the KD 0 score and confirms compound pages as the entry point.
- Recommendation: Benchmark on-page patterns against the five recurring vendors (title formulas, COA presentation, trust badges) rather than the retail tier. Track this fixed competitor set monthly.
- Dependencies: Feeds content specs for DFS-03/DFS-04; no blockers.
- Failure check: Competitor set churns >50% next quarter (would mean the benchmark is unstable and analysis must be redone).
- Success check: pepselect.com content matches or exceeds the recurring vendors on the observable trust elements (COA links, purity %, testing lab named).
- Leading indicator: Position of the 5 recurring vendors on tracked terms (any SERP re-check shows them; stability = benchmark validity).

---

---

*Source: DataForSEO agent (`_agents/dataforseo.md`)*

### [DFS-06] AI Overviews sit at/near position 1 on all three SERPs and cite vendor content directly
- Priority: High
- Category: AI Visibility / AEO
- Evidence class: [4-DataForSEO estimate]
- Evidence:
  - "buy research peptides" AIO cites: phoenixpeptide.com (homepage, "ISO 9001 Certified... USA-Manufactured"), verifiedpeptides.com (homepage, "500+ Published Certificates of Analysis... Trusted by experts since 2020"), biosynth.com, rpeptide.com, genscript.com ×2, americanpeptides.us (homepage with product/price listings), redrockpeptides.com, YouTube ×2. AIO explicitly segments "Academic and Institutional Suppliers" vs "Online Research Vendors".
  - "bpc-157 for sale" AIO (position 1) cites: xenopeptides.com **product page** ("BPC-157 Peptide For Sale | 5mg & 10mg, COA Verified" — with tiered pricing pulled into the citation), desertmobilemedical.com product page (with $399/mo pricing), riteaid.com guide, superpower.com guide, prohealth.com product page, amazon.com search pages ×2, veniceapothecary.net blog, plus clinic blogs (pranawellnessclinic, drrogerscenters, tyranceorthopedics) and YouTube ×2.
  - "research peptides" AIO (pre-paid file): cites vendors americanpeptides.us and rpeptide.com; "Things to know" cards cite vendor blogs (palmettopeptides ×3, pspeptides, validatedpeptides, peptidelaws).
- Affected URLs: Future pepselect.com homepage, product pages, and educational/COA pages
- Reasoning: Content types that earn AIO citations in this niche, observed verbatim: (1) vendor homepages whose meta/on-page text states certifications and testing counts ("ISO 9001", "500+ Published COAs"); (2) product pages with compound + dosage + "COA Verified" in the title and visible tiered pricing; (3) batch-verification/COA library pages; (4) educational guides on legality/safety (Rite Aid, Superpower); (5) expert video. AIO extraction favors pages where the trust claim is machine-readable in the first visible text. A small vendor (verifiedpeptides.com — not even in the organic 9) got AIO-cited purely on its published-COA positioning: AI visibility here does not require organic rank.
- Recommendation: Publish a public, crawlable COA/batch-verification library page and state quantified trust claims in homepage/product-page opening text and titles (e.g., "N published COAs, third-party HPLC tested"). Structure product pages as compound + strengths + "COA Verified" titles with visible pricing tiers.
- Dependencies: Depends on DFS-01 (must be crawlable); independent of link authority (DFS-02) per the verifiedpeptides.com observation.
- Failure check: 6 months post-launch, zero AIO/PAA citations for pepselect.com on any tracked query (spot-checkable with a single $0.002 SERP call).
- Success check: pepselect.com appears as an ai_overview_reference on any peptide query.
- Leading indicator: Referral/agent traffic from google.com AIO clicks and ChatGPT/Perplexity user-agents in server logs (free, continuous).

---

---

*Source: Technical SEO agent (`_agents/technical.md`)*

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

---

*Source: Technical SEO agent (`_agents/technical.md`)*

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

---

*Source: Content & E-E-A-T agent (`_agents/content.md`)*

### [CONT-03] Sitewide boilerplate quantified at 619–628 identical words, 38%–90% of on-page text depending on template
- Priority: High
- Category: Unique Content Ratio
- Evidence class: 5-Crawler observation/inference
- Evidence: Exact-line-match diff across the stripped text of 16 sampled pages (home, 3 products, shop, testing hub, testing compound page, COA batch page, contact, FAQ, privacy policy, terms, RUO disclaimer, refund/shipping policy, military discount, track-order) found **57 lines of text byte-identical across all 16 pages, totaling 619–628 words per page** (word count varies slightly because two of the identical-line-sets differ by one word depending on which FDA-disclaimer variant a given page uses — see CONT-07). As a share of each page's total stripped text: `track_order` 90.4% (624/690 words), `military_discount` 86.3% (624/723), `contact` 87.0% (625/718), `testing_reta30` 68.6% (624/910), `product_bpc157` 63.8% (625/980), `testing_hub` 62.0% (624/1006), `product_tb500` 61.2% (625/1022), `home` 59.8% (624/1043), `ruo_disclaimer` 57.4% (626/1090), `product_nad` 58.6% (625/1066), `shop` 55.9% (625/1118), `coa_reta30_batch` 51.5% (627/1217), `refund_shipping` 50.6% (628/1242), `privacy_policy` 48.7% (626/1286), `faq` 44.9% (625/1391), `terms_conditions` 38.0% (624/1644). The 57 identical lines include the full age/research-gate attestation script (12 separate checkbox/paragraph statements), both FDA disclaimer paragraphs (96–98 words each), the footer copyright lines, and ~30 short nav-label lines (`Compounds`, `FAQ`, `My Account`, etc.).
- Affected URLs: All 43 sitemap URLs share the same theme header/footer/gate template, so this ratio is structural, not page-specific; 16 were directly measured.
- Reasoning: This independently reproduces and refines the prior audit's "560 words of identical boilerplate on 43/43 pages" finding — the exact word count differs (619–628 vs. 560) because of a different extraction method (regex line-diff here vs. whatever tool produced the prior figure), but both measurements point at the same structural fact: a large, fixed block of legal/nav copy is repeated verbatim across every template. On short pages (contact, military-discount, track-your-order), this pushes the boilerplate-to-unique ratio above 85%, meaning a crawler or LLM extracting "what is this page about" gets mostly the same repeated legal/nav text back, not page-specific signal — a direct input to Google's scaled-content/duplicate-content quality signals and to AI systems' per-URL content differentiation.
- Recommendation: Description only — collapse the two near-duplicate FDA disclaimer paragraphs into one canonical version (see CONT-07) and consider moving the full age-gate attestation text out of the crawlable DOM into a client-rendered-only modal (it is a consent UI, not indexable content), which alone would remove roughly 200 of the 624 boilerplate words from every page's crawlable text.
- Dependencies: Overlaps with CONT-07 (duplicate disclaimers) and the prior audit's C-01; implementation should be sequenced with G-02 (research-gate markup) from the prior technical/GEO passes since both touch the same gate component.
- Failure check: The FDA-disclaimer consolidation removes only one of the two paragraphs from some templates but not others, creating a *new* inconsistency between pages.
- Success check: Re-running the same exact-line-match diff after the change shows boilerplate word count per page reduced by roughly 150–200 words with no page losing legally required disclosures.
- Leading indicator: None visible without re-crawling; recommend the site owner track total unique-vs-boilerplate word ratio as a fixed check in future content QA rather than relying on the next full audit.

---

*Source: Content & E-E-A-T agent (`_agents/content.md`)*

### [CONT-04] Product-page unique content is thin — 294 to 441 unique words across 10 of 16 SKUs sampled
- Priority: High
- Category: Thin Content / Content Minimums
- Evidence class: 5-Crawler observation/inference
- Evidence: Subtracting the 625-word sitewide boilerplate block (CONT-03) from each product page's total stripped-text word count yields: `product_pt141` 294, `product_glutathione` 319, `product_bactwater` 332, `product_ghkcu` 346, `product_bpc157` 355, `product_ss31` 371, `product_glp1s10` 377, `product_tb500` 397, `product_motsc10` 431, `product_nad` 441. Mean ≈ 366 words. This "unique" figure still includes templated modal copy that repeats *within* a page (the "Notify me" out-of-stock modal text, duplicated once per cross-sell item shown — see CONT-08), so the truly bespoke editorial content per product (description + research-context bullet list + intended-use paragraph) is smaller still; on `product_bpc157` that core block is approximately 95 words (Description: 30 words; Research context bullets: 20 words; Intended use: 45 words).
- Affected URLs: All 10 sampled product pages: `/product/bpc157-10/`, `/product/tb500-10/`, `/product/nad/`, `/product/ss-31/`, `/product/motsc-10/`, `/product/glp1-s10/`, `/product/pt-141/`, `/product/glutathione/`, `/product/bacteriostatic-water-30ml/`, `/product/ghk-cu/`. The remaining 6 product URLs (`glp2-t20`, `glp3-r10`, `glp3-r20`, `glp3-r30`, `tesa-10`) were not word-counted in this pass but share the identical template, so the same range is expected.
- Reasoning: This independently confirms the direction of the prior audit's C-02 finding ("all 15 product pages below 400-word gate, 196–324 unique words"); my own measurement lands somewhat higher (294–441, mean 366) because it retains the cross-sell/modal text the prior tool may have excluded — both readings agree that unique, product-specific editorial content sits below or near this skill's 400-word product-page floor (300+ general / 400+ for complex products), and well below this skill's 800-word service-page floor if these SKUs were ever treated as such. This is a coverage-floor observation, not a ranking-factor claim — Google does not use word count directly — but it does indicate that the *topical* coverage per product (mechanism, dosing-format specifics beyond a purity number, comparison to related compounds, storage/handling detail beyond the one dilution-notice paragraph) is currently minimal.
- Recommendation: Description only — expand the "Description" and "Research context" sections per product with additional genuinely product-specific detail (e.g., typical reconstitution ratios researchers use, molecular weight, solubility notes, a short comparison to the 1–2 most closely related compounds in the catalog) rather than adding filler; the existing citation-and-CAS-number pattern is a good template to extend, not replace.
- Dependencies: Should be sequenced after CONT-02 (fix the placeholder citations first, since expanding a citation list before fixing the ones already broken compounds the problem).
- Failure check: Word count increases but density/quality regresses (content_quality.py filler_score rises above 0, currently the site's strongest content-quality signal — see Verified Correct).
- Success check: Re-measured unique word count per product page (using the same boilerplate-subtraction method) exceeds 400 words while `content_quality.py` filler_score remains 0 and ai_pattern_score remains 0.
- Leading indicator: None available without re-crawl; consider a lightweight internal word-count check per product template as part of the content pipeline.

---

*Source: Content & E-E-A-T agent (`_agents/content.md`)*

### [CONT-05] Zero blog, guide, or informational content exists anywhere on the site
- Priority: High
- Category: Topical Coverage / AI Search Readiness
- Evidence class: 5-Crawler observation/inference
- Evidence: Full sitemap enumeration returns exactly 4 child sitemaps and 43 URLs total: `page-sitemap.xml` (9 static pages: home, contact, faq, military-discount, privacy-policy, refund-shipping-policy, ruo-disclaimer, terms-conditions, track-your-order), `ps_compound-sitemap.xml` (9: testing hub + 8 per-compound archive pages), `ps_coa_test-sitemap.xml` (9 per-batch COA pages), `product-sitemap.xml` (16: shop + 15 products). No `/blog/`, `/guides/`, `/resources/`, or similarly named sitemap or URL exists in this list, and `robots.txt` declares only `sitemap_index.xml`. FAQ content (763 words stripped) is the closest thing to informational content, but it is transactional/policy FAQ (shipping, payment, account) rather than topical/educational content about the compounds themselves.
- Affected URLs: Site-wide (absence, not a specific URL).
- Reasoning: This reaffirms the prior audit's C-06/G-04 findings with a fresh, independent sitemap re-enumeration on the audit date rather than carrying the claim forward unverified. For a research-compound vertical where the highest-intent informational queries (mechanism explanations, reconstitution/storage best practices, how COA/purity testing works) sit upstream of purchase intent, having zero content addressing them means the site can only be surfaced by AI answer engines and Google for direct navigational/product queries, not for the broader research questions that would otherwise route traffic and citations toward it — despite the site holding genuinely citable primary data (the COA archive) that such content could responsibly link to.
- Recommendation: Description only — this is a content-strategy gap, not a technical one; any informational content added would need to stay within the RUO/no-human-use framing already used consistently elsewhere on the site (per the RUO disclaimer and FDA disclaimer language already present) rather than introducing new unsupported claims.
- Dependencies: Should be planned alongside CONT-08 (no author/expertise signal), since informational content without a credentialed voice behind it would not close the Expertise gap on its own.
- Failure check: New content is added but duplicates or paraphrases the existing per-product "Description" text rather than covering genuinely new topical ground.
- Success check: A future sitemap enumeration shows a new content type (e.g., `/guides/` or `/resources/`) with word counts meeting the 1,500-word blog-post floor and citing/linking into the existing COA archive.
- Leading indicator: Sitemap child-count (currently 4) increasing, and internal links from existing product/testing pages into any new content hub appearing in a future crawl.

---

*Source: Schema / Structured Data agent (`_agents/schema.md`)*

### [SCHEMA-01] Product pages emit two disconnected JSON-LD graphs with inconsistent @context
- Priority: High
- Category: Graph integrity / emitter conflict
- Evidence class: [5-Crawler observation/inference]
- Evidence: Each of the 3 product pages returns exactly 2 separate `<script type="application/ld+json">` blocks (`grep -c 'application/ld+json'` = 2 on `/product/bpc157-10/`). Block 1 (Yoast SEO): `"@context": "https://schema.org"`. Block 2 (WooCommerce core Product schema): `"@context": "https://schema.org/"` (trailing slash) with its own top-level `@type: "Product"` — not nested inside Yoast's `@graph`.
- Affected URLs: `/product/bpc157-10/`, `/product/ghk-cu/`, `/product/ss-31/` (pattern applies to all products site-wide via WooCommerce template)
- Reasoning: Two independent plugins (Yoast SEO and WooCommerce core structured data) each emit their own `<script>` tag instead of one merged `@graph`. This is common in WooCommerce+Yoast stacks and is generally tolerated by Google's parser (each block is validated independently), but the mismatched `@context` string (`https://schema.org` vs `https://schema.org/`) and the lack of a single graph increases the chance of future entity duplication (see SCHEMA-02, SCHEMA-03) and makes maintenance/debugging harder.
- Recommendation: Standardize on a single `@context` value (`https://schema.org`, no trailing slash) for both emitters. If merging emitters into one `@graph` is not feasible without custom development, at minimum normalize the WooCommerce block's `@context` to match Yoast's, and link the two graphs via shared `@id`s (see SCHEMA-02) rather than leaving them as fully independent documents. Description only — no code should be deployed by this audit.
- Dependencies: Unblocks SCHEMA-02 and SCHEMA-03 (entity de-duplication depends on having a consistent context/@id strategy first).
- Failure check: Google Rich Results Test / Search Console still reports both blocks as independently valid but continues to show them as unrelated entities in the "detected structured data" panel.
- Success check: Rich Results Test shows one coherent set of entities per URL with matching `@context`, and the Offer's seller/organization resolves to the same `@id` as the page's Organization node.
- Leading indicator: In Search Console → Enhancements, Product and Merchant listing item counts remain stable or improve after normalization (a drop would indicate a hidden dependency on the split-script structure).

---

*Source: Performance (lab) agent (`_agents/performance.md`)*

### [PERF-03] Hero and content images are the single largest identified byte-waste source, led by an unoptimized homepage hero PNG
- Priority: High
- Category: LCP / image optimization
- Evidence class: 2-PageSpeed lab
- Evidence: `image-delivery-insight` audit, Home page: `PS-laying_fam-768x434.png` — `fetchpriority="high"` (explicitly marked by the theme as the priority image), totalBytes 367,813 B, wastedBytes 329,794 B (90% waste). Second item: `tesamorelin-10mg-coa-source.webp` — 308,112 B, wastedBytes 291,690 B (95% waste), `loading="lazy" decoding="async"`. Third: `tesamorelin-10mg-vial-batch.webp` — 113,818 B, wastedBytes 90,812 B (80% waste). Combined `image-delivery-insight` estimated savings on Home: "Est savings of 775 KiB" against a total image payload of 926,403 B — i.e. the tool estimates ~84% of all image bytes on the homepage are recoverable waste.
- Affected URLs: `/` (primary — hero image is `fetchpriority="high"`, i.e. the developer already flagged it as LCP-critical but did not right-size it)
- Reasoning: The `fetchpriority="high"` attribute on `PS-laying_fam-768x434.png` is strong circumstantial evidence this is (or is intended to be) the LCP element on Home, since Lighthouse and browsers use that hint for LCP prioritization. A 367 KB PNG serving a 768×434 slot with 90% estimated waste means the source file is far larger/less compressed than the displayed dimensions require — classic un-resized-source or wrong-format (PNG instead of WebP/AVIF) issue. This directly extends Home's LCP time (6.0 s, the worst of the four templates).
- Recommendation: Re-export the homepage hero image at its actual display dimensions in WebP or AVIF, and re-check the two `why-pep-select` WebP images (COA and vial-batch) for correct sizing relative to their rendered `boundingRect` — both show ~80–95% waste ratios despite already being WebP, which points to oversized source dimensions rather than format.
- Dependencies: None blocking; can be implemented independently of PERF-02. Unblocks PERF-01 (Home).
- Failure check: Re-run `image-delivery-insight` shows the same file(s) with >50% wastedBytes ratio.
- Success check: `image-delivery-insight` total estimated savings for Home drops from "775 KiB" to a low double-digit KiB figure; Home LCP time drops measurably in the next lab run.
- Leading indicator: `totalBytes` reported for `PS-laying_fam-*.png` (or its replacement) in the `image-delivery-insight` audit item list.

---

*Source: Performance (lab) agent (`_agents/performance.md`)*

### [PERF-05] High JavaScript request volume (36–48 scripts/template) built on jQuery/jQuery UI/Underscore is the primary INP-risk driver
- Priority: High
- Category: INP
- Evidence class: 2-PageSpeed lab / 5-Crawler observation
- Evidence: `resource-summary` script counts — Home 44 req (464,246 B), Product 48 req (492,314 B), Shop 41 req (461,565 B), Testing 36 req (419,870 B). `js-libraries` audit (Home) detects jQuery 3.7.1, jQuery UI 1.13.3, Underscore 1.13.8, core-js, WordPress 7.0.4 — i.e. multiple legacy DOM/utility libraries loaded concurrently rather than a single consolidated bundle. `dom-size-insight` "Total elements": Home 437, Product 459, Shop 694, Testing 595 — all comfortably under the 1,500-element risk threshold, so DOM size itself is not currently a contributing INP risk factor. Total Blocking Time (a lab proxy correlated with INP, not INP itself — INP cannot be measured in a single synthetic Lighthouse run) is 30–130 ms across the four templates, currently inside the "Good" INP band (≤200 ms) as a rough proxy.
- Affected URLs: `/`, `/product/bpc157-10/`, `/shop/`, `/testing/`
- Reasoning: TBT is currently in the good range, so INP is not yet a confirmed field failure — but 36–48 separate script requests per page, several of them render-blocking (PERF-02) and drawn from three different JS utility libraries (jQuery, jQuery UI, Underscore) plus per-plugin bundles, is a script-volume profile that degrades quickly on low-end mobile devices and is the standard leading cause of INP regressions once real-user interaction (add-to-cart, side-cart open, filter clicks) is layered on top of a synthetic lab run that does not simulate user input at all. This finding should be read as risk exposure, not a confirmed field failure — field INP is owned by the separate CrUX agent.
- Recommendation: Consolidate/defer non-critical script bundles (side-cart, points-and-rewards, confetti effect — see PERF-12) so fewer scripts execute on initial load, and audit whether jQuery UI is still required by active Elementor/plugin features.
- Dependencies: Overlaps with PERF-02 (render-blocking scripts) and PERF-12 (unused JS bytes) — sequence together since they touch the same enqueued-script list. Depends on a template-by-template feature audit (which plugin JS is actually used per page) before removal.
- Failure check: Field/CrUX INP (checked separately by the field-data agent) remains in "Needs Improvement" or "Poor" after script consolidation, or TBT rises above 200 ms in a follow-up lab run.
- Success check: Script request count per template drops meaningfully (e.g., below 30) without functional regression in cart/checkout/filter interactions, and TBT stays ≤130 ms or improves.
- Leading indicator: `resource-summary` Script `requestCount` and `transferSize`, re-checked via `pagespeed_check.py --psi-only` without a full audit.

---

*Source: Visual / Mobile agent (`_agents/visual.md`)*

### [VIS-02] Interstitial gate has no focus-trap or `inert`/`aria-hidden` on background content
- Priority: High
- Category: Accessibility (keyboard & screen reader) tied to interstitial
- Evidence class: [5-Crawler observation/inference]
- Evidence: Raw HTML shows the gate is marked `role="dialog" aria-modal="true"`, but the underlying page wrapper is not given `inert` (0 occurrences of the `inert` attribute in the homepage HTML) or `aria-hidden="true"` (the 13 `aria-hidden` occurrences found on the homepage are pre-existing icon/decoration attributes elsewhere in the page, not applied to a background wrapper when the gate is open). The gate's own control script (the block containing `COOKIE`, `setCookie`, `closeGate`, checkbox/select `change` listeners) contains no `keydown`, `focus(`, or `Tab`-key handling — i.e., no scripted focus trap.
- Affected URLs: `https://pepselect.com/`, `https://pepselect.com/product/bpc157-10/`, `https://pepselect.com/shop/`, `https://pepselect.com/testing/` (sitewide mechanism).
- Reasoning: `aria-modal="true"` tells assistive technology that everything outside the dialog should be treated as inert, but without an actual `inert` attribute (or `aria-hidden` + real focus trap) on the background, keyboard users tabbing through the page and screen-reader users in browse/virtual-cursor mode can still reach and activate background navigation links, buttons, and forms that are visually hidden behind the modal (`overflow:hidden` on `html`/`body` prevents scrolling to see them, but does not prevent focus from landing on them). This is a WCAG 2.1 SC 2.4.3 (Focus Order) / 4.1.2 (Name, Role, Value) conformance gap common to hand-rolled modals.
- Recommendation: Describe (do not implement) that the gate script should apply `inert` (or `aria-hidden="true"` plus a scripted Tab-key trap that cycles focus within `#psag-gate`) to the main content wrapper for the duration the gate is open, and move initial focus into the dialog (e.g., the "Researcher type" select) when it opens.
- Dependencies: Depends on VIS-01 remediation scope (any redesign of the gate should include this fix in the same change). Does not block other findings.
- Failure check: Manual keyboard test (Tab key) from page load reaches links/buttons that are not part of `#psag-gate` while the gate is still open.
- Success check: Tab key cycles only within the gate's interactive elements until it is dismissed; screen reader announces only the dialog's content.
- Leading indicator: Accessibility scanner (axe/Lighthouse a11y audit) flags on this specific page pattern trend toward zero over time.

---

*Source: GEO / AI Search agent (`_agents/geo.md`)*

### [GEO-03] Product-page description passages are far shorter than the optimal AI-citation length
- Priority: High
- Category: Citability
- Evidence class: 5-Crawler observation
- Evidence: `/product/glp3-r10/` "Description" section, full paragraph, verbatim: *"Retatrutide is a peptide studied as a triple receptor agonist, engineered to engage the GLP-1, GIP, and glucagon receptors. It is researched for the structural basis of its simultaneous activity across all three receptors."* — **34 words**, one paragraph, no supporting stats. This is the entire extractable "what is this compound" passage on the page. The same product's own COA archive holds seven measured properties (claimed content, net content, purity 99.85%, identity, heavy metals, sterility, fentanyl screen) with named lab and method, but none of that data appears in the product page's own text.
- Affected URLs: All 15 `/product/*` URLs (pattern confirmed on `glp3-r10`; consistent with the 196–324-unique-word range already measured site-wide in the prior SEO audit's C-02 finding).
- Reasoning: The stated optimal AI-citation passage length is 134–167 words; a 34-word single-fact paragraph is roughly one-quarter of that floor. An AI engine assembling an answer about "what is Retatrutide" or "is GLP-3 R pure" has almost nothing extractable from the product page itself — it would have to synthesize across the separate COA batch page, which most retrieval-and-summarize pipelines will not chain automatically. This is the same underlying content gap the SEO audit's C-02 flagged for word-count/ranking reasons; the GEO framing is sharper: the fix is not "add more words," it is "surface the batch-specific facts that already exist in Dataset schema as prose the description paragraph can cite."
- Recommendation: Extend each product description with a 100–130-word block that states the compound's current batch purity, net content, identity result, and testing lab/method in plain prose (mirroring the Dataset `variableMeasured` values), landing the full description in the 134–167-word optimal band without adding unverifiable claims.
- Dependencies: Content for this should be templated to pull live values from the same data source that feeds the COA `Dataset` schema (GEO-07), so the two never drift out of sync. Sequenced after GEO-07.
- Failure check: Expanding descriptions to 134–167 words with no batch-data content, or with data that goes stale relative to the live COA record, would not move citation likelihood and would create a new trust inconsistency — re-derive from the live batch feed, not static copy.
- Success check: All 15 product descriptions land in the 134–167-word band and contain at least one named, sourced statistic (purity %, lab name, or test date).
- Leading indicator: Word count per product description on next crawl; presence of a specific statistic + attribution in the first 60 words.

---

---

*Source: GEO / AI Search agent (`_agents/geo.md`)*

### [GEO-06] No informational content mapped to the exact query set already proven to earn AI Overview and "Things to know" citations in this niche
- Priority: High
- Category: Citability / Content Coverage
- Evidence class: 4-DataForSEO estimate + 5-Crawler observation
- Evidence: The DataForSEO SERP artifact lists four People Also Ask queries for "research peptides" — *"What is the most trusted peptide company?"*, *"Do research peptides actually work?"*, *"Where can I buy research peptides in the USA?"*, *"What is the risk of taking peptides?"* — plus three AI-Overview "Things to know" card topics: **Benefits, Regulations, Purity Standards**, each citing NIH/PMC, Harvard Health, WebMD, and multiple vendor blogs. A full-text review of Pep Select's existing Q&A content (15 FAQ questions + 5 homepage FAQ questions, verbatim topics: research-use definition, batch documentation lookup, batch-fail policy, storage/handling, payment, cash-back, shipping cutoffs, order tracking, damaged-order remedy) shows **zero overlap** with any of the four PAA queries or the three "Things to know" topics. There is no page anywhere on the crawled site addressing "what is a research peptide," "purity standards / how testing works as a category" (as distinct from a single batch's result), "is research peptide use legal / RUO framing explained," or a trust/comparison page.
- Affected URLs: Site-wide gap — no single URL currently addresses this; closest partial coverage is `/faq/` (transactional, not informational) and the COA archive (batch-specific, not category-explainer).
- Reasoning: The DataForSEO evidence shows this is not a closed category — vendor blogs are already winning citations in the AI Overview and consideration cards for exactly these topics. Pep Select has the strongest raw material in the category to answer the "Purity Standards" and "trusted company" queries credibly (permanent, third-party, batch-level, HPLC/LC-MS/immunoassay lab data, including publishing batches that *failed* release review — verified directly on `/testing/retatrutide-20mg/`, which shows batch PSRT2062926JP marked "Did not pass release review" and "was not released for sale," a transparency signal that is essentially absent from competitor blog content per the DataForSEO evidence) — but has not written the informational page that would let an AI Overview cite that material the way it already cites americanpeptides.us and rpeptide.com.
- Recommendation: Publish 3–4 informational pages directly targeting the PAA/consideration-card topics: (a) a "how we test / purity standards" explainer generalizing the COA methodology across all compounds, (b) an RUO/legality explainer using the site's existing compliance framing, (c) a "how to evaluate a research peptide vendor" trust page that can legitimately compete for "most trusted peptide company," and (d) fold "do research peptides work" into RUO-compliant language about what is and isn't studied — mirroring GenScript's coexistence pattern noted in the DataForSEO analysis (RUO framing that survives alongside commercial content rather than clashing with Google's caution overlay).
- Dependencies: Should ship with `FAQPage`/`Article` schema from day one (GEO-02 pattern) and link directly into the COA archive and relevant product pages. Benefits from GEO-04/GEO-05 (an entity with off-site corroboration is more likely to be treated as a citable source once the content exists).
- Failure check: If these pages are published and indexed but do not appear in a repeat DataForSEO SERP/AIO pull after 8–12 weeks, the constraint has shifted to authority (GEO-05), not content coverage — do not keep iterating content in isolation.
- Success check: A repeat DataForSEO SERP pull for "research peptides" or its PAA/related-search set shows Pep Select content cited in the AI Overview, "Things to know" cards, or People Also Ask expansions.
- Leading indicator: Search Console impressions for non-transactional, question-phrased queries (e.g., "how are research peptides tested," "are research peptides legal") appearing where they currently register zero.

---

---

*Source: SXO agent (`_agents/sxo.md`)*

### [SXO-02] No comparison or "why choose us" content type exists for the "best/cheapest/most trusted" query cluster
- Priority: High
- Category: Page-Type Mismatch — Content Gap
- Evidence class: 4 (DataForSEO estimate) + 5 (Crawler observation)
- Evidence: Related searches in the SERP pull are uniformly comparison-shaped ("best place to buy research peptides online," "cheapest research peptides," "research peptides for sale") and PAA includes "What is the most trusted peptide company?" Cross-checked against the site: the 18-question FAQ (`pages/faq.html`, H3 extraction) contains zero questions framed around trust comparison, vendor selection, or "why Pep Select" — its closest entries are logistics-framed ("What are the shipping options?," "Where do you ship?"). This matches the main audit's C-06 ("No editorial or informational content surface").
- Affected URLs: `/` (would host or link the asset), `/shop/`, sitewide (no candidate page currently exists).
- Reasoning: Google is already citing two vendors directly inside the AI Overview for this exact query, so citation is achievable in this niche — but only for content that exists. Pep Select has the raw material (independent lab, named lab, batch-level COAs) to answer "most trusted peptide company" credibly and RUO-compliantly; it simply has not been assembled into a single citable page.
- Recommendation: Description only — a dedicated trust/methodology page (e.g., "How we test" or "Purity standards") that mirrors Google's own "Things to know" categories (Benefits, Regulations, Purity Standards) using only verifiable, already-published facts (named lab, HPLC/MS methods, batch retention policy). No comparative or superiority claims about named competitors — describe Pep Select's own practice only, consistent with the compliance boundary against unsupported comparative claims.
- Dependencies: Depends on C-04 (no About/credential page — a trust page needs an identifiable entity behind it). Unblocks further AI Overview citation eligibility (parallel to G-04).
- Failure check: If the page is published and Search Console shows no impression growth for trust-modifier queries ("trusted," "legit," "safe") after 8 weeks, the constraint is domain authority (G-05), not the content gap — stop iterating on this content and redirect to off-site work.
- Success check: GSC impressions/clicks appear for trust-modifier query variants; the page is eligible for (though not guaranteed) AI Overview citation alongside the two already-cited competitors.
- Leading indicator: GSC → Performance → query filter for "trust/trusted/legit/safe/best" modifiers, checked monthly.

---

*Source: SXO agent (`_agents/sxo.md`)*

### [SXO-03] Zero pages address any of the 4 ready-made PAA slots
- Priority: High
- Category: Page-Type Mismatch — PAA Content Gap
- Evidence class: 4 (DataForSEO estimate) + 5 (Crawler observation)
- Evidence: PAA verbatim from the SERP pull: "What is the most trusted peptide company?" / "Do research peptides actually work?" / "Where can I buy research peptides in the USA?" / "What is the risk of taking peptides?" Cross-referenced against the full 18-question FAQ list and all 43 crawled URLs — no page title, H1, or FAQ question matches or closely paraphrases any of the four. The closest adjacent content is per-product "Research context ... View the sources" citation blocks (verified on `/product/glp3-r10/`: 2 journal citations with DOIs) and the `/ruo-disclaimer/` page, both of which answer a narrower or differently-framed question than the PAA text.
- Affected URLs: sitewide — no page currently targets this slot set.
- Reasoning: PAA boxes are a direct content brief from Google. "Where can I buy research peptides in the USA?" is a broad vendor-discovery question distinct from Pep Select's existing "Where do you ship?" (logistics, not vendor-selection); "What is the risk of taking peptides?" is a safety question distinct from the RUO page's legal-liability framing. Each of the 4 PAA slots maps to a different journey stage (2 awareness, 2 consideration) and none currently has a candidate page.
- Recommendation: Description only — four narrowly-scoped content candidates, RUO-compliant (describe what is measured/documented, never what a compound does to a person): (a) a trust/vendor-selection page (shared scope with SXO-02), (b) an evidence-summary page consolidating the existing per-compound journal citations already on product pages, (c) a USA-availability/legality page distinct from shipping logistics, (d) a safety/handling page (storage, RUO scope, qualified-researcher restriction) distinct from the existing legal-liability RUO page.
- Dependencies: Shares the trust-page candidate with SXO-02; should be sequenced after C-04 for the same reason (citable content needs an identifiable entity).
- Failure check: If all four pages are published and none is ever surfaced in a PAA box or AI Overview citation after a reasonable indexing period, PAA targeting at the page level was not the binding lever for this query — authority (G-05) likely is.
- Success check: Any one of the four candidate pages appears as a PAA-linked result or AI Overview citation for its target question.
- Leading indicator: Manual/periodic SERP check on the four PAA question strings; GSC impressions for question-phrased queries matching each page.

---

*Source: SXO agent (`_agents/sxo.md`)*

### [SXO-04] `/shop/` category page under-delivers the trust density and stock breadth that SERP category-page winners show
- Priority: High
- Category: Page-Type Mismatch — Execution Gap
- Evidence class: 5 (Crawler observation/inference)
- Evidence: Text extraction from `pages/shop.html` shows 15 product cards, of which **8 display "Out of stock"** and only 7 are purchasable (matches the main audit's O-04: 47% of catalog unavailable). No "review," "rating," or trust-badge text appears anywhere on the page outside WooCommerce boilerplate config strings (`review_rating_required`) and the single sentence "Pep Select carries what passes our review and nothing else." No price-range summary, no per-card purity/testing marker — cards show only name, price, and stock state.
- Affected URLs: `https://pepselect.com/shop/`.
- Reasoning: Two of the nine SERP competitors show a price range directly in their snippet (rpeptide.com $120–$200; redrockpeptides.com $59–$410) — a comparison-stage signal delivered before the click. Pep Select's own category page doesn't even deliver that signal after the click: a visitor must open each product individually to see its purity/batch data, and nearly half the grid is a dead end ("Notify when available" instead of a purchase path). For the price-comparison shopper persona (57/100, weakest lever), this is the primary friction point.
- Recommendation: Description only — surface a compact, per-card trust marker (e.g., "99.6% purity · Tested Jul 30") pulled from the same batch data already rendered on the linked product/testing pages, and evaluate whether out-of-stock cards should be visually deprioritized or moved below in-stock items rather than interleaved.
- Dependencies: Depends on S-04 (ItemList/schema linking, main audit) and benefits from C-02 (thin content) being addressed first so the underlying product pages have more to summarize.
- Failure check: If per-card trust markers are added and category-page engagement/click-through to product pages doesn't change, the stock-availability problem (not the missing trust marker) is the dominant friction — prioritize catalog restocking or SKU pruning instead.
- Success check: Re-crawl shows visible purity/test-date markers on in-stock cards; Search Console click-through rate for `/shop/` rises; internal click rate from `/shop/` to product pages increases.
- Leading indicator: GSC → Pages → `/shop/` CTR trend; ratio of in-stock to out-of-stock SKUs on any given re-check.

---

*Source: E-commerce agent (`_agents/ecommerce.md`)*

### [ECOM-03] Manufacturer boilerplate description used verbatim on GLP-2 T20, inconsistent with the site's otherwise unique copy
- Priority: High
- Category: Content uniqueness
- Evidence class: 5-Crawler observation
- Evidence: Product JSON-LD `description` field, verbatim: *"GLP-2T 20MG. This product is intended exclusively for laboratory research and analytical applications. Each vial is manufactured to high-quality standards and is supplied for use by qualified researchers in controlled laboratory environments. The contents are intended for scientific investigation only... Product Features Laboratory research grade peptide Manufactured under strict quality standards Suitable for analytical and research applications Individually packaged for laboratory use Intended for qualified research professionals Storage Store in a cool, dry place away from direct sunlight..."* By contrast, every other checked product (BPC-157, GHK-CU, SS-31, TB-500, NAD+, MOTS-C, GLP-1 S10, Glutathione) uses a distinct one-paragraph, mechanism-specific description (e.g. GHK-CU: *"GHK-Cu is a copper-binding tripeptide... studied for its role in tissue remodeling... stimulating structural-protein synthesis and modulating genes tied to skin and matrix regeneration."*).
- Affected URLs: `https://pepselect.com/product/glp2-t20/`
- Reasoning: This is templated wholesale-supplier boilerplate ("Product Features... Manufactured under strict quality standards... Suitable for analytical and research applications") of a style commonly reused verbatim across many peptide resellers. It breaks the pattern of concrete, differentiated, mechanism-level copy that the prior full-site audit measured at 0/100 filler score and 0.93–1.00 information density elsewhere — meaning GLP-2 T20 is both a duplicate-content risk versus other sellers and an outlier in the site's own content-quality profile.
- Recommendation: Describe replacing the GLP-2 T20 description with a mechanism-specific paragraph matching the format used for the other 14 products, sourced/approved via the product-marketing reference material rather than supplier copy.
- Dependencies: Depends on `.agents/product-marketing.md`-approved compound description for GLP-2 T20 (peptide-marketing/compliance review, not a code change).
- Failure check: The generic "Product Features / Manufactured under strict quality standards" paragraph is still present verbatim on a future crawl.
- Success check: GLP-2 T20's `description` field matches the mechanism-specific one-paragraph format used site-wide and is unique versus supplier/manufacturer stock copy (spot-check via search-snippet lookup).
- Leading indicator: Word-for-word diff of the GLP-2 T20 description against the current boilerplate string above.

---

*Source: E-commerce agent (`_agents/ecommerce.md`)*

### [ECOM-04] The COA trust archive (`/testing/`) has zero internal links back to product pages
- Priority: High
- Category: Internal linking (content ↔ product)
- Evidence class: 5-Crawler observation
- Evidence: Regex scan of the rendered `/testing/` hub HTML for `href="https://pepselect.com/product/` returned 0 matches. The reverse link exists and works (every checked product page links forward to `/testing/` and, where a batch exists, to the specific `/testing/[slug]/[batch]/` page), but the flow is one-directional.
- Affected URLs: `https://pepselect.com/testing/` (and, by extension, its 8 compound-hub and 9 batch sub-pages, none of which were observed linking to `/product/`)
- Reasoning: The prior full-site audit identifies `/testing/` as the site's strongest differentiator and most likely source of organic/AI-citation traffic (Dataset schema, DataDownload PDFs, near-unique in the vertical). A visitor or an AI crawler arriving there — whether from search, an AI Overview citation, or a shared link — has no on-page path into the shop. This both wastes the archive's link equity (no PageRank flow from `/testing/` into `/product/`) and caps its commercial value.
- Recommendation: Describe adding a "Buy this compound" or "Shop [Compound]" link/button on each `/testing/[compound]/` and `/testing/[compound]/[batch]/` page pointing to the matching `/product/[slug]/` page, once ECOM-02's coverage gap is closed for the 6 missing compounds.
- Dependencies: Independent of ECOM-02 for the 9 compounds that already have both pages; depends on ECOM-02 for full-catalog coverage.
- Failure check: `/testing/` pages still contain 0 links to `/product/` URLs on a future crawl.
- Success check: Each `/testing/[compound]/` page contains at least one link to its corresponding `/product/[slug]/` page; internal-link graph shows `/testing/` → `/product/` edges.
- Leading indicator: Manual click-through from any `/testing/[compound]/` page — a "Shop" or "Buy" CTA becomes visible.

---

*Source: E-commerce agent (`_agents/ecommerce.md`)*

### [ECOM-05] Nearly half the live catalog (7 of 15 products) is Out of Stock, fully indexed, with no substitute-product surfacing
- Priority: High
- Category: Out-of-stock handling
- Evidence class: 5-Crawler observation
- Evidence: Product JSON-LD `offers[0].availability` across all 15 real products: `OutOfStock` — BPC-157 (`bpc157-10`), SS-31 (`ss-31`), MOTS-C (`motsc-10`), GLP-1 S10/semaglutide (`glp1-s10`), Retatrutide 20mg (`glp3-r20`), Glutathione (`glutathione`), GLP-2 T20 (`glp2-t20`) = 7/15 (47%). `InStock`: GHK-CU, TB-500, NAD+, Tesamorelin, PT-141, Retatrutide 10mg, Retatrutide 30mg, Bacteriostatic Water = 8/15. All 7 OOS pages return HTTP 200, `<meta name="robots" content="index, follow...">` (verified on `bpc157-10`), and remain in `product-sitemap.xml` and in "related products" grids elsewhere on the site (e.g. `bpc157-10`'s related-products block surfaces Retatrutide 20mg and Glutathione, both also OutOfStock). The only OOS mitigation observed is a "cwginstock" back-in-stock email opt-in widget (`Email when stock available`) — no in-stock substitute or alternate-strength suggestion is surfaced.
- Affected URLs: `https://pepselect.com/product/bpc157-10/`, `https://pepselect.com/product/ss-31/`, `https://pepselect.com/product/motsc-10/`, `https://pepselect.com/product/glp1-s10/`, `https://pepselect.com/product/glp3-r20/`, `https://pepselect.com/product/glutathione/`, `https://pepselect.com/product/glp2-t20/`
- Reasoning: Keeping OOS pages indexed and internally linked is generally correct practice (preserves URL equity, avoids 404/redirect churn) and is not itself the problem. The risk is compounding with ECOM-02: two of the seven OOS products (BPC-157, SS-31) are also the ones missing COA pages, meaning a visitor who searches for either compound by name currently lands on a page that is both unavailable to buy and unable to show batch-tested purity — the two weakest trust/conversion states stacked on the same URLs.
- Recommendation: Describe surfacing an explicit in-stock alternative (e.g. "In the meantime, see [similar compound]") on OOS product pages beyond the restock-email form, and prioritizing restock or supplier sourcing for BPC-157 and SS-31 given their likely search demand (both are named in the competitor SERP evidence as commonly searched compound terms). This is an inventory/business decision, not a code change, beyond describing where a "you may also like — in stock" block could be templated.
- Dependencies: Independent of other findings; compounds well with ECOM-02 and ECOM-03 on BPC-157/SS-31/GLP-2 T20 specifically.
- Failure check: OOS ratio stays at or above ~47% on a future crawl with no substitute-product mechanism added.
- Success check: OOS product pages show a clearly in-stock alternative recommendation in addition to the restock-email opt-in; OOS ratio trending down.
- Leading indicator: `availability` field flipping to `InStock` in Product JSON-LD for BPC-157/SS-31, checkable via a single-page fetch without a full re-audit.

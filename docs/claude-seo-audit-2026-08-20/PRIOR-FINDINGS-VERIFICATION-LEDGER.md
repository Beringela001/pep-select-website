# Prior Findings Verification Ledger — 2026-08-20

**Baseline audited:** `docs/claude-seo-latest/CODEX-SEO-FINDINGS-LEDGER-2026-08-18.md` (97 findings, Claude SEO 2.2.4 integrated reports, 2026-08-18) plus every release/checkpoint document dated through 2026-08-19 (M3, M4 Batches 1/2/3-4, beta.39 staging performance note, GSC/PageSpeed checkpoint).

**This audit:** a NEW, independent post-remediation verification pass against Live `https://pepselect.com` as of 2026-08-20. Every one of the 97 prior findings was re-checked with fresh evidence by a dedicated specialist (technical, performance, schema, sitemap, content, e-commerce, GEO, visual, SXO, Google-field-data). Full per-domain detail and raw evidence citations live in `findings/*.md` and `raw-crawl/` / `raw-pagespeed/`. This document is the single consolidated table plus the handful of cross-domain judgment calls made when two specialists' independent evidence bore on the same finding.

## Status-label definitions (unchanged from the 8/18 ledger; preserved verbatim)

- **VERIFIED FIXED:** the finding's specific factual claim is now false — direct, fresh evidence shows the underlying condition no longer holds.
- **PARTIALLY FIXED:** real, verifiable improvement exists, but the finding's core claim is not fully resolved.
- **REGRESSED:** the condition is now worse than the 8/18 baseline, or a previously verified fix no longer holds.
- **STILL OPEN:** no material change from 8/18; the original condition persists.
- **BLOCKED BY REAL EVIDENCE:** resolution requires business data, real credentials/records, account access, or an owner decision — never fabricated. Unchanged unless real input was supplied.
- **SUPERSEDED:** informational, optional, time-dependent, or intentionally not pursued (e.g., "don't manufacture freshness dates," "don't chase retired rich results"). No action expected.

Original priority values (Critical/High/Medium/Low) are preserved exactly as published on 8/18 and are **not** changed by this audit, per instructions.

## Headline result

| Classification | Count | Share |
|---|---:|---:|
| VERIFIED FIXED | 13 | 13% |
| PARTIALLY FIXED | 15 | 15% |
| STILL OPEN | 47 | 48% |
| BLOCKED BY REAL EVIDENCE | 15 | 15% |
| SUPERSEDED | 7 | 7% |
| REGRESSED | 0 | 0% |
| **Total** | **97** | 100% |

Zero findings regressed. Five deployment milestones (M3, M4 Batch 1/2/3-4, beta.39) since 8/18 delivered real, independently-reproduced fixes concentrated in crawl-path/sitemap hygiene, the GLP-2T rewrite, and schema entity-graph consolidation. The largest remaining blocks are (a) 47 still-open items spanning security headers, the research-gate UX, catalog-wide thin content, and citability, and (b) 15 items genuinely blocked on business input (reviews, NAP, social profiles, GA4 property, restocking) that no engineering work can close.

## Three findings where fresh evidence meaningfully changes the picture (read these first)

1. **VIS-02 (High) — downgraded from "Live code verified" to STILL OPEN.** Direct Playwright DOM/script inspection on 2026-08-20 found `aria-describedby` absent, the "Exit" link has **no `href` attribute at all** (JS-click-only, excluded from tab order), a full-text search of the rendered page for `inert`/`aria-hidden` returned **zero matches anywhere** (no background inerting/focus trap exists), and no `keydown`/Tab handling code exists in the gate's script. Four of the eight specific sub-claims in the 8/18 ledger's VIS-02 write-up are not supported by the live markup. `role="dialog"`, `aria-modal`, `aria-labelledby`, and the attestation controls are confirmed present. See `findings/visual-sxo.md` §2 for the full claim-by-claim table.
2. **SXO-01 (Critical) — reframed, not fixed.** Raw non-JS HTTP fetches on five templates confirm real page content (hero copy, product data, full COA/batch evidence blocks) **is** delivered in the HTTP response body, tens of thousands of bytes before the gate's markup, which sits at the end of `<body>`. The original wording "trust content is deferred, not delivered" overstates the server-side mechanism — nothing is withheld from the response. What remains unremediated and fully confirmed is the human/rendering-side problem: the gate is an unconditional `position:fixed;z-index:9999999` overlay, so 100% of JS-rendering visitors (and very likely Googlebot's rendering pass) see only the gate at first paint. Still STILL OPEN, but the fix should be scoped as a gate-presentation/timing change, not a content-generation fix. See `findings/visual-sxo.md` §"THE CENTRAL QUESTION."
3. **GOOG-01 / GOOG-02 — indexation itself is now confirmed, closing the literal claim, while the practical concern (GOOG-03) persists.** Read-only GSC URL Inspection on 2026-08-20 shows Shop, the Quality Archive hub, the documentation guide, and Retatrutide 10mg have all moved from "Discovered/unknown" to **"Submitted and indexed."** This resolves GOOG-01/GOOG-02's specific "not indexed" claims. But Search Analytics for the same property shows **0 clicks and 7 impressions total** across both the 28-day and 90-day windows, average position 45.7 — indexation has not yet produced any organic visibility, which is exactly what GOOG-03 (still open) tracks separately.

## Full classification table

### Google / Indexation (GOOG)

| ID | Priority | Prior State (8/18) | Classification | Evidence |
|---|---|---|---|---|
| GOOG-01 | Critical | Live technical work complete / Google validation pending | **VERIFIED FIXED** | GSC URL Inspection 2026-08-20: `/shop/` verdict PASS, "Submitted and indexed," last crawl 2026-08-19T19:08:25Z. All crawl-path prerequisites (canonical, robots, sitemap) independently confirmed clean. `findings/google-field-data.md` §1, `findings/technical.md`. |
| GOOG-02 | High | Live source verified / Google validation pending | **VERIFIED FIXED** | GSC URL Inspection 2026-08-20: `/testing/` verdict PASS, "Submitted and indexed," referring `ps_compound-sitemap.xml`. Sitemap-side fix re-confirmed live by two independent specialists. `findings/google-field-data.md` §1, `findings/sitemap.md`. |
| GOOG-03 | Critical | Live technical work complete / monitoring pending | **STILL OPEN** | GSC Search Analytics 2026-08-20: 0 clicks, 7 impressions, 0% CTR, avg. position 45.7 — identical for 28-day and 90-day windows. `findings/google-field-data.md` §2. |
| GOOG-04 | High | Live partial | **PARTIALLY FIXED** | Homepage mobile LCP now 3.8s (Needs Improvement, was Poor >4.0s); render-blocking reduced but not eliminated (2,320ms). `findings/performance.md` §6. |
| GOOG-05 | High | Live partial | **PARTIALLY FIXED** | Shop mobile LCP improved from 7.8s baseline to a 4.2–6.8s range across independent runs; still above "Good" and touches "Poor" in the worst run. `findings/performance.md` §6. |
| GOOG-06 | Medium | Not started | **STILL OPEN** | NAD+ product mobile LCP measured 4.2s–7.1s across runs — at or worse than the original 4.2s baseline; no targeted remediation evidence found. `findings/performance.md` §6. |
| GOOG-07 | Medium | Not started | **PARTIALLY FIXED** | Site-wide render-blocking remains the dominant mobile bottleneck (1,650–2,830ms) on every template, but beta.39's font consolidation is measurably reflected (Testing hub font transfer now lowest of all 5 templates, was the worst). `findings/performance.md` §3. |
| GOOG-08 | Low | Not started | **STILL OPEN** | Umbrella for TECH-01–04. Fresh header pull confirms none of HSTS/CSP/X-Frame-Options/Referrer-Policy/Permissions-Policy present; only `X-Content-Type-Options: nosniff`. `findings/technical.md` H1/M1/M2/L1. |
| GOOG-09 | Medium | Blocked / input needed | **BLOCKED BY REAL EVIDENCE** | Fresh CrUX History check (origin + homepage URL) 2026-08-20 returns the identical "insufficient Chrome traffic volume for eligibility" error as 8/19. No code fix exists. `findings/google-field-data.md` §4, `findings/performance.md` §4. |
| GOOG-10 | Low | Not started | **STILL OPEN** | `/shop/` still renders 7 identical non-descriptive "Learn more" links, no `aria-label` differentiation. `findings/content.md` §5. |
| GOOG-11 | Medium | Blocked / input needed | **BLOCKED BY REAL EVIDENCE** | `google_auth.py --check` confirms GA4 still unavailable — OAuth credentials exist but no `GA4_PROPERTY_ID` configured. New evidence: a `gtag.js` container (`GT-NNQ4N6DP`) is now detectable site-wide, but this does not by itself confirm a working GA4 data stream; needs Paulo/GA4-admin confirmation. `findings/google-field-data.md` §5, `findings/content.md` §6. |

### Off-page / Competitive (DFS)

| ID | Priority | Prior State (8/18) | Classification | Evidence |
|---|---|---|---|---|
| DFS-01 | Critical | Not started | **STILL OPEN** | GSC Search Analytics confirms 0 organic clicks (see GOOG-03). Independent WebSearch spot-checks for "buy research peptides online" and "pep select peptides research" on 2026-08-20 show pepselect.com absent from all returned results. Orchestrator-level evidence, no dedicated specialist owned this ID. |
| DFS-02 | Critical | Not started | **STILL OPEN** | Not independently re-measured this cycle — free-tool (Common Crawl/Bing Webmaster) backlink re-check was not run; no paid DataForSEO/Ahrefs/Moz call was made per audit constraints. No evidence of any W8 (off-site outreach) work in any release note through beta.39, so no improvement is presumed. |
| DFS-03 | High | Not started | **STILL OPEN** | WebSearch spot-check for "buy research peptides online" (2026-08-20) returns 9 competitor domains, pepselect.com absent — matches original 100-deep-crawl absence finding directionally. |
| DFS-04 | High | Not started | **STILL OPEN** | Not independently re-verified — requires paid keyword-difficulty data (DataForSEO Labs), out of this audit's read-only/free-tool scope. No contrary evidence found. |
| DFS-05 | High | Not started | **STILL OPEN** | WebSearch spot-check for "bpc-157 for sale" (2026-08-20) returns the same class of recurring research-peptide vendor domains (corepeptides.com, biotechpeptides.com, xenopeptides.com, etc.); pepselect.com absent — consistent with unchanged competitive landscape. |
| DFS-06 | High | Not started | **STILL OPEN** | Not independently re-tested — WebSearch does not surface AI Overview panel content directly; no paid SERP-feature tool used per constraints. No contrary evidence found. |
| DFS-07 | Medium | Blocked / input needed | **BLOCKED BY REAL EVIDENCE** | No `google_product_category`, GTIN, MPN, or Merchant Center feed reference found on any of 16 pages fetched 2026-08-20. `findings/ecommerce.md`. |
| DFS-08 | Medium | Not started | **STILL OPEN** | New FAQ/guide content addresses Pep Select's own operational questions and COA literacy, not the SERP-derived PAA question map identified in the original audit. `findings/content.md` §4. |
| DFS-09 | Medium | Not started | **STILL OPEN** | No content found targeting the "bpc-157 for sale" commercial-intent cluster; BPC-157's product page remains a standard thin templated page (194 words, out of stock). `findings/content.md` §9. |
| DFS-10 | Low | Superseded / no immediate fix | **SUPERSEDED** | Domain age is purely time-dependent (now ~7 weeks vs. ~6 at 8/18); no fix applicable or expected. Unchanged disposition. |

### Content / E-E-A-T (CONT)

| ID | Priority | Prior State (8/18) | Classification | Evidence |
|---|---|---|---|---|
| CONT-01 | Critical | Live verified | **VERIFIED FIXED** | Not re-tested this cycle (deprioritized per scope); no contrary evidence encountered. Deferring to prior verified status; `/terms-of-service/` redirect chain independently re-confirmed intact by the technical specialist. `findings/content.md` §8, `findings/technical.md`. |
| CONT-02 | Critical | Live verified | **VERIFIED FIXED** | GLP-2T page shows fully-corrected DOI citation text; no `[VERIFY DOI]` placeholder found. `findings/content.md` §8. |
| CONT-03 | High | Not started (ledger) / "limited Live contribution" (checkpoint) | **PARTIALLY FIXED** | Homepage gained one natural "research peptide" use; GLP-2T's description was rewritten. The ~109-word verbatim compliance block still recurs on every sampled product page including GLP-2T; sitewide boilerplate ratio not materially reduced catalog-wide. `findings/content.md` §1–2. |
| CONT-04 | High | Not started | **STILL OPEN** | 5 of 6 sampled SKUs still run the one-paragraph-description + 3-bullet template at 85–108 unique words each. Bacteriostatic Water 30mL has **zero** product-specific description — a new, worse data point than the original sample. `findings/content.md` §2. |
| CONT-05 | High | Not started | **PARTIALLY FIXED** | One substantial 2,244–2,351-word evidence-led guide is now live, citing real batch pages including a failed/rejected batch. Still a single article, not a content program. `findings/content.md` §3. |
| CONT-06 | Medium | Partially complete | **STILL OPEN** | GLP-2T's three DOI strings remain plain, unhyperlinked text; confirmed on fresh fetch. `findings/content.md` §2. |
| CONT-07 | Medium | Not started (table) / ambiguous "Live implementation deployed" (checkpoint bullet) | **STILL OPEN** | Two differently-worded FDA disclaimer paragraphs (gate version, product "Intended use" version) both still present verbatim, co-occurring on every product page. The Batch 1 checkpoint bullet grouping CONT-07 with the GLP-2T fix appears to be a mislabeling — direct evidence contradicts it. `findings/content.md` §1. |
| CONT-08 | Medium | Blocked / input needed | **BLOCKED BY REAL EVIDENCE** | New guide's Article JSON-LD attributes authorship to the Organization entity; the one `Person` node in the graph is a bare WordPress username with no bio/credentials. `findings/content.md` §3, `findings/geo.md` §6. |
| CONT-09 | Medium | Blocked / input needed | **BLOCKED BY REAL EVIDENCE** | `/contact/` extracted content shows no phone or street address; dominated by gate/FDA text. `findings/content.md` §8. |
| CONT-10 | Low | Not started | **STILL OPEN** | Not independently re-verified (requires out-of-stock modal interaction state); no release note claims a fix. `findings/content.md` §9. |
| CONT-11 | Low | Not started | **VERIFIED FIXED** | Homepage hero confirmed to contain one natural "research peptide" use. `findings/content.md` §1. |
| CONT-12 | Low | Superseded / no immediate fix | **SUPERSEDED** | Out of scope for public read-only crawl (requires WP admin data); no public evidence contradicts prior classification. |
| CONT-13 | Low | Partially complete | **STILL OPEN** | "PepSelect" (one word) and "Pep Select" (two words) both still appear within the same legal documents, including in the mailing address (quantified: Terms 12 vs 7, Privacy 3 vs 7, Refund 1 vs 7, RUO 0 vs 20). `findings/content.md` §7. |
| CONT-14 | Low | Not started | **STILL OPEN** | New guide (technical/structured) and product pages (dense, citation-heavy) sit at one readability register while homepage/FAQ sit plainer; no bridging content observed (qualitative). `findings/content.md`. |

### Performance / Core Web Vitals (PERF, plus GOOG-04/05/06/07/09 and TECH-06 above)

| ID | Priority | Prior State (8/18) | Classification | Evidence |
|---|---|---|---|---|
| PERF-01 | Critical | Live partial | **PARTIALLY FIXED** | Home mobile LCP moved out of Poor into Needs-Improvement (3.8s); Shop/NAD+/Testing hub/COA report remain mostly in Poor range (4.2–7.5s) across independent runs. `findings/performance.md` §6. |
| PERF-02 | Critical | Live partial | **PARTIALLY FIXED** | Mobile render-blocking estimate now 1,650–2,830ms, down from 2,700–3,000ms at the 8/19 checkpoint — real reduction, still the largest single LCP driver. `findings/performance.md` §3, §6. |
| PERF-03 | High | Live implemented / measurement pending | **PARTIALLY FIXED** | Hero WebP reduction independently re-verified; `image-delivery-insight` still fires 49–161 KiB savings on all 5 templates — non-hero images remain unoptimized. `findings/performance.md` §3. |
| PERF-04 | Medium | Partially complete | **STILL OPEN** | PSI summary format doesn't isolate individual assets (e.g., the logo); prior "partially complete" state could not be independently confirmed or refuted from this dataset. `findings/performance.md` §6. |
| PERF-05 | High | Not started | **STILL OPEN** | No INP field data exists (CrUX unavailable); TBT (10–350ms, highly variable) is the only proxy and shows no consistent improvement; jQuery/jQuery-UI stack unchanged in this evidence. `findings/performance.md` §6. |
| PERF-06 | Medium | Not started | **STILL OPEN** | GTM confirmed ~182 KiB / 95–285ms main-thread on every template, both strategies — unchanged, still the dominant third party. `findings/performance.md` §3. |
| PERF-07 | Medium | Not started | **PARTIALLY FIXED** | Beta.39's font consolidation (4 requests→1) reflected in data: Testing hub's font transfer is now the lowest of the 5 templates. `font-display: swap` gap still unresolved (50–190ms mobile). `findings/performance.md` §6. |
| PERF-08 | Medium | Not started | **VERIFIED FIXED** | Testing-hub-specific SI/FCP outlier (12.8s vs 3.2s) is gone; fresh data shows Testing hub SI/FCP within the same band as the other 4 templates. `findings/performance.md` §6. |
| PERF-09 | Low | Not started | **STILL OPEN** | `unsized-images` audit still fails; CLS itself remains Good today, so the latent risk is unchanged. `findings/performance.md` §6. |
| PERF-10 | Low | Not started | **STILL OPEN** | Unused JavaScript remains a consistent 105–106 KiB savings finding on every URL/strategy, unchanged. `findings/performance.md` §3. |
| PERF-11 | Low | Already complete | **VERIFIED FIXED** | TTFB 3–104ms across every run, desktop and mobile, well under the 200ms threshold. Positive finding preserved, no regression. `findings/performance.md` §2. |

### Above-the-fold / Interstitial (VIS)

| ID | Priority | Prior State (8/18) | Classification | Evidence |
|---|---|---|---|---|
| VIS-01 | Critical | Not started | **STILL OPEN** | Screenshot + DOM measurement, both 390×844 and 1440×900: `#psag-gate` bounding rect exactly equals the viewport, `position:fixed`, `z-index:9999999`, `body{overflow:hidden}`. Zero storefront content visible pre-interaction. `findings/visual-sxo.md` §1. |
| VIS-02 | High | Live code verified | **STILL OPEN** (see "three findings" section above) | Direct DOM/script inspection: `aria-describedby` absent; Exit link has no `href` (JS-only); zero `inert`/`aria-hidden` anywhere on the page; no focus-trap code. `role`, `aria-modal`, `aria-labelledby`, and attestation controls confirmed present. `findings/visual-sxo.md` §2. |
| VIS-03 | Low | Not started | **STILL OPEN** | Gate script: `var EXIT = "https://google.com"`; confirmed off-site redirect with no on-site alternative. Additionally the Exit anchor has no `href` (new evidence). `findings/visual-sxo.md` §3. |
| VIS-04 | Low | Not started | **STILL OPEN** | Computed font-size via `getComputedStyle`: gate text nodes 10.5–14px, all below the 16px guideline. `findings/visual-sxo.md` §4. |
| VIS-05 | Medium | Conflicting / validate | **STILL OPEN** | Not independently re-measured with a dedicated PSI run against gate vs. no-gate state this session; DOM evidence (full-viewport fixed gate at initial render) is consistent with the concern. `findings/visual-sxo.md` §5. |

### GEO / AI Search Readiness (GEO)

| ID | Priority | Prior State (8/18) | Classification | Evidence |
|---|---|---|---|---|
| GEO-01 | Low | Superseded / no immediate fix | **SUPERSEDED** | `llms.txt` confirmed still 404. No change; Google does not consume this file, remains correctly deprioritized. (The specialist's raw table read "STILL OPEN" — reclassified here to SUPERSEDED to preserve the original status-rule meaning: optional/not useful at current priority, unchanged since 8/18.) `findings/geo.md` §1. |
| GEO-02 | Medium | Conflicting / validate | **STILL OPEN** | `/faq/` confirmed to carry no `QAPage`/`Question` markup. No Google SERP benefit exists post-FAQ-retirement, so remains owner-discretionary, not a defect — but the underlying gap is unchanged. `findings/schema.md` §5. |
| GEO-03 | High | Not started | **STILL OPEN** | Live-fetched NAD+/GLP-2T/Glutathione description passages measured ~55–58 words each against a ~134–167 word optimal AI-citation benchmark. `findings/geo.md` §3. |
| GEO-04 | Medium | Blocked / input needed | **BLOCKED BY REAL EVIDENCE** | Homepage Organization JSON-LD has no `sameAs` key; confirmed unchanged. `findings/geo.md` §4, `findings/schema.md` §3. |
| GEO-05 | Critical | Not started | **BLOCKED BY REAL EVIDENCE** | Same Organization-entity check; zero off-site identity-graph links. Requires Paulo-approved real social/entity profiles. `findings/geo.md` §4. |
| GEO-06 | High | Not started | **PARTIALLY FIXED** | New guide substantively answers "how to read a COA" in depth (2,351 words, real batch citations); does not cover the wider PAA/comparison query cluster and uses zero question-phrased headings across 17 H2/H3s. `findings/geo.md` §5. |
| GEO-07 | Medium | Not started | **STILL OPEN** | Compound-hub pages (e.g. `/testing/nad-500-mg/`) display real batch-history data as plain text with zero `Dataset`/`ItemList` structured-data representation. `findings/schema.md` §6. |
| GEO-08 | Low | Blocked / input needed | **BLOCKED BY REAL EVIDENCE** | Guide's Article JSON-LD author is the Organization entity, not a named person; no visible byline/reviewer text. A disconnected `Person` node leaks the raw WordPress username `beringela001` into page metadata (`meta[name=author]`, Twitter card) — a hygiene issue, not a real credentialed byline. `findings/geo.md` §6. |
| GEO-09 | Low | Not started | **STILL OPEN** | Live `robots.txt` confirmed to have no named AI-crawler tokens (GPTBot, ClaudeBot, PerplexityBot, OAI-SearchBot, Google-Extended, CCBot, etc.); all fall under the wildcard allow-all. Policy decision, not a defect. `findings/technical.md` L3, `findings/geo.md` §2. |

### Search Experience (SXO)

| ID | Priority | Prior State (8/18) | Classification | Evidence |
|---|---|---|---|---|
| SXO-01 | Critical | Not started | **STILL OPEN** (see "three findings" section above) | Raw non-JS HTML confirmed to deliver full real content (hero, product data, COA evidence) before the gate's markup on all 5 pages tested; the gate is a rendering-only overlay, not server-side content withholding. Human-facing first-paint blocking remains 100% unremediated. `findings/visual-sxo.md` "SXO Findings" §. |
| SXO-02 | High | Not started | **PARTIALLY FIXED** | A new "Why Pep Select is different" homepage section exists (not present as of 8/19 checkpoints); single homepage section, no `/shop/`-level equivalent, no explicit comparison content. `findings/visual-sxo.md`. |
| SXO-03 | High | Not started | **STILL OPEN** (limited evidence) | `/faq/` confirmed to deliver real content pre-gate; specific PAA-slot coverage not individually enumerated this session. `findings/visual-sxo.md`. |
| SXO-04 | High | Not started | **STILL OPEN** | `/shop/` confirmed to deliver real catalog content pre-gate; card-level trust-signal density vs. SERP-winner patterns not directly measured this session. `findings/visual-sxo.md`. |
| SXO-05 | Medium | Not started | **STILL OPEN** (partial evidence) | On `/product/nad/`, trust/batch evidence is positioned immediately after the add-to-cart block (improvement-consistent); full ordering vs. description/compliance text not fully captured. `findings/visual-sxo.md`. |
| SXO-06 | Medium | Partially complete | **STILL OPEN** | `/testing/` confirmed to deliver real content pre-gate; current title/H1 values not extracted this session; sitemap-visibility component separately confirmed fixed (`findings/sitemap.md`). |
| SXO-07 | Medium | Blocked / input needed | **BLOCKED BY REAL EVIDENCE** | No bulk/wholesale pricing, quantity-break pricing, or wholesale CTA on any of 5 fetched pages. Genuine business-offering decision, not a code gap. `findings/visual-sxo.md`. |
| SXO-08 | Low | Not started | **STILL OPEN** | Gate confirmed to expose only two terminal actions (Enter / Exit-to-Google); no third, lower-friction path. Unchanged. `findings/visual-sxo.md`. |

### E-commerce (ECOM)

| ID | Priority | Prior State (8/18) | Classification | Evidence |
|---|---|---|---|---|
| ECOM-01 | Critical | Blocked / input needed | **BLOCKED BY REAL EVIDENCE** | Zero `aggregateRating`/`reviewCount` in JSON-LD across all 15 products; correctly not fabricated. `findings/ecommerce.md`. |
| ECOM-02 | Critical | Blocked / input needed | **BLOCKED BY REAL EVIDENCE** | 7 of 15 products (glp1-s10, glutathione, glp2-t20, bpc157-10, motsc-10, ss-31, bacteriostatic-water-30ml) have no `/testing/<compound>/` page at all; no new archive page added. `findings/ecommerce.md`. |
| ECOM-03 | High | Not started / Live implementation deployed (checkpoint) | **VERIFIED FIXED** | GLP-2T Product JSON-LD description is unique dual-receptor copy; 3 DOI citations render on-page; no supplier boilerplate found. `findings/ecommerce.md`. |
| ECOM-04 | High | Live verified | **VERIFIED FIXED** | `/testing/` renders all 8 expected product back-links; 3 sampled compound-history pages each link to the correct product, all resolve 200. `findings/ecommerce.md`. |
| ECOM-05 | High | Blocked / input needed | **BLOCKED BY REAL EVIDENCE** | Exactly 7 of 15 products remain Out of Stock; no substitute-product surfacing on any. `findings/ecommerce.md`. |
| ECOM-06 | Medium | Conflicting / validate | **STILL OPEN** | Offer schema has sku/price/availability/seller/return-policy but no GTIN, MPN, `priceValidUntil`, or `OfferShippingDetails` on any of 15 products; no fabrication. `findings/ecommerce.md`, `findings/schema.md` §2. |
| ECOM-07 | Medium | Not started | **STILL OPEN** | Product descriptions still follow the rigid templated pattern across sampled pages. `findings/ecommerce.md`, `findings/content.md` §2. |
| ECOM-08 | Medium | Partially complete | **STILL OPEN** | `/shop/` JSON-LD has no `ItemList`/`OfferCatalog` node. `findings/ecommerce.md`, `findings/schema.md` §8. |
| ECOM-09 | Low | Blocked / input needed | **BLOCKED BY REAL EVIDENCE** | No visible Merchant Center policy signal anywhere on-page; owner eligibility verification still required. `findings/ecommerce.md`. |

### Schema / Structured Data (SCHEMA)

| ID | Priority | Prior State (8/18) | Classification | Evidence |
|---|---|---|---|---|
| SCHEMA-01 | High | Live partial | **PARTIALLY FIXED** | Both product-page JSON-LD blocks confirmed exact `https://schema.org` context on a broader sample (in-stock, out-of-stock, no-description products); still two independent `<script>` blocks, cross-referenced only via shared Organization `@id`. `findings/schema.md` §1. |
| SCHEMA-02 | Medium | Live verified | **VERIFIED FIXED** | Offer.seller `@id` = `https://pepselect.com/#organization` confirmed identical across 3 sampled products of varied stock state. `findings/schema.md` §1. |
| SCHEMA-03 | Medium | Live verified | **VERIFIED FIXED** | Dataset.creator on the completed NAD+ batch report resolves to the same shared Organization `@id`. `findings/schema.md` §1, §6. |
| SCHEMA-04 | Medium | Blocked / input needed | **BLOCKED BY REAL EVIDENCE** | `sameAs` absent from Organization/OnlineStore block on all 11 sampled pages. `findings/schema.md` §3. |
| SCHEMA-05 | Medium | Blocked / input needed | **BLOCKED BY REAL EVIDENCE** | No `telephone`/`address`/`contactPoint` on Organization or `/contact/`. `findings/schema.md` §3. |
| SCHEMA-06 | Medium | Conflicting / validate | **STILL OPEN** | GTIN/MPN/`priceValidUntil`/`OfferShippingDetails`/review/rating confirmed truthfully absent, not fabricated. `findings/schema.md` §2. |
| SCHEMA-07 | Low | Superseded / no immediate fix | **SUPERSEDED** | Homepage `BreadcrumbList` still has exactly one `ListItem`; no action needed. `findings/schema.md` §4. |
| SCHEMA-08 | Low | Conflicting / validate | **SUPERSEDED** | No `FAQPage`/`Question`/`Answer` present anywhere sampled, including `/faq/` — correct given Google's 2026-05-07 FAQ rich-result retirement. `findings/schema.md` §5. |
| SCHEMA-09 | Medium | Live partial / input needed | **PARTIALLY FIXED** | Batch report `datePublished` is a real ISO-8601 WordPress timestamp; `license` confirmed still absent, no fabrication. `findings/schema.md` §6. |
| SCHEMA-10 | Low | Live verified | **VERIFIED FIXED** | Exact `"@context": "https://schema.org"` string match confirmed in every JSON-LD block on all 11 pages sampled. `findings/schema.md` §1. |

### Technical / Security / Sitemap (TECH, MAP)

| ID | Priority | Prior State (8/18) | Classification | Evidence |
|---|---|---|---|---|
| TECH-01 | High | Not started | **STILL OPEN** | `Strict-Transport-Security` absent from every response header sampled. `findings/technical.md` H1. |
| TECH-02 | Medium | Not started | **STILL OPEN** | `Content-Security-Policy` absent from every response header sampled. `findings/technical.md` M1. |
| TECH-03 | Medium | Not started | **STILL OPEN** | Neither `X-Frame-Options` nor CSP `frame-ancestors` present. `findings/technical.md` M2. |
| TECH-04 | Low | Not started | **STILL OPEN** | Neither `Referrer-Policy` nor `Permissions-Policy` present. `findings/technical.md` L1. |
| TECH-05 | Low | Not started | **PARTIALLY FIXED** | Case-variant URLs on WordPress Page routes (`/Shop/`, `/Contact/`, `/FAQ/`, `/Cart/`) still 200 without redirect, but each renders a correct lowercase canonical (mitigating, not eliminating, duplicate-indexation risk). Custom-post-type routes (Product, Testing) are correctly case-sensitive and 404. Narrower scope than originally described. `findings/technical.md` L2. |
| TECH-06 | High | Live partial | **PARTIALLY FIXED** | Mobile LCP no longer uniformly "Poor" (Home now Needs-Improvement) but remains Poor in the majority of independent runs on Shop, NAD+, Testing hub, COA report. `findings/performance.md` §6. |
| TECH-07 | Low | Superseded / no immediate fix | **SUPERSEDED** | IndexNow still not implemented; no change, optional/Bing-only scope. `findings/technical.md`, `findings/sitemap.md`. |
| MAP-01 | Medium | Evaluated / merchandising input needed | **STILL OPEN** | The "cart upsell" mitigation is confirmed via `checkout-upsell.js` source to be an AJAX toggle, not a real `<a href>` — Bacteriostatic Water has zero crawlable internal link path (sitemap-only discovery). This is stronger, more direct evidence than the original "evaluated" framing implied. `findings/technical.md` "Orphan-page risk" section. |
| MAP-02 | Low | Superseded / no immediate fix | **SUPERSEDED** | The 8 static/legal `page-sitemap.xml` entries still cluster in the same 81-second window (2026-08-14T16:45:20–16:46:41). No manufactured-freshness rewrite planned; correctly untouched. `findings/technical.md` M3, `findings/sitemap.md` §2. |
| MAP-03 | Medium | Live verified | **VERIFIED FIXED** | `/shop/page/2/` → 404; `/shop/page/1/` → 301 → `/shop/`; `/shop/` → 200. Re-confirmed by two independent specialists. `findings/technical.md`, `findings/sitemap.md` §5. |

## Limitations of this verification pass

- **DFS-02, DFS-04, DFS-06** (backlink profile/spam score, keyword difficulty, AI Overview citation) were not independently re-measured — they require paid SERP/backlink tooling (DataForSEO Labs, Ahrefs, Moz) explicitly excluded from this audit's scope. Free WebSearch spot-checks (3 queries) found no contrary evidence — pepselect.com remains absent from all sampled organic results, consistent with the GSC-confirmed zero-click reality.
- **SXO-03/04/05/06** carry partial rather than complete evidence: raw HTML was confirmed to deliver real pre-gate content on every page tested, but a full content-level read against each finding's specific original claim (exact PAA coverage, card-level trust density, full description-vs-compliance ordering, exact title/H1 text) was not completed within this session's time budget. See `findings/visual-sxo.md` "Limitations" for the itemized list.
- **CONT-12** (freshness-timestamp clustering) is out of scope for a public read-only crawl — it requires WordPress admin/revision-history data this audit does not have access to.
- **PERF-04** could not be independently confirmed or refuted — the PSI summary tooling used does not isolate individual assets (e.g., the logo) from aggregate resource-savings figures.
- No Google Rich Results Test / Search Console Enhancement report was pulled for schema validation (read-only GET scope only); classification is based on direct JSON-LD structural parsing.
- A full sitewide sweep for stray internal links to the now-404 `/about/` page was not performed (spot-checked homepage/footer only, found clean).

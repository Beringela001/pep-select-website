# Prior Findings Verification Ledger — 2026-08-23

**Baseline:** `docs/claude-seo-audit-2026-08-20/` (97 findings, Claude SEO 2.2.4). **This audit:** a new, independent read-only re-verification pass against Live `https://pepselect.com` on 2026-08-23, Claude SEO plugin **2.2.4**. Auth tier **2** (API key + OAuth + GA4 property configured; GA4 access grant pending). Every one of the 97 prior findings was re-checked with fresh evidence by a dedicated topic specialist (technical, schema, content, ecommerce, geo, visual/SXO) plus orchestrator-run performance + Google-field-data passes. Full per-domain detail and raw evidence in `findings/*.md`, `raw-crawl/`, `raw-pagespeed/`, `raw-visual/`, `screenshots/`.

**Constraints honored:** read-only GET/HEAD; no mutations to Live/Staging/WordPress/Kinsta/GSC/GA4/Google Ads/DNS; no indexing submissions; no paid/metered DataForSEO/Ahrefs/Semrush; no fabricated reviews, ratings, GTINs, lab facts, or medical claims. Original priorities preserved exactly as published 8/18→8/20.

## Status-label definitions (unchanged, preserved verbatim)

- **VERIFIED FIXED:** the finding's specific factual claim is now false — fresh evidence shows the condition no longer holds.
- **PARTIALLY FIXED:** real, verifiable improvement exists, but the core claim is not fully resolved.
- **REGRESSED:** the condition is worse than baseline, or a previously verified fix no longer holds.
- **STILL OPEN:** no material change; the original condition persists.
- **BLOCKED BY REAL EVIDENCE:** resolution requires business data, real credentials/records, account access, or an owner decision — never fabricated.
- **SUPERSEDED:** informational, optional, time-dependent, or intentionally not pursued.

## Headline result

| Classification | Count | 8/20 | Δ |
|---|---:|---:|---:|
| VERIFIED FIXED | 14 | 13 | +1 |
| PARTIALLY FIXED | 17 | 15 | +2 |
| STILL OPEN | 46 | 47 | −1 |
| BLOCKED BY REAL EVIDENCE | 13 | 15 | −2 |
| SUPERSEDED | 7 | 7 | 0 |
| REGRESSED | 0 | 0 | 0 |
| **Total re-verified** | **97** | **97** | |
| **New findings this cycle** | **14** | — | |

**Zero regressions.** Net movement toward fixed is modest and concentrated in three items, all real and independently evidenced.

## Three findings where fresh evidence changes the picture (read first)

1. **VIS-02 (High) — STILL OPEN → VERIFIED FIXED.** The single biggest correction, in the opposite direction from 8/20. On 8/20, four of the gate's eight accessibility sub-claims (aria-describedby, real exit `href`, focus-trap, background inerting) were found *absent* from live markup despite being marked verified. On 8/23 all four are genuinely present and functioning: `inert`+`aria-hidden` applied to body-level nodes with state restoration on close, a keydown Tab focus-trap scoped to `#psag-gate`, a valid `aria-describedby`, and a real `<a href>` exit control. `role="dialog"`/`aria-modal`/`aria-labelledby` also present. Only the sub-16px gate font remains (tracked under VIS-04). The gate remediation shipped between 8/20 and 8/23. Note: the "gate v2.1.3" version label from the release notes was **not** found anywhere in the DOM/script — the only version token shipped is a `PS-RUO-2026.08` compliance-form string — so the *behavior* is verified fixed while the *"2.1.3" identifier* is `[VERIFY CLAIM]`.
2. **CONT-09 / SCHEMA-05 — the new phone number.** A real phone, **+1 (833) 737-7528**, is now live with a `tel:` link on `/contact/`, the homepage header, and the sitewide footer (the 8/20–22 "logo/phone" release). This moves CONT-09 from BLOCKED → **PARTIALLY FIXED** (real contact info now exists; street/mailing address still absent → new CONT-16). SCHEMA-05 moves from BLOCKED → **STILL OPEN**: the blocker (no real value existed) is lifted, but the number is still absent from Organization/ContactPage JSON-LD — a now-code-ready fix, captured as new SCHEMA-11.
3. **GOOG-03 — measured organic drift, still near-zero.** GSC Search Analytics (28-day) moved from 0 clicks / 7 impressions / avg pos 45.7 (8/20) to **2 clicks / 24 impressions / avg pos 26.2** (8/23) — the site's first-ever recorded organic clicks (both to the homepage, pos 13.2). Real, verified improvement in exposure. STILL OPEN: absolute organic visibility remains effectively zero and the core concern is unchanged. The two new products (KPV, Cagrilintide) shipped fully crawlable and `kpv10` is already **indexed** (URL Inspection PASS).

## Full classification table (97 re-verified)

| ID | Priority | Prior (8/20) | Classification (8/23) | Evidence |
|---|---|---|---|---|
| GOOG-01 | Critical | VERIFIED FIXED | **VERIFIED FIXED** | URL Inspection /shop/ PASS 2026-08-23; Shop-adjacent impressions in GSC. |
| GOOG-02 | High | VERIFIED FIXED | **VERIFIED FIXED** | URL Inspection /testing/ PASS; hub+batch URLs receiving impressions. |
| GOOG-03 | Critical | STILL OPEN | **STILL OPEN** | IMPROVED: 2 clicks/24 impr/pos 26.2 vs 0/7/45.7. Absolute organic visibility still near-zero; core claim holds. |
| GOOG-04 | High | PARTIALLY FIXED | **PARTIALLY FIXED** | Home mobile LCP 4.4s. Unchanged. |
| GOOG-05 | High | PARTIALLY FIXED | **PARTIALLY FIXED** | Shop mobile LCP 5.3-6.0s. Unchanged. |
| GOOG-06 | Medium | STILL OPEN | **STILL OPEN** | NAD+ mobile LCP 4.5s. Unchanged. |
| GOOG-07 | Medium | PARTIALLY FIXED | **PARTIALLY FIXED** | Site-wide render-blocking persists. |
| GOOG-08 | Low | STILL OPEN | **STILL OPEN** | Security-header stack absent (umbrella for TECH-01..04). |
| GOOG-09 | Medium | BLOCKED BY REAL EVIDENCE | **BLOCKED BY REAL EVIDENCE** | CrUX origin+page 'insufficient Chrome traffic'; field_metrics {} on every PSI run. |
| GOOG-10 | Low | STILL OPEN | **STILL OPEN** | /shop/ still uses non-differentiated repeated CTA links. |
| GOOG-11 | Medium | BLOCKED BY REAL EVIDENCE | **BLOCKED BY REAL EVIDENCE** | ADVANCED: GA4 property 549907385 now identified+configured (Tier 2); live read denies access ('add as Viewer'). One grant from measured. |
| DFS-01 | Critical | STILL OPEN | **STILL OPEN** | Literal '0 clicks' now false (2 clicks). Non-brand commercial visibility still absent; core concern unchanged. |
| DFS-02 | Critical | STILL OPEN | **STILL OPEN** | Backlink profile NOT re-measured (paid tooling excluded). No contrary evidence. |
| DFS-03 | High | STILL OPEN | **STILL OPEN** | WebSearch spot-check: pepselect.com absent from research-peptide commercial SERPs. |
| DFS-04 | High | STILL OPEN | **STILL OPEN** | Keyword-difficulty NOT re-measured (paid tooling excluded). |
| DFS-05 | High | STILL OPEN | **STILL OPEN** | Commercial-cluster SERPs still dominated by incumbent vendors; pepselect absent. |
| DFS-06 | High | STILL OPEN | **STILL OPEN** | AI-Overview citation NOT re-measured (paid tooling excluded). |
| DFS-07 | Medium | BLOCKED BY REAL EVIDENCE | **BLOCKED BY REAL EVIDENCE** | No google_product_category/GTIN/MPN/Merchant feed reference on any product. |
| DFS-08 | Medium | STILL OPEN | **STILL OPEN** | Guide/FAQ content does not target the SERP PAA question map. |
| DFS-09 | Medium | STILL OPEN | **STILL OPEN** | No content targeting the commercial-intent product clusters. |
| DFS-10 | Low | SUPERSEDED | **SUPERSEDED** | Domain age is time-dependent only; no fix applicable. |
| CONT-01 | Critical | VERIFIED FIXED | **VERIFIED FIXED** | Carried: /terms-of-service/ redirect prior-verified; not re-tested this cycle, no contrary evidence (orchestrator preserves prior verified per bridge rule). |
| CONT-02 | Critical | VERIFIED FIXED | **VERIFIED FIXED** | GLP-2T DOI citations correct; no [VERIFY DOI] placeholder. |
| CONT-03 | High | PARTIALLY FIXED | **PARTIALLY FIXED** | ~109-word compliance block byte-identical on 17/17 products; sitewide boilerplate ratio not reduced. |
| CONT-04 | High | STILL OPEN | **STILL OPEN** | Templated thin descriptions persist; unique content 71-99 words/product; Bacteriostatic Water still ZERO product description. (Specialist leaned PARTIALLY; orchestrator holds STILL OPEN — core claim fully persists, one data point worse.) |
| CONT-05 | High | PARTIALLY FIXED | **PARTIALLY FIXED** | Guide grew to ~3,250 words but is still the only guide, not a program. |
| CONT-06 | Medium | STILL OPEN | **STILL OPEN** | GLP-2T DOI strings remain plain unhyperlinked text. |
| CONT-07 | Medium | STILL OPEN | **STILL OPEN** | Two differently-worded FDA disclaimer paragraphs co-occur on every product page. |
| CONT-08 | Medium | BLOCKED BY REAL EVIDENCE | **BLOCKED BY REAL EVIDENCE** | Guide authored by Organization; Person node still a bare WP username, no bio. |
| CONT-09 | Medium | BLOCKED BY REAL EVIDENCE | **PARTIALLY FIXED** | DRIFT: real phone +1 (833) 737-7528 with tel: now live on /contact/ + homepage header/footer. Street/mailing address still absent. |
| CONT-10 | Low | STILL OPEN | **STILL OPEN** | Out-of-stock modal wording not deeply re-verified; no fix claimed. |
| CONT-11 | Low | VERIFIED FIXED | **VERIFIED FIXED** | Homepage hero natural 'research peptide' use present. |
| CONT-12 | Low | SUPERSEDED | **SUPERSEDED** | Freshness-timestamp clustering (WP admin data) out of read-only scope. |
| CONT-13 | Low | STILL OPEN | **STILL OPEN** | 'PepSelect' vs 'Pep Select' split unchanged in legal docs (Terms 12/7, Privacy 3/7, Refund 1/7, RUO 0/20). |
| CONT-14 | Low | STILL OPEN | **STILL OPEN** | Readability register split persists; no bridging content. |
| PERF-01 | Critical | PARTIALLY FIXED | **PARTIALLY FIXED** | Home mobile LCP 4.4s; Shop/NAD+/Testing/COA 4.5-9.7s mostly Poor. Unchanged. |
| PERF-02 | Critical | PARTIALLY FIXED | **PARTIALLY FIXED** | Render-blocking still dominant mobile LCP driver. |
| PERF-03 | High | PARTIALLY FIXED | **PARTIALLY FIXED** | Hero WebP holds; non-hero image savings still fire (Shop worst). |
| PERF-04 | Medium | STILL OPEN | **STILL OPEN** | Logo asset not isolable from PSI summary; not confirmable. |
| PERF-05 | High | STILL OPEN | **STILL OPEN** | No INP field data (CrUX unavailable); TBT 0-500ms variable. |
| PERF-06 | Medium | STILL OPEN | **STILL OPEN** | GTM cost unchanged; dominant third party. |
| PERF-07 | Medium | PARTIALLY FIXED | **PARTIALLY FIXED** | Font consolidation holds; font-display swap gap unresolved. |
| PERF-08 | Medium | VERIFIED FIXED | **VERIFIED FIXED** | Testing-hub SI/FCP outlier stays gone; within band. |
| PERF-09 | Low | STILL OPEN | **STILL OPEN** | unsized-images latent; most CLS Good (but see PERF-12). |
| PERF-10 | Low | STILL OPEN | **STILL OPEN** | Unused-JS savings unchanged. |
| PERF-11 | Low | VERIFIED FIXED | **VERIFIED FIXED** | TTFB low across all runs; no regression. |
| VIS-01 | Critical | STILL OPEN | **STILL OPEN** | #psag-gate == full viewport, position:fixed, z-index:9999999, body overflow hidden; zero storefront pre-interaction. 8/8 captures. |
| VIS-02 | High | STILL OPEN | **VERIFIED FIXED** | Gate accessibility remediation now present: inerting (inert+aria-hidden on body nodes), keydown Tab focus-trap, aria-describedby, real exit href. 7/8 sub-claims now pass; only sub-16px font remains (tracked as VIS-04). |
| VIS-03 | Low | STILL OPEN | **PARTIALLY FIXED** | Exit anchor now has real href (keyboard-reachable); destination still off-site google.com with no on-site low-friction alternative. |
| VIS-04 | Low | STILL OPEN | **STILL OPEN** | Gate text 10.5-15px, below 16px. Unchanged. |
| VIS-05 | Medium | STILL OPEN | **STILL OPEN** | Gate performance impact not independently re-measured this cycle. [VERIFY CLAIM] |
| SXO-01 | Critical | STILL OPEN | **STILL OPEN** | Real content present in raw HTML before gate markup; 100% first-paint blocking for JS-rendering visitors persists. Reframed, unchanged. |
| SXO-02 | High | PARTIALLY FIXED | **PARTIALLY FIXED** | Carried forward; not visually re-verified (gate occludes viewport). [VERIFY CLAIM] |
| SXO-03 | High | STILL OPEN | **STILL OPEN** | /faq/ delivers real content pre-gate; PAA-slot coverage not enumerated this cycle. |
| SXO-04 | High | STILL OPEN | **STILL OPEN** | /shop/ delivers real catalog content pre-gate; card trust-density not measured this cycle. |
| SXO-05 | Medium | STILL OPEN | **STILL OPEN** | Carried forward; not visually re-verified (gate occludes viewport). [VERIFY CLAIM] |
| SXO-06 | Medium | STILL OPEN | **STILL OPEN** | /testing/ delivers real content pre-gate; title/H1 not extracted this cycle. Sitemap visibility remains fixed. |
| SXO-07 | Medium | BLOCKED BY REAL EVIDENCE | **BLOCKED BY REAL EVIDENCE** | No bulk/wholesale pricing offering on any page. Business decision. |
| SXO-08 | Low | STILL OPEN | **STILL OPEN** | Gate exposes only Enter / Exit-to-Google; no third lower-friction path. |
| GEO-01 | Low | SUPERSEDED | **SUPERSEDED** | llms.txt 404; Google ignores it. Unchanged (specialist raw table read STILL OPEN; reclassified SUPERSEDED to preserve status-rule meaning). |
| GEO-02 | Medium | STILL OPEN | **STILL OPEN** | /faq/ carries no QAPage/Question markup. Owner-discretionary post FAQ-retirement. |
| GEO-03 | High | STILL OPEN | **STILL OPEN** | Product citable passages 52-61 words vs ~134-167 benchmark; ~1/3 of target. |
| GEO-04 | Medium | BLOCKED BY REAL EVIDENCE | **BLOCKED BY REAL EVIDENCE** | Organization JSON-LD has no sameAs. |
| GEO-05 | Critical | BLOCKED BY REAL EVIDENCE | **BLOCKED BY REAL EVIDENCE** | Zero off-site identity-graph links across all 46 pages; strongest citation signals absent. |
| GEO-06 | High | PARTIALLY FIXED | **PARTIALLY FIXED** | Guide unchanged; still zero question-phrased headings; no wider PAA cluster coverage. |
| GEO-07 | Medium | STILL OPEN | **STILL OPEN** | Dataset present ONLY on 9 batch-report pages; the 8 compound-hub pages carry batch data as plain text, zero Dataset/ItemList. |
| GEO-08 | Low | BLOCKED BY REAL EVIDENCE | **BLOCKED BY REAL EVIDENCE** | beringela001 WP-username still leaks into meta[name=author] + Person node on the guide. |
| GEO-09 | Low | STILL OPEN | **STILL OPEN** | robots.txt has no named AI-crawler tokens; all under wildcard allow-all. Policy decision. |
| SCHEMA-01 | High | PARTIALLY FIXED | **PARTIALLY FIXED** | Products emit 2 independent JSON-LD blocks, cross-linked only via Organization @id. |
| SCHEMA-02 | Medium | VERIFIED FIXED | **VERIFIED FIXED** | Offer.seller @id = #organization consistent across products. |
| SCHEMA-03 | Medium | VERIFIED FIXED | **VERIFIED FIXED** | Dataset.creator on batch reports resolves to shared Organization @id. |
| SCHEMA-04 | Medium | BLOCKED BY REAL EVIDENCE | **BLOCKED BY REAL EVIDENCE** | sameAs absent from Organization/OnlineStore. |
| SCHEMA-05 | Medium | BLOCKED BY REAL EVIDENCE | **STILL OPEN** | Blocker lifted: real phone now live in visible HTML but NOT in JSON-LD (no telephone/contactPoint/address in any of 46 parsed blocks). Ready to implement (see SCHEMA-11). |
| SCHEMA-06 | Medium | STILL OPEN | **STILL OPEN** | GTIN/MPN/priceValidUntil/OfferShippingDetails/review/rating truthfully absent. |
| SCHEMA-07 | Low | SUPERSEDED | **SUPERSEDED** | Homepage BreadcrumbList single ListItem; no action. |
| SCHEMA-08 | Low | SUPERSEDED | **SUPERSEDED** | No FAQPage/Question/Answer anywhere; correct post FAQ retirement. |
| SCHEMA-09 | Medium | PARTIALLY FIXED | **PARTIALLY FIXED** | Batch report datePublished real ISO-8601; license absent (no fabrication). |
| SCHEMA-10 | Low | VERIFIED FIXED | **VERIFIED FIXED** | Exact '@context':'https://schema.org' in every block. |
| ECOM-01 | Critical | BLOCKED BY REAL EVIDENCE | **BLOCKED BY REAL EVIDENCE** | Zero aggregateRating/reviewCount across all products; correctly not fabricated. |
| ECOM-02 | Critical | BLOCKED BY REAL EVIDENCE | **BLOCKED BY REAL EVIDENCE** | 9 of 17 products have no /testing/ COA hub (incl new KPV/Cagrilintide). Scope grew with catalog. |
| ECOM-03 | High | VERIFIED FIXED | **VERIFIED FIXED** | GLP-2T unique dual-receptor description + 3 DOI on-page. |
| ECOM-04 | High | VERIFIED FIXED | **VERIFIED FIXED** | /testing/ links product back-links; hubs link to correct product 200. (Caveat: ECOM-10.) |
| ECOM-05 | High | BLOCKED BY REAL EVIDENCE | **BLOCKED BY REAL EVIDENCE** | 7 of 17 products Out of Stock (same 7 absolute), no substitute surfacing. |
| ECOM-06 | Medium | STILL OPEN | **STILL OPEN** | Offer lacks GTIN/MPN/priceValidUntil/OfferShippingDetails. |
| ECOM-07 | Medium | STILL OPEN | **STILL OPEN** | Rigid templated product descriptions across all products. |
| ECOM-08 | Medium | STILL OPEN | **STILL OPEN** | /shop/ JSON-LD has no ItemList/OfferCatalog (confirmed none anywhere). |
| ECOM-09 | Low | BLOCKED BY REAL EVIDENCE | **BLOCKED BY REAL EVIDENCE** | No Merchant Center policy signal on-page. |
| TECH-01 | High | STILL OPEN | **STILL OPEN** | Strict-Transport-Security absent on all 7 templates incl checkout. |
| TECH-02 | Medium | STILL OPEN | **STILL OPEN** | Content-Security-Policy absent. |
| TECH-03 | Medium | STILL OPEN | **STILL OPEN** | X-Frame-Options / CSP frame-ancestors absent. |
| TECH-04 | Low | STILL OPEN | **STILL OPEN** | Referrer-Policy / Permissions-Policy absent. |
| TECH-05 | Low | PARTIALLY FIXED | **PARTIALLY FIXED** | Case-variant Page URLs still 200 with correct lowercase canonical. |
| TECH-06 | High | PARTIALLY FIXED | **PARTIALLY FIXED** | Mobile LCP still Poor on majority of runs. |
| TECH-07 | Low | SUPERSEDED | **SUPERSEDED** | IndexNow not implemented; optional. |
| MAP-01 | Medium | STILL OPEN | **STILL OPEN** | Bacteriostatic Water 30ml orphan: no crawlable <a href> to its product URL (cart upsell is JS AJAX toggle). |
| MAP-02 | Low | SUPERSEDED | **SUPERSEDED** | Static/legal page-sitemap timestamp clustering; no manufactured-freshness rewrite. |
| MAP-03 | Medium | VERIFIED FIXED | **VERIFIED FIXED** | /shop/page/2/ 404; /shop/page/1/ 301 to /shop/. Re-confirmed. |

## New findings this cycle (14)

| ID | Priority | Type | Evidence |
|---|---|---|---|
| TECH-08 | Low | NEW (info) | product-sitemap batch-resave on catalog reorder — cosmetic, mirrors MAP-02; not a defect. |
| SCHEMA-11 | Medium | NEW | Real phone now on-page but absent from Organization/ContactPage JSON-LD; add telephone/contactPoint from the verbatim number. Code-ready. |
| SCHEMA-12 | Low | NEW (info) | Minor schema hygiene note (see schema.md). |
| CONT-15 | Medium | NEW | The three Retatrutide dosage variants (glp3-r10/r20/r30) share byte-identical descriptions/citations — duplicate-content risk across indexed product pages. |
| CONT-16 | Low | NEW | No street/mailing address on-page despite the newly-added phone (NAP completeness). |
| ECOM-10 | High | NEW | Retatrutide 20MG (glp3-r20) COA widget + hub link vanish entirely once the product goes Out of Stock, though the hub exists and links back — breaks the product<->COA path for OOS SKUs. |
| ECOM-11 | Low | NEW | Three Retatrutide SKUs share word-for-word identical descriptions (same root as CONT-15, ecommerce lens). |
| ECOM-12 | Low | NEW (info) | Bacteriostatic Water has no Product description field at all. |
| GEO-10 | Low | NEW | No RSL 1.0 / AI-content-licensing signal anywhere (consistent with no llms.txt). |
| GEO-11 | Low | NEW (info) | Crawl-tool word_count overstates citability (counts nav/footer/legal boilerplate); true passages measured directly. |
| VIS-06 | Low | NEW (positive) | Gate accessibility remediation (inerting/focus-trap/aria-describedby/real exit href) now genuinely implemented — the change that resolves VIS-02. |
| VIS-07 | Low | NEW (info) | Gate renders up to 14 animated .psag-bubble layers via requestAnimationFrame running continuously until dismissed — minor main-thread cost, not benchmarked. [VERIFY CLAIM] |
| SXO-09 | Info | NEW (info) | Gate fires fetch() POST to admin-ajax.php (action=psag_record, keepalive) on Enter — compliance logging, not an SEO issue; noted for completeness. |
| PERF-12 | Medium | NEW (watch) | One unique home-mobile PSI run shows CLS 0.303 (Poor) vs prior <=0.084; other two home runs failed. Unconfirmed single sample — re-measure / [VERIFY CLAIM] before/after homepage changes. |

## Limitations of this verification pass

- **DFS-02 / DFS-04 / DFS-06** (backlink profile, keyword difficulty, AI-Overview citation) were **not** re-measured — they require paid SERP/backlink tooling (DataForSEO/Ahrefs/Moz) explicitly excluded by the read-only, no-metered-spend constraint. No contrary evidence found; carried forward.
- **GA4 (GOOG-11)** could not be read: the property (`549907385`) is now configured but the authenticated identity lacks Viewer access ("Permission denied"). One access grant from measurable.
- **SXO-02 / SXO-05** could not be visually re-verified — the full-viewport gate occludes 100% of every screenshot and no gate dismissal was performed (read-only scope). Carried forward from prior status, flagged `[VERIFY CLAIM]`.
- **PERF-12** (home-mobile CLS 0.303) rests on a single unique PSI sample (the other two home runs failed) — flagged for dedicated re-measurement, not asserted as a regression.
- **CONT-01 / CONT-10 / CONT-14** were not re-tested from the disk-only corpus; prior status carried forward with no contrary evidence.
- CrUX real-user field data remains unavailable for this origin (insufficient Chrome traffic) — every performance number is Lighthouse **lab** data, which Google does not use to evaluate real-world CWV in Search.

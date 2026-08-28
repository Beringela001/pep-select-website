# Prior Findings Verification Ledger — 2026-08-28

**Baseline:** `docs/claude-seo-audit-2026-08-23/` (97 original findings + 14 added 8/23 = **111**, Claude SEO 2.2.4). **This audit:** new, independent read-only re-verification against Live `https://pepselect.com` on 2026-08-28, Claude SEO plugin **2.2.4**, auth tier **2** (GA4 grant still pending). Eleven parallel topic specialists plus orchestrator-run curl verification of every 8/23 code-ready item. Raw evidence in `findings/*.md`, `raw-crawl/`, `raw-pagespeed/`, `screenshots/`.

**Constraints honored:** read-only GET/HEAD; no mutations to Live/Staging/WordPress/Kinsta/GSC/GA4/DNS; no indexing submissions; no paid/metered DataForSEO/Ahrefs/Moz; no fabricated reviews, ratings, GTINs, lab facts, or medical claims. Original priorities preserved.

## Status-label definitions (unchanged)

- **VERIFIED FIXED:** the finding's specific factual claim is now false.
- **PARTIALLY FIXED:** real, verifiable improvement; core claim not fully resolved.
- **REGRESSED:** worse than baseline, or a verified fix no longer holds.
- **STILL OPEN:** no material change.
- **BLOCKED BY REAL EVIDENCE:** needs business data, real records, account access, or an owner decision — never fabricated.
- **SUPERSEDED:** informational, optional, time-dependent, or intentionally not pursued.

## Headline result (all 111)

| Classification | 8/28 | 8/23 (97 + 14 new) | Δ |
|---|---:|---:|---:|
| VERIFIED FIXED | 15 | 14 | +1 (ECOM-10) |
| PARTIALLY FIXED | 19 | 17 | +2 (ECOM-02, CONT-15, ECOM-11; GOOG-05 out) |
| STILL OPEN | 50 | 46 + 4 new | 0 net |
| BLOCKED BY REAL EVIDENCE | 12 | 13 | −1 (ECOM-02 → PF) |
| SUPERSEDED / informational | 14 | 7 + 7 info | 0 (PERF-12 not reproduced → SUPERSEDED) |
| REGRESSED | 1 | 0 | +1 (GOOG-05, lab n=2) |
| **Total re-verified** | **111** | **111** | |
| **New findings this cycle** | **8** | — | |

## Findings where fresh evidence changes the picture (read first)

1. **ECOM-02 (Critical) — BLOCKED → PARTIALLY FIXED.** 8/23: 9 of 17 products had no `/testing/` COA hub. 8/28: hubs exist for glutathione, bpc-157, kpv, mots-c, ss-31, cagrilintide (6 new; 15 hubs total), 9 new batch reports, and **14 of 17 product pages link to a hub or batch report**. Remaining without any COA path: `glp1-s10`, `glp2-t20`, `bacteriostatic-water-30ml`.
2. **ECOM-10 (High) — NEW 8/23 → VERIFIED FIXED.** `/product/glp3-r20/` is still out of stock and now renders `href="https://pepselect.com/testing/retatrutide-20mg/"`. The product↔COA path survives OOS.
3. **GOOG-05 (High) — PARTIALLY FIXED → REGRESSED (lab).** `/shop/` mobile LCP 9.9–10.0 s (n=2) vs 5.3–6.0 s on 8/23. Home mobile spread 4.6–9.8 s. Lab variance is large; confirm with three more runs before treating as a production regression.
4. **PERF-12 (watch) — SUPERSEDED.** Home mobile CLS 0.081 / 0.084 / 0.084 across three runs; the 8/23 single 0.303 sample did not reproduce.
5. **CONT-15 / ECOM-11 — PARTIALLY FIXED.** Meta descriptions on glp3-r10/r20/r30 are now distinct (three different hashes); the "Description" paragraph opening is still byte-identical and main-content similarity is 0.70–0.74. Body copy still needs differentiation.
6. **GOOG-03 / DFS-01 — STILL OPEN, improved again.** 9 clicks / 85 impressions (28-day) vs 2 / 24. Non-brand commercial queries still 0 clicks at positions 63–94.

## Full classification table (111 re-verified)

| ID | Priority | 8/23 | **8/28** | Evidence |
|---|---|---|---|---|
| GOOG-01 | Critical | VERIFIED FIXED | **VERIFIED FIXED** | `/` and both inspected products "Submitted and indexed", canonical match; Shop-adjacent impressions. |
| GOOG-02 | High | VERIFIED FIXED | **VERIFIED FIXED** | Batch report `/testing/retatrutide-20mg/psrt2062926jp/` recorded 1 click at pos 5.0. |
| GOOG-03 | Critical | STILL OPEN | **STILL OPEN** | IMPROVED: 9 clicks / 85 impr / pos 38.2 (was 2/24/26.2). Core claim (near-zero organic) holds. |
| GOOG-04 | High | PARTIALLY FIXED | **PARTIALLY FIXED** | Home mobile LCP 4.6–9.8 s (3 runs). |
| GOOG-05 | High | PARTIALLY FIXED | **REGRESSED (lab)** | Shop mobile LCP 9.9–10.0 s (n=2) vs 5.3–6.0 s. `[VERIFY CLAIM]` — re-run 3×. |
| GOOG-06 | Medium | STILL OPEN | **STILL OPEN** | NAD not measured; product template (ghk-cu) 5.4–10.9 s. |
| GOOG-07 | Medium | PARTIALLY FIXED | **PARTIALLY FIXED** | Render-blocking 2,080 ms savings (home mobile). |
| GOOG-08 | Low | STILL OPEN | **STILL OPEN** | Header stack absent (umbrella TECH-01..04). |
| GOOG-09 | Medium | BLOCKED | **BLOCKED BY REAL EVIDENCE** | CrUX + CrUX History: "No CrUX data for this origin" 2026-08-28. |
| GOOG-10 | Low | STILL OPEN | **STILL OPEN** | PSI SEO audit: "Links do not have descriptive text — 4 links" on home. |
| GOOG-11 | Medium | BLOCKED | **BLOCKED BY REAL EVIDENCE** | `Permission denied for property '549907385'` verbatim 2026-08-28. |
| DFS-01 | Critical | STILL OPEN | **STILL OPEN** | 9 clicks; non-brand queries all 0 clicks, pos 63–94. |
| DFS-02 | Critical | STILL OPEN | **STILL OPEN** | Common Crawl (cc-main-2026-jan-feb-mar): domain not in crawl, no rank. Tier 0 only; no paid re-measure. Domain created 2026-05-31. |
| DFS-03 | High | STILL OPEN | **STILL OPEN** | SXO WebSearch: absent from "research peptides", "buy research peptides with COA", "TB-500 peptide for sale", "is pepselect legit reddit". |
| DFS-04 | High | STILL OPEN | **STILL OPEN** | Not re-measured (paid). |
| DFS-05 | High | STILL OPEN | **STILL OPEN** | Commercial SERPs 100% incumbent vendor pages; pepselect absent. |
| DFS-06 | High | STILL OPEN | **STILL OPEN** | Not re-measured (paid). |
| DFS-07 | Medium | BLOCKED | **BLOCKED BY REAL EVIDENCE** | GSC URL Inspection on glp3-r30: "missing global identifier (gtin/brand)". No feed. |
| DFS-08 | Medium | STILL OPEN | **STILL OPEN** | One guide; no PAA-targeted content. |
| DFS-09 | Medium | STILL OPEN | **STILL OPEN** | No commercial-cluster content. |
| DFS-10 | Low | SUPERSEDED | **SUPERSEDED** | Domain age (~3 months) time-dependent. |
| CONT-01 | Critical | VERIFIED FIXED | **VERIFIED FIXED** | Carried; no contrary evidence. |
| CONT-02 | Critical | VERIFIED FIXED | **VERIFIED FIXED** | GLP-2T: 3 DOI strings present, no `[VERIFY DOI]`. |
| CONT-03 | High | PARTIALLY FIXED | **PARTIALLY FIXED** | Compliance/liability block still embedded in product descriptions (glutathione dilution-refund clause). |
| CONT-04 | High | STILL OPEN | **STILL OPEN** | Templated thin descriptions persist; Bac Water still no description (carried ECOM-12). |
| CONT-05 | High | PARTIALLY FIXED | **PARTIALLY FIXED** | Still exactly one guide in `post-sitemap.xml`. |
| CONT-06 | Medium | STILL OPEN | **STILL OPEN** | glp2-t20: 3 DOI strings, 0 `href="https://doi.org/…"`. |
| CONT-07 | Medium | STILL OPEN | **STILL OPEN** | Carried; not re-verified. |
| CONT-08 | Medium | BLOCKED | **BLOCKED BY REAL EVIDENCE** | No author bylines or credentials on home, products, guide. |
| CONT-09 | Medium | PARTIALLY FIXED | **PARTIALLY FIXED** | Phone +1 (833) 737-7528 visible on home; no address. |
| CONT-10 | Low | STILL OPEN | **STILL OPEN** | Carried. |
| CONT-11 | Low | VERIFIED FIXED | **VERIFIED FIXED** | Carried. |
| CONT-12 | Low | SUPERSEDED | **SUPERSEDED** | Carried. |
| CONT-13 | Low | STILL OPEN | **STILL OPEN** | "PepSelect": Terms 13, Privacy 4, Refund 2 instances (counts drifted up, not down). |
| CONT-14 | Low | STILL OPEN | **STILL OPEN** | Carried. |
| PERF-01 | Critical | PARTIALLY FIXED | **PARTIALLY FIXED** | Mobile LCP 4.6–10.9 s across three templates; mostly Poor. Worse than 8/23 on lab. |
| PERF-02 | Critical | PARTIALLY FIXED | **PARTIALLY FIXED** | Render-blocking still dominant (2,080 ms home mobile; 390 ms desktop). |
| PERF-03 | High | PARTIALLY FIXED | **PARTIALLY FIXED** | "Improve image delivery" 202 KiB mobile / 369 KiB desktop (home). |
| PERF-04 | Medium | STILL OPEN | **STILL OPEN** | Carried. |
| PERF-05 | High | STILL OPEN | **STILL OPEN** | No INP field data; home desktop TBT 1,770 ms (see PERF-14). |
| PERF-06 | Medium | STILL OPEN | **STILL OPEN** | GTM present; AdSense chain also present (new PERF-13). |
| PERF-07 | Medium | PARTIALLY FIXED | **PARTIALLY FIXED** | Font-display 70–80 ms savings still flagged. |
| PERF-08 | Medium | VERIFIED FIXED | **VERIFIED FIXED** | Carried (testing hub not measured). |
| PERF-09 | Low | STILL OPEN | **STILL OPEN** | CLS Good on all runs (0–0.084); unsized-image risk latent. |
| PERF-10 | Low | STILL OPEN | **STILL OPEN** | Unused JS 255–257 KiB. |
| PERF-11 | Low | VERIFIED FIXED | **VERIFIED FIXED** | Desktop FCP 0.7–0.8 s; TTFB not flagged. |
| PERF-12 | Medium | NEW (watch) | **SUPERSEDED** | Not reproduced: home mobile CLS 0.081/0.084/0.084. |
| VIS-01 | Critical | STILL OPEN | **STILL OPEN** | `#psag-gate` `role="dialog" aria-modal="true"`; visual specialist: 100% of above-fold hidden on home + PDP, desktop + mobile. |
| VIS-02 | High | VERIFIED FIXED | **VERIFIED FIXED** | `aria-labelledby`/`aria-describedby`/`aria-modal` present in markup. |
| VIS-03 | Low | PARTIALLY FIXED | **PARTIALLY FIXED** | Carried. Gate now offers "I Agree" / "Under 21". |
| VIS-04 | Low | STILL OPEN | **STILL OPEN** | Carried; visual specialist reports base font 16 px on page, gate text not re-measured. |
| VIS-05 | Medium | STILL OPEN | **STILL OPEN** | `[VERIFY CLAIM]` carried. |
| SXO-01 | Critical | STILL OPEN | **STILL OPEN** | Content in raw HTML; first paint fully blocked. |
| SXO-02 | High | PARTIALLY FIXED | **PARTIALLY FIXED** | Carried `[VERIFY CLAIM]`. |
| SXO-03 | High | STILL OPEN | **STILL OPEN** | Carried. |
| SXO-04 | High | STILL OPEN | **STILL OPEN** | Persona scoring: comparison shopper 54 (home) / 47 (PDP) — no price-per-mg, no cross-sell, scarcity copy. |
| SXO-05 | Medium | STILL OPEN | **STILL OPEN** | Carried. |
| SXO-06 | Medium | STILL OPEN | **STILL OPEN** | Carried. |
| SXO-07 | Medium | BLOCKED | **BLOCKED BY REAL EVIDENCE** | No bulk pricing offering. |
| SXO-08 | Low | STILL OPEN | **STILL OPEN** | Gate paths: agree / under-21 exit only. |
| GEO-01 | Low | SUPERSEDED | **SUPERSEDED** | `/llms.txt` 404. |
| GEO-02 | Medium | STILL OPEN | **STILL OPEN** | No QAPage on `/faq/`. (GEO specialist's FAQPage suggestion rejected per quality gate.) |
| GEO-03 | High | STILL OPEN | **STILL OPEN** | Product passages 30–60 words vs 134–167 benchmark. |
| GEO-04 | Medium | BLOCKED | **BLOCKED BY REAL EVIDENCE** | No `sameAs`. |
| GEO-05 | Critical | BLOCKED | **BLOCKED BY REAL EVIDENCE** | Zero off-site identity links; SXO "legit reddit" query returns nothing. |
| GEO-06 | High | PARTIALLY FIXED | **PARTIALLY FIXED** | Carried. |
| GEO-07 | Medium | STILL OPEN | **STILL OPEN** | `"@type":"Dataset"` count = 0 on nad-500-mg, tb-500-10-mg, bpc-157-10-mg hubs. |
| GEO-08 | Low | BLOCKED | **BLOCKED BY REAL EVIDENCE** | `meta[name=author]="beringela001"` still present on the guide (hygiene sub-fix is code-ready). |
| GEO-09 | Low | STILL OPEN | **STILL OPEN** | robots.txt: wildcard allow-all, no AI-crawler tokens. |
| SCHEMA-01 | High | PARTIALLY FIXED | **PARTIALLY FIXED** | Carried. |
| SCHEMA-02 | Medium | VERIFIED FIXED | **VERIFIED FIXED** | `Offer.seller` → `#organization`. |
| SCHEMA-03 | Medium | VERIFIED FIXED | **VERIFIED FIXED** | Carried. |
| SCHEMA-04 | Medium | BLOCKED | **BLOCKED BY REAL EVIDENCE** | No `sameAs` on OnlineStore. |
| SCHEMA-05 | Medium | STILL OPEN | **STILL OPEN** | `"telephone"` absent from `/` and `/contact/` JSON-LD. |
| SCHEMA-06 | Medium | STILL OPEN | **STILL OPEN** | GSC URL Inspection confirms missing `aggregateRating`, `review`, `shippingDetails`, `gtin`, `priceValidUntil`. |
| SCHEMA-07 | Low | SUPERSEDED | **SUPERSEDED** | — |
| SCHEMA-08 | Low | SUPERSEDED | **SUPERSEDED** | No FAQPage anywhere; correct. |
| SCHEMA-09 | Medium | PARTIALLY FIXED | **PARTIALLY FIXED** | Carried. |
| SCHEMA-10 | Low | VERIFIED FIXED | **VERIFIED FIXED** | `https://schema.org` context on all blocks. |
| ECOM-01 | Critical | BLOCKED | **BLOCKED BY REAL EVIDENCE** | Zero ratings; correctly not fabricated. |
| ECOM-02 | Critical | BLOCKED | **PARTIALLY FIXED** | 14/17 products link to COA hub/report; 15 hubs, 18 reports. Remaining: glp1-s10, glp2-t20, bac water. |
| ECOM-03 | High | VERIFIED FIXED | **VERIFIED FIXED** | GLP-2T DOIs on page. |
| ECOM-04 | High | VERIFIED FIXED | **VERIFIED FIXED** | Product→hub links verified on 14 products. |
| ECOM-05 | High | BLOCKED | **BLOCKED BY REAL EVIDENCE** | WORSENED: 10 of 17 OOS (glutathione, glp3-r20, glp1-s10, glp2-t20, bpc157-10, cag10, kpv10, motsc-10, ss-31, glp3-r30). |
| ECOM-06 | Medium | STILL OPEN | **STILL OPEN** | As SCHEMA-06. |
| ECOM-07 | Medium | STILL OPEN | **STILL OPEN** | Templated descriptions. |
| ECOM-08 | Medium | STILL OPEN | **STILL OPEN** | `/shop/` has no ItemList. |
| ECOM-09 | Low | BLOCKED | **BLOCKED BY REAL EVIDENCE** | Merchant Center status unknown; e-commerce specialist flags policy risk for injectable research peptides + no-returns policy. |
| TECH-01 | High | STILL OPEN | **STILL OPEN** | No `Strict-Transport-Security` on `/`, `/shop/`, `/product/pt-141/`, `/privacy-policy/`. 4th cycle. |
| TECH-02 | Medium | STILL OPEN | **STILL OPEN** | No CSP. |
| TECH-03 | Medium | STILL OPEN | **STILL OPEN** | No X-Frame-Options / frame-ancestors. |
| TECH-04 | Low | STILL OPEN | **STILL OPEN** | No Referrer-Policy / Permissions-Policy. |
| TECH-05 | Low | PARTIALLY FIXED | **PARTIALLY FIXED** | Carried. |
| TECH-06 | High | PARTIALLY FIXED | **PARTIALLY FIXED** | Mobile LCP Poor on all sampled runs. |
| TECH-07 | Low | SUPERSEDED | **SUPERSEDED** | IndexNow absent; technical specialist rates it High, original classification preserved (optional). |
| TECH-08 | Low | NEW (info) | **SUPERSEDED** | Informational; carried. |
| SCHEMA-11 | Medium | NEW | **STILL OPEN** | Phone not in JSON-LD (see SCHEMA-05). |
| SCHEMA-12 | Low | NEW (info) | **SUPERSEDED** | Informational; carried. |
| CONT-15 | Medium | NEW | **PARTIALLY FIXED** | Meta descriptions now unique; body "Description" paragraph identical across r10/r20/r30; main-content similarity 0.70–0.74. |
| CONT-16 | Low | NEW | **STILL OPEN** | No street/mailing address. |
| ECOM-10 | High | NEW | **VERIFIED FIXED** | OOS glp3-r20 links `/testing/retatrutide-20mg/`. |
| ECOM-11 | Low | NEW | **PARTIALLY FIXED** | Same root as CONT-15. |
| ECOM-12 | Low | NEW (info) | **STILL OPEN** | Carried; Bac Water description not re-verified. |
| GEO-10 | Low | NEW | **STILL OPEN** | No RSL/licensing signal; llms.txt 404. |
| GEO-11 | Low | NEW (info) | **SUPERSEDED** | Informational. |
| VIS-06 | Low | NEW (positive) | **SUPERSEDED** | Informational (resolved VIS-02). |
| VIS-07 | Low | NEW (info) | **SUPERSEDED** | `[VERIFY CLAIM]` carried; may contribute to PERF-14. |
| SXO-09 | Info | NEW (info) | **SUPERSEDED** | Informational. |
| MAP-01 | Medium | STILL OPEN | **STILL OPEN** | 0 links to `bacteriostatic-water-30ml` from `/shop/` and `/faq/`. |
| MAP-02 | Low | SUPERSEDED | **SUPERSEDED** | — |
| MAP-03 | Medium | VERIFIED FIXED | **VERIFIED FIXED** | Carried. |

## New findings this cycle (8)

| ID | Priority | Type | Evidence |
|---|---|---|---|
| MAP-04 | High | NEW | `https://pepselect.com/order/` (WooCommerce order page, `<meta name="robots" content="noindex, nofollow">`) is listed in `page-sitemap.xml` (lastmod 2026-08-26). GSC reports 0 sitemap errors today but noindexed sitemap URLs generate "Excluded by noindex" coverage noise and signal poor hygiene. Code-ready: exclude WC endpoint pages from the Yoast page sitemap. |
| CONT-17 | High | NEW | `/about-us/` returns 200 with `<meta name='robots' content='noindex, follow'>` on a ~7,000-word page; absent from `page-sitemap.xml`. Prior cycles logged residual impressions as "monitor". Content specialist rates the absence of an indexed About/company page the top E-E-A-T gap for a YMYL-adjacent vendor. NEEDS APPROVAL — the noindex may be intentional and the page's claims must pass marketing/compliance review before exposure. |
| SCHEMA-13 | Medium | NEW | `og:type="article"` on `/shop/` and all product pages (`/product/tb500-10/` sampled); `website` on home. Yoast default; should be `product` on PDPs and `website` on `/shop/`. Social-share rendering only; code-ready. |
| PERF-13 | Medium | NEW | `<link rel='dns-prefetch' href='//pagead2.googlesyndication.com'>` and an AdSense script chain load on the storefront alongside GTM. Third-party main-thread cost; also a policy question for a research-chemical store. `[VERIFY CLAIM]` whether intentional. |
| PERF-14 | High | NEW | Homepage **desktop** Lighthouse 59 with TBT 1,770 ms, JS execution 2.9 s, main-thread 7.0 s; `/shop/` desktop 95 and `/product/ghk-cu/` desktop 86 on the same day; 8/20 home desktop averaged 96.8. Template-specific. Coincides with the 8/26 cart-recovery exit-offer release and the gate's animated bubble layers (VIS-07). Cause `[VERIFY CLAIM]` — re-run 3× and bisect. |
| VIS-08 | Low | NEW | PSI accessibility: "Background and foreground colors do not have a sufficient contrast ratio" on home (mobile + desktop). Accessibility score 96. |
| TECH-09 | Low | NEW | PSI best-practices: "Browser errors were logged to the console" on home (mobile + desktop). Not enumerated by the API summary; capture via DevTools. |
| SXO-10 | Medium | NEW | Product title tags/meta end in generic "for Research \| Pep Select" and "price, availability, product details"; competitors' snippets already claim "99% purity / third-party tested / COA". Pep Select's real differentiator (batch-matched, dated COA with named lab) is absent from the snippet where the SERP battle is decided. NEEDS APPROVAL (copy change under `.agents/product-marketing.md`). |

## Limitations of this verification pass

- **DFS-02 / DFS-04 / DFS-06** not re-measured with paid tooling; Common Crawl absence is the only backlink evidence (Tier 0, confidence 0.50).
- **GA4 (GOOG-11)** still permission-denied.
- **Lab variance:** home mobile scored 58 then 71, 71 within minutes; the shop/home LCP regressions (GOOG-05, PERF-01) and the home-desktop drop (PERF-14) each rest on 2–3 runs and are flagged, not asserted as production regressions.
- **Not re-verified this cycle, carried with no contrary evidence:** CONT-01, CONT-07, CONT-10, CONT-14, PERF-04, PERF-08, VIS-04/05, SXO-02/03/05/06, GEO-06, SCHEMA-01/03/09, TECH-05, MAP-03, ECOM-12, TECH-08, SCHEMA-12.
- **MAP-01** checked only from `/shop/` and `/faq/`; a crawlable link elsewhere was not searched for.
- Four specialists (content, schema, performance, e-commerce) hit their turn limits and reported from partial passes; the orchestrator filled product/category PSI numbers from their saved JSON and re-ran the code-ready checks directly.
- CrUX field data unavailable; all CWV numbers are Lighthouse lab.

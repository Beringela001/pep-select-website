# INTEGRATED-03 — Strategy & Action Plan

> Part of the Pep Select integrated SEO audit — 2026-08-18 — target https://pepselect.com
> Files: INTEGRATED-00 context & scorecard · INTEGRATED-01 critical & high · INTEGRATED-02 medium, low & verified-correct · INTEGRATED-03 strategy & action plan · INTEGRATED-04 evidence & limitations
>
> This file **synthesizes and sequences**; it does not restate findings. Every action references finding IDs whose full verbatim detail (evidence, affected URLs, dependencies, failure/success checks, leading indicators) lives in INTEGRATED-01/02. Nothing here was implemented — all actions are recommendations for the owner to schedule.

---

## Synthesis — 10-principle walk (PERCEIVE → ANALYZE → VALIDATE → ACT)

### PERCEIVE

- **Observe-external:** The vertical's page-one is feature-dense and shallow (9 organic slots), won by vendors whose titles lead with verifiable trust claims ("third-party tested", "99%+ purity", "COA", "US made"). AI Overviews sit at/near position 1 on all three SERPs pulled and cite vendor content directly — including verifiedpeptides.com, which earns an AI Overview citation **without ranking organically** (DFS-05, DFS-06). Three target keywords score difficulty 0 (DFS-04). [4]
- **Observe-internal:** The site's plumbing is healthy (see Verified Correct inventory in INTEGRATED-02) and it owns the vertical's rarest asset — a batch-level COA archive with `Dataset`/`DataDownload` schema. But every fresh session on every page renders only a full-viewport research gate (VIS-01), unique product copy is thin over a 619–628-word sitewide boilerplate block (CONT-03, CONT-04), and the trust asset is orphaned from the catalog in both directions (ECOM-04, MAP-01). [5]
- **Listen (what Google is telling us, verified):** 0 clicks, 5 impressions, position 34 over 28 days; homepage indexed, `/shop/` and products "Discovered – currently not indexed", `/testing/` unknown (GOOG-01–03). "Discovered – currently not indexed" is Google explicitly saying: *we know these URLs exist and have chosen not to spend the crawl/index budget* — a demand/quality verdict, not a technical block. CrUX has no data at all [3-ineligible], so lab signals and on-page quality are the only performance evidence Google has. [1]

### ANALYZE

- **Think (first principles):** Indexation of a new domain is a function of (a) discovered demand signals — links, brand queries; (b) predicted page value — unique content, satisfied intent; (c) render economics — what the crawler gets for its budget. Pep Select currently fails (a) almost absolutely (6 backlinks, spam score 67, ~6 weeks old — DFS-02, DFS-10), is mediocre on (b) for money pages (CONT-04, ECOM-07), and taxes (c) with a gate-dominated first paint and Poor mobile LCP (VIS-01/VIS-05, PERF-01/02, GOOG-04/05).
- **Connect-lateral:** The AI Overview pathway is decoupled from organic rank in this niche (DFS-06). The COA archive is exactly the citable, data-backed content type the AIO and "Things to know" cards already cite from competitors (GEO-05, GEO-06). The same trust properties the SERP rewards in titles are the ones missing from Product schema (ECOM-06, SCHEMA-06) and from the PAA-shaped content gap (SXO-02, SXO-03, CONT-05).
- **Connect-system (the causal chain):** Authority ≈ 0 → crawl priority low → "Discovered – not indexed" → zero impressions → no CrUX eligibility → no field validation → everything downstream (rankings, AIO citations, reviews, revenue) is blocked. Two on-site multipliers deepen the hole: the gate (suppresses measured LCP and first-visit engagement) and thin unique copy (suppresses predicted page value). **Therefore off-site authority and the gate/performance fixes are the bottleneck stage; content and schema work are force multipliers that pay out only once pages are being indexed.**

### VALIDATE

- **Feel (calibration):** The temptation is to treat this as a performance audit (most findings by volume). That would be wrong: a site with 8.9 s mobile LCP *and* links *and* indexed pages would still get traffic. The scarce resource is Google's willingness to index — every week spent polishing CSS before fixing discoverability is a week of zero impressions either way.
- **Accept (falsifiability of the strategy itself):** This plan predicts that authority + gate/LCP + unique content moves pages from "Discovered" to "Indexed" within ~4–8 weeks of execution. **The strategy is falsified if**, after P0–P2 are verifiably complete and ≥10 referring domains exist, GSC still shows `/shop/` and ≥half the products unindexed after 8 further weeks — at which point the hypothesis shifts to a domain-level trust problem (spam-score neighborhood, DFS-02) and the correct next move is a backlink-source audit and possibly disavow analysis, not more content.

### ACT

- **Create:** The build-out below (phases P0–P5).
- **Grow (global leading indicators, watchable weekly in GSC without re-running any audit):** ① indexed-page count in the Pages report; ② total impressions; ③ queries with >0 impressions; ④ referring domains (any free backlink checker); ⑤ PSI mobile LCP on the homepage. Targets: impressions >100/week by +6 weeks from P0 completion; ≥10 catalog URLs indexed by +8 weeks.

---

## Prioritized Action Plan

Order within each phase = execution order. **Deps** names the finding IDs (or phases) that must land first. Every referenced ID carries its own failure check, success check and leading indicator in INTEGRATED-01/02 — those apply verbatim and are not repeated here.

### P0 — Stop-the-bleed (days 1–7). No dependencies; everything else builds on these.

| # | Action (reference) | Why first | Sequencing note |
|---|---|---|---|
| P0-1 | Restore/publish `/terms-of-service/` — **CONT-01** | Sitewide 404 referenced by the compliance gate on all 43 pages; a legal-trust contradiction on a YMYL-adjacent store | Unblocks the gate's legitimacy (P0-2) and every trust-content action in P2 |
| P0-2 | Redesign the research gate so first paint is real content — **VIS-01, SXO-01, VIS-05, SXO-08, VIS-02** | The gate is what PSI, Googlebot's renderer, and every first-time visitor measure/see; single biggest lever on measured LCP and on-SERP experience | Do before the performance sprint (P1) so P1's PSI re-measurements reflect the real page; a dismissible banner/inline consent pattern preserves the RUO compliance intent |
| P0-3 | Remove the live "[VERIFY DOI]" placeholder — **CONT-02** | Production QA artifact on YMYL-adjacent citation content; minutes to fix, disproportionate E-E-A-T damage | Pair with CONT-06 (link the DOIs) if touching the same blocks |
| P0-4 | Link `/testing/` ↔ product pages both ways; de-orphan the orphaned product — **ECOM-04, MAP-01** | Internal discovery paths are the cheapest crawl-priority signal available while authority is near zero | Feeds GOOG-01/GOOG-02 recovery; no dependency |

### P1 — Performance sprint (weeks 1–3). Deps: P0-2 (measure the real page, not the gate).

| # | Action (reference) | Sequencing note |
|---|---|---|
| P1-1 | Eliminate/defer render-blocking CSS/JS — **PERF-02, GOOG-07, PERF-05** | Largest measured cost (~2.7–3.0 s of first paint); do before image work so re-tests isolate each change |
| P1-2 | Optimize hero + logo images (format, size, priority hint) — **PERF-03, PERF-04, GOOG-04** | Homepage LCP element itself |
| P1-3 | Re-run PSI mobile+desktop on the four templates — **PERF-01, GOOG-05, GOOG-06, TECH-06** | Success checks live in those findings; target: all four templates out of Poor (<4.0 s lab mobile LCP) |
| P1-4 | Secondary: GTM main-thread cost, fonts, unused bytes, image dimensions — **PERF-06, PERF-07, PERF-08, PERF-09, PERF-10** | Only after P1-1..3 verify; diminishing returns tier |

### P2 — Trust & content build (weeks 2–6). Deps: P0-1 (legal page live), P0-3.

| # | Action (reference) | Sequencing note |
|---|---|---|
| P2-1 | Publish the four PAA-answering trust pages (most-trusted / do-they-work / where-to-buy-USA / risks) — **SXO-03, DFS-08, CONT-05, GEO-06** | Directly targets the proven AIO/"Things to know" citation pathway (DFS-06); the COA archive is the evidence backbone for all four |
| P2-2 | Build the "why choose us"/comparison layer — **SXO-02, SXO-04, DFS-05** | Needs P2-1's evidence pages to link to; targets the "best/cheapest/most trusted" cluster incl. the KD-0 terms (DFS-04) |
| P2-3 | Lift unique product copy above the boilerplate floor; fix the GLP-2 T20 manufacturer copy — **CONT-04, CONT-03, ECOM-03, ECOM-07, GEO-03** | Raises predicted page value for exactly the URLs stuck in "Discovered – currently not indexed" (GOOG-01) |
| P2-4 | Surface E-E-A-T identity: author/reviewer signals, NAP on contact/footer, brand-name consistency — **CONT-08, CONT-09, CONT-13, GEO-08** | Cheap; batch with P2-1 templates |
| P2-5 | Close the COA gaps (6 of 15 products) and start review capture — **ECOM-02, ECOM-01** | ECOM-01's aggregateRating also feeds SCHEMA-06/ECOM-06 in P3 |
| P2-6 | Consolidate duplicated disclaimers; out-of-stock substitute surfacing — **CONT-07, ECOM-05, CONT-10** | Housekeeping tier of the same sprint |

### P3 — Schema & structure consolidation (weeks 3–6). Deps: P2-4 (identity data must exist before it can be marked up), P2-5 for rating markup.

| # | Action (reference) | Sequencing note |
|---|---|---|
| P3-1 | Merge the three disconnected Organization entities into one @id graph — **SCHEMA-01, SCHEMA-02, SCHEMA-03, SCHEMA-10** | Root-cause fix first; SCHEMA-04/05 depend on it |
| P3-2 | Add sameAs + NAP/contactPoint to the unified entity — **SCHEMA-04, SCHEMA-05, GEO-04** | Requires P3-1 and any social/profile URLs from P4 |
| P3-3 | Complete Product/Offer trust properties — **SCHEMA-06, ECOM-06** | aggregateRating arrives via ECOM-01 (P2-5) |
| P3-4 | Dataset schema on COA hub pages + license/datePublished on batch pages — **GEO-07, SCHEMA-09** | Strengthens the citation asset before P4 promotes it |
| P3-5 | Structural tidy-ups — **SCHEMA-07, ECOM-08, MAP-03, GEO-02** (QAPage only if genuine user Q&A), **CONT-06, CONT-11** | Backlog-adjacent; bundle opportunistically |

### P4 — Authority & off-site (start week 2, ongoing; the true bottleneck).

| # | Action (reference) | Sequencing note |
|---|---|---|
| P4-1 | Investigate the spam-score-67 backlink source before building anything — **DFS-02, DFS-10** | Diagnosis precedes outreach; if toxic, document (disavow only if evidence warrants — owner decision) |
| P4-2 | Earn first legitimate references: lab partners, payment/shipping partners, industry directories, the COA archive as a linkable asset — **GEO-05, DFS-01, DFS-03** | The COA archive and P2-1 pages are the assets being promoted; benchmark vendors in DFS-05 show what gets cited (DFS-06) |
| P4-3 | Multi-modal presence (YouTube/video noted as the strongest AIO-correlated channel in the SERP evidence) — **GEO-05 evidence, DFS-06** | After P2-1 exists to link back to |
| P4-4 | Merchant Center / free-listings eligibility check — **ECOM-09, DFS-07** | Owner verification required (restricted vertical); explicitly out of audit scope to action |

### P5 — Hardening & hygiene (backlog, any time; independent).

- Security headers: HSTS → CSP → XFO/frame-ancestors → Referrer/Permissions-Policy — **TECH-01, TECH-02, TECH-03, TECH-04, GOOG-08** (order per TECH dependencies)
- Case-variant URL 200, IndexNow, "Learn more" link text, llms.txt, AI-crawler robots differentiation, GA4 property configuration, freshness-timestamp hygiene, readability bridging, notify-me duplication, sitemap lastmod hygiene — **TECH-05, TECH-07, GOOG-10, GEO-01, GEO-09, GOOG-11, CONT-12, CONT-14, CONT-10, MAP-02**
- GA4 (GOOG-11) is listed here for effort, but configure early if conversion visibility matters to the owner — it gates nothing else in this plan.

---

## Sequencing logic in one paragraph

P0 removes the contradictions that poison everything downstream (a 404'd legal anchor, a gate that hides the site from measurement, a QA placeholder on YMYL content) and opens internal discovery paths. P1 then makes the *real* page fast while P2 gives Google and AI engines something worth indexing and citing — P2 is deliberately parallel-tracked with P4, because content without authority stays "Discovered – not indexed" (the verified current state), and authority without content has nothing to land on. P3 consolidates the structured-data layer only after the identity and rating data it marks up exist. P5 never blocks anything.

## What success looks like (global)

| Horizon | Check (source) |
|---|---|
| +2 weeks | Terms 404 resolved sitewide; gate no longer first paint; PSI mobile homepage LCP < 4.0 s [2] |
| +4 weeks | `/testing/` known & indexed; ≥1 PAA page live; referring domains ≥ 5 [1][4] |
| +8 weeks | `/shop/` + ≥10 products indexed; impressions > 100/week; ≥1 non-brand query with clicks [1] |
| +12 weeks | First KD-0 term ranking page 1; CrUX eligibility achieved (field data starts existing) [1][3][4] |
| Falsification | See VALIDATE → Accept above: P0–P2 done + ≥10 referring domains + 8 more weeks + still unindexed ⇒ pivot to domain-trust investigation (DFS-02) |

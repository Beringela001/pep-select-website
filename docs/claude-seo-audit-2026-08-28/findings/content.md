# Content Quality & E-E-A-T Findings — pepselect.com — 2026-08-28

**Specialist:** seo-content (hit 15-turn limit; reported from home, `/about-us/`, `/product/glutathione/`, `/product/pt-141/`, sitemap XML). Word-count/readability passes on shop, testing sub-pages and a third product were not completed. Orchestrator verified the `/about-us/` robots state and Retatrutide duplication directly.

## Scores
- **Content Quality: 48 / 100** (8/23: 43)
- **On-Page: 60 / 100**

## E-E-A-T
| Factor | Weight | Score | Basis |
|---|---|---|---|
| Experience | 20% | 40 | Operational detail exists (glutathione dilution note); no case studies / first-hand narratives |
| Expertise | 25% | 30 | No bylines, no credentials on any reviewed page |
| Authoritativeness | 25% | 30 | One article; no external citations/press/recognition surfaced |
| Trustworthiness | 30% | 62 | RUO disclaimer, privacy, refund/shipping, terms, contact, phone, and the `/testing/*` COA archive (now 15 hubs / 18 reports) — strongest area |

## Findings
**Critical (specialist rating)**
- **CONT-17 (NEW, ledger priority High, NEEDS APPROVAL):** `/about-us/` → 200, `<meta name='robots' content='noindex, follow'>`, ~7,000 words of HTML, absent from `page-sitemap.xml`. Prior cycles recorded residual GSC impressions and logged "monitor"; this cycle rates the absence of an indexed company/QA page the top E-E-A-T gap for a YMYL-adjacent vendor. Whether to index it is Paulo's decision and its claims must pass marketing/compliance review first.
- No author/expert attribution anywhere reviewed (CONT-08, BLOCKED).
- Content program effectively absent: `post-sitemap.xml` = 1 article (CONT-05). **Blog/content strategy present: No** — seo-cluster not spawned.

**High**
- Liability/refund language embedded in product "Description" copy (glutathione dilution-refund clause) rather than linked policy pages (CONT-03) — muddies schema `description` and AI-extractable facts.
- `/testing/*` archive depth not content-audited this pass; verify pages carry real COA data, not placeholders (orchestrator: batch reports link from 14/17 products; see ecommerce.md).

**Medium**
- Freshness exists at meta level (glutathione `2026-08-22`) but no visible "last verified" on page.
- No `Person`/`Article` schema to support author/company trust.
- **CONT-15 (PARTIALLY FIXED):** Retatrutide glp3-r10/r20/r30 meta descriptions now differ; "Description" paragraph opening identical; main-content similarity 0.738 (r10/r20), 0.703 (r10/r30).
- CONT-13: "PepSelect" 13 (Terms) / 4 (Privacy) / 2 (Refund) instances.
- CONT-06: 3 GLP-2T DOI strings, 0 hyperlinks.

## Top fixes
1. Decide `/about-us/` (CONT-17) — index after review, or publish an indexed "Our process / Quality Archive" page.
2. Named author/reviewer bios with credentials on product + guide pages (CONT-08 — BLOCKED until real people/credentials exist).
3. Content cluster around COA interpretation, stability, handling — interlink with `/testing/*` (CONT-05, DFS-08).
4. Move liability/refund clauses to policy pages (CONT-03).
5. `Organization` + `Person` + `Article` schema and visible "last verified" dates.

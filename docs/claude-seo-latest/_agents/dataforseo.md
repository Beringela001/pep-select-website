# DataForSEO Analysis — pepselect.com

**Date:** 2026-08-18 · **Data source:** DataForSEO (live) · **Evidence class:** 4 (third-party estimates)
**Scope:** Domain rank, ranked keywords, backlinks, 2 live SERPs (+1 pre-paid SERP file), keyword difficulty for 10 target terms.

---

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

## Verified Correct
- All six authorized API calls returned status_code 20000 (Ok). No call was repeated; no unauthorized endpoint was used.
- Empty results for domain_rank_overview and ranked_keywords are genuine "no data" responses, not errors.
- SERP data timestamped 2026-08-18 (live). Backlink counts as of the 2026-08-18 request.

## Data Sources & Limitations
- All findings are **evidence class 4 — DataForSEO third-party estimates**; Labs data reflects DataForSEO's US/en database refresh cycle, and live SERPs reflect one crawl (SERP volatility applies).
- Both depth-100 SERP requests returned only 9 organic results each — Google served single feature-dense pages; deeper results were not available in the response, so "absent from top 100" formally means "absent from all results Google returned."
- Keyword difficulty does not model YMYL/compliance filtering, Merchant Center eligibility, or AI Overview dynamics; KD 0 values on newer compounds (retatrutide) may partly reflect sparse data.
- **Budget-excluded endpoints (NOT called):** backlinks_anchors (no anchor-text detail), backlinks_competitors / dataforseo_labs_google_competitors_domain (no shared-keyword competitor metrics), dataforseo_labs_bulk_traffic_estimation (no competitor traffic estimates), AI-mentions/LLM endpoints (no direct LLM-visibility measurement — AI-visibility findings are inferred from AI Overview citations only), search-volume endpoints (no volume figures for the 10 target terms).
- Pre-paid evidence incorporated: docs/claude-seo-latest/DATAFORSEO-serp-research-peptides-2026-08-18.md ("research peptides" SERP, $0.002, already logged).

## API Calls Made
- dataforseo_labs_google_domain_rank_overview — pepselect.com, US, en ($0.01)
- dataforseo_labs_google_ranked_keywords — pepselect.com, US, en, limit 100 ($0.05)
- backlinks_summary — pepselect.com ($0.02)
- serp_organic_live_advanced — "buy research peptides", US, en, desktop, depth 100 ($0.002)
- serp_organic_live_advanced — "bpc-157 for sale", US, en, desktop, depth 100 ($0.002)
- dataforseo_labs_bulk_keyword_difficulty — 10 keywords, US, en ($0.01)

Total: $0.094 (as pre-approved).

## Category Score: 8/100
Zero ranked keywords, zero SERP presence across three tracked queries, 6 backlinks at spam score 67 on a 6-week-old domain — the only positives are an unusually low keyword-difficulty landscape (three KD-0 targets) and demonstrated vendor access to AI Overview citations.

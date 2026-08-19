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

## Verified Correct

These require no SXO action — recorded so they are not re-flagged on a future pass:

- **Product-page trust content is accurate and substantive where it exists** — the Batch Documentation block (purity %, batch ID, lab name, test date) on `/product/glp3-r10/` is real, specific, and matches the linked `/testing/` batch record. The gap identified (SXO-05) is sequencing, not accuracy or existence.
- **`Dataset` schema is genuinely present** on COA batch-detail pages (verified directly in raw HTML on `/testing/ghk-cu-50-mg/psgkcu5071926gx/`), corroborating the main audit's claim about this asset.
- **Transparent failed-batch disclosure exists**: `/testing/retatrutide-20mg/` shows a batch (PSRT2062926JP) marked "Did not pass release review... not released for sale" — publishing a failed QC result is a genuine, rare trust signal in this vertical and should not be removed or softened.
- **The research gate's indexability exemption is correctly a non-issue** (per the main audit's G-02) — Googlebot sees the underlying page content regardless of the gate. SXO-01/SXO-08 are about the *human* first-impression experience, not crawlability, and should not be conflated with G-02 when prioritizing work.
- **`/shop/` and product pages share one consistent template** — no divergent or broken variants were found across the 15 product URLs sampled via the on-page table.

---

## Data Sources & Limitations

- **SERP evidence is a single live DataForSEO pull for one head term** ("research peptides," US/desktop) — evidence class 4. It does not cover compound-specific queries (e.g., "buy BPC-157," "GLP-3 R purity"), which is the actual competitive set for the 15 individual product pages. The "ALIGNED" call on the product-page type above is a scoped judgment, not a validated one — a compound-level SERP pull would be needed to confirm it.
- **No new page fetches were performed for this pass** — analysis reused same-day raw HTML captures and rendered screenshots from a sibling audit run (`docs/claude-seo-latest/00-audit-context.md` and related files), plus fresh text/schema extraction against those captures. Per the read-only/GET-only constraint, no live re-fetch of pepselect.com was made to confirm the site is unchanged since that capture.
- **No Google Search Console, GA4, or PageSpeed Insights access** — every "Leading indicator" above is stated as a metric to watch *if and when* such access exists; none of the persona/engagement claims are backed by first-party analytics in this pass.
- **Gate interaction (Enter Site / Exit click behavior) was not measurable** — no analytics on gate completion rate were available; SXO-01 and SXO-08 are reasoned from static screenshots and markup, not observed user behavior.
- **Persona scores are qualitative, analyst-assigned estimates** (0–25 per dimension) based on observed page content and SERP signals, not survey or behavioral data — they are directional, not measured.
- **This report does not re-litigate findings already fully owned by the on-page SEO audit** (O-01 homepage/shop/testing H1 keyword gap, O-04 out-of-stock anchor text, C-02 thin product content, S-04 missing ItemList, G-05 zero backlink authority) except where cited as supporting evidence for a distinct SXO-specific angle.

---

## Category Score: 55/100

Content substance is genuinely strong (real batch data, transparent failed-QC disclosure, a page type — the COA archive — with zero SERP peer), but the score is held down by a first-impression journey defect that hits every persona identically (SXO-01), a complete absence of the comparison/PAA-targeted content type the SERP is visibly rewarding (SXO-02/03), and one persona (bulk/wholesale) with no coverage at all (SXO-07).

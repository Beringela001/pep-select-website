# GEO Audit — pepselect.com

Audit date: 2026-08-18 · Scope: AI crawler access, llms.txt, passage-level citability (homepage, `/testing/`, one product page, `/faq/`), Q&A extractability, brand-mention signals, platform-specific readiness (Google AI Overviews, ChatGPT search, Perplexity, Bing Copilot). Method: live read-only GET fetches (`claude-seo run render_page.py`, raw + rendered modes) plus one DataForSEO live-SERP artifact supplied by the coordinator (`DATAFORSEO-serp-research-peptides-2026-08-18.md`, evidence class 4). No DataForSEO calls were made by this agent. Findings ID prefix: `GEO-NN`.

---

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

## Data Sources & Limitations

- **Live fetches performed this session (class 5, GET-only):** `robots.txt`, `llms.txt`, `sitemap_index.xml`, `ps_coa_test-sitemap.xml`, homepage (`/`), `/testing/`, `/faq/`, one product page (`/product/glp3-r10/`), one COA batch leaf page (`/testing/ghk-cu-50-mg/psgkcu5071926gx/`, fetched both raw and force-rendered via Playwright), and one COA compound-hub page (`/testing/retatrutide-20mg/`). All via `claude-seo run render_page.py`, which performs SSRF/DNS-rebinding-safe fetches; no direct `requests.get` calls were made.
- **No live User-Agent spoofing was performed.** `render_page.py` does not expose a custom User-Agent option, so this audit could not directly compare server responses to a real `GPTBot`/`ClaudeBot`/`PerplexityBot` User-Agent header against a generic fetch (a cloaking check). The `robots.txt`-based access assessment (GEO-09) and the raw-HTML-completeness observation (Verified Correct) are the best available substitutes and are both evidence class 5, but neither confirms the origin does not serve different content to a declared AI-bot User-Agent.
- **No DataForSEO calls were made by this agent**, per instruction. All market-side evidence (AI Overview citations, PAA queries, organic result set, "Things to know" cards) is drawn entirely from the single supplied artifact `docs/claude-seo-latest/DATAFORSEO-serp-research-peptides-2026-08-18.md` (evidence class 4, one Google US desktop pull, single point in time, single keyword "research peptides" — not validated against ChatGPT search, Perplexity, or Bing Copilot directly, and not re-verified for currency as of 2026-08-18 beyond the file's own stated pull date).
- **Platform-specific readiness (Google AIO, ChatGPT search, Perplexity, Bing Copilot) is assessed qualitatively, not measured per platform.** Technical accessibility (SSR, robots.txt) is uniform across all four. Google AI Overview is the only platform with direct citation evidence in scope (GEO-05, class 4); ChatGPT search, Perplexity, and Bing Copilot readiness is inferred from the same content/authority findings (GEO-03 through GEO-08) without platform-specific verification, since no `ai_optimization_chat_gpt_scraper` or `ai_opt_llm_ment_search` MCP tool call was made or authorized this session.
- **Not independently re-verified in this session (relied on the prior full-site SEO audit dated the same day):** Common Crawl absence (G-05), no About/author page (C-04), 43-page site structure and word counts (C-01/C-02). These are cited as supporting evidence in GEO-05 and GEO-08 rather than re-measured, since the coordinator's own prior artifact already establishes them with fuller methodology than this GEO-scoped pass would add.
- **Brand-mention presence (Wikipedia, Reddit, YouTube, LinkedIn) was assessed only via on-site signals** (absence of any outbound link or `sameAs` reference) and the domain-wide Common Crawl absence already on record. No external search of Wikipedia, Reddit, YouTube, or LinkedIn was performed to confirm zero third-party mentions exist independent of the site's own lack of self-links — this is a reasonable inference given the domain's newness (content dated from 2026-06-24 onward) and total absence from the Common Crawl web graph, but it is an inference, not a direct check of those platforms.

---

## Category Score: 51/100

Dimension estimates — Citability 58/100, Structural Readability 65/100, Multi-Modal Content 28/100, Authority & Brand Signals 13/100, Technical Accessibility 85/100 — weighted 25/20/15/20/20 → **51/100**. The site is technically wide open to every AI crawler in scope and already produces one genuinely rare, well-structured citable asset (the COA batch `Dataset` schema), but it carries a near-zero off-site authority signal confirmed against a live SERP where two direct competitors are already cited inside Google's AI Overview for the category's head term — that gap, not any on-page defect, is the dominant constraint on the score.

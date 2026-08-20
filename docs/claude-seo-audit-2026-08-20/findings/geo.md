# GEO / AI Search Readiness — Verification Audit, 2026-08-20

Scope: GEO-01, GEO-03, GEO-04, GEO-05, GEO-06, GEO-08, GEO-09 (GEO-02 and GEO-07 belong to the schema specialist and are out of scope here). Verification against the 2026-08-18 ledger, focused on whether `https://pepselect.com/guides/how-to-review-research-peptide-documentation/` (Live since 2026-08-19, M4 Batch 2) moved the needle. All checks below are live GET requests made 2026-08-20; no mutation performed.

## 1. GEO-01 — llms.txt

`curl -s -o /dev/null -w "%{http_code}" https://pepselect.com/llms.txt` → **404**. Confirmed still missing. No change since 2026-08-18. Consistent with the original Low priority: Google does not consume llms.txt for AI Overviews/AI Mode, and no major AI crawler treats it as authoritative discovery input today, so this remains optional and should stay below crawl/content/authority work in sequencing.

## 2. GEO-09 — robots.txt AI crawler distinction

Live `https://pepselect.com/robots.txt` (200):

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

No user-agent-specific blocks or allows exist for any crawler by name. There is no `GPTBot`, `OAI-SearchBot`, `ClaudeBot`, `PerplexityBot`, `Google-Extended`, `CCBot`, `anthropic-ai`, or `Bytespider` token anywhere in the file — every crawler, AI or otherwise, currently gets the same blanket `Disallow:` (allow-all, minus the WooCommerce/wp-admin paths in the WordPress core block). This means AI search-retrieval crawlers are not blocked (good — GPTBot, OAI-SearchBot, ClaudeBot, PerplexityBot can currently fetch the site), but the file also makes no explicit distinction that would let the owner selectively curb training-only crawlers (CCBot, anthropic-ai, Bytespider) while explicitly keeping retrieval crawlers open. **Confirmed unchanged and still open** — matches the original finding exactly.

## 3. GEO-03 — Citability / passage length

Fetched three product pages live and isolated the actual on-page description block (the paragraph directly under "Description" plus the "Research context" bullet list — the only citable factual prose on the page; legal/disclaimer boilerplate is not counted as it is not what an AI Overview would extract as compound-specific information):

| Product | Intro paragraph + Research-context bullets | Word count |
|---|---|---|
| NAD+ (`/product/nad/`) | "NAD+ (nicotinamide adenine dinucleotide) is a coenzyme studied for its central role in cellular energy metabolism. It is researched as a substrate in redox reactions and as a driver of sirtuin-dependent regulatory pathways." + 3 one-line bullets | ~55 words |
| GLP-2T 20mg (`/product/glp2-t20/`) | "Tirzepatide is a dual-agonist research peptide designed to engage both GIP and GLP-1 receptors. Research focuses on how one peptide interacts with two receptor systems and how its structure shapes signaling at each receptor." + 3 bullets | ~58 words |
| Glutathione (`/product/glutathione/`) | "Glutathione is a tripeptide (glutamate–cysteine–glycine) studied as a principal cellular antioxidant. It is researched for its role as a redox buffer and as a cofactor in enzymatic detoxification and protein regulation." + 3 bullets | ~57 words |

The Product JSON-LD `description` field mirrors just the two-sentence intro (33–36 words), confirmed by parsing each page's `application/ld+json` graph.

Against a ~134–167 word optimal self-contained passage length for AI citation, every sampled product sits at roughly one-third of that target, even counting the bulleted research-context list (which is not really passage-length text — each bullet is a 6–9 word fragment, not a self-contained sentence). None of the three sampled passages would independently satisfy AI-citation length guidance.

This is consistent with the ECOM-03/CONT-07 ledger note: those fixes replaced manufacturer boilerplate with unique, DOI-cited copy and removed a duplicate-content risk, but they did not add length. GEO-03 targets citation-length adequacy specifically, which is a distinct problem from uniqueness. **GEO-03 remains STILL OPEN.** The M6-content work (unique-copy expansion, ECOM-04/CONT-04 adjacent) has not yet been extended to description length.

## 4. GEO-04 / GEO-05 — sameAs / off-site brand signals

Homepage Organization JSON-LD (`https://pepselect.com/#organization`, type `OnlineStore`) was parsed directly from the live `@graph`:

```json
{
  "@type": "OnlineStore",
  "@id": "https://pepselect.com/#organization",
  "name": "Pep Select",
  "url": "https://pepselect.com/",
  "logo": { ... },
  "hasMerchantReturnPolicy": { ... }
}
```

No `sameAs` key is present. No social, Wikipedia, Crunchbase, or other identity-graph links exist on the Organization entity. This matches the 2026-08-18 state exactly — **confirmed still absent, no regression, no improvement.**

Both findings require Paulo to supply and approve real, live social/entity profile URLs (YouTube, Reddit presence, LinkedIn, a Wikipedia/Wikidata entity, etc. — per the brand-mention correlation table, YouTube mentions correlate most strongly (~0.737) with AI citation, ahead of Domain Rating). No such profiles were provided to this audit and none should be fabricated or guessed. **GEO-04 and GEO-05 remain BLOCKED BY REAL EVIDENCE**, exactly as the original ledger classified them ("Blocked/input needed" and "Not started" pending owner input, respectively).

## 5. GEO-06 — informational content coverage

The new guide (`/guides/how-to-review-research-peptide-documentation/`, WordPress post 1536) is live, 200, indexable (`index, follow`), self-canonical, in `post-sitemap.xml`, with `wordCount: 2351` in its Article JSON-LD. Headings extracted from the live page:

1. How to Review Research Peptide Documentation (H1)
2. Start with the batch, not the headline number
3. See the batch connection on the page
4. Check identity before you interpret purity
5. Match the compound and labeled strength
6. Find the laboratory, date, and report reference
7. Follow the report back to the laboratory
8. Read each method as a separate line of evidence
9. When the label and measured content disagree
10. The laboratory photographed the submitted vials
11. Treat status words as data
12. Read the release decision last
13. The archive also shows what Pep Select rejected
14. Two records, two different decisions
15. Use the Pep Select record as your checklist
16. How Pep Select organizes published batch records
17. Review the batch record before you choose.

**Coverage assessment against the target query set:**

- "How to read a COA" / "how to review peptide documentation" → directly covered, in depth, with six evidence images and eighteen numbered callouts per the M4B2 release notes. This is a genuine, substantive answer to a real query in the niche.
- "What is a peptide research certificate of analysis" → partially covered implicitly (the guide explains COA components — batch number, purity, lab identity, report reference — but never states a direct, extractable "A COA is..." definitional passage near the top). An AI Overview definitional snippet extraction would need to synthesize this from context rather than lift a self-contained sentence.
- "Batch testing for research peptides" / how batch-to-batch testing works → covered via the "archive," "two records, two different decisions," and "how Pep Select organizes published batch records" sections.
- Other PAA-adjacent queries in this niche (e.g., "is it legal to buy research peptides," "how are research peptides stored/reconstituted," "what does 99% purity mean," "research peptide vs pharmaceutical grade") → **not covered.** This guide is a single, deep piece on COA literacy; it does not expand into the wider PAA cluster the original SXO-02/SXO-03/DFS-08 findings identified (comparison content, "why choose us," or the broader PAA question map from the two commercial SERPs).
- **None of the 17 headings are phrased as questions.** They are declarative/imperative statements ("Start with the batch, not the headline number," "Treat status words as data"). Question-based H2/H3 headings are a stronger direct-extraction signal for AI Overview and PAA matching than declarative headings; this guide does not use that pattern anywhere.
- No visible byline, credential, or "last updated" date is shown to readers (see GEO-08 below) — this affects the guide's standing as an authoritative source for AI systems that weight E-E-A-T signals on YMYL-adjacent health/wellness-adjacent content.

**Net assessment:** GEO-06 moves from "no informational content exists" to "one substantial, genuinely useful piece of informational content exists, covering one real query well." It does not close the finding — it addresses roughly one node of a multi-query content gap that also includes SXO-02/SXO-03/DFS-08 (comparison pages, PAA cluster, "best/cheapest/trusted" intent). **PARTIALLY FIXED.**

## 6. GEO-08 — author/reviewer/last-updated byline

Checked the guide page specifically for a visible byline, reviewer credential, or "last updated" date shown to readers, plus the underlying JSON-LD:

- Visible page content: no "Written by," "Reviewed by," "Author," or "Last updated" text found anywhere in the rendered body (0 matches for those visible-content patterns).
- Article JSON-LD: `"author": {"@id": "https://pepselect.com/#organization"}` — the byline is the Organization entity, not a named/credentialed person. `datePublished` and `dateModified` exist in the JSON-LD (2026-08-19) but are not surfaced as a visible "last updated" line to readers.
- A separate, disconnected Yoast `Person` node exists in the same JSON-LD graph: `{"@type":"Person","name":"beringela001","sameAs":["https://pepselect.com"],"url":"https://pepselect.com/guides/author/beringela001/"}`. This is the raw WordPress username of whoever is logged in as the post's WP author, auto-emitted by Yoast's author-archive schema — it is not wired to the Article's `author` field and is not shown to readers. The page's `<meta name="author" content="beringela001">` and `twitter:label1: "Written by" / twitter:data1: "beringela001"` tags do leak this raw username into page metadata, which is a minor hygiene issue (a machine-readable, non-credentialed username being labeled "Written by") but not a fabricated credential, and not a real byline either way.

No real, approved author/reviewer credential was published, and none should be fabricated. **GEO-08 remains BLOCKED BY REAL EVIDENCE**, unchanged from 2026-08-18. Flagging the leaked `beringela001` username as a small cleanup item worth a note to the schema/content owner (hide or set a real display name for the WP user, or suppress `meta[name=author]`/twitter author tags), independent of the byline decision itself.

## Passage-citability spot check

Two self-contained passages were extracted per page and evaluated on whether they would stand alone as an AI Overview snippet without surrounding context.

**Product page — NAD+ (`/product/nad/`):**

1. *"NAD+ (nicotinamide adenine dinucleotide) is a coenzyme studied for its central role in cellular energy metabolism."* — Stands alone reasonably well; defines the compound and its studied role in one sentence. Would work as a short definitional snippet, though it is below optimal length as a full passage on its own.
2. *"Studied for its role in cellular energy metabolism"* (research-context bullet) — Does **not** stand alone; it has no subject (no compound name), so out of context it is meaningless. It only makes sense as a fragment under the "NAD+ / Research context" heading. This is a structural weakness for AI extraction: bullets need to be self-contained subject+claim statements, not sentence fragments with the implied subject supplied only by the surrounding page structure.

**Guide — How to Review Research Peptide Documentation:**

1. *"Read each method as a separate line of evidence"* (H3 alone) — Not self-contained as extracted; it's an instruction without the "what" and "why" that follows it in-page. The paragraph beneath likely completes the thought, but the heading text itself, if lifted in isolation (as AI Overview sometimes does with H-tag text), would confuse rather than answer.
2. The guide's evidence-image callouts (numbered 1–18 per the release notes) are visually anchored to specific photograph regions — this is exactly the kind of grounded, verifiable content AI systems favor, but the callout text itself is dependent on the adjacent image for meaning and would not extract cleanly as pure text into a text-only AI Overview snippet the way a self-contained sentence would.

**Takeaway:** both the product pages and the new guide read well for a human visitor in page context, but neither is optimized for atomic, standalone extraction — the exact structural gap GEO-03/GEO-06 describe. Fixing this is a content-authoring pattern change (write each key sentence so it carries its own subject and claim), not a schema or technical change.

## Prior Finding Classifications

| ID | Original Priority | Prior State (8/18) | Current Classification | Evidence |
|---|---|---|---|---|
| GEO-01 | Low | Superseded / no immediate fix | **STILL OPEN** (unchanged, low-priority as designed) | `curl -o /dev/null -w "%{http_code}" https://pepselect.com/llms.txt` → 404, confirmed 2026-08-20. |
| GEO-03 | High | Not started | **STILL OPEN** | Live-fetched NAD+, GLP-2T, Glutathione descriptions (intro + research-context bullets) measured at ~55–58 words each against a ~134–167 word optimal citation-passage benchmark; JSON-LD `description` confirms the same short text is what search engines see. |
| GEO-04 | Medium | Blocked / input needed | **BLOCKED BY REAL EVIDENCE** | Homepage `OnlineStore` JSON-LD (`https://pepselect.com/#organization`) fetched live 2026-08-20 has no `sameAs` key. |
| GEO-05 | Critical | Not started | **BLOCKED BY REAL EVIDENCE** | Same homepage entity check as GEO-04; zero off-site identity-graph links present. Requires Paulo-approved real social/entity profiles; none supplied. |
| GEO-06 | High | Not started | **PARTIALLY FIXED** | New guide (post 1536, live, 2351 words, sitemap-indexed) substantively answers "how to read a COA"; does not cover the broader PAA/comparison query cluster (SXO-02/SXO-03/DFS-08) and uses zero question-phrased headings across its 17 H2/H3s. |
| GEO-08 | Low | Blocked / input needed | **BLOCKED BY REAL EVIDENCE** | Guide's Article JSON-LD author is the Organization entity, not a named person; no visible byline/reviewer/"last updated" text found in rendered HTML. A disconnected Yoast `Person` node exposing the raw WP username `beringela001` exists in metadata/JSON-LD but is not a real credentialed byline. |
| GEO-09 | Low | Not started | **STILL OPEN** | Live `robots.txt` fetched 2026-08-20 contains only a WordPress core path block and a blanket Yoast allow-all; no named AI crawler (GPTBot, ClaudeBot, PerplexityBot, OAI-SearchBot, Google-Extended, CCBot, etc.) appears anywhere in the file. |

## Sources

- Live fetches 2026-08-20: `https://pepselect.com/robots.txt`, `https://pepselect.com/llms.txt`, `https://pepselect.com/`, `https://pepselect.com/product/nad/`, `https://pepselect.com/product/glp2-t20/`, `https://pepselect.com/product/glutathione/`, `https://pepselect.com/guides/how-to-review-research-peptide-documentation/`.
- `docs/claude-seo-latest/CODEX-SEO-FINDINGS-LEDGER-2026-08-18.md`
- `docs/claude-seo-latest/LIVE-SEO-M4-BATCH-2-RELEASE-2026-08-19.md`

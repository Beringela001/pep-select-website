# Content Quality / E-E-A-T Findings — 2026-08-20 Verification Audit

Scope: content-quality/E-E-A-T slice of the post-remediation SEO audit of the live site https://pepselect.com. Verifies CONT-01 through CONT-14, GOOG-10, GOOG-11, DFS-08, DFS-09 against the 2026-08-18 findings ledger and the two 2026-08-19 Live release notes. All evidence below is from fresh GET fetches of the live site on 2026-08-20 (raw HTML + trafilatura extraction via `render_page.py`, `mode=never`/`auto`). No mutations were made.

## Method note

Pages were fetched with the shared `render_page.py` renderer (`mode=auto`/`never`, raw fetch — the site is not an SPA, `is_spa=false` on every sampled URL). Word counts below are computed two ways and labeled accordingly:
- **trafilatura-extracted word count** — boilerplate-stripped main-content text (nav/footer chrome removed by trafilatura itself).
- **non-compliance-boilerplate word count** — the trafilatura-extracted count minus the ~109-word compliance block (dilution notice + intended-use + FDA statement) that recurs verbatim on every sampled product page. This is the closer proxy to the original audit's "unique content" metric, but the two are not directly comparable because the original audit's baseline methodology/page scope is not fully documented here — treat both as directional, not identical to the 8/18 numbers.

Raw JSON evidence saved under `docs/claude-seo-audit-2026-08-20/raw-crawl/content-extracted.json`, `content-checks.json`, `content-checks2.json`, `doi-check.json`, `guide-jsonld.json`, `contact-check.json`, `ga4-check2.json`, `home-title.json`.

---

## 1. Homepage (CONT-11, CONT-07, CONT-03/ECOM-07 partial)

**CONT-11 — "research peptide" usage.** Verified. The homepage hero now reads:

> "You shouldn't need five tabs and a leap of faith to look into a **research peptide**. Pep Select keeps current product details and available batch documentation in one place..."

This is one natural, non-stuffed body-copy use of "peptide," exactly matching the Batch 1 release note claim. The `<title>` tag is separately "Research Peptides with Batch-Matched Lab Reports | Pep Select" (pre-existing, plural, not part of this release) and the meta description reads "Browse research compounds with clear product information, batch-specific documentation, and accessible testing history from Pep Select." Total trafilatura-extracted homepage body: 330 words. This is a genuine, narrow fix — it addresses the single flagged sentence-level gap, not overall topical/keyword depth.

**CONT-07 — duplicate FDA disclaimers.** Confirmed still present and still worded inconsistently. Two different FDA-disclaimer paragraphs exist site-wide:

- Paragraph A (Research Gate, present in the raw HTML of every page sampled — home, shop, glp2t, faq, contact): *"FDA Disclaimer: The statements made within this website have not been evaluated by the U.S. Food and Drug Administration. The statements and the products of this company are not intended to diagnose, treat, cure or prevent any disease. This company is not a compounding pharmacy or chemical compounding facility as defined under 503A of the Federal Food, Drug, and Cosmetic Act, and is not an outsourcing facility as defined under 503B of the Federal Food, Drug, and Cosmetic Act. All products are sold for research, laboratory, or analytical purposes only, and are not for human consumption."*
- Paragraph B (every product page's "Intended use" block, including GLP-2T's freshly rewritten page): *"These statements have not been evaluated by the Food and Drug Administration. No claims are made regarding the diagnosis, treatment, cure, or prevention of any disease. Use is restricted to qualified research professionals."*

Both paragraphs co-occur on every product page. Wording is inconsistent ("U.S. Food and Drug Administration" vs. "Food and Drug Administration"; "not intended to diagnose, treat, cure or prevent" vs. "No claims are made regarding the diagnosis, treatment, cure, or prevention"). Note: the 8/18 ledger's master table still lists CONT-07 as "Not started," but the Batch 1 Live checkpoint bullet says "`ECOM-03` and `CONT-07`: Live implementation deployed" in the same sentence describing the GLP-2T rewrite — that appears to be a mislabeling in the release note (the actual GLP-2T change did not touch either FDA disclaimer paragraph; both are still present, still duplicated, still worded inconsistently, confirmed by direct fetch). Treating the master-table "Not started" status and the direct evidence as authoritative over the ambiguous checkpoint bullet.

**Research Gate dominates crawlable text.** Trafilatura's boilerplate-stripping algorithm — designed to find "the" main content block — selected the Research Gate/compliance modal as primary content on `/shop/`, `/faq/`(indirectly), and `/contact/`, not the actual page content (shop grid, FAQ answers, contact form). This is independent corroboration of the still-open VIS-01/SXO-01 findings (gate dominates first-paint content) from a content-extraction angle, not just a visual one.

---

## 2. Product pages (CONT-03, CONT-04, ECOM-03, ECOM-07, CONT-06)

Sampled 6 SKUs: GLP-2T, NAD+, Bacteriostatic Water 30mL, BPC-157, GHK-Cu, Glutathione.

| SKU | Stock | trafilatura word count | Has Description/Research-context/citations? | Non-boilerplate word count (approx.) |
|---|---|---|---|---|
| GLP-2T | Out of stock | 217 | Yes — Pep Select-specific dual-receptor copy | ~108 |
| NAD+ | In stock | 205 | Yes | ~96 |
| BPC-157 | Out of stock | 194 | Yes | ~85 |
| GHK-Cu | In stock | 212 | Yes | ~103 |
| Glutathione | Out of stock | 216 | Yes | ~107 |
| Bacteriostatic Water 30mL | In stock | 119 | **No — no Description, no Research context, no CAS number, no citations at all** | **~10** |

**ECOM-03 (GLP-2T supplier boilerplate) — VERIFIED FIXED on Live.** The product now reads:

> "Tirzepatide is a dual-agonist research peptide designed to engage both GIP and GLP-1 receptors. Research focuses on how one peptide interacts with two receptor systems and how its structure shapes signaling at each receptor."

This is Pep Select-authored, mechanism-specific copy, not the prior manufacturer/supplier boilerplate. Three research-context bullets and three DOI citations (Willard FS et al. 2020; Sun B et al. 2022; Zou X et al. 2025) are present. Matches the Batch 1 release claim.

**CONT-03 / CONT-04 / ECOM-07 (sitewide boilerplate ratio, thin product content, rigid one-paragraph template) — STILL LARGELY OPEN, confirmed by fresh sampling.** Every sampled product (including the "fixed" GLP-2T) still carries an identical ~109-word verbatim compliance block:
- Dilution notice (51 words, identical): *"If a compound turns cloudy after it is reconstituted, the cause is almost always the reconstitution solution rather than the compound itself, and non-laboratory-grade solutions are the usual culprit. For this reason, cloudiness cannot be accepted as grounds for a refund unless the reconstitution was done using a laboratory-grade reconstitution solution."*
- Intended use (25 words, identical): *"Research Use Only. Supplied strictly for laboratory research. Not for use in humans or animals, and not for use in foods, drugs, supplements, or diagnostics."*
- FDA statement (33 words, identical — this is "Paragraph B" from the CONT-07 section above).

Beyond that fixed block, per-SKU unique content is still in the 85–108 word range for the 5 SKUs that have a description at all — comparable in kind to the original 294–441-word finding only if the original count included nav/footer/compliance text that trafilatura now strips; either way, the underlying problem (rigid one-paragraph description + 3 boilerplate bullets + citations, repeated as a template across the catalog) is unchanged for every SKU except GLP-2T's rewritten paragraph.

**Bacteriostatic Water 30mL is a new/worse data point:** it has no Description section, no Research context, no CAS number, and no citations at all — it is effectively 100% boilerplate plus price/stock. This SKU was not called out by name in the original CONT-04 sample but is worse than any of the originally-sampled 10-of-16 thin SKUs.

**CONT-06 (DOI/PMID not linkified) — STILL OPEN, confirmed.** On GLP-2T, all three DOI strings render as plain text, not hyperlinks:
```
DOI:10.1172/jci.insight.140532.
DOI:10.1073/pnas.2116506119
DOI:10.1016/j.ijbiomac.2025.146141
```
No `<a href>` to doi.org, pubmed.ncbi.nlm.nih.gov, or ncbi.nlm.nih.gov was found in the GLP-2T page source. Matches the ledger's own "CONT-06 remains open" note.

---

## 3. New guide page (partial CONT-05, GEO-06; CONT-08/GEO-08 still blocked)

`https://pepselect.com/guides/how-to-review-research-peptide-documentation/` — HTTP 200, confirmed live.

**Content depth.** This is a substantial, genuinely evidence-led piece: 2,244 trafilatura-extracted words (536 unique tokens), walking through how to cross-check a Pep Select COA (batch code, vial photo, cap/crimp, lab name, report reference, identity vs. purity distinction, release status) using two real, named batch examples (NAD+ 500 mg batch ND50026205JP, Retatrutide 20 mg batch PSRT2062926JP — including a batch that **failed** identity review and was not released, which is a genuine trust/transparency signal, not marketing copy). It links out to four real `/testing/.../` batch pages, which returned 200 in the release note's route checks. This meaningfully improves CONT-05 ("zero blog/guide content") and GEO-06 ("no informational content") — it is one real, deep piece, not the "zero" baseline anymore. It does not fully close either finding: it is a single article, not a content program, and does not map to the SERP-proven PAA/AI-Overview query set from DFS-06/DFS-08/GEO-06.

**CONT-08 / GEO-08 (author/reviewer credential, still blocked).** Confirmed still unresolved. The guide's `Article` JSON-LD sets `author` to the site Organization entity (`https://pepselect.com/#organization`), not a named person:
```json
{"@type":"Article", "author":{"@id":"https://pepselect.com/#organization"}, "publisher":{"@id":"https://pepselect.com/#organization"}, ...}
```
A separate, disconnected `Person` entity exists in the same JSON-LD graph — `"name":"beringela001"` (a WordPress username, `sameAs` only pointing back to the homepage, `url` = `/guides/author/beringela001/`) — but it is **not** referenced by the Article's `author` field and carries no bio, credentials, or expertise signal. This is consistent with the ledger's "Blocked / input needed" status and the no-fabrication constraint: no real, approved named author/reviewer has been published anywhere sampled, including on this new YMYL-adjacent guide.

---

## 4. FAQ page (DFS-08 territory, note only)

`https://pepselect.com/faq/` — 643 trafilatura-extracted words across six sections (Research use, Testing & records, Storage & handling, Ordering/payment/rewards, Shipping, Orders & support). This is genuine, specific content (e.g., exact storage temperatures, exact shipping cutoffs, cash-back mechanics) — not thin. It is oriented around Pep Select's own operational/policy questions, not the SERP-derived PAA question set that DFS-08 and SXO-03 describe (e.g., generic "what is [compound] used for," "is [research peptide] legal," comparison-style queries). No new content was found that specifically targets that PAA map. DFS-08 remains open; the FAQ is adjacent but not a substitute.

---

## 5. GOOG-10 — non-descriptive "Learn more" link text

**STILL OPEN, confirmed present.** `/shop/` renders at least 7 identical `<a>` elements with visible text "Learn more" and no `aria-label` differentiation, each pointing to a different product URL:
```
/product/glp3-r10/  -> "Learn more"
/product/glp3-r30/  -> "Learn more"
/product/tb500-10/  -> "Learn more"
/product/ghk-cu/    -> "Learn more"
/product/nad/       -> "Learn more"
/product/tesa-10/   -> "Learn more"
/product/pt-141/    -> "Learn more"
```
No change from the original finding.

---

## 6. GOOG-11 — GA4 configuration (public-crawl limitation noted)

**Cannot be fully verified via public crawl** (GA4 configuration/reporting requires account access this audit does not have). What the homepage source does show: a `gtag.js` loader is present (`https://www.googletagmanager.com/gtag/js?id=GT-NNQ4N6DP`) with a live `gtag()` call wired to a custom event-throttling function. The `GT-` prefix is Google's unified "Google tag" container format (can carry GA4 config, Google Ads, or both). This is new evidence of *some* Google tag being active site-wide that was not documented in the 8/18 ledger. It does not, by itself, confirm a working GA4 property, conversion events, or that organic session/conversion visibility (the actual finding) has been restored — that requires GA4 admin/reporting access. Recommend the technical/analytics owner confirm in GA4 Admin whether `GT-NNQ4N6DP` maps to a configured GA4 data stream.

---

## 7. CONT-13 — brand name inconsistency ("Pep Select" vs. "PepSelect")

**STILL OPEN, confirmed and quantified.** Checked Terms & Conditions, Privacy Policy, Refund & Shipping Policy, RUO Disclaimer:

| Page | "PepSelect" (one word) | "Pep Select" (two words) |
|---|---|---|
| Terms & Conditions | 12 | 7 |
| Privacy Policy | 3 | 7 |
| Refund & Shipping Policy | 1 | 7 |
| RUO Disclaimer | 0 | 20 |

Both forms appear within the same legal document, e.g. Terms & Conditions opens "Welcome to PepSelect. These Terms & Conditions..." and elsewhere reads "The Site is operated by PepSelect ('PepSelect,' 'we,' 'us,' or 'our')." The mailing address in Privacy Policy also uses the one-word form: "Mailing address: PepSelect, 2090 Baker Rd, Ste 304, Kennesaw, GA 3[0144]." No public evidence of standardization since 8/18.

---

## 8. CONT-09 (contact/NAP), CONT-01/CONT-02 spot checks

**CONT-09 spot check (not owned by this slice's priority list but touched during fetch):** `/contact/` page's crawlable/extracted content is dominated by the Research Gate attestation and FDA disclaimer text (same gate-dominance issue noted in §1); no phone number or street address pattern was found in the extracted text. Consistent with the ledger's "Blocked / input needed" status — no NAP change detected.

**CONT-01 spot check:** not re-tested this session (deprioritized per instructions; no contrary evidence encountered).

**CONT-02 spot check:** GLP-2T page shows fully-corrected DOI citation text with no `[VERIFY DOI]` placeholder — consistent with "Live verified."

---

## 9. Items not independently re-verified this cycle (limitations)

- **CONT-10** (duplicated "Notify me" modal text per cross-sell item) — not re-fetched this session (out-of-stock modal requires interaction-state capture beyond a plain GET of page source); no release note claims a fix. Deferring to prior "Not started" classification.
- **CONT-12** (freshness-timestamp clustering on 8 static/legal pages) — explicitly out of scope per task instructions (requires WP admin data). The `htmldate`-derived `publication_date` values collected incidentally for sampled pages (home 2026-08-14, GLP-2T 2026-08-14, NAD+ 2026-08-14, Bacteriostatic Water 2026-08-20, BPC-157 2026-08-19, GHK-Cu 2026-08-20, Glutathione 2026-08-14, FAQ 2026-08-14, Shop 2026-08-14) show some variation across sampled non-legal pages, but the original finding was specifically about legal/static pages (Terms, Privacy, Refund, etc.) clustered in an 81-second window, which was not independently re-measured. No public evidence either way; deferring to prior classification.
- **DFS-09** ("bpc-157 for sale" SERP intent split) — no page-level evidence of new content targeting this specific commercial query cluster was found; BPC-157's own product page remains a standard thin templated SKU page (194 words, out of stock).

---

## Prior Finding Classifications

| ID | Original Priority | Prior State (8/18) | Current Classification | Evidence |
|---|---|---|---|---|
| CONT-01 | Critical | Live verified | VERIFIED FIXED | Not re-tested this cycle (deprioritized per task scope); no contrary evidence found incidentally. Deferring to prior "Live verified" status. |
| CONT-02 | Critical | Live verified | VERIFIED FIXED | GLP-2T citation text fully corrected on fresh fetch; no `[VERIFY DOI]` placeholder present. §8. |
| CONT-03 | High | Not started (ledger table) / "limited Live contribution" (Batch 1 note) | PARTIALLY FIXED | Homepage gained one natural "research peptide" use (§1); GLP-2T's description paragraph was rewritten (§2). The ~109-word verbatim compliance block still recurs on every sampled product page including GLP-2T; sitewide boilerplate ratio is not materially reduced catalog-wide. §1, §2. |
| CONT-04 | High | Not started | STILL OPEN | 5 of 6 sampled SKUs still carry the same one-paragraph-description + 3-bullet + citations template with ~85–108 non-boilerplate words each; Bacteriostatic Water 30mL has zero product-specific description content (worse than any originally-sampled SKU). §2. |
| CONT-05 | High | Not started | PARTIALLY FIXED | One substantial (2,244-word), evidence-led guide is now live at `/guides/how-to-review-research-peptide-documentation/`, linking real batch pages. Still a single article, not a content program. §3. |
| CONT-06 | Medium | Partially complete | STILL OPEN | GLP-2T's three DOI strings remain plain text; no hyperlinks to doi.org/PubMed found in page source. §2. |
| CONT-07 | Medium | Not started (master table) / ambiguously claimed "Live implementation deployed" in Batch 1 checkpoint bullet | STILL OPEN | Two differently-worded FDA disclaimer paragraphs (Research Gate version and per-product "Intended use" version) both still present verbatim, co-occurring on every product page including GLP-2T. Flagging the release-note bullet as likely mislabeled (it describes the GLP-2T rewrite, which did not touch either disclaimer). §1. |
| CONT-08 | Medium | Blocked / input needed | BLOCKED BY REAL EVIDENCE | New guide's Article JSON-LD attributes authorship to the Organization entity, not a named person; the one Person entity in the graph ("beringela001") is a bare WordPress username with no bio/credentials and is not wired to the Article's `author` field. No real, approved author/reviewer published. §3. |
| CONT-09 | Medium | Blocked / input needed | BLOCKED BY REAL EVIDENCE | `/contact/` extracted content shows no phone or street address; page is dominated by Research Gate/FDA text. §8. |
| CONT-10 | Low | Not started | STILL OPEN | Not independently re-verified this session (requires out-of-stock modal interaction state); no release note claims a fix. §9. |
| CONT-11 | Low | Not started | VERIFIED FIXED | Homepage hero now contains one natural "research peptide" use, exactly as claimed in the Batch 1 release note. §1. |
| CONT-12 | Low | Superseded / no immediate fix | SUPERSEDED | Out of scope per task instructions (requires WP admin data); no public evidence gathered contradicts prior classification. §9. |
| CONT-13 | Low | Partially complete | STILL OPEN | "PepSelect" (one word) and "Pep Select" (two words) both still appear within the same legal documents (Terms, Privacy, Refund), including in the mailing address. §7. |
| CONT-14 | Low | Not started | STILL OPEN | New guide (2,244 words, technical/structured) and product pages (dense, citation-heavy) sit at one readability register while homepage/FAQ sit at a plainer register; no bridging content observed. Not independently re-scored with a formal readability metric this session — qualitative only. |
| GOOG-10 | Low | Not started | STILL OPEN | 7 identical non-descriptive "Learn more" links confirmed on `/shop/` product cards, no `aria-label` differentiation. §5. |
| GOOG-11 | Medium | Blocked / input needed | STILL OPEN | A `gtag.js` container (`GT-NNQ4N6DP`) is now detectable in the homepage source — new evidence not in the 8/18 ledger — but GA4 property/conversion configuration cannot be confirmed via public crawl. Recommend owner/GA4-admin verification. §6. |
| DFS-08 | Medium | Not started | STILL OPEN | New FAQ/guide content addresses Pep Select's own operational questions and COA-literacy, not the SERP-derived PAA question map. §4. |
| DFS-09 | Medium | Not started | STILL OPEN | No content found targeting the "bpc-157 for sale" commercial-intent SERP cluster; BPC-157's product page remains a standard thin templated page. §9. |

---

## Summary judgment on the catalog-wide thin-content/boilerplate problem

The catalog-wide thin-content/boilerplate problem (CONT-03/CONT-04/ECOM-07) has **not** meaningfully improved beyond the GLP-2T-only fix the 8/18 ledger already flagged as "limited." Fresh sampling of 6 SKUs on 2026-08-20 shows every product page — including the rewritten GLP-2T — still carries an identical ~109-word verbatim compliance block (dilution notice + intended-use + FDA statement), and 5 of 6 sampled SKUs still follow the same rigid one-paragraph-description-plus-three-bullets-plus-citations template with only 85–108 words of genuinely unique content. One SKU sampled this cycle, Bacteriostatic Water 30mL, has **no** product-specific description at all — a new, worse data point than anything in the original CONT-04 sample. The one real content investment this cycle — the 2,244-word evidence-led documentation guide — is genuinely good work (real batch citations, a shown failure case, real internal links) and meaningfully dents CONT-05/GEO-06, but it is a single article, not a scalable fix for the ~14+ product pages still running the thin template.

# Content Quality / E-E-A-T Re-Verification — pepselect.com
**Audit date:** 2026-08-23 | **Plugin:** Claude SEO v2.2.4 | **Scope:** Read-only, measurement only (no site changes, no paid APIs)
**Evidence base:** `docs/claude-seo-audit-2026-08-23/raw-crawl/pages/*.html` (46 URLs, fetched live 2026-08-23) + `extracted.json`. Parsed with trafilatura + BeautifulSoup from disk; not re-crawled.

---

## 1. Per-ID re-verification table

| ID | Priority | Classification | Evidence |
|----|----------|-----------------|----------|
| CONT-01 | Critical | BLOCKED BY REAL EVIDENCE (carried forward) | `/terms-of-service/` does not appear anywhere in the 46-URL crawl set, `all-urls.txt`, or any sitemap/header file in this evidence bundle, so the redirect cannot be re-confirmed from disk without a live fetch. No counter-evidence (broken link, 404, orphaned reference) was found either. Status carried forward as prior "VERIFIED FIXED" with the caveat that this pass could not independently re-test it. |
| CONT-02 | Critical | VERIFIED FIXED | `product_glp2-t20.html` DOI citations are real, resolvable-looking DOIs (e.g., `DOI:10.1172/jci.insight.140532`, `DOI:10.1073/pnas.2116506119`, `DOI:10.1016/j.ijbiomac.2025.146141`). String `[VERIFY DOI]` returns zero matches anywhere in the page. |
| CONT-03 | High | PARTIALLY FIXED (quantified) | The verbatim 60-word "Intended use" compliance paragraph ("Research Use Only. Supplied strictly for laboratory research...Use is restricted to qualified research professionals.") is byte-identical across **all 17/17 products** (100%), including the two new SKUs KPV and Cagrilintide. A second, differently-worded ~109-word "FDA Disclaimer:" paragraph also recurs on all 17/17 product pages (see CONT-07). Unique per-product content (Description + Research context + CAS + citations) averages only 71–99 words per page (see CONT-04), so boilerplate still makes up roughly 60–70% of each product's main-content word count. |
| CONT-04 | High | PARTIALLY FIXED | 16 of 17 products now carry a **unique** Description paragraph (23–39 words) + 3 "Research context" bullets + a CAS number + 1–3 real citations (DOI/PMID/PMCID), a real improvement over the previous single generic template. Measured unique-content-block word counts (Description + Research context + CAS + citations, excluding all compliance boilerplate): BPC-157 72, Cagrilintide 99, GHK-CU 80, GLP-1 S (semaglutide) 87, GLP-2T (tirzepatide) 95, GLP-3 R 10/20/30mg (retatrutide) 82 each, Glutathione 94, KPV 81, MOTS-C 89, NAD+ 73, PT-141 93, SS-31 91, TB-500 86, Tesamorelin 71. **Bacteriostatic Water 30mL still has ZERO product-specific content**: direct HTML inspection confirms the string "Description" does not appear anywhere on `product_bacteriostatic-water-30ml.html` — no Description tab, no Research context, no CAS number, no citations. This is the one product that remains exactly as thin as the prior audit found it. |
| CONT-05 | High | PARTIALLY FIXED | The guide `/guides/how-to-review-research-peptide-documentation/` grew from ~2,244–2,351 words to **3,250 words** (per `extracted.json`), so depth increased materially. It is still the only evidence-led guide on the site — no second or third guide exists in the 46-URL crawl set, so this remains a single asset, not a content program. |
| CONT-06 | Medium | STILL OPEN | Direct HTML inspection of `product_glp2-t20.html` (and pattern holds across all product pages) shows DOI strings sitting as plain text inside `<li>` elements (e.g., `<li>Willard FS, et al. JCI Insight. 2020. DOI:10.1172/jci.insight.140532. PMCID:PMC7526454</li>`) with no surrounding `<a href>` — confirmed unhyperlinked. |
| CONT-07 | Medium | STILL OPEN | Both the "Intended use" paragraph (60 words) and the separate "FDA Disclaimer:" paragraph (~109 words, "This company is not a compounding pharmacy...") co-occur verbatim on all 17/17 product pages, including the new KPV and Cagrilintide pages. Two differently worded regulatory disclaimers still overlap on every SKU. |
| CONT-08 | Medium | STILL OPEN (evidence now available — reclassified from BLOCKED BY REAL EVIDENCE) | The guide page's `Article` JSON-LD includes a `Person` node: `{"@type":"Person","name":"beringela001","image":{...avatar...},"sameAs":["https://pepselect.com"],"url":"https://pepselect.com/guides/author/beringela001/"}`. No `description`, `jobTitle`, `honorificSuffix`, or credential field is present — confirmed bare WordPress username with an avatar image, no bio. |
| CONT-09 | Medium | PARTIALLY FIXED (drift confirmed) | **Phone number is now live**: `1 (833) 737-7528` appears on `/contact/` (twice) and on the homepage (`root.html`), each with a working `tel:` link. This confirms the reported 8/20–22 change shipped. However, **no street/mailing address** was found on `/contact/` — the only "address" hits on that page are form-field labels ("Email Address cannot be empty"), not a physical address. Trustworthiness signal is improved but incomplete. |
| CONT-10 | Low | STILL OPEN (not deeply re-verified) | The out-of-stock UI is an inline subscribe form ("Out of stock" / "Email when stock available"), not a true modal — wording itself reads neutrally with no urgency/scarcity language. Prior specific concern was not itemized in the brief, so this is carried forward unchanged; flagging as low-confidence re-verification. `[VERIFY CLAIM]` |
| CONT-11 | Low | VERIFIED FIXED | Homepage hero lead paragraph still uses one natural instance: "You shouldn't need five tabs and a leap of faith to look into a research peptide." No stuffing detected elsewhere in the hero. |
| CONT-12 | Low | SUPERSEDED | Carried forward per instructions; not re-tested this pass. |
| CONT-13 | Low | STILL OPEN (counts unchanged) | Re-counted "PepSelect" (no space) vs. "Pep Select" (with space) across full rendered text of the four legal pages: Terms & Conditions 12 vs. 7; Privacy Policy 3 vs. 7; Refund & Shipping Policy 1 vs. 7; RUO Disclaimer 0 vs. 20. Identical to the counts cited in the prior audit brief — no drift, issue persists exactly as before. |
| CONT-14 | Low | STILL OPEN | Not deeply re-measured this pass (Flesch/grade-level scoring not run); qualitative register split persists — legal/compliance blocks read at a dense legal register while product Description/Research-context copy reads in plain language, consistent with the prior finding. `[VERIFY CLAIM]` |
| GOOG-10 | Low | STILL OPEN (count updated) | `/shop/` now has **9** identical `<a class="pepselect-card__action">Learn more</a>` links (up from 7, reflecting the 2 new SKUs), still with zero `aria-label` differentiation (0 matches for `aria-label="...Learn more..."`). |
| GEO-03 | High | STILL OPEN (passages shorter than before) | Measured the standalone Description paragraph — the most citable single passage — on 4 products: BPC-157 26 words, Cagrilintide 39 words, GHK-CU 29 words, KPV 23 words. This is even shorter than the previously flagged ~55–58-word passages, and remains far below the ~134–167-word AI-citation benchmark. The fuller unique content block (Description + Research context + CAS + citations, 71–99 words per product) still falls short of that benchmark as a single quotable passage. |

---

## 2. New findings (CONT-15+)

**CONT-15 (Medium) — Near-duplicate content across dosage-variant product pages.** The three Retatrutide SKUs (`product_glp3-r10`, `product_glp3-r20`, `product_glp3-r30` — GLP-3 R 10mg/20mg/30mg) carry byte-identical Description paragraphs, identical "Research context" bullets, identical CAS number, and identical citation list (Jastreboff AM et al., NEJM 2023; Retatrutide MASLD phase 2a, Nat Med 2024). Only price and stock status differ. This is a common e-commerce pattern for dosage variants, but combined with the sitewide compliance-block duplication (CONT-03/07) it further concentrates near-duplicate content across the catalog. Recommend at minimum a dosage-specific sentence (e.g., typical study concentrations at that strength) to differentiate the three pages for search and AI-citation purposes.

**CONT-16 (Low) — Contact page has a phone but no verifiable street/mailing address.** Now that a phone number is live (see CONT-09), the absence of a physical address is the more conspicuous remaining trust gap on `/contact/`, an E-E-A-T Trustworthiness signal Google's QRG explicitly looks for on transactional sites.

---

## 3. RUO compliance scan

Scanned extracted main-content text of all 46 crawled pages (guide, FAQ, all 17 product pages, all COA/testing pages, legal pages, contact, shop, homepage) for human-use, dosing, or therapeutic-claim language using pattern matching for: inject*, dos(e/es/age/ing), mg/kg, administer*, self-administer*, "take", swallow, apply-to-skin, weight-loss/lose-weight/burn-fat, cures/treats/heals, "you will feel", "users report", "patients", "clinically proven", "FDA approved", "prescription".

**Result: No RUO compliance violation found.** Every matched instance is part of the negating compliance/disclaimer language itself (e.g., "I will not administer these materials to humans or animals," "not intended to diagnose, treat, cure or prevent any disease," "Not for injection, ingestion, topical application, or any other form of personal...use" on `/terms-conditions/`). No page makes an affirmative human-use, dosing, or therapeutic claim. This is consistent with the brand voice guardrails in `.agents/product-marketing.md` (anti-persona language: no "patients," "users," "injectors," "dieters"), which are being followed in the live copy.

---

## 4. Changes since 2026-08-20

- **New phone number live:** `1 (833) 737-7528` with `tel:` link now appears on `/contact/` and the homepage header — confirms the reported 8/20–22 drift (CONT-09 partially fixed; street address still missing, CONT-16).
- **Two new SKUs added to catalog:** KPV (`/product/kpv10/`) and Cagrilintide (`/product/cag10/`), each shipped with the same unique-description-plus-boilerplate structure as the rest of the catalog (81 and 99 unique content words respectively) — not thinner than the existing 15 products, but they also inherit the same sitewide compliance-block duplication (CONT-03/07) and unhyperlinked DOIs (CONT-06).
- **Homepage batch-matching example swapped:** now references Tesamorelin batch `PSTES1071926GX` instead of the prior NAD+/GLP-3 R example — cosmetic/example change only, no content-quality impact.
- **Guide deepened:** `/guides/how-to-review-research-peptide-documentation/` grew from ~2,244–2,351 words to 3,250 words — still the only guide (CONT-05 partially fixed, program-level gap remains).
- **Shop page catalog growth:** "Learn more" link count on `/shop/` rose from 7 to 9 (GOOG-10 issue unchanged in kind, count updated).
- **No change** detected in: FDA disclaimer duplication (CONT-07), DOI hyperlinking (CONT-06), Person JSON-LD bio (CONT-08), legal-doc brand-name inconsistency (CONT-13), Bacteriostatic Water's zero-content description (part of CONT-04).

---

## Stop conditions / unresolved items

- CONT-01 (terms-of-service redirect) could not be independently re-tested from the local evidence bundle — `/terms-of-service/` is absent from the 46-URL crawl set and no header capture exists for it. Recommend a live redirect check outside this read-only pass.
- CONT-10 and CONT-14 are carried forward with low confidence (`[VERIFY CLAIM]`) — not deeply re-measured this pass under time constraints.

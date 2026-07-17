# WEB-2C Homepage Copy Draft

This document separates Paulo-approved public copy from proposed supporting copy, dynamic requirements, source-verification results, and internal implementation notes. Only text labeled approved public copy is approved for publication.

# 1. Page Objective

Help research purchasers identify Pep Select as a source of research compounds, understand how vial and batch identifiers connect to available documentation, and choose between browsing compounds and reviewing COAs. The page must earn confidence through specific records and clear status language while keeping complete testing history in the COA Archive.

# 2. Messaging Hierarchy

| Role | Direction |
|---|---|
| Primary message | Pep Select offers research compounds with a documentation-led path from the vial and its identifiers to available batch records. |
| Supporting message | Visitors can browse current compounds or review available COAs and testing history before deciding what to purchase. |
| Proof message | Compound, labeled strength, batch number, packaging identifiers, status, test metadata, and available laboratory documentation provide the evidence. Each field must come from its owning system. |
| Primary CTA | `Explore Compounds` |
| Strongest secondary CTA | `Review COAs` |

# 3. Approved Hero

**Approved public eyebrow:** Research Compounds

**Approved public headline:** Match the vial. Match the batch.

**Approved public supporting paragraph:** Pep Select is built around clear identifiers, current batch status, and access to available testing history. Explore the catalog, or review the records first.

**Approved primary CTA:** Explore Compounds

**Approved secondary CTA:** Review COAs

**Internal rationale:** The eyebrow identifies the product category, the signature line states the mission, and the paragraph directs visitors to the catalog or records without claiming universal testing or documentation coverage.

# 4. Complete Eight-Section Draft

## Section 1: Hero and primary actions

**Internal section purpose:** Identify the offering, establish the documentation-led mission, and serve purchase-ready and evidence-first visitors in the first viewport.

**Approved public eyebrow:** Research Compounds

**Approved public heading:** Match the vial. Match the batch.

**Approved public supporting copy:** Pep Select is built around clear identifiers, current batch status, and access to available testing history. Explore the catalog, or review the records first.

**CTA or link text:**

- Primary: Explore Compounds
- Secondary: Review COAs

**Required dynamic content:** Canonical Shop URL and canonical `/testing/` URL.

**Internal evidence notes:** “Research compounds” and the research-use boundary come from the approved product-marketing context. “Available” prevents the paragraph from promising documentation for every product or batch.

## Section 2: Match the vial to the batch

**Internal section purpose:** Make batch traceability concrete for visitors who understand evidence but may not know the documentation system.

**Approved public heading:** Every identifier should point to the same record.

**Proposed public supporting copy:** Start with what the Pep Select vial shows. Compound, labeled strength, batch number, cap, crimp, and packaging identity help distinguish one batch from another. The matching record can show its current testing status and available laboratory documentation.

**Proposed public identifier labels:**

- Compound
- Labeled Strength
- Batch Number
- Cap Color
- Crimp Color
- Packaging Identity
- Testing Status
- Laboratory Documentation

**CTA or link text:** Review Batch Documentation

**Required dynamic content:** Record-owned values for each displayed identifier; omit a field when the owning system has no value rather than filling it with invented text.

**Internal evidence notes:** The COA Archive source is not present in this repository. The Identifier-to-record mapping decision below governs every proposed field; none may be treated as confirmed plugin data until verified.

## Section 3: COA and testing-history preview

**Internal section purpose:** Show enough evidence to support evaluation, disclose that outcomes vary, and route visitors to the complete archive.

**Approved public heading:** Read the current record. Keep the history in view.

**Proposed public supporting copy:** Open a specific batch record to review the status and test details the COA Archive provides. Continue to the archive for available reports and historical detail.

**CTA or link text:**

- Record link: Review Complete Record
- Archive link: View Testing History

**Required dynamic content:**

- Compound
- Batch number
- Cap and crimp identity when recorded
- Current record status
- Test type
- Test date
- Laboratory when available
- Canonical link to the complete record

**Internal evidence notes:** The Archive preview behavior and Status terminology decisions below govern this section. Full testing records and historical detail stay in `/testing/`.

## Section 4: Featured compounds

**Internal section purpose:** Give purchase-ready visitors a concise, factual route into the current catalog after the documentation path has been established.

**Approved public heading:** Current research compounds

**Proposed public supporting copy:** Browse in-stock compounds from the current Pep Select catalog. Product details, availability, and prices come from the product record.

**CTA or link text:**

- Product card: View Compound Details
- Section link: Explore All Compounds

**Required dynamic content:** Four to six in-stock WooCommerce products using the canonical product title, Pep Select-owned product image, current price, stock state, and product URL.

**Internal evidence notes:** Selection must use an approved dynamic rule. Do not type product names, prices, or availability into homepage copy. Do not infer any medical, performance, or personal-use benefit from the compound name.

## Section 5: Documentation and status guide

**Internal section purpose:** Help visitors read status labels without treating a workflow state as a laboratory conclusion.

**Approved public heading:** Status before conclusions.

**Proposed public supporting copy:** Open the batch record to review its displayed status and available test details. Do not treat a workflow label as a conclusion about a test or batch.

**CTA or link text:** View Testing History

**Required dynamic content:** Exact plugin-owned status label, semantic status treatment, and canonical record or archive link.

**Internal evidence notes:** No exact public status label or meaning was source-verified. Do not publish homepage-authored labels or definitions. Render only the plugin-owned label after its supported interface is confirmed.

## Section 6: Transparency in practice

**Internal section purpose:** Explain the value of accurate record states without making unsupported promises about release controls or report publication.

**Approved public heading:** A record should show what happened.

**Proposed public supporting copy:** Review the status and available documentation attached to the batch record. Keep each statement tied to that record, without extending it to every batch or release.

**CTA or link text:** Review COAs

**Required dynamic content:** Batch identifiers, current plugin-owned status, and canonical links to available documentation.

**Internal evidence notes:** The approved heading is a transparency principle. Do not state that failed reports remain public, failed batches cannot be sold, reports are complete and unedited, every release receives the same testing scope, or every batch receives third-party testing until an operational owner supplies evidence.

## Section 7: Research-use boundaries and support

**Internal section purpose:** State the intended research context and give visitors a calm route to practical help.

**Approved public heading:** For laboratory research and analytical work.

**Proposed public supporting copy:** Pep Select compounds are presented for legitimate laboratory research and analytical purposes. Review the FAQ for common ordering and documentation questions, or contact Pep Select support when you need help with a product, order, or record.

**CTA or link text:**

- Read the FAQ
- Contact Support

**Required dynamic content:** Canonical FAQ and Contact URLs.

**Internal evidence notes:** Keep the approved research-use boundary and supported FAQ and Contact paths. Do not add human-use implications.

## Section 8: Final decision path

**Internal section purpose:** Repeat the approved choices after the visitor has seen the catalog and documentation framework.

**Approved public heading:** Start with the compound or start with the record.

**Proposed public supporting copy:** Browse current research compounds, or review available COAs and testing history before you continue.

**CTA or link text:**

- Primary: Explore Compounds
- Secondary: Review COAs

**Required dynamic content:** Canonical Shop URL and canonical `/testing/` URL.

**Internal evidence notes:** This section introduces no new proof, urgency, guarantee, or operational claim.

# 5. Image Direction

| Image area | Recommendation |
|---|---|
| Hero | Use a Pep Select-owned studio photograph showing a family of current Pep Select vials in a clean light-blue environment. Labels and packaging must represent real Pep Select materials. Keep enough negative space for the headline and CTAs. |
| Vial-to-batch section | Use owned detail photography of a real vial label, cap, crimp, and packaging identifiers. Pair details only with the correct batch record. |
| COA preview | Let the record interface and factual metadata carry the section. If an image is needed, use an owned documentation or packaging detail, not a staged scientist. |
| Featured compounds | Use the canonical Pep Select product image supplied by WooCommerce for each product. |
| Remaining sections | Prefer typography, status components, and owned packaging details over decorative photography. |

Do not use people, syringes, body or wellness imagery, transformations, administration cues, or generic laboratory stock photography. Do not reuse existing imagery unless Pep Select owns it and its subject, provenance, crop, and batch accuracy pass review.

# 6. Dynamic Content Requirements

## WooCommerce-owned content

- Four to six in-stock featured products selected by an approved rule
- Canonical product title
- Pep Select-owned product image
- Current WooCommerce price
- Current stock state
- Canonical product URL

## Proposed COA Archive-owned preview content

These are homepage requirements pending plugin-source or supported-interface verification, not confirmed fields:

- Compound
- Batch number
- Cap and crimp identity when recorded
- Current record status
- Test type
- Test date
- Laboratory when available
- Canonical complete-record URL
- Supported current and historical record behavior

## Fallback behavior

| Condition | Required behavior |
|---|---|
| No featured products | Hide the empty product grid and retain one `Explore Compounds` link to the catalog. Do not invent products or availability. |
| Missing product image | Use an approved Pep Select placeholder or a neutral non-deceptive card treatment. Do not use third-party or generic product photography. |
| Missing price | Omit the price field and keep `View Compound Details`; do not type a substitute price or affordability statement. |
| Missing batch field | Omit the unavailable field and preserve the record link; do not infer an identifier from another batch. |
| No recent COA records | Hide the empty preview list and retain `Review COAs` to the complete archive. Do not imply that records do not exist unless the archive confirms that state. |
| Unavailable laboratory name | Omit the laboratory field or use the plugin's confirmed unavailable-state label; do not guess the laboratory. |

## COA Archive source-verification result

The Pep Select COA Archive plugin source is not available in this repository. A targeted tracked-file and PHP/JavaScript search found only the legacy Elementor COA loop export and the child theme's canonical `/testing/` link. Neither source defines the current plugin's statuses, fields, sorting, record visibility, full-record routes, or retrieval interface.

| Verification target | Result |
|---|---|
| Exact public status labels | Not source-verified |
| Exact public status meanings | Not source-verified |
| Compound, strength, batch, cap, crimp, packaging, test type, test date, laboratory, and report-link fields | Not source-verified |
| Fields that may be missing | Not source-verified |
| Current-versus-historical sorting | Not source-verified |
| Public display of failed records | Not source-verified |
| Public display of pending or in-progress records | Not source-verified |
| Archive URL | `/testing/` is the approved canonical project route; plugin-source confirmation was unavailable |
| Full-record URL pattern | Not source-verified |
| Homepage-preview retrieval without duplicated business logic | Not source-verified |

**Implementation note, not public copy:** Inspect the installed plugin source or a documented public interface before implementation. The homepage may consume plugin-owned output but must not recreate COA status, visibility, record-selection, or testing rules in the child theme.

# 7. Compliance and Evidence Review

## Consolidated evidence decisions

| Evidence category | Current status | What must be confirmed | Draft action |
|---|---|---|---|
| Identifier-to-record mapping | `[VERIFY CLAIM]` | Plugin-owned fields and their mapping to the intended record | Keep the message, but do not render an identifier until the field and relationship are confirmed. |
| Status terminology | `[VERIFY CLAIM]` | Exact public labels and exact meanings from the COA Archive | Provisional definitions were removed. Render only verified plugin-owned labels. |
| Archive preview behavior | `[VERIFY CLAIM]` | Supported fields, optionality, sorting, failed/pending visibility, full-record URLs, and retrieval interface | Use only confirmed behavior. Keep complete reports and historical detail in `/testing/`. |
| Operational transparency and testing scope | `[VERIFY CLAIM]` | Failed-record retention, sale controls, report completeness/editing, testing scope, and third-party coverage | Keep stronger operational statements omitted until evidence exists. |
| Pricing accessibility | Resolved for this draft | Current WooCommerce product and price data | Show actual current prices only. Omit affordability, value, lowest-price, and unsupported premium comparisons. |
| Research-use and support language | Supported direction | Approved research-use wording plus canonical FAQ and Contact paths | Keep concise research-use wording and supported routes; exclude human-use implications. |

## Standing compliance exclusions

- Do not publish medical, therapeutic, performance, personal-use, administration, or human/animal-use implications.
- Do not publish purity percentages, guarantees, rankings, customer counts, or superiority claims without specific approved evidence.
- Do not infer shared batch identity from matching cap, crimp, label, vial, or packaging appearance.

# 8. Approved Draft Summary

## Approved hero

- Eyebrow: Research Compounds
- Headline: Match the vial. Match the batch.
- Supporting paragraph: Pep Select is built around clear identifiers, current batch status, and access to available testing history. Explore the catalog, or review the records first.
- Primary CTA: Explore Compounds
- Secondary CTA: Review COAs

## Approved public headings

1. Match the vial. Match the batch.
2. Every identifier should point to the same record.
3. Read the current record. Keep the history in view.
4. Current research compounds
5. Status before conclusions.
6. A record should show what happened.
7. For laboratory research and analytical work.
8. Start with the compound or start with the record.

## Remaining consolidated verification decisions

The four unresolved evidence categories are Identifier-to-record mapping, Status terminology, Archive preview behavior, and Operational transparency and testing scope. Their single governing flags appear in the consolidated evidence table; they are not repeated throughout the draft.

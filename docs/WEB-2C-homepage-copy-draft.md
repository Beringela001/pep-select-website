# WEB-2C Homepage Copy Draft

This is a review draft, not approved publication copy. Text labeled as internal notes, dynamic requirements, or `[VERIFY CLAIM]` must never appear as customer-facing copy.

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

# 3. Hero Concepts

## Option A: Signature mission (recommended)

**Optional eyebrow:** Research Compounds

**Headline:** Match the vial. Match the batch.

**Supporting paragraph:** Pep Select provides research compounds for laboratory research and analytical work, with clear product details and access to available batch documentation and testing history. Explore the catalog or review the records first.

**Primary CTA:** Explore Compounds

**Secondary CTA:** Review COAs

**Internal rationale:** The approved signature line leads, while the eyebrow and first sentence identify the product category without delay. The paragraph presents both visitor paths and limits documentation language to records that are available.

## Option B: Category first

**Optional eyebrow:** Pep Select

**Headline:** Research compounds with the record in view.

**Supporting paragraph:** Browse current compounds, check factual product details, and review available batch documentation through the Pep Select COA Archive.

**Primary CTA:** Explore Compounds

**Secondary CTA:** Review COAs

**Internal rationale:** This option names the category in the headline and moves the signature line into the next section. It is direct, but less distinctive than Option A.

## Option C: Evidence-first choice

**Optional eyebrow:** For Laboratory Research and Analytical Work

**Headline:** Start with the compound. Check the record.

**Supporting paragraph:** Pep Select gives research purchasers two clear paths: explore available compounds or examine current and historical testing records before moving forward.

**Primary CTA:** Explore Compounds

**Secondary CTA:** Review COAs

**Internal rationale:** This option emphasizes choice and evidence without claiming that every catalog item has the same documentation or testing outcome.

# 4. Complete Eight-Section Draft

## Section 1: Hero and primary actions

**Internal section purpose:** Identify the offering, establish the documentation-led mission, and serve purchase-ready and evidence-first visitors in the first viewport.

**Proposed public eyebrow:** Research Compounds

**Proposed public heading:** Match the vial. Match the batch.

**Proposed public supporting copy:** Pep Select provides research compounds for laboratory research and analytical work, with clear product details and access to available batch documentation and testing history. Explore the catalog or review the records first.

**CTA or link text:**

- Primary: Explore Compounds
- Secondary: Review COAs

**Required dynamic content:** Canonical Shop URL and canonical `/testing/` URL.

**Internal evidence notes:** “Research compounds” and the research-use boundary come from the approved product-marketing context. “Available” prevents the paragraph from promising documentation for every product or batch.

## Section 2: Match the vial to the batch

**Internal section purpose:** Make batch traceability concrete for visitors who understand evidence but may not know the documentation system.

**Proposed public heading:** The identifiers should lead to the record.

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

**Internal evidence notes:** Confirm that each listed packaging field exists in the COA Archive and maps to the intended record before publication. `[VERIFY CLAIM]`

## Section 3: COA and testing-history preview

**Internal section purpose:** Show enough evidence to support evaluation, disclose that outcomes vary, and route visitors to the complete archive.

**Proposed public heading:** Read the current record. Keep the history in view.

**Proposed public supporting copy:** Testing records can document favorable results, work in progress, incomplete information, no test for a listed scope, or a failed outcome. Read the status and test details for the specific batch. The complete record belongs in the COA Archive.

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

**Internal evidence notes:** The preview must render the plugin-owned status without converting every completed record into a pass. Confirm the supported query, sorting, field labels, and handling of current versus historical records. `[VERIFY CLAIM]`

## Section 4: Featured compounds

**Internal section purpose:** Give purchase-ready visitors a concise, factual route into the current catalog after the documentation path has been established.

**Proposed public heading:** Current research compounds

**Proposed public supporting copy:** Browse in-stock compounds from the current Pep Select catalog. Product details, availability, and prices come from the product record.

**CTA or link text:**

- Product card: View Compound Details
- Section link: Explore All Compounds

**Required dynamic content:** Four to six in-stock WooCommerce products using the canonical product title, Pep Select-owned product image, current price, stock state, and product URL.

**Internal evidence notes:** Selection must use an approved dynamic rule. Do not type product names, prices, or availability into homepage copy. Do not infer any medical, performance, or personal-use benefit from the compound name.

## Section 5: Documentation and status guide

**Internal section purpose:** Help visitors read status labels without treating a workflow state as a laboratory conclusion.

**Proposed public heading:** Read the status before the claim.

**Proposed public supporting copy:** A status tells you where a specific record stands. Open the record for the test scope, stated result, and available documentation.

**Proposed public status explanations:**

| Status family | Public explanation |
|---|---|
| Approved or Completed | The recorded review or test has reached its documented endpoint. Open the record to see the scope and stated outcome. |
| Testing or Verification in Progress | Work is underway. No final outcome should be inferred. |
| Pending | Required information or the next recorded step remains outstanding. |
| Failed | The documented test or verification did not meet its recorded criterion. Read the complete record for scope and result. |
| Not Tested | No test is recorded for the listed scope. |
| Not Applicable | The listed test or status does not apply to this record. |

**CTA or link text:** View Testing History

**Required dynamic content:** Exact plugin-owned status label, semantic status treatment, and canonical record or archive link.

**Internal evidence notes:** Confirm every label and explanation against the Pep Select COA Archive. Do not publish this provisional mapping until the plugin owner confirms the exact meaning of Approved, Completed, Testing, Verification in Progress, Pending, Failed, Not Tested, and Not Applicable. `[VERIFY CLAIM]`

## Section 6: Transparency in practice

**Internal section purpose:** Explain the value of accurate record states without making unsupported promises about release controls or report publication.

**Proposed public heading:** A record should say what is known.

**Proposed public supporting copy:** Pep Select organizes available batch information by identifier and status so researchers can distinguish one documented record from another. Pending, incomplete, not-tested, and failed outcomes must remain distinct from a pass. Review the record tied to the batch instead of relying on a broad assurance.

**CTA or link text:** Review COAs

**Required dynamic content:** Batch identifiers, current plugin-owned status, and canonical links to available documentation.

**Internal evidence notes:** Do not state that failed reports remain public, failed batches cannot be sold, reports are complete and unedited, every release receives the same testing scope, or every batch receives third-party testing until an operational owner supplies evidence. `[VERIFY CLAIM]`

## Section 7: Research-use boundaries and support

**Internal section purpose:** State the intended research context and give visitors a calm route to practical help.

**Proposed public heading:** For laboratory research and analytical work.

**Proposed public supporting copy:** Pep Select compounds are presented for legitimate laboratory research and analytical purposes. Review the FAQ for common ordering and documentation questions, or contact Pep Select support when you need help with a product, order, or record.

**CTA or link text:**

- Read the FAQ
- Contact Support

**Required dynamic content:** Canonical FAQ and Contact URLs.

**Internal evidence notes:** Confirm the final research-use statement with the approved launch language. Confirm the support topics and route against current operations. `[VERIFY CLAIM]`

## Section 8: Final decision path

**Internal section purpose:** Repeat the approved choices after the visitor has seen the catalog and documentation framework.

**Proposed public heading:** Start with the compound or start with the record.

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

## COA Archive-owned content

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

# 7. Compliance and Evidence Review

| Proposed statement or claim category | Status | Required source or evidence | Recommended action |
|---|---|---|---|
| Pep Select provides research compounds for laboratory research and analytical work | Supported | Approved product-marketing context | Use as the category and use boundary. |
| “Match the vial. Match the batch.” | Supported | Paulo's approved core line | Use prominently as the signature mission. |
| Access to available batch documentation and testing history | Supported | Confirmed COA Archive capability and `/testing/` route | Keep “available”; render record-specific facts dynamically. |
| Four to six current in-stock products with real prices | Supported | WooCommerce product, stock, and price data | Render dynamically; never type prices into page copy. |
| Packaging identifiers map to the intended COA record | Requires verification | COA Archive schema, record mapping, and operational owner | Confirm every displayed field and relationship before publication. `[VERIFY CLAIM]` |
| Provisional status names and explanations | Requires verification | COA Archive source definitions and plugin owner | Replace draft terminology with exact supported labels and meanings. `[VERIFY CLAIM]` |
| Failed reports remain public or failed batches are blocked from sale | Requires verification | Approved operating policy and representative records | Omit until verified; never turn intent into a factual claim. `[VERIFY CLAIM]` |
| Reports are published completely and unedited | Requires verification | Publication policy, data flow, and record review | Omit until verified. `[VERIFY CLAIM]` |
| Every release receives the same testing scope or every batch receives third-party testing | Prohibited without evidence | Batch-level laboratory records and approved operational policy | Do not publish as a universal claim. `[VERIFY CLAIM]` |
| “Affordable,” “lower-priced,” or “better value” | Requires verification | Approved pricing analysis with defined comparison basis and date | Show current prices instead; omit comparison language. `[VERIFY CLAIM]` |
| Medical, therapeutic, performance, personal-use, administration, or human/animal-use implications | Prohibited | Not applicable | Exclude from copy, metadata, CTAs, testimonials, and imagery. |
| Purity percentages, guarantees, rankings, customer counts, or superiority claims | Prohibited without specific approved evidence | Current source record and approval for the exact statement | Omit; do not invent proof. |

# 8. Recommended Draft

## Recommended hero

Use **Option A: Signature mission**.

- Eyebrow: Research Compounds
- Headline: Match the vial. Match the batch.
- Supporting paragraph: Pep Select provides research compounds for laboratory research and analytical work, with clear product details and access to available batch documentation and testing history. Explore the catalog or review the records first.
- Primary CTA: Explore Compounds
- Secondary CTA: Review COAs

## Recommended key supporting lines

- The identifiers should lead to the record.
- Read the current record. Keep the history in view.
- Read the status before the claim.
- A record should say what is known.
- Start with the compound or start with the record.

## Lines requiring Paulo's specific approval

- The complete Option A supporting paragraph
- “The identifiers should lead to the record.”
- “Read the current record. Keep the history in view.”
- “Read the status before the claim.”
- “A record should say what is known.”
- “Start with the compound or start with the record.”

## Remaining verification items

- Confirm that compound, strength, batch, cap, crimp, packaging, status, and laboratory-documentation fields map to the intended record. `[VERIFY CLAIM]`
- Confirm the exact COA Archive status names and public meanings. `[VERIFY CLAIM]`
- Confirm supported current/historical sorting, searching, and preview behavior. `[VERIFY CLAIM]`
- Confirm whether failed records remain public and whether failed batches are blocked from sale. `[VERIFY CLAIM]`
- Confirm whether reports are published completely and unedited. `[VERIFY CLAIM]`
- Confirm the final research-use language and supported Contact/FAQ topics. `[VERIFY CLAIM]`

# SEO Milestone 4, Batch 2 Evidence Register: 2026-08-19

## Purpose

This register controls the factual claims, SEO requirements, page ownership, and acceptance checks for the proposed guide `How to Review Research Peptide Documentation`.

The guide may explain how to inspect published documentation. It may not introduce medical, human-use, dosing, comparative-superiority, or unsupported laboratory claims.

## Claude SEO finding ledger

| Finding | Original priority | Requirement preserved from the audit | Batch 2 response | Status before publication |
|---|---:|---|---|---|
| `CONT-05` | High | Add a genuine informational content type, meet the audit's 1,500-word floor, and link into the COA archive. | Publish the first long-form guide as a WordPress post and link it to the Quality Archive and two permanent batch records. | Implemented on Staging; 1,613 rendered words |
| `GEO-06` | High | Add content mapped to vendor-evaluation and testing-documentation questions; ship with Article schema and direct archive/product links. | Explain the document fields a buyer can verify and demonstrate them with current Pep Select records. | Implemented on Staging; Article schema and links verified |
| `SXO-02` | High | Create trust/vendor-evaluation content using verified Pep Select practices without competitor or superiority claims. | Give readers a vendor-neutral checklist, then show how Pep Select publishes the same fields. | Implemented on Staging; awaiting visual approval |
| `SXO-03` | High | Create a candidate page for the trust/vendor-selection question cluster while staying inside RUO limits. | Answer what documentation to inspect before choosing a research peptide source. Human-use, effect, and safety questions remain outside this page. | Partial by design; candidate page implemented on Staging |

The priorities and meanings above remain unchanged. Batch 2 does not claim that one guide completes every informational-content recommendation in these findings.

## Approved source records

### Current, approved example

- Public record: `https://pepselect.com/testing/nad-500-mg/nd50026205jp/`
- Compound and labeled strength: NAD+ 500 mg
- Batch: `ND50026205JP`
- Laboratory named on the record: Freedom Diagnostics Testing
- Test date shown on the record: July 30, 2026
- COA reference shown on the record: `PepS2607280579`
- Identity line: NAD+ identity confirmed by LC-MS
- Purity line: HPLC-UV, 99.87%
- Average net content line: 454.82 mg
- Microbial line: no detectable microbial DNA by the method shown on the record
- Fentanyl line: not detected by the immunoassay shown on the record
- Endotoxin and elemental impurity lines: not performed; the record leaves those fields blank rather than presenting a result
- Release state: Current / Approved

Permitted use: demonstrate how a reader matches the compound, strength, batch code, lab, date, methods, results, and release decision within one permanent record.

### Not-released example

- Public record: `https://pepselect.com/testing/retatrutide-20mg/psrt2062926jp/`
- Compound and labeled strength: Retatrutide 20 mg
- Batch: `PSRT2062926JP`
- Laboratory named on the record: ILS Labs
- Test date shown on the record: July 28, 2026
- COA reference shown on the record: `COA-2026-AN6WH9`
- Identity line: LC-MS, Fail
- Purity line: HPLC, 100% Reported
- Release state: Did Not Pass Release Review / not released for sale

Permitted use: demonstrate that a reported purity value does not replace an identity result or a release decision. The guide must keep `100% Reported` separate from `Pass`.

### Corrected-strength example

- Public record: `https://pepselect.com/testing/retatrutide-10mg/rt2026205jp/`
- Compound presentation: Retatrutide 10 mg
- Batch: `RT2026205JP`
- Laboratory: Freedom Diagnostics Testing
- Laboratory COA reference/search code: `PepS2607280575`
- Supplier packaging shown in the laboratory photograph: 20 mg
- Net content reported by the laboratory: 11.51 mg
- Identity: confirmed as Retatrutide
- Purity: 99.63%
- Public-record handling: the original batch number and mismatch explanation remain visible; Pep Select lists and prices the batch as 10 mg to match the measured content.
- Source PDF supplied by Paulo: `C:\Users\paulo\Downloads\coa\PepS2607280575.pdf`

Permitted use: demonstrate how Pep Select preserves the original submitted-vial evidence and batch number while presenting the product at the measured strength. Do not convert 11.51 mg into an exact 10.00 mg laboratory result.

### Rejected NAD+ example

- Public record: `https://pepselect.com/testing/nad-500-mg/psnad562926jp/`
- Compound and labeled strength: NAD+ 500 mg
- Batch: `PSNAD562926JP`
- Laboratory: ILS Labs
- Cap: Gold
- Crimp: Orange
- Public release decision: purity fell below Pep Select's release threshold; the lot was rejected and no vials from the lot were sold.

Permitted use: demonstrate that the archive preserves the batch identity, packaging identifiers, rejection reason, and no-sale decision for a failed release review.

### Laboratory-source verification example

- The public batch-page design can show the named laboratory, COA reference, analysis date, `View Verified Lab Report`, and `Download Original PDF` actions together.
- The guide may explain that a record hosted on the named laboratory's own domain gives the reader an independent copy to compare against Pep Select's page and PDF.
- The guide may not describe an external link as an absolute guarantee. Readers must still verify the destination domain and matching report identifiers.

## Archive vocabulary

The COA plugin documentation defines distinct public states:

- `Current`: the current published Approved/Complete report selected by the archive workflow.
- `Incoming`: one public Pending record selected by the archive workflow.
- `Previous`: an older public Approved report.
- `Failed` history: may remain public but does not become the product card's current record.
- Test-result labels include `Pass`, `Fail`, `Pending`, `Reported`, `Not Tested`, and `Not Applicable`.

The guide must not merge these labels or infer a result from an empty field.

## Claim boundaries

### Allowed

- A reader can check whether the report names the same compound, strength, and batch code as the item under review.
- A named method answers the specific question associated with that test line.
- A report date and reference number help a reader identify and retrieve the record.
- A release decision is separate from an individual test result.
- One batch report does not automatically document another batch.
- Pep Select's public archive contains both approved/current records and a published record for a batch that was not released for sale.
- Public batch records can expose a photographed vial, batch code, cap, crimp, laboratory reference, original PDF, and external laboratory verification link when those source materials are available.
- Pep Select preserves the documented Retatrutide batch `RT2026205JP` label mismatch and presents the batch as 10 mg to match the measured net content rather than the supplier's 20 mg packaging label.
- Pep Select preserves the public rejection reason and sale-release decision for NAD+ batch `PSNAD562926JP` and Retatrutide batch `PSRT2062926JP`.
- Pep Select requires independent laboratory testing before a batch is released for sale. This is an owner-confirmed policy, not proof that every archive field has a completed result.

### Not allowed without new evidence and approval

- `Every product is independently tested` or any wording that confuses product-wide and batch-specific evidence.
- `All batches receive every test` or any fixed-panel promise.
- `100% pure`, `safe`, `sterile`, `free from contaminants`, or other conclusions broader than the exact record.
- Any statement that an HPLC purity result proves compound identity.
- Any medical, therapeutic, dosing, personal-outcome, administration, or human-use statement.
- Any statement that Pep Select is the best, cheapest, safest, most trusted, or superior to another vendor.
- Any accusation or characterization of a named competitor.
- Any invented author, reviewer, scientist, laboratory credential, certification, or years-in-business claim.

## Search intent and user job

- Primary intent: informational and commercial investigation.
- Reader: an analytical buyer comparing research peptide sources and trying to understand which documents belong to a product batch.
- Main job: leave the page knowing which fields to compare and where to inspect Pep Select's published batch records.
- Primary action: `Review COAs`.
- Secondary action: `Browse Research Compounds`.

## Proposed WordPress ownership

- Content source: standard WordPress Post, editable in the native WordPress editor.
- Category: `Guides`.
- Desired canonical URL: `https://pepselect.com/guides/how-to-review-research-peptide-documentation/`.
- Permalink approach: because the site currently has no published posts, set the post permalink structure to `/guides/%postname%/` only after a Staging backup and verification that no existing route is affected.
- Presentation owner: the child theme may supply guide-only templates and styles. It must not own the article text.
- SEO owner: Yoast owns title, description, canonical, Open Graph, and the base schema graph.
- Sitemap owner: Yoast. The child theme already restores `post-sitemap.xml` when an indexable post exists.
- Author representation: propose `Pep Select` as an Organization author. Do not invent a person or credentials. This does not close the audit's separate individual-expertise gap.

## Proposed metadata

- SEO title: `How to Review Research Peptide Documentation | Pep Select`
- Meta description: `Learn how Pep Select connects a research peptide COA to the photographed vial, batch number, cap, crimp, laboratory report, results, and release decision.`
- Slug: `how-to-review-research-peptide-documentation`
- Canonical: the final public guide URL
- Social title: same as SEO title
- Social description: same factual promise as the meta description

## Proposed internal links

| Location | Anchor | Destination | Purpose |
|---|---|---|---|
| Hero and final CTA | `Review COAs` | `https://pepselect.com/testing/` | Primary next step |
| Opening/intro | `Quality Archive` | `https://pepselect.com/testing/` | Explain where records live |
| Approved example | `NAD+ 500 mg batch ND50026205JP` | `https://pepselect.com/testing/nad-500-mg/nd50026205jp/` | Direct supporting evidence |
| Not-released example | `Retatrutide 20 mg batch PSRT2062926JP` | `https://pepselect.com/testing/retatrutide-20mg/psrt2062926jp/` | Direct supporting evidence |
| Corrected-strength example | `Retatrutide 10 mg batch RT2026205JP` | `https://pepselect.com/testing/retatrutide-10mg/rt2026205jp/` | Submitted-label and measured-content evidence |
| Rejected NAD+ example | `NAD+ batch PSNAD562926JP` | `https://pepselect.com/testing/nad-500-mg/psnad562926jp/` | Public rejection reason and packaging identifiers |
| Final secondary CTA | `Browse Research Compounds` | `https://pepselect.com/shop/` | Commercial path after education |

Product-page links are intentionally excluded from the examples until Staging confirms the current product URLs and stock/release relationship for each cited batch.

## Proposed Article schema contract

- `@type`: `Article`
- `headline`: exact H1
- `description`: approved meta description
- `mainEntityOfPage`: final canonical URL
- `datePublished` and `dateModified`: WordPress publication and revision timestamps
- `author`: Pep Select Organization entity, using the existing `https://pepselect.com/#organization` identity when Yoast permits a clean reference
- `publisher`: existing Pep Select Organization/OnlineStore entity
- `image`: approved guide hero image only if an original, relevant image is supplied; do not use an unrelated stock image merely to fill the property
- No `FAQPage` schema unless visible question-and-answer content is added and the final markup remains truthful

## Release checks

### Content

- At least 1,500 visible guide words, excluding navigation, footer, and schema.
- Exact factual examples match the current public records.
- No unsupported or prohibited claim appears.
- No `[VERIFY CLAIM]` marker appears.
- The writing sounds like Pep Select: clear, calm, practical, and human.

### Search and structure

- One indexable canonical URL returns HTTP 200.
- H1, title, description, canonical, Open Graph, and Article schema agree.
- Yoast exposes the guide in `post-sitemap.xml` and the root sitemap includes that child sitemap.
- The page links to the Quality Archive and all four supporting records.
- The guide appears in a visible site navigation path or content hub; sitemap-only discovery is insufficient.

### Experience

- Primary `Review COAs` action is visible near the top and bottom.
- The checklist and examples scan cleanly on desktop and mobile.
- No nested tinted cards, horizontal overflow, broken focus state, or layout shift.
- Header, footer, age gate, cart, checkout, account, products, and archive behavior remain unchanged.

### Measurement

- Record publication date and Search Console inspection state.
- Request indexing after Live publication.
- Check impressions and query wording after 8–12 weeks before changing the copy.
- Repeat the relevant DataForSEO query set after the same observation window.
- Do not claim a ranking or traffic improvement before measurement supports it.

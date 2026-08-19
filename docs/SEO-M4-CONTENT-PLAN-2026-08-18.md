# SEO Milestone 4 Content Plan — 2026-08-18

## Goal

Build original, evidence-led content that helps researchers understand Pep Select products, documentation, and batch records. The work follows the Claude SEO 2.2.4 findings without changing their priorities or meaning.

Milestone 4 does not promise rankings, indexing, AI citations, or conversion gains. Those outcomes require post-release measurement.

## Recovery and branch

- Staging recovery point: `Before Claude SEO Milestone 4 content batch 1 - 2026-08-18`, created August 18, 2026 at 10:43 PM America/New_York.
- The verified oldest manual Staging backup was removed to make room: `Before homepage batch-match trust section 0.22.0-beta.1 - 2026-08-15`, created August 15, 2026 at 2:27 AM.
- Git branch: `codex/seo-m4-content`.
- Live remains unchanged until Paulo approves a completed Milestone 4 release.

## Required content workflow

Every publishable batch follows this order:

1. Product-marketing context
2. Copywriting draft
3. Compliance and evidence review
4. Conversion and page-journey review
5. Copy-editing pass
6. Final natural-language cleanup
7. Paulo approval of exact copy
8. Staging implementation and testing
9. Live deployment only after separate approval

No `[VERIFY CLAIM]` text may reach Staging or Live.

## Batch 1 — Correct contradictions and copied product text

**Staging status:** Deployed and browser-verified on August 18, 2026 as child theme `0.25.0-beta.27`. Release evidence: [`claude-seo-latest/STAGING-SEO-M4-BATCH-1-RELEASE-2026-08-18.md`](claude-seo-latest/STAGING-SEO-M4-BATCH-1-RELEASE-2026-08-18.md). Live remains unchanged.

**Purpose:** Repair current content before adding new pages.

**Findings:** `ECOM-03`, `CONT-07`, `CONT-11`, with a limited contribution to `CONT-03`, `CONT-04`, and `ECOM-07`.

**Pages and owners:**

- GLP-2T product: child-theme compound library and WooCommerce Product JSON-LD description.
- Homepage hero: child-theme homepage template.
- Sitewide footer: child-theme footer copy.

**Changes:**

- Replace the supplier/manufacturer GLP-2T paragraph with Pep Select-specific, mechanism-only copy.
- Correct the product-name matching defect that currently prevents the approved GLP-2T library entry from rendering.
- Add one natural use of `research peptide` to the homepage body.
- Refine the sitewide testing sentence around Paulo's confirmed policy: independent laboratory testing is required before a compound is released for sale.

**Non-goals:**

- No prices, stock, products, orders, checkout, payments, shipping, rewards, or COA records change.
- No dosing, reconstitution, medical, human-use, outcome, safety, purity, superiority, or universal-testing claim.
- No new page or schema type in this batch.

**Acceptance:**

- GLP-2T visible copy and Product JSON-LD contain the approved unique description.
- Supplier boilerplate and `Manufactured under strict quality standards` are absent from the GLP-2T response.
- The homepage body contains one natural `research peptide` use.
- The footer states the pre-release testing policy without claiming that pending or unreleased compounds have completed testing.
- Desktop and mobile product, homepage, Shop, Cart, Checkout, Testing, and My Account checks pass.

## Batch 2 — Documentation and vendor-evaluation guide

**Purpose:** Create the first useful informational destination for visitors comparing research vendors and documentation practices.

**Findings:** `CONT-05`, `GEO-06`, `SXO-02`, and `SXO-03`.

**Proposed page:** `How to Review Research Peptide Documentation`.

**Primary action:** `Review COAs`.

**Content boundaries:**

- Teach readers to compare compound identity, labeled strength, batch number, report date, laboratory, method, result, and release status.
- Explain that one report does not automatically apply to every batch.
- Link to the Quality Archive and relevant Pep Select records.
- Use Pep Select’s published not-released records only where the current archive confirms the status.
- Describe Pep Select’s own records. Do not name or accuse competitors.

**Dependency:** Exact copy, evidence register, metadata, page layout, Article schema, and internal links require Paulo approval before implementation.

## Batch 3 — Research documentation hub and compliant question coverage

**Purpose:** Organize verified citations and answer useful questions that fit Pep Select’s research-only limits.

**Findings:** `CONT-05`, `GEO-03`, `GEO-06`, `SXO-03`, and `CONT-14`.

Candidate topics:

- What a batch-specific COA can and cannot show
- How Pep Select organizes current, previous, pending, and not-released records
- How to match a vial label to a public batch record
- How research-only labeling changes the questions Pep Select can answer

Questions about personal effects, dosing, medical risks, or consumer use are excluded.

## Batch 4 — Product depth and Shop trust density

**Purpose:** Replace the rigid catalog-wide description pattern with useful, compound-specific material and show accurate documentation status closer to the Shop decision point.

**Findings:** `CONT-03`, `CONT-04`, `GEO-03`, `SXO-04`, `SXO-05`, and `ECOM-07`.

**Method:**

- Work in small product groups.
- Build an evidence register for every product before drafting.
- Use current COA status dynamically where possible.
- Never imply that every product has a COA.
- Move verified documentation cues earlier in the product journey without changing commerce behavior.

## Blocked inputs

- Author or reviewer credentials remain blocked until Paulo supplies a real person and approves the public wording.
- Wholesale content remains blocked until Paulo confirms that Pep Select offers a wholesale path.
- USA legal-availability content requires qualified legal review before publication.
- Handling and storage guidance requires an approved source and scope. It may not include consumer-use instructions.

## Milestone exit check

- Each published page has one intent, one useful action, unique copy, an evidence register, supported metadata, and internal links.
- Every claim maps to a current Pep Select record or a cited source.
- No unsupported or prohibited claim ships.
- Staging regression passes before a Live deployment request.
- Search Console and DataForSEO measurements occur after indexing. They are outcome checks, not release claims.

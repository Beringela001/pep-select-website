# WEB-2C Homepage Visitor Journey

## Scope and source boundary

This document defines homepage objectives, information order, conversion paths, and evidence requirements. It does not provide final homepage copy or authorize implementation.

Sources are limited to the approved product-marketing context, the confidential Pep Select supplement, WEB-1 Staging findings, the WEB-2 rebuild plan, Elementor Homepage #571, and the authoritative Pep Select COA Archive 0.4.0 source described below. Confidential strategy language is not reproduced here.

## 1. Homepage objective

Help a research purchaser understand what Pep Select offers, how product and batch documentation can be reviewed, and where to continue. The page should support a confident next step through evidence and clear status information rather than broad quality or trust claims.

The homepage must serve two visitor states:

- Ready to browse: reach current research compounds quickly.
- Still evaluating: review available COAs and testing history before considering a product.

## 2. Primary visitor

Researchers and laboratory purchasers seeking clear compound information, batch documentation, testing-history access, reasonable pricing, and a professional purchasing experience.

The homepage must not address patients, consumers, personal experimenters, or people seeking health or performance outcomes.

### Internal customer-avatar review

Jordan Reyes is an internal decision lens, not a public persona or a source of public-facing copy. The avatar represents an analytical purchaser who is not necessarily a laboratory specialist, expects to spend approximately $150–$400 per order, and needs enough plain-language evidence to make an informed next step.

| Evaluation area | Journey implication |
|---|---|
| Visitor psychology | Make the path easy to scan, define technical status language, and let evidence precede reassurance. |
| Likely objections | Address vague sourcing, inflated pricing, unsupported claims, and blind-trust marketing through specific records, visible facts, and restrained language. |
| Reading level | Explain documentation relationships in plain language; reserve dense technical detail for the COA Archive. |
| Trust requirements | Surface batch traceability, available third-party testing records, professional support, and a dependable ordering path without implying universal coverage. |
| CTA hierarchy | Keep catalog exploration primary for purchase-ready visitors and make COA review the strongest secondary path for evidence-first visitors. |
| Pricing context | Show current WooCommerce prices where products appear. Treat pricing accessibility as supporting territory, not an unsupported comparative claim. |

## 3. Primary conversion action

**Approved primary CTA: `Explore Compounds`.**

This is the clearest path to the WooCommerce catalog, supports the site's commercial purpose, and uses approved CTA language. It should lead to the canonical Shop destination and remain visible in the first viewport. Documentation context and a COA path should sit beside it so the action does not imply unsupported product assurances.

The avatar review supports this decision: a visitor ready to evaluate a $150–$400 order needs a direct catalog path, while the adjacent documentation path answers skepticism without obstructing product discovery.

Paulo approved this as the homepage's single primary conversion action.

## 4. Secondary conversion action

**Approved strongest secondary CTA: `Review COAs`.**

This should lead to the canonical `/testing/` experience owned by the Pep Select COA Archive. It supports visitors who need evidence before browsing or purchasing and must remain visually secondary to `Explore Compounds` without being hidden.

The avatar review confirms this should be the strongest secondary CTA. It directly answers the need to verify batch documentation and testing history before relying on marketing language.

## 5. Questions the homepage must answer

1. What does Pep Select offer, and for what legitimate purpose?
2. Who is the catalog intended for?
3. How can a visitor browse current compounds?
4. What documentation can a visitor review before deciding what to purchase?
5. How are product, batch, and testing records related?
6. How are current, historical, pending, failed, unavailable, or untested records distinguished when those states exist?
7. Where can a visitor search or browse COAs and testing history?
8. Which featured compounds are currently available, and where does their displayed data come from?
9. What ordering or support path is available when a visitor needs help?
10. What research-use limitations apply?

## 6–8. Approved section order, purpose, and evidence

Use eight homepage sections.

| Order | Section | Purpose | Required evidence or dynamic data |
|---|---|---|---|
| 1 | Match the vial. Match the batch. | Identify Pep Select as a source of research compounds and present `Explore Compounds` plus strongest secondary `Review COAs`. | Canonical Shop and `/testing/` URLs; approved research-use framing; evidence source for every factual statement. Do not lead with an unverified price claim. |
| 2 | Every identifier should point to the same record. | Explain in plain language how compound, labeled strength, batch number, cap color, crimp color, and available batch imagery may connect a Pep Select vial to a record. | Plugin-owned compound and batch fields exist. Cap, crimp, and images are stage-dependent or optional; a fallback image must never be presented as exact batch packaging. Operational confirmation is still required before claiming that every physical vial maps to every displayed field. |
| 3 | Read the current record. Keep the history in view. | Preview available evidence in a scannable form, then route visitors to complete records and historical detail without using the obsolete COA custom post type. | Use only the smallest verified projection: compound and labeled strength, public batch number when available, plugin-owned public status label, one relevant date, and the canonical full-record link. Keep result detail and extended history in `/testing/`. |
| 4 | Current research compounds | Give ready-to-browse visitors four to six dynamically selected in-stock products with factual price visibility. | Dynamic WooCommerce title, Pep Select-owned image, current price, stock state, and canonical link; WEB-2D product-card foundation; missing-image and empty states. |
| 5 | Status before conclusions. | Help visitors read the plugin-owned public status without treating a workflow label as a laboratory conclusion. | Render the plugin-owned public label rather than translating stored administrative values in the theme. Default public labels are configurable, so homepage copy must not hard-code them. |
| 6 | A record should show what happened. | State the approved transparency principle without asserting unconfirmed failed-record retention, release controls, report completeness, or universal testing scope. | Stronger operational statements remain omitted or `[VERIFY CLAIM]` until supported. |
| 7 | For laboratory research and analytical work. | State the research-use boundary and provide concise paths to FAQ and Contact Support. | Approved current research-use language; canonical FAQ and Contact routes; no medical, personal-use, or animal-use implications. |
| 8 | Start with the compound or start with the record. | Repeat the primary catalog action and provide the secondary documentation path after the visitor has reviewed the page. | Canonical Shop and `/testing/` routes; no new claims, urgency, scarcity, guarantees, or unsupported reassurance. |

### Avatar review conclusions

1. `Explore Compounds` is the approved primary CTA.
2. `Review COAs` is the approved strongest secondary CTA and should be visible alongside the primary action where space permits.
3. The eight-section order is approved. Documentation and COA access appear before featured products, resolving evidence and sourcing objections early without delaying the catalog path.
4. Pricing accessibility is a supporting homepage message territory. Use actual WooCommerce prices and evidence-backed value context; claims such as affordable, lower-priced, or better value require `[VERIFY CLAIM]`.
5. Before the archive handoff, show only a scannable testing summary: record availability or status, the relevant compound or batch identifier, and sourced test metadata when available. Keep raw reports, dense history, and full current/historical records in `/testing/`.
6. The CTA hierarchy, eight-section order, COA-before-products sequence, and testing-detail boundary are approved. Exact copy and unresolved evidence still require review before publication.

## 9. Claims requiring verification

Mark these `[VERIFY CLAIM]` until a current record and owner support the exact wording:

- Every product, peptide, batch, or release is tested, vetted, verified, reviewed, passed, pure, sterile, or supported by a COA.
- Every release follows the current homepage's four-stage process.
- Compounds are reviewed before release or before catalog publication.
- Pep Select uses small-batch curation or transparent sourcing standards.
- A stated number or percentage of compounds is selected, verified, tracked, tested, or research-only.
- Current and historical testing records are complete, permanent, unedited, or available for every batch.
- Third-party laboratory testing applies beyond the specific documented batch, test, and result.
- Pep Select provides higher quality, greater consistency, superior products, or better documentation than another seller.
- Pep Select is trusted by researchers or supported by the current unattributed testimonials.
- Fulfillment, support, availability, pricing, or turnaround claims that lack current operational evidence.
- A homepage batch lookup exists or behaves like the archive search. The plugin's archive search supports compound identity, labeled strength, and eligible public batch numbers, but no separate homepage-search interface is implemented.

Do not add purity percentages, health outcomes, medical language, personal-use guidance, customer counts, rankings, guarantees, or comparative accusations.

## 10. Current Homepage #571 disposition

| Current area | Decision | Reason |
|---|---|---|
| Hero and dual CTA concept | Rebuild | Preserve the two clear paths, but replace hard-coded Staging URLs, obsolete `/coas/`, and universal vetting/testing language. |
| Smooth-text marquee | Remove | Plugin-specific decoration uses small text and includes unsupported trust/testing phrases; it delays the main tasks. |
| Standalone laboratory image | Remove | Avoid generic laboratory stock photography. Use only Pep Select-owned product, vial, packaging, or identification imagery. |
| Three standards cards | Replace | Use the approved Transparency in Practice section instead of carrying forward generic standards claims or repeated inline badge CSS. |
| Featured compounds heading and loop | Retain concept; rebuild implementation | Use WooCommerce data and the WEB-2D card system. Do not keep the shared Dark Loop template `65` as the homepage dependency. |
| About narrative and counters | Rebuild narrative; remove counters | Move the useful mission into the documentation sections. Remove the `20+` and percentage counters unless current records support them. |
| Four-stage process | Remove | The current statement that every release passes all four stages is unverified. Transparency in Practice replaces this structure without asserting an unconfirmed process. |
| Batch COA lookup | Retain purpose; replace implementation | Use the Pep Select COA Archive and `/testing/`. Do not reuse the legacy `search` widget configured for post type `coa` and loop template `485`. |
| Testimonials | Remove | The three statements are unattributed and cannot be treated as proof. |
| FAQ accordion | Rebuild or reduce to a FAQ link | ElementsKit is disabled, the current order-link answer is obsolete, and the FAQ widget is not a durable dependency. Use verified questions only. |
| Closing CTA | Rebuild | Retain a final decision point, but use the approved CTA hierarchy and no generic quality language. |

The export contains 15 top-level containers, 62 widgets, repeated inline badge CSS, hard-coded Staging destinations, a shared loop template, legacy COA search configuration, and an ElementsKit accordion. No `hide_desktop`, `hide_tablet`, or `hide_mobile` value set to `yes` was found in this supplied JSON; verify the WEB-2 hidden-remnant concern against Staging before deletion decisions.

## 11. Mobile-priority ordering

1. Identify Pep Select and the research-use context.
2. Show `Explore Compounds` and `Review COAs` as comfortable, distinct tap targets.
3. Explain documentation and traceability without a marquee or decorative interruption.
4. Provide a concise COA/testing summary and archive path before a long product list.
5. Show a limited featured-product set with current price visibility using the approved responsive card system.
6. Explain status meanings with text, not color alone.
7. Keep Transparency in Practice concise and evidence-bound on mobile.
8. Keep research-use, FAQ, Contact, and final actions visible without a long accordion.

Mobile must avoid horizontal overflow, tiny technical text, excessive stacked spacing, hidden evidence links, or floating controls covering primary actions.

## 12. Acceptance criteria before copywriting begins

- `Explore Compounds` is approved as the primary CTA and `Review COAs` as the strongest secondary CTA.
- The eight-section journey, COA-before-products order, and mobile priority are approved.
- Every planned factual claim has an evidence source, owner, and approval state; unresolved statements use `[VERIFY CLAIM]` or are removed.
- The homepage integration uses plugin-owned visibility, status, and sorting logic; a supported narrow preview interface and its empty/error states are approved before implementation.
- Four to six dynamically selected in-stock products, factual WooCommerce price display, and Pep Select-owned imagery are approved.
- WEB-2D product-card dependencies and homepage data ownership are agreed before product cards are built.
- Transparency in Practice replaces the proposed operating-standards section; unconfirmed operational statements remain `[VERIFY CLAIM]`.
- Hero imagery uses a clean studio family of Pep Select vials in a light-blue environment; no people or generic laboratory stock photography.
- Research-use, ordering, FAQ, and support facts match current operations.
- No competitor-derived material, unsupported testimonial, medical implication, or confidential strategy language enters the copy brief.
- Publication remains blocked until Paulo approves the exact draft wording and all required evidence decisions are resolved.

## Deferred WEB-2C implementation fixes

- Coded-header logo dimensions must remain consistent on every page.
- The Rewards control must remain visually consistent on every page.
- Page-specific Elementor or WooCommerce CSS must not override the coded header.

These are global-shell regression requirements for implementation and QA. They do not authorize header redesign during the homepage copy phase.

## Approved WEB-2C homepage copy decisions

- Use eight sections in the approved order shown above, with the COA and testing-history preview before featured products.
- Use `Explore Compounds` as the primary CTA and `Review COAs` as the strongest secondary CTA.
- Use “Match the vial. Match the batch.” as the central Pep Select mission and signature line while identifying the research-compound category clearly.
- Show four to six in-stock featured products using dynamic WooCommerce data and real current prices without unsupported affordability comparisons.
- Use only Pep Select-owned product and vial imagery. Prefer a clean studio family of Pep Select vials in a light-blue environment; avoid people and generic laboratory stock photography.
- Use Transparency in Practice instead of an operating-standards section. Flag every unconfirmed operational statement with `[VERIFY CLAIM]`.
- Keep complete testing records in the Pep Select COA Archive rather than overcrowding the homepage.
- Use the approved hero exactly: eyebrow `Research Compounds`; headline `Match the vial. Match the batch.`; supporting paragraph “Pep Select is built around clear identifiers, current batch status, and access to available testing history. Explore the catalog, or review the records first.”
- Use the eight approved public headings shown in the section-order table.

The remaining decisions concern exact draft wording and verified data behavior, not the approved journey structure.

## Targeted COA source-verification result

Verification used the clean extracted 0.4.0 source at `C:\Users\paulo\Documents\Pep Select COA Page\pepselect-coa-archive` (external project commit `5ab62eb0387956ac7999d44a74c642d10f490fc8`). The stable package `C:\Users\paulo\Documents\Pep Select COA Page\pepselect-coa-archive-0.4.0.zip` was retained read-only as the release reference.

### Verified plugin capability and terminology

- Current stored outcomes are `pending`, `approved`, and `failed`. Accepted legacy stored values are `archived`, `superseded`, `in-testing`, and `vendor-vetting`; they are normalized or retained for migration, not proposed as new homepage terminology.
- Stored workflow stages are `vendor-vetting`, `waiting-on-vendor`, `submitted-to-lab`, `in-testing`, and `complete`. Their default public labels are `Vetting Vendor`, `Waiting on Vendor`, `Submitted to Laboratory`, `Verification in Progress`, and `Complete`.
- Eligible pending records in the four incoming stages and eligible completed approved or failed records can be public. `incoming` is a public grouping, not a stored outcome; it covers pending vendor-vetting, waiting, submitted, and in-progress records that satisfy public requirements. Failed records can appear in compound history; compounds represented only by failed records are excluded from the main archive unless the configurable failed-only setting is enabled.
- Default public assurance labels include `Full-QC Documented` for approved records and `Did Not Pass Release Review` for failed records. These labels are configurable. Other templates use context-specific labels such as `Approved`, `Not Released`, and `Incoming`; the homepage must consume the plugin-provided label for its selected view rather than inventing a glossary.
- Current and historical records are both supported. Approved records sort current first, then by test date and publication date; incoming records use expected date and workflow priority; failed records sort newest first. Compound history shows the latest approved record, up to ten previous approved or failed records, and incoming records separately.
- Public routes are `/testing/`, `/testing/{compound-slug}/`, and `/testing/{compound-slug}/{batch-slug}/`. Archive search supports compound identity, labeled strength, and eligible public batch numbers.
- Existing shortcodes are `[pepselect_coa_archive]`, `[pepselect_compound_history]`, `[pepselect_coa_report]`, and the product-context `[pepselect_product_coa_carousel]`. Internal `COA_Test_Repository`, `Frontend_Router`, and `Frontend_View_Model` methods centralize visibility, selection, sorting, labels, and URLs. The post types use WordPress REST support, but the safe REST metadata projection is too limited for this preview; no custom REST endpoint or supported generic homepage-preview helper was found.

### Verified field availability

| Field group | Public capability and legitimate gaps |
|---|---|
| Compound and labeled/claimed strength | Compound display identity and strength exist publicly. Compound identity fields are required; test-level claimed content and unit also exist but may be blank. |
| Batch and packaging identity | Batch number, cap color, crimp color, batch-vial photo, and additional identity photos exist. Availability depends on workflow stage and legacy-record exemptions; fallback imagery is not proof of exact packaging. |
| Status, stage, and report type | Stored outcome and workflow stage exist. Public status labels are computed/configurable. Report type is computed as `Full-QC`, `Purity`, `Purity + Identity`, or `Partial QC`; there is no single stored report-type field. |
| Testing scope and results | Purity, identity, sterility, heavy metals, endotoxin, fentanyl, and net-content evidence can be public for completed records or explicitly enabled real partial results. Individual categories may be blank, pending, not tested, or not applicable; there is no single stored test-type/scope field. |
| Dates, laboratory, and report link | Test date, expected report date, testing laboratory, and lab-report URL exist. Test date is exposed for completed records; expected date is stage-bound; laboratory and report URLs depend on stage/outcome and may legitimately be absent. |
| Current/history marker | An `is_current` marker exists, but public queries—not homepage code—must decide the latest, current, previous, incoming, and failed groups. |

### Homepage field boundary

The verified minimum preview is compound and labeled strength, public batch number when available, the plugin-owned public status label, one relevant date (test date for completed records or expected report date for eligible incoming records), and the canonical full-record link. Cap and crimp colors, exact batch imagery, laboratory, report type (`Full-QC`, `Purity`, `Purity + Identity`, or `Partial QC`), and detailed results exist but are optional, conditional, or unnecessarily dense for the homepage. A batch photo is exact only when the view model identifies it as the batch-vial source; compound images and the local placeholder are fallbacks.

Implementation should add or approve a narrow plugin-owned homepage projection instead of recreating status, visibility, sorting, or URL rules in the child theme. The source verifies plugin behavior, not operational promises that every batch is tested, every failed batch was withheld from sale, every record remains public, reports are unmodified, or every release receives the same scope; those claims still require separate business confirmation.

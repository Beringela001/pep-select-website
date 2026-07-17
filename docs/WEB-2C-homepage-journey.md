# WEB-2C Homepage Visitor Journey

## Scope and source boundary

This document defines homepage objectives, information order, conversion paths, and evidence requirements. It does not provide final homepage copy or authorize implementation.

Sources are limited to the approved product-marketing context, the confidential Pep Select supplement, WEB-1 Staging findings, the WEB-2 rebuild plan, and Elementor Homepage #571. Confidential strategy language is not reproduced here.

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
| 1 | Hero and primary actions | Identify Pep Select as a source of research compounds and present `Explore Compounds` plus strongest secondary `Review COAs`. | Canonical Shop and `/testing/` URLs; approved research-use framing; evidence source for every factual statement. Do not lead with an unverified price claim. |
| 2 | Match the vial to the batch | Explain in plain language how compound, labeled strength, batch number, cap color, crimp color, packaging identity, testing status, and available laboratory documentation connect a Pep Select vial to a record. | Confirmed data fields and relationships from the COA Archive and operating process; `[VERIFY CLAIM]` for any universal coverage statement. |
| 3 | COA and testing-history preview | Preview available evidence in a scannable form, then route visitors to complete current and historical testing records without using the obsolete COA custom post type. | Pep Select COA Archive output and `/testing/` route; supported query behavior; sourced compound, batch number, cap/crimp identity when recorded, record status, test type, date, and laboratory when available; empty and error states. |
| 4 | Featured compounds | Give ready-to-browse visitors four to six dynamically selected in-stock products with factual price visibility. | Dynamic WooCommerce title, Pep Select-owned image, current price, stock state, and canonical link; WEB-2D product-card foundation; missing-image and empty states. |
| 5 | Documentation and status guide | Help visitors interpret documented states without treating every status as a pass. | Exact terminology supported by the COA Archive; text or icons in addition to color; no inferred laboratory result. |
| 6 | Transparency in practice | Focus on visible records, accurate statuses, documentation access, and distinguishing one Pep Select batch from another. | Plugin-owned records and status data; `[VERIFY CLAIM]` for any statement about publication completeness, failed-report retention, release controls, testing scope, or universal third-party testing. |
| 7 | Research-use boundaries and support | State the research-use boundary and provide concise paths to FAQ and Contact Support. | Approved current research-use language; canonical FAQ and Contact routes; no medical, personal-use, or animal-use implications. |
| 8 | Final decision path | Repeat the primary catalog action and provide the secondary documentation path after the visitor has reviewed the page. | Canonical Shop and `/testing/` routes; no new claims, urgency, scarcity, guarantees, or unsupported reassurance. |

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
- A homepage batch lookup can find a record by batch number; the current export's legacy `coa` search is not proof of the COA Archive's supported query behavior.

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
- COA Archive owners confirm the supported current/historical record behavior, exact homepage preview fields, status definitions, search route, and empty/error states.
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

The remaining decisions concern exact draft wording and verified data behavior, not the approved journey structure.

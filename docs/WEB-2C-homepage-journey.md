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

## 3. Primary conversion action

**Recommended primary CTA: `Explore Compounds`.**

This is the clearest path to the WooCommerce catalog, supports the site's commercial purpose, and uses approved CTA language. It should lead to the canonical Shop destination and remain visible in the first viewport. Documentation context and a COA path should sit beside it so the action does not imply unsupported product assurances.

Paulo must approve this as the homepage's single primary conversion action before copywriting begins.

## 4. Secondary conversion action

**Recommended secondary CTA: `Review COAs`.**

This should lead to the canonical `/testing/` experience owned by the Pep Select COA Archive. It supports visitors who need evidence before browsing or purchasing and must remain visually secondary to `Explore Compounds` without being hidden.

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

## 6–8. Recommended section order, purpose, and evidence

Use eight homepage sections.

| Order | Section | Purpose | Required evidence or dynamic data |
|---|---|---|---|
| 1 | Orientation and primary actions | Identify Pep Select as a research-compound store, establish documentation-led positioning, and present `Explore Compounds` plus secondary `Review COAs`. | Canonical Shop and `/testing/` URLs; approved research-use framing; evidence source for every factual statement. |
| 2 | Documentation and traceability | Explain what visitors can inspect and how product, batch, packaging, testing status, and available documentation relate. | Confirmed data fields and relationships from the COA Archive and operating process; exact status definitions; `[VERIFY CLAIM]` for any universal coverage statement. |
| 3 | COA and testing-history access | Give evidence-seeking visitors a direct route to current and historical testing records without using the obsolete COA custom post type. | Pep Select COA Archive output and `/testing/` route; supported query behavior; current/historical, empty, unavailable, pending, and error states. |
| 4 | Featured compounds | Give ready-to-browse visitors a concise view of selected current products. | WooCommerce product title, image, price, stock state, canonical link, and approved selection rule; WEB-2D product-card foundation; missing-image and empty states. |
| 5 | Documentation/status guide | Help visitors interpret the labels and record states they may encounter without treating every status as a pass. | Approved semantic status definitions and source fields; text or icons in addition to color; no inferred laboratory result. |
| 6 | Verified operating standards | Explain only the selection, documentation, review, or release steps Pep Select can substantiate. Omit the section if the process cannot be verified. | Named operational owner and evidence for each step; current policy or record; no claim that every release follows a process unless confirmed. |
| 7 | Research-use and decision support | State the research-use boundary and provide concise paths to FAQ and Contact for ordering, documentation, or support questions. | Approved current research-use language; canonical FAQ and Contact routes; current ordering/support workflow; no medical or personal-use implications. |
| 8 | Final decision path | Repeat the primary catalog action and provide the secondary documentation path after the visitor has reviewed the page. | Canonical Shop and `/testing/` routes; no new claims, urgency, scarcity, guarantees, or unsupported reassurance. |

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
| Standalone laboratory image | Retain only as a candidate asset | Reuse only if Paulo approves its relevance, provenance, crop, responsive behavior, and meaningful alt text. |
| Three standards cards | Rebuild conditionally | The information pattern may help, but each selection, batch, and review statement needs operational evidence. Remove repeated inline badge CSS. |
| Featured compounds heading and loop | Retain concept; rebuild implementation | Use WooCommerce data and the WEB-2D card system. Do not keep the shared Dark Loop template `65` as the homepage dependency. |
| About narrative and counters | Rebuild narrative; remove counters | Move the useful mission into the documentation sections. Remove the `20+` and percentage counters unless current records support them. |
| Four-stage process | Rebuild conditionally | Keep only verified steps. The current statement that every release passes all four stages is unverified. |
| Batch COA lookup | Retain purpose; replace implementation | Use the Pep Select COA Archive and `/testing/`. Do not reuse the legacy `search` widget configured for post type `coa` and loop template `485`. |
| Testimonials | Remove | The three statements are unattributed and cannot be treated as proof. |
| FAQ accordion | Rebuild or reduce to a FAQ link | ElementsKit is disabled, the current order-link answer is obsolete, and the FAQ widget is not a durable dependency. Use verified questions only. |
| Closing CTA | Rebuild | Retain a final decision point, but use the approved CTA hierarchy and no generic quality language. |

The export contains 15 top-level containers, 62 widgets, repeated inline badge CSS, hard-coded Staging destinations, a shared loop template, legacy COA search configuration, and an ElementsKit accordion. No `hide_desktop`, `hide_tablet`, or `hide_mobile` value set to `yes` was found in this supplied JSON; verify the WEB-2 hidden-remnant concern against Staging before deletion decisions.

## 11. Mobile-priority ordering

1. Identify Pep Select and the research-use context.
2. Show `Explore Compounds` and `Review COAs` as comfortable, distinct tap targets.
3. Explain documentation and traceability without a marquee or decorative interruption.
4. Provide the COA/testing-history path before a long product list.
5. Show a limited featured-product set using the approved responsive card system.
6. Explain status meanings with text, not color alone.
7. Include verified standards only if they remain concise on mobile.
8. Keep research-use, FAQ, Contact, and final actions visible without a long accordion.

Mobile must avoid horizontal overflow, tiny technical text, excessive stacked spacing, hidden evidence links, or floating controls covering primary actions.

## 12. Acceptance criteria before copywriting begins

- Paulo approves `Explore Compounds` as the primary CTA and `Review COAs` as the secondary CTA.
- Paulo approves the eight-section journey and mobile priority.
- Every planned factual claim has an evidence source, owner, and approval state; unresolved statements use `[VERIFY CLAIM]` or are removed.
- COA Archive owners confirm the supported current/historical record behavior, status definitions, search route, and empty/error states.
- The featured-product selection rule and treatment of out-of-stock products are approved.
- WEB-2D product-card dependencies and homepage data ownership are agreed before product cards are built.
- The operating-standards section is either supported by current policy/records or removed.
- Any retained image has approved provenance, purpose, crop, and alt-text direction.
- Research-use, ordering, FAQ, and support facts match current operations.
- No competitor-derived material, unsupported testimonial, medical implication, or confidential strategy language enters the copy brief.
- Copywriting remains blocked until Paulo approves the objective, audience, CTA hierarchy, section order, and unresolved evidence decisions.

## Deferred WEB-2C implementation fixes

- Coded-header logo dimensions must remain consistent on every page.
- The Rewards control must remain visually consistent on every page.
- Page-specific Elementor or WooCommerce CSS must not override the coded header.

These are global-shell regression requirements for implementation and QA. They do not authorize header redesign during the homepage copy phase.

## Decisions requiring Paulo's approval

- Confirm `Explore Compounds` as the primary homepage CTA.
- Approve the eight-section order and whether COA access should remain before featured compounds.
- Approve the featured-product selection rule, including out-of-stock handling.
- Decide whether a verified operating-standards section belongs on the homepage.
- Approve or reject the existing laboratory image as a candidate asset.
- Confirm how much current and historical testing information should appear on the homepage versus `/testing/`.

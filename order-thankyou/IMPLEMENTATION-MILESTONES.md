# Pep Select order page and thank-you card milestones

Status: design concept only. No production code, WooCommerce data, Ops data, staging, or Live environment changed.

## Architecture decision

Build the customer order experience as a dedicated WordPress plugin, tentatively named **Pep Select Order Experience**.

The plugin should own the secure order endpoint, WooCommerce permission checks, order-to-batch metadata, the related-compound rules, discount presentation, reorder behavior, templates, and feature settings. The child theme should supply narrow visual integration only. The COA plugin should remain the source of public batch records and media. WooCommerce remains the source of order, customer, totals, and line-item data.

Deactivation gives Pep Select a clean operational off-switch. The plugin must remove its routes and enhancements without deleting order metadata. Default WooCommerce `view-order` pages must continue to work after deactivation.

Ops should extend its existing Woo order, batch-allocation, PDF, QR, PrintNode, and print-log system. A second Ops application or independent card generator would duplicate working infrastructure.

## Milestone 0: approve the experience and language

Website scope:

- Approve the order-page hierarchy, desktop composition, mobile composition, exact labels, discount placement, related-product heading, and privacy boundary.
- Decide whether the QR opens a login-first full order view or a low-information public shell followed by verification.
- Lock which FAQ storage statements may appear.
- Do not publish a reconstituted-stability duration until Pep Select has a compound-specific source and approved wording.

Ops scope:

- Provide the physical card PDF template, finished dimensions, bleed, safe area, printer model, stock orientation, and whether Ops should download a PDF or send it to PrintNode.
- Approve the front and back card copy.

Exit criteria:

- Paulo approves one page visual and one printed-card visual.
- Privacy model and card dimensions are fixed.

## Milestone 1: define the Website ↔ Ops order-batch contract

Website scope:

- Define WooCommerce line-item metadata for each fulfilled allocation: public batch number snapshot, COA permalink snapshot, batch photo attachment or URL, and quantity from that batch.
- Support one order line drawing from more than one batch.
- Preserve historical snapshots if a batch name, image, or current-product batch changes later.

Ops scope:

- Use the existing `WooImportedOrderLine.selectedBatchId`, finalized sale allocations, `batchNumberSnapshot`, `Batch.coaPermalink`, and uploaded batch photos.
- Add an idempotent authenticated write-back after Ops finalizes the shipped allocations.
- Record success, retryable failure, and permanent failure in the existing external write-back/error system.

Exit criteria:

- A test Woo order with three products receives the exact shipped allocation for every quantity.
- Re-running the write-back does not duplicate or alter finalized data.
- A later batch rename does not rewrite what the customer received.

## Milestone 2: WordPress plugin foundation and safe rollback

Website scope:

- Create the standalone plugin with a feature flag and versioned settings.
- Register a secure order endpoint while keeping WooCommerce `/my-account/view-order/{id}/` intact.
- Add “Review order details” links to the existing My Account order list.
- Render the default WooCommerce order view when the feature flag is off or required batch data is absent.
- Keep all prices, stock, taxes, payment, shipping, rewards, and order status behavior unchanged.

Security requirements:

- Logged-in customers may view only orders owned by their account.
- Guest or logged-out scans must use a high-entropy opaque token plus verification. Store only a hash of the token.
- Do not expose addresses, payment details, email addresses, phone numbers, or internal Ops identifiers in a QR-accessible public shell.
- Require renewed verification before showing customer name, totals, discount code, or reorder actions if the visitor is not already authenticated.
- Rate-limit failed verification and return generic errors.

Rollback:

- Deactivating the plugin restores native WooCommerce account order behavior.
- Deactivation retains metadata so reactivation restores the feature.

## Milestone 3: order-page core

Website scope:

- Build the gratitude opening and order-status summary.
- Render one card per order line with the shipped-batch vial photo, approved study bullets, public batch number, and direct COA link.
- Add accessible order-total disclosure using WooCommerce totals.
- Add missing-photo, missing-COA, multi-batch, refunded-line, cancelled-order, and deleted-product states.
- Build responsive behavior for desktop, tablet, and mobile.

Data sources:

- Customer, order number, dates, status, totals, quantities: WooCommerce.
- Compound study bullets: one approved relationship/content registry owned by the plugin or a shared site-core data service.
- Batch number, COA link, vial photo: shipped-allocation snapshot written by Ops.

Exit criteria:

- The displayed vial, batch number, and COA record agree for every allocation.
- Native order totals match the new disclosure exactly.
- Keyboard, screen-reader, 320–1440 px, logged-in, logged-out, guest, and error states pass.

## Milestone 4: storage and support information

Website scope:

- Pull approved storage and handling excerpts from one maintained content source.
- Keep compound-specific stability facts separate from general FAQ facts.
- Add precise links to the FAQ, support, shipping, and return policies.

Content gate:

- Do not infer stability duration, sterility, compatibility, dosing, administration, or human-use guidance.
- Each compound-specific duration requires a source, scope, date, owner approval, and review cadence.

Exit criteria:

- Every displayed handling statement has an identified source and owner.
- Removing or revising a source updates all order pages without editing historical orders.

## Milestone 5: related-compound rules

Website scope:

- Create an owner-editable relationship registry based on approved study-area tags.
- Score candidates by strong shared themes, exclude products already in the order, and apply stock/display rules.
- Show up to four cards with a visible shared-study-area reason.
- Add the boundary: shared study areas do not establish compatibility, combined-use evidence, or a recommended protocol.
- Track card impressions and product-detail clicks without recording sensitive order contents in analytics.

Recommended language:

- Heading: **Explore related compounds**
- Card label: **Shared study area**
- Avoid: synergy, stack, works well with, pairs with, protocol, and studied together unless direct evidence supports the exact pair claim.

Exit criteria:

- A human reviewer can explain why each suggested card appeared.
- Keyword changes cannot create an unreviewed relationship in production.

## Milestone 6: thank-you discount and reorder

Website scope:

- Display the approved 15% code only after customer verification.
- Enforce one use per customer email through WooCommerce coupon rules, not page copy alone.
- Use WooCommerce’s native availability and pricing at reorder time.
- “Reorder available items” adds only valid available items, then sends the customer to the cart for review.
- Explain unavailable, changed, or deleted items before cart creation.

Exit criteria:

- Coupon use limits work for account and guest orders.
- Reorder never bypasses stock, price, coupon, tax, shipping, or checkout validation.

## Milestone 7: Ops thank-you card generator

Ops scope:

- Add one order-page QR URL to the existing finalized order/packing-slip data builder.
- Add a thank-you-card PDF renderer that fits the supplied printer template.
- Keep all text and artwork static except the QR code and any approved order reference.
- Provide **Download PDF** and, if desired, **Print** using the existing PrintNode integration.
- Add preview, re-download, reprint, failure, retry, and audit-log states.
- Lock the QR only after the website has created the secure order-access record.

Exit criteria:

- QR codes scan from a real printed card at multiple phone angles and lighting levels.
- The code resolves to the correct order without exposing the numeric order ID in the URL.
- Card generation is idempotent and logged.
- A website/API outage leaves the card in a visible “not ready” state and never prints a dead QR.

## Milestone 8: staging integration and release

- Seed test cases for account orders, guest orders, multiple quantities, multi-batch allocations, missing photos, missing COAs, cancelled/refunded orders, out-of-stock reorder items, and expired/revoked QR access.
- Verify account, COA, checkout, payment, shipping, rewards, tracking, and access-gate regressions.
- Test card PDF at final physical size on the real stock and printer template.
- Back up staging, deploy the plugin and Ops contract to staging, and complete end-to-end UAT.
- Release the Website plugin and Ops change with independent rollback paths.
- Keep Live untouched until Paulo approves the exact deployment milestone.

## Preserve, refine, replace, remove

- **Preserve:** WooCommerce orders and totals, current My Account page, COA public records, Ops batch allocation snapshots, Ops PDF/QR/PrintNode pipeline, print logs.
- **Refine:** My Account orders so each order opens, the order view, FAQ presentation, reorder entry point.
- **Replace:** The current “list only” order interaction with a secure detailed experience while preserving the native fallback.
- **Remove:** No current production feature in this design phase.

## Current concept recommendation

Use a standalone WordPress plugin. It gives Pep Select an off-switch and keeps portable order logic out of the child theme. Build the Ops portion inside the existing Control App because it already owns the exact shipped batches, batch photos, COA permalinks, PDF rendering, QR creation, PrintNode submission, and print auditing.

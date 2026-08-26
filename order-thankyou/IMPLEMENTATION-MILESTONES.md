# Pep Select Order Experience implementation milestones

Status: visual design approved. Production implementation has not started. Live remains untouched until the integrated staging milestone is approved.

## Architecture decision

Build the customer-facing feature as a standalone WordPress plugin named **Pep Select Order Experience** (`pepselect-order-experience`).

The plugin owns secure order access, the enhanced order page, WooCommerce account integration, shipped-batch snapshots, related-compound rules, discount presentation, reorder behavior, privacy controls, templates, assets, diagnostics, and the feature switch. WooCommerce remains the source of orders, customers, totals, prices, stock, taxes, coupons, and checkout rules. The COA system remains the source of published batch records and laboratory media. The child theme supplies the normal Pep Select header, footer, and shared design tokens but contains no order-experience business logic.

The Ops work belongs inside the existing Pep Select Control App. Ops already owns exact batch allocation, allocation snapshots, COA permalinks, PDFs, QR generation, PrintNode, retry handling, and print logs. A second Ops application would duplicate those systems.

## Permanent fallback design

The QR must never depend only on a plugin rewrite route that disappears when the plugin is disabled.

- QR codes point to a permanent WordPress page such as `/order/` with a high-entropy opaque access token. The public URL never contains the WooCommerce order ID.
- The WordPress page remains after plugin deactivation and contains safe fallback content linking to My Account and Contact Support. A printed QR therefore reaches a useful page instead of a 404.
- When the plugin feature switch is off, authenticated customers use WooCommerce's native `/my-account/view-order/{id}/` experience. The enhanced page is bypassed.
- Full plugin deactivation removes the enhanced rendering and integrations but does not delete the permanent page, access records, or order metadata.
- Ops has its own card-generation switch. Turning it off leaves the existing packing-slip and PrintNode workflows intact.
- Reactivation restores the experience from retained records. No customer/order migration is required.

The feature switch is the normal operational off-switch. Full plugin deactivation is the emergency fallback.

## Milestone 1: Secure order foundation and Website ↔ Ops data contract

This milestone builds the complete data and security foundation both applications need. It is not considered complete with only a plugin shell or an API stub.

### WordPress plugin

- Create the standalone plugin, versioned settings, activation/deactivation behavior, diagnostics, and a default-disabled feature switch.
- Create or adopt the permanent `/order/` fallback page without deleting it on deactivation.
- Create secure order-access records using high-entropy opaque tokens; store only token hashes and support revoke/regenerate events.
- Add cache prevention, `noindex`, `nofollow`, `noarchive`, `nosnippet`, and equivalent response headers for every enhanced order response.
- Allow authenticated customers to open only their own orders from My Account.
- Allow QR access through the opaque token without exposing addresses, email, phone, payment details, internal Ops IDs, or numeric order IDs.
- Preserve native WooCommerce `view-order` behavior and the existing account/cash-back presentation.
- Define versioned WooCommerce line-allocation metadata for public batch number, quantity from that batch, COA permalink snapshot, vial-photo snapshot, and allocation status.
- Support multiple batches fulfilling one WooCommerce line and preserve historical snapshots when current batch records later change.

### Ops integration

- Map finalized Ops allocations from `WooImportedOrderLine.selectedBatchId`, sale allocations, `batchNumberSnapshot`, `Batch.coaPermalink`, and uploaded vial media into the versioned WordPress contract.
- Add an authenticated, idempotent WordPress write-back after allocations are finalized.
- Add access-record creation/readiness confirmation so Ops receives the permanent order-page URL only after WordPress can resolve it.
- Record successful, retryable, and permanent write-back failures through the existing integration/error system.

### Milestone exit

- A representative three-product order, a multi-quantity order, and a multi-batch line reproduce the exact shipped allocations in WordPress.
- Repeating the write-back changes nothing and creates no duplicate records.
- The account owner and the QR token can reach the correct order; unrelated accounts and invalid tokens receive the same generic denial.
- Feature-off and plugin-deactivated tests preserve native WooCommerce order access and the permanent QR fallback page.
- No checkout, payment, stock, shipping, rewards, tracking, COA, or account regression is introduced.

## Milestone 2: Complete customer order experience

This milestone delivers the approved visual as one finished customer flow, including every feature that relies on the order and batch contract.

### Order page

- Implement the approved Concept 03 desktop, tablet, and mobile composition using the active site header, footer, and design tokens.
- Render the gratitude header, order date/status, compact product grid, order-information disclosure, storage and handling, discount, related compounds, support, and reorder cards.
- Render the actual shipped vial image, public batch number, documented result, laboratory, test date, and direct COA link for every allocation.
- Handle missing photo, missing COA, pending/failed result, deleted product, cancelled/refunded line, and multi-batch states without inventing data.
- Add order-page links to the existing My Account order surfaces while leaving WooCommerce's native view-order endpoint available.

### Controlled content and relationship engine

- Create one approved content registry for product study bullets and storage/handling excerpts.
- Create the graphical relationship matrix as structured data, not generated page copy.
- Tag each compound with approved study areas and score eligible products by overlap.
- Exclude products already ordered, hidden/unavailable products, duplicates, and owner-blocked relationships; display up to four explainable results.
- Store the displayed relationship reason so a reviewer can explain why every card appeared.
- Do not infer compatibility, protocols, stacks, combined effects, or human use.

### Discount and reorder

- Display the thank-you coupon only where the approved access level permits it.
- Enforce one use per customer email through WooCommerce coupon rules rather than page wording.
- Reorder through WooCommerce's current products, prices, availability, quantities, taxes, and cart validation.
- Explain unavailable or changed items before sending the customer to the cart.

### Milestone exit

- The dynamic data version matches the approved mockup at 320, 390, 768, 1024, and 1440 px.
- Every vial, batch number, result, photo, and COA link agrees with the Ops snapshot.
- Related cards are deterministic and human-explainable.
- Coupon and reorder actions respect native WooCommerce validation.
- Logged-in, token-access, invalid, revoked, missing-data, cancelled/refunded, keyboard, and screen-reader states pass.

## Milestone 3: Ops thank-you-card production and QR readiness

This milestone turns the working order URL into the printable fulfillment tool. It waits for Paulo's final card PDF/template dimensions but combines the entire Ops flow rather than separating PDF, QR, download, and printing.

- Add order-page readiness and URL data to the existing finalized-order/packing-slip builder.
- Build the thank-you card against the supplied physical PDF template, bleed, safe area, stock orientation, and printer dimensions.
- Keep approved artwork static; generate the order-specific QR and any approved order reference.
- Add preview, Download PDF, Print, re-download, reprint, retry, and visible failure states.
- Use the existing QR library, PDFKit pipeline, PrintNode client, queued retry behavior, and print audit log.
- Prevent card printing until allocations are finalized and WordPress confirms that the QR target resolves.
- Make generation idempotent so retries produce the same order URL and do not create extra access records.
- Store the template version, QR target fingerprint, print result, PrintNode job ID, operator, and timestamp in the existing audit trail.

### Milestone exit

- A real printed card scans correctly across current iPhone and Android cameras, multiple angles, normal packing-room lighting, and the final card stock.
- The QR reaches the correct private order experience without exposing the numeric order ID.
- An unavailable Website/API leaves the card visibly not ready and never prints a dead QR.
- Disabling card generation does not affect packing slips, sales allocation, or other PrintNode jobs.

## Milestone 4: Integrated staging, controlled release, and rollback proof

This milestone proves the complete system together and is the only milestone authorized to prepare a Live release. Live deployment still requires Paulo's explicit approval.

- Deploy the plugin with its feature switch off and deploy the compatible Ops contract to staging.
- Seed account orders, guest orders, multiple quantities, split batches, missing photos, missing COAs, pending/failed tests, cancelled/refunded orders, unavailable reorder items, coupon reuse, and expired/revoked tokens.
- Verify the full sequence: Woo order import → Ops allocation → WordPress snapshot → access URL → order page → COA → related products → coupon/reorder → card PDF → printed QR.
- Run privacy, authorization, token-guessing, rate-limit, caching, indexing-header, and generic-error checks.
- Re-test checkout, payment, stock, shipping, rewards, tracking, account pages, access gate, COA archive, packing slips, and PrintNode queues.
- Prove the normal rollback by disabling the feature switch and the emergency rollback by deactivating the plugin.
- Confirm previously printed QR codes reach the permanent fallback page during both rollback modes.
- Back up staging, record the exact plugin and Ops versions, complete UAT, and prepare independent Website and Ops rollback packages.

### Release order after approval

1. Install the Website plugin with the feature switch off.
2. Deploy the compatible Ops contract with card printing off.
3. Validate internal test orders and physical cards.
4. Enable the enhanced order page for controlled test orders.
5. Enable card generation after the Website readiness checks pass.
6. Expand to all eligible new orders after Paulo approves the staging evidence.

### Milestone exit

- End-to-end UAT passes with real printer output and no unresolved high-risk findings.
- Normal and emergency fallback behavior is demonstrated, not assumed.
- Local and remote Git heads match for both repositories.
- Live remains untouched until Paulo authorizes the exact release.

## Required inputs and decisions

- The physical thank-you-card PDF/template, dimensions, bleed, safe area, stock orientation, and printer selection are required before Milestone 3 rendering.
- The initial related-compound matrix and reasons require Paulo's approval during Milestone 2.
- The final coupon and eligible order statuses require confirmation before staging UAT.

## First build target

Begin with Milestone 1. Its first testable delivery is an installable, default-disabled plugin plus the idempotent Ops/WordPress contract exercised against seeded orders. Do not begin the final card renderer before that contract and permanent QR fallback are proven.

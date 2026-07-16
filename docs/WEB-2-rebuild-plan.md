# WEB-2 Controlled Front-End Rebuild Plan

Plan date: July 16, 2026

Environment: Kinsta Staging first

Status: Planning only; no implementation is authorized by this document

## 1. Purpose

WEB-2 will rebuild the Pep Select presentation layer while preserving the working commerce, customer, testing, verification, and fulfillment systems underneath it.

The rebuild must proceed as a sequence of small, reversible milestones. Each replacement will be built and tested on Staging before its existing counterpart is retired. Live remains untouched until a separate, approved deployment milestone defines the exact promotion and rollback procedure.

This plan uses the completed WEB-1 reports as its source of truth:

- `docs/WEB-1-elementor-audit.md`
- `docs/WEB-1-staging-findings.md`

WEB-2 does not approve visual designs, final page copy, legal advice, plugin removal, data migration, or production deployment.

## 2. Non-negotiable preservation boundary

The rebuild must preserve:

- WooCommerce products, variations, inventory, customers, orders, coupons, taxes, shipping, and email workflows.
- The temporary emailed Square payment-link workflow.
- Customer accounts and password reset.
- YITH rewards functionality until a later dedicated rewards milestone.
- VerifyPass military and law-enforcement verification and its working WooCommerce coupon creation.
- The Pep Select COA Archive plugin, its records, and canonical `/testing/` routes.
- Existing working checkout and fulfillment integrations.
- Current legal and policy pages until replacement copy is approved.

The following already-tested flows are treated as a preserved baseline: cart, four-step checkout, emailed Square payment-link orders, WooCommerce order statuses, customer order history, addresses, account editing, logout, password reset, reward-point conversion into coupons, and mobile side cart. They should not be retested repetitively unless a milestone changes their implementation or a neighboring change creates a credible regression risk.

No visual milestone may change prices, variation relationships, stock, coupons, taxes, shipping rules, payment behavior, order states, customer records, checkout validation, or email delivery logic as a side effect.

## 3. Presentation layer in scope

WEB-2 will plan and, after later milestone approval, rebuild or restyle:

- Header and navigation
- Footer
- Homepage
- Shop/product archive
- Product-card loop
- Search and search-results experience
- Single-product template
- Cart and side-cart styling
- Checkout styling
- My Account styling
- Contact page
- FAQ page
- About page
- Military/first responder page
- Legal-policy page layouts

This scope is presentation work. Business behavior remains owned by WooCommerce or the dedicated plugin/service that currently provides it.

## 4. Architecture and delivery rules

### 4.1 Ownership by layer

| Layer | May own | Must not own |
|---|---|---|
| Elementor | Marketing and editorial page composition, approved headings and imagery, reusable visual sections, and Theme Builder presentation where visual administration is useful | Secrets, authentication, order lookup security, rate limiting, canonical product/order relationships, checkout logic, or durable integrations |
| Child theme, if justified | Theme-coupled global styles, narrow WooCommerce presentation overrides, and theme-specific template hooks | Portable business logic or duplicated WooCommerce data |
| Pep Select Site Core plugin | Durable site-specific behavior, integration adapters, secure endpoints, dynamic account/header behavior, reusable shortcodes or blocks, and functionality that must survive a theme change | COA behavior that belongs in the dedicated COA plugin or copied plugin code |
| Existing dedicated plugin | The feature it already owns, including Pep Select COA Archive, YITH rewards, VerifyPass, side cart, tracking, or checkout integrations | Unrelated page composition or general brand styling |
| WooCommerce | Products, variations, stock, customers, orders, addresses, coupons, taxes, shipping, checkout, and account/order records | A duplicated parallel Pep Select commerce data model |

### 4.2 Required constraints

- Do not build business logic into Elementor templates.
- Do not restore ElementsKit Lite or ElementsKit Pro. ElementsKit Lite is the confirmed trigger for Staging Elementor editor memory exhaustion.
- Do not reuse Peptides Divas, BioQuantum, Siat legacy COA templates, or copied competitor layouts or copy.
- Do not edit WordPress core, WooCommerce core, or third-party plugin files.
- Do not store credentials or secrets in Elementor, Git, JavaScript, or public markup.
- Replace old templates only after the replacement passes desktop, tablet, mobile, accessibility, and relevant functional testing.
- Preserve rollback points throughout the rebuild.
- Do all work on Staging first.
- Keep Live untouched until an approved deployment milestone.
- Use environment-neutral internal links and assets. Do not hard-code Staging, Kinsta, Hostinger, Peptides Divas, BioQuantum, or obsolete URLs into replacements.
- Record database-held Elementor, menu, and Theme Builder changes separately from code changes.

### 4.3 Standard milestone gate

Before each implementation milestone:

1. Confirm the Staging environment and current Git branch.
2. Confirm a clean worktree.
3. Create a named Kinsta manual Staging backup.
4. Export every Elementor template that will be changed.
5. Record current Theme Builder conditions, menu assignments, plugin ownership, and relevant settings.
6. State the milestone scope and exclusions.

After each milestone:

1. Test the changed surface and its immediate integrations.
2. Test at 1440, 1280, 1024, 768, 480, 430, 390, 375, 360, and 320px where applicable.
3. Check keyboard use, visible focus, semantic labels, touch targets, contrast, reduced motion, error states, and no horizontal page scrolling.
4. Confirm that Live was not modified.
5. Record the verified replacement, remaining risks, and rollback steps before moving forward.

## 5. Milestone plan

## WEB-2A — Design system and global foundations

### Objective

Define the approved visual, responsive, accessibility, and component foundations that every later milestone will use, without redesigning or publishing a page.

### What will be changed

- Document exact approved color tokens after extracting the current Pep Select values rather than relying on approximate references.
- Define typography roles, spacing, container widths, breakpoints, radii, borders, shadows, icon rules, focus styles, motion rules, and semantic status colors.
- Define component specifications and states for buttons, links, inputs, search, product cards, COA/evidence cards, badges, alerts, empty states, navigation, drawers, tables, accordions, modals, and loading states.
- Decide where global presentation tokens will live: Elementor global settings, a justified child-theme stylesheet, or another approved presentation layer.
- Produce a page/template inventory that maps every future component to its source of truth and owner.

### What must be preserved

- Current Pep Select identity anchors and approved COA status distinctions.
- WooCommerce and plugin behavior.
- Existing Live and Staging page assignments until later replacements pass their own milestone.
- Historical exports as evidence; they are not design sources.

### Dependencies

- WEB-1 audit findings.
- Approval of design tokens and component specifications.
- Confirmation of current font files, brand assets, and exact production color values.
- Decision on whether a child theme is genuinely required.

### Acceptance criteria

- One approved source of truth defines primitive, semantic, and component tokens.
- Desktop, tablet, and mobile rules are explicit rather than desktop layouts merely being compressed.
- Components define relevant default, hover, focus, active, disabled, loading, empty, success, warning, and error states.
- Normal body text and controls target accessible sizing and contrast; touch targets are at least 44px where practical.
- Status meaning is never communicated by color alone.
- No ElementsKit dependency, legacy-brand visual pattern, copied competitor composition, final page copy, or page implementation is introduced.

### Rollback checkpoint

Documentation-only checkpoint and Git commit. If any Staging global setting must be tested later, create a named backup immediately before that test and export the existing global settings first.

### Explicit exclusions

- No page redesign or publication.
- No final marketing or legal copy.
- No plugin removal or update.
- No checkout, account, COA, rewards, VerifyPass, or commerce behavior change.

## WEB-2B — Header, navigation, search, and footer

### Objective

Replace the fragile global shell with an accessible, Elementor-native or appropriately coded header/footer system that preserves account, rewards, cart, search, and legal navigation behavior without ElementsKit.

### What will be changed

- Rebuild the announcement bar, desktop header, mobile header/drawer, primary navigation, account access, rewards display, cart trigger, search interface, and footer presentation.
- Continue using Elementor's native WordPress Menu widget or another approved native approach; do not reintroduce ElementsKit.
- Repair header search and search-result presentation, including oversized imagery, result layout, loading, empty, one-result, many-result, long-title, and missing-image states.
- Replace hard-coded environment URLs with WordPress-aware destinations.
- Clarify footer navigation and responsive behavior, including the current order-history/tracking destination.

### What must be preserved

- Existing WordPress menus and their destinations.
- YITH rewards calculations and customer balances.
- `[xoo_wsc_cart]`/side-cart behavior or its verified integration point.
- Account login state and password-reset access.
- Current policy destinations and compliance text until approved replacements exist.
- The working Staging native menu replacement in Header #1323 until the new header is verified.

### Dependencies

- WEB-2A approved tokens and component specifications.
- Ownership trace for rewards, side cart, account state, search query behavior, and any header shortcodes.
- Current menu and Theme Builder condition exports.
- Confirmation of the shipping announcement before reuse.

### Acceptance criteria

- Header and footer work at every required width with no ElementsKit widget or metadata dependency required for rendering.
- Navigation, mobile drawer, search, account, rewards, and cart are keyboard operable with visible focus and meaningful accessible names.
- Header and compound/product search results use consistent, bounded imagery and readable layouts.
- Search handles loading, no results, one result, many results, long titles, and missing images.
- Logged-out/logged-in, zero/nonzero rewards, and empty/populated cart states render correctly.
- No hard-coded Staging, Kinsta, Hostinger, Peptides Divas, or BioQuantum links remain in the replacement.
- Old global templates remain available for rollback until the new Theme Builder conditions are verified.

### Rollback checkpoint

Create `Before WEB-2B Global Shell Replacement` backup, export Header #1323 and Footer #391, record menu assignments and display conditions, and retain the last verified templates until WEB-2B is approved.

### Explicit exclusions

- No rewards-rule redesign.
- No public tracking implementation.
- No checkout/cart business-logic change.
- No homepage, archive, or product-template redesign beyond search-result dependencies required by this milestone.

## WEB-2C — Homepage

### Objective

Create an original Pep Select homepage presentation using approved components and verified claims while preserving product, search, COA, and commerce links.

### What will be changed

- Replace the current Homepage `571` composition section by section.
- Remove copied Peptides Divas testimonials, hidden-at-all-width remnants, duplicated inline styles, ambiguous CTAs, and unverified placeholder claims from the replacement.
- Use the approved global header/footer and standardized product/COA components when available.
- Establish intentional desktop, tablet, and mobile information hierarchy.

### What must be preserved

- WordPress static homepage assignment until replacement testing is complete.
- Working shop, account, cart, checkout, and `/testing/` destinations.
- Product and COA source data; homepage sections may present them but must not recreate their business logic.
- Existing homepage export for rollback and historical evidence.

### Dependencies

- WEB-2A.
- WEB-2B global shell and repaired search direction.
- Approved homepage structure and separately approved final copy.
- Verified evidence for every testing, catalog, shipping, process, and testimonial claim.
- Stable product-card and COA-card specifications, even if their full archive milestone follows.

### Acceptance criteria

- Page content and composition are original to Pep Select and contain no Peptides Divas, BioQuantum, Siat, or competitor-derived material.
- Every factual claim has an identified source and approval.
- No section is hidden at all breakpoints as a substitute for cleanup.
- Product and testing links use canonical routes and render useful missing/empty states.
- Layout passes required widths, keyboard navigation, heading hierarchy, focus, contrast, touch-target, reduced-motion, and image-sizing checks.
- Existing commerce, account, rewards, VerifyPass, COA, and email workflows are unchanged.

### Rollback checkpoint

Create `Before WEB-2C Homepage Replacement` backup, export the current Home page and replacement draft, and do not switch the static front-page assignment until approval.

### Explicit exclusions

- No product database or catalog-rule changes.
- No final product-description rewrite.
- No legal-policy replacement.
- No deletion of Homepage `571` or historical exports during this milestone.

## WEB-2D — Shop archive and product-card system

### Objective

Build one dependable product-card system and shop archive that preserve WooCommerce product truth while fixing shared Dark Loop and search-presentation problems.

### What will be changed

- Build a replacement for shared Dark Loop template `65` in parallel rather than editing it in place.
- Rebuild Products Archive #441 presentation, search field, grid, pagination/load-more, empty states, and responsive columns.
- Define product-card display for title, image, price, variation/range context, stock status, testing status when available, and precise destination labels.
- Exclude out-of-stock products from related-product presentation only if that behavior is explicitly approved and implemented through the correct WooCommerce query layer.
- Remove negative-margin and overflow-dependent layout techniques from the replacement.

### What must be preserved

- WooCommerce products, variations, prices, sale prices, inventory, stock states, tax display, product URLs, and query context.
- Existing loop `65` and Archive #441 until every consumer is migrated and tested.
- `[product_stock_status]` behavior until its provider and replacement needs are understood.
- Search, homepage, archive, and related-product consumers of loop `65` during parallel testing.

### Dependencies

- WEB-2A component rules.
- WEB-2B search behavior.
- Complete consumer map for loop `65`.
- Provider trace for `[product_stock_status]` and WooCommerce query customizations.
- Approved rules for out-of-stock related products.

### Acceptance criteria

- New product cards render correct products, images, prices, stock states, and destinations without changing WooCommerce records.
- Cards handle long names, missing images, sale/regular prices, simple and variable products, in-stock/out-of-stock states, and one/many result grids.
- Archive and search layouts do not show oversized images or horizontal scrolling.
- Every known consumer of loop `65` is listed with migration/test status.
- Old loop `65` is not retired until homepage, header search, archive, and both product-template contexts have verified replacements.

### Rollback checkpoint

Create `Before WEB-2D Product Grid Replacement` backup, export Archive #441 and loop `65`, record all template IDs that reference `65`, and retain both old and new loops until migration is complete.

### Explicit exclusions

- No product-data cleanup, repricing, stock adjustment, SKU change, or variation restructuring.
- No single-product template replacement.
- No checkout, coupon, shipping, or tax change.
- No deletion of loop `65` during initial implementation.

## WEB-2E — Single-product template

### Objective

Replace Single Product #462 with a readable, responsive presentation that preserves native WooCommerce purchase behavior, bundle behavior, COA/testing history, and side-cart integration.

### What will be changed

- Build a replacement single-product template in parallel.
- Improve mobile typography, bundle-option spacing, testing-history sizing, related-product length, button hierarchy, and drawer layering.
- Align rewards language with points terminology; final wording requires approval.
- Use canonical `/testing/` data/routes and the Pep Select COA Archive integration instead of legacy COA fields/templates.
- Replace broad placeholder-style product copy only after verified product copy is approved separately.

### What must be preserved

- Product/variation IDs, prices, stock, bundle quantity behavior, discounts, add-to-cart validation, taxes, shipping, and side-cart totals.
- Verified PT-141 behavior: three vials produce quantity `3`, price `$124.15`, savings `$13.82`, and side-cart total `$134.15` including `$10` shipping, unless authorized commerce rules change outside WEB-2.
- WooCommerce tabs and product metadata that remain approved.
- Pep Select COA Archive records and `/testing/` routes.
- Existing Single Product #462 until replacement conditions are proven.

### Dependencies

- WEB-2A.
- WEB-2D approved product-card system for related products.
- Ownership trace for bundle controls, COA carousel/testing history, ACF values, rewards display, and side-cart integration.
- Approved product copy and exact testing-status mapping.

### Acceptance criteria

- Simple, variable, bundled, sale, in-stock, and out-of-stock products retain correct WooCommerce behavior.
- PT-141 baseline values and quantity behavior remain unchanged.
- Mobile typography, bundle controls, testing history, related products, and side-cart layering are readable and operable.
- Floating cart does not remain visually above the open side-cart drawer.
- COA/testing links use current plugin-owned routes and truthful status labels.
- Add to cart, side cart, checkout handoff, rewards display, and related products pass targeted regression tests.
- Single Product #279 remains unassigned and preserved until the replacement is verified and the legacy-removal gate is approved.

### Rollback checkpoint

Create `Before WEB-2E Single Product Replacement` backup, export #462 and #279, record #462 display conditions, and retain #462 as an inactive rollback template after switching conditions.

### Explicit exclusions

- No product/variation/inventory changes.
- No new ordering model or return to “Request an order link.”
- No rewards calculation changes.
- No deletion of legacy ACF/COA data during this milestone.

## WEB-2F — Cart, side cart, and checkout presentation

### Objective

Apply the approved design system to cart, side cart, and checkout without changing the verified four-step checkout or emailed Square payment-link workflow.

### What will be changed

- Restyle cart, side-cart drawer, checkout steps, fields, summaries, notices, validation, loading, and success/error presentation.
- Correct responsive spacing, typography, stacking, focus order, drawer scrim, z-index, and fixed-control conflicts.
- Make the payment-link next step clear using separately approved transactional copy.

### What must be preserved

- Cart contents and calculations.
- WooCommerce checkout source of truth and the working four-step flow.
- Temporary emailed Square payment-link workflow.
- Coupons, taxes, shipping, payment/order creation, order emails, statuses, and fulfillment integrations.
- Side Cart WooCommerce behavior and mobile side-cart operation.

### Dependencies

- WEB-2A.
- WEB-2B cart trigger/global shell.
- WEB-2E product-to-cart behavior.
- Ownership map for Fluid Checkout, Side Cart WooCommerce, WooPayments Safe Mode on Staging, Square payment-link handling, taxes, shipping, and transactional emails.

### Acceptance criteria

- Presentation changes do not alter totals, coupons, tax, shipping, checkout validation, order creation, emails, statuses, or the payment-link workflow.
- Guest and account checkout states remain supported.
- Fields have visible labels, associated errors, correct input types/autofill, focus management, and clear recovery messages.
- Side cart and checkout work at required widths with no obscured controls or horizontal scrolling.
- Previously verified flows receive targeted regression tests only where styling or DOM changes touch them.

### Rollback checkpoint

Create `Before WEB-2F Cart Checkout Styling` backup and record/export relevant Elementor, Fluid Checkout, side-cart, and theme presentation settings. Preserve the previous verified CSS/templates for immediate restoration.

### Explicit exclusions

- No payment-provider migration.
- No checkout-step, tax, shipping, coupon, order-status, email, or fulfillment logic changes.
- No real payments or customer information on Staging.

## WEB-2G — My Account and rewards presentation

### Objective

Create a coherent account experience around existing WooCommerce account data and YITH rewards behavior without changing authentication or reward rules.

### What will be changed

- Restyle account navigation, dashboard, orders, order details, addresses, account editing, login, password reset, logout, and reward/coupon presentation.
- Align visible terminology around points and coupons; do not use conflicting cash-back language.
- Define responsive tables and mobile stacked records, empty states, errors, and long identifiers.

### What must be preserved

- WordPress/WooCommerce customer identities, passwords, password reset, addresses, order history, account endpoints, permissions, and metadata.
- YITH points balances, conversion rules, generated coupons, and customer history until a later dedicated rewards milestone.
- Existing logged-in/logged-out behavior and order privacy.

### Dependencies

- WEB-2A.
- WEB-2B account/rewards header states.
- WooCommerce endpoint and template ownership map.
- YITH shortcode, endpoint, coupon, and display-state inventory.
- Google sign-in launch decision; if retained, server-side identity ownership belongs in Site Core or its supported dedicated integration.

### Acceptance criteria

- Existing customers can log in, reset passwords, view orders, edit addresses/account details, log out, view points, and convert points to coupons as before.
- No parallel account/order data store is introduced.
- Orders and personal details remain permission-protected.
- Account navigation and records are keyboard accessible and usable at all required widths.
- Empty, loading, error, one-record, many-record, and long-identifier states are defined.

### Rollback checkpoint

Create `Before WEB-2G Account Styling` backup and preserve current WooCommerce/YITH templates and settings. Record every overridden account endpoint/template before activation.

### Explicit exclusions

- No reward earn-rate, conversion-rate, expiration, or coupon-rule changes.
- No customer migration or account merging.
- No new Google sign-in implementation unless separately approved.
- No public order-tracking feature.

## WEB-2H — Supporting pages

### Objective

Rebuild Contact, FAQ, About, and Military/First Responder presentation using approved components while preserving working form and VerifyPass behavior.

### What will be changed

- Contact: rebuild layout and form presentation; replace obsolete “Request an order link” messaging only after approved copy is supplied.
- FAQ: replace ElementsKit-dependent or unstable accordion presentation with an accessible native solution; treat content as present, not deleted.
- About: correct excessive whitespace, replace five plain navy circles with meaningful approved icons, and include only verified claims.
- Military/First Responder: integrate VerifyPass through a VerifyPass-supported embedded or modal experience when technically supported.
- Resolve the duplicate military-discount page only after the canonical replacement and route are verified.

### What must be preserved

- Elementor contact-form submission fields and successful delivery of name, email, subject, and message.
- Current form behavior until authenticated production sending and Reply-To behavior are separately configured and tested.
- FAQ content.
- VerifyPass identity verification, uploads, camera access, military/law-enforcement checks, and working WooCommerce coupon creation.
- Existing page routes until redirects and navigation are approved.

### Dependencies

- WEB-2A and WEB-2B.
- Approved page structures and final copy.
- Authenticated production email plan and Reply-To requirements.
- VerifyPass documentation/support confirmation for embedded or modal behavior.
- Verified catalog/testing evidence for About claims.

### Acceptance criteria

- Contact layouts and form work on desktop and mobile with correct fields, labels, validation, submission, receipt, and Reply-To behavior in the target environment.
- FAQ questions and answers render consistently on Staging without ElementsKit and remain keyboard/screen-reader accessible.
- About spacing and icon semantics are intentional; no unverified catalog/testing numbers appear.
- VerifyPass remains within the Pep Select experience where supported and works on desktop/mobile without breaking identity checks, uploads, camera access, or coupon creation.
- One canonical military/first-responder route is identified before any duplicate is retired.

### Rollback checkpoint

Create `Before WEB-2H Supporting Pages` backup and export each current page/form before replacing it. Preserve the working popup-based VerifyPass path until embedded/modal verification is fully approved.

### Explicit exclusions

- No VerifyPass identity or coupon-rule rewrite.
- No production email credential/configuration change without a separate approved action.
- No final legal-policy work.
- No unsupported testing, response-time, or catalog claims.

## WEB-2I — Legal and policy content implementation

### Objective

Implement approved legal and policy copy in consistent, readable layouts after operational and legal facts have been verified.

### What will be changed

- Apply approved content to Privacy Policy, Terms & Conditions, Refund & Shipping Policy, and RUO Disclaimer layouts.
- Replace `Last updated: [DATE]` placeholders with approved dates.
- Reflect the emailed Square payment-link workflow and production refund process.
- Retain Google sign-in language only if enabled at launch.
- Direct manufacturing-defect communication to Pep Select support.

### What must be preserved

- Current legal pages, routes, and copy until replacements are approved and ready.
- Current checkout/payment workflow and customer records.
- Required research-use limitations and truthful COA/testing distinctions.

### Dependencies

- Approved legal/policy copy.
- Final verification of Georgia governing law, Kennesaw mailing address, processing timelines, Square refund handling, testing/COA claims, Google sign-in launch status, and actual repackaging/labeling/modification operations.
- WEB-2A layout components and WEB-2B footer destinations.

### Acceptance criteria

- No `[DATE]` placeholder remains.
- Every operational, address, payment, refund, testing, COA, Google sign-in, and RUO statement has an approved source.
- Pages are readable and accessible at all required widths with correct heading hierarchy and internal links.
- Footer and checkout references point to the canonical policies.
- Current policy pages remain recoverable until final approval.

### Rollback checkpoint

Create `Before WEB-2I Policy Publication` backup and export/snapshot every current policy page. Keep previous approved text available for immediate restoration.

### Explicit exclusions

- No legal conclusions generated by the implementation team.
- No policy publication before owner/legal approval.
- No payment, refund, shipping, Google sign-in, COA, or operations change disguised as copy work.

## WEB-2J — Responsive QA, accessibility, performance, and launch readiness

### Objective

Verify the assembled WEB-2 presentation on Staging, close cross-page regressions, and produce a launch recommendation and rollback package without modifying Live.

### What will be changed

- Fix only defects discovered in the approved WEB-2 surfaces.
- Consolidate remaining duplicated presentation CSS and remove replacement-only dead assets after dependency checks.
- Replace hard-coded environment URLs in approved replacement surfaces.
- Prepare final template/export inventory, test evidence, deployment checklist, and rollback instructions.

### What must be preserved

- All systems in the non-negotiable preservation boundary.
- Historical exports and last verified rollback packages.
- Live environment until a separate deployment milestone is approved.
- Working integrations and routes, especially WooCommerce, Square payment-link email flow, COA Archive `/testing/`, YITH rewards, VerifyPass, side cart, checkout, account, and fulfillment.

### Dependencies

- WEB-2A through WEB-2I accepted or explicitly deferred.
- Stable Staging environment and named final backup.
- Complete route/template/plugin ownership maps.
- Approved final copy and legal content.

### Acceptance criteria

- Required width matrix passes on all rebuilt surfaces, including browser resize without reload.
- Keyboard navigation, visible focus, semantic headings/labels, modal escape, no traps, status text beyond color, contrast, touch targets, validation association, reduced motion, and meaningful action names pass review.
- Logged-out, logged-in, empty, loading, success, validation error, server error, no-results, one-result, many-results, long-name, and missing-image states are verified where relevant.
- No body-level horizontal scrolling, obscured actions, oversized search images, broken drawers, or floating-control collisions remain.
- Elementor editor use stays within the 256 MB per-thread Staging constraint during representative edits; memory and thread events are reviewed.
- No ElementsKit dependency exists in active replacement templates.
- Performance review covers image dimensions/formats, lazy loading below the fold, font loading, layout shift, unnecessary scripts/widgets, and duplicated inline CSS.
- Targeted regression tests confirm preserved integrations still work where WEB-2 touched their presentation.
- Launch package includes exact files/templates/settings, backup name, smoke tests, failure indicators, and rollback steps.
- Live has not been modified.

### Rollback checkpoint

Create `WEB-2 Final Staging Candidate` backup, export all active replacement templates and global settings, retain the previous verified packages, and document a page-by-page rollback map.

### Explicit exclusions

- No Live deployment.
- No unrelated feature work.
- No plugin/core update campaign.
- No cleanup/deletion without satisfying the legacy-removal register gates.

## 6. Legacy-removal register

“Remove” means retire from active use only after the listed gate passes. Backups and historical exports remain available unless a later cleanup milestone explicitly approves deletion.

| Legacy item | Current concern | Required replacement or evidence | Removal gate | Until then |
|---|---|---|---|---|
| ElementsKit Lite | Confirmed Elementor editor memory trigger on Staging | Native Elementor or appropriately coded replacements for every direct widget | All active templates contain no required ElementsKit widgets; editor/public QA passes | Keep disabled on Staging; do not restore |
| ElementsKit Pro | Adds the same legacy dependency family and is not required for the native header replacement | Same dependency inventory as Lite | No active dependency and rollback verified | Keep disabled on Staging; do not restore |
| Old `/coas/` page | Obsolete route/presentation beside canonical Pep Select COA Archive `/testing/` routes | Canonical `/testing/` navigation, redirects, and equivalent user access | Links, redirects, search, historical records, and SEO behavior verified | Preserve page/route evidence; do not delete |
| Old `coa`/COAs custom post type | Legacy data model referenced by old Elementor search/single templates | Confirm all required records exist in Pep Select COA Archive and map correctly | Record counts, attachments, statuses, relationships, routes, and rollback verified | Preserve all records and registration source |
| Old COA ACF field group | Legacy product/COA fields embedded in old templates | Current plugin-owned fields and presentation verified for every product/batch | Field values exported/mapped; no active consumer remains | Preserve field group and values |
| Elementor Single Post #510 | Old COA single template using `[coa_table]` | Pep Select COA Archive `/testing/` single-record presentation | Display condition removed only after all statuses/routes work | Preserve template export and condition record |
| COA loop template #485 | Old `coa` search-result card | Current testing search/result component using plugin-owned data/routes | Homepage/header/search consumers migrated and all result states pass | Preserve template and ID references |
| Unused Single Product #279 | Unassigned legacy product template with placeholders, ACF, `[recent_batches]`, and inquiry flow | WEB-2E replacement and proof that no products/conditions reference #279 | Theme Builder, shortcode, ACF, and data dependency checks pass | Preserve inactive template/export |
| Current Single Product #462 | Active product presentation to be replaced | WEB-2E approved replacement | Product coverage and targeted commerce regression tests pass | Preserve as active, then inactive rollback template |
| Shared Dark Loop #65 | Shared by search, archive, homepage, and related products | WEB-2D product-card replacement plus consumer migration map | Every consumer migrated and verified | Preserve active template and export |
| Products Archive #441 | Current archive presentation | WEB-2D archive replacement | Search, products, prices, stock, pagination, and responsive QA pass | Preserve active condition and export |
| Homepage #571 | Contains copied/inherited content and hidden remnants | WEB-2C original approved homepage | Static-page switch, links, components, and responsive QA pass | Preserve current assignment/export |
| Peptides Divas template #77 | Unrelated brand and copied content | None; historical evidence only | Confirm unassigned, unlinked, and not embedded | Preserve export; never reuse |
| BioQuantum templates #409 and #413 | Unrelated brand, emails, Hostinger assets, form, and claims | None; historical evidence only | Confirm unassigned, unlinked, and not embedded | Preserve exports; never reuse |
| Siat legacy COA templates/assets | Legacy brand/system not approved for Pep Select rebuild | Current COA Archive presentation | Inventory and active-reference check pass | Preserve evidence; never use as design source |
| Sample Page | Default/unused WordPress content candidate | No replacement needed if confirmed unused | Unpublished/unlinked/no dependency and backup verified | Preserve until cleanup approval |
| Duplicate military-discount page | Competing route/content risk | One canonical WEB-2H Military/First Responder page and verified redirects | Navigation, VerifyPass, SEO, and redirects pass | Preserve both pages until canonical route approved |
| Hard-coded Kinsta/Hostinger/Peptides Divas URLs | Environment and legacy portability risk | Dynamic/canonical internal URLs and locally owned approved assets | Link crawl and Staging/public checks pass | Preserve old templates for rollback |
| Old header/footer templates | Global dependency and rollback risk | WEB-2B approved replacements | Theme Builder conditions, menus, account, rewards, cart, search, and legal links pass | Preserve active or inactive rollback copies |
| Current legal pages | Placeholder dates and unverified language | WEB-2I approved policy content | Owner/legal approval and operational verification complete | Keep current pages active |

## 7. Risk register

| Risk | Evidence/impact | Control | Owner/checkpoint |
|---|---|---|---|
| Elementor memory usage | Home editor exceeded the 256 MB per-thread limit; Kinsta recorded 15 memory and 28 thread-limit events | Keep ElementsKit disabled; minimize widget/add-on load; edit/test in bounded templates; monitor representative editor sessions | Every Elementor milestone; final WEB-2J review |
| Shared Dark Loop dependencies | Template `65` serves header search, archive, homepage, and related products | Build parallel replacement; keep consumer map; migrate one context at a time; do not delete early | WEB-2D with checks in 2B, 2C, and 2E |
| Broken search results | Header and product/compound searches show broken layouts and oversized images | Treat search as a dedicated WEB-2B repair; use bounded cards and full result-state QA | WEB-2B, regression in WEB-2D/J |
| Staging FAQ inconsistency | Staging intermittently shows headings without accordion content; Live content exists | Do not classify content as deleted; replace widget presentation without ElementsKit; preserve content and test repeated loads | WEB-2H and WEB-2J |
| Hard-coded Staging/environment URLs | WEB-1 found hard-coded live Kinsta, Hostinger, Peptides Divas, and obsolete destinations; rebuild work could also accidentally introduce Staging URLs | Use WordPress-aware links and approved owned assets; prohibit Staging hostnames in replacements; crawl links before retirement | All milestones; final WEB-2J |
| Unverified claims | Homepage, About, product, shipping, testing, testimonials, and COA language lacks evidence | Maintain claim register; require source and approval; do not carry forward by default | 2C, 2E, 2H, 2I |
| Unverified policy language | Dates, Square flow, Google sign-in, address, law, timelines, refunds, COA/testing, and RUO operations need confirmation | Keep current pages until approved copy; separate legal/operational review from layout | WEB-2I |
| WooCommerce regression | Visual work could alter products, variations, stock, totals, checkout, accounts, orders, taxes, shipping, coupons, or emails | Use native data; no parallel model; targeted regression testing; named backups | 2D–2G and WEB-2J |
| Square payment-link workflow regression | Checkout currently creates orders and emails temporary Square payment links | Treat as preserved integration; no payment redesign in WEB-2 | WEB-2F and WEB-2J |
| COA data/route regression | Old `coa` templates coexist with Pep Select COA Archive `/testing/` | Map records/routes/shortcodes/fields; preserve plugin and data; remove legacy only after verified migration | WEB-2E, legacy gate, WEB-2J |
| Rewards regression | Header and account use YITH; terminology is inconsistent | Preserve calculations and conversion; change presentation/terms only; dedicated rewards logic deferred | WEB-2B/G |
| VerifyPass regression | Verification and coupon creation work; embedded/modal change could break uploads/camera/identity | Use only VerifyPass-supported integration; retain popup fallback until full device QA | WEB-2H |
| Side-cart layering/integration | Mobile works, but floating cart remains behind/above the open drawer incorrectly | Fix z-index/scrim/presentation without changing cart calculations | WEB-2E/F |
| Contact email deliverability | Staging mail lands in Spam from Kinsta sender | Keep form behavior; plan authenticated production sender and correct Reply-To separately | WEB-2H and deployment planning |
| Premature cleanup | Old templates may contain active conditions, shortcode, ACF, route, or rollback dependencies | Apply legacy-removal gates; never delete during replacement build | Every milestone |

## 8. Template replacement order

This order minimizes shared-dependency and commerce risk:

1. **Freeze and document the baseline.** Record active Theme Builder conditions, menu assignments, routes, plugin ownership, and exports. Keep ElementsKit disabled.
2. **Complete WEB-2A foundations.** Approve tokens, responsive rules, components, and ownership before touching active templates.
3. **Build new product and COA card specifications without switching consumers.** These are shared prerequisites, not active replacements yet.
4. **Replace header/footer and repair search in WEB-2B.** Keep old global templates available; confirm rewards, account, cart, menus, and search states.
5. **Create the parallel product-loop replacement in WEB-2D.** Test it in an isolated preview before changing archive or other consumers.
6. **Replace the shop archive consumer.** Migrate Archive #441 first because it offers the clearest full-grid test surface.
7. **Replace the homepage in WEB-2C using the verified global shell and card components.** Do not reuse Homepage #571 content by default.
8. **Replace Single Product #462 in WEB-2E.** Preserve all WooCommerce and COA behavior; keep #462 as rollback and #279 inactive.
9. **Migrate related-product and remaining search consumers from Dark Loop #65.** Confirm no active reference remains before retirement.
10. **Restyle cart/checkout, then account/rewards.** These follow stable product-to-cart behavior and do not change their underlying integrations.
11. **Replace supporting pages and VerifyPass presentation.** Preserve working form and verification behavior during parallel testing.
12. **Implement approved legal/policy content.** Keep existing pages active until final content approval.
13. **Run WEB-2J full QA and only then evaluate legacy retirement.** Removal occurs item by item using the register, never as a bulk cleanup.

## 9. Overall WEB-2 definition of done

WEB-2 is complete only when all of the following are true:

- WEB-2A through WEB-2J are accepted or explicitly documented as deferred with owner approval.
- All in-scope presentation surfaces use the approved Pep Select design system and original, approved content.
- No active replacement requires ElementsKit Lite or Pro.
- No active replacement uses Peptides Divas, BioQuantum, Siat legacy COA, or copied competitor composition/copy.
- WooCommerce products, variations, inventory, customers, orders, coupons, taxes, shipping, checkout, emails, and fulfillment remain intact.
- The emailed Square payment-link workflow remains operational unless a separate approved payment milestone replaces it.
- Accounts, password reset, YITH rewards, VerifyPass verification/coupons, side cart, and fulfillment integrations remain operational.
- Pep Select COA Archive records and `/testing/` routes remain canonical and intact.
- Search results render correctly with controlled imagery and complete loading/empty/error/result states.
- All rebuilt surfaces pass the required width matrix and accessibility baseline.
- Elementor editor performance is acceptable within Staging's 256 MB per-thread limit for representative editing tasks, with no repeat ElementsKit-triggered failure.
- Hard-coded Staging/live-host/legacy URLs have been removed from active replacement surfaces.
- Every retained testing, shipping, catalog, product, testimonial, policy, and operational claim has an approved source.
- Current legal pages are not replaced until approved content is ready.
- Every retired legacy item has passed its register gate and retains a documented rollback path.
- A final Staging backup, template/export package, test record, smoke-test list, failure indicators, and rollback instructions exist.
- Live remains untouched throughout WEB-2; production promotion is handled only by a separately approved deployment milestone.

## 10. Recommended immediate implementation milestone

Begin with **WEB-2A — Design system and global foundations**.

This is the safest first implementation milestone because it changes no customer flow, product data, plugin behavior, or active template assignment. It creates the shared decisions required to prevent each later page from inventing its own typography, spacing, CSS, icons, responsive behavior, and component states.

The first WEB-2A checkpoint should produce only:

1. An exact current-token inventory.
2. A proposed token and component specification for review.
3. A source-of-truth/ownership map for global styles and reusable components.
4. A Staging implementation prompt with strict scope, non-goals, likely files/settings, tests, rollback steps, acceptance criteria, and a stop condition.

Do not begin WEB-2B or publish global settings until WEB-2A is reviewed and approved.

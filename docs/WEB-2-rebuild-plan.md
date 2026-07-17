# WEB-2 Controlled Coded Customer-Facing Rebuild Plan

Plan date: July 16, 2026

Environment: Kinsta Staging first

Status: Planning only; no implementation is authorized by this document

## 1. Purpose

WEB-2 will rebuild the complete Pep Select customer-facing presentation through a lightweight Hello Elementor child theme while preserving the working commerce, customer, testing, verification, and fulfillment systems underneath it.

The rebuild must proceed as a sequence of small, reversible milestones. Each replacement will be built and tested on Staging before its existing counterpart is retired. Live remains untouched until a separate, approved deployment milestone defines the exact promotion and rollback procedure.

The successful Pep Select COA Archive development workflow is the delivery model: detailed requirements, bounded coded implementation, versioned packaging, Staging installation, real-output review, screenshot-led iteration, responsive/accessibility verification, and a preserved rollback.

Codex will implement each customer-facing system from approved requirements, visual references, screenshots, responsive rules, state definitions, integration ownership, acceptance criteria, and iterative Paulo review. It must not infer final claims/legal copy, expand a milestone silently, or move business logic into the theme.

This plan uses the completed WEB-1 reports as its source of truth:

- `docs/WEB-1-elementor-audit.md`
- `docs/WEB-1-staging-findings.md`

WEB-2 does not approve final visual designs, final page copy, legal advice, plugin removal, customer/product/order migration, operational-integration migration, or production deployment.

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
- The current WooCommerce database; no products, variations, inventory, customers, accounts, orders, coupons, or operational records are rebuilt or migrated.
- The existing Hello Elementor parent theme and active Elementor templates as the immediate rollback until the coded replacement is fully approved.

The following already-tested flows are treated as a preserved baseline: cart, four-step checkout, emailed Square payment-link orders, WooCommerce order statuses, customer order history, addresses, account editing, logout, password reset, reward-point conversion into coupons, and mobile side cart. They should not be retested repetitively unless a milestone changes their implementation or a neighboring change creates a credible regression risk.

No visual milestone may change prices, variation relationships, stock, coupons, taxes, shipping rules, payment behavior, order states, customer records, checkout validation, or email delivery logic as a side effect.

## 3. Presentation layer in scope

WEB-2 will plan and, after later milestone approval, rebuild or restyle:

- Announcement bar
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
- Responsive global styles and accessibility behavior

The lightweight Hello Elementor child theme is the primary implementation layer for all of these customer-facing surfaces. Elementor may remain available for limited editable marketing and editorial content, but it will not own the critical global shell or core commerce presentation.

This scope is presentation work. Business behavior remains owned by WordPress, WooCommerce, or the dedicated plugin/service that currently provides it. The child theme may consume supported public hooks, functions, shortcodes, endpoints, and template data; it must not duplicate, recalculate, migrate, or store the business data those systems own.

## 4. Architecture and delivery rules

### 4.1 Ownership by layer

| Layer | May own | Must not own |
|---|---|---|
| Elementor | Limited editable marketing/editorial content where intentionally retained | Critical global shell, core commerce presentation, secrets, authentication, order lookup security, canonical product/order relationships, checkout logic, or durable integrations |
| Pep Select Hello child theme | Complete customer-facing presentation: global shell, homepage, archive/cards/search, single product, cart/checkout/account presentation, supporting/legal layouts, global responsive/accessibility styles, WordPress/WooCommerce hooks, and minimal template overrides only when necessary | Portable business logic, customer/product/order data models, authentication, rewards/cart/COA calculations, payment/shipping/email logic, secrets, or duplicated WooCommerce data |
| Hello Elementor parent theme | Stable parent framework and immediate theme-switch fallback | Direct Pep Select edits or custom business logic |
| Pep Select Site Core plugin | Durable site-specific behavior, integration adapters, secure endpoints, dynamic account/header behavior, reusable shortcodes or blocks, and functionality that must survive a theme change | COA behavior that belongs in the dedicated COA plugin or copied plugin code |
| Existing dedicated plugin | The feature it already owns, including Pep Select COA Archive, YITH rewards, VerifyPass, side cart, tracking, or checkout integrations | Unrelated page composition or general brand styling |
| WooCommerce | Products, variations, stock, customers, orders, addresses, coupons, taxes, shipping, checkout, and account/order records | A duplicated parallel Pep Select commerce data model |

### 4.2 Required constraints

- Stop manually constructing critical global or core-commerce components in Elementor.
- Do not publish or assign display conditions to the draft `Pep Select Header — WEB-2B`.
- Do not build business logic into Elementor templates or the child theme.
- Do not restore ElementsKit Lite or ElementsKit Pro. ElementsKit Lite is the confirmed trigger for Staging Elementor editor memory exhaustion.
- Do not reuse Peptides Divas, BioQuantum, Siat legacy COA templates, or copied competitor layouts or copy.
- Do not edit WordPress core, WooCommerce core, or third-party plugin files.
- Do not store credentials or secrets in Elementor, Git, JavaScript, or public markup.
- Use supported WordPress/WooCommerce hooks before copying templates. Add an override only when necessary, keep it narrow, record its installed source version, and review it after WooCommerce updates.
- Replace old presentation only after the coded replacement passes desktop, tablet, mobile, accessibility, and relevant functional testing.
- Preserve rollback points throughout the rebuild.
- Do all work on Staging first.
- Keep Live untouched until an approved deployment milestone.
- Use environment-neutral internal links and assets. Do not hard-code Staging, Kinsta, Hostinger, Peptides Divas, BioQuantum, or obsolete URLs into replacements.
- Preserve the Hello Elementor parent theme and active Elementor templates for immediate rollback while coded replacements are built in parallel.
- Record database-held Elementor, menu, theme activation, and other WordPress changes separately from code changes.

### 4.3 Standard milestone gate

Before each implementation milestone:

1. Confirm the Staging environment and current Git branch.
2. Confirm a clean worktree.
3. Create a named Kinsta manual Staging backup.
4. Export every active Elementor template whose presentation will be replaced, without editing or deleting it.
5. Record current Theme Builder conditions, active/parent/child theme versions, menu assignments, plugin ownership, WooCommerce template status, and relevant settings.
6. Confirm the previous verified child-theme package and Hello Elementor parent fallback are recoverable.
7. State the milestone scope, likely child-theme modules/files, integration boundaries, tests, rollback, and exclusions.

After each milestone:

1. Test the changed surface and its immediate integrations.
2. Test at 1440, 1280, 1024, 768, 480, 430, 390, 375, 360, and 320px where applicable.
3. Check keyboard use, visible focus, semantic labels, touch targets, contrast, reduced motion, error states, and no horizontal page scrolling.
4. Confirm that Live was not modified.
5. Record the verified child-theme version/package/hash, changed presentation modules, remaining risks, and page-level/theme-level rollback steps before moving forward.

### 4.4 Modular child-theme architecture

The child theme must remain one coherent presentation system with small modules rather than a collection of page-specific patches:

| Module | Responsibility |
|---|---|
| Foundations | WEB-2A CSS variables, typography, containers, spacing, focus, motion, status, and responsive rules |
| Global shell | Announcement, header, navigation, search entry/results, account/rewards/cart controls, and footer |
| Shared components | Buttons, links, fields, notices, status badges, empty/loading/error states, product cards, and COA/evidence presentation adapters |
| Commerce presentation | Shop/archive, single product, cart, side cart, checkout, and My Account through hooks first and narrow WooCommerce overrides only when necessary |
| Page presentation | Homepage, Contact, FAQ, About, Military/First Responder, and legal layouts built from approved shared components |
| Integration adapters | Presentation-only calls to public YITH, side-cart, VerifyPass, COA Archive, WooCommerce, and WordPress interfaces; no copied logic or data |
| Accessible interaction | Small dependency-free scripts for navigation/disclosure/focus behavior only; no customer, commerce, rewards, order, or COA data handling |
| Packaging | One versioned child-theme folder/ZIP, changelog, source-version record for overrides, hash, tests, and rollback notes per milestone |

The detailed proposed file structure lives in `docs/WEB-2B-global-navigation-plan.md`. Later milestones extend that structure; they do not create separate themes or duplicate token systems.

## 5. Milestone plan

## WEB-2A — Design system and global foundations

### Objective

Define the approved visual, responsive, accessibility, and component foundations that every later milestone will use, without redesigning or publishing a page.

### What will be changed

- Document exact approved color tokens after extracting the current Pep Select values rather than relying on approximate references.
- Define typography roles, spacing, container widths, breakpoints, radii, borders, shadows, icon rules, focus styles, motion rules, and semantic status colors.
- Define component specifications and states for buttons, links, inputs, search, product cards, COA/evidence cards, badges, alerts, empty states, navigation, drawers, tables, accordions, modals, and loading states.
- Record Elementor global foundations and mirror the approved tokens into the coded child-theme presentation layer without creating a Site Core settings panel.
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
- Approved decision to use the lightweight Hello Elementor child theme as the primary customer-facing presentation layer.

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

Create the lightweight Hello Elementor child-theme foundation and replace the fragile global shell with coded, accessible header, navigation, product search/results, account/rewards/cart controls, and footer presentation.

### What will be changed

- Create and package the modular Pep Select Hello child theme with approved WEB-2A tokens, global responsive/accessibility styles, semantic shell templates, and narrow integration adapters.
- Build the announcement bar, desktop header, mobile header/drawer, WordPress navigation, account access, rewards display, cart trigger, product-search interface/results, and footer in code.
- Render navigation through WordPress menu APIs; do not reintroduce ElementsKit or build a new Elementor global-shell template.
- Repair header search and search-result presentation, including oversized imagery, result layout, loading, empty, one-result, many-result, long-title, and missing-image states.
- Replace hard-coded environment URLs with WordPress-aware destinations.
- Clarify footer navigation and responsive behavior, including the current order-history/tracking destination.
- Leave the draft `Pep Select Header — WEB-2B` unpublished and without display conditions.

### What must be preserved

- Existing WordPress menus and their destinations.
- YITH rewards calculations and customer balances.
- `[xoo_wsc_cart]`/side-cart behavior or its verified integration point.
- Account login state and password-reset access.
- Current policy destinations and compliance text until approved replacements exist.
- Active Header #1323 and Footer #391, including their `Entire Site` conditions, while the child theme is built and reviewed.
- Hello Elementor parent theme as the immediate theme-switch rollback.
- Elementor rendering/editing for current page content.

### Dependencies

- WEB-2A approved tokens and component specifications.
- Ownership trace for rewards, side cart, account state, search query behavior, and any header shortcodes.
- Current menu, Theme Builder condition exports, Hello Elementor version, and verified parent-theme fallback.
- Confirmation of the shipping announcement before reuse.

### Acceptance criteria

- Child theme renders exactly one semantic header and footer at every required width, with no ElementsKit or Elementor Theme Builder shell rendering required while it is active.
- Navigation, mobile drawer, search, account, rewards, and cart are keyboard operable with visible focus and meaningful accessible names.
- Header and compound/product search results use consistent, bounded imagery and readable layouts.
- Search handles loading, no results, one result, many results, long titles, and missing images.
- Logged-out/logged-in, zero/nonzero rewards, and empty/populated cart states render correctly.
- No hard-coded Staging, Kinsta, Hostinger, Peptides Divas, or BioQuantum links remain in the replacement.
- Parent-theme activation restores preserved Header #1323/Footer #391 and current Elementor page content.
- Child-theme package, version, hash, static checks, Staging tests, and rollback evidence are recorded.

### Rollback checkpoint

Create `Before WEB-2B Coded Global Shell`, export Header #1323/Footer #391, record their unchanged conditions, record the parent theme/version and menu assignments, and retain the previous verified child-theme package. Primary rollback is activation of the Hello Elementor parent theme; backup restore is secondary.

### Explicit exclusions

- No rewards-rule redesign.
- No public tracking implementation.
- No checkout/cart business-logic change.
- No implementation of later homepage, archive/card, single-product, Cart/Checkout, My Account, supporting-page, or legal-layout modules during the WEB-2B shell checkpoint; they remain coded child-theme scope for their own milestones.

## WEB-2C — Homepage

### Objective

Create an original coded Pep Select homepage in the child theme using approved components and verified claims while preserving product, search, COA, and commerce links.

### What will be changed

- Build the homepage through a coded front-page template and reusable child-theme components from approved requirements, visual references, screenshots, responsive rules, and locked content.
- Replace the current Homepage `571` presentation only after the coded page passes review.
- Remove copied Peptides Divas testimonials, hidden-at-all-width remnants, duplicated inline styles, ambiguous CTAs, and unverified placeholder claims from the replacement.
- Use the approved global header/footer and standardized product/COA components when available.
- Establish intentional desktop, tablet, and mobile information hierarchy.

### What must be preserved

- WordPress static homepage assignment until replacement testing is complete.
- Working shop, account, cart, checkout, and `/testing/` destinations.
- Product and COA source data; homepage sections may present them but must not recreate their business logic.
- Existing homepage export for rollback and historical evidence.
- Existing Home post ID `79`, WordPress front-page setting, and Elementor Homepage #571 until the coded presentation is approved.

### Dependencies

- WEB-2A.
- WEB-2B global shell and repaired search direction.
- Approved homepage structure and separately approved final copy.
- Verified evidence for every testing, catalog, shipping, process, and testimonial claim.
- Stable coded product-card and COA-card specifications; implement WEB-2D card foundations before the homepage consumes them.

### Acceptance criteria

- Page content and composition are original to Pep Select and contain no Peptides Divas, BioQuantum, Siat, or competitor-derived material.
- Every factual claim has an identified source and approval.
- No section is hidden at all breakpoints as a substitute for cleanup.
- Product and testing links use canonical routes and render useful missing/empty states.
- Layout passes required widths, keyboard navigation, heading hierarchy, focus, contrast, touch-target, reduced-motion, and image-sizing checks.
- Existing commerce, account, rewards, VerifyPass, COA, and email workflows are unchanged.

### Rollback checkpoint

Create `Before WEB-2C Coded Homepage`, export/snapshot Home post ID 79 and Homepage #571, package the previous verified child theme, and keep a page-level fallback plus the Hello parent-theme fallback. Do not change the static front-page assignment unless the coded routing requires it and that change is separately approved.

### Explicit exclusions

- No product database or catalog-rule changes.
- No final product-description rewrite.
- No legal-policy replacement.
- No deletion of Homepage `571` or historical exports during this milestone.

## WEB-2D — Shop archive and product-card system

### Objective

Build one coded child-theme product-card system and Shop archive that preserve WooCommerce product truth while replacing shared Dark Loop and broken search presentation.

### What will be changed

- Build one reusable coded product-card component against native WooCommerce data, using hooks first and `content-product.php` override only if necessary.
- Build the Shop/archive presentation in the child theme, including search field, grid, pagination/load-more, empty states, ordering compatibility, and responsive columns.
- Define product-card display for title, image, price, variation/range context, stock status, testing status when available, and precise destination labels.
- Exclude out-of-stock products from related-product presentation only if that behavior is explicitly approved and implemented through the correct WooCommerce query layer.
- Remove negative-margin and overflow-dependent layout techniques from the replacement.

### What must be preserved

- WooCommerce products, variations, prices, sale prices, inventory, stock states, tax display, product URLs, and query context.
- Existing loop `65` and Archive #441 until every consumer is migrated and tested.
- `[product_stock_status]` behavior until its provider and replacement needs are understood.
- Search, homepage, archive, and related-product consumers of loop `65` during parallel testing.
- Products Archive #441 and Dark Loop #65 as immediate presentation rollback evidence until every coded consumer is verified.

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

Create `Before WEB-2D Coded Product Grid`, export Archive #441 and loop #65, record every consumer, preserve the previous child-theme package, and retain the Elementor templates unchanged. Roll back the module/package or activate the Hello parent theme.

### Explicit exclusions

- No product-data cleanup, repricing, stock adjustment, SKU change, or variation restructuring.
- No single-product template replacement.
- No checkout, coupon, shipping, or tax change.
- No deletion or condition change for Archive #441 or loop #65 during parallel implementation.

## WEB-2E — Single-product template

### Objective

Build a readable coded single-product presentation in the child theme that preserves native WooCommerce purchase behavior, bundle behavior, COA/testing history, and side-cart integration.

### What will be changed

- Build the single-product presentation through WooCommerce hooks first and narrow installed-version template overrides only where necessary.
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
- Single Product #279 as unassigned historical/rollback evidence.

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

Create `Before WEB-2E Coded Single Product`, export #462/#279, record #462 conditions, preserve the previous child-theme package, and keep both Elementor templates unchanged until the coded product presentation passes all product states. Roll back the module/package or activate Hello parent.

### Explicit exclusions

- No product/variation/inventory changes.
- No new ordering model or return to “Request an order link.”
- No rewards calculation changes.
- No deletion of legacy ACF/COA data during this milestone.

## WEB-2F — Cart, side cart, and checkout presentation

### Objective

Apply the approved design system through coded child-theme presentation for cart, side cart, and checkout without changing the verified four-step checkout or emailed Square payment-link workflow.

### What will be changed

- Style native WooCommerce, Side Cart WooCommerce, and Fluid Checkout output through scoped child-theme CSS/hooks first; use narrow template overrides only when necessary.
- Present cart, side-cart drawer, checkout steps, fields, summaries, notices, validation, loading, and success/error states through coded components/styles.
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

Create `Before WEB-2F Coded Cart Checkout`, record relevant Fluid Checkout/side-cart/WooCommerce settings and template status, preserve the previous verified child-theme package, and retain existing Elementor/plugin presentation as fallback. Roll back the module/package or activate Hello parent; do not alter cart/customer data.

### Explicit exclusions

- No payment-provider migration.
- No checkout-step, tax, shipping, coupon, order-status, email, or fulfillment logic changes.
- No real payments or customer information on Staging.

## WEB-2G — My Account and rewards presentation

### Objective

Create a coherent coded My Account presentation around existing WooCommerce account data and YITH rewards behavior without changing authentication or reward rules.

### What will be changed

- Style account navigation, dashboard, orders, order details, addresses, account editing, login, password reset, logout, and reward/coupon presentation through child-theme hooks/CSS and narrow overrides only when necessary.
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

Create `Before WEB-2G Coded Account`, preserve current WooCommerce/YITH templates/settings and the previous child-theme package, and record every overridden My Account template/source version. Roll back the module/package or activate Hello parent without migrating or clearing customer data.

### Explicit exclusions

- No reward earn-rate, conversion-rate, expiration, or coupon-rule changes.
- No customer migration or account merging.
- No new Google sign-in implementation unless separately approved.
- No public order-tracking feature.

## WEB-2H — Supporting pages

### Objective

Build coded child-theme layouts for Contact, FAQ, About, and Military/First Responder using approved components while preserving working form and VerifyPass behavior.

### What will be changed

- Contact: build a coded page layout and style the existing working form provider; replace obsolete “Request an order link” messaging only after approved copy is supplied.
- FAQ: build an accessible coded disclosure/accordion presentation; treat content as present, not deleted.
- About: build the coded layout, correct excessive whitespace, replace five plain navy circles with meaningful approved icons, and include only verified claims.
- Military/First Responder: build the coded layout around VerifyPass-supported embedded or modal behavior when technically supported.
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

Create `Before WEB-2H Coded Supporting Pages`, export/snapshot every current page/form, preserve the previous child-theme package, and retain the working popup VerifyPass path until the coded experience passes. Roll back each page module/package or activate Hello parent.

### Explicit exclusions

- No VerifyPass identity or coupon-rule rewrite.
- No production email credential/configuration change without a separate approved action.
- No final legal-policy work.
- No unsupported testing, response-time, or catalog claims.

## WEB-2I — Legal and policy content implementation

### Objective

Implement approved legal and policy copy in consistent, readable coded child-theme layouts after operational and legal facts have been verified.

### What will be changed

- Build reusable coded legal-page presentation and apply approved content to Privacy Policy, Terms & Conditions, Refund & Shipping Policy, and RUO Disclaimer layouts.
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

Create `Before WEB-2I Coded Policy Publication`, export/snapshot every current policy page, preserve the previous child-theme package, and keep previous approved text/layout available for immediate page-level or parent-theme restoration.

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
- Prepare the final child-theme package/version/hash, optional WooCommerce override inventory, preserved Elementor export inventory, test evidence, deployment checklist, and rollback instructions.

### What must be preserved

- All systems in the non-negotiable preservation boundary.
- Historical exports and last verified rollback packages.
- Hello Elementor parent theme and active/historical Elementor templates as rollback until a dedicated deployment/retirement milestone approves otherwise.
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
- Representative retained Elementor editorial content remains editable within the 256 MB per-thread Staging constraint; memory and thread events are reviewed.
- No ElementsKit dependency exists in active child-theme presentation or retained launch-critical content.
- Performance review covers image dimensions/formats, lazy loading below the fold, font loading, layout shift, unnecessary scripts/widgets, and duplicated inline CSS.
- Targeted regression tests confirm preserved integrations still work where WEB-2 touched their presentation.
- Launch package includes exact child-theme files/version/hash, template overrides/source versions, settings, backup name, smoke tests, failure indicators, and page-level/parent-theme rollback steps.
- Live has not been modified.

### Rollback checkpoint

Create `WEB-2 Final Staging Candidate`, export retained Elementor templates/global settings, retain the previous verified child-theme package and Hello parent theme, and document a page-by-page plus full theme-switch rollback map.

### Explicit exclusions

- No Live deployment.
- No unrelated feature work.
- No plugin/core update campaign.
- No cleanup/deletion without satisfying the legacy-removal register gates.

## 6. Legacy-removal register

“Remove” means retire from active use only after the listed gate passes. Backups and historical exports remain available unless a later cleanup milestone explicitly approves deletion.

| Legacy item | Current concern | Required replacement or evidence | Removal gate | Until then |
|---|---|---|---|---|
| ElementsKit Lite | Confirmed Elementor editor memory trigger on Staging | Coded child-theme replacements for launch-critical presentation plus safe retained editorial content | No active customer-facing coded system or retained launch-critical content requires ElementsKit; editor/public QA passes | Keep disabled on Staging; do not restore |
| ElementsKit Pro | Adds the same legacy dependency family and is not required for the coded rebuild | Same dependency inventory as Lite | No active dependency and rollback verified | Keep disabled on Staging; do not restore |
| Old `/coas/` page | Obsolete route/presentation beside canonical Pep Select COA Archive `/testing/` routes | Canonical `/testing/` navigation, redirects, and equivalent user access | Links, redirects, search, historical records, and SEO behavior verified | Preserve page/route evidence; do not delete |
| Old `coa`/COAs custom post type | Legacy data model referenced by old Elementor search/single templates | Confirm all required records exist in Pep Select COA Archive and map correctly | Record counts, attachments, statuses, relationships, routes, and rollback verified | Preserve all records and registration source |
| Old COA ACF field group | Legacy product/COA fields embedded in old templates | Current plugin-owned fields and presentation verified for every product/batch | Field values exported/mapped; no active consumer remains | Preserve field group and values |
| Elementor Single Post #510 | Old COA single template using `[coa_table]` | Pep Select COA Archive `/testing/` single-record presentation | Display condition removed only after all statuses/routes work | Preserve template export and condition record |
| COA loop template #485 | Old `coa` search-result card | Current testing search/result component using plugin-owned data/routes | Homepage/header/search consumers migrated and all result states pass | Preserve template and ID references |
| Unused Single Product #279 | Unassigned legacy product template with placeholders, ACF, `[recent_batches]`, and inquiry flow | WEB-2E replacement and proof that no products/conditions reference #279 | Theme Builder, shortcode, ACF, and data dependency checks pass | Preserve inactive template/export |
| Current Single Product #462 | Active product presentation to be replaced | WEB-2E coded child-theme product presentation | Product coverage and targeted commerce regression tests pass | Preserve active/rollback template and export until dedicated retirement approval |
| Shared Dark Loop #65 | Shared by search, archive, homepage, and related products | WEB-2D product-card replacement plus consumer migration map | Every consumer migrated and verified | Preserve active template and export |
| Products Archive #441 | Current archive presentation | WEB-2D coded child-theme archive | Search, products, prices, stock, pagination, and responsive QA pass | Preserve active condition/export as parent-theme rollback |
| Homepage #571 | Contains copied/inherited content and hidden remnants | WEB-2C original coded child-theme homepage | Routing, links, components, claims, and responsive QA pass | Preserve Home ID 79/current assignment/export as rollback evidence |
| Peptides Divas template #77 | Unrelated brand and copied content | None; historical evidence only | Confirm unassigned, unlinked, and not embedded | Preserve export; never reuse |
| BioQuantum templates #409 and #413 | Unrelated brand, emails, Hostinger assets, form, and claims | None; historical evidence only | Confirm unassigned, unlinked, and not embedded | Preserve exports; never reuse |
| Siat legacy COA templates/assets | Legacy brand/system not approved for Pep Select rebuild | Current COA Archive presentation | Inventory and active-reference check pass | Preserve evidence; never use as design source |
| Sample Page | Default/unused WordPress content candidate | No replacement needed if confirmed unused | Unpublished/unlinked/no dependency and backup verified | Preserve until cleanup approval |
| Duplicate military-discount page | Competing route/content risk | One canonical WEB-2H Military/First Responder page and verified redirects | Navigation, VerifyPass, SEO, and redirects pass | Preserve both pages until canonical route approved |
| Hard-coded Kinsta/Hostinger/Peptides Divas URLs | Environment and legacy portability risk | Dynamic/canonical internal URLs and locally owned approved assets | Link crawl and Staging/public checks pass | Preserve old templates for rollback |
| Elementor Header #1323/Footer #391 | Global dependency and immediate parent-theme rollback | WEB-2B coded child-theme shell | Child theme passes shell/content QA and parent-theme activation restores both templates | Preserve templates and unchanged conditions until a dedicated post-approval retirement milestone |
| Draft `Pep Select Header — WEB-2B` | Abandoned manual Elementor path | None; the coded child theme is the approved path | Confirm unpublished and no conditions | Never publish or assign conditions; preserve only as draft evidence until cleanup approval |
| Current legal pages | Placeholder dates and unverified language | WEB-2I approved policy content | Owner/legal approval and operational verification complete | Keep current pages active |

## 7. Risk register

| Risk | Evidence/impact | Control | Owner/checkpoint |
|---|---|---|---|
| Elementor memory usage | Home editor exceeded the 256 MB per-thread limit; Kinsta recorded 15 memory and 28 thread-limit events | Stop manual construction of critical components; keep ElementsKit disabled; retain Elementor only for bounded editorial content; monitor representative editor sessions | Retained editorial pages; final WEB-2J review |
| Child-theme activation conflict | Active Elementor Theme Builder shell could duplicate or override coded shell | Child `header.php`/`footer.php` must render exactly one shell without editing #1323/#391; verify parent-theme switch restores them; stop if unsupported/private APIs are needed | WEB-2B and every package activation |
| WooCommerce override drift | Copied templates can become outdated after WooCommerce changes | Prefer hooks; add overrides only when necessary; record installed source versions; review template status after updates | WEB-2D–2G and WEB-2J |
| Elementor content compatibility | Coded theme could break existing editorial pages, forms, canvas/full-width layouts, or editor loading | Preserve required WordPress/Elementor hooks and content templates; test representative public/editor views after every theme package | Every milestone; WEB-2J |
| Milestone scope expansion | A full coded rebuild could become one large unreviewable release | One surface/system per bounded brief, package, screenshot review, responsive QA, and rollback gate | Every milestone |
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

## 8. Modular coded implementation order

This page-by-page order minimizes shared-dependency and commerce risk. Milestone identifiers remain unchanged even when a shared component prerequisite is implemented before a page that has an earlier letter.

1. **Freeze and document the baseline.** Record parent/active theme, Elementor conditions, menu assignments, routes, integrations, WooCommerce template status, screenshots, and exports. Keep ElementsKit disabled.
2. **Use the completed WEB-2A foundations.** Mirror approved tokens into child-theme CSS variables; do not build a Site Core settings panel.
3. **WEB-2B — child-theme foundation and global shell.** Build announcement, desktop/mobile header, navigation, product-search entry/results, account/rewards/cart controls, footer, global responsive/accessibility styles, and parent-theme rollback.
4. **WEB-2D — coded product-card system.** Build and review one reusable card against native WooCommerce data before any page consumes it.
5. **WEB-2D — coded Shop archive and product-search results.** Apply the approved card to archive/search states, pagination, ordering, and responsive grids while preserving #441/#65.
6. **WEB-2C — coded homepage.** Build from approved requirements, visual references, screenshots, locked copy, verified claims, and the approved shell/cards; preserve Home ID 79/#571.
7. **WEB-2E — coded single-product presentation.** Use Woo hooks first and narrow overrides only where necessary; preserve #462/#279 and every purchase/COA integration.
8. **Migrate remaining Dark Loop #65 consumers.** Move related products and any remaining search/home contexts only after their coded replacements pass.
9. **WEB-2F — coded cart and side-cart presentation.** Preserve all calculations and drawer behavior.
10. **WEB-2F — coded checkout presentation.** Preserve the four-step flow, order creation, emails, and Square payment-link workflow.
11. **WEB-2G — coded My Account/rewards presentation.** Preserve customer identities, privacy, endpoints, records, and YITH rules.
12. **WEB-2H — coded Contact layout/form presentation.** Preserve submission fields and delivery behavior.
13. **WEB-2H — coded FAQ layout.** Preserve content and use an accessible disclosure pattern.
14. **WEB-2H — coded About layout.** Use only verified claims and approved icons.
15. **WEB-2H — coded Military/First Responder layout.** Preserve VerifyPass identity, uploads/camera access, and coupon creation.
16. **WEB-2I — coded legal-policy layouts.** Keep existing pages active until approved operational/legal copy is ready.
17. **WEB-2J — integrated child-theme QA and launch candidate.** Verify all pages, states, integrations, package versions, override sources, performance, accessibility, and both page-level and parent-theme rollback.
18. **Evaluate legacy retirement only after WEB-2J.** Apply the register item by item; never bulk-delete or remove the parent-theme fallback during the prelaunch rebuild.

For each item, Codex receives a detailed implementation brief with exact scope, visual references/screenshots, responsive rules, data owner, likely modules/files, required states, tests, acceptance criteria, package/version rules, rollback, and stop condition. Paulo reviews real Staging output before the next item proceeds.

## 9. Overall WEB-2 definition of done

WEB-2 is complete only when all of the following are true:

- WEB-2A through WEB-2J are accepted or explicitly documented as deferred with owner approval.
- All in-scope customer-facing presentation surfaces are delivered through the approved modular Pep Select Hello child theme, use the approved design system, and contain original approved content.
- Elementor owns only intentionally retained limited editorial content, not the critical global shell or core commerce presentation.
- No active replacement requires ElementsKit Lite or Pro.
- No active replacement uses Peptides Divas, BioQuantum, Siat legacy COA, or copied competitor composition/copy.
- WooCommerce products, variations, inventory, customers, orders, coupons, taxes, shipping, checkout, emails, and fulfillment remain intact.
- The emailed Square payment-link workflow remains operational unless a separate approved payment milestone replaces it.
- Accounts, password reset, YITH rewards, VerifyPass verification/coupons, side cart, and fulfillment integrations remain operational.
- Pep Select COA Archive records and `/testing/` routes remain canonical and intact.
- Search results render correctly with controlled imagery and complete loading/empty/error/result states.
- All rebuilt surfaces pass the required width matrix and accessibility baseline.
- Representative retained Elementor editorial content remains editable within Staging's 256 MB per-thread limit, with no repeat ElementsKit-triggered failure.
- Hard-coded Staging/live-host/legacy URLs have been removed from active replacement surfaces.
- Every retained testing, shipping, catalog, product, testimonial, policy, and operational claim has an approved source.
- Current legal pages are not replaced until approved content is ready.
- Every retired legacy item has passed its register gate and retains a documented rollback path.
- The final child-theme package/version/hash, WooCommerce override/source-version inventory, Staging backup, preserved Elementor exports, test record, smoke-test list, failure indicators, and page-level/parent-theme rollback instructions exist.
- Switching back to the unchanged Hello Elementor parent theme restores the preserved pre-rebuild presentation baseline.
- Live remains untouched throughout WEB-2; production promotion is handled only by a separately approved deployment milestone.

## 10. Recommended immediate implementation milestone

Begin implementation with **WEB-2B — coded child-theme foundation and global shell**.

WEB-2A is complete: approved tokens and Elementor global foundations are documented. WEB-2B is the safest next milestone because it establishes the versioned child-theme architecture, parent-theme rollback, global accessibility/responsive rules, and supported integration boundaries that every later coded page will reuse.

The first WEB-2B implementation checkpoint should produce only:

1. One lightweight Hello Elementor child-theme package with approved tokens and modular foundations.
2. The coded announcement/header/navigation/search/customer-controls/footer shell.
3. Verified Elementor editorial-page compatibility.
4. Static/package validation, responsive/accessibility/integration test evidence, version/hash, and a parent-theme rollback record.
5. No later page module beyond the minimum search-results dependency needed by the global shell.

Do not begin WEB-2C/2D page implementation until the child-theme foundation, global shell, parent-theme rollback, and current Elementor-content compatibility are reviewed and approved.

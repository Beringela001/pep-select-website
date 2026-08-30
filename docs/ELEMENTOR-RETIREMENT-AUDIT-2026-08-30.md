# Elementor retirement audit

Date: August 30, 2026

## Decision

**Conditional go. Do not deactivate Elementor yet.**

Elementor Core, Elementor Pro, and Marquee Addons can be retired after one published content surface is replaced: `/about-us/` (WordPress page 812). It is the only audited published route that still renders an Elementor document. The rest of the customer-facing site is already owned by the Pep Select child theme, WooCommerce, or the COA plugin.

The Hello Elementor **parent theme is not part of this retirement**. The active Pep Select theme is still a child theme with `Template: hello-elementor`, checks that the parent exists, and enqueues the parent stylesheet. Removing the Elementor plugins is feasible; removing the Hello Elementor theme requires a separate standalone-theme conversion.

## Scope and non-goals

- Audited repository references, WordPress page metadata, Elementor Library templates, active plugin versions, staging parity, and representative public routes.
- No plugin was deactivated, deleted, updated, or reconfigured.
- No page, template, display condition, order, customer, product, shipping setting, reward, COA record, or email was changed.
- The `/about-us/` indexing decision remains separate. A coded replacement should initially preserve the current `noindex, follow` state unless its claims pass content/compliance review.

## Installed dependency set

Staging and Live match:

| Component | Version | Audit status |
| --- | ---: | --- |
| Elementor Core | 4.1.5 | Retire after blockers are cleared |
| Elementor Pro | 3.34.0 | Retire; Kinsta previously identified this version as vulnerable |
| Marquee Addons for Elementor | 3.9.85 | Retire with Elementor; no required public widget was found |
| Hello Elementor parent theme | 3.4.9 | Keep for now; required by the active child theme |
| Pep Select child theme | 0.25.0-beta.68 | Keep; owns the coded storefront and commerce presentation |

Elementor Core cannot be deactivated while Pro is active. The safe deactivation order is Marquee Addons, Elementor Pro, then Elementor Core.

## WordPress inventory

- Live contains 20 WordPress pages. Twelve carry an Elementor marker, including two drafts.
- Ten published pages carry an Elementor marker, but only `/about-us/` emitted an Elementor document in the rendered page.
- The Elementor Library contains 14 stored templates: 13 published and one draft.
- Stored Theme Builder conditions still name Header 1323 and Footer 391 for the entire site, Products Archive 441 for product archives, and Single Product 462 for products.
- Those conditions are retained recovery data, not current storefront owners. The coded shell suppresses the header/footer templates, and the child theme seizes shop/archive and single-product rendering.

## Public-route evidence

The following Live routes rendered no Elementor document node during the audit:

- Home
- Shop and product detail
- Contact
- FAQ
- Military Discount
- Privacy Policy
- Terms & Conditions
- RUO Disclaimer
- Refund & Shipping Policy
- My Account
- Track Your Order

The coded Home, Shop, Product, Contact, and Quality Archive paths already suppress Elementor front-end assets. Other coded or WooCommerce pages still receive unnecessary Elementor assets because their WordPress page metadata retains Elementor edit mode. For example, FAQ rendered no Elementor node but loaded the orphaned Elementor runtime and logged `elementorFrontendConfig is not defined`.

### Remaining hard blocker: About Us

- URL: `/about-us/`
- WordPress page: 812
- State: published, Elementor-authored, `noindex, follow`, absent from the normal coded navigation, and showing zero WordPress page stats in the inspected list.
- Live render: one Elementor document node (`wp-page`, ID 812).
- Authenticated audit session: 34 Elementor/Elementor Pro/Marquee-related assets on Live and 42 on staging.
- Visible content includes qualitative company/testing claims plus three counters that currently render as `0+`, `0%`, and `0%` before/without their animation state.
- The child theme's About semantic correction depends on an Elementor-specific heading class and stored H2 markup.

Removing Elementor now would remove or corrupt this page's visible body. The page must be replaced with a coded template or intentionally unpublished first.

### Military Discount is not a blocker

The Military Discount page carries Elementor metadata, but its coded page template renders the VerifyPass button from ordinary WordPress content. The inspected output is plain button HTML with the existing VerifyPass popup URL and contains no Elementor wrapper. This flow still requires an explicit post-deactivation test because it is the only coded page that intentionally calls `the_content()` for a critical action.

## Repository dependency assessment

### Hard theme dependency

- `style.css` declares `Template: hello-elementor`.
- `inc/setup.php` verifies the Hello Elementor parent and enqueues its stylesheet.
- WordPress will not treat the child theme as valid without that parent.

This is a dependency on the parent **theme**, not on Elementor Core or Pro.

### Plugin compatibility code that becomes removable

- Elementor editor/preview detection in the coded shell.
- Theme Builder header/footer suppression filters.
- Theme Builder single-product override prevention.
- Administrator-only legacy Elementor shell controls.
- Route-specific Elementor asset dequeue logic and its contract tests.
- Elementor-specific About heading rewriting.
- Residual Elementor wrapper selectors in presentation CSS.

The only direct Elementor PHP class access is guarded by both `did_action( 'elementor/loaded' )` and `class_exists()`. The Theme Builder hooks are ordinary WordPress filters and do not fatal when Elementor is absent. The current source therefore has no identified unguarded PHP class call that would prevent a staging deactivation test.

## Benefits

- Removes the vulnerable/out-of-pair Elementor Pro installation instead of maintaining an unsupported Core/Pro version combination.
- Removes three active plugins and their administrative/update surface.
- Eliminates Elementor/Pro/Marquee payload from About and residual payload from coded pages that still carry Elementor metadata.
- Removes the observed orphaned `elementorFrontendConfig` console error.
- Simplifies template ownership: one coded shell, coded editorial pages, WooCommerce commerce surfaces, and the COA plugin.

The largest benefit is security and maintainability. Home, Shop, Product, Contact, and Quality Archive already suppress Elementor assets, so their incremental speed gain will be small. About and the residual coded-page loads should improve more noticeably.

## Required retirement implementation

### Phase 1 — clear the blocker in code

1. Build a coded `/about-us/` template and scoped assets using only verified claims.
2. Remove or replace the broken counters; do not invent quantities or percentages.
3. Preserve `noindex, follow` initially. Treat indexing as a separate approved content decision.
4. Replace the Elementor-specific About H2 rewrite with semantic markup in the coded template.
5. Remove the legacy Elementor shell/editor compatibility path and update rollback documentation.
6. Simplify or remove obsolete Elementor asset-dequeue logic and update its tests.
7. Keep the Hello Elementor parent dependency unchanged in this phase.

### Phase 2 — staging retirement

1. Export the 14 stored Elementor Library templates and create a named Kinsta staging backup.
2. Deactivate Marquee Addons, then Elementor Pro, then Elementor Core.
3. Clear caches and verify Home, About, Shop, product, Quality Archive, FAQ, Contact, Military/VerifyPass, legal pages, Cart, Checkout, My Account, order tracking, side cart, BOGO, rewards, shipping rates, and stock notification.
4. Verify desktop/mobile navigation, logged-in/logged-out states, browser console, PHP logs, SEO metadata, sitemap behavior, and no raw shortcode output.
5. Delete the three plugin directories on staging only after the deactivated state passes.

### Phase 3 — Live retirement

1. Create a fresh named Live backup.
2. Deploy the same tested child-theme package.
3. Deactivate and remove the same three plugins in the same order.
4. Clear caches and repeat the staging acceptance suite without submitting an order, contact message, VerifyPass request, or stock subscription.

## Rollback

- Restore the named Kinsta backup if the site or administrator becomes inaccessible.
- Before deleting plugin files, deactivation can be reversed in the order Elementor Core, Elementor Pro, then Marquee Addons.
- Retain the Elementor Library exports and the current beta.68 theme package until the Live retirement has completed its verification window.
- Do not remove the Hello Elementor parent theme during this milestone.

## Final verdict

Elementor plugin retirement is practical and low-risk **after one coded About page replacement**. Immediate removal is a no-go because page 812 is still genuinely Elementor-rendered. No other audited public route requires Elementor output.

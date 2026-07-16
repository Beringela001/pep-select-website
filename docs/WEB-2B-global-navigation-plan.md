# WEB-2B — Coded Customer-Facing Rebuild and Global Shell Plan

**Scope:** Establish the modular Hello Elementor child-theme architecture for the complete customer-facing rebuild; implement the global shell first through WEB-2B

**Checkpoint:** Planning only

**Environment:** Kinsta Staging first; Live remains untouched

**Sources of truth:**

- `docs/WEB-1-elementor-audit.md`
- `docs/WEB-1-staging-findings.md`
- `docs/WEB-2-rebuild-plan.md`
- `docs/WEB-2A-design-system-audit.md`

This document authorizes no WordPress, Elementor, theme, plugin, configuration, export, credential, database, or Live-environment change. It replaces the earlier manual Elementor implementation approach with a coded Hello Elementor child-theme strategy for the complete customer-facing WEB-2 rebuild.

## 1. Approved architecture decision

Elementor proved too unstable and inefficient for building critical global site components manually. WEB-2B will not build or publish the global shell through new Elementor Theme Builder templates.

The approved approach is:

- Do not publish or assign display conditions to the draft `Pep Select Header — WEB-2B`.
- Preserve active Elementor Header #1323 and Footer #391, including their current `Entire Site` display conditions, until the coded replacements pass testing.
- Build a lightweight Hello Elementor child theme in parallel on Staging.
- Use the child theme to control customer-facing presentation, including:
  - Announcement bar
  - Desktop and mobile header
  - Navigation
  - Product search and results presentation
  - Account, rewards, and cart controls
  - Footer
  - Homepage
  - Shop archive
  - Product-card system
  - Single-product pages
  - Cart and side-cart presentation
  - Checkout presentation
  - My Account presentation
  - Contact, FAQ, About, military-discount, and legal-page layouts
  - Responsive global spacing and styling
- Keep Elementor available only for limited editable marketing and editorial content where that remains useful.
- Do not let Elementor own the critical global shell or core commerce presentation.
- Do not move business logic into the theme.
- Make the coded replacement independently reversible by switching back to the existing Hello Elementor parent theme.
- Keep Live untouched.

## 2. Why the plan changed

### Confirmed current problem

- Staging has a 512 MB PHP memory pool across two threads, with 256 MB available per thread.
- Kinsta reported 15 memory-limit events and 28 thread-limit events.
- Opening the Home page in normal Elementor previously exhausted the 256 MB thread limit and produced an HTTP 500/critical error.
- ElementsKit Lite was the confirmed trigger. ElementsKit Lite and Pro remain disabled.
- Replacing the broken ElementsKit navigation inside Header #1323 with Elementor's native WordPress Menu widget restored the current public header, but the broader manual Elementor workflow remains inefficient for a launch-critical global shell.

### Architectural conclusion

The global shell, catalog, product, cart, checkout, account, and supporting-page presentation are launch-critical. A lightweight child theme gives them a versioned, reviewable, testable, and independently reversible home without placing customer or commerce logic in Elementor.

Elementor remains available for limited editable marketing sections and editorial content. It is no longer the planned owner of the WEB-2B global shell or the core customer-facing commerce presentation.

The successful Pep Select COA Archive development workflow is the model: define detailed requirements, implement a bounded coded system, install it in parallel on Staging, review real output, iterate from screenshots and observed behavior, verify responsive/accessibility states, package a versioned replacement, and preserve a direct rollback.

Codex will implement each customer-facing system from:

- Approved detailed requirements and acceptance criteria.
- Approved visual references and screenshots.
- Existing Staging behavior and integration evidence.
- Explicit desktop, tablet, and mobile rules.
- Accessibility, performance, and data-safety constraints.
- Small reviewable commits/packages and iterative Paulo review.

Codex must not infer final marketing/legal copy, redesign unrelated surfaces inside a milestone, or move business logic to make presentation easier.

## 3. Replacement and rollback strategy

### Build in parallel

Create the child theme as a new, version-controlled package. Install it on Staging without deleting or modifying the Hello Elementor parent theme, Header #1323, Footer #391, or the draft `Pep Select Header — WEB-2B`.

The draft Elementor header must remain unpublished and receive no display conditions. It is not a fallback, migration target, or source of truth.

### Preserve the active Elementor shell

Header #1323 and Footer #391 remain assigned to `Entire Site` while the child theme is being built and reviewed. Their template records, content, and conditions must not be deleted or overwritten.

### Theme-level takeover

The child theme will use normal WordPress child-theme template hierarchy to provide its own coded `header.php` and `footer.php`.

The child templates must preserve required WordPress hooks and document structure while rendering the coded Pep Select shell instead of invoking Elementor Theme Builder header/footer locations. This allows the existing Elementor templates and conditions to remain stored and unchanged.

The implementation must verify that:

- The child theme renders exactly one header and one footer.
- Elementor Header #1323/Footer #391 do not also render while the child theme is active.
- Switching back to the Hello Elementor parent theme restores the parent's normal Elementor location handling and the still-active #1323/#391 templates.
- No undocumented Elementor private API is required.

If normal template hierarchy cannot provide this cleanly on Staging, stop implementation and revise the plan. Do not solve the conflict by deleting templates or assigning the draft Elementor header.

### Activation rule

Child-theme activation is the shell switch. It must occur only in an approved Staging test window after the package, static checks, local rendering checks where available, and a named backup are complete.

Immediate rollback is theme activation back to the Hello Elementor parent theme. Database restoration is a secondary recovery path, not the first response.

## 4. Preservation boundary

The child theme must preserve:

- Existing Pep Select logo and branding assets.
- WooCommerce products, variations, inventory, customers, orders, coupons, taxes, shipping, and email workflows.
- The temporary emailed Square payment-link workflow.
- WooCommerce account data, login state, account endpoints, and password reset.
- Existing cart calculations and Side Cart WooCommerce/Xootix behavior.
- YITH rewards balances, calculations, conversion, coupons, and account links until WEB-2G.
- VerifyPass identity verification and WooCommerce coupon creation.
- Pep Select COA Archive, its records, search behavior, and canonical `/testing/` routes.
- Existing checkout, fulfillment, and payment integrations.
- Current legal, research-use, FDA, support, and policy content until replacement copy is approved.
- Existing functional footer destinations unless explicitly marked for review.
- Elementor page-content rendering and editing for marketing/editorial pages.
- Current Staging rollback points:
  - `Before WEB-1 Audit`
  - `After ElementsKit Removal - Elementor Fixed`
  - `Before WEB-2A Design System`
  - `After WEB-2A Global Foundations`
- Hello Elementor parent theme unchanged and available for immediate activation.
- Live unchanged.

No visual milestone may change prices, product relationships, stock, rewards rules, coupons, taxes, shipping rules, payment behavior, order states, customer records, checkout validation, email delivery, COA records, or verification logic as a side effect.

## 5. Preserve / Refine / Replace / Remove classification

| Decision | Items |
|---|---|
| Preserve | Hello Elementor parent theme; Header #1323; Footer #391; current menu records/destinations; logo assets; WooCommerce account/cart behavior; side cart; YITH rewards; `/testing/`; legal/support text; Elementor page content |
| Refine | Header hierarchy; responsive navigation; product search presentation; focus/touch states; account/rewards/cart presentation; footer hierarchy; global spacing; environment-neutral links |
| Replace | Elementor-rendered global shell while the child theme is active; broken product-search results presentation; oversized general WordPress product-search layout |
| Exclude from replacement | ElementsKit; manual Elementor header/footer rebuild; draft `Pep Select Header — WEB-2B`; hard-coded environment/legacy URLs; raw inaccessible controls; unsupported mega menus; copied legacy-brand styling |

“Replace” means the child theme becomes the active presentation layer after testing. It does not authorize deleting the preserved Elementor templates, parent theme, exports, assets, records, plugins, or data.

## 6. Ownership by layer

| Layer | Owns in WEB-2B | Must not own |
|---|---|---|
| Hello Elementor parent theme | Stable parent framework and immediate fallback | Pep Select customizations or edited parent files |
| Pep Select Hello child theme | Complete customer-facing presentation: global shell, homepage, product/search/archive/card views, single-product presentation, cart/checkout/account presentation, supporting/legal layouts, responsive/accessibility styles, and narrow WooCommerce presentation hooks/overrides when necessary | Customer/order/product data models, authentication, rewards calculations, cart calculations, COA queries, payment/shipping/email logic, secrets |
| Elementor | Limited editable marketing and editorial content where intentionally retained | Critical global shell, core commerce presentation, business logic, secrets, customer/order logic |
| WordPress | Menus, theme activation, URLs, users, authentication, query and template APIs | Duplicated theme-specific data stores |
| WooCommerce | Products, product search truth, prices, stock, cart, checkout, accounts, orders, taxes, shipping, emails | Duplicated commerce logic in the child theme |
| YITH Points and Rewards | Rewards balance, rules, conversion, and coupons | Theme-calculated rewards values |
| Side Cart WooCommerce/Xootix | Side-cart state, trigger integration, drawer, and calculations | Theme-recreated cart state |
| Pep Select COA Archive | COA search, records, statuses, and `/testing/` routes | Theme-created parallel COA search/data |
| VerifyPass | Identity verification and coupon handoff | Theme-created verification logic |
| Pep Select Site Core | Durable site-specific business behavior only when separately approved and launch-critical | Design-system panel or speculative WEB-2B backend work |

The child theme may call public WordPress, WooCommerce, YITH, side-cart, VerifyPass, and COA integration points to render presentation. It must not duplicate, recalculate, migrate, or store the data those systems own.

## 7. Complete coded rebuild scope

The modular child theme is the primary implementation layer for these customer-facing systems:

1. Announcement bar.
2. Desktop and mobile header.
3. Primary navigation and mobile navigation panel.
4. Footer.
5. Homepage presentation.
6. Shop/product archive.
7. Reusable product-card system.
8. Product search and search-results layout.
9. Single-product pages.
10. Cart and side-cart presentation.
11. Checkout presentation.
12. My Account presentation.
13. Contact page layout and form presentation.
14. FAQ layout and accessible interaction presentation.
15. About page layout.
16. Military-discount/first-responder layout and VerifyPass presentation.
17. Legal-policy page layouts after approved copy exists.
18. Responsive global styles, accessibility behavior, focus, motion, layering, and empty/loading/error states.

Each system is a separate reviewable milestone or sub-milestone. A later surface must reuse approved child-theme tokens and components rather than create a second page-specific styling system.

The current WooCommerce database and all working operational integrations remain in place. There is no customer, order, product, or operational-data migration in WEB-2.

## 8. Approved WEB-2A foundations

| Foundation | Coded-shell use |
|---|---|
| Pep Navy `#002A53` | Primary header/footer surfaces and high-emphasis controls after contrast verification |
| Pep Dark Navy `#001D3A` | Secondary dark surfaces only where intentionally specified |
| Pep Cyan `#17A1CF` | Accent, focus, active, and information treatment after contrast verification |
| Pep Ink `#13283D` | Primary text on light surfaces |
| Pep Slate `#5E6F80` | Secondary text after contrast verification |
| Pep Border `#D7E1E9` | Dividers, search fields, and subtle boundaries |
| Pep Surface `#F3F8FC` and Pep White `#FFFFFF` | Light shell/search/footer surfaces |
| Plus Jakarta Sans | Navigation, body/interface text, search, buttons, footer links, account/rewards/cart controls |
| IBM Plex Mono | Technical metadata only where appropriate; not general navigation |
| Maximum content width | `1200px` |
| Outer gutters | `32px` desktop, `24px` tablet, `20px` mobile |
| Radius system | `8px` small, `12px` medium, `20px` large, `999px` pill |
| Motion | Approximately `180ms`; subtle color, border, shadow, or `1–2px` lift; no shrink; reduced-motion behavior |
| Retained breakpoints | Mobile `767px`; Tablet `1024px` |

The child theme should mirror these approved tokens as CSS custom properties. It must not create a design-system settings panel in Site Core during the prelaunch rebuild.

## 9. Coded global-shell component structure

### 9.1 Announcement bar

- Render only if the exact operational statement is verified and approved.
- Do not copy the current free two-day shipping/$200 claim by default.
- Keep content within the `1200px` inner container and approved outer gutters.
- Use semantic text/link markup and no decorative script or marquee dependency.

### 9.2 Desktop header

- Semantic `<header>` landmark.
- Existing Pep Select logo linked to the dynamic home URL.
- Product search using WordPress/WooCommerce query APIs and a product-specific form.
- Rewards presentation supplied by the existing YITH integration.
- Account link supplied by the canonical WooCommerce My Account URL/state.
- Cart trigger supplied by the existing side-cart/WooCommerce integration.
- Semantic primary `<nav>` rendered from the current WordPress menu.
- Required primary destinations: Home; Compounds / Shop; COAs → `/testing/`; FAQ; Contact.
- No unsupported mega menu or prelaunch nested feature.

### 9.3 Mobile header and navigation

- One visible menu toggle with a meaningful name and programmatic expanded state.
- One coded navigation panel using the same WordPress menu, not a duplicated hard-coded list.
- Logo, cart, and menu remain immediately reachable.
- Account/rewards placement avoids duplicate controls and excessive blank space.
- Visible close control, Escape behavior, focus containment appropriate to the panel pattern, and focus return to the toggle.
- Correct overlay ordering with the side-cart drawer.

### 9.4 Product search and results

- Header and Shop search use one recognizable Pep Select pattern.
- Header search may remain compact; Shop search may be wider.
- Both submit a product-only WordPress/WooCommerce search through supported APIs.
- Result presentation uses bounded thumbnails, canonical product titles/links, and native WooCommerce values where displayed.
- No fallthrough to the current oversized-image general WordPress search-results layout.
- COA Archive search remains visually related but functionally separate and plugin-owned.

### 9.5 Account, rewards, and cart controls

- Use semantic links/buttons and documented public integration points.
- Preserve logged-in/logged-out account behavior.
- Preserve zero/nonzero rewards states without calculating balances in the theme.
- Preserve empty/populated cart states, side-cart opening, and cart totals without recreating cart logic.
- Icon-only controls require meaningful accessible names and at least a `44×44px` target where practical.

### 9.6 Footer

- Semantic `<footer>` landmark.
- Existing logo/branding.
- Current support information, research-use language, FDA disclaimer, and policy text unchanged.
- Clear link groups for compounds/Shop, COA Archive, support, customer account/orders, military/first responder, and policies.
- COA destination corrected to `/testing/`.
- “Track your order” label remains flagged because `/my-account/orders/` is an account-order list, not confirmed public tracking.
- External developer credit remains flagged for Paulo's approval.
- Mobile hierarchy reduces height through grouping and spacing, not hidden legal/support links.

## 10. Navigation destination register

| Destination | Source/route | Decision |
|---|---|---|
| Home | Dynamic WordPress home URL | Preserve |
| Compounds / Shop | Canonical WooCommerce Shop page | Preserve; displayed label requires visual/content approval |
| COAs | `/testing/` | Use canonical Pep Select COA Archive route; do not use `/coas/` |
| FAQ | Existing functional FAQ page | Preserve |
| Contact | Existing functional Contact page | Preserve |
| My Account | Canonical WooCommerce My Account page | Preserve |
| Rewards | Existing YITH account/rewards destination | Preserve until WEB-2G |
| Cart | Existing side-cart trigger/cart destination | Preserve |
| Orders | `/my-account/orders/` | Preserve access; do not describe as public tracking without approval |
| Military / First Responder | Current functional VerifyPass entry page | Preserve until WEB-2H confirms the canonical page |
| Policies | Current functional policy pages | Preserve unchanged until WEB-2I |
| External developer credit | Current `serviceslash.com` destination | Owner decision required; not assumed launch-critical |

Use WordPress functions/menu records for internal destinations. Do not hard-code Kinsta, Staging, Hostinger, Peptides Divas, BioQuantum, or obsolete hosts/routes.

## 11. Desktop, tablet, and mobile layouts

### Desktop: wider than `1024px`

1. Optional verified announcement bar spans the viewport; content uses the inner `1200px` container and `32px` gutters.
2. Main header establishes logo, flexible product search, and grouped rewards/account/cart controls.
3. Primary navigation receives its own clear region when necessary rather than competing for one crowded row.
4. Search width is bounded so utilities cannot be pushed off screen.
5. Current-page navigation state is apparent without relying only on color.
6. Header remains usable with browser zoom and longer labels.
7. Footer uses intentional brand/support and link-group columns, not equal-weight clutter.

### Tablet: `768px` through `1024px`

1. Use `24px` outer gutters.
2. Collapse navigation before labels become cramped; verify the exact `1024px` transition and resize without reload.
3. Keep logo, account access, and cart reachable.
4. Move search to a full or near-full row when needed.
5. Place rewards once, in the utility row or panel, without duplicate actions.
6. Footer wraps in balanced groups and keeps legal text at a readable measure.

### Mobile: `767px` and below

1. Use `20px` outer gutters.
2. Prioritize logo, cart, and one menu toggle in the first row.
3. Present product search as a full-width second row or clearly labeled expandable region.
4. Keep one navigation list and avoid duplicate account/rewards/cart controls.
5. Do not create large blank regions after navigation links.
6. Prevent body-level horizontal scrolling and nested scroll traps.
7. Menu and side-cart overlays must not compete; one layer visually and interactively dominates at a time.
8. Footer uses compact, always-discoverable groups. Legal/support text remains visible and readable through `320px`.

## 12. Product search behavior decision tree

```text
User starts a search
|
+-- Header or Shop product search
|   |
|   +-- Query is empty
|   |   `-- Do not submit; retain focus and expose a clear product-search prompt
|   |
|   `-- Query contains text
|       |
|       +-- Submit through supported WordPress/WooCommerce product search
|       |   using a verified product-only query
|       |
|       +-- Products found
|       |   `-- Render bounded product results with canonical links
|       |
|       +-- No products found
|       |   `-- Render a product-specific no-results state and a path to Shop
|       |
|       `-- Query fails
|           `-- Render a recoverable product-search error; never show oversized general results
|
`-- COA Archive search
    |
    +-- Query is empty
    |   `-- Retain the COA-specific prompt and context
    |
    `-- Query contains text
        |
        +-- Submit through Pep Select COA Archive
        +-- Records found: show compounds/testing records through `/testing/`
        +-- No records: show the plugin-owned COA no-results state
        `-- Failure: show the plugin-owned recoverable error
```

Product and COA searches share approved visual tokens and interaction language, not a query implementation or data model.

## 13. WordPress/WooCommerce implementation rules

- Use WordPress template hierarchy, `get_header()`, `get_footer()`, semantic landmarks, WordPress menu APIs, and public URL functions.
- Preserve `wp_head()`, `wp_body_open()`, `body_class()`, `wp_footer()`, and other required parent/theme/plugin integration points.
- Use WordPress and WooCommerce hooks before copying templates.
- Add a WooCommerce template override only when a hook or conditional presentation cannot meet the verified requirement.
- When an override is necessary, copy the correct installed-version template, document its source/version, keep changes minimal, and add it to the WooCommerce template-status review.
- Use the native product search request shape and WooCommerce product query; do not build a parallel product index.
- Scope product-results presentation so it does not intercept COA Archive results or unrelated WordPress searches.
- Call the existing YITH and side-cart integration through supported public hooks/functions/shortcodes; do not calculate rewards or cart contents in the theme.
- Do not expose private customer/order data in shell markup, JavaScript, or search results.
- Do not edit Hello Elementor parent files, WordPress core, WooCommerce core, or third-party plugin files.
- Do not store secrets, credentials, environment URLs, or private identifiers in the theme.

## 14. Proposed child-theme file structure

This is a proposed structure only. No files are created in this planning checkpoint.

```text
pep-select-hello-child/
├── style.css
├── functions.php
├── header.php
├── footer.php
├── front-page.php                         # Coded homepage shell when WEB-2C is approved
├── screenshot.png                         # Optional, approved branded theme thumbnail
├── README.md
├── assets/
│   ├── css/
│   │   ├── pep-select-foundations.css     # Approved tokens and global accessibility rules
│   │   ├── pep-select-shell.css           # Header, navigation, search, footer
│   │   ├── pep-select-commerce.css        # Archive, cards, product, cart, checkout, account
│   │   └── pep-select-pages.css           # Homepage and supporting/legal layouts
│   └── js/
│       ├── pep-select-navigation.js       # Menu state, Escape, focus return; no business logic
│       └── pep-select-presentation.js     # Approved UI states only; no commerce/business logic
├── inc/
│   ├── setup.php                          # Enqueues, supports, menu locations
│   ├── shell-integrations.php             # Public YITH/side-cart presentation adapters
│   ├── search-presentation.php            # Product-search routing/presentation hooks only
│   ├── woocommerce-presentation.php       # Narrow Woo hooks and template selection
│   └── page-presentation.php              # Page-template registration/presentation helpers
├── template-parts/
│   ├── header/
│   │   ├── announcement.php
│   │   ├── brand.php
│   │   ├── product-search.php
│   │   ├── customer-controls.php
│   │   └── navigation.php
│   ├── footer/
│   │   ├── brand-support.php
│   │   ├── link-groups.php
│   │   ├── legal-disclaimers.php
│   │   └── footer-bottom.php
│   ├── components/
│   │   ├── product-card.php
│   │   ├── status-badge.php
│   │   ├── notice.php
│   │   └── empty-state.php
│   ├── pages/
│   │   ├── homepage.php
│   │   ├── contact.php
│   │   ├── faq.php
│   │   ├── about.php
│   │   ├── military-discount.php
│   │   └── legal.php
│   └── search/
│       ├── product-result.php
│       ├── product-empty.php
│       └── product-error.php
├── page-templates/
│   └── README.md                           # Only approved coded page templates are added
└── woocommerce/
    ├── product-searchform.php              # Optional; only if a supported hook is insufficient
    ├── content-product.php                 # Optional product-card override after WEB-2D approval
    ├── single-product/                     # Optional narrow overrides after WEB-2E approval
    ├── cart/                               # Optional narrow overrides after WEB-2F approval
    ├── checkout/                           # Optional narrow overrides after WEB-2F approval
    └── myaccount/                          # Optional narrow overrides after WEB-2G approval
```

### File-boundary rules

- `style.css` contains the required child-theme header and only minimal bootstrap styles.
- `functions.php` is a small loader, not a monolithic implementation file.
- CSS uses WEB-2A variables and is separated by foundation, shell, commerce, and page responsibility without duplicating tokens.
- JavaScript manages accessible presentation only; it does not fetch, calculate, migrate, or store account, rewards, cart, checkout, order, product, VerifyPass, or COA data.
- `inc/shell-integrations.php` adapts existing public plugin output for placement; it does not duplicate plugin logic.
- `inc/search-presentation.php` may select product-result presentation through supported query/template hooks; it does not create a custom product database or recommendation engine.
- Each file or directory under `woocommerce/` remains absent until its milestone proves a hook cannot meet the presentation requirement. Every override records the installed WooCommerce source version and receives a template-status review.
- Do not add build tooling, package dependencies, frameworks, or a Site Core settings panel unless a separate launch-critical need is approved.

### 14.1 Page-by-page coded implementation sequence

The child theme grows through small packages and review gates. Do not implement all surfaces in one release.

1. **Child-theme foundation and WEB-2B global shell**
   - Tokens, base accessibility, header, mobile navigation, product search entry/results, account/rewards/cart controls, and footer.
   - Activate only after parent-theme rollback and Elementor page compatibility are proven.
2. **WEB-2D product-card system**
   - Build one coded reusable card against WooCommerce data.
   - Verify long names, prices, variations, stock, sale, missing image, and testing-status presentation without changing product records.
3. **WEB-2D Shop archive**
   - Apply the approved card to the Shop archive, product search, pagination, ordering, empty states, and responsive grids.
   - Keep Archive #441 and Dark Loop #65 available until every consumer is migrated.
4. **WEB-2C homepage**
   - Build the coded homepage from approved visual references, screenshots, locked content, and verified claims.
   - Reuse the approved global shell and product/COA components; preserve Home post ID 79 and Homepage #571 as rollback evidence until the coded front-page switch is approved.
5. **WEB-2E single-product presentation**
   - Add coded WooCommerce presentation through hooks first and narrow overrides only where necessary.
   - Preserve product IDs, variations, stock, bundle behavior, add-to-cart, testing history, and side-cart handoff.
6. **WEB-2F cart and side-cart presentation**
   - Style native WooCommerce/cart-plugin output without changing totals, coupons, tax, shipping, or cart state.
7. **WEB-2F checkout presentation**
   - Style the verified four-step checkout and emailed Square payment-link handoff without changing validation, order creation, emails, or payment behavior.
8. **WEB-2G My Account presentation**
   - Style login, dashboard, orders, addresses, account editing, password reset, logout, and YITH rewards while preserving permissions and records.
9. **WEB-2H Contact**
   - Build the coded layout around the existing working form behavior; production mail authentication remains a separate configuration task.
10. **WEB-2H FAQ**
    - Present existing FAQ content with an accessible coded disclosure pattern; do not classify intermittent Staging rendering as deleted content.
11. **WEB-2H About**
    - Build the approved coded layout with verified claims and meaningful icons.
12. **WEB-2H Military / First Responder**
    - Build presentation around VerifyPass-supported behavior without changing identity verification, uploads, camera access, or coupon creation.
13. **WEB-2I legal-policy layouts**
    - Build readable coded layouts, but publish replacement content only after operational/legal approval.
14. **WEB-2J integrated QA and launch candidate**
    - Test the assembled child theme across pages, customer states, widths, accessibility, performance, and preserved integrations.

For every item, Codex receives a bounded implementation brief containing exact scope, source screenshots/references, responsive behavior, states, data owner, likely files, acceptance criteria, tests, package/version rules, rollback, and a stop condition. Paulo reviews the real Staging output before the next item begins.

## 15. Accessibility requirements

### Global shell

- Use semantic `<header>`, `<nav>`, `<main>`, and `<footer>` landmarks without duplicates.
- Provide a working skip-to-main-content link.
- Ensure DOM and keyboard order match the visual order.
- Give logo/home, menu, search, rewards, account, cart, and close controls meaningful names.
- Use visible `:focus-visible` states on light and dark surfaces.
- Target at least `44×44px` interactive areas where practical and adequate spacing between adjacent controls.
- Preserve zoom, text scaling, and browser accessibility settings.

### Mobile navigation

- Toggle is a semantic button with accessible name and accurate expanded/controls state.
- Panel has a visible close path and Escape behavior.
- Focus moves intentionally on open, remains within an actual modal drawer if that pattern is used, and returns to the toggle on close.
- Closing behavior works by keyboard, pointer, and touch without trapping the user.
- Current navigation location is not communicated by color alone.

### Product search

- Visible or programmatic label distinguishes product search from COA search.
- Placeholder is not the only label.
- Submit control has a meaningful name.
- Results are keyboard reachable and retain logical order.
- Loading, no-results, and failure feedback is announced appropriately without stealing focus.
- Long titles wrap; missing images do not remove the result's text/link.

### Motion and contrast

- Normal text targets at least `4.5:1`; essential non-text controls meet applicable contrast requirements.
- Pep Cyan is tested in each actual foreground/background pairing.
- Approximately `180ms` transitions do not shift layout; no shrink effects.
- Reduced-motion mode removes nonessential movement while preserving clear states.

## 16. Performance and stability safeguards

- Keep ElementsKit Lite and Pro disabled.
- Do not load Elementor to render the coded header/footer while the child theme is active.
- Keep markup and DOM depth small; avoid duplicate desktop/mobile trees where responsive CSS can reflow one structure.
- Use one modest navigation script with no framework or third-party dependency.
- Load CSS/JavaScript only where required and use versioned enqueueing.
- Use the existing approved logo asset with declared dimensions to reduce layout shift.
- Bound product-result image dimensions and avoid full-size images.
- Do not load a large live-results product grid inside the header.
- Defer advanced autocomplete; use a reliable submit-to-results flow for launch.
- Prefer public hooks and conditional CSS over broad WooCommerce template copies.
- Avoid repeated inline CSS, raw SVG blocks, decorative animation, background video, marquee, and plugin add-ons.
- Verify that Elementor marketing pages still edit normally within the Staging 256 MB per-thread limit; the coded shell must not increase editor instability.
- Stop if the child theme creates duplicate headers/footers, breaks required hooks, causes editor critical errors, or requires an undocumented private API.

## 17. WEB-2B global-shell implementation sequence

### Phase 0 — preflight and recovery capture

1. Confirm Kinsta Staging, the correct Git branch, and a clean worktree.
2. Confirm Hello Elementor parent theme name, version, files, and current active state.
3. Confirm ElementsKit Lite and Pro remain disabled.
4. Create Kinsta backup `Before WEB-2B Coded Global Shell`.
5. Preserve all existing WEB-1/WEB-2A backups.
6. Export Header #1323 and Footer #391 and record their `Entire Site` conditions.
7. Record that draft `Pep Select Header — WEB-2B` is unpublished and has no display condition.
8. Record current menu assignments, logo asset, YITH output, My Account destination, side-cart trigger, and footer routes/text.
9. Capture current desktop/tablet/mobile screenshots for comparison.

### Phase 1 — create the child-theme foundation locally

1. Create the child theme with Hello Elementor as its declared parent.
2. Add only required theme metadata, setup/enqueue loading, and approved WEB-2A CSS variables.
3. Add no business logic, credentials, environment URLs, dependencies, or database migrations.
4. Validate PHP syntax, text-domain/escaping practices, and archive structure.
5. Package the child theme as one installable top-level theme folder.

### Phase 2 — build the coded header/footer shell

1. Implement `header.php` with required WordPress document hooks and semantic shell markup.
2. Render the current WordPress menu through menu APIs.
3. Add the existing logo, product search, My Account, YITH rewards output, and side-cart integration through supported interfaces.
4. Implement accessible desktop/tablet/mobile navigation behavior.
5. Implement `footer.php` with required WordPress footer hooks.
6. Preserve support, research-use, FDA, and policy text; update only the COA route to `/testing/`.
7. Confirm no call renders Elementor Theme Builder header/footer locations from the child templates.

### Phase 3 — implement product search presentation

1. Use a product-specific form built on supported WordPress/WooCommerce search behavior.
2. Confirm the request returns products only and does not intercept COA searches.
3. Implement bounded product-result presentation through hooks/template parts.
4. Add a WooCommerce override only if the supported hook path cannot meet the requirement.
5. Test empty, one, many, long-title, missing-image, and failure states.
6. Confirm no result reaches the oversized general WordPress product-results layout.

### Phase 4 — local/static verification and packaging

1. Run PHP syntax checks and available WordPress coding/static checks.
2. Review escaping, sanitization, nonce usage where relevant, URLs, hooks, and template output.
3. Confirm no secrets, personal data, database dump, cache, log, vendor backup, or environment hostname enters the package.
4. Validate the ZIP extracts to one child-theme folder and declares the correct parent.
5. Record a version and package hash.

### Phase 5 — install inactive and preview on Staging

1. Upload/install the child theme on Staging without activating it.
2. Use WordPress's supported theme preview where it accurately exercises the child templates.
3. Confirm one header/footer, correct global hooks, page content, styles, and integrations in preview.
4. If preview cannot test the true shell because of environment limitations, document the gap and proceed only to an approved short activation test window with rollback ready.

### Phase 6 — controlled Staging activation test

1. Reconfirm `Before WEB-2B Coded Global Shell` and the parent theme fallback.
2. Activate the Pep Select child theme on Staging.
3. Immediately verify exactly one header and footer and no Elementor shell duplication.
4. Test desktop header, mobile navigation, product search, account/rewards/cart controls, footer, and Elementor page content.
5. If a P0 failure appears, reactivate the Hello Elementor parent theme immediately.
6. Do not modify Header #1323/Footer #391 conditions to make the test pass.

### Phase 7 — full Staging QA

1. Test Home, Shop, a product page, `/testing/`, Contact, FAQ, policy pages, Cart, Checkout, and My Account.
2. Test `1440`, `1280`, `1024`, `768`, `480`, `430`, `390`, `375`, `360`, and `320px` plus resize without reload.
3. Test keyboard only, focus, zoom/large text, reduced motion, mobile drawer open, and orientation changes.
4. Test logged out/logged in, zero/nonzero rewards, empty/populated cart, and side-cart interaction.
5. Test product-search empty, one, many, long-name, missing-image, and failure states.
6. Verify COA search and `/testing/` are unchanged.
7. Verify checkout/payment, order, rewards, VerifyPass, shipping, email, and account logic remains owned by existing systems.
8. Verify representative Elementor marketing pages render publicly and open in the editor.

### Phase 8 — approval and closeout

1. Present desktop, tablet, and mobile screenshots to Paulo.
2. Resolve only WEB-2B visual decisions.
3. Export/package the approved child theme and record version/hash.
4. Record active theme, preserved parent theme, preserved Elementor template conditions, tests, residual risks, and rollback steps.
5. Create a post-verification Staging backup only after all acceptance criteria pass.
6. Confirm Live was not modified.
7. Stop before WEB-2C, WEB-2D, deployment, or legacy cleanup.

## 18. WEB-2B acceptance criteria and rollback by surface

### 18.1 Desktop header

**Acceptance criteria**

- Exactly one semantic header renders while the child theme is active.
- Logo, product search, rewards, account, cart, and primary navigation are clearly organized within the `1200px` container and approved gutters.
- Home, Compounds / Shop, COAs (`/testing/`), FAQ, and Contact resolve correctly.
- Header remains readable at `1440`, `1280`, and `1024px`, zoomed text, and browser resize without overlap.
- Controls have meaningful names, visible focus, correct current-page state, and comfortable targets.
- No ElementsKit or Elementor Theme Builder header rendering is required while the child theme is active.

**Rollback**

1. Reactivate Hello Elementor parent theme.
2. Confirm Header #1323 renders through its preserved `Entire Site` condition.
3. Verify logo, menu, product search, rewards, account, and cart.
4. Clear only necessary Staging caches and record the failure.

### 18.2 Mobile navigation

**Acceptance criteria**

- One toggle opens one navigation panel using the current WordPress menu.
- Toggle/close semantics, expanded state, Escape, focus handling, and focus return work.
- Navigation contains no excessive blank area, duplicate controls, horizontal scroll, or keyboard trap.
- Logo/cart remain reachable, and menu layering does not conflict with the side cart.
- Behavior passes `767`, `480`, `430`, `390`, `375`, `360`, and `320px` plus resize without reload.

**Rollback**

1. Reactivate Hello Elementor parent theme.
2. Verify Header #1323's native Elementor WordPress Menu mobile behavior returns.
3. Confirm the side cart and account access remain reachable.

### 18.3 Product search

**Acceptance criteria**

- Header and Shop search submit product-only searches through supported WordPress/WooCommerce behavior.
- Results contain bounded images, readable titles, canonical links, and appropriate no-results/failure states.
- Searches never land on the oversized-image general WordPress results layout.
- COA search remains plugin-owned and returns testing records through `/testing/`.
- Search is labeled, keyboard operable, and handles no, one, many, long-title, and missing-image states.
- No custom product database, advanced autocomplete, or copied Dark Loop #65 is required.

**Rollback**

1. Reactivate Hello Elementor parent theme to restore the preserved Elementor shell/search path.
2. If the child theme remains active for a non-search test, disable only the child theme's optional product-search presentation module through its documented reversible configuration; do not alter product data.
3. Verify Shop, product links, and COA Archive routes.

### 18.4 Cart/account/rewards controls

**Acceptance criteria**

- Logged-out/logged-in account destinations work through canonical WooCommerce URLs.
- YITH zero/nonzero rewards output matches the existing system; no reward value is calculated by the theme.
- Empty/populated cart and side-cart opening work; totals and items match WooCommerce.
- Controls are labeled, focusable, touch-friendly, and do not expose private customer/order data.
- No checkout, order, coupon, reward, shipping, payment, or email behavior changes.

**Rollback**

1. Reactivate Hello Elementor parent theme.
2. Verify Header #1323 restores the known rewards, account, and side-cart integration points.
3. Confirm cart contents and customer session remain intact; do not clear customer data as a rollback step.

### 18.5 Footer

**Acceptance criteria**

- Exactly one semantic footer renders while the child theme is active.
- COA link uses `/testing/`.
- Existing Shop, FAQ, Contact, account/orders, military, support, and policy destinations remain available.
- Research-use language, FDA disclaimer, support information, and legal text are not rewritten.
- Desktop/tablet grouping is clear; mobile height is reduced without hiding important legal/support links.
- Links are keyboard/touch accessible and readable through `320px`.

**Rollback**

1. Reactivate Hello Elementor parent theme.
2. Confirm Footer #391 renders through its preserved `Entire Site` condition.
3. Verify policy, support, account/orders, military, and COA links.
4. Note that the old `/coas/` link may return with Footer #391; this is a known fallback limitation, not permission to delete the template.

### 18.6 Elementor page-content compatibility

**Acceptance criteria**

- Existing Elementor-built marketing/editorial pages render their content inside the coded global shell.
- Home, Contact, FAQ, About, policy pages, product/archive templates, Cart, Checkout, and My Account retain their current page/template ownership.
- Representative pages open in Elementor without a new critical error or missing content.
- The child theme does not override `page.php`, `singular.php`, Elementor canvas/full-width layouts, or content loops unless a separately documented launch-critical need is approved.
- Elementor CSS, frontend scripts, dynamic tags, forms, and page-level responsive settings continue to load where currently required.

**Rollback**

1. Reactivate Hello Elementor parent theme.
2. Verify the affected page publicly and in Elementor editor.
3. If parent-theme activation does not restore the page, restore `Before WEB-2B Coded Global Shell` and stop.

## 19. Full rollback procedure

### Primary rollback: theme switch

1. In Staging Appearance → Themes, activate the existing Hello Elementor parent theme.
2. Confirm Header #1323 and Footer #391 render from their unchanged `Entire Site` conditions.
3. Verify Home, Shop, product, `/testing/`, Contact, Cart, Checkout, My Account, and a policy page.
4. Verify menu, product search, rewards, account, side cart, and footer destinations.
5. Clear only necessary Staging/Elementor caches.
6. Record the child-theme version, failure, viewport/state, and rollback result.

### Secondary rollback: backup restore

Use `Before WEB-2B Coded Global Shell` only if switching to the parent theme does not restore the known baseline or if database-held settings were unexpectedly affected.

### Rollback invariants

- Do not delete the child theme during diagnosis; keep the failed package as evidence.
- Do not delete Header #1323, Footer #391, or their conditions.
- Do not publish or assign the draft `Pep Select Header — WEB-2B`.
- Do not clear carts, customer sessions, orders, or plugin data.
- Do not change Live.

## 20. Explicit exclusions

- No website, theme, Elementor, plugin, configuration, export, credential, or database change during this planning checkpoint.
- No child-theme files created in this checkpoint.
- No direct edits to the Hello Elementor parent theme.
- No publication or display-condition assignment for `Pep Select Header — WEB-2B`.
- No deletion, unpublishing, or condition change for Header #1323 or Footer #391 during parallel development.
- No ElementsKit Lite or Pro restoration.
- No business logic in the child theme.
- No product, price, variation, stock, tax, shipping, coupon, customer, order, checkout, payment, email, rewards, VerifyPass, COA, or side-cart logic changes.
- No custom account/authentication implementation.
- No combined product/COA backend search.
- No advanced autocomplete, predictive search, fuzzy search, recommendations, search analytics, mega menu, or unsupported dropdown before launch.
- No implementation of the later homepage, Shop/card, single-product, Cart/Checkout, My Account, supporting-page, or legal-layout milestones during the WEB-2B global-shell checkpoint. Those surfaces remain coded child-theme scope but require their own bounded approval, implementation, QA, package, and rollback gate.
- No final marketing, shipping, support, FDA, research-use, or legal copy rewrite.
- No Site Core design-system panel or speculative backend work.
- No dependency/plugin/core update campaign.
- No Live deployment or production theme activation.

## 21. Items requiring Paulo’s approval before Staging activation

- Desktop header hierarchy and whether navigation occupies its own row.
- Logo display size and clear space without altering the source asset.
- Whether a verified announcement bar is visually included; wording requires separate operational approval.
- Header search width and tablet/mobile placement.
- Order and presentation of rewards, account, cart, and menu controls.
- Mobile drawer width, overlay, close control, link spacing, and customer-control placement.
- Product-results presentation: compact list versus bounded small-card grid before WEB-2D defines the canonical product-card system.
- Footer desktop grouping, tablet wrap order, and mobile grouping.
- Whether the external developer credit remains visible.
- Focus-ring appearance on Pep White and Pep Navy after contrast testing.
- Approval to activate the child theme for the controlled Staging test window.

These approvals do not authorize final copy, business-logic changes, Live deployment, or removal of the preserved Elementor templates.

## 22. Child-theme foundation created

The first foundation-only WEB-2B source was created locally as version `0.1.0`. Its initial distributable package failed WordPress installation and has been superseded by corrected version `0.1.1`.

- Local theme path: `pepselect-child/`
- Corrected distributable ZIP: `dist/pepselect-child-0.1.1.zip`
- ZIP SHA256: `A0D61ED1CDB6F7C5D91A6B0B5E5571828012142F2E748AB477C70195D123564B`
- Parent declaration: Hello Elementor (`Template: hello-elementor`)
- Package structure: one top-level `pepselect-child/` folder, with `style.css` and `functions.php` directly inside it
- Local references: all child-theme files referenced by the bootstrap and enqueue code exist
- URL and credential scan: no hard-coded Staging, Live, legacy-brand, or external URLs and no secrets or credentials were found
- Commerce boundary: no `woocommerce/` directory, WooCommerce template override, business logic, customer-data access, tracking, analytics, remote request, or external dependency was introduced
- Presentation boundary: no header, footer, search, page, or Elementor template replacement was introduced
- PHP validation: the PHP source passed a manual and structural static syntax review; `php -l` was unavailable because this local environment has no PHP CLI executable

At the time version `0.1.1` was packaged, it had not yet been installed or activated. Its subsequent Staging-only installation and activation are recorded below.

### Version 0.1.0 installation failure and 0.1.1 correction

- WordPress Staging rejected version `0.1.0` with: `The package could not be installed. The theme is missing the style.css stylesheet.`
- The source `style.css` header was valid, but the Windows `Compress-Archive` package stored entry names with backslash bytes, such as `pepselect-child\style.css`, rather than portable ZIP paths such as `pepselect-child/style.css`.
- The earlier validation incorrectly normalized backslashes to forward slashes before reporting the entry list, which concealed the packaging defect.
- Version `0.1.1` uses explicitly constructed forward-slash entry names, contains one top-level `pepselect-child/` directory, and has been verified both directly from the archive and after temporary extraction.
- No theme behavior, WordPress record, Elementor template, plugin, configuration, credential, database content, Staging setting, or Live environment was changed by this package repair.

## Child Theme Installed and Activated on Staging

- Pep Select child theme version `0.1.1` installed successfully on Staging.
- WordPress correctly detected Hello Elementor `3.4.9` as the parent theme.
- Live Preview loaded successfully before activation.
- The child theme was activated only on Staging.
- The existing Elementor homepage, Header #1323, Footer #391, navigation, products, and page content continued loading after activation.
- No WooCommerce templates or business logic have been replaced yet.
- Live remains untouched.
- The existing Hello Elementor parent theme remains installed for immediate rollback.
- The unpublished draft `Pep Select Header — WEB-2B` still has no display conditions and is not active.
- Staging rollback backups now include:
  - `Before WEB-2B Header Footer Rebuild`
  - `After WEB-2B Child Theme Activation`
- The failed version `0.1.0` package attempt is resolved by the successfully installed version `0.1.1` package. The packaging cause and correction remain documented in the preceding section and are not repeated here.

## Coded Header Preview Created

Version `0.2.0` adds the first coded WEB-2B header behind a private preview. This checkpoint creates no public header switch and changes no Elementor display condition.

### Preview access and isolation

- Preview query parameter: `?pepselect_header_preview=1`
- Access is restricted to logged-in users with the WordPress `manage_options` capability.
- Authorized preview responses send no-cache headers.
- During an authorized preview, the coded header renders through `wp_body_open` and Elementor Header #1323 is suppressed for that request through Elementor's documented Theme Location template-ID filter.
- The Hello Elementor fallback header is hidden by preview-only CSS; the existing Elementor footer and normal page content remain visible.
- Without the exact query value and capability check, no coded-header asset, markup, body class, or Elementor-header suppression is applied. Ordinary visitors continue to receive Header #1323 unchanged.
- The unpublished draft `Pep Select Header — WEB-2B` remains inactive and has no display conditions.

### Coded preview contents

- Announcement: `Free 2-Day Shipping on Cart Subtotals of $200`.
- Custom logo from WordPress site settings, with the site name as the no-logo fallback.
- Product-only search using standard `s` and `post_type=product` query parameters and no autocomplete.
- Exactly five primary destinations: Home, Compounds, COAs at `/testing/`, FAQ, and Contact.
- Responsive desktop, tablet, and mobile layouts using the approved WEB-2A colors, typography, `1200px` content width, approved gutters, radii, and approximately `180ms` motion.
- Mobile navigation uses a semantic button, accurate `aria-expanded`, Escape-to-close with focus return, link-close behavior, and responsive state reset. It never locks body scrolling.
- Search-results presentation is not replaced in this checkpoint and remains a later WEB-2B repair.

### Confirmed integrations and fallbacks

- Header #1323 export confirms the YITH Points and Rewards shortcode `[yith_ywpar_points label="" show_worth="no"]`. Version `0.2.0` uses it only when WordPress reports that shortcode as registered.
- Header #1323 export confirms the Xootix Side Cart shortcode `[xoo_wsc_cart]`. Version `0.2.0` uses it only when WordPress reports that shortcode as registered, without recreating cart state or calling plugin JavaScript.
- My Account, Cart, and Shop destinations use public WooCommerce URL functions when available; no Staging or Live hostname is stored in the theme.
- Navigation checks the export-confirmed `new` menu first, then assigned and existing WordPress menus. Only safe same-site matches for the five approved labels are used; missing matches receive controlled environment-neutral fallbacks, and COAs always resolves to `/testing/`.
- If the YITH balance shortcode is unavailable, the header shows a Rewards link without an invented point value.
- If the Xootix shortcode is unavailable, the header shows the normal WooCommerce Cart link with the server-rendered WooCommerce item count.
- If no custom logo exists, the linked WordPress site name is shown.

### Validation and package

- Theme version: `0.2.0`.
- Package: `dist/pepselect-child-0.2.0.zip`.
- SHA256: `335DC6A9B96FCFF36704A6D1197B57B5AAACF3B8DB946EE590B292B6AC394865`.
- JavaScript syntax passed the bundled Node.js syntax check.
- PHP source passed manual and structural static review; PHP CLI remains unavailable locally, so `php -l` was not run.
- Every required and referenced local file exists.
- Static checks confirm the exact preview parameter, `manage_options` restriction, preview-only assets, scoped Elementor suppression, no settings/data writes, and no `header.php` override.
- No hard-coded environment domain, secret, credential, remote request, analytics, tracking call, WooCommerce template override, or business-logic replacement was found.
- Mobile CSS uses bounded widths, `min-width: 0`, `max-width: 100%`, no `100vw`, and no body scroll lock; no obvious body-level horizontal-overflow rule was found.
- The ZIP contains one `pepselect-child/` root, uses forward-slash paths, contains no nested theme folder, matches the source files byte-for-byte, and reproduced the same files after temporary extraction.
- This repository checkpoint did not upload version `0.2.0`, modify Staging or Live, or change WordPress, Elementor, plugins, configuration, credentials, or database content.

## Header Preview Logo and Rewards Correction

Version `0.2.1` corrects two narrow preview integrations without changing the public header or any Elementor condition.

- The active Header #1323 export and Staging markup both identify WordPress Media Library attachment `595`, `Logo_Pepselect_Whitebackground-1.png`, as the current Pep Select logo. The coded preview now renders that attachment through WordPress image functions, prefers a future valid Custom Logo setting, stores no environment-specific image URL, and retains the accessible site-name fallback when neither attachment is available.
- YITH's official documentation identifies `[yith_ywpar_points]` as the supported remaining-points display for the logged-in user. The preview uses that registered shortcode only for a logged-in user. It does not query YITH tables, instantiate undocumented classes, or default a missing balance to zero; unavailable or empty supported output leaves the functional Rewards link without a number.
- Version `0.2.1` remains restricted to authorized `?pepselect_header_preview=1` requests. Header #1323 remains unchanged for ordinary visitors, and footer, search, cart, account, navigation, WooCommerce behavior, and business logic remain outside this correction.
- Test package: `dist/pepselect-child-0.2.1.zip`; SHA256: `3A777CA03283F6DE577A7BC8C975C156B67A7318C91291C097DC9C4E9993C273`.

## Mobile Header Preview Refinement

Version `0.2.2` changes only coded-header preview styles at widths up to `767px`. It increases announcement readability, enlarges the logo, keeps My Account, cart, and menu controls at practical 44px targets with more space, places the full-width product search on its own 48px row, and keeps Rewards as a labeled link inside the collapsible navigation rather than the icon row. Bounded grid, action, logo, and search widths prevent header-level horizontal overflow without clipping the side-cart drawer. Desktop/tablet styling, JavaScript accessibility behavior, preview authorization, Elementor conditions, WooCommerce behavior, and Live remain unchanged.

- Test package: `dist/pepselect-child-0.2.2.zip`; SHA256: `840FB0F630A8E8536925F9CD633B0D558156B324308E08206E1B16FD618DC726`.

## Header Proportion and Mobile State Refinement

Version `0.2.3` slightly increases desktop logo presence with layout-neutral visual scaling, reduces desktop product-search width, and compacts Rewards spacing while retaining the existing vertical padding, control heights, `1200px` inner-width token, and balanced account/cart actions. These proportion rules apply only above `1024px`. On mobile, the approved version `0.2.2` structure remains unchanged; Rewards now receives `is-current` and `aria-current="page"` only when its WooCommerce account endpoint matches the current route. Non-current hover treatment no longer uses the active-page cyan border or dark background, and keyboard focus remains independently visible through the existing focus outline.

- Test package: `dist/pepselect-child-0.2.3.zip`; SHA256: `599C41193E598FE05372A02953C73A704328F5248A38327AA30F2F9D339A2934`.

## Coded Footer Preview Created

Version `0.3.0` adds the first coded WEB-2B footer behind private administrator previews. It does not publish a replacement, alter Elementor display conditions, or change ordinary requests.

- `?pepselect_footer_preview=1` shows the coded footer with the existing Elementor Header #1323.
- `?pepselect_header_preview=1` continues to show only the coded header with Elementor Footer #391.
- `?pepselect_shell_preview=1` shows the coded header and footer together.
- Every preview requires a logged-in user with `manage_options`; authorized responses receive no-cache headers and load only the assets for the requested component.
- Footer #391 remains active outside preview. During footer or shell preview it is suppressed for that request through Elementor's Theme Location filter, with the Hello Elementor fallback footer hidden by scoped preview CSS.
- The coded footer prefers the configured WordPress Custom Logo and otherwise uses Footer #391's confirmed Media Library attachment `687`; the site name remains the accessible no-logo fallback.
- Current research-use statements, `support@pepselect.com`, current internal link labels, the canonical `/testing/` COA route, and the exact published FDA disclaimer are preserved. Internal destinations use WordPress or WooCommerce URL functions and environment-neutral fallbacks.
- The copyright year is dynamic and the external developer credit is omitted from the private coded preview pending final approval.
- The responsive footer uses the approved WEB-2A colors, typography, `1200px` width, gutters, spacing, visible focus, reduced motion, and practical mobile link targets. Legal and support content remains visible through mobile layouts.
- No WooCommerce template, business logic, customer data, Elementor condition, WordPress record, plugin, configuration, Staging setting, or Live environment was changed.
- Test package: `dist/pepselect-child-0.3.0.zip`; SHA256: `2792ED92425024F28068DCB1F5210B7E8375AEAACEFF33AD95AA31171C87D7A3`.
- Package validation confirmed one `pepselect-child/` root, portable forward-slash entries, required theme files, no nested theme folder, successful temporary extraction, and byte-for-byte source matching across all `20` files.

## Coded Shell Activated by Default

Version `0.3.1` makes the approved coded header from `0.2.3` and coded footer from `0.3.0` the default site shell whenever the Pep Select child theme owns a supported front-end request. Elementor continues to render all page content between them.

- Normal front-end requests render the coded header and footer and suppress Elementor Header #1323 and Footer #391 through the existing Theme Location filters.
- A logged-in administrator with `manage_options` may append `?pepselect_legacy_shell=1` to restore Header #1323 and Footer #391 for that uncached request only. Unauthorized visitors remain on the coded shell.
- Coded-shell replacement is bypassed in wp-admin, Elementor editor requests, WordPress Customizer previews, login screens, REST requests, AJAX requests, cron, feeds, and CLI contexts.
- The earlier header, footer, and combined preview parameters remain accepted as administrator-only compatibility controls, but the coded shell no longer requires a query parameter.
- Header #1323, Footer #391, their Elementor display conditions, and all Elementor page content remain stored and unchanged. No permanent setting or database value is added.
- Hello Elementor remains installed as the immediate theme-level rollback.
- Staging rollback backup: `Before WEB-2B Coded Shell Activation`.
- Live remains untouched.
- Test package: `dist/pepselect-child-0.3.1.zip`; SHA256: `B1D2E59482AA0BA2121E14D40B512D3063A9025A799941EC7EA6B61AFF7E81C1`.

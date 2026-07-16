# WEB-2B — Header, Navigation, Search, and Footer Implementation Plan

**Checkpoint:** Planning only

**Environment:** Kinsta Staging first; Live remains untouched

**Sources of truth:**

- `docs/WEB-1-elementor-audit.md`
- `docs/WEB-1-staging-findings.md`
- `docs/WEB-2-rebuild-plan.md`
- `docs/WEB-2A-design-system-audit.md`

This document authorizes no WordPress, Elementor, plugin, configuration, export, credential, database, or Live-environment change. It defines the controlled implementation sequence for the later WEB-2B implementation checkpoint.

## 1. Objective

Replace the fragile global presentation shell with a clean, accessible Pep Select header, navigation, product-search experience, and footer while preserving the working commerce, account, rewards, cart, COA Archive, and legal destinations underneath it.

WEB-2B is a presentation milestone. It must not change products, prices, stock, customers, orders, rewards calculations, side-cart calculations, checkout, the emailed Square payment-link workflow, COA records, or account behavior.

## 2. Recommended replacement strategy

### Recommendation: build parallel replacement templates

Build new draft templates named clearly for WEB-2B, with no active display conditions, rather than modifying Header #1323 and Footer #391 directly.

Only switch the `Entire Site` display conditions after the replacements pass preview, responsive, accessibility, search, account, rewards, and cart testing on Staging.

### Why this minimizes risk

- Header #1323 and Footer #391 are assigned across the entire site. Editing either directly exposes every Staging page to incomplete work.
- Header #1323 is currently functional after the ElementsKit menu was replaced with Elementor's native WordPress Menu widget. It is a known-good rollback point even though its broader presentation needs replacement.
- The header binds navigation to product search, YITH rewards, WooCommerce account access, and the side-cart trigger. A partial edit could strand launch-critical customer actions.
- Footer #391 contains policy, support, research-use, FDA, account-order, military, and COA destinations. A partial edit could hide compliance or support access.
- Parallel templates allow isolated preview and immediate rollback by restoring the old Theme Builder conditions.
- The existing templates and exports remain historical and recovery evidence. WEB-2B does not delete them.

### Switching rule

Do not leave old and new global templates assigned to `Entire Site` simultaneously. During the approved switch window, remove the old condition and add the corresponding replacement condition as one controlled operation, then run an immediate public Staging smoke test.

Switch and verify the header first. Switch and verify the footer second. If either fails, restore its old condition immediately without disturbing the other verified surface.

## 3. Preservation boundary

WEB-2B must preserve:

- Existing Pep Select logo files, proportions, destinations, and approved branding assets.
- The current WordPress menu and correct navigation destinations.
- WooCommerce account access and logged-in/logged-out behavior.
- WooCommerce cart behavior and the existing side-cart integration.
- YITH rewards balances and account/rewards links until WEB-2G.
- `/testing/` as the official Pep Select COA Archive destination.
- Pep Select COA Archive ownership of COA search, testing records, and result routes.
- Existing functional footer destinations unless this plan explicitly marks a label or route for review.
- Current legal, research-use, FDA, support, and policy text until separately approved content exists.
- Desktop and mobile access to all primary navigation and customer utilities.
- The current Staging rollback points:
  - `Before WEB-1 Audit`
  - `After ElementsKit Removal - Elementor Fixed`
  - `Before WEB-2A Design System`
  - `After WEB-2A Global Foundations`
- The working Staging environment and all customer, order, checkout, and account data.
- Live unchanged.

## 4. Current-state summary

### 4.1 Header and navigation

- Header #1323 is assigned to `Entire Site` on Staging.
- The supplied export is `header-elementor-73.json`, titled `Elementor Header #1323`; the filename/export ID mismatch must remain documented.
- ElementsKit Lite is the confirmed trigger for Elementor editor memory exhaustion under Staging's 256 MB per-thread limit.
- ElementsKit Lite and Pro remain disabled.
- Disabling ElementsKit removed the old navigation widget. It was replaced on Staging with Elementor's native WordPress Menu widget, configured for desktop and mobile, publicly tested, and published.
- The exported header couples the logo, announcement bar, desktop/mobile search, YITH rewards shortcode, account access, side-cart shortcode, and primary navigation.
- The export contains hard-coded live-domain account, rewards, and logo asset URLs. Replacement links must be WordPress-aware or canonical rather than environment-specific.
- The raw account link in the export does not prove a meaningful accessible name.
- The header previously used separate desktop and mobile search widgets. Duplicate interactive controls create focus and behavioral drift risk if visibility rules fail.
- The shipping announcement makes a specific free two-day shipping promise at a `$200` subtotal. It is unverified and must not be carried into the replacement without current operational approval.

### 4.2 Search

- WEB-1 confirmed that both header search and compound/product search results render with broken layouts and oversized product imagery.
- The exported header search references shared Dark Loop #65 for results. Loop #65 is also used by Archive #441, Homepage #571, and related-product contexts.
- Changing Dark Loop #65 inside WEB-2B would create cross-milestone risk. Its full replacement remains owned by WEB-2D.
- Product search and COA search are different behaviors even though they must share one recognizable visual pattern:
  - Header and Shop search must query WooCommerce products.
  - COA Archive search must remain owned by Pep Select COA Archive and return compounds/testing records through `/testing/` routes.
- The generic WordPress search-results layout is not an acceptable launch destination.

### 4.3 Footer

- Footer #391 is assigned to `Entire Site` on Staging.
- The supplied export is `footer-elementor-70.json`, titled `Elementor Footer #391`; the mismatch must remain documented.
- The footer uses Elementor Image, Text Editor, Icon List, Heading, and container widgets. It contains ElementsKit metadata but no direct ElementsKit widget in the exported tree.
- The export hard-codes 12 `pepselect.kinsta.cloud` URLs/assets.
- The COA link points to the obsolete `/coas/` route and uses the singular label “Certificate of Analysis.” The replacement must use the approved COA destination `/testing/`; final label styling still requires visual review.
- “Track your order” currently points to `/my-account/orders/`. That destination is a customer order list, not proof of a public tracking feature. Preserve access to orders, but mark the label for later content approval rather than implying functionality that is not present.
- The footer has seven mobile overrides and no tablet-specific overrides despite multiple link groups and long legal text.
- Research-use language, FDA disclaimer, support information, copyright, and policy text require preservation and verification, not rewriting in WEB-2B.

## 5. Preserve / Refine / Replace / Remove classification

| Decision | Items |
|---|---|
| Preserve | Pep Select logo/brand assets; active WordPress menu destinations; WooCommerce account/cart behavior; side-cart provider and trigger; YITH rewards calculation/balance; `/testing/`; existing legal/support/footer text; Header #1323 and Footer #391 as rollback templates |
| Refine | Native WordPress Menu presentation; header information hierarchy; link grouping; responsive layouts; focus and touch states; footer hierarchy; account/rewards/cart labels and accessible names; environment-neutral links |
| Replace | Header and footer presentation templates; broken product-search presentation; oversized general search-results layout; duplicate desktop/mobile search structure where one responsive instance can serve both |
| Remove from replacements | ElementsKit widgets/dependency; hard-coded Kinsta/Hostinger/legacy-brand URLs; inaccessible raw HTML controls where a native Elementor widget is available; shrink effects; unsupported mega-menu/dropdown behavior; copied legacy-brand styling |

“Remove” here means omit from the new replacement. It does not authorize deleting current templates, exports, assets, plugins, records, or historical evidence.

## 6. Approved WEB-2A foundations to apply

| Foundation | WEB-2B use |
|---|---|
| Primary Pep Select Navy `#002A53` | Primary header/footer surfaces, high-emphasis controls, or text where contrast is verified |
| Pep Cyan `#17A1CF` | Focus/active/accent treatment where contrast is verified; not the sole indicator of state |
| Plus Jakarta Sans | Navigation, search, buttons, account/rewards/cart labels, footer links, and general interface text |
| Maximum content width `1200px` | Inner header, navigation, search, and footer containers |
| Gutters | `32px` desktop, `24px` tablet, and `20px` mobile on outer containers only |
| Radii | `8px` small, `12px` medium, `20px` large, `999px` pill; large rounding reserved for prominent features |
| Motion | Approximately `180ms`; subtle color, border, shadow, or `1–2px` lift only; no shrink; reduced-motion alternative |
| Retained breakpoints | Mobile `767px`; Tablet `1024px` |

Use the custom Elementor global color and font tokens already added on Staging. Do not change Elementor System Colors, System Fonts, global container padding, global gaps, global buttons, or Theme Styles during WEB-2B.

## 7. Architecture and ownership

| Concern | Source of truth / owner | WEB-2B responsibility |
|---|---|---|
| Header/footer composition | Elementor Theme Builder | Build lightweight replacement templates using native Elementor widgets |
| Primary navigation destinations | WordPress menu assignment | Reuse the current menu and correct destinations; do not duplicate links manually unless a documented limitation requires it |
| Product catalog/search data | WooCommerce products and native query context | Present product search safely without altering products, stock, prices, or query truth |
| COA search/data/routes | Pep Select COA Archive | Apply approved presentation only through plugin-supported output; do not recreate its query or records in Elementor |
| Account identity and endpoints | WordPress/WooCommerce | Preserve links and state; do not implement authentication logic in Elementor |
| Rewards balance/rules | YITH Points and Rewards | Preserve the existing shortcode/integration and calculations; presentation only |
| Side cart | Side Cart WooCommerce/Xootix and WooCommerce cart | Preserve `[xoo_wsc_cart]` or its verified integration point; presentation/trigger only |
| Global tokens | Elementor custom globals documented in WEB-2A | Consume approved tokens; do not create a Site Core settings panel |
| Theme-coupled search routing/style, if native tools are insufficient | A separately justified child-theme/presentation layer | Requires an explicit implementation approval; keep narrow and presentation-only |
| Durable custom search behavior, if genuinely required | Pep Select Site Core only when launch-critical and approved | Not assumed in WEB-2B; advanced autocomplete and speculative backend work are deferred |

Native Elementor Shortcode widgets may host the existing YITH and side-cart integrations. That does not transfer ownership of rewards or cart logic to Elementor.

## 8. Proposed component structure

### 8.1 Header replacement

1. **Optional announcement region**
   - Present only if its exact operational claim is verified and approved.
   - Do not copy the current shipping promise by default.
   - Keep it short, dismiss-free for launch unless dismissal state is genuinely required, and readable at all widths.
2. **Identity and customer-utility region**
   - Existing Pep Select logo linked through the dynamic Site URL.
   - Product search with a visible or programmatic label and clear submit control.
   - Rewards link/balance using the current YITH integration.
   - Account link using the canonical WooCommerce My Account destination.
   - Cart/side-cart trigger using the current working integration.
3. **Primary-navigation region**
   - Elementor's native WordPress Menu widget using the existing assigned menu.
   - Required destinations: Home; Compounds / Shop; COAs → `/testing/`; FAQ; Contact.
   - No mega menu or unverified nested dropdown before launch.
4. **Mobile navigation panel**
   - One menu toggle with a meaningful accessible name.
   - The same WordPress menu, not a separately maintained duplicate list.
   - Essential customer links arranged without duplicating visible header controls unnecessarily.
   - A visible close path, Escape behavior where supported, logical focus order, and no trapped focus.

### 8.2 Product search components

1. A compact header product-search control.
2. A wider Shop search variant using the same field, icon, border, type, radius, focus, loading, and error language.
3. A bounded, lightweight product-results presentation for WEB-2B:
   - Controlled thumbnail dimensions or a missing-image fallback.
   - Product name and canonical product link.
   - WooCommerce-owned price only if the chosen native result widget supplies it reliably.
   - Clear no-results and error states.
   - No use of the current oversized general WordPress image layout.
4. No changes to shared Dark Loop #65 during this milestone. A dedicated temporary search-result item may be built for WEB-2B and later reconciled with the canonical WEB-2D product-card system.

### 8.3 COA search component

1. Retain Pep Select COA Archive as the query, record, and routing owner.
2. Apply the same approved outer shape, type, icon, border, focus, and interaction language as product search where the plugin supports safe styling.
3. Keep the task and destination distinct: COA search returns compounds/testing records under `/testing/`, not WooCommerce products.
4. Do not copy old `coa` post-type search logic, Loop #485, `[coa_table]`, or `/coas/` routing into the new header.

### 8.4 Footer replacement

1. **Brand/support region**
   - Existing logo/brand asset.
   - Current support information, pending factual verification.
   - Current research-use and FDA language unchanged.
2. **Navigation groups**
   - Shop/compounds and COA Archive (`/testing/`).
   - Support destinations such as FAQ and Contact.
   - Customer destinations such as My Account/orders where currently functional.
   - Military/first responder destination, preserving the current VerifyPass flow until WEB-2H.
3. **Policy group**
   - Existing Privacy Policy, Terms & Conditions, Refund & Shipping Policy, and RUO Disclaimer destinations.
   - No legal rewrite or placeholder-date correction in WEB-2B.
4. **Bottom line**
   - Preserve current copyright and any approved credit until content approval.
   - Review external developer-credit intent before carrying it forward.

Use native Elementor Heading, Text Editor, Image, Icon List, Social/Icon, Menu, and container widgets as appropriate. Do not introduce ElementsKit or raw HTML for standard controls.

## 9. Navigation destination register

| Visible destination | Required route/source | Decision |
|---|---|---|
| Home | Dynamic Site URL/home | Preserve |
| Compounds / Shop | Canonical WooCommerce Shop page | Preserve; Paulo approves the displayed label |
| COAs | `/testing/` | Correct the obsolete `/coas/` destination |
| FAQ | Existing functional FAQ page | Preserve |
| Contact | Existing functional Contact page | Preserve |
| My Account | Canonical WooCommerce My Account page | Preserve |
| Rewards | Existing YITH account/rewards destination | Preserve until WEB-2G; do not change rules |
| Cart | Existing side-cart trigger and cart destination | Preserve |
| Orders | `/my-account/orders/` | Preserve access; do not call it public “tracking” without approved wording |
| Military / First Responder | Current functional page/VerifyPass entry | Preserve until WEB-2H determines the canonical page |
| Policy links | Current functional policy pages | Preserve unchanged until WEB-2I |
| External developer credit | Current `serviceslash.com` destination | Mark for owner approval; not assumed required for launch |

All internal destinations must use dynamic/relative WordPress-aware links or canonical WordPress page/menu assignments. Do not hard-code Kinsta, Staging, Hostinger, Peptides Divas, or BioQuantum hosts.

## 10. Desktop layout

**Target:** wider than the retained `1024px` tablet breakpoint.

1. Optional verified announcement row spans the viewport; content sits within the `1200px` inner container and `32px` outer gutters.
2. Main utility row uses a stable three-part hierarchy:
   - Logo/identity at the start.
   - Product search as the flexible central region.
   - Rewards, account, and cart controls grouped at the end.
3. Primary WordPress navigation sits in a dedicated row or clearly separated region so menu labels do not compete with the search and customer utilities.
4. Each icon control has a visible label or meaningful accessible name and at least a `44×44px` interactive area where practical.
5. Search growth must be bounded so it cannot push account/cart controls outside the container.
6. Active/current navigation is visually identifiable without relying only on color.
7. No hover interaction is required to reach primary destinations.

Footer desktop structure:

- `1200px` maximum content width with `32px` outer gutters.
- Brand/support/legal statement receives sufficient reading width.
- Link groups align in intentional columns rather than six equal-weight columns.
- Policy and support links remain visible.
- Bottom line is visually separated without an oversized blank region.

## 11. Tablet layout

**Target:** `768px` through `1024px`, including the exact `1024px` transition.

1. Use the `24px` approved outer gutter.
2. Do not squeeze the full desktop menu between the logo and utilities.
3. Use the native menu toggle/panel at the point the desktop labels no longer fit; retained breakpoint behavior must be verified at `1024px` and browser resize without reload.
4. Keep logo, account, and cart immediately reachable.
5. Search becomes its own full or near-full row when necessary; do not reduce the text field below a usable width.
6. Rewards may move into the utility row or navigation panel, but access and balance behavior must remain intact and must not duplicate confusing controls.
7. Drawer/panel layering must not conflict with the side cart. Only one overlay should visually dominate at a time.

Footer tablet structure:

- Use a two-column or balanced wrapping layout with `24px` outer gutters.
- Keep legal/research text readable rather than forcing narrow multi-column paragraphs.
- Link groups wrap in complete groups; headings must not separate from their links.
- No tablet behavior may depend on the current footer's absence of tablet overrides.

## 12. Mobile layout

**Target:** `767px` and below, tested through `320px`.

1. Use the `20px` approved outer gutter.
2. First row prioritizes the logo, cart, and one native menu toggle. Account may remain as a labeled/icon control if space permits; otherwise it appears once in the opened navigation panel.
3. Search appears as a full-width second row or a clearly labeled expandable region. It must not overlap the logo, cart, menu, or side-cart drawer.
4. Do not show separate, behaviorally different desktop and mobile searches unless Elementor cannot reflow one instance safely. If two instances are unavoidable, verify that only one is focusable/rendered at each breakpoint and both use identical product-query behavior.
5. The navigation panel presents Home, Compounds / Shop, COAs, FAQ, and Contact in one clear list.
6. Avoid duplicate rewards, account, and cart controls inside the panel when the same action remains visible in the header.
7. Menu and close controls meet the touch-target baseline and have meaningful accessible names.
8. Panel content must not create a large empty area after the last item, body-level horizontal scrolling, nested scroll traps, or covered actions.
9. The open menu must not sit underneath the floating cart or side-cart layer. Closing the menu restores focus to its trigger.

Footer mobile structure:

- Use a compact, intentional stack with `20px` outer gutters.
- Prefer always-visible, tightly grouped link lists over decorative empty space.
- A native Elementor accordion may be considered only if it remains accessible and reduces height without hiding required legal/support access. Do not use ElementsKit.
- Research-use and FDA language remain visible and readable; do not truncate or conceal it.
- Avoid excessive gaps between headings and link groups.
- Copyright and support information wrap naturally without tiny text.

## 13. Search behavior decision tree

```text
User starts a search
|
+-- From Header or Shop
|   |
|   +-- Query is empty
|   |   `-- Do not submit; keep focus in the field and provide a clear accessible prompt
|   |
|   `-- Query contains text
|       |
|       +-- Submit as a WooCommerce product search (`post_type=product` or verified native equivalent)
|       |
|       +-- Products found
|       |   `-- Show bounded product results with controlled thumbnails and canonical product links
|       |
|       +-- No products found
|       |   `-- Show a product-specific no-results state and a path back to Shop
|       |
|       `-- Query fails
|           `-- Show a recoverable product-search error; never fall through to the oversized general WordPress layout
|
`-- From COA Archive
    |
    +-- Query is empty
    |   `-- Do not submit; retain the COA Archive prompt and context
    |
    `-- Query contains text
        |
        +-- Submit through Pep Select COA Archive
        |
        +-- Records found
        |   `-- Return compound/testing records through canonical `/testing/` routes
        |
        +-- No records found
        |   `-- Show the plugin-owned COA no-results state
        |
        `-- Query fails
            `-- Show the plugin-owned recoverable error without substituting product results
```

Product and COA search share a visual system, not a query or database implementation.

## 14. Search implementation decision and code boundary

### Launch-ready first choice: native tools

Use the least complex verified path in this order:

1. Inspect Elementor's native Search widget and WooCommerce search behavior on Staging.
2. If the native Elementor widget can reliably restrict header/Shop queries to WooCommerce products and route them to an isolated, properly designed product-results context, use it.
3. Otherwise use WooCommerce's native product-search form/widget with a verified `product` query and a dedicated/bounded product-results presentation.
4. Do not publish either approach if it falls through to the generic oversized-image WordPress search-results page.

Elementor/WooCommerce can own:

- Search field and submit presentation.
- Product query submission through native WooCommerce behavior.
- A product-search results template or result item when Theme Builder/query conditions can isolate it safely.
- Result thumbnails, titles, links, prices, and empty states supplied by native product context.
- Responsive styling and focus states.

Pep Select COA Archive continues to own:

- COA query interpretation.
- Compound/testing record retrieval.
- COA result states and canonical `/testing/` routes.
- Any plugin-specific search endpoint or record permissions.

Later code may be required only if native behavior cannot safely provide:

- Reliable product-only query scoping without affecting general WordPress or COA searches.
- A dedicated product-results route/template condition.
- Accessible autocomplete, suggestion ranking, keyboard navigation, request cancellation, or debouncing.
- Cross-surface analytics or other advanced search behavior.

Advanced autocomplete is excluded from the launch-critical WEB-2B path. If narrow theme-coupled routing or CSS is required, prefer the justified presentation layer. Add Site Core work only when launch-critical durable behavior is proven necessary and separately approved.

## 15. Accessibility requirements

### Header, navigation, and utilities

- One logical keyboard order matching the visual order.
- Meaningful names for logo/home, menu toggle, search, rewards, account, cart, menu close, and all icon-only controls.
- Visible `focus-visible` treatment on light and navy surfaces; do not remove browser focus without an accessible replacement.
- Minimum `44×44px` interactive target where practical, with at least `8px` separation between adjacent icon controls.
- Current-page state conveyed through more than color alone.
- Menu open/closed state exposed programmatically where the native widget supports it.
- Escape closes overlays where supported, no keyboard trap, and focus returns to the opening control.
- Skip-to-main-content behavior must remain available through the theme or be included only through an approved accessible method.
- Logo alternative text must identify Pep Select without redundant “image of” wording.

### Search

- Programmatic label or visible label; placeholder text is not the only label.
- Search purpose distinguishes products from COA/testing records.
- Submit control has a meaningful name.
- Loading, no-results, and errors are announced without stealing focus.
- Keyboard users can reach every result and return to the query.
- Result focus order follows visual order; no hover-only content.
- Long product/compound names wrap without clipping.
- Missing images retain a usable text result.

### Footer

- Link-group headings communicate structure without breaking semantic heading order.
- Link text describes its destination; avoid ambiguous “click here.”
- Legal/support text meets contrast and readable-size requirements.
- Accordion behavior, if approved, exposes expanded state, works by keyboard, and keeps required destinations discoverable.

### Motion and contrast

- Normal text targets at least `4.5:1`; larger text and essential UI graphics meet applicable contrast requirements.
- Pep Cyan is not assumed accessible on white or navy until the exact foreground/background pairing is tested.
- Interaction changes use the approved approximately `180ms` duration, no shrink, and no layout-shifting animation.
- Reduced-motion mode removes nonessential lift/movement while retaining state clarity.

## 16. Performance and Elementor-memory safeguards

- Keep ElementsKit Lite and Pro disabled. Do not import an ElementsKit widget or restore its dependency.
- Build from native Elementor containers and widgets with the smallest practical widget tree.
- Use one responsive component where possible instead of duplicate desktop/mobile trees.
- Use native Image/Site Logo, Menu, Search, Icon, Text, and Shortcode widgets rather than raw HTML/SVG/style blocks for standard controls.
- Consume WEB-2A global tokens rather than repeating local color/font CSS.
- Add no large inline style blocks, copied gradients, decorative scripts, background video, marquee, or animation library.
- Use the existing optimized logo asset; do not create or load duplicate logo files for breakpoints unless required by an approved brand variant.
- Bound result-image dimensions to avoid layout shift and oversized downloads.
- Do not load a full 20-item product grid inside a header overlay.
- Avoid live autocomplete for launch, eliminating unnecessary AJAX requests and keyboard/state complexity.
- Keep custom CSS minimal, centralized, documented, and scoped to the replacement; do not duplicate it per widget.
- Open and save replacement templates in representative editor sessions while reviewing Kinsta memory/thread events against the 256 MB per-thread limit.
- Stop implementation if the normal Elementor editor again reaches a critical error, if memory events increase materially during representative edits, or if a new add-on dependency appears.

## 17. Step-by-step implementation order

### Phase 0 — preflight and rollback capture

1. Confirm Kinsta Staging, branch, and clean worktree.
2. Confirm ElementsKit Lite and Pro remain disabled.
3. Create Kinsta backup `Before WEB-2B Global Shell Replacement`.
4. Preserve the existing WEB-1 and WEB-2A backups.
5. Export current Header #1323 and Footer #391 from Staging.
6. Record their `Entire Site` display conditions, current WordPress menu assignment, logo asset, and active URLs.
7. Record current YITH rewards shortcode/output, account destination/states, side-cart shortcode/trigger, and empty/populated cart behavior.
8. Capture desktop, tablet, and mobile screenshots of the current header, open menu, search, side cart, and footer for rollback comparison.

### Phase 1 — freeze destinations and content

1. Create a route matrix for every header/footer link.
2. Confirm Home, Shop, FAQ, Contact, account, orders, military, and policy destinations.
3. Set COAs to `/testing/` in the replacement only.
4. Mark “Track your order,” developer credit, copyright format, support details, and the shipping announcement for approval.
5. Preserve research-use, FDA, and legal text verbatim; do not rewrite it.

### Phase 2 — validate the launch search path

1. Test native Elementor product query scoping and WooCommerce Product Search behavior in an isolated draft/preview context.
2. Confirm the submitted request returns products only.
3. Confirm it can avoid the generic WordPress search-results layout.
4. Build the smallest dedicated product-result item/template necessary for WEB-2B without modifying Dark Loop #65.
5. Test empty, one, many, long-name, missing-image, and error behavior.
6. Confirm COA search continues through Pep Select COA Archive and `/testing/` without query changes.
7. Stop and request approval before adding code if the native launch path cannot meet these gates.

### Phase 3 — build the header replacement

1. Create a new Header template with no display conditions.
2. Use the existing logo through a dynamic Site URL destination.
3. Add the verified product search.
4. Add rewards using the existing YITH integration without changing its logic.
5. Add native account and side-cart controls with meaningful labels.
6. Add Elementor's native WordPress Menu widget using the current menu.
7. Configure desktop, tablet, and mobile layouts from one shared structure where practical.
8. Apply the approved WEB-2A tokens and outer gutters only inside the replacement.
9. Verify menu and side-cart overlay/z-index behavior together.

### Phase 4 — build the footer replacement

1. Create a new Footer template with no display conditions.
2. Reuse the approved logo/brand asset.
3. Rebuild link groups with native Elementor widgets and dynamic/canonical URLs.
4. Point COAs to `/testing/`.
5. Preserve research-use, FDA, support, copyright, and policy text without legal rewriting.
6. Implement desktop, tablet, and compact mobile hierarchy.
7. Mark but do not silently change outdated/uncertain labels and external credit.

### Phase 5 — isolated QA

1. Preview header and footer on Home, Shop, a product page, `/testing/`, Contact, FAQ, a policy page, Cart, Checkout, and My Account.
2. Test at `1440`, `1280`, `1024`, `768`, `480`, `430`, `390`, `375`, `360`, and `320px`.
3. Resize without reload across `1024px` and `767px`.
4. Test keyboard-only, zoom/large text, reduced motion, and common browser focus behavior.
5. Test logged out/logged in, zero/nonzero rewards, empty/populated cart, menu open, side cart open, and search result states.
6. Confirm no horizontal scroll, covered control, duplicate focus target, hard-coded environment URL, or ElementsKit widget.
7. Review Elementor editor stability and Kinsta memory/thread events.

### Phase 6 — visual approval

1. Present desktop, tablet, and mobile screenshots for Paulo's approval.
2. Resolve only WEB-2B visual decisions listed in Section 22.
3. Re-run affected responsive and accessibility checks.
4. Do not switch display conditions without explicit approval.

### Phase 7 — controlled condition switch

1. Confirm the `Before WEB-2B Global Shell Replacement` backup and exports are recoverable.
2. Remove Header #1323's `Entire Site` condition and assign it to the approved replacement in one controlled operation.
3. Immediately smoke-test public Staging navigation, search, account, rewards, and cart.
4. Restore #1323 immediately if a failure occurs.
5. After the header passes, switch Footer #391 the same way.
6. Immediately smoke-test all footer, policy, support, account-order, military, and `/testing/` links.
7. Keep #1323 and #391 inactive and unchanged as rollback templates.

### Phase 8 — closeout

1. Export the approved replacement templates and record their IDs/conditions.
2. Record every changed database-held Elementor/menu setting separately from Git files.
3. Create a post-verification Staging backup only after the replacements pass.
4. Document tests, residual risks, and exact rollback steps.
5. Confirm Live was not modified.
6. Stop before WEB-2C, WEB-2D, or any deployment work.

## 18. Acceptance criteria

### Header and navigation

- Replacement header uses native Elementor widgets and contains no ElementsKit widget/dependency required for rendering.
- Existing Pep Select logo and brand assets remain intact and link to the correct site home.
- Home, Compounds / Shop, COAs (`/testing/`), FAQ, and Contact work from desktop, tablet, and mobile navigation.
- Native WordPress Menu uses the correct assigned menu and has no unsupported mega menu or prelaunch dropdown feature.
- Logo, product search, rewards/account, and cart controls have clear hierarchy and do not overlap or become cramped.
- Account access works logged out and logged in.
- YITH rewards shows correct zero/nonzero states without changing balances or calculations.
- Empty/populated side-cart behavior and totals remain correct; the menu does not conflict with the cart drawer.
- All interactive controls have meaningful names, visible focus, logical keyboard order, and comfortable targets.
- No duplicate focusable desktop/mobile controls exist at a given width.

### Search

- Header and Shop search submit product-only queries and return WooCommerce products.
- Product searches never land on the current oversized-image general WordPress results layout.
- Product results have bounded images and readable layout for no, one, and many results, long names, and missing images.
- COA Archive search remains plugin-owned and returns compounds/testing records through `/testing/` routes.
- Header, Shop, and COA search share recognizable shape, border, type, icon, focus, and interaction language without sharing backend logic.
- Search is keyboard operable, labeled by purpose, and has accessible loading/no-results/error feedback.
- Advanced autocomplete is not required for acceptance.

### Footer

- Replacement footer uses native Elementor widgets and no ElementsKit dependency.
- COA link points to `/testing/`.
- Existing functional Shop, FAQ, Contact, account/orders, military, support, and policy destinations remain available.
- Research-use language, FDA disclaimer, support information, and legal text are not rewritten in WEB-2B.
- Desktop and tablet grouping is clear; mobile height is reduced through hierarchy and spacing rather than hidden required links.
- Footer text remains readable and links remain keyboard/touch accessible.
- Outdated or uncertain labels/destinations remain documented for approval rather than silently changed.

### Safety, performance, and rollback

- Header #1323 and Footer #391 remain recoverable and are not deleted.
- No hard-coded Kinsta, Staging, Hostinger, Peptides Divas, BioQuantum, or obsolete `/coas/` link remains in the active replacements.
- No WooCommerce, YITH, side-cart, account, checkout, order, email, or COA business logic changes.
- Representative Elementor editing remains stable within the Staging 256 MB per-thread constraint, with no repeat ElementsKit-triggered failure.
- Required width matrix, keyboard, focus, contrast, target size, reduced motion, and resize-without-reload checks pass.
- New/old template IDs, display conditions, menu assignment, test evidence, backups, and rollback steps are recorded.
- Live remains untouched.

## 19. Rollback checkpoint

Before implementation:

- Create `Before WEB-2B Global Shell Replacement`.
- Retain all existing WEB-1 and WEB-2A backups.
- Export Header #1323 and Footer #391.
- Record the exact `Entire Site` conditions and WordPress menu assignment.

Before switching conditions:

- Export both replacement templates.
- Confirm the old templates remain unchanged and recoverable.
- Record the condition-switch order and public smoke-test list.

Immediate rollback procedure:

1. Remove the failing replacement's `Entire Site` condition.
2. Restore the same condition to Header #1323 or Footer #391.
3. Clear only the necessary Staging/Elementor caches.
4. Verify public navigation, account, rewards, cart, search, `/testing/`, support, and policy links.
5. If template-condition rollback is insufficient, restore `Before WEB-2B Global Shell Replacement`.
6. Record the failure and stop; do not attempt Live changes.

The old templates are not cleanup candidates during WEB-2B. Retirement or deletion requires the later legacy-removal gate.

## 20. Explicit exclusions

- No WordPress, Elementor, plugin, configuration, export, credential, database, or Live change during this planning checkpoint.
- No direct implementation until a separate WEB-2B implementation action is approved.
- No deletion of Header #1323, Footer #391, Dark Loop #65, COA Loop #485, old `/coas/` page, or historical exports.
- No ElementsKit Lite or Pro restoration.
- No homepage, Shop archive, canonical product-card, single-product, Cart, Checkout, My Account, rewards, Contact, FAQ, About, VerifyPass, or legal-page redesign beyond the minimum global-shell/search dependency described here.
- No changes to products, prices, stock, variations, taxes, shipping, coupons, customers, orders, checkout, payment-link emails, fulfillment, rewards calculations, or side-cart calculations.
- No final marketing copy, shipping promise, legal rewrite, FDA rewrite, policy-date correction, public tracking implementation, or support promise.
- No advanced autocomplete, predictive search, fuzzy matching, recommendation engine, search analytics, mega menu, or unsupported dropdown before launch.
- No combined product-and-COA backend search.
- No Site Core design-system settings panel or speculative backend work.
- No plugin/core/dependency updates.
- No Live deployment.

## 21. Content and factual approvals separate from visual approval

The following are not visual decisions and must not be silently resolved in WEB-2B:

- Whether the current free two-day shipping/$200 announcement is operationally accurate and should remain.
- Whether “Track your order” should be renamed because it currently leads to `/my-account/orders/`.
- Whether the external developer credit remains required.
- Whether current support details, mailing address, copyright format, research-use language, and FDA disclaimer are factually/currently approved.
- Whether the military/first-responder page label and route are canonical before WEB-2H.

Until approval, preserve functional destinations and existing legal/compliance wording; omit unverified promotional claims from the new presentation rather than rewriting them.

## 22. Items requiring Paulo’s visual approval before publishing

- Final header hierarchy: logo/search/utilities arrangement and whether desktop navigation occupies its own row.
- Logo display size and clear space at desktop, tablet, and mobile widths, without altering the source asset.
- Whether the verified announcement bar is visually present; its wording requires separate operational approval.
- Desktop search width and the compact-header versus full-row search presentation at tablet/mobile widths.
- Visual treatment and order of rewards, account, cart, and menu controls, including which labels remain visible beside icons.
- Mobile navigation presentation: drawer/dropdown width, overlay, link spacing, close treatment, and placement of account/rewards access.
- Product-search results layout for WEB-2B: compact list versus bounded small-card grid, before WEB-2D defines the final product-card system.
- Footer desktop column grouping and tablet wrap order.
- Footer mobile treatment: always-visible grouped links versus a tested native Elementor accordion.
- Final focus-ring appearance on white and Pep Navy surfaces, after contrast verification.

Approval of these visual choices does not authorize final copy, business-logic changes, template-condition switching, or Live deployment.

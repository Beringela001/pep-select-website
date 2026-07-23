# Changelog

All notable changes to the Pep Select child theme are documented here.

**Live version: 0.17.0-beta.1** — deployed to production (www.pepselect.com) on 2026-07-23. Everything from 0.16.0-beta.6 onward is on Live, not Staging only: the product B4G1 and cash-back pills (both captured and restyled from YITH plugin output), the live-updating dollar cart pill with the cart hang fixed, the reworked cash-back page (3 stat cards, 4 how-it-works cards, referral code and share link via `[ywpar_referral_link user_id="auto"]`), the restyled coupon form, the removed "Cart" heading, and quantity plus add-to-cart on one row.

## 0.17.0-beta.2 - 2026-07-23

- Removed the carrier delivery-time estimate from shipping labels wherever they display. The live-rates integration appends the transit estimate to the method title itself and offers no setting to suppress it, so any parenthetical mentioning "day" or "days" is stripped from the cart and checkout rate labels and from the stored method title shown on the order-received page, account order views, and order emails. Display only: rates, costs, and stored titles are unchanged, and parentheticals that are not estimates, such as "(Signature required)", are left alone.

## 0.17.0-beta.1 - 2026-07-22

- Redesigned the on-hold order email to the storefront: navy headings, cyan pill Square button, amber exact-amount block, Plus Jakarta Sans with email-safe fallbacks, and IBM Plex Mono for the order total. Voice reset to calm and precise. The live total via `get_formatted_order_total()`, the Square link in both button and plain-text form, the "3BS Holdings LLC" descriptor, and the support address are unchanged. Table layout with inline styles throughout; no flexbox or grid.
- Added a customer completed-order email override with a shipment tracking block styled to match, showing carrier, tracking number, and a tracking button plus plain link when a URL is available. The block is omitted entirely when an order has no tracking.
- Added `inc/emails.php` with shared email tokens mirroring foundations.css, and `pepselect_child_get_order_tracking()`, which resolves tracking from the WooCommerce Shipment Tracking structure, known Easyship and generic meta keys, a scan of the order's own meta for tracking-shaped keys, and finally the fulfilment order note Easyship writes. The key it read from is emitted as an HTML comment in the email source so it can be confirmed on a real shipment, and the whole result is filterable via `pepselect_child_order_tracking`.

## 0.16.0-beta.16 - 2026-07-22

- Referral link now renders. YITH's `get_referral_link()` defaults `user_id` to 0 and only outputs when it resolves to a real user, so the shortcode is called as `[ywpar_referral_link user_id="auto"]`. Its output (`#ywpar_referral_link_sc`) is captured and restyled into a copyable field with a Copy button plus the full share link, and YITH's raw block is collapsed only once the values have been read from it.
- Removed the referral reflection diagnostic and its raw dump; the referral section now renders only when YITH returns real output.

## 0.16.0-beta.15 - 2026-07-22

- Removed all network and timer work from the cart pill. YITH injects its cart banner client-side from its own blocks bundle, so the previous refresh (re-fetching the cart page and re-parsing it) could never find the banner and only triggered the slow cart request repeatedly, making the pill appear late and then flicker. The pill now mirrors YITH's banner directly: one cheap DOM scan, coalesced to at most once per animation frame, with no fetch, no document parsing, and no timers. Live updates come from YITH re-rendering its own banner.
- When YITH's `[ywpar_referral_link]` returns nothing, the page now reads its callback from the plugin file on the install via reflection and prints the callback name, file, line range, and full source, alongside referral-related options and user meta, so the exact condition it requires can be identified rather than guessed.

## 0.16.0-beta.14 - 2026-07-22

- Referral code and share link now render from YITH's own `[ywpar_referral_link]` shortcode, which is separate from the my-points endpoint output. Its output is captured and restyled into a copyable field with a Copy button plus the full share link. If the shortcode is registered but returns nothing, its raw output is shown for diagnosis instead of being silently hidden.
- Removed the temporary debug build: the raw-output dump block and the `PEPSELECT_CASHBACK_DEBUG` constant are gone.
- Fixed the cart pill performance regression (slow appearance, then flicker and reload). Three causes: the MutationObserver triggered a full cart-page re-fetch on every DOM change during the block cart's normal render, and that request is the slow one; the refresh ran even when nothing had changed; and a refresh that read no value blanked the pill. The pill is now captured from YITH's already-rendered banner immediately with no network call, an empty read never blanks an existing value, and the server is only re-read when the cart total actually changes.

## 0.16.0-beta.13 - 2026-07-22 (TEMPORARY DEBUG BUILD)

- Added top margin to the cart cash-back pill so it no longer touches the nav bar.
- TEMPORARY: the cash back page renders a debug block above the normal layout showing YITH's unmodified my-points output three ways — a plugin/shortcode/endpoint probe, the output rendered verbatim with the theme restyle bypassed, and the raw escaped source. This exists only to identify the real referral markup and must be removed in the next build (delete the debug block in `woocommerce/myaccount/cash-back.php` and the `PEPSELECT_CASHBACK_DEBUG` constant).

## 0.16.0-beta.12 - 2026-07-22

- Cash back page now uses the site type scale instead of page-specific styling: Plus Jakarta Sans for UI, the editorial face for headings, and IBM Plex Mono for dollar amounts, matching shop, product, and checkout.
- Rewrote the four "How it works" cards: Earn on every order, Bring a friend, Spend it at checkout, One balance.
- Removed YITH's points/redeem summary box (duplicated the balance cards) and its orphaned Points history / Manage Points tab navigation, whose panels are inlined as sections. Both are dropped server-side, with a CSS guard.
- The referral slot no longer matches YITH's points summary. YITH Points & Rewards ships no referral code or share link, so the referral section only renders if a referral plugin outputs into the account endpoint; nothing is fabricated.
- Fixed the cart pill reverting to YITH's raw trophy banner: the observer added in beta.10 only scheduled a server refresh on totals changes and never re-ran the local capture, so a banner injected after first paint was missed. It now re-captures locally on any foreign DOM change and still re-reads from the server when totals change.

## 0.16.0-beta.11 - 2026-07-22

- Fixed: YITH's referral code and share link were hidden by a blanket rule on `.ywpar_myaccount_entry_info`. That block is surfaced again and its code/link are captured and presented as brand-styled copyable fields with Copy buttons; if either cannot be read, YITH's own referral UI is left visible and untouched.
- Reworked the cash back page layout: three stat cards at the top (Available balance, Total earned, Total applied), "How it works" as a 2x2 grid of numbered steps (earn 3%, refer friends, apply at checkout with a $5 minimum, it stacks up), a referral section with the copyable code and share link plus three small stat cards, and cash back history at the bottom.
- Total earned and total applied are derived from YITH's own points log (positive and negative entries), using the same dollar conversion as the balance card.
- The Manage Points convert-to-code form is folded into a "Turn your balance into a code" section instead of a separate tab. YITH's rendered output is split into slots and re-emitted verbatim, so all logic, nonces, and code generation stay with YITH.

## 0.16.0-beta.10 - 2026-07-22

- Removed the "Cart" page heading via Hello Elementor's page-title filter, with a CSS fallback.
- Cash-back pill on the cart is now larger and horizontally centered above the cart, in the same cyan treatment.
- Cash-back pill now updates live when cart totals change (coupon applied or removed, quantity changed). On each change the cart is re-requested and YITH's freshly rendered value is re-captured; the amount is never computed in the theme, so YITH's own rules (such as no points on the coupon-discounted amount) continue to hold.
- Restyled the "Add coupons" form: the Apply button was rendering with pink text and border, and is now a secondary-weight cyan pill button (cyan outline, filling cyan on hover) matching Proceed to Checkout, with the coupon input matching the theme's field styling.

## 0.16.0-beta.9 - 2026-07-22

- Restyled YITH Points & Rewards' cart/checkout points banner into the cyan cash-back pill. Because the cart is a client-rendered WooCommerce block cart, this uses a browser script (`assets/js/cart-rewards.js`) that reads YITH's own rendered "you will earn N Points" text (plugin stays the source of truth), drops the trophy, converts points to dollars to match the cash-back framing site-wide (100 points = $1.00, so 2280 points = $22.80), and applies the cash-back pill styling. Keyed on the rendered text, not a plugin class, so it is version-agnostic and a no-op when the banner is absent (e.g. logged-out). Enqueued on cart and checkout.

## 0.16.0-beta.8 - 2026-07-22

- Quantity input and Add to cart now sit on one row (desktop and mobile): quantity fixed at ~64px, the button fills the remaining width, both the same height and vertically aligned. The empty space below is left untouched for the later current-batch display.

## 0.16.0-beta.7 - 2026-07-22

- Reworked the product promotion pills to capture and restyle plugin-rendered output instead of recomputing from plugin internals (fixes the beta.6 regression where the cash-back pill vanished). An output buffer around the single-product summary lifts YITH Points & Rewards' own earn message (`p.ywpar_earn_points`) into the cyan cash-back pill, and YITH Dynamic Pricing's rule note (`.ywdpd-notices-wrapper`) into the amber B4G1 pill, then drops both into one flex row above the price. The plugins remain the single source of truth; nothing is computed from their APIs.
- Removed the beta.6 earn-rate computation, the `PEPSELECT_CASHBACK_DOLLARS_PER_POINT` constant in this file, and the native-message suppression filters/CSS that caused the regression. YITH's earn message now also styles in place as a cyan pill fallback if it is ever not captured.
- Activated the B4G1 pill: it now renders when YITH Dynamic Pricing prints a rule note for the product, showing the plugin's own note text ("Buy 4 get 1 free") and hiding the raw gray note. `pepselect_product_has_b4g1()` now takes the detected state and stays filterable. The quantity-discount table wrapper and the stock line are left untouched.

## 0.16.0-beta.6 - 2026-07-22

- Replaced the native YITH points message on the single compound page with a coded cash-back pill that reads the earn rate live from Points & Rewards (global rate or any per-product/category override), converts it to an effective percentage against price, and hides itself when Points & Rewards is inactive or the product earns nothing. The pill keeps the theme's existing cyan treatment; the native YITH message is suppressed via filter and CSS to avoid duplication.
- Built the Buy 4, Get 1 Free pill (gift icon, "Buy 4, get the 5th free") in the theme's amber treatment, matching the on-hold email. It is gated behind `pepselect_product_has_b4g1()`, which returns false until YITH Dynamic Pricing is installed, so it renders nowhere for now.
- Both pills sit in a wrapping flex row hooked into `woocommerce_single_product_summary` above the pricing, B4G1 first when present and cash-back second. The row is only emitted when at least one pill qualifies, so a lone cash-back pill leaves no empty gap.

## 0.8.0-beta.8 - 2026-07-18

- Rewrote Research context for nine compounds from a fresh sourced research pass, tying each bullet to the area of genuine research interest (skin and pigmentation, appetite and body-weight biology, wound healing, exercise capacity, mitochondrial energy) with its underlying study, while holding the mechanism-only, no-human-use register. PT-141, TB-500, and BPC-157 keep their prior bullets pending a tighter source pass, since verifiable primary studies were not confirmed for them.
- Moved the CAS number into the top of the View the sources disclosure, above the citations, so the legitimacy signal stays available without cluttering the main view.

## 0.8.0-beta.7 - 2026-07-18

- Removed the Specifications block (CAS, Formula, Form) from the compound description per direction; the description now flows from the summary paragraph into Research context.
- Rewrote all twelve compounds' Research context bullets into plainer, curiosity-driven language pointing toward the area of research interest, while holding the mechanism-only, no-human-use register. GHK-Cu's third bullet moved from MMP/TIMP matrix regulation to its antioxidant and repair-signaling activity. The four widely known human pharmaceuticals stay most conservative.
- Research context now renders full width in a balanced two-column list on desktop, single column on mobile.

## 0.8.0-beta.6 - 2026-07-18

- Forced the add to cart button to brand cyan (navy hover) over the plugin/Elementor purple default that was overriding it, across every state.
- Locked the View the sources toggle against the Elementor global button hover that turned it magenta.
- Image card and buy card now stretch to equal height, with the product image centered in its card.
- Related "More from the selection" cards rendered smaller than primary shop cards so they read as secondary content.
- Related products become a horizontal snap carousel on mobile, matching the homepage, with compact cards.

## 0.8.0-beta.5 - 2026-07-18

- Split the hero into two separate cards: a compact image card (smaller, contained) and a buy card holding the compound identity, pricing, and add to cart.
- Compound title is now large Georgia serif matching the COA hero treatment; applies to every compound.
- Add to cart recolored to brand cyan (navy on hover), replacing the default purple.
- Removed the short description above the price and the SKU/category/tag meta below add to cart.
- Rewards message restyled as a standalone pill above the pricing.
- Removed the duplicate testing-history heading: the COA carousel shortcode prints its own styled heading, so the theme no longer prints a second one above it.
- Image lightbox: clicking the image or expand control opens a floating, appropriately sized image over a dimmed backdrop (notify-dialog language) instead of the hover zoom.
- Related section eyebrow kept as Keep exploring; heading changed from You may also like to More from the selection. Related cards use the shared front-page card component and stylesheet so they match exactly.
- Nudged the COA carousel cards toward a more vertical proportion with less dead space.

## 0.8.0-beta.4 - 2026-07-18

- Redesigned the single compound page in the COA card layout: full-width soft band background, a breadcrumb, a hero card pairing the gallery (sized, contained, no longer oversized) with the buy area, the description in its own card, and COA-consistent section headings (cyan eyebrow over a serif h2) for Independent testing history and a You may also like related grid capped at three. The coded template now renders every section directly rather than through hooks, keeping the layout compact and card-based to reduce scrolling.
- All commerce renderers remain native WooCommerce (quantity discounts, add to cart, points, gallery); the COA history carousel keeps its shortcode in the testing-history card.

## 0.8.0-beta.3 - 2026-07-18

- Fixed the compound description block rendering inside the narrow product summary column (crushing the text into a strip over the price): the block now renders in a full-width zone below the summary, and related products and up-sells were re-sequenced to follow it.
- Restored the COA history carousel: it was placed by the legacy Elementor template that the coded template replaced, so it is now rendered explicitly via the [pepselect_product_coa_carousel] shortcode in its original position below the compound block. Plugin untouched.

## 0.8.0-beta.2 - 2026-07-18

- Fixed the coded compound page not appearing: the legacy Elementor Theme Builder single-product template was rendering these pages and never firing WooCommerce hooks, so the coded block had nothing to attach to. The theme now seizes the single-product template via template_include at the same late priority used by the archive and homepage, runs WooCommerce native single-product output (all commerce renderers fire), and declines Elementor Theme Builder override for the single location.

## 0.8.0-beta.1 - 2026-07-18

Milestone M4: single compound page (WEB-2E). Archive M3 is final at 0.7.0-beta.9.

- Added the coded compound description block on single product pages, replacing the default WooCommerce tabbed description. Renders the approved hybrid-register content: mechanism-only description with inline cyan reference superscripts, a Specifications table (CAS, Formula, Form), Research context bullets, a collapsed "View the sources" disclosure, and locked Intended Use and FDA disclaimer blocks. Products without a library entry fall back to the standard description with the legal blocks still enforced.
- Added the approved twelve-compound content library (descriptions, specifications, research context, and sources) keyed by normalized compound name. Three entries carry data flags for manual resolution: TB-500 specifications pending the fragment COA, and SS-31/GHK-Cu source DOIs pending verification.
- All commerce renderers are preserved untouched: add to cart, quantity discounts, points messaging, verification, and the product gallery keep their WooCommerce structure. The COA history carousel keeps its position through the plugin's existing shortcode after the product summary.

## 0.7.0-beta.9 - 2026-07-18

- Confirmation copy revised: "Once [compound] comes back in stock, we will notify you at [email]".

## 0.7.0-beta.8 - 2026-07-18

- Simplified card status bands to a single state: every pending batch, at the vendor or at the laboratory, now reads "Restocking Soon" on the calm cyan tone; the finer distinction belongs to the product page. 
- Versioned the status-map cache key with the theme version so label and mapping changes take effect immediately on release instead of waiting out the transient (the cause of "More coming" lingering after the beta.7 install).

## 0.7.0-beta.7 - 2026-07-18

- Rebalanced the card ratio: shorter media box, larger typography. Desktop titles at 30px serif, prices at 26px, stock status at 16px semibold, larger strength pills; mobile titles at 22px and prices at 21px with buttons unchanged.
- Reordered the card body per direction: strength pill, compound name, status band, availability, price, action. Status band sits between name and availability, availability sits directly above price, with a consistent spacing rhythm and recalculated symmetry slots.
- Renamed the incoming-stock band from "More coming" to "Restocking Soon".
- Guaranteed identical card rendering on every page: all card typography now carries component-scoped specificity so page-level heading and paragraph rules (the source of the homepage/shop mismatch) can no longer restyle it, and the small-viewport card scale moved into the shared card stylesheet.

## 0.7.0-beta.6 - 2026-07-18

- Added a clear confirmation state to the notify dialog: on the plugin's own success event, the form swaps to a "You're all set." view echoing the subscriber's email, with a Continue shopping action leading to the product page. Errors keep the form visible with the plugin's inline message.

## 0.7.0-beta.5 - 2026-07-18

- Added the theme-owned Back In Stock Notifier form template through the plugin's documented override mechanism (theme folder back-in-stock-notifier-for-woocommerce/default-form.php). All functional plumbing from the original is preserved: the field classes its JavaScript binds to, hidden product, variation, and security fields, the response container, and every extension hook, including the ones that render the consent row. Bootstrap presentation is replaced with design-system markup.
- Added form styling that loads with the coded shell, dressing the form everywhere it renders: the archive notify dialog now, product pages ahead of milestone M4. The plugin Status page should now report the subscribe form template as loaded from theme.

## 0.7.0-beta.4 - 2026-07-18

- Rebuilt the notify experience as the originally envisioned enlarged card: the notify action opens a centered dialog carrying the product image, strength, name, an invitation line, and the waitlist form, over a dimmed backdrop. Native dialog semantics provide the focus trap and Escape handling; backdrop click and the close control dismiss; browsers without dialog support fall through to the product page.
- Removed the in-card overlay panel and its styles; the plugin-skeleton neutralization moved into the dialog scope.

## 0.7.0-beta.3 - 2026-07-18

- Fixed notify panels rendering open and refusing to close: the panel display rule now yields to the hidden attribute, so panels start closed and the close control works.
- Hardened the panel close button on all interactive states against retained Elementor global button styles.
- Neutralized the waitlist plugin's unstyled Bootstrap-panel skeleton inside the card panel with scoped overrides (duplicate heading hidden, fields and consent row dressed in design tokens) pending the proper theme template override for the form.

## 0.7.0-beta.2 - 2026-07-18

- Archive heading grew into the editorial treatment: larger scale with "the standard." carried in the cyan serif accent, wider heading block, and a larger lead.
- The full catalog now loads on one page; pagination removed.
- Grid is three columns on desktop and two on tablet and mobile.
- Cards align symmetrically across the row: uniform title and status slots keep prices and actions on shared horizontal lines whether or not a card carries a status band; actions are slimmer at a fixed 44px with the shortened "Notify when available" label on one line.
- Notify now opens an in-place panel over the card carrying the waitlist plugin's real subscribe form for that product (rendered via its shortcode, submitted through the plugin's own handler); Escape or the close control dismisses, one panel open at a time, and the action falls back to the product page if the plugin is ever absent.

## 0.7.0-beta.1 - 2026-07-18

Milestone M3: compounds archive (WEB-2D). Footer M2 is final at 0.6.0-beta.3.

- Added the coded compounds archive template serving the shop page, product taxonomies, and product searches through a late template_include route, replacing the legacy Elementor loop and archive templates on these views. Product searches now honor the search term, show a result count, and offer a browse-everything path when nothing matches. Twelve products per page with design-system pagination.
- Archive heading carries the approved copy: "Compounds / Selection is the standard. / Pep Select carries what passes our review and nothing else. The details sit on every card."
- Rebuilt the shared compound card with the approved editorial typography: serif title, strength pill, "Available" and "Out of Stock" stock language, serif price, and an outlined Learn more action. Out-of-stock products swap the action for "Notify me when available", linking to the product page's back-in-stock form. The homepage grid uses the same component, and card styles moved to a shared stylesheet.
- Added a read-only bridge to the COA Archive plugin: pending batches surface on cards as status bands, with vendor stages reading "More coming" and laboratory stages reading "Waiting on lab test". Expected dates are deliberately not displayed, results are transient-cached for five minutes, and no plugin data is written.
- Order-tracking page: heading block centered, label restored to cyan and enlarged, form card centered.

## 0.6.0-beta.3 - 2026-07-18

- Redesigned the order-tracking page away from the centered competitor-style composition: the small Order Tracking label is now the page heading, followed directly by the description, in a left-aligned split layout with the form card on the right, mirroring the homepage FAQ section language. The large two-tone title is removed.

## 0.6.0-beta.2 - 2026-07-18

- Added a coded page template for /track-your-order/: centered eyebrow, navy headline with cyan serif accent, and lead copy, with the WooCommerce shortcode remaining the authoritative page content. Form fields stack full width inside the card, the submit button spans the card, and the plugin's default intro line is hidden in favor of the page lead.

## 0.6.0-beta.1 - 2026-07-18

Milestone M2: footer. Header M1 is final at 0.5.0-beta.2.

- Removed the Explore link group (All products, About us, FAQ) as duplicate navigation; the footer now carries the Support and Legal groups only, per the trust-floor rationale.
- Retargeted "Track your order" from the login-gated account orders endpoint to the guest-friendly /track-your-order/ page when it exists, with a safe fallback to the account endpoint until that page is published, so the link never 404s.
- Added design-system styling for the WooCommerce order-tracking shortcode form used by the guest tracking page.
- Compressed the mobile footer: the two remaining groups sit side by side, the FDA disclaimer drops one size step with tightened leading (content unchanged), and the bottom bar tightens. Desktop link columns cap their width and align right.
- Fixed an invalid gap-right property in the small-viewport rules (now column-gap).

## 0.5.0-beta.2 - 2026-07-18

- Added live compound search suggestions to the header: typing two or more characters queries a new read-only REST endpoint against live WooCommerce data and lists up to eight matching compounds with their strength tag, out-of-stock compounds included with a subtle note. Suggestions support mouse, touch, and full keyboard navigation (arrows, Enter, Escape) with an accessible combobox/listbox pattern; selecting one opens its product page. Enter without a selection still submits the standard search.
- Hardened the search pill radius under the header ID scope so retained Elementor global input styles can no longer flatten the rounded ends.
- Root cause recorded for the broken results page: the legacy Elementor product archive template ignores the search term and renders the full catalog; the coded compounds archive in milestone M3 replaces it.

## 0.5.0-beta.1 - 2026-07-18

Milestone M1: header. Homepage WEB-2C is complete and committed at 0.4.0-beta.8.

- Enlarged the header logo (224x72 desktop, 168x56 tablet, mobile unchanged at 144x52).
- Restyled the search as a single fully rounded pill and retired the visible magnifier submit button; the form still submits on Enter, and the submit control remains in the accessibility tree, reappearing on keyboard focus.
- Centered the search pill on the page axis with a symmetric three-column header grid so it aligns with the centered primary navigation.
- Compacted the rewards action to the star icon plus the YITH points balance; the visible "Rewards" label is removed while the accessible name remains. Logged-out visitors see the star only, linking to the rewards page.
- Verified, no change required: the coded header already forces the COAs navigation item to the canonical /testing/ route, resolving the /coas/ destination bug recorded in the COA Archive plugin handoff.

## 0.4.0-beta.8 - 2026-07-18

- Allowed the coded shell to render inside the WordPress Customizer preview so Homepage Hero controls preview against the real homepage; the Elementor editor exclusion and the admin legacy-shell parameter are unchanged.
- Rebuilt the hero as a light composition: the family image bleeds to the section edge and dissolves behind the copy through a gradient mask, with navy headline text, cyan serif accent, opened serif leading, and navy button variants. Removed the now-obsolete frame corner control from the Customizer; image, fit, position, and zoom controls remain.
- Applied approved round-3 copy: hero lead rewritten without the em dash, "Explore Our Selection" CTA, "The Current Selection / A short list, on purpose." featured heading set, Direction C "Everyone has a COA." Why-section set with items Selected first, Filed to the batch, and Nothing rushed at you, and the "records stay online forever" policy stated as approved.
- Replaced the homepage FAQ with the five-question set under "The questions we hear most.", including verified shipping facts, the Square payment-link flow, and the damaged-or-incorrect-order policy summarized from the published Refund and Shipping Policy.
- Placed the Why Pep Select section on the soft cyan band to restore the alternating section rhythm.
- Fixed non-concentric corners: card and editorial photos now carry an inner radius parallel to their rounded containers, media boxes lock to a 4:5 aspect so cover cropping stays negligible, and the editorial visual uses uniform feature-radius corners.

## 0.4.0-beta.7 - 2026-07-17

- Added a Homepage Hero section to the WordPress Customizer with an image picker and live-preview slider controls for horizontal position, vertical position, zoom (100-160%), frame corner roundness (0-48px), and an image-fit choice.
- Saved values are stored as theme mods, bounded and sanitized server-side, and rendered as CSS custom properties attached to the homepage stylesheet; templates remain untouched and the parent-theme rollback stays clean.
- A Customizer-chosen image takes precedence over the coded default candidates; clearing the control returns to the approved PS-laying_fam family image chain.
- Slider changes render instantly in the Customizer preview through a postMessage bridge; choosing a different image refreshes the preview.

## 0.4.0-beta.6 - 2026-07-17

- Removed the hero micro-proof line and rebalanced the hero CTA spacing.
- Pointed the hero at the approved PS-laying_fam family image, resolved through the current environment's uploads URL with ordered fallbacks (scaled file, original file, previous approved image, branded panel).
- Made all four hero-frame corners the uniform feature radius, retiring the asymmetric corner accent.
- Added documented hero image framing controls to foundations.css (--pep-hero-image-fit, --pep-hero-image-position, --pep-hero-image-zoom) so the image can be reframed inside the rounded frame without template changes; overrides also work from the WordPress Customizer Additional CSS panel.

## 0.4.0-beta.5 - 2026-07-17

- Replaced the hero product composition with a single approved family image resolved from the Media Library by stored file path, with a branded image-ready fallback; removed the floating product tiles and the direct-purchase product card.
- Vertically centered the hero CTA row with equal breathing room between the lead paragraph and the micro-proof line.
- Removed the confidence strip, batch identity, Quality Archive, and final CTA sections from the homepage render; template files remain in the repository for the legacy-retirement register. The homepage now flows hero, featured compounds, Why Pep Select, FAQ.
- Added a single "Open the Quality Archive" action to the Why Pep Select section so the archive keeps one clear mid-page route after the section consolidation.
- Rebuilt featured-compound selection: in-stock priority compounds fill slots in order (GLP-3 R, GLP-2 T, GHK-CU, NAD+, TB-500, BPC-157), and remaining slots rotate daily through the rest of the eligible catalog with a date-seeded shuffle that stays cache-stable within a day.
- Added a strength badge to the storefront product card sourced from the product_tag taxonomy, mirroring the legacy loop card; products without a strength tag render no badge.
- Removed the visible "Catalog image" caption from the Why Pep Select photo while keeping accessible alt text.
- Restyled the FAQ as individual bordered cards and declared explicit palette backgrounds and colors on every accordion button state so retained Elementor global button styles can no longer bleed an off-palette highlight into the open item.
- Tightened the homepage section rhythm across desktop, tablet, and mobile breakpoints.

## 0.4.0-beta.4 - 2026-07-17

- Fixed hero product photos rendering as hard white rectangles on the dark stage: opaque catalog photography now displays as intentional rounded white tiles with a border and elevation instead of the transparent-cutout treatment the previous CSS assumed.
- Hid the third stacked hero photo, whose opaque background merged with the floating product card into a single malformed white surface, and retuned the second tile (including the two-product variant) so the card zone stays clear.
- Separated the floating hero product card from adjacent white tiles with the standard border token and a slightly stronger shadow, and softened the stage ground blur now that tiles carry their own elevation.
- Applied the same rounded-tile treatment to the batch identity photo inside the dark composition and corrected the image-fallback text color for contrast on the new white surface.
- No template, copy, markup, or data changes; all edits are scoped to `assets/css/homepage.css`.

## 0.4.0-beta.3 - 2026-07-16

- Retained the beta.2 homepage architecture, private preview gate, WooCommerce product selection, and COA Archive boundaries.
- Reframed the hero around the approved behind-the-label idea using the evidence-safe fallback paragraph and conversational archive CTA.
- Strengthened the existing dynamic hero composition and featured-product cards without adding dependencies or template overrides.
- Reduced repeated technical language across the supporting sections while preserving the verified FAQ and identity labels.

## 0.4.0-beta.2 - 2026-07-16

- Replaced the visually rejected compliance-led homepage preview with a product-first storefront while preserving the administrator-only front-page gate.
- Added a dark product hero using live WooCommerce images, prices, stock states, and links without hard-coded product data.
- Moved four dynamically selected compounds near the top and redesigned the reusable homepage product card.
- Concentrated batch identity and Quality Archive guidance into two later sections without duplicating COA records, statuses, or sorting logic.
- Added a compact accessible FAQ using supported existing content and omitted the obsolete order-link item.
- Preserved the coded header and footer, component isolation, responsive behavior, reduced motion, and WooCommerce/COA ownership boundaries.

## 0.4.0-beta.1 - 2026-07-16

- Added the coded eight-section WEB-2C homepage behind the administrator-only `?pepselect_home_preview=1` front-page gate.
- Preserved the existing Elementor homepage for normal and unauthorized requests and retained the coded WEB-2B shell.
- Added WooCommerce API-based featured-product selection and a reusable homepage product-card foundation without template overrides.
- Added a safe COA Archive fallback because the stable plugin exposes no supported generic homepage-preview interface.
- Isolated coded-header logo and Rewards presentation from page-specific Elementor and WooCommerce styles.
- Added responsive homepage styles, visible focus, practical touch targets, and reduced-motion handling without JavaScript.

## 0.3.1 - 2026-07-16

- Made the approved coded header and footer the default shell on supported front-end requests while preserving all Elementor page content.
- Added the administrator-only `?pepselect_legacy_shell=1` emergency route to restore Elementor Header #1323 and Footer #391 for one uncached request.
- Excluded WordPress administration, Elementor editor, Customizer, login, REST, AJAX, cron, feed, and CLI contexts from coded-shell replacement.
- Preserved the stored Elementor templates and conditions, Hello Elementor theme rollback, WooCommerce integrations, and all business logic.

## 0.3.0 - 2026-07-16

- Added the first coded footer behind administrator-only `pepselect_footer_preview` and combined `pepselect_shell_preview` request flags.
- Preserved current footer branding, research-use statements, support address, internal destinations, canonical `/testing/` COA route, and exact published FDA disclaimer through environment-neutral WordPress and WooCommerce URL helpers.
- Added responsive dark footer presentation with semantic link groups, visible focus, practical mobile targets, compact mobile hierarchy, and reduced-motion support.
- Preserved the independent coded-header preview, public Elementor Header #1323/Footer #391, all Elementor conditions, WooCommerce behavior, and business logic.

## 0.2.3 - 2026-07-16

- Increased desktop logo presence, reduced desktop search width, and compacted the desktop Rewards control without changing header height or the `1200px` inner-width foundation.
- Made the mobile Rewards link route-aware and removed its unconditional active treatment.
- Separated non-current mobile hover styling from the current-page cyan accent and dark background while preserving visible keyboard focus.
- Preserved version `0.2.2` mobile structure, search, controls, tap targets, navigation behavior, reduced motion, and administrator-only preview isolation.

## 0.2.2 - 2026-07-16

- Refined only the coded header's mobile layout at widths up to `767px`.
- Increased announcement readability and logo size, retained three 44px action controls, and expanded product search to a full-width 48px row.
- Kept Rewards out of the mobile icon row and clearly labeled it inside the accessible collapsible navigation.
- Preserved desktop/tablet styling, preview restrictions, navigation JavaScript, WooCommerce behavior, and Elementor conditions.

## 0.2.1 - 2026-07-16

- Rendered the current Pep Select logo from WordPress Media Library attachment `595`, preferring a configured WordPress Custom Logo and retaining a site-name fallback.
- Kept YITH as the rewards-balance source of truth through its documented `[yith_ywpar_points]` shortcode, with a label-only fallback when no supported output is available.
- Preserved the administrator-only preview boundary and ordinary Elementor Header #1323 behavior.

## 0.2.0 - 2026-07-16

- Added a coded announcement, logo, product search, rewards/account/cart controls, and five-link primary navigation.
- Restricted the coded header to logged-in administrators using `?pepselect_header_preview=1`.
- Added preview-only CSS and dependency-free mobile navigation JavaScript with Escape and responsive reset behavior.
- Reused the confirmed YITH points and Xootix side-cart shortcodes when registered, with safe account/cart fallbacks.
- Preserved ordinary Elementor Header #1323 requests, Footer #391, Elementor conditions, WooCommerce templates, and all business logic.

## 0.1.1 - 2026-07-16

- Rebuilt the distributable archive with portable forward-slash ZIP entry paths.
- Corrected the WordPress installation failure caused by Windows backslashes in the version 0.1.0 archive entries.
- Changed no theme behavior, templates, integrations, or design tokens.

## 0.1.0 - 2026-07-16

- Added the Hello Elementor child-theme metadata and guarded bootstrap.
- Added front-end parent, child, and foundation stylesheet loading with file-version cache busting.
- Added approved WEB-2A CSS design tokens and reduced-motion support.
- Added package, scope, screenshot, and future-directory documentation.
- Added no templates, scripts, external dependencies, business logic, or WooCommerce overrides.

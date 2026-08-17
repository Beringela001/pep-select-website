# Handoff — Codex Checkout, Cart, and Side-Cart Work Through 0.20.0-beta.50

Date: 2026-08-17  
Implementation window: 2026-08-12  
Repository: `C:\Users\paulo\Documents\Pep Select Website`  
Branch used: `codex/checkout-panel-architecture`

## 1. Scope and endpoint

This document covers the work completed after the checkout-panel architecture conversation was restarted from the existing `0.20.0-beta.33` handoff. The first new functional release in this work was beta.34; the last beta was beta.50.

The checkout was then promoted to stable `0.20.0`. That stable closeout is recorded separately in §8 because it contains six small visual/interaction fixes made immediately after beta.50.

This is a historical checkout milestone handoff. The repository has since advanced to later SEO/homepage versions; its current `style.css` version must not be changed back to 0.20 when using this document.

Detailed hook order, validation flow, YITH integration, CSS-specificity traps, and panel DOM architecture remain in `HANDOFF-checkout-panel-architecture.md`. Read that document before modifying checkout code.

## 2. Outcome

The milestone produced the approved desktop checkout panel and rebuilt the responsive checkout into a deliberate mobile/tablet flow without replacing WooCommerce, Fluid Checkout, YITH Points and Rewards, Xootix Side Cart, shipping, tax, coupon, payment, or order logic.

The final experience at beta.50 was:

- Desktop: a narrower, continuous white customer-details card on the left and the approved 420px order-summary panel on the right.
- Mobile and tablet through 1199px: customer details first, followed by one fully expanded order card containing products, discounts, cash back, BAC water, totals, payment instructions, and the order button.
- Cart: no duplicate YITH redemption banner; the Pep Select cash-back pill remains; the coupon input is permanently visible.
- Side cart: compact BAC water offer with no coupon drawer.
- Cash back: one clean amount field, zero/reset state, Max, Apply, applied pill, removal recovery, and no browser leave-page prompt.

## 3. Preserve / Refine / Replace / Remove decisions

| Decision | What it means |
|---|---|
| Preserve | WooCommerce products, cart, totals, taxes, shipping, coupons, checkout validation, orders, and emails; Fluid Checkout step structure; YITH balance/conversion/nonces/endpoints; Xootix side-cart behavior; Square email-payment workflow; the completed right-panel design. |
| Refine | Checkout presentation, field grouping, responsive order, cash-back controls, BAC upsell presentation, selected shipping state, link/focus styles, and spacing. |
| Replace | Only fragile presentation wrappers and mobile placement behavior. The real WooCommerce `#order_review` node is moved on narrow layouts; it is never cloned or reimplemented. |
| Remove | Redundant minimum copy, Fluid quantity stepper in the summary, mobile top cart dropdown after safe relocation, visible country rows, YITH date of birth, duplicate cart redemption banner, side-cart promo controls, phone helper, and outdated field labels. |

Business calculations stayed with their owning plugins. No price, stock, reward rate, coupon rule, tax calculation, shipping calculation, payment behavior, order state, customer record, or product relationship was moved into the child theme.

## 4. Release-by-release record

### Inherited baseline — beta.33

Beta.33 was the handoff starting point, not new Codex scope. It had already closed the computed-style comparison failures on the order button and line-item containment and established the exact 18px line-item-to-discount-card gap.

### beta.34 — order-summary line item

Commits: `e850dd5`, verification `4ccf99a`

- Removed Fluid Checkout's surviving editable quantity stepper at the required ID-level specificity.
- Kept the approved quiet `Qty 2` and `Remove` presentation; quantity editing remains available through `Edit cart`.
- Changed the visible tax label to `Sales tax (WA)` format using the customer's live two-letter shipping or billing state.
- Did not change the calculated tax amount.
- Confirmed the installed beta.33/beta.34 line-item gap was exactly 18px.

### beta.35 — cash-back interaction rebuild

Commit: `334818a`

- Changed the heading to `REDEEM CASH BACK (YOU HAVE $X.XX)` and removed the duplicated `— MIN $5.00` phrase.
- Kept `Minimum redemption is $5.00.` as the single minimum notice.
- Changed the amount control to start at `0` and select its full value on focus.
- Made Max fill the live available amount without applying, submitting, refreshing, or navigating.
- Removed the double-border/doubled-input appearance.
- Prevented YITH's native submit fallback from triggering Chrome's leave-site warning while retaining YITH's bound AJAX click.
- Added immediate busy feedback and faster checkout synchronization, with only a 120ms fallback.
- Added a vertical applied-pill wrapper so valid coupons and cash back can stack one pill per line.
- Made cash-back removal restore the redemption card at `0` with the refreshed available balance.

### beta.36 — cash-back removal recovery

Commit: `afb22de`

- Rebuilt the zeroed redemption card even when YITH omitted its native redemption form after a Fluid Checkout refresh.
- Reused YITH's own last server-rendered maximum, conversion method, and nonce.
- Added a fallback call to YITH's native `ywpar_apply_points` endpoint with its exact plugin payload when no native Apply control remained.
- Added an immediate processing state to applied-pill removal.

### beta.37 — cash-back state persistence

Commits: `c132f3d`, verification `766ab6b`

- Persisted the last server-rendered YITH redemption configuration in session storage so removal recovery survives a full checkout fragment refresh.
- Stored only YITH's points maximum, dollar maximum, rate method, and WordPress nonce.
- Refreshed that cached state whenever YITH rendered a new native form.
- Verified two complete Max → Apply → Remove cycles, including the cached-state fallback path.

### beta.38 — expanded mobile checkout flow

Commit: `ed89747`

- Changed mobile checkout to customer details first and one fully expanded order card second.
- Moved Fluid Checkout's real `#order_review` node from its narrow-screen collapsible container into the permanent bottom order card.
- Did not clone the node, preserving WooCommerce, YITH, coupon, cart-fragment, form, and event behavior.
- Hid the top `Your cart — X items` dropdown only after the real order review was confirmed in its destination.
- Left Fluid's original dropdown visible as a fail-safe if the move could not complete.
- Reapplied placement after checkout updates, fragment replacements, DOM mutations, resizes, and responsive changes.

### beta.39 — correct Fluid narrow-layout range

Commits: `bc0c8fe`, verification `a5a1566`

- Extended the expanded mobile order flow through Fluid Checkout's actual narrow-layout range of 0–999px.
- Accounted for Fluid continuing to relocate the order table at tablet widths even when its body class still reported a two-column layout.
- Verified the sequence and containment at 390, 440, 768, and 900px.

### beta.40 — iPad Pro stacking

Commit: `67a1c0b`

- Extended the stacked checkout flow through 1199px.
- Prevented Fluid's 1024px layout from squeezing the order panel into roughly 242px of usable content.
- Kept the normal two-column desktop layout beginning at 1200px, where the order panel can retain its intended 420px width.

### beta.41 — iPad column alignment

Commits: `a36ab48`, verification `a28b2ca`

- Removed Fluid's surviving float and 450px width from `.fc-inside` and `.fc-checkout-steps` only between 1000 and 1199px.
- Aligned both the checkout fields and following order card inside the same centered 800px container.
- Verified 1024×1366, 1199px, and live 1024 → 1200 → 1024 transitions with no reload.

### beta.42 — checkout field cleanup

Commits: `98a277d`, verification `45bf630`

- Hid billing and shipping Country/Region rows while keeping both underlying values present and submitted as `US`.
- Removed YITH's optional date-of-birth field before Fluid rendered it, plus a CSS fallback for cached markup.
- Replaced remaining WooCommerce/Fluid pink or red checkout links with Pep Select cyan and navy hover/focus colors.
- Verified all three changes across 390, 440, 768, 900, 1024, 1199, 1200, and 1440px.

### beta.43 — compact BAC offer restored in the side cart

Commits: `88c59f1`, verification `b32a421`

- Fixed the root cause of the giant side-cart BAC image: the shared markup and behavior were correct, but its styles loaded only on checkout/cart pages.
- Added a dedicated side-cart stylesheet loaded wherever the Xootix drawer and BAC offer are available.
- Restored the compact white bordered card, thumbnail, branded switch, concise copy, and one-line price on every storefront page.
- Verified live add/remove behavior without a reload.

### beta.44 — BAC card spacing and sizing

Commits: `727b068`, verification `82b68ff`

- Added an exact 12px gap between the side-cart BAC card and the View Cart/Checkout buttons.
- Increased desktop card padding to 18px and the thumbnail to 56px.
- Kept 16px phone padding so the switch, label, and price fit in the 390px drawer.
- Allowed natural wrapping at 360/375px instead of shrinking or overflowing.

### beta.45 — shared BAC image and price cleanup

Commits: `def9983`, verification `f1e96dc`

- Increased the shared BAC thumbnail to 72×72px on checkout and side cart.
- Removed the visible `30mL –` prefix everywhere the shared BAC card appears.
- Kept the live WooCommerce price (`$19.99` at verification time).
- Kept the full product name in the checkbox's accessible label.
- Verified checkout and side-cart containment, add/remove behavior, and responsive widths.

### beta.46 — cart rewards and coupon presentation

Commits: `4adaacb`, verification `95ce751`

- Hid only YITH's duplicate redemption banner above the Cart page.
- Preserved the separate Pep Select cash-back earnings pill.
- Kept WooCommerce Blocks' existing coupon form mounted and expanded.
- Hid only the accordion handle so the coupon input and Apply button remain visible like checkout.
- Did not change coupon application or reward earning logic.

### beta.47 — remove side-cart coupon controls

Commits: `30d3ad5`, verification `6908b75`

- Hid Xootix's `Have a Promo Code?` trigger and its matching coupon slider only in the side cart.
- Removed access to the side-cart Apply Coupon heading, promo input, and Submit control.
- Preserved the full Cart coupon form and Checkout discount-code form.

### beta.48 — left checkout card redesign

Commit: `4c0090c`

- Left the approved right order panel unchanged at 420px.
- Reduced the desktop customer-details column and centered the two-column composition in a 1080px wrapper.
- Made Contact, Shipping address, Shipping, and Billing address read as one continuous white card with a soft shadow.
- Kept privacy, Research Purpose, and Acknowledgments in a separate white card with a 20px gap.
- Kept Shipping above Billing because Fluid's `Same as shipping address` dependency makes that order clearer and safer.
- Changed the email helper to: `Use the same email for checkout and payment. We’ll send your Square payment link, order confirmation, and tracking updates here.`
- Changed `Town / City` to `City`.
- Removed the phone helper `Only used for shipping-related questions.`
- Changed `Shipping method` to `Shipping`.
- Put City, State, and ZIP on one row at 600px and wider; kept them stacked on phones.
- Introduced the soft-blue selected shipping treatment.

### beta.49 — authoritative City label and selected shipping override

Commit: `f939d32`

- Set `City` through WooCommerce's locale-aware default-address filter so Fluid could not restore `Town / City` after rendering.
- Explicitly replaced Fluid's selected shipping gray background with Pep Select soft blue.

### beta.50 — selected shipping specificity and desktop centering

Commits: `092a2ac`, verification `434fae5`

- Finished the selected Shipping state through Fluid Checkout's own checked-option variables, outranking its high-specificity gray rule without changing shipping markup or behavior.
- Centered the narrower 1080px checkout wrapper inside Fluid's existing 1140px desktop container.
- Verified at 1440, 1200, 1024, 768, 440, and 390px with no horizontal overflow or checkout console errors.

## 5. Final beta.50 behavior by surface

### Desktop checkout — 1200px and wider

- Overall checkout wrapper: 1080px, centered inside Fluid's 1140px container.
- Customer-details column: 602px.
- Order-panel column: 428px.
- Actual approved right panel: 420px; its content, order, and styling were preserved.
- Left details: one continuous white Contact/Shipping/Billing card plus one separate white acknowledgments card.
- City/State/ZIP: one row.
- Selected shipping: `#E8F6FB` soft-blue fill with cyan border and selection mark.

### Tablet and mobile — 0 through 1199px

- Customer details appear first.
- The order card follows; nothing is hidden inside the top Fluid cart dropdown.
- The order card contains the real product list, discount code, applied pills, cash-back controls, BAC offer, totals, payment, and order button.
- 1000–1199px uses an 800px centered stacked container.
- Phone City/State/ZIP fields stack to full width.
- The top cart dropdown is hidden only after successful order-review placement.

### Cart

- The top duplicate YITH redemption banner is hidden.
- The Pep Select `You’ll earn $X.XX in cash back` pill remains.
- Coupon input and Apply button are always visible.

### Side cart

- No coupon trigger or coupon drawer.
- BAC offer uses the compact shared card with a 72px image, no `30mL –` prefix, live price, and a 12px button gap.
- View Cart and Checkout remain visible on tested phone widths.

## 6. Architecture and file ownership

| File | Responsibility in this milestone |
|---|---|
| `pepselect-child/assets/css/checkout.css` | Checkout/cart presentation, responsive stacking, continuous left card, selected shipping, link and form styling, BAC card presentation, order-summary refinements. |
| `pepselect-child/assets/css/header.css` | Side-cart-only suppression of Xootix coupon controls. |
| `pepselect-child/assets/css/side-cart-upsell.css` | Compact BAC offer presentation outside checkout/cart pages. |
| `pepselect-child/assets/js/checkout-redemption.js` | YITH cash-back presentation adapter, Max/Apply/removal UX, cached native configuration, applied pills, refresh recovery. |
| `pepselect-child/assets/js/checkout-mobile-order.js` | Safe relocation of the real `#order_review` node and narrow-layout fail-safe state. |
| `pepselect-child/assets/js/cart-rewards.js` | Pep Select cart earnings pill and always-expanded Woo Blocks coupon presentation. |
| `pepselect-child/inc/checkout-fields.php` | Country/DOB field removal, exact email/City/phone copy, Research Purpose and acknowledgment rendering/validation. |
| `pepselect-child/inc/checkout-upsell.php` | Shared BAC product lookup, markup, live price, toggle behavior, and conditional side-cart stylesheet loading. |
| `pepselect-child/inc/checkout.php` | Checkout hooks/enqueues, tax label, coupon/order-summary placement, responsive script loading, labels, and layout integration. |
| `pepselect-child/style.css` | Release version header only. |
| `HANDOFF-checkout-panel-architecture.md` | Detailed checkout DOM, hooks, validation, YITH behavior, CSS traps, live verification, release and rollback record. |
| `M12-5-checkout-page-45-block-content-backup.html` | Preserved source/evidence backup for the prior checkout block content. |

Presentation remains in the child theme because these changes are coupled to Fluid Checkout, the active theme, WooCommerce markup, and the Xootix drawer. YITH remains the source of truth for balances and redemption. WooCommerce remains the source of truth for cart, coupon, tax, shipping, order, and checkout data.

## 7. Verification completed through beta.50

- beta.34: installed Live; summary line item, exact 18px gap, tax label, coupon remove/reapply, reload, and checkout-fragment refresh passed.
- beta.37: installed Live; zero state, focus selection, Max, Apply, applied pill, no leave-site prompt, removal restoration, cached fallback, and second full redemption cycle passed.
- beta.39: installed Live; complete fields-then-order flow passed at 390, 440, 768, and 900px.
- beta.41: installed Live; 1024×1366 and 1199px stacked alignment plus the 1200px desktop transition passed.
- beta.42: installed Live; hidden countries retaining `US`, no birthday field, link colors, products visible, no overflow, and no checkout-script errors passed at eight widths from 390 to 1440px.
- beta.43–45: installed Live; BAC card appearance, image/price, spacing, mobile containment, and add/remove restoration passed.
- beta.46: installed Live; cart banner removal, retained cash-back pill, expanded coupon form, and 390px containment passed.
- beta.47: installed Live; side-cart coupon trigger/drawer removal while preserving Cart and Checkout coupon fields passed on desktop and 390px.
- beta.50: installed Live; continuous left card, separate acknowledgments card, exact copy, City/State/ZIP behavior, selected shipping state, preserved right-panel controls, and no overflow passed at 1440, 1200, 1024, 768, 440, and 390px.
- Browser console was clean for the changed checkout/upsell scripts during the recorded verification passes.

## 8. Stable 0.20.0 closeout after beta.50

Commits: release `acaae0c`, polish `d61aa92`, verification `4e9b5d0`

The following final fixes shipped immediately after beta.50 in stable `0.20.0`:

- Matched the continuous left card's top and bottom inner corners to the separate card at 8px.
- Made the visible left and right desktop cards start and end on the same pixels using grid stretching, without fixed heights.
- Changed the action text to `Place your order`.
- Removed Fluid's hidden 20px order-button wrapper padding and reduced the visible amber-notice-to-button gap to 33px.
- Removed Fluid's gray hover fill from unselected shipping rates while preserving the selected soft-blue state.
- Changed customer text inputs, phone, native selects, Select2, checkboxes, and radios to the same cyan border, 3px ring, and 2px accessibility outline used by the cash-back field; no black focus color remains.
- Reverified 1440, 1200, 1024, 768, 440, and 390px with all order controls present, no horizontal overflow, and no checkout console errors.

## 9. Release and rollback artifacts at milestone close

| | Release | Immediate rollback |
|---|---|---|
| File | `dist/pepselect-child-0.20.0.zip` | `dist/pepselect-child-0.20.0-beta.50.zip` |
| Version | `0.20.0` | `0.20.0-beta.50` |
| Bytes | 278,110 | 277,106 |
| SHA-256 | `5a09b0cd4a4038357affa3921bf67d0d68c40875915755600355e3102a825139` | `ba71bd394b28d76f3279f1c774be11bebf71b7e1c9da74f86b5cdd57e5b28371` |

These ZIPs already exist in `dist/`. Do not rebuild them from the repository's current HEAD because later milestones have changed the theme since 0.20.

Deployment for this milestone was a manual child-theme ZIP upload through WordPress. Nothing auto-deployed. Always verify the installed production `style.css` version rather than inferring it from Git or the changelog.

## 10. Known constraints and remaining tests

- `WELCOME10` had **Individual use only** enabled during verification. A simultaneous coupon/cash-back stack was therefore not forced on Live. The UI supports one applied pill per line, but stacking depends on WooCommerce/YITH coupon rules.
- Still owed from the checkout architecture handoff: verify that the Square amount exactly matches the discounted total when redemption is applied.
- Still owed: one complete test order with redemption applied.
- The last recorded successful end-to-end redemption order before this work was staging order `#1443` on beta.21. Do not present that as beta.50/stable end-to-end proof.
- The mobile order script depends on moving the real `#order_review` node. Preserve its fail-safe: never hide Fluid's top cart control unless the destination placement has succeeded.
- YITH binds its native Apply behavior to a click at page load. Do not replace it with `requestSubmit()` or assume a refetched form has live handlers.
- Test changes against the actually installed build. Injecting CSS into an older build cannot reveal missing markup, version-specific cascade order, stale fragments, or server-rendered hook differences.

## 11. Safe next-operator checklist

1. Read `HANDOFF-checkout-panel-architecture.md` before touching checkout hooks or CSS.
2. Confirm the exact installed theme version and take the required named backup before any approved deployment.
3. Preserve the right order panel unless a new, explicit milestone changes it.
4. Keep business calculations in WooCommerce/YITH and presentation adapters in the child theme.
5. Test logged-in and logged-out checkout, cart, and side cart as applicable.
6. Test 390, 440, 768, 1024, 1199, 1200, and 1440px when checkout geometry changes.
7. Exercise checkout fragment refreshes, coupon changes, cash-back Apply/Remove, BAC add/remove, shipping selection, and viewport transitions when the relevant files change.
8. Confirm no horizontal overflow, no hidden products/order controls, keyboard-visible focus, and a clean browser console.
9. Build installable ZIPs only when requested, place them only in `dist/`, print SHA-256, and retain the previous verified package for rollback.

## 12. Commit chain for this work

```text
0cc961c Document checkout panel architecture handoff
e850dd5 Match checkout summary line item to approved mockup
4ccf99a Record live beta.34 checkout verification
334818a Fix checkout cash back interactions
afb22de Restore cash back card after removal
c132f3d Persist cash back state across checkout refresh
766ab6b Record live beta.37 cash back verification
ed89747 Expand mobile checkout order summary
bc0c8fe Cover Fluid tablet checkout breakpoint
a5a1566 Record live beta.39 mobile verification
67a1c0b Stack checkout through iPad Pro widths
a36ab48 Align iPad checkout columns
a28b2ca Record live beta.41 iPad verification
98a277d Fix checkout country birthday and link styling
45bf630 Record live beta.42 checkout verification
88c59f1 Restore compact side cart water upsell
b32a421 Record live beta.43 side cart verification
727b068 Refine side cart upsell spacing
82b68ff Record live beta.44 side cart verification
def9983 Refine shared bac water card
f1e96dc Record live beta.45 bac card verification
4adaacb Simplify cart rewards and coupon controls
95ce751 Record live beta.46 cart verification
30d3ad5 Remove coupon controls from side cart
6908b75 Record live beta.47 side-cart verification
4c0090c Refine checkout customer details card
f939d32 Finish checkout field and shipping state styles
092a2ac Finalize checkout selected shipping treatment
434fae5 Record live beta.50 checkout verification
acaae0c Release stable checkout 0.20.0
d61aa92 Polish stable checkout interactions
4e9b5d0 Record stable 0.20.0 verification
```

The architecture handoff, changelog entries, implementation commits, and installed-build verification notes are the evidence base for this summary. Where a complete checkout order was not run, this document says so explicitly.

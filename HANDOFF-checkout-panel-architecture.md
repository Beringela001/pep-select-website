# Handoff — Pep Select Checkout Architecture

**Written:** 2026-08-12
**Theme version at handoff:** `0.20.0-beta.46` (repo + built ZIP + Live, verified 2026-08-12).
**Branch:** `codex/checkout-panel-architecture` · **Latest implementation commit:** `4adaacb`
**Theme:** `pepselect-child` (Hello Elementor child)
**Spec file:** `checkout-panel-pepselect.html` at repo root — 13,366 bytes, SHA-256 `c888fb252d994432915ec5ff803fdbd0c79ca36d16054651d1fe3bb0f990c741`

---

## 0. Read this first

The checkout is **not** a WooCommerce checkout you can style directly. Three systems own the markup, in this order of authority:

1. **Fluid Checkout PRO** — rebuilds the entire checkout into a two-column, multi-step layout. It renders the classic WooCommerce checkout, not the block checkout, even though the page is `[woocommerce_checkout]`. Most of the DOM you see is Fluid's, not Woo's.
2. **YITH WooCommerce Points and Rewards PREMIUM 4.27.0** — owns cash-back. It implements a redemption as a **coupon** (`ywpar_discount_1`).
3. **The child theme** — hooks into both.

**Critical:** large parts of Fluid are **Pro-only and their source is not public**. `.product-total`, `.fc-cart-item-actions`, `table.fc-review-order-totals-table` do not exist in the free plugin source on GitHub. When you cannot find a hook for something, that is why. The free source is worth downloading for reference:
`https://raw.githubusercontent.com/fluid-checkout/fluid-checkout/master/inc/checkout-steps.php` (~300KB, contains most action/filter registrations).

---

## 1. Page structure — what "left" and "right" actually are

```
form.checkout.woocommerce-checkout
├── .fc-wrapper
│   ├── .fc-checkout-steps                        ← THE LEFT COLUMN
│   │   ├── step: Contact
│   │   ├── step: Shipping   (shipping address, shipping method)
│   │   ├── step: Billing    (billing address)
│   │   └── step: Payment    ← THE "LEFT CARD" YOU ASKED ABOUT
│   └── .fc-sidebar
│       └── .fc-sidebar__inner
│           └── #fc-checkout-order-review.fc-checkout-order-review   ← RIGHT PANEL
│               └── .fc-checkout-order-review__inner
│                   ├── .fc-checkout-order-review__head  (h3 + Edit cart)
│                   └── #order_review.woocommerce-checkout-review-order
│                       └── table.shop_table.woocommerce-checkout-review-order-table
│                           ├── thead      (hidden)
│                           ├── tbody      ← everything is injected here
│                           └── tfoot      (hidden — see §5)
```

Both columns are inside **one** `form.checkout`. That matters: any `<form>` you nest inside it gets dropped by the HTML parser. This bit us once already (§6).

---

## 2. THE LEFT CARD — how it is built now

**The left "Payment" step no longer contains payment.** In M12-9 payment was moved to the right panel and the consent block moved into the space it vacated. The left card is Fluid's payment step, emptied and refilled.

### The hook: `fc_checkout_payment`

Fluid fires `do_action('fc_checkout_payment')` inside the payment step. Originally:

```php
add_action( 'fc_checkout_payment', 'woocommerce_checkout_payment', 20 );   // Fluid's default
```

The theme removes that and hangs three things on it instead:

| Priority | Function | File | Renders |
|---|---|---|---|
| 5 | `pepselect_child_render_consent_terms` | `inc/checkout.php:395` | `wc_get_template('checkout/terms.php')` → privacy + terms paragraph |
| 10 | `pepselect_child_research_purpose_field` | `inc/checkout-fields.php:139` | Research Purpose `<select>` |
| 20 | `pepselect_child_required_checkboxes` | `inc/checkout-fields.php:202` | The two compliance checkboxes |

The removal happens in `pepselect_child_swap_payment_and_consent()`, `inc/checkout.php:406`, hooked on `wp` priority **200** (must run *after* Fluid's own late hooks, which register on `wp` at 100):

```php
remove_action( 'fc_checkout_payment', 'woocommerce_checkout_payment', 20 );
```

### The heading

The payment substep's title ("Payment method") was orphaned once payment left. It is nulled through Fluid's own registration filter — **not** hidden in CSS:

```php
// inc/checkout.php:477
add_filter( 'fc_register_checkout_substep_args', 'pepselect_child_clear_payment_substep_title' );
// sets $args['substep_title'] = null when $args['substep_id'] === 'payment'
```

Fluid supports a null substep title (it does the same when the coupon section title is disabled), so the substep still registers and step progression is intact.

### Why the acknowledgments live here and not in the right panel

They were originally on `woocommerce_review_order_before_submit`, which renders in the **right** panel. M12-9 moved them left by changing only the two `add_action` targets in `checkout-fields.php`. **The markup, labels, validation and order meta were never touched** — this was deliberate, that stack has been rebuilt three times and is the thing an underwriter tests.

### The validation chain (do not disturb)

| Stage | Hook | File |
|---|---|---|
| Client-side | capture-phase guard on the Place Order click | `assets/js/checkout-acks.js` (enqueued `inc/checkout-fields.php:248`) |
| Server-side | `woocommerce_checkout_process` p10 ×2 | `checkout-fields.php:269, 286` |
| Persist | `woocommerce_checkout_create_order` p10/p20 | `checkout-fields.php:305, 323` |
| Admin display | `woocommerce_admin_order_data_after_billing_address` | `checkout-fields.php:390, 410` |
| Email | `woocommerce_email_order_meta_fields` | `checkout-fields.php:437` |

Field IDs: `research_purpose`, `compliance_acknowledgment`, `policy_agreement`. Error spans are `#{id}_error`.

**Verified working** (bypass POST to `/?wc-ajax=checkout` with the three fields stripped returns `result: failure` with all three messages). The client guard is deliberately **independent of Fluid** — a Fluid-dependent version silently broke inline errors in beta.13/14.

### Parent-theme conflict

`inc/checkout-fields.php:36-44` removes an older set of identically-purposed hooks (`custom_checkout_required_checkboxes`, `pepselect_research_purpose_field`, etc.) on `init` p5. If you ever see doubled acknowledgment fields, that's what this guards against.

---

## 3. THE RIGHT PANEL — how content gets in

Everything in the right panel is injected into the **tbody of the review-order table** via one WooCommerce action, ordered by priority:

```php
do_action( 'woocommerce_review_order_after_cart_contents' );   // fires inside <tbody>, after the line items
```

| Priority | Function | Renders | File |
|---|---|---|---|
| 5 | `pepselect_child_render_notices_in_summary` | WooCommerce notices (only if `wc_notice_count() > 0`) | `checkout.php:672` |
| 10 | `pepselect_child_render_coupon_in_summary` | DISCOUNT CODE card | `checkout.php:325` |
| 20 | `pepselect_child_render_applied_pills` | Applied coupon / cash-back pills | `checkout.php:693` |
| 25 | `pepselect_child_render_redeem_slot` | Empty `<tr>` the JS fills with the cash-back card | `checkout.php:~700` |
| 30 | `pepselect_child_render_bacwater_upsell_summary_row` | BAC water card | `checkout-upsell.php:160` |
| 40 | `pepselect_child_render_summary_totals` | The flat totals list | `checkout.php:753` |

Each renders a `<tr class="pepselect-summary-row ..."><td class="pepselect-panel-cell">`. **The `pepselect-panel-cell` class matters** — see §7.

Payment and Place Order render *after* the table, on a different action:

| Priority | Function | Renders |
|---|---|---|
| 4 | `pepselect_child_render_payment_heading` | `.pepselect-pay-section` wrapper + "PAYMENT" label |
| 5 | `pepselect_child_render_payment_in_summary` | `woocommerce_checkout_payment()` + closes the wrapper |
| 10 | Fluid's own | Place Order button |

```php
add_action( 'fc_place_order', 'pepselect_child_render_payment_heading', 4, 2 );
add_action( 'fc_place_order', 'pepselect_child_render_payment_in_summary', 5, 2 );
```

Both use a **run-once static guard**, not the `$is_sidebar` argument. `fc_place_order` can fire for more than one place-order instance; relying on `$is_sidebar` risked rendering **no payment box at all**, which is worse than rendering it twice.

### Line items

Fluid emits each line item's parts through its own action:

```php
fc_order_summary_cart_item_details
  10 → output_order_summary_cart_item_product_name   (Fluid)
  30 → output_order_summary_cart_item_unit_price     (Fluid)
  40 → output_order_summary_cart_item_meta_data      (Fluid)
  90 → output_order_summary_cart_item_quantity       (Fluid — REMOVED by us)
  95 → pepselect_child_render_summary_item_qty       (ours: "Qty N  Remove")
```

The stepper is removed the supported way, in `pepselect_child_swap_payment_and_consent()`:
```php
remove_action( 'fc_order_summary_cart_item_details', array( $steps, 'output_order_summary_cart_item_quantity' ), 90 );
```

The strength pill ("10MG") comes from `woocommerce_cart_item_name` (`checkout.php:238`), reusing `pepselect_child_get_product_strength_label()` from `inc/homepage-preview.php:285` — it reads the `product_tag` taxonomy. **Do not create a second resolver.**

> **History worth knowing:** the qty line used to be appended *inside* the name string by that same filter. Nested in `.cart-item__name` it had nowhere to wrap, overflowed its parent, and sat on top of the discount card (measured −69.2px). Moving it to its own hook fixed it. If you touch line items, keep qty as a **sibling**, not part of the name.

---

## 4. Cash back (YITH) — the fragile part

**Conversion: 100 points = $1.** The field POSTed to YITH is `ywpar_input_points`, in **POINTS**. Everything the customer sees is **DOLLARS**. Getting this wrong under-redeems silently — YITH clamps server-side rather than erroring, so a customer asking for $5 could redeem 5 points ($0.05) with no visible failure.

The conversion lives in exactly one function, `assets/js/checkout-redemption.js`:

```js
function dollarsToPoints( dollars, pointsMax, maxDollars ) {
    // rate DERIVED from YITH's own fields, not hardcoded:
    var rate = maxPts / maxUsd;          // ywpar_points_max / ywpar_max_discount
    var points = Math.round( amount * rate );
    if ( points > maxPts ) points = maxPts;   // always clamped
}
```

Verified on production against a 1120pt / $11.20 balance: `$5.00 → 500`, `$7.00 → 700`, `$20.00 → clamped 1120`.

### How applying works — and the trap

YITH renders a classic form `form.ywpar_apply_discounts` on `woocommerce_before_checkout_form`. The theme hides it and presents its own card, which **triggers YITH's own Apply button**:

```js
nativeButton().click();
```

**Do not replace this with `form.requestSubmit()` or `form.submit()`.** Tested on production: `requestSubmit` fires a submit event and applies **nothing** — YITH listens for a click on its button and applies over its own AJAX. A native POST is not a substitute. This exact substitution shipped in beta.25 and broke apply entirely.

**Second trap:** YITH binds its handler only to forms present at **page load**. A form recovered from a background fetch is inert — clicking its button falls through to a native POST that navigates the page and applies nothing. beta.25 did this too.

### Known unfixed behaviour

If the page loads with a redemption already applied and the customer removes it, YITH does not re-render its apply form, so the cash-back card cannot rebuild until the next page view. Restoring it without a reload needs either a YITH-bound form (only a page load produces one) or a reimplementation of YITH's private AJAX contract. Reported, not built.

### Removal

Everything applied is removed by the pill's `×`, which is deliberately WooCommerce's own control:
```html
<a href="#" class="woocommerce-remove-coupon" data-coupon="CODE">×</a>
```
Fluid has a document-level capture handler bound to `.woocommerce-remove-coupon` that does the removal. The `href` is rewritten to `#` server-side (`woocommerce_cart_totals_coupon_html`, `checkout.php:546`) so a click before the script binds cannot navigate away.

Five removal affordances exist in the raw DOM. Exactly **two** are left visible: the line item's own Remove, and the pill's ×. The other three (Fluid's `.fc-cart-item-actions`, Fluid's applied-coupon list entry, WooCommerce's `[Remove]` in the hidden tfoot) are suppressed. Fluid's applied-list **container** is deliberately kept — it is the target of an `update_order_review` fragment.

---

## 5. Totals

Fluid renders totals in **two** tables' `tfoot`. Both are suppressed; the theme renders its own flat `<div>` list instead (`pepselect_child_render_summary_totals`, `checkout.php:753`), server-side, so it re-renders with every `update_order_review` fragment and needs no client-side syncing.

Row order follows the mockup: **Discount → Subtotal → Cash back → Shipping → Tax → Total**.

> **Deliberate deviation:** the mockup shows a *post-discount* Subtotal ($143.98 from $159.98 − $16.00). The build prints WooCommerce's **real** `get_cart_subtotal()` with discounts below it. Matching the mockup literally would mean displaying a figure WooCommerce never calculates. Row order is the mockup's; values are WooCommerce's.

**Tax label:** beta.34 formats the visible custom-summary label as `Sales tax (WA)`, using the customer's live two-letter shipping state and falling back to billing state. This matches the mockup/reference without changing WooCommerce's tax-rate name, calculation, or amount.

The second, hidden totals table (`table.fc-review-order-totals-table` inside `.fc-place-order__section--main`) is **Fluid Pro** and was left alone — it is `display:none` and cannot affect layout.

---

## 6. Coupon field

Fluid renders coupons as a substep inside the payment step. The theme suppresses that and renders the same section inside the summary card:

```php
add_filter( 'fc_coupon_code_displayed_as_substep', '__return_false' );
add_filter( 'fc_coupon_code_field_initially_expanded', '__return_true' );
$coupons->output_section_coupon_codes_fields( array(), array( 'initial_state' => 'expanded' ), false );
//                                                                                            ^^^^^ suppresses the "Add coupon code" toggle
```

**Two things must be rendered alongside it or coupons half-break:**
```php
$coupons->output_coupon_codes_messages_container();   // .fc-coupon-code-messages  — where errors print
$coupons->output_substep_text_coupon_codes();         // .fc-step__substep-text-content--coupon-codes — fragment target
```
Omitting these (beta.20) meant removal reached the server but produced no loading state, no in-place refresh, and **every coupon error was discarded silently** — including individual-use conflicts.

> **Nested form warning:** the summary panel is inside `form.checkout`. Moving YITH's `form.ywpar_apply_discounts` in there breaks it. That is why the theme uses a proxy: the native form stays where YITH put it, hidden, and the card triggers it.

---

## 7. CSS — the thing that has cost the most time

`assets/css/checkout.css` is ~2,900 lines and has accumulated eight milestones of rules. **Four separate regressions in this project were caused by the stylesheet fighting itself, not by the plugins.** Read this section before writing a single rule.

### Rule 1 — Fluid's boxed template outranks you with an ID

```css
body.has-fc-design-template--boxed div.woocommerce .fc-wrapper #order_review
  table.woocommerce-checkout-review-order-table td { padding: 5px !important }
```
Specificity **(1,5,4)** with `!important`. An `!important` declaration can only be beaten by `!important`, and an id cannot be outweighed by any number of classes. Panel rules that need to beat it repeat that whole chain plus one extra class. This is why `.pepselect-panel-cell` exists — it is the *one* cell class carrying the id-bearing override, so everything inside it can be plain `div`s needing no `!important` at all.

### Rule 2 — later wins at equal specificity, and that has bitten repeatedly

- **beta.27:** cards rendered transparent because an *older* rule `.fc-checkout-order-review .pepselect-inner-card` (0,2,0) outranked the newer `.pepselect-inner-card` (0,1,0). Fix was deleting the old block, not adding a newer one.
- **beta.29:** the payment heading lost its mono/amber type because that element carries **both** `fc-checkout-order-review-title` and `pepselect-payment-title`, and the panel-heading rule was re-declared later in the file. The heading rule now carries `:not(.pepselect-payment-title)`.
- **beta.32:** three separate rules for `tr.cart_item > td` existed simultaneously.

**If a rule does not apply, do not append a stronger one. Find the rule that is winning and delete it.**

### Rule 3 — an invalid `var()` resolves to *unset*, not to the previous declaration

beta.28 added tokens **after** the closing brace of `:root` in `foundations.css`. They were never defined, so `background: var(--pep-surface-card)` was invalid and the cards computed to **transparent** — even though a literal `background:#FFFFFF !important` rule sat above it. Panel rules now carry literal fallbacks: `var(--pep-surface-card, #FFFFFF)`.

### Rule 4 — table cells

`margin` is ignored on a table cell. Use `padding`. And a cell containing floated children collapses to `height: 0` — `display: flow-root` fixes it. Fluid floats `.product-name` and `.product-details`.

### Design tokens

`assets/css/foundations.css` `:root`. Commercial-surface tokens added M12-16: `--pep-radius-card-inner` (**6px**), `--pep-surface-card`, `--pep-surface-panel`, `--pep-color-quiet`, `--pep-color-placeholder`, `--pep-totals-row-gap`, `--pep-totals-total-gap`.

> An 8px card radius was approved mid-project then **revoked** — the mockup says 6px and the mockup is authoritative. Conventions are recorded in `pep-select-design-tokens.md` §8 and summarised in `AGENTS.md` under *Visual Conventions*.

---

## 8. Where things stand — verification results

Last full pass ran against **installed beta.32**, diffing the live panel against the mockup rendered in an iframe, property-by-property across 36 mapped selector pairs.

**186 comparisons · 184 PASS · 2 FAIL.** DOM order matched the mockup exactly. All 46 mapped mockup selectors resolve to a live element.

beta.33 closed two of three:

| Item | Status |
|---|---|
| Place order 15px/700 → 13px/600 | **Closed.** Legacy M7 rule selects it as `button#place_order.fc-place-order-button` (1,1,1), which outranked panel-scoped `#place_order` |
| Line item cell `height: 0`, qty overlapping card by −69.2px | **Closed.** Cell was matched by the panel-cell group's id-bearing selector *and* Fluid floats its children. Deduped to one rule + `flow-root`. Cell 0 → 92px, overlap −69.2 → +23px |
| items→card gap 23px vs mockup 18px | **Closed on installed beta.33.** Live measurement is exactly 18px from both the quantity line and `.product-details` to the discount card |

beta.34 follow-up: the live line item exposed Fluid's editable quantity stepper even though the priority-90 callback had been removed. The approved mockup and Orbitrex reference have only `Qty 2` plus `Remove`; beta.34 suppresses the surviving wrapper at Fluid's id-bearing specificity. It also formats the visible tax label as `Sales tax (WA)` from the customer's live state code.

### Installed beta.34 verification

- Production `style.css` reports `0.20.0-beta.34`.
- Fluid quantity wrapper exists for compatibility but computes to `display:none`; only `Qty 2` and `Remove` are visible.
- Quantity-to-discount-card and `.product-details`-to-card gaps both measure exactly `18px`.
- Panel width/padding/radius: `420px` / `28px` / `16px`; inner card padding/radius: `16px` / `6px`; Place order padding/weight: `13px` / `600`.
- Tax row renders `Sales tax (WA)` without changing the calculated amount.
- Coupon lifecycle passed on Live: remove → card refresh → reapply `WELCOME10` → pill restored → survives reload.
- Corrected quantity visibility, 18px gap, and tax label survive both checkout fragment refreshes and a full reload.
- Browser console: zero errors during install, fragment refresh, and reload verification.

### Installed beta.37 cash-back verification

- Balance copy is `REDEEM CASH BACK (YOU HAVE $11.20)`; the only minimum copy is `Minimum redemption is $5.00.`
- The dollar control starts at `0`. The wrapper owns the single `1px` border; the input computes to `border: 0`, transparent background, and `0px` radius, so there is no nested field.
- Focus selects the entire value: typing after focus replaced `11.20` with `7` rather than appending.
- Max changed `0` to `11.20` without applying a coupon, changing totals, navigating, or opening a dialog.
- Apply produced the separate `CASH BACK −$11.20 applied` pill and `Cash back −$11.20` total with no leave-site dialog.
- Applied pills are inside `.pepselect-applied-list`, which computes to `flex-direction: column`; valid combinations stack one pill per line. `WELCOME10` itself remains individual-use, so a simultaneous live coupon/cash-back combination was not forced.
- Removal immediately marks the pill as processing, removes the cash-back total, and restores the card at `0` with `(YOU HAVE $11.20)` even though YITH omits its native form after the refresh.
- The restored form was used for a second complete Max → Apply → Remove cycle. This exercises the cached-state fallback and YITH `ywpar_apply_points` endpoint, not only the first native-form path.
- `WELCOME10` was restored after testing and the checkout ended with one WELCOME10 pill, the redemption card at `0`, and no cash back applied.

### Installed beta.39 mobile-flow verification

- Fluid PRO's narrow layout physically moves `#order_review` from `.fc-checkout-order-review__inner` into `.fc-checkout-order-review-collapsible__content .collapsible-content__inner`. That is why the old mobile page showed `Your cart — 2 items` at the top while the bottom `Your Order` card contained only payment.
- `assets/js/checkout-mobile-order.js` moves the existing `#order_review` node—not a clone—back after `.fc-order-review-table__placeholder--main`. The move retains WooCommerce/YITH handlers and form fields. A mutation observer plus `updated_checkout`, resize, and media-query listeners correct Fluid's later fragment or responsive relocations.
- The top collapsible is hidden only while `body.pepselect-mobile-order-review-expanded` confirms that the real order table is safely inside the bottom card. If placement ever fails, Fluid's original top dropdown remains visible.
- Verified at 390, 440, 768, and 900px: checkout fields first; then heading, product, discount, cash back where available, BAC, totals, payment, and Place order. The fields-to-summary gap is 20px and the top collapsible computes to `display:none`.
- At 390 and 440px, `document.body.scrollWidth === window.innerWidth`; no sidebar descendant crossed either viewport edge. Product and Place order remain visible.
- At 1000px the success class is absent, Fluid's normal permanent desktop sidebar owns `#order_review`, and the top collapsible remains hidden by Fluid itself. This confirms the custom behavior stops at the exact 999/1000 responsive boundary.
- Logged-in verification at 768px included WELCOME10/cash-back-capable markup and showed the full sequence inside `.fc-sidebar`; Chrome reported no console errors.

### Installed beta.41 iPad Pro verification

- At 1024px, Fluid's native desktop switch produced an 800px checkout wrapper split into a 450px form and 300px sidebar. With the correct 28px panel padding, only 242px remained for order content. No element technically overflowed, but the desktop panel was visibly crushed.
- Width sampling found the real comfortable breakpoint: Fluid keeps that 800px wrapper through 1199px, then jumps to an 1140px wrapper at 1200px. At 1200px the order panel reaches its intended 420px width and 362px content width.
- The stacked flow therefore extends through 1199px. A separate 1000–1199px alignment rule removes Fluid's surviving `.fc-inside` float/450px width so both checkout fields and the order panel share the same centered 800px container.
- Verified at 1024×1366: fields `800px` at x=`112`, order panel `800px` at x=`112`, usable order content `742px`, a `20px` vertical gap, top cart dropdown hidden, product and Place order visible, and body width exactly `1024px` with no horizontal overflow.
- Verified at 1199px: the same centered 800px stacked geometry. At 1200px: custom success class absent and Fluid's normal 662.5px + 420px desktop columns restored.
- Live viewport transitions 1024 → 1200 → 1024 passed without reload. The order review stayed in the correct sidebar card, both layouts returned their exact widths, and `checkout-mobile-order.js` logged zero errors.

### Installed beta.42 country, birthday, and link verification

- Both country rows are hidden at checkout, but the underlying billing and shipping country controls remain present and submit `US`. Tax, shipping, and order country data are therefore preserved.
- YITH's optional `yith_birthday` field is removed through `woocommerce_checkout_fields` before Fluid Checkout builds its expandable section. On Live, the input, toggle, and wrapper all return zero DOM nodes.
- Every visible link inside `.fc-checkout-steps` computes to Pep Select cyan `rgb(23, 161, 207)`; zero checkout links retain WooCommerce pink `rgb(204, 51, 102)`.
- Live sweep passed at 390, 440, 768, 900, 1024, 1199, 1200, and 1440px. Every width retained US values, no birthday markup, visible cart products, no horizontal overflow, and zero checkout-script console errors.
- The intended layout boundary remains exact: stacked fields-then-order through 1199px; normal side-by-side desktop columns at 1200px and above.

### Installed beta.43 side-cart BAC verification

- Root cause: the side-cart hook and shared BAC markup were correct, but their styles lived only in `checkout.css`. Because that file is limited to checkout/cart/order-received, the drawer rendered an unstyled 385×306px image and native checkbox on Home and product pages.
- `assets/css/side-cart-upsell.css` now carries only the compact drawer component and is enqueued wherever Xootix can open and the BAC product is offerable. Checkout CSS and checkout geometry are unchanged.
- Live desktop card: 385px wide × 107px high, 16px padding, 1px border, 6px radius, 52×52px thumbnail, 44×26px branded switch. This matches the working checkout component's 107px height and 52px thumbnail.
- Live 390px drawer: 371px wide inside the viewport, card 331px wide × 116px high, 52×52px thumbnail, no horizontal overflow. The small height increase is the expected one-line wrap in the narrower drawer.
- Live interaction passed: add changed the cart from 2 to 3 and suppressed the offer; removing the BAC line returned the cart to 2 and restored the compact offer without a reload. Zero upsell-script console errors.

### Installed beta.44 side-cart spacing and size verification

- The BAC card now has a measured 12px gap before the footer buttons at every tested width instead of sharing an edge with them.
- Desktop card: 385px wide × 113px high, 18px padding, 56×56px thumbnail. Both footer buttons remain fully visible and the drawer has no horizontal overflow.
- The phone rule keeps 16px card padding while retaining the 56px thumbnail and slightly larger type. At 390px the card is 331px wide × 137px high, the row stays inside the card, and both buttons remain visible.
- Live sweep passed at 360, 375, 390, 430, 768, and 1024px: exact 12px card/button gap, no clipping, no horizontal overflow, and no missing buttons. Narrower 360/375px layouts wrap the label and price naturally inside the card rather than shrinking or overflowing them.

### Installed beta.45 shared BAC image and price verification

- The shared upsell renderer now prints the live WooCommerce price only. The visible price is `$19.99` in checkout and the side cart; the former parsed `30mL –` prefix is absent. The full product name remains in the checkbox's accessible label.
- The product image is 72×72px on both surfaces. In the desktop side cart it now fills the card content height without increasing the 113px card height; the existing 12px card-to-button gap remains unchanged.
- The 390px side cart is 331px wide × 127px high, with the 72px image, `$19.99` price, both footer buttons visible, no clipping, and no horizontal overflow.
- Checkout passed at 390, 440, 768, 1024, 1199, 1200, and 1440px: 72×72px image, price only, card and row inside the panel, and no horizontal overflow. The iPhone 16 Pro Max-width card remains fully contained.
- Live add/remove passed after the markup change: cart 2 → 3 with BAC and back to 2 after removal; the offer hid and returned correctly. Zero upsell-script console errors, and the test cart was restored.

### Installed beta.46 cart-only rewards and coupon verification

- Cart only: YITH's `.wp-block-yith-par-message-reward-cart_container` remains in the DOM for plugin compatibility but computes to `display:none`; its top redemption banner is no longer visible.
- The separate Pep Select cash-back pill remains visible and still reads `You’ll earn $4.80 in cash back`. Its code and styling were not changed.
- WooCommerce Blocks' coupon form mounts expanded after initial render and after block re-renders. Its accordion handle is hidden, while the existing Enter code input and Apply button remain visible.
- Desktop and logged-in 390px mobile both passed. Mobile form width is 355px from x=10 to x=365, both controls fit, product count remains one cart line / quantity two, total remains $174.39, and the page has no horizontal overflow.
- Checkout, side cart, products, totals, shipping, reward earning logic, and coupon application logic were not modified. Zero `cart-rewards.js` console errors.

### Still owed
- Square amount matches the discounted total with a redemption applied
- A test order end-to-end with redemption applied

Last successful end-to-end test order was **#1443** on staging (beta.21): cash back $7.20 applied as `ywpar_discount_1`, total $98.67 → $91.00, Square panel read $91.00, all acknowledgment meta written, balance $7.20 → $0.00 → restored to $7.20 on cancel.

---

## 9. Settings that are NOT code — for Paulo, in wp-admin

| Thing | Where |
|---|---|
| Progress bar still rendering | WooCommerce → Settings → Fluid Checkout → Checkout → **Progress bar** (`fc_enable_checkout_progress_bar`) |
| Collapsible substeps / "Change" links | WooCommerce → Settings → Fluid Checkout → Layout → **Checkout layout** → `single-step` (option `fc_checkout_layout`, values `multi-step` \| `single-step`) |
| Tax label "WA State Tax" | WooCommerce → Settings → Tax → Standard rates → **Tax name** |
| `/military-discount/` noindex on Live | Pages → Military & First Responder → Yoast SEO → Advanced → *Allow search engines…* → **Yes** |
| Side cart "Continue Shopping" button | It is a **text field**, not a toggle — clearing the label removes the button (`sct-ft-contbtn`). Code alternative: `xoo_wsc_footer_buttons_args` → `unset($args['buttons']['continue'])` |
| Cash back + promo stacking | `welcome10` has *Individual use only* ticked, so cash back and promo codes cannot coexist. Untick in Marketing → Coupons if they should stack. **Unverified risk:** applying an individual-use coupon *second* may silently drop the cash back rather than erroring |

---

## 10. Build / release ritual

```
STEP 0  git fetch && git pull origin web-2c-homepage
        Print the REAL Version header from pepselect-child/style.css
Validate  php-parser AST on changed PHP  (scratchpad/phpcheck/check.js)
          node --check on changed JS
          brace-count on CSS
Release   bump style.css → CHANGELOG entry → commit → push
          BOTH ZIPs to dist/ per AGENTS.md, named <package>-<version>.zip
          print SHA-256 for release + rollback side by side
```

`dist/` is gitignored. ZIPs are built with `git archive --format=zip -o dist/... HEAD -- pepselect-child`.

Current verified release: `dist/pepselect-child-0.20.0-beta.46.zip` · 275,005 bytes · SHA-256 `7600b797e92c9c00bea15a279c6f81677672bb35ce78d34cfe0627199d584f02`.

Immediate rollback: `dist/pepselect-child-0.20.0-beta.45.zip` · 274,500 bytes · SHA-256 `910bf9abcd01db25b8994df4ad131c88bf01207f04190a231a1a385578ddc1d9`.

Deployment is a **manual ZIP upload through WordPress**. Nothing auto-deploys. Live and staging drift — always verify the deployed `style.css` version from production, never assume, and never trust the "Live version" line in `CHANGELOG.md` by itself (it was stale once and caused a wrong call).

---

## 11. Honest note on why this stalled

The recurring failure was **method, not knowledge**. Verification was done by injecting CSS into a page running the *previous* build. That cannot detect:

- a token defined outside `:root` (the var is simply absent)
- a class collision that depends on stylesheet order
- a server-rendered element that does not exist yet
- an element whose height does not contain its children

Every one of those shipped at least once. The reliable loop is: **install the build, then measure it, then fix.** The computed-style diff harness in §8 is the right tool — it found two real defects in one pass, including one (BAC card rendering in the wrong position) that no amount of looking at the page had caught. Use it, and run it against an installed build.

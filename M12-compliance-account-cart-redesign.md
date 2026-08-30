# M12 — Compliance, account, and cart redesign

**Created:** 2026-08-11  
**Branch:** `web-2c-homepage`  
**Theme version at plan time:** 0.20.0-beta.7 (confirm real value in `style.css` before any build)  
**Reference for design language:** orbitrexpeptide.is  
**Reference for the BAC water card only:** trustedpeps.us

---

## Sequencing

Do these one at a time, in this order. Each ships as its own beta with a rollback ZIP.

| Order | Item | Why here |
|---|---|---|
| **M12-1** | Checkout compliance checkboxes | Blocks payment processor approval. Nothing else matters until this ships. |
| **M12-2** | Account page simplification | Self-contained. No dependency on cart work. |
| **M12-3** | Clean Orbitrex-style cart and side cart | Must precede M12-4 so the upsell lands in a finished surface. |
| **M12-4** | BAC water card inside the side cart | Depends on M12-3. |
| **M12-5** | Discount pill + apply cash-back balance at checkout | Largest unknown. Last so a long diagnosis cannot block the rest. |

---

## Blockers

### 1. www / non-www split cart — STILL LIVE, verified 2026-08-11

This was reported as possibly fixed. It is not. Re-verified in one browser, same moment:

| Host | Store API `items_count` |
|---|---|
| `www.pepselect.com` | **0** |
| `pepselect.com` | **1** (GLP-3 R x1) |

`https://www.pepselect.com/testing/` returns **200 on www with no redirect**. Every other URL redirects www → non-www correctly, which is why this cannot be reproduced in ordinary browsing. You only land on www by opening the Quality Archive — the page promoted as the primary trust signal. A visitor who checks a COA and then adds to cart is on a second WooCommerce session for the rest of that visit.

**Repro in 30 seconds:** open `https://www.pepselect.com/testing/`, confirm the address bar still says `www`, add anything to the cart from there, then open `https://pepselect.com/cart/`. The item will be missing.

Kinsta-level redirect fix, not theme work. **Fix before M12-3.** Cart and side cart testing is unreliable until it is done.

### 2. Stale cart fragments

Header badge and side cart lag the real cart; a badge read "3" immediately after the cart was emptied. Fix as part of M12-3, not after.

### 3. Free shipping coupons inert

Shipping → US zone → Free shipping → change "Free shipping requires" to *A minimum order amount OR a coupon*. One dropdown. Relevant to M12-5 testing.

---

## M12-1 — Checkout compliance checkboxes

**Priority: highest. Required for payment processor approval.**

### Goal

Replace the current consent checkboxes with two mandatory acknowledgments modelled on Orbitrex.

### Current state

- "Research Purpose *" dropdown
- "I have read and agree to the Privacy Policy. *"
- "I have read and agree to the Terms & Conditions. *"

### Target state

Section heading: **Acknowledgments**

**Checkbox 1 — required.** Compliance statement, verbatim:

```
Research-only use restriction; prohibition on human or animal consumption;
acknowledgment that products are not for diagnosis/treatment/prevention of any
disease; indemnification of the seller; acknowledgment that the buyer is a
qualified professional.
```

**Checkbox 2 — required.** Combined policy agreement, all three linked:

```
I have read and agree to the Terms & Conditions, Privacy Policy, and
Return & Refund Policy.
```

- Terms & Conditions → `https://pepselect.com/terms-conditions/`
- Privacy Policy → `https://pepselect.com/privacy-policy/`
- Return & Refund Policy → `https://pepselect.com/refund-shipping-policy/`
- New tab, `rel="noopener"`

### Constraints

- Both mandatory. Place order blocked until both ticked.
- Inline error next to the unticked box on failed submit, focus moved to the first. Not a generic top-of-page notice.
- `required` and `aria-required` in markup.
- **Store acceptance on the order** with a timestamp, in order meta, surfaced in the admin order screen. This is what turns a checkbox into evidence in a dispute, which is what an underwriter cares about.
- Checkbox 1's label is long. Fully readable at 390px, no truncation, no scroll box.
- Keep the **Research Purpose dropdown** above Acknowledgments. Do not remove it. Orbitrex has no equivalent; it demonstrates collection of a stated research purpose and strengthens the application.
- Record the previous checkbox labels verbatim in `HANDOFF-processor-compliance-wording.md` before changing them.

### Done when

Submission is blocked with zero or one box ticked, succeeds with both, and acceptance meta is written to a real test order with no payment taken.

---

## M12-2 — Account page simplification

### Goal

Replace the multi-page WooCommerce account area with a single scrolling page of cards.

### Structure — four cards

1. **Welcome, {first name}** + email, with Sign Out button
2. **My Information** — name, phone, default shipping address, with Edit button
3. **Cash back** — current balance in dollars, lifetime earned, plain-English earn and redeem explanation
4. **Your Orders** — inline list. Per order: order number, date, status, total, tracking number, cash back earned and applied, and the line items with quantity and price. No click-through needed to see what was in an order.

**Marketing / email and SMS toggles are OUT OF SCOPE.** Orbitrex has them; Pep Select is not building them in M12-2.

### Constraints

- **Do not break WooCommerce endpoints.** Order detail views, downloads, payment methods and address editing must still resolve. Collapsing the visible navigation is a presentation change; the endpoints stay.
- Cash-back card uses **dollar framing** per the standing copy rule. Points are the backend mechanic; customers see dollars.
- The generic "Account activity" label bug on the cash-back page is open from M10 — fix it here rather than carrying it forward.
- Existing tokens only: navy `#002A53`, cyan `#17A1CF`, Georgia for card headings, Plus Jakarta Sans for interface, IBM Plex Mono for figures.
- Google OAuth login via Nextend Social Login Pro must keep working.

---

## M12-3 — Clean cart and side cart

### Goal

Bring the cart page and side cart to the same calm, uncluttered standard as the compound cards.

### What to take from Orbitrex

- Generous whitespace, few borders, one clear summary panel
- Monospace for figures and totals; sans for labels
- A single unmistakable primary action
- Plain-English reassurance under the total ("Estimated with your saved address — final total confirmed at checkout")
- Nothing competing with the checkout button

### Explicitly NOT wanted

- **No free-shipping progress bar.** TrustedPeps has one; it is clutter and does not ship.
- Nothing else from the TrustedPeps side cart. The BAC water card in M12-4 is the only element being borrowed from it.

### Current state

- **Cart page**: functional and on-brand after M7, but denser than the target.
- **Side cart**: WooCommerce Side Cart Premium (`xoo-wsc`). Completely off-brand — dark blue buttons, different typography, "Have a Promo Code?", "Free!" styling. It fires on every add-to-cart, so it is one of the most-seen surfaces on the site.

### Route decision — record it before building

- **A — Override the plugin's CSS from the child theme.** Faster, lower risk, but a plugin update can undo it and you inherit its markup.
- **B — Replace it with a coded side cart in the theme.** Full design control. Must reimplement AJAX add/remove, quantity, and fragment refresh.

Dropping the progress bar removes the most awkward piece of route B, so B is more viable than it first appeared. Evaluate A first; move to B if the plugin's markup makes the target unreachable. Whichever is chosen, record the reason here.

### Constraints

- Cart page keeps: cash-back pill, coupon field, dilution-safe copy, mobile carousel empty state, BAC water exclusion from the "Selected compounds" list.
- Fix the stale fragment lag as part of this.

---

## M12-4 — BAC water card in the side cart

### Goal

Surface the bacteriostatic water offer inside the side cart, so it is seen before checkout rather than only at checkout.

### Pattern

A tinted card near the bottom of the side cart, above the discount code field and above the subtotal:
- Short heading
- Product thumbnail, name, price
- A single **Add** button

This is the only element taken from TrustedPeps. Everything around it follows the M12-3 design language.

### Constraints

- Same product and lookup as the checkout upsell: SKU `BACW30`, product 1339, $19.99, catalog visibility Hidden.
- Reuse the existing checkout upsell resolver. Do not create a second source of truth for price or stock.
- Out of stock → the whole card does not render. No greyed button, no message.
- Quantity 1. If already in the cart, the card reflects that rather than offering it again.
- Copy stays inside the compliance framing established in the processor work. Do not connect it to the dilution notice or make any grade, purity, or suitability claim.
- Adding from the side cart must update the side cart, header badge, and cart page live.

### Interaction with M12-1

The checkout upsell card stays where it is, between billing address and coupon code, above payment method. Two entry points is fine; two different price sources is not.

---

## M12-5 — Discount pill and applying cash-back balance at checkout

**Diagnosis first, build second.**

### Goal

Two things at checkout:

1. **Discount code as a pill.** Enter a code, hit Apply, and the applied code renders as a removable pill rather than a plain line item.
2. **Apply cash-back balance directly** — no code generation step. A field showing the available balance in dollars, with Apply and Max buttons, that reduces the order total.

### The economics are already defined — do not change them

Existing published rules stay exactly as they are:

- Earn **3% cash back**: 3 points per $1 spent
- **100 points = $1**
- Minimum redemption **500 points ($5)**

**Only the mechanism changes.** Today a customer converts their balance into a discount code in their account and pastes it at checkout. The target removes that step: they apply the balance directly at checkout, like Orbitrex.

This means M12-5 has no open business question. It is purely a technical problem.

### Why this has failed before

Unknown. **Leading hypothesis worth testing first:** Fluid Checkout replaces the checkout template, so plugin markup hooked to classic checkout hooks silently never renders. This is exactly the failure mode hit with the BAC upsell, where `woocommerce_review_order_after_order_total` had to be verified against Fluid rather than assumed. YITH's redemption form may hook somewhere Fluid does not fire.

### Diagnosis phase — required before any build decision

Report on each, with evidence:

1. Does YITH WooCommerce Points and Rewards have a **native checkout redemption** feature, and is it enabled? Screenshot the setting.
2. If enabled, does its markup render on the Fluid Checkout page? Check the DOM, not just the visual. If absent, identify the hook it uses and whether Fluid fires it.
3. Is the failure configuration, hook incompatibility, or a genuine plugin limitation? Name which.
4. What is Orbitrex using? Inspect their checkout for plugin fingerprints — script handles, CSS class prefixes, REST routes, generator meta. Report findings rather than guessing.

### Escalation path, in order

1. **Fix YITH's native redemption** — cheapest, keeps one rewards system.
2. **Bridge YITH to Fluid Checkout** from the child theme — render redemption at a hook Fluid does fire, reading YITH's own API rather than reimplementing balance logic.
3. **Replace the rewards plugin**, if a materially better one is identified.
4. **Custom WP plugin** — last resort only. Owning accrual, redemption, refunds and order-edit adjustments is a real maintenance burden and a place where money bugs live.

Do not skip to option 4 because options 1–3 look tedious.

### Constraints

- Customer-facing framing stays **cash back in dollars**.
- The existing 3% / 100 points = $1 / 500-point minimum rules must be preserved exactly.
- Redemption must survive order edits, partial refunds and cancellations without leaking value. Specify this behaviour before building.
- Must not break the header cash-back pill or the account cash-back card.
- The account page must keep working whether or not code generation remains as a fallback. Decide and record whether the "turn your balance into a code" path is retired or kept.

---

## Cross-cutting constraints

- **Design tokens only.** Navy `#002A53`, deep navy `#001D3A`, cyan `#17A1CF`, green `#16834A`, amber `#B46A00`, red `#C43D3D`. Radii 8/12/20/32/999. ~180ms motion with reduced-motion support. Breakpoints 767px / 1024px. No new colours, radii, or motion values.
- **Type:** Georgia editorial, Plus Jakarta Sans interface, IBM Plex Mono technical and figures.
- **Copy:** calm, precise, plain English. Audience is researchers and lab purchasers. Never imply human use, dosing, medical outcomes, or guaranteed purity. No em-dashes. Run through `.agents/product-marketing.md` and `.agents/skills` before delivery.
- **Never touch the COA plugin's cards or files.** Read-only via core APIs where data is needed.
- **HPOS compatibility mode stays on.** Disabling it silently stops Easyship receiving orders.
- **Legacy REST API stays removed.** Easyship 0.9.16 and the Control App use current REST integrations; do not reinstall the legacy plugin unless a named `/wc-api/` consumer is demonstrated.
- Amber is reserved for the Square payment instruction panel. Do not reuse it for offers or upsells.
- Every release: bump `style.css`, CHANGELOG entry, commit, push, ZIP to `dist/`, print SHA-256, and build a rollback ZIP alongside.

---

## Carried-over items not in M12

- Military & First Responder VerifyPass button inline styles fighting theme CSS (cosmetic)
- Restocking chip mapping — blocked until a real batch enters a pre-approval stage. Compound → product link uses `pepselect_coa_product_id`, not `woocommerce_product_id`; batch records carry no SKU field; compounds are stored per strength, so the granularity is already correct.
- Batch-number-on-cart-line — ops app backlog
- `ywot_tracking_code` empty on shipped orders; theme parses an Easyship order note instead. Fragile. Ops app backlog.

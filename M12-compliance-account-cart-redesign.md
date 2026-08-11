# M12 — Compliance, account, and cart redesign

**Created:** 2026-08-11  
**Branch:** `web-2c-homepage`  
**Theme version at plan time:** 0.20.0-beta.7 (confirm real value in `style.css` before any build)  
**Reference site for design language:** orbitrexpeptide.is  
**Reference for side cart upsell pattern:** trustedpeps.us

---

## Sequencing

Do these one at a time, in this order. Each ships as its own beta with a rollback ZIP.

| Order | Item | Why here |
|---|---|---|
| **M12-1** | Checkout compliance checkboxes | Blocks payment processor approval. Nothing else matters until this ships. |
| **M12-2** | Account page simplification | Self-contained. No dependency on cart work. |
| **M12-3** | Orbitrex design language on cart + side cart | Must precede M12-4 so the upsell is built into a finished surface, not retrofitted. |
| **M12-4** | BAC water offer inside the side cart | Depends on M12-3. |
| **M12-5** | Discount pill + loyalty points redemption | Largest unknown. Do last so a long diagnosis cannot block the rest. |

---

## Blockers to clear first

These are open from earlier work and will actively corrupt testing on M12-3, M12-4 and M12-5.

1. **www / non-www split cart.** `/testing/` returns 200 on `www` without redirecting, so any visitor who touches the Quality Archive lands on a second WooCommerce session with a different cart. Proven live: emptying the cart on `pepselect.com` reported 0 items while `www.pepselect.com` reported 2 at the same moment. This is a Kinsta redirect, not theme work. **Fix before M12-3.**
2. **Stale cart fragments.** Header badge and side cart lag the real cart; a badge read "3" immediately after the cart was emptied. Directly relevant to M12-3 and M12-4.
3. **Free shipping coupons inert.** Shipping → US zone → Free shipping → change "Free shipping requires" to *A minimum order amount OR a coupon*. One dropdown. Relevant to M12-5 testing.

---

## M12-1 — Checkout compliance checkboxes

**Priority: highest. Required for payment processor approval.**

### Goal

Replace the current consent checkboxes with two mandatory acknowledgments modelled on Orbitrex.

### Current state

Three separate controls:
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

**Checkbox 2 — required.** Combined policy agreement, with all three linked:

```
I have read and agree to the Terms & Conditions, Privacy Policy, and
Return & Refund Policy.
```

- Terms & Conditions → `https://pepselect.com/terms-conditions/`
- Privacy Policy → `https://pepselect.com/privacy-policy/`
- Return & Refund Policy → `https://pepselect.com/refund-shipping-policy/`
- Links open in a new tab with `rel="noopener"`

### Constraints

- Both mandatory. Place order blocked until both ticked.
- Inline error next to the unticked box on failed submit, focus moved to the first one. Not a generic top-of-page notice.
- `required` and `aria-required` in markup.
- **Store acceptance on the order** with a timestamp, in order meta, surfaced in the admin order screen. This turns the checkbox from decoration into evidence in a dispute, which is exactly what an underwriter cares about.
- Checkbox 1's label is long. Fully readable at 390px, no truncation, no scroll box.
- Keep the **Research Purpose dropdown** above the Acknowledgments section. Do not remove it. It demonstrates collection of a stated research purpose and supports the application.
- Record the previous checkbox labels verbatim in `HANDOFF-processor-compliance-wording.md` before changing them.

### Done when

Submission is blocked with zero or one box ticked, succeeds with both, and acceptance meta is written to a real test order (no payment taken).

---

## M12-2 — Account page simplification

### Goal

Replace the multi-page WooCommerce account area with a single scrolling page of cards, matching Orbitrex's structure.

### Orbitrex structure (observed)

One page, no tabs, no sub-navigation. Cards in order:

1. **Welcome, {first name}** + email, with Sign Out button
2. **My Information** — name, phone, default shipping address, with Edit button
3. **Marketing / Email & text updates** — two toggles with consent microcopy beneath
4. **Loyalty Points** — current balance, dollar value, lifetime earned, plain-English earn/redeem explanation
5. **Your Orders** — inline list. Per order: order number, date, status, total, tracking number, points earned/redeemed, and the line items with quantity and price. No click-through needed to see what was in an order.

### Constraints

- **Do not break WooCommerce endpoints.** Order detail views, downloads, payment methods and address editing must all still resolve. Collapsing the visible navigation is a presentation change; the endpoints stay.
- Loyalty card must use **cash-back framing** per the standing copy rule: customers see a dollar figure, not raw points terminology as the primary unit.
- The generic "Account activity" label bug on the cash-back page is open from M10 — fix it here rather than carrying it forward.
- Cards use existing tokens: navy `#002A53`, cyan `#17A1CF`, Georgia for card headings, Plus Jakarta Sans for interface, IBM Plex Mono for figures.
- Google OAuth login via Nextend Social Login Pro must keep working.

### Open question

Marketing toggles (item 3 in Orbitrex's layout) require an email/SMS consent backend. FluentCRM Pro is installed. Decide whether this card is in scope for M12-2 or deferred — do not build a toggle that writes nowhere.

---

## M12-3 — Orbitrex design language on cart and side cart

### Goal

Bring the cart page and side cart up to the same calm, uncluttered standard as the compound cards.

### What makes Orbitrex's cart work

- Generous whitespace, few borders, one clear summary panel
- Monospace for figures and totals; sans for labels
- A single unmistakable primary action
- Plain-English reassurance under the total ("Estimated with your saved address — final total confirmed at checkout")
- Nothing competing with the checkout button

### Current state

- **Cart page**: functional and on-brand after M7, but denser than Orbitrex.
- **Side cart**: WooCommerce Side Cart Premium (`xoo-wsc`). Completely off-brand — dark blue buttons, different typography, "Have a Promo Code?", "Free!" styling. It fires on every add-to-cart, so it is one of the most-seen surfaces on the site.

### Decision required before building

The side cart is a third-party plugin. Two routes:

- **A — Override its CSS from the child theme.** Faster, lower risk, but a plugin update can undo it and you inherit its markup constraints.
- **B — Replace it with a coded side cart in the theme.** Full design control, no plugin fighting, but it must reimplement AJAX add/remove, quantity, fragment refresh and the free-shipping progress bar.

Recommend evaluating A first and only moving to B if the plugin's markup makes the target design unreachable. Record the decision here before building.

### Constraints

- Cart page must keep: cash-back pill, coupon field, dilution-safe copy, mobile carousel empty state, BAC water exclusion from the "Selected compounds" list.
- Fix the stale fragment lag as part of this, not after.

---

## M12-4 — BAC water offer in the side cart

### Goal

Surface the bacteriostatic water offer inside the side cart, so it is seen before checkout rather than only at checkout.

### Reference pattern (TrustedPeps)

A tinted panel near the bottom of the side cart, above the discount code field:
- Heading along the lines of "Don't forget your BAC Water!"
- Product thumbnail, name, price
- A single **Add** button
- Sits above the subtotal and the checkout button

### Constraints

- Same product and lookup as the checkout upsell: SKU `BACW30`, product 1339, $19.99, catalog visibility Hidden.
- Reuse the existing checkout upsell resolver. Do not create a second source of truth for price or stock.
- Out of stock → the whole panel does not render. No greyed button, no message.
- Quantity 1. If already in the cart, the panel reflects that rather than offering it again.
- Copy must stay inside the compliance framing established in the processor work. Do not connect it to the dilution notice or make any grade, purity, or suitability claim.
- Adding from the side cart must update the side cart, header badge, and cart page live.

### Interaction with M12-1

The checkout upsell card stays where it is (its own card between billing address and coupon code, above payment method). Two entry points is fine; two different price sources is not.

---

## M12-5 — Discount code pill and loyalty points redemption

**Largest unknown. Diagnosis first, build second.**

### Goal

Two things at checkout:

1. **Discount code as a pill.** Enter a code, hit Apply, and the applied code renders as a removable pill (Orbitrex: `PEPPAL 10% off - $8.00ncoded with an ×`) rather than a plain line item.
2. **Loyalty redemption without generating coupon codes.** A field showing the customer's available balance in dollars, with Apply and Max buttons, that reduces the order total directly.

### Orbitrex's model, for reference

- Account page states: earn 1 point per $1 spent; 1,000 points = $50 off any order (so 1 pt = $0.05)
- Checkout shows: `REDEEM LOYALTY POINTS (YOU HAVE 243 PTS ($12.15) — 1000 PTS = $50)` with a number input, **Apply**, and **Max**
- Applied discounts render as removable pills in the order summary

### Why this has failed before

Unknown, and that is the point of the diagnosis phase. **Leading hypothesis worth testing first:** Fluid Checkout replaces the checkout template. Plugin markup hooked to classic checkout hooks silently never renders. This is exactly the failure mode hit with the BAC upsell, where `woocommerce_review_order_after_order_total` had to be verified against Fluid rather than assumed. YITH's redemption form may be hooking somewhere Fluid does not fire.

### Diagnosis phase — required before any build decision

Report on each, with evidence:

1. Does YITH WooCommerce Points and Rewards have a **native checkout redemption** feature, and is it enabled in settings? Screenshot the setting.
2. If enabled, does its markup render on the Fluid Checkout page? Check the DOM, not just the visual. If absent, identify the hook it uses and whether Fluid fires it.
3. Is the failure configuration, hook incompatibility, or a genuine plugin limitation? Name which.
4. What is Orbitrex actually using? Inspect their checkout for plugin fingerprints — script handles, CSS class prefixes, REST routes, generator meta. Report findings rather than guessing.
5. Confirm the intended Pep Select conversion rate and cap. Currently 3% cash back earned; redemption rate and any per-order cap are **not yet defined**. This is a business decision, not a code one.

### Escalation path, in order

1. **Fix YITH's native redemption** — cheapest, keeps one rewards system.
2. **Bridge YITH to Fluid Checkout** from the child theme — render YITH's redemption at a hook Fluid does fire, reading YITH's own API rather than reimplementing balance logic.
3. **Replace the rewards plugin** with whatever Orbitrex uses, if that is identified and materially better.
4. **Custom WP plugin** — last resort only. Owning points accrual, redemption, refunds, and order-edit adjustments is a real maintenance burden and a place where money bugs live.

Do not skip to option 4 because options 1–3 look tedious.

### Constraints

- Customer-facing framing stays **cash back in dollars**. Points are the backend mechanic.
- Redemption must survive order edits, partial refunds, and cancellations without leaking value. Specify this behaviour before building.
- Must not break the existing header cash-back pill or the account cash-back page.

---

## Cross-cutting constraints

Apply to every item above.

- **Design tokens only.** Navy `#002A53`, deep navy `#001D3A`, cyan `#17A1CF`, green `#16834A`, amber `#B46A00`, red `#C43D3D`. Radii 8/12/20/32/999. ~180ms motion with reduced-motion support. Breakpoints 767px / 1024px. No new colours, radii, or motion values.
- **Type:** Georgia editorial, Plus Jakarta Sans interface, IBM Plex Mono technical and figures.
- **Copy:** calm, precise, plain English. Audience is researchers and lab purchasers. Never imply human use, dosing, medical outcomes, or guaranteed purity. No em-dashes. Run through `.agents/product-marketing.md` and `.agents/skills` before delivery.
- **Never touch the COA plugin's cards or files.** Read-only via core APIs where data is needed.
- **HPOS compatibility mode stays on.** Disabling it silently stops Easyship receiving orders.
- **Legacy REST API stays installed** — required permanently for Easyship.
- Every release: bump `style.css`, CHANGELOG entry, commit, push, ZIP to `dist/`, print SHA-256, and build a rollback ZIP alongside.
- Amber is reserved for the Square payment instruction panel. Do not reuse it for offers or upsells.

---

## Carried-over items not in M12

Still open, unscheduled:

- Military & First Responder VerifyPass button inline styles fighting theme CSS (cosmetic)
- Restocking chip mapping — blocked until a real batch enters a pre-approval stage. Compound → product link uses `pepselect_coa_product_id`, not `woocommerce_product_id`; batch records carry no SKU field; compounds are stored per strength so the granularity is already correct.
- Batch-number-on-cart-line — ops app backlog
- `ywot_tracking_code` empty on shipped orders; theme parses an Easyship order note instead. Fragile. Ops app backlog.

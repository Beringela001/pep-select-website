# Ops handoff: Cart Discounts 2.3.1 coupon priority

## Purpose

Pep Select Cart Discounts 2.3.1 makes every active sitewide promotion the storefront's discount authority. This prevents accidental combinations during events such as a 30% sitewide sale while still permitting specific automatic-discount exceptions, coupon exceptions, and one higher-value replacement code. Shipping methods remain independent.

WordPress remains the execution engine. Ops should read and write the plugin's authenticated rule document rather than duplicating discount calculations or storing a second promotion model.

## Ownership and control flow

1. Ops reads `GET /wp-json/pepselect-bogo/v1/sitewide-discounts` with the existing WordPress Application Password.
2. WordPress returns schema version 3, the complete rule list, a SHA-256 `revision`, and the supported contract.
3. Ops edits a complete rule in memory, preserving fields it does not yet render.
4. Ops writes the entire collection with `schema_version: 3`, `if_revision`, and `rules`.
5. WordPress validates, normalizes, saves, and returns the authoritative post-save document. A stale revision returns HTTP 409.
6. The plugin's WooCommerce hooks enforce the saved priority on cart, side cart, checkout, Store API requests, and posted orders.

Ops must never calculate or inject the storefront discount itself. WooCommerce coupon and order-line totals remain the accounting authority.

## Sitewide rule schema additions

Every sitewide rule now includes:

| Field | Type | Meaning |
|---|---|---|
| `stackable` | boolean | Deprecated compatibility field. WordPress normalizes it to `false`; sitewide promotions always take over the cart. |
| `allowed_automatic_discounts` | `string[]` | Stable automatic-discount IDs such as `bogo` or `compound:<rule-id>`. Only selected automatic offers remain active and visible. |
| `allowed_coupon_sources` | `string[]` | Supported values are `cart_recovery` and `military`. Valid coupons from these recognized sources may remain beside the sitewide promotion. |
| `allowed_coupon_codes` | `string[]` | Exact Woo coupon codes or safe trailing-star prefixes such as `PARTNER-*`. Maximum 50 normalized entries. |
| `override_coupon_codes` | `string[]` | Exact Woo coupon codes only. Wildcards are stripped. A valid applied code here replaces the sitewide discount and every other discount. |

Existing fields remain unchanged: `id`, `enabled`, `discount_type`, `discount_amount`, `threshold_type`, `threshold_amount`, `audience`, `customer_ids`, `excluded_product_ids`, `label`, `sale_label_color`, `regular_price_color`, and `sale_price_color`.

## Runtime precedence

When no exclusive sitewide rule is active, the existing coordinator behavior remains: eligible stackable automatic rules combine; otherwise the highest-value exclusive automatic rule wins.

When a sitewide rule is active for the current audience:

1. Its takeover policy is active even before a minimum subtotal or quantity is met. Below the minimum, other discounts remain blocked and no automatic sitewide coupon is applied.
2. If a configured replacement code is applied, the sitewide coupon, BOGO, compound rules, and every other coupon are removed. The replacement coupon remains by itself.
3. Otherwise, the sitewide coupon applies when its product/minimum conditions qualify.
4. BOGO and compound automatic coupons are removed and their product/cart pills are hidden, unless their stable ID is explicitly selected in `allowed_automatic_discounts`.
5. Ordinary Woo coupons remain only when they match an allowed exact code, allowed prefix, or allowed source.
6. Unapproved new coupon attempts return: `This code cannot be combined with the current sitewide promotion.`

WooCommerce shipping rates are outside this coupon coordinator. Free shipping over $200 remains available and is not disabled by a sitewide takeover.

The automatic sitewide virtual coupon is intentionally no longer marked `individual_use`. The shared coordinator owns exclusivity so a permitted individual-use recovery coupon can be accepted without WooCommerce removing the sitewide coupon first.

## Coupon-source recognition

- Cart Recovery: Woo coupon metadata `_pepselect_exit_offer` is truthy.
- Military / VerifyPass: the normalized coupon code or description contains `verifypass`, `military`, or `first responder`.
- Integrations may provide a more authoritative source through `pepselect_discount_coupon_source( $source, $coupon )`.

Source recognition never creates or validates a coupon. The coupon must still pass every native WooCommerce validity, email, usage, expiry, product, and minimum restriction.

## LABORDAY40 configuration

For the planned Labor Day promotion, the recommended rule is:

```json
{
  "enabled": true,
  "discount_type": "percent",
  "discount_amount": "30",
  "threshold_type": "none",
  "stackable": false,
  "allowed_automatic_discounts": [],
  "allowed_coupon_sources": [],
  "allowed_coupon_codes": [],
  "override_coupon_codes": ["LABORDAY40"]
}
```

`LABORDAY40` must be a real WooCommerce 40% coupon with its own intended audience, expiry, and usage restrictions. It is a replacement—not an extra 10% coupon—so the order records one clear 40% discount and cannot reach 70%.

## Ops implementation requirements

1. Extend `PromotionRule` in `src/domain/promotionControl.ts` with `allowed_automatic_discounts`, the three coupon arrays, and the existing sale-color/customer fields that are currently omitted.
2. Update sitewide fixtures to `schema_version: 3`.
3. In `savePromotionRuleAction`, parse and send the new fields. For edits, merge form changes into the rule returned by the latest GET so unrendered fields are never erased.
4. Add owner-facing controls on `/new-ops/promotions`: per-automatic-discount exceptions; Cart Recovery and Military/VerifyPass source checkboxes; allowed exact codes/prefixes; exact replacement codes. Do not expose an Allow every discount switch for sitewide rules.
5. Before save, explain that Exclusive takeover blocks all unlisted discounts. Before activation, preview the resulting precedence and affected products.
6. Keep the current consequence check, audit-log before/after snapshot, capability protection, Application Password handling, cache-busted read, and revision conflict handling.
7. Do not add a local coupon table or reproduce Woo coupon validity in Ops. Link to the existing Woo coupon manager until a revision-protected coupon bridge is deliberately built.

## Required Ops tests

- GET accepts and preserves schema version 3 fields.
- Legacy `stackable: true` sitewide rules read back as exclusive and do not retain BOGO or compound discounts.
- Automatic exceptions serialize stable IDs and restore only the selected offer and its storefront pills.
- Toggle writes the full unchanged rule document plus the one enabled-state change.
- Edit preserves unknown fields and all priority arrays.
- Exact allowed code and trailing-star prefix serialize correctly.
- Replacement codes reject or strip wildcard input.
- Stale `if_revision` displays the WordPress conflict without retrying blindly.
- Activation preflight names the active takeover, allowed exceptions, replacement codes, exclusions, and projected margin consequence.
- Audit log stores the full before/after WordPress response without credentials or customer secrets.

## Website verification coverage

The website suite covers field normalization, source and prefix matching, replacement matching, exclusive selection below minimum, disallowed coupon rejection, allowed coupon retention, replacement takeover, BOGO suppression, storefront price suppression after replacement, PHP syntax, and the existing BOGO/compound/cart regressions.

## Rollback

Restore Cart Discounts 2.3.0 and the staging backup created immediately before deployment. Version 2.3.0 retains the core sitewide rules but may honor a legacy Allow every discount state. After rollback, verify BOGO and compound stacking manually.

# WEB-M15: Multi-Rule Compound Discounts 1.7.0

## Outcome

WooCommerce > Compound Discounts now stores a collection of independent rules instead of one global rule. Saving creates a row in **Saved discounts**. Each row can be activated, deactivated, edited, or deleted, and multiple active qualifying rules may apply to the same cart.

## Customer-facing behavior

- The **Customer label** is the visible discount name in Cart and Checkout.
- Labels are required, unique, and limited to 24 characters.
- Automatic discount pills do not expose a remove control. Normal customer coupons and cash-back coupons remain removable.
- Changing or deleting a rule removes its retired automatic coupon code from the cart.

## Compatibility and Ops contract

- The existing `pepselect_compound_discount_rule_v1` setting is read through a non-destructive migration so the configured 1.6.0 promotion remains available.
- New state is stored in `pepselect_compound_discount_rules_v2` after the first manager or Ops update.
- Authenticated Ops clients can read or replace the collection at `/wp-json/pepselect-bogo/v1/compound-discounts` using schema version 2 and revision conflict protection.
- The singular 1.6.0 route remains as an alias but now serves the version 2 collection contract.

## Guardrails

- New discounts are saved inactive and require an explicit activation.
- A maximum of 50 rules is enforced.
- Rule IDs and customer labels must be unique.
- Labels that collide with a published WooCommerce coupon are rejected.
- The YITH Buy 4 Get 1 promotion and its quantity behavior are unchanged.

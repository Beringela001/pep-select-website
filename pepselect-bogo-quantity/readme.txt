=== Pep Select Automatic Free Vials ===
Version: 1.2.1

Companion behavior for the live YITH Buy 4 get 1 free rule.

* A customer selecting 4 sees 5 vials in the cart.
* YITH discounts the earned fifth vial by 100%.
* WooCommerce stores quantity 5, so orders and stock reduction stay truthful.
* Cart line details say how many free vials are included without exposing internal inventory wording.
* Eligible lines in the Side Cart and full Cart show a compact "Add 5, one is on us." pill until the offer is earned.
* Cart quantity controls represent physical vials: reducing 5 to 4 removes the free vial and does not add it back.

Eligible SKUs in 1.0.0: GLP3R10, GLP3R20, GLP2T20, GLP1S10, MOTSC10, GHKCU50.

1.0.1 keeps Side Cart quantity controls on the paid count, so removing a vial
does not immediately add the earned vial back.

1.0.2 applies the same paid-count control behavior to WooCommerce Cart and
Checkout blocks while preserving physical quantities for orders and stock.

1.1.0 makes the visible cart quantity the physical vial count and makes repeated
product-page selections replace the eligible cart line.

1.1.1 lets cart quantity controls edit that physical count normally, fixing the
5-to-4 removal loop while keeping product-page 4-to-5 expansion.

1.1.2 scopes 4-to-5 expansion to the eligible product Add to Cart form so side
cart and Cart block quantity saves cannot accidentally retrigger it.

1.2.0 adds a cart-only promotion pill to eligible lines. Before the offer is
earned it says "Add 5, one is on us." Once earned it confirms the number of
free vials included. Pricing and quantity behavior are unchanged.

1.2.1 renders the pill through Xootix's product-summary hook so it remains
visible when Side Cart product metadata is disabled.

=== Pep Select Automatic Free Vials ===
Version: 1.0.5

Companion behavior for the live YITH Buy 4 get 1 free rule.

* A customer selecting 4 adds 5 physical vials to the cart.
* YITH discounts the earned fifth vial by 100%.
* WooCommerce stores quantity 5, so orders and stock reduction stay truthful.
* Cart line details explain the paid, free, and inventory quantities.

Eligible SKUs in 1.0.0: GLP3R10, GLP3R20, GLP2T20, GLP1S10, MOTSC10, GHKCU50.

1.0.1 keeps Side Cart quantity controls on the paid count, so removing a vial
does not immediately add the earned vial back.

1.0.2 applies the same paid-count control behavior to WooCommerce Cart and
Checkout blocks while preserving physical quantities for orders and stock.

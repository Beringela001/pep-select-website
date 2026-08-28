# WEB-M15 — Buy 4 get 1 cart experience

## Outcome

For eligible SKUs, every quantity input keeps the literal physical quantity the
customer selected. At five vials YITH discounts one vial by 100%. WooCommerce
stores, orders, and reduces stock by the same visible physical quantity.

## Live rule dependency

YITH rule 1209 remains the only pricing authority. It is configured as purchase
5, receive 1 at 100% discount. The repeat option must remain enabled so ten
physical vials receive two free-vial discounts.

## Eligibility

Version 1.5.0 mirrors the products selected in rule 1209 by SKU:
GLP3R10, GLP3R20, GLP2T20, GLP1S10, MOTSC10, and GHKCU50.

When rule 1209 eligibility changes, update `pepselect_bogo_skus()` in the same
release so price behavior and physical quantity cannot drift.

## Cart controls

The product page, full WooCommerce Cart block, and Xootix Side Cart display and
save the literal physical quantity. Five stays five, eight stays eight, nine
stays nine, and ten stays ten. Decreasing five to four removes the free-vial
discount; increasing four to five earns it.

Eligible lines also carry a compact cart-only promotion pill. Below five vials
it says "Add 5, one is on us." At five or more it confirms the number of free
vials added. The marker is hidden outside the full Cart and Xootix drawer,
so checkout and order presentation remain unchanged.

Version 1.4.0 contains no quantity-expansion or line-replacement hooks. YITH
calculates discounts but does not reinterpret the customer-entered quantity.

## Order 1560

Order 1560 predates this plugin and remains four vials in WooCommerce. The Ops
release paired with this milestone recognizes the mapped SKU promotion and
posts it as four paid plus one free, consuming five vials. Its Woo review card
also provides an explicit free-vial override for manual exceptions.

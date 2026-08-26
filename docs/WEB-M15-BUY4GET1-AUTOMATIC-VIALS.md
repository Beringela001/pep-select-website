# WEB-M15 — Automatic Buy 4 get 1 vial

## Outcome

For eligible SKUs, selecting four vials adds a fifth vial to the visible cart
before YITH calculates its 100% discount. WooCommerce stores, orders, and
reduces stock by the visible physical quantity while charging for four.

## Live rule dependency

YITH rule 1209 remains the pricing authority. It is configured as purchase 5,
receive 1 at 100% discount. The rule's repeat option must remain enabled so
eight paid vials become ten physical vials with two free.

## Eligibility

Version 1.1.2 mirrors the products selected in rule 1209 by SKU:
GLP3R10, GLP3R20, GLP2T20, GLP1S10, MOTSC10, and GHKCU50.

When rule 1209 eligibility changes, update `pepselect_bogo_skus()` in the same
release so price behavior and physical quantity cannot drift.

## Cart controls

The full WooCommerce Cart block and the Xootix side cart display physical
quantity. Four selected becomes five visible vials. The line identifies the
free vial without exposing internal inventory wording. The product-page selector
turns four selected vials into five. Once in the cart, the controls represent
physical vials: decreasing five to four removes the free vial and increasing
four to five earns it again.

The expansion hook is scoped to the eligible product Add to Cart form. Side
Cart and Cart block edits never pass that marker, preventing a saved quantity
of four from being expanded back to five.

## Order 1560

Order 1560 predates this plugin and remains four vials in WooCommerce. The Ops
release paired with this milestone recognizes the mapped SKU promotion and
posts it as four paid plus one free, consuming five vials. Its Woo review card
also provides an explicit free-vial override for manual exceptions.

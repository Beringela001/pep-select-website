# WEB-M15 — Automatic Buy 4 get 1 vial

## Outcome

The quantity selected by the customer is now the paid quantity. For eligible
SKUs, the dedicated `pepselect-bogo-quantity` plugin adds one physical vial per
four paid before YITH calculates its 100% discount. WooCommerce therefore
stores and reduces stock by the physical quantity.

## Live rule dependency

YITH rule 1209 remains the pricing authority. It is configured as purchase 5,
receive 1 at 100% discount. The rule's repeat option must remain enabled so
eight paid vials become ten physical vials with two free.

## Eligibility

Version 1.0.5 mirrors the products selected in rule 1209 by SKU:
GLP3R10, GLP3R20, GLP2T20, GLP1S10, MOTSC10, and GHKCU50.

When rule 1209 eligibility changes, update `pepselect_bogo_skus()` in the same
release so price behavior and physical quantity cannot drift.

## Cart controls

The full WooCommerce Cart block and the Xootix side cart display the paid
quantity selected by the customer. The free vial remains in the underlying
WooCommerce cart so YITH can price it at 100% off and stock can be reduced
correctly. Increasing or decreasing the visible quantity recalculates the
physical quantity without exposing the extra inventory unit in the controls.

## Order 1560

Order 1560 predates this plugin and remains four vials in WooCommerce. The Ops
release paired with this milestone recognizes the mapped SKU promotion and
posts it as four paid plus one free, consuming five vials. Its Woo review card
also provides an explicit free-vial override for manual exceptions.

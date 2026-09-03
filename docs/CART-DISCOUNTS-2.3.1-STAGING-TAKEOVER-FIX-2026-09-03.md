# Cart Discounts 2.3.1 staging takeover fix

## Scope

- Every active sitewide promotion owns discount priority, including rules previously saved as Allow every discount.
- Buy 4 Get 1 and compound discounts are removed and hidden from product pages, product cards, side cart, cart, and checkout by default.
- Managers may explicitly allow Buy 4 Get 1 or an individual compound rule.
- Existing Woo coupon source/code exceptions and replacement codes remain supported.
- Shipping methods are not changed. Free shipping over $200 remains available.
- Deployment target: staging only. Live is explicitly out of scope until Paulo authorizes it.

## Contract

Sitewide REST schema is version 3. `allowed_automatic_discounts` accepts `bogo` and `compound:<rule-id>` values. `stackable` remains in normalized rule documents for backward compatibility but is always returned as `false`. The contract reports `sitewide_takeover: always_exclusive` and `shipping_policy: unchanged`.

## Verification

- Deployed `pepselect-bogo-quantity` 2.3.1 to staging only and cleared the staging cache.
- Active legacy `LABORDAY20` normalized to `exclusive · no exceptions` in the WordPress manager.
- Product page and related-product cards show only the 20% sitewide sale price; no Buy 4 Get 1 pill appears.
- Cart with seven qualifying items retains only `LABORDAY20`; no BOGO or compound coupon/pill appears.
- Checkout retains only `LABORDAY20` and still offers Free shipping at a subtotal above $200.
- Direct unauthenticated access to the Ops REST endpoint returns HTTP 401 by design. Authenticated WordPress admin controls remain available.
- PHP syntax checks and the sitewide, stacking, BOGO, compound, and JavaScript regression suites pass.

## Release artifact

- `dist/pepselect-bogo-quantity-2.3.1.zip`
- SHA-256: `D25E9B4432335661E96BFCFB85909A99BC9C398E729CDB5A4AEBC0FA0BBFD974`

Live was not changed.

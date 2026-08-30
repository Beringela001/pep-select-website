# Pass 3 commerce plugin maintenance

Date: August 30, 2026

## Outcome

- Updated the approved commerce plugin group on staging first, then Live.
- Preserved products, customers, orders, payment behavior, shipping configuration, rewards, BOGO rules, COA behavior, and the access gate.
- Left Elementor, Side Cart Free, analytics, marketing, SEO, ACF, YITH, and inactive channel plugins outside this pass.

## Versions

| Plugin | Before | After |
| --- | ---: | ---: |
| Back In Stock Notifier for WooCommerce | 7.2.2 | 7.4.1 |
| Fluid Checkout Lite | 4.2.4 | 4.2.7 |
| Fluid Checkout Pro | 4.0.4 | 4.0.6 |
| Google Address Autocomplete for WooCommerce | 2.2.8 | 2.3.0 |
| VerifyPass | 5.0.1 Live / 5.0.2 staging | 5.0.2 |
| WooCommerce | 10.9.4 | 11.0.1 |

Fluid Checkout's required database migration completed successfully on both environments.

## Backups and deployment

- Staging rollback point: `Before Pass 2.5 performance beta68 staging - 2026-08-29`, created Aug 29 at 11:27 PM.
- Live rollback point: `Before Pass 2.5 performance beta68 live - 2026-08-29`, created Aug 29 at 11:36 PM.
- Both existing backups were created after the previous release and before Pass 3, so no backup was deleted or replaced.
- Each plugin was updated individually. WooCommerce was updated last.

## Verification

- Confirmed all six target versions on staging and Live.
- Product pages rendered with inventory, BOGO messaging, pricing, and batch-report links.
- Add-to-cart and the premium side cart worked with totals and checkout controls.
- Checkout rendered contact and address fields, research acknowledgments, order summary, Square payment instructions, and Place Your Order. No order was submitted.
- Easyship returned USPS, FedEx, and UPS rates on staging and Live.
- Rewards balance, projected cash back, and redemption controls rendered.
- Back In Stock displayed its email, consent, and submit controls for unavailable products. No subscription or email was sent.
- Live browser console errors: zero.
- Live post-update logs contained no cart-recovery parse errors and no continuing plugin-update fatal errors.

Two transient admin requests failed while WordPress was replacing plugin directories: one during the staging WooCommerce update and one during the Live Address Autocomplete/VerifyPass sequence. Both stopped immediately after installation. Subsequent admin, product, cart, checkout, shipping, and stock-alert requests completed normally.

The existing Easyship undefined-setting warnings remain. Shipping rates continue to calculate correctly; the warnings predate Pass 3 and were not introduced by these updates.

## Rollback

Restore the named Live Kinsta backup if a delayed commerce regression appears.

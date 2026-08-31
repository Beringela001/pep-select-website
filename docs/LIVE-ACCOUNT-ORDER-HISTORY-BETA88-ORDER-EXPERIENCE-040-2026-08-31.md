# Live account order history release — 2026-08-31

## Release

- Child theme: `0.25.0-beta.88`
- Order Experience: `0.4.0`
- Theme package: `dist/pepselect-child-0.25.0-beta.88.zip`
- Theme SHA-256: `CC97917905D34F8FADBD5D90805AA576A1FB7EF6A921CA78CAEA10C27E7613C9`
- Plugin package: `dist/pepselect-order-experience-0.4.0.zip`
- Plugin SHA-256: `0B86BBFD244B7959A559A3D7AE0DC1CDA0CBF763FC1CF05EBA59A05C9FB1257C`

## Behavior

- My Account shows five compact recent orders and keeps older orders behind an accessible disclosure control.
- Each full row opens the secure order page when its immutable Ops snapshot exists; otherwise it falls back to WooCommerce's native order view.
- Tracking numbers open the resolved carrier page independently from the order-row link.
- Customer-facing statuses map WooCommerce and shipment state to Waiting for payment, Being prepared, In transit, and Completed.
- Deactivating Order Experience preserves the account list and native WooCommerce order links.

## Verification

- PHP syntax passed for all changed PHP files.
- Order Experience account, Milestone 2, Milestone 4, relationship-engine, and security contracts passed.
- Staging diagnostic: plugin `0.4.0`, feature enabled, WooCommerce available, permanent order page published, access-record table ready.
- Live diagnostic: plugin `0.4.0`, feature enabled, access-record table ready.
- Staging and Live account pages loaded without PHP errors; caches were refreshed after deployment.
- The approved populated-state fixture was verified at desktop and 390px mobile widths, including the expanded 20-order state without overlap.

## Rollback

- Staging backup: `Before account order history beta88 and Order Experience 0.4.0 staging - 2026-08-31`
- Live backup: `Before account order history beta88 and Order Experience 0.4.0 live - 2026-08-31`
- Code rollback: restore the named environment backup or reinstall the previous verified theme and Order Experience packages, then clear Kinsta caches.

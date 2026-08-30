# Live email footer and mobile QA — 2026-08-30

## Release

- Theme: `dist/pepselect-child-0.25.0-beta.78.zip`
- Theme SHA-256: `234B9017B14208A3295DBDC95A58E5B24E12FC65DC85A53DF17B710683FD4B4A`
- Cart recovery plugin: `dist/pepselect-cart-recovery-0.4.4.zip`
- Plugin SHA-256: `C099CB8D58CE307ED984952C21E7A27D1A188DD452BEC609ED7DFE1300449778`
- Source commit: `bda543a`

## Backup

- Environment: Kinsta Live
- Verified the manual-backup list was full before deployment.
- Deleted only the oldest bottom entry: `Before American ownership and ship-from trust copy live - 2026-08-30`, created Aug 30, 2026 at 3:25 PM.
- Created: `Before email footer mobile QA beta77 and cart recovery 0.4.4 - 2026-08-30`, Aug 30, 2026 at 7:11 PM.

## Deployment

- Staging and Live both report `Pep Select` as the active theme after installing beta.78.
- Live reports `Pep Select Cart Recovery` active at version `0.4.4`.
- Kinsta caches were cleared after the Live update.
- Beta.78 closes the one issue found during the first Live pass: the admin new-order email now uses the same complete company footer as every other WooCommerce email.

## Mobile verification

The actual WooCommerce preview endpoint was rendered at a 390 px mobile viewport for all 21 registered email types. Every preview had:

- a 390 px document width with no horizontal page overflow;
- no visible element crossing the viewport boundary;
- the approved American-owned and operated company line;
- website, street address, support email, and phone number.

Verified types: new order, both cancelled-order notices, both failed-order notices, on-hold, processing, completed, refunded, order details, customer note, reset password, new account, payment-gateway enabled, both POS notices, both back-in-stock notices, email confirmation, expiring points, and updated points.

Cart recovery remains protected at final send by plugin version 0.4.4: it removes the superseded light footer, replaces the outdated human-support sentence, appends the shared company footer, and injects narrow-screen rules for the generated cart-product table. The four resolved cart-recovery presentation samples were also checked at 390 px with no horizontal overflow.

## Validation

- `tests/test-customer-email-footers.js`: pass
- `tests/test-american-ownership-trust-copy.js`: pass
- PHP syntax check for `woocommerce/emails/admin-new-order.php`: pass


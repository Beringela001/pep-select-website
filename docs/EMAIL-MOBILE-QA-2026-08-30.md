# Email Mobile QA — 2026-08-30

Release gate: no email may create horizontal page overflow or crop a cart/order product list at a 390 px mobile viewport.

## Covered email families

- WooCommerce shared renderer: admin order alerts; customer cancelled, failed, on-hold, processing, completed, refunded, order details, customer note, reset-password, new-account, POS, points, and email-confirmation messages.
- Pep Select custom WooCommerce canvases: new account, payment required, processing, shipped/completed, admin new order, and back-in-stock.
- Cart recovery: signup coupon, 90-minute reminder, 24-hour reminder, and 48-hour reminder with the additional coupon.
- FluentCRM marketing: educational, GHK-CU/NAD+, Labor Day VIP, and Labor Day public campaign layouts.

## Automated and visual checks

- The four cart-recovery presentation states report zero overflowing descendants at mobile width.
- The account-created and payment-required mobile canvases report zero overflowing descendants.
- All four newsletter pages remain within the viewport; the small flag graphic keeps its fixed print-safe size.
- The shared WooCommerce email stylesheet constrains wrappers, media, order tables, links, and long values on screens up to 600 px.
- The recovery integration wraps database-authored email bodies, removes the superseded light footer, appends the shared company footer, and constrains injected product tables on screens up to 520 px.

## Deployment verification still required

- After staging installation, preview the actual WooCommerce and Cart Abandonment Recovery output at mobile width with populated order/cart data.
- Confirm product name, image, quantity, and price remain visible without horizontal scrolling.
- Repeat the same checks on Live after backup, deployment, and cache clearing.

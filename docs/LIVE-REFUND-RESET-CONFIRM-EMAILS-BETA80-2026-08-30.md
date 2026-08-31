# Live release — refund and account-security emails

Released August 30, 2026 as part of the combined Pep Select child-theme build `0.25.0-beta.80`.

## Delivered

- Refunded-order email with distinct full-refund and partial-refund language.
- Reset-password email using WooCommerce's signed password-reset URL.
- Confirm-email-address message using WooCommerce's verification URL.
- Matching plain-text templates for all three messages.
- Shared Pep Select HTML canvas, company footer, and narrow-screen safeguards.
- WooCommerce remains authoritative for refund amounts, order details, recipients, and account links.

## Verification

- All repository JavaScript safeguards passed.
- All seven new PHP templates passed syntax validation.
- Staging WooCommerce previews passed at desktop and mobile widths.
- The staging refund preview exposed and then verified fixes for compressed table headings and horizontal overflow.
- Live WooCommerce previews verified all three messages and their CTA destinations at mobile width.
- The combined release preserved the concurrently approved account login and registration changes included in `beta.80`.

## Deployment record

- Package: `dist/pepselect-child-0.25.0-beta.80.zip`
- SHA-256: `1B7025E2D8F137EC37E97071C203B1E1BFFF9E213B513C29CD6A22DD61884D7F`
- Pre-deployment backup: `Before refund reset confirm emails and account beta80 - 2026-08-30`
- The previously verified oldest manual backup, `Before Cart Recovery 0.3.0 live - 2026-08-30` from August 30 at 3:51 PM, was removed to create capacity.
- Live active theme: Pep Select `0.25.0-beta.80`.

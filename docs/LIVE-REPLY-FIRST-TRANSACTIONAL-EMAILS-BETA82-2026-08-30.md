# Live reply-first transactional emails — beta.82

**Deployed:** 2026-08-30  
**Environment:** Live  
**Theme:** Pep Select `0.25.0-beta.82`

## Change

- Replaced the refund, reset-password, and confirm-email support copy with: `Have a question? Reply to this email, and one of our team members will be in touch shortly.`
- Removed the refund email's separate contact button, order-number request, and duplicate WooCommerce additional content.
- Kept the reset-password and email-confirmation security instructions and signed links unchanged.
- Applied the same reply instruction to the matching plain-text templates.

## Verification

- PHP syntax checks passed for all six HTML and plain-text templates.
- All 20 repository tests passed.
- Live WooCommerce mobile previews were inspected for Refunded order, Reset password, and Confirm email address.
- The three live previews show the approved reply instruction without the old order-number prompt or generic additional content.
- Active Live theme confirmed as `0.25.0-beta.82`.

## Backup and package

- Deleted the confirmed oldest manual backup: `Before prominent American ownership beta73 live - 2026-08-30` (Aug 30, 2026, 4:15 PM).
- Created: `Before reply-first transactional email copy beta82 - 2026-08-30` (Aug 30, 2026, 8:11 PM).
- Package: `dist/pepselect-child-0.25.0-beta.82.zip`
- SHA-256: `017D31DF36BBA4CEDEC21DDC01C1BE25EB20DFACDFA2B62CD70676614DD03438`
- Staging was skipped at Paulo's direction for this copy-only correction.


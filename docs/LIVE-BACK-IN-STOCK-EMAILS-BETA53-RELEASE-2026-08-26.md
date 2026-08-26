# Live Back-in-Stock Email Release — 0.25.0-beta.53

## Scope

- Replaced the Back In Stock Notifier subscription-confirmation and product-available email layouts with the approved Pep Select customer-email design.
- Added warm, plain-language copy, responsive product cards, and matching plain-text fallbacks.
- Preserved the notifier plugin as the authority for subscriptions, stock events, recipients, and delivery.
- Kept customer replies routed to `support@pepselect.com`.

## Package

- File: `dist/pepselect-child-0.25.0-beta.53.zip`
- SHA-256: `17B06C09F5C06BD6E6BA0B7F18CA3CADB46B4DE379E0F6A58D69082D3A6E0663`

## Backup and deployment

- Live manual-backup capacity was 4 of 5, so no backup deletion was required.
- Created: `Before back-in-stock email refresh 0.25.0-beta.53 live deployment - 2026-08-26`.
- Replaced Live child theme `0.25.0-beta.52` with `0.25.0-beta.53` through WordPress.
- WordPress confirmed `Theme updated successfully` and `Active Theme — Pep Select Version: 0.25.0-beta.53`.
- Cleared all Kinsta caches from WordPress after deployment.

## Live verification

- WooCommerce recognizes the child-theme overrides for both `Back In Stock - Subscription Confirmation` and `Back In Stock - Product Available`.
- Both notifications remain enabled and their Live HTML previews render the new Pep Select email canvas and approved copy.
- The subscription preview includes `We'll keep an eye on it`, `Stock watch is on`, and `You can give the refresh button a rest.`
- The availability preview includes `Good news. It's back.`, the available product state, and the product call to action.
- Both previews display `Pep Select <support@pepselect.com>` and the support reply address in the message body.
- No test email was sent and no Live product stock was changed during verification.

## Rollback

Restore the pre-deployment Live backup or reinstall the prior `0.25.0-beta.52` child-theme package.

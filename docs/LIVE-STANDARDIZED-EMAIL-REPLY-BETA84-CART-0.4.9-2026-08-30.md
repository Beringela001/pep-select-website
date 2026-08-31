# Live standardized customer email reply copy — 2026-08-30

## Release

- Pep Select child theme: `0.25.0-beta.84`
- Pep Select Cart Recovery: `0.4.9`
- Theme package: `dist/pepselect-child-0.25.0-beta.84.zip`
- Theme SHA-256: `76530942BB786E6DE5E67EEF5368F9C9DC126B92057CD1FC39209605405C0D42`
- Plugin package: `dist/pepselect-cart-recovery-0.4.9.zip`
- Plugin SHA-256: `CDF4DC3A819E0D5E8E5BEE23A6128AB9703D1EC65264DE8D7B46DCF306C240F8`

## Change

All previously treated customer-facing email workflows now use the approved support line:

`Have a question? Reply to this email, and one of our team members will be in touch shortly.`

The covered workflows are new account, payment/order received, processing order, completed/shipped order, refund, reset password, confirm email address, stock subscription, back-in-stock notice, immediate signup-code email, and the 90-minute, 24-hour, and 48-hour saved-cart reminders.

Account-security instructions, order actions, tracking actions, unsubscribe controls, and company footer information remain intact. Cart Recovery normalizes older database-authored reminder wording at send time and renders the approved line independently of stale saved support copy.

## Verification

- PHP syntax checks passed for all modified PHP templates and Cart Recovery.
- Repository customer-email suite: 21/21 passed.
- Cart Recovery contract suite: 1/1 passed.
- Staging verified Pep Select `0.25.0-beta.84` active and Cart Recovery send-time normalization working before Live deployment.
- Live verified Pep Select `0.25.0-beta.84` active.
- Live verified Pep Select Cart Recovery `0.4.9` active.
- Live Kinsta caches cleared successfully.

## Backup and rollback

- Deleted only the verified oldest manual backup after explicit confirmation: `Before email footer mobile QA beta77 and cart recovery 0.4.4 - 2026-08-30`, created Aug 30, 2026 at 7:11 PM.
- Created: `Before standardized reply support beta84 and cart recovery 0.4.9 - 2026-08-30`.
- Roll back by restoring that named backup, or reinstalling the prior verified theme/plugin packages and clearing all Kinsta caches.

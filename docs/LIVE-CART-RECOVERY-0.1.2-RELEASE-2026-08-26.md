# Pep Select Cart Recovery 0.1.2 Live Release

Release date: 2026-08-26

## Scope

- Deploy the Pep Select Stay in the Loop exit offer.
- Connect captured email addresses to WooCommerce cart recovery and FluentCRM.
- Replace the three stock recovery emails with the approved Pep Select designs and copy.
- Upgrade the signup coupon from 10% to 15% in the 48-hour recovery email.
- Make `WELCOME10` stackable with a $100 minimum order.

## Rollback point

- Kinsta Live manual backup: `Before Pep Select cart recovery 0.1.1 live deployment - 2026-08-26`.
- The manual-backup list was full. The oldest backup at the bottom was verified and removed before this backup was created.

## Package

- Plugin: Pep Select Cart Recovery 0.1.2
- Artifact: `dist/pepselect-cart-recovery-0.1.2.zip`
- SHA-256: `DB5E6978C93D31434262890960B2F494BDBFBED716B03A5BA68DC6451B3667C0`
- Git commit: `87eb414`

## Live configuration

- Public exit offer: enabled.
- Coupon expiry: 7 days.
- Dismiss cooldown: 30 days.
- FluentCRM list: `Pep Select Offers`, ID 5.
- Final recovery template ID: 3.
- Sender and reply-to: `support@pepselect.com`.
- Cart-abandonment cutoff: 60 minutes.
- Email 1: 30 minutes after abandonment, 90 minutes after cart activity.
- Email 2: 23 hours after abandonment, 24 hours after cart activity.
- Email 3: 47 hours after abandonment, 48 hours after cart activity.
- All three templates are enabled and use the approved custom HTML instead of the stock WooCommerce email wrapper.

## Coupon behavior

- Exit-offer coupons are unique, email-restricted, stackable, and initially worth 10%.
- The same exit-offer coupon changes to 15% when recovery template ID 3 sends.
- Carts without an exit-offer signup coupon do not receive the 48-hour upgrade email.
- `WELCOME10` remains 10%, now has a $100 minimum order, and is stackable.

## Verification

- Contract test passed.
- Plugin is active in Live WordPress as version 0.1.2.
- Plugin settings survived the restore: enabled, list ID 5, final template ID 3.
- Recovery templates are enabled at 30 minutes, 23 hours, and 47 hours after abandonment.
- `WELCOME10` persisted with a $100 minimum and `Individual use only` disabled.
- Public unauthenticated HTML contains the approved popup copy.
- Live 0.1.2 CSS contains the hidden honeypot rule.
- Live 0.1.2 JavaScript contains the 15-second desktop exit-intent and 45-second/55% mobile trigger behavior.
- WordPress and Kinsta caches were cleared during release.
- No test customer, coupon, cart, order, or email subscription was created.
- The temporary Code Snippets deployment helper and its one-time restore snippet were deleted after use.

## Rollback

Restore the named Kinsta manual backup if the complete release must be reverted. For a plugin-only rollback, deactivate Pep Select Cart Recovery and disable the three recovery templates before investigating.

# Pep Select Exit Offer 0.1.3

Release date: 2026-08-28

## Scope

- Make desktop exit intent reliable across normal browser cursor behavior.
- Preserve the 15-second engagement delay, one-day live dismissal cooldown, administrator exclusion, and existing mobile trigger.

## Change

- Expand the recognized desktop top edge from 8 pixels to 40 pixels.
- Listen for both document mouseout and document-element mouseleave.
- Preserve a qualifying desktop exit that happens before eligibility, then show the offer when the 15-second delay completes.

## Non-goals

- No changes to coupons, email capture, FluentCRM, cart recovery, checkout, mobile timing, customer eligibility, or copy.

## Package

- Artifact: `dist/pepselect-cart-recovery-0.1.3.zip`
- SHA-256: `40CA8EB5E871AF5673D9B58F6E319F78A66A250DD1ECD7B952805870D8162CDC`

## Verification

- Run the plugin contract test.
- Verify the package contains version 0.1.3.
- Test logged-out desktop behavior after 15 seconds.
- Confirm logged-in WooCommerce administrators remain excluded.
- Confirm mobile still requires 45 seconds and 55% scroll depth.

## Rollback

- Reinstall Pep Select Cart Recovery 0.1.2 or restore the pre-deployment Kinsta backup.

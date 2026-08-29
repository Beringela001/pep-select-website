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

- Plugin contract test passed.
- JavaScript syntax check and PHP lint passed.
- Live homepage serves the 0.1.3 CSS and JavaScript assets.
- Clean logged-out desktop test opened the offer after 15 seconds and a real top-edge cursor exit.
- Administrator exclusion remains enforced by the existing PHP eligibility check.
- Mobile behavior remains unchanged at 45 seconds and 55% scroll depth.
- Live rollback point: `Before exit offer 0.1.3 live deployment - 2026-08-28`.

## Rollback

- Reinstall Pep Select Cart Recovery 0.1.2 or restore `Before exit offer 0.1.3 live deployment - 2026-08-28` in Kinsta.

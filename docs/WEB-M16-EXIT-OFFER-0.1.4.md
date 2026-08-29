# Pep Select Exit Offer 0.1.4

Release date: 2026-08-28

## Scope

- Center the exit-offer dialog on desktop and mobile.
- Replace the incorrectly inherited opaque backdrop with a 50% translucent navy overlay.

## Change

- Center the dialog with a viewport grid instead of bottom-right positioning.
- Lock the full-screen veil to `rgba(0, 29, 58, 0.5)` with component-level overrides so theme button styles cannot replace it.
- Preserve the offer content, form behavior, eligibility, cooldown, tracking, and trigger timing.

## Package

- Artifact: `dist/pepselect-cart-recovery-0.1.4.zip`
- SHA-256: `452DD1866241DAC944445182187884E67C57E090A806C74F5A61B38764B89553`.

## Verification

- Plugin contract test, JavaScript syntax check, and PHP lint passed.
- Live homepage serves the 0.1.4 CSS and JavaScript assets.
- Clean logged-out desktop test opened the offer after the 15-second exit trigger.
- Live screenshot confirmed the dialog is centered and the page remains visible through the 50% backdrop.
- Mobile centering and spacing are enforced by the responsive CSS contract.
- Live rollback point: `Before exit offer 0.1.4 live deployment - 2026-08-28`.

## Rollback

- Reinstall Pep Select Cart Recovery 0.1.3 or restore `Before exit offer 0.1.4 live deployment - 2026-08-28` in Kinsta.

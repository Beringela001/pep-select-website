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

- Run the plugin contract test, JavaScript syntax check, and PHP lint.
- Confirm the dialog is centered on desktop and mobile.
- Confirm the page remains visible through the 50% backdrop.
- Confirm close behavior and the 15-second desktop exit trigger still work.

## Rollback

- Reinstall Pep Select Cart Recovery 0.1.3 or restore the named pre-deployment Kinsta backup.

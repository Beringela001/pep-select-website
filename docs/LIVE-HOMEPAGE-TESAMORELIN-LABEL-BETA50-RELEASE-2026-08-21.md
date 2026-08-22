# Live homepage Tesamorelin label release — 0.25.0-beta.50

## Scope

- Replaced only the visible label artwork on the left Tesamorelin vial in the homepage “Match the batch. Match the vial.” education section.
- Preserved the original base vial photograph, right-vial reverse label, batch number, QR code, COA source image, callouts, copy, layout, typography, logo treatment, and green strength pill.
- Delivered the new label as a small decorative overlay so the unrelated artwork remains byte-for-byte unchanged.

## Package

- Artifact: `dist/pepselect-child-0.25.0-beta.50.zip`
- SHA-256: `E518F80770CB29526632B0675292CE9FD9E6CC507EA7E71D1866A20F9FAE4581`
- Source commit: `45d2721 Update homepage Tesamorelin label artwork`

## Live deployment

- Environment verified: Pep Select → Live.
- Manual-backup capacity was available, so no additional deletion was required for this release.
- Rollback backup created: `Before homepage Tesamorelin label 0.25.0-beta.50 live deployment - 2026-08-21`.
- Backup timestamp: Aug 21, 2026, 11:24 PM.
- Existing theme: `0.25.0-beta.49`.
- Uploaded and activated theme: `0.25.0-beta.50`.
- WordPress confirmed `Active Theme — Pep Select Version: 0.25.0-beta.50`.
- Kinsta confirmed: `All caches were cleared. Changes usually appear globally within a few minutes.`

## Verification

- Desktop viewport: 1920 × 855.
- Mobile viewport: 390 × 844.
- Original base image loaded at 1200 × 900.
- New overlay loaded at 280 × 343.
- Overlay placement remained identical at both breakpoints: left 21.5%, top 42.89%, width 23.33%, height 38.11% relative to the original vial image.
- No horizontal overflow detected on desktop or mobile.
- Visual inspection passed on both breakpoints; the right vial, batch/QR, COA, and callouts remained intact.
- Existing unrelated Elementor console error remains: `elementorFrontendConfig is not defined` from Elementor 4.1.5. This release did not modify Elementor or introduce that error.

## Source safeguards

- Original vial-photo SHA-256: `9A21BB574A830F884FAEF1877C59BBF5643370DBF20275767AE100F6CC730903`.
- Original COA-image SHA-256: `41C065627EDC1315CB8D243CB26ACA707B9ED0FE67D76341B920502FC6841451`.
- Regression and performance asset tests passed before packaging.

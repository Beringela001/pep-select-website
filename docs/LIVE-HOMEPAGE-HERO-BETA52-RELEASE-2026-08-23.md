# Live Homepage Hero Release — 0.25.0-beta.52

## Scope

- Replaced the prior composited homepage hero with the approved finished vial artwork supplied as `website_hero.png`.
- Preserved homepage copy, layout, calls to action, responsive behavior, WooCommerce, COA, account, checkout, payment, shipping, rewards, and inventory behavior.
- Regenerated the existing seven responsive WebP candidates at 320, 480, 640, 768, 1024, 1536, and 2048 pixels.

## Package

- File: `dist/pepselect-child-0.25.0-beta.52.zip`
- SHA-256: `19DD5A9F20C70B7AC6A4C67F05ADBD3A60C4F30F66566539E90A40F52DAD462D`

## Backup and deployment

- Live manual-backup capacity was 3 of 5, so no backup deletion was required.
- Created: `Before homepage hero artwork 0.25.0-beta.52 live deployment - 2026-08-23`.
- Replaced Live child theme `0.25.0-beta.50` with `0.25.0-beta.52` through WordPress.
- WordPress confirmed `Theme updated successfully` and `Active Theme — Pep Select Version: 0.25.0-beta.52`.
- Cleared all Kinsta caches from WordPress after deployment.

## Live verification

- Desktop and mobile homepage views render the approved logo-and-vial artwork.
- The hero remains visible with its existing accessible alt text and seven-candidate WebP source set.
- Mobile verification at the available narrow Chrome viewport showed no horizontal page overflow.
- Existing hero copy and both calls to action remain present.
- Chrome reported an existing Elementor `elementorFrontendConfig` console error; this image-only release did not change Elementor or JavaScript.

## Rollback

Restore the named Kinsta backup above or reinstall the prior `0.25.0-beta.50` child-theme package.

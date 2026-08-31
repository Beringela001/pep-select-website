# Live account spacing refinement — beta.86

Date: 2026-08-30  
Theme: Pep Select `0.25.0-beta.86`

## Release

- Package: `dist/pepselect-child-0.25.0-beta.86.zip`
- Size: 2,704,542 bytes
- SHA-256: `965BD77CE59BD5A4182451AD7B08866B42C6115996095F59B842335FB059757D`
- Commits: `5b69d1a`, `79a5e3c`, `1a06b99`

## Delivered

- Replaced the tinted logged-out account background with white.
- Removed WooCommerce's inherited 64px logged-out wrapper gap.
- Set the final menu-to-card spacing to 24px on desktop and 16px on mobile.
- Centered the Google sign-in control in both login and registration views.

## Deployment and verification

- Direct Live deployment was explicitly authorized.
- Created Live backup `Before account page spacing beta85 live - 2026-08-30`; it covers the beta.85 and beta.86 refinements.
- Deleted only the verified oldest bottom manual backup: `Before refund reset confirm emails and account beta80 - 2026-08-30` (Aug 30, 2026, 8:02 PM).
- Installed beta.85, inspected the inherited wrapper spacing, then installed final beta.86 and cleared Kinsta caches.
- Confirmed beta.86 is the active Live theme.
- Live desktop verification measured a 24px menu-to-card gap; mobile measured 16px at 390 × 844.
- White background, centered Google controls in both panels, no date-of-birth field, and no horizontal overflow were verified.

## Rollback

Restore `Before account page spacing beta85 live - 2026-08-30`, or reinstall `dist/pepselect-child-0.25.0-beta.84.zip`, then clear Kinsta caches.

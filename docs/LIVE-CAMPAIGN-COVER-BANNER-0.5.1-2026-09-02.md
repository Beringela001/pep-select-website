# Campaign Cover Banner 0.5.1 release

Date: 2026-09-02  
Branch: `codex/seo-m4-content`  
Source commit: `2623d24 Make campaign cover fill first screen`

## Outcome

- Added two Campaign Cover Banner display modes: **Fill screen (recommended)** and **Show full image**.
- The banner fills the space between the navigation and the bottom of the first browser view; the existing homepage cover starts after the first scroll.
- The height recalculates when the browser is resized or a mobile device changes orientation.
- Desktop and mobile image masters remain separate. Recommended sizes are 1920 x 1080 and 1080 x 1920.
- Existing campaign, popup, email, and recovery-code settings were preserved.

## Package

- File: `dist/pepselect-cart-recovery-0.5.1.zip`
- SHA-256: `7B9555A669EAA0E88E5447A2F72DD04B6271F574746D981C85EC9E28AE35CB52`

## Staging

- Deleted only the verified oldest manual backup to make room: `Before coded About page beta69 staging - 2026-08-30`.
- Created rollback point: `Before Campaign Cover Banner 0.5.1 staging - 2026-09-02` (Sep 2, 2026, 11:45 AM).
- Updated the plugin from 0.5.0 to 0.5.1.
- Verified both modes save and survive reload. Returned the saved mode to Fill screen.
- Verified the active scheduled banner ends exactly at the desktop viewport bottom at 1440 x 900 and has no horizontal overflow.
- Verified Show full image at 390 x 844 and 1440 x 900, including live resizing without reload and no horizontal overflow.
- Cleared all Kinsta caches. Browser console check returned no errors.

## Live

- Deleted only the verified oldest manual backup to make room: `Before Trustpilot customer cooldown and catch-up 0.2.0 - 2026-09-01`.
- Created rollback point: `Before Campaign Cover Banner 0.5.1 live - 2026-09-02` (Sep 2, 2026, 11:50 AM).
- Updated the plugin from 0.5.0 to 0.5.1.
- Preserved the Campaign Cover Banner's disabled state and all stored campaign fields; no promotion was published by the deployment.
- Verified the two display options and updated desktop/mobile preview are present.
- Verified neighboring Exit Popup settings remained intact, including the stored 15% offer, one use per email, and non-stackable generated coupons.
- Cleared all Kinsta caches.
- Verified the public homepage at 390 x 844 and 1440 x 900 with zero horizontal overflow and no browser-console errors.

## Automated checks

- Plugin contract tests passed.
- First-screen measurement and resize tests passed.
- Public and admin JavaScript syntax checks passed.
- PHP syntax check passed.
- Git diff whitespace check passed.

## Rollback

Restore the named environment-specific manual backup in MyKinsta. The two deleted capacity backups were permanently removed and cannot be recovered.

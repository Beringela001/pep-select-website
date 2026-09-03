# Campaign Cover Banner 0.5.2 release

Date: 2026-09-03  
Branch: `codex/seo-m4-content`  
Source commit: `81f51ee Make campaign cover banner clickable`

## Outcome

- Added an optional Banner destination field to Campaign Cover Banner.
- When a destination is present, the entire visible banner is a same-tab link with keyboard focus styling and an accessible label.
- When the destination is blank, the banner remains non-clickable.
- The admin preview identifies when the banner is clickable and shows the destination.
- Existing popup, email, coupon, schedule, image, layout, and campaign settings were preserved.

## Package

- File: `dist/pepselect-cart-recovery-0.5.2.zip`
- SHA-256: `11590703CD6B29BE5449DDC6979C912978C6B9C5977DF7B2B08F48911DA74F15`

## Staging

- Deleted only the verified oldest manual backup to make room: `Before account login redesign beta80 staging - 2026-08-30` (Aug 30, 2026, 8:01 PM).
- Created rollback point: `Before Campaign Cover Banner 0.5.2 staging - 2026-09-03` (Sep 3, 2026, 11:28 AM).
- Updated the plugin from 0.5.1 to 0.5.2.
- Verified `/shop/` saves, survives reload, renders the whole campaign banner as a link, and navigates to the staging shop.
- Restored Banner destination to blank and verified the public banner returned to a non-clickable section.
- Preserved the active schedule, Fill screen mode, desktop image, focal points, colors, and all other stored settings.
- Verified the active banner at 390 x 844 with no horizontal overflow and no browser-console errors.
- Cleared all Kinsta caches.

## Live

- Deleted only the verified oldest manual backup to make room: `Before Trustpilot customer selector and block test 0.4.0 - 2026-09-01` (Sep 1, 2026, 7:10 PM).
- Created rollback point: `Before Campaign Cover Banner 0.5.2 live - 2026-09-03` (Sep 3, 2026, 11:34 AM).
- Updated the plugin from 0.5.1 to 0.5.2.
- Preserved the Campaign Cover Banner's disabled state, blank destination, Fill screen mode, and all stored campaign fields; no promotion was published by the deployment.
- Verified neighboring Exit Popup settings remained intact: 15% offer, one use per coupon, one use per email, and non-stackable generated coupons.
- Cleared all Kinsta caches.
- Verified the public homepage and shop load without horizontal overflow or browser-console errors, including the homepage at 390 x 844.

## Automated checks

- Plugin contract tests passed.
- First-screen banner tests passed.
- Public and admin JavaScript syntax checks passed.
- PHP syntax check passed.
- Git diff whitespace check passed.

## Rollback

Restore the named environment-specific manual backup in MyKinsta. The two deleted capacity backups were permanently removed and cannot be recovered.

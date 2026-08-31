# Live account login redesign — beta.83

Date: 2026-08-30  
Theme: Pep Select `0.25.0-beta.83`

## Release artifact

- Package: `dist/pepselect-child-0.25.0-beta.83.zip`
- Size: 2,704,273 bytes
- SHA-256: `7C88065A4AE4DF0AF8A57D3876941E23459D007B6B7F06CC3D2380CBE4D53586`
- Account implementation commits: `d04389e`, `7a74ae8`, `84b562f`, `f8b58a6`

## Delivered

- Replaced the competing logged-out login and registration columns with one centered account card and accessible tabs.
- Removed the optional YITH date-of-birth field from registration without removing other WooCommerce registration hooks.
- Increased the Google sign-in popup configuration to 820 × 720 on desktop.
- Removed WooCommerce's nested form card, prevented global button styles from recoloring the account tabs, and removed horizontal overflow.

## Backups and deployment

- Staging rollback backup: `Before account login redesign beta80 staging - 2026-08-30`.
- Live rollback backup: `Before account login redesign beta83 live - 2026-08-30`.
- Deleted only the verified oldest bottom manual staging backup: `Before Easyship 0.9.16 staging update - 2026-08-29`.
- Deleted only the verified oldest bottom manual Live backup: `Before Popup Admin 0.4.0 live - 2026-08-30` (Aug 30, 2026, 4:18 PM).
- Installed the exact beta.83 package on staging first, then on Live, and cleared Kinsta caches after each installation.
- Confirmed Pep Select `0.25.0-beta.83` is the active Live child theme.

## Verification

- Desktop and 390 × 844 mobile checks passed on staging and Live.
- Login and registration tabs switch correctly; keyboard arrow navigation also switches tabs.
- Login form, registration form, submit controls, and Google controls remain available.
- Registration contains only email and password fields; no date-of-birth field is rendered.
- The active tab remains transparent with the Pep Select navy label and cyan indicator.
- The WooCommerce form surface computes to transparent, zero border, and zero padding.
- Document scroll width equals client width at desktop and mobile sizes.
- Google sign-in links report popup dimensions of 820 × 720.
- Account safeguard tests, repository JavaScript safeguards, JavaScript syntax, CSS balance, and Git diff checks passed. PHP syntax checks passed before the CSS-only staging refinements; the PHP files did not change afterward.

## Rollback

Restore `Before account login redesign beta83 live - 2026-08-30`, or reinstall `dist/pepselect-child-0.25.0-beta.82.zip`, then clear Kinsta caches.

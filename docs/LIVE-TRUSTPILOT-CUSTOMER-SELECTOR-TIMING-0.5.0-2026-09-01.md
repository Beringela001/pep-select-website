# Live Trustpilot Customer Selector and Timing 0.5.0 — 2026-09-01

## Outcome

- Upgraded `Pep Select Trustpilot Review Invitations` from 0.3.0 through 0.4.0 to 0.5.0 on Live.
- Added admin-only email suggestions from WordPress users, WooCommerce customers, and recent order billing emails.
- Preserved manual entry for any valid email address.
- Added a `Test block` control that runs the same exclusion guard used by scheduled invitations without calling the mail sender.
- Added a `Send after` setting from 1–60 days, defaulting to 7 days.
- Saving the delay recalculates invitations that are still pending from their original WooCommerce completion timestamps.
- Existing 180-day customer cooldown, catch-up, signed opt-out, branded email, Trustpilot destination, and inactive official Trustpilot sender remain unchanged.

## Verification

- Static invitation checks passed.
- WordPress reported successful updates from 0.3.0 to 0.4.0 and from 0.4.0 to 0.5.0.
- Live plugin list verified version 0.5.0 active.
- Live Review Invitations page verified `Enabled`, `7 days`, `Customer or email address`, `Add exclusion`, `Test block`, and `Save timing`.
- The admin suggestion list loaded 19 unique known addresses; no addresses were copied into logs or release records.
- A non-customer example address was added, passed the non-sending block test, and was removed. The exclusion list returned to empty.
- Saving the current 7-day timing rescheduled five pending invitations and displayed a successful confirmation.

## Backups and rollback

- Removed the verified oldest manual backup, `Before Hospira water card Order Experience 0.4.1 live - 2026-08-31`, after explicit authorization.
- Created and verified `Before Trustpilot customer selector and block test 0.4.0 - 2026-09-01` before the 0.4.0 deployment.
- Removed the next verified oldest manual backup, `Before Trustpilot WooCommerce integration - 2026-09-01`, under the full delete-and-deploy authorization.
- Created and verified `Before Trustpilot selector block test and timing 0.5.0 - 2026-09-01` before the final deployment.
- Rollback: restore the 0.5.0 Kinsta recovery point to return to version 0.4.0, or restore the 0.4.0 recovery point to return to version 0.3.0.

## Artifact

- Package: `dist/pepselect-trustpilot-review-0.5.0.zip`
- SHA-256: `e989f466d3e24f586b64e10f50876c487295aa49573e45afaea29c7dfdf79336`

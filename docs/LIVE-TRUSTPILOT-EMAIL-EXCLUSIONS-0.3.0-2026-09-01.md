# Live Trustpilot Email Exclusions 0.3.0 — 2026-09-01

## Outcome

- Upgraded `Pep Select Trustpilot Review Invitations` from 0.2.0 to 0.3.0 on Live.
- Added an administrator-managed email exclusion list under **WooCommerce → Review Invitations**.
- Any valid email address can be added without requiring an existing WordPress user or WooCommerce customer.
- Adding an address cancels its pending invitation and blocks future invitations for the same normalized billing email.
- Each saved exclusion can be removed from the same page.
- Existing trigger, seven-day delay, 180-day customer cooldown, catch-up, opt-out, email design, and Trustpilot destination remain unchanged.

## Verification

- Static invitation checks passed.
- Package contents passed the WordPress update comparison from 0.2.0 to 0.3.0.
- WordPress reported `Plugin updated successfully`.
- Live plugin list verified version 0.3.0 active.
- Live control verified `Enabled`, the new `Email address` field, `Add exclusion`, and the empty exclusion state.
- A non-customer example address was added and displayed, then removed; the list returned to empty.
- No customer email or order data was used during verification.

## Backup and rollback

- Removed the verified oldest manual backup, `Before account order history beta88 and Order Experience 0.4.0 live - 2026-08-31`, after explicit approval because all five manual slots were full.
- Created and verified `Before Trustpilot email exclusions 0.3.0 - 2026-09-01` before deployment.
- Rollback: restore that Kinsta backup or reinstall `pepselect-trustpilot-review-0.2.0.zip`.

## Artifact

- Package: `dist/pepselect-trustpilot-review-0.3.0.zip`
- SHA-256: `cddbe13e3e6f68a247c9175562e9f4786b7aba9352227932df1225a5bc9c2bed`

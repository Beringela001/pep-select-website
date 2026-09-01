# Live Trustpilot Customer Cadence 0.2.0 — 2026-09-01

## Outcome

- Upgraded `Pep Select Trustpilot Review Invitations` from 0.1.0 to 0.2.0 on live.
- Preserved the approved email design, copy, logo, destination, opt-out, and seven-day delay.
- Added a customer-level cooldown of 180 days using a salted billing-email hash and timestamp.
- Prevented repeat orders from creating parallel pending invitations for the same billing email.
- Kept the official `Trustpilot-reviews` plugin inactive to prevent duplicate senders.

## Existing-customer catch-up

- Used the latest completed order for each unique billing email.
- Found 12 unique eligible customers.
- Queued 6 customers whose latest completed order was already at least seven days old.
- Scheduled 6 customers for the exact seven-day mark of their latest completed order.
- Skipped 0 customers in this initial catch-up.
- Verified all 6 past-due actions completed and all 6 corresponding messages were recorded as `Sent` by WP Mail SMTP through Google / Gmail.
- Verified the remaining 6 actions are pending on future dates.

## Safety and privacy

- No additional readable customer email list is stored. Customer cadence and pending state use salted email hashes.
- Existing signed opt-outs remain permanent and customer-wide.
- Order status, checkout, payment, pricing, shipping, inventory, rewards, and customer records were not changed.
- Catch-up status exposes only aggregate totals in the administrator control panel.

## Verification

- Node contract checks passed.
- PHP syntax checks passed for the plugin and email template.
- PHP behavior checks passed for paused state, seven-day scheduling from completion time, one pending invitation per customer, 180-day suppression, post-cooldown eligibility, cancellation cleanup, latest-order catch-up selection, past-due queueing, future scheduling, opt-out suppression, and prior-send suppression.
- Live control verified `Enabled`, `7 days`, `At most once every 180 days per billing email`, and the completed catch-up totals.
- Live plugin list verified version 0.2.0 active and `Trustpilot-reviews` inactive.
- Homepage and cart smoke checks passed after deployment.

## Backup and rollback

- Deleted the confirmed oldest manual backup, `Before Cart Recovery 0.4.12 popup persistence fix - 2026-08-31`, because Kinsta's five-slot manual backup limit was full.
- Created and verified `Before Trustpilot customer cooldown and catch-up 0.2.0 - 2026-09-01` before deployment.
- Application rollback: pause invitations, replace the plugin with 0.1.0, and review or cancel pending `pepselect_send_trustpilot_review_invitation` actions before re-enabling.
- Full-environment rollback: restore the named Kinsta backup.

## Artifact

- Package: `dist/pepselect-trustpilot-review-0.2.0.zip`
- SHA-256: `ACB2AC5F303F2FFDC6E49A61ECCF772E6FFF777D615A399432B8A641AAA162CC`

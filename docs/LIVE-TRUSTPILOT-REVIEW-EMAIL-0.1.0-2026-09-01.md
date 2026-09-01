# Live Trustpilot Review Email 0.1.0 — 2026-09-01

## Outcome

- Deployed and activated `Pep Select Trustpilot Review Invitations` 0.1.0 on live.
- Enabled one branded, neutral review invitation seven days after a WooCommerce order reaches `Completed`.
- Directed the CTA to the official Trustpilot review form for `pepselect.com`.
- Deactivated the official `Trustpilot-reviews` WooCommerce plugin to prevent duplicate invitations. It remains installed and can be reactivated for rollback.

## Customer safeguards

- One send per order.
- Eligibility is rechecked at send time.
- Cancelled, failed, and refunded orders have pending invitations cancelled.
- A signed opt-out link suppresses future review invitations without placing the customer's email address in the URL.
- Copy welcomes every rating and offers no incentive.
- Failed email delivery retries after six hours, up to three attempts.

## Review classification

The CTA uses Trustpilot's public official review form. Reviews collected through this link are organic reviews, not Trustpilot verified-invitation reviews. A future authenticated Trustpilot invitation/API integration is required if Pep Select wants custom-branded emails that also receive Trustpilot's verified invitation classification.

## Verification

- Node contract checks passed.
- PHP syntax checks passed for the plugin and template.
- PHP behavior checks passed for paused-by-default behavior, seven-day scheduling, single-send enforcement, cancellation, destination, copy neutrality, and sent-state recording.
- Desktop and 360-pixel mobile previews were inspected on live. Logo scaling, content wrapping, CTA visibility, footer alignment, and disclosure copy passed.
- Live control showed `Enabled`, `WooCommerce order completed`, `7 days`, and `https://www.trustpilot.com/evaluate/pepselect.com`.
- The former Trustpilot sender showed inactive after the switch.

## Backup and rollback

- Deleted the confirmed oldest manual backup, `Before Cart Recovery 0.4.11 live - 2026-08-31`, because the five-slot manual backup limit was full.
- Created and verified `Before Pep Select Trustpilot review email 0.1.0 - 2026-09-01` before deployment.
- Application rollback: pause invitations, deactivate `Pep Select Trustpilot Review Invitations`, then reactivate `Trustpilot-reviews` if the former flow is required.
- Full-environment rollback: restore the named Kinsta backup.

## Artifact

- Package: `dist/pepselect-trustpilot-review-0.1.0.zip`
- SHA-256: `0C1C7DA13556421EC3E42EE378315A4F32423720D9952CA34BE29073BF553F45`

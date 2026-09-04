# Checkout Address Validation 0.3.0 — Implementation — 2026-09-04

## Outcome

Prepared a fail-closed postal-address verification layer for WooCommerce checkout. The package is not deployed.

## Root cause

- WooCommerce defaults new customer addresses to the shop country/region, which is United States — New York.
- Google autocomplete correctly sets Louisiana when a complete suggestion is selected.
- Manual or incomplete entry can change the street, city, and ZIP while retaining the default New York state.
- The existing validator only detects special ZIP ranges for Alaska, Hawaii, and Puerto Rico, so Louisiana ZIP `70769` with state `NY` passes.
- Fluid Checkout's hidden billing fields can refresh independently while "Same as shipping" is selected, allowing stale address components.

## Fix

- Reject street lines that contain only a house number or otherwise lack both a number and street name.
- Validate the full shipping address before order creation through Google Address Validation with USPS CASS enabled.
- Require a complete premise-level result, USPS DPV confirmation `Y`, and matching country, state, city, and five-digit ZIP.
- Reject corrected, incomplete, mismatched, or non-deliverable responses instead of silently changing the order.
- Cache only the pass/fail result under a one-way address hash for 24 hours; raw addresses and API responses are not stored.
- Copy shipping fields to billing atomically on the server when "Same as shipping" is selected.
- Show the incomplete-street warning immediately in checkout before submission.

## Configuration required before staging

1. Enable Google Address Validation API in the existing `pep-select-website` Google Cloud project.
2. Create a separate server-side API key restricted to Address Validation API and the production/staging server egress IPs.
3. Define the key outside the repository as `PEPSELECT_GOOGLE_ADDRESS_VALIDATION_API_KEY`.
4. Set a conservative daily quota and billing alert before enabling checkout enforcement.

The existing browser-referrer key must not be reused for server requests. With no server key configured, version 0.3.0 keeps the structural and existing destination checks active but does not call Google, allowing safe installation/configuration ordering.

## Verification completed

- Live, no order submitted: a complete official Prairieville, LA 70769 autocomplete result set shipping and billing state to `LA` and survived checkout refresh.
- Live, no order submitted: manual address `38206`, Prairieville, NY 70769 produced no warning and left Place order enabled on version 0.2.6, reproducing the reported failure class.
- Live checkout session restored to its original shipping and billing values after testing.
- PHP syntax check passed.
- Shipping restrictions contract passed, including incomplete street, atomic billing sync, valid Prairieville, state mismatch, city mismatch, and non-deliverable street cases.

## Release state

- Source version: `0.3.0`
- Staging: not deployed
- Live: untouched
- Rollback: reinstall `pepselect-shipping-restrictions` 0.2.6

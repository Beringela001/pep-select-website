# Checkout Address Validation 0.3.0 — Implementation — 2026-09-04

## Outcome

Released a fail-closed postal-address verification layer for WooCommerce checkout to staging and live.

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

## Runtime configuration

1. Google Address Validation API is enabled in the existing `pep-select-website` Google Cloud project.
2. A dedicated server-side key is restricted to Address Validation API; it is not reused by browser autocomplete.
3. The key is defined outside the repository as `PEPSELECT_GOOGLE_ADDRESS_VALIDATION_API_KEY` in both staging and live configuration.
4. Application-level IP restriction, a conservative daily quota, and a billing alert remain recommended follow-up hardening.

The existing browser-referrer key must not be reused for server requests. With no server key configured, version 0.3.0 keeps the structural and existing destination checks active but does not call Google, allowing safe installation/configuration ordering.

## Verification completed

- Live, no order submitted: a complete official Prairieville, LA 70769 autocomplete result set shipping and billing state to `LA` and survived checkout refresh.
- Live, no order submitted: manual address `38206`, Prairieville, NY 70769 produced no warning and left Place order enabled on version 0.2.6, reproducing the reported failure class.
- Live checkout session restored to its original shipping and billing values after testing.
- PHP syntax check passed.
- Shipping restrictions contract passed, including incomplete street, atomic billing sync, valid Prairieville, state mismatch, city mismatch, and non-deliverable street cases.
- Staging plugin version `0.3.0` is active.
- Staging server-side checkout test rejected Prairieville, NY 70769 through Google verification and created no order.
- Live plugin version `0.3.0` is active after cache clear.
- Live storefront, admin, and checkout load successfully; the incomplete-street warning is active and the original checkout values were restored without submitting an order.
- A transient live HTTP 500 occurred when the hosting editor duplicated `wp-config.php` during an attempted whole-file replacement. The original file was immediately restored, the storefront recovered, and the API constant was re-added with a verified single-line insertion before deployment continued.

## Release state

- Source version: `0.3.0`
- Staging: deployed and verified
- Live: deployed and verified
- Live rollback backup: `Before Shipping Restrictions 0.3.0 live - 2026-09-04`
- Rollback: reinstall `pepselect-shipping-restrictions` 0.2.6

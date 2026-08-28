# Live Shipping Restrictions Release — 0.1.2

## Scope

- Added a dedicated Pep Select Shipping Restrictions plugin so the checkout guard survives theme changes.
- Rejects shipping countries and states outside the contiguous 48 states and Washington, D.C.
- Rejects excluded ZIP ranges even when a customer selects a false lower-48 state.
- Covers Alaska, Hawaii, Puerto Rico, the U.S. Virgin Islands, Pacific territories, and overseas military ZIP ranges.
- Shows an accessible red warning directly above the active ZIP field.
- Removes cached and newly calculated shipping rates for excluded destinations.
- Preserves Free Shipping and Easyship rates for eligible destinations.

## Package

- File: `dist/pepselect-shipping-restrictions-0.1.2.zip`
- SHA-256: `EA5C7B4665C7C228D78EF5D21B23372EE606B30E54E9BBF2C86BAF8466F44F54`

## Backup and deployment

- Live manual-backup capacity was 5 of 5.
- Verified and deleted the bottom and oldest manual backup: `Before automatic free vials 1.0.1 live restore - 2026-08-26`.
- Created: `Before shipping restrictions 0.1.0 live deployment - 2026-08-27` at 9:49 PM.
- Installed and activated Pep Select Shipping Restrictions on Live.
- Applied two QA revisions and confirmed the final active version is `0.1.2`.
- Cleared all Kinsta caches after the final update.

## Live verification

- A valid New York address received a USPS Priority Mail rate.
- A valid Washington, D.C. address received USPS and FedEx rates.
- A Florida address with Puerto Rico ZIP `00901` displayed the red warning directly above ZIP and received no shipping options.
- The invalid ZIP field had `aria-invalid="true"` and referenced the warning through `aria-describedby`.
- Replacing the excluded ZIP with an allowed ZIP hid the warning, restored `aria-invalid="false"`, and restored shipping rates.
- The loaded checkout asset reported plugin version `0.1.2`.
- No test order was submitted or created.

## Rollback

Restore the named pre-deployment Live backup or deactivate Pep Select Shipping Restrictions. The WooCommerce `Contiguous US` zone remains the first shipping boundary if the plugin is deactivated.

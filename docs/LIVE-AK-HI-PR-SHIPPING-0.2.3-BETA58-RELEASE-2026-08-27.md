# Live Alaska, Hawaii, and Puerto Rico Shipping Release — 0.2.3 / Beta 58

## Outcome

- Restored checkout eligibility for Alaska, Hawaii, and Puerto Rico.
- Kept Alaska and Puerto Rico USPS-only.
- Kept Hawaii on the live carrier rates returned at checkout.
- Continued blocking the U.S. Virgin Islands, other U.S. territories, and overseas military destinations.
- Added Puerto Rico to the U.S. State / Territory selector so WooCommerce, tax calculation, and Easyship receive a consistent domestic address.
- Hard-blocked mismatched state and ZIP combinations, including a Puerto Rico ZIP entered with New York selected.
- Moved the specific destination warning into the Shipping section so it no longer changes the ZIP-field layout.
- Suppressed WooCommerce's generic no-shipping-services message while the specific destination warning is active, leaving one clear explanation.
- Updated the live FAQ and Refund & Shipping Policy with the same rules.

## Live packages

- Theme: `dist/pepselect-child-0.25.0-beta.58.zip`
- Theme SHA-256: `9994CF2F0A22B58D79B3E3FA0F3752A34F6D851E69AA02022B27F5E4795C976D`
- Shipping plugin: `dist/pepselect-shipping-restrictions-0.2.3.zip`
- Plugin SHA-256: `2015A5AE1E6F448EBC21C8C0B9F887C1FB060EE11654730E5E48C631E4ABDE8F`

## Backup and deployment

- Verified the live manual-backup list before deployment.
- Deleted only the bottom and oldest manual backup after explicit authorization.
- Created: `Before AK HI PR shipping restoration 0.2.0 beta55 - 2026-08-27`.
- Uploaded and activated the final theme and plugin packages on Live.
- Cleared all Kinsta caches after the final deployment.
- Confirmed the active live versions are theme `0.25.0-beta.58` and plugin `0.2.3`.

## WooCommerce and carrier configuration

- Selling countries remain limited to the United States.
- The primary United States shipping zone includes all 50 states, Washington, D.C., and Puerto Rico.
- The primary zone retains Free Shipping and Easyship; the plugin removes non-USPS rates for Alaska and Puerto Rico.
- Hawaii receives the live services returned by the configured carriers.
- A lower-priority Puerto Rico-only zone remains below the primary zone and does not match checkout traffic because the primary zone matches Puerto Rico first.

## Live checkout verification

- Puerto Rico, ZIP `00901`: USPS Priority Mail only at `$13.63`.
- Alaska, ZIP `99801`: USPS Priority Mail only at `$12.97`.
- Hawaii, ZIP `96813`: USPS Priority Mail `$12.97`, FedEx 2Day `$27.75`, UPS 2nd Day Air `$40.80`, and FedEx Home Delivery `$65.28`.
- New York selected with Puerto Rico ZIP `00901`: checkout showed `This ZIP code belongs to Puerto Rico. Select Puerto Rico in the State / Territory field.` and returned no rate.
- The mismatch warning appeared once under the Shipping heading; WooCommerce's generic no-services notice was absent.
- The invalid ZIP retained `aria-invalid="true"` and checkout remained hard-blocked.
- U.S. Virgin Islands ZIP `00802`: checkout showed the unsupported-destination warning, returned no rate, and remained hard-blocked.
- No test order was submitted or created.

Rates above are the live quotes returned during deployment verification and can change when carrier pricing changes.

## Public copy verification

- The live FAQ states that Pep Select ships to all 50 states, Washington, D.C., and Puerto Rico; Alaska and Puerto Rico are USPS-only; and Puerto Rico customers must select Puerto Rico in State / Territory.
- The live Refund & Shipping Policy states the same scope and exclusions.

## Google address autocomplete

- The Google Address Autocomplete plugin is installed and active, but autocomplete is disabled and no Google API key is configured.
- It was not enabled as part of this release. The corrected State / Territory flow works without it and remains compatible with a future properly restricted Google key.

## Rollback

Restore the named pre-deployment live backup. Alternatively, reinstall theme `0.25.0-beta.58`'s predecessor and deactivate or replace Pep Select Shipping Restrictions `0.2.3` together so checkout copy and enforcement remain consistent.

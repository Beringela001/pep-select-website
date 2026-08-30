# Live Google Address Autocomplete 0.2.6 Release — 2026-08-30

## Outcome

Google address autocomplete is enabled on the live WooCommerce checkout. Suggestions are limited to the United States and Puerto Rico. Puerto Rico selections are normalized to WooCommerce's United States territory format so the State / Territory field is `PR` and the existing USPS-only shipping rule remains authoritative.

## Google and WooCommerce configuration

- Google Cloud project: `pep-select-website`
- Enabled APIs: Maps JavaScript API and Places API (New)
- Allowed website referrers: `https://pepselect.com/*` and `https://www.pepselect.com/*`
- Fluid Checkout Google Address Autocomplete: active and enabled on live
- API key test: passed in the WordPress settings
- Credentials are stored only in the connected services and were not written to the repository or release artifacts.

## Release

- Plugin: Pep Select Shipping Restrictions 0.2.6
- Package: `dist/pepselect-shipping-restrictions-0.2.6.zip`
- SHA-256: `60EDF23F050DD1894044CED5F5920255F39B8503AE33D72D816C1C828FAA6257`
- Staging install: successful
- Live install: successful and active
- Live cache: cleared after installation

## Backup

- Verified the live environment before deployment.
- Removed only the verified oldest manual backup after approval: `Before coded About and Elementor retirement beta70 live - 2026-08-30`.
- Created and verified the replacement restore point: `Before Google address autocomplete 0.2.4 live - 2026-08-30`.

The backup name reflects the deployment milestone at the time it was created; it is the restore point immediately before the 0.2.4–0.2.6 live update sequence.

## Live checkout verification

No order was submitted.

- Puerto Rico: `1 Calle San Gerónimo, San Juan, PR 00907` populated from Google; checkout stored country `US`, state `PR`, and showed only USPS Priority Mail at the calculated live rate.
- Alaska: `120 4th Street, Juneau, AK 99801` populated from Google and showed only USPS Priority Mail.
- Hawaii: `415 South Beretania Street, Honolulu, HI 96813` populated from Google and returned calculated USPS, FedEx, and UPS rates.
- Michigan: `100 North Capitol Avenue, Lansing, MI 48933` populated with state `MI`, addressing the previously reported state-mapping concern.
- Europe: `10 Downing Street, London, United Kingdom` returned no autocomplete suggestions because address search is limited to `US` and `PR`; WooCommerce shipping validation remains the server-side enforcement layer.
- Shipping warnings remained hidden for each valid supported address.

## Rollback

Restore the named Kinsta manual backup, or reinstall the previous shipping-restrictions package and disable Google Address Autocomplete in WooCommerce settings.

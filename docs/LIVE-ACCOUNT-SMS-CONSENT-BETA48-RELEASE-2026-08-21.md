# Live Account SMS Consent Release — 0.25.0-beta.48

**Deployed:** 2026-08-21  
**Environment:** Live  
**Changed surface:** Signed-in `/my-account/` dashboard

## Delivered

- Added a simple full-width Text message preferences card between the account information cards and the referral card.
- Added a mobile-number field using the customer's canonical WooCommerce billing phone.
- Added separate unselected consent checkboxes for customer-care messages and marketing messages, plus an unselected no-text option.
- Kept both affirmative choices combinable and made the no-text choice mutually exclusive in the browser and on the server.
- Added the approved PS Research Solutions LLC disclosure and Privacy Policy/Messaging Terms link below the form.
- Added nonce protection, server-side phone and choice validation, and customer-level consent records with source, disclosure version, and GMT update time.
- Kept opt-out available even when no valid mobile number is present.

## Release controls

- Removed only the verified oldest manual backup: `Before Cagrilintide product content 0.25.0-beta.44 live deployment - 2026-08-21`.
- Created and confirmed: `Before account SMS consent 0.25.0-beta.48 live deployment - 2026-08-21`.
- Deployed `dist/pepselect-child-0.25.0-beta.48.zip`.
- SHA-256: `94D26179F1C290BF3FF41370607626257753A895994A337271545494197B2294`.
- Cleared all WordPress/Kinsta caches.

## Verification

- All eight JavaScript safeguards passed, including the new account SMS consent test.
- Active Live child-theme version confirmed as `0.25.0-beta.48`.
- Live account dashboard confirmed all three checkboxes are unselected on initial load.
- Live browser interaction confirmed both affirmative choices can be selected together, the no-text choice clears them, and choosing an affirmative option clears no-text.
- A no-selection submission displayed the expected validation and did not save a preference.
- The complete disclosure, Privacy Policy link, mobile field, and Save action render correctly.
- Desktop and 390px mobile checks found no horizontal overflow.
- Homepage, Shop, Cart, and Privacy Policy neighboring surfaces loaded without critical errors or horizontal overflow.
- PHP CLI lint was unavailable because PHP is not installed locally; WordPress installed and executed the package successfully on Live.

## Rollback

Restore the named Kinsta backup above or reinstall the prior `0.25.0-beta.47` child-theme package.

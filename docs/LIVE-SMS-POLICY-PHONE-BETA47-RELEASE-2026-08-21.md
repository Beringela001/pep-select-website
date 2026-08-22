# Live SMS Policy and Support Phone Release — 0.25.0-beta.47

**Deployed:** 2026-08-21  
**Environment:** Live  
**Changed surfaces:** Privacy Policy, Terms and Conditions, Contact, checkout policy acknowledgment, and site footer

## Delivered

- Added the requested customer-data and mobile opt-in sharing restrictions to the Privacy Policy.
- Added the Messaging Program Terms and Conditions, including program scope, opt-out, HELP, carrier, rate, frequency, and privacy details.
- Added the messaging/data-collection clause to the Terms and Conditions.
- Clarified in the Privacy Policy that checking the required policy agreement at checkout acknowledges the Messaging Program Terms and Conditions.
- Added `1 (833) 737-7528 (1-833-PEP-SLCT)` to the Contact page and site footer with a clickable telephone link.

## Release controls

- Removed only the verified oldest manual backup: `Before KPV product content 0.25.0-beta.41 live deployment - 2026-08-21`.
- Created and confirmed: `Before SMS policy and phone 0.25.0-beta.47 live deployment - 2026-08-21`.
- Deployed `dist/pepselect-child-0.25.0-beta.47.zip`.
- SHA-256: `C09BF3AD7F1F5F464A8C32AD75A6812DA66CA1675F169F3C880B1C2FB8959D53`.
- Cleared all WordPress/Kinsta caches.

## Verification

- All seven JavaScript safeguards passed, including the SMS policy content test.
- Active Live child-theme version confirmed as `0.25.0-beta.47`.
- Privacy Policy, Terms and Conditions, and Contact content verified on Live at desktop and mobile widths.
- Footer and Contact telephone links resolve to `tel:+18337377528`.
- Homepage, Shop, and Cart neighboring surfaces loaded without horizontal overflow.
- PHP CLI lint was unavailable because PHP is not installed locally; WordPress installed and rendered the package successfully on Live.

## Unrelated Site Kit diagnosis

- The WordPress dashboard's Search Console and Analytics cards return `missing_delegation_consent` while both services remain listed as connected.
- This indicates missing or revoked Google delegation consent for Site Kit, rather than a child-theme regression.
- No Google account permissions, Site Kit connections, or analytics configuration were changed during diagnosis.

## Rollback

Restore the named Kinsta backup above or reinstall the prior `0.25.0-beta.46` child-theme package.

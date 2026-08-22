# Live Logo and Phone Display Release — 0.25.0-beta.49

**Deployed:** 2026-08-21  
**Environment:** Live  
**Changed surfaces:** Global header, global footer, and Contact page

## Delivered

- Replaced the Pep Select header logo with the approved new brand artwork.
- Replaced the footer logo with the white-text version prepared for the dark-blue footer.
- Preserved the open counters in the P, A, and D letterforms and added the approved very slight PEP separation.
- Removed the `1-833-PEP-SLCT` vanity display from the footer and Contact page.
- Retained the clickable phone number as `1 (833) 737-7528` with the existing `tel:+18337377528` destination.

## Release controls

- Verified the Live manual-backup list before deletion.
- Removed only the oldest bottom entry: `Before account referral card move 0.25.0-beta.45 live deployment - 2026-08-21`.
- Created and confirmed: `Before logo and phone display 0.25.0-beta.49 live deployment - 2026-08-21`.
- Deployed `dist/pepselect-child-0.25.0-beta.49.zip`.
- SHA-256: `C6147C5D45F0BBF3339B5F890CA03430F01F28C420EEA78A1F93388AFF0DF2F8`.
- Cleared all WordPress/Kinsta caches after installation.

## Verification

- WordPress confirmed the active child theme as `Pep Select Version: 0.25.0-beta.49`.
- Live homepage uses the bundled `pep-select-logo-header.png` and `pep-select-logo-footer.png` assets.
- Desktop and 390px mobile visual checks confirmed the header remains responsive and the footer remains legible.
- Footer displays `1 (833) 737-7528` and no longer contains `1-833-PEP-SLCT`.
- Contact displays `1 (833) 737-7528`, omits the vanity number, and retains `tel:+18337377528`.
- Home, Contact, Shop, Cart, and Checkout rendered without fatal or critical errors.
- All targeted local JavaScript safeguards passed before packaging.
- PHP CLI lint was unavailable because PHP is not installed locally; WordPress installed and executed the package successfully on Live.

## Rollback

Restore the named Kinsta backup above or reinstall the prior `0.25.0-beta.48` child-theme package.

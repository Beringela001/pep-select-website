# Live Account Referral Placement Release — 0.25.0-beta.45

**Deployed:** 2026-08-21  
**Environment:** Live  
**Changed surface:** `/my-account/` and `/my-account/cash-back/`

## Delivered

- Moved the existing dynamic Refer a friend card onto the main My Account dashboard.
- Confirmed the Live order: Welcome, My information, Cash back, Refer a friend, Your orders.
- Preserved the server-generated `PSRC` referral link, `WELCOME10` instructions, $15 referral figure, copy control, YITH referral behavior, and WooCommerce account endpoints.
- Removed the referral block from the Cash back detail page so it appears only once; its How it works, balance conversion, and history sections remain available.

## Release controls

- Removed only the verified oldest manual backup: `Before SEO render-path cleanup 0.25.0-beta.39 live deployment - 2026-08-19`.
- Created and confirmed: `Before account referral card move 0.25.0-beta.45 live deployment - 2026-08-21`.
- Deployed `dist/pepselect-child-0.25.0-beta.45.zip`.
- SHA-256: `7817D65E633C8E9F28C1736193E1EBF60FF335B9727216A8FDCC3B031FD2A55B`.
- Cleared all Kinsta WordPress caches.

## Verification

- All five JavaScript safeguards passed, including the account referral-placement test.
- Active Live child-theme version confirmed as `0.25.0-beta.45`.
- Live dashboard confirmed the dynamic share URL, Copy button, three referral steps, `WELCOME10`, and $15.00 bonus.
- At 390 px, the steps collapse to one column and the page has no horizontal overflow.
- Cash back, Orders, Account details, and Addresses endpoints loaded successfully.
- The final My Account dashboard produced no browser console errors.
- PHP CLI lint was unavailable because PHP is not installed locally; the changed templates rendered successfully on Live.

## Rollback

Restore the named Kinsta backup above or reinstall the prior `0.25.0-beta.44` child-theme package.

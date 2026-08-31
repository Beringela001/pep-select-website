# Live account registration subtitle — beta.87

Date: 2026-08-30  
Theme: Pep Select `0.25.0-beta.87`

- Replaced “Create an account for faster checkout and easy order tracking.” with “Create an account to track your orders.”
- Package: `dist/pepselect-child-0.25.0-beta.87.zip`
- SHA-256: `1F4C6EBFCC4CE50C15EECB27C20619F167C5928D699E40DBF2AE098313E95FBB`
- Commit: `df125c6`
- Used the existing Live rollback backup `Before account page spacing beta85 live - 2026-08-30`, which covers this continuous account-page refinement.
- Confirmed beta.87 is active on Live and cleared Kinsta caches.
- Verified the registration subtitle renders as one line at desktop and 390 × 844 mobile widths, with no horizontal overflow and no date-of-birth field.

Rollback: restore the named backup above or reinstall `dist/pepselect-child-0.25.0-beta.86.zip`, then clear Kinsta caches.

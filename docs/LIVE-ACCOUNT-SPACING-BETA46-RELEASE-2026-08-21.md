# Live Account Card Spacing Release — 0.25.0-beta.46

**Deployed:** 2026-08-21  
**Environment:** Live  
**Changed surface:** `/my-account/`

## Delivered

- Removed the Cash back detail-page engine class from the moved referral card.
- Eliminated its unintended 40px top margin and restored the dashboard's standard 20px gap.
- Kept the dashboard order and referral behavior unchanged.

## Verified spacing

- Welcome to My information: 20px.
- My information to Cash back: 20px horizontally.
- My information/Cash back to Refer a friend: 20px.
- Refer a friend to Your orders: 20px.
- At 390px, every stacked card gap is 20px and page width equals viewport width.

## Release controls

- Removed only the verified oldest manual backup: `Before catalog ordering 0.25.0-beta.40 live deployment - 2026-08-20`.
- Created and confirmed: `Before account card spacing 0.25.0-beta.46 live deployment - 2026-08-21`.
- Deployed `dist/pepselect-child-0.25.0-beta.46.zip`.
- SHA-256: `27CD43B00152D9AF1B2BAA1A27760BD4D00AC49E0CA837C5E670225309FEE22B`.
- Cleared all Kinsta WordPress caches.

## Verification

- All five JavaScript safeguards passed.
- Active Live child-theme version confirmed as `0.25.0-beta.46`.
- Desktop and 390px mobile measurements confirmed uniform 20px gaps.
- The final My Account page produced no browser console errors.

## Rollback

Restore the named Kinsta backup above or reinstall the prior `0.25.0-beta.45` child-theme package.

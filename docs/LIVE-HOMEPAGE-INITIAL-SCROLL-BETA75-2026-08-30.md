# Live homepage initial-scroll correction — beta75

Date: 2026-08-30  
Environment: Live  
Status: complete

## Finding

PS Access Gate 2.2.2 fixed the gate's focus-induced scroll jump. The follow-up screenshot exposed a separate browser behavior: when the canonical homepage was reloaded from an existing tab already positioned `181px` down, Chrome restored that exact offset and left the global header above the viewport.

Exact reproduction on `https://pepselect.com/`:

- Brand-new direct tab: `scrollY = 0`; header visible.
- Existing tab positioned at `181px`, then reloaded: `scrollY = 181`; header top `-149px`.
- Canonical page served the corrected `preventScroll` access-gate code, ruling out a stale Kinsta response as the remaining cause.

## Fix

Pep Select child theme 0.25.0-beta.75 resets normal direct and reload homepage visits to `0,0` on `pageshow`, with a double animation-frame follow-up to run after delayed browser restoration. It deliberately does not reset:

- URLs containing an explicit `#fragment`;
- Back/Forward history traversal; or
- pages restored from the back-forward cache.

The change is homepage-only presentation behavior. Access-gate consent, WooCommerce, products, cart, checkout, payments, shipping, accounts, orders, COA, and other routes are unchanged.

## Verification

- Static homepage initial-scroll safeguard test: passed.
- Homepage JavaScript syntax check: passed.
- Live exact canonical direct load: `scrollY = 0`; desktop header top `32px` below the logged-in admin bar.
- Live desktop reproduction: forced `scrollY = 181`, reloaded canonical `/`, then measured `scrollY = 0`; header visible.
- Live mobile 390×844 reproduction: forced `scrollY = 176`, reloaded canonical `/`, then measured `scrollY = 0`; header visible and no horizontal overflow.
- Live `#pepselect-faq-title` navigation landed on the requested heading instead of resetting to the top.
- Live Back navigation from Shop restored the homepage's prior `900px` position.
- Active Live theme confirmed as Pep Select; all Kinsta caches cleared.
- Browser console errors: none.

## Deployment and rollback

- Package: `dist/pepselect-child-0.25.0-beta.75.zip`
- SHA-256: `C53773168947F252B1A44374955EBC1AC7DC098BE5A9016CFC292845988B4A0A`
- Rollback: reinstall `dist/pepselect-child-0.25.0-beta.73.zip` and clear caches.
- Per owner authorization, this code-only Live correction is deployed without a new Kinsta backup; the exact beta73 and beta74 packages remain available as rollback artifacts.

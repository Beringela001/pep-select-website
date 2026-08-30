# Live initial-load scroll fix — PS Access Gate 2.2.2

Date: 2026-08-30  
Environments: staging and live  
Status: complete

## Finding

With a valid remembered access-gate cookie, `closeGate()` moved keyboard focus to the page's `<main>` element using `main.focus()`. Browser focus scrolling moved the viewport down by exactly the global-header height on every load.

Measured before the fix:

- Desktop: `scrollY = 181`; header top `-181px`; main content top approximately `0px`.
- Mobile at 390×844: `scrollY = 176`; header top `-176px`; main content top approximately `0px`.

This made the page appear to load below the menu and forced visitors to scroll upward to recover the header.

## Fix

PS Access Gate 2.2.2 retains the accessibility focus handoff but calls `main.focus({ preventScroll: true })`. Gate consent, cookies, focus containment, legal content, settings, styling, and commerce behavior are unchanged.

## Package

- File: `dist/ps-access-gate-2.2.2.zip`
- SHA-256: `79F81E4155BF4ECE48086FFDFA706977FE99325A681D88AD560C08A0C8F549B7`
- Production contents: plugin PHP, readme, and bundled logo only; tests excluded.
- Code-only rollback: `dist/ps-access-gate-2.2.1.zip`

The Live Kinsta manual-backup list was verified at 5/5 before deployment. This patch changes no database records or settings, and the exact prior 2.2.1 plugin package remains available for immediate rollback.

## Verification

- Access-gate accessibility contract test passed.
- PHP syntax check passed.
- Staging updated from 2.2.1 to 2.2.2 successfully before Live.
- Live updated from 2.2.1 to 2.2.2 successfully and caches were cleared.
- Live desktop after fix: `scrollY = 0`; header top `0px`; full header visible; main retained programmatic focus.
- Live 390×844 mobile after fix: `scrollY = 0`; header top `0px`; full header visible; main retained programmatic focus.
- Mobile Home, Shop, GLP-3 R product, Quality Archive, and Contact loaded at the top without fatal errors.
- Desktop Home, Shop, Cart, and Checkout loaded at the top without fatal errors.
- Browser console errors: none.

## Rollback

Reinstall `dist/ps-access-gate-2.2.1.zip` and replace the current plugin, then clear Kinsta caches. No database rollback is required.

# Coded About page and Elementor retirement — beta.70

Date: 2026-08-30  
Environment validated: staging  
Release candidate: Pep Select child theme 0.25.0-beta.70  
Artifact: `dist/pepselect-child-0.25.0-beta.70.zip`  
SHA-256: `5E896ED935CC12C5932C7DC952086352E08E23DABBE66FA83781BE4F4D109C60`

## Outcome

- Replaced the legacy Elementor About page frontend with a coded theme template.
- Preserved the stored Elementor document for rollback while forcing the coded route at runtime.
- Used the current Tesamorelin product listing and featured image instead of a copied legacy vial asset.
- Added one H1, AboutPage structured data, page-specific title and description, and a canonical production URL.
- Kept About out of the header and footer navigation.
- Removed Elementor, Elementor Pro, and Marquee Addons assets from the About frontend.

## Staging gate

The manual pre-deployment backup is named `Before coded About page beta69 staging - 2026-08-30`. It predates both beta.69 and the beta.70 route-ownership correction.

After beta.70 was installed, the following plugins were deactivated in dependency order:

1. Marquee Addons for Elementor
2. Elementor Pro
3. Elementor

The plugin files remain installed on staging for one-click rollback. Hello Elementor 3.4.9 remains installed as the parent theme; Pep Select remains the active child theme.

## Verification

- Local PHP syntax checks passed for all changed PHP files.
- Local JavaScript regression suite passed, including the coded About safeguards.
- BOGO and compound-discount PHP regression tests passed.
- Staging routes passed after all three plugins were inactive: Home, About, Shop, Tesamorelin product, Quality Archive, FAQ, Contact, Military Discount, Cart, Checkout, My Account, and Track Order.
- No tested route displayed a fatal error or exposed shortcode.
- About has one H1, AboutPage schema, no About navigation entry, and no Elementor/Pro/Marquee frontend assets.
- Staging-wide `noindex, nofollow` remains active as expected for the non-production environment.
- Mobile check at 390 × 844 passed with no horizontal overflow and the current Tesamorelin product image.

## Rollback

Reactivate Elementor, then Elementor Pro, then Marquee Addons. If theme rollback is also required, restore the named manual staging backup.

## Live deployment

Beta.70 was deployed to production after the staging gate passed. Before deployment, the oldest live manual backup (`Before beta63 new site icon live - 2026-08-29`, created Aug 28, 2026 at 11:51 PM) was removed to free the required slot. The replacement live backup is:

`Before coded About and Elementor retirement beta70 live - 2026-08-30`

Production then received the same theme ZIP and the same plugin deactivation order used on staging. Elementor, Elementor Pro, and Marquee Addons are inactive; their files remain installed for rollback. Pep Select remains the active child theme and Hello Elementor 3.4.9 remains its installed parent.

The production smoke test passed Home, About, Shop, Tesamorelin product, Quality Archive, FAQ, Contact, Military Discount, Cart, Checkout, My Account, and Track Order. No tested route displayed a fatal error or exposed shortcode. The live About page is indexable, uses the production canonical URL, contains AboutPage schema, and loads no Elementor, Elementor Pro, or Marquee Addons plugin assets.

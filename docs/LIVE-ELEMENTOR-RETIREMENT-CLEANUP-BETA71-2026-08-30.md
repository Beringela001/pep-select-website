# Elementor retirement cleanup — beta71

Date: 2026-08-30  
Environments: staging and live  
Status: complete

## Outcome

- Permanently deleted Elementor Core, Elementor Pro, and Marquee Addons for Elementor from staging and live after owner confirmation.
- Retained Hello Elementor 3.4.9 as the installed parent theme required by the active Pep Select child theme.
- Removed obsolete Elementor editor, Theme Builder, legacy-shell, asset dequeue, and Marquee compatibility code from the child theme.
- Deployed Pep Select child theme `0.25.0-beta.71` to staging, then the identical ZIP to live.

## Recovery assets

- Live manual backup: `Before coded About and Elementor retirement beta70 live - 2026-08-30`
- Staging manual backup: `Before coded About page beta69 staging - 2026-08-30`
- Elementor template archive: `site-exports/elementor/elementor-templates-staging-2026-08-30.zip`
- Template archive SHA-256: `68153FCEA153B0A5F11AC397B473D4669D7E9BF3ABB2AB38886163B127CF239F`
- The archive contains all 13 non-empty library templates. Default Kit ID 7 was the fourteenth library row and returned `The template is empty`, so Elementor provided no export payload for it.

## Release artifact

- File: `dist/pepselect-child-0.25.0-beta.71.zip`
- SHA-256: `93151B61C9377F5666EB69188B885AAC61F4FCD2A638EAFA350226C9291E5980`
- ZIP root: `pepselect-child/`

## Verification

- Local PHP syntax passed for every changed PHP file.
- All 15 relevant JavaScript regression suites passed.
- BOGO and compound-discount PHP commerce regressions passed.
- Staging reported beta71 active and Hello Elementor 3.4.9 present as its parent.
- Staging public checks passed on home, About, Shop, a current product, Testing/COA, FAQ, Contact, and Military Discount after beta71. The full 12-route staging gate had already passed immediately after plugin deletion.
- Live reported beta71 active after deployment and cache clearing.
- Live passed home, About, Shop, a current product, Testing/COA, FAQ, Contact, Military Discount, Cart, Checkout, My Account, and Track Order.
- No checked live route exposed a fatal/critical/parse error, raw Elementor shortcode, or asset from the retired Elementor/Pro/Marquee plugin directories.

## Rollback

Restore the named environment backup for a complete environment rollback. For a code-only rollback, reinstall the signed-off beta70 child-theme package. Reinstall the retired plugin packages only if the archived Elementor content must be opened again; do not activate them on live merely to inspect the archive.

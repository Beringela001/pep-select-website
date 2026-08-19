# Staging SEO Milestone 4 Batch 1 Release — 2026-08-18

## Outcome

Milestone 4 Batch 1 was deployed to the Pep Select Staging environment only. Paulo approved the batch on August 19, 2026 after requesting removal of the adjacent Quality Archive footer link. The final active child-theme package is `0.25.0-beta.28`. Live was not changed.

## Recovery point

- Kinsta Staging backup: `Before Claude SEO Milestone 4 content batch 1 - 2026-08-18`
- Created: August 18, 2026 at 10:43 PM America/New_York
- Previous verified Staging theme: `0.25.0-beta.25`

## Package

- File: `dist/pepselect-child-0.25.0-beta.28.zip`
- Size: 1,360,454 bytes
- SHA-256: `b3dbbfe2c1e272d28bdc8f5400615cbd20cf5d8a89f7e93e90a4cde75c0f3fc1`
- ZIP structure verified: one `pepselect-child/` root with the expected theme files and no repository, dependency, or build directories.
- Supersedes the initial Staging review build `0.25.0-beta.27`.

## Deployed changes

- Replaced the GLP-2T supplier/manufacturer boilerplate with the approved Pep Select-specific, mechanism-only description.
- Corrected GLP-2T compound matching so the approved description, research contexts, citations, and Product JSON-LD description render.
- Added the approved natural use of `research peptide` to the homepage hero.
- Updated the sitewide footer to state Pep Select's confirmed pre-release independent-testing policy without claiming that pending or unreleased compounds have already completed testing.
- Removed the adjacent `Review batch-specific Certificates of Analysis in the Quality Archive.` link at Paulo's direction. The existing footer navigation remains unchanged.

## Staging verification

- WordPress accepted the uploaded theme and reported `Theme updated successfully`.
- Kinsta Staging cache was cleared after installation.
- Homepage: approved hero and final footer copy visible; the rejected adjacent Quality Archive link is absent; desktop and mobile layouts passed without visible clipping or horizontal overflow.
- GLP-2T: approved description, three research-context bullets, three verified source citations, and matching Product JSON-LD description visible; old supplier boilerplate absent.
- Neighboring routes checked: Shop, Quality Archive, Cart, Checkout, and My Account loaded without fatal PHP output.
- Mobile checks completed for Homepage, Shop, GLP-2T, Quality Archive, and Cart.
- Final beta.28 regression check completed on Homepage, GLP-2T, and Cart after cache clearing. The removed link remained absent and no fatal output or page overflow appeared.
- Quality Archive's raw document width was eight pixels wider than its viewport because the existing Xootix side-cart panel is intentionally parked off-screen. No visible content crossed the viewport.

## Limitations and observations

- PHP CLI is not installed in this workspace, so a local command-line PHP syntax check could not be run. WordPress installed and executed the package successfully, and the affected public routes were browser-tested.
- The browser console contains an existing Elementor `elementorFrontendConfig is not defined` error. The released content still renders and the changed user flows pass. This release does not claim to resolve that separate script-loading issue.
- No ranking, indexing, PageSpeed, or conversion improvement is claimed from this deployment. Those outcomes require later measurement.

## Rollback

Restore the named Kinsta Staging backup for a complete rollback, or reinstall child theme `0.25.0-beta.25`. Clear Kinsta cache after either rollback method and repeat the route checks above.

# Live SEO Milestone 4 Batch 1 Release — 2026-08-19

## Outcome

Paulo explicitly approved Milestone 4 Batch 1 and its Live deployment. The Staging-verified child theme `0.25.0-beta.28` was promoted to Live. WordPress confirmed `Theme updated successfully`, and the Live cache was cleared.

## Recovery point and backup capacity

- Live recovery point: `Before Claude SEO Milestone 4 batch 1 live deployment - 2026-08-19`
- Created: August 19, 2026 at 10:50 AM America/New_York
- Previous Live child theme: `0.25.0-beta.26`
- Manual backup capacity was 5 / 5. The bottom and oldest entry was verified before deletion: `Before shipped-order email 0.25.0-beta.12 live deployment - 2026-08-18`, created August 18, 2026 at 2:13 PM. It was permanently deleted under Paulo's standing approval, and capacity changed to 4 / 5 before the new backup was created.

## Package

- File: `dist/pepselect-child-0.25.0-beta.28.zip`
- Size: 1,360,454 bytes
- SHA-256: `b3dbbfe2c1e272d28bdc8f5400615cbd20cf5d8a89f7e93e90a4cde75c0f3fc1`
- WordPress comparison: installed `0.25.0-beta.26`; uploaded `0.25.0-beta.28`.
- ZIP structure was verified before the Staging release: one `pepselect-child/` root with the expected theme files and no repository, dependency, or build directories.

## Released changes

- Replaced GLP-2T supplier/manufacturer boilerplate with the approved Pep Select-specific, mechanism-only description.
- Corrected GLP-2T matching so the approved description, research contexts, citations, and Product JSON-LD description render.
- Added the approved natural use of `research peptide` to the homepage hero.
- Added the owner-confirmed pre-release independent-testing policy sentence to the sitewide footer.
- Preserved Paulo's final Staging decision to omit the adjacent `Review batch-specific Certificates of Analysis in the Quality Archive.` link. The existing footer navigation remains unchanged.

## Live verification

- Homepage: approved hero and footer policy visible; rejected adjacent link absent; no fatal output or horizontal overflow on desktop or mobile.
- GLP-2T: approved description, three research contexts, three verified DOI citations, and matching Product JSON-LD description visible; old supplier boilerplate absent; no fatal output or horizontal overflow on desktop or mobile.
- Shop: product content loaded without fatal output or horizontal overflow.
- Quality Archive: archive content loaded without fatal output. Its known off-screen Xootix side-cart panel can widen the raw document measurement without placing visible content outside the viewport.
- Cart: existing cart and totals loaded; no cart contents were changed.
- Checkout: checkout shell loaded; no order, payment, address, or checkout data was submitted or changed.
- My Account: account content loaded; no customer data was changed.
- Live cache confirmation: `All caches were cleared. Changes usually appear globally within a few minutes.`

## Limitations

- PHP CLI is unavailable in this workspace. WordPress installed and executed the package successfully, and the affected Live routes were browser-tested.
- The previously observed Elementor `elementorFrontendConfig is not defined` console error remains on Live. The tested pages render and the changed flows pass; this release does not claim to resolve that separate script-loading issue.
- No ranking, indexing, PageSpeed, Core Web Vitals, or conversion improvement is claimed. Those outcomes require later measurement.

## Rollback

Restore the named Kinsta Live recovery point for a complete rollback, or reinstall the previously verified child theme `0.25.0-beta.26`. Clear the Live Kinsta cache after rollback and repeat the route checks above.

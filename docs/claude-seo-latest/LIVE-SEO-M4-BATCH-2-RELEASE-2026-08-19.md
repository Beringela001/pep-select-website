# Live SEO Milestone 4 Batch 2 Release — 2026-08-19

## Outcome

Claude SEO Milestone 4 Batch 2 is deployed to Live and owner-approved. The release publishes the evidence-led documentation guide, adds its discovery card to the COA Archive, and preserves the approved Staging presentation.

- Live guide: `https://pepselect.com/guides/how-to-review-research-peptide-documentation/`
- WordPress post ID: `1536`
- Category: `Guides` (`guides`, ID `37`)
- Final child theme: `0.25.0-beta.34`
- Final COA Archive plugin: `0.7.3`

## Recovery point

- Live was at the five-manual-backup limit.
- The verified oldest bottom-row backup was permanently deleted: `Before payment-confirmed email 0.25.0-beta.15 live deployment - 2026-08-18`, created August 18, 2026 at 2:54 PM.
- Replacement recovery point created before deployment: `Before Claude SEO Milestone 4 Batch 2 live deployment - 2026-08-19`.
- Rollback target: restore that named Live backup, then clear all Kinsta caches.

## Packages

- Child theme package: `dist/pepselect-child-0.25.0-beta.34.zip`
  - Size: `2,361,892` bytes
  - SHA-256: `53BEFA05FAF74F147B4306662097E1D97A9A20E742957596F5391DF3126D589E`
- COA Archive package: `Pep Select COA Page/dist/pepselect-coa-archive-0.7.3.zip`
  - Size: `374,191` bytes
  - SHA-256: `49C4221BEA4B2CE414D4B66ED07A322838545032F017C1EFEC2B92775D918198`

The theme moved from Live `0.25.0-beta.28` to `0.25.0-beta.34`. The COA Archive moved from Live `0.7.2` to `0.7.3`. Both remained active after replacement.

## WordPress publication settings

- Status: published
- Permalink structure: `/guides/%postname%/`
- Slug: `how-to-review-research-peptide-documentation`
- Comments: closed
- Pingbacks and trackbacks: closed
- Jetpack publication: `Post only`; no subscriber email was sent
- Excerpt retained for the visible guide introduction
- A pre-existing noindex `Hello world!` sample post was not deleted. The permalink change also places that sample under `/guides/hello-world/`.

## SEO verification

- HTTP `200` on the final guide URL.
- Title: `How to Review Research Peptide Documentation | Pep Select`.
- One H1.
- Robots: `index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1`.
- Self-referencing canonical matches the final guide URL.
- Exactly one public `meta[name="description"]` remains, owned by Yoast.
- The visible excerpt remains unchanged after duplicate-description cleanup.
- Yoast emits `Article` schema with Pep Select Organization references for both author and publisher.
- `post-sitemap.xml` returns `200` and contains the guide.
- `sitemap_index.xml` returns `200` and contains `post-sitemap.xml`.

## Content and visual verification

- Six evidence images and eighteen numbered callouts render.
- The approved `Reported` sentence is present; the removed pass-claim wording is absent.
- The Freedom Diagnostics report is a complete image with no internal scrolling frame.
- Desktop guide width has no horizontal overflow.
- At `390 × 844`, the guide has no horizontal overflow and the report stage height equals its scroll height.
- The COA Archive displays `Read the COA Guide` and links to the final Live URL.
- The COA Archive's existing off-canvas side-cart markup contributes an 8-pixel document-width measurement while closed; no guide or archive content card crosses the viewport.
- Live guide console check returned no errors or warnings.

## Route and commerce checks

All four guide evidence links rendered without a 404:

- `/testing/nad-500-mg/nd50026205jp/`
- `/testing/nad-500-mg/psnad562926jp/`
- `/testing/retatrutide-10mg/rt2026205jp/`
- `/testing/retatrutide-20mg/psrt2062926jp/`

Home, Shop, Cart, Checkout, and My Account rendered without a 404, PHP fatal error, parse error, or visible warning.

## Cache and measurement limits

- Kinsta page, object, and CDN caches were cleared after the release and after the final metadata correction.
- This release verifies technical indexability and sitemap discovery only. It does not claim indexing, rankings, traffic, AI citations, or conversion improvement before measurement.


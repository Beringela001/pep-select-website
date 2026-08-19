# Staging SEO Milestone 4 Batch 2 Release — 2026-08-19

## Outcome

Milestone 4 Batch 2 was deployed to the Pep Select Staging environment only. It adds the first long-form Guides article, a reusable guide template, responsive guide styling, Article schema support, and a Quality Archive discovery card linking to the guide. Paulo approved the guide page; the new discovery card is awaiting final visual approval. Live was not changed.

## Recovery point

- Kinsta Staging backup: `Before Claude SEO Milestone 4 content batch 2 - 2026-08-19`
- Kinsta confirmed that the backup was created.
- Manual backup capacity was full. After verifying timestamps and bottom-list position, the oldest backup removed was `Before homepage trust section 0.22.0-beta.3 - 2026-08-15`, created August 15, 2026 at 8:11 AM.
- Before the Quality Archive discovery-card update, a second named Staging backup was created: `Before Milestone 4 Batch 2 COA guide card - 2026-08-19`, created August 19, 2026 at 11:50 AM.
- Capacity was full for that backup. After verifying timestamps and bottom-list position, the oldest backup removed was `Before SEO Milestone 4 implementation - 2026-08-15`, created August 15, 2026 at 8:49 AM.

## Package

- File: `dist/pepselect-child-0.25.0-beta.29.zip`
- Size: 1,366,616 bytes
- SHA-256: `0c4527c0dad0e93ee11f62e0bdda11347fe74594586246d3c29cb0b11e8ed605`
- ZIP structure verified with one `pepselect-child/` root.
- COA plugin package: `dist/pepselect-coa-archive-0.7.3.zip`
- COA plugin size: 374,191 bytes
- COA plugin SHA-256: `49c4221bea4b2ce414d4b66ed07a322838545032f017c1efec2b92775d918198`
- COA ZIP structure verified with one `pepselect-coa-archive/` root.

## Published Staging page

- Title: `How to Review Research Peptide Documentation`
- Staging URL: `https://stg-pepselect-staging.kinsta.cloud/guides/how-to-review-research-peptide-documentation/`
- WordPress post ID: `1539`
- Category: `Guides`
- Permalink structure: `/guides/%postname%/`
- Jetpack publication setting: `Post only`; no subscriber email was sent.
- Comments and pingbacks: disabled.

## Quality Archive discovery card

- Placement: directly below the Quality Archive hero and above the compound catalog.
- Heading: `What should you look for in a COA?`
- Action: `Read the COA Guide`
- Destination: the approved Staging guide URL.
- Desktop: one horizontal information card with a single clear action.
- Mobile: two-column icon-and-copy layout with a full-width action; visible content fits the viewport.
- The action was clicked in browser validation and opened the expected guide title and H1.
- COA records, archive search, batch identity, product relationships, and commerce behavior were not changed.

## SEO and structured-data verification

- SEO title and meta description match the approved page contract.
- One H1 is present.
- Rendered guide content contains 1,613 words.
- Yoast Article schema is present.
- Article author and publisher both resolve to the existing Pep Select Organization entity at `/#organization`; no individual credentials were invented.
- Publication and modification dates are present.
- Staging correctly emits `noindex, nofollow`. A canonical and sitemap inclusion must be checked after a separately approved Live deployment, because Staging is intentionally excluded from indexing.

## Visual and route verification

- Desktop and 390-pixel mobile layouts render without horizontal content overflow.
- The mobile comparison table becomes readable stacked field cards instead of requiring sideways scrolling.
- Eight quick-check cards, eight question cards, seven comparison rows, and two final actions render.
- The Quality Archive, NAD+ record, Retatrutide record, and Shop links resolve to their Staging equivalents.
- Homepage, Shop, Quality Archive, Cart, Checkout, and My Account loaded with their expected main content after deployment.
- The child theme remained active and Kinsta Staging caches were cleared.

## Limitations and observations

- PHP CLI is unavailable locally. WordPress accepted and executed the installed theme, and the affected pages rendered successfully, providing runtime validation.
- The existing Elementor console warning `elementorFrontendConfig is not defined` remains. It predates this guide and did not prevent the guide or neighboring routes from rendering. Batch 2 does not claim to fix it.
- A browser extension blocked the Staging sitemap request. Staging is noindex, so Live sitemap verification remains a release check rather than a claimed Staging result.
- No ranking, indexing, traffic, or conversion improvement is claimed before measurement.

## Rollback

Restore the named Kinsta Staging backup, clear Kinsta caches, and repeat the guide and neighboring-route checks.

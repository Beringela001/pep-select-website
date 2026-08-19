# Staging SEO Milestone 4 Batch 2 Release — 2026-08-19

## Outcome

Milestone 4 Batch 2 was deployed to the Pep Select Staging environment only. It adds the first long-form Guides article, a reusable guide template, responsive guide styling, Article schema support, and a Quality Archive discovery card linking to the guide. The approved article was expanded with six real Pep Select and laboratory-document examples and eighteen numbered callouts so readers can see the batch, photographed vial, cap, crimp, laboratory source, corrected-strength history, and rejected-batch decisions. The visual revision is awaiting Paulo's final review. Live was not changed.

## Recovery point

- Kinsta Staging backup: `Before Claude SEO Milestone 4 content batch 2 - 2026-08-19`
- Kinsta confirmed that the backup was created.
- Manual backup capacity was full. After verifying timestamps and bottom-list position, the oldest backup removed was `Before homepage trust section 0.22.0-beta.3 - 2026-08-15`, created August 15, 2026 at 8:11 AM.
- Before the Quality Archive discovery-card update, a second named Staging backup was created: `Before Milestone 4 Batch 2 COA guide card - 2026-08-19`, created August 19, 2026 at 11:50 AM.
- Capacity was full for that backup. After verifying timestamps and bottom-list position, the oldest backup removed was `Before SEO Milestone 4 implementation - 2026-08-15`, created August 15, 2026 at 8:49 AM.
- Before the visual-evidence revision, a third named Staging backup was created: `Before Milestone 4 Batch 2 visual evidence revision - 2026-08-19`.
- Capacity was full for that backup. After verifying timestamps and bottom-list position, the oldest backup removed was `Before Claude SEO remediation batch 1 - 2026-08-18`, created August 18, 2026 at 3:12 PM.

## Package

- File: `dist/pepselect-child-0.25.0-beta.30.zip`
- Size: 2,361,151 bytes
- SHA-256: `3533ED3D092FE828BEF30B2F35CFDDD9AA6455AF46D8F45A3AC404DA4734B4FC`
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

- The SEO title remains the approved title. Yoast outputs the revised evidence-led meta description.
- One H1 is present.
- The source guide contains approximately 2,464 words.
- Yoast Article schema is present.
- Article author and publisher both resolve to the existing Pep Select Organization entity at `/#organization`; no individual credentials were invented.
- Publication and modification dates are present.
- Staging correctly emits `noindex, nofollow`. A canonical and sitemap inclusion must be checked after a separately approved Live deployment, because Staging is intentionally excluded from indexing.

## Visual and route verification

- Desktop and 390-pixel mobile layouts render without horizontal page overflow.
- Six evidence images and eighteen numbered visual callouts render.
- The examples cover the current NAD+ record, the supplier-label correction for Retatrutide batch `RT2026205JP`, the associated Freedom Diagnostics report and submitted-vial photograph, rejected NAD+ batch `PSNAD562926JP`, and rejected Retatrutide batch `PSRT2062926JP`.
- The Quality Archive and all four referenced batch-record links resolve to their intended Staging URLs.
- Homepage, Shop, Quality Archive, Cart, Checkout, and My Account loaded with their expected main content after deployment.
- The child theme remained active and Kinsta Staging caches were cleared.

## Limitations and observations

- PHP CLI is unavailable locally. WordPress accepted and executed the installed theme, and the affected pages rendered successfully, providing runtime validation.
- The existing Elementor console warning `elementorFrontendConfig is not defined` remains. It predates this guide and did not prevent the guide or neighboring routes from rendering. Batch 2 does not claim to fix it.
- Staging currently emits two description tags for the guide: the intended Yoast description and a second excerpt-based description from the existing SEO/plugin stack. This plugin overlap must be resolved before the Live release; it does not affect visual approval of the guide body.
- A browser extension blocked the Staging sitemap request. Staging is noindex, so Live sitemap verification remains a release check rather than a claimed Staging result.
- No ranking, indexing, traffic, or conversion improvement is claimed before measurement.

## Rollback

Restore the named Kinsta Staging backup, clear Kinsta caches, and repeat the guide and neighboring-route checks.

# Pep Select SEO Milestone 3 — Staging Release Evidence

**Date:** 2026-08-18  
**Environment:** Staging only  
**Finding authority:** Claude SEO 2.2.4 integrated reports and the Codex findings ledger

## Outcome

Milestone 3's owner-correct technical changes are installed and verified on Staging. No Live deployment, Search Console indexing request, or claim of improved organic visibility was made.

## Recovery point

- Kinsta manual backup: `Before Claude SEO remediation Milestone 3 - 2026-08-18`
- Created: August 18, 2026 at 9:56 PM America/New_York
- Capacity was full. After verifying timestamp and bottom-list position, the exact oldest manual backup, `Before SEO Milestone 3 implementation - 2026-08-14`, was deleted with Paulo's approval.

## Installed packages

| Surface | Installed version | Package | SHA-256 |
|---|---:|---|---|
| Pep Select child theme | `0.25.0-beta.25` | `dist/pepselect-child-0.25.0-beta.25.zip` | `989d81befd3ae6cc7d1a893dbc4159e368baf254072aac922d77215052d6f5a5` |
| Pep Select COA Archive | `0.7.2` | `dist/pepselect-coa-archive-0.7.2.zip` | `1c8ad7ca3904a7c426c248fdebf3c59a76934d0520a37b06a34caf119052b4d6` |

Theme beta.25 supersedes the intermediate beta.24 Staging build. It changes only the schema-context owner hook discovered during acceptance testing; all other Milestone 3 behavior remains the same.

## Implemented evidence

- Impossible `/shop/page/2/` and higher requests return HTTP 404 while the complete one-page Shop remains available.
- WooCommerce Offer seller uses the existing Pep Select `OnlineStore` entity at `/#organization`.
- COA Dataset creator uses the same `/#organization` entity.
- COA Dataset `datePublished` derives from the real WordPress record publication timestamp.
- Yoast and WooCommerce render the exact same `https://schema.org` context. WooCommerce's official root-context filter is used because it wraps the graph after the Product-level filter runs.
- The COA sitemap already includes `/testing/` and every public compound URL; no duplicate sitemap implementation was added.

## Acceptance results

| Check | Result |
|---|---:|
| Home | HTTP 200 |
| Shop | HTTP 200 |
| Invalid Shop page 2 | HTTP 404 |
| Representative BPC-157 product | HTTP 200 |
| Testing archive | HTTP 200 |
| Representative GHK-Cu COA report | HTTP 200 |
| Cart | HTTP 200 |
| Checkout after redirects | HTTP 200 |

Rendered schema evidence:

- Product page contexts: `https://schema.org` and `https://schema.org`, with no trailing-slash variant.
- Offer seller: `OnlineStore` with `@id` ending `/#organization`.
- COA report: `Dataset` creator with `@id` ending `/#organization` and `datePublished` `2026-07-15T16:03:54+00:00`.

## Preserved limitations and dependencies

- Google indexation and organic visibility cannot be closed from Staging. They require Live promotion, recrawl, Search Console URL Inspection, and monitoring.
- Product schema still comes from separate Yoast and WooCommerce emitters. Their context and shared organization references are coherent, but the blocks were not forcibly merged.
- Dataset `license` remains absent because no verified public data-license URL was available. No license was invented.
- Organization `sameAs`, public NAP/contact data, reviews, GTIN/MPN, shipping details, and comparable trust properties remain blocked on truthful business records or policy decisions.
- Bacteriostatic Water remains an intentional cart-upsell item. It was not forced into the Shop because that would change merchandising.
- Google indexing was not requested.

## Rollback

Restore the named Kinsta recovery point, or reinstall the previously verified child theme `0.25.0-beta.21` and COA Archive `0.7.1`. After rollback, clear Kinsta cache and repeat the route, commerce, sitemap, and structured-data checks above.

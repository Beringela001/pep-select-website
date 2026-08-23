# Schema.org Structured Data Re-Verification — pepselect.com
Audit date: 2026-08-23 | Claude SEO plugin v2.2.4 | Read-only measurement, no site changes

## Method
JSON-LD `<script type="application/ld+json">` blocks were parsed directly from the locally
cached HTML in `docs/claude-seo-audit-2026-08-23/raw-crawl/pages/*.html` (all 46 URLs, fetched
live 2026-08-23) using a local regex+`json.loads` extractor (no re-crawl). Full-corpus `grep`
sweeps were run across all 46 parsed JSON-LD dumps for forbidden/fabricated fields
(`aggregateRating`, `reviewCount`, `ratingValue`, `review`, `gtin`, `mpn`), for `FAQPage` /
`Question` / `Answer`, and for `ItemList` / `OfferCatalog`. Representative pages were read in
full: `root.html`, `contact.html`, `guides_how-to-review-research-peptide-documentation.html`,
`shop.html`, `testing.html`, `testing_ghk-cu-50-mg.html` (compound-hub sample),
`testing_ghk-cu-50-mg_psgkcu5071926gx.html` (batch-report sample), `product_bpc157-10.html`
(baseline product), `product_kpv10.html` and `product_cag10.html` (new products).

## Per-ID Status Table

| ID | Priority | Classification | Evidence |
|---|---|---|---|
| SCHEMA-01 | High | PARTIALLY FIXED | Product pages emit 2 top-level JSON-LD objects inside one `<script>` tag (confirmed on `product_bpc157-10.html`, `product_kpv10.html`, `product_cag10.html`, `grep -c '"@context"'` = 2 on all three). Object 1 = Yoast graph (`WebPage`/`ImageObject`/`BreadcrumbList`/`WebSite`/`OnlineStore`, `@id: https://pepselect.com/#organization`). Object 2 = WooCommerce `Product` block whose `Offer.seller.@id` also resolves to `https://pepselect.com/#organization`. Still two independent emitters, not a single unified graph, but consistently cross-linked via the shared Organization `@id`. |
| SCHEMA-02 | Medium | VERIFIED FIXED | `Offer.seller.@id = "https://pepselect.com/#organization"` confirmed identical on `product_bpc157-10.json`, `product_kpv10.json`, `product_cag10.json` (and matches Organization `@id` sitewide). |
| SCHEMA-03 | Medium | VERIFIED FIXED | `testing_ghk-cu-50-mg_psgkcu5071926gx.html`: `Dataset.creator = {"@type":"Organization","@id":"https://pepselect.com/#organization", ...}` — same `@id` as the sitewide Organization/OnlineStore node. |
| SCHEMA-04 | Medium | BLOCKED BY REAL EVIDENCE | Full-corpus grep for `sameAs` on Organization/OnlineStore across all 46 files: zero matches on the Organization node. Only hit is on `guides_how-to-review-research-peptide-documentation.html`, where a `Person` (article author `beringela001`) has `"sameAs":["https://pepselect.com"]` — not an Organization identity graph. No real off-site social/business profile URLs available to add without fabrication. |
| SCHEMA-05 | Medium | STILL OPEN (blocker lifted) | **JSON-LD**: `telephone`/`contactPoint`/`PostalAddress`/`streetAddress` are absent from the Organization/OnlineStore node and from the `/contact/` `WebPage` object in every file (confirmed via corpus-wide grep — zero JSON-LD matches). **Raw HTML**: `contact.html` now contains a real, live phone number in two places — the contact-page "hub" block (`Call us: <a href="tel:+18337377528">1 (833) 737-7528</a>`, line 1149) and the sitewide footer (`Phone: <a href="tel:+18337377528">1 (833) 737-7528</a>`, line 1221). The same footer phone link appears on `root.html`, `shop.html`, `product_bpc157-10.html`, `testing.html`, `faq.html` (grep count = 1 each). **Conclusion**: the phone number reported as added 8/20–22 is confirmed present and real, resolving the prior "no real phone to cite" blocker — but it has NOT been added to the Organization/OnlineStore or ContactPage JSON-LD. This is now an actionable gap rather than a data-availability blocker; reclassified from BLOCKED BY REAL EVIDENCE to STILL OPEN. |
| SCHEMA-06 | Medium | STILL OPEN | Corpus-wide grep for `gtin`, `mpn`, `review`, `aggregateRating`, `reviewCount`, `ratingValue` returns zero matches across all 46 files — confirmed truthfully absent, not fabricated. `priceValidUntil` and `OfferShippingDetails` also absent from every `Offer` checked (bpc157-10, kpv10, cag10). |
| SCHEMA-07 | Low | SUPERSEDED | Homepage `BreadcrumbList` (`root.html`) still has exactly 1 `ListItem` (`{"position":1,"name":"Home"}`, no `item` URL) — unchanged from prior audit, confirmed superseded/no-op finding (single-item breadcrumb on the homepage is expected/correct; Google does not require a URL on the terminal item). |
| SCHEMA-08 | Low | SUPERSEDED | Corpus-wide grep for `"FAQPage"`, `"Question"`, `"Answer"` across all 46 files: zero matches, including `faq.html` (which renders FAQ content as plain HTML with no structured markup). Correct post-2026-05-07 FAQ rich-result retirement; no regression. |
| SCHEMA-09 | Medium | PARTIALLY FIXED | `testing_ghk-cu-50-mg_psgkcu5071926gx.html` Dataset: `datePublished: "2026-07-15T16:03:54+00:00"` — real ISO-8601, not fabricated. `license` field checked via grep on 3 sampled batch pages (ghk-cu, nad, tb-500 batches): zero matches — still absent, correctly not fabricated. |
| SCHEMA-10 | Low | VERIFIED FIXED | Every JSON-LD object in the corpus uses exactly `"@context": "https://schema.org"` (confirmed on every top-level object read: root, contact, guides, shop, testing, testing hub, testing batch, 3 product pages). No `http://` variants found. |
| ECOM-06 | Medium | STILL OPEN | Same finding as SCHEMA-06 at the Offer level: `sku`, `price`/`priceCurrency` (via `UnitPriceSpecification`), `availability`, `seller`, `hasMerchantReturnPolicy` present on every product Offer sampled (bpc157-10, kpv10, cag10); `gtin`, `mpn`, `priceValidUntil`, `OfferShippingDetails` confirmed absent, not fabricated. |
| ECOM-08 | Medium | STILL OPEN | `shop.html` JSON-LD graph contains only `WebPage`/`ImageObject`/`BreadcrumbList`/`WebSite`/`OnlineStore` — no `ItemList` or `OfferCatalog` node. Corpus-wide grep for `ItemList`/`OfferCatalog` across all 46 files: zero matches anywhere on the site, including `shop.html` and `testing.html`. |
| GEO-07 | Medium | STILL OPEN | Compound-hub page `testing_ghk-cu-50-mg.html` JSON-LD graph = `WebSite`/`OnlineStore`/`WebPage`/`BreadcrumbList` only — no `Dataset`, `ItemList`, or any reference to the individual batch reports listed on the page. Batch-level `Dataset`/`DataCatalog`/`DataDownload` markup exists only one level down, on the 9 individual batch-report pages (confirmed via `testing_ghk-cu-50-mg_psgkcu5071926gx.html` and cross-checked against `extracted.json` type lists for all 8 hub / 9 batch pages). Hub pages still show batch history as plain text/links with zero structured data despite functioning as an index of Datasets. |
| GEO-04/GEO-05 | Medium | BLOCKED BY REAL EVIDENCE | Same underlying gap as SCHEMA-04: no Organization `sameAs` / off-site identity graph anywhere in the corpus. No real social/business-directory profile URLs currently exist to cite without fabrication. |

## New Findings (SCHEMA-11+)

**SCHEMA-11 (Medium, NEW) — Real business phone number live on-site but not reflected in any JSON-LD.**
Confirmed distinct from SCHEMA-05's original framing: this is not merely "no telephone data
exists" — a genuine, working phone number (`+1 833 737 7528`) is now published sitewide (footer)
and prominently on `/contact/`. Google's `LocalBusiness`/`Organization` `telephone` and
`ContactPage`'s `contactPoint` properties are natural fits and require no fabrication — the
value already exists in visible HTML. Recommended JSON-LD addition (values taken verbatim from
`contact.html`, no placeholders):

```json
{
  "@context": "https://schema.org",
  "@type": "ContactPoint",
  "telephone": "+1-833-737-7528",
  "contactType": "customer service",
  "email": "support@pepselect.com",
  "areaServed": "US"
}
```
This should be added as `OnlineStore.telephone` and/or `OnlineStore.contactPoint` on the
`https://pepselect.com/#organization` node (propagates sitewide via the shared `@id`), not
fabricated per-page. `PostalAddress`/`streetAddress` remain unverified — do not add unless a
real, publishable business address exists [VERIFY CLAIM — mailing address not observed anywhere
in the crawled HTML].

**SCHEMA-12 (Low, NEW, informational) — Person `sameAs` on guide author is self-referential.**
`guides_how-to-review-research-peptide-documentation.html`'s `Person` node (`beringela001`) has
`"sameAs": ["https://pepselect.com"]`, i.e., it points back to the site's own homepage rather
than an external author profile. Not invalid per spec, but it provides no external identity
signal and is unlikely to add topical/E-E-A-T value in its current form. Low priority; no action
required unless a real author bio/profile page is planned.

## Compliance Check (fabrication guard)

- No `aggregateRating`, `reviewCount`, `ratingValue`, `review`, `gtin`, or `mpn` was found
  anywhere in the 46-page corpus (verified via corpus-wide grep across all extracted JSON-LD
  dumps). **Pass — no fabrication.**
- `MerchantReturnPolicy` confirmed unchanged and consistent everywhere sampled (root, contact,
  guides, shop, testing, testing hub, testing batch, bpc157-10, kpv10, cag10):
  `"returnPolicyCategory": "https://schema.org/MerchantReturnNotPermitted"`,
  `"applicableCountry": "US"`. **Pass — no drift.**

## Drift Check — New Products (KPV, Cagrilintide)

`/product/kpv10/` and `/product/cag10/` both emit the same 2-block pattern as every other
product page (Yoast graph + WooCommerce `Product` block), with `sku`, `price`/`priceCurrency`,
`availability`, `seller.@id` matching the shared Organization node, and
`hasMerchantReturnPolicy` referencing the shared `#merchant-return-policy` node. No missing
fields relative to baseline (`product_bpc157-10.html`); no GTIN/MPN/review/rating fabricated on
either. **No regressions found on the new products.**

## Changes Since 2026-08-20

- **SCHEMA-05 reclassified**: BLOCKED BY REAL EVIDENCE → **STILL OPEN**. A real phone number
  (`1 (833) 737-7528`) was confirmed added to visible sitewide HTML (footer + `/contact/`) between
  2026-08-20 and 2026-08-23, but it has not been propagated into the Organization/OnlineStore or
  ContactPage JSON-LD. This removes the previous "no real phone exists" blocker.
- New finding **SCHEMA-11** captures the concrete, low-effort fix opportunity created by that
  drift (add `telephone`/`contactPoint` using the now-real, non-fabricated number).
- New products KPV10 and CAG10 (published 2026-08-21/22 per their `datePublished` fields)
  verified schema-complete and consistent with the existing product template — no drift or
  regression.
- All other SCHEMA-*/ECOM-*/GEO-* findings unchanged from prior classifications.

## Stop Conditions
None. No paid/metered API calls were made (no DataForSEO), no URLs were submitted, no Search
Console changes were made, and no site files were modified. All findings above are drawn from
locally cached HTML fetched live 2026-08-23.

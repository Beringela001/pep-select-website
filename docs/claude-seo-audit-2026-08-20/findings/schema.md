# Structured Data Verification Audit — pepselect.com (Live)

**Audit date:** 2026-08-20
**Scope:** SCHEMA-01 through SCHEMA-10, GEO-02, GEO-07 (plus ECOM-08 noted, not owned)
**Method:** Live GET fetches via `render_page.py --mode auto --json --json-ld-output`, all pages returned `is_spa: false` and served JSON-LD server-rendered in raw HTML (no client-side injection risk for this audit). Raw JSON-LD extracted to bounded artifacts under `docs/claude-seo-audit-2026-08-20/raw-crawl/jsonld/*.json`; page-level summaries under `docs/claude-seo-audit-2026-08-20/raw-crawl/*-summary.json`.

**Sample (11 URLs, all HTTP 200):**

| Page | URL |
|---|---|
| Homepage | `https://pepselect.com/` |
| Shop | `https://pepselect.com/shop/` |
| Product — NAD+ | `https://pepselect.com/product/nad/` |
| Product — GLP-2T 20mg | `https://pepselect.com/product/glp2-t20/` |
| Product — Bacteriostatic Water 30mL (no-description case) | `https://pepselect.com/product/bacteriostatic-water-30ml/` |
| Compound history | `https://pepselect.com/testing/nad-500-mg/` |
| Completed batch report | `https://pepselect.com/testing/nad-500-mg/nd50026205jp/` |
| Quality Archive hub | `https://pepselect.com/testing/` |
| Contact | `https://pepselect.com/contact/` |
| FAQ | `https://pepselect.com/faq/` |
| Documentation guide (M4 Batch 2 release) | `https://pepselect.com/guides/how-to-review-research-peptide-documentation/` |

No mutations were made. All requests were GET only.

---

## 1. @context and entity-graph consolidation (SCHEMA-01 / SCHEMA-02 / SCHEMA-03 / SCHEMA-10)

Every sampled template still emits **two separate `<script type="application/ld+json">` blocks on product pages** (a Yoast `@graph` block covering `WebPage`/`WebSite`/`BreadcrumbList`/`OnlineStore`, and a second WooCommerce `Product` block). Non-product templates emit only the Yoast `@graph` block.

**Both blocks render the exact root context `"@context": "https://schema.org"`** (verified string match, no `http://`, no missing block) on every sampled page, including the two-emitter product pages. Example — NAD+ product page, block 2 (WooCommerce):

```json
{
  "@context": "https://schema.org",
  "@type": "Product",
  "@id": "https://pepselect.com/product/nad/#product",
  "name": "NAD+",
  ...
  "offers": [
    {
      "@type": "Offer",
      "availability": "https://schema.org/InStock",
      "seller": {
        "@type": "OnlineStore",
        "@id": "https://pepselect.com/#organization",
        "name": "Pep Select",
        "url": "https://pepselect.com/"
      },
      "price": "51.99",
      "priceCurrency": "USD",
      "hasMerchantReturnPolicy": {
        "@id": "https://pepselect.com/#merchant-return-policy"
      }
    }
  ]
}
```

And the Yoast block (block 1) on the same page defines the canonical entity:

```json
{
  "@type": "OnlineStore",
  "@id": "https://pepselect.com/#organization",
  "name": "Pep Select",
  "url": "https://pepselect.com/",
  ...
}
```

**Offer.seller `@id`** resolves to `https://pepselect.com/#organization` on all three sampled products (NAD+, GLP-2T 20mg, Bacteriostatic Water 30mL) — confirmed identical across all three.

**Dataset.creator `@id`** on the completed batch report (`/testing/nad-500-mg/nd50026205jp/`) also resolves to the same `https://pepselect.com/#organization`, rendered as `Organization`:

```json
"creator": {
  "@type": "Organization",
  "@id": "https://pepselect.com/#organization",
  "name": "Pep Select",
  "url": "https://pepselect.com/"
}
```

**Assessment:** the three shared-`@id` claims from the M3 Live release (`https://pepselect.com/#organization` used consistently by Offer.seller, Dataset.creator, and the Yoast `OnlineStore` entity) hold on Live as of 2026-08-20, across a broader sample than the original single-product spot check (now verified on 2 in-stock + 1 out-of-stock product, plus a completed COA batch page). The `@context` normalization also holds — no `http://schema.org` or mixed-context instances were found in any of the 11 pages sampled.

**What has not changed:** the Yoast and WooCommerce blocks remain two independent `<script>` emitters. The Product block does not reference the Yoast `WebPage`/`BreadcrumbList` graph (no `mainEntityOfPage`, no `isPartOf` back to the page), and the Yoast graph does not reference the `Product` entity. Linkage is by shared `@id` string match on the Organization entity only, not a single merged `@graph`. This matches the documented "Live partial" state for SCHEMA-01 exactly — no regression, no further consolidation.

---

## 2. Product / Offer completeness (SCHEMA-06 / ECOM-06 territory, informational)

Sampled Product blocks (NAD+, GLP-2T 20mg, Bacteriostatic Water 30mL) all confirm the same shape: `name`, `url`, `image`, `sku`, `brand.name`, `offers[].price`, `priceCurrency`, `availability`, `seller`, `hasMerchantReturnPolicy`. Cross-checked absence of fabricated/invented properties — **truthfully absent on all three**, not fabricated:

- `gtin`/`gtin8`/`gtin12`/`gtin13`/`gtin14`/`mpn` — absent
- `offers[].priceValidUntil` — absent
- `offers[].shippingDetails` (`OfferShippingDetails`) — absent
- `review` — absent
- `aggregateRating` — absent

Bacteriostatic Water 30mL (the no-description case) confirms the Product block has **no `description` field at all** — WooCommerce did not synthesize a placeholder value, and no other fabricated field was found. This is the correct behavior under the no-fabrication policy.

`availability` correctly differs by real stock state: `https://schema.org/InStock` for NAD+ and Bacteriostatic Water, `https://schema.org/OutOfStock` for GLP-2T 20mg — confirming the flag is live-derived, not templated.

---

## 3. sameAs / NAP (SCHEMA-04, SCHEMA-05, GEO-04) — confirmed still absent

Checked the `OnlineStore` (`#organization`) entity on every sampled page (home, shop, 3 products, compound history, batch report, testing hub, contact, FAQ, guide). It consistently contains only `name`, `url`, `logo`/`image`, and `hasMerchantReturnPolicy`. No sampled instance carries:

- `sameAs` (any array or string)
- `telephone`
- `address` / `PostalAddress`
- `contactPoint` / `ContactPoint`

The `/contact/` page's own `WebPage` block (name "Contact - Pep Select") likewise carries no `ContactPage`-specific NAP properties — it is a plain `WebPage` in the `@graph`, identical in shape to every other static page.

One incidental new entity was found on the documentation guide: a WordPress author-archive `Person` (`beringela001`) with `"sameAs": ["https://pepselect.com"]` — this is a self-referential internal link generated by the Yoast author-archive default, not a real external identity/social profile, and it is not attached to the Organization entity. It does not change the SCHEMA-04/GEO-04 disposition.

**Confirmed: business-blocked, unchanged.**

---

## 4. BreadcrumbList depth (SCHEMA-07)

- Homepage: still a single `ListItem` ("Home"), no `item` URL on it — unchanged from the original finding.
- Shop: 2-deep (Home → Shop), each `ListItem` has a real `item` URL except the terminal node (expected/correct pattern).
- Product pages: 3-deep (Home → Shop → Product name).
- Compound history: 3-deep (Home → Certificate of Analysis Archive → Compound).
- Batch report: 4-deep (Home → Certificate of Analysis Archive → Compound → Batch).
- Contact/FAQ: 2-deep.
- Guide: 2-deep (Home → Guide title).

Homepage remains the only single-node trail. No action is warranted per the original "Superseded / no immediate fix" disposition — unchanged.

---

## 5. FAQPage / Q&A structured data (SCHEMA-08, GEO-02)

`/faq/` renders only `WebPage` + `BreadcrumbList` + `WebSite` + `OnlineStore` in its `@graph` — **no `FAQPage`, no `Question`/`Answer` types, no `QAPage`.** This is correct under the current hard rule (Google retired FAQ rich results for all sites 2026-05-07); SCHEMA-08's "no action for rich-result chasing" disposition is satisfied by continuing to do nothing here.

GEO-02 (genuinely extractable Q&A content with no Q&A structured data) is also unchanged: the visible FAQ content on `/faq/` still has no corresponding `QAPage`/`Question` markup. Per current guidance this is **not** a Google SERP opportunity and any AI/GEO benefit is unconfirmed — so this remains a low-urgency, owner-discretionary item, not a defect. No regression, no fix.

---

## 6. Dataset schema — COA batch report (SCHEMA-09) and compound-hub pages (GEO-07)

### Batch report `/testing/nad-500-mg/nd50026205jp/` — Dataset block

```json
{
  "@type": "Dataset",
  "@id": "https://pepselect.com/testing/nad-500-mg/nd50026205jp/#dataset",
  "name": "NAD+ batch ND50026205JP laboratory results",
  "identifier": "ND50026205JP",
  "isAccessibleForFree": true,
  "creator": {
    "@type": "Organization",
    "@id": "https://pepselect.com/#organization",
    "name": "Pep Select",
    "url": "https://pepselect.com/"
  },
  "includedInDataCatalog": {
    "@type": "DataCatalog",
    "name": "Pep Select Quality Archive",
    "url": "https://pepselect.com/testing/"
  },
  "variableMeasured": [ /* 8 PropertyValue entries: Claimed content, Average net content, Purity, Identity, Endotoxin, Heavy metals, Sterility, Fentanyl screen */ ],
  "datePublished": "2026-07-27T02:21:21+00:00",
  "provider": { "@type": "Organization", "name": "Freedom Diagnostics Testing" },
  "dateCreated": "2026-07-31",
  "measurementTechnique": ["LC-MS", "HPLC-UV", "Immunoassay"],
  "distribution": [
    { "@type": "DataDownload", "encodingFormat": "application/pdf", "contentUrl": "https://pepselect.com/wp-content/uploads/2026/08/PepS2607280579.pdf" }
  ]
}
```

- `datePublished` is `2026-07-27T02:21:21+00:00` — ISO 8601, a real distinct timestamp (not a bulk-resave artifact; differs from `dateCreated`/`dateModified` patterns seen elsewhere), matching the M3 release claim of deriving from the true WordPress publication timestamp. **Confirmed present.**
- `license` — **confirmed still absent** from the Dataset object. No fabricated license URL was found. Per business rule, this stays absent until Pep Select has an approved public data-license URL.
- No fabricated `aggregateRating`/`review`-style properties were added to Dataset. `creator` @id-links to the same shared Organization entity (see §1).

### Compound-hub page `/testing/nad-500-mg/` (GEO-07)

The compound-history hub's `@graph` contains only `WebSite`, `OnlineStore`, `WebPage`, `BreadcrumbList` — **no `Dataset`, `ItemList`, or any structured data representing the batch history table itself.** The hub page displays real batch records (batch numbers, dates, links to individual COA pages) as plain HTML/text with zero structured-data representation of that list. This is unchanged from the original finding — **still open.**

---

## 7. Quality Archive hub (`/testing/`)

`@graph` contains `WebSite`, `OnlineStore`, `CollectionPage` (not `Dataset`/`ItemList`), and `BreadcrumbList`. No structured-data representation of the compound list itself. Consistent with GEO-07's broader pattern (informational only, not separately tracked under a distinct ID).

---

## 8. Shop page — ItemList/OfferCatalog (ECOM-08, noted not owned)

`/shop/` `@graph` contains only `WebPage`, `ImageObject`, `BreadcrumbList`, `WebSite`, `OnlineStore` — **no `ItemList` or `OfferCatalog`.** Confirmed unchanged; flagged for the ECOM owner, not scored here.

---

## 9. Documentation guide — Article schema (M4 Batch 2 claim)

```json
{
  "@type": "Article",
  "@id": ".../#article",
  "author": { "@id": "https://pepselect.com/#organization" },
  "headline": "How to Review Research Peptide Documentation",
  "datePublished": "2026-08-19T16:58:44+00:00",
  "dateModified": "2026-08-19T16:58:46+00:00",
  "publisher": { "@id": "https://pepselect.com/#organization" },
  "wordCount": 2351,
  "articleSection": ["Guides"]
}
```

Confirmed: both `author` and `publisher` reference the shared `https://pepselect.com/#organization` entity, exactly as documented in the M4 Batch 2 release. `@type: Article` is a valid, non-deprecated Google type. Not one of the ledger's owned IDs, but included as corroborating evidence that the entity-graph consolidation pattern is being applied consistently to new content types, not just Product/Dataset.

---

## Prior Finding Classifications

| ID | Original Priority | Prior State (8/18) | Current Classification | Evidence |
|---|---|---|---|---|
| SCHEMA-01 | High | Live partial — contexts consistent, Offer seller connected, but Yoast/WooCommerce remain separate emitters | PARTIALLY FIXED | Both product-page JSON-LD blocks confirmed `"@context": "https://schema.org"` on NAD+/GLP-2T/Bacteriostatic Water; still two independent `<script>` blocks with no cross-reference beyond shared Organization `@id` (`docs/claude-seo-audit-2026-08-20/raw-crawl/jsonld/product-nad.json`) |
| SCHEMA-02 | Medium | Live verified — Offer.seller = OnlineStore, `@id` `/#organization` | VERIFIED FIXED | Confirmed identical `seller` block with `@id: https://pepselect.com/#organization` on all 3 sampled products (`product-nad.json`, `product-glp2t20.json`, `product-bacwater.json`) |
| SCHEMA-03 | Medium | Live verified — Dataset.creator = Organization, `@id` `/#organization` | VERIFIED FIXED | `batch-nad500-jp.json`: `Dataset.creator` is `{"@type":"Organization","@id":"https://pepselect.com/#organization", ...}` |
| SCHEMA-04 | Medium | Blocked / input needed — no `sameAs` on Organization/OnlineStore | BLOCKED BY REAL EVIDENCE | `OnlineStore` (`#organization`) block on all 11 sampled pages contains only `name`/`url`/`logo`/`hasMerchantReturnPolicy` — no `sameAs` anywhere; requires real, approved social/entity profiles |
| SCHEMA-05 | Medium | Blocked / input needed — no NAP/contactPoint on Organization or /contact/ | BLOCKED BY REAL EVIDENCE | Same `OnlineStore` block and `/contact/` `WebPage` block both lack `telephone`/`address`/`contactPoint` (`contact.json`); requires Paulo-approved public NAP |
| SCHEMA-06 | Medium | Conflicting/validate — Product/Offer missing recommended trust properties | STILL OPEN | GTIN/MPN/`priceValidUntil`/`OfferShippingDetails`/`review`/`aggregateRating` confirmed truthfully absent (not fabricated) on NAD+, GLP-2T, Bacteriostatic Water; no real backing records exist yet to add them |
| SCHEMA-07 | Low | Superseded/no immediate fix — homepage BreadcrumbList has 1 ListItem | SUPERSEDED | `home.json`: `BreadcrumbList.itemListElement` still has exactly one entry ("Home"); no action needed per original disposition |
| SCHEMA-08 | Low | Conflicting/validate — no FAQPage anywhere, including /faq/ | SUPERSEDED | `faq.json` confirms no `FAQPage`/`Question`/`Answer` types present; correct behavior given Google retired FAQ rich results 2026-05-07 |
| SCHEMA-09 | Medium | Live partial/input needed — real `datePublished`, `license` intentionally absent | PARTIALLY FIXED | `batch-nad500-jp.json`: `datePublished: "2026-07-27T02:21:21+00:00"` (real, ISO 8601) present; `license` property confirmed absent, no fabrication |
| SCHEMA-10 | Low | Live verified — Yoast/WooCommerce contexts both exact `https://schema.org` | VERIFIED FIXED | Confirmed exact-string match `"@context": "https://schema.org"` in both JSON-LD blocks on all product pages sampled, and in the single block on all non-product pages |
| GEO-02 | Medium | Conflicting/validate — no Q&A structured data despite genuine Q&A content | STILL OPEN | `faq.json` confirms no `QAPage`/`Question` markup on `/faq/`; no Google SERP benefit exists post-retirement, so this remains owner-discretionary rather than a defect, but the gap itself is unchanged |
| GEO-07 | Medium | Not started — COA compound-hub pages carry no Dataset/structured data | STILL OPEN | `compound-nad500.json` (`/testing/nad-500-mg/`) `@graph` has only `WebSite`/`OnlineStore`/`WebPage`/`BreadcrumbList` — no `Dataset`/`ItemList` represents the visible batch-history table |

---

## Limitations

- Sample is 11 URLs; not every product/compound/batch combination in the catalog was checked. The pattern is consistent enough across in-stock, out-of-stock, and no-description products that broader regression is unlikely, but not exhaustively proven.
- No Google Rich Results Test / Search Console Enhancement report was pulled in this pass (read-only GET scope); classification is based on direct JSON-LD structural validation only.
- `render_page.py` reported `is_spa: false` and no Playwright fallback was triggered on any sampled page — all JSON-LD is confirmed server-rendered in raw HTML, so raw vs. rendered content comparison was not necessary for this slice.

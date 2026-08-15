# SEO Milestone 4 — Search Console, Merchant Eligibility, and Performance

Prepared: 2026-08-15  
Status: Ready for staging implementation after approval  
Live baseline: Pep Select child theme `0.22.0-beta.5`

## Outcome

Turn the first useful Search Console signals into one meaningful release: resolve only truthful merchant-listing opportunities, establish a dependable search-performance baseline, and improve measurable page speed without changing landing-page copy or commerce behavior.

## Search Console evidence

Google reported two new non-critical structured-data messages on 2026-08-15. Both reports show **0 invalid items, 1 valid item**, and affect the same example URL:

- `https://pepselect.com/product/glp3-r30/`
- Product snippets: missing `review` and `aggregateRating`.
- Merchant listings: missing offer-level `hasMerchantReturnPolicy`, `shippingDetails`, `validFrom`, and a global identifier such as GTIN or brand.
- The example was last crawled on 2026-08-14. Search Console was still processing broader indexing data, so reporting lag must be separated from current live output.

These are enhancement suggestions, not indexing failures. No immediate live repair or Search Console validation was started.

## Current live truth after Milestone 3

The live GLP-3 R page currently emits:

- one valid `Product` with SKU `GLP3R30`;
- one in-stock USD `Offer` with current WooCommerce price and URL;
- `brand` as `Pep Select`;
- `seller` as `Pep Select` / `OnlineStore`;
- one `OnlineStore` graph node with the public Refund & Shipping Policy link;
- no fabricated rating, review, GTIN, MPN, price-validity date, return term, or shipping promise.

Because `brand` is already present in live markup after the report's crawl, the identifier warning may clear after Google recrawls. Do not add a fake GTIN or MPN.

## Work packages

### 1. Recrawl-aware structured-data audit

- Capture the current Search Console counts and affected example before changes.
- Validate every intended product URL against current live WooCommerce, Yoast, and child-theme output.
- Confirm whether Google still reports the brand warning after it recrawls `0.22.0-beta.5`.
- Treat a warning that has already been corrected in current live markup as monitoring work, not another code change.

### 2. One connected store-policy graph

- Give the global `MerchantReturnPolicy` a stable `@id` under the existing `OnlineStore` node.
- Reference that same policy from each `Offer` instead of duplicating or contradicting it.
- Keep the public policy URL as the source of truth.
- Review whether the current policy supports `applicableCountry: US` and `MerchantReturnNotPermitted` for shipped orders. The policy allows pre-shipment cancellation and remedies for damaged or incorrect orders, but states that returns are not accepted after shipment; encode only a representation that matches that distinction exactly.
- Do not modify the visible Refund & Shipping Policy in this milestone unless Paulo separately approves its wording.

### 3. Shipping eligibility without invented promises

- Audit current WooCommerce shipping zones, methods, rates, and destination restrictions read-only.
- Compare those settings with the visible United States-only policy and actual checkout results.
- Add a global `ShippingService` and offer reference only if destination, rate, handling time, and transit-time data can be sourced exactly.
- Mark any missing rate or carrier-time fact `[VERIFY CLAIM]` and keep it out of production markup.
- Preserve checkout, shipping calculations, taxes, free-shipping thresholds, and order behavior unchanged.

### 4. Optional fields: deliberate decisions

- `review` and `aggregateRating`: remain absent unless genuine reviews are visible on the same product page and WooCommerce can provide the exact count and rating. A Google enhancement warning is not permission to invent social proof.
- `validFrom`: remain absent for ordinary evergreen prices unless WooCommerce has a real scheduled-sale start or another exact source-backed effective date.
- GTIN/MPN: remain absent unless Pep Select has authentic identifiers assigned to the exact sellable item.
- `brand`: keep the current truthful `Pep Select` Brand node and verify that Google clears the older warning after recrawl.

### 5. Performance and crawl baseline

- Capture repeatable mobile and desktop lab measurements for homepage, Shop, GLP-3 R, Quality Archive, and one batch record.
- Record LCP, INP proxy/TBT, CLS, transferred bytes, request count, and largest assets before implementation.
- Fix only code- or asset-level causes that can be isolated without changing approved landing-page copy, product data, checkout, OPS, or COA relationships.
- Recheck sitemap status, canonical host, HTTPS, indexability, and structured-data enhancement counts after deployment.
- Treat the current lack of Core Web Vitals field data as a monitoring baseline, not a failure.

### 6. Staging, release, and Google follow-through

- Create a named staging backup before implementation; if full, delete only the oldest backup at the bottom.
- Package theme changes as the next versioned ZIP in `dist/`, print SHA-256, and verify the archive before upload.
- Validate all product markup plus desktop/mobile commerce, account, COA, and printed-QR routes on staging.
- Deploy to live only after Paulo approves the verified milestone.
- Create a fresh live backup, clear caches, repeat the live crawl and smoke tests, then use Search Console validation only for issues actually corrected and visible to Google.

## Acceptance criteria

- Search Console remains at zero critical Product snippet and Merchant listing errors.
- Every intended product continues to emit truthful Product, Offer, SKU, price, currency, availability, seller, URL, image, description, and brand values.
- Return and shipping graph references resolve to one global store-policy source without duplicate or conflicting entities.
- No fabricated reviews, ratings, identifiers, delivery times, shipping prices, return windows, or price dates.
- All intended sitemap URLs retain 200 responses, one canonical host, correct canonicals, and intended indexability.
- Representative mobile and desktop performance measurements improve or remain neutral; no Core Web Vitals regression is introduced.
- Shop, product, cart, checkout entry, account, Quality Archive, compound, batch, rewards, VerifyPass, OPS, and the NAD printed-QR redirect pass smoke tests.

## Non-goals

- No landing-page copy rewrite.
- No SKU, product strength, price, inventory, order, payment, tax, shipping-rule, customer, rewards, OPS, or COA-data changes.
- No fake GTIN, MPN, review, aggregate rating, or merchant policy detail.
- No Google Ads, Merchant Center feed, or paid-campaign launch.
- No Search Console `Validate fix` action before the corresponding live output is verified.

## Rollback

- Restore the pre-deployment Kinsta backup for database-held changes.
- Reinstall the prior verified child-theme ZIP for code changes.
- Clear Kinsta and WordPress caches, then repeat commerce, archive, redirect, schema, and sitemap checks.

## Primary references

- Google merchant listings: https://developers.google.com/search/docs/appearance/structured-data/merchant-listing
- Google merchant return policy: https://developers.google.com/search/docs/appearance/structured-data/return-policy
- Google merchant shipping policy: https://developers.google.com/search/docs/appearance/structured-data/shipping-policy
- Google product snippets: https://developers.google.com/search/docs/appearance/structured-data/product-snippet
- Google structured-data policies: https://developers.google.com/search/docs/appearance/structured-data/sd-policies

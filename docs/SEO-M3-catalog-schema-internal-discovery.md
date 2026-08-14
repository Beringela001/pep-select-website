# SEO Milestone 3 — Catalog Consolidation, Structured Data, and Internal Discovery

Status: prepared — ready for staging kickoff  
Prepared: 2026-08-14  
Deployment boundary: staging first; live deployment requires a fresh backup and explicit approval after verification

## Outcome

Make the catalog easier for search engines and researchers to understand by choosing one primary catalog surface, making product structured data factual and product-specific, and strengthening crawlable connections between products, batch documentation, and supporting pages.

This is one coordinated architecture milestone. It does not rewrite landing-page copy, launch articles, change product names, or alter commerce data.

## Live baseline

- The current sitemap emits 45 unique, valid, indexable URLs.
- Every sitemap URL has at least one internal link; there are no sitemap orphans.
- Three URLs have only one inbound sitemap-page link: About Us, Bacteriostatic Water 30mL, and the Research Compounds product category.
- Shop and the Research Compounds product category are separate indexable URLs with substantially overlapping catalog purpose. Milestone 2 gave them unique snippets, but the primary-catalog decision remains unresolved.
- All 15 individual product pages emit `Product` and `Offer` structured data with WooCommerce SKU, price, currency, availability, seller, image, and URL data.
- Fourteen product schema descriptions repeat the generic phrase `High-purity research peptide`; Bacteriostatic Water has an empty schema description. These values are not sufficiently product-specific and the generic purity wording should not be asserted without page-level evidence.
- No product markup fabricates ratings or reviews. This must remain true unless genuine review data exists and is visible on the corresponding product page.
- Product offers currently emit `priceValidUntil: 2027-12-31`. The source and truth of this date must be established; it should be removed if it is not an actual pricing commitment.
- Breadcrumb schema is present across the crawl and should be preserved.
- Yoast currently emits `Organization`, `WebSite`, and page graph markup broadly. Product routes receive WooCommerce product markup. COA routes expose `WebPage` or `CollectionPage` plus breadcrumbs.
- Google Search Console still needs time to refresh its historical discovered-page and indexing reports after Milestone 2.

## Preserve / Refine / Replace / Remove

### Preserve

- WooCommerce as the source of truth for product IDs, SKUs, prices, stock, availability, currency, and product URLs.
- COA Archive as the source of truth for compound, batch, status, result, and product relationships.
- Existing valid breadcrumb markup and crawlable navigation.
- Yoast ownership of ordinary page titles, descriptions, canonicals, and sitemap generation.
- The current non-www canonical host and exact legacy redirects.

### Refine

- Product schema descriptions so each mirrors approved, visible product information without unsupported claims.
- Product-to-documentation and documentation-to-product links where a real COA relationship exists.
- Anchor text so links describe the destination without hype or vague `learn more` language.
- Organization schema only where an accurate `OnlineStore` subtype or policy data materially improves the graph.

### Replace

- The generic repeated product-schema description with a product-specific, page-supported description.
- The empty Bacteriostatic Water schema description with approved visible product information, or omit it until truthful copy exists.
- Any artificial `priceValidUntil` value with a real source-backed date, or no value.

### Remove or consolidate

- Resolve the duplicate catalog architecture. Recommended default: make `/shop/` the primary catalog and 301 `/product-category/research-compounds/` to `/shop/` if no menu, filter, ad, analytics, or integration depends on the category archive.
- If a redirect would break a real dependency, use the fallback: canonicalize the category archive to Shop, set it to `noindex`, remove it from the sitemap, and retain the route for compatibility.
- Do not add rating, review, medical, purity, shipping-time, return-policy, loyalty, or Dataset markup unless the corresponding visible facts and source systems are verified.

## Work packages

### 1. Catalog consolidation and dependency audit

- Trace every link, menu item, WooCommerce setting, filter, campaign URL, analytics reference, and plugin dependency that points to the Research Compounds category.
- Choose the redirect or compatibility fallback using the evidence above.
- Preserve product archives, add-to-cart behavior, filters, pagination, sorting, breadcrumbs, and analytics attribution.
- Update sitemap expectations from 45 to 44 indexable URLs only if the category archive is consolidated.

### 2. Product structured-data truth

- Trace the exact WooCommerce and Yoast filters generating the current Product and Offer graphs.
- Map all 15 product IDs and SKUs to their approved visible descriptions without changing product names, SKU relationships, price, stock, or COA connections.
- Remove unsupported generic purity wording from schema output.
- Verify `priceValidUntil`; retain it only if a real source system owns that date.
- Add `brand: Pep Select` only if the implementation accurately describes the seller's own branded products and passes validation.
- Do not manufacture `aggregateRating`, `review`, `mpn`, `gtin`, shipping, or return-policy properties.

### 3. Store and policy entity graph

- Review whether Pep Select should be represented as `OnlineStore` rather than generic `Organization` on the homepage or About page.
- Compare the live Refund & Shipping Policy and rewards rules with Google's current merchant-return-policy and loyalty-program requirements.
- Add only properties supported by exact current policy and system data. Mark anything uncertain `[VERIFY CLAIM]` and keep it out of production markup.
- Avoid duplicating or fighting Yoast's graph IDs.

### 4. Internal discovery and COA pathways

- Build a URL-to-URL map for Shop, products, compound records, batch records, FAQ, About, and policies.
- Add descriptive, crawlable `<a href>` links only where the destination helps the current page's user.
- Preserve the COA plugin's product and batch relationships; never infer or invent a test record.
- Improve inbound discovery for About Us and the primary catalog through natural navigation or contextual links.
- Treat Bacteriostatic Water as an accessory product and avoid implying a use case that is not already approved and visible.
- Do not add `Dataset` schema to COA pages unless a separate eligibility review establishes that the visible records satisfy Google's Dataset guidance.

### 5. Validation, deployment, and monitoring

- Create a named staging Kinsta backup before implementation. If capacity is full, delete only the oldest backup at the bottom.
- Record active theme, Yoast, WooCommerce, and COA plugin versions.
- Validate representative product, in-stock, out-of-stock, accessory, Shop, category, compound, and batch URLs using structured-data and browser checks.
- Crawl every intended sitemap URL on staging, then repeat on live only after deployment approval.
- Create a new live backup before deployment, clear caches, and retain the previous verified package for rollback.
- Use Search Console enhancement and URL Inspection reports after Google recrawls; do not treat immediate reporting lag as a failed deployment.

## Architecture placement

- Use WooCommerce structured-data filters for product and offer corrections, keeping WooCommerce values canonical.
- Keep theme-specific contextual links in the child-theme templates that render those surfaces.
- Keep COA relationships and COA-page presentation in the COA plugin; do not copy batch data into the child theme.
- Use Yoast-supported filters or settings for Organization, canonical, robots, and sitemap behavior.
- Do not edit WordPress core, WooCommerce core, Yoast, or third-party plugin files.

## Non-goals

- No landing-page or policy rewrite.
- No new blog, article, glossary, or content-hub launch.
- No Google Ads, Merchant Center feed, or paid-campaign work.
- No price, stock, SKU, discount, tax, shipping, payment, checkout, order, customer, rewards, VerifyPass, OPS, or COA-data changes.
- No fabricated reviews, ratings, identifiers, testing facts, policies, scientific claims, or human-use implications.
- No broad Core Web Vitals redesign; performance becomes a later milestone after this architecture is stable.

## Acceptance criteria

- One documented primary catalog URL, with the secondary route safely redirected or excluded from indexing without breaking dependencies.
- Every intended indexable URL returns 200, has one H1, a self-referencing canonical, and unique title and description.
- Every individual product page emits valid Product and Offer markup whose name, SKU, price, currency, availability, seller, URL, image, and description match visible WooCommerce data.
- No product schema contains generic unsupported purity wording, an empty required business value, or a fabricated review/rating.
- `priceValidUntil` is source-backed or absent.
- Organization or OnlineStore markup contains only verified facts and does not conflict with Yoast graph IDs.
- Every intended indexable page has a crawlable inbound link with descriptive anchor text.
- Product and COA links appear only where the database relationship exists.
- Desktop and mobile Shop, product, COA, cart, checkout, account, rewards, and verification smoke tests pass.
- Final staging and live crawls report zero fatal errors, stale canonicals, accidental `noindex`, or sitemap regressions.

## Test checklist

- In-stock product and out-of-stock product.
- Bacteriostatic Water accessory product.
- Product with current COA, previous COA, failed/not-released COA, and no COA.
- Shop sorting, pagination, search, add to cart, cart, and checkout entry.
- Logged-out and logged-in account navigation.
- Desktop, tablet, and mobile product and archive layouts.
- Rich Results validation for product, breadcrumb, and organization markup.
- Exact SKU, price, stock, availability, image, canonical, and redirect comparisons before and after.
- Full sitemap crawl and Search Console inspection after deployment.

## Rollback

- Restore the pre-deployment Kinsta backup for database-held Yoast or taxonomy changes.
- Reinstall the prior verified child-theme or plugin ZIP for code changes.
- Restore the original category indexability only if the primary-catalog consolidation causes a verified dependency failure.
- Clear Kinsta and WordPress caches, then repeat the commerce and sitemap smoke checks.

## Primary references

- Google product structured data: https://developers.google.com/search/docs/appearance/structured-data/product
- Google product snippets: https://developers.google.com/search/docs/appearance/structured-data/product-snippet
- Google merchant listings: https://developers.google.com/search/docs/appearance/structured-data/merchant-listing
- Google organization markup: https://developers.google.com/search/docs/appearance/structured-data/organization
- Google breadcrumb markup: https://developers.google.com/search/docs/appearance/structured-data/breadcrumb
- Google link best practices: https://developers.google.com/search/docs/crawling-indexing/links-crawlable
- Google structured-data policies: https://developers.google.com/search/docs/appearance/structured-data/sd-policies

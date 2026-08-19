# Staging SEO Performance Verification — Theme 0.25.0-beta.39

**Date:** 2026-08-19  
**Environment:** Staging only  
**Package:** `dist/pepselect-child-0.25.0-beta.39.zip`  
**Size:** 2,365,402 bytes  
**SHA-256:** `EFF07C6CF97D358B9AAB654F6925CCA3D160000F406DDC7802F953C262F26B9A`

## Sitemap diagnosis

The sitemap configuration is not broken.

- The guide is present in `https://pepselect.com/post-sitemap.xml` at `https://pepselect.com/guides/how-to-review-research-peptide-documentation/`.
- Retatrutide 10 mg is present in `https://pepselect.com/product-sitemap.xml` at `https://pepselect.com/product/glp3-r10/`.
- Both child sitemaps are linked from `https://pepselect.com/sitemap_index.xml`.

Search Console's earlier “No referring sitemap detected” result was stale discovery/reporting state, not a current sitemap omission. Both URLs were submitted for indexing in the 2026-08-19 checkpoint.

## Render-path changes

- Consolidated Elementor's four Google Fonts stylesheets into one request without changing the requested families or variants.
- Added early connections to Google Fonts CSS and font delivery hosts.
- Inlined six small Pep Select shell styles while preserving their source order and rules.
- On the non-shopping Quality Archive only, removed seven unused rewards, dynamic-pricing, back-in-stock, and Select2 styles plus the three native WooCommerce page-layout styles.
- Preserved jQuery, WooCommerce commerce scripts, checkout, rewards, pricing, product, side-cart, and COA business logic.

## Staging verification

Verified on the homepage, Shop, NAD+ product page, and Quality Archive:

- one consolidated Google Fonts request and zero old Elementor Google Fonts requests;
- one inline Pep Select shell block and zero external shell-style requests;
- unchanged computed heading font families;
- header and footer present;
- no fatal or critical WordPress error;
- Shop retains fourteen unique product links;
- NAD+ retains its Add to Cart control, current-batch summary, and batch-report link;
- Quality Archive retains its COA guide link and archive content;
- the side-cart opens from the Quality Archive and still shows subtotal and Checkout;
- the Quality Archive's existing 8-pixel desktop overflow matches Live and was not introduced by this package.

The Quality Archive now loads zero matching rewards, pricing, back-in-stock, Select2, or native WooCommerce page-layout styles. Commerce routes retain those assets.

## Mobile PageSpeed spot check

Two Staging mobile Lighthouse runs were made minutes apart on the Quality Archive. These are lab results, not field/Core Web Vitals data, so they show direction rather than a guaranteed score.

| Measurement | Before final route cleanup | After final route cleanup |
|---|---:|---:|
| Performance | 69 | 78 |
| First Contentful Paint | 4.2 s | 2.9 s |
| Largest Contentful Paint | 4.2 s | 3.5 s |
| Total Blocking Time | 0 ms | 0 ms |
| Cumulative Layout Shift | 0 | 0 |
| Render-blocking estimate | 3,150 ms | 2,050 ms |
| First-party blocking transfer | 98.4 KiB | 81.1 KiB |
| Google Fonts blocking duration | 2,020 ms | 900 ms |

Final PageSpeed report: https://pagespeed.web.dev/analysis/https-stg-pepselect-staging-kinsta-cloud-testing/0lkbupl6uv?form_factor=mobile

Staging's Lighthouse SEO score is intentionally reduced by its noindex protection and is not comparable with Live's SEO score.

## Remaining blockers and boundary

The remaining Quality Archive list is the COA stylesheet, Elementor/Hello layout styles, the page-specific Elementor stylesheet, Google Fonts, and shared JavaScript dependencies including jQuery, jQuery Migrate, BlockUI, Underscore, and `wp-util`.

Those files currently draw the page or support shared WooCommerce/cart behavior. They were not blindly removed or deferred because doing so could create a visual flash or break shopping behavior. A later batch should address them only with component-level replacement or dependency testing.

## Recovery and release state

- Staging backup: `Before Claude SEO measured render-path cleanup beta.37 - 2026-08-19`.
- Live backup: `Before SEO render-path cleanup 0.25.0-beta.39 live deployment - 2026-08-19`.
- The installer confirmed the active Live theme moved from `0.25.0-beta.38` to `0.25.0-beta.39`, preserving the Control Ops supplier-order restocking changes already committed in beta.38.
- Live caches were cleared after activation.
- Live smoke checks passed on the homepage, Shop, NAD+ product page, and Quality Archive: headers, footers, product links, Add to Cart, COA guide link, consolidated fonts, inlined shell styles, and route-specific removals were present without a fatal WordPress error.
- The Live side-cart shell, current cart contents, subtotal, and Checkout action remained available; no cart contents were changed.

# SEO Performance Batch — 2026-08-28

**Source report:** `docs/claude-seo-audit-2026-08-28/`  
**Claude SEO:** 2.2.4  
**Implementation owner:** Pep Select child theme  
**Release candidate:** `0.25.0-beta.60`

## Scope

Reduce the measured render and transfer cost on the fully coded Home, Shop,
product, and Quality Archive templates without changing WooCommerce, side-cart,
checkout, payment, rewards, COA, access-gate, analytics, or Search Console
behavior. Remove the unused Site Kit AdSense advertising payload globally after
Paulo confirmed that Pep Select does not run Google ads.

## Findings ledger

| Claude finding | Priority | Verified evidence | Existing Pep state | Implementation status | Reason |
|---|---|---|---|---|---|
| PERF-14: homepage desktop regression | High | Lighthouse home desktop: 59, TBT 1,770 ms. Google tag and AdSense produced about 1.26 s of the five longest recorded tasks. The exit-offer asset is a small local footer script and was not identified in those tasks. | Performance cleanup shipped in beta.39; the audit statement that the program was not started conflicts with repository and release history. | Partially ready | Remove broken/unused Elementor assets now. Site Kit analytics and AdSense require a business decision before changing their loading or availability. |
| PERF-02: render-blocking requests | High | Lighthouse lists 40 render-blocking requests on home, including unused Elementor and non-critical WooCommerce helpers. | Partial cleanup already exists in `inc/performance.php`. | Implemented locally | Elementor assets are removed only on fully coded templates; verified helper scripts are deferred with dependency order preserved. |
| PERF-03: image delivery | Medium | Lighthouse estimates 202–369 KB savings; header and footer logos alone transfer 107,017 and 82,106 bytes at roughly 224 px and 190 px rendered widths. | Responsive hero WebP chain already shipped; global logos remained 1,400 px PNGs. | Implemented locally | Lossless 448 px WebP replacements retain two-device-pixel coverage and save about 146 KB across the two responses. The access-gate logo remains unchanged because that plugin has concurrent uncommitted work. |
| TECH-09: console error | Low | Lighthouse records `elementorFrontendConfig is not defined` from `elementor/assets/js/frontend.min.js`. | Fully coded templates do not render Elementor widgets, but its orphaned runtime bundle still loads. | Implemented locally | Remove Elementor front-end runtime assets on coded templates while preserving editor/preview requests. |
| PERF-13: AdSense chain | Medium | Site Kit injects AdSense on every sampled storefront template; Lighthouse attributes 254,718 bytes and 543.9 ms main-thread time on the slow desktop-home run. | External Site Kit configuration; Paulo confirmed Pep Select does not run Google ads. | Implemented locally | Site Kit's supported AdSense-only filters block standard and AMP advertising tags. Analytics, Search Console, and Tag Manager behavior remain unchanged. |

## Files

- `pepselect-child/inc/performance.php`
- `pepselect-child/inc/header-preview.php`
- `pepselect-child/inc/footer-preview.php`
- `pepselect-child/assets/images/brand/pep-select-logo-header-448.webp`
- `pepselect-child/assets/images/brand/pep-select-logo-footer-448.webp`
- `pepselect-child/style.css`
- `pepselect-child/CHANGELOG.md`
- `tests/test-performance-assets.js`

## Verification

- PHP syntax check for all modified PHP files.
- Performance safeguard contract test.
- Git whitespace check.
- Pixel-exact comparison between each resized RGBA source and its decoded
  lossless WebP counterpart at the target dimensions.
- Required next gate: deploy the saved candidate to Staging, purge caches, and
  verify Home, Shop, product, Quality Archive, cart, side cart, and checkout on
  desktop/mobile before any Live decision.

## Success and stop checks

- No Elementor CSS/JS response on the four coded templates.
- No `pagead2.googlesyndication.com` or Google/DoubleClick advertising request.
- Google Analytics and existing WooCommerce event measurement remain present.
- No `elementorFrontendConfig is not defined` console error.
- Header and footer use the 448 px WebP sources with unchanged rendered geometry.
- Catalog, add-to-cart, side-cart, BOGO, rewards, stock notification, COA, and
  access-gate behavior remain unchanged.
- Stop if verification shows the AdSense-only filters affect Analytics,
  Search Console, Tag Manager configuration, or WooCommerce event measurement.

## Rollback

Revert release candidate `0.25.0-beta.60`; the original PNG assets remain in
the theme and no database or external-service setting changes are included.

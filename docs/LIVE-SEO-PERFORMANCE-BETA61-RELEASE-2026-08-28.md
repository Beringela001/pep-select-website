# Live SEO Performance Release — beta61 — 2026-08-28

## Outcome

Pep Select `0.25.0-beta.61` was released to Live after a full Live-to-Staging
refresh, Staging verification, and a new Live manual rollback backup.

The release removes unused Elementor front-end assets from the fully coded Home,
Shop, product, and COA Archive templates, blocks only Site Kit's unused AdSense
tags, defers verified non-critical commerce helpers, and serves right-sized
lossless header and footer logos. Google Analytics and Tag Manager remain active.

## Package

- File: `dist/pepselect-child-0.25.0-beta.61.zip`
- SHA-256: `1383A7D188DEAF295E139661660A41EA822A0BC30A8AF4BD9210E16835398340`
- Size: 2,561,978 bytes
- Git commit: `3641232` (`Cover Elementor 4.1 generated styles`)

## Deployment safeguards

- Refreshed Staging from Live before validation because Staging was stale.
- Deleted only the verified oldest Live manual backup after confirming the Live
  manual-backup list was at its 5/5 limit.
- Removed backup: `Before Order Experience 0.3.3 related cards deployment - 2026-08-27`,
  created Aug 27, 2026 at 10:36 AM.
- Created and verified the new 5/5 Live restore point:
  `Before SEO performance beta61 live deployment - 2026-08-28`.
- Cleared all Staging caches after testing and all Live caches after release.

## Public runtime verification

Logged-out checks passed on Home, Shop, `/product/glp3-r10/`, COA Archive,
Cart, and Checkout:

- AdSense advertising assets: 0.
- Elementor front-end assets on the four coded SEO templates: 0.
- Google Analytics / Tag Manager remains present on every audited Live template.
- Optimized 448 px WebP header and footer logos are served on Live.
- Homepage browser warnings and errors: 0.
- Add to cart, cart contents, checkout billing fields, and Place Order control
  loaded successfully. No order was submitted.

## Fresh Google PageSpeed results

Captured Aug 28, 2026 at 1:06–1:08 PM EDT with Lighthouse 13.4.1. These are
lab results; Google reports no field-data sample for the origin.

| URL | Desktop | Mobile | Desktop LCP / TBT | Mobile LCP / TBT | Fresh report |
|---|---:|---:|---:|---:|---|
| Home | **98** | **85** | 1.0 s / 10 ms | 3.3 s / 20 ms | [Google PSI](https://pagespeed.web.dev/analysis/https-pepselect-com/rs0ub5phbv?form_factor=desktop) |
| Shop | **92** | **76** | 1.3 s / 130 ms | 4.7 s / 90 ms | [Google PSI](https://pagespeed.web.dev/analysis/https-pepselect-com-shop/m5l91ifzf5?form_factor=desktop) |
| GHK-Cu product | **94** | **79** | 1.0 s / 160 ms | 4.5 s / 80 ms | [Google PSI](https://pagespeed.web.dev/analysis/https-pepselect-com-product-ghk-cu/hck1v60g25?form_factor=desktop) |

Comparison with the Claude audit captured earlier the same day:

| URL | Before desktop | After desktop | Before mobile | After mobile |
|---|---:|---:|---:|---:|
| Home | 59 | **98** | 58–71 | **85** |
| Shop | 95 | 92 | 59–60 | **76** |
| GHK-Cu product | 86 | **94** | 57–58 | **79** |

An additional GLP-3 R run showed normal mobile improvement but a cold desktop
outlier: desktop 59 (FCP/LCP 4.1 s, TBT 0 ms) and mobile 77 (LCP 4.5 s, TBT
90 ms). That result is retained at [Google PSI](https://pagespeed.web.dev/analysis/https-pepselect-com-product-glp3-r10/ynt0izk8b3?form_factor=desktop)
to make the observed run-to-run/server-response variance explicit.

## Remaining measured opportunities

The homepage's remaining mobile score is dominated by initial rendering rather
than JavaScript execution: 20 ms TBT, 3.3 s LCP, and 0.084 CLS. Google lists
render-blocking styles/fonts and 123 KiB of image-delivery savings. Further image
or font changes may affect visual assets or visual rendering and were not made
under the current approval boundary.


# Live SEO Image Release — beta62 — 2026-08-28

## Outcome

Pep Select `0.25.0-beta.62` and an asset-only rebuild of PS Access Gate 2.2.1
were released to Live after Staging verification and a new Live rollback backup.
The logo artwork, colors, copy, layout, intrinsic aspect ratios, gate settings,
and gate behavior were preserved.

## Delivered files

- Access-gate logo: 107,017-byte 1400×299 PNG replaced by a visually matched,
  transparent 768×164 PNG of 22,780 bytes.
- Header logo: the 448×96 WebP was re-encoded from the original PNG at high
  quality, reducing it from 26,050 to 20,760 bytes.
- Responsive header source: new 320×68 WebP of 14,950 bytes with `srcset` and
  explicit `sizes`; wider and high-density displays retain the 448 px source.
- The full-resolution original PNG assets remain available for rollback.

Image comparison on white and Pep Select navy backgrounds found an average
composited RGB error below one level for the access-gate derivative. Staging
screenshots confirmed no visible logo or layout change.

## Packages

- `dist/pepselect-child-0.25.0-beta.62.zip`
  - SHA-256: `B49F2004F7CD0BFF42DDF5F9084D29317950EEF2CAD839912010E69A344325FD`
  - Size: 2,572,095 bytes
- `dist/ps-access-gate-2.2.1.zip`
  - SHA-256: `ED21CE1865C7C7E30A5741E4B1BD62ED30C72BC5799CE19107B7FD61EB8F33F9`
  - Size: 40,848 bytes
  - Production-only package: PHP, readme, and bundled logo; no test folder.
- Git implementation commit: `e577842` (`Optimize responsive brand logo delivery`).

## Deployment safeguards

- Verified and deleted only the oldest Live manual backup at the 5/5 limit:
  `Before contiguous US shipping beta54 live deployment - 2026-08-27`, created
  Aug 27, 2026 at 9:02 PM.
- Created and verified the new 5/5 restore point:
  `Before SEO image optimization beta62 live - 2026-08-28`.
- Deployed and visually verified both packages on Staging before Live.
- Cleared every Staging and Live cache after deployment.

## Live verification

- Live gate asset: HTTP 200, `image/png`, 22,780 bytes.
- Live responsive header asset: HTTP 200, `image/webp`, 14,950 bytes.
- Browser-selected header source and `srcset`/`sizes`: verified.
- AdSense assets: 0; Elementor front-end assets on the coded homepage: 0.
- Google Analytics / Tag Manager remains present.
- Browser warnings and errors: 0.
- Add to cart, cart contents, checkout billing fields, and Place Order control:
  passed. No order was submitted.

## Fresh Google measurements

Google now reports 42.5 KiB of first-party logo resources instead of 129.9 KiB,
an 87.4 KiB (67%) measured reduction. Its estimated remaining image-delivery
opportunity fell from 122.6 KiB to 35.2 KiB.

Three post-release mobile lab runs:

| Run | Score | FCP | LCP | TBT | CLS | Report |
|---|---:|---:|---:|---:|---:|---|
| 1 | 67 | 2.9 s | 3.8 s | 510 ms | 0.084 | [Google PSI](https://pagespeed.web.dev/analysis/https-pepselect-com/lp9vbjt20i?form_factor=mobile) |
| 2 | 65 | 4.0 s | 6.1 s | 110 ms | 0 | [Google PSI](https://pagespeed.web.dev/analysis/https-pepselect-com/ibo73m056m?form_factor=mobile) |
| 3 | 67 | 2.9 s | 3.8 s | 510 ms | 0.084 | [Google PSI](https://pagespeed.web.dev/analysis/https-pepselect-com/1rzg7gfpfz?form_factor=mobile) |
| **Median** | **67** | **2.9 s** | **3.8 s** | **510 ms** | **0.084** | — |

The two repeat mobile samples immediately before beta62 scored 65 and 66 with
6.6 s and 6.5 s LCP. The image release materially reduced the median LCP, but
intermittent JavaScript blocking remains and prevents the score from rising in
proportion to the image improvement.

Desktop remains **98** with 0.8 s LCP, 20 ms TBT, and 0.006 CLS in the second
post-release report.


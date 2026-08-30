# Live post-Elementor PageSpeed rerun — beta71

Date: 2026-08-30  
Capture window: 3:24–3:29 AM EDT  
Lighthouse: 13.4.1  
Theme: Pep Select `0.25.0-beta.71`

## Scope

Single Google PageSpeed Insights lab run on each stable public coded template after Elementor Core, Elementor Pro, and Marquee Addons were removed from Live. Google reports no origin field-data sample, so the results below are lab measurements and can vary between runs.

## Results

| Template | Mobile | Desktop | Mobile LCP / TBT / CLS | Desktop LCP / TBT / CLS |
|---|---:|---:|---|---|
| Home | **90** | **89** | 3.6 s / 20 ms / 0 | 0.8 s / 0 ms / 0.004 |
| Shop | **80** | **84** | 5.1 s / 40 ms / 0 | 1.3 s / 170 ms / 0.002 |
| GLP-3 R product | **93** | **100** | 3.2 s / 50 ms / 0 | 0.7 s / 40 ms / 0.001 |
| Quality Archive | **97** | **97** | 2.3 s / 40 ms / 0 | 0.9 s / 100 ms / 0.001 |

Quality Archive report: [Google PageSpeed Insights](https://pagespeed.web.dev/analysis/https-pepselect-com-testing/fqaanq2m4r?form_factor=desktop)

## Comparison with the recorded pre-retirement baseline

- Home mobile improved from the stable beta62 median of **67** to **90**. LCP moved from 3.8 s to 3.6 s while TBT fell from 510 ms to 20 ms.
- Shop mobile improved from **76** to **80**. Its remaining 5.1 s LCP is now the clearest storefront performance target.
- The prior GLP-3 R sample scored mobile **77** and desktop **59** during a documented cold desktop outlier. The new matching product sample scored mobile **93** and desktop **100**.
- Home desktop scored 89 despite 0.8 s LCP and 0 ms TBT because this run recorded an anomalous 5.7 s Speed Index. The earlier beta62 desktop score was 98. This is lab variance, not evidence that Elementor removal made the core render slower.
- Shop desktop likewise scored below its earlier 92 sample while retaining a fast 1.3 s LCP; server response, font timing, and a 4.2 s Speed Index dominated that run.
- No earlier directly comparable Quality Archive result was recorded.

## Checkout exclusion

Google's stateless test reached checkout with an empty cart and followed WooCommerce's empty-checkout redirect, so the generated result did not measure a populated checkout. That score is excluded. A normal browser request independently resolved `/checkout/`, rendered the Checkout heading, and remained covered by the beta71 functional smoke gate.

## Remaining measured opportunities

The recurring opportunities are render-blocking CSS/fonts, right-sized images, explicit image dimensions, and about 76–77 KiB of unused JavaScript on the public coded templates. Shop should be the first optimization target because its mobile LCP remains 5.1 s. The Quality Archive has a large image-delivery estimate but already scores 97 on both form factors; optimize it only if image changes can preserve COA evidence fidelity.

## Conclusion

The rerun supports a material mobile performance improvement after the retirement work, especially on Home and the representative product page. Elementor is no longer the dominant performance issue. Further work should target Shop LCP and shared render-blocking/font delivery, with repeat samples used before treating isolated desktop scores as regressions.

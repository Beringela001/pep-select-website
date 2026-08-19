# Performance Audit — pepselect.com (Lab)

Date: 2026-08-18
Method: Google PSI API v5 (Lighthouse 13.x, insight-based audits), `psi-only` mode (no CrUX field data pulled — field data is owned by a separate agent). Direct `curl` observation of TTFB, HTTP response headers (compression, caching, CDN) for cross-validation of Lighthouse's throttled lab numbers. Mobile strategy only (Lighthouse mobile emulation is Google's primary ranking signal surface; desktop was not run for this pass — see Limitations).

URLs audited (from `https://pepselect.com/sitemap_index.xml` plus the requested non-sitemap `/testing/` page):
- Home: `https://pepselect.com/`
- Product: `https://pepselect.com/product/bpc157-10/`
- Shop (WooCommerce archive): `https://pepselect.com/shop/`
- Testing/COA lookup: `https://pepselect.com/testing/` (200 OK, intentionally absent from the Yoast sitemap)

Raw run summary (Lighthouse mobile, single run each):

| URL | Perf score | LCP | FCP | TBT | CLS | Speed Index | Total requests | Total weight |
|---|---|---|---|---|---|---|---|---|
| Home | 69 | 6.0 s | 3.2 s | 75 ms | 0.084 | 3.4 s | 114 | 1,710,045 B (1,670 KiB) |
| Product | 76 | 4.7 s | 3.3 s | 40 ms | 0 | 3.3 s | 116 | 1,089,904 B |
| Shop | 72 | 5.7 s | 3.2 s | 30 ms | 0.018 | 3.7 s | 113 | 1,191,826 B |
| Testing | 65 | 5.0 s | 3.2 s | 130 ms | 0 | 12.8 s | 87 | 1,260,540 B |

Resource-type breakdown (`resource-summary` audit, requestCount / transferSize bytes):

| URL | Script | Stylesheet | Image | Font |
|---|---|---|---|---|
| Home | 44 req / 464,246 B | 49 req / 134,346 B | 10 req / 926,403 B | 7 req / 152,349 B |
| Product | 48 req / 492,314 B | 47 req / 146,198 B | 10 req / 265,027 B | 7 req / 152,666 B |
| Shop | 41 req / 461,565 B | 41 req / 132,216 B | 20 req / 413,391 B | 7 req / 152,346 B |
| Testing | 36 req / 419,870 B | 32 req / 123,762 B | 9 req / 543,250 B | 6 req / 141,733 B |

This confirms (fresh, 2026-08-18) the prior inline-audit observation of 31–49 render-blocking-eligible stylesheets and 34–48 scripts on templated pages — the CSS/JS request counts above land inside that range on all four templates.

---

### [PERF-01] LCP is "Poor" (>4.0 s) on all four audited templates
- Priority: Critical
- Category: LCP
- Evidence class: 2-PageSpeed lab
- Evidence: Lighthouse mobile LCP — Home 6.0 s (score 0.13), Product 4.7 s, Shop 5.7 s, Testing 5.0 s. All four exceed the 4.0 s "Poor" ceiling; none are even in the 2.5–4.0 s "Needs Improvement" band. `interactive` (time-to-interactive) is numerically identical to LCP on Home/Product/Shop (6.0 s / 4.8 s / 5.7 s), indicating LCP is the last major paint event gated by render-blocking resources rather than a late-injected element.
- Affected URLs: `/`, `/product/bpc157-10/`, `/shop/`, `/testing/`
- Reasoning: A single lab run cannot establish the CrUX 75th-percentile pass/fail Google actually scores, but a mobile LCP of 4.7–6.0 s under Lighthouse's standard throttling profile leaves effectively no headroom — real-world visitors on slower connections/devices will see worse. This is a first-order, whole-site condition, not a page-specific defect, so it should be prioritized above individual asset fixes.
- Recommendation: Treat LCP as the single top KPI for this audit cycle. The concrete levers are covered in PERF-02 (render-blocking CSS/JS) and PERF-03 (image weight) below — LCP will not move without addressing both, since the render-blocking chain delays when the LCP element can even begin downloading/painting, and the LCP image itself is heavier than necessary once it starts.
- Dependencies: Blocks/depends on PERF-02, PERF-03. Unblocks any future INP/CLS work being measured meaningfully (currently overshadowed by LCP failure).
- Failure check: A follow-up PSI lab run still shows LCP >4.0 s mobile on any of the four templates after PERF-02/03 are implemented.
- Success check: A follow-up PSI lab run shows LCP ≤2.5 s (ideally) or at minimum inside the 2.5–4.0 s band, on all four templates, with `interactive` no longer pinned to the same value as LCP.
- Leading indicator: Lighthouse "largest-contentful-paint" `score` field (currently 0.13 Home / ~0.2–0.3 elsewhere) trending toward 0.9+; PSI Lighthouse `performance` category score (currently 65–76) trending toward 90+.

### [PERF-02] Render-blocking CSS/JS consumes ~50–62% of all stylesheet+script requests, adding an estimated 2.7–3.0 s to first paint on every template
- Priority: Critical
- Category: LCP / render-blocking
- Evidence class: 2-PageSpeed lab
- Evidence: `render-blocking-insight` audit — Home: 58 render-blocking resources of 93 CSS+JS requests (62%), Est. savings 2,940 ms. Product: 56 of 95 (59%), Est. savings 2,970 ms. Shop: 50 of 82 (61%), Est. savings 2,730 ms. Testing: 41 of 68 (60%), Est. savings 2,800 ms. Top individual blockers by `wastedMs`: Home — `woocommerce.css` (629 ms, 11,558 B), `underscore.min.js` (472 ms, 8,443 B), `cards.css` (315 ms, 4,983 B); Product — `jquery.min.js` (1,100 ms), `xoo-wsc-style.css` (472 ms), `jquery.blockUI.min.js` (315 ms); Shop — `style.min.css` [WordPress core] (1,297 ms), `woocommerce.css` (649 ms), `jquery.min.js` (973 ms); Testing — Google Fonts Roboto request (901 ms, see PERF-07), `style.min.css` (1,365 ms), `pepselect-coa-frontend.css` (854 ms), `frontend.min.css` (513 ms).
- Affected URLs: `/`, `/product/bpc157-10/`, `/shop/`, `/testing/`
- Reasoning: Roughly 6 in 10 CSS/JS requests on every template are marked render-blocking by Lighthouse, meaning the browser must download and parse them before it can paint. This is the direct mechanical cause of PERF-01: FCP (3.2–3.3 s, consistent across all four pages) is already late, and LCP trails a further 1.4–2.8 s behind FCP because the LCP element's own resources queue behind this blocking chain. The consistency of the FCP number (3.2–3.3 s) across four structurally different templates strongly suggests a shared render-blocking cause (theme/plugin CSS and jQuery-family scripts loaded head-blocking on every page) rather than page-specific content.
- Recommendation: Reduce the number of render-blocking stylesheets/scripts loaded in `<head>` — defer or async non-critical CSS/JS (jQuery UI, Underscore, WooCommerce/side-cart CSS not needed for above-the-fold content), and inline or preload only the CSS needed for the first viewport. Do not change WooCommerce, Elementor, or plugin core files; scope changes to enqueue/loading strategy only.
- Dependencies: Depends on identifying which of the 41–58 blocking resources are above-the-fold-critical per template (differs by template, so this needs template-by-template triage before implementation). Unblocks PERF-01.
- Failure check: A follow-up `render-blocking-insight` audit still reports >40% of CSS+JS requests as render-blocking, or `Est savings` remains >1,500 ms.
- Success check: `render-blocking-insight` estimated savings drops below ~500 ms and FCP moves below 2.0 s on all four templates.
- Leading indicator: `render-blocking-insight` `Est savings of X ms` value and `total_items` count, re-checked without a full audit re-run via `claude-seo run pagespeed_check.py <url> --psi-only`.

### [PERF-03] Hero and content images are the single largest identified byte-waste source, led by an unoptimized homepage hero PNG
- Priority: High
- Category: LCP / image optimization
- Evidence class: 2-PageSpeed lab
- Evidence: `image-delivery-insight` audit, Home page: `PS-laying_fam-768x434.png` — `fetchpriority="high"` (explicitly marked by the theme as the priority image), totalBytes 367,813 B, wastedBytes 329,794 B (90% waste). Second item: `tesamorelin-10mg-coa-source.webp` — 308,112 B, wastedBytes 291,690 B (95% waste), `loading="lazy" decoding="async"`. Third: `tesamorelin-10mg-vial-batch.webp` — 113,818 B, wastedBytes 90,812 B (80% waste). Combined `image-delivery-insight` estimated savings on Home: "Est savings of 775 KiB" against a total image payload of 926,403 B — i.e. the tool estimates ~84% of all image bytes on the homepage are recoverable waste.
- Affected URLs: `/` (primary — hero image is `fetchpriority="high"`, i.e. the developer already flagged it as LCP-critical but did not right-size it)
- Reasoning: The `fetchpriority="high"` attribute on `PS-laying_fam-768x434.png` is strong circumstantial evidence this is (or is intended to be) the LCP element on Home, since Lighthouse and browsers use that hint for LCP prioritization. A 367 KB PNG serving a 768×434 slot with 90% estimated waste means the source file is far larger/less compressed than the displayed dimensions require — classic un-resized-source or wrong-format (PNG instead of WebP/AVIF) issue. This directly extends Home's LCP time (6.0 s, the worst of the four templates).
- Recommendation: Re-export the homepage hero image at its actual display dimensions in WebP or AVIF, and re-check the two `why-pep-select` WebP images (COA and vial-batch) for correct sizing relative to their rendered `boundingRect` — both show ~80–95% waste ratios despite already being WebP, which points to oversized source dimensions rather than format.
- Dependencies: None blocking; can be implemented independently of PERF-02. Unblocks PERF-01 (Home).
- Failure check: Re-run `image-delivery-insight` shows the same file(s) with >50% wastedBytes ratio.
- Success check: `image-delivery-insight` total estimated savings for Home drops from "775 KiB" to a low double-digit KiB figure; Home LCP time drops measurably in the next lab run.
- Leading indicator: `totalBytes` reported for `PS-laying_fam-*.png` (or its replacement) in the `image-delivery-insight` audit item list.

### [PERF-04] Site-wide oversized PNG logo repeats the same byte waste on every template
- Priority: Medium
- Category: Image optimization
- Evidence class: 2-PageSpeed lab
- Evidence: `Logo_Pepselect_Whitebackground-1.png` appears as a top-5 `image-delivery-insight` offender on all four audited templates with an identical footprint each time: totalBytes 52,773 B, wastedBytes 50,576 B (96% waste). A second, differently-sized instance (`-768x185.png`, 33,183 B / wastedBytes 30,633 B, 92% waste) also recurs on Home and Product/Shop lists.
- Affected URLs: `/`, `/product/bpc157-10/`, `/shop/`, `/testing/` (site-wide — this is almost certainly the header/age-gate logo asset loaded on every page template)
- Reasoning: A 96% waste ratio on a small logo indicates the served file is a raw, uncompressed or oversized PNG rather than an optimized format sized to its actual display box (the item snippet shows it rendering inside the age-gate card and header, both small elements). Because it loads on every page, this is a small-magnitude-per-page but site-wide-frequency finding — fixing it once (one asset) improves every template simultaneously with no per-template work.
- Recommendation: Re-export the logo as a compressed PNG-8/WebP or (given it's a flat-color brand mark) SVG, sized to its actual rendered dimensions, and reuse the single optimized asset across header and age-gate contexts.
- Dependencies: None. Unblocks a small, uniform LCP/byte-weight improvement across all four templates simultaneously (low effort, site-wide payoff — good candidate to sequence early).
- Failure check: Logo file continues to appear as a top-5 `image-delivery-insight` item with >50% wastedBytes after replacement.
- Success check: Logo no longer appears in `image-delivery-insight` top offenders on any template; total transferred bytes for the logo drop by roughly the 50 KB currently wasted, on every page load.
- Leading indicator: `totalBytes` for the logo URL in `image-delivery-insight`, checked on any single template (result generalizes to all four since it's the same asset).

### [PERF-05] High JavaScript request volume (36–48 scripts/template) built on jQuery/jQuery UI/Underscore is the primary INP-risk driver
- Priority: High
- Category: INP
- Evidence class: 2-PageSpeed lab / 5-Crawler observation
- Evidence: `resource-summary` script counts — Home 44 req (464,246 B), Product 48 req (492,314 B), Shop 41 req (461,565 B), Testing 36 req (419,870 B). `js-libraries` audit (Home) detects jQuery 3.7.1, jQuery UI 1.13.3, Underscore 1.13.8, core-js, WordPress 7.0.4 — i.e. multiple legacy DOM/utility libraries loaded concurrently rather than a single consolidated bundle. `dom-size-insight` "Total elements": Home 437, Product 459, Shop 694, Testing 595 — all comfortably under the 1,500-element risk threshold, so DOM size itself is not currently a contributing INP risk factor. Total Blocking Time (a lab proxy correlated with INP, not INP itself — INP cannot be measured in a single synthetic Lighthouse run) is 30–130 ms across the four templates, currently inside the "Good" INP band (≤200 ms) as a rough proxy.
- Affected URLs: `/`, `/product/bpc157-10/`, `/shop/`, `/testing/`
- Reasoning: TBT is currently in the good range, so INP is not yet a confirmed field failure — but 36–48 separate script requests per page, several of them render-blocking (PERF-02) and drawn from three different JS utility libraries (jQuery, jQuery UI, Underscore) plus per-plugin bundles, is a script-volume profile that degrades quickly on low-end mobile devices and is the standard leading cause of INP regressions once real-user interaction (add-to-cart, side-cart open, filter clicks) is layered on top of a synthetic lab run that does not simulate user input at all. This finding should be read as risk exposure, not a confirmed field failure — field INP is owned by the separate CrUX agent.
- Recommendation: Consolidate/defer non-critical script bundles (side-cart, points-and-rewards, confetti effect — see PERF-12) so fewer scripts execute on initial load, and audit whether jQuery UI is still required by active Elementor/plugin features.
- Dependencies: Overlaps with PERF-02 (render-blocking scripts) and PERF-12 (unused JS bytes) — sequence together since they touch the same enqueued-script list. Depends on a template-by-template feature audit (which plugin JS is actually used per page) before removal.
- Failure check: Field/CrUX INP (checked separately by the field-data agent) remains in "Needs Improvement" or "Poor" after script consolidation, or TBT rises above 200 ms in a follow-up lab run.
- Success check: Script request count per template drops meaningfully (e.g., below 30) without functional regression in cart/checkout/filter interactions, and TBT stays ≤130 ms or improves.
- Leading indicator: `resource-summary` Script `requestCount` and `transferSize`, re-checked via `pagespeed_check.py --psi-only` without a full audit.

### [PERF-06] Google Tag Manager is the dominant third-party cost on every template
- Priority: Medium
- Category: Third-party scripts / INP
- Evidence class: 2-PageSpeed lab
- Evidence: `third-parties-insight` audit, consistent across all four templates: Google Tag Manager — transferSize 186,574 B, mainThreadTime 130–191 ms (Home 147.7 ms, Product 130.1 ms, Shop 109.5 ms, Testing 190.8 ms — the highest of any run). Google Fonts — 140,750–151,684 B transfer, 0 ms main-thread (font bytes, not execution cost). `unused-javascript` audit flags the GTM script itself (`gtag/js?id=GT-NNQ4N6DP`) with wastedBytes 75,516–76,075 B (~41% of its own transferred size) on every template.
- Affected URLs: `/`, `/product/bpc157-10/`, `/shop/`, `/testing/`
- Reasoning: GTM alone accounts for 110–191 ms of main-thread time on every page — a meaningful fraction of the ≤200 ms INP "Good" budget before any first-party script or user interaction is counted, and it ships ~41% unused code on every load. This is a third-party dependency outside direct template control (tag configuration lives in GTM, not the codebase), so it is flagged separately from first-party script bloat (PERF-05).
- Recommendation: Review the GTM container for tags that can be trimmed, consolidated, or loaded conditionally (e.g., only on pages where the corresponding pixel/tag is actually needed), and confirm GTM is loaded with appropriate loading strategy (not blocking initial render) rather than removing GTM itself — this is a configuration/business decision requiring approval, not a code change.
- Dependencies: Requires GTM container access/approval (outside this audit's read-only scope) to action. No dependency on PERF-01–05.
- Failure check: `third-parties-insight` mainThreadTime for GTM remains >100 ms after container review.
- Success check: GTM mainThreadTime drops below ~50 ms and/or `unused-javascript` wastedBytes for the GTM script drops materially.
- Leading indicator: `third-parties-insight` GTM `mainThreadTime` value, checked per template.

### [PERF-07] `/testing/` loads a bloated 16-weight Google Fonts Roboto request not seen on other templates, plus a site-wide font-display gap
- Priority: Medium
- Category: Font loading / render-blocking
- Evidence class: 2-PageSpeed lab
- Evidence: `render-blocking-insight` on `/testing/` lists a Google Fonts request for family `Roboto:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&display=swap` with wastedMs 901 — the single largest render-blocking item found on any of the four templates. `font-display-insight` flags `Woo-Side-Cart.ttf` (side-cart plugin icon font) as missing font-display optimization on all four templates: wastedMs 50 (Home), 45 (Product), 110 (Shop), 80 (Testing). Font resource counts: 6–7 requests, 141,733–152,666 B per template.
- Affected URLs: `/testing/` (Roboto weight bloat, page-specific); `/`, `/product/bpc157-10/`, `/shop/`, `/testing/` (font-display gap, site-wide)
- Reasoning: Requesting all 16 Roboto weight/style combinations (regular + italic × 8 weights) when a typical page uses 2–4 weights forces the browser to download and parse a far larger font-face declaration set than needed, and `render-blocking-insight` confirms this specific request is currently the worst single blocker measured across the entire audit (901 ms). The `Woo-Side-Cart.ttf` font-display gap is smaller in magnitude (45–110 ms) but present on every template, indicating the plugin's icon font is not using `font-display: swap` or equivalent, risking brief invisible-text/icon flashes (FOIT).
- Recommendation: On `/testing/`, request only the specific Roboto weights actually used by that page's typography instead of the full 16-variant family string. Site-wide, add or verify `font-display: swap` (or `optional`) on the side-cart plugin's icon font declaration.
- Dependencies: None on other findings. The Roboto fix is isolated to whatever template/page-builder element renders `/testing/`; the font-display fix touches the side-cart plugin's CSS enqueue only, not plugin core files.
- Failure check: `render-blocking-insight` still lists the full 16-weight Roboto request on `/testing/` after remediation, or `font-display-insight` still flags `Woo-Side-Cart.ttf` on any template.
- Success check: Roboto request on `/testing/` is reduced to the actually-used weight subset and its `wastedMs` drops sharply; `Woo-Side-Cart.ttf` no longer appears in `font-display-insight`.
- Leading indicator: `font-display-insight` `total_items` count (currently 1 per template) and the Roboto request's `wastedMs` value on `/testing/`.

### [PERF-08] `/testing/` shows a large Speed Index/FCP gap (12.8 s vs 3.2 s) not present on the other three templates
- Priority: Medium
- Category: Rendering / visual progress
- Evidence class: 2-PageSpeed lab
- Evidence: Speed Index — Home 3.4 s, Product 3.3 s, Shop 3.7 s, Testing 12.8 s. FCP is nearly identical across all four templates (3.2–3.3 s), but Testing's Speed Index is 3.5–3.9x higher than the other three despite a similar FCP and a lower total request count (87 vs 113–116 on the others) and lower Total Blocking Time is not the cause (130 ms, the highest of the four but not extreme).
- Affected URLs: `/testing/`
- Reasoning: Speed Index measures how quickly visible content is painted in, not just when the first pixel appears — a large gap between FCP and Speed Index on one template only (while the other three are consistent) indicates something specific to this page's rendering sequence (e.g., large above-the-fold content painting in late, or a heavy layout/reflow step after first paint) rather than a site-wide condition. This is flagged as an anomaly requiring template-specific investigation rather than a diagnosed root cause, since the bundled audits captured here (render-blocking, image-delivery, DOM size) do not by themselves explain a 4x Speed Index multiplier.
- Recommendation: Investigate `/testing/`'s above-the-fold rendering sequence specifically (e.g., via a Lighthouse trace/filmstrip for this URL) to identify what is painting late; do not apply a generic fix without that diagnosis.
- Dependencies: Needs a dedicated trace-level look at `/testing/` (out of scope for this bundled-audit pass) before a specific recommendation can be made.
- Failure check: Speed Index on `/testing/` remains disproportionately high relative to its own FCP after the render-blocking (PERF-02) and font (PERF-07) fixes specific to this page are applied.
- Success check: Speed Index on `/testing/` falls into the same 3–4 s range as the other three templates.
- Leading indicator: `speed-index` `display` value for `/testing/` in a follow-up `pagespeed_check.py` run.

### [PERF-09] Unsized images create latent CLS risk despite CLS currently passing
- Priority: Low
- Category: CLS
- Evidence class: 2-PageSpeed lab
- Evidence: `unsized-images` audit (Home) flags two images without explicit `width`/`height`: the header/age-gate logo (`Logo_Pepselect_Whitebackground-1.png`, `loading="eager"`) and the side-cart's empty-cart illustration (`empty-cart.png`, rendered at 200×200 inside `.xoo-wsc-empty-cart`). Actual CLS scores measured this run are already good: Home 0.084, Shop 0.018, Product 0, Testing 0 — all well inside the ≤0.1 "Good" band.
- Affected URLs: `/` (confirmed via audit); logo is shared site-wide per PERF-04 so the same missing-dimensions condition likely applies wherever it renders without explicit attributes, though this was only directly observed on Home in this pass.
- Reasoning: CLS is not currently a measured problem, but `unsized-images` is a leading indicator, not a lagging one — these two images are exceptions to an otherwise-good pattern (`why-pep-select` images are correctly using `width`/`height`, per their audit snippets), and unsized images only become a visible CLS event when timing conditions change (e.g., slower network causing the image to arrive after adjacent text has already laid out, or the side-cart being opened before/after data loads). Flagging now, before it becomes a scored failure, is lower-cost than fixing it after a regression.
- Recommendation: Add explicit `width`/`height` (or `aspect-ratio` CSS) attributes to the logo `<img>` and the side-cart empty-cart illustration.
- Dependencies: None. Low effort, can be bundled with PERF-04's logo re-export.
- Failure check: `unsized-images` audit still flags either image after the fix.
- Success check: `unsized-images` audit returns 0 items on Home; CLS scores remain ≤0.1 across all four templates in subsequent runs.
- Leading indicator: `unsized-images` `total_items` count.

### [PERF-10] Unused CSS/JS bytes add measurable but secondary savings opportunity
- Priority: Low
- Category: JS/CSS payload
- Evidence class: 2-PageSpeed lab
- Evidence: `unused-javascript` flags the GTM script (75,516–76,075 B wasted per template, ~41% of its transfer size) and `confetti.js` from the side-cart plugin's confetti-effect library (34,036 B total, 31,819 B / 93% wasted) on all four templates. `opportunities` (type=`opportunity` audits) report `unused-css-rules` savings of 300 ms (Product) and 150 ms (Shop), and `unused-javascript` savings of 450 ms (Shop) and 300 ms (Testing). `unminified-javascript` flags `xoo-wsc-main.js` with 2,311 B of minification savings (Home).
- Affected URLs: `/`, `/product/bpc157-10/`, `/shop/`, `/testing/`
- Reasoning: `confetti.js` at 93% unused bytes strongly suggests the side-cart's celebratory confetti animation library is loaded on every page load regardless of whether the triggering event (e.g., successful add-to-cart) ever fires — a straightforward lazy-load candidate. This is smaller in magnitude than PERF-02/03/05 (hundreds of ms, not seconds) so it is sequenced as a secondary cleanup item.
- Recommendation: Lazy-load `confetti.js` so it only downloads when the add-to-cart success state actually triggers it, and run the standard unused-CSS-rule review (defer non-critical CSS) already recommended for the render-blocking chain (PERF-02) — these are the same underlying resource list.
- Dependencies: Overlaps with PERF-02 and PERF-05 (same plugin script/style list); sequence together.
- Failure check: `confetti.js` still ships with >80% unused bytes on initial page load after the fix.
- Success check: `confetti.js` no longer appears in `unused-javascript` on initial-load audits (i.e., it loads only on trigger).
- Leading indicator: `unused-javascript` `wastedBytes` value for `confetti.js`.

### [PERF-11] TTFB, edge caching, and compression are strong and are not contributing to the LCP/render-blocking problems above
- Priority: Low
- Category: Server response / caching / compression (positive finding — maintain, do not regress)
- Evidence class: 2-PageSpeed lab / 5-Crawler observation
- Evidence: Lighthouse `server-response-time` audit (throttled mobile lab): Home 3 ms, Product 2 ms, Shop 5 ms, Testing 4 ms — all far under the 200 ms TTFB "Good" ceiling referenced in the CWV brief. Direct unthrottled `curl` cross-check confirms this independently: Home TTFB 0.054 s / total 0.060 s, Product 0.091 s / 0.098 s, Shop 0.039 s / 0.046 s, Testing 0.051 s / 0.060 s, all HTTP 200. Response headers on all four HTML pages: `CF-Cache-Status: HIT`, `Server: cloudflare`, `Ki-CF-Cache-Status: HIT` (Kinsta edge cache), `Cache-Control: public, max-age=0, s-maxage=86400`, `Content-Encoding: br` (Brotli). Static asset headers (e.g., `woocommerce.css`) show `Cache-Control: max-age=315360000` (10-year cache) with `Content-Encoding: br`; the homepage hero PNG shows `Cache-Control: max-age=315360000`, `Expires: Thu, 31 Dec 2037` (image formats are not additionally compressed, which is expected/correct for already-compressed PNG/WebP).
- Affected URLs: `/`, `/product/bpc157-10/`, `/shop/`, `/testing/`, and static assets generally
- Reasoning: This contradicts a plausible assumption that a WooCommerce/Elementor/Kinsta stack would have slow TTFB — it does not. The Cloudflare + Kinsta two-layer edge cache is HIT on every page tested, HTML is Brotli-compressed, and static assets carry effectively-infinite cache lifetimes. This means none of PERF-01 through PERF-10 are caused by server response time, cache misses, or missing compression — the entire LCP/render-blocking problem is front-end payload and request-chain structure, not infrastructure. This significantly narrows and de-risks the remediation scope.
- Recommendation: No action required. Preserve current Cloudflare/Kinsta cache and Brotli configuration; re-verify headers after any CDN/hosting configuration change.
- Dependencies: None. Documents a baseline other findings depend on (confirms PERF-01's cause is front-end, not server-side).
- Failure check: A future check shows `CF-Cache-Status` or `Ki-CF-Cache-Status` as `MISS`/`BYPASS` on HTML documents, `Content-Encoding` missing on text assets, or TTFB exceeding 200 ms.
- Success check: Headers continue to show `HIT` + `Content-Encoding: br` + long `max-age` on static assets in future spot checks.
- Leading indicator: `Server-response-time` Lighthouse audit value, or a manual `curl -sD - -o /dev/null <url>` header check.

---

## Verified Correct

- All four URLs were confirmed live (HTTP 200) via direct `curl` immediately before and alongside the PSI runs; `/testing/` was independently confirmed reachable even though it is absent from `sitemap_index.xml`/`page-sitemap.xml`/`product-sitemap.xml`.
- PSI lab metrics (LCP/FCP/TBT/CLS/Speed Index, resource-summary counts) were cross-checked internally between the `lab_metrics`, `failed_audits`, and `audit_details` sections of each PSI JSON response for consistency (e.g., `render-blocking-insight` savings-ms values match between the summary and detail views).
- TTFB and caching/compression claims (PERF-11) were independently verified with unthrottled `curl -sD -` header inspection outside of Lighthouse, rather than relying solely on the lab tool's throttled `server-response-time` figure.
- The render-blocking request-count ratios (PERF-02) were computed directly from each page's own `resource-summary` Script+Stylesheet counts against its own `render-blocking-insight` `total_items`, per template, not inferred from a single page.
- Only GET requests were issued throughout (PSI API calls, sitemap XML fetches, HTML/header fetches). No forms were submitted, no state-changing requests were made, and no CrUX/field-data endpoints were queried per the read-only/lab-only scope of this task.

## Data Sources & Limitations

- All Core Web Vitals figures in this report are **lab data** (Evidence class 2): a single Lighthouse 13.x run per URL via the PSI API, mobile strategy only, `psi-only` (no CrUX). A single run is not the 75th-percentile field measurement Google actually scores — the sibling field-data agent covering CrUX is the authority for pass/fail against real users. Treat every LCP/CLS/TBT number here as directional, not as a confirmed Google verdict.
- Desktop strategy was not run in this pass; all findings and numbers above are mobile-only. If desktop performance materially differs (e.g., due to different image `srcset` breakpoints), that would require a separate desktop PSI pass to confirm.
- INP itself cannot be measured by Lighthouse (a lab tool with no real user input) in a single run; Total Blocking Time is used throughout as the standard lab proxy and is explicitly labeled as such (PERF-05). FID is not referenced anywhere in this report, per current methodology (INP is the sole interactivity metric).
- The bundled `pagespeed_check.py` tool caps each Lighthouse audit's `items` detail list at 5 entries (with a `total_items` count for the full set); consequently `network-requests` (116/113/116/87 total items) could only be inspected for its first 5 raw entries by request order, not resorted by size — the `resource-summary` and named insight audits (`image-delivery-insight`, `render-blocking-insight`, `third-parties-insight`) were used instead as they report accurate aggregate/top-offender data independent of this cap. No definitive "the LCP element is exactly file X" confirmation audit (e.g., a `largest-contentful-paint-element` audit) was available in this Lighthouse 13.x run; PERF-03's LCP-element attribution for the homepage hero image is inferential (based on its `fetchpriority="high"` attribute), not a direct Lighthouse LCP-element audit output.
- Render-page/HTML-source inspection (`render_page.py`) was not additionally run against these four URLs in this pass, since the PSI audit details already surfaced the specific blocking/oversized resources needed for these findings; it remains available for deeper DOM/element-level follow-up if a specific finding needs confirmation beyond what PSI's `audit_details` already provided.
- All figures reflect a single point-in-time snapshot (2026-08-18); WooCommerce/Elementor sites can vary run-to-run due to plugin updates, cache warm/cold state, or A/B content changes.

## Category Score: 42/100

LCP fails "Poor" (>4.0 s) on all four templates driven by a consistent, whole-site render-blocking chain (~60% of CSS+JS requests) and unoptimized images (up to 90–96% waste on individual assets) — this is the dominant, unresolved condition and caps the score. CLS is currently passing everywhere and TBT/INP-proxy risk is currently in the "Good" lab range, and server infrastructure (TTFB, Cloudflare+Kinsta caching, Brotli) is strong and not a contributing cause — which meaningfully narrows remediation scope but does not offset the LCP failure being universal across every audited template.

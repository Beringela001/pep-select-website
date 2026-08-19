# Claude SEO 2.2.4 — Deep Single-Page Analysis (`/seo page`)

- **URL analyzed:** https://pepselect.com/ (homepage)
- **Date:** 2026-08-18
- **Fetch status:** HTTP 200 (raw HTTP fetch, no JS render required — full content present in HTML)
- **HTML size:** ~124 KB
- **Detected business type:** E-commerce (WooCommerce storefront: `/product/`, add-to-cart endpoints, OnlineStore schema, side-cart plugin) — YMYL-adjacent category (research compounds)
- **Stack signals:** WordPress + WooCommerce 10.9.4, Yoast SEO 28.1, Kinsta hosting behind Cloudflare (`x-kinsta-cache: HIT`), Jetpack, YITH Points & Rewards, Woo Side Cart Premium, custom child theme `pepselect-child`

---

## Synthesis Walkthrough (10-Principle Framework)

**PERCEIVE** — observed the live HTML, headers, robots.txt, sitemap index, image weights (HEAD requests), redirect behavior, and structured data exactly as served; observed internal signals (WooCommerce/Yoast stack, custom child-theme hero section); listened to page intent: a trust-first storefront whose core promise is batch-matched COA documentation.

**ANALYZE** — first-principle: this homepage's job is (1) rank for brand + "research peptides with COA/lab reports" intent, (2) pass trust scrutiny in a YMYL-adjacent category, and (3) route link equity to `/shop/`, `/product/*`, and `/testing/`. Lateral connection: the COA/trust story is also the page's strongest AI-citability asset. System connection: performance findings (hero PNG, render-blocking head) and trust findings (broken legal link, contradictory return-policy schema) interact — both feed the same quality perception Google and users form.

**VALIDATE** — every finding below carries evidence measured in this session and an explicit falsifiability check ("how would we know this failed?"). Priorities follow the Claude SEO definitions: Critical = blocks indexing/penalty risk; High = significant ranking impact within 1 week; Medium = optimization within 1 month; Low = backlog. Nothing found blocks indexing, so the top findings are High.

**ACT** — the action plan at the end sequences fixes by dependency and gives leading indicators monitorable without re-running the audit.

---

## Page Score Card

```
Overall Score: 77/100

On-Page SEO:     82/100  ████████░░
Content Quality: 88/100  █████████░
Technical:       68/100  ███████░░░
Schema:          75/100  ████████░░
Images:          70/100  ███████░░░
```

Scoring notes:
- **On-Page (82):** strong title/desc/H1/linking; docked for the 404 footer legal link, duplicate conflicting anchor text, and slightly short meta description.
- **Content (88):** bundled content-quality tool scored **96/100** (filler 0, AI-pattern 0, information density 0.99, repetition 23/100); docked modestly for readability grade and thin external corroboration.
- **Technical (68):** clean indexability, but heavy render-blocking head (49 stylesheets, 27 blocking scripts), no HSTS, no resource preloads, three full-weight Google Font families.
- **Schema (75):** five valid Yoast graph nodes, but a contradictory MerchantReturnPolicy, a stale WebPage.name, and a missed ItemList opportunity.
- **Images (70):** perfect alt coverage and correct lazy strategy, but the LCP hero is an oversized PNG (up to 2.25 MB at largest srcset variant).

---

## What Is Already Correct

These require **no action** — do not "fix" them:

1. **Indexability:** `meta robots` = `index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1`. No noindex, no crawl traps on the homepage.
2. **Canonical:** `<link rel="canonical" href="https://pepselect.com/" />` — present, self-referencing, absolute.
3. **Title tag:** "Research Peptides with Batch-Matched Lab Reports | Pep Select" — 61 characters, leads with category keyword, unique differentiator ("Batch-Matched Lab Reports"), brand at the end. Essentially optimal (1 char over the 60 guideline; pixel width acceptable).
4. **Exactly one H1:** `<h1 id="pepselect-home-title"><span>The label is the easy part.</span><em>What's behind it matters.</em></h1>`. One H1, on-brand, matches the trust-first intent.
5. **Heading hierarchy:** H1 → H2 (4 content sections + 2 footer groups) → H3 (4 product names + 5 FAQ questions). No skipped levels.
6. **URL:** root URL, clean, no parameters.
7. **Redirect hygiene:** `http://pepselect.com` → 301 → `https://pepselect.com/`; `https://www.pepselect.com` → 301 → apex. Single canonical host.
8. **robots.txt:** sane WooCommerce disallows (`?add-to-cart=`, wc-logs, woocommerce_uploads), `Sitemap:` directive present, **no AI-crawler blocks** (GPTBot/ClaudeBot/PerplexityBot all allowed — good for GEO).
9. **XML sitemaps:** Yoast sitemap index live at `/sitemap_index.xml` with page, product, `ps_compound`, and `ps_coa_test` sitemaps; homepage included in `page-sitemap.xml`; lastmod dates fresh (2026-08-14 to 2026-08-18).
10. **Alt text: 10/10 images have descriptive alt attributes**, including exemplary ones ("Front and reverse views of a Pep Select Tesamorelin 10 mg clear vial with blue cap, silver crimp, and batch PSTES1071926GX.").
11. **Lazy-loading strategy is textbook:** hero = `loading="eager"` + `fetchpriority="high"`; all below-fold images = native `loading="lazy"` (lazy_method: native). No JS lazy-loader conflicts.
12. **Product thumbnails are excellent:** 300×300 WebP at 5.9–7.1 KB each.
13. **Image dimensions:** width/height set on 9 of 10 images (CLS protection).
14. **Open Graph core:** og:locale, og:type, og:title, og:description, og:url, og:site_name, og:image all present; twitter:card = summary_large_image.
15. **Schema present and structurally valid:** WebPage, ImageObject (primary image), BreadcrumbList, WebSite (with SearchAction/sitelinks-searchbox), OnlineStore with logo — proper Yoast @graph with correct @id linking.
16. **Content quality (tool-measured 96/100):** zero filler, zero AI-pattern writing, information density 0.99. Copy is specific and verifiable (real batch number PSTES1071926GX, named lab "Freedom Diagnostics", concrete cutoffs "10:00 AM ET", "90 minutes", "72 hours").
17. **FAQ section answers real transactional queries** in self-contained, extractable passages — strong AI-citability (each answer stands alone with entity + fact).
18. **Trust/YMYL groundwork:** RUO disclaimer, FDA disclaimer, 503A statement, COA archive linked 4× with varied anchors (COAs / See the Receipts / Open the Quality Archive / Certificate of Analysis).
19. **Internal linking:** 38 internal links to 18 unique targets; money pages get multiple, varied, descriptive anchors (`/shop/`: "Compounds", "Explore Our Selection", "Browse the Full Selection"). No orphan-page symptoms from this page.
20. **Edge caching:** `x-kinsta-cache: HIT` behind Cloudflare — TTFB layer is handled.
21. **`X-Content-Type-Options: nosniff`** present.
22. **Word count:** 834 (main content parse) / ~1,029 visible words including nav/footer — comfortably above homepage thin-content thresholds for e-commerce.

---

## Findings (by priority, original assignments preserved)

### HIGH-1 — LCP hero image is an oversized PNG (368 KB mobile → up to 2.25 MB desktop)

- **Evidence (measured via HEAD requests, 2026-08-18):**
  - `PS-laying_fam-768x434.png` — **367,813 B** (default/mobile src)
  - `PS-laying_fam-1024x578.png` — **623,780 B**
  - `PS-laying_fam-1536x868.png` — **1,300,115 B**
  - `PS-laying_fam-2048x1157.png` — **2,253,716 B**
  - `sizes="(max-width: 767px) 92vw, 50vw"` — a 1920px-wide desktop viewport selects the 1024w file (~624 KB); high-DPR/wide screens select 1536w–2048w (1.3–2.25 MB).
- **Affected element:** `<figure class="pepselect-home__hero-figure"><img fetchpriority="high" ... src=".../2026/07/PS-laying_fam-768x434.png" ...>` — this is almost certainly the LCP element.
- **First principle (THINK):** LCP is dominated by resource load time of the largest above-fold element. The markup already does everything right (eager, fetchpriority=high, srcset/sizes); the only remaining lever is bytes. A product-lineup photo as PNG carries 5–10× the bytes of the same image as WebP/AVIF at visually identical quality. Every other product image on this site is already WebP — the hero is the outlier.
- **Dependency (CONNECT):** independent of all other fixes; do first for the largest single CWV win. Compounds with HIGH-3 (render-blocking head) — both must land to move LCP meaningfully.
- **Recommendation:** Re-export `PS-laying_fam` as WebP (or AVIF with WebP fallback) at quality ~80; regenerate all srcset sizes. Target ≤120 KB at 768w and ≤250 KB at 1536w. Optionally add `<link rel="preload" as="image" imagesrcset="..." imagesizes="...">` in the head. (Minor: the img tag has a duplicated `fetchpriority="high"` attribute — harmless, tidy when editing.)
- **Failure check (ACCEPT):** if after conversion PSI mobile LCP does not improve ≥0.3 s (or the LCP element turns out to be the H1 text, not the image), this finding overstated the impact — verify the LCP element in PSI's diagnostics.
- **Leading indicator (GROW):** re-run the HEAD request on the new hero URL — the finding is closed when the served mobile hero is < 150 KB; watch CrUX LCP p75 in Search Console CWV report over the following 28 days.

### HIGH-2 — Footer legal link "Terms and Conditions" returns 404, and duplicates another footer anchor

- **Evidence (measured):** footer contains **two** "terms" links: `Terms and conditions` → `https://pepselect.com/terms-conditions/` (**HTTP 200**) and `Terms and Conditions` → `https://pepselect.com/terms-of-service/` (**HTTP 404**).
- **Affected URLs:** homepage footer (site-wide footer, so likely every page); broken target `/terms-of-service/`.
- **First principle (THINK):** a site-wide 404 in the legal-links block wastes crawl budget on every page, leaks internal PageRank to a dead end, and — in a YMYL-adjacent category where the entire homepage narrative is "trust us, check the receipts" — a broken Terms link is exactly the kind of contradiction quality raters and cautious customers notice. Two identical anchors pointing at different URLs also sends ambiguous relevance signals.
- **Dependency (CONNECT):** unblocks nothing else, but should ship before any crawl-focused work; trivially cheap.
- **Recommendation:** remove the `/terms-of-service/` footer link (or repoint it to `/terms-conditions/`), leaving one canonical Terms link. If `/terms-of-service/` ever had traffic/links, 301 it to `/terms-conditions/`.
- **Failure check (ACCEPT):** `curl -I https://pepselect.com/terms-of-service/` still returning 404 while the footer still links to it = not fixed. If Search Console never showed the 404 being crawled, impact was lower than estimated (but the fix costs minutes regardless).
- **Leading indicator (GROW):** GSC → Pages → "Not found (404)" — `/terms-of-service/` should disappear from the report after the next crawl cycle.

### HIGH-3 — Heavy render-blocking head: 49 stylesheets, 27 blocking scripts, 3 full-weight font families, zero preloads

- **Evidence (measured from served HTML):**
  - 49 `<link rel="stylesheet">` tags; 43 external scripts of which only 1 async + 15 defer → **27 parser-blocking**; 28 inline scripts.
  - Google Fonts requested as **Roboto, Roboto Slab, and Plus Jakarta Sans each with all 18 weight/style variants** (100–900 + italics).
  - **No `<link rel="preload">` at all** (no font preload, no LCP image preload).
- **Affected element:** document `<head>` (plugin CSS/JS: WooCommerce, side-cart, YITH, Jetpack forms, mediaelement, etc.).
- **First principle (THINK):** first render cannot start until blocking CSS/JS resolves; every stylesheet is a potential critical-path round trip. Fonts declared with 18 variants force the browser to download weights the design never uses. This suppresses LCP and FCP even with edge caching, because the cost is client-side parse/fetch, not server latency.
- **Dependency (CONNECT):** interacts with HIGH-1 — hero bytes and head blocking are the two levers on LCP; fixing only one caps the gain. This is the largest-effort High item (plugin/theme surgery on WordPress).
- **Recommendation:** (a) trim Google Fonts to the weights actually used (typically 400/500/700 of one or two families — likely removes Roboto or Roboto Slab entirely); (b) add `preconnect` to fonts.gstatic.com and preload the primary WOFF2; (c) use a performance plugin (e.g., Perfmatters, already familiar to the tooling) or conditional dequeue to remove unused plugin CSS/JS on the homepage (mediaelement, Jetpack forms layout, select2 are unlikely to be needed above the fold); (d) defer remaining non-critical JS.
- **Failure check (ACCEPT):** if PSI's "Eliminate render-blocking resources" audit shows < 300 ms estimated savings, this finding is lower-impact than assessed and can drop to Medium. If dequeuing breaks side-cart/rewards UI, roll back per-handle — dequeue one handle at a time.
- **Leading indicator (GROW):** count of `<link rel='stylesheet'>` in view-source (target: < 25) and the PSI render-blocking audit; CrUX FCP/LCP p75 trend in GSC.

### MEDIUM-1 — MerchantReturnPolicy schema contradicts the page's own refund promise

- **Evidence:** OnlineStore schema declares `"hasMerchantReturnPolicy": { "returnPolicyCategory": "https://schema.org/MerchantReturnNotPermitted", "merchantReturnLink": ".../refund-shipping-policy/" }` — while the on-page FAQ states: *"If an order arrives damaged or incorrect... we resolve it with a replacement, refund, or store credit."*
- **Affected element:** Yoast schema graph node `#organization` → `#merchant-return-policy`; affects any Merchant Center / product rich-result surfaces that inherit the org-level policy.
- **First principle (THINK):** structured data is a machine-readable claim; when it contradicts the visible page, the safest engine response is to distrust both. "Return not permitted" alongside a visible refund promise is a self-inflicted trust inconsistency in a category where trust is the differentiator.
- **Dependency (CONNECT):** decide the true policy first (business decision), then fix in Yoast/WooCommerce settings; product pages inherit it.
- **Recommendation:** if the real policy is "no returns, but damaged/incorrect orders are replaced/refunded within 72 h," the closer encoding is `MerchantReturnFiniteReturnWindow` with `merchantReturnDays: 3` and `returnMethod`/`itemCondition` constraints, or keep `MerchantReturnNotPermitted` **only if** the refund policy page genuinely offers no remedy (it visibly does). Align schema with the written policy.
- **Failure check (ACCEPT):** Google Rich Results Test / Schema.org validator still showing `MerchantReturnNotPermitted` after the change = not deployed. If Merchant Center is not in use and no product rich results ever show return info, real-world impact is limited to consistency hygiene.
- **Leading indicator (GROW):** Rich Results Test on `https://pepselect.com/` shows the updated returnPolicyCategory.

### MEDIUM-2 — Schema WebPage.name is stale and mismatches the title tag

- **Evidence:** JSON-LD `WebPage.name` = `"Research Compounds & Batch Documentation | Pep Select"`; `<title>` = `"Research Peptides with Batch-Matched Lab Reports | Pep Select"`.
- **Affected element:** Yoast schema graph WebPage node (likely a leftover from a previous title iteration).
- **First principle (THINK):** conflicting machine-readable names for the same entity dilute signal consistency. Minor alone, but free to fix and compounding with MEDIUM-1's "schema disagrees with page" pattern.
- **Dependency (CONNECT):** none; a Yoast re-save of the homepage SEO title normally regenerates it.
- **Recommendation:** re-save the homepage in Yoast so WebPage.name matches the current title.
- **Failure check (ACCEPT):** view-source after cache purge still shows the old name = caching layer serving stale HTML (purge Kinsta/Cloudflare).
- **Leading indicator (GROW):** one view-source check post-purge.

### MEDIUM-3 — Featured products carry no ItemList markup (missed structured-data opportunity)

- **Evidence:** four products (GLP-3 R $79.99, GHK-CU $33.99, NAD+ $51.99, TB-500 $49.99) rendered with names, prices, images, and links — zero product-related structured data on the homepage.
- **Affected element:** "The Current Selection" section.
- **First principle (THINK):** the homepage should not carry full Product schema (offers belong on product pages to avoid duplicate-offer conflicts), but an `ItemList` of product URLs tells engines these are the flagship entities and strengthens the site's entity graph at zero risk.
- **Dependency (CONNECT):** verify product pages themselves have valid Product/Offer schema first (out of scope for this page analysis — flagged for a `/seo schema` pass on `/product/glp3-r10/`).
- **Recommendation — ready-to-use JSON-LD:**

```json
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "name": "The Current Selection",
  "itemListOrder": "https://schema.org/ItemListOrderAscending",
  "numberOfItems": 4,
  "itemListElement": [
    { "@type": "ListItem", "position": 1, "url": "https://pepselect.com/product/glp3-r10/" },
    { "@type": "ListItem", "position": 2, "url": "https://pepselect.com/product/ghk-cu/" },
    { "@type": "ListItem", "position": 3, "url": "https://pepselect.com/product/nad/" },
    { "@type": "ListItem", "position": 4, "url": "https://pepselect.com/product/tb500-10/" }
  ]
}
</script>
```

- **Failure check (ACCEPT):** ItemList produces no rich result of its own — if the expectation was a visible SERP feature, that expectation is wrong; the benefit is entity/crawl signaling only.
- **Leading indicator (GROW):** Rich Results Test recognizes the ItemList without errors.

### MEDIUM-4 — No HSTS header

- **Evidence:** response headers for `https://pepselect.com/` contain no `Strict-Transport-Security` header (0 matches).
- **Affected URLs:** entire origin.
- **First principle (THINK):** without HSTS, first visits over `http://` depend on the 301 hop (one downgrade-attack window, one extra redirect). Not a ranking factor per se, but a security-hygiene signal and a small latency saving for returning visitors.
- **Dependency (CONNECT):** enable at Cloudflare (SSL/TLS → Edge Certificates → HSTS) — no code change; confirm all subdomains serve HTTPS before adding `includeSubDomains`.
- **Recommendation:** `Strict-Transport-Security: max-age=15552000` initially; extend and consider preload once stable.
- **Failure check (ACCEPT):** `curl -sI https://pepselect.com | grep -i strict` empty = not deployed. If any active subdomain is HTTP-only, `includeSubDomains` would break it — that is the risk to check, not a reason to skip base HSTS.
- **Leading indicator (GROW):** header visible in any response; securityheaders.com grade improves.

### MEDIUM-5 — Meta description under-uses its space (135/160 chars)

- **Evidence:** "Browse research compounds with clear product information, batch-specific documentation, and accessible testing history from Pep Select." — 135 characters; accurate but lists features without the strongest differentiators (independent lab, batch-vial matching, same-day shipping).
- **Affected element:** `<meta name="description">` (Yoast homepage field).
- **First principle (THINK):** the description is free SERP ad copy; CTR is the lever, not rankings. The page's most distinctive, click-worthy claims (independent third-party lab reports matched to your exact vial; free 2-day shipping ≥ $200) are absent.
- **Dependency (CONNECT):** none.
- **Recommendation (example, 156 chars):** "Research peptides with independent, batch-matched lab reports you can verify against your vial. Short catalog, full COA archive, free 2-day shipping over $200."
- **Failure check (ACCEPT):** if GSC homepage CTR for brandless queries does not move over 4–8 weeks, the old description was not the constraint — revert or iterate. Google may rewrite descriptions regardless.
- **Leading indicator (GROW):** GSC → Performance → homepage URL filter → CTR on non-brand queries.

### MEDIUM-6 — Social card metadata incomplete (twitter:description, twitter:image, og:image dimensions/alt)

- **Evidence:** present: `twitter:card`, `twitter:title`. Missing: `twitter:description`, `twitter:image` (falls back to og:image in most scrapers, but declared-card-with-partial-set is fragile), `og:image:width`, `og:image:height`, `og:image:alt`. Also `article:modified_time` is emitted on an `og:type=website` page (harmless Yoast quirk, informational only).
- **Affected element:** head meta block (Yoast social settings).
- **First principle (THINK):** explicit dimensions let link-preview scrapers render the card on first share without fetching the image; missing description yields inconsistent previews on X/Slack/Discord — this is share-conversion hygiene, not rankings.
- **Dependency (CONNECT):** none; Yoast Social tab fields.
- **Recommendation:** fill twitter:description; add og:image:width=2062, og:image:height=2560, og:image:alt. Consider a purpose-built 1200×630 og:image rather than the portrait product photo (current RT30F is 2062×2560 portrait — croppable unpredictably in landscape cards).
- **Failure check (ACCEPT):** X Card Validator / opengraph.xyz preview still renders incomplete = not fixed.
- **Leading indicator (GROW):** one card-validator check.

### MEDIUM-7 — Two below-fold images exceed the 200 KB warning threshold; footer logo oversized

- **Evidence (measured):**
  - `tesamorelin-10mg-coa-source.webp` — **308,112 B** (3400×4400 source displayed far smaller) — exceeds 200 KB warning threshold (under the 500 KB critical line).
  - `9nrNfe_B.png` (footer logo) — **166,909 B** at 2048×503 for a footer-sized slot; PNG.
  - Second header/footer logo instance (`Logo_Pepselect_Whitebackground-1.png`, 52,773 B) is emitted once **without width/height** (the only image missing dimensions).
- **Affected elements:** "Match the batch" section COA image; footer logos.
- **First principle (THINK):** both are lazy-loaded, so LCP is unaffected — this is bandwidth/CLS hygiene, hence Medium not High. Serving a 4400px-tall COA scan into a card slot wastes ~250 KB per visitor scroll.
- **Recommendation:** generate an ~800px-wide WebP rendition of the COA image for this section (keep the full-res scan behind the Quality Archive link); re-export footer logo as WebP ≤ 30 KB; add width/height to the dimension-less logo instance.
- **Failure check (ACCEPT):** HEAD content-length unchanged = not deployed.
- **Leading indicator (GROW):** page weight in any waterfall tool; the two HEAD requests above.

### MEDIUM-8 — OnlineStore schema is thin: no contactPoint, no sameAs

- **Evidence:** `OnlineStore` node has name/url/logo/returnPolicy only. The page itself exposes `support@pepselect.com`; no social profiles are linked anywhere on the page (so sameAs may be genuinely empty — verify).
- **First principle (THINK):** org-level schema is the anchor of the site's knowledge-graph identity; contactPoint corroborates legitimacy (matters more in trust-sensitive verticals). sameAs only if real profiles exist — never fabricate.
- **Recommendation:** add via Yoast/filter: `"contactPoint": {"@type":"ContactPoint","email":"support@pepselect.com","contactType":"customer support"}`; add `sameAs` only for genuinely owned profiles.
- **Failure check (ACCEPT):** validator shows the node unchanged = not deployed; if no social profiles exist, absence of sameAs is correct, not a failure.
- **Leading indicator (GROW):** Rich Results Test on homepage.

### LOW-1 — Title tag 61 characters (guideline 50–60)

- **Evidence:** 61 chars. Pixel width likely within limits; truncation risk minimal. **Backlog only** — do not churn a working title for one character.
- **Failure check:** SERP preview tools showing "…" truncation would justify a trim; otherwise leave it.

### LOW-2 — Footer navigation groups use H2 headings ("Support", "Legal", "Research Access Verification")

- **Evidence:** 3 of the 6 H2s are footer/utility groupings, diluting the semantic outline of the document.
- **First principle:** heading structure is a relevance outline; nav labels in it add noise, not harm. Cosmetic.
- **Recommendation:** if ever refactoring the footer, demote to styled `<p>`/`<div>` or aria-labels. Not worth a dedicated deploy.

### LOW-3 — No llms.txt

- **Evidence:** `https://pepselect.com/llms.txt` → 404.
- **Context:** llms.txt is optional and **ignored by Google Search**; some AI crawlers read it. Robots.txt already allows AI crawlers — the substantive GEO groundwork (extractable FAQ passages, specific verifiable facts) is already on the page.
- **Recommendation:** backlog; if added, list shop, testing archive, FAQ, and policy URLs with one-line descriptions.
- **Failure check:** absence of measurable AI-referral change after adding is the expected outcome — treat as a lottery ticket, not a lever.

### LOW-4 — No authoritative external links (sole external href is mailto:)

- **Evidence:** 1 external link total: `mailto:support@pepselect.com`.
- **Context:** normal for an e-commerce homepage; the "independent laboratory" (Freedom Diagnostics) claim, however, is the one place an outbound corroborating link would strengthen E-E-A-T. Consider linking the lab's site from the testing/COA pages (not necessarily the homepage).
- **Failure check:** if added on the homepage and it leaks conversion-path clicks, the placement (not the idea) was wrong — keep it in the Quality Archive instead.

### INFO — FAQ section present without FAQPage schema: **correct as-is, do not "fix"**

- Google retired FAQ rich results for all sites on May 7, 2026 — adding FAQPage markup for SERP benefit is explicitly **not recommended** (Claude SEO quality gate). The unmarked FAQ content is already in its highest-value form for AI extraction. No action.

### INFO — Duplicate `fetchpriority="high"` attribute on the hero `<img>`

- Harmless HTML validation nit; browsers use the first occurrence. Tidy opportunistically during HIGH-1.

---

## Content Quality Detail

| Metric | Value | Assessment |
|---|---|---|
| Tool overall quality | **96/100** | Excellent |
| Filler score | 0/100 (higher = worse) | Excellent |
| AI-pattern score | 0/100 (higher = worse) | Excellent — reads human-written |
| Information density | 0.99 | Excellent |
| Repetition | 23/100 (higher = worse) | Good |
| Main-content word count | 834 (parser) / ~1,029 visible incl. nav/footer | Above homepage minimums |
| Flesch Reading Ease | 47.3 | "Difficult" band — acceptable for a research-buyer audience |
| Flesch-Kincaid grade | ~10.1 | Appropriate for audience |
| Keyword usage (visible text) | research ×26, compound ×14, batch ×9, pep select ×9, RUO ×3, peptide ×2, COA ×2 | Natural; note "peptide" appears only 2× in body despite being the title's head term — the body leans on "compound." Semantically fine, but one or two natural body uses of "research peptides" would tighten title↔body relevance. |
| E-E-A-T markers | Named independent lab, real batch ID, concrete SLAs (10 AM ET cutoff, 72-hour claims window, 90-minute payment expiry), RUO/FDA/503A disclaimers | Strong for category |
| Freshness | `article:modified_time` 2026-08-14; sitemap lastmod 2026-08-14–18 | Fresh |

---

## Core Web Vitals (HTML-inferred risk flags — lab/field measurement unavailable this session)

- **LCP risk: HIGH** — 368 KB–2.25 MB PNG hero (HIGH-1) + 49 stylesheets/27 blocking scripts (HIGH-3). Mitigants already in place: eager + fetchpriority=high + srcset/sizes + edge cache HIT.
- **INP risk: MODERATE** — 43 external + 28 inline scripts (Woo side-cart, rewards, Jetpack, GTM); only 16 async/defer. Main-thread pressure plausible on mobile.
- **CLS risk: LOW** — dimensions on 9/10 images, `contain-intrinsic-size` fallback CSS present, fonts use `display=swap` (swap can cause minor text-shift; acceptable trade).

---

## Prioritized Action Plan (dependency-sequenced)

| # | Action | Priority | Effort | Depends on | Expected impact |
|---|---|---|---|---|---|
| 1 | Remove/repoint footer `/terms-of-service/` 404 link | High | 5 min | — | Trust + crawl hygiene, site-wide |
| 2 | Convert hero `PS-laying_fam` to WebP/AVIF, regenerate srcset (≤150 KB mobile) | High | 1–2 h | — | Largest single LCP win |
| 3 | Trim Google Fonts to used weights; preconnect/preload primary font | High | 1 h | — | FCP/LCP, render path |
| 4 | Dequeue unused plugin CSS/JS on homepage; defer remaining JS (target <25 stylesheets, <10 blocking scripts) | High | 0.5–1 day | Test after #3 | FCP/LCP/INP |
| 5 | Align MerchantReturnPolicy schema with actual refund policy | Medium | 30 min | Business decision on policy wording | Trust consistency, Merchant surfaces |
| 6 | Re-save homepage in Yoast → fix stale WebPage.name; fill twitter:description + og:image dims | Medium | 15 min | — | Signal consistency, share cards |
| 7 | Rewrite meta description to ~155 chars with differentiators | Medium | 15 min | — | SERP CTR |
| 8 | Add ItemList JSON-LD for the four featured products | Medium | 30 min | Verify product-page Product schema first | Entity signaling |
| 9 | Enable HSTS at Cloudflare | Medium | 15 min | Subdomain HTTPS check | Security hygiene |
| 10 | Downsize COA section image + footer logo; add missing width/height | Medium | 30 min | — | Page weight, CLS edge |
| 11 | Add contactPoint (and real sameAs if any) to OnlineStore schema | Medium | 30 min | — | Knowledge-graph identity |
| 12 | Backlog: llms.txt, footer H2 demotion, lab outbound link on testing page, title char trim | Low | — | — | Marginal |

**Re-audit trigger:** after items 1–4 ship, run `/seo page https://pepselect.com` again plus a PSI check; after item 5–8, run `/seo schema https://pepselect.com`.

---

## Measurement Limitations (disclosed)

1. **PageSpeed Insights API was rate-limited** during this session (shared anonymous quota: "240 QPM / 25,000 QPD exceeded" on both mobile and desktop) — **no lab LCP/INP/CLS numbers**; CWV statements above are HTML-inferred risk flags only, per the seo-page methodology ("reference only, not measurable from HTML alone"). Re-run later or configure a Google API key for `/seo google pagespeed`.
2. **No CrUX field data** fetched (requires API key) — real-user CWV unknown.
3. **No Search Console data** (no Google API credentials detected) — CTR, query, and indexation claims are directional; leading indicators reference GSC assuming the property is verified.
4. **Analysis is of the raw served HTML** (no JS render was needed — content is server-rendered — but any client-side-injected elements, e.g., GTM-inserted tags or side-cart DOM, are not reflected).
5. **Readability (Flesch 47.3 / grade 10.1) computed on visible text including nav/footer boilerplate** — main-content-only readability is likely slightly easier than measured.
6. **SERP position / backlink data not included** (DataForSEO MCP not available in this session).
7. **Product pages, /shop/, /testing/ were not analyzed** — findings about their schema (MEDIUM-3 dependency) are flagged assumptions, not measurements.
8. Word counts differ by method (834 parser vs ~1,029 visible-text) because the parser excludes some nav/footer text; both are reported.

---

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Built by agricidaniel — Join the AI Marketing Hub community
🆓 Free  → https://www.skool.com/ai-marketing-hub
⚡ Pro   → https://www.skool.com/ai-marketing-hub-pro
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

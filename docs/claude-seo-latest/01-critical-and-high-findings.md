# 01 — Critical and High Findings

Part of the Pep Select full site SEO audit. Index: [`../CLAUDE-SEO-LATEST.md`](../CLAUDE-SEO-LATEST.md)
Audit date: 2026-08-18 · claude-seo v2.2.4 · 43/43 pages

> **Core Web Vitals caveat, repeated here because it applies to P-01, P-02 and P-03:** no `GOOGLE_API_KEY` was configured, so PageSpeed Insights, CrUX and CrUX History were unavailable. **All Core Web Vitals statements below are inferred from HTML structure and measured asset weights. There is no field data and no Lighthouse run.**

> **Count note (preserved, not corrected):** the audit's summary line records "1 Critical · 8 High". Nine findings below carry High priority (T-02, C-01, C-02, O-01, P-01, P-02, P-03, I-01, G-05). The individual finding priorities are reproduced exactly as issued; the discrepancy is in the original summary tally, and no priority has been changed here.

**Findings in this file:** T-01 (Critical) · T-02 · C-01 · C-02 · O-01 · P-01 · P-02 · P-03 · I-01 · G-05

---

# 🔴 Critical

## T-01 — `/terms-of-service/` returns 404 and is linked from all 43 pages

**Priority: 🔴 Critical** · Category: Technical SEO

### Evidence

- `curl -o /dev/null -w '%{http_code}' https://pepselect.com/terms-of-service/` → **404**
- Present in **43 of 43** crawled pages
- Source markup (research gate legal block, identical sitewide):

  ```html
  By proceeding, you confirm that you are 21 years of age or older and a qualified
  research professional. ... By creating an account and/or placing an order, you agree
  to our <a href="/terms-of-service/">Terms and Conditions</a>, including all use
  restrictions, and agree to indemnify and hold harmless the seller ...
  ```

- The correct page exists and returns 200 at `/terms-conditions/`
- The footer links correctly to `/terms-conditions/`; only the gate is wrong

### Affected URLs

All 43 crawled URLs. The broken link is emitted by the shared research-gate template, so it appears on the homepage, all 15 product pages, `/shop/`, all 18 `/testing/` URLs, and all 9 static/policy pages.

### Reasoning — why this is Critical, not Medium

This is not primarily an SEO issue. Users are being asked to affirm agreement to terms they cannot reach, on a legally-gated purchase flow for regulated materials, on every page of the site. The indemnification clause in that same sentence depends on the linked document. The SEO cost (43 broken internal links, wasted crawl requests) is the smaller half of the problem.

### Dependencies

None. Fix immediately and independently.

### Recommended action

Change `href="/terms-of-service/"` to `href="/terms-conditions/"` in the research-gate template. Alternatively add a 301 from `/terms-of-service/` → `/terms-conditions/` as a safety net, since the wrong URL may already exist in external references or prior form versions.

### Success check

`curl -sI https://pepselect.com/terms-of-service/` returns 301 (or the link no longer appears in page source); `grep -c 'terms-of-service'` across a fresh crawl returns 0. Search Console → Pages → "Not found (404)" shows no entries for this path within 2 weeks.

### Leading indicator

Search Console Crawl Stats — 404 response count should drop to zero.

---

# 🟠 High

## T-02 — Orphan page: `/product/bacteriostatic-water-30ml/`

**Priority: 🟠 High** · Category: Technical SEO

### Evidence

- Present in `product-sitemap.xml`, returns 200, indexable, self-canonical
- **Zero inbound internal links** across the entire 43-page crawl
- `grep -c 'bacteriostatic-water-30ml' pages/shop.html` → **0**. It is not listed on `/shop/`
- Only page in the crawl that mentions it is the product page itself
- Schema shows it as `InStock` at $19.99 with SKU `BACW30` — it is a live, purchasable product

### Affected URLs

`https://pepselect.com/product/bacteriostatic-water-30ml/`

### Reasoning

The sitemap says "index this"; the internal link graph says "this page does not exist." Google resolves that contradiction by treating the page as low-value. It is also a natural cross-sell for every peptide on the site — bacteriostatic water is the reconstitution consumable — so the commercial cost of the orphaning is larger than the SEO cost.

### Dependencies

None.

### Recommended action

Determine whether the product is deliberately hidden from the catalog (WooCommerce → Catalog visibility = "Hidden"). If deliberate, remove it from the sitemap and `noindex` it. If not deliberate — which the InStock status and pricing suggest — restore it to `/shop/` and add it as a related/cross-sell item on all 15 peptide product pages.

### Success check

Inbound internal link count ≥ 1 on a re-crawl; page appears in `/shop/` listing; Search Console URL Inspection reports "Discovered — referring page" rather than sitemap-only discovery.

---

## C-01 — 560 words of identical boilerplate on all 43 pages

**Priority: 🟠 High** · Category: Content Quality

### Evidence

Line-frequency analysis across the full crawl identified **28 text blocks present on ≥90% of pages**, totalling **560 words per page**:

- Research-gate attestation block (~430 words): researcher-type list, age affirmation, six-point attestation, indemnification paragraph
- FDA Disclaimer — appearing **twice per page** in near-identical form, once in the gate ("U.S. Food and Drug Administration") and once in the footer ("US Food and Drug Administration")
- RUO statement, footer navigation, support block

Boilerplate as a share of total page text:

| Page | Total words | Boilerplate | Unique | Unique % |
|---|---|---|---|---|
| `/track-your-order/` | 617 | 560 | **57** | 9% |
| `/military-discount/` | 628 | 560 | **68** | 10% |
| `/contact/` | 637 | 560 | **77** | 12% |
| `/product/bacteriostatic-water-30ml/` | 756 | 560 | 196 | 25% |
| `/testing/retatrutide-20mg/` | 756 | 560 | 196 | 25% |
| `/` (homepage) | 881 | 560 | 321 | 36% |
| `/terms-conditions/` | 1552 | 560 | 992 | 63% |

### Affected URLs

All 43 crawled URLs.

### Reasoning — with an important qualification

The `unique %` column is depressed by a template artifact and should **not** be read as a duplicate-content penalty signal; Google routinely discounts boilerplate. The meaningful figure is the **absolute unique word count**, and three pages fall below 80 unique words. `/track-your-order/` at 57 unique words is functionally an empty page with a form on it.

The second, sharper issue is that the FDA disclaimer is duplicated **within** each page with inconsistent wording ("US" vs "U.S."). That is a content-governance defect, not just redundancy.

### Dependencies

Resolving this partly depends on the research-gate implementation (see **G-02** and **T-01** — all three touch the same template).

### Recommended action

1. Collapse the duplicated FDA disclaimer to a single instance per page and standardize on "U.S. Food and Drug Administration."
2. Move the full six-point attestation behind the existing "Read the researcher attestation" disclosure so it is not in the default DOM text of every page; keep the short affirmation visible.
3. Expand the three thinnest pages — see **C-03**.

### Success check

Re-run the boilerplate analysis; per-page boilerplate drops below 300 words. No page under 150 unique words.

---

## C-02 — All 15 product pages fall below the 400-word quality gate

**Priority: 🟠 High** · Category: Content Quality

### Evidence

`references/quality-gates.md` sets **Product Page: 400 words minimum, 80%+ unique**. Measured unique word counts:

| Product | Unique words | Product | Unique words |
|---|---|---|---|
| `bacteriostatic-water-30ml` | 196 | `glp3-r30` | 274 |
| `pt-141` | 206 | `glutathione` | 283 |
| `motsc-10` | 211 | `ghk-cu` | 284 |
| `tb500-10` | 240 | `tesa-10` | 303 |
| `glp3-r20` | 242 | `glp2-t20` | 315 |
| `ss-31` | 242 | `glp1-s10` | 324 |
| `glp3-r10` | 249 | | |
| `bpc157-10` | 263 | **Range** | **196–324** |
| `nad` | 269 | **Gate** | **400** |

**Every product page fails the gate.** Also below gate:

- `/shop/` category page: 290 unique words (gate: 400)
- `/` homepage: 321 unique words (gate: 500)
- `/faq/`: 719 unique words (gate: 800) — marginal

### Affected URLs

All 15 `/product/*` URLs, plus `/shop/`, `/` and `/faq/`.

### Reasoning

Product descriptions are single-paragraph mechanism summaries. Sample (`glp3-r10`): *"Retatrutide is a peptide studied as a triple receptor agonist, engineered to engage the GLP-1, GIP, and glucagon receptors. It is researched for the structural basis of its simultaneous activity across all three receptors."* That is accurate and appropriately RUO-compliant, but it is roughly 40 words carrying the entire commercial page.

The material to fix this **already exists on the site** — the COA archive holds purity, identity, heavy-metals, sterility and fentanyl-screen data per batch, plus measurement techniques. Surfacing a batch-data summary and handling/storage guidance on the product page would clear the gate with content that is both unique and genuinely useful, without making any prohibited claims.

### Dependencies

Benefits from **S-04** (ItemList/linking between product and COA hub). Should be sequenced after **T-01** (trivial) but before any link-building push — thin pages convert links poorly.

### Recommended action

Add to each product template:

- (a) current batch summary pulled from the linked COA `Dataset` (purity, test date, lab, methods)
- (b) storage and handling specifications
- (c) reconstitution/consumables note linking to bacteriostatic water — which also fixes **T-02**
- (d) explicit link to that compound's `/testing/` hub

Target 450–550 unique words. Keep all language RUO-compliant — describe what was measured, never what it does in a subject.

### Success check

All 15 product pages ≥ 400 unique words on re-crawl. Search Console impressions for compound-name queries rise over 8 weeks.

### Failure check / falsifiability

If impressions do not move after 8 weeks despite passing the gate, the constraint is domain authority (see **G-05**), not content depth — redirect effort to off-site work rather than writing more.

---

## O-01 — Homepage H1 carries no keyword signal

**Priority: 🟠 High** · Category: On-Page SEO

### Evidence

```html
<h1 id="pepselect-home-title">
    <span>The label is the easy part.</span>
    <em>What&rsquo;s behind it matters.</em>
</h1>
```

Title tag targets *"Research Peptides with Batch-Matched Lab Reports."* The H1 contains none of those terms. The eyebrow text "Research Without the Runaround" sits directly above it and also carries no target term.

### Affected URLs

`https://pepselect.com/` primarily. Same pattern on `/shop/` ("Selection is the standard.") and `/testing/` ("Every batch has a permanent address.").

### Reasoning

After the title, the H1 is the strongest on-page relevance signal. A pure brand-voice H1 forfeits it entirely. `/shop/` has the same pattern, as does `/testing/` — though `/testing/` compensates with a strong 60-character title.

### Dependencies

None. Independent of all other findings.

### Recommended action

Keep the voice, add the anchor — promote a keyword-bearing line into the H1 and demote the current line to sub-headline. Example preserving tone: **"Research peptides, with the batch record attached."** Apply the same treatment to `/shop/`.

### Success check

Homepage H1 contains the primary term; Search Console impressions for peptide head terms rise within 6 weeks.

### Failure check / falsifiability

If impressions do not move in 6 weeks, on-page relevance is not the constraint — domain authority is (**G-05**). Stop optimizing on-page and move to off-site.

---

## P-01 — 31–49 render-blocking stylesheets and 34–48 scripts on every page

**Priority: 🟠 High** · Category: Performance

> Inferred from HTML structure and measured asset weights. No field data available.

### Evidence

Measured across all 43 crawled pages:

| Page type | Stylesheets | Scripts | Total | `<head>` size |
|---|---|---|---|---|
| Product pages (15) | 47–48 | 47–48 | **~95** | ~55 KB |
| Homepage | 49 | 43 | 92 | 53 KB |
| `/shop/` | 41 | 40 | 81 | 51 KB |
| Static pages | 31–39 | 34–39 | 65–77 | 49–50 KB |
| `/testing/` pages | 32 | 34–35 | 66–67 | 50–53 KB |

Homepage payload measured: **416 KB JavaScript + 77 KB CSS** (compressed transfer). Largest contributors:

| Asset | Size | Needed on homepage? |
|---|---|---|
| `googletagmanager.com/gtag/js` | 187 KB | Analytics |
| `confetti.js` (side-cart plugin) | 34 KB | **No** |
| `jquery.min.js` | 30 KB | Yes (dependency) |
| `selectWoo.full.min.js` | 20 KB | **No** — no dropdowns present |
| `sweetalert2.min.js` (back-in-stock) | 19 KB | **No** |
| `elementor/frontend-modules.min.js` | 15 KB | Yes |
| YITH `modals.min.js` | 14 KB | **No** |
| YITH `owl.carousel.min.js` | 11 KB | **No** |

Also measured: **zero `<link rel="preload">` and zero `<link rel="preconnect">` on any page.** Only a `dns-prefetch` for googletagmanager.

### Affected URLs

All 43 crawled URLs; product pages worst at ~95 requests.

### Reasoning

First paint is serialized behind the slowest of up to 49 stylesheet requests. WooCommerce and its plugin ecosystem enqueue assets globally by default; roughly 80 KB of the homepage's JavaScript belongs to features that do not appear on it. This is also the most likely INP contributor, though INP cannot be confirmed without field data.

### Dependencies

**Blocks P-02 and I-01 from being measurable.** Converting the hero image will not visibly move LCP while 49 stylesheets block paint. Do this first.

### Recommended action

Conditionally dequeue plugin assets by page context (`is_front_page()`, `is_product()`, `is_page()`). Priority dequeues on the homepage: confetti, sweetalert2, selectWoo, owl.carousel, YITH modals. Add `preconnect` to `fonts.gstatic.com` and `googletagmanager.com`. Add `preload` for the LCP image per template.

### Success check

Total requests below 50 on the homepage and below 60 on product pages. PageSpeed Insights "Eliminate render-blocking resources" savings drop below 500 ms.

### Failure check / falsifiability

If request count drops below 50 and LCP is unchanged, the bottleneck is not asset count — re-measure with field data before investing further.

### Leading indicator

PSI mobile LCP, measured before and after.

---

## P-02 — Four Google Fonts stylesheets, each requesting all 9 weights plus italics

**Priority: 🟠 High** · Category: Performance

> Inferred from HTML structure. No field data available.

### Evidence

Four separate render-blocking cross-origin stylesheet requests:

```
fonts.googleapis.com/css?family=Roboto:100,100italic,...,900,900italic&display=swap
fonts.googleapis.com/css?family=Roboto+Slab:100,100italic,...,900,900italic&display=swap
fonts.googleapis.com/css?family=Plus+Jakarta+Sans:100,100italic,...,900,900italic&display=swap
fonts.googleapis.com/css?family=IBM+Plex+Mono:100,100italic,...,900,900italic&display=swap
```

Each declares 18 variants. **No `preconnect` to `fonts.gstatic.com`.**

### Affected URLs

All 43 crawled URLs.

### Reasoning

Browsers only download the woff2 files for weights actually used, so the font *payload* is smaller than the declaration suggests. The real cost is four render-blocking cross-origin round trips with no connection warming — DNS + TLS + request for each, on the critical path. Roboto Slab does not appear to be used in the visible design and is likely an Elementor default.

### Dependencies

Same subsystem as **P-01**; fix together.

### Recommended action

Restrict each family to the 2–3 weights actually rendered in Elementor's font settings. Remove Roboto Slab if unused. Add `<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>`. Consider self-hosting to eliminate the cross-origin hop entirely.

### Success check

Font stylesheet requests reduced from 4 to 1–2; `preconnect` present; PSI font-related opportunities cleared.

---

## P-03 — Product gallery image has no dimensions, no `loading`, no `fetchpriority`

**Priority: 🟠 High** · Category: Performance

> Inferred from HTML structure. No field data available.

### Evidence

The primary above-fold image on all 15 product pages:

```html
<img class="pepselect-compound-page__image"
     src="https://pepselect.com/wp-content/uploads/2026/06/RT10F-600x745.webp"
     data-full="https://pepselect.com/wp-content/uploads/2026/06/RT10F-scaled.webp"
     alt="GLP-3 R" data-pepselect-lightbox-open />
```

No `width`, no `height`, no `srcset`, no `sizes`, no `loading`, no `fetchpriority`.

### Affected URLs

All 15 `/product/*` URLs.

### Reasoning

This is the LCP element on every product page. Missing intrinsic dimensions means the browser cannot reserve layout space — a direct CLS contributor on the site's 15 most commercially important pages. Absent `fetchpriority="high"`, it competes with 95 other requests for bandwidth. The homepage hero does this correctly; the product template does not.

### Dependencies

Independent of **P-01**, but its LCP benefit will only be visible after P-01.

### Recommended action

Add `width` and `height` attributes, `fetchpriority="high"`, and a `srcset`/`sizes` pair to the `pepselect-compound-page__image` template in the child theme.

### Success check

CLS below 0.1 on product pages in PSI; no "image elements do not have explicit width and height" warning.

---

## I-01 — Seven images exceed 200 KB; every oversized file is a PNG

**Priority: 🟠 High** · Category: Images

### Evidence

Measured via HEAD requests across all 73 unique images (total 4.26 MB):

| Size | File | Format | Where |
|---|---|---|---|
| **374 KB** | `glutathione600-F-600x754.png` | PNG | `/product/glutathione/` — LCP element |
| **362 KB** | `PT141-10F-600x745.png` | PNG | `/product/pt-141/` — LCP element |
| **359 KB** | `PS-laying_fam-768x434.png` | PNG | Homepage hero — LCP element |
| **301 KB** | `tesamorelin-10mg-coa-source.webp` | WebP | Homepage, 3400×4400 intrinsic |
| **201 KB** | `Retatrutide_PSRT2062926JP_Summary-768x1086.png` | PNG | COA batch page |
| **198 KB** | `Retatrutide_30mg_ND_R30_060326_Summary-1-768x1086.png` | PNG | COA batch page |
| **197 KB** | `TB-500_TB10-6926_Summary-3-768x1086.png` | PNG | COA batch page |
| 184 KB | `NAD_PSNAD562926JP_Summary-768x1086.png` | PNG | COA batch page |
| 174 KB | `PT-141_PSPT14162926JP_Summary-768x1086.png` | PNG | COA batch page |
| 163 KB | `9nrNfe_B.png` | PNG | Homepage footer logo |
| 111 KB | `tesamorelin-10mg-vial-batch.webp` | WebP | Homepage |

**7 images over 200 KB (critical threshold); 11 over 100 KB.**

### Affected URLs

`/`, `/product/glutathione/`, `/product/pt-141/`, and the COA batch pages under `/testing/`.

### Reasoning

The pattern is unambiguous: **PNG is the entire problem.** Comparable WebP product images on the same site are 7 KB (`RT10F-300x300.webp`). Three of the four heaviest files are the LCP element of their respective pages. The COA summary images are document scans — the format best served by WebP compression and the worst possible use of PNG.

### Dependencies

LCP benefit gated behind **P-01**.

### Recommended action

Convert all 15 PNGs to WebP. Expect 80–90% reduction (≈1.8 MB saved sitewide). Set the WordPress media pipeline to generate WebP on upload so this does not regress. `claude-seo run` includes image optimization tooling for batch conversion.

### Success check

Zero images above 200 KB; no more than two above 100 KB. PSI "Serve images in next-gen formats" opportunity cleared.

---

## G-05 — Domain absent from Common Crawl web graph

**Priority: 🟠 High** *(strategic constraint, not a defect)* · Category: AI Search / GEO

### Evidence

```
Common Crawl Domain Metrics: pepselect.com
  Release:              cc-main-2026-jan-feb-mar
  PageRank:             None (rank #None)
  Harmonic Centrality:  None (rank #None)
  Number of hosts:      None
```

Moz and Bing Webmaster APIs are not configured, so this is the only available authority signal.

### Affected URLs

Domain-wide (`pepselect.com`).

### Reasoning

The domain does not appear in the Jan–Mar 2026 web graph at all. This is **consistent and expected** — schema `datePublished` values show content was created from 2026-06-24 onward, after that crawl window closed. It is not evidence of a penalty. It does establish the baseline: **there is no measurable external link graph yet.**

This is the most important strategic finding in the audit, and it reframes everything else. On-page and technical work has a ceiling set by domain authority. Pep Select's on-page quality is already above its category median; its authority is at zero. Past a certain point, further on-page refinement will not produce ranking movement for competitive head terms.

### Dependencies

Link acquisition should follow **C-02**, **C-04**, and **C-06** — thin pages and an anonymous entity convert outreach poorly.

### Recommended action

1. Configure Moz and/or Bing Webmaster API credentials so `/seo backlinks` can produce a real baseline.
2. Verify the domain in Google Search Console and Bing Webmaster Tools — this audit had no access to either.
3. Treat the **COA Quality Archive as the link asset.** It is genuinely citable: structured, free, permanent batch-level lab data with `Dataset` markup. That is a linkable resource in a category where almost nobody publishes verifiable data.

### Success check

Domain appears in the next Common Crawl release with a non-null PageRank. Referring domain count above zero in Search Console → Links.

### Leading indicator

Search Console → Links → Top linking sites, checked monthly.

---

*Continued in [`02-medium-and-low-findings.md`](02-medium-and-low-findings.md)*

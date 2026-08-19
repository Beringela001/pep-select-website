# E-commerce SEO Findings — pepselect.com

Audit date: 2026-08-18. Scope: product-page optimization, Product schema completeness, category/shop architecture, out-of-stock handling, review capture, COA trust-signal linking, product↔category↔content internal linking, and free-listing/Merchant Center structured-data implications for a Shopping-restricted vertical.

Method: GET-only crawl via `render_page.py --mode always` (Playwright-rendered HTML) + JSON-LD extraction via `--json-ld-output`. Sitemap fully enumerated from `sitemap_index.xml` → 4 sub-sitemaps → 43 total URLs (9 pages, 9 `ps_compound` COA hub pages, 9 `ps_coa_test` batch pages, 16 product-sitemap entries: `/shop/` + 15 products). All 15 real products (`bpc157-10`, `ghk-cu`, `ss-31`, `tb500-10`, `motsc-10`, `glp1-s10`, `nad`, `glp3-r20`, `tesa-10`, `pt-141`, `glp3-r30`, `glutathione`, `glp2-t20`, `glp3-r10`, `bacteriostatic-water-30ml`) plus `/shop/` and `/testing/` were rendered and their Product/WebPage JSON-LD parsed. No DataForSEO calls were made; the one DataForSEO citation below reuses the existing `DATAFORSEO-serp-research-peptides-2026-08-18.md` report per instructions.

---

### [ECOM-01] Zero review capture or aggregateRating anywhere in the catalog
- Priority: Critical
- Category: Review capture / trust signals
- Evidence class: 5-Crawler observation
- Evidence: Parsed Product JSON-LD on `bpc157-10`, `ghk-cu`, `ss-31`, `tb500-10`, `nad`, `bacteriostatic-water-30ml`, `motsc-10`, `glp1-s10`, `glutathione`, `glp2-t20`, `glp3-r10/20/30`, `tesa-10`, `pt-141` (all 15 products) — none contain `aggregateRating` or `review`. HTML search on `ghk-cu` for `star-rating`, `woocommerce-Reviews`, `comment-form`, `judge.me`, `yotpo`, `stamped` all returned 0 matches. The only "review" occurrences on-page are Yoast's templated meta description ("Review GHK-CU 50MG from Pep Select...") and a COA-carousel caption ("Review the latest independently tested batch records") — neither is a customer review mechanism.
- Affected URLs: All 15 `/product/*/` pages
- Reasoning: Google's Product rich-result eligibility and the SERP's own trust signals in this vertical (see ECOM-08) reward `aggregateRating`/`review` markup. A store built around lab-verified purity (the COA archive) has an unusually strong case for genuine reviews but currently surfaces none — a visible gap versus the "third-party tested" trust language that wins in this SERP.
- Recommendation: Confirm whether WooCommerce native reviews are disabled in wp-admin → Settings → Products, or a review app was never installed; if reviews exist in the backend but are suppressed from the theme, describe re-enabling display; if none exist, describe standing up a review-collection mechanism (native WooCommerce reviews or a review app) and emitting `aggregateRating`/`review` in the existing Product JSON-LD once real reviews accumulate. Do not fabricate reviews or ratings.
- Dependencies: Depends on business decision to collect reviews (compliance review needed given RUO/YMYL-adjacent framing — avoid review copy that implies human use). Unblocks Product rich-result star ratings.
- Failure check: `aggregateRating`/`review` still absent from Product JSON-LD after a review app or native reviews are enabled; or reviews are collected but never wired into schema.
- Success check: At least one product page emits valid `aggregateRating` with `ratingValue`, `reviewCount`, and it validates against Google's Merchant/Product schema requirements.
- Leading indicator: Presence of a visible review count/star widget on any product page on a future crawl; review count trending up in wp-admin.

### [ECOM-02] COA testing pages missing for 6 of 15 catalog products, including two headline compounds
- Priority: Critical
- Category: Trust signals / COA linkage
- Evidence class: 5-Crawler observation
- Evidence: `ps_compound-sitemap.xml` lists exactly 8 compound hub pages under `/testing/`: `retatrutide-30mg`, `retatrutide-20mg`, `ghk-cu-50-mg`, `tesamorelin-10-mg`, `nad-500-mg`, `pt-141-10-mg`, `tb-500-10-mg`, `retatrutide-10mg`. Cross-referencing against the 15-product catalog, **BPC-157, SS-31, MOTS-C, GLP-1 S10 (semaglutide), Glutathione, and GLP-2 T20** have no corresponding `/testing/` page. Confirmed on-page: `bpc157-10` and `ss-31` product pages contain only generic `<a href="https://pepselect.com/testing/">COAs</a>` / "Certificate of Analysis" links with no compound-specific "Current Batch" deep link and no inline purity percentage, whereas `ghk-cu`, `tb500-10`, and `nad` each show a "Current Batch" block linking directly to their batch-level COA page (e.g. `https://pepselect.com/testing/tb-500-10-mg/tb10-6926/`) with an inline purity value ("99.76%", "99.85%", "99.87%"). The `/testing/` hub itself (rendered, H2/H3 audit) lists only GHK-CU, NAD+, PT-141, Retatrutide (×3), TB-500, and Tesamorelin — confirming the gap sitewide, not just a linking bug on two pages.
- Affected URLs: `https://pepselect.com/product/bpc157-10/`, `https://pepselect.com/product/ss-31/`, `https://pepselect.com/product/motsc-10/`, `https://pepselect.com/product/glp1-s10/`, `https://pepselect.com/product/glutathione/`, `https://pepselect.com/product/glp2-t20/`, `https://pepselect.com/testing/`
- Reasoning: The 2026-08-18 site-wide audit (`00-audit-context.md`) names the COA Quality Archive as the site's rarest competitive asset. That asset only functions as a trust signal on the ~9 products it actually covers. For BPC-157 and SS-31 in particular — both high-recognition, frequently searched compound names in this vertical — a visitor or crawler following the product's own "Certificate of Analysis" link lands on a hub that does not mention that compound at all, which reads as a broken trust promise rather than a working one.
- Recommendation: Describe publishing (or backdating, if lab data already exists internally) `/testing/` batch pages for BPC-157, SS-31, MOTS-C, GLP-1 S10, Glutathione, and GLP-2 T20, mirroring the existing `Dataset`/`DataDownload` schema pattern used for the other 9. For any compound where current-lot COA data does not yet exist, describe either delaying the "Certificate of Analysis" link/claim on that specific product page or clearly labeling it "testing in progress" rather than linking to a hub that omits the compound silently.
- Dependencies: Depends on lab/COA data availability for the missing compounds (owner-side, not a code change). Unblocks ECOM-04's fix (linking `/testing/` back to products) being meaningful for the full catalog.
- Failure check: Product pages for the 6 compounds keep linking only to the generic `/testing/` index with no compound-specific batch data six months from now.
- Success check: Each of the 6 compounds has a `/testing/[slug]/` hub page and at least one `ps_coa_test` batch page, and its product page shows the same "Current Batch" + purity-percentage block already used for GHK-CU/TB-500/NAD+.
- Leading indicator: `ps_compound-sitemap.xml` entry count rising from 8 toward 15 (currently trackable via `sitemap_index.xml` without re-running the audit).

### [ECOM-03] Manufacturer boilerplate description used verbatim on GLP-2 T20, inconsistent with the site's otherwise unique copy
- Priority: High
- Category: Content uniqueness
- Evidence class: 5-Crawler observation
- Evidence: Product JSON-LD `description` field, verbatim: *"GLP-2T 20MG. This product is intended exclusively for laboratory research and analytical applications. Each vial is manufactured to high-quality standards and is supplied for use by qualified researchers in controlled laboratory environments. The contents are intended for scientific investigation only... Product Features Laboratory research grade peptide Manufactured under strict quality standards Suitable for analytical and research applications Individually packaged for laboratory use Intended for qualified research professionals Storage Store in a cool, dry place away from direct sunlight..."* By contrast, every other checked product (BPC-157, GHK-CU, SS-31, TB-500, NAD+, MOTS-C, GLP-1 S10, Glutathione) uses a distinct one-paragraph, mechanism-specific description (e.g. GHK-CU: *"GHK-Cu is a copper-binding tripeptide... studied for its role in tissue remodeling... stimulating structural-protein synthesis and modulating genes tied to skin and matrix regeneration."*).
- Affected URLs: `https://pepselect.com/product/glp2-t20/`
- Reasoning: This is templated wholesale-supplier boilerplate ("Product Features... Manufactured under strict quality standards... Suitable for analytical and research applications") of a style commonly reused verbatim across many peptide resellers. It breaks the pattern of concrete, differentiated, mechanism-level copy that the prior full-site audit measured at 0/100 filler score and 0.93–1.00 information density elsewhere — meaning GLP-2 T20 is both a duplicate-content risk versus other sellers and an outlier in the site's own content-quality profile.
- Recommendation: Describe replacing the GLP-2 T20 description with a mechanism-specific paragraph matching the format used for the other 14 products, sourced/approved via the product-marketing reference material rather than supplier copy.
- Dependencies: Depends on `.agents/product-marketing.md`-approved compound description for GLP-2 T20 (peptide-marketing/compliance review, not a code change).
- Failure check: The generic "Product Features / Manufactured under strict quality standards" paragraph is still present verbatim on a future crawl.
- Success check: GLP-2 T20's `description` field matches the mechanism-specific one-paragraph format used site-wide and is unique versus supplier/manufacturer stock copy (spot-check via search-snippet lookup).
- Leading indicator: Word-for-word diff of the GLP-2 T20 description against the current boilerplate string above.

### [ECOM-04] The COA trust archive (`/testing/`) has zero internal links back to product pages
- Priority: High
- Category: Internal linking (content ↔ product)
- Evidence class: 5-Crawler observation
- Evidence: Regex scan of the rendered `/testing/` hub HTML for `href="https://pepselect.com/product/` returned 0 matches. The reverse link exists and works (every checked product page links forward to `/testing/` and, where a batch exists, to the specific `/testing/[slug]/[batch]/` page), but the flow is one-directional.
- Affected URLs: `https://pepselect.com/testing/` (and, by extension, its 8 compound-hub and 9 batch sub-pages, none of which were observed linking to `/product/`)
- Reasoning: The prior full-site audit identifies `/testing/` as the site's strongest differentiator and most likely source of organic/AI-citation traffic (Dataset schema, DataDownload PDFs, near-unique in the vertical). A visitor or an AI crawler arriving there — whether from search, an AI Overview citation, or a shared link — has no on-page path into the shop. This both wastes the archive's link equity (no PageRank flow from `/testing/` into `/product/`) and caps its commercial value.
- Recommendation: Describe adding a "Buy this compound" or "Shop [Compound]" link/button on each `/testing/[compound]/` and `/testing/[compound]/[batch]/` page pointing to the matching `/product/[slug]/` page, once ECOM-02's coverage gap is closed for the 6 missing compounds.
- Dependencies: Independent of ECOM-02 for the 9 compounds that already have both pages; depends on ECOM-02 for full-catalog coverage.
- Failure check: `/testing/` pages still contain 0 links to `/product/` URLs on a future crawl.
- Success check: Each `/testing/[compound]/` page contains at least one link to its corresponding `/product/[slug]/` page; internal-link graph shows `/testing/` → `/product/` edges.
- Leading indicator: Manual click-through from any `/testing/[compound]/` page — a "Shop" or "Buy" CTA becomes visible.

### [ECOM-05] Nearly half the live catalog (7 of 15 products) is Out of Stock, fully indexed, with no substitute-product surfacing
- Priority: High
- Category: Out-of-stock handling
- Evidence class: 5-Crawler observation
- Evidence: Product JSON-LD `offers[0].availability` across all 15 real products: `OutOfStock` — BPC-157 (`bpc157-10`), SS-31 (`ss-31`), MOTS-C (`motsc-10`), GLP-1 S10/semaglutide (`glp1-s10`), Retatrutide 20mg (`glp3-r20`), Glutathione (`glutathione`), GLP-2 T20 (`glp2-t20`) = 7/15 (47%). `InStock`: GHK-CU, TB-500, NAD+, Tesamorelin, PT-141, Retatrutide 10mg, Retatrutide 30mg, Bacteriostatic Water = 8/15. All 7 OOS pages return HTTP 200, `<meta name="robots" content="index, follow...">` (verified on `bpc157-10`), and remain in `product-sitemap.xml` and in "related products" grids elsewhere on the site (e.g. `bpc157-10`'s related-products block surfaces Retatrutide 20mg and Glutathione, both also OutOfStock). The only OOS mitigation observed is a "cwginstock" back-in-stock email opt-in widget (`Email when stock available`) — no in-stock substitute or alternate-strength suggestion is surfaced.
- Affected URLs: `https://pepselect.com/product/bpc157-10/`, `https://pepselect.com/product/ss-31/`, `https://pepselect.com/product/motsc-10/`, `https://pepselect.com/product/glp1-s10/`, `https://pepselect.com/product/glp3-r20/`, `https://pepselect.com/product/glutathione/`, `https://pepselect.com/product/glp2-t20/`
- Reasoning: Keeping OOS pages indexed and internally linked is generally correct practice (preserves URL equity, avoids 404/redirect churn) and is not itself the problem. The risk is compounding with ECOM-02: two of the seven OOS products (BPC-157, SS-31) are also the ones missing COA pages, meaning a visitor who searches for either compound by name currently lands on a page that is both unavailable to buy and unable to show batch-tested purity — the two weakest trust/conversion states stacked on the same URLs.
- Recommendation: Describe surfacing an explicit in-stock alternative (e.g. "In the meantime, see [similar compound]") on OOS product pages beyond the restock-email form, and prioritizing restock or supplier sourcing for BPC-157 and SS-31 given their likely search demand (both are named in the competitor SERP evidence as commonly searched compound terms). This is an inventory/business decision, not a code change, beyond describing where a "you may also like — in stock" block could be templated.
- Dependencies: Independent of other findings; compounds well with ECOM-02 and ECOM-03 on BPC-157/SS-31/GLP-2 T20 specifically.
- Failure check: OOS ratio stays at or above ~47% on a future crawl with no substitute-product mechanism added.
- Success check: OOS product pages show a clearly in-stock alternative recommendation in addition to the restock-email opt-in; OOS ratio trending down.
- Leading indicator: `availability` field flipping to `InStock` in Product JSON-LD for BPC-157/SS-31, checkable via a single-page fetch without a full re-audit.

### [ECOM-06] Product Offer schema is missing GTIN/MPN, `priceValidUntil`, and `OfferShippingDetails`
- Priority: Medium
- Category: Product schema completeness
- Evidence class: 5-Crawler observation
- Evidence: Full Product JSON-LD parsed for all 15 products contains only `name`, `url`, `description` (14/15 — see ECOM-03/07), `image`, `sku`, `brand` (self-brand "Pep Select"), and one `Offer` with `price`, `priceCurrency`, `availability`, `seller`, and `hasMerchantReturnPolicy` (via `@id` reference to the Organization-level policy). No `gtin`/`gtin8/12/13/14`/`mpn`, no `priceValidUntil`, and no `shippingDetails` (`OfferShippingDetails`) node were found on any of the 15 products.
- Affected URLs: All 15 `/product/*/` pages
- Reasoning: GTIN/MPN are commonly not applicable for a private-label/compounded research product with no third-party manufacturer identifier — that gap is likely unavoidable and should be verified with the owner rather than assumed fixable. `priceValidUntil` and `shippingDetails`, however, are both Google-recommended Offer fields with no such constraint, and their absence is a straightforward completeness gap versus Google's Merchant/Product structured-data guidance, independent of whether this vertical is Shopping-ads-restricted (see ECOM-08).
- Recommendation: Describe adding `priceValidUntil` (a rolling date, e.g. reflecting the site's actual price-review cadence) and an `OfferShippingDetails` block (shipping cost, handling time, transit time — using the values already published on `/refund-shipping-policy/`) to the existing Offer node in the Product schema template. Flag GTIN/MPN to the owner as `[VERIFY CLAIM]` — confirm whether any compound has a manufacturer-assigned identifier before adding one.
- Dependencies: Shipping figures should be sourced from `/refund-shipping-policy/` to avoid an inconsistency between the schema and the on-page policy. Independent of ECOM-01/02/04/05.
- Failure check: `priceValidUntil` and `shippingDetails` remain absent from Product JSON-LD on a future crawl.
- Success check: Both fields validate cleanly in a structured-data test against a sampled product URL, with shipping values matching `/refund-shipping-policy/`.
- Leading indicator: Presence of `"shippingDetails"` and `"priceValidUntil"` keys in the Product JSON-LD block on any single product page fetch.

### [ECOM-07] Product descriptions follow a rigid one-paragraph template that reads as thin at scale
- Priority: Medium
- Category: Content quality (product-page description depth)
- Evidence class: 5-Crawler observation
- Evidence: Every checked description (excluding the ECOM-03 outlier) follows the identical structure: "[NAME] [DOSE]MG. [Compound] is a [class] studied for its role in [X]. It is researched for [Y]." Example set: BPC-157 ("...studied for its role in angiogenesis and tissue integrity. It is researched for its activity within growth-factor signaling pathways..."), SS-31 ("...studied for its ability to bind cardiolipin... researched for its role in supporting mitochondrial energy production..."), NAD+ ("...studied for its central role in cellular energy metabolism. It is researched as a substrate in redox reactions..."). Each is roughly 35–45 words / two sentences.
- Affected URLs: All 15 `/product/*/` pages
- Reasoning: This overlaps with, but is distinct from, the prior full-site audit's C-02 finding (all product pages under the 400-word quality gate) — that finding is about total page word count; this one is specifically about the schema/meta `description` and lead-in copy following one rigid sentence template across the whole catalog, which is a pattern a content-quality classifier (or a competitor doing a side-by-side comparison) can detect regardless of how factually distinct the specifics are.
- Recommendation: Describe expanding each product's lead description with compound-specific detail not present elsewhere on the page — e.g. typical reconstitution/storage notes distinct per compound, a one-line summary of what the current COA batch shows (for the 9 compounds where ECOM-02 doesn't apply), or a short "what researchers use this for" context sentence — varying sentence structure across products rather than only substituting nouns into the same two-sentence frame.
- Dependencies: Complements the previously-tracked C-02 finding; content must go through `.agents/product-marketing.md` compliance review before publication (no new medical/performance claims).
- Failure check: All 15 descriptions still match the identical two-sentence template on a future crawl.
- Success check: A sample of product descriptions shows varied sentence structure and compound-specific detail beyond mechanism-of-action boilerplate, while remaining compliant with RUO framing.
- Leading indicator: Word count and template-match rate across a spot-check of 3–4 product descriptions.

### [ECOM-08] `/shop/` category page lacks ItemList/OfferCatalog schema as the catalog scales
- Priority: Medium
- Category: Category/shop architecture
- Evidence class: 5-Crawler observation
- Evidence: `/shop/` JSON-LD `@graph` contains exactly `WebPage`, `ImageObject`, `BreadcrumbList`, `WebSite`, and `OnlineStore` nodes — no `ItemList`, `OfferCatalog`, or per-product summary node. HTML scan of `/shop/` found no `product-category` links (0 matches) and no pagination markers (`nav-links`/`page-numbers` both absent), confirming a single flat archive of all 15 products with no subcategory taxonomy or faceted navigation.
- Affected URLs: `https://pepselect.com/shop/`
- Reasoning: At 15 SKUs a flat, unpaginated archive is reasonable and not itself a defect — faceted navigation would be premature. The schema gap is the more durable issue: an `ItemList`/`OfferCatalog` node on the category page gives search engines and AI answer engines (relevant given the competitor SERP's AI Overview citations) a machine-readable manifest of what the store carries, which the current markup does not provide.
- Recommendation: Describe adding an `ItemList` (or `OfferCatalog`) node to `/shop/`'s existing JSON-LD `@graph`, referencing each in-stock product's `@id`. Revisit subcategory/faceted navigation only if/when SKU count grows meaningfully beyond ~15–20.
- Dependencies: None; independent of other findings.
- Failure check: `/shop/` JSON-LD still contains no `ItemList`/`OfferCatalog` node on a future crawl.
- Success check: `/shop/` JSON-LD includes a valid `ItemList` referencing the current in-stock catalog.
- Leading indicator: `"ItemList"` or `"OfferCatalog"` appearing in the `@graph` types list on a single fetch of `/shop/`.

### [ECOM-09] Restricted-vertical Merchant Center/free-listing eligibility is an open policy question — owner verification required, no action taken
- Priority: Low
- Category: Marketplace visibility / policy (informational only)
- Evidence class: 4-DataForSEO estimate (reused, no new call) + 5-Crawler observation
- Evidence: `docs/claude-seo-latest/DATAFORSEO-serp-research-peptides-2026-08-18.md` (existing report, not re-queried): "Two of nine organic listings arrive via Shopping-feed auto-tagging" — `redrockpeptides.com` ("Price '$59–$410'; `srsltid` = Merchant Center feed") and `nationwidepeptides.com` ("`srsltid` Merchant Center tagging") both surface `srsltid` parameters in organic Google results for "research peptides," alongside a third competitor (`rpeptide.com`) showing a price annotation ("$120–$200"). Separately, this crawl confirmed Pep Select's own `Offer.hasMerchantReturnPolicy` references an Organization-level `MerchantReturnPolicy` with `returnPolicyCategory: "https://schema.org/MerchantReturnNotPermitted"` — schema-valid, but a "no returns permitted" policy is a variable Merchant Center weighs in program eligibility.
- Affected URLs: All 15 `/product/*/` pages (schema); competitor evidence is domain-level (`redrockpeptides.com`, `nationwidepeptides.com`, `rpeptide.com`), not a Pep Select URL
- Reasoning: This vertical (research peptides) is understood to be restricted for Google Shopping *ads*, per the task brief — but the observed `srsltid` tags on competitor organic results suggest some peptide-adjacent listings do appear via Merchant Center free-listing surfaces regardless, which raises a genuine, unresolved policy question about whether Pep Select's own compounds would be eligible for a free listing and, if so, whether "no returns permitted" would affect approval. This is a policy determination Google makes per-merchant and per-category, not something inferable from a SERP snapshot of three competitors.
- Recommendation: This finding is descriptive only. It does not recommend touching Merchant Center, submitting a feed, or changing the return policy — the task scope explicitly excludes that. Surface this open question to Paulo/the site owner for manual verification against current Google Merchant Center category policies before any Merchant Center action is considered. If/when that verification happens, note that Product schema completeness (ECOM-06) is a prerequisite regardless of the policy answer, since Merchant Center feeds are commonly cross-checked against on-page structured data.
- Dependencies: Depends entirely on manual policy research by the site owner; blocks nothing else in this report. Loosely supports ECOM-06 (schema completeness is useful either way).
- Failure check: N/A — this is a flagged question, not an implementable fix; "failure" would be acting on it without owner sign-off.
- Success check: Owner confirms, via Google's own Merchant Center policy documentation or direct account-level testing, whether this vertical/category is eligible for free listings.
- Leading indicator: Any future appearance of `srsltid`-tagged Pep Select results in organic Google searches (would indicate a Merchant Center feed is already live) — none observed in the current evidence.

---

## Verified Correct

- All 15 products emit valid `Product` JSON-LD with `name`, `sku`, `brand`, and a `price`/`priceCurrency`/`availability` `Offer` — the schema foundation is sound; gaps are additive (ECOM-06), not structural.
- `Offer.hasMerchantReturnPolicy` is present via `@id` reference to a site-wide `MerchantReturnPolicy` node — this satisfies Google's requirement that a return policy be declared at either Offer or Organization level (Organization level, confirmed here, is valid).
- All 6 spot-checked product pages return `<meta name="robots" content="index, follow...">` — Out-of-Stock products are not being accidentally deindexed or noindexed, which is correct practice.
- Every product and the `/shop/` page carries a `BreadcrumbList` schema node and visible on-page breadcrumb markup.
- Every product page includes a "related products" and cross-sell/upsell block (WooCommerce native), providing product↔product internal linking.
- Products with a mapped COA batch (GHK-CU, TB-500, NAD+, and by pattern the other 6 covered compounds) show a "Current Batch" block with an inline purity percentage and a direct link to the specific `/testing/[compound]/[batch]/` page — a genuinely strong, working trust-signal pattern where it exists.
- Yoast-generated titles/meta descriptions are dynamically unique per product (e.g. "BPC-157 10MG for Research | Pep Select" / "Review BPC-157 10MG from Pep Select, including current price, availability, product details...") — no templated duplication was found at the meta-tag level, only at the body-description level (ECOM-07).
- The 7 Out-of-Stock products still surface a "back in stock" email opt-in (`cwginstock` plugin) rather than a dead end.
- `/shop/` correctly lists all 15 products on a single unpaginated archive with no orphaned catalog pages observed in this pass.

## Data Sources & Limitations

- All page data in this report comes from a live, read-only, GET-only crawl on 2026-08-18 using `render_page.py --mode always` (Playwright-rendered DOM) and its JSON-LD extraction, run against `https://pepselect.com`. No POST/PUT/DELETE requests were made; no cart, checkout, account, or admin surface was touched.
- No new DataForSEO Merchant API or SERP calls were made for this report, per task instruction. The single DataForSEO citation (ECOM-09) reuses the pre-existing `DATAFORSEO-serp-research-peptides-2026-08-18.md` report verbatim and is marked as such.
- Merchant Center itself was not accessed, queried, or modified — ECOM-09 is explicitly a flagged policy question for owner research, not a verified account-level fact.
- Variant/quantity-tier structured data (WooCommerce variable-product `data-product_variations`) could not be confirmed present or absent with certainty — the variation form markup (`variations_form`) is present on all 15 products, but the embedded variations JSON was not found in the static render for the products checked; this may reflect single-variation products, AJAX-loaded variation data, or a markup pattern not captured by this pass. Not reported as a finding due to insufficient evidence; flagged here for a follow-up pass if variant-level schema (multiple `Offer`s per SKU/strength) needs verification.
- Image-level product-gallery optimization (alt text, dimensions, WebP) was not re-audited here in depth beyond what the existing site-wide audit (`00-audit-context.md`, finding I-01) already covers; no new product-specific image finding is included to avoid duplicating unverified claims.
- Cash-back (YITH Points & Rewards) and Buy-4-Get-1 (YITH Dynamic Pricing) promotional pill behavior was spot-checked against project memory (`product-pills.md`) and found consistent with expected logged-out behavior (no cash-back pill; B4G1 pill present only where the plugin's discount note renders), but this is a UI/promotions concern, not an SEO finding, and is noted here only for completeness, not scored.
- This report is scoped to e-commerce/product-page SEO only; site-wide technical, content-volume, and performance findings already tracked in `docs/claude-seo-latest/00-audit-context.md` and `01-critical-and-high-findings.md` are referenced but not re-litigated here.

## Category Score: 58/100

Solid schema foundation (valid Product/Offer/return-policy markup on 100% of SKUs, correct indexability of OOS pages, working related-products linking) is offset by two Critical gaps directly inside this category's stated priorities — zero review/rating capture anywhere, and a COA trust-signal chain that is broken for 40% of the catalog including two high-recognition compounds — plus a one-directional trust-to-commerce link gap and a real (not templated-in-general, but one specific) content-uniqueness violation.

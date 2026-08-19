# Schema.org Structured Data Audit — pepselect.com

Date: 2026-08-18
Method: Read-only GET crawl via `claude-seo run render_page.py --mode auto` (raw HTML; no page in this sample triggered SPA rendering — `is_spa: false`, `mode_used: raw` on every URL). JSON-LD extracted with `--json-ld-output` (bounded full blocks); microdata/RDFa checked by grepping raw HTML for `itemscope`, `itemtype=`, `typeof=`.

Sitemap enumeration: `sitemap_index.xml` → `page-sitemap.xml`, `product-sitemap.xml`, `ps_compound-sitemap.xml`, `ps_coa_test-sitemap.xml`.

Pages sampled:
- Homepage: `https://pepselect.com/`
- Shop/category: `https://pepselect.com/shop/`
- Product ×3: `https://pepselect.com/product/bpc157-10/`, `https://pepselect.com/product/ghk-cu/`, `https://pepselect.com/product/ss-31/`
- `/testing/` (compound archive index): `https://pepselect.com/testing/`
- `/testing/` compound-level page: `https://pepselect.com/testing/retatrutide-30mg/`
- `/testing/` batch/COA leaf page (Dataset/DataDownload): `https://pepselect.com/testing/retatrutide-30mg/nd_r30_060326/`
- Contact page (used as about/contact): `https://pepselect.com/contact/`
- `/faq/` (checked only to confirm FAQPage status per hard rules): `https://pepselect.com/faq/`

---

### [SCHEMA-01] Product pages emit two disconnected JSON-LD graphs with inconsistent @context
- Priority: High
- Category: Graph integrity / emitter conflict
- Evidence class: [5-Crawler observation/inference]
- Evidence: Each of the 3 product pages returns exactly 2 separate `<script type="application/ld+json">` blocks (`grep -c 'application/ld+json'` = 2 on `/product/bpc157-10/`). Block 1 (Yoast SEO): `"@context": "https://schema.org"`. Block 2 (WooCommerce core Product schema): `"@context": "https://schema.org/"` (trailing slash) with its own top-level `@type: "Product"` — not nested inside Yoast's `@graph`.
- Affected URLs: `/product/bpc157-10/`, `/product/ghk-cu/`, `/product/ss-31/` (pattern applies to all products site-wide via WooCommerce template)
- Reasoning: Two independent plugins (Yoast SEO and WooCommerce core structured data) each emit their own `<script>` tag instead of one merged `@graph`. This is common in WooCommerce+Yoast stacks and is generally tolerated by Google's parser (each block is validated independently), but the mismatched `@context` string (`https://schema.org` vs `https://schema.org/`) and the lack of a single graph increases the chance of future entity duplication (see SCHEMA-02, SCHEMA-03) and makes maintenance/debugging harder.
- Recommendation: Standardize on a single `@context` value (`https://schema.org`, no trailing slash) for both emitters. If merging emitters into one `@graph` is not feasible without custom development, at minimum normalize the WooCommerce block's `@context` to match Yoast's, and link the two graphs via shared `@id`s (see SCHEMA-02) rather than leaving them as fully independent documents. Description only — no code should be deployed by this audit.
- Dependencies: Unblocks SCHEMA-02 and SCHEMA-03 (entity de-duplication depends on having a consistent context/@id strategy first).
- Failure check: Google Rich Results Test / Search Console still reports both blocks as independently valid but continues to show them as unrelated entities in the "detected structured data" panel.
- Success check: Rich Results Test shows one coherent set of entities per URL with matching `@context`, and the Offer's seller/organization resolves to the same `@id` as the page's Organization node.
- Leading indicator: In Search Console → Enhancements, Product and Merchant listing item counts remain stable or improve after normalization (a drop would indicate a hidden dependency on the split-script structure).

### [SCHEMA-02] Offer.seller is a disconnected duplicate of the site Organization entity
- Priority: Medium
- Category: Duplicate entity / graph integrity
- Evidence class: [5-Crawler observation/inference]
- Evidence: WooCommerce Product block on every sampled product page: `"seller": {"@type": "OnlineStore", "name": "Pep Select", "url": "https://pepselect.com"}` — no `@id`. Meanwhile Yoast's block on the same page defines the canonical entity at `"@id": "https://pepselect.com/#organization"` with full detail (logo, `hasMerchantReturnPolicy`, etc.).
- Affected URLs: `/product/bpc157-10/`, `/product/ghk-cu/`, `/product/ss-31/`
- Reasoning: Schema.org/Google parsers treat objects without `@id` as anonymous blank nodes. Because `Offer.seller` restates `name`/`url` instead of referencing `{"@id": "https://pepselect.com/#organization"}`, the "same real-world seller" is represented as two unconnected nodes on the same page. This dilutes entity consolidation that Google's Knowledge Graph and Merchant Center matching rely on.
- Recommendation: Change `Offer.seller` to reference the canonical organization by `@id` (`{"@id": "https://pepselect.com/#organization"}`) instead of repeating name/url inline. This is a WooCommerce Product-schema template change, described only.
- Dependencies: Depends on SCHEMA-01 (context alignment). Unblocks cleaner sameAs/NAP consolidation (SCHEMA-04/05).
- Failure check: Rich Results Test / manual graph inspection still shows two separate "Pep Select" organization-type nodes on the same page.
- Success check: Only one Organization/OnlineStore node with `@id` `#organization` appears per page, referenced by both Yoast's `publisher` and WooCommerce's `seller`.
- Leading indicator: No new count of "Organization" entities appears in Search Console structured-data reports beyond the expected single site-wide entity.

### [SCHEMA-03] Dataset.creator on COA batch pages is a third disconnected "Pep Select" entity
- Priority: Medium
- Category: Duplicate entity / graph integrity
- Evidence class: [5-Crawler observation/inference]
- Evidence: On the COA leaf page, the Dataset node contains: `"creator": {"@type": "Organization", "name": "Pep Select", "url": "https://pepselect.com/"}` (no `@id`), while the same page's Yoast block already defines `"@id": "https://pepselect.com/#organization"` typed `OnlineStore`.
- Affected URLs: `/testing/retatrutide-30mg/nd_r30_060326/` (pattern applies to all `ps_coa_test` leaf pages per `ps_coa_test-sitemap.xml`, e.g. `/testing/ghk-cu-50-mg/psgkcu5071926gx/`, `/testing/tb-500-10-mg/tb10-6926/`, etc.)
- Reasoning: Same root cause as SCHEMA-02 — this is now the third distinct, disconnected node describing "Pep Select" found across the sampled pages (Yoast's `#organization` OnlineStore, WooCommerce's anonymous Offer.seller OnlineStore, and now Dataset's anonymous Organization creator). None share an `@id`, so no single entity accumulates all the trust signals (logo, return policy, eventual sameAs) that Google could otherwise consolidate.
- Recommendation: Point `Dataset.creator` at `{"@id": "https://pepselect.com/#organization"}` instead of restating name/url. Description only.
- Dependencies: Same remediation pattern as SCHEMA-02; can be batched together as one graph-consolidation change.
- Failure check: Structured Data Testing shows Dataset.creator as an anonymous node distinct from the site's main Organization.
- Success check: Dataset.creator resolves to the same `@id` used sitewide for Pep Select.
- Leading indicator: None directly observable in GSC; verify via spot-check of Rich Results Test output after the change.

### [SCHEMA-04] Organization/OnlineStore entity has no `sameAs` anywhere sampled
- Priority: Medium
- Category: Organization / entity building
- Evidence class: [5-Crawler observation/inference]
- Evidence: The `#organization` node (typed `OnlineStore`) on every sampled page contains only `name`, `url`, `logo`/`image`, and `hasMerchantReturnPolicy`. No `sameAs` array is present on homepage, shop, testing index, testing compound page, COA leaf page, product pages, or contact page.
- Affected URLs: site-wide (`https://pepselect.com/#organization`, verified absent on `/`, `/shop/`, `/testing/`, `/testing/retatrutide-30mg/`, `/testing/retatrutide-30mg/nd_r30_060326/`, `/product/bpc157-10/`, `/product/ghk-cu/`, `/product/ss-31/`, `/contact/`)
- Reasoning: `sameAs` linking the site's Organization entity to owned profiles (e.g., verified social/business profiles) is a standard, low-risk way to help Google disambiguate the brand entity for Knowledge Panel and Merchant Center matching. Its complete absence across every sampled page indicates it was never configured in the Yoast Organization settings (or the corresponding fields are empty), not a per-page inconsistency.
- Recommendation: If Pep Select maintains verified official profiles (business social accounts, Wikidata/Crunchbase if applicable, verified marketplace profiles), add them as a `sameAs` array on the `#organization` node via Yoast's Organization settings ("Social profiles" field), each URL absolute. Do not fabricate placeholder profile URLs. Description only.
- Dependencies: None; independent of SCHEMA-01–03.
- Failure check: `sameAs` remains absent after Yoast social-profile fields are checked and populated (indicates a template override elsewhere).
- Success check: `#organization` node includes a `sameAs` array of verified, live, absolute URLs.
- Leading indicator: Google Knowledge Panel (if/when one appears for the brand name) reflects the linked profiles.

### [SCHEMA-05] Organization entity and /contact/ page carry no NAP (address/telephone/contactPoint) data
- Priority: Medium
- Category: Organization / ContactPage
- Evidence class: [5-Crawler observation/inference]
- Evidence: `/contact/` emits only generic `WebPage` + `BreadcrumbList` + the sitewide `WebSite`/`OnlineStore` blocks (identical structure to every other page — see full JSON-LD excerpt captured for this page, which contains no `ContactPage` type, `address`, `telephone`, `email`, or `ContactPoint` property anywhere). The `#organization` node itself also has no `address`, `telephone`, or `contactPoint` on any sampled page.
- Affected URLs: `https://pepselect.com/contact/` (primary), plus the sitewide `#organization` node
- Reasoning: This is an opportunity gap rather than a spec violation — generic `Organization`/`OnlineStore` schema does not require address/telephone, and an online-only research-compound retailer with no physical storefront may intentionally omit a mailing address for operational/privacy reasons. However, `/contact/` is the one URL in the sample where a dedicated `ContactPage` type and/or `ContactPoint` (e.g., `contactType: "customer service"`, `email`, or a contact form URL) would be directly on-topic and low-risk to add, and its complete absence means the contact page currently carries zero schema value beyond generic WebPage/Breadcrumb.
- Recommendation: Consider changing `/contact/` page schema type from generic `WebPage` to `ContactPage`, and/or adding a `ContactPoint` (with `contactType`, and `email`/`url` to the existing contact form — no telephone/address if Pep Select does not publish one) to the `#organization` node. This is optional and should only be implemented with real, verifiable values — no placeholder data. Description only.
- Dependencies: None.
- Failure check: N/A (opportunity, not a break) — re-check only if Pep Select decides to implement.
- Success check: `/contact/` emits `ContactPage` type and/or a `ContactPoint` with real values validated by Rich Results Test with zero errors.
- Leading indicator: N/A — no rich result exists for ContactPoint alone; this is a trust/entity-clarity improvement, not a SERP feature.

### [SCHEMA-06] Product/Offer missing recommended trust and rich-result properties across all sampled products
- Priority: Medium
- Category: Product / Offer
- Evidence class: [5-Crawler observation/inference]
- Evidence: All 3 sampled Product blocks contain only `name`, `url`, `description`, `image`, `sku`, `brand.name`, and one `Offer` with `price`/`priceCurrency`/`availability`/`hasMerchantReturnPolicy`. None contain `aggregateRating`, `review`, `gtin`/`gtin8`/`gtin12`/`gtin13`/`gtin14`, `mpn`, `priceValidUntil`, `itemCondition`, or `OfferShippingDetails`. Example (BPC-157): `{"@type":"Product","sku":"BPC157-10","offers":[{"@type":"Offer","price":"38.99","priceCurrency":"USD","availability":"https://schema.org/OutOfStock", ...}],"brand":{"@type":"Brand","name":"Pep Select"}}` — no rating/review block anywhere in the 5 JSON-LD blocks sampled site-wide.
- Affected URLs: `/product/bpc157-10/`, `/product/ghk-cu/`, `/product/ss-31/` (pattern applies to all WooCommerce products)
- Reasoning: Google's Product structured data requires only `name` plus one of `offers`/`review`/`aggregateRating` for basic eligibility, which is met. But `aggregateRating`/`review`, `gtin`/`mpn`, `priceValidUntil`, and `itemCondition` are recommended properties Google documents for full Product rich-result and Merchant Listing eligibility (star ratings, price-drop badges, shopping-tab enrichment). Their complete absence across all 3 sampled products suggests either WooCommerce product reviews are disabled/unpopulated site-wide, or the review data isn't being passed into schema even where reviews exist — this should be verified against whether the Product Reviews feature is enabled in WooCommerce settings.
- Recommendation: (1) Confirm whether WooCommerce product reviews are enabled/collected; if so, verify why `aggregateRating`/`review` are not emitted (may need a WooCommerce Schema/Yoast setting checked). (2) If GTIN/MPN identifiers exist for compounds (unlikely for research peptides without retail barcodes — verify before adding), add them; otherwise this may not apply to the catalog. (3) Consider adding `priceValidUntil` to Offers to support price-related rich results. Description only — no markup deployed.
- Dependencies: Reviews decision is a business/compliance question outside this audit's scope (research-peptide RUO context may intentionally avoid consumer-style star ratings) — flag for Paulo's decision, not to be assumed.
- Failure check: Rich Results Test continues to show Product as valid but with only baseline fields; Search Console "Merchant listings" report (if enrolled) shows missing recommended fields.
- Success check: Where applicable and accurate, `aggregateRating`/`review`/`priceValidUntil` appear and validate with zero errors/warnings in Rich Results Test.
- Leading indicator: Search Console Products report "Items with issues/warnings" count for missing recommended fields.

### [SCHEMA-07] Homepage BreadcrumbList has only one ListItem (no real trail)
- Priority: Low
- Category: BreadcrumbList
- Evidence class: [5-Crawler observation/inference]
- Evidence: Homepage `BreadcrumbList`: `"itemListElement": [{"@type": "ListItem", "position": 1, "name": "Home"}]` — single entry, no `item` URL.
- Affected URLs: `https://pepselect.com/`
- Reasoning: This is standard Yoast behavior for a site's front page (there is nothing to show a trail to) and is not a spec violation, but a single-node BreadcrumbList carries no rich-result value on its own. Informational only.
- Recommendation: No action required. Documented for completeness only.
- Dependencies: None.
- Failure check: N/A.
- Success check: N/A.
- Leading indicator: N/A.

### [SCHEMA-08] No FAQPage schema found anywhere sampled, including /faq/
- Priority: Low
- Category: FAQPage (per hard rule: Google retired FAQ rich results for all sites May 7 2026)
- Evidence class: [5-Crawler observation/inference]
- Evidence: `https://pepselect.com/faq/` emits only `BreadcrumbList`, `WebPage`, `WebSite`, `OnlineStore` — no `FAQPage`, `Question`, or `Answer` types detected.
- Affected URLs: `https://pepselect.com/faq/`
- Reasoning: Per current hard rules, FAQ rich results no longer exist for any site as of May 7 2026, so the absence of FAQPage here is not a missed SERP opportunity. Per hard rule, this audit does not recommend adding new FAQPage markup for SERP benefit. If Pep Select is separately curious about AI/GEO citation coverage of the FAQ content, that benefit is unconfirmed and would be an optional decision, not an SEO requirement — and since `/faq/` is static, company-authored FAQ content (not genuine visitor-submitted Q&A), `QAPage` would not be an appropriate substitute either.
- Recommendation: No action required under current Google guidance. If Pep Select later wants to explore unconfirmed AI/GEO markup coverage for this content, that should be a separate, explicitly-scoped decision — not implemented as part of this SEO audit.
- Dependencies: None.
- Failure check: N/A.
- Success check: N/A.
- Leading indicator: N/A — no SERP feature exists to monitor for FAQPage currently.

### [SCHEMA-09] Dataset schema on COA batch pages missing recommended `license` and `datePublished`
- Priority: Medium
- Category: Dataset / DataDownload
- Evidence class: [5-Crawler observation/inference]
- Evidence: Sampled Dataset node contains `dateCreated: "2026-06-25"` but no `datePublished`, and no `license` property anywhere in the block (full Dataset JSON-LD captured for `/testing/retatrutide-30mg/nd_r30_060326/#dataset`).
- Affected URLs: `/testing/retatrutide-30mg/nd_r30_060326/` (pattern applies to all `ps_coa_test-sitemap.xml` leaf pages)
- Reasoning: Google's Dataset structured data guidelines list `license` and `datePublished` among recommended properties for Dataset/Google Dataset Search eligibility. `license` in particular clarifies usage rights for the published lab-report data (PDF + images), which is useful given this data is explicitly `isAccessibleForFree: true` and distributed as a `DataDownload`.
- Recommendation: Consider adding a `license` URL (pointing to Pep Select's own terms/usage statement for the COA data, not a generic open-data license unless one is actually intended) and a `datePublished` value (the date the batch report page went live, distinct from `dateCreated` which appears to be the lab test date) to each Dataset node. Description only.
- Dependencies: None.
- Failure check: Google Dataset Search / Rich Results Test still lists these as missing recommended fields.
- Success check: Dataset validates with `license` and `datePublished` present and accurate.
- Leading indicator: N/A (Dataset Search indexing is not observable via Search Console for this property directly); spot-check via Rich Results Test after change.

### [SCHEMA-10] @context inconsistency between Yoast and WooCommerce JSON-LD emitters
- Priority: Low
- Category: Code quality / spec convention
- Evidence class: [5-Crawler observation/inference]
- Evidence: Yoast blocks use `"@context": "https://schema.org"`; WooCommerce Product blocks use `"@context": "https://schema.org/"` (trailing slash) on the same page.
- Affected URLs: `/product/bpc157-10/`, `/product/ghk-cu/`, `/product/ss-31/`
- Reasoning: Both forms resolve identically for parsers, so this causes no functional error, but it is a documented best-practice deviation (this audit's own rule set: "prefer `https://schema.org` as @context, not a trailing-slash or http variant").
- Recommendation: Normalize the WooCommerce block's `@context` to `https://schema.org` for consistency. This may require a WooCommerce version/setting check since it is core plugin output, not child-theme code (do not edit plugin core files directly). Description only.
- Dependencies: Same remediation batch as SCHEMA-01.
- Failure check: Rich Results Test / validator continues to show mismatched `@context` strings across blocks on the same URL.
- Success check: Both blocks on a given page use identical `@context` string.
- Leading indicator: N/A — cosmetic/consistency fix, no measurable SERP signal.

---

## Verified Correct

- `@context` value used by the primary (Yoast) graph is `https://schema.org` (no `http://`, no trailing slash) on every page sampled — correct per current best practice.
- All URLs used as `@id`, `url`, `item`, `contentUrl`, and `logo`/`image` values across every sampled block are absolute (`https://pepselect.com/...`) — no relative URLs found.
- All dates observed (`datePublished`, `dateModified`, `dateCreated`, sitemap `lastmod`) are in ISO 8601 format (e.g., `2026-08-14T16:45:20+00:00`, `2026-06-25`).
- No deprecated schema types found anywhere sampled: no `HowTo`, no `SpecialAnnouncement`, no `CourseInfo`/`EstimatedSalary`/`LearningVideo`.
- No `FAQPage` markup exists anywhere sampled (see SCHEMA-08) — nothing to flag as a stale deprecated-adjacent block, and no incorrect new FAQPage was found either.
- No legacy Microdata or RDFa detected: `grep` for `itemscope`, `itemtype=`, and `typeof=` returned zero matches on raw HTML for both the homepage and a product page — JSON-LD is the sole structured-data format in use, per best practice.
- `WebSite` + `SearchAction` (Sitelinks Search Box) is correctly implemented site-wide with a valid `EntryPoint` (`urlTemplate: "https://pepselect.com/?s={search_term_string}"`) and `query-input` `PropertyValueSpecification` — present and well-formed on every sampled page.
- `BreadcrumbList` is present and correctly structured (sequential `position`, `name`, and `item` URL on all but the final/current-page item, which correctly omits `item` per Google's guidance) on all non-homepage pages sampled: product pages, shop, testing index, testing compound page, testing COA leaf page, and contact page.
- `MerchantReturnPolicy` (`applicableCountry: "US"`, `returnPolicyCategory: "https://schema.org/MerchantReturnNotPermitted"`, `merchantReturnLink`) is present and consistently attached to both the Organization node and each product's Offer — a coherent, deliberate "no returns" policy consistent with a research-use-only compound business model.
- Dataset/DataDownload architecture is correctly scoped: the compound-level page (`/testing/retatrutide-30mg/`) correctly does NOT carry Dataset markup (it's a listing/`WebPage`), while the individual batch/COA leaf page correctly carries the `Dataset` + `DataDownload` + `DataCatalog` + `variableMeasured` (`PropertyValue`) structure, with `isAccessibleForFree: true`, a real `provider` (testing lab), and a real `distribution.contentUrl` pointing to an actual hosted PDF.
- Organization entity is typed `OnlineStore` (a valid schema.org subtype of `Organization`) rather than plain `Organization` — this is expected, intentional WooCommerce+Yoast SEO integration behavior (not a bug) and remains valid for Google's Organization/logo guidance, which accepts more specific subtypes.
- Product `Offer.availability` values observed (`https://schema.org/OutOfStock` for BPC-157 and SS-31, `https://schema.org/InStock` for GHK-CU) are valid schema.org Enumeration values and appear to accurately reflect real per-product stock state at crawl time.

## Data Sources & Limitations

- All data gathered via read-only GET requests (`claude-seo run render_page.py --mode auto`); no forms submitted, no authenticated/admin views used, no writes made to the live site.
- Structured-data detection used the tool's bounded `--json-ld-output` extraction; page HTML beyond structured data was not fully reviewed except for targeted `grep` checks for microdata/RDFa markers and `<script type="application/ld+json">` counts.
- Sample scope: 9 URLs total (homepage, shop, 3 products, testing index, testing compound page, 1 testing COA leaf page, contact, plus `/faq/` checked only for FAQPage status). The site has at least 9 COA leaf pages (`ps_coa_test-sitemap.xml`) and 15 products (`product-sitemap.xml`); only 1 COA leaf page and 3 products were sampled in full detail. Findings describing "site-wide" or "pattern applies to all" are inferred from template consistency (identical WooCommerce/Yoast plugin output structure across all sampled instances of each page type), not independently verified on every single URL.
- No PageSpeed/CrUX/GSC/DataForSEO data was pulled for this schema-specific audit; all evidence is class 5 (crawler observation/inference). Rich Results Test / Search Console Enhancements reports were not queried live as part of this audit — "failure check"/"success check" items reference how those tools would be used to verify, not confirmed current output from them.
- WooCommerce product review/rating configuration (SCHEMA-06) was inferred from the absence of `aggregateRating`/`review` in JSON-LD only; the WooCommerce admin settings themselves were not inspected (no site access beyond public GET requests).
- Whether Pep Select intentionally omits NAP data (SCHEMA-05) for privacy/compliance reasons in the research-peptide business model was not confirmed; flagged as an opportunity only, not assumed to be an oversight.

## Category Score: 78/100

Core technical schema (WebSite/SearchAction, BreadcrumbList, MerchantReturnPolicy, Dataset/DataDownload architecture) is well-implemented and free of deprecated types, but the score is held back by unmerged/duplicate Organization entities across three separate emitters (SCHEMA-01–03, -10) and missing recommended Product/Dataset trust properties (SCHEMA-04, -06, -09) that reduce rich-result and entity-consolidation eligibility.

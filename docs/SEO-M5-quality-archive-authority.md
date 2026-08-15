# SEO Milestone 5 — Quality Archive Authority and Crawl Consolidation

## Outcome

Help search systems understand that Pep Select publishes batch-specific laboratory records, while removing duplicate and empty sitemap signals. This milestone changes technical search metadata only. It does not rewrite the homepage or other visible landing-page copy.

## Baseline evidence — 2026-08-15

- Google Search Console reports zero web-search clicks and is still processing indexing data, so there is not yet a reliable query dataset to optimize against.
- HTTPS reports 7 valid URLs and 0 non-HTTPS URLs.
- Product snippets, merchant listings, and breadcrumbs each report 0 invalid items.
- All 18 public Quality Archive URLs return indexable pages with exact canonicals and unique titles/descriptions.
- The 9 report pages expose only generic `WebPage` and breadcrumb semantics even though their visible content contains a specific compound, batch, laboratory, report date, and measured results.
- The root sitemap includes an empty standard-post sitemap.
- `/shop/` appears in both the page and product sitemaps.
- All 15 product pages link to the Quality Archive; only the 7 products with a truthful current-batch relationship link directly to a report.

## Scope

### 1. Batch-report evidence semantics — COA plugin owner

- Keep the existing `WebPage` and `BreadcrumbList` graph nodes.
- On completed public report routes, add one connected `Dataset` node describing only information already visible on that report.
- Required dataset fields: unique name and a 50–5000-character factual description.
- Add only public, non-empty values for batch identifier, compound, laboratory, test date, report/PDF distribution, methods, images, and measured results.
- Connect the report page to the dataset with `mainEntity` and connect the dataset back with `mainEntityOfPage`.
- Do not emit a dataset for a progress-only record without completed public laboratory results.
- Do not add medical, therapeutic, safety, certification, review, rating, or unsupported comparative claims.

### 2. Sitemap consolidation — child-theme owner

- Suppress the standard post sitemap only while there are zero published posts.
- Keep `/shop/` in the WooCommerce product sitemap and exclude the duplicate Shop page entry from the page sitemap.
- Preserve every public product, compound-history, and batch-report URL.

### 3. Search and share title system — shared ownership

- Derive a complete title map from competitor evidence and Pep Select's evidence-first position before changing any title.
- Cover the homepage, Shop, each product/strength, Quality Archive, compound-history pages, and individual batch-report pages.
- Keep every title specific to the page's search intent and visible content; avoid repeated keyword strings and generic wording such as `Research Compounds & Batch Documentation`.
- Apply approved titles consistently to the HTML title, Open Graph title, and Twitter title.
- Keep visible homepage and product copy unchanged unless separately approved.
- Keep the current share image unless a dedicated social-image review proves a better crop is needed.

### 4. Competitor intelligence and Pep Select adaptation

- Audit the public search footprint, page architecture, sitemaps, metadata, structured data, internal links, content clusters, product discovery, COA discovery, and mobile performance of Simple Peptide, Orbitrex Peptides, and Licensed Peptides.
- Separate brand/direct/paid traffic from organic SEO so estimated monthly visits are not misreported as Google clicks.
- Record patterns that appear to work across more than one competitor and opportunities where Pep Select can be materially clearer.
- Adapt the strongest patterns to Pep Select's evidence-first position; do not copy source code, visual trade dress, page text, fabricated reviews, or unsupported claims.
- Convert approved opportunities into prioritized implementation work with explicit owners and verification gates.

## Competitor findings

The competitor review covered public HTML, metadata, structured data, internal links, robots files, and XML sitemaps on 2026-08-15. Traffic figures are third-party estimates, not Google Search Console data.

Audited domains: `simplepeptide.com`, `orbitrexpeptide.is`, and `licensedpeptides.com`. The plural domains supplied initially for Simple and Orbitrex were not the active sites.

| Site | What appears to work | What Pep Select should not copy |
|---|---|---|
| Simple Peptide | A large 139-product catalog, 35 product categories, concise category-first titles, and product pages with substantial supporting copy and FAQ markup. Public traffic estimates show strong brand/direct demand; the leading organic queries are variants of the brand name. | Generic COA metadata, an Open Graph homepage title of `Home`, exposed Elementor template URLs, thin editorial depth, or treating estimated total visits as organic clicks. |
| Orbitrex Peptides | Clear traceability language, concise product titles, direct Shop discovery, and a dedicated COA destination. Its homepage leads with third-party testing and evidence rather than a generic company slogan. | A shallow content architecture, repetitive product descriptions, or relying on off-site reputation in place of a durable first-party archive. |
| Licensed Peptides | Search-intent article titles, a large 274-post long-tail library, dense contextual internal linking, and Article/Product/FAQ schema. | Human-use or medical claims, unsupported purity/certification claims, review theater, keyword-stuffed descriptions, funnel URLs in sitemaps, or mismatched Open Graph titles. |

### Conclusions

- The repeatable pattern is clear category language plus an exact page purpose. A cold visitor should see `research peptides`, the compound, the batch, or the lab report before the brand name.
- Simple Peptide's estimated traffic is not evidence of 300,000 monthly Google clicks. Public estimates indicate a mix led by direct/brand demand, with organic search materially smaller.
- Licensed Peptides demonstrates that long-tail educational coverage can build search surface area, but its medical framing and high-volume publishing model do not fit Pep Select's compliance or evidence standards.
- Pep Select's defensible advantage is its own public evidence chain: finished labeled vial, batch identity, independent report, and a stable archive URL. Titles should expose that distinction without claiming every competitor lacks it.

## Approved title architecture

The title system uses category language where it is truthful and switches to `compound` or `lab report` where the page can include NAD+ or another non-peptide research product. The pipe separates the page's search intent from the brand consistently.

| Page type | Title formula | Purpose |
|---|---|---|
| Homepage | `Research Peptides with Batch-Matched Lab Reports | Pep Select` | Names the product category and Pep Select's evidence system in one scan. |
| Shop | `Shop Research Peptides & Compounds | Pep Select` | Matches catalog intent without falsely classifying every item as a peptide. |
| Product | `{Visible product name + strength} for Research | Pep Select` | Keeps each strength distinct and avoids unsupported quality or human-use claims. |
| Quality Archive | `Peptide COA Archive: Search by Compound & Batch | Pep Select` | Uses the industry term buyers search while explaining how the archive is organized. |
| Compound history | `{Compound + strength} COAs & Batch History | Pep Select` | Matches compound-specific evidence intent. |
| Individual report | `{Compound + strength} Batch {batch} Lab Report | Pep Select` | Makes the exact compound, lot, and document type visible in search and link previews. |

The same formula must appear in the HTML title, Open Graph title, and Twitter title. Canonicals, visible headings, URLs, and stored COA/product data remain unchanged.

### Public evidence reviewed

- Simple Peptide homepage, Shop/product pages, COA page, blog, robots file, and sitemap index.
- Orbitrex Peptides homepage, Shop, product pages, COA page, and sitemap.
- Licensed Peptides homepage, Shop/product pages, `How to Read a Peptide COA`, robots file, and sitemap indexes.
- Public SEMrush and Similarweb estimates used only for directional traffic/channel context.
- Google Search Central Dataset structured-data guidance and Yoast's supported XML sitemap filters used for implementation constraints.

### 5. Verification

- Validate the graph as JSON-LD and with Google’s Rich Results Test where supported.
- Confirm the new dataset describes visible page content and has unique `name` and compliant `description` values.
- Confirm every public archive route remains 200, indexable, and canonically self-referencing.
- Confirm the one-off NAD QR redirect still resolves only to `/testing/nad-500-mg/nd50026205jp/`.
- Confirm all 15 product pages retain truthful archive links and the same 7 direct current-report links.
- Confirm the sitemap index no longer lists an empty post sitemap and `/shop/` appears exactly once.
- Confirm every approved title is unique, describes the visible page, appears in HTML/Open Graph/Twitter metadata, and retains the correct canonical URL.
- Recheck cart, checkout, account, product SKUs, and OPS/COA records for non-interference.

## Non-goals

- No landing-page copy changes.
- No product, SKU, price, stock, order, customer, payment, shipping, rewards, VerifyPass, or OPS record changes.
- No COA content edits, route renames, legacy-slug cleanup, or QR changes.
- No claims that structured data guarantees ranking or a rich result.
- No live deployment without a verified staging result and Paulo’s separate approval.

## Ownership boundary

- The child theme owns the sitemap cleanup because it already owns storefront SEO filters.
- The COA plugin owns report semantics because it owns the public report view model, visibility allowlist, routes, and batch records.
- The child theme must not duplicate COA business logic to manufacture report schema.

## Source guidance

- Google Search Central documents `Dataset` markup for pages describing structured data, including images capturing data, and requires `name` plus a 50–5000-character `description`.
- Google’s general guidelines require structured data to represent visible page content and prohibit misleading markup.
- Yoast’s XML Sitemap API provides supported filters for excluding a post type or specific post IDs.

## Acceptance gate

Milestone 5 is ready for live consideration only after both the child-theme sitemap changes and COA-plugin dataset semantics pass staging validation together. A partial package is not a live candidate.

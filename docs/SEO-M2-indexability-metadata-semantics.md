# SEO Milestone 2 — Indexability, Metadata, and Semantic Structure

Status: in progress — baseline crawl complete  
Kickoff date: 2026-08-14  
Deployment boundary: staging first; live requires a separate explicit deployment approval

## Goal

Give every indexable Pep Select URL one clear search identity and one semantic page heading, then provide Google with a clean, stable sitemap and verifiable crawl path. This milestone does not rewrite landing-page body copy.

## Baseline completed

- Google Search Console accepted `https://pepselect.com/sitemap_index.xml` on 2026-08-14 with status Success and 59 discovered pages. The Page indexing report is still processing.
- Search Console has insufficient 90-day field data for both mobile and desktop Core Web Vitals, so laboratory and browser measurements will be used until field data exists.
- The current sitemap index contains six child sitemaps and 44 unique live URLs: 10 pages, 9 compound routes, 9 COA test routes, 16 products, and 1 product category.
- All 44 live sitemap URLs return 200, expose a canonical matching the final URL, have unique titles, avoid `noindex`, and contain no remaining `synthetic` wording.
- No duplicate title or duplicate meta-description groups were found.
- Ten live URLs lack meta descriptions: RUO Disclaimer, Terms & Conditions, Refund & Shipping Policy, Privacy Policy, About Us, Track Your Order, FAQ, Contact, Military Discount, and the Research Compounds product category.
- The live templates currently expose no true H1 across the 44 sitemap URLs.
- Representative staging checks show exactly one H1 on GLP-2T, FAQ, Shop, and the COA archive. This points to a live/staging template-version difference rather than 44 separate content defects.

## Work packages

### 1. Shared semantic-heading release

- Trace the exact child-theme and plugin versions responsible for product, standard-page, archive, and COA headings.
- Verify one truthful H1 per indexable template on staging without changing visual hierarchy or commerce behavior.
- Regression-test desktop/mobile product, Shop, FAQ, legal, contact, tracking, military, compound, and COA routes.
- Package and document only the required shared release; do not promote unrelated staging work.

### 2. Missing search descriptions

- Add factual, compliant Yoast meta descriptions to the ten identified URLs.
- Keep legal descriptions descriptive rather than promotional.
- Do not change visible landing-page copy, policies, product claims, prices, stock, or availability.

### 3. Sitemap and indexing reconciliation

- Re-crawl all child sitemaps after staging changes.
- Reconcile Search Console's current 59 discovered-page count with the 44 unique URLs presently emitted by the sitemap.
- Confirm removed and redirected URLs are absent from the sitemap and resolve with the intended 301 behavior.
- After an approved live deployment, clear caches, verify canonical/title/description/H1/schema output, and allow Search Console time to refresh.

## Acceptance criteria

- Every intended indexable sitemap URL returns 200 and has one self-referencing canonical.
- Every intended indexable page has one meaningful H1 in rendered HTML.
- Every sitemap URL has a unique, factual title and meta description appropriate to its template.
- No indexable sitemap URL contains `noindex`, stale `www` canonicals, stale GLP-2T 30mg metadata, or `synthetic` wording.
- Product, checkout, payments, inventory, orders, rewards, VerifyPass, OPS, and COA records remain unchanged.
- Staging regression checks pass before any live deployment request.

## Preserved boundaries

- No landing-page body copy rewrite.
- No pricing, inventory, SKU, customer, order, checkout, payment, shipping, rewards, VerifyPass, or OPS business-logic changes.
- No COA batch identity, result, status, or product relationship changes.
- COA Archive 0.6.4 remains staging-only until separately approved for live deployment.

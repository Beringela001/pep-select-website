# Pep Select SEO Remediation Roadmap

**Created:** 2026-08-18  
**Audit baseline:** Claude SEO 2.2.4 integrated audit, 57/100, 97 findings  
**Working rule:** Claude SEO findings remain the source recommendations. Codex validates ownership, dependencies, existing milestones, and safe implementation before making changes.

## Outcome

Increase qualified organic discovery by fixing real technical defects first, making the existing catalog and COA archive easier to crawl and understand, improving mobile delivery, and then publishing evidence-led content that answers demonstrated search demand without introducing human-use or unsupported claims.

## Non-negotiable boundaries

- No production deployment without Paulo's explicit approval.
- No invented reviews, COAs, test results, GTINs, MPNs, staff credentials, addresses, certifications, or scientific claims.
- No change to the research gate's legal requirements without compliance approval.
- No medical, dosing, reconstitution, administration, or personal-use content.
- Existing WooCommerce, checkout, payment, shipping, rewards, VerifyPass, COA, and order logic must remain intact.
- Every release must have a rollback package and post-deployment checks.

## Work sequence

### Step 0 — Establish the tracker and measurement baseline

**Findings covered:** all 97; especially GOOG-01/02/03/09/11, DFS-01/03/10.

1. Register every audit finding once, preserving its original priority and recommendation.
2. Consolidate duplicate symptoms under shared root-cause work packages without deleting any finding.
3. Record current GSC indexation, impressions, clicks, queries, and inspected URLs.
4. Record current PageSpeed results for home, Shop, one product, and Testing.
5. Record DataForSEO baseline positions and backlink metrics; do not rerun paid calls unless the expected cost is approved.
6. Add GA4 organic-session and conversion measurement only after confirming the correct property and consent setup.

**Exit check:** Every finding has an owner, status, dependency, acceptance test, and rollback route. Baseline screenshots/exports are dated.

### Step 1 — Remove production defects and credibility leaks

**Findings covered:** CONT-01, CONT-02, CONT-06, TECH-05, GOOG-10, CONT-10/12/13.

1. Permanently redirect `/terms-of-service/` to `/terms-conditions/`.
2. Correct the separate research-gate source so it links directly to `/terms-conditions/`.
3. Replace every visible `[VERIFY DOI]` marker only after matching the citation to a primary publication record. Where the citation itself is wrong, correct the full citation instead of attaching a DOI to the wrong paper.
4. Make verified citations machine-usable links.
5. Normalize the case-variant URL with a single canonical redirect.
6. Replace repeated generic link labels where the destination is not clear from surrounding context.
7. Remove mechanically duplicated modal text where it does not need to be repeated in the DOM.

**Current progress:** The narrow 301 fallback for `/terms-of-service/` has been added locally in `pepselect-child/inc/seo-catalog.php`. The gate owner was identified as the PS Access Gate plugin; its latest 2.1.1 package was imported as local source and updated to 2.1.2 so both defaults and previously saved legal text use `/terms-conditions/` without overwriting the surrounding wording. All five production `[VERIFY DOI]` entries were checked against publication records and corrected locally; one required correcting its journal/year, and another required correcting its publication year as well as adding the DOI.

**Exit check:** Old Terms URL returns one 301 and a final 200; a fresh crawl finds no live `[VERIFY DOI]`; every changed DOI resolves to the intended publication; no legal or checkout regression.

### Step 2 — Decide and repair the research-gate experience

**Findings covered:** VIS-01/02/03/04/05, SXO-01/08, part of PERF-01 and GOOG-04/05/06.

1. Confirm the identified PS Access Gate 2.1.1 package matches the active production plugin byte-for-byte or by version/source inspection.
2. Export its current settings before any staging or production edit.
3. Separate legal requirements from discretionary segmentation fields.
4. With compliance approval, test the lightest gate that still satisfies the required attestation.
5. Add focus trapping, initial focus, Escape behavior if permitted, background `inert`/`aria-hidden`, keyboard order, and restored focus.
6. Replace the off-site Google exit with an approved informational destination or neutral close/exit behavior.
7. Retain a visible path to the full attestation and legal terms.

**Approval gate:** Paulo and compliance/legal must approve any change to what visitors must attest to. The audit itself says the gate is a human UX issue, not a Google crawl-blocking penalty.

**Exit check:** Keyboard and screen-reader test passes; first-time visitor flow is documented; legal fields remain approved; Search Console rendering still exposes the underlying page; conversion/drop-off measurement is active.

### Step 3 — Fix mobile performance at the shared-source level

**Findings covered:** PERF-01/02/03/04/05/06/07/08/09/10/11, GOOG-04/05/06/07, TECH-06.

1. Re-run PageSpeed after the gate decision so the test measures the intended experience.
2. Confirm the actual LCP element on each template rather than assuming all four share one cause.
3. Optimize the homepage hero and gate/header logo at their real rendered dimensions and use modern formats where supported.
4. Preserve the already-completed 768px hero request behavior unless new measurements disprove it.
5. Remove or defer non-critical CSS and template-specific assets.
6. Audit the 36–48 script requests by owner; unload assets on templates that do not use them.
7. Isolate GTM tags by business value before removing or delaying them.
8. Correct the Testing page's oversized font request and visual-progress gap.
9. Add dimensions/aspect ratios to the remaining unsized images.

**Exit check:** Four-template mobile lab retest; no visual or checkout regression; LCP trends toward ≤2.5s, CLS ≤0.1, and INP/TBT risk materially reduced. Keep the currently strong TTFB, caching, and compression unchanged.

### Step 4 — Strengthen crawl paths and indexation signals

**Findings covered:** GOOG-01/02/03, MAP-01/02/03, ECOM-04, SXO-06, TECH-05/07, GEO-09.

1. Add direct Testing/COA-to-product links only where the compound/batch mapping is certain.
2. Confirm every indexable product is reachable from Shop or another indexable hub.
3. Stop unbounded `/shop/page/N/` duplicate pages with a correct out-of-range response or redirect policy.
4. Submit/inspect Shop, Testing, and representative product URLs in GSC after deployment.
5. Request indexing only after the pages and internal links are stable.
6. Treat IndexNow and AI-crawler policy as optional policy choices, not prerequisites for Google indexing.

**Exit check:** No intended product is orphaned; invalid pagination no longer returns duplicate 200 pages; GSC URL Inspection shows discoverable/indexable pages and a valid sitemap relationship.

### Step 5 — Consolidate schema and entity identity

**Findings covered:** SCHEMA-01/02/03/04/05/06/07/08/09/10, GEO-02/04/07, ECOM-06/08.

1. Map every current JSON-LD emitter: Yoast, WooCommerce, child theme, and COA plugin.
2. Connect Offer seller and Dataset creator to the one canonical Pep Select organization `@id`.
3. Remove duplicate/disconnected entities only after verifying rich-result output remains valid.
4. Add supported organization contact/address/social properties only when Paulo supplies and approves public values.
5. Add `datePublished` and an approved license to batch data only if the underlying records support them.
6. Evaluate ItemList/OfferCatalog on Shop.
7. Do not invent GTIN, MPN, reviews, shipping details, or `priceValidUntil`. Add only data backed by WooCommerce/business records.
8. Do not add FAQ schema merely to chase a retired rich result; use it only if it truthfully represents visible FAQ content and still adds machine-readability value.

**Exit check:** Schema validator and Google rich-result tests show zero new errors; Product, Offer, Organization, Dataset, and DataDownload entities resolve to coherent IDs; product snippets/merchant listings stay valid.

### Step 6 — Build evidence-first landing and guide content

**Findings covered:** CONT-03/04/05/06/07/08/11/14, GEO-03/06/08, SXO-02/03/04/05/07, ECOM-03/07.

1. Replace the GLP-2 supplier boilerplate with verified Pep Select-specific product information.
2. Expand unique product content with compound identity, documented specifications, research context, and verified citations—not dosing, reconstitution, outcomes, or filler.
3. Reduce repeated sitewide disclaimer copy to one consistent approved presentation where legally possible.
4. Create a trust/vendor-evaluation page focused on documentation, traceability, status communication, and how to review batch records.
5. Create a research-documentation/evidence hub using already verified citations.
6. Create compliant handling/storage and USA availability/legal-information pages only after legal review of the scope.
7. Use the demonstrated PAA/query set as an editorial backlog, but reject questions that cannot be answered inside Pep Select's RUO guardrails.
8. Improve Shop trust density using actual current status data; never imply every product has a COA.
9. Add a genuine wholesale/contact path only if the business supports it.

**Required review order for publishable copy:** Product Marketing → Copywriting → compliance/evidence review → CRO → Copy Editing → Stop Slop → Paulo approval → implementation.

**Exit check:** Each page has a defined query/intent, unique purpose, evidence register, approved claims, internal links, metadata, and measurable CTA. No `[VERIFY CLAIM]` reaches production.

### Step 7 — Add legitimate trust and commerce signals

**Findings covered:** ECOM-01/02/05/09, CONT-09, SCHEMA-04/05/06, DFS-07.

1. Choose a real post-purchase review collection process; do not seed reviews.
2. Add review schema only when visible, eligible reviews exist and the platform outputs them correctly.
3. Treat missing COA pages as an operational/testing dependency. Never create a page that implies testing occurred when it did not.
4. For out-of-stock products, choose approved substitutes only when they are genuinely appropriate; otherwise surface restock notification and relevant available categories without equivalence claims.
5. Publish NAP, staff, contact, and social identity only when Paulo approves the exact public data.
6. Confirm restricted-product eligibility before investing in Merchant Center/free listings.

**Exit check:** Every trust signal maps to a real record; no false aggregate rating; COA status agrees across product, archive, schema, and PDF; OOS paths are useful and accurate.

### Step 8 — Build authority without spam

**Findings covered:** DFS-02/03/04/05/06/08/09/10, GEO-05, plus content assets from Step 6.

1. Cleanly document the existing backlink profile and suspicious domains; do not buy links or mass-disavow without evidence.
2. Create link-worthy assets around batch traceability, testing-history methodology, and evidence review.
3. Pursue relevant laboratory, supplier, association, and editorial citations with manual quality control.
4. Earn mentions to the Pep Select entity and specific useful resources, not only the homepage.
5. Track referring domains, branded searches, non-branded impressions, AI Overview citations, and assisted conversions monthly.

**Exit check:** Growth comes from relevant referring domains and qualified impressions; spam score does not worsen; outreach claims exactly match public evidence.

## Release pattern for every implementation batch

1. Confirm source owner and current state.
2. Back up/export the exact surface being changed.
3. Make the smallest local or staging change.
4. Run targeted syntax/static tests.
5. Run the Claude finding's original failure and success checks.
6. Perform desktop/mobile and checkout/business-flow regression checks appropriate to the change.
7. Present Paulo with the change, evidence, known risks, and rollback instructions.
8. Deploy only after explicit approval.
9. Purge relevant caches and re-check live behavior.
10. Update the finding ledger and monitor leading indicators.

## Deployment milestones

Small changes stay grouped on staging until they form one coherent, reversible milestone. Live promotion happens after that milestone passes its own checks and Paulo explicitly approves it; we do not deploy after every tiny edit, and we do not wait for all 97 findings to be finished.

| Milestone | Scope | Current state | Live gate |
|---|---|---|---|
| M1 — Trust and crawl integrity | Terms redirect/link, verified citations, gate accessibility, COA-to-product links | Deployed and verified on Live in child theme `0.25.0-beta.21`, PS Access Gate `2.1.2`, and COA Archive `0.7.1`. A human assistive-technology walkthrough remains recorded as a limitation. | Live gate passed on 2026-08-18: named backup, targeted storefront regression, and Paulo approval. |
| M2 — Mobile performance | Evidence images, homepage hero, unused/render-blocking assets, four-template PageSpeed | Implemented and deployed on Live in theme `0.25.0-beta.21`: responsive WebP hero, optimized evidence images, and the first safe head-asset cleanup pass. Live route, commerce, and 390 px overflow checks pass. | Implementation was promoted with Paulo's approval while Google quota was exhausted. Four PageSpeed reruns remain required before claiming an improved score or closing the milestone. |
| M3 — Indexation and schema | Crawl paths, pagination, GSC inspection, connected Product/Offer/Organization/Dataset schema | Staging implementation and regression complete in child theme `0.25.0-beta.25` and COA Archive `0.7.2`. Invalid Shop pagination is fixed; Offer seller and Dataset creator share the existing organization ID; Dataset publication date is present; both schema emitters use the same context. GSC outcome checks remain pending. | Live promotion requires Paulo approval and a named Live backup; GSC URL Inspection/recrawl remains the outcome gate after deployment. |
| M4 — Evidence-led content and GEO | Product copy, trust/evidence hubs, compliant query coverage | Not started. | Evidence/compliance/copy review and Paulo approval. |
| M5 — Authority and operations | Reviews, COA coverage, entity signals, legitimate links, ongoing measurement | Not started. | Operational records exist and every public signal is verifiable. |

## First three implementation batches

### Batch A — Broken trust surfaces

- Legacy Terms 301 fallback is deployed and verified on Live: one 301 to `/terms-conditions/`, then 200.
- Research-gate Terms link is corrected at its actual plugin source and PS Access Gate `2.1.2` remains active on Live.
- All five `[VERIFY DOI]` citations are corrected and verified on the four affected Live product pages; the placeholder is absent. Linking all DOI/PMID strings remains tracked under `CONT-06`.
- Live crawl and storefront checks pass for this batch.

### Batch B — Catalog ↔ Testing crawl loop

- Verify current product-to-COA mapping. **Local source check complete:** the COA plugin already stores an exact WooCommerce product ID and only exposes a URL for a published product.
- Add exact reverse links from compound archives/batch records to products. **Deployed and verified on Live in COA Archive 0.7.1:** eight matched archive cards and compound-history heroes render the existing published-product URL conditionally; desktop and mobile checks pass.
- Resolve the one reported orphan product.
- Validate sitemap, canonicals, and GSC inspection.

### Batch C — Gate/accessibility/performance decision

- Export gate source/settings. **Source package identified. Live is protected by the named Kinsta backup `Before Claude SEO remediation milestones 1-2 live deployment - 2026-08-18`.**
- Produce a compliance-preserving revised interaction for approval.
- Implement accessibility corrections. **PS Access Gate 2.1.2 is active on staging without changing the required fields or attestation. In a clean visitor session, the dialog has correct accessible naming, initial focus, modal isolation, scroll lock, and forward/reverse focus-wrap boundaries. The automation surface cannot perform a complete real screen-reader walkthrough, so that limitation remains explicit rather than being claimed as a full assistive-technology pass.**
- Re-run four-template PageSpeed and then optimize confirmed LCP/render blockers. **Implementation and regression pass are deployed on Live in beta.21:** the 390 px hero transfer is 93.7% smaller than the prior PNG candidate, the 1024 px comparison is 92.5% smaller, head styles are reduced by 5–6 per template, and blocking head scripts are reduced from 7–8 to 4. Mobile Home, Shop, Product, Testing, Cart, Account, and Checkout checks pass. Google PageSpeed remeasurement is waiting on the daily API quota reset, so no improved score is claimed and M2 remains measurement-pending.

## Decisions Paulo must make before affected work can ship

- Whether the gate's researcher-type dropdown must remain mandatory.
- Which public business address, phone, staff/reviewer identity, and social profiles may be published.
- Whether Pep Select wants a real review-request workflow.
- Which out-of-stock products may show which alternatives.
- Whether a wholesale/bulk inquiry path is actually offered.
- Whether Merchant Center participation is permitted for this catalog.
- Final approval for each staging-to-live deployment.

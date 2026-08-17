# Pep Select SEO and Google Ads Handoff

Prepared: 2026-08-17  
Repository: `C:\Users\paulo\Documents\Pep Select Website`  
SEO release verified live: `pepselect-child 0.25.0-beta.4` and `pepselect-coa-archive 0.7.0`  
SEO release commit: `1463baf` (`Close SEO milestone 5 live release`)  
Google Ads status: campaign created; ads disapproved; no useful traffic or spend recorded

## 1. Executive status

SEO Milestones 1 through 5 are complete and were deployed to Live. The work corrected product identity and strength data, consolidated duplicate catalog routes, repaired titles/canonicals/social metadata, added truthful Product/Offer/store policy schema, improved homepage loading, strengthened the public Quality Archive, added batch-report Dataset semantics, and cleaned the XML sitemap.

The paid-search track did not reach an approved ad. Google rejected both the original homepage campaign and a narrower, documentation-only NAD+ batch-record ad. The rejection named destination content containing BPC-157, GHK-CU, MOTS-C, TB-500, and Tesamorelin, and cited **Unapproved substances**, **Restricted drug terms in personalized advertising**, and a certification requirement. This means the blocker is not simply weak ad copy. Google is reviewing the destination and the broader navigable website.

The evidence available at handoff does **not** show an SEO traffic lift yet. At the Milestone 5 baseline, Search Console was still processing indexing data and reported zero web-search clicks. It also does not show paid-ad performance: the ads were not eligible to run, so there were no meaningful impressions, clicks, conversions, or spend to optimize.

## 2. Safety and ownership boundaries preserved

All SEO releases were designed around these boundaries:

- WooCommerce remains the source of truth for products, SKUs, prices, stock, cart, checkout, orders, customers, shipping, and payments.
- The COA plugin remains the source of truth for batch records, report visibility, compound history, report routes, PDFs, laboratory data, and Dataset markup.
- OPS, rewards, VerifyPass, account, order tracking, and the printed NAD QR workflow were preserved.
- No fake reviews, ratings, GTINs, MPNs, delivery times, purity claims, certifications, or price-validity dates were introduced.
- The homepage was not broadly rewritten. The only substantial approved visible addition was the educational “Match the batch. Match the vial.” section.
- Staging was used for meaningful SEO releases before Live promotion. Named backups and live smoke checks were recorded in the milestone documents.

## 3. SEO Milestone 1 — catalog and COA integrity

Source: `docs/SEO-M1-catalog-coa-integrity.md`  
Status: complete, staged, backed up, deployed, and verified

### Work completed

- Corrected GLP-2T product ID `543` to the confirmed 20 mg identity across OPS, Staging, and Live:
  - SKU `GLP2T20`
  - slug `glp2-t20`
  - strength tag `20mg`
  - existing `TZ20F` product image and accurate alt text
- Corrected Glutathione from the stale `10mg` tag to `600mg`; retained SKU `GLUTA600`.
- Verified the 16-product catalog against the connected systems.
- Did not invent current-batch COAs for products without a tested release.
- Kept failed batches publicly visible as **Not Released**, while excluding them from “Current” and product-level current-report displays.
- Migrated the Retatrutide archive route from the numeric legacy route to `/testing/retatrutide-10mg/` with exact permanent redirects.
- Preserved the one-off printed NAD vial redirect:
  - `/testing/nad-500-mg/progress-1269/`
  - redirects to `/testing/nad-500-mg/nd50026205jp/`
- Verified the testing sitemap and factual batch metadata/schema.

### Logic

SEO could not be trusted until the visible catalog, SKU/strength identity, OPS mappings, COA relationships, and public routes agreed. This milestone fixed the data layer first instead of optimizing incorrect pages.

### Release evidence

- COA plugin package: `pepselect-coa-archive-0.6.4.zip`
- SHA-256: `4A4F24A834F30010123A7F8B1CD5168C2327FCF389DC1FC0428B7A7F2E5F0D44`
- Commit: `e609d9a`

## 4. SEO Milestone 2 — indexability, metadata, and semantics

Source: `docs/SEO-M2-indexability-metadata-semantics.md`  
Status: complete and verified live on 2026-08-14

### Work completed

- Crawled the exact sitemap body: 45 unique URLs at that point.
- Verified 44 of 45 URLs already had one H1.
- Promoted the existing About hero from H2 to H1 without rewriting it.
- Added factual meta descriptions to key policy/support pages and the Research Compounds category.
- Removed the word `synthetic` from approved Retatrutide and TB-500 descriptions without changing product identity or commerce data.
- Established unique titles, descriptions, self-canonicals, and the non-`www` canonical host.
- Confirmed `www.pepselect.com` permanently redirects to `pepselect.com`, eliminating the host split that could affect indexing and customer sessions.
- Confirmed Staging remained noindex.
- Submitted `https://pepselect.com/sitemap_index.xml` in Search Console; it was accepted as **Success** and displayed 59 discovered pages at that historical reporting point.

### Final About-page state

The About page was later intentionally removed from the footer, marked `noindex, follow`, and excluded from Yoast XML sitemaps. The page was not deleted or rewritten. This final state supersedes Milestone 3’s temporary crawlable-footer link.

### Verification result

- 45/45 sitemap URLs returned 200.
- Every crawled URL had one H1, title, meta description, and self-canonical.
- Zero duplicate titles or descriptions were found in that release crawl.
- No stale `www` canonicals, `synthetic` wording, or stale 30 mg GLP-2T metadata remained.

### Release evidence

- Child theme: `0.20.1-beta.2`
- SHA-256: `B5A75F3CCD89672B8D4F7386453B5D5A272F85306B1DEBF294E806DE25C6B2E1`

## 5. SEO Milestone 3 — catalog consolidation, product schema, and discovery

Source: `docs/SEO-M3-catalog-schema-internal-discovery.md`  
Status: complete and verified live on 2026-08-14

### Work completed

- Made `/shop/` the primary catalog surface.
- Permanently redirected `/product-category/research-compounds/` to `/shop/`, preserving safe campaign, sorting, and filtering parameters.
- Removed the redirected category from Yoast and removed the now-empty product-category sitemap.
- Replaced generic or empty Product descriptions with approved, visible, product-specific descriptions.
- Distinguished multiple strengths of the same compound using visible strength information.
- Left Bacteriostatic Water without a manufactured description where no approved source existed.
- Added truthful `Brand: Pep Select` markup.
- Repaired stale Product and Open Graph URLs after slug changes.
- Removed WooCommerce’s unsupported assumed `priceValidUntil` values.
- Added a single `OnlineStore` graph entity using the verified organization, logo, and policy URL.
- Aligned HTML, Open Graph, and Twitter metadata.
- Forced the global Compounds navigation destination to Shop.

### Logic

Two competing catalog URLs split crawl signals and confused users. The solution was one canonical shop, strength-specific metadata, and Product schema derived only from visible WooCommerce truth.

### Verification result

- 44 intended URLs passed the release crawl.
- Fifteen products passed Product/Offer checks.
- Fourteen unique product descriptions were present; the one unsupported case stayed empty rather than being fabricated.
- No invented ratings, reviews, `priceValidUntil`, or null schema values were emitted.
- Product layouts were tested at 390, 768, and 1440 px.
- A broader root crawl exposed 14 WordPress utility/default routes for later deliberate treatment; they were not silently redirected or noindexed.

### Release evidence

- Child theme: `0.21.0-beta.6` for the principal release; the About cleanup followed in `0.21.0-beta.7`.
- Principal package SHA-256: `8E06B980F88792F85DB7384816750D93DFD823D8A62C92132F9C4D0895827433`

## 6. SEO Milestone 4 — Search Console, merchant eligibility, and performance

Source: `docs/SEO-M4-search-console-merchant-performance.md`  
Status: complete and verified on Staging and Live on 2026-08-15

### Work completed

- Added one stable US `MerchantReturnPolicy` entity matching the published no-returns-after-shipment policy.
- Referenced that one policy from every WooCommerce Product `Offer`.
- Retained Brand and SKU on all 15 product sitemap URLs.
- Deliberately omitted shipping schema because Easyship prices and carrier transit times are dynamic and could not be represented as a truthful fixed promise.
- Deliberately omitted reviews, aggregate ratings, GTIN/MPN, and `validFrom` when no authentic source existed.
- Fixed the homepage hero so the browser requests the 768 px derivative instead of starting a roughly 3.4 MB original plus the derivative.
- Confirmed the post-release homepage requests only `PS-laying_fam-768x434.png` for that hero family.
- Preserved commerce, shipping calculations, product data, OPS, and COA relationships.

### Search Console findings

Google had reported non-critical enhancement suggestions on the GLP-3 R product page:

- Product snippets: missing `review` and `aggregateRating`.
- Merchant listings: missing return policy, shipping details, `validFrom`, and a global identifier.

Both reports showed 0 invalid and 1 valid item. The release added only the return policy that could be supported truthfully. Brand was already present by the time the older Search Console crawl was reviewed. No fake values were added merely to silence warnings.

### Representative performance evidence

The pre-release desktop homepage transferred about 4,381 KB because of the duplicate hero request. The isolated asset correction removed that defect. Representative mobile checks already showed zero TBT and low measured LCP on the tested pages; the change therefore stayed narrow rather than introducing speculative site-wide optimization.

### Release evidence

- Child theme: `0.23.0-beta.1`
- SHA-256: `B2E7816372EA40310F01DA860AE404098BCB15B621EE0FF134F5EE9F303A3C60`
- Named Staging and Live backups were created before deployment.
- Smoke checks passed for Home, Shop, GLP-3 R, Cart, My Account, Quality Archive, NAD batch, and the printed-QR redirect.

## 7. SEO Milestone 5 — Quality Archive authority and crawl consolidation

Source: `docs/SEO-M5-quality-archive-authority.md`  
Status: final; deployed and verified live on 2026-08-15

### Work completed

- Added a connected `Dataset` node to completed public batch-report pages.
- Dataset values come only from public, visible report facts: compound, strength, batch, laboratory, test/report date, public PDF, methods, images, and measured results.
- Connected the page and Dataset with `mainEntity` / `mainEntityOfPage`.
- Did not emit Dataset markup for progress-only records without completed results.
- Suppressed the standard post sitemap only while no eligible published post exists; it returns automatically when real indexable posts exist.
- Removed duplicate `/shop/` inclusion from the page sitemap while retaining Shop in the WooCommerce product sitemap.
- Preserved every public product, compound-history, and batch-report route.
- Applied a page-purpose title system consistently to HTML, Open Graph, and Twitter metadata.

### Final title architecture

| Page | Final formula/example |
|---|---|
| Home | `Research Peptides with Batch-Matched Lab Reports | Pep Select` |
| Shop | `Shop Research Peptides & Compounds | Pep Select` |
| Product | `{Product name + strength} for Research | Pep Select` |
| Quality Archive | `Peptide COA Archive: Search by Compound & Batch | Pep Select` |
| Compound history | `{Compound + strength} COAs & Batch History | Pep Select` |
| Individual report | `{Compound + strength} Batch {batch} Lab Report | Pep Select` |

### Competitor research and adaptation

The public sites audited were the active domains `simplepeptide.com`, `orbitrexpeptide.is`, and `licensedpeptides.com`.

Patterns adapted:

- Put the search intent or exact page purpose before the brand.
- Make Shop and COA/archive discovery obvious.
- Use compound- and strength-specific titles.
- Build long-tail educational coverage only where claims can be supported.
- Use Pep Select’s labeled-vial, batch, report, and permanent archive relationship as the differentiator.

Patterns rejected:

- Copying competitor source code, visual trade dress, or wording.
- Medical or human-use claims.
- Unsupported purity/certification claims or review theater.
- Keyword stuffing, repetitive generic descriptions, exposed template URLs, or mismatched social titles.
- Treating Similarweb or Semrush estimated visits as verified Google clicks. The “300,000 clicks” figure for Simple Peptide was not proven as 300,000 monthly Google organic clicks; the available estimates indicated a meaningful direct/brand component.

### Search baseline at completion

- Search Console still showed 0 web-search clicks and was processing indexing data.
- HTTPS: 7 valid URLs and 0 non-HTTPS URLs.
- Product snippets, merchant listings, and breadcrumbs: 0 invalid items.
- Eighteen public Quality Archive URLs were indexable, self-canonical, and carried unique titles/descriptions.
- Fifteen product pages linked to the archive; seven products with truthful current-batch relationships linked directly to a report.

### Release evidence

- Child theme: `0.25.0-beta.4`
- Child theme ZIP SHA-256: `E375B2F8203939AE7055194BC19309BEB05E013BF4F1421F61FCE22832041DD3`
- COA plugin: `0.7.0`
- COA plugin ZIP SHA-256: `459C35FE4703EF6F5C8BB78EBC3356646A72025EEF646160B8320BF12553EEEF`

## 8. Homepage trust education — “Match the batch. Match the vial.”

This was a separately approved visible homepage change developed alongside the later SEO releases.

### Final implementation

- Replaced the generic trust block with the phrase:
  - `Match the batch.`
  - `Match the vial.`
- The final example uses the documented Tesamorelin 10 mg batch `PSTES1071926GX` and its matching Freedom Diagnostics report.
- A high-resolution clear-vial front/reverse asset shows:
  - blue cap and silver crimp;
  - compound name and 10 mg strength;
  - the printed batch identifier.
- The independent report preview is a static image, not a deceptive link.
- The sole action opens the main Quality Archive rather than promoting one product’s test.
- Callout endpoints were corrected so:
  - cap/crimp points to the hardware;
  - compound/strength points to `10 mg`;
  - batch points to the printed lot rather than the QR code.
- The mobile strength callout was lowered so it no longer covers the 10 mg label.
- Mobile and desktop versions preserve the same lesson without horizontal overflow.

### Purpose

The section translates Pep Select’s actual operating difference into a fast visual explanation: the customer can compare the finished labeled vial, its physical identifiers, its batch number, and the lab’s published record. It addresses industry uncertainty with evidence rather than generic “trust us” language.

### Current code/assets

- `pepselect-child/template-parts/home/why-pep-select.php`
- `pepselect-child/assets/css/homepage.css`
- `pepselect-child/assets/images/why-pep-select/tesamorelin-10mg-vial-batch.webp`
- `pepselect-child/assets/images/why-pep-select/tesamorelin-10mg-coa-source.webp`

## 9. Current sitemap, canonical, and redirect model

- Canonical host: `https://pepselect.com` without `www`.
- `www` requests permanently redirect to the non-`www` host.
- Yoast owns XML generation; the child theme applies bounded exclusions.
- `/shop/` is the one primary catalog URL.
- `/product-category/research-compounds/` permanently redirects to `/shop/`.
- The empty product-category sitemap is excluded.
- The post sitemap is excluded only when there is no eligible published post.
- Shop appears once, in the WooCommerce product sitemap rather than again in the page sitemap.
- About Us is `noindex, follow`, removed from the footer, and excluded from the sitemap.
- Public product, compound-history, and completed batch-report routes remain discoverable.
- The legacy NAD printed-QR redirect remains intentionally narrow and must be preserved.

## 10. Google Ads account and campaign created

Account: Pep Select  
Google Ads customer ID: `896-149-9074`  
Campaign: `PS Search | Research Compounds | $500 Test`  
Campaign ID: `24149327992`  
Ad group ID: `199372811236`  
Budget: `$16.45/day`, approximately `$500/month`

The advertiser/business information supplied during setup identified the company as **PS Research Solutions LLC**, a laboratory-research seller with no professional license. Payment setup and signup were confirmed by Paulo.

### Intended campaign logic

The campaign was designed as a small Search test, not a broad display campaign:

- Reach people actively typing research-peptide and COA-related queries into Google.
- Keep the test budget bounded near $500/month.
- Start with exact and phrase match to control irrelevant traffic.
- Use Pep Select’s batch documentation and Quality Archive as the differentiator.
- Measure qualified site visits and eventual conversions before expanding.

### Keywords created

- `[peptides]`
- `[research peptides]`
- `"research peptides"`
- `"peptides for research"`
- `"buy research peptides"`
- `[peptide coa]`
- `"research peptides online"`
- `"peptide coa"`
- `[pep select]`
- `[pepselect]`
- `"pep select"`

The keywords/campaign could display as Eligible/Learning while the ad itself was Not eligible. Keyword eligibility did not mean the ad had been approved.

## 11. Google Ads creative tests and outcomes

### Test 1 — homepage/search campaign

The original responsive search ad pointed to `https://pepselect.com/` and used research-peptide, batch-record, and COA language, including concepts such as:

- Shop Research Peptides
- Match the Batch. Match the Vial.
- See the Vial Behind the COA
- Independent laboratory and batch documentation

Google disapproved the ad for **Unapproved substances** after evaluating the destination.

### Certification/review attempt

A certification/review path was started using the supplied business information. Pep Select does not hold the professional license Google’s healthcare certification flow may require. No completed approval or support case number is recorded in the available evidence.

Google’s notification center showed no pending reply, and the ad remained disapproved.

### Test 2 — narrow documentation-only NAD record

After reviewing a third-party peptide-ad guide and Google’s official policy, a narrower ad was built to remove the obvious product-sales framing:

- Final URL: `https://pepselect.com/testing/nad-500-mg/nd50026205jp/`
- Display path: `pepselect.com/coa/nad-batch`
- No sitelinks to Shop, Archive, FAQ, Contact, or restricted product pages.
- Focused on a public batch record, labeled vial, independent report, and research-use-only framing.
- Removed unsupported claims such as GMP certification, “99.9% pure,” “FDA compliant,” purity promises, and unverified shipping promises.

Headlines used:

1. Pep Select Research Records
2. Research Use Only Peptides
3. Match the Batch. Match Vial.
4. Review Batch-Specific COAs
5. Independent Lab Reports
6. See the Vial Behind the COA
7. For Laboratory Research Only
8. No Therapeutic Claims
9. Published Batch Records
10. Clear Research Documentation
11. Pep Select Official Site
12. View NAD+ Laboratory Record
13. Batch Records You Can Check

Descriptions used:

1. `Review batch-specific COAs and independent laboratory reports for research use only.`
2. `See the labeled vial, batch number and available test results in one published record.`
3. `Research use only. Not for human consumption. No therapeutic or medical claims.`
4. `Pep Select connects the vial, batch identifier and available laboratory documentation.`

The ad reached an Ad Strength of **Average**, was submitted after Google reauthentication, and was disapproved immediately.

### Google’s explicit destination findings

The Google policy email dated 2026-08-16 identified:

- BPC-157
- GHK-CU
- MOTS-C / MOTs-C
- TB-500
- TESAMORELIN / Tesamorelin

Policies named:

- Restricted drug terms in personalized advertising
- Unapproved substances
- Certificate required

### Measured paid result

- Approved ads: 0
- Meaningful impressions: 0 recorded
- Meaningful clicks: 0 recorded
- Conversions: 0
- Spend: no meaningful spend recorded

## 12. Google Ads conclusion and decision logic

The second test is decisive enough to change strategy. A neutral, research-use-only, COA-specific ad with no sales sitelinks was still rejected immediately. That indicates the domain/destination inventory is the principal restriction, not just the wording of the responsive ad.

Google’s official policy states that unapproved substances cannot be promoted, regardless of claims about legality. Google may review the ad, final URL, and broader navigable destination. Editing the final URL or destination can trigger another review.

Therefore:

- Renaming Tesamorelin or other products to codes such as `PS3-RT` to hide the substance is not an acceptable fix.
- Removing restricted pages temporarily, obtaining approval, and restoring them is not an acceptable fix.
- Sending reviewers to a clean bridge page that later changes into a product funnel is not an acceptable fix.
- Those actions can be treated as circumvention or bait-and-switch behavior and create account-suspension risk.
- Competitor visibility is not proof that their ads are compliant, approved under the same account/category, or using cloaking. No evidence was obtained that Simple Peptide or Orbitrex was cloaking, and that accusation should not be made.
- More cosmetic ad-copy variations are unlikely to solve this destination-level rejection.

Recommended paid-search state:

1. Pause the campaign so the $500 budget is protected, even though disapproved ads cannot currently spend.
2. Preserve the existing campaign and rejection history for a legitimate manual review.
3. Continue certification/support only if Google identifies a valid eligibility route for this exact business and inventory.
4. Do not repeatedly resubmit near-identical ads; repeated violations can elevate account risk.
5. Use Google Ads later only for a genuinely separate, permanently eligible service that Pep Select really offers—not a temporary bridge to restricted products.

Primary policy reference: `https://support.google.com/adspolicy/answer/15595718?hl=en`

## 13. What the results do and do not prove

### Proven

- The technical SEO foundation is materially cleaner and internally consistent.
- Product, catalog, and COA identities are aligned.
- Canonical host, catalog route, titles, descriptions, social metadata, and sitemap signals are consolidated.
- Product/Offer/OnlineStore/return-policy schema is grounded in real data.
- Completed COA records now expose batch-specific Dataset semantics.
- The homepage teaches the batch-to-vial evidence system clearly on desktop and mobile.
- Google Ads rejected the domain/destination even when the test was narrowed to a documentation-only record.

### Not yet proven

- An increase in Google organic rankings, impressions, clicks, revenue, or conversions.
- That the current title system has outperformed alternatives; Search Console did not yet have sufficient data.
- That competitor traffic estimates represent Google organic clicks.
- That any competitor is cloaking or evading policy.
- That Pep Select qualifies for Google healthcare/pharmaceutical certification.
- That another ad-copy rewrite alone can become eligible.

## 14. Recommended next work

### Immediate

- Confirm the Google Ads campaign is paused; do not rely solely on disapproval to protect the budget.
- Keep one screenshot/PDF of each policy notice and record any certification/support case number.
- Do not change Live inventory or product naming for ad-review evasion.
- Allow Google to recrawl the completed SEO release before interpreting Search Console warnings.

### Next 30–60 days

- Monitor Search Console weekly for:
  - indexed pages and exclusions;
  - query impressions and clicks;
  - homepage, Shop, products, compound histories, and report pages;
  - Product, merchant, breadcrumb, HTTPS, and Core Web Vitals reports.
- Compare query impressions by page type before changing titles again.
- Build a small, high-quality educational content cluster around laboratory documentation, how to read COAs, batch identity, testing methods, and research documentation—without medical/human-use claims.
- Link educational pages naturally to the Quality Archive, compounds, and relevant public batch records.
- Keep the public archive current as new batches are tested; do not break old permanent report URLs.
- Measure organic conversions separately from brand/direct traffic.

### Before any future paid campaign

- Obtain a written eligibility answer or certification decision from Google for the exact business model and destination.
- Use conversion tracking that is tested end to end before spending.
- Add negative keywords and tight location/device controls.
- Start with a genuinely eligible offer and landing page that will remain unchanged after review.
- Treat the initial $500 as a controlled experiment, not a guarantee of sales.

## 15. Verification checklist for the next operator

### Live SEO

- Confirm installed child theme is `0.25.0-beta.4` or document any later release.
- Confirm installed COA plugin is `0.7.0` or document any later release.
- Check Home, Shop, one product, Quality Archive, one compound page, and one completed report at desktop and mobile sizes.
- Confirm `www` permanently redirects to non-`www`.
- Confirm `/product-category/research-compounds/` permanently redirects to `/shop/`.
- Confirm the printed NAD QR legacy route still lands on `nd50026205jp`.
- Confirm About is absent from the footer, `noindex, follow`, and absent from XML sitemaps.
- Confirm the post sitemap is absent only while no eligible article exists.
- Confirm Shop appears once across the sitemap index.
- Validate Product/Offer/Brand/return-policy references and one completed report Dataset.
- Verify no restricted business data, SKU, price, stock, order, or COA record changed during any SEO release.

### Search Console

- Record current total clicks, impressions, average CTR, and average position with an exact date range.
- Record current Page indexing, HTTPS, Product snippet, Merchant listing, Breadcrumb, and Core Web Vitals counts.
- Do not compare fresh current data with the historical 59-discovered-page number without accounting for sitemap consolidation and reporting lag.

### Google Ads

- Open campaign ID `24149327992` and confirm campaign status, ad status, and spend.
- Open Policy Manager and capture the full current destination/policy details.
- Check whether certification/support produced a case ID or written decision.
- Keep the campaign paused unless an ad is legitimately approved and the landing page remains compliant.
- Do not appeal until the stated policy issue has genuinely been resolved or Google support confirms eligibility.

## 16. Authoritative repository references

- `docs/SEO-M1-catalog-coa-integrity.md`
- `docs/SEO-M2-indexability-metadata-semantics.md`
- `docs/SEO-M3-catalog-schema-internal-discovery.md`
- `docs/SEO-M4-search-console-merchant-performance.md`
- `docs/SEO-M5-quality-archive-authority.md`
- `pepselect-child/inc/seo-catalog.php`
- `pepselect-child/inc/seo-semantics.php`
- `pepselect-child/template-parts/home/why-pep-select.php`
- `pepselect-child/CHANGELOG.md`
- `HANDOFF.md`

## 17. Bottom line

The SEO foundation is finished through Milestone 5 and live. Its value is structural and evidence-based, but it needs time and Search Console data before anyone can honestly claim ranking or revenue gains.

Google Ads is not waiting for optimization; it is blocked by policy. The honest documentation-only experiment was still disapproved immediately. Preserve the account, protect the budget, avoid circumvention, and put near-term effort into organic search and the Quality Archive while seeking a definitive Google eligibility decision.

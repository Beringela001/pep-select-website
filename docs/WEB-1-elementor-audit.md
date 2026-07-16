# WEB-1 Elementor Export Audit

Audit date: July 16, 2026  
Checkpoint: WEB-1 — Full Website Audit and Rebuild Architecture  
Scope: Audit only; no WordPress, staging, dependency, template, or implementation changes

## 1. Scope, evidence, and limits

This report audits all 12 JSON files in `site-exports/elementor/` against the four existing WEB-0 documents in `docs/` and the Pep Select design, copy, responsive, WordPress, WooCommerce, and delivery rules.

Evidence used:

- Elementor export metadata (`title`, `type`, `version`, `page_settings`, widget trees, settings, dynamic tags, links, inline HTML/CSS, and responsive overrides)
- Cross-template references such as Loop Grid template IDs `65` and `485`
- WEB-0 environment, inventory, architecture, and foundation decisions
- The repository's Pep Select brand, copy, responsive, design-system, and WordPress/WooCommerce standards

Important limitations:

- An Elementor JSON export does not include Theme Builder display conditions, publication status, menu assignments, plugin activation state, database-held ACF values, media-library alt text, shortcode registrations, or rendered output. “Active candidate” below means the template appears intended for current use or is referenced by another export; it does not prove that WordPress currently assigns it.
- A shortcode name can prove that a provider is required, but not which plugin or codebase registered it. Provider ownership must be traced on staging before replacement.
- Responsive findings are based on stored Elementor settings, not screenshots or rendered browser behavior.
- Claims and testimonials are flagged for verification. The exports do not contain evidence proving them.

All exports parse successfully and identify Elementor export schema version `0.4`.

## 2. Template inventory and classification

### `footer-elementor-70.json`

- **Export title/type:** `Elementor Footer #391`; `footer`.
- **Purpose:** Global footer with logo, research-use and FDA disclaimers, support address, navigation/legal link lists, and copyright/developer credit.
- **Apparent status:** Active candidate. The JSON cannot confirm its Theme Builder assignment. The mismatch between filename ID `70` and export title `#391` should be resolved in WordPress.
- **Dependencies:** Elementor containers, Image, Text Editor, Icon List, and Heading widgets. It contains ElementsKit metadata fields but no ElementsKit widget in the tree.
- **URL evidence:** 12 hard-coded `https://pepselect.kinsta.cloud/` links/assets, covering the logo, shop, about, FAQ, contact, COAs, account orders, military discount, and policy pages. It also hard-codes `https://serviceslash.com/` in the developer credit.
- **Copy/content concerns:** “Track your order” links to `/my-account/orders/`, which is an account-order list rather than evidence of a public tracking experience. “Certificate of Analysis” is singular while the target is `/coas/`. `PepSelect` is styled as one word in the copyright line, while other copy uses both `PepSelect` and `Pep Select`.
- **Responsive evidence:** Seven mobile-specific settings and no tablet-specific overrides. Six containers and three link columns therefore require rendered tablet review; the JSON alone cannot prove wrapping, accordion behavior, link density, or legal-copy readability.
- **Replacement risk:** High. A footer replacement can break policy paths, support information, compliance copy, account/order navigation, and any approved legal language. Preserve exact destinations and validate the legal content before replacing presentation.
- **Staging inspection required:** Confirm active assignment, all links, current legal text, mobile/tablet layout, developer-credit intent, and whether order tracking should remain an account link.

### `header-elementor-73.json`

- **Export title/type:** `Elementor Header #1323`; `header`.
- **Purpose:** Announcement bar, logo, desktop/mobile search, rewards balance, account access, side cart, and primary navigation.
- **Apparent status:** Active candidate. The filename/title ID mismatch (`73` versus `1323`) and missing display conditions require WordPress confirmation.
- **Dependencies:** Elementor Search widgets; ElementsKit `ekit-nav-menu`; Elementor dynamic Site URL tag; YITH Points and Rewards shortcode `[yith_ywpar_points label="" show_worth="no"]`; Side Cart WooCommerce/Xootix shortcode `[xoo_wsc_cart]`; raw HTML/SVG for account and rewards links; Loop template `65` as the search-result template.
- **URL evidence:** Hard-coded live-domain links to `/my-account/` and `/my-account/cash-back/`, plus a hard-coded live upload URL for the logo. Only the logo destination uses Elementor's dynamic Site URL tag.
- **Copy/content concerns:** “Free 2-day shipping on cart subtotals of $200” is a specific commercial promise that must be checked against current shipping rules. Default search copy includes “Type to start searching...” and the generic failure text “It seems we can’t find what you’re looking for.” The raw account link contains an empty `<span>` and an `aria-hidden` SVG, so the export does not show an accessible text name for that link.
- **Responsive evidence:** One search is hidden on mobile; the alternate search is hidden on desktop and tablet. The header stores 19 mobile and 12 tablet overrides. The ElementsKit menu uses separate mobile/tablet positioning and toggle settings.
- **Replacement risk:** Critical. This template couples global navigation to search results, rewards, account access, cart behavior, and ElementsKit. Removing it before tracing all providers can strand customer actions or suppress dynamic balances/cart state.
- **Staging inspection required:** Confirm active display conditions; logged-in/logged-out states; zero/nonzero rewards; empty/populated cart; desktop, tablet, mobile menu/search behavior; keyboard focus; and the truth of the shipping announcement.

### `loop-item-coa-elementor-485.json`

- **Export title/type:** `COA loop`; `loop-item`.
- **Purpose:** COA search/result card showing featured image, dynamic post title, and a “View Lab Report” link.
- **Apparent status:** Referenced candidate. Homepage template `571` explicitly uses template ID `485` for a search restricted to post type `coa`. Its preview is `single/coa` with preview post `481`.
- **Dependencies:** Elementor Theme Post Featured Image, Theme Post Title, Button, dynamic Post Title/Post URL tags, and the custom `coa` post type.
- **Copy/content concerns:** The stored fallback title is Elementor's placeholder “Add Your Heading Text Here.” It could surface if the dynamic tag fails. Typography is Georgia for the title and IBM Plex Mono for the button.
- **Responsive evidence:** No tablet- or mobile-specific overrides are stored.
- **Replacement risk:** High. Template `485` is part of the COA lookup chain; changing it can break result recognition or report access even if the search widget remains.
- **Staging inspection required:** Confirm actual result data, no-results/loading/error behavior, long batch/title wrapping, missing featured images, link destination, keyboard use, and mobile card sizing.

### `loop-item-dark-elementor-65.json`

- **Export title/type:** `Dark Loop`; `loop-item`.
- **Purpose:** Product card for archive, homepage, search, and related-product grids.
- **Apparent status:** Heavily referenced candidate. Template ID `65` is referenced by header searches, product archive `441`, homepage `571`, and both single-product exports `279` and `462`.
- **Dependencies:** Elementor dynamic post/product tags, WooCommerce Product Price, product featured image/title/tag data, Button, and custom shortcode `[product_stock_status]` whose provider is not identified by the JSON.
- **URL evidence:** Hard-coded live upload fallback `https://pepselect.kinsta.cloud/wp-content/uploads/2026/06/placeholder.png`.
- **Copy/content concerns:** Generic CTA “Learn more” is less precise than the approved Pep Select action language. The label “From” is populated dynamically from WooCommerce product tags, so its meaning depends on tag governance. The stock shortcode's output states are not present in the export.
- **Responsive evidence:** 23 mobile overrides but only one tablet override. It uses multiple negative top margins (`-10`, `-15`, `-20`) and a `-35px` mobile button margin, plus an overflow-hidden image region. These settings create overlap/clipping risk at long names, large text, and narrow widths.
- **Replacement risk:** Critical. It is a shared dependency for at least five exported experiences. Replacing or deleting it can simultaneously affect site search, catalog, homepage products, and related products.
- **Staging inspection required:** Confirm all referencing contexts; sale/regular price; in-stock/out-of-stock/backorder output; missing image; long tag/title; touch target; focus; card height consistency; 320–1024px behavior; and shortcode ownership.

### `products-archive-elementor-441.json`

- **Export title/type:** `Elementor Products Archive #441`; `product-archive`.
- **Purpose:** Product catalog/archive hero, compound search, and 20-item Loop Grid.
- **Apparent status:** Active candidate; assignment is not provable from the export.
- **Dependencies:** Elementor Pro Product Archive/Theme Builder context, Search widget, Loop Grid, and loop template `65`.
- **Copy/content concerns:** “Selected Research Compounds” and the supporting sentence include broad “vetted” and “consistency” language that requires evidence. The compound-search submit label is “View COA,” which does not match the stated task “Search Compounds.” Default no-results copy is generic.
- **Responsive evidence:** Ten mobile and three tablet overrides; one negative `-8px` heading margin. No rendered evidence confirms the 20-item grid's column behavior or filter/search usability.
- **Replacement risk:** Critical. It controls product discovery and depends on shared loop `65`; replacement must preserve WooCommerce archive context, pagination/load-more behavior, product links, prices, and stock states.
- **Staging inspection required:** Confirm active archive assignment, search semantics, result relevance, no-results behavior, pagination/load more, card columns at all target widths, and WooCommerce ordering/filter interactions outside the export.

### `saved-page-bq-about-409.json`

- **Export title/type:** `bq about`; `page`.
- **Purpose:** BioQuantum Sciences about/company page.
- **Apparent status:** Obsolete/inherited historical evidence. WEB-0 explicitly restricts template `409` from use as a Pep Select visual, structural, or copy foundation.
- **Brand-remnant evidence:** “About BioQuantum Sciences”; multiple BioQuantum Sciences references; “Formerly operating as AmeriQuantum Sciences”; Texas supplier positioning; claims including “>99 Purity on all peptides,” “same day shipping before 3pm CST,” “14 day money back guarantee,” and specific testing/compliance statements.
- **Dependencies:** Elementor; plugin-specific `deensimc-smooth-text` widget (provider not identifiable from the JSON); raw HTML/SVG/style blocks; Montserrat typography.
- **URL evidence:** Two Pep Select live upload URLs and one Hostinger asset URL: `https://seagreen-stork-640674.hostingersite.com/wp-content/uploads/2026/06/Blank-ALLbanner-2.png`.
- **Responsive evidence:** 128 mobile and 124 tablet overrides; the hero supporting heading is hidden on tablet and mobile, and the tablet background uses the Hostinger asset.
- **Style evidence:** Six custom-CSS entries and four embedded `<style>` blocks. The same `.badge-check-wrapper` block is repeated across this and the BioQuantum contact export.
- **Replacement risk:** Low to current Pep Select behavior if truly unassigned, but high contamination risk if imported or copied. It contains unrelated identity, claims, assets, purple gradient treatment, and plugin-specific effects.
- **Preservation decision:** Preserve the JSON only as historical/recovery evidence. Do not reuse its copy, layout, claims, visual system, or Hostinger assets.
- **Staging inspection required:** Confirm it is not assigned to a live page, navigation item, reusable section, or Theme Builder condition before archival decisions.

### `saved-page-bq-contact-413.json`

- **Export title/type:** `bq contact`; `page`.
- **Purpose:** BioQuantum Sciences contact/inquiry page and form.
- **Apparent status:** Obsolete/inherited historical evidence. WEB-0 explicitly restricts template `413` from becoming a Pep Select foundation.
- **Brand-remnant evidence:** BioQuantum banner asset naming, Fort Worth/Texas location, and `support@bqsciences.com`, `research@bqsciences.com`, and `wholesale@bqsciences.com`.
- **Dependencies:** Elementor Pro Form widget; Elementor; raw HTML/SVG/style blocks; Montserrat. The form is still named the generic `New Form` and uses `[all-fields]` email content; recipient/action configuration is not explicit in the export.
- **URL evidence:** Two Pep Select live upload URLs and one Hostinger mobile image URL: `https://seagreen-stork-640674.hostingersite.com/wp-content/uploads/2026/06/ChatGPTImageMay25-3.webp`.
- **Copy/content concerns:** Generic form success/error/server/invalid messages remain. “All inquiries receive a response within one business day” is an operational promise requiring verification.
- **Responsive evidence:** 58 mobile and 47 tablet overrides. One hero is hidden on mobile/tablet and an alternate section is hidden on desktop, creating parallel desktop/mobile presentation paths that can drift.
- **Replacement risk:** Low to current Pep Select behavior if unassigned, but high privacy/delivery risk if the form remains connected; messages could route to an unrelated brand or expose obsolete contact details.
- **Preservation decision:** Preserve only as historical/recovery evidence. Do not reuse the form, contacts, copy, or visual structure.
- **Staging inspection required:** Confirm the page is unassigned/unpublished, inspect form actions and recipients without submitting real data, and verify no menu or internal links expose it.

### `saved-page-pepdivas-home-77.json`

- **Export title/type:** `pepdivas home`; `page`.
- **Purpose:** Peptides Divas luxury-positioned homepage.
- **Apparent status:** Obsolete/inherited historical evidence. WEB-0 explicitly restricts template `77` from use as the Pep Select foundation.
- **Brand-remnant evidence:** Five textual “Peptides Divas” occurrences; hard-coded `https://peptidesdivas.com/shop/` and `/about-us/` links; “quiet luxury,” “premium research compounds,” “boutique,” “luxury hand,” and “Trusted by quiet professionals” positioning.
- **Dependencies:** Elementor; ElementsKit Accordion; Elementor Loop Grid using missing/unexported template ID `15`; raw HTML/SVG/style blocks; Playfair Display, Inter, and Plus Jakarta Sans.
- **Copy/content concerns:** Anonymous testimonial-style quotes, same-day processing claim, “Each batch is positioned with independent Certificate of Analysis documentation and purity details,” and multiple premium/luxury claims require evidence and are inappropriate as Pep Select copy. One inline style contains malformed `border-radius:1 4px`; repeated border declarations also appear.
- **Responsive evidence:** 84 mobile and 51 tablet overrides, with ten negative-margin settings. Rendered behavior is unknown.
- **Style evidence:** Ten custom-CSS entries and four embedded style blocks. Several effects and structures are repeated in the BioQuantum and Pep Select homepage exports.
- **Replacement risk:** Low to current Pep Select behavior if unassigned, but critical contamination risk. It also depends on loop template `15`, which is absent from the supplied exports.
- **Preservation decision:** Preserve only as historical/recovery evidence. Do not reuse its copy, testimonials, visual language, loop dependency, or external links.
- **Staging inspection required:** Confirm it is not published/linked/embedded and determine whether template `15` exists or is used elsewhere before any cleanup decision.

### `saved-page-pepselect-homepage-571.json`

- **Export title/type:** `pepselect_homepage`; `page`.
- **Purpose:** Pep Select homepage candidate with hero, value statements, featured products, about/process sections, COA lookup, testimonials, FAQ, and catalog CTA.
- **Apparent status:** Current active candidate, but WEB-0 requires inherited/recycled elements to be audited before retention. JSON cannot confirm the assigned WordPress front page.
- **Dependencies:** Elementor; ElementsKit Accordion; plugin-specific `deensimc-smooth-text` widget; Loop Grid using template `65`; Search restricted to custom post type `coa` using result template `485`; raw HTML/SVG/style blocks.
- **URL evidence:** Six hard-coded Pep Select live-domain occurrences, including `/shop/`, `/coas/`, and two upload assets. No staging URL is present.
- **Inherited/copy evidence:** Two testimonials and attributions are exact repeats from the Peptides Divas export: “Consistently reliable service and quality presentation.” / “Research Buyer, Melbourne,” and “One of the most professional peptide suppliers we've worked with.” / “Laboratory Operations Manager.” The testimonial section follows the same three-card structure. This is direct copied/inherited content, not Pep Select-specific evidence.
- **Other copy concerns:** “We Vet Every Release,” “Every release passes through four stages,” “Only then is a compound listed,” “Trusted by Researchers,” “A Standard Worth Trusting,” and “The result is a cleaner, more trustworthy research experience designed around confidence...” are factual or broad reassurance claims that need documented support. “Enter COAs” is an unclear field prompt for a batch lookup. “Order links are provided following inquiry confirmation” may not match the current WooCommerce purchase flow. The FAQ answer “Research use only.” is overly terse.
- **Responsive evidence:** 197 mobile and 178 tablet overrides. Entire top-level sections `content[10]` and `content[11]`—the “Trusted / Words From The Lab” heading and testimonial cards—are marked hidden on desktop, tablet, and mobile. They remain stored but cannot render through normal responsive visibility. Numerous `-8px` heading margins are repeated.
- **Style evidence:** 20 custom-CSS entries and four embedded style blocks. It mixes Georgia, IBM Plex Mono, Inter, and Plus Jakarta Sans, while also carrying a gold `.text-gradient` rule shared with single-product templates. Repeated card gradients and inline icon styles indicate copy/paste rather than centralized tokens.
- **Replacement risk:** Critical. This page connects to product loop `65` and COA loop `485`; a broad overwrite could break both discovery paths. It also contains unverified claims and inherited content that should not survive by default.
- **Staging inspection required:** Confirm front-page assignment; compare hidden versus visible sections; verify every claim and testimonial; test product/COA searches and all states; inspect actual responsive layout; and trace `deensimc-smooth-text` ownership before deciding what, if anything, is retained.

### `single-post-elementor-510.json`

- **Export title/type:** `Elementor Single Post #510`; `single-post`.
- **Purpose:** Single COA record page with dynamic title and a rendered COA table.
- **Apparent status:** COA single-template candidate. Preview context is `single/coa` with preview post `481`; display conditions are absent.
- **Dependencies:** Elementor dynamic Post Title and custom shortcode `[coa_table]`. The shortcode provider is not identified by the export; the active-plugin inventory includes Pep Select COA Archive and should be checked rather than assumed.
- **Copy/content concerns:** Stored heading is “Certificate of analysis” but is replaced dynamically by the post title. No loading, empty, failed, previous, or unavailable COA states are visible in the JSON.
- **Responsive evidence:** Ten mobile and nine tablet overrides; one `-8px` title margin.
- **Replacement risk:** Critical. This is a direct COA presentation path. Deleting or replacing it before tracing `[coa_table]`, the `coa` post type, and status semantics could hide or misstate laboratory records.
- **Staging inspection required:** Confirm Theme Builder condition, current/past/failed/pending records, long titles, table responsiveness, missing data, report links, shortcode provider, and keyboard/screen-reader behavior.

### `single-product-elementor-279.json`

- **Export title/type:** `Elementor Single Product #279`; `product`.
- **Purpose:** Custom single-product presentation centered on batch metadata, purity, scanned COA, an inquiry/order-link flow, accordion details, recent batches, and related products.
- **Apparent status:** Duplicate/legacy candidate competing with template `462`. Active assignment cannot be determined from JSON.
- **Dependencies:** Elementor and Elementor Pro; WooCommerce product image/title/short description/content and related-products query; ElementsKit Accordion; ACF dynamic fields `purity`, `current_batch_no`, `scan_coa`, `batch_information`, and `coa_access`; shortcode `[recent_batches]`; Loop template `65`.
- **Copy/content concerns:** Stored fallbacks include “Add Your Heading Text Here,” “List Item #3,” and three instances of the “Far far away, behind the word mountains...” demo paragraph. The “Request Order Link” button has no explicit destination in the export. “View Latest COA” points to `#`. Claims about review and “quality, documentation, and consistency” require evidence. These fallbacks can leak if dynamic data is absent or broken.
- **Responsive evidence:** 27 mobile and 12 tablet overrides, including negative margins (`-15px`) and several shared section offsets.
- **Style evidence:** Six custom-CSS entries; shared gold gradient and background-gradient rules also occur in template `462` and homepage `571`.
- **Replacement risk:** Critical. Even if legacy, it references canonical product data, ACF fields, batch/COA providers, related-products loop `65`, and an alternate ordering model. Deleting it without checking conditions can remove product access or historical batch information.
- **Staging inspection required:** Confirm assignment and whether products use an inquiry link or normal cart; verify all ACF fields and missing-value fallbacks; trace `[recent_batches]`; test COA access, related products, and every WooCommerce state.

### `single-product-elementor-462.json`

- **Export title/type:** `Elementor Single Product #462`; `product`.
- **Purpose:** More WooCommerce-native single-product presentation with breadcrumb, images, title, price, short description, add to cart, product tabs, latest COA action, COA carousel, and related products.
- **Apparent status:** Duplicate/current candidate competing with template `279`. Its higher ID and more native commerce widgets do not prove active assignment.
- **Dependencies:** Elementor and Elementor Pro; WooCommerce Breadcrumb, Product Images, Title, Price, Short Description, Add to Cart, Product Data Tabs, and related-products query; ACF URL field `view_latest_co`; shortcode `[pepselect_product_coa_carousel]`; Loop template `65`.
- **Copy/content concerns:** The dynamic product title retains “Add Your Heading Text Here” as a fallback. “Each PepSelect release is reviewed...” and the related-product consistency statement require evidence. Capitalization is inconsistent (`view latest COA`).
- **Responsive evidence:** 18 mobile and 11 tablet overrides. It uses multiple negative desktop margins (`-20` to `-25px`) and a `-30px` mobile container margin, creating collision/reading-order risk.
- **Style evidence:** Four custom-CSS entries, including the same gold text gradient and section backgrounds used in template `279` and homepage `571`.
- **Replacement risk:** Critical. It directly controls add-to-cart and product information while depending on ACF, COA rendering, and shared product loop `65`. Visual work must not alter price, stock, variations, purchase validation, or product data.
- **Staging inspection required:** Confirm Theme Builder condition and product coverage; test simple/variable/out-of-stock products, quantity, cart/side-cart interaction, tabs, COA missing/present states, ACF URL behavior, carousel ownership, related products, and all target widths.

## 3. Cross-template dependency map

### Elementor and Elementor Pro

- All 12 exports require Elementor's container/widget schema.
- Theme Builder template types are present for header, footer, product archive, product, single post, and loop items.
- Pro-level capabilities visible in the exports include Loop Grid, Search, Form, WooCommerce widgets, Theme Post widgets/dynamic tags, ACF dynamic tags, and per-widget Custom CSS.
- Theme Builder display conditions and actual assignments are not exported and must be checked on staging.

### ElementsKit

- `header-elementor-73.json`: `ekit-nav-menu`.
- `saved-page-pepdivas-home-77.json`, `saved-page-pepselect-homepage-571.json`, and `single-product-elementor-279.json`: `elementskit-accordion`.
- Many exports also contain ElementsKit cursor/badge metadata even where no ElementsKit widget appears. Metadata alone should not be treated as proof that a template functionally requires the plugin; direct widgets do.

### ACF

- `single-product-elementor-279.json` dynamically reads ACF fields for purity, current batch number, scanned COA image, batch information, and COA access.
- `single-product-elementor-462.json` dynamically reads an ACF URL for the latest COA.
- Field keys are embedded in dynamic tags. Renaming/removing field groups or changing return types can break the rendered template even when Elementor remains intact.

### WooCommerce

- `loop-item-dark-elementor-65.json` depends on WooCommerce price/title/tag data.
- `single-product-elementor-279.json` uses WooCommerce product media/title/short description/content and related-products querying, but no native Add to Cart widget is visible.
- `single-product-elementor-462.json` uses the more complete native purchase surface: breadcrumb, media, title, price, short description, add to cart, tabs, and related products.
- `products-archive-elementor-441.json` depends on product-archive context and loop `65`.
- No visual replacement is safe until prices, stock, variations, add-to-cart behavior, and archive/product assignments are verified.

### COA system

- Custom post type `coa` is referenced by loop `485`, homepage search, and single-post preview context.
- `[coa_table]` appears in single-post `510`.
- `[pepselect_product_coa_carousel]` appears in single-product `462`.
- `[recent_batches]` appears in single-product `279`.
- The active-plugin inventory includes Pep Select COA Archive, but the JSON does not prove which shortcode(s) it registers. Ownership, data sources, status handling, and fallback behavior require code/plugin inspection and staging output.

### Rewards

- The header requires YITH Points and Rewards through `[yith_ywpar_points ...]` and hard-codes `/my-account/cash-back/`.
- No other exported template visibly renders rewards data.

### Cart

- The header requires `[xoo_wsc_cart]`, consistent with the active Side Cart WooCommerce system.
- Single-product `462` supplies native Add to Cart; the handoff from that widget to side-cart behavior must be tested.
- Single-product `279` shows an alternate “Request Order Link” flow and may represent a competing/obsolete commerce model.

### Order and shipment tracking

- No YITH Order and Shipment Tracking widget or shortcode is present in the 12 exports.
- The footer's “Track your order” link goes to `/my-account/orders/`.
- Therefore the exports provide no evidence of the public order-tracking implementation or YITH tracking presentation. This is an audit gap to inspect on staging, not evidence that tracking is absent from the site.

### Other plugin-specific behavior

- `deensimc-smooth-text` appears in BioQuantum About `409` and Pep Select homepage `571`. Its provider/version is not identifiable from the JSON.
- `[product_stock_status]` appears in loop `65`; provider unknown.
- The BioQuantum contact page uses Elementor Pro Form, but its recipient and submission actions are not explicit in the export.

## 4. Hard-coded and obsolete URL audit

### Hard-coded production-domain URLs

The exports contain hard-coded `https://pepselect.kinsta.cloud/` URLs rather than environment-neutral/dynamic links:

- Footer `70`: 12 occurrences.
- Header `73`: 3 occurrences.
- Dark loop `65`: 1 fallback-image occurrence.
- BioQuantum About `409`: 2 upload occurrences.
- BioQuantum Contact `413`: 2 upload occurrences.
- Peptides Divas Home `77`: 2 upload occurrences.
- Pep Select Homepage `571`: 6 occurrences.
- Single Product `279`: 2 occurrences.
- Single Product `462`: 1 occurrence.

This creates portability risk between live, staging, and any future canonical domain. The header demonstrates the safer alternative only for the logo destination: Elementor's dynamic Site URL tag.

### Hostinger URLs

- BioQuantum About `409`: tablet hero image on `seagreen-stork-640674.hostingersite.com`.
- BioQuantum Contact `413`: mobile image on the same Hostinger hostname.

These are inherited external-asset dependencies and should be preserved only inside the historical exports unless staging proves an active page still depends on them.

### Peptides Divas URLs

- Peptides Divas Home `77` hard-codes `https://peptidesdivas.com/shop/` and `https://peptidesdivas.com/about-us/` across four link occurrences.

### BioQuantum references

- No BioQuantum website URL is present, but About `409` contains BioQuantum/AmeriQuantum brand copy and Contact `413` contains three `@bqsciences.com` addresses.

### Staging URLs

- No occurrence of `stg-pepselect-staging.kinsta.cloud` was found in the 12 exports.
- The absence of a staging hostname does not make the links environment-neutral; most internal destinations are pinned to the live Kinsta hostname.

### Other external/obsolete candidates

- Footer `70` links to `https://serviceslash.com/` in its developer credit. Whether this is still approved cannot be determined from JSON.
- Single Product `279` uses `#` for “View Latest COA,” a placeholder rather than a usable destination.

## 5. Copy audit

### Broken or placeholder copy

- “Add Your Heading Text Here”: COA loop `485` and both single-product templates as stored dynamic fallbacks.
- “List Item #3”: Single Product `279` batch list fallback.
- “Far far away, behind the word mountains...”: three stored accordion fallbacks in Single Product `279`.
- `#` destination: “View Latest COA” in Single Product `279`.
- Empty explicit destination: “Request Order Link” in Single Product `279`.
- Generic Elementor form name `New Form` and generic form result/error messages: BioQuantum Contact `413`.
- Generic search no-results message “It seems we can’t find what you’re looking for.” appears in header, archive, and product grids.

### Copied/inherited copy

- Pep Select Homepage `571` repeats two testimonial quotes and attributions exactly from Peptides Divas Home `77`.
- Pep Select Homepage `571`, Peptides Divas Home `77`, and the BioQuantum pages share repeated card structures, inline SVG/style patterns, gradients, and generic documentation/quality language.
- The direct copy evidence means homepage `571` cannot be accepted as wholly original Pep Select content without a section-by-section decision.

### Claims requiring evidence or approval

- Header `73`: free two-day shipping at a $200 subtotal.
- BioQuantum About `409`: purity, testing, same-day shipping, money-back, climate-control, compliance, and batch-traceability claims.
- BioQuantum Contact `413`: one-business-day response promise.
- Peptides Divas Home `77`: premium/luxury positioning, testimonials, documentation and same-day processing claims.
- Pep Select Homepage `571`: vetting every release, four-stage release process, trust/reliability statements, “Trusted by Researchers,” anonymous testimonials, and inquiry-based order-link behavior.
- Products Archive `441` and both single-product templates: broad vetting/consistency/review statements.

The JSON provides no supporting records for these claims. They should remain unapproved until matched to operational and COA evidence.

## 6. Responsive and accessibility concerns visible in JSON

- **Header:** separate desktop and mobile search instances plus ElementsKit navigation can create duplicate focus targets or inconsistent query behavior if visibility CSS fails. The raw account link has no visible/accessible text in the export.
- **Footer:** no tablet-specific overrides despite multi-column content and long legal text.
- **COA loop `485`:** no stored tablet/mobile overrides.
- **Product loop `65`:** heavy negative spacing, including `-35px` on mobile, plus overflow clipping.
- **Archive `441`:** limited tablet-specific tuning compared with mobile; 20-item output must be checked across columns and container widths.
- **BioQuantum About/Contact:** device-specific hidden sections and external Hostinger mobile/tablet images create divergent responsive paths.
- **Pep Select Homepage `571`:** two whole testimonial-related sections are hidden at every device class; hidden content should not be mistaken for an approved live experience.
- **Single Product `279`/`462`:** negative spacing and uneven tablet/mobile override counts can cause product information, buttons, and tabs to collide or reorder poorly. Template `462` includes a `-30px` mobile container margin.
- **Inline HTML icons:** many SVGs are correctly marked `aria-hidden`, but the JSON often provides no adjacent programmatic label inside the raw HTML itself. Accessibility depends on surrounding widget text and rendered markup.
- **Not verifiable from JSON:** focus visibility, DOM heading order, keyboard traps, form label association, media-library alt text, contrast after rendering, dynamic error announcements, reduced motion, and floating cart/rewards overlap. These require staging inspection.

## 7. Repeated CSS, duplicated styles, and typography inconsistency

### Custom CSS volume

- Header `73`: 1 block.
- Dark Loop `65`: 1.
- Products Archive `441`: 1.
- BioQuantum About `409`: 6.
- BioQuantum Contact `413`: 2.
- Peptides Divas Home `77`: 10.
- Pep Select Homepage `571`: 20.
- Single Product `279`: 6.
- Single Product `462`: 4.

### Exact repeated rules

- Identical card shadow rule: 10 instances across BioQuantum About, BioQuantum Contact, and Peptides Divas Home.
- Identical light card gradient: 9 instances across Dark Loop `65` and Pep Select Homepage `571`.
- Identical `180deg` section background: 5 instances across Homepage `571` and Single Products `279`/`462`.
- Identical `360deg` section background: 5 instances across the same three templates.
- Identical gold `.text-gradient`: 4 instances across Homepage `571` and Single Products `279`/`462`.
- Identical pulsing hero-button CSS: BioQuantum About and Peptides Divas Home.
- Repeated `selector p{ margin: 0px; }`: Peptides Divas Home and Pep Select Homepage.

### Embedded style duplication

- BioQuantum About contains 4 inline `<style>` blocks; BioQuantum Contact 2; Peptides Divas Home 4; Pep Select Homepage 4.
- The same 44px BioQuantum badge style is repeated 6 times across the two BioQuantum exports.
- The same 48px Pep Select badge style is repeated 3 times in Homepage `571`.
- A Peptides Divas badge block is repeated twice and itself repeats a border declaration.

### Typography families by template group

- Header/footer: Plus Jakarta Sans.
- Pep Select archive/loops/products: Georgia, IBM Plex Mono, and Plus Jakarta Sans.
- Pep Select Homepage `571`: Georgia, IBM Plex Mono, Inter, and Plus Jakarta Sans.
- Peptides Divas: Playfair Display, Inter, and Plus Jakarta Sans.
- BioQuantum: Montserrat.

The current-candidate templates therefore lack one consistent typographic system, and homepage `571` retains Inter while adjacent Pep Select templates use the Georgia/IBM Plex Mono/Plus Jakarta Sans combination. This is file evidence of mixed systems, not a recommendation for which typefaces to keep.

## 8. Historical-evidence-only material

Preserve, but do not use as a rebuild foundation:

- `saved-page-pepdivas-home-77.json`
- `saved-page-bq-about-409.json`
- `saved-page-bq-contact-413.json`

Preserve within those exports as evidence only:

- Peptides Divas and BioQuantum/AmeriQuantum names, copy, testimonials, email addresses, and external links
- Hostinger asset URLs and unrelated upload references
- Purple/luxury visual treatments, Montserrat/Playfair-based typography, pulsing effects, and duplicated badge styles
- Unverified shipping, purity, testing, guarantee, response-time, climate-control, compliance, and testimonial claims
- Missing template `15` reference from the Peptides Divas product grid

Homepage `571` is not historical-only as a whole, but its copied testimonial content and inherited duplicated structures should be treated as unapproved evidence pending review.

## 9. Required visual/staging inspection before final decisions

1. Confirm WordPress front-page assignment and every Theme Builder display condition for header, footer, product archive, both product templates, COA single, and loops.
2. Identify which of Single Product `279` and `462` is active, whether conditions overlap, and whether different products receive different templates.
3. Confirm template `65` in header search, archive, homepage, and related-product contexts before changing it.
4. Confirm template `485` and the `coa` search/single-record path, including no-result, missing-report, previous, pending, failed, and untested states.
5. Trace providers for `[product_stock_status]`, `[recent_batches]`, `[coa_table]`, and `[pepselect_product_coa_carousel]`.
6. Inspect ACF field groups, return types, missing values, and product coverage for all embedded field keys.
7. Test logged-out/logged-in header behavior, rewards, account link, desktop/mobile search, empty/populated cart, and ElementsKit menu keyboard behavior.
8. Verify shipping, ordering, testing, batch, testimonial, response-time, and other factual claims against current operations/evidence.
9. Confirm legacy pages `77`, `409`, and `413` are unpublished, unassigned, absent from navigation, and not embedded as reusable sections.
10. Inspect every target width (1440, 1280, 1024, 768, 480, 430, 390, 375, 360, and 320px), including long names, large text, missing images, and floating cart/rewards controls.
11. Confirm whether hidden homepage sections `content[10]` and `content[11]` are intentionally retired or unfinished remnants.
12. Check all hard-coded live-domain links/assets under staging and define which destinations must become environment-neutral during a later approved implementation phase.

## 10. Prioritized issue list for the later full audit

### P0 — commerce, COA, and global navigation safety

1. Resolve active Theme Builder assignments and overlap between Single Product `279` and `462`.
2. Map shared Loop `65` across every search/archive/home/related-products consumer before any replacement.
3. Trace all COA post-type, ACF, shortcode, loop `485`, and single-template `510` dependencies and status semantics.
4. Verify header rewards, account, cart, search, menu, and shipping-promise behavior in all customer states.
5. Determine whether template `279`'s inquiry flow or template `462`'s native Add to Cart is canonical; do not infer from IDs.

### P1 — brand contamination, broken content, and portability

6. Keep templates `77`, `409`, and `413` historical-only and confirm they are not exposed.
7. Remove from consideration the copied Peptides Divas testimonials and unverified claims in Homepage `571` pending evidence/review.
8. Inventory and later neutralize hard-coded live Kinsta links and external Hostinger/Peptides Divas dependencies.
9. Eliminate visible failure fallbacks: Elementor placeholder headings, demo accordion prose, `List Item #3`, empty order-link destination, and `#` COA link.
10. Resolve missing Loop template `15` as historical dependency evidence.

### P2 — responsive, accessibility, and maintainability

11. Validate negative-margin/overflow behavior in Loop `65` and both product templates across the required width matrix.
12. Review header duplicate search instances, unlabeled raw account link, menu focus behavior, and mobile/tablet layout.
13. Review hidden-at-all-width homepage sections and device-specific BioQuantum duplicate sections.
14. Consolidate repeated inline CSS/styles and define a single approved typography/token source only in a later design/implementation phase.
15. Replace generic search/form feedback with task-specific, accessible states after behavior and architecture are approved.

## 11. Audit checkpoint conclusion

The highest-risk exported architecture is not the historical BioQuantum or Peptides Divas content by itself; it is the coupling among current candidates. Loop `65` is shared by search, archive, homepage, and product recommendations. Two product templates encode different commerce models. COA presentation spans a custom post type, loop `485`, single template `510`, ACF fields, and multiple shortcodes. Header `73` binds navigation to rewards, account, search, cart, and a commercial shipping promise.

No template should be replaced on filename or visual appearance alone. Active assignments, shortcode ownership, ACF data, WooCommerce behavior, and rendered states must be confirmed on staging before a later redesign or implementation decision.

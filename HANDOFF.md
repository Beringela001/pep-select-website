# Pep Select Website Redesign Handoff

State captured: July 17, 2026

Repository: `C:\Users\paulo\Documents\Pep Select Website`

Current branch: `web-2c-homepage`

Current source commit: `f51a701` (`WEB-2C promote beta.3 homepage for staging release`)

Child-theme version: `0.4.0-beta.3`

## 1. Read this first

Pep Select is being rebuilt as a coded, customer-facing presentation layer on top of the existing WordPress and WooCommerce system. The rebuild must preserve products, variations, inventory, customers, orders, coupons, taxes, shipping, checkout, account data, emailed Square payment links, rewards, VerifyPass, email behavior, and the Pep Select COA Archive.

The presentation layer lives in the `pepselect-child/` Hello Elementor child theme. Elementor remains installed for selected editorial content and as a rollback source, but it must not own the critical global shell or core commerce presentation. Business logic stays in WooCommerce or the dedicated plugin that owns it.

Live has not been modified by WEB-2. Work is Staging-first.

### Current release state

- The coded header and footer were activated and verified on Staging as child-theme version `0.3.1`.
- The approved WEB-2C beta.3 homepage is complete in source.
- Commit `f51a701` makes the coded homepage the normal output for supported front-page requests. It no longer requires `?pepselect_home_preview=1`, authentication, or `manage_options`.
- The Elementor Home page remains stored and unchanged as the rollback source.
- A release ZIP was created outside the repository at `C:\Users\paulo\Documents\Pep Select Website Releases\pepselect-child-0.4.0-beta.3.zip`.
- ZIP SHA-256: `3E928EB5623E0819FD0EA0D270621DA62DE78D56966B83FD42C655BC7FA3D162`.
- The promoted `f51a701` build has **not** been deployed or browser-verified on Staging. Deployment stopped before any Staging change because MyKinsta and WordPress were signed out.
- The last documented Staging theme state is `0.3.1`; verify the installed version before proceeding.

Several older WEB-2C documents and `pepselect-child/README.md` still describe the homepage as an administrator-only preview. That is documentation drift. The runtime source at `f51a701` is authoritative.

## 2. Architecture and ownership

| Layer | Owns | Must not own |
|---|---|---|
| WordPress | Pages, menus, users, authentication, URLs, theme activation, template APIs | Duplicated commerce or COA data |
| WooCommerce | Products, variations, prices, stock, cart, checkout, customers, accounts, orders, coupons, taxes, shipping, and emails | Theme-specific duplicated data models |
| `pepselect-child` | Global shell, page templates, scoped presentation, accessibility behavior, and narrow integration adapters | Prices, stock, reward calculations, COA status logic, payment logic, customer data, or secrets |
| Elementor / Elementor Pro | Retained editorial page content and historical rollback templates | Critical shell, core commerce presentation, authentication, or business logic |
| Pep Select COA Archive | COA records, public terminology, current/history selection, sorting, report routes, and testing presentation | General storefront or account logic |
| YITH Points and Rewards | Points balances, rules, conversion, coupons, and history | Theme-calculated or invented balances |
| VerifyPass | Identity verification and WooCommerce coupon creation | Theme-reimplemented verification logic |
| Other commerce plugins | Fluid Checkout, side cart, WooPayments, shipping, tax, tracking, and fulfillment behavior | Reimplemented theme logic |

Primary theme rollback: activate the unchanged Hello Elementor parent theme. The parent-theme rollback restores the preserved Elementor shell and page content. The administrator-only one-request shell rollback remains `?pepselect_legacy_shell=1`.

## 3. Design direction

### Settled visual identity

The site should feel clinical-modern, editorial, approachable, and product-led. It should look established and evidence-aware without becoming cold, institutional, underground, luxury-exclusive, or like a generic SaaS template.

Core characteristics:

- Deep navy and cyan remain the unmistakable Pep Select identity.
- Real WooCommerce product imagery is preferred over decorative laboratory stock imagery.
- Clear hierarchy, varied section rhythm, and deliberate information density replace walls of equal-weight cards.
- Documentation is available and easy to reach, but not every section is forced into a COA card.
- Product discovery appears early; deeper evidence is concentrated in the Quality Archive section.
- Depth comes from tonal layering, product imagery, restrained borders/shadows, and `1–2px` lift. Avoid neon glow, glassmorphism, and busy gradients.
- Desktop, tablet, and mobile are related compositions, not one desktop layout squeezed smaller.

### Approved global foundations

| Category | Decision |
|---|---|
| Primary navy | `#002A53` |
| Supporting navy | `#001D3A` |
| Cyan | `#17A1CF` |
| Semantic green | `#16834A` |
| Semantic amber | `#B46A00` |
| Semantic red | `#C43D3D` |
| Ink / slate / neutral | `#13283D`, `#5E6F80`, `#7A8793` |
| Border / surfaces | `#D7E1E9`, `#F3F8FC`, `#F5F6F7`, `#FFFFFF` |
| Soft semantic surfaces | `#E8F6FB`, `#EAF5EF`, `#FFF4DF`, `#FBECEC` |
| Editorial type | Georgia, `400` and `700` |
| Interface type | Plus Jakarta Sans, `400` and `600` |
| Technical type | IBM Plex Mono, `500` |
| Content width | `1200px` |
| Gutters | `32px` desktop, `24px` tablet, `20px` mobile |
| Radii | `8px`, `12px`, `20px`, and `999px` pill |
| Motion | Approximately `180ms`; color/border/shadow or `1–2px` lift; no shrink; reduced-motion support |
| Breakpoints retained | Mobile `767px`; tablet `1024px` |

These values exist as child-theme variables in `pepselect-child/assets/css/foundations.css` and as custom Elementor global colors/fonts on Staging. Elementor System Colors, System Fonts, button defaults, theme styles, global padding, and global gaps remain unchanged to avoid restyling legacy templates.

### Homepage-specific evolution

WEB-2A is a foundation, not a creative ceiling. The approved beta.3 homepage uses:

- Larger commercial display type than the original conservative scale.
- Plus Jakarta Sans for primary commercial headings, with selective Georgia italic accents.
- Local `24–32px` feature radii and one asymmetric image treatment.
- Section-specific spacing rather than a uniform vertical interval.
- Immersive hero and Quality Archive compositions instead of placing every section in a card.
- Richer product-image surfaces and restrained elevation.

These deviations improve hierarchy, rhythm, product focus, and perceived polish while retaining Pep Select navy/cyan, accessibility, performance, and compliance boundaries.

### References and transferable principles

The repository names one external site as a current visual reference:

- **Orbitrex:** used only for high-level commercial principles: an immersive hero, products near the top, alternating visual environments, a compact FAQ, and credibility concentrated into a strong section. Do not copy its exact layout, graphics, copy, dimensions, branded terminology, statistics, or components.

The Pep Select COA Archive is also an internal implementation reference: bounded requirements, versioned code, Staging review, responsive QA, and rollback. Its data model and UI logic remain plugin-owned.

TrustedPeps, Crush Research, Peptides Divas, BioQuantum, and similar sellers are competitor-boundary examples, not approved design sources.

### Rejected directions

- **Peptides Divas luxury styling:** gold, Playfair Display, “quiet luxury,” boutique/exclusive language, anonymous testimonials, and premium-as-proof positioning. Rejected as another brand, inaccessible in tone, and dependent on unsupported claims.
- **BioQuantum styling/content:** purple gradients, Montserrat, Hostinger assets, unrelated contact details, Texas positioning, and purity/shipping/guarantee claims. Rejected as inherited brand contamination.
- **Manual Elementor global shell:** rejected after Elementor proved unstable and inefficient for critical global components.
- **ElementsKit shell/navigation:** rejected because ElementsKit Lite was the confirmed trigger for Elementor editor memory exhaustion on Staging. Lite and Pro must remain disabled.
- **Beta.2 hero direction:** the earlier instructional/product-record framing was judged emotionally flat. Beta.3 uses “The label is the easy part. What’s behind it matters.” while retaining product-first conversion flow.
- **Card-everything / rigid token application:** rejected because it made the site clinical, repetitive, and template-driven.
- **Generic visual trends:** purple AI gradients, neon glow, default glassmorphism, excessive pills, generic science stock imagery, and SaaS-style decoration are not Pep Select.
- **Cold institutional design:** the site must remain human and commercially useful, not read like a hospital portal or legal notice.
- **Underground/biohacker/bodybuilding imagery or language:** prohibited by brand and research-use positioning.
- **Exact competitor imitation:** references may solve a UX problem, but the final composition and copy must remain original to Pep Select.

## 4. Page-by-page status and ownership

| Surface | Status | Current owner/location | Next action |
|---|---|---|---|
| Design foundations | Complete | Child theme `assets/css/foundations.css`; Elementor Global Styles on Staging | Preserve; verify export before migration |
| Announcement/header/navigation | Redesigned, coded, and Staging-verified at `0.3.1` | `template-parts/header/`, `inc/header-preview.php`, `assets/css/header.css`, `assets/js/header.js` | Regression-test with beta.3 deployment; shipping announcement still needs operational confirmation |
| Footer | Redesigned, coded, and Staging-verified at `0.3.1` | `template-parts/footer/`, `inc/footer-preview.php`, `assets/css/footer.css` | Regression-test; verify universal testing wording and legal/support labels before launch |
| Homepage | Design/copy approved; coded beta.3 complete; public routing committed locally; Staging promotion not verified | `templates/front-page-preview.php`, `inc/homepage-preview.php`, `template-parts/home/`, `assets/css/homepage.css`, `assets/js/homepage.js` | Back up Staging, deploy `f51a701`, clear Staging cache, test logged-out/admin desktop/mobile; preserve Home ID `79` and Elementor #571 |
| Existing Elementor homepage | Preserved rollback source | WordPress Home page ID `79`; repository reference `saved-page-pepselect-homepage-571.json` | Do not delete or overwrite; export fresh before retirement |
| Shop/product archive | Untouched by coded rebuild | Active Elementor Products Archive #441; JSON `products-archive-elementor-441.json` | WEB-2D coded archive and search-results rebuild |
| Product-card loop | Legacy/current shared dependency | Elementor Dark Loop #65; JSON `loop-item-dark-elementor-65.json` | WEB-2D reusable coded card; migrate every consumer before retirement |
| Header product-search entry | Coded | Header form submits `s` plus `post_type=product` | Preserve behavior; results presentation remains unfinished |
| Product search/results | Broken/current; not redesigned | WordPress/Elementor search results and loop #65 | Repair in WEB-2D with bounded imagery and all empty/error/result states |
| COA search/results | Existing plugin is canonical | Pep Select COA Archive at `/testing/`; old Elementor COA loop #485 is legacy | Do not merge with product search or duplicate plugin logic |
| Single product | Untouched presentation; functional commerce verified | Active Elementor Single Product #462; JSON `single-product-elementor-462.json` | WEB-2E coded presentation; preserve all WooCommerce, bundle, side-cart, and COA behavior |
| Legacy Single Product #279 | Unassigned/historical | JSON `single-product-elementor-279.json` | Preserve until conditions, ACF, and shortcode dependencies are proven unused |
| Cart | Functionally verified; presentation untouched | WooCommerce plus existing theme/plugin output | WEB-2F styling only; do not change totals or data |
| Side cart | Functional; mobile overlap remains | Side Cart WooCommerce/Xootix; header shortcode integration | WEB-2F fix layering and footer overlap without changing calculations |
| Checkout | Functional four-step flow; presentation untouched | WooCommerce + Fluid Checkout Lite/Pro | WEB-2F style only; preserve emailed Square payment-link workflow |
| My Account | Functional; presentation untouched | WooCommerce account templates/endpoints | WEB-2G coded styling; preserve customer data, permissions, password reset, and orders |
| Rewards | Functional; shell integration coded | YITH Points and Rewards; `cash-back` account endpoint; header shortcode output | WEB-2G presentation/terminology; do not change points rules or coupon conversion |
| Contact | Existing Elementor page renders and submits; redesign untouched | WordPress/Elementor database; no current Pep Select JSON export | WEB-2H; preserve fields/delivery, remove obsolete “request an order link,” solve authenticated sender/Reply-To for production |
| FAQ page | Existing page; redesign untouched; Staging rendering inconsistent | WordPress/Elementor database; no page export | WEB-2H accessible coded layout; content is not deleted |
| Homepage FAQ | Coded and approved | `template-parts/home/faq.php`, `assets/js/homepage.js`; source subset from Homepage #571 | Preserve three-item supported subset and keyboard behavior |
| About Us | Existing page renders; redesign untouched | WordPress/Elementor database; no Pep Select page export | WEB-2H; remove excess whitespace, replace plain circles with meaningful icons, verify all numerical/testing claims |
| Military/First Responder | Existing flow works; redesign untouched | WordPress page + VerifyPass | WEB-2H; use only VerifyPass-supported modal/embed or retain safe popup; preserve uploads/camera/coupon creation |
| Privacy Policy | Existing page renders; untouched | WordPress/Elementor database | WEB-2I after legal/operational approval |
| Terms & Conditions | Existing page renders; untouched | WordPress/Elementor database | WEB-2I after legal approval |
| Refund & Shipping Policy | Existing page renders; untouched | WordPress/Elementor database | WEB-2I after operational/legal approval |
| RUO Disclaimer | Existing page renders; untouched | WordPress/Elementor database | WEB-2I after actual repackaging/labeling operations are confirmed |
| COA Archive pages | Existing separate coded system; not redesigned here | Pep Select COA Archive plugin, stable source version `0.4.0`, `/testing/` routes | Preserve exactly; test after every theme package |
| Old `/coas/` and `coa` CPT pages | Legacy, not yet retired | Old WordPress content, Elementor #510/#485, ACF/shortcodes | Preserve until records, routes, redirects, SEO, attachments, and statuses are mapped |
| Sample Page | Cleanup candidate | WordPress database | Preserve until confirmed unpublished, unlinked, and unused |
| Duplicate military page | Cleanup candidate | WordPress database | Choose canonical route only after VerifyPass/navigation/SEO checks |

## 5. Coded child-theme map

```text
pepselect-child/
├── style.css                         # Theme metadata; version 0.4.0-beta.3
├── functions.php                     # Loads inc/setup.php only
├── inc/
│   ├── setup.php                     # Parent guard, foundational assets, module bootstrap
│   ├── header-preview.php            # Default coded shell routing and header integrations
│   ├── footer-preview.php            # Footer routing, logo, and link groups
│   └── homepage-preview.php          # Public front-page routing and WooCommerce product query
├── assets/css/
│   ├── foundations.css
│   ├── header.css
│   ├── footer.css
│   └── homepage.css
├── assets/js/
│   ├── header.js                     # Mobile menu accessibility/state
│   └── homepage.js                   # FAQ accordion behavior
├── template-parts/header/
├── template-parts/footer/
├── template-parts/home/
└── templates/front-page-preview.php  # Historical filename; now the public coded front page
```

Despite historical `preview` names, the shell is public by default and the homepage is public on supported front-page requests at `f51a701`. Do not rename these files/functions casually during a release; treat renaming as a separate refactor with routing tests.

No `woocommerce/` template override directory exists. Keep using hooks/public APIs first and add an override only when a milestone proves it necessary.

## 6. Elementor-specific handoff

### Active assignments recorded on Staging

- Header #1323: Entire Site
- Footer #391: Entire Site
- Products Archive #441: All Product Archives
- Single Product #462: Products
- Single Product #279: no instances
- Single Post #510: COAs
- WordPress front page: static Home page, post ID `79`

The coded child theme suppresses Header #1323 and Footer #391 through Elementor Theme Location filters on supported front-end requests. Their stored templates and conditions remain untouched. `?pepselect_legacy_shell=1` restores them for one authorized administrator request.

### Elementor work created or changed during WEB-2

- Added the approved custom global colors and font styles listed in Section 3.
- Changed Elementor global content width from `1140px` to `1200px`.
- Left Elementor System Colors, System Fonts, global padding/gaps, buttons, and theme styles unchanged.
- Retained mobile breakpoint `767px` and tablet breakpoint `1024px`.
- Replaced Header #1323's missing ElementsKit navigation with Elementor's native WordPress Menu widget after ElementsKit was disabled.
- Created an unpublished draft named `Pep Select Header — WEB-2B`; it has no display conditions and must never be published or assigned. The coded child theme is the approved path.
- No new active Elementor header, footer, homepage, product, archive, account, checkout, or policy template was created for the redesign.

### Important reproducibility gap

The repository has no Elementor Site Settings export, and Theme Builder display conditions are not stored in the JSON exports. The existing `header-elementor-73.json` also reflects the older ElementsKit navigation rather than the later native WordPress Menu repair. Before migrating, retiring, or rebuilding the rollback environment:

1. Export current Elementor Site Settings from Staging.
2. Re-export current Header #1323 and Footer #391.
3. Record Theme Builder conditions from WordPress.
4. Export Home ID `79` and the current homepage template/state.
5. Preserve the existing repository JSON files as historical evidence; do not overwrite them without a dated backup.

### Elementor JSON export register

| Export | WordPress/Elementor identity | Purpose and status | Critical dependencies / instruction |
|---|---|---|---|
| `footer-elementor-70.json` | Footer #391 | Preserved active rollback footer | Elementor widgets; contains hard-coded old URLs; re-export current Staging version |
| `header-elementor-73.json` | Header #1323 | Preserved active rollback header; export predates native-menu repair | Search, old ElementsKit nav, YITH shortcode, Xootix shortcode, loop #65; do not treat export as current |
| `loop-item-coa-elementor-485.json` | COA loop #485 | Legacy old-COA result card | Old `coa` post type and Elementor dynamic post data; preserve until old COA migration is complete |
| `loop-item-dark-elementor-65.json` | Dark Loop #65 | Shared product card currently used by multiple Elementor contexts | WooCommerce data and `[product_stock_status]`; critical shared dependency |
| `products-archive-elementor-441.json` | Products Archive #441 | Current Elementor Shop archive | Search + Loop Grid #65; preserve until WEB-2D passes |
| `saved-page-bq-about-409.json` | BioQuantum About | Historical only | `deensimc-smooth-text`, Montserrat, raw HTML/CSS, Hostinger/legacy assets; never reuse |
| `saved-page-bq-contact-413.json` | BioQuantum Contact | Historical only | Elementor Pro Form, old emails, Montserrat, raw HTML/CSS; never reuse or submit real data |
| `saved-page-pepdivas-home-77.json` | Peptides Divas Home | Historical only | ElementsKit Accordion, missing loop #15, Playfair/Inter, copied testimonials/claims; never reuse |
| `saved-page-pepselect-homepage-571.json` | Pep Select homepage candidate | Historical/current Elementor rollback evidence | ElementsKit Accordion, `deensimc-smooth-text`, loop #65, old COA search/loop #485, copied testimonials, extensive inline CSS |
| `single-post-elementor-510.json` | Single Post #510 | Legacy old-COA single template | `[coa_table]` and old `coa` post type; preserve until plugin routes cover all records |
| `single-product-elementor-279.json` | Single Product #279 | Unassigned legacy product template | ACF legacy fields, ElementsKit Accordion, `[recent_batches]`, loop #65, obsolete inquiry flow |
| `single-product-elementor-462.json` | Single Product #462 | Current product presentation | Native Woo widgets, ACF `view_latest_co`, `[pepselect_product_coa_carousel]`, loop #65 |

Filename IDs do not always match exported template titles: `70` maps to Footer #391 and `73` maps to Header #1323. Use WordPress template IDs and recorded conditions, not filenames alone.

### Legacy Elementor CSS burden

The audited exports contain extensive template-local styling rather than reliable global tokens. Custom-CSS block counts include Header `1`, Dark Loop `1`, Products Archive `1`, BioQuantum About `6`, BioQuantum Contact `2`, Peptides Divas `10`, Homepage #571 `20`, Single Product #279 `6`, and #462 `4`. Do not copy these blocks into the child theme wholesale. Migrate one verified component at a time.

### Widgets, dynamic tags, and shortcodes to trace before retirement

- ElementsKit: `ekit-nav-menu`, `elementskit-accordion`. Keep Lite and Pro disabled; replace remaining active consumers.
- Elementor Pro: Search, Loop Grid, Form, WooCommerce widgets, Theme Post widgets, ACF dynamic tags, and per-widget Custom CSS.
- Unknown provider: `deensimc-smooth-text`.
- Unknown provider: `[product_stock_status]` in loop #65.
- Legacy COA path: `[coa_table]`, `[recent_batches]`, and `[pepselect_product_coa_carousel]`.
- YITH: `[yith_ywpar_points label="" show_worth="no"]`.
- Xootix Side Cart: `[xoo_wsc_cart]`.

Never delete a plugin, shortcode, ACF field group, template, or custom post type until every consumer and stored record is mapped and a tested rollback exists.

## 7. COA Archive integration boundary

The Pep Select COA Archive is a separate project and the authoritative frontend for testing records. The stable verified package is version `0.4.0`. The website theme must treat it as an external dependency, not copy its source or rebuild its logic.

Non-negotiable rules:

- `/testing/` is the canonical archive destination.
- The plugin owns public status labels, vetting terminology, current versus previous selection, sorting, record visibility, compound history, full batch reports, optional fields, report URLs, and expected dates.
- The theme must not query undocumented database tables, instantiate private plugin services, duplicate status maps, infer seller identity from cap/crimp colors, or simulate COA records.
- Product search must return WooCommerce products; COA search must return plugin-owned compounds/testing records. Do not merge the query logic merely because the inputs share a visual pattern.
- The beta.3 homepage links to `/testing/` and omits a fabricated “latest record” card because plugin `0.4.0` exposes no supported generic homepage projection.
- Product pages may link to plugin-owned routes, but legacy ACF fields/shortcodes must not become a second source of truth.
- After every theme deployment, verify `/testing/`, search, compound history, a full batch record, current/previous records, optional/missing data, report links, and responsive behavior.
- Do not modify the external COA project or stable ZIP as part of website milestones.

## 8. Runtime dependencies and settings

### Required by the coded theme

- Hello Elementor parent theme, documented Staging version `3.4.9`.
- WordPress theme hooks and template APIs.
- Elementor Theme Location filters for suppressing the preserved header/footer on coded-shell requests.
- WooCommerce public APIs and URLs.
- WordPress Custom Logo; Media Library fallback IDs are header `595` and footer `687`.
- WordPress navigation menu named `new` is checked first, followed by assigned/existing menus and safe same-site fallbacks.
- YITH Points and Rewards shortcode when registered; otherwise show “Rewards” without an invented balance.
- Xootix side-cart shortcode when registered; otherwise show the canonical WooCommerce cart/count fallback.

### Preserved operational systems

- WooCommerce
- Elementor and Elementor Pro
- Fluid Checkout Lite and Pro
- Advanced Custom Fields Pro
- Pep Select COA Archive
- PS Access Gate
- YITH Points and Rewards
- YITH Order and Shipment Tracking
- Side Cart WooCommerce
- VerifyPass
- WooPayments, including Safe Mode on Staging
- WooCommerce Shipping and Tax
- Google for WooCommerce
- Jetpack and its WooCommerce-connected services
- Temporary emailed Square payment-link workflow

Do not update, replace, disconnect, or remove these systems during a presentation milestone. Jetpack specifically must not be disconnected without a controlled maintenance plan.

### Staging constraints and rollback points

- Kinsta Staging has a `512 MB` PHP pool across two threads, with `256 MB` per thread.
- Kinsta recorded `15` memory-limit and `28` thread-limit events during the audit.
- ElementsKit Lite reproduced the Elementor editor failure; Lite and Pro remain disabled.
- Existing named backups include:
  - `Before WEB-1 Audit`
  - `After ElementsKit Removal - Elementor Fixed`
  - `Before WEB-2A Design System`
  - `After WEB-2A Global Foundations`
  - `Before WEB-2B Header Footer Rebuild`
  - `After WEB-2B Child Theme Activation`
  - `Before WEB-2B Coded Shell Activation`
  - `After WEB-2B Coded Shell Activation`

Create and verify a new named Kinsta Staging backup before deploying beta.3. Never use Kinsta Push Environment or touch Live without a separate approved deployment milestone.

## 9. Copy and voice decisions

### Voice

Use calm, precise, direct, human, informed language. The tone is confident without arrogance and clinical-modern without sounding like a hospital or legal notice. Prefer concrete tasks, records, batches, and actions over vague quality adjectives.

Core positioning:

- Transparent over mysterious.
- Documented over exaggerated.
- Accessible over exclusive.
- Dependable over flashy.
- Carefully selected rather than indiscriminately listed.
- Batch traceability rather than broad unsupported reassurance.

Do not imply human use, medical outcomes, dosing, administration, safety, efficacy, universal testing, guaranteed purity, comparative superiority, or pricing superiority. Never invent products, prices, stock, scarcity, test results, laboratories, testimonials, statistics, certifications, turnaround times, or guarantees. Use `[VERIFY CLAIM]` in working documents when evidence is missing.

### Approved beta.3 homepage copy

1. **Hero**
   - Eyebrow: `RESEARCH WITHOUT THE RUNAROUND`
   - Heading: `The label is the easy part.` / `What's behind it matters.`
   - Copy: `You shouldn't need five tabs and a leap of faith to explore a research compound. Pep Select keeps current product details and available batch documentation close at hand—so the information is easier to find when you want it.`
   - CTAs: `Explore the Lineup`; `See the Receipts`
   - Micro-proof: `Current compounds`; `Visible batch status`; `No documentation scavenger hunt`

2. **Confidence strip**
   - `Live catalog pricing`
   - `Current availability`
   - `Batch records when available`
   - `Direct support`

3. **Featured compounds**
   - Eyebrow: `CURRENTLY IN THE CATALOG`
   - Heading: `Start with what caught your eye.`
   - Copy: `The details are already waiting.`
   - CTAs: `Explore All Compounds`; `View Compound`

4. **Why Pep Select**
   - Eyebrow: `WHY PEP SELECT`
   - Heading: `Less guessing.` / `More to go on.`
   - Copy: `The compound gets your attention. Clear product details and available records help you take the closer look.`
   - Supporting points: `Focused lineup`; `Details where you expect them`; `The deeper dive is there`

5. **Batch identity**
   - Heading: `Nice label.` / `Now show me the batch.`
   - Copy: `When a record is available, the vial identifiers and batch details should connect without making you play detective.`
   - CTA: `Review Batch Records`
   - Labels: `Compound`; `Labeled Strength`; `Batch Number`; `Cap Color`; `Crimp Color`; `Current Status`
   - Fallback: `Recorded when available`

6. **Quality Archive**
   - Eyebrow: `PEP SELECT QUALITY ARCHIVE`
   - Heading: `See what the label can't tell you.`
   - Copy: `Search by compound, follow current and previous records, and open the documentation available for each release.`
   - Signature: `Match the vial. Match the batch.`
   - CTA: `Open the Quality Archive`
   - Action labels: `Search by compound`; `Follow batch history`; `Open the full record`

7. **FAQ**
   - Heading: `Questions before you order?`
   - `What are Pep Select compounds intended for?`: `Research use only.`
   - `Do all products include COAs?`: `Where available, documentation is associated with individual batches.`
   - `Can I verify a batch?`: `Yes. Use the Quality Archive to search by compound and open available batch records.`
   - CTA: `Read All FAQs`

8. **Final CTA**
   - Heading: `Found the compound?` / `Check what's behind it.`
   - Copy: `Explore the current lineup, or take the deeper dive inside the Pep Select Quality Archive.`
   - CTAs: `Explore Compounds`; `See the Receipts`

Product names, IDs, images, prices, stock, and URLs remain dynamic WooCommerce data. Detailed COA values remain in the plugin.

### Global-shell copy that still requires verification

- Announcement: `Free 2-Day Shipping on Cart Subtotals of $200`. Confirm the current shipping rule before launch.
- Header search placeholder: `Which compound are you looking for?`
- Navigation: `Home`, `Compounds`, `COAs`, `FAQ`, `Contact`.
- Footer includes `For laboratory research use only.`, `Products are not intended for human consumption.`, support at `support@pepselect.com`, and the published FDA disclaimer in `template-parts/footer/disclaimer.php`.
- The footer sentence claiming compounds are independently tested with batch-specific COAs can read as universal. Verify against actual coverage or revise in a dedicated approved copy/legal checkpoint.
- Footer `Track your order` currently points to WooCommerce account orders, not a proven public tracking tool.
- Use “points,” not “cash back,” in customer-facing rewards presentation unless the rewards milestone approves different terminology.

Do not restore the obsolete “Request an order link” page copy. Customers order through checkout and receive the temporary Square payment link by email.

## 10. Remaining work in priority order

1. **Finish the WEB-2C Staging promotion.** Authenticate MyKinsta and Staging WordPress, verify the target is Staging, record the installed child-theme version, create a named backup, upload the `f51a701` beta.3 ZIP, clear Staging-only caches, and test logged-out/admin root requests at desktop and mobile widths. Roll back on fatal errors, missing data, broken shell/COA, or substantial overflow.
2. **Close the unfinished search work.** The coded header search entry exists, but product results remain broken/oversized. Implement a launch-safe product results layout without touching COA search ownership.
3. **WEB-2D product-card system and Shop archive.** Replace Dark Loop #65 and Archive #441 in parallel. Trace `[product_stock_status]`, preserve all WooCommerce truth, and migrate each loop consumer through a recorded test.
4. **Reconcile the homepage product card with WEB-2D.** The homepage currently has its own reusable partial. Consolidate only after the canonical WEB-2D card is approved; do not regress the approved beta.3 composition.
5. **WEB-2E single product.** Replace #462 through hooks first, preserve bundle/cart/COA behavior, fix mobile typography and related products, and retain #279 as evidence.
6. **WEB-2F cart, side cart, and checkout presentation.** Fix layering, footer overlap, focus, responsive summaries, and validation styling without changing totals, checkout steps, orders, emails, or Square links.
7. **WEB-2G My Account and rewards.** Style account endpoints and points/coupon states; preserve identity, privacy, orders, addresses, password reset, and YITH rules.
8. **WEB-2H supporting pages.** In order: Contact, FAQ, About, Military/First Responder. Export each current page before replacement. Preserve form delivery and VerifyPass behavior.
9. **WEB-2I legal and policy pages.** Do not replace until dates, Square workflow, Google sign-in launch status, Georgia law, Kennesaw address, processing/refund handling, COA claims, and actual packaging/labeling operations are approved.
10. **WEB-2J integrated QA and launch readiness.** Test the full width matrix, keyboard, focus, reduced motion, logged-in/out states, empty/error states, performance, Elementor editor compatibility, product/COA/search/cart/account flows, and rollback.
11. **Legacy retirement only after replacements pass.** Apply the removal register item by item. Never bulk-delete Elementor templates, old COA records, ACF fields, plugins, pages, or the Hello parent fallback.
12. **Live deployment is separate.** Require explicit approval, a current Live backup, a reviewed deployment plan, desktop/mobile/commerce testing, and rollback. Do not push Staging to Live casually.

## 11. Immediate Staging smoke-test checklist

After beta.3 installation:

- Root URL without parameters shows the coded homepage while logged out.
- Administrator root URL shows the same coded homepage without a parameter.
- `?pepselect_home_preview=1` does not expose different or privileged content.
- `?pepselect_legacy_shell=1` remains administrator-only and changes only the shell.
- Elementor Home ID `79` still exists and has not been overwritten.
- Hero copy, CTAs, real WooCommerce images/titles/prices/stock, four featured products, sections 4–6, FAQ, and final CTA render.
- Header/footer dimensions and navigation remain stable on every page.
- Product links, Shop, account, cart, rewards, and `/testing/` work.
- No PHP warning, fatal error, broken image, mixed content, failed beta.3 asset, console error, or horizontal overflow appears.
- Test approximately `1440px` and `390px` immediately; complete the full `1440`, `1280`, `1024`, `768`, `480`, `430`, `390`, `375`, `360`, and `320px` matrix before launch.
- Confirm WooCommerce and COA records were not modified by deployment.

## 12. Authoritative repository references

- `docs/WEB-1-elementor-audit.md`: all supplied exports, dependencies, legacy contamination, URLs, copy, and risks.
- `docs/WEB-1-staging-findings.md`: active assignments, editor failure, plugin isolation, page/function tests, and known defects.
- `docs/WEB-2-rebuild-plan.md`: milestones, preservation boundary, legacy-removal register, risk register, and implementation order.
- `docs/WEB-2A-design-system-audit.md`: approved design foundations and Staging global-style changes.
- `docs/WEB-2B-global-navigation-plan.md`: coded-shell architecture, integrations, packages, Staging activation, and rollback.
- `docs/WEB-2C-homepage-journey.md`: product-first journey and compliance boundary; preview-status language is stale after `f51a701`.
- `docs/WEB-2C-homepage-copy-draft.md`: approved beta.3 copy; preview-status language is stale after `f51a701`.
- `docs/WEB-2C-homepage-implementation.md`: beta.3 implementation and design evolution; preview-status language is stale after `f51a701`.
- `.agents/product-marketing.md`: tracked public positioning, voice, claims, and compliance guardrails.
- `site-exports/elementor/`: historical Elementor JSON evidence; not a complete or current site backup.
- `pepselect-child/`: authoritative current presentation source.

The private Pep Select copy supplement is intentionally excluded from this handoff and must never be committed, packaged, quoted, summarized, or exposed in public artifacts.

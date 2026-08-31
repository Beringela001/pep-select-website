# Changelog

**Release candidate: 0.25.0-beta.86** - Remove the inherited account wrapper gap.

## 0.25.0-beta.86 - 2026-08-30

- Remove WooCommerce's logged-out wrapper padding and cap the intentional menu-to-card gap at 24px on desktop and 16px on mobile.
- Center the Google sign-in control consistently in both the login and registration panels.

**Previous release candidate: 0.25.0-beta.85** - Tighten the logged-out account page.

## 0.25.0-beta.85 - 2026-08-30

- Remove the tinted account-page band and reduce its vertical padding so the login card sits closer to the site menu with less scrolling.

**Previous release candidate: 0.25.0-beta.84** - Standardize customer email reply support.

## 0.25.0-beta.84 - 2026-08-30

- Use the approved reply-first support sentence across every treated account, order, shipment, refund, and stock-notification email.
- Preserve account-security instructions while removing duplicate support-address prompts from email bodies.

**Previous release candidate: 0.25.0-beta.83** - Resolve the account form surface cascade.

## 0.25.0-beta.83 - 2026-08-30

- Raise the scoped account-form reset above WooCommerce's built-in login card rule so the page reliably renders one surface.

**Previous release candidate: 0.25.0-beta.82** - Make transactional email support reply-first.

## 0.25.0-beta.82 - 2026-08-30

- Replace redundant refund, password-reset, and email-confirmation support copy with one direct reply instruction.
- Remove the refund email's duplicate contact button and suppress generic WooCommerce additional content from these three custom messages.
- Keep the account-security instructions and signed account actions unchanged.

**Previous release candidate: 0.25.0-beta.81** - Finish the logged-out account surface.

## 0.25.0-beta.81 - 2026-08-30

- Remove WooCommerce's nested form card so login and registration use one intentional white surface.
- Keep account tabs on-brand in every interaction state and eliminate the remaining viewport-width overflow.

**Previous release candidate: 0.25.0-beta.80** - Focus the logged-out account experience.

## 0.25.0-beta.80 - 2026-08-30

- Replace the competing login and registration columns with one responsive card and accessible account-access tabs.
- Remove YITH's optional date-of-birth field from account registration while preserving other WooCommerce registration hooks.
- Expand the Google sign-in popup within the available desktop display and remove the logged-out page's horizontal overflow.

**Previous release candidate: 0.25.0-beta.79** - Rebuild the refund and account-verification email workflows.

## 0.25.0-beta.79 - 2026-08-30

- Add a responsive Pep Select refund email with distinct full-refund and partial-refund messaging while preserving WooCommerce's order summary and refund calculations.
- Add responsive reset-password and confirm-email messages that preserve WooCommerce's signed, one-time account links.
- Add matching plain-text versions and route all three HTML messages through one shared Pep Select canvas and company footer.

**Previous release candidate: 0.25.0-beta.78** - Complete the shared company footer across every WooCommerce email.

## 0.25.0-beta.78 - 2026-08-30

- Replace the admin new-order email's short order label with the same full company footer used by the rest of the WooCommerce email workflow.
- Preserve the admin order number as quiet footer context while keeping the ownership line, website, address, email, and phone number consistent.

## 0.25.0-beta.77 - 2026-08-30

- Add the complete company contact footer to shared and custom WooCommerce email layouts.
- Replace the platform-dependent flag emoji with a small email-safe flag image.
- Present `Pep Select is an American-owned and operated company.` at 13px regular weight so it supports the footer instead of dominating it.
- Add a shared narrow-screen safeguard for every WooCommerce-rendered email so order and product tables wrap inside the mobile canvas instead of cropping.
- Enforce the same approved footer at FluentCRM send time so saved campaigns, automations, and future newsletters cannot drift back to the older ownership treatment.

**Previous release candidate: 0.25.0-beta.75** - Keep normal homepage loads anchored at the global header without overriding history restoration.

## 0.25.0-beta.75 - 2026-08-30

- Keep the beta74 direct/reload top reset, but leave the browser's native scroll-restoration mode unchanged.
- Preserve Back/Forward position as well as explicit `#fragment` navigation and bfcache restoration.

**Previous release candidate: 0.25.0-beta.74** - Keep normal homepage loads anchored at the global header.

## 0.25.0-beta.74 - 2026-08-30

- Reset direct and reload homepage visits to the top after Chrome applies remembered scroll restoration.
- Preserve explicit `#fragment` navigation and bfcache restoration; beta75 completes Back/Forward position preservation.
- Keep the correction in homepage presentation code without changing access-gate or commerce behavior.

**Previous release candidate: 0.25.0-beta.73** - Make American ownership prominent across the site and transactional email footers.

## 0.25.0-beta.73 - 2026-08-30

- Render `🇺🇸 American-owned and operated.` larger, bold, and high-contrast in the coded website footer.
- Render the same company-level line at 15px, bold, and navy in custom and default WooCommerce customer-email footers.
- Preserve the exact ownership wording and all existing product-origin, research-use, shipping, order, and email-routing boundaries.

**Previous release candidate: 0.25.0-beta.72** - Add direct American ownership and ship-from facts to customer trust surfaces.

## 0.25.0-beta.72 - 2026-08-30

- Add the owner-confirmed `🇺🇸 American-owned and operated.` line to the website footer and coded customer-email footers without making a product-origin claim.
- State plainly in the full and homepage FAQs that Pep Select orders ship from New York or Georgia.
- Preserve all research-use, testing, commerce, email-routing, and shipping logic.

**Previous release candidate: 0.25.0-beta.71** - Remove obsolete Elementor plugin compatibility after permanent retirement.

## 0.25.0-beta.71 - 2026-08-30

- Remove Elementor editor, legacy-shell, Theme Builder suppression, single-product override, asset-dequeue, font-consolidation, and Marquee cleanup paths that no longer have an installed provider.
- Preserve the Hello Elementor parent-theme dependency, fallback header/footer suppression, and all coded WooCommerce, COA, navigation, account, cart, and checkout ownership.
- Archive all 13 non-empty stored Elementor templates before plugin deletion; Elementor reported the fourteenth Library item, Default Kit ID 7, as empty and not exportable.

**Previous release candidate: 0.25.0-beta.70** - Force the coded About template ahead of its stored Elementor page assignment.

## 0.25.0-beta.70 - 2026-08-30

- Select `page-about-us.php` through the child theme's template filter so legacy Elementor page metadata cannot retain ownership of the public route.
- Keep the stored Elementor document unchanged as rollback data while the coded template owns `/about-us/`.

**Previous release candidate: 0.25.0-beta.69** - Replace the last public Elementor page with a coded, indexable About page.

## 0.25.0-beta.69 - 2026-08-30

- Replace the Elementor-authored About page body with a responsive child-theme template while keeping the page out of header and footer navigation.
- Source the hero vial from the current Tesamorelin WooCommerce product image, with a published-product fallback instead of a copied legacy image.
- Remove the obsolete About `noindex` and sitemap exclusion, then add a focused search title, description, canonical URL, social image, and AboutPage schema.
- Remove Elementor front-end assets from the coded About route and preserve the confirmed research-use, testing, catalog, and Quality Archive boundaries.

**Previous release candidate: 0.25.0-beta.68** - Load side-cart confetti only when a celebration is triggered.

## 0.25.0-beta.68 - 2026-08-29

- Replace the side-cart plugin's 127 KiB initial confetti dependency with a tiny on-demand loader on the audited storefront templates.
- Preserve the plugin's configured progress-bar celebrations by loading its original vendor bundle when `confetti.create()` is first called.
- Keep the original WordPress script handle and dependency order so the side-cart runtime remains compatible.

**Release candidate: 0.25.0-beta.67** - Remove unused Elementor runtime assets from the coded Contact template.

## 0.25.0-beta.67 - 2026-08-29

- Treat the coded Contact page as safe for the existing Elementor front-end asset cleanup while preserving Elementor editor and preview requests.
- Remove the orphaned Elementor front-end bundle that logged `elementorFrontendConfig is not defined` on `/contact/`.

**Previous release candidate: 0.25.0-beta.66** - Make contact timing initialization safely verifiable.

## 0.25.0-beta.66 - 2026-08-29

- Add a non-sensitive data marker after the browser initializes the minimum-fill timestamp, allowing staging verification without exposing the timestamp value.

**Previous release candidate: 0.25.0-beta.65** - Initialize the contact-form timing guard after cached markup loads.

## 0.25.0-beta.65 - 2026-08-29

- Wait for `DOMContentLoaded` before setting the minimum-fill timestamp when the script is loaded in the document head.

**Previous release candidate: 0.25.0-beta.64** - Block automated contact-form mail abuse without adding visitor tracking.

## 0.25.0-beta.64 - 2026-08-29

- Add a three-second minimum fill time to reject immediate automated submissions.
- Limit valid submissions to five per hashed connection identity per hour without storing raw IP addresses.
- Suppress exact duplicate messages for 15 minutes after successful delivery while preserving the existing support inbox and reply-to behavior.
- Keep bot and throttle responses generic so automated senders receive no filtering signal.

**Previous release candidate: 0.25.0-beta.62** - Reduce logo transfer cost without changing the artwork.

## 0.25.0-beta.62 - 2026-08-28

- Add a responsive 320 px header-logo source while retaining the 448 px source for wider and high-density displays.
- Re-encode the 448 px WebP header logo from the original PNG at high quality, reducing its transfer size without changing its dimensions or layout.
- Preserve the existing eager/high-priority header behavior, intrinsic aspect ratio, alt text, and visual design.

**Previous release candidate: 0.25.0-beta.61** - Cover Elementor 4.1 generated stylesheet handles.

## 0.25.0-beta.61 - 2026-08-28

- Remove Elementor-generated upload styles by their verified `/uploads/elementor/css/` source path on the fully coded performance templates.
- Cover Elementor 4.1 handles such as `base-desktop` and `local-79-frontend-*` that do not use the historical `elementor-post-*` prefix.
- Preserve Elementor editor and preview requests and leave all non-audited templates unchanged.

**Previous release candidate: 0.25.0-beta.60** - Stop unused Site Kit AdSense advertising payloads.

## 0.25.0-beta.60 - 2026-08-28

- Block Site Kit's AdSense tag on standard and AMP requests because Pep Select does not run publisher advertising.
- Preserve Site Kit Analytics, Search Console, Tag Manager configuration, and WooCommerce event measurement.
- Remove the audited AdSense and Google/DoubleClick advertising request chain without editing third-party plugin code.

**Previous release candidate: 0.25.0-beta.59** - Remove unused template assets and right-size global logo delivery.

## 0.25.0-beta.59 - 2026-08-28

- Stop loading Elementor front-end CSS and JavaScript on the fully coded Home, Shop, product, and Quality Archive templates while preserving Elementor editor and preview requests.
- Remove the orphaned Elementor bundle that logs `elementorFrontendConfig is not defined` on those templates.
- Defer verified non-critical WooCommerce and theme helpers without removing their catalog or side-cart behavior.
- Replace the 1,400 px header and footer logo transfers with visually identical 448 px lossless WebP assets sized for their largest 2x rendered use, reducing those two responses by about 146 KB.
- Preserve Site Kit analytics and AdSense unchanged pending an explicit business decision about their storefront role.

**Previous release candidate: 0.25.0-beta.58** - Align Puerto Rico checkout with domestic USPS rating.

## 0.25.0-beta.58 - 2026-08-27

- Tell Puerto Rico customers to select Puerto Rico in State / Territory.
- Align checkout guidance with Easyship's domestic U.S. USPS address format.

**Previous release candidate: 0.25.0-beta.57** - Show Country / Region for Puerto Rico checkout.

## 0.25.0-beta.57 - 2026-08-27

- Restore the checkout Country / Region field now that Puerto Rico is an eligible destination.
- Let shoppers choose United States or Puerto Rico before checkout calculates state, tax, and carrier rates.

**Previous release candidate: 0.25.0-beta.56** - Correct Puerto Rico address entry and shipping calculation.

## 0.25.0-beta.56 - 2026-08-27

- Tell Puerto Rico customers to select Puerto Rico in Country / Region.
- Clarify that doing so allows checkout to calculate the correct tax and USPS rate.

**Previous release candidate: 0.25.0-beta.55** - Restore Alaska, Hawaii, and Puerto Rico shipping.

## 0.25.0-beta.55 - 2026-08-27

- Update the FAQ and Refund & Shipping Policy to allow all 50 states, Washington, D.C., and Puerto Rico.
- State that Alaska and Puerto Rico orders ship by USPS only.
- Continue excluding the U.S. Virgin Islands, other U.S. territories, and overseas military addresses.

**Previous release candidate: 0.25.0-beta.54** - Limit shipping to the contiguous United States.

## 0.25.0-beta.54 - 2026-08-27

- Update the FAQ and Refund & Shipping Policy to state that Pep Select ships only to the lower 48 states and Washington, D.C.
- State that Alaska, Hawaii, Puerto Rico, the U.S. Virgin Islands, other U.S. territories, and overseas military addresses are not eligible for shipping.
- Add a regression check that prevents the former 50-state and broad U.S. shipping promises from returning.

**Previous release candidate: 0.25.0-beta.53** - Bring back-in-stock emails into the Pep Select transactional email system.

## 0.25.0-beta.53 - 2026-08-26

- Add child-theme HTML and plain-text overrides for the Back In Stock Notifier subscription-confirmation and product-available emails.
- Match the approved Pep Select order-email canvas, typography, product card, status language, support footer, and responsive behavior.
- Replace the empty-name greeting and artificial scarcity copy with the approved human stock-watch messages.
- Keep the notifier plugin authoritative for subscriptions, recipients, stock transitions, and delivery while routing replies to `support@pepselect.com`.

**Release candidate: 0.25.0-beta.52** - Replace the homepage hero with the approved finished vial artwork.

## 0.25.0-beta.52 - 2026-08-23

- Replace the prior composited homepage hero with the approved finished 4096 px artwork featuring the new Pep Select logo and updated vial labels.
- Regenerate the existing seven responsive WebP hero sizes while preserving the homepage layout, copy, image treatment, and responsive source selection.

**Previous release candidate: 0.25.0-beta.51** - Apply the approved new labels to the homepage hero lineup.

## 0.25.0-beta.51 - 2026-08-22

- Replace only the printed label artwork on the five homepage hero vials with the approved GLP-3 RT, GHK-CU, BPC-157, Tesamorelin, and NAD+ labels.
- Preserve the original hero crop, vial positions, caps, glass, lighting, reflections, shadows, background, colors, and responsive presentation.
- Regenerate the existing seven optimized WebP hero sizes without changing homepage structure or copy.

**Release candidate: 0.25.0-beta.50** - Apply the approved Tesamorelin label to the homepage batch-matching visual.

## 0.25.0-beta.50 - 2026-08-21

- Preserve the original two-vial photograph, reverse label, batch number, QR code, COA image, pointer positions, and responsive layout.
- Overlay only the approved new Tesamorelin 10 mg front label on the left vial.
- Keep the additional label payload below 19 KB so the logo update does not replace the optimized 48 KB base photograph with a much larger composite.

**Release candidate: 0.25.0-beta.49** - Replace the global logos and simplify phone-number display.

## 0.25.0-beta.49 - 2026-08-21

- Replace the coded header and footer logos with the approved new Pep Select artwork.
- Use the corrected reversed footer lockup with open P, A, and D counters and a subtle separation between the P, E, and P letterforms.
- Remove the vanity-number suffix from the global footer and Contact page while retaining the tap-to-call `1 (833) 737-7528` number.

**Release candidate: 0.25.0-beta.48** - Add account text-message consent preferences.

## 0.25.0-beta.48 - 2026-08-21

- Add a simple text-message preference card to the signed-in My Account dashboard with a mobile-number field, separate customer-care and marketing consent choices, an explicit no-text option, and the approved disclosure.
- Keep all consent choices unselected on initial load, allow both affirmative choices together, make the no-text choice exclusive, and save the customer's choice with a timestamp after nonce and phone validation.

**Release candidate: 0.25.0-beta.47** - Add SMS program terms and customer-service phone details.

## 0.25.0-beta.47 - 2026-08-21

- Add the approved Data Sharing and Messaging Program Terms and Conditions sections to the Privacy Policy, add the messaging terms to Terms & Conditions, and update both revision dates.
- State that checking the policy agreement box at checkout acknowledges acceptance of the Messaging Program Terms and Conditions.
- Add the tap-to-call customer-service number to the Contact page and global footer.

**Release candidate: 0.25.0-beta.46** - Normalize My Account card spacing.

## 0.25.0-beta.46 - 2026-08-21

- Remove the Cash back detail-page engine class from the dashboard referral card; that class added a 40px section margin on top of the dashboard's 20px grid gap.
- Restore a consistent 20px gap vertically and horizontally between Welcome, My information, Cash back, Refer a friend, and Your orders.

**Previous release candidate: 0.25.0-beta.45** - Move the referral card onto the main account dashboard.

## 0.25.0-beta.45 - 2026-08-21

- Move the existing dynamic Refer a friend card from the Cash back detail endpoint to the main My Account dashboard, below the My information and Cash back cards and above Your orders.
- Preserve the server-generated `PSRC` share link, `WELCOME10` instructions, $15 referral figure, copy control, YITH referral behavior, cash-back detail tools, and native WooCommerce account endpoints.

**Previous release candidate: 0.25.0-beta.44** - Study-tethered plain-English Cagrilintide content.

## 0.25.0-beta.44 - 2026-08-21

- Add a plain-English Cagrilintide description and three research-context bullets covering amylin signaling, semaglutide combination research, and fullness or food-intake signaling.
- Tie each bullet to a separate verified publication while leaving CAS and formula values for batch-specific COA confirmation.

**Previous release candidate: 0.25.0-beta.43** - Study-tethered plain-English KPV content.

## 0.25.0-beta.43 - 2026-08-21

- Tie each KPV research-context bullet to a separate verified preclinical study: intestinal inflammation and barrier research, targeted delivery to the intestinal lining, and airway or epithelial inflammation.
- Add the verified 2017 Molecular Therapy nanoparticle-delivery study between the existing intestinal and airway sources so superscripts 1, 2, and 3 map directly to the displayed citations.

**Previous release candidate: 0.25.0-beta.42** - Plain-English KPV research content.

## 0.25.0-beta.42 - 2026-08-21

- Replace pathway-heavy KPV wording with plain-English research context covering intestinal inflammation and barrier research, immune signaling in the intestinal lining, and airway or epithelial inflammation.
- Keep every KPV context line tethered to the same two verified preclinical sources without adding human-use, treatment, safety, or effectiveness claims.

**Previous release candidate: 0.25.0-beta.41** - KPV structured product-page research content.

## 0.25.0-beta.41 - 2026-08-21

- Add KPV to the approved compound-content library with a mechanism-focused description, three preclinical research-context bullets, and two verified primary literature references.
- Render KPV through the same coded Description, Research context, source disclosure, and intended-use components used by Tesamorelin and the other library-backed compounds.
- Preserve WooCommerce product data and the COA Archive as the sources of truth for price, stock, batch documentation, and testing history. No CSS, inventory, checkout, payment, shipping, or COA records change.

**Previous release candidate: 0.25.0-beta.40** - Dynamic availability and research-mechanism catalog ordering.

## 0.25.0-beta.40 - 2026-08-20

- Sort Shop, product taxonomy, and product-search cards dynamically into three live availability groups: in stock, restocking soon, then out of stock.
- Within every availability group, apply the researched merchandising sequence: GLPs (GLP-3, GLP-2, GLP-1); healing/repair (BPC-157, TB-500, GHK-Cu); metabolism/mitochondrial (MOTS-C, SS-31, NAD+); growth-hormone axis (Tesamorelin, Sermorelin, CJC-1295, Ipamorelin); supporting/other (KPV, Glutathione, PT-141).
- Keep per-product Display order as the tie-breaker for multiple strengths and the fallback for future unclassified compounds. Bacteriostatic water remains outside compound classification.
- Preserve WooCommerce as stock source of truth and the COA Archive bridge as the restocking source of truth; no product, inventory, COA, pricing, cart, checkout, or order records change.

**Previous release candidate: 0.25.0-beta.39** - Claude SEO measured render-path cleanup, built on the Control Ops beta.38 restocking changes.

## 0.25.0-beta.39 - 2026-08-19

- Consolidate Elementor's four Google Fonts stylesheets into one request while preserving the same four families and every requested weight and italic variant.
- Inline the small Pep Select foundation, header, footer, back-in-stock, and side-cart shell styles on the four audited SEO templates without changing their source rules or cascade order.
- Stop loading rewards, dynamic-pricing, back-in-stock, Select2, and native WooCommerce page-layout styles on the Quality Archive, where verified live markup contains none of those components.
- Add early connection hints for the consolidated Google Fonts stylesheet and font files.
- Preserve WooCommerce, checkout, pricing, rewards, side-cart, product, and COA scripts and business logic.

**Previous release candidate: 0.25.0-beta.36** - Claude SEO Milestone 4 combined batch 3 and 4 product trust placement.

## 0.25.0-beta.36 - 2026-08-19

- Render the compact COA summary and dilution notice after the existing promotion-pill output buffer closes so dynamic batch content cannot be mistaken for promotional text.
- Preserve the approved card order directly after the purchase or back-in-stock section.

**Previous release candidate: 0.25.0-beta.35**

## 0.25.0-beta.35 - 2026-08-19

- Place the COA plugin's compact current or incoming batch summary directly after the purchase or back-in-stock action and before the existing dilution notice.
- Keep the simple compound descriptions, complete testing-history carousel, WooCommerce commerce controls, and COA source records unchanged.
- Pair with COA Archive 0.7.4 so the compact summary and full history read the same automatic product-to-compound relationship and public report model.

**Previous release: 0.25.0-beta.34** - Claude SEO Milestone 4 content batch 2 public metadata refinement.

## 0.25.0-beta.34 - 2026-08-19

- Keep the first Yoast description tag and remove later duplicate description tags on public guide responses, where WordPress omits the administrative Yoast class marker.

## 0.25.0-beta.33 - 2026-08-19

- Keep Yoast as the single meta-description owner on guide posts.
- Preserve the visible guide excerpt while removing only the duplicate non-Yoast description tag from final guide HTML.

## 0.25.0-beta.32 - 2026-08-19

- Remove WordPress-generated line-break spacing around guide evidence images so the full report sits flush in its frame.

## 0.25.0-beta.31 - 2026-08-19

- Show the complete Freedom Diagnostics report image in the guide without an internal scrolling frame.
- Shorten the `Reported` status explanation to its literal meaning: the record presents a laboratory value.

## 0.25.0-beta.30 - 2026-08-19

- Add real Quality Archive and Freedom Diagnostics screenshots to the documentation guide with numbered, responsive explanations for batch identity, photographed vials, cap/crimp matching, laboratory-source verification, corrected strength, and rejected-batch decisions.
- Preserve Retatrutide batch `RT2026205JP` as the documented corrected-strength example and NAD+ `PSNAD562926JP` plus Retatrutide `PSRT2062926JP` as public not-released examples without changing their records or release decisions.
- Keep the comparison evidence-led without unsupported superiority or absolute-guarantee language.
- Preserve all storefront, WooCommerce, account, checkout, email, and COA business behavior.

**Previous release: 0.25.0-beta.29** — Claude SEO Milestone 4 content batch 2 initial Staging review.

## 0.25.0-beta.29 - 2026-08-19

- Add a dedicated, responsive presentation for WordPress posts in the `Guides` category while keeping guide copy editable in WordPress.
- Add a visual document-check hero, scannable evidence cards, comparison table treatment, section navigation, and archive-first action path for the approved documentation guide.
- Keep Yoast as the Article graph owner while connecting guide author and publisher references to the existing Pep Select Organization entity without inventing personal credentials.
- Preserve all Milestone 4 Batch 1 storefront, commerce, account, checkout, email, and Quality Archive behavior.

**Previous release: 0.25.0-beta.28** — Claude SEO Milestone 4 content batch 1 owner-approved footer revision.

## 0.25.0-beta.28 - 2026-08-19

- Remove the owner-rejected Quality Archive link from the sitewide footer while preserving the approved pre-release independent-testing policy sentence.
- Preserve all Milestone 4 Batch 1 product, homepage, WooCommerce, and COA behavior from `0.25.0-beta.27`.

**Previous release: 0.25.0-beta.27** — Claude SEO Milestone 4 content batch 1.

## 0.25.0-beta.27 - 2026-08-18

- Replace GLP-2T supplier boilerplate with approved Pep Select product copy focused on dual-receptor identity and binding.
- Normalize compound-library matching so `GLP-2T` resolves to the reviewed `glp-2 t` content entry despite punctuation or spacing differences.
- Add one natural `research peptide` use to the homepage body while preserving `compound` as the broader catalog term.
- State the owner-confirmed pre-release independent-testing policy in the footer.
- Preserve all WooCommerce product, stock, price, cart, checkout, payment, order, rewards, and COA behavior.

**Previous release: 0.25.0-beta.26** — Claude SEO Milestone 3 unified Live release.

## 0.25.0-beta.26 - 2026-08-18

- Preserve the complete Live 0.25.0-beta.23 storefront and transactional-email release, including the responsive admin new-order email.
- Return HTTP 404 for impossible `/shop/page/2/` and higher URLs because the complete catalog intentionally renders on one page.
- Normalize WooCommerce's final root JSON-LD context to the exact Schema.org context emitted by Yoast.
- Connect every WooCommerce Offer seller to the existing Pep Select `/#organization` entity without inventing business data.
- Pair with COA Archive 0.7.2, which connects Dataset creator to the same organization and publishes the real WordPress record date.

## 0.25.0-beta.23 - 2026-08-18

- Remove WooCommerce's inherited `Congratulations on the sale!` additional content from the internal order alert.
- Preserve beta.22's date-first subject, operational hierarchy, live order data, direct review action, and responsive presentation.

## 0.25.0-beta.22 - 2026-08-18

- Preserve the complete Live 0.25.0-beta.21 theme baseline, including its SEO, responsive image, access, COA, checkout, and customer-email work.
- Replace WooCommerce's default admin new-order email with the Pep Select transactional canvas and a compact operations-first hierarchy.
- Set inbox subjects from the order creation date and number, for example `[Aug 18, 2026] New order #1519`.
- Keep WooCommerce authoritative for product images, item metadata, quantities, totals, payment, shipping, coupons, rewards metadata, research purpose, customer notes, and addresses.
- Add a direct order-review button and distinct desktop/mobile compositions without changing order or fulfillment behavior.

## 0.25.0-beta.21 - 2026-08-18
- Preserves the exact email templates and email behavior deployed on Live in 0.25.0-beta.16.
- Carries forward the staging-verified Claude SEO milestone 1 trust/crawl work already present in the Live baseline.
- Adds the milestone 2 responsive WebP homepage hero, optimized evidence images, and audited-template render-path improvements from 0.25.0-beta.20.
- Intentionally excludes the later email-development delta that was present on staging but was not part of this SEO deployment authorization.

## 0.25.0-beta.20 - 2026-08-18

- Match the saved hero attachment by its public filename family so the responsive WebP source is selected even when WordPress stores a generated/scaled original path.
- Include the WordPress posts-page request used by the Testing archive in the audited-template asset cleanup.
- Preserve beta.19 as the verified installation baseline while correcting only the two output conditions that its post-deployment HTML exposed.

## 0.25.0-beta.19 - 2026-08-18

- Serve the existing homepage hero artwork through a responsive WebP source set while retaining the Media Library PNG as the fallback.
- Stop loading unused Marquee Addons files on the four SEO performance templates.
- Stop loading block, Jetpack form, and media-player styles on those templates when their components are not present.
- Preserve the staging-verified `0.25.0-beta.18` image fixes and all commerce behavior.

## 0.25.0-beta.18 - 2026-08-18

- Right-size and recompress the two homepage evidence images while preserving their content and layout.
- Reduce their combined transfer size from 421,930 bytes to 96,112 bytes (77.2%).
- Preserve the staging-verified `0.25.0-beta.17` SEO fixes and exact `0.25.0-beta.14` functional baseline.

## 0.25.0-beta.17 - 2026-08-18

- Redirect the retired `/terms-of-service/` route to the canonical `/terms-conditions/` page.
- Replace five visible `[VERIFY DOI]` placeholders with publication-verified citation details and DOI links.
- Preserve the exact staging `0.25.0-beta.14` baseline and all commerce behavior.

## 0.25.0-beta.14 - 2026-08-18

- Restore the approved connected desktop progress treatment while keeping the same status markup safe and readable on mobile.
- Preserve beta.13's WooCommerce-safe carrier, tracking, order, and shipping sections.

## 0.25.0-beta.13 - 2026-08-18

- Rework the shipped-order status, carrier/service, and shipping-address sections as single-source responsive structures so WooCommerce's email processor cannot strip mobile content.
- Preserve the approved beta.12 subject, title, copy, live order data, tracking action, and desktop presentation.

## 0.25.0-beta.12 - 2026-08-18

- Replace the hybrid completed-order email with the approved full-canvas desktop and mobile shipped-order design.
- Lock the inbox subject to `Thank you for choosing Pep Select. Your order is on the way` and use the visible title `Thank you. Your order is on the way`.
- Keep tracking number, carrier, shipping service, tracking URL, product images, items, totals, research purpose, points, and shipping address dynamic from WooCommerce and the existing tracking resolver.
- Preserve order status, shipment integration, customer data, support reply routing, and the existing account and payment-required emails.

## 0.25.0-beta.11 - 2026-08-18

- Increase the supporting payment-link, bank-statement, order-summary, product, total, address, support, and footer text by one pixel for easier reading on desktop and mobile.
- Preserve the approved layout, wording, WooCommerce product and order bindings, Square destination, responsive stacking, and support-routed replies.

## 0.25.0-beta.10 - 2026-08-18

- Add `Reply-To: Pep Select <support@pepselect.com>` to every WooCommerce customer email when another integration has not already provided a reply address.
- Leave the authenticated From address under WP Mail SMTP so the Gmail connection and sender authentication remain intact.
- Preserve the approved beta.9 subject, copy, responsive rendering, WooCommerce data bindings, and Square payment destination.

## 0.25.0-beta.9 - 2026-08-18

- Keep stacked mobile order-summary cells inside their card by including horizontal padding in the declared 100% width.
- Prevent price and total values from being clipped at 390px and narrower email canvases.
- Preserve the approved subject, copy, layout, WooCommerce data bindings, and Square payment destination from beta.8.

## 0.25.0-beta.8 - 2026-08-18

- Lock the customer on-hold inbox subject to `Thank you for your order. Payment is the next step` in source control.
- Keep WooCommerce's database-level additional-content default from adding a duplicate closing message beneath the approved layout.
- Preserve the responsive beta.7 layout, dynamic WooCommerce order and product data, Square destination, and all commerce behavior.

## 0.25.0-beta.7 - 2026-08-18

- Replace the customer on-hold email with the approved Pep Select desktop and mobile payment-required layout.
- Thank the customer, explain that the order is held for 90 minutes, and describe what happens after payment confirmation without punitive wording.
- Show one amber order-total reminder, a named Square button, a visible fallback link, and a desktop-only QR code that opens the same Square payment page.
- Explain that the payment appears as `3BS Holdings LLC` and that an updated order-status email follows confirmation.
- Pull every ordered product name, variation image, parent-image fallback, quantity, line amount, order total, address, research-purpose value, and compatible rewards value from WooCommerce order data.
- Preserve the account-created and completed-order email templates, WooCommerce order status logic, Square link, checkout, payments, shipping, rewards, and customer records.

## 0.25.0-beta.6 - 2026-08-18

- Use `Welcome to Pep Select` as the customer new-account inbox subject.
- Use `Your account is ready` as the in-email title on desktop and mobile.
- Keep the approved account email layout, content, links, sender, and authenticated support routing unchanged.

## 0.25.0-beta.5 - 2026-08-18

- Add a WooCommerce 10.9-compatible customer new-account template matching the approved Pep Select desktop and mobile mockup.
- Preserve WooCommerce-owned names, usernames, recipient addresses, My Account links, and generated-password setup links.
- Add one shared `support@pepselect.com` helper for transactional email recovery and footer links; mail authentication and connection routing remain owned by WP Mail SMTP.
- Keep the account email isolated from the existing on-hold and completed-order templates while the four-email system is implemented one message at a time.

## 0.25.0-beta.4 - 2026-08-15

- Lowers the mobile-only `Compound + strength` callout from `bottom: 18%` to `bottom: 10%`, keeping the Tesamorelin `10 mg` label unobstructed without changing desktop.

## 0.25.0-beta.3 - 2026-08-15

- Treats a published post that Yoast explicitly marks `noindex` as ineligible for the post sitemap, preventing an empty sitemap from appearing in the public index.

## 0.25.0-beta.2 - 2026-08-15

- Initial empty-post sitemap hardening; superseded by `0.25.0-beta.3` after the live crawl found a published noindexed placeholder post.

## 0.25.0-beta.1 - 2026-08-15

- Replace the homepage batch-matching education example with the documented Tesamorelin 10 mg batch `PSTES1071926GX`.
- Use a cool, clear-vial product visual and the matching Freedom Diagnostics COA.
- Correct the callout endpoints so compound strength points to `10 mg`, batch points to the printed lot, and cap/crimp points to the vial hardware.
- Rename the first evidence row from `What it is` to `Compound name` for faster comprehension.

## 0.24.0-beta.1 - 2026-08-15

- Remove the standard post sitemap only while there are no published posts; it returns automatically when the first post is published.
- Keep the WooCommerce Shop archive in the product sitemap and remove its duplicate entry from the page sitemap.
- Give the homepage, Shop, and each product/strength a page-specific HTML and social title built around research-peptide discovery and visible product identity.
- Keep the visible landing-page and product copy, current social image, URLs, and canonicals unchanged.
- Leave the Shop URL, canonical, template, products, SKUs, COA records, and commerce behavior unchanged.

**Previous staging candidate: 0.23.0-beta.1** — Merchant schema and homepage performance.

## 0.23.0-beta.1 - 2026-08-15

- Give the published US no-returns policy one stable schema identifier and reference it from each WooCommerce product offer.
- Keep unsupported reviews, ratings, shipping promises, global identifiers, and validity dates out of structured data.
- Request the responsive 768 px homepage hero source by default so browsers do not begin downloading the 3.4 MB original before selecting a responsive candidate.
- Shorten the educational evidence row to `Blue cap · silver crimp` on desktop and mobile; the factual amber-vial image and alt text remain unchanged.
- Leave Easyship rates, checkout, product records, SKUs, COAs, and shipping behavior unchanged.

**Previous staging candidate: 0.22.0-beta.5** — Mobile pointer clearance at 320 px.

## 0.22.0-beta.5 - 2026-08-15

- Raise the compound-and-strength pointer label slightly so it clears the overlapping COA preview at the 320 px phone breakpoint.
- Preserve the restored pointer geometry at 360, 390, and 430 px.

**Previous staging candidate: 0.22.0-beta.4** — Mobile batch-matching pointers.

## 0.22.0-beta.4 - 2026-08-15

- Restore the cap-and-crimp, compound-and-strength, and batch pointer lines on phone layouts.
- Use smaller mobile annotation pills and move the lower label above the overlapping COA preview.
- Preserve the verified desktop composition, static COA preview, archive CTA, and all commerce and COA behavior.

**Previous staging candidate: 0.22.0-beta.3** — Plain-language batch lesson and static COA evidence.

## 0.22.0-beta.3 - 2026-08-15

- Explain the three matching points in direct language centered on the vial in front of the researcher.
- Shorten the evidence labels to `What it is`, `Identifiers`, and `Which batch` for faster scanning and safer wrapping.
- Replace the screenshot-derived COA preview with a high-resolution web asset rendered from the original laboratory PDF.
- Keep the COA preview static and non-clickable; route the single action to the main Quality Archive at `/testing/`.
- Remove NAD-specific CTA language so the section remains an educational explanation of Pep Select's documentation approach.

**Previous staging candidate: 0.22.0-beta.2** — NAD batch evidence and mobile trust composition.

## 0.22.0-beta.2 - 2026-08-15

- Replace the GLP-3 RT educational example with the documented NAD+ 500 mg batch `ND50026205JP`.
- Show a polished front-and-reverse amber vial pair with blue cap, silver crimp, and the matching batch identifier.
- Link the evidence card and CTA directly to the NAD+ batch record in the Quality Archive.
- Keep the mission and SEO-supporting explanation as indexable HTML, with factual image alt text and a descriptive internal link.
- Recompose the section on mobile so the headline leads directly into the evidence visual before the supporting details.
- Leave the original laboratory report, COA Archive data, product mappings, SKUs, and commerce behavior unchanged.

**Previous staging candidate: 0.22.0-beta.1** — Initial batch-matching homepage trust section.

## 0.22.0-beta.1 - 2026-08-15

- Replace the generic Why Pep Select homepage block with the batch-matching mission: “Match the batch. Match the vial.”
- Keep each half of the mission headline on one intentional line, including at the 320 px phone breakpoint.
- Show a GLP-3 RT 20 mg vial and a factual Freedom Diagnostics report excerpt tied to batch `RT2026205JP`.
- Call out the labeled identity, cap and crimp, and batch record on desktop; present the same evidence as a readable stacked list on mobile.
- Keep the Quality Archive authoritative for current records and testing status; no COA, SKU, catalog, checkout, or other commerce logic changes.

**Previous staging candidate: 0.21.0-beta.7** — About-page search and footer cleanup.

## 0.21.0-beta.7 - 2026-08-14

- Remove About us from the global footer without deleting or rewriting the page.
- Mark only `/about-us/` as `noindex, follow` and exclude it from Yoast XML sitemaps.

## 0.21.0-beta.6 - 2026-08-14

- Remove the exact obsolete duplicate product meta-description tag from final product HTML without changing stored or visible product copy.

## 0.21.0-beta.5 - 2026-08-14

- Suppress the exact obsolete generic product excerpt that a legacy callback emitted as a second meta description.

## 0.21.0-beta.4 - 2026-08-14

- Apply current strength-specific product metadata to Open Graph and Twitter previews.
- Repair stale product canonical and Open Graph URLs to the current WooCommerce permalink.
- Use the current HTTPS WooCommerce image for product social previews.

## 0.21.0-beta.3 - 2026-08-14

- Distinguish same-compound strengths in Product descriptions using the visible WooCommerce strength tag.
- Replace repeated generic product search titles and descriptions with unique, visible-fact metadata.

## 0.21.0-beta.2 - 2026-08-14

- Preserve valid Yoast schema values while repairing stale same-product URLs.
- Remove the empty redirected product-category sitemap from the sitemap index.
- Prefix visible product descriptions with the visible product name for unique, page-grounded Product markup.

## 0.21.0-beta.1 - 2026-08-14

- Consolidate the duplicate Research Compounds archive into the canonical Shop route with a permanent redirect that preserves query parameters.
- Remove the redirected category term from Yoast XML sitemaps and force the coded Compounds navigation item to the WooCommerce Shop URL.
- Replace generic product-schema descriptions with the approved description visible on each coded product page; omit descriptions where no visible source exists.
- Remove WooCommerce's assumed year-end price-validity values unless a real scheduled-sale end date exists.
- Repair stale Yoast product URLs after catalog slug changes, add the truthful Pep Select brand, and connect a minimal OnlineStore entity to the WebSite graph.
- Add About us to the crawlable global footer navigation without changing page copy.

All notable changes to the Pep Select child theme are documented here.

**Live version: 0.20.1-beta.2** — installed and verified 2026-08-14 through the public SEO crawl and WordPress theme replacement result. Treat this line as a dated summary; verify the deployed `style.css` before answering what is live.

Previously live: **0.17.0-beta.1**, deployed to production on 2026-07-23.

## 0.20.1-beta.2 - 2026-08-14

- Promote the existing About Us hero heading from H2 to H1 in server-rendered output without changing its visible copy or styling.

## 0.20.1-beta.1 - 2026-08-14

- Remove the word `synthetic` from the Retatrutide, TB-500, and Tirzepatide compound descriptions while preserving product names, SKUs, COA relationships, and commerce behavior.

## 0.20.0 - 2026-08-12

- Promote the completed 0.20 checkout work from beta to the stable `0.20.0` release.
- Match the rounded corners of the continuous Contact/Shipping/Billing card to the separate Acknowledgments card.
- On desktop, stretch the left and right checkout columns to the same visual height while keeping the approved order panel at 420px and leaving the mobile/tablet stacked flow unchanged.
- Change the checkout action to `Place your order`, remove Fluid's hidden 20px button-wrapper padding, and tighten the visible button gap so it sits closer to the amber payment notice.
- Remove Fluid Checkout's gray shipping-rate hover fill while retaining the soft-blue selected state. Give customer text fields, phone, native selects, Select2 controls, checkboxes, and radios the same cyan border, ring, and accessibility outline as the cash-back field.

## 0.20.0-beta.50 - 2026-08-12

- Finish the selected Shipping state through Fluid Checkout's own checked-option variables, which outrank its high-specificity gray rule without changing any shipping markup or behavior. Center the narrower 1080px desktop checkout wrapper inside Fluid's existing 1140px container.

## 0.20.0-beta.49 - 2026-08-12

- Complete the beta.48 left-column refinement against Fluid Checkout's live cascade: set City through WooCommerce's locale-aware default-address filter so `Town / City` cannot return, and explicitly replace the selected shipping rate's gray background color with the Pep Select soft blue.

## 0.20.0-beta.48 - 2026-08-12

- Refine the left checkout column without changing the completed order-summary panel: Contact, Shipping address, Shipping, and Billing address now read as one continuous white card with a soft shadow; the acknowledgments/privacy card remains separate.
- Narrow the desktop customer-details column while preserving the order panel's existing width. City, State, and ZIP share one row from tablet widths upward and stack on phones; vertical field spacing is reduced slightly.
- Replace the email helper with a visible reminder to use the same email for checkout and payment, covering the Square payment link, order confirmation, and tracking updates. Rename Town / City to City, remove the shipping-phone helper, and shorten Shipping method to Shipping.
- Give the selected shipping rate a soft Pep Select blue fill, cyan border, and cyan selection mark instead of Fluid Checkout's gray selected state.

## 0.20.0-beta.47 - 2026-08-12

- Side cart only: remove Xootix's `Have a Promo Code?` trigger and its matching coupon slider. The full Cart and Checkout coupon fields remain unchanged.

## 0.20.0-beta.46 - 2026-08-12

- Cart page only: hide YITH's duplicate redemption banner injected above the cart while leaving the separate Pep Select cash-back earnings pill unchanged.
- Cart page only: keep WooCommerce Blocks' existing coupon form expanded and hide only its accordion handle, so the coupon input and Apply button are always visible like checkout. Side cart, checkout, totals, products, rewards logic, and all other cart presentation remain unchanged.

## 0.20.0-beta.45 - 2026-08-12

- Enlarge the shared BAC product thumbnail to 72px in both checkout and the side cart so it fills the height of the card content instead of leaving dead space below the image.
- Remove the parsed `30mL –` prefix from the shared upsell price line. Both surfaces now show only the live WooCommerce price, while the full product name remains in the checkbox's accessible label.

## 0.20.0-beta.44 - 2026-08-12

- Give the side-cart BAC offer a distinct 12px gap above the footer buttons instead of letting the card touch them.
- Increase the card subtly with 18px desktop padding, a 56px thumbnail, and half-pixel type increases. Phone padding remains 16px so the switch, label, and price retain enough horizontal room in the 390px drawer.

## 0.20.0-beta.43 - 2026-08-12

- Restore the compact Bacteriostatic Water upsell in the side cart on every storefront page. Its markup and behavior were already shared with checkout, but its presentation lived only in the checkout/cart stylesheet, so pages such as Home rendered a full-width product image and native checkbox.
- Add a small side-cart-only stylesheet, loaded only when the Xootix drawer is available and the BAC product is offerable. The drawer now matches the checkout card: 52px thumbnail, white bordered card, compact copy, branded switch, and one-line price.

## 0.20.0-beta.42 - 2026-08-12

- Hide the redundant billing and shipping country rows while retaining their US value for shipping, tax, and order records.
- Remove YITH's optional date-of-birth checkout field before Fluid Checkout renders it, with a CSS fallback for cached plugin markup.
- Replace remaining WooCommerce/Fluid Checkout pink checkout links with the Pep Select cyan link tone and navy hover/focus tone.

## 0.20.0-beta.41 - 2026-08-12

- Completes the 1000–1199px tablet layout by removing Fluid's surviving desktop float and 450px width from `.fc-inside` and `.fc-checkout-steps`. On iPad Pro, the checkout fields and the following order card now share the same 800px centered container.
- The alignment override is limited to 1000–1199px; the already-verified 390/440px phone geometry and 1200px desktop geometry are unchanged.

## 0.20.0-beta.40 - 2026-08-12

- Treats iPad Pro and other 1000–1199px checkout widths as the stacked tablet flow. Fluid switches to two columns at 1000px but holds the checkout to an 800px wrapper through 1199px, producing a 300px panel with only 242px of usable content at 1024px.
- The complete 420px order panel now follows the checkout fields at those widths. Fluid's full 1140px wrapper begins at 1200px, where the intended desktop panel reaches 420px with 362px of content, so the normal two-column desktop layout resumes there.

## 0.20.0-beta.39 - 2026-08-12

- Extends the beta.38 expanded mobile order flow through Fluid Checkout's real narrow-layout range, 0–999px. Fluid keeps relocating the order table into the top collapsible at tablet widths even while the body still carries its `two_columns` class; the permanent desktop sidebar behavior begins at 1000px.

## 0.20.0-beta.38 - 2026-08-12

- Mobile checkout now follows the Orbitrex reference structure: contact, shipping, billing, and acknowledgments first, followed by one fully expanded order card containing products, discount/cash-back, BAC upsell, totals, payment, and Place order.
- Moves Fluid Checkout's real `#order_review` node back from its narrow-screen collapsible container into the permanent bottom order card. The node is moved rather than cloned, preserving WooCommerce, YITH, coupon, cart-fragment, and form behavior.
- Removes the redundant `Your cart — X items` dropdown from the top of the mobile checkout only after the order table is confirmed in the bottom card. If the script cannot complete the move, Fluid's dropdown remains visible as a functional fail-safe.
- Re-runs the placement after Fluid/WooCommerce fragment refreshes, responsive changes, and structural replacements. Desktop layout is unchanged.

## 0.20.0-beta.37 - 2026-08-12

- Makes the beta.36 cash-back reset survive YITH's full checkout refresh by retaining the last server-rendered redemption configuration in browser session storage. Only YITH's points maximum, dollar maximum, rate method, and WordPress nonce are retained; the values are refreshed whenever YITH renders a new native form.

## 0.20.0-beta.36 - 2026-08-12

- Rebuilds the zeroed cash-back card immediately after removal even when YITH omits its native redemption form from Fluid Checkout's refreshed fragment. The retained values come from YITH's own last server-rendered maximum, rate, and nonce.
- Keeps redemption reusable in that restored state through YITH's own `ywpar_apply_points` endpoint and exact 4.27.0 payload when no native Apply control remains.
- Gives every applied-pill removal an immediate processing state while WooCommerce completes its checkout recalculation.

## 0.20.0-beta.35 - 2026-08-12

- Cash-back balance copy now reads only `(YOU HAVE $X.XX)`; the existing minimum-redemption note remains the single place that states the $5.00 floor.
- The amount control starts at `0`, selects its value on focus, and Max fills the visible field with the live YITH maximum without applying or submitting anything.
- Apply still uses YITH's bound AJAX click, but its native submit fallback is neutralized so it cannot trigger Chrome's leave-site warning. The controls show an immediate busy state and checkout refresh synchronization runs immediately with only a 120ms fallback.
- The amount control is one bordered field instead of a bordered input nested inside another bordered field.
- Applied coupon and cash-back pills now have an explicit vertical list wrapper, so any valid combination stacks one pill per line. Removing cash back rebuilds the redemption card at `0` from the refreshed live balance.

## 0.20.0-beta.34 - 2026-08-12

- Removes Fluid Checkout's surviving quantity stepper from the order-summary line item at the same id-bearing specificity as Fluid's boxed template. The approved mockup and Orbitrex reference show only the quiet `Qty 2` line and `Remove`; quantity editing remains available through `Edit cart`.
- Changes the custom summary's visible tax label from the WooCommerce rate name (`WA State Tax`) to the reference format (`Sales tax (WA)`), using the checkout customer's live two-letter shipping or billing state. Tax calculation and amount are unchanged.
- Confirms the installed beta.33 quantity-to-discount-card gap is exactly 18px. No spacing adjustment is included in this release.

## 0.20.0-beta.33 - 2026-08-12

- Closes the two failures found by the computed-style diff run against installed 0.20.0-beta.32. That pass compared the live panel with the mockup rendered in an iframe, property by property across 36 mapped selector pairs: 186 comparisons, 184 PASS, 2 FAIL, both on the Place order button.
- Place order took 15px padding and weight 700 instead of the mockup's 13px and 600. The legacy M7 rule selects it as button#place_order.fc-place-order-button, which outranks a panel-scoped #place_order, so the panel rule now matches that same shape and wins.
- The line-item cell measured 0px tall while the item content inside it measured 64px, so the quantity line overflowed and sat 69.2px over the discount card, and the mockup's 18px gap measured 0. Two causes: a floated descendant was not contained by the cell, and margin-bottom is ignored on a table cell. The cell now establishes a block formatting context with flow-root and carries the 18px as padding-bottom.
- No other change. DOM order was already correct on beta.32 and matches the mockup exactly: head, items, discount card, applied pill, cash-back card, BAC card, totals, pay.

## 0.20.0-beta.32 - 2026-08-12

- The summary panel is now a clone of checkout-panel-pepselect.html rather than an interpretation of it. Every declaration in the panel block is copied from that file's own <style>, each rule annotated with the mockup class it mirrors, and mapped onto the live element that plays that role. 46 mockup selectors were mapped and all 46 resolve to a live element.
- The 8px inner-card radius approved during M12-17 is REVOKED. The file says 6px, so the cards are 6px and --pep-radius-card-inner is back to 6px. The design-token record is corrected to match.
- Totals rows follow the file's order exactly: discount, subtotal, cash back, shipping, tax, total. The values remain live WooCommerce data dropped into those slots - the subtotal is still WooCommerce's own cart subtotal and is not recomputed to match the mockup's illustrative figure, which would mean printing a number WooCommerce never calculates.
- The payment divider moves to where the file puts it. In the mockup .pay carries margin-top 20px, padding-top 18px and the 1px #D7E1E9 rule, while .paylab carries only its own type and a 12px bottom margin. A .pepselect-pay-section wrapper now plays .pay, so the two map one to one.
- The BAC card was rendering before the applied pill and the cash-back card because it shared priority 10 with the discount card. It moves to priority 30, giving the file's order: items, discount, applied, cash back, BAC, totals, pay.
- Place order takes the file's .cta values, including font-weight 600 and 13px padding, which the theme button rule had been overriding at 700 and 15px.

## 0.20.0-beta.31 - 2026-08-12

- The line-item overlap reported in 0.20.0-beta.30 is fixed in markup rather than forced with CSS. The quantity line was being appended inside .cart-item__name by the woocommerce_cart_item_name filter, so it had nowhere to wrap to and overflowed its parent onto the discount card. It is now rendered as its own element on Fluid's own fc_order_summary_cart_item_details action at priority 95, alongside the product name (10) and unit price (30), so it participates in normal flow and forms its own row. Measured on the deployed build with the new structure simulated: the overlap of -25.2px becomes a clear gap, and elementFromPoint at the boundary returns the containing cell rather than an overlapping element.
- Fluid's inline quantity stepper is removed through the supported path - remove_action on fc_order_summary_cart_item_details priority 90 - rather than hidden. Quantities remain editable through Edit cart, which opens the cart page.
- Two other line-item controls are rendered by Fluid Checkout PRO, whose source is not readable: the duplicate amount (.product-total) and the second remove control (.fc-cart-item-actions, "Remove item"). No hook or setting for either could be verified, so they are suppressed in CSS and that is recorded as a CSS suppression rather than claimed as a source-level removal. After the change exactly one amount and one Remove render per line item.
- Applied discounts now have exactly one removal affordance. Five were found on the deployed build; the pill's x is kept, the item's own Remove is kept for the line item, and Fluid's applied-coupon list entry, its "Remove item" control and WooCommerce's [Remove] in the suppressed totals row are hidden. Fluid's applied-list container is retained because it is the target of an update_order_review fragment; only its visible entries are hidden.
- The notices row is no longer rendered when there is nothing to say. WooCommerce prints a wrapper even with no messages, so a :empty rule could not collapse it and the row was adding 12px between the line items and the discount card.
- Discount card is one flex row with a 10px gap: the coupon input now fills the available width instead of collapsing to 74px, with the mockup's placeholder, border, radius and padding, and an outline Apply button.
- BAC card puts the toggle, "Add to cart" and the price on one row with the price aligned right, matching the mockup order.

## 0.20.0-beta.30 - 2026-08-12

- (A) The payment heading had reverted to the panel heading's type - Plus Jakarta Sans 20px/600 navy, left aligned - instead of mono 12px, 2px tracking, #B46A00, centred between amber rules. Fluid gives that element both fc-checkout-order-review-title and pepselect-payment-title, and 0.20.0-beta.29 re-declared the panel heading rule later in the stylesheet than the payment-title rule; at equal specificity the later declaration won. The heading rule now excludes .pepselect-payment-title explicitly, so the two cannot collide again whatever order they appear in. Restored and verified: IBM Plex Mono, 12px, 2px letter-spacing, rgb(180,106,0), centred, over a 1px #D7E1E9 divider.
- (B) Edit cart rendered below the heading rule instead of beside the heading. Fluid's markup is div.fc-checkout-order-review__head holding the h3 and the link as siblings, and 0.20.0-beta.29 applied the flex row and the border-bottom to the h3, which contains only the words. The row and the rule now belong to the head wrapper. A second cause was found on measuring: the wrapper carries flex-wrap:wrap and the h3 computed to the full 362px, which pushed the link onto its own line even once it was inside the row; the row is now nowrap and the heading sizes to its text. Verified: heading and link share a baseline, rule spans the full 362px content width.
- (C) NOT FIXED, and no change shipped. The line item's quantity line overlaps the discount card. It was measured at -25.2px (the card begins above the qty line) and reproduces in plain block flow, so it is structural and predates this build rather than being caused by the flex layout added in 0.20.0-beta.29. Three different layouts were tried against live measurements and each left an overlap; rather than ship an unconverged layout change to a live checkout, the line item is left exactly as 0.20.0-beta.29 rendered it and the defect is reported. See the delivery note for the likely cause and what is needed to settle it.
- This release is CSS only. No PHP, JavaScript, markup, validation, order meta or payment behaviour is touched.

## 0.20.0-beta.29 - 2026-08-12

- The three inner cards rendered transparent with square corners in 0.20.0-beta.28 because the commercial-surface tokens added in that release were written AFTER the closing brace of :root in foundations.css, so they were never defined. An invalid var() resolves to unset rather than falling back to an earlier declaration, so background and border-radius were dropped entirely even though a literal white rule existed above. The tokens now sit inside :root, and every var() in the panel carries a literal fallback so a missing token can never blank a card again.
- Fluid's .fc-checkout-order-review__inner was still painting a white card of its own inside the panel (white fill, 1px border, 8px radius, 12px/20px padding, 20px bottom margin). It does not exist in the mockup. Its styling is stripped to nothing while the element itself is kept, since it is Fluid's structure.
- The width cascade is removed. Cards were inheriting a chain of insets - Fluid's inner wrapper, the review table, and the table cell's 10px/20px padding - which left them 84px narrower than the panel. The review table, its body, the summary rows and their cells are now full-width layout boxes with no padding, and the coupon and BAC cells carry the panel-cell class that was previously only on the pills, redeem slot and totals. All three cards now measure 362px, flush to the panel padding.
- The heading rule was sitting on the "Your Order" text, so it stopped after 104px. It now belongs to the heading row and spans the full content width, with Edit cart on the same baseline.
- Line items follow the mockup: no thumbnail, name and strength pill on the left with the amount right, and a single quiet "Qty N  Remove" line beneath. The quantity stepper is hidden on the checkout; the remove link uses WooCommerce's own cart remove URL.
- BAC card puts the toggle, "Add to cart" and the price on one row; the redemption card orders its controls input, Apply, Max.
- Card radius is 8px, per Paulo's instruction for rounder edges. This differs from the mockup, which specifies 6px, and the token --pep-radius-card-inner is updated to 8px so the recorded design language and the built surface agree.

## 0.20.0-beta.28 - 2026-08-12

- M12-16 finishes the panel rebuild and removes the cause of it failing. 0.20.0-beta.27 added the correct rules but they never took, because panel blocks from M12-6 through M12-11 were still in the stylesheet and several of their selectors carried .fc-checkout-order-review while the newer ones did not. A scoped !important rule outranks an unscoped one, so the older rules kept stripping the inner cards back to transparent. Those blocks are deleted rather than out-specified, and every rule in the replacement is now scoped to the panel.
- Inner cards render as specified: white, 1px #D7E1E9, 6px radius, 16px padding, 4px bottom margin, on all three of discount code, redeem cash back and BAC water. A DISCOUNT CODE label is added in mono 11px with 1.54px tracking, and the coupon placeholder now reads ENTER DISCOUNT CODE.
- The cash-back card and the stray YITH points bar had a single shared cause, introduced in 0.20.0-beta.27: the redemption slot is rendered server-side and starts empty, and the script treated its presence as proof the card was already built. It skipped building, then threw on the balance lookup; the catch stripped html.pep-redeem-ready, which is the class that hides YITH's native bar. So the card never appeared and the raw points bar reappeared. The script now tests for the card itself, and the bar is additionally suppressed unconditionally on checkout so a script failure can never expose it again.
- Double amber container fixed. The legacy M7 treatment on .payment_box.payment_method_bacs was wrapping the new amber block, giving two nested amber frames. Its selector li.wc_payment_method.payment_method_bacs .payment_box outranked a panel-scoped override, so the rule is deleted. The element itself is untouched - WooCommerce and the gateway depend on it - and is now a transparent pass-through, leaving .pepselect-pay as the only amber container.
- Heading is title case at 20px/600 navy with the 14px/24px rule beneath it; the payment heading reads PAYMENT, centred between 1px #E8C99A rules. Totals rows carry 6px padding-top and no borders, and the TOTAL row alone has its top border with 18px/600 mono navy on both label and value. The checkout quantity stepper is hidden in favour of a single quiet quantity line with a Remove link.
- Design language recorded. foundations.css gains --pep-radius-card-inner (6px), --pep-surface-card, --pep-surface-panel, --pep-color-quiet, --pep-color-placeholder, --pep-totals-row-gap and --pep-totals-total-gap. pep-select-design-tokens.md gains a Commercial Surface Conventions section, and AGENTS.md gains a short Visual Conventions list pointing at it. Panel width, panel radius, label and pill tracking, the BAC thumbnail size and the amber border widths were judged one-off and left in checkout.css.

## 0.20.0-beta.27 - 2026-08-12

- M12-14 rebuilds the order summary panel to the approved mockup (checkout-panel-pepselect.html) rather than adjusting it further. Panel geometry is now 420px wide, 28px padding, #F3F8FC on a 1px #D7E1E9 border at 16px radius. The heading reads "Your Order" with the Edit cart link beside it, over a 14px/24px rule.
- The totals are no longer WooCommerce table rows. Fluid renders a tfoot in each of the two totals tables it produces, and those tfoots are suppressed so exactly one visible set of totals remains: a flat list of divs rendered server-side on woocommerce_review_order_after_cart_contents. Because the list re-renders with every update_order_review fragment it stays in step with the cart without any client-side syncing. Row rhythm is the mockup's: 6px padding-top, 13px, #5C7086, no borders on any row, credits in #17A1CF, and a border, 14px padding-top and 12px margin-top on the TOTAL row alone, in 18px mono navy for both label and value.
- Specificity, recorded because it has cost time twice: Fluid's boxed design template sets cell padding behind an id selector and marks it !important, so it can only be beaten by !important at equal-or-greater specificity. Each block therefore renders into one wrapper cell (.pepselect-panel-cell) that carries the id-matching selector and !important once; everything inside those cells is plain divs and needs none. That is the whole of the !important use for panel spacing.
- Anything applied is now a pill with a close control, one rule for both coupons and cash back. The close control reuses WooCommerce's own remove markup (class woocommerce-remove-coupon plus data-coupon), so removal runs through the path already working in production and the href stays the "#" this theme rewrites, meaning a click can never navigate. The redeem card is not rendered at all while cash back is applied and returns when the pill removes it. "Enter 0 and Apply" is deliberately not implemented as a removal path.
- Inner cards - discount code, redeem cash back, BAC water - are white on the tinted panel, which is what stops the card-in-a-card effect that repeated tint-on-tint attempts produced. All three share one treatment: #FFFFFF, 1px #D7E1E9, 6px radius, 16px padding, 4px bottom margin, with mono 11px/1.54px labels.
- The payment block keeps the live copy verbatim. Diffed against the live strings: all four parts are word-identical to the mockup, and the only change is structural - the first sentence becomes a bold headline with the sentence after it as a paragraph, and the exact-amount instruction moves into a white inset box with the amount in 14px mono amber. The PAYMENT heading is centred between amber rules. No emoji: the mockup's emoji variant is presented there as a rejected option and the brief forbids it.
- Known deviation from the mockup, deliberate: the mockup lists Discount above a reduced Subtotal ($159.98 less $16.00 shown as $143.98). Matching that literally would mean printing a subtotal WooCommerce never calculates. The values stay WooCommerce's own, so Subtotal is the real cart subtotal and discounts sit below it; only the row order differs.

## 0.20.0-beta.26 - 2026-08-12

- (A) Fixes the 0.20.0-beta.25 regression where applying cash back did nothing. The cause was the background-fetch recovery added in that release: YITH binds its Apply handler only to forms present at page load, so a form recovered from a fetch is inert. Clicking its button fell through to a native form POST, which navigated the page and applied nothing. That recovery is removed and redemption again triggers YITH's own bound Apply control. Verified on production: with ywpar_input_points set to 500, clicking YITH's button produced a $5.00 discount and no navigation, while form.requestSubmit() fired a submit event and applied nothing - a native POST is not a substitute for the click YITH listens to.
- (B) NOT FIXED, and deliberately not papered over. After an applied redemption is removed over AJAX, YITH does not re-render its apply form, so the card has nothing to rebuild from and the button returns only on the next page load. Reproduced on production: remove succeeded (total 17640 -> 18386) and form.ywpar_apply_discounts was absent afterwards. Restoring the button without a reload needs either a YITH-bound form, which only a page load produces, or a reimplementation of YITH's private AJAX apply contract, which is not readable from a premium plugin. 0.20.0-beta.25 attempted the former by injection and broke apply outright; that approach is not reinstated.
- (E) Redemption is now a variable amount: a dollar field with Apply and Max, replacing the all-or-nothing button. The dollars-to-points conversion lives in exactly one function; the rate is derived from YITH's own field pair (ywpar_points_max against ywpar_max_discount) rather than assumed, so it cannot drift if the store's rate changes, and the result is always clamped to the server maximum. Max writes ywpar_points_max straight through with no conversion round-trip. The $5.00 minimum is enforced in the field. The value POSTed to YITH remains points, unchanged. Verified on production against a 1120 point / $11.20 balance: $5.00 gave discount 500, $7.00 gave 700, and $20.00 clamped to 1120 ($11.20), each with no navigation.
- (C) The coupon field and its Apply button render expanded in the summary panel with no "Add coupon code" toggle, using Fluid's own expansible-section arguments (initial_state expanded, handle suppressed) plus fc_coupon_code_field_initially_expanded. This is a filter, not an admin setting, so there is no settings path for Paulo here.
- (D) The BAC water upsell now renders in the side cart between the totals and the buttons, on Side Cart WooCommerce's own xoo_wsc_before_footer_btns hook, so no template override is carried in the theme. This corrects the M12-12 report that no hook existed below the totals: that was read from xoo-wsc-footer.php alone and missed the nested global/footer/buttons.php, which fires it. The existing resolver is reused, so out of stock or unpurchasable renders nothing, and the panel is suppressed when the product is already in the cart.

## 0.20.0-beta.25 - 2026-08-12

- (B) The cash-back block no longer renders empty after a redemption is removed. YITH only prints its apply form on a full page load, so a page opened with a redemption applied had no form anywhere in the document; removing over AJAX left the card with nothing to rebuild from, and the button only returned on reload. The script now fetches the checkout once in the background, lifts form.ywpar_apply_discounts out of the response into a hidden holder, and rebuilds the card in place. It asks only once per removal, and stops asking if a fresh load has no form either, which is the genuine below-minimum case.
- (A) The applied-coupon Remove control can no longer navigate. WooCommerce renders it as a real link to ?remove_coupon=<code> and Fluid's footer-loaded script is what cancels that default, so a click landing before the script binds followed the href and left checkout with the discount still applied. The href is rewritten to "#" through woocommerce_cart_totals_coupon_html. This deliberately drops WooCommerce's no-JS removal fallback, which is already academic on a checkout that needs JavaScript for Fluid, the acknowledgment validation and the redemption card.
- (E) YITH's redemption form no longer renders on the cart. On /cart/ it printed inside wp-block-yith-par-message-reward-cart above the cash-back pill, duplicating the checkout UI. That block is dropped on the cart only, through core's render_block filter, with no YITH files edited. Verified on production that the block contains only the nonces and the form, not the earn message, so the pill is untouched. Checkout redemption is unaffected.
- (F) Pink (rgb(204,51,102)) removed from YITH's Apply Points button and its related controls, and from the block-cart item image link, pinned to brand tokens and scoped to those controls rather than a blanket anchor restyle.
- (G) The BAC upsell toggle now reads "Add to cart" and the price moved to its own line beneath the subline, where it stays visible. The subline is restored to "Compounds ship as lyophilized powder." - the "Reconstitution Solution - for Laboratory Use." wording was adopted only for the payment processor application, which is no longer proceeding. Confirmed against HANDOFF-processor-compliance-wording.md row 6, which records the original wording and marks the change revertible.
- (C) and (D) needed no change and none was made. Measured on production beta.24: the inner wrapper is 369.5px inside a 427.5px panel, with zero gap on either side, which is exactly the panel border-box minus its two 1px borders and 28px padding - the 372px figure omits the borders. The totals divider added in 0.20.0-beta.24 is present and applied, tr.cart-subtotal carrying border-top 1px solid rgb(215,225,233) and 20px padding-top.

## 0.20.0-beta.24 - 2026-08-12

- (A) The order summary is one card again. 0.20.0-beta.23 cleared the inner wrapper's background, border and radius but left the drop shadow Fluid paints on it, so it still read as a floating panel inside the blue card. The shadow is removed, including the separate flyout-open variant that sets its own. Source confirmed as Fluid's checkout-steps-424.min.css rule on div.woocommerce .fc-wrapper .fc-checkout-order-review .fc-checkout-order-review__inner, not theme CSS.
- Correction to the reported cause: the inner wrapper was not narrower than the panel content box. Measured, its width already equals the panel's border-box width minus its two 1px borders and 28px padding on each side (242px inside a 300px panel at the width tested; 428px minus 58px gives the 369.5px reported on production). No width change was made or needed - the inset edge was the shadow alone.
- (C) A hairline divider now separates the cash-back block from the totals, on tr.cart-subtotal, using the same 1px --pep-color-border treatment as the dividers above it, with 20px of breathing room either side (padding-bottom on tr.pep-redeem-slot, padding-top on the subtotal row). foundations.css defines no spacing token, so this follows the px scale already used by the dividers in checkout.css.
- Unchanged by request: the hairline dividers above, the centred content alignment, the amber Square panel and its own card treatment, and the BAC product image and its side-by-side layout. This release is CSS only - no PHP, markup, validation or order-meta changes.

## 0.20.0-beta.23 - 2026-08-12

- (A) The orphaned "Payment method" heading in the left column is removed - the payment box it labelled moved to the summary panel in 0.20.0-beta.22, and the block below it carries its own Acknowledgments heading. Done through Fluid's fc_register_checkout_substep_args filter by setting the payment substep title to null, which Fluid supports (it does the same when the coupon section title is disabled), so the substep still registers and step structure is intact. The relocated payment section now has its own heading in the panel, "Payment method", reusing the panel's existing h3.fc-checkout-order-review-title treatment set by "Order summary". No new copy.
- (B) One card. The summary panel is now the only filled surface: the coupon and cash-back blocks lose their tint, border and radius and are separated by hairline rules and spacing. Applied discounts remain pills, now sitting directly on the panel instead of inside a nested box. The cash-back block was stripped alongside the coupon block for the same reason, though it was not in the reported nesting - it carried the identical treatment from 0.20.0-beta.20.
- (C) The BAC upsell shows the product image to the left of the text, 60px square (48px below 480px), with the small radius token. The image is resolved from the same WC_Product the block already uses for price and stock - no second lookup, no hard-coded attachment id or URL - and is rendered only when the product has a featured image, so a product without one degrades to the previous text-only block rather than a placeholder. Verified before building: product 1339 (SKU BACW30) has featured image 1344 on both staging and production.
- (D) Checkout notices move out of the full-width banner at the top of the page and render inline in the summary panel, directly above the coupon block, as a line of text. woocommerce_output_all_notices is unhooked from woocommerce_before_checkout_form (priority 10) and called on woocommerce_review_order_after_cart_contents at priority 15, between the BAC row (10) and the coupon row (20). This covers every cart-level checkout notice, so reward-applied, coupon-applied and coupon-removed all behave the same way.
- (E) With a single gateway, the payment label row and its radio are visually hidden and the amber Square panel is untouched. The radio remains in the DOM and is not disabled, so the gateway still posts. Scoped to payment_method_bacs, so adding a second gateway would restore visible labels.
- (F) Not done in the theme, and deliberately: the collapsible substeps are governed by a Fluid setting, not by theme code. See the release notes for the exact path.
- (G) A per-item batch number is still not built. The storefront knows only a compound's current batch while the vial that ships is chosen at fulfilment, so printing it would tell a researcher they are receiving batch X when they may receive batch Y. Confirmed again that the Store API cart item exposes item_data: [] - no batch data reaches checkout.
- (H) The "Live version" line at the top of this file was stale (0.19.0-beta.8) and has been corrected to 0.20.0-beta.17, verified by reading style.css directly from production. Per-version entries were already complete from 0.20.0-beta.1 onward; nothing was missing.

## 0.20.0-beta.22 - 2026-08-12

- M12-9 payment and consent swap columns. The order summary now ends at TOTAL, then the payment method, the Square instruction panel and Place order. The privacy paragraph, Research Purpose field and Acknowledgments move to the left column, into the slot the payment section vacated.
- Hooks: payment is unhooked from fc_checkout_payment (priority 20) and rendered on fc_place_order at priority 5, ahead of Fluid's own place-order output at 10, so it lands in the same container as the button, immediately above it. A run-once guard is used instead of the $is_sidebar argument, so the box renders exactly once wherever the button is rather than risking no payment box at all. The consent block moves by retargeting its two existing render hooks to fc_checkout_payment (Research Purpose 10, checkboxes 20); the privacy paragraph moves with it via checkout/terms.php on the same action at priority 5, after unhooking Fluid's fc_checkout_place_order_terms output.
- Only the hook targets changed in inc/checkout-fields.php. The acknowledgment markup, labels, links, client and server validation, and the order meta are untouched, as is the Square panel copy and its amber treatment.
- Relocation was proven safe before the code was written by moving the live nodes on the deployed beta.21 and re-running the validation against the moved DOM: focus landed on research_purpose, no submit was attempted, zero checkout AJAX calls, all three fields carried aria-invalid=true and their aria-describedby, and exactly three error nodes were visible.
- Verified before the change on beta.21, staging order #1443: cash back $7.20 applied as coupon ywpar_discount_1, total $98.67 -> $91.00, Square instruction panel read $91.00 (the discounted total), acknowledgment meta written (both flags Yes, 2026-08-12T06:24:14-04:00, wording version 11b0a95ea858, Academic Research), balance $7.20 -> $0.00, and cancelling the order restored it to $7.20.
- Known and unchanged: three remove-coupon controls exist when a coupon is applied - one in the relocated coupon card's applied list (the one carrying Fluid's live-refresh fragment) and one in each of Fluid's two order-summary tables, of which the second is hidden. The duplication is Fluid's own dual-summary rendering; it is left alone because removing either row risks the removal handler repaired in 0.20.0-beta.21.

## 0.20.0-beta.21 - 2026-08-12

- Coupon card completeness fix. Moving the coupon section into the order summary in 0.20.0-beta.20 suppressed Fluid's coupon substep, which also suppressed the two auxiliary containers Fluid prints through fc_before_substep_coupon_codes: .fc-coupon-code-messages (where apply and remove errors are rendered) and .fc-step__substep-text-content--coupon-codes (the applied-coupon list, and the target of Fluid's woocommerce_update_order_review_fragments entry). Both are now printed inside the relocated card.
- Effect of the omission: coupon removal did reach the server and did clear the cart, but the click produced no loading state (Fluid blocks the applied-list container, which was null), the applied-coupon list never refreshed in place, and any coupon error - including an individual-use conflict between a promo code and a YITH cash-back redemption - was discarded with no message shown. Removal verified working on the deployed beta.20 before this change: coupons [welcome10] -> [], total 9015 -> 9867, fc_remove_coupon_code posted, no console errors, state persisted across a reload.
- Applied coupons in the relocated list render as removable pills consistent with the rest of the panel.

## 0.20.0-beta.20 - 2026-08-12

- M12-8 (A) TOTAL row typography. Subtotal/Shipping/Tax were already 13px/400 with mono figures from M12-6 (measured on beta.19), so the remaining fault was the TOTAL label itself, still Plus Jakarta Sans 15px/600 while its figure was mono 18px/600. The whole TOTAL row is now IBM Plex Mono 18px/600 navy, so it reads as the answer.
- M12-8 (B) The checkout progress bar is removed from the DOM rather than hidden. Fluid renders it via FluidCheckout_Steps::output_checkout_progress_bar() on woocommerce_before_checkout_form priority 4; that action is now unhooked on wp at priority 200 (after Fluid's own late hooks), so .fc-progress-bar and its children are no longer emitted. Guarded against a missing or renamed class.
- M12-8 (C) BAC water upsell, coupon code and cash back now render inside the order summary below the line items, via woocommerce_review_order_after_cart_contents (BAC priority 10, coupon priority 20; cash back is placed by the existing checkout-redemption.js immediately above the totals). That hook fires inside the review table's tbody, after the line items and before the totals in tfoot, so the resulting order is items, BAC, coupon, cash back, totals, TOTAL, then payment and Place order.
- The coupon section is moved using Fluid's own fc_coupon_code_displayed_as_substep filter to suppress its payment-step substep, then rendering the same FluidCheckout_CouponCodes::output_section_coupon_codes_fields() inside the summary, so Fluid's AJAX apply/remove endpoints are untouched and the section is not duplicated. Coupon and cash back share an inner-card treatment (cyan-soft tint, 1px border, 6px radius, 16px padding) and applied discounts render as removable pills.
- Not included: the payment method and Square panel remain in the left column. Moving them is the one change in this brief that can alter the money flow, and it cannot be exercised until the build is installed, so it is held for a separate isolated release rather than bundled with four other unverified moves. Acknowledgments, their validation, the capture-phase guard and the order meta are untouched.

## 0.20.0-beta.19 - 2026-08-12

- M12-7 (F) Restore the Military & First Responder discount. The footer link, hidden in 0.20.0-beta.9 for processor compliance, is restored as the last entry in the footer Support group (label "Military & First responder discount", linking to /military-discount/). The page's noindex is removed - the three page-gated robots filters (wpseo_robots_array, wpseo_robots, wp_robots) are deleted from inc/military-page.php, so /military-discount/ is indexable again. The unused pepselect_child_get_military_url() helper is deleted (it was never called; the footer links via pepselect_child_get_page_url). The page template and the VerifyPass button in the page content are untouched. The restore is recorded in HANDOFF-processor-compliance-wording.md section 4.

## 0.20.0-beta.18 - 2026-08-12

- M12-7 checkout order-summary panel fixes. (A) The order review showed a card inside a card: the M12-6 styling added an outer card on .fc-checkout-order-review while the theme already styled the nested .fc-checkout-order-review__inner as its own panel (white fill, 1px border, 8px radius). The inner panel is now flattened (transparent, no border, no radius) so only the one outer card renders.
- (B) The Country / Region field is hidden on the checkout. The store ships US-only and the control is a single-option ("US") select, so hiding the row with display:none leaves billing_country and shipping_country posting "US" unchanged - the value is not cleared. State / County is left visible. Verified end to end before this change on staging order #1435: Country recorded United States (US), State Washington, WA state tax applied, acknowledgment meta written, totals correct.
- (C) The line-item thumbnail is removed from the order summary and each line now shows the compound strength as a pill after the name (e.g. "GLP-3 R 10MG"), with the price right-aligned. The pill reuses the existing homepage/archive strength resolver (pepselect_child_get_product_strength_label, product_tag based) via a woocommerce_cart_item_name filter scoped to the checkout only, and is styled to mirror the compound card badge (IBM Plex Mono, bordered, uppercase).
- Out of scope and untouched: the Acknowledgments block and its validation/meta, the Research Purpose select, the BAC upsell, the Square instruction panel and its amber framing, the cash-back dollar conversion and the points posted to YITH, and Fluid's four-step flow. (D) A per-item batch number was requested as report-only and is not built - see the delivery note; the storefront cannot know the batch that will actually ship.

## 0.20.0-beta.17 - 2026-08-12

- M12-6 checkout cleanup + summary-card overflow fix (CSS only, no flow changes). Removed the "STEP n OF 4" Fluid progress bar; hid YITH's optional date-of-birth field (input[name=yith_birthday]) and its row wherever it renders; fixed the two pink (rgb(204,51,102)) link leaks - a.expansible-section__toggle-plus and a.woocommerce-privacy-policy-link now use brand cyan with no underline, scoped to those controls only.
- Summary-card overflow: at intermediate widths the two-column layout squeezes the right sidebar below the review table's natural width, crowding line-item prices against the rounded border. Fixed by constraining the content to fit - product image capped at 44px, table forced to 100% width, cells wrap - rather than clipping with overflow:hidden, which would risk the Research Purpose select2 dropdown that renders inside the card. Verified at the 1076px squeeze width: no element extends past the card's right border and the document has no horizontal overflow.
- NOT done in this release, by deliberate scope decision under "a working checkout beats a matching layout": the Orbitrex column restructure (moving payment method + Square panel + place order into the right panel, moving the BAC upsell and coupon into the right panel as inner cards with removable pills, moving acknowledgments to the left column). That relocation touches the money-critical payment/Square/acknowledgment-validation flow and must be built and verified as a separate, staged pass. The exact Fluid hooks and risk ranking are recorded in the task report.

## 0.20.0-beta.16 - 2026-08-12

- M12-6 checkout order-summary panel + redemption form styling. The right-hand order review (.fc-checkout-order-review) had no card and every row fell back to the browser system font (-apple-system) at one weight, so nothing read as the answer. It now sits in a card (surface tint matching the card panels, border and 12px radius from tokens, 28px padding / 20px at 767px) with the inherited system font replaced: labels and product names in Plus Jakarta Sans, all currency figures in IBM Plex Mono, Subtotal/Shipping/Tax reduced to 13px/400 slate, and the TOTAL promoted to an 18px mono navy figure so it is the clear focal point.
- The newly-working YITH redemption form was unstyled and rendered in a bare band between the progress bar and the Contact card, framed in points. A new script (assets/js/checkout-redemption.js) hides YITH's native form and presents a themed, dollar-framed redemption card inside the summary, below the line items and above the totals, matching the reference placement. Copy is reframed to dollars: "Cash back / {balance} available", an "Apply {amount} cash back" button, and "Minimum redemption is $5.00."; the applied totals row label is reframed from "Redeem points" to "Cash back applied".
- Money safety: the amount POSTed to YITH stays in points. The card applies the full available balance by leaving ywpar_input_points at its own server-set maximum (ywpar_points_max) - no points<->dollars conversion is performed on the posted value - and the card's Apply button only triggers YITH's own hidden Apply control, which reads the untouched points field. Remove is YITH's native totals-row control. Verified on staging: applying redeems the exact balance ($7.20 from 720 points), the order total drops to the discounted figure, the Square instruction panel shows the discounted total, removing restores it. YITH re-renders its apply form only on a full page load, so after removing a redemption the card returns on the next page view - this is YITH's native behaviour, mirrored, not a regression.
- Fail-safe: the native form is hidden only while html.pep-redeem-ready is present, a class the script adds after it takes over; if the script does not run or errors, the native form stays visible so redemption is never left without a control. Out of scope and untouched: the Acknowledgments block and its validation/meta, the BAC upsell, the Square instruction panel, and Fluid's four-step flow. The redemption form's raw-points copy from M12-5 is resolved here.

## 0.20.0-beta.15 - 2026-08-11

- M12-1 regression fix: the checkout compliance validator is now independent of Fluid Checkout, and the beta.13 stripFluidRequired approach is removed. Code path that was at fault: our messages are written in setError() inside validate(), which beta.13-14 fired only from the WooCommerce checkout_place_order jQuery event. That event only reaches us if Fluid lets the submit through its own pass; Fluid selects fields for that pass by the validate-required class. stripFluidRequired removed that class from the three fields to silence Fluid's duplicate, which coupled our validator to Fluid's pass - if the class was ever present at click time, Fluid could gate the submit before checkout_place_order and our errors would go silent. The validator never read validate-required itself; the dependency was indirect, through Fluid's pass.
- The fix adds a capture-phase guard: a document-level click listener on #place_order and a form submit listener, both in the capture phase, run validate() before Fluid's handlers and - only when a field is invalid - block the event (preventDefault + stopImmediatePropagation) and show our messages. Nothing in this path reads validate-required, so stripping a Fluid class can never silence the errors again. When all three fields are satisfied the guard is a no-op and Fluid's normal validation (address fields, etc.) runs unchanged. The checkout_place_order handler is kept as a belt-and-suspenders third layer.
- validate-required is no longer stripped, so the three fields are properly required in Fluid's model again. Verified on Live with real (trusted) Place order clicks, all three empty: the three inline errors appear together, focus moves to Research Purpose, aria-invalid="true" and aria-describedby are set on all three, exactly one message per field, and Fluid's generic "This is a required field." appears zero times - so no duplicate is reintroduced and no Fluid suppression was needed. When the three fields are filled, validate() returns true and the guard does not block.
- No other files changed. Server enforcement is unchanged: the bypass POST to /?wc-ajax=checkout with the three fields stripped still returns result:failure with all three messages. Validated with node -c. The M12-2 account page (four cards, hidden nav, empty-orders state, no console errors, no horizontal overflow) is unaffected and was re-confirmed on Live.

## 0.20.0-beta.14 - 2026-08-11

- M12-2 account page redesign. /my-account/ is now one scrolling page of four cards - welcome + sign out, My information (name, phone, default shipping address), Cash back (dollar balance, lifetime earned, plain-English earn/redeem), and Your orders inline (per order: number, date, status, total, cash back earned, shipment tracking when present, and every line item with quantity and price, so the contents of an order are visible without a click-through). The left account navigation rail is hidden by CSS; this is presentation only - all seven endpoints (/my-account/, /orders/, /edit-address/, /edit-account/, /cash-back/, /customer-logout/, /view-order/{id}/) still resolve directly and are reachable from the cards and the site header. Verified all seven load.
- Tracking on order cards reuses the existing pepselect_child_get_order_tracking() resolver (no second parser). It anchors on order notes matching /track|tracking|shipped/ and takes the first token of 6-40 chars with at least four digits; for the one genuinely shipped order (#1290) this resolves 9205590323035200223379 from the Easyship note, and orders that never shipped (#1263, #1388) resolve nothing and render no tracking row at all - absence is correct, not a fallback. The note's tracking link is a bare domain with no number, so the number is shown as plain text, never linked. Degrades to nothing rather than erroring if the note format changes. The anchoring substrings are recorded in a code comment for the day Easyship changes its wording.
- Points no longer leak into the customer view. (a) YITH's per-order "Points earned: N" on /view-order/ is converted to dollars (480 -> $4.80) by a scoped output buffer on the view-order endpoint that rewrites only the ywpar-order-point-summary paragraph; YITH plugin files are untouched and the buffer degrades to unchanged output if the markup is absent. (b) The POINTS column of YITH's account history table (Table 1, my_account_orders) is converted to dollars server-side via DOMDocument on the captured shortcode HTML before render - not in the DOM - and the header/data-title become "Cash back"; the share-coupon table's VALUE column is left alone as it is already dollars. Reason cells that exposed a raw coupon code ("Created coupon: xxxx-...") are reworded through the existing pepselect_child_cashback_reason_label(); recognised human reasons (Order Completed, Order Cancelled, Target achieved - Daily Login) are left exactly as YITH renders them.
- Per-order cash back earned is summed from YITH's own points log by order_id (the same authoritative source as the balance and totals), so no per-order plugin meta key is assumed; when the log carries no order reference the line is simply omitted. get_cashback_history() now carries order_id for this.
- A4 pink-link fix: the /view-order/ line-item product name inherited an underlined pink (rgb(204,51,102)) link. It is pinned to the brand palette (text-decoration:none + navy, cyan on hover), scoped to the order-details product-name anchor only - not a blanket account-anchor restyle. Selector 2 (the side-cart "Change address" button) already has no decoration and was left alone; selectors 3 and 4 ignored as instructed.
- Design: existing tokens only (navy #002A53, cyan #17A1CF, Georgia card headings, Plus Jakarta Sans interface, IBM Plex Mono figures, radii 8/12/20/999, 180ms motion with a reduced-motion block). Status pills are colour-grouped from the existing palette (completed green, processing/on-hold/pending amber, cancelled/failed/refunded red). Empty and edge states all render text: no orders shows a prompt plus a Browse compounds button; zero balance shows $0.00 with the explanation; no saved address shows a note plus an Add an address link; a missing phone shows "Not added yet"; many line items stack and wrap; a cancelled order shows a red status pill. The account frame was switched to border-box so the gutter padding no longer adds to the width, capping content at the intended 1200px and removing a small horizontal overflow at 390px. The logged-out login form (Nextend Social Login Pro Google button) is unchanged and still renders its button.
- M12-1 validation polish: no code change was needed this release - the Fluid Checkout duplicate "This is a required field." message was already resolved by beta.13's stripFluidRequired, which removes the validate-required class from only the three compliance fields so Fluid skips them while every other field keeps the class and is still validated. Confirmed live on the deployed build: running Fluid's validateAllFields plus the custom validator on the three empty fields yields zero occurrences of Fluid's generic message and exactly one custom message per field, and every normal required field still carries validate-required. Not regressed.
- Validated with php-parser (dashboard.php, account.php, cash-back.php all parse), CSS brace-count (balanced), and no JavaScript changed. The tracking parser and both points conversions were proven offline against the verbatim samples. The dashboard was rendered from the real foundations.css + account.css at 1200px (two-column) and inside a 390px viewport (single column, 2-column line items, no horizontal overflow). The server-side checkout bypass POST to /?wc-ajax=checkout with the three fields stripped still returns result:failure with all three messages. No console errors on /my-account/.

## 0.20.0-beta.13 - 2026-08-11

- M12-1 polish: removed the duplicate validation message on the three checkout compliance fields. Fluid Checkout validates any field carrying the standard validate-required class and shows its own generic "This is a required field.", which stacked under the custom, processor-specific messages. The client script now strips validate-required from only those three fields (research_purpose, compliance_acknowledgment, policy_agreement) on load and after each AJAX order-review refresh, so the custom message is the single source for each; every other checkout field keeps the class and Fluid still validates it (verified an empty billing email still shows Fluid required error). Fluid keys on the class and not on aria-required (verified), so aria-required, the required asterisk, focus management, aria-invalid, aria-describedby, and server-side enforcement are all unchanged. The field-stripped bypass POST still returns failure with all three messages.

## 0.20.0-beta.12 - 2026-08-11

- M12-1 hotfix: the HTML required attribute added in beta.11 broke the inline validation. WooCommerce applies the required custom attribute to the Research Purpose select but not to the checkboxes, so with all three fields empty the empty required select failed the browser constraint (checkValidity() false) and Fluid Checkout's own validation, which keys on that, blocked the submit before the custom validator could run. No error rendered and Place order looked dead, hitting every first-time customer who left all three empty.
- Route B was chosen over adding novalidate. The checkout form already carries novalidate, yet it was still broken, which proves Fluid Checkout validates independently of that attribute, so novalidate cannot suppress it. Instead the HTML required attribute was removed from all three fields, keeping aria-required and WooCommerce's own required argument (which supplies the asterisk and the validate-required class). This reverts to the beta.10 field configuration that worked, while keeping beta.11's Research Purpose client-side validation and dynamic aria-describedby.
- All three fields (compliance_acknowledgment, policy_agreement, research_purpose) are now consistent: no HTML required attribute, aria-required="true", the validate-required class and required asterisk from WooCommerce, and aria-invalid plus aria-describedby set and cleared by the script.
- Verified on the deployed script with the field in the fixed state: all three empty then Place order shows three inline errors at once ("Please select a research purpose to continue." plus the two acknowledgment messages), focus moves to the first invalid field (Research Purpose), aria-invalid and aria-describedby are set on all three, and no native browser bubble competes; clearing each field removes only its own error and aria-describedby; with all three satisfied placement proceeds. The server-side bypass POST to /?wc-ajax=checkout with the fields stripped still returns failure with all three messages. At 390px the three errors are visible with no horizontal overflow, the compliance label is not truncated (137px), and Place order stays on screen.
- Server-side validation, order meta with timestamp and wording version, the legacy consent admin block, label text, links, rel="noopener", and the BAC upsell are all unchanged. Validated with php-parser (no JS or CSS changed).

## 0.20.0-beta.11 - 2026-08-11

- M12-1 follow-up: four checkout compliance fixes. The verified-working parts (server-side validation, order meta with timestamp and wording version, admin block for new orders, label text, links, inline errors, focus, aria-invalid) are unchanged.
- Admin consent block for pre-change orders. The block now reads three shapes: new orders show the acknowledgments unchanged; orders placed before M12-1 show their legacy consent under a clear "Legacy consent (pre-2026-08-11 wording)" heading with the acceptance time and wording version shown as "not recorded" rather than fabricated; orders with neither render nothing instead of an empty block. The legacy acceptances are read under both the leading-underscore keys the migrated code wrote (_privacy_agreement, _terms_agreement_custom) and their no-underscore forms, as a safety net so a key-shape mismatch cannot hide a real acceptance.
- Added the HTML required attribute to all three fields (compliance_acknowledgment, policy_agreement, research_purpose), keeping aria-required. The checkout form already carries novalidate, so native browser validation is suppressed and adding required raises no native bubble and no message that competes with the existing inline errors; validation UX stays with the script and the authoritative gate stays server-side.
- Errors are now associated with their fields for screen readers. Each error node has a stable id (research_purpose_error, compliance_acknowledgment_error, policy_agreement_error), and the script sets the field's aria-describedby to that id when the error is shown and removes it when cleared, so a screen reader announces the reason rather than just an invalid state.
- Research Purpose now fails fast client-side like the checkboxes. An empty selection shows an inline error in the same style and position pattern and is included in the same focus-first-invalid pass (first in page order, so it takes focus before the checkboxes). Error string: "Please select a research purpose to continue." The server-side check is unchanged.
- Reported, not changed (Step 5): the Acknowledgments block renders in the right-hand order-summary column, about 328px wide at a 390px viewport with 13px label text; the long compliance sentence wraps across multiple lines to 137px tall and is fully readable with no truncation or scrolling and no horizontal overflow. Dense but legible; left in place for Paulo to decide on relocating to the main column later.
- Validated with php-parser, node -c, and CSS brace-count. The field-stripped POST bypass test against /?wc-ajax=checkout still returns failure with all three server-side messages; client-side blocking, inline errors, dynamic aria-describedby, and focus were verified live by injection. The end-to-end test order and its stored meta are confirmed on the deployed build.

## 0.20.0-beta.10 - 2026-08-11

- M12-1 checkout compliance acknowledgments, required for payment-processor approval. Replaced the two legacy consent checkboxes (privacy_agreement, terms_agreement_custom) with an Acknowledgments section on the same proven Fluid Checkout hook the old controls used (woocommerce_review_order_before_submit; Research Purpose kept unchanged at priority 10, the Acknowledgments at priority 20), because Fluid Checkout silently drops markup on classic hooks it does not fire.
- Checkbox 1 (compliance_acknowledgment): the verbatim compliance statement (research-only use, no human or animal consumption, not for diagnosis/treatment/prevention, seller indemnification, qualified professional). Checkbox 2 (policy_agreement): a single agreement to the Terms & Conditions, Privacy Policy, and Return & Refund Policy, each linked and opening in a new tab with rel=noopener. Privacy consent is folded into checkbox 2, so it is still explicitly ticked, not dropped. The Research Purpose dropdown and its 7 options are untouched.
- Both are mandatory. Validation runs server-side on woocommerce_checkout_process (authoritative, non-bypassable) and client-side (assets/js/checkout-acknowledgments.js): an inline error is shown next to each unticked box and focus moves to the first, rather than a generic top-of-page notice, with aria-required and aria-describedby so screen readers announce it. Error strings: "You must acknowledge the compliance statement to place your order." and "You must agree to the Terms & Conditions, Privacy Policy, and Return & Refund Policy to place your order."
- Acceptance is stored on the order as evidence, not a flag, through the WooCommerce order CRUD (HPOS-safe): _pepselect_ack_compliance and _pepselect_ack_policy as Yes/No, _pepselect_ack_timestamp as an ISO 8601 acceptance time, and _pepselect_ack_version as a wording-version identifier. The version is the first 12 hex of sha1 of the two label strings (11b0a95ea858) rather than a manual number, so it changes automatically when the wording changes and old orders keep their own hash; the text-to-hash mapping is recorded in HANDOFF-processor-compliance-wording.md.
- The admin order screen block under the billing address shows the new acknowledgments with their timestamp and version for new orders, and falls back to the legacy _privacy_agreement / _terms_agreement_custom meta for orders placed before this change. Historical meta is neither migrated nor deleted, so old orders still display correctly.
- Layout: the long compliance label wraps and is fully readable at 390px (rendered 136px tall, multi-line, no truncation, no scroll box, no horizontal overflow), styled with existing checkout tokens and the standard required asterisk. Validated with php-parser, node -c, and CSS brace-count; client-side blocking, inline errors, focus, and the 390px layout were verified live by injection. The end-to-end test order (blocked with 0/1, succeeds with both, meta written) runs against the deployed build.

## 0.20.0-beta.9 - 2026-08-08

- Payment-processor compliance and BAC upsell relocation.
- Dilution notice: removed the former named solution brand and its graded-quality claim everywhere the notice appeared, per attorney approval, and replaced it with laboratory-grade reconstitution-solution wording. Updated the per-compound notice under add to cart (inc/single-product.php), the Refund and Shipping Policy in two places and Terms section 5 (inc/legal-content.php, three matching clauses each adapted to fit their surrounding wording rather than pasted verbatim), and scrubbed the same brand and grade wording from the historical changelog entries, so the theme now contains zero occurrences of either. No other policy sentence changed.
- Footer disclaimer: replaced the research-use line and the human-consumption line with the two required sentences, kept contiguous, "All products sold on this website are intended for research and identification purposes only. These products are not intended for human dosing, injection, or ingestion." The independent-testing trust line is kept as a separate following sentence, and the separate FDA disclaimer paragraph is untouched.
- Product descriptions: prefixed the intended-use line in the shared description partial (template-parts/product/description.php) with the literal phrase "Research Use Only.", so it appears in the description area of every compound page. This is a shared partial, not per-product database content, so no product records were edited.
- Military and First Responder link: removed from the footer navigation (inc/footer-preview.php). The page, its template, and its styles are untouched, and it remains reachable at /military-discount/. Added a noindex directive gated to that one page (inc/military-page.php), applied through the active SEO layer with a core wp_robots fallback, so the page cannot be found via search. It was the only link to that page in the theme.
- BAC upsell: moved out of the collapsible order summary to directly above the payment method, through the Fluid Checkout before-payment hook (fc_checkout_before_step_payment_fields), so the order total is final before the Square payment instruction. On desktop this moves it from the order-summary sidebar into the main column above payment; the markup changed only from a totals-table row to a standalone block, and the panel styling and the Add 30mL row are unchanged. Toggling still refreshes the order total and the Square instruction amount together with no reload (verified live: 35.35 to 56.14 and back).
- BAC upsell subline: changed from "Compounds ship as lyophilized powder." to "Reconstitution Solution – for Laboratory Use." with an en dash, the processor's required framing, keeping the existing muted subline styling and adding no grade, purity, sterility, or suitability claim.

## 0.20.0-beta.8 - 2026-08-08

- Fixed the out-of-stock chip so it reads "Restocking soon" when a compound has a batch in the vetting pipeline, and "Out of stock" otherwise. The chip already switched on the status band; the bridge that resolves the band (inc/compound-status.php) was reading the wrong things, so it always returned nothing and every out-of-stock card read "Out of stock".
- Root cause was the compound-to-product link. The bridge read the meta key woocommerce_product_id, but the COA Archive plugin stores the product link as pepselect_coa_product_id on the ps_compound record (e.g. compound 885 "Retatrutide 20mg" -> product 864, GLP-3 R SKU GLP3R20). The bridge now reads pepselect_coa_product_id first and falls back to woocommerce_product_id for any older record. (Whether woocommerce_product_id is also mirrored on the record could not be read as a logged-out visitor -- the COA compound REST route is auth-gated -- so the fallback covers both cases.)
- Changed the in-pipeline test to match on "not terminal" instead of an allowlist of pipeline stage names. A batch counts as in-pipeline when its workflow_stage is anything other than the terminal value(s), held in one named constant PEPSELECT_COA_TERMINAL_STAGES (currently 'complete'). The comparison is lowercased and separator-collapsed, so vendor-vetting, vendor_vetting, and Vendor Vetting resolve identically. An allowlist is what broke this before: a stage renamed or added upstream silently dropped products back to "Out of stock". The coa_status filter was dropped from the test, because workflow_stage is the pipeline signal and coa_status (pending / approved / failed) is only meaningful once a batch is complete.
- Kept the mapping keyed on compound_id, not SKU. Compounds are stored per strength (Retatrutide 10mg / 20mg / 30mg are separate ps_compound records), so compound -> product is already 1:1; the batch records carry no SKU field, so SKU keying was neither possible nor needed.
- No chip on in-stock products. Identical styling for both strings. "Restocking soon" (140px) does not wrap or overflow at the archive card width (measured 291px desktop and 183px mobile 2-up), the homepage featured card (282px), or the empty-cart carousel card (267px); the chip grows horizontally and the font is never shrunk. The chip state reaches screen readers through the existing visually-hidden text in the card body.
- Resilience preserved: if the COA plugin is deactivated, its post types are absent, or any lookup fails, the resolver returns an empty map and the chip falls back to "Out of stock" -- never an error, never blank.
- Latent today and correctly so: all nine batch records are workflow_stage=complete, so nothing is in the pipeline and every out-of-stock card correctly reads "Out of stock". Because it cannot be shown on Live yet, the resolver was proven against synthetic records: RT20 (compound 885) with workflow_stage=in-testing resolves to "Restocking soon" on product 864 and on neither 862 (RT10) nor 865 (RT30); RT30 (compound 883) complete and RT10 (compound 961) with no batch both resolve to "Out of stock".

## 0.20.0-beta.7 - 2026-08-07

- Made the compound image fill the card instead of floating at 64% of card width. The undershoot was object-fit:contain sizing a square source by height inside a landscape panel; the fix is the fit and the padding, not the percentage. The image panel now has zero padding and the image is width:100%, height:100%, object-fit:cover, border-radius:0, so it fills the panel edge to edge; the card keeps overflow:hidden so its 32px radius clips the image's top corners. The image now renders at 99.3% of card width, up from 64%. This is on the base card, so every surface that renders the partial (archive, homepage featured, empty-cart carousel, related products) shares it.
- Set the panel ratio by crop safety, not by the proposed value. The product photos are square but the vial sits low: measured whitespace is about 8.7% at the top and only about 2.7% at the bottom (not the assumed 11% each), verified per product by sampling the rendered thumbnails, including the two PNGs from the other shoot (PT-141, Glutathione). A 1.1 landscape panel with centred cover crops about 4.55% off the bottom, which clips the vial base on 13 of the 14 images, so 1.1 was not shipped. The panel ratio was reduced to 1.03, cropping about 1.46% per side, which sits inside the tightest top (8.7%) and bottom (2.7%) whitespace on every product with margin to spare. No vial cap or base is clipped on any product.
- Separated the name and price by type family, not only by size. The compound name stays Georgia (the editorial voice) at 21px/700 navy, down from 30px. The price moves to IBM Plex Mono at 17px/600 navy, down from Georgia 26px/700, so it now reads as data and rhymes with the mono dose pill. Both are existing type tokens (--pep-font-editorial, --pep-font-technical); no new family was introduced. Mono price on white measures 14.42:1, passing WCAG AA.
- Shrank the dose pill from about 65x34px to about 50x25px: font-size 11px (was 13px) and padding 3px 9px (was 6px 14px), keeping IBM Plex Mono, the pill radius, the tint fill, the border, and the left alignment.
- Card height went to about 458px, up from 438px, rather than dropping as expected. The drop had assumed a valid 1.1 landscape panel; because crop safety forces a near-square 1.03 panel, the image is taller (about 281px) even though the text block shrank, and the retained dose-pill row plus the 44px CTA keep the body near 177px. No crop-safe ratio can bring the card below 438px with the current card composition. This trades toward the image-fills-the-card direction of this change.
- Kept intact: 4 columns with a 12px gap and a 32px card radius on the archive; the out-of-stock treatment (image 0.53 opacity, grayscale 47%, greyed card, chip over the image, Notify button enabled at full contrast); the hidden stock label and restocking pill with the chip carrying the state; the whole-card and independent-CTA click targets; and the stock-first archive ordering, which is a query filter and untouched.

## 0.20.0-beta.6 - 2026-08-07

- Tightened the compounds archive to four columns on desktop (>=1024px) with a 12px gap, three columns on tablet (768-1023px), and the existing two columns on mobile (<=767px, one column below 400px). At a 1200px content area each card is 291px wide. The homepage featured grid and the empty-cart carousel layouts are untouched.
- Shrank the rendered vial and unified the image treatment across every surface that renders the card partial (archive, homepage featured, empty-cart carousel, related products). The image panel is now a 1.25 aspect box with 16px padding; the image is 72% of the panel content box, contained, with a 16px radius inside the card's 32px radius for concentric corners (32 minus the 16px padding). At 1200px the image renders at 72.0% of the panel content box, which is 63.6% of the full card width, down from about 95% live. The vial stays fully visible and uncropped at every breakpoint (max-height caps it, object-fit contains it). There are no 32px or 16px radius tokens in the scale (small 8, medium 12, large 20, pill 999), and cards.css already expressed its radii as raw px (24 and 14), so the new 32 and 16 follow that file's own convention rather than inventing tokens.
- Removed the in-body stock text line ("Available" / "Out of Stock"), the separate "Restocking Soon" pill, and the reserved alignment row they needed. Price and action now bottom-align as a pair on every card (margin-top:auto moves from the action to the price), so in-stock and out-of-stock cards are identical height. A single chip centred over the image is the sole stock signal: it reads "Restocking soon" when the product carries a pending-batch flag (the COA Archive bridge status band, inc/compound-status.php, tone "incoming") and "Out of stock" otherwise, same styling for both. The chip is aria-hidden and paired with visually-hidden body text so the state still reaches screen readers.
- Greyed out-of-stock cards: the image drops to 0.53 opacity with grayscale(47%), the card takes a cooler surface background, and the title, price, and dose pill mute to slate. The "Notify when available" CTA stays fully enabled at full contrast and is the most prominent element, keeping its hover fill. Measured on the greyed card: CTA label 14.42:1, card title and price 4.84:1, chip 14.42:1, all passing WCAG AA.
- Kept intact: the left-aligned dose pill above the compound name, the whole-card stretched-link overlay with the independently clickable CTA, the homepage "Learn more" action with no stock label, and the stock-first archive ordering, query, sorting, and filtering, none of which were touched.

## 0.20.0-beta.5 - 2026-08-04

- Diagnosed the reported Bacteriostatic Water symptoms (toggle sometimes needs a refresh, the item is lost when leaving checkout, it is missing from the side cart) before changing code. Nothing in inc/checkout-upsell.php or the toggle script removes, clears, or expires the item on page unload, navigation, or a non-checkout request; the only cart writes are the add and remove inside the AJAX handler, driven solely by the toggle. It is an ordinary cart line. Confirmed live on a single host (pepselect.com, never touching www): the item persists across shop and checkout navigation, the server add and remove return the authoritative in_cart state, and the classic session cart and the Store API cart agree. The cross-page loss is the split-host cart (www vs non-www serving separate sessions), which is server configuration and not addressed in the theme.
- Made the toggle refresh the rest of the UI after a change. On success it now fires the WooCommerce cart fragment refresh (wc_fragment_refresh) in addition to update_checkout, so the side cart, the header cart count, and any cart-total pill update without a reload; the order total and Square amount still refresh through update_checkout. Verified live on checkout that the header count moves 3 to 4 on add and back to 3 on remove.
- Made the toggle reliable. The switch is disabled while a request is in flight and a re-entrancy guard protects the delegated handler if the order review re-renders mid-request, so overlapping requests cannot race. After the request resolves the switch is reconciled against the in_cart state the server reports rather than the click, so it can never drift from the real cart. On failure it rolls back to its pre-click state, so it is never left on with an empty cart, and shows an inline retry message in the panel (in the existing --pep-color-red token). Intermittent AJAX failures are most consistent with a stale nonce served from a cached checkout page, or a request landing on the other host in the split-host setup; a fresh page load mints a new nonce, which matches the refresh-fixes-it symptom.
- Reduced perceived latency. The switch moves optimistically on click and the panel shows a subtle in-progress state (the label dims to match the disabled switch, cursor progress, no spinner) using the shared --pep-motion-duration token, which collapses to near zero under prefers-reduced-motion. WooCommerce's own overlay covers the order-total update, and the switch is released as soon as the fast toggle request resolves rather than waiting on the slower review refresh, so it responds at once. No new tokens, and the panel styling and copy are unchanged.

## 0.20.0-beta.4 - 2026-08-04

- Gave the checkout Bacteriostatic Water upsell a light blue tinted panel using the existing --pep-color-cyan-soft token, the same tint the cash-back pill background and the card image panel use, with a cyan border matching the cash-back pill. The 8px radius and Plus Jakarta Sans are kept, with no shadow, gradient, or glow, and it stays visually distinct from the amber payment instruction panel. Heading text (navy) on the tint measures 13.05:1, passing AA and AAA.
- Rewrote the copy: heading "Need bacteriostatic water for your research?", a muted subline "Compounds ship as lyophilized powder." in the --pep-color-slate body token, and a shorter toggle-row label "Add 30mL - $19.99" with an en-dash separator. The price is read live from the product. The volume is parsed from the product title, since the product carries no volume attribute, rather than typed as a literal, and falls back to the full name. The toggle keeps a full-name accessible label ("Add Bacteriostatic Water 30mL to your order"). No claim is made about grade, purity, sterility, suitability, or refund eligibility, and nothing connects the product to the dilution notice.

## 0.20.0-beta.3 - 2026-08-04

- Set the Bacteriostatic Water upsell SKU constant (PEPSELECT_BAC_WATER_SKU in inc/checkout-upsell.php) to BACW30, so the checkout upsell now renders. The product is resolved by SKU, and its price and stock are read from the live product. Verified against the live store: SKU BACW30, product id 1339, $19.99, in stock, purchasable.
- Confirmed placement under Fluid Checkout without a hook change. The block hooks woocommerce_review_order_after_order_total, and that hook fires inside Fluid Checkout's order-summary sidebar, directly under the order total and above the terms consent, verified against the live checkout DOM (the classic review-order table renders in the fc-sidebar, and the existing consent hooks render there too). The block is navy, Plus Jakarta Sans, 8px radius, cyan switch, with no new tokens.
- Confirmed catalog visibility "Hidden" does not block the SKU lookup, the price read, or add-to-cart: the product is purchasable and was added successfully through the store cart in testing, so the toggle works without making the product visible on /shop or in search.

## 0.20.0-beta.2 - 2026-08-04

- Fixed the empty cart reverting to the stock WooCommerce product list after an item was removed without a reload. The reverting list is the woocommerce/product-new block, which WooCommerce Blocks re-renders client-side after a Store API cart mutation, bypassing the PHP render_block filter that replaces it on the server. The coded list is now the single source on both paths: rendered server-side on load, and on the client re-render cloned from a page <template> by assets/js/cart-empty.js into the empty-cart block, with the stock grid hidden in CSS. No card markup is duplicated in JavaScript, and a no-JS removal reloads and re-runs the server render. Verified on live by reproducing the removal on desktop and mobile.
- Excluded Bacteriostatic Water, and any catalog-hidden product, from the empty-cart list so it agrees with /shop. The list query did not apply the shop's visibility rule; it now gates products through WC_Product::is_visible(), the same predicate the shop archive uses to decide whether to render a card. No product ID or SKU is hardcoded.
- Closed an unterminated @media (max-width: 767px) block in checkout.css left by an earlier commit, which had trapped the (not-yet-rendered) Bacteriostatic Water upsell styles inside the mobile breakpoint; those rules are now top-level as intended.

## 0.20.0-beta.1 - 2026-08-04

- Added a Bacteriostatic Water upsell to the checkout Order Summary, directly under the order total and above the terms text. A toggle adds one unit to the cart and refreshes the totals live over AJAX, and toggling off removes it; if it is already in the cart the toggle shows on. The product is resolved by SKU from a single constant (PEPSELECT_BAC_WATER_SKU in inc/checkout-upsell.php), and its price and stock are read from the live product, never hardcoded. When the product is out of stock, missing, or the SKU is unset, the whole block does not render (no greyed toggle, no message). Styled to the checkout: navy, Plus Jakarta Sans, 8px radius.
- Added a per-product "Display order" field to the product admin (General panel) and sorted the compound listings by it: in-stock products always sort before out-of-stock ones, and within the in-stock group products sort by the display-order value, lowest first. The sort reads only the stored meta, so the sequence is changed in the admin without code. It applies to the shop page, every product category and tag listing, and product search, since all run through the main archive query. A one-time seed sets the launch order (GLP-3 R, GLP-2T, GLP-1 S, NAD+, TB-500, BPC-157, Tesamorelin, Glutathione, then everything else) by matching product titles.

## 0.19.0-beta.19 - 2026-08-03

- Gave the homepage and archive cards the empty-cart card's inset image treatment, value for value. The photo now sits 8px inside the panel so a band of the panel tint shows on all four sides, and the image carries its own 14px radius nested inside the card's 24px one. The tint is the base gradient and the panel sets no outer radius of its own, because the card's overflow already clips its top corners. Nothing was invented; all four values are the reference's.
- Kept the square image box introduced in 0.19.0-beta.18 rather than the reference's 4:3. With a square box the image fills the panel evenly, so the 8px inset reads as an equal band on all four sides, which is the requested result. A 4:3 box letterboxes a taller photograph and the side bands come out wider than the top and bottom. The vial stays fully visible and uncropped.
- Moved the dose pill back to the left, sharing the same edge as the compound name, stock label, price, and button, matching the cart card. It stays on its own line above the name and its size and styling are unchanged. The alignment is set explicitly because the compact card body stretches its children, which would otherwise pull the pill to the full width of the card.
- Added clearance below the empty-cart carousel so the last card no longer sits against the footer, at two desktop gutters on both mobile and desktop. Spacing only; the carousel cards are untouched.

## 0.19.0-beta.18 - 2026-08-03

- Fixed the product image on the homepage and archive cards leaving pale gutters at the sides while cropping the vial top or bottom. Three things were combining. The 8px panel padding guaranteed a gutter on all four sides before the image was measured at all. The 4:3 box is wider than these product photographs, so fitting the image to the panel height letterboxed it horizontally and left the side gutters. And the inherited grid centring sizes the image as a centred item rather than stretching it to the area, so where the height did not resolve cleanly the image kept its intrinsic height and spilled past the short box, clipping the cap on archive cards and the base on the homepage.
- The panel is now a square block box with no padding, and the image is a plain block filling it exactly with object-fit contain, so the whole vial is always visible and nothing is cropped on any edge. A square is the ratio the related-products panel already uses, so it is a shape these photographs are known to sit in. Because the panel no longer has padding, the image reaches the card's full inner width.
- The panel now carries the card's own 24px corner radius on its top two corners and clips to it, so the image follows the card's outer curve instead of showing a square edge behind a separately rounded image. The image's own 14px radius is removed in favour of the panel's clip. Note that the card's radius is a literal 24px in cards.css rather than one of the four radius tokens, which top out at 20px; no new value was introduced, the panel simply reuses the card's.
- This leaves the homepage and archive panels differing from the empty-cart card, which still uses a 4:3 box with 8px padding. Those two values are what produce gutters, so the empty-cart card cannot both keep them and render edge to edge. The empty-cart card was left untouched as instructed; aligning it is a two-line change whenever it is wanted.

## 0.19.0-beta.17 - 2026-08-03

- Made the homepage and archive card image panels match the empty-cart card exactly. 0.19.0-beta.16 had replaced the reference approach with an absolutely positioned image inside a padding-free wrapper, which changed the content box the image was fitted into and rendered the vial smaller than it should be, with dead gutters on the homepage and a visibly undersized image on the archive. The compact rules are now the empty-cart values verbatim, a 4:3 box with 8px padding and an image at full width and height with object-fit contain, inheriting the base grid centring and panel tint. The one addition is overflow hidden on the wrapper, which clips and cannot alter layout, so the overflow fixed in 0.19.0-beta.16 cannot return. All three card types now compute identical image geometry.
- Fixed the dose pill rendering as a tight circle on homepage and archive cards. The compact variant zeroed padding-inline on every direct child of the card body to force one shared left edge, which also stripped the pill's own 6px 14px down to 6px 0 and left a pill-radius capsule hugging the text. The pill's horizontal padding is its shape rather than an alignment offset, so the reset now applies to margins only. No other body child carries horizontal padding, so the shared left edge is unaffected. The pill's padding, radius, font, and fill are once again identical to the empty-cart pill.
- Archive cards now reserve the Restocking Soon row on every card, so the stock line and the price sit at the same height across a row whether or not a card has the band. The reserved box keeps its bottom alignment, so the stock line lands on the same baseline in both cases. Cards without the band carry a small reserved gap, which is the accepted trade for alignment. Homepage cards have neither a band nor a stock line and reserve nothing.

## 0.19.0-beta.16 - 2026-08-03

- Fixed the product image spilling out of its box on the archive and homepage cards. 0.19.0-beta.15 shortened the image box from a 7:8 to a 4:3 aspect ratio but left the base grid layout in place. A grid item carries an automatic minimum size, so the image could not shrink below its own intrinsic height, and with the box now shorter than that height and no overflow control on the wrapper it spilled downward over the compound name and the dose pill. The taller box had hidden the problem because the intrinsic height still fitted. The wrapper now owns the box outright with a fixed aspect ratio and hidden overflow, and the image is absolutely positioned inside it at full width and height with object-fit contain, so its intrinsic size cannot influence layout at all. The panel keeps its existing gradient tint, which shows as the letterbox around the contained vial.
- Moved the dose pill back onto its own line above the compound name, which is the original structure. Sharing the name's row squeezed the name into the remaining width and, together with an overflow-wrap anywhere rule, broke long names mid-word. The only change from the original layout is that the pill now sits at the right edge of the card body. The name has the full body width on its own line and no longer breaks mid-word. Pill styling is unchanged.
- Everything else from 0.19.0-beta.15 is retained: one shared left edge for the body content, the removed reserved heights, the stretched-link card with an independently clickable button, the homepage dropping its redundant Available label, and the archive keeping its stock labels and Notify when available. Queries, sort orders, and colour treatments are untouched, and the related-products and empty-cart carousels remain structurally identical to 0.19.0-beta.14.

## 0.19.0-beta.15 - 2026-08-02

- Compacted and realigned the compound cards on the compounds archive and the homepage featured grid. All four surfaces that show a compound card share one partial, so the changes are gated behind a variant argument that adds a pepselect-card--compact class. The related-products carousel and the empty-cart carousel pass no variant and their markup is unchanged, verified by rendering both against the previous partial and comparing the resulting DOM.
- The card body is now the single container holding the horizontal padding and no child adds its own, so the compound name, stock label, price, and button share one left edge. The dose pill keeps its internal padding because that padding is the pill shape, and it no longer sits on the left edge.
- The dose pill moved to the right of the compound name on the same row. The name takes the remaining width and may wrap; the pill holds its width and cannot be pushed off the card.
- Removed the reserved heights that were creating the dead space: 76px on the status block, 66px on mobile, and 2.3em on the title, 2.2em on mobile. Equal card heights within a row already come from the grid stretching its items and from the action's margin-top auto, so the slack from a missing Restocking Soon pill now collects directly above the button instead of sitting as a hole in the middle of the card. The image box goes from a 7:8 crop to a 4:3 box with contain, so the whole vial is visible and uncropped in less height.
- The whole card is now a link to the compound page, implemented as a stretched-link overlay on the existing compound-name anchor. No second anchor was added and no button is nested inside a link. The action button is raised above the overlay so it stays independently clickable, and the card gained a focus-visible outline so keyboard users can see which card is focused.
- Homepage cards drop the Available label, which is redundant on a grid that only shows in-stock compounds. Archive cards keep Available, Out of Stock, Restocking Soon, and Notify when available exactly as before. Both keep the Learn more button linking to the compound page. Neither query, sort order, nor colour treatment was changed.

## 0.19.0-beta.14 - 2026-08-02

- Fixed both product lists rendering on the empty cart. 0.19.0-beta.13 swapped the list from inside the empty-cart block's own output, but that block does not contain the list: the products render from a separate block. The swap found nothing, took its append fallback, and added the carousel to the end of the empty-cart block while the stock list carried on rendering further down, leaving the heading stranded between the two. The list is now handled where it actually renders: the first block that looks like a product list becomes our carousel and any later one is dropped, so exactly one list survives. Because the swap happens in place, the heading keeps its own position and the sequence is now vial mark, title, subline, heading, carousel. The swap is also gated on the cart being genuinely empty, so a product list on the Cart page with items in the cart is left alone.
- Reduced the empty cart card height by about a third. The product image box goes from a 7:8 crop to a 4:3 box with contain, so the whole vial is visible and uncropped at its edges in much less vertical space, and the internal padding and the gaps between the dose tag, name, availability, price, and button are tightened. Type sizes, colours, radii, and the card's visual style are unchanged. Every rule is scoped to the empty cart list, so the homepage grid and the single-compound related carousel keep the taller card.
- The empty cart buttons now read Add to cart and add the product directly, using WooCommerce's own loop add-to-cart markup so cart state, the AJAX refresh, and the header count behave normally. The shared card partial takes an optional action argument that defaults to the existing Learn more link, so the homepage and single-compound carousels are unaffected. A product that is not a purchasable in-stock simple product falls back to a link to its page rather than shipping a button that cannot work.

## 0.19.0-beta.13 - 2026-08-02

- The empty cart product list is now queried and rendered by the theme instead of being filtered out of the block's own list. Only two compounds were showing because the block asks for a small fixed number of products and the out-of-stock filter removed some of them; a filter can only remove, it cannot reach past the block's post count. The list is now queried with wc_get_products at status publish, stock_status instock, catalog visibility visible, limit 12, ordered by menu order then title, and the block's own list is replaced with it.
- The list renders through the existing compound card partial, the same one the homepage grid and the single-compound related carousel use, in the same ul and li wrapper as the related carousel. No new card markup was written. Desktop presentation matches the homepage featured grid exactly: four columns, two at 1024px, and the scroll-snap carousel below 768px. Mobile and desktop show the same compounds and the same cards; only the container changes.
- cards.css is now enqueued on the cart page. The cart was the one surface using the card partial with no stylesheet behind it.
- Added breathing room above the vial mark on small screens, one step up the gutter scale. The theme has no dedicated spacing scale, so the gutter tokens are the only spacing tokens available.
- Removed the out-of-stock marking and its display none rule. Both are dead now that the query returns in-stock products only, so an out-of-stock compound is never rendered and there is nothing to hide. The product-ID resolver introduced in 0.19.0-beta.11 was removed with it.
- The homepage carousel, the COA carousel, and their templates are untouched.

## 0.19.0-beta.12 - 2026-08-02

- Fixed the empty cart product list still rendering as a vertical stack. The class was reaching the right element all along: the out-of-stock filtering that shipped in 0.19.0-beta.11 is applied in the same pass, to the children of that same tagged container, and it worked, which is only possible if the container was found and tagged. The failure was in the CSS. The rule was a single class and it never reset grid-template-columns, while WordPress layout support emits a container rule that sets display grid together with an explicit grid-template-columns and doubles its own class to raise specificity above a plain single-class selector. Both carousels already in the theme neutralise grid-template-columns for that reason; that reset was the missing piece.
- The empty cart carousel now uses the same recipe as the related products carousel on the single compound page: flex track, x mandatory snap, edge bleed at the layout gutter, hidden scrollbar, and snap alignment on the children. That rule cannot be shared literally, because product.css is only enqueued on single product pages and its class is not present on the cart, so the declarations are reproduced in the one stylesheet the cart loads rather than a second pattern being invented. The selector is now body plus a doubled class so it outranks the generated container rule, and display, grid-template-columns, and the out-of-stock display none carry !important because the competing values are generated per page load and their exact selector cannot be confirmed from the repository. Those are the only three in the file.
- The homepage carousel and the COA carousel are untouched. Desktop is unchanged; every rule sits inside the 767px breakpoint.

## 0.19.0-beta.11 - 2026-08-02

- Out-of-stock compounds are now actually hidden from the empty cart list on phones. The 0.19.0-beta.9 approach used a post_class filter, but the block product template that renders this list never calls get_post_class(), so the class never reached the markup and Glutathione and the 20mg GLP-3 R kept rendering with Read more buttons. The marking now happens in the same DOM pass that tags the carousel: each item's product ID is recovered from the rendered item, confirmed by loading it through wc_get_product(), and out-of-stock items get the ps-oos class. The ID is read from a data-product-id or data-product_id attribute, then a post-<id> class, then the item's first link resolved through url_to_postid(). When no item resolves, nothing is hidden and the real markup is reported. The dead post_class filter has been removed rather than left in place.
- Fixed the empty cart page overflowing horizontally and clipping the first words of its title and subline. The beta.10 width containment was scoped to the filled cart's blocks, so it never reached the empty-cart block. The resets now apply to the cart page as a whole, covering the theme and Elementor content wrappers, both cart-state block wrappers, and wide and full alignments, whose viewport-relative widths overshoot a narrow screen. The empty-cart title and subline additionally carry their own width and wrapping guard.
- Desktop is unchanged. Every rule in this release sits inside the 767px breakpoint.

## 0.19.0-beta.10 - 2026-08-02

- Fixed the mobile cart overflow that 0.19.0-beta.9 did not resolve. The cause was a selector error, not a delivery or caching problem: WooCommerce Blocks passes "wc-block-cart" into its SidebarLayout component, so wc-block-cart and wc-block-components-sidebar-layout sit on the same element, and beta.9 targeted them with a descendant combinator that can never match one element. The rule that collapses the two-column layout therefore never applied, while the width rules on the main and sidebar columns did, leaving two full-width flex children in a still-flex row about twice the viewport wide. Because the cart is centred, that overflow split both sides and clipped the left edge. Selectors now match the layout class on its own, are scoped under body.woocommerce-cart so they outrank the plugin's single-class rules without !important, and set min-width 0 on cart descendants so a long label can no longer force its row wider than the screen.
- The empty cart carousel no longer guesses a container class. The rendered block is parsed and the element that actually holds the product items is tagged with ps-empty-carousel, so the carousel works across block versions, including container classes that do not exist yet. When fewer than two product items are found nothing is tagged, the markup is returned untouched, and the real markup is reported to shop managers.
- No change to desktop. All layout rules remain inside the 767px breakpoint.

## 0.19.0-beta.9 - 2026-08-02

- Fixed the cart overflowing horizontally on phones. The block cart sizes itself from a JS-measured container class (.is-large / .is-medium / .is-small) rather than the viewport, so on a narrow screen it kept the two-column sidebar layout and pushed "Coupons", "Estimated total", and the shipping method name past the left edge. Below 768px the sidebar layout now collapses to a single column, main and sidebar are full width with no inline padding, the item table is width-constrained with table-layout fixed, the item table header is hidden, and totals rows are flex with both label and value allowed to shrink so long labels wrap instead of widening the row. The same overrides are applied under the .is-large and .is-medium container classes. The cart wrapper carries overflow-x clip as a final guard.
- Replaced the empty cart's broken placeholder image with a coded inline vial mark, and the default "Your cart is currently empty!" string with "An empty cart is a clean bench." and "Pick a compound and we will handle the paperwork." The placeholder is matched on the block's own class rather than an image URL, so no uploaded asset is referenced. When the expected markup is not found the block is returned untouched and the real markup is emitted as an HTML comment for shop managers rather than guessed at or hidden.
- Added a mobile-only "Selected compounds" label for the empty cart's "New in store" heading. Both labels render and CSS shows one per breakpoint, so the desktop wording is unchanged.
- Out-of-stock products in the empty cart recommendation list are marked with a ps-oos class on the cart page and hidden below 768px only. Desktop is untouched.
- The empty cart recommendation list becomes a horizontal scroll-snap carousel below 768px, bleeding to the screen edge without widening the page. Layout only; the cards keep their existing styling.

## 0.19.0-beta.8 - 2026-08-01

- Added a dilution and cloudiness clause to the Refund & Shipping Policy (both under "Not Eligible for Refund or Replacement" and alongside the damaged or incorrect order remedies) and to Terms & Conditions section 5. Cloudiness after reconstitution is not eligible for refund, replacement, or credit unless a laboratory-grade reconstitution solution was used. Existing refund language unchanged; no sections renumbered. last_updated bumped to August 1, 2026 on both documents. Attorney approved.

## 0.19.0-beta.7 - 2026-08-01

- Added a dilution notice to every compound buy card, directly below add to cart (and below the notify form on out-of-stock pages). It states that post-reconstitution cloudiness is almost always caused by the dilution solution rather than the compound, and that cloudiness is not eligible for refund unless a laboratory-grade reconstitution solution was used. Binding refund-policy language — flagged for M9 attorney review.

## 0.19.0-beta.6 - 2026-07-25

- Fixed the referral share link never changing from ?ref=7. The link was built client-side by reading a data attribute off the wrong element (YITH's inner node instead of the wrapper that carried it), so it always fell back to YITH's raw numeric link and every vanity scheme was computed and then ignored. The share field is now rendered server-side in the template from the stored code, and the JavaScript no longer harvests YITH's markup at all; it only wires the Copy button.
- Referral codes are now generated at account creation and stored as the pepselect_referral_code user meta. The user_register hook covers both manual signup and Nextend/Google social login, since both create the account through wp_insert_user. A one-time guarded backfill gives every existing account a code. Display falls back to generating the code on demand if the meta is ever missing, so the link can never regress to the bare ID.
- The code stays PSRC + user ID (PSRC7); the resolver strips the prefix back to the numeric ID for YITH, so crediting is unchanged. YITH's own referral shortcode is no longer rendered, and a CSS guard hides any stray YITH referral markup in the cash-back area.

## 0.19.0-beta.5 - 2026-07-25

- Replaced the name and email referral schemes, which both degraded to the bare user ID when the account data was missing (common for Google/Nextend social logins), with a fixed-prefix code that reads no account data and cannot fail: the code is "PSRC" plus the user ID (user 7 -> ?ref=PSRC7). The resolver strips the PSRC prefix (case-insensitive) to recover the numeric ID and hands it to YITH, so crediting is unchanged; a legacy numeric ?ref is left untouched. The share-link field and Copy button show the PSRC-format URL.

## 0.19.0-beta.4 - 2026-07-25

- Switched the vanity referral code from name-based to email-based. Google/Nextend social-login accounts populate no first or last name (and often no billing name), so the name-based code collapsed to the bare user ID and the link stayed ?ref=7. The code is now the email local-part plus the user ID (contact@paulobasseto.com, user 7 -> ?ref=contact7); email is always present. The local-part is lowercased, reduced to a-z0-9, and capped at 12 characters, and an empty result falls back to the bare ID.
- The resolver recovers the user ID by trying each trailing-digit suffix and regenerating the code, so a local-part that itself ends in digits still resolves to the correct user; it hands YITH the numeric ID, leaving attribution unchanged, and ignores spoofed or unknown codes while still passing a legacy numeric ?ref through.

## 0.19.0-beta.3 - 2026-07-25

- Fixed the vanity referral link not generating: the code read only the WordPress account first and last name, which is empty for customers who have only a billing name, so it collapsed to the bare user ID and the share link stayed as ?ref=7. It now falls back to the billing name, then the display name, before the ID, so a named customer gets a real code (Paulo Basseto, user 7 -> ?ref=PABA7). The resolver uses the same generator, so inbound codes still validate back to the numeric user ID YITH credits.
- Added a three-step "how to refer" explainer above the share link: share the link, tell the friend to use code WELCOME10 at checkout for 10% off their first order, and earn $15 in cash back when their order completes. Numbered cards in the page tokens, stacking on small screens.

## 0.19.0-beta.2 - 2026-07-25

- Removed the "Your referral code" field from the cash-back referral section. It showed the raw numeric user ID and did nothing, since there is nowhere to enter a bare code; the share link is the actual mechanism, and only it remains.
- Replaced the raw numeric referral link (?ref=7) with a readable vanity link: the first two letters of the first name, the first two of the last, and the user ID, uppercased (Paulo Basseto, user 7 -> ?ref=PABA7). The share field and Copy button now show the vanity URL. Names shorter than two letters use what letters exist, a missing name falls back to the bare ID, and the trailing ID keeps every code unique. On a visit, the code is validated back to the user ID it encodes and YITH is handed the numeric ID it keys attribution to, so a vanity link credits exactly as the numeric link did; a mismatched or spoofed code is ignored.

## 0.19.0-beta.1 - 2026-07-23

- Migrated the checkout consent checkboxes, the Research Purpose field, and three legacy shortcodes out of the parent theme (hello-elementor/functions.php) into the child theme, where a parent-theme update can no longer destroy them. The parent's original hooks are removed at runtime on init and the migrated callbacks are renamed with the pepselect_child_ prefix, so the two copies never duplicate output or fatally redeclare a function while the parent code is still present. All data contracts are preserved: the field names, the order meta keys, the Yes/No consent values, the raw Research Purpose label, the seven options and their placeholder, the field order (Research Purpose first, then the two consents), and the "Research Purpose" email label.
- Fixed the checkout Terms consent link, which pointed at the non-existent /terms-and-conditions/; both consent links now resolve through pepselect_child_get_legal_url() with a home_url() fallback, so they cannot drift to a dead slug again. Research Purpose is sanitized and checked against the known options before it is stored.
- Registered the three parent-theme shortcodes so no legacy page renders raw shortcode text: [product_stock_status] is migrated as-is, and [recent_batches] and [coa_table], which depended on the now-removed Advanced Custom Fields and are superseded by the COA Archive plugin, are retained as empty stubs that can be removed once confirmed unused.

## 0.18.0-beta.3 - 2026-07-23

- Removed the conflicting "1–2 business days" processing claim from the Terms & Conditions (Shipping & Risk of Loss) and the Refund & Shipping Policy, so the same-day cutoff copy is the single statement of processing and dispatch time. The Terms now point to the Refund & Shipping Policy for those times, and the policy paragraph begins at the tracking sentence. Risk-of-loss language, refund terms, carrier options, and the free-shipping threshold are unchanged.
- Shortened the homepage FAQ shipping answer to the cutoff, the holiday note, and the free two-day threshold, dropping the carrier list. The full FAQ answer keeps the carrier options.

## 0.18.0-beta.2 - 2026-07-23

- Completed the shipping-cutoff copy and made it consistent everywhere it appears. The published wording stated only the before-10:00 AM same-day case; it now states the full schedule: orders before 10:00 AM ET Monday through Thursday ship the same day, orders after that cutoff on those days ship the next day, orders placed Friday through Sunday ship on Monday, and holidays can shift these times when carrier services are closed. The Shipping section of the Refund & Shipping Policy carries this as its own paragraph and is the authoritative statement; the full FAQ answer and the homepage FAQ answer carry the same four facts. The shipping-options facts (USPS Priority, FedEx two-day and next-day, free FedEx two-day at a $200 subtotal), the 50-states-plus-DC coverage, and every other policy statement are unchanged.

## 0.18.0-beta.1 - 2026-07-23

- Coded the four legal pages — Terms & Conditions, Privacy Policy, RUO Disclaimer, and Refund & Shipping Policy — and routed them by slug. A `template_include` filter at priority 99 seizes rendering for the four known slugs the same way the single-product page does, so Elementor no longer renders them and there is no double-render risk and no admin step to perform; any other slug falls through untouched. The copy is reproduced verbatim from the live pages, extracted into a single source at `inc/legal-content.php` as an ordered array of heading, paragraph, and list blocks and escaped on output. The only change to the wording is the last-updated line: the literal "Last updated: [DATE]" placeholder on Terms, Privacy, and Refund & Shipping is replaced with a real date, July 23 2026, now rendered from one `last_updated` field rather than sitting inline in the prose; RUO gains the same last-updated line for consistency. `templates/legal-page.php` renders any document, `inc/legal-pages.php` holds the routing, the `assets/css/legal.css` enqueue scoped to these pages, and a `pepselect_child_get_legal_url()` helper. The design follows the utility-page signature: an uppercase cyan eyebrow as the h1, a single left-aligned column at a ~70-character measure, 16px body at line-height 1.7, Georgia section headings over Plus Jakarta Sans body, and a plain in-page table of contents for documents over six sections (Terms, Privacy, and Refund & Shipping get one; RUO does not). No cards, tokens throughout, reduced-motion respected.

## 0.17.0-beta.6 - 2026-07-23

- Adjusted the type set in 0.17.0-beta.5 after seeing it in place. On the compound page the sources toggle drops 15px to 13px at line-height 1.5 and the intended-use paragraph drops 15px to 13px, so neither competes with the description; the intended-use label stays at 14px, the CAS line and citations at 14px, and the disclaimer at 12px. In the block cart everything moves up one more step, scoped under `.wc-block-cart` as before: column headers 14px to 15px, product name 16px to 17px, prices and line totals 15px to 16px, totals rows 15px to 16px, the order total row 17px to 18px, the coupon panel toggle 15px to 16px, and the quantity selector set to 15px. The Proceed to checkout button stays at 16px.
- Removed the per-item short description from cart line items, which was rendering "High-purity research peptide" at about 11px. The slot is left in place for a future current-batch line.

## 0.17.0-beta.5 - 2026-07-23

- Legibility pass across the cart, the header, and the compound page's supporting text. Font sizes only, with line-height where it helped; no layout, spacing, colour, or markup changed. Block cart, scoped under `.wc-block-cart` because the block ships its own smaller scale: column headers 13px to 14px, product name 13px to 16px at line-height 1.4, prices and line totals 13px to 15px, product details 14px, totals rows 13px to 15px, and the order total row 17px at weight 600; the coupon panel toggle 13px to 15px. Header: navigation links 15px to 16px, and the action labels and rewards value 14px to 15px, with the existing 1024px rewards reduction moved 13px to 14px so mobile is not left smaller than before. Compound page: eyebrow 12px to 13px, sources toggle 13px to 15px, CAS line and citations 12px to 14px at line-height 1.6, intended-use label 13px to 14px and its paragraph 13px to 15px at line-height 1.6, and the disclaimer 11px to 12px at line-height 1.6, still subordinate but legible. The compound description and Research context bullets are unchanged.

## 0.17.0-beta.4 - 2026-07-23

- Tracking resolution now validates that a candidate actually looks like a tracking number before accepting it, so a flag meta such as "1" stored under a tracking-shaped key can no longer render the tracking block. The check requires a plain string of 6 to 40 characters made of letters, digits, spaces, and hyphens with at least four digits, and rejects boolean-ish values, arrays, objects, and serialized data. It is applied to every resolution path, and a candidate that fails is skipped so resolution continues to the next source. The email source comment now also lists the tracking-related meta keys present on the order, keys only, so the real Easyship key can be identified from one shipped order.

## 0.17.0-beta.3 - 2026-07-23

- The 0.17.0-beta.2 delivery-estimate strip did not apply to Fluid Checkout's shipping options, because Fluid Checkout builds its own option markup from the raw rate label rather than the cart full-label. The strip now also hooks `woocommerce_shipping_rate_label` at a late priority, which is the filter `WC_Shipping_Rate::get_label()` applies, so Fluid Checkout and the WooCommerce Blocks cart inherit it at the source. The cart full-label filter moved to the same late priority in case the rates plugin appends its estimate after us.

## 0.17.0-beta.2 - 2026-07-23

- Removed the carrier delivery-time estimate from shipping labels wherever they display. The live-rates integration appends the transit estimate to the method title itself and offers no setting to suppress it, so any parenthetical mentioning "day" or "days" is stripped from the cart and checkout rate labels and from the stored method title shown on the order-received page, account order views, and order emails. Display only: rates, costs, and stored titles are unchanged, and parentheticals that are not estimates, such as "(Signature required)", are left alone.

## 0.17.0-beta.1 - 2026-07-22

- Redesigned the on-hold order email to the storefront: navy headings, cyan pill Square button, amber exact-amount block, Plus Jakarta Sans with email-safe fallbacks, and IBM Plex Mono for the order total. Voice reset to calm and precise. The live total via `get_formatted_order_total()`, the Square link in both button and plain-text form, the "3BS Holdings LLC" descriptor, and the support address are unchanged. Table layout with inline styles throughout; no flexbox or grid.
- Added a customer completed-order email override with a shipment tracking block styled to match, showing carrier, tracking number, and a tracking button plus plain link when a URL is available. The block is omitted entirely when an order has no tracking.
- Added `inc/emails.php` with shared email tokens mirroring foundations.css, and `pepselect_child_get_order_tracking()`, which resolves tracking from the WooCommerce Shipment Tracking structure, known Easyship and generic meta keys, a scan of the order's own meta for tracking-shaped keys, and finally the fulfilment order note Easyship writes. The key it read from is emitted as an HTML comment in the email source so it can be confirmed on a real shipment, and the whole result is filterable via `pepselect_child_order_tracking`.

## 0.16.0-beta.16 - 2026-07-22

- Referral link now renders. YITH's `get_referral_link()` defaults `user_id` to 0 and only outputs when it resolves to a real user, so the shortcode is called as `[ywpar_referral_link user_id="auto"]`. Its output (`#ywpar_referral_link_sc`) is captured and restyled into a copyable field with a Copy button plus the full share link, and YITH's raw block is collapsed only once the values have been read from it.
- Removed the referral reflection diagnostic and its raw dump; the referral section now renders only when YITH returns real output.

## 0.16.0-beta.15 - 2026-07-22

- Removed all network and timer work from the cart pill. YITH injects its cart banner client-side from its own blocks bundle, so the previous refresh (re-fetching the cart page and re-parsing it) could never find the banner and only triggered the slow cart request repeatedly, making the pill appear late and then flicker. The pill now mirrors YITH's banner directly: one cheap DOM scan, coalesced to at most once per animation frame, with no fetch, no document parsing, and no timers. Live updates come from YITH re-rendering its own banner.
- When YITH's `[ywpar_referral_link]` returns nothing, the page now reads its callback from the plugin file on the install via reflection and prints the callback name, file, line range, and full source, alongside referral-related options and user meta, so the exact condition it requires can be identified rather than guessed.

## 0.16.0-beta.14 - 2026-07-22

- Referral code and share link now render from YITH's own `[ywpar_referral_link]` shortcode, which is separate from the my-points endpoint output. Its output is captured and restyled into a copyable field with a Copy button plus the full share link. If the shortcode is registered but returns nothing, its raw output is shown for diagnosis instead of being silently hidden.
- Removed the temporary debug build: the raw-output dump block and the `PEPSELECT_CASHBACK_DEBUG` constant are gone.
- Fixed the cart pill performance regression (slow appearance, then flicker and reload). Three causes: the MutationObserver triggered a full cart-page re-fetch on every DOM change during the block cart's normal render, and that request is the slow one; the refresh ran even when nothing had changed; and a refresh that read no value blanked the pill. The pill is now captured from YITH's already-rendered banner immediately with no network call, an empty read never blanks an existing value, and the server is only re-read when the cart total actually changes.

## 0.16.0-beta.13 - 2026-07-22 (TEMPORARY DEBUG BUILD)

- Added top margin to the cart cash-back pill so it no longer touches the nav bar.
- TEMPORARY: the cash back page renders a debug block above the normal layout showing YITH's unmodified my-points output three ways — a plugin/shortcode/endpoint probe, the output rendered verbatim with the theme restyle bypassed, and the raw escaped source. This exists only to identify the real referral markup and must be removed in the next build (delete the debug block in `woocommerce/myaccount/cash-back.php` and the `PEPSELECT_CASHBACK_DEBUG` constant).

## 0.16.0-beta.12 - 2026-07-22

- Cash back page now uses the site type scale instead of page-specific styling: Plus Jakarta Sans for UI, the editorial face for headings, and IBM Plex Mono for dollar amounts, matching shop, product, and checkout.
- Rewrote the four "How it works" cards: Earn on every order, Bring a friend, Spend it at checkout, One balance.
- Removed YITH's points/redeem summary box (duplicated the balance cards) and its orphaned Points history / Manage Points tab navigation, whose panels are inlined as sections. Both are dropped server-side, with a CSS guard.
- The referral slot no longer matches YITH's points summary. YITH Points & Rewards ships no referral code or share link, so the referral section only renders if a referral plugin outputs into the account endpoint; nothing is fabricated.
- Fixed the cart pill reverting to YITH's raw trophy banner: the observer added in beta.10 only scheduled a server refresh on totals changes and never re-ran the local capture, so a banner injected after first paint was missed. It now re-captures locally on any foreign DOM change and still re-reads from the server when totals change.

## 0.16.0-beta.11 - 2026-07-22

- Fixed: YITH's referral code and share link were hidden by a blanket rule on `.ywpar_myaccount_entry_info`. That block is surfaced again and its code/link are captured and presented as brand-styled copyable fields with Copy buttons; if either cannot be read, YITH's own referral UI is left visible and untouched.
- Reworked the cash back page layout: three stat cards at the top (Available balance, Total earned, Total applied), "How it works" as a 2x2 grid of numbered steps (earn 3%, refer friends, apply at checkout with a $5 minimum, it stacks up), a referral section with the copyable code and share link plus three small stat cards, and cash back history at the bottom.
- Total earned and total applied are derived from YITH's own points log (positive and negative entries), using the same dollar conversion as the balance card.
- The Manage Points convert-to-code form is folded into a "Turn your balance into a code" section instead of a separate tab. YITH's rendered output is split into slots and re-emitted verbatim, so all logic, nonces, and code generation stay with YITH.

## 0.16.0-beta.10 - 2026-07-22

- Removed the "Cart" page heading via Hello Elementor's page-title filter, with a CSS fallback.
- Cash-back pill on the cart is now larger and horizontally centered above the cart, in the same cyan treatment.
- Cash-back pill now updates live when cart totals change (coupon applied or removed, quantity changed). On each change the cart is re-requested and YITH's freshly rendered value is re-captured; the amount is never computed in the theme, so YITH's own rules (such as no points on the coupon-discounted amount) continue to hold.
- Restyled the "Add coupons" form: the Apply button was rendering with pink text and border, and is now a secondary-weight cyan pill button (cyan outline, filling cyan on hover) matching Proceed to Checkout, with the coupon input matching the theme's field styling.

## 0.16.0-beta.9 - 2026-07-22

- Restyled YITH Points & Rewards' cart/checkout points banner into the cyan cash-back pill. Because the cart is a client-rendered WooCommerce block cart, this uses a browser script (`assets/js/cart-rewards.js`) that reads YITH's own rendered "you will earn N Points" text (plugin stays the source of truth), drops the trophy, converts points to dollars to match the cash-back framing site-wide (100 points = $1.00, so 2280 points = $22.80), and applies the cash-back pill styling. Keyed on the rendered text, not a plugin class, so it is version-agnostic and a no-op when the banner is absent (e.g. logged-out). Enqueued on cart and checkout.

## 0.16.0-beta.8 - 2026-07-22

- Quantity input and Add to cart now sit on one row (desktop and mobile): quantity fixed at ~64px, the button fills the remaining width, both the same height and vertically aligned. The empty space below is left untouched for the later current-batch display.

## 0.16.0-beta.7 - 2026-07-22

- Reworked the product promotion pills to capture and restyle plugin-rendered output instead of recomputing from plugin internals (fixes the beta.6 regression where the cash-back pill vanished). An output buffer around the single-product summary lifts YITH Points & Rewards' own earn message (`p.ywpar_earn_points`) into the cyan cash-back pill, and YITH Dynamic Pricing's rule note (`.ywdpd-notices-wrapper`) into the amber B4G1 pill, then drops both into one flex row above the price. The plugins remain the single source of truth; nothing is computed from their APIs.
- Removed the beta.6 earn-rate computation, the `PEPSELECT_CASHBACK_DOLLARS_PER_POINT` constant in this file, and the native-message suppression filters/CSS that caused the regression. YITH's earn message now also styles in place as a cyan pill fallback if it is ever not captured.
- Activated the B4G1 pill: it now renders when YITH Dynamic Pricing prints a rule note for the product, showing the plugin's own note text ("Buy 4 get 1 free") and hiding the raw gray note. `pepselect_product_has_b4g1()` now takes the detected state and stays filterable. The quantity-discount table wrapper and the stock line are left untouched.

## 0.16.0-beta.6 - 2026-07-22

- Replaced the native YITH points message on the single compound page with a coded cash-back pill that reads the earn rate live from Points & Rewards (global rate or any per-product/category override), converts it to an effective percentage against price, and hides itself when Points & Rewards is inactive or the product earns nothing. The pill keeps the theme's existing cyan treatment; the native YITH message is suppressed via filter and CSS to avoid duplication.
- Built the Buy 4, Get 1 Free pill (gift icon, "Buy 4, get the 5th free") in the theme's amber treatment, matching the on-hold email. It is gated behind `pepselect_product_has_b4g1()`, which returns false until YITH Dynamic Pricing is installed, so it renders nowhere for now.
- Both pills sit in a wrapping flex row hooked into `woocommerce_single_product_summary` above the pricing, B4G1 first when present and cash-back second. The row is only emitted when at least one pill qualifies, so a lone cash-back pill leaves no empty gap.

## 0.8.0-beta.8 - 2026-07-18

- Rewrote Research context for nine compounds from a fresh sourced research pass, tying each bullet to the area of genuine research interest (skin and pigmentation, appetite and body-weight biology, wound healing, exercise capacity, mitochondrial energy) with its underlying study, while holding the mechanism-only, no-human-use register. PT-141, TB-500, and BPC-157 keep their prior bullets pending a tighter source pass, since verifiable primary studies were not confirmed for them.
- Moved the CAS number into the top of the View the sources disclosure, above the citations, so the legitimacy signal stays available without cluttering the main view.

## 0.8.0-beta.7 - 2026-07-18

- Removed the Specifications block (CAS, Formula, Form) from the compound description per direction; the description now flows from the summary paragraph into Research context.
- Rewrote all twelve compounds' Research context bullets into plainer, curiosity-driven language pointing toward the area of research interest, while holding the mechanism-only, no-human-use register. GHK-Cu's third bullet moved from MMP/TIMP matrix regulation to its antioxidant and repair-signaling activity. The four widely known human pharmaceuticals stay most conservative.
- Research context now renders full width in a balanced two-column list on desktop, single column on mobile.

## 0.8.0-beta.6 - 2026-07-18

- Forced the add to cart button to brand cyan (navy hover) over the plugin/Elementor purple default that was overriding it, across every state.
- Locked the View the sources toggle against the Elementor global button hover that turned it magenta.
- Image card and buy card now stretch to equal height, with the product image centered in its card.
- Related "More from the selection" cards rendered smaller than primary shop cards so they read as secondary content.
- Related products become a horizontal snap carousel on mobile, matching the homepage, with compact cards.

## 0.8.0-beta.5 - 2026-07-18

- Split the hero into two separate cards: a compact image card (smaller, contained) and a buy card holding the compound identity, pricing, and add to cart.
- Compound title is now large Georgia serif matching the COA hero treatment; applies to every compound.
- Add to cart recolored to brand cyan (navy on hover), replacing the default purple.
- Removed the short description above the price and the SKU/category/tag meta below add to cart.
- Rewards message restyled as a standalone pill above the pricing.
- Removed the duplicate testing-history heading: the COA carousel shortcode prints its own styled heading, so the theme no longer prints a second one above it.
- Image lightbox: clicking the image or expand control opens a floating, appropriately sized image over a dimmed backdrop (notify-dialog language) instead of the hover zoom.
- Related section eyebrow kept as Keep exploring; heading changed from You may also like to More from the selection. Related cards use the shared front-page card component and stylesheet so they match exactly.
- Nudged the COA carousel cards toward a more vertical proportion with less dead space.

## 0.8.0-beta.4 - 2026-07-18

- Redesigned the single compound page in the COA card layout: full-width soft band background, a breadcrumb, a hero card pairing the gallery (sized, contained, no longer oversized) with the buy area, the description in its own card, and COA-consistent section headings (cyan eyebrow over a serif h2) for Independent testing history and a You may also like related grid capped at three. The coded template now renders every section directly rather than through hooks, keeping the layout compact and card-based to reduce scrolling.
- All commerce renderers remain native WooCommerce (quantity discounts, add to cart, points, gallery); the COA history carousel keeps its shortcode in the testing-history card.

## 0.8.0-beta.3 - 2026-07-18

- Fixed the compound description block rendering inside the narrow product summary column (crushing the text into a strip over the price): the block now renders in a full-width zone below the summary, and related products and up-sells were re-sequenced to follow it.
- Restored the COA history carousel: it was placed by the legacy Elementor template that the coded template replaced, so it is now rendered explicitly via the [pepselect_product_coa_carousel] shortcode in its original position below the compound block. Plugin untouched.

## 0.8.0-beta.2 - 2026-07-18

- Fixed the coded compound page not appearing: the legacy Elementor Theme Builder single-product template was rendering these pages and never firing WooCommerce hooks, so the coded block had nothing to attach to. The theme now seizes the single-product template via template_include at the same late priority used by the archive and homepage, runs WooCommerce native single-product output (all commerce renderers fire), and declines Elementor Theme Builder override for the single location.

## 0.8.0-beta.1 - 2026-07-18

Milestone M4: single compound page (WEB-2E). Archive M3 is final at 0.7.0-beta.9.

- Added the coded compound description block on single product pages, replacing the default WooCommerce tabbed description. Renders the approved hybrid-register content: mechanism-only description with inline cyan reference superscripts, a Specifications table (CAS, Formula, Form), Research context bullets, a collapsed "View the sources" disclosure, and locked Intended Use and FDA disclaimer blocks. Products without a library entry fall back to the standard description with the legal blocks still enforced.
- Added the approved twelve-compound content library (descriptions, specifications, research context, and sources) keyed by normalized compound name. Three entries carry data flags for manual resolution: TB-500 specifications pending the fragment COA, and SS-31/GHK-Cu source DOIs pending verification.
- All commerce renderers are preserved untouched: add to cart, quantity discounts, points messaging, verification, and the product gallery keep their WooCommerce structure. The COA history carousel keeps its position through the plugin's existing shortcode after the product summary.

## 0.7.0-beta.9 - 2026-07-18

- Confirmation copy revised: "Once [compound] comes back in stock, we will notify you at [email]".

## 0.7.0-beta.8 - 2026-07-18

- Simplified card status bands to a single state: every pending batch, at the vendor or at the laboratory, now reads "Restocking Soon" on the calm cyan tone; the finer distinction belongs to the product page. 
- Versioned the status-map cache key with the theme version so label and mapping changes take effect immediately on release instead of waiting out the transient (the cause of "More coming" lingering after the beta.7 install).

## 0.7.0-beta.7 - 2026-07-18

- Rebalanced the card ratio: shorter media box, larger typography. Desktop titles at 30px serif, prices at 26px, stock status at 16px semibold, larger strength pills; mobile titles at 22px and prices at 21px with buttons unchanged.
- Reordered the card body per direction: strength pill, compound name, status band, availability, price, action. Status band sits between name and availability, availability sits directly above price, with a consistent spacing rhythm and recalculated symmetry slots.
- Renamed the incoming-stock band from "More coming" to "Restocking Soon".
- Guaranteed identical card rendering on every page: all card typography now carries component-scoped specificity so page-level heading and paragraph rules (the source of the homepage/shop mismatch) can no longer restyle it, and the small-viewport card scale moved into the shared card stylesheet.

## 0.7.0-beta.6 - 2026-07-18

- Added a clear confirmation state to the notify dialog: on the plugin's own success event, the form swaps to a "You're all set." view echoing the subscriber's email, with a Continue shopping action leading to the product page. Errors keep the form visible with the plugin's inline message.

## 0.7.0-beta.5 - 2026-07-18

- Added the theme-owned Back In Stock Notifier form template through the plugin's documented override mechanism (theme folder back-in-stock-notifier-for-woocommerce/default-form.php). All functional plumbing from the original is preserved: the field classes its JavaScript binds to, hidden product, variation, and security fields, the response container, and every extension hook, including the ones that render the consent row. Bootstrap presentation is replaced with design-system markup.
- Added form styling that loads with the coded shell, dressing the form everywhere it renders: the archive notify dialog now, product pages ahead of milestone M4. The plugin Status page should now report the subscribe form template as loaded from theme.

## 0.7.0-beta.4 - 2026-07-18

- Rebuilt the notify experience as the originally envisioned enlarged card: the notify action opens a centered dialog carrying the product image, strength, name, an invitation line, and the waitlist form, over a dimmed backdrop. Native dialog semantics provide the focus trap and Escape handling; backdrop click and the close control dismiss; browsers without dialog support fall through to the product page.
- Removed the in-card overlay panel and its styles; the plugin-skeleton neutralization moved into the dialog scope.

## 0.7.0-beta.3 - 2026-07-18

- Fixed notify panels rendering open and refusing to close: the panel display rule now yields to the hidden attribute, so panels start closed and the close control works.
- Hardened the panel close button on all interactive states against retained Elementor global button styles.
- Neutralized the waitlist plugin's unstyled Bootstrap-panel skeleton inside the card panel with scoped overrides (duplicate heading hidden, fields and consent row dressed in design tokens) pending the proper theme template override for the form.

## 0.7.0-beta.2 - 2026-07-18

- Archive heading grew into the editorial treatment: larger scale with "the standard." carried in the cyan serif accent, wider heading block, and a larger lead.
- The full catalog now loads on one page; pagination removed.
- Grid is three columns on desktop and two on tablet and mobile.
- Cards align symmetrically across the row: uniform title and status slots keep prices and actions on shared horizontal lines whether or not a card carries a status band; actions are slimmer at a fixed 44px with the shortened "Notify when available" label on one line.
- Notify now opens an in-place panel over the card carrying the waitlist plugin's real subscribe form for that product (rendered via its shortcode, submitted through the plugin's own handler); Escape or the close control dismisses, one panel open at a time, and the action falls back to the product page if the plugin is ever absent.

## 0.7.0-beta.1 - 2026-07-18

Milestone M3: compounds archive (WEB-2D). Footer M2 is final at 0.6.0-beta.3.

- Added the coded compounds archive template serving the shop page, product taxonomies, and product searches through a late template_include route, replacing the legacy Elementor loop and archive templates on these views. Product searches now honor the search term, show a result count, and offer a browse-everything path when nothing matches. Twelve products per page with design-system pagination.
- Archive heading carries the approved copy: "Compounds / Selection is the standard. / Pep Select carries what passes our review and nothing else. The details sit on every card."
- Rebuilt the shared compound card with the approved editorial typography: serif title, strength pill, "Available" and "Out of Stock" stock language, serif price, and an outlined Learn more action. Out-of-stock products swap the action for "Notify me when available", linking to the product page's back-in-stock form. The homepage grid uses the same component, and card styles moved to a shared stylesheet.
- Added a read-only bridge to the COA Archive plugin: pending batches surface on cards as status bands, with vendor stages reading "More coming" and laboratory stages reading "Waiting on lab test". Expected dates are deliberately not displayed, results are transient-cached for five minutes, and no plugin data is written.
- Order-tracking page: heading block centered, label restored to cyan and enlarged, form card centered.

## 0.6.0-beta.3 - 2026-07-18

- Redesigned the order-tracking page away from the centered competitor-style composition: the small Order Tracking label is now the page heading, followed directly by the description, in a left-aligned split layout with the form card on the right, mirroring the homepage FAQ section language. The large two-tone title is removed.

## 0.6.0-beta.2 - 2026-07-18

- Added a coded page template for /track-your-order/: centered eyebrow, navy headline with cyan serif accent, and lead copy, with the WooCommerce shortcode remaining the authoritative page content. Form fields stack full width inside the card, the submit button spans the card, and the plugin's default intro line is hidden in favor of the page lead.

## 0.6.0-beta.1 - 2026-07-18

Milestone M2: footer. Header M1 is final at 0.5.0-beta.2.

- Removed the Explore link group (All products, About us, FAQ) as duplicate navigation; the footer now carries the Support and Legal groups only, per the trust-floor rationale.
- Retargeted "Track your order" from the login-gated account orders endpoint to the guest-friendly /track-your-order/ page when it exists, with a safe fallback to the account endpoint until that page is published, so the link never 404s.
- Added design-system styling for the WooCommerce order-tracking shortcode form used by the guest tracking page.
- Compressed the mobile footer: the two remaining groups sit side by side, the FDA disclaimer drops one size step with tightened leading (content unchanged), and the bottom bar tightens. Desktop link columns cap their width and align right.
- Fixed an invalid gap-right property in the small-viewport rules (now column-gap).

## 0.5.0-beta.2 - 2026-07-18

- Added live compound search suggestions to the header: typing two or more characters queries a new read-only REST endpoint against live WooCommerce data and lists up to eight matching compounds with their strength tag, out-of-stock compounds included with a subtle note. Suggestions support mouse, touch, and full keyboard navigation (arrows, Enter, Escape) with an accessible combobox/listbox pattern; selecting one opens its product page. Enter without a selection still submits the standard search.
- Hardened the search pill radius under the header ID scope so retained Elementor global input styles can no longer flatten the rounded ends.
- Root cause recorded for the broken results page: the legacy Elementor product archive template ignores the search term and renders the full catalog; the coded compounds archive in milestone M3 replaces it.

## 0.5.0-beta.1 - 2026-07-18

Milestone M1: header. Homepage WEB-2C is complete and committed at 0.4.0-beta.8.

- Enlarged the header logo (224x72 desktop, 168x56 tablet, mobile unchanged at 144x52).
- Restyled the search as a single fully rounded pill and retired the visible magnifier submit button; the form still submits on Enter, and the submit control remains in the accessibility tree, reappearing on keyboard focus.
- Centered the search pill on the page axis with a symmetric three-column header grid so it aligns with the centered primary navigation.
- Compacted the rewards action to the star icon plus the YITH points balance; the visible "Rewards" label is removed while the accessible name remains. Logged-out visitors see the star only, linking to the rewards page.
- Verified, no change required: the coded header already forces the COAs navigation item to the canonical /testing/ route, resolving the /coas/ destination bug recorded in the COA Archive plugin handoff.

## 0.4.0-beta.8 - 2026-07-18

- Allowed the coded shell to render inside the WordPress Customizer preview so Homepage Hero controls preview against the real homepage; the Elementor editor exclusion and the admin legacy-shell parameter are unchanged.
- Rebuilt the hero as a light composition: the family image bleeds to the section edge and dissolves behind the copy through a gradient mask, with navy headline text, cyan serif accent, opened serif leading, and navy button variants. Removed the now-obsolete frame corner control from the Customizer; image, fit, position, and zoom controls remain.
- Applied approved round-3 copy: hero lead rewritten without the em dash, "Explore Our Selection" CTA, "The Current Selection / A short list, on purpose." featured heading set, Direction C "Everyone has a COA." Why-section set with items Selected first, Filed to the batch, and Nothing rushed at you, and the "records stay online forever" policy stated as approved.
- Replaced the homepage FAQ with the five-question set under "The questions we hear most.", including verified shipping facts, the Square payment-link flow, and the damaged-or-incorrect-order policy summarized from the published Refund and Shipping Policy.
- Placed the Why Pep Select section on the soft cyan band to restore the alternating section rhythm.
- Fixed non-concentric corners: card and editorial photos now carry an inner radius parallel to their rounded containers, media boxes lock to a 4:5 aspect so cover cropping stays negligible, and the editorial visual uses uniform feature-radius corners.

## 0.4.0-beta.7 - 2026-07-17

- Added a Homepage Hero section to the WordPress Customizer with an image picker and live-preview slider controls for horizontal position, vertical position, zoom (100-160%), frame corner roundness (0-48px), and an image-fit choice.
- Saved values are stored as theme mods, bounded and sanitized server-side, and rendered as CSS custom properties attached to the homepage stylesheet; templates remain untouched and the parent-theme rollback stays clean.
- A Customizer-chosen image takes precedence over the coded default candidates; clearing the control returns to the approved PS-laying_fam family image chain.
- Slider changes render instantly in the Customizer preview through a postMessage bridge; choosing a different image refreshes the preview.

## 0.4.0-beta.6 - 2026-07-17

- Removed the hero micro-proof line and rebalanced the hero CTA spacing.
- Pointed the hero at the approved PS-laying_fam family image, resolved through the current environment's uploads URL with ordered fallbacks (scaled file, original file, previous approved image, branded panel).
- Made all four hero-frame corners the uniform feature radius, retiring the asymmetric corner accent.
- Added documented hero image framing controls to foundations.css (--pep-hero-image-fit, --pep-hero-image-position, --pep-hero-image-zoom) so the image can be reframed inside the rounded frame without template changes; overrides also work from the WordPress Customizer Additional CSS panel.

## 0.4.0-beta.5 - 2026-07-17

- Replaced the hero product composition with a single approved family image resolved from the Media Library by stored file path, with a branded image-ready fallback; removed the floating product tiles and the direct-purchase product card.
- Vertically centered the hero CTA row with equal breathing room between the lead paragraph and the micro-proof line.
- Removed the confidence strip, batch identity, Quality Archive, and final CTA sections from the homepage render; template files remain in the repository for the legacy-retirement register. The homepage now flows hero, featured compounds, Why Pep Select, FAQ.
- Added a single "Open the Quality Archive" action to the Why Pep Select section so the archive keeps one clear mid-page route after the section consolidation.
- Rebuilt featured-compound selection: in-stock priority compounds fill slots in order (GLP-3 R, GLP-2 T, GHK-CU, NAD+, TB-500, BPC-157), and remaining slots rotate daily through the rest of the eligible catalog with a date-seeded shuffle that stays cache-stable within a day.
- Added a strength badge to the storefront product card sourced from the product_tag taxonomy, mirroring the legacy loop card; products without a strength tag render no badge.
- Removed the visible "Catalog image" caption from the Why Pep Select photo while keeping accessible alt text.
- Restyled the FAQ as individual bordered cards and declared explicit palette backgrounds and colors on every accordion button state so retained Elementor global button styles can no longer bleed an off-palette highlight into the open item.
- Tightened the homepage section rhythm across desktop, tablet, and mobile breakpoints.

## 0.4.0-beta.4 - 2026-07-17

- Fixed hero product photos rendering as hard white rectangles on the dark stage: opaque catalog photography now displays as intentional rounded white tiles with a border and elevation instead of the transparent-cutout treatment the previous CSS assumed.
- Hid the third stacked hero photo, whose opaque background merged with the floating product card into a single malformed white surface, and retuned the second tile (including the two-product variant) so the card zone stays clear.
- Separated the floating hero product card from adjacent white tiles with the standard border token and a slightly stronger shadow, and softened the stage ground blur now that tiles carry their own elevation.
- Applied the same rounded-tile treatment to the batch identity photo inside the dark composition and corrected the image-fallback text color for contrast on the new white surface.
- No template, copy, markup, or data changes; all edits are scoped to `assets/css/homepage.css`.

## 0.4.0-beta.3 - 2026-07-16

- Retained the beta.2 homepage architecture, private preview gate, WooCommerce product selection, and COA Archive boundaries.
- Reframed the hero around the approved behind-the-label idea using the evidence-safe fallback paragraph and conversational archive CTA.
- Strengthened the existing dynamic hero composition and featured-product cards without adding dependencies or template overrides.
- Reduced repeated technical language across the supporting sections while preserving the verified FAQ and identity labels.

## 0.4.0-beta.2 - 2026-07-16

- Replaced the visually rejected compliance-led homepage preview with a product-first storefront while preserving the administrator-only front-page gate.
- Added a dark product hero using live WooCommerce images, prices, stock states, and links without hard-coded product data.
- Moved four dynamically selected compounds near the top and redesigned the reusable homepage product card.
- Concentrated batch identity and Quality Archive guidance into two later sections without duplicating COA records, statuses, or sorting logic.
- Added a compact accessible FAQ using supported existing content and omitted the obsolete order-link item.
- Preserved the coded header and footer, component isolation, responsive behavior, reduced motion, and WooCommerce/COA ownership boundaries.

## 0.4.0-beta.1 - 2026-07-16

- Added the coded eight-section WEB-2C homepage behind the administrator-only `?pepselect_home_preview=1` front-page gate.
- Preserved the existing Elementor homepage for normal and unauthorized requests and retained the coded WEB-2B shell.
- Added WooCommerce API-based featured-product selection and a reusable homepage product-card foundation without template overrides.
- Added a safe COA Archive fallback because the stable plugin exposes no supported generic homepage-preview interface.
- Isolated coded-header logo and Rewards presentation from page-specific Elementor and WooCommerce styles.
- Added responsive homepage styles, visible focus, practical touch targets, and reduced-motion handling without JavaScript.

## 0.3.1 - 2026-07-16

- Made the approved coded header and footer the default shell on supported front-end requests while preserving all Elementor page content.
- Added the administrator-only `?pepselect_legacy_shell=1` emergency route to restore Elementor Header #1323 and Footer #391 for one uncached request.
- Excluded WordPress administration, Elementor editor, Customizer, login, REST, AJAX, cron, feed, and CLI contexts from coded-shell replacement.
- Preserved the stored Elementor templates and conditions, Hello Elementor theme rollback, WooCommerce integrations, and all business logic.

## 0.3.0 - 2026-07-16

- Added the first coded footer behind administrator-only `pepselect_footer_preview` and combined `pepselect_shell_preview` request flags.
- Preserved current footer branding, research-use statements, support address, internal destinations, canonical `/testing/` COA route, and exact published FDA disclaimer through environment-neutral WordPress and WooCommerce URL helpers.
- Added responsive dark footer presentation with semantic link groups, visible focus, practical mobile targets, compact mobile hierarchy, and reduced-motion support.
- Preserved the independent coded-header preview, public Elementor Header #1323/Footer #391, all Elementor conditions, WooCommerce behavior, and business logic.

## 0.2.3 - 2026-07-16

- Increased desktop logo presence, reduced desktop search width, and compacted the desktop Rewards control without changing header height or the `1200px` inner-width foundation.
- Made the mobile Rewards link route-aware and removed its unconditional active treatment.
- Separated non-current mobile hover styling from the current-page cyan accent and dark background while preserving visible keyboard focus.
- Preserved version `0.2.2` mobile structure, search, controls, tap targets, navigation behavior, reduced motion, and administrator-only preview isolation.

## 0.2.2 - 2026-07-16

- Refined only the coded header's mobile layout at widths up to `767px`.
- Increased announcement readability and logo size, retained three 44px action controls, and expanded product search to a full-width 48px row.
- Kept Rewards out of the mobile icon row and clearly labeled it inside the accessible collapsible navigation.
- Preserved desktop/tablet styling, preview restrictions, navigation JavaScript, WooCommerce behavior, and Elementor conditions.

## 0.2.1 - 2026-07-16

- Rendered the current Pep Select logo from WordPress Media Library attachment `595`, preferring a configured WordPress Custom Logo and retaining a site-name fallback.
- Kept YITH as the rewards-balance source of truth through its documented `[yith_ywpar_points]` shortcode, with a label-only fallback when no supported output is available.
- Preserved the administrator-only preview boundary and ordinary Elementor Header #1323 behavior.

## 0.2.0 - 2026-07-16

- Added a coded announcement, logo, product search, rewards/account/cart controls, and five-link primary navigation.
- Restricted the coded header to logged-in administrators using `?pepselect_header_preview=1`.
- Added preview-only CSS and dependency-free mobile navigation JavaScript with Escape and responsive reset behavior.
- Reused the confirmed YITH points and Xootix side-cart shortcodes when registered, with safe account/cart fallbacks.
- Preserved ordinary Elementor Header #1323 requests, Footer #391, Elementor conditions, WooCommerce templates, and all business logic.

## 0.1.1 - 2026-07-16

- Rebuilt the distributable archive with portable forward-slash ZIP entry paths.
- Corrected the WordPress installation failure caused by Windows backslashes in the version 0.1.0 archive entries.
- Changed no theme behavior, templates, integrations, or design tokens.

## 0.1.0 - 2026-07-16

- Added the Hello Elementor child-theme metadata and guarded bootstrap.
- Added front-end parent, child, and foundation stylesheet loading with file-version cache busting.
- Added approved WEB-2A CSS design tokens and reduced-motion support.
- Added package, scope, screenshot, and future-directory documentation.
- Added no templates, scripts, external dependencies, business logic, or WooCommerce overrides.

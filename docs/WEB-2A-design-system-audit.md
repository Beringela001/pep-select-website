# WEB-2A — Current Design System Audit

**Checkpoint:** Documentation only

**Sources:** Elementor JSON exports in `site-exports/elementor/`, `WEB-1-elementor-audit.md`, `WEB-1-staging-findings.md`, and `WEB-2-rebuild-plan.md`

**Environment impact:** None. No WordPress, Elementor, plugin, configuration, export, credential, or database content was changed.

## 1. Purpose and evidence limits

This report records the visual foundation that can be demonstrated from the supplied Elementor exports and the completed WEB-1/WEB-2 documentation. It is an inventory, not a redesign or final specification.

The export set contains the current-candidate header, footer, homepage, product archive, product-card loop, and single-product templates, plus legacy BioQuantum and Peptides Divas material. It does **not** contain exports for My Account, cart, checkout, Contact, FAQ, About, military/first responder, or legal-policy pages. WEB-1 confirms that several of those surfaces render on Staging, but their exact visual values cannot be derived from this export set.

The JSON files have `content`, `page_settings`, `version`, `title`, and `type` at the top level. They do not contain an exported Elementor `site_settings` or global-style payload. There are no usable `__globals__` token references in the current-candidate exports. The two saved-page exports that include `__globals__` contain only an empty `title_color` mapping. Therefore, the exports prove extensive template-local styling but do not prove the current contents of Elementor Site Settings.

## 2. Export coverage by surface

| Surface | Primary export evidence | Evidence status |
|---|---|---|
| Header and search | `header-elementor-73.json`; WEB-1 records the deployed template as Header #1323 after the native WordPress Menu replacement | Colors, search styling, spacing, and responsive overrides are visible in the supplied export; the final Staging menu widget state must be visually rechecked because the export identifier differs from the deployed template ID |
| Footer | `footer-elementor-70.json`; WEB-1 records the deployed template as Footer #391 | Partial confirmed styling; current assignment and destinations require Staging verification |
| Homepage | `saved-page-pepselect-homepage-571.json`; WEB-1 records the static Home page as post ID 79 and the composition as Homepage #571 | Extensive local visual values are confirmed; hidden and copied remnants make it unsuitable as a canonical system |
| Product archive | `products-archive-elementor-441.json` | Confirmed local archive and search values |
| Product cards | `loop-item-dark-elementor-65.json` | Confirmed shared Dark Loop values; shared-consumer risk remains |
| COA cards | `loop-item-coa-elementor-485.json` | Confirmed card/button typography and colors; legacy COA retirement status is separate from visual evidence |
| Single product | `single-product-elementor-462.json`; `single-product-elementor-279.json` is unused | #462 is the active-candidate visual source; #279 is historical comparison only |
| Account area | No Elementor JSON export supplied | Exact values cannot be confirmed; WEB-1 confirms the functional account flows work |
| Policy pages | No Elementor JSON export supplied | Exact values cannot be confirmed; WEB-1 confirms the pages render |
| Contact, FAQ, About, military page | No page export supplied; BioQuantum saved pages are not Pep Select sources | Exact Pep Select values cannot be confirmed from JSON |

## 3. Confirmed existing values found in exports

### 3.1 Colors

The table below records repeated Pep Select-candidate values. “Observed use” describes the exports; it is not a recommendation or approval of the role.

| Value | Observed use and location |
|---|---|
| `#0A2540` | Dominant deep navy across Homepage #571, Products Archive #441, Dark Loop #65, COA Loop #485, and both product templates; used for headings, dark surfaces, and primary actions |
| `#0A1E40` | Header text/icon navy in Header #73; close to, but not identical to, `#0A2540` |
| `#00AADD` | Cyan accent across the homepage, archive, product loops, and product templates; used for links, borders, buttons, and emphasis |
| `#5E6E82` | Secondary gray text in homepage/archive/current-candidate content; the homepage contains one explicit `link_color` using this value |
| `#DCE5EE` | Light border/divider color in header search, archive search, homepage, Dark Loop, and product surfaces |
| `#EAF6FC` | Pale cyan surface and hover fill in homepage, archive, loops, and product templates |
| `#F3F8FC` | Pale page/section surface in homepage, archive, and product-related layouts |
| `#F7FAFD` | Very pale surface in the homepage and current-candidate template family |
| `#FFFFFF` | Primary white surface/text value throughout all current-candidate exports |
| `#111111` | Dark text in Dark Loop and related current-candidate button content |
| `#899DD7` and `#ADD4EA` | Muted periwinkle/light-blue gradients, button fills, and borders in current-candidate homepage/product styling |
| `#12263ACC` | Translucent dark overlay in the current-candidate template family |
| `#1A548F` | Secondary header blue in Header #73 |
| `#F9FAFB`, `#7A7A7A`, `#DBDBDB` | Header search background, input text, and border respectively |
| `#0056CF`, `#0662E4`, `#209633` | Values present in unused Single Product #279 for blue primary actions and green status treatment; they are not evidence of the active #462 standard |
| `#336699` | Local action color in Single Product #462 and footer content; its relationship to the core blue palette is not defined |

The export corpus contains `#FFFFFF` 82 times, `#0A2540` 37 times, `#00AADD` 32 times, `#5E6E82` 25 times, `#111111` 20 times, and `#DCE5EE` 20 times in settings identified as colors. Frequency supports their current use but does not establish accessibility, brand approval, or semantic ownership.

Legacy colors are also present but must remain historical evidence only:

- BioQuantum exports use purples such as `#3E03BB`, `#3F06BC`, `#3600AA`, and `#A082FF`.
- Peptides Divas uses golds such as `#E6AC3D`, `#E6C372`, `#F6E3A8`, and `#C9A14A`, plus dark blue `#1E2248`.
- These palettes are not Pep Select design-token candidates under WEB-2.

### 3.2 Typography

#### Families found

| Family | Confirmed usage |
|---|---|
| Plus Jakarta Sans | Header, footer, Dark Loop, Homepage #571, product templates, and many controls |
| Georgia | Homepage headings, product/archive content, and COA-related editorial display |
| IBM Plex Mono | COA Loop, Dark Loop, archive search/labels, and homepage labels/actions |
| Inter | Homepage #571 body/content and Peptides Divas legacy content |
| Montserrat | BioQuantum About and Contact only; historical, not a Pep Select source |
| Playfair Display | Peptides Divas only; historical, not a Pep Select source |

#### Sizes, weights, line heights, and tracking

Across the exports, the most common explicitly set desktop sizes are `16px` (51 occurrences), `14px` (37), `12px` (33), `48px` (21), and `18px` (18). Other repeated desktop sizes include `20px`, `24px`, `30px`, and `36px`; outliers include `11px`, `40px`, `50px`, `60px`, and `72px`.

The most common mobile overrides are `30px` (24 occurrences), `14px` (8), and `20px` (4). Tablet overrides are much sparser: `16px` appears 13 times and `36px` 10 times. This does not form a complete type scale; it shows selective overrides and inherited values.

Explicit weights are mostly `400` (105 occurrences) and `500` (50), with `600` (13) and `700` (6) used less often. The most frequent desktop line heights are `1.5em`, `1.2em`, and `1.625em`, followed by `1em`, `1.1em`, and `1.3em`. Some widgets use pixel line heights, including values such as `12px`, `18px`, `24px`, and `57px`, which makes proportional scaling inconsistent.

Tracking frequently uses `0.18em` and `0.22em` for uppercase/label-like text. Other values include `0.14em`, `0.1em`, `1px`, and `-0.01em`. Many tablet and mobile tracking overrides are empty, so their computed behavior depends on inheritance rather than an explicit responsive rule.

### 3.3 Buttons and states

| Export/component | Confirmed default | Confirmed hover/state behavior |
|---|---|---|
| Homepage primary “View Compounds” | Plus Jakarta Sans `16px`/`500`; white on `#0A2540`; `1px` translucent white border; `10px` radius; `16px 28px` padding | Background `#123E6D`; `0.7s` transition and `shrink` animation; mobile padding `14px 10px` |
| Homepage secondary “Explore COAs” | Plus Jakarta Sans `16px`/`500`; `#0A2540` on white; `1px #00AADD` border; `10px` radius; `16px 28px` padding | Background `#EAF6FC`; same transition/animation pattern |
| Homepage “View All Compounds” | IBM Plex Mono `14px`/`500`; `#00AADD`; transparent button; wrapper uses `1px #00AADD`, `6px` radius, and `12px 24px` padding | Text `#0A2540`; wrapper background `#EAF6FC` |
| Homepage “View Catalog” | Georgia `16px`/`400`; white on `#00AADD`; `6px` radius; `14px 20px` padding | Cyan text on white; `0.7s` transition and `shrink`; mobile padding `14px 10px` |
| Dark Loop action | Plus Jakarta Sans `16px`/`500`; `#111111` on white; `1px #00AADD`; `10px` radius; `16px 28px` padding | Black on `#EAF6FC`; `0.7s` and `shrink`; mobile type `14px`, padding `16px 10px` |
| COA Loop action | IBM Plex Mono `500`; `#00AADD` background | Hover background `#152541`; the complete text/disabled/focus specification is absent |
| Single Product #462 COA action | Plus Jakarta Sans `500`; white on `#336699`; `10px` radius; `15px 20px` padding | No complete hover/focus/disabled specification is visible |
| Header search control | Plus Jakarta Sans `16px`; `#F9FAFB` field; `#7A7A7A` input text; `1px #DBDBDB`; `100px` pill radius; `12px 25px` field padding | No explicit, complete focus treatment is present in the export |

The exports do not define complete active, keyboard-focus, disabled, busy/loading, or error states for the current button family. BioQuantum and Peptides Divas pill buttons are legacy patterns and must not fill these gaps.

### 3.4 Links

Link styling is not systematic in the exports. One explicit `link_color` is `#5E6E82`. Multiple widgets instead use generic `color`, `hover_color`, or button settings. Current-candidate hover values include `#0A2540`, `#00AADD`, `#FFFFFF`, and `#000000`, depending on context.

No export-wide rule confirms underlines, visited-link styling, keyboard focus, or a consistent distinction between inline text links and action links. Footer and navigation destinations are known from WEB-1, but the supplied exports do not establish a canonical link-state system.

### 3.5 Containers and spacing

Confirmed layout values include:

- `content_width: full` occurs 108 times across container/section settings.
- A boxed width of `1200px` occurs 39 times; `1250px` occurs 4 times.
- Repeated gaps include `24px` and `48px`; zero-gap containers also occur frequently.
- Repeated internal padding includes `20px`, `28px`, and `32px` on all sides.
- Repeated section padding includes `100px 0 64px`, `112px 0`, `100px 0 100px`, `60px 0 96px`, and `50px 0 50px`.
- Common tablet side padding is `20px`; common mobile side padding is `10px` or `20px`.
- Header search uses `12px 25px`; archive/COA search uses `14px 20px`, with mobile left padding reduced to `10px`.
- Negative top margins are used repeatedly, including `-8px` (23 occurrences) and smaller groups of `-10px` and `-15px`. WEB-1 also identified `-20px`, `-25px`, `-30px`, and `-35px` responsive offsets in shared/current templates.

These values demonstrate patterns, not a coherent spacing scale. The simultaneous use of `1200px` and `1250px` containers and extensive full-width sections is an unresolved system decision.

### 3.6 Radii, borders, shadows, and cards

Confirmed repeated treatments include:

- `1px` borders on all sides occur 48 times, commonly with `#DCE5EE`, cyan, or contextual colors.
- Repeated radii include `6px`, `10px`, `12px`, `20px`, `22px`, `24px`, and `100px` pills.
- `22px` card radius occurs 23 times, while `28px` card padding occurs 17 times.
- Images use repeated `20px` and `24px` radii.
- A repeated hover shadow is `0 19px 19px -3px rgba(0, 0, 0, 0.1)`; it appears 11 times in settings.
- WEB-1 found the same card-shadow CSS block repeated 10 times across BioQuantum and Peptides Divas exports. That legacy repetition is evidence of duplication, not a Pep Select standard.
- Current-candidate cards combine white or pale-blue surfaces, light borders, large radii, gradients, and hover transitions, but not through shared exported tokens.

Status badges are present in multiple exports, but their visual and semantic mappings are not centralized. The unused #279 green treatment and active-candidate blue/cyan treatments cannot safely be merged into a status palette without confirming the underlying status meanings.

### 3.7 Forms and search fields

Current Pep Select-candidate field evidence is strongest for search:

- Header search: Plus Jakarta Sans `16px`, `#F9FAFB` background, `#7A7A7A` text, `1px #DBDBDB` border, `100px` radius, and `12px 25px` padding.
- Archive/home COA search: IBM Plex Mono `14px`, `1px #DCE5EE` border, `12px` radius, `14px 20px` field padding, and a `#0A2540` submit control with `12px` radius and `10px 40px` padding. Mobile field padding becomes `14px 0 14px 10px`; submit padding becomes `14px 10px`.

The only full contact-form styling export is BioQuantum Contact #413: Montserrat `11px` labels, white fields, `#DFDDDE` borders, `8px` radii, and a purple pill submit button. It is inherited-brand evidence and must not be used as the Pep Select form standard.

The supplied exports do not confirm Pep Select text-area, select, checkbox, radio, validation, help text, disabled, autofill, error, success, or keyboard-focus styling. WEB-1 confirms the Staging contact form submits, but that functional result does not establish its design values.

### 3.8 Desktop, tablet, and mobile behavior

The JSON uses Elementor device-suffixed settings such as `_tablet` and `_mobile`; it does not export the site’s actual breakpoint pixel configuration. The presence of a tablet/mobile override therefore confirms a device-specific adjustment, not the precise viewport threshold at which it activates.

Confirmed patterns include:

- Header #73 has separate desktop and mobile search arrangements and substantial tablet/mobile overrides.
- Dark Loop #65 reduces button type from `16px` to `14px`, compresses horizontal button padding, and uses negative margins/overflow-dependent positioning.
- Archive #441 has relatively few responsive overrides compared with the homepage and legacy saved pages.
- Homepage #571 has 197 mobile and 178 tablet-specific settings, but also contains two entire sections hidden at all breakpoints. The volume of overrides is not evidence of a reliable responsive system.
- Single Product #462 has 18 mobile and 11 tablet-specific settings and uses negative offsets; WEB-1 visually confirmed that mobile product type, bundle options, and testing history are too small/cramped.
- The legacy BioQuantum and Peptides Divas pages contain very high numbers of responsive overrides. They are historical evidence of fragile, template-by-template tuning.

WEB-2 defines the later verification widths as 1440, 1280, 1024, 768, 480, 430, 390, 375, 360, and 320px. Those are QA targets, not values proven to be the current Elementor breakpoints.

### 3.9 Global Elementor styles versus local styles

**Appears to exist conceptually:** Repeated colors, fonts, radii, widths, and button treatments suggest an informal visual language. The recurring navy/cyan/light-surface palette and Plus Jakarta Sans/Georgia/IBM Plex Mono family are recognizable across current-candidate templates.

**Confirmed in the exports:** These values are mostly hard-coded in individual containers and widgets. No usable exported Global Color, Global Font, or Site Settings definitions bind them together. The JSON contains no site-wide global-style payload and virtually no non-empty global token references.

**Cannot be inferred:** Elementor Site Settings may exist in the WordPress database even though they were not included in these exports. Their actual contents, whether active widgets inherit them, and whether theme or plugin CSS overrides them require a later read-only Staging inspection before implementation.

## 4. Inconsistent or duplicated values

### 4.1 Color and role conflicts

- Deep navy is split between at least `#0A2540` and `#0A1E40`; `#152541`, `#1A548F`, and `#336699` introduce further dark/medium blues without documented roles.
- Primary actions vary among navy, cyan, `#336699`, and—only in unused #279—`#0056CF`.
- Hover behavior is inconsistent: darkening, swapping foreground/background, pale-cyan fill, white fill, transparent alpha changes, and shrink animation all appear.
- Secondary text and links are not separated consistently; `#5E6E82` is used as both text and an explicit link value.
- Status colors are not mapped to confirmed semantics. Green in unused #279 cannot be assumed to mean a current approved success or availability state.

### 4.2 Typography conflicts

- Current-candidate templates mix Plus Jakarta Sans, Georgia, IBM Plex Mono, and Inter without a documented role matrix.
- Body/control sizes include `11px` and `12px`, while headings range through `30px`, `36px`, `40px`, `48px`, `50px`, `60px`, and `72px`.
- Line height mixes unitless/em and fixed pixel values.
- Large tracking (`0.18em` and `0.22em`) is applied locally rather than through a label token.
- Sparse tablet/mobile overrides leave many values dependent on inheritance, while other templates over-specify nearly every device value.

### 4.3 Layout and component conflicts

- Boxed containers use both `1200px` and `1250px`.
- Common card radii span `10px`, `12px`, `20px`, `22px`, and `24px`; pills use `100px`.
- Similar action controls use `6px`, `10px`, `12px`, and `100px` radii and several padding sets.
- Search fields use two unrelated shapes: a `100px` header pill and a `12px` archive/COA field.
- Negative margins and hidden-overflow fixes substitute for explicit responsive layout rules in shared/current templates.
- Repeated one-off gradients, overlays, and hover effects produce similar cards through different settings.

### 4.4 Repeated inline CSS and widget styling

WEB-1 counted local custom-CSS blocks in the exports: Header `1`, Dark Loop `1`, Products Archive `1`, BioQuantum About `6`, BioQuantum Contact `2`, Peptides Divas `10`, Homepage #571 `20`, Single Product #279 `6`, and Single Product #462 `4`.

Specific repeated patterns include:

- A light gradient repeated nine times across Dark Loop and Homepage #571.
- Two section-background gradient directions repeated five times each across the homepage/product family.
- A gold `.text-gradient` rule repeated four times across homepage/product templates, despite gold being associated with legacy Peptides Divas material.
- Repeated embedded badge CSS: six blocks in BioQuantum, three in the Pep Select homepage, and two in Peptides Divas.
- Repeated card-shadow CSS in the inherited-brand exports.

These blocks increase Elementor editor payload, make states difficult to update consistently, and compound the memory risk already confirmed on Staging. They should be consolidated only during later implementation, after ownership and consumer impact are known.

## 5. Values that cannot yet be confirmed

The following require a read-only Staging inspection, approved brand source, or later visual decision. They must not be invented from the exports:

- The exact contents of Elementor Global Colors, Global Fonts, Theme Style, and breakpoint settings.
- The production font files, licensed weights, loading method, and fallbacks actually delivered to browsers.
- The authoritative brand navy where `#0A2540` and `#0A1E40` conflict.
- Canonical semantic mappings for success, warning, error, information, stock, sale, COA/testing, and rewards statuses.
- Exact account, cart, side-cart, checkout, Contact, FAQ, About, military/first responder, and policy-page visual values.
- WooCommerce and third-party plugin control styles that are injected outside Elementor JSON, including YITH rewards, VerifyPass, side cart, bundle controls, checkout notices, and account tables.
- Current computed link underlines, visited states, browser focus rings, autofill styles, validation states, and high-contrast behavior.
- The actual Staging header appearance after ElementsKit navigation was replaced with Elementor’s native WordPress Menu widget.
- The current computed widths/gutters after theme styles, browser viewport, and nested Elementor containers interact.
- Which current shadows, gradients, animations, and status treatments are intentional Pep Select brand decisions versus copied or abandoned experiments.
- The contrast ratios of alpha colors over their real rendered backgrounds; hex values alone are insufficient for translucent combinations.

## 6. Accessibility risks evidenced by the audit

- `11px` and `12px` text occurs repeatedly. WEB-1 independently confirms undersized mobile product typography and testing history.
- Large letter spacing on small uppercase labels can reduce readability, especially at `11px`–`12px`.
- Header/search placeholder-style gray values need computed contrast testing against their actual backgrounds.
- Translucent colors such as `#FFFFFF0D`, `#FFFFFF14`, and `#12263ACC` cannot be assumed to meet contrast requirements.
- Some mobile actions compress horizontal padding to `10px`; exports do not prove a minimum `44px` target height/width.
- Icon-only header, menu, cart, search, drawer, and status controls do not have a confirmed system for accessible names, focus, or hit areas.
- The exports do not show a clear, consistent keyboard-focus treatment for buttons, links, fields, cards, menus, drawers, or accordions.
- Hover-only color and shrink effects are not sufficient for keyboard or touch users; reduced-motion behavior is not defined.
- Status meaning is visually inconsistent and may rely on color alone.
- Negative margins, fixed minimum heights, overflow hiding, and extensive device overrides create zoom, reflow, clipping, and horizontal-scroll risk.
- Full-width and boxed containers are mixed without a confirmed readable text measure for long policy or account content.
- Broken search presentation and oversized result imagery, already confirmed by WEB-1, create hierarchy and reflow problems.
- The Staging FAQ’s intermittent missing accordion rendering requires a non-ElementsKit, keyboard- and screen-reader-safe presentation; content must not be treated as deleted.

## 7. Recommended canonical design-token categories for the rebuild

This section proposes categories and ownership only. It does not approve final values.

### 7.1 Three-layer token model

1. **Primitive tokens** — raw color values, font families/weights, size steps, spacing steps, radii, border widths, shadows, opacity, duration, and easing.
2. **Semantic tokens** — page/surface/text/border/action/focus/status roles that refer to primitives, such as `color.text.primary`, `color.action.primary`, or `color.status.warning`.
3. **Component tokens** — deliberate mappings for header, footer, button, link, field, card, badge, alert, search, drawer, table, accordion, and modal states.

### 7.2 Categories to define

| Category | Required token groups |
|---|---|
| Brand color | Primary/secondary brand colors, accent, neutrals, surfaces, overlays, and on-color text |
| Semantic color | Text hierarchy, links, actions, borders, focus, disabled, success, warning, error, information, stock, sale, COA/testing, and rewards |
| Typography | Display, page heading, section heading, card title, body, small body, label/eyebrow, control, code/data; family, size, weight, line height, tracking, and fallback for each |
| Spacing | Primitive step scale plus semantic section, stack, cluster, grid, field, card, and control spacing |
| Containers | Full-bleed rules, maximum content widths, reading width, gutters, nested-container behavior, and alignment |
| Shape | Border widths, component radii, pill rule, image radius, and clipping behavior |
| Elevation | Default/hover/drawer/modal shadow levels and rules for when elevation is permitted |
| Motion | Durations, easing, hover movement, drawer/modal transitions, loading behavior, and reduced-motion alternatives |
| Responsive | Approved breakpoints, mobile-first rules, type changes, gutters, grids, navigation modes, and component-specific reflow |
| Interaction | Default, hover, focus-visible, active, selected, disabled, loading, success, warning, error, and empty states |
| Accessibility | Focus indicator, contrast thresholds, touch-target minimum, readable type minimum, labels/help/errors, icon naming, and status text |

### 7.3 Source-of-truth and ownership recommendation

- Use one approved token register as the human-readable source of truth.
- Mirror editor-safe brand, typography, and basic layout tokens into Elementor Site Settings only after backing up/exporting the existing settings and testing on Staging.
- Keep durable behavior in WooCommerce, Pep Select Site Core, or the dedicated plugin that owns it. Elementor tokens may style presentation but must not encode commerce, rewards, VerifyPass, COA, account, or order logic.
- Use a justified presentation stylesheet only for global/component states that Elementor cannot express reliably. Do not create a child theme until WEB-2A confirms it is necessary.
- Do not restore ElementsKit Lite or Pro.
- Keep legacy exports as evidence and rollback artifacts, not token sources.

## 8. Proposed WEB-2A implementation sequence

Implementation begins only after this audit and the approved design decisions recorded under “Approved WEB-2A Design Decisions” are reviewed for implementation.

### 1. Brand color tokens

- Implement the approved Primary Pep Select Navy while retaining the conflicting export values only as current-state audit evidence.
- Define primitive and semantic roles, including on-color text and status meanings.
- Test every proposed foreground/background pairing, including translucent overlays.
- Exclude BioQuantum, Peptides Divas, and unused-template colors unless independently approved as Pep Select values.

### 2. Typography tokens

- Verify font files, licensed weights, loading, and fallbacks.
- Assign one explicit role to each approved family and remove accidental role overlap.
- Define responsive size, weight, line-height, and tracking values without reusing tiny legacy labels.
- Confirm long-form reading styles for policies/account content separately from marketing display styles.

### 3. Spacing and container tokens

- Choose one spacing scale and map section, stack, grid, card, field, and control gaps to it.
- Approve maximum content and reading widths, gutters, full-bleed rules, and nested alignment.
- Replace negative-margin positioning with intentional grid/flex/container rules in later templates.

### 4. Buttons and links

- Define primary, secondary, tertiary/text, destructive, and icon-action patterns only where the product needs them.
- Specify default, hover, focus-visible, active, disabled, and loading states plus touch-target and reduced-motion rules.
- Define inline, navigation, footer, card, and action-link behavior, including underlines and visited/focus treatment where appropriate.

### 5. Forms

- Define labels, required indicators, help text, fields, text areas, selects, checkboxes, radios, search, validation, errors, success, disabled, readonly, and autofill states.
- Keep form submission and integration behavior unchanged.
- Test keyboard order, labels, error association, zoom, and touch targets.

### 6. Cards and status badges

- Define surface, border, radius, padding, image ratio, shadow/elevation, content order, action hierarchy, and responsive behavior.
- Create separate component mappings for product and COA/evidence cards while sharing primitives where appropriate.
- Confirm status semantics and require text/icons in addition to color.
- Do not switch Dark Loop #65 or any active consumer during WEB-2A.

### 7. Responsive rules

- Confirm actual Elementor breakpoint settings, then document the approved breakpoint and container model.
- Define mobile-first behavior for type, grids, navigation, drawers, tables, forms, cards, and long content.
- Include resize-without-reload behavior and the WEB-2 width matrix in later QA.

### 8. Accessibility baseline

- Approve contrast, visible focus, touch-target, readable-type, reduced-motion, heading, labeling, error, and non-color-status requirements.
- Define an accessible focus token that works on light, dark, and image/gradient surfaces.
- Treat these as acceptance gates for every later WEB-2 milestone.

### 9. Elementor global-style implementation

- Create a named Staging backup and export the existing Elementor Site Settings immediately before any change.
- Record current global colors, fonts, Theme Style, breakpoints, and inherited theme/plugin effects.
- Implement only approved editor-safe tokens in Elementor; keep component behavior in its owning layer.
- Apply tokens to isolated test components first. Do not publish or replace an active template in WEB-2A.
- Measure representative editor memory against the 256 MB per-thread Staging constraint and keep ElementsKit disabled.

### 10. Verification and rollback

- Compare isolated components at the required width matrix, keyboard-only, zoomed text, reduced motion, and common state variants.
- Confirm WooCommerce, account, rewards, VerifyPass, COA, cart, checkout, and email behavior was not touched.
- Record every global setting changed, its old value, new value, owner, and consumer.
- Roll back by restoring the Elementor settings export or the named Staging backup if editor stability, inherited styling, or unrelated active templates regress.
- Stop before WEB-2B or any active page/template redesign until WEB-2A is approved.

## Approved WEB-2A Design Decisions

### 1. Primary brand navy

- Primary Pep Select Navy: `#002A53`
- Darker supporting navy shades may exist only as secondary surfaces when intentionally specified later.

### 2. Typography roles

- Georgia: editorial headings and brand statements
- Plus Jakarta Sans: navigation, body copy, buttons, forms, product information, and general interface text
- IBM Plex Mono: batch IDs, COA values, SKUs, and technical metadata

### 3. Content width and gutters

- Maximum content width: `1200px`
- Desktop gutters: `32px`
- Tablet gutters: `24px`
- Mobile gutters: `20px`

### 4. Border-radius system

- Small: `8px`
- Medium: `12px`
- Large: `20px`
- Pill: `999px`
- Large rounding should be reserved for prominent feature sections rather than applied everywhere.

### 5. Hover and motion

- Use subtle color, border, shadow, or `1–2px` lift changes
- Do not use shrink effects
- Target transition duration: approximately `180ms`
- Provide reduced-motion behavior

### 6. Semantic status system

- Available / Pass / Completed: green
- Testing / Verification in Progress: cyan or teal
- Pending / Expected / Waiting: amber
- Failed / Error: red
- Out of Stock / Not Tested / Not Applicable: neutral gray
- Sale: primary navy badge with white text
- Rewards: cyan or teal treatment
- Status meaning must use text and/or icons, not color alone
- Technical metadata should use IBM Plex Mono where appropriate

### 7. Search appearance

- Header, Shop, and COA Archive searches must use one recognizable Pep Select visual pattern
- Use consistent shape, borders, typography, icon treatment, focus state, and interaction behavior
- Header search may remain compact
- Shop and COA searches may be wider
- Product search must return products
- COA search must return compounds and testing records
- Do not retain the current oversized-image WordPress search-results layout

### 8. Launch sequencing decision

- Do not build a Pep Select Design System settings panel in Site Core during the prelaunch rebuild
- Prioritize the customer-facing site and launch-critical commerce experience first
- Use approved global styles during the rebuild
- Preserve the safety of customer information, purchasing, orders, checkout, and account systems
- Editable WordPress design-system controls may be considered as a separate post-launch milestone
- Do not add Site Core backend work to WEB-2A unless it is required for launch-critical functionality

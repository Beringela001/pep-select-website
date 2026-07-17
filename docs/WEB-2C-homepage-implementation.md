# WEB-2C Coded Homepage Private Preview

Theme version: `0.4.0-beta.1`

Status: Local implementation only. Not packaged, installed, activated, or published by this checkpoint.

## Private preview gate

The coded homepage is selected only on the WordPress front page when a logged-in user with `manage_options` requests `?pepselect_home_preview=1`. The gate is enforced in PHP through the existing supported-front-end request safeguards. Unauthorized, logged-out, non-front-page, admin, Elementor editor, Customizer, REST, AJAX, cron, feed, login, and CLI requests retain their normal behavior.

The existing Elementor homepage remains the default. The coded WEB-2B header and footer remain active during the homepage preview. The independent administrator-only `?pepselect_legacy_shell=1` behavior is unchanged.

## Template structure

- Controller and data adapter: `pepselect-child/inc/homepage-preview.php`
- Private front-page template: `pepselect-child/templates/front-page-preview.php`
- Eight section templates: `pepselect-child/template-parts/home/`
- Reusable WEB-2C product-card foundation: `template-parts/home/product-card.php`
- Scoped presentation: `pepselect-child/assets/css/homepage.css`
- JavaScript: none added

## Dynamic data and selection

WooCommerce remains the product source of truth. The preview queries published, visible, purchasable, in-stock products through `wc_get_products()`. Valid Featured products are selected first. When fewer than four qualify, the latest other qualifying products fill only the positions needed to reach four. The final list is capped at six.

Product cards use the WooCommerce product object for the canonical title, image attachment, price HTML, stock state, and permalink. Product IDs, names, prices, stock quantities, and sale messages are not stored in the theme.

Shop, My Account, FAQ, Contact, and other internal destinations use existing WordPress or WooCommerce resolution helpers. The COA destination remains the canonical environment-neutral `/testing/` route.

## COA Archive integration boundary

Source inspected read-only: `C:\Users\paulo\Documents\Pep Select COA Page\pepselect-coa-archive` at commit `5ab62eb0387956ac7999d44a74c642d10f490fc8`. The stable package `C:\Users\paulo\Documents\Pep Select COA Page\pepselect-coa-archive-0.4.0.zip` retained SHA256 `1DB9551C9114D48210E20E2C74BC30E45C78B26F77691613ACE9236591F33F35`.

The clean extracted Pep Select COA Archive 0.4.0 source exposes full archive, compound-history, report, and product-context shortcodes, while its repository, router, and view-model services are private runtime dependencies. It does not expose a supported generic homepage-preview shortcode, custom REST projection, or public service accessor.

This checkpoint therefore does not query COA post types, copy plugin source, instantiate internal services, translate stored statuses, or recreate sorting. The approved COA copy and `View Testing History` handoff render as a polished fallback. A later COA-owned enhancement may provide the verified maximum-three-record projection.

## Fallback behavior

- No confirmed hero image provenance: render the image-ready editorial field without a placeholder or stock image.
- WooCommerce unavailable: retain the approved section copy and `Explore Compounds` action with a concise unavailable state.
- No qualifying products: hide the grid and retain the approved catalog action.
- Missing product image: render a neutral bounded image state.
- Missing price: omit the price rather than inventing one.
- Missing COA preview interface: retain the approved section copy and canonical archive action.
- Optional identifier data: use `Recorded when available`; do not imply that every record contains every field.

## Header isolation

The coded header now uses selectors rooted at `#pepselect-site-header` to normalize logo dimensions and Rewards control box sizing, typography, line height, margins, padding, borders, alignment, button appearance, hover, and focus behavior. The rules remain inside the header component and do not reset Elementor, WooCommerce, COA, product, archive, or page content.

## Later activation and rollback

For a later Staging checkpoint, deploy the reviewed child-theme source and open the assigned WordPress front page while logged in as an administrator with `?pepselect_home_preview=1`. Normal requests continue to show the Elementor homepage until a separate approved milestone changes the default template behavior.

Immediate page rollback is removal of the preview parameter. Shell rollback remains the administrator-only legacy-shell parameter or activation of the installed Hello Elementor parent theme. No Elementor content, display condition, homepage assignment, product data, or COA data must be deleted for rollback.

## Remaining verification

- Approve a COA-plugin-owned generic homepage preview interface and its empty/error states.
- Confirm physical label-to-record population practices before broad traceability claims.
- Confirm failed-record publication and retention policy.
- Confirm failed-batch release controls.
- Confirm report completeness and editing policy.
- Confirm whether testing scope and third-party coverage are consistent across releases.
- Complete browser-level desktop, tablet, mobile, keyboard, contrast, and real-data review on Staging before publication.

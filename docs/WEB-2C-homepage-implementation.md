# WEB-2C Coded Homepage Private Preview

Theme version: `0.4.0-beta.2`

Status: Local private-preview implementation only. Not packaged, installed, activated, or published by this checkpoint.

## Direction change

The beta.1 compliance-led eight-section concept was technically successful and visually rejected. Beta.2 replaces its report-like presentation with a product-first storefront: real products and pricing appear in the hero and near the top, while detailed COA guidance is concentrated later in one Quality Archive feature.

Orbitrex informed only broad commercial principles. No competitor copy, assets, graphics, dimensions, statistics, terminology, or exact composition was reproduced.

## Private preview gate

The coded homepage is selected only on the WordPress front page when a logged-in user with `manage_options` requests `?pepselect_home_preview=1`. Unauthorized, logged-out, non-front-page, admin, Elementor editor, Customizer, REST, AJAX, cron, feed, login, and CLI requests retain their normal behavior.

The Elementor homepage remains the default. The coded WEB-2B header and footer remain active during the preview, and `?pepselect_legacy_shell=1` remains an independent administrator-only shell control.

## Structure

- Preview routing and dynamic data: `pepselect-child/inc/homepage-preview.php`
- Front-page controller: `pepselect-child/templates/front-page-preview.php`
- Eight modular sections and reusable product card: `pepselect-child/template-parts/home/`
- Scoped presentation: `pepselect-child/assets/css/homepage.css`
- FAQ behavior: `pepselect-child/assets/js/homepage.js`

The sequence is hero, confidence strip, featured compounds, Why Pep Select, batch identity, Quality Archive, FAQ, and final CTA.

## WooCommerce integration

WooCommerce remains the catalog source of truth. `wc_get_products()` retrieves published, visible, purchasable, in-stock products. Featured products lead the pool; latest eligible products fill remaining positions. The storefront is capped at four products, and the hero uses up to three candidates with real product images.

WordPress responsive image markup supplies `srcset` and `sizes`. The primary hero image is eager with high fetch priority. Other hero and below-fold images are lazy. Product IDs, names, images, prices, stock values, and URLs are not stored in the theme.

Missing images use branded, non-deceptive fallbacks. Catalog images are never described as exact batch-packaging evidence.

## FAQ source and behavior

The FAQ uses three supported items from `site-exports/elementor/saved-page-pepselect-homepage-571.json`. The obsolete “request an order link” item is excluded. The batch answer names the verified Quality Archive destination.

The accordion uses semantic buttons, `aria-expanded`, controlled regions, native Enter/Space behavior, Arrow/Home/End focus movement, visible focus, and one initial expanded state. No library or remote dependency is added.

## COA boundary

The stable Pep Select COA Archive 0.4.0 source remains authoritative and unchanged. It exposes no supported generic homepage-preview interface. Beta.2 therefore links to `/testing/` and communicates verified archive actions without querying raw records, mapping statuses, recreating sorting, instantiating private services, or simulating laboratory data.

## Header isolation and safeguards

The beta.1 component-scoped logo and Rewards protections remain unchanged. Homepage CSS is rooted in `.pepselect-home` component classes and does not target the header, footer, Elementor, WooCommerce templates, or COA views. No WooCommerce override directory or business logic is introduced.

## Responsive, accessibility, and performance baseline

- Plus Jakarta Sans leads commercial headings and interface copy; Georgia italic appears only on selected accent lines.
- Desktop uses the approved 1200px grid, an immersive 620–700px hero, and four equal product cards.
- Tablet uses two product columns and stacks the identity feature when needed.
- Mobile presents copy before imagery, full-width CTAs, a two-by-two confidence strip, a readable product rail, stacked editorial sections, and practical touch targets.
- One H1, logical section headings, meaningful product alt text, visible focus, sufficient contrast, text-backed status, and reduced-motion handling are required.
- No external font, slider, animation, analytics, or tracking dependency is added.

## Rollback and review boundary

Remove `?pepselect_home_preview=1` to return to the unchanged Elementor homepage. The preserved Elementor homepage, coded-shell rollback control, and Hello Elementor parent theme remain available.

No browser-level visual verification is claimed for this local checkpoint. Desktop, tablet, mobile, keyboard, real-product imagery, long product names, missing data, and floating side-cart interaction require Staging review before publication.

# Pep Select child theme

- Version: 0.25.0-beta.72
- Parent: Hello Elementor (`hello-elementor`)
- Text domain: `pepselect-child`

Pep Select is the lightweight presentation child theme for the coded customer-facing site. WooCommerce remains the source of truth for product images, item data, totals, addresses, accounts, shipping methods, and order status.

## Requirements and safe failure

- WordPress 6.4 or later.
- PHP 7.4 or later.
- The unmodified Hello Elementor parent theme installed in the `hello-elementor` folder.

The `Template: hello-elementor` declaration allows WordPress to reject activation when the parent is missing. A defensive runtime guard also prevents child assets from loading and displays an administrator notice if the parent becomes unavailable after activation.

Installing a ZIP does not activate a theme automatically. The Pep Select child theme is the active theme on Staging and Live.

## Foundation scope

- Enqueues the Hello Elementor parent stylesheet and child styles on the public front end only.
- Uses file modification times for local asset cache busting, with the theme version as a fallback.
- Defines the approved WEB-2A colors, font-family roles, layout widths/gutters, radii, transition duration, and reduced-motion value as CSS custom properties.
- Bundles no font files. Georgia and system fallbacks remain available when Plus Jakarta Sans or IBM Plex Mono are not supplied by an approved site-level font source.
- Adds no business logic, database access, migration, analytics, tracking, remote request, dependency, or administrative asset.
- Uses narrow WooCommerce presentation overrides while preserving WooCommerce data, triggers, calculations, and customer-account behavior.
- Renders the coded header and footer on supported front-end requests.
- Keeps `?pepselect_header_preview=1`, `?pepselect_footer_preview=1`, and `?pepselect_shell_preview=1` as administrator-only compatibility controls.
- Bypasses coded-shell replacement in wp-admin, Customizer, login, REST, AJAX, cron, feed, and CLI contexts.
- Uses the configured WordPress Custom Logo, with the approved bundled brand mark as its fallback.
- Uses the officially documented YITH remaining-points shortcode only for logged-in users and only when registered; otherwise it shows the Rewards destination without a numeric balance.
- Uses the confirmed Xootix side-cart shortcode only when registered, with an environment-neutral cart fallback.
- Preserves current research-use statements, support email, footer destinations, exact published FDA disclaimer, and dynamic copyright year; the coded footer omits the external developer credit.
- Adds `?pepselect_home_preview=1` as a capability-gated coded homepage preview on the WordPress front page.
- Queries featured and fallback products through WooCommerce public APIs for the hero and four-product storefront without template overrides or stored-data changes.
- Uses an action-only Quality Archive feature because COA Archive version 0.4.0 exposes no supported generic homepage-preview projection.
- Loads a dependency-free, preview-only FAQ controller for semantic accordion state and keyboard navigation.

## Structure

```text
pepselect-child/
|-- assets/
|   |-- css/
|   |   |-- foundations.css
|   |   |-- footer.css
|   |   |-- header.css
|   |   `-- homepage.css
|   `-- js/
|       |-- header.js
|       |-- homepage.js
|       `-- README.md
|-- inc/
|   |-- footer-preview.php
|   |-- header-preview.php
|   |-- homepage-preview.php
|   `-- setup.php
|-- templates/
|   `-- front-page-preview.php
|-- template-parts/
|   |-- footer/
|   |   |-- disclaimer.php
|   |   |-- link-groups.php
|   |   `-- site-footer.php
|   |-- header/
|   |   |-- actions.php
|   |   |-- navigation.php
|   |   `-- site-header.php
|   |-- home/
|   |   |-- batch-identity.php
|   |   |-- coa-feature.php
|   |   |-- confidence-strip.php
|   |   |-- featured-products.php
|   |   |-- final-cta.php
|   |   |-- faq.php
|   |   |-- hero.php
|   |   |-- product-card.php
|   |   `-- why-pep-select.php
|   `-- README.md
|-- CHANGELOG.md
|-- functions.php
|-- README.md
|-- SCREENSHOT.md
`-- style.css
```

The `woocommerce/` directory is deliberately absent. Add an override only in its approved later milestone, after a supported hook has been shown to be insufficient.

## Rollback boundary

Hello Elementor remains installed as the required parent theme; Elementor Core, Elementor Pro, and Marquee Addons have been retired. Recovery copies of the former Elementor templates and environment backups are maintained outside the theme. Reinstalling an earlier signed-off Pep Select child-theme package is the code rollback path.

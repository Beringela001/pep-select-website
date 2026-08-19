# Pep Select child theme

- Version: 0.25.0-beta.26
- Parent: Hello Elementor (`hello-elementor`)
- Text domain: `pepselect-child`

Pep Select is the lightweight presentation child theme for the controlled customer-facing rebuild. Version 0.25.0-beta.26 preserves the complete Live 0.25.0-beta.23 email and storefront baseline while adding the Staging-verified Milestone 3 crawl and structured-data corrections. WooCommerce remains the source of truth for product images, item data, totals, addresses, accounts, shipping methods, and order status.

## Requirements and safe failure

- WordPress 6.4 or later.
- PHP 7.4 or later.
- The unmodified Hello Elementor parent theme installed in the `hello-elementor` folder.

The `Template: hello-elementor` declaration allows WordPress to reject activation when the parent is missing. A defensive runtime guard also prevents child assets from loading and displays an administrator notice if the parent becomes unavailable after activation.

Installing a ZIP does not activate a theme automatically. The child theme is already active on Staging under the separately documented WEB-2B activation checkpoint; this local version does not modify Staging or Live.

## Foundation scope

- Enqueues the Hello Elementor parent stylesheet and child styles on the public front end only.
- Uses file modification times for local asset cache busting, with the theme version as a fallback.
- Defines the approved WEB-2A colors, font-family roles, layout widths/gutters, radii, transition duration, and reduced-motion value as CSS custom properties.
- Bundles no font files. Georgia and system fallbacks remain available when Plus Jakarta Sans or IBM Plex Mono are not supplied by an approved site-level font source.
- Adds no business logic, database access, migration, analytics, tracking, remote request, dependency, or administrative asset.
- Uses narrow WooCommerce presentation overrides while preserving WooCommerce data, triggers, calculations, and customer-account behavior.
- Renders the coded header and footer by default on normal front-end requests while preserving Elementor page content between them.
- Suppresses Elementor Header #1323 and Footer #391 only for requests where the coded shell is active; it does not change or delete their stored conditions.
- Keeps `?pepselect_header_preview=1`, `?pepselect_footer_preview=1`, and `?pepselect_shell_preview=1` as administrator-only compatibility controls.
- Adds administrator-only `?pepselect_legacy_shell=1` to restore the preserved Elementor shell for one request; explicit shell-control responses receive no-cache headers.
- Bypasses coded-shell replacement in wp-admin, Elementor editor, Customizer, login, REST, AJAX, cron, feed, and CLI contexts.
- Uses the confirmed Elementor Header #1323 Media Library logo attachment when no WordPress Custom Logo is set, without storing an environment URL.
- Uses the confirmed Elementor Footer #391 Media Library logo attachment when no WordPress Custom Logo is set, without storing an environment URL.
- Uses the officially documented YITH remaining-points shortcode only for logged-in users and only when registered; otherwise it shows the Rewards destination without a numeric balance.
- Uses the confirmed Xootix side-cart shortcode only when registered, with an environment-neutral cart fallback.
- Preserves current research-use statements, support email, footer destinations, exact published FDA disclaimer, and dynamic copyright year; the coded footer omits the external developer credit.
- Leaves existing Elementor page content, Header #1323, Footer #391, and all Elementor display conditions stored and unchanged.
- Adds `?pepselect_home_preview=1` as a capability-gated coded homepage preview on the WordPress front page only; unauthorized and ordinary requests continue to use the existing homepage.
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

The existing Elementor homepage, Hello Elementor parent theme, and stored Elementor Header #1323 and Footer #391 remain the rollback baseline. Remove `?pepselect_home_preview=1` to return to the unchanged homepage. A logged-in administrator may use `?pepselect_legacy_shell=1` for a one-request shell check; reactivating the parent theme remains the immediate full rollback.

# Pep Select child theme

- Version: 0.3.1
- Parent: Hello Elementor (`hello-elementor`)
- Text domain: `pepselect-child`

Pep Select is a lightweight presentation child theme for the controlled WEB-2 customer-facing rebuild. Version 0.3.1 makes the approved coded header and footer the default presentation shell on supported front-end requests. Elementor continues to own page content, and its stored Header #1323, Footer #391, and display conditions remain unchanged for emergency rollback.

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
- Adds no WooCommerce template override and does not modify WooCommerce behavior.
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

## Structure

```text
pepselect-child/
|-- assets/
|   |-- css/
|   |   |-- foundations.css
|   |   |-- footer.css
|   |   `-- header.css
|   `-- js/
|       |-- header.js
|       `-- README.md
|-- inc/
|   |-- footer-preview.php
|   |-- header-preview.php
|   `-- setup.php
|-- template-parts/
|   |-- footer/
|   |   |-- disclaimer.php
|   |   |-- link-groups.php
|   |   `-- site-footer.php
|   |-- header/
|   |   |-- actions.php
|   |   |-- navigation.php
|   |   `-- site-header.php
|   `-- README.md
|-- CHANGELOG.md
|-- functions.php
|-- README.md
|-- SCREENSHOT.md
`-- style.css
```

The `woocommerce/` directory is deliberately absent. Add an override only in its approved later milestone, after a supported hook has been shown to be insufficient.

## Rollback boundary

The existing Hello Elementor parent theme and stored Elementor Header #1323 and Footer #391 remain the rollback baseline. Version 0.3.1 changes no WordPress records or Elementor display conditions. A logged-in administrator may use `?pepselect_legacy_shell=1` for a one-request shell check; reactivating the parent theme remains the immediate full rollback.

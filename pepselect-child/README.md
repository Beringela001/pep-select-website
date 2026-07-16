# Pep Select child theme

- Version: 0.2.2
- Parent: Hello Elementor (`hello-elementor`)
- Text domain: `pepselect-child`

Pep Select is a lightweight presentation child theme for the controlled WEB-2 customer-facing rebuild. Version 0.2.2 keeps the first coded header behind a private, administrator-only preview and refines only its mobile composition. It does not replace the public Elementor header, footer, search-results presentation, WooCommerce template, page template, or Elementor display condition.

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
- Adds `?pepselect_header_preview=1`, restricted to logged-in users with `manage_options`, for the coded-header review.
- Loads coded-header CSS and JavaScript only during an authorized preview and sends no-cache headers for that response.
- Uses the confirmed Elementor Header #1323 Media Library logo attachment when no WordPress Custom Logo is set, without storing an environment URL.
- Uses the officially documented YITH remaining-points shortcode only for logged-in users and only when registered; otherwise it shows the Rewards destination without a numeric balance.
- Uses the confirmed Xootix side-cart shortcode only when registered, with an environment-neutral cart fallback.
- Leaves ordinary requests, existing Elementor page content, Header #1323, Footer #391, and Elementor display conditions untouched.

## Structure

```text
pepselect-child/
|-- assets/
|   |-- css/
|   |   |-- foundations.css
|   |   `-- header.css
|   `-- js/
|       |-- header.js
|       `-- README.md
|-- inc/
|   |-- header-preview.php
|   `-- setup.php
|-- template-parts/
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

The existing Hello Elementor parent theme and active Elementor Header #1323 and Footer #391 remain the rollback baseline. Version 0.2.2 changes no WordPress records or Elementor display conditions. Removing the preview query parameter immediately restores the ordinary Header #1323 request path; reactivating the parent theme remains the full theme-level rollback.

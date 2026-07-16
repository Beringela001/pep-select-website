# Pep Select child theme

- Version: 0.1.1
- Parent: Hello Elementor (`hello-elementor`)
- Text domain: `pepselect-child`

Pep Select is a lightweight presentation child theme for the controlled WEB-2 customer-facing rebuild. Version 0.1.1 establishes only the inactive theme foundation. It does not provide a coded header, footer, search results, WooCommerce override, page template, or Elementor display condition.

## Requirements and safe failure

- WordPress 6.4 or later.
- PHP 7.4 or later.
- The unmodified Hello Elementor parent theme installed in the `hello-elementor` folder.

The `Template: hello-elementor` declaration allows WordPress to reject activation when the parent is missing. A defensive runtime guard also prevents child assets from loading and displays an administrator notice if the parent becomes unavailable after activation.

Installing the ZIP does not activate the theme. Activation must be a separate, approved Staging action after a named backup and rollback check. This repository checkpoint does not install or activate the theme anywhere.

## Foundation scope

- Enqueues the Hello Elementor parent stylesheet and child styles on the public front end only.
- Uses file modification times for local asset cache busting, with the theme version as a fallback.
- Defines the approved WEB-2A colors, font-family roles, layout widths/gutters, radii, transition duration, and reduced-motion value as CSS custom properties.
- Bundles no font files. Georgia and system fallbacks remain available when Plus Jakarta Sans or IBM Plex Mono are not supplied by an approved site-level font source.
- Adds no business logic, database access, migration, analytics, tracking, remote request, dependency, or administrative asset.
- Adds no WooCommerce template override and does not modify WooCommerce behavior.
- Leaves existing Elementor page content and the active Elementor header/footer untouched.

## Structure

```text
pepselect-child/
|-- assets/
|   |-- css/
|   |   `-- foundations.css
|   `-- js/
|       `-- README.md
|-- inc/
|   `-- setup.php
|-- template-parts/
|   `-- README.md
|-- CHANGELOG.md
|-- functions.php
|-- README.md
|-- SCREENSHOT.md
`-- style.css
```

The `woocommerce/` directory is deliberately absent. Add an override only in its approved later milestone, after a supported hook has been shown to be insufficient.

## Rollback boundary

The existing Hello Elementor parent theme and active Elementor Header #1323 and Footer #391 remain the rollback baseline. Later Staging activation of this child theme must remain reversible by reactivating the parent theme. Version 0.1.1 changes no WordPress records or template display conditions.

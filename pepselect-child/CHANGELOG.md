# Changelog

All notable changes to the Pep Select child theme are documented here.

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

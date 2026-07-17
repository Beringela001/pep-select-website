# Changelog

All notable changes to the Pep Select child theme are documented here.

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

# Pass 2.5 performance release — 0.25.0-beta.68

Date: August 29, 2026

## Outcome

- Released Pep Select child theme 0.25.0-beta.68 to staging and Live.
- Replaced the Side Cart WooCommerce plugin's 130,311-byte initial confetti dependency with a 1,110-byte on-demand loader on the audited storefront templates.
- Removed 129,201 bytes, or 99.1%, from that dependency's initial JavaScript payload while retaining the configured progress-bar celebrations.
- Kept the plugin's original script handle and dependency order. The original vendor library is fetched only when its async `confetti.create()` method is called.
- Left Elementor removal, analytics, checkout behavior, payment, shipping, rewards, BOGO, Back In Stock, COA, and access-gate behavior outside this change.

## Package

- File: `dist/pepselect-child-0.25.0-beta.68.zip`
- Size: 2,692,575 bytes
- SHA-256: `F8E6B5FF3C9EC42292A60A612A87672F17A2C4C0432218663388E92AF5256A65`

## Backups and deployment

- Staging rollback point: `Before Pass 2.5 performance beta68 staging - 2026-08-29`, created Aug 29 at 11:27 PM.
- Live rollback point: `Before Pass 2.5 performance beta68 live - 2026-08-29`, created Aug 29 at 11:36 PM.
- Staging capacity was 5/5; deleted only the verified oldest manual backup, `Before Claude SEO measured render-path cleanup beta.37 - 2026-08-19`, created Aug 19 at 3:24 PM.
- Live capacity was 5/5; deleted only the verified oldest manual backup, `Before BOGO product pill 1.8.1 live - 2026-08-29`, created Aug 28 at 11:27 PM.
- Deployed the same staging-tested beta.68 ZIP to Live and cleared Live WordPress caches.

## Verification

- PHP syntax: passed.
- JavaScript syntax: passed.
- `tests/test-performance-assets.js`: passed.
- `tests/test-confetti-loader.js`: passed, including one-time vendor loading and proxy behavior.
- Git whitespace check: passed.
- Staging active theme: 0.25.0-beta.68.
- Staging initial page load: loader present; original confetti bundle absent.
- Staging on-demand check: original vendor bundle loaded and returned a callable celebration function.
- Staging add-to-cart: passed; side cart opened with products, totals, cart, and checkout controls.
- Staging checkout: billing, shipping, payment instructions, acknowledgments, order summary, and Place Your Order control rendered. No order was submitted.
- Live active theme: 0.25.0-beta.68.
- Live Home, Shop, GHK-Cu product, Testing, and Cart returned HTTP 200. Empty-cart Checkout redirected normally to Cart.
- Live public source: loader present; original confetti bundle absent from initial markup.
- Live on-demand check: original vendor bundle loaded successfully and returned a callable celebration function.
- Live browser errors: zero.

Google PageSpeed's API quota was exhausted during the release window, so no new Lighthouse score is claimed. The byte reduction and runtime behavior were measured directly.

## Rollback

Restore the named Live Kinsta backup or reinstall `dist/pepselect-child-0.25.0-beta.67.zip`.

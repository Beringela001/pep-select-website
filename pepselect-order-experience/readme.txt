=== Pep Select Order Experience ===
Contributors: pepselect
Tags: woocommerce, orders, batch, coa
Requires at least: 6.5
Tested up to: 6.8
Requires PHP: 8.1
Stable tag: 0.4.2
License: GPLv2 or later

Secure, batch-specific customer order records connected to Pep Select Ops.

== Installation ==

1. Upload and activate the plugin.
2. Confirm the permanent /order/ fallback page exists.
3. Configure the existing WordPress Application Password in Ops.
4. Leave the feature disabled until staging verification is complete.

== Changelog ==

= 0.4.2 =
* Resolves the approved NAD batch typo ND50026205JP to ND50026205JS for the exact COA photo, displayed batch, and report link without rewriting historical Ops snapshots.

= 0.4.1 =
* Gives Hospira Bacteriostatic Water, USP a product-details card without peptide testing, purity, laboratory, or unavailable-COA messaging.

= 0.4.0 =
* Links compact My Account order rows to their secure order pages and keeps older orders behind an accessible disclosure control.

= 0.3.3 =
* Shows complete, higher-resolution related-product vial images and fills open recommendation slots with relevant restocking compounds.

= 0.3.2 =
* Enlarges centered vial photos and keeps related-compound cards at their standard grid width.

= 0.3.1 =
* Shows complete COA vial photos in a compact side rail on desktop and a contained mobile frame.

= 0.3.0 =
* Adds Milestone 4 privacy hardening, invalid-token throttling, and repeatable release/rollback contracts.

= 0.2.4 =
* Uses explicitly synthetic customer and order labels in the administrator-only preview.

= 0.2.3 =
* Keeps the longest current batch identifier on one line at the 320 px breakpoint.

= 0.2.2 =
* Prevents narrow mobile batch and temperature breaks and uses a compact mobile reorder label.

= 0.2.1 =
* Hardens active-theme button, currency, and related-card presentation after full-page visual review.

= 0.2.0 =
* Adds the complete responsive order page, exact COA batch-vial resolution, controlled research context, owner-approved related-product scoring, validated thank-you coupon display, native WooCommerce reorder checks, and an administrator-only sample preview.

= 0.1.0 =
* Adds secure opaque order access, hashed token storage, revocation, snapshot API, cache prevention, and permanent fallback.

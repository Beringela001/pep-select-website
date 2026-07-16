# WEB-1 Staging Verification Findings

Verification date: July 16, 2026
Environment: Staging

## Active Elementor assignments

- Header #1323: Include ? Entire site
- Footer #391: Include ? Entire site
- Products Archive #441: Include ? All Product Archives
- Single Product #462: Include ? Products
- Single Product #279: No instances
- Single Post #510: Include ? COAs

## Homepage assignment

- WordPress homepage mode: Static page
- Homepage: Home
- Homepage post ID: 79
- Status: Published
- Builder: Elementor
- Posts page: None selected

## Staging safeguards

- Search-engine indexing is discouraged
- WooPayments Safe Mode is enabled
- Manual rollback backup: Before WEB-1 Audit

## Confirmed editor failure

Opening Home post ID 79 in Elementor normally exhausted the Staging environment's 256 MB per-thread PHP memory limit and produced an HTTP 500/critical error.

Observed error:

- Allowed memory: 268435456 bytes (256 MB)
- Failure location: wp-includes/class-wpdb.php line 2351
- Secondary failure location: wp-includes/functions.php line 4398
- Additional database messages appeared after memory exhaustion

## Kinsta resource findings

- Kinsta Staging has a 512 MB PHP memory pool across two threads.
- Each PHP thread has 256 MB available.
- Kinsta reported 15 memory-limit events.
- Kinsta reported 28 thread-limit events.

## Troubleshooting results

The following isolation tests were completed on Staging:

1. The homepage loaded successfully in Elementor Safe Mode. This confirmed that the Home page content itself was not corrupted.
2. Temporarily disabling Marquee Addons did not resolve the normal Elementor editor failure.
3. Disabling both ElementsKit Lite and ElementsKit Pro allowed the Home editor to load normally without Safe Mode.
4. Re-enabling ElementsKit Lite by itself reproduced the editor failure.

These results confirm that ElementsKit Lite was the trigger for Elementor editor memory exhaustion on this Staging environment.

## Staging remediation

- ElementsKit Lite and ElementsKit Pro remain disabled on Staging.
- Disabling ElementsKit removed the ElementsKit navigation widget from Header template #1323.
- The missing navigation was replaced with Elementor's native WordPress Menu widget.
- The native menu was configured for desktop and mobile.
- The menu was tested on the public Staging site and published.

## Verification status

- The public homepage now loads normally on Staging.
- The Home page Elementor editor now loads normally without Safe Mode.

## Backup and environment boundary

- A Kinsta manual Staging backup was created named `After ElementsKit Removal - Elementor Fixed`.
- Live was not modified.

## Contact page

- Desktop and mobile layouts render.
- The Elementor contact form submits successfully.
- Submitted name, email, subject, and message are received correctly.
- Staging messages land in Gmail Spam because they are sent from `email@pepselect.kinsta.cloud` through the Kinsta staging mail service.
- Production requires authenticated Pep Select sending and correct Reply-To behavior.
- “Request an order link” is outdated because customers now order through checkout and receive the payment link by email.

## FAQ page

- Live displays the accordion questions correctly.
- Staging intermittently displays only the category headings, with the accordion content missing.
- The Elementor editor loads inconsistently and sometimes requires a long wait or back navigation.
- The FAQ content is not classified as deleted. This is a Staging Elementor/widget rendering issue requiring repair.

## About Us page

- Desktop and mobile layouts render.
- Excessive desktop whitespace appears around the standards section.
- Five standards currently use plain navy circles instead of meaningful icons.
- Claims such as “20+ compounds verified” and “100% third-party tested” must be checked against the real catalog and current batch statuses before reuse.

## Legal and policy pages

- Privacy Policy, Terms & Conditions, Refund & Shipping Policy, and RUO Disclaimer all render.
- Privacy Policy, Terms & Conditions, and Refund & Shipping Policy still contain `Last updated: [DATE]`.
- Privacy Policy must reflect the emailed Square payment-link workflow.
- Privacy Policy should retain Google sign-in language only if Google sign-in is enabled at launch.
- Georgia governing law, the Kennesaw mailing address, processing timelines, refund handling through Square, and testing/COA claims require final verification.
- RUO language stating that Pep Select does not repackage, label, or modify products must be checked against actual operations.
- Manufacturing-defect communication should direct customers to Pep Select support.

## VerifyPass

- Military and law-enforcement verification works.
- Successful verification creates a working WooCommerce discount code.
- The current button opens VerifyPass in a separate popup window.
- Rebuild requirement: use VerifyPass-supported embedded or modal behavior that remains within the Pep Select experience and works on desktop and mobile without breaking identity verification, uploads, or camera access.

## Product page and cart

- The PT-141 product page renders on desktop and mobile.
- Bundle options work: selecting three vials adds quantity `3` at `$124.15` and displays `$13.82` savings.
- The side cart correctly calculates `$134.15`, including `$10` shipping.
- Mobile product typography is too small.
- Bundle options are cramped on mobile.
- Testing history is undersized on mobile.
- Related products create a long vertical list on mobile.
- The floating cart remains visible behind the open side-cart drawer.
- “Earn 1% cash back” conflicts with the points terminology and should be renamed.
- Related-product selection currently includes out-of-stock products.
- Product descriptions contain broad, placeholder-style claims that require verified rewritten copy.

## Previously verified functional flows

The following flows have already been tested and work:

- Cart
- Four-step checkout
- Emailed Square payment-link order workflow
- WooCommerce order statuses
- Customer order history
- Addresses
- Account editing
- Logout
- Password reset
- Reward-point conversion into coupons
- Mobile side cart

Do not repeat these tests unless the rebuild changes their underlying implementation.

## Search

- Both header search and compound/product search results render with broken layouts and oversized product imagery.
- Search remains a rebuild and repair requirement.

## WEB-1 staging audit conclusion

- Core commerce and customer-account behavior is operational.
- The major risks are Elementor instability and memory use, Staging-only widget/render inconsistencies, broken search presentation, legacy and copy issues, and unverified policy/claim language.
- WEB-1 now has enough evidence to proceed to rebuild planning without continuing repetitive manual testing.

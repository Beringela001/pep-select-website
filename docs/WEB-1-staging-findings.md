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

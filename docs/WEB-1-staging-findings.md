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

Opening the normal WordPress editor for Home post ID 79 causes a fatal PHP memory error.

Observed error:

- Allowed memory: 268435456 bytes (256 MB)
- Failure location: wp-includes/class-wpdb.php line 2351
- Secondary failure location: wp-includes/functions.php line 4398
- Additional database messages appeared after memory exhaustion

This confirms a 256 MB memory exhaustion event, but it does not identify the plugin or component responsible.

No plugins were deactivated and no memory settings were changed during this audit checkpoint.

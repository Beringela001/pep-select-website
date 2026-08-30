# Pass 1 platform maintenance release

Date: August 29, 2026

## Outcome

- Removed the inactive Pinterest for WooCommerce plugin from staging and Live.
- Updated Marquee Addons for Elementor from 3.9.82 to 3.9.85 on staging and Live.
- Released Pep Select child theme 0.25.0-beta.67 to remove unused Elementor runtime assets from the coded Contact template.
- Confirmed `/contact/` loads no Elementor script, reports no `elementorFrontendConfig is not defined` error, and retains the protected coded form.
- Kept Elementor Core 4.1.5 and Elementor Pro 3.34.0 paired because Elementor's package endpoint rejected the Pro 4.2.2 download as unauthorized on both environments. Kinsta continues to identify Pro 3.34.0 as vulnerable.
- Reviewed Scheduled Actions without deleting records. Live remains at 134 historical failures and 24 valid pending actions.
- Left unrelated routine updates queued for their owning dependency passes: FluentCRM/mail plugins with email delivery, checkout and WooCommerce extensions with commerce verification, and licensed YITH/ACF packages after their update authorization is repaired. No broad bulk update was performed.

## Backups and rollback

- Staging backup: `Before Pass 1 platform maintenance staging - 2026-08-29`, created August 29 at 9:38 PM.
- Live backup: `Before Pass 1 platform maintenance live - 2026-08-29`, created August 29 at 9:52 PM.
- To make room, permanently deleted the verified oldest staging backup `Before Claude SEO Milestone 4 combined batches 3-4 - 2026-08-19` and the verified oldest Live backup `Before BOGO authority 1.8.0 live - 2026-08-29`.
- Staging was restored once from its fresh backup after Kinsta reported an Elementor Pro update that did not actually change the installed version. The environment was then revalidated before deployment continued.

## Package

- File: `dist/pepselect-child-0.25.0-beta.67.zip`
- SHA-256: `6729DC7A439E108827E49A840B0449A4BF7005D0F80DFB56B6CA6C8B8EA626CA`

## Verification

- `tests/test-performance-assets.js`: passed.
- `tests/test-contact-form-abuse.js`: passed.
- Staging and Live home, shop, cart, checkout, and contact routes rendered without a fatal or critical-error page.
- Staging and Live Contact pages loaded no Elementor runtime script and logged no browser errors.
- Easyship 0.9.16 remained active.
- Live caches were cleared after deployment.
- No order or contact-form submission was made.

## Worker analysis

Kinsta's past-24-hours view reported 450 PHP thread-limit events, 6,238 PHP requests, a 0.84-second average PHP/MySQL response time, and zero PHP memory-limit events. The Action Scheduler queue runner was the top recurring slow path at roughly 5.62 seconds average. FluentCRM/admin and modern WooCommerce REST traffic also appeared among slower paths. This overlaps the next email-delivery pass; capacity should be reassessed after that work rather than increased blindly.

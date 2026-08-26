=== Pep Select Cart Recovery ===
Contributors: pepselect
Requires at least: 6.5
Requires PHP: 7.4
Stable tag: 0.1.0
License: Proprietary

Native Pep Select exit offer and Cart Abandonment Recovery integration.

== Installation ==

1. Install and activate WooCommerce and Cart Abandonment Recovery for WooCommerce.
2. Install and activate this plugin.
3. Open WooCommerce > Exit Offer.
4. Configure the coupon expiry, dismissal cooldown, and optional FluentCRM list ID.
5. Leave the public offer disabled until the recovery templates and sender settings are ready.

== Behavior ==

* Creates one unique, email-restricted, single-use 10% coupon.
* Coupons can combine with eligible offers.
* Uses a 7-day expiry by default.
* Shows no popup or assets until explicitly enabled.
* Loads no third-party script, font, image, or popup service.
* Sends non-identifying events to the existing dataLayer.
* Connects a captured cart to the installed recovery plugin without editing vendor files.
* Adds marketing contacts to FluentCRM only when the optional checkbox is selected and a list ID is configured.

=== Pep Select Trustpilot Review Invitations ===
Contributors: pepselect
Requires at least: 6.4
Requires PHP: 7.4
Stable tag: 0.2.0

Sends one neutral Pep Select-branded review request seven days after an order reaches WooCommerce Completed status.

== Behavior ==

* Uses Action Scheduler when available and WordPress cron as a fallback.
* Sends each eligible order at most once and each billing email at most once every 180 days.
* Prevents multiple pending invitations for repeat orders from the same billing email.
* Provides a one-time catch-up that selects the latest completed order per customer, queues customers already past seven days, and schedules newer customers for their seven-day mark.
* Cancels pending sends for cancelled, failed, or refunded orders.
* Includes a signed customer opt-out that suppresses future review invitations for the same email address.
* Links directly to Pep Select's official Trustpilot review form.
* Does not create, edit, filter, or import Trustpilot reviews.

== Changelog ==

= 0.2.0 =
* Added a 180-day customer cooldown, repeat-order pending deduplication, and controlled historical-order catch-up.

= 0.1.0 =
* Initial release.

=== Pep Select Shipping Restrictions ===
Contributors: pepselect
Requires at least: 6.5
Requires PHP: 8.1
Stable tag: 0.2.0
License: Proprietary

Validates destination addresses and applies destination-specific carrier rules.

== Description ==

Adds three safeguards:

* Allows the 50 U.S. states, Washington, D.C., and Puerto Rico.
* Keeps Alaska and Puerto Rico shipping USPS-only.
* Blocks mismatched Alaska, Hawaii, and Puerto Rico ZIP/state combinations.

== Changelog ==

= 0.2.0 =
* Restore Alaska, Hawaii, and Puerto Rico shipping.
* Keep Alaska and Puerto Rico rates USPS-only.
* Validate special-region ZIP and state/territory combinations.

= 0.1.2 =
* Clear cached shipping rates before recalculating an excluded checkout address.

= 0.1.1 =
* Preserve the accessible invalid state after checkout recalculates shipping.

= 0.1.0 =
* Initial release.

=== Pep Select Shipping Restrictions ===
Contributors: pepselect
Requires at least: 6.5
Requires PHP: 8.1
Stable tag: 0.4.1
License: Proprietary

Validates destination addresses and applies destination-specific carrier rules.

== Description ==

Adds three safeguards:

* Allows the 50 U.S. states, Washington, D.C., and Puerto Rico.
* Keeps Alaska and Puerto Rico shipping USPS-only.
* Blocks mismatched Alaska and Hawaii ZIP/state combinations and Puerto Rico ZIP/country combinations.

== Changelog ==

= 0.4.1 =
* Preserve the complete Google-selected address through Fluid Checkout refreshes.
* Keep hidden billing fields aligned when "Same as shipping address" is selected.
* Avoid rejecting serviceable addresses after carrier rates have been returned.

= 0.2.6 =
* Preserve Puerto Rico in the State / Territory field after WooCommerce rebuilds the US state selector.

= 0.2.5 =
* Allow Google autocomplete to resolve Puerto Rico before normalizing it to the WooCommerce US territory format.

= 0.2.4 =
* Allow Google Address Autocomplete to suggest Puerto Rico addresses.
* Store selected Puerto Rico addresses as United States with Puerto Rico selected in State / Territory.

= 0.2.3 =
* Show one specific destination warning in the Shipping area.
* Hide WooCommerce's duplicate no-services message while that warning is active.

= 0.2.2 =
* Add Puerto Rico to the United States State / Territory choices for domestic USPS rating.
* Preserve strict Puerto Rico ZIP/state validation.

= 0.2.1 =
* Treat Puerto Rico as its WooCommerce Country / Region so customers can enter valid addresses.
* Keep Puerto Rico rates USPS-only and block Puerto Rico ZIPs entered under the United States.

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

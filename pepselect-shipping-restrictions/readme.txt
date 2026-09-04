=== Pep Select Shipping Restrictions ===
Contributors: pepselect
Requires at least: 6.5
Requires PHP: 8.1
Stable tag: 0.4.0
License: Proprietary

Validates destination addresses and applies destination-specific carrier rules.

== Description ==

Adds checkout address and destination safeguards:

* Allows the 50 U.S. states, Washington, D.C., and Puerto Rico.
* Keeps Alaska and Puerto Rico shipping USPS-only.
* Verifies complete delivery addresses through a server-side Google/USPS check.
* Locks order submission until the current address is verified.
* Offers a verified corrected address when the entered street, city, state, or ZIP differs.

== Changelog ==

= 0.4.0 =
* Verify complete checkout addresses before order submission.
* Show verified address suggestions without exposing the Google API key in the browser.
* Disable Place Order whenever the active address is incomplete, changed, invalid, or still being checked.

= 0.3.0 =
* Add final server-side Google Address Validation with USPS deliverability confirmation.
* Block incomplete street lines and city, state, and ZIP mismatches before creating an order.
* Synchronize billing and shipping address fields atomically when they are the same.

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

=== Pep Select Cart Recovery ===
Contributors: pepselect
Requires at least: 6.5
Requires PHP: 7.4
Stable tag: 0.4.10
License: Proprietary

Native Pep Select exit offer and Cart Abandonment Recovery integration.

== Installation ==

1. Install and activate WooCommerce and Cart Abandonment Recovery for WooCommerce.
2. Install and activate this plugin.
3. Open WooCommerce > Popups.
4. Use the Exit Popup or Campaign Popup tab and watch the live preview while editing.
5. Configure the email offer, coupon value, wording, email copy, and appearance under Exit Popup.
6. Optionally configure one Campaign Popup with a start time, end time, display delay, CTA, code, and appearance.
7. Leave each public popup disabled until its settings are ready.

== Behavior ==

* Creates a unique, email-restricted WooCommerce coupon using the configured percentage or fixed-cart value.
* Keeps private codes unique while allowing the administrator to choose the code prefix.
* Emails the code in the Pep Select transactional-email design without creating an account.
* Makes all customer-facing exit-popup and immediate coupon-email wording editable.
* Keeps the approved popup frame and responsive layout fixed while allowing background images, colors, tint, overlay opacity, text colors, and button colors.
* Identifies Pep Select as an American-owned and operated company in the customer email footer.
* Keeps saved-cart product tables inside the mobile email canvas and preserves database-authored message content.
* Detects a complete existing company footer before appending the shared footer, preventing duplicates without deleting surrounding email markup.
* Blocks a recovery send when CartFlows supplies a genuinely empty email template.
* Standardizes the approved reply-first support sentence across signup-code and saved-cart emails, including previously saved recovery templates.
* Coupons can combine with eligible offers.
* Uses a 7-day expiry by default.
* Shows no popup or assets until explicitly enabled.
* Loads no third-party script, font, image, or popup service.
* Sends non-identifying events to the existing dataLayer.
* Recognizes desktop exit intent across a practical top-edge area and preserves an early exit signal until the engagement delay completes.
* Connects a captured cart to the installed recovery plugin without editing vendor files.
* Adds subscribers to FluentCRM when a list ID is configured.
* Creates a separate, stackable 5% coupon only when the configured 48-hour recovery template sends.
* Reuses the same 5% code if the final email is retried, preventing duplicate coupons.
* Limits FluentCRM marketing delivery to one message per second to reduce recipient-provider deferrals.
* Supports one scheduled promotional popup with site-timezone start/end controls and an automatic stop at the configured end time.
* Supports a configurable delay after arrival, dismiss cooldown, destination, optional displayed promotion code, and independent visual settings.
* Suppresses the email-capture popup during an active scheduled promotion by default so visitors do not receive competing modal messages.
* Provides separate Exit Popup and Campaign Popup administration tabs with desktop/mobile live previews and plain-language field guidance.
* Provides an authenticated, capability-protected WordPress REST settings endpoint for future Control Ops integration.
* Removes the Campaign Popup button when its button-text setting is blank.

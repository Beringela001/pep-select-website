# Pep Select Conversion Tracking

Version `0.1.0-beta.1` provides a theme-independent tracking layer for WooCommerce.

## What it measures

- `view_item`: product detail view after analytics consent.
- `add_to_cart`: owned by Site Kit when its WooCommerce provider is active; otherwise this plugin sends the event.
- `begin_checkout`: once per cart hash after analytics consent.
- `order_submitted`: represented by WooCommerce order creation and intentionally not counted as revenue.
- `purchase`: BACS/Square-link orders only after WooCommerce reports the order paid. GA4 and Meta delivery are independently idempotent and retried up to five times.
- First and last touch after analytics or marketing consent: source, medium, campaign, campaign ID, term, content, referring hostname, landing path, and UTC capture time. Known ad click parameters may infer source/medium, but their identifiers, full referrer URLs, and arbitrary query parameters are not stored on the order.

Product ID/SKU, product name, quantity, order revenue, currency, tax, shipping, coupon, campaign fields, and transaction ID are included. The owned server payload never includes customer name, email, phone, address, IP address, or free-form checkout fields. Browser tags still receive ordinary network metadata from the visitor's request, and therefore load only after the matching consent category is granted.

## Required staging configuration

Add secrets to `wp-config.php`, never this repository:

```php
define( 'PEPSELECT_GA4_MEASUREMENT_ID', 'G-XXXXXXXXXX' );
define( 'PEPSELECT_GA4_API_SECRET', 'replace-with-secret' );

// Optional Meta browser + CAPI delivery.
define( 'PEPSELECT_META_PIXEL_ID', 'replace-with-pixel-id' );
define( 'PEPSELECT_META_ACCESS_TOKEN', 'replace-with-token' );
define( 'PEPSELECT_META_API_VERSION', 'set-current-supported-version' );

```

The Meta API version has no hard-coded fallback because Meta retires versions. Confirm the current supported version during deployment.

## Consent contract

The plugin is fail-closed. A consent manager must call:

```js
window.PepSelectTrackingConsent.update({ analytics: true, marketing: false });
```

It may instead dispatch a `pepselect:consent` event with the same detail. Revocation must call the API again with `false` values. The CMP integration should also return `true` from the `pepselect_tracking_cmp_integrated` PHP filter so Site Health reports a passing state.

Do not treat the existing research-professional age/access gate as analytics consent.

## Site Kit interop

Keep Site Kit as the single Google page-tag owner. Its WooCommerce provider currently owns `add_to_cart`. On an unpaid BACS order-received page, this plugin removes only Site Kit's `purchase` event before its provider runs. Paid revenue is sent later from the WooCommerce paid-order hook.

Site Kit Consent Mode is a separate requirement and must be enabled and verified on Staging before release. Do not activate another GA tag or another WooCommerce ecommerce plugin alongside this implementation.

## Verification checklist

1. Install on Staging only and add staging provider credentials.
2. Connect the CMP contract, enable Site Kit Consent Mode, clear caches, and verify no owned GA4 or Meta request occurs before consent.
3. With analytics consent, verify one `view_item`, one `add_to_cart`, and one `begin_checkout`, each with currency and item data.
4. Place a synthetic BACS order. Confirm no `purchase` appears on the thank-you page and attribution is stored on the order.
5. Mark the synthetic order paid. Confirm one GA4 `purchase` with the real order total and transaction ID; with marketing consent and Meta IDs, confirm one matching Meta `Purchase`.
6. Refresh the order page and repeat the paid-status hook. Confirm no duplicate purchase.
7. Revoke consent and verify subsequent owned analytics/marketing requests stop and consent-scoped hidden identifiers are cleared.
8. Run mobile PageSpeed and compare LCP/TBT/request count against the pre-install baseline. Roll back if median LCP regresses by more than 200 ms or TBT by more than 50 ms over three comparable runs.
9. Verify cart, Fluid Checkout steps, BACS order placement, payment email, manual paid-status transition, completed-order email, and order history.

## Rollback

Deactivate this plugin and remove its `PEPSELECT_*` constants. This stops new capture/delivery without changing WooCommerce products, customers, order totals, payment behavior, or historical order records. Existing `_pepselect_*` order metadata is inert and can remain for auditability.

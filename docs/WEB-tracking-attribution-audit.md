# Pep Select marketing measurement audit

Audit date: September 4, 2026. Scope: conversion tracking, attribution, shared consent integration, and tracking-script performance impact only. Live was inspected read-only and was not changed. Heatmaps and session recording are owned by the separate dedicated implementation task.

## Current state

### Preserve

- Site Kit owns the Google tag (`GT-NNQ4N6DP`) for GA4 property `549907385` / stream `G-WKBPQ38WOV`.
- WooCommerce remains the product, cart, checkout, order, total, tax, shipping, coupon, and payment-status source of truth.
- Jetpack Stats provides broad traffic/referrer reporting. Its current seven-day view showed Instagram as the dominant referrer and `ig / paid` as the dominant UTM source/medium.
- WooCommerce sourcebuster/order-attribution scripts remain installed; this implementation adds a bounded first/last-touch record rather than deleting native data.

### Refine

- Site Kit Plugin conversion tracking is enabled and its WooCommerce provider declares `add_to_cart` and `purchase` ownership.
- `purchase` currently fires from the WooCommerce thank-you hook. That is premature for Pep Select's BACS gateway, which creates an unpaid order and emails a Square payment link.
- Site Kit Consent Mode is disabled on Live. A CMP/consent signal is therefore a release blocker for the new marketing and replay tools.
- WooCommerce's rendered attribution `lifetime` of `1.0e-5` months is its default session-oriented setting, not a site misconfiguration. Native sourcebuster remains intact; the new plugin adds controlled first/last-touch order metadata without changing WooCommerce's native configuration.

### Replace

- Replace thank-you-page revenue counting for BACS with a paid-order event fired only after `WC_Order::is_paid()` is true.
- Replace fragmented reporting assumptions with one documented owner per event and independent idempotency markers for GA4 and Meta purchase delivery.

### Remove

- No plugin, tag, or historical data was removed.
- Do not add a second Google page tag, GTM ecommerce recipe, Pixel Manager, or generic WooCommerce analytics plugin; those would create duplicate ownership.

## Live tag and privacy findings

- Present: Site Kit Google tag, Site Kit WooCommerce event provider, Jetpack Stats, Automattic WooCommerce Analytics, WooCommerce sourcebuster/order-attribution, and Klaviyo.
- Absent in inspected markup: Meta Pixel and a consent manager.
- Site Kit excludes logged-in users, which should be preserved for clean testing.
- The live privacy page mentions cookies and traffic analysis but does not name GA4, Meta, consent categories, retention, or withdrawal. Legal review and updated disclosure are required before enabling Meta.
- Automattic WooCommerce Analytics rendered a server-provided common properties object that includes network/device context. This implementation does not extend or reuse it, and its owned server payload contains no customer name, email, phone, address, or IP. Consent-gated browser tags still receive ordinary request metadata from their providers.

## Event ownership after staging activation

| Funnel event | Owner | Trigger | Duplicate guard |
|---|---|---|---|
| `view_item` | Pep Select plugin | Product page after analytics consent | Session key per product |
| `add_to_cart` | Site Kit when declared; Pep Select fallback otherwise | Add-to-cart interaction | Runtime owner check |
| `begin_checkout` | Pep Select plugin | Checkout with non-empty cart after consent | Cart-hash session key |
| `order_submitted` | WooCommerce record | Checkout creates order | Not revenue |
| `purchase` for BACS/Square | Pep Select server delivery | Order is paid | Provider-specific order meta |
| Campaign attribution | Pep Select plugin | Campaign/referral entry request | First and last touch in WC session/order |

## Performance baseline

Mobile PageSpeed Insights, September 4, 2026:

- Performance: 72
- FCP: 1.8 s
- LCP: 5.7 s
- TBT: 170 ms
- CLS: 0
- Speed Index: 5.2 s
- No CrUX field data was available.
- Main opportunities: render-blocking requests (estimated 1.2 s), image delivery (121 KiB), unused JavaScript (77 KiB), and three long tasks.

The new owned browser file is dependency-free, deferred, and limited to commerce surfaces. The release budget is a maximum 200 ms median LCP regression and 50 ms TBT regression across three comparable mobile runs.

## Release gates

1. CMP selected and wired to the documented JS/PHP consent contract.
2. Site Kit Consent Mode enabled on Staging and verified denied-by-default.
3. Privacy Policy updated and legally reviewed before Meta is enabled.
4. GA4 Measurement Protocol secret created for the existing stream; no secret committed.
5. Meta CAPI version confirmed at deployment time; Pixel/CAPI remain disabled unless marketing consent is recorded.
6. Synthetic BACS order passes order-submitted/no-purchase, emailed-link, paid-status, one-purchase, revenue, item, attribution, retry, and duplicate tests.
7. Checkout, payment email, order status, completed email, and My Account remain unchanged.

## Verification limits

Source, WordPress configuration, anonymous markup, and public PageSpeed were audited. No Live settings were changed and no Live order was placed. Provider secrets, a CMP, a local WordPress/WooCommerce runtime, and an authorized Staging deployment were not available in this worktree, so external event receipt and payment-email delivery remain required Staging gates rather than claimed passes.

## Staging deployment result

Deployed September 4, 2026 to `stg-pepselect-staging.kinsta.cloud`:

- Created and verified the Kinsta manual backup `Before Pep Select Tracking 0.1.0-beta.1 staging` before installation.
- Installed and activated `Pep Select Conversion Tracking` version `0.1.0-beta.1` from the verified package in `dist/`.
- Confirmed the plugin is active in WordPress and its Site Health tests are running.
- Confirmed fail-closed behavior in a fresh anonymous browser: the 7,512-byte first-party `tracking.js` loaded, while no GA4, Meta, or Clarity request was made without consent.
- Confirmed the mobile shop and cart rendered, empty-cart checkout still redirected to the cart, and the browser reported no console errors.
- Three-run public response-time medians improved from 1.623 s to 1.385 s on the home page and from 1.540 s to 1.450 s on the shop page. This is not a Core Web Vitals replacement, but it shows no material server-response regression from activation.
- One successful post-install PageSpeed/Lighthouse report scored mobile 92 (FCP 1.5 s, LCP 2.6 s, TBT 0 ms, CLS 0, Speed Index 5.1 s) and desktop 95 (FCP 0.3 s, LCP 0.6 s, TBT 0 ms, CLS 0.004, Speed Index 2.4 s). Google's API quota was exhausted and a UI repeat did not complete, so this is recorded as a useful sample rather than the required three-run release gate.

Live promotion remains blocked. Site Health correctly reports that a CMP integration is missing and paid-order GA4 delivery is not configured. Site Kit is also disconnected on the cloned Staging URL. Complete release gates 1-7 above, including the synthetic BACS paid-order test and comparable post-install mobile PageSpeed runs, before deploying this plugin to Live.

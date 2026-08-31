# Pep Select Ops Control-Surface Catalog

Last verified: 2026-08-30

## Purpose

This is the handoff contract between the Pep Select website repository and the future PepSelect Ops integration milestone. It catalogs every custom WordPress plugin in this repository, the control hooks that already exist, the controls that remain WordPress-only, and the customer-facing theme bridges that Ops may need to consume.

Update this file whenever a custom plugin adds, removes, or changes a setting, REST route, hook, stored option, table, or Ops-owned product/order field.

## Status legend

- **READY** — authenticated Ops settings or operational endpoints already exist.
- **PARTIAL** — an Ops integration exists, but some settings still require WordPress administration or an indirect data path.
- **NONE** — no authenticated Ops control contract exists yet.
- **READ-ONLY** — public storefront data endpoint; never use it for administration.

## Inventory

| Surface | Version | Status | Existing Ops path | Main gap |
|---|---:|---|---|---|
| Pep Select Cart Discounts | 2.0.1 | READY | Versioned authenticated REST settings | Ops UI/client not yet connected |
| Pep Select Cart Recovery | 0.4.8 | READY | Authenticated popup-settings REST endpoint | Add revision conflicts and stronger audit metadata |
| Pep Select Order Experience | 0.3.3 | PARTIAL | Authenticated order snapshot and revoke endpoints | Feature settings are WordPress-only |
| Pep Select Shipping Restrictions | 0.2.6 | NONE | None | Build a versioned policy API before remote control |
| PS Access Gate | 2.2.2 | NONE | None | Build a settings API; keep consent data separately protected |
| Child-theme restocking status | Theme-owned | PARTIAL | Woo product meta and COA records | Formalize the metadata contract and audit trail |
| Child-theme compound search | Theme-owned | READ-ONLY | Public product search route | Not an Ops control surface |

## Shared integration rules for PepSelect Ops

1. Use HTTPS and a dedicated WordPress Application Password owned by a least-privilege service account with `manage_woocommerce` only where required.
2. Store credentials only in the Ops secret store. Never commit them to either repository or expose them to a browser client.
3. Keep separate Staging and Live base URLs, credentials, and audit histories.
4. Read current state before every write. When a route supports `revision`/`if_revision`, always send the last observed revision and handle HTTP 409 by refreshing instead of overwriting.
5. Log environment, actor, request ID, route, old revision, new revision, and a redacted change summary. Never log tokens, customer private data, consent IP data, or Application Passwords.
6. Make retries idempotent. A network retry must not duplicate a discount, rotate an order token unintentionally, or create multiple coupons.
7. Provide an explicit preview or dry-run for high-impact discount and shipping-policy changes. Default newly created promotions to inactive.
8. Preserve the WordPress admin as the emergency fallback. Ops must consume the same stored state and must not maintain a second source of truth.
9. Validate on Staging before Live and retain an environment backup/rollback point for releases that change execution logic.

## 1. Pep Select Cart Discounts 2.0.1 — READY

Source: `pepselect-bogo-quantity/` (the directory name is retained for upgrade compatibility; the plugin name is **Pep Select Cart Discounts**).

### What it controls

- Buy 4 Get 1 Free product eligibility and activation.
- Multiple compound-specific automatic discounts.
- Multiple sitewide automatic discounts.
- Percentage or fixed-cart amounts where supported.
- Quantity or subtotal minimums, including no minimum for sitewide rules.
- Audience targeting for everyone, logged-in customers, subscribers, prior purchasers, VIPs, or selected customer accounts.
- Per-rule stackable/exclusive behavior. The coordinator applies all eligible stackable rules plus the single best eligible exclusive rule.
- Customer-facing labels with bounded length so cart and checkout pills remain usable.

### WordPress administration

Top-level **Cart Discounts** menu with these submenus:

- Overview
- Buy 4 Get 1
- Compound Discounts
- Sitewide Discounts

Saving a compound or sitewide discount creates a separate rule row. Each row can be independently activated, deactivated, edited, or deleted.

### Stored state

- `pepselect_bogo_rule_v1`
- `pepselect_compound_discount_rules_v2`
- `pepselect_compound_discount_rule_v1` — legacy single-rule source, migrated for compatibility
- `pepselect_sitewide_discount_rules_v1`

### Authenticated REST contract

Namespace: `pepselect-bogo/v1`

| Route | Methods | Purpose |
|---|---|---|
| `/buy-four-get-one` | GET, editable write | Read or replace the BOGO rule |
| `/compound-discounts` | GET, editable write | Read or replace the compound rule collection |
| `/compound-discount` | GET, editable write | Legacy alias for the compound collection |
| `/sitewide-discounts` | GET, editable write | Read or replace the sitewide rule collection |

All routes require `manage_woocommerce`. Responses expose a SHA-256 `revision`; writes accept `if_revision` and return HTTP 409 on a stale update. Ops should treat each returned collection as the authoritative document.

### Extension hooks

- `pepselect_bogo_rule`
- `pepselect_bogo_rule_updated`
- `pepselect_bogo_enabled`
- `pepselect_bogo_product_is_eligible`
- `pepselect_bogo_skus`
- `pepselect_sitewide_discount_product_eligible`
- `pepselect_discount_customer_is_subscriber`
- `pepselect_discount_customer_is_vip`
- Customer display bridges consumed by the child theme: `pepselect_product_has_b4g1` and `pepselect_child_b4g1_pill_label`

Subscriber targeting detects FluentCRM membership when available and remains overrideable through the subscriber filter. VIP targeting is intentionally supplied by the filter until Ops becomes the owner of the VIP list.

### Changes already delivered

- Replaced the plugin’s fixed product list with a versioned BOGO rule controllable by WordPress or Ops.
- Made the plugin—not YITH—the authority for eligible BOGO products, including GLP products.
- Added literal cart quantity and one-free-vial behavior plus side-cart, cart, checkout, and product-page messaging.
- Added multi-rule compound discounts with independent activation and customer labels.
- Removed the removable “x” from automatic discount pills.
- Added sitewide rules, audience targeting, customer search, minimums, and stackable/exclusive coordination.
- Consolidated all automatic discounts into one menu with separate submenus.
- Contained WooCommerce customer/product search controls on narrow admin screens and cache-busted the corrected stylesheet in 2.0.1.

### Ops milestone work

- Build typed Ops forms for all three route families.
- Map Ops customer groups to the subscriber and VIP hooks without sending entire customer lists to the browser.
- Add preview output that names eligible products/customers and projected rule priority before activation.
- Keep sitewide rules inactive by default and show a high-impact Live confirmation.

## 2. Pep Select Cart Recovery 0.4.8 — READY WITH CONTRACT GAP

Source: `pepselect-cart-recovery/`

### What it controls

- Exit-intent email capture and restricted WooCommerce coupon creation.
- Signup offer and cart-recovery offer behavior.
- Scheduled campaign popup, timing, content, imagery, and suppression of the exit popup.
- FluentCRM list integration and abandoned-cart email integration.

### Stored state and administration

- Option: `pepselect_cart_recovery_settings`
- WordPress location: WooCommerce Popups, with Exit Popup and Campaign Popup views.

### Authenticated REST contract

- Namespace: `pepselect/v1`
- Route: `/popup-settings`
- Methods: GET and editable write
- Permission: `manage_woocommerce`
- Filter: `pepselect_popup_settings`
- Post-update action: `pepselect_popup_settings_updated` with sanitized settings, source, and WordPress user ID

The plugin also exposes a nonce- and rate-limited public AJAX capture action. That action is a storefront submission endpoint, not an Ops settings API.

### Changes already delivered

- Exit popup with private email-restricted discount generation.
- Signup and abandoned-cart recovery discounts.
- Live admin previews and a scheduled campaign popup.
- Campaign scheduling, mutual suppression, design/copy hardening, email compatibility, and cache-busted assets.

### Ops milestone work

- Add `revision`/`if_revision` optimistic concurrency and HTTP 409 behavior matching Cart Discounts.
- Return a redacted normalized settings document; never expose generated coupon secrets or subscriber data.
- Add request ID and old/new revision to the update action or an audit event.
- Build Staging preview and schedule validation in Ops before Live activation.

## 3. Pep Select Order Experience 0.3.3 — PARTIAL

Source: `pepselect-order-experience/`

### Existing Ops operational API

Namespace: `pepselect-order-experience/v1`

| Route | Method | Purpose |
|---|---|---|
| `/orders/{order_id}/snapshot` | POST | Store a validated, immutable fulfillment snapshot and obtain/rotate an access token |
| `/orders/{order_id}/revoke` | POST | Revoke the private customer order-page access token |

Both routes require `manage_woocommerce`. Snapshot writes require schema version 1, exact WooCommerce order-line matches, batch allocations, and a matching SHA-256 snapshot hash.

WordPress stores only the SHA-256 hash of the access token. Ops is the sole keeper of the printable token returned at creation/rotation.

### Stored data

- Table: `{$wpdb->prefix}pepselect_order_access`
- Order item meta: `_pepselect_order_allocations_v1`
- Order meta: `_pepselect_order_experience_snapshot_version`
- Order meta: `_pepselect_order_experience_snapshot_hash`
- Order meta: `_pepselect_order_experience_allocation_status`

### WordPress-only settings

- `pepselect_oe_enabled`
- `pepselect_oe_relationships_approved`
- `pepselect_oe_coupon_code`
- `pepselect_oe_blocked_compounds`
- `pepselect_oe_page_id`
- `pepselect_oe_table_version`
- Filter: `pepselect_oe_blocked_relationships`

### Changes already delivered

- Secure tokenized customer order pages and revocation.
- Exact order/batch/COA snapshots, batch report links, and fulfillment allocation metadata.
- Reorder, restocking, related-product, privacy, throttling, responsive-layout, and release hardening.

### Ops milestone work

- Keep snapshot/revoke as operational endpoints; do not merge them into settings CRUD.
- Add a separate versioned settings endpoint for enablement, relationship approval, coupon code, and blocked compounds.
- Add idempotency keys for snapshot writes and require explicit `rotate_access=true` for rotation.
- Redact access tokens from logs and never add an endpoint that reads the stored token hash as if it were reusable.

## 4. Pep Select Shipping Restrictions 0.2.6 — NONE

Source: `pepselect-shipping-restrictions/`

The plugin currently enforces a code-owned shipping policy through WooCommerce and Fluid Checkout hooks. It controls allowed states/territories, ZIP/state/country validation, available package rates, and USPS-only service for designated regions. There is no option, admin screen, REST route, or custom Ops action/filter for remote policy management.

### Changes already delivered

- Hard shipping exclusions and accessible address validation.
- Rate-cache invalidation when an address changes.
- Alaska, Hawaii, and Puerto Rico support, including Puerto Rico domestic USPS/address behavior.

### Ops milestone work

- Create a versioned policy document for allowed regions, USPS-only regions, excluded ZIP ranges, and customer messages.
- Add authenticated GET/edit routes with revision conflicts, audit events, policy validation, and a dry-run address evaluator.
- Preserve the current hard-coded policy as the safe fallback until a valid Ops policy is explicitly activated.
- Treat this as high risk: regression tests must cover checkout, shipping rates, taxes, and payment completion before Live activation.

## 5. PS Access Gate 2.2.2 — NONE

Source: `ps-access-gate/`

### Current WordPress state

- Settings option: `psag_settings`
- Database version: `psag_db_version`
- Consent table: `{$wpdb->prefix}psag_consents`
- WordPress Settings API administration and nonce-protected CSV export
- Public/authenticated AJAX consent recording
- Cache purge on settings changes

The AJAX endpoint records customer consent. It is not an Ops control endpoint.

### Changes already delivered

- Simple and advanced compliance modes, age/research confirmation, cookie/form versioning, consent logs, and CSV export.
- Accessibility, focus/inert handling, responsive design, branded logo, opacity, and scroll-position fixes.

### Ops milestone work

- Add authenticated, versioned settings GET/edit routes with concurrency and an update audit action.
- Keep consent records on a separately permissioned read/export surface. Never include IP, browser, or consent evidence in the general settings response.
- Require a Staging preview and Live confirmation for wording, minimum-age, cookie-version, and form-version changes.

## 6. Child-theme restocking and COA bridge — PARTIAL/INDIRECT

Source: `pepselect-child/inc/compound-status.php`

- Ops can mark a product with private WooCommerce product meta `_pepselect_restocking_soon=yes`.
- The storefront reads COA plugin records from `ps_compound` and `ps_coa_test`.
- Product mapping uses `pepselect_coa_product_id`, with legacy `woocommerce_product_id` compatibility.
- Batch state uses `compound_id` and `workflow_stage`.
- Terminal COA stages are controlled by `PEPSELECT_COA_TERMINAL_STAGES`, defaulting to complete.
- Adding, updating, or deleting `_pepselect_restocking_soon` invalidates the storefront status cache.

### Ops milestone work

- Document one canonical Woo/COA mapping owner and formalize the allowed meta values.
- Prefer an authenticated adapter endpoint or a narrowly scoped WooCommerce product-meta client over direct database writes.
- Add audit ownership, idempotent updates, and a read-after-write storefront status check.

## 7. Child-theme public and presentation bridges

These are cataloged to prevent Ops from mistaking them for administrative APIs.

### Compound search — READ-ONLY

- Namespace: `pepselect-child/v1`
- GET `/compound-search?term=...`
- Public permission callback
- Minimum search length 2; maximum 8 published, visible products
- Returns title, strength, URL, and stock state with short public caching

This is safe for storefront search and must never accept settings writes.

### Presentation filters — NOT SETTINGS APIs

- `pepselect_product_has_b4g1`
- `pepselect_child_b4g1_pill_label`
- `pepselect_child_cashback_history_raw`
- `pepselect_child_points_balance`
- `pepselect_child_order_tracking`

These adapt plugin/Ops-owned state for the customer interface. Ops should control their upstream data, not call the filters remotely.

## Endpoints that must not become general Ops controls

- Public cart-recovery email capture AJAX.
- PS Access Gate consent-recording AJAX.
- Public child-theme compound search.
- Private order-page access URLs or token hashes.
- Direct database writes to WordPress options, order access records, consents, or COA tables.

## Recommended Ops implementation order

1. **Cart Discounts** — consume the complete revisioned API and build BOGO, Compound, and Sitewide submenus.
2. **Cart Recovery** — add revision protection, then connect Exit Popup and Campaign Popup controls.
3. **Order Experience** — retain snapshot/revoke and add a separate settings contract.
4. **Restocking/COA bridge** — formalize the existing product-meta and batch workflow hookup.
5. **PS Access Gate** — add settings control while keeping consent evidence isolated.
6. **Shipping Restrictions** — migrate last, with the deepest checkout and fulfillment validation.

## Definition of done for the future Ops milestone

- Every READY/PARTIAL surface has typed request/response models, redacted logs, environment isolation, retries, and audit history.
- Stale settings writes produce a visible conflict rather than silently replacing WordPress changes.
- All creation screens default to inactive and show the affected scope before activation.
- Staging-to-Live promotion preserves the exact reviewed settings revision.
- WordPress admin and Ops show the same state after read-after-write verification.
- Integration tests prove unauthorized requests fail and public endpoints cannot mutate settings.
- Shipping, checkout, coupons, payments, customer privacy, consent evidence, orders, COA links, and existing discount rules remain intact.

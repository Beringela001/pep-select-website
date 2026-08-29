# Pep Select Site Health Incident Audit

Date: August 29, 2026

## Verdict

The storefront is not broadly broken. Product, cart, and checkout requests completed successfully during the audit, no current HTTP 500 pattern was found, and Easyship remains connected to WooCommerce. The absence of orders today is therefore not enough to identify an outage.

Several real maintenance and abuse issues were found. The highest-confidence checkout issue is an outdated Easyship plugin that is known to misidentify WooCommerce 10.x, generate excessive errors, and cause checkout issues on some stores.

## Confirmed findings

| Area | Evidence | Risk | Required action |
| --- | --- | --- | --- |
| Storefront and checkout | Public storefront and checkout returned HTTP 200 with no current browser-console or HTTP 500 failure pattern. | No broad outage found. A transaction can still fail for a customer-specific address, rate, or payment response. | Run a staged checkout matrix after updates, then one controlled live smoke test after deployment approval. |
| Easyship connection | Easyship shows `pepselect.com` connected and last synced August 29 at 2:08 PM. WordPress contains the matching access token. Order auto-sync is every 6 hours. | Credentials are not missing. | Do not rotate or reconnect the token. |
| Easyship plugin | WordPress runs Easyship 0.9.15; 0.9.16 is available. The official 0.9.16 changelog fixes WooCommerce 10.x being treated as old, which caused excessive logging and checkout issues on some sites. | High-confidence source of the observed `api_key`, `api_secret`, `es_taxes_duties`, and deprecated product-property noise. | Update to 0.9.16 on staging first. |
| Easyship product data | Easyship lists 15 products, a 1 lb fallback weight, and no fallback dimensions. | Products missing dimensions may receive incomplete or less accurate rate selection. | Audit dimensions for all shippable products before removing fallback behavior. |
| Cart-abandonment email | The message came from Cart Abandonment Recovery for WooCommerce, not FluentCRM. | Operational confusion, not a broken automation. | Document ownership; do not expect these recipients in FluentCRM list 5. |
| FluentCRM list 5 | List 5 is the explicit Pep Select Offers / exit-offer destination and currently has no subscribers. | No evidence that cart recovery should add contacts there. | Leave logic unchanged unless marketing explicitly chooses a consent-compliant shared audience. |
| Contact email | The message was submitted through the coded contact form. The form had only a nonce and honeypot. Historical messages show repeated unsolicited SEO outreach. | Inbox abuse and mail reputation/load risk. | Theme 0.25.0-beta.64 adds timing, hashed-IP throttling, and duplicate suppression. |
| HPOS and Legacy REST API | WooCommerce warns that HPOS and WooCommerce Legacy REST API 1.0.5 are active together. | Unsupported storage/API combination. | Prove all integrations on staging, then deactivate Legacy REST API. |
| Control App dependency | Control App source and operations documentation use `/wp-json/wc/v3/*`, including HPOS-safe by-ID order reads. No legacy `/wc-api/*` dependency was found. | Control App does not justify keeping Legacy REST API active. Unknown external consumers must still be checked. | Test order/product/webhook sync after staging deactivation. |
| Background work | Kinsta reported 388 PHP worker-limit events with two workers. | Requests can queue during traffic or cron bursts even without 500 responses. | Recheck after Easyship logging fix and scheduled-action cleanup; profile slow endpoints before increasing plan capacity. |
| Scheduled Actions | 134 historical failures were present, mostly obsolete WooPayments or migration work; 24 actions were pending. | Old failures create noise; pending actions need current-state verification. | Do not bulk-delete. Verify recurring owners, run due actions normally, and archive only confirmed obsolete records. |
| Updates and vulnerability | WordPress currently reports 26 plugin updates; Kinsta reported one vulnerable component. | Security and compatibility exposure. | Identify the vulnerable package, back up, then stage updates in dependency groups rather than bulk-updating live. |
| FluentCRM delivery | Two marketing sends failed through Mailgun last night; no transactional-mail failure pattern was found. | Small campaign-delivery gap, not store-order failure. | Inspect the two provider responses and retry only when the recipients remain eligible. |
| Pinterest | WooCommerce reports an invalid Pinterest access token and no operations possible until reconnect. | Product-feed/marketing impact only. | Reconnect only if Pinterest remains an active channel; otherwise deactivate the unused integration on staging. |

## Implemented locally

Theme 0.25.0-beta.64 contains:

- three-second minimum form-fill time;
- five valid submissions per hashed connection identity per hour;
- no storage of raw IP addresses and no trust in forwarded-IP headers;
- exact-message replay suppression for 15 minutes after successful delivery;
- generic success responses for filtered submissions so bots receive no signal;
- unchanged support inbox and Reply-To behavior.

## Staging remediation sequence

1. Create and verify a staging backup.
2. Update Easyship 0.9.15 to 0.9.16.
3. Confirm Easyship remains connected, token remains populated, and product/order sync completes.
4. Test cart quantity changes and checkout rates for Georgia, Alaska, and Puerto Rico with representative products.
5. Confirm the Easyship warning volume stops and no checkout fatal/error appears.
6. Deactivate WooCommerce Legacy REST API on staging.
7. Test Control App order reads, product reads/writes, status write-back, and webhook inventory.
8. Deploy and test theme 0.25.0-beta.64 contact protection.
9. Identify the vulnerable package and stage plugin updates in compatible dependency groups.
10. Review pending Scheduled Actions and the two failed FluentCRM sends using their current provider responses.
11. Review Kinsta worker saturation after the noisy Easyship code and due jobs are corrected.

## Acceptance gates

- Product, cart, checkout, account, order tracking, payment, shipping, rewards, VerifyPass, and COA flows pass on staging.
- Easyship returns rates for the required domestic destination matrix.
- Cart quantity changes do not produce a fatal error.
- Control App `/wp-json/wc/v3/*` reads and writes pass with Legacy REST API inactive.
- Contact form sends one legitimate message, silently drops an immediate bot-style submission, suppresses an exact replay, and throttles the sixth valid attempt within one hour.
- No new PHP fatal, HTTP 500 pattern, or unresolved scheduled-action backlog appears during the observation window.

## Rollback

- Easyship: restore the staging backup or reinstall 0.9.15 only if 0.9.16 fails the acceptance matrix.
- Legacy REST API: reactivate it only if a named integration demonstrably depends on `/wc-api/*`; capture that consumer before rollback.
- Contact protection: redeploy theme 0.25.0-beta.63 if legitimate submissions fail, then diagnose the exact guard involved.
- Live changes: require a fresh backup and explicit deployment authorization. No live configuration or plugin changes were made during this audit.

## Evidence boundaries

Graphify was queried for Easyship, HPOS, cart-recovery, and contact dependencies. The existing graph did not contain useful application-level edges for these flows, so no dependency claim above relies on inferred graph links. Source references and live read-only configuration were used instead.

Official Easyship changelog: https://wordpress.org/plugins/easyship-woocommerce-shipping-rates/

# Live Pass 2 Email Delivery — 2026-08-29

## Outcome

Pep Select was not broken and Mailgun authentication was not failing. The
campaign-delivery incident was recipient-provider throttling caused by a fast
marketing burst from Mailgun's shared sending IP. Pass 2 updated the matched
FluentCRM pair and reduced FluentCRM campaign delivery to one message per
second. WooCommerce transactional email remains on its separate Gmail route.

## Incident evidence

- Mailgun's 30-day overview showed 94.85% delivered, 164 failed events, and no
  suppressions.
- The last-24-hour failure view contained 126 retry/failure events affecting 74
  unique recipients, not 126 permanently lost messages.
- Recipient distribution: Yahoo 40, AOL 26, Optimum 3, Gmail 2, AIM 2, and
  Netscape 1.
- Yahoo/AOL/AIM/Netscape returned temporary `TSS04` deferrals for unexpected
  volume or complaints. Optimum temporarily restricted recipient volume.
- A representative Optimum message delivered after two temporary failures and
  about 20 minutes of retrying.
- Only two messages became permanent failures; both Gmail recipients reported
  full inboxes.
- SPF, DKIM, Mailgun MX, tracking CNAME, and DMARC were present and verified.
  DMARC remains in monitoring mode (`p=none`).
- The affected campaign sent 208 messages at about 3–4 messages per second.
  It followed a 1,094-message campaign within roughly one day.
- WP Mail SMTP had only the same two inbox-full messages marked Not Sent.
- FluentCRM's queue was current. No overdue email actions or broken cron loop
  was found.

## Routing verified

- FluentCRM Core or Pro initiators route through `Mailgun – FluentCRM`.
- WooCommerce initiators route through the `support@pepselect.com` Google/Gmail
  connection.
- Unmatched WordPress mail continues through the primary Gmail connection.
- The FluentCRM throttle therefore does not slow WooCommerce order,
  payment, or shipping email.

## Changes

### Staging

- FluentCRM Core: `3.1.10` → `3.1.13`.
- FluentCRM Pro: `3.1.11` → `3.1.13`.
- Pep Select Cart Recovery: `0.1.2` → `0.1.5` main integration code.
- Global FluentCRM UI rate normalized from 11 to its supported minimum of 4.
- Custom filter sets the effective campaign limit to one message per second.

### Live

- FluentCRM Core: `3.1.10` → `3.1.13`.
- FluentCRM Pro: `3.1.11` → `3.1.13`.
- Pep Select Cart Recovery: `0.1.4` → `0.1.5` using the verified package.
- Added `fluent_crm/global_email_limit_per_second` at priority 100 with an
  effective limit of one message per second.

FluentCRM documents that its UI-derived value is floored at four before this
filter runs, so the project filter is the supported point for the lower
provider-warmup limit:
https://developers.fluentcrm.com/hooks/filters/emails-and-sending

WP Mail SMTP Pro `4.9.0` and Cart Abandonment Recovery `2.1.3` had no available
update. Back In Stock Notifier `7.2.2` → `7.4.1` is independent of this
campaign incident and remains outside this paired CRM release.

## Package

- Artifact: `dist/pepselect-cart-recovery-0.1.5.zip` (not committed).
- SHA-256: `E2B31915010BD89225756A50D71A5864110BA8F57A960E3C7AAA546B5D4D0302`.

## Verification

- PHP syntax check passed.
- Cart Recovery contract test passed.
- Dedicated FluentCRM throttle contract test passed.
- Cart Recovery JavaScript syntax check passed.
- Staging storefront and FluentCRM settings loaded without a fatal error.
- Staging FluentCRM minute scheduler was current with no overdue work.
- Live plugin inventory reports FluentCRM Core `3.1.13`, FluentCRM Pro
  `3.1.13`, and Pep Select Cart Recovery `0.1.5` active.
- Live storefront and FluentCRM settings loaded without a fatal error.
- Live FluentCRM minute scheduler was current with no overdue work.
- Live Smart Routing still separates FluentCRM/Mailgun from
  WooCommerce/Gmail.
- No customer email or test email was sent during deployment.

## Rollback

- Staging: restore `Before Pass 1 platform maintenance staging - 2026-08-29`
  from Aug 29, 2026 at 9:38 PM.
- Live: restore `Before Pass 1 platform maintenance live - 2026-08-29` from
  Aug 29, 2026 at 9:52 PM, or reinstall
  `dist/pepselect-cart-recovery-0.1.4.zip`.

## Follow-up

Review Mailgun after the next intentional campaign. Keep the one-per-second
limit while the shared-IP/domain reputation stabilizes. Reconsider a higher
rate only after consecutive campaigns show no Yahoo/AOL `TSS04` burst pattern.

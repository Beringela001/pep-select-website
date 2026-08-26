# WEB M16 — Exit Capture, Cart Recovery, and Revenue Attribution

Status: implementation milestone. Live remains unchanged until the release checkpoint is approved.

## Outcome

Turn more anonymous product interest into measurable, permission-based follow-up without slowing the storefront or blurring the SEO results already appearing in new markets such as Massachusetts.

This milestone adds a restrained exit-intent email offer, connects captured shoppers to WooCommerce Cart Abandonment Recovery when a cart exists, and sends at most three useful reminders: about 90 minutes, 24 hours, and 48 hours after the shopper stops. It also distinguishes organic discovery, popup capture, cart recovery, and completed revenue.

## What the audit found

- Cart Abandonment Recovery for WooCommerce `2.1.3` is installed and tracking is enabled.
- A cart becomes abandoned after 60 minutes. The plugin retains an abandoned record for at least 10 days; that does not require emails to continue for 10 days.
- All three recovery email templates are currently disabled.
- The current template schedule is 30 minutes, 1 day, and 3 days after abandonment. With the 60-minute cutoff, the first send is already approximately 90 minutes after the shopper stops.
- The dashboard currently reports one recovered order and `$374.38` recovered revenue for August 20–26, but disabled templates mean that result is not evidence that an email caused the purchase.
- The plugin starts identifying guest carts only after an email is entered at checkout. It cannot currently recognize most visitors who add products but never enter checkout details.
- Sender and reply-to fields are blank in the recovery plugin. Both must be set to `support@pepselect.com` at release.
- `WELCOME10` is currently 10% with a `$75` minimum and “individual use only” enabled. It is therefore not currently stackable and does not yet match the requested `$100` minimum.
- Site Kit has Search Console, Analytics, and PageSpeed Insights connected. FluentCRM is active, but a dedicated offer list does not yet exist.

No customer names or email addresses belong in project documentation, analytics, or GitHub.

## Preserve, refine, replace, remove

### Preserve

- WooCommerce as the authority for carts, orders, coupons, and customer sessions.
- The installed recovery plugin as the authority for abandoned-cart records, restoration links, scheduling, suppression after purchase, and unsubscribe handling.
- Pep Select's current email design language: white card, navy/cyan accent, Plus Jakarta Sans labels, IBM Plex Mono figures, restrained radius, and plain-language support.
- Existing Search Console, Analytics, and PageSpeed measurement.

### Refine

- Change the active sequence to 30 minutes after the 60-minute cutoff, 23 hours after that, and 47 hours after that. Customer-facing elapsed time becomes approximately 90 minutes, 24 hours, and 48 hours after activity stops.
- Explicitly configure `Pep Select <support@pepselect.com>` and reply-to `support@pepselect.com`.
- Change `WELCOME10` to a `$100` minimum and allow combination with eligible coupons.
- Label recovered revenue by actual touchpoint instead of treating every recovered cart as email-caused.

### Replace

- Replace the plugin's generic email bodies with the approved Pep Select HTML and plain-text sequence.
- Replace the third-party-style interruptive popup pattern with a native, small exit panel that does no work until a qualifying visitor signals exit.

### Remove

- Remove fake urgency, countdown language, “Hurry,” and repeated checkout pressure.
- Stop recovery messaging after the 48-hour note. The underlying record may remain for reporting and suppression until the plugin marks it lost.

## Customer flow

1. A visitor browses normally. No popup network request runs and no space is reserved in the page layout.
2. On desktop, exit intent means the pointer moves toward the browser's top edge after a minimum engaged visit. On touch devices, use a conservative fallback based on meaningful engagement and back/visibility behavior; do not fire immediately on arrival.
3. The panel offers one unique 10% code in exchange for an email address and clear consent. It can be dismissed permanently for the configured cooldown.
4. WooCommerce creates one email-restricted, single-use, percentage coupon. It may combine with eligible coupons, including the updated `WELCOME10`.
5. The subscriber is added to a new FluentCRM list named `Pep Select Offers`. Cart reminders remain transactional follow-up to the disclosed cart-recovery consent, while broader promotional sends obey marketing consent and unsubscribe status.
6. If a non-empty WooCommerce cart exists now or later in the same session, the captured email and coupon are passed to the installed abandonment plugin. The plugin owns restoration and scheduling.
7. A completed or processing order suppresses the remaining reminders.
8. If no order occurs, reminders stop after the 48-hour email. No fourth message is sent.

## Offer rules

### Exit offer

- Discount: 10% percentage coupon.
- Code: unique, non-guessable, and generated server-side.
- Restrictions: one use, restricted to the submitted email, no free shipping, product subtotal only.
- Combination: permitted with eligible coupons.
- Expiration: configurable; initial release target is 7 days so the code remains useful without becoming a permanent leaked offer.
- Reuse: the same captured code appears in recovery emails. Do not create a second 10% code for the same session.

### WELCOME10

- Discount remains 10%.
- Minimum eligible merchandise subtotal becomes `$100`.
- “Individual use only” is disabled so it can combine with the exit code.
- Existing one-use-per-customer behavior remains.
- Checkout QA must verify the intended combined 20% outcome and interactions with automatic free-vial promotions, rewards, sale items, shipping thresholds, and excluded products.

## Message sequence

| Touch | Approximate time after activity stops | Job | Discount |
|---|---:|---|---|
| Exit panel | During a qualifying exit | Turn anonymous interest into permission-based identity | Unique 10% |
| Email 1 | 90 minutes | Restore context without pressure | Reuse captured code when present |
| Email 2 | 24 hours | Offer human help and answer hesitation | Reuse captured code when present |
| Email 3 | 48 hours | Friendly final note; explicitly stop hovering | Reuse captured code when present |

If a cart was captured at checkout without an exit-offer code, the sequence must not silently invent a second promotion. Discount presentation is conditional.

## Measurement model

The funnel must answer four different questions:

1. **Discovery:** Which landing page, query channel, campaign, and region brought the visit?
2. **Interest:** Did the visitor view products, documentation, use search, add to cart, or begin checkout?
3. **Identity:** Did the visitor submit the exit offer or enter an email at checkout?
4. **Revenue:** Was the order organic/direct, popup-assisted, cart-email-assisted, or recovered without an email click?

### Analytics events

- `pep_exit_offer_eligible`
- `pep_exit_offer_view`
- `pep_exit_offer_dismiss`
- `pep_exit_offer_submit`
- `pep_exit_offer_success`
- `pep_cart_identified`
- `pep_recovery_email_click` with touch `90m`, `24h`, or `48h`
- WooCommerce/GA4 commerce events already in use: product view, add to cart, begin checkout, purchase

Never send email addresses, coupon codes, names, or other personally identifiable information to GA4. Attribution parameters may contain only non-identifying campaign and touch labels.

### Geographic SEO evidence

Massachusetts orders are a useful lead because that state was not intentionally advertised, but location alone cannot prove SEO. The reporting view should join or compare:

- order state and revenue;
- first landing page;
- first and last source/medium;
- campaign/referrer where available;
- Search Console landing-page impressions and clicks for the same period;
- popup submission and recovery-email click flags.

Classify a sale as **SEO-supported** only when the first recorded acquisition source is organic search or the evidence chain strongly supports an organic landing page. Classify missing-source sales as unknown/direct, not automatically SEO.

## Performance and SEO budget

- Native plugin code only; no third-party popup SaaS, remote JavaScript, new font, animation library, or popup image.
- Load the small stylesheet only on public WooCommerce/catalog surfaces.
- Defer the script and keep it dormant until eligibility checks pass.
- No network request before a visitor submits the form.
- Inject the closed dialog outside normal layout flow to avoid cumulative layout shift.
- Reserve no hero or product-grid space for it.
- Use CSS transitions that respect `prefers-reduced-motion`.
- Exclude bots, logged-in administrators, checkout/order-received pages, and visitors who already submitted or dismissed within the cooldown.
- Performance regression gate: no material decline in mobile Lighthouse performance, LCP, INP, or CLS versus the same URLs and environment immediately before release.

## Privacy and deliverability

- The form states that the visitor receives the code, occasional Pep Select email when separately consented, and up to three reminders if they start a cart.
- Include a working unsubscribe link in every recovery email.
- Honor FluentCRM unsubscribe status and the recovery plugin's own suppression list.
- Do not send reminders when the cart is empty, the order has been placed, the email is invalid, or the customer opted out.
- Rate-limit coupon creation and form submission by session and server-side safeguards.
- Send through the existing WooCommerce mail path with explicit from/reply-to addresses; do not add a second delivery service in this milestone.

## Milestones

### M16.1 — Baseline and attribution

- Record current cart-plugin configuration and recovery totals.
- Capture baseline storefront performance on representative home, shop, product, cart, and checkout pages.
- Confirm GA4 commerce events and define a geographic/source revenue report.
- Record Massachusetts orders as an attribution investigation, not a conclusion.

### M16.2 — Exit capture and coupon service

- Build the accessible desktop and mobile panel.
- Create the server-side coupon endpoint, validation, rate limiting, session storage, and consent record.
- Add the dedicated FluentCRM list integration with a safe no-plugin fallback.
- Add non-PII analytics events.

### M16.3 — Cart identity and recovery sequence

- Connect the submitted email and existing unique code to the active WooCommerce cart and installed recovery plugin.
- Install the approved Pep Select HTML and plain-text email set.
- Enable the sequence at approximately 90 minutes, 24 hours, and 48 hours.
- Configure sender, reply-to, restoration links, UTM touch labels, unsubscribe, and purchase suppression.

### M16.4 — Offer compatibility and quality assurance

- Update `WELCOME10` to `$100` minimum and stackable.
- Test unique-code restrictions and the intended combined 20% case.
- Test guest and logged-in flows, desktop and mobile, dismiss cooldown, duplicate submit, empty cart, restored cart, completed order, unsubscribe, expired coupon, and email-client rendering.
- Verify free-vial, rewards, sale-price, tax, shipping, and checkout behavior remain intact.

### M16.5 — Performance release and learning loop

- Compare pre/post performance and block release if the storefront regresses materially.
- Create a manual backup, deploy through the approved WordPress path, clear caches, and run live smoke tests without creating a real customer order.
- Review after 7, 14, and 30 days: panel view-to-submit rate, add-to-cart rate, identified-cart rate, email click rate by touch, recovered orders, recovered revenue, unsubscribe rate, coupon stacking, and revenue by first source/state.
- Optimize copy or trigger thresholds only after enough traffic exists to distinguish signal from daily noise.

## Release acceptance criteria

- The panel does not appear on first paint and causes no visible layout shift.
- A submitted visitor receives exactly one unique 10% code and is not repeatedly prompted.
- A later cart in the same session becomes identifiable to the recovery plugin.
- The same code is reused throughout the sequence and can combine with the revised `WELCOME10`.
- No more than three reminders send, at the intended elapsed times, and all later sends stop after purchase or opt-out.
- Email design matches the current Pep Select order and back-in-stock family in Gmail, Outlook, and mobile widths.
- Analytics distinguish organic acquisition, popup capture, recovery-email clicks, and unassisted recovered carts without collecting PII.
- Representative storefront performance remains within the agreed regression budget.
- Rollback can restore the prior plugin/settings state without touching products, customers, orders, payment, shipping, rewards, VerifyPass, or COA logic.

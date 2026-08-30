# Pep Select Email Marketing Strategy

**Status:** Internal strategy. No email, automation, discount, platform migration, or customer-data export is authorized by this document.

**Revision:** v3

**Last updated:** 2026-08-30

## Objective

Use permissioned email to help a visitor evaluate Pep Select, recover an interrupted purchase, understand the order process, review available batch documentation, and decide whether to return. Measure the system by profitable incremental orders, repeat purchase, inbox placement, complaints, and unsubscribes rather than attributed revenue alone.

Email remains subject to Pep Select's research-use, evidence, privacy, consent, and claim-verification rules. It is an owned audience channel, not a private exception to public marketing standards.

Batch matching is the system's proof spine. Use email to connect a viewed or purchased compound to the correct Pep Select record only when authoritative order, inventory, and documentation data establish the exact relationship. Otherwise, link to general product information or testing history without implying an exact batch match.

## Current Pep Select Foundation

- FluentCRM is the current self-hosted customer relationship and automation layer.
- FluentCRM campaigns use the Mailgun route; WooCommerce transactional messages remain on the separate Gmail route.
- The current campaign limit is one message per second while shared-IP and domain reputation stabilize.
- A four-message cart-recovery sequence and private offer already exist in implementation work.
- Back-in-stock messages already use the approved Pep Select customer-email design.

These existing systems take precedence over the source video's platform recommendations. Do not migrate providers based on one case study.

## Source and Confidence

Source: [These Email Flows Sold Seven Figures of Peptides](https://www.youtube.com/watch?v=vBYIMUkHJ8Y), Daniel Budai, 12:49. The supplied transcript appears machine-generated and may contain naming or wording errors.

The revenue figures, platform-acceptance statements, timing recommendations, and causal claims belong to the presenter. Pep Select has not independently verified them. Treat them as hypotheses or operating ideas, not proof.

## Techniques to Adopt

### 1. Give every flow one job

Each automation should remove one specific source of uncertainty or support one lifecycle moment. Define entry criteria, exit criteria, suppression rules, timing, message goal, primary action, and success metric before writing copy.

### 2. Make batch matching the proof spine

Use the exact compound, labeled strength, batch identifier, testing status, and available report only when Pep Select's authoritative systems support every field. A product-level link must remain product-level. Do not convert it into a batch claim through copy, imagery, or proximity.

Post-purchase email can provide the strongest implementation when the fulfilled order records the shipped batch. If the system does not capture that relationship, send the customer to the product's testing history and state what the page contains.

### 3. Prioritize welcome, checkout recovery, and post-purchase

The case study reported that welcome and post-purchase generated the most attributed revenue. Pep Select should treat that result as a prioritization clue, then validate it with its own data.

- Stabilize the existing checkout-recovery sequence first.
- Build a permissioned welcome flow for people who explicitly join an eligible list.
- Build separate first-order and returning-customer post-purchase paths when their questions and next steps differ.

### 4. Make checkout recovery a trust and decision-support flow

Use a clear mobile CTA, a reliable saved-cart link, accurate dynamic product content, visible support access, current shipping and payment expectations, and links to available batch documentation. QA every dynamic block and destination before activation.

The existing four-message Pep Select sequence already follows much of this pattern. Improve it through testing and operational accuracy before adding more messages.

### 5. Split customers by behavior and eligibility

First-time customers, returning customers, dormant customers, and active subscribers need different messages. Segmentation must use documented criteria and honor unsubscribe, recovery suppression, and consent status across systems.

### 6. Protect list portability and deliverability

- Create regular encrypted exports of eligible contacts, consent/source fields, unsubscribe state, and suppression state.
- Document access, retention, recovery testing, and deletion rules for every export.
- Suppress or reduce frequency for unengaged contacts instead of repeatedly mailing them.
- Monitor complaints, temporary deferrals, permanent failures, inbox placement, and provider concentration after every intentional campaign.
- Keep FluentCRM and WooCommerce delivery routes separated unless a tested change proves safer.

### 7. Moderate customer-supplied content

Review every testimonial, review excerpt, image, and user-supplied statement before it enters an email. Reject personal outcomes, health or transformation claims, human-use implications, unverified facts, and context that changes the meaning of the original review.

## Seven-Flow Roadmap

| Flow | Pep Select job | Entry and exit controls | Safe message territory | Stage |
|---|---|---|---|---|
| Welcome | Orient an explicitly opted-in subscriber and show how to evaluate Pep Select | Enter through documented consent; exit on unsubscribe, suppression, or purchase when later messages no longer fit | Brand purpose, research-use boundary, catalog navigation, COA archive, support | Build after recovery stabilization |
| Checkout recovery | Restore an interrupted cart and answer decision blockers | Enter from a qualified abandoned checkout; exit on purchase, unsubscribe, suppression, or expiry | Saved cart, current product details, available batch reports, shipping, payment, support | Existing; stabilize and measure |
| Browse follow-up | Help a known, eligible subscriber revisit a product page | Requires approved tracking and consent logic; exit on purchase, inactivity threshold, unsubscribe, or suppression | Viewed compound details, available documentation, related archive navigation | Hold for privacy and tracking review |
| Post-purchase | Set expectations and support the next appropriate step | Enter on paid order; split first-time and returning customers; stop messages that duplicate transactional mail | Order process, payment, fulfillment, documentation access, support, feedback request | High priority |
| Returning-customer relationship | Make repeat communication more relevant without creating a status tier | Use documented purchase history; re-evaluate eligibility; keep evidence and ordinary support available to every eligible customer | Relevant documentation updates, order-process guidance, support, optional early notice where operationally true | Build after history and eligibility validation |
| Winback | Re-engage eligible customers after a meaningful lapse | Start with a 90-day hypothesis, then tune from Pep Select data; exit on engagement, purchase, unsubscribe, or suppression | What changed, current catalog, new documentation, support | Later test |
| Reorder consideration | Surface current information when purchase history suggests possible relevance | Use observed reorder intervals, never predicted personal depletion; exit on purchase, inactivity, unsubscribe, or suppression | Current availability, batch documentation, product-detail review | Hold for data and copy approval |

## Sequence Design Rules

- Use one primary action per email.
- Make the CTA obvious on mobile and describe the destination.
- Link the hero only when its click behavior is clear; do not rely on an image as the sole CTA.
- Render dynamic product, cart, name, coupon, and link fields with realistic test data before activation.
- Include a plain-text fallback and a working unsubscribe mechanism where required.
- Stop recovery and promotional messages promptly after purchase, unsubscribe, or suppression.
- Keep ordinary language short and specific. Put technical detail in the Quality Archive or product record.
- Place support access near the decision point.

## Offer and Urgency Rules

The video's example escalated from reminders to a 15% discount and deadline urgency. Pep Select should not copy that pattern by default.

- Treat the current private 20% offer plus additional 5% recovery code as a bounded test, not the permanent recovery model.
- Do not expand, repeat, or normalize the stacked offer until a holdout test shows profitable incremental orders after discounts, fulfillment costs, refunds, and repeat-purchase effects.
- Never invent a deadline, stock condition, limited quantity, or last chance.
- Lead with saved-cart accuracy, documentation access, shipping and payment clarity, and support before adding or increasing a discount.
- Measure whether a discount creates incremental orders or subsidizes purchases that would have happened anyway.
- Apply coupon eligibility, stacking, expiration, and email restriction exactly as configured.

## Trust Rules

Use verified Pep Select evidence close to the claim it supports: current batch records, accurate order expectations, real support access, and genuine customer feedback that passes moderation.

Every customer-facing email footer should quietly include `🇺🇸 American-owned and operated.` Keep the line subordinate to the message, attach the flag only to that company-level statement, and do not use it to imply that a compound is made, manufactured, sourced, or bottled in the United States. When shipping origin is relevant, say `Pep Select orders ship from New York or Georgia.` Do not recast this as "fulfillment locations" or warehouse-scale language.

Keep batch-level email claims mechanically tied to authoritative records. If an email cannot identify the exact shipped or available batch, use product-level language and link to the testing-history page. Never infer the batch from a product name, vial image, prior purchase, cap color, or packaging resemblance.

Do not adopt these source suggestions without separate proof and approval:

- US flags, US-bottled statements, or origin cues used as generic trust decoration
- Laboratory-testing badges that exceed the actual batch record
- Consumer outcome testimonials
- Unattributed review counts, ratings, customer totals, or revenue figures
- Scientific research presented as a reason for personal use or expected outcomes

## Platform and Sending Resilience

The presenter recommended FluentCRM, MailerLite, or Customer.io and claimed other providers restrict peptide businesses. Provider policies change, and the video is not sufficient vendor due diligence.

Pep Select should keep FluentCRM while it meets operational needs. Before any migration or secondary-provider setup, obtain written category acceptance, data-processing terms, suppression portability, webhook/API capability, sender-authentication requirements, pricing at projected volume, and an export/exit procedure.

Do not frame self-hosting as immunity from enforcement, deliverability controls, privacy duties, or regulators. Confirm whether the current authenticated Mailgun sending identity provides the desired reputation isolation before adding another domain or subdomain.

## Measurement Plan

Track by flow and message:

- Eligible entrants, sends, deliveries, temporary deferrals, permanent failures, complaints, unsubscribes, and suppressions
- Click-through to the promised destination and saved-cart restoration success
- Click-through to the correct product, testing-history, or exact batch record without mismatched identifiers
- Purchase conversion within a defined window
- Incremental lift using holdout groups when volume permits
- Gross margin after discounts and delivery costs
- Repeat purchase rate and time between orders
- Support replies and the unresolved questions they reveal

Treat platform-attributed revenue as directional. Do not compare Pep Select directly with the video's reported revenue without matching audience size, traffic, margins, attribution windows, and flow definitions.

## Implementation Order

1. Audit the existing recovery sequence's triggers, dynamic content, stop conditions, mobile CTA, unsubscribe behavior, and coupon economics.
2. Establish a baseline dashboard for delivery, complaints, conversions, margin, and repeat purchase.
3. Build the permissioned welcome flow.
4. Confirm whether order and inventory records can identify the exact shipped batch before adding batch-specific post-purchase content.
5. Build first-time and returning-customer post-purchase paths without duplicating transactional email.
6. Validate returning and dormant-customer segments, then pilot relationship and winback flows.
7. Review privacy, tracking, and consent requirements before browse follow-up.
8. Analyze real reorder intervals and approve research-framed copy before any reorder-consideration pilot.
9. Add an encrypted, tested list-export and recovery procedure with privacy controls.

## Source-Technique Decisions

| Source technique | Decision | Pep Select adaptation |
|---|---|---|
| Treat email as a portable audience asset | Adopt | Maintain consent-aware exports and documented recovery procedures |
| Seven lifecycle flows | Adapt | Use the roadmap above; do not launch all seven at once |
| Four-message checkout recovery | Adopt with testing | Keep the current sequence, validate every trigger, link, dynamic block, and offer |
| First-time versus returning post-purchase paths | Adopt | Separate only where expectations or support needs differ |
| Top 5–10% VIP segment | Reject the status framing | Use a returning-customer relationship flow based on documented relevance; do not tier access to evidence or ordinary support |
| Three-month winback trigger | Test | Use 90 days as a starting hypothesis, then tune from actual behavior |
| Four-to-five-week replenishment based on running out | Reject as stated | Never predict personal depletion; consider evidence-led reorder timing only from observed data |
| Escalating discounts and last-call urgency | Do not default | Require truthful conditions, margin review, and incrementality testing |
| More social proof | Adapt | Use only genuine, moderated, relevant proof with exact support |
| US flags or US-bottled trust cues | Hold | Require verified operational evidence and a clear customer-information purpose |
| MailerLite or Customer.io migration | Hold | Existing FluentCRM takes precedence; conduct current written vendor due diligence first |
| Separate sending setup | Verify current state | Confirm authentication and reputation isolation before changing domains or routes |
| Suppress unengaged subscribers | Adopt | Define thresholds from engagement and provider data; preserve required suppression records |
| Moderate reviews before sending | Adopt | Apply Pep Select's evidence and human-use boundaries to every excerpt |

## Approval Gates

Paulo must approve each flow's audience, timing, offer, claims, and copy before implementation. Privacy and consent logic must be confirmed before browse tracking or cross-list enrollment. Operational owners must verify shipping, payment, inventory, batch-documentation, and support statements against the live system. No flow may imply an exact batch match without an authoritative data connection, and no customer tier may receive privileged access to core evidence.

## Revision History

- v3 (2026-08-30): Added the owner-confirmed American ownership footer line and direct New York/Georgia ship-from wording, with boundaries against product-origin and dropshipping implications.
- v2 (2026-08-30): Made batch matching the proof spine, replaced VIP framing with a returning-customer relationship flow, and gated the stacked recovery offer behind profitable incremental-lift evidence.
- v1 (2026-08-30): Adapted the source video's lifecycle-email techniques to Pep Select's existing systems and boundaries.

# Pep Select Email Workflow Audit

Audited against the live WooCommerce email registry on August 30, 2026.

## Footer standard

Every customer-facing HTML email uses the same navy Pep Select company footer:

- 🇺🇸 American-owned and operated.
- pepselect.com
- 2090 Baker Rd, Ste 304 #A85
- Kennesaw, GA 30144
- support@pepselect.com
- 1 (833) 737-7528

The shared WooCommerce footer override covers every standard WooCommerce email immediately, including notifications whose body has not yet received a Pep Select redesign. Cart recovery uses an equivalent plugin-owned footer so it remains independent of the active theme. FluentCRM campaign templates must keep the same footer in their saved campaign HTML.

## Customer emails with a completed Pep Select body

| Email | Status | Source |
| --- | --- | --- |
| New account | Built | Child theme template |
| Order on-hold | Built | Child theme template |
| Processing order | Built | Child theme template |
| Completed order | Built | Child theme template |
| Back In Stock — subscription confirmation | Built | Shared BIS template |
| Back In Stock — product available | Built | Shared BIS template |
| Exit-offer discount code | Built | Cart recovery plugin |
| Cart recovery — 90 minutes | Built/configured | Cart Abandonment Recovery template |
| Cart recovery — 24 hours | Built/configured | Cart Abandonment Recovery template |
| Cart recovery — 48 hours + 5% | Built/configured | Cart Abandonment Recovery template |
| Marketing newsletters and campaigns | Built as campaign templates | FluentCRM / newsletter previews |

## Customer emails still using the standard WooCommerce body

These now receive the correct Pep Select footer, but their message body still needs the normal Pep Select workflow: copy review, branded HTML design, mobile review, preview email, and trigger validation.

| Email | Live status | Priority note |
| --- | --- | --- |
| Cancelled order | Disabled | Design before enabling |
| Failed order | Enabled | High priority because it is customer-facing and enabled |
| Refunded order | Enabled | High priority; must cover full and partial refunds clearly |
| Order details | Manual | High priority; includes paid/unpaid invoice and payment-link states |
| Customer note | Enabled | Medium priority; preserve the staff-authored note prominently |
| Reset password | Enabled | High priority account-security workflow |
| POS completed order | Manual | Medium priority if POS is actively used |
| POS refunded order | Manual | Medium priority if POS is actively used |
| Confirm email address | Enabled | High priority account verification workflow |
| Expiring Points | Enabled | Medium priority YITH rewards workflow |
| Updated Points | Enabled | Medium priority YITH rewards workflow |

## Internal store emails

These are operational messages sent to Pep Select staff, not customers. The shared WooCommerce footer still supplies the company information, while the message body remains compact and operational.

| Email | Status |
| --- | --- |
| New order | Built Pep Select admin template; enabled |
| Cancelled order | Standard WooCommerce admin body; enabled |
| Failed order | Standard WooCommerce admin body; enabled |
| Payment gateway enabled | Extension-provided admin body; enabled |

## Recommended next design batch

Build the enabled, high-impact customer workflows together:

1. Failed order
2. Refunded order, including full and partial variants
3. Order details, including paid and unpaid variants
4. Reset password
5. Confirm email address

Then build customer notes, rewards notices, and the two POS notices as one secondary batch.

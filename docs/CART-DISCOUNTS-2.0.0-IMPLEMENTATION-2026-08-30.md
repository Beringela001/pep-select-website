# Cart Discounts 2.0.0 Implementation — 2026-08-30

## Milestone

Consolidate Pep Select's automatic discount controls into one plugin and one WordPress admin area without changing current live settings during migration.

## Delivered

- Renamed the existing combined plugin to **Pep Select Cart Discounts**.
- Added a top-level **Cart Discounts** menu with these submenus:
  - Overview
  - Buy 4 Get 1
  - Compound Discounts
  - Sitewide Discounts
- Added a stackable/exclusive option to Buy 4 Get 1 and every compound or sitewide rule.
- Added one shared stacking coordinator. All qualifying stackable rules run together. If any exclusive rules qualify, only the exclusive rule with the greatest estimated savings runs.
- Added multi-rule sitewide discounts for all products in the canonical `compounds` category.
- Added optional pre-discount compound subtotal or compound quantity minimums.
- Added audiences for everyone, logged-in customers, active subscribers, previous purchasers, VIP customers, and specifically selected customer accounts.
- Added customer search by name or email for specific and VIP lists.
- Added FluentCRM subscriber detection with a protected filter fallback for Ops integrations.
- Preserved non-removable automatic discount pills and the 24-character customer-label limit.
- Extended the authenticated Ops contracts with sitewide rules and stacking fields while accepting the previous BOGO and compound schema versions during migration.

## Safety and migration

- Existing BOGO and compound options remain in their current WordPress option keys.
- Existing rules with no stacking field normalize to **stackable**, preserving the current behavior.
- Sitewide discounts start empty and inactive until a manager creates and activates a rule.
- Customer-specific, subscriber, purchaser, and VIP discounts require login so eligibility cannot be inferred from a guest's checkout email.
- Shipping, taxes, and non-compound accessories are excluded from sitewide discount calculations.
- YITH remains outside this plugin and its matching BOGO rule must stay inactive.

## Verification

- PHP syntax checks passed for all new and changed discount classes.
- Existing compound discount behavior checks passed.
- Existing Buy 4 Get 1 behavior checks passed.
- New sitewide minimum, audience, product-scope, and exclusivity checks passed.
- New cross-engine stacking and best-exclusive-selection checks passed.
- Static plugin integration and frontend asset checks passed.
- `git diff --check` passed.

## Deployment status

Source implementation is complete. No ZIP was built and no staging or live deployment was performed in this milestone. Build, backup, browser verification, and deployment require a separate explicitly authorized installation milestone.

# WEB-0 Environment Inventory

Inventory date: July 16, 2026

## Live

- Environment name: Live
- Domain: pepselect.kinsta.cloud
- Purpose: Production storefront
- Direct redesign work: Prohibited
- Changes require a current backup, clean Git commit, testing, and rollback plan

## Staging

- Environment name: Staging
- Domain: stg-pepselect-staging.kinsta.cloud
- Created by cloning the Live environment
- Environment type: Standard
- Purpose: Website redesign, development, integration testing, and quality assurance
- Search-engine indexing: Blocked
- WooPayments: Safe Mode enabled
- Storefront clone: Verified
- WordPress administration: Verified

## Safety rules

- Do not use the Kinsta Push Environment function without a reviewed deployment plan.
- Do not connect staging WooPayments as a new independent payment account.
- Do not process real customer payments on staging.
- Do not enter real customer information during testing.
- All redesign and functional changes begin on staging.
- Production changes require desktop, tablet, mobile, account, cart, checkout, and order-flow verification.

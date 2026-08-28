# Live Contiguous-U.S. Shipping Release — 0.25.0-beta.54

## Scope

- Restricted WooCommerce shipping to the 48 contiguous states and Washington, D.C.
- Excluded Alaska, Hawaii, Puerto Rico, the U.S. Virgin Islands, other U.S. territories, and overseas military addresses.
- Updated the FAQ and Refund & Shipping Policy with the same customer-facing shipping scope.
- Preserved the existing Free shipping and Easyship methods for eligible addresses.

## Package

- File: `dist/pepselect-child-0.25.0-beta.54.zip`
- SHA-256: `AA9CF5B4E8FF4F964290342C2131D2A7D917CEFCF57CD03649D8778396DC3320`

## Backup and deployment

- Live manual-backup capacity was 5 of 5.
- Verified and deleted the bottom and oldest manual backup: `Before Pep Select cart recovery 0.1.1 live deployment - 2026-08-26`.
- Created: `Before contiguous US shipping beta54 live deployment - 2026-08-27`.
- Replaced Live child theme `0.25.0-beta.53` with `0.25.0-beta.54` through WordPress.
- WordPress confirmed `Theme updated successfully` and `Active Theme — Pep Select Version: 0.25.0-beta.54`.
- Renamed WooCommerce zone `US` to `Contiguous US` and limited it to 49 regions: the lower 48 states plus Washington, D.C.
- Cleared all Kinsta caches from WordPress after deployment.

## Live verification

- WooCommerce lists `Contiguous US` with 49 regions and the existing Free shipping and Easyship methods.
- Alaska and Hawaii are unchecked; Washington, D.C. is checked.
- WooCommerce's `Rest of the world` fallback offers no shipping methods, preventing excluded addresses from receiving a rate.
- The Live FAQ answer states the contiguous-U.S. scope and exclusions.
- The Live Refund & Shipping Policy states the same scope and shows `Last updated: August 27, 2026`.
- The prior `all 50 states` wording is absent from both Live pages.

## Rollback

Restore the pre-deployment Live backup. Alternatively, reinstall `0.25.0-beta.53`, restore the prior FAQ and policy copy, and change the WooCommerce zone back to the full United States.

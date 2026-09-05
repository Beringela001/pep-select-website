# Live NAD QR and order photo correction — 2026-09-05

Released Order Experience 0.4.2 and COA Archive 0.7.8 for the owner's requested Live NAD URL/photo repair.

- The printed `/testing/nad-500-mg/nd50026205jp` URL, with or without a trailing slash, permanently redirects to `/testing/nad-500-mg/nd50026205js/`.
- The older `/testing/nad-500-mg/progress-1269/` QR route points directly to that same corrected report.
- Order Experience recognizes only the approved `ND50026205JP` → `ND50026205JS` batch correction. It uses the public COA's exact vial photo, corrected display batch, and canonical link. Historical Ops snapshots remain intact.
- Public visibility, workflow, and unique-match guards still apply. No product, payment, inventory, customer, or order data was rewritten.

## Verification

- Focused PHP correction contracts passed, including exact redirects, no destination loop, adjacent routes, unknown batches, private records, ambiguous records, and unchanged snapshot quantity.
- Order Experience milestone 2 and security contracts completed successfully; changed PHP passed syntax checks.
- Live original QR paths returned HTTP 301 to the corrected URL; destination returned 200; an unrelated similar batch path returned 404.
- Owner-supplied order page now uses exactly the COA image `/wp-content/uploads/2026/07/ND50026205JS-768x1024.jpg`, displays the corrected batch, and links to the canonical report.
- Mobile check at 390 px: no horizontal overflow; loaded NAD photo, full batch label, and report button visible. Desktop photo loaded. Neighboring compound cards remained intact.
- Both WordPress updates reported success. All Kinsta caches cleared successfully.

## Backup and rollback

Verified the Live manual backup list newest-to-oldest. After the owner's explicit confirmation, removed only the bottom/oldest backup, `Before Cart Discounts 2.3.1 live - 2026-09-03`, created Sep 3 at 12:51 PM.

Created `Before NAD QR and order photo fixes COA 0.7.8 OE 0.4.2 - 2026-09-05`, shown at Sep 5, 12:45 AM. For code rollback, reinstall Order Experience 0.4.1 and COA Archive 0.7.7, then clear caches. The named full backup is also available; restoring it would require accounting for subsequent orders.

## Package identity

- `dist/pepselect-order-experience-0.4.2.zip`: SHA-256 `0F2A922AEF28E99C6AFBE68886E2912E5131FAA61BF41DA4B29F3BF4A0AF5220`.
- COA repository `dist/pepselect-coa-archive-0.7.8.zip`: SHA-256 `92DAF664CC08EF4CB06F612BC66698A4417B2084068077700308F3174FD46F4B`.

Packages are untracked build artifacts. The private order capability URL is intentionally omitted.

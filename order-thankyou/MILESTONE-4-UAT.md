# Order Experience Milestone 4 UAT

Status: implementation and staging evidence in progress. Live customer activation is not authorized by this document.

## Release candidates

- Website: Pep Select Order Experience `0.3.0`, feature switch off.
- Ops: commit and container image recorded after verification, order thank-you card switch off.
- Website rollback: install the recorded `0.2.4` package or deactivate the plugin. The published `/order/` page remains.
- Ops rollback: restore the recorded prior container image. Packing slips and other PrintNode jobs are independent.

Never record customer names, addresses, email addresses, phone numbers, payment data, access tokens, Application Passwords, or QR payloads in this file.

## Automated release gates

- [ ] WordPress PHP syntax passes for every plugin PHP file.
- [ ] WordPress Milestone 2 and Milestone 4 contracts pass.
- [ ] Ops Prisma format and validation pass.
- [ ] Ops lint passes with no new warnings.
- [ ] Ops typecheck passes.
- [ ] Full Ops test suite passes.
- [ ] Ops production build passes.
- [ ] Plugin and Ops release artifacts have recorded SHA-256 values.
- [ ] Local and remote Git heads match in both repositories.

## Initial safe state

- [ ] Staging backup name and time recorded.
- [ ] Website plugin installed on Staging with secure order pages off.
- [ ] Related-compound cards remain owner-unapproved unless Paulo approves the current registry.
- [ ] Coupon section remains hidden unless a valid 15% WooCommerce coupon is selected.
- [ ] Ops thank-you card downloads remain off.
- [ ] `/order/` fallback is published and useful with the feature off.

## Controlled order matrix

Use test orders only. Record order labels that reveal no customer identity.

- [ ] Account order with one item and one batch.
- [ ] Guest order.
- [ ] Multiple quantities from one batch.
- [ ] One product split across two batches.
- [ ] Three-product reference order: GLP-3 R 30 mg, Tesamorelin 10 mg, and GHK-CU 50 mg.
- [ ] Missing vial photo.
- [ ] Missing COA permalink.
- [ ] Pending test.
- [ ] Failed test.
- [ ] Cancelled order.
- [ ] Refunded order, including a partially refunded quantity.
- [ ] Product unavailable for reorder.
- [ ] Coupon already used by the test customer.
- [ ] Revoked token and rotated replacement token.

## End-to-end proof

- [ ] WooCommerce order imports into Ops without changing payment or customer facts.
- [ ] Sale posting settles exact batch allocations.
- [ ] Ops publishes one versioned snapshot to WordPress.
- [ ] WordPress returns a permanent same-origin `/order/` URL only after the record is ready.
- [ ] Enabling the Ops card switch exposes two individual PNG downloads.
- [ ] Front PNG stays byte-identical across orders.
- [ ] Back PNG changes when the secure order URL changes.
- [ ] Printed QR opens the intended private order page on a phone.
- [ ] Vial photo, batch number, laboratory result, tested date, and COA link match the settled allocation.
- [ ] Related products exclude ordered, blocked, hidden, unavailable, and duplicate products.
- [ ] Reorder adds only currently purchasable quantities and reports exclusions clearly.

## Privacy and abuse checks

- [ ] Response sends `X-Robots-Tag` and page sends robots metadata with noindex, nofollow, noarchive, nosnippet, and noimageindex.
- [ ] Response sends `Cache-Control`/WordPress no-cache headers.
- [ ] Response sends `Referrer-Policy: no-referrer` so the opaque token does not follow COA, product, account, or support links.
- [ ] Numeric order IDs do not authorize public access.
- [ ] Wrong, malformed, expired, and revoked tokens all show the same safe fallback content.
- [ ] Repeated invalid token attempts are throttled without storing a raw IP address.
- [ ] Snapshot REST writes require an authenticated user with `manage_woocommerce`.
- [ ] Order page contains no address, email, phone, payment, internal Ops ID, or access-token copy.

## Neighboring-system regression

- [ ] Product, cart, checkout, payment, tax, shipping, tracking, and account flows pass.
- [ ] Inventory allocation and Woo stock synchronization pass.
- [ ] Rewards and VerifyPass pass.
- [ ] Access gate passes.
- [ ] COA archive and exact batch pages pass.
- [ ] Packing-slip download and PrintNode queue pass with card generation both off and on.
- [ ] Public catalog and sitemap behavior remain unchanged; `/order/` remains excluded from indexing.

## Rollback proof

- [ ] Normal Website rollback: disable secure order pages. Native My Account order access still works and printed QR reaches `/order/` fallback.
- [ ] Emergency Website rollback: deactivate the plugin. `/order/` remains published and printed QR reaches fallback.
- [ ] Ops rollback: disable thank-you card downloads. Secure order publishing, packing slips, fulfillment, and other PrintNode jobs continue.
- [ ] Container rollback: restore the recorded previous image and confirm `/api/health`.
- [ ] Re-enable only after every rollback check is demonstrated.

## Evidence required for Live approval

- Exact Website package name, version, SHA-256, Git commit, and Staging installation result.
- Exact Ops image, Git commit, migration result, and `/api/health` response.
- Desktop and mobile screenshots of the controlled order page.
- Front and back PNG dimensions, density, and hashes.
- Photograph or scan result from one physical card.
- Completed test matrix, unresolved findings, and both rollback results.

# Staging SEO Release — 2026-08-18

## Boundary

- Environment changed: Kinsta **Staging** only.
- Live changed: **No**.
- Staging source baseline: fresh Live-to-Staging clone; installed child theme was `0.25.0-beta.14`.
- Recovery point: `Before Claude SEO remediation batch 1 - 2026-08-18`.
- The full manual-backup list was verified first. Only the bottom/oldest entry, `Before SEO Milestone 2 implementation - 2026-08-14`, was removed to make room.

## Deployed packages

| Package | Purpose | SHA-256 |
|---|---|---|
| `dist/pepselect-child-0.25.0-beta.17.zip` | Terms redirect and five verified citation corrections, reconstructed from the exact staging `0.25.0-beta.14` baseline | `E58062BA6F680A9C5BB2A875FE5EE427B6E92181A99B07A76464742ABF3ABAC6` |
| `dist/ps-access-gate-2.1.2.zip` | Canonical Terms link, accessible dialog/focus behavior, native exit link, responsive logo | `3DEAE92CECD05972EDD551B5790C2EE7C6EE70776051983A51C703047C4E587E` |
| `dist/pepselect-coa-archive-0.7.1.zip` | Exact published-product links from the Testing archive and compound-history heroes | `D458B1997D43BC959559EB17F9EBEDED2DB491EC137117CD6A9110F71544BD6F` |
| `dist/pepselect-child-0.25.0-beta.18.zip` | Right-sized versions of the two audited homepage evidence images, built on the verified beta.17 package | `73E8B655F1835412A029454E102F0D8EC90BE45565F3B1B15FDF74FC1ECAD7A4` |
| `dist/pepselect-child-0.25.0-beta.19.zip` | First hero/render-path candidate; installed so its generated output could be verified rather than assumed | `2CF4A848B4B9B2C07FC0E236C89B702069100978825D6E89DD801B55687520A1` |
| `dist/pepselect-child-0.25.0-beta.20.zip` | Corrected hero selection and Testing/posts-page asset condition; final active staging candidate | `056A3D5A7554B972F4E7ED3294E333D7DD6950320520EBE56AC49EBD48A12172` |

Active staging state after cache clearing: child theme `0.25.0-beta.20`, PS Access Gate `2.1.2`, COA Archive `0.7.1`.

## Mobile-performance candidate status

`dist/pepselect-child-0.25.0-beta.19.zip` was deployed first. SHA-256: `2CF4A848B4B9B2C07FC0E236C89B702069100978825D6E89DD801B55687520A1`.

Post-deployment HTML verification found that beta.19 did not select the WebP source because the saved attachment filename did not satisfy its exact match. It also did not classify Testing correctly because WordPress serves that URL as the configured posts page. The other three audited templates did remove the targeted head assets; the late WooCommerce block stylesheet remains near the footer and is not being forced out because it is outside the render-blocking head chain and may support cart behavior.

`dist/pepselect-child-0.25.0-beta.20.zip` corrects only those two output conditions and is now deployed and active on staging. SHA-256: `056A3D5A7554B972F4E7ED3294E333D7DD6950320520EBE56AC49EBD48A12172`.

## Verified outcomes

- `/terms-of-service/` returns one Pep Select 301 to `/terms-conditions/`; the destination returns 200.
- Gate HTML links directly to `/terms-conditions/` and contains the required dialog description, attestation control relationship, background inerting, focus logic, native exit link, and responsive medium logo markup.
- The access-gate automated accessibility test passes.
- The five corrected DOI citations render on Glutathione, NAD+, SS-31, and MOTS-c product pages; no `[VERIFY DOI]` placeholder remains in those responses.
- `/testing/` renders eight reverse links, one for every archive compound with an exact published WooCommerce product match.
- A representative compound-history page renders the same exact product link.
- Archive links wrap correctly at a 390 px mobile viewport and render without disturbing the desktop grid.
- The two audited evidence images were reduced from 421,930 bytes to 96,112 bytes combined (77.2%) without changing their content or layout.
- The homepage now emits responsive 320–2048 px WebP hero sources while retaining the Media Library PNG as the fallback. At a verified 390 px viewport it selects the 480 px WebP (14,926 bytes) instead of the previous 600 px PNG (236,541 bytes), a 93.7% transfer reduction. The 1024 px comparison is 46,948 bytes versus 623,780 bytes, a 92.5% reduction.
- Head styles decreased from 48→43 on Home, 41→35 on Shop, 47→41 on the representative product, and 32→27 on Testing.
- Non-deferred head scripts decreased from 8→4 on Home and 7→4 on Shop, Product, and Testing. The remaining WooCommerce block stylesheet appears after `</head>` and was deliberately retained to protect cart behavior.
- A real 390 px browser check shows no horizontal overflow on Home, Shop, Product, Testing, Cart, My Account, or Checkout. The hero artwork and layout remain visually intact.
- The Shop exposes 14 product destinations; Testing exposes eight archive cards and eight exact product links; the representative out-of-stock product retains its notifier; the existing cart line and checkout/payment shell remain present.
- Home, Shop, representative product, Cart, My Account, Testing, and a representative batch route return 200.
- Empty Checkout redirects to Cart as expected.
- The legacy NAD QR route still redirects to the canonical batch page.

## Known limitations and next gates

- Google PageSpeed API returned daily-quota exhaustion during the post-release measurement attempt. Do not claim a new PSI score until the quota resets and the four-template mobile checks are repeated.
- The hero WebP and first confirmed-unused head-asset cleanup are deployed and verified. They remain measurement-pending rather than score-complete until the four PageSpeed mobile reruns can be performed.
- `VIS-01` and `SXO-01` remain open because changing the required gate fields or its full-screen behavior needs Paulo's compliance decision. This release preserves the gate requirements.
- A clean visitor session now confirms accessible dialog naming, initial focus, background inerting, scroll lock, and both focus-wrap boundaries. The automation surface cannot substitute for a complete human screen-reader walkthrough, so that limitation remains explicit.
- No Search Console validation or indexing request should be started until the relevant changes reach Live and are publicly crawlable.

## Rollback

1. Restore Kinsta staging backup `Before Claude SEO remediation batch 1 - 2026-08-18` for a complete rollback.
2. For code-only rollback, reinstall the prior verified child-theme package `dist/pepselect-child-0.25.0-beta.14.zip`, COA Archive `0.7.0`, and the pre-release access-gate package/settings as applicable.
3. Clear WordPress/Kinsta caches.
4. Repeat Terms, product, archive, Cart, Checkout entry, My Account, and QR-route smoke checks.

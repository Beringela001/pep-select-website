# Live Buy 4 Get 1 authority 1.8.0 release — 2026-08-28

## Outcome

Pep Select BOGO Cart Experience 1.8.0 is active on Live. The plugin is now the
single authority for Buy 4 Get 1 status, eligible compounds, pricing, and cart
messaging. YITH rule 1209 is inactive.

The live plugin rule is enabled for:

- GLP-3 R 10 mg (`GLP3R10`)
- GLP-3 R 20 mg (`GLP3R20`)
- GLP-2T 20 mg (`GLP2T20`)
- GLP-1 S 10 mg (`GLP1S10`)
- MOTS-C 10 mg (`MOTSC10`)
- GHK-CU 50 mg (`GHKCU50`)

WooCommerce > Buy 4 Get 1 and the authenticated
`/wp-json/pepselect-bogo/v1/buy-four-get-one` endpoint use the same versioned
rule. The endpoint supports revision-checked updates for the future Ops mapping.

## Release controls

- Removed the oldest manual backup, `Before SEO image optimization beta62 live - 2026-08-28`, after confirming it was the bottom/oldest entry.
- Created rollback backup: `Before BOGO authority 1.8.0 live - 2026-08-29`.
- Package: `dist/pepselect-bogo-quantity-1.8.0.zip` (not committed).
- SHA-256: `91ADDF6C0E343DDAA1F630E9A1983B459EE39D42F79BF64573F2ED8017592B57`.
- Implementation commit: `9411e0790af59c533da01234533d61f546270c07`.

## Verification

- PHP lint passed for the plugin entry point and new rule class.
- Buy 4 Get 1 behavior tests passed.
- Compound-discount regression tests passed.
- Front-end/static and performance-asset checks passed.
- WordPress reported the plugin active at version 1.8.0.
- YITH rule 1209 remained `Inactive` after reload.
- The plugin rule remained enabled with all six selected compounds.
- Live GHK-CU at physical quantity 5 showed `1 free vial added`, a `$33.99`
  automatic discount against a `$169.95` subtotal, and no removable `x` control.
- The public Ops-route probe returned protected `rest_forbidden` / HTTP 401,
  confirming the route is registered and requires authenticated WooCommerce
  management permission.

## Rollback

Restore the named Kinsta backup. For a package-only rollback, reinstall
`dist/pepselect-bogo-quantity-1.7.1.zip`; because 1.7.1 delegates pricing to
YITH, rule 1209 must then be re-enabled before the promotion is offered again.

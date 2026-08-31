# Cart Discounts 2.0.1 Release — 2026-08-30

## Outcome

Pep Select Cart Discounts 2.0.1 was deployed to Staging and Live on 2026-08-30. Staging was validated before Live. Existing rule state was preserved and no sitewide discount was created or activated.

## Release artifact

- Package: `dist/pepselect-bogo-quantity-2.0.1.zip`
- SHA-256: `D67B8AF5A29D9E848001ABE8EA1445A7AF34058929BB064D1F9290F8CD3A16D6`
- Archive shape: one top-level `pepselect-bogo-quantity/` directory
- Git branch: `codex/seo-m4-content`
- Main implementation commit: `e0903de`
- Ops catalog commit: `9f3095d`
- Mobile containment commit: `b35b895`
- 2.0.1 version/cache-bust commit: `6ce2faf`

## Backups

### Staging

- Deleted the confirmed oldest manual backup: `Before Pass 1 platform maintenance staging - 2026-08-29`
- Created: `Before Cart Discounts 2.0.0 staging - 2026-08-30`
- The backup was created before the first Cart Discounts deployment and remains the rollback point for the final 2.0.1 Staging state. The note retains the initial package version because 2.0.1 was the cache-busting follow-up found during Staging QA.

### Live

- Deleted the confirmed oldest manual backup: `Before Google address autocomplete 0.2.4 live - 2026-08-30`
- Created: `Before Cart Discounts 2.0.1 live - 2026-08-30`

Deleted Kinsta manual backups are permanent and cannot be recovered.

## Automated validation

- BOGO cart experience contract passed.
- Performance asset safeguards passed.
- BOGO rule PHP behavior passed.
- Compound discount PHP behavior passed.
- Sitewide discount PHP behavior passed.
- Stackable/exclusive coordination PHP behavior passed.
- Plugin PHP syntax passed.
- Git whitespace validation passed.
- Archive structure and SHA-256 were verified.

## Staging verification

- Plugin active as Pep Select Cart Discounts 2.0.1.
- Overview, Buy 4 Get 1, Compound Discounts, and Sitewide Discounts pages loaded.
- Existing Staging BOGO switch remained off.
- Existing Staging BOGO selection remained six products: GLP3R10, GLP3R20, GLP2T20, GLP1S10, MOTSC10, and GHKCU50.
- Compound rule collection remained empty.
- Sitewide rule collection remained empty.
- Sitewide customer targeting, stacking, and 24-character customer-label controls loaded.
- GLP-3 R product page loaded without a fatal error and correctly showed no BOGO pill while the Staging BOGO switch was off.
- At 390px, the Sitewide Discounts admin page had no horizontal overflow; the hidden Select2 control was contained to one pixel and the visible replacement remained usable.
- Unauthenticated Sitewide Discounts REST request returned HTTP 401.

## Live verification

- Plugin active as Pep Select Cart Discounts 2.0.1.
- Live BOGO remained enabled and stackable for GLP3R10, GLP3R20, and GLP3R30.
- The matching YITH `Buy 4 get 1 free` dynamic rule remained inactive.
- Existing compound rule `GHK+NAD DUO` remained active, 20% off, one eligible item, two compounds, and stackable.
- Sitewide rule collection remained empty.
- GLP-3 R 10MG product page displayed the `Buy 4 get 1 free` pill.
- The product page and pill had no horizontal overflow at 390px.
- Anonymous cart smoke test retained the literal physical quantity of 8, displayed `1 free vial added`, and applied one $79.99 `buy 4 get 1 free` discount.
- The automatic discount label appeared without a customer-removable `x`.
- Cart and product pages loaded without a fatal or critical error.
- Live Sitewide Discounts admin had no horizontal overflow at 390px and loaded the 2.0.1 stylesheet.
- Unauthenticated Sitewide Discounts REST request returned HTTP 401.

## Preserved production state

- No sitewide promotion was activated.
- No customer list or customer audience was changed.
- No BOGO product selection, stacking selection, or switch was changed.
- No compound discount value, minimum, selection, label, or activation was changed.
- No YITH rule was activated.

## Ops handoff

The living website-to-Ops contract is `docs/OPS-CONTROL-SURFACE-CATALOG.md`. It catalogs all custom plugins, exact REST routes and hooks, stored state, current gaps, security boundaries, and the recommended Ops implementation order.

## Rollback

If rollback is required, restore the environment-specific Kinsta backup above. After restoration, verify the active plugin version, BOGO rule state, YITH rule status, compound rule collection, product pill, and a five-or-more-vial cart before reopening the environment.

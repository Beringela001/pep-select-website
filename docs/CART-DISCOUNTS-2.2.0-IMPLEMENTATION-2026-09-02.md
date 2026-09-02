# Cart Discounts 2.2.0 implementation — 2026-09-02

Pep Select Cart Discounts 2.2.0 adds rule-specific storefront sale colors and reorganizes the WordPress administration around the established Pep Select Popups pattern.

## Delivered

- Shared top-card navigation for Buy 4 Get 1, Compound Discounts, and Sitewide Discounts, including active-rule status.
- Sitewide live preview using Pep Select product-page and product-card surfaces.
- Product/Card and Desktop/Mobile preview controls.
- Independent `% off`, crossed-out price, and sale-price color controls on every sitewide rule.
- Storefront price markup consumes the three saved colors through scoped CSS variables.
- Existing rules receive the current approved colors automatically; discount calculations, exclusions, audiences, stacking, coupons, and order accounting are unchanged.
- The authenticated schema-version-1 collection remains backward compatible and now exposes all three optional color fields plus the `sale_colors` contract declaration.

## Release verification

- Package: `dist/pepselect-bogo-quantity-2.2.0.zip` (not committed)
- SHA-256: `147A774DDFE0E5DC0ADCFF8F2A33F568462296D1E0BE0F0D3A83DAEE40A34160`
- Staging and Live both report plugin version 2.2.0.
- Staging validated before Live: shared navigation, three color controls, live preview, Product/Card switching, Desktop/Mobile switching, and active LABORDAY20 storefront pricing all passed.
- Live LABORDAY20 is active at 20%, no minimum, everyone, exclusive, with one excluded product.
- Live GHK-CU displays `$33.99`, `20% off`, and `$27.19`; the three scoped color values are present in the storefront markup.
- Live Bacteriostatic Water remains `$19.99` with no sitewide wrapper on its own product price. Eligible related-product cards continue to show their discounts.
- Kinsta caches were cleared after deployment.

## Rollback

- Staging backup: `Before Cart Discounts 2.2.0 staging - 2026-09-02`
- Live backup: `Before Cart Discounts 2.2.0 live - 2026-09-02`
- To make room, only the verified oldest manual backups were deleted: `Before Cart Recovery 0.3.0 staging - 2026-08-30` and `Before Trustpilot email exclusions 0.3.0 - 2026-09-01`.

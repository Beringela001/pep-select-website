# Cart Discounts 2.2.0 implementation — 2026-09-02

Pep Select Cart Discounts 2.2.0 adds rule-specific storefront sale colors and reorganizes the WordPress administration around the established Pep Select Popups pattern.

## Delivered locally

- Shared top-card navigation for Buy 4 Get 1, Compound Discounts, and Sitewide Discounts, including active-rule status.
- Sitewide live preview using Pep Select product-page and product-card surfaces.
- Product/Card and Desktop/Mobile preview controls.
- Independent `% off`, crossed-out price, and sale-price color controls on every sitewide rule.
- Storefront price markup consumes the three saved colors through scoped CSS variables.
- Existing rules receive the current approved colors automatically; discount calculations, exclusions, audiences, stacking, coupons, and order accounting are unchanged.
- The authenticated schema-version-1 collection remains backward compatible and now exposes all three optional color fields plus the `sale_colors` contract declaration.

## Release state

Implementation is local and not deployed. Stage before Live when deployment is explicitly authorized for this version.

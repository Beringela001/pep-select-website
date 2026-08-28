# Schema Findings — pepselect.com — 2026-08-28

**Specialist:** seo-schema (hit 15-turn limit; reported from collected data). **Pages:** `/`, `/shop/`, `/product/bpc157-10/`, `/product/pt-141/`. **Tool note:** `schema_ecommerce_validate.py` raised `AttributeError` on Yoast's list-form `priceSpecification` — plugin bug, not a site defect; manual review used. Corroborated by GSC URL Inspection rich-result warnings (see google-field-data.md).

## Schema score: 71 / 100 (8/23: 70)

## Detected (all valid JSON-LD, `https://schema.org`)
- Home & `/shop/`: `WebPage`, `WebSite` (+`SearchAction`), `BreadcrumbList`, `OnlineStore` with `MerchantReturnPolicy` (`MerchantReturnNotPermitted`, link to `/refund-shipping-policy/`), logo `ImageObject`.
- Products: `Product` with `name`, `url`, `description`, `image`, `sku`, `brand`, `offers[Offer]` (`price`, `priceCurrency`, `availability` correctly differentiated — BPC-157 `OutOfStock`, PT-141 `InStock`), `seller` → `#organization`, `hasMerchantReturnPolicy`.

## Findings
**Critical:** none. No HowTo; no FAQPage anywhere (SCHEMA-08 correct).

**High**
- No `aggregateRating`/`review` (ECOM-01, SCHEMA-06) — not fabricated; add only with genuine reviews. GSC warns on both inspected products.
- No `OfferShippingDetails` (SCHEMA-06/ECOM-06) — required for merchant-listing shipping badge. GSC warns. Biggest actionable gap; snippet below.

**Medium**
- No `gtin`/`mpn` (DFS-07). GSC: "missing global identifier".
- `/shop/` has no `ItemList`/`CollectionPage` (ECOM-08).
- `OnlineStore` has no `sameAs` (SCHEMA-04/GEO-04).
- `telephone`/`contactPoint` absent although the number is on-page (SCHEMA-05/11) — orchestrator verified `"telephone"` count 0 on `/` and `/contact/`.
- **SCHEMA-13 (NEW):** `og:type="article"` on `/shop/` and PDPs (should be `website`/`product`).

**Info**
- `MerchantReturnNotPermitted` is accurate markup for the stated policy; forfeits return-policy rich result. Confirm it still matches `/refund-shipping-policy/` after the 8/27 shipping-scope update.
- GSC also lists `validFrom` and `hasMerchantReturnPolicy` as missing on glp3-r30 despite the Organization-level policy — verify the per-Offer reference resolves.

## Ready-to-paste — `OfferShippingDetails` (values are structural placeholders; replace with the live policy before deploy)
```json
"shippingDetails": {
  "@type": "OfferShippingDetails",
  "shippingRate": {"@type": "MonetaryAmount", "value": "0.00", "currency": "USD"},
  "shippingDestination": {"@type": "DefinedRegion", "addressCountry": "US"},
  "deliveryTime": {
    "@type": "ShippingDeliveryTime",
    "handlingTime": {"@type": "QuantitativeValue", "minValue": 1, "maxValue": 2, "unitCode": "DAY"},
    "transitTime": {"@type": "QuantitativeValue", "minValue": 2, "maxValue": 5, "unitCode": "DAY"}
  }
}
```
Note: shipping is now contiguous-US + AK/HI/PR only (8/27 releases); `shippingDestination` should reflect the real scope, not a bare `US`.

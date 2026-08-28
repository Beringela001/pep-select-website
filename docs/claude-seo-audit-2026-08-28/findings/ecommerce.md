# E-commerce Findings — pepselect.com — 2026-08-28

**Specialist:** seo-ecommerce (hit 20-turn limit; static on-page analysis via `fetch_page.py` + `parse_html.py`; no DataForSEO credentials → marketplace/pricing data skipped). Orchestrator verified COA coverage, OOS state, and variant duplication on all 17 products. Evidence: `../raw-crawl/product_parsed.json`, `shop_parsed.json`, `oos-glp2-t20.html`.

## Catalog state (17 products)
- **Out of stock: 10 / 17** — glutathione, glp3-r20, glp1-s10, glp2-t20, bpc157-10, cag10, kpv10, motsc-10, ss-31, glp3-r30 (8/23: 7/17). ECOM-05 worsened; back-in-stock emails now exist (8/26 release).
- **COA path: 14 / 17** products link to a `/testing/` hub or batch report (8/23: 8/17). Missing: `glp1-s10`, `glp2-t20`, `bacteriostatic-water-30ml`. Hubs 8→15, batch reports 9→18. **ECOM-02 → PARTIALLY FIXED.**
- **OOS COA path (ECOM-10) → VERIFIED FIXED:** `/product/glp3-r20/` (OOS) links `/testing/retatrutide-20mg/`.

## Findings
**Critical (specialist rating; ledger ECOM-09 Low preserved)**
- **Merchant Center eligibility risk:** injectable research peptides with RUO framing are a known restricted/suspension category regardless of labelling; `MerchantReturnNotPermitted` compounds it. Live account status could not be verified. Audit policy fit before any Shopping spend (ECOM-09, DFS-07).

**High**
- No `ItemList`/`Product` markup on `/shop/` (ECOM-08).
- Dose variants as separate canonical URLs (`glp3-r10/20/30`) instead of one variable product — splits equity/reviews; body descriptions still share paragraphs (CONT-15/ECOM-11 PARTIALLY FIXED — meta differentiated only). Consolidation is a business-logic change (NEEDS APPROVAL).
- OOS pages remain `index, follow` with price rendered (correct practice); `Offer.availability` on OOS pages verified `OutOfStock` for bpc157-10 by the schema specialist; not verified for the other 9 `[VERIFY CLAIM]`.

**Medium**
- `og:type="article"` on PDPs and `/shop/` (SCHEMA-13, NEW).
- No faceted/filter URLs found (good — no facet bloat) but also no crawlable subcategories; `/shop/` is the only catalog page (ECOM-08 context).
- Product schema minimal: no `gtin`/`mpn`, `aggregateRating`, `priceValidUntil`, `shippingDetails`; single image despite gallery (SCHEMA-06/ECOM-06).

**Low**
- Shop-page `<h3>` "KPV" flagged as ambiguous heading — minor.
- Bacteriostatic Water: orphan (MAP-01) and no description (ECOM-12) — not re-verified this pass.

**Positive**
- Batch-specific COA linking works well where present (e.g. `/testing/tb-500-10-mg/tb10-6926/` with purity %, lab, test date) — replicate on the 3 remaining products.

## Top fixes
1. Consolidate dose variants into single variable products (NEEDS APPROVAL; rollback package).
2. `ItemList` + per-item `Product` on `/shop/`.
3. Merchant Center policy audit before Shopping spend.
4. Verify `Offer.availability` on all 10 OOS products.
5. Enrich Product schema (`gtin`/`mpn` if real, `priceValidUntil`, multiple `image`, `shippingDetails`); fix `og:type`.

**Unverified (turn limit):** live Merchant Center status; availability schema on 9 OOS pages; filter parameters beyond static HTML; marketplace pricing (no DataForSEO).

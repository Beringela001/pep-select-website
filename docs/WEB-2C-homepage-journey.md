# WEB-2C Homepage Visitor Journey

## Current direction

The beta.2 product-first implementation remains the technical and architectural foundation. Beta.3 corrects its emotionally flat, instructional hero and makes "What's Behind the Label Matters" the homepage emotional anchor. Products, pricing, and catalog access remain near the top; detailed Quality Archive material remains concentrated later on the page.

The homepage remains an administrator-only preview pending Paulo's visual approval. Orbitrex informed only high-level commercial principles such as an immersive hero, early products, alternating visual environments, compact FAQ, and concentrated credibility. No competitor copy, graphics, assets, statistics, branded terminology, or exact composition is reproduced.

## Objective and visitor

Help a research purchaser recognize Pep Select as a research-compound storefront, see current product data, and reach the catalog quickly. Visitors who want deeper evidence retain a clear path to the Pep Select Quality Archive.

The primary visitor values clear product information, current pricing, availability, batch documentation, and a professional ordering experience. The page does not address patients, consumers, personal experimenters, or people seeking health or performance outcomes.

## Conversion hierarchy

- Primary action: `Explore the Lineup`
- Strongest secondary action: `See the Receipts`, with accessible Quality Archive context
- Supporting actions: individual `View Compound` links, `Review Batch Records`, `Open the Quality Archive`, and `Read All FAQs`

## Product-first section order

| Order | Section | Purpose | Dynamic or verified source |
|---|---|---|---|
| 1 | Product-first hero | Identify the category, show real products and current commerce data, and present the primary catalog action. | Up to three eligible WooCommerce products with real images; canonical title, price, stock state, product URL, Shop URL, and `/testing/`. |
| 2 | Confidence strip | Reinforce four factual capabilities without unsupported metrics. | Approved labels only: live catalog pricing, current availability, batch records when available, and direct support. |
| 3 | Featured compounds | Put four current products near the top for ready-to-browse visitors. | Published, visible, purchasable, in-stock WooCommerce products; Featured first, then latest eligible products. |
| 4 | Why Pep Select | Explain the focused catalog and documentation path without turning proof into every section's subject. | Approved copy plus one available WooCommerce product image; no invented standards or coverage claims. |
| 5 | Batch identity | Connect a catalog product visually to the identifier concept without presenting invented record values. | Product image when available; explanatory identifier labels and neutral availability text only. |
| 6 | Quality Archive | Concentrate the traceability mission and route evidence-first visitors to the archive. | Canonical `/testing/` route and source-verified archive capabilities; no homepage record query. |
| 7 | FAQ | Address supported pre-order questions in a compact accessible format. | Three supported items derived from Elementor Homepage #571; obsolete order-link content excluded. |
| 8 | Final CTA | Repeat the catalog-first decision path and the archive alternative. | Canonical Shop and `/testing/` URLs; no new claim. |

## Image and product rules

- Use only WooCommerce product-record images; never hard-code a product ID, name, price, stock state, or image URL.
- Prefer Featured products, then fill with other valid in-stock products.
- The hero may show up to three products with images. The first likely-LCP image loads eagerly; other and below-fold images load lazily through WordPress responsive image markup.
- Missing hero imagery produces a branded image-ready state. Missing card imagery produces a bounded Pep Select fallback, not an invented vial.
- Catalog images must not be presented as exact batch-packaging evidence.

## Evidence and compliance boundary

The Quality Archive owns COA visibility, public terminology, current/history selection, sorting, records, and routes. Its stable 0.4.0 source exposes no supported generic homepage-preview interface, so beta.3 provides archive navigation rather than duplicating plugin logic or inventing records.

The page makes no universal-testing, purity, quality-guarantee, comparative, health, medical, dosing, administration, or human-use claims. Current WooCommerce prices and stock states may appear only as live product data.

## Responsive priority

On mobile, the hero copy appears before product imagery; CTAs remain full-width and comfortable; the confidence strip becomes two columns; featured products use a readable horizontal rail; editorial and identity compositions stack; the FAQ remains keyboard operable; and no page content may alter the coded header or create body-level horizontal overflow.

## Acceptance criteria

- The server-side `?pepselect_home_preview=1` gate remains front-page-only and requires `manage_options`.
- Normal and unauthorized requests retain the Elementor homepage.
- The coded WEB-2B header and footer remain unchanged and isolated.
- One H1 and a logical heading order are present.
- Products appear before the Quality Archive feature.
- Product data and imagery remain WooCommerce-owned and dynamic.
- FAQ content comes from an established source and excludes obsolete ordering instructions.
- COA logic, status mapping, sorting, and records are not duplicated.
- Desktop, tablet, mobile, keyboard, reduced-motion, and real-data behavior require visual review on Staging before publication.

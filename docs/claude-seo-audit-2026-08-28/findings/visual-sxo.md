# Visual & SXO Findings — pepselect.com — 2026-08-28

**Specialists:** seo-visual (`capture_screenshot.py`, `analyze_visual.py`; home + `/product/ghk-cu/`, desktop 1920×1080 / mobile 375×812; screenshots in `../screenshots/`) and seo-sxo (`render_page.py`, `parse_html.py`, WebSearch on 4 queries). Read-only; the gate was not dismissed.

## Visual

**Critical**
- **VIS-01 / SXO-01 (STILL OPEN):** the "Welcome / 21+ research use only" modal with dark backdrop hides 100% of above-the-fold content on home and PDP, desktop and mobile: H1, hero, CTAs ("Explore Our Selection" / "See the Receipts"), and on the PDP the price, Add to Cart, and "View batch report" link. Gate markup: `id="psag-gate" role="dialog" aria-modal="true" aria-labelledby="psag-title" aria-describedby="psag-intro"`; form token `PS-RUO-2026.08`. A11y sub-layer holds (VIS-02).

**Medium**
- Promo bar + logo header + nav consume ~215 px desktop / ~290 px mobile before hero content; on mobile the PDP H1 ("GHK-CU") sits below the first viewport even after gate dismissal.
- Trust signals (FDA disclaimer, T&C, batch/COA) surface only inside the gate or as a small "View batch report" link — no persistent trust strip post-gate.

**Low / positive**
- No horizontal overflow; tap targets ≥48 px; base font 16 px; no overlap/text overflow detected.
- "Remember me for 30 days" pre-checked — mitigates repeat friction.
- Mobile nav collapses correctly; cart/account icons reachable.
- **VIS-08 (NEW, Low):** PSI colour-contrast failure on home.

**Fixes (NEEDS APPROVAL — gate is a compliance decision):** lighter bottom-sheet/banner gate; shrink header stack on mobile; persistent trust strip (COA/lab-tested, RUO, review count) in hero/PDP summary.

## SXO

**Primary finding — visibility mismatch, not page-type mismatch (High).** SERPs for "research peptides", "buy research peptides with COA", "TB-500 peptide for sale" are 100% vendor/product pages (American Peptides, Nationwide Peptides, Core Peptides, Oath Research, Eternal Peptides, Elite Research Lab, BioEdge, Bluumm). Pep Select's WooCommerce templates are the correct type; the site simply does not appear in any of the four SERPs, including "is pepselect legit reddit" (DFS-03/05, GEO-05).

**Trust-signal gap (High).** Zero brand mentions for the "legit" query. Competitors surface Trustpilot pages; Pep Select has no `Review`/`AggregateRating` and no indexed third-party footprint (ECOM-01, GEO-05).

**Commoditised messaging (Medium) — SXO-10 (NEW).** "≥99% purity", "third-party tested", "USA-made", "COA per batch" appear in 8+ competitor titles/meta. Pep Select's stronger, real differentiator (batch-matched, dated COA, named lab ILS Labs, live Quality Archive) is not in its title tags/snippets ("… for Research | Pep Select").

**User stories (from SERP signals)**
1. Reorder verification — confirm the COA for the exact batch about to be reordered.
2. Legitimacy proof before payment — independent third-party evidence the vendor is real.
3. Side-by-side spec comparison — purity %, price/mg, origin at a glance.

**Persona scores (25 pts × Relevance/Clarity/Trust/Action)**
| Persona | Home | PDP (TB-500) |
|---|---|---|
| Researcher buyer | 73 | 82 |
| First-time legitimacy checker | 64 | 62 |
| Comparison shopper | 54 | **47** |

Weakest: comparison shopper on PDP — no price-per-mg, no stack cross-sell (TB-500 + BPC-157), no comparison table; "Only 3 left in stock" reads as manipulative to a sceptical visitor (SXO-04).

**Fixes:** (1) real reviews + schema (Phase 3); (2) front-load COA/lab/purity into titles/meta — SXO-10 (NEEDS APPROVAL, copy); (3) purity/price-per-mg module; (4) off-site presence (Trustpilot, directories); (5) related-stack cross-sells.

**Limitations:** SERP snapshot is point-in-time WebSearch; no rank tracking; no DA data.

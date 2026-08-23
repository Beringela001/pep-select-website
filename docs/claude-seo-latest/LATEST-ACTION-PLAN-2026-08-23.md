# Action Plan — Claude SEO Audit 2026-08-23

Phased plan for the 46 still-open + 14 new findings, using the bridge's three labels:

- **[CODE-READY]** — implementable now against a known file/surface, no business input, no fabrication.
- **[NEEDS APPROVAL]** — code can support it, but scope/UX/business direction needs Paulo's sign-off.
- **[BLOCKED — REAL EVIDENCE NEEDED]** — cannot proceed without real records, credentials, accounts, or an owner decision; never fabricate.

This run is **measurement only** — nothing here was implemented. All items map to the source findings in `PRIOR-FINDINGS-VERIFICATION-LEDGER.md` and `findings/*.md`.

---

## Phase 1 — [CODE-READY] quick wins (no business input, low risk)

| Item | Findings | Surface | Notes |
|---|---|---|---|
| Security headers at the edge | TECH-01, TECH-03, GOOG-08 | Cloudflare/Kinsta (no WP code) | Ship HSTS **without** `preload` first; add `X-Frame-Options: SAMEORIGIN`. Verify no subdomain breakage, then `preload`. |
| CSP (report-only first) | TECH-02, TECH-03 | Edge | `Content-Security-Policy-Report-Only` for ≥1 release cycle before enforcing — GTM/Elementor/YITH/side-cart AJAX will otherwise break. |
| Referrer-Policy + Permissions-Policy | TECH-04 | Edge | `strict-origin-when-cross-origin` + a minimal Permissions-Policy. |
| Phone → JSON-LD | **SCHEMA-11**, SCHEMA-05 | child theme `inc/seo-catalog.php` or `seo-semantics.php` | The verbatim number (+1 833 737-7528) is already on-page; add `telephone` + `contactPoint` to Organization/ContactPage. No new claim. |
| Dataset on compound hubs | GEO-07 | child theme (extend the proven batch-report pattern) | 8 hub pages; 100% real backing data already displayed as plain text. |
| Bacteriostatic Water crawlable link | MAP-01 | a real `<a href>` from a product page or FAQ | Closes the orphan; the Shop-grid merchandising decision is separate and not required. |
| OOS COA path restore | **ECOM-10** | product template | Retatrutide 20MG's COA widget + hub link vanish when OOS though the hub exists — keep the link rendered regardless of stock. |
| Retatrutide description de-dup | **CONT-15**, ECOM-11 | product content | glp3-r10/r20/r30 are byte-identical — differentiate by strength/context to remove duplicate-content risk across indexed pages. |
| Username-leak hygiene | GEO-08 | guide meta / Person node | Suppress or replace `beringela001` in `meta[name=author]` + Twitter-card. |
| Brand-name standardization | CONT-13 | 4 legal pages | Find-and-replace "PepSelect"→"Pep Select" (Terms 12, Privacy 3, Refund 1 instances). |
| DOI hyperlinks | CONT-06 | GLP-2T product copy | Make the 3 plain DOI strings real links. |

## Phase 2 — [NEEDS APPROVAL] (code exists; direction needed)

| Item | Findings | Decision required |
|---|---|---|
| **Research-gate blocking behavior** | VIS-01, SXO-01, SXO-08 | The single highest-impact open item. Gate blocks 100% of first paint on every page/device. The a11y sub-layer is now fixed (VIS-02); the *blocking* is a compliance/attestation decision — timing/scoping change needs Paulo's sign-off. Do **not** alter blocking without it. |
| Thin-content program | CONT-03, CONT-04, ECOM-07, GEO-03 | Extend the guide's evidence-led approach to the 17 templated product descriptions and lengthen citable passages toward the ~150-word benchmark. Scope/effort decision; Bac Water needs any description at all (ECOM-12). |
| Gate exit destination + font | VIS-03, VIS-04 | On-site low-friction alternative to the Google exit; raise gate font to ≥16px. Both touch the gate — bundle with the gate decision. |
| Homepage CLS watch | PERF-12 | Re-measure `/` mobile 3× before/after any homepage change; decide whether the batch-matching label/image needs explicit sizing. |
| Second FDA disclaimer | CONT-07 | Two differently-worded disclaimers co-occur on every product page — decide the canonical one (compliance review, do not rewrite unilaterally). |
| llms.txt / AI-crawler policy | GEO-09, GEO-10, GEO-01 | Named AI-crawler tokens in robots.txt and RSL/licensing are policy decisions, not defects. |

## Phase 3 — [BLOCKED — REAL EVIDENCE NEEDED] (do not fabricate)

| Item | Findings | What's needed |
|---|---|---|
| Reviews / ratings | ECOM-01 | Real customer reviews — never fabricated aggregateRating. |
| 9 missing COA hubs | ECOM-02 | Real lab records for glp1-s10, glutathione, glp2-t20, bpc157-10, motsc-10, ss-31, bac-water, **KPV, Cagrilintide**. |
| Out-of-stock substitutes | ECOM-05 | Merchandising/business decision on substitute surfacing for 7 OOS SKUs. |
| Street/mailing address | CONT-16, SCHEMA-05 | A real address to complete NAP (phone now exists). |
| sameAs / social profiles | GEO-04, GEO-05, SCHEMA-04 | Real, Paulo-approved off-site identity profiles (strongest AI-citation signal, entirely absent). |
| Named author / reviewer | CONT-08, GEO-08 | A real credentialed byline for the guide. |
| GA4 access | GOOG-11 | Grant the authenticated identity **Viewer** on GA4 property 549907385 (GA4 Admin → Property Access Management). One grant from measurable. |
| Merchant Center / product feed | ECOM-09, DFS-07 | Owner eligibility + a real product feed decision. |
| Backlinks / off-site authority | DFS-01, DFS-02, DFS-03, DFS-05 | Real off-site outreach; backlink re-measurement needs paid tooling (out of this run's scope). |

## Phase 4 — [MONITOR] (no code fix exists)

- **CrUX field-data eligibility** (GOOG-09) — purely a function of real-user Chrome traffic growing over time.
- **Organic visibility** (GOOG-03, DFS-01) — watch GSC for the second non-zero-click week; `/about-us/` still drawing impressions despite noindex (monitor for drop).
- **Paid-tool re-measures** (DFS-02/04/06) — backlink profile, keyword difficulty, AI-Overview citation; only if Paulo authorizes DataForSEO/Ahrefs spend.

---

**Sequencing recommendation:** Phase 1 security headers + SCHEMA-11 + GEO-07 + MAP-01 + ECOM-10 are the highest value-to-effort and carry no business dependency. The gate decision (Phase 2) is the single largest lever but is Paulo's call. Everything in Phase 3 is genuinely blocked and must not be closed with fabricated data.

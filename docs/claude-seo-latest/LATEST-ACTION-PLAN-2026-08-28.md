# Action Plan — Claude SEO Audit 2026-08-28

Phased plan for the 50 still-open + 19 partially-fixed + 1 regressed + 8 new findings, using the bridge's three labels:

- **[CODE-READY]** — implementable now against a known file/surface, no business input, no fabrication.
- **[NEEDS APPROVAL]** — code can support it, but scope/UX/business direction needs Paulo's sign-off.
- **[BLOCKED — REAL EVIDENCE NEEDED]** — cannot proceed without real records, credentials, accounts, or an owner decision; never fabricate.

This run is **measurement only** — nothing was implemented. All items map to `PRIOR-FINDINGS-VERIFICATION-LEDGER.md` and `findings/*.md`. None of the seven 8/23 Phase-1 quick wins shipped; they are repeated here with updated evidence.

Each item carries its first-principle basis (THINK), dependency (CONNECT), failure check (ACCEPT) and leading indicator (GROW) in compact form.

---

## Phase 1 — [CODE-READY] quick wins (no business input, low risk)

| # | Item | Findings | Surface | Why / dependency / how we'd know it failed / what to watch |
|---|---|---|---|---|
| 1 | Security headers at the edge | TECH-01, TECH-03, TECH-04, GOOG-08 | Cloudflare/Kinsta (no WP code) | Checkout site with only `nosniff`; 4th cycle. HSTS without `preload` first + `X-Frame-Options: SAMEORIGIN` + `Referrer-Policy: strict-origin-when-cross-origin`. Fail: `curl -sI` still lacks HSTS. Watch: no subdomain/mixed-content errors for one release. |
| 2 | CSP Report-Only | TECH-02 | Edge | Report-Only for ≥1 release cycle; GTM/Elementor/YITH/side-cart/exit-offer AJAX will break if enforced blind. Depends on #1. Fail: report endpoint shows blocked first-party assets. |
| 3 | Remove `/order/` from sitemap | **MAP-04** | Yoast page settings / WC endpoint exclusion | Noindexed URL in sitemap. Fail: `grep -c '/order/' page-sitemap.xml` ≠ 0. Watch: GSC "Excluded by noindex" count. |
| 4 | Phone → JSON-LD | SCHEMA-11, SCHEMA-05 | child theme `inc/seo-catalog.php` / `seo-semantics.php` | Number already visible; pure wiring. Fail: `grep -c '"telephone"'` on `/contact/` = 0. |
| 5 | `Dataset` on the 15 compound hubs | GEO-07 | child theme (extend batch-report pattern) | 0/3 sampled hubs carry it; real data already on page. Unblocks AI-citation of batch data. Fail: hub `Dataset` count 0. |
| 6 | Crawlable link to Bacteriostatic Water | MAP-01 | product page / FAQ `<a href>` | Orphan for 4 cycles. Fail: `/shop/` + `/faq/` still 0 anchors. Watch: URL Inspection "Referring page". |
| 7 | Differentiate Retatrutide body copy | CONT-15, ECOM-11 | product content (glp3-r10/r20/r30) | Meta done; description paragraph identical. Depends on Phase-2 variant decision — if consolidating, skip. Fail: main-content similarity stays >0.7. |
| 8 | `og:type` fix | **SCHEMA-13** | Yoast filter in child theme | `product` on PDPs, `website` on `/shop/`. Fail: `og:type` still `article`. |
| 9 | Username-leak hygiene | GEO-08 (sub-fix) | guide `meta[name=author]` / Person node | Suppress `beringela001`; real byline stays Phase 3. Fail: `grep -c beringela001` ≠ 0. |
| 10 | DOI hyperlinks + brand-name normalisation | CONT-06, CONT-13 | GLP-2T copy; Terms/Privacy/Refund | 3 DOI strings unlinked; "PepSelect" 13/4/2 instances. |
| 11 | Home-desktop regression bisect | **PERF-14**, PERF-05 | staging: toggle exit-offer script, gate bubbles | Home desktop 59 / TBT 1,770 ms vs 86–95 on other templates. Re-run 3×, then disable candidates one at a time. Fail: TBT stays >600 ms with candidates off (cause is elsewhere). Watch: weekly PSI home desktop. |
| 12 | Console errors + contrast | **TECH-09**, **VIS-08** | theme CSS / JS | Capture console in DevTools; fix the flagged contrast pair. Low. |

## Phase 2 — [NEEDS APPROVAL] (code exists; direction needed)

| Item | Findings | Decision required |
|---|---|---|
| **Research-gate blocking behaviour** | VIS-01, SXO-01, SXO-08, VIS-03/04 | Still the single largest lever: 100% of first paint hidden on every device/page. A11y sub-layer is done; the blocking is a compliance decision. Visual specialist proposes bottom-sheet/banner pattern; "remember 30 days" already limits repeat friction. Do **not** alter without sign-off. |
| **`/about-us/` index decision** | **CONT-17**, CONT-08 | Un-noindex only after the page's company/QA claims pass `.agents/product-marketing.md` review. If it stays noindex, a replacement indexed "Our process / Quality Archive" page is needed to close the E-E-A-T gap. |
| **Dose-variant consolidation** | CONT-15, ECOM-11, ECOM-07 | glp3-r10/20/30 → one variable product with a dose selector. Touches WooCommerce product/stock/COA relationships (business-logic boundary); needs rollback package. Do before soliciting reviews so they accrue to one URL. |
| **Snippet rewrite** | **SXO-10**, DFS-09 | Front-load "batch-matched COA · named lab · purity %" into product titles/meta under marketing/compliance rules. Fail: GSC product-page CTR unchanged after 4 weeks. |
| **AdSense on storefront** | **PERF-13**, PERF-06 | Confirm intent. If unintended, remove; if intended, lazy-load after interaction. |
| **Mobile render-path program** | PERF-01/02/03/07/10, GOOG-04/05/06/07, TECH-06 | Already scoped in `MOBILE-SEO-PERFORMANCE-MILESTONES-2026-08-20.md` M1–M2; not started. Lab LCP worse this cycle (shop 9.9–10.0 s). Approve M1–M2 as one staging package. |
| **Thin-content program** | CONT-03/04/05, ECOM-07, GEO-03, DFS-08 | Extend the guide's evidence-led approach to 17 templated descriptions; separate liability clauses into policy pages; passages toward ~150 words. Scope/effort decision. |
| **`/shop/` ItemList + subcategories** | ECOM-08 | Add ItemList JSON-LD; decide whether crawlable subcategories (GLP-1s, healing peptides) fit the flat-catalog choice. |
| **Comparison-shopper module** | SXO-04 | Price-per-mg, cross-sell stacks (TB-500 + BPC-157); revisit "Only 3 left" scarcity copy. |
| **IndexNow / llms.txt / AI-crawler policy** | TECH-07, GEO-01, GEO-09, GEO-10 | Policy decisions, not defects. Technical specialist rates IndexNow High; original classification (optional) preserved. |

## Phase 3 — [BLOCKED — REAL EVIDENCE NEEDED] (do not fabricate)

| Item | Findings | What's needed |
|---|---|---|
| Reviews / ratings | ECOM-01, SCHEMA-06 | Real customer reviews (Trustpilot referral already detected by URL Inspection). GSC merchant-listing warnings will clear only with genuine `aggregateRating`. |
| 3 remaining COA hubs | ECOM-02 | Real lab records for glp1-s10, glp2-t20, bacteriostatic-water. (14/17 done.) |
| Out-of-stock handling | ECOM-05 | 10/17 OOS — merchandising decision on substitutes/restock; back-in-stock emails now exist. |
| Street/mailing address | CONT-16, SCHEMA-05 | Real address for NAP. |
| sameAs / social profiles | GEO-04, GEO-05, SCHEMA-04 | Real, approved off-site profiles — strongest AI-citation and "legit" signal, entirely absent. |
| Named author / reviewer | CONT-08, GEO-08 | Real credentialed byline. |
| GA4 access | GOOG-11 | Grant Viewer on property 549907385. |
| Merchant Center / feed | ECOM-09, DFS-07 | Eligibility decision; e-commerce specialist flags suspension risk for injectable research peptides + no-returns policy. Audit policy fit before any Shopping spend. |
| Backlinks / off-site authority | DFS-01/02/03/05 | Outreach (supplier directories, digital PR, YouTube COA walkthrough). Add free Moz key for Tier-1 measurement. |
| Bulk pricing | SXO-07 | Business decision. |

## Phase 4 — [MONITOR]

- **CrUX eligibility** (GOOG-09) — traffic-dependent; instrument `web-vitals.js` → GA4 once GOOG-11 is granted.
- **Organic visibility** (GOOG-03, DFS-01) — 9 clicks / 85 impr; watch for first non-brand click and for `/about-us/` impressions to drop.
- **Lab regressions** (GOOG-05, PERF-14) — confirm with 3 runs each before treating as production issues.
- **`ps_coa_test-sitemap.xml` growth** (18 today) — ensure batch pages stay unique, not templated.
- **Drift tracking** — capture `/seo drift baseline https://pepselect.com` so the next cycle diffs automatically.

---

**Sequencing recommendation:** Phase 1 items 1–6 carry no business dependency and have been code-ready for three cycles — ship them as one edge + child-theme package. Item 11 (home-desktop bisect) is new and cheap. The gate decision and the `/about-us/` decision (Phase 2) are the two largest levers and both are Paulo's call. Phase 3 stays blocked until real evidence exists; never close those with fabricated data.

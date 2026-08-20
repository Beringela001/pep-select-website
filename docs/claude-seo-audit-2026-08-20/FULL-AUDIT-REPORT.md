# Pep Select — Deep Post-Remediation SEO Audit

**Audited:** `https://pepselect.com` (Live)
**Date:** 2026-08-20
**Tooling:** Claude SEO 2.2.4 plugin (`/seo-audit` methodology), PageSpeed Insights v5 + Lighthouse 13.x (Tier-1 authenticated API), Google Search Console API (read-only), CrUX History API, direct HTTP/JSON-LD inspection, Playwright Chromium (installed this session).
**Type:** Verification audit against the 2026-08-18 baseline (`docs/claude-seo-latest/CODEX-SEO-FINDINGS-LEDGER-2026-08-18.md`, 97 findings) and five subsequent deployment milestones (M3, M4 Batch 1/2/3-4, beta.39). This is a new, independent pass — not a summary of the prior audit.

---

## 1. Methodology and data freshness

- **Crawl:** all 5 XML sitemaps fetched live (`sitemap_index.xml` → post/page/ps_compound/ps_coa_test/product sitemaps), yielding **44 unique indexable URLs**, cross-checked against internal links found on Home/Shop/Testing. This is a small site — 44 URLs is the genuine indexable footprint, not a crawl-budget cap; the site is well under the 500-URL ceiling this audit was authorized to use. `robots.txt` was fetched and respected (no disallowed path was requested).
- **Specialist coverage:** nine parallel specialist passes — technical/crawlability, performance/CWV, schema, sitemap, content/E-E-A-T, e-commerce, GEO/AI-readiness, visual/gate-accessibility (Playwright), SXO, and Google-field-data (GSC/CrUX) — each independently re-verified its slice of the 97 prior findings with fresh evidence. Raw evidence lives under `raw-crawl/` and `raw-pagespeed/`; full per-domain detail lives under `findings/*.md`.
- **Performance data:** 20 fresh PageSpeed Insights runs (1 desktop + 3 mobile per URL) across 5 URLs: homepage, `/shop/`, `/product/nad/`, `/testing/`, and one completed COA batch report (`/testing/nad-500-mg/nd50026205jp/`). Raw JSON preserved for all 20 runs.
- **Field data:** CrUX checked at both origin and page level — still ineligible (insufficient Chrome traffic). GSC checked read-only (URL Inspection + Search Analytics); **no indexing/recrawl requests were submitted this cycle**, unlike the 8/19 checkpoint.
- **Freshness:** all evidence in this report was gathered 2026-08-20. GSC Search Analytics has its normal 2–3 day reporting lag (28-day window ends 2026-08-17). GSC URL Inspection reflects each URL's last actual Google crawl (range: 2026-08-19T19:08Z–2026-08-20T09:43Z).
- **Constraints honored:** read-only GET/HEAD only; no mutations to Live, Staging, WordPress, Kinsta, GSC, GA4, Google Ads, DNS, or Cloudflare; no indexing submissions; no paid DataForSEO/Ahrefs/Semrush calls; no fabricated reviews, ratings, lab facts, or medical claims.
- **Sample size honesty:** the site's genuine indexable footprint (44 URLs) means specialist passes sampled representative pages per template type (e.g., 3–6 of 15 products, 3 of 8 compound-history hubs) rather than exhaustively re-testing all 44. Every specific claim in this report cites the exact URL(s) checked; see `findings/*.md` for full per-URL detail.

## 2. Executive summary

Five deployment milestones since the 2026-08-18 audit delivered **13 verified fixes** and **15 partial fixes**, concentrated in crawl-path/sitemap hygiene, the GLP-2T content rewrite, and JSON-LD entity-graph consolidation. GSC now confirms Shop, the Quality Archive, the documentation guide, and Retatrutide 10mg have all reached **"Submitted and indexed"** status — a real, verified change. **Zero findings regressed.**

But **47 of 97 findings (48%) remain unchanged** ("Still Open"), and this pass surfaced two important corrections to prior claims:

1. **The research-gate accessibility work (VIS-02) does not hold up under direct inspection.** Four of its eight specific sub-claims — `aria-describedby`, a native exit link, focus-trap/keyboard containment, and background inerting — are not present in the live markup, despite being marked "Live code verified" since 8/18.
2. **The gate does not withhold content server-side (a correction to SXO-01's framing, not a fix).** Raw, non-JS HTTP responses confirm real page content — product data, COA batch evidence, hero copy — is fully present in every page's initial HTML, tens of thousands of bytes before the gate's markup. The unresolved problem is a rendering/UX one (100% of first paint blocked for JS-rendering visitors, screenshot-confirmed), not a crawlability/content-generation one.

**Indexation has improved; organic visibility has not.** GSC Search Analytics shows **0 clicks and 7 impressions total** across both the 28-day and 90-day windows, average position 45.7. This is the honest current state of the site's organic performance.

**Performance: Paulo's reported ~98 desktop / ~68 mobile gap is real, reproducible, and explained below (§4).** Desktop averages 96.8 across 5 URLs; mobile averages 71.6 with a 56–81 range — 68 sits inside that range, not below it. The dominant cause is Lighthouse's mobile CPU/network throttling methodology itself (a 4–6x multiplier on identical code), not a single fixable defect — though real, actionable opportunities remain (render-blocking CSS/JS, GTM cost, unused JS, non-hero image delivery).

## 3. Score tables

### 3.1 SEO Health Score (this audit's own scoring, 0–100 per category)

| Category | Score | Basis |
|---|---:|---|
| Technical SEO | 74/100 | Crawl/sitemap/canonical hygiene strong; security-header stack (HSTS/CSP/X-Frame-Options/Referrer-Policy) entirely absent; one genuine orphan product page |
| Schema / Structured Data | 68/100 | Entity-graph consolidation (context, Offer.seller, Dataset.creator) holds broadly; two-emitter architecture and missing Dataset on compound-hub pages remain |
| Content Quality | 42/100 | One strong new guide; catalog-wide thin/templated product descriptions unresolved; two inconsistent legal disclaimers; brand-name inconsistency |
| Performance (CWV, lab) | 58/100 | Desktop excellent (96.8 avg); mobile Needs-Improvement to Poor with high run-to-run variance (56–81) |
| E-commerce | 55/100 | Schema/pricing/no-fabrication discipline strong; 7 of 15 products have no COA coverage; 7 of 15 out of stock with no substitutes; no reviews (by design) |
| Above-the-fold / SXO | 30/100 | Gate blocks 100% of first paint on every device; accessibility sub-claims not supported by markup; content itself is not withheld server-side |
| GEO / AI Readiness | 38/100 | One substantial new guide; passage lengths ~1/3 of AI-citation benchmark; no sameAs/off-site signals; no differentiated AI-crawler robots.txt policy |

*(Weighted overall score intentionally omitted — the site's dominant unresolved issue, the above-the-fold gate, is a UX/business decision requiring Paulo's sign-off, not a single number that should be allowed to imply the site is "mostly fine.")*

### 3.2 Desktop / Mobile PageSpeed scores (fresh, 2026-08-20)

| URL | Desktop | Mobile (individual runs) | Mobile LCP range |
|---|---:|---|---|
| `/` (Home) | 97 | 81, 81, 81 *(all 3 cached, 1 unique run)* | 3.8s |
| `/shop/` | 96 | 81, 81, **67** | 4.2s – 6.8s |
| `/product/nad/` | 96 | 75, **63**, 75 | 4.2s – 7.1s |
| `/testing/` | 97 | 76, 66, **56** | 4.2s – 7.5s |
| COA batch report | 98 | 79, 79, 79 *(all 3 cached, 1 unique run)* | 4.4s |
| **Average** | **96.8** | **71.6** (median 75, range 56–81) | — |

**Caveat on the mobile average:** 6 of the 9 "runs" per URL were PSI-served cache duplicates (identical `analysis_timestamp`) — only 9 genuinely independent mobile measurements exist across 5 URLs, not 15. Where independent runs exist, variance is large (Shop 81→67, NAD+ 75→63, Testing hub 76→66→56 — a 20-point swing across three back-to-back API calls on unchanged code). **Any single mobile PageSpeed number, including Paulo's reported ~68, should be read as one sample from a 56–81 range, not a fixed score.**

## 4. Why mobile can score ~68 while desktop scores ~98 (evidence for the reported gap)

Full metric tables (FCP/LCP/TBT/CLS/SI/TTFB/transfer/requests per run) are in `findings/performance.md`. Top five quantified causes:

1. **Lighthouse's mobile throttling methodology itself — the single largest factor.** Mobile lab runs apply ~4x CPU slowdown plus a simulated slow-4G network profile; desktop applies minimal throttling. For byte-identical resources, the render-blocking estimate swings from **370–610ms on desktop to 1,650–2,830ms on mobile** — a 4–6x multiplier tracking Lighthouse's stated throttle ratio almost exactly. This explains most of the gap independent of any code defect, and it caps how much further optimization alone can close the gap.
2. **Render-blocking CSS/JS (1,650–2,830ms mobile, present on every template).** Directly responsible for LCP landing at 3.8–7.5s on mobile. Beta.39's font consolidation and shell-CSS inlining measurably helped (staging showed 3,150ms→2,050ms on Quality Archive), but Live's fresh Testing-hub numbers still bracket the pre-fix staging value — improvement not yet clearly separable from run-to-run noise on Live.
3. **Google Tag Manager — ~182 KiB and 95–285ms main-thread cost on every template, both strategies**, byte-identical, but execution cost scales up under mobile's CPU throttle. Not yet addressed (PERF-06).
4. **Unused JavaScript — a consistent 105–106 KiB savings opportunity** on every URL and strategy. Same bytes shipped regardless of device; parse/exec time is inflated ~4x on mobile.
5. **Image delivery (non-hero) — 49–161 KiB remaining savings**, worst on Shop. PERF-03 fixed the homepage hero specifically (93.7%/92.5% verified reductions); the `image-delivery-insight` audit still fires on all 5 templates because other images weren't touched.

**Not a factor:** TTFB (3–104ms everywhere, well under the 200ms threshold — server response is not the problem) and CLS (0–0.084 everywhere, Good). **Not measurable:** CrUX real-user field data — the origin remains ineligible (insufficient Chrome traffic), so every number above is Lighthouse lab data, not real-user experience. Google will not use lab data to evaluate real-world Core Web Vitals status in Search.

## 5. Top 5 causes, ranked by overall audit impact (not just performance)

1. **The full-viewport research gate blocks 100% of first paint on every device, for every JS-rendering visitor** (VIS-01, confirmed via screenshot; SXO-01, confirmed the content itself is present in raw HTML but visually withheld). This is the single highest-leverage unresolved item — it affects every page, every visitor, and is very likely evaluated by Google's rendering pass the same way a human sees it.
2. **The complete absence of baseline security headers** (HSTS, CSP, X-Frame-Options, Referrer-Policy, Permissions-Policy) — a same-origin, no-WordPress-code-required fix at the Cloudflare/Kinsta edge, touching every page including checkout, unaddressed since 8/18 while five other milestones shipped.
3. **Catalog-wide thin/templated product content** — 5 of 6 sampled SKUs (including the "fixed" GLP-2T) still carry an identical ~109-word compliance block plus 85–108 words of unique copy; Bacteriostatic Water has zero product-specific description at all.
4. **Mobile render-blocking CSS/JS and third-party (GTM) cost**, still the dominant driver of Poor-range mobile LCP on Shop/NAD+/Testing hub.
5. **Zero organic search visibility despite improved indexation** — Shop, Testing, the guide, and Retatrutide 10mg are now indexed, but Search Analytics shows 0 clicks / 7 impressions across 90 days. Indexation was a prerequisite, not a guarantee; the gap between "crawlable" and "found by users" remains essentially total.

## 6. Quick wins (code-ready, low risk, no business input required)

1. **Add HSTS + X-Frame-Options at the Cloudflare/Kinsta edge.** No WordPress plugin code required. Start HSTS without `preload` for one release cycle, verify no subdomain breakage, then add `preload`.
2. **Extend the already-proven `Dataset` JSON-LD pattern from individual COA batch reports to compound-hub pages** (GEO-07). Pep Select already has 100% real backing data for this — the pattern is proven correct on batch-report pages; this is pure engineering, no new business decision.
3. **Add a real `href="https://pepselect.com/product/bacteriostatic-water-30ml/"` link from at least one relevant product page or FAQ answer** (MAP-01) — the current "cart upsell" is a JS toggle with no crawlable anchor; this closes a genuine orphan-page gap without a merchandising decision (adding it to the Shop grid, which is a separate, larger decision, is not required to fix the orphan-link problem).
4. **Fix the "Exit" link to be a real `<a href="...">` element** instead of a JS-only click handler (VIS-02/VIS-03) — restores keyboard-tab reachability regardless of the larger gate-redesign decision.
5. **Standardize "Pep Select" vs. "PepSelect"** in Terms, Privacy, and Refund policy text (CONT-13) — a find-and-replace-scale content fix with no design/business dependency.
6. **Suppress the leaked WordPress username (`beringela001`) from `meta[name=author]` and Twitter-card author tags** on the guide (GEO-08 hygiene note) — either hide it or set a real display name; independent of the larger "who is the real author" decision.

## 7. Risks

- **Security-header rollout risk:** CSP in particular can break third-party scripts (GTM, Elementor, YITH rewards, Google Fonts, side-cart/checkout-upsell AJAX) if deployed in enforcing mode without a report-only trial first. Recommend `Content-Security-Policy-Report-Only` for at least one full release cycle before enforcing.
- **Gate redesign risk:** any change to the research-gate's blocking behavior is a compliance/business decision (the gate exists for a stated attestation/compliance purpose), not a pure UX fix — do not alter its blocking behavior without Paulo's explicit sign-off, even though the accessibility sub-claims (focus trap, inerting, real exit link) can be fixed independently of that larger decision.
- **Content-freshness discipline:** CONT-12/MAP-02's "don't manufacture freshness dates" rule should continue to be honored; do not resave static pages solely to change `lastmod`.
- **Case-URL redirect (TECH-05):** adding a 301 for case-variant Page URLs is low-risk but should be tested against any existing internal links or campaign UTMs that might rely on a specific case variant before enforcing.

## 8. Exact verification checks (for the next operator)

- `curl -sD - https://pepselect.com/ | grep -i strict-transport` → should return a header once HSTS ships (currently empty).
- `curl -s https://pepselect.com/testing/nad-500-mg/ | grep -c '"@type":"Dataset"'` → should be ≥1 once GEO-07 ships (currently 0).
- `curl -s https://pepselect.com/product/bacteriostatic-water-30ml/ -o /dev/null -w '%{http_code}'` combined with a `grep` for its URL in Shop/product-page HTML → should find ≥1 crawlable `<a href>` once MAP-01 ships (currently 0).
- Re-run `pagespeed_check.py --strategy mobile` at least 3x per URL before/after any performance change and report the range, not a single score — this audit's own data shows single-run comparisons will misattribute lab-runner noise to code changes.
- GSC URL Inspection on `/shop/`, `/testing/`, the guide, and `/product/glp3-r10/` — confirm they remain "Submitted and indexed" and watch Search Analytics for the first non-zero click.
- `curl -sD - https://pepselect.com/Shop/` → should 301 to `/shop/` once TECH-05 ships (currently 200, no redirect).

## 9. Prioritized plan

See `ACTION-PLAN.md` for the full phased breakdown with effort estimates and code-ready vs. approval-required labeling. Headline sequencing:

1. **Immediate (code-ready):** security headers (report-only CSP first), GEO-07 Dataset extension, MAP-01 orphan link, VIS-02/VIS-03 exit-link fix, CONT-13 brand-name standardization, GEO-08 username-leak hygiene fix.
2. **Requires Paulo approval (UX/business decision, code exists to support either direction):** the research-gate blocking-behavior decision (VIS-01/SXO-01/SXO-08) — this is the single highest-impact open item in the entire audit and is explicitly a business/compliance call, not an engineering one.
3. **Content investment (scoped, no blocker):** extend the guide's evidence-led, real-batch-citation approach to the ~13+ remaining templated product descriptions (CONT-03/04/ECOM-07), and lengthen citable passages toward the ~150-word AI-citation benchmark (GEO-03).
4. **Blocked on business input (do not fabricate):** reviews/ratings (ECOM-01), 7 missing COA archive pages (ECOM-02, pending real lab records), NAP/contact info (SCHEMA-05/CONT-09), sameAs/social profiles (SCHEMA-04/GEO-04/GEO-05), a real named author/reviewer (CONT-08/GEO-08), GA4 property confirmation (GOOG-11).
5. **Monitor only (no code fix exists):** CrUX field-data eligibility (GOOG-09) — purely a function of real-user traffic volume growing over time.

## 10. What this audit does not claim

- No ranking, click, revenue, or conversion improvement is claimed anywhere in this report.
- Indexation ≠ visibility: four URLs newly reaching "Submitted and indexed" is a real, verified technical outcome; it is not evidence of improved search performance, which remains at zero clicks.
- No CrUX/real-user Core Web Vitals verdict is possible for this origin at this traffic volume; every performance number in this report is Lighthouse lab data.
- No backlink, keyword-difficulty, or AI-Overview-citation re-measurement was performed (DFS-02/04/06) — these require paid tooling explicitly excluded from this audit's scope.

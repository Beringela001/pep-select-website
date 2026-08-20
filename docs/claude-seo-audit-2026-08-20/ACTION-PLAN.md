# Action Plan — Pep Select SEO, 2026-08-20 Verification Audit

Source: `PRIOR-FINDINGS-VERIFICATION-LEDGER.md` (97 findings) and `FULL-AUDIT-REPORT.md`. Every item below is labeled **[CODE-READY]** (an engineer can implement without further input), **[NEEDS APPROVAL]** (a UX/business/compliance decision from Paulo, code can be written either way once decided), or **[BLOCKED — REAL EVIDENCE NEEDED]** (requires real business data/records/credentials Pep Select must supply; never fabricate).

## Phase 1 — Immediate, code-ready, no dependency (target: this week)

| Item | Finding(s) | Effort | Notes |
|---|---|---|---|
| Add `Strict-Transport-Security` header at Cloudflare/Kinsta edge | TECH-01, GOOG-08 | Low | Start without `preload`, verify no subdomain breakage one release cycle, then add `preload` |
| Add `X-Frame-Options: SAMEORIGIN` at the edge | TECH-03, GOOG-08 | Low | Protects checkout and the gate flow from clickjacking |
| Add `Referrer-Policy: strict-origin-when-cross-origin` and a conservative `Permissions-Policy` | TECH-04, GOOG-08 | Low | Disable geolocation/microphone/camera by default; allow `payment=(self)` if a WooCommerce payment method needs it |
| Start `Content-Security-Policy-Report-Only` covering actual script/style/img/font origins (self, Cloudflare, Google Fonts, Elementor, GTM, YITH/WooCommerce, Jetpack) | TECH-02, GOOG-08 | Medium | Report-only for ≥1 release cycle before enforcing — real risk of breaking third-party scripts if enforced blind |
| Extend the proven `Dataset` JSON-LD pattern from individual batch reports to compound-hub pages (`/testing/<compound>/`) | GEO-07 | Medium | Pattern and data already exist and are verified correct on batch-report pages; pure engineering |
| Add one real `<a href="...">` link to `bacteriostatic-water-30ml` from a relevant product page or FAQ answer | MAP-01 | Low | Closes the orphan-page gap without deciding whether to add it to the Shop catalog grid (a separate, larger merchandising call) |
| Give the gate's "Exit" element a real `href="https://google.com"` instead of JS-only click handling | VIS-02, VIS-03 | Low | Restores default keyboard-tab reachability regardless of the larger gate-redesign decision in Phase 2 |
| Add `aria-describedby` pointing to the gate's intro paragraph | VIS-02 | Low | One attribute + one `id`; independent of the blocking-behavior decision |
| Increase gate legal/disclaimer text below 16px to ≥16px on mobile | VIS-04 | Low | `.psag-note` (11.5px), `.psag-version` (10.5px), `.psag-copy` (12px), `.psag-intro` (14px) |
| Standardize "Pep Select" vs. "PepSelect" across Terms, Privacy, Refund policy text and the mailing address | CONT-13 | Low | Find-and-replace scale; pick one form and apply consistently |
| Suppress the leaked WordPress username (`beringela001`) from `meta[name=author]` / Twitter-card author tags on the guide | GEO-08 (hygiene) | Low | Either hide the tag or set a real WP display name; independent of the "who is the real author" decision |
| Differentiate `robots.txt` for AI crawlers (explicitly allow retrieval bots like OAI-SearchBot/PerplexityBot/Google-Extended; optionally block training-only bots like CCBot/GPTBot) | GEO-09 | Low | This is a policy choice Paulo should confirm the direction of, but the technical change itself is a small robots.txt edit — listed here as code-ready pending a one-line decision, not a blocker |
| Case-normalize WordPress Page-type routes to their lowercase canonical via a 301 | TECH-05 | Low–Medium | Test against any existing internal links/campaign UTMs using a non-canonical case first |

## Phase 2 — Needs Paulo's approval (business/UX/compliance decision; code supports either direction)

| Item | Finding(s) | Why it needs approval |
|---|---|---|
| **Research-gate blocking-behavior redesign** (defer full-viewport block until scroll/interaction, or add a real focus trap + background inerting to make the current pattern accessible as-is) | VIS-01, VIS-02 (focus trap/inerting), SXO-01, SXO-08 | The gate exists for a stated compliance/attestation purpose — its blocking behavior is a business decision, not a pure engineering call. This is the single highest-impact unresolved item in the audit: it affects 100% of visitors on every page and device. Two independent paths exist and should be presented to Paulo: (a) keep it fully blocking but make it genuinely accessible (focus trap + `inert` on background + `aria-describedby`), or (b) reduce its severity (e.g., allow scroll-through with a persistent banner) — a bigger compliance conversation. |
| Add a low-friction third path for undecided visitors (e.g., "browse without attesting" or an on-site informational landing page instead of exit-to-Google) | VIS-03, SXO-08 | Changes the gate's fundamental in/out UX contract |
| Decide whether to add Bacteriostatic Water to the Shop catalog grid | MAP-01 (broader merchandising question) | Catalog/merchandising decision beyond the Phase-1 orphan-link fix |
| Approve a dedicated comparison/"why choose us" page or `/shop/`-level trust-density upgrade | SXO-02, SXO-04 | Net-new page type and homepage-adjacent content decision |

## Phase 3 — Content investment (scoped, no blocker, larger effort)

| Item | Finding(s) | Scope |
|---|---|---|
| Extend the guide's evidence-led, real-batch-citation approach to the ~13+ remaining templated product descriptions | CONT-03, CONT-04, ECOM-07 | Leave the compliance/FDA blocks untouched; replace only the one-paragraph description + 3-bullet template with unique, mechanism-specific copy (as GLP-2T got) |
| Lengthen citable passages toward the ~150-word AI-citation benchmark | GEO-03 | Same content work as above, done with self-contained subject+claim sentences (not fragments) so each passage can stand alone for AI extraction |
| Add question-phrased H2/H3 headings to the guide and future content, matching real PAA queries | GEO-06, DFS-08 | Content-authoring pattern change, not new research |
| Add content addressing the broader PAA/comparison query cluster (best/cheapest/trusted, storage, legality, "what is a COA") | SXO-02, SXO-03, DFS-08, DFS-09 | Net-new content, can reuse the guide's evidence-led format |
| Reconcile the two differently-worded FDA disclaimer paragraphs into one consistent version | CONT-07 | Legal/compliance text — coordinate wording with whoever owns the FDA disclaimer language, even though this is a content task |
| Hyperlink DOI/PMID citation strings to doi.org/PubMed | CONT-06 | Small template change on the citation block |
| Add `aria-label` differentiation to the 7 identical "Learn more" product-card links on `/shop/` | GOOG-10 | Accessibility + on-page SEO improvement |
| Add `ItemList`/`OfferCatalog` schema to `/shop/` | ECOM-08 | Schema addition, no new business data required |

## Phase 4 — Blocked on real business input (never fabricate)

| Item | Finding(s) | What's needed from Paulo |
|---|---|---|
| Real product reviews/ratings | ECOM-01 | A genuine review-capture workflow — never seed or invent ratings |
| COA archive pages for the 7 uncovered products (glp1-s10, glutathione, glp2-t20, bpc157-10, motsc-10, ss-31, bacteriostatic-water-30ml) | ECOM-02 | Real, completed lab testing records for these compounds |
| Substitute-product surfacing on the 7 out-of-stock product pages | ECOM-05 | Paulo-approved substitute mapping |
| NAP (phone, address, staff) on `/contact/` and Organization schema | SCHEMA-05, CONT-09 | Paulo-approved public contact information |
| `sameAs` social/entity profiles on the Organization entity | SCHEMA-04, GEO-04, GEO-05 | Real, live social/entity profile URLs (YouTube correlates most strongly with AI citation per prior research) |
| Real named, credentialed author/reviewer for the guide and future YMYL-adjacent content | CONT-08, GEO-08 | A real, approved person's name and credentials |
| Merchant Center feed / restricted-catalog eligibility | DFS-07, ECOM-09, ECOM-06 (GTIN/MPN) | Owner verification of eligibility before any feed work; real GTIN/MPN identifiers if/when they exist |
| GA4 property confirmation | GOOG-11 | Confirm which GA4 property (if any) should be connected — a `gtag.js` container (`GT-NNQ4N6DP`) is already live site-wide but its GA4 wiring is unconfirmed |
| Dataset `license` URL on COA batch reports | SCHEMA-09 | An approved, real public data-license URL |

## Phase 5 — Monitor only (no code fix exists)

| Item | Finding(s) | What to watch |
|---|---|---|
| CrUX real-user field-data eligibility | GOOG-09 | Purely a function of real-user Chrome traffic volume growing over time; re-check monthly |
| Organic click/impression growth | GOOG-03, DFS-01, DFS-03 | Watch GSC Search Analytics weekly; currently 0 clicks / 7 impressions across 90 days |
| Backlink profile / spam score / keyword difficulty / AI Overview citation | DFS-02, DFS-04, DFS-06 | Not independently re-measured this cycle (requires paid tooling out of scope); revisit with DataForSEO/Ahrefs access if the constraint is lifted |
| Domain age | DFS-10 | Time-dependent only, no action |

## Already correct — preserve, do not regress

- TTFB (3–104ms everywhere) and CLS (0–0.084 everywhere) — PERF-11, do not introduce layout shift or server-response regressions while making the above changes.
- No-fabrication discipline on Product/Offer/Dataset schema (GTIN, MPN, reviews, `priceValidUntil`, license) — continue leaving these absent rather than inventing values.
- Sitemap hygiene (44 URLs, 0 non-200s, `/shop/` deduplicated, transactional pages correctly excluded) — MAP-02/MAP-03/sitemap structure.
- www→non-www and `/terms-of-service/`→`/terms-conditions/` redirect chains — CONT-01, single-hop, verified intact.

# Visual / SXO Findings — 2026-08-20 Verification Audit

> This file is shared between the Visual/Gate-Accessibility specialist and the SXO specialist.
> If SXO content is not yet present below, it will be appended separately in its own `## SXO Findings` section.

## Visual / Gate Accessibility Findings

**Method used:** Live screenshots via Playwright Chromium (installed and confirmed working this session), plus rendered-DOM/JS markup inspection (`page.content()` after `networkidle`, and in-page `getComputedStyle`/DOM queries executed via `page.evaluate`). This is real pixel-level and DOM-level verification, not markup-only fallback — Playwright was available and used successfully. All checks were read-only GET navigations; no attestation form was submitted, no checkboxes were checked, and the "Enter Site" button was never clicked.

Screenshots saved:
- `docs/claude-seo-audit-2026-08-20/screenshots/gate-mobile-390x844.png`
- `docs/claude-seo-audit-2026-08-20/screenshots/gate-desktop-1440x900.png`

### 1. Above-the-fold coverage (VIS-01)

Confirmed on both viewports, pre-interaction, via both visual screenshot and DOM measurement:

- Mobile 390×844: gate element `#psag-gate` bounding rect = `{w:390, h:844, top:0, left:0}` — exactly matches the full viewport. `position: fixed`, `display: flex`, `visibility: visible`, `z-index: 9999999`. `document.body` computed `overflow: hidden`.
- Desktop 1440×900: gate element `#psag-gate` bounding rect = `{w:1440, h:900, top:0, left:0}` — exactly matches the full viewport. Same fixed/flex/visible/z-index/body-overflow properties.

Both screenshots show only the "Research Access Verification" card on a solid background; no storefront content (header, hero, nav, products) is visible or peeking through on either device. **VIS-01 is fully reproduced: the gate blocks 100% of above-the-fold content on both mobile and desktop before any interaction.**

### 2. Gate DOM/ARIA markup inspection (VIS-02)

Actual live markup (rendered DOM, desktop 1440×900 load), quoted:

```html
<div id="psag-gate" role="dialog" aria-modal="true" aria-labelledby="psag-title">
  <div class="psag-card">
    <div class="psag-logo"><img src="https://pepselect.com/wp-content/uploads/2026/06/Logo_Pepselect_Whitebackground-1.png" alt="Pep Select" loading="eager"></div>
    <p class="psag-kicker">Research Gate</p>
    <h2 class="psag-title" id="psag-title">Research Access Verification</h2>
    <p class="psag-intro">Pep Select provides research-grade compounds exclusively for qualified laboratory and in vitro research. Please confirm the following before entering.</p>
    ...
    <p class="psag-exit">Not a researcher? <a id="psagExit">Exit</a></p>
    <div class="psag-legal"> ... </div>
  </div>
</div>
```

Checked against each specific claim in the prior ledger's VIS-02 write-up ("dialog description, attestation controls, responsive logo, native exit link, focus containment, background inerting, and focus restoration are present"):

| Claimed element | Live evidence | Present? |
|---|---|---|
| `role="dialog"` | `role="dialog"` on `#psag-gate` | Yes |
| `aria-modal` | `aria-modal="true"` | Yes |
| `aria-labelledby` (title relationship) | `aria-labelledby="psag-title"` → resolves to "Research Access Verification" | Yes |
| **`aria-describedby` (dialog description)** | Attribute is **absent** — `dialog.getAttribute('aria-describedby')` returned `null`. No element provides an accessible description of the gate's purpose to AT beyond the title. | **No** |
| Attestation controls | Two checkboxes, a researcher-type `<select>`, and an expandable "Read the researcher attestation" `<button aria-expanded="false">` with an ordered list of 6 attestation statements — present and reasonably marked up. | Yes |
| Responsive logo | Single `<img src="...">` with `alt="Pep Select"`, **no `srcset`/`sizes`**, no `<picture>`. It is a plain fixed-resolution image, not a responsive image; only "responsive" in the CSS-scaling sense (it visually resizes across the two screenshots), not in the markup sense. | Partial |
| **Native exit link** | `<a id="psagExit">Exit</a>` — **no `href` attribute at all.** Navigation happens only via a JS click handler: `exit.addEventListener('click', function(){ window.location.href = EXIT; })`. An anchor with no `href` is not a real hyperlink: browsers give it no link semantics and it is excluded from the default Tab order. This directly contradicts the "native exit link" claim. | **No** |
| **Focus containment (focus trap)** | Full text of the gate's `<script>` block was read line-by-line. There is no `keydown`/`Tab` handling anywhere in it, and no focus-trap library is loaded for the gate. Nothing prevents Tab/Shift+Tab from leaving the dialog into the (visually hidden but still-present) page behind it. | **No** |
| **Background inerting** (`inert` / `aria-hidden`) | Queried every one of `document.body`'s ~50 direct children (header, nav, main, footer, all `<script>` tags, etc.) for `.inert` and `aria-hidden`: **every single one returned `inert: false, ariaHidden: null`.** A full-text search of the entire rendered HTML/JS document for the strings `inert` and `aria-hidden` returned zero matches anywhere on the page. The background is visually covered by `z-index`/`overflow:hidden` but is **not** inerted for assistive technology — a screen reader or switch-access user could still navigate into hidden background content. | **No** |
| Focus restoration | No code path found (no "Enter Site" click was performed, per the read-only constraint, so post-close behavior wasn't directly observed) — but the script contains no reference to storing/restoring `document.activeElement`, and on initial page load `document.activeElement` was `<body>` (focus was never moved into the dialog in the first place), so there is nothing being "restored" from. | Not verified / no supporting code found |

**Net assessment:** of the eight specific sub-claims made in the ledger for VIS-02 ("Live code verified"), direct DOM/script inspection confirms 2 fully (role/aria-modal, aria-labelledby) and attestation controls, but contradicts or fails to find evidence for the four highest-accessibility-impact claims: `aria-describedby`, native exit link, focus trap, and background inerting. None of `inert`, `aria-hidden`, or any Tab/keydown focus-management code exists anywhere in the live page. This is a materially different picture from "Live code verified" — this is markup/script-level evidence, not a full screen-reader walkthrough, but it is direct and reproducible, and it does not support the prior claim for those four items.

### 3. "Not a researcher? Exit" destination (VIS-03)

From the gate's own script (quoted verbatim from the rendered page):

```js
var EXIT    = "https:\/\/google.com";
...
var exit = document.getElementById('psagExit');
if (exit) exit.addEventListener('click', function(){ window.location.href = EXIT; });
```

Confirmed: clicking "Exit" would run `window.location.href = "https://google.com"` — an off-site redirect to Google's homepage, with no informational alternative page on pepselect.com. This is worse than a plain `<a href="https://google.com">`, because (as noted in §2) the anchor has no `href` at all, so it is JS-only and not in the keyboard tab order by default. VIS-03 is fully reproduced, with additional evidence that it is not even backed by a real hyperlink.

### 4. Legal/disclaimer text mobile font size (VIS-04)

Computed `font-size` (desktop 1440×900 render; CSS is not viewport-conditional for these rules based on the stylesheet, and the same card scales down proportionally on mobile per the screenshots) for gate text nodes:

| Element | Class | Computed font-size |
|---|---|---|
| Intro paragraph | `.psag-intro` | 14px |
| "Remember me" note | `.psag-note` | 11.5px |
| Form version footer | `.psag-version` | 10.5px |
| Copyright line | `.psag-copy` | 12px |

All four measured text nodes inside the gate are below the ~16px mobile-readability guideline; the smallest (form version, 10.5px) is roughly two-thirds under the threshold. The main legal/FDA-disclaimer paragraph block sits inside `.psag-legal` styled visually the same size class as the note text in the screenshot (visibly small, condensed paragraph text below the fold of the card) — consistent with sub-16px sizing, though the exact computed value for that specific paragraph was not separately isolated. VIS-04 is reproduced for the four confirmed elements.

### 5. Synthetic tooling measures the gate, not the page (VIS-05)

Not independently re-tested this session (no new PageSpeed/Lighthouse run was performed), but the DOM evidence above is consistent with the prior "Conflicting / validate" status: the gate is injected as the first paint-blocking, full-viewport element (`z-index: 9999999`, `position: fixed`, covers 100% of viewport at load), so any synthetic tool measuring initial render/LCP on the raw URL will measure the gate rather than the storefront content beneath it. No new measurement data collected this session; classification carried forward as unresolved pending a dedicated re-test.

---

### Prior Finding Classifications

| ID | Original Priority | Prior State (8/18) | Current Classification | Evidence |
|---|---|---|---|---|
| VIS-01 | Critical | Not started | STILL OPEN | Screenshot + DOM measurement on both 390×844 and 1440×900: `#psag-gate` bounding rect exactly equals viewport (`w`/`h`/`top:0`/`left:0`), `position:fixed`, `z-index:9999999`, `display:flex`, `body{overflow:hidden}`. No storefront content visible or reachable pre-interaction on either device. |
| VIS-02 | High | Live code verified | STILL OPEN | Direct rendered-DOM/script inspection contradicts 4 of the ledger's specific sub-claims: `aria-describedby` attribute is absent (`null`); the "Exit" element `<a id="psagExit">` has **no `href`** and is JS-click-only; a full-text search of the entire rendered page for `inert`/`aria-hidden` returned zero matches (no background inerting exists); the gate's `<script>` block contains no `keydown`/Tab focus-trap logic. `role="dialog"`, `aria-modal="true"`, `aria-labelledby`, and attestation controls are confirmed present. A human AT walkthrough remains outstanding as before, but the underlying markup itself does not support the "focus containment," "background inerting," and "native exit link" claims. |
| VIS-03 | Low | Not started | STILL OPEN | Gate script: `var EXIT = "https://google.com"`; click handler does `window.location.href = EXIT`. Confirmed off-site redirect to Google with no on-site informational alternative. Additionally the "Exit" anchor has no `href` attribute (JS-only), which was not previously documented. |
| VIS-04 | Low | Not started | STILL OPEN | Computed font-size via `getComputedStyle`: `.psag-intro` 14px, `.psag-note` 11.5px, `.psag-version` 10.5px, `.psag-copy` 12px — all below 16px. |
| VIS-05 | Medium | Conflicting / validate | STILL OPEN | Not independently re-measured with PageSpeed/Lighthouse this session; DOM evidence (full-viewport, `z-index:9999999`, fixed gate present at initial render) is consistent with the prior concern that synthetic first-paint/LCP measurement captures the gate, not the underlying page. Classification carried forward unchanged pending a dedicated re-test. |

---

## SXO Findings

**Method used:** raw (non-JS) HTTP GET via `curl` with a standard desktop user-agent against `https://pepselect.com/`, `/product/nad/`, `/shop/`, `/testing/`, and `/faq/`. No Playwright rendering, no cookies set, no form submission, no attestation. This deliberately captures exactly what a crawler, a text-only client, or a JS-disabled browser receives on first response — the same class of evidence the ledger's SXO-01 finding is about. Full HTML was saved locally and inspected with byte-offset/line-number search (not truncated summaries). A pre-existing truncated crawl-summary snapshot in `docs/claude-seo-audit-2026-08-20/raw-crawl/` (500-char excerpts only) was cross-checked but not relied on as primary evidence because it is too short to confirm ordering or completeness.

### THE CENTRAL QUESTION: does the raw HTML withhold content, or is the gate a rendering-only overlay?

**It is a rendering-only overlay. The raw, unrendered HTML response does NOT withhold page content.**

Evidence, all five pages tested:

| Page | Raw HTML total length | `<body>` starts at (byte offset) | Gate `id="psag-gate"` div starts at (byte offset) |
|---|---|---|---|
| `/` (home) | 160,602 | 85,363 | 150,773 |
| `/product/nad/` | 174,832 | 87,429 | 164,889 |
| `/shop/` | 183,766 | 83,208 | 173,942 |
| `/testing/` | 158,316 | 78,975 | 148,496 |
| `/faq/` | 125,663 | 50,425 | 115,840 |

On every page, the gate's dialog markup (`<div id="psag-gate" role="dialog" ...>`) is placed as effectively the last major block in `<body>`, after tens of thousands of bytes of real page markup. On the homepage, the real hero/trust copy — "Research Without the Runaround", "You shouldn't need five tabs and a leap of faith to look into a research peptide. Pep Select keeps current product details and available batch documentation in one place...", "The Current Selection", and "Why Pep Select is different" — appears at byte offsets corresponding to roughly the first third of `<body>`, more than 30,000 bytes before the gate markup even begins. On `/product/nad/`, the real content present before the gate div includes: page `<title>NAD+ 500MG for Research | Pep Select</title>`, `<h1>NAD+</h1>`, `<p class="stock in-stock">In stock</p>`, `<button ... name="add-to-cart" value="549" ...>Add to cart</button>`, and a full COA/batch-documentation block (`<aside class="ps-coa-app ps-coa-product-summary ...">`) citing a real batch number (`ND50026205JP`), purity (`99.87%`), test date (`Tested July 30, 2026`), and named lab (`Freedom Diagnostics Testing`), plus a "Batch Documentation" carousel of current/incoming/previous batch records. None of this is stubbed, placeholder, or empty-shell markup — it is fully populated real content, delivered in the initial HTTP response body before any script executes.

The gate's own CSS confirms the mechanism: `#psag-gate{position:fixed;inset:0;z-index:9999999;display:flex;...}` is a plain, unconditional inline `<style>` rule — not something injected only after a JS check. It visually covers the whole viewport for a rendering client the instant CSS is applied, which is why the Visual specialist's screenshots (§1 above) show only the gate. But visual covering is not the same as content removal: the underlying DOM nodes for the real page (hero copy, product data, COA batch records, nav, footer) exist in the document the whole time and are present in the raw HTTP response text that a search-engine crawler, a text browser, or a screen reader operating on raw markup would receive.

One important asymmetry for human (non-crawler) users specifically: the "Enter Site" button ships with the HTML `disabled` attribute (`<button ... id="psagEnter" disabled>Enter Site →</button>`), and it is only ever re-enabled by a JS `sync()` function that listens for checkbox/select changes. There is no `<noscript>` fallback and no non-JS path to enable it. So while the *content* is not withheld from the response, a real human visitor with JavaScript disabled would be permanently unable to dismiss the gate at all (worse than the JS-enabled case) — this is a distinct issue from indexability and is noted for completeness, not as a correction to the "content is present" finding.

**Practical implication for the ledger:** SXO-01's original wording — "trust content is deferred, not delivered" — overstates the server-side mechanism. The content genuinely is delivered in the HTTP response body on every page tested. What is actually true, and still a real, unresolved problem, is that (a) 100% of *human, JS-rendering* visitors see only the gate at first paint (confirmed independently by the Visual specialist's screenshots/DOM measurements above), and (b) this pattern is very likely to be evaluated by Google's rendering pass (which executes JS/CSS, unlike a raw-HTML crawl) the same way a human sees it — as an intrusive, full-viewport interstitial — which is the substance of the separately-owned VIS-01 finding and Google's page-experience guidance on intrusive interstitials. The crawlability/indexability risk (bots literally not seeing text) that "deferred, not delivered" implies is not what the evidence shows; the risk is a rendering/UX-pattern one, not a content-withholding one. This distinction matters for how any fix is scoped: it is a gate-presentation/timing problem (W2: research-gate source/settings), not a content-generation or server-response problem.

### SXO-02 — comparison / "why choose us" content for best/cheapest/most-trusted cluster

The homepage raw HTML now contains a section headed **"Why Pep Select is different"** (`<p class="pepselect-home__eyebrow">Why Pep Select is different</p>`, byte offset ~2,737 lines into the markup, well before the gate), immediately following "The Current Selection" section. This is new differentiation-style content that did not appear to exist as of the 8/18 audit (the ledger's Milestone 4 Batch 1 checkpoint on 8/19 only documents "one natural `research peptide` use" as the homepage content change, not a differentiator section — so this section is a further, more recent addition). This content type is a genuine step toward SXO-02's need.

However, it does not fully close the gap: it is a single differentiator section on the homepage, not a dedicated comparison asset addressing the "best/cheapest/most trusted" query cluster specifically — no explicit pricing comparison, no side-by-side competitor/criteria comparison, and no equivalent content exists on `/shop/` (the page type most likely to catch that query cluster's intent). The full text of the "Why Pep Select is different" section beyond its heading was not captured in this pass (evidence is limited to the heading and surrounding structural placement); a follow-up read of its full body copy is recommended before closing this finding outright.

### SXO-03 — PAA-style question coverage on `/faq/` and the documentation guide

`/faq/` was fetched (raw HTML, 125,663 bytes, real content present ~65,000 bytes before the gate div) but its specific question/answer content was not individually enumerated in this pass — this is a limitation of this session's evidence, not a negative finding. No new ledger-level evidence (e.g., a checkpoint entry documenting FAQ content expansion) was found in `CODEX-SEO-FINDINGS-LEDGER-2026-08-18.md`'s checkpoint log between 8/18 and 8/19 that references FAQ or PAA-targeted content work; the checkpoint entries in that window cover GLP-2T description content, footer testing-policy copy, and one homepage keyword use only. Given no documented remediation event and no direct content audit performed this session, this is carried forward as **unverified — insufficient evidence to confirm either fixed or still open**, and should not be marked resolved without a dedicated content read of `/faq/` and the documentation guide against the four PAA questions identified in the original DFS-08/SXO-03 evidence.

### SXO-04 — `/shop/` trust density and stock breadth vs SERP winners

`/shop/` raw HTML was fetched (183,766 bytes, real catalog content confirmed present well before the gate div at byte 173,942) but a structured comparison of trust-signal density (COA links, batch data, policy callouts) *directly on the shop/category listing* against SERP-winner patterns was not completed in this pass. What can be said from adjacent evidence: the ledger's ECOM-04 (Live verified) confirms the COA archive links back to individual products, and the product-page level (SXO-05 below) confirms batch/COA data is present directly on product pages reached from `/shop/`. Whether that trust density is also surfaced at the `/shop/` listing level itself (e.g., as badges/snippets on each product card, not just after a click-through) was not directly confirmed. Classification: **STILL OPEN**, carried forward from "Not started," since no checkpoint entry documents shop-page trust-density work and this session did not gather direct card-level evidence to the contrary.

### SXO-05 — product-page content sequencing (trust proof vs. compliance/description)

On `/product/nad/`, the confirmed raw-HTML order of key blocks (by ascending byte/line position, all before the gate) is:
1. `<title>`/`<h1>NAD+</h1>` and stock status (`In stock`) / `Add to cart` button
2. Immediately after: `<aside class="ps-coa-app ps-coa-product-summary ps-coa-product-summary--documented" aria-label="Batch documentation">` — the real batch/COA evidence block (current batch link, purity, batch number, lab name)
3. Then: a "Batch Documentation" carousel showing current/incoming/previous batch records

This places trust/batch evidence directly adjacent to the add-to-cart action — structurally an improvement pattern (trust proof near the primary conversion point), not proof that it precedes the *product description* or the *compliance disclaimer* text specifically, since this pass did not capture where the long-form description paragraph and the product-page-level compliance disclaimer (distinct from the gate's own legal text) sit relative to this block. Evidence gathered is **partial**: it confirms trust/batch content is present and positioned close to the primary CTA, but does not conclusively confirm the full description-vs-compliance-vs-trust ordering the original finding describes. Classification: **STILL OPEN**, pending a dedicated full-page content-order read of one product template.

### SXO-06 — `/testing/` title/H1 capturing its unique-page-type advantage

`/testing/` raw HTML was fetched (158,316 bytes, real content confirmed present before the gate) but its `<title>` and `<h1>` values were not individually extracted in this pass. No new ledger checkpoint entry between 8/18–8/19 documents a `/testing/` title/H1 change (the checkpoints in that window cover GLP-2T, footer copy, and homepage keyword use only). Classification: carried forward as **STILL OPEN** from "Partially complete" — no evidence of further change found, but also no fresh content-level disconfirmation gathered; a direct title/H1 read is recommended before this is finalized in the main report.

### SXO-07 — bulk/wholesale buyer path

No bulk/wholesale pricing tier, quantity-break pricing, or wholesale-inquiry CTA was observed on any of the five fetched pages (home, product, shop, testing, faq), consistent with the prior "Blocked / input needed" status. This remains a genuine business-offering question, not a code defect: Pep Select would need to decide to offer bulk/wholesale pricing before any page type or CTA path could be built for it. **Informational note, not an execution gap** — no action recommended without an owner decision on whether this offering exists or is planned.

### SXO-08 — gate's binary in/out choice, no low-friction undecided-visitor path

Confirmed directly from the gate markup fetched this session: the gate exposes exactly two terminal actions — the "Enter Site →" button (`id="psagEnter"`, gated behind checkbox/select completion) and the "Not a researcher? Exit" link (`id="psagExit"`, which redirects off-site to `https://google.com` per §VIS-03 above). There is no third path (e.g., "browse without an account," "learn more first," or an on-site informational landing page) for a visitor who is not ready to attest but also doesn't want to leave the site entirely. This is unchanged from the 8/18 state.

---

### Prior Finding Classifications — SXO

| ID | Original Priority | Prior State (8/18) | Current Classification | Evidence |
|---|---|---|---|---|
| SXO-01 | Critical | Not started | STILL OPEN | Raw (non-JS) HTML fetched for 5 pages confirms real page content — hero copy, product data, COA/batch evidence — is present in the HTTP response body well before the gate's `#psag-gate` div, which sits at/near the end of `<body>` on every page tested (e.g., home: content ~30,000+ bytes before gate at byte 150,773 of 160,602). The gate's CSS (`position:fixed;inset:0;z-index:9999999`) is an unconditional overlay, not a server-side content substitution. **Correction to original framing:** content is not "deferred, not delivered" server-side — it is delivered in full, then visually covered client-side. The underlying human-facing problem (100% of first paint blocked, confirmed independently by the Visual specialist's screenshots) remains completely unremediated, so the finding stays open, but any fix should be scoped as a gate-presentation/timing change (W2), not a content-generation fix. |
| SXO-02 | High | Not started | PARTIALLY FIXED | Homepage raw HTML now contains a "Why Pep Select is different" section (new since 8/18 per ledger checkpoints, which only document a homepage keyword-use change as of 8/19). This is genuine differentiation content but is a single homepage section, not a dedicated comparison asset for the "best/cheapest/most trusted" cluster, and has no `/shop/`-level equivalent. |
| SXO-03 | High | Not started | STILL OPEN (limited evidence) | `/faq/` raw HTML fetched and confirmed to contain real pre-gate content, but specific Q&A coverage against the 4 identified PAA slots was not enumerated this session; no ledger checkpoint documents FAQ/PAA-targeted content work in the 8/18–8/19 window. Recommend a direct content read before final closure. |
| SXO-04 | High | Not started | STILL OPEN | `/shop/` raw HTML confirmed to deliver real catalog content pre-gate, but card-level trust-signal density (vs. SERP-winner patterns) was not directly measured this session; no checkpoint documents shop-listing trust-density work. |
| SXO-05 | Medium | Not started | STILL OPEN (partial evidence) | On `/product/nad/`, trust/batch evidence (COA summary, batch carousel) is confirmed positioned immediately after the add-to-cart block — an improvement-consistent pattern — but full ordering vs. the long-form description and product-page compliance disclaimer was not captured this session. |
| SXO-06 | Medium | Partially complete | STILL OPEN | `/testing/` raw HTML confirmed to deliver real content pre-gate, but current `<title>`/`<h1>` values were not extracted this session; no checkpoint documents a title/H1 change in the 8/18–8/19 window. |
| SXO-07 | Medium | Blocked / input needed | BLOCKED BY REAL EVIDENCE | No bulk/wholesale pricing, quantity-break pricing, or wholesale CTA observed on any of the 5 fetched pages. Remains a genuine business-offering decision, not a code gap. |
| SXO-08 | Low | Not started | STILL OPEN | Gate markup confirmed to expose only two terminal actions (`psagEnter` / `psagExit`, the latter redirecting off-site to google.com); no third, lower-friction path exists for an undecided visitor. Unchanged from 8/18. |

### Limitations

- No `claude-seo run render_page.py` execution was possible this session (`claude-seo run` reported "runtime is not ready"); raw-HTML evidence above was instead gathered directly via `curl` with a standard desktop user-agent, saved to local files, and inspected with byte-offset/line search. This satisfies the "raw, non-JS HTTP response" requirement but is not the skill's standard tool path.
- A pre-existing `docs/claude-seo-audit-2026-08-20/raw-crawl/` snapshot (produced earlier this session, `mode_used: raw`, `is_spa: false`) was consulted for cross-checking but its `content`/`extracted_text` fields are truncated to ~500 characters and were not relied on as primary evidence for ordering or completeness claims.
- SXO-03 (FAQ/PAA coverage), SXO-04 (shop card-level trust density), SXO-05 (full description-vs-compliance-vs-trust ordering), and SXO-06 (testing page title/H1) each have partial rather than complete evidence — the raw HTML was fetched and confirmed to contain real pre-gate content in all cases, but a full content-level read against the specific original claims was not completed within this session. This is flagged explicitly in each section above rather than guessed at.
- No paid SERP tooling (DataForSEO/Ahrefs/Semrush) was used, per instructions; no WebSearch sanity-check of current competing SERP page types was performed this session due to time constraints — this is a limitation for benchmarking SXO-02/SXO-04 against current competitor patterns specifically (the prior audit's SERP analysis was relied on for taxonomy/precedent instead).
- No state-changing interaction with the gate was performed (no checkbox checks, no form submission, no cookie manipulation) — consistent with the read-only constraint.

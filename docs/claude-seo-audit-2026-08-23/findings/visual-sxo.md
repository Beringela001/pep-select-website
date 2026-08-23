# Visual + SXO Re-Verification — pepselect.com
Audit date: 2026-08-23 | Claude SEO plugin v2.2.4 | Read-only (Playwright Chromium, live page loads; no site changes, no URL submission)

## Method
- Live Playwright captures at 390x844 (mobile) and 1440x900 (desktop) for `/`, `/shop/`, `/product/nad/`, `/testing/` (8 screenshots total).
- DOM measurement script run in-page via `page.evaluate()` on each load (bounding rects, computed styles, ARIA attributes, `inert`/`aria-hidden` counts, font sizes, exit-link `href`, version-string search).
- Cross-checked against local raw HTML crawl (`docs/claude-seo-audit-2026-08-23/raw-crawl/pages/*.html`, fetched 2026-08-23) for the gate's inline `<style>`/`<script>` source, since the gate markup ships server-side in the initial HTML (confirmed present in `root.html`, `shop.html`, `testing.html`, `product_nad.html`).
- No prior 2026-08-20 audit artifacts were available in this session for direct diffing; comparisons below are against the finding IDs/labels and prior claims provided in the task brief.

---

## 1. Gate accessibility 8-claim table

| # | Claim | Present? | Evidence |
|---|---|---|---|
| 1 | `role="dialog"` on gate container | **YES** | `<div id="psag-gate" role="dialog" aria-modal="true" aria-labelledby="psag-title" aria-describedby="psag-intro">` — present in live-page DOM measurement (all 4 pages x 2 viewports) and in local `root.html`/`shop.html`/`testing.html`/`product_nad.html` source. |
| 2 | `aria-modal` | **YES** | `aria-modal="true"` on same element, same evidence as above. |
| 3 | `aria-labelledby` | **YES** | `aria-labelledby="psag-title"`, pointing to `<h2 id="psag-title">Research Access Verification</h2>` (valid target, ID exists in DOM). |
| 4 | `aria-describedby` | **YES** | `aria-describedby="psag-intro"`, pointing to `<p id="psag-intro">Pep Select provides research-grade compounds...</p>` (valid target, ID exists in DOM). |
| 5 | "Exit" control has a real `href` | **YES** | `<a id="psagExit" href="https://google.com">Exit</a>` — DOM measurement confirms `href: "https://google.com"` on the `<a>` element. (Note: a separate wrapping `<p class="psag-exit">` has no `href`, which is expected/correct — it's not the interactive element.) |
| 6 | Background inerting (`inert` / `aria-hidden`) present somewhere on page | **YES** | Gate's inline script iterates `document.body.children` on open and sets `node.setAttribute('inert','')` + `node.setAttribute('aria-hidden','true')` on every sibling except the gate/script/style. Live measurement: `inertCount` 8–10, `ariaHiddenCount` 21–43 depending on page (header, SVGs, and other body-level elements confirmed inerted, e.g. `<header id="pepselect-site-header" aria-hidden="true">`). `closeGate()` correctly restores prior `inert`/`aria-hidden` state on exit. |
| 7 | Focus-trap / keydown Tab handling | **YES** | Inline script: `gate.addEventListener('keydown', function(event){ if ('Tab' !== event.key) return; ... cycle first/last focusable within gate ... })` — a real Tab-cycle focus trap scoped to `#psag-gate`, plus `requestAnimationFrame` sets initial focus into the gate on open and `closeGate()` restores focus to `<main>` on close via a temporary `tabindex="-1"`. |
| 8 | Gate text ≥16px base font-size | **NO — still fails** | Measured computed font sizes inside `#psag-gate` (mobile+desktop, all 4 pages): `.psag-kicker` 11px, `.psag-type-label` 11px, `.psag-intro` 14px, `.psag-check span` 14px, `.psag-attest-toggle` 11.5px, `.psag-attest ol li` 13.5px, `.psag-version` 10.5px, `.psag-note` 11.5px, `.psag-exit` ~12.5px (from CSS). Only `.psag-title` (22px mobile / 26px desktop) and `<option>` items (15px) clear 16px. Body/legal copy remains 10.5–14px.

**Bottom line: 7 of 8 claims now verified present** (role, aria-modal, aria-labelledby, aria-describedby, exit href, inerting, focus-trap). Only the font-size claim remains failing.

---

## 2. Per-finding-ID status table

| ID | Severity | Claim | Prior status | Current status | Evidence |
|---|---|---|---|---|---|
| VIS-01 | Critical | `#psag-gate` == full viewport, `position:fixed`, `z-index:9999999`, `body{overflow:hidden}`, zero storefront content pre-interaction | STILL OPEN | **STILL OPEN** | Confirmed on all 8 captures: `gateRect` == `{top:0,left:0,width:<viewport>,height:<viewport>}` in every case; computed style `position:fixed; z-index:9999999`; `document.documentElement/body` computed `overflow:hidden` (via `html.psag-open,body.psag-open{overflow:hidden!important}`). Gate is `display:flex`/`visibility:visible` and fully occludes the storefront on first paint on every one of the 4 tested URLs at both viewports. No change from prior. |
| VIS-02 | High | Accessibility sub-claims (aria-describedby, real exit link, focus trap, inerting) not in live markup despite being marked verified | STILL OPEN | **VERIFIED FIXED (7/8 sub-claims)** | See table above. `aria-describedby`, real `href` exit link, background inerting, and keydown focus trap are now all present and functioning as designed; `role`/`aria-modal`/`aria-labelledby` also present. Only the font-size sub-claim (part of VIS-04, tracked separately) remains open — see VIS-04. Recommend re-labeling VIS-02 as fixed and tracking the residual font-size issue solely under VIS-04. |
| VIS-03 | Low | Exit → off-site (`https://google.com`), no on-site alternative; exit anchor has no `href` | STILL OPEN | **PARTIALLY FIXED** | The anchor now has a real `href="https://google.com"` (previously JS-click-only with no `href`, which was itself an accessibility/keyboard-nav bug — that sub-issue is fixed). However the destination is still an off-site Google redirect with no on-site low-friction alternative (e.g., a non-research landing page or homepage-without-gate). The "no on-site alternative" part of the finding is STILL OPEN. |
| VIS-04 | Low | Gate text 10.5–14px, below 16px | STILL OPEN | **STILL OPEN** | Re-measured directly via computed `getComputedStyle(...).fontSize` on every visible leaf text node inside `#psag-gate`: range is 10.5px (`.psag-version`) to 15px (`<option>` text); most body copy sits at 11–14px. No change from prior range. |
| VIS-05 | Medium | Gate's effect on measured performance (not independently re-measured) | STILL OPEN | **STILL OPEN / BLOCKED BY REAL EVIDENCE** | This re-verification pass captured DOM/visual state only; no Lighthouse/PageSpeed/CWV re-measurement was performed (out of scope per task — "no paid APIs" and no `raw-pagespeed` data was regenerated in this session). Directional note: the gate adds one inline `<style>` block, one inline `<script>` block, a full-viewport bubble-animation layer (`requestAnimationFrame`-created `.psag-bubble` elements, up to 14 per load, CSS `animation` running continuously until dismissed), and a synchronous DOM walk over all `document.body.children` on load — none independently benchmarked here. [VERIFY CLAIM] for any specific performance delta. |
| SXO-01 | Critical | Real page content IS delivered in raw non-JS HTML before the gate markup; issue is 100% first-paint blocking for JS-rendering visitors | STILL OPEN (reframed) | **CONFIRMED — STILL OPEN (reframed, unchanged)** | Local raw HTML crawl confirms full storefront content (header, hero, product content, footer) is present in server-rendered HTML on all 46 crawled URLs, positioned *before* the gate's `<style>`/`<div id="psag-gate">`/`<script>` block, which appears late in `<body>` (e.g., in `root.html` the gate script/markup begins ~line 143,650 of a ~158.8K-char document, i.e., after the primary content). Non-JS/HTML-only clients (most crawlers) receive real content; JS-executing browsers get 100% of the viewport occluded by the gate at first paint via the inline script that runs synchronously and sets `inert`/`aria-hidden`/`overflow:hidden` before paint settles. No architecture change since prior audit. |
| SXO-02 | High | "Why Pep Select is different" homepage section exists; no `/shop/`-level equivalent | PARTIALLY FIXED | **UNABLE TO RE-VERIFY VISUALLY THIS PASS — [VERIFY CLAIM]** | The gate occupies the entire viewport at first paint on every page load in this session (see VIS-01), so no post-gate scroll/interaction was performed and neither the homepage trust section nor any shop-page equivalent was visually re-confirmed in the captured screenshots. A raw-HTML text check would be needed to re-verify without dismissing the gate; not performed this pass (task scope was gate/viewport measurement + screenshot capture, not full content diffing). Recommend explicit follow-up. |
| SXO-05 | Medium | On `/product/nad/`, trust/batch evidence positioned after add-to-cart | STILL OPEN | **UNABLE TO RE-VERIFY VISUALLY THIS PASS — [VERIFY CLAIM]** | Same constraint as SXO-02: the gate fully occludes `/product/nad/` at first paint in the captured screenshots (`product-nad-mobile.png`, `product-nad-desktop.png` show only the gate). DOM order was not walked for this specific claim in this session. Not contradicted, simply not re-measured — carry forward prior status. |
| SXO-08 | Low | Gate exposes only Enter / Exit-to-Google, no lower-friction third path | STILL OPEN | **STILL OPEN** | Confirmed via DOM: gate's only two interactive exits are `#psagEnter` (disabled until researcher-type + both checkboxes are set) and `#psagExit` (→ `https://google.com`). No third option (e.g., "browse without confirming," "contact us," or a non-gated informational page) exists in the markup on any of the 4 tested pages. |

---

## 3. Gate version token

**Exact token found: `PS-RUO-2026.08`**

- Rendered in-page: `<p class="psag-version">Form version PS-RUO-2026.08</p>` (inside the collapsible "Read the researcher attestation" panel).
- Also set as a JS variable in the gate's inline script: `var VERSION = "PS-RUO-2026.08";`, used both as the compliance-cookie value (`COOKIE = "71326_cookie"`, cookie value `= COOKIE + '=' + VERSION`) and sent server-side on submit (`data.append('fversion', VERSION)`).
- Confirmed identical across all 4 pages checked (`root.html`, `shop.html`, `testing.html`, `product_nad.html`).
- **The reported "gate v2.1.3" token was NOT found anywhere in the live DOM, inline `<script>`/`<style>` source, or any of the 4 local HTML files searched** (`grep -c "2.1.3"` = 0 hits in `root.html`; no `gate...v[0-9]` pattern matched in any file). A version-string regex sweep of all `<script>` tags and the full HTML document on every live page load also returned zero matches for any `vN.N.N` semver-style token. **[VERIFY CLAIM]: "gate v2.1.3" could not be corroborated — the only version identifier present in the live gate is the compliance form-version string `PS-RUO-2026.08`, which is a date-stamped legal/consent-form revision marker, not a semver release number.** This may indicate the "v2.1.3" label refers to an internal deployment/release-tracking system not exposed in the front-end markup, or it may be a mismatch between the reported claim and what's actually shipped — cannot resolve from client-side evidence alone.

---

## 4. New findings (VIS-06+ / SXO-09+)

- **VIS-06 (Info/Positive):** Gate correctly restores prior `inert`/`aria-hidden` state on close (not just blanket-removing it) — `closeGate()` reads each background node's `hadInert`/`ariaHidden` snapshot taken at open-time and restores it exactly, which is correct defensive accessibility engineering. Also restores focus to `<main>` via a temporary `tabindex="-1"` + `.focus()`, then cleans up the tabindex attribute afterward. No prior finding covered this; noting as a genuine accessibility-engineering positive.
- **VIS-07 (Low):** The gate persists via a first-party cookie (`71326_cookie=PS-RUO-2026.08`) valid for up to 30 days if "Remember me for 30 days" is checked, but if left unchecked, `setCookie(0)` is still called on Enter (session-only cookie, expires on browser close per the `days` param evaluating to 0 → no `expires` set = session cookie). This means the gate will re-appear on the *very next fresh browser session* even after a visitor already completed it once with "remember" unchecked — worth flagging as a friction/UX point, not strictly an accessibility bug.
- **SXO-09 (Info):** The gate also fires a `fetch()` POST to `wp-admin/admin-ajax.php` (`action=psag_record`) with `keepalive:true` on every successful Enter, recording researcher type + form version server-side. This is a compliance-logging mechanism, not a content/SEO issue, but is new information not in the prior report's scope — noting for completeness since it touches the same code path being audited.

---

## 5. Changes since 2026-08-20

1. **Gate accessibility markup substantially rebuilt.** 4 of the 8 previously-missing sub-claims (`role="dialog"`, `aria-modal`, `aria-describedby`, real exit `href`) are now present, on top of the 4 that may have already existed (`aria-labelledby` — status of this specific one at 08-20 was not itemized in the brief but is now confirmed present). Additionally, **background inerting** (`inert` + `aria-hidden`, prior: ZERO matches) and **focus-trap/keydown Tab handling** (prior: none) — both called out in the brief as absent — are now fully implemented via the gate's inline script. This is a real, verifiable accessibility uplift.
2. **Font sizing unchanged.** Gate text remains 10.5–14px (title element only clears 16px), identical range to the prior audit's 10.5–14px finding. VIS-04 remains fully open.
3. **Exit destination unchanged.** Still routes to `https://google.com`; only the *mechanism* changed (real `href` vs. JS-only click), not the *destination logic*. VIS-03 downgraded to PARTIALLY FIXED, not fully fixed.
4. **Gate version identifier does not match the reported "v2.1.3" release label.** The only in-markup/in-script version token is `PS-RUO-2026.08`. This is either a different versioning scheme for the same release, or the "v2.1.3" claim cannot be corroborated from client-visible evidence — flagged as [VERIFY CLAIM].
5. **Full-viewport, first-paint blocking behavior (VIS-01) is unchanged** — gate still 100% occludes the storefront with `position:fixed`, `z-index:9999999`, viewport-matching bounding rect, and `overflow:hidden` on `html`/`body` on all 4 tested pages at both viewports.
6. **Homepage header (logo/phone) — could not be visually re-verified via screenshot** because the gate fully occludes the header at first paint on every capture (`home-desktop.png`, `home-mobile.png` show only the gate card). A DOM/markup check of the header CSS/HTML in the local `root.html` crawl shows: logo present via `#pepselect-site-header .pepselect-header__logo-link` / `.pepselect-header__brand img.custom-logo` (224x72px slot, `PEP SELECT` wordmark + hexagon icon — same logo used inside the gate card itself, visible in both screenshots). **No `tel:` link or phone-number-specific header class was found** in a targeted search of `root.html` (`tel:`, `pepselect-header__phone*`, `href="tel...` all returned zero matches). This is consistent with "no phone number in header" but is a markup-level observation, not a direct visual confirmation, since the gate blocks the header visually. **[VERIFY CLAIM]: cannot confirm any logo/phone drift visually — would require bypassing/dismissing the gate (out of scope for this read-only, no-interaction pass) or comparing to a saved 2026-08-20 header screenshot, which was not available in this session.**

---

## Screenshots saved

All in `docs/claude-seo-audit-2026-08-23/screenshots/`:
- `home-mobile.png` (390x844)
- `home-desktop.png` (1440x900)
- `shop-mobile.png` (390x844)
- `shop-desktop.png` (1440x900)
- `product-nad-mobile.png` (390x844)
- `product-nad-desktop.png` (1440x900)
- `testing-mobile.png` (390x844)
- `testing-desktop.png` (1440x900)

All 8 screenshots show only the research gate card (no storefront content visible), confirming VIS-01 at first paint on every tested page/viewport combination.

Raw measurement data: `docs/claude-seo-audit-2026-08-23/raw-visual/measurements.json` (full DOM/computed-style/accessibility-attribute dump per page x viewport).

---

## Stop conditions / scope notes

- Playwright + Chromium were available and functioned correctly; no capture failures.
- Network access to `pepselect.com` confirmed live (HTTP 200) at session start.
- **SXO-02 and SXO-05 could not be visually re-verified** in this pass because the gate occludes 100% of the viewport at first paint on every capture and no gate-dismissal/interaction was performed (out of scope: "measurement only," "read-only page loads"). Both are marked [VERIFY CLAIM] / carried forward from prior status rather than re-confirmed.
- No performance re-measurement (VIS-05) was performed — no paid APIs, no Lighthouse run in this session; status carried forward as STILL OPEN / BLOCKED BY REAL EVIDENCE.
- The "gate v2.1.3" version claim could not be corroborated anywhere in client-visible markup/script/HTML; the actual token found is `PS-RUO-2026.08`. Flagged as [VERIFY CLAIM].

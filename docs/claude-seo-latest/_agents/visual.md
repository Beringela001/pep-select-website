# Visual / Mobile Rendering Audit — pepselect.com
Date: 2026-08-18
Tooling: claude-seo bundled Playwright scripts (`capture_screenshot.py`, `render_page.py`), plus read-only `GET` fetches of raw HTML for structural verification. Chromium via claude-seo managed runtime confirmed ready (`claude-seo doctor` → Runtime: ready, Chromium: ready).

Viewports tested: Laptop/"Desktop" 1366×768, Mobile 375×812 (device scale factor 2, per tool default).
Pages tested: Homepage (`/`), Product (`/product/bpc157-10/`), Shop/category (`/shop/`), `/testing/`.

All screenshots saved to `C:\Users\paulo\Documents\Pep Select Website\docs\claude-seo-latest\_agents\screenshots\`:
`homepage_laptop.png`, `homepage_mobile.png`, `product_laptop.png`, `product_mobile.png`, `shop_laptop.png`, `shop_mobile.png`, `testing_laptop.png`, `testing_mobile.png`.

## Headline observation
On a fresh browser session (no cookies — the same state as any first-time visitor, and the same state any automated crawler/testing tool would start from), **all four page types render byte-identical screenshots at each viewport**: an "Research Access Verification" gate (`#psag-gate`) that fully occupies the viewport. MD5 checksums confirm `homepage_laptop.png` = `product_laptop.png` = `shop_laptop.png` = `testing_laptop.png` (`965048a2...`), and the same for all four `*_mobile.png` (`715a6e30...`). This is the dominant visual/UX fact for this audit and is documented in detail below.

Raw-HTML inspection (GET only, no form submission) confirms the gate is server-rendered (present in the initial HTML response, not injected only by client JS) on all four URLs, and that real page content (H1s, nav, Elementor markup) exists in the DOM behind it:
- `/` → H1: "The label is the easy part. / What's behind it matters."
- `/product/bpc157-10/` → H1: "BPC-157"
- `/shop/` → H1: "Selection is the standard."
- `/testing/` → H1: "Every batch has a permanent address."

Because the gate blocks all rendered pixels pre-interaction, **true above-the-fold hero/CTA layout, image rendering, and any visually-observable layout shift for the real page templates could not be assessed from screenshots** — see Data Sources & Limitations. Structural facts below are taken from raw HTML where a screenshot could not confirm rendering.

---

### [VIS-01] Mandatory full-viewport "Research Gate" blocks 100% of above-the-fold content on every page, on every device, before any interaction
- Priority: Critical
- Category: Above-the-fold / Intrusive interstitial
- Evidence class: [5-Crawler observation/inference]
- Evidence: Screenshots `homepage_laptop.png`, `homepage_mobile.png`, `product_laptop.png`, `product_mobile.png`, `shop_laptop.png`, `shop_mobile.png`, `testing_laptop.png`, `testing_mobile.png` are pairwise byte-identical per viewport (MD5 `965048a25ea7bc6b2b2b260cd4ffcd1b` for all four laptop captures, `715a6e30d850489fa5fdffb3edf4cc49` for all four mobile captures) — i.e. the visible viewport is the gate, not the page, on every template tested. Raw HTML confirms `<div id="psag-gate" role="dialog" aria-modal="true">` with CSS `position:fixed;inset:0;z-index:9999999;` and `html.psag-open,body.psag-open{overflow:hidden!important;}`, present in the initial server response on all four URLs. The gate requires: selecting a "Researcher type" from a dropdown, checking two checkboxes ("21 years of age", "qualified researcher... not for human or veterinary use"), then clicking "Enter Site" before any real page content becomes visible or scrollable.
- Affected URLs: `https://pepselect.com/`, `https://pepselect.com/product/bpc157-10/` (representative product template), `https://pepselect.com/shop/`, `https://pepselect.com/testing/` — mechanism is theme/plugin-level (injected via shared footer include), so it is reasonable to infer it applies sitewide to all templates, not just the four sampled.
- Reasoning: No primary H1, value proposition, hero image, navigation, or CTA is visible or reachable without first completing a 4-step form. This is the textbook definition of an intrusive interstitial from a UX standpoint, and it applies uniformly across content, commerce, and compliance pages alike — a first-time visitor arriving via a shop-page or product-page ad/search click sees the same generic gate as someone landing on the homepage, with zero page-specific context to justify continuing.
- Recommendation: Re-scope the gate to the minimum legally-necessary confirmation (e.g., a single age/RUO acknowledgment) and consider a lighter-weight, dismissible banner pattern for the "researcher type" marketing/segmentation question rather than bundling it into a mandatory full-screen wall. If the full gate must remain for compliance reasons, ensure it is visually clear on first paint (which it is) but reconsider requiring a dropdown selection (which is not an age/legal check) as a hard blocker to entry — that field could be optional or deferred to checkout. Time-box a UX review of drop-off rate at this gate specifically (see Leading indicator).
- Dependencies: Depends on legal/compliance sign-off for what the gate is required to collect (age, RUO acknowledgment) versus what is discretionary (researcher type, "remember me" duration). Unblocks: more accurate above-the-fold/CWV/CTA-visibility testing (see VIS-05), and any conversion-rate-optimization work on hero sections, which is currently invisible to first-time visitors.
- Failure check: Screenshots or CrUX/PSI field data captured with a fresh (no-cookie) session continue to show the gate as 100% of the viewport with no real content visible.
- Success check: A fresh, no-cookie screenshot of each template shows real page content (H1, nav, hero, CTA) either fully visible or partially visible behind/instead of a lighter, less blocking confirmation UI; time-to-first-real-content interaction decreases.
- Leading indicator: Gate completion/exit ratio in analytics (e.g., "Enter Site" clicks vs. "Exit" clicks vs. no interaction/bounce) tracked as a funnel step; bounce rate on landing pages compared before/after any gate redesign.

### [VIS-02] Interstitial gate has no focus-trap or `inert`/`aria-hidden` on background content
- Priority: High
- Category: Accessibility (keyboard & screen reader) tied to interstitial
- Evidence class: [5-Crawler observation/inference]
- Evidence: Raw HTML shows the gate is marked `role="dialog" aria-modal="true"`, but the underlying page wrapper is not given `inert` (0 occurrences of the `inert` attribute in the homepage HTML) or `aria-hidden="true"` (the 13 `aria-hidden` occurrences found on the homepage are pre-existing icon/decoration attributes elsewhere in the page, not applied to a background wrapper when the gate is open). The gate's own control script (the block containing `COOKIE`, `setCookie`, `closeGate`, checkbox/select `change` listeners) contains no `keydown`, `focus(`, or `Tab`-key handling — i.e., no scripted focus trap.
- Affected URLs: `https://pepselect.com/`, `https://pepselect.com/product/bpc157-10/`, `https://pepselect.com/shop/`, `https://pepselect.com/testing/` (sitewide mechanism).
- Reasoning: `aria-modal="true"` tells assistive technology that everything outside the dialog should be treated as inert, but without an actual `inert` attribute (or `aria-hidden` + real focus trap) on the background, keyboard users tabbing through the page and screen-reader users in browse/virtual-cursor mode can still reach and activate background navigation links, buttons, and forms that are visually hidden behind the modal (`overflow:hidden` on `html`/`body` prevents scrolling to see them, but does not prevent focus from landing on them). This is a WCAG 2.1 SC 2.4.3 (Focus Order) / 4.1.2 (Name, Role, Value) conformance gap common to hand-rolled modals.
- Recommendation: Describe (do not implement) that the gate script should apply `inert` (or `aria-hidden="true"` plus a scripted Tab-key trap that cycles focus within `#psag-gate`) to the main content wrapper for the duration the gate is open, and move initial focus into the dialog (e.g., the "Researcher type" select) when it opens.
- Dependencies: Depends on VIS-01 remediation scope (any redesign of the gate should include this fix in the same change). Does not block other findings.
- Failure check: Manual keyboard test (Tab key) from page load reaches links/buttons that are not part of `#psag-gate` while the gate is still open.
- Success check: Tab key cycles only within the gate's interactive elements until it is dismissed; screen reader announces only the dialog's content.
- Leading indicator: Accessibility scanner (axe/Lighthouse a11y audit) flags on this specific page pattern trend toward zero over time.

### [VIS-03] "Not a researcher? Exit" sends visitors off-site to google.com with no informational alternative
- Priority: Low
- Category: UX / conversion, tied to interstitial
- Evidence class: [5-Crawler observation/inference]
- Evidence: Gate control script defines `var EXIT = "https://google.com";` and an "Exit" link/button is rendered next to the "Not a researcher?" prompt in the gate card (visible in `homepage_laptop.png` and `homepage_mobile.png`).
- Affected URLs: Sitewide (same shared gate component).
- Reasoning: Any visitor who is unsure, under-informed, or simply misclicks the "Exit" affordance is removed from the site entirely with no compliant landing page, FAQ, or explanation of why they were redirected — a hard, unrecoverable exit rather than a soft decline state. This forecloses any chance of that visitor returning to complete the gate later in the same session and removes any opportunity to capture why they declined.
- Recommendation: Describe (not implement) routing "Exit" to an on-site informational/compliance page explaining the research-use-only policy, rather than an off-domain redirect, or at minimum to a neutral non-competitor destination.
- Dependencies: Independent of VIS-01/VIS-02; can be addressed separately.
- Failure check: Clicking "Exit" continues to navigate to `google.com` (or any off-domain destination) with no explanation shown first.
- Success check: "Exit" leads to an on-site page or a confirmation step before leaving.
- Leading indicator: Exit-click volume relative to total gate impressions, tracked in analytics.

### [VIS-04] Legal/disclaimer copy inside the gate renders below common mobile-readability guidance (~16px)
- Priority: Low
- Category: Mobile usability / readability
- Evidence class: [5-Crawler observation/inference]
- Evidence: Gate CSS sets `.psag-legal p{font-size:10.5px;...}`, `.psag-address,.psag-copy{font-size:10.5px;...}`, `.psag-remember{font-size:13px;...}`, `.psag-attest ol li{font-size:13.5px;...}`, and checkbox label text `.psag-check span{font-size:14px;...}`. All are below the ~16px base-font guidance commonly used to avoid mobile pinch-zoom, though this is stylistically typical for fine-print/legal disclaimers.
- Affected URLs: Sitewide (same shared gate component); most visible in `homepage_mobile.png` where the bottom disclaimer paragraph and copyright line are noticeably small relative to the heading/button.
- Reasoning: The disclaimer text carries the actual compliance/legal weight of the gate ("By proceeding, you confirm... not for human consumption, diagnosis, treatment, or prevention of any disease..."), so it is the one section of the modal where under-sized type is most consequential — a visitor could accept the gate's legal terms without comfortably reading them on a phone.
- Recommendation: Describe (not implement) increasing the legal/disclaimer paragraph and copyright line to at least 13–14px on mobile, and the primary checkbox/attestation copy to 16px where layout allows, without changing the overall card proportions.
- Dependencies: Independent, low-effort styling change; can ship alongside VIS-01/VIS-02 or separately.
- Failure check: Disclaimer/copyright text remains at ~10.5px on mobile captures.
- Success check: Updated screenshot shows disclaimer text at a legible size without requiring pinch-zoom.
- Leading indicator: None specific to analytics; verify via re-screenshot only.

### [VIS-05] Synthetic performance/CWV tooling (PageSpeed Insights, Lighthouse, and Googlebot's rendering pass) will measure the gate, not the real page
- Priority: Medium
- Category: Above-the-fold / Core Web Vitals measurement integrity
- Evidence class: [5-Crawler observation/inference]
- Evidence: The gate is present in the raw server-rendered HTML (not client-injected after load) and is paint-eligible immediately: its logo `<img ... loading="eager">` and the "Research Access Verification" heading are the first large, above-the-fold content painted for any session without the `71326_cookie` cookie. Any Lighthouse/PageSpeed Insights run, and any Googlebot rendering pass, starts cookie-less by default.
- Affected URLs: Sitewide — homepage, product, shop, and `/testing/` all confirmed to serve the same gate on cold requests.
- Reasoning: Largest Contentful Paint, Cumulative Layout Shift, and above-the-fold visual-completeness metrics captured by lab tools (and potentially CrUX field data for a meaningful share of real first-time sessions) will reflect the gate's logo/heading/card, not the actual homepage hero, product hero image, or shop grid. This means prior or future LCP/CLS optimization work on the real templates can be masked or misrepresented by metrics that are actually measuring the gate, and any performance regression in the real templates could go undetected if testing tools are only ever hitting the gated state.
- Recommendation: Describe (not implement) a testing protocol that accounts for this: run performance/CWV audits both against the gated (cookie-less) state and against a state with the `71326_cookie` consent cookie pre-set, so real-template performance is measured separately from gate performance. Document which of the two states any given CWV report reflects.
- Dependencies: Unblocks more accurate future performance audits; depends on whichever tool/harness is used supporting cookie pre-seeding for a "post-gate" test pass.
- Failure check: Future PSI/Lighthouse/CWV reports for this domain do not specify which state (gated vs. post-gate) was measured, or continue to report the gate's own logo/heading as the LCP element without flagging it.
- Success check: Audit reports going forward explicitly separate "first-visit / gated" and "returning-visitor / post-gate" performance results.
- Leading indicator: LCP element identified in PSI/Lighthouse reports for a cookie-less run — currently expected to be the gate logo or heading rather than a homepage/product hero element.

## Verified Correct
- `<meta name="viewport" content="width=device-width, initial-scale=1">` is present and correctly configured on the homepage, enabling proper mobile scaling.
- No horizontal overflow or element clipping was observed in any of the eight captured screenshots (laptop 1366px and mobile 375px) — the gate card itself is fully responsive and centers correctly at both widths.
- No separate/stacked cookie-consent banner (checked for CookieYes, Complianz, CookieLaw, generic GDPR banner markers) was found layered on top of the research gate — visitors face one modal, not two.
- The gate's decorative floating-bubble animation correctly respects `@media (prefers-reduced-motion:reduce)` (animation disabled for users who request reduced motion), and the animation uses `transform`/`opacity` only (no layout-triggering properties), so it should not contribute to Cumulative Layout Shift.
- Real page content (H1, navigation, Elementor sections) is present in the initial server-rendered HTML response behind the gate for all four sampled URLs (confirmed via `GET` fetch), meaning the gate does not appear to strip content from the DOM outright — the primary risk identified here is visual/UX blocking (VIS-01/VIS-05), not content removal.
- Gate touch targets: the "Enter Site" button (`padding:16px 0`, full width) and each checkbox row (`.psag-check`, full-width flex label with `padding:15px 2px`) both compute to well over 48px tap-target height based on CSS inspection, despite the visual checkbox icon itself being small (19×19px) — the clickable label area is what matters and it is generously sized.

## Data Sources & Limitations
- Screenshot capture used the bundled `claude-seo run capture_screenshot.py` tool (Playwright + Chromium, confirmed ready via `claude-seo doctor`) at `laptop` (1366×768) and `mobile` (375×812) presets, with fresh (no-cookie) browser contexts per the tool's default behavior — this matches a genuine first-time-visitor state, which is the correct state to audit for above-the-fold/interstitial purposes.
- Because every page tested returned the same full-viewport gate in a cookie-less session, **the true above-the-fold hero layout, real navigation, product imagery/rendering, and any layout shift of the actual homepage/product/shop/testing templates could not be captured or visually verified in this pass.** This is a direct consequence of the site's own gate mechanism, not a tooling failure — the tool correctly captured what a first-time visitor sees.
- This audit is strictly read-only/GET-only per instructions; the gate's "Enter Site" action triggers a client-side cookie write and (per the script's `AJAX`/`RECORD` variables) likely an `admin-ajax.php` POST to log consent, so the gate was intentionally **not** submitted/clicked through, and no cookie was injected to bypass it, to avoid any state-changing request to the live site. Consequently, screenshots of the real templates' above-the-fold content are not available from this pass; a follow-up pass with explicit authorization to pre-seed a client-side cookie (no POST required, since the check is `document.cookie`-based) could capture the real templates without ever submitting the form.
- Underlying-page facts referenced above (H1 text, presence of nav/Elementor markup, absence of a separate cookie banner, viewport meta tag) were confirmed via plain `GET` fetch of raw HTML (`urllib`), not rendered screenshots, and are noted as such.
- Product page sampled: `/product/bpc157-10/`, chosen as a representative product template discovered via the `/shop/` page's product links; other product templates were not individually screenshotted but share the same gate mechanism per the shop/testing/homepage cross-check.
- No JavaScript execution issues, console errors, or network waterfall were inspected (out of scope for this visual pass); only rendered pixel output and raw HTML/CSS were analyzed.

## Category Score: 38/100
A mandatory, non-dismissible, full-viewport interstitial with no background inerting/focus-trap currently determines 100% of the first-visit above-the-fold experience on every page type (Critical, VIS-01), compounded by an accessibility gap in how it's implemented (VIS-02) and a measurement-integrity risk for all future Core Web Vitals/PageSpeed audits (VIS-05); the two Low-priority items (VIS-03, VIS-04) are minor by comparison. Score reflects that the single most important visual/UX fact for this site — what a first-time visitor actually sees — is currently a compliance gate rather than the product/value proposition, on every template tested.

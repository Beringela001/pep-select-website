# M10 Phase 1 — Pre-Launch Audit

Audit only. No code changed, nothing committed, no ZIP built. All live checks were run logged-out with `curl` against production `https://pepselect.com` (the apex; `www` 301-redirects to it) on 2026-07-23. Theme reviewed at commit on `web-2c-homepage` (0.18.0-beta.3).

Severity legend: **HIGH** blocks launch or breaks a page · **MEDIUM** should fix before launch · **LOW** cosmetic or nice-to-have.

---

## 1. Elementor retirement survey

For all 18 routes: HTTP 200 except `/documentation/` (404). **No route carries a `data-elementor-type` attribute**, and the only `elementor-widget` string on any page is a CSS selector inside a `<style>` block (`.elementor-widget-woocommerce-product-price .price`), not content markup. So **Elementor's Theme Builder renders content on none of these routes.** Elementor and Elementor Pro remain active as plugins and still enqueue global CSS/JS on every page (e.g. `elementor/assets/css/frontend.min.css`, `elementor-pro` widget CSS, and `uploads/elementor/css/post-79.css` for the homepage).

| Route | Renders it (owner) | `data-elementor-type` | page-template class | Elementor content? | Verdict |
|---|---|---|---|---|---|
| `/` | theme `inc/homepage-preview.php` (template_include@99, `is_front_page`) | none | page-template-elementor | No | **RETIREMENT CANDIDATE** |
| `/shop/` | theme `inc/archive-compounds.php` (`is_shop`) | none | – | No | **RETIREMENT CANDIDATE** |
| `/testing/` | **PS-COA plugin** (`ps-coa-*` markup) — not theme, not Elementor | none | – | No | Plugin-owned (see below) |
| `/faq/` | theme `page-faq.php` (slug template) | none | page-template-default | No | **RETIREMENT CANDIDATE** |
| `/contact/` | theme `page-contact.php` | none | page-template-default | No | **RETIREMENT CANDIDATE** |
| `/military-discount/` | theme `page-military-discount.php` | none | page-template-default | No | **RETIREMENT CANDIDATE** |
| `/track-your-order/` | theme `page-track-your-order.php` | none | page-template-default | No | **RETIREMENT CANDIDATE** |
| `/terms-conditions/` | theme `inc/legal-pages.php` (template_include@99) | none | page-template-elementor | No | **RETIREMENT CANDIDATE** |
| `/privacy-policy/` | theme `inc/legal-pages.php` | none | page-template-elementor | No | **RETIREMENT CANDIDATE** |
| `/ruo-disclaimer/` | theme `inc/legal-pages.php` | none | page-template-elementor | No | **RETIREMENT CANDIDATE** |
| `/refund-shipping-policy/` | theme `inc/legal-pages.php` | none | page-template-elementor | No | **RETIREMENT CANDIDATE** |
| `/cart/` | WooCommerce block (`wc-block-cart`) + theme CSS | none | page-template-default | No | WooCommerce-owned |
| `/checkout/` | WooCommerce / Fluid Checkout (redirects to `/cart/` when empty) | none | page-template-default | No | WooCommerce-owned |
| `/my-account/` | WooCommerce + theme `woocommerce/myaccount/*` overrides | none | page-template-default | No | WooCommerce-owned |
| `/product/glp3-r30/` | theme `inc/single-product.php` (`is_product`) | none | – | No | **RETIREMENT CANDIDATE** |
| `/product/pt-141/` | theme `inc/single-product.php` | none | – | No | **RETIREMENT CANDIDATE** |
| `/product/tb500-10/` | theme `inc/single-product.php` | none | – | No | **RETIREMENT CANDIDATE** |
| `/documentation/` | nothing — 404 | none | – | No | Phantom (see item 2) |

- **RETIREMENT CANDIDATES (theme demonstrably owns rendering, Elementor absent from content):** `/`, `/shop/`, all three `/product/*`, `/faq/`, `/contact/`, `/military-discount/`, `/track-your-order/`, and the four legal pages. **Recommendation:** these are safe to migrate off Elementor from a content standpoint. **Severity LOW** (they already render coded).
- **DO NOT TOUCH — Elementor still renders content:** **none.** No audited route depends on Elementor for content.
- **`/testing/` is owned by the PS-COA plugin**, not the theme and not Elementor. It renders the Quality Archive (`ps-coa-archive-hero`, `ps-coa-compound-card`, `ps-coa-search`). Do not treat it as a theme retirement item, and confirm it has no Elementor CSS dependency before deactivating Elementor. **Severity MEDIUM** (verify before plugin removal).
- **Caveat on actually deactivating Elementor:** content is fully migrated, but (a) the homepage (post 79) and the four legal pages still have the *Elementor Canvas* page template attribute (`page-template-elementor`), and (b) Elementor CSS is enqueued globally. The theme's `template_include` at priority 99 already wins regardless of the stored template, so rendering should survive deactivation — but this must be verified on staging first, page by page, including `/testing/`, `/cart/`, `/checkout/`, `/my-account/`. **Severity MEDIUM** — deactivation is a staging test, not a blind switch.

---

## 2. Unknown route `/documentation/`

- **Status: 404.** Nothing renders it; it is not a published page.
- **It is not linked anywhere.** No `href` to `/documentation/` exists in the live homepage, in any of the 18 fetched page bodies (footer and header are global, so a link there would have appeared), or in the repo. The word "documentation" appears only in homepage **copy and aria-labels** (`hero.php`, `final-cta.php`, `why-pep-select.php`, and the homepage FAQ), and every one of those buttons points to `home_url( '/testing/' )`, not `/documentation/`.
- **It does not duplicate `/testing/` because it does not exist** — `/testing/` is the live Quality Archive; `/documentation/` is a dead slug with no page and no inbound link.
- **Recommendation: no action required.** The premise that `/documentation/` is linked from the homepage is stale — the current build already routes "batch documentation" to `/testing/`. Optionally add a 301 `/documentation/ → /testing/` at the server for any old external/inbound links, but nothing in this site references it. **Severity LOW.**

---

## 3. Internal link sweep (header + footer + hardcoded repo URLs)

Sources: `template-parts/header/`, `template-parts/footer/`, `inc/header-preview.php`, `inc/footer-preview.php`, `page-*.php`. Every internal destination fetched logged-out.

| Link | Status |
|---|---|
| `/` , `/shop/` , `/testing/` , `/faq/` , `/contact/` , `/military-discount/` , `/track-your-order/` | 200 |
| `/terms-conditions/` , `/privacy-policy/` , `/ruo-disclaimer/` , `/refund-shipping-policy/` | 200 |
| `/my-account/` , `/my-account/cash-back/` , `/my-account/orders/` | 200 |
| `/product/glp3-r30/` , `/product/pt-141/` , `/product/tb500-10/` | 200 |
| `/cart/` , `/checkout/` (→ `/cart/` when empty) | 200 |

- **No non-200 internal links. No slug that fails to resolve to a published page.** **Severity: none / PASS.**
- **Minor inconsistency:** the footer "Track your order" link points to `/my-account/orders/` (`pepselect_child_get_track_order_url()` → WC orders endpoint), **not** to the coded `/track-your-order/` page, which exists and is theme-owned but is unlinked from global chrome. Both are 200; decide whether the dedicated page should be linked or removed. **Severity LOW.**
- **Note:** WordPress feeds (`/feed/`, `/comments/feed/`) are advertised in the homepage `<head>` (WP default), not content links. Consider disabling feeds for a storefront as launch hygiene. **Severity LOW.**

---

## 4. Known defect — military VerifyPass button

Live markup of the button on `/military-discount/`:

```html
<div class="pepselect-military__verify">
  <p><button onclick="window.open('https://verifypass.com/auth/7531055dfc', 'VerifyPass', 'width=475,height=700');">
    <br /> Verify With VerifyPass<br />
  </button></p>
</div>
```

- **The described leftover inline styles are not present in the live HTML.** The button has **no `style` attribute** — only an `onclick`. The string `#00AADD` appears zero times on the page. The reported "lighter blue / 8px corners / 45px height" inline styles are not being served.
- **No CSS rule is "losing."** `assets/css/military.css` (lines 142–164) styles `.pepselect-military__verify button` with `!important` on `background` (brand cyan), `border-radius` (pill), `height:auto`, `min-height:0`, padding, and font. An `!important` stylesheet rule beats a non-`!important` inline style, so even if an inline color/radius/height returned, the override would still win. The `<p>` wrapper and `<br/>` line breaks are already neutralised (`.pepselect-military__verify p { margin:0 }`, `white-space:nowrap`, `line-height:1`).
- **Recommendation:** no code fix indicated by the served HTML — the button should already render as the cyan pill. Confirm visually in a browser on the live/staging page. If it still looks off-brand there, the cause is **not** inline styles (they are absent) — capture a screenshot and the computed style, because it would then be something else (a VerifyPass-injected stylesheet, or a stale page cache). Do not add more `!important`; the existing override is already comprehensive. **Severity LOW** (likely already correct; premise appears outdated).

---

## 5. Responsive / console pass (static CSS review)

Scanned each coded page's CSS for fixed pixel widths, element `min-width` above 320px, `100vw`/`vw` widths, `overflow-x`, and `white-space:nowrap` on containers. This is a static read, not a browser test.

- **homepage.css, archive.css, faq.css, contact.css, checkout.css, footer.css, foundations.css:** no fixed-width or overflow risks found. **PASS.**
- **account.css:817** — `.pepselect-login { width:100vw; margin-left:calc(50% - 50vw); … }`. Correct full-bleed pattern with compensating negative margins. On Windows with a visible scrollbar, `100vw` can produce a few px of horizontal scroll (a known quirk of the pattern). **Severity LOW.** Recommendation: if any horizontal jiggle appears on the login page, swap to `width:100%` on a full-width parent or clamp with `overflow-x:hidden` on the band's wrapper.
- **account.css:1248/1297** — `width:110px` / `140px` on the referral copy-field and coupon inputs. Below 320px, harmless. **PASS.**
- **product.css:428** — `white-space:nowrap` on the short "View all compounds" link. Short label; no overflow. **PASS.**
- **military.css:160** — `white-space:nowrap !important` on the VerifyPass button text ("Verify With VerifyPass"). Fits within 320px at the given padding. **Severity LOW** — verify on a 320px viewport; if it clips, allow wrapping at the smallest breakpoint.
- **cards.css:349** — `width:94vw` inside `@media (max-width:480px)` on the notify dialog. Responsive and intentional. **PASS.**
- **header.css:24/36 (224px), 514/577/677 (logo at breakpoints)** — fixed logo widths, all ≤224px and reduced at each breakpoint. **PASS.**

Overall responsive risk is **LOW**. No container forces a width wider than the viewport outside the deliberate full-bleed pattern; no element `min-width` above 320px was found (the `min-width` hits are all media-query breakpoints).

---

## 6. Launch hardening

- **Indexability — PASS.** `robots.txt` disallows only `/wp-admin/` and WooCommerce transient/log/upload paths and `?add-to-cart=` URLs; the Yoast block is `Disallow:` (empty = allow all); `Sitemap: https://pepselect.com/sitemap_index.xml` is present. The homepage carries **no `noindex` meta**. The site is indexable. **Severity: none.**
- **Staging URL — PASS.** `stg-pepselect-staging.kinsta.cloud` (and `kinsta.cloud`) appears **nowhere in the theme repo** and in **none** of the 18 fetched live page bodies. **Severity: none.**
- **Debug / leftover code — PASS.** No `console.log`, `var_dump`, `error_log`, `print_r(`, `dd(`, `die(`, `wp_die(`, `TODO`, `FIXME`, `XXX`, `HACK`, or `DEBUG` markers in any theme `.php` or `.js` file. The earlier `PEPSELECT_CASHBACK_DEBUG` build was removed in 0.16.0-beta.14 and is confirmed gone. **Severity: none.**

---

## Prioritised fix list

Nothing here blocks launch. In priority order:

1. **MEDIUM — Verify Elementor deactivation on staging** before removing the plugin: load all 18 routes plus `/testing/`, `/cart/`, `/checkout/`, `/my-account/` with Elementor deactivated and confirm the coded templates still render and no page relied on Elementor's global CSS. Content is already fully migrated; this is a controlled staging test, not a blind switch. (Item 1)
2. **MEDIUM — Confirm `/testing/` (PS-COA plugin) has no Elementor CSS dependency** as part of that same staging pass. (Item 1)
3. **LOW — Decide the `/track-your-order/` page's fate:** either link it from the footer instead of `/my-account/orders/`, or retire the coded page. (Item 3)
4. **LOW — Optional 301 `/documentation/ → /testing/`** for any old inbound links; the site itself references nothing there. (Item 2)
5. **LOW — Browser-verify the military VerifyPass button and the login-band full-bleed** at 320px and on Windows; no code change is indicated by the static/served evidence. (Items 4, 5)
6. **LOW — Launch hygiene:** consider disabling WordPress feeds (`/feed/`, `/comments/feed/`) on the storefront. (Item 3)

### Method notes / limitations
- All live checks were logged-out `curl` against production. Logged-in states (account dashboard, populated cart, the YITH cart pill, the completed-order email) were not exercised.
- The static CSS review is not a rendered-viewport test; the LOW responsive items should be confirmed in a browser.
- "Owner" was determined by cross-referencing the served HTML markers against the theme's `template_include` seizures, slug templates, and WooCommerce overrides.

# PepSelect Brand Extract

Verbatim extraction from the `Beringela001/pep-select-website` repository, branch `web-2c-homepage`,
theme version `0.19.0-beta.8`. Extracted 2026-08-01.

Everything below is copied exactly from the repository. Nothing is summarised, reworded, or
inferred. Where a requested value does not exist in the repository, it is marked `NOT FOUND`.

## Scope and access limits (read this first)

This repository contains **only the WordPress child theme** (`pepselect-child/`) plus planning docs.
It is not a full WordPress install. The following are therefore outside what can be extracted:

- **No database, no `wp-config.php`, no WP-CLI, no MySQL client.** Verified absent.
- **The live site is unreachable from this environment.** The network policy denies
  `www.pepselect.com` (`CONNECT tunnel failed, response 403`), so the WooCommerce REST API and
  Store API could not be queried. This directly blocks §4.
- **The COA Archive plugin is not in this repository.** The theme only bridges to it read-only.
  Batch numbers, purity, lab names, and test dates are owned by that plugin. See §2.
- **The parent theme (`hello-elementor`) is not in this repository.**

| Source checked | Result |
| --- | --- |
| `tailwind.config.*` | NOT FOUND |
| `theme.json` | NOT FOUND (neither root nor `pepselect-child/`) |
| SCSS variable files | NOT FOUND (no `.scss` anywhere) |
| `tokens.*` / `design-system/` directory | NOT FOUND |
| `package.json` | NOT FOUND |
| CSS custom properties in global stylesheet | **FOUND** — `pepselect-child/assets/css/foundations.css` |
| Active theme `style.css` | **FOUND** — metadata only, no styles |
| Active theme `functions.php` | **FOUND** — loader only, no tokens |

**The single source of truth for design tokens is `pepselect-child/assets/css/foundations.css`.**

---

# 1. Design tokens

## 1.1 Token source, verbatim

`pepselect-child/assets/css/foundations.css` (complete file):

```css
/**
 * Pep Select design foundations.
 *
 * Version: 0.4.0-beta.3
 * Scope: approved WEB-2A tokens only. Existing templates are not restyled here.
 */

:root {
	/* Brand and semantic color primitives. */
	--pep-color-navy: #002A53;
	--pep-color-dark-navy: #001D3A;
	--pep-color-cyan: #17A1CF;
	--pep-color-green: #16834A;
	--pep-color-amber: #B46A00;
	--pep-color-red: #C43D3D;
	--pep-color-ink: #13283D;
	--pep-color-slate: #5E6F80;
	--pep-color-neutral: #7A8793;
	--pep-color-border: #D7E1E9;
	--pep-color-surface: #F3F8FC;
	--pep-color-soft-gray: #F5F6F7;
	--pep-color-white: #FFFFFF;
	--pep-color-cyan-soft: #E8F6FB;
	--pep-color-green-soft: #EAF5EF;
	--pep-color-amber-soft: #FFF4DF;
	--pep-color-red-soft: #FBECEC;

	/* Typography roles. Approved fonts are not bundled by this theme. */
	--pep-font-editorial: Georgia, "Times New Roman", Times, serif;
	--pep-font-interface: "Plus Jakarta Sans", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
	--pep-font-technical: "IBM Plex Mono", "SFMono-Regular", Consolas, "Liberation Mono", monospace;
	--pep-font-weight-regular: 400;
	--pep-font-weight-medium: 500;
	--pep-font-weight-semibold: 600;
	--pep-font-weight-bold: 700;

	/* Layout. */
	--pep-content-max-width: 1200px;
	--pep-gutter-desktop: 32px;
	--pep-gutter-tablet: 24px;
	--pep-gutter-mobile: 20px;
	--pep-layout-gutter: var(--pep-gutter-desktop);

	/* Shape. */
	--pep-radius-small: 8px;
	--pep-radius-medium: 12px;
	--pep-radius-large: 20px;
	--pep-radius-pill: 999px;

	/*
	 * Homepage hero image framing controls.
	 * Adjust these values to reframe the hero image without touching any template:
	 *   --pep-hero-image-fit:      cover (fills, may crop) or contain (shows all).
	 *   --pep-hero-image-position: horizontal% vertical% focal point, e.g. 60% 40%.
	 *   --pep-hero-image-zoom:     1 = normal; 1.15 = zoom in 15%.
	 */
	--pep-hero-image-fit: cover;
	--pep-hero-image-position: 50% 50%;
	--pep-hero-image-zoom: 1;

	/* Motion. Components may opt in; shrink interactions are not permitted. */
	--pep-motion-duration: 180ms;
}

@media (max-width: 1024px) {
	:root {
		--pep-layout-gutter: var(--pep-gutter-tablet);
	}
}

@media (max-width: 767px) {
	:root {
		--pep-layout-gutter: var(--pep-gutter-mobile);
	}
}

@media (prefers-reduced-motion: reduce) {
	:root {
		--pep-motion-duration: 0.01ms;
	}
}
```

## 1.2 Colours, with hex and actual usage

Usage counts are every `var(--token)` reference across all 14 stylesheets in
`pepselect-child/assets/css/`. No token is unused.

| Variable | Hex | Uses | Properties | Files (count) |
| --- | --- | --- | --- | --- |
| `--pep-color-navy` | `#002A53` | 121 | color, background, accent-color | account.css(35), cards.css(17), product.css(14), homepage.css(12) |
| `--pep-color-dark-navy` | `#001D3A` | 15 | color, background | homepage.css(5), header.css(4), footer.css(2), bisn-form.css(1) |
| `--pep-color-cyan` | `#17A1CF` | 103 | color, border-color, background | account.css(33), checkout.css(11), product.css(11), homepage.css(9) |
| `--pep-color-green` | `#16834A` | 8 | color, background, border | account.css(4), contact.css(2), cards.css(1), product.css(1) |
| `--pep-color-amber` | `#B46A00` | 9 | color, border, border-left | checkout.css(6), product.css(2), cards.css(1) |
| `--pep-color-red` | `#C43D3D` | 5 | color, border | contact.css(2), account.css(1), cards.css(1), product.css(1) |
| `--pep-color-ink` | `#13283D` | 17 | color | account.css(4), header.css(3), legal.css(3), checkout.css(2) |
| `--pep-color-slate` | `#5E6F80` | 58 | color, border-color | account.css(26), product.css(8), cards.css(4), contact.css(3) |
| `--pep-color-neutral` | `#7A8793` | 15 | color | product.css(6), account.css(5), cards.css(1), checkout.css(1) |
| `--pep-color-border` | `#D7E1E9` | 75 | border, border-bottom, border-top | account.css(31), product.css(8), cards.css(7), header.css(6) |
| `--pep-color-surface` | `#F3F8FC` | 26 | background | account.css(8), header.css(4), product.css(4), cards.css(3) |
| `--pep-color-soft-gray` | `#F5F6F7` | 1 | background | legal.css(1) |
| `--pep-color-white` | `#FFFFFF` | 87 | background, color, border-color | account.css(23), homepage.css(13), header.css(9), cards.css(8) |
| `--pep-color-cyan-soft` | `#E8F6FB` | 20 | background | account.css(7), product.css(4), homepage.css(3), cards.css(2) |
| `--pep-color-green-soft` | `#EAF5EF` | 1 | background | contact.css(1) |
| `--pep-color-amber-soft` | `#FFF4DF` | 2 | background | cards.css(1), product.css(1) |
| `--pep-color-red-soft` | `#FBECEC` | 1 | background | contact.css(1) |

### Primary accent and body text — flagged

- **Primary brand colour / primary accent: `--pep-color-navy` `#002A53`.**
  Explicitly designated in `docs/WEB-2A-design-system-audit.md`: *"Primary Pep Select Navy: `#002A53`"*.
  It is also the most-used token (121 references). It is the primary button background
  (`.pepselect-home__button--primary`), all card titles, all card prices, and the compound page title.
- **Secondary accent: `--pep-color-cyan` `#17A1CF`** (103 references). Used for the cyan CTA button
  (`.pepselect-home__button--cyan`), card action borders, focus/link hover states, and the
  "testing in progress" status family.
- **Body text: `--pep-color-ink` `#13283D`** is the darkest body role (legal body copy, header text),
  but **`--pep-color-slate` `#5E6F80` is the more widely used running-text colour** (58 references vs 17).
  Precisely:
  - `.pepselect-legal__body` → `color: var(--pep-color-ink)` at `16px` — the long-form reading colour.
  - `--pep-color-slate` — secondary/supporting body text, leads, meta.
  - `--pep-color-neutral` `#7A8793` — the quietest text (de-emphasised notices, struck prices).

### Semantic status colours

From `docs/WEB-2A-design-system-audit.md`, "Semantic status system":

```
- Available / Pass / Completed: green
- Testing / Verification in Progress: cyan or teal
- Pending / Expected / Waiting: amber
- Failed / Error: red
- Out of Stock / Not Tested / Not Applicable: neutral gray
- Sale: primary navy badge with white text
- Rewards: cyan or teal treatment
- Status meaning must use text and/or icons, not color alone
- Technical metadata should use IBM Plex Mono where appropriate
```

As implemented in `pepselect-child/assets/css/cards.css`:

```css
.pepselect-card__stock--available {
	color: var(--pep-color-green);
}

.pepselect-card__stock--out {
	color: var(--pep-color-red);
}

.pepselect-card__band--testing {
	color: var(--pep-color-amber);
	background: var(--pep-color-amber-soft);
}

.pepselect-card__band--incoming {
	color: var(--pep-color-navy);
	background: var(--pep-color-cyan-soft);
}
```

## 1.3 Fonts

### Families and the CSS variable for each

| Role | CSS variable | Stack | Intended use (per WEB-2A) |
| --- | --- | --- | --- |
| Editorial | `--pep-font-editorial` | `Georgia, "Times New Roman", Times, serif` | editorial headings and brand statements |
| Interface | `--pep-font-interface` | `"Plus Jakarta Sans", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif` | navigation, body copy, buttons, forms, product information, general interface text |
| Technical | `--pep-font-technical` | `"IBM Plex Mono", "SFMono-Regular", Consolas, "Liberation Mono", monospace` | batch IDs, COA values, SKUs, technical metadata |

### Weights actually loaded — important caveat

**The theme loads no webfonts at all.** Verified: no `@font-face` rule, no `fonts.googleapis.com`,
no `fonts.gstatic.com`, and no font enqueue anywhere in `pepselect-child/`. The `foundations.css`
comment states this explicitly:

```css
/* Typography roles. Approved fonts are not bundled by this theme. */
```

So **Georgia is the only family guaranteed to render** (system serif). "Plus Jakarta Sans" and
"IBM Plex Mono" resolve to their fallbacks unless the parent theme or Elementor loads them —
neither is in this repository, so that cannot be confirmed here. `NOT FOUND: webfont loading.`

**For email this is actually convenient:** the declared stacks already degrade to
`system-ui` / `Georgia` / monospace.

Weight tokens declared:

```css
--pep-font-weight-regular: 400;
--pep-font-weight-medium: 500;
--pep-font-weight-semibold: 600;
--pep-font-weight-bold: 700;
```

Weights registered as Elementor global font styles (`docs/WEB-2A-design-system-audit.md`):

```
- Pep Editorial: Georgia, 400
- Pep Editorial Bold: Georgia, 700
- Pep Interface: Plus Jakarta Sans, 400
- Pep Interface Semibold: Plus Jakarta Sans, 600
- Pep Technical: IBM Plex Mono, 500
```

## 1.4 Type scale

**There is no element-level `h1`–`h4` scale and no font-size tokens.** Sizes are declared
per-component. `NOT FOUND: a global type scale.` The real values, by surface:

### Homepage — `pepselect-child/assets/css/homepage.css`

```css
.pepselect-home h1 { font-size: clamp(58px, 6.4vw, 88px); }   /* desktop */
.pepselect-home h2 { font-size: clamp(42px, 4.5vw, 64px); }   /* desktop */
.pepselect-home h3 { font-size: 20px; }
.pepselect-home__eyebrow { font-size: 12px; }
.pepselect-home__lead { font-size: 18px; }
.pepselect-home__section-heading p:not(.pepselect-home__eyebrow) { font-size: 17px; }

/* mobile overrides */
.pepselect-home h1 { font-size: clamp(44px, 12vw, 52px); }
.pepselect-home h2 { font-size: clamp(34px, 9.4vw, 44px); }
.pepselect-home__lead,
.pepselect-home__section-heading p:not(.pepselect-home__eyebrow) { font-size: 16px; }
```

### Other surfaces

| Selector | Size | File |
| --- | --- | --- |
| `.pepselect-compounds__heading h1` | `clamp(40px, 6vw, 64px)` | `archive.css` |
| `.pepselect-compounds__lead` | `18px` | `archive.css` |
| `.pepselect-compounds__eyebrow` | `12px` | `archive.css` |
| `.pepselect-military__title` | `clamp(2.2rem, 5vw, 3.4rem)` | `military.css` |
| `.pepselect-military__lead` | `18px` | `military.css` |
| `.pepselect-military__eyebrow` | `14px` | `military.css` |
| `.pepselect-compound-page__section-heading h2` | `clamp(28px, 3.4vw, 38px)` | `product.css` |
| `.pepselect-compound-page__eyebrow` | `12px` | `product.css` |
| `.pepselect-compound__eyebrow` | `13px` | `product.css` |
| `.pepselect-compound__context h3` | `14px` | `product.css` |
| `.pepselect-legal__section-title` (h2) | `22px` (mobile `20px`) | `legal.css` |
| `.pepselect-legal__section-title--sub` (h3) | `18px` | `legal.css` |
| `.pepselect-legal__body` | `16px` | `legal.css` |
| `.pepselect-legal__eyebrow` | `17px` (mobile `15px`) | `legal.css` |
| `.pepselect-contact__eyebrow` / `__lead` | `17px` | `contact.css` |
| `.pepselect-faq__eyebrow` / `__lead` | `17px` | `faq.css` |
| `.pepselect-track-order__eyebrow` / `__lead` | `17px` | `footer.css` |
| `.pepselect-footer__link-group h2` | `14px` | `footer.css` |
| `.pepselect-card__title` | `30px` | `cards.css` |
| `.pepselect-card__price` | `26px` | `cards.css` |
| `.pepselect-card__stock` | `16px` | `cards.css` |
| `.pepselect-card__strength` | `13px` | `cards.css` |
| `.pepselect-card__band` | `13px` | `cards.css` |
| `.pepselect-card__action` | `15px` | `cards.css` |
| `.pepselect-home__button`, `.pepselect-home__text-link` | `15px` | `homepage.css` |
| `.pepselect-dilution-notice__label` / `__body` | `12px` | `product.css` |

**Effective roles:** body `16px` · lead `17–18px` · small/eyebrow `12–14px` ·
h3 `20px` · h2 `clamp(42px, 4.5vw, 64px)` · h1 `clamp(58px, 6.4vw, 88px)`.
All sizes are `px` except `.pepselect-military__title`, which is the only `rem` value in the theme.

## 1.5 Letterspacing (every non-default value)

The pattern is unmistakable: **uppercase eyebrows and technical/monospace treatments get positive
tracking; large editorial headings get negative tracking.**

| Selector | Value | File |
| --- | --- | --- |
| `.pepselect-account__nav-title` | `-0.01em` | `account.css` |
| `.pepselect-account__title` | `-0.01em` | `account.css` |
| `.pepselect-account__stat-label` | `0.06em` | `account.css` |
| `.pepselect-account__stat-value` | `-0.02em` | `account.css` |
| `.pepselect-cashback__balance-label` | `0.06em` | `account.css` |
| `.pepselect-cashback__balance-value` | `-0.02em` | `account.css` |
| `.pepselect-cashback__how-title, .pepselect-cashback__history-title` | `0.08em` | `account.css` |
| `.woocommerce-orders-table thead th` | `0.06em` | `account.css` |
| `.woocommerce-account .woocommerce-EditAccountForm legend` | `0.04em` | `account.css` |
| `.pepselect-login__title` | `-0.02em` | `account.css` |
| `.pepselect-login__divider` | `0.08em` | `account.css` |
| `.pepselect-login__col-title` | `0.06em` | `account.css` |
| `.pepselect-cashback__engine .ywpar_tabs_links` | `0.02em` | `account.css` |
| `.pepselect-cashback__engine table.ywpar_points_rewards thead th` | `0.06em` | `account.css` |
| `.pepselect-cashback__engine .ywpar-share-status` | `0.02em` | `account.css` |
| `.pepselect-stat__label` | `0.06em` | `account.css` |
| `.pepselect-copyfield__label` | `0.06em` | `account.css` |
| `.pepselect-cashback .pepselect-account__title, .pepselect-cashback__section-title, .pepselect-cashback__how-title` | `0` | `account.css` |
| `.pepselect-cashback .pepselect-stat__value, .pepselect-cashback .pepselect-copyfield__input` | `-0.01em` | `account.css` |
| `.pepselect-refer-step__code` | `0.02em` | `account.css` |
| `.pepselect-compounds__eyebrow` | `0.13em` | `archive.css` |
| `.pepselect-compounds__heading h1` | `-0.01em` | `archive.css` |
| `.pepselect-card .pepselect-card__strength` | `0.08em` | `cards.css` |
| `#place_order, .fc-place-order-button, button#place_order.fc-place-order-button` | `0.01em` | `checkout.css` |
| `.fc-step__substep-title` | `0.06em` | `checkout.css` |
| `.pepselect-contact__eyebrow` | `0.13em` | `contact.css` |
| `.pepselect-contact__hub-title` | `0.08em` | `contact.css` |
| `.pepselect-contact__field label` | `0.01em` | `contact.css` |
| `.pepselect-contact__submit` | `0.01em` | `contact.css` |
| `.pepselect-faq__eyebrow` | `0.13em` | `faq.css` |
| `.pepselect-faq__group-title` | `0.08em` | `faq.css` |
| `.pepselect-footer__link-group h2` | `0.08em` | `footer.css` |
| `.pepselect-track-order__eyebrow` | `0.13em` | `footer.css` |
| `.pepselect-header__suggestion-strength` | `0.08em` | `header.css` |
| `.pepselect-home h1, .pepselect-home h2` | `-0.045em` | `homepage.css` |
| `.pepselect-home h1 em, .pepselect-home h2 em` | `-0.02em` | `homepage.css` |
| `.pepselect-home__eyebrow` | `0.13em` | `homepage.css` |
| `.pepselect-home__hero-image-ready span` | `0.12em` | `homepage.css` |
| `.pepselect-home__visual-fallback span` | `0.08em` | `homepage.css` |
| `.pepselect-legal__eyebrow` | `0.13em` | `legal.css` |
| `.pepselect-legal__toc-title` | `0.1em` | `legal.css` |
| `.pepselect-military__eyebrow` | `0.18em` | `military.css` |
| `.pepselect-military__title` | `-0.02em` | `military.css` |
| `.pepselect-military__step-number` | `0.02em` | `military.css` |
| `.pepselect-military__verify button` | `0.01em !important` | `military.css` |
| `.pepselect-compound-page__summary .product_title` | `-0.02em` | `product.css` |
| `.pepselect-compound__eyebrow` | `0.13em` | `product.css` |
| `.pepselect-compound-page__eyebrow` | `0.14em` | `product.css` |
| `.pepselect-compound-page__section-heading h2` | `-0.02em` | `product.css` |
| `.pepselect-dilution-notice__label` | `0.02em` | `product.css` |

**Key groupings:**

- `0.13em` — the standard uppercase eyebrow across homepage, archive, contact, FAQ, legal, product,
  and track-order. This is the single most characteristic tracking value in the system.
- `0.18em` — `.pepselect-military__eyebrow`, the widest in the theme.
- `0.08em` — uppercase section titles, footer link-group headings, and
  `.pepselect-card__strength` (the monospace strength pill).
- `0.06em` — uppercase small labels (stat labels, table headers).
- `-0.045em` — `.pepselect-home h1, .pepselect-home h2`, the tightest; the big serif display headings.
- `-0.02em` — `.pepselect-home h1 em, .pepselect-home h2 em` (the italic second line),
  `.pepselect-compound-page__summary .product_title`, `.pepselect-military__title`.

The uppercase eyebrow treatment, verbatim from `homepage.css`:

```css
.pepselect-home__eyebrow {
	font-family: var(--pep-font-interface);
	font-size: 12px;
	letter-spacing: 0.13em;
}
```

The monospace technical treatment, verbatim from `cards.css`:

```css
.pepselect-card .pepselect-card__strength {
	display: inline-flex;
	align-items: center;
	padding: 6px 14px;
	margin-bottom: 12px;
	border: 1px solid var(--pep-color-border);
	border-radius: var(--pep-radius-pill);
	color: var(--pep-color-navy);
	background: var(--pep-color-surface);
	font-family: var(--pep-font-technical);
	font-size: 13px;
	font-weight: 600;
	letter-spacing: 0.08em;
	text-transform: uppercase;
}
```

## 1.6 Border, rule, and radius values

### Radius tokens

```css
--pep-radius-small: 8px;
--pep-radius-medium: 12px;
--pep-radius-large: 20px;
--pep-radius-pill: 999px;
```

From `docs/WEB-2A-design-system-audit.md`:

> Large rounding should be reserved for prominent feature sections rather than applied everywhere.

### Radii used off-token

| Selector | Value | File | Note |
| --- | --- | --- | --- |
| `.pepselect-card` | `24px` | `cards.css` | card frame, larger than `--pep-radius-large` |
| `.pepselect-card__media img` | `14px` | `cards.css` | concentric inside the 24px frame with 10px padding |

### Borders and rules

The single border colour across the system is `--pep-color-border` `#D7E1E9` (75 uses,
the properties `border`, `border-bottom`, `border-top`). The canonical rule is:

```css
border: 1px solid var(--pep-color-border);
```

Representative uses, verbatim:

```css
.pepselect-card {
	border: 1px solid var(--pep-color-border);
	border-radius: 24px;
	background: var(--pep-color-white);
}

.pepselect-dilution-notice {
	margin-top: 20px;
	padding: 14px 16px;
	border: 1px solid var(--pep-color-border);
	border-radius: var(--pep-radius-medium);
	background: var(--pep-color-surface);
}

.pepselect-card__action {
	border: 1px solid var(--pep-color-cyan);
	border-radius: var(--pep-radius-medium);
}
```

Shadows are not tokenised. The two in use:

```css
.pepselect-card:hover { box-shadow: 0 14px 34px rgb(0 42 83 / 12%); }
.pepselect-home__button--primary:hover { box-shadow: 0 12px 26px rgb(0 42 83 / 16%); }
```

`rgb(0 42 83 ...)` is `#002A53` — the navy, at 12% and 16%.

## 1.7 Spacing scale

**There is no spacing scale token set.** `NOT FOUND: spacing tokens.` The only layout tokens are
gutters and content width:

```css
--pep-content-max-width: 1200px;
--pep-gutter-desktop: 32px;   /* --pep-layout-gutter default */
--pep-gutter-tablet: 24px;    /* applied at max-width: 1024px */
--pep-gutter-mobile: 20px;    /* applied at max-width: 767px */
```

Breakpoints: **tablet `1024px`, mobile `767px`.**

The de facto spacing scale, measured by counting every `px` value used in `gap`, `margin*`,
`padding*` across all 14 stylesheets:

| Value | Occurrences |
| --- | --- |
| `1px` | 5 |
| `2px` | 6 |
| `3px` | 1 |
| `4px` | 11 |
| `5px` | 3 |
| `6px` | 22 |
| `7px` | 2 |
| `8px` | 52 |
| `9px` | 1 |
| `10px` | 26 |
| `11px` | 5 |
| `12px` | 43 |
| `13px` | 2 |
| `14px` | 44 |
| `15px` | 2 |
| `16px` | 42 |
| `18px` | 43 |
| `20px` | 29 |
| `22px` | 28 |
| `24px` | 28 |
| `26px` | 3 |
| `28px` | 17 |
| `30px` | 3 |
| `32px` | 15 |
| `34px` | 5 |
| `36px` | 5 |
| `38px` | 1 |
| `40px` | 15 |
| `42px` | 1 |
| `44px` | 3 |
| `48px` | 14 |
| `52px` | 2 |
| `54px` | 3 |
| `56px` | 4 |
| `64px` | 8 |
| `68px` | 1 |
| `72px` | 9 |
| `80px` | 3 |
| `88px` | 3 |
| `96px` | 9 |
| `104px` | 1 |

**The dominant rhythm is an 8/12/14/16/18 core with 20/22/24/28 for block separation and
32/40/48/64/72/96 for section padding.** `8px` (52 uses), `14px` (44), `12px` (43), `18px` (43),
`16px` (42) are the workhorses. This is an even-number scale, not a strict 8-point grid —
`14px`, `18px`, and `22px` are all heavily used.

## 1.8 Motion

```css
--pep-motion-duration: 180ms;   /* 0.01ms under prefers-reduced-motion */
```

From `docs/WEB-2A-design-system-audit.md`:

> - Use subtle color, border, shadow, or `1–2px` lift changes
> - Do not use shrink effects
> - Target transition duration: approximately `180ms`
> - Provide reduced-motion behavior

Hover lift in practice: `transform: translateY(-1px)` on buttons, `translateY(-2px)` on cards.

## 1.9 Buttons — full CSS

`pepselect-child/assets/css/homepage.css`:

```css
.pepselect-home__button,
.pepselect-home__text-link {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-height: 48px;
	font-family: var(--pep-font-interface);
	font-size: 15px;
	font-weight: var(--pep-font-weight-semibold);
	line-height: 1.2;
	text-decoration: none;
	transition: color 180ms var(--pep-home-ease), background-color 180ms var(--pep-home-ease), border-color 180ms var(--pep-home-ease), box-shadow 180ms var(--pep-home-ease), transform 180ms var(--pep-home-ease);
}

.pepselect-home__button {
	padding: 14px 24px;
	border: 1px solid transparent;
	border-radius: var(--pep-radius-small);
}

.pepselect-home__button:hover {
	transform: translateY(-1px);
}

.pepselect-home__button--primary {
	color: var(--pep-color-white);
	background: var(--pep-color-navy);
}

.pepselect-home__button--primary:hover {
	color: var(--pep-color-white);
	background: var(--pep-color-dark-navy);
	box-shadow: 0 12px 26px rgb(0 42 83 / 16%);
}

.pepselect-home__button--cyan {
	color: var(--pep-color-dark-navy);
	background: var(--pep-color-cyan);
}

.pepselect-home__button--cyan:hover {
	color: var(--pep-color-dark-navy);
	background: var(--pep-color-white);
	box-shadow: 0 12px 28px rgb(0 0 0 / 16%);
}

.pepselect-home__button--outline-light {
	border-color: rgb(255 255 255 / 48%);
	color: var(--pep-color-white);
	background: transparent;
}

.pepselect-home__button--outline-light:hover {
	border-color: var(--pep-color-white);
	color: var(--pep-color-white);
	background: rgb(255 255 255 / 8%);
}

.pepselect-home__button--outline-navy {
	border: 1px solid var(--pep-color-navy);
	background: transparent;
	color: var(--pep-color-navy);
}

.pepselect-home__button--outline-navy:hover {
	background: var(--pep-color-navy);
	color: var(--pep-color-white);
}
```

Mobile: `.pepselect-home__button { flex: 1 1 100%; width: 100%; min-height: 50px; }`

---

# 2. The batch block

## 2.1 The honest answer first

**There is no component in this repository that renders a batch number, purity, net content, lab
name, and test date together.** I searched every PHP, CSS, and JS file for `batch`, `purity`,
`net content`, `lot`, `test date`, and `lab name`.

The reason is architectural, and it is stated in the theme's own comments: **that block belongs to
the Pep Select COA Archive plugin, which is not in this repository.** The theme only bridges to it
read-only.

Evidence — `pepselect-child/inc/compound-status.php` header:

```php
/**
 * Read-only bridge to the Pep Select COA Archive plugin.
 *
 * Maps WooCommerce products to their plugin compound and surfaces the state
 * of pending batches for storefront status bands. Nothing here writes to
 * plugin data; the plugin's ownership of records is untouched.
 *
 * Mapping agreed 2026-07-18 (simplified same day): every pending batch,
 * whether at the vendor or at the laboratory, surfaces as "Restocking
 * Soon". The distinction lives on the product page. Expected COA dates
 * are deliberately not shown.
 *
 * @package PepSelectChild
 */
```

The plugin's data model, as read by the theme (`inc/compound-status.php`):

| Plugin entity | Type / key |
| --- | --- |
| Compound record | post type `ps_compound` |
| Test record | post type `ps_coa_test` |
| Product link | post meta `woocommerce_product_id` on `ps_compound` |
| Test → compound link | post meta `compound_id` on `ps_coa_test` |
| Test status | post meta `coa_status` (value `pending` queried) |
| Workflow stage | post meta `workflow_stage`, values `vendor-vetting`, `waiting-on-vendor`, `submitted-to-lab`, `in-testing` |

The rendered batch/COA carousel on the product page is a plugin shortcode. The theme calls it and
nothing more — `pepselect-child/templates/single-compound.php`:

```php
<?php if ( shortcode_exists( 'pepselect_product_coa_carousel' ) ) : ?>
	<section class="pepselect-compound-page__testing">
		<?php echo do_shortcode( '[pepselect_product_coa_carousel]' ); ?>
	</section>
<?php endif; ?>
```

**To reproduce the real batch block pixel-identically in email you need the COA Archive plugin
repository, not this one.** What this repository does contain is below, in full.

## 2.2 Batch identity explainer — full markup

`pepselect-child/template-parts/home/batch-identity.php`, complete and unmodified. This is the
closest thing in the repo to a batch block: it names the six batch identifiers, but renders the
literal placeholder string `Recorded when available` in every value slot rather than real data.

```php
<?php
/**
 * Product-led batch identity explainer.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$testing_url = isset( $args['testing_url'] ) ? $args['testing_url'] : home_url( '/testing/' );
$product     = isset( $args['identity_product'] ) && is_a( $args['identity_product'], 'WC_Product' ) ? $args['identity_product'] : null;
$identifiers = array(
	__( 'Compound', 'pepselect-child' ),
	__( 'Labeled Strength', 'pepselect-child' ),
	__( 'Batch Number', 'pepselect-child' ),
	__( 'Cap Color', 'pepselect-child' ),
	__( 'Crimp Color', 'pepselect-child' ),
	__( 'Current Status', 'pepselect-child' ),
);
?>
<section class="pepselect-home__section pepselect-home__identity" aria-labelledby="pepselect-identity-title">
	<div class="pepselect-home__inner pepselect-home__identity-grid">
		<div class="pepselect-home__identity-copy">
			<h2 id="pepselect-identity-title"><span><?php esc_html_e( 'Nice label.', 'pepselect-child' ); ?></span><em><?php esc_html_e( 'Now show me the batch.', 'pepselect-child' ); ?></em></h2>
			<p class="pepselect-home__lead"><?php esc_html_e( 'When a record is available, the vial identifiers and batch details should connect without making you play detective.', 'pepselect-child' ); ?></p>
			<a class="pepselect-home__button pepselect-home__button--primary" href="<?php echo esc_url( $testing_url ); ?>"><?php esc_html_e( 'Review Batch Records', 'pepselect-child' ); ?></a>
		</div>

		<div class="pepselect-home__identity-composition">
			<div class="pepselect-home__identity-product">
				<?php if ( $product && $product->get_image_id() ) : ?>
					<?php
					echo wp_get_attachment_image(
						$product->get_image_id(),
						'woocommerce_single',
						false,
						array(
							'alt'      => $product->get_name(),
							'class'    => 'pepselect-home__identity-image',
							'decoding' => 'async',
							'loading'  => 'lazy',
							'sizes'    => '(max-width: 767px) 46vw, 24vw',
						)
					); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WordPress generates escaped responsive image markup.
					?>
				<?php else : ?>
					<span><?php esc_html_e( 'Catalog image unavailable', 'pepselect-child' ); ?></span>
				<?php endif; ?>
			</div>
			<dl class="pepselect-home__identity-panel">
				<?php foreach ( $identifiers as $identifier ) : ?>
					<div>
						<dt><?php echo esc_html( $identifier ); ?></dt>
						<dd><?php esc_html_e( 'Recorded when available', 'pepselect-child' ); ?></dd>
					</div>
				<?php endforeach; ?>
			</dl>
		</div>
	</div>
</section>
```

### Its styling: `NOT FOUND`

There is **no CSS for `.pepselect-home__identity*` in any stylesheet.** Verified by grep across all
14 files in `pepselect-child/assets/css/`.

### It does not render on the live site

`pepselect-child/templates/front-page-preview.php` is the homepage template, and it includes only
four parts:

```php
<main id="pepselect-home-main" class="pepselect-home" tabindex="-1">
	<?php get_template_part( 'template-parts/home/hero', null, $home_context ); ?>
	<?php get_template_part( 'template-parts/home/featured-products', null, $home_context ); ?>
	<?php get_template_part( 'template-parts/home/why-pep-select', null, $home_context ); ?>
	<?php get_template_part( 'template-parts/home/faq', null, $home_context ); ?>
</main>
```

**Four template parts exist in the repo but are referenced by no template and have no CSS. They are
dead code and are not on the live homepage:**

- `template-parts/home/batch-identity.php`
- `template-parts/home/coa-feature.php`
- `template-parts/home/confidence-strip.php`
- `template-parts/home/final-cta.php`

Their copy is still transcribed in §3 below and marked, because it is approved brand voice — but
do not assume a reader of the site has seen it.

## 2.3 What actually renders batch-adjacent data: the compound card

This is live on the homepage and the compounds archive. It is the real, styled, in-production
component that carries strength, availability, price, and testing status. **This is the element to
reproduce in email.**

`pepselect-child/template-parts/home/product-card.php` — full markup:

```php
<article class="pepselect-card">
	<a class="pepselect-card__media" href="<?php echo esc_url( $product_url ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'View %s', 'pepselect-child' ), $product_name ) ); ?>" tabindex="-1">
		<?php
		if ( $image_id ) {
			echo wp_get_attachment_image(
				$image_id,
				'woocommerce_thumbnail',
				false,
				array(
					'alt'      => $product_name,
					'loading'  => 'lazy',
					'decoding' => 'async',
				)
			); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Core generates escaped markup.
		}
		?>
	</a>

	<div class="pepselect-card__body">
		<?php if ( '' !== $strength_label ) : ?>
			<span class="pepselect-card__strength"><?php echo esc_html( $strength_label ); ?></span>
		<?php endif; ?>

		<h3 class="pepselect-card__title"><a href="<?php echo esc_url( $product_url ); ?>"><?php echo esc_html( $product_name ); ?></a></h3>

		<div class="pepselect-card__status">
			<?php if ( $status_band ) : ?>
				<p class="pepselect-card__band pepselect-card__band--<?php echo esc_attr( $status_band['tone'] ); ?>"><?php echo esc_html( $status_band['label'] ); ?></p>
			<?php endif; ?>

			<p class="pepselect-card__stock <?php echo $in_stock ? 'pepselect-card__stock--available' : 'pepselect-card__stock--out'; ?>">
				<?php $in_stock ? esc_html_e( 'Available', 'pepselect-child' ) : esc_html_e( 'Out of Stock', 'pepselect-child' ); ?>
			</p>
		</div>

		<p class="pepselect-card__price"><?php echo wp_kses_post( $price_html ); ?></p>

		<?php if ( $in_stock ) : ?>
			<a class="pepselect-card__action" href="<?php echo esc_url( $product_url ); ?>"><?php esc_html_e( 'Learn more', 'pepselect-child' ); ?></a>
		<?php else : ?>
			<?php $inline_form = shortcode_exists( 'cwginstock_subscribe_form' ); ?>
			<a class="pepselect-card__action pepselect-card__action--notify" href="<?php echo esc_url( $product_url ); ?>"<?php if ( $inline_form ) : ?> data-pepselect-notify-toggle aria-expanded="false" aria-controls="pepselect-notify-<?php echo esc_attr( $product->get_id() ); ?>"<?php endif; ?>><?php esc_html_e( 'Notify when available', 'pepselect-child' ); ?></a>
		<?php endif; ?>
	</div>
</article>
```

The strength label is derived from the `product_tag` taxonomy, not a custom field
(`inc/homepage-preview.php`):

```php
function pepselect_child_get_product_strength_label( $product ) {
	if ( ! is_a( $product, 'WC_Product' ) ) {
		return '';
	}

	$terms = get_the_terms( $product->get_id(), 'product_tag' );

	if ( ! is_array( $terms ) ) {
		return '';
	}

	foreach ( $terms as $term ) {
		$name = trim( $term->name );

		if ( preg_match( '/^\d+(?:\.\d+)?\s*(?:mg|mcg|iu|ml|g)$/i', $name ) ) {
			return strtoupper( preg_replace( '/\s+/', '', $name ) );
		}
	}

	return '';
}
```

### Card CSS, complete and unmodified

`pepselect-child/assets/css/cards.css`:

```css
/**
 * Unified compound card, shared by the homepage grid and the compounds
 * archive. Typography follows the approved editorial treatment; corners
 * are concentric with the card frame.
 */

.pepselect-card {
	display: flex;
	flex-direction: column;
	height: 100%;
	overflow: hidden;
	border: 1px solid var(--pep-color-border);
	border-radius: 24px;
	background: var(--pep-color-white);
	transition: box-shadow 180ms var(--pep-home-ease, ease), transform 180ms var(--pep-home-ease, ease);
}

.pepselect-card:hover {
	box-shadow: 0 14px 34px rgb(0 42 83 / 12%);
	transform: translateY(-2px);
}

@media (prefers-reduced-motion: reduce) {
	.pepselect-card,
	.pepselect-card:hover {
		transform: none;
		transition: none;
	}
}

.pepselect-card__media {
	display: grid;
	aspect-ratio: 7 / 8;
	padding: 10px;
	background: linear-gradient(180deg, var(--pep-color-surface), var(--pep-color-cyan-soft));
	place-items: center;
}

.pepselect-card__media img {
	display: block;
	width: 100%;
	height: 100%;
	border-radius: 14px;
	object-fit: cover;
}

.pepselect-card__body {
	display: flex;
	flex: 1 1 auto;
	flex-direction: column;
	align-items: flex-start;
	padding: 18px 20px 20px;
}

.pepselect-card .pepselect-card__strength {
	display: inline-flex;
	align-items: center;
	padding: 6px 14px;
	margin-bottom: 12px;
	border: 1px solid var(--pep-color-border);
	border-radius: var(--pep-radius-pill);
	color: var(--pep-color-navy);
	background: var(--pep-color-surface);
	font-family: var(--pep-font-technical);
	font-size: 13px;
	font-weight: 600;
	letter-spacing: 0.08em;
	text-transform: uppercase;
}

.pepselect-card {
	position: relative;
}

.pepselect-card .pepselect-card__title {
	min-height: 2.3em;
	margin: 0 0 10px;
	color: var(--pep-color-navy);
	font-family: var(--pep-font-editorial);
	font-size: 30px;
	font-weight: var(--pep-font-weight-bold);
	line-height: 1.15;
}

.pepselect-card .pepselect-card__title a {
	color: var(--pep-color-navy);
	font-family: inherit;
	text-decoration: none;
}

.pepselect-card .pepselect-card__title a:hover {
	color: var(--pep-color-cyan);
}

.pepselect-card__status {
	display: flex;
	flex-direction: column;
	gap: 8px;
	align-items: flex-start;
	justify-content: flex-end;
	min-height: 76px;
	margin-bottom: 12px;
}

.pepselect-card .pepselect-card__stock {
	margin: 0;
	font-family: var(--pep-font-interface);
	font-size: 16px;
	font-weight: var(--pep-font-weight-semibold);
}

.pepselect-card__stock--available {
	color: var(--pep-color-green);
}

.pepselect-card__stock--out {
	color: var(--pep-color-red);
}

.pepselect-card .pepselect-card__band {
	display: inline-flex;
	align-items: center;
	padding: 5px 12px;
	margin: 0;
	border-radius: var(--pep-radius-small);
	font-family: var(--pep-font-interface);
	font-size: 13px;
	font-weight: var(--pep-font-weight-semibold);
}

.pepselect-card__band--testing {
	color: var(--pep-color-amber);
	background: var(--pep-color-amber-soft);
}

.pepselect-card__band--incoming {
	color: var(--pep-color-navy);
	background: var(--pep-color-cyan-soft);
}

.pepselect-card .pepselect-card__price {
	margin: 0 0 16px;
	color: var(--pep-color-navy);
	font-family: var(--pep-font-editorial);
	font-size: 26px;
	font-weight: var(--pep-font-weight-bold);
	line-height: 1.1;
}

.pepselect-card__price del {
	margin-right: 8px;
	color: var(--pep-color-neutral);
	font-weight: 400;
}

.pepselect-card__action {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 100%;
	height: 44px;
	margin-top: auto;
	padding: 0 16px;
	white-space: nowrap;
	border: 1px solid var(--pep-color-cyan);
	border-radius: var(--pep-radius-medium);
	color: var(--pep-color-navy);
	background: var(--pep-color-white);
	font-family: var(--pep-font-interface);
	font-size: 15px;
	font-weight: var(--pep-font-weight-semibold);
	text-decoration: none;
	transition: background-color 180ms ease, color 180ms ease, border-color 180ms ease;
}

.pepselect-card__action:hover {
	border-color: var(--pep-color-navy);
	color: var(--pep-color-white);
	background: var(--pep-color-navy);
}

.pepselect-card__action--notify {
	border-color: var(--pep-color-border);
	color: var(--pep-color-slate);
	font-size: 14px;
}

.pepselect-card__action--notify:hover {
	border-color: var(--pep-color-navy);
	color: var(--pep-color-white);
	background: var(--pep-color-navy);
}
```

## 2.4 Where the COA link points

**Every COA / batch-record link in the theme resolves to `home_url( '/testing/' )` —
`https://www.pepselect.com/testing/`.** It is hardcoded in eight places and there is no
`/coa/` or `/quality-archive/` route.

| File | Link label | Target |
| --- | --- | --- |
| `templates/front-page-preview.php:25` | (context value) | `home_url( '/testing/' )` |
| `template-parts/home/hero.php:27` | `See the Receipts` | `$testing_url` |
| `template-parts/home/why-pep-select.php:71` | `Open the Quality Archive` | `$testing_url` |
| `template-parts/home/coa-feature.php:30` | `Open the Quality Archive` | `$testing_url` |
| `template-parts/home/batch-identity.php:28` | `Review Batch Records` | `$testing_url` |
| `template-parts/home/final-cta.php:27` | `See the Receipts` | `$testing_url` |
| `page-contact.php:47` | `Quality Archive` | `home_url( '/testing/' )` |
| `inc/footer-preview.php:167` | `Certificate of Analysis` | `home_url( '/testing/' )` |

The header nav resolves the same destination by slug with fallbacks (`inc/header-preview.php:441`):

```php
'aliases'  => array( 'coas', 'coa-archive', 'testing' ),
'fallback' => home_url( '/testing/' ),
```

Nav label for it: `COAs`.

## 2.5 QR code on the packing slip

**`NOT FOUND`.** There is no QR code generation anywhere in this repository — no `qr`, `qrcode`,
`qr_code`, or packing-slip code in any PHP or JS file. Packing slips are produced by EasyShip
(a third-party service), which is not in this repo.

The only reference to the subject is a **planned, not-yet-built** item in `BACKLOG.md`:

> The batch is to be persisted as **order line-item meta on the order**, not recomputed per surface,
> and consumed from that single field by the order-received page, the completed-order email, and
> EasyShip labels and packing slips. Cart implementation should extend the Store API cart item schema
> rather than inject into the DOM. See the ops repo item for framings, acceptance criteria, and the
> EasyShip field investigation.

So: batch-on-packing-slip is a backlog item, and the QR mechanism is either in EasyShip's
configuration or does not exist yet. Confirm with whoever owns the EasyShip account.

---

# 3. Live copy

All copy below is quoted exactly, including the HTML entities the source uses (`&rsquo;`, `&deg;`,
`&amp;`, `&ndash;`). Entities are shown as written in the source, with the rendered character noted
where it matters.

## 3.1 Homepage — `https://www.pepselect.com/`

Template: `pepselect-child/templates/front-page-preview.php`.
**Render order: hero → featured products → why Pep Select → FAQ.** That is the whole page.

### Hero — `pepselect-child/template-parts/home/hero.php`

**Eyebrow:**
> Research Without the Runaround

**Headline** (two lines; the second is in `<em>`, rendered italic serif):
> The label is the easy part.
> *What's behind it matters.*

(source: `What&rsquo;s behind it matters.`)

**Subhead / lead:**
> You shouldn't need five tabs and a leap of faith to look into a research compound. Pep Select keeps current product details and available batch documentation in one place, so the information is there when you need it.

(source: `You shouldn&rsquo;t need five tabs and a leap of faith to look into a research compound. Pep Select keeps current product details and available batch documentation in one place, so the information is there when you need it.`)

**Buttons:** `Explore Our Selection` (primary, → shop) · `See the Receipts` (outline-navy, → /testing/)

**Hero image alt text:**
> Pep Select research compound lineup

**Fallback when no hero image is set:**
> Pep Select Catalog

**`aria-label` on the second button:**
> View Pep Select Quality Archive and batch documentation

### Featured products — `pepselect-child/template-parts/home/featured-products.php`

**Eyebrow:**
> The Current Selection

**Section heading:**
> A short list, on purpose.

**Body paragraph:**
> Every compound here was selected before it was stocked, with strength and available batch records in plain view.

**Text link:** `Browse the Full Selection`

**Empty states:**
> No qualifying products are available.

> The product catalog is unavailable.

**Empty-state button:** `Explore Compounds`

**Grid `aria-label`:** `Featured compounds`

The four featured slots fill from this priority list, in order, then rotate daily through the
remaining in-stock catalog (`inc/homepage-preview.php`):

```php
function pepselect_child_get_priority_compound_names() {
	return array( 'GLP-3 R', 'GLP-2 T', 'GHK-CU', 'NAD+', 'TB-500', 'BPC-157' );
}
```

### Why Pep Select — `pepselect-child/template-parts/home/why-pep-select.php`

**Eyebrow:**
> Why Pep Select

**Heading** (second line italic):
> Everyone has a COA.
> *Ours have a permanent address.*

**Lead paragraph:**
> One document can be shown to anyone. We file records batch by batch, publicly, forever.

**Three list items (h3 + paragraph):**

> **Selected first**
> A focused catalog that is easier to explore.

> **Filed to the batch**
> Current product information stays close to the compound.

> **Nothing rushed at you**
> Open the Quality Archive when you want the batch-level detail.

**Button:** `Open the Quality Archive` (primary, → /testing/)
**Its `aria-label`:** `Open the Pep Select Quality Archive and batch documentation`
**Image fallback text:** `Pep Select Research Compounds`

### Homepage FAQ — `pepselect-child/template-parts/home/faq.php` + `inc/homepage-preview.php`

**Heading:**
> The questions we hear most.

**Text link:** `Read All FAQs`

The five questions, verbatim from `pepselect_child_get_homepage_faqs()`:

> **What does "for research use only" mean?**
> Every compound in the catalog is supplied for laboratory research only. Nothing we sell is intended for use in humans or animals.

> **How do I find documentation for my batch?**
> Open the Quality Archive and search by compound. When a batch record exists, it is filed there and stays online.

> **How do orders ship, and how fast?**
> Orders placed before 10:00 AM ET, Monday through Thursday, ship the same day. Orders placed after that cutoff ship the next day, and orders placed Friday through Sunday ship on Monday. Holidays can shift these times when carrier services are closed. Orders with a subtotal of $200 or more ship free with FedEx two-day.

> **How does payment work?**
> After you place an order, a secure Square payment link arrives by email. Your order confirms once payment clears, and unpaid orders cancel automatically after 90 minutes.

> **What if something is wrong with my order?**
> If an order arrives damaged or incorrect, contact support within 72 hours of delivery with your order number and photos of the product and packaging. Once verified, we resolve it with a replacement, refund, or store credit.

### Homepage sections that exist in code but DO NOT RENDER

Flagged in §2.2. Their copy is approved brand voice and useful for calibration, but **no visitor has
seen it.** Treat as unpublished.

**`template-parts/home/batch-identity.php`** — heading (second line italic):
> Nice label.
> *Now show me the batch.*

> When a record is available, the vial identifiers and batch details should connect without making you play detective.

Button: `Review Batch Records`. Identifier labels: `Compound`, `Labeled Strength`, `Batch Number`,
`Cap Color`, `Crimp Color`, `Current Status`. Every value slot reads `Recorded when available`.
Image fallback: `Catalog image unavailable`.

**`template-parts/home/coa-feature.php`** — eyebrow `Pep Select Quality Archive`, heading:
> See what the label
> *can't tell you.*

(source: `can&rsquo;t tell you.`)

> Search by compound, follow current and previous records, and open the documentation available for each release.

Signature line:
> Match the vial. Match the batch.

Button: `Open the Quality Archive`. Numbered actions: `Search by compound`,
`Follow batch history`, `Open the full record`.

**`template-parts/home/confidence-strip.php`** — four signals:
`Live catalog pricing` · `Current availability` · `Batch records when available` · `Direct support`
(`aria-label`: `Pep Select catalog features`)

**`template-parts/home/final-cta.php`** — heading:
> Found the compound?
> *Check what's behind it.*

(source: `Check what&rsquo;s behind it.`)

> Explore the current lineup, or take the deeper dive inside the Pep Select Quality Archive.

Buttons: `Explore Compounds` (cyan) · `See the Receipts` (outline-light)

## 3.2 About / our testing / transparency pages

**`NOT FOUND` as coded pages.** There is no About page, no "our testing" page, and no transparency
page template in this repository. Verified: no `page-about.php`, no about/testing/transparency
content file.

The `/testing/` destination is the **COA Archive plugin's** archive, which is not in this repo — so
its intro text cannot be extracted here (see §3.5).

The nearest equivalent published brand statement is the footer research copy
(`template-parts/footer/site-footer.php`), quoted in full in §3.7.

Note: `site-exports/elementor/saved-page-bq-about-409.json` exists, but it is an Elementor export of
a **BioQuantum** page (a competitor / prior site), not Pep Select. It is not Pep Select copy and is
excluded.

## 3.3 Product descriptions

Source: `pepselect-child/inc/compound-content.php`. The file header states the editorial contract:

```php
/**
 * Compound content for single product pages (WEB-2E).
 *
 * Approved, mechanism-only descriptions in the hybrid reference register.
 * Keyed by normalized compound name so content survives product edits.
 * Specs and sources are supplied here as reviewed data; the template renders
 * them but invents nothing. Compounds absent from this map simply render the
 * standard WooCommerce description with the locked legal blocks.
 *
 * @package PepSelectChild
 */
```

Matching is on the product name lowercased with strength suffixes stripped — so **"GHK-Cu 50mg"
matches the `ghk-cu` entry** and **"GLP-3 R 30mg" matches `glp-3 r`**. The strength is not part of
the description; it comes from the `product_tag` taxonomy.

### GHK-Cu (matches "GHK-Cu 50mg") — key `ghk-cu`

**Description:**
> GHK-Cu is a copper-binding tripeptide (glycyl-L-histidyl-L-lysine) studied for its role in tissue remodeling. It is researched for stimulating structural-protein synthesis and modulating genes tied to skin and matrix regeneration.

**Specs:** CAS `300801-03-0` · Formula `C28H48CuN12O8` · Form `Lyophilized powder`

**Research context:**
> - Studied for skin and tissue renewal¹
> - Researched for wound-healing and repair signaling¹
> - Investigated for regenerative signaling pathways in skin²

**Sources:**
> 1. Pickart L, Margolina A. J Biomater Sci Polym Ed. 2008. PMID:18644225
> 2. Pickart L, et al. Oxid Med Cell Longev. 2015. DOI:10.1155/2015/648108

### GLP-3 R (matches "GLP-3 R 30mg") — key `glp-3 r`

**Description:**
> Retatrutide is a synthetic peptide studied as a triple receptor agonist, engineered to engage the GLP-1, GIP, and glucagon receptors. It is researched for the structural basis of its simultaneous activity across all three receptors.

**Specs:** CAS `2381089-83-2` · Formula `C221H342N46O68` · Form `Lyophilized powder`

**Research context:**
> - Studied for metabolic regulation through triple-hormone signaling¹
> - Researched for body-weight and appetite-related biology¹
> - Investigated for liver-fat and cardiometabolic pathways²

**Sources:**
> 1. Jastreboff AM, et al. N Engl J Med. 2023. DOI:10.1056/NEJMoa2301972
> 2. Retatrutide MASLD phase 2a. Nat Med. 2024. DOI:10.1038/s41591-024-03018-2

### BPC-157 — key `bpc-157`

**Description:**
> BPC-157 is a pentadecapeptide studied for its role in angiogenesis and tissue integrity. It is researched for its activity within growth-factor signaling pathways governing blood-vessel formation.

**Specs:** CAS `137525-51-0` · Formula `C62H98N16O22` · Form `Lyophilized powder`

**Research context:**
> - Studied for promoting blood-vessel formation¹
> - Researched for its role in tissue repair and integrity²
> - Investigated for its activity in growth-factor signaling¹

**Sources:**
> 1. Wu W, et al. J Mol Med. 2017. DOI:10.1007/s00109-016-1488-y
> 2. Cell Commun Signal. 2026. DOI:10.1186/s12964-026-02694-6

### The remaining nine, in full

**Glutathione** (`glutathione`) — CAS `70-18-8` · `C10H17N3O6S` · Lyophilized powder
> Glutathione is a tripeptide (glutamate&ndash;cysteine&ndash;glycine) studied as a principal cellular antioxidant. It is researched for its role as a redox buffer and as a cofactor in enzymatic detoxification and protein regulation.

Context: Studied for cellular defense against oxidative stress¹ · Researched for its role in skin pigmentation and brightness biology² · Investigated for redox control and signaling inside cells³
Sources: Lu SC. Biochim Biophys Acta. 2013. DOI:10.1016/j.bbagen.2012.10.008 · Weschawalit S, et al. Clin Cosmet Investig Dermatol. 2017. DOI:10.2147/CCID.S128278 · Allen EMG, Mieyal JJ. Antioxidants. 2023. [VERIFY DOI]

**PT-141** (`pt-141`) — CAS `189691-06-3` · `C50H68N14O10` · Lyophilized powder
> PT-141 (bremelanotide) is a cyclic heptapeptide studied as a melanocortin receptor agonist. It is researched for its activity at central melanocortin receptors and the downstream signaling associated with them.

Context: Studied for its activity at melanocortin receptors¹ · Researched for melanocortin signaling in the nervous system² · Investigated for its receptor-activation behavior³
Sources: Pfaus JG, et al. Proc Natl Acad Sci USA. 2004. DOI:10.1073/pnas.0400491101 · Pfaus J, Giuliano F, Gelez H. J Sex Med. 2007. DOI:10.1111/j.1743-6109.2007.00610.x · Diamond LE, et al. Curr Top Med Chem. 2007. DOI:10.2174/156802607780906681

**NAD+** (`nad+`) — CAS `53-84-9` · `C21H27N7O14P2` · **Form: Solid** (the only non-lyophilized entry)
> NAD+ (nicotinamide adenine dinucleotide) is a coenzyme studied for its central role in cellular energy metabolism. It is researched as a substrate in redox reactions and as a driver of sirtuin-dependent regulatory pathways.

Context: Studied for its role in cellular energy metabolism¹ · Researched for mitochondrial fitness and sirtuin-linked signaling¹ · Investigated for system-wide metabolic regulation¹
Sources: Canto C, Menzies KJ, Auwerx J. Cell Metab. 2015. [VERIFY DOI]

**SS-31** (`ss-31`) — CAS `736992-21-5` · `C32H49N9O5` · Lyophilized powder
> SS-31 (elamipretide) is a mitochondria-targeted tetrapeptide studied for its ability to bind cardiolipin in the inner mitochondrial membrane. It is researched for its role in supporting mitochondrial energy production and cellular resilience.

Context: Studied for mitochondrial membrane protection and energy production¹ · Researched for mitochondrial function in aging² · Investigated for brain-energy and mitochondrial stress³
Sources: Chavez JD, et al. Proc Natl Acad Sci USA. 2020. DOI:10.1073/pnas.2002250117 · Pharaoh G, et al. J Physiol. 2023. PMID:37462785 · Wu J, et al. Mol Neurobiol. 2019. [VERIFY DOI]

**GLP-1 S** (`glp-1 s`) — CAS `910463-68-2` · `C187H291N45O59` · Lyophilized powder
> Semaglutide is a GLP-1 receptor agonist studied for its engagement of the GLP-1 receptor. It is researched for the signaling pathways it activates and for the prolonged receptor occupancy associated with its structure.

Context: Studied for GLP-1-driven metabolic regulation¹ · Researched for appetite and body-weight biology² · Investigated for oral peptide delivery in metabolic research³
Sources: Kommu S, et al. StatPearls. 2024. · Salvador R, et al. Cureus. 2025. PMID:40143174 · Yang F, et al. Diabetes Res Clin Pract. 2021. DOI:10.1016/j.diabres.2021.108656

**Tesamorelin** (`tesamorelin`) — CAS `218949-48-5` · `C221H366N72O67S` · Lyophilized powder
> Tesamorelin is a growth hormone-releasing hormone (GHRH) analogue studied for its action on pituitary somatotroph cells. It is researched for its role in stimulating endogenous growth hormone release through GHRH-receptor signaling.

Context: Studied for visceral-fat and body-composition biology¹ · Researched for growth-hormone-releasing signaling¹ · Investigated for liver-fat and body-composition research²
Sources: Bedimo R, et al. HIV Ther. 2011. PMID:22096409 · Tesamorelin body-composition meta-analysis. 2025. PMID:41545261

**TB-500** (`tb-500`) — CAS `[SUPPLY FROM COA]` · Formula `[SUPPLY FROM COA]` · Lyophilized powder
> TB-500 is a synthetic peptide derived from thymosin beta-4 studied as an actin-sequestering molecule. It is researched for its role in regulating actin filament assembly and cell motility.

Context: Studied for its role in cell movement and repair signaling¹ · Researched for regulating actin, a key structural protein² · Investigated for its role in tissue and blood-vessel formation¹
Sources: Goldstein AL, et al. Trends Mol Med. 2005. DOI:10.1016/j.molmed.2005.07.004 · Yu FX, et al. J Biol Chem. 1993. PMID:8416954 · Safer D, et al. PMID:8471179

> **Note:** `[SUPPLY FROM COA]` is a live placeholder in production code. The template suppresses the
> CAS line when it holds that value, so the CAS row simply does not render for TB-500.

**GLP-2 T** (`glp-2 t`) — CAS `2023788-19-2` · `C225H348N48O68` · Lyophilized powder
> Tirzepatide is a synthetic peptide studied as a dual receptor agonist engaging both the GIP and GLP-1 receptors. It is researched for the structural basis of its balanced activity across the two incretin receptors.

Context: Studied for dual-incretin metabolic regulation¹ · Researched for blood-sugar and insulin-response biology² · Investigated for body-weight regulation through dual signaling³
Sources: Nauck MA, et al. Cardiovasc Diabetol. 2022. DOI:10.1186/s12933-022-01604-7 · Rosenstock J, et al. Lancet. 2021. PMID:34186022 · Kassab HK, et al. Int J Obes. 2023. DOI:10.1038/s41366-023-01337-x

**MOTS-c** (`mots-c`) — CAS `1627580-64-6` · `C101H152N28O22S2` · Lyophilized powder
> MOTS-c is a mitochondrial-derived peptide encoded within the mitochondrial 12S rRNA region, studied as a mitochondrial-to-nuclear signaling molecule. It is researched for its role in metabolic regulation through the AMPK pathway.

Context: Studied for cellular energy and metabolic stress signaling¹ · Researched for exercise capacity and physical-performance biology² · Investigated for inflammation-linked metabolic pathways³
Sources: Kong BS, et al. Diabetes Metab J. 2023. [VERIFY DOI] · Mohtashami Z, et al. Int J Mol Sci. 2022. · Zheng Y, et al. Front Endocrinol. 2023. [VERIFY DOI]

### Product page furniture

`pepselect-child/template-parts/product/description.php` labels:
`Description` (eyebrow) · `Research context` (h3) · `View the %d source` / `View the %d sources`
(toggle, pluralised) · `CAS number` · `Intended use`

## 3.4 Compounds archive — `https://www.pepselect.com/shop/`

`pepselect-child/templates/archive-compounds.php`

**Eyebrow:** `Compounds`

**H1** (the `<em>` renders italic):
> Selection is *the standard.*

(source: `Selection is <em>the standard.</em>`)

**Lead:**
> Pep Select carries what passes our review and nothing else. The details sit on every card.

**Search-results H1:** `Results for "%s"`
**Search-results lead:** `%s compound matches your search.` / `%s compounds match your search.`
**Empty state:** `No compounds match that search.` + link `Browse the full selection`

## 3.5 COA archive page — `https://www.pepselect.com/testing/`

**Intro text: `NOT FOUND`.** The page is rendered by the COA Archive plugin, which is not in this
repository. No template, no intro string, and no failed-lot presentation code exists here.

**How failed lots are described — the only published statement in this repo** is the FAQ answer
(`pepselect-child/inc/faq-content.php`, section "Testing & records"):

> **What happens if a batch fails testing?**
> We publish results as the lab returns them, and we do not hide a batch that falls short. A batch that does not meet spec is not sold, but its record stays on the testing page like any other, because a complete record includes the batches that did not pass, not only the ones that did.

The related published claims about the archive, same file:

> **How do I find the documents for my batch?**
> Every batch we have released has a permanent record on our testing page. Search the compound, or enter the batch code printed on your vial, to open the exact certificate of analysis for what you received. Records stay online after a batch sells out.

> **What is a batch number, and how do I use it?**
> A batch number is the identifier tied to one specific production batch of a compound. It is how the vial in your hand connects to a single, specific test record rather than a general product claim. Enter it on our testing page and you will land on the certificate of analysis for that exact batch, the same document our team reads. Batch numbers do not change once assigned, so the link between your vial and its record is permanent.

> **What is on a certificate of analysis?**
> Each certificate is the third-party lab's report for that batch: the compound identified, the batch it came from, and the date it was tested. We publish them exactly as the lab returns them, unedited.

**For the actual failed-lot labelling in the UI (badge text, colour, position), you need the COA
Archive plugin repo.** The theme's own status vocabulary is limited to two bands
(`inc/compound-status.php`): label `Restocking Soon`, tones `incoming` and `testing`.

## 3.6 Every CTA button label used anywhere on the site

Grouped by surface. Every one is quoted exactly as it appears in code.

### Homepage
| Label | Style | Destination | File |
| --- | --- | --- | --- |
| `Explore Our Selection` | primary (navy) | shop | `home/hero.php` |
| `See the Receipts` | outline-navy | `/testing/` | `home/hero.php` |
| `Browse the Full Selection` | text link | shop | `home/featured-products.php` |
| `Explore Compounds` | primary | shop | `home/featured-products.php` (empty state) |
| `Open the Quality Archive` | primary | `/testing/` | `home/why-pep-select.php` |
| `Read All FAQs` | text link | `/faq/` | `home/faq.php` |

### Homepage — unpublished parts (do not render)
| Label | Style | File |
| --- | --- | --- |
| `Review Batch Records` | primary | `home/batch-identity.php` |
| `Open the Quality Archive` | cyan | `home/coa-feature.php` |
| `Explore Compounds` | cyan | `home/final-cta.php` |
| `See the Receipts` | outline-light | `home/final-cta.php` |

### Product card (homepage grid + archive)
| Label | Condition | File |
| --- | --- | --- |
| `Learn more` | in stock | `home/product-card.php` |
| `Notify when available` | out of stock | `home/product-card.php` |
| `Continue shopping` | notify-dialog success | `home/product-card.php` |

### Archive / search
| Label | File |
| --- | --- |
| `Browse the full selection` | `templates/archive-compounds.php` (empty state) |

### Header — `template-parts/header/`
| Label | Note |
| --- | --- |
| `Rewards` | action + nav item |
| `My Account` | action |
| `Cart` | action |
| `Search products` | label + submit |
| `Open primary navigation` | mobile toggle `aria-label` |
| `Home` `Compounds` `COAs` `FAQ` `Contact` | primary nav (`inc/header-preview.php`) |

**Header announcement bar** (`template-parts/header/site-header.php`):
> Free 2-Day Shipping on Cart Subtotals of $200

**Search placeholder:**
> Which compound are you looking for?

### Footer link labels — `inc/footer-preview.php`
**Support:** `Contact us` · `FAQ` · `Certificate of Analysis` · `Track your order` · `Military & First responder discount`
**Legal:** `Privacy Policy` · `Terms and conditions` · `RUO Disclaimer` · `Refund & Shipping Policy`

### Contact page — `page-contact.php`
| Label | Note |
| --- | --- |
| `Send message` | form submit |
| `Quality Archive` | inline link |
| `order tracking page` | inline link |

### Military page — `page-military-discount.php`
The CTA itself is the VerifyPass button, which lives in WordPress page content, not the theme:
```php
// Only the VerifyPass button should live in the page content, so
// its onclick and popup are preserved exactly.
```
`NOT FOUND: the VerifyPass button label` — it is authored HTML in the database.

### Account / cash-back — `woocommerce/myaccount/cash-back.php`
| Label |
| --- |
| `Copy` |
| `Turn your balance into a code` |

### Product page (single compound)
| Label | Source |
| --- | --- |
| `View the %d source` / `View the %d sources` | `product/description.php` |
| `Add to cart` | WooCommerce core, not themed |
| `Email when stock available` | BISN form title, `back-in-stock-notifier-for-woocommerce/default-form.php` |

### CTA language policy — `.agents/product-marketing.md`

**Preferred:**
```
- Explore Compounds
- Review COAs
- View Testing History
- Review Batch Documentation
- Browse Research Compounds
- View Compound Details
- Contact Support
```

**Avoid:**
```
- Start Your Journey
- Transform Your Health
- Get Results
- Lose Weight Now
- Feel Better
- Try It Today
```

## 3.7 Disclaimer text, exactly as written, and where each sits

### A. Footer FDA Disclaimer — sitewide, bottom of every page

`pepselect-child/template-parts/footer/disclaimer.php`. Renders inside `.pepselect-footer__disclaimer`,
between the footer link groups and the copyright line. The label is bold, the body is not.

> **FDA Disclaimer:** The statements made within this website have not been evaluated by the US Food and Drug Administration. The statements and the products of this company are not intended to diagnose, treat, cure or prevent any disease. Pep Select is not a compounding pharmacy or chemical compounding facility as defined under 503A of the Federal Food, Drug, and Cosmetic Act. Pep Select is not an outsourcing facility as defined under 503B of the Federal Food, Drug, and Cosmetic Act. All products are sold for research, laboratory, or analytical purposes only, and are not for human consumption.

### B. Footer research copy — sitewide, footer brand column

`pepselect-child/template-parts/footer/site-footer.php`, three separate `<p>` elements above the
support email:

> For laboratory research use only.

> Pep Select compounds are independently tested, with batch specific Certificates of Analysis available for review.

> Products are not intended for human consumption.

Support line: `Support: support@pepselect.com`
Copyright: `© {year} Pep Select. All rights reserved.`

### C. Intended use — every single product page

`pepselect-child/template-parts/product/description.php`, in `.pepselect-compound__intended`,
directly below the description/sources block.

> **Intended use**
> Supplied strictly for laboratory research. Not for use in humans or animals, and not for use in foods, drugs, supplements, or diagnostics.

### D. Product page FDA statement — every single product page

Same file, `.pepselect-compound__disclaimer`, the last element in the section, below Intended use.

> These statements have not been evaluated by the Food and Drug Administration. No claims are made regarding the diagnosis, treatment, cure, or prevention of any disease. Use is restricted to qualified research professionals.

### E. Dilution notice — every compound buy card (added 0.19.0-beta.7, 2026-08-01)

`pepselect-child/inc/single-product.php`, hooked to `woocommerce_single_product_summary` at
priority 35 — directly below add-to-cart, and below the back-in-stock notify form on out-of-stock
compounds.

> **Dilution notice**
> If a compound turns cloudy after it is reconstituted, the cause is almost always the dilution solution rather than the compound itself, and non-pharmaceutical-grade solutions are the usual culprit. For this reason, cloudiness cannot be accepted as grounds for a refund unless the reconstitution was done using Pfizer pharmaceutical-grade dilution solution.

Markup and CSS:

```html
<div class="pepselect-dilution-notice">
    <p class="pepselect-dilution-notice__label">Dilution notice</p>
    <p class="pepselect-dilution-notice__body">…</p>
</div>
```

```css
.pepselect-dilution-notice {
	margin-top: 20px;
	padding: 14px 16px;
	border: 1px solid var(--pep-color-border);
	border-radius: var(--pep-radius-medium);
	background: var(--pep-color-surface);
}

.pepselect-dilution-notice__label {
	margin: 0 0 5px;
	color: var(--pep-color-navy);
	font-family: var(--pep-font-interface);
	font-size: 12px;
	font-weight: var(--pep-font-weight-semibold);
	letter-spacing: 0.02em;
}

.pepselect-dilution-notice__body {
	margin: 0;
	color: var(--pep-color-neutral);
	font-size: 12px;
	line-height: 1.55;
}
```

> ⚠️ **This clause is pending attorney sign-off per the repository changelog.** It also asserts a
> scientific claim and names a third-party brand as the sole condition of refund eligibility.
> Do not repeat it in marketing email without checking its current legal status.

### F. Dilution and cloudiness clause — legal pages (added 0.19.0-beta.8, 2026-08-01)

`pepselect-child/inc/legal-content.php`, in three places: Terms & Conditions §5, and twice in the
Refund & Shipping Policy.

> Dilution and cloudiness: Cloudiness that appears after a compound is reconstituted is almost always caused by the dilution solution rather than the compound. Cloudiness is not eligible for a refund, replacement, or credit unless the reconstitution was performed using Pfizer pharmaceutical-grade dilution solution.

### G. Research Use Only — legal page

`inc/legal-content.php`, Terms & Conditions §6:

> We work to keep product descriptions, specifications, and labels accurate, but they may change without notice. Products are batch-tested by independent laboratories, and Certificates of Analysis (COAs) are published on our COA lookup page. Purity is verified at the batch level and is not guaranteed beyond the listed conditions and shelf life. All products are provided "as is" for research purposes only, without warranties of any kind, express or implied.

Terms §7, Assumption of Responsibility:

> Once an order leaves our facility, full responsibility for handling, storage, testing, and research use transfers to the purchaser. You acknowledge and agree that PepSelect is not responsible for misuse, mishandling, improper storage, or unauthorized use of any product, and is not liable for injury, harm, loss, or damage resulting from failure to follow these Terms.

**Four legal documents exist as coded data** in `inc/legal-content.php`, rendered by
`templates/legal-page.php`:

| Slug | Title | last_updated |
| --- | --- | --- |
| `terms-conditions` | Terms & Conditions | August 1, 2026 |
| `privacy-policy` | Privacy Policy | July 23, 2026 |
| `ruo-disclaimer` | RUO Disclaimer | July 23, 2026 |
| `refund-shipping-policy` | Refund & Shipping Policy | August 1, 2026 |

Terms section headings, in order:
`1. Research Use Only` · `2. Eligibility & Researcher Certification` · `3. Ordering & Order Acceptance` ·
`4. Payment` · `5. Shipping & Risk of Loss` · `6. Product Information & Certificates of Analysis` ·
`7. Assumption of Responsibility` · `8. Limitation of Liability` · `9. Right to Refuse Service` ·
`10. Intellectual Property` · `11. Privacy` · `12. Governing Law` · `13. Changes to These Terms` · `14. Contact`

RUO Disclaimer section headings:
`1. Purchaser Acknowledgment` · `2. Intended Use` · `3. Purchaser Verification` ·
`4. Limitation of Liability` · `5. FDA Disclaimer` · `6. Regulatory Notice`

Refund & Shipping Policy section headings:
`Shipping` · `Cancellations & Refunds Before Shipment` · `After Shipment — All Sales Final` ·
`Damaged or Incorrect Orders` · `Packages Marked as Delivered` ·
`Not Eligible for Refund or Replacement` · `Refund Processing` · `Contact Us`

Key refund paragraphs, verbatim:

> We aim to make every order fast, accurate, and predictable. Please read this policy carefully before purchasing — by placing an order with PepSelect you agree to the terms below.

> Orders placed before 10:00 AM ET, Monday through Thursday, ship the same day. Orders placed after 10:00 AM ET, Monday through Thursday, ship the next day. Orders placed Friday through Sunday ship on Monday. Holidays can shift these times when carrier services are closed.

> Due to the sensitive nature of research compounds, all sales are final once an order has shipped. We do not accept returns or offer cancellations for orders in transit, and we cannot resell returned product. Carrier delays do not qualify for refunds.

> Upon verification, we will offer a replacement shipment at no cost, a refund, or store credit, at our discretion. Requests submitted outside the 72-hour window may not be eligible.

> If you believe your order qualifies for review, reach out to [support@pepselect.com]. We review every request case by case and will always do our best to help when eligibility criteria are met.

> **Note:** the square brackets around `[support@pepselect.com]` are in the production source.

## 3.8 Full FAQ page — `https://www.pepselect.com/faq/`

`pepselect-child/inc/faq-content.php`, complete, grouped as it renders. (The "Testing & records"
group is quoted in §3.5 and not repeated.)

**Research use**

> **Are these compounds for human use?**
> No. Everything we sell is research-use-only (RUO) material, intended for laboratory research by qualified professionals. Nothing here is a drug, supplement, or product for human or veterinary use, and nothing on this site is a claim about safety, efficacy, or medical outcomes.

> **Who can purchase?**
> You must be 21 or older to order. By purchasing, you confirm you are acquiring these materials for lawful research use only, consistent with their research-use-only status.

**Storage & handling**

> **How should I store these materials?**
> Store lyophilized (freeze-dried) material at -20 °C for long-term storage; 2 to 8 °C is acceptable for shorter periods. Reconstituted solutions are less stable and should be kept at 2 to 8 °C. Avoid repeated freeze-thaw cycles.

(source uses `&deg;` for °)

**Ordering, payment & rewards**

> **How does payment work?**
> After you place an order, you will receive a secure Square payment link by email to complete your purchase. Orders that are not paid are automatically cancelled after 90 minutes, so inventory is not held.

> **How does cash back work?**
> You earn 3% cash back on every order: 3 points for every $1 you spend, where 100 points is worth $1. Once your balance reaches 500 points ($5), you can turn it into a code from your account and enter that code in the discount field at checkout. Your balance shows in your account.

> **I lost my cash-back redeem code. What do I do?**
> Email us at support@pepselect.com and we will help you recover it. Include the email address on your account so we can find your record.

**Shipping**

> **When do orders ship?**
> Orders placed before 10:00 AM ET, Monday through Thursday, ship the same day. After 10:00 AM ET on those days, orders ship the next day. Orders placed Friday through Sunday ship on Monday. Holidays can shift these times when carrier services are closed.

> **What are the shipping options?**
> USPS Priority and FedEx (two-day and next-day). FedEx two-day is free on orders with a subtotal of $200 or more. Rates are calculated at checkout.

> **Where do you ship?**
> All 50 U.S. states and Washington, D.C. Shipping is calculated at checkout.

> **How do I track my order?**
> Use our order tracking page and enter your details to see current status.

**Orders & support**

> **My order arrived damaged or incorrect. What should I do?**
> Contact support within 72 hours of delivery with your order number and photos of the product and packaging. Once verified, we resolve it with a replacement, refund, or store credit, per our published policy.

> **I did not receive an order confirmation email.**
> Email support@pepselect.com with your name and the last 6 digits of the card used, and we will trace the order for you.

> **Do you offer a military or first responder discount?**
> Yes. Verify your status on our Military & Law Enforcement page, verification is handled by VerifyPass, to receive a one-time 20% discount code.

> **How do I contact you?**
> Email support@pepselect.com and we will get back to you.

## 3.9 Contact page — `https://www.pepselect.com/contact/`

`pepselect-child/page-contact.php`

**H1 / eyebrow:** `Contact`

**Lead:**
> Questions about an order, a batch, or a compound? Send us a message and we'll reply within one business day.

**Section heading:** `Before you write`

> Every batch's certificate of analysis is filed in the [Quality Archive], and stays there after a batch sells out.

> Order status and tracking live on the [order tracking page].

> Lost a cash-back redeem code? Email us and we will help you recover it.

> For anything else, email [support@pepselect.com]. We reply within one business day.

Form labels: `Name` · `Email *` · `Subject` · `Message *` · submit `Send message`

## 3.10 Military & First Responder page — `https://www.pepselect.com/military-discount/`

`pepselect-child/page-military-discount.php`

**Eyebrow:** `Discount Program`

**H1** (second line italic):
> For those who serve,
> *20% off every order.*

**Lead:**
> We honor military members, veterans, and first responders. Verify your service once, and your code is yours to use.

**Three numbered steps (01 / 02 / 03):**

> **Verify your status**
> Confirm your eligibility with our partner VerifyPass. It opens in a secure window and takes about a minute.

> **Receive your code**
> Once verified, a one-time discount code arrives by email.

> **Apply it at checkout**
> Enter the code at checkout to take 20% off your order.

**Fine print:**
> One code per verified person. Cannot be combined with other offers.

## 3.11 Order tracking page — `https://www.pepselect.com/track-your-order/`

`pepselect-child/page-track-your-order.php`

**H1 / eyebrow:** `Order Tracking`

**Lead:**
> Enter your order number and the billing email you used at checkout to see where your shipment stands.

## 3.12 Cash-back / rewards page — `/my-account/cash-back/`

`pepselect-child/woocommerce/myaccount/cash-back.php`

**Section title:** `Cash back`
> Earn 3% back on every order. Turn your balance into a code and apply it at checkout.

**Four stat / how-it-works cards:**

> **Earn on every order**
> 3% of every completed order comes back to you as cash back.

> **Bring a friend**
> Share your code. They save 10% on their first order, you get $15 once it completes.

> **Spend it at checkout**
> At $5 or more, turn your balance into a code and apply it to any order.

> **One balance**
> Cash back from your purchases and your referral rewards collect together in a single balance.

**Balance labels:** `Available balance` (`Redeem it for a code at checkout.`) ·
`Total earned` (`Across all your orders.`) · `Total applied` (`Already used at checkout.`)

**Referral block:** heading `Refer a friend`
> Share your link. They save 10% on their first order, and you earn $15 in cash back once it completes.

Three steps:
> Share your link with a friend.

> Tell them to use code %s at checkout for 10% off their first order.

> When their order completes, you get $15 in cash back.

Other labels: `Your share link` · `Copy` · `Referral bonus` · `How it works` ·
`Turn your balance into a code` · `Cash back history`

Referral code format (`inc/referral-vanity.php`): `PSRC` + user ID, e.g. `?ref=PSRC7`.

## 3.13 Back-in-stock form

`pepselect-child/back-in-stock-notifier-for-woocommerce/default-form.php`

**Form title:** `Email when stock available`

Notify dialog copy (`template-parts/home/product-card.php`):
> Leave your email and we will let you know the moment %s is available again.

> **You're all set.**
> Once %s comes back in stock, we will notify you at **{email}**

---

# 4. Product data

## `NOT FOUND` — and why

**No per-SKU product data could be extracted.** The requested table (name, strength, price, stock
quantity, purchasable, batch number, purity, net content, lab name, test date, COA URL) cannot be
produced from this environment. Both routes you offered are closed:

| Route | Status |
| --- | --- |
| Query the database | **Unavailable.** No `wp-config.php`, no `.sql` dump, no `mysql`/`mysqldump` client, no WordPress install in this repo. Verified by filesystem search. |
| WooCommerce REST API | **Unavailable.** The sandbox network policy denies the host. `curl https://www.pepselect.com/...` returns `CONNECT tunnel failed, response 403`; the proxy log records `connect_rejected … www.pepselect.com:443`. Also, `wc/v3` would need a consumer key/secret, which is not in the repo (and must not be). |
| WooCommerce Store API (public, no auth) | Attempted as the faster fallback. **Blocked by the same network policy.** |

This repository contains the **presentation layer only**. Product records, prices, stock levels, and
all COA/batch data live in the WordPress database and the COA Archive plugin, neither of which is here.

**To get this table, run one of these where the site is reachable:**

```bash
# fastest, no auth, public
curl -s 'https://www.pepselect.com/wp-json/wc/store/v1/products?per_page=100' > products.json

# authenticated, includes stock_quantity and full meta
curl -s -u ck_xxx:cs_xxx \
  'https://www.pepselect.com/wp-json/wc/v3/products?per_page=100&status=publish' > products.json

# or on the server
wp wc product list --user=1 --fields=id,sku,name,price,stock_quantity,stock_status,purchasable --format=csv
```

Batch/purity/lab/test-date must be joined separately from the plugin's post types:

```bash
wp post list --post_type=ps_coa_test --post_status=publish \
  --fields=ID,post_title --format=csv
# then per record: compound_id, coa_status, workflow_stage (see §2.1 for the meta map)
```

## What product identity IS in the repo

### The 12 compounds with approved content (`inc/compound-content.php`)

Sorted alphabetically — **stock quantity is unknown, so the requested sort could not be applied.**

| Compound key | Display name in content | CAS | Formula | Form | Strength | Price | Stock qty | Purchasable | Batch № | Purity | Net content | Lab | Test date | COA URL |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `bpc-157` | BPC-157 | `137525-51-0` | `C62H98N16O22` | Lyophilized powder | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND |
| `ghk-cu` | GHK-Cu | `300801-03-0` | `C28H48CuN12O8` | Lyophilized powder | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND |
| `glp-1 s` | Semaglutide | `910463-68-2` | `C187H291N45O59` | Lyophilized powder | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND |
| `glp-2 t` | Tirzepatide | `2023788-19-2` | `C225H348N48O68` | Lyophilized powder | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND |
| `glp-3 r` | Retatrutide | `2381089-83-2` | `C221H342N46O68` | Lyophilized powder | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND |
| `glutathione` | Glutathione | `70-18-8` | `C10H17N3O6S` | Lyophilized powder | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND |
| `mots-c` | MOTS-c | `1627580-64-6` | `C101H152N28O22S2` | Lyophilized powder | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND |
| `nad+` | NAD+ | `53-84-9` | `C21H27N7O14P2` | **Solid** | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | see below | NOT FOUND | NOT FOUND | see below | NOT FOUND | NOT FOUND |
| `pt-141` | PT-141 (bremelanotide) | `189691-06-3` | `C50H68N14O10` | Lyophilized powder | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND |
| `ss-31` | SS-31 (elamipretide) | `736992-21-5` | `C32H49N9O5` | Lyophilized powder | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND |
| `tb-500` | TB-500 | `[SUPPLY FROM COA]` | `[SUPPLY FROM COA]` | Lyophilized powder | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND |
| `tesamorelin` | Tesamorelin | `218949-48-5` | `C221H366N72O67S` | Lyophilized powder | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND | NOT FOUND |

**Neither "GHK-Cu 50mg" nor "GLP-3 R 30mg" appears anywhere in the repository as a product record.**
The strengths you named come from the `product_tag` taxonomy in the database. The content library is
keyed on the compound name with strength stripped, which is why one `ghk-cu` entry serves every
GHK-Cu strength.

### Homepage priority order (a real merchandising signal)

`inc/homepage-preview.php` — the first four in-stock of these fill the homepage grid, in this order:

```php
array( 'GLP-3 R', 'GLP-2 T', 'GHK-CU', 'NAD+', 'TB-500', 'BPC-157' )
```

### The only real batch data in the entire repository

From `BACKLOG.md`. Two batch numbers and one lab name appear as incidental examples:

| Field | Value | Source |
| --- | --- | --- |
| Batch number | `PSNAD562926JP` | `BACKLOG.md`, NAD+ |
| Batch number | `ND_R30_060326` | `BACKLOG.md`, format note |
| Lab | `ILS Labs` | `BACKLOG.md`, NAD+ |
| Status | `Failed` (marked Published) | `BACKLOG.md`, NAD+ |

Purity percentage, net content, and test date: **`NOT FOUND` anywhere in the repository.**

### ⚠️ Open bug that contradicts published copy — read before writing any transparency claim

`BACKLOG.md`, "Bugs", status **open**, priority **high**:

> ### COA Archive plugin — failed batches do not render on the public /testing/ archive
>
> **Status:** open · **Priority:** high (transparency commitment)
>
> Failed-status batches are marked Published but never appear on the public archive page.
>
> - **Example:** NAD+ batch `PSNAD562926JP`, tested by ILS Labs, status **Failed**, marked **Published** — absent from the public page.
> - **Expected:** per the transparency decision, failed batches **must** publish with a clear "failed — not offered for sale" status. Publishing a failure is a deliberate brand signal, not an edge case.
> - **Investigate:** why failed-status batches are filtered out of the public archive query/render path, and surface them with the failed state displayed distinctly.

**This directly contradicts the live FAQ answer quoted in §3.5** ("we do not hide a batch that falls
short … its record stays on the testing page like any other"). The intent is real and published; the
implementation is currently broken. **Do not build email copy around "see our failed batches" until
this is fixed** — a reader who clicks through will not find them.

Related note from the same file:

> ### Batch numbering — existing numbers predate R6, do not "fix"
>
> Current batch numbers on the site (e.g. `PSNAD562926JP`, `ND_R30_060326`) predate the R6 batch-number system and **will not match its format**. This is expected, not a defect.

---

# 5. Everything else in the repo

## 5.1 Brand / voice / tone document — YES, there is one

**`.agents/product-marketing.md`** is a complete product-marketing, voice, evidence, and
copy-compliance guide. **This is the single most important file in this extract for writing email.**
Reproduced in full and verbatim:

```markdown
# Product Marketing Context

**Document version:** v1
**Last updated:** 2026-07-16

## Product Overview

**One-liner:** Pep Select provides carefully selected research compounds supported by transparent documentation, batch-specific testing records, and clear product information.

**Purpose:** Pep Select exists to make peptide research more transparent, more accessible, and more dependable. Quality research compounds should not require inflated luxury pricing, unclear sourcing, exaggerated promises, or blind trust.

**Product category:** Research compounds for legitimate laboratory research and analytical purposes.

**Product type:** E-commerce.

**Business model:** Remain commercially sustainable while keeping documented research products accessible.

## Core Positioning

Pep Select does not sell confidence through hype. Pep Select provides documentation that allows researchers to evaluate each release and build their own confidence.

**Preferred positioning language:** "Pep Select is known for making peptide research more transparent, more accessible, and more dependable."

**Brand lane:**

- Transparent over mysterious
- Documented over exaggerated
- Accessible over exclusive
- Dependable over flashy
- Carefully selected rather than indiscriminately listed
- Batch traceability rather than broad unsupported assurances

## Target Audience

**Primary audience:** Researchers and laboratory purchasers who value clear product information, batch documentation, third-party testing, consistent ordering, reasonable pricing, and a professional purchasing experience.

**Primary use case:** Review compound information and available batch documentation before deciding what to purchase for legitimate laboratory research or analytical work.

**Jobs to be done:**

- Find carefully selected research compounds.
- Review batch-specific documentation and testing history.
- Understand current testing status without broad assurances.
- Order through a clear, professional purchasing experience.

**Anti-persona language:** Do not describe the audience as patients, users, injectors, dieters, bodybuilders, biohackers, or people seeking health outcomes.

## Problems and Boundaries

**Problems Pep Select addresses:**

- Unclear product information or sourcing
- Exaggerated promises
- Blind trust without documentation
- Inflated luxury positioning that makes documented research products less accessible

**Competitive boundary:** Do not imitate TrustedPeps, Crush Research, Orbitrex, Peptides Divas, BioQuantum, or other competitors. Do not make comparative claims about them without verified evidence and approval.

## Differentiation

Pep Select may differentiate through careful selection, accessible documentation, batch traceability, clear status communication, professional support, straightforward ordering, and reasonable pricing.

Do not convert these territories into superiority, guarantee, or universal-testing claims. Each factual statement must match current Pep Select records.

## Brand Voice

**Tone:** Calm, precise, trustworthy, direct, human, informed, accessible, and confident without arrogance.

**Style:** Clinical-modern without becoming cold. Use concise sentences, clear hierarchy, and plain English. Use technical language only when it improves accuracy. Headlines may be memorable but must remain credible and grounded.

## Customer Language

**Words and ideas to use:**

- Careful selection
- Batch documentation
- Third-party laboratory reports
- Testing-history access
- Traceability
- Clear status communication
- Transparent product information
- Dependable fulfillment processes
- Reasonable and accessible pricing
- Professional customer support
- Straightforward ordering
- Research-use limitations
- Review documentation before deciding what to purchase

**Copy to avoid:**

- In today's fast-paced world
- Unlock your potential
- Elevate your journey
- Cutting-edge solutions
- Premium quality you can trust
- Your trusted partner
- Uncompromising excellence
- Not a big-box operation
- Focused, hands-on brand
- Frictionless ordering
- Best-in-class
- Game-changing
- Revolutionary results
- It's not just X, it's Y
- Excessive rhetorical questions or em dashes
- Repetitive three-item slogan structures
- Vague quality, reliability, consistency, or trust claims without evidence

## Evidence and Documentation Rules

Only make factual claims supported by confirmed Pep Select records.

Testing claims must be batch-specific where appropriate, match the actual COA status, remain limited to the documented test and result, and change when the underlying record changes.

Do not say every product is tested, verified, passed, sterile, pure, or available unless current records support that exact statement.

Do not invent:

- Purity percentages or laboratory results
- Customer counts or testing statistics
- Turnaround times
- Testimonials or rankings
- Certifications or guarantees
- Comparative superiority
- Scientific conclusions

Use `[VERIFY CLAIM]` when evidence is unavailable. Flag uncertainty instead of guessing.

## Compliance Guardrails

Present all products strictly for legitimate laboratory research and analytical purposes.

Never write or imply:

- Human or animal consumption
- Medical, therapeutic, diagnostic, preventive, or veterinary use
- Treatment or mitigation of any disease or condition
- Weight loss, appetite suppression, fat loss, muscle growth, healing, recovery, anti-aging, hormonal, metabolic, cognitive, or performance outcomes
- Effects on the structure or function of the human body
- Dosing, cycles, titration, administration, injection, reconstitution, or personal-use instructions
- That a compound is safe or effective for a person
- Personal experimentation
- Physician, clinic, or patient use
- Lifestyle transformation or before-and-after outcomes

Do not communicate those meanings indirectly through imagery, testimonials, FAQs, headings, metadata, or calls to action. A Research Use Only disclaimer does not permit prohibited claims elsewhere.

Avoid these terms unless supported and specifically approved:

- Safe
- Effective
- Clinically proven
- Pharmaceutical grade
- Medical grade
- FDA approved
- FDA compliant
- Guaranteed purity
- Highest purity
- Risk-free
- Proven results
- Superior
- Best quality

Do not present legal disclaimers as proof that marketing claims comply with applicable law.

## Approved Message Territories

Copy may focus on careful selection, batch documentation, third-party laboratory reports, testing-history access, traceability, clear status communication, transparent product information, dependable fulfillment processes, reasonable and accessible pricing, professional customer support, straightforward ordering, research-use limitations, and documentation review before purchase.

Every factual statement within these territories still requires current evidence.

## CTA Language

**Preferred:**

- Explore Compounds
- Review COAs
- View Testing History
- Review Batch Documentation
- Browse Research Compounds
- View Compound Details
- Contact Support

**Avoid:**

- Start Your Journey
- Transform Your Health
- Get Results
- Lose Weight Now
- Feel Better
- Try It Today

## Homepage Copy Workflow

WEB-2C homepage copy must follow this sequence:

1. Product-marketing context
2. Page objective and visitor journey
3. Copywriting draft
4. Compliance and evidence review
5. CRO structural review
6. Copy-editing pass
7. Stop-slop final pass
8. Paulo approval
9. Website implementation

Stop Slop is a final editing pass, not the primary writer. CRO may improve hierarchy and clarity but may not introduce unsupported urgency, scarcity, health claims, or personal-use implications.

## Goals and Approval

**Business goal:** Maintain commercial sustainability while keeping documented research products accessible.

**Homepage primary conversion action:** `[VERIFY CLAIM]`

No homepage copy is approved for publication until Paulo reviews it.

## Open Evidence Questions

- Which current batches have third-party laboratory reports, and which exact tests and results may be cited? `[VERIFY CLAIM]`
- Which fulfillment, availability, support, and ordering statements have current operational evidence? `[VERIFY CLAIM]`
- What is the single primary conversion action for the WEB-2C homepage? `[VERIFY CLAIM]`

## Legal Review Note

This document is a conservative marketing guardrail. It is not a substitute for review by qualified legal counsel.

## Changelog

- v1 (2026-07-16) — Initial Pep Select product-marketing, evidence, voice, and copy-compliance context.
```

> **Note for email specifically:** the guide bans "Pharmaceutical grade" unless specifically approved.
> The dilution notice added on 2026-08-01 (§3.7 E/F) uses exactly that phrase, in a
> third-party-brand context. It is flagged for attorney review in the changelog. Treat as approved
> for the product page and legal pages only; do not extend it to email without checking.

## 5.2 Repo-wide copy rules — `AGENTS.md`

The "Copy and Messaging" section, verbatim:

```markdown
## Copy and Messaging
- For Pep Select copy tasks, read `.agents/product-marketing.md`.
- Also read `C:\Users\paulo\.codex\private\pepselect\confidential-copy-strategy.md` when it exists.
- Treat the private supplement as Pep Select-only confidential strategy.
- Never commit, quote, summarize, package, or reproduce the private supplement in completion reports or public artifacts.
- Use Product Marketing, Copywriting, compliance review, CRO, Copy Editing, and Stop Slop in that order.
- Stop Slop is the final cleanup pass, not the primary writer.
- Never introduce unsupported factual, comparative, laboratory, medical, or human-use claims.
- Mark uncertain claims with `[VERIFY CLAIM]`.
- Confidence must come from evidence and specificity, not disguised prohibited implications.
```

> The referenced `confidential-copy-strategy.md` is a Windows-local path and is **not present in this
> repository**. Per the rule above it is deliberately not reproduced here. If it exists on your
> machine, read it alongside this extract.

## 5.3 Email templates already in the repo — YES, two

`pepselect-child/woocommerce/emails/` contains two WooCommerce template overrides:

- `customer-completed-order.php`
- `customer-on-hold-order.php`

The changelog references an amber treatment on the on-hold email that matches the B4G1 pill.
These are transactional templates, not marketing templates. **`NOT FOUND`: any marketing email
template.** Read those two files directly if you want to match transactional styling — they were
not transcribed here because they are outside the four requested content areas, but they are the
closest existing precedent for Pep Select HTML email.

## 5.4 `/docs` directory — full listing

Thirteen files, all planning/audit documents from the WEB-0 → M10 rebuild programme:

| File | Lines | Title |
| --- | --- | --- |
| `docs/WEB-0-environments.md` | 38 | WEB-0 Environment Inventory |
| `docs/WEB-0-foundation-decisions.md` | 17 | Pep Select Website Rebuild Decisions |
| `docs/WEB-0-rebuild-architecture.md` | 56 | WEB-0 Rebuild Architecture |
| `docs/WEB-0-site-inventory.md` | 77 | WEB-0 Site Inventory |
| `docs/WEB-1-elementor-audit.md` | 427 | WEB-1 Elementor Export Audit |
| `docs/WEB-1-staging-findings.md` | 158 | WEB-1 Staging Verification Findings |
| `docs/WEB-2-rebuild-plan.md` | 746 | WEB-2 Controlled Coded Customer-Facing Rebuild Plan |
| `docs/WEB-2A-design-system-audit.md` | 460 | WEB-2A — Current Design System Audit **(the token source-of-truth doc)** |
| `docs/WEB-2B-global-navigation-plan.md` | 954 | WEB-2B — Coded Customer-Facing Rebuild and Global Shell Plan |
| `docs/WEB-2C-homepage-copy-draft.md` | 84 | WEB-2C Homepage Copy Draft **(copy doc, see below)** |
| `docs/WEB-2C-homepage-implementation.md` | 77 | WEB-2C Coded Homepage Private Preview |
| `docs/WEB-2C-homepage-journey.md` | 62 | WEB-2C Homepage Visitor Journey |
| `docs/m10-audit.md` | 124 | M10 Phase 1 — Pre-Launch Audit |

## 5.5 `docs/WEB-2C-homepage-copy-draft.md` — the approved copy draft, in full

Useful because it shows what was drafted vs what shipped. **Several CTAs in this draft were changed
before implementation** — e.g. draft `Explore the Lineup` shipped as `Explore Our Selection`;
draft `View Compound` shipped as `Learn more`; draft section-4 heading `Less guessing.` /
`More to go on.` shipped as `Everyone has a COA.` / `Ours have a permanent address.`
Where the draft and the code differ, **the code in §3 is what is live.**

```markdown
# WEB-2C Homepage Copy Draft

## Approval status

Beta.3 retains the technically successful beta.2 product-first structure while replacing its emotionally flat, instructional hero. "What's Behind the Label Matters" is now the homepage emotional anchor. The preview remains private and unpublished pending Paulo's visual approval.

## Section 1: Product-first hero

- Eyebrow: `RESEARCH WITHOUT THE RUNAROUND`
- Heading: `The label is the easy part.` / `What's behind it matters.`
- Supporting copy: `You shouldn't need five tabs and a leap of faith to explore a research compound. Pep Select keeps current product details and available batch documentation close at hand—so the information is easier to find when you want it.`
- Primary CTA: `Explore the Lineup`
- Secondary CTA: `See the Receipts`, with an accessible Quality Archive label
- Micro-proof: `Current compounds`; `Visible batch status`; `No documentation scavenger hunt`
- Dynamic product card: canonical product title, current price, and `View Compound`

## Section 2: Confidence strip

- `Live catalog pricing`
- `Current availability`
- `Batch records when available`
- `Direct support`

## Section 3: Featured compounds

- Eyebrow: `CURRENTLY IN THE CATALOG`
- Heading: `Start with what caught your eye.`
- Supporting copy: `The details are already waiting.`
- Section CTA: `Explore All Compounds`
- Product CTA: `View Compound`
- Dynamic fields: canonical title, image, current price, current stock state, and product URL

## Section 4: Why Pep Select

- Eyebrow: `WHY PEP SELECT`
- Heading: `Less guessing.` / `More to go on.`
- Supporting copy: `The compound gets your attention. Clear product details and available records help you take the closer look.`
- `Focused lineup` — `A focused catalog that is easier to explore.`
- `Details where you expect them` — `Current product information stays close to the compound.`
- `The deeper dive is there` — `Open the Quality Archive when you want the available batch-level detail.`

## Section 5: Batch identity

- Heading: `Nice label.` / `Now show me the batch.`
- Supporting copy: `When a record is available, the vial identifiers and batch details should connect without making you play detective.`
- CTA: `Review Batch Records`
- Explanatory labels: `Compound`; `Labeled Strength`; `Batch Number`; `Cap Color`; `Crimp Color`; `Current Status`
- Availability text: `Recorded when available`

## Section 6: Quality Archive

- Eyebrow: `PEP SELECT QUALITY ARCHIVE`
- Heading: `See what the label can't tell you.`
- Supporting copy: `Search by compound, follow current and previous records, and open the documentation available for each release.`
- Secondary signature: `Match the vial. Match the batch.`
- Primary CTA: `Open the Quality Archive`
- Action labels: `Search by compound`; `Follow batch history`; `Open the full record`

This section does not display simulated records, laboratory results, statuses, or statistics. Detailed records remain in `/testing/`.

## Section 7: FAQ

- Heading: `Questions before you order?`
- `What are Pep Select compounds intended for?` — `Research use only.`
- `Do all products include COAs?` — `Where available, documentation is associated with individual batches.`
- `Can I verify a batch?` — `Yes. Use the Quality Archive to search by compound and open available batch records.`
- CTA: `Read All FAQs`

Source: the supported FAQ subset in `site-exports/elementor/saved-page-pepselect-homepage-571.json`, with the obsolete order-link item removed and the batch-search destination updated to the verified Quality Archive route.

## Section 8: Final CTA

- Heading: `Found the compound?` / `Check what's behind it.`
- Supporting copy: `Explore the current lineup, or take the deeper dive inside the Pep Select Quality Archive.`
- Primary CTA: `Explore Compounds`
- Secondary CTA: `See the Receipts`, with an accessible Quality Archive label

## Dynamic and compliance rules

- Never type product names, IDs, images, prices, stock values, or scarcity into homepage copy.
- Do not claim universal testing, guaranteed quality or purity, comparative pricing, human use, health outcomes, or administration guidance.
- Do not imply every product has a COA or every identifier exists for every record.
- Keep detailed COA data and public status terminology inside the Quality Archive until it exposes a supported homepage interface.
- Orbitrex remains a high-level commercial-design benchmark only; none of its copy or branded material is used.
```

## 5.6 Other root files

| File | Lines | What it is |
| --- | --- | --- |
| `AGENTS.md` | 3.1 KB | Repository working rules: token discipline, build artifacts, safety boundaries, copy and messaging (§5.2). |
| `BACKLOG.md` | 2.6 KB | Open bugs and features. Contains the failed-batch archive bug and the only real batch numbers (§4). |
| `HANDOFF.md` | 37 KB | Full rebuild handoff, state captured July 17 2026. Describes the coded presentation layer over WordPress/WooCommerce and everything that must be preserved. |
| `pepselect-child/CHANGELOG.md` | — | Complete version history, currently at `0.19.0-beta.8`. |
| `site-exports/elementor/` | 12 JSON | Elementor exports of the **legacy** site and of other brands (`pepdivas`, `bq-about`, `bq-contact` = BioQuantum). Historical only; the coded theme has replaced these surfaces. |
| `graphify-out/` | — | Tooling AST cache. No content. |
| `.codex/skills/` | — | Generic agent skills (banner-design, design-system). Not Pep Select brand material. |
| `dist/` | 2 ZIP | Build artifacts, gitignored. |

## 5.7 Build artifact conventions — `AGENTS.md`

```markdown
## Build Artifacts
- Write every installable build ZIP, theme or plugin, to the `dist/` folder at the repository root.
- Name it `<package>-<version>.zip`, for example `dist/pepselect-child-0.17.0-beta.3.zip`.
- Never write build artifacts to the repository root, the theme folder, or anywhere else.
- Print the ZIP's SHA-256 after every build.
- Do not commit the contents of `dist/`.
```

---

# Summary of everything marked NOT FOUND

| Requested | Status | Where it actually lives |
| --- | --- | --- |
| Tailwind / theme.json / SCSS / tokens dir | NOT FOUND | Tokens are CSS custom properties in `foundations.css` |
| Webfont loading (`@font-face`, Google Fonts) | NOT FOUND | Parent theme or Elementor — neither in this repo |
| Global `h1`–`h4` type scale | NOT FOUND | Sizes are per-component; see §1.4 |
| Spacing scale tokens | NOT FOUND | Only gutters tokenised; de facto scale measured in §1.7 |
| Batch block rendering purity / net content / lab / test date | NOT FOUND | Pep Select COA Archive **plugin** (separate repo) |
| CSS for the batch identity block | NOT FOUND | Never written; the block is dead code |
| QR code on packing slip | NOT FOUND | EasyShip; backlog item, may not exist yet |
| About / our testing / transparency pages | NOT FOUND | No such templates exist |
| COA archive page intro text | NOT FOUND | COA Archive plugin |
| Failed-lot UI labelling | NOT FOUND | COA Archive plugin — **and currently broken, see §4** |
| All per-SKU product data (§4 in full) | NOT FOUND | WordPress/WooCommerce database — unreachable from here |
| Purity %, net content, test date for any SKU | NOT FOUND | COA Archive plugin |
| Marketing email templates | NOT FOUND | Only two transactional WooCommerce templates exist |
| VerifyPass button label | NOT FOUND | Authored HTML in WordPress page content |
| `confidential-copy-strategy.md` | Not present, and deliberately not reproduced | Local machine only |

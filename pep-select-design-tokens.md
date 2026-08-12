# Pep Select — Design Token Extraction

**Authoritative source:** `pepselect-child/assets/css/foundations.css` `:root` (the coded WEB-2 design system). A **legacy Elementor "kit-7" global palette** also exists in the DB with *different* values — it's flagged in each section and **does not win** on the coded brand pages (verified against live computed styles).

## 1. COLORS

### `:root` block, verbatim
```css
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
}
```

### Role map
| Role | Token | Hex | Where |
|---|---|---|---|
| **Primary / darkest** | `--pep-color-navy` | **#002A53** | headings, primary button bg, card title, borders on CTAs |
| Primary dark (hover) | `--pep-color-dark-navy` | **#001D3A** | primary button hover bg |
| **Accent** | `--pep-color-cyan` | **#17A1CF** | links/hover, link underline, CTA borders, cyan button bg, focus ring |
| Ink (body heading) | `--pep-color-ink` | **#13283D** | homepage body copy base |
| **Body text (muted/secondary)** | `--pep-color-slate` | **#5E6F80** | sublines, muted body, OOS text |
| Neutral (tertiary) | `--pep-color-neutral` | **#7A8793** | struck-through prices, faint meta |
| **Border / divider** | `--pep-color-border` | **#D7E1E9** | card borders, inputs, dividers, off-toggle track |
| **Page background** | `--pep-color-white` | **#FFFFFF** | page + card background |
| Section/card tint | `--pep-color-surface` | **#F3F8FC** | pills, OOS card bg, strength pill bg, panel bases |
| Card-image / info tint | `--pep-color-cyan-soft` | **#E8F6FB** | card image panel, cash-back pill bg, BAC upsell panel |
| Neutral tint | `--pep-color-soft-gray` | **#F5F6F7** | alt surfaces |
| Success / success tint | `--pep-color-green` / `-green-soft` | **#16834A** / **#EAF5EF** | in-stock, success states |
| Warning / warning tint | `--pep-color-amber` / `-amber-soft` | **#B46A00** / **#FFF4DF** | Square payment panel, testing band |
| Error / error tint | `--pep-color-red` / `-red-soft` | **#C43D3D** / **#FBECEC** | errors, inline failure text |

### Buttons (exact)
| Button | Background | Text | Border | Hover |
|---|---|---|---|---|
| **Primary CTA** (`--primary`) | **#002A53** navy | #FFFFFF | 1px transparent | bg **#001D3A**, shadow `0 12px 26px rgb(0 42 83 / 16%)` |
| **Cyan CTA** (`--cyan`) | **#17A1CF** | #001D3A | 1px transparent | bg #FFFFFF, text #001D3A, shadow `0 12px 28px rgb(0 0 0 / 16%)` |
| **Outline-navy** | transparent | #002A53 | 1px **#002A53** | bg #002A53, text #FFFFFF |
| **Outline-light** (on dark) | transparent | #FFFFFF | 1px `rgb(255 255 255 / 48%)` | bg `rgb(255 255 255 / 8%)`, border #FFFFFF |
| **Card action** (outline) | #FFFFFF | #002A53 | 1px **#17A1CF** cyan | bg #002A53, text #FFFFFF, border #002A53 |

### ⚠️ Conflicting source — legacy Elementor kit-7 palette (does NOT win on coded pages)
`--e-global-color-primary: #6EC1E4` · `--e-global-color-secondary: #54595F` · `--e-global-color-text: #7A7A7A` · `--e-global-color-accent: #61CE70`. These are Elementor's default palette (light blue / green) left in the kit; they only affect residual Elementor widgets. The raw `<body>` default text also computes to **#333333** (Hello Elementor), overridden per-component by the pep tokens above. **Use the pep tokens for replication.**

## 2. FONTS

### Stacks (from `:root`)
```css
--pep-font-editorial: Georgia, "Times New Roman", Times, serif;
--pep-font-interface: "Plus Jakarta Sans", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
--pep-font-technical: "IBM Plex Mono", "SFMono-Regular", Consolas, "Liberation Mono", monospace;
```
```css
--pep-font-weight-regular: 400;
--pep-font-weight-medium: 500;
--pep-font-weight-semibold: 600;
--pep-font-weight-bold: 700;
```

| Role | Family | Type | Weights in use |
|---|---|---|---|
| **Body / interface / most headings** | **"Plus Jakarta Sans"** | Google Font | 400, 500, 600, 700 |
| **Editorial** (compound names, editorial headings) | **Georgia** | **web-safe** (not loaded) | 700 (also 400) |
| **Technical** (batch IDs, prices, dose pills, SKUs) | **"IBM Plex Mono"** | Google Font | 500, 600 |

- Headings: mixed — UI headings render **Plus Jakarta Sans** (verified live), compound names use **Georgia**.
- **The child theme loads no fonts** (no `@font-face`/`@import`/enqueue). Plus Jakarta Sans + IBM Plex Mono are loaded site-wide via Google Fonts (through Elementor). Exact `<head>` links on live:
```html
<link href="https://fonts.googleapis.com/css?family=Plus+Jakarta+Sans:100,100italic,...,900,900italic&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=IBM+Plex+Mono:100,100italic,...,900,900italic&display=swap" rel="stylesheet">
<!-- also present but legacy Elementor defaults, not used by the coded system: -->
<link href="https://fonts.googleapis.com/css?family=Roboto:100,...,900italic&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Roboto+Slab:100,...,900italic&display=swap" rel="stylesheet">
```
> Note: the Elementor kit still names **Roboto / Roboto Slab** as its primary/secondary typography — legacy, not used by the coded pages.

## 3. SHAPE & STYLE

### Radius tokens (`:root`)
```css
--pep-radius-small: 8px;
--pep-radius-medium: 12px;
--pep-radius-large: 20px;
--pep-radius-pill: 999px;
```
| Element | Radius | Note |
|---|---|---|
| **Cards** | **32px** | raw px in `cards.css` (not a token) — this wins on the live archive/home cards |
| Card image panel | **16px** | raw px |
| **Buttons** — homepage | **8px** (`--pep-radius-small`) | primary/cyan/outline home buttons |
| **Buttons** — card action / checkout | **12px** (`--pep-radius-medium`) | card CTA, notify dialog inputs/buttons |
| Pills (dose, cash-back) | **999px** (`--pep-radius-pill`) | |
| Notify modal | **24px** | raw px |

> **Button radius conflict:** homepage buttons = **8px**; card/checkout buttons = **12px**. Both ship; each wins in its own context.

### Signature box-shadows (all navy-tinted, `rgb(0 42 83)` = #002A53)
```css
/* card hover */            box-shadow: 0 14px 34px rgb(0 42 83 / 12%);
/* primary button hover */  box-shadow: 0 12px 26px rgb(0 42 83 / 16%);
/* cyan button hover */     box-shadow: 0 12px 28px rgb(0 0 0 / 16%);
/* notify modal */          box-shadow: 0 30px 80px rgb(0 29 58 / 30%);
```
Cards and buttons carry **no resting shadow** — shadow appears on **hover** only (border-defined at rest). Card hover also lifts `transform: translateY(-2px)`.

### Other system values (bonus, from `:root`)
```css
--pep-content-max-width: 1200px;
--pep-gutter-desktop: 32px;  --pep-gutter-tablet: 24px;  --pep-gutter-mobile: 20px;
--pep-motion-duration: 180ms;   /* → 0.01ms under prefers-reduced-motion */
```

**Hero gradient** (raw hexes, not tokenized): `linear-gradient(115deg, #F3F8FC 0%, #DCE9F4 62%, #D5E4F1 100%)`.

---

**Bottom line for replication:** copy the `foundations.css` `:root` block (colors + fonts + radii + motion) as the token base; add card radius **32px**/image **16px** and the four navy hover-shadows as component tokens; load **Plus Jakarta Sans** and **IBM Plex Mono** from Google Fonts, keep **Georgia** as web-safe editorial. Ignore the Elementor kit-7 palette (#6EC1E4/#61CE70/Roboto) — it's legacy and doesn't drive the brand pages.


## 8. COMMERCIAL SURFACE CONVENTIONS (M12-16)

**Reference surface:** the checkout order-summary panel, built to the approved mockup
`checkout-panel-pepselect.html`. This supersedes earlier conventions where they conflict.

### Rules

- **White inner cards on a tinted panel, never tint on tint.** The panel is `--pep-surface-panel`
  (#F3F8FC); cards inside it are `--pep-surface-card` (#FFFFFF) with a 1px `--pep-color-border`
  edge. Tint inside tint is what produced the card-in-a-card defect repeatedly.
- **One card per surface.** A nested filled card is a defect, not a nuance. If a block needs
  separating, it becomes a white card or it gets whitespace — not a second fill and not a top rule.
- **Applied state is always a pill with an x**, never an inline row control. This holds for
  coupons and for cash back alike, so there is one mental model for removing anything.
- **Figures are always IBM Plex Mono; labels are always Plus Jakarta Sans.** Every currency value,
  code and quantity is mono; every label, heading and sentence is the interface face.
- **Only the total is emphasised.** Ordinary totals rows carry no borders and one padding value;
  the TOTAL row alone gets a top border, extra padding and 18px/600 mono navy on both label and
  value. No dividers between ordinary rows.
- **Amber is reserved for the Square payment instruction, and to exactly one container.** If an
  amber block ends up inside another amber block, the outer one is legacy and must be stripped to
  a transparent pass-through rather than removed from the DOM.

### Radius

`8px` joins the existing scale as the **inner-card radius**, recorded as
`--pep-radius-card-inner`. The panel itself uses 16px; the existing small/medium/large/pill tokens
are unchanged.

### Tokens added to `foundations.css`

| Token | Value | Why reusable |
|---|---|---|
| `--pep-radius-card-inner` | `8px` | inner-card radius, now part of the scale (8px per Paulo in M12-17; the mockup specified 6px) |
| `--pep-surface-card` | `#FFFFFF` | the white-card-on-tint pattern |
| `--pep-surface-panel` | `#F3F8FC` | the tinted commercial panel |
| `--pep-color-quiet` | `#7A8793` | tertiary control colour (Remove links) |
| `--pep-color-placeholder` | `#A8B4BF` | input placeholder |
| `--pep-totals-row-gap` | `6px` | totals rhythm |
| `--pep-totals-total-gap` | `14px` | totals rhythm, TOTAL row |

**Judged one-off, left in `checkout.css`:** panel width 420px and its 28px padding, the 16px panel
radius, the 1.54px label tracking, the -0.13px pill tracking, the 52px BAC thumbnail, and the
amber block's 2px border and #E8C99A rules. These describe one surface, not a system.

# Pep Select email — handoff

Everything done on Pep Select email work, as of 2026-08-13.
Written so someone with no prior context can pick this up.

---

## 1. Where things live

Email work is split across **two repositories**, both on a branch named
`claude/pepselect-email-campaign-ppwleb`. Same branch name, different repos —
this is the single easiest thing to get confused about.

| Repo | Path in container | Contains | Branch tip |
|---|---|---|---|
| `Beringela001/pep-select-website` | `/workspace/pep-select-website` | All real, sendable email work | `89dafb9` |
| `Beringela001/Email_marketing` | `/home/user/Email_marketing` | An 8-email campaign, unfinished, out of scope | `e58f33d` |

Everything sendable is in `pep-select-website/pepselect-email-campaign/`.

Note: the `pep-select-website` remote was originally set to lowercase
`beringela001`, which GitHub served via a redirect. It has been corrected to
canonical `Beringela001`.

---

## 2. There are three separate email artifacts

Easy to conflate. They are unrelated pieces of work.

### A. `pepselect-email.html` — "The label is the easy part."
The original email. Four product cards: GLP-3 R 10mg, GHK-Cu 50mg, NAD+ 500mg,
Tesamorelin 10mg. Complete and near-sendable.

- `pepselect-email.html` — master, keeps `{{GHKCU_URL}}`, `{{NAD_URL}}`,
  `{{TESA_URL}}`, `{{COMPANY_ADDRESS}}`, `{{preferences_url}}`, `{{unsubscribe_url}}`
- `pepselect-email.SEND.html` — same file with the three product URLs filled in
- `PREVIEW-open-me.html` — offline preview, five images inlined, GLP-3 R card
  loads from the live site
- `assets/` — five source images

**Known defect, unfixed:** the hero image at the top is still the old **30mg**
vial (`glp-3-rt-30mg-hero-1196.jpg`, alt "GLP-3 RT 30mg vial") while the card
below it says **10MG**. Commit `905cc03` swapped the card 30mg→10mg and left the
hero alone. There is no 10mg hero asset in the repo. Either supply one or drop
the hero before sending.

### B. `peptides-shouldnt-be-complicated.*` — the current build
Built 2026-08-08 from a written spec plus a mockup. This is the active work.

- `peptides-shouldnt-be-complicated.html` — master with four `{{DESC_*}}` placeholders
- `peptides-shouldnt-be-complicated.A.html` — mockup descriptions
- `peptides-shouldnt-be-complicated.B.html` — replacement descriptions
- `PREVIEW-A.html`, `PREVIEW-B.html` — offline previews, all images inlined
- `assets-final/` — 20 supplied assets plus 6 derived crops

A and B are generated from the same master by string substitution. Verified:
**347 lines each, 4 differing lines, all `card-desc` divs.** Nothing else differs.

Card copy:

| Compound | A (mockup) | B (replacement) |
|---|---|---|
| GLP-3 RT | Designed and studied to support weight management and metabolic pathways, including GLP-1, GIP, and glucagon. | A triple-target peptide acting on GLP-1, GIP, and glucagon. Researched for metabolic support and appetite regulation. |
| GHK-Cu | Designed and studied to support collagen production, skin health, and tissue remodeling. | A naturally occurring copper peptide, researched for collagen support, skin, and tissue repair. |
| NAD+ | Designed and studied to support cellular energy, mitochondrial function, and healthy aging. | The cellular energy molecule your cells run on. Researched for mitochondrial function and metabolic fuel. |
| Tesamorelin | Designed and studied to support growth hormone signaling, body composition, and metabolic health. | A growth-hormone peptide researched for body composition and lean-mass support. |

### C. `Email_marketing/campaigns/glp3r-ghk-cu/` — 8-email campaign
Eight complete HTML emails plus STRATEGY.md, COPY.md, BRAND-TOKENS.md.
Generated 2026-08-01. Every brand value is an unfilled `{{TOKEN}}` including
`{{LOGO_URL}}` and `{{SITE_URL}}`, so none is sendable.

**Explicitly declared out of scope by the owner.** Do not delete, merge, or
develop without a fresh instruction. Untouched since `e58f33d`.

---

## 3. Verified facts

**`pepselect.com` (apex) is canonical.** Confirmed in a browser by the owner,
not inferred:
- Page URLs: `www.pepselect.com/` **301-redirects** to `https://pepselect.com/`
- Image URLs under `/wp-content/`: **no redirect**, both hosts serve 200 directly

Consequence: links on `www` cost a redirect hop, images on `www` do not break.
Gmail's image proxy does not follow redirects, which is why this was chased down.

**Egress from the build container is blocked.** The proxy returns 403 to CONNECT
for both `pepselect.com` and `www.pepselect.com`, so nothing here can fetch or
verify anything on the live site. Every live-site fact in this document came
from the owner, not from a fetch.

---

## 4. Host inconsistency between the two emails — resolve before sending

| Email | Host used | Why |
|---|---|---|
| `pepselect-email.html` | **apex** — all 13 URLs | Normalized in `6b979d6` after verification |
| `peptides-shouldnt-be-complicated.*` | **www** — all URLs | The written spec said "use exactly" with `www` |

These contradict each other. The newer email carries a redirect hop on every
link, which is the exact thing four commits removed from the older one. Left as
specified rather than silently overridden. One `sed` fixes it:

```
sed -i 's|https://www\.pepselect\.com|https://pepselect.com|g' peptides-shouldnt-be-complicated*.html
```

---

## 5. Assets to upload before sending

All images are referenced as absolute URLs at
`https://www.pepselect.com/wp-content/uploads/2026/08/<filename>`.

**Already live** (used by the older email): `pepselect-logo-496.png`,
`glp-3-rt-30mg-hero-1196.jpg`, `glp-3-r-10mg-card.jpg`, `ghk-cu-50mg-card.jpg`,
`nad-500mg-card.jpg`, `tesamorelin-10mg-card.jpg`

**Not yet uploaded** — the newer email needs all 20 supplied assets plus these
6 derived crops, or the hero, cards and QR photo break:

| File | Size | Derived from |
|---|---|---|
| `hero-tall.jpg` | 352×440 | `hero.jpg` (560×448) cropped to portrait |
| `card-glp3-tile.jpg` | 380×304 | `card-glp3.jpg` (460×460) |
| `card-ghkcu-tile.jpg` | 380×304 | `card-ghkcu.jpg` |
| `card-nad-tile.jpg` | 380×304 | `card-nad.jpg` |
| `card-tesa-tile.jpg` | 380×304 | `card-tesa.jpg` |
| `qr-photo-tall.jpg` | 240×362 | `qr-photo.jpg` (520×436) |

The crops exist because the mockup shows the hero vial bleeding the full panel
height and the card vials cropped at the base, which the square/landscape
originals cannot do. **Originals are untouched** and still in `assets-final/`.

`mono-white.png` is supplied but unused — no slot for it in the spec or mockup.

---

## 6. Merge values for FluentCRM

Source: `UPLOAD-GUIDE.md:29-31`, confirmed 2026-08-04. Apex host.

```
{{GHKCU_URL}}  ->  https://pepselect.com/product/ghk-cu/
{{NAD_URL}}    ->  https://pepselect.com/product/nad/
{{TESA_URL}}   ->  https://pepselect.com/product/tesa-10/
```

Leave literal, the ESP substitutes them:
`##crm.manage_subscription_url##`, `##crm.unsubscribe_url##`,
`{{preferences_url}}`, `{{unsubscribe_url}}`

Only the owner can supply `{{COMPANY_ADDRESS}}` — **legally required** in the
footer of the older email. The newer email hard-codes
`2090 Baker Road, Ste 304 A85, Kennesaw, GA 30144`.

---

## 7. Open items

1. **Hero mismatch in `pepselect-email.html`** — 30mg hero over a 10MG card.
   Needs a 10mg hero asset, or drop the hero.
2. **`{{COMPANY_ADDRESS}}`** unfilled in the older email.
3. **Host inconsistency** between the two emails (section 4).
4. **6 crops need uploading** (section 5).
5. **Circled check icons** — the mockup shows circled checks in the QR
   checklist; the asset zip has no such icon, so plain `✓` is used. A CSS circle
   renders as a square in Outlook. Supply the icon to match exactly.
6. **Footer links and address kept** in the newer email though the mockup omits
   them. An unsubscribe link and postal address are legally required on
   marketing mail. Owner decision if they should go.
7. **Nothing has been sent or tested in a real client.** No Litmus or Email on
   Acid run. Rendering claims are based on construction, not observation.

---

## 8. Commit history (pep-select-website)

```
89dafb9  2026-08-08  Rebuild email to match the mockup, and split into versions A and B
449e869  2026-08-08  Build "Peptides shouldn't be complicated." email
44394c7  2026-08-08  Add send-ready email with product URLs filled in
73561f3  2026-08-05  Preview: drop GLP-3 R placeholder, load that card from the live site
6b979d6  2026-08-05  Normalize all email URLs to the apex domain
a278145  2026-08-05  Add email validation report
16c6157  2026-08-05  Point Tesamorelin card image at apex domain
905cc03  2026-08-04  Email v2: swap GLP-3 R 30mg -> 10mg, Glutathione -> Tesamorelin
7e3b30d  2026-08-02  Add GLP-3 R / GHK-Cu / NAD+ / Glutathione marketing email
238d65c  2026-08-01  docs: PepSelect brand extract for marketing email reference
```

`Email_marketing`: single commit `e58f33d`, 2026-08-01.

Both branches are pushed and in sync with their remotes. Working trees clean.

---

## 9. Build notes

Previews are generated by inlining `assets-final/` files as base64 data URIs,
so they open offline. There is no build tool in the repo — the generator was an
ad-hoc script. To rebuild: substitute each
`src="https://www.pepselect.com/wp-content/uploads/2026/08/<f>"` with a data URI
of `assets-final/<f>`.

Checks the newer email passes (both A and B):

| Check | Result |
|---|---|
| `grep -o '{{[A-Z_]*}}'` | none left in A/B; four `DESC_*` in master |
| `grep -c 'background-image'` | 0 |
| `grep -o '##[a-z_.]*##'` | the two FluentCRM codes only |
| File size | 28.3 KB (Gmail clips at ~102 KB) |
| `<img>` without `alt` | 0 of 20 |
| form / script / web font / flex / grid | 0 |

Construction: table layout, inline styles, no external CSS, no
`background-image` behind live text, `<style>` used only for media queries.
Mobile: container 100%, hero stacks text-first, cards stack single column,
QR photo hidden, shipping strip stacks, icon bar wraps 3-then-2.

# FluentCRM upload guide

## 1. Upload the images first

Media → Add New → upload all 6 files from `assets/`.

| File | Used for |
|---|---|
| `pepselect-logo-496.png` | Header logo |
| `glp-3-rt-30mg-hero-1196.jpg` | Hero banner |
| `glp-3-r-10mg-card.jpg` | GLP-3 R card — **not yet supplied, see below** |
| `ghk-cu-50mg-card.jpg` | GHK-Cu card |
| `nad-500mg-card.jpg` | NAD+ card |
| `tesamorelin-10mg-card.jpg` | Tesamorelin card — **provisional, see below** |

After uploading, copy each file's real URL from the media library.

The HTML currently points at `https://pepselect.com/wp-content/uploads/2026/08/<filename>`.
If WordPress puts them in a different month folder or renames a file (appending `-1`,
`-scaled`, etc.), find-and-replace the URLs in the HTML to match. **Every image URL must be
absolute and start with `https://pepselect.com/`** — relative paths break in email.

## 2. Fill in the placeholders

Search the HTML for `{{` and replace all four:

| Placeholder | Replace with | How to get it |
|---|---|---|
| `{{GHKCU_URL}}` | `https://pepselect.com/product/ghk-cu/` | confirmed 2026-08-04 |
| `{{NAD_URL}}` | `https://pepselect.com/product/nad/` | confirmed 2026-08-04 |
| `{{TESA_URL}}` | `https://pepselect.com/product/tesa-10/` | confirmed 2026-08-04 |
| `{{COMPANY_ADDRESS}}` | Physical mailing address | **legally required** in the footer |

GLP-3 R 10mg is already live at `https://pepselect.com/product/glp3-r10/`. The hero image
links to `/shop/`.

Each appears twice in the HTML (image link + button), so expect 2 replacements per URL.
Verify with a search for `{{` — it must return zero matches before you send.

## 3. Preferences and unsubscribe

`{{preferences_url}}` and `{{unsubscribe_url}}` are already in the footer. Swap them for
FluentCRM's own smartcodes when you paste:

```
##crm.manage_subscription_url##
##crm.unsubscribe_url##
```

FluentCRM will otherwise append its own unsubscribe block, which will duplicate the footer.

## 4. Paste the email

Campaigns → New Campaign → Email Body → the `{}` / **Raw HTML** or **Visual Builder → Code**
block. Paste the entire contents of `pepselect-email.html`.

Do **not** paste into the rich-text editor — it rewrites table markup and will break the
two-column card layout.

## 5. Subject line

```
Subject:  The label is the easy part.
Preview:  Everyone sells peptides. We research with our own compounds. Save 10% with WELCOME10.
```

## 6. Before sending

- Send a test to yourself. Open on a phone **and** in Outlook desktop if you have it.
- Confirm all 6 images load. If any are broken, the URL did not match the media library.
- Click every button — 4 product cards (image + button each), 1 shop link, plus the logo and hero.
- Confirm the discount code `WELCOME10` exists and is active in WooCommerce.
- Confirm all four compounds are actually in stock; each card says "In stock" in hard text.

## Known rendering notes

- **Outlook desktop squares off rounded corners.** Cards, buttons, and the strength pills
  will render as rectangles there. Everything else honors the radius. Not a defect.
- **Plus Jakarta Sans and IBM Plex Mono will not load** in most email clients. The stacks
  fall back to Arial and Courier New, which is expected and intentional.
- Georgia headlines render everywhere.

## Blocking: two card images

**`glp-3-r-10mg-card.jpg` does not exist yet.** `assets/` currently holds
`glp-3-r-10mg-card.PLACEHOLDER.jpg`, which is a stand-in so the preview lays out
correctly. It is stamped DO NOT UPLOAD. Replace it with a real 10mg product shot,
cropped 440x440 to match the framing of `ghk-cu-50mg-card.jpg`, and rename it to
`glp-3-r-10mg-card.jpg` before uploading. Do not substitute the 30mg photo — the
card is badged 10MG and links to `/product/glp3-r10/`.

**`tesamorelin-10mg-card.jpg` is provisional.** It was cropped from the
batch-matching infographic, so the vials are angled rather than upright, the caps
are blue rather than silver, and the background is flat grey rather than the lit
podium used on the other cards. It will read as a different shoot next to GHK-Cu
and NAD+. Replace it with a matching product shot when one exists.

## Copy needing sign-off

The Tesamorelin description — "A 44-amino-acid peptide studied as a growth
hormone-releasing factor analogue." — is new copy, not lifted verbatim from
`inc/data/compounds.md`. It is structural and mechanism-level with no outcome
claim, but it has not been through the compliance pass. Confirm before sending.

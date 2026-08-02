# FluentCRM upload guide

## 1. Upload the images first

Media → Add New → upload all 6 files from `assets/`.

| File | Used for |
|---|---|
| `pepselect-logo-496.png` | Header logo |
| `glp-3-rt-30mg-hero-1196.jpg` | Hero banner |
| `glp-3-r-30mg-card.jpg` | GLP-3 R card |
| `ghk-cu-50mg-card.jpg` | GHK-Cu card |
| `nad-500mg-card.jpg` | NAD+ card |
| `glutathione-600mg-card.jpg` | Glutathione card |

After uploading, copy each file's real URL from the media library.

The HTML currently points at `https://www.pepselect.com/wp-content/uploads/2026/08/<filename>`.
If WordPress puts them in a different month folder or renames a file (appending `-1`,
`-scaled`, etc.), find-and-replace the URLs in the HTML to match. **Every image URL must be
absolute and start with `https://www.pepselect.com/`** — relative paths break in email.

## 2. Fill in the placeholders

Search the HTML for `{{` and replace all four:

| Placeholder | Replace with | How to get it |
|---|---|---|
| `{{GHKCU_URL}}` | GHK-Cu 50mg product URL | see below |
| `{{NAD_URL}}` | NAD+ 500mg product URL | see below |
| `{{GLUTATHIONE_URL}}` | Glutathione 600mg product URL | see below |
| `{{COMPANY_ADDRESS}}` | Physical mailing address | **legally required** in the footer |

GLP-3 R is already live at `https://www.pepselect.com/product/glp3-r30/`.

To get the other three slugs, run on the server:

```bash
wp wc product list --user=1 --fields=id,name,slug,permalink --format=csv
```

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

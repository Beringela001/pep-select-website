# Live Site Icon Release — beta63 — 2026-08-28

## Outcome

Pep Select `0.25.0-beta.63` was released to Live with the approved standalone
hexagonal mark as the canonical site icon. The old June WhatsApp-image favicon
is no longer emitted in the homepage icon tags. No website copy, product data,
commerce behavior, or visible page layout changed.

## Implementation

- Extracted the standalone mark from the approved `PS_newLogoVector.ai` source
  without redrawing or recoloring it.
- Preserved transparency and added balanced square clear space so the mark
  remains legible in browser tabs and circular search-result treatments.
- Added optimized 32, 48, 180, 192, and 512 px PNG derivatives under the child
  theme's brand assets.
- Used WordPress's `get_site_icon_url` filter so core generates the standard
  browser, high-resolution, Apple-touch, feed, embed, and REST references from
  one stable icon family.
- Avoided the environment's failing Media Library thumbnail processor; the
  favicon no longer depends on an uploaded attachment or generated thumbnails.

## Package

- `dist/pepselect-child-0.25.0-beta.63.zip`
  - SHA-256: `7C55B4F4E8475B1321CCB6460239CB4135D45E383DD10E593F066B32B24D0889`
  - Size: 2,676,910 bytes
- Implementation commit: `c44c58d` (`Use new Pep Select mark for site icons`).

## Deployment safeguards

- Verified the 5/5 Live manual-backup limit and the bottom/oldest entry.
- Deleted only `Before exit offer 0.1.3 live deployment - 2026-08-28`, created
  Aug 28, 2026 at 10:49 PM, after explicit confirmation.
- Created and verified the new restore point
  `Before beta63 new site icon live - 2026-08-29` before deployment.
- Deployed and verified beta63 on Staging before Live.
- Cleared the Live WordPress/Kinsta cache after the theme replacement.

## Verification

- PHP syntax: passed for `inc/site-icon.php` and `inc/setup.php`.
- Staging emitted the new 32, 192, and Apple-touch icon URLs; all returned HTTP
  200 with `image/png`.
- Live theme marker: `0.25.0-beta.63`.
- Live emitted the new 32, 192, and Apple-touch icon URLs.
- Googlebot-Image requests returned HTTP 200 and `image/png` for every emitted
  icon; `robots.txt` does not block the theme asset directory.
- Live homepage, shop, GLP-3 R product, cart, and checkout returned HTTP 200.

Google may retain the previous search-result favicon until it recrawls the
homepage. Google documents that favicon refreshes can take several days to
several weeks; the new Live markup and crawlable stable URLs are already in
place.

# Pep Select Ad Starter

This folder turns the existing Pep Select brand and project assets into a repeatable ad-production system. It does not replace the website design system or product-marketing context.

## Start here

- `brand-profile.json` is the compact setup file for an ad tool or creative brief.
- `asset-manifest.csv` maps the approved source assets and how to use them.
- `ad-copy-bank.md` contains ready-to-test messaging by campaign angle.
- `creative-system.md` defines layouts, typography, color, and review rules.
- `exports/` contains three starter ads for Meta feed, square placements, and Story/Reels.
- `render_ads.py` regenerates the starter exports from the source assets.

## Brand position

Pep Select makes peptide research more transparent, accessible, and dependable through careful selection, clear product information, and available batch documentation.

## Production defaults

1. Choose one campaign angle and one CTA.
2. Use a real Pep Select product, vial, or documentation asset from the manifest.
3. Keep copy limited to what the selected asset and current records support.
4. Add `For research use only.` to every product or catalog creative.
5. Route product exploration to the catalog and evidence-led creative to the Quality Archive.
6. Verify current product, price, stock, batch, and laboratory details before publishing.

## Regenerate exports

From the repository root, run:

```powershell
python mockups/ads/pep-select-brand-starter/render_ads.py
```

The renderer uses the existing production logo, hero photography, and vial/batch imagery. It does not alter those source files.


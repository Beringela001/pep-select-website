# Source Map

Use this map to locate authoritative inputs without scanning unrelated files.

## Claude SEO source

- Plugin root: `C:\Users\paulo\.claude\plugins\marketplaces\AgriciDaniel-claude-seo`
- Installed manifest: `.claude-plugin/plugin.json`
- Main orchestrator: `skills/seo/SKILL.md`
- Topic skills: `skills/seo-*/SKILL.md`
- Optional integrations: `extensions/*/skills/*/SKILL.md`

The installed manifest reported Claude SEO version 2.2.4 during bridge creation on 2026-08-18. Read the manifest again when version-sensitive behavior matters.

Select only the topic skill needed for the supplied report. Examples:

| Report topic | Claude SEO source |
|---|---|
| Full audit | `skills/seo-audit/SKILL.md` |
| Technical/indexing | `skills/seo-technical/SKILL.md` |
| Content/E-E-A-T | `skills/seo-content/SKILL.md` |
| Schema | `skills/seo-schema/SKILL.md` |
| Ecommerce/product SEO | `skills/seo-ecommerce/SKILL.md` |
| Search Console/PageSpeed/CrUX | `skills/seo-google/SKILL.md` |
| GEO/AI search | `skills/seo-geo/SKILL.md` |
| Ahrefs | `extensions/ahrefs/skills/seo-ahrefs/SKILL.md` |
| DataForSEO | `extensions/dataforseo/skills/seo-dataforseo/SKILL.md` |

## Pep Select source

- Current combined handoff: `docs/SEO-GOOGLE-ADS-HANDOFF-2026-08-17.md`
- Catalog and COA integrity: `docs/SEO-M1-catalog-coa-integrity.md`
- Indexability and metadata: `docs/SEO-M2-indexability-metadata-semantics.md`
- Catalog schema and discovery: `docs/SEO-M3-catalog-schema-internal-discovery.md`
- Search Console and performance: `docs/SEO-M4-search-console-merchant-performance.md`
- Authority and crawl consolidation: `docs/SEO-M5-quality-archive-authority.md`
- Product and compliance context: `.agents/product-marketing.md`
- Pep Select implementation standards: `.codex/skills/pepselect-web-design/SKILL.md`

Read the combined handoff first. Read a milestone file only when the Claude finding touches that milestone.

## Interpretation rules

- A completed milestone is verified project history, not a suggestion to redo work.
- A newer Claude report may identify drift or regression after a milestone; verify before declaring either source wrong.
- Third-party platform estimates are directional evidence unless independently confirmed.
- Keep credentials, OAuth tokens, API keys, service accounts, `.env` files, and provider configuration outside the repository.

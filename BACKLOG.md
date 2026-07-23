# PepSelect Website — Backlog

Storefront and plugin work items. Ops-app milestones live in the ops repo
(`PEPSELECT-OPS-DESIGN-SYSTEM.md` §10 ladder, §12 backlog).

---

## Bugs

### COA Archive plugin — failed batches do not render on the public /testing/ archive

**Status:** open · **Priority:** high (transparency commitment)

Failed-status batches are marked Published but never appear on the public archive page.

- **Example:** NAD+ batch `PSNAD562926JP`, tested by ILS Labs, status **Failed**, marked **Published** — absent from the public page.
- **Expected:** per the transparency decision, failed batches **must** publish with a clear "failed — not offered for sale" status. Publishing a failure is a deliberate brand signal, not an edge case.
- **Investigate:** why failed-status batches are filtered out of the public archive query/render path, and surface them with the failed state displayed distinctly.

Related: ops design doc §13 "COA publishing (R4)" — `pass_status` is genuinely variable, and the archive is required to render passed and failed states distinctly.

---

## Notes

### Batch numbering — existing numbers predate R6, do not "fix"

Current batch numbers on the site (e.g. `PSNAD562926JP`, `ND_R30_060326`) predate the R6 batch-number system and **will not match its format**. This is expected, not a defect.

R6 reconciliation handles the format transition later. Do not retrofit or rewrite existing batch numbers.

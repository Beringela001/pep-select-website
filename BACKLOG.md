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

## Features

### Cart line item: batch number (blocked on ops)

**Status:** blocked · **Source of truth:** ops repo `PEPSELECT-OPS-DESIGN-SYSTEM.md` §13, "Batch number as a shared order field"

Show the batch number on each cart line item, in the slot vacated when the per-item short description was removed in `0.17.0-beta.6` — the CSS slot `.wc-block-components-product-metadata__description` is already hidden under `.wc-block-cart` in `assets/css/checkout.css` and marked reserved for exactly this. Blocked until the ops app assigns a specific batch to an order at fulfilment; until then the storefront knows only the *current* batch, which may not be the one that ships.

The batch is to be persisted as **order line-item meta on the order**, not recomputed per surface, and consumed from that single field by the order-received page, the completed-order email, and EasyShip labels and packing slips. Cart implementation should extend the Store API cart item schema rather than inject into the DOM. See the ops repo item for framings, acceptance criteria, and the EasyShip field investigation.

---

## Notes

### Batch numbering — existing numbers predate R6, do not "fix"

Current batch numbers on the site (e.g. `PSNAD562926JP`, `ND_R30_060326`) predate the R6 batch-number system and **will not match its format**. This is expected, not a defect.

R6 reconciliation handles the format transition later. Do not retrofit or rewrite existing batch numbers.

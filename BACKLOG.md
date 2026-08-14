# PepSelect Website — Backlog

Storefront and plugin work items. Ops-app milestones live in the ops repo
(`PEPSELECT-OPS-DESIGN-SYSTEM.md` §10 ladder, §12 backlog).

---

## Bugs

### COA Archive plugin — failed batches do not render on the public /testing/ archive

**Status:** resolved in COA Archive 0.5.6; staging re-verified 2026-08-14 · **Priority:** closed

Failed-status batches now appear on the public archive page while remaining excluded from Current status and product carousels.

- **Verified:** NAD+ batch `PSNAD562926JP` and Retatrutide 20mg batch `PSRT2062926JP` render on the staging archive as **Not Released**.
- **Preserved:** failed batches do not become Current and do not appear in product COA carousels.
- **Resolution:** COA Archive 0.5.6 made lone-failure compounds visible regardless of the retired design toggle.

Related: ops design doc §13 "COA publishing (R4)" — `pass_status` is genuinely variable, and the archive is required to render passed and failed states distinctly.

---

## Features

**M12 milestone plan:** the sequenced M12-1 to M12-5 work (checkout compliance checkboxes, account page simplification, cart and side cart redesign, side cart BAC offer, discount pill and loyalty redemption) is planned in [M12-compliance-account-cart-redesign.md](M12-compliance-account-cart-redesign.md).

### Cart line item: batch number (blocked on ops)

**Status:** blocked · **Source of truth:** ops repo `PEPSELECT-OPS-DESIGN-SYSTEM.md` §13, "Batch number as a shared order field"

Show the batch number on each cart line item, in the slot vacated when the per-item short description was removed in `0.17.0-beta.6` — the CSS slot `.wc-block-components-product-metadata__description` is already hidden under `.wc-block-cart` in `assets/css/checkout.css` and marked reserved for exactly this. Blocked until the ops app assigns a specific batch to an order at fulfilment; until then the storefront knows only the *current* batch, which may not be the one that ships.

The batch is to be persisted as **order line-item meta on the order**, not recomputed per surface, and consumed from that single field by the order-received page, the completed-order email, and EasyShip labels and packing slips. Cart implementation should extend the Store API cart item schema rather than inject into the DOM. See the ops repo item for framings, acceptance criteria, and the EasyShip field investigation.

---

### Restocking chip — batch to product mapping (blocked, do not build yet)

**Status:** blocked · **Trigger:** the first time a real batch enters a pre-approval stage

The out-of-stock chip on the compound card should read "Restocking soon" when the product is out of stock **and** a batch for that exact product is in a pre-approval stage (vendor vetting → under testing), and "Out of stock" otherwise. Latent today: nothing is in flight, so every out-of-stock card correctly reads "Out of stock". Verified 2026-08-08 against the plugin's own frontend — GLP-3 R 20mg's COA page shows "No new batches currently under vetting" and "no active vendor or laboratory verification records" — so this is **not a bug today**.

`inc/compound-status.php` maps `compound_id → ps_compound → woocommerce_product_id`, i.e. one product per compound. Batch placement is actually driven by the **SKU on the batch record**, not the compound link — a mislabeled RT20 batch once landed on the wrong product via its SKU. So the current chain will misfire on the first real pre-approval batch for a multi-strength compound (Retatrutide is RT10 / RT20 / RT30).

Needed before building, from wp-admin or plugin source (COA REST routes are auth-gated and the plugin source is not in this repo, so none of this is readable as a logged-out visitor):

- Does `ps_coa_test` carry a **SKU** field? If yes, re-key on **batch SKU → product SKU** instead of `compound_id → woocommerce_product_id`.
- The exact **workflow status meta key**, the **pre-approval status values** (verbatim), and the **`post_status`** in-flight batches use. Current code checks hyphenated slugs (`vendor-vetting`, `in-testing`) and `post_status=publish`; both may be wrong.
- Make the stage match **tolerant of separator and case** so hyphen/underscore drift cannot silently break it again.

Resilience to preserve: if the plugin is deactivated or the lookup fails, the chip must fall back to "Out of stock", never error or render blank. The current early-return guard plus the default `$oos_label` already do this.

**Carousel cross-contamination check** (done 2026-08-08, public product pages — not an issue today): the per-strength COA carousel is correct. glp3-r10 shows `RT2026205JP` (10mg), glp3-r30 shows `ND_R30_060326` (30mg), and glp3-r20 shows no current batch (its only batch, `PSRT2062926JP`, failed release review) without borrowing RT10 or RT30 records. Re-verify when the mapping code is built.

---

## Notes

### Batch numbering — existing numbers predate R6, do not "fix"

Current batch numbers on the site (e.g. `PSNAD562926JP`, `ND_R30_060326`) predate the R6 batch-number system and **will not match its format**. This is expected, not a defect.

R6 reconciliation handles the format transition later. Do not retrofit or rewrite existing batch numbers.

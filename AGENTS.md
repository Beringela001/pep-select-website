# Pep Select Repository Instructions

## Token and Cost Discipline
- Prefer targeted file reads and searches over full-repository scans.
- Do not reread unchanged audit or planning documents unless required evidence is missing.
- Reference existing documentation rather than repeating its full contents in prompts or reports.
- Prefix eligible shell, Git, search, test, diff, and log commands with RTK.
- Use raw command output only when exact unfiltered details are required.
- Keep implementation checkpoints small, testable, and reviewable.
- Keep completion reports concise: outcomes, risks, verification, commit, and worktree status.
- Do not rebuild a ZIP until a testable installation package is specifically requested.
- Match validation depth to the risk of the change; do not run broad validation after every isolated edit.
- Do not perform full-drive or full-repository scans without a clear need.
- Reuse confirmed project state rather than rediscovering it.

## Tool Discipline
- RTK is the default output-compaction tool for supported commands.
- Caveman is a communication-compression mode only; do not use it where clarity or safety would suffer.
- Do not initialize GSD `.planning/` state unless Paulo explicitly approves replacing or integrating the existing WEB milestone workflow.
- Do not use GSD when it would duplicate existing documents under docs/.
- Use Graphify only when dependency, architecture, or data-flow relationships genuinely require graph analysis.
- Do not build a Graphify graph for simple searches, isolated files, or straightforward edits.

## GitHub Ledger
- GitHub is the authoritative project ledger and universal record of completed Pep Select changes.
- A completed code, documentation, configuration, or deployment change is not finished until it is committed, pushed to the current approved GitHub branch, and verified so local `HEAD` matches the remote branch.
- Preserve and incorporate concurrent approved work from other Pep Select tasks; never overwrite it merely to restore an older local baseline.
- Skip the final push only when Paulo explicitly instructs that the change must remain local.

## Build Artifacts
- Write every installable build ZIP, theme or plugin, to the `dist/` folder at the repository root.
- Name it `<package>-<version>.zip`, for example `dist/pepselect-child-0.17.0-beta.3.zip`.
- Never write build artifacts to the repository root, the theme folder, or anywhere else.
- Print the ZIP's SHA-256 after every build.
- Do not commit the contents of `dist/`.

## Visual Conventions (commercial surfaces)
- The checkout order-summary panel is the reference surface; it supersedes earlier conventions where they conflict.
- White inner cards on a tinted panel. Never tint on tint.
- One card per surface. A nested filled card is a defect.
- Applied state is always a pill with an x, never an inline row control.
- Figures are always IBM Plex Mono; labels are always Plus Jakarta Sans.
- Only the total is emphasised. No dividers between ordinary rows.
- Amber is reserved for the Square payment instruction, in exactly one container.
- Inner-card radius is 6px (`--pep-radius-card-inner`). Full detail in `pep-select-design-tokens.md`.

## Safety Boundaries
- Live remains untouched unless Paulo explicitly authorizes a deployment milestone.
- When an authorized staging or live backup is required and manual capacity is full, always verify the list order, automatically delete only the oldest manual backup at the bottom, create the new backup, and continue the deployment without pausing for separate project-level approval.
- Never delete a newer backup to make space, and never assume a backup is oldest without checking its timestamp and bottom-list position.
- Preserve WooCommerce products, customers, orders, checkout, payments, shipping, rewards, VerifyPass, and COA business logic.
- Keep business logic out of the child theme.
- Never expose or commit credentials, tokens, private customer data, or environment secrets.

## Copy and Messaging
- For Pep Select copy tasks, read `.agents/product-marketing.md`.
- Also read `C:\Users\paulo\.codex\private\pepselect\confidential-copy-strategy.md` when it exists.
- Treat the private supplement as Pep Select-only confidential strategy.
- Never commit, quote, summarize, package, or reproduce the private supplement in completion reports or public artifacts.
- Use Product Marketing, Copywriting, CRO, Copy Editing, and Stop Slop in that order.
- Stop Slop is the final cleanup pass, not the primary writer.

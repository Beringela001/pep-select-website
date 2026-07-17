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

## Safety Boundaries
- Live remains untouched unless Paulo explicitly authorizes a deployment milestone.
- Preserve WooCommerce products, customers, orders, checkout, payments, shipping, rewards, VerifyPass, and COA business logic.
- Keep business logic out of the child theme.
- Never expose or commit credentials, tokens, private customer data, or environment secrets.

## Copy and Messaging
- For Pep Select copy tasks, read `.agents/product-marketing.md`.
- Also read `C:\Users\paulo\.codex\private\pepselect\confidential-copy-strategy.md` when it exists.
- Treat the private supplement as Pep Select-only confidential strategy.
- Never commit, quote, summarize, package, or reproduce the private supplement in completion reports or public artifacts.
- Use Product Marketing, Copywriting, compliance review, CRO, Copy Editing, and Stop Slop in that order.
- Stop Slop is the final cleanup pass, not the primary writer.
- Never introduce unsupported factual, comparative, laboratory, medical, or human-use claims.
- Mark uncertain claims with `[VERIFY CLAIM]`.
- Confidence must come from evidence and specificity, not disguised prohibited implications.

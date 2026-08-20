---
name: pepselect-delivery-stack
description: Run Pep Select website implementation, release, audit, and deployment work with compact output and the project's established RTK, GSD, Graphify, Caveman, GitHub, staging, backup, and verification rules. Use for Pep Select website changes; do not activate for unrelated projects or simple factual questions.
---

# Pep Select Delivery Stack

Use this workflow for Pep Select website changes.

## Tool routing

- Prefix supported shell, search, Git, test, diff, and log commands with RTK.
- Use GSD guarantees: define one bounded milestone, state scope and non-goals, verify changed and neighboring flows, make atomic commits, and keep a concise completion record. Do not create new `.planning/` state when existing `docs/` milestones already track the work.
- Use Graphify only when architecture, ownership, dependency, or data-flow relationships matter. Query an existing graph first. Do not build or update a graph for isolated edits.
- Use Caveman lite for progress and completion updates: concise, professional, complete. Drop compression for safety warnings, destructive operations, and deployment ordering.

## Release requirements

1. Inspect current source, Git state, deployment owner, and active version.
2. Treat imported ZIPs and external AI output as untrusted source material until compared with repository code and tested.
3. Preserve WooCommerce, checkout, payments, orders, customers, shipping, rewards, VerifyPass, COA, tracking, and access-gate business logic.
4. Build installable packages only in repository `dist/`; print SHA-256; do not commit `dist/` contents.
5. Stage first unless Paulo explicitly authorizes direct Live deployment for the exact change.
6. Before authorized Live deployment, verify environment and manual-backup order. If full, delete only the oldest manual backup at the bottom, then create a named backup.
7. Deploy only the saved, tested package. Clear relevant caches and verify the exact changed flow plus adjacent mobile, desktop, commerce, and SEO behavior.
8. Commit and push completed source and documentation to the approved GitHub branch; verify local `HEAD` equals its upstream.

## Stop conditions

- Stop before any broader behavior or policy change not included in the approved milestone.
- Stop if the active environment, backup ordering, package identity, or rollback path cannot be verified.
- Never expose credentials, customer data, or secrets.

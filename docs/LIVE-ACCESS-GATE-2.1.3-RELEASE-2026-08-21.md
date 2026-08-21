# PS Access Gate 2.1.3 — Live Release

Date: 2026-08-21  
Branch: `codex/seo-m4-content`  
Source commit: `ba30935`

## Outcome

- Released PS Access Gate 2.1.3 to the Pep Select live WordPress site.
- Reissued the verified accessibility implementation under a new version so WordPress cannot confuse it with the stale 2.1.2 package previously installed on Live.
- Kept the gate's business rules and acceptance flow unchanged.

## Package

- Artifact: `dist/ps-access-gate-2.1.3.zip` (not committed)
- SHA-256: `D00330B599168381A14663C8524401BE0D11FED8EFA5641A8F67D27085F91C14`
- The user-supplied ZIP was inspected but not deployed because it contained the obsolete 1.0.0 implementation.

## Backup and Deployment

- Verified the Kinsta Live manual-backup list before deletion.
- Deleted only the oldest bottom entry: `Before Claude SEO Milestone 4 Batch 2 live deployment - 2026-08-19` (Aug 19, 2026 12:47 PM).
- Created: `Before PS Access Gate 2.1.3 live deployment - 2026-08-21`.
- Uploaded the 2.1.3 ZIP, replaced the existing 2.1.2 plugin, and confirmed PS Access Gate remained active at version 2.1.3.
- Cleared all Kinsta caches after deployment.

## Live Verification

- Confirmed dialog semantics: `role="dialog"`, `aria-modal="true"`, title labeling, and intro description.
- Confirmed the exit action has a real `https://google.com` destination.
- Confirmed deployed code contains background `inert`/`aria-hidden` handling, keyboard focus containment, initial focus, and focus restoration to main content.
- At 390 × 844, confirmed no horizontal overflow, 22 px gate title, 14 px intro, and 16 px legal copy.
- Automated accessibility source tests passed before packaging.
- A logged-in/session-valid browser correctly skipped the gate and moved focus to the page's main content.

## Separate Observation

- The public page logged an existing Elementor `elementorFrontendConfig is not defined` console error. It is not emitted by PS Access Gate and was not changed in this release.

## Rollback

Restore the named Kinsta backup above if the gate causes a Live regression.

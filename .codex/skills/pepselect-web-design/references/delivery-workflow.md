# Delivery, Staging, and Release Workflow

## Milestone discipline

- Give every project a named milestone.
- State scope and non-goals before implementation.
- Stop at the milestone boundary.
- Do not bundle unrelated redesigns into defect fixes.

## Before changes

1. Confirm staging environment.
2. Create a named Kinsta manual backup.
   - If manual backup capacity is full on Staging or Live, delete only the oldest backup at the bottom of the list, then create the newest named backup.
3. Confirm the correct local repository.
4. Inspect Git status and current branch.
5. Export critical Elementor templates when they are in scope.
6. Record active theme, child theme, plugin versions, and relevant settings.

## Implementation

- Use source control for custom code and skill files.
- Keep database-held Elementor/menu changes documented separately.
- Preserve exported templates as references, not as automatic overwrite packages.
- Do not commit credentials, database dumps, full uploads, caches, logs, or vendor backups.

## Testing

- Run available syntax, static, unit, integration, and browser checks.
- Report unavailable testing honestly.
- Test the changed flow plus adjacent commerce and mobile regressions.
- Use real staging behavior before stable promotion.

## Packaging

- Use one top-level plugin folder for installable WordPress plugins.
- Validate archive paths, extraction, hashes, and activation path.
- Keep previous verified packages for rollback.

## Deployment

Provide beginner-friendly steps for:

- Backup
- Upload or deployment
- Cache clearing
- Smoke test
- Failure indicators
- Rollback

Never deploy automatically unless Paulo explicitly asks for the exact deployment action and the environment supports it safely.

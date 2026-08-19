---
name: pepselect-claude-seo
description: Apply findings from the installed Claude SEO plugin or its reports to the Pep Select website without changing their meaning. Use when reviewing, planning, implementing, testing, or tracking Claude SEO recommendations for Pep Select, including technical SEO, Search Console, schema, content, ecommerce, Core Web Vitals, GEO, backlinks, Ahrefs, Semrush, or DataForSEO findings.
---

# Pep Select Claude SEO Bridge

Use Claude SEO as the SEO methodology and Codex as the implementation partner.
Do not replace a supplied Claude SEO report with an independent Codex audit.

## Authority contract

1. Preserve each Claude SEO finding, priority, dependency, and success check in meaning.
2. Keep the original report separate from Codex notes. Never silently rewrite the source report.
3. Use the installed Claude SEO 2.2.4 skill only to interpret its terminology or workflow when the report is unclear.
4. Use verified repository and live evidence to determine whether and how a finding can be implemented.
5. Apply Pep Select safety, compliance, architecture, and approval boundaries to implementation. These boundaries may block or reshape an implementation, but they do not erase the original finding.
6. Challenge a specific finding only when current primary evidence or verified site state contradicts it. Show the evidence and the exact conflict. Do not make broad claims that Claude SEO is ineffective or fake.

## Required workflow

### 1. Intake the Claude SEO result

- Use the report, pasted findings, or exact report path supplied by Paulo.
- Record its date and Claude SEO version when available.
- If no report or path is available, ask Paulo for only that item. Do not substitute a new generic audit.
- Treat the supplied report as read-only source evidence.

### 2. Load only necessary context

- Read `references/source-map.md`.
- Read the current Pep Select SEO handoff and only the milestone documents relevant to the finding.
- Read `.agents/product-marketing.md` before any content, metadata, schema-copy, or claim change.
- Use the installed Claude SEO skill file matching the reported topic only when clarification is necessary. Do not load the entire plugin automatically.
- Use `$pepselect-web-design` for implementation and release boundaries.

### 3. Preserve the findings

Create a findings ledger before proposing changes. For every finding include:

| Field | Required content |
|---|---|
| Claude finding | Faithful quotation or meaning-preserving summary |
| Claude priority | Original priority without silent adjustment |
| Evidence | Report evidence and any verified local/live evidence |
| Existing Pep state | Already complete, partially complete, not started, superseded, or conflicting |
| Implementation status | Ready, needs evidence, needs Paulo approval, blocked by safety, or already satisfied |
| Reason | Concrete explanation without changing the source finding |

If a finding is already satisfied by a completed Pep Select milestone, mark it `already satisfied`; do not present it as new work.

### 4. Translate, do not reinterpret

For findings that require work:

1. Classify the affected element as Preserve, Refine, Replace, or Remove.
2. Identify its source of truth: Elementor, child theme, Site Core plugin, WooCommerce, COA plugin, WordPress database, Google, or another external service.
3. Separate presentation changes from business-logic changes.
4. Define exact files or administrative surfaces, tests, non-goals, rollback, and stop condition.
5. Ask for approval before implementation when scope has not already been approved.

### 5. Implement safely

- Preserve WooCommerce products, prices, stock, customers, orders, checkout, payments, shipping, rewards, VerifyPass, and COA relationships.
- Keep business logic out of the child theme.
- Never edit WordPress, WooCommerce, or third-party plugin core files.
- Never introduce unsupported medical, human-use, laboratory, purity, certification, performance, or comparative claims.
- Mark missing evidence as `[VERIFY CLAIM]`.
- Never submit URLs, modify Search Console, spend API credits, change third-party accounts, or deploy to Live without explicit approval.
- Make the smallest testable change that satisfies the Claude finding.

### 6. Verify and report

- Test the exact changed flow and neighboring regressions.
- Compare the result against Claude SEO's stated success or failure check when one exists.
- Report separately: Claude finding, implementation, verification, unresolved risk, and next action.
- Preserve uncertainty. Do not claim Search Console, rankings, Core Web Vitals field data, conversions, or recrawling changed unless measured.

## Evidence hierarchy

Use sources in this order when they overlap:

1. Verified Pep Select records and current site behavior.
2. Google Search Console, PageSpeed Insights, CrUX, and other first-party platform data.
3. Claude SEO's collected evidence and deterministic outputs.
4. Ahrefs, Semrush, DataForSEO, Similarweb, and other third-party estimates.

Always label third-party estimates. Never convert estimated visits or rankings into verified Google clicks.

## Stop conditions

Stop and ask Paulo before continuing when:

- a recommendation would change Live;
- a recommendation could change commerce or COA business logic;
- the report and verified Pep Select evidence conflict materially;
- a claim lacks support;
- a paid API or metered batch call is required;
- the correct system owner cannot be identified.


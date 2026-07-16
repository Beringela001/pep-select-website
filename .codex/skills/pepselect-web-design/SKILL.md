---
name: pepselect-web-design
description: Apply Pep Select-specific brand, UX, copy, responsive, WordPress, Elementor, WooCommerce, account, order-tracking, and deployment standards. Use for auditing, designing, rewriting, implementing, or reviewing any Pep Select website page, component, customer flow, Codex plan, or release. This skill overrides generic design recommendations when they conflict with Pep Select's approved brand position, existing COA system, ecommerce safety, mobile standards, or beginner-friendly deployment process.
---

# Pep Select Web Design

## Operating principle

Build Pep Select as a transparent, accessible, dependable research-peptide brand. Preserve working commerce and COA behavior while replacing weak presentation, broken dependencies, and generic marketplace copy.

When UI/UX Pro Max is available, use it for broad pattern discovery. Treat this skill as the final authority for Pep Select brand, architecture, copy, and release constraints.

## Required workflow

1. Inspect the current implementation before proposing changes.
2. Classify each relevant element as Preserve, Refine, Replace, or Remove.
3. Identify the source of truth: Elementor, theme, child theme, plugin, WooCommerce, WordPress database, or external service.
4. Separate presentation defects from business-logic defects.
5. Produce desktop and mobile behavior intentionally; never treat mobile as a squeezed desktop layout.
6. Preserve working ecommerce, COA, authentication, rewards, verification, tracking, and order data.
7. Implement only the approved milestone scope.
8. Test the exact changed flow and neighboring regressions.
9. Package, version, document, back up, and provide rollback instructions.

## Read references selectively

- For brand purpose and positioning, read `references/brand-foundation.md`.
- For page copy, microcopy, naming, and competitor differentiation, read `references/voice-and-copy.md` and `references/competitor-boundaries.md`.
- For visual systems and component decisions, read `references/design-system.md`.
- For WordPress, Elementor, WooCommerce, authentication, tracking, and code placement, read `references/wordpress-woocommerce.md`.
- For mobile, accessibility, browser widths, and responsive QA, read `references/responsive-standards.md`.
- For milestones, approvals, Git, staging, packaging, deployment, and rollback, read `references/delivery-workflow.md`.

## Non-negotiable brand rules

- Lead with documentation, clarity, and evidence rather than confidence theater.
- Make quality research more accessible without looking cheap or discount-driven.
- Use a calm clinical-modern presentation; avoid cold institutional sterility.
- Avoid bodybuilding, underground-lab, crypto, sci-fi, and aggressive biohacker aesthetics.
- Avoid generic luxury claims, inflated superiority language, and unsupported certainty.
- Never imply that price alone proves quality.
- Never fabricate testing, availability, scientific results, delivery promises, certifications, or customer outcomes.
- Preserve the approved COA visual language and truthful test-status distinctions.

## Copy requirements

- Write original Pep Select copy, not competitor-adjacent boilerplate.
- Prefer concrete nouns and verbs over vague adjectives.
- Keep sentences direct, readable, and confident without hype.
- Explain what Pep Select documents, verifies, publishes, or makes easier.
- Distinguish tested, incoming, previous, failed, pending, and untested states precisely.
- Do not use medical-treatment language or consumer health promises for research products.
- Do not use phrases listed as prohibited in `references/competitor-boundaries.md`.
- For important page copy, provide the final copy as locked content before implementation.

## Visual requirements

- Use the current Pep Select identity as the starting point, not a generic redesign preset.
- Anchor the interface in deep navy and established blue tones, with cyan/teal accents and restrained green for verified/pass states.
- Do not default to purple AI gradients, neon glows, glassmorphism, or excessive pills.
- Use whitespace to create hierarchy, not to leave pages sparse or unfinished.
- Use editorial composition, meaningful grouping, and clear information density.
- Keep cards visually coherent across catalog, account, tracking, and supporting pages.
- Every component must define default, hover, focus, active, disabled, loading, empty, success, warning, and error states when relevant.

## Architecture rules

- Use Elementor for editable marketing content and page composition.
- Use a child theme for theme-specific presentation, template overrides, and narrowly scoped WooCommerce visual integration.
- Use a custom Pep Select Site Core plugin for reusable business logic, integrations, authentication, secure order tracking, account enhancements, dynamic states, and functionality that must survive a theme change.
- Do not store secrets in Elementor, Git, JavaScript, or public markup.
- Do not add functionality to the COA plugin unless it belongs to COA management or presentation.
- Do not edit WordPress core, WooCommerce core, or third-party plugin files.
- Do not duplicate WooCommerce data models when its native customer, order, endpoint, nonce, and permission systems can be extended safely.
- Prefer removing fragile plugin dependence only after tracing all data, hooks, shortcodes, emails, and customer-facing behavior it currently provides.

## Ecommerce safety

Never change prices, stock, SKU relationships, taxes, discounts, payment behavior, shipping behavior, order states, customer data, or checkout validation as a side effect of a visual milestone.

For any account, login, tracking, cart, checkout, or product change:

- Preserve WooCommerce source-of-truth records.
- Use capability, nonce, rate-limit, privacy, and account-linking protections.
- Test logged-in, logged-out, empty, error, and mobile states.
- Use generic public errors when specific errors could reveal customer or order existence.
- Keep Google identity linking dependent on verified identity data and safe existing-account matching.

## Responsive standard

- Design desktop, tablet, and mobile as related but distinct compositions.
- Test actual containers, not only viewport width.
- Avoid arbitrary mid-word breaks, squeezed cards, horizontal page scrolling, hidden actions, and excessive stacked spacing.
- Keep mobile tap targets and form controls comfortable.
- Ensure fixed or floating elements do not cover cart, carousel, menu, form, or checkout controls.
- Preserve semantic labels and keyboard use when visually compacting interfaces.

## Delivery format

For design or implementation tasks, return:

1. Current-state finding
2. Root cause or design problem
3. Preserve / Refine / Replace / Remove decisions
4. Approved proposed experience
5. Desktop behavior
6. Mobile behavior
7. Copy, including exact labels and error states
8. Architecture placement
9. Security and data-safety notes
10. Acceptance criteria
11. Test checklist
12. Deployment and rollback steps

For Codex implementation prompts, include strict scope, non-goals, likely files, tests, package rules, deliverables, acceptance criteria, and a stop condition.

## Beginner-friendly guidance

When guiding Paulo through local setup, WordPress, Kinsta, Google Cloud, Git, or deployment:

- Give one checkpoint at a time.
- Wait for the result before moving forward.
- State exactly where to click or what command to paste.
- Explain what the step changes and what it does not change.
- Never assume technical familiarity.
- Ask for the complete error or a screenshot when a step differs from expectations.

## Approval discipline

- Do not turn explorations into permanent brand rules until Paulo approves them.
- Do not redesign unrelated pages during a scoped milestone.
- Do not overwrite approved COA work.
- Do not deploy automatically.
- Do not claim browser, database, payment, email, or live integration testing when it was unavailable.

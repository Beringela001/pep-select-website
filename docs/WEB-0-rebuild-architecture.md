# WEB-0 Rebuild Architecture

## Purpose

Pep Select will not be rebuilt as a collection of unrelated Elementor pages or copied templates. Each responsibility must live in the correct layer so the site remains maintainable.

## Elementor

Elementor will be used for:

- Marketing and editorial page layouts
- Approved homepage and supporting-page designs
- Reusable visual sections
- Product presentation where WooCommerce behavior remains intact
- Header and footer presentation when appropriate

Elementor must not contain business-critical PHP logic or credentials.

## Pep Select Site Core plugin

A custom Pep Select Site Core plugin will contain functionality that must survive theme or Elementor changes, including:

- Account and registration enhancements
- Google sign-in integration
- Public order tracking
- WooCommerce customer and order behavior
- Rewards presentation integrations
- Dynamic header and account data
- Custom shortcodes, endpoints, and API integrations
- Site-specific business rules

## Theme layer

Hello Elementor remains the active theme during the audit.

A child theme will only be introduced when theme-level templates or styles are genuinely required. It will not be created simply to hold unrelated functionality.

## Existing plugins

Existing plugins must not be replaced, updated, or removed until their purpose, dependencies, and data are audited.

## Design rule

Legacy Peptides Divas and BioQuantum templates are recovery references only. The Pep Select rebuild must use an original design system, content structure, visual language, and responsive experience created specifically for Pep Select.

## Deployment rule

Development and testing must occur on staging or locally before production changes.

Every production deployment must have:

- A current Kinsta backup
- A documented rollback point
- A clean Git commit
- Desktop and mobile verification
- WooCommerce purchase-flow testing

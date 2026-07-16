# WordPress, Elementor, and WooCommerce Architecture

## Placement decision

### Elementor

Use for:

- Marketing page composition
- Editable headings, imagery, and approved copy blocks
- Reusable visual sections that do not contain sensitive logic
- Theme Builder headers and footers when administration needs visual editing

Do not use for:

- Secrets
- Authentication verification
- Order lookup security
- Rate limiting
- Complex account data logic
- Canonical product or order relationships

### Child theme

Use for:

- Theme-specific templates and hooks
- Narrow WooCommerce presentation overrides
- Global site styling that is coupled to the active theme
- Header/footer presentation only when the current architecture requires theme code

Do not place portable business logic here.

### Pep Select Site Core plugin

Use for:

- Google identity integration and account linking
- Secure public order tracking
- My Account enhancements and endpoints
- Reusable shortcodes, blocks, REST/AJAX handlers, and service classes
- Dynamic logged-in/logged-out behavior
- Integration adapters for tracking, rewards, and verification
- Data migrations and versioned settings
- Functionality that must survive a theme change

## WooCommerce rules

- Keep WooCommerce orders, customers, products, addresses, and endpoints canonical.
- Extend native permission, nonce, and validation systems.
- Do not copy order data into a parallel custom database without a proven need.
- Do not expose billing, shipping, payment, or customer details in public tracking results.
- Match public tracking using the order identifier and billing email, then show only permitted fields.
- Rate-limit failed tracking attempts and use generic mismatch messages.
- Test guest orders and account orders separately.

## Google sign-in

- Use a supported Google identity flow.
- Verify identity server-side.
- Link only through verified identity data.
- Avoid duplicate WordPress users for the same verified email.
- Preserve historical WooCommerce orders and customer metadata.
- Store client secrets outside public code and markup.
- Support separate approved origins and redirect settings for staging and production.
- Provide recovery through normal WordPress password reset when appropriate.

## Dependency review

Before replacing a plugin:

1. Identify every shortcode, widget, hook, database table, option, endpoint, email, cron event, and customer-facing state it provides.
2. Export or document its data.
3. Build and test the replacement in parallel on staging.
4. Remove the plugin only after all required behavior and migration checks pass.

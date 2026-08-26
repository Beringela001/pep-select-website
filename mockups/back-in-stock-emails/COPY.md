# Pep Select back-in-stock emails

Status: copy and visual mockup for review. No WordPress settings or live templates changed.

## Current source

The Back In Stock Notifier for WooCommerce plugin owns both messages. WooCommerce lists them as:

- `Back In Stock - Subscription Confirmation`
- `Back In Stock - Product Available`

Both messages are enabled and use HTML email mode. The redesigned Pep Select order emails live in `pepselect-child/woocommerce/emails/`, while these two emails still use the plugin's basic WooCommerce content fields.

## Shared settings

- From name: `Pep Select`
- From address: `support@pepselect.com`
- Reply-to: `support@pepselect.com`
- Greeting: `Hi there,`

The neutral greeting avoids the current empty `Hello ,` when a subscriber does not provide a name.

## Email 1: Subscription confirmation

- Trigger: immediately after someone requests a stock notification
- Subject: `You're on the list for {only_product_name}`
- Preheader: `We'll email you when {only_product_name} is available again.`
- Email heading: `We'll keep an eye on it`
- Eyebrow: `Stock notification confirmed`
- Body: `We saved your request for {only_product_name}. When it returns to stock, we'll send one email to {subscriber_email}.`
- Friendly note: `Stock watch is on. You can give the refresh button a rest.`
- Product status: `Waiting for restock`
- CTA: `Review product details`
- CTA destination: `{product_link}`

## Email 2: Product available

- Trigger: when the subscribed product returns to stock
- Subject: `{only_product_name} is available again at Pep Select`
- Preheader: `You asked us to let you know when {only_product_name} returned to stock.`
- Email heading: `Good news. It's back.`
- Eyebrow: `Your stock notification`
- Body: `{only_product_name} is available again. You asked us to let you know, so this is the note.`
- Product status: `Available now`
- Supporting copy: `Review the product information and available batch documentation before ordering.`
- CTA: `View {only_product_name}`
- CTA destination: `{product_link}`
- Closing: `Thanks for asking us to keep an eye on it.`

## Placeholder mapping

- Product name: `{only_product_name}`
- Product image: `{product_image}`
- Product page: `{product_link}`
- Subscriber email: `{subscriber_email}`
- Shop name: `{shopname}`

The plugin also exposes `{cart_link}`, but the proposed availability email links to the product page. This gives the customer a chance to review current product information and available documentation before adding the item to the cart.

## Implementation notes for a later milestone

1. Keep the plugin responsible for subscriptions, stock triggers, subscriber records, and delivery timing.
2. Replace only the presentation layer through supported WooCommerce or plugin template hooks. Do not edit the third-party plugin.
3. Add the hidden preheader in the template because the plugin settings screen does not provide a preheader field.
4. Preserve every placeholder and product link through HTML escaping appropriate to its output type.
5. Test a subscriber with no name, a long product name, a missing product image, and mobile email clients.
6. Confirm each notification sends once and that plugin unsubscribe or privacy behavior remains intact before release.

## Pre-release email tests

- Gmail desktop and mobile
- Outlook desktop and web
- Apple Mail
- Product image present and missing
- Plain-text fallback
- Link tracking and product URL
- Sender and reply-to address
- Subscription confirmation immediately after signup
- Availability email after a controlled staging stock change

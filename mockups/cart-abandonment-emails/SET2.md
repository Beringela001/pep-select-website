# Pep Select cart-recovery sequence: Set 2

Status: warmer copy and visual mockups for review. Set 1 remains unchanged. No live settings changed.

## Tone direction

Set 2 sounds like a person at Pep Select wrote to one customer. The messages give the customer room to decide, offer practical help, and present the final coupon as a thank-you rather than a sales push.

## Global settings

- From name: `Pep Select`
- From address: `support@pepselect.com`
- Reply-to address: `support@pepselect.com`
- Cart cut-off: 60 minutes
- Recovery UTM parameters:
  - `utm_source=cart_recovery`
  - `utm_medium=email`
  - `utm_campaign=abandoned_cart_set2`

## Email 1: A thoughtful reminder

- Send: 30 minutes after the cart becomes abandoned, about 90 minutes after cart activity stops with the current cut-off.
- Subject: `We saved your Pep Select cart for you`
- Preheader: `Your selected research items are right where you left them.`
- Heading: `Still thinking it over?`
- Body: `You left a few items in your cart, so we saved them for you. Take your time reviewing the product details and available documentation. Your cart will be ready when you want to come back.`
- CTA: `Continue where I left off`
- Coupon: none

## Email 2: A personal offer to help

- Send: 24 hours after the cart becomes abandoned.
- Subject: `Can we help with your saved cart?`
- Preheader: `Questions about a product, documentation, shipping, or checkout? Reply to us.`
- Heading: `Is there anything we can help with?`
- Body: `Your cart is still saved. If a question made you pause, send us a reply. We can help you find product details, available documentation, shipping information, or the right place to continue at checkout.`
- CTA: `Take another look`
- Coupon: none

## Email 3: A warm 10% thank-you

- Send: 72 hours after the cart becomes abandoned.
- Subject: `A thank-you from Pep Select: 10% off your cart`
- Preheader: `If you are still considering your saved items, we would like to offer 10% off.`
- Heading: `We would like to offer you 10% off`
- Body: `We know a research order can take time to review. If you are still considering the items in your cart, we would like to offer 10% off.`
- Coupon heading: `A thank-you from Pep Select`
- Coupon message: `Use your code below, or click the button and we will apply it for you. You can use the discount for the next 48 hours.`
- Closing: `Whatever you decide, thank you for considering Pep Select.`
- CTA: `Return to my cart with 10% off`
- Coupon type: percentage discount
- Coupon amount: 10
- Auto-apply coupon: enabled
- Expiry: 48 hours after the email
- Individual use only: enabled
- Free shipping: disabled

## Dynamic fields

- Customer name: `{{customer.firstname}}`
- Product table: `{{cart.product.table}}`
- Recovery link: `{{cart.checkout_url}}`
- Coupon code: `{{cart.coupon_code}}` in email 3 only
- Unsubscribe: `{{cart.unsubscribe}}`

## Coupon behavior

When email 3 becomes due, the plugin creates a unique WooCommerce coupon for that abandoned cart. The customer returns through `{{cart.checkout_url}}`, which restores the cart and applies the coupon. The message also displays `{{cart.coupon_code}}` as a fallback.

The 10% discount applies to eligible product subtotals. It does not reduce shipping or tax. Before activation, staging tests must confirm how the coupon interacts with sale items, automatic free-vial promotions, YITH pricing, rewards, and the free-shipping threshold.

## Required pre-live checks

1. Add and approve the cart-recovery privacy disclosure.
2. Confirm only this plugin sends abandoned-cart messages; keep Klaviyo recovery flows off.
3. Build the final table-based HTML from the approved set.
4. Send previews to Gmail and Outlook.
5. Run a complete abandoned-cart test on staging.
6. Verify cart restoration, coupon application, expiry, product eligibility, unsubscribe handling, and suppression after purchase.

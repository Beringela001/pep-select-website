# Pep Select cart-recovery sequence

Status: copy and visual mockups for review. No live settings changed.

## Global settings

- From name: `Pep Select`
- From address: `support@pepselect.com`
- Reply-to address: `support@pepselect.com`
- Use WooCommerce email style: disabled for the final custom HTML build
- Cart cut-off: 60 minutes
- Recovery UTM parameters:
  - `utm_source=cart_recovery`
  - `utm_medium=email`
  - `utm_campaign=abandoned_cart`

## Email 1

- Send: 30 minutes after the cart becomes abandoned. With the current 60-minute cut-off, the first reminder arrives about 90 minutes after the cart activity stops.
- Subject: `Your Pep Select cart is saved`
- Preheader: `Your selected research items are still in your saved cart.`
- CTA: `Return to my cart`
- Coupon: none

## Email 2

- Send: 24 hours after the cart becomes abandoned
- Subject: `Need help with your Pep Select cart?`
- Preheader: `Your cart is saved, and our support team can help with checkout questions.`
- CTA: `Review my saved cart`
- Coupon: none

## Email 3

- Send: 72 hours after the cart becomes abandoned
- Subject: `10% off the research items in your saved cart`
- Preheader: `Return through this email and your unique 10% cart discount will apply at checkout.`
- CTA: `Apply 10% and return to checkout`
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

The plugin creates a unique WooCommerce coupon for the abandoned cart when email 3 becomes due. The customer returns through `{{cart.checkout_url}}`, which restores the cart and auto-applies the code. The email also displays `{{cart.coupon_code}}` as a fallback.

The 10% discount should apply to eligible product subtotals. It should not reduce shipping or tax. Before activation, staging tests must confirm how the coupon interacts with sale items, automatic free-vial promotions, YITH pricing, rewards, and the free-shipping threshold.

## Required pre-live checks

1. Add and approve the cart-recovery privacy disclosure.
2. Confirm only this plugin sends abandoned-cart messages; keep Klaviyo recovery flows off.
3. Build the final table-based HTML from the approved mockups.
4. Send previews to Gmail and Outlook.
5. Run a complete abandoned-cart test on staging.
6. Verify cart restoration, coupon application, expiry, product eligibility, unsubscribe handling, and suppression after purchase.

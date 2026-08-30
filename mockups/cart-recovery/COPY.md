# Pep Select Email Offer and Cart Recovery Copy

Status: implementation copy for the four-email recovery sequence.

## Exit offer form

Eyebrow: `Before you go`

Headline: `Your email just found 20% off.`

Body: `Drop it below and we will send a private discount code straight to your inbox.`

Field placeholder: `Email address`

Button: `Send my 20% code`

Inbox note: `Your code comes with occasional product and restock emails. Unsubscribe anytime.`

## Email 0: Immediately after signup

Subject: `Your private 20% code from Pep Select`

Preheader: `The private code you asked for is inside.`

Eyebrow: `Fair trade`

Heading: `Your 20% code has landed.`

Body: `You gave us your email. We promised 20% off. Fair trade.`

Coupon note:

- Heading: `Your private code`
- Code: dynamically generated 20% code
- Body: `Use this code at checkout with the same email address. It expires in the configured number of days and can be combined with eligible offers.`

Button: `Explore compounds`

Support note: `Questions before you order? Reply to this email and a real person from Pep Select will help.`

## Email 1: About 90 minutes

Subject: `Your cart kept your place`

Preheader: `Your saved items are ready when you are.`

Eyebrow: `Where you left off`

Heading: `Your cart kept your place.`

Body:

`Hi {{customer.firstname}},`

`You got pulled away. Your cart did not take it personally. Your items are still here when you are ready.`

Saved-cart note:

- Heading: `Right where you left it`
- Body: `Open your saved cart to review the same items, product details, and available batch documentation.`

Button: `Return to my cart`

Button note: `This link restores your saved cart. Any discount already connected to it comes along too.`

Support note: `Have a question first? Reply here or write to support@pepselect.com. Ordering is optional. Our humans will answer your questions.`

## Email 2: 24 hours

Subject: `Did a question stop your checkout?`

Preheader: `Ask us about a product, batch document, shipping, or checkout.`

Eyebrow: `A quick check-in`

Heading: `The tabs multiplied. We get it.`

Body:

`Hi {{customer.firstname}},`

`Your cart is still saved. If a product detail, batch document, shipping question, or checkout issue made you pause, reply and tell us what got in the way.`

Help note:

- Heading: `Skip the chatbot obstacle course`
- Body: `Send us the question. A real person will help you find the useful answer.`

Button: `Review my cart`

Button note: `This link returns you to the same saved items.`

Support note: `Have a question first? Reply here or write to support@pepselect.com. Ordering is optional. Our humans will answer your questions.`

## Email 3: 48 hours

Subject: `We found another 5% for your cart`

Preheader: `This new 5% code can be used with your private 20% code.`

Eyebrow: `One last nudge`

Heading: `We found another 5%.`

Body:

`Hi {{customer.firstname}},`

`Your cart is still here, so we added a little more encouragement.`

`Use this new 5% code with the private 20% code we already sent you.`

Coupon note:

- Heading: `Your extra 5% code`
- Code: `{{pepselect.bonus_coupon_code}}`
- Body: `Enter this code with your private 20% code at checkout. Both codes are restricted to the email address that received them.`

Button: `Return to my cart`

Support note: `Have a question first? Reply here or write to support@pepselect.com. Ordering is optional. Our humans will answer your questions.`

## Coupon behavior

The exit offer creates the original 20% code and emails it immediately. The 48-hour email creates a separate 5% code. Both coupons are email-restricted, single-use, and configured to combine with eligible offers. The extra 5% code is not created by the 90-minute or 24-hour emails.

All three recovery emails use `{{cart.checkout_url}}` for the button, `{{cart.product.table}}` for the cart, and `{{cart.unsubscribe}}` for the reminder unsubscribe link.

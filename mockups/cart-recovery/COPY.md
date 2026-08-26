# Pep Select Stay in the Loop and Cart Recovery Copy

Status: revised review set. No live changes.

## Stay in the Loop form

Eyebrow: `Join the list`

Headline: `Stay in the loop and get an additional 10% off.`

Body: `Get new product updates, restock notes, and the occasional useful email.`

Field placeholder: `Email address`

Button: `Get 10% off`

Inbox note: `We respect your inbox. Unsubscribe anytime.`

## Email 1: About 90 minutes

Subject: `A quick note about your saved cart`

Preheader: `Your items are still here, along with the details you were reviewing.`

Eyebrow: `Where you left off`

Heading: `Your cart is saved.`

Body:

`Hi {{customer.firstname}},`

`Your cart is still here. No countdown clock or dramatic music. You can pick up where you left off whenever you are ready.`

Saved-cart note:

- Heading: `Your cart is saved`
- Body: `The product details and available batch documentation are ready when you are.`

Button: `Return to my cart`

Button note: `This link restores your saved cart. If you have a discount code, it comes along too.`

Support note: `Have a question first? Reply here or write to support@pepselect.com. Ordering is optional. Our humans will answer your questions.`

## Email 2: 24 hours

Subject: `A quick check-in on your saved cart`

Preheader: `If a product, document, or checkout question stopped you, reply here.`

Eyebrow: `A quick check-in`

Heading: `The tabs multiplied. We get it.`

Body:

`Hi {{customer.firstname}},`

`Your cart is still saved. If a product detail, batch document, shipping question, or checkout issue made you pause, reply and tell us what you need.`

Help note:

- Heading: `Skip the chatbot obstacle course`
- Body: `Send us your question. We will help you find the useful answer.`

Button: `Review my cart`

Button note: `This link returns you to the same saved items.`

Support note: `Have a question first? Reply here or write to support@pepselect.com. Ordering is optional. Our humans will answer your questions.`

## Email 3: 48 hours

Subject: `A little help with the research decision`

Preheader: `Your 10% code is now 15% off.`

Eyebrow: `A little help`

Heading: `Your code is now 15% off.`

Body:

`Hi {{customer.firstname}},`

`We get it. Research decisions can feel as complicated as a chain of amino acids.`

`Let us make this one a little easier. Your discount code is now 15% off.`

Coupon note:

- Heading: `Your upgraded code`
- Code: `{{cart.coupon_code}}`
- Body: `Use it on eligible items, or use the button below and we will bring it back with your cart.`

Button: `Return to my cart`

Support note: `Have a question first? Reply here or write to support@pepselect.com. Ordering is optional. Our humans will answer your questions.`

## Coupon behavior

The Stay in the Loop form creates the original 10% code. The 48-hour email upgrades that same code to 15%. It does not create a second code.

All three recovery emails use `{{cart.checkout_url}}` for the button, `{{cart.product.table}}` for the cart, and `{{cart.unsubscribe}}` for the reminder unsubscribe link.

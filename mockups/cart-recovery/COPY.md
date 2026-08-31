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

Support note: `Have a question? Reply to this email, and one of our team members will be in touch shortly.`

## Email 1: About 90 minutes

Subject: `Want another look?`

Preheader: `Your Pep Select cart is ready when you are.`

Eyebrow: `Your saved cart`

Heading: `Pick up where you left off.`

Body:

`Hi {{customer.firstname}},`

`The compounds you selected are still in your cart if you would like to take another look.`

Button: `View my cart`

Support note: `Have a question? Reply to this email, and one of our team members will be in touch shortly.`

## Email 2: 24 hours

Subject: `Need a hand?`

Preheader: `Questions before ordering? Just reply to this email.`

Eyebrow: `A quick note`

Heading: `Any questions?`

Body:

`Hi {{customer.firstname}},`

`Just a quick note to let you know your cart is still available if you would like another look.`

`Have a question? Reply to this email, and one of our team members will be in touch shortly.`

Button: `View my cart`

Support note: None. The reply invitation already appears above the cart.

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

Support note: `Have a question? Reply to this email, and one of our team members will be in touch shortly.`

## Coupon behavior

The exit offer creates the original 20% code and emails it immediately. The 48-hour email creates a separate 5% code. Both coupons are email-restricted, single-use, and configured to combine with eligible offers. The extra 5% code is not created by the 90-minute or 24-hour emails.

All three recovery emails use `{{cart.checkout_url}}` for the button, `{{cart.product.table}}` for the cart, and `{{cart.unsubscribe}}` for the reminder unsubscribe link.

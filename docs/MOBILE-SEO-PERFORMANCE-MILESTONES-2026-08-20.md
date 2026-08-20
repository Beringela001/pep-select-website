# Pep Select Mobile SEO and Performance Milestones

Date: August 20, 2026  
Status: Proposed; no website changes made by this plan

## The plain-English objective

Mobile speed is one supporting signal, not the engine that creates organic traffic. This plan makes Pep Select faster and easier to use on slower phones while protecting checkout, COA, product, tracking, and research-access behavior.

The current website foundation is sound enough to grow from. We should not rebuild it or chase a perfect Lighthouse score. The useful target is a stable, meaningfully faster mobile experience, followed by content and authority work that gives Google reasons to rank Pep Select.

## What the audit proves today

- Google can discover the site: all 44 genuine indexable URLs are represented across five sitemaps.
- Important pages are indexed, including Shop, Testing, the guide, and Retatrutide 10 mg.
- Desktop performance is strong: 96.8 average in the audit.
- Mobile performance is inconsistent rather than broken: 71.6 average, 75 median, and 56–81 across repeated laboratory tests.
- Mobile LCP ranged from 3.8 to 7.5 seconds under simulated slow-phone conditions.
- Hosting response time and layout stability are already healthy.
- Organic visibility is still very early: 0 clicks and 7 impressions in the measured 90-day Search Console window, with average position 45.7.

## Milestone 1 — Mobile payload and image cleanup

### Work

- Correctly size and compress the research-gate logo and header logo.
- Correctly size and compress the Shop Glutathione image and homepage Tesamorelin image.
- Preserve image quality, labels, batch identifiers, and COA evidence.
- Add or verify explicit image dimensions so the browser reserves the right space.
- Keep decorative and below-the-fold media out of the initial mobile download where safe.

### Why it helps

Phones download fewer unnecessary bytes and can paint the important part of the page sooner. This is the safest performance work because it does not alter business logic.

### Verification gate

- Stage first.
- Test Home, Shop, Testing, one product, and one batch-report page on mobile and desktop.
- Run three independent mobile measurements per page and report median plus range, not one flattering score.
- Confirm product imagery, QR codes, COA images, checkout, and the access gate remain intact.

## Milestone 2 — Initial render-path cleanup

### Work

- Prevent side-cart celebration/confetti code from loading before it is needed.
- Reduce unused JavaScript and route-specific CSS on pages that do not use it.
- Investigate the slow Google Fonts stylesheet observed on the Testing page.
- Inventory Google Tag Manager tags and triggers. Remove or delay nothing until its measurement purpose is known.
- Preserve WooCommerce, rewards, VerifyPass, COA, payments, shipping, and analytics behavior.

### Why it helps

On a slower phone, the browser currently pauses to process files that are not necessary for the first screen. This milestone shortens that queue.

### Verification gate

- Stage first with rollback package.
- Confirm cart, checkout, analytics events, conversion measurement, rewards, VerifyPass, and COA navigation.
- Aim for a mobile median score of 80 or better where the laboratory allows it, but judge success primarily by faster LCP, less render blocking, and no functional regression.

## Milestone 3 — Access-gate accessibility without changing the rule

### Recommended work

- Keep the same fully blocking verification behavior.
- Give the gate a complete spoken description for assistive technology.
- Keep keyboard focus inside the gate while it is open.
- Make the page behind it inactive to keyboard and screen-reader users.
- Restore focus correctly after verification.
- Make Exit a real, keyboard-native link.
- Increase small mobile legal text to a readable size.
- Use the optimized gate image from Milestone 1.

### Why it helps

This does not loosen access. It makes the existing gate behave like a proper dialog for keyboard and screen-reader visitors, reduces confusion, and removes real accessibility defects. The optimized image can also improve first paint slightly.

### Verification gate

- Manual keyboard-only test on mobile-width and desktop-width layouts.
- Screen-reader semantics check.
- Confirm verification state, exit behavior, checkout, and page access remain unchanged.

## Optional decision — Change how much the gate blocks

This is not required to fix technical SEO and is not authorized by this plan.

Two separate choices must not be confused:

1. **Recommended now:** keep the full blocker and repair accessibility.
2. **Optional later:** show a page preview or compact verification banner before verification.

The second choice could improve the first impression and engagement, but it changes the current business/compliance experience. It requires Paulo's explicit approval after reviewing a mockup. Google already receives the page content in the HTML, so this is not necessary to make the pages crawlable.

## Milestone 4 — Validate, stop tuning, and return to organic growth

### Work

- Repeat the same five-page, three-run mobile matrix after Milestones 1–3.
- Compare bytes, render-blocking time, LCP, score median, and score range with the baseline.
- Run a focused crawl and confirm canonicals, schema, indexability, sitemaps, products, and COA pages did not regress.
- Watch Search Console indexing and query impressions.

### Stop condition

When mobile performance is stable and the remaining work is high-risk or low-return, stop chasing the score. Shift effort to the work that creates rankings: useful search-focused pages, internal links, current COA/archive evidence, citations, backlinks, and brand mentions.

## Priority recommendation

Approve Milestones 1–3 as one staging package. They are meaningful, testable, and do not require a gate-policy change. Review results before any live deployment. Treat the optional gate experience as a later business decision.

## What this plan does not promise

- A Lighthouse score of 100.
- Immediate rankings or traffic.
- That a faster page alone will create demand or authority.
- Any change to the access rule without explicit approval.


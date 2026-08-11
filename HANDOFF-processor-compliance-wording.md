# Handoff — Payment processor compliance wording

**Created:** 2026-08-11, before the compliance edit  
**Theme version at time of capture:** 0.20.0-beta.7 (confirm against `style.css` before reverting)  
**Branch:** `web-2c-homepage`  
**Repo:** `Beringela001/pep-select-website`

## Why this exists

Wording is being changed to satisfy payment processor underwriting requirements. Attorney has approved the changes for the purpose of getting approved. This document records the **exact wording live before the change**, so any of it can be restored later if the processor agrees post-approval.

Restoring is a business and legal decision, not a code decision. Item 1 changes a refund condition — see the warning under it.

---

## Change summary

| # | What | Current | New | Reversible? |
|---|---|---|---|---|
| 1 | Dilution notice | Names Pfizer pharmaceutical-grade solution as the refund condition | "a laboratory-grade reconstitution solution" | Yes, but see warning |
| 2 | Footer disclaimer | Pep Select wording | Processor's verbatim required wording | Yes |
| 3 | Product descriptions | "Supplied strictly for laboratory research." | Prefixed with literal "Research Use Only." | Yes |
| 4 | Military & First Responder | Linked in footer, indexable | Link hidden, page noindexed, page still live at URL | Yes |
| 5 | BAC upsell position | Inside collapsible cart panel | Directly above payment method | Yes |
| 6 | BAC upsell subline | "Compounds ship as lyophilized powder." | "Reconstitution Solution – for Laboratory Use." | Yes |

---

## 1. Dilution notice

> **Reverting this changes who qualifies for a refund.** The current wording restricts cloudiness refunds to reconstitution done with one named product. The replacement widens it to any solution a buyer describes as laboratory-grade. Do not revert without the attorney confirming which condition you want to be bound by.

### CURRENT (verified live on the product page, 2026-08-11)

```
Dilution notice

If a compound turns cloudy after it is reconstituted, the cause is almost always
the dilution solution rather than the compound itself, and non-pharmaceutical-grade
solutions are the usual culprit. For this reason, cloudiness cannot be accepted as
grounds for a refund unless the reconstitution was done using Pfizer
pharmaceutical-grade dilution solution.
```

### REPLACED WITH

```
Dilution notice

If a compound turns cloudy after it is reconstituted, the cause is almost always
the reconstitution solution rather than the compound itself, and
non-laboratory-grade solutions are the usual culprit. For this reason, cloudiness
cannot be accepted as grounds for a refund unless the reconstitution was done using
a laboratory-grade reconstitution solution.
```

### Known locations

- Per-product dilution notice under add-to-cart
- `inc/legal-content.php` — Refund & Shipping Policy (two separate places)
- `inc/legal-content.php` — Terms and Conditions, section 5

### Legal page variants — TO BE FILLED BY CLAUDE CODE BEFORE EDITING

The clause is worded differently in the legal pages than on the product card. Claude Code must paste the **verbatim current strings** from `inc/legal-content.php` here, with file and line numbers, before making any change. Without this the legal pages cannot be reverted accurately.

```
Captured from commit a0eab47 (immediately before the compliance edit). The current working
tree contains ZERO occurrences — all were replaced in 0.20.0-beta.9 (commit 9e64eca).
Total pre-change occurrences in the theme: 7 (3 legal, 1 product notice, 3 changelog).
legal-content.php and single-product.php line numbers are unchanged in the current tree;
the CHANGELOG line numbers below have since shifted down as newer entries were prepended.

Refund & Shipping Policy — occurrence 1 (under "Damaged or Incorrect Orders")
inc/legal-content.php:560
'html' => 'Dilution and cloudiness: Cloudiness that appears after a compound is reconstituted is almost always caused by the dilution solution rather than the compound. Cloudiness is not eligible for a refund, replacement, or credit unless the reconstitution was performed using Pfizer pharmaceutical-grade dilution solution.',

Refund & Shipping Policy — occurrence 2 (under "Not Eligible for Refund or Replacement")
inc/legal-content.php:591
'html' => 'Dilution and cloudiness: Cloudiness that appears after a compound is reconstituted is almost always caused by the dilution solution rather than the compound. Cloudiness is not eligible for a refund, replacement, or credit unless the reconstitution was performed using Pfizer pharmaceutical-grade dilution solution.',

Terms and Conditions, section 5 ("5. Shipping & Risk of Loss")
inc/legal-content.php:120
'html' => 'Dilution and cloudiness: Cloudiness that appears after a compound is reconstituted is almost always caused by the dilution solution rather than the compound. Cloudiness is not eligible for a refund, replacement, or credit unless the reconstitution was performed using Pfizer pharmaceutical-grade dilution solution.',

Other occurrence — per-product dilution notice (also shown as CURRENT in section 1 above)
inc/single-product.php:187
<p class="pepselect-dilution-notice__body"><?php esc_html_e( 'If a compound turns cloudy after it is reconstituted, the cause is almost always the dilution solution rather than the compound itself, and non-pharmaceutical-grade solutions are the usual culprit. For this reason, cloudiness cannot be accepted as grounds for a refund unless the reconstitution was done using Pfizer pharmaceutical-grade dilution solution.', 'pepselect-child' ); ?></p>

Other occurrences — historical changelog entries (line numbers as of a0eab47)
pepselect-child/CHANGELOG.md:46
- Rewrote the copy: heading "Need bacteriostatic water for your research?", a muted subline "Compounds ship as lyophilized powder." in the --pep-color-slate body token, and a shorter toggle-row label "Add 30mL - $19.99" with an en-dash separator. The price is read live from the product. The volume is parsed from the product title, since the product carries no volume attribute, rather than typed as a literal, and falls back to the full name. The toggle keeps a full-name accessible label ("Add Bacteriostatic Water 30mL to your order"). No claim is made about grade, purity, sterility, suitability, or refund eligibility, and nothing connects the product to the Pfizer dilution solution.
pepselect-child/CHANGELOG.md:143
- Added a dilution and cloudiness clause to the Refund & Shipping Policy (both under "Not Eligible for Refund or Replacement" and alongside the damaged or incorrect order remedies) and to Terms & Conditions section 5. Cloudiness after reconstitution is not eligible for refund, replacement, or credit unless Pfizer pharmaceutical-grade dilution solution was used. Existing refund language unchanged; no sections renumbered. last_updated bumped to August 1, 2026 on both documents. Attorney approved.
pepselect-child/CHANGELOG.md:147
- Added a dilution notice to every compound buy card, directly below add to cart (and below the notify form on out-of-stock pages). It states that post-reconstitution cloudiness is almost always caused by the dilution solution rather than the compound, and that cloudiness is not eligible for refund unless Pfizer pharmaceutical-grade dilution solution was used. Binding refund-policy language — flagged for M9 attorney review.
```

---

## 2. Footer disclaimer

### CURRENT (verified live, 2026-08-11)

```
For laboratory research use only. Pep Select compounds are independently tested,
with batch specific Certificates of Analysis available for review. Products are
not intended for human consumption.
```

### REPLACED WITH

```
All products sold on this website are intended for research and identification
purposes only. These products are not intended for human dosing, injection, or
ingestion.

Pep Select compounds are independently tested, with batch specific Certificates
of Analysis available for review.
```

The two required sentences must stay contiguous so an automated scanner reads them as one string. The Certificates of Analysis sentence is unchanged and was kept deliberately as a trust line.

The separate FDA Disclaimer paragraph lower in the footer was **not** modified.

---

## 3. Product description phrase

### CURRENT (verified live, 2026-08-11)

```
Supplied strictly for laboratory research.
```

### REPLACED WITH

```
Research Use Only. Supplied strictly for laboratory research.
```

The processor's review checks for the literal capitalised phrase "Research Use Only" or "Research Purposes Only". The original sentence means the same thing but does not contain the literal string.

**Note for reverting:** if this text turned out to live per-product in wp-admin rather than in the theme, the revert is a wp-admin edit per product, not a code change. Claude Code should record below which it was.

```
Theme-generated. The phrase is emitted by the shared partial
template-parts/product/description.php (line 65), inside the "Intended use" block, via
esc_html_e — it is NOT stored per-product in wp-admin. It renders identically on every
compound page, so no product records were edited and there are no affected product IDs.
(In 0.20.0-beta.9 the line in that one partial was prefixed to read
"Research Use Only. Supplied strictly for laboratory research. ...".) To revert, edit the
single partial, not any product.
```

---

## 4. Military & First Responder discount

### CURRENT

- Footer link text: `Military & First responder discount`
- Href: `https://pepselect.com/military-discount/`
- Page indexable

### AFTER

- Footer link hidden
- Page still live and reachable by direct URL, so existing customer links keep working
- Page set to noindex
- Page and template **not** deleted

### To revert

Unhide the footer link, remove the noindex. Nothing was destroyed.

```
Only one link to /military-discount/ existed in the theme:
- inc/footer-preview.php — the footer navigation "Support" group entry
  array( 'label' => __( 'Military & First responder discount', 'pepselect-child' ), 'url' => pepselect_child_get_page_url( 'military-discount' ) ),
  It was line 169 at commit a0eab47. Removed entirely in 0.20.0-beta.9, so the theme now
  contains zero links to the page. To revert: re-add that array entry.
- pepselect_child_get_military_url() is defined in inc/military-page.php but is unused
  (never called), so it renders no link.

noindex applied in inc/military-page.php, every filter gated to this one page via
pepselect_child_is_military_request() ( = is_page( 'military-discount' ) ):
- add_filter( 'wpseo_robots_array', 'pepselect_child_military_robots_yoast_array' ) — sets index=noindex, follow=nofollow (Yoast is the active SEO layer)
- add_filter( 'wpseo_robots', 'pepselect_child_military_robots_yoast_string' ) — forces "noindex, nofollow" (legacy Yoast string)
- add_filter( 'wp_robots', 'pepselect_child_military_robots_core' ) — core fallback if no SEO plugin outputs robots
No other page is affected. To revert: remove those three filters.
```

---

## 5 and 6. BAC water upsell

### CURRENT position

Rendered inside the collapsible "Your cart — N items" panel on the checkout page, so on mobile it was hidden until the customer expanded the cart.

### NEW position

Directly **above** the payment method section.

Reason to keep it there even if other wording reverts: the Square instruction block tells the customer to enter the order total exactly and warns that mismatched payments delay the order. The upsell must come before that instruction so the total is final when they read the amount. Placing it after the payment method risks a customer paying the pre-upsell amount.

### Subline copy

**Current:**
```
Compounds ship as lyophilized powder.
```

**Replaced with:**
```
Reconstitution Solution – for Laboratory Use.
```

The replacement is the processor's required framing for reconstitution solution, so it must not be paraphrased while underwriting is in progress. Note the en dash.

Heading unchanged in both versions:
```
Need bacteriostatic water for your research?
```

---

## Related item, not changed

The processor rule that drives item 1 also requires reconstitution solution to be framed as "Reconstitution Solution – for Laboratory Use". The product is currently titled **Bacteriostatic Water 30mL**. This was deliberately left alone. If the processor flags it during review, that is the likely reason.

---

## Current checkout consent controls — captured before M12-1

Verbatim from `inc/checkout-fields.php` on `web-2c-homepage`. M12-1 replaces the two consent
checkboxes below with the two Acknowledgments; the Research Purpose dropdown is kept above them.
Recorded here so the current controls can be restored if needed. All three render on
`woocommerce_review_order_before_submit` — Research Purpose at priority 10 (above), the two
consents at priority 20.

### Research Purpose dropdown
Field name `research_purpose` · order meta `_research_purpose` · required · stored value is the raw label.

- Label: `Research Purpose`
- Options:
```
""                                     => Select research purpose...   (placeholder)
Academic Research
Pharmaceutical Research
Biotech R&D
Cellular / Molecular Biology
Peptide Characterization
Quality Control / Analytical Testing
Other Research Purpose
```

### Checkbox 1
Field name `privacy_agreement` · order meta `_privacy_agreement` (stored Yes/No) · required. Label verbatim:
```
I have read and agree to the <a href="[privacy-policy URL]" target="_blank">Privacy Policy</a>.
```

### Checkbox 2
Field name `terms_agreement_custom` · order meta `_terms_agreement_custom` (stored Yes/No) · required. Label verbatim:
```
I have read and agree to the <a href="[terms-conditions URL]" target="_blank">Terms & Conditions</a>.
```

The `href` values are built at runtime from the legal-URL helper and escaped; both links open in a new tab.

### Validation messages (verbatim)
```
Please select a research purpose to continue.
Please agree to the Privacy Policy to continue.
Please agree to the Terms & Conditions to continue.
```

---

## New checkout consent controls — M12-1 (replaces the two consent checkboxes above)

Verbatim from `inc/checkout-fields.php` after M12-1. The Research Purpose dropdown recorded
above is UNCHANGED. The two legacy consent checkboxes (`privacy_agreement` /
`terms_agreement_custom`) are replaced by the two Acknowledgments below; privacy consent is
folded into checkbox 2, so it is still explicitly ticked. Same hook and priorities as before:
`woocommerce_review_order_before_submit`, Research Purpose at 10, Acknowledgments at 20.

Section heading: **Acknowledgments**

### Checkbox 1 — compliance statement
Field name `compliance_acknowledgment` · order meta `_pepselect_ack_compliance` (Yes/No) · required. Label verbatim:
```
Research-only use restriction; prohibition on human or animal consumption; acknowledgment that products are not for diagnosis/treatment/prevention of any disease; indemnification of the seller; acknowledgment that the buyer is a qualified professional.
```

### Checkbox 2 — combined policy agreement
Field name `policy_agreement` · order meta `_pepselect_ack_policy` (Yes/No) · required. Label verbatim (three links, target `_blank`, `rel="noopener"`):
```
I have read and agree to the Terms & Conditions, Privacy Policy, and Return & Refund Policy.
```
- Terms & Conditions      -> https://pepselect.com/terms-conditions/
- Privacy Policy          -> https://pepselect.com/privacy-policy/
- Return & Refund Policy   -> https://pepselect.com/refund-shipping-policy/

### Acceptance evidence stored on the order (WC CRUD meta, HPOS-safe)
- `_pepselect_ack_compliance`  = Yes/No
- `_pepselect_ack_policy`       = Yes/No
- `_pepselect_ack_timestamp`    = ISO 8601 acceptance time (`current_time( 'c' )`)
- `_pepselect_ack_version`      = `11b0a95ea858`

The wording version is the first 12 hex of `sha1( checkbox1_label . '|' . checkbox2_label )` — a
HASH of the two canonical label strings, not a manual number, so it changes automatically if the
wording changes and old orders keep their own hash. If either label above is edited, recompute
and record the new hash here. Current mapping:
```
11b0a95ea858  ->  the two labels exactly as printed in this section
```

### Validation
Both mandatory. Server-side on `woocommerce_checkout_process` (authoritative, non-bypassable) and
client-side (`assets/js/checkout-acknowledgments.js`: inline error next to each unticked box, focus
to the first). Server-side error strings:
```
You must acknowledge the compliance statement to place your order.
You must agree to the Terms & Conditions, Privacy Policy, and Return & Refund Policy to place your order.
```

### Admin order screen
The block under the billing address shows the new acknowledgments (with timestamp and version) for
M12-1 orders, and falls back to the legacy `_privacy_agreement` / `_terms_agreement_custom` meta for
orders placed before M12-1. Both are readable; nothing was migrated or deleted.

Shipped in: 0.20.0-beta.10.

---

## How to revert

1. Confirm with the attorney which items are safe to revert, especially item 1.
2. Check the real `style.css` version before building anything.
3. Restore the CURRENT strings above verbatim, including the legal page variants once Claude Code has filled them in.
4. Rebuild and ship as a normal beta with a rollback ZIP alongside.

The rollback ZIP built with the compliance release restores everything at once but also reverts the upsell relocation and any unrelated work in that build. For a partial revert, restore the strings individually instead.

```
Compliance release: 0.20.0-beta.9
Commit:             9e64eca  (on web-2c-homepage)
Release ZIP:  pepselect-child-0.20.0-beta.9.zip  SHA-256 bef451ead64d38c2fa4dc7dcafadb56b79435b6f8f52dc60897f8f9564af5178
Rollback ZIP: pepselect-child-0.20.0-beta.8.zip  SHA-256 e8ab3bbf968e46901dfdb6688efe316aa97b713e18c3f9cd2256deec47f410ae
```

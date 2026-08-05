# Email validation report — `pepselect-email-peptide-is-a-word.html`

**Date:** 2026-08-01
**Repo:** `Beringela001/pep-select-website` @ `238d65c` (shallow clone)
**Sources of truth consulted:** `pepselect-child/assets/css/foundations.css`,
`pepselect-child/inc/emails.php`, `pepselect-child/inc/compound-content.php`,
`pepselect-child/woocommerce/emails/*.php`, `.agents/product-marketing.md`,
`PepSelect_Brand_Extract.md`, `BACKLOG.md`, `docs/m10-audit.md`, `HANDOFF.md`

---

## Status: deliverable 1 not produced

**`pepselect-email-peptide-is-a-word.FINAL.html` does not exist, because the input file does not.**

The task says to drop `pepselect-email-peptide-is-a-word.html` in the repo root before starting. It
was not dropped. I searched the repo working tree, all git history on the shallow clone, and the
whole filesystem — the file is not present under that name or any near variant. The only HTML in the
repo is `graphify-out/graph.html`.

I did not reconstruct the email. Producing a "corrected" file from a reconstruction would hand you
markup that looks authoritative but was never the thing you wrote, and every §5 finding below would
be a guess about copy I had never read.

**Everything that does not require the file's bytes is resolved below.** Once you add the file, the
remaining work is mechanical: apply §1, §3, §4 as find-and-replace, then run the §5 sweep against
real copy.

---

## Summary of what resolved

| § | Item | Status |
|---|---|---|
| 1 | Permalink base | **Resolved** — `/product/`, not `compounds` |
| 1 | GLP-3 R URL | **Resolved** — real slug found, guess was wrong |
| 1 | GHK-Cu URL | **Blocked** — slug not in repo, WP-CLI command below |
| 2 | COA deep-link parameter | **Blocked** — plugin source not in repo, site unreachable |
| 3 | Image hosting | **Partly resolved** — correct host identified; files absent, cannot host |
| 4 | 15 hard-coded hexes | **Resolved** — all 15 match; one conflicts with email convention |
| 4 | `#8FC7E4` / `#B8CEDF` | **Resolved** — both → `#D7E1E9` |
| 4 | Font stacks | **Resolved** — email stacks deliberately differ from `foundations.css` |
| 5 | Two register rulings | **Resolved** — both approved, one with a condition |
| 5 | Full copy sweep | **Blocked** — needs the file |
| 5 | Scarcity/price copy | **Flagged** — new finding, see §5.3 |
| 6 | Transactional conventions | **Resolved** |
| 7 | BACKLOG bug | **Resolved** — still open |
| 7 | Stock and prices | **Blocked** — not in repo |

---

## §1 — Product URLs

### Permalink base: `/product/` is correct

The products are **WooCommerce products, not a `compounds` custom post type**. Evidence:

- `grep -rn "register_post_type"` across the repo returns **nothing**. The child theme registers no
  custom post type. The only custom post type referenced anywhere is `ps_coa_test`, owned by the COA
  Archive plugin (`PepSelect_Brand_Extract.md:668`).
- `docs/m10-audit.md:29,59` lists three live `/product/*` routes rendered by theme
  `inc/single-product.php` on the `is_product()` conditional — a WooCommerce conditional.
- The audit's logged-out link sweep (`docs/m10-audit.md:59`) records all three at **HTTP 200**.
- `/shop/` is rendered by `inc/archive-compounds.php` on `is_shop()`. The filename says "compounds";
  the conditional is WooCommerce's. The word is branding, not a post type.

So the base in the email is right. Only the slugs are wrong.

### GLP-3 R 30mg — resolved

| | |
|---|---|
| **Old (guess)** | `https://www.pepselect.com/product/glp-3-r-30mg/` |
| **New** | `https://www.pepselect.com/product/glp3-r30/` |
| **Source** | `docs/m10-audit.md:29` (route table) and `:59` (link sweep, HTTP 200) |

### GHK-Cu 50mg — unresolved, blocking

The slug is not in this repo. `PepSelect_Brand_Extract.md:1949` states it directly:

> Neither "GHK-Cu 50mg" nor "GLP-3 R 30mg" appears anywhere in the repository as a product record.
> The strengths you named come from the `product_tag` taxonomy in the database.

The repo carries the presentation layer only. `inc/compound-content.php` is keyed on the compound
name with strength stripped (`'ghk-cu'`), so one entry serves every GHK-Cu strength and no
per-strength slug exists here.

**Do not pattern-guess it.** The three known real slugs are `glp3-r30`, `pt-141`, `tb500-10` — a
condensed form with no `mg` suffix and inconsistent hyphenation. `ghk-cu-50mg` matches none of them,
and `ghk-cu50` / `ghkcu-50` / `ghk-cu-50` are all equally plausible. A wrong slug is a 404 in a
broadcast send.

**Run on the server.** The command in the brief needs a slug you don't have yet, so list first:

```bash
# list every product slug and permalink at once — pick GHK-Cu 50mg from the output
wp wc product list --user=1 --fields=id,name,slug,permalink --format=csv

# or, without the WooCommerce CLI package:
wp post list --post_type=product --post_status=publish \
  --fields=ID,post_name,post_title --format=csv

# then confirm the exact URL:
wp post list --post_type=product --field=url --name=<slug>
```

While you are there, confirm `glp3-r30` is still the live slug — the m10 audit is a point-in-time
snapshot and a product rename would have silently changed it.

### Replacement checklist

The brief says six occurrences: per product, an MSO `<v:roundrect href>`, a fallback `<a href>`, and
a hero image wrapper link. I could not verify the count against the file. When you apply it:

```bash
grep -c "glp-3-r-30mg" pepselect-email-peptide-is-a-word.html
grep -c "ghk-cu-50mg"  pepselect-email-peptide-is-a-word.html
```

Both counts must reach **0** after the edit. Note the hero is described as GLP-3 **RT** while the
product is GLP-3 **R** — confirm the hero link points at the GLP-3 R product and not a third SKU.

---

## §2 — COA deep-link format

**Unresolved. Leave `/testing/` as it is.**

The COA Archive plugin is **not in this repository.** The repo contains the child theme only; the
plugin is a separate, independently versioned system:

- `HANDOFF.md:155` — "Pep Select COA Archive plugin, stable source version `0.4.0`, `/testing/`
  routes … Preserve exactly."
- `docs/m10-audit.md:17,36` — `/testing/` is "**plugin-owned**", rendering `ps-coa-archive-hero`,
  `ps-coa-compound-card`, `ps-coa-search`. Not theme, not Elementor.

What the repo does expose is the data model, not the URL contract: post type `ps_coa_test`, with the
test-to-compound link held in post meta `compound_id` (`PepSelect_Brand_Extract.md:668-670`). No
`query_var` registration, no rewrite rule, and no example of a lot-code URL appears anywhere.

There is also a signal that the plugin's public interface is deliberately narrow. `HANDOFF.md:268`
notes the homepage "omits a fabricated 'latest record' card because plugin `0.4.0` exposes no
supported generic homepage projection." A plugin that exposes no homepage projection may well expose
no lot-code query parameter either.

I could not test `PSGKCU5071926GX` or `ND_R30_060326` live: **`www.pepselect.com` is blocked by this
environment's egress policy** (`CONNECT tunnel failed, response 403`). This is not new — the brand
extract hit the identical wall and documented it at `PepSelect_Brand_Extract.md:1900`.

**To resolve**, on a machine that can reach the site or read the plugin:

```bash
# 1. Does the plugin register a query var?
grep -rn "query_var\|add_rewrite_rule\|add_rewrite_tag" wp-content/plugins/*coa*/

# 2. Empirically, with a known-good lot code:
curl -sI 'https://www.pepselect.com/testing/?lot=ND_R30_060326'
curl -sI 'https://www.pepselect.com/testing/?batch=ND_R30_060326'
curl -sI 'https://www.pepselect.com/testing/ND_R30_060326/'
```

A 200 alone is not proof — `/testing/` will likely return 200 and ignore an unknown parameter. Diff
the response body against the bare `/testing/` to confirm it actually filtered.

**Until that returns a working link, `/testing/` stays.** A deep link that silently degrades to the
unfiltered archive is worse than no deep link: it promises a one-click certificate and delivers a
search page, which is exactly the "documentation scavenger hunt" the brand positions against.

---

## §3 — Images

**Blocked on the source files. The correct host is resolved.**

### Neither source image is in the repo

There are **zero image binaries in the entire repository** — no `.png`, `.jpg`, `.webp`, or `.svg`.
`Logo_Pepselect_Whitebackground.png` and the GLP-3 RT vial render are both absent. I cannot export
at 496px/1196px, cannot compress to under 200KB, and cannot return URLs for files I do not have.

### Where images are actually served from

| Environment | Host | Source |
|---|---|---|
| Production | `www.pepselect.com` | `docs/WEB-0-environments.md` |
| Kinsta live | `pepselect.kinsta.cloud` | `docs/WEB-0-environments.md:8` |
| Staging | `stg-pepselect-staging.kinsta.cloud` | `docs/WEB-0-environments.md:16` |

Uploads follow the WordPress default, `/wp-content/uploads/YYYY/MM/`, confirmed by the live
fallback URL recorded at `docs/WEB-1-elementor-audit.md:70`.

**Use `https://www.pepselect.com/wp-content/uploads/…` — not the Kinsta hostname.** Hard-coded
`pepselect.kinsta.cloud` asset URLs are a defect this project has already cleaned up once:
`docs/WEB-1-elementor-audit.md:35,242` catalogues 12 of them in the legacy Elementor exports as
"URL evidence" of environment coupling, and `docs/m10-audit.md:105` records the cleanup as **PASS** —
`kinsta.cloud` now appears nowhere in the theme repo or in any of the 18 fetched live page bodies.
Putting it back in a broadcast email would reintroduce that coupling on the one surface where the URL
is permanent and uneditable after send.

**No CDN.** No CDN configuration, rewrite rule, or third-party asset host appears anywhere in the
repo. Absent one, uploads are served from the primary domain.

### What I could not confirm

**Whether the host allows hotlinking from email clients.** This requires an actual request against
the live host with and without a `Referer`, and the egress policy blocks it. Check before send:

```bash
curl -sI 'https://www.pepselect.com/wp-content/uploads/2026/06/<file>.png'
curl -sI -H 'Referer: https://mail.google.com/' \
     'https://www.pepselect.com/wp-content/uploads/2026/06/<file>.png'
```

Both must return `200` with an `image/*` content type. A `403` on the second, or any Kinsta
edge/hotlink rule, breaks every image in the send. Gmail proxies images through
`googleusercontent.com` and sends **no** referer, so a referer-blocking rule that looks fine in a
browser can still blank the email.

### Also worth checking

`docs/WEB-1-staging-findings.md:82` records that staging mail from `email@pepselect.kinsta.cloud`
lands in Gmail **Spam**. Confirm FluentCRM sends from the production domain with SPF/DKIM/DMARC
aligned, or the image question is moot.

---

## §4 — Design tokens

### All 15 hexes verified against `foundations.css`

| Hex | Token | Line | Match |
|---|---|---|---|
| `#002A53` | `--pep-color-navy` | 10 | ✅ |
| `#001D3A` | `--pep-color-dark-navy` | 11 | ✅ |
| `#17A1CF` | `--pep-color-cyan` | 12 | ✅ |
| `#16834A` | `--pep-color-green` | 13 | ✅ |
| `#B46A00` | `--pep-color-amber` | 14 | ✅ |
| `#13283D` | `--pep-color-ink` | 16 | ✅ |
| `#5E6F80` | `--pep-color-slate` | 17 | ✅ |
| `#7A8793` | `--pep-color-neutral` | 18 | ✅ |
| `#D7E1E9` | `--pep-color-border` | 19 | ✅ |
| `#F3F8FC` | `--pep-color-surface` | 20 | ✅ |
| `#F5F6F7` | `--pep-color-soft-gray` | 21 | ✅ |
| `#E8F6FB` | `--pep-color-cyan-soft` | 23 | ✅ |
| `#EAF5EF` | `--pep-color-green-soft` | 24 | ✅ |
| `#FFF4DF` | `--pep-color-amber-soft` | 25 | ⚠️ see below |
| `#FFFFFF` | `--pep-color-white` | 22 | ✅ |

All fifteen are real tokens. One has a conflict, and it is not with `foundations.css`.

### ⚠️ `#FFF4DF` conflicts with the established *email* token map

`pepselect-child/inc/emails.php:22-39` defines `pepselect_child_email_tokens()`, the canonical token
map both transactional templates consume. It is **not** a straight copy of `foundations.css`:

```php
'amber'      => '#B46A00',   // same as foundations
'amber_soft' => '#FDF6EA',   // foundations --pep-color-amber-soft is #FFF4DF
'amber_ink'  => '#5C3A00',   // no foundations equivalent — email-only
```

For the same semantic role — soft amber fill behind an amber callout — email uses `#FDF6EA` and the
web token file uses `#FFF4DF`. `customer-on-hold-order.php:77-79` ships `#FDF6EA` today.

**Recommendation:** `#FFF4DF` → **`#FDF6EA`**, and if the email puts text on that fill, use
**`#5C3A00`** (`amber_ink`) rather than navy or ink. Rationale: on an email surface the email token
map is the more specific source of truth, and matching it means a marketing email and a transactional
email opened side by side show the same amber. Flagging rather than silently applying — this one is a
judgment call about which source wins, and it is yours.

### Naming collision worth knowing about

`emails.php` calls `#001D3A` **`ink`**. In `foundations.css`, `#001D3A` is `--pep-color-dark-navy`,
and `--pep-color-ink` is `#13283D`. Same word, two different colors.

Both transactional templates set **body text to `#001D3A`** (`$pep['ink']`). If the marketing email
sets body text to `#13283D` because that is what `foundations.css` calls "ink", it will not match the
transactional emails even though both are "correct". Decide deliberately; do not let the name pick.

### Orphan colors — both resolve to `#D7E1E9`

Neither `#8FC7E4` nor `#B8CEDF` is a token in `foundations.css` or `emails.php`.

Computed WCAG 2.1 contrast, and RGB euclidean distance to every plausible light token:

| Candidate | On `#002A53` | On `#001D3A` | Distance to `#8FC7E4` | Distance to `#B8CEDF` |
|---|---|---|---|---|
| **`#D7E1E9` (border)** | **10.88:1** | **12.80:1** | **76.7 (nearest)** | **37.7 (nearest)** |
| `#EAF5EF` (green-soft) | 12.92:1 | 15.20:1 | 102.6 | 65.4 |
| `#E8F6FB` (cyan-soft) | 13.05:1 | 15.37:1 | 103.2 | 68.5 |
| `#F3F8FC` (surface) | 13.49:1 | 15.88:1 | 113.9 | 78.0 |
| `#17A1CF` (cyan) | 4.83:1 | 5.69:1 | 127.6 | 167.9 |
| `#7A8793` (neutral) | 3.92:1 ❌ | 4.62:1 | 105.3 | 121.1 |
| `#5E6F80` (slate) | 2.79:1 ❌ | 3.28:1 ❌ | 141.9 | 161.7 |

**`--pep-color-border` `#D7E1E9` is simultaneously the nearest token to both orphans and comfortably
above 4.5:1 on both navy backgrounds.** It wins on both criteria with no trade-off.

| Old | New | Token | Contrast on `#002A53` |
|---|---|---|---|
| `#8FC7E4` | `#D7E1E9` | `--pep-color-border` | 10.88:1 |
| `#B8CEDF` | `#D7E1E9` | `--pep-color-border` | 10.88:1 |

**One correction to the brief's premise.** Both orphans *already* pass 4.5:1 — `#8FC7E4` is 7.87:1
and `#B8CEDF` is 8.88:1 on navy. This is a **token-governance fix, not an accessibility fix**. The
replacement happens to improve contrast, but nothing was failing. Worth knowing so the change is not
described to anyone as a WCAG remediation.

Do note that `#D7E1E9` is semantically a *border* token being used as text. The real gap is that
neither token file defines an on-dark muted text color — there are no inverse tokens at all. If
de-emphasised-text-on-navy is now a recurring pattern, the durable fix is a new token
(`--pep-color-on-navy-muted`) rather than borrowing the border color in every future artifact.

### Font stacks — use `emails.php`, not `foundations.css`

`foundations.css` defines three roles (lines 29-31):

```css
--pep-font-editorial: Georgia, "Times New Roman", Times, serif;
--pep-font-interface: "Plus Jakarta Sans", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
--pep-font-technical: "IBM Plex Mono", "SFMono-Regular", Consolas, "Liberation Mono", monospace;
```

`emails.php` deliberately diverges for email (lines 35-36):

```php
'font'      => "'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif",
'font_mono' => "'IBM Plex Mono', 'SFMono-Regular', Consolas, 'Liberation Mono', 'Courier New', Courier, monospace",
```

Two changes, both correct for email:

1. **`system-ui` is dropped.** It is unreliable in Outlook and several webmail clients, where an
   unrecognised first-position keyword can cause the whole declaration to be discarded.
2. **Real fallbacks are appended** — `Roboto, Helvetica, Arial` and `'Courier New', Courier` — so the
   stack lands on a font that exists everywhere instead of bare `sans-serif`/`monospace`.

**Verdict:** the brief asks for the stacks "verbatim" against `foundations.css`. Do not do that. On an
email surface, match `emails.php` verbatim. If the file currently carries the `foundations.css`
stacks, replace both.

There is **no editorial/Georgia role in the email token map.** If the marketing email uses a serif for
display headings, that is a new pattern with no precedent in either transactional template — worth a
deliberate decision rather than an inherited one.

Neither "Plus Jakarta Sans" nor "IBM Plex Mono" will render in most clients regardless: webfont
`<link>` tags are stripped by Outlook, Gmail, and Yahoo. Design for the fallback — Arial and
Courier New are what most recipients will actually see.

---

## §5 — Compliance

Checked against `.agents/product-marketing.md` v1 (2026-07-16), Compliance Guardrails (129-164) and
Customer Language (71-107).

### 5.1 The GHK-Cu spelling block — ✅ approved, no change

Naming glycine, histidine, lysine and copper is **composition, not effect**. It states what the
molecule *is*. Nothing in the Compliance Guardrails restricts stating composition; "transparent
product information" and "clear product information" are named Approved Message Territories
(line 168).

The register is already in production on product pages. `pepselect-child/inc/compound-content.php:73`
ships this approved description:

> GHK-Cu is a copper-binding tripeptide (glycyl-L-histidyl-L-lysine) studied for its role in tissue
> remodeling. It is researched for stimulating structural-protein synthesis and modulating genes tied
> to skin and matrix regeneration.

And `pepselect-child/CHANGELOG.md:159` confirms this was a deliberate, reviewed editorial position:

> …while holding the **mechanism-only, no-human-use register**.

The email's block is **strictly more conservative** than what already ships: naming three amino acids
and a metal is pure composition, with none of the "studied for its role in tissue remodeling"
mechanism framing that the product page carries. If the product-page version is approved, the email
version is approved a fortiori.

**Ruling: approved for email. No edit.** One condition — this holds only while the block stays
compositional. If it acquires a "which is why…" clause connecting composition to an outcome, it
becomes an effects claim and the ruling does not carry.

### 5.2 "Identity confirmed" and "97.62% against a 95.0% minimum" — ✅ register approved, ⚠️ figures need verification

**The register is approved.** "Batch documentation", "third-party laboratory reports", and
"testing-history access" are all Approved Message Territories (lines 76-78, 168). The phrasing is
also precisely the *shape* the guardrails require of a testing claim (line 113):

> Testing claims must be batch-specific where appropriate, match the actual COA status, remain
> limited to the documented test and result, and change when the underlying record changes.

A specific result stated against a stated specification is the compliant form. It is measurably not
the banned form: the avoid-list (148-162) bans **"Guaranteed purity"** and **"Highest purity"**, and
"97.62% against a 95.0% minimum" claims neither — it reports one number and the threshold it was
measured against, with no superlative and no guarantee. Reporting a result against a spec is the
opposite of the vague, evidence-free quality claim line 107 prohibits.

**⚠️ The figures themselves are unverified, and that is a real gate.** Line 117-118: "Do not invent
purity percentages or laboratory results." I could not check `97.62%`, `95.0%`, or "identity
confirmed" against any record — no COA data exists in this repo, and the archive is unreachable.

This is a formally open question in the guardrail document itself (line 219):

> Which current batches have third-party laboratory reports, and which exact tests and results may be
> cited? `[VERIFY CLAIM]`

**Ruling: register approved; do not send until both numbers, the "identity confirmed" statement, and
the two lot codes are confirmed against the actual COA records for the exact batches shipping.** If
the batch turns over between approval and send, the numbers change — line 113 requires the claim to
change with the record. Hard-coding a purity figure in a broadcast email means it is frozen at send
time; make sure it is frozen at a *true* value.

### 5.3 ⚠️ New flag — "Only 4 left" is a scarcity claim

Not raised in the brief. Raising it because it is the clearest guardrail conflict I found.

`PepSelect_Brand_Extract.md:2389`, under "Dynamic and compliance rules":

> **Never type product names, IDs, images, prices, stock values, or scarcity into homepage copy.**

And `.agents/product-marketing.md:207`:

> CRO may improve hierarchy and clarity but **may not introduce unsupported urgency, scarcity**,
> health claims, or personal-use implications.

"Only 4 left" is a hard-typed stock value *and* a scarcity device. The `$179.99` and `$33.99` figures
are hard-typed prices. The rule as written scopes to homepage copy, so this is not a literal
violation of a rule about emails — but the reasoning behind it applies with more force to email, not
less. A homepage re-renders on every request; a sent email is frozen forever. If the count is wrong
at open time, the email is making a false scarcity claim to every recipient, permanently, with no
way to correct it.

The brand's whole position is "documented over exaggerated" and "transparent over mysterious"
(lines 26-27). An uncheckable countdown is the one element in this email that a skeptical researcher
cannot verify — which is precisely the register the brand defines itself against.

**Per your instruction I have not edited it.** My recommendation: drop "Only 4 left" entirely and
replace hard-typed prices with a "current pricing on the product page" construction, or accept it as
a deliberate, approved exception with Paulo's sign-off recorded. Note also that approving it makes the
§7 stock check blocking rather than advisory.

### 5.4 One correction to the brief's premise on em dashes

The brief asks me to confirm "no em dashes". The guardrail does not say that. Line 105 reads:

> **Excessive** rhetorical questions or em dashes

Excessive, not absolute. And the approved WEB-2C homepage hero copy uses one
(`PepSelect_Brand_Extract.md:2320`): "close at hand—so the information is easier to find". So em
dashes are in-policy in approved production copy. Judge the email on density, not presence. The same
"excessive" qualifier applies to rhetorical questions; the approved FAQ heading "Questions before you
order?" (line 2372) is itself interrogative.

Three-item slogan structures are the stricter rule — line 106 bans "**Repetitive** three-item slogan
structures" with no excess qualifier, though note the approved homepage micro-proof is itself a
three-item list (line 2323), so the target is repetition of the *structure*, not any list of three.

### 5.5 What I could not check

The full sweep — human/animal use, effects, dosing, reconstitution, the 13-term banned list
(148-162), the 14-phrase avoid list (89-107), em-dash and rhetorical-question density, three-item
structures, and CTA language against the preferred/avoid lists (172-191) — **requires the file.**

For when you have it, the CTA list is worth a targeted look. Preferred: "Explore Compounds", "Review
COAs", "View Testing History", "Review Batch Documentation", "Browse Research Compounds", "View
Compound Details", "Contact Support". A marketing email's buttons are the most likely place for
off-list CTA language to appear.

---

## §6 — Conventions from the transactional templates

Read: `customer-completed-order.php`, `customer-on-hold-order.php`, and their shared token source
`inc/emails.php`.

### The single most important finding

**Both templates consume `pepselect_child_email_tokens()`. Neither hard-codes a hex outside the
inline fallback array.** The marketing email hard-codes everything, which is unavoidable in
FluentCRM — but it means `inc/emails.php:22-39`, not `foundations.css`, is the list to reconcile
against. See §4.

### Conventions to match

| Element | Established convention | Source |
|---|---|---|
| **Body text** | `15px` / `line-height:1.7` / color `#001D3A` (`ink`) | `completed:36` |
| **Buttons** | `<table>` → `<td bgcolor>` → `border-radius:999px` → inline-block `<a>`, padding `13px 30px`, `15px`/`600`, white text | `completed:99-109` |
| **Cards** | `1px solid` accent + `border-radius:12px` + soft-tint background + padding `26px 30px` | `completed:74-76` |
| **Callouts** | `border-left:4px solid` accent + `border-radius:8px` (`radius`) | `on-hold:77` |
| **Field labels** | `12px` / `600` / uppercase / `letter-spacing:0.06em` / color `#5E6F80` (`slate`) | `completed:83,91` |
| **Data values** | mono stack / `20px` / `600` / navy / `word-break:break-all` | `completed:94` |
| **Links** | cyan `#17A1CF` | `completed:112,147` |
| **Support address** | `support@pepselect.com` | `completed:147` |
| **Radii** | only two: `999px` (pill) and `8px`; cards use `12px` inline | `emails.php:37-38` |

### ⚠️ Buttons: no VML anywhere in the established templates

The brief describes MSO `<v:roundrect>` blocks in the marketing email. **Neither transactional
template uses VML.** Both use `<td bgcolor>` + `border-radius` + inline-block `<a>`.

This is a genuine trade-off, not a defect on either side:

- The established approach degrades to a **square** cyan button in Outlook (which ignores
  `border-radius`) but stays a working, correctly-colored button.
- `v:roundrect` renders a real rounded button in Outlook, but `arcsize` cannot reproduce a 999px pill
  on a 52px-tall button — Outlook will show a stadium only if `arcsize="50%"`, and most
  implementations hard-code a squared `arcsize="0%"`.

**Recommendation: keep the VML if it is already there and set `arcsize="50%"`** so Outlook
approximates the pill rather than contradicting it. Do not silently switch the marketing email to
square buttons when every transactional email renders pills in every modern client.

### Wrapper, logo, and footer are NOT in these templates

Both files delegate to WooCommerce core:

```php
do_action( 'woocommerce_email_header', $email_heading, $email );   // completed:49
do_action( 'woocommerce_email_footer', $email );                    // completed:186
```

There is **no `email-header.php` or `email-footer.php` override in the child theme** — I searched;
none exists. So wrapper width, logo treatment, footer construction, and disclaimer placement are all
controlled by **WooCommerce email settings in the database**, not by anything in this repo.

**I could not resolve them, and they are exactly what the brief asks for in §6.** WooCommerce core
defaults to a 600px content table inside a 100%-width wrapper, and the marketing email should match
whatever the real setting is. Get the actual values:

```bash
wp option get woocommerce_email_header_image
wp option get woocommerce_email_base_color
wp option get woocommerce_email_background_color
wp option get woocommerce_email_body_background_color
wp option get woocommerce_email_text_color
wp option get woocommerce_email_footer_text
```

`woocommerce_email_footer_text` matters most for the brief's disclaimer question — it is where any
RUO disclaimer on transactional mail lives, and the marketing email's footer should not contradict
it. `woocommerce_email_header_image` gives you the logo URL already in use, which may resolve part of
§3 for free.

---

## §7 — Two facts before send

### 7.1 BACKLOG bug — ⚠️ STILL OPEN

`BACKLOG.md:10-20`, current at `238d65c`:

> ### COA Archive plugin — failed batches do not render on the public /testing/ archive
> **Status:** open · **Priority:** high (transparency commitment)
> Failed-status batches are marked Published but never appear on the public archive page.

Example given: NAD+ batch `PSNAD562926JP`, tested by ILS Labs, status **Failed**, marked
**Published** — absent from the public page.

**Implication for the email, which links `/testing/` twice:** a recipient who follows either link
sees an archive showing only passes. Your read is right and worth stating plainly — the copy must not
begin to claim the archive shows failures until the render path is fixed. Specifically, avoid any
formulation like "including the ones that didn't pass", "every result, pass or fail", or "we publish
our failures". Each would be false today.

The narrower risk: the archive currently *looks* like a 100%-pass record. Any email copy that gestures
at completeness ("the full testing history", "every batch we've tested") inherits that implication
without stating it. Worth a specific look at the two link contexts once the file exists.

### 7.2 Stock and prices — unresolved, not in the repo

I cannot confirm `Only 4 left`, `In stock`, `$179.99`, or `$33.99`. `PepSelect_Brand_Extract.md:1893`
is explicit:

> **No per-SKU product data could be extracted.** … This repository contains the **presentation layer
> only**. Product records, prices, stock levels, and all COA/batch data live in the WordPress database
> and the COA Archive plugin, neither of which is here.

The brand extract documents the same three closed routes I hit (`:1897-1901`): no database, no
`wp-config.php`, no `.sql` dump, and both the WooCommerce REST API and the public Store API blocked by
the identical `CONNECT tunnel failed, response 403` egress denial.

**Run one of these:**

```bash
# public, no auth
curl -s 'https://www.pepselect.com/wp-json/wc/store/v1/products?per_page=100' > products.json

# authenticated, includes stock_quantity
curl -s -u ck_xxx:cs_xxx \
  'https://www.pepselect.com/wp-json/wc/v3/products?per_page=100&status=publish' > products.json

# or on the server
wp wc product list --user=1 \
  --fields=id,sku,name,price,stock_quantity,stock_status,purchasable --format=csv
```

See §5.3 — if "Only 4 left" survives review, this check is **blocking**, and it must be re-run
immediately before send rather than at drafting time.

### 7.3 Incidental: one lot code corroborates

`BACKLOG.md:40` lists `ND_R30_060326` as a real current batch number, matching the GLP-3 R lot in the
brief. It also warns it predates the R6 numbering system:

> Current batch numbers on the site (e.g. `PSNAD562926JP`, `ND_R30_060326`) predate the R6
> batch-number system and **will not match its format**. This is expected, not a defect. … Do not
> retrofit or rewrite existing batch numbers.

**So do not "correct" `ND_R30_060326` to look like `PSGKCU5071926GX`.** The two lot codes in this
email are in different formats on purpose. The GHK-Cu code `PSGKCU5071926GX` does not appear anywhere
in the repo and still needs verification against the COA record.

---

## What is needed to finish

**Blocking on you:**

1. **Add `pepselect-email-peptide-is-a-word.html` to the repo root.** Everything in §1, §3, §4 then
   applies mechanically, and §5's full sweep can run.
2. **The two image files** — `Logo_Pepselect_Whitebackground.png` and the GLP-3 RT vial render — or
   the URLs where they are already hosted (try `wp option get woocommerce_email_header_image` first).

**Blocking on server access** (all four blocked here by the egress policy — `www.pepselect.com`
returns `CONNECT tunnel failed, response 403`):

3. GHK-Cu product slug — §1
4. COA lot-code parameter, if one exists — §2
5. WooCommerce email settings for wrapper/logo/footer/disclaimer — §6
6. Live stock and prices — §7.2

**Blocking on a decision:**

7. `#FFF4DF` → `#FDF6EA`? — §4
8. "Only 4 left" and hard-typed prices: remove, or approve as an exception? — §5.3
9. Purity figures and lot codes verified against the actual COA records — §5.2

Items 7-9 need a human call. The rest I will apply the moment the inputs land.

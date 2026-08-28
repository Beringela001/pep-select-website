# Technical SEO Findings — pepselect.com — 2026-08-28

**Specialist:** seo-technical (Claude SEO 2.2.4). **Method:** `fetch_page.py`, `render_page.py --mode auto`, `sitemap_discovery.py`, `curl -sI` on `/`, `/shop/`, `/product/pt-141/`, `/product/glutathione/`, `/privacy-policy/`, `/refund-shipping-policy/`, robots.txt, sitemap_index.xml. Read-only. Orchestrator re-verified headers and 8/23 code-ready items directly.

## Technical score: 74 / 100 (unchanged)

| Category | Score | Status |
|---|---|---|
| Crawlability | 90 | Pass |
| Indexability | 85 | Pass |
| Security (HTTPS/headers) | 40 | Fail |
| URL structure | 90 | Pass |
| Mobile | 85 | Pass |
| Core Web Vitals (lab signals) | 60 | Needs improvement |
| Structured data | 75 | Pass (partial) |
| JS rendering | 95 | Pass |
| IndexNow | 30 | Fail (original ledger classification: optional/SUPERSEDED — TECH-07) |

## Findings

**Critical (specialist rating; ledger priority TECH-01 High, TECH-02/03 Medium, TECH-04 Low preserved)**
- Security headers absent site-wide: no `Strict-Transport-Security`, `Content-Security-Policy`, `X-Frame-Options`, `Referrer-Policy`, `Permissions-Policy` on any sampled template. Only `X-Content-Type-Options: nosniff` present. Orchestrator `curl -sI https://pepselect.com/` 2026-08-28: `CF-Ray`, `Server: cloudflare`, `X-Content-Type-Options: nosniff`, `x-kinsta-cache: HIT` — nothing else. `/checkout/` → 302. Fourth consecutive cycle.

**High**
- No IndexNow key file or robots.txt reference (`grep -ic indexnow robots.txt` = 0). Ledger TECH-07 stays SUPERSEDED (optional) per original classification; specialist recommends implementing.
- No `aggregateRating`/`review` on product schema (see schema.md, SCHEMA-06/ECOM-01).

**Medium**
- Homepage carries 41 `<script src>` tags (WooCommerce/plugin sprawl); INP risk on cart interactions. Confirmed by PSI: home desktop TBT 1,770 ms (new PERF-14).
- No explicit `<meta name="robots">` on templates other than the Yoast default (informational).

**New this cycle**
- **MAP-04 (High):** `/order/` is `noindex, nofollow` yet listed in `page-sitemap.xml` (found by seo-sitemap; see sitemap.md).
- **TECH-09 (Low):** PSI "Browser errors were logged to the console" on home.

## Passing checks (evidence)
- robots.txt declares `Sitemap: https://pepselect.com/sitemap_index.xml`; `sitemap_discovery.py` validated 200, 5 child sitemaps.
- `http://` → 301 → `https://` single hop.
- Self-referencing canonicals on `/` and `/product/pt-141/`.
- Viewport meta present; single H1 on home.
- `render_page.py`: `is_spa: false`, `mode_used: raw` — server-rendered, safe for non-JS crawlers.
- `/wp-content/uploads/` directory listing 403 (hardened).

## Top fixes
1. HSTS (no `preload` initially) + `X-Frame-Options: SAMEORIGIN` + `Referrer-Policy` + `Permissions-Policy` at Cloudflare/Kinsta; CSP Report-Only first.
2. Remove `/order/` from the page sitemap (MAP-04).
3. IndexNow key + auto-ping (if Paulo opts in; TECH-07).
4. Trim/defer the 41 homepage scripts; see performance.md.
5. Capture console errors (TECH-09).

# GEO / AI Search Readiness Findings — pepselect.com — 2026-08-28

**Specialist:** seo-geo (`fetch_page.py`, `agent_ux_check.py`, `ucp_check.py`; home + one PDP; robots.txt; `/llms.txt`).

## AI Readiness score: 62 / 100
**Not comparable to the 8/23 figure (38):** different specialist rubric; no change occurred in the authority signals (sameAs, author, off-site) that drove the prior score. Use the dimension table, not the headline.

| Dimension | Score | Notes |
|---|---|---|
| Citability | 65 | Specific facts (purity %, batch ID, CAS, DOI-cited sources) but passages 30–60 words vs 134–167 optimum, fragmented across widgets (GEO-03) |
| Structural readability | 62 | Clean H1/H2/H3; question-phrased FAQ headings |
| Multi-modal | 50 | Photos with alt text; no video/tables/data widgets |
| Authority & brand | 45 | No `sameAs`, no named experts, no reviews (GEO-04/05, CONT-08). Positive: real phone, named lab (ILS Labs), peer-reviewed citations (PNAS, J Sex Med) |
| Technical accessibility | 85 | Server-rendered; robots open; segmented sitemaps |

## AI crawler access
robots.txt: wildcard `User-agent: *` with empty `Disallow:` — all crawlers allowed (GPTBot, OAI-SearchBot, ClaudeBot, PerplexityBot, Google-Extended, CCBot, Bytespider). No named tokens (GEO-09 — policy decision). `/llms.txt` → 404 (GEO-01, SUPERSEDED; GEO-10 no RSL signal).

## Findings
**High**
- Product/home answer content split across small UI fragments rather than one self-contained ~150-word passage (GEO-03).
- No entity verification: no `sameAs`, no author bylines, no reviews schema — weakest dimension and the one most correlated with AI citation (GEO-04/05, BLOCKED).

**Medium**
- Product meta descriptions generic ("price, availability, product details") — omit the standout stat (99.69% purity, batch-tested). Folded into SXO-10.
- `Dataset` JSON-LD absent on compound hubs (GEO-07 — orchestrator: 0/3 sampled hubs).
- **Specialist recommended `FAQPage` schema on the homepage FAQ. Rejected by the orchestrator:** the plugin quality gate records that Google retired FAQ rich results for all sites on 2026-05-07; SCHEMA-08 already classifies "no FAQPage" as correct. `QAPage` remains the only option and only for genuine user Q&A (GEO-02).

**Low**
- No multi-modal content beyond linked HTML batch reports.
- `meta[name=author]="beringela001"` still leaks on the guide (GEO-08).

## Brand-mention analysis
No on-site signals point to Wikipedia/Reddit/YouTube/LinkedIn presence; SXO WebSearch found zero brand mentions for "is pepselect legit reddit".

## Top fixes (after correction)
1. Consolidate product data into one 134–167-word answer block (mechanism + purity + batch + CAS + intended use) — GEO-03.
2. `Organization.sameAs` + About/Team page with named, credentialed staff — BLOCKED until real profiles/people exist (GEO-04/05, CONT-08, CONT-17).
3. `Dataset` on hubs (GEO-07, code-ready).
4. `/llms.txt` — optional (GEO-01).
5. Off-site presence: YouTube COA walkthrough, directories, reviews (GEO-05, DFS-02).

Platform estimates (directional, not live-polled): Google AIO ~60 · ChatGPT ~55 · Perplexity ~58 · Bing Copilot ~55.

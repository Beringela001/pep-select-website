import json
import sys
from playwright.sync_api import sync_playwright

OUT_DIR = r"C:\Users\paulo\Documents\Pep Select Website\docs\claude-seo-audit-2026-08-20\screenshots"
RESULT_PATH = r"C:\Users\paulo\Documents\Pep Select Website\vis_audit_result.json"
HTML_PATH = r"C:\Users\paulo\Documents\Pep Select Website\vis_audit_rendered.html"

result = {}

with sync_playwright() as p:
    browser = p.chromium.launch()

    # ---- Mobile 390x844 ----
    ctx_m = browser.new_context(viewport={"width": 390, "height": 844})
    page_m = ctx_m.new_page()
    page_m.goto("https://pepselect.com/", timeout=45000, wait_until="networkidle")
    page_m.screenshot(path=OUT_DIR + r"\gate-mobile-390x844.png")
    # Check if gate covers viewport - measure bounding box of dialog/gate element
    gate_info_m = page_m.evaluate("""
        () => {
            const candidates = document.querySelectorAll('[role="dialog"], .ps-access-gate, [class*="access-gate"], [id*="access-gate"], [class*="research-gate"], [id*="research-gate"]');
            const results = [];
            candidates.forEach(el => {
                const r = el.getBoundingClientRect();
                const cs = getComputedStyle(el);
                results.push({
                    tag: el.tagName, id: el.id, cls: el.className,
                    rect: {w: r.width, h: r.height, top: r.top, left: r.left},
                    display: cs.display, visibility: cs.visibility, zIndex: cs.zIndex, position: cs.position
                });
            });
            return {
                vw: window.innerWidth, vh: window.innerHeight,
                candidates: results,
                bodyOverflow: getComputedStyle(document.body).overflow,
                docHTML_length: document.documentElement.outerHTML.length
            };
        }
    """)
    result["mobile_390x844"] = gate_info_m
    ctx_m.close()

    # ---- Desktop 1440x900 ----
    ctx_d = browser.new_context(viewport={"width": 1440, "height": 900})
    page_d = ctx_d.new_page()
    page_d.goto("https://pepselect.com/", timeout=45000, wait_until="networkidle")
    page_d.screenshot(path=OUT_DIR + r"\gate-desktop-1440x900.png")
    gate_info_d = page_d.evaluate("""
        () => {
            const candidates = document.querySelectorAll('[role="dialog"], .ps-access-gate, [class*="access-gate"], [id*="access-gate"], [class*="research-gate"], [id*="research-gate"]');
            const results = [];
            candidates.forEach(el => {
                const r = el.getBoundingClientRect();
                const cs = getComputedStyle(el);
                results.push({
                    tag: el.tagName, id: el.id, cls: el.className,
                    rect: {w: r.width, h: r.height, top: r.top, left: r.left},
                    display: cs.display, visibility: cs.visibility, zIndex: cs.zIndex, position: cs.position
                });
            });
            return {
                vw: window.innerWidth, vh: window.innerHeight,
                candidates: results,
                bodyOverflow: getComputedStyle(document.body).overflow,
                docHTML_length: document.documentElement.outerHTML.length
            };
        }
    """)
    result["desktop_1440x900"] = gate_info_d

    # ---- Deep ARIA / markup / exit-link / font-size inspection (desktop page) ----
    deep = page_d.evaluate(r"""
        () => {
            const out = {};

            // dialog role element
            const dialog = document.querySelector('[role="dialog"]');
            if (dialog) {
                out.dialog_outerHTML_trunc = dialog.outerHTML.slice(0, 4000);
                out.dialog_attrs = {
                    role: dialog.getAttribute('role'),
                    ariaLabelledby: dialog.getAttribute('aria-labelledby'),
                    ariaDescribedby: dialog.getAttribute('aria-describedby'),
                    ariaModal: dialog.getAttribute('aria-modal'),
                    id: dialog.id
                };
                if (dialog.getAttribute('aria-describedby')) {
                    const descEl = document.getElementById(dialog.getAttribute('aria-describedby'));
                    out.describedby_text = descEl ? descEl.textContent.trim().slice(0,500) : null;
                }
                if (dialog.getAttribute('aria-labelledby')) {
                    const labEl = document.getElementById(dialog.getAttribute('aria-labelledby'));
                    out.labelledby_text = labEl ? labEl.textContent.trim().slice(0,500) : null;
                }
            } else {
                out.dialog_found = false;
            }

            // background inerting
            out.body_children_inert = [];
            Array.from(document.body.children).forEach(c => {
                out.body_children_inert.push({
                    tag: c.tagName, id: c.id, cls: (c.className||'').toString().slice(0,80),
                    inert: c.inert, ariaHidden: c.getAttribute('aria-hidden')
                });
            });

            // exit link - search for anchors with text mentioning researcher/exit
            const anchors = Array.from(document.querySelectorAll('a'));
            out.exit_candidates = anchors.filter(a => /exit|not a researcher|leave/i.test(a.textContent))
                .map(a => ({text: a.textContent.trim().slice(0,100), href: a.getAttribute('href'), target: a.getAttribute('target'), rel: a.getAttribute('rel')}));

            // all anchors inside dialog/gate for completeness
            const gateRoot = dialog || document.querySelector('[class*="access-gate"], [class*="research-gate"]');
            if (gateRoot) {
                out.gate_all_links = Array.from(gateRoot.querySelectorAll('a')).map(a => ({
                    text: a.textContent.trim().slice(0,100), href: a.getAttribute('href'), target: a.getAttribute('target')
                }));

                // logo
                const imgs = Array.from(gateRoot.querySelectorAll('img, svg'));
                out.gate_logo_elements = imgs.slice(0,5).map(el => ({
                    tag: el.tagName,
                    src: el.getAttribute('src'),
                    srcset: el.getAttribute('srcset'),
                    cls: (el.className||'').toString().slice(0,120),
                    alt: el.getAttribute ? el.getAttribute('alt') : null
                }));

                // legal / disclaimer text font sizes - find small text elements
                const textEls = Array.from(gateRoot.querySelectorAll('p, span, small, div, label'));
                const fontInfo = [];
                textEls.forEach(el => {
                    const txt = el.textContent.trim();
                    if (txt.length > 20 && el.children.length === 0) {
                        const cs = getComputedStyle(el);
                        fontInfo.push({
                            text: txt.slice(0,120),
                            fontSize: cs.fontSize,
                            tag: el.tagName,
                            cls: (el.className||'').toString().slice(0,80)
                        });
                    }
                });
                out.gate_text_font_sizes = fontInfo;
            } else {
                out.gate_root_found = false;
            }

            // focus trap: check for keydown listeners is hard from outside; report activeElement and tabindex info
            out.active_element_tag = document.activeElement ? document.activeElement.tagName : null;
            out.active_element_id = document.activeElement ? document.activeElement.id : null;

            return out;
        }
    """)
    result["deep_inspection"] = deep

    # Save full rendered HTML for grep-level backup inspection
    html = page_d.content()
    with open(HTML_PATH, "w", encoding="utf-8") as f:
        f.write(html)

    ctx_d.close()
    browser.close()

with open(RESULT_PATH, "w", encoding="utf-8") as f:
    json.dump(result, f, indent=2)

print("DONE")

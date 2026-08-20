import json
from playwright.sync_api import sync_playwright

RESULT_PATH = r"C:\Users\paulo\Documents\Pep Select Website\vis_audit_followup_result.json"

with sync_playwright() as p:
    browser = p.chromium.launch()
    ctx = browser.new_context(viewport={"width": 1440, "height": 900})
    page = ctx.new_page()
    page.goto("https://pepselect.com/", timeout=45000, wait_until="networkidle")

    data = page.evaluate("""
        () => {
            const out = {};
            const legalPs = Array.from(document.querySelectorAll('.psag-legal p'));
            out.legal_paragraphs = legalPs.map(p => {
                const cs = getComputedStyle(p);
                return { text: p.textContent.trim().slice(0,80), fontSize: cs.fontSize };
            });
            const gate = document.getElementById('psag-gate');
            out.gate_base_font = gate ? getComputedStyle(gate).fontSize : null;
            const card = document.querySelector('.psag-card');
            out.card_base_font = card ? getComputedStyle(card).fontSize : null;

            // logo responsive check: srcset/sizes/picture/media queries
            const logoImg = document.querySelector('.psag-logo img');
            out.logo_attrs = logoImg ? {
                src: logoImg.getAttribute('src'), srcset: logoImg.getAttribute('srcset'),
                sizes: logoImg.getAttribute('sizes'), width: logoImg.width, height: logoImg.height,
                naturalWidth: logoImg.naturalWidth
            } : null;

            // check for aria-describedby anywhere near dialog, and initial focus target
            const dialog = document.getElementById('psag-gate');
            out.dialog_full_attrs = dialog ? Array.from(dialog.attributes).map(a => a.name + '=' + a.value) : null;

            return out;
        }
    """)

    # mobile logo check
    ctx2 = browser.new_context(viewport={"width": 390, "height": 844})
    page2 = ctx2.new_page()
    page2.goto("https://pepselect.com/", timeout=45000, wait_until="networkidle")
    mobile_logo = page2.evaluate("""
        () => {
            const logoImg = document.querySelector('.psag-logo img');
            if (!logoImg) return null;
            const r = logoImg.getBoundingClientRect();
            return {width: r.width, height: r.height};
        }
    """)
    data["mobile_logo_rect"] = mobile_logo
    ctx2.close()

    ctx.close()
    browser.close()

with open(RESULT_PATH, "w", encoding="utf-8") as f:
    json.dump(data, f, indent=2)
print("DONE")

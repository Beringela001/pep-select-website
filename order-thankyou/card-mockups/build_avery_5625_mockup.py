from __future__ import annotations

from io import BytesIO
from pathlib import Path

from PIL import Image, ImageDraw, ImageFont
from reportlab.graphics.barcode.qr import QrCodeWidget
from reportlab.lib.pagesizes import letter
from reportlab.lib.utils import ImageReader
from reportlab.pdfgen import canvas


ROOT = Path(__file__).resolve().parents[2]
OUT = ROOT / "order-thankyou" / "card-mockups" / "output"
PDF_OUT = ROOT / "output" / "pdf"
LOGO_DARK = ROOT / "pepselect-child" / "assets" / "images" / "brand" / "pep-select-logo-header.png"
LOGO_LIGHT = ROOT / "pepselect-child" / "assets" / "images" / "brand" / "pep-select-logo-footer.png"

DPI = 300
CARD_W, CARD_H = 1050, 1500  # 3.5 x 5 inches at 300 DPI
RADIUS = 75  # Avery template uses a 0.25 inch corner radius

NAVY = "#002A53"
DARK_NAVY = "#001D3A"
CYAN = "#17A1CF"
GREEN = "#16834A"
GREEN_SOFT = "#EAF5EF"
INK = "#13283D"
SLATE = "#5E6F80"
BORDER = "#D7E1E9"
SURFACE = "#F3F8FC"
WHITE = "#FFFFFF"

FONT_REG = Path(r"C:\Windows\Fonts\segoeui.ttf")
FONT_SEMI = Path(r"C:\Windows\Fonts\seguisb.ttf")
FONT_BOLD = Path(r"C:\Windows\Fonts\segoeuib.ttf")
FONT_MONO = Path(r"C:\Windows\Fonts\consola.ttf")
FONT_MONO_BOLD = Path(r"C:\Windows\Fonts\consolab.ttf")
FONT_SCRIPT = ROOT / "order-thankyou" / "card-mockups" / "assets" / "Brother Signature.otf"


def font(path: Path, size: int) -> ImageFont.FreeTypeFont:
    return ImageFont.truetype(str(path), size=size)


def rounded_mask() -> Image.Image:
    mask = Image.new("L", (CARD_W, CARD_H), 0)
    ImageDraw.Draw(mask).rounded_rectangle((0, 0, CARD_W - 1, CARD_H - 1), radius=RADIUS, fill=255)
    return mask


def fit_logo(path: Path, max_w: int, max_h: int) -> Image.Image:
    logo = Image.open(path).convert("RGBA")
    logo.thumbnail((max_w, max_h), Image.Resampling.LANCZOS)
    return logo


def centered(draw: ImageDraw.ImageDraw, y: int, text: str, fnt: ImageFont.FreeTypeFont, fill: str, spacing: int = 4) -> int:
    box = draw.multiline_textbbox((0, 0), text, font=fnt, spacing=spacing, align="center")
    width = box[2] - box[0]
    draw.multiline_text(((CARD_W - width) / 2, y), text, font=fnt, fill=fill, spacing=spacing, align="center")
    return box[3] - box[1]


def qr_image(payload: str, size: int) -> Image.Image:
    widget = QrCodeWidget(payload)
    widget.qr.make()
    modules = widget.qr.modules
    count = widget.qr.moduleCount
    quiet_modules = 4
    module_px = max(1, size // (count + quiet_modules * 2))
    actual = module_px * (count + quiet_modules * 2)
    qr = Image.new("RGB", (actual, actual), WHITE)
    qd = ImageDraw.Draw(qr)
    for row, values in enumerate(modules):
        for col, enabled in enumerate(values):
            if enabled:
                x0 = (col + quiet_modules) * module_px
                y0 = (row + quiet_modules) * module_px
                qd.rectangle((x0, y0, x0 + module_px - 1, y0 + module_px - 1), fill=DARK_NAVY)
    return qr


def draw_check(draw: ImageDraw.ImageDraw, cx: int, cy: int, radius: int, color: str) -> None:
    draw.ellipse((cx - radius, cy - radius, cx + radius, cy + radius), outline=color, width=4)
    draw.line((cx - 8, cy, cx - 1, cy + 8, cx + 12, cy - 10), fill=color, width=4, joint="curve")


def draw_feature_icon(draw: ImageDraw.ImageDraw, cx: int, cy: int, kind: str) -> None:
    # Small hand-drawn-style illustrations: offset blobs, varied silhouettes, and
    # playful accent marks keep these from reading like a generic software icon set.
    if kind == "coa":
        draw.ellipse((cx - 34, cy - 29, cx + 27, cy + 32), fill="#E8F6FB")
        draw.ellipse((cx + 22, cy - 31, cx + 31, cy - 22), fill=CYAN)
        draw.polygon(
            ((cx - 18, cy - 20), (cx + 8, cy - 24), (cx + 15, cy + 12), (cx - 11, cy + 17)),
            fill=WHITE,
            outline=NAVY,
        )
        draw.line((cx - 10, cy - 9, cx + 5, cy - 11), fill=NAVY, width=3)
        draw.line((cx - 8, cy - 1, cx + 3, cy - 3), fill=NAVY, width=3)
        draw.ellipse((cx + 3, cy + 3, cx + 20, cy + 20), outline=CYAN, width=4)
        draw.line((cx + 17, cy + 17, cx + 27, cy + 27), fill=CYAN, width=4)
    elif kind == "product":
        draw.ellipse((cx - 30, cy - 32, cx + 33, cy + 31), fill="#EAF5EF")
        draw.polygon(((cx - 23, cy - 14), (cx - 2, cy - 9), (cx - 2, cy + 18), (cx - 23, cy + 12)), fill=WHITE, outline=NAVY)
        draw.polygon(((cx + 2, cy - 9), (cx + 23, cy - 14), (cx + 23, cy + 12), (cx + 2, cy + 18)), fill=WHITE, outline=NAVY)
        draw.line((cx, cy - 9, cx, cy + 18), fill=NAVY, width=3)
        draw.line((cx - 17, cy - 5, cx - 7, cy - 2), fill=CYAN, width=3)
        draw.line((cx + 7, cy - 2, cx + 17, cy - 5), fill=CYAN, width=3)
        draw.line((cx + 25, cy - 29, cx + 25, cy - 20), fill=CYAN, width=3)
        draw.line((cx + 20, cy - 24, cx + 30, cy - 24), fill=CYAN, width=3)
    elif kind == "gift":
        draw.ellipse((cx - 32, cy - 28, cx + 30, cy + 34), fill="#E8F6FB")
        draw.rectangle((cx - 18, cy - 4, cx + 18, cy + 21), fill=WHITE, outline=NAVY, width=3)
        draw.rectangle((cx - 22, cy - 13, cx + 22, cy - 3), fill=WHITE, outline=NAVY, width=3)
        draw.line((cx, cy - 13, cx, cy + 21), fill=CYAN, width=4)
        draw.arc((cx - 21, cy - 31, cx, cy - 8), 185, 360, fill=CYAN, width=4)
        draw.arc((cx, cy - 31, cx + 21, cy - 8), 180, 355, fill=CYAN, width=4)
        draw.line((cx - 29, cy - 24, cx - 34, cy - 30), fill=CYAN, width=3)
        draw.ellipse((cx + 27, cy - 26, cx + 34, cy - 19), fill=CYAN)
    else:
        draw.ellipse((cx - 31, cy - 30, cx + 31, cy + 32), fill="#EAF5EF")
        draw.rounded_rectangle((cx - 22, cy - 15, cx + 10, cy + 10), radius=8, fill=WHITE, outline=NAVY, width=3)
        draw.line((cx - 12, cy + 10, cx - 18, cy + 18, cx - 2, cy + 10), fill=NAVY, width=3)
        draw.rounded_rectangle((cx - 2, cy - 5, cx + 24, cy + 15), radius=7, fill="#E8F6FB", outline=CYAN, width=3)
        draw.ellipse((cx + 4, cy + 2, cx + 8, cy + 6), fill=NAVY)
        draw.ellipse((cx + 12, cy + 2, cx + 16, cy + 6), fill=NAVY)
        draw.line((cx + 23, cy - 24, cx + 23, cy - 16), fill=CYAN, width=3)
        draw.line((cx + 19, cy - 20, cx + 27, cy - 20), fill=CYAN, width=3)


def front_card() -> Image.Image:
    card = Image.new("RGBA", (CARD_W, CARD_H), WHITE)
    draw = ImageDraw.Draw(card)

    # A compact brand bar preserves Carol's hierarchy without taking over the card.
    draw.rounded_rectangle((0, 0, CARD_W - 1, 235), radius=RADIUS, fill=NAVY)
    draw.rectangle((0, 125, CARD_W, 235), fill=NAVY)
    logo = fit_logo(LOGO_LIGHT, 565, 122)
    card.alpha_composite(logo, ((CARD_W - logo.width) // 2, 54))

    centered(draw, 315, "THANK YOU", font(FONT_BOLD, 86), NAVY)
    centered(draw, 414, "for choosing Pep Select.", font(FONT_SEMI, 36), INK)
    draw.rounded_rectangle((452, 490, 598, 498), radius=4, fill=CYAN)

    # Carol's appreciation copy is intentionally preserved verbatim.
    body = (
        "We trully appreciate your trust in us. Our mission is simple:\n"
        "to provide high-quality research peptides with transparent testing,\n"
        "reliable service and fair pricing."
    )
    centered(draw, 565, body, font(FONT_REG, 31), SLATE, spacing=16)

    centered(draw, 790, "We're here if you ever need anything.", font(FONT_SEMI, 33), INK)
    centered(draw, 862, "- The Pep Select Team", font(FONT_REG, 30), INK)

    # The personal sign-off is deliberately larger and slightly angled to feel handwritten.
    signature = Image.new("RGBA", (760, 285), (0, 0, 0, 0))
    signature_draw = ImageDraw.Draw(signature)
    signature_draw.text((62, 4), "Thank you!", font=font(FONT_SCRIPT, 175), fill=CYAN)
    signature = signature.rotate(9, resample=Image.Resampling.BICUBIC, expand=True)
    card.alpha_composite(signature, (215, 1005))

    draw.rounded_rectangle((0, 1405, CARD_W - 1, CARD_H - 1), radius=RADIUS, fill=NAVY)
    draw.rectangle((0, 1405, CARD_W, 1460), fill=NAVY)
    centered(draw, 1434, "PEPSELECT.com", font(FONT_MONO_BOLD, 23), WHITE)

    card.putalpha(rounded_mask())
    return card


def back_card(token: str) -> Image.Image:
    card = Image.new("RGBA", (CARD_W, CARD_H), WHITE)
    draw = ImageDraw.Draw(card)

    logo = fit_logo(LOGO_DARK, 565, 125)
    card.alpha_composite(logo, ((CARD_W - logo.width) // 2, 72))
    draw.line((110, 238, 940, 238), fill=BORDER, width=2)

    centered(draw, 285, "MATCH YOUR BATCH.\nMATCH YOUR VIAL.", font(FONT_BOLD, 54), NAVY, spacing=0)
    centered(draw, 425, "Scan to open your order page.", font(FONT_REG, 29), SLATE)

    payload = f"https://pepselect.com/order/?access={token}"
    qr = qr_image(payload, 390)
    card.alpha_composite(qr.convert("RGBA"), ((CARD_W - qr.width) // 2, 500))

    items = [
        ("coa", "Verified COA Analysis", "View all 3rd party test results for this order"),
        ("product", "Additional Product Information", "Learn more about these compounds, storage, related studies"),
        ("gift", "A little gift from us", "A token of appreciation for choosing Pep Select"),
        ("help", "Have questions?", "We have answers! Contact our team."),
    ]

    y_positions = (945, 1060, 1190, 1310)
    wrapped_details = (
        "View all 3rd party test results for this order",
        "Learn more about these compounds, storage, related studies",
        "A token of appreciation for choosing Pep Select",
        "We have answers! Contact our team.",
    )
    for index, ((kind, label, _), y, detail) in enumerate(zip(items, y_positions, wrapped_details)):
        draw_feature_icon(draw, 108, y + 40, kind)
        draw.text((165, y + 4), label, font=font(FONT_SEMI, 26), fill=NAVY)
        draw.text((165, y + 45), detail, font=font(FONT_REG, 21), fill=SLATE)
        if index < 3:
            draw.line((165, y + 101, 925, y + 101), fill=BORDER, width=2)

    card.putalpha(rounded_mask())
    return card


def presentation(front: Image.Image, back: Image.Image, back_label: str) -> Image.Image:
    width, height = 2600, 1850
    preview = Image.new("RGB", (width, height), "#EAF1F6")
    draw = ImageDraw.Draw(preview)
    draw.text((130, 80), "PEP SELECT  /  ORDER THANK-YOU CARD", font=font(FONT_MONO_BOLD, 27), fill=CYAN)
    draw.text((130, 132), "Avery 5625 - 3.5 x 5 in - 300 DPI", font=font(FONT_BOLD, 56), fill=NAVY)
    draw.text((130, 210), "Reusable thank-you front. Unique QR back generated for every order.", font=font(FONT_REG, 30), fill=SLATE)

    scaled_w, scaled_h = 735, 1050
    front_small = front.resize((scaled_w, scaled_h), Image.Resampling.LANCZOS)
    back_small = back.resize((scaled_w, scaled_h), Image.Resampling.LANCZOS)
    positions = ((370, 365), (1495, 365))
    for image, (x, y), label in zip((front_small, back_small), positions, ("FRONT", back_label)):
        shadow = Image.new("RGBA", (scaled_w + 80, scaled_h + 80), (0, 0, 0, 0))
        sd = ImageDraw.Draw(shadow)
        sd.rounded_rectangle((35, 35, scaled_w + 35, scaled_h + 35), radius=55, fill=(0, 29, 58, 38))
        preview.paste(shadow, (x - 40, y - 20), shadow)
        preview.paste(image, (x, y), image)
        label_box = draw.textbbox((0, 0), label, font=font(FONT_MONO_BOLD, 22))
        lw = label_box[2] - label_box[0]
        draw.text((x + (scaled_w - lw) / 2, y + scaled_h + 52), label, font=font(FONT_MONO_BOLD, 22), fill=NAVY)

    draw.text((130, 1715), "PRINT NOTE", font=font(FONT_MONO_BOLD, 20), fill=CYAN)
    draw.text((310, 1706), "The final PDF is positioned to the supplied 2 x 2 Avery cut lines; no scaling should be applied when printing.", font=font(FONT_REG, 24), fill=INK)
    return preview


def build_pdf(front: Image.Image, back_sheets: list[list[Image.Image]], path: Path) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)
    c = canvas.Canvas(str(path), pagesize=letter)
    c.setTitle("Pep Select Avery 5625 Order Thank-You Card Mockup")

    # Exact cut boxes measured from the supplied PDF: 252 x 360 pt (3.5 x 5 in).
    placements = [(27, 405), (333, 405), (27, 27), (333, 27)]

    front_buffer = BytesIO()
    front.save(front_buffer, format="PNG", dpi=(DPI, DPI))
    front_reader = ImageReader(front_buffer)
    for x, y in placements:
        c.drawImage(front_reader, x, y, width=252, height=360, mask="auto")
    c.showPage()

    for backs in back_sheets:
        for image, (x, y) in zip(backs, placements):
            image_buffer = BytesIO()
            image.save(image_buffer, format="PNG", dpi=(DPI, DPI))
            c.drawImage(ImageReader(image_buffer), x, y, width=252, height=360, mask="auto")
        c.showPage()
    c.save()


def main() -> None:
    OUT.mkdir(parents=True, exist_ok=True)
    front = front_card()
    tokens = [f"DEMO-PS1048{index + 2}-{index + 1:02d}" for index in range(4)]
    backs = [back_card(token) for token in tokens]

    front.save(OUT / "pep-select-thank-you-card-front-300dpi.png", dpi=(DPI, DPI))
    backs[0].save(OUT / "pep-select-thank-you-card-back-stacked-300dpi.png", dpi=(DPI, DPI))
    presentation(front, backs[0], "BACK / STACKED").save(
        OUT / "pep-select-thank-you-card-front-back-preview.png", dpi=(150, 150)
    )
    build_pdf(front, [backs], PDF_OUT / "pep-select-avery-5625-card-mockup.pdf")

    print(f"front={CARD_W}x{CARD_H}px")
    print(f"back={CARD_W}x{CARD_H}px")
    print(f"pdf={PDF_OUT / 'pep-select-avery-5625-card-mockup.pdf'}")


if __name__ == "__main__":
    main()

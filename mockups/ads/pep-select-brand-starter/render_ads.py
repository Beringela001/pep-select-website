from pathlib import Path
from PIL import Image, ImageDraw, ImageFont


ROOT = Path(__file__).resolve().parents[3]
HERE = Path(__file__).resolve().parent
EXPORTS = HERE / "exports"
EXPORTS.mkdir(exist_ok=True)

NAVY = "#002A53"
DARK_NAVY = "#001D3A"
CYAN = "#17A1CF"
INK = "#13283D"
SLATE = "#5E6F80"
SURFACE = "#F3F8FC"
WHITE = "#FFFFFF"

FONT_HEAD = Path("C:/Windows/Fonts/georgiab.ttf")
FONT_UI = Path("C:/Windows/Fonts/arial.ttf")
FONT_UI_BOLD = Path("C:/Windows/Fonts/arialbd.ttf")
FONT_MONO = Path("C:/Windows/Fonts/consola.ttf")

LOGO_LIGHT = ROOT / "pepselect-child/assets/images/brand/pep-select-logo-header.png"
LOGO_DARK = ROOT / "pepselect-child/assets/images/brand/pep-select-logo-footer.png"
HERO = ROOT / "pepselect-child/assets/images/hero/pepselect-home-hero-1536.webp"
VIAL_BATCH = ROOT / "pepselect-child/assets/images/why-pep-select/tesamorelin-10mg-vial-batch.webp"


def font(path, size):
    return ImageFont.truetype(str(path), size)


def cover(image, size, focus=(0.5, 0.5)):
    image = image.convert("RGB")
    target_w, target_h = size
    scale = max(target_w / image.width, target_h / image.height)
    resized = image.resize((round(image.width * scale), round(image.height * scale)), Image.Resampling.LANCZOS)
    left = max(0, round((resized.width - target_w) * focus[0]))
    top = max(0, round((resized.height - target_h) * focus[1]))
    return resized.crop((left, top, left + target_w, top + target_h))


def place_logo(canvas, source, x, y, width):
    logo = Image.open(source).convert("RGBA")
    height = round(width * logo.height / logo.width)
    logo = logo.resize((width, height), Image.Resampling.LANCZOS)
    canvas.alpha_composite(logo, (x, y))
    return height


def draw_lines(draw, lines, xy, face, fill, gap=8):
    x, y = xy
    for line in lines:
        draw.text((x, y), line, font=face, fill=fill)
        box = draw.textbbox((x, y), line, font=face)
        y = box[3] + gap
    return y


def button(draw, xy, label, width=340, height=76, fill=NAVY, text_fill=WHITE):
    x, y = xy
    draw.rounded_rectangle((x, y, x + width, y + height), radius=12, fill=fill)
    face = font(FONT_UI_BOLD, 29)
    box = draw.textbbox((0, 0), label, font=face)
    tx = x + (width - (box[2] - box[0])) / 2
    ty = y + (height - (box[3] - box[1])) / 2 - box[1]
    draw.text((tx, ty), label, font=face, fill=text_fill)


def footer(draw, width, y, dark=False):
    face = font(FONT_MONO, 22)
    label = "For research use only."
    box = draw.textbbox((0, 0), label, font=face)
    draw.text(((width - (box[2] - box[0])) / 2, y), label, font=face, fill=WHITE if dark else SLATE)


def render_feed():
    size = (1080, 1350)
    canvas = Image.new("RGBA", size, SURFACE)
    image = cover(Image.open(HERO), (1080, 720), focus=(0.5, 0.5))
    canvas.alpha_composite(image.convert("RGBA"), (0, 0))
    draw = ImageDraw.Draw(canvas)
    draw.rounded_rectangle((64, 620, 1016, 1284), radius=32, fill=WHITE, outline="#D7E1E9", width=2)
    place_logo(canvas, LOGO_LIGHT, 118, 672, 360)
    draw.text((118, 824), "RESEARCH WITHOUT THE RUNAROUND", font=font(FONT_UI_BOLD, 24), fill=CYAN)
    draw_lines(draw, ["What’s behind the", "label matters."], (118, 876), font(FONT_HEAD, 68), NAVY, 2)
    draw.text((118, 1054), "Review available batch documentation before you decide", font=font(FONT_UI, 28), fill=INK)
    draw.text((118, 1094), "what belongs in your research workflow.", font=font(FONT_UI, 28), fill=INK)
    button(draw, (118, 1168), "Review COAs", width=300)
    footer(draw, size[0], 1304)
    canvas.convert("RGB").save(EXPORTS / "pep-select-documentation-meta-feed-1080x1350.jpg", quality=94, optimize=True)


def render_square():
    size = (1080, 1080)
    canvas = Image.new("RGBA", size, DARK_NAVY)
    image = cover(Image.open(VIAL_BATCH), (1080, 620), focus=(0.5, 0.36)).convert("RGBA")
    canvas.alpha_composite(image, (0, 0))
    draw = ImageDraw.Draw(canvas)
    draw.rounded_rectangle((48, 42, 500, 160), radius=18, fill=(255, 255, 255, 238))
    place_logo(canvas, LOGO_LIGHT, 76, 69, 385)
    draw.rectangle((0, 576, 1080, 1080), fill=DARK_NAVY)
    draw.text((72, 636), "BATCH DOCUMENTATION", font=font(FONT_UI_BOLD, 25), fill="#7DDBF4")
    draw_lines(draw, ["Find the record", "behind the vial."], (72, 690), font(FONT_HEAD, 68), WHITE, 0)
    button(draw, (72, 902), "View Testing History", width=390, fill=CYAN, text_fill=DARK_NAVY)
    footer(draw, size[0], 1030, dark=True)
    canvas.convert("RGB").save(EXPORTS / "pep-select-batch-record-square-1080x1080.jpg", quality=94, optimize=True)


def render_story():
    size = (1080, 1920)
    canvas = Image.new("RGBA", size, SURFACE)
    image = cover(Image.open(HERO), (1080, 910), focus=(0.5, 0.5)).convert("RGBA")
    canvas.alpha_composite(image, (0, 0))
    draw = ImageDraw.Draw(canvas)
    draw.rounded_rectangle((64, 760, 1016, 1660), radius=32, fill=WHITE, outline="#D7E1E9", width=2)
    place_logo(canvas, LOGO_LIGHT, 122, 840, 430)
    draw.text((122, 1024), "CAREFULLY SELECTED", font=font(FONT_UI_BOLD, 26), fill=CYAN)
    draw_lines(draw, ["A short list,", "on purpose."], (122, 1086), font(FONT_HEAD, 88), NAVY, 4)
    draw.text((122, 1322), "Explore research compounds with clear product", font=font(FONT_UI, 32), fill=INK)
    draw.text((122, 1368), "details and available batch records close at hand.", font=font(FONT_UI, 32), fill=INK)
    button(draw, (122, 1482), "Explore Compounds", width=380)
    footer(draw, size[0], 1738)
    canvas.convert("RGB").save(EXPORTS / "pep-select-product-first-story-1080x1920.jpg", quality=94, optimize=True)


if __name__ == "__main__":
    render_feed()
    render_square()
    render_story()
    for path in sorted(EXPORTS.glob("*.jpg")):
        print(path.relative_to(ROOT))

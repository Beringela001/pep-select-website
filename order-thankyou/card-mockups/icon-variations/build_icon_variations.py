from __future__ import annotations

from pathlib import Path

from PIL import Image, ImageDraw, ImageFont


ROOT = Path(__file__).resolve().parents[3]
OUT = ROOT / "order-thankyou" / "card-mockups" / "icon-variations" / "output"

NAVY = "#002A53"
CYAN = "#17A1CF"
GREEN = "#16834A"
INK = "#13283D"
SLATE = "#5E6F80"
CYAN_SOFT = "#E8F6FB"
GREEN_SOFT = "#EAF5EF"
BLUE_SOFT = "#EEF4FA"
WHITE = "#FFFFFF"

FONT_BOLD = Path(r"C:\Windows\Fonts\seguisb.ttf")
FONT_MONO = Path(r"C:\Windows\Fonts\consolab.ttf")

ICON = 360
SHEET_W, SHEET_H = 2160, 1020


def font(path: Path, size: int) -> ImageFont.FreeTypeFont:
    return ImageFont.truetype(str(path), size=size)


def blob(draw: ImageDraw.ImageDraw, variant: int) -> None:
    colors = (CYAN_SOFT, GREEN_SOFT, BLUE_SOFT)
    fill = colors[variant % len(colors)]
    if variant % 4 == 0:
        draw.ellipse((58, 55, 302, 305), fill=fill)
        draw.ellipse((205, 42, 308, 145), fill=WHITE)
    elif variant % 4 == 1:
        draw.rounded_rectangle((56, 58, 304, 304), radius=72, fill=fill)
        draw.ellipse((42, 206, 112, 276), fill=WHITE)
    elif variant % 4 == 2:
        draw.polygon(((180, 38), (300, 105), (305, 246), (190, 318), (63, 250), (55, 104)), fill=fill)
    else:
        draw.ellipse((50, 70, 290, 300), fill=fill)
        draw.ellipse((215, 38, 318, 141), fill=fill)


def sparkle(draw: ImageDraw.ImageDraw, x: int, y: int, size: int = 18, color: str = CYAN) -> None:
    draw.line((x - size, y, x + size, y), fill=color, width=7)
    draw.line((x, y - size, x, y + size), fill=color, width=7)


def dot(draw: ImageDraw.ImageDraw, x: int, y: int, radius: int = 8, color: str = CYAN) -> None:
    draw.ellipse((x - radius, y - radius, x + radius, y + radius), fill=color)


def report_icon(variant: int) -> Image.Image:
    image = Image.new("RGBA", (ICON, ICON), (0, 0, 0, 0))
    d = ImageDraw.Draw(image)
    blob(d, variant)
    w = 10
    if variant == 1:  # tilted report + loupe
        d.polygon(((92, 78), (224, 62), (243, 250), (110, 268)), fill=WHITE, outline=NAVY)
        d.line((125, 118, 199, 109), fill=NAVY, width=w)
        d.line((129, 151, 193, 143), fill=NAVY, width=w)
        d.ellipse((194, 187, 274, 267), outline=CYAN, width=12)
        d.line((256, 249, 302, 294), fill=CYAN, width=13)
    elif variant == 2:  # clipboard check
        d.rounded_rectangle((90, 83, 263, 280), radius=18, fill=WHITE, outline=NAVY, width=w)
        d.rounded_rectangle((134, 62, 220, 103), radius=13, fill=CYAN_SOFT, outline=CYAN, width=8)
        d.line((126, 147, 225, 147), fill=NAVY, width=9)
        d.line((126, 183, 203, 183), fill=NAVY, width=9)
        d.line((132, 231, 158, 255, 219, 202), fill=GREEN, width=13, joint="curve")
        sparkle(d, 286, 91)
    elif variant == 3:  # report + shield
        d.rounded_rectangle((77, 77, 230, 273), radius=14, fill=WHITE, outline=NAVY, width=w)
        d.line((110, 122, 194, 122), fill=NAVY, width=9)
        d.line((110, 157, 185, 157), fill=NAVY, width=9)
        d.polygon(((222, 158), (291, 184), (282, 256), (223, 296), (164, 256), (155, 184)), fill=CYAN_SOFT, outline=CYAN)
        d.line((190, 222, 216, 246, 263, 202), fill=GREEN, width=12, joint="curve")
    elif variant == 4:  # certificate ribbon
        d.rounded_rectangle((79, 67, 277, 245), radius=18, fill=WHITE, outline=NAVY, width=w)
        d.line((120, 116, 235, 116), fill=NAVY, width=9)
        d.line((120, 151, 220, 151), fill=NAVY, width=9)
        d.ellipse((141, 176, 216, 251), fill=CYAN_SOFT, outline=CYAN, width=10)
        d.polygon(((157, 240), (144, 304), (180, 283), (196, 306), (206, 239)), fill=CYAN)
    elif variant == 5:  # verified folder
        d.rounded_rectangle((56, 119, 302, 274), radius=22, fill=WHITE, outline=NAVY, width=w)
        d.polygon(((72, 119), (138, 119), (161, 91), (232, 91), (249, 119)), fill=CYAN_SOFT, outline=NAVY)
        d.line((111, 201, 150, 238, 241, 158), fill=GREEN, width=15, joint="curve")
        dot(d, 294, 88)
    elif variant == 6:  # result graph + loupe
        d.rounded_rectangle((71, 69, 273, 272), radius=16, fill=WHITE, outline=NAVY, width=w)
        d.line((104, 225, 104, 120), fill=SLATE, width=8)
        d.line((104, 225, 231, 225), fill=SLATE, width=8)
        d.line((116, 203, 145, 175, 178, 190, 225, 129), fill=CYAN, width=12, joint="curve")
        d.ellipse((202, 207, 286, 291), outline=NAVY, width=11)
        d.line((271, 278, 308, 313), fill=NAVY, width=12)
    elif variant == 7:  # report under an eye
        d.rounded_rectangle((105, 76, 251, 273), radius=15, fill=WHITE, outline=NAVY, width=w)
        d.line((132, 125, 221, 125), fill=NAVY, width=9)
        d.line((132, 159, 212, 159), fill=NAVY, width=9)
        d.arc((56, 165, 305, 296), 200, 340, fill=CYAN, width=13)
        d.arc((56, 118, 305, 249), 20, 160, fill=CYAN, width=13)
        d.ellipse((157, 188, 204, 235), fill=NAVY)
    elif variant == 8:  # stamped result
        d.polygon(((94, 70), (252, 83), (237, 280), (79, 266)), fill=WHITE, outline=NAVY)
        d.line((120, 119, 220, 128), fill=NAVY, width=9)
        d.line((117, 157, 205, 165), fill=NAVY, width=9)
        d.ellipse((122, 169, 253, 300), outline=CYAN, width=14)
        d.line((148, 230, 177, 258, 230, 206), fill=GREEN, width=14, joint="curve")
    elif variant == 9:  # stacked reports
        d.rounded_rectangle((70, 96, 233, 278), radius=12, fill=CYAN_SOFT, outline=CYAN, width=9)
        d.rounded_rectangle((102, 72, 272, 263), radius=12, fill=WHITE, outline=NAVY, width=w)
        d.line((132, 119, 234, 119), fill=NAVY, width=9)
        d.line((132, 153, 220, 153), fill=NAVY, width=9)
        d.line((136, 209, 160, 232, 221, 178), fill=GREEN, width=13, joint="curve")
        sparkle(d, 289, 286)
    else:  # microscope + report
        d.rounded_rectangle((176, 84, 292, 251), radius=12, fill=WHITE, outline=NAVY, width=9)
        d.line((199, 129, 266, 129), fill=NAVY, width=8)
        d.line((199, 160, 254, 160), fill=NAVY, width=8)
        d.line((89, 86, 148, 145), fill=CYAN, width=16)
        d.line((127, 123, 104, 188), fill=NAVY, width=15)
        d.arc((78, 158, 203, 284), 90, 285, fill=NAVY, width=14)
        d.line((68, 275, 211, 275), fill=NAVY, width=14)
    return image


def product_icon(variant: int) -> Image.Image:
    image = Image.new("RGBA", (ICON, ICON), (0, 0, 0, 0))
    d = ImageDraw.Draw(image)
    blob(d, variant + 1)
    w = 10
    if variant == 1:  # open guide
        d.polygon(((65, 99), (171, 125), (171, 282), (65, 251)), fill=WHITE, outline=NAVY)
        d.polygon(((178, 125), (289, 99), (289, 251), (178, 282)), fill=WHITE, outline=NAVY)
        d.line((175, 124, 175, 281), fill=CYAN, width=10)
        d.line((94, 149, 145, 162), fill=NAVY, width=8)
        d.line((211, 159, 265, 146), fill=NAVY, width=8)
        sparkle(d, 304, 75)
    elif variant == 2:  # vial + info bubble
        d.rounded_rectangle((82, 110, 190, 274), radius=22, fill=WHITE, outline=NAVY, width=w)
        d.rectangle((102, 77, 170, 112), fill=CYAN_SOFT, outline=NAVY, width=9)
        d.line((111, 199, 163, 199), fill=CYAN, width=10)
        d.rounded_rectangle((181, 74, 303, 181), radius=30, fill=WHITE, outline=CYAN, width=10)
        d.text((226, 87), "i", font=font(FONT_BOLD, 62), fill=NAVY)
    elif variant == 3:  # folding leaflet
        d.polygon(((57, 88), (135, 111), (135, 281), (57, 255)), fill=WHITE, outline=NAVY)
        d.polygon(((140, 111), (219, 83), (219, 254), (140, 281)), fill=CYAN_SOFT, outline=NAVY)
        d.polygon(((224, 83), (303, 111), (303, 281), (224, 254)), fill=WHITE, outline=NAVY)
        d.line((79, 145, 113, 156), fill=CYAN, width=8)
        d.line((245, 137, 282, 150), fill=CYAN, width=8)
    elif variant == 4:  # product tag + vial
        d.rounded_rectangle((63, 119, 166, 276), radius=20, fill=WHITE, outline=NAVY, width=w)
        d.rectangle((82, 88, 148, 120), fill=CYAN_SOFT, outline=NAVY, width=9)
        d.polygon(((180, 84), (293, 84), (315, 106), (315, 230), (202, 230), (180, 208)), fill=WHITE, outline=CYAN)
        d.ellipse((205, 107, 227, 129), outline=NAVY, width=7)
        d.text((233, 129), "i", font=font(FONT_BOLD, 58), fill=NAVY)
    elif variant == 5:  # folder of product cards
        d.rounded_rectangle((51, 125, 309, 279), radius=24, fill=WHITE, outline=NAVY, width=w)
        d.polygon(((65, 125), (137, 125), (158, 94), (239, 94), (257, 125)), fill=GREEN_SOFT, outline=NAVY)
        d.rounded_rectangle((96, 151, 165, 245), radius=10, fill=CYAN_SOFT, outline=CYAN, width=8)
        d.rounded_rectangle((182, 151, 256, 245), radius=10, fill=WHITE, outline=CYAN, width=8)
    elif variant == 6:  # scroll
        d.rounded_rectangle((90, 77, 276, 274), radius=20, fill=WHITE, outline=NAVY, width=w)
        d.arc((55, 64, 126, 142), 80, 280, fill=CYAN, width=10)
        d.arc((240, 213, 314, 290), 260, 100, fill=CYAN, width=10)
        d.line((129, 126, 236, 126), fill=NAVY, width=9)
        d.line((129, 164, 225, 164), fill=NAVY, width=9)
        d.line((129, 202, 211, 202), fill=NAVY, width=9)
        sparkle(d, 286, 76)
    elif variant == 7:  # bookmarked booklet
        d.rounded_rectangle((83, 67, 282, 286), radius=18, fill=WHITE, outline=NAVY, width=w)
        d.rectangle((118, 67, 159, 174), fill=CYAN)
        d.polygon(((118, 174), (139, 151), (159, 174)), fill=WHITE)
        d.line((181, 126, 247, 126), fill=NAVY, width=9)
        d.line((181, 164, 238, 164), fill=NAVY, width=9)
        d.line((118, 224, 244, 224), fill=SLATE, width=8)
    elif variant == 8:  # product cube + info
        d.polygon(((84, 120), (173, 75), (263, 120), (173, 167)), fill=WHITE, outline=NAVY)
        d.polygon(((84, 126), (173, 173), (173, 276), (84, 229)), fill=CYAN_SOFT, outline=NAVY)
        d.polygon(((178, 173), (263, 126), (263, 229), (178, 276)), fill=WHITE, outline=NAVY)
        d.ellipse((234, 58, 311, 135), fill=WHITE, outline=CYAN, width=10)
        d.text((262, 63), "i", font=font(FONT_BOLD, 47), fill=NAVY)
    elif variant == 9:  # magnifier over vial
        d.rounded_rectangle((85, 104, 190, 273), radius=22, fill=WHITE, outline=NAVY, width=w)
        d.rectangle((105, 74, 170, 106), fill=CYAN_SOFT, outline=NAVY, width=9)
        d.ellipse((169, 121, 280, 232), outline=CYAN, width=13)
        d.line((258, 211, 310, 264), fill=CYAN, width=14)
        d.line((112, 191, 163, 191), fill=NAVY, width=9)
    else:  # mini reference library
        d.rounded_rectangle((55, 75, 305, 286), radius=20, fill=WHITE, outline=NAVY, width=w)
        d.line((69, 213, 292, 213), fill=NAVY, width=10)
        for x, color in ((88, CYAN), (132, NAVY), (184, GREEN), (230, CYAN)):
            d.rounded_rectangle((x, 111, x + 30, 211), radius=6, fill=WHITE, outline=color, width=8)
        d.text((126, 224), "i", font=font(FONT_BOLD, 45), fill=CYAN)
    return image


def gift_icon(variant: int) -> Image.Image:
    image = Image.new("RGBA", (ICON, ICON), (0, 0, 0, 0))
    d = ImageDraw.Draw(image)
    blob(d, variant + 2)
    w = 10
    if variant == 1:  # classic confetti box
        d.rectangle((91, 151, 269, 279), fill=WHITE, outline=NAVY, width=w)
        d.rectangle((75, 118, 285, 153), fill=WHITE, outline=NAVY, width=w)
        d.line((180, 118, 180, 279), fill=CYAN, width=13)
        d.arc((112, 66, 181, 132), 175, 360, fill=CYAN, width=12)
        d.arc((179, 66, 248, 132), 180, 365, fill=CYAN, width=12)
        d.line((64, 83, 45, 63), fill=CYAN, width=9)
        dot(d, 298, 92)
    elif variant == 2:  # envelope heart
        d.rounded_rectangle((64, 96, 296, 269), radius=24, fill=WHITE, outline=NAVY, width=w)
        d.line((72, 112, 180, 196, 288, 112), fill=CYAN, width=11)
        d.polygon(((180, 246), (133, 204), (139, 169), (180, 185), (221, 169), (227, 204)), fill=CYAN)
        sparkle(d, 304, 68)
    elif variant == 3:  # open box stars
        d.polygon(((91, 168), (180, 205), (269, 168), (252, 281), (108, 281)), fill=WHITE, outline=NAVY)
        d.polygon(((91, 168), (59, 121), (151, 145), (180, 205)), fill=CYAN_SOFT, outline=CYAN)
        d.polygon(((269, 168), (301, 121), (209, 145), (180, 205)), fill=CYAN_SOFT, outline=CYAN)
        sparkle(d, 180, 89, 26)
        sparkle(d, 118, 82, 14, GREEN)
        dot(d, 246, 78)
    elif variant == 4:  # gift bag
        d.polygon(((94, 116), (266, 116), (283, 282), (77, 282)), fill=WHITE, outline=NAVY)
        d.arc((118, 65, 242, 163), 185, 355, fill=CYAN, width=12)
        d.line((180, 149, 180, 256), fill=CYAN, width=12)
        d.polygon(((180, 188), (150, 161), (130, 194), (180, 229), (230, 194), (210, 161)), fill=CYAN_SOFT, outline=CYAN)
    elif variant == 5:  # thank-you tag without text
        d.polygon(((76, 89), (230, 89), (303, 162), (181, 284), (76, 179)), fill=WHITE, outline=NAVY)
        d.ellipse((111, 119, 143, 151), outline=CYAN, width=9)
        d.polygon(((187, 224), (142, 184), (148, 148), (187, 168), (226, 148), (233, 184)), fill=CYAN)
        dot(d, 294, 80)
    elif variant == 6:  # ticket with bow
        d.rounded_rectangle((54, 119, 306, 258), radius=24, fill=WHITE, outline=NAVY, width=w)
        d.line((180, 123, 180, 255), fill=CYAN, width=10)
        d.arc((114, 75, 181, 139), 175, 360, fill=CYAN, width=11)
        d.arc((179, 75, 246, 139), 180, 365, fill=CYAN, width=11)
        d.line((92, 188, 143, 188), fill=SLATE, width=9)
        d.line((217, 188, 268, 188), fill=SLATE, width=9)
    elif variant == 7:  # star parcel
        d.polygon(((72, 120), (180, 69), (288, 120), (180, 177)), fill=WHITE, outline=NAVY)
        d.polygon(((72, 126), (180, 183), (180, 289), (72, 229)), fill=CYAN_SOFT, outline=NAVY)
        d.polygon(((186, 183), (288, 126), (288, 229), (186, 289)), fill=WHITE, outline=NAVY)
        d.line((180, 71, 180, 288), fill=CYAN, width=11)
        sparkle(d, 270, 74, 17)
    elif variant == 8:  # hands holding gift
        d.rectangle((126, 94, 234, 196), fill=WHITE, outline=NAVY, width=w)
        d.line((180, 94, 180, 196), fill=CYAN, width=10)
        d.arc((133, 57, 181, 112), 170, 355, fill=CYAN, width=10)
        d.arc((179, 57, 227, 112), 185, 365, fill=CYAN, width=10)
        d.arc((48, 142, 195, 303), 280, 80, fill=NAVY, width=14)
        d.arc((165, 142, 312, 303), 100, 260, fill=NAVY, width=14)
    elif variant == 9:  # card and ribbon
        d.rounded_rectangle((68, 74, 291, 269), radius=20, fill=WHITE, outline=NAVY, width=w)
        d.line((88, 126, 270, 126), fill=CYAN, width=10)
        d.polygon(((180, 235), (142, 202), (149, 169), (180, 184), (211, 169), (218, 202)), fill=CYAN)
        d.line((111, 153, 246, 153), fill=SLATE, width=8)
    else:  # balloon gift
        d.ellipse((183, 47, 291, 166), fill=CYAN_SOFT, outline=CYAN, width=10)
        d.line((237, 166, 211, 219), fill=CYAN, width=8)
        d.rectangle((70, 164, 211, 284), fill=WHITE, outline=NAVY, width=w)
        d.rectangle((58, 135, 223, 167), fill=WHITE, outline=NAVY, width=w)
        d.line((141, 136, 141, 284), fill=CYAN, width=11)
    return image


def help_icon(variant: int) -> Image.Image:
    image = Image.new("RGBA", (ICON, ICON), (0, 0, 0, 0))
    d = ImageDraw.Draw(image)
    blob(d, variant)
    w = 10
    if variant == 1:  # overlapping bubbles
        d.rounded_rectangle((55, 91, 228, 219), radius=36, fill=WHITE, outline=NAVY, width=w)
        d.polygon(((89, 215), (68, 267), (131, 218)), fill=WHITE, outline=NAVY)
        d.rounded_rectangle((158, 151, 306, 258), radius=34, fill=CYAN_SOFT, outline=CYAN, width=w)
        for x in (193, 232, 271):
            dot(d, x, 204, 7, NAVY)
    elif variant == 2:  # heart conversation
        d.rounded_rectangle((55, 83, 302, 262), radius=44, fill=WHITE, outline=NAVY, width=w)
        d.polygon(((95, 258), (72, 305), (142, 260)), fill=WHITE, outline=NAVY)
        d.polygon(((180, 226), (124, 178), (132, 132), (180, 156), (228, 132), (236, 178)), fill=CYAN)
        sparkle(d, 298, 76)
    elif variant == 3:  # friendly question
        d.rounded_rectangle((70, 68, 290, 270), radius=50, fill=WHITE, outline=CYAN, width=11)
        d.polygon(((114, 264), (92, 309), (161, 268)), fill=WHITE, outline=CYAN)
        d.text((145, 91), "?", font=font(FONT_BOLD, 122), fill=NAVY)
        dot(d, 280, 68)
    elif variant == 4:  # headset
        d.arc((64, 61, 296, 293), 180, 360, fill=NAVY, width=16)
        d.rounded_rectangle((54, 162, 100, 251), radius=18, fill=CYAN_SOFT, outline=CYAN, width=10)
        d.rounded_rectangle((260, 162, 306, 251), radius=18, fill=CYAN_SOFT, outline=CYAN, width=10)
        d.arc((104, 106, 256, 271), 5, 175, fill=NAVY, width=10)
        d.line((279, 249, 245, 281, 207, 281), fill=NAVY, width=10)
    elif variant == 5:  # envelope reply
        d.rounded_rectangle((58, 94, 302, 263), radius=25, fill=WHITE, outline=NAVY, width=w)
        d.line((68, 111, 180, 196, 292, 111), fill=CYAN, width=11)
        d.arc((198, 197, 319, 304), 170, 300, fill=GREEN, width=12)
        d.polygon(((302, 251), (321, 284), (282, 283)), fill=GREEN)
    elif variant == 6:  # phone + bubbles
        d.rounded_rectangle((78, 53, 221, 307), radius=24, fill=WHITE, outline=NAVY, width=w)
        d.line((124, 276, 176, 276), fill=CYAN, width=9)
        d.rounded_rectangle((165, 104, 307, 211), radius=30, fill=CYAN_SOFT, outline=CYAN, width=10)
        for x in (201, 237, 273):
            dot(d, x, 158, 7, NAVY)
    elif variant == 7:  # smiling chat
        d.rounded_rectangle((53, 78, 307, 268), radius=48, fill=WHITE, outline=NAVY, width=w)
        d.polygon(((103, 262), (76, 312), (151, 268)), fill=WHITE, outline=NAVY)
        dot(d, 135, 161, 10, CYAN)
        dot(d, 225, 161, 10, CYAN)
        d.arc((128, 152, 233, 234), 15, 165, fill=NAVY, width=11)
    elif variant == 8:  # bubble trio
        d.rounded_rectangle((45, 120, 172, 234), radius=33, fill=WHITE, outline=NAVY, width=9)
        d.rounded_rectangle((116, 62, 265, 184), radius=35, fill=CYAN_SOFT, outline=CYAN, width=9)
        d.rounded_rectangle((188, 146, 317, 254), radius=31, fill=WHITE, outline=NAVY, width=9)
        dot(d, 153, 122, 7)
        dot(d, 190, 122, 7)
        dot(d, 227, 122, 7)
    elif variant == 9:  # paper-plane answer
        d.rounded_rectangle((51, 73, 290, 257), radius=45, fill=WHITE, outline=NAVY, width=w)
        d.polygon(((108, 177), (252, 113), (207, 239), (169, 188)), fill=CYAN_SOFT, outline=CYAN)
        d.line((169, 188, 252, 113), fill=CYAN, width=8)
        d.polygon(((96, 252), (73, 299), (142, 257)), fill=WHITE, outline=NAVY)
    else:  # chat lifesaver
        d.ellipse((74, 73, 286, 285), fill=WHITE, outline=NAVY, width=w)
        d.ellipse((122, 121, 238, 237), fill=CYAN_SOFT, outline=CYAN, width=10)
        d.line((105, 105, 143, 143), fill=CYAN, width=12)
        d.line((217, 217, 255, 255), fill=CYAN, width=12)
        d.line((217, 143, 255, 105), fill=CYAN, width=12)
        d.line((105, 255, 143, 217), fill=CYAN, width=12)
        d.rounded_rectangle((146, 151, 214, 205), radius=18, fill=WHITE, outline=NAVY, width=8)
    return image


CATEGORIES = (
    ("VERIFIED COA ANALYSIS", report_icon, "verified-coa"),
    ("ADDITIONAL PRODUCT INFORMATION", product_icon, "product-information"),
    ("A LITTLE GIFT FROM US", gift_icon, "gift"),
    ("HAVE QUESTIONS?", help_icon, "questions"),
)


def build_sheet(title: str, renderer, slug: str) -> Path:
    sheet = Image.new("RGB", (SHEET_W, SHEET_H), "#F4F8FB")
    d = ImageDraw.Draw(sheet)
    d.text((90, 55), title, font=font(FONT_BOLD, 46), fill=NAVY)
    d.text((90, 118), "10 ICON DIRECTIONS", font=font(FONT_MONO, 22), fill=CYAN)

    start_x, start_y = 80, 185
    cell_w, cell_h = 400, 385
    for index in range(10):
        col, row = index % 5, index // 5
        x = start_x + col * cell_w
        y = start_y + row * cell_h
        d.rounded_rectangle((x, y, x + 340, y + 330), radius=42, fill=WHITE, outline="#D7E1E9", width=3)
        icon = renderer(index + 1)
        icon.thumbnail((280, 280), Image.Resampling.LANCZOS)
        sheet.paste(icon, (x + 30, y + 20), icon)
        number = f"{index + 1:02d}"
        box = d.textbbox((0, 0), number, font=font(FONT_MONO, 22))
        d.text((x + 170 - (box[2] - box[0]) / 2, y + 342), number, font=font(FONT_MONO, 22), fill=SLATE)

    OUT.mkdir(parents=True, exist_ok=True)
    path = OUT / f"{slug}-10-icon-variations.png"
    sheet.save(path, dpi=(150, 150))
    return path


def main() -> None:
    for title, renderer, slug in CATEGORIES:
        print(build_sheet(title, renderer, slug))


if __name__ == "__main__":
    main()

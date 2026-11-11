"""Draws one illustrated candle image per product and saves it as WebP.

Reads  scripts/seed/products.json  (name, sku, wax and bg colours)
Writes assets/images/products/<sku>.webp

Run:  python scripts/images/generate_product_images.py
"""
import json
from pathlib import Path

from PIL import Image, ImageDraw, ImageFilter, ImageFont

ROOT = Path(__file__).resolve().parents[2]
PRODUCTS = ROOT / "scripts" / "seed" / "products.json"
OUT_DIR = ROOT / "assets" / "images" / "products"
SIZE = 1200
FONT = "C:/Windows/Fonts/georgia.ttf"


def hex_rgb(value: str) -> tuple:
    value = value.lstrip("#")
    return tuple(int(value[i:i + 2], 16) for i in (0, 2, 4))


def shade(color: tuple, factor: float) -> tuple:
    return tuple(max(0, min(255, int(c * factor))) for c in color)


def background(bg: tuple) -> Image.Image:
    """Soft vertical gradient with a lighter table surface."""
    img = Image.new("RGB", (SIZE, SIZE), bg)
    draw = ImageDraw.Draw(img)
    for y in range(SIZE):
        draw.line([(0, y), (SIZE, y)], fill=shade(bg, 1.06 - 0.12 * y / SIZE))
    draw.rectangle([0, 850, SIZE, SIZE], fill=shade(bg, 0.9))
    return img


def draw_glow(img: Image.Image, cx: int, cy: int) -> None:
    glow = Image.new("RGBA", img.size, (255, 214, 120, 0))  # same colour, so blurring stays warm
    ImageDraw.Draw(glow).ellipse([cx - 170, cy - 170, cx + 170, cy + 170], fill=(255, 214, 120, 150))
    img.paste(Image.alpha_composite(img.convert("RGBA"), glow.filter(ImageFilter.GaussianBlur(70))).convert("RGB"))


def draw_candle(img: Image.Image, cx: int, base_y: int, wax: tuple, width: int, height: int) -> None:
    draw = ImageDraw.Draw(img)
    left, right, top = cx - width // 2, cx + width // 2, base_y - height
    draw.ellipse([left + 10, base_y - 18, right - 10, base_y + 34], fill=shade(wax, 0.55))  # shadow
    draw.rounded_rectangle([left, top, right, base_y], radius=36, fill=shade(wax, 0.82))  # jar
    draw.rounded_rectangle([left + 14, top + 60, right - 14, base_y - 14], radius=26, fill=wax)  # wax
    draw.ellipse([left + 14, top + 36, right - 14, top + 84], fill=shade(wax, 1.12))  # wax top
    draw.line([(cx, top + 60), (cx, top + 16)], fill=(60, 45, 35), width=7)  # wick
    draw.ellipse([cx - 13, top - 46, cx + 13, top + 14], fill=(255, 196, 80))  # flame
    draw.ellipse([cx - 6, top - 26, cx + 6, top + 8], fill=(255, 246, 214))


def draw_label(img: Image.Image, cx: int, base_y: int, width: int, text: str) -> None:
    draw = ImageDraw.Draw(img)
    font = ImageFont.truetype(FONT, 34)
    lines = text.replace(" Soy Candle", "").replace(" (3 candles)", "").split(" and ")
    box_h = 70 + 44 * len(lines)
    top = base_y - 60 - box_h
    draw.rounded_rectangle([cx - width // 2 + 40, top, cx + width // 2 - 40, top + box_h], radius=14, fill=(252, 249, 242))
    y = top + 28
    for line in lines:
        w = draw.textlength(line, font=font)
        draw.text((cx - w / 2, y), line, fill=(70, 55, 45), font=font)
        y += 44


def render(product: dict) -> Image.Image:
    wax, bg = hex_rgb(product["wax"]), hex_rgb(product["bg"])
    img = background(bg)
    gift = "Gift Set" in product["name"]
    jars = [(300, 280, 340), (600, 320, 420), (900, 280, 340)] if gift else [(600, 460, 560)]
    for cx, width, height in jars:
        draw_glow(img, cx, 850 - height - 20)
        draw_candle(img, cx, 850, wax, width, height)
    if not gift:
        draw_label(img, 600, 850, 460, product["name"])
    return img


def main() -> None:
    OUT_DIR.mkdir(parents=True, exist_ok=True)
    for product in json.loads(PRODUCTS.read_text(encoding="utf-8")):
        path = OUT_DIR / f"{product['sku']}.webp"
        render(product).save(path, "WEBP", quality=82)
        print(f"{path.name}: {path.stat().st_size // 1024} KB")


if __name__ == "__main__":
    main()

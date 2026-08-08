"""
Compose Figma's per-path SVG fragments back into single icons.

get_design_context returns a multi-path icon as N absolutely-positioned
fragments inside a fixed box. Rather than redraw the glyphs, this fetches the
exact fragment bytes and replays Figma's own positioning maths:

  base rect  <- the fragment's inset[top right bottom left] (% of the box)
  final rect <- base rect expanded by the wrapper's negative insets
                (% of the base rect, or a literal px value)

Each fragment is then placed with a translate+scale that maps its viewBox onto
that rect, which reproduces the frame pixel for pixel. A composed icon whose
scale factors come out at 1.0 confirms the fragments were already at natural
size in the box.

Asset URLs expire ~7 days after the get_design_context call that produced them,
so re-running this later needs fresh URLs from a new call.

Usage: python3 scripts/compose-figma-icons.py resources/images/sizdah
"""

import re
import urllib.request
import xml.etree.ElementTree as ET

BASE = "https://www.figma.com/api/mcp/asset/"
SQUARE = (-12.5, -12.5, -12.5, -12.5)
NONE = (0, 0, 0, 0)

# name -> (box size, output subdir, [(asset uid, inset, wrapper inset), ...])
# inset/wrapper are (top, right, bottom, left); wrapper values are % of the base
# rect unless given as a "1px"-style string.
ICONS = {
    # Home KPI row — 268:3026
    "kpi-engagement": (32, "home", [
        ("1cb2187b-eca5-40ee-bab6-094210a3f992", (29.17, 8.33, 45.83, 66.67), SQUARE),
        ("a4ac2d52-341d-49e9-849d-84f8841f7a0b", (29.17, 8.33, 29.17, 8.33), (-7.5, -3.75, -7.5, -3.75)),
    ]),
    "kpi-audience": (32, "home", [
        ("fa60f9dd-cd80-41e2-a899-2b18debc9c5a", (66.67, 50.0, 12.5, 50.0), (-15.0, "1px", -15.0, "1px")),
        ("bf7b8faf-5c9a-4ab3-bb0e-5b55c399fbb8", (61.0, 33.33, 12.5, 66.67), (-11.79, "1px", -11.79, "1px")),
        ("77b303a2-04f3-4fc3-bf3b-3792356af01b", (44.4, 16.67, 12.5, 83.33), (-7.25, "1px", -7.25, "1px")),
        ("05ee4eeb-9b64-44bb-a627-8843526f047c", (12.5, 8.33, 37.5, 8.33), (-6.25, -3.75, -6.25, -3.75)),
        ("ee822fec-0fda-4264-bee6-d56c183989d0", (76.93, 83.33, 12.5, 16.67), (-29.56, "1px", -29.56, "1px")),
        ("664f0d9a-084e-404c-9544-a3f37456b670", (61.07, 66.67, 12.5, 33.33), (-11.82, "1px", -11.82, "1px")),
    ]),
    # Also used as the "working with" icon on Contact (I279:6438) — identical
    # fragments and insets, so the Home export is reused there.
    "kpi-retention": (32, "home", [
        ("da3703a1-ae5b-4960-8cff-8b6bf0b7dca4", (62.5, 33.33, 12.5, 8.33), (-12.5, -5.36, -12.5, -5.36)),
        ("8610244f-7143-4f6e-858a-ba6fea8d5445", (13.04, 20.85, 54.7, 66.67), (-9.69, -25.03, -9.69, -25.04)),
        ("d5cef871-966d-49d9-abba-56656ae406a2", (63.05, 8.33, 12.5, 79.17), (-12.78, -25.0, -12.78, -25.01)),
        ("e24f6ab5-da50-4e09-8e14-8442a67305e9", (12.5, 45.83, 54.17, 20.83), (-9.37, -9.37, -9.37, -9.37)),
    ]),

    # Contact — 279:6398. Detail-row icons (24px inside a 48px round chip).
    "contact-phone": (24, "contact", [
        ("763151f8-56f1-4d06-9e08-b8873fae19af", (8.33, 8.33, 8.33, 8.33), (-3.75, -3.75, -3.75, -3.75)),
    ]),
    "contact-location": (24, "contact", [
        ("73a0b4e3-0abd-4832-9ad4-245a0cf01551", (8.33, 16.67, 8.33, 16.67), (-3.75, -4.69, -3.75, -4.69)),
        ("9094d288-fde7-46c8-8aae-36a354a8f02d", (29.17, 37.5, 45.83, 37.5), SQUARE),
    ]),
    "contact-email": (24, "contact", [
        ("d170a622-641a-4e97-b58d-95f03ee706a8", (29.17, 8.33, 45.84, 8.33), (-12.51, -3.75, -12.51, -3.75)),
        ("3928c7ad-99ec-4675-82f5-4ebc6da2f5ba", (16.67, 8.33, 16.67, 8.33), (-4.69, -3.75, -4.69, -3.75)),
    ]),

    # Contact — form field affordances.
    "field-brand": (24, "contact", [
        ("b6433d3e-0bb2-4322-8427-83e41329871b", (8.35, 8.35, 8.32, 8.29), (-4.5, -4.5, -4.5, -4.5)),
        ("6ec06b57-7049-4fce-9983-db3eb71bbe93", (41.67, 37.5, 41.67, 37.5), (-22.5, -15.0, -22.5, -15.0)),
    ]),
    "field-service": (24, "contact", [
        ("bf056f6e-e0eb-45aa-954d-e84cf521151a", (8.33, 8.26, 50.0, 8.35), (-9.0, -4.5, -9.0, -4.5)),
        ("793b0c89-b952-43d1-b6d7-5001702ba190", (50.0, 8.33, 29.17, 8.33), (-18.01, -4.5, -18.01, -4.5)),
        ("793b0c89-b952-43d1-b6d7-5001702ba190", (70.83, 8.33, 8.34, 8.33), (-18.01, -4.5, -18.01, -4.5)),
    ]),
    "field-caret": (24, "contact", [
        ("69289573-3a47-461f-937f-71b321e54428", (37.5, 25.0, 37.5, 25.0), (-12.5, -6.25, -12.5, -6.25)),
    ]),
    "flag-oman": (20, "contact", [
        ("075cba05-a2d9-47ab-a4af-e9bf5a6fa465", (63.89, 0, 13.89, 22.22), NONE),
        ("b1ef0d78-fc59-487f-85a3-fcb0d9195004", (13.89, 0, 63.89, 22.22), NONE),
        ("43fdfc9a-3916-429d-a84b-dfe5b80bf09d", (13.89, 0, 13.89, 0), NONE),
        ("a58a1e0f-848b-48fc-9e20-d40504c76a37", (19.61, 80.6, 66.5, 5.5), NONE),
    ]),
}

# Single-file assets that need no composition — saved as-is.
PLAIN = {
    "social/instagram": "2a061a1d-325b-4f16-8bab-3e00defaf85e",
    "social/linkedin": "4ea93fdf-a935-4c8c-84fb-e1bc387c7d7a",
    "social/whatsapp": "a8addbd1-8ecb-487f-a8b0-8a5bc85b86ff",
    "social/x": "376b2b14-a6cc-4840-83c6-f2eada72de35",
    "social/telegram": "38f1a6e5-f12b-4559-bff1-37315f555e33",
    "contact/field-user": "1c68969e-5820-42b9-bc9b-3bd48941b273",
    "shared/wordmark-inline": "d54a6dfa-fd68-4c44-9429-2983db200a76",
    "shared/up-right-arrow": "cda62799-d7fd-4ef8-9610-6004676be743",
}

ET.register_namespace("", "http://www.w3.org/2000/svg")


def fetch(uid: str) -> str:
    # Figma's asset host rejects urllib's default User-Agent with an empty body.
    req = urllib.request.Request(BASE + uid + ".svg", headers={"User-Agent": "curl/8"})
    with urllib.request.urlopen(req) as r:
        return r.read().decode("utf-8")


def viewbox(svg: ET.Element) -> tuple[float, float, float, float]:
    vb = svg.get("viewBox")
    if vb:
        return tuple(float(v) for v in re.split(r"[ ,]+", vb.strip()))
    w = float(re.sub(r"[^\d.]", "", svg.get("width", "1")) or 1)
    h = float(re.sub(r"[^\d.]", "", svg.get("height", "1")) or 1)
    return (0.0, 0.0, w, h)


def expand(value, extent: float) -> float:
    """Wrapper insets are % of the base rect, except literal px values."""
    if isinstance(value, str) and value.endswith("px"):
        return float(value[:-2])
    return -float(value) / 100.0 * extent


def compose(size: float, parts: list) -> tuple[str, list[float]]:
    root = ET.Element(
        "svg",
        {
            # ET emits xmlns itself via register_namespace — setting it here too
            # produces a duplicate attribute and invalid XML.
            "viewBox": f"0 0 {size:g} {size:g}",
            "width": f"{size:g}",
            "height": f"{size:g}",
            "fill": "none",
        },
    )
    scales = []
    for uid, inset, wrap in parts:
        top, right, bottom, left = inset
        x0 = left / 100.0 * size
        y0 = top / 100.0 * size
        w0 = (100.0 - left - right) / 100.0 * size
        h0 = (100.0 - top - bottom) / 100.0 * size

        dt, dr, db, dl = (expand(w, h0 if i % 2 == 0 else w0) for i, w in enumerate(wrap))
        x, y = x0 - dl, y0 - dt
        w, h = w0 + dl + dr, h0 + dt + db

        frag = ET.fromstring(fetch(uid))
        vx, vy, vw, vh = viewbox(frag)
        sx = w / vw if vw else 1.0
        sy = h / vh if vh else 1.0
        scales += [sx, sy]

        g = ET.SubElement(root, "g")
        g.set("transform", f"translate({x:.4f} {y:.4f}) scale({sx:.6f} {sy:.6f}) translate({-vx:.4f} {-vy:.4f})")
        for child in list(frag):
            g.append(child)

    return ET.tostring(root, encoding="unicode"), scales


if __name__ == "__main__":
    import os
    import sys

    out = sys.argv[1].rstrip("/")

    for name, (size, subdir, parts) in ICONS.items():
        os.makedirs(f"{out}/{subdir}", exist_ok=True)
        svg, scales = compose(size, parts)
        with open(f"{out}/{subdir}/{name}.svg", "w") as fh:
            fh.write(svg + "\n")
        drift = max(abs(s - 1.0) for s in scales)
        print(f"{subdir}/{name}.svg  {len(parts)} fragment(s)  max scale drift {drift:.4f}")

    for path, uid in PLAIN.items():
        os.makedirs(f"{out}/{os.path.dirname(path)}", exist_ok=True)
        body = fetch(uid)
        with open(f"{out}/{path}.svg", "w") as fh:
            fh.write(body)
        print(f"{path}.svg  verbatim  {len(body)} bytes")

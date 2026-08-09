# Translations TODO

Persian is the source language for the Sizdah rebrand — every string below is
taken verbatim from the Figma file. English and Arabic are **not** machine
translated; they are listed here so a human translator can supply the real copy.

Until a row is resolved, `en` and `ar` fall back to whatever the previous
(Sahra-era) wording was, which is serviceable but off-brand.

## Stale — fa was rewritten from Figma, en/ar still hold Sahra-era copy

| Key | fa (new, from Figma) | Needs |
| --- | --- | --- |
| `errors.404.title` | مسیر را گم کردید؟ (266:2831) | en, ar |
| `errors.404.message` | صفحه‌ای که دنبال آن بودید پیدا نشد، اما با هم می‌توانیم مسیر درست را پیدا کنیم. (266:2832) | en, ar |

## Editorial copy — belongs in the database, not in lang files

Per the brief, section copy stays editable in Filament, so it lives in the
`{entity}_translations` tables rather than here. The Figma frames supply the fa
column only; en/ar rows need authoring before those locales are usable:

- `home` page sections — hero, kpi, trust_proof, services_cloud, lead_magnet,
  projects_showcase, process, why_us, reviews, final_cta
- Services page — four service entries (برندینگ، تولید محتوا، مارکتینگ،
  پشتیبانی شبکه‌های اجتماعی) with their feature lists
- Legal pages — privacy policy (8 blocks) and terms of use

## Known design constraint

The 404 illustration (`resources/images/sizdah/errors/lost-map.png`) has the
numerals ۴۰۴ drawn into the artwork, so it reads as Persian digits in every
locale. Replacing it for en/ar needs a new export from design — it cannot be
fixed in code.

## Added during the Contact frame

| Key | Action taken | Needs |
| --- | --- | --- |
| `forms.details.follow` | The sentence changed structure — the wordmark is now a separate image placed before the text, so the string must no longer contain the brand name. The old en/ar copy still said "Sahra" / "صحراء", which is both the wrong brand and now duplicated. Both locales were set to the Persian value rather than machine translated. | en, ar |
| `forms.contact.phone_placeholder` | New key. en/ar received Latin and Arabic-Indic digits respectively — a numeral-system choice, not a translation. | review only |

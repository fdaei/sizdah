# SEO — راهنمای پنل مدیریت / Admin panel guide

> برای کارشناس سئویی که با وردپرس + Yoast / Rank Math کار کرده است.
> For SEO specialists coming from WordPress + Yoast / Rank Math.
> نسخه‌ی کامل و تصویری داخل پنل: **Website setup ← راهنمای سئو (SEO guide)**.

## فارسی

| کار | کجای پنل | معادل وردپرس |
|---|---|---|
| عنوان سئو، نامک، توضیحات متا، پیش‌نمایش گوگل، کلمه کلیدی کانونی و مرتبط، محتوای ستون | فرم هر محتوا ← باکس «سئو (Yoast SEO)» ← تب سئو | متاباکس Yoast |
| تحلیل سئو و خوانایی (فارسی) | همان باکس ← تب سئو / خوانایی؛ ستون و فیلتر امتیاز در لیست‌ها | تحلیل Yoast |
| عنوان/توضیحات/تصویر فیسبوک و ایکس | باکس سئو ← تب شبکه‌های اجتماعی | Yoast Social |
| نوع صفحه، نوع مقاله، JSON-LD سفارشی | باکس سئو ← تب اسکیما | Yoast Schema / Rank Math Schema |
| index/noindex، follow، روبات پیشرفته، کنونیکال، عنوان بردکرامب، نقشه سایت | باکس سئو ← تب پیشرفته | Yoast Advanced |
| ویرایشگر متن (H1–H6، لینک nofollow/sponsored، تصویر با alt، جدول، یوتیوب/آپارات، FAQ) | فیلد متن مقاله / صفحه / بخش پروژه | ویرایشگر کلاسیک/گوتنبرگ |
| قالب عنوان، جداکننده، پیش‌فرض‌ها، Knowledge Graph و سئوی محلی، کدهای تأیید، noindex آرشیوها، بردکرامب، robots.txt، کد سفارشی | Website setup ← تنظیمات سئو | Yoast Settings / Rank Math Titles & Meta, Local SEO, Webmaster Tools |
| ریدایرکت‌ها (301/302/307/308/410/451، Regex، CSV) | Advanced ← ریدایرکت‌ها | Rank Math Redirections |
| گزارش ۴۰۴ | Advanced ← گزارش ۴۰۴ | Rank Math 404 Monitor |
| نقشه سایت | خودکار: `/sitemap.xml` | Yoast XML Sitemaps |

## English

| Task | Where in the panel | WordPress equivalent |
|---|---|---|
| SEO title, slug, meta description, Google preview, focus + related keyphrases, cornerstone | Any content form → "سئو (Yoast SEO)" box → SEO tab | Yoast meta box |
| SEO + readability analysis (Persian-aware) | Same box → SEO / Readability tabs; score columns + filters in tables | Yoast analysis |
| Facebook / X title, description, image | SEO box → Social tab | Yoast Social |
| Page type, article type, custom JSON-LD | SEO box → Schema tab | Yoast / Rank Math Schema |
| index/noindex, follow, advanced robots, canonical, breadcrumb title, sitemap options | SEO box → Advanced tab | Yoast Advanced |
| Body editor (H1–H6, nofollow/sponsored links, images with alt, tables, YouTube/Aparat, FAQ) | Article / page / project-section body | Classic / Gutenberg |
| Title templates, separator, defaults, Knowledge Graph + Local SEO, verification codes, archive noindex, breadcrumbs, robots.txt, custom code | Website setup → SEO settings | Yoast Settings / Rank Math Titles & Meta, Local SEO, Webmaster Tools |
| Redirections (301/302/307/308/410/451, regex, CSV import/export) | Advanced → Redirections | Rank Math Redirections |
| 404 monitor | Advanced → 404 monitor | Rank Math 404 Monitor |
| XML sitemaps | Automatic: `/sitemap.xml` (per-type, 1000 URLs/page, images, hreflang) | Yoast XML Sitemaps |

### Production checklist

1. Google Rich Results Test on: home (FAQPage), an article (BlogPosting, VideoObject if any), About (Person), a project.
2. Search Console → URL inspection on the same URLs; confirm the rendered HTML has `<title>`, canonical and robots.
3. Search Console → Sitemaps → submit `https://sizdahmarketing.com/sitemap.xml` (remove the old `sitemap-fa.xml`; it now 301s).
4. SEO settings → Webmaster tools: paste the Google/Bing verification codes.
5. SEO settings → Search appearance: confirm "Discourage search engines" is **off**.
6. Check `https://sizdahmarketing.com/robots.txt` ends with the absolute `Sitemap:` line.

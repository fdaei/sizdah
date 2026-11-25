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

### بازبینی فنی ۲۰۲۶-۱۱-۲۵

اصلاحات کد در این بازبینی:

- لینک‌های `hreflang` فقط به ترجمه‌های موجود و قابل ایندکس اشاره می‌کنند. لینک خدمات از نامک واقعی هر زبان استفاده می‌کند و شمارهٔ صفحه در آرشیوهای صفحه‌بندی‌شده حفظ می‌شود.
- صفحه‌های دوم به بعد عنوان مجزا دارند؛ قالب‌های سفارشی دارای `%page%` حفظ می‌شوند. پارامتر `page` در صفحات ثابت کنونیکال تازه ایجاد نمی‌کند.
- مقالهٔ ویژه در تمام صفحه‌های فهرست از کوئری شبکه کنار گذاشته می‌شود تا مرز صفحهٔ اول و دوم مقالهٔ تکراری نسازد.
- توضیحات پیش‌فرض صفحات حقوقی از متن همان صفحه استخراج می‌شوند. توضیحات آرشیوهای فاقد متن اختصاصی بر اساس نام همان دسته، برچسب یا صنعت ساخته می‌شوند؛ اطلاعات اختصاصی کارشناس سئو اولویت دارد.
- تصویر اصلی خدمت در صفحهٔ جزئیات، اطلاعات اشتراک‌گذاری، اسکیما و نقشهٔ سایت استفاده می‌شود. فایل دوباره فشرده، تبدیل یا تکثیر نمی‌شود.
- بخش‌ها و آیتم‌های مخفی پروژه از نمایش و متن اسکیما حذف می‌شوند. FAQ، اعضای تیم و دیدگاه‌ها فقط وقتی بخش مربوط به آن‌ها نمایش داده می‌شود در اسکیما قرار می‌گیرند.
- رندر جدید PHP نیز متاتگ‌های زبان را دریافت می‌کند. خطاهای بدون سشن و پاسخ مرورگرهای بازمانده از نسخهٔ قبلی بررسی شده‌اند.

اعتبارسنجی: ۳۲ آدرس نسخهٔ محلی از نقشهٔ سایت بررسی شدند؛ همه پاسخ ۲۰۰، عنوان و توضیحات داشتند و JSON-LD آن‌ها قابل‌خواندن بود. خانه و جزئیات خدمت با JavaScript خاموش، در عرض‌های ۳۹۰ و ۱۴۴۰ پیکسل بررسی شدند و سرریز افقی نداشتند. ۱۵ تست در `NativeSeoOutputTest` و `SeoAuditRegressionTest` خروجی جدید و اصلاحات این بازبینی را پوشش می‌دهند. تست‌های قدیمیِ وابسته به پاسخ JSON و پوستهٔ Inertia هم‌زمان با مهاجرت رندر صفحات در حال به‌روزرسانی‌اند؛ نتیجهٔ آن‌ها با تست‌های خروجی جدید یکی نیست. این بررسی معادل امتیاز جدید PageSpeed یا تأیید ایندکس در Search Console نیست.

این تغییرها هنوز روی هاست نصب نشده‌اند. پس از انتشار، در «تنظیمات عملکرد» کش صفحه‌ها پاک شود. در خروجی مشاهده‌شدهٔ سایت زنده، خط `Sitemap:` در `robots.txt` نسبی بود؛ پس از انتشار باید آدرس کامل `https://sizdahmarketing.com/sitemap.xml` دیده شود. اگر خروجی قدیمی باقی ماند، فایل ثابت `public_html/robots.txt` و کش هاست/CDN بررسی شوند؛ کد مسیر Laravel خروجی مطلق تولید می‌کند.

مراجع: [تصاویر در جستجوی گوگل](https://developers.google.com/search/docs/appearance/google-images)، [نسخه‌های زبانی](https://developers.google.com/search/docs/specialty/international/localized-versions)، [رندر JavaScript و سئو](https://developers.google.com/search/docs/crawling-indexing/javascript/javascript-seo-basics).

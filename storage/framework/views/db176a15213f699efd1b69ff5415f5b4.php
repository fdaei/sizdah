
<?php
    $card = 'border:1px solid rgba(156,163,175,.35); border-radius:12px; padding:20px; margin-bottom:16px';
    $h = 'font-size:17px; font-weight:700; margin:0 0 10px';
    $p = 'margin:0 0 8px; line-height:1.9';
    $tag = 'display:inline-block; border-radius:6px; padding:1px 8px; font-size:12px; background:rgba(156,163,175,.18); margin-inline-end:4px';
    $dot = fn ($c) => "display:inline-block; width:10px; height:10px; border-radius:50%; background:$c; margin-inline-end:6px";
?>
<?php if (isset($component)) { $__componentOriginal166a02a7c5ef5a9331faf66fa665c256 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal166a02a7c5ef5a9331faf66fa665c256 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-panels::components.page.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-panels::page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<div dir="rtl" style="max-width:900px; font-size:14px">

    <div style="<?php echo e($card); ?>; background:rgba(37,99,235,.06)">
        <p style="<?php echo e($p); ?>"><b>اگر با Yoast یا Rank Math در وردپرس کار کرده‌اید، همه‌چیز آشناست.</b>
            پایین فرم ویرایش هر محتوا (مقاله، صفحه، خدمت، پروژه، دسته‌بندی، برچسب، صنعت، عضو تیم) یک باکس
            <b>«سئو (Yoast SEO)»</b> هست — دقیقاً جای متاباکس Yoast. همه‌ی برچسب‌ها فارسی‌اند و اصطلاح انگلیسی آشنا داخل پرانتز آمده.</p>
        <p style="<?php echo e($p); ?>">تحلیل‌ها زنده‌اند: هر چیزی تایپ کنید، چند لحظه بعد امتیاز و پیشنهادها به‌روز می‌شوند. امتیاز نهایی بعد از <b>ذخیره (Save)</b> در لیست هم نمایش داده می‌شود.</p>
    </div>

    <div style="<?php echo e($card); ?>">
        <h2 style="<?php echo e($h); ?>">رنگ‌ها چه معنی دارند؟</h2>
        <p style="<?php echo e($p); ?>"><span style="<?php echo e($dot('#16a34a')); ?>"></span><b>سبز (۷۰ به بالا)</b> — خوب است، کاری لازم نیست.</p>
        <p style="<?php echo e($p); ?>"><span style="<?php echo e($dot('#ea580c')); ?>"></span><b>نارنجی (۴۰ تا ۶۹)</b> — قابل قبول، ولی بهتر می‌شود.</p>
        <p style="<?php echo e($p); ?>"><span style="<?php echo e($dot('#dc2626')); ?>"></span><b>قرمز (زیر ۴۰)</b> — مشکل دارد؛ موارد «مشکلات (Problems)» را اول درست کنید.</p>
        <p style="<?php echo e($p); ?>">هدف ۱۰۰ نیست؛ مثل Yoast، سبز بودن کافی است. متن را برای خواننده بنویسید، نه برای امتیاز.</p>
    </div>

    <div style="<?php echo e($card); ?>">
        <h2 style="<?php echo e($h); ?>">۱. تب «سئو (SEO)» — همان Snippet editor</h2>
        <p style="<?php echo e($p); ?>"><b>پیش‌نمایش گوگل (Google preview):</b> نتیجه‌ی جستجو همان‌طور که گوگل نشان می‌دهد؛ دکمه‌ی دسکتاپ/موبایل را بزنید. دو نوار زیرش طول عنوان (حداکثر ۵۸۰ پیکسل ≈ ۶۰ نویسه) و توضیحات (حداکثر ۹۲۰ پیکسل ≈ ۱۵۶ نویسه) را نشان می‌دهند.</p>
        <p style="<?php echo e($p); ?>"><b>عنوان سئو (SEO title):</b> خالی بماند = قالب پیش‌فرض <span dir="ltr" style="<?php echo e($tag); ?>">%title% %sep% %sitename%</span> یعنی «عنوان محتوا — سیزده». متغیرها مثل Yoast کار می‌کنند:
            <span dir="ltr" style="<?php echo e($tag); ?>">%title%</span> عنوان محتوا،
            <span dir="ltr" style="<?php echo e($tag); ?>">%sep%</span> جداکننده،
            <span dir="ltr" style="<?php echo e($tag); ?>">%sitename%</span> نام سایت،
            <span dir="ltr" style="<?php echo e($tag); ?>">%tagline%</span> شعار،
            <span dir="ltr" style="<?php echo e($tag); ?>">%page%</span> شماره صفحه.</p>
        <p style="<?php echo e($p); ?>"><b>نامک (Slug):</b> بخش آخر آدرس. حروف فارسی مجاز است؛ فاصله‌ها خودکار خط‌تیره می‌شوند و تکراری بودن بررسی می‌شود. <b>بعد از انتشار، نامک را بی‌دلیل عوض نکنید</b> — لینک‌های قبلی و رتبه از دست می‌رود.</p>
        <p style="<?php echo e($p); ?>"><b>توضیحات متا (Meta description):</b> ۱۲۰ تا ۱۵۶ نویسه، شامل کلمه کلیدی، با دعوت به اقدام. خالی بماند = خلاصه‌ی محتوا استفاده می‌شود.</p>
        <p style="<?php echo e($p); ?>"><b>کلمه کلیدی کانونی (Focus keyphrase):</b> عبارت اصلی که می‌خواهید با آن دیده شوید. <b>بدون آن امتیاز سئو محاسبه نمی‌شود.</b> هر کلمه کلیدی را فقط برای یک محتوا استفاده کنید (رقابت با خودتان = Keyword cannibalization).</p>
        <p style="<?php echo e($p); ?>"><b>کلمات کلیدی مرتبط (Related keyphrases):</b> مترادف‌ها و عبارات فرعی؛ هر کدام را تایپ و Enter بزنید. (در Yoast این امکان نسخه‌ی پولی است.)</p>
        <p style="<?php echo e($p); ?>"><b>محتوای ستون (Cornerstone content):</b> برای مهم‌ترین و جامع‌ترین مقالات سایت روشن کنید؛ حداقل طول متن از ۳۰۰ به ۹۰۰ کلمه می‌رسد.</p>
    </div>

    <div style="<?php echo e($card); ?>">
        <h2 style="<?php echo e($h); ?>">بررسی‌های تحلیل سئو (SEO analysis)</h2>
        <p style="<?php echo e($p); ?>">همان چک‌های Yoast: کلمه کلیدی در عنوان (بهتر: ابتدای عنوان)، در پاراگراف اول، در توضیحات متا، در نامک، در زیرعنوان‌ها (H2/H3)، چگالی ۰٫۵٪ تا ۳٪، تکراری نبودن، طول متن، لینک داخلی، لینک خروجی، alt تصاویر، طول عنوان و طول توضیحات.</p>
        <p style="<?php echo e($p); ?>"><b>فارسی را درست می‌فهمد:</b> «ي/ك» عربی با «ی/ک» فارسی یکی حساب می‌شود، اعراب نادیده گرفته می‌شود و نیم‌فاصله با فاصله یکی است؛ پس «بازاریابی‌دیجیتال» و «بازاریابی دیجیتال» یک کلمه کلیدی‌اند.</p>
    </div>

    <div style="<?php echo e($card); ?>">
        <h2 style="<?php echo e($h); ?>">۲. تب «خوانایی (Readability)»</h2>
        <p style="<?php echo e($p); ?>">طول جمله‌ها (حداکثر ۲۵٪ بیش از ۲۰ کلمه)، طول پاراگراف (حداکثر ۱۵۰ کلمه)، توزیع زیرعنوان (هر ۳۰۰ کلمه یک زیرعنوان)، کلمات ربط فارسی (مثل «بنابراین، همچنین، اما، در نتیجه» — حداقل ۳۰٪ جمله‌ها) و شروع تکراری جمله‌ها.</p>
        <p style="<?php echo e($p); ?>">چک «جمله‌ی مجهول (Passive voice)» Yoast عمداً حذف شده؛ تشخیص آن در فارسی قابل اعتماد نیست.</p>
    </div>

    <div style="<?php echo e($card); ?>">
        <h2 style="<?php echo e($h); ?>">۳. تب «شبکه‌های اجتماعی (Social)»</h2>
        <p style="<?php echo e($p); ?>">ظاهر لینک در فیسبوک، لینکدین، تلگرام، واتساپ (Open Graph) و ایکس. همه اختیاری‌اند: خالی = عنوان و توضیحات سئو. اگر عنوان ایکس خالی باشد، عنوان فیسبوک استفاده می‌شود. تصویر پیشنهادی ۱۲۰۰×۶۳۰ پیکسل؛ خالی = تصویر شاخص یا تصویر پیش‌فرض سایت.</p>
    </div>

    <div style="<?php echo e($card); ?>">
        <h2 style="<?php echo e($h); ?>">۴. تب «اسکیما (Schema)»</h2>
        <p style="<?php echo e($p); ?>">داده‌ی ساختاریافته برای ریچ‌اسنیپت گوگل. پیش‌فرض‌ها خودکار درست‌اند (مقاله = BlogPosting، درباره ما = AboutPage، تماس = ContactPage، خدمت = Service، پروژه = CreativeWork)؛ فقط اگر دلیل دارید عوض کنید.</p>
        <p style="<?php echo e($p); ?>"><b>اسکیمای سفارشی (Custom JSON-LD):</b> برای حرفه‌ای‌ها. فقط JSON بنویسید، بدون تگ <span dir="ltr" style="<?php echo e($tag); ?>">&lt;script&gt;</span>. JSON نامعتبر هنگام ذخیره خطا می‌دهد و هرگز صفحه را خراب نمی‌کند. بعد از انتشار با <a href="https://search.google.com/test/rich-results" target="_blank" rel="noopener" style="text-decoration:underline">Rich Results Test</a> بررسی کنید.</p>
    </div>

    <div style="<?php echo e($card); ?>">
        <h2 style="<?php echo e($h); ?>">۵. تب «پیشرفته (Advanced)» — با احتیاط</h2>
        <p style="<?php echo e($p); ?>"><b>نمایش در نتایج جستجو (index / noindex):</b> «خیر» یعنی گوگل این صفحه را ایندکس نکند. برای صفحات تشکر، صفحات کم‌ارزش و تکراری. <b>اشتباه در این گزینه صفحه را از گوگل حذف می‌کند.</b></p>
        <p style="<?php echo e($p); ?>"><b>دنبال کردن لینک‌ها (follow / nofollow):</b> تقریباً همیشه پیش‌فرض بماند.</p>
        <p style="<?php echo e($p); ?>"><b>روبات پیشرفته:</b> noarchive، nosnippet، noimageindex و max-snippet / max-image-preview / max-video-preview — همان گزینه‌های Rank Math.</p>
        <p style="<?php echo e($p); ?>"><b>آدرس کنونیکال (Canonical URL):</b> خالی = خود صفحه (درست برای ۹۹٪ موارد). فقط وقتی محتوا تکراریِ صفحه‌ی دیگری است، آدرس کامل آن صفحه را بگذارید. پارامترهای <span dir="ltr" style="<?php echo e($tag); ?>">utm_*</span> خودکار از کنونیکال حذف می‌شوند.</p>
        <p style="<?php echo e($p); ?>"><b>عنوان بردکرامب (Breadcrumbs title):</b> عنوان کوتاه‌تر برای مسیر راهنما در گوگل.</p>
        <p style="<?php echo e($p); ?>"><b>نقشه سایت (Sitemap):</b> حذف از نقشه سایت، اولویت و تناوب تغییر. خالی = خودکار.</p>
    </div>

    <div style="<?php echo e($card); ?>">
        <h2 style="<?php echo e($h); ?>">لیست‌ها: پیدا کردن محتوای ضعیف</h2>
        <p style="<?php echo e($p); ?>">در لیست مقالات، صفحات و… دو ستون <b>سئو</b> و <b>خوانایی</b> هست (اگر دیده نمی‌شوند، از آیکون ستون‌ها بالای جدول روشنشان کنید). با <b>فیلتر</b> «امتیاز سئو ← ضعیف» یا «بدون کلمه کلیدی» سریع محتوایی را که کار لازم دارد پیدا کنید.</p>
    </div>

    <div style="<?php echo e($card); ?>">
        <h2 style="<?php echo e($h); ?>">تفاوت‌ها با وردپرس</h2>
        <p style="<?php echo e($p); ?>">• سایت فعلاً فقط فارسی است؛ اگر زبان دیگری فعال شود، داخل باکس سئو برای هر زبان یک زبانه‌ی جدا ظاهر می‌شود.</p>
        <p style="<?php echo e($p); ?>">• صفحات «دسته‌بندی، برچسب، صنعت و عضو تیم» هنوز صفحه‌ی عمومی ندارند؛ تنظیمات سئوی آن‌ها ذخیره می‌شود و با ساخته شدن آن صفحات اعمال خواهد شد.</p>
        <p style="<?php echo e($p); ?>">• «نامک» خدمات، لنگر داخل صفحه‌ی خدمات است (<span dir="ltr" style="<?php echo e($tag); ?>">/services#…</span>) و در فرم اصلی خدمت قرار دارد، نه در باکس سئو.</p>
        <p style="<?php echo e($p); ?>">• تنظیمات کلی سئو (قالب عنوان، جداکننده، Knowledge Graph، کدهای تأیید گوگل و…)، ریدایرکت‌ها و نقشه سایت در مراحل بعدی به همین منو اضافه می‌شوند.</p>
    </div>

    <div style="<?php echo e($card); ?>; background:rgba(22,163,74,.06)">
        <h2 style="<?php echo e($h); ?>">چک‌لیست سریع قبل از انتشار</h2>
        <p style="<?php echo e($p); ?>">☐ کلمه کلیدی کانونی تعیین شد و تکراری نیست<br>
            ☐ کلمه کلیدی در عنوان، پاراگراف اول، نامک و توضیحات متا آمده<br>
            ☐ نوار عنوان و توضیحات در پیش‌نمایش گوگل سبز است<br>
            ☐ حداقل یک لینک داخلی و همه‌ی تصاویر alt دارند<br>
            ☐ امتیاز سئو و خوانایی سبز یا نارنجی است<br>
            ☐ در تب پیشرفته چیزی بی‌دلیل روی noindex نیست</p>
    </div>
</div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal166a02a7c5ef5a9331faf66fa665c256)): ?>
<?php $attributes = $__attributesOriginal166a02a7c5ef5a9331faf66fa665c256; ?>
<?php unset($__attributesOriginal166a02a7c5ef5a9331faf66fa665c256); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal166a02a7c5ef5a9331faf66fa665c256)): ?>
<?php $component = $__componentOriginal166a02a7c5ef5a9331faf66fa665c256; ?>
<?php unset($__componentOriginal166a02a7c5ef5a9331faf66fa665c256); ?>
<?php endif; ?>
<?php /**PATH /home/fdaei/Downloads/sizdah/resources/views/filament/pages/seo-guide.blade.php ENDPATH**/ ?>
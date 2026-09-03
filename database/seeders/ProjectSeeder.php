<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PublicationStatus;
use App\Enums\SectionType;
use App\Models\Industry;
use App\Models\PageSection;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Database\Seeder;

/**
 * The six case studies from the Figma projects grid (1362:7198). Cheshmeh is
 * the one project the Figma file expands in full (case study 336:5374) — its
 * copy is transcribed verbatim from the frame. The other five only ever had a
 * title/cover/excerpt, so their case-study pages (goals, strategy,
 * deliverables, results) rendered empty; every field below marked
 * "placeholder" is authored content filling that gap, not sourced from
 * Figma, and should be swapped for real client copy when available. Their
 * `showcase` reuses each project's own cover image (no separate showcase
 * photography exists for them) and they carry no before/after pair for the
 * same reason — the section simply does not render without one.
 */
final class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $industries = Industry::query()->withTranslations()->get()->keyBy(
            fn (Industry $i): string => (string) $i->getTranslation('slug'),
        );

        $projects = [
            [
                'slug' => 'baghche-branding', 'industry' => 'غذا-و-نوشیدنی', 'year' => '2024',
                'instagram' => 'baghche', 'cover' => 'projects/baghche.webp',
                'en' => [
                    'title' => 'Baghche', 'subtitle' => 'Healthy food, honestly presented',
                    'excerpt' => 'Branding and content support for a healthy food brand focused on freshness, trust, and everyday convenience.',
                    'challenge' => 'Baghche made a genuinely good product, but its social content did not clearly communicate the freshness and quality behind it.',
                    'challenge_points' => ['No consistent visual identity', 'Scattered, low-rhythm content', 'Weak trust signals for new customers'],
                    'results_summary' => 'The project gave Baghche a more consistent visual identity and a steadier publishing rhythm, growing audience trust and engagement.',
                ],
                'fa' => [
                    'title' => 'باغچه', 'subtitle' => 'غذای سالم، صادقانه ارائه‌شده',
                    'excerpt' => 'پشتیبانی برندینگ و محتوا برای برند غذای سالم با تمرکز بر تازگی، اعتماد و راحتی روزمره.',
                    'challenge' => 'باغچه محصولی واقعاً باکیفیت داشت، اما محتوای شبکه‌های اجتماعی‌اش تازگی و کیفیت پشت آن را به‌روشنی منتقل نمی‌کرد.',
                    'challenge_points' => ['نبود هویت بصری یکدست', 'محتوای پراکنده و بی‌ریتم', 'اعتمادسازی ضعیف نزد مشتری تازه'],
                    'results_summary' => 'این پروژه به باغچه هویت بصری یکدست‌تر و ریتم انتشار منظم‌تری داد و اعتماد و تعامل مخاطبان را افزایش داد.',
                ],
                'ar' => [
                    'title' => 'باغتشه', 'subtitle' => 'طعام صحي بعرض صادق',
                    'excerpt' => 'دعم الهوية والمحتوى لعلامة أطعمة صحية تركز على النضارة والثقة والراحة اليومية.',
                    'challenge' => 'كان لدى باغتشه منتج جيد فعلاً، لكن محتوى التواصل الاجتماعي لم يعكس بوضوح النضارة والجودة وراءه.',
                    'challenge_points' => ['لا هوية بصرية متسقة', 'محتوى متناثر بلا إيقاع', 'إشارات ثقة ضعيفة للعملاء الجدد'],
                    'results_summary' => 'منح المشروع باغتشه هوية بصرية أكثر اتساقاً وإيقاع نشر أكثر انتظاماً، مما رفع ثقة الجمهور وتفاعله.',
                ],
                'goals' => [
                    ['en' => ['title' => 'Trusted Identity', 'description' => 'A brand people feel confident in'], 'fa' => ['title' => 'هویت قابل‌اعتماد', 'description' => 'برندی که مخاطب به آن اطمینان کند'], 'ar' => ['title' => 'هوية موثوقة', 'description' => 'علامة يثق بها الجمهور']],
                    ['en' => ['title' => 'Healthy Visual Language', 'description' => 'Imagery that reads as fresh'], 'fa' => ['title' => 'زبان بصری سالم', 'description' => 'تصویری که تازگی را منتقل کند'], 'ar' => ['title' => 'لغة بصرية صحية', 'description' => 'صور تعكس النضارة']],
                    ['en' => ['title' => 'Organized Content', 'description' => 'A steady, purposeful rhythm'], 'fa' => ['title' => 'محتوای منظم', 'description' => 'ریتم انتشار ثابت و هدفمند'], 'ar' => ['title' => 'محتوى منظم', 'description' => 'إيقاع نشر ثابت وهادف']],
                    ['en' => ['title' => 'More Engagement', 'description' => 'A closer bond with the audience'], 'fa' => ['title' => 'تعامل بیشتر', 'description' => 'ارتباط نزدیک‌تر با مخاطب'], 'ar' => ['title' => 'تفاعل أكبر', 'description' => 'ارتباط أقرب بالجمهور']],
                ],
                'strategy' => [
                    ['en' => ['title' => 'Visual System', 'description' => 'A color palette and product-photography templates built around freshness and natural texture.'], 'fa' => ['title' => 'سیستم بصری', 'description' => 'پالت رنگی و قالب‌های عکاسی محصول با محوریت تازگی و طبیعی‌بودن طراحی شد.'], 'ar' => ['title' => 'النظام البصري', 'description' => 'صُممت لوحة ألوان وقوالب تصوير للمنتج تركز على النضارة والملمس الطبيعي.']],
                    ['en' => ['title' => 'Content Direction', 'description' => 'Content was organized around ingredient quality, production transparency, and everyday healthy living.'], 'fa' => ['title' => 'جهت‌گیری محتوا', 'description' => 'محتوا حول کیفیت مواد اولیه، شفافیت تولید و سبک زندگی سالم سازمان یافت.'], 'ar' => ['title' => 'توجيه المحتوى', 'description' => 'نُظّم المحتوى حول جودة المكونات وشفافية الإنتاج ونمط الحياة الصحي اليومي.']],
                    ['en' => ['title' => 'Publishing Flow', 'description' => 'A regular posting calendar was set for feed posts and stories.'], 'fa' => ['title' => 'جریان انتشار', 'description' => 'تقویم انتشار منظمی برای پست‌ها و استوری‌ها تدوین شد.'], 'ar' => ['title' => 'تدفق النشر', 'description' => 'وُضع تقويم نشر منتظم للمنشورات والقصص.']],
                ],
                'deliverables' => [
                    ['en' => ['title' => 'Brand Direction', 'description' => 'A clearer visual and verbal direction for the brand.'], 'fa' => ['title' => 'جهت‌گیری برند', 'description' => 'جهت بصری و کلامی شفاف‌تر برای برند.'], 'ar' => ['title' => 'توجيه العلامة', 'description' => 'اتجاه بصري ولفظي أوضح للعلامة.']],
                    ['en' => ['title' => 'Visual System', 'description' => 'Reusable layouts and photography rules for social media.'], 'fa' => ['title' => 'سیستم بصری', 'description' => 'قالب‌ها و قواعد عکاسی قابل‌استفاده مجدد برای شبکه‌های اجتماعی.'], 'ar' => ['title' => 'النظام البصري', 'description' => 'تخطيطات وقواعد تصوير قابلة لإعادة الاستخدام لوسائل التواصل.']],
                    ['en' => ['title' => 'Content Calendar', 'description' => 'A structured monthly content plan.'], 'fa' => ['title' => 'تقویم محتوا', 'description' => 'برنامه ماهانه ساختارمند محتوا.'], 'ar' => ['title' => 'تقويم المحتوى', 'description' => 'خطة محتوى شهرية منظمة.']],
                    ['en' => ['title' => 'Story Templates', 'description' => 'Flexible story formats for product highlights and engagement.'], 'fa' => ['title' => 'قالب‌های استوری', 'description' => 'فرمت‌های انعطاف‌پذیر استوری برای معرفی محصول و تعامل.'], 'ar' => ['title' => 'قوالب القصص', 'description' => 'صيغ قصص مرنة لإبراز المنتج والتفاعل.']],
                ],
                'results' => [
                    ['en' => ['value' => '+98%', 'title' => 'View'], 'fa' => ['value' => '+۹۸٪', 'title' => 'بازدید'], 'ar' => ['value' => '+٩٨٪', 'title' => 'المشاهدات']],
                    ['en' => ['value' => '+126%', 'title' => 'Interaction'], 'fa' => ['value' => '+۱۲۶٪', 'title' => 'تعامل'], 'ar' => ['value' => '+١٢٦٪', 'title' => 'التفاعل']],
                    ['en' => ['value' => '+64%', 'title' => 'Follower'], 'fa' => ['value' => '+۶۴٪', 'title' => 'دنبال‌کننده'], 'ar' => ['value' => '+٦٤٪', 'title' => 'المتابعون']],
                    ['en' => ['value' => '+85%', 'title' => 'Reach'], 'fa' => ['value' => '+۸۵٪', 'title' => 'دسترسی'], 'ar' => ['value' => '+٨٥٪', 'title' => 'الوصول']],
                ],
            ],
            [
                'slug' => 'kerman-motors', 'industry' => 'خودرو', 'year' => '2024',
                'instagram' => null, 'cover' => 'projects/kerman-motors.webp',
                'en' => [
                    'title' => 'Kerman Motors', 'subtitle' => 'Factory-price sale campaign',
                    'excerpt' => 'Campaign visuals and promotional creative for a factory-price sales launch.',
                    'challenge' => 'Kerman Motors needed a strong, structured campaign that could grab attention fast, inside a short sales window and a crowded automotive market.',
                    'challenge_points' => ['No cohesive campaign visuals', 'A tight launch timeline', 'Heavy competition in the auto market'],
                    'results_summary' => 'The campaign helped Kerman Motors capture wider attention in a short sales window and increased showroom visits.',
                ],
                'fa' => [
                    'title' => 'کرمان موتورز', 'subtitle' => 'کمپین فروش با قیمت کارخانه',
                    'excerpt' => 'بصری‌های کمپین و خلاقیت تبلیغاتی برای راه‌اندازی فروش با قیمت کارخانه.',
                    'challenge' => 'کرمان موتورز به کمپینی قدرتمند و ساختاریافته نیاز داشت که در بازه فروش کوتاه و بازار شلوغ خودرو، توجه مخاطب را سریع جلب کند.',
                    'challenge_points' => ['نبود بصری‌های کمپین یکدست', 'بازه زمانی فشرده راه‌اندازی', 'رقابت سنگین در بازار خودرو'],
                    'results_summary' => 'کمپین به کرمان موتورز کمک کرد در بازه فروش کوتاه، توجه گسترده‌تری جذب کند و مراجعه به نمایندگی افزایش یابد.',
                ],
                'ar' => [
                    'title' => 'كرمان موتورز', 'subtitle' => 'حملة بيع بسعر المصنع',
                    'excerpt' => 'مرئيات حملة وإبداع ترويجي لإطلاق بيع بسعر المصنع.',
                    'challenge' => 'احتاجت كرمان موتورز إلى حملة قوية ومنظمة تجذب الانتباه بسرعة، ضمن نافذة بيع قصيرة وسوق سيارات مزدحم.',
                    'challenge_points' => ['لا مرئيات حملة متماسكة', 'جدول زمني ضيق للإطلاق', 'منافسة شديدة في سوق السيارات'],
                    'results_summary' => 'ساعدت الحملة كرمان موتورز على جذب اهتمام أوسع خلال نافذة بيع قصيرة وزيادة الزيارات لصالة العرض.',
                ],
                'goals' => [
                    ['en' => ['title' => 'High-Impact Campaign', 'description' => 'Immediate attention from the target audience'], 'fa' => ['title' => 'کمپین اثرگذار', 'description' => 'جلب توجه فوری مخاطب هدف'], 'ar' => ['title' => 'حملة عالية التأثير', 'description' => 'جذب انتباه فوري للجمهور المستهدف']],
                    ['en' => ['title' => 'Clear Message', 'description' => 'The price advantage, communicated plainly'], 'fa' => ['title' => 'پیام شفاف', 'description' => 'انتقال روشن مزیت قیمتی'], 'ar' => ['title' => 'رسالة واضحة', 'description' => 'إيصال واضح لميزة السعر']],
                    ['en' => ['title' => 'Strong Visual Identity', 'description' => 'A sense of trust and authority'], 'fa' => ['title' => 'هویت بصری قدرتمند', 'description' => 'ایجاد حس اعتماد و اقتدار برند'], 'ar' => ['title' => 'هوية بصرية قوية', 'description' => 'خلق إحساس بالثقة والقوة']],
                    ['en' => ['title' => 'Higher Conversion', 'description' => 'More visits to the showroom'], 'fa' => ['title' => 'نرخ تبدیل بالاتر', 'description' => 'افزایش مراجعه به نمایندگی'], 'ar' => ['title' => 'تحويل أعلى', 'description' => 'زيادة الزيارات لصالة العرض']],
                ],
                'strategy' => [
                    ['en' => ['title' => 'Campaign Visual System', 'description' => 'Visual templates were built around the brand color and bold typography.'], 'fa' => ['title' => 'سیستم بصری کمپین', 'description' => 'قالب‌های بصری با تاکید بر رنگ برند و تایپوگرافی قدرتمند طراحی شد.'], 'ar' => ['title' => 'النظام البصري للحملة', 'description' => 'صُممت قوالب بصرية تركز على لون العلامة والطباعة القوية.']],
                    ['en' => ['title' => 'Focused Messaging', 'description' => 'The core message centered on the factory price and the time-limited offer.'], 'fa' => ['title' => 'پیام‌رسانی متمرکز', 'description' => 'پیام اصلی کمپین حول مزیت قیمت کارخانه و محدودیت زمانی شکل گرفت.'], 'ar' => ['title' => 'رسائل مركزة', 'description' => 'تمحورت الرسالة الأساسية حول سعر المصنع والعرض محدود الوقت.']],
                    ['en' => ['title' => 'Multi-Channel Rollout', 'description' => 'Content was coordinated across posts, stories, and paid ads.'], 'fa' => ['title' => 'توزیع چندکاناله', 'description' => 'محتوا برای پست، استوری و تبلیغات پولی هماهنگ‌سازی شد.'], 'ar' => ['title' => 'توزيع متعدد القنوات', 'description' => 'تم تنسيق المحتوى عبر المنشورات والقصص والإعلانات المدفوعة.']],
                ],
                'deliverables' => [
                    ['en' => ['title' => 'Campaign Identity', 'description' => 'A dedicated visual identity for the sale campaign.'], 'fa' => ['title' => 'هویت بصری کمپین', 'description' => 'هویت بصری اختصاصی برای کمپین فروش.'], 'ar' => ['title' => 'هوية الحملة', 'description' => 'هوية بصرية مخصصة لحملة البيع.']],
                    ['en' => ['title' => 'Promotional Banners', 'description' => 'Ad-ready banners across formats and channels.'], 'fa' => ['title' => 'بنرهای تبلیغاتی', 'description' => 'بنرهای آماده تبلیغ در قالب‌ها و کانال‌های مختلف.'], 'ar' => ['title' => 'لافتات ترويجية', 'description' => 'لافتات جاهزة للإعلان عبر التنسيقات والقنوات.']],
                    ['en' => ['title' => 'Social Content', 'description' => 'A coordinated set of posts and stories for the launch window.'], 'fa' => ['title' => 'محتوای شبکه‌های اجتماعی', 'description' => 'مجموعه هماهنگ پست و استوری برای بازه راه‌اندازی.'], 'ar' => ['title' => 'محتوى التواصل الاجتماعي', 'description' => 'مجموعة منسقة من المنشورات والقصص لنافذة الإطلاق.']],
                    ['en' => ['title' => 'Distribution Strategy', 'description' => 'A plan for where and when each asset runs.'], 'fa' => ['title' => 'استراتژی توزیع', 'description' => 'برنامه‌ای برای زمان و کانال انتشار هر دارایی.'], 'ar' => ['title' => 'استراتيجية التوزيع', 'description' => 'خطة لتوقيت ومكان نشر كل عنصر.']],
                ],
                'results' => [
                    ['en' => ['value' => '+142%', 'title' => 'View'], 'fa' => ['value' => '+۱۴۲٪', 'title' => 'بازدید'], 'ar' => ['value' => '+١٤٢٪', 'title' => 'المشاهدات']],
                    ['en' => ['value' => '+96%', 'title' => 'Interaction'], 'fa' => ['value' => '+۹۶٪', 'title' => 'تعامل'], 'ar' => ['value' => '+٩٦٪', 'title' => 'التفاعل']],
                    ['en' => ['value' => '+58%', 'title' => 'Follower'], 'fa' => ['value' => '+۵۸٪', 'title' => 'دنبال‌کننده'], 'ar' => ['value' => '+٥٨٪', 'title' => 'المتابعون']],
                    ['en' => ['value' => '+110%', 'title' => 'Reach'], 'fa' => ['value' => '+۱۱۰٪', 'title' => 'دسترسی'], 'ar' => ['value' => '+١١٠٪', 'title' => 'الوصول']],
                ],
            ],
            [
                'slug' => 'fakhar-clinic', 'industry' => 'سلامت', 'year' => '2024',
                'instagram' => 'fakharclinic', 'cover' => 'projects/fakhar-clinic.webp',
                'en' => [
                    'title' => 'Fakhar Medical & Dental Clinic', 'subtitle' => 'Comprehensive care, one clear voice',
                    'excerpt' => 'Brand direction and content for a comprehensive medical services provider.',
                    'challenge' => 'Fakhar Clinic offered a wide range of services, but its digital presence had not communicated that range with the professional, trustworthy tone patients expect.',
                    'challenge_points' => ['Scattered messaging across departments', 'No consistent professional tone', 'Limited trust built online'],
                    'results_summary' => 'The project gave Fakhar Clinic one consistent professional voice online, building more trust and a closer connection with patients.',
                ],
                'fa' => [
                    'title' => 'کلینیک پزشکی و دندانپزشکی فخر', 'subtitle' => 'مراقبت جامع، یک صدای شفاف',
                    'excerpt' => 'جهت‌گیری برند و محتوا برای ارائه‌دهنده خدمات پزشکی جامع.',
                    'challenge' => 'کلینیک فخر خدمات پزشکی متنوعی ارائه می‌داد، اما حضور دیجیتالش این تنوع را با لحن حرفه‌ای و قابل‌اعتمادی که بیماران انتظار دارند منعکس نمی‌کرد.',
                    'challenge_points' => ['پیام‌رسانی پراکنده میان بخش‌ها', 'نبود لحن یکدست و حرفه‌ای', 'اعتمادسازی محدود در فضای آنلاین'],
                    'results_summary' => 'این پروژه به کلینیک فخر یک صدای یکدست و حرفه‌ای در فضای دیجیتال داد و اعتماد و ارتباط بیماران را افزایش داد.',
                ],
                'ar' => [
                    'title' => 'عيادة فخر الطبية وطب الأسنان', 'subtitle' => 'رعاية شاملة بصوت واحد واضح',
                    'excerpt' => 'توجيه العلامة والمحتوى لمزود خدمات طبية شاملة.',
                    'challenge' => 'قدمت عيادة فخر خدمات طبية متنوعة، لكن حضورها الرقمي لم يعكس هذا التنوع بالنبرة المهنية والموثوقة التي يتوقعها المرضى.',
                    'challenge_points' => ['رسائل متناثرة بين الأقسام', 'لا نبرة مهنية متسقة', 'ثقة محدودة بُنيت عبر الإنترنت'],
                    'results_summary' => 'منح المشروع عيادة فخر صوتاً مهنياً متسقاً عبر الإنترنت، مما بنى ثقة أكبر وتواصلاً أقرب مع المرضى.',
                ],
                'goals' => [
                    ['en' => ['title' => 'One Voice', 'description' => 'A single professional tone across services'], 'fa' => ['title' => 'صدای واحد', 'description' => 'یک لحن حرفه‌ای برای همه خدمات'], 'ar' => ['title' => 'صوت واحد', 'description' => 'نبرة مهنية واحدة لكل الخدمات']],
                    ['en' => ['title' => 'More Trust', 'description' => 'A sense of safety and expertise'], 'fa' => ['title' => 'اعتماد بیشتر', 'description' => 'حس امنیت و تخصص'], 'ar' => ['title' => 'ثقة أكبر', 'description' => 'إحساس بالأمان والخبرة']],
                    ['en' => ['title' => 'Clear Services', 'description' => 'Every department explained plainly'], 'fa' => ['title' => 'شفافیت خدمات', 'description' => 'معرفی روشن هر بخش درمانی'], 'ar' => ['title' => 'خدمات واضحة', 'description' => 'شرح واضح لكل قسم علاجي']],
                    ['en' => ['title' => 'Human Connection', 'description' => 'A closer bond with patients'], 'fa' => ['title' => 'ارتباط انسانی', 'description' => 'نزدیکی بیشتر به مخاطب'], 'ar' => ['title' => 'تواصل إنساني', 'description' => 'قرب أكبر من الجمهور']],
                ],
                'strategy' => [
                    ['en' => ['title' => 'Visual System', 'description' => 'A calm, professional identity was built around soft colors and legible typography.'], 'fa' => ['title' => 'سیستم بصری', 'description' => 'هویت بصری آرام و حرفه‌ای با تمرکز بر رنگ‌های ملایم و تایپوگرافی خوانا طراحی شد.'], 'ar' => ['title' => 'النظام البصري', 'description' => 'صُممت هوية بصرية هادئة ومهنية بألوان ناعمة وطباعة واضحة.']],
                    ['en' => ['title' => 'Content Direction', 'description' => 'Content was organized around medical expertise, patient experience, and frequent questions.'], 'fa' => ['title' => 'جهت‌گیری محتوا', 'description' => 'محتوا حول تخصص پزشکی، تجربه بیمار و پرسش‌های پرتکرار سازمان یافت.'], 'ar' => ['title' => 'توجيه المحتوى', 'description' => 'نُظّم المحتوى حول الخبرة الطبية وتجربة المريض والأسئلة الشائعة.']],
                    ['en' => ['title' => 'Tone of Voice', 'description' => 'A tone that stays warm yet professional across every message.'], 'fa' => ['title' => 'لحن ارتباطی', 'description' => 'لحنی صمیمی و در عین حال حرفه‌ای برای تمام پیام‌ها تعریف شد.'], 'ar' => ['title' => 'نبرة الخطاب', 'description' => 'حُددت نبرة دافئة ومهنية في آن واحد لكل الرسائل.']],
                ],
                'deliverables' => [
                    ['en' => ['title' => 'Brand Direction', 'description' => 'A clearer direction for how the clinic communicates.'], 'fa' => ['title' => 'جهت‌گیری برند', 'description' => 'جهت شفاف‌تر برای نحوه ارتباط کلینیک.'], 'ar' => ['title' => 'توجيه العلامة', 'description' => 'اتجاه أوضح لكيفية تواصل العيادة.']],
                    ['en' => ['title' => 'Content Strategy', 'description' => 'Content pillars for each medical department.'], 'fa' => ['title' => 'استراتژی محتوا', 'description' => 'ستون‌های محتوا برای هر بخش درمانی.'], 'ar' => ['title' => 'استراتيجية المحتوى', 'description' => 'ركائز محتوى لكل قسم طبي.']],
                    ['en' => ['title' => 'Visual System', 'description' => 'Templates and visual rules for clinic content.'], 'fa' => ['title' => 'سیستم بصری', 'description' => 'قالب‌ها و قواعد بصری برای محتوای کلینیک.'], 'ar' => ['title' => 'النظام البصري', 'description' => 'قوالب وقواعد بصرية لمحتوى العيادة.']],
                    ['en' => ['title' => 'Content Calendar', 'description' => 'A structured monthly content plan.'], 'fa' => ['title' => 'تقویم محتوا', 'description' => 'برنامه ماهانه ساختارمند محتوا.'], 'ar' => ['title' => 'تقويم المحتوى', 'description' => 'خطة محتوى شهرية منظمة.']],
                ],
                'results' => [
                    ['en' => ['value' => '+87%', 'title' => 'View'], 'fa' => ['value' => '+۸۷٪', 'title' => 'بازدید'], 'ar' => ['value' => '+٨٧٪', 'title' => 'المشاهدات']],
                    ['en' => ['value' => '+104%', 'title' => 'Interaction'], 'fa' => ['value' => '+۱۰۴٪', 'title' => 'تعامل'], 'ar' => ['value' => '+١٠٤٪', 'title' => 'التفاعل']],
                    ['en' => ['value' => '+49%', 'title' => 'Follower'], 'fa' => ['value' => '+۴۹٪', 'title' => 'دنبال‌کننده'], 'ar' => ['value' => '+٤٩٪', 'title' => 'المتابعون']],
                    ['en' => ['value' => '+73%', 'title' => 'Reach'], 'fa' => ['value' => '+۷۳٪', 'title' => 'دسترسی'], 'ar' => ['value' => '+٧٣٪', 'title' => 'الوصول']],
                ],
            ],
            [
                'slug' => 'plus-protein', 'industry' => 'غذا-و-نوشیدنی', 'year' => '2023',
                'instagram' => 'plusprotein', 'cover' => 'projects/plus-protein.webp',
                'en' => [
                    'title' => 'Plus Protein', 'subtitle' => 'A mark built for the gym bag',
                    'excerpt' => 'Logo and packaging-facing brand identity for a protein products company.',
                    'challenge' => 'Plus Protein made a genuinely good product, but its mark and packaging could not clearly stand out on a crowded supplement shelf.',
                    'challenge_points' => ['An outdated, low-distinction mark', 'Packaging misaligned with the target audience', 'No recognizable identity at shelf level'],
                    'results_summary' => 'The project gave Plus Protein a distinctive mark and a consistent packaging system, creating a clearer position in a competitive market.',
                ],
                'fa' => [
                    'title' => 'پلاس پروتئین', 'subtitle' => 'نشانی ساخته‌شده برای کیف ورزشی',
                    'excerpt' => 'هویت برند برای لوگو و بسته‌بندی یک شرکت محصولات پروتئینی.',
                    'challenge' => 'پلاس پروتئین محصولی واقعاً باکیفیت داشت، اما نشان و بسته‌بندی برند نتوانسته بود جایگاه آن را در قفسه شلوغ مکمل‌های ورزشی به‌روشنی متمایز کند.',
                    'challenge_points' => ['نشان تجاری قدیمی و کم‌تمایز', 'بسته‌بندی ناهماهنگ با مخاطب هدف', 'نبود هویت بصری قابل تشخیص در قفسه'],
                    'results_summary' => 'این پروژه به پلاس پروتئین نشانی متمایز و سیستم بسته‌بندی یکدست داد و جایگاه روشن‌تری در بازار رقابتی برایش ساخت.',
                ],
                'ar' => [
                    'title' => 'بلس بروتين', 'subtitle' => 'شعار صُمم لحقيبة الرياضة',
                    'excerpt' => 'هوية العلامة للشعار والتغليف لشركة منتجات بروتين.',
                    'challenge' => 'كان لدى بلس بروتين منتج جيد فعلاً، لكن شعار العلامة وتغليفها لم يستطيعا التميّز بوضوح على رف مكملات مزدحم.',
                    'challenge_points' => ['شعار قديم وقليل التميّز', 'تغليف غير متوافق مع الجمهور المستهدف', 'لا هوية بصرية يمكن تمييزها على الرف'],
                    'results_summary' => 'منح المشروع بلس بروتين شعاراً مميزاً ونظام تغليف متسقاً، مما خلق موضعاً أوضح في سوق تنافسي.',
                ],
                'goals' => [
                    ['en' => ['title' => 'Distinctive Mark', 'description' => 'A logo that stands out on shelf'], 'fa' => ['title' => 'نشان متمایز', 'description' => 'لوگویی متمایز برای قفسه'], 'ar' => ['title' => 'شعار مميز', 'description' => 'شعار يبرز على الرف']],
                    ['en' => ['title' => 'Dynamic Identity', 'description' => 'A sense of energy and performance'], 'fa' => ['title' => 'هویت پویا', 'description' => 'حسی از انرژی و عملکرد'], 'ar' => ['title' => 'هوية ديناميكية', 'description' => 'إحساس بالطاقة والأداء']],
                    ['en' => ['title' => 'Unified Packaging', 'description' => 'Consistency across the whole product line'], 'fa' => ['title' => 'بسته‌بندی هماهنگ', 'description' => 'یکدستی در کل خط محصول'], 'ar' => ['title' => 'تغليف موحد', 'description' => 'اتساق عبر خط المنتجات بأكمله']],
                    ['en' => ['title' => 'Clear Market Position', 'description' => 'Standing out in a competitive category'], 'fa' => ['title' => 'جایگاه روشن', 'description' => 'تمایز در بازار رقابتی'], 'ar' => ['title' => 'موضع واضح', 'description' => 'التميز في فئة تنافسية']],
                ],
                'strategy' => [
                    ['en' => ['title' => 'Mark Design', 'description' => 'The logo and wordmark were redesigned around energy and athletic performance.'], 'fa' => ['title' => 'طراحی نشان', 'description' => 'لوگو و نشان تجاری با الهام از انرژی و عملکرد ورزشی بازطراحی شد.'], 'ar' => ['title' => 'تصميم الشعار', 'description' => 'أُعيد تصميم الشعار مستوحى من الطاقة والأداء الرياضي.']],
                    ['en' => ['title' => 'Packaging System', 'description' => 'A consistent visual template was defined for the entire product line.'], 'fa' => ['title' => 'سیستم بسته‌بندی', 'description' => 'قالب بصری یکدستی برای کل خط محصولات تعریف شد.'], 'ar' => ['title' => 'نظام التغليف', 'description' => 'حُدد قالب بصري متسق لكامل خط المنتجات.']],
                    ['en' => ['title' => 'Visual Language', 'description' => 'A color palette and typography suited to an athletic audience were developed.'], 'fa' => ['title' => 'زبان بصری', 'description' => 'پالت رنگی و تایپوگرافی متناسب با مخاطب ورزشکار توسعه یافت.'], 'ar' => ['title' => 'اللغة البصرية', 'description' => 'طُورت لوحة ألوان وطباعة تناسب الجمهور الرياضي.']],
                ],
                'deliverables' => [
                    ['en' => ['title' => 'Logo Design', 'description' => 'A redesigned mark and wordmark.'], 'fa' => ['title' => 'طراحی لوگو', 'description' => 'بازطراحی نشان و لوگوتایپ.'], 'ar' => ['title' => 'تصميم الشعار', 'description' => 'إعادة تصميم الشعار والاسم.']],
                    ['en' => ['title' => 'Packaging System', 'description' => 'A unified packaging template for every product.'], 'fa' => ['title' => 'سیستم بسته‌بندی', 'description' => 'قالب یکدست بسته‌بندی برای هر محصول.'], 'ar' => ['title' => 'نظام التغليف', 'description' => 'قالب تغليف موحد لكل منتج.']],
                    ['en' => ['title' => 'Brand Guide', 'description' => 'A reference for color, type, and usage rules.'], 'fa' => ['title' => 'راهنمای برند', 'description' => 'مرجعی برای رنگ، تایپوگرافی و قواعد استفاده.'], 'ar' => ['title' => 'دليل العلامة', 'description' => 'مرجع للألوان والخطوط وقواعد الاستخدام.']],
                    ['en' => ['title' => 'Social Assets', 'description' => 'Visual assets carrying the new identity to social media.'], 'fa' => ['title' => 'دارایی‌های بصری شبکه‌های اجتماعی', 'description' => 'دارایی‌های بصری برای انتقال هویت جدید به شبکه‌های اجتماعی.'], 'ar' => ['title' => 'أصول التواصل الاجتماعي', 'description' => 'أصول بصرية تنقل الهوية الجديدة إلى وسائل التواصل.']],
                ],
                'results' => [
                    ['en' => ['value' => '+68%', 'title' => 'Brand Recognition'], 'fa' => ['value' => '+۶۸٪', 'title' => 'بازشناسی برند'], 'ar' => ['value' => '+٦٨٪', 'title' => 'التعرف على العلامة']],
                    ['en' => ['value' => '+55%', 'title' => 'Interaction'], 'fa' => ['value' => '+۵۵٪', 'title' => 'تعامل'], 'ar' => ['value' => '+٥٥٪', 'title' => 'التفاعل']],
                    ['en' => ['value' => '+40%', 'title' => 'Follower'], 'fa' => ['value' => '+۴۰٪', 'title' => 'دنبال‌کننده'], 'ar' => ['value' => '+٤٠٪', 'title' => 'المتابعون']],
                    ['en' => ['value' => '+90%', 'title' => 'Visual Consistency'], 'fa' => ['value' => '+۹۰٪', 'title' => 'یکدستی بصری'], 'ar' => ['value' => '+٩٠٪', 'title' => 'الاتساق البصري']],
                ],
            ],
            [
                'slug' => 'karsa-tourism', 'industry' => 'گردشگری', 'year' => '2023',
                'instagram' => 'karsatravel', 'cover' => 'projects/karsa-tourism.webp',
                'en' => [
                    'title' => 'Karsa Tourism Services', 'subtitle' => 'Booking made to feel personal',
                    'excerpt' => 'Branding and campaign visuals for a travel and tourism services company.',
                    'challenge' => 'Karsa offered a wide range of tours, but its digital content read as generic and did not build the trust travelers need before they book.',
                    'challenge_points' => ['Generic, impersonal content', 'No visual storytelling per destination', 'Limited trust built before booking'],
                    'results_summary' => 'The project gave Karsa a more personal storytelling approach, increasing pre-booking trust and engagement with tour content.',
                ],
                'fa' => [
                    'title' => 'خدمات گردشگری کرسا', 'subtitle' => 'رزروی با حس شخصی',
                    'excerpt' => 'برندینگ و بصری‌های کمپین برای شرکت خدمات گردشگری.',
                    'challenge' => 'کرسا تورهای متنوعی ارائه می‌داد، اما محتوای دیجیتالش عمومی به‌نظر می‌رسید و اعتماد لازم برای رزرو را پیش از سفر در مخاطب نمی‌ساخت.',
                    'challenge_points' => ['محتوای عمومی و غیرشخصی', 'نبود روایت بصری برای هر مقصد', 'اعتمادسازی محدود پیش از رزرو'],
                    'results_summary' => 'این پروژه با روایتی شخصی‌تر، اعتماد مخاطب پیش از رزرو و تعامل با محتوای تورها را برای کرسا افزایش داد.',
                ],
                'ar' => [
                    'title' => 'خدمات كارسا السياحية', 'subtitle' => 'حجز يبدو شخصياً',
                    'excerpt' => 'الهوية ومرئيات الحملة لشركة خدمات سياحية وسفر.',
                    'challenge' => 'قدمت كارسا مجموعة واسعة من الرحلات، لكن محتواها الرقمي بدا عاماً ولم يبنِ الثقة التي يحتاجها المسافرون قبل الحجز.',
                    'challenge_points' => ['محتوى عام وغير شخصي', 'لا سرد بصري لكل وجهة', 'ثقة محدودة قبل الحجز'],
                    'results_summary' => 'منح المشروع كارسا سرداً أكثر شخصية، مما رفع ثقة الجمهور قبل الحجز وتفاعله مع محتوى الرحلات.',
                ],
                'goals' => [
                    ['en' => ['title' => 'A More Personal Story', 'description' => 'The real feeling of travel for each viewer'], 'fa' => ['title' => 'روایت شخصی‌تر', 'description' => 'حس واقعی سفر برای هر مخاطب'], 'ar' => ['title' => 'سرد أكثر شخصية', 'description' => 'إحساس حقيقي بالسفر لكل مشاهد']],
                    ['en' => ['title' => 'Trust Before Booking', 'description' => 'Reducing hesitation before the decision'], 'fa' => ['title' => 'اعتماد پیش از رزرو', 'description' => 'کاهش تردید مخاطب پیش از تصمیم'], 'ar' => ['title' => 'ثقة قبل الحجز', 'description' => 'تقليل التردد قبل القرار']],
                    ['en' => ['title' => 'Inspiring Visual Identity', 'description' => 'A picture of the quality of every tour'], 'fa' => ['title' => 'هویت بصری الهام‌بخش', 'description' => 'تصویری از کیفیت هر تور'], 'ar' => ['title' => 'هوية بصرية ملهمة', 'description' => 'صورة عن جودة كل رحلة']],
                    ['en' => ['title' => 'Easier Booking', 'description' => 'A clear path to action'], 'fa' => ['title' => 'رزرو ساده‌تر', 'description' => 'مسیر روشن تا اقدام'], 'ar' => ['title' => 'حجز أسهل', 'description' => 'مسار واضح نحو الإجراء']],
                ],
                'strategy' => [
                    ['en' => ['title' => 'Visual Storytelling', 'description' => 'Each destination was introduced with its own dedicated visual narrative.'], 'fa' => ['title' => 'روایت‌گویی بصری', 'description' => 'هر مقصد با روایت تصویری اختصاصی خودش معرفی شد.'], 'ar' => ['title' => 'السرد البصري', 'description' => 'قُدمت كل وجهة بسردها البصري الخاص.']],
                    ['en' => ['title' => 'Content Direction', 'description' => 'Content was organized around real traveler experience and trustworthy detail.'], 'fa' => ['title' => 'جهت‌گیری محتوا', 'description' => 'محتوا حول تجربه واقعی مسافران و جزئیات قابل‌اعتماد سازمان یافت.'], 'ar' => ['title' => 'توجيه المحتوى', 'description' => 'نُظّم المحتوى حول تجربة المسافرين الحقيقية والتفاصيل الموثوقة.']],
                    ['en' => ['title' => 'Conversion Path', 'description' => 'Clear calls to action for booking were built into every piece of content.'], 'fa' => ['title' => 'مسیر تبدیل', 'description' => 'فراخوان‌های اقدام شفاف برای رزرو در هر محتوا گنجانده شد.'], 'ar' => ['title' => 'مسار التحويل', 'description' => 'أُدرجت دعوات واضحة للحجز في كل محتوى.']],
                ],
                'deliverables' => [
                    ['en' => ['title' => 'Brand Direction', 'description' => 'A clearer visual and verbal direction.'], 'fa' => ['title' => 'جهت‌گیری برند', 'description' => 'جهت بصری و کلامی شفاف‌تر.'], 'ar' => ['title' => 'توجيه العلامة', 'description' => 'اتجاه بصري ولفظي أوضح.']],
                    ['en' => ['title' => 'Content Strategy', 'description' => 'A content plan built around each destination.'], 'fa' => ['title' => 'استراتژی محتوا', 'description' => 'برنامه محتوایی حول هر مقصد.'], 'ar' => ['title' => 'استراتيجية المحتوى', 'description' => 'خطة محتوى حول كل وجهة.']],
                    ['en' => ['title' => 'Tour Templates', 'description' => 'Reusable visual formats for presenting each tour.'], 'fa' => ['title' => 'قالب‌های تور', 'description' => 'فرمت‌های بصری قابل‌استفاده مجدد برای معرفی هر تور.'], 'ar' => ['title' => 'قوالب الرحلات', 'description' => 'صيغ بصرية قابلة لإعادة الاستخدام لعرض كل رحلة.']],
                    ['en' => ['title' => 'Content Calendar', 'description' => 'A structured monthly content plan.'], 'fa' => ['title' => 'تقویم محتوا', 'description' => 'برنامه ماهانه ساختارمند محتوا.'], 'ar' => ['title' => 'تقويم المحتوى', 'description' => 'خطة محتوى شهرية منظمة.']],
                ],
                'results' => [
                    ['en' => ['value' => '+118%', 'title' => 'View'], 'fa' => ['value' => '+۱۱۸٪', 'title' => 'بازدید'], 'ar' => ['value' => '+١١٨٪', 'title' => 'المشاهدات']],
                    ['en' => ['value' => '+92%', 'title' => 'Interaction'], 'fa' => ['value' => '+۹۲٪', 'title' => 'تعامل'], 'ar' => ['value' => '+٩٢٪', 'title' => 'التفاعل']],
                    ['en' => ['value' => '+67%', 'title' => 'Follower'], 'fa' => ['value' => '+۶۷٪', 'title' => 'دنبال‌کننده'], 'ar' => ['value' => '+٦٧٪', 'title' => 'المتابعون']],
                    ['en' => ['value' => '+81%', 'title' => 'Reach'], 'fa' => ['value' => '+۸۱٪', 'title' => 'دسترسی'], 'ar' => ['value' => '+٨١٪', 'title' => 'الوصول']],
                ],
            ],
            [
                'slug' => 'cheshmeh-beauty', 'industry' => 'زیبایی-و-سلامت', 'year' => '2024',
                'instagram' => 'cheshmeh.beauty', 'cover' => 'projects/cheshmeh.webp',
                'banner' => 'projects/cheshmeh-banner.webp', 'featured' => true,
                // Title and excerpt are the literal case-study text of Figma
                // 639:1617 (I1016:1863;… "Cheshmeh" + its subtitle run).
                'en' => [
                    'title' => 'Cheshmeh', 'subtitle' => 'A calmer, more trusted identity',
                    'excerpt' => 'Cheshmeh needed a stronger digital identity. one that could communicate trust, simplicity, and brand value across every touchpoint.',
                    'challenge' => 'Cheshmeh had strong services, but its digital presence did not clearly reflect the trust, calmness, and clarity people expect from a beauty clinic.',
                    'challenge_points' => ['No clear content direction', 'Inconsistent visual identity', 'Unclear brand presence'],
                    'results_summary' => "The project helped Cheshmeh build a clearer and more consistent digital presence. Through a refined visual direction and strategic content approach, the brand created stronger audience trust and engagement.",
                ],
                'fa' => [
                    'title' => 'کلینیک زیبایی چشمه', 'subtitle' => 'هویتی آرام‌تر و قابل‌اعتمادتر',
                    'excerpt' => 'بازطراحی کامل هویت دیجیتال برای یک کلینیک زیبایی و دندانپزشکی.',
                    'challenge' => 'چشمه خدمات قوی داشت، اما حضور دیجیتالش اعتماد، آرامش و شفافیتی را که از یک کلینیک زیبایی انتظار می‌رود، منعکس نمی‌کرد.',
                    'challenge_points' => ['نبود جهت‌گیری شفاف محتوایی', 'هویت بصری ناهماهنگ', 'حضور برند نامشخص'],
                    'results_summary' => 'این پروژه به چشمه کمک کرد حضور دیجیتالی شفاف‌تر و یکدست‌تری بسازد. با جهت‌گیری بصری بازتعریف‌شده و رویکرد محتوایی استراتژیک، برند اعتماد و تعامل قوی‌تری از مخاطبان به دست آورد.',
                ],
                'ar' => [
                    'title' => 'عيادة تجميل تشيشمه', 'subtitle' => 'هوية أكثر هدوءاً وثقة',
                    'excerpt' => 'تجديد كامل للهوية الرقمية لعيادة تجميل وأسنان.',
                    'challenge' => 'كانت لدى تشيشمه خدمات قوية، لكن حضورها الرقمي لم يعكس بوضوح الثقة والهدوء والوضوح التي يتوقعها الناس من عيادة تجميل.',
                    'challenge_points' => ['لا اتجاه واضح للمحتوى', 'هوية بصرية غير متسقة', 'حضور غير واضح للعلامة'],
                    'results_summary' => 'ساعد المشروع تشيشمه على بناء حضور رقمي أوضح وأكثر اتساقاً. من خلال توجيه بصري مُحسّن ونهج محتوى استراتيجي، بنت العلامة ثقة وتفاعلاً أقوى مع الجمهور.',
                ],
                'goals' => [
                    ['en' => ['title' => 'Calm Identity', 'description' => 'Softer brand perception'], 'fa' => ['title' => 'هویت آرام', 'description' => 'برداشتی نرم‌تر از برند'], 'ar' => ['title' => 'هوية هادئة', 'description' => 'إدراك أنعم للعلامة']],
                    ['en' => ['title' => 'Clear Direction', 'description' => 'A consistent visual system'], 'fa' => ['title' => 'جهت شفاف', 'description' => 'سیستم بصری یکدست'], 'ar' => ['title' => 'اتجاه واضح', 'description' => 'نظام بصري متسق']],
                    ['en' => ['title' => 'Trusted Presence', 'description' => 'Building audience trust'], 'fa' => ['title' => 'حضور قابل‌اعتماد', 'description' => 'ساخت اعتماد مخاطب'], 'ar' => ['title' => 'حضور موثوق', 'description' => 'بناء ثقة الجمهور']],
                    ['en' => ['title' => 'Meaningful Content', 'description' => 'Better audience connection'], 'fa' => ['title' => 'محتوای معنادار', 'description' => 'ارتباط بهتر با مخاطب'], 'ar' => ['title' => 'محتوى هادف', 'description' => 'تواصل أفضل مع الجمهور']],
                ],
                'strategy' => [
                    ['en' => ['title' => 'Visual System', 'description' => 'We developed a cleaner and more consistent visual language, focused on color, composition, soft hierarchy, and a more recognizable brand presence.'], 'fa' => ['title' => 'سیستم بصری', 'description' => 'زبان بصری‌ای شفاف‌تر و یکدست‌تر با تمرکز بر رنگ، ترکیب‌بندی و سلسله‌مراتب نرم توسعه دادیم.'], 'ar' => ['title' => 'النظام البصري', 'description' => 'طورنا لغة بصرية أنظف وأكثر اتساقاً، تركز على اللون والتكوين والتراتب الناعم.']],
                    ['en' => ['title' => 'Content Direction', 'description' => 'The content was organized around clear pillars: brand story, product value, lifestyle moments, audience education, and trust-building messages.'], 'fa' => ['title' => 'جهت‌گیری محتوا', 'description' => 'محتوا حول ستون‌های شفافی سازمان یافت: داستان برند، ارزش محصول، لحظات سبک زندگی و پیام‌های اعتمادساز.'], 'ar' => ['title' => 'توجيه المحتوى', 'description' => 'نُظّم المحتوى حول ركائز واضحة: قصة العلامة وقيمة المنتج ولحظات نمط الحياة ورسائل بناء الثقة.']],
                    ['en' => ['title' => 'Publishing Flow', 'description' => 'A structured publishing rhythm was created to help the brand stay active, consistent, and aligned across posts, stories, and campaign content.'], 'fa' => ['title' => 'جریان انتشار', 'description' => 'ریتم انتشار ساختارمندی ایجاد شد تا برند در پست‌ها، استوری‌ها و محتوای کمپین فعال و یکدست بماند.'], 'ar' => ['title' => 'تدفق النشر', 'description' => 'أُنشئ إيقاع نشر منظم ليبقى العلامة نشطة ومتسقة عبر المنشورات والقصص ومحتوى الحملات.']],
                    ['en' => ['title' => 'Audience Focus', 'description' => 'The messaging was shaped around people looking for quality, trust, simplicity, and a brand they could connect with beyond the product itself.'], 'fa' => ['title' => 'تمرکز بر مخاطب', 'description' => 'پیام‌رسانی حول افرادی شکل گرفت که به دنبال کیفیت، اعتماد، سادگی و ارتباطی فراتر از محصول بودند.'], 'ar' => ['title' => 'التركيز على الجمهور', 'description' => 'صيغت الرسائل حول أشخاص يبحثون عن الجودة والثقة والبساطة وعلامة يتواصلون معها بما يتجاوز المنتج.']],
                ],
                'deliverables' => [
                    ['en' => ['title' => 'Brand Direction', 'description' => 'A clearer direction for how Cheshmeh should communicate visually and verbally.'], 'fa' => ['title' => 'جهت‌گیری برند', 'description' => 'جهتی شفاف‌تر برای نحوه ارتباط بصری و کلامی چشمه.'], 'ar' => ['title' => 'توجيه العلامة', 'description' => 'اتجاه أوضح لكيفية تواصل تشيشمه بصرياً ولفظياً.']],
                    ['en' => ['title' => 'Content Strategy', 'description' => "Content pillars and messaging themes designed around the brand's audience and business goals."], 'fa' => ['title' => 'استراتژی محتوا', 'description' => 'ستون‌های محتوا و مضامین پیام طراحی‌شده حول مخاطب و اهداف کسب‌وکار.'], 'ar' => ['title' => 'استراتيجية المحتوى', 'description' => 'ركائز محتوى وموضوعات رسائل مصممة حول جمهور العلامة وأهداف العمل.']],
                    ['en' => ['title' => 'Visual System', 'description' => 'Reusable layouts, visual rules, and design patterns for social media.'], 'fa' => ['title' => 'سیستم بصری', 'description' => 'قالب‌ها، قواعد بصری و الگوهای طراحی قابل‌استفاده مجدد برای شبکه‌های اجتماعی.'], 'ar' => ['title' => 'النظام البصري', 'description' => 'تخطيطات وقواعد بصرية وأنماط تصميم قابلة لإعادة الاستخدام لوسائل التواصل.']],
                    ['en' => ['title' => 'Content Calendar', 'description' => 'A structured monthly content plan for posts, stories, and campaigns.'], 'fa' => ['title' => 'تقویم محتوا', 'description' => 'برنامه ماهانه ساختارمند محتوا برای پست‌ها، استوری‌ها و کمپین‌ها.'], 'ar' => ['title' => 'تقويم المحتوى', 'description' => 'خطة محتوى شهرية منظمة للمنشورات والقصص والحملات.']],
                    ['en' => ['title' => 'Story Templates', 'description' => 'Flexible story formats for product highlights, brand moments, FAQs, and engagement.'], 'fa' => ['title' => 'قالب‌های استوری', 'description' => 'فرمت‌های انعطاف‌پذیر استوری برای معرفی محصول، لحظات برند و تعامل.'], 'ar' => ['title' => 'قوالب القصص', 'description' => 'صيغ قصص مرنة لإبراز المنتج ولحظات العلامة والأسئلة الشائعة والتفاعل.']],
                    ['en' => ['title' => 'Campaign Ideas', 'description' => 'Creative concepts to support seasonal promotions, awareness, and audience interaction.'], 'fa' => ['title' => 'ایده‌های کمپین', 'description' => 'مفاهیم خلاقانه برای پشتیبانی از پروموشن‌های فصلی و تعامل مخاطب.'], 'ar' => ['title' => 'أفكار الحملات', 'description' => 'مفاهيم إبداعية لدعم العروض الموسمية والتوعية وتفاعل الجمهور.']],
                ],
                'results' => [
                    ['en' => ['value' => '+189%', 'title' => 'ROI'], 'fa' => ['value' => '+۱۸۹٪', 'title' => 'بازگشت سرمایه'], 'ar' => ['value' => '+١٨٩٪', 'title' => 'عائد الاستثمار']],
                    ['en' => ['value' => '+154%', 'title' => 'Reach'], 'fa' => ['value' => '+۱۵۴٪', 'title' => 'دسترسی'], 'ar' => ['value' => '+١٥٤٪', 'title' => 'الوصول']],
                    ['en' => ['value' => '+189%', 'title' => 'Interaction'], 'fa' => ['value' => '+۱۸۹٪', 'title' => 'تعامل'], 'ar' => ['value' => '+١٨٩٪', 'title' => 'التفاعل']],
                    ['en' => ['value' => '+256%', 'title' => 'Follower'], 'fa' => ['value' => '+۲۵۶٪', 'title' => 'دنبال‌کننده'], 'ar' => ['value' => '+٢٥٦٪', 'title' => 'المتابعون']],
                    ['en' => ['value' => '+72%', 'title' => 'View'], 'fa' => ['value' => '+۷۲٪', 'title' => 'بازدید'], 'ar' => ['value' => '+٧٢٪', 'title' => 'المشاهدات']],
                ],
            ],
        ];

        foreach ($projects as $index => $data) {
            $project = Project::updateOrCreate(
                ['id' => $index + 1],
                [
                    'industry_id' => $industries->get($data['industry'])?->id,
                    'status' => PublicationStatus::Published,
                    'published_at' => now()->subDays(count($projects) - $index),
                    'sort_order' => $index,
                    'is_featured' => $data['featured'] ?? false,
                    'year' => $data['year'],
                    'instagram_handle' => $data['instagram'],
                    'cover_path' => $data['cover'],
                    'banner_path' => $data['banner'] ?? $data['cover'],
                ],
            );

            $project->setTranslations([
                'en' => array_merge(['slug' => $data['slug']], $data['en']),
                'fa' => array_merge(['slug' => $this->faSlug($data['slug'])], $data['fa']),
                'ar' => array_merge(['slug' => $this->arSlug($data['slug'])], $data['ar']),
            ]);

            // Attach a couple of services to each project.
            $project->services()->sync(
                Service::query()->inRandomOrder()->limit(2)->pluck('id'),
            );

            $this->caseStudyDetail($project, $data);

            if (($data['featured'] ?? false) === true) {
                foreach ([
                    'projects/cheshmeh-showcase-1.webp',
                    'projects/cheshmeh-showcase-2.webp',
                    'projects/cheshmeh-showcase-3.webp',
                ] as $order => $path) {
                    $project->images()->updateOrCreate(
                        ['sort_order' => $order],
                        ['path' => $path],
                    );
                }

                $project->update([
                    'before_image_path' => 'projects/cheshmeh-before.webp',
                    'after_image_path' => 'projects/cheshmeh-after.webp',
                ]);
            } else {
                // No dedicated showcase photography exists for these five yet
                // (placeholder content, see class docblock) — reuse the cover
                // so the "selected content" section isn't empty, and skip
                // before/after entirely rather than pairing a real image
                // with a fabricated "after" that doesn't exist.
                $project->images()->updateOrCreate(
                    ['sort_order' => 0],
                    ['path' => $data['cover']],
                );
            }
        }
    }

    /**
     * Goals / strategy / deliverables / results sections shared by every
     * project's case-study page (`Work/Show.vue`, Figma 336:5374's system).
     */
    private function caseStudyDetail(Project $project, array $data): void
    {
        $project->sections()->delete();

        $goals = $this->section($project, SectionType::Goals, 0);
        $this->items($goals, $data['goals']);

        $strategy = $this->section($project, SectionType::Strategy, 1);
        $this->items($strategy, $data['strategy']);

        $deliverables = $this->section($project, SectionType::Deliverables, 2);
        $this->items($deliverables, $data['deliverables']);

        $results = $this->section($project, SectionType::Results, 3);
        $this->items($results, $data['results']);
    }

    private function section(Project $project, SectionType $type, int $order): PageSection
    {
        return $project->sections()->create([
            'type' => $type,
            'sort_order' => $order,
            'is_visible' => true,
        ]);
    }

    /**
     * @param  array<int, array<string, array<string, string>>>  $items
     */
    private function items(PageSection $section, array $items): void
    {
        foreach ($items as $index => $translations) {
            $item = $section->items()->create(['sort_order' => $index, 'is_visible' => true]);
            $item->setTranslations($translations);
        }
    }

    private function faSlug(string $enSlug): string
    {
        return match ($enSlug) {
            'baghche-branding' => 'باغچه',
            'kerman-motors' => 'کرمان-موتورز',
            'fakhar-clinic' => 'کلینیک-فخر',
            'plus-protein' => 'پلاس-پروتئین',
            'karsa-tourism' => 'کرسا-توریسم',
            'cheshmeh-beauty' => 'چشمه-بیوتی',
            default => $enSlug,
        };
    }

    private function arSlug(string $enSlug): string
    {
        return match ($enSlug) {
            'baghche-branding' => 'باغتشه',
            'kerman-motors' => 'كرمان-موتورز',
            'fakhar-clinic' => 'عيادة-فخر',
            'plus-protein' => 'بلس-بروتين',
            'karsa-tourism' => 'كارسا-تورزم',
            'cheshmeh-beauty' => 'تشيشمه-بيوتي',
            default => $enSlug,
        };
    }
}

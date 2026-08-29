<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PublicationStatus;
use App\Models\Service;
use Illuminate\Database\Seeder;

/**
 * The four services. Persian copy is verbatim from the text runs on Figma
 * 308:4492 (315:4855/4858/4860.., 315:4899.., 315:4979.., 321:5005..),
 * re-read 2026-08-27; en/ar are still the older derived copy and need a
 * native pass (GAPS G10, TRANSLATIONS_TODO.md).
 * Image paths point at the manifest destinations (docs/ASSET-MANIFEST.md §5).
 */
final class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'image' => 'services/branding.webp',
                'icon' => 'palette',
                'en' => [
                    'title' => 'Branding',
                    'slug' => 'branding',
                    'description' => 'Building a clear identity that helps your brand become recognizable, consistent, and memorable across every touchpoint.',
                    'features' => ['Logo design', 'Visual Identity Development', 'Brand book creation', 'Brand consistency systems', 'Posters'],
                    'image_alt' => 'Framed poster reading "Where identity becomes direction"',
                ],
                'fa' => [
                    'title' => 'برندینگ',
                    'slug' => 'برندینگ',
                    'description' => 'ساخت هویتی یکپارچه که برندتان را در ذهن مخاطب، قابل‌شناسایی و ماندگار می‌کند.',
                    'features' => ['طراحی لوگو', 'توسعه هویت بصری', 'تدوین برندبوک', 'طراحی سیستم یکپارچگی برند', 'طراحی پوستر'],
                    'image_alt' => 'پوستر قاب‌شده با عبارت «جایی که هویت به جهت تبدیل می‌شود»',
                ],
                'ar' => [
                    'title' => 'الهوية التجارية',
                    'slug' => 'الهوية-التجارية',
                    'description' => 'بناء هوية واضحة تجعل علامتك مميزة ومتسقة ولا تُنسى في كل نقطة تواصل.',
                    'features' => ['تصميم الشعار', 'تطوير الهوية البصرية', 'إعداد دليل العلامة', 'أنظمة اتساق العلامة', 'الملصقات'],
                    'image_alt' => 'ملصق مؤطر يحمل عبارة «حيث تصبح الهوية اتجاهاً»',
                ],
            ],
            [
                'image' => 'services/content-production.webp',
                'icon' => 'video',
                'en' => [
                    'title' => 'Content Production',
                    'slug' => 'content-production',
                    'description' => 'Creating meaningful content that connects your brand with the right audience.',
                    'features' => ['Social media content creation', 'Post and story design', 'Reels and short-form content support', 'Campaign visuals', 'Content direction and creative planning'],
                    'image_alt' => 'Warm desk setup with laptop, notebook and coffee',
                ],
                'fa' => [
                    'title' => 'تولید محتوا',
                    'slug' => 'تولید-محتوا',
                    'description' => 'محتوا باید فراتر از سرگرمی باشد؛ ما داستان‌هایی می‌سازیم که مخاطب را به مشتری تبدیل می‌کند.',
                    'features' => ['استراتژی و تقویم محتوایی', 'طراحی پیام و روایت برند', 'تولید محتوای کوتاه و اثرگذار', 'سناریونویسی و کارگردانی خلاق'],
                    'image_alt' => 'میز کار گرم با لپ‌تاپ، دفترچه و قهوه',
                ],
                'ar' => [
                    'title' => 'إنتاج المحتوى',
                    'slug' => 'إنتاج-المحتوى',
                    'description' => 'إنشاء محتوى هادف يربط علامتك بالجمهور المناسب.',
                    'features' => ['إنشاء محتوى وسائل التواصل', 'تصميم المنشورات والقصص', 'دعم الريلز والمحتوى القصير', 'مرئيات الحملات', 'توجيه المحتوى والتخطيط الإبداعي'],
                    'image_alt' => 'مكتب دافئ مع حاسوب محمول ودفتر وقهوة',
                ],
            ],
            [
                'image' => 'services/marketing-design.webp',
                'icon' => 'megaphone',
                'en' => [
                    'title' => 'Marketing Design',
                    'slug' => 'marketing-design',
                    'description' => 'Creating visual assets that communicate your message with clarity and impact.',
                    'features' => ['Promotional creative', 'Campaign assets', 'Advertising visuals', 'Presentation and communication materials'],
                    'image_alt' => 'Large "MARKETING" wall type behind a seated person',
                ],
                'fa' => [
                    'title' => 'مارکتینگ',
                    'slug' => 'مارکتینگ',
                    'description' => 'دارایی‌های بصری باید «اقدام» ایجاد کنند. ما پیام شما را با وضوح و قدرت به مخاطب می‌رسانیم.',
                    'features' => ['طراحی اقلام تبلیغاتی', 'طراحی کمپین‌های فروش', 'طراحی بنرهای دیجیتال و تبلیغاتی', 'طراحی هویت بصری کمپین‌ها'],
                    'image_alt' => 'تایپوگرافی بزرگ «MARKETING» روی دیوار پشت فردی نشسته',
                ],
                'ar' => [
                    'title' => 'تصميم التسويق',
                    'slug' => 'تصميم-التسويق',
                    'description' => 'إنشاء أصول بصرية توصل رسالتك بوضوح وتأثير.',
                    'features' => ['الإبداع الترويجي', 'أصول الحملات', 'المرئيات الإعلانية', 'مواد العرض والتواصل'],
                    'image_alt' => 'كلمة «MARKETING» كبيرة على الجدار خلف شخص جالس',
                ],
            ],
            [
                'image' => 'services/social-media-support.webp',
                'icon' => 'share-2',
                'en' => [
                    'title' => 'Social Media Support',
                    'slug' => 'social-media-support',
                    'description' => 'Building a consistent social presence through structured content and ongoing support.',
                    'features' => ['Page management', 'Content structuring', 'Visual consistency across platforms', 'Ongoing creative support for digital presence'],
                    'image_alt' => 'Hand holding a phone showing analytics',
                ],
                'fa' => [
                    'title' => 'پشتیبانی شبکه های اجتماعی',
                    'slug' => 'پشتیبانی-شبکه-های-اجتماعی',
                    'description' => 'حضور آنلاین نباید تصادفی باشد. ما با ساختاردهی و مدیریت منظم، پلتفرم‌های شما را به کانال‌های رشد تبدیل می‌کنیم.',
                    'features' => ['مدیریت جامع صفحات', 'ساختاردهی محتوایی', 'آنالیز عملکرد و بهینه‌سازی', 'توسعه مداوم حضور دیجیتال'],
                    'image_alt' => 'دستی که گوشی با نمودار تحلیل را نگه داشته',
                ],
                'ar' => [
                    'title' => 'دعم وسائل التواصل',
                    'slug' => 'دعم-وسائل-التواصل',
                    'description' => 'بناء حضور اجتماعي متسق عبر محتوى منظم ودعم مستمر.',
                    'features' => ['إدارة الصفحات', 'هيكلة المحتوى', 'الاتساق البصري عبر المنصات', 'دعم إبداعي مستمر للحضور الرقمي'],
                    'image_alt' => 'يد تحمل هاتفاً يعرض التحليلات',
                ],
            ],
        ];

        foreach ($services as $index => $data) {
            $service = Service::updateOrCreate(
                ['id' => $index + 1],
                [
                    'status' => PublicationStatus::Published,
                    'published_at' => now(),
                    'sort_order' => $index,
                    'show_on_home' => true,
                    'icon' => $data['icon'],
                    'image_path' => $data['image'],
                ],
            );

            $service->setTranslations([
                'en' => $data['en'],
                'fa' => $data['fa'],
                'ar' => $data['ar'],
            ]);
        }
    }
}

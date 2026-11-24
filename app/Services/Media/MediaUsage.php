<?php

declare(strict_types=1);

namespace App\Services\Media;

use App\Models\Media;
use Illuminate\Support\Facades\DB;

/** Find stored references before removing an original media file. */
final class MediaUsage
{
    /** @var array<string, array{string, array<string, string>}> */
    private const FIELDS = [
        'posts' => ['مقاله', ['cover_path' => 'تصویر کاور']],
        'post_translations' => ['متن مقاله', ['content' => 'محتوا']],
        'projects' => ['پروژه', ['cover_path' => 'کاور', 'banner_path' => 'بنر', 'before_image_path' => 'تصویر قبل', 'after_image_path' => 'تصویر بعد']],
        'project_images' => ['تصویر پروژه', ['path' => 'گالری']],
        'services' => ['خدمت', ['image_path' => 'تصویر']],
        'page_sections' => ['بخش صفحه یا پروژه', ['image_path' => 'تصویر بخش']],
        'section_items' => ['آیتم بخش', ['image_path' => 'تصویر آیتم']],
        'page_section_translations' => ['محتوای بخش', ['content' => 'متن']],
        'page_translations' => ['متن صفحه', ['content' => 'محتوا']],
        'team_members' => ['عضو تیم', ['photo_path' => 'تصویر']],
        'testimonials' => ['نظر مشتری', ['avatar_path' => 'تصویر']],
        'clients' => ['مشتری', ['logo_path' => 'لوگو']],
        'settings' => ['تنظیمات سایت', ['value' => 'مقدار تنظیم']],
        'seo_meta' => ['سئوی محتوا', ['og_image' => 'تصویر شبکه اجتماعی', 'twitter_image' => 'تصویر توییتر', 'schema_data' => 'اسکیما', 'custom_schema' => 'اسکیمای سفارشی']],
        'media' => ['فایل رسانه', ['poster_path' => 'پوستر ویدیو']],
    ];

    /** @return list<string> */
    public static function find(Media $media): array
    {
        $path = (string) $media->path;
        if ($path === '') {
            return [];
        }

        $uses = [];
        foreach (self::FIELDS as $table => [$label, $fields]) {
            $query = DB::table($table)->select(array_merge(['id'], match ($table) { 'settings' => ['key'], 'post_translations' => ['post_id', 'locale'], 'page_translations' => ['page_id', 'locale'], 'page_section_translations' => ['page_section_id', 'locale'], 'page_sections' => ['sectionable_type', 'sectionable_id', 'type'], 'section_items' => ['page_section_id'], 'project_images' => ['project_id'], 'seo_meta' => ['seoable_type', 'seoable_id', 'locale'], default => [] }, array_keys($fields)));
            if ($table === 'media') {
                $query->where('id', '!=', $media->id);
            }
            foreach ($query->cursor() as $row) {
                foreach ($fields as $field => $fieldLabel) {
                    $value = $row->{$field};
                    if (! is_string($value)) {
                        continue;
                    }
                    // JSON often escapes slashes; rich text may contain absolute or URL-encoded URLs.
                    $value = rawurldecode(html_entity_decode(str_replace('\\/', '/', $value), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                    if (! str_contains($value, $path)) {
                        continue;
                    }
                    $identifier = self::location($table, $row);
                    $uses[] = "{$label} {$identifier} — {$fieldLabel}";
                }
            }
        }

        return array_values(array_unique($uses));
    }

    private static function location(string $table, object $row): string
    {
        return match ($table) {
            'settings' => self::settingLocation($row->key),
            'posts' => '#'.$row->id.' (/admin/posts/'.$row->id.'/edit)',
            'post_translations' => '#'.$row->post_id.'، زبان '.$row->locale.' (/admin/posts/'.$row->post_id.'/edit)',
            'projects' => '#'.$row->id.' (/admin/projects/'.$row->id.'/edit)',
            'project_images' => '#'.$row->id.'، پروژه #'.$row->project_id.' (/admin/projects/'.$row->project_id.'/edit)',
            'services' => '#'.$row->id.' (/admin/services/'.$row->id.'/edit)',
            'page_translations' => '#'.$row->page_id.'، زبان '.$row->locale.' (/admin/pages/'.$row->page_id.'/edit)',
            'page_sections' => self::sectionLocation($row),
            'page_section_translations' => self::sectionById($row->page_section_id).'، زبان '.$row->locale,
            'section_items' => '#'.$row->id.'، '.self::sectionById($row->page_section_id),
            'seo_meta' => class_basename($row->seoable_type).' #'.$row->seoable_id.'، زبان '.$row->locale,
            default => '#'.$row->id,
        };
    }

    private static function settingLocation(string $key): string
    {
        if (preg_match('/^image_top_(home|work|services|insights|about)_(desktop|tablet|mobile)$/', $key, $match)) {
            $page = ['home' => 'صفحه اصلی', 'work' => 'پروژه‌ها', 'services' => 'خدمات', 'insights' => 'مقالات', 'about' => 'درباره ما'][$match[1]];
            $device = ['desktop' => 'دسکتاپ', 'tablet' => 'تبلت', 'mobile' => 'موبایل'][$match[2]];

            return "پس‌زمینه {$page}، {$device} (/admin/manage-settings)";
        }

        return "({$key}) (/admin/manage-settings)";
    }

    private static function sectionById(int $id): string
    {
        $section = DB::table('page_sections')->where('id', $id)->first(['id', 'sectionable_type', 'sectionable_id', 'type']);

        return $section ? self::sectionLocation($section) : 'بخش #'.$id;
    }

    private static function sectionLocation(object $section): string
    {
        $type = class_basename($section->sectionable_type);
        $link = match ($type) {
            'Page' => '/admin/pages/'.$section->sectionable_id.'/edit',
            'Project' => '/admin/projects/'.$section->sectionable_id.'/edit',
            default => null,
        };
        $owner = $type === 'Page'
            ? 'صفحه '.(DB::table('pages')->where('id', $section->sectionable_id)->value('key') ?? '#'.$section->sectionable_id)
            : ($type === 'Project' ? 'پروژه #'.$section->sectionable_id : $type.' #'.$section->sectionable_id);

        return "#{$section->id}، بخش {$section->type} در {$owner}".($link ? " ({$link})" : '');
    }

}

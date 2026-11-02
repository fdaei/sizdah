<?php

declare(strict_types=1);

use App\Enums\SectionType;
use App\Models\Page;
use App\Models\Service;
use Database\Seeders\PageSeeder;
use Database\Seeders\ServiceSeeder;
use Inertia\Testing\AssertableInertia;

beforeEach(function (): void {
    $this->seed([PageSeeder::class, ServiceSeeder::class]);
});

it('delivers all editable services-page content to the frontend', function (): void {
    $page = Page::query()->where('key', 'services')->firstOrFail();
    $page->setTranslations(['fa' => [
        'title' => 'عنوان ویرایش‌شده',
        'subtitle' => 'برچسب ویرایش‌شده',
        'description' => 'توضیح ویرایش‌شده',
    ]]);

    $cta = $page->sections()->where('type', SectionType::FinalCta)->firstOrFail();
    $cta->setTranslations(['fa' => [
        'title' => 'دعوت به اقدام ویرایش‌شده',
        'primary_cta_label' => 'شروع تازه',
        'primary_cta_url' => '/fa/contact',
        'image_alt' => 'تصویر اختصاصی دعوت به اقدام',
    ]]);
    $cta->update(['image_path' => 'sections/custom-services-cta.jpg']);

    $service = Service::query()->firstOrFail();
    $service->setTranslations(['fa' => [
        'title' => 'خدمت ویرایش‌شده',
        'description' => 'شرح خدمت ویرایش‌شده',
        'features' => ['ویژگی ویرایش‌شده'],
    ]]);

    $this->get('/fa/services')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $response) => $response
            ->component('Services')
            ->where('heading.title', 'عنوان ویرایش‌شده')
            ->where('heading.eyebrow', 'برچسب ویرایش‌شده')
            ->where('heading.description', 'توضیح ویرایش‌شده')
            ->where('services.0.title', 'خدمت ویرایش‌شده')
            ->where('services.0.description', 'شرح خدمت ویرایش‌شده')
            ->where('services.0.features', ['ویژگی ویرایش‌شده'])
            ->where('sections.final_cta.title', 'دعوت به اقدام ویرایش‌شده')
            ->where('sections.final_cta.primaryCta.label', 'شروع تازه')
            ->where('sections.final_cta.primaryCta.url', '/fa/contact')
            ->where('sections.final_cta.image.src', '/storage/sections/custom-services-cta.jpg')
            ->where('sections.final_cta.image.alt', 'تصویر اختصاصی دعوت به اقدام'));
});

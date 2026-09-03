<?php

declare(strict_types=1);

use App\Enums\SectionType;
use App\Models\Page;
use Database\Seeders\PageSeeder;

/**
 * Guards the Home KPI strip (Figma 268:3026) against the exact regression
 * found in practice: the seeder was correct, but the deployed database had
 * drifted — icons nulled out and cards reordered — with nothing in the
 * codebase left to catch it. This seeds fresh and asserts on the result.
 */
it('seeds the Home KPI section with the exact Figma card content, icons and order', function (): void {
    $this->seed(PageSeeder::class);

    $page = Page::query()->where('key', 'home')->firstOrFail();

    $kpi = $page->sections()
        ->where('type', SectionType::Kpi)
        ->with(['items.translations' => fn ($q) => $q->where('locale', 'fa')])
        ->firstOrFail();

    $cards = $kpi->items
        ->sortBy('sort_order')
        ->map(fn ($item) => [
            'icon' => $item->icon,
            'value' => $item->translations->first()->value,
            'title' => $item->translations->first()->title,
            'description' => $item->translations->first()->description,
        ])
        ->values()
        ->all();

    // Reading order on the frame (right to left) is retention -> audience ->
    // engagement, i.e. 268:3029 leads; that is also array/sort_order 0..2.
    expect($cards)->toBe([
        [
            'icon' => 'retention',
            'value' => '+90%',
            'title' => 'حفظ مشتریان',
            'description' => 'در پلن‌های بازاریابی ماهانه',
        ],
        [
            'icon' => 'audience',
            'value' => '+70K',
            'title' => 'رشد مخاطب',
            'description' => 'فالوئر های جذب شده از یک ریلز',
        ],
        [
            'icon' => 'engagement',
            'value' => '+40%',
            'title' => 'افزایش تعامل',
            'description' => 'میانگین رشد بعد از بازنگری استراتژی',
        ],
    ]);
});

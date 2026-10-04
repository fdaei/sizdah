<?php

declare(strict_types=1);

use App\Enums\SectionType;
use App\Filament\Resources\PageResource\Pages\EditPage;
use App\Filament\Resources\ProjectResource\RelationManagers\SectionsRelationManager;
use App\Models\Page;
use App\Models\User;
use Database\Seeders\PageSeeder;
use Database\Seeders\RolePermissionSeeder;
use Filament\Tables\Actions\EditAction;
use Inertia\Testing\AssertableInertia;
use Livewire\Livewire;

/**
 * The admin section editor is the only way an editor reaches the copy on the
 * section-driven pages, so these guard the round trip: a value saved through
 * the Filament form must arrive in the props the Vue page renders.
 */
beforeEach(function (): void {
    $this->seed([RolePermissionSeeder::class, PageSeeder::class]);

    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $this->actingAs($admin);

    $this->home = Page::query()->where('key', 'home')->firstOrFail();
});

function homeSection(Page $home, SectionType $type)
{
    return $home->sections()->where('type', $type)->firstOrFail();
}

it('shows the plain content field for the home hero, which renders it as its paragraph', function (): void {
    Livewire::test(SectionsRelationManager::class, [
        'ownerRecord' => $this->home,
        'pageClass' => EditPage::class,
    ])
        ->mountTableAction(EditAction::class, homeSection($this->home, SectionType::Hero))
        ->assertFormFieldIsVisible('translations.fa.content', 'mountedTableActionForm');
});

it('delivers hero copy and text colours saved in the admin to the home page', function (): void {
    Livewire::test(SectionsRelationManager::class, [
        'ownerRecord' => $this->home,
        'pageClass' => EditPage::class,
    ])
        ->mountTableAction(EditAction::class, homeSection($this->home, SectionType::Hero))
        ->setTableActionData([
            'translations' => ['fa' => ['content' => 'پاراگراف ویرایش‌شده در پنل']],
            'title_color' => '#ff0000',
        ])
        ->callMountedTableAction()
        ->assertHasNoTableActionErrors();

    $this->get('/fa')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Home')
            ->where('sections.hero.content', 'پاراگراف ویرایش‌شده در پنل')
            ->where('sections.hero.colors.title', '#ff0000'));
});

it('offers only the artwork keys the frontend can draw for card icons', function (): void {
    expect(array_keys(SectionType::Kpi->iconOptions()))->toBe(['engagement', 'audience', 'retention'])
        ->and(SectionType::WhyUs->iconOptions())->toBe([]);
});

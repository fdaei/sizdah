<?php

declare(strict_types=1);

use App\Filament\Pages\ManageUiStrings;
use App\Models\Page;
use App\Models\User;
use App\Support\UiStrings;
use Livewire\Livewire;

beforeEach(function (): void {
    config(['inertia.ssr.enabled' => false]);
    Page::factory()->create(['key' => 'home']);
});

it('serves lang-file defaults when nothing is overridden', function (): void {
    $this->get('/fa')->assertOk()
        ->assertInertia(fn ($page) => $page->where('translations.common.read_more', 'ادامه مطلب'));
});

it('applies admin overrides to the Inertia translations prop and to __()', function (): void {
    UiStrings::save('fa', ['common.read_more' => 'بیشتر بخوانید']);

    $this->get('/fa')->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('translations.common.read_more', 'بیشتر بخوانید')
            // Siblings in the same group are untouched.
            ->where('translations.common.view_all', 'مشاهده همه'));

    expect(__('common.read_more'))->toBe('بیشتر بخوانید')
        ->and(__('common.view_all'))->toBe('مشاهده همه');
});

it('drops empty values, unknown keys, defaults and locked groups', function (): void {
    UiStrings::save('fa', [
        'common.read_more' => '',
        'common.view_all' => 'مشاهده همه', // same as default
        'common.nope' => 'x',
        'validation.required' => 'x',
        'footer.info' => 'اطلاعات تماس',
    ]);

    expect(UiStrings::overrides('fa'))->toBe(['footer.info' => 'اطلاعات تماس']);
});

it('lets an admin edit strings from the panel', function (): void {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    Livewire::actingAs($admin)
        ->test(ManageUiStrings::class)
        ->set('data.fa.footer__terms', 'قوانین سایت')
        ->call('save')
        ->assertHasNoErrors();

    expect(UiStrings::overrides('fa'))->toBe(['footer.terms' => 'قوانین سایت']);
});

it('keeps admin/validation strings out of the editable list', function (): void {
    expect(UiStrings::editable('fa'))
        ->not->toHaveKey('admin')
        ->not->toHaveKey('validation')
        ->toHaveKey('footer');
});

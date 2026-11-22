<?php

declare(strict_types=1);

use App\Enums\PublicationStatus;
use App\Filament\Resources\IndustryResource\Pages\EditIndustry;
use App\Filament\Resources\TeamMemberResource\Pages\EditTeamMember;
use App\Models\Industry;
use App\Models\Project;
use App\Models\TeamMember;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Inertia\Testing\AssertableInertia as Assert;
use Livewire\Livewire;

function seoIndustry(string $slug): Industry
{
    $industry = Industry::create(['sort_order' => 0]);
    $industry->setTranslations(['fa' => ['name' => 'صنعت نمونه', 'slug' => $slug]]);

    return $industry;
}

it('serves only published projects of the selected category with its seo', function (): void {
    config(['inertia.ssr.enabled' => false]);
    $industry = seoIndustry('example');
    $industry->saveSeo(['fa' => ['title' => 'عنوان دسته پروژه', 'description' => 'توضیحات دسته پروژه']]);
    $project = Project::factory()->published()->create(['industry_id' => $industry->id]);
    $project->setTranslations(['fa' => ['title' => 'پروژه منتشر', 'slug' => 'published-project']]);
    $draft = Project::factory()->create(['industry_id' => $industry->id, 'status' => PublicationStatus::Draft]);
    $draft->setTranslations(['fa' => ['title' => 'پروژه پیش نویس', 'slug' => 'draft-project']]);
    $other = Project::factory()->published()->create(['industry_id' => null]);
    $other->setTranslations(['fa' => ['title' => 'پروژه دیگر', 'slug' => 'other-project']]);

    $this->get('/fa/work/industry/example')->assertOk()->assertInertia(fn (Assert $page) => $page
        ->component('Work/Index')->where('heading.title', 'صنعت نمونه')
        ->has('projects', 1)->where('projects.0.title', 'پروژه منتشر')
        ->where('seo.canonical', url('/fa/work/industry/example')));
    expect($this->get('/fa/work/industry/example')->getContent())->toContain('عنوان دسته پروژه')->toContain('توضیحات دسته پروژه');
    expect($this->get('/fa/work/published-project')->assertOk()->getContent())->toContain('href="/fa/work/industry/example"');
    expect($this->get('/sitemap-industries-1.xml')->assertOk()->getContent())->toContain('/fa/work/industry/example');
    $this->get('/fa/work/industry/missing')->assertNotFound();
});

it('applies the admin seo and redirects a renamed project category', function (): void {
    $this->seed(RolePermissionSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $this->actingAs($admin);
    $industry = seoIndustry('old-industry');

    Livewire::test(EditIndustry::class, ['record' => $industry->id])
        ->fillForm(['translations' => ['fa' => ['slug' => 'new-industry']], 'seo' => ['fa' => ['title' => 'عنوان جدید', 'robots_index' => '0']]])
        ->call('save')->assertHasNoFormErrors();

    $this->get('/fa/work/industry/old-industry')->assertRedirect('/fa/work/industry/new-industry')->assertStatus(301);
    $this->get('/fa/work/industry/new-industry')->assertOk()->assertSee('عنوان جدید')->assertSee('noindex, follow');
});

it('explains team seo without offering page fields that cannot affect a team member', function (): void {
    $this->seed(RolePermissionSeeder::class);
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $this->actingAs($admin);
    $member = TeamMember::create(['is_active' => true, 'sort_order' => 0]);
    $member->setTranslations(['fa' => ['name' => 'عضو', 'role' => 'مدیر']]);

    Livewire::test(EditTeamMember::class, ['record' => $member->id])
        ->assertSee('اسکیمای Person')->assertDontSee('عنوان سئو (SEO title)');
});

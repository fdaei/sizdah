<?php

declare(strict_types=1);

use App\Models\Project;
use App\Models\User;
use App\Support\Preview;

function draftProject(string $slug): Project
{
    $project = Project::factory()->create();
    $project->setTranslations(['fa' => ['title' => 'Draft', 'slug' => $slug, 'excerpt' => 'x']]);

    return $project->fresh();
}

it('renders a draft for a logged-in admin through a signed preview link', function (): void {
    $url = Preview::url(draftProject('draft-preview'), 'fa');

    $this->actingAs(User::factory()->create())
        ->get($url)
        ->assertOk()
        ->assertHeader('X-Robots-Tag', 'noindex, nofollow');
});

it('keeps the draft hidden without a session', function (): void {
    $url = Preview::url(draftProject('draft-guest'), 'fa');

    $this->get($url)->assertNotFound();
});

it('rejects a tampered signature', function (): void {
    $url = Preview::url(draftProject('draft-tamper'), 'fa');

    $this->actingAs(User::factory()->create())
        ->get(preg_replace('/signature=[0-9a-f]+/', 'signature='.str_repeat('0', 64), $url))
        ->assertNotFound();
});

it('unlocks only the previewed record', function (): void {
    $previewed = draftProject('draft-a');
    draftProject('draft-b');

    $token = explode('preview=', Preview::url($previewed, 'fa'))[1];
    parse_str('preview='.$token, $q);
    Preview::activate($q['preview'], $q['signature']);

    expect(Project::query()->published()->pluck('id')->all())->toBe([$previewed->id]);

    Preview::reset();
    expect(Project::query()->published()->count())->toBe(0);
});

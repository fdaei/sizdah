<?php

declare(strict_types=1);

use App\Enums\PublicationStatus;
use App\Filament\Resources\RedirectResource\Pages\ListRedirects;
use App\Models\NotFoundLog;
use App\Models\Post;
use App\Models\Redirect;
use App\Services\Redirects\RedirectResolver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

beforeEach(function (): void {
    config(['inertia.ssr.enabled' => false]);
});

function redirectRule(array $attrs): Redirect
{
    return Redirect::query()->create($attrs + ['status_code' => 301, 'match_type' => 'exact', 'is_active' => true]);
}

it('applies an exact 301 before routing, keeping the query string, and counts the hit', function (): void {
    $r = redirectRule(['source_path' => '/fa/old-page/', 'destination_path' => 'fa/about']);

    expect($r->source_path)->toBe('fa/old-page');

    $this->get('/fa/old-page?utm_source=x')
        ->assertStatus(301)
        ->assertRedirect(url('/fa/about').'?utm_source=x');

    expect($r->fresh()->hits)->toBe(1)->and($r->fresh()->last_hit_at)->not->toBeNull();
});

it('matches Persian paths whether stored raw or percent-encoded', function (): void {
    redirectRule(['source_path' => '/fa/insights/%D9%85%D9%82%D8%A7%D9%84%D9%87', 'destination_path' => 'fa/insights']);

    $this->get('/fa/insights/مقاله')->assertStatus(301)->assertRedirect(url('/fa/insights'));
});

it('supports regex rules with $1 captures, 302/307 and external targets', function (): void {
    redirectRule(['source_path' => '^blog/(\d+)$', 'match_type' => 'regex', 'destination_path' => 'fa/insights?id=$1', 'status_code' => 302]);
    redirectRule(['source_path' => 'go/docs', 'destination_path' => 'https://example.com/docs', 'status_code' => 307]);

    $this->get('/blog/42')->assertStatus(302)->assertRedirect(url('/fa/insights').'?id=42');
    $this->get('/go/docs')->assertStatus(307)->assertRedirect('https://example.com/docs');
});

it('answers 410 / 451 without a destination', function (): void {
    redirectRule(['source_path' => 'fa/removed', 'destination_path' => 'fa/x', 'status_code' => 410]);

    expect(Redirect::query()->where('source_path', 'fa/removed')->value('destination_path'))->toBeNull();
    $this->get('/fa/removed')->assertStatus(410);
});

it('ignores inactive rules and never touches the admin panel or POSTs', function (): void {
    redirectRule(['source_path' => 'fa/off', 'destination_path' => 'fa/about', 'is_active' => false]);
    redirectRule(['source_path' => 'fa/contact', 'destination_path' => 'fa/about']);

    $this->get('/fa/off')->assertNotFound();
    $this->post('/fa/contact', [])->assertStatus(302)->assertHeaderMissing('X-Redirect-Rule');
    expect(Redirect::query()->where('source_path', 'fa/contact')->value('hits'))->toBe(0);
});

it('collapses chains: A->B then B->C makes A->C', function (): void {
    $a = redirectRule(['source_path' => 'fa/a', 'destination_path' => 'fa/b']);
    redirectRule(['source_path' => 'fa/b', 'destination_path' => 'fa/c']);

    expect($a->fresh()->destination_path)->toBe('fa/c');
    $this->get('/fa/a')->assertRedirect(url('/fa/c'));
});

it('shortens a new rule that points at an existing source', function (): void {
    redirectRule(['source_path' => 'fa/b', 'destination_path' => 'fa/c']);
    $a = redirectRule(['source_path' => 'fa/a', 'destination_path' => 'fa/b']);

    expect($a->destination_path)->toBe('fa/c');
});

it('preserves destination query strings through exact redirect chains', function (): void {
    redirectRule(['source_path' => 'fa/b', 'destination_path' => 'fa/c']);
    $a = redirectRule(['source_path' => 'fa/a', 'destination_path' => 'fa/b?source=campaign']);

    expect($a->destination_path)->toBe('fa/c?source=campaign');
    $this->get('/fa/a?incoming=ignored')->assertRedirect(url('/fa/c').'?source=campaign');
});

it('lets the final destination query override an earlier query in a chain', function (): void {
    redirectRule(['source_path' => 'fa/b', 'destination_path' => 'fa/c?chosen=final']);
    $a = redirectRule(['source_path' => 'fa/a', 'destination_path' => 'fa/b?chosen=first']);

    expect($a->destination_path)->toBe('fa/c?chosen=final');
    $this->get('/fa/a')->assertRedirect(url('/fa/c').'?chosen=final');
});

it('does not treat a host that merely starts with the site host as internal', function (): void {
    $host = parse_url(url('/'), PHP_URL_HOST);
    $destination = 'https://'.$host.'.example.org/target?source=external';
    $redirect = redirectRule(['source_path' => 'fa/external', 'destination_path' => $destination]);

    expect($redirect->destination_path)->toBe($destination);
    $this->get('/fa/external')->assertRedirect($destination);
});

it('places a forwarded query before an external fragment', function (): void {
    redirectRule(['source_path' => 'fa/a', 'destination_path' => 'https://example.org/target#section']);

    $this->get('/fa/a?from=site')->assertRedirect('https://example.org/target?from=site#section');
});

it('detects loops', function (): void {
    redirectRule(['source_path' => 'fa/b', 'destination_path' => 'fa/a']);

    expect(app(RedirectResolver::class)->finalDestination('fa/a', 'fa/b'))->toBeNull();
});

it('does not retain redirects created in a rolled back transaction', function (): void {
    $resolver = app(RedirectResolver::class);
    expect($resolver->match('fa/temporary'))->toBeNull();
    DB::beginTransaction();
    redirectRule(['source_path' => 'fa/temporary', 'destination_path' => 'fa/about']);
    expect($resolver->match('fa/temporary'))->not->toBeNull();
    DB::rollBack();

    expect($resolver->match('fa/temporary'))->toBeNull();
});

it('invalidates the redirect map repopulated before a transaction commits', function (): void {
    $resolver = app(RedirectResolver::class);
    DB::beginTransaction();
    redirectRule(['source_path' => 'fa/new-rule', 'destination_path' => 'fa/about']);
    Cache::forever(Redirect::CACHE_KEY, ['exact' => [], 'regex' => []]);
    expect($resolver->match('fa/new-rule'))->not->toBeNull();
    DB::commit();

    expect($resolver->match('fa/new-rule'))->not->toBeNull();
});

it('creates a 301 automatically when a published post slug changes', function (): void {
    $post = Post::factory()->create(['status' => PublicationStatus::Published, 'published_at' => now()->subDay()]);
    $post->setTranslations(['fa' => ['title' => 'عنوان', 'slug' => 'نامک-قدیمی', 'content' => '<p>x</p>']]);

    $post->translations()->where('locale', 'fa')->first()->update(['slug' => 'نامک-جدید']);

    $rule = Redirect::query()->where('origin', 'auto')->first();
    expect($rule)->not->toBeNull()
        ->and($rule->source_path)->toBe('fa/insights/نامک-قدیمی')
        ->and($rule->destination_path)->toBe('fa/insights/نامک-جدید');

    $this->get('/fa/insights/نامک-قدیمی')->assertStatus(301);

    // Renaming back removes the rule that would now capture the live URL.
    $post->translations()->where('locale', 'fa')->first()->update(['slug' => 'نامک-قدیمی']);
    expect(Redirect::query()->where('source_path', 'fa/insights/نامک-قدیمی')->exists())->toBeFalse();
});

it('does not create slug redirects for drafts', function (): void {
    $post = Post::factory()->create(['status' => PublicationStatus::Draft, 'published_at' => null]);
    $post->setTranslations(['fa' => ['title' => 'پیش‌نویس', 'slug' => 'a', 'content' => '<p>x</p>']]);
    $post->translations()->first()->update(['slug' => 'b']);

    expect(Redirect::query()->count())->toBe(0);
});

it('logs 404s once per path with hits, and skips asset/scanner noise', function (): void {
    $this->get('/fa/no-such-page', ['Referer' => 'https://ref.example/x'])->assertNotFound();
    $this->get('/fa/no-such-page')->assertNotFound();
    $this->get('/fa/wp-login.php')->assertNotFound();
    $this->get('/fa/.env')->assertNotFound();
    $this->get('/fa/missing.png')->assertNotFound();

    expect(NotFoundLog::query()->count())->toBe(1);
    $log = NotFoundLog::query()->first();
    expect($log->path)->toBe('fa/no-such-page')->and($log->hits)->toBe(2)->and($log->referrer)->toBe('https://ref.example/x');
});

it('imports redirects from CSV (headers from WordPress plugins too)', function (): void {
    $csv = tempnam(sys_get_temp_dir(), 'csv');
    file_put_contents($csv, "\xEF\xBB\xBFsource,target,code,regex\n/fa/one,/fa/about,301,0\nfa/two,,410,0\n,/x,301,0\n^old/(.*)$,fa/$1,302,1\n");

    [$created, $updated, $skipped] = ListRedirects::import($csv);

    expect([$created, $updated, $skipped])->toBe([3, 0, 1])
        ->and(Redirect::query()->where('source_path', 'fa/two')->value('status_code'))->toBe(410)
        ->and(Redirect::query()->where('match_type', 'regex')->value('destination_path'))->toBe('fa/$1');
});

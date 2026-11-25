<?php

declare(strict_types=1);

use App\Jobs\OptimizeImage;
use App\Models\Media;
use App\Models\Project;
use App\Services\ImageOptimizer;
use App\Services\MediaTransformer;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

beforeEach(function (): void {
    Storage::fake('public');
});

it('serves only the uploaded file even when smaller legacy variants exist', function (): void {
    $disk = Storage::disk('public');
    $disk->put('projects/seo-cover.avif', 'original optimized by the editor');
    $disk->put('projects/seo-cover-400.avif', 'smaller');
    $disk->put('projects/seo-cover-400.webp', 'smaller');

    $media = MediaTransformer::make('projects/seo-cover.avif', 'SEO alt', 'project.cover');

    expect($media)->toBe([
        'src' => '/storage/projects/seo-cover.avif',
        'alt' => 'SEO alt',
        'width' => 448,
        'height' => 448,
    ]);

    $html = view('partials.content-image', ['image' => $media])->render();
    expect($html)->toContain('src="/storage/projects/seo-cover.avif"', 'alt="SEO alt"')
        ->not->toContain('srcset=', '<source', '-400.');
});

it('preserves remote image URLs without alternatives', function (): void {
    $media = MediaTransformer::make('https://cdn.example.com/cover.png', 'Remote');

    expect($media['src'])->toBe('https://cdn.example.com/cover.png')
        ->and($media)->not->toHaveKey('srcset')
        ->and($media)->not->toHaveKey('webp')
        ->and($media)->not->toHaveKey('avif');
});

it('uses the same original project image in initial HTML and browser data', function (string $userAgent): void {
    config(['inertia.ssr.enabled' => false]);
    $path = 'projects/seo-cover.avif';
    Storage::disk('public')->put($path, 'editor-prepared upload');
    Storage::disk('public')->put('projects/seo-cover-400.avif', 'legacy variant');
    $project = Project::factory()->published()->create(['cover_path' => $path]);
    $project->setTranslations(['fa' => ['title' => 'پروژه', 'slug' => 'original-cover', 'cover_alt' => 'عکس اصلی']]);

    $home = \App\Models\Page::factory()->create(['key' => 'home']);
    $home->sections()->create(['type' => \App\Enums\SectionType::ProjectsShowcase, 'is_visible' => true]);
    $response = $this->get('/fa', ['User-Agent' => $userAgent])->assertOk();
    $html = $response->getContent();
    $document = new DOMDocument;
    @$document->loadHTML('<?xml encoding="UTF-8">'.$html);
    $xpath = new DOMXPath($document);
    $page = $response->viewData('page');

    expect($xpath->query('//main//img[@src="/storage/projects/seo-cover.avif"]')->length)->toBe(1)
        ->and($xpath->query('//main//img[contains(@srcset, "seo-cover-")]')->length)->toBe(0)
        ->and($page['props']['projects'][0]['image']['src'])->toBe('/storage/projects/seo-cover.avif')
        ->and($page['props']['projects'][0]['image'])->not->toHaveKey('srcset');
})->with(['Mozilla/5.0', 'Mozilla/5.0 Chrome-Lighthouse']);

it('does not queue image processing when media or project images are saved', function (): void {
    Queue::fake();
    $path = 'projects/seo-cover.png';
    Storage::disk('public')->put($path, 'prepared image bytes');
    $media = Media::create(['path' => $path, 'name' => 'seo-cover.png', 'mime' => 'image/png', 'type' => 'image', 'size' => 20]);
    $project = Project::factory()->create(['cover_path' => $path]);
    $media->update(['path' => 'projects/new-cover.png']);
    $project->update(['cover_path' => 'projects/new-cover.png']);
    $project->update(['cover_path' => null]);

    Queue::assertNotPushed(OptimizeImage::class);
    expect(Storage::disk('public')->allFiles())->toBe([$path])
        ->and(Storage::disk('public')->get($path))->toBe('prepared image bytes');
});

it('keeps uploads and legacy files untouched when old jobs or the old command run', function (): void {
    $disk = Storage::disk('public');
    $files = [
        'projects/cover.png' => 'new upload',
        'projects/previous.png' => 'previous upload',
        'projects/previous-400.webp' => 'legacy file',
    ];
    foreach ($files as $path => $bytes) {
        $disk->put($path, $bytes);
    }

    (new OptimizeImage('projects/cover.png', 'projects/previous.png'))->handle();
    expect(ImageOptimizer::optimize('projects/cover.png'))->toBe([]);
    $this->artisan('sizdah:optimize-images')
        ->expectsOutputToContain('Automatic image generation is disabled')
        ->assertSuccessful();

    expect($disk->allFiles())->toHaveCount(count($files));
    foreach ($files as $path => $bytes) {
        expect($disk->get($path))->toBe($bytes);
    }
});

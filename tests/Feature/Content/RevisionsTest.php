<?php

declare(strict_types=1);

use App\Enums\PublicationStatus;
use App\Models\ContentRevision;
use App\Models\Post;

function revisedPost(): Post
{
    $post = Post::factory()->create(['status' => PublicationStatus::Draft, 'published_at' => null]);
    $post->setTranslations(['fa' => ['title' => 'نسخه ۱', 'slug' => 'r', 'content' => '<p>متن اول</p>']]);

    return $post;
}

it('snapshots the previous text on every edit, not on create', function (): void {
    $post = revisedPost();
    expect(ContentRevision::count())->toBe(0);

    $post->setTranslations(['fa' => ['title' => 'نسخه ۲', 'content' => '<p>متن دوم</p>']]);

    $rev = $post->revisions()->first();
    expect($rev->data['title'])->toBe('نسخه ۱')
        ->and($rev->data['content'])->toBe('<p>متن اول</p>')
        ->and($rev->locale)->toBe('fa');
});

it('ignores edits that do not touch tracked text (e.g. slug only)', function (): void {
    $post = revisedPost();
    $post->setTranslations(['fa' => ['slug' => 'r2']]);

    expect(ContentRevision::count())->toBe(0);
});

it('restores a revision and keeps the current text as a new revision', function (): void {
    $post = revisedPost();
    $post->setTranslations(['fa' => ['title' => 'نسخه ۲', 'content' => '<p>متن دوم</p>']]);

    $post->revisions()->first()->restore();

    $post->load('translations');
    expect($post->getTranslation('title', 'fa'))->toBe('نسخه ۱')
        ->and($post->revisions()->count())->toBe(2)
        ->and($post->revisions()->first()->data['title'])->toBe('نسخه ۲');
});

it('keeps only the newest KEEP revisions', function (): void {
    $post = revisedPost();
    foreach (range(1, ContentRevision::KEEP + 5) as $i) {
        $post->setTranslations(['fa' => ['title' => "ویرایش {$i}"]]);
    }

    expect($post->revisions()->count())->toBe(ContentRevision::KEEP);
});

it('estimates reading time from Persian words (not Latin-only str_word_count)', function (): void {
    $post = Post::factory()->create();
    $post->setTranslations(['fa' => ['title' => 'ز', 'slug' => 'z', 'content' => '<p>'.str_repeat('کلمه ', 450).'</p>']]);

    expect($post->fresh()->calculateReadingMinutes('fa'))->toBe(3);
});

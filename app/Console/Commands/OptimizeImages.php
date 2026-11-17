<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Client;
use App\Models\PageSection;
use App\Models\Post;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\SectionItem;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Services\ImageOptimizer;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

/** Backfills responsive variants for images uploaded before the optimizer existed. */
final class OptimizeImages extends Command
{
    protected $signature = 'sizdah:optimize-images';

    protected $description = 'Generate high-quality responsive WebP/AVIF variants for every uploaded image';

    /** @var list<class-string<Model>> */
    private const MODELS = [
        Client::class, PageSection::class, Post::class, Project::class, ProjectImage::class,
        SectionItem::class, Service::class, TeamMember::class, Testimonial::class,
    ];

    public function handle(): int
    {
        if (! ImageOptimizer::isSupported()) {
            $this->error('The PHP GD extension (with WebP/AVIF support) is required.');

            return self::FAILURE;
        }

        $paths = [];
        foreach (self::MODELS as $class) {
            $model = new $class;
            $columns = (fn (): array => $this->optimizedImages)->call($model);
            $query = method_exists($model, 'bootSoftDeletes') ? $class::withTrashed() : $class::query();

            foreach ($query->get($columns) as $row) {
                foreach ($columns as $column) {
                    if (is_string($row->{$column}) && $row->{$column} !== '') {
                        $paths[$row->{$column}] = true;
                    }
                }
            }
        }

        $bar = $this->output->createProgressBar(count($paths));
        $written = 0;
        foreach (array_keys($paths) as $path) {
            $written += count(ImageOptimizer::optimize($path));
            $bar->advance();
        }
        $bar->finish();

        $this->newLine();
        $this->info(sprintf('%d images processed, %d variants written.', count($paths), $written));

        return self::SUCCESS;
    }
}

<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\ImageOptimizer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

final class OptimizeImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public int $timeout = 300;

    public function __construct(public string $path, public ?string $previous = null) {}

    public function handle(): void
    {
        if ($this->previous !== null && $this->previous !== $this->path) {
            ImageOptimizer::purge($this->previous);
        }

        ImageOptimizer::optimize($this->path);
    }
}

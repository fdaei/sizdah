<?php

declare(strict_types=1);

namespace App\Jobs;

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
        // Old jobs may still be waiting during deployment. Leave uploads and
        // legacy files untouched; public delivery now uses the original file.
    }
}

<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

/** Preserve the legacy command name without generating replacement images. */
final class OptimizeImages extends Command
{
    protected $signature = 'sizdah:optimize-images';

    protected $description = 'Report the original-upload policy; no image variants are generated';

    public function handle(): int
    {
        $this->info('Automatic image generation is disabled. The site serves original uploads unchanged.');

        return self::SUCCESS;
    }
}

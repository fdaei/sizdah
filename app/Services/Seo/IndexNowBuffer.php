<?php

declare(strict_types=1);

namespace App\Services\Seo;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/** Collect committed URLs during a request and send each URL only once. */
final class IndexNowBuffer
{
    /** @var array<string, true> */
    private array $urls = [];

    private bool $scheduled = false;

    public function add(array $urls, string $key, string $host, string $keyLocation): void
    {
        foreach ($urls as $url) {
            $this->urls[$url] = true;
        }

        if ($this->scheduled) {
            return;
        }

        $this->scheduled = true;
        app()->terminating(function () use ($key, $host, $keyLocation): void {
            $urls = array_keys($this->urls);
            $this->urls = [];
            $this->scheduled = false;
            foreach (array_chunk($urls, 10000) as $chunk) {
                try {
                    $response = Http::timeout(5)->asJson()->post(IndexNow::ENDPOINT, [
                        'host' => $host,
                        'key' => $key,
                        'keyLocation' => $keyLocation,
                        'urlList' => $chunk,
                    ]);
                    if ($response->failed()) {
                        Log::warning('IndexNow rejected the submission', ['status' => $response->status(), 'url_count' => count($chunk)]);
                    }
                } catch (\Throwable $e) {
                    Log::info('IndexNow ping failed: '.$e->getMessage());
                }
            }
        });
    }
}

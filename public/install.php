<?php

/*
 * One-time installer for hosts without a terminal.
 * Open: https://yourdomain.com/install.php?token=63d6dacd33d5898654fa049b02678c67
 * Deletes itself after a fully successful run. Delete it manually otherwise.
 */

declare(strict_types=1);

const INSTALL_TOKEN = '63d6dacd33d5898654fa049b02678c67';

if (! hash_equals(INSTALL_TOKEN, (string) ($_GET['token'] ?? ''))) {
    http_response_code(404);
    exit;
}

@set_time_limit(300);
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-store');

$base = dirname(__DIR__);
$log = [];
$ok = true;

$step = function (string $title, callable $fn) use (&$log, &$ok): void {
    try {
        $out = (string) $fn();
        $log[] = ['ok', $title, $out];
    } catch (Throwable $e) {
        $ok = false;
        $log[] = ['fail', $title, $e->getMessage()];
    }
};

// 1. Pre-flight checks (before Laravel boots).
if (! is_file($base.'/.env')) {
    exit('<p style="color:red">فایل .env پیدا نشد. اول .env.example را به .env تغییر نام دهید و پر کنید.</p>');
}
foreach (['storage', 'storage/framework/cache/data', 'storage/framework/sessions', 'storage/framework/views', 'storage/logs', 'bootstrap/cache'] as $dir) {
    $path = $base.'/'.$dir;
    if (! is_dir($path)) {
        @mkdir($path, 0775, true);
    }
    if (! is_writable($path)) {
        exit('<p style="color:red">پوشه قابل نوشتن نیست: '.htmlspecialchars($dir).' — دسترسی را روی 775 بگذارید.</p>');
    }
}

// 2. Boot Laravel.
require $base.'/vendor/autoload.php';
$app = require $base.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$artisan = function (string $command, array $params = []) use ($kernel): string {
    $code = $kernel->call($command, $params);
    $out = trim($kernel->output());
    if ($code !== 0) {
        throw new RuntimeException("exit {$code}\n{$out}");
    }

    return $out;
};

$step('clear old caches', fn () => $artisan('optimize:clear'));

$step('APP_KEY', function () use ($artisan) {
    if (filled(config('app.key'))) {
        return 'already set';
    }

    return $artisan('key:generate', ['--force' => true]);
});

$step('database connection', function () {
    Illuminate\Support\Facades\DB::connection()->getPdo();

    return 'connected: '.config('database.default');
});

$step('migrate', fn () => $artisan('migrate', ['--force' => true]));

$step('storage link', function () use ($base) {
    $link = $base.'/public/storage';
    $target = $base.'/storage/app/public';
    if (is_link($link) || is_dir($link)) {
        return 'already exists';
    }
    if (function_exists('symlink') && @symlink($target, $link)) {
        return 'symlink created';
    }
    throw new RuntimeException('symlink() روی این هاست غیرفعال است. از پشتیبانی هاست بخواهید symlink را بسازند: public/storage → storage/app/public');
});

$step('optimize (config/route/view cache)', fn () => $artisan('optimize'));
$step('filament component cache', fn () => $artisan('filament:cache-components'));

?><!doctype html>
<html lang="fa" dir="rtl"><head><meta charset="utf-8"><title>نصب</title>
<style>body{font-family:tahoma,sans-serif;max-width:800px;margin:2rem auto;padding:0 16px}pre{background:#f4f4f4;padding:8px;white-space:pre-wrap;direction:ltr;text-align:left}.ok{color:green}.fail{color:#c00}</style>
</head><body>
<h1>نصب سیزده</h1>
<?php foreach ($log as [$status, $title, $out]): ?>
    <h3 class="<?= $status ?>"><?= $status === 'ok' ? '✔' : '✘' ?> <?= htmlspecialchars($title) ?></h3>
    <?php if ($out !== ''): ?><pre><?= htmlspecialchars($out) ?></pre><?php endif; ?>
<?php endforeach; ?>
<?php if ($ok): @unlink(__FILE__); ?>
    <p class="ok"><b>همه‌چیز انجام شد.</b> <?= is_file(__FILE__) ? '<span class="fail">فایل install.php را دستی پاک کنید.</span>' : 'فایل install.php خودش پاک شد.' ?></p>
<?php else: ?>
    <p class="fail"><b>یک یا چند مرحله خطا داد.</b> مشکل را برطرف کنید و دوباره همین صفحه را باز کنید.</p>
<?php endif; ?>
</body></html>

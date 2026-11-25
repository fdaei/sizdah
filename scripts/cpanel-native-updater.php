<?php

declare(strict_types=1);

// Release-specific installer. The release ZIP is uploaded separately.
const RELEASE_SHA256 = '__RELEASE_SHA256__';
const INSTALL_PASSWORD_HASH = '__INSTALL_PASSWORD_HASH__';
const RELEASE_NAMES = ['sizdah-native-php-20261125.zip', 'sizdah-cpanel.zip'];

header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('X-Robots-Tag: noindex, nofollow');
header('X-Content-Type-Options: nosniff');
function escape(string $value): string { return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function fail(string $message): never { throw new RuntimeException($message); }
function regularPath(string $file, string $root): void {
    if (!str_starts_with($file, $root.'/')) fail('مسیر مقصد معتبر نیست.');
    $parent = $file;
    while ($parent !== $root) {
        if (is_link($parent)) fail('لینک در مسیر جایگزینی مجاز نیست: '.basename($file));
        $parent = dirname($parent);
    }
    if (file_exists($file) && !is_file($file)) fail('مقصد فایل معمولی نیست: '.basename($file));
}
function writeStream($source, string $file, int $size, int $mode = 0644): void {
    $dir = dirname($file);
    if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) fail('ساخت پوشه ممکن نشد.');
    $temp = tempnam($dir, '.sizdah-install-');
    if (!$temp) fail('ساخت فایل موقت ممکن نشد.');
    try {
        $out = fopen($temp, 'wb');
        if (!$out) fail('نوشتن فایل ممکن نشد.');
        $bytes = stream_copy_to_stream($source, $out);
        fclose($out);
        if ($bytes !== $size) fail('فایل استخراج‌شده ناقص است.');
        if (!chmod($temp, $mode) || !rename($temp, $file)) fail('جایگزینی فایل ممکن نشد.');
        if (function_exists('opcache_invalidate') && pathinfo($file, PATHINFO_EXTENSION) === 'php') @opcache_invalidate($file, true);
    } finally { if (is_file($temp)) @unlink($temp); }
}
function restoreFiles(array $written, string $backupPath, array $modes): bool {
    $ok = true;
    $backup = new ZipArchive;
    if ($backup->open($backupPath) !== true) return false;
    foreach (array_reverse($written, true) as $file => $key) {
        try {
            $stat = $backup->statName($key);
            if ($stat === false) { if (is_file($file) && !unlink($file)) $ok = false; continue; }
            $stream = $backup->getStream($key);
            if (!$stream) { $ok = false; continue; }
            try { writeStream($stream, $file, $stat['size'], $modes[$file] ?? 0644); }
            finally { fclose($stream); }
        } catch (Throwable) { $ok = false; }
    }
    $backup->close();
    return $ok;
}
?><!doctype html><html lang="fa" dir="rtl"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>نصب نسخهٔ PHP سیزده</title><body style="font-family:tahoma;max-width:850px;margin:3em auto;padding:0 1em;line-height:2"><h2>نصب نسخهٔ PHP سیزده</h2><?php
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') { ?>
<p>فایل ZIP نسخهٔ جدید را کنار این فایل در <b>public_html</b> بگذارید. این ابزار فایل‌های سایت را جایگزین می‌کند و بکاپ فایل‌های تغییرکرده را خارج از پوشهٔ عمومی نگه می‌دارد. فایل .env، دیتابیس، تصاویر آپلودی، index.php و .htaccess فعلی حفظ می‌شوند.</p>
<form method="post"><label>رمز نصب: <input type="password" name="password" required autocomplete="off"></label><br><button type="submit">نصب نسخهٔ جدید و پاک‌سازی کش</button></form></body></html><?php exit;
}
if (!password_verify((string)($_POST['password'] ?? ''), INSTALL_PASSWORD_HASH)) {
    http_response_code(403); exit('<p>رمز نصب درست نیست.</p></body></html>');
}
@set_time_limit(0);
ignore_user_abort(true);
$written = []; $modes = []; $backupPath = null; $zip = null; $lock = null;
$committed = false; $rolledBack = false; $maintenance = null; $maintenanceToken = null;
register_shutdown_function(static function () use (&$committed, &$rolledBack, &$written, &$backupPath, &$modes, &$maintenance, &$maintenanceToken): void {
    if (!$committed && !$rolledBack && $backupPath && $written) {
        $ok = restoreFiles($written, $backupPath, $modes);
        error_log('Sizdah native installer shutdown rollback: '.($ok ? 'OK' : 'FAILED'));
        if (!$ok) return; // Keep maintenance active until the backup is checked.
    }
    if ($maintenance && is_file($maintenance) && file_get_contents($maintenance) === $maintenanceToken) @unlink($maintenance);
});
try {
    if (PHP_VERSION_ID < 80401 || !class_exists('ZipArchive')) fail('PHP 8.4.1 یا بالاتر و افزونه zip لازم است.');
    $public = realpath(__DIR__);
    if (!$public) fail('مسیر نصب مشخص نیست.');
    $home = dirname($public);
    if (basename($public) === 'public_html' && is_file($home.'/sizdah/.env')) $appDir = realpath($home.'/sizdah');
    elseif (basename($public) === 'public' && is_file($home.'/.env')) $appDir = realpath($home);
    else fail('نصب فعلی پیدا نشد؛ این فایل باید در public_html کنار سایت قرار بگیرد و پوشهٔ sizdah بیرون آن باشد.');
    if (!$appDir || is_link($appDir) || is_link($appDir.'/.env') || !is_file($appDir.'/artisan') || !is_file($appDir.'/bootstrap/app.php')) fail('مسیر برنامهٔ فعلی معتبر نیست.');
    // The backup directory must be outside the public document root.
    $privateRoot = basename($public) === 'public' ? dirname($appDir) : $home;
    $backupRoot = $privateRoot.'/sizdah-deploy-backups';
    if (is_link($backupRoot) || (!is_dir($backupRoot) && !mkdir($backupRoot, 0700, true))) fail('ساخت پوشهٔ بکاپ ممکن نشد.');
    $lockPath = $backupRoot.'/.native-install.lock';
    if (is_link($lockPath)) fail('مسیر قفل نصب معتبر نیست.');
    $lock = fopen($lockPath, 'c');
    if (!$lock || !flock($lock, LOCK_EX | LOCK_NB)) fail('یک نصب دیگر در حال اجراست.');
    $archive = null;
    foreach (array_unique([$public, $home]) as $dir) foreach (RELEASE_NAMES as $name) {
        $candidate = $dir.'/'.$name;
        if (!is_link($candidate) && is_file($candidate) && hash_equals(RELEASE_SHA256, hash_file('sha256', $candidate))) { $archive = $candidate; break 2; }
    }
    if (!$archive) fail('ZIP معتبر پیدا نشد. بستهٔ sizdah-native-php-20261125.zip را بدون Extract کنار فایل نصب قرار دهید. بستهٔ ناقص یا نسخهٔ متفاوت پذیرفته نمی‌شود.');
    $zip = new ZipArchive;
    if ($zip->open($archive, ZipArchive::CHECKCONS) !== true) fail('ZIP باز نشد یا ناقص است.');
    $entries = []; $seen = []; $totalSize = 0; $backupSize = 0;
    for ($i = 0; $i < $zip->numFiles; $i++) {
        $name = $zip->getNameIndex($i);
        if (!is_string($name) || str_contains($name, "\0") || str_contains($name, '\\') || str_starts_with($name, '/') || preg_match('~(^|/)\.\.?(/|$)~', $name)) fail('مسیر نامعتبر در ZIP.');
        if (str_ends_with($name, '/')) continue;
        $os = 0; $attr = 0;
        if ($zip->getExternalAttributesIndex($i, $os, $attr) && $os === ZipArchive::OPSYS_UNIX && (($attr >> 16) & 0170000) === 0120000) fail('لینک داخل ZIP مجاز نیست.');
        if (str_starts_with($name, 'public/')) {
            $relative = substr($name, 7);
            if (preg_match('~^(storage(?:/|$)|index\.php$|\.htaccess$|hot$|install\.php$)~', $relative)) continue;
            $file = $public.'/'.$relative; $root = $public; $key = 'public/'.$relative;
        } elseif (preg_match('~^(app|bootstrap|config|database|lang|resources|routes|vendor)/~', $name) || in_array($name, ['artisan', 'composer.json', 'composer.lock'], true)) {
            if (str_starts_with($name, 'bootstrap/cache/') || str_starts_with($name, 'bootstrap/ssr/') || preg_match('~\.(?:sqlite(?:-wal|-shm)?|sql)$~i', $name)) continue;
            $file = $appDir.'/'.$name; $root = $appDir; $key = 'app/'.$name;
        } else continue;
        regularPath($file, $root);
        if (isset($seen[$file])) fail('فایل تکراری در ZIP.');
        $seen[$file] = true;
        $stat = $zip->statIndex($i);
        if (!$stat || $stat['size'] > 100 * 1024 * 1024) fail('اندازهٔ فایل بسته معتبر نیست.');
        if (is_file($file) && filesize($file) === $stat['size'] && hash_equals(sprintf('%08x', $stat['crc']), hash_file('crc32b', $file))) continue;
        $entries[] = [$name, $file, $key, $stat['size']];
        $totalSize += $stat['size'];
        if (is_file($file)) $backupSize += filesize($file);
    }
    foreach (['app/View/PublicPage.php', 'resources/views/site/app.blade.php', 'public/build/manifest.json', 'vendor/autoload.php'] as $required) if ($zip->locateName($required) === false) fail('فایل ضروری نسخهٔ جدید در بسته نیست.');
    // Publish the new manifest after the assets and server templates.
    usort($entries, static fn ($a, $b) => ($a[0] === 'public/build/manifest.json' ? 1 : 0) <=> ($b[0] === 'public/build/manifest.json' ? 1 : 0));
    $caches = [];
    foreach ([$appDir.'/bootstrap/cache', $appDir.'/storage/framework/views'] as $cacheDir) {
        if (!is_dir($cacheDir) || is_link($cacheDir)) fail('پوشهٔ کش برنامه معتبر نیست.');
        foreach (new DirectoryIterator($cacheDir) as $cache) {
            if ($cache->isFile() && !$cache->isLink() && $cache->getExtension() === 'php') {
                $file = $cache->getPathname(); $caches[$file] = 'cache/'.substr($file, strlen($appDir) + 1); $backupSize += $cache->getSize();
            }
        }
    }
    if (is_file($public.'/hot')) { regularPath($public.'/hot', $public); $caches[$public.'/hot'] = 'cache/public/hot'; }
    $available = disk_free_space($backupRoot);
    if ($available !== false && $available < $backupSize + min($totalSize, 100 * 1024 * 1024) + 20 * 1024 * 1024) fail('فضای خالی برای نصب و بکاپ کافی نیست.');
    $runDir = $backupRoot.'/native-'.date('Ymd-His').'-'.bin2hex(random_bytes(4));
    if (!mkdir($runDir, 0700)) fail('ساخت پوشهٔ بکاپ نصب ممکن نشد.');
    $backupPath = $runDir.'/files.zip';
    $backup = new ZipArchive;
    if ($backup->open($backupPath, ZipArchive::CREATE | ZipArchive::EXCL) !== true) fail('ساخت بکاپ ممکن نشد.');
    $destinations = [];
    foreach ($entries as [$name, $file, $key]) $destinations[$file] = $key;
    foreach ($caches as $file => $key) $destinations[$file] = $key;
    foreach ($destinations as $file => $key) if (is_file($file)) {
        $modes[$file] = fileperms($file) & 0777;
        if (!$backup->addFile($file, $key)) fail('افزودن فایل به بکاپ ممکن نشد.');
    }
    if (!$backup->close()) fail('ذخیرهٔ بکاپ شکست خورد.');
    chmod($backupPath, 0600);
    $manifestPath = $runDir.'/restore-map.json';
    if (file_put_contents($manifestPath, json_encode(['files' => $destinations, 'modes' => $modes], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)) === false) fail('ذخیرهٔ مسیرهای بازیابی ممکن نشد.');
    chmod($manifestPath, 0600);
    $down = $appDir.'/storage/framework/down';
    regularPath($down, $appDir);
    if (!file_exists($down)) {
        $maintenance = $down;
        $maintenanceToken = json_encode(['time' => time(), 'retry' => 60, 'refresh' => 10, 'status' => 503]);
        if (file_put_contents($down, $maintenanceToken, LOCK_EX) !== strlen($maintenanceToken)) fail('فعال‌کردن وضعیت نصب ممکن نشد.');
    }
    foreach ($entries as [$name, $file, $key, $size]) {
        $stream = $zip->getStream($name);
        if (!$stream) fail('خواندن فایل ZIP ممکن نشد.');
        $written[$file] = $key;
        try { writeStream($stream, $file, $size); } finally { fclose($stream); }
    }
    foreach ($caches as $file => $key) {
        $written[$file] = $key;
        if (function_exists('opcache_invalidate')) @opcache_invalidate($file, true);
        if (!unlink($file)) fail('پاک‌سازی کش ممکن نشد.');
    }
    require $appDir.'/vendor/autoload.php';
    $app = require $appDir.'/bootstrap/app.php';
    $app->usePublicPath($public);
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    \App\Support\SiteSettings::flush();
    $committed = true;
    file_put_contents($runDir.'/installed.json', json_encode(['release_sha256' => RELEASE_SHA256, 'changed_files' => count($entries), 'installed_at' => date(DATE_ATOM)], JSON_PRETTY_PRINT));
    if ($maintenance && is_file($maintenance) && file_get_contents($maintenance) === $maintenanceToken) unlink($maintenance);
    echo '<p style="color:green">نسخهٔ PHP سیزده نصب شد و کش‌ها پاک شدند. دیتابیس و فایل‌های آپلودی حفظ شدند.</p><p>بکاپ فایل‌های جایگزین‌شده: <code dir="ltr">'.escape($backupPath).'</code></p>';
    if (str_starts_with($archive, $public.'/')) {
        if (!@rename($archive, $runDir.'/release.zip')) echo '<p>فایل ZIP آپلودشده را از public_html حذف کنید.</p>';
    }
    if (@unlink(__FILE__)) echo '<p>فایل نصب حذف شد.</p>'; else echo '<p>این فایل نصب را از public_html حذف کنید.</p>';
    echo '<p><a href="/fa">مشاهدهٔ سایت</a> — <a href="/admin">ورود به پنل</a></p>';
} catch (Throwable $error) {
    http_response_code(500);
    $restored = !$written || ($backupPath && restoreFiles($written, $backupPath, $modes));
    $rolledBack = true;
    if (!$restored) $maintenanceToken = null; // Do not reopen a partially restored site.
    error_log('Sizdah native installation: '.$error->getMessage());
    echo '<p style="color:#a00">نصب کامل نشد: '.escape($error->getMessage()).'</p><p>'.($restored ? 'فایل‌های تغییرکرده بازیابی شدند؛ نسخهٔ قبلی حفظ شده است.' : 'بازیابی کامل نشد؛ وضعیت تعمیر نگه داشته شد. بکاپ فایل‌ها باید بررسی شود.').'</p>';
} finally {
    if ($zip instanceof ZipArchive) $zip->close();
    if (is_resource($lock)) { flock($lock, LOCK_UN); fclose($lock); }
}
?></body></html>

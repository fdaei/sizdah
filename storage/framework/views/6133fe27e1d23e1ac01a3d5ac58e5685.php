<?php
    /**
     * Server-rendered SEO head — the initial-HTML half of the SEO contract
     * built by App\Services\SeoBuilder (technical SEO audit 2026-10-04,
     * SEO-DEV-01/02/04).
     *
     * Rendered by Blade, not Vue, so crawlers get title, description,
     * canonical, robots, hreflang, Open Graph and JSON-LD even when the SSR
     * process is down or JavaScript never runs. Every tag carries an
     * `inertia="<key>"` attribute: on client-side navigations Inertia's head
     * manager replaces them with the identically keyed tags SeoHead.vue
     * renders, so the two never duplicate or drift apart.
     *
     * Pages without a `seo` prop are error pages: noindex, and a title only
     * when SSR did not already render one from Error.vue.
     */
    $seo        = $page['props']['seo'] ?? null;
    $alternates = $page['props']['alternates'] ?? null;
    $status     = $page['props']['status'] ?? null;
    $appName    = config('app.name');
    $locale     = app()->getLocale();
    $ssrHead    = isset($__inertiaSsrResponse) && $__inertiaSsrResponse;
    $schemaJson = isset($seo['schema'])
        ? json_encode($seo['schema'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG)
        : null;
?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($seo): ?>
    <title inertia><?php echo e($seo['title'] ? "{$seo['title']} — {$appName}" : $appName); ?></title>
    <meta name="description" content="<?php echo e($seo['description']); ?>" inertia="description">
    <link rel="canonical" href="<?php echo e($seo['canonical']); ?>" inertia="canonical">
    <meta name="robots" content="<?php echo e(($seo['noindex'] ?? false) ? 'noindex, follow' : 'index, follow'); ?>" inertia="robots">

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($alternates): ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $alternates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <link rel="alternate" hreflang="<?php echo e(config("locales.supported.{$code}.html_lang")); ?>" href="<?php echo e($url); ?>" inertia="hreflang-<?php echo e($code); ?>">
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <link rel="alternate" hreflang="x-default" href="<?php echo e($alternates[config('locales.default')] ?? reset($alternates)); ?>" inertia="hreflang-x-default">
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <meta property="og:type" content="<?php echo e($seo['type']); ?>" inertia="og:type">
    <meta property="og:title" content="<?php echo e($seo['title']); ?>" inertia="og:title">
    <meta property="og:description" content="<?php echo e($seo['description']); ?>" inertia="og:description">
    <meta property="og:url" content="<?php echo e($seo['canonical']); ?>" inertia="og:url">
    <meta property="og:site_name" content="<?php echo e(\App\Support\SiteSettings::get('seo_organization_name', $locale, 'Sizdah')); ?>" inertia="og:site_name">
    <meta property="og:locale" content="<?php echo e(str_replace('-', '_', config("locales.supported.{$locale}.html_lang"))); ?>" inertia="og:locale">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($seo['image']): ?>
        <meta property="og:image" content="<?php echo e($seo['image']); ?>" inertia="og:image">
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <meta name="twitter:card" content="<?php echo e($seo['image'] ? 'summary_large_image' : 'summary'); ?>" inertia="twitter:card">
    <meta name="twitter:title" content="<?php echo e($seo['title']); ?>" inertia="twitter:title">
    <meta name="twitter:description" content="<?php echo e($seo['description']); ?>" inertia="twitter:description">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($seo['image']): ?>
        <meta name="twitter:image" content="<?php echo e($seo['image']); ?>" inertia="twitter:image">
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($seo['type'] === 'article'): ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($seo['publishedAt'] ?? null): ?>
            <meta property="article:published_time" content="<?php echo e($seo['publishedAt']); ?>" inertia="article:published_time">
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($seo['modifiedAt'] ?? null): ?>
            <meta property="article:modified_time" content="<?php echo e($seo['modifiedAt']); ?>" inertia="article:modified_time">
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($seo['author'] ?? null): ?>
            <meta property="article:author" content="<?php echo e($seo['author']); ?>" inertia="article:author">
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($schemaJson): ?>
        <script type="application/ld+json" inertia="schema"><?php echo $schemaJson; ?></script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php else: ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $ssrHead): ?>
        <title inertia><?php echo e($status ? __('errors.'.(in_array($status, [403, 404, 429, 500, 503], true) ? $status : 500).'.title').' — '.$appName : $appName); ?></title>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <meta name="robots" content="noindex, follow" inertia="robots">
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH /home/fdaei/project/my/sizdah/resources/views/partials/seo.blade.php ENDPATH**/ ?>

<?php
    $verify = [
        'google-site-verification' => \App\Support\SiteSettings::get('seo_verify_google'),
        'msvalidate.01' => \App\Support\SiteSettings::get('seo_verify_bing'),
        'yandex-verification' => \App\Support\SiteSettings::get('seo_verify_yandex'),
        'baidu-site-verification' => \App\Support\SiteSettings::get('seo_verify_baidu'),
    ];
?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $verify; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($code)): ?>
        <meta name="<?php echo e($name); ?>" content="<?php echo e($code); ?>">
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php echo \App\Support\SiteSettings::get('seo_head_code') ?: ''; ?>

<?php /**PATH /home/fdaei/Downloads/sizdah/resources/views/partials/seo-site.blade.php ENDPATH**/ ?>
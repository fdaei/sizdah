
<?php ($large = $network === 'og' || $card === 'summary_large_image'); ?>
<div dir="<?php echo e($dir); ?>" style="max-width:500px; overflow:hidden; border:1px solid #dadde1; border-radius:10px; background:#fff; color:#1d2129; font-family:Helvetica, Arial, Tahoma, sans-serif">
    <div style="<?php echo e($large ? '' : 'display:flex'); ?>">
        <div style="<?php echo e($large ? 'width:100%; aspect-ratio:1.91/1' : 'width:125px; height:125px; flex-shrink:0'); ?>; background:#f0f2f5 center/cover no-repeat; <?php echo e($image ? "background-image:url('".e($image)."')" : ''); ?>; display:flex; align-items:center; justify-content:center; font-size:12px; color:#9ca3af">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($image)): ?> تصویر پیش‌فرض سایت (Default image) <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <div style="padding:10px 12px; <?php echo e($network === 'og' ? 'background:#f2f3f5' : ''); ?>">
            <div dir="ltr" style="font-size:12px; text-transform:uppercase; color:#606770"><?php echo e($host); ?></div>
            <div style="font-size:16px; font-weight:600; margin-top:2px"><?php echo e(\Illuminate\Support\Str::limit($title, 90)); ?></div>
            <div style="font-size:14px; color:#606770; margin-top:2px"><?php echo e(\Illuminate\Support\Str::limit($description, 160)); ?></div>
        </div>
    </div>
</div>
<?php /**PATH /home/fdaei/Downloads/sizdah/resources/views/filament/seo/social-preview.blade.php ENDPATH**/ ?>
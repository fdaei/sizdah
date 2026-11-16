<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($image ?? null): ?>
    <img src="<?php echo e($image['src']); ?>" alt="<?php echo e($image['alt']); ?>"
         width="<?php echo e($image['width']); ?>" height="<?php echo e($image['height']); ?>"
         class="my-6 h-auto max-w-full rounded-sm" loading="lazy">
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH /home/fdaei/Downloads/phc-backend/resources/views/partials/content-image.blade.php ENDPATH**/ ?>
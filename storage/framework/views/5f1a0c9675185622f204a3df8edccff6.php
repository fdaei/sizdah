<article class="my-8 space-y-3">
    <h2 class="text-title-lg text-ink-50"><a href="<?php echo e($card['url']); ?>"><?php echo e($card['title']); ?></a></h2>
    <?php echo $__env->make('partials.content-image', ['image' => $card['image'] ?? null], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($card['excerpt'] ?? ''): ?>
        <p><?php echo e($card['excerpt']); ?></p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($card['publishedAtIso'] ?? ''): ?>
        <time datetime="<?php echo e($card['publishedAtIso']); ?>"><?php echo e($card['publishedAt']); ?></time>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</article>
<?php /**PATH /home/fdaei/Downloads/phc-backend/resources/views/partials/content-card.blade.php ENDPATH**/ ?>
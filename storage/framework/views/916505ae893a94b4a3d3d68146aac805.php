<?php ($hash = \App\Filament\Support\Editor\Blocks\AparatBlock::hash((string) ($url ?? ''))); ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hash): ?>
    <figure class="rich-embed" data-embed="aparat">
        <iframe src="https://www.aparat.com/video/video/embed/videohash/<?php echo e($hash); ?>/vt/frame"
                title="<?php echo e($title ?? 'ویدیو آپارات'); ?>" loading="lazy" allowfullscreen
                style="aspect-ratio:16/9; width:100%; border:0"></iframe>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($title)): ?><figcaption><?php echo e($title); ?></figcaption><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </figure>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH /home/fdaei/Downloads/sizdah/resources/views/tiptap/blocks/aparat.blade.php ENDPATH**/ ?>
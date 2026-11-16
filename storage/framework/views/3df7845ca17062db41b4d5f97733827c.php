<?php
    $rating = \App\Models\SeoMeta::rating($score);
    $dot = ['good' => '#16a34a', 'ok' => '#ea580c', 'bad' => '#dc2626', 'none' => '#9ca3af'];
    $groups = ['bad' => 'مشکلات (Problems)', 'ok' => 'پیشنهادها (Improvements)', 'good' => 'نتایج خوب (Good results)'];
?>
<div dir="rtl" style="border:1px solid rgba(156,163,175,.35); border-radius:10px; padding:16px">
    <div style="display:flex; align-items:center; gap:8px; font-weight:600; margin-bottom:12px">
        <span style="display:inline-block; width:12px; height:12px; border-radius:50%; background:<?php echo e($dot[$rating]); ?>"></span>
        <?php echo e($heading); ?>

        <span style="margin-inline-start:auto; border-radius:6px; padding:2px 8px; font-size:12px; color:#fff; background:<?php echo e($dot[$rating]); ?>" dir="ltr"><?php echo e($score); ?>/100</span>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php ($items = array_filter($checks, fn ($c) => $c['status'] === $status)); ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($items): ?>
            <div style="font-size:12px; font-weight:500; opacity:.65; margin-bottom:6px"><?php echo e($label); ?> (<?php echo e(count($items)); ?>)</div>
            <ul style="list-style:none; margin:0 0 12px; padding:0; font-size:14px">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li style="display:flex; align-items:flex-start; gap:8px; margin-bottom:6px">
                        <span style="margin-top:6px; flex-shrink:0; width:10px; height:10px; border-radius:50%; background:<?php echo e($dot[$status]); ?>"></span>
                        <span><?php echo e($c['text']); ?></span>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </ul>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH /home/fdaei/Downloads/sizdah/resources/views/filament/seo/analysis-panel.blade.php ENDPATH**/ ?>
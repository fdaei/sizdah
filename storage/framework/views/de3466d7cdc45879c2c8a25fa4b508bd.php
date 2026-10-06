<div
    <?php echo e($attributes
            ->merge([
                'id' => $getId(),
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)); ?>

>
    <?php echo e($getChildComponentContainer()); ?>

</div>
<?php /**PATH /home/fdaei/workspace/mine/phc-frontend/vendor/filament/infolists/resources/views/components/grid.blade.php ENDPATH**/ ?>
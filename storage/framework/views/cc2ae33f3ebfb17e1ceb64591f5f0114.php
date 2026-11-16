
<?php ($tl = mb_strlen($title)); ?>
<?php ($dl = mb_strlen($description)); ?>
<div
    x-data="{
        mode: 'desktop', tw: 0, dw: 0,
        init() {
            const c = document.createElement('canvas').getContext('2d');
            c.font = '20px Arial, Tahoma, sans-serif'; this.tw = Math.round(c.measureText(<?php echo \Illuminate\Support\Js::from($title)->toHtml() ?>).width);
            c.font = '14px Arial, Tahoma, sans-serif'; this.dw = Math.round(c.measureText(<?php echo \Illuminate\Support\Js::from($description)->toHtml() ?>).width);
        },
        color(px, low, max) { return (px === 0 || px > max) ? '#dc2626' : (px < low ? '#ea580c' : '#16a34a') },
    }"
    style="display:flex; flex-direction:column; gap:12px"
>
    <div style="display:flex; gap:8px; font-size:12px">
        <button type="button" @click="mode='desktop'" :style="mode==='desktop' ? 'background:#2563eb;color:#fff' : 'background:#f3f4f6;color:#111'" style="border-radius:6px; padding:4px 10px">دسکتاپ (Desktop)</button>
        <button type="button" @click="mode='mobile'" :style="mode==='mobile' ? 'background:#2563eb;color:#fff' : 'background:#f3f4f6;color:#111'" style="border-radius:6px; padding:4px 10px">موبایل (Mobile)</button>
    </div>

    <div dir="<?php echo e($dir); ?>" :style="{ maxWidth: mode==='mobile' ? '360px' : '600px' }"
         style="border:1px solid #e5e7eb; border-radius:10px; background:#fff; padding:16px; font-family:Arial, Tahoma, sans-serif; box-shadow:0 1px 2px rgba(0,0,0,.05)">
        <div style="display:flex; align-items:center; gap:8px; font-size:12px; color:#202124; margin-bottom:4px">
            <span style="display:inline-block; width:26px; height:26px; border-radius:50%; background:#f1f3f4"></span>
            <span dir="ltr" style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap"><?php echo e($url); ?></span>
        </div>
        <div style="font-size:20px; line-height:1.3; color:#1a0dab; overflow:hidden"
             :style="mode==='desktop'
                ? 'max-width:580px; white-space:nowrap; text-overflow:ellipsis'
                : 'display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical'"><?php echo e($title ?: 'عنوان سئو'); ?></div>
        <div style="margin-top:4px; font-size:14px; line-height:1.58; color:#4d5156; overflow:hidden; display:-webkit-box; -webkit-box-orient:vertical"
             :style="{ WebkitLineClamp: mode==='mobile' ? 3 : 2 }"><?php echo e($description ?: 'توضیحات متا را وارد کنید؛ در غیر این صورت گوگل بخشی از متن صفحه را نمایش می‌دهد.'); ?></div>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:12px; font-size:12px">
        <div>
            <div style="display:flex; justify-content:space-between; margin-bottom:4px"><span>عنوان (Title): <?php echo e($tl); ?> / ۶۰ نویسه</span><span dir="ltr" x-text="tw + ' / 580px'"></span></div>
            <div style="height:8px; border-radius:4px; background:#e5e7eb; overflow:hidden"><div style="height:8px" :style="{ width: Math.min(100, tw/580*100) + '%', background: color(tw, 400, 580) }"></div></div>
        </div>
        <div>
            <div style="display:flex; justify-content:space-between; margin-bottom:4px"><span>توضیحات (Meta description): <?php echo e($dl); ?> / ۱۵۶ نویسه</span><span dir="ltr" x-text="dw + ' / 920px'"></span></div>
            <div style="height:8px; border-radius:4px; background:#e5e7eb; overflow:hidden"><div style="height:8px" :style="{ width: Math.min(100, dw/920*100) + '%', background: color(dw, 600, 920) }"></div></div>
        </div>
    </div>
</div>
<?php /**PATH /home/fdaei/Downloads/sizdah/resources/views/filament/seo/serp-preview.blade.php ENDPATH**/ ?>
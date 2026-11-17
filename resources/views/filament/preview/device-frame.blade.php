{{-- Device-switching preview. Inline styles: the panel theme doesn't compile custom utilities. --}}
@php
    $devices = [
        'mobile' => ['label' => 'موبایل', 'width' => 375, 'height' => 812],
        'tablet' => ['label' => 'تبلت', 'width' => 768, 'height' => 1024],
        'laptop' => ['label' => 'لپ‌تاپ', 'width' => 1280, 'height' => 800],
        'desktop' => ['label' => 'دسکتاپ', 'width' => 1440, 'height' => 900],
    ];
@endphp

<div
    x-data="{
        devices: @js($devices),
        device: 'desktop',
        scale: 1,
        fit() {
            const box = this.$refs.stage;
            if (! box) return;
            const d = this.devices[this.device];
            this.scale = Math.min(1, (box.clientWidth - 32) / d.width);
        },
        reload() { this.$refs.frame.src = this.$refs.frame.src; },
    }"
    x-init="$nextTick(() => fit()); new ResizeObserver(() => fit()).observe($refs.stage)"
    x-effect="device; $nextTick(() => fit())"
    style="display:flex; flex-direction:column; gap:12px"
>
    @if ($url === null)
        <p>این مورد صفحه‌ی عمومی (یا اسلاگ) ندارد؛ پیش‌نمایش در دسترس نیست.</p>
    @else
        <div style="display:flex; flex-wrap:wrap; align-items:center; gap:8px; justify-content:space-between">
            <div style="display:flex; flex-wrap:wrap; gap:6px">
                <template x-for="(d, key) in devices" :key="key">
                    <button
                        type="button"
                        x-on:click="device = key"
                        x-text="d.label + ' · ' + d.width"
                        :style="device === key
                            ? { background: 'rgb(var(--primary-600))', color: '#fff', borderColor: 'transparent' }
                            : { background: 'transparent', color: 'inherit', borderColor: 'rgba(156,163,175,.45)' }"
                        style="padding:6px 12px; border-radius:8px; border:1px solid rgba(156,163,175,.45); font-size:13px"
                    ></button>
                </template>
            </div>
            <div style="display:flex; gap:12px; font-size:13px">
                <button type="button" x-on:click="reload()" style="text-decoration:underline">بارگذاری مجدد</button>
                <a href="{{ $url }}" target="_blank" rel="noopener" style="text-decoration:underline">باز کردن در تب جدید</a>
            </div>
        </div>

        <div
            x-ref="stage"
            style="background:rgba(156,163,175,.15); border-radius:12px; padding:16px; overflow:hidden; display:flex; justify-content:center"
            :style="{ height: (devices[device].height * scale + 32) + 'px' }"
        >
            <div
                :style="{ width: devices[device].width * scale + 'px', height: devices[device].height * scale + 'px' }"
                style="flex:none"
            >
                <iframe
                    x-ref="frame"
                    src="{{ $url }}"
                    title="پیش‌نمایش"
                    loading="lazy"
                    :style="{ width: devices[device].width + 'px', height: devices[device].height + 'px', transform: 'scale(' + scale + ')', transformOrigin: 'top left' }"
                    style="border:0; border-radius:8px; background:#fff; box-shadow:0 4px 24px rgba(0,0,0,.18)"
                ></iframe>
            </div>
        </div>
    @endif
</div>

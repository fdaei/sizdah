{{-- Facebook / X card preview (inline styles: no Filament theme build). --}}
@php($large = $network === 'og' || $card === 'summary_large_image')
<div dir="{{ $dir }}" style="max-width:500px; overflow:hidden; border:1px solid #dadde1; border-radius:10px; background:#fff; color:#1d2129; font-family:Helvetica, Arial, Tahoma, sans-serif">
    <div style="{{ $large ? '' : 'display:flex' }}">
        <div style="{{ $large ? 'width:100%; aspect-ratio:1.91/1' : 'width:125px; height:125px; flex-shrink:0' }}; background:#f0f2f5 center/cover no-repeat; {{ $image ? "background-image:url('".e($image)."')" : '' }}; display:flex; align-items:center; justify-content:center; font-size:12px; color:#9ca3af">
            @unless ($image) تصویر پیش‌فرض سایت (Default image) @endunless
        </div>
        <div style="padding:10px 12px; {{ $network === 'og' ? 'background:#f2f3f5' : '' }}">
            <div dir="ltr" style="font-size:12px; text-transform:uppercase; color:#606770">{{ $host }}</div>
            <div style="font-size:16px; font-weight:600; margin-top:2px">{{ \Illuminate\Support\Str::limit($title, 90) }}</div>
            <div style="font-size:14px; color:#606770; margin-top:2px">{{ \Illuminate\Support\Str::limit($description, 160) }}</div>
        </div>
    </div>
</div>

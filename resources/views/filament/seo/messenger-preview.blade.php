{{-- LinkedIn / Telegram / WhatsApp link-card previews (inline styles: no Filament theme build). --}}
@php($t = \Illuminate\Support\Str::limit((string) $title, 70))
@php($d = \Illuminate\Support\Str::limit((string) $description, 110))
@php($bg = $image ? "background-image:url('".e($image)."')" : '')
<div dir="{{ $dir }}" style="display:grid; gap:12px; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); font-family:Helvetica, Arial, Tahoma, sans-serif">
    {{-- LinkedIn: large image, bold title, host --}}
    <div style="border:1px solid #e0e0e0; border-radius:8px; overflow:hidden; background:#fff; color:#000">
        <div style="font-size:11px; padding:4px 8px; color:#666">LinkedIn</div>
        <div style="aspect-ratio:1.91/1; background:#eef3f8 center/cover no-repeat; {{ $bg }}"></div>
        <div style="padding:8px 10px"><div style="font-size:14px; font-weight:600">{{ $t }}</div><div dir="ltr" style="font-size:12px; color:#666">{{ $host }}</div></div>
    </div>
    {{-- Telegram: accent bar, site name, title, description, image --}}
    <div style="border-radius:10px; background:#effdde; padding:8px 10px; color:#000">
        <div style="font-size:11px; color:#666; margin-bottom:4px">Telegram</div>
        <div style="border-inline-start:3px solid #4fae4e; padding-inline-start:8px">
            <div dir="ltr" style="font-size:12px; color:#4fae4e; font-weight:600">{{ $host }}</div>
            <div style="font-size:13px; font-weight:600">{{ $t }}</div>
            <div style="font-size:12px">{{ $d }}</div>
            <div style="margin-top:6px; aspect-ratio:1.91/1; border-radius:6px; background:#dfe7d6 center/cover no-repeat; {{ $bg }}"></div>
        </div>
    </div>
    {{-- WhatsApp: compact card, square thumbnail --}}
    <div style="border-radius:10px; background:#d9fdd3; padding:6px; color:#111">
        <div style="font-size:11px; color:#666; padding:2px 4px">WhatsApp</div>
        <div style="display:flex; gap:8px; background:#f0f2f5; border-radius:8px; overflow:hidden">
            <div style="width:72px; height:72px; flex-shrink:0; background:#dfe5e7 center/cover no-repeat; {{ $bg }}"></div>
            <div style="padding:6px 6px 6px 0"><div style="font-size:13px; font-weight:600">{{ $t }}</div><div style="font-size:12px; color:#555">{{ $d }}</div><div dir="ltr" style="font-size:11px; color:#888">{{ $host }}</div></div>
        </div>
    </div>
</div>

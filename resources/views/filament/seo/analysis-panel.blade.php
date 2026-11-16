@php
    $rating = \App\Models\SeoMeta::rating($score);
    $dot = ['good' => '#16a34a', 'ok' => '#ea580c', 'bad' => '#dc2626', 'none' => '#9ca3af'];
    $groups = ['bad' => 'مشکلات (Problems)', 'ok' => 'پیشنهادها (Improvements)', 'good' => 'نتایج خوب (Good results)'];
@endphp
<div dir="rtl" style="border:1px solid rgba(156,163,175,.35); border-radius:10px; padding:16px">
    <div style="display:flex; align-items:center; gap:8px; font-weight:600; margin-bottom:12px">
        <span style="display:inline-block; width:12px; height:12px; border-radius:50%; background:{{ $dot[$rating] }}"></span>
        {{ $heading }}
        <span style="margin-inline-start:auto; border-radius:6px; padding:2px 8px; font-size:12px; color:#fff; background:{{ $dot[$rating] }}" dir="ltr">{{ $score }}/100</span>
    </div>
    @foreach ($groups as $status => $label)
        @php($items = array_filter($checks, fn ($c) => $c['status'] === $status))
        @if ($items)
            <div style="font-size:12px; font-weight:500; opacity:.65; margin-bottom:6px">{{ $label }} ({{ count($items) }})</div>
            <ul style="list-style:none; margin:0 0 12px; padding:0; font-size:14px">
                @foreach ($items as $c)
                    <li style="display:flex; align-items:flex-start; gap:8px; margin-bottom:6px">
                        <span style="margin-top:6px; flex-shrink:0; width:10px; height:10px; border-radius:50%; background:{{ $dot[$status] }}"></span>
                        <span>{{ $c['text'] }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    @endforeach
</div>

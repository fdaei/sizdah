{{-- Read-only preview of a revision snapshot (inline styles: no theme utilities needed). --}}
<div dir="rtl" style="display:flex; flex-direction:column; gap:12px">
    @foreach (['title' => 'عنوان', 'subtitle' => 'زیرعنوان', 'excerpt' => 'خلاصه', 'description' => 'توضیحات'] as $key => $label)
        @if (filled($revision->data[$key] ?? null))
            <div><b>{{ $label }}:</b> {{ $revision->data[$key] }}</div>
        @endif
    @endforeach
    <div style="border:1px solid rgba(156,163,175,.35); border-radius:10px; padding:16px; max-height:60vh; overflow:auto; line-height:1.9">
        {{-- Admin-authored HTML from this site's own editor, shown to admins only. --}}
        {!! $revision->data['content'] ?? '<i>بدون متن</i>' !!}
    </div>
</div>

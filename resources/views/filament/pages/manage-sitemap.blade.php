<x-filament-panels::page>
    @if ($siteNoindex)
        <div class="rounded-xl border border-danger-300 bg-danger-50 p-4 text-sm text-danger-700">
            کل سایت در «تنظیمات سئو» روی noindex است؛ نقشه سایت خالی برگردانده می‌شود.
        </div>
    @endif

    <x-filament::section>
        <x-slot name="heading">آدرس نقشه سایت</x-slot>
        <div class="space-y-2 text-sm">
            <p>این آدرس را در <b>Google Search Console</b> ← Sitemaps و <b>Bing Webmaster Tools</b> ثبت کنید:</p>
            <p dir="ltr" class="font-mono"><a href="{{ $indexUrl }}" target="_blank" class="text-primary-600 underline">{{ $indexUrl }}</a></p>
            <p class="text-gray-500">این آدرس به‌صورت خودکار در <a href="{{ $robotsUrl }}" target="_blank" class="underline" dir="ltr">robots.txt</a> هم معرفی شده و با هر ذخیره‌ی محتوا به‌روز می‌شود.</p>
        </div>
    </x-filament::section>

    <x-filament::section>
        <x-slot name="heading">وضعیت فعلی</x-slot>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="text-start text-gray-500">
                    <th class="py-2 text-start">نوع محتوا</th><th class="py-2 text-start">وضعیت</th><th class="py-2 text-start">تعداد آدرس</th><th class="py-2 text-start">فایل</th>
                </tr></thead>
                <tbody>
                @foreach ($stats as $type => $row)
                    <tr class="border-t border-gray-200 dark:border-white/10">
                        <td class="py-2">{{ $labels[$type] }}</td>
                        <td class="py-2">
                            <x-filament::badge :color="$row['enabled'] ? 'success' : 'gray'">{{ $row['enabled'] ? 'فعال' : 'غیرفعال' }}</x-filament::badge>
                        </td>
                        <td class="py-2">{{ $row['count'] }}</td>
                        <td class="py-2" dir="ltr">
                            @if ($row['enabled'] && $row['count'] > 0)
                                @foreach ($row['files'] as $file)
                                    <a href="{{ $file }}" target="_blank" class="text-primary-600 underline">{{ basename($file) }}</a>
                                @endforeach
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </x-filament::section>

    <form wire:submit="save">
        {{ $this->form }}
        <div class="mt-6 flex justify-end">
            <x-filament::button type="submit">ذخیره (Save changes)</x-filament::button>
        </div>
    </form>
</x-filament-panels::page>

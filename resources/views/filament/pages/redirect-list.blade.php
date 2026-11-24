<x-filament-panels::page class="fi-resource-list-records-page">
    <div class="flex flex-col gap-y-6">
        <x-filament::section>
            <x-slot name="heading">ریدایرکت خودکار هر محتوا</x-slot>
            <x-slot name="description">
                برای هر مورد تعیین کنید پس از تغییر نامک، آدرس قبلی خودکار به آدرس جدید منتقل شود. این تنظیم برای همه زبان‌های همان مورد است و ریدایرکت‌های قبلی را تغییر نمی‌دهد.
                @if (! $this->automaticRedirectEnabled())
                    ریدایرکت خودکار سراسری در تنظیمات سئو غیرفعال است؛ ابتدا آن را فعال کنید.
                @endif
            </x-slot>
            <div class="grid gap-3 sm:grid-cols-2">
                <label class="text-sm">نوع محتوا
                    <select wire:model.live="contentType" class="mt-1 block w-full rounded-lg border-gray-300 text-sm dark:bg-gray-900 dark:border-gray-700">
                        @foreach (\App\Services\Redirects\SlugRedirector::CONTENT_TYPES as $key => [$label])
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="text-sm">جست‌وجوی نام
                    <input wire:model.live.debounce.400ms="contentSearch" type="search" class="mt-1 block w-full rounded-lg border-gray-300 text-sm dark:bg-gray-900 dark:border-gray-700" />
                </label>
            </div>
            @php($records = $this->contentRecords())
            <div class="mt-4 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($records as $record)
                    <div wire:key="redirect-record-{{ $contentType }}-{{ $record->getKey() }}" class="flex items-center justify-between gap-3 py-3">
                        <span class="text-sm font-medium">{{ $this->contentRecordLabel($record) }}</span>
                        @php($enabled = $this->contentRedirectEnabled($record->getKey()))
                        <x-filament::button size="sm" :color="$enabled ? 'success' : 'gray'"
                            wire:click="toggleContentRedirect('{{ $contentType }}', {{ $record->getKey() }})"
                            wire:loading.attr="disabled"
                            :aria-label="($enabled ? 'غیرفعال کردن ریدایرکت خودکار ' : 'فعال کردن ریدایرکت خودکار ') . $this->contentRecordLabel($record)"
                            :disabled="! (auth()->user()?->isAdmin() ?? false)">
                            {{ $enabled ? 'فعال' : 'غیرفعال' }}
                        </x-filament::button>
                    </div>
                @empty
                    <p class="py-4 text-sm text-gray-500">موردی پیدا نشد.</p>
                @endforelse
            </div>
            @if ($records->lastPage() > 1)
                <div class="mt-4 flex items-center justify-between gap-3 text-sm">
                    <x-filament::button size="sm" color="gray" wire:click="$set('contentPage', {{ max(1, $contentPage - 1) }})" :disabled="$contentPage <= 1">قبلی</x-filament::button>
                    <span>صفحه {{ $records->currentPage() }} از {{ $records->lastPage() }}</span>
                    <x-filament::button size="sm" color="gray" wire:click="$set('contentPage', {{ min($records->lastPage(), $contentPage + 1) }})" :disabled="$contentPage >= $records->lastPage()">بعدی</x-filament::button>
                </div>
            @endif
        </x-filament::section>

        <x-filament-panels::resources.tabs />
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_BEFORE, scopes: $this->getRenderHookScopes()) }}
        {{ $this->table }}
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_AFTER, scopes: $this->getRenderHookScopes()) }}
    </div>
</x-filament-panels::page>

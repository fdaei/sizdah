<?php

declare(strict_types=1);

namespace App\Filament\Concerns;

use App\Filament\Support\TranslatableForm;
use App\Models\SeoMeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Mixed into Filament Create/Edit pages for translatable resources.
 *
 * Splits the nested `translations` array off the form data, saves the parent
 * model, then writes the translation rows — all inside one transaction so a
 * failure part-way cannot leave a record without its translations.
 */
trait HandlesTranslations
{
    /** @var array<string, array<string, mixed>> */
    protected array $pendingTranslations = [];

    /** @var array<string, array<string, mixed>> seo.{locale}.* (HasSeo models only) */
    protected array $pendingSeo = [];

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        [$data, $this->pendingTranslations] = TranslatableForm::split($data);
        $this->pendingSeo = $data['seo'] ?? [];
        unset($data['seo']);

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        [$data, $this->pendingTranslations] = TranslatableForm::split($data);
        $this->pendingSeo = $data['seo'] ?? [];
        unset($data['seo']);

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->getRecord();

        if ($record instanceof Model) {
            $record->loadMissing('translations');

            $data = TranslatableForm::hydrate($record, $data);

            if (method_exists($record, 'seoMeta')) {
                $data['seo'] = $record->seoMeta()->get()
                    ->mapWithKeys(fn (Model $m): array => [$m->locale => array_merge($m->only(SeoMeta::EDITABLE), [
                        'robots_index' => $m->robots_index === null ? null : (int) $m->robots_index,
                        'robots_follow' => $m->robots_follow === null ? null : (int) $m->robots_follow,
                        'sitemap_priority' => $m->sitemap_priority === null ? null : (string) $m->sitemap_priority,
                    ])])
                    ->all();
            }

            return $data;
        }

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data): Model {
            $record = static::getModel()::create($data);

            TranslatableForm::persist($record, $this->pendingTranslations);
            $this->persistSeo($record);

            return $record;
        });
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data): Model {
            $record->update($data);

            TranslatableForm::persist($record, $this->pendingTranslations);
            $this->persistSeo($record);

            return $record;
        });
    }

    /**
     * Write seo.{locale} rows and cache the analysis scores for the table
     * column. Locales with an untouched SEO tab and no translation are skipped.
     */
    protected function persistSeo(Model $record): void
    {
        if (! method_exists($record, 'saveSeo') || $this->pendingSeo === []) {
            return;
        }

        $resource = static::getResource();
        $ctx = method_exists($resource, 'seoContext') ? $resource::seoContext() : null;
        $scores = [];

        foreach ($this->pendingSeo as $locale => $fields) {
            $translation = $this->pendingTranslations[$locale] ?? [];
            $hasInput = collect($fields)->flatten()->contains(fn (mixed $v): bool => filled($v) && $v !== false);

            if (! $hasInput && ! collect($translation)->flatten()->contains(fn (mixed $v): bool => filled($v))) {
                unset($this->pendingSeo[$locale]);

                continue;
            }

            if ($ctx !== null) {
                $scores[$locale] = $ctx->scores($translation, (array) $fields, $locale, $record);
            }
        }

        $record->saveSeo($this->pendingSeo, $scores);
    }
}

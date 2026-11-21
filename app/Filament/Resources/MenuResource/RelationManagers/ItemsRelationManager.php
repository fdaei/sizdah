<?php

declare(strict_types=1);

namespace App\Filament\Resources\MenuResource\RelationManagers;

use App\Filament\Support\TranslatableForm;
use App\Models\MenuItem;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/**
 * Menu links.
 *
 * An item points at either a named route (locale-aware — survives slug edits)
 * or a raw URL. Footer column headings are items with neither, rendered as
 * plain text with their children beneath.
 */
final class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Menu items';

    public function form(Form $form): Form
    {
        return $form->schema([
            TranslatableForm::tabs(fn (string $locale): array => [
                TextInput::make("translations.{$locale}.label")
                    ->label('Label')
                    ->required($locale === config('locales.fallback'))
                    ->maxLength(150),
            ], 'Label'),

            Section::make('Destination')
                ->columns(2)
                ->schema([
                    Select::make('route_name')
                        ->label('Named route')
                        ->options(self::routeOptions())
                        ->searchable()
                        ->native(false)
                        ->live()
                        ->helperText('Preferred — automatically carries the active locale.'),

                    TextInput::make('url')
                        ->label('Or external URL')
                        ->maxLength(500)
                        ->disabled(fn (Get $get): bool => filled($get('route_name')))
                        ->helperText('Used only when no named route is selected.'),

                    Select::make('target')
                        ->options([
                            '_self' => 'Same tab',
                            '_blank' => 'New tab',
                        ])
                        ->default('_self')
                        ->native(false),

                    Select::make('parent_id')
                        ->label('Parent item')
                        ->placeholder('— None (top level) —')
                        ->options(fn (?MenuItem $record): array => $this->parentOptions($record))
                        ->searchable()
                        ->native(false)
                        ->helperText('Nest under any item to build categories and sub-categories (unlimited depth).'),

                    Select::make('children_source')
                        ->label('ساخت خودکار زیرمنو از (Generate submenu from)')
                        ->options(MenuItem::CHILDREN_SOURCES)
                        ->placeholder('هیچ — فقط آیتم‌های زیرمجموعه')
                        ->native(false)
                        ->helperText('مثلاً روی «مقالات»: همهٔ دسته‌ها و زیردسته‌ها خودکار در زیرمنو می‌آیند و با هر تغییر به‌روز می‌شوند.'),
                ]),

            Section::make()
                ->columns(3)
                ->schema([
                    TextInput::make('sort_order')->numeric()->default(0),

                    Toggle::make('is_cta')
                        ->label('Render as button')
                        ->helperText("The header's Let's Talk CTA."),

                    Toggle::make('is_active')->label('Active')->default(true),
                ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $this->orderAsTree($query))
            ->reorderable('sort_order')
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->getStateUsing(fn (MenuItem $record): string => str_repeat('— ', $this->depthOf($record)).$record->getTranslation('label'))
                    ->description(fn (MenuItem $record): ?string => $this->breadcrumbOf($record))
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('route_name')
                    ->label('Destination')
                    ->getStateUsing(fn (MenuItem $record): string => $record->route_name ?? $record->url ?? '— heading —')
                    ->color('gray'),

                Tables\Columns\IconColumn::make('is_cta')->label('CTA')->boolean(),
                Tables\Columns\IconColumn::make('is_active')->label('Active')->boolean(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->using(fn (array $data): Model => $this->persist($data)),
            ])
            ->actions([
                Tables\Actions\CreateAction::make('addChild')
                    ->label('Add sub-item')
                    ->icon('heroicon-m-plus')
                    ->modalHeading('Add sub-item')
                    ->fillForm(fn (MenuItem $record): array => ['parent_id' => $record->id, 'target' => '_self', 'is_active' => true, 'sort_order' => 0])
                    ->using(fn (array $data): Model => $this->persist($data)),

                Tables\Actions\EditAction::make()
                    ->mutateRecordDataUsing(function (array $data, MenuItem $record): array {
                        $record->loadMissing('translations');

                        return TranslatableForm::hydrate($record, $data);
                    })
                    ->using(fn (MenuItem $record, array $data): Model => $this->persist($data, $record)),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    /** @var array<int, array{depth: int, path: array<int, string>, position: int, label: string}>|null */
    private ?array $treeIndex = null;

    /**
     * Depth-first index of the whole menu (WordPress-style ordering):
     * every item follows its parent, siblings by sort_order.
     *
     * @return array<int, array{depth: int, path: array<int, string>, position: int, label: string}>
     */
    private function tree(): array
    {
        if ($this->treeIndex !== null) {
            return $this->treeIndex;
        }

        $items = $this->getOwnerRecord()->items()->with('translations')->orderBy('sort_order')->orderBy('id')->get();
        $byParent = $items->groupBy(fn (MenuItem $i): string => (string) $i->parent_id);
        $index = [];
        $walk = function (string $parentKey, int $depth, array $path) use (&$walk, &$index, $byParent): void {
            foreach ($byParent->get($parentKey, []) as $item) {
                if (isset($index[$item->id])) {
                    continue; // cycle guard
                }
                $label = (string) $item->getTranslation('label');
                $index[$item->id] = ['depth' => $depth, 'path' => $path, 'position' => count($index), 'label' => $label];
                $walk((string) $item->id, $depth + 1, [...$path, $label]);
            }
        };
        $walk('', 0, []);

        // Orphans (parent from another menu / missing) go last as roots.
        foreach ($items as $item) {
            $index[$item->id] ??= ['depth' => 0, 'path' => [], 'position' => count($index), 'label' => (string) $item->getTranslation('label')];
        }

        return $this->treeIndex = $index;
    }

    private function orderAsTree(Builder $query): Builder
    {
        $this->treeIndex = null;
        $tree = $this->tree();

        if ($tree === []) {
            return $query;
        }

        $cases = collect($tree)->map(fn (array $n, int $id): string => 'WHEN '.(int) $id.' THEN '.(int) $n['position'])->implode(' ');
        $table = $query->getModel()->getTable();

        return $query->reorder()->orderByRaw("CASE {$table}.id {$cases} ELSE 999999 END");
    }

    private function depthOf(MenuItem $record): int
    {
        return $this->tree()[$record->id]['depth'] ?? 0;
    }

    private function breadcrumbOf(MenuItem $record): ?string
    {
        $path = $this->tree()[$record->id]['path'] ?? [];

        return $path === [] ? null : implode(' › ', $path);
    }

    /**
     * Every item as an indented option, excluding the record itself and its
     * descendants so a cycle can never be created.
     *
     * @return array<int, string>
     */
    private function parentOptions(?MenuItem $record): array
    {
        $this->treeIndex = null;
        $tree = $this->tree();
        $excluded = [];

        if ($record !== null) {
            $excluded[$record->id] = true;
            $items = $this->getOwnerRecord()->items()->get(['id', 'parent_id']);
            do {
                $added = false;
                foreach ($items as $item) {
                    if ($item->parent_id !== null && isset($excluded[$item->parent_id]) && ! isset($excluded[$item->id])) {
                        $excluded[$item->id] = $added = true;
                    }
                }
            } while ($added);
        }

        return collect($tree)
            ->reject(fn (array $n, int $id): bool => isset($excluded[$id]))
            ->map(fn (array $n): string => str_repeat('— ', $n['depth']).$n['label'])
            ->all();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function persist(array $data, ?MenuItem $record = null): Model
    {
        return DB::transaction(function () use ($data, $record): Model {
            [$attributes, $translations] = TranslatableForm::split($data);

            if ($record === null) {
                $record = $this->getRelationship()->create($attributes);
            } else {
                $record->update($attributes);
            }

            TranslatableForm::persist($record, $translations);

            return $record;
        });
    }

    /**
     * Public named routes an editor may link to.
     *
     * @return array<string, string>
     */
    private static function routeOptions(): array
    {
        $names = [
            'home' => 'Home',
            'work.index' => 'Work',
            'services' => 'Services',
            'about' => 'About',
            'insights.index' => 'Insights',
            'contact' => 'Contact',
            'legal.privacy' => 'Privacy Policy',
            'legal.terms' => 'Terms & Conditions',
        ];

        return array_filter(
            $names,
            fn (string $name): bool => Route::has($name),
            ARRAY_FILTER_USE_KEY,
        );
    }
}

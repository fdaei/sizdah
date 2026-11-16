<?php

declare(strict_types=1);

namespace App\Filament\Support\Editor;

use App\Models\Post;
use App\Models\Project;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Set;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Support\HtmlString;

/**
 * WordPress-style link dialog for the Tiptap editor (replaces the plugin's
 * free-text `rel` box): URL, internal-content search, "open in new tab",
 * and nofollow / sponsored / ugc checkboxes. Registered via
 * config/filament-tiptap-editor.php `link_action`.
 *
 * Writes the same `insertFromAction` event as the stock action, so the
 * editor JS is unchanged.
 */
final class WordPressLinkAction extends Action
{
    private const REL_FLAGS = ['nofollow', 'sponsored', 'ugc'];

    public static function getDefaultName(): ?string
    {
        return 'filament_tiptap_link';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->modalWidth('lg')
            ->arguments([
                'href' => '', 'id' => '', 'hreflang' => '', 'target' => '', 'rel' => '',
                'referrerpolicy' => '', 'as_button' => false, 'button_theme' => '',
            ])
            ->mountUsing(function (ComponentContainer $form, array $arguments): void {
                $rel = array_filter(explode(' ', (string) ($arguments['rel'] ?? '')));
                $form->fill([
                    'href' => $arguments['href'] ?? '',
                    'id' => $arguments['id'] ?? '',
                    'new_tab' => ($arguments['target'] ?? '') === '_blank',
                    'rel_flags' => array_values(array_intersect($rel, self::REL_FLAGS)),
                ]);
            })
            ->modalHeading(fn (array $arguments): string => blank($arguments['href'] ?? '') ? 'افزودن لینک (Insert link)' : 'ویرایش لینک (Edit link)')
            ->form([
                Grid::make(1)->schema([
                    Select::make('internal')
                        ->label('جستجو در محتوای سایت (Link to existing content)')
                        ->placeholder('نام مقاله، پروژه یا صفحه را تایپ کنید…')
                        ->searchable()
                        ->getSearchResultsUsing(fn (string $search): array => self::search($search))
                        ->live()
                        ->afterStateUpdated(fn (?string $state, Set $set) => $state ? $set('href', $state) : null)
                        ->dehydrated(false),

                    TextInput::make('href')
                        ->label('آدرس (URL)')
                        ->extraInputAttributes(['dir' => 'ltr'])
                        ->requiredWithout('id')
                        ->validationAttribute('URL'),

                    Toggle::make('new_tab')
                        ->label('باز شدن در زبانه جدید (Open in new tab)'),

                    CheckboxList::make('rel_flags')
                        ->label('ویژگی rel لینک (Link rel attributes)')
                        ->options([
                            'nofollow' => 'nofollow — گوگل این لینک را دنبال نکند',
                            'sponsored' => 'sponsored — لینک تبلیغاتی / رپورتاژ',
                            'ugc' => 'ugc — محتوای تولیدشده توسط کاربر',
                        ])
                        ->helperText('برای لینک‌های داخلی سایت معمولاً هیچ‌کدام لازم نیست.'),

                    TextInput::make('id')
                        ->label('شناسه لنگر (Anchor ID)')
                        ->extraInputAttributes(['dir' => 'ltr'])
                        ->helperText('اختیاری؛ برای لینک به بخشی از همین صفحه.'),
                ]),
            ])
            ->action(function (TiptapEditor $component, array $data, array $arguments): void {
                $rel = array_values(array_intersect((array) ($data['rel_flags'] ?? []), self::REL_FLAGS));
                if ($data['new_tab'] ?? false) {
                    $rel = [...$rel, 'noopener', 'noreferrer'];
                }

                $component->getLivewire()->dispatch(
                    event: 'insertFromAction',
                    type: 'link',
                    statePath: $component->getStatePath(),
                    href: (string) ($data['href'] ?? ''),
                    id: (string) ($data['id'] ?? ''),
                    hreflang: '',
                    target: ($data['new_tab'] ?? false) ? '_blank' : '',
                    rel: implode(' ', array_unique($rel)),
                    referrerpolicy: '',
                    as_button: false,
                    button_theme: '',
                    coordinates: $arguments['coordinates'] ?? [],
                );

                $component->state($component->getState());
            })
            ->extraModalFooterActions(fn (Action $action): array => ($action->getArguments()['href'] ?? '') !== '' ? [
                $action->makeModalSubmitAction('remove_link', [])
                    ->label('حذف لینک (Remove link)')
                    ->color('danger')
                    ->extraAttributes(fn (): array => [
                        'x-on:click' => new HtmlString("\$dispatch('unset-link', {'statePath': '{$action->getComponent()->getStatePath()}'}); close()"),
                        'style' => 'margin-inline-start: auto;',
                    ]),
            ] : []);
    }

    /**
     * Relative URLs so links survive a domain change and count as internal.
     *
     * @return array<string, string> url => label
     */
    private static function search(string $term): array
    {
        $term = trim($term);
        $results = [];

        $pages = [
            'home' => 'صفحه اصلی', 'about' => 'درباره ما', 'services' => 'خدمات', 'work.index' => 'نمونه‌کارها',
            'insights.index' => 'مقالات', 'contact' => 'تماس با ما',
        ];
        foreach ($pages as $route => $label) {
            if ($term === '' || str_contains($label, $term)) {
                $results[route($route, ['locale' => config('locales.default')], false)] = "صفحه: {$label}";
            }
        }

        $byTitle = fn ($q) => $q->where('title', 'like', "%{$term}%");

        Post::query()->with('translations')->whereHas('translations', $byTitle)->limit(10)->get()
            ->each(function (Post $p) use (&$results): void {
                $results[$p->url(config('locales.default'), false)] = 'مقاله: '.$p->getTranslation('title');
            });

        Project::query()->with('translations')->whereHas('translations', $byTitle)->limit(10)->get()
            ->each(function (Project $p) use (&$results): void {
                $results[$p->url(config('locales.default'), false)] = 'پروژه: '.$p->getTranslation('title');
            });

        return array_slice($results, 0, 25, true);
    }
}

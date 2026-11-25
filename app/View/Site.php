<?php

declare(strict_types=1);

namespace App\View;

use App\Support\JalaliDate;
use Illuminate\Support\HtmlString;

/** Presentation helpers for the native PHP templates. No browser framework or JS server. */
final class Site
{
    public static function render(string $name, array $input, array $page, ?callable $slot = null, array $inherited = []): HtmlString
    {
        foreach (array_keys($input) as $key) {
            if (str_contains($key, '-') && ! str_starts_with($key, 'data-') && ! str_starts_with($key, 'aria-')) {
                $camel = preg_replace_callback('/-([a-z])/', fn ($m) => strtoupper($m[1]), $key);
                $input[$camel] = $input[$key];
                unset($input[$key]);
            }
        }
        if (! preg_match('~^(Pages|Components|Layouts)/[A-Za-z/]+$~', $name)) {
            throw new \InvalidArgumentException('Unknown public template.');
        }
        if (in_array($name, ['Components/BlogCard', 'Components/FeaturedPostCard', 'Components/ProjectPostCard'], true)) {
            $card = $input['post'] ?? $input['project'] ?? [];
            if (empty($card['slug'])) {
                return new HtmlString('');
            }
        }
        $attributes = array_filter($input, fn ($key) => in_array($key, ['class', 'style', 'id', 'dir', 'role', 'tabindex', 'hidden'], true) || str_starts_with($key, 'data-') || str_starts_with($key, 'aria-'), ARRAY_FILTER_USE_KEY);
        $attributes = array_merge($inherited, $attributes);
        $html = view('site.'.str_replace('/', '.', $name), compact('input', 'page', 'slot', 'attributes'))->render();
        if (str_starts_with($name, 'Pages/') && ! preg_match('/<h1\b/', $html)) {
            $title = $input['title'] ?? $input['heading']['title'] ?? $input['seo']['title'] ?? config('app.name');
            $html = '<h1 class="sr-only">'.e($title).'</h1>'.$html;
        }

        return new HtmlString($html);
    }

    public static function defaults(array $defaults, array $input): array
    {
        foreach ($defaults as $key => $value) {
            if (is_callable($value)) {
                $defaults[$key] = $value();
            }
        }
        foreach ($defaults as $key => $value) {
            if (is_bool($value) && ($input[$key] ?? null) === '') {
                $input[$key] = true;
            }
        }

        return array_merge($defaults, $input);
    }

    public static function context(array $input, array $page): array
    {
        static $countries;
        $countries ??= require resource_path('views/site/countries.php');
        $t = self::t(...);

        return array_merge($input, [
            'props' => $input, 'page' => $page, 't' => $t, '$t' => $t, 'Link' => 'a',
            'countries' => $countries,
            'defaultCountry' => collect($countries)->firstWhere('iso2', 'OM'),
            'model' => $input['modelValue'] ?? collect($countries)->firstWhere('iso2', 'OM'),
            'flagUrl' => fn ($iso) => '/site/flags/'.strtolower((string) $iso).'.svg',
            'MOTION' => ['testimonials' => ['driftPerCard' => 5, 'dwell' => 4.5, 'slide' => 0.6]],
            'TYPEFACE_CLASS' => ['text' => 'font-arabic', 'numeric' => 'font-numeric tracking-numeric'],
            'ctaVariant' => fn ($cta, $fallback) => $cta['variant'] ?? $fallback,
            'ctaArrow' => fn ($cta, $fallback) => ! empty($cta['icon']) ? $cta['icon'] === 'arrow' : $fallback,
            'sectionColor' => fn ($section, $field) => ! empty($section['colors'][$field]) ? ['color' => $section['colors'][$field]] : null,
            'headingColors' => fn ($section) => ['eyebrow' => $section['colors']['eyebrow'] ?? null, 'title' => $section['colors']['title'] ?? null, 'subtitle' => $section['colors'][empty($section['subtitle']) ? 'description' : 'subtitle'] ?? null],
            'IconClose' => 'Components/Icons/IconClose', 'IconCheck' => 'Components/Icons/IconCheck',
        ]);
    }

    public static function prepare(string $name, array &$v): void
    {
        if ($name === 'Layouts/AppLayout') {
            $key = ['Work/Index' => 'work', 'Services' => 'services', 'Insights/Index' => 'insights', 'About' => 'about'][$v['page']['component']] ?? null;
            $images = $v['page']['props']['settings']['images'] ?? [];
            $v['topBackgroundStyles'] = [];
            if ($key) {
                $desktop = $images['top_'.$key.'_desktop'] ?? null;
                $tablet = $images['top_'.$key.'_tablet'] ?? $desktop;
                $mobile = $images['top_'.$key.'_mobile'] ?? $tablet;
                foreach (compact('desktop', 'tablet', 'mobile') as $size => $url) {
                    if ($url) {
                        $v['topBackgroundStyles']['--top-bg-'.$size] = 'url('.json_encode($url).')';
                    }
                }
            }
        }
        if ($name === 'Components/SmartText') {
            $raw = (string) ($v['props']['value'] ?? '');
            $parts = preg_split('/([\d۰-۹٠-٩]+(?:[.,٬٫\/:%٪+−–\-\s]*[\d۰-۹٠-٩]+)*)/u', $raw, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
            $v['segments'] = array_map(fn ($text) => ['text' => $text, 'value' => $text, 'typeface' => preg_match('/^[\d۰-۹٠-٩]/u', $text) ? 'numeric' : 'text'], $parts ?: []);
            $hasNumber = (bool) preg_match('/[\d۰-۹٠-٩]/u', $raw);
            $hasText = (bool) preg_match('/[\p{L}]/u', $raw);
            $v['contentType'] = $hasNumber && $hasText ? 'mixed' : ($hasNumber ? 'numeric' : ($raw === '' ? 'empty' : 'text'));
            $v['isMixed'] = $v['contentType'] === 'mixed';
            $v['fontClass'] = $hasNumber && ! $hasText ? 'font-numeric tracking-numeric' : 'font-arabic';
            $v['direction'] = preg_match('/[\x{0600}-\x{06FF}]/u', $raw) ? 'rtl' : 'ltr';
        }
        if (isset($v['form']) && is_array($v['form'])) {
            $v['form'] = array_replace($v['form'], request()->session()->getOldInput());
            $v['form']['errors'] = request()->session()->get('errors')?->getBag('default')->messages() ?? [];
            $v['form']['errors'] = array_map(fn ($messages) => $messages[0] ?? '', $v['form']['errors']);
            if ($name === 'Pages/Contact') {
                $v['phoneNumber'] = old('phone', '');
                $v['selectedServices'] = array_values(array_map(fn ($service) => $service['title'], array_filter($v['props']['services'] ?? [], fn ($service) => in_array($service['id'], $v['form']['service_ids'] ?? []))));
            }
        }
    }

    public static function t(?string $key, array $params = []): string
    {
        $text = __((string) $key, $params);
        if (! is_string($text)) {
            return (string) $key;
        }
        foreach ($params as $k => $value) {
            $text = str_replace('{'.$k.'}', self::text($value), $text);
        }

        return $text;
    }

    public static function get(mixed $value, mixed $key): mixed
    {
        if (is_array($value)) {
            return $value[$key ?? ''] ?? null;
        }
        if (is_object($value)) {
            return $value->{$key} ?? null;
        }

        return null;
    }

    public static function truth(mixed $value): bool
    {
        return is_array($value) || is_object($value) || ($value !== null && $value !== false && $value !== '' && $value !== 0 && $value !== 0.0);
    }

    public static function text(mixed $value): string
    {
        if (is_array($value)) {
            return implode(',', array_map(self::text(...), $value));
        }
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return (string) ($value ?? '');
    }

    public static function length(mixed $value): int
    {
        return is_array($value) ? count($value) : mb_strlen((string) ($value ?? ''));
    }

    public static function add(mixed $a, mixed $b): mixed
    {
        return is_string($a) || is_string($b) ? self::text($a).self::text($b) : ($a ?? 0) + ($b ?? 0);
    }

    public static function iter(mixed $value): array
    {
        if (is_int($value)) {
            return $value > 0 ? range(1, $value) : [];
        }

        return is_array($value) ? $value : [];
    }

    public static function method(mixed $value, string $method, array $args): mixed
    {
        $array = is_array($value) ? $value : [];
        $string = is_string($value) || is_numeric($value) ? (string) $value : '';
        $callback = $args[0] ?? null;
        switch ($method) {
            case 'map': $result = [];
                foreach ($array as $key => $item) {
                    $result[] = $callback($item, $key);
                }

return $result;
            case 'filter': $result = [];
                foreach ($array as $key => $item) {
                    if (is_callable($callback) ? $callback($item, $key) : self::truth($item)) {
                        $result[] = $item;
                    }
                }

return $result;
            case 'find': foreach ($array as $key => $item) {
                if ($callback($item, $key)) {
                    return $item;
                }
            }

return null;
            case 'findIndex': foreach ($array as $key => $item) {
                if ($callback($item, $key)) {
                    return $key;
                }
            }

return -1;
            case 'some': foreach ($array as $key => $item) {
                if ($callback($item, $key)) {
                    return true;
                }
            }

return false;
            case 'includes': return is_array($value) ? in_array($args[0] ?? null, $array, true) : str_contains($string, self::text($args[0] ?? ''));
            case 'slice': return is_array($value) ? array_slice($value, (int) ($args[0] ?? 0), isset($args[1]) ? (int) $args[1] - (int) ($args[0] ?? 0) : null) : mb_substr($string, (int) ($args[0] ?? 0), isset($args[1]) ? (int) $args[1] - (int) ($args[0] ?? 0) : null);
            case 'indexOf': $index = is_array($value) ? array_search($args[0], $array, true) : mb_strpos($string, (string) $args[0]);

                return $index === false ? -1 : $index;
            case 'join': return implode((string) ($args[0] ?? ','), $array);
            case 'split': return ($args[0] ?? '') === '' ? mb_str_split($string) : explode((string) $args[0], $string);
            case 'trim': return trim($string);
            case 'toLowerCase': return mb_strtolower($string);
            case 'toUpperCase': return mb_strtoupper($string);
            case 'startsWith': return str_starts_with($string, (string) $args[0]);
            case 'endsWith': return str_ends_with($string, (string) $args[0]);
            case 'padStart': return str_pad($string, (int) $args[0], (string) ($args[1] ?? ' '), STR_PAD_LEFT);
            case 'replace': return str_starts_with((string) $args[0], '~') ? preg_replace((string) $args[0], (string) ($args[1] ?? ''), $string) : preg_replace('/'.preg_quote((string) $args[0], '/').'/', (string) ($args[1] ?? ''), $string, 1);
            case 'get': return $array[$args[0]] ?? null;
            case 'has': return array_key_exists($args[0], $array);
            case 'format': return self::year();
        }
        throw new \LogicException('Unsupported presentation method: '.$method);
    }

    public static function object(string $method, array $args): array
    {
        $value = (array) ($args[0] ?? []);

        return match ($method) {
            'values' => array_values($value),
            'keys' => array_keys($value),
            'entries' => array_map(fn ($key) => [$key, $value[$key]], array_keys($value)),
            'fromEntries' => array_column($value, 1, 0),
            default => [],
        };
    }

    public static function orbit(array $slots, array $services): array
    {
        $result = [];
        foreach ($slots as $slot) {
            if ($services === []) {
                break;
            }
            $index = 0;
            foreach ($services as $key => $service) {
                if (($service['icon'] ?? '') === $slot['icon']) {
                    $index = $key;
                    break;
                }
            }
            $result[] = ['slot' => $slot, 'service' => $services[$index]];
            array_splice($services, $index, 1);
        }

        return $result;
    }

    public static function year(): string
    {
        return app()->getLocale() === 'fa' ? last(explode(' ', JalaliDate::format(now()))) : now()->format('Y');
    }

    public static function classes(mixed $value): string
    {
        if (! is_array($value)) {
            return is_string($value) ? $value : '';
        }
        $classes = [];
        foreach ($value as $key => $item) {
            if (is_int($key)) {
                $classes[] = self::classes($item);
            } elseif (self::truth($item)) {
                $classes[] = $key;
            }
        }

        return trim(implode(' ', array_filter($classes)));
    }

    public static function style(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }
        if (! is_array($value)) {
            return '';
        }
        $parts = [];
        foreach ($value as $key => $item) {
            if (is_int($key)) {
                $parts[] = self::style($item);

                continue;
            }
            if ($item === null || $item === '') {
                continue;
            }
            $property = str_starts_with($key, '--') ? $key : strtolower((string) preg_replace('/[A-Z]/', '-$0', $key));
            $unitless = in_array($property, ['opacity', 'z-index', 'font-weight', 'line-height', 'order', 'flex', 'flex-grow', 'flex-shrink'], true);
            $parts[] = $property.':'.self::text($item).(is_numeric($item) && ! $unitless && ! str_starts_with($key, '--') ? 'px' : '');
        }

        return implode(';', $parts);
    }

    public static function attributes(array $own, array $inherited = [], string $scope = '', ?string $component = null): HtmlString
    {
        $class = self::classes([$own['class'] ?? [], $inherited['class'] ?? []]);
        $style = self::style([$own['style'] ?? [], $inherited['style'] ?? []]);
        $attrs = array_merge($own, $inherited);
        if ($class !== '') {
            $attrs['class'] = $class;
        }
        if ($style !== '') {
            $attrs['style'] = $style;
        }
        if ($scope !== '') {
            $attrs[$scope] = '';
        }
        if ($component) {
            $attrs['data-site'] = $component;
        }
        $out = '';
        foreach ($attrs as $name => $value) {
            if ($value === null || ($value === false && ! str_starts_with($name, 'aria'))) {
                continue;
            }
            if (in_array($name, ['class', 'style'], true) && ! is_string($value)) {
                continue;
            }
            if (! preg_match('/^[a-zA-Z_:][a-zA-Z0-9_.:\-]*$/', $name)) {
                continue;
            }
            if (str_starts_with($name, 'aria') || str_starts_with($name, 'data')) {
                $name = strtolower((string) preg_replace('/[A-Z]/', '-$0', $name));
            }
            if (in_array($name, ['disabled', 'checked', 'selected', 'open', 'hidden', 'required', 'multiple', 'autofocus', 'novalidate'], true)) {
                if ($value !== false && $value !== null) {
                    $out .= ' '.$name;
                }
            } else {
                $out .= ' '.$name.'="'.e(self::text($value)).'"';
            }
        }

        return new HtmlString($out);
    }

    public static function tag(mixed $tag): string
    {
        return is_string($tag) && preg_match('/^(?:a|button|div|span|p|h[1-6]|section|article|nav|svg)$/', $tag) ? $tag : 'span';
    }

    public static function element(mixed $tag, array $attrs, array $page, ?callable $slot, array $inherited, string $scope, ?string $component): HtmlString
    {
        if (is_string($tag) && str_starts_with($tag, 'Components/')) {
            return self::render($tag, $attrs, $page, $slot, $inherited);
        }
        $tag = self::tag($tag);

        return new HtmlString('<'.$tag.self::attributes($attrs, $inherited, $scope, $component).'>'.($slot ? $slot() : '').'</'.$tag.'>');
    }
}

import { computed, inject, type App, type ComputedRef, type InjectionKey } from 'vue'
import { usePage } from '@inertiajs/vue3'

/**
 * UI strings.
 *
 * `lang/{locale}/*.php` is shared into Inertia by
 * AppServiceProvider::shareTranslations(), so Blade, validation messages and
 * Vue all resolve the same string from one source. Keys are dot-paths into
 * that tree, file name first: `common.read_more`, `forms.contact.name`.
 *
 * Editorial copy does NOT belong here — that lives in the translation tables
 * and arrives as page props so it stays editable in Filament.
 */

type TranslationTree = { [key: string]: string | TranslationTree }

export type Translator = (key: string, replacements?: Record<string, string | number>) => string

export const TranslatorKey: InjectionKey<Translator> = Symbol('translator')

function resolve(tree: TranslationTree, key: string): string | undefined {
  const value = key
    .split('.')
    .reduce<string | TranslationTree | undefined>(
      (node, segment) =>
        node !== undefined && typeof node !== 'string' ? node[segment] : undefined,
      tree,
    )

  return typeof value === 'string' ? value : undefined
}

/**
 * `:name` style placeholders, matching Laravel's own replacement syntax so a
 * string can move between a Blade view and a Vue component untouched.
 */
function interpolate(line: string, replacements: Record<string, string | number>): string {
  return Object.entries(replacements).reduce(
    (carry, [token, value]) => carry.replaceAll(`:${token}`, String(value)),
    line,
  )
}

export function createTranslator(tree: TranslationTree): Translator {
  return (key, replacements) => {
    const line = resolve(tree, key)

    if (line === undefined) {
      // Fall back to the key so a missing string is visible in review rather
      // than rendering an empty element.
      return key
    }

    return replacements ? interpolate(line, replacements) : line
  }
}

/**
 * Installs `$t` globally so templates can call it without importing, and
 * provides the same function for `useTranslations()`.
 */
export function installTranslations(app: App): void {
  const translator: Translator = (key, replacements) => {
    const tree = (usePage().props.translations ?? {}) as TranslationTree

    return createTranslator(tree)(key, replacements)
  }

  app.config.globalProperties.$t = translator
  app.provide(TranslatorKey, translator)
}

export function useTranslations(): { t: Translator; locale: ComputedRef<string> } {
  const injected = inject(TranslatorKey, undefined)

  const t: Translator =
    injected ??
    ((key, replacements) => {
      const tree = (usePage().props.translations ?? {}) as TranslationTree

      return createTranslator(tree)(key, replacements)
    })

  const locale = computed(() => {
    const page = usePage().props.locale as { current?: string } | undefined

    return page?.current ?? 'fa'
  })

  return { t, locale }
}

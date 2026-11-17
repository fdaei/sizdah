import { computed, type ComputedRef } from 'vue'
import { usePage } from '@inertiajs/vue3'

/**
 * Reactive read of a shared site setting by dot path, e.g.
 * `useSetting('contact.phone')` or `useSetting('seo.defaultTitle', 'Sizdah')`.
 * Backed by the `settings` Inertia prop (App\Support\SiteSettings::forFrontend).
 */
export function useSetting<T = unknown>(path: string, fallback?: T): ComputedRef<T> {
  const page = usePage()

  return computed(() => {
    const value = path
      .split('.')
      .reduce<unknown>(
        (node, segment) =>
          node !== null && typeof node === 'object'
            ? (node as Record<string, unknown>)[segment]
            : undefined,
        page.props.settings,
      )

    return (value === undefined || value === null || value === '' ? fallback : value) as T
  })
}

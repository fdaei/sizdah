import type { Translator } from '../Composables/useTranslations'

/**
 * `$t` is installed globally by installTranslations() in app.ts, so templates
 * can resolve UI strings without importing the composable per component.
 */
declare module 'vue' {
  interface ComponentCustomProperties {
    $t: Translator
  }
}

declare module '*.vue' {
  import type { DefineComponent } from 'vue'

  const component: DefineComponent<Record<string, unknown>, Record<string, unknown>, unknown>

  export default component
}

export {}

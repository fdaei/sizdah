import '../css/app.css'

import { createInertiaApp } from '@inertiajs/vue3'
import createServer from '@inertiajs/vue3/server'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { createSSRApp, h, type DefineComponent } from 'vue'
import { renderToString } from 'vue/server-renderer'
import { ZiggyVue, type Config } from 'ziggy-js'

import AppLayout from './Layouts/AppLayout.vue'
import { installTranslations } from './Composables/useTranslations'

const appName = import.meta.env.VITE_APP_NAME ?? 'Sizdah'

/*
 | One page's render error must not take the renderer down: Node exits on an
 | uncaught error, and from then on Laravel silently serves every page as an
 | empty client-rendered shell. Log it and keep serving — Laravel already
 | falls back to client rendering for the request that failed.
 */
process.on('uncaughtException', (error) => console.error('[ssr] uncaught', error))
process.on('unhandledRejection', (reason) => console.error('[ssr] unhandled rejection', reason))

createServer((page) => {
  /*
   | Components import `route` straight from 'ziggy-js', which reads the global
   | `Ziggy` config. In the browser the Blade `@routes` directive defines it; in
   | Node nothing does, so every route() call threw and the whole SSR render
   | failed — leaving crawlers with an empty app shell. The shared `ziggy` prop
   | carries the same config (incl. URL::defaults' locale), so expose it here.
   */
  ;(globalThis as { Ziggy?: unknown }).Ziggy = (page.props as { ziggy?: unknown }).ziggy

  return createInertiaApp({
    page,
    render: renderToString,
    // Same format as app.ts, but no default: with an empty title Inertia
    // would emit a bare "<title>Sizdah</title>" ahead of the real one from
    // resources/views/partials/seo.blade.php (SeoHead renders nothing in SSR).
    title: (title) => (title ? `${title} — ${appName}` : ''),
    resolve: (name) =>
      resolvePageComponent(
        `./Pages/${name}.vue`,
        import.meta.glob<DefineComponent>('./Pages/**/*.vue'),
      ).then((module) => {
        module.default.layout ??= AppLayout
        return module
      }),
    setup({ App, props, plugin }) {
      const app = createSSRApp({ render: () => h(App, props) })
        .use(plugin)
        .use(ZiggyVue, (page.props as { ziggy?: Config }).ziggy)

      installTranslations(app)
      return app
    },
  })
})

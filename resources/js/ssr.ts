import '../css/app.css'

import { createInertiaApp } from '@inertiajs/vue3'
import createServer from '@inertiajs/vue3/server'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { createSSRApp, h, type DefineComponent } from 'vue'
import { renderToString } from 'vue/server-renderer'
import { ZiggyVue } from 'ziggy-js'

import AppLayout from './Layouts/AppLayout.vue'
import { installTranslations } from './Composables/useTranslations'

createServer((page) =>
  createInertiaApp({
    page,
    render: renderToString,
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
        .use(ZiggyVue, (page.props as { ziggy?: any }).ziggy)

      installTranslations(app)
      return app
    },
  }),
)

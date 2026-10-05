import '../css/app.css'

import { createSSRApp, h, type DefineComponent } from 'vue'
import { createInertiaApp, router } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from 'ziggy-js'

import AppLayout from './Layouts/AppLayout.vue'
import { installTranslations } from './Composables/useTranslations'
import { initMotion, killPageAnimations } from './lib/motion'

const appName = import.meta.env.VITE_APP_NAME ?? 'Sizdah'

createInertiaApp({
  title: (title) => (title ? `${title} — ${appName}` : appName),

  resolve: (name) => {
    const page = resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob<DefineComponent>('./Pages/**/*.vue'),
    )

    // Every page gets the shared chrome unless it opts out by setting
    // `defineOptions({ layout: null })`.
    return page.then((module) => {
      module.default.layout ??= AppLayout
      return module
    })
  },

  setup({ el, App, props, plugin }) {
    // createSSRApp hydrates the server-rendered markup instead of discarding
    // and re-mounting it (no re-render flash / layout shift). When the SSR
    // server is down the container is empty and Vue falls back to a full mount.
    const app = createSSRApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)

    installTranslations(app)

    app.mount(el)

    // The initial page is mounted asynchronously by createInertiaApp. Running
    // this before mount meant the first page had no reveal targets; initialize
    // after Vue has rendered so Chrome's responsive/inspect view gets the same
    // motion as a normal viewport.
    initMotion()
  },

  progress: {
    color: '#F8B937', // Figma "Yellow/1000"
    showSpinner: false,
  },
})

/*
 | GSAP lifecycle across Inertia navigations.
 |
 | Inertia swaps the page component without a full reload, so ScrollTrigger
 | instances from the previous page would otherwise survive, keep listening,
 | and fight the new page's layout. We tear them down before every navigation
 | and re-initialise once the new page has painted.
 */
router.on('before', () => {
  killPageAnimations()
})

router.on('navigate', () => {
  // Wait for the new DOM to be laid out before measuring scroll positions.
  requestAnimationFrame(() => {
    requestAnimationFrame(() => initMotion())
  })
})

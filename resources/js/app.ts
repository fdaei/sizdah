import '../css/app.css'

import { createApp, createSSRApp, h, type DefineComponent } from 'vue'
import { createInertiaApp, router } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from 'ziggy-js'

import AppLayout from './Layouts/AppLayout.vue'
import { installTranslations } from './Composables/useTranslations'

let motionGeneration = 0
let motion: typeof import('./lib/motion') | undefined

async function initPageMotion(): Promise<void> {
  const generation = ++motionGeneration
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    document.documentElement.classList.add('reduced-motion')
    return
  }
  motion ??= await import('./lib/motion')
  if (generation === motionGeneration) motion.initMotion()
}

const appName = import.meta.env.VITE_APP_NAME ?? 'Sizdah'

createInertiaApp({
  // Titles arrive fully rendered (SeoBuilder applies the Yoast-style
  // %title% %sep% %sitename% template server-side), so never append here.
  title: (title) => title || appName,

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
    // Blade's PHP content is a complete reading view, not Vue SSR markup.
    // Mount over it normally; hydrate only when the Node renderer supplied Vue HTML.
    const create = el.hasAttribute('data-php-content') ? createApp : createSSRApp
    const app = create({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)

    installTranslations(app)

    app.mount(el)

    // The initial page is mounted asynchronously by createInertiaApp. Running
    // this before mount meant the first page had no reveal targets; initialize
    // after Vue has rendered so Chrome's responsive/inspect view gets the same
    // motion as a normal viewport.
    void initPageMotion()
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
  motionGeneration++
  motion?.killPageAnimations()
})

router.on('navigate', () => {
  // Wait for the new DOM to be laid out before measuring scroll positions.
  requestAnimationFrame(() => {
    requestAnimationFrame(() => void initPageMotion())
  })
})

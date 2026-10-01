<script setup lang="ts">
import { computed, ref } from 'vue'
import { usePage } from '@inertiajs/vue3'
import AppHeader from '@/Layouts/AppHeader.vue'
import AppFooter from '@/Layouts/AppFooter.vue'
import MobileMenu from '@/Layouts/MobileMenu.vue'
import FlashMessages from '@/Components/FlashMessages.vue'
import type { SharedProps } from '@/types'
import siteBackgroundUrl from '~img/sizdah/shared/site-background.webp'

/**
 * Shared chrome. app.ts assigns this to every page unless the page opts out
 * with `defineOptions({ layout: null })`.
 *
 * The page transition (A12) runs on <main> only, so the header and footer stay
 * put across Inertia navigations instead of re-mounting.
 *
 * MOST dark page frames paint a warm wash into one corner over the ink-1000
 * ground — a brand-yellow linear gradient that is fully transparent for the
 * first ~80% of its run. The angle and peak opacity drift slightly per frame
 * (projects 222:1989 and 404 266:2825 are -42deg/10%, about 336:5623 is
 * -11deg/7%); that spread is treated as authoring noise, so one shared wash at
 * the dominant -42deg/10% stands in for all of them.
 *
 * The file models it as a second fill on the page frame, so `.page-wash` is a
 * background-image on the same element that carries `bg-ink-1000` rather than
 * an overlay div — no extra node and no stacking-order to manage.
 */
const page = usePage<SharedProps>()

const gridPages = ['Work/Index', 'Services', 'Insights/Index', 'Contact', 'About']

/**
 * Home is the exception: 268:2962 has NO page wash. Sampled down the full
 * 9919px of the frame, its ground is a flat #141414 — the warm glow visible in
 * the hero belongs to the hero art itself and is already baked into
 * `home/hero-bg.jpg`. Because that art is an opaque full-bleed raster it also
 * MASKS the shared wash, so leaving Home washed produced a hard seam at the
 * hero's bottom edge: #141414 above, a gold-tinted #1f1c15 below.
 */
const washed = computed(() => page.component !== 'Home' && !gridPages.includes(page.component))

const gridBackground = computed(() => gridPages.includes(page.component))
const nonHomeBackground = computed(() => page.component !== 'Home')

const menuOpen = ref(false)
</script>

<template>
  <div
    class="site-shell flex min-h-screen-safe flex-col bg-ink-1000"
    :class="{ 'page-wash': washed, 'grid-background': gridBackground, 'non-home-background': nonHomeBackground, 'error-shell': page.component === 'Error', 'plain-mobile-background': ['Work/Index', 'Insights/Show'].includes(page.component) }"
    :style="{ '--site-background': `url(${siteBackgroundUrl})` }"
  >
    <AppHeader @open-menu="menuOpen = true" />
    <MobileMenu :open="menuOpen" @close="menuOpen = false" />

    <FlashMessages />

    <Transition
      mode="out-in"
      enter-active-class="transition-opacity duration-300 ease-brand"
      enter-from-class="opacity-0"
      leave-active-class="transition-opacity duration-200 ease-brand"
      leave-to-class="opacity-0"
    >
      <main id="main" :key="page.url" class="flex-1">
        <slot />
      </main>
    </Transition>

    <AppFooter v-if="!['Insights/Show', 'Error'].includes(page.component)" />
  </div>
</template>

<style scoped>
.non-home-background {
  background-image: var(--site-background) !important;
  background-position: top center;
  background-repeat: repeat-y;
  background-size: 100% auto;
}

@media (max-width: 767px) {
  .site-shell.non-home-background {
    background-image: var(--site-background) !important;
    background-position: top center;
    background-repeat: repeat-y;
    background-size: 100% auto;
  }

  .site-shell.error-shell {
    background-image: none !important;
  }

  .site-shell.plain-mobile-background {
    background-image: none !important;
  }
}
</style>

<script setup lang="ts">
import { ref } from 'vue'
import { usePage } from '@inertiajs/vue3'
import AppHeader from '@/Layouts/AppHeader.vue'
import AppFooter from '@/Layouts/AppFooter.vue'
import MobileMenu from '@/Layouts/MobileMenu.vue'
import FlashMessages from '@/Components/FlashMessages.vue'
import type { SharedProps } from '@/types'

/**
 * Shared chrome. app.ts assigns this to every page unless the page opts out
 * with `defineOptions({ layout: null })`.
 *
 * The page transition (A12) runs on <main> only, so the header and footer stay
 * put across Inertia navigations instead of re-mounting.
 *
 * Every dark page frame paints a warm wash into one corner over the ink-1000
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

const menuOpen = ref(false)
</script>

<template>
  <div class="page-wash flex min-h-screen-safe flex-col bg-ink-1000">
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

    <AppFooter />
  </div>
</template>

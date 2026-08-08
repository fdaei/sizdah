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
 */
const page = usePage<SharedProps>()

const menuOpen = ref(false)
</script>

<template>
  <div class="flex min-h-screen-safe flex-col bg-ink-1000">
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
      <main :key="page.url" id="main" class="flex-1">
        <slot />
      </main>
    </Transition>

    <AppFooter />
  </div>
</template>

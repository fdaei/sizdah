<script setup lang="ts">
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { Menu } from 'lucide-vue-next'
import BrandLogo from '@/Components/BrandLogo.vue'
import CtaButton from '@/Components/CtaButton.vue'
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue'
import underlineUrl from '~img/sizdah/shared/nav-underline.svg'
import doodleUrl from '~img/sizdah/shared/header-doodle.svg'
import type { NavItem, SharedProps } from '@/types'

/**
 * Site header. Figma 268:3031 (instanced on every frame, 1440 x 108).
 *
 * Layout in the file is RTL: wordmark at the inline start, the menu centred,
 * and the gold CTA at the inline end. Built with logical properties so the
 * same markup flips for the LTR locale.
 */
defineEmits<{ openMenu: [] }>()

const page = usePage<SharedProps>()

const items = computed<NavItem[]>(() => page.props.navigation.header)
const settings = computed(() => page.props.settings)

/** The nav item flagged `isCta` in Filament becomes the gold button. */
const cta = computed(() => items.value.find((item) => item.isCta) ?? null)
const links = computed(() => items.value.filter((item) => !item.isCta))

const currentUrl = computed(() => page.url)

function isActive(item: NavItem): boolean {
  // page.url includes the locale prefix and any query string.
  const path = currentUrl.value.split('?')[0].replace(/\/$/, '')
  const target = new URL(item.url, 'http://x').pathname.replace(/\/$/, '')

  return path === target
}
</script>

<template>
  <header
    class="fixed inset-x-0 top-0 z-header border-b border-white/5 bg-white/[0.15] backdrop-blur-header"
  >
    <div
      class="container-sizdah flex items-center justify-between gap-6 py-6"
    >
      <!-- Wordmark — inline start -->
      <Link :href="`/${page.props.locale.current}`" class="relative shrink-0">
        <BrandLogo :width="104" :title="settings.siteName" />
        <img
          :src="doodleUrl"
          alt=""
          aria-hidden="true"
          width="16"
          height="20"
          class="pointer-events-none absolute -top-2 end-[-14px] hidden h-5 w-4 lg:block"
        />
      </Link>

      <!-- Primary navigation -->
      <nav :aria-label="$t('common.primary_navigation')" class="hidden lg:block">
        <ul class="flex items-center gap-6">
          <li v-for="item in links" :key="item.id">
            <Link
              :href="item.url"
              :target="item.target"
              :aria-current="isActive(item) ? 'page' : undefined"
              class="group flex flex-col items-center px-1 py-1 text-title-md transition-colors duration-200 ease-brand"
              :class="isActive(item) ? 'text-paper' : 'text-ink-200 hover:text-paper'"
            >
              {{ item.label }}
              <!--
                The file draws the active state as a hand-inked rule under the
                label. Inactive items get the same mark on hover, so the
                asset is rendered for both and only its opacity changes.
              -->
              <img
                :src="underlineUrl"
                alt=""
                aria-hidden="true"
                width="45"
                height="5"
                class="mt-1 h-[5px] w-[45px] transition-opacity duration-200 ease-brand"
                :class="isActive(item) ? 'opacity-100' : 'opacity-0 group-hover:opacity-60'"
              />
            </Link>
          </li>
        </ul>
      </nav>

      <div class="flex items-center gap-4">
        <LanguageSwitcher class="hidden lg:flex" />

        <CtaButton
          v-if="cta"
          :label="cta.label"
          :href="cta.url"
          class="hidden lg:inline-flex"
        />

        <button
          type="button"
          class="inline-flex size-11 items-center justify-center rounded-sm text-paper lg:hidden"
          :aria-label="$t('common.open_menu')"
          @click="$emit('openMenu')"
        >
          <Menu class="size-6" aria-hidden="true" />
        </button>
      </div>
    </div>
  </header>
</template>

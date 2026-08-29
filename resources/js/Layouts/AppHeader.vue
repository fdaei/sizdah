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
 *
 * Measured off the frame (1440 x 107.568): px space96 / py space24, the logo
 * 104 x 59.568 at y=24 (it is what sets the header height), the menu on gap 24,
 * and the ink doodle `Layer_1` (I268:3031;56:2399) at x=98.148 / y=12.948 —
 * i.e. over the CTA's top corner, NOT beside the wordmark, where it used to sit.
 *
 * The frame paints no bottom border; the 15% white fill plus the 15px backdrop
 * blur are the whole treatment.
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
  <header class="fixed inset-x-0 top-0 z-header bg-white/[0.15] backdrop-blur-header">
    <div class="container-sizdah flex items-center justify-between gap-6 py-6">
      <!-- Wordmark — inline start -->
      <Link :href="`/${page.props.locale.current}`" class="shrink-0">
        <BrandLogo :width="104" :title="settings.siteName" />
      </Link>

      <!-- Primary navigation -->
      <nav :aria-label="$t('common.primary_navigation')" class="hidden lg:block">
        <ul class="flex items-center gap-6">
          <li v-for="item in links" :key="item.id">
            <Link
              :href="item.url"
              :target="item.target"
              :aria-current="isActive(item) ? 'page' : undefined"
              class="flex flex-col items-center justify-center px-1 py-1 text-title-md transition-colors duration-200 ease-brand"
              :class="
                isActive(item)
                  ? 'font-medium text-paper'
                  : 'font-normal text-ink-200 hover:text-paper'
              "
            >
              {{ item.label }}
              <!--
                The two states use different nodes, not one asset at two
                opacities (header item 20:2087):
                  selected 54:2219 — a hand-inked rule on a 2px layout box that
                    renders 4.71px, stretched to the label width. The SVG carries
                    preserveAspectRatio="none", so w-full is how it is meant to
                    scale. Item height: 4 + 25 + 2 + 4 = 35, the master exactly.
                  default 27:2357 — a 45.276 x 9.676 squiggle exported with
                    opacity="0" baked in, i.e. deliberately invisible. It is a
                    spacer, and it is load-bearing: it is what makes an unselected
                    item 42.676 tall against the selected item's 35, which the
                    menu's items-center then offsets. Rendering nothing here would
                    shift every inactive label down ~3.8px.
                The file ships no hover variant, so hover stays a colour change.
              -->
              <span v-if="isActive(item)" class="relative block h-[2px] w-full">
                <img
                  :src="underlineUrl"
                  alt=""
                  aria-hidden="true"
                  class="absolute inset-x-0 -top-[1.7px] h-[4.71px] w-full max-w-none"
                />
              </span>
              <span v-else class="block h-[9.676px] w-[45.276px]" aria-hidden="true" />
            </Link>
          </li>
        </ul>
      </nav>

      <div class="flex items-center gap-4">
        <LanguageSwitcher class="hidden lg:flex" />

        <!--
          Layer_1 sits 17.8px above the CTA's top edge and 2.1px in from its
          inline-end edge, mirrored on X. It decorates the button, not the logo.
        -->
        <div v-if="cta" class="relative isolate hidden lg:block">
          <CtaButton
            :label="cta.label"
            :href="cta.url"
            class="h-[46px] w-[127px] px-0 py-0"
          />
          <img
            :src="doodleUrl"
            alt=""
            aria-hidden="true"
            width="16"
            height="20"
            class="pointer-events-none absolute -top-[18px] end-[2px] z-20 h-5 w-4 flip-rtl"
          />
        </div>

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

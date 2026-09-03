<script setup lang="ts">
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import { Mail, MapPin, Phone } from 'lucide-vue-next'
import BrandLogo from '@/Components/BrandLogo.vue'
import footerBloomUrl from '~img/sizdah/shared/footer-bloom.svg'
import type { NavItem, SharedProps } from '@/types'

/**
 * Site footer. Figma page-instance Footer, e.g. `688:5781` on Contact
 * (`279:6325`) — every page frame carries its own copy at this node range
 * (`688:5xxx`/`688:6xxx`), all structurally identical.
 *
 * RE-VERIFIED 2026-09-02 (see GAPS G53): the file's footer was redesigned
 * since the 2026-08-21 pass this docblock previously described. The SAHRA/
 * unrebranded-instance deviation this docblock used to document no longer
 * applies — the current frame is properly Sizdah-branded (own wordmark,
 * Persian copy) — so the structure AND content now both come straight from
 * the frame; nothing is being substituted.
 *
 * Geometry: outer frame 1440x501.76, content track inset px96/pt48
 * (`container-sizdah` at `xl`). Top block (brand + 3 nav columns) is
 * 1248x218; a 40px gap to the bottom legal bar (1248x66, itself a 24px-padded
 * rule + 18px text row). Column heights still confirm the 1.5 type scale.
 * Column titles use the brand yellow while the remaining footer copy is white
 * so the navigation stays legible against the dark footer background.
 *
 * NEW this pass — a `Group 26` decorative wash sits behind the whole block,
 * x=96/y=52, 1248x449.76, seven brand-yellow (`#F8B937`) blobs at 3% opacity.
 * It alone accounts for the frame's height: 52 (its own top offset) + 449.76
 * (its height) = 501.76, exactly the frame height — the real content only
 * needs 324px, so the frame carries ~130px of pure bottom padding purely to
 * give the wash room to breathe. Exported as `footer-bloom.svg` and painted
 * as a `container-sizdah`-track-width background, `xl:` and up only — same
 * "container padding only matches the frame's 96px gutter at `xl`" reasoning
 * as Legal's trust-badge (`Legal.vue`, GAPS G49).
 *
 * The old NOT-implemented note about a bleeding SAHRA wordmark (former
 * GAPS G24) is retired along with it — the current frame does not draw one.
 */
const page = usePage<SharedProps>()

const settings = computed(() => page.props.settings)
const columns = computed<NavItem[]>(() => page.props.navigation.footer)

const year = computed(() =>
  new Intl.DateTimeFormat(page.props.locale.htmlLang, { year: 'numeric' }).format(new Date()),
)

const contact = computed(() => settings.value.contact)
</script>

<template>
  <footer class="rounded-t-lg border-t border-warm-100 bg-ink-1000 shadow-footer">
    <div class="container-sizdah relative pb-12 pt-12 xl:pb-[177.76px]">
      <img
        :src="footerBloomUrl"
        alt=""
        aria-hidden="true"
        class="pointer-events-none absolute inset-x-0 top-[52px] hidden w-full xl:block"
      />

      <div class="relative flex flex-col gap-10">
        <div class="flex flex-col justify-between gap-10 md:flex-row md:items-start">
          <!-- Brand + positioning line -->
          <div class="flex max-w-[402px] flex-col gap-8">
            <BrandLogo :width="87" :title="settings.siteName" />
            <p class="text-body-lg leading-normal text-white">{{ settings.tagline }}</p>
          </div>

          <!-- Link columns -->
          <div class="grid grid-cols-2 gap-10 md:flex md:gap-[88px]">
            <nav
              v-for="column in columns"
              :key="column.id"
              :aria-label="column.label"
              class="flex flex-col gap-4"
            >
              <h2 class="text-body-lg font-medium leading-normal text-brand">
                {{ column.label }}
              </h2>

              <ul class="flex flex-col gap-3">
                <li v-for="child in column.children" :key="child.id">
                  <Link
                    :href="child.url"
                    :target="child.target"
                    class="text-label-lg leading-normal text-white transition-colors duration-200 ease-brand hover:text-brand"
                  >
                    {{ child.label }}
                  </Link>
                </li>
              </ul>
            </nav>

            <!-- Contact column — from settings, not the menu tree -->
            <div class="flex flex-col gap-4">
              <h2 class="text-body-lg font-medium leading-normal text-brand">
                {{ $t('footer.info') }}
              </h2>

              <ul class="flex flex-col gap-3 text-label-lg leading-normal text-white">
                <li v-if="contact.location" class="flex items-center gap-2">
                  <MapPin class="size-4 shrink-0" aria-hidden="true" />
                  <span>{{ contact.location }}</span>
                </li>
                <li v-if="contact.phone" class="flex items-center gap-2">
                  <Phone class="size-4 shrink-0" aria-hidden="true" />
                  <a
                    :href="`tel:${contact.phone.replace(/\s/g, '')}`"
                    class="latin-nums transition-colors duration-200 ease-brand hover:text-brand"
                    dir="ltr"
                  >
                    {{ contact.phone }}
                  </a>
                </li>
                <li v-if="contact.email" class="flex items-center gap-2">
                  <Mail class="size-4 shrink-0" aria-hidden="true" />
                  <a
                    :href="`mailto:${contact.email}`"
                    class="transition-colors duration-200 ease-brand hover:text-brand"
                    dir="ltr"
                  >
                    {{ contact.email }}
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <div
          class="flex flex-col items-start justify-between gap-4 border-t border-warm-300 py-6 sm:flex-row sm:items-center"
        >
          <p class="text-label-lg leading-normal text-white">
            {{ $t('footer.copyright', { year, name: settings.siteName }) }}
          </p>

          <div class="flex items-center gap-4">
            <Link
              :href="route('legal.terms')"
              class="text-label-lg leading-normal text-white underline transition-colors duration-200 ease-brand hover:text-brand"
            >
              {{ $t('footer.terms') }}
            </Link>
            <Link
              :href="route('legal.privacy')"
              class="text-label-lg leading-normal text-white underline transition-colors duration-200 ease-brand hover:text-brand"
            >
              {{ $t('footer.privacy_policy') }}
            </Link>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

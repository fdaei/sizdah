<script setup lang="ts">
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import { Mail, MapPin, Phone } from 'lucide-vue-next'
import BrandLogo from '@/Components/BrandLogo.vue'
import type { NavItem, SharedProps } from '@/types'

/**
 * Site footer. Figma I279:6327 — brand block + link columns + bottom bar.
 *
 * DEVIATION, deliberate: the Footer instance in the Sizdah file was never
 * rebranded. It still carries the SAHRA logotype, LTR English copy, the old
 * `black/*` palette and a hardcoded "© 2026 Sahra" line. Shipping it verbatim
 * would put the previous agency's name on every page of this site, so the
 * *structure* is taken from the frame (brand + tagline, link columns, rule,
 * copyright / legal row) while the content comes from `navigation.footer` and
 * `settings` and the colours come from the Sizdah ramp. Logged in
 * FIGMA/state.json.
 *
 * Geometry and colour ARE taken from the frame and were re-measured 2026-08-21:
 * py space48, a 402 brand column on gap 32, three link columns on gap 88, the
 * top block 40 above a bottom bar that pads 24 either side of its rule. Column
 * heights confirm the type runs on 1.5, not the 1.25 scale default — the Quick
 * Links column is 24 + 16 + 6x21 + 5x12 = 226, exactly the frame's height.
 *
 * Two colour findings that had been inverted here: the column TITLES are
 * black/900 #393637 (a deliberately recessive label, darker than the links) and
 * the links, tagline and bottom bar are black/600 #7B7979. Both hairlines are
 * light, not dark — the footer's top rule is black/100 #E9E9E9 and the bottom
 * bar's is black/300 #BDBCBD; both were ink-800. Sampled off the rendered frame.
 *
 * NOT implemented, deliberately: the 1248x304 outlined wordmark (1065:2315) that
 * bleeds past the footer's bottom edge. It spells SAHRA, and it is drawn as
 * #231F20 at 15% over #141414 — it renders #171717, a 3/255 tonal shift that is
 * imperceptible. Redrawing it with the Sizdah letterforms would be inventing
 * artwork for an invisible effect. Logged in .figma-sync/GAPS.md G24.
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
    <div class="container-sizdah py-12">
      <div class="flex flex-col gap-10">
        <div class="flex flex-col justify-between gap-10 md:flex-row md:items-start">
          <!-- Brand + positioning line -->
          <div class="flex max-w-[402px] flex-col gap-8">
            <BrandLogo :width="87" :title="settings.siteName" />
            <p class="text-body-lg leading-normal text-warm-600">{{ settings.tagline }}</p>
          </div>

          <!-- Link columns -->
          <div class="grid grid-cols-2 gap-10 md:flex md:gap-[88px]">
            <nav
              v-for="column in columns"
              :key="column.id"
              :aria-label="column.label"
              class="flex flex-col gap-4"
            >
              <h2 class="text-body-lg font-medium leading-normal text-warm-900">
                {{ column.label }}
              </h2>

              <ul class="flex flex-col gap-3">
                <li v-for="child in column.children" :key="child.id">
                  <Link
                    :href="child.url"
                    :target="child.target"
                    class="text-label-lg leading-normal text-warm-600 transition-colors duration-200 ease-brand hover:text-brand"
                  >
                    {{ child.label }}
                  </Link>
                </li>
              </ul>
            </nav>

            <!-- Contact column — from settings, not the menu tree -->
            <div class="flex flex-col gap-4">
              <h2 class="text-body-lg font-medium leading-normal text-warm-900">
                {{ $t('footer.info') }}
              </h2>

              <ul class="flex flex-col gap-3 text-label-lg leading-normal text-warm-600">
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
          <p class="text-label-lg leading-normal text-warm-600">
            {{ $t('footer.copyright', { year, name: settings.siteName }) }}
          </p>

          <div class="flex items-center gap-4">
            <Link
              :href="route('legal.privacy')"
              class="text-label-lg leading-normal text-warm-600 underline transition-colors duration-200 ease-brand hover:text-brand"
            >
              {{ $t('footer.privacy_policy') }}
            </Link>
            <Link
              :href="route('legal.terms')"
              class="text-label-lg leading-normal text-warm-600 underline transition-colors duration-200 ease-brand hover:text-brand"
            >
              {{ $t('footer.terms') }}
            </Link>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

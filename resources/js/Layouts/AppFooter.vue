<script setup lang="ts">
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
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
 */
const page = usePage<SharedProps>()

const settings = computed(() => page.props.settings)
const columns = computed<NavItem[]>(() => page.props.navigation.footer)
const locale = computed(() => page.props.locale.current)

const year = computed(() =>
  new Intl.DateTimeFormat(page.props.locale.htmlLang, { year: 'numeric' }).format(new Date()),
)

const contact = computed(() => settings.value.contact)
</script>

<template>
  <footer class="border-t border-ink-800 bg-ink-1000">
    <div class="container-sizdah py-12">
      <div class="flex flex-col gap-10">
        <div class="flex flex-col justify-between gap-10 md:flex-row md:items-start">
          <!-- Brand + positioning line -->
          <div class="flex max-w-[402px] flex-col gap-8">
            <BrandLogo :width="104" :title="settings.siteName" />
            <p class="text-body-lg text-ink-500">{{ settings.tagline }}</p>
          </div>

          <!-- Link columns -->
          <div class="grid grid-cols-2 gap-10 md:flex md:gap-[88px]">
            <nav
              v-for="column in columns"
              :key="column.id"
              :aria-label="column.label"
              class="flex flex-col gap-4"
            >
              <h2 class="text-body-lg font-medium text-paper">{{ column.label }}</h2>

              <ul class="flex flex-col gap-3">
                <li v-for="child in column.children" :key="child.id">
                  <Link
                    :href="child.url"
                    :target="child.target"
                    class="text-label-lg text-ink-500 transition-colors duration-200 ease-brand hover:text-brand"
                  >
                    {{ child.label }}
                  </Link>
                </li>
              </ul>
            </nav>

            <!-- Contact column — from settings, not the menu tree -->
            <div class="flex flex-col gap-4">
              <h2 class="text-body-lg font-medium text-paper">{{ $t('footer.info') }}</h2>

              <ul class="flex flex-col gap-3 text-label-lg text-ink-500">
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
          class="flex flex-col items-start justify-between gap-4 border-t border-ink-800 py-6 sm:flex-row sm:items-center"
        >
          <p class="text-label-lg text-ink-500">
            {{ $t('footer.rights', { year, name: settings.siteName }) }}
          </p>

          <div class="flex items-center gap-4">
            <Link
              :href="`/${locale}/privacy-policy`"
              class="text-label-lg text-ink-500 underline transition-colors duration-200 ease-brand hover:text-brand"
            >
              {{ $t('footer.privacy') }}
            </Link>
            <Link
              :href="`/${locale}/terms`"
              class="text-label-lg text-ink-500 underline transition-colors duration-200 ease-brand hover:text-brand"
            >
              {{ $t('footer.terms') }}
            </Link>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

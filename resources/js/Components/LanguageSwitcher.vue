<script setup lang="ts">
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import type { LocaleCode, SharedProps } from '@/types'

/**
 * Locale switcher.
 *
 * `alternates` is the same page in each language, built server-side by
 * LocaleAlternates — so switching language keeps the visitor on the article or
 * project they were reading instead of dropping them on the home page.
 *
 * Renders nothing when only one locale is live. That is the current state —
 * config/locales.php lists `fa` alone — so this collapses site-wide rather than
 * drawing a switcher with a single, un-switchable option. Restoring a locale in
 * that config brings it back with no change here.
 */
const page = usePage<SharedProps>()

const current = computed(() => page.props.locale.current)
const options = computed(() => page.props.locale.supported)
const alternates = computed(() => page.props.alternates)

function urlFor(code: LocaleCode): string {
  return alternates.value[code] ?? `/${code}`
}
</script>

<template>
  <nav
    v-if="options.length > 1"
    :aria-label="$t('common.change_language')"
    class="flex items-center gap-1"
  >
    <template v-for="(option, index) in options" :key="option.code">
      <span v-if="index > 0" class="text-ink-700" aria-hidden="true">/</span>
      <Link
        :href="urlFor(option.code)"
        :lang="option.code"
        :aria-current="option.code === current ? 'true' : undefined"
        class="rounded-xs px-1 py-1 text-label-lg transition-colors duration-200 ease-brand"
        :class="option.code === current ? 'text-brand' : 'text-ink-400 hover:text-paper'"
      >
        {{ option.native }}
      </Link>
    </template>
  </nav>
</template>

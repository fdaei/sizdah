<script setup lang="ts">
/**
 * Visible breadcrumb trail. AppLayout renders it on every page whose `seo`
 * carries `breadcrumbs` — built server-side by SeoBuilder::breadcrumbTrail(),
 * the same trail as the BreadcrumbList JSON-LD, and switched off together
 * with it (SEO settings → Breadcrumbs).
 *
 * Placement: `.section-first` starts content at 188px (172px on lg) while
 * the fixed header ends at ~124px (108px), so the trail sits in that free
 * band instead of pushing the page down. One line; it scrolls sideways on
 * narrow screens and the current page truncates.
 *
 * RTL: the chevron is mirrored so it always points "forward".
 */
import { Link } from '@inertiajs/vue3'
import { useTranslations } from '@/Composables/useTranslations'
import type { Breadcrumb } from '@/types'

defineProps<{ items: Breadcrumb[] }>()

const { t } = useTranslations()
</script>

<template>
  <nav
    :aria-label="t('common.breadcrumbs')"
    class="pointer-events-none absolute inset-x-0 top-[136px] z-10 lg:top-[124px]"
  >
    <ol
      class="container-sizdah pointer-events-auto flex items-center gap-2 overflow-x-auto whitespace-nowrap
             text-[12px] leading-normal text-ink-400 [scrollbar-width:none] lg:text-[14px]"
    >
      <li
        v-for="(item, index) in items"
        :key="item.href"
        class="flex min-w-0 items-center gap-2"
        :class="index === items.length - 1 ? 'shrink' : 'shrink-0'"
      >
        <svg
          v-if="index > 0"
          class="size-3 shrink-0 text-ink-600 rtl:-scale-x-100"
          viewBox="0 0 12 12"
          fill="none"
          aria-hidden="true"
        >
          <path d="M4.5 2.5 8 6l-3.5 3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>

        <span
          v-if="index === items.length - 1"
          aria-current="page"
          class="max-w-[60vw] truncate text-ink-100 lg:max-w-[36rem]"
        >
          {{ item.name }}
        </span>

        <Link
          v-else
          :href="item.href"
          class="rounded-sm transition-colors duration-200 ease-brand hover:text-brand focus-visible:outline focus-visible:outline-2 focus-visible:outline-brand"
        >
          {{ item.name }}
        </Link>
      </li>
    </ol>
  </nav>
</template>

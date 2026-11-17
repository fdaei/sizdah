<script setup lang="ts">
import { useTranslations } from '@/Composables/useTranslations'
import type { TocItem } from '@/types'

/**
 * Collapsible table of contents for an article (TOC Plus equivalent).
 * Native <details>/<summary> gives keyboard + screen-reader support for free;
 * links smooth-scroll unless the visitor prefers reduced motion.
 */
defineProps<{ items: TocItem[]; collapsed?: boolean }>()

const { t } = useTranslations()

function go(event: MouseEvent, id: string): void {
  const target = document.getElementById(id)
  if (!target) return

  event.preventDefault()
  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches
  target.scrollIntoView({ behavior: reduce ? 'auto' : 'smooth', block: 'start' })
  history.replaceState(history.state, '', `#${encodeURIComponent(id)}`)
  target.focus({ preventScroll: true })
}
</script>

<template>
  <nav
    :aria-label="t('blog.toc_title')"
    class="mb-10 rounded-lg border border-ink-800 bg-ink-900/40 p-5"
  >
    <details :open="!collapsed">
      <summary class="cursor-pointer select-none text-title-sm text-ink-50">
        {{ t('blog.toc_title') }}
      </summary>
      <ol class="mt-4 flex flex-col gap-2 text-body-lg">
        <li v-for="item in items" :key="item.id" :class="item.level > 2 ? 'ps-5 text-body-md' : ''">
          <a
            :href="`#${item.id}`"
            class="text-ink-200 transition-colors hover:text-brand"
            @click="go($event, item.id)"
          >
            {{ item.text }}
          </a>
        </li>
      </ol>
    </details>
  </nav>
</template>

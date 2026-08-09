<script setup lang="ts">
import { computed } from 'vue'
import { useTranslations } from '@/Composables/useTranslations'
import type { PostDetail } from '@/types'
import subjectUrl from '~img/sizdah/blog/article-subject.svg'
import authorUrl from '~img/sizdah/blog/article-author.svg'
import calendarUrl from '~img/sizdah/shared/calendar.svg'

/**
 * Article header chips — Figma "info" (292:7522 / 292:7507 / 287:5095).
 *
 * 2px Yellow/300 outline over a faint raking brand wash, label above value, and
 * a 24px glyph at the inline start. A chip is dropped when the post has no such
 * value rather than rendering an empty row.
 *
 * The date glyph in the frame is a ten-fragment variant of the same calendar
 * drawn on the blog cards; the card export is reused for it.
 */
const props = defineProps<{ post: PostDetail }>()

const { t } = useTranslations()

const chips = computed(() =>
  [
    props.post.category
      ? { key: 'subject', icon: subjectUrl, label: t('blog.subject'), value: props.post.category.name }
      : null,
    props.post.author
      ? { key: 'author', icon: authorUrl, label: t('blog.written_by'), value: props.post.author.name }
      : null,
    { key: 'date', icon: calendarUrl, label: t('blog.date'), value: props.post.publishedAt },
  ].filter((chip): chip is { key: string; icon: string; label: string; value: string } => chip !== null),
)
</script>

<template>
  <ul class="flex flex-wrap items-center gap-4">
    <li
      v-for="chip in chips"
      :key="chip.key"
      class="flex items-start gap-2 rounded-lg border-2 border-brand-200 px-6 py-3"
      style="
        background-image: linear-gradient(
          -15deg,
          rgb(248 185 55 / 0) 2.32%,
          rgb(248 185 55 / 7%) 100%
        );
      "
    >
      <img
        :src="chip.icon"
        alt=""
        aria-hidden="true"
        width="24"
        height="24"
        class="size-6 shrink-0"
      />
      <span class="flex flex-col justify-center gap-2 whitespace-nowrap">
        <span class="text-label-lg text-ink-50">{{ chip.label }}</span>
        <span class="text-body-md text-ink-200">{{ chip.value }}</span>
      </span>
    </li>
  </ul>
</template>

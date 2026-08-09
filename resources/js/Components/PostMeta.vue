<script setup lang="ts">
import { useTranslations } from '@/Composables/useTranslations'
import type { PostSummary } from '@/types'
import calendarUrl from '~img/sizdah/shared/calendar.svg'

/**
 * Reading time / date strip — Figma I277:5849;276:5728, repeated verbatim on the
 * featured row (270:5264) and every blog card.
 *
 * DOM order is date-then-reading-time so that under RTL the calendar sits at the
 * inline start, matching the frame. Under LTR it mirrors on its own.
 *
 * `publishedAt` arrives pre-formatted and digit-localised from
 * ContentTransformer; `readingTime` is a raw integer and renders in Latin
 * digits, which is what the frame shows.
 */
const props = defineProps<{ post: PostSummary }>()

const { t } = useTranslations()
</script>

<template>
  <div class="flex items-center gap-4 text-body-md text-ink-200">
    <span class="flex items-center gap-2">
      <img
        :src="calendarUrl"
        alt=""
        aria-hidden="true"
        width="24"
        height="24"
        class="size-6 shrink-0"
      />
      <time :datetime="props.post.publishedAtIso">{{ props.post.publishedAt }}</time>
    </span>

    <span class="flex items-center gap-2">
      <span aria-hidden="true" class="size-1 shrink-0 rounded-round bg-brand" />
      <span>{{ t('blog.reading_time', { minutes: props.post.readingTime }) }}</span>
    </span>
  </div>
</template>

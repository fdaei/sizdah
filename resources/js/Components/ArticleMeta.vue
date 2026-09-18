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
 * A brand-300 outline over a faint raking brand wash, label above value, and a
 * 24px glyph at the inline start. A chip is dropped when the post has no such
 * value rather than rendering an empty row.
 *
 * 2026-09-11: the outline is the hand-drawn `.sketch-frame-chip` drawing (see
 * app.css) rather than the 2px CSS border the frame measured — the same rule
 * the filter chips took, at the user's request. It keeps brand-300 as its
 * colour and the wash underneath is untouched; padding is 26/14px rather than
 * 24/12 so the chip keeps the box size the border gave it.
 *
 * 2026-09-17: switched to the same manual 9-slice FilterChips.vue uses
 * (`.sketch-frame-chip-cap-start` / `-middle` / `-cap-end`, still the same
 * filter-chip-frame.svg) instead of the single stretched `.sketch-frame-chip`
 * mask — an article's category/author value can be long enough to widen the
 * chip well past the 105x46 source drawing, which stretched the drawn 16px
 * corner arcs into ellipses exactly like the filter chips did. Only the
 * straight middle slice stretches now; the two corner caps stay circular.
 * Cap order is swapped (`-cap-end` at the visual start) to match the RTL fix
 * applied to FilterChips.vue.
 *
 * CORRECTED 2026-09-04 (PARITY Phase 1): this docblock used to claim Yellow/300
 * "has no slot in the `brand` scale, so it's an arbitrary value rather than a
 * token". That was wrong. Yellow/300 IS a Figma variable — it just does not
 * appear on Home (268:2962), and a `get_variable_defs` read scoped to Home
 * therefore reports it as absent. It is now `brand-300`.
 *
 * The date glyph in the frame is a ten-fragment variant of the same calendar
 * drawn on the blog cards; the card export is reused for it.
 */
const props = defineProps<{ post: PostDetail }>()

const { t } = useTranslations()

const chips = computed(() =>
  [
    props.post.category
      ? {
          key: 'subject',
          icon: subjectUrl,
          label: t('blog.subject'),
          value: props.post.category.name,
        }
      : null,
    props.post.author
      ? {
          key: 'author',
          icon: authorUrl,
          label: t('blog.written_by'),
          value: props.post.author.name,
        }
      : null,
    { key: 'date', icon: calendarUrl, label: t('blog.date'), value: props.post.publishedAt },
  ].filter(
    (chip): chip is { key: string; icon: string; label: string; value: string } => chip !== null,
  ),
)
</script>

<template>
  <ul class="flex flex-wrap items-center gap-4">
    <li
      v-for="chip in chips"
      :key="chip.key"
      class="relative flex items-start gap-2 rounded-lg px-[26px] py-[14px]"
      style="
        background-image: linear-gradient(
          -15deg,
          rgb(248 185 55 / 0) 2.32%,
          rgb(248 185 55 / 7%) 100%
        );
      "
    >
      <span
        aria-hidden="true"
        class="sketch-frame-chip-cap-end pointer-events-none absolute inset-y-0 start-0 w-6 bg-brand-300"
      />
      <span
        aria-hidden="true"
        class="sketch-frame-chip-middle pointer-events-none absolute inset-y-0 inset-x-6 bg-brand-300"
      />
      <span
        aria-hidden="true"
        class="sketch-frame-chip-cap-start pointer-events-none absolute inset-y-0 end-0 w-6 bg-brand-300"
      />
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

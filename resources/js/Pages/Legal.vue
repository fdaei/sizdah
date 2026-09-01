<script setup lang="ts">
import SeoHead from '@/Components/SeoHead.vue'
import type { SeoMeta } from '@/types'
import trustBadgeUrl from '~img/sizdah/legal/trust-badge.svg'

/**
 * Legal — Figma 279:5924 (privacy policy) and 281:6773 (terms of use).
 *
 * One component for both routes: LegalController hands over the Page's title,
 * intro and rich-text `content`, so the eight heading/body blocks the frame
 * draws are authored in Filament rather than hardcoded here. The frame's rhythm
 * lives in `.rich-prose` (resources/css/app.css).
 *
 * `updatedAt` is supplied by the controller but the frame shows no date, so it
 * is deliberately not rendered.
 *
 * The frame's rhythm and `.rich-prose` now line up exactly: h2 at 30/38
 * (`text-heading-lg`), body at 20/25 (`text-title-md`), 64px between blocks,
 * 24px heading-to-copy, 32px between paragraphs. That only became true once the
 * type scale was re-measured off the frames — it was set at 1.5 leading, and
 * 279:6728/279:6730 are 1.27 and 1.25. See .figma-sync/GAPS.md G14.
 */
const props = defineProps<{
  title: string
  subtitle: string
  content: string
  updatedAt: string | null
  seo: SeoMeta
}>()
</script>

<template>
  <SeoHead :seo="props.seo" />

  <!--
    Both Legal frames (279:5924 privacy, 281:6773 terms) put the title block's
    top edge at y=188, same 8px-over-`.section-first` gap as Services (G44).
    Overridden locally rather than in the shared utility, same reasoning as
    Services: with Legal now also measured at 188, the site is a 3-3 split
    against Contact/Work index/Insights index at 180 — not a clear "one
    outlier", so the safer move is another local override. See GAPS G49.
  -->
  <article class="section-first pb-24 md:pt-[188px]">
    <!--
      Decorative seal (279:5927 / 281:6776, "image 127 [Vectorized]"), identical
      on both frames: a hand-drawn badge in near-background ink (#202024/#222326
      on the #141414 ground — a barely-visible watermark, not a focal element).
      Figma's `left` is a physical offset of the full 1440 frame, not the 1248
      content track, so this reuses ServiceOrbit's `max-w-frame` pattern (see
      its own docblock: nesting in `container-sizdah` scales a frame-relative
      offset by 86.7%) rather than approximating a container-relative percentage.
      `top` is 1258px below the content start (1446 minus the frame's y=188) —
      both frames share that one fixed offset despite different total heights
      (2818 vs 2443), so it is an ambient accent, not tied to a specific
      heading, and does not need to track admin-authored copy length exactly.
      Hidden below `xl` — `container-sizdah`'s padding only matches the frame's
      96px gutter at that breakpoint, and this is the one edge of the composition
      that would otherwise drift. See GAPS G49.
    -->
    <div class="relative mx-auto hidden w-full max-w-frame xl:block">
      <img
        :src="trustBadgeUrl"
        alt=""
        aria-hidden="true"
        width="24"
        height="24"
        class="pointer-events-none absolute left-[calc(66.67%-16px)] top-[1258px] size-6"
      />
    </div>

    <div class="container-sizdah">
      <!--
        Title and intro sit in a 612px column at the inline start of the 1250
        track (279:5998); the body blocks below run the full track width.
      -->
      <header class="flex max-w-measure flex-col gap-6">
        <h1 class="text-display-lg text-ink-50">{{ props.title }}</h1>
        <p v-if="props.subtitle" class="text-title-md text-ink-200">
          {{ props.subtitle }}
        </p>
      </header>

      <!-- eslint-disable-next-line vue/no-v-html -- admin-authored rich text -->
      <div class="rich-prose mt-24" v-html="props.content" />
    </div>
  </article>
</template>

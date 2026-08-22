<script setup lang="ts">
import SeoHead from '@/Components/SeoHead.vue'
import type { SeoMeta } from '@/types'

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

  <article class="section-first pb-24">
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

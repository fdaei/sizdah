<script setup lang="ts">
import type { SectionItem } from '@/types'
import cardShapeUrl from '~img/sizdah/home/process-card.svg'

/**
 * Process step card — Figma 268:3549.
 *
 * The card's edge is a hand-drawn notched outline, not a rounded rectangle, so
 * it ships as an SVG behind the content rather than as border styling. It is the
 * one light surface in the section: ink-1000 title over Yellow/50.
 *
 * The step numeral is positional and generated here; the per-step glyph comes
 * from the section item's `icon` in the CMS, since the six differ.
 */
const props = defineProps<{
  item: SectionItem
  /** Zero-based; rendered as a padded 01…06. */
  index: number
}>()
</script>

<template>
  <li class="relative isolate flex items-center justify-center px-8 py-16">
    <img
      :src="cardShapeUrl"
      alt=""
      aria-hidden="true"
      width="380"
      height="398"
      class="absolute inset-0 -z-10 size-full"
    />

    <div class="flex w-full max-w-[280px] flex-col gap-10">
      <div class="flex items-center justify-between">
        <img
          v-if="props.item.icon"
          :src="props.item.icon"
          alt=""
          aria-hidden="true"
          width="64"
          height="68"
          class="h-[68px] w-16 object-contain"
        />
        <span v-else aria-hidden="true" />

        <p class="text-heading-xl text-brand latin-nums" aria-hidden="true">
          {{ String(props.index + 1).padStart(2, '0') }}
        </p>
      </div>

      <div class="flex flex-col gap-4">
        <h3 class="text-heading-sm text-ink-1000">{{ props.item.title }}</h3>
        <p class="text-body-lg font-medium text-ink-500">{{ props.item.description }}</p>
      </div>
    </div>
  </li>
</template>

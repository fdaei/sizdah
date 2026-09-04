<script setup lang="ts">
import { computed } from 'vue'
import type { SectionItem } from '@/types'
import discoveryUrl from '~img/sizdah/home/step-discovery.svg'
import strategyUrl from '~img/sizdah/home/step-strategy.svg'
import productionUrl from '~img/sizdah/home/step-production.svg'
import publishingUrl from '~img/sizdah/home/step-publishing.svg'
import campaignUrl from '~img/sizdah/home/step-campaign.svg'
import optimizationUrl from '~img/sizdah/home/step-optimization.svg'
import approvalUrl from '~img/sizdah/home/why-quality.svg'

/**
 * Process step card — Figma 523:5646, one cell of the six-up grid at 523:5798.
 *
 * REDRAWN: this card used to be the hand-drawn notched outline on Yellow/50
 * (268:3549). The frame was redesigned — it is now a dark cell, ink-900 under a
 * raking 20% brand wash that runs bright at the top-start corner and fades out
 * at the bottom-end, with a flat 2px ink-300 edge and square corners. The
 * cells butt against each other, so their edges collapse to a single 2px rule;
 * the grid draws those rules rather than each card carrying its own border
 * (see the parent in Home.vue).
 *
 * The step numeral is positional and generated here. The frame shows "01" on
 * all six cells — the component default was never overridden per instance — so
 * the sequence is authored here rather than read from the design.
 *
 * Re-measured against the component set 537:5510 on 2026-08-21. Padding, gaps,
 * ground and type all held; two things did not:
 *   - the numeral 537:5993 is Maneli **Regular** 32/40, not Medium. It was
 *     taking `text-heading-xl`, which carries weight 500.
 *   - the set ships a `Property 1=hover` variant (537:5511) that nothing
 *     implemented: the cell flips to a flat #f3b435 — a hair off Yellow/1000,
 *     and the file's only use of it — and the copy inverts to the dark ramp.
 *
 * DEVIATION: the hover variant also re-aligns the numeral row from `center` to
 * `start`, which would jog the numeral ~12px on pointer-enter. That reads as
 * authoring drift rather than intent, so the alignment is held steady and only
 * the colour inverts.
 */
const props = defineProps<{
  item: SectionItem
  /** Zero-based; rendered as a padded 01…06. */
  index: number
}>()

/*
 | Keyed on SectionItem.icon. Five of the six seeded steps have a glyph drawn
 | for them in 523:5798; `approval` (بازبینی و تأیید) is a step the CMS has and
 | the frame does not, so it borrows the check-badge from the why-us set
 | (268:3670) — the only "verified" mark in the file. `campaign` is the reverse
 | case: drawn in the frame, unused by the current copy, registered so an editor
 | can reach it.
 */
const ICONS: Record<string, string> = {
  discovery: discoveryUrl,
  strategy: strategyUrl,
  production: productionUrl,
  approval: approvalUrl,
  publishing: publishingUrl,
  campaign: campaignUrl,
  optimization: optimizationUrl,
}

const iconUrl = computed(() => (props.item.icon ? ICONS[props.item.icon] : undefined))
</script>

<template>
  <li
    class="process-wash group flex flex-col gap-6 px-8 py-10 transition-colors duration-200 ease-brand hover:bg-brand-pressed hover:bg-none"
  >
    <!--
      537:5890 — glyph at the inline start, numeral opposite. DOM order is the
      reverse of the Figma export because the frame is laid out RTL.
    -->
    <div class="flex items-center justify-between gap-4">
      <img
        v-if="iconUrl"
        :src="iconUrl"
        alt=""
        aria-hidden="true"
        width="64"
        height="64"
        class="size-16 shrink-0 object-contain transition duration-200 ease-brand group-hover:brightness-0"
      />
      <span v-else aria-hidden="true" class="size-16 shrink-0" />

      <p
        class="latin-nums font-display text-heading-xl font-normal text-brand transition-colors duration-200 ease-brand group-hover:text-ink-1000"
        aria-hidden="true"
      >
        {{ String(props.index + 1).padStart(2, '0') }}
      </p>
    </div>

    <div class="flex flex-col gap-4">
      <h3
        class="text-heading-md text-paper transition-colors duration-200 ease-brand group-hover:text-ink-1000"
      >
        {{ props.item.title }}
      </h3>
      <p
        v-if="props.item.description"
        class="text-title-sm text-ink-200 transition-colors duration-200 ease-brand group-hover:text-ink-800"
      >
        {{ props.item.description }}
      </p>
    </div>
  </li>
</template>

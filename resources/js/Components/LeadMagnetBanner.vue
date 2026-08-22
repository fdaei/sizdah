<script setup lang="ts">
import CtaButton from '@/Components/CtaButton.vue'
import type { PageSectionData } from '@/types'
import doodleUrl from '~img/sizdah/shared/checklist-doodle.svg'

/**
 * Lead magnet strip — Figma "lead magnet" 303:4455 (article) and 391:4795
 * (Home). The one cream surface in an otherwise dark design: Yellow/200 fill
 * under a raking brand glow, with ink copy.
 *
 * The two frames are the same strip at two scales, so they share this
 * component and differ only by `size`:
 *   - `sm` — 303:4455, mid-article. space32 padding, radiusSM, no rule.
 *   - `lg` — 391:4795, Home. space64 padding, 24px corners, a 3px brand rule
 *            and a hollow brand button instead of the filled one.
 *
 * The frame draws a button but no email field, so this links to the section's
 * own CTA rather than posting inline; the newsletter route stays where the
 * form that collects an address lives.
 */
const props = withDefaults(defineProps<{ section: PageSectionData; size?: 'sm' | 'lg' }>(), {
  size: 'sm',
})
</script>

<template>
  <aside
    class="flex flex-col items-center gap-6 overflow-hidden md:flex-row md:justify-between"
    :class="props.size === 'lg' ? 'rounded-xl border-3 border-brand p-8 lg:p-16' : 'rounded-sm p-8'"
    style="
      background-image:
        linear-gradient(-8.86deg, rgb(248 185 55 / 0) 2.32%, rgb(248 185 55 / 10%) 100%),
        linear-gradient(0deg, #fef1d7, #fef1d7);
    "
  >
    <div
      class="flex flex-col gap-2 text-center md:text-start"
      :class="props.size === 'lg' ? 'max-w-[546px] gap-4' : 'max-w-[458px]'"
    >
      <p
        :class="
          props.size === 'lg' ? 'text-heading-sm text-ink-1000' : 'text-title-md text-ink-900'
        "
      >
        {{ props.section.title }}
      </p>
      <p
        v-if="props.section.description"
        :class="props.size === 'lg' ? 'text-body-lg text-ink-600' : 'text-body-md text-ink-600'"
      >
        {{ props.section.description }}
      </p>
    </div>

    <CtaButton
      v-if="props.section.primaryCta"
      :label="props.section.primaryCta.label"
      :href="props.section.primaryCta.url"
      :variant="props.size === 'lg' ? 'brand-outline' : 'solid'"
      class="shrink-0"
    />

    <img
      :src="doodleUrl"
      alt=""
      aria-hidden="true"
      width="64"
      height="49"
      class="hidden h-[49px] w-16 shrink-0 flip-rtl md:block"
      :class="props.size === 'lg' && 'md:hidden'"
    />
  </aside>
</template>

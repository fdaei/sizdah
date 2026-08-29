<script setup lang="ts">
import CtaButton from '@/Components/CtaButton.vue'
import type { PageSectionData } from '@/types'
import doodleUrl from '~img/sizdah/shared/checklist-doodle.svg'
import leadMagnetDotsUrl from '~img/sizdah/home/lead-magnet-dots.svg'
import leadMagnetGridUrl from '~img/sizdah/home/lead-magnet-grid.svg'
import leadMagnetScribbleUrl from '~img/sizdah/home/lead-magnet-scribble.svg'

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
    class="relative flex flex-col items-center gap-6 overflow-hidden"
    :class="
      props.size === 'lg'
        ? 'min-h-[272px] rounded-xl border-3 border-brand bg-brand-200 p-8 lg:flex-row lg:justify-between lg:p-16'
        : 'rounded-sm p-8 md:flex-row md:justify-between'
    "
    :style="
      props.size === 'sm'
        ? {
          backgroundImage:
            'linear-gradient(-8.86deg, rgb(248 185 55 / 0) 2.32%, rgb(248 185 55 / 10%) 100%), linear-gradient(0deg, #fef1d7, #fef1d7)',
        }
        : undefined
    "
  >
    <template v-if="props.size === 'lg'">
      <img
        :src="leadMagnetGridUrl"
        alt=""
        aria-hidden="true"
        width="401"
        height="401"
        class="pointer-events-none absolute right-0 top-0 hidden h-full w-[55%] object-cover lg:block"
      />
      <img
        :src="leadMagnetDotsUrl"
        alt=""
        aria-hidden="true"
        width="26"
        height="48"
        class="pointer-events-none absolute left-[60px] top-[24px] hidden h-[48px] w-[26px] lg:block"
      />
      <img
        :src="leadMagnetScribbleUrl"
        alt=""
        aria-hidden="true"
        width="56"
        height="56"
        class="pointer-events-none absolute left-[306px] top-[128px] hidden size-14 lg:block"
      />
    </template>

    <div
      class="relative z-10 flex flex-col gap-2 text-center"
      :class="props.size === 'lg' ? 'max-w-[546px] gap-4 lg:w-[546px]' : 'max-w-[458px]'"
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
        :class="
          props.size === 'lg'
            ? 'text-body-lg text-ink-600 lg:text-start'
            : 'text-body-md text-ink-600 md:text-start'
        "
      >
        {{ props.section.description }}
      </p>
    </div>

    <CtaButton
      v-if="props.section.primaryCta"
      :label="props.section.primaryCta.label"
      :href="props.section.primaryCta.url"
      :variant="props.size === 'lg' ? 'brand-outline' : 'solid'"
      class="relative z-10 shrink-0"
    />

    <img
      :src="doodleUrl"
      alt=""
      aria-hidden="true"
      width="64"
      height="49"
      class="hidden h-[49px] w-16 shrink-0 flip-rtl md:block"
      :class="props.size === 'lg' && 'hidden'"
    />
  </aside>
</template>

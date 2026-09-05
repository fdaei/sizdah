<script setup lang="ts">
import { ref } from 'vue'
import CtaButton from '@/Components/CtaButton.vue'
import LeadMagnetModal from '@/Components/LeadMagnetModal.vue'
import type { PageSectionData } from '@/types'
import doodleUrl from '~img/sizdah/shared/checklist-doodle.svg'
import articleAccentUrl from '~img/sizdah/blog/lead-magnet-accent.svg'
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
 *   - `lg` — 391:4795, Home. 1036 wide inside the 1248 track, space64 padding,
 *            24px corners, a 3px brand rule drawn INSIDE the box (so the card
 *            stays 1036 x 194), Yellow/50 — not the Yellow/200 the `sm` strip
 *            fills with — and a hollow brand button instead of the filled one.
 *
 * The button opens `LeadMagnetModal` — see that component's docblock and
 * GAPS G51. An earlier note here read "the frame draws a button but no email
 * field, so this links to the section's own CTA" — true of 303:4455/391:4795
 * themselves, but incomplete: the actual field lives in a modal these two
 * frames open, found later nested inside an unrelated "blog list" exploration
 * frame rather than filed as its own component. The modal has its own fixed
 * title/description (see its docblock) — this banner's own copy is not
 * passed through to it.
 */
const props = withDefaults(
  defineProps<{
    section: PageSectionData
    size?: 'sm' | 'lg'
    /** Matches `NewsletterSubscriptionRequest`'s `source` enum. */
    source: 'home' | 'article'
  }>(),
  { size: 'sm' },
)

const modalOpen = ref(false)
</script>

<template>
  <aside
    class="relative flex flex-col items-center gap-6 overflow-hidden"
    :class="
      props.size === 'lg'
        ? 'mx-auto max-w-[1036px] rounded-xl bg-brand-50 p-8 ring-[3px] ring-inset ring-brand lg:flex-row lg:justify-between lg:p-16'
        : 'w-full max-w-[826px] rounded-lg p-8 md:flex-row md:items-center md:justify-between md:gap-6'
    "
    :style="
      props.size === 'sm'
        ? {
            backgroundImage:
              'linear-gradient(-8.864deg, rgb(248 185 55 / 0) 2.3248%, rgb(248 185 55 / 10%) 100%), linear-gradient(90deg, #fef1d7 0%, #fef1d7 100%)',
          }
        : undefined
    "
  >
    <img
      v-if="props.size === 'sm'"
      :src="doodleUrl"
      alt=""
      aria-hidden="true"
      width="64"
      height="50"
      class="hidden h-[49.419px] w-16 shrink-0 md:block"
    />

    <template v-if="props.size === 'lg'">
      <!--
        391:4800 is a 400x400 ruled sheet parked at the card's inline end and
        pulled 85 above its top edge, so `overflow-hidden` crops it to the
        right third. Stretching it to `h-full w-[55%] object-cover` turned its
        horizontal rules into vertical ones.
      -->
      <img
        :src="leadMagnetGridUrl"
        alt=""
        aria-hidden="true"
        width="400"
        height="400"
        class="pointer-events-none absolute left-[699px] top-[-85px] hidden size-[400px] max-w-none lg:block"
      />
      <!-- 391:4837, a 3x2 dot grid — 48x26, not the 26x48 it was exported at. -->
      <img
        :src="leadMagnetDotsUrl"
        alt=""
        aria-hidden="true"
        width="48"
        height="26"
        class="pointer-events-none absolute left-[66px] top-[26px] hidden h-[26px] w-[48px] lg:block"
      />
      <img
        :src="leadMagnetScribbleUrl"
        alt=""
        aria-hidden="true"
        width="56"
        height="56"
        class="pointer-events-none absolute left-[308px] top-[130px] hidden size-14 lg:block"
      />
    </template>

    <div
      class="relative z-10 flex flex-col gap-2"
      :class="
        props.size === 'lg'
          ? 'max-w-[580px] text-center gap-4 lg:w-[580px]'
          : 'w-full max-w-[458px] items-end text-start md:w-[458px]'
      "
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

    <div v-if="props.section.primaryCta" class="relative z-10 shrink-0">
      <CtaButton
        :label="props.section.primaryCta.label"
        :variant="props.size === 'lg' ? 'brand-outline' : 'solid'"
        @click="modalOpen = true"
      />
    </div>

    <img
      v-if="props.size === 'sm'"
      :src="articleAccentUrl"
      alt=""
      aria-hidden="true"
      width="16"
      height="20"
      class="pointer-events-none absolute left-[19px] top-1/2 hidden h-5 w-4 -translate-y-1/2 md:block"
    />

  </aside>

  <LeadMagnetModal :open="modalOpen" :source="props.source" @close="modalOpen = false" />
</template>

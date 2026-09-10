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
 * (Home). The one cream surface in an otherwise dark design: a flat Yellow/50
 * fill with ink copy.
 *
 * The two frames are the same strip at two scales, so they share this
 * component and differ only by `size`:
 *   - `sm` — 303:4455, 826 x 139 mid-article. space32 padding, radiusSM, no
 *            rule, and a filled brand button.
 *   - `lg` — 391:4795, Home. 1036 wide inside the 1248 track, space64 padding,
 *            and a hollow brand button instead of the filled one. The frame
 *            measures a 3px brand rule at 24px corners, drawn INSIDE the box;
 *            since 2026-09-11 (GAPS G68) it wears the site-wide
 *            `.sketch-frame` instead, which is likewise inset and so keeps the
 *            card at 1036 x 194. `sm` stays unruled.
 *
 * Re-read against the live frame 2026-09-08. Four things had drifted on `sm`
 * and are corrected: the fill was Yellow/200 under a raking brand gradient
 * (the frame is a flat #FEFBF5 — sampled on the render, corner to corner);
 * corners were radiusLG; the 303:4458 ruled sheet behind the copy was missing
 * altogether; and the 303:4482 accent was centred vertically instead of
 * sitting 34px down. The content column also carried `items-end`, which under
 * RTL resolves to the LEFT edge and flushed the description away from the
 * headline it is meant to hang under.
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
        ? 'sketch-frame mx-auto max-w-[1036px] bg-brand-50 p-8 lg:flex-row lg:justify-between lg:p-16'
        : 'w-full max-w-[826px] rounded-sm bg-brand-50 p-8 md:flex-row md:items-center md:justify-between md:gap-6'
    "
  >
    <img
      v-if="props.size === 'sm'"
      :src="doodleUrl"
      alt=""
      aria-hidden="true"
      width="64"
      height="50"
      class="relative z-20 hidden h-[49.419px] w-16 shrink-0 md:block"
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
          : 'w-full max-w-[458px] text-start md:w-[458px]'
      "
    >
      <!--
        303:4458 — the same ruled sheet the Home banner draws, parented to the
        content column rather than the card: 400x400 at (305, -102) from the
        column's top-left, so `overflow-hidden` on the card crops it to a band
        behind the copy and on past the doodle. `-z-10` keeps it under the two
        paragraphs without dropping it behind the card's own fill.
      -->
      <img
        v-if="props.size === 'sm'"
        :src="leadMagnetGridUrl"
        alt=""
        aria-hidden="true"
        width="400"
        height="400"
        class="pointer-events-none absolute left-[305px] top-[-102px] -z-10 hidden size-[400px] max-w-none md:block"
      />

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
      <!--
        303:4482 — the hand-drawn arrow that points at the button. The frame
        puts it at (19, 34) and the CTA at (32, 46.5), i.e. 13 left of and 12.5
        above the button's top-inline corner. Those two offsets are what the
        design fixes; the card-relative 34 is not, because the card only
        measures 139 when the copy is the frame's own two-line title. The
        seeded copy is shorter (114.5), the CTA rides up with it, and an
        accent pinned to the card lands flat on the button instead. So it
        hangs off the button, which is what it is drawn against.
      -->
      <img
        v-if="props.size === 'sm'"
        :src="articleAccentUrl"
        alt=""
        aria-hidden="true"
        width="16"
        height="20"
        class="pointer-events-none absolute -left-[13px] -top-[12.5px] hidden h-5 w-4 md:block"
      />
    </div>
  </aside>

  <LeadMagnetModal :open="modalOpen" :source="props.source" @close="modalOpen = false" />
</template>

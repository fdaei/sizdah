<script setup lang="ts">
import Eyebrow from '@/Components/Eyebrow.vue'

/**
 * The repeated Home section header — Figma 268:3461 (projects), 268:3468
 * (process), 268:3797 (insights), 268:3725 (testimonials), 268:3037 (services
 * band), 268:3479 (why us) and 434:5470 (FAQ).
 *
 * All seven are the same three parts: the "small title" eyebrow, a 40px Bold
 * headline and a muted subtitle. They differ only in whether the subtitle sits
 * opposite the headline (`split`) or beneath it (`stacked`), and whether the
 * ground is the dark page or the one cream band.
 *
 * `title` renders `whitespace-pre-line` because several frames break the
 * headline across two lines ("هر برند / داستان خودش را دارد") — the break is
 * authored in the CMS field rather than hardcoded here.
 *
 * DEVIATION: the frames set the headline at a fixed 40px in either Black/50
 * (#F3F3F3) or Yellow/50 (#FEFBF5), inconsistently and with no pattern to it.
 * Both read as the same off-white at this size, so `ink-50` carries all of
 * them, and `section-line` supplies the clamp so long Persian headlines do not
 * overflow below 1440 — its maximum is the frame's 40px exactly.
 */
withDefaults(
  defineProps<{
    eyebrow?: string
    title: string
    subtitle?: string
    /** `split` sets the subtitle opposite the headline; `stacked`, beneath it. */
    layout?: 'split' | 'stacked'
    /** `light` is the cream services band (268:3032). */
    tone?: 'dark' | 'light'
    /**
     * Space between the eyebrow and the headline row. The frames carry two
     * values with no rule behind them: 24 on projects (268:3461), the services
     * band (502:5424) and why-us (268:3475); 48 on testimonials (268:3721) and
     * insights (268:3797), which are two copies of one block. See GAPS G45.
     */
    gap?: 'sm' | 'lg'
    /**
     * Width of the subtitle column. The frames set it per instance (505 on the
     * services band, 268:3039), so it is a prop rather than one shared value;
     * 430 is what the split instances measured at before they were re-read.
     */
    subtitleWidth?: string
  }>(),
  {
    eyebrow: '',
    subtitle: '',
    layout: 'split',
    tone: 'dark',
    gap: 'sm',
    subtitleWidth: '',
  },
)
</script>

<template>
  <div class="flex flex-col items-start" :class="gap === 'lg' ? 'gap-12' : 'gap-6'">
    <Eyebrow
      v-if="eyebrow"
      :text="eyebrow"
      :marker-ring="tone === 'dark' ? 'paper' : 'ink'"
    />

    <div
      class="flex w-full flex-col gap-6"
      :class="layout === 'split' && 'md:flex-row md:items-center md:justify-between'"
    >
      <h2
        class="whitespace-pre-line text-section-line font-bold"
        :class="tone === 'light' ? 'text-ink-1000' : 'text-ink-50'"
      >
        {{ title }}
      </h2>

      <p
        v-if="subtitle"
        class="text-title-md"
        :class="[
          tone === 'light' ? 'text-ink-700' : 'text-ink-200',
          subtitleWidth || (layout === 'split' ? 'md:max-w-[430px]' : 'max-w-[400px]'),
        ]"
      >
        {{ subtitle }}
      </p>
    </div>
  </div>
</template>

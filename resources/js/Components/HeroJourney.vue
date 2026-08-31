<script setup lang="ts">
import strategyUrl from '~img/sizdah/home/hero/hero-strategy.svg'
import identityUrl from '~img/sizdah/home/hero/hero-identity.svg'
import contentUrl from '~img/sizdah/home/hero/hero-content.svg'
import growthUrl from '~img/sizdah/home/hero/hero-growth.svg'
import arcUrl from '~img/sizdah/home/hero/hero-arc.svg'
import { useTranslations } from '@/Composables/useTranslations'

/**
 * Hero illustration — Figma 268:2962, the loose vector group at x184..650,
 * y216..717: four hand-drawn marks (notebook, bulb, laptop, rocket) joined by a
 * dotted trail, each captioned.
 *
 * REPLACES `hero-journey.png`. That export is stale — it is an older draft of
 * this art (numbered 01–04 cards with body copy) flattened onto an opaque
 * #141414 ground, and it was exported so dark that 99% of its pixels sit below
 * luminance 44: on the page it read as an empty black rectangle. The current
 * frame draws the art as vectors in cream (#FFF8EB) and brand (#F8B937) on
 * transparency, which is what this ships.
 *
 * Laid out the same way as ServiceOrbit: percentage offsets inside a
 * fixed-ratio box, so the composition scales without coming apart. Offsets are
 * measured off the frame, including the deliberately long drop from each mark
 * to its caption — verified against a render of 268:2962, not inferred.
 *
 * The captions are real text so they translate and stay selectable; only the
 * marks and the trail are artwork. The trail is drawn here as three dashed
 * paths rather than exported: the frame stores it as ~50 separate 5–17px
 * fragments, which is a drawing tool artefact and not worth 50 requests.
 */
const { t } = useTranslations()
</script>

<template>
  <!--
    466x500 is the composition box itself (x184..650, y216..717 on the frame),
    so it is also the cap: at the old max-w-[560px] every offset below scaled
    with it and the whole trail rendered ~20% oversized.
  -->
  <div class="relative aspect-[466/500] w-full max-w-[466px]" aria-hidden="true">
    <!--
      Dotted trail, Black/600 in the frame. viewBox matches the composition box
      so the paths land on the marks at any scale.
    -->
    <svg
      class="absolute inset-0 size-full"
      viewBox="0 0 466 500"
      fill="none"
      preserveAspectRatio="none"
    >
      <g
        stroke="#6C6C69"
        stroke-width="2"
        stroke-dasharray="6 8"
        stroke-linecap="round"
        fill="none"
      >
        <path d="M128 57 C 190 100, 270 96, 339 56" />
        <path d="M391 112 C 362 160, 372 212, 395 256" />
        <path d="M125 310 C 190 352, 282 348, 348 312" />
      </g>
    </svg>

    <!-- 416:4529 — the hand-drawn descender from the notebook to the laptop. -->
    <img
      :src="arcUrl"
      alt=""
      width="47"
      height="172"
      class="absolute left-[2.36%] top-[19.83%] w-[10.17%]"
    />

    <img
      :src="strategyUrl"
      alt=""
      width="129"
      height="96"
      class="absolute left-0 top-[1.79%] w-[27.53%]"
    />
    <img
      :src="identityUrl"
      alt=""
      width="104"
      height="111"
      class="absolute left-[72.75%] top-0 w-[22.31%]"
    />
    <img
      :src="contentUrl"
      alt=""
      width="124"
      height="93"
      class="absolute left-[0.21%] top-[53.59%] w-[26.5%]"
    />
    <img
      :src="growthUrl"
      alt=""
      width="95"
      height="111"
      class="absolute left-[74.68%] top-[51.98%] w-[20.3%]"
    />

    <p class="absolute left-[6.65%] top-[32.8%] text-title-md font-semibold text-paper">
      {{ t('home.journey.strategy') }}
    </p>
    <p class="absolute left-[82.19%] top-[34.42%] text-title-md font-semibold text-paper">
      {{ t('home.journey.identity') }}
    </p>
    <p class="absolute left-[10.73%] top-[94.92%] text-title-md font-semibold text-paper">
      {{ t('home.journey.content') }}
    </p>
    <p class="absolute left-[84.12%] top-[96.55%] text-title-md font-semibold text-paper">
      {{ t('home.journey.growth') }}
    </p>
  </div>
</template>

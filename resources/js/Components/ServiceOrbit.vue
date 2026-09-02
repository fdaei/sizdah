<script setup lang="ts">
import { computed } from 'vue'
import SectionHeading from '@/Components/SectionHeading.vue'
import type { PageSectionData, ServiceItem } from '@/types'
import centerUrl from '~img/sizdah/home/orbit-center.svg'
import wordmarkUrl from '~img/sizdah/home/orbit-wordmark.svg'
import contentUrl from '~img/sizdah/home/orbit-content.svg'
import brandingUrl from '~img/sizdah/home/orbit-branding.svg'
import marketingUrl from '~img/sizdah/home/orbit-marketing.svg'
import socialUrl from '~img/sizdah/home/orbit-social.svg'
import lineTopStartUrl from '~img/sizdah/home/orbit-line-tl.svg'
import lineTopEndUrl from '~img/sizdah/home/orbit-line-tr.svg'
import lineBottomStartUrl from '~img/sizdah/home/orbit-line-bl.svg'
import lineBottomEndUrl from '~img/sizdah/home/orbit-line-br.svg'
import gridDoodleUrl from '~img/sizdah/home/orbit-grid.svg'
import scribbleUrl from '~img/sizdah/home/orbit-scribble.svg'
import crossUrl from '~img/sizdah/home/orbit-cross.svg'
import dotsUrl from '~img/sizdah/home/orbit-dots.svg'

/**
 * Services band — Figma 268:3032, the one cream (#FDFCFA) section on Home.
 *
 * Four services orbit a hand-drawn "LOST LEVEL" badge, each joined to it by a
 * dashed connector. The frame is a 1440x1130 absolute composition, so above
 * `lg` this reproduces it as percentage offsets inside a fixed-ratio box —
 * every piece then scales with the viewport instead of breaking apart. Below
 * `lg` the diagram cannot survive (the connectors would point nowhere), so it
 * degrades to a plain grid of illustration + label and the badge moves above.
 *
 * The offsets use **physical** left/top rather than the project's usual logical
 * properties, deliberately: the frame is already the RTL composition, and a
 * diagram must not mirror itself when an English visitor loads the page. Only
 * the labels below each illustration follow the reading direction.
 *
 * Artwork is matched on `ServiceItem.icon`, the one service field that is not
 * translated — see ContentTransformer::service(). Slots are fixed by the frame,
 * so a service whose icon is unknown falls into whichever slot is still free
 * and the section still renders four nodes.
 */
const props = defineProps<{
  section: PageSectionData
  services: ServiceItem[]
}>()

/*
 | Geometry read straight off 268:3032. `left`/`top`/`width` are percentages of
 | the 1440 x 760 diagram box, whose origin is the frame's y=290 — the first
 | pixel below the heading block.
 */
type Slot = {
  icon: string
  art: string
  /** Natural size of the export, so the box never collapses while it loads. */
  size: [number, number]
  left: string
  top: string
  width: string
  line: { src: string; left: string; top: string }
}

const SLOTS: Slot[] = [
  {
    icon: 'video',
    art: contentUrl,
    size: [240, 246],
    left: '13.89%',
    top: '1.97%',
    width: '16.67%',
    line: { src: lineTopStartUrl, left: '29.76%', top: '25.86%' },
  },
  {
    icon: 'palette',
    art: brandingUrl,
    size: [240, 260],
    left: '69.44%',
    top: '2.24%',
    width: '16.67%',
    line: { src: lineTopEndUrl, left: '58.98%', top: '25.98%' },
  },
  {
    icon: 'share-2',
    art: socialUrl,
    size: [282, 246],
    left: '8.89%',
    top: '48.03%',
    width: '21.67%',
    line: { src: lineBottomStartUrl, left: '29.76%', top: '51.45%' },
  },
  {
    icon: 'megaphone',
    art: marketingUrl,
    size: [240, 246],
    left: '69.44%',
    top: '48.42%',
    width: '16.67%',
    line: { src: lineBottomEndUrl, left: '57.71%', top: '51.45%' },
  },
]

/**
 * Pairs each frame slot with a service. Icon match first; anything left over is
 * dealt out in authored order so the diagram never renders an empty node.
 */
const nodes = computed(() => {
  const pool = [...props.services]

  return SLOTS.map((slot) => {
    const exact = pool.findIndex((service) => service.icon === slot.icon)
    const index = exact === -1 ? 0 : exact
    const service = pool.length > 0 ? pool.splice(index, 1)[0] : null

    return { slot, service }
  }).filter((node) => node.service !== null)
})
</script>

<template>
  <section class="bg-[#FDFCFA] py-16 lg:pb-20 lg:pt-[91px]">
    <div class="container-sizdah">
      <SectionHeading
        data-reveal
        :eyebrow="props.section.eyebrow"
        :title="props.section.title"
        :subtitle="props.section.subtitle || props.section.description"
        tone="light"
        subtitle-width="md:max-w-[505px]"
      />
    </div>

    <!--
      Desktop composition. `aspect-[1440/760]` keeps the absolute offsets valid
      at every width, so the connectors stay attached to their nodes.
    -->
    <!--
      Full-bleed to the frame's own 1440, NOT the 1248 content track: every
      offset below is a percentage of the frame's 1440x760 composition box, so
      nesting this in `container-sizdah` scaled the whole diagram to 86.7% and
      pulled all four nodes in towards the badge.
    -->
    <div class="mt-12 hidden lg:mt-20 lg:block">
      <div class="relative mx-auto aspect-[1440/760] w-full max-w-frame" data-reveal>
        <!-- Decorative: the tic-tac-toe hatch above the badge (268:3405). -->
        <img
          :src="gridDoodleUrl"
          alt=""
          aria-hidden="true"
          width="160"
          height="143"
          class="absolute left-[41.25%] top-[0.39%] w-[11.11%]"
        />

        <!--
          Decorative: the two Yellow/900 crosses (268:3460 at 138,2120 and
          268:3459 at 1090,2552) and the 3x2 dot grid (268:3437 at 1282,2158).
          All three were missing from the band.
        -->
        <img
          :src="crossUrl"
          alt=""
          aria-hidden="true"
          width="24"
          height="24"
          class="absolute left-[9.58%] top-[40%] w-[1.67%]"
        />
        <img
          :src="crossUrl"
          alt=""
          aria-hidden="true"
          width="24"
          height="24"
          class="absolute left-[75.69%] top-[96.84%] w-[1.67%]"
        />
        <img
          :src="dotsUrl"
          alt=""
          aria-hidden="true"
          width="47"
          height="26"
          class="absolute left-[89.03%] top-[45%] w-[3.26%]"
        />

        <!-- Decorative: the scribble below the badge (268:3420). -->
        <img
          :src="scribbleUrl"
          alt=""
          aria-hidden="true"
          width="104"
          height="104"
          class="absolute left-[52.22%] top-[85.13%] w-[7.22%]"
        />

        <!-- Connectors sit under the nodes so the dashes tuck behind the art. -->
        <img
          v-for="node in nodes"
          :key="`line-${node.slot.icon}`"
          :src="node.slot.line.src"
          alt=""
          aria-hidden="true"
          width="160"
          height="97"
          class="absolute w-[11.48%]"
          :style="{ left: node.slot.line.left, top: node.slot.line.top }"
        />

        <!-- Hand-drawn badge (268:3040) with the wordmark centred on it. -->
        <div class="absolute left-[39.44%] top-[26.84%] w-[21.11%]">
          <img :src="centerUrl" alt="" aria-hidden="true" width="304" height="261" class="w-full" />
          <img
            :src="wordmarkUrl"
            alt="Lost Level"
            width="100"
            height="73"
            class="absolute left-[32.57%] top-[39.46%] w-[32.89%]"
          />
        </div>

        <div
          v-for="node in nodes"
          :key="node.slot.icon"
          class="absolute flex flex-col items-center gap-2"
          :style="{ left: node.slot.left, top: node.slot.top, width: node.slot.width }"
        >
          <img
            :src="node.slot.art"
            alt=""
            aria-hidden="true"
            :width="node.slot.size[0]"
            :height="node.slot.size[1]"
            class="w-full"
          />
          <p class="text-center text-heading-sm text-ink-1000">{{ node.service!.title }}</p>
        </div>
      </div>
    </div>

    <!--
      Below `lg` the connectors have nowhere to point, so the diagram becomes
      a plain grid and the badge leads it.
    -->
    <div class="container-sizdah mt-12 lg:hidden">
      <div class="flex flex-col items-center gap-10">
        <div class="relative w-[220px]">
          <img :src="centerUrl" alt="" aria-hidden="true" width="304" height="261" class="w-full" />
          <img
            :src="wordmarkUrl"
            alt="Lost Level"
            width="100"
            height="73"
            class="absolute left-[32.57%] top-[39.46%] w-[32.89%]"
          />
        </div>

        <ul class="grid w-full grid-cols-1 gap-10 sm:grid-cols-2" data-reveal-group>
          <li v-for="node in nodes" :key="node.slot.icon" class="flex flex-col items-center gap-2">
            <img
              :src="node.slot.art"
              alt=""
              aria-hidden="true"
              :width="node.slot.size[0]"
              :height="node.slot.size[1]"
              class="w-full max-w-[240px]"
            />
            <p class="text-center text-heading-sm text-ink-1000">{{ node.service!.title }}</p>
          </li>
        </ul>
      </div>
    </div>
  </section>
</template>

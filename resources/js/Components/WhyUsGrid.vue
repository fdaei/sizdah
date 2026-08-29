<script setup lang="ts">
import { computed } from 'vue'
import SectionHeading from '@/Components/SectionHeading.vue'
import type { PageSectionData } from '@/types'
import outcomesUrl from '~img/sizdah/home/why-outcomes.svg'
import brandUrl from '~img/sizdah/home/why-brand.svg'
import endToEndUrl from '~img/sizdah/home/why-endtoend.svg'
import qualityUrl from '~img/sizdah/home/why-quality.svg'
import ruleVerticalUrl from '~img/sizdah/home/why-rule-vertical.svg'
import ruleTopUrl from '~img/sizdah/home/why-rule-a.svg'
import ruleBottomUrl from '~img/sizdah/home/why-rule-b.svg'

/**
 * Why us — Figma 268:3475 (heading) with the four claims at 268:3708, 268:3711,
 * 268:3714 and 268:3717, quartered by the hand-drawn rules 268:2993 (vertical)
 * and 268:2994 / 268:2995 (horizontal).
 *
 * The frame positions all four absolutely inside a 680x400 box. That is not
 * worth reproducing literally — the copy is CMS-authored and any of it can run
 * long — so the claims sit in a real 2x2 grid and the rules are laid over it as
 * decoration. The two horizontals are deliberately staggered (the frame puts
 * them 56px apart in y), which is what keeps the quartering from reading as a
 * plain table; their offsets are kept.
 *
 * The rules are positioned physically rather than logically: they are one
 * drawing, and a drawing must not mirror itself when the reading direction
 * changes. They are dropped below `sm`, where the grid is a single column and
 * there are no quadrants left to divide.
 *
 * `why_us` items carry no icon in the CMS, so the four glyphs are matched by
 * position, in the frame's RTL reading order: top-start, top-end, bottom-start,
 * bottom-end.
 */
const props = defineProps<{ section: PageSectionData }>()

const GLYPHS = [outcomesUrl, brandUrl, endToEndUrl, qualityUrl]

/** Exact desktop positions inside the 680x400 artwork, in CMS/RTL reading order. */
const DESKTOP_POSITIONS = [
  'lg:left-[360px] lg:top-[71px] lg:w-[283px] lg:justify-between',
  'lg:left-[46px] lg:top-[81px] lg:w-[266px] lg:justify-between',
  'lg:left-[362px] lg:top-[244px] lg:w-[275px] lg:justify-between',
  'lg:left-[46px] lg:top-[282px] lg:w-[264px] lg:justify-between',
]

const claims = computed(() =>
  props.section.items.map((item, index) => ({ item, glyph: GLYPHS[index % GLYPHS.length] })),
)
</script>

<template>
  <section class="section">
    <div class="container-sizdah">
      <div class="grid gap-12 lg:grid-cols-[401px_680px] lg:justify-between lg:gap-0">
        <SectionHeading
          data-reveal
          :eyebrow="props.section.eyebrow"
          :title="props.section.title"
          :subtitle="props.section.subtitle || props.section.description"
          layout="stacked"
        />

        <div
          class="relative grid gap-x-10 gap-y-12 sm:grid-cols-2 lg:mt-[51px] lg:h-[400px] lg:w-[680px] lg:block"
          data-reveal-group
        >
          <img
            :src="ruleVerticalUrl"
            alt=""
            aria-hidden="true"
            width="4"
            height="400"
            class="pointer-events-none absolute top-0 hidden h-[400px] w-1 lg:left-[330px] lg:block"
          />
          <img
            :src="ruleTopUrl"
            alt=""
            aria-hidden="true"
            width="320"
            height="4"
            class="pointer-events-none absolute hidden h-1 w-[320px] lg:left-[344px] lg:top-[190px] lg:block"
          />
          <img
            :src="ruleBottomUrl"
            alt=""
            aria-hidden="true"
            width="320"
            height="4"
            class="pointer-events-none absolute hidden h-1 w-[320px] lg:left-[-26px] lg:top-[246px] lg:block"
          />

          <!--
            268:3717 — glyph at the inline start with the copy right-aligned
            against it, so DOM order is glyph first.
          -->
          <div
            v-for="({ item, glyph }, index) in claims"
            :key="item.id"
            class="relative flex items-start gap-4 lg:absolute lg:gap-0"
            :class="[index % 2 === 0 ? 'sm:pe-10 lg:pe-0' : 'sm:ps-0', DESKTOP_POSITIONS[index]]"
          >
            <img
              :src="glyph"
              alt=""
              aria-hidden="true"
              width="40"
              height="40"
              class="size-10 shrink-0"
            />

            <div class="flex w-[212px] flex-col gap-2 text-right">
              <h3 class="text-heading-sm text-ink-50">{{ item.title }}</h3>
              <p v-if="item.description" class="text-title-sm text-ink-300">
                {{ item.description }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

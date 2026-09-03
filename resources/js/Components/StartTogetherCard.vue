<script setup lang="ts">
import CtaButton from '@/Components/CtaButton.vue'
import type { PageSectionData } from '@/types'
import journeyUrl from '~img/sizdah/about/cta-journey.svg'

/**
 * The cream "شروع همکاری" card — Figma "Frame 96467", 1248x513.
 *
 * The file carries it as a duplicated frame rather than a component, but the
 * copies are byte-identical across every page that ends on it — about
 * (577:10889), projects (577:9134), case study (577:10538), Insights index
 * (577:9485), services (577:10187) and the article page (577:9836) all carry
 * the same five text runs, the same 592x417 vectorised illustration and the
 * same geometry, confirmed by screenshot. It is a component here so none of
 * them can drift apart.
 *
 * `FinalCtaCard` (the older dark Sahra card, 294:7672) is dead: that node no
 * longer exists in the file. Services and the article page used it until
 * 2026-08-24 on a stale assumption that their frames still drew it — see
 * GAPS G34.
 *
 * The card's grid wash is `.grid-mesh` with an ink hairline on cream. It is
 * cropped to the physical right side of the card and uses oversized cells,
 * leaving the copy side on a clean cream background.
 */
const props = defineProps<{ section: PageSectionData }>()
</script>

<template>
  <section class="relative overflow-hidden rounded-xl bg-brand-200 p-8 lg:p-12">
    <div
      class="grid-mesh pointer-events-none absolute inset-y-0 right-0 w-[58%]"
      style="--mesh-color: rgb(20 20 20 / 6%); --mesh-cell-x: 156px; --mesh-cell-y: 112px"
      aria-hidden="true"
    />

    <div
      class="relative z-10 flex flex-col items-center gap-10 lg:flex-row lg:justify-between lg:gap-6"
    >
      <div class="flex w-full flex-col items-start gap-12 text-start lg:max-w-[554px]">
        <div class="flex flex-col items-start gap-12">
          <div class="flex flex-col items-start gap-2">
            <!-- 577:11228 is set in Maneli, which is unlicensed here; it takes
                 the display stack at the same size. -->
            <p v-if="props.section.eyebrow" class="font-display text-title-md text-brand">
              {{ props.section.eyebrow }}
            </p>

            <div class="flex flex-col gap-6">
              <h2 class="text-section-line text-ink-1000">{{ props.section.title }}</h2>
              <p v-if="props.section.description" class="text-title-sm text-ink-800">
                {{ props.section.description }}
              </p>
            </div>
          </div>

          <CtaButton
            v-if="props.section.primaryCta"
            :label="props.section.primaryCta.label"
            :href="props.section.primaryCta.url"
            size="lg"
            with-arrow
          />
        </div>

        <!-- 577:11233 — the services line, same column the shared card uses. -->
        <p v-if="props.section.subtitle" class="text-body-lg text-ink-700">
          {{ props.section.subtitle }}
        </p>
      </div>

      <img
        :src="journeyUrl"
        alt=""
        aria-hidden="true"
        width="592"
        height="417"
        class="w-full max-w-[592px]"
      />
    </div>
  </section>
</template>

<script setup lang="ts">
import CtaButton from '@/Components/CtaButton.vue'
import type { PageSectionData } from '@/types'
import journeyUrl from '~img/sizdah/about/cta-journey.svg'

/**
 * The cream "شروع همکاری" card — Figma "Frame 96467", 1248x513.
 *
 * The file carries it as a duplicated frame rather than a component, but the
 * copies are byte-identical: 577:10889 on about (336:5623) and 577:9134 on
 * projects (222:1989) have the same five text runs, the same 592x417 vectorised
 * illustration and the same geometry. It is a component here so the two pages
 * cannot drift apart.
 *
 * Not to be confused with `FinalCtaCard` — that is the older dark card
 * (294:7672) still used by services and the article page. Both read the same
 * `final_cta` section, so a page picks whichever its frame draws.
 *
 * The card's grid wash is `.grid-mesh` with an ink hairline on cream; the
 * frame's own background image (imageRef d7b452aea69f, 1200x884) is nothing but
 * that grid, so it is not shipped.
 */
const props = defineProps<{ section: PageSectionData }>()
</script>

<template>
  <section
    class="grid-mesh rounded-xl bg-brand-200 p-8 lg:p-12"
    style="--mesh-color: rgb(20 20 20 / 6%); --mesh-cell-x: 113px; --mesh-cell-y: 64px"
  >
    <div class="flex flex-col items-center gap-10 lg:flex-row lg:justify-between lg:gap-6">
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
        class="w-full max-w-[592px] lg:order-first"
      />
    </div>
  </section>
</template>

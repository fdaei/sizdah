<script setup lang="ts">
import CtaButton from '@/Components/CtaButton.vue'
import type { PageSectionData } from '@/types'
import backgroundUrl from '~img/sizdah/shared/final-cta-bg.png'

/**
 * Final CTA card — Figma "final CTA card" (294:7672). 1248x483, on the article
 * and services frames.
 *
 * DEVIATION, deliberate: this component was never rebranded in Figma. It still
 * carries Sahra's English Poppins copy ("Share your goals with Sahra"), Sahra's
 * gold (#BD933B) on the eyebrow, and a CTA with white text on a white fill —
 * an invisible label. The layout, artwork and rhythm are reproduced faithfully;
 * the copy comes from the CMS section instead of being hardcoded, the eyebrow
 * takes the Sizdah brand colour, and the button gets an ink label so it can be
 * read. Recorded in FIGMA/state.json.
 *
 * `section.content` holds the service list printed under the button.
 */
const props = defineProps<{ section: PageSectionData }>()
</script>

<template>
  <section class="relative isolate overflow-hidden rounded-lg p-8 md:p-14">
    <img
      :src="backgroundUrl"
      alt=""
      aria-hidden="true"
      width="1672"
      height="941"
      class="absolute inset-0 -z-10 size-full object-cover"
    />

    <p v-if="props.section.eyebrow" class="text-heading-sm text-brand">
      {{ props.section.eyebrow }}
    </p>

    <div class="mt-2 flex max-w-[715px] flex-col gap-10">
      <div class="flex flex-col gap-12">
        <div class="flex flex-col gap-6">
          <h2 class="text-display-md text-white">{{ props.section.title }}</h2>
          <p v-if="props.section.description" class="text-title-sm text-ink-100">
            {{ props.section.description }}
          </p>
        </div>

        <CtaButton
          v-if="props.section.primaryCta"
          :label="props.section.primaryCta.label"
          :href="props.section.primaryCta.url"
          variant="light"
        />
      </div>

      <p v-if="props.section.content" class="text-title-sm text-ink-100">
        {{ props.section.content }}
      </p>
    </div>
  </section>
</template>

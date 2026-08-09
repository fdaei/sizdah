<script setup lang="ts">
import { computed } from 'vue'
import Eyebrow from '@/Components/Eyebrow.vue'
import FinalCtaCard from '@/Components/FinalCtaCard.vue'
import SeoHead from '@/Components/SeoHead.vue'
import type { PageSectionData, SectionMap, SeoMeta, ServiceItem } from '@/types'

/**
 * Services — Figma 308:4492.
 *
 * Four numbered blocks, each a 604x604 image alternating side to side against a
 * copy column: a display numeral, an underlined title, a paragraph, and the
 * service's feature bullets. Ends with the shared final CTA card.
 *
 * The numbering is positional (01…04) and generated here rather than stored, so
 * reordering services in Filament renumbers them automatically.
 *
 * DEVIATIONS: the frame sets the numerals in Maneli, which is not licensed for
 * this project — they fall back to the display stack at the same 64px. The
 * hand-drawn flourishes (322:5207 and the "Group 21" badge at 315:4998) are not
 * placed. Both recorded in FIGMA/state.json.
 */
const props = defineProps<{
  heading: { eyebrow: string; title: string; description: string }
  services: ServiceItem[]
  sections: SectionMap
  seo: SeoMeta
}>()

const finalCta = computed<PageSectionData | undefined>(() => props.sections.final_cta)
</script>

<template>
  <SeoHead :seo="props.seo" />

  <div class="section-first pb-24">
    <div class="container-sizdah flex flex-col gap-24">
      <header class="flex flex-col items-center gap-6 text-center">
        <Eyebrow v-if="props.heading.eyebrow" :text="props.heading.eyebrow" />
        <h1 class="max-w-measure text-display-lg text-ink-50">{{ props.heading.title }}</h1>
        <p v-if="props.heading.description" class="max-w-measure text-title-sm text-ink-200">
          {{ props.heading.description }}
        </p>
      </header>

      <section
        v-for="(service, index) in props.services"
        :key="service.slug"
        class="grid items-center gap-8 lg:grid-cols-2 lg:gap-16"
      >
        <!--
          The frame alternates which side the image sits on. `order` is keyed to
          the index so the pattern holds however many services are published.
        -->
        <div
          class="overflow-hidden rounded-lg"
          :class="index % 2 === 0 ? 'lg:order-first' : 'lg:order-last'"
        >
          <img
            v-if="service.image"
            :src="service.image.src"
            :srcset="service.image.srcset"
            :alt="service.image.alt"
            :width="service.image.width"
            :height="service.image.height"
            loading="lazy"
            class="aspect-square w-full object-cover"
          />
          <div v-else class="aspect-square w-full bg-ink-900" aria-hidden="true" />
        </div>

        <div class="flex flex-col gap-2">
          <p class="text-display-xl text-paper latin-nums" aria-hidden="true">
            {{ String(index + 1).padStart(2, '0') }}
          </p>

          <div class="flex flex-col gap-14">
            <div class="flex flex-col gap-10">
              <h2 class="w-fit border-b-2 border-brand pb-1 text-display-sm font-bold text-paper">
                {{ service.title }}
              </h2>
              <p class="text-title-md text-ink-200">{{ service.description }}</p>
            </div>

            <ul v-if="service.features.length" class="flex flex-col gap-4">
              <Eyebrow
                v-for="feature in service.features"
                :key="feature"
                as="li"
                size="lg"
                marker="dot"
                :text="feature"
              />
            </ul>
          </div>
        </div>
      </section>

      <FinalCtaCard v-if="finalCta" :section="finalCta" />
    </div>
  </div>
</template>

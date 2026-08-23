<script setup lang="ts">
import { computed } from 'vue'
import Eyebrow from '@/Components/Eyebrow.vue'
import FinalCtaCard from '@/Components/FinalCtaCard.vue'
import SeoHead from '@/Components/SeoHead.vue'
import type { PageSectionData, SectionMap, SeoMeta, ServiceItem } from '@/types'
import ringUrl from '~img/sizdah/services/ring.svg'
import sparkUrl from '~img/sizdah/services/spark.svg'

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
 * DEVIATIONS: the frame sets the numerals in Maneli (315:4854, 64px in a 145px
 * box), which is not licensed for this project — they take the `font-display`
 * stack at the same 64px. The 2.27 line box is a Maneli metric artefact rather
 * than a layout intent, so the numerals stay on leading-1. The
 * hand-drawn flourishes are placed: a brand-yellow ring behind each numeral
 * (511:9188/9193/9518, 607:5917 — one master asset instanced four times, not
 * four unique strokes: their path data is byte-identical, only the Figma
 * node id differs) and a small spark between blocks (511:9195/9200, 322:5242,
 * also one shared asset — same byte-identical situation). The "Group 21"
 * badge at 315:4998 is still not placed, and a fifth, ~20x25 mark (322:5230)
 * sits mostly above the frame's own top edge (y=-18.6 of 25 tall) so it is
 * treated as clipped/not meant to render, same reasoning as the footer's
 * invisible SAHRA wordmark (GAPS G24). Both recorded in FIGMA/state.json and
 * GAPS G29.
 *
 * The rings sit inside an oversized (505x145) invisible text frame in Figma —
 * a leading-2.27 Maneli metric artefact, not a layout intent (see the numeral
 * note above) — so their Figma coordinates describe a box far bigger than the
 * rendered glyph. Centring each ring behind its numeral reproduces the "circle
 * the number" effect without inheriting that artefact; see GAPS G29 for why an
 * exact coordinate copy was not attempted.
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
    <div class="container-sizdah relative isolate flex flex-col gap-24">
      <!-- 511:9147 — the shared 109px mesh, 872 tall, from frame y=202. -->
      <div
        class="grid-mesh pointer-events-none absolute inset-inline-start-0 inset-block-start-[22px] -z-10 hidden h-[872px] w-full max-w-container lg:block"
        style="--mesh-cell-x: 109.095px; --mesh-cell-y: 109.095px"
        aria-hidden="true"
      />

      <!--
        309:4754 — gap 40 to the title block (309:4758), which is gap 24. The
        lede is tite/Large (22/500), not the 18/400 this carried.
      -->
      <header class="flex flex-col items-center gap-10 text-center">
        <Eyebrow v-if="props.heading.eyebrow" :text="props.heading.eyebrow" />

        <div class="flex flex-col items-center gap-6">
          <h1 class="max-w-measure text-display-lg text-ink-50">{{ props.heading.title }}</h1>
          <p v-if="props.heading.description" class="max-w-measure text-title-lg text-ink-200">
            {{ props.heading.description }}
          </p>
        </div>
      </header>

      <template v-for="(service, index) in props.services" :key="service.slug">
        <section class="grid items-center gap-8 lg:grid-cols-2 lg:gap-16">
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
            <p class="relative w-fit">
              <img
                :src="ringUrl"
                alt=""
                aria-hidden="true"
                class="pointer-events-none absolute inset-inline-start-1/2 inset-block-start-1/2 -z-10 w-40 max-w-none -translate-x-1/2 -translate-y-1/2"
              />
              <span class="font-display text-display-xl text-paper latin-nums" aria-hidden="true">
                {{ String(index + 1).padStart(2, '0') }}
              </span>
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

        <!-- 511:9195/9200, 322:5242 — a small centred spark between blocks. -->
        <img
          v-if="index < props.services.length - 1"
          :src="sparkUrl"
          alt=""
          aria-hidden="true"
          class="mx-auto"
        />
      </template>

      <FinalCtaCard v-if="finalCta" :section="finalCta" />
    </div>
  </div>
</template>

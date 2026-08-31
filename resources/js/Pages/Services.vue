<script setup lang="ts">
import { computed } from 'vue'
import Eyebrow from '@/Components/Eyebrow.vue'
import SeoHead from '@/Components/SeoHead.vue'
import StartTogetherCard from '@/Components/StartTogetherCard.vue'
import type { PageSectionData, SectionMap, SeoMeta, ServiceItem } from '@/types'
import ringUrl from '~img/sizdah/services/ring.svg'
import sparkUrl from '~img/sizdah/services/spark.svg'
import underlineUrl from '~img/sizdah/shared/nav-underline.svg'

/**
 * Services — Figma 308:4492.
 *
 * Four numbered blocks, each a 604x604 image alternating side to side against a
 * 505.31 copy column: a display numeral, an underlined title, a paragraph, and
 * the service's feature bullets. Ends with the shared cream final CTA card
 * (`StartTogetherCard`, 577:10187 "Frame 96467") — this used the dark
 * `FinalCtaCard` until 2026-08-24, on the belief the frame still drew the old
 * Sahra card at 294:7672; that node no longer exists in the file, confirmed
 * by screenshot match against About's card. See GAPS G34.
 *
 * The numbering is positional (01…04) and generated here rather than stored, so
 * reordering services in Filament renumbers them automatically.
 *
 * GEOMETRY, measured off the frame 2026-08-27 (every value below is a real
 * bounding box, not an inference):
 *
 *   header block 309:4754  638 wide, centred, gap 40 → title stack (gap 24)
 *   header → blocks        140   (447 → 587)
 *   block ↔ block          200   (315:4945 children at y 0/804/1608/2412)
 *   row 315:4891           img 604 | gap 135 | copy 505.313, items-center,
 *                          justify-center (1244.313 in a 1248 track)
 *   copy column 315:4880   numeral box 145 → gap 8 → (title/desc → gap 56 →
 *                          features), title→desc 40, feature rows gap 16
 *   title frame 315:4857   text 46 tall, 2px rule flush beneath it
 *   blocks → CTA card      224   (3016 → 3240)
 *   CTA card → footer      224   (3752.86 → 3976.86)
 *
 * The numeral's 145px line box is NOT the metric artefact this file previously
 * assumed. It is load-bearing: the copy column is centred against the 604 image,
 * so collapsing the numeral to leading-1 lifts the whole title/desc/features
 * stack 40px against the photograph. `leading-[145px]` reproduces the frame
 * independently of which face renders the glyph, so the Maneli substitution
 * (below) does not leak into the layout.
 *
 * DEVIATIONS: the frame sets the numerals in Maneli (315:4854, 64px), which is
 * not licensed for this project — they take the `font-display` stack at the
 * same size. The hand-drawn flourishes are placed: a brand-yellow 163x109.12
 * ring behind each numeral (511:9188/9193/9518, 607:5917 — one master asset
 * instanced four times, not four unique strokes: their path data is
 * byte-identical, only the Figma node id differs) and a 20x25 spark 20px above
 * blocks 2-4 (511:9195/9200, 322:5242, also one shared asset — same
 * byte-identical situation). Both flourishes are page-level absolute
 * decorations in the frame, so they are positioned rather than laid out; the
 * ring is anchored off the copy column's start edge rather than centred on the
 * glyph, for the reason given at its call site. The "Group 21"
 * badge at 315:4998 is still not placed, and a fifth, ~20x25 mark (322:5230)
 * sits mostly above the frame's own top edge (y=-18.6 of 25 tall) so it is
 * treated as clipped/not meant to render, same reasoning as the footer's
 * invisible SAHRA wordmark (GAPS G24). Both recorded in FIGMA/state.json and
 * GAPS G29.
 *
 * The file ships desktop (1440) frames only, so every measured value above is
 * pinned from `lg` up and the smaller tiers keep the derived rhythm — see
 * .figma-sync/GAPS.md.
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

  <!--
    The frame puts the eyebrow's top edge at y=188, where `.section-first`
    clears the 108px header by 72 and lands at 180. The 8px is overridden here
    rather than in the shared utility because only this frame has been measured
    at this tier — see .figma-sync/GAPS.md G44.
  -->
  <div class="section-first pb-24 md:pt-[188px] lg:pb-[224px]">
    <div class="container-sizdah relative isolate">
      <!--
        511:9147 — the shared 109px mesh, 1200x872 from frame (120, 202), i.e.
        inset 24 from both edges of the 1248 content track rather than flush to
        it. `.container-sizdah` is the full 1440 box with the gutter as padding,
        so an absolute child resolves against the padding box: the inset is
        gutter + 24, and the max-width subtracts both gutters so narrower
        viewports cannot push the 1200 out past the frame.
      -->
      <div
        class="grid-mesh pointer-events-none absolute block-start-[14px] -z-10 hidden h-[872px] w-[1200px] max-w-[calc(100%-176px)] lg:inline-start-[88px] lg:block xl:inline-start-[120px] xl:max-w-[calc(100%-240px)]"
        style="--mesh-cell-x: 109.095px; --mesh-cell-y: 109.095px"
        aria-hidden="true"
      />

      <!--
        309:4754 — a 638-wide centred stack, gap 40 to the title block
        (309:4758), which is itself gap 24. The lede is tite/Large (22/500).
      -->
      <header class="mx-auto flex w-fit max-w-full flex-col items-center gap-10 text-center">
        <Eyebrow v-if="props.heading.eyebrow" :text="props.heading.eyebrow" />

        <div class="flex flex-col items-center gap-6">
          <!--
            309:4759 hugs at 613 inside a 638 block and breaks over two lines;
            capping the headline at the block's 638 reproduces that break. The
            lede below is one line in the frame, and 638 is nothing more than
            its Peyda hug — Vazirmatn needs 676 for the same run, so the block
            hugs the lede (`w-fit`) instead of pinning a width that would wrap
            it. See the DEVIATIONS note above.
          -->
          <h1 class="max-w-[638px] text-display-lg text-ink-50">{{ props.heading.title }}</h1>
          <p v-if="props.heading.description" class="text-title-lg text-ink-200">
            {{ props.heading.description }}
          </p>
        </div>
      </header>

      <div class="mt-16 flex flex-col gap-24 lg:mt-[140px] lg:gap-[200px]">
        <section
          v-for="(service, index) in props.services"
          :key="service.slug"
          class="relative flex flex-col items-center gap-8 lg:flex-row lg:justify-center lg:gap-[135px]"
        >
          <!--
            511:9195/9200, 322:5242 — a spark centred on the track, 20px above
            the top edge of every block after the first. Absolute, because the
            frame draws it over the block rather than between them: its box
            overlaps the block it sits on.
          -->
          <img
            v-if="index > 0"
            :src="sparkUrl"
            alt=""
            aria-hidden="true"
            class="pointer-events-none absolute -top-[20px] left-1/2 h-[25px] w-[20px] max-w-none -translate-x-1/2"
          />

          <!--
            The frame alternates which side the image sits on: left for blocks
            01/03, right for 02/04. Under RTL the first flex item lands at the
            right, so an even index has to be ordered *last* to reach the left.
            `order` is keyed to the index so the pattern holds however many
            services are published.
          -->
          <div
            class="w-full overflow-hidden rounded-sm lg:min-w-0 lg:basis-[604px]"
            :class="index % 2 === 0 ? 'lg:order-last' : 'lg:order-first'"
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

          <div class="flex w-full flex-col gap-2 lg:min-w-0 lg:basis-[505.313px]">
            <!--
              The ring overhangs the copy column's start edge by 40 in the frame
              (35.85/40.85/38.85/43.85 across the four blocks) and centres on the
              145 numeral box. It is anchored to that edge rather than centred on
              the glyph because Idealist sets "01" 43px narrower than Maneli
              does, and centring on the substitute would drag the ring ~20px
              off the frame's position.
            -->
            <p class="relative w-fit">
              <img
                :src="ringUrl"
                alt=""
                aria-hidden="true"
                class="pointer-events-none absolute inline-start-[-40px] top-1/2 -z-10 h-[109.12px] w-[163px] max-w-none -translate-y-1/2"
              />
              <span
                class="font-display text-display-xl leading-[145px] text-paper latin-nums"
                aria-hidden="true"
              >
                {{ String(index + 1).padStart(2, '0') }}
              </span>
            </p>

            <div class="flex flex-col gap-14">
              <div class="flex flex-col gap-10">
                <!--
                  315:4857 — the rule is the same hand-drawn stroke the header
                  puts under the active nav item (both are EL-0d4ea584), sitting
                  flush under a 46px text box, not a straight 2px border.
                -->
                <div class="w-fit">
                  <h2 class="text-display-sm font-bold text-paper">{{ service.title }}</h2>
                  <span class="relative block h-[2px] w-full">
                    <img
                      :src="underlineUrl"
                      alt=""
                      aria-hidden="true"
                      class="absolute inset-x-0 -top-[1.7px] h-[4.71px] w-full max-w-none"
                    />
                  </span>
                </div>

                <p class="text-title-md text-ink-200">{{ service.description }}</p>
              </div>

              <ul v-if="service.features.length" class="flex flex-col gap-4">
                <Eyebrow
                  v-for="feature in service.features"
                  :key="feature"
                  as="li"
                  size="lg"
                  :text="feature"
                />
              </ul>
            </div>
          </div>
        </section>
      </div>

      <StartTogetherCard v-if="finalCta" :section="finalCta" class="mt-24 lg:mt-[224px]" />
    </div>
  </div>
</template>

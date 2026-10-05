<script setup lang="ts">
import CtaButton from '@/Components/CtaButton.vue'
import type { PageSectionData } from '@/types'
import { sectionColor } from '@/lib/sectionColors'
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
 * Ground and mesh are measured off the designer's own 1x export of the frame
 * (2026-09-08), which supersedes the earlier eyeballed pass — see GAPS G57:
 *
 * - The ground is `surface-raised` (#FDFCFA), the raw fill tailwind.config.js
 *   already records against 553:7779, i.e. THIS card. It rendered as
 *   `brand-100` (#FFF8EB) until now, which is a visibly warmer yellow cream.
 * - The mesh sits on the **copy** side, not the illustration side: the frame's
 *   raster fill spans 42.53%..100% of the card width, and under the RTL frame
 *   that band is behind the text. Hence `inline-start-0` (physical right in
 *   fa), which mirrors correctly if an LTR locale ever comes back.
 * - Hairlines carry ~10% ink. The export's own lines are soft (the frame's
 *   fill is a resampled raster, so each hairline lands as #EEEEEB against a
 *   lighter neighbour); 10% is what reproduces their total weight with the
 *   crisp 1px gradient this utility draws. The old 2% was read off a
 *   contrast-boosted screenshot and is five times too faint.
 * - Cells are 113.4 x 115.3, and the tiling is anchored 37.5px in from the
 *   card's right edge and 75.5px down from its top, exactly where the export
 *   puts its outermost lines. Without that offset the default 0,0 origin runs
 *   a hairline down the band's inner edge and along the card's top edge,
 *   neither of which the frame draws.
 * - The frame draws no outline on this card; it carries `.sketch-frame` from
 *   2026-09-11 (GAPS G68), which sets the radius-24 clip the card used to get
 *   from `rounded-xl`.
 *
 * Mobile (below md) follows the file's own phone frame, 1072:5146 (362 wide):
 * padding 24, radius 16, a 32px gap above a 320-wide copy of the same
 * illustration, and smaller type — eyebrow Maneli 16, title Peyda Bold 26,
 * body Peyda Medium 16, services line Medium 12. Its button is the compact
 * CTA: space12 all round, a 14px Medium label and no arrow. The text gaps
 * (8 / 24 / 48 / 48) match the desktop card's. The phone frame draws no mesh;
 * the full-card mesh below md comes from a user-supplied export (GAPS G74).
 */
const props = defineProps<{ section: PageSectionData }>()
</script>

<template>
  <section
    class="sketch-frame overflow-hidden bg-surface-raised p-8 max-md:rounded-lg max-md:p-6 lg:p-12"
  >
    <div
      class="grid-mesh pointer-events-none absolute inset-y-0 inline-start-0 w-[57.47%] max-md:hidden"
      style="
        --mesh-color: rgb(20 20 20 / 10%);
        --mesh-cell-x: 113.4px;
        --mesh-cell-y: 115.3px;
        background-position: right 37.5px top 75.5px;
      "
      aria-hidden="true"
    />

    <!-- Mobile mesh (user-supplied export, 2026-10-05; GAPS G74): 11 x 11 cells
         across the card, interior lines only. Verticals ~12% ink peaking at
         mid-height and fading to nothing at both ends; horizontals a flat ~2.5%.
         The wrapper is a size container so the cell height can track the
         width at the export's 58.2 : 51.3 ratio. -->
    <div
      class="pointer-events-none absolute inset-0 [container-type:size] md:hidden"
      aria-hidden="true"
    >
      <div
        class="absolute inset-y-0 left-0 w-[calc(100%*10/11)]"
        style="
          background-image: linear-gradient(to left, rgb(20 20 20 / 12%) 1px, transparent 1px);
          background-size: 10% 100%;
          -webkit-mask-image: linear-gradient(to bottom, transparent, #000 50%, transparent);
          mask-image: linear-gradient(to bottom, transparent, #000 50%, transparent);
        "
      />
      <div
        class="absolute inset-0"
        style="
          background-image: linear-gradient(to top, rgb(20 20 20 / 2.5%) 1px, transparent 1px);
          background-size: 100% calc(100cqw / 11 * 0.881);
        "
      />
    </div>

    <div
      class="relative z-10 flex flex-col items-center gap-10 max-md:gap-8 lg:flex-row lg:justify-between lg:gap-6"
    >
      <div class="flex w-full flex-col items-start gap-12 text-start lg:max-w-[554px]">
        <div class="flex flex-col items-start gap-12">
          <div class="flex flex-col items-start gap-2">
            <!-- 577:11228 is set in Maneli, which is unlicensed here; it takes
                 the display stack at the same size. -->
            <p
              v-if="props.section.eyebrow"
              class="font-display text-title-md text-brand max-md:text-[16px] max-md:font-normal max-md:leading-normal"
              :style="sectionColor(props.section, 'eyebrow')"
            >
              {{ props.section.eyebrow }}
            </p>

            <div class="flex flex-col gap-6">
              <h2
                class="text-section-line text-ink-1000 max-md:text-[26px] max-md:font-bold max-md:leading-normal"
                :style="sectionColor(props.section, 'title')"
              >
                {{ props.section.title }}
              </h2>
              <p
                v-if="props.section.description"
                class="text-title-sm text-ink-800 max-md:text-[16px] max-md:font-medium max-md:leading-normal"
                :style="sectionColor(props.section, 'description')"
              >
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
            class="max-md:gap-1 max-md:p-3 max-md:text-label-lg max-md:[&>img]:hidden"
          />
        </div>

        <!-- 577:11233 — the services line, same column the shared card uses. -->
        <p
          v-if="props.section.subtitle"
          class="text-body-lg text-ink-700 max-md:text-label-md max-md:leading-normal"
          :style="sectionColor(props.section, 'subtitle')"
        >
          {{ props.section.subtitle }}
        </p>
      </div>

      <img
        :src="journeyUrl"
        alt=""
        aria-hidden="true"
        width="592"
        height="417"
        class="w-full max-w-[592px] max-md:max-w-[320px]"
      />
    </div>
  </section>
</template>

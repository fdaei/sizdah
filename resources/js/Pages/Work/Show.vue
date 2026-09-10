<script setup lang="ts">
import { computed, ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import FilterChips from '@/Components/FilterChips.vue'
import SectionHeading from '@/Components/SectionHeading.vue'
import SeoHead from '@/Components/SeoHead.vue'
import StartTogetherCard from '@/Components/StartTogetherCard.vue'
import { useTranslations } from '@/Composables/useTranslations'
import type {
  CardItem,
  FilterOption,
  PageSectionData,
  ProjectDetail,
  ResultStat,
  SeoMeta,
} from '@/types'
import servicesIconUrl from '~img/sizdah/work/meta-services.svg'
import instagramIconUrl from '~img/sizdah/work/meta-instagram.svg'
import yearIconUrl from '~img/sizdah/work/meta-year.svg'
import industryIconUrl from '~img/sizdah/work/meta-industry.svg'
import challengeMarkUrl from '~img/sizdah/work/challenge-mark.svg'
import nextArrowUrl from '~img/sizdah/work/next-arrow.svg'
import resultRoiUrl from '~img/sizdah/work/result-roi.svg'
import resultReachUrl from '~img/sizdah/work/result-reach.svg'
import resultInteractionUrl from '~img/sizdah/work/result-interaction.svg'
import resultFollowerUrl from '~img/sizdah/work/result-follower.svg'
import resultViewUrl from '~img/sizdah/work/result-view.svg'
import strategyRuleUrl from '~img/sizdah/work/strategy-rule-h.svg'
import strategyRuleVerticalUrl from '~img/sizdah/work/strategy-rule-v.svg'

/**
 * Case study — Figma "case study" 336:5374 (1440x7403).
 *
 * The frame is a free-positioned one-off drawn around a single project
 * (استخر چشمه), so this is a faithful reading of its *system* — type ramp, card
 * treatments, section order, quadrant geometry — rather than a transcription of
 * absolute coordinates, which would not survive a project with a different
 * number of goals or results.
 *
 * RTL NOTE, and it is the thing that bit this page hardest (2026-09-06). Figma
 * reports auto-layout in LTR coordinates even for an RTL design, so a frame
 * whose copy visually hugs the right edge reads as `alignItems: flex-end` in
 * the API. In CSS-logical terms that is `start`, not `end`. Every
 * `items-end` / `text-end` on this page was therefore mirrored — goal cards,
 * the meta chips, the next-project block — and the fix is `items-start` (or
 * simply letting `dir="rtl"` do it). Do not "correct" these back by matching
 * Figma's property name to the Tailwind class of the same name.
 *
 * Three distinct card treatments, deliberately kept apart:
 *   - goals (423:4973) — `.surface-case-card` under the shared `.sketch-frame`
 *     rule, four across; brand number at 36/600 above a 24-gap title/body pair.
 *     The frame measures it at radius 16 with a 3px ink-200 border; the site-
 *     wide sketched frame overrides both (see the note on `cardBlocks`).
 *   - deliverables (429:5099) — the same card, three across in two rows. The frame's own heading on this block reads "اهداف پروژه", a
 *     copy-paste of the goals heading; `work.deliverables` is used instead.
 *   - strategy (428:5041-428:5088) — no fill or border. A three-column
 *     quadrant: the heading block occupies the first (right-hand) column and
 *     spans both rows, the four numbered cells fill the other two, staggered
 *     rather than gridded, and three brush strokes divide them. The strokes
 *     are real geometry, not rules — a tapered, uneven hand-drawn path — so
 *     they ship as SVG (`strategy-rule-v`, `strategy-rule-h`) rather than as
 *     the 3px borders this block used to draw. Vectors 6 and 7 are the same
 *     path, so one file serves both columns.
 *
 * DEVIATIONS:
 *   - 428:5044 puts an eyebrow ("چرا سیزده") and a package subtitle ("سه پکیج…")
 *     in the quadrant's heading cell — Services-page pricing copy left in the
 *     file, which is why an earlier pass reproduced the cell's geometry but
 *     not its wording. The user chose the frame verbatim on 2026-09-08, so
 *     `work.strategy_*` now carries that copy and `work.strategy` is gone.
 *     See GAPS G19 and G61.
 *   - 428:5047 rims the eyebrow marker in Black/800 (#434343) where every
 *     other dark-ground instance rims in Yellow/50. `Eyebrow` keeps its
 *     documented dark-ground default rather than forking for an invisible
 *     1px; see GAPS G45 and G61.
 *   - 430:5211 repeats the "نتایج" heading between the before/after pair and
 *     the next-project block, after the results section has already run. It is
 *     a leftover duplicate and is not rendered.
 *   - The frame's result labels are Latin (ROI, Reach, …) on Latin numerals;
 *     the seeded fa content is Persian on Persian numerals. Content wins — the
 *     tile geometry, glyph, colour and type are the frame's.
 */
const props = defineProps<{
  project: ProjectDetail
  finalCta: PageSectionData | null
  seo: SeoMeta
}>()

const { t } = useTranslations()

/*
 | 411:8568, read right-to-left: industry, year, instagram, services. The frame
 | lists them in the opposite order because Figma reports the row LTR.
 */
const meta = [
  { key: 'industry', icon: industryIconUrl, value: props.project.industry },
  { key: 'year', icon: yearIconUrl, value: props.project.year },
  {
    key: 'instagram',
    icon: instagramIconUrl,
    value: props.project.instagram,
    // 411:8619 sets the handle in `ts1` — an underline text style.
    underline: true,
  },
  { key: 'services', icon: servicesIconUrl, value: props.project.services.join('، ') },
].filter((item) => item.value)

/*
 | Both blocks use `.surface-case-card` and `.sketch-frame`; only the column
 | count differs, and it is the frame's (423:4972 is four across, 429:5092 and
 | 429:5117 are three across).
 */
const cardBlocks: {
  key: string
  items: CardItem[]
  /*
   | The sketched card frame (.sketch-frame) is a fixed radius-24 drawing and
   | sets the radius itself, so both blocks now wear it. The goals row was
   | measured at r16 with a 3px ink-200 rule; it is deliberately overridden to
   | the shared frame (user decision 2026-09-11 — every card surface on the site
   | wears the sketched rule) and rounds up to 24 so the artwork's corners
   | cannot out-curve the card's own clip.
   */
  columns: string
  /** Step above the block; see the rhythm table on the track. */
  lead: string
}[] = [
  {
    key: 'goals',
    items: props.project.goals,
    // Single row, so the 16 gutter is the only gap the frame draws.
    columns: 'gap-4 sm:grid-cols-2 lg:grid-cols-4',
    lead: 'mt-20 md:mt-[136px]',
  },
  {
    key: 'deliverables',
    items: props.project.deliverables,
    // 429:5092 -> 429:5117 is 40 between the rows against 16 between columns.
    columns: 'gap-x-4 gap-y-10 sm:grid-cols-2 lg:grid-cols-3',
    lead: 'mt-20 md:mt-[233px]',
  },
].filter((block) => block.items.length > 0)

/** Tile glyphs 615:6045…615:6160, keyed by `ResultStat.icon`. */
const resultIcons: Record<string, { src: string; width: number; height: number }> = {
  roi: { src: resultRoiUrl, width: 32, height: 32 },
  reach: { src: resultReachUrl, width: 32, height: 22 },
  interaction: { src: resultInteractionUrl, width: 24, height: 24 },
  follower: { src: resultFollowerUrl, width: 32, height: 20 },
  view: { src: resultViewUrl, width: 32, height: 19 },
}

// `icon` is persisted as a stable English key, but older rows may have been
// seeded before the icon backfill ran. Resolve the visible label as a safety
// net so a tile can never receive the wrong glyph (or lose it) because the
// database contains a localized title.
const resultIconAliases: Record<string, keyof typeof resultIcons> = {
  roi: 'roi',
  'بازگشت سرمایه': 'roi',
  reach: 'reach',
  دسترسی: 'reach',
  interaction: 'interaction',
  تعامل: 'interaction',
  follower: 'follower',
  دنبال‌کننده: 'follower',
  view: 'view',
  بازدید: 'view',
}

function resultIcon(result: ResultStat): (typeof resultIcons)[string] | undefined {
  const key =
    resultIconAliases[result.icon?.trim().toLowerCase() ?? ''] ??
    resultIconAliases[result.label.trim().toLowerCase()]

  return key ? resultIcons[key] : undefined
}

/*
 | 430:5201 — the chip row beside the showcase heading. It filters the gallery
 | that is already on the page, so the chips are buttons rather than links and
 | nothing is re-fetched. The row is only drawn once at least one image carries
 | a tag, so an untagged project gets the plain gallery instead of a control
 | that cannot do anything.
 */
const activeTag = ref<string | null>(null)

const showcaseFilters = computed<FilterOption[]>(() => {
  const tags = [...new Set(props.project.showcase.map((image) => image.tag).filter(Boolean))]

  if (tags.length === 0) {
    return []
  }

  return [
    { value: null, label: t('common.all') },
    ...tags.map((tag) => ({ value: tag as string, label: tag as string })),
  ]
})

const visibleShowcase = computed(() =>
  activeTag.value === null
    ? props.project.showcase
    : props.project.showcase.filter((image) => image.tag === activeTag.value),
)

/** 01, 02, … — the frame pads to two digits. */
function ordinal(index: number): string {
  return String(index + 1).padStart(2, '0')
}

/*
 | The quadrant's four cells number 01-04 down the page but do NOT stack in
 | that order: 01 and 03 sit in the inner column (428:5058, 428:5087) and 02
 | and 04 in the outer one (428:5067, 428:5088), so the pairs interleave. Split
 | the list on parity and keep each cell's original index for its numeral.
 */
const strategyColumns = computed<{ item: CardItem; index: number }[][]>(() => {
  const cells = props.project.strategy.map((item, index) => ({ item, index }))

  return [
    cells.filter((cell) => cell.index % 2 === 0),
    cells.filter((cell) => cell.index % 2 === 1),
  ]
})

// When a project has no deliverables block, strategy takes its place in the
// section sequence and therefore uses the frame's goals-to-next-block rhythm.
const strategyLead = computed(() =>
  props.project.deliverables.length > 0 ? 'mt-20 md:mt-[121px]' : 'mt-20 md:mt-[233px]',
)
</script>

<template>
  <SeoHead :seo="props.seo" />

  <article class="section-first pb-[442.38px] md:pt-[188px]">
    <!--
      Vertical rhythm. 336:5374 is free-positioned, so the step between blocks
      is NOT uniform — it is measured per boundary off the frame and carried on
      each section as `md:mt-[…]` rather than as one `gap` on the track:

        header -> hero        153   (438 -> 591)
        hero -> challenge     163   (1215 -> 1378)
        challenge -> goals    136   (1525 -> 1661)
        goals -> next block   233   (2047 -> 2280)
        that -> the one after 121   (2720 -> 2841)
        -> showcase           167   (3562 -> 3729)
        showcase -> results   189   (4337 -> 4526)
        results -> before/after 127 (4893 -> 5020)
        -> next project       270   (5391 -> 5661)
        next project -> CTA   172   (5774 -> 5946)

      The frame runs goals, strategy, deliverables; this page keeps the same
      blocks when content exists (the Cheshmeh case study replaces
      deliverables with its «چرا سیزده» strategy quadrant), so the 233 and 121
      steps sit either side of the middle block when it is present.
      Below `md` the whole ramp collapses to a flat 80.
    -->
    <div class="container-sizdah relative isolate flex flex-col">
      <!--
        511:9519 — a 109px hairline mesh, 1200x872, inset 24 from the content
        track (frame x=120 against a 96 gutter) and starting 22px below the
        content top.
      -->
      <div
        class="grid-mesh pointer-events-none absolute inline-start-6 block-start-[22px] -z-10 hidden h-[872px] w-[1200px] max-w-[calc(100%-theme(spacing.6))] lg:block"
        style="--mesh-cell-x: 109.095px; --mesh-cell-y: 109.095px"
        aria-hidden="true"
      />

      <!--
        411:8560 — a centred 612 column (411:8561) inside a 670 track, gap 64
        down to the meta row and gap 24 inside the title block (411:8565). Both
        text runs are CENTER in the frame, and it carries no eyebrow, so this
        header is centred and the kicker is not drawn. Only the copy is capped
        at 612: the meta row is its own 740-wide frame in the file and would
        wrap early if it inherited the track.
      -->
      <header class="flex flex-col items-center gap-16">
        <div class="flex w-full max-w-measure flex-col items-center gap-6 text-center">
          <h1 class="text-display-md text-ink-50">{{ props.project.title }}</h1>
          <p v-if="props.project.excerpt" class="text-title-sm text-ink-200">
            {{ props.project.excerpt }}
          </p>
        </div>

        <!--
          Meta row 411:8568. The 24px glyph leads and the label/value stack
          follows it, so in RTL the glyph sits at the inline START (visually
          right) and the text runs after it — user decision 2026-09-11, taken
          against the earlier reading of 411:8569 (see .figma-sync/GAPS.md).
        -->
        <dl v-if="meta.length" class="flex flex-wrap justify-center gap-4">
          <div
            v-for="item in meta"
            :key="item.key"
            class="surface-meta-chip flex items-start gap-2 rounded-lg border-2 border-brand-300 px-6 py-3"
          >
            <img
              :src="item.icon"
              alt=""
              aria-hidden="true"
              width="24"
              height="24"
              class="size-6 shrink-0"
            />
            <div class="flex flex-col justify-center gap-2 whitespace-nowrap">
              <dt class="text-label-lg text-ink-50">{{ t(`work.${item.key}`) }}</dt>
              <dd
                class="latin-nums text-body-md text-ink-200"
                :class="item.underline && 'underline'"
              >
                {{ item.value }}
              </dd>
            </div>
          </div>
        </dl>
      </header>

      <!-- 423:4964 — 1248x624, i.e. a flat 2:1, at radius 24 under a 5% cast. -->
      <figure
        v-if="props.project.banner ?? props.project.image"
        class="mt-20 overflow-hidden rounded-xl md:mt-[153px]"
      >
        <img
          :src="(props.project.banner ?? props.project.image)!.src"
          :srcset="(props.project.banner ?? props.project.image)!.srcset"
          :alt="(props.project.banner ?? props.project.image)!.alt"
          :width="(props.project.banner ?? props.project.image)!.width"
          :height="(props.project.banner ?? props.project.image)!.height"
          class="aspect-[2/1] w-full object-cover shadow-[3.32px_3.32px_9.96px_0_rgb(0_0_0/5%)]"
        />
      </figure>

      <!-- Challenge 423:4971 — the 144x144 mark (611:5944) sits off the heading's end. -->
      <section
        v-if="props.project.challenge"
        class="mt-20 flex flex-col items-center gap-10 md:mt-[163px]"
      >
        <h2 class="relative mx-auto w-fit text-center text-section-line text-brand-50">
          {{ t('work.challenge') }}
          <img
            :src="challengeMarkUrl"
            alt=""
            aria-hidden="true"
            width="144"
            height="144"
            class="pointer-events-none absolute inline-start-full top-1/2 ms-3 hidden size-36 -translate-y-[calc(50%+29px)] lg:block"
          />
        </h2>

        <p class="w-full max-w-[832px] text-center text-title-lg text-ink-200">
          {{ props.project.challenge }}
        </p>

        <ul
          v-if="props.project.challengePoints.length"
          class="flex w-full max-w-[832px] flex-col gap-3"
        >
          <li
            v-for="point in props.project.challengePoints"
            :key="point"
            class="flex items-start gap-3 text-title-sm text-ink-200"
          >
            <span class="mt-2 size-2 shrink-0 rounded-round bg-brand" aria-hidden="true" />
            <span>{{ point }}</span>
          </li>
        </ul>
      </section>

      <!-- Goals 428:5089 / deliverables 429:5090 — gap 40 over a gap-16 card row. -->
      <section
        v-for="block in cardBlocks"
        :key="block.key"
        class="flex flex-col gap-10"
        :class="block.lead"
      >
        <h2 class="text-section-line text-brand-50">{{ t(`work.${block.key}`) }}</h2>

        <ul class="grid" :class="block.columns" data-reveal-group>
          <li
            v-for="(item, index) in block.items"
            :key="item.title"
            class="surface-case-card sketch-frame flex h-full flex-col items-start justify-center gap-10 px-6 py-14"
            data-reveal
          >
            <span class="latin-nums text-display-sm font-semibold text-brand" aria-hidden="true">
              {{ ordinal(index) }}
            </span>
            <div class="flex w-full flex-col gap-6">
              <h3 class="text-heading-sm text-brand-50">{{ item.title }}</h3>
              <p class="text-title-sm text-ink-200">{{ item.description }}</p>
            </div>
          </li>
        </ul>
      </section>

      <!--
        Strategy quadrant 428:5041-428:5088. Free-positioned in the frame: the
        heading block 428:5044 holds the first (right-hand) column and the four
        numbered cells scatter down the two columns beside it, staggered rather
        than gridded and divided by hand-drawn brush strokes.

        The stagger is the frame's, measured off the cell tops: the cells start
        59px below the heading (2280 -> 2339) and the outer column runs a
        further 38px lower than the inner one (428:5058 at 2339 against
        428:5067 at 2377; 428:5087 at 2574 against 428:5088 at 2594). Below
        `lg` the heading returns to a header above the cells, and below `sm`
        the two columns collapse and the vertical stroke drops out.

        Column track, scaled from the frame's 1284 span onto the 1248 content
        track: heading 401, a 204 gutter, then the two ~327 cell columns 24
        apart. An even three-up reads wrong — it closes the gutter the frame
        opens between the heading and cell 01.

        The heading column is a percentage so it tracks the container's four
        `px-*` steps, but the gutter is only the frame's 198 at `2xl`, the one
        width the file was drawn at (1440). Held in px below that it eats the
        whole 896 track at `lg` and squeezes the cells to three words a line.
      -->
      <section
        v-if="props.project.strategy.length"
        class="grid lg:grid-cols-[minmax(0,31%)_minmax(0,1fr)] lg:gap-x-10 2xl:gap-x-[198px]"
        :class="strategyLead"
      >
        <!--
          428:5044 — eyebrow 428:5046 (45px box) over a 40px Bold headline and
          a 20px Medium subtitle, the same three parts as every Home section
          header, so this is `SectionHeading` stacked rather than a second copy.
          Its copy is the frame's own, Services-page wording and all (G61).
        -->
        <SectionHeading
          :eyebrow="t('work.strategy_eyebrow')"
          :title="t('work.strategy_title')"
          :subtitle="t('work.strategy_subtitle')"
          layout="stacked"
        />

        <div class="relative mt-12 grid gap-x-6 gap-y-[52px] sm:grid-cols-2 lg:mt-[59px]">
          <!--
            428:5041 — a 4x400 stroke centred in the gutter between the two
            columns, drawn the full height of the block rather than per row.
            `inline-start` keeps it in the gutter in either direction.
          -->
          <img
            v-if="strategyColumns[1].length"
            :src="strategyRuleVerticalUrl"
            alt=""
            aria-hidden="true"
            width="4"
            height="400"
            class="pointer-events-none absolute inset-y-0 hidden h-full w-1 max-w-none sm:block"
            style="inset-inline-start: calc(50% - 2px)"
          />

          <!--
            `contents` below `sm`: with one column there is no quadrant to
            divide, so the wrappers step out of the box tree, the strokes drop
            out with them, and `order` puts the cells back in 01-04 reading
            order — otherwise the interleave would stack them 01, 03, 02, 04.
          -->
          <div
            v-for="(column, columnIndex) in strategyColumns"
            :key="columnIndex"
            class="contents sm:flex sm:flex-col sm:gap-[52px]"
            :class="columnIndex === 1 && 'sm:mt-[38px]'"
          >
            <template v-for="cell in column" :key="cell.item.title">
              <!--
                428:5042 / 428:5043 — the same 320x4 stroke, one per column,
                sitting in the gap between that column's two cells. It orders
                one below the cell it follows so the `order` above cannot sort
                it to the head of the column.
              -->
              <img
                v-if="cell.index > 1"
                :src="strategyRuleUrl"
                alt=""
                aria-hidden="true"
                width="320"
                height="4"
                class="hidden h-1 w-full max-w-[320px] sm:block"
                :style="{ order: cell.index - 1 }"
              />

              <div class="flex items-start gap-4" :style="{ order: cell.index }" data-reveal>
                <span class="latin-nums text-heading-xl text-brand" aria-hidden="true">
                  {{ ordinal(cell.index) }}
                </span>
                <!-- 428:5058 — a 252 measure, which is what sets the 4-line wrap. -->
                <div class="flex max-w-[252px] flex-col gap-2">
                  <h3 class="text-heading-sm text-ink-50">{{ cell.item.title }}</h3>
                  <p class="text-title-sm text-ink-200">{{ cell.item.description }}</p>
                </div>
              </div>
            </template>
          </div>
        </div>
      </section>

      <!-- Content showcase 430:5201 (heading + chips) over 430:5137 (the grid). -->
      <section
        v-if="props.project.showcase.length"
        class="mt-20 flex flex-col gap-[57px] md:mt-[167px]"
      >
        <div class="flex flex-wrap items-center justify-between gap-6">
          <h2 class="text-section-line text-brand-50">{{ t('work.showcase') }}</h2>

          <FilterChips
            v-if="showcaseFilters.length"
            :options="showcaseFilters"
            :active="activeTag"
            :label="t('work.showcase')"
            @select="activeTag = $event"
          />
        </div>

        <ul class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3" data-reveal-group>
          <li v-for="image in visibleShowcase" :key="image.src" data-reveal>
            <img
              :src="image.src"
              :srcset="image.srcset"
              :alt="image.alt"
              :width="image.width"
              :height="image.height"
              loading="lazy"
              class="aspect-[4/5] w-full rounded-xl object-cover"
            />
          </li>
        </ul>
      </section>

      <!--
        Results 430:5141 — gap 48 throughout: heading to tiles, tiles to the
        summary line. Each tile (615:6045…) is px-32/py-16 on gold-100 at
        radius 8, glyph over label over value.
      -->
      <section
        v-if="props.project.results.length || props.project.resultsSummary"
        class="mt-20 flex flex-col gap-12 md:mt-[189px]"
      >
        <div class="flex flex-col gap-12">
          <h2 class="text-section-line text-brand-50">{{ t('work.results') }}</h2>

          <ul
            v-if="props.project.results.length"
            class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5"
            data-reveal-group
          >
            <li
              v-for="result in props.project.results"
              :key="result.label"
              class="flex h-full flex-col items-start justify-center gap-4 rounded-sm bg-gold-100 px-8 py-4"
              data-reveal
            >
              <img
                v-if="resultIcon(result)"
                :src="resultIcon(result)!.src"
                :width="resultIcon(result)!.width"
                :height="resultIcon(result)!.height"
                alt=""
                aria-hidden="true"
                class="block h-auto"
              />
              <div class="flex flex-col gap-2">
                <span class="text-title-lg text-warm-900">{{ result.label }}</span>
                <!-- dir=ltr keeps a leading "+" leading; bidi would flip it to the end. -->
                <span dir="ltr" class="latin-nums block text-heading-lg text-brand">
                  {{ result.value }}
                </span>
              </div>
            </li>
          </ul>
        </div>

        <p
          v-if="props.project.resultsSummary"
          class="whitespace-pre-line text-heading-sm text-ink-200"
        >
          {{ props.project.resultsSummary }}
        </p>
      </section>

      <!-- 430:5204 — قبل leads (right), بعد follows; captions centred over a 2:1 plate. -->
      <section
        v-if="props.project.beforeAfter.before && props.project.beforeAfter.after"
        class="mt-20 grid gap-6 md:mt-[127px] md:grid-cols-2"
      >
        <figure
          v-for="side in ['before', 'after'] as const"
          :key="side"
          class="flex flex-col gap-6"
        >
          <figcaption class="text-center text-heading-xl font-bold text-ink-50">
            {{ t(`work.${side}`) }}
          </figcaption>
          <img
            :src="props.project.beforeAfter[side]!.src"
            :srcset="props.project.beforeAfter[side]!.srcset"
            :alt="props.project.beforeAfter[side]!.alt"
            loading="lazy"
            class="aspect-[2/1] w-full rounded-sm object-cover shadow-[0_4px_10px_0_rgb(0_0_0/5%)]"
          />
        </figure>
      </section>

      <!-- Next project 430:5212 — label, then title with the arrow at its end. -->
      <section
        v-if="props.project.next"
        class="mt-20 flex flex-col items-start gap-8 md:mt-[270px]"
      >
        <p class="text-heading-sm text-ink-200">{{ t('work.next_case_study') }}</p>
        <Link
          :href="props.project.next.url"
          class="group flex items-center gap-4 transition-colors duration-200 ease-brand"
        >
          <span
            class="text-display-md text-ink-50 transition-colors duration-200 ease-brand group-hover:text-brand"
          >
            {{ props.project.next.title }}
          </span>
          <img
            :src="nextArrowUrl"
            alt=""
            aria-hidden="true"
            width="40"
            height="40"
            class="size-10 transition-transform duration-200 ease-brand group-hover:-translate-x-2"
          />
        </Link>
      </section>

      <StartTogetherCard
        v-if="props.finalCta"
        :section="props.finalCta"
        class="mt-20 md:mt-[172px]"
      />
    </div>
  </article>
</template>

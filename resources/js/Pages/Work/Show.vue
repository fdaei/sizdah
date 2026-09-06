<script setup lang="ts">
import { computed, ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import FilterChips from '@/Components/FilterChips.vue'
import SeoHead from '@/Components/SeoHead.vue'
import StartTogetherCard from '@/Components/StartTogetherCard.vue'
import { useTranslations } from '@/Composables/useTranslations'
import type {
  CardItem,
  FilterOption,
  PageSectionData,
  ProjectDetail,
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
 *   - goals (423:4973) — `.surface-case-card`, 3px ink-200 border, radius 16,
 *     four across; brand number at 36/600 above a 24-gap title/body pair.
 *   - deliverables (429:5099) — the same card at radius 24, three across in
 *     two rows. The frame's own heading on this block reads "اهداف پروژه", a
 *     copy-paste of the goals heading; `work.deliverables` is used instead.
 *   - strategy (428:5041-428:5088) — no fill or border. A three-column
 *     quadrant: the section heading occupies the first (right-hand) column and
 *     spans both rows, the four numbered cells fill the other two, and 3px
 *     brand-200 rules divide them.
 *
 * DEVIATIONS:
 *   - 428:5044 puts an eyebrow ("چرا سیزده") and a package subtitle ("سه پکیج…")
 *     in the quadrant's heading cell. Both are Services-page copy left in the
 *     file; the cell's geometry is reproduced, its copy is not. See GAPS G19.
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
 | Both blocks use `.surface-case-card`; only the radius and the column count
 | differ, and both are the frame's (423:4972 is four across at r16, 429:5092
 | and 429:5117 are three across at r24).
 */
const cardBlocks: { key: string; items: CardItem[]; radius: string; columns: string }[] = [
  {
    key: 'goals',
    items: props.project.goals,
    radius: 'rounded-lg',
    columns: 'sm:grid-cols-2 lg:grid-cols-4',
  },
  {
    key: 'deliverables',
    items: props.project.deliverables,
    radius: 'rounded-xl',
    columns: 'sm:grid-cols-2 lg:grid-cols-3',
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
 | Quadrant rules 428:5041-428:5043. The heading holds the first column and
 | both rows, so the cells flow two-per-row into the remaining columns: an odd
 | cell is the one in the far column and carries the vertical rule on its
 | inline start; anything on the second row carries the horizontal one. At the
 | one-column breakpoint only the horizontal rule survives.
 */
function cellRules(index: number): string[] {
  return [
    index > 0 ? 'border-t-[3px] border-brand-200' : '',
    index === 1 ? 'sm:border-t-0' : '',
    index % 2 === 1 ? 'sm:border-s-[3px] sm:border-brand-200' : '',
  ].filter(Boolean)
}
</script>

<template>
  <SeoHead :seo="props.seo" />

  <article class="section-first pb-24">
    <div class="container-sizdah relative isolate flex flex-col gap-20 md:gap-[120px]">
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

        <!-- Meta row 411:8568 — icon at the inline start, label over value. -->
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
            <div class="flex flex-col gap-2 whitespace-nowrap">
              <dt class="text-label-lg text-ink-50">{{ t(`work.${item.key}`) }}</dt>
              <dd class="latin-nums text-body-md text-ink-200" :class="item.underline && 'underline'">
                {{ item.value }}
              </dd>
            </div>
          </div>
        </dl>
      </header>

      <!-- 423:4964 — 1248x624, i.e. a flat 2:1, at radius 24 under a 5% cast. -->
      <figure v-if="props.project.banner ?? props.project.image" class="overflow-hidden rounded-xl">
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
      <section v-if="props.project.challenge" class="flex flex-col items-center gap-10">
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
      <section v-for="block in cardBlocks" :key="block.key" class="flex flex-col gap-10">
        <h2 class="text-section-line text-brand-50">{{ t(`work.${block.key}`) }}</h2>

        <ul class="grid gap-4" :class="block.columns" data-reveal-group>
          <li
            v-for="(item, index) in block.items"
            :key="item.title"
            class="surface-case-card flex h-full flex-col items-start justify-center gap-10 border-3 border-ink-200 px-6 py-14"
            :class="block.radius"
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
        Strategy quadrant 428:5041-428:5088. The heading is a cell of the grid,
        not a header above it: it holds the first column across both rows while
        the numbered cells fill the other two, divided by 3px brand-200 rules.
      -->
      <section v-if="props.project.strategy.length" class="grid sm:grid-cols-2 lg:grid-cols-3">
        <!--
          428:5044's cell. The frame lifts the heading clear of the ruled area
          rather than padding it in line with the cells, so the top padding is
          dropped once the quadrant actually forms.
        -->
        <h2 class="p-8 text-section-line text-brand-50 sm:col-span-2 lg:col-span-1 lg:row-span-2 lg:pt-0">
          {{ t('work.strategy') }}
        </h2>

        <div
          v-for="(item, index) in props.project.strategy"
          :key="item.title"
          class="flex items-start gap-4 p-8"
          :class="cellRules(index)"
          data-reveal
        >
          <span class="latin-nums text-heading-xl text-brand" aria-hidden="true">
            {{ ordinal(index) }}
          </span>
          <div class="flex flex-col gap-2">
            <h3 class="text-heading-sm text-ink-50">{{ item.title }}</h3>
            <p class="text-title-sm text-ink-200">{{ item.description }}</p>
          </div>
        </div>
      </section>

      <!-- Content showcase 430:5201 (heading + chips) over 430:5137 (the grid). -->
      <section v-if="props.project.showcase.length" class="flex flex-col gap-12">
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
        class="flex flex-col gap-12"
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
                v-if="result.icon && resultIcons[result.icon]"
                :src="resultIcons[result.icon].src"
                :width="resultIcons[result.icon].width"
                :height="resultIcons[result.icon].height"
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

        <p v-if="props.project.resultsSummary" class="whitespace-pre-line text-heading-sm text-ink-200">
          {{ props.project.resultsSummary }}
        </p>
      </section>

      <!-- 430:5204 — قبل leads (right), بعد follows; captions centred over a 2:1 plate. -->
      <section
        v-if="props.project.beforeAfter.before && props.project.beforeAfter.after"
        class="grid gap-6 md:grid-cols-2"
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
      <section v-if="props.project.next" class="flex flex-col items-start gap-8">
        <p class="text-heading-sm text-ink-200">{{ t('work.next_case_study') }}</p>
        <Link
          :href="props.project.next.url"
          class="group flex items-center gap-4 transition-colors duration-200 ease-brand"
        >
          <span class="text-display-md text-ink-50 transition-colors duration-200 ease-brand group-hover:text-brand">
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

      <StartTogetherCard v-if="props.finalCta" :section="props.finalCta" />
    </div>
  </article>
</template>

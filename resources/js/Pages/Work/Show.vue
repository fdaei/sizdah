<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import SeoHead from '@/Components/SeoHead.vue'
import StartTogetherCard from '@/Components/StartTogetherCard.vue'
import { useTranslations } from '@/Composables/useTranslations'
import type { CardItem, PageSectionData, ProjectDetail, SeoMeta } from '@/types'
import servicesIconUrl from '~img/sizdah/work/meta-services.svg'
import instagramIconUrl from '~img/sizdah/work/meta-instagram.svg'
import yearIconUrl from '~img/sizdah/work/meta-year.svg'
import industryIconUrl from '~img/sizdah/work/meta-industry.svg'
import challengeMarkUrl from '~img/sizdah/work/challenge-mark.svg'
import nextArrowUrl from '~img/sizdah/work/next-arrow.svg'

/**
 * Case study — Figma "case study" 336:5374 (1440x7403).
 *
 * Rebuilt against the real frame; the previous version was DERIVED. The frame
 * is a free-positioned one-off drawn around a single project (استخر چشمه), so
 * this is a faithful reading of its *system* — type ramp, card treatments,
 * section order — rather than a transcription of absolute coordinates, which
 * would not survive a project with a different number of goals or results.
 *
 * Two distinct card treatments, deliberately kept apart:
 *   - goals / deliverables (423:4973) — black base under a 20% black wash and
 *     a raking brand glow (-42.92deg, 0->8%; re-measured 2026-08-24, was flat
 *     `bg-black` with px-24/py-24 and gap-24/gap-8 where the frame draws
 *     px-24/py-56 and gap-40/gap-24), 3px ink-200 border, r16, brand number
 *     at 36/600, brand-50 title, ink-200 body.
 *   - strategy (428:5058) — no fill or border; the number sits outside the copy
 *     at 32/500 brand and the quadrant is divided by 3px brand-200 rules.
 *
 * DEVIATIONS, all forced by the data contract:
 *   - The frame's five result cards each carry their own 32x31 icon. `results`
 *     is `{label, value}` with no icon field, so the cards render without one
 *     rather than guessing a mapping. Adding icons means a schema change.
 *   - 430:5201 puts a chip row beside the showcase heading. Nothing filters the
 *     showcase — it is a flat image list — so only the heading is rendered.
 *   - 428:5044 sits in the middle of the frame carrying the Services page's
 *     package copy ("چرا سیزده" / "سه پکیج…"). It is stray content left in the
 *     file, not part of this page. See .figma-sync/GAPS.md G19.
 *   - The frame closes on "Frame 96467" (577:10538) — the cream card, i.e.
 *     StartTogetherCard. This page used `FinalCtaCard` (the dark card) until
 *     2026-08-21, which was simply the wrong card for this frame; that card
 *     turned out to be wrong everywhere else too and is now deleted (GAPS G34).
 */
const props = defineProps<{
  project: ProjectDetail
  finalCta: PageSectionData | null
  seo: SeoMeta
}>()

const { t } = useTranslations()

const meta = [
  { key: 'services', icon: servicesIconUrl, value: props.project.services.join('، ') },
  { key: 'instagram', icon: instagramIconUrl, value: props.project.instagram },
  { key: 'year', icon: yearIconUrl, value: props.project.year },
  { key: 'industry', icon: industryIconUrl, value: props.project.industry },
].filter((item) => item.value)

// Both use the bordered card; strategy is drawn differently and stays separate.
const cardBlocks: { key: string; items: CardItem[] }[] = [
  { key: 'goals', items: props.project.goals },
  { key: 'deliverables', items: props.project.deliverables },
].filter((block) => block.items.length > 0)

/** 01, 02, … — the frame pads to two digits and localises like any generated number. */
function ordinal(index: number): string {
  return String(index + 1).padStart(2, '0')
}
</script>

<template>
  <SeoHead :seo="props.seo" />

  <article class="section-first pb-24">
    <div class="container-sizdah relative isolate flex flex-col gap-20 md:gap-[120px]">
      <!--
        511:9519 — the same 109px hairline mesh the projects frame uses, 872
        tall here and starting 22px below the content top (frame y=202 vs 180).
      -->
      <div
        class="grid-mesh pointer-events-none absolute inline-start-0 block-start-[22px] -z-10 hidden h-[872px] w-full max-w-container lg:block"
        style="--mesh-cell-x: 109.095px; --mesh-cell-y: 109.095px"
        aria-hidden="true"
      />

      <!--
        411:8560 — a centred 670 track on gap 64 over the 612 column (411:8561),
        whose title block (411:8565) is gap 24. Both text runs are CENTER in the
        frame, and it carries no eyebrow, so this header is centred and the
        kicker is not drawn.
      -->
      <header class="mx-auto flex max-w-[670px] flex-col items-center gap-16">
        <div class="flex w-full max-w-measure flex-col items-center gap-6 text-center">
          <h1 class="text-display-md text-ink-50">{{ props.project.title }}</h1>
          <p v-if="props.project.excerpt" class="text-title-sm text-ink-200">
            {{ props.project.excerpt }}
          </p>
        </div>

        <!-- Meta row 411:8568 — label over value, icon at the inline end. -->
        <dl v-if="meta.length" class="flex flex-wrap justify-center gap-x-16 gap-y-8">
          <div v-for="item in meta" :key="item.key" class="flex items-center gap-4">
            <div class="flex flex-col gap-1">
              <dt class="text-label-lg text-ink-50">{{ t(`work.${item.key}`) }}</dt>
              <dd class="text-body-md text-ink-200 latin-nums">{{ item.value }}</dd>
            </div>
            <img :src="item.icon" alt="" aria-hidden="true" width="24" height="24" />
          </div>
        </dl>
      </header>

      <figure v-if="props.project.banner ?? props.project.image" class="overflow-hidden rounded-xl">
        <img
          :src="(props.project.banner ?? props.project.image)!.src"
          :srcset="(props.project.banner ?? props.project.image)!.srcset"
          :alt="(props.project.banner ?? props.project.image)!.alt"
          :width="(props.project.banner ?? props.project.image)!.width"
          :height="(props.project.banner ?? props.project.image)!.height"
          class="w-full object-cover"
        />
      </figure>

      <!-- Challenge 423:4971 — the 144x144 mark at 611:5944 sits beside it. -->
      <section v-if="props.project.challenge" class="relative flex flex-col gap-6">
        <img
          :src="challengeMarkUrl"
          alt=""
          aria-hidden="true"
          class="hidden size-36 shrink-0 lg:block"
        />
        <h2 class="text-display-md font-semibold text-brand-50">{{ t('work.challenge') }}</h2>
        <p class="max-w-[832px] text-title-lg text-ink-200">{{ props.project.challenge }}</p>

        <ul
          v-if="props.project.challengePoints.length"
          class="flex max-w-[832px] flex-col gap-3 pt-2"
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

      <section v-for="block in cardBlocks" :key="block.key" class="flex flex-col gap-8">
        <h2 class="text-display-md font-semibold text-brand-50">{{ t(`work.${block.key}`) }}</h2>

        <ul class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4" data-reveal-group>
          <li
            v-for="(item, index) in block.items"
            :key="item.title"
            class="flex flex-col items-start gap-10 rounded-lg border-3 border-ink-200 bg-black px-6 py-14"
            style="
              background-image:
                linear-gradient(-42.92deg, rgb(248 185 55 / 0) 2.3248%, rgb(248 185 55 / 8%) 100%),
                linear-gradient(0deg, rgb(0 0 0 / 20%), rgb(0 0 0 / 20%));
            "
            data-reveal
          >
            <span class="text-display-sm font-semibold text-brand latin-nums" aria-hidden="true">
              {{ ordinal(index) }}
            </span>
            <div class="flex flex-col gap-6">
              <h3 class="text-heading-sm text-brand-50">{{ item.title }}</h3>
              <p class="text-title-sm text-ink-200">{{ item.description }}</p>
            </div>
          </li>
        </ul>
      </section>

      <!--
        Strategy quadrant 428:5041-428:5088. The frame divides four bare cards
        with 3px brand-200 rules; the grid reproduces that with divide-* so the
        rules collapse correctly when the grid drops to one column.
      -->
      <section v-if="props.project.strategy.length" class="flex flex-col gap-8">
        <h2 class="text-display-md font-semibold text-brand-50">{{ t('work.strategy') }}</h2>

        <ul
          class="grid divide-y-[3px] divide-brand-200 sm:grid-cols-2 sm:divide-x-[3px]"
          data-reveal-group
        >
          <li
            v-for="(item, index) in props.project.strategy"
            :key="item.title"
            class="flex items-start gap-6 p-8"
            data-reveal
          >
            <span class="text-heading-xl text-brand latin-nums" aria-hidden="true">
              {{ ordinal(index) }}
            </span>
            <div class="flex flex-col gap-2">
              <h3 class="text-heading-sm text-ink-50">{{ item.title }}</h3>
              <p class="text-title-sm text-ink-200">{{ item.description }}</p>
            </div>
          </li>
        </ul>
      </section>

      <section v-if="props.project.showcase.length" class="flex flex-col gap-8">
        <h2 class="text-display-md font-semibold text-brand-50">{{ t('work.showcase') }}</h2>

        <ul class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3" data-reveal-group>
          <li v-for="image in props.project.showcase" :key="image.src" data-reveal>
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
        Results 430:5141 — cream tiles, brand value, then the summary line.
        Re-measured 2026-08-24: px-32/py-16 (was p-6), gap-8 (was gap-3), and
        the label is warm-900 #393637 (was ink-900 #2C2C2C — the cool/warm
        ramp mixup again, see GAPS G35/G39) since the tile sits on gold-100.
      -->
      <section
        v-if="props.project.results.length || props.project.resultsSummary"
        class="flex flex-col gap-8"
      >
        <h2 class="text-display-md font-semibold text-brand-50">{{ t('work.results') }}</h2>

        <ul
          v-if="props.project.results.length"
          class="grid gap-6 sm:grid-cols-2 lg:grid-cols-5"
          data-reveal-group
        >
          <li
            v-for="result in props.project.results"
            :key="result.label"
            class="flex flex-col gap-2 rounded-sm bg-gold-100 px-8 py-4"
            data-reveal
          >
            <span class="text-title-lg text-warm-900">{{ result.label }}</span>
            <span class="text-heading-lg text-brand latin-nums">{{ result.value }}</span>
          </li>
        </ul>

        <p v-if="props.project.resultsSummary" class="text-heading-sm text-ink-200">
          {{ props.project.resultsSummary }}
        </p>
      </section>

      <section
        v-if="props.project.beforeAfter.before && props.project.beforeAfter.after"
        class="grid gap-6 md:grid-cols-2"
      >
        <figure
          v-for="side in ['after', 'before'] as const"
          :key="side"
          class="flex flex-col gap-4"
        >
          <figcaption class="text-heading-xl font-bold text-ink-50">
            {{ t(`work.${side}`) }}
          </figcaption>
          <img
            :src="props.project.beforeAfter[side]!.src"
            :srcset="props.project.beforeAfter[side]!.srcset"
            :alt="props.project.beforeAfter[side]!.alt"
            loading="lazy"
            class="w-full rounded-xl object-cover"
          />
        </figure>
      </section>

      <!-- Next project 430:5212 — label, then arrow + title as one link. -->
      <section v-if="props.project.next" class="flex flex-col items-end gap-8 text-end">
        <p class="text-heading-sm text-ink-200">{{ t('work.next_case_study') }}</p>
        <Link
          :href="props.project.next.url"
          class="group flex items-center gap-4 transition-colors duration-200 ease-brand hover:text-brand"
        >
          <img
            :src="nextArrowUrl"
            alt=""
            aria-hidden="true"
            width="40"
            height="40"
            class="transition-transform duration-200 ease-brand group-hover:-translate-x-2"
          />
          <span class="text-display-md text-ink-50 group-hover:text-brand">
            {{ props.project.next.title }}
          </span>
        </Link>
      </section>

      <StartTogetherCard v-if="props.finalCta" :section="props.finalCta" />
    </div>
  </article>
</template>

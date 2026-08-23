<script setup lang="ts">
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import StartTogetherCard from '@/Components/StartTogetherCard.vue'
import SeoHead from '@/Components/SeoHead.vue'
import type {
  PageSectionData,
  SectionItem,
  SectionMap,
  SeoMeta,
  SharedProps,
  TeamMemberItem,
} from '@/types'
import heroIllustrationUrl from '~img/sizdah/about/hero-team.webp'
import heroScriptUrl from '~img/sizdah/about/hero-script.svg'
import storyMarkUrl from '~img/sizdah/about/story-mark.svg'
import pathIconUrl from '~img/sizdah/about/principle-path.svg'
import impactIconUrl from '~img/sizdah/about/principle-impact.svg'
import identityIconUrl from '~img/sizdah/about/principle-identity.svg'
import discoveryIconUrl from '~img/sizdah/about/principle-discovery.svg'

/**
 * About — Figma 336:5623 (1440x4021).
 *
 * Supersedes the generic section-loop this page used to render: the route had
 * no frame when it was first built (FIGMA/state.json listed it under
 * derivedScreens), and one now exists. Five blocks, all fed by the `about`
 * Page's sections so everything stays editable in Filament:
 *
 *   about_hero    -> 336:5703  headline, lede, illustration
 *   story         -> 357:9335  centred 824px column
 *   how_we_think  -> 411:7908  header row + four principle cards
 *   team          -> 411:7906  header row + member cards (TeamMember rows)
 *   final_cta     -> 577:10889 cream card
 *
 * The frame is authored RTL/Persian, so its inline start is the right edge.
 * Every rule here is logical (`text-start`, `inset-inline-*`, `items-start`)
 * and the layout mirrors itself under LTR rather than being duplicated.
 *
 * DEVIATIONS, all deliberate:
 *  - The hero eyebrow (336:5705 "small title") is hidden in the frame, so
 *    `about_hero.eyebrow` is not rendered.
 *  - 336:5706 and 577:11228 set their eyebrows in Maneli, which is not
 *    licensed here; they take the `font-display` stack at the same size.
 *  - `hero-script.svg` is the hand-drawn Persian word "سیزده", so it is placed
 *    for fa only — en/ar render the headline as plain text.
 *  - The hero copy sits 69px above the illustration's centre in the frame;
 *    it is centred here rather than pinned to a magic offset.
 *  - Section rhythm is a uniform 192px (`lg:gap-48`); the frame runs 189-200
 *    between blocks and 251 before the CTA.
 */
const props = defineProps<{
  sections: SectionMap
  team: TeamMemberItem[]
  seo: SeoMeta
}>()

const page = usePage<SharedProps>()

const hero = computed<PageSectionData | undefined>(() => props.sections.about_hero)
const story = computed<PageSectionData | undefined>(() => props.sections.story)
const think = computed<PageSectionData | undefined>(() => props.sections.how_we_think)
const teamSection = computed<PageSectionData | undefined>(() => props.sections.team)
const finalCta = computed<PageSectionData | undefined>(() => props.sections.final_cta)

/** The word is Persian; en/ar get the headline as text. */
const showHeroScript = computed(() => page.props.locale.current === 'fa')

/*
 | Streamline Freehand glyphs, one per principle card (598:5713, 598:5718,
 | 593:5966, 591:5940). An item's `icon` selects one by name; the seeded items
 | carry no icon, so the frame's own order is the fallback and the section
 | still renders exactly as designed.
 */
const ICONS: Record<string, string> = {
  path: pathIconUrl,
  impact: impactIconUrl,
  identity: identityIconUrl,
  discovery: discoveryIconUrl,
}

/*
 | Reading order, not canvas order: the frame lays the row out left to right as
 | path / impact / identity / discovery, which under RTL is read last-to-first.
 | The seeded items run in reading order, so the fallback does too.
 */
const ICON_ORDER = [discoveryIconUrl, identityIconUrl, impactIconUrl, pathIconUrl]

function iconFor(item: SectionItem, index: number): string | undefined {
  if (item.icon && ICONS[item.icon]) {
    return ICONS[item.icon]
  }

  return ICON_ORDER[index]
}
</script>

<template>
  <SeoHead :seo="props.seo" />

  <div class="section-first pb-24 lg:pb-48">
    <div class="container-sizdah flex flex-col gap-24 lg:gap-48">
      <!--
        Hero — 336:5703 / 343:9292. Copy column (466) at the inline start with
        the 696px illustration opposite; below `lg` they stack, copy first.
      -->
      <section
        v-if="hero"
        class="grid items-center gap-12 lg:grid-cols-[minmax(0,466px)_minmax(0,1fr)] lg:gap-[86px]"
      >
        <div class="flex flex-col items-start gap-6 text-start" data-reveal>
          <h1 class="flex flex-wrap items-center gap-x-2 text-hero-line font-bold text-ink-50">
            <span>{{ hero.title }}</span>
            <img
              v-if="showHeroScript"
              :src="heroScriptUrl"
              alt=""
              aria-hidden="true"
              width="128"
              height="46"
              class="inline-block h-[0.96em] w-auto"
            />
            <span v-if="hero.subtitle">{{ hero.subtitle }}</span>
          </h1>

          <p v-if="hero.content" class="text-heading-md text-brand-200">
            {{ hero.content }}
          </p>

          <p v-if="hero.description" class="text-title-sm text-ink-200">
            {{ hero.description }}
          </p>
        </div>

        <img
          v-if="hero.image"
          :src="hero.image.src"
          :srcset="hero.image.srcset"
          :alt="hero.image.alt"
          :width="hero.image.width"
          :height="hero.image.height"
          class="w-full"
        />
        <img
          v-else
          :src="heroIllustrationUrl"
          alt=""
          aria-hidden="true"
          width="696"
          height="522"
          class="w-full"
        />
      </section>

      <!--
        Story — 357:9335. Centred 824px column over the decorative hairline
        mesh (359:9560, 109x118 cells) with the freehand mark at 583:5905.
      -->
      <section v-if="story" class="relative isolate">
        <div
          class="grid-mesh pointer-events-none absolute inset-inline-start-0 inset-block-start-[-170px] -z-10 hidden h-[944px] w-full max-w-container lg:block"
          style="--mesh-cell-x: 109px; --mesh-cell-y: 118px"
          aria-hidden="true"
        />

        <img
          :src="storyMarkUrl"
          alt=""
          aria-hidden="true"
          width="144"
          height="144"
          class="pointer-events-none absolute inset-inline-end-[24%] inset-block-start-[-52px] -z-10 hidden size-36 lg:block"
        />

        <div class="mx-auto flex max-w-[824px] flex-col items-center gap-10 text-center lg:gap-20">
          <h2 class="text-section-line text-brand-50">{{ story.title }}</h2>

          <div class="flex flex-col gap-6">
            <p v-if="story.description" class="text-title-lg text-ink-200">
              {{ story.description }}
            </p>

            <!-- eslint-disable-next-line vue/no-v-html -- admin-authored rich text -->
            <div v-if="story.content" class="rich-prose" v-html="story.content" />
          </div>
        </div>
      </section>

      <!-- How we think — 411:7908. Header row, then four principle cards. -->
      <section v-if="think" class="flex flex-col gap-10 lg:gap-20">
        <div
          class="flex flex-col items-start gap-4 text-start md:flex-row md:items-center md:justify-between md:gap-16"
        >
          <h2 class="text-section-line text-brand-50">{{ think.title }}</h2>

          <p v-if="think.description" class="max-w-[506px] text-title-md text-ink-200">
            {{ think.description }}
          </p>
        </div>

        <ul
          v-if="think.items.length"
          class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4"
          data-reveal-group
        >
          <!--
            406:7225 and siblings: a raking brand glow over a 20% black wash,
            3px ink-200 rule, radiusLG. The glow runs corner to corner (-45deg
            from the node's gradient handles) and the layer itself sits at 80%.
          -->
          <li
            v-for="(item, index) in think.items"
            :key="item.id"
            class="flex flex-col items-start gap-10 rounded-lg border-3 border-ink-200 px-6 py-14 text-start"
            style="
              background-image:
                linear-gradient(-45deg, rgb(248 185 55 / 0%) 0%, rgb(248 185 55 / 8%) 100%),
                linear-gradient(0deg, rgb(0 0 0 / 20%), rgb(0 0 0 / 20%));
            "
          >
            <img
              v-if="iconFor(item, index)"
              :src="iconFor(item, index)"
              alt=""
              aria-hidden="true"
              width="40"
              height="40"
              class="size-10 shrink-0"
            />

            <div class="flex flex-col gap-6">
              <h3 class="text-heading-sm text-brand-50">{{ item.title }}</h3>
              <p v-if="item.description" class="text-title-sm text-ink-300">
                {{ item.description }}
              </p>
            </div>
          </li>
        </ul>
      </section>

      <!-- Team — 411:7906. Header row, then the member cards (598:5732). -->
      <section v-if="props.team.length" class="flex flex-col gap-10 lg:gap-20">
        <div
          class="flex flex-col items-start gap-4 text-start md:flex-row md:items-center md:justify-between md:gap-16"
        >
          <h2 v-if="teamSection?.title" class="text-section-line text-brand-50">
            {{ teamSection.title }}
          </h2>

          <p v-if="teamSection?.description" class="max-w-[506px] text-title-md text-ink-200">
            {{ teamSection.description }}
          </p>
        </div>

        <ul class="grid gap-[26px] grid-cols-2 sm:grid-cols-3 lg:grid-cols-5" data-reveal-group>
          <!--
            The component ships a hover variant (598:5733): the cream card and
            its two copy steps all swap to brand. Border and fill are the same
            colour, so the 4px rule reads as bleed around the photo.
          -->
          <li
            v-for="member in props.team"
            :key="member.name"
            class="group flex flex-col overflow-hidden rounded-lg border-4 border-brand-200 bg-brand-200 transition-colors duration-200 ease-brand hover:border-brand hover:bg-brand"
          >
            <img
              v-if="member.image"
              :src="member.image.src"
              :srcset="member.image.srcset"
              :alt="member.image.alt"
              :width="member.image.width"
              :height="member.image.height"
              loading="lazy"
              class="aspect-square w-full rounded-lg object-cover object-bottom"
            />
            <div v-else class="aspect-square w-full rounded-lg bg-warm-200" aria-hidden="true" />

            <div class="flex flex-col gap-1 p-4 text-start">
              <p class="text-heading-sm text-warm-900 group-hover:text-ink-1000">
                {{ member.name }}
              </p>
              <p class="text-title-md text-ink-700 group-hover:text-ink-800">
                {{ member.role }}
              </p>
            </div>
          </li>
        </ul>
      </section>

      <!--
        Final CTA — 577:10889. About's own cream card, not the dark shared
        `FinalCtaCard` (294:7672) the services and article frames use. The same
        card is duplicated onto projects (577:9134), hence the component.
      -->
      <StartTogetherCard v-if="finalCta" :section="finalCta" />
    </div>
  </div>
</template>
